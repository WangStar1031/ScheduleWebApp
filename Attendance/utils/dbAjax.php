<?php
	if( $_SERVER['HTTP_HOST'] == 'localhost'||  $_SERVER['HTTP_HOST'] == '192.168.1.75'){
		include_once("dbManager.php");
	} else{
		include_once("dbManagerForServer.php");
	}
	if(isset($_POST['userMail'])){
		$userMail = $_POST['userMail'];
		$userPass = $_POST['userPass'];
		echo VerifyUserFromNamePass($userMail, $userPass);
	}
	if(isset($_POST['AddNewCompany'])){
		$company = new stdClass();
		$company->Name = $_POST['AddNewCompany'];
		$company->regAddress = $_POST['regAddress'];
		$company->offAddress = $_POST['offAddress'];
		$company->regNumber = $_POST['regNumber'];
		$company->VATNumber = $_POST['VATNumber'];
		echo insertNewCompany($company);
	}
	if( isset($_POST['EditCompany'])){
		$prevRegNumber = $_POST['EditCompany'];
		$Id = getCompanyId($prevRegNumber);
		if( $Id != 0){
			$company = new stdClass();
			$company->Name = $_POST['Name'];
			$company->regAddress = $_POST['regAddress'];
			$company->offAddress = $_POST['offAddress'];
			$company->regNumber = $_POST['regNumber'];
			$company->VATNumber = $_POST['VATNumber'];
			updateCompany( $Id, $company);
		}
	}
	if( isset($_POST['DeleteCompany'])){
		$regNumber = $_POST['DeleteCompany'];
		$Id = getCompanyId($regNumber);
		if( $Id != 0) deleteCompany($Id);
	}
	if( isset($_POST['getEmployees'])){
		$regNumber = $_POST['getEmployees'];
		$Id = getCompanyId($regNumber);
		if( $Id != 0){
			$arrEmployees = getEmployee('idCompany="$Id"');
			// echo json_encode($arrEmployees);
			$utf8string = html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", json_encode($arrEmployees)), ENT_NOQUOTES, 'UTF-8');
			echo $utf8string;
		}
	}
	if( isset($_POST['getEmployeesFromTreeInfo'])){
		$idTreeInfo = $_POST['getEmployeesFromTreeInfo'];
		$condition = '';
		if( isset($_POST['strOrder'])){
			$idSort = $_POST['strOrder'];
			$asc = $_POST['strASC'];
			$condition = 'idTreeInfo="'.$idTreeInfo.'" ORDER BY ' . $idSort . ' ' .$asc;
		} else{
			$condition = 'idTreeInfo="'.$idTreeInfo.'"';
		}
		$arrEmployees = getEmployee($condition);
		echo json_encode($arrEmployees);
	}
	if( isset($_POST['getBranches'])){
		$regNumber = $_POST['getBranches'];
		if( $regNumber == ""){
			echo json_encode(getBranches(1));
		} else{
			$companyId = getCompanyId($regNumber);
			if( $companyId != 0){
				echo json_encode(getBranchesFromCompany($companyId));
			}
		}
	}
	if( isset($_POST['getDepartment'])){
		$regNumber = $_POST['getDepartment'];
		if( $regNumber == ""){
			echo json_encode(getDepartment(1));
		} else{
			$companyId = getCompanyId($regNumber);
			if( $companyId != 0){
				echo json_encode(getDepartmentFromCompany($companyId));
			}
		}
	}
	if( isset($_POST['getPosts'])){
		$idTreeInfo = $_POST['getPosts'];
		$type = $_POST['type'];
		if( $type == "Company"){
			echo json_encode(getPosts('idTreeInfo='.$idTreeInfo));
		} else{
			$idTreeInfo = getCompanyIdForEmployee($idTreeInfo);
			echo json_encode(getPosts('idTreeInfo='.$idTreeInfo));
		}
	}
	if( isset($_POST['ConfirmBranch'])){
		$idBranch = $_POST['ConfirmBranch'];
		$branch = new stdClass();
		$branch->Name = $_POST['strBrName'];
		$branch->regNumber = $_POST['strBrRegNumber'];
		$branch->regAddress = $_POST['strBrRegAddr'];
		$isChecked = $_POST['isChecked'];
		$idCompany = getCompanyId($_POST['strCompanyRegNumber']);
		if( $idBranch == -1){
			if( insertNewBranches($branch) == "YES"){
				$idBranch = getLastBranchId();
				echo $idBranch;
			}
		} else{
			updateBranches($idBranch, $branch);
		}
		$companyInfo = new stdClass();
		$companyInfo->idCompany = $idCompany;
		$companyInfo->idInfos = $idBranch;
		$companyInfo->infoTypes = "branch";
		if( $isChecked == "true"){
			insertNewCompanyInfo($companyInfo);
		} else{
			deleteCompanyInfoFromInfo($companyInfo);
		}
	}
	if( isset($_POST['delPosts'])){
		$idPosts = $_POST['delPosts'];
		deletePosts($idPosts);
	}
	if( isset($_POST['delBranch'])){
		$idBranch = $_POST['delBranch'];
		deleteBranches($idBranch);
		$companyInfo = new stdClass();
		$companyInfo->idInfos = $idBranch;
		$companyInfo->infoTypes = "branch";
		deleteCompanyInfoFromTypeNInfo($companyInfo);
	}
	if( isset($_POST['delDepartment'])){
		$idDepartment = $_POST['delDepartment'];
		deleteDepartment($idDepartment);
		$companyInfo = new stdClass();
		$companyInfo->idInfos = $idDepartment;
		$companyInfo->infoTypes = "department";
		deleteCompanyInfoFromTypeNInfo($companyInfo);
	}
	if( isset($_POST['ConfirmDepartment'])){
		$idDepartment = $_POST['ConfirmDepartment'];
		$department = new stdClass();
		$department->Name = $_POST['strDeName'];
		$isChecked = $_POST['isChecked'];
		$idCompany = getCompanyId($_POST['strCompanyRegNumber']);
		if( $idDepartment == -1){
			if( insertDepartment($department) == "YES"){
				$idDepartment = getLastDepartmentId();
				echo $idDepartment;
			}
		} else{
			updateDepartment($idDepartment, $department);
		}
		$companyInfo = new stdClass();
		$companyInfo->idCompany = $idCompany;
		$companyInfo->idInfos = $idDepartment;
		$companyInfo->infoTypes = "department";
		if( $isChecked == "true"){
			insertNewCompanyInfo($companyInfo);
		} else{
			deleteCompanyInfoFromInfo($companyInfo);
		}
	}
	if( isset($_POST['ConfirmPosts'])){
		$idPosts = $_POST['ConfirmPosts'];
		$posts = new stdClass();
		$posts->Name = $_POST['strDeName'];
		$isChecked = $_POST['isChecked'];
		$idCompany = getCompanyId($_POST['strCompanyRegNumber']);
		if( $idPosts == -1){
			if( insertPosts($posts) == "YES"){
				$idPosts = getLastPostsId();
				echo $idPosts;
			}
		} else{
			updatePosts($idPosts, $posts);
		}
		$companyInfo = new stdClass();
		$companyInfo->idCompany = $idCompany;
		$companyInfo->idInfos = $idPosts;
		$companyInfo->infoTypes = "posts";
		if( $isChecked == "true"){
			insertNewCompanyInfo($companyInfo);
		} else{
			deleteCompanyInfoFromInfo($companyInfo);
		}
	}
	if( isset($_POST['getAlerts'])){
		echo json_encode(getAlertsFromTreeInfo( $_POST['getAlerts']));
	}
	if( isset($_POST['setAlerts'])){
		$idTreeInfo = $_POST['setAlerts'];
		$alerts = new stdClass();
		$alerts->isEmSMSNotAtWork = $_POST['isEmSMSNotAtWork'];
		$alerts->isEmEMLNotAtWork = $_POST['isEmEMLNotAtWork'];
		$alerts->timeNotAtWork = $_POST['timeNotAtWork'];
		$alerts->msgNotAtWork = $_POST['msgNotAtWork'];
		$alerts->isEmSMSDelay = $_POST['isEmSMSDelay'];
		$alerts->isEmEMLDelay = $_POST['isEmEMLDelay'];
		$alerts->isBrSMSDelay = $_POST['isBrSMSDelay'];
		$alerts->isBrEMLDelay = $_POST['isBrEMLDelay'];
		$alerts->isDeSMSDelay = $_POST['isDeSMSDelay'];
		$alerts->isDeEMLDelay = $_POST['isDeEMLDelay'];
		$alerts->timeDelay = $_POST['timeDelay'];
		$alerts->msgDelay = $_POST['msgDelay'];
		$alerts->isEmSMSChange = $_POST['isEmSMSChange'];
		$alerts->isEmEMLChange = $_POST['isEmEMLChange'];
		$alerts->isBrSMSChange = $_POST['isBrSMSChange'];
		$alerts->isBrEMLChange = $_POST['isBrEMLChange'];
		$alerts->isDeSMSChange = $_POST['isDeSMSChange'];
		$alerts->isDeEMLChange = $_POST['isDeEMLChange'];
		$alerts->msgChang = $_POST['msgChang'];
		echo setAlertsFromTreeInfo($idTreeInfo, $alerts);
	}
	if( isset($_POST['getTreeInfo'])){
		echo json_encode(getTreeCurInfo($_POST['getTreeInfo']));
	}
	if( isset($_POST['getCompanyFromTreeInfo'])){
		$idTreeInfo = $_POST['getCompanyFromTreeInfo'];
		echo json_encode(getCompany('idTreeInfo='.$idTreeInfo));
	}
	if(isset($_POST['updateCompanyFromTreeInfo'])){
		$Id = $_POST['updateCompanyFromTreeInfo'];
		$strName =  $_POST['strName'];
		updateTreeValue($Id, $strName);
		$company = new stdClass();
		$company->Name = $strName;
		$company->regAddress = $_POST['regAddress'];
		$company->offAddress = $_POST['offAddress'];
		$company->regNumber = $_POST['regNumber'];
		$company->VATNumber = $_POST['VATNumber'];
		updateCompanyFromTreeInfo( $Id, $company);
		echo "YES";
	}
	if( isset($_POST['updateBranchFromTreeInfo'])){
		$Id = $_POST['updateBranchFromTreeInfo'];
		$strName =  $_POST['strName'];
		updateTreeValue($Id, $strName);
		$branch = new stdClass();
		$branch->Name = $strName;
		$branch->regNumber = $_POST['regNumber'];
		$branch->regAddress = $_POST['regAddress'];
		updateBranchFromTreeInfo( $Id, $branch);
		echo "YES";		
	}
	if( isset($_POST['updateDepartmentFromTreeInfo'])){
		$Id = $_POST['updateDepartmentFromTreeInfo'];
		$strName =  $_POST['strName'];
		updateTreeValue($Id, $strName);
		$department = new stdClass();
		$department->Name = $strName;
		updateDepartmentFromTreeInfo( $Id, $department);
		echo "YES";		
	}
	if( isset($_POST['createNewTreeInfo'])){
		$treeNode = new stdClass();
		$treeNode->idParents = $_POST['createNewTreeInfo'];
		$treeNode->strName = $_POST['strName'];
		$treeNode->Category = $_POST['Category'];
		createNewTreeInfo($treeNode);
		$buf = getLastTreeInfo();
		echo $buf['Id'];
	}
	if( isset($_POST['getTreeChildInfo'])){
		echo json_encode(getTreeChildInfo($_POST['getTreeChildInfo']));
	}
	if( isset($_POST['getBranchFromTreeInfo'])){
		$idTreeInfo = $_POST['getBranchFromTreeInfo'];
		echo json_encode(getBranches('idTreeInfo='.$idTreeInfo));

	}
	if( isset($_POST['getDepartmentFromTreeInfo'])){
		$idTreeInfo = $_POST['getDepartmentFromTreeInfo'];
		echo json_encode(getDepartment('idTreeInfo='.$idTreeInfo));

	}
	if( isset($_POST['deleteTreeInfo'])){
		$idTreeInfo = $_POST['deleteTreeInfo'];
		deleteTreeInfos($idTreeInfo);
	}
	if( isset($_POST['insertNewEmployee'])){
		$employeeInfo = new stdClass();
		$employeeInfo->idTreeInfo = $_POST['insertNewEmployee'];
		$employeeInfo->Id = $_POST['Id'];
		$employeeInfo->Name = $_POST['Name'];
		$employeeInfo->SurName = $_POST['SurName'];
		$employeeInfo->Code = $_POST['Code'];
		$employeeInfo->Address = $_POST['Address'];
		$employeeInfo->PhoneNumber = $_POST['PhoneNumber'];
		$employeeInfo->Email = $_POST['Email'];
		$employeeInfo->NFCNumber = $_POST['NFCNumber'];
		insertNewEmployee($employeeInfo);
	}
	if( isset($_POST['deleteEmployeeFromTreeInfo'])){
		$idTreeInfo = $_POST['deleteEmployeeFromTreeInfo'];
		deleteEmployeeFromTreeInfo($idTreeInfo);
	}
	if( isset($_POST['delEmployee'])){
		deleteEmployee($_POST['delEmployee']);
	}
	if( isset($_POST['addNewSchedule'])){
		$scheduleInfo = new stdClass();
		$scheduleInfo->idNode = $_POST['addNewSchedule'];
		$scheduleInfo->nodeType = $_POST['nodeType'];
		$scheduleInfo->strType = $_POST['strType'];
		$scheduleInfo->strPeriod = $_POST['strPeriod'];
		$scheduleInfo->strTime = $_POST['strTime'];
		insertSchedule($scheduleInfo);
	}
	if( isset($_POST['delSchedule'])){
		$idSchedule = $_POST['delSchedule'];
	}
	if( isset($_POST['getSchedule'])){
		$idNode = $_POST['getSchedule'];
		$nodeType = $_POST['nodeType'];
		echo json_encode(getSchedule($idNode, $nodeType));
	}
	if( isset($_POST['insertPost'])){
		$postInfo = new stdClass();
		$postInfo->Id = $_POST['Id'];
		$postInfo->idTreeInfo = $_POST['insertPost'];
		$postInfo->strCode = $_POST['strCode'];
		$postInfo->strProfession = $_POST['strProfession'];
		$postInfo->strDetails = $_POST['strDetails'];
		insertNewPosts($postInfo);
	}
	if( isset($_POST['insertVacation'])){
		$vacationInfo = new stdClass();
		$vacationInfo->Id = $_POST['Id'];
		$vacationInfo->idTreeInfo = $_POST['insertVacation'];
		$vacationInfo->strName = $_POST['strName'];
		$vacationInfo->strDetails = $_POST['strDetails'];
		insertNewVacation($vacationInfo);
	}
	if( isset($_POST['getVacation'])){
		$idTreeInfo = $_POST['getVacation'];
		if( $_POST['type'] == "Company"){
			echo json_encode(getVacation("idTreeInfo=".$idTreeInfo));
		} else{
			$idTreeInfo = getCompanyIdForEmployee($idTreeInfo);
			echo json_encode(getVacation("idTreeInfo=".$idTreeInfo));
		}
	}
	if( isset($_POST['deleteVacation'])){
		$idVacation = $_POST['deleteVacation'];
		deleteVacation($idVacation);
	}
	if( isset($_POST['getEmployeeVacation'])){
		$idEmployee = $_POST['getEmployeeVacation'];
		echo json_encode(getEmployeeVacation($idEmployee));
	}
	if( isset($_POST['setPostIdToEmployee'])){
		$idEmployee = $_POST['setPostIdToEmployee'];
		$idPosts = $_POST['idPosts'];
		setPostIdToEmployee($idEmployee, $idPosts);
	}
	if( isset($_POST['setEmployeeVacation'])){
		$emVacationInfo = new stdClass();
		$emVacationInfo->idEmployee = $_POST['setEmployeeVacation'];
		deleteEmployeeVacation($emVacationInfo->idEmployee);
		if( $_POST['arrVacationIds'] != ""){
			$arrVacationIds = array();
			$arrVacationIds = explode(',', $_POST['arrVacationIds']);
			$arrPeriod = array();
			$arrPeriod = explode(',', $_POST['arrPeriod']);
			for( $i = 0; $i < count($arrVacationIds); $i++){
				$emVacationInfo->idVacation = $arrVacationIds[$i];
				$emVacationInfo->strPeriod = $arrPeriod[$i];
				setEmployeeVacation( $emVacationInfo);
			}
		}
	}
	if( isset($_POST['setEmployeeTreeInfo'])){
		$idEmployee = $_POST['setEmployeeTreeInfo'];
		$idTreeInfo = $_POST['idTreeInfo'];
		ExecuteQuery("UPDATE employee SET idTreeInfo='$idTreeInfo' WHERE Id='$idEmployee'");
	}
	if( isset($_POST['setEmployeeTreeInfos'])){
		$idEmployees = $_POST['setEmployeeTreeInfos'];
		$arrEmployee = explode(",", $idEmployees);
		$idTreeInfo = $_POST['idTreeInfo'];
		for( $i = 0; $i < count($arrEmployee); $i++){
			$idEmployee = $arrEmployee[$i];
			ExecuteQuery("UPDATE employee SET idTreeInfo='$idTreeInfo' WHERE Id='$idEmployee'");
		}
	}
	function getEmployeeSchedule($idEmployee, $idTreeInfo){
		$arrSchedule = getSchedule($idEmployee, "Employee");
		if( count($arrSchedule) != 0)
			return $arrSchedule;
		while( $idTreeInfo != 0){
			$arrTreeInfo = getTreeCurInfo($idTreeInfo);
			$arrSchedule = getSchedule($idTreeInfo, "TreeInfo");
			if( count($arrSchedule) != 0)
				return $arrSchedule;
			$idTreeInfo = $arrTreeInfo['idParents'];
		}
		return $arrSchedule;
	}
	if( isset($_POST['getReport'])){
		$idTreeInfo = $_POST['getReport'];
		$isAllView = $_POST['isAllView'];
		$reportCat = $_POST['reportCat'];
		$fromDate = $_POST['fromDate'];
		$tillDate = $_POST['tillDate'];
		$Category = getTreeInfoCategory( $idTreeInfo);

		$arrTreeInfos = array();
		if( $isAllView == "true"){
			$arrTreeInfos = getAllTreeInfoId($idTreeInfo);
		} else{
			array_push($arrTreeInfos, $idTreeInfo);
		}
		$arrEmployees = array();
		for( $i = 0; $i < count($arrTreeInfos); $i ++){
			$arrBuf = array();
			$arrBuf = getEmployee("idTreeInfo='".$arrTreeInfos[$i]."'");
			for($j = 0; $j < count($arrBuf); $j++){
				array_push($arrEmployees, $arrBuf[$j]);
			}
		}
		
		$dateFrom = new DateTime($fromDate);
		$dateTill = new DateTime($tillDate);
		$arrTableContents = array();
		for( $i = 0; $i < count($arrEmployees); $i++){
			$arrTr = array();
			$employee = $arrEmployees[$i];
			// if( $isAllView == "true"){
				// if( $Category == "Company" || $Category == "company"){
					$arrTr['Branch'] = getBranchName($employee['idTreeInfo']);
					$arrTr['Department'] = getDepartmentName($employee['idTreeInfo']);
				// } else if( $Category == "Branch" ||  $Category == "branch"){
					// $arrTr['Department'] = getDepartmentName($employee['idTreeInfo']);
				// }
			// }
			$arrTr['Name'] = $employee['strName'];
			$arrTr['SurName'] = $employee['SurName'];
			$idEmployee = $employee['Id'];
			$idPosts = $employee['idPosts'];
			$post = "";
			if( isset($idPosts)){
				$arrPosts = getPosts("Id=" . $idPosts);
				$post = $arrPosts[0]['strProfession'];
			}
			$arrTr['post'] = $post;
			$employeeVacation = getEmployeeVacation($idEmployee);
			$arrVacation = array();
			for( $iV = 0; $iV < count($employeeVacation); $iV++){
				$oneVacation = $employeeVacation[$iV];
				$strPeriod_V = $oneVacation['strPeriod'];
				$arrPeriod_V = array();
				$arrPeriod_V = explode("-", $strPeriod_V);
				$start_V = new DateTime($arrPeriod_V[0]);
				$end_V = new DateTime($arrPeriod_V[1]);
				for( $date_V = $start_V; strtotime($date_V->format("m/d/Y")) <= strtotime($end_V->format("m/d/Y")); $date_V->modify('+1 day')){
					if( strtotime( $date_V->format("m/d/Y")) >= strtotime($dateFrom->format("m/d/Y")) && strtotime($date_V->format("m/d/Y")) <= strtotime($dateTill->format("m/d/Y"))){
						$arrCurVacation = getVacation("Id = " . $oneVacation['idVacation']);
						$arrVacation[$date_V->format("Y:m:d")] = $arrCurVacation[0]['strDetails'];
					}
				}
			}
			$employeeSchedule = getEmployeeSchedule($idEmployee, $employee['idTreeInfo']);

			for( $date = $dateFrom; strtotime($date->format("m/d/Y")) <= strtotime($dateTill->format("m/d/Y")); $date->modify('+1 day')){
				$arrTr[$date->format("Y-m-d")] = "";
				$strDate4Search = $date->format("Y-m-d");
				if( isset($arrVacation[$date->format("Y:m:d")])){
					$arrTr[$strDate4Search] = $arrVacation[$date->format("Y:m:d")];
					// continue;
				}
				$isWorking = true;
				$strCondition = "idNode = " . $idEmployee . " AND FieldDays LIKE '%" . $strDate4Search . "%'";
				$arrRetBuf = getScheduleFromCondition($strCondition);
				// var_dump($arrRetBuf);
				// exit();
				if( count($arrRetBuf) == 0){
					$arrTr[$strDate4Search] = $arrTr[$strDate4Search] . "@@@OFF";
				}
				if( $employeeSchedule[0]['ScheduleType'] != "From/Till"){ 

				} else{ // Start/Working

				}
			}
			array_push($arrTableContents, $arrTr);
		}
		echo json_encode($arrTableContents);
	}
	if( isset($_POST['updateScheTemplate'])){
		$idCompany = $_POST['idCompany'];
		$contents = $_POST['updateScheTemplate'];
		$arrScheTemples = array();
		$arrScheTemples = explode('$$$', $_POST['updateScheTemplate']);
		for( $i = 0; $i < count($arrScheTemples); $i++){
			$oneScheTemp = $arrScheTemples[$i];
			$arrFields = array();
			$arrFields = explode('@@@', $oneScheTemp);
			$scheTempInfo = new stdClass();
			$scheTempInfo->Id = $arrFields[0];
			$scheTempInfo->strName = $arrFields[1];
			$scheTempInfo->strType = $arrFields[2];
			$scheTempInfo->strPeriod = $arrFields[3];
			$scheTempInfo->strTime = $arrFields[4];
			insertScheduleTemplate($scheTempInfo, $idCompany);
		}
	}
	if( isset( $_POST['deleteScheTemplate'])){
		$id = $_POST['deleteScheTemplate'];
		deleteScheduleTemplate($id);
	}
	if( isset($_POST['getScheTemplate'])){
		$id = $_POST['getScheTemplate'];
		echo json_encode(getScheTemplate($id));
	}
	if( isset($_POST['getScheduleTemplateInfo'])){
		$id = $_POST['getScheduleTemplateInfo'];
		$isEmployee = $_POST['isEmployee'];
		$idCompany = -1;
		if( $isEmployee == 'true'){
			$idCompany = getCompanyIdForEmployee($id);
		} else{
			$idCompany = getCompanyIdFromTreeInfo($id);
		}
		echo json_encode(getScheTemplate($idCompany));
	}
	if( isset($_POST['getEmployeeData'])) {
		$Id = $_POST['getEmployeeData'];
		$arrRet = getEmployee("Id=".$Id);
		echo json_encode( $arrRet);
	}
	if( isset($_POST['getPostCodeNProfession'])){
		$IdPost = $_POST['getPostCodeNProfession'];
		$arrRet = getPosts("Id=".$IdPost);
		echo json_encode($arrRet[0]);
	}
?>
