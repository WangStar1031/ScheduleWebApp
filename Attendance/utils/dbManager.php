<?php
	// Connection
	require_once("dbConnection.php");
	function ExecuteQuery($sql){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$conn->query($sql);
	}
	// User
	function RegisterUser( $name, $eMail, $pass){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT Id FROM users WHERE UserMail='".$eMail."';";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			return "0";
		}
		$VerifyCode = crypt($name.$pass,'');
		$sql = "INSERT INTO users(UserName, UserMail, Password, VerifyCode, VerifyStates) VALUES('$name','$eMail', '$pass','$VerifyCode', 'No')";
		if( $conn->query($sql) === TRUE){
			return $VerifyCode;
		}
		$conn->close();
		return "1";
	}
	function RegisterPowerUser( $name, $eMail, $pass){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT Id FROM users WHERE UserMail='".$eMail."';";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			return "0";
		}
		$VerifyCode = crypt($name.$pass,'');
		$sql = "INSERT INTO users(UserName, UserMail, Password, UserRole, VerifyCode, VerifyStates) VALUES('$name','$eMail', '$pass','PowerUser', '', 'Yes')";
		if( $conn->query($sql) === TRUE){
			return $VerifyCode;
		}
		$conn->close();
		return "1";
	}
	function RegisterUserWithRole( $name, $eMail, $pass, $role, $strUserId){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$VerifyCode = crypt($name.$pass,'');
		$sql = "SELECT Id FROM users WHERE UserId='".$strUserId."';";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$sql = "UPDATE users SET UserId='$strUserId' UserName='$name', UserMail='$eMail', Password='$pass', UserRole='$role', VerifyCode='$VerifyCode', VerifyStates='No' WHERE UserId='$strUserId'";
		} else{
			$companyId = getCompanyIdForEmployee($strUserId);
			$sql = "INSERT INTO users(UserId, CompanyId, UserName, UserMail, Password, UserRole, VerifyCode, VerifyStates) VALUES('$strUserId', '$companyId', '$name','$eMail', '$pass','$role', '$VerifyCode', 'No')";
		}
		if( $conn->query($sql) === TRUE){
			return $VerifyCode;
		}
		return "1";
	}
	function VerifyUserFromCode($verifyCode){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT UserName FROM users WHERE VerifyCode='$verifyCode' AND VerifyStates='No';";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
			$UserName = $row['UserName'];
			$sql = "UPDATE users SET VerifyStates='Yes' WHERE VerifyCode='$verifyCode'";
			$conn->query($sql);
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
		$sql = "SELECT * FROM users WHERE (UserName='$name' OR UserMail='$name') AND Password='$pass'";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
			$UserName = $row['UserName'];
			$UserRole = $row['UserRole'];
			$UserId = $row['UserId'];
			$ComId = $row['CompanyId'];
			return $UserName . '??' . $UserRole . '??' . $UserId . '??' . $ComId;
		}
		return "";
	}
	function getUserNameFromEmail($mail){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return "";
		}
		$sql = "SELECT UserName FROM users WHERE UserMail='$mail'";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			return true;
		}
		return false;
	}
	function insertNewEmployee($employeeInfo){
		// if( verifyEmployee($employeeInfo)){
		// 	echo "Exist Employee.";
		// 	return;
		// }
		$idTreeInfo = $employeeInfo->idTreeInfo;
		$Id = $employeeInfo->Id;
		$Name = $employeeInfo->Name;
		$SurName = $employeeInfo->SurName;
		$Code = $employeeInfo->Code;
		$Address = $employeeInfo->Address;
		$PhoneNumber = $employeeInfo->PhoneNumber;
		$Email = $employeeInfo->Email;
		$NFCNumber = $employeeInfo->NFCNumber;

		// $idBranches = $employeeInfo->idBranches;
		// $idDepartment = $employeeInfo->idDepartment;
		// $idSchedule = $employeeInfo->idSchedule;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		if($Id == 0){
			$sql = "INSERT INTO employee( idTreeInfo, strName, SurName, Code, Address, PhoneNumber, Email, NFCNumber) VALUES( '$idTreeInfo', '$Name', '$SurName', '$Code', '$Address', '$PhoneNumber', '$Email', '$NFCNumber')";
		} else{
			$sql = "UPDATE employee SET strName='$Name', SurName='$SurName', Code='$Code', Address='$Address', PhoneNumber='$PhoneNumber', Email='$Email', NFCNumber='$NFCNumber' WHERE Id='$Id'";
		}
		if( $conn->query($sql) === TRUE){
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
		$conn->query($sql);
		return;
	}
	function deleteEmployee($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM employee WHERE Id='$Id'";
		$conn->query($sql);
	}
	function deleteEmployeeFromTreeInfo($idTreeInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM employee WHERE idTreeInfo='$idTreeInfo'";
		$conn->query($sql);
	}
	function getEmployee($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM employee WHERE $condition";
		// echo $sql;
		$result = $conn->query($sql);
		$arrRetVal = array();
		while( $row = $result->fetch_assoc()){
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	function setPostIdToEmployee($idEmployee, $idPosts){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "UPDATE employee SET idPosts='$idPosts' WHERE Id='$idEmployee'";
		$conn->query($sql);	
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			return true;
		}
		return false;
	}
	function getCompanyIdForEmployee($idEmployee){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT idTreeInfo FROM employee WHERE Id='$idEmployee'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		return getCompanyIdFromTreeInfo($row['idTreeInfo']);
	}
	function getCompanyIdFromTreeInfo( $idTreeInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT * FROM treeinfo WHERE Id='$idTreeInfo'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		if( $row['idParents'] == 0){
			return $row['Id'];
		}
		$idTreeInfo = $row['idParents'];
		$sql = "SELECT * FROM treeinfo WHERE Id='$idTreeInfo'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		if( $row['idParents'] == 0){
			return $row['Id'];
		}
		$idTreeInfo = $row['idParents'];
		$sql = "SELECT * FROM treeinfo WHERE Id='$idTreeInfo'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		if( $row['idParents'] == 0){
			return $row['Id'];
		}
	}
	function getCompanyId($regNumber){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM company WHERE regNumber='$regNumber'";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
			return $row['Id'];
		}
		return 0;
	}
	function insertNewCompany($companyInfo){
		if( verifyCompany($companyInfo)){
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
		$sql = "INSERT INTO company(strName, regAddress, offAddress, regNumber, VATNumber) VALUES('$Name', '$regAddress', '$offAddress', '$regNumber', '$VATNumber')";
		if( $conn->query($sql) === TRUE){
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
		$conn->query($sql);
		return;
	}
	function updateCompanyFromTreeInfo($Id, $companyInfo){
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
		$sql = "SELECT Id FROM company WHERE idTreeInfo='$Id'";
		$result = $conn->query($sql);
		if( $result->num_rows == 0){
			$sql = "INSERT INTO company(idTreeInfo, strName, regAddress, offAddress, regNumber, VATNumber) VALUES('$Id', '$Name', '$regAddress', '$offAddress', '$regNumber', '$VATNumber')";
		} else{
			$sql = "UPDATE company SET strName='$Name', regAddress='$regAddress', offAddress='$offAddress', regNumber='$regNumber', VATNumber='$VATNumber' WHERE idTreeInfo='$Id'";
		}
		$conn->query($sql);
		return;
	}
	function deleteCompany($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM company WHERE Id='$Id'";
		$conn->query($sql);
	}
	function getCompany($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return array();
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM company WHERE $condition";
		// echo $sql;
		$result = $conn->query($sql);
		$arrRetVal = array();
		while( $row = $result->fetch_assoc() ){
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
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
		if( $conn->query($sql) === TRUE){
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
		$conn->query($sql);
		return;
	}
	function updateBranchFromTreeInfo($Id, $branchesInfo){
		$Name = $branchesInfo->Name;
		$regNumber = $branchesInfo->regNumber;
		$regAddress = $branchesInfo->regAddress;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM branches WHERE idTreeInfo='$Id'";
		$result = $conn->query($sql);
		if( $result->num_rows == 0){
			$sql = "INSERT INTO branches(idTreeInfo, strName, regNumber, regAddress) VALUES('$Id', '$Name', '$regNumber', '$regAddress')";
		} else{
			$sql = "UPDATE branches SET strName='$Name', regNumber='$regNumber', regAddress='$regAddress' WHERE idTreeInfo='$Id'";
		}
		$conn->query($sql);
		return;
	}
	function deleteBranches($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM branches WHERE Id='$Id'";
		$conn->query($sql);
	}
	function getBranches($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM branches WHERE $condition";
		$result = $conn->query($sql);
		$arrRetVal = array();
		while(	$row = $result->fetch_assoc()){
			array_push( $arrRetVal, $row);
		}
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
		$result = $conn->query($sql);
		$arrRetVal = array();
		while( $row = $result->fetch_assoc()){
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
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
		if( $conn->query($sql) === TRUE){
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
		$conn->query($sql);
		return;
	}
	function deleteCompanyInfo($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM companyinfo WHERE Id='$Id'";
		$conn->query($sql);
	}
	function deleteCompanyInfoFromInfo($company_info){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM companyinfo WHERE idCompany='$company_info->idCompany' AND idInfos='$company_info->idInfos' AND infoTypes='$company_info->infoTypes'";
		$conn->query($sql);
	}
	function deleteCompanyInfoFromTypeNInfo($company_info){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM companyinfo WHERE idInfos='$company_info->idInfos' AND infoTypes='$company_info->infoTypes'";
		$conn->query($sql);
	}
	function getCompanyInfo($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM companyinfo WHERE $condition";
		$result = $conn->query($sql);
		$arrRetVal = array();
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
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
		if( $conn->query($sql) === TRUE){
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
		$conn->query($sql);
		return;
	}
	function updateDepartmentFromTreeInfo($Id, $departmentInfo){
		$Name = $departmentInfo->Name;
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM branches WHERE idTreeInfo='$Id'";
		$result = $conn->query($sql);
		if( $result->num_rows == 0){
			$sql = "INSERT INTO department(idTreeInfo, strName) VALUES('$Id', '$Name')";
		} else{
			$sql = "UPDATE department SET strName='$Name' WHERE idTreeInfo='$Id'";
		}
		$conn->query($sql);
		return;
	}
	function deleteDepartment($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM department WHERE Id='$Id'";
		$conn->query($sql);
	}
	function getDepartment($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM department WHERE $condition";
		$result = $conn->query($sql);
		$arrRetVal = array();
		while(	$row = $result->fetch_assoc()){
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
		$result = $conn->query($sql);
		$arrRetVal = array();
		while( $row = $result->fetch_assoc()){
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			return true;
		}
		return false;
	}
	function insertNewPosts($postInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		if( $postInfo->Id == 0){
			$sql = "INSERT INTO posts(idTreeInfo, strCode, strProfession, strDetails) VALUES('$postInfo->idTreeInfo', '$postInfo->strCode', '$postInfo->strProfession', '$postInfo->strDetails')";
		} else{
			$sql = "UPDATE posts SET idTreeInfo='$postInfo->idTreeInfo', strCode='$postInfo->strCode', strProfession='$postInfo->strProfession', strDetails='$postInfo->strDetails' WHERE Id='$postInfo->Id'";
		}
		echo $sql;
		$conn->query($sql);
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
		if( $conn->query($sql) === TRUE){
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
		$conn->query($sql);
		return;
	}
	function deletePosts($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM posts WHERE Id='$Id'";
		$conn->query($sql);
	}
	function getPosts($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM posts WHERE $condition";
		$result = $conn->query($sql);
		$arrRetVal = array();
		while($row = $result->fetch_assoc()){
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
		$result = $conn->query($sql);
		$arrRetVal = array();
		while( $row = $result->fetch_assoc()){
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
			$Id = $row['Id'];
			return $Id;
		}
		return -1;
	}
	// Schedule
	function insertSchedule( $scheduleInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT Id FROM schedule WHERE idNode = '$scheduleInfo->idNode'";
		$result = $conn->query($sql);
		if($result->num_rows == 0){
			$sql = "INSERT INTO schedule(idNode, nodeType, ScheduleType, FieldDays, FieldTime) VALUES('$scheduleInfo->idNode', '$scheduleInfo->nodeType', '$scheduleInfo->strType', '$scheduleInfo->strPeriod', '$scheduleInfo->strTime')";
		} else{
			$sql = "UPDATE schedule SET idNode='$scheduleInfo->idNode', nodeType='$scheduleInfo->nodeType', ScheduleType='$scheduleInfo->strType', FieldDays='$scheduleInfo->strPeriod', FieldTime='$scheduleInfo->strTime' WHERE idNode='$scheduleInfo->idNode' AND nodeType='$scheduleInfo->nodeType'";
		}
		echo $sql;
		$conn->query($sql);
	}
	function deleteSchedule($idSchedule){		
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "DELETE FROM schedule WHERE Id='$idSchedule'";
		$conn->query($sql);
	}
	function getSchedule($idNode, $nodeType){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT * FROM schedule WHERE idNode='$idNode' AND nodeType='$nodeType'";
		$result = $conn->query($sql);
		$arrRetVal = array();
		while ($row = $result->fetch_assoc()) {
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	function getScheduleFromCondition($strCondition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		$sql = "SELECT * FROM schedule WHERE " . $strCondition;
		// return $sql;
		$result = $conn->query($sql);
		$arrRetVal = array();
		while ($row = $result->fetch_assoc()) {
			array_push( $arrRetVal, $row);
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
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
		if( $conn->query($sql) === TRUE){
			return "YES";
		}
		return "NO";
	}
	function insertNewVacation($vacationInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo "Connection failed: " . $conn->connect_error;
			return "";
		}
		if( $vacationInfo->Id == 0){
			$sql = "INSERT INTO vacation(idTreeInfo, strName, strDetails) VALUES('$vacationInfo->idTreeInfo', '$vacationInfo->strName', '$vacationInfo->strDetails')";
		} else{
			$sql = "UPDATE vacation SET idTreeInfo='$vacationInfo->idTreeInfo', strName='$vacationInfo->strName', strDetails='$vacationInfo->strDetails' WHERE Id='$vacationInfo->Id'";
		}
		echo $sql;
		$conn->query($sql);
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
		$conn->query($sql);
		return;
	}
	function deleteVacation($Id){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM vacation WHERE Id='$Id'";
		$conn->query($sql);
	}
	function getVacation($condition){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( !isset($condition))$condition = "1";
		$sql = "SELECT * FROM vacation WHERE $condition";
		$result = $conn->query($sql);
		$arrRetVal = array();
		while( $row = $result->fetch_assoc()){
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
		$result = $conn->query($sql);
		$arrRetVal = array();
		while( $row = $result->fetch_assoc()){
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$sql = "DELETE FROM alerts WHERE idCompany='$idCompany'";
			$conn->query($sql);
		}
		$sql = "INSERT INTO alerts( idCompany, isEmSMSNotAtWork, isEmEMLNotAtWork, timeNotAtWork, msgNotAtWork, isEmSMSDelay, isEmEMLDelay, isBrSMSDelay, isBrEMLDelay, isDeSMSDelay, isDeEMLDelay, timeDelay, msgDelay, isEmSMSChange, isEmEMLChange, isBrSMSChange, isBrEMLChange, isDeSMSChange, isDeEMLChange, msgChang) VALUES( '$idCompany', '$alertsInfo->isEmSMSNotAtWork', '$alertsInfo->isEmEMLNotAtWork', '$alertsInfo->timeNotAtWork', '$alertsInfo->msgNotAtWork', '$alertsInfo->isEmSMSDelay', '$alertsInfo->isEmEMLDelay', '$alertsInfo->isBrSMSDelay', '$alertsInfo->isBrEMLDelay', '$alertsInfo->isDeSMSDelay', '$alertsInfo->isDeEMLDelay', '$alertsInfo->timeDelay', '$alertsInfo->msgDelay', '$alertsInfo->isEmSMSChange', '$alertsInfo->isEmEMLChange', '$alertsInfo->isBrSMSChange', '$alertsInfo->isBrEMLChange', '$alertsInfo->isDeSMSChange', '$alertsInfo->isDeEMLChange', '$alertsInfo->msgChang')";
		if( $conn->query($sql) === TRUE){
			return "YES";
		}
		return "NO";
	}
	function setAlertsFromTreeInfo($idTreeInfo, $alertsInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT Id FROM alerts WHERE idCompany='$idTreeInfo'";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$sql = "DELETE FROM alerts WHERE idCompany='$idTreeInfo'";
			$conn->query($sql);
		}
		$sql = "INSERT INTO alerts( idCompany, isEmSMSNotAtWork, isEmEMLNotAtWork, timeNotAtWork, msgNotAtWork, isEmSMSDelay, isEmEMLDelay, isBrSMSDelay, isBrEMLDelay, isDeSMSDelay, isDeEMLDelay, timeDelay, msgDelay, isEmSMSChange, isEmEMLChange, isBrSMSChange, isBrEMLChange, isDeSMSChange, isDeEMLChange, msgChang) VALUES( '$idTreeInfo', '$alertsInfo->isEmSMSNotAtWork', '$alertsInfo->isEmEMLNotAtWork', '$alertsInfo->timeNotAtWork', '$alertsInfo->msgNotAtWork', '$alertsInfo->isEmSMSDelay', '$alertsInfo->isEmEMLDelay', '$alertsInfo->isBrSMSDelay', '$alertsInfo->isBrEMLDelay', '$alertsInfo->isDeSMSDelay', '$alertsInfo->isDeEMLDelay', '$alertsInfo->timeDelay', '$alertsInfo->msgDelay', '$alertsInfo->isEmSMSChange', '$alertsInfo->isEmEMLChange', '$alertsInfo->isBrSMSChange', '$alertsInfo->isBrEMLChange', '$alertsInfo->isDeSMSChange', '$alertsInfo->isDeEMLChange', '$alertsInfo->msgChang')";
		if( $conn->query($sql) === TRUE){
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
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
			return $row;
		}
		return "";
	}
	function getAlertsFromTreeInfo( $idTreeInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * From alerts WHERE idCompany='$idTreeInfo'";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
			return $row;
		}
		return "";
	}
	// TreeViewInfo
	function createNewTreeInfo($treeNode){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "INSERT INTO treeinfo(strName, Category, idParents) VALUES('$treeNode->strName', '$treeNode->Category', '$treeNode->idParents')";
		if( $conn->query($sql) === TRUE){
			return "YES";
		}
		return "NO";
	}
	function getLastTreeInfo(){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * FROM treeinfo ORDER BY Id DESC LIMIT 1";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			$row = $result->fetch_assoc();
			return $row;
		}
	}
	function getChildNodeCount($IdCurNode){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT count(*) AS ChildCount FROM treeinfo WHERE idParents = $IdCurNode";
		$result1 = $conn->query($sql);
		$row1 = $result1->fetch_assoc();
		return $row1['ChildCount'];
	}
	function getTreeChildInfo($idParents){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "";
		if( $idParents == 0){
			$sql = "SELECT * FROM treeinfo WHERE idParents = 0";
		} else{
			$sql = "SELECT * FROM treeinfo WHERE idParents = $idParents";
		}
		$result = $conn->query($sql);
		$arrRetVal = array();
		if( $result->num_rows > 0){
			while( $row = $result->fetch_assoc()){
				$row['ChildCount'] = getChildNodeCount($row['Id']);
				array_push( $arrRetVal, $row);
			}
		} else if($idParents == 0){
			$treeNode = new stdClass();
			$treeNode->strName = "New Company1";
			$treeNode->Category = "company";
			$treeNode->idParents = 0;
			createNewTreeInfo($treeNode);
			array_push( $arrRetVal, getLastTreeInfo());
		}
		return $arrRetVal;
	}
	function getTreeCurInfo($idCurNode){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * FROM treeinfo WHERE Id = $idCurNode";
		$result = $conn->query($sql);
		if( $result->num_rows>0){
			return $result->fetch_assoc();
		}
		return 0;
	}
	function updateTreeValue($idCurNode, $strName){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "UPDATE treeinfo SET strName='$strName' WHERE Id='$idCurNode'";
		// echo $sql;
		return $conn->query($sql);
	}
	function deleteTreeInfos($idTreeInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT Id FROM treeinfo WHERE Id='$idTreeInfo' OR idParents='$idTreeInfo'";
		$result = $conn->query($sql);
		while($row = $result->fetch_assoc()){
			$Id = $row['Id'];
			$sql = "DELETE FROM treeinfo WHERE Id='$Id'";
			$conn->query($sql);
			$sql = "DELETE FROM company WHERE idTreeInfo='$Id'";
			$conn->query($sql);
			$sql = "DELETE FROM branches WHERE idTreeInfo='$Id'";
			$conn->query($sql);
			$sql = "DELETE FROM department WHERE idTreeInfo='$Id'";
			$conn->query($sql);
			$sql = "DELETE FROM employee WHERE idTreeInfo='$Id'";
			$conn->query($sql);
		}
		echo "YES";
	}
	function getEmployeeVacation($idEmployee){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * FROM employeevacation WHERE idEmployee='$idEmployee'";
		$result = $conn->query($sql);
		$arrRetVal = array();
		while( $row = $result->fetch_assoc()){
			array_push( $arrRetVal, $row);
		}
		return $arrRetVal;
	}
	function deleteEmployeeVacation($idEmployee){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM employeevacation WHERE idEmployee='$idEmployee'";
		$conn->query($sql);
	}
	function setEmployeeVacation( $emVacationInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "INSERT INTO employeevacation(idEmployee, idVacation, strPeriod) VALUES('$emVacationInfo->idEmployee', '$emVacationInfo->idVacation', '$emVacationInfo->strPeriod')";
		$conn->query($sql);
	}
	function getAllTreeInfoId($idTreeInfo){
		$arrRetVal = array();
		array_push($arrRetVal, $idTreeInfo);
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT Id FROM treeinfo WHERE idParents='$idTreeInfo'";
		$result = $conn->query($sql);
		$arrBuff = array();
		while( $row = $result->fetch_assoc()){
			array_push($arrBuff, $row['Id']);
			array_push($arrRetVal, $row['Id']);
		}
		for( $i = 0; $i < count($arrBuff); $i++){
			$id = $arrBuff[$i];
			$sql = "SELECT Id FROM treeinfo WHERE idParents='$id'";
			$result = $conn->query($sql);
			while($row = $result->fetch_assoc()){
				array_push($arrRetVal, $row['Id']);
			}
		}
		return $arrRetVal;
	}
	function getTreeInfoCategory($idTreeInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT Category FROM treeinfo WHERE Id = '$idTreeInfo'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		return $row['Category'];
	}
	function getBranchName($idTreeInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * FROM treeinfo WHERE Id='$idTreeInfo'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$cat = $row['Category'];
		if( $cat == "Company"){
			return "";
		}
		if( $cat == "Branch"){
			return $row['strName'];
		}
		$sql = "SELECT * FROM treeinfo WHERE Id='".$row['idParents']."'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$cat = $row['Category'];
		if( $cat == "Branch"){
			return $row['strName'];
		}
		return "";
	}
	function getDepartmentName($idTreeInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * FROM treeinfo WHERE Id='$idTreeInfo'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		if( $row['Category'] == "Department"){
			return $row['strName'];
		}
		return "";
	}
	function insertScheduleTemplate($scheTempInfo, $idCompany){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		if( $scheTempInfo->Id == 0){
			$sql = "INSERT INTO scheduletemplate( idCompany, strName, strType, strPeriod, strTime) VALUES('$idCompany', '$scheTempInfo->strName', '$scheTempInfo->strType', '$scheTempInfo->strPeriod', '$scheTempInfo->strTime')";
		} else{
			$sql = "UPDATE scheduletemplate SET strName='$scheTempInfo->strName', strType='$scheTempInfo->strType', strPeriod='$scheTempInfo->strPeriod', strTime='$scheTempInfo->strTime' WHERE Id='$scheTempInfo->Id'";
		}
		// echo $sql;
		$conn->query($sql);
	}
	function deleteScheduleTemplate($idScheTemp){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "DELETE FROM scheduletemplate WHERE Id='$idScheTemp'";
		$conn->query($sql);
	}
	function getScheTemplate($idTreeInfo){
		$conn = getConnection();
		if( $conn->connect_error){
			echo("Connection failed: " . $conn->connect_error);
			return;
		}
		$sql = "SELECT * FROM scheduletemplate WHERE idCompany='$idTreeInfo'";
		$result = $conn->query($sql);
		$arrRetVal = array();
		while($row = $result->fetch_assoc()){
			array_push($arrRetVal, $row);
		}
		return $arrRetVal;
	}
?>