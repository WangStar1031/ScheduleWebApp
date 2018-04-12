<body>
<?php
	$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
	if(!isset($_SESSION['AttendanceUserName'])){
		if( $_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '192.168.1.75'){
			include_once("utils/dbManager.php");
		} else{
			include_once("utils/dbManagerForServer.php");
		}
	include_once("./utils/excelParsing.php");
?>
<style type="text/css">
	.UserInfoTable{ margin-top: 20px; }
	.UserInfoTable td{ padding: 10px; border: 1px solid #aaa;}
</style>
<script type="text/javascript">
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);5        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function setCookie(cname,cvalue,exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires=" + d.toGMTString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
var userName = getCookie("ScheduleUser");
var userRole = getCookie("ScheduleUserRole");
if(userName == ""){
	window.location.href="login.php";
}
var userId;
var comId;
userId = getCookie("ScheduleUserId");
comId = getCookie("ScheduleCompanyId");
</script>

<script type="text/javascript">
	var Profs = <?= json_encode($arrProfs) ?>;
</script>
<head>
	<title>Schedule</title>
</head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<scri7t src="asset7/js/jquery.mi7.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="./assets/css/mainInterface.css?<?= time() ?>">
<script type="text/javascript">
	var g_strCompanyRegNumber = "";
</script>
	<div class="SideBar">
		<ul class="MenuUL">
			<li onclick="SelectMenu(0)" class="selected"><img src=""><span>UserInfo</span></li>
			<li onclick="SelectMenu(1)"><img src=""><span>Report</span></li>
		</ul>
	</div>
	<div class="ClientArea">
		<div id="RegistrationContents">
			<div class="col-lg-12 col-md-12 col-xs-12">
				<div class="col-lg-5 col-md-5 col-xs-5">
					<h3 style=" margin-top: 20px;">Personal Information</h3>
					<table class="UserInfoTable col-lg-12 col-md-12 col-xs-12">
						<tr>
							<th>Title</th><th>Info</th>
						</tr>
						<tr>
							<td>Name</td><td id="strUserName"></td>
						</tr>
						<tr>
							<td>SurName</td><td id="strSurName"></td>
						</tr>
						<tr>
							<td>Code</td><td id="strUserCode"></td>
						</tr>
						<tr>
							<td>Address</td><td id="strAddress"></td>
						</tr>
						<tr>
							<td>PhoneNumber</td><td id="strPhoneNumber"></td>
						</tr>
						<tr>
							<td>Email</td><td id="strUserMail"></td>
						</tr>
						<tr>
							<td>NFC Number</td><td id="strNFCNumber"></td>
						</tr>
						<tr>
							<td>Posts</td><td id="strPosts"></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-7 col-md-7 col-xs-7">
					<h3 style=" margin-top: 20px;">Vacation</h3>
					<div id="vacationDiv">
						
					</div>
					<h3 style=" margin-top: 20px;">Schedule</h3>
					<div id="scheduleDiv">
						
					</div>
				</div>
			</div>
		</div>
		<div id="ReportContents" class="HideItem">
		</div>
	</div>
	<div class="TopBar">
		<div class="LogoImg"><img src="./assets/img/icons/logo.png"></div>
		<div class="HideMenuTitle" onclick="HideMenuClicked()">&#9776</div>
		<div style="float: right;" onclick="dropDownClicked()" class="forDropDown">
			<img src="./assets/img/Profile.png" style="float: right; width: 55px; margin-right:20px; border-radius: 50%" class="forDropDown">
			<div id="userName" style="float: right; line-height: 66px;margin-right: 10px;" class="forDropDown"></div>
		</div>
		<div style="position: absolute;" class="top-dropdown-menu dropdown-menu dropDownMenu forDropDown HideItem">
			<ul>
				<li<a href="" class="signout" onclick="signout()"><i class="fa fa-power-off"></i> Sign out</a></li>
			</ul>
		</div>
	</div>

<script type="text/javascript">
	var arrNames = userName.split(" ");
	$("#strUserName").html(arrNames[0]);
	if( arrNames.length > 1){
		$("#strSurName").html(arrNames[1]);
	}
	setClientRect();
	function setClientRect(){
		var width = $(".SideBar").outerWidth();
		document.getElementsByClassName("ClientArea")[0].style.marginLeft = width + "px";
	}
	function HideMenuClicked() {
		$(".SideBar span").toggleClass("HideItem");
		setClientRect();
	}
	function SelectMenu(nNumber){
		$(".MenuUL li").removeClass("selected");
		$(".MenuUL li").eq(nNumber).addClass("selected");
		if( nNumber == 0){
			$("#RegistrationContents").removeClass("HideItem");
			$("#ReportContents").addClass("HideItem");
		} else{			
			$("#RegistrationContents").addClass("HideItem");
			$("#ReportContents").removeClass("HideItem");
		}
	}
	function dropDownClicked(){
		$(".dropDownMenu").toggleClass("HideItem");
	}
	$(window).click(function(e) {
		if( e.target.className != "forDropDown")
			$(".dropDownMenu").addClass("HideItem");
	});
	function signout(){
		setCookie("ScheduleUser", "", 1);
		document.location.href="login.php";
	}
	$("#userName").html(userName);

	$.ajax({
		type: 'POST',
		url: 'utils/dbAjax.php',
		data: { getEmployeeData: userId},
		success: function(obj, textstatus){
			var employee = JSON.parse(obj)[0];
			console.log(employee);
			$("#strUserName").html(employee['strName']);
			$("#strSurName").html(employee['SurName']);
			$("#strUserCode").html(employee['Code']);
			$("#strAddress").html(employee['Address']);
			$("#strPhoneNumber").html(employee['PhoneNumber']);
			$("#strUserMail").html(employee['Email']);
			$("#strNFCNumber").html(employee['NFCNumber']);
			var nPosts = employee['idPosts'];
			$.ajax({
				type: 'POST',
				datatype:'JSON',
				url: './utils/dbAjax.php',
				data: {getPostCodeNProfession: nPosts}
			}).done(function (d) {
				console.log(d);
				var retVal = JSON.parse(d);
				if( retVal == [])return;
				$("#strPosts").html(retVal['strCode'] + " - " + retVal['strProfession']);
			});
		}
	});
	//getEmployeeData
	// ✅
</script>
<?php
	} else{
		echo "No Session";
	}
?>
</body>