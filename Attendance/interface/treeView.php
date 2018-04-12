
<?php
	include_once("scheduletemplate.php");
?>

<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

 


<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script><script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script src="assets/js/mindmup-editabletable.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>
<script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/14082/FileSaver.js'></script>

<style type="text/css">
	.treeView{ list-style: none; height: 300px; overflow: auto; background-color: white; border: 1px solid black; margin-top: 20px; padding-left: 0px; }
	.EmployeeView table{ width: 100%; margin-top: 20px; }
	.treeView li:first-child{ font-weight: bold; background-color: #333; color: white; padding-bottom: 25px; }
	.treeView li{ padding: 15 0 15 0; cursor: pointer; width: 100%; }
	.spacePadding{ padding-left: 20px; }
	.treeViewId, .treeViewParentId, .treeViewDepth, .treeViewChildLoaded{ display: none; }
	/*.DetailsView{ width: 100%; }*/
	/*.DetailsPan{  }*/
	.DetailsPan{ height: 300px; overflow: auto; background-color: white; border: 1px solid black; margin-top: 20px; padding: 10px; }
	.DetailsView table{ width: 100%; }
	.DetailsView table td{ margin: 5px; padding: 5px; }
	.treeViewName{ margin-left: 10px; }
	.treeViewSelected{ background-color: #dcf; color: black; }
	.EmployeeActionBtns{ margin-top: 20px; }
	.EmployeeView table td{ background-color: white; border: 1px solid black; text-align: center;}
	#ScheduleTable { width: 100%; }
	#ScheduleTable button{ width: 50%; }
	#ScheduleTable td, #vacationTable td{ border: 1px solid #aaa; }
	#vacationTable button{ width: 100%; }
	.btnScheduleAdd{ margin-top: 20px; }
	.ScheduleTableSelected{ background-color: #0f8; }
	#postTable td, #vacationTable td{ border: 1px solid #aaa; padding: 0px;}
	#AllPosts { width: 100%; margin-top: 20px; }
	#EmPostsSelect{ width: 100%; }
	#postsFilter{ width: 100%; margin-top: 20px; }
	.SelectedRow{ background-color: #fa0; color: white; border: 1px solid #fa0;}
	#uploadExcelForm{ margin-bottom: 0px !important; }
	#customUploadLabel{ font-weight: normal; margin-bottom: 0px !important; }
	#ScheduleList { list-style: none; padding: 10px; }
	#emDetailsCalendar table th{ color: black; }
	#emDetailsCalendar table { font-size: 1em; }
	.EmPostsPan { margin-top: 30px; }
	.EmVacationPan .table-condensed th{ color: black; }
	.monthselect, .yearselect { color: black; }
	#EmVacationTable td { border: 1px solid #aaa; }
	#EmVacationTypeSelect { min-width: 100px; }
</style>
<div class="col-lg-4 col-md-12 col-xs-12">
	<ul class="treeView">
		<li>
			<div class="row col-lg-12 col-md-12 col-xs-12">
				<div class="col-lg-8 col-md-8 col-xs-8">Name</div>
				<div class="col-lg-4 col-md-4 col-xs-4">Category</div>
			</div>
		</li>
		<?php
			$arrCompanies = getTreeChildInfo(0);
			for( $i = 0; $i < count($arrCompanies); $i++){
				$Id = $arrCompanies[$i]['Id'];
				$strName = $arrCompanies[$i]['strName'];
				$Category = $arrCompanies[$i]['Category'];
				$ChildCount = $arrCompanies[$i]['ChildCount'];
				$icon = getIconForCategory($Category);
		?>
		<li onclick="treeItemClicked(<?= $Id ?>)">
			<div class="row col-lg-12 col-md-12 col-xs-12">
				<div class="col-lg-8 col-md-8 col-xs-8">
					<span class="<?php if($ChildCount == 0) echo 'HideItem'; ?> isChildNode" onclick="expandItem(<?= $Id ?>)"><i class="fa fa-plus" aria-hidden="true"></i></span>
					<span class="<?php if($ChildCount > 0) echo 'HideItem'; ?> isChildNode" onclick="collapseItem(<?= $Id ?>)"><i class="fa fa-minus" aria-hidden="true"></i></span>
					<span><?= $icon ?></span><span class="treeViewName"><?= $strName ?></span>
				</div>
				<div class="treeViewCategory col-lg-4 col-md-4 col-xs-4">Company</div>
				<div class="treeViewId"><?= $Id ?></div>
				<div class="treeViewParentId">0</div>
				<div class="treeViewDepth">0</div>
				<div class="treeViewChildLoaded">0</div>
			</div>		
		</li>
		<?php
			}
		?>

	</ul>
	<div class="btn-group">
		<button onclick="addChild()">Add Child</button>
		<button onclick="delNode()"></span>Delete</button>
		<button onclick="addCompany()" class="btn-success">Add Company</button>
	</div>
</div>
	
<div class="col-lg-8 col-md-12 col-xs-12">
	<div class="DetailsPan">
		<div class="DetailsView CompanyView HideItem col-lg-6 col-md-6 col-xs-6">
			<table>
				<tr>
					<td>Company Name</td>
					<td><input type="text" id="companyName"></td>
				</tr>
				<tr>
					<td>Reg Address</td>
					<td><input type="text" id="regAddress"></td>
				</tr>
				<tr>
					<td>Official Address</td>
					<td><input type="text" id="offAddress"></td>
				</tr>
				<tr>
					<td>Reg Number</td>
					<td><input type="text" id="regNumber"></td>
				</tr>
				<tr>
					<td>VAT Number</td>
					<td><input type="text" id="vatNumber"></td>
				</tr>
			</table>
			<button onclick="onSaveCompanyInfo()">Save</button>
			<h5>Company Settings</h5>
			<button class="btn-success alertSettingBtn" data-toggle="modal" data-target="#alertsModal" onclick="AlertSettingsOpen()">Alert</button>
			<button class="btn-success postsSettingBtn" data-toggle="modal" data-target="#postsModal" onclick="getPostsForTree()">Posts</button>
			<button class="btn-success vacationSettingBtn" data-toggle="modal" data-target="#vacationModal" onclick="VacationSettingsOpen()">Vacation</button>
			<button class="btn-success vacationSettingBtn" data-toggle="modal" data-target="#scheduleTemplateModal" onclick="ScheduleTemplateOpen()">Schedule Template</button>
		</div>
		<div class="DetailsView BranchView HideItem col-lg-6 col-md-6 col-xs-6">
			<table>
				<tr>
					<td>Branch Name</td>
					<td><input type="text" id="branchName"></td>
				</tr>
				<tr>
					<td>Reg Number</td>
					<td><input type="text" id="branchRegNumber"></td>
				</tr>
				<tr>
					<td>Reg Address</td>
					<td><input type="text" id="branchRegAddress"></td>
				</tr>
			</table>
			<button onclick="onSaveBranchInfo()">Save</button>
		</div>
		<div class="DetailsView DepartmentView HideItem col-lg-6 col-md-6 col-xs-6">
			<table>
				<tr>
					<td>Department Name</td>
					<td><input type="text" id="departmentName"></td>
				</tr>
			</table>
			<button onclick="onSaveDepartmentInfo()">Save</button>
		</div>
		<div class="col-lg-6 col-md-6 col-xs-6">
			<div class="SchedulePan HideItem">
				<h3 style="margin-top: 10px;">Schedule</h3>
				<label>Schedule Template : </label>
				<select id="ScheduleTemplateOption" onchange="ScheduleTempChanged()">
				</select>
				<div class="row col-lg-12 col-md-12 col-xs-12" style="padding-left: 0px; padding-right: 0px;">
					<div class="col-lg-6 col-md-6 col-xs-6">
						<label>Type : </label>
						<select id="chkScheduleType" onchange="changeScheduleType()">
							<option>From/Till</option>
							<option>Start/Working</option>
						</select><br><br>
						<input type="time" id="ScheTimeFirst" class="FirstTime">
						<input type="time" id="ScheTimeLast" class="LastTime">
					</div>
					<div class="col-lg-6 col-md-6 col-xs-6">
						<div style="width: 100%; height: 140px; border:1px solid #aaa; overflow: auto;">
							<ul id="ScheduleList">
							</ul>
						</div>
					</div>
				</div>
				<br>
				<div style="margin-top: 20px;" class="btn-group">
					<button class="btn-success btnScheduleEdit" onclick="onScheduleEdit()">Edit Schedule</button>
					<button class="btn-success btnScheduleSave" onclick="onScheduleSave()">Save Schedule</button>
				</div>
			</div>
		</div>
	</div>
</div>
<iframe id="iframeTag" name="iframeTag" class="HideItem"></iframe>
<div class="EmployeeView col-lg-12 col-md-12 col-xs-12">
	<table id="EmployeeTable">
		<tr>
			<th>Select</th>
			<th onclick="headerNameClicked()">Name <span></span></th>
			<th onclick="headerSurNameClicked()">SurName <span></span></th>
			<th>Code</th>
			<th>Address</th>
			<th>Phone Number</th>
			<th>Email</th>
			<th>NFCNumber</th>
			<th>Action</th>
		</tr>
	</table>
	<div class="btn-group EmployeeActionBtns">
		<button onclick="addEmployee()" class="btn-success">Add Employee</button>
		<button onclick="saveEmployeeData()" class="btn-success">Save Data</button>
		&nbsp&nbsp&nbsp&nbsp&nbsp
		<button onclick="employeeMoveTo()" class="btn-success">Move To</button>
		<button class="btn-success" id="myBtnFromFile">
			<form id="uploadExcelForm" target="iframeTag" action="./utils/importEmployees.php" enctype="multipart/form-data" method="post">
				<div id="uploadFilePicker">
					<label id="customUploadLabel" for="file-upload" class="custom-file-upload">
						Import From File
					</label>
					<input id="file-upload" name="upload" type="file" class="HideItem" />
					<input type="text" name="idTreeInfo" class="HideItem" id="fileTreeId">
				</div>
			</form>
		</button>
		<button onclick="exportToFile()" class="btn-success">Export To File</button>
		<button onclick="downloadTemplate()" class="btn-success">Download Template</button>
		<!-- <button onclick="ImportFromExcel()" class="btn-success">Import From Excel</button> -->
	</div>
	
</div>
	<!-- TreeView Modal -->
	<div class="modal fade" id="treeViewModal" role="dialog">
		<div class="modal-dialog">		
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>Please insert and select Values.</h4>
				</div>
				<div class="modal-body">
					<table>
						<tr>
							<td>Name</td>
							<td><input type="text" name="Name"></td>
						</tr>
						<tr>
							<td>Category</td>
							<td><select id="treeViewSelect" onchange="treeSelectChanged()"></select></td>
						</tr>
					</table>
					<div style="width: 100%; height: 20px;"></div>
					<div class="AddChildBranch">
						<table>
							<tr>
								<td>Reg Number</td>
								<td><input type="text" name="" id="AddChildBranchRegNumber"></td>
							</tr>
							<tr>
								<td>Reg Address</td>
								<td><input type="text" name="" id="AddChildBranchRegAddress"></td>
							</tr>
						</table>
					</div>
					<div class="AddChildDepartment">
						<table>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="treeViewAdd()">OK</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>			
		</div>
	</div>

	<!-- Alerts Modal -->
	<div class="modal fade" id="alertsModal" role="dialog">
		<div class="modal-dialog">		
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>Please set Company's Alerts.</h4>
				</div>
				<div class="modal-body">
					<?php
					include("alerts.php")
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="AlertSet()">OK</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>			
		</div>
	</div>


	<!-- Invite Modal -->
	<div class="modal fade" id="inviteModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>User Invitation</h4>
				</div>
				<input type="hidden" id="inviteUserId">
				<div class="modal-body">
					<table>
						<tr>
							<td><label>User Name</label></td>
						</tr>
						<tr>
							<td>
								<input type="text" id="inviteUserName" readonly style="width: 49%;">
								<input type="text" id="inviteUserSurName" readonly style="width: 49%;">
							</td>
						</tr>
						<tr>
							<td><label>User Email</label></td>
						</tr>
						<tr>
							<td>
								<input type="text" id="inviteUserMail" readonly style="width: 100%;">
							</td>
						</tr>
						<tr>
							<td><label>User Role</label></td>
						</tr>
						<tr>
							<td>
								<select id="inviteUserRole">
									<option>Power Admin</option>
									<option>Director</option>
									<option>Manager</option>
									<option>User</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><label>User Password</label></td>
						</tr>
						<tr>
							<td>
								<input type="text" id="inviteUserPass">
							</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="InviteSet()">OK</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Posts Modal -->
	<div class="modal fade" id="postsModal" role="dialog">
		<div class="modal-dialog">		
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>Company's Posts.</h4>
				</div>
				<div class="modal-body">
					<table id="postTable" style="width: 100%;">
						<tr>
							<th>Code</th>
							<th>Profession</th>
							<th>Details</th>
							<th>Action</th>
						</tr>
					</table>
					<input type="text" name="" id="postsFilter" onkeypress="postFilterKeyPressed(event)">
					<select id="AllPosts" class="selectpicker" data-live-search="true">
						
					</select>
					<button onclick="addPosts()" style="margin-top: 20px;">Add Posts</button>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="PostsSet()">OK</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>			
		</div>
	</div>
	<!-- Vacation Modal -->
	<div class="modal fade" id="vacationModal" role="dialog">
		<div class="modal-dialog">		
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>Company's Vacation.</h4>
				</div>
				<div class="modal-body">
					<table id="vacationTable" style="width: 100%;">
						<tr>
							<th>Name</th>
							<th>Details</th>
							<th>Action</th>
						</tr>
					</table>
					<button onclick="addVacation()" style="margin-top: 20px;">Add Vacation</button>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="VacationSet()">OK</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>			
		</div>
	</div>

	<!-- Employee Move To Modal -->
	<div class="modal fade" id="employeeMoveToModal" role="dialog">
		<div class="modal-dialog">		
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>Please Select Employee's position.</h4>
				</div>
				<div class="modal-body">
					<?php
					include_once("moveto.php");
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="TreeViewIdSet()">OK</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>			
		</div>
	</div>

	<!-- Employee Details Modal -->
	<div class="modal fade" id="emDetailsModal" role="dialog">
		<div class="modal-dialog">		
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>Employee's Detail</h4>
				</div>
				<div class="modal-body">
					<div class="EmSchedulePan">
						<div class="col-lg-5 col-md-5 col-xs-5">
							<table style="width: 100%;">
								<tr>
									<td><strong>Name</strong></td><td id="emDetailsName"></td>
								</tr>
								<tr>
									<td><strong>SurName</strong></td><td id="emDetailsSurName"></td>
								</tr>
							</table>
							<h4>Schedule</h4>
							<label>Schedule Template</label>
							<select id="emScheduleTemplateOption" onchange="onEmScheTempChanged()"></select><br><br>
							<label>Schedule Type</label>
							<select id="chkEmScheduleType" onchange="changeEmScheduleType()">
								<option>From/Till</option>
								<option>Start/Working</option>
							</select><br><br>
							<input type="time" id="emFirstTime">
							<input type="time" id="emLastTime">	
							<div class="EmPostsPan">
								<h4>Posts</h4>
								<label>Please select posts.</label>
								<select id="EmPostsSelect"></select>
							</div>					
						</div>
						<div class="col-lg-7 col-md-7 col-xs-7">							
							<div class="k-content" style="text-align: center;">
								<div id="emDetailsCalendar"></div>
							</div>
						</div>
	
					</div>
					<div class="EmVacationPan">
						<h4>Vacations</h4>
						<table id="EmVacationTable" style="width: 100%;">
							<tr>
								<th>VacationType</th>
								<th>Period</th>
								<th>Action</th>
							</tr>
						</table>
						<table style="margin-top: 20px; border-top: 1px solid #aaa; width: 100%;">
							<tr>
								<td style="width: 20%;">
									<select id="EmVacationTypeSelect" style="width: 100%;">
									</select>
								</td>
								<td>
									<input type="text" name="startDate">
									<input type="text" name="endDate">
									<!-- <input type="text" name="daterange" style="width: 100%;"> -->
								</td>
								<td><button id="EmAddVacation" onclick="onEmAddVacation()" style="width: 100%;">Add</button></td>
							</tr>
						</table>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="EmployeeSet()">OK</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>			
		</div>
	</div>

	<!-- Add Company Modal -->
	<div class="modal fade" id="addCompanyModal" role="dialog">
		<div class="modal-dialog">		
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>Company Details</h4>
				</div>
				<div class="modal-body">
					<table>
						<tr>
							<td>Company Name</td>
							<td><input type="text" name="" id="AddCompanyName"></td>
						</tr>
						<tr>
							<td>Reg Address</td>
							<td><input type="text" name="" id="AddCompanyRegAddr"></td>
						</tr>
						<tr>
							<td>Official Address</td>
							<td><input type="text" name="" id="AddCompanyOffAddr"></td>
						</tr>
						<tr>
							<td>Reg Number</td>
							<td><input type="text" name="" id="AddCompanyRegNumber"></td>
						</tr>
						<tr>
							<td>VAT Number</td>
							<td><input type="text" name="" id="AddCompanyVatNumber"></td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="CompanySet()">OK</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>			
		</div>
	</div>
<?php
	include("scheduleModal.php");
?>
<iframe id="my_iframe" style="display:none;"></iframe>
<script type="text/javascript">
	var nIdTreeInfo = 0;
	var strCategory = "";
	var nCurrentItemDepth = 0;
	var nCurRowNum = -1;
	var nCurColNum = -1;
	var strCurCell = "";
	var arrCurVacations = [];
	var arrCurPosts = [];
	var curEmployeeId = 0;
	var g_arrScheduleTemplates = [];
	var emCalendar;
	$('#EmployeeTable').editableTableWidget();
	$(function() {
		$('input[name="daterange"]').daterangepicker();
	});
	function addCompany(){
		if( userRole == "Manager"){
			alert("You can't create Company.");
			return;
		}
		$("#addCompanyModal").modal("toggle");
	}
	function treeViewAdd(){
		var strItemName = $("#treeViewModal table tr").eq(0).find("input").eq(0).val();
		var strItemCategory = $("#treeViewSelect").val();
		if( strItemName == ""){
			alert("Please type the Name.");
			return;
		}
		var strBranchName, strBranchRegNum, strBranchRegAdd;
		var strDepartmentName;
		if( strItemCategory == "Branch"){
			strBranchRegNum = $("#AddChildBranchRegNumber").val();
			strBranchRegAdd = $("#AddChildBranchRegAddress").val();
			if( strBranchRegNum == "" || strBranchRegAdd == ""){
				alert("Please insert the Branch info.")
				return;
			}
		} else{
		}
		var idParents = nIdTreeInfo;
		var strName = strItemName;
		var Category = strItemCategory;
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {createNewTreeInfo: idParents, strName:strName, Category:Category}
		}).done(function (d) {
			var newId = d;
			$("#treeViewModal").modal('toggle');
			var oneNode = [];
			oneNode['Id'] = newId;
			oneNode['ChildCount'] = 0;
			oneNode['strName'] = strName;
			oneNode['Category'] = Category;
			oneNode['idParents'] = idParents;//treeViewParentId, treeViewId
			var strHtml = makeNode(oneNode, nCurrentItemDepth*1 + 1);
			var elemLis = $(".treeView li");
			var parentLi = $(".treeView .treeViewId");
			var friendsLi = $(".treeView .treeViewParentId");
			var LastLi = $(".treeView li").filter( function(index){
				return ($(".treeView li").eq(index).find(".treeViewParentId").html() == idParents);
			});
			if( LastLi.length != 0){
				LastLi.eq(LastLi.length-1).after(strHtml);
			} else{
				var ParentLi = $(".treeView li").filter( function(index){
					return ($(".treeView li").eq(index).find(".treeViewId").html() == idParents);
				});
				ParentLi.eq(ParentLi.length-1).after(strHtml);
			}
			if( Category == "Branch"){
				$.ajax({
					type: 'POST',
					datatype:'JSON',
					url: './utils/dbAjax.php',
					data: {updateBranchFromTreeInfo: newId, strName:strName, regNumber:strBranchRegNum, regAddress:strBranchRegAdd}
				}).done(function (d) {
				});
			} else{
				$.ajax({
					type: 'POST',
					datatype:'JSON',
					url: './utils/dbAjax.php',
					data: {updateDepartmentFromTreeInfo: newId, strName:strName}
				}).done(function (d) {
				});
			}
		});
	}
	function onSaveCompanyInfo(){
		console.log('onSaveCompanyInfo');
		var strName = $("#companyName").val();
		var regAddress = $("#regAddress").val();
		var offAddress = $("#offAddress").val();
		var regNumber = $("#regNumber").val();
		var VATNumber = $("#vatNumber").val();
		if( strName == "" || regAddress == "" || offAddress == "" || regNumber == "" || VATNumber ==""){
			alert("Please insert the values.");
			return;
		}
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {updateCompanyFromTreeInfo: nIdTreeInfo, strName:strName, regAddress:regAddress, offAddress:offAddress, regNumber:regNumber, VATNumber:VATNumber}
		}).done(function (d) {
			if (d == "YES") {
				var elems = $(".treeView li");
				for( var i = 0; i < elems.length; i++){
					var elemLi = elems.eq(i);
					if( elemLi.find(".treeViewId").eq(0).html()==nIdTreeInfo){
						elemLi.find(".treeViewName").eq(0).html(strName);
					}
				}
			}
		});
	}
	function onSaveBranchInfo(){
		console.log('onSaveCompanyInfo');
		var strName = $("#branchName").val();
		var regNumber = $("#branchRegNumber").val();
		var regAddress = $("#branchRegAddress").val();
		if( strName == "" || regNumber == "" || regAddress == ""){
			alert("Please insert the values.");
			return;
		}
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {updateBranchFromTreeInfo: nIdTreeInfo, strName:strName, regNumber:regNumber, regAddress:regAddress}
		}).done(function (d) {
			if (d == "YES") {
				var elems = $(".treeView li");
				for( var i = 0; i < elems.length; i++){
					var elemLi = elems.eq(i);
					if( elemLi.find(".treeViewId").eq(0).html()==nIdTreeInfo){
						elemLi.find(".treeViewName").eq(0).html(strName);
					}
				}
			}
		});
	}
	function onSaveDepartmentInfo(){
		console.log('onSaveDepartmentInfo');
		var strName = $("#departmentName").val();
		if( strName == ""){
			alert("Please insert the values.");
			return;
		}
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {updateDepartmentFromTreeInfo: nIdTreeInfo, strName:strName}
		}).done(function (d) {
			if (d == "YES") {
				var elems = $(".treeView li");
				for( var i = 0; i < elems.length; i++){
					var elemLi = elems.eq(i);
					if( elemLi.find(".treeViewId").eq(0).html()==nIdTreeInfo){
						elemLi.find(".treeViewName").eq(0).html(strName);
					}
				}
			}
		});
	}
	function addChild(){
		if(nIdTreeInfo == 0)
			return;
		var elems = $(".treeView li");
		for( var i = 0; i < elems.length; i++){
			var elemLi = elems.eq(i);
			if( elemLi.find(".treeViewId").eq(0).html()==nIdTreeInfo){
				strCategory = elemLi.find(".treeViewCategory").eq(0).html();
				// nCurrentItemDepth = parseInt(elemLi.find(".treeViewChildLoaded").eq(0).html());
				break;
			}
		}
		if( strCategory == "Department"){
			return;
		}
		var arrCategories = [];
		switch( strCategory){
			case 'Company':
				arrCategories.push('Branch');arrCategories.push('Department');
				$(".AddChildBranch").removeClass("HideItem");
				$(".AddChildDepartment").addClass("HideItem");
				break;
			case 'Branch':
				arrCategories.push('Department');
				$(".AddChildBranch").addClass("HideItem");
				$(".AddChildDepartment").removeClass("HideItem");
				break;
		}
		var strHtml = "";
		for( var i = 0; i < arrCategories.length; i++){
			strHtml += '<option>' + arrCategories[i] + '</option>';
		}
		$("#treeViewSelect").html(strHtml);
		$("#treeViewModal").modal('toggle');
	}
	function getCompanyInfo(nId){
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getCompanyFromTreeInfo: nId}
		}).done(function (d) {
			$("#companyName").val("");
			$("#regAddress").val("");
			$("#offAddress").val("");
			$("#regNumber").val("");
			$("#vatNumber").val("");
			// console.log(d);
			var retVal = JSON.parse(d);
			// console.log(retVal);
			$("#companyName").val( retVal[0]['strName']);
			$("#regAddress").val( retVal[0]['regAddress']);
			$("#offAddress").val( retVal[0]['offAddress']);
			$("#regNumber").val( retVal[0]['regNumber']);
			$("#vatNumber").val( retVal[0]['VATNumber']);
		});
	}
	function getBranchInfo(nId){
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getBranchFromTreeInfo: nId}
		}).done(function (d) {
			// console.log(d);
			var retVal = JSON.parse(d);
			console.log(retVal);
			if( retVal.length == 0){
				$("#branchName").val("");
				$("#branchRegNumber").val("");
				$("#branchRegAddress").val("");
				return;
			}
			$("#branchName").val( retVal[0]['strName']);
			$("#branchRegNumber").val( retVal[0]['regNumber']);
			$("#branchRegAddress").val( retVal[0]['regAddress']);
		});
	}
	function getDepartmentInfo(nId){
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getDepartmentFromTreeInfo: nId}
		}).done(function (d) {
			// console.log(d);
			var retVal = JSON.parse(d);
			console.log(retVal);
			if( retVal.length == 0){
				$("#departmentName").val("");
				return;
			}
			$("#departmentName").val( retVal[0]['strName']);
		});
	}
	function drawEmployeeView(nId, strOrder, strASC){
		var elemTrs = $(".EmployeeView table tr").filter(function(index){
			return (index != 0);
		});
		elemTrs.remove();
		var data = {};
		if( strOrder == ""){
			data = {getEmployeesFromTreeInfo: nIdTreeInfo}; 
		} else{
			data = {getEmployeesFromTreeInfo: nIdTreeInfo, strOrder:strOrder, strASC: strASC};
		}
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: data
		}).done(function (d) {
			var arrEmployees = JSON.parse(d);
			for( var i = 0; i < arrEmployees.length; i++){
				var employee = arrEmployees[i];
				var strHtml = '';
				strHtml += '<tr>';
					strHtml += '<td class="HideItem">' + employee['Id'] + '</td>';
					strHtml += '<td><button style="width:100%; height:100%;" onclick="SelectRowBtnClicked(this)">Sel</button></td>';
					strHtml += '<td>' + employee['strName'] + '</td>';
					strHtml += '<td>' + employee['SurName'] + '</td>';
					if( userRole != "Manager"){
						strHtml += '<td>' + employee['Code'] + '</td>';
					}
					strHtml += '<td>' + employee['Address'] + '</td>';
					strHtml += '<td>' + employee['PhoneNumber'] + '</td>';
					strHtml += '<td>' + employee['Email'] + '</td>';
					strHtml += '<td>' + employee['NFCNumber'] + '</td>';
					strHtml += '<td class="HideItem">' + employee['idPosts'] + '</td>';
					strHtml += '<td><button onclick="detailsBtnClicked(this)" style="width:33%;">Details</button><button onclick="delBtnClicked(this)" style="width:33%;">Delete</button><button onclick="inviteBtnClicked(this)" style="width:33%;">Invite</button></td>';
				strHtml += '</tr>';
				$("#EmployeeTable tr:last").after(strHtml);
			}
			$('#EmployeeTable').editableTableWidget();
			$('#EmployeeTable tr').find("td:last").attr("tabindex","");
			$('#EmployeeTable tr').find("td:nth-child(1)").attr("tabindex","");
			if(userRole == "Manager"){
				$("#EmployeeTable th").filter(function(index){
					return $("#EmployeeTable th").eq(index).html() == "Code";
				}).remove();

			}
		});
	}
	function drawTreeItemInfos(nId){
		$("#fileTreeId").val(nIdTreeInfo);
		$(".DetailsPan .SchedulePan").removeClass("HideItem");
		var elemLis = $(".treeView li");
		for( var i = 0; i < elemLis.length; i++){
			var ElemLi = elemLis.eq(i);
			ElemLi.find("div").eq(0).removeClass("treeViewSelected");
			if( ElemLi.find(".treeViewId").html() == nIdTreeInfo){
				ElemLi.find("div").eq(0).addClass("treeViewSelected");
				nCurrentItemDepth = ElemLi.find(".treeViewDepth").eq(0).html() * 1;
			}
		}
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getTreeInfo: nId}
		}).done(function (d) {
			// console.log(d);
			$(".DetailsView").addClass("HideItem");
			var retVal = JSON.parse(d);
			strCategory = retVal['Category'];
			switch( retVal['Category']){
				case 'company':case 'Company':
					$(".CompanyView").removeClass("HideItem");
					getCompanyInfo(retVal['Id']);
					break;
				case 'branch':case 'Branch':
					$(".BranchView").removeClass("HideItem");
					getBranchInfo(retVal['Id']);
					break;
				case 'department':case 'Department':
					$(".DepartmentView").removeClass("HideItem");
					getDepartmentInfo(retVal['Id']);
					break;
			}
			getScheduleContents(nIdTreeInfo, "TreeInfo");
		});
		drawEmployeeView(nId, "", "");
	}
	function treeItemClicked(nId){
		if( nIdTreeInfo == nId)
			return;
		nIdTreeInfo = nId;

		getScheduleTemplates(nIdTreeInfo, false);
		drawTreeItemInfos(nId);
	}
	function getScheduleContents(nId, strCategory){
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getSchedule: nId, nodeType:strCategory}
		}).done(function (d) {
			$("#chkScheduleType").val("");
			$("#ScheTimeFirst").val("");
			$("#ScheTimeLast").val("");
			$("#ScheduleList").html("");
			var arrSchedules = JSON.parse(d);
			if( arrSchedules.length == 0) 
				return;
			var schedule = arrSchedules[0];
			$("#chkScheduleType").val(schedule['ScheduleType']);
			var strTime = schedule['FieldTime'];
			var arrTime = strTime.split("-");
			$("#ScheTimeFirst").val(arrTime[0]);
			$("#ScheTimeLast").val(arrTime[1]);
			var strPeriod = schedule['FieldDays'];
			var arrPeriod = strPeriod.split(",");
			var strHtml = "";
			for( var i = 0; i < arrPeriod.length; i++){
				strHtml += "<li>"+arrPeriod[i]+"</li>";
			}
			$("#ScheduleList").html(strHtml);
		});
	}
	function loadEmployeeSchedule(idEmployee){  // must be changed
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getSchedule: idEmployee, nodeType:"Employee"}
		}).done(function (d) {
			$("#emScheduleTemplateOption").prop("selectedIndex",0);
			$("#chkEmScheduleType").prop("selectedIndex",0);
			$("#emFirstTime").val("");
			$("#emLastTime").val("");

			var arrSchedules = JSON.parse(d);
			if( arrSchedules.length == 0)return;
			var schedule = arrSchedules[0];
			$("#chkEmScheduleType").val(schedule['ScheduleType']);
			var strTime = schedule['FieldTime'];
			var arrTime = strTime.split("-");
			$("#emFirstTime").val( arrTime[0]);
			$("#emLastTime").val( arrTime[1]);
			var strDates = schedule['FieldDays'];
			var arrDates = strDates.split(",");
			var arrCalDates = [];
			for( var i = 0; i < arrDates.length; i++){
				arrCalDates.push(new Date(arrDates[i]));
			}
			emCalendar.selectDates(arrCalDates);
		});
	}
	var curEmployeePostsId = 0;
	function loadEmployeePosts(idEmployee){
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getPosts: idEmployee, type:"Employee"}
		}).done(function (d) {
			console.log(d);
			$("#EmPostsSelect").prop("selectedIndex", 0);

			arrCurPosts = [];
			var arrPosts = JSON.parse(d);
			var strHtml = "";
			var nCurPostsIndex = 0;
			for( var i = 0; i < arrPosts.length; i++){
				var post = arrPosts[i];
				arrCurPosts.push(post['Id']);
				if( post['Id'] == curEmployeePostsId){
					nCurPostsIndex = i;
				}
				strHtml += "<option>" + post['strCode'] + "-" + post['strProfession'] + "</option>";
			}
			$("#EmPostsSelect").html(strHtml);
			$("#EmPostsSelect").prop("selectedIndex", nCurPostsIndex);
		});
	}
	function loadEmployeeVacation(idEmployee){
		$("#EmVacationTable tr").filter(function(index){
			return index > 0;
		}).remove();
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getVacation: idEmployee, type:"Employee"}
		}).done(function (d) {
			$("#EmVacationTable tr").filter(function(index){
				return index > 0;
			}).remove();
			arrCurVacations = [];
			var arrVacations = JSON.parse(d);
			var strHtml = "";
			for( var i = 0; i < arrVacations.length; i++){
				var vacation = arrVacations[i];
				arrCurVacations.push(vacation['Id']);
				strHtml += "<option>" + vacation['strName'] + "</option>";
			}
			$("#EmVacationTypeSelect").html(strHtml);
			$.ajax({
				type: 'POST',
				datatype: 'JSON',
				url: './utils/dbAjax.php',
				data: {getEmployeeVacation: idEmployee}
			}).done(function(d){
				var arrEmVacations = JSON.parse(d);
				var strHtml = "";
				for( var i = 0; i < arrEmVacations.length; i++){
					var emVacation = arrEmVacations[i];
					var idVacation = emVacation['idVacation'];
					var strVacationName = $("#EmVacationTypeSelect option").eq(arrCurVacations.indexOf(idVacation)).html();
					var strPeriod = emVacation['strPeriod'];
					strHtml += "<tr>";
						strHtml += "<td class='HideItem'>" + idVacation + "</td>";
						strHtml += "<td>" + strVacationName + "</td>";
						strHtml += "<td>" + strPeriod + "</td>";
						strHtml += "<td><button onclick='deleteEmployeeVacation(this)' style='width:100%;'>Delete</button></td>";
					strHtml += "</tr>";
				}
				$("#EmVacationTable tr:last").after(strHtml);
			});
		});		
	}
	function detailsBtnClicked(_this){
		var elemTr = $("#EmployeeTable tr").eq(_this.parentElement.parentElement.rowIndex);
		var idEmployee = elemTr.find("td").eq(0).html();
		$("#emDetailsName").html(elemTr.find("td").eq(2).html());
		$("#emDetailsSurName").html(elemTr.find("td").eq(3).html());
		curEmployeeId = idEmployee;
		curEmployeePostsId = elemTr.find("td").eq(8).html();
		loadEmployeeSchedule(idEmployee);
		loadEmployeePosts(idEmployee);
		loadEmployeeVacation(idEmployee);

		$("#emDetailsModal").modal("toggle");
	}
	function EmployeeSet(){
		var strEmScheduleType = $("#chkEmScheduleType").val();
		var strDays = "";
		var strTime = "";
		var strFirstTime = $("#emFirstTime").val();
		var strLastTime = $("#emLastTime").val();
		strTime = strFirstTime + "-" + strLastTime;
		var arrDates = emCalendar.selectDates();
		var arrSelectedDates = [];
		for( var i = 0; i < arrDates.length; i++){
			var nMonth = 1 + arrDates[i].getMonth();
			var nDay = arrDates[i].getDate();
			nMonth = (nMonth > 9 ? nMonth : "0" + nMonth);
			nDay = (nDay > 9 ? nDay : "0" + nDay);
			var strDate = (1900 + arrDates[i].getYear()) + "-" + nMonth + "-" + nDay
			arrSelectedDates.push(strDate);
		}
		strDays = arrSelectedDates.join(",");
		// Write DB - Schedule // please get idEmployee.
		if( strDays != "" && strTime != "-"){
			$.ajax({
				type: 'POST',
				datatype:'JSON',
				url: './utils/dbAjax.php',
				data: { addNewSchedule:curEmployeeId, nodeType:"Employee", strType:strEmScheduleType, strPeriod:strDays, strTime:strTime}
			}).done(function (d) {
			});
		}
		
		var nPostsIndex = $("#EmPostsSelect").prop("selectedIndex");
		var idPosts = arrCurPosts[nPostsIndex];
		// Write DB- employee table - idPosts
		$.ajax({
			type:'POST',
			datatype:'JSON',
			url:'./utils/dbAjax.php',
			data:{ setPostIdToEmployee:curEmployeeId, idPosts:idPosts}
		}).done(function(d){
		});

		var elemTrs = $("#EmVacationTable tr");
		var arrVacationIds = [];
		var arrPeriod = [];
		for( var i = 1; i < elemTrs.length; i++){
			var idVacation = $("#EmVacationTable tr").eq(i).find("td").eq(0).html();
			arrVacationIds.push( idVacation);
			var strPeriod = $("#EmVacationTable tr").eq(i).find("td").eq(2).html();
			arrPeriod.push(strPeriod);
		}
		// Write DB - employeevacation 
		$.ajax({
			type:'POST',
			datatype:'JSON',
			url:'./utils/dbAjax.php',
			data:{ setEmployeeVacation:curEmployeeId, arrVacationIds:arrVacationIds.join(","), arrPeriod:arrPeriod.join(",")}
		}).done(function(d){
		});
		$("#emDetailsModal").modal("toggle");
	}
	function onEmAddVacation(){
		var curId = $("#EmVacationTypeSelect").prop("selectedIndex");
		if( curId == -1){
			alert("You have to create and select the Vacation.\n To create vacation, you have to click the vacation in company information part.");
			return;
		}
		var strVacationName = $("#EmVacationTypeSelect").val();
		var strFromDate = $(".EmVacationPan table:last").find("input").eq(0).val();
		var strEndDate = $(".EmVacationPan table:last").find("input").eq(1).val();
		var strPeriod = strFromDate + "-" + strEndDate;
		var strHtml = "";
		strHtml += "<tr>";
			strHtml += "<td class='HideItem'>" + arrCurVacations[curId] + "</td>";
			strHtml += "<td>" + strVacationName + "</td>";
			strHtml += "<td>" + strPeriod + "</td>";
			strHtml += "<td><button onclick='deleteEmployeeVacation(this)' style='width:100%;'>Delete</button></td>";
		strHtml += "</tr>";
		console.log(strHtml);
		$("#EmVacationTable tr:last").after(strHtml);
	}
	function getIconForCategory(_Category){
		switch (_Category) {
			case 'Company':case 'company':
				return '<i class="fa fa-university" aria-hidden="true"></i>';
			case 'Branch':case 'branch':
				return '<i class="fa fa-building" aria-hidden="true"></i>';
			case 'Department':case 'department':
				return '<i class="fa fa-users" aria-hidden="true"></i>';
			case 'Employee':case 'employee':
				return '<i class="fa fa-user" aria-hidden="true"></i>';
		}
	}
	function makeNode(oneNode, nDepth){
		var strRetVal = "";
		console.log(oneNode['ChildCount']);
		strRetVal += '<li onclick="treeItemClicked('+oneNode['Id']+')">';
			strRetVal += '<div class="row col-lg-12 col-md-12 col-xs-12">';
				strRetVal += '<div class="col-lg-8 col-md-8 col-xs-8">';
				for( var jj = 0; jj < nDepth; jj++){
					strRetVal += '<span class="spacePadding"> </span>';
				}
					strRetVal += '<span class="'+(oneNode['ChildCount']=="0" ? "HideItem" : "")+' isChildNode" onclick="expandItem('+oneNode['Id']+')"><i class="fa fa-plus" aria-hidden="true"></i></span>';
					strRetVal += '<span class="'+(oneNode['ChildCount']=="0" ? "" : "HideItem")+' isChildNode" onclick="collapseItem('+oneNode['Id']+')"><i class="fa fa-minus" aria-hidden="true"></i></span> ';
					strRetVal += '<span>'+getIconForCategory(oneNode['Category'])+'</span><span class="treeViewName">'+oneNode['strName']+'</span> ';
				strRetVal += '</div>';
				strRetVal += '<div class="treeViewCategory col-lg-4 col-md-4 col-xs-4">'+oneNode['Category']+'</div>';
				strRetVal += '<div class="treeViewId">'+oneNode['Id']+'</div>';
				strRetVal += '<div class="treeViewParentId">'+oneNode['idParents']+'</div>';
				strRetVal += '<div class="treeViewDepth">'+nDepth+'</div>';
				strRetVal += '<div class="treeViewChildLoaded">0</div>';
			strRetVal += '</div>';
		strRetVal += '</li>';
		return strRetVal;
	}
	function deleteChildNodes(nId){
		$(".treeView li").filter(function(index){
			return ($(".treeView li").eq(index).find(".treeViewParentId").eq(0).html() == nId);
		}).remove();
	}
	function collapseItem(nId){
		console.log("collapseItem"+nId);
		var curElemLi = $(".treeView li").filter(function(index){
			return ($(".treeView li").eq(index).find(".treeViewId").eq(0).html() == nId);
		}).eq(0);
		var ElemLis = $(".treeView li").filter(function(index){
			return ($(".treeView li").eq(index).find(".treeViewParentId").eq(0).html() == nId);
		});
		for( var i = 0; i < ElemLis.length; i++){
			deleteChildNodes(ElemLis.eq(0).find(".treeViewId").html());
		}
		ElemLis.remove();
		if( ElemLis.length == 0)return;
		var curElemLi = $(".treeView li").filter(function(index){
			return ($(".treeView li").eq(index).find(".treeViewId").eq(0).html() == nId);
		}).eq(0);
		curElemLi.find(".isChildNode").eq(0).removeClass("HideItem");
		curElemLi.find(".isChildNode").eq(1).addClass("HideItem");
	}
	function expandItem(nId){
		var elemLis = $(".treeView li");
		for ( var i = 1; i < elemLis.length; i++){
			var elemLi = elemLis.eq(i);
			var nCurId = elemLi.find(".treeViewId").html();
			if( nId != nCurId) continue;
			var curLi = elemLi;
			curLi.find(".isChildNode").eq(0).addClass("HideItem");
			curLi.find(".isChildNode").eq(1).removeClass("HideItem");
			var isLoaded = elemLi.find(".treeViewChildLoaded").html();
			if( isLoaded == 0){
				$.ajax({
					type: 'POST',
					datatype:'JSON',
					url: './utils/dbAjax.php',
					data: {getTreeChildInfo: nId}
				}).done(function (d) {
					var nDepth = curLi.find(".treeViewDepth").eq(0).html();
					var retVal = JSON.parse(d);
					console.log(retVal);
					var strHtml = '';
					for( var j = 0; j < retVal.length; j++){
						var oneNode = retVal[j];
						strHtml += makeNode(oneNode, nDepth*1+1);
						// console.log(strHtml);
					}
					curLi.after(strHtml);
				});				
			} else{

			}
		}
	}
	function delNode(){
		if(nIdTreeInfo == 0){
			return;
		}
		var curNode = $(".treeView li").filter(function(index){
			return $(".treeView li").eq(index).find(".treeViewId").eq(0).html() == nIdTreeInfo;
		});
		if( userRole != 'PowerUser'){
			var nDepth = curNode.find(".treeViewDepth").eq(0).html();
			if(nDepth == 0){
				alert("You can't delete Company.");
				return;
			}
		}
		var r = confirm("Are you sure delete current item?");
		if( r != true){
			return;
		}
		if( $(".treeView li").filter(function(index){
			return ($(".treeView li").eq(index).find(".treeViewParentId").eq(0).html() == nIdTreeInfo);
		}).length != 0){
			alert("You have to delete child nodes to delete current node.");
			return;
		}
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {deleteTreeInfo: nIdTreeInfo}
		}).done(function (d) {
			$(".treeView li").filter(function(index){
				return ($(".treeView li").eq(index).find(".treeViewId").eq(0).html() == nIdTreeInfo || $(".treeView li").eq(index).find(".treeViewParentId").eq(0).html() == nIdTreeInfo);
			}).remove();
			$(".treeViewReport li").filter(function(index){
				return ($(".treeViewReport li").eq(index).find(".treeViewIdReport").eq(0).html() == nIdTreeInfo || $(".treeViewReport li").eq(index).find(".treeViewParentIdReport").eq(0).html() == nIdTreeInfo);
			}).remove();
			$(".treeViewMoveto li").filter(function(index){
				return ($(".treeViewMoveto li").eq(index).find(".treeViewIdMoveto").eq(0).html() == nIdTreeInfo || $(".treeViewMoveto li").eq(index).find(".treeViewParentIdMoveto").eq(0).html() == nIdTreeInfo);
			}).remove();
		});
	}
	function delBtnClicked(_this){
		if( userRole == 'Manager'){
			alert("You can't delete Employees.");
			return;
		}
		var r = confirm("Are you sure delete current item?");
		if( r != true){
			return;
		}
		var elemTr = $("#EmployeeTable tr").eq(_this.parentElement.parentElement.rowIndex);
		var idEmployee = elemTr.find("td").eq(0).html();
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {delEmployee: idEmployee}
		}).done(function (d) {
			$("#EmployeeTable tr").eq(_this.parentElement.parentElement.rowIndex).remove();
		});
	}
	function deleteEmployeeVacation(_this){
		var elemTr = $("#EmVacationTable tr").eq(_this.parentElement.parentElement.rowIndex);
		elemTr.remove();
	}
	function addEmployee(){
		if( nIdTreeInfo == 0)
			return;
		var strHtml = '';
		strHtml += '<tr>';
			strHtml += '<td class="HideItem">0</td>';
			strHtml += '<td><button style="width:100%; height:100%;" onclick="SelectRowBtnClicked(this)">Sel</button></td>';
			for( var i = 0; i < 7; i++){
				strHtml += '<td></td>';
			}
			strHtml += '<td class="HideItem">0</td>';
			strHtml += "<td><button onclick='detailsBtnClicked(this)' style='width:33%;'>Details</button><button onclick='delBtnClicked(this)' style='width:33%;'>Delete</button><button onclick='inviteBtnClicked(this)' style='width:33%;'>Invite</button></td>"
		strHtml += '</tr>';
		console.log(strHtml);
		$("#EmployeeTable tr:last").after(strHtml);
		$('#EmployeeTable').editableTableWidget();
		$('#EmployeeTable tr').find("td:last").attr("tabindex","");
		$('#EmployeeTable tr').find("td:nth-child(1)").attr("tabindex","");
	}
	function saveEmployeeData(){
		// $("#EmployeeTable input").remove();
		var ElemTrs = $("#EmployeeTable tr");
		for( var i = 1; i < ElemTrs.length; i++){
			var strId = ElemTrs.eq(i).find("td").eq(0).html();
			var strName = ElemTrs.eq(i).find("td").eq(2).html();
			var strSurName = ElemTrs.eq(i).find("td").eq(3).html();
			var strCode = ElemTrs.eq(i).find("td").eq(4).html();
			var strAddress = ElemTrs.eq(i).find("td").eq(5).html();
			var strPhoneNumber = ElemTrs.eq(i).find("td").eq(6).html();
			var strEmail = ElemTrs.eq(i).find("td").eq(7).html();
			var strNFCNumber = ElemTrs.eq(i).find("td").eq(8).html();
			$.ajax({
				type: 'POST',
				datatype:'JSON',
				url: './utils/dbAjax.php',
				data: {insertNewEmployee: nIdTreeInfo, Id:strId, Name:strName, SurName:strSurName, Code:strCode, Address:strAddress, PhoneNumber:strPhoneNumber, Email:strEmail, NFCNumber:strNFCNumber}
			}).done(function (d) {

			});
		}
	}
	function deleteSchedule(_this){
		var elemTr = $("#ScheduleTable tr").eq(_this.parentElement.parentElement.rowIndex);
		var idSchedule = elemTr.find("td").eq(0).html();
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {delSchedule: idSchedule}
		}).done(function (d) {
			$("#ScheduleTable tr").eq(_this.parentElement.parentElement.rowIndex).remove();
		});
	}
	function changeScheduleType(){
		var scheduleType = $("#chkScheduleType").val();
		$(".schedules").removeClass("HideItem");
		if( scheduleType == "Days of week"){
			$(".schedules").eq(1).addClass("HideItem");
		} else{
			$(".schedules").eq(0).addClass("HideItem");
		}
	}
	function AlertSet(){
		SaveAlerts(nIdTreeInfo);
		$("#alertsModal").modal('toggle');
	}
	function AlertSettingsOpen(){
		loadAlerts(nIdTreeInfo);
	}
	function VacationSettingsOpen(){
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getVacation: nIdTreeInfo, type:"Company"}
		}).done(function (d) {
			var arrVacations = JSON.parse(d);
			$("#vacationTable tr").filter(function(index){
				return index > 0;
			}).remove();
			var strHtml = "";
			for( var i = 0; i < arrVacations.length; i++){
				var vacation = arrVacations[i];
				strHtml += "<tr>";
					strHtml += "<td class='HideItem'>"+vacation['Id']+"</td>";
					strHtml += "<td>"+vacation['strName']+"</td>";
					strHtml += "<td>"+vacation['strDetails']+"</td>";
					strHtml += "<td><button onclick='deleteVacation(this)'>Delete</button></td>"
				strHtml += "</tr>";
			}
			$("#vacationTable tr:last").after(strHtml);
			$('#vacationTable').editableTableWidget();
			$('#vacationTable tr').find("td:last").attr("tabindex","");
		});
	}
	function PostsSet(){
		var ElemTrs = $("#postTable tr");
		for( var i = 1; i < ElemTrs.length; i++){
			var strId = ElemTrs.eq(i).find("td").eq(0).html();
			var strCode = ElemTrs.eq(i).find("td").eq(1).html();
			var strProfession = ElemTrs.eq(i).find("td").eq(2).html();
			var strDetails = ElemTrs.eq(i).find("td").eq(3).html();
			$.ajax({
				type:'POST',
				datatype:'JSON',
				url: './utils/dbAjax.php',
				data: {insertPost: nIdTreeInfo, Id: strId, strCode:strCode, strProfession:strProfession, strDetails:strDetails}
			}).done(function(d){

			});
		}
		$("#postsModal").modal('toggle');
	}
	var arrProfs = [];
	function addPosts(){
		var selIndex = $("#AllPosts").prop("selectedIndex");
		// var idSelPosts = $("#AllPosts option").eq(selIndex).find("ID").eq(0).html();
		// debugger;
		var strPosts = Profs[arrProfs[selIndex]];
		var arrBuf = [];
		arrBuf = strPosts.split("@@@");
		var strCode = arrBuf[0];
		var strName = arrBuf[1];
		var strDetails = arrBuf[2];
		strDetails = strDetails.length > 20 ? strDetails.substring(0, 20) + "..." : strDetails;
		var elemExisting = $("#postTable tr").filter(function(index){
			return $("#postTable tr").eq(index).find("td").eq(1).html() == strCode;
		});
		if( elemExisting.length > 0){
			alert("Existing Posts.");
			return;
		}
		var strHtml = "";
		strHtml += "<tr><td class='HideItem'>0</td><td>"+strCode+"</td><td>"+strName+"</td><td>"+strDetails+"</td><td><button style='width:100%;' onclick='deletePosts(this)'>Delete</button></td></tr>";
		$('#postTable tr:last').after(strHtml);
		// $('#postTable').editableTableWidget();
		$('#postTable tr').find("td:last").attr("tabindex","");
	}
	function deletePosts(_this){
		var elemTr = $("#postTable tr").eq(_this.parentElement.parentElement.rowIndex);
		var idPost = elemTr.find("td").eq(0).html();
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {delPosts: idPost}
		}).done(function (d) {
			elemTr.remove();
		});
	}
	function getPostsForTree(){
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {getPosts: nIdTreeInfo, type:"Company"}
		}).done(function (d) {
			$('#postTable tr').filter(function(index){
				return (index > 0);
			}).remove();
			var arrPosts = JSON.parse(d);
			var strHtml = "";
			for( var i = 0; i < arrPosts.length; i++){
				var posts = arrPosts[i];
				strHtml += "<tr>";
					strHtml += "<td class='HideItem'>"+posts['Id']+"</td>";
					strHtml += "<td>"+posts['strCode']+"</td>";
					strHtml += "<td>"+posts['strProfession']+"</td>";
					strHtml += "<td>"+posts['strDetails']+"</td>";
					strHtml += "<td><button style='width:100%;' onclick='deletePosts(this)'>Delete</button></td>";
				strHtml += "</tr>";
			}
			$("#postTable tr:last").after(strHtml);
			// $('#postTable').editableTableWidget();
			$('#postTable tr').find("td:last").attr("tabindex","");
		});
	}
	function addVacation(){
		var elemTrs = $("#vacationTable tr");
		var strHtml = "";
		strHtml += "<tr>";
			strHtml += "<td class='HideItem'>0</td><td></td><td></td><td><button onclick='deleteVacation(this)'>Delete</button></td>";
		strHtml += "</tr>";
		$("#vacationTable tr:last").after(strHtml);
		$('#vacationTable').editableTableWidget();
		$('#vacationTable tr').find("td:last").attr("tabindex","");
	}
	function deleteVacation(_this){
		var r = confirm("Are you sure delete current item?");
		if( r != true){
			return;
		}
		nRowIdx = _this.parentElement.parentElement.rowIndex;
		var elemTr = $("#vacationTable tr").eq( nRowIdx);
		var strId = $("#vacationTable tr").eq( nRowIdx).find("td").eq(0).html();
		elemTr.remove();
		if( strId != ""){
			$.ajax({
				type:'POST',
				datatype:'JSON',
				url: './utils/dbAjax.php',
				data: {deleteVacation: strId}
			}).done(function(d){
			});
		}

	}
	function VacationSet(){
		var ElemTrs = $("#vacationTable tr");
		for( var i = 1; i < ElemTrs.length; i++){
			var strId = ElemTrs.eq(i).find("td").eq(0).html();
			var strName = ElemTrs.eq(i).find("td").eq(1).html();
			var strDetails = ElemTrs.eq(i).find("td").eq(2).html();
			$.ajax({
				type:'POST',
				datatype:'JSON',
				url: './utils/dbAjax.php',
				data: {insertVacation: nIdTreeInfo, Id: strId, strName:strName, strDetails:strDetails}
			}).done(function(d){

			});
		}
		$("#vacationModal").modal('toggle');
	}
	function onScheduleSave(){
		var strType = $("#chkScheduleType").val();
		var strPeriod = "";
		var strTime = "";
		var elemLis = $("#ScheduleList li");
		var arrPeriod = [];
		for( var i = 0; i < elemLis.length; i++){
			arrPeriod.push(elemLis.eq(i).html());
		}
		strPeriod = arrPeriod.join(",");
		strTime = $("#ScheTimeFirst").val() + "-" + $("#ScheTimeLast").val();
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {addNewSchedule: nIdTreeInfo, nodeType:"TreeInfo", strType:strType, strPeriod:strPeriod, strTime:strTime}
		}).done(function (d) {
		});
	}
	function changeEmScheduleType(){
		var strType = $("#chkEmScheduleType").val();
		if( strType != "Period"){
			$(".forEmDays").removeClass("HideItem");
			$(".forEmPeriod").addClass("HideItem");
		} else{
			$(".forEmDays").addClass("HideItem");
			$(".forEmPeriod").removeClass("HideItem");
		}
	}
	var nCurEmployeeRowIdx = -1;
	function movetoBtnClicked(_this){
		nCurEmployeeRowIdx = _this.parentElement.parentElement.rowIndex;
		var elemTr = $("#EmployeeTable tr").eq( nCurEmployeeRowIdx);
		curEmployeeId = elemTr.find("td").eq(0).html();
		$("#employeeMoveToModal").modal("toggle");
	}
	function treeSelectChanged(){
		var selectedPart = $("#treeViewSelect").val();
		if( selectedPart == "Branch"){
			$(".AddChildBranch").removeClass("HideItem");
			$(".AddChildDepartment").addClass("HideItem");
		} else{
			$(".AddChildBranch").addClass("HideItem");
			$(".AddChildDepartment").removeClass("HideItem");
		}
	}
	function CompanySet(){
		var strName = $("#AddCompanyName").val();
		var Category = "Company";
		var strRegAddr = $("#AddCompanyRegAddr").val();
		var strOffAddr = $("#AddCompanyOffAddr").val();
		var strRegNumber = $("#AddCompanyRegNumber").val();
		var strVatNumber = $("#AddCompanyVatNumber").val();
		if( strName == "" || strRegAddr == "" || strOffAddr == "" || strRegNumber == "" || strVatNumber == ""){
			alert("Please insert Campany infos.");
			return;
		}
		$.ajax({
			type: 'POST',
			datatype:'JSON',
			url: './utils/dbAjax.php',
			data: {createNewTreeInfo: 0, strName:strName, Category:Category}
		}).done(function (d) {
			var newId = d;
			var oneNode = [];
			oneNode['Id'] = newId;
			oneNode['ChildCount'] = 0;
			oneNode['strName'] = strName;
			oneNode['Category'] = Category;
			oneNode['idParents'] = 0;
			var strHtml = makeNode(oneNode, 0);
			$(".treeView li:last").after(strHtml);
			strHtml = makeNodeReport(oneNode, 0);
			$(".treeViewReport li:last").after(strHtml);
			strHtml = makeNodeMoveto(oneNode, 0);
			$(".treeViewMoveto li:last").after(strHtml);
			var regAddress = strRegAddr;
			var offAddress = strOffAddr;
			var regNumber = strRegNumber;
			var VATNumber = strVatNumber;
			$.ajax({
				type: 'POST',
				datatype:'JSON',
				url: './utils/dbAjax.php',
				data: {updateCompanyFromTreeInfo: newId, strName:strName, regAddress:regAddress, offAddress:offAddress, regNumber:regNumber, VATNumber:VATNumber}
			}).done(function (d) {
				$("#addCompanyModal").modal("toggle");
			});
		});
	}
	function setAllPosts(){
		// AllPosts
		var strHtml = "";
		arrProfs = [];
		for( var i = 1; i < Profs.length; i++){
			var strProf = Profs[i];
			var arrBuf = strProf.split("@@@");
			arrProfs.push(i);
			strHtml += "<option>" + arrBuf[0] + " - " + arrBuf[1] + "</option>";
		}
		$("#AllPosts").html(strHtml);
	}
	setAllPosts();
	function postFilterKeyPressed(e){
		console.log(e.keyCode);
		if( e.keyCode == 13){
			var strFilter = $("#postsFilter").val();
			arrProfs = [];
			var strHtml = "";
			for( var i = 1; i < Profs.length; i++){
				var strProf = Profs[i];
				var arrBuf = strProf.split("@@@");
				if( arrBuf[1].toLowerCase().indexOf(strFilter) > -1){
					arrProfs.push(i);
					strHtml += "<option>" + arrBuf[0] + " - " + arrBuf[1] + "</option>";
				}
			}
			$("#AllPosts").html(strHtml);
		}
	
	}
	var isMultiSelected = false;
	function TreeViewIdSet(){
		var selectedDiv = $("#employeeMoveToModal .treeViewSelected");
		if(selectedDiv.length == 0){
			alert("Please select the node.");
			return;
		}
		var nToTreeInfo = selectedDiv.find(".treeViewIdMoveto").html();
		if( nIdTreeInfo == nToTreeInfo){
			$("#employeeMoveToModal").modal("toggle");
			return;
		}
		if( isMultiSelected == false){
			$.ajax({
				type: 'POST',
				datatype:'JSON',
				url: './utils/dbAjax.php',
				data: {setEmployeeTreeInfo: curEmployeeId, idTreeInfo:nToTreeInfo}
			}).done(function (d) {
				$("#EmployeeTable tr").eq( nCurEmployeeRowIdx).remove();
				$("#employeeMoveToModal").modal("toggle");
				isMultiSelected = false;
			});
		} else{
			var ElemSels = $("#EmployeeTable tr").filter(function(index){
				return $("#EmployeeTable tr").eq(index).find("button").eq(0).hasClass("SelectedRow");
			});
			var arrSels = [];
			for( var i = 0; i < ElemSels.length; i++){
				arrSels.push(ElemSels.eq(i).find("td").eq(0).html());
			}
			console.log(arrSels);
			$.ajax({
				type: 'POST',
				datatype:'JSON',
				url: './utils/dbAjax.php',
				data: {setEmployeeTreeInfos: arrSels.join(","), idTreeInfo:nToTreeInfo}
			}).done(function (d) {
				ElemSels.remove();
				$("#employeeMoveToModal").modal("toggle");
				isMultiSelected = false;
			});
		}	
	}
	function SelectRowBtnClicked(_this){
		var elemTr = $("#EmployeeTable tr").eq(_this.parentElement.parentElement.rowIndex);
		elemTr.find("button").eq(0).toggleClass("SelectedRow");
	}
	function employeeMoveTo(){
		var ElemSels = $("#EmployeeTable tr").filter(function(index){
			return $("#EmployeeTable tr").eq(index).find("button").eq(0).hasClass("SelectedRow");
		});
		if( ElemSels.length == 0){
			alert("You didn't Select any Employees.");
			return;
		}
		isMultiSelected = true;
		$("#employeeMoveToModal").modal("toggle");		
	}
	// function ImportFromExcel(){

	// }
	document.getElementById("file-upload").onchange = function() {
		if( $("#file-upload").val() == ""){
			return;
		}
		if( nIdTreeInfo == 0) {
			alert("Please select the node.");
			$("#file-upload").val("");
			return;
		}
		console.log("file-upload");
		document.getElementById("uploadExcelForm").submit();
		setTimeout(function(){
			drawTreeItemInfos(nIdTreeInfo);
			$("#file-upload").val("");
		}, 1000);
	};
	function makeCSVFileContents(){
		var strRetVal = "";
		var Trs = $("#EmployeeTable tr");
		for( var i = 1; i < $("#EmployeeTable th").length - 1; i++){
			strRetVal += $("#EmployeeTable th").eq(i).html() + ",";
		}
		strRetVal += "\n";
		for( var i = 1; i < Trs.length; i++){
			for( var j = 2; j < Trs.eq(i).find("td").length - 2; j++){
				strRetVal += Trs.eq(i).find("td").eq(j).html() + ",";
			}
			strRetVal += "\n";
		}
		return strRetVal;
	}
	function getCurrentItemName(){
		if( nIdTreeInfo == 0) return '';
		var strName = $(".treeView li").filter(function(index){
			return $(".treeView li").eq(index).find(".treeViewId").eq(0).html() == nIdTreeInfo;
		}).eq(0).find(".treeViewName").eq(0).html();
		return strName;
	}
	function exportToFile(){
		if(nIdTreeInfo == 0)
		{
			alert("Please select the Node.")
			return;
		}
		var nHeaderNameIdxBuf = nHeaderNameIdx;
		var nHeaderSurNameIdxBuf = nHeaderSurNameIdx;
		nHeaderNameIdx = 0;
		nHeaderSurNameIdx = 0;
		setHeaderIcons();
		var strContents = makeCSVFileContents();
		strContents = strContents.split("<span></span>").join("");
		var blob = new Blob([strContents], {type: "text/plain;charset=utf-8"});
		saveAs(blob, getCurrentItemName() + ".csv");
		nHeaderNameIdx = nHeaderNameIdxBuf;
		nHeaderSurNameIdx = nHeaderSurNameIdxBuf;
		setHeaderIcons();
		// window.saveAs("a.txt", "a.csv");
	}
	function getHeaderSpanIcon(nIdSpan){
		if( nIdSpan == 0)
			return '';
		if( nIdSpan == 1)
			return '<i class="fa fa-angle-up" aria-hidden="true"></i>';
		return '<i class="fa fa-angle-down" aria-hidden="true"></i>';
	}
	var nHeaderNameIdx = 0;
	var nHeaderSurNameIdx = 0;
	function setHeaderIcons(){
		$("#EmployeeTable th").eq(1).find("span").html(getHeaderSpanIcon(nHeaderNameIdx));
		$("#EmployeeTable th").eq(2).find("span").html(getHeaderSpanIcon(nHeaderSurNameIdx));
	}
	function headerNameClicked(){
		nHeaderSurNameIdx = 0;
		switch( nHeaderNameIdx){
			case 0: case 1: nHeaderNameIdx ++;break;
			case 2: nHeaderNameIdx = 1;break;
		}
		setHeaderIcons();
		if( nHeaderNameIdx == 1)
			drawEmployeeView(nIdTreeInfo, "strName", "ASC");
		else
			drawEmployeeView(nIdTreeInfo, "strName", "DESC");
	}
	function headerSurNameClicked(){
		nHeaderNameIdx = 0;
		switch( nHeaderSurNameIdx){
			case 0: case 1: nHeaderSurNameIdx ++;break;
			case 2: nHeaderSurNameIdx = 1;break;
		}
		setHeaderIcons();
		if( nHeaderSurNameIdx == 1)
			drawEmployeeView(nIdTreeInfo, "SurName", "ASC");
		else
			drawEmployeeView(nIdTreeInfo, "SurName", "DESC");
	}
	function inviteBtnClicked(_this){
		if( userRole == "Manager"){
			alert("You can't invite Users.");
			return;
		}
		var elemTr = $("#EmployeeTable tr").eq(_this.parentElement.parentElement.rowIndex);
		var strId = elemTr.find("td").eq(0).html();
		if( strId == "0"){
			alert("You didn't save Employee data. \n Please save before invite.");
			return;
		}
		var strUserName = elemTr.find("td").eq(2).html();
		var strUserSurName = elemTr.find("td").eq(3).html();
		var strUserCode = elemTr.find("td").eq(4).html()
		var strUserMail = elemTr.find("td").eq(7).html()
		if( strUserName == "" || strUserSurName == "" || strUserCode == "" || strUserMail == ""){
			alert("Please type the information about employee.");
			return;
		}
		$("#inviteUserId").val(strId);
		$("#inviteUserName").val(strUserName);
		$("#inviteUserSurName").val(strUserSurName);
		$("#inviteUserMail").val(strUserMail);
		$("#inviteUserRole").prop("selectedIndex", 2);
		//   inviteUserPass
		$("#inviteModal").modal('toggle');
	}
	function InviteSet(){
		var strId = $("#inviteUserId").val();
		var strUserName = $("#inviteUserName").val();
		var strUserSurName = $("#inviteUserSurName").val();
		var strUserMail = $("#inviteUserMail").val();
		var strUserRole = $("#inviteUserRole").val();
		var strUserPass = $("#inviteUserPass").val();
		$.ajax({
			type: 'POST',
			url: 'utils/mail.php',
			data: { inviteMail: strId, eMail: strUserMail, userPass: strUserPass, userRole: strUserRole, userName: strUserName + " " + strUserSurName},
			success: function(obj, textstatus){
				console.log(obj);
				// alert("Verify Email sent.");
				$("#inviteModal").modal("toggle");
			}
		});
	}
	function getScheduleTemplates(idInfo, isEmployee){
		g_arrScheduleTemplates = [];
		$.ajax({
			type: 'POST',
			url: 'utils/dbAjax.php',
			data: { getScheduleTemplateInfo: idInfo, isEmployee: isEmployee},
			success: function(obj, textstatus){
				g_arrScheduleTemplates = JSON.parse(obj);
				var strHtml = "";
				$("#ScheduleTemplateOption").html(strHtml);
				$("#emScheduleTemplateOption").html(strHtml);
				strHtml += "<option></option>";
				for(var i = 0; i < g_arrScheduleTemplates.length; i++){
					strHtml += "<option>"+g_arrScheduleTemplates[i]['strName']+"</option>";
				}
				$("#ScheduleTemplateOption").html(strHtml);
				$("#emScheduleTemplateOption").html(strHtml);
			}
		});
	}
	function ScheduleTempChanged(){
		var nSelectedIdx = $("#ScheduleTemplateOption").prop("selectedIndex");
		if( nSelectedIdx == 0) {
			$("#chkScheduleType").val("");
			$("#ScheTimeFirst").val("");
			$("#ScheTimeLast").val("");
			return;
		}
		var selSchedule = g_arrScheduleTemplates[nSelectedIdx - 1];
		$("#chkScheduleType").val(selSchedule['strType']);
		var arrTime = selSchedule['strTime'].split("-");
		$("#ScheTimeFirst").val(arrTime[0]);
		$("#ScheTimeLast").val(arrTime[1]);
		var strPeriod = selSchedule['strPeriod'];
		var arrPeriod = strPeriod.split(",");
		var strHtml = "";
		for( var i = 0; i < arrPeriod.length; i++){
			strHtml += "<li>" + arrPeriod[i] + "</li>";
		}
		$("#ScheduleList").html(strHtml);
	}
	function onScheduleEdit(){
		$("#scheduleModalType").html("TreeInfo");
		$("#scheduleModalId").html( nIdTreeInfo);
		var nSelectedIdx = $("#chkScheduleType").prop("selectedIndex");
		$("#ScheModalType").prop("selectedIndex", nSelectedIdx);
		$("#NewScheModalTable .TimePicker").addClass("HideItem");
		if( $("#chkScheduleType").val() == "From/Till"){
			$("#NewScheModalTable .TimePicker").eq(0).removeClass("HideItem");
			$("#ScheModalFromTime").val($("#ScheTimeFirst").val());
			$("#ScheModalTillTime").val($("#ScheTimeLast").val());				
		} else {
			$("#NewScheModalTable .TimePicker").eq(1).removeClass("HideItem");
			$("#ScheModalStartTime").val($("#ScheTimeFirst").val());
			$("#ScheModalWorkTime").val($("#ScheTimeLast").val());				
		}
		var elemLis = $("#ScheduleList li");
		var arrDates = [];
		for( var i = 0; i < elemLis.length; i++){
			arrDates.push(new Date(elemLis.eq(i).html()));
		}
		calendarModal.selectDates(arrDates);
		$("#scheduleModal").modal("toggle");
	}
	function onEmScheTempChanged(){
		var nSelectedIdx = $("#emScheduleTemplateOption").prop("selectedIndex");
		if( nSelectedIdx == 0) {
			$("#chkEmScheduleType").val("");
			$("#emFirstTime").val("");
			$("#emLastTime").val("");
			return;
		}
		var selSchedule = g_arrScheduleTemplates[nSelectedIdx - 1];
		$("#chkEmScheduleType").val(selSchedule['strType']);
		var arrTime = selSchedule['strTime'].split("-");
		$("#emFirstTime").val(arrTime[0]);
		$("#emLastTime").val(arrTime[1]);
		var strPeriod = selSchedule['strPeriod'];
		var arrPeriod = strPeriod.split(",");
		var dateVal = [];
		for(var i = 0; i < arrPeriod.length; i++){
			dateVal.push(new Date(arrPeriod[i]));
		}
		emCalendar.selectDates(dateVal);
	}

	$(document).ready(function() {
		$("#emDetailsCalendar").kendoCalendar({
			selectable: "multiple",
			weekNumber: true,
		});
		emCalendar = $("#emDetailsCalendar").data("kendoCalendar");
		$("#emDetailsCalendar .k-footer").remove();
	});
	$(document).ready( function(){
		$('input[name="startDate"]').daterangepicker({
			singleDatePicker: true,
			drops: 'up',
			showDropdowns: true
		});
		$('input[name="endDate"]').daterangepicker({
			singleDatePicker: true,
			drops: 'up',
			showDropdowns: true
		});
	});
	function downloadTemplate(){
		document.getElementById('my_iframe').src = './utils/template.xls';
		// $('a#someID').attr({target: '_blank', href  : 'template.xls'});
	}
</script>