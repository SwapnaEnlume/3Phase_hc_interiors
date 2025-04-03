<?php
echo $this->Form->create(null);

echo "<h2 style=\"float:left;\">Mark Batch as Invoiced</h2>
<hr style=\"clear:both;\" />";


echo $this->Form->input('invoice_number',['required'=>true]);

/**PPSASCRUM-113 start **/
echo $this->Form->input('date_invoiced',['label'=>'Invoice Date','required'=>false,'autocomplete'=>'off','value'=>date('Y-m-d')]);
/**PPSASCRUM-113 End **/
echo "<br><BR><div id=\"buttonsbottom\">";
echo $this->Form->submit('Mark This Batch as Invoiced');
echo "<button type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";

echo "</div><br><br>";

echo $this->Form->end();
?>
<script>
$(function(){
	$('#date-invoiced').datepicker();
});
</script>

<style>
body{ font-family:Arial,Helvetica,sans-serif; }
form div.input{ margin-bottom:10px; }
form div.input label{ display:block; font-weight:bold; }
form div.input input[type=text]{ width:95%; padding:6px; }
form div.input textarea{ width:95%; padding:6px; }

#buttonsbottom div.submit{ float:left; }
#buttonsbottom button[type=button]{ float:right; }

tr.Mfgnote{ color:orange; }
tr.Shipnote{ color:purple; }

table thead tr{ background:#26337A; }
table thead tr th{ color:#FFF; }	
table{ border-bottom:2px solid #26337A; }
	
form div.input.date select{ padding-right:30px !important; }
	
table tbody tr:nth-of-type(even){ background:#f8f8f8; }
table tbody tr:nth-of-type(odd){ background:#e8e8e8; }
	
form h2{ font-size:large; font-weight:bold; color:#26337A; }
form{ max-width:600px; margin:15px auto; }
form dl dd{ padding-left:20px; }
form input[type=submit]{ background:#26337A; color:#FFF; font-weight:bold; padding:15px 25px; font-size:large; border:0; }
</style>