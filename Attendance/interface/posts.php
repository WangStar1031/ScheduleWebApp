<style type="text/css">
	#PostsTable input{ width: 100%; border: none;}
	#PostsTable{background-color: white; border: 2px solid #aaa;}
	#PostsTable td{ text-align: center; border: 1px solid #aaa;}
</style>
<script type="text/javascript">
	var g_arrPosts = [];
</script>
<table style="width: 100%;" id="PostsTable">
	<tr>
		<th style="min-width: 15px;"></th>
		<th>Posts Name</th>
		<th>Action</th>
	</tr>
	<?php
		$arrPosts = getPosts(1);
		for( $i = 0; $i < count($arrPosts); $i++){
			$_Posts = $arrPosts[$i];
	?>
	<script type="text/javascript">
		g_arrPosts.push("<?= $_Posts['Id'] ?>");
	</script>
	<tr>
		<td><input type="checkbox" class="chkPosts"></td>
		<td><input type="text" class="txtPostsName" value="<?= $_Posts['strName'] ?>"></td>
		<td>
			<div class="btn-group">
				<button onclick="confirmPosts(<?= $i ?>)">Confirm</button>
				<button onclick="delPosts(<?= $i ?>)">Del</button>
			</div>			
		</td>
	</tr>
	<?php
		}
	?>
</table>
<div class="col-lg-12 col-md-12 col-xs-12" style="height: 20px;"></div>
<button type="button" class="btn btn-info" onclick="AddPosts()">Add</button>

<script type="text/javascript">
	function loadPosts( strCompanyRegNumber){
		g_strCompanyRegNumber = strCompanyRegNumber;
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			datatype: 'json',
			data: {getPosts: strCompanyRegNumber}
		}).done(function (d) {
			var MatchingPosts = JSON.parse(d);
			$(".chkPosts").prop("checked","");
			for( var i = 0; i < MatchingPosts.length; i++){
				var idBranch = MatchingPosts[i].Id;
				var nNumber = g_arrPosts.indexOf(idBranch);
				if( nNumber != -1){
					$("#PostsTable tr").eq(nNumber+1).find("input").eq(0).prop("checked", "true");
				}
			}
		});
	}
	function AddPosts(){
		var nCount = $("#PostsTable tr").length - 1;
		if( nCount != 0){
			var strDeName = $("#PostsTable tr").eq(nCount).find("input").eq(1).val();
			if( strDeName == ""){
				alert("Please insert the vaules.");
				return;
			}
		}
		var strHtml = '<tr><td><input type="checkbox" class="chkPosts"></td><td><input type="text" class="txtPostsName"></td><td><div class="btn-group"><button onclick="confirmPosts('+nCount+')">Confirm</button><button onclick="delPosts('+nCount+')">Del</button></div></td></tr>';
		$("#PostsTable tr:last").after(strHtml);
	}
	function delPosts(nNumber){
		var strRegNumber = $("#BranchesTable tr").eq(nNumber+1).find("td").eq(3).html();
		var idPosts = g_arrPosts[nNumber];

		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			data: {delPosts:idPosts}
		}).done(function (d) {
			$("#PostsTable tr").eq(nNumber+1).remove();
			var elemTrs = $("#PostsTable tr");
			for( var i = 1; i < elemTrs.length; i++){
				$("#PostsTable tr").eq(i).find("button").eq(0).attr("onclick", "confirmPosts("+(i-1)+")");
				$("#PostsTable tr").eq(i).find("button").eq(1).attr("onclick", "delPosts("+(i-1)+")");
			}
			g_arrBranches.splice( nNumber, 1);		
		});			
	}
	function confirmPosts(nNumber){
		var isChecked = $("#PostsTable tr").eq(nNumber+1).find("input").eq(0).prop("checked");
		if( isChecked == true){
			if( g_strCompanyRegNumber == ""){
				alert("Please Select the Company.");
				return;
			}
		}
		var strDeName = $("#PostsTable tr").eq(nNumber+1).find("input").eq(1).val();
		if( strDeName == ""){
			alert("Please insert the vaules.");
			return;
		}
		var isNew = !(g_arrPosts.length > nNumber);
		var PostsId = -1;
		if( !isNew ){
			PostsId = g_arrPosts[nNumber];
		}
		$.ajax({
			type: 'POST',
			url: './utils/dbAjax.php',
			data: {ConfirmPosts:PostsId, strDeName: strDeName, isChecked:isChecked, strCompanyRegNumber:g_strCompanyRegNumber}
		}).done(function (d) {
			if( isNew) g_arrPosts.push(d);
		});
	}
</script>