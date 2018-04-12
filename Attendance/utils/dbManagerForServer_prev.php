<?php
	// Connection
	require_once("dbConnectionForServer.php");
	// User
	function RegisterUser( $name, $eMail, $pass){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT Id FROM users WHERE UserName='".$name."' OR UserMail='".$eMail."';";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			return "0";
		}
		$VerifyCode = crypt($name.$pass,'');
		$sql = "INSERT INTO users(UserName, UserMail, Password, VerifyCode, VerifyStates) VALUES('$name','$eMail', '$pass','$VerifyCode', 'No')";
		if( mysql_query($sql) == true){
			return $VerifyCode;
		}
		$conn->close();
		return "1";
	}
	function VerifyUserFromCode($verifyCode){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT UserName FROM users WHERE VerifyCode='$verifyCode' AND VerifyStates='No';";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			$UserName = $row['UserName'];
			$sql = "UPDATE users SET VerifyStates='Yes' WHERE VerifyCode='$verifyCode'";
			mysql_query($sql);
			return $UserName;
		}
		return "";
	}
	function VerifyUserFromNamePass($name, $pass){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT UserName FROM users WHERE (UserName='$name' OR UserMail='$name') AND Password='$pass' AND VerifyStates='Yes';";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			$UserName = $row['UserName'];
			return $UserName;
		}
		return "";
	}
	function getUserNameFromEmail($mail){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return "";
		}
		$sql = "SELECT UserName FROM users WHERE UserMail='$mail' AND VerifyStates='Yes';";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			$UserName = $row['UserName'];
			return $UserName;
		}
		return "";
	}
	function getPasswordFromEmail($mail){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return "";
		}
		$sql = "SELECT Password FROM users WHERE UserMail='$mail' AND VerifyStates='Yes';";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			$Password = $row['Password'];
			return $Password;
		}
		return "";
	}
	// Employee
	function verifyEmployee($employeeInfo){
		$PhoneNumber = $employeeInfo->PhoneNumber;
		$Email = $employeeInfo->Email;
		$NFCNumber = $employeeInfo->NFCNumber;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM employee WHERE PhoneNumber='$PhoneNumber' OR Email='$Email' OR NFCNumber='$NFCNumber'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			return true;
		}
		return false;
	}
	function insertNewEmployee($employeeInfo){
		if( verifyEmployee($employeeInfo)){
			echo "Exist Employee.";
			return;
		}
		$Name = $employeeInfo->Name;
		$SurName = $employeeInfo->SurName;
		$Code = $employeeInfo->Code;
		$Address = $employeeInfo->Address;
		$PhoneNumber = $employeeInfo->PhoneNumber;
		$Email = $employeeInfo->Email;
		$NFCNumber = $employeeInfo->NFCNumber;

		$idBranches = $employeeInfo->idBranches;
		$idDepartment = $employeeInfo->idDepartment;
		$idSchedule = $employeeInfo->idSchedule;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "INSERT INTO employee(idCompany, strName, SurName, Code, Address, PhoneNumber, Email, NFCNumber, idBranches, idDepartment, idSchedule) VALUES('$idCompany', '$Name', '$SurName', '$Code', '$Address', '$PhoneNumber', '$Email', '$NFCNumber', '$idBranches', '$idDepartment', '$idSchedule')";
		if( mysql_query($sql) == true){
			return "YES";
		}
		return "NO";
	}
	function updateEmployee($Id, $employeeInfo){
		$idCompany = $employeeInfo->idCompany;
		$Name = $employeeInfo->Name;
		$SurName = $employeeInfo->SurName;
		$Code = $employeeInfo->Code;
		$Address = $employeeInfo->Address;
		$PhoneNumber = $employeeInfo->PhoneNumber;
		$Email = $employeeInfo->Email;
		$NFCNumber = $employeeInfo->NFCNumber;

		$idBranches = $employeeInfo->idBranches;
		$idDepartment = $employeeInfo->idDepartment;
		$idSchedule = $employeeInfo->idSchedule;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "UPDATE employee SET idCompany='$idCompany', Name='$Name', SurName='$SurName', Code='$Code', Address='$Address', PhoneNumber='$PhoneNumber', Email='$Email', NFCNumber='$NFCNumber', idBranches='$idBranches', idDepartment='$idDepartment', idSchedule='$idSchedule') WHERE Id=$Id";
		mysql_query($sql);
		return;
	}
	function deleteEmployee($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM employee WHERE Id='$Id'";
		mysql_query($sql);
	}
	function getEmployee($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM employee WHERE $condition";
		$result = mysql_query($sql);
		$arrRetVal = array();
		while( $row = mysql_fetch_assoc($result)){
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	// Company
	function verifyCompany($companyInfo){
		$regNumber = $companyInfo->regNumber;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM company WHERE regNumber='$regNumber'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			return true;
		}
		return false;
	}

	function getCompanyId($regNumber){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM company WHERE regNumber='$regNumber'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			return $row['Id'];
		}
		return 0;
	}
	function insertNewCompany($companyInfo){
		if( verifyCompany($companyInfo) == true){
			echo "Exist Company.";
			return;
		}
		$Name = $companyInfo->Name;
		$regAddress = $companyInfo->regAddress;
		$offAddress = $companyInfo->offAddress;
		$regNumber = $companyInfo->regNumber;
		$VATNumber = $companyInfo->VATNumber;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		// return "Before insert";
		$sql = "INSERT INTO company(strName, regAddress, offAddress, regNumber, VATNumber) VALUES('$Name', '$regAddress', '$offAddress', '$regNumber', '$VATNumber')";
		if( mysql_query($sql) == true){
			return "YES";
		}
		return $sql;
	}
	function updateCompany($Id, $companyInfo){
		$Name = $companyInfo->Name;
		$regAddress = $companyInfo->regAddress;
		$offAddress = $companyInfo->offAddress;
		$regNumber = $companyInfo->regNumber;
		$VATNumber = $companyInfo->VATNumber;

		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "UPDATE company SET strName='$Name', regAddress='$regAddress', offAddress='$offAddress', regNumber='$regNumber', VATNumber='$VATNumber' WHERE Id='$Id'";
		mysql_query($sql);
		return;
	}
	function deleteCompany($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM company WHERE Id='$Id'";
		mysql_query($sql);
	}
	function getCompany($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return array();
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM company WHERE $condition";
		$result = mysql_query($sql);
		$arrRetVal = array();
		while( $row = mysql_fetch_assoc($result) ){
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	// Branches
	function verifyBranches($branchesInfo){
		$Name = $branchesInfo->Name;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM branches WHERE strName='$Name'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			return true;
		}
		return false;
	}
	function insertNewBranches($branchesInfo){
		if( verifyBranches($branchesInfo)){
			echo "Exist Branches.";
			return;
		}
		$Name = $branchesInfo->Name;
		$regNumber = $branchesInfo->regNumber;
		$regAddress = $branchesInfo->regAddress;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "INSERT INTO branches(strName, regNumber, regAddress) VALUES('$Name', '$regNumber', '$regAddress')";
		if( mysql_query($sql) == true){
			return "YES";
		}
		return "NO";
	}
	function updateBranches($Id, $branchesInfo){
		$Name = $branchesInfo->Name;
		$regNumber = $branchesInfo->regNumber;
		$regAddress = $branchesInfo->regAddress;

		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "UPDATE branches SET Name='$Name', regNumber='$regNumber', regAddress='$regAddress' WHERE Id='$Id'";
		mysql_query($sql);
		return;
	}
	function deleteBranches($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM branches WHERE Id='$Id'";
		mysql_query($sql);
	}
	function getBranches($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM branches WHERE $condition";
		$result = mysql_query($sql);
		$arrRetVal = array();
		// var_dump( $result);
		while(	$row = mysql_fetch_assoc($result)){
			array_push( $arrRetVal, $row);
		}
		// var_dump($arrRetVal);
		return $arrRetVal;
	}
	function getBranchesFromCompany($companyId){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * FROM branches WHERE Id in (SELECT IdInfos FROM companyinfo WHERE infoTypes='branch' AND idCompany='$companyId')";
		// echo $sql;
		$result = mysql_query($sql);
		$arrRetVal = array();
		while( $row = mysql_fetch_assoc($result)){
			array_push($arrRetVal, $row);
		}
		return $arrRetVal;
	}
	function getLastBranchId(){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM branches ORDER BY Id DESC LIMIT 1";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			$Id = $row['Id'];
			return $Id;
		}
		return -1;
	}
	// Company info
	function verifyCompanyInfo($company_info){
		$idCompany = $company_info->idCompany;
		$idInfos = $company_info->idInfos;
		$infoTypes = $company_info->infoTypes;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM companyinfo WHERE idCompany='$idCompany' AND idInfos='$idInfos' AND infoTypes='$infoTypes'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			return true;
		}
		return false;
	}
	function insertNewCompanyInfo($company_info){
		if( verifyCompanyInfo($company_info)){
			echo "Exist Record.";
			return;
		}
		$idCompany = $company_info->idCompany;
		$idInfos = $company_info->idInfos;
		$infoTypes = $company_info->infoTypes;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "INSERT INTO companyinfo(idCompany, idInfos, infoTypes) VALUES('$idCompany', '$idInfos', '$infoTypes')";
		if( mysql_query($sql) == true){
			return "YES";
		}
		return "NO";
	}
	function updateCompanyInfo($Id, $company_info){
		$idCompany = $company_info->idCompany;
		$idInfos = $company_info->idInfos;
		$infoTypes = $company_info->infoTypes;

		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "UPDATE companyinfo SET idCompany='$idCompany', idInfos='$idInfos', infoTypes='$infoTypes' WHERE Id='$Id'";
		mysql_query($sql);
		return;
	}
	function deleteCompanyInfo($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM companyinfo WHERE Id='$Id'";
		mysql_query($sql);
	}
	function deleteCompanyInfoFromInfo($company_info){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM companyinfo WHERE idCompany='$company_info->idCompany' AND idInfos='$company_info->idInfos' AND infoTypes='$company_info->infoTypes'";
		mysql_query($sql);
	}
	function deleteCompanyInfoFromTypeNInfo($company_info){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM companyinfo WHERE idInfos='$company_info->idInfos' AND infoTypes='$company_info->infoTypes'";
		mysql_query($sql);
	}
	function getCompanyInfo($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM companyinfo WHERE $condition";
		$result = mysql_query($sql);
		$arrRetVal = array();
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	// Department  
	function verifyDepartment($department){
		$Name = $department->Name;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM department WHERE strName='$Name'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			return true;
		}
		return false;
	}
	function insertDepartment($department){
		if( verifyDepartment($department)){
			echo "Exist Department.";
			return;
		}
		$Name = $department->Name;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "INSERT INTO department(strName) VALUES('$Name')";
		if( mysql_query($sql) == true){
			return "YES";
		}
		return "NO";
	}
	function updateDepartment($Id, $department){
		$Name = $department->Name;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "UPDATE department SET Name='$Name' WHERE Id='$Id'";
		mysql_query($sql);
		return;
	}
	function deleteDepartment($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM department WHERE Id='$Id'";
		mysql_query($sql);
	}
	function getDepartment($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM department WHERE $condition";
		$result = mysql_query($sql);
		$arrRetVal = array();
		while(	$row = mysql_fetch_assoc($result)){
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	function getDepartmentFromCompany($companyId){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * FROM department WHERE Id in (SELECT IdInfos FROM companyinfo WHERE infoTypes='department' AND idCompany='$companyId')";
		// echo $sql;
		$result = mysql_query($sql);
		$arrRetVal = array();
		while( $row = mysql_fetch_assoc($result)){
			array_push($arrRetVal, $row);
		}
		return $arrRetVal;
	}
	function getLastDepartmentId(){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM department ORDER BY Id DESC LIMIT 1";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			$Id = $row['Id'];
			return $Id;
		}
		return -1;
	}
	// Posts
	function verifyPosts($posts){
		$Name = $posts->Name;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM posts WHERE strName='$Name'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			return true;
		}
		return false;
	}
	function insertPosts($posts){
		if( verifyPosts($posts)){
			echo "Exist Posts.";
			return;
		}
		$Name = $posts->Name;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "INSERT INTO posts(strName) VALUES('$Name')";
		if( mysql_query($sql) == true){
			return "YES";
		}
		return "NO";
	}
	function updatePosts($Id, $posts){
		$Name = $posts->Name;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "UPDATE posts SET Name='$Name' WHERE Id='$Id'";
		mysql_query($sql);
		return;
	}
	function deletePosts($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM posts WHERE Id='$Id'";
		mysql_query($sql);
	}
	function getPosts($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM posts WHERE $condition";
		$result = mysql_query($sql);
		$arrRetVal = array();
		while($row = mysql_fetch_assoc($result)){
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	function getPostsFromCompany($companyId){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * FROM posts WHERE Id in (SELECT IdInfos FROM companyinfo WHERE infoTypes='posts' AND idCompany='$companyId')";
		// echo $sql;
		$result = mysql_query($sql);
		$arrRetVal = array();
		while( $row = mysql_fetch_assoc($result)){
			array_push($arrRetVal, $row);
		}
		return $arrRetVal;
	}
	function getLastPostsId(){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM posts ORDER BY Id DESC LIMIT 1";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			$Id = $row['Id'];
			return $Id;
		}
		return -1;
	}
	// Schedule
	function verifySchedule($schedule){
		$Name = $schedule->Name;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM schedule WHERE strName='$Name'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			return true;
		}
		return false;
	}
	function insertSchedule($schedule){
		if( verifySchedule($schedule)){
			echo "Exist Posts.";
			return;
		}
		$Name = $schedule->Name;
		$Type = $schedule->Type;
		$ScheduleDate = $schedule->ScheduleDate;
		$ScheduleTime = $schedule->ScheduleTime;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "INSERT INTO schedule(strName, Type, ScheduleDate, ScheduleTime) VALUES('$Name', '$Type', '$ScheduleDate', '$ScheduleTime')";
		if( mysql_query($sql) == true){
			return "YES";
		}
		return "NO";
	}
	function updateSchedule($Id, $schedule){
		$Name = $schedule->Name;
		$Type = $schedule->Type;
		$ScheduleDate = $schedule->ScheduleDate;
		$ScheduleTime = $schedule->ScheduleTime;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "UPDATE schedule SET strName='$Name', Type='$Type', ScheduleDate='$ScheduleDate', ScheduleTime='$ScheduleTime' WHERE Id='$Id'";
		mysql_query($sql);
		return;
	}
	function deleteSchedule($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM schedule WHERE Id='$Id'";
		mysql_query($sql);
	}
	function getSchedule($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM schedule WHERE $condition";
		$result = mysql_query($sql);
		$arrRetVal = array();
		while( $row = mysql_fetch_assoc($result)){
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	function getlastScheduleId(){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM schedule ORDER BY Id DESC LIMIT 1";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			$Id = $row['Id'];
			return $Id;
		}
		return -1;
	}
	function getScheduleFromCompany($companyId){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * FROM schedule WHERE Id in (SELECT IdInfos FROM companyinfo WHERE infoTypes='schedule' AND idCompany='$companyId')";
		// echo $sql;
		$result = mysql_query($sql);
		$arrRetVal = array();
		while( $row = mysql_fetch_assoc($result)){
			array_push($arrRetVal, $row);
		}
		return $arrRetVal;
	}
	// Vacation
	function verifyVacation($vacation){
		$Name = $schedule->Name;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM vacation WHERE strName='$Name'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			return true;
		}
		return false;
	}
	function insertVacation($vacation){
		if( verifyVacation($schedule)){
			echo "Exist Posts.";
			return;
		}
		$Name = $schedule->Name;
		$Type = $schedule->Type;
		$Period = $schedule->Period;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "INSERT INTO vacation(strName, Type, Period) VALUES('$Name', '$Type', '$Period')";
		if( mysql_query($sql) == true){
			return "YES";
		}
		return "NO";
	}
	function updateVacation($Id, $schedule){
		$Name = $schedule->Name;
		$Type = $schedule->Type;
		$Period = $schedule->Period;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "UPDATE vacation SET strName='$Name', Type='$Type', Period='$Period' WHERE Id='$Id'";
		mysql_query($sql);
		return;
	}
	function deleteVacation($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM vacation WHERE Id='$Id'";
		mysql_query($sql);
	}
	function getVacation($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM vacation WHERE $condition";
		$result = mysql_query($sql);
		$arrRetVal = array();
		while( $row = mysql_fetch_assoc($result)){
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	// Illness
	function getIllness($illness){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM illness WHERE $condition";
		$result = mysql_query($sql);
		$arrRetVal = array();
		while( $row = mysql_fetch_assoc($result)){
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	// Alerts
	function setAlerts($idCompany, $alertsInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT Id FROM alerts WHERE idCompany='$idCompany'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$sql = "DELETE FROM alerts WHERE idCompany='$idCompany'";
			mysql_query($sql);
		}
		$sql = "INSERT INTO alerts( idCompany, isEmSMSNotAtWork, isEmEMLNotAtWork, timeNotAtWork, msgNotAtWork, isEmSMSDelay, isEmEMLDelay, isBrSMSDelay, isBrEMLDelay, isDeSMSDelay, isDeEMLDelay, timeDelay, msgDelay, isEmSMSChange, isEmEMLChange, isBrSMSChange, isBrEMLChange, isDeSMSChange, isDeEMLChange, msgChang) VALUES( '$idCompany', '$alertsInfo->isEmSMSNotAtWork', '$alertsInfo->isEmEMLNotAtWork', '$alertsInfo->timeNotAtWork', '$alertsInfo->msgNotAtWork', '$alertsInfo->isEmSMSDelay', '$alertsInfo->isEmEMLDelay', '$alertsInfo->isBrSMSDelay', '$alertsInfo->isBrEMLDelay', '$alertsInfo->isDeSMSDelay', '$alertsInfo->isDeEMLDelay', '$alertsInfo->timeDelay', '$alertsInfo->msgDelay', '$alertsInfo->isEmSMSChange', '$alertsInfo->isEmEMLChange', '$alertsInfo->isBrSMSChange', '$alertsInfo->isBrEMLChange', '$alertsInfo->isDeSMSChange', '$alertsInfo->isDeEMLChange', '$alertsInfo->msgChang')";
		if( mysql_query($sql) == true){
			return "YES";
		}
		return "NO";
	}
	function getAlerts( $idCompany){		
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * From alerts WHERE idCompany='$idCompany'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			return $row;
		}
		return "";
	}

?>