<?php
$usedTemplates=0;

$numUseCCs=0;
$numUseBSs=0;
$numUseWTs=0;

foreach($lineItems as $itemID => $itemData){
	if($itemData['data']['product_type'] == 'cubicle_curtains' || $itemData['data']['calculator_used']=='cubicle-curtain'){
		if($postdata["useline_".$itemID] == "1"){
			$numUseCCs++;
		}
	}
	
	if($itemData['data']['product_type'] == 'bedspreads' || $itemData['data']['calculator_used']=='bedspread' || $itemData['data']['calculator_used'] == 'bedspread-manual'){
		if($postdata["useline_".$itemID] == "1"){
			$numUseBSs++;
		}
	}
	
	if($itemData['data']['product_type'] == 'window_treatments' || $itemData['data']['calculator_used']=='straight-cornice' || $itemData['data']['calculator_used']=='box-pleated'){
		if($postdata["useline_".$itemID] == "1"){
			$numUseWTs++;
		}
	}
	
}



if(intval($orderData['cc_qty']) > 0 && $numUseCCs > 0){
	if($usedTemplates > 0){
		echo "<br pagebreak=\"true\"/>";
	}
	
	echo "<h3 style=\"text-align:center;\">Cubicle Curtain Packing Slip</h3>";
	
	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"50%\" align=\"left\" valign=\"top\" style=\"font-size:9px;\">
	Healthcare Interiors<br>".$allSettings['hci_address_line_1']." ".$allSettings['hci_address_line_2']."<br>".$allSettings['hci_address_city'].", ".$allSettings['hci_address_state']." ".$allSettings['hci_address_zipcode']."</td>
	<td width=\"50%\" align=\"right\" valign=\"top\">
		<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
		<tr>
		<td width=\"15%\" align=\"left\" style=\"font-size:6px;\">&nbsp;</td>
		<td width=\"45%\" align=\"left\" style=\"font-size:8px;\">PACKING LIST NUMBER:</td>
		<td width=\"15%\" align=\"left\" style=\"border:1px solid #000000; font-size:6px;\">".$postdata['packslip_number']."</td>
		<td width=\"25%\" align=\"left\" style=\"font-size:6px;\">&nbsp;</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
	
	<br><br>
	
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<td width=\"10%\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">SOLD TO:</td>
	<td width=\"40%\" valign=\"top\" align=\"left\" style=\"font-size:9px;\"><table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\"><tr><td>".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);
	if(strlen(trim($orderData['shipping_address_2'])) >0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode']);
	if(strlen(trim($orderData['attention'])) >0){
		echo "<br>ATTN: ".strtoupper($orderData['attention']);
	}
	echo "</td></tr></table></td>
	<td width=\"15%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"35%\" valign=\"top\" style=\"font-size:8px;\" align=\"left\">Date: ".date('m/d/Y')."<br><br>SHIPPING INSTRUCTIONS: ".$orderData['shipping_instructions']."<br><br>SPECIAL:</td>
	</tr>
	</table>
	
	<br><br>
	
	<table width=\"60%\" cellspacing=\"0\" cellpadding=\"3\" border=\"1\" bordercolor=\"#000000\">
	<tr>
	<td align=\"center\" style=\"font-size:8px;\">OUR ORDER NO.</td>
	<td align=\"center\" style=\"font-size:8px;\">YOUR ORDER NO.</td>
	<td align=\"center\" style=\"font-size:8px;\">CARTONS - PKGS</td>
	<td align=\"center\" style=\"font-size:8px;\">TOTAL WEIGHT</td>
	</tr>
	<tr>
	<td align=\"center\" style=\"font-size:8px;\">".$orderData['order_number']."</td>
	<td align=\"center\" style=\"font-size:8px;\">".$orderData['po_number']."</td>
	<td align=\"center\" style=\"font-size:8px;\">&nbsp;</td>
	<td align=\"center\" style=\"font-size:8px;\">&nbsp;</td>
	</tr>
	</table>
	
	<br><br><br>
	
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
	<thead>
	<tr bgcolor=\"#000000\">
	<th width=\"8%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">Room #</th>
	<th width=\"8%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">QUANTITY<br>ORDERED</th>
	<th width=\"10%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">PREVIOUSLY<br>SHIPPED</th>
	<th width=\"10%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">QTY SHIPPED<br>TODAY</th>
	<th width=\"6%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">BACK<br>ORDER</th>
	<th width=\"48%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\" colspan=\"4\">DESCRIPTION</th>
	<th width=\"4%\" style=\"color:#FFFFFF; font-size:6px; border-left:2px solid #FFFFFF; border-top:2px solid #FFFFFF; border-right:1px solid #000000; border-bottom:2px solid #FFFFFF; background:#FFFFFF;\" bgcolor=\"#FFFFFF\" align=\"center\" valign=\"bottom\">&nbsp;</th>
	<th width=\"6%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">BOX #</th>
	</tr>
	</thead>
	<tbody>";
	
	
	
	foreach($lineItems as $itemID => $itemData){
		if($itemData['data']['product_type'] == 'cubicle_curtains' || $itemData['data']['calculator_used']=='cubicle-curtain'){
			if($postdata["useline_".$itemID] == "1"){
				echo "<tr>
				<td width=\"8%\" style=\"font-size:7px;\" align=\"left\" valign=\"top\">".$itemData['data']['room_number']."</td>
				<td width=\"8%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['data']['qty']."</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['previously_shipped']."</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$postdata["qty_in_package_".$itemID]."</td>
				<td width=\"6%\" style=\"font-size:7px;\" valign=\"top\" align=\"center\">".$postdata["backorder_count_".$itemID]."</td>
				<td width=\"12%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['fabricdata']['fabric_name']."</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['fabricdata']['color']."</td>
				<td width=\"10%\" style=\"font-size:7px; border-right:2px solid #FFFFFF; border-bottom:1px solid #000000;\" align=\"center\" valign=\"top\">".$itemData['metadata']['width']." x ".$itemData['metadata']['length']."</td>
				<td width=\"16%\" style=\"font-size:7px;\" valign=\"top\" align=\"center\">Cubicle Curtain</td>
				<td width=\"4%\" style=\"font-size:7px; border-left:2px solid #FFFFFF; border-top:2px solid #FFFFFF; border-right:1px solid #000000; border-bottom:2px solid #FFFFFF\" valign=\"top\">&nbsp;</td>
				<td width=\"6%\" style=\"font-size:7px;\" valign=\"top\">&nbsp;</td>
				</tr>";
			}
		}
	}
	
	echo "</tbody>
	</table>";
	
	echo "<br><br>
	
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<tr>
		<td width=\"20%\" style=\"font-size:7px;\">ORDER COMPLETE</td>
		<td width=\"20%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"10%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"20%\" style=\"font-size:7px;\">PACKED BY</td>
		<td width=\"30%\" style=\"font-size:7px;\">______________________</td>
	</tr>
	<tr>
		<td width=\"20%\" style=\"font-size:7px;\">BALANCE TO FOLLOW</td>
		<td width=\"20%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"10%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"20%\" style=\"font-size:7px;\">CHECKED BY</td>
		<td width=\"30%\" style=\"font-size:7px;\">______________________</td>
	</tr>
	</table>";
	
	$usedTemplates++;
}

