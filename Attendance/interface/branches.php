<style type="text/css">
	#BranchesTable input{ width: 100%; border: none;}
	#BranchesTable{background-color: white; border: 2px solid #aaa;}
	#BranchesTable td{ text-align: center; border: 1px solid #aaa;}
</style>
<script type="text/javascript">
	var g_arrBranches = [];
</script>
<table style="width: 100%;" id="BranchesTable">
	<tr>
		<th style="min-width: 15px;"></th>
		<th>Branch Name</th>
		<th>Regist Number</th>
		<th>Regist Address</th>
		<th>Action</th>
	</tr>
	<?php
		$arrBranches = getBranches(1);
		for( $i = 0; $i < count($arrBranches); $i++){
			$_branch = $arrBranches[$i];
	?>
	<script type="text/javascript">
		g_arrBranches.push("<?= $_branch['Id'] ?>");
	</script>
	<tr>
		<td><input type="checkbox" class="chkBranch"></td>
		<td><input type="text" class="txtBranchName" value="<?= $_branch['strName'] ?>"></td>
		<td><input type="text" class="txtBranchNumber" value="<?= $_branch['regNumber'] ?>"></td>
		<td><input type="text" class="txtBranchAddress" value="<?= $_branch['regAddress'] ?>"></td>
		<td>
			<div class="btn-group">
				<button onclick="confirmBranch(<?= $i ?>)">Confirm</button>
				<button onclick="delBranch(<?= $i ?>)">Del</button>
			</div>			
		</td>
	</tr>
	<?php
		}
	?>
</table>
<div class="col-lg-12 col-md-12 col-xs-12" style="height: 20px;"></div>
<button type="button" class="btn btn-info" onclick="AddBranch()">Add</button>

<script type="text/javascript">
	function loadBranch( strCompanyRegNumber){
		g_strCompanyRegNumber = strCompanyRegNumber;
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			datatype: 'json',
			data: {getBranches: strCompanyRegNumber}
		}).done(function (d) {
			var MatchingBranches = JSON.parse(d);
			$(".chkBranch").prop("checked","");
			for( var i = 0; i < MatchingBranches.length; i++){
				var idBranch = MatchingBranches[i].Id;
				var nNumber = g_arrBranches.indexOf(idBranch);
				if( nNumber != -1){
					$("#BranchesTable tr").eq(nNumber+1).find("input").eq(0).prop("checked", "true");
				}
			}
		});
	}
	function AddBranch(){
		var nCount = $("#BranchesTable tr").length - 1;
		if( nCount != 0){
			var strBrName = $("#BranchesTable tr").eq(nCount).find("input").eq(1).val();
			var strBrRegNumber = $("#BranchesTable tr").eq(nCount).find("input").eq(2).val();
			var strBrRegAddr = $("#BranchesTable tr").eq(nCount).find("input").eq(3).val();
			if( strBrName == "" || strBrRegNumber == "" || strBrRegAddr == ""){
				alert("Please insert the vaules.");
				return;
			}
		}
		var strHtml = '<tr><td><input type="checkbox" class="chkBranch"></td><td><input type="text" class="txtBranchName"></td><td><input type="text" class="txtBranchNumber"></td><td><input type="text" class="txtBranchAddress"></td><td><div class="btn-group"><button onclick="confirmBranch('+nCount+')">Confirm</button><button onclick="delBranch('+nCount+')">Del</button></div></td></tr>';
		$("#BranchesTable tr:last").after(strHtml);
	}
	function delBranch(nNumber){
		var strRegNumber = $("#BranchesTable tr").eq(nNumber+1).find("td").eq(3).html();
		var idBranch = g_arrBranches[nNumber];

		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			data: {delBranch:idBranch}
		}).done(function (d) {
			$("#BranchesTable tr").eq(nNumber+1).remove();
			var elemTrs = $("#BranchesTable tr");
			for( var i = 1; i < elemTrs.length; i++){
				console.log(i);
				// $("#BranchesTable tr").eq(i).attr("onclick", "CompanyTrClicked("+(i-1)+")");
				$("#BranchesTable tr").eq(i).find("button").eq(0).attr("onclick", "confirmBranch("+(i-1)+")");
				$("#BranchesTable tr").eq(i).find("button").eq(1).attr("onclick", "delBranch("+(i-1)+")");
			}
			g_arrBranches.splice( nNumber, 1);		
		});
	}
	function confirmBranch(nNumber){
		var isChecked = $("#BranchesTable tr").eq(nNumber+1).find("input").eq(0).prop("checked");
		if( isChecked == true){
			if( g_strCompanyRegNumber == ""){
				alert("Please Select the Company.");
				return;
			}
		}
		var strBrName = $("#BranchesTable tr").eq(nNumber+1).find("input").eq(1).val();
		var strBrRegNumber = $("#BranchesTable tr").eq(nNumber+1).find("input").eq(2).val();
		var strBrRegAddr = $("#BranchesTable tr").eq(nNumber+1).find("input").eq(3).val();
		if( strBrName == "" || strBrRegNumber == "" || strBrRegAddr == ""){
			alert("Please insert the vaules.");
			return;
		}
		var isNew = !(g_arrBranches.length > nNumber);
		var BranchId = -1;
		if( !isNew ){
			BranchId = g_arrBranches[nNumber];
		}
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			data: {ConfirmBranch:BranchId, strBrName: strBrName, strBrRegNumber:strBrRegNumber, strBrRegAddr:strBrRegAddr, isChecked:isChecked, strCompanyRegNumber:g_strCompanyRegNumber}
		}).done(function (d) {
			if( isNew) g_arrBranches.push(d);
		});
	}
</script>