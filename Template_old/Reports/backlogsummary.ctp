<!-- src/Template/Reports/backlogsummary.ctp -->
<script src="/js/jquery.multiselect.min.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jquery.multiselect.css" />
<style>
h2{ text-align:center; font-size:24px; }
form{ max-width:500px; margin:0 auto; }
#as-daterangestart{ width:48%; display:inline-block; margin-right:1%; }
#as-daterangeend{ width:48%; display:inline-block; margin-left:1%; }
	
.ui-multiselect{ padding:8px !important; }
</style>
<?php
echo $this->Form->create(null);
if($thisType=='backlog'){
    echo "<h2>Generate Back Log Summary Report</h2><Hr>";
}else{
    echo "<h2>Generate Bookings Summary Report</h2><Hr>";
}
?>
<p><label>Date Range</label><input type="text" id="as-daterangestart" autocomplete="off" name="datestart" placeholder="START" /> <input type="text" id="as-daterangeend" placeholder="END" name="dateend" autocomplete="off" /></p>

<?php
/*
<p><label style="display:inline-block; width:32%; margin-right:0.5%;">Quote #<input type="text" id="as-quotenumber" name="quotenumber" /></label>
<label style="display:inline-block; width:32%; margin-right:0.5%;">Order #<input type="text" id="as-ordernumber" name="ordernumber" /></label>
<label style="display:inline-block; width:32%;">Client PO #<input type="text" id="as-clientpo" name="clientponumber" /></label></p>
*/
?>

<div style="display:inline-block; "><label>Customer<select id="as-customer" name="customer[]" multiple="multiple">
			<?php
				foreach($allcompanies as $company){
				    if($company['status'] == 'Active'){
					    echo "<option value=\"".$company['id']."\">".$company['company_name']."</option>";
				    }
				}
				?></select></label></div>

<?php
/*
<div style="display:inline-block; width:48%;"><label>HCI Agent<select id="as-hciagent" multiple="multiple" name="hciagent[]">
			<?php
				foreach($allusers as $user){
					echo "<option value=\"".$user['id']."\">".$user['last_name'].", ".$user['first_name']."</option>";
				}
				?></select></label></div>
*/
?>
<div style="padding:20px 0 0 0; text-align:center;"><button type="submit" id="bigsearchgo">Generate Report</button></div>

	<script>
	$(function(){
	    $('select#as-fabricsearch,select#as-lineitem,select#as-customer,select#as-hciagent,select#includestatuses').multiselect({
    		multiple:true,
    		noneSelectedText: "--All Customers--",
    		header:false
	    });
	    
	    $('#as-daterangestart,#as-daterangeend').datepicker();
	});
	</script>
<?php

echo $this->Form->end();

?>