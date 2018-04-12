<?php

	require('./utils/php-excel-reader/excel_reader2.php');

	require('./utils/SpreadsheetReader.php');
	$arrProfs = array();
	function parseExcel($PathName){
		global $arrProfs;
		$Reader = new SpreadsheetReader($PathName);
		foreach ($Reader as $Row)
		{
			// echo $Row;
			if( count($Row) == 0)
				continue;
			if( !isset($Row[0]))
				continue;
			$strProfCode = $Row[0];
			$strProfName = $Row[1];
			$strProfDetails = isset($Row[2]) ? $Row[2] : "";
			array_push($arrProfs, $strProfCode . "@@@" . $strProfName . "@@@" . $strProfDetails);
		}
	}
	parseExcel("./utils/prof_klas.xls");
?>