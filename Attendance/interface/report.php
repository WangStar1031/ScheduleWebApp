
<style type="text/css">
	.treeViewReport{ list-style: none; height: 180px; overflow: auto; background-color: white; border: 1px solid black; margin-top: 20px; padding-left: 0px; }
	.detailReport, .DateRange { margin-top: 40px; }
	.treeViewReport li:first-child{ font-weight: bold; background-color: #333; color: white; padding-bottom: 25px; }
	.treeViewReport li{ padding: 10 0 10 0; cursor: pointer; width: 100%; }
	.spacePadding{ padding-left: 20px; }
	.treeViewIdReport, .treeViewParentIdReport, .treeViewDepthReport, .treeViewChildLoadedReport{ display: none; }
	.treeViewName{ margin-left: 10px; }
	.treeViewSelected{ background-color: #dcf; color: black; }
	#DetailsReportTable{ background-color: white; }
	#DetailsReportTable td{ border: 1px solid #333; text-align: center; min-width: 1.3em; width: 2em;}
</style>
<div class="col-lg-3 col-md-4 col-xs-12">
	<ul class="treeViewReport">
		<li>
			<div class="row col-lg-12 col-md-12 col-xs-12">
				<div class="col-lg-12 col-md-12 col-xs-12">Name</div>
			</div>
		</li>
		<?php
			$arrCompanies = getTreeChildInfo(0);
			for( $i = 0; $i < count($arrCompanies); $i++){
				$Id = $arrCompanies[$i]['Id'];
				$strName = $arrCompanies[$i]['strName'];
				$Category = $arrCompanies[$i]['Category'];
				$ChildCount = $arrCompanies[$i]['ChildCount'];
				$icon = getIconForCategory($Category);
		?>
		<li onclick="treeItemClickedReport(<?= $Id ?>)">
			<div class="row col-lg-12 col-md-12 col-xs-12">
				<div class="col-lg-12 col-md-12 col-xs-12">
					<span class="<?php if($ChildCount == 0) echo 'HideItem'; ?> isChildNode" onclick="expandItemReport(<?= $Id ?>)"><i class="fa fa-plus" aria-hidden="true"></i></span>
					<span class="<?php if($ChildCount > 0) echo 'HideItem'; ?> isChildNode" onclick="collapseItemReport(<?= $Id ?>)"><i class="fa fa-minus" aria-hidden="true"></i></span>
					<span><?= $icon ?></span><span class="treeViewName"><?= $strName ?></span>
				</div>
				<div class="treeViewIdReport"><?= $Id ?></div>
				<div class="treeViewParentIdReport">0</div>
				<div class="treeViewDepthReport">0</div>
				<div class="treeViewChildLoadedReport">0</div>
			</div>		
		</li>
		<?php
			}
		?>
	</ul>
</div>
<div class="col-lg-9 col-md-8 col-xs-12">
	<div class="detailReport">
		<button type="button" class="btn btn-secondary" id="btnAllView" onclick="btnAllViewClicked()">All View</button>
	</div>
	<br>
	<div class="btn-group ReportCategory">
		<button class="btn btn-primary" onclick="onReportCategory(0)">Report By Day</button>
		<button class="btn btn-secondary" onclick="onReportCategory(1)">Delay Report</button>
	</div>
	<br>
	<div class="DateRange">
		<label>Date Range: </label>
		<input type="text" name="daterange" value="" id="dateRangeReport" />
		<button class="btn btn-success" onclick="displayReport()">Display</button>
		<button class="btn btn-success" onclick="printReport()">Print</button>
	</div>
</div>

<div class="col-lg-12 col-md-12 col-xs-12" style="margin-top: 20px; overflow: auto;" id="DetailsReportTableContainer">
	<table id="DetailsReportTable">

	</table>
</div>
<script type="text/javascript">
	var nIdTreeInfoReport = -1;
	var nCurrentItemDepthReport = 0;
	var isAllView = false;
	var nReportCat = 0;
	var strCategory = "";
	var strCurrentCompanyName = "";
	function treeItemClickedReport(nId){
		if( nIdTreeInfoReport == nId)
			return;
		nIdTreeInfoReport = nId;
		var elemLis = $(".treeViewReport li");
		for( var i = 0; i < elemLis.length; i++){
			var ElemLi = elemLis.eq(i);
			ElemLi.find("div").eq(0).removeClass("treeViewSelected");
			if( ElemLi.find(".treeViewIdReport").html() == nIdTreeInfoReport){
				ElemLi.find("div").eq(0).addClass("treeViewSelected");
				nCurrentItemDepthReport = ElemLi.find(".treeViewDepthReport").eq(0).html() * 1;
				if( nCurrentItemDepthReport == 0){
					strCurrentCompanyName = ElemLi.find(".treeViewName").eq(0).html();
				}
			}
		}
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getTreeInfo: nId}
		}).done(function (d) {
			console.log(d);
			strCategory = JSON.parse(d)['Category'];
			console.log(strCategory);
		});
	}
	function expandItemReport(nId){
		var elemLis = $(".treeViewReport li");
		for ( var i = 1; i < elemLis.length; i++){
			var elemLi = elemLis.eq(i);
			var nCurId = elemLi.find(".treeViewIdReport").html();
			if( nId != nCurId) continue;
			var curLi = elemLi;
			curLi.find(".isChildNode").eq(0).addClass("HideItem");
			curLi.find(".isChildNode").eq(1).removeClass("HideItem");
			var isLoaded = elemLi.find(".treeViewChildLoadedReport").html();
			if( isLoaded == 0){
				$.ajax({
					type: 'POST',
					datatype:'JSON',
					url: './utils/dbAjax.php',
					data: {getTreeChildInfo: nId}
				}).done(function (d) {
					var nDepth = curLi.find(".treeViewDepthReport").eq(0).html();
					var retVal = JSON.parse(d);
					console.log(retVal);
					var strHtml = '';
					for( var j = 0; j < retVal.length; j++){
						var oneNode = retVal[j];
						strHtml += makeNodeReport(oneNode, nDepth*1+1);
						// console.log(strHtml);
					}
					curLi.after(strHtml);
				});				
			} else{

			}
		}
	}
	function collapseItemReport(nId){
		var curElemLi = $(".treeViewReport li").filter(function(index){
			return ($(".treeViewReport li").eq(index).find(".treeViewIdReport").eq(0).html() == nId);
		}).eq(0);
		var ElemLis = $(".treeViewReport li").filter(function(index){
			return ($(".treeViewReport li").eq(index).find(".treeViewParentIdReport").eq(0).html() == nId);
		});
		for( var i = 0; i < ElemLis.length; i++){
			deleteChildNodesReport(ElemLis.eq(0).find(".treeViewIdReport").html());
		}
		ElemLis.remove();
		if( ElemLis.length == 0)return;
		var curElemLi = $(".treeViewReport li").filter(function(index){
			return ($(".treeViewReport li").eq(index).find(".treeViewIdReport").eq(0).html() == nId);
		}).eq(0);
		curElemLi.find(".isChildNode").eq(0).removeClass("HideItem");
		curElemLi.find(".isChildNode").eq(1).addClass("HideItem");
	}
	function deleteChildNodesReport(nId){
		$(".treeViewReport li").filter(function(index){
			return ($(".treeViewReport li").eq(index).find(".treeViewParentIdReport").eq(0).html() == nId);
		}).remove();
	}
	function makeNodeReport(oneNode, nDepth){
		var strRetVal = "";
		console.log(oneNode['ChildCount']);
		strRetVal += '<li onclick="treeItemClickedReport('+oneNode['Id']+')">';
			strRetVal += '<div class="row col-lg-12 col-md-12 col-xs-12">';
				strRetVal += '<div class="col-lg-12 col-md-12 col-xs-12">';
				for( var jj = 0; jj < nDepth; jj++){
					strRetVal += '<span class="spacePadding"> </span>';
				}
					strRetVal += '<span class="'+(oneNode['ChildCount']=="0" ? "HideItem" : "")+' isChildNode" onclick="expandItemReport('+oneNode['Id']+')"><i class="fa fa-plus" aria-hidden="true"></i></span>';
					strRetVal += '<span class="'+(oneNode['ChildCount']=="0" ? "" : "HideItem")+' isChildNode" onclick="collapseItemReport('+oneNode['Id']+')"><i class="fa fa-minus" aria-hidden="true"></i></span> ';
					strRetVal += '<span>'+getIconForCategory(oneNode['Category'])+'</span><span class="treeViewName">'+oneNode['strName']+'</span> ';
				strRetVal += '</div>';
				strRetVal += '<div class="treeViewIdReport">'+oneNode['Id']+'</div>';
				strRetVal += '<div class="treeViewParentIdReport">'+oneNode['idParents']+'</div>';
				strRetVal += '<div class="treeViewDepthReport">'+nDepth+'</div>';
				strRetVal += '<div class="treeViewChildLoadedReport">0</div>';
			strRetVal += '</div>';
		strRetVal += '</li>';
		return strRetVal;
	}
	function btnAllViewClicked(){
		isAllView = !isAllView;
		if( isAllView == true){
			$("#btnAllView").removeClass("btn-secondary").addClass("btn-primary");
		} else{
			$("#btnAllView").addClass("btn-secondary").removeClass("btn-primary");
		}
	}
	function onReportCategory(nCat){
		nReportCat = nCat;
		$(".ReportCategory button").removeClass("btn-primary").addClass("btn-secondary");
		$(".ReportCategory button").eq(nCat).removeClass("btn-secondary").addClass("btn-primary");
	}
	function displayReport(){
		if( nIdTreeInfoReport == -1)return;
		var strDateRange = $("#dateRangeReport").val();
		var arrDates = strDateRange.split(" - ");

		var fromDate = new Date(arrDates[0]);
		var tillDate = new Date(arrDates[1]);
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getReport: nIdTreeInfoReport, isAllView:isAllView, reportCat:nReportCat, fromDate:arrDates[0], tillDate:arrDates[1]}
		}).done(function (d) {
			console.log(d);
			var arrTableContents = JSON.parse(d);

			$("#DetailsReportTable tr").remove();
			strHtml = "";

			var date1 = new Date(fromDate);
			var date2 = new Date(tillDate);
			var timeDiff = Math.abs(date2.getTime() - date1.getTime());
			var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
			var columnCount = diffDays + 7;
			strHtml += '<tr><td colspan="' + columnCount + '">' + 'Company "' + strCurrentCompanyName + '"</td></tr>';
			var arrBranches = [];
			for( var i = 0; i < arrTableContents.length; i ++){
				var curContent = arrTableContents[i];
				if( arrBranches.indexOf(curContent.Branch) == -1){
					arrBranches.push(curContent.Branch);
				}
			}
			for( var i = 0; i < arrBranches.length; i++){
				strHtml += '<tr><td style="text-align:left; background-color:#cbffb9;" colspan="' + columnCount + '">' + 'Branch: "' + arrBranches[i] + '"</td></tr>';
				var arrBranchContents = arrTableContents.filter(Contents => Contents.Branch == arrBranches[i]);
				var arrDepartments = [];
				for( var j = 0; j < arrBranchContents.length; j++){
					var curContent = arrBranchContents[j];
					if( arrDepartments.indexOf(curContent.Department) == -1){
						arrDepartments.push(curContent.Department);
					}
				}
				for( var j = 0; j < arrDepartments.length; j++){
					var arrDepartmentContents = arrBranchContents.filter(Contents => Contents.Department == arrDepartments[j]);
					strHtml += '<tr><td style="text-align:left; background-color:#ffce85;" colspan="' + columnCount + '">' + 'Department: "' + arrDepartments[j] + '"</td></tr>';
					var arrPosts = [];
					for( var k = 0; k < arrDepartmentContents.length; k++){
						var curContent = arrDepartmentContents[k];
						if( arrPosts.indexOf(curContent.post) == -1){
							arrPosts.push(curContent.post);
						}
					}
					for( var k = 0; k < arrPosts.length; k++){
						var arrMembers = arrDepartmentContents.filter(Contents => Contents.post == arrPosts[k]);
						strHtml += '<tr><td style="text-align:left;" colspan="' + columnCount + '">' + 'Post: "' + arrPosts[k] + '"</td></tr>';
						if( i == 0 && j == 0 && k == 0){
							strHtml += '<tr>';
								strHtml += '<td rowspan="2">Name</td>';
								strHtml += '<td rowspan="2">Surname</td>';
								strHtml += getMonthString(fromDate, tillDate);
							strHtml += '</tr>';
							strHtml += '<tr>';
							strHtml += getDayString(fromDate, tillDate);
							strHtml += '</tr>';
						}
						for( var l = 0; l < arrMembers.length; l++){
							strHtml += '<tr>';
								strHtml += '<td>'+arrMembers[l]['Name']+'</td>';
								strHtml += '<td>'+arrMembers[l]['SurName']+'</td>';
								var BA = 0, A = 0, SA = 0, SB = 0;
								for( var date = new Date(fromDate); date <= tillDate; date.setDate(date.getDate()+1)){
									var strCol = (date.getYear() + 1900) + "-" + (date.getMonth() >= 9 ? date.getMonth() + 1 : "0" + (date.getMonth() + 1)) + "-" + (date.getDate() > 9 ? date.getDate() : "0" + date.getDate());
									if( !arrMembers[l][strCol]){
										strHtml += "<td>-</td>";
									} else{
										var strContents = arrMembers[l][strCol];
										var arrBuf = strContents.split("@@@");
										if( arrBuf.length == 1){
											strHtml += "<td>"+arrMembers[l][strCol] + "</td>";
										} else{
											strHtml += "<td style='background-color:#fcc;'>"+arrBuf[0] + "</td>";
										}
									}
								}
								strHtml += '<td style="background-color:lightyellow;"></td>';
								strHtml += '<td style="background-color:lightyellow;"></td>';
								strHtml += '<td style="background-color:lightyellow;"></td>';
								strHtml += '<td style="background-color:lightyellow;"></td>';
							strHtml += '</tr>';						
						}
	
					}
				}
			}

			// strHtml += "<tr>";
			// if( isAllView){
			// 	if( strCategory == "Company" || strCategory == "company"){
			// 		strHtml += "<th colspan='2'>Part</th>";
			// 	} else if( strCategory == "Branch" || strCategory == "branch"){
			// 		strHtml += "<th>Part</th>";
			// 	}
			// }
			// strHtml += "<th>Name</th>";
			// strHtml += "<th>Post</th>";
			// for( var date = new Date(fromDate); date <= tillDate; date.setDate(date.getDate()+1)){
			// 	strHtml += "<th>"+(date.getYear() + 1900) + " / " + (date.getMonth() + 1) + " / " + (date.getDate()) + "</th>";
			// }
			// strHtml += "</tr>";
			// for( var i = 0; i < arrTableContents.length; i++){
			// 	var tableContents = arrTableContents[i];
			// 	strHtml += "<tr>";
			// 		if( isAllView){
			// 			if( strCategory == "Company" || strCategory == "company"){
			// 				if( tableContents['Branch'] == ""){
			// 					strHtml += "<td colspan='2'></td>";
			// 				} else if(tableContents['Department'] == ""){
			// 					strHtml += "<td colspan='2'>"+tableContents['Branch']+"</td>";
			// 				} else{
			// 					strHtml += "<td>"+tableContents['Branch']+"</td>";
			// 					strHtml += "<td>"+tableContents['Department']+"</td>";
			// 				}
			// 			} else if( strCategory == "Branch" || strCategory == "branch"){
			// 				strHtml += "<td>"+tableContents['Department']+"</td>";
			// 			}
			// 		}
			// 		strHtml += "<td>" + tableContents['Name'] + "</td>";
			// 		strHtml += "<td>" + tableContents['post'] + "</td>";
			// 		for( var date = new Date(fromDate); date <= tillDate; date.setDate(date.getDate()+1)){
			// 			var strCol = (date.getYear() + 1900) + "-" + (date.getMonth() >= 9 ? date.getMonth() + 1 : "0" + (date.getMonth() + 1)) + "-" + (date.getDate() > 9 ? date.getDate() : "0" + date.getDate());
			// 			if( !tableContents[strCol]){
			// 				strHtml += "<td>-</td>";
			// 			} else{
			// 				var strContents = tableContents[strCol];
			// 				var arrBuf = strContents.split("@@@");
			// 				if( arrBuf.length == 1){
			// 					strHtml += "<td>"+tableContents[strCol] + "</td>";
			// 				} else{
			// 					strHtml += "<td style='background-color:#fcc;'>"+arrBuf[0] + "</td>";
			// 				}
			// 			}
			// 		}
			// 	strHtml += "</tr>";
			// }
			// console.log(strHtml);
			$("#DetailsReportTable").html(strHtml);
			// reportTableOrganize();
		});
	}
	function reportTableOrganize(){
		var elemTrs = $("#DetailsReportTable tr");
		var strCell = $("#DetailsReportTable tr").eq(1).find("td").eq(0).html();
		var nColspan = $("#DetailsReportTable tr").eq(1).find("td").eq(0).prop("colspan");
		var nStartSpan = 1;
		for( var i = 2; i < elemTrs.length; i++){
			var strCurCell = $("#DetailsReportTable tr").eq(i).find("td").eq(0).html();
			var nCurColspan = $("#DetailsReportTable tr").eq(i).find("td").eq(0).prop("colspan");
			// var colspan = 
			if( strCurCell == strCell && nColspan == nCurColspan){
				$("#DetailsReportTable tr").eq(nStartSpan).find("td").eq(0).prop("rowspan", i - nStartSpan + 1);
				$("#DetailsReportTable tr").eq(i).find("td").eq(0).remove();
			} else{
				strCell = strCurCell;
				nStartSpan = i;
				nColspan = nCurColspan;
			}
		}
		if( strCategory != "Company"){
			return;
		}
		var arrOrgs = $("#DetailsReportTable tr").filter(function(index){
			return $("#DetailsReportTable tr").eq(index).find("td").eq(0).prop("rowspan") != 1 && $("#DetailsReportTable tr").eq(index).find("td").eq(0).prop("colspan") == 1;
		});
		console.log(arrOrgs);

		for( var i = 0; i < arrOrgs.length; i++){
			var nCount = arrOrgs.find("td").eq(0).prop("rowspan");
			console.log(arrOrgs.index());
			var strCell = arrOrgs.find("td").eq(1).html();
			var nStartSpan = arrOrgs.index();
			// debugger;
			for( var j = arrOrgs.index() + 1; j < arrOrgs.index() + nCount; j++ ){
				var strCurCell = $("#DetailsReportTable tr").eq(j).find("td").eq(0).html();
				if( strCell == strCurCell ){
					$("#DetailsReportTable tr").eq(nStartSpan).find("td").eq(1).prop("rowspan", j - nStartSpan + 1);
					$("#DetailsReportTable tr").eq(j).find("td").eq(0).remove();
				} else{
					strCell = strCurCell;
					nStartSpan = j;
				}
			}
		}
	}
	function printReport(){		
		var restorepage = document.body.innerHTML;
		var strStyle = '<style type="text/css"> #DetailsReportTable{-webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px;} #DetailsReportTable td{  border: 1px solid #333; text-align: center; } #DetailsReportTable th{ background-color:black; color:white; font-weight:bold; font-size:1.1em; border: 1px solid #333;}</style>';
		var printcontent = document.getElementById("DetailsReportTableContainer").innerHTML;
		document.body.innerHTML = strStyle+printcontent;
		window.print();
		document.body.innerHTML = restorepage;
		$('input[name="daterange"]').daterangepicker();
	}
	function getMonthString(fromDate, tillDate){
		var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
		var arrMonths = [];
		var arrMonthCount = [];
		var strHtml = "";
		for( var date = new Date(fromDate); date <= tillDate; date.setDate(date.getDate() + 1)){
			var nMonth = date.getMonth();
			if( arrMonths.indexOf(nMonth) == -1){
				arrMonths.push(nMonth);
				arrMonthCount[arrMonths.length - 1] = 0;
			}
			arrMonthCount[ arrMonths.length - 1] ++;
		}
		var i;
		for( i = 0; i < arrMonths.length; i++){
			strHtml += '<td style="background-color:'+(i%2 ? 'darkgray;' : 'lightyellow;')+'" colspan="'+arrMonthCount[i]+'">'+monthNames[arrMonths[i]]+'</td>';
		}
		strHtml += '<td style="background-color:'+(i%2 ? 'darkgray;' : 'lightyellow;')+'" colspan="4">Sum</td>';
		return strHtml;
	}
	function getDayString(fromDate, tillDate){
		var strHtml = "";
		for( var date = new Date(fromDate); date <= tillDate; date.setDate(date.getDate() + 1)){
			var nDate = date.getDate();
			strHtml += '<td style="'+(date.getDay() == 0 || date.getDay() == 6 ? 'color:red;' : '')+'">'+nDate+'</td>';
		}
		strHtml += '<td style="background-color:lightyellow;">BA<td style="background-color:lightyellow;">A</td><td style="background-color:lightyellow;">SA</td><td style="background-color:lightyellow;">SB</td>';
		return strHtml;
	}
</script>