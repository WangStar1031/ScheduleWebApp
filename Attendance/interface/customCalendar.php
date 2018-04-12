<?php
	$Year = date("Y");
	$Month = date("m");
	$Day = date("d");
	$dFirst = mktime( 1, 1, 1, 1, $Month, $Year);
	$dateForFirst = date("l", $dFirst);
	$MonthFirst =  date("N", strtotime($dateForFirst));
?>
<style type="text/css">
	.calenderTb .btn:hover{
		color: white!important;
	}
	.calenderTb th, .calenderTb td{ cursor: pointer; }
	.selDays{ background-color: #0dd; }
</style>
	<div class="row">
			<table class="calenderTb table-condensed table-bordered table-striped">
				<tr>
				  <th colspan="7">
					<span class="btn-group">
						<div class="btn" onclick="calMonth(-1)"><</div>
						<div class="btn active" id="CalDate"><?= $Month ?> <?= $Year ?></div>
						<div class="btn" onclick="calMonth(1)">></div>
					</span>
				  </th>
				</tr>
				<tr>
					<th onclick="DayClicked(0)">Su</th>
					<th onclick="DayClicked(1)">Mo</th>
					<th onclick="DayClicked(2)">Tu</th>
					<th onclick="DayClicked(3)">We</th>
					<th onclick="DayClicked(4)">Th</th>
					<th onclick="DayClicked(5)">Fr</th>
					<th onclick="DayClicked(6)">Sa</th>
				</tr>
			</table>
	</div>


<script type="text/javascript">
	var g_Year = '<?= $Year ?>';
	var g_Month = '<?= $Month ?>' - 1;
	var curScheduleType = "Regular";
	function MakeCalendar(nYear, nMonth){
		var d = new Date( nYear, nMonth, 1);
		var day = d.getDay();
		$("#CalDate").html((d.getMonth()+1 >= 10 ? (d.getMonth()+1) : "0"+(d.getMonth()+1)) + " - " + (d.getYear()+1900));
		// var elemTrs = $(".calenderTb tr");
		while( $(".calenderTb tr").length > 2){
			$(".calenderTb tr").eq($(".calenderTb tr").length - 1).remove();
		}
		var strHtml = "";
		var nDays = new Date( nYear, nMonth, 0).getDate();
		var nCurCell = 0;
		while( nCurCell < (nDays + day) ){
			if( nCurCell % 7 == 0)
				strHtml += '<tr>';
			strHtml += '<td'+(nCurCell >= day ? " onclick='DateClicked("+parseInt(nCurCell/7)+","+parseInt(nCurCell%7)+")'" : "")+'>'+(nCurCell >= day ? (nCurCell - day+1) : "")+'</td>';
			if( nCurCell % 7 == 6)
				strHtml += '</tr>';
			nCurCell ++;
		}
		$(".calenderTb tr:last").after(strHtml);
	}
	MakeCalendar(g_Year, g_Month);
	function calMonth(nMonthDiff){
		var d = new Date(g_Year, g_Month + nMonthDiff, 1);
		var y = d.getYear();
		g_Year = y+1900;
		g_Month = d.getMonth();
		MakeCalendar( y+1900, g_Month);
	}
	function setScheduleType(strType){
		curScheduleType = strType;
	}
	function setSelDaysOrDates( strSelects){
		$(".calenderTb tr td").removeClass("selDays");
		$(".calenderTb tr th").removeClass("selDays");
		var arrSelects = strSelects.split(",");
		console.log(arrSelects);
		if( curScheduleType == "Regular"){
			var today = new Date();
			MakeCalendar( today.getFullYear(), today.getMonth());
			for( var i = 0; i < arrSelects.length; i++){
				DayClicked(arrSelects[i]);
			}
		} else{
			var arrYearMon = arrSelects[0].split("-");
			MakeCalendar( arrYearMon[0], arrYearMon[1]);
			for( var i = 2; i < $(".calenderTb tr").length; i++){
				var elems = $(".calenderTb tr").eq(i);
				for( var j = 0; j < elems.find("td").length; j++){
					if( arrSelects.indexOf(elems.find("td").eq(j).html()) != -1){
						elems.find("td").eq(j).addClass("selDays");
					}
				}
			}
		}
	}
	function DayClicked(nNumber){
		if( curScheduleType != "Regular")return;
		$(".calenderTb tr").eq(1).find("th").eq(nNumber).toggleClass("selDays");
		for( var i = 2; i < $(".calenderTb tr").length; i++){
			$(".calenderTb tr").eq(i).find("td").eq(nNumber).toggleClass("selDays");
		}
	}
	function DateClicked(nRow, nCol){
		if( curScheduleType == "Regular")return;
		$(".calenderTb tr").eq(nRow+2).find("td").eq(nCol).toggleClass("selDays");
	}
	function getRegularData(){
		var retData = [];
		for( var i = 0; i < 7; i++){
			if( $(".calenderTb tr").eq(1).find("th").eq(i).hasClass("selDays")){
				retData.push(i);
			}
		}
		return retData;
	}
	function getParticularData(){
		var retData = [];
		retData.push(g_Year + "-" + g_Month);
		for( var i = 2; i < $(".calenderTb tr").length; i++){
			for( j = 0; j < 7; j++){
				var elem = $(".calenderTb tr").eq(i).find("td").eq(j);
				if( elem.html() != "" && elem.hasClass("selDays")){
					retData.push(elem.html());
				}
			}
		}
		return retData;
	}
</script>