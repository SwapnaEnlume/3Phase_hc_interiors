<?php
if($step=="step1"){
echo $this->Form->create(false);
echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);

echo "<h3>Bulk Edit MSR Entries</h3>";
echo "<div style=\"margin:10px auto;background:#FDF8D9;padding:5px 10px;border:1px solid #990000;text-align:center;\"><b style=\"color:#990000;\">IMPORTANT:</b> <em>Do not change fields you do not wish to overwrite.</em></div>";

echo "<fieldset id=\"selectedentries\"><legend>Apply Changes To <small><em>(".count($entryids)." entries selected)</em></small></legend><ul>";
foreach($selectedentries as $msrentry){
	echo "<li>".$msrentry['part_or_fabric']." ".$msrentry['revision_or_color']."</li>";
}
echo "</ul><div style=\"clear:both;\"></div></fieldset>";
	
echo "<fieldset><legend>Other Details</legend>";

echo "<div><label>Order Status:</label>";
echo $this->Form->select('order_status',['Not Acknowledged'=>'Not Acknowledged','Will Advise'=>'Will Advise','Backorder'=>'Backorder','In Stock' => 'In Stock','Blanket'=>'Blanket'],['empty'=>'--Select Order Status--']);
echo "</div>";


echo "<div class=\"input\"><label>Estimated Ship Date:</label>
<input type=\"date\" name=\"estimated_ship_date\" />

<label><input type=\"checkbox\" name=\"blank_esd\" value=\"1\" /> Delete existing Est Ship Dates</label></div>";

echo "<div class=\"input\"><label>ETA:</label>
<input type=\"date\" name=\"eta\" />

<label><input type=\"checkbox\" name=\"blank_eta\" value=\"1\" /> Delete existing ETAs</label></div>";

echo "<div><label>Shipment Status:</label>";
echo $this->Form->select('shipment_status',['TBD'=>'TBD','Hold'=>'Hold','In Transit'=>'In Transit','Delivered'=>'Delivered','Cancelled'=>'Cancelled'],['empty'=>'--Select Shipment Status--']);
echo "</div>";

echo $this->Form->input('qb_po_number',['label'=>'QB PO#']);

echo $this->Form->input('notes',['type'=>'textarea']);

echo "</fieldset>";



echo "<div id=\"buttonrow\">";
echo $this->Form->button('Cancel',['type'=>'button',"onclick"=>'location.replace(\'/reports/msr/\')']);
echo $this->Form->submit('Review + Apply Changes');
echo "<div style=\"clear:both;\"></div></div>";

echo $this->Form->end();
?>
<style>
h3{ text-align: center; margin:0; }
#buttonrow button{ float:left; background:#CCC; border:1px solid #444; padding:5px !important; font-size:14px !important; color:#000 !important; }
#buttonrow .submit{ float:right; }
form{ max-width:600px; margin:30px auto 15px auto; }
fieldset{ background:#f8f8f8; margin-bottom:20px; border:1px solid #CCC; }
fieldset legend{ font-weight:bold; color:#172457; background:none !important; border-bottom:0 !important; display: inline-block !important; width: auto !important; }
fieldset label{ margin-right:10px; font-size:12px; }
fieldset div.input{ padding:5px; }
fieldset div.input.number{ width:45%; display:inline-block; }
fieldset div.input input{ padding:3px !important; }
.radiobuttons label{ display:inline-block !important; }
.submit input[type=submit]{ background:#10204B; color:#FFF; border:0; padding:10px 15px; font-size:14px; font-weight:bold; cursor:pointer; }
div.checkboxes div.input{ display:inline-block; }

</style>
<script>
$(function(){
	$('form').submit(function(){
		
	});
});
</script>
<?php 
}elseif($step=="step2"){
	echo $this->Form->create(false);
	echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);
	foreach($inputdata as $key => $value){
		if($key != 'process'){
			echo $this->Form->input($key,['type'=>'hidden','value'=>$value]);
		}
	}
	echo "<h3>Review + Apply Changes</h3>";
	echo "<fieldset id=\"selectedentries\"><legend>Apply Changes To <small><em>(".count($entryids)." entries selected)</em></small></legend><ul>";
	foreach($selectedentries as $msrentry){
		echo "<li>".$msrentry['part_or_fabric']." ".$msrentry['revision_or_color']."</li>";
	}
	echo "</ul><div style=\"clear:both;\"></div></fieldset>";

    $otherChanges=0;
	echo "<fieldset><legend>Other Details</legend>";
	
	echo "<dl>";
	
	if($inputdata['order_status'] != ''){
	    echo "<dt>Order Status</dt>
	    <dd>".$inputdata['order_status']."</dd>";
	    $otherChanges++;
	}
	
	if($inputdata['estimated_ship_date'] != ''){
	    echo "<dt>Estimated Ship Date</dt>
	    <dd>".$inputdata['estimated_ship_date']."</dd>";
	    $otherChanges++;
	}else{
	    if($inputdata['blank_esd'] == '1'){
	        echo "<dt>Remove Est Ship Date</dt>
	        <dd>YES</dd>";
	        $otherChanges++;
	    }
	}
	
	if($inputdata['eta'] != ''){
	    echo "<dt>ETA</dt>
	    <dd>".$inputdata['eta']."</dd>";
	    $otherChanges++;
	}else{
	    if($inputdata['blank_eta'] == '1'){
	        echo "<dt>Remove ETA</dt>
	        <dd>YES</dd>";
	        $otherChanges++;
	    }
	}
	
	
	if($inputdata['shipment_status'] != ''){
	    echo "<dt>Shipment Status</dt>
	    <dd>".$inputdata['shipment_status']."</dd>";
	    $otherChanges++;
	}
	
	if($inputdata['qb_po_number'] != ''){
	    echo "<dt>QB PO#</dt>
	    <dd>".$inputdata['qb_po_number']."</dd>";
	    $otherChanges++;
	}
	
	if($inputdata['notes'] != ''){
	    echo "<dt>Notes</dt>
	    <dd>".$inputdata['notes']."</dd>";
	    $otherChanges++;
	}
	
	
	
	echo "</dl></fieldset>";
	

	echo "<div id=\"buttonrow\">";
	echo $this->Form->button('Go Back',['type'=>'button',"onclick"=>'history.go(-1)']);
	echo $this->Form->submit('Apply Changes Now');
	echo "<div style=\"clear:both;\"></div></div>";
	echo $this->Form->end();
	?>
	<style>
h3{ text-align: center; margin:0; }
#buttonrow button{ float:left; background:#CCC; border:1px solid #444; padding:5px !important; font-size:14px !important; color:#000 !important; }
#buttonrow .submit{ float:right; }
form{ max-width:600px; margin:30px auto 15px auto; }
fieldset{ background:#f8f8f8; margin-bottom:20px; border:1px solid #CCC; }
fieldset legend{ font-weight:bold; color:#172457; background:none !important; border-bottom:0 !important; display: inline-block !important; width: auto !important; }
fieldset label{ margin-right:10px; font-size:12px; }
fieldset div.input{ padding:5px; }
fieldset div.input.number{ width:45%; display:inline-block; }
fieldset div.input input{ padding:3px !important; }
.radiobuttons label{ display:inline-block !important; }
.submit input[type=submit]{ background:#10204B; color:#FFF; border:0; padding:10px 15px; font-size:14px; font-weight:bold; cursor:pointer; }
div.checkboxes div.input{ display:inline-block; }


fieldset dl{ font-size:12px; }
fieldset dl dd{ padding-left:30px; }
</style>
	<?php
}
?>