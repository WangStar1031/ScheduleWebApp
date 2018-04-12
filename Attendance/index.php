<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<script src="assets/js/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="./assets/css/mainInterface.css?<?= time() ?>">

<body>
<?php
	$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
	// echo $_SERVER['SERVER_PROTOCOL'];
	// echo $_SERVER['HTTP_HOST']."<br>";
	session_start();
	if(!isset($_SESSION['AttendanceUserName'])){
		if( $_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '192.168.1.75'){
			include_once("utils/dbManager.php");
		} else{
			include_once("utils/dbManagerForServer.php");
		}
		RegisterPowerUser("adminWill", "adminWill", "1qaz2wsx");
?>
<?php
	include_once("./utils/excelParsing.php");

	function getIconForCategory($Category){
		switch ($Category) {
			case 'company':case 'Company':
				return '<i class="fa fa-university" aria-hidden="true"></i>';
			case 'branch':case 'Branch':
				return '<i class="fa fa-building" aria-hidden="true"></i>';
			case 'department':case 'Department':
				return '<i class="fa fa-users" aria-hidden="true"></i>';
		}
	}
?>
<script type="text/javascript">
  
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
var userName = getCookie("ScheduleUser");
var userRole = getCookie("ScheduleUserRole");
if(userName == ""){
	window.location.href="login.php";
}
if( userRole == "User"){
	window.location.href = "userInfo.php";
}
var userId;
var comId;
if( userRole != 'PowerUser'){
	userId = getCookie("ScheduleUserId");
	comId = getCookie("ScheduleCompanyId");
	$(".treeView li").filter(function(index){
		return $(".treeView li").eq(index).find(".treeViewId").html != comId;
	}).remove();
}
</script>

<script type="text/javascript">
	var Profs = <?= json_encode($arrProfs) ?>;
</script>
<head>
	<title>Schedule</title>
</head>
<script type="text/javascript">
	var g_strCompanyRegNumber = "";
</script>
	<div class="SideBar">
		<ul class="MenuUL">
			<li onclick="SelectMenu(0)" class="selected"><img src="./assets/img/icons/registration.png"><span>Registration</span></li>
			<li onclick="SelectMenu(1)"><img src="./assets/img/icons/report.png"><span>Report</span></li>
		</ul>
	</div>
	<div class="ClientArea">
		<div id="RegistrationContents">

			<?php
				include("./interface/treeView.php");
			?>
		</div>
		<div id="ReportContents" class="HideItem">
			<?php
				include("./interface/report.php")
			?>
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
	setClientRect();
	function setClientRect(){
		var width = $(".SideBar").outerWidth();
		document.getElementsByClassName("ClientArea")[0].style.marginLeft = width + "px";
	}
	function setCookie(cname,cvalue,exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires=" + d.toGMTString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
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
	// ✅
</script>
<?php
	} else{
		echo "No Session";
	}
?>
</body>