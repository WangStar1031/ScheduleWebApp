<?php
	 $conn = null;
	function getConnection(){
		if( $conn != null)return $conn;
		$DBservername = 'localhost';
		
		$DBusername = 'mednieks_user1';
		$DBpassword = '1qaz2wsx3edc';
		$DBname = 'mednieks_attendance';
		
		$conn = mysql_connect($DBservername, $DBusername, $DBpassword);
		mysql_select_db($DBname, $conn);
		// echo( "Selected DB ");

		// $DBusername = 'root';
		// $DBpassword = '';
		// $DBname = 'attendance';

		// $conn = new mysqli($DBservername, $DBusername, $DBpassword, $DBname);


		return $conn;
	}
?>
