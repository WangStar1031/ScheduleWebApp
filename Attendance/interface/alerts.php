<style type="text/css">
	#AlertPan table td{
		padding: 10px;
	}
	#AlertPan .textfield1{
		border-radius: 5px;
		width: 10em;
	}
	#AlertPan .textfield2{
		border-radius: 5px;
		width: 4em;
		text-align: center;
	}
	#AlertPan .chkfield{
		width: 2em;
	}
	#AlertPan input{ width: 100%; }
	#AlertPan .title{ background-color: #e6d1ac; border-radius: 5px; color: black;}
	#AlertPan .textMinutes{ background-color: #50c150; border-radius: 5px; }
	#AlertPan .textMinutes input{ border: none;  background-color: #50c150; color: white; text-align: center;}
</style>
<div id="AlertPan">
		
	<table>
		<tr>
			<td colspan="5" class="title">Warning that employee not at work</td>
		</tr>
		<tr>
			<td class="textfield1">Employee</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">SMS</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">e-mail</td>
		</tr>
		<tr>
			<td class="textfield1">Delay more than minutes</td>
			<td colspan="2" class="textMinutes"><input type="text"></td>
		</tr>
		<tr>
			<td colspan="5"><input type="text"></td>
		</tr>

		<tr>
			<td colspan="5" class="title">Delay alert to employee</td>
		</tr>
		<tr>
			<td class="textfield1">Employee</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">SMS</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">e-mail</td>
		</tr>
		<tr>
			<td class="textfield1">Branch</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">SMS</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">e-mail</td>
		</tr>
		<tr>
			<td class="textfield1">Department</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">SMS</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">e-mail</td>
		</tr>
		<tr>
			<td class="textfield1">Delay more than</td>
			<td colspan="2" class="textMinutes"><input type="text"></td>
		</tr>
		<tr>
			<td colspan="5"><input type="text"></td>
		</tr>

		<tr>
			<td colspan="5" class="title">Warning about working schedule changes</td>
		</tr>
		<tr>
			<td class="textfield1">Employee</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">SMS</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">e-mail</td>
		</tr>
		<tr>
			<td class="textfield1">Branch</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">SMS</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">e-mail</td>
		</tr>
		<tr>
			<td class="textfield1">Department</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">SMS</td>
			<td class="chkfield"><input type="checkbox"></td>
			<td class="textfield2">e-mail</td>
		</tr>
		<tr>
			<td colspan="5"><input type="text"></td>
		</tr>
	</table>

<div class="col-lg-12 col-md-12 col-xs-12" style="height: 20px;"></div>
<!-- <button class="btn" onclick="SaveAlerts()">Save</button> -->
</div>

