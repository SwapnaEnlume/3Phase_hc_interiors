<?php
	echo "<h3 style=\"text-align:center;\">Packing Slip</h3>";
	//PPSASCRUM-7 start
    $addressName = '';
    if($orderData['shipto_id']  != NULL) {
       $addressName = $shipToName; //
    } else {
        $addressName =$orderData['facility'];
    }
  
	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"50%\" align=\"left\" valign=\"top\" style=\"font-size:9px;\">
	".$customerData['company_name']."<br>".$allSettings['hci_address_line_1']." ".$allSettings['hci_address_line_2']."<br>".$allSettings['hci_address_city'].", ".$allSettings['hci_address_state']." ".$allSettings['hci_address_zipcode']."</td>
	<td width=\"50%\" align=\"right\" valign=\"top\">
		<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
		<tr>
		<td width=\"15%\" align=\"left\" style=\"font-size:6px;\">&nbsp;</td>
		<td width=\"45%\" align=\"left\" style=\"font-size:8px;\">PACKING LIST NUMBER:</td>
		<td width=\"15%\" align=\"left\" style=\"border:1px solid #000000; font-size:6px;\">".$orderData['order_number']."</td>
		<td width=\"25%\" align=\"left\" style=\"font-size:6px;\">&nbsp;</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
	
	<br><br>
	
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<td width=\"10%\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">SHIP TO:</td>
	<td width=\"40%\" valign=\"top\" align=\"left\" style=\"font-size:9px;\"><table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\"><tr><td>"."<br>".strtoupper($addressName."<br>".$orderData['shipping_address_1']);
	if(strlen(trim($orderData['shipping_address_2'])) >0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode']);
	if(strlen(trim($orderData['attention'])) >0){
		echo "<br>ATTN: ".strtoupper($orderData['attention']);
	}
	  //PPSASCRUM-7 end
	echo "</td></tr></table></td>
	<td width=\"15%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"35%\" valign=\"top\" style=\"font-size:8px;\" align=\"left\">Date: ".date('m/d/Y')."<br><br>SHIPPING METHOD: ".$allShipMethods[$orderData['shipping_method_id']]."<br><br>SHIPPING INSTRUCTIONS: ".$orderData['shipping_instructions']."<br><br>SPECIAL:</td>
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
	<th width=\"5%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">Line #</th>
	<th width=\"8%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">Room #</th>
	<th width=\"8%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">QUANTITY<br>ORDERED</th>
	<th width=\"10%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">PREVIOUSLY<br>SHIPPED</th>
	<th width=\"10%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">QTY SHIPPED<br>TODAY</th>
	<th width=\"6%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">BACK<br>ORDER</th>
	<th width=\"43%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\" colspan=\"4\">DESCRIPTION</th>
	<th width=\"4%\" style=\"color:#FFFFFF; font-size:6px; border-left:2px solid #FFFFFF; border-top:2px solid #FFFFFF; border-right:1px solid #000000; border-bottom:2px solid #FFFFFF; background:#FFFFFF;\" bgcolor=\"#FFFFFF\" align=\"center\" valign=\"bottom\">&nbsp;</th>
	<th width=\"6%\" style=\"color:#FFFFFF; font-size:6px;\" align=\"center\" valign=\"bottom\">BOX #</th>
	</tr>
	</thead>
	<tbody>";
	
	
	
	foreach($lineItems as $itemID => $itemData){
			if($itemData['data']['internal_line'] == 0){
				echo "<tr>
				<td width=\"5%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['data']['line_number']."</td>
				<td width=\"8%\" style=\"font-size:7px;\" align=\"left\" valign=\"top\">".$itemData['data']['room_number']."</td>
				<td width=\"8%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">".$itemData['data']['qty'];
				if($itemData['data']['unit'] == 'linear feet'){
					echo " LF";
				}
				echo "</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">&nbsp;</td>
				<td width=\"10%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">&nbsp;</td>
				<td width=\"6%\" style=\"font-size:7px;\" valign=\"top\" align=\"center\">&nbsp;</td>";


				if($itemData['data']['product_type'] == 'track_systems'){
					echo "<td width=\"43%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\" colspan=\"4\">";
					echo $itemData['data']['title'];
					if(strlen(trim($itemData['data']['description'])) >0){
						echo "<br>";
						echo $itemData['data']['description'];
					}
					echo "</td>";

					echo "<td width=\"4%\" style=\"font-size:7px; border-left:2px solid #FFFFFF; border-top:2px solid #FFFFFF; border-right:1px solid #000000; border-bottom:2px solid #FFFFFF\" valign=\"top\">&nbsp;</td>
				<td width=\"6%\" style=\"font-size:7px;\" valign=\"top\">&nbsp;</td>
				</tr>";


				}else{


					echo "<td width=\"12%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">";
					if($itemData['metadata']['com-fabric'] == '1'){
						echo 'COM ';
					}
					if($itemData['data']['product_type'] == 'custom' && isset($itemData['metadata']['fabric_name']) && strlen(trim($itemData['metadata']['fabric_color'])) >0){
						echo $itemData['metadata']['fabric_name'];
					}else{
						echo $itemData['fabricdata']['fabric_name'];
					}
					echo "</td>
					<td width=\"9%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">";
					if($itemData['data']['product_type'] == 'custom' && isset($itemData['metadata']['fabric_color']) && strlen(trim($itemData['metadata']['fabric_color'])) >0){
						echo $itemData['metadata']['fabric_color'];
					}else{
						echo $itemData['fabricdata']['color'];
					}
					echo "</td>";

					if($itemData['data']['product_type'] == 'custom'){
						echo "<td width=\"22%\" style=\"font-size:7px;\" align=\"center\" valign=\"top\">";
						
						if(strlen(trim($itemData['data']['title'])) >0){
							echo $itemData['data']['title']."<br>";
						}
						
						if(strlen(trim($itemData['data']['description'])) >0){
							echo $itemData['data']['description']."<br>";
						}

						if(isset($itemData['metadata']['specs']) && strlen(trim($itemData['metadata']['specs'])) >0){
							echo $itemData['metadata']['specs'];
						}

						echo "</td>";
					}else{
						echo "<td width=\"9%\" style=\"font-size:7px; border-right:2px solid #FFFFFF; border-bottom:1px solid #000000;\" align=\"center\" valign=\"top\">".$itemData['metadata']['width']." x ".$itemData['metadata']['length']."</td>
							<td width=\"13%\" style=\"font-size:7px;\" valign=\"top\" align=\"center\">";
				
							switch($itemData['data']['product_type']){
								case "bedspreads":
									echo "Bedspread";
								break;
								case "cubicle_curtains":
									echo "Cubicle Curtain";
								break;
								case "window_treatments":
									echo $itemData['metadata']['wttype'];
								break;
								case "calculator":
									switch($itemData['data']['calculator_used']){
										case "pinch-pleated":
											echo "Pinch Pleated Drapery";
										break;
										case "bedspread":
											echo "Bedspread";
										break;
										case "cubicle-curtain":
											echo "Cubicle Curtain";
										break;
										case "box-pleated":
											echo $itemData['metadata']['valance-type']." Valance";
										break;
										case "straight-cornice":
											echo $itemData['metadata']['cornice-type']." Cornice";
										break;
									}
								break;
								case 'newcatchall-bedding':
                        		case 'newcatchall-blinds':
                                case 'newcatchall-cornice':
                                case 'newcatchall-cubicle':
                                case 'newcatchall-drapery':
                                case 'newcatchall-hardware':
                                case 'newcatchall-hwtmisc':
                                case 'newcatchall-misc':
                                case 'newcatchall-service':
                                case 'newcatchall-shades':
                                case 'newcatchall-shutters':
                                case 'newcatchall-swtmisc':
                                case 'newcatchall-valance':
                                    echo $itemData['data']['title'];
                                break;
							}
					
							echo "</td>";
						}
				echo "<td width=\"4%\" style=\"font-size:7px; border-left:2px solid #FFFFFF; border-top:2px solid #FFFFFF; border-right:1px solid #000000; border-bottom:2px solid #FFFFFF\" valign=\"top\">&nbsp;</td>
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
		<td width=\"10%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"10%\" style=\"font-size:7px; border-left:1px solid #000000; border-top:1px solid #000000; border-right:1px solid #000000; border-bottom:1px solid #000000;\">&nbsp;</td>
		<td width=\"10%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"20%\" style=\"font-size:7px;\">PACKED BY</td>
		<td width=\"30%\" style=\"font-size:7px;\">______________________</td>
	</tr>
	<tr>
		<td width=\"20%\" style=\"font-size:7px;\">BALANCE TO FOLLOW</td>
		<td width=\"10%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"10%\" style=\"font-size:7px; border-left:1px solid #000000; border-top:1px solid #000000; border-right:1px solid #000000; border-bottom:1px solid #000000;\">&nbsp;</td>
		<td width=\"10%\" style=\"font-size:7px;\">&nbsp;</td>
		<td width=\"20%\" style=\"font-size:7px;\">CHECKED BY</td>
		<td width=\"30%\" style=\"font-size:7px;\">______________________</td>
	</tr>
	</table>";
	
//exit;
?>