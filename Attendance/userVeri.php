<?php
	$userMail = $_POST['userMail'];
	$userPass = $_POST['userPass'];
	if($userMail == "Cristiano Rufini" && $userPass == "Rufini"){
		echo "Cristiano Rufini";
	} else if($userMail == "admin" && $userPass == "admin"){
		echo "admin";
	} else if($userMail == "aaa" && $userPass == "aaa"){
		echo "aaa";
	} else{
		echo "";
	}
?>