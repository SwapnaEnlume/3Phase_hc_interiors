<!-- src/Template/Quotes/cloneLineItem.ctp -->

<?php
echo $this->Form->create(false);
//echo $this->Form->input('process',['type'=>'hidden','value'=>'yes']);
echo $this->Form->input('ordermode',['type'=>'hidden','value'=>$ordermode]);
echo $this->Form->input('quoteLineItemID',['type'=>'hidden','value'=>$quoteLineItemID]);
echo $this->Form->input('orderID',['type'=>'hidden','value'=>$orderId]);

echo "<p><label>Create Clone Under Line:</label>
	    <select name=\"clonelineNumber\" id=\"clonelineNumber\"><option value=\"\" >Last</option>";
		foreach ($clonelineNumber as $fabric) {
			echo "<option value=\"" . $fabric['line_number'] . "\"";
			echo ">" . $fabric['wo_line_number'] . "</option>";

		}

echo "</select></p>";//".$quoteLineItemID.",".$ordermode.",".$('#clonelineNumber').val()."
echo "<p><button style=\"float:left;\" type=\"button\" onclick=\"cloneLineItem(".$quoteLineItemID.",'".$ordermode."',"."$('#clonelineNumber :selected').text())\">Apply Clone Line Number </button><p style=\"float:right;border: 1px solid #660000;background-color: #9CE447 !important;color: #FFF;padding: 5px;font-size: 13px;font-weight: bold;display: inline-block;border: 1px solid #660000;\" onclick=\"closeLineItemPopUP()\">Cancel</p><div style=\"clear:both;\"></div></p>";
echo $this->Form->end();
?>
<style>
form{ width:700px; margin:20px auto; }
h1,h4{ text-align: center; }
div.submit{ display:inline-block; }
div.submit input[type=submit]{ background:green; padding:10px; font-size:16px; color:#FFF; font-weight:bold; border:1px solid #000; }
button[type=button]{ background:red; color:#FFF; padding:5px; font-size:13px; font-weight:bold; display:inline-block; border:1px solid #660000; }
</style>
<script>
    function cloneLineItem(lineitemid,ordermode,assignedLineNumber){
     
		location.href='/quotes/clonelineItem/'+lineitemid+'/'+ordermode+'/'+$('#clonelineNumber :selected').text();
	
}
function closeLineItemPopUP(){
   // alert('close');
    location.href='/orders/woview/<?php echo $orderId;?>';
   
}
</script>