<style type="text/css">
	.OtherSettingsPan{
		background-color: white; padding-top: 15px; padding-bottom: 15px;
	}
	.OtherSettingsPage{ width: 100%; border: 1px solid #333; padding-top: 15px; padding-bottom: 15px;}
</style>
<div class="OtherSettingsPan col-lg-12 col-md-12 col-xs-12">
	<div class="btn-group">
		<button type="button" class="btn btn-success OtherSettingsBtn btnSelected" id="BranchesBtn" onclick="SettingGroupClicked(0)">Branches</button>
		<button type="button" class="btn btn-success OtherSettingsBtn" id="DepartmentBtn" onclick="SettingGroupClicked(1)">Department</button>
		<button type="button" class="btn btn-success OtherSettingsBtn" id="PostsBtn" onclick="SettingGroupClicked(2)">Posts</button>
		<button type="button" class="btn btn-success OtherSettingsBtn" id="ScheduleBtn" onclick="SettingGroupClicked(3)">Schedule</button>
		<button type="button" class="btn btn-success OtherSettingsBtn" id="VacationBtn" onclick="SettingGroupClicked(4)">Vacation</button>
		<button type="button" class="btn btn-success OtherSettingsBtn" id="VacationBtn" onclick="SettingGroupClicked(5)">Alerts</button>
	</div>
	<div class="OtherSettingsPage col-lg-12 col-md-12 col-xs-12">
		<?php
		// echo $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."interface/branches.php";
		include("branches.php");
		?>
	</div>
	<div class="OtherSettingsPage col-lg-12 col-md-12 col-xs-12 HideItem">
		<?php
		include("department.php");
		?>
	</div>
	<div class="OtherSettingsPage col-lg-12 col-md-12 col-xs-12 HideItem">
		<?php
		include("posts.php");
		?>
	</div>
	<div class="OtherSettingsPage col-lg-12 col-md-12 col-xs-12 HideItem">
		<?php
		// include("schedule.php");
		?>
	</div>
	<div class="OtherSettingsPage col-lg-12 col-md-12 col-xs-12 HideItem">
		<?php
		include("vacation.php");
		?>
	</div>
	<div class="OtherSettingsPage col-lg-12 col-md-12 col-xs-12 HideItem">
		<?php
		// include("alerts.php");
		?>
	</div>
</div>
<script type="text/javascript">
	
	function SettingGroupClicked(nNumber){
		$(".OtherSettingsBtn").removeClass("btnSelected");
		$(".OtherSettingsBtn").eq(nNumber).addClass("btnSelected");
		$(".OtherSettingsPage").addClass("HideItem");
		$(".OtherSettingsPage").eq(nNumber).removeClass("HideItem");
	}
</script>