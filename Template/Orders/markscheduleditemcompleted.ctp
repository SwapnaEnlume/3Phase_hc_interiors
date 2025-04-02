<?php
echo $this->Form->create(null);

$batchFullyBoxed=false;

$batchLineTalliesVsBoxedTallies=array();

//echo "<pre>"; print_r($boxedItems); echo "</pre><hr>";

echo "<h2 style=\"float:left;\">Mark Batch as Produced</h2>
<h3 style=\"float:right;\">Work Order: <a href=\"".$thisWorkOrder['id']."/\" target=\"_blank\">".$thisWorkOrder['order_number']."</a></h3>
<hr style=\"clear:both;\" />

<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
<thead>
<tr>
<th width=\"12%\" style=\"font-size:12px;\">Line #</th>
<th width=\"22%\" style=\"font-size:12px;\">Item Type</th>
<th width=\"33%\" style=\"font-size:12px;\">QTY This Batch</th>
<th width=\"33%\" style=\"font-size:12px;\">QTY Boxed</th>
</tr>
</thead>
<tbody>";
foreach($ThisDayThisOrderStatuses as $statusEntry){
    
    
	echo "<tr>";
	echo "<td width=\"12%\">".$statusEntry['order_line_number']."</td>
	<td width=\"22%\">";
	$thisLineOverallQTY=0;
	
	foreach($thisOrderItems as $orderItem){
		if($orderItem['id'] == $statusEntry['order_item_id']){
			
			foreach($thisQuoteItems as $quoteItem){
				if($quoteItem['id'] == $orderItem['quote_line_item_id']){
					
					$thisLineOverallQTY=$quoteItem['qty'];
					
					switch($quoteItem['product_type']){
						case "cubicle_curtains":
							echo "Cubicle Curtains";
						break;
						case "bedspreads":
							echo "Bedspreads";
						break;
						case "window_treatments":
							echo $itemMeta[$quoteItem['id']]['wttype'];
						break;
						case "track_systems":
							echo "Track Systems";
						break;
						case "calculator":
							switch($itemMeta[$quoteItem['id']]['calculator-used']){
								case "box-pleated":
									echo $itemMeta[$quoteItem['id']]['valance-type']." Valance";
								break;
								case "bedspread":
									echo $itemMeta[$quoteItem['id']]['style']." Bedspread";
								break;
								case "straight-cornice":
									echo $itemMeta[$quoteItem['id']]['cornice-type']." Cornice";
								break;
								case "pinch-pleated":
									echo $itemMeta[$quoteItem['id']]['panel-type'].' '.$itemMeta[$quoteItem['id']]['unit-of-measure']." Pinch-Pleated Drapery";
								break;
								case "rod-pocket":

								break;
								case "ripple-fold":

								break;
								case "accordia-fold":

								break;
								case "grommet-top":

								break;
								case "swags-and-cascades":

								break;
							}
						break;
						case "custom":
							echo "Catch-All";
						break;
						case "services":
							echo "Service";
						break;
					}
					
					
					
				}
			}
			
		}
	}
	
	echo "</td>";
	echo "<td width=\"33%\">".$statusEntry['qty_involved']."</td>";
	echo "<td width=\"33%\">";
	
	$boxedThisLine=0;
	foreach($thisOrderItems as $orderItem){
		if($orderItem['id'] == $statusEntry['order_item_id']){
	        foreach($boxedItems as $boxid => $items){
	            foreach($items as $item){
	                if($item['quote_item_id'] == $orderItem['quote_line_item_id']){
	                    $boxedThisLine=($boxedThisLine+$item['qty']);
	                }
	            }
	        }
		}
	}
	
	echo $boxedThisLine;
	
	$batchLineTalliesVsBoxedTallies[]=array('batchqty'=>$statusEntry['qty_involved'],'boxedqty'=>$boxedThisLine);
	
	echo "</td>";
	echo "</tr>";
}
echo "</tbody>
</table>";

$incompleteCount=0;
foreach($batchLineTalliesVsBoxedTallies as $compare){
    if($compare['batchqty'] > $compare['boxedqty']){
        $incompleteCount++;
    }
}

if($incompleteCount > 0){
    $batchFullyBoxed=false;
}else{
    $batchFullyBoxed=true;
}

echo "<table width=\"100%\" cellpadding=\"0\" border=\"0\" style=\"border:0 !important; background:#FFF !important;\">
<tr style=\"border:0 !important; background:#FFF !important;\">
<td width=\"40%\" style=\"border:0 !important; background:#FFF !important;\">&nbsp;</td>
<td width=\"60%\" align=\"center\" style=\"border:0 !important; background:#FFF !important;\">";

if($batchFullyBoxed){
    echo "<span style=\"color:navy; font-weight:bold; font-size:18px;\">FULLY BOXED</span>";
}else{
    echo "<span style=\"color:red; font-weight:bold; font-size:18px;\">BATCH BOXING INCOMPLETE</span>";
}

echo "</td>
</tr>
</table>";

echo "<br><br>";

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

echo "<br><br>";

echo $this->Form->input('date',['label'=>'Date Completed','required'=>true,'autocomplete'=>'off']);

if(!$batchFullyBoxed){
    echo "<div style=\"text-align:center; padding:10px 0; font-size:18px; font-weight:bold; color:red;\">ALERT: THIS BATCH HAS NOT YET BEEN FULLY BOXED.<br>YOU ARE ABOUT TO MARK IT AS PRODUCED</div>";
}else{
    echo "<br><BR>";
}

echo "<div id=\"buttonsbottom\">";
echo $this->Form->submit('Mark This Schedule Batch as Produced');

echo "<button type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";

echo "</div><br><br>";

echo $this->Form->end();
?>
<script>
$(function(){
	$('#date').datepicker();
});
</script>

<style>
body{ font-family:Arial,Helvetica,sans-serif; }
form div.input{ margin-bottom:10px; }
form div.input label{ display:block; font-weight:bold; }
form div.input input[type=text]{ width:95%; padding:6px; }
form div.input textarea{ width:95%; padding:6px; }
	
tr.Mfgnote{ color:orange; }
tr.Shipnote{ color:purple; }

#buttonsbottom div.submit{ float:left; }
#buttonsbottom button[type=button]{ float:right; }
	
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