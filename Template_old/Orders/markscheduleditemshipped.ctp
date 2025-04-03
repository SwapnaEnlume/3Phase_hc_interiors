<style>
form{ max-width:100% !important; }
</style>
<?php
echo $this->Form->create(null);
/**PPSASCRUM-3 start **/

echo "<h2 style=\"float:left;\">Mark Batch as Shipped";
echo  ($thisCustomer['on_credit_hold']) ? '<span style="color: red;padding: 0px 0px 0px 150px;"> On Credit Hold</span>  ' : '';
echo "<h2><h3 style=\"float:right;\">Work Order: <a href=\"/orders/editlines/".$thisWorkOrder['id']."/workorder\" target=\"_blank\">".$thisWorkOrder['order_number']."</a></h3>
<hr style=\"clear:both;\" />

<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
<thead>
<tr>
<th>Line #</th>
<th>Item Type</th>
<th>QTY This Batch</th>
<th>QTY Non-Boxed</th>
<th>QTY Ordered</th>
</tr>
</thead>
<tbody>";
/**PPSASCRUM-3 end **/

foreach($lineItems as $idnum => $line){
    echo "<tr>
    <td class=\"linenumber\" style=\"text-align:center;font-size:14px;\">".$line['data']->line_number."</td>
    <td class=\"lineitem\" style=\"text-align:center;font-size:14px;\">".$line['data']->title."</td>
    <td class=\"batchqty\" style=\"text-align:center;font-size:14px;\">".$line['this_batch']."</td>
    <td class=\"nonboxedqty\" style=\"text-align:center;font-size:14px;\">";
    echo (intval($line['this_batch']) - intval($line['this_batch_other_boxes']) );
    echo "</td>
    <td class=\"orderqty\" style=\"text-align:center;font-size:14px;\">".($line['this_batch']+$line['other_batches']+$line['remaining_unscheduled'])."</td>
    </tr>";
}
echo "</tbody></table>";

echo "<br><Br>";

echo "<h3>Batch Notes:</h3>";
echo "<table id=\"batchnotes\" width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\"><thead>
	<tr>
	<th width=\"20%\" valign=\"top\">Date/Time</th>
	<th width=\"20%\" valign=\"top\">User</th>
	<th width=\"20%\" valign=\"top\">Note Type</th>
	<th width=\"40%\" valign=\"top\">Message</th>
	</tr>
</thead><tbody>";
foreach($batchNotes as $note){
	echo "<tr class=\"".$note['note_type']."note\"><td width=\"20%\" valign=\"top\">".date('n/j/Y g:iA',$note['time'])."</td>
	<td width=\"20%\" valign=\"top\">".$allUsers[$note['user_id']]['first_name']." ".substr($allUsers[$note['user_id']]['last_name'],0,1).".</td>
	<td width=\"20%\" valign=\"top\">".strtoupper($note['note_type'])."</td>
	<td width=\"40%\" valign=\"top\">".$note['message']."</td>
	</tr>";
}
echo "</tbody></table>";

echo "<input type=\"hidden\" id=\"doingBulkToggle\" value=\"no\" />";

echo "<br><br><script>
function toggleAllBoxes(){
    $('#doingBulkToggle').val('yes');
    if($('#checkallboxes').is(':checked')){
        $.get('/orders/togglebatchshipstatus/".$batchID."/yes',
            function(result){
                
                $('table#boxeslist tbody tr td.checkbox input').prop('checked',true);
                $('#doingBulkToggle').val('no');
                alert(result);
                
        });
    }else{
        $.get('/orders/togglebatchshipstatus/".$batchID."/no',
            function(result){
                
                $('table#boxeslist tbody tr td.checkbox input').prop('checked',false);
                $('#doingBulkToggle').val('no');
                alert(result);
                
        });
    }
}

function toggleBoxShipStatus(boxID){
    if( $('#doingBulkToggle').val() == 'no'){
        if($('input[name=\'markboxshipped_'+boxID+'\']').prop('checked')){
            var newvalue='yes';
        }else{
            var newvalue='no';
        }
        
        $.get('/orders/toggleboxshipstatus/'+boxID+'/'+newvalue,
        function(result){
            alert(result);
        });
    }else{
        //ignore the ajax request because all of the items are being toggled at once right now
    }
}
</script>";

//echo "<pre>"; print_r($lineItems); echo "</pre>";
//echo "<pre>"; print_r($batchBoxes); echo "</pre>";

$totalNonBoxed=0;
foreach($lineItems as $idnum => $line){
    $totalNonBoxed=( $totalNonBoxed + (intval($line['this_batch']) - intval($line['this_batch_other_boxes']) ));
}

$ifallcheckedonload='';
$boxCount=count($batchBoxes);
$shippedCount=0;

foreach($batchBoxes as $box){
    if($box['status'] == 'Shipped'){
        $shippedCount++;
    }
}

if($shippedCount == $boxCount){
    $ifallcheckedonload=" checked=\"checked\"";
}

echo "<h3>Batch Boxes:</h3><p>Check all the boxes below that have been shipped.</p>";
echo '<table width="100%" id="boxeslist" cellpadding="5" cellspacing="0" border="1">
<thead>
    <tr>
        <th class="checkbox">
            <input type="checkbox" onclick="toggleAllBoxes()" id="checkallboxes"'.$ifallcheckedonload.' />
        </th>
        <th class="boxnum">Box #</th>
        <th class="itemcount">Item Count</th>
        <th class="boxdims">Dimensions</th>
        <th class="boxweight">Weight</th>
        <th class="boxstatus">Status</th>
        <th class="boxuser">User</th>
        <th class="boxdate">Date/Time</th>
        <th class="warehouselocation">Warehouse Location</th>
    </tr>
