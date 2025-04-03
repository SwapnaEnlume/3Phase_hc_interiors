<?php
$usedTemplates=0;

if(intval($orderData['cc_qty']) > 0){
	if($usedTemplates > 0){
		echo "<br pagebreak=\"true\"/>";
	}
	
	
	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"60%\" valign=\"top\">
	
		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr>
				<td colspan=\"4\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">CUBICLE CURTAIN MATERIAL USAGE WORKSHEET</span></td>
			</tr>
			<tr>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>CUSTOMER:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".$customerData['company_name']."</span></td>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>WO #:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".$orderData['order_number']."</span></td>
			</tr>
			<tr>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>Customer PO #:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>Date:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".date('m/d/Y',$orderData['created'])."</span></td>
			</tr>
		</table>
	
	</td>
	<td width=\"40%\" valign=\"top\">
	&nbsp;
	</td>
	</tr>
	</table>";
	
	
	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
	<thead>
		<tr bgcolor=\"#000000\">
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">LINE</th>
		<th width=\"4%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">QTY</th>
		<th width=\"14%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">FABRIC</th>
		<th width=\"14%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">COLOR</th>
		<th width=\"8%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">MESH<br>COLOR</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">WIDTH</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">LENGTH</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">ESTIMATED<br>YARDS</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">YDS PER<br>CURTAIN</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">ROLL #</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">TAGGED YDS<br>BEFORE JOB</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">PROJECTED<br>YDS / ROLL</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">ACTUAL YDS<br>USED</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">YDS LEFT IN<br>ROLL</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">CUT BY:</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">DATE</th>
		</tr>
	</thead>
	<tbody>";
	
	foreach($lineItems as $itemID => $itemData){
		if($itemData['data']['product_type'] == 'cubicle_curtains' || $itemData['data']['calculator_used']=='cubicle-curtain'){
			echo "<tr>
			<td width=\"5%\" rowspan=\"5\" valign=\"middle\" align=\"right\" style=\"vertical-align:middle; line-height:50px; font-size:16px; font-weight:bold;\">".$itemData['data']['line_number']."</td>
			<td width=\"4%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['data']['qty']."</td>
			<td width=\"14%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">";
			if($itemData['metadata']['com-fabric'] == '1'){
				echo "COM ";
			}
			echo $itemData['fabricdata']['fabric_name']."</td>
			<td width=\"14%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['fabricdata']['color']."</td>
			<td width=\"8%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['mesh'].'&quot; '.$itemData['metadata']['mesh-color']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['width']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['length']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['total-yds']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['yds-per-unit']."</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>";
		}
	}
	
	echo "</tbody>
	</table><br><br>
	<table width=\"60%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
	<thead>
	<tr bgcolor=\"#000000\">
	<th align=\"center\" width=\"6%\" style=\"font-size:6px; color:#FFFFFF;\">LINE:</th>
	<th align=\"center\" width=\"74%\" style=\"font-size:6px; color:#FFFFFF;\">REASONS FOR DIFFERENCES</th>
	<th align=\"center\" width=\"20%\" style=\"font-size:6px; color:#FFFFFF;\">SIGNED:</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td width=\"6%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"74%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"20%\" style=\"font-size:6px;\">&nbsp;</td>
	</tr>
	<tr>
	<td width=\"6%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"74%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"20%\" style=\"font-size:6px;\">&nbsp;</td>
	</tr>
	<tr>
	<td width=\"6%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"74%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"20%\" style=\"font-size:6px;\">&nbsp;</td>
	</tr>
	</tbody>
	</table>";
	
	$usedTemplates++;
}

if(intval($orderData['bs_qty']) > 0){
	if($usedTemplates > 0){
		echo "<br pagebreak=\"true\"/>";
	}
	
	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"60%\" valign=\"top\">
	
		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr>
			<td colspan=\"4\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">BEDSPREADS MATERIAL USAGE WORKSHEET</span></td>
			</tr>
			<tr>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>CUSTOMER:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".$customerData['company_name']."</span></td>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>WO #:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".$orderData['order_number']."</span></td>
			</tr>
			<tr>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>Customer PO #:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>Date:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".date('m/d/Y',$orderData['created'])."</span></td>
			</tr>
		</table>
	
	</td>
	<td width=\"40%\" valign=\"top\">
	&nbsp;
	</td>
	</tr>
	</table>";
	
	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
	<thead>
		<tr bgcolor=\"#000000\">
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">LINE</th>
		<th width=\"4%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">QTY</th>
		<th width=\"18%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">FABRIC</th>
		<th width=\"18%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">COLOR</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">WIDTH</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">LENGTH</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">ESTIMATED<br>YARDS</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">YDS PER<br>BEDSPREAD</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">ROLL #</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">TAGGED YDS<br>BEFORE JOB</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">PROJECTED<br>YDS / ROLL</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">ACTUAL YDS<br>USED</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">YDS LEFT IN<br>ROLL</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">CUT BY:</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">DATE</th>
		</tr>
	</thead>
	<tbody>";
	
	foreach($lineItems as $itemID => $itemData){
		if($itemData['data']['product_type'] == 'bedspreads' || $itemData['data']['calculator_used'] == 'bedspread'){
			echo "<tr>
			<td width=\"5%\" rowspan=\"5\" valign=\"middle\" align=\"right\" style=\"vertical-align:middle; line-height:50px; font-size:16px; font-weight:bold;\">".$itemData['data']['line_number']."</td>
			<td width=\"4%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['data']['qty']."</td>
			<td width=\"18%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">";
			if($itemData['metadata']['com-fabric'] == '1'){
				echo "COM ";
			}
			echo $itemData['fabricdata']['fabric_name']."</td>
			<td width=\"18%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['fabricdata']['color']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['width']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['length']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".(floatval($itemData['metadata']['yds-per-unit'])*floatval($itemData['data']['qty']))."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['yds-per-unit']."</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>";
		}
	}
	
	echo "</tbody>
	</table><br><br>
	<table width=\"60%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
	<thead>
	<tr bgcolor=\"#000000\">
	<th align=\"center\" width=\"6%\" style=\"font-size:6px; color:#FFFFFF;\">LINE:</th>
	<th align=\"center\" width=\"74%\" style=\"font-size:6px; color:#FFFFFF;\">REASONS FOR DIFFERENCES</th>
	<th align=\"center\" width=\"20%\" style=\"font-size:6px; color:#FFFFFF;\">SIGNED:</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td width=\"6%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"74%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"20%\" style=\"font-size:6px;\">&nbsp;</td>
	</tr>
	<tr>
	<td width=\"6%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"74%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"20%\" style=\"font-size:6px;\">&nbsp;</td>
	</tr>
	<tr>
	<td width=\"6%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"74%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"20%\" style=\"font-size:6px;\">&nbsp;</td>
	</tr>
	</tbody>
	</table>";
	
	
	$usedTemplates++;
}

