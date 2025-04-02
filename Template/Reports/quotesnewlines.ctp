<h3>Quotes – New Lines Created Report</h3>
<hr>
<?php
echo $this->Form->create(null,['url'=>['action'=>'quotesnewlines']]);

echo $this->Form->input('report_date_start',['label'=>'Report PO Date Start', 'required'=>true,'autocomplete'=>'off']);

echo $this->Form->input('report_date_end',['label'=>'Report PO Date End','required'=>true,'autocomplete'=>'off']);


$customersArr=array();
foreach($allCustomers as $customerRow){
    $customersArr[$customerRow['id']]=$customerRow['company_name'];
}

echo "<div class=\"input selectbox\"><label>Customer</label>";
echo $this->Form->select('customer_id',$customersArr,['empty'=>'--Select Customer--']);
echo "</div>";

$typesArr=array();
foreach($quoteTypes as $typeRow){
    $typesArr[$typeRow['id']]=$typeRow['type_label'];
}

echo "<div class=\"input selectbox\"><label>Quote Type</label>";
echo $this->Form->select('quote_type',$typesArr,['empty'=>'--Select Type--']);
echo "</div>";

echo "<div id=\"revision\"><input type=\"checkbox\" name=\"revision\" value=\"yes\" /><span>Include Revisions </span> </div>";

echo "<div class=\"input selectbox\"><label>Created By:</label>";
echo "<select name=\"filterbyuser\"><option value=\"allusers\" selected>--All Users--</option>";
foreach($allUsers as $user){
    echo "<option value=\"".$user['id']."\">".$user['first_name']." ".$user['last_name']."</option>";
}
echo "</select></div>";


echo $this->Form->submit('Generate Report');

echo $this->Form->end();
?>

<style>
#revision span{ opacity:0.4; }
#revision span.show{ opacity:1 !important; }
#revision span input[type=number]{ display:inline-block; width:120px; }

form div.input select{ min-width:135px; max-width:300px; }
#content{ width:500px; margin:0 auto; }
</style>
<script>
	$(function(){
	    $('#report-date-start,#report-date-end').datepicker();
	});
	</script>