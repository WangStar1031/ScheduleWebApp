<style type="text/css">
	#DepartmentTable input{ width: 100%; border: none;}
	#DepartmentTable{background-color: white; border: 2px solid #aaa;}
	#DepartmentTable td{ text-align: center; border: 1px solid #aaa;}
</style>
<script type="text/javascript">
	var g_arrDepartment = [];
</script>
<table style="width: 100%;" id="DepartmentTable">
	<tr>
		<th style="min-width: 15px;"></th>
		<th>Department Name</th>
		<th>Action</th>
	</tr>
	<?php
		$arrDepartment = getDepartment(1);
		for( $i = 0; $i < count($arrDepartment); $i++){
			$_department = $arrDepartment[$i];
	?>
	<script type="text/javascript">
		g_arrDepartment.push("<?= $_department['Id'] ?>");
	</script>
	<tr>
		<td><input type="checkbox" class="chkDepartment"></td>
		<td><input type="text" class="txtDepartmentName" value="<?= $_department['strName'] ?>"></td>
		<td>
			<div class="btn-group">
				<button onclick="confirmDepartment(<?= $i ?>)">Confirm</button>
				<button onclick="delDepartment(<?= $i ?>)">Del</button>
			</div>			
		</td>
	</tr>
	<?php
		}
	?>
</table>
<div class="col-lg-12 col-md-12 col-xs-12" style="height: 20px;"></div>
<button type="button" class="btn btn-info" onclick="AddDepartment()">Add</button>

<script type="text/javascript">
	function loadDepartment( strCompanyRegNumber){
		g_strCompanyRegNumber = strCompanyRegNumber;
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			datatype: 'json',
			data: {getDepartment: strCompanyRegNumber}
		}).done(function (d) {
			var MatchingDepartment = JSON.parse(d);
			$(".chkDepartment").prop("checked","");
			for( var i = 0; i < MatchingDepartment.length; i++){
				var idBranch = MatchingDepartment[i].Id;
				var nNumber = g_arrDepartment.indexOf(idBranch);
				if( nNumber != -1){
					$("#DepartmentTable tr").eq(nNumber+1).find("input").eq(0).prop("checked", "true");
				}
			}
		});
	}
	function AddDepartment(){
		var nCount = $("#DepartmentTable tr").length - 1;
		if( nCount != 0){
			var strDeName = $("#DepartmentTable tr").eq(nCount).find("input").eq(1).val();
			if( strDeName == ""){
				alert("Please insert the vaules.");
				return;
			}
		}
		var strHtml = '<tr><td><input type="checkbox" class="chkDepartment"></td><td><input type="text" class="txtDepartmentName"></td><td><div class="btn-group"><button onclick="confirmDepartment('+nCount+')">Confirm</button><button onclick="delDepartment('+nCount+')">Del</button></div></td></tr>';
		$("#DepartmentTable tr:last").after(strHtml);
	}
	function delDepartment(nNumber){
		var strRegNumber = $("#BranchesTable tr").eq(nNumber+1).find("td").eq(3).html();
		var idDepartment = g_arrDepartment[nNumber];

		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			data: {delDepartment:idDepartment}
		}).done(function (d) {
			$("#DepartmentTable tr").eq(nNumber+1).remove();
			var elemTrs = $("#DepartmentTable tr");
			for( var i = 1; i < elemTrs.length; i++){
				$("#DepartmentTable tr").eq(i).find("button").eq(0).attr("onclick", "confirmDepartment("+(i-1)+")");
				$("#DepartmentTable tr").eq(i).find("button").eq(1).attr("onclick", "delDepartment("+(i-1)+")");
			}
			g_arrBranches.splice( nNumber, 1);		
		});			
	}
	function confirmDepartment(nNumber){
		var isChecked = $("#DepartmentTable tr").eq(nNumber+1).find("input").eq(0).prop("checked");
		if( isChecked == true){
			if( g_strCompanyRegNumber == ""){
				alert("Please Select the Company.");
				return;
			}
		}
		var strDeName = $("#DepartmentTable tr").eq(nNumber+1).find("input").eq(1).val();
		if( strDeName == ""){
			alert("Please insert the vaules.");
			return;
		}
		var isNew = !(g_arrDepartment.length > nNumber);
		var DepartmentId = -1;
		if( !isNew ){
			DepartmentId = g_arrDepartment[nNumber];
		}
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			data: {ConfirmDepartment:DepartmentId, strDeName: strDeName, isChecked:isChecked, strCompanyRegNumber:g_strCompanyRegNumber}
		}).done(function (d) {
			if( isNew) g_arrDepartment.push(d);
		});
	}
</script>