if(intval($orderData['tt_qty']) > 0 || intval($orderData['drape_qty']) > 0){
	if($usedTemplates > 0){
		echo "<br pagebreak=\"true\"/>";
	}
	
	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"60%\" valign=\"top\">
	
		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr>
			<td colspan=\"4\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">WINDOW TREATMENTS MATERIAL USAGE WORKSHEET</span></td>
			</tr>
			<tr>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>CUSTOMER:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".$customerData['company_name']."</span></td>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>WO #:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".$orderData['order_number']."</span></td>
			</tr>
			<tr>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>Customer PO #:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
				<td width=\"15%\" valign=\"top\" align=\"left\"><span style=\"font-size:8px;\"><b>Date:</b></span></td>
				<td width=\"35%\" valign=\"top\" align=\"center\"><span style=\"font-size:8px;\">".date('m/d/Y',$orderData['created'])."</span></td>
			</tr>
		</table>
	
	</td>
	<td width=\"40%\" valign=\"top\">
	&nbsp;
	</td>
	</tr>
	</table>";
	
	
	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
	<thead>
		<tr bgcolor=\"#000000\">
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">LINE</th>
		<th width=\"4%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">QTY</th>
		<th width=\"14%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">FABRIC</th>
		<th width=\"14%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">COLOR</th>
		<th width=\"8%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">MESH<br>COLOR</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">WIDTH</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">LENGTH</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">ESTIMATED<br>YARDS</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">YDS PER<br>CURTAIN</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">ROLL #</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">TAGGED YDS<br>BEFORE JOB</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">PROJECTED<br>YDS / ROLL</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">ACTUAL YDS<br>USED</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">YDS LEFT IN<br>ROLL</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">CUT BY:</th>
		<th width=\"5%\" style=\"font-size:5px; color:#FFFFFF;\" align=\"center\">DATE</th>
		</tr>
	</thead>
	<tbody>";
	
	foreach($lineItems as $itemID => $itemData){
		if($itemData['data']['product_type'] == 'window_treatments' || $itemData['data']['calculator_used']=='box-pleated' || $itemData['data']['calculator_used']=='straight-cornice' || $itemData['data']['calculator_used']=='pinch-pleated'){
			echo "<tr>
			<td width=\"5%\" rowspan=\"5\" valign=\"middle\" align=\"right\" style=\"vertical-align:middle; line-height:50px; font-size:16px; font-weight:bold;\">".$itemData['data']['line_number']."</td>
			<td width=\"4%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['data']['qty']."</td>
			<td width=\"14%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">";
			if($itemData['metadata']['com-fabric'] == '1'){
				echo "COM ";
			}
			echo $itemData['fabricdata']['fabric_name']."</td>
			<td width=\"14%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['fabricdata']['color']."</td>
			<td width=\"8%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['mesh'].'&quot; '.$itemData['metadata']['mesh-color']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['width']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['length']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['total-yds']."</td>
			<td width=\"5%\" valign=\"top\" align=\"center\" style=\"font-size:7px;\">".$itemData['metadata']['yds-per-unit']."</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			<td width=\"5%\" style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td rowspan=\"4\" valign=\"top\" style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>
			<tr>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			<td style=\"font-size:6px;\">&nbsp;</td>
			</tr>";
		}
	}
	
	echo "</tbody>
	</table><br><br>
	<table width=\"60%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
	<thead>
	<tr bgcolor=\"#000000\">
	<th align=\"center\" width=\"6%\" style=\"font-size:6px; color:#FFFFFF;\">LINE:</th>
	<th align=\"center\" width=\"74%\" style=\"font-size:6px; color:#FFFFFF;\">REASONS FOR DIFFERENCES</th>
	<th align=\"center\" width=\"20%\" style=\"font-size:6px; color:#FFFFFF;\">SIGNED:</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td width=\"6%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"74%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"20%\" style=\"font-size:6px;\">&nbsp;</td>
	</tr>
	<tr>
	<td width=\"6%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"74%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"20%\" style=\"font-size:6px;\">&nbsp;</td>
	</tr>
	<tr>
	<td width=\"6%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"74%\" style=\"font-size:6px;\">&nbsp;</td>
	<td width=\"20%\" style=\"font-size:6px;\">&nbsp;</td>
	</tr>
	</tbody>
	</table>";
	
	$usedTemplates++;
}


//exit;
?>