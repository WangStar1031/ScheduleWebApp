
<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.117/styles/kendo.common-material.min.css" />
<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.117/styles/kendo.material.min.css" />

<!-- <script src="https://kendo.cdn.telerik.com/2018.1.117/js/jquery.min.js"></script> -->
<script src="https://kendo.cdn.telerik.com/2018.1.117/js/kendo.all.min.js"></script>

<style type="text/css">
	#ScheTempTable, #ScheTempNew{
		width: 100%;
	}
	#ScheTempNew td{
		padding:5px;
	}
	#ScheTempTable td{
		border: 1px solid #333;
	}
	#ScheTempNew{
		 margin-bottom: 20px;
	}
	#multiCalendar th{ color: black!important; }
	#multiCalendar .k-footer{ height: 3em; display: none!important;}
	#multiCalendar table{ font-size: 1em; }
	#multiCalendar .k-header .k-icon{ margin-top:0.5em;}
	#NewScheTempTable td{ padding: 3px; }
	.SelRow{ background-color: #fffcad; }
</style>
<!-- Schedule Template Modal -->
<div class="modal fade" id="scheduleTemplateModal" role="dialog">
	<div class="modal-dialog">		
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3>Schedule Template</h3>
			</div>
			<div class="modal-body">
				<div style="width: 100%; max-height: 250px; overflow: auto; margin-bottom: 20px; border: 2px solid #333;">
					<table id="ScheTempTable">
						<tr>
							<th>Name</th>
							<th>Type</th>
							<th>Time</th>
							<th>Action</th>
						</tr>
					</table>
				</div>
				<div style="width: 100%; border: 1px solid #333; padding: 10px;margin-bottom: 15px;">
					<div class="row">
						<div class="col-lg-5 col-md-5 col-xs-5">
							<h4 style="text-align: center;">New Schedule Template</h4>
							<table id="NewScheTempTable">
								<tr>
									<td><label>Schedule Name</label></td>
								</tr>
								<tr>
									<td><input type="text" id="ScheTempName"></td>
								</tr>
								<tr>
									<td><label>Schedule Type</label></td>
								</tr>
								<tr>
									<td>
										<select id="ScheTempType" onchange="ScheTempTypeChanged()">
											<option>From/Till</option>
											<option>Start/Working</option>
										</select>
									</td>
								</tr>
								<tr class="TimePicker forWeekDay HideItem">
									<td>
										<input type="time" id="ScheTempFromTime" class="FirstTime">
										<input type="time" id="ScheTempTillTime" class="LastTime">
									</td>
								</tr>
								<tr class="TimePicker forOthers">
									<td>
										<input type="time" id="ScheTempStartTime" class="FirstTime"> hrs 
										<input type="time" id="ScheTempWorkTime" class="LastTime">
									</td>
								</tr>
							</table>
							<button style="margin-top: 20px;" onclick="UpdateScheTemplate()">Update Schedule Template</button>
						</div>
						<div class="col-lg-7 col-md-7 col-xs-7">
							<div class="k-content" style="text-align: center;">
								<div id="multiCalendar"></div>
							</div>
						</div>
					</div>

				</div>
				<button onclick="AddNewScheTemplate()">Add New Schedule Template</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" onclick="ScheTempSet()">OK</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>			
	</div>
</div>

<script type="text/javascript">
	var calendar;
	$(document).ready(function() {
		// create Calendar from div HTML element
		$("#multiCalendar").kendoCalendar({
		selectable: "multiple",
		weekNumber: true,
		// disableDates: ["we", "sa"]
		});
		calendar = $("#multiCalendar").data("kendoCalendar");
	});
	function ScheduleTemplateOpen(){
		calendar.selectDates([]);
		$("#ScheTempName").val("");
		$("#ScheTempType").val("");
		$(".FirstTime").val("");
		$(".LastTime").val("");
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getScheTemplate: nIdTreeInfo}
		}).done(function (d) {
			var arrScheTempls = JSON.parse(d);
			$("#ScheTempTable tr").filter(function(index){
				return index > 0;
			}).remove();
			var strHtml = "";
			for( var i = 0; i < arrScheTempls.length; i++){
				var ScheTemp = arrScheTempls[i];
				strHtml += "<tr onclick='selectSchTemplate("+ScheTemp['Id']+")'>";
					strHtml += "<td class='HideItem'>"+ScheTemp['Id']+"</td>";
					strHtml += "<td>"+ScheTemp['strName']+"</td>";
					strHtml += "<td>"+ScheTemp['strType']+"</td>";
					strHtml += "<td class='HideItem'>"+ScheTemp['strPeriod']+"</td>";
					strHtml += "<td>"+ScheTemp['strTime']+"</td>";
					strHtml += "<td><button style='width:100%;' onclick='delScheTemp(this)'>delete</button></td>";
				strHtml += "</tr>";
			}
			$("#ScheTempTable tr:last").after(strHtml);
		});
	}
	function ScheTempTypeChanged(){
		var strScheType = $("#ScheTempType").val();
		var nScheTypeIdx = $("#ScheTempType").prop("selectedIndex");
		$(".TimePicker").addClass("HideItem");
		if( nScheTypeIdx == 1){
			$(".TimePicker").eq(0).removeClass("HideItem");
		} else{
			$(".TimePicker").eq(1).removeClass("HideItem");
		}
	}
	function AddNewScheTemplate(){
		var strName = $("#ScheTempName").val();
		var strType = $("#ScheTempType").val();
		var nScheTypeIdx = $("#ScheTempType").prop("selectedIndex");
		var strTime = "";
		if( nScheTypeIdx == 1){
			strTime = $("#ScheTempFromTime").val() + "-" + $("#ScheTempTillTime").val();
		} else{
			strTime = $("#ScheTempStartTime").val() + "-" + $("#ScheTempWorkTime").val();
		}
		if( strName == "" || strTime == ""){
			alert("Please insert the Values.");
			return;
		}
		var arrSelectedDates = [];
		// arrSelectedDates = calendar.selectDates();
		if( calendar.selectDates().length == 0){
			alert("Please select the dates.");
			return;
		}
		for( var i = 0; i < calendar.selectDates().length; i++){
			var nMonth = 1 + calendar.selectDates()[i].getMonth();
			var nDay = calendar.selectDates()[i].getDate();
			nMonth = (nMonth > 9 ? nMonth : "0" + nMonth);
			nDay = (nDay > 9 ? nDay : "0" + nDay);
			var strDate = (1900 + calendar.selectDates()[i].getYear()) + "-" + nMonth + "-" + nDay
			arrSelectedDates.push(strDate);
			// arrSelectedDates[i] = (1900 + arrSelectedDates[i].getYear()) + "-" + nMonth + "-" + nDay;
		}
		for( var i = 1; i < $("#ScheTempTable tr").length; i ++){
			if($("#ScheTempTable tr").eq(i).find("td").eq(1).html() == strName){
				alert("Existing Schedule Name.");
				return;
			}
		}
		var strHtml = "";
		strHtml += "<tr>";
			strHtml += "<td class='HideItem'>0</td>";
			strHtml += "<td>" + strName + "</td>";
			strHtml += "<td>" + strType + "</td>";
			strHtml += "<td class='HideItem'>"+arrSelectedDates.join(",")+"</td>";
			strHtml += "<td>" + strTime + "</td>";
			strHtml += "<td><button style='width:100%;' onclick='delScheTemp(this)'>delete</button></td>";
		strHtml += "</tr>";
		$("#ScheTempTable tr:last").after(strHtml);
	}
	function delScheTemp(_this){		
		var r = confirm("Are you sure delete current item?");
		if( r != true){
			return;
		}
		var elemTr = $("#ScheTempTable tr").eq(_this.parentElement.parentElement.rowIndex);
		var Id = elemTr.find("td").eq(0).html();
		if( Id == 0){
			elemTr.remove();
			return;
		}
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {deleteScheTemplate: Id}
		}).done(function (d) {
			elemTr.remove();
		});
	}
	function ScheTempSet(){
		var elemTr = $("#ScheTempTable tr");
		var arrScheTempls = [];
		for( var i = 1; i < elemTr.length; i++){
			var Elem = elemTr.eq(i);
			var arrTrHtmls = [];
			for(var j = 0; j < 5; j++){
				arrTrHtmls.push( Elem.find("td").eq(j).html());
			}
			arrScheTempls.push( arrTrHtmls.join("@@@"));
		}
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {updateScheTemplate: arrScheTempls.join("$$$"), idCompany:nIdTreeInfo}
		}).done(function (d) {
			$("#scheduleTemplateModal").modal("toggle");
			getScheduleTemplates(nIdTreeInfo, false);
		});
	}
	function selectSchTemplate(nIdSch){
		$("#ScheTempTable tr").removeClass("SelRow");
		var selectedTr = $("#ScheTempTable tr").filter(function(index){
			return $("#ScheTempTable tr").eq(index).find("td").eq(0).html() == nIdSch;
		});
		selectedTr.addClass("SelRow");
		var strId = selectedTr.find("td").eq(0).html();
		var strName = selectedTr.find("td").eq(1).html();
		var strType = selectedTr.find("td").eq(2).html();
		var strPeriod = selectedTr.find("td").eq(3).html();
		var strTime = selectedTr.find("td").eq(4).html();
		var arrBuf = strTime.split("-");
		var strFirstTime = arrBuf[0];
		var strLastTime = arrBuf[1];
		$("#ScheTempName").val(strName);
		$("#ScheTempType").val(strType);
		$(".FirstTime").val(strFirstTime);
		$(".LastTime").val(strLastTime);
		var arrDates = strPeriod.split(",");
		var dateVal = [];
		for(var i = 0; i < arrDates.length; i++){
			dateVal.push(new Date(arrDates[i]));
		}
		calendar.selectDates(dateVal);
	}
	function UpdateScheTemplate(){
		var selectedTr = $("#ScheTempTable tr.SelRow").eq(0);
		var strName = $("#ScheTempName").val();
		var otherTrs = $("#ScheTempTable tr").filter(function(index){
			return !($("#ScheTempTable tr").hasClass("SelRow"));
		});
		for(var i = 0; i < otherTrs.length; i++){
			var curTr = otherTrs.eq(i);
			var strCurName = curTr.find("td").eq(1).html();
			if( strName == strCurName){
				alert("Existing Schedule template name.");
				return;
			}
		}
		var strType = $("#ScheTempType").val();
		var nScheTypeIdx = $("#ScheTempType").prop("selectedIndex");
		var strTime = "";
		if( nScheTypeIdx == 1){
			strTime = $("#ScheTempFromTime").val() + "-" + $("#ScheTempTillTime").val();
		} else{
			strTime = $("#ScheTempStartTime").val() + "-" + $("#ScheTempWorkTime").val();
		}
		var arrPeriod = [];
		if( strName == "" || strTime == ""){
			alert("Please insert the Values.");
			return;
		}
		var arrSelectedDates = [];
		if( calendar.selectDates().length == 0){
			alert("Please select the dates.");
			return;
		}
		for( var i = 0; i < calendar.selectDates().length; i++){
			var nMonth = 1 + calendar.selectDates()[i].getMonth();
			var nDay = calendar.selectDates()[i].getDate();
			nMonth = (nMonth > 9 ? nMonth : "0" + nMonth);
			nDay = (nDay > 9 ? nDay : "0" + nDay);
			var strDate = (1900 + calendar.selectDates()[i].getYear()) + "-" + nMonth + "-" + nDay
			arrSelectedDates.push(strDate);
		}
		selectedTr.find("td").eq(1).html(strName);
		selectedTr.find("td").eq(2).html(strType);
		selectedTr.find("td").eq(3).html(arrSelectedDates.join(","));
		selectedTr.find("td").eq(4).html(strTime);

	}
</script>