<h3>Ship-To Address Report</h3>
<hr>
<?php
echo $this->Form->create(null,['url'=>['action'=>'shipaddressreport']]);

echo $this->Form->input('report_date_start',['required'=>true,'autocomplete'=>'off']);

echo $this->Form->input('report_date_end',['required'=>true,'autocomplete'=>'off']);

echo "<div class=\"input selectbox\"><label>Manager</label>";
echo $this->Form->select('manager_id',$allManagers,['empty'=>'--Select Manager--']);
echo "</div><br>";


echo $this->Form->submit('Generate Report');

echo $this->Form->end();
?>
<script>
$(function(){
	$('#report-date-start,#report-date-end').datepicker();
});
</script>
<style>
form div.input select{ min-width:135px; max-width:300px; }
#content{ width:500px; margin:0 auto; }
</style>