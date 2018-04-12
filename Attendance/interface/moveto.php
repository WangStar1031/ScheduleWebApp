
<style type="text/css">
	.treeViewMoveto{ list-style: none; height: 250px; overflow: auto; background-color: white; border: 1px solid black; margin-top: 20px; padding-left: 0px; }
	.detailReport, .DateRange { margin-top: 20px; }
	.treeViewMoveto li:first-child{ font-weight: bold; background-color: #333; color: white; padding-bottom: 25px; }
	.treeViewMoveto li{ padding: 15 0 15 0; cursor: pointer; width: 100%; }
	.spacePadding{ padding-left: 20px; }
	.treeViewIdMoveto, .treeViewParentIdMoveto, .treeViewDepthMoveto, .treeViewChildLoadedMoveto{ display: none; }
	.treeViewName{ margin-left: 10px; }
	.treeViewSelected{ background-color: #dcf; color: black; }
	#DetailsReportTable{ background-color: white; }
	#DetailsReportTable td{ border: 1px solid #333; text-align: center; }
</style>

<div class="col-lg-12 col-md-12 col-xs-12">
	<ul class="treeViewMoveto">
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
		<li onclick="treeItemClickedMoveto(<?= $Id ?>)">
			<div class="row col-lg-12 col-md-12 col-xs-12">
				<div class="col-lg-12 col-md-12 col-xs-12">
					<span class="<?php if($ChildCount == 0) echo 'HideItem'; ?> isChildNode" onclick="expandItemMoveto(<?= $Id ?>)"><i class="fa fa-plus" aria-hidden="true"></i></span>
					<span class="<?php if($ChildCount > 0) echo 'HideItem'; ?> isChildNode" onclick="collapseItemMoveto(<?= $Id ?>)"><i class="fa fa-minus" aria-hidden="true"></i></span>
					<span><?= $icon ?></span><span class="treeViewName"><?= $strName ?></span>
				</div>
				<div class="treeViewIdMoveto"><?= $Id ?></div>
				<div class="treeViewParentIdMoveto">0</div>
				<div class="treeViewDepthMoveto">0</div>
				<div class="treeViewChildLoadedMoveto">0</div>
			</div>		
		</li>
		<?php
			}
		?>
	</ul>
</div>


<script type="text/javascript">
	var nIdTreeInfoMoveto = -1;
	var nCurrentItemDepthMoveto = 0;
	function treeItemClickedMoveto(nId){
		if( nIdTreeInfoMoveto == nId)
			return;
		nIdTreeInfoMoveto = nId;
		var elemLis = $(".treeViewMoveto li");
		for( var i = 0; i < elemLis.length; i++){
			var ElemLi = elemLis.eq(i);
			ElemLi.find("div").eq(0).removeClass("treeViewSelected");
			if( ElemLi.find(".treeViewIdMoveto").html() == nIdTreeInfoMoveto){
				ElemLi.find("div").eq(0).addClass("treeViewSelected");
				nCurrentItemDepthMoveto = ElemLi.find(".treeViewDepth").eq(0).html() * 1;
			}
		}
	}
	function expandItemMoveto(nId){
		var elemLis = $(".treeViewMoveto li");
		for ( var i = 1; i < elemLis.length; i++){
			var elemLi = elemLis.eq(i);
			var nCurId = elemLi.find(".treeViewIdMoveto").html();
			if( nId != nCurId) continue;
			var curLi = elemLi;
			curLi.find(".isChildNode").eq(0).addClass("HideItem");
			curLi.find(".isChildNode").eq(1).removeClass("HideItem");
			var isLoaded = elemLi.find(".treeViewChildLoadedMoveto").html();
			if( isLoaded == 0){
				$.ajax({
					type: 'POST',
					datatype:'JSON',
					url: './utils/dbAjax.php',
					data: {getTreeChildInfo: nId}
				}).done(function (d) {
					var nDepth = curLi.find(".treeViewDepthMoveto").eq(0).html();
					var retVal = JSON.parse(d);
					console.log(retVal);
					var strHtml = '';
					for( var j = 0; j < retVal.length; j++){
						var oneNode = retVal[j];
						strHtml += makeNodeMoveto(oneNode, nDepth*1+1);
						// console.log(strHtml);
					}
					curLi.after(strHtml);
				});				
			} else{

			}
		}
	}
	function collapseItemMoveto(nId){
		var curElemLi = $(".treeViewMoveto li").filter(function(index){
			return ($(".treeViewMoveto li").eq(index).find(".treeViewIdMoveto").eq(0).html() == nId);
		}).eq(0);
		var ElemLis = $(".treeViewMoveto li").filter(function(index){
			return ($(".treeViewMoveto li").eq(index).find(".treeViewParentIdMoveto").eq(0).html() == nId);
		});
		for( var i = 0; i < ElemLis.length; i++){
			deleteChildNodesMoveto(ElemLis.eq(0).find(".treeViewIdMoveto").html());
		}
		ElemLis.remove();
		if( ElemLis.length == 0)return;
		var curElemLi = $(".treeViewMoveto li").filter(function(index){
			return ($(".treeViewMoveto li").eq(index).find(".treeViewIdMoveto").eq(0).html() == nId);
		}).eq(0);
		curElemLi.find(".isChildNode").eq(0).removeClass("HideItem");
		curElemLi.find(".isChildNode").eq(1).addClass("HideItem");
	}
	function deleteChildNodesMoveto(nId){
		$(".treeViewMoveto li").filter(function(index){
			return ($(".treeViewMoveto li").eq(index).find(".treeViewParentIdMoveto").eq(0).html() == nId);
		}).remove();
	}
	function makeNodeMoveto(oneNode, nDepth){
		var strRetVal = "";
		console.log(oneNode['ChildCount']);
		strRetVal += '<li onclick="treeItemClickedMoveto('+oneNode['Id']+')">';
			strRetVal += '<div class="row col-lg-12 col-md-12 col-xs-12">';
				strRetVal += '<div class="col-lg-12 col-md-12 col-xs-12">';
				for( var jj = 0; jj < nDepth; jj++){
					strRetVal += '<span class="spacePadding"> </span>';
				}
					strRetVal += '<span class="'+(oneNode['ChildCount']=="0" ? "HideItem" : "")+' isChildNode" onclick="expandItemMoveto('+oneNode['Id']+')"><i class="fa fa-plus" aria-hidden="true"></i></span>';
					strRetVal += '<span class="'+(oneNode['ChildCount']=="0" ? "" : "HideItem")+' isChildNode" onclick="collapseItemMoveto('+oneNode['Id']+')"><i class="fa fa-minus" aria-hidden="true"></i></span> ';
					strRetVal += '<span>'+getIconForCategory(oneNode['Category'])+'</span><span class="treeViewName">'+oneNode['strName']+'</span> ';
				strRetVal += '</div>';
				strRetVal += '<div class="treeViewIdMoveto">'+oneNode['Id']+'</div>';
				strRetVal += '<div class="treeViewParentIdMoveto">'+oneNode['idParents']+'</div>';
				strRetVal += '<div class="treeViewDepthMoveto">'+nDepth+'</div>';
				strRetVal += '<div class="treeViewChildLoadedMoveto">0</div>';
			strRetVal += '</div>';
		strRetVal += '</li>';
		return strRetVal;
	}
</script>