<script type="text/javascript">
	function formatAlerts(){
		console.log("formatAlerts");
		$("#AlertPan table tr").eq(1).find("input").eq(0).prop("checked", "");
		$("#AlertPan table tr").eq(1).find("input").eq(1).prop("checked", "");
		$("#AlertPan table tr").eq(2).find("input").eq(0).val("");
		$("#AlertPan table tr").eq(3).find("input").val("");
		$("#AlertPan table tr").eq(5).find("input").eq(0).prop("checked","");
		$("#AlertPan table tr").eq(5).find("input").eq(1).prop("checked","");
		$("#AlertPan table tr").eq(6).find("input").eq(0).prop("checked","");
		$("#AlertPan table tr").eq(6).find("input").eq(1).prop("checked","");
		$("#AlertPan table tr").eq(7).find("input").eq(0).prop("checked","");
		$("#AlertPan table tr").eq(7).find("input").eq(1).prop("checked","");
		$("#AlertPan table tr").eq(8).find("input").eq(0).val("");
		$("#AlertPan table tr").eq(9).find("input").eq(0).val("");
		$("#AlertPan table tr").eq(11).find("input").eq(0).prop("checked","");
		$("#AlertPan table tr").eq(11).find("input").eq(1).prop("checked","");
		$("#AlertPan table tr").eq(12).find("input").eq(0).prop("checked","");
		$("#AlertPan table tr").eq(12).find("input").eq(1).prop("checked","");
		$("#AlertPan table tr").eq(13).find("input").eq(0).prop("checked","");
		$("#AlertPan table tr").eq(13).find("input").eq(1).prop("checked","");
		$("#AlertPan table tr").eq(14).find("input").eq(0).val("");
	}
	function loadAlerts( idTreeInfo){
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			datatype: 'json',
			data: {getAlerts: idTreeInfo}
		}).done(function (d) {
			console.log(d);
			var alertInfo = JSON.parse(d);
			console.log(alertInfo);
			if( alertInfo == ""){
				formatAlerts();
				return;
			}
			if(alertInfo['isEmSMSNotAtWork'] == "true"){
				$("#AlertPan table tr").eq(1).find("input").eq(0).prop("checked","true");
			} else{
				$("#AlertPan table tr").eq(1).find("input").eq(0).prop("checked","");
			}
			if(alertInfo['isEmEMLNotAtWork'] == "true"){
				$("#AlertPan table tr").eq(1).find("input").eq(1).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(1).find("input").eq(1).prop("checked", "");
			}
			$("#AlertPan table tr").eq(2).find("input").eq(0).val(alertInfo['timeNotAtWork']);
			$("#AlertPan table tr").eq(3).find("input").eq(0).val(alertInfo['msgNotAtWork']);
			if(alertInfo['isEmSMSDelay'] == "true"){
				$("#AlertPan table tr").eq(5).find("input").eq(0).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(5).find("input").eq(0).prop("checked", "");
			}
			if(alertInfo['isEmEMLDelay'] == "true"){
				$("#AlertPan table tr").eq(5).find("input").eq(1).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(5).find("input").eq(1).prop("checked", "");
			}
			if(alertInfo['isBrSMSDelay'] == "true"){
				$("#AlertPan table tr").eq(6).find("input").eq(0).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(6).find("input").eq(0).prop("checked", "");
			}
			if(alertInfo['isBrEMLDelay'] == "true"){
				$("#AlertPan table tr").eq(6).find("input").eq(1).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(6).find("input").eq(1).prop("checked", "");
			}
			if(alertInfo['isDeSMSDelay'] == "true"){
				$("#AlertPan table tr").eq(7).find("input").eq(0).prop("checked","true");
			} else{
				$("#AlertPan table tr").eq(7).find("input").eq(0).prop("checked","");
			}
			if(alertInfo['isDeEMLDelay'] == "true"){
				$("#AlertPan table tr").eq(7).find("input").eq(1).prop("checked","true");
			} else{
				$("#AlertPan table tr").eq(7).find("input").eq(1).prop("checked","");
			}
			$("#AlertPan table tr").eq(8).find("input").eq(0).val(alertInfo['timeDelay']);
			$("#AlertPan table tr").eq(9).find("input").eq(0).val(alertInfo['msgDelay']);
			if(alertInfo['isEmSMSChange'] == "true"){
				$("#AlertPan table tr").eq(11).find("input").eq(0).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(11).find("input").eq(0).prop("checked", "");
			}
			if(alertInfo['isEmEMLChange'] == "true"){
				$("#AlertPan table tr").eq(11).find("input").eq(1).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(11).find("input").eq(1).prop("checked", "");
			}
			if(alertInfo['isBrSMSChange'] == "true"){
				$("#AlertPan table tr").eq(12).find("input").eq(0).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(12).find("input").eq(0).prop("checked", "");
			}
			if(alertInfo['isBrEMLChange'] == "true"){
				$("#AlertPan table tr").eq(12).find("input").eq(1).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(12).find("input").eq(1).prop("checked", "");
			}
			if(alertInfo['isDeSMSChange'] == "true"){
				$("#AlertPan table tr").eq(13).find("input").eq(0).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(13).find("input").eq(0).prop("checked", "");
			}
			if(alertInfo['isDeEMLChange'] == "true"){
				$("#AlertPan table tr").eq(13).find("input").eq(1).prop("checked", "true");
			} else{
				$("#AlertPan table tr").eq(13).find("input").eq(1).prop("checked", "");
			}
			$("#AlertPan table tr").eq(14).find("input").eq(0).val(alertInfo['msgChang']);
		});
	}
	function SaveAlerts(idTreeInfo){
		// if( g_strCompanyRegNumber == ""){
		// 	alert("Please select the company.");
		// 	return;
		// }
		var isEmSMSNotAtWork = $("#AlertPan table tr").eq(1).find("input").eq(0).prop("checked");
		var isEmEMLNotAtWork = $("#AlertPan table tr").eq(1).find("input").eq(1).prop("checked");
		var timeNotAtWork = $("#AlertPan table tr").eq(2).find("input").eq(0).val();
		var msgNotAtWork = $("#AlertPan table tr").eq(3).find("input").val();
		var isEmSMSDelay = $("#AlertPan table tr").eq(5).find("input").eq(0).prop("checked");
		var isEmEMLDelay = $("#AlertPan table tr").eq(5).find("input").eq(1).prop("checked");
		var isBrSMSDelay = $("#AlertPan table tr").eq(6).find("input").eq(0).prop("checked");
		var isBrEMLDelay = $("#AlertPan table tr").eq(6).find("input").eq(1).prop("checked");
		var isDeSMSDelay = $("#AlertPan table tr").eq(7).find("input").eq(0).prop("checked");
		var isDeEMLDelay = $("#AlertPan table tr").eq(7).find("input").eq(1).prop("checked");
		var timeDelay = $("#AlertPan table tr").eq(8).find("input").eq(0).val();
		var msgDelay = $("#AlertPan table tr").eq(9).find("input").eq(0).val();
		var isEmSMSChange = $("#AlertPan table tr").eq(11).find("input").eq(0).prop("checked");
		var isEmEMLChange = $("#AlertPan table tr").eq(11).find("input").eq(1).prop("checked");
		var isBrSMSChange = $("#AlertPan table tr").eq(12).find("input").eq(0).prop("checked");
		var isBrEMLChange = $("#AlertPan table tr").eq(12).find("input").eq(1).prop("checked");
		var isDeSMSChange = $("#AlertPan table tr").eq(13).find("input").eq(0).prop("checked");
		var isDeEMLChange = $("#AlertPan table tr").eq(13).find("input").eq(1).prop("checked");
		var msgChang = $("#AlertPan table tr").eq(14).find("input").eq(0).val();
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			datatype: 'json',
			data: {setAlerts: idTreeInfo, isEmSMSNotAtWork:isEmSMSNotAtWork, isEmEMLNotAtWork:isEmEMLNotAtWork, timeNotAtWork:timeNotAtWork, msgNotAtWork:msgNotAtWork, isEmSMSDelay:isEmSMSDelay, isEmEMLDelay:isEmEMLDelay, isBrSMSDelay:isBrSMSDelay, isBrEMLDelay:isBrEMLDelay, isDeSMSDelay:isDeSMSDelay, isDeEMLDelay:isDeEMLDelay, timeDelay:timeDelay,msgDelay:msgDelay, isEmSMSChange:isEmSMSChange, isEmEMLChange:isEmEMLChange, isBrSMSChange:isBrSMSChange, isBrEMLChange:isBrEMLChange, isDeSMSChange:isDeSMSChange, isDeEMLChange:isDeEMLChange, msgChang:msgChang}
		}).done(function (d) {
			console.log(d);
		});
	}
</script>