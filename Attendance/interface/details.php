<style type="text/css">
	.Contents{ padding-top: 10px; }
	.btnSelected{background-color:#398439!important;}
</style>
<h1>Settings</h1>
<div class="btn-group">
	<button type="button" class="btn btn-success btnSelected" id="EmployeesBtn" onclick="GroupClicked(0)">Employees</button>
	<button type="button" class="btn btn-success" id="SettingsBtn" onclick="GroupClicked(1)">Other Settings</button>
</div>
<div class="Contents">
	<div id="EmployeesContents">
		<table class="col-lg-12 col-md-12 col-xs-12">
			<tr>
				<th>Name</th>
				<th>SurName</th>
				<th>Code</th>
				<th>Email</th>
				<th>PhoneNumber</th>
				<th>NFC Number</th>
<!--			<th>Branches</th>
				<th>Department</th>
				<th>Posts</th>
				<th>Schedule</th>
				<th>Vacation</th> -->
 				<th>Action</th>
			</tr>
		</table>
		<div class="col-lg-12 col-md-12 col-xs-12" style="height: 30px;"></div>
		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#employeeModal" onclick="employeeModal()">Add</button>
	</div>

	<div id="OtherSettinsContents" class="HideItem">
		<?php
		include_once("otherSettings.php")
		?>
	</div>
</div>
  <!-- Modal -->
  <div class="modal fade" id="employeeModal" role="dialog">
    <div class="modal-dialog">    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="employeeModalTitle">Add New Employee</h4>
        </div>
        <div class="modal-body">
        	<table>
        		<tr>
        			<td>Name</td>
        			<td><input type="text" name="Name"></td>
        		</tr>
        		<tr>
        			<td>SurName</td>
        			<td><input type="text" name="regAddress"></td>
        		</tr>
        		<tr>
        			<td>Code</td>
        			<td><input type="text" name="offAddress"></td>
        		</tr>
        		<tr>
        			<td>Email</td>
        			<td><input type="email" name="regNumber"></td>
        		</tr>
        		<tr>
        			<td>Phone Number</td>
        			<td><input type="text" name="PhoneNumber"></td>
        		</tr>
        		<tr>
        			<td>NFC Number</td>
        			<td><input type="text" name="NFCNumber"></td>
        		</tr>
        		<tr>
        			<td>Branches</td>
        			<td id="BranchesSelect"></td>
        		</tr>
        		<tr>
        			<td>Department</td>
        			<td id="DepartmentSelect"></td>
        		</tr>
        		<tr>
        			<td>Posts</td>
        			<td id="PostsSelect"></td>
        		</tr>
        		<tr>
        			<td>Schedule</td>
        			<td id="ScheduleSelect"></td>
        		</tr>
        	</table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" onclick="EditedEmployeeInfo()">OK</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>      
    </div>
  </div>

<script type="text/javascript">
	var strCurrentCompany = "";
	var isEmployeeEdit = false;
	function getCompanySettings(){
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			datatype:'json',
			data: {getBranches: strCurrentCompany}
		}).done(function (d) {
		});
	}
	function GroupClicked(nNumber){
		if( nNumber == 0) {
			$("#EmployeesBtn").addClass("btnSelected");
			$("#SettingsBtn").removeClass("btnSelected");
			$("#EmployeesContents").removeClass("HideItem");
			$("#OtherSettinsContents").addClass("HideItem");
		} else {
			$("#EmployeesBtn").removeClass("btnSelected");
			$("#SettingsBtn").addClass("btnSelected");
			$("#EmployeesContents").addClass("HideItem");
			$("#OtherSettinsContents").removeClass("HideItem");
		}
	}
	function companyChanged(strRegNumber){
		loadBranch(strRegNumber);
		loadDepartment(strRegNumber);
		loadPosts(strRegNumber);
		// loadSchedule(strRegNumber);
		// loadAlerts(strRegNumber);
		strCurrentCompany = strRegNumber;
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			datatype:'json',
			data: {getEmployees: strRegNumber}
		}).done(function (d) {
			// console.log(d);
		});
	}
	function employeeModal(){
		isEmployeeEdit = false;
		$("#employeeModalTitle").html("Add New Employee");
		for( i = 0; i < 7; i++)
			$("#employeeModal input").eq(i).val("");
	}
	function EditedEmployeeInfo(){
		var strName = $("#employeeModal input").eq(0).val();
		var strSurName = $("#employeeModal input").eq(1).val();
		var strCode = $("#employeeModal input").eq(2).val();
		var strEmail = $("#employeeModal input").eq(3).val();
		var strPhoneNumber = $("#employeeModal input").eq(4).val();
		var strNFCNumber = $("#employeeModal input").eq(5).val();
		// var strBranches = $("#BranchesSelect").val();
		if(strName=="" || strSurName=="" || strCode=="" || strEmail=="" || strPhoneNumber=="" || strNFCNumber=="" || strDepartment=="" ){
			alert("Please insert the values.");
			return;
		}
		if( isEmployeeEdit == false){
			$.ajax({
				type: 'POST',
				url: './utils/dbAjax.php',
				data: {AddNewEmployee: strCurrentCompany, strName:strName,strSurName:strSurName,strCode:strCode,strEmail:strEmail,strPhoneNumber:strPhoneNumber,strNFCNumber:strNFCNumber,strDepartment:strDepartment}
			}).done(function (d) {
				if( d == "YES"){
					var nCount = $("#companyTable tr").length;
					var strHtml = '<tr onclick="CompanyTrClicked('+(nCount-1)+')">';
					strHtml += '<td>'+strName+'</td>';
					strHtml += '<td>'+strSurName+'</td>';
					strHtml += '<td>'+strCode+'</td>';
					strHtml += '<td>'+strEmail+'</td>';
					strHtml += '<td>'+strPhoneNumber+'</td>';
					strHtml += '<td>'+strNFCNumber+'</td>';
					strHtml += '<td>'+strDepartment+'</td>';
					strHtml += '<td><div class="btn-group"><button onclick="onEdit('+(nCount-1)+')">Edit</button><button onclick="onDel('+(nCount-1)+')">Del</button></div></td>';
					$("#companyTable tr:last").after(strHtml);
					$("#companyModal").modal('toggle');

				} else{
					alert("Can't Add new Company.");
				}
			});
		} else{
			var prevRegNumber = $("#companyTable .trSelected td").eq(3).html();
			$.ajax({
				type: 'POST',
				url: './utils/dbAjax.php',
				data: {EditCompany:prevRegNumber, Name: strName, regAddress:strRegAddr, offAddress:strOffAddr, regNumber:strRegNumber, VATNumber:strVATNumber}
			}).done(function (d) {
				$("#companyTable .trSelected td").eq(0).html(strName);
				$("#companyTable .trSelected td").eq(1).html(strRegAddr);
				$("#companyTable .trSelected td").eq(2).html(strOffAddr);
				$("#companyTable .trSelected td").eq(3).html(strRegNumber);
				$("#companyTable .trSelected td").eq(4).html(strVATNumber);
				$("#companyModal").modal('toggle');
			});
		}
	}
</script>