<h3>Invoiced Batch Report</h3>
<hr>
<?php
echo $this->Form->create(null,['url'=>['action'=>'invoicedorders']]);

echo $this->Form->input('report_date_start',['required'=>true,'autocomplete'=>'off']);

echo $this->Form->input('report_date_end',['required'=>true,'autocomplete'=>'off']);

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