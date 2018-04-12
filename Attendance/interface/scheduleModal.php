
<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.117/styles/kendo.common-material.min.css" />
<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.117/styles/kendo.material.min.css" />

<!-- <script src="https://kendo.cdn.telerik.com/2018.1.117/js/jquery.min.js"></script> -->
<script src="https://kendo.cdn.telerik.com/2018.1.117/js/kendo.all.min.js"></script>

<style type="text/css">
	#ScheModalTable, #ScheModalNew{
		width: 100%;
	}
	#ScheModalNew td{
		padding:5px;
	}
	#ScheModalTable td{
		border: 1px solid #333;
	}
	#ScheModalNew{
		 margin-bottom: 20px;
	}
	#multiModalcalendarModal th{ color: black!important; }
	#multiModalcalendarModal .k-footer{ height: 3em; display: none!important;}
	#multiModalcalendarModal table{ font-size: 1em; }
	#multiModalcalendarModal .k-header .k-icon{ margin-top:0.5em;}
	#NewScheModalTable td{ padding: 3px; }
</style>
<!-- Schedule Template Modal -->
<div class="modal fade" id="scheduleModal" role="dialog">
	<div class="HideItem" id="scheduleModalType"></div>
	<div class="HideItem" id="scheduleModalId"></div>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3>Schedule Editing</h3>
			</div>
			<div class="modal-body">
				<div style="width: 100%; border: 1px solid #333; padding: 10px;margin-bottom: 15px;">
					<div class="row">
						<div class="col-lg-5 col-md-5 col-xs-5">
							<table id="NewScheModalTable">
								<tr>
									<td><label>Schedule Type</label></td>
								</tr>
								<tr>
									<td>
										<select id="ScheModalType" onchange="ScheModalTypeChanged()">
											<option>From/Till</option>
											<option>Start/Working</option>
										</select>
									</td>
								</tr>
								<tr class="TimePicker forWeekDay HideItem">
									<td>
										<input type="time" id="ScheModalFromTime" class="FirstTime">
										<input type="time" id="ScheModalTillTime" class="LastTime">
									</td>
								</tr>
								<tr class="TimePicker forOthers">
									<td>
										<input type="time" id="ScheModalStartTime" class="FirstTime"> hrs 
										<input type="time" id="ScheModalWorkTime" class="LastTime">
									</td>
								</tr>
							</table>
						</div>
						<div class="col-lg-7 col-md-7 col-xs-7">
							<div class="k-content" style="text-align: center;">
								<div id="multiModalcalendarModal"></div>
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" onclick="ScheModalSet()">OK</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>			
	</div>
</div>
<script type="text/javascript">
	var calendarModal;
	$(document).ready(function() {
		$("#multiModalcalendarModal").kendoCalendar({
			selectable: "multiple",
			weekNumber: true,
		});
		calendarModal = $("#multiModalcalendarModal").data("kendoCalendar");
	});
	function ScheModalTypeChanged(){
		var strScheType = $("#ScheModalType").val();
		var nScheTypeIdx = $("#ScheModalType").prop("selectedIndex");
		$("#NewScheModalTable .TimePicker").addClass("HideItem");
		if( nScheTypeIdx == 1){
			$("#NewScheModalTable .TimePicker").eq(1).removeClass("HideItem");
		} else{
			$("#NewScheModalTable .TimePicker").eq(0).removeClass("HideItem");
		}
	}
	function ScheModalSet(){
		$("#chkScheduleType").prop("selectedIndex", $("#ScheModalType").prop("selectedIndex"));
		if( $("#ScheModalType").val() == "From/Till"){
			$("#ScheTimeFirst").val($("#ScheModalFromTime").val());
			$("#ScheTimeLast").val($("#ScheModalTillTime").val());				
		} else {
			$("#ScheTimeFirst").val($("#ScheModalStartTime").val());
			$("#ScheTimeLast").val($("#ScheModalWorkTime").val());
		}
		var strHtml = "";
		var arrSelDates = calendarModal.selectDates();
		for( var i = 0; i < arrSelDates.length; i++){
			var year = arrSelDates[i].getYear();
			var month = arrSelDates[i].getMonth();
			var date = arrSelDates[i].getDate();
			var strDate = (1900 + year) + "-" + (month >= 9 ? 1 + month : "0" + (1 + month)) + "-" + (date > 9 ? date : "0" + (1 + date));
			strHtml += "<li>" + strDate + "</li>";
		}
		$("#ScheduleList").html(strHtml);
		$("#scheduleModal").modal("toggle");
	}
</script>