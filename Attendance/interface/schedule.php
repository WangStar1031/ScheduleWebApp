<style type="text/css">
	.TimeType, .FirstTime, .LastTime{ padding: 5px; border-radius: 3px; border: none; }
	.TimeType{ background-color: #fcfcbf; }
	.FirstTime{ background-color: #449d44; }
	.LastTime{ background-color: #c495e4; }
	.ScheduleTime{ margin-top: 20px; }
	.ScheduleTime input{ color: white; }
	#ScheduleTable td{text-align: center; border: 2px solid #aaa;}
</style>
<script type="text/javascript">
	var g_arrSchedule = [];
</script>
<div class="row">
<div class="col-lg-8 col-md-8 col-xs-12">
	<table id="ScheduleTable" style="width: 100%;">
		<tr>
			<th></th>
			<th>Name</th>
			<th>Type</th>
			<th>ScheduleTime</th>
			<th>Action</th>
		</tr>
	<?php
		$arrSchedule = getSchedule(1);
		for( $i = 0; $i < count($arrSchedule); $i++){
			$_Schedule = $arrSchedule[$i];
	?>
	<script type="text/javascript">
		g_arrSchedule.push( '<?= $_Schedule['Id'] ?>');
	</script>
		<tr>
			<td><input type="checkbox" class="chkSchedule" onchange="chkScheduleChange(<?= $i ?>)"></td>
			<td><?= $_Schedule['strName'] ?></td>
			<td><?= $_Schedule['Type'] ?></td>
			<td><?= $_Schedule['ScheduleTime'] ?></td>
			<td>
				<div class="btn-group">
					<button onclick="editSchedule(<?= $i ?>)">Edit</button>
					<button onclick="delSchedule(<?= $i ?>)">Del</button>
				</div>			
			</td>
		</tr>
	<?php
		}
	?>
	</table>
	<div class="col-lg-12 col-md-12 col-xs-12" style="height: 20px;"></div>
	<button type="button" class="btn btn-info" onclick="AddSchedule()">Add</button>
</div>
<div class="col-lg-4 col-md-4 col-xs-12 ScheduleDetails HideItem">
	<label>Schedule Name</label>
	<input type="text" id="ScheduleName"><br>
	<label style="margin-top: 15px;">Schedule Type</label>
	<select id="ScheduleType" onchange="ScheduleTypeChange()">
		<option>Regular</option>
		<option>Particular</option>
	</select>
	<?php
		include("customCalendar.php");
	?>
	<div class="row ScheduleTime">
		<table>
			<tr>
				<td class="TimeType">From / Till</td>
				<td><input type="time" id="FromTime" class="FirstTime"></td>
				<td><input type="time" id="TillTime" class="LastTime"></td>
			</tr>
		</table>
	</div>
	<div class="row ScheduleTime HideItem">
		<table>
			<tr>
				<td class="TimeType">Working Time</td>
				<td><input type="time" id="WorkTime" class="FirstTime"></td>
			</tr>
		</table>
	</div>
	<div class="col-lg-12 col-md-12 col-xs-12" style="height: 20px;"></div>
	<button type="button" class="btn btn-info" onclick="ConfirmSchedule()">Confirm</button>
	<button type="button" class="btn" onclick="CancelSchedule()">Candel</button>
</div>
</div>
<script type="text/javascript">
	var isNewSchedule = false;
	var nCurrentSchedule;
	function loadSchedule( strCompanyRegNumber){
		// g_strCompanyRegNumber = strCompanyRegNumber;
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			datatype: 'json',
			data: {getSchedule: strCompanyRegNumber}
		}).done(function (d) {
			var MatchingSchedules = JSON.parse(d);
			$(".chkSchedule").prop("checked","");
			for( var i = 0; i < MatchingSchedules.length; i++){
				var idSchedule = MatchingSchedules[i].Id;
				var nNumber = g_arrSchedule.indexOf(idSchedule);
				if( nNumber != -1){
					$("#ScheduleTable tr").eq(nNumber+1).find("input").eq(0).prop("checked", "true");
				}
			}
		});
	}
	function AddSchedule(){
		isNewSchedule = true;
		nCurrentSchedule = -1;
		$(".ScheduleDetails").removeClass("HideItem");
	}
	function ConfirmSchedule(){
		var strScheduleName = $("#ScheduleName").val();
		if( strScheduleName == ""){
			alert("Please type the Schedule Name.");
			return;
		}
		var strScheduleType = $("#ScheduleType").val();
		var arrScheduleDate = [];
		if( strScheduleType == "Regular"){
			arrScheduleDate = getRegularData();
		} else{
			arrScheduleDate = getParticularData();
		}
		if( arrScheduleDate.length == 0){
			alert("Please select the days or dates.");
			return;
		}
		var StartTime = "", TillTime = "";
		var strScheduleTime = "";
		var isTimeFull = true;
		if( strScheduleType == "Regular"){
			StartTime = $("#FromTime").val();
			TillTime = $("#TillTime").val();
			strScheduleTime = StartTime + "-" + TillTime;
			if( StartTime == "" || TillTime == ""){
				isTimeFull = false;
			}
		} else {
			StartTime = $("#WorkTime").val();
			strScheduleTime = StartTime;
			if( StartTime == "")
				isTimeFull = false;
		}
		if( isTimeFull == false){
			alert("Please enter the time.");
			return;
		}
		console.log(strScheduleTime);
		var ScheduleId = -1;
		if( !isNewSchedule){
			ScheduleId = g_arrSchedule[nCurrentSchedule];
		}
		// if( isNewSchedule){
			$.ajax({
				type: 'POST',
				url: './utils/dbAjax.php',
				datatype: 'json',
				data: {confirmSchedule: ScheduleId, strScheduleName:strScheduleName, strScheduleType:strScheduleType, arrScheduleDate:arrScheduleDate.join(","), strScheduleTime:strScheduleTime}
			}).done(function (d) {
				if( isNewSchedule){
					var strHtml = '<tr>';
					strHtml += '<td><input type="checkbox" class="chkSchedule" onchange="chkScheduleChange('+g_arrSchedule.length+')"></td><td>'+strScheduleName+'</td><td>'+strScheduleType+'</td><td>'+strScheduleTime+'</td><td><div class="btn-group"><button onclick="editSchedule('+g_arrSchedule.length+')">Edit</button><button onclick="delSchedule('+g_arrSchedule.length+')">Del</button></div></td></tr>';
					$("#ScheduleTable tr:last").after(strHtml);
					g_arrSchedule.push(d);
				} else{
					var nRow = g_arrSchedule.indexOf(ScheduleId) + 1;
					$("#ScheduleTable tr").eq(nRow).find("td").eq(1).html( strScheduleName);
					$("#ScheduleTable tr").eq(nRow).find("td").eq(2).html( strScheduleType);
					$("#ScheduleTable tr").eq(nRow).find("td").eq(3).html( strScheduleTime);
				}
				$(".ScheduleDetails").addClass("HideItem");
			});
		// }
	}
	function CancelSchedule(){
		$(".ScheduleDetails").addClass("HideItem");
	}
	function ScheduleTypeChange(){
		var strScheduleType = $("#ScheduleType").val();
		setScheduleType(strScheduleType);
		$(".ScheduleTime").removeClass("HideItem");
		if( strScheduleType == "Regular"){
			$(".ScheduleTime").eq(1).addClass("HideItem");
		} else{
			$(".ScheduleTime").eq(0).addClass("HideItem");
		}
	}
	function editSchedule(nNumber){
		nCurrentSchedule = nNumber;
		isNewSchedule = false;
		var curScheduleId = g_arrSchedule[nNumber];
		$("#ScheduleName").val( $("#ScheduleTable tr").eq(nNumber + 1).find("td").eq(1).html());
		var strScheduleType = $("#ScheduleTable tr").eq(nNumber + 1).find("td").eq(2).html();
		$("#ScheduleType").val( strScheduleType);
		var strScheduleTime = $("#ScheduleTable tr").eq(nNumber + 1).find("td").eq(3).html();
		var arrScheduleTime = [];
		arrScheduleTime = strScheduleTime.split("-");
		$(".ScheduleTime").removeClass("HideItem");
		if( strScheduleType == "Regular"){
			$(".ScheduleTime").eq(1).addClass("HideItem");
			$("#FromTime").val(arrScheduleTime[0]);
			$("#TillTime").val(arrScheduleTime[1]);
		} else{
			$(".ScheduleTime").eq(0).addClass("HideItem");
			$("#WorkTime").val(arrScheduleTime[0]);
		}
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			datatype: 'json',
			data: {getScheduleDate: curScheduleId}
		}).done(function (d) {
			setSelDaysOrDates(d);
		});

		$(".ScheduleDetails").removeClass("HideItem");
		setScheduleType(strScheduleType);
	}
	function chkScheduleChange(nNumber){
		if( g_strCompanyRegNumber == ""){
			alert("Please select the company.");
			return;
		}
		var isChecked = $("#ScheduleTable tr").eq(nNumber+1).find("input").eq(0).prop("checked");
		var idSchedule = g_arrSchedule[nNumber];
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			data: {updateCompanyInfo: g_strCompanyRegNumber, idSchedule: idSchedule, isChecked: isChecked}
		}).done(function(d){

		});
	}
	function delSchedule(nNumber){
		var strRegNumber = $("#ScheduleTable tr").eq(nNumber+1).find("td").eq(3).html();
		var idSchedule = g_arrSchedule[nNumber];

		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			data: {delSchedule:idSchedule}
		}).done(function (d) {
			$("#ScheduleTable tr").eq(nNumber+1).remove();
			var elemTrs = $("#ScheduleTable tr");
			for( var i = 1; i < elemTrs.length; i++){
				$("#ScheduleTable tr").eq(i).find("button").eq(0).attr("onclick", "editSchedule("+(i-1)+")");
				$("#ScheduleTable tr").eq(i).find("button").eq(1).attr("onclick", "delSchedule("+(i-1)+")");
			}
			g_arrSchedule.splice( nNumber, 1);		
		});
	}
</script>