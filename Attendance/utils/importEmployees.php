<?php
	if( $_SERVER['HTTP_HOST'] == 'localhost'||  $_SERVER['HTTP_HOST'] == '192.168.1.75'){
		include_once("dbManager.php");
	} else{
		include_once("dbManagerForServer.php");
	}
	require('./php-excel-reader/excel_reader2.php');

	require('./SpreadsheetReader.php');
	function charsetConvert($prev){
		return html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $prev), ENT_NOQUOTES, 'UTF-8');
	}
	function parseExcelForEmployee($PathName, $idTreeInfo){
		$Reader = new SpreadsheetReader($PathName);
		// var_dump($Reader);
		$index = 0;
		foreach ($Reader as $Row)
		{
			if( !isset($Row[0]))
				continue;
			echo "<br>";
			var_dump($Row);
			echo "<br>";
			echo $index;
			if( $index == 0){
				$index++;
				continue;
			}
			$employeeInfo = new stdClass();
			$employeeInfo->idTreeInfo = $idTreeInfo;
			$employeeInfo->Id = 0;
			$employeeInfo->Name = $Row[0];
			$employeeInfo->SurName = $Row[1];
			$employeeInfo->Code = $Row[2];
			$employeeInfo->Address = $Row[3];
			$employeeInfo->PhoneNumber = $Row[4];
			$employeeInfo->Email = $Row[5];
			$employeeInfo->NFCNumber = $Row[6];
			echo json_encode($employeeInfo);

			insertNewEmployee($employeeInfo);
			// return;
		}
	}

	if(isset($_FILES['upload'])){
		if(count($_FILES['upload']['name']) > 0){
			echo count($_FILES['upload']['name']);
			$tmpFilePath = $_FILES['upload']['tmp_name'];
			echo "<br>";
			echo $_FILES['upload']['name'];
			echo "<br>";
			echo $_FILES['upload']['tmp_name'];
			echo "<br>";
			$idTreeInfo = $_POST['idTreeInfo'];
			$destFName = $_FILES['upload']['name'];
			// copy($tmpFilePath, $destFName);
			echo $idTreeInfo;
			move_uploaded_file($tmpFilePath, $destFName);
			if($tmpFilePath != ""){
				parseExcelForEmployee($destFName, $idTreeInfo);
			}
		}
	}
?>