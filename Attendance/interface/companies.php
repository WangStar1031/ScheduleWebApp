<style type="text/css">
	.modal table, .modal table input{ width: 100%; }
	.modal table td { padding: 5px; }
	#companyTable{background-color: white; border: 2px solid #aaa;}
	#companyTable td:last-child { text-align: center; }
	#companyTable td{cursor: pointer; padding: 5 0 5 5;}
	.trSelected{ background-color: #0f8; }
</style>
<h1>Companies</h1>
<table class="col-lg-12 col-md-12 col-xs-12" id="companyTable">
	<thead>
		<tr>
			<th>Name</th>
			<th>Reg Address</th>
			<th>Off Address</th>
			<th>Reg Number</th>
			<th>VAT Number</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$arrCompanies = getCompany(1);
		for( $i = 0; $i < count($arrCompanies); $i++){
			$company = $arrCompanies[$i];
		?>
		<tr onclick="CompanyTrClicked(<?= $i ?>)">
			<td><?= $company['strName'] ?></td>
			<td><?= $company['regAddress'] ?></td>
			<td><?= $company['offAddress'] ?></td>
			<td><?= $company['regNumber'] ?></td>
			<td><?= $company['VATNumber'] ?></td>
			<td>
				<div class="btn-group">
					<button onclick="onEdit(<?= $i ?>)">Edit</button>
					<button onclick="onDel(<?= $i ?>)">Del</button>
				</div>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<div class="col-lg-12 col-md-12 col-xs-12" style="height: 30px;"></div>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#companyModal" onclick="companyModal()">Add</button>

  <!-- Modal -->
  <div class="modal fade" id="companyModal" role="dialog">
    <div class="modal-dialog">    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="companyModalTitle"></h4>
        </div>
        <div class="modal-body">
        	<table>
        		<tr>
        			<td>Company Name</td>
        			<td><input type="text" name="Name"></td>
        		</tr>
        		<tr>
        			<td>Registration Address</td>
        			<td><input type="text" name="regAddress"></td>
        		</tr>
        		<tr>
        			<td>Official Address</td>
        			<td><input type="text" name="offAddress"></td>
        		</tr>
        		<tr>
        			<td>Registration Number</td>
        			<td><input type="text" name="regNumber"></td>
        		</tr>
        		<tr>
        			<td>VAT Number</td>
        			<td><input type="text" name="VATNumber"></td>
        		</tr>
        	</table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" onclick="EditedCompanyInfo()">OK</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>      
    </div>
  </div>


<script type="text/javascript">
	var isEdit = false;
	function companyModal(){
		isEdit = false;
		$("#companyModalTitle").html("Add New Company");
		for( i = 0; i < 5; i++)
			$("#companyModal input").eq(i).val("");
	}
	function EditedCompanyInfo(){
		var strName = $("#companyModal input").eq(0).val();
		var strRegAddr = $("#companyModal input").eq(1).val();
		var strOffAddr = $("#companyModal input").eq(2).val();
		var strRegNumber = $("#companyModal input").eq(3).val();
		var strVATNumber = $("#companyModal input").eq(4).val();
		if( strName=="" || strRegAddr=="" || strOffAddr=="" || strRegNumber=="" || strVATNumber==""){
			alert("Please insert the values.");
			return;
		}
		if( isEdit == false){
			$.ajax({
				type: 'POST',
				url: './utils/dbAjax.php',
				data: {AddNewCompany: strName, regAddress:strRegAddr, offAddress:strOffAddr, regNumber:strRegNumber, VATNumber:strVATNumber}
			}).done(function (d) {
				if( d == "YES"){
					var nCount = $("#companyTable tr").length;
					var strHtml = '<tr onclick="CompanyTrClicked('+(nCount-1)+')">';
					strHtml += '<td>'+strName+'</td>';
					strHtml += '<td>'+strRegAddr+'</td>';
					strHtml += '<td>'+strOffAddr+'</td>';
					strHtml += '<td>'+strName+'</td>';
					strHtml += '<td>'+strRegNumber+'</td>';
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
	function CompanyTrClicked( nNumber){
		$("#companyTable tr").removeClass("trSelected");
		$("#companyTable tr").eq(nNumber + 1).addClass("trSelected");
		companyChanged( $("#companyTable .trSelected td").eq(3).html() );
	}
	function onEdit( nNumber){
		isEdit = true;
		$("#companyModalTitle").html("Edit Company Infos.");
		for( i = 0; i < 5; i++)
			$("#companyModal input").eq(i).val($("#companyTable tr").eq(nNumber+1).find("td").eq(i).html());
		$("#companyModal").modal('toggle');
	}
	function onDel( nNumber){
		var r = confirm("Are you sure delete selected Company?");
		if( r == true){
			var strRegNumber = $("#companyTable tr").eq(nNumber+1).find("td").eq(3).html();	
			$.ajax({
				type: 'POST',
				url: './utils/dbAjax.php',
				data: {DeleteCompany:strRegNumber}
			}).done(function (d) {
				$("#companyTable tr").eq(nNumber+1).remove();
				setTimeout( function(){
					var elemTrs = $("#companyTable tr");
					for( var i = 1; i < elemTrs.length; i++){
						console.log(i);
						$("#companyTable tr").eq(i).attr("onclick", "CompanyTrClicked("+(i-1)+")");
						$("#companyTable tr").eq(i).find("button").eq(0).attr("onclick", "onEdit("+(i-1)+")");
						$("#companyTable tr").eq(i).find("button").eq(1).attr("onclick", "onDel("+(i-1)+")");
					}
				}, 500);	
			});
		}
	}
</script>
