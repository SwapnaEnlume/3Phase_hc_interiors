<h2>Please select your Date Range for this report:</h2>
<p><label>Start Date<input type="text" name="startDate" value="" /></label></p>
<p><label>End Date<input type="text" name="endDate" value="" /></label></p>
<p><button type="button" onclick="doSummaryDates()">Generate Report</button></p>
<script>
function doSummaryDates(){
	var startDateSplit=$('input[name=startDate]').val().split('/');
	var startDate=startDateSplit[2]+'-'+startDateSplit[0]+'-'+startDateSplit[1];

	var endDateSplit=$('input[name=endDate]').val().split('/');
	var endDate=endDateSplit[2]+'-'+endDateSplit[0]+'-'+endDateSplit[1];

	location.href="/orders/unscheduledreport/"+startDate+"/"+endDate+"/Unscheduled%20Report%20"+startDate+"%20through%20"+endDate+".xlsx";
}

$(function(){
	$('input[name=startDate],input[name=endDate]').datepicker();
});
</script>