</thead>
<tbody>';
    
    foreach($batchBoxes as $box){
        
      echo "<tr>
      <td class=\"checkbox\"><input type=\"checkbox\" name=\"markboxshipped_".$box['id']."\" value=\"yes\" ";
      if($box['status'] == 'Shipped'){ echo "checked=\"checked\" "; } 
      echo "onclick=\"toggleBoxShipStatus('".$box['id']."')\" /></td>
      <td class=\"boxnum\">".$box['box_number']."</td>
      <td class=\"itemcount\">".$box['item_count']."</td>
      <td class=\"boxdims\">".$box['length']." X ".$box['width']." X ".$box['height']."</td>
      <td class=\"boxweight\">".$box['weight']." lbs</td>
      <td class=\"boxstatus\">".$box['status']."</td>
      <td class=\"boxuser\">";
      foreach($allusers as $userData){
          if($userData['id'] == $box['user_id']){
              echo $userData['first_name']." ".substr($userData['last_name'],0,1);
          }
      }
      echo "</td>
      <td class=\"boxdate\">".date('M j - g:iA',$box['created'])."</td>
      <td class=\"warehouselocation\">";
      foreach($warehouseLocations as $whLoc){
          if($whLoc->id==$box['warehouse_location_id']){
              echo $whLoc['location'];
          }
      }
      echo "</td>
      </tr>";  
    }
    echo '</tbody>
</table><br><br>
<style>
table#boxeslist thead tr{ background:#664C00; }
table#boxeslist thead tr th{ color:#FFF !important; font-size:small; }
table#boxeslist tbody tr td{ font-size:small; line-height:1.4; border-bottom:1px solid #111 !important; border-left:1px solid #111 !important; }

table#boxeslist tbody tr:nth-of-type(odd){ background:rgba(0,0,0,0.01); }
table#boxeslist tbody tr:nth-of-type(even){ background:rgba(102,76,0,0.05); }

table#boxeslist .boxnum{ width:11%; text-align:center; vertical-align:middle; }
table#boxeslist .itemcount{width:8%; text-align:center; vertical-align:middle; }
table#boxeslist .boxdims{width:14%; text-align:center; vertical-align:middle; }
table#boxeslist .boxweight{width:8%; text-align:center; vertical-align:middle; }
table#boxeslist .boxstatus{width:12%; text-align:center; vertical-align:middle; }
table#boxeslist .boxuser{width:15%; text-align:center; vertical-align:middle; }
table#boxeslist .boxdate{width:14%; text-align:center; vertical-align:middle; }
table#boxeslist .warehouselocation{ width:10%; text-align:center; vertical-align:middle; }
table#boxeslist .checkbox{width:8%; text-align:center; vertical-align:middle; }

</style>';

echo $this->Form->input('carrier',['required'=>false]);

echo $this->Form->input('tracking_number',['required'=>true]);
echo "<div class=\"input textarea\">";
echo $this->Form->label('Delivery Address');


$shipto = $thisWorkOrder['shipping_address_1']."\n";

if(strlen($thisWorkOrder['shipping_address_2']) >0){
	$shipto .= $thisWorkOrder['shipping_address_2']."\n";
}
$shipto .= $thisWorkOrder['shipping_city'].", ".$thisWorkOrder['shipping_state']." ".$thisWorkOrder['shipping_zipcode'];


echo $this->Form->textarea('delivery_address',['required'=>true,'value'=>$shipto,'readonly'=>true]);
echo "</div>";


echo "<p><b>Facility:</b> ".$thisWorkOrder['facility']."</p>";

echo "<p><b>Attention:</b> ".$thisWorkOrder['attention']."</p>";

echo "<p><b>Customer Name:</b> ".$thisCustomer['company_name']."</p>";

echo "<p><b>Shipping Method:</b> ".$thisShippingMethod."</p>";

echo "<p><b>Shipping Instructions:</b> ".$thisWorkOrder['shipping_instructions']."</p>";

echo $this->Form->input('date_shipped',['label'=>'Date Shipped Date','required'=>false,'autocomplete'=>'off']);

echo "<br><BR><div id=\"buttonsbottom\">";
/**PPSASCRUM-3 start **/
echo  ($thisCustomer['on_credit_hold']) ? '<div style="padding: 0px 351px 0px 0px;text-align: right;font-size: 20px;font-weight: bold;"><span style="color: red;"> On Credit Hold</span> </div> ' : '';
/**PPSASCRUM-3 end **/
echo $this->Form->submit('Mark This Batch as Shipped');
echo "<button type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";

echo "</div><br><br>";

echo $this->Form->end();
?>
<script>
$(function(){
	$('#date-shipped').datepicker();
	console.log('NON-BOXED = <?php echo $totalNonBoxed; ?>');
	<?php
	if($totalNonBoxed > 0 && count($batchBoxes) > 0){
	?>
	$('form').submit(function(){
	    alert('ERROR: Cannot mark this batch as Shipped until the <?php echo $totalNonBoxed; ?> Non-Boxed items are listed as Boxed.');
	    return false;
	});
	<?php } ?>
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