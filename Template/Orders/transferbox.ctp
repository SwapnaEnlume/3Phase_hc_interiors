<style>
h2{ float:left; }
h3{ float:right; }
#buttonswrap{ margin-top:25px; }
#buttonswrap div.submit{ width:150px; float:left; }
#buttonswrap div.submit input{ background:#1F2965; color:#FFF; border:0; font-weight:bold; font-size:16px; padding:6px 20px; cursor:pointer; }
#buttonswrap button{ float:right; }
</style>
<h2>Transfer A Box</h2>
<h3>Box #<?php echo $thisBox['box_number']; ?></h3>
<div style="clear:both;"></div>
<?php
echo $this->Form->create(null);

echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);

$warehouseLocationsArr=array();
foreach($warehouseLocations as $whLoc){
    $warehouseLocationsArr[$whLoc['id']]=$whLoc['location'];
}


echo "<p>Current Location: <u>";
foreach($warehouseLocations as $whLoc){
    if($whLoc['id'] == $thisBox['warehouse_location_id']){
        echo $whLoc['location'];
    }
}
echo "</u></p>";

echo "<div class=\"selectbox\"><label>Warehouse Location</label>";
echo $this->Form->select('warehouse_location',$warehouseLocationsArr,array('required'=>false,'empty'=>'--Select Location--'));
echo "</div>";

echo "<div id=\"buttonswrap\">";
echo $this->Form->submit('Transfer Box');

echo "<button type=\"button\" onclick=\"parent.$.fancybox.close()\">Cancel</button>";
echo "<div style=\"clear:both;\"></div></div>";

echo $this->Form->end();
?>