<h3>Big Quotes Report</h3>
<hr>
<?php
echo $this->Form->create(null,['url'=>['action'=>'bigquotes']]);

echo $this->Form->input('report_date_start',['required'=>true,'autocomplete'=>'off']);

echo $this->Form->input('report_date_end',['required'=>true,'autocomplete'=>'off']);


echo "<div class=\"input selectbox\"><label>Created By:</label>";
echo "<select name=\"filterbyuser\"><option value=\"allusers\" selected>--All Users--</option>";
foreach($allUsers as $user){
    echo "<option value=\"".$user['id']."\">".$user['first_name']." ".$user['last_name']."</option>";
}
echo "</select></div>";


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