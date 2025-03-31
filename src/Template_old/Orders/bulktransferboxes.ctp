<style>
h2{ text-align:center; margin:5px 0 10px 0; }
h3{ text-align:center; margin:5px 0 10px 0; }

#buttonswrap{ margin-top:25px; }
#buttonswrap div.submit{ width:150px; float:left; }
#buttonswrap div.submit input{ background:#1F2965; color:#FFF; border:0; font-weight:bold; font-size:16px; padding:6px 20px; cursor:pointer; }
#buttonswrap button{ float:right; }
table#boxlist{ margin-bottom:25px; }

table#boxlist thead tr{ background:#1F2965; }
table#boxlist thead tr th{ color:#FFF; }
</style>
<h2>Bulk Transfer Boxes</h2>
<h3>Transfer the following:</h3>
<table id="boxlist" width="300" align="center" cellpadding="3" cellspacing="0" border="1">
<thead>
    <tr>
        <th>Box#</th>
        <th>Status</th>
        <th>Location</th>
    </tr>
</thead>
<tbody>
    <?php
    foreach($theseBoxes as $box){
        echo "<tr>
        <td>".$box['box_number']."</td>
        <td>".$box['status']."</td>
        <td>";
        foreach($warehouseLocations as $whLoc){
            if($whLoc['id'] == $box['warehouse_location_id']){
                echo $whLoc['location'];
            }
        }
        echo "</td></tr>";
    }
    ?>
</tbody>
</table>
<?php
echo $this->Form->create(null);
echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);

$warehouseLocationsArr=array();
foreach($warehouseLocations as $whLoc){
    $warehouseLocationsArr[$whLoc['id']]=$whLoc['location'];
}

echo "<div class=\"selectbox\"><label>Warehouse Location</label>";
echo $this->Form->select('warehouse_location',$warehouseLocationsArr,array('required'=>false,'empty'=>'--Select Location--'));
echo "</div>";

echo "<div id=\"buttonswrap\">";
echo $this->Form->submit('Transfer Boxes');

echo "<button type=\"button\" onclick=\"parent.$.fancybox.close()\">Cancel</button>";
echo "<div style=\"clear:both;\"></div></div>";

echo $this->Form->end();
?>