if(intval($orderData['bs_qty']) > 0 && $numUseBSs > 0){
	if($usedTemplates > 0){
		echo "<br pagebreak=\"true\"/>";
	}
	
	
	echo "<h3 style=\"text-align:center;\">Bedspread Packing Slip</h3>";
	
	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"50%\" align=\"left\" valign=\"top\" style=\"font-size:9px;\">
	Healthcare Interiors<br>".$allSettings['hci_address_line_1']." ".$allSettings['hci_address_line_2']."<br>".$allSettings['hci_address_city'].", ".$allSettings['hci_address_state']." ".$allSettings['hci_address_zipcode']."</td>
	<td width=\"50%\" align=\"right\" valign=\"top\">
		<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
		<tr>
		<td width=\"15%\" align=\"left\" style=\"font-size:6px;\">&nbsp;</td>
		<td width=\"45%\" align=\"left\" style=\"font-size:8px;\">PACKING LIST NUMBER:</td>
		<td width=\"15%\" align=\"left\" style=\"border:1px solid #000000; font-size:6px;\">".$postdata['packslip_number']."</td>
		<td width=\"25%\" align=\"left\" style=\"font-size:6px;\">&nbsp;</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
	
	<br><br>
	
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<td width=\"10%\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">SOLD TO:</td>
	<td width=\"40%\" valign=\"top\" align=\"left\" style=\"font-size:9px;\"><table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\"><tr><td>".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);
	if(strlen(trim($orderData['shipping_address_2'])) >0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode']);
	if(strlen(trim($orderData['attention'])) >0){
		echo "<br>ATTN: ".strtoupper($orderData['attention']);
	}
	echo "</td></tr></table></td>
	<td width=\"15%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"35%\" valign=\"top\" style=\"font-size:8px;\" align=\"left\">Date: ".date('m/d/Y')."<br><br>SHIPPING INSTRUCTIONS: ".$orderData['shipping_instructions']."<br><br>SPECIAL:</td>
	</tr>
	</table>
	
	<br><br>
	
	<table width=\"60%\" cellspacing=\"0\" cellpadding=\"3\" border=\"1\" bordercolor=\"#000000\">
	<tr>
	<td align=\"center\" style=\"font-size:8px;\">OUR ORDER NO.</td>
	<td align=\"center\" style=\"font-size:8px;\">YOUR ORDER NO.</td>
	<td align=\"center\" style=\"font-size:8px;\">CARTONS - PKGS</td>
	<td align=\"center\" style=\"font-size:8px;\">TOTAL WEIGHT</td>
	</tr>
	<tr>
	<td align=\"center\" style=\"font-size:8px;\">".$orderData['order_number']."</td>
	<td align=\"center\" style=\"font-size:8px;\">".$orderData['po_number']."</td>
	<td align=\"center\" style=\"font-size:8px;\">&nbsp;</td>
	<td align=\"center\" style=\"font-size:8px;\">&nbsp;</td>
	</tr>
	</table>
	
	<br><br><br>
	
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
	<thead>
	<tr bgcolor=\"#000000\">
	<th width=\"8%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">Room #</th>
	<th width=\"8%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">QUANTITY<br>ORDERED</th>
	<th width=\"10%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">PREVIOUSLY<br>SHIPPED</th>
	<th width=\"10%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">QTY SHIPPED<br>TODAY</th>
	<th width=\"6%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">BACK<br>ORDER</th>
	<th width=\"48%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\" colspan=\"4\">DESCRIPTION</th>
	<th width=\"4%\" style=\"color:#FFFFFF; font-size:6px; border-left:2px solid #FFFFFF; border-top:2px solid #FFFFFF; border-right:1px solid #000000; border-bottom:2px solid #FFFFFF; background:#FFFFFF;\" bgcolor=\"#FFFFFF\" align=\"center\" valign=\"bottom\">&nbsp;</th>
	<th width=\"6%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">BOX #</th>
	</tr>
	</thead>
	<tbody>";
	
	
	
	foreach($lineItems as $itemID => $itemData){
		if($itemData['data']['product_type'] == 'bedspreads' || $itemData['data']['calculator_used']=='bedspread' || $itemData['data']['calculator_used'] == 'bedspread-manual'){
			if($postdata["useline_".$itemID] == "1"){
				echo "<tr>
				<td width=\"8%\" style=\"font-size:7px;\" align=\"left\" valign=\"top\">".$itemData['data']['room_number']."</td>
				<td width=\"8%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['data']['qty']."</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['previously_shipped']."</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$postdata["qty_in_package_".$itemID]."</td>
				<td width=\"6%\" style=\"font-size:7px;\" valign=\"top\" align=\"center\">".$postdata["backorder_count_".$itemID]."</td>
				<td width=\"12%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['fabricdata']['fabric_name']."</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['fabricdata']['color']."</td>
				<td width=\"10%\" style=\"font-size:7px; border-right:2px solid #FFFFFF; border-bottom:1px solid #000000;\" align=\"center\" valign=\"top\">".$itemData['metadata']['width']." x ".$itemData['metadata']['length']."</td>
				<td width=\"16%\" style=\"font-size:7px;\" valign=\"top\" align=\"center\">Bedspread - ";
				$stylesplit=explode(" (",$itemData['metadata']['style']);
				echo $stylesplit[0];
				echo "</td>
				<td width=\"4%\" style=\"font-size:7px; border-left:2px solid #FFFFFF; border-top:2px solid #FFFFFF; border-right:1px solid #000000; border-bottom:2px solid #FFFFFF\" valign=\"top\">&nbsp;</td>
				<td width=\"6%\" style=\"font-size:7px;\" valign=\"top\">&nbsp;</td>
				</tr>";
			}
		}
	}
	
	echo "</tbody>
	</table>";
	
	echo "<br><br>
	
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<tr>
		<td width=\"20%\" style=\"font-size:7px;\">ORDER COMPLETE</td>
		<td width=\"20%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"10%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"20%\" style=\"font-size:7px;\">PACKED BY</td>
		<td width=\"30%\" style=\"font-size:7px;\">______________________</td>
	</tr>
	<tr>
		<td width=\"20%\" style=\"font-size:7px;\">BALANCE TO FOLLOW</td>
		<td width=\"20%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"10%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"20%\" style=\"font-size:7px;\">CHECKED BY</td>
		<td width=\"30%\" style=\"font-size:7px;\">______________________</td>
	</tr>
	</table>";
	
	$usedTemplates++;
}

