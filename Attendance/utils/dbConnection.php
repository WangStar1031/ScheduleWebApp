<?php
	function getConnection(){
		$DBservername = 'localhost';
		
		// $DBusername = 'mednieks_user1';
		// $DBpassword = '1qaz2wsx3edc';
		// $DBname = 'mednieks_attendance';

		$DBusername = 'root';
		$DBpassword = '';
		$DBname = 'attendance';

		$conn = new mysqli($DBservername, $DBusername, $DBpassword, $DBname);
		// $conn = new mysql($DBservername, $DBusername, $DBpassword, $DBname);


		return $conn;
	}
?>
