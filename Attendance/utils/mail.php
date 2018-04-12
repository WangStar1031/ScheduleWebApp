<?php
	if( $_SERVER['HTTP_HOST'] == 'localhost'||  $_SERVER['HTTP_HOST'] == '192.168.1.75'){
		include_once("dbManager.php");
	} else{
		include_once("dbManagerForServer.php");
	}

	if(isset($_POST['inviteMail'])){
		$strUserId = $_POST['inviteMail'];
		$strMail = $_POST['eMail'];
		$strPass = $_POST['userPass'];
		$strRole = $_POST['userRole'];
		$strName = $_POST['userName'];
		$strUrl = 'http://etabula.lv/Will/Attendance/';
		$verifyCode = RegisterUserWithRole( $strName, $strMail, $strPass, $strRole, $strUserId);
		$msg = "<!DOCTYPE html><html lang='en'><body><h1>Hello, <span style='font-weight:bolder;'> $strName! </span></h1><br><br> I invite you on my Schedule App. <br> Please click this Button to verify your email. <br>Your Password is $strPass<br><br><div style = 'padding:5px; background-color:#485aa2; width:5em; text-align:center; '><a href = '$strUrl' style = 'color:white; '>Click Me</a></div><br>$strUrl</body></html>";
		// $msg = wordwrap($msg, 70);
		$headers = "From: support@etabula.lv" . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		ini_set("SMTP","ssl://smtp-mail.outlook.com");
		ini_set("smtp_port","587");
		if( mail($strMail, "Welcome to Etabula.lv", $msg, $headers)){
			echo "OK Sent.";
		} else{
			echo "Not Send.";
		}

	}
?>