if(intval($orderData['tt_qty']) > 0 && $numUseWTs > 0){
	if($usedTemplates > 0){
		echo "<br pagebreak=\"true\"/>";
	}
	
	
	echo "<h3 style=\"text-align:center;\">Window Treatments Packing Slip</h3>";
	
	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"50%\" align=\"left\" valign=\"top\" style=\"font-size:9px;\">
	Healthcare Interiors<br>".$allSettings['hci_address_line_1']." ".$allSettings['hci_address_line_2']."<br>".$allSettings['hci_address_city'].", ".$allSettings['hci_address_state']." ".$allSettings['hci_address_zipcode']."</td>
	<td width=\"50%\" align=\"right\" valign=\"top\">
		<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
		<tr>
		<td width=\"15%\" align=\"left\" style=\"font-size:6px;\">&nbsp;</td>
		<td width=\"45%\" align=\"left\" style=\"font-size:8px;\">PACKING LIST NUMBER:</td>
		<td width=\"15%\" align=\"left\" style=\"border:1px solid #000000; font-size:6px;\">".$postdata['packslip_number']."</td>
		<td width=\"25%\" align=\"left\" style=\"font-size:6px;\">&nbsp;</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
	
	<br><br>
	
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<td width=\"10%\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">SOLD TO:</td>
	<td width=\"40%\" valign=\"top\" align=\"left\" style=\"font-size:9px;\"><table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\"><tr><td>".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);
	if(strlen(trim($orderData['shipping_address_2'])) >0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode']);
	if(strlen(trim($orderData['attention'])) >0){
		echo "<br>ATTN: ".strtoupper($orderData['attention']);
	}
	echo "</td></tr></table></td>
	<td width=\"15%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"35%\" valign=\"top\" style=\"font-size:8px;\" align=\"left\">Date: ".date('m/d/Y')."<br><br>SHIPPING INSTRUCTIONS: ".$orderData['shipping_instructions']."<br><br>SPECIAL:</td>
	</tr>
	</table>
	
	<br><br>
	
	<table width=\"60%\" cellspacing=\"0\" cellpadding=\"3\" border=\"1\" bordercolor=\"#000000\">
	<tr>
	<td align=\"center\" style=\"font-size:8px;\">OUR ORDER NO.</td>
	<td align=\"center\" style=\"font-size:8px;\">YOUR ORDER NO.</td>
	<td align=\"center\" style=\"font-size:8px;\">CARTONS - PKGS</td>
	<td align=\"center\" style=\"font-size:8px;\">TOTAL WEIGHT</td>
	</tr>
	<tr>
	<td align=\"center\" style=\"font-size:8px;\">".$orderData['order_number']."</td>
	<td align=\"center\" style=\"font-size:8px;\">".$orderData['po_number']."</td>
	<td align=\"center\" style=\"font-size:8px;\">&nbsp;</td>
	<td align=\"center\" style=\"font-size:8px;\">&nbsp;</td>
	</tr>
	</table>
	
	<br><br><br>
	
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
	<thead>
	<tr bgcolor=\"#000000\">
	<th width=\"8%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">Room #</th>
	<th width=\"8%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">QUANTITY<br>ORDERED</th>
	<th width=\"10%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">PREVIOUSLY<br>SHIPPED</th>
	<th width=\"10%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">QTY SHIPPED<br>TODAY</th>
	<th width=\"6%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">BACK<br>ORDER</th>
	<th width=\"48%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\" colspan=\"4\">DESCRIPTION</th>
	<th width=\"4%\" style=\"color:#FFFFFF; font-size:6px; border-left:2px solid #FFFFFF; border-top:2px solid #FFFFFF; border-right:1px solid #000000; border-bottom:2px solid #FFFFFF; background:#FFFFFF;\" bgcolor=\"#FFFFFF\" align=\"center\" valign=\"bottom\">&nbsp;</th>
	<th width=\"6%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">BOX #</th>
	</tr>
	</thead>
	<tbody>";
	
	
	
	foreach($lineItems as $itemID => $itemData){
		if($itemData['data']['product_type'] == 'window_treatments' || $itemData['data']['calculator_used']=='straight-cornice' || $itemData['data']['calculator_used']=='box-pleated'){
			if($postdata["useline_".$itemID] == "1"){
				echo "<tr>
				<td width=\"8%\" style=\"font-size:7px;\" align=\"left\" valign=\"top\">".$itemData['data']['room_number']."</td>
				<td width=\"8%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['data']['qty']."</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['previously_shipped']."</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$postdata["qty_in_package_".$itemID]."</td>
				<td width=\"6%\" style=\"font-size:7px;\" valign=\"top\" align=\"center\">".$postdata["backorder_count_".$itemID]."</td>
				<td width=\"12%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['fabricdata']['fabric_name']."</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['fabricdata']['color']."</td>
				<td width=\"10%\" style=\"font-size:7px; border-right:2px solid #FFFFFF; border-bottom:1px solid #000000;\" align=\"center\" valign=\"top\">".$itemData['metadata']['width']." x ".$itemData['metadata']['length']."</td>
				<td width=\"16%\" style=\"font-size:7px;\" valign=\"top\" align=\"center\">&nbsp;</td>
				<td width=\"4%\" style=\"font-size:7px; border-left:2px solid #FFFFFF; border-top:2px solid #FFFFFF; border-right:1px solid #000000; border-bottom:2px solid #FFFFFF\" valign=\"top\">&nbsp;</td>
				<td width=\"6%\" style=\"font-size:7px;\" valign=\"top\">&nbsp;</td>
				</tr>";
			}
		}
	}
	
	echo "</tbody>
	</table>";
	
	echo "<br><br>
	
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<tr>
		<td width=\"20%\" style=\"font-size:7px;\">ORDER COMPLETE</td>
		<td width=\"20%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"10%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"20%\" style=\"font-size:7px;\">PACKED BY</td>
		<td width=\"30%\" style=\"font-size:7px;\">______________________</td>
	</tr>
	<tr>
		<td width=\"20%\" style=\"font-size:7px;\">BALANCE TO FOLLOW</td>
		<td width=\"20%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"10%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"20%\" style=\"font-size:7px;\">CHECKED BY</td>
		<td width=\"30%\" style=\"font-size:7px;\">______________________</td>
	</tr>
	</table>";

	
	$usedTemplates++;
}


//exit;
?>