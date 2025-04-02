<?php

if($types != 'all'){
	$types=explode("_",$types);
}else{
	$types=array('cc','bs','wt','track','catchall','swtcornice','swtdraperies','swtvalance','swtmisc');
}

$totalsheets=count($types);

$usedLineIDs=array();



$globalNotesOutput='';

if(count($globalNotes) >0){
    /* PPSASCRUM-336: start */
	$globalNotesOutput .= "<b>GLOBAL NOTES:</b><br/><table  style=\"background:#f8f8f8 !important; width:100% !important;\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000066\">";
	/* PPSASCRUM-336: end */

	foreach($globalNotes as $noteRow){
	    /* PPSASCRUM-336: start */
		$globalNotesOutput .= "<tr><td width=\"10%\" align=\"left\" valign=\"top\" style=\"font-size:12px;\">";
		/* PPSASCRUM-336: end */
		

		$globalNotesOutput .= "<b>".$allUsers[$noteRow['user_id']]['first_name']." ".substr($allUsers[$noteRow['user_id']]['last_name'],0,1).":</b></td>
		<td style=\"font-size:12px;\" valign=\"top\" align=\"left\" width=\"10%\">";
		if($noteRow['appear_on_pdf'] == '1'){
			$globalNotesOutput .= "<em>[PUBLIC]</em>";
		}else{
			$globalNotesOutput .= "<em>[INTERNAL]</em>";
		}
		$globalNotesOutput .= "</td>
		<td style=\"font-size:12px; line-height: 20px;\" valign=\"top\" align=\"left\" width=\"80%\">";
				
		$globalNotesOutput .= nl2br($noteRow['note_text']);
		$globalNotesOutput .= "</td></tr>";
	}

	$globalNotesOutput .= "</table>";
}










if(isset($_GET['debug'])){
	print_r($quoteItems);exit;
}


if(in_array('cc',$types)){
//CUBICLES BEGIN
	$lastparent=0;


	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"65%\" valign=\"top\" align=\"left\">
	<br>";
	echo "<table width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">FROM:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($customerData['company_name'])."</span></td>
	</tr>
	<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">PURCHASE ORDER</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">SHIP TO:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);

	if(strlen(trim($orderData['shipping_address_2'])) > 0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode'])."<Br></span></td>
	</tr>";
	
	if(strlen(trim($orderData['attention'])) > 0){
		echo "<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">ATTN:</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['attention'])."</span></td>
		</tr>";
	}

	echo "</table>";


	echo "</td>
	<td width=\"35%\" valign=\"top\" align=\"right\">
	<Br><Br>
	<b>CUBICLE CURTAIN WORK ORDER</b>

	</td>
	</tr>
	</table>
	<br>";

	if(count($globalNotes) > 0){
		echo "<b>GLOBAL NOTES BELOW</b>";
	}
	echo "<br>
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#555555\">
	<thead>
	<tr bgcolor=\"#111111\">
	<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WO LINE</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>SO LINE</b></span></th>
		<th width=\"5%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LOCATION</b></span></th>
		<th width=\"3%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>QTY</b></span></th>
		<th width=\"13%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FABRIC</b></span></th>
		<th width=\"13%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>COLOR</b></span></th>
		<th width=\"10%\" colspan=\"3\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FINISHED MESH</b></span></th>
		<th width=\"12%\" colspan=\"3\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>DIMENSIONS</b></span></th>
		<th width=\"38%\" colspan=\"11\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WIDTHS</b></span></th>

	</tr>
	<tr bgcolor=\"#111111\">

		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>TYPE</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>SIZE</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>COLOR</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FINISH<br>WIDTH</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>CUT<br>WIDTH</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FINISH<br>LENGTH</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>EACH</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>TOTAL</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FULL</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>1/2</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>1/4</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>1/3</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>CUT<br>LENGTH</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>VERTICAL<br>REPEAT</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px; background-color:#333333;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FABRIC</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>ACTUAL YARDS</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>ACTUAL LF</b></span></th>
	</tr>
	</thead>
	<tbody>";

	$usedLines=array();
	$lineRowTotals=array();
	$COMFabricsTotals=array();
	foreach($quoteItems as $itemid => $item){
		if(isset($lineRowTotals[$item['line_number']]) && is_numeric($lineRowTotals[$item['line_number']])){
			$lineRowTotals[$item['line_number']]++;
		}else{
			$lineRowTotals[$item['line_number']]=1;
		}
	}

	$metaout='';

	$i=1;
	

	foreach($quoteItems as $itemid => $item){
		//print_r($item);
		$metaout .= nl2br(print_r($item,1));
		
		if(!in_array($itemid,$usedLineIDs)){
			
			if($item['product_type'] == 'cubicle_curtains' || ($item['product_type'] == 'calculator' && $item['metadata']['calculator-used'] == 'cubicle-curtain') || $item['product_type'] == 'newcatchall-cubicle'){

				if($item['parent_line'] == 0){
					$lastparent=$item['primarykey'];
				}

				if($i % 2 == 0){
					$bgcolor='#e8e8e8';
					$highlightbg='#AAAAAA';
				}else{
					$bgcolor='#FFFFFF';
					$highlightbg='#CCCCCC';
				}
				echo "<tr bgcolor=\"".$bgcolor."\">";
				echo "<td width=\"4%\" style=\"text-align:center; font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['wo_line_number']."</span></td>
				    <td width=\"4%\" style=\"text-align:center; font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['line_number']."</span></td>";
				echo "<td width=\"5%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
    			if(isset($item['metadata']['location'])){
    			    echo $item['metadata']['location'];
    			}else{
    			    echo $item['room_number'];
    			}
    			echo "</span></td>";
				echo "<td width=\"3%\" style=\"font-size:7px;\"><span style=\"text-align:center; font-size:7px;color:#000000;\">".$item['qty']."</span></td>";
				echo "<td width=\"13%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";

				if($item['metadata']['railroaded'] == '1'){
					echo "RR ";
				}
				if($item['metadata']['com-fabric'] == '1'){
					echo "COM ";
				}
				echo $item['fabricdata']['fabric_name']."</span></td>";
				echo "<td width=\"13%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['fabricdata']['color']."</span></td>";


				if($item['metadata']['mesh-type']=='None'){
					echo "<td width=\"10%\" colspan=\"3\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">None</span></td>";
				}else{
					echo "<td width=\"3%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".str_replace(" Mesh","",$item['metadata']['mesh-type'])."</span></td>";

					echo "<td width=\"3%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
					if(isset($item['metadata']['finished-mesh'])){
						echo $item['metadata']['finished-mesh'];
					}elseif(isset($item['metadata']['mesh'])){
						echo (floatval($item['metadata']['mesh'])+floatval($allsettings['mesh_heading']));
					}
					echo "</span></td>";
					echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['metadata']['mesh-color']."</span></td>";

				}
				echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				
				if(isset($item['metadata']['expected-finish-width'])){
				    echo $item['metadata']['expected-finish-width'];
				}elseif(isset($item['metadata']['width'])){
				    echo $item['metadata']['width'];
				}else{
				    echo "&nbsp;";
				}
				echo "</span></td>";

				echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if($item['product_type'] == 'newcatchall-cubicle'){
				    echo $item['metadata']['cut-width'];
				}else{
				    if($item['metadata']['railroaded']=='1'){
					    echo '0';
				    }else{
					    echo $item['metadata']['width'];
				    }
				}
				echo "</span></td>";
				echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".$item['metadata']['length']."</span></td>";
				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";

				if($item['product_type'] == 'cubicle_curtains'){
					$eachstart=(floatval($item['metadata']['width']) / 72);
					$each=substr($eachstart, 0, ((strpos($eachstart, '.')+1)+2));
				}else{
					$each=substr($item['metadata']['widths-each'], 0, ((strpos($item['metadata']['widths-each'], '.')+1)+2));
				}

				echo $each;
				echo "</span></td>";
				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";

				if($item['product_type'] == 'cubicle_curtains'){
					$total=(floatval($eachstart) * floatval($item['qty']));
				}else{
					$total=(floatval($item['metadata']['widths-each']) * floatval($item['qty']));
				}
				$total=substr($total, 0, ((strpos($total, '.')+1)+2));
				echo $total;
				echo "</span></td>";
				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				//fulls
				$fullseach=floor($each);
				$fulls=($fullseach*floatval($item['qty']));
				echo $fulls;
				echo "</span></td>";
				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				//halfs
				$halfscalc=(floatval($each) - $fullseach);
				if($halfscalc >= 0.5 && strval($halfscalc) != '0.66'){
					$halfseach=1;
					$halfs=floatval($item['qty']);
				}else{
					$halfseach=0;
					$halfs=0;
				}
				echo $halfs;
				//echo " (halfscalc = ".$halfscalc.")";
				echo "</span></td>";
				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				//quarters
				$quarterscalc=(floatval($each) - $fullseach);
				if(strval($quarterscalc) == '0.25' || strval($quarterscalc) == '0.75'){
					$quarterseach=1;
					$quarters=floatval($item['qty']);
				}else{
					$quarterseach=0;
					$quarters=0;
				}
				echo $quarters;
				echo "</span></td>";
				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				//thirds
				$thirdscalc=(floatval($each) - $fullseach);
				if(strval($thirdscalc) == '0.33'){
					$thirdseach=1;
					$thirds=floatval($item['qty']);
				}elseif(strval($thirdscalc) == '0.66'){
					$thirdseach=2;
					$thirds=(2*floatval($item['qty']));
				}else{
					$thirdseach=0;
					$thirds=0;
				}
				echo $thirds;
				echo "</span></td>";

				echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if($item['product_type'] == 'cubicle_curtains'){
				    $meta_length = floatval($item['metadata']['length']);
				    $meta_mesh = floatval($item['metadata']['mesh']);
				    $inches_per_hem = floatval($allsettings['inches_per_hem']);
				    $inches_per_seam = floatval($allsettings['inches_per_seam']);
				    $inches_per_head = floatval($allsettings['inches_for_header']);
				    
					if($item['metadata']['mesh-type'] == 'None'){
						echo ($meta_length + $inches_per_hem + $inches_per_head);
					}else{
					   $vert_rpt = floatval($item['metadata']['vertical-repeat']);
					   $mesh_heading = floatval($allsettings['mesh_heading']);
                       $mesh_type_calc = (($meta_length - $meta_mesh - $mesh_heading) + $inches_per_hem + $inches_per_seam);
                       if($vert_rpt == 0) {
					    echo $mesh_type_calc;
					   } else {
					    echo (ceil($mesh_type_calc/$vert_rpt) * $vert_rpt);
					   }
					}
				}else{
					echo $item['metadata']['cl'];
				}
				echo "</span></td>";
				echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if($item['product_type'] == 'cubicle_curtains'){
					echo '0';
				}else{
					echo $item['metadata']['vertical-repeat'];
				}
				echo "</span></td>";
				echo "<td width=\"4%\" style=\"font-size:7px; background-color:".$highlightbg."; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if($item['product_type'] == 'cubicle_curtains' || $item['product_type'] == 'newcatchall-cubicle'){
					echo $item['fabricdata']['fabric_width'];
				}else{
					echo $item['metadata']['fabric-width'];
				}
				echo "</span></td>";
				echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".(floatval($item['metadata']['yds-per-unit']) * floatval($item['qty']))."</span></td>";
				echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".(floatval($item['metadata']['labor-billable'])*floatval($item['qty']))."</span></td>";
				echo "</tr>";


				if(count($item['notesdata']) >0){
					echo "<tr bgcolor=\"".$bgcolor."\">
						<!-- PPSASCRUM-409: start -->
						<!-- <td colspan=\"3\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"19\" valign=\"top\" align=\"left\" style=\"font-size:6px;\"> -->
						<td colspan=\"2\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"20\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">
						<!-- PPSASCRUM-409: end -->";
					$notenum=1;
					foreach($item['notesdata'] as $noteid => $note){
					    /* PPSASCRUM-409: start */
						echo nl2br($note['message']);
						/* PPSASCRUM-409: end */
						if($notenum < count($item['notesdata'])){
							echo "<hr>";
						}
						$notenum++;
					}
					echo "</td></tr>";
				}

				$i++;
			}elseif($item['product_type'] == "custom" && $item['parent_line'] > 0 && $item['parent_line'] == $lastparent){
				echo "<tr bgcolor=\"".$bgcolor."\">
				<td style=\"font-size:7px;\">".$item['line_number']."</td>
				<td style=\"font-size:7px;\">";
    			if(isset($item['metadata']['location'])){
    			    echo $item['metadata']['location'];
    			}else{
    			    echo $item['room_number'];
    			}
    			echo "</td>
				<td style=\"font-size:7px;\">".$item['qty']."</td>
				<td style=\"font-size:7px;\">".$item['fabricdata']['fabric_name']."</td>
				<td style=\"font-size:7px;\">".$item['fabricdata']['color']."</td>
				<td style=\"font-size:7px;\" colspan=\"5\">";
				if(isset($item['metadata']['line_item_title'])){
				    echo $item['metadata']['line_item_title'];
				}else{
				    echo $item['title'];
				}
				echo "</td>
				<td style=\"font-size:7px;\" colspan=\"12\">";
				if(isset($item['metadata']['description'])){
				    echo $item['metadata']['description'];
				}else{
				    echo $item['description'];
				}
				echo " (".(floatval($item['metadata']['yds-per-unit'])*floatval($item['qty']))." yards)</td>
				</tr>";

				if(count($item['notesdata']) >0){
					echo "<tr bgcolor=\"".$bgcolor."\">
						<!-- PPSASCRUM-409: start -->
						<!-- <td colspan=\"3\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"19\" valign=\"top\" align=\"left\" style=\"font-size:6px;\"> -->
						<td colspan=\"2\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"20\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">
						<!-- PPSASCRUM-409: end -->";
					$notenum=1;
					foreach($item['notesdata'] as $noteid => $note){
						/* PPSASCRUM-409: start */
						echo nl2br($note['message']);
						/* PPSASCRUM-409: end */
						if($notenum < count($item['notesdata'])){
							echo "<hr>";
						}
						$notenum++;
					}
					echo "</td></tr>";
				}
			}


			
			
			$usedLineIDs[]=$itemid;
		}
		
	
		
		//loop through again just searching for other "parent" lines with the same line number as this outer loop's line number
		foreach($quoteItems as $subitemid => $subitem){
			
			if($subitem['line_number'] == $item['line_number'] && $subitem['parent_line'] == 0 && $subitem['product_type'] == 'custom'){
				if(!in_array($subitemid,$usedLineIDs)){
					//this seems to be a child line.  let's treat it as such
					echo "<tr bgcolor=\"".$bgcolor."\">
					<td style=\"font-size:7px;\">".$subitem['line_number']."</td>
					<td style=\"font-size:7px;\">";
        			if(isset($subitem['metadata']['location'])){
        			    echo $subitem['metadata']['location'];
        			}else{
        			    echo $subitem['room_number'];
        			}
        			echo "</td>
					<td style=\"font-size:7px;\">".$subitem['qty']."</td>
					<td style=\"font-size:7px;\">".$subitem['fabricdata']['fabric_name']."</td>
					<td style=\"font-size:7px;\">".$subitem['fabricdata']['color']."</td>
					<td style=\"font-size:7px;\" colspan=\"5\">";
    				if(isset($subitem['metadata']['line_item_title'])){
    				    echo $subitem['metadata']['line_item_title'];
    				}else{
    				    echo $subitem['title'];
    				}
    				echo "</td>
					<td style=\"font-size:7px;\" colspan=\"12\">";
    				if(isset($subitem['metadata']['description'])){
    				    echo $subitem['metadata']['description'];
    				}else{
    				    echo $subitem['description'];
    				}
    				echo " (".(floatval($subitem['metadata']['yds-per-unit'])*floatval($subitem['qty']))." yards)</td>
					</tr>";

					$usedLineIDs[]=$subitemid;
				}

			}
		}
		
		
		
		
		
	}
echo "</tbody></table>";

echo $globalNotesOutput;

//CUBICLES END
}



/*if(in_array('cc',$types) && (in_array('bs',$types) || in_array('wt',$types) || in_array('track',$types) || in_array('catchall',$types) ) ){
	echo "<br pagebreak=\"true\"/>";
}*/

if(in_array('bs',$types) && count($types) > 1){
	echo "<br pagebreak=\"true\"/>";
}

if(in_array('bs',$types)){
//BEDSPREADS BEGIN

$lastparent=0;

	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"65%\" valign=\"top\" align=\"left\">
	<br>";
	echo "<table width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">FROM:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($customerData['company_name'])."</span></td>
	</tr>
	<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">PURCHASE ORDER</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">SHIP TO:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);

	if(strlen(trim($orderData['shipping_address_2'])) > 0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode'])."<Br></span></td>
	</tr>";
	
	if(strlen(trim($orderData['attention'])) > 0){
		echo "<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">ATTN:</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['attention'])."</span></td>
		</tr>";
	}

	echo "</table>";


	echo "</td>
	<td width=\"35%\" valign=\"top\" align=\"right\">
	<Br><Br>
	<B>BEDDING WORK ORDER</B>

	</td>
	</tr>
	</table>
	<br>";

	if(count($globalNotes) > 0){
		echo "<b>GLOBAL NOTES BELOW</b>";
	}
	echo "<br>

	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
	<thead>
	<tr bgcolor=\"#444444\">
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WO LINE</b></span></th>
			<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>SO LINE</b></span></th>
		<th width=\"5%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LOCATION</b></span></th>
		<th width=\"3%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>QTY</b></span></th>
		<th width=\"5%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FABRIC</b></span></th>
		<th width=\"5%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>COLOR</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>STYLE</b></span></th>
		<th width=\"5%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>QUILT</b></span></th>
		<th width=\"3%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FILL</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>BACKING</b></span></th>
		<th width=\"12%\" colspan=\"4\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FINISHED SIZES</b></span></th>



		<th width=\"3%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>W EA</b></span></th>

		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>W TOT</b></span></th>

		<th width=\"6%\" colspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>TOP WIDTHS</b></span></th>

		<th width=\"6%\" colspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>DROP WIDTHS</b></span></th>

		<th width=\"9%\" colspan=\"3\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>TOP CUT SIZES</b></span></th>

		<th width=\"9%\" colspan=\"3\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>DROP CUT SIZES</b></span></th>

		<th width=\"2%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>RPT</b></span></th>
		<th width=\"2%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FAB W</b></span></th>
		<th width=\"3%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>YDS<br>EACH</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>YDS<br>TOTAL</b></span></th>
	</tr>
	<tr bgcolor=\"#444444\">

		<!--Finish Sizes subcols-->
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>W</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>L</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>MAT</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>DROP</b></span></th>


		<!--Top Widths subcols-->
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>EA</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>CL</b></span></th>

		<!--Drop Widths subcols-->
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>EA</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>CL</b></span></th>

		<!--Top Cut sizes subcols-->
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>EA</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>W</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>L</b></span></th>
	

		<!--Drop Cut Sizes subcols-->
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>EA</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>W</b></span></th>
		<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>L</b></span></th>
	</tr>
	</thead>
	<tbody>";

	$usedLines=array();
	$lineRowTotals=array();
	$COMFabricsTotals=array();

	foreach($quoteItems as $item){
		if(isset($lineRowTotals[$item['line_number']]) && is_numeric($lineRowTotals[$item['line_number']])){
			$lineRowTotals[$item['line_number']]++;
		}else{
			$lineRowTotals[$item['line_number']]=1;
		}
	}

	$metaout='';

	$i=1;
	foreach($quoteItems as $item){
		$metaout .= nl2br(print_r($item,1));
		if($item['product_type'] == 'bedspreads' || ($item['product_type'] == 'calculator' && ($item['metadata']['calculator-used'] == 'bedspread' || $item['metadata']['calculator-used'] == 'bedspread-manual')) || $item['product_type'] == 'newcatchall-bedding'){
			if($i % 2 == 0){
				$bgcolor='#e8e8e8';
				$highlightbg='#AAAAAA';
			}else{
				$bgcolor='#FFFFFF';
				$highlightbg='#CCCCCC';
			}

			if($item['parent_line'] == 0){
				$lastparent=$item['primarykey'];
			}

			echo "<tr bgcolor=\"".$bgcolor."\">";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\"><span style=\"font-size:7px;color:#000000;\">".$item['wo_line_number']."</span></td>
			<td width=\"4%\" style=\"font-size:7px; text-align:center;\"><span style=\"font-size:7px;color:#000000;\">".$item['line_number']."</span></td>";
			echo "<td width=\"5%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['location'])){
			    echo $item['metadata']['location'];
			}else{
			    echo $item['room_number'];
			}
			echo "</span></td>";
			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\"><span style=\"font-size:7px;color:#000000;\">".$item['qty']."</span></td>";
			echo "<td width=\"5%\" style=\"font-size:7px; text-align:center;\"><span style=\"font-size:7px;color:#000000;\">";

			if($item['metadata']['railroaded'] == '1'){
				echo "RR ";
			}
			if($item['metadata']['com-fabric'] == '1'){
				echo "COM ";
			}
			echo $item['fabricdata']['fabric_name']."</span></td>";
			echo "<td width=\"5%\" style=\"font-size:7px; text-align:center;\"><span style=\"font-size:7px;color:#000000;\">".$item['fabricdata']['color']."</span></td>";


			

			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			$styleval=explode(" (",$item['metadata']['style']);
			echo $styleval[0];
			echo "</span></td>";



			if($item['metadata']['quilted'] == '1'){
				echo "<td width=\"5%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
				echo $item['metadata']['quilting-pattern'];
				echo "</span></td>";

				echo "<td width=\"3%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
				echo "6oz";
				echo "</span></td>";


				echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
				echo $item['fabricdata']['bs_backing_material'];
				echo "</span></td>";
			}else{
				echo "<td width=\"12%\" colspan=\"3\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">None</span></td>";
			}
			
			


			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			echo $item['metadata']['width'];
			echo "</span></td>";
			
			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".$item['metadata']['length']."</span></td>";



			//mattress width
			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".$item['metadata']['custom-top-width-mattress-w']."</span></td>";


			//finished drop
			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if($item['product_type'] == 'bedspreads'){
				echo ((floatval($item['metadata']['width'])-floatval($item['metadata']['custom-top-width-mattress-w']))/2);
			}else{
				echo $item['metadata']['assumed-drop-width'];
			}
			echo "</span></td>";


			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if($item['product_type'] == 'bedspreads'){
				$each=floatval($item['metadata']['layout']);
			}else{
				$each=floatval(substr($item['metadata']['layout'], 0, ((strpos($item['metadata']['layout'], '.')+1)+2)));
			}
			echo $each;
			echo "</span></td>";



			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if($item['product_type'] == 'bedspreads'){
				echo ceil(($each*floatval($item['qty'])));
			}else{
				echo $item['metadata']['total-widths'];
			}
			echo "</span></td>";


			$clsplit=explode(' / ',$item['metadata']['cl']);

			//top widths each
			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if($item['product_type'] == 'newcatchall-bedding'){
			    echo "&nbsp;";
			}else{
			    echo $item['metadata']['qty'];
			}
			echo "</span></td>";

			//top widths length
			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if($item['product_type'] == 'calculator'){
				echo str_replace("CL (tops)","",$clsplit[0]);
			}elseif($item['product_type'] == 'newcatchall-bedding'){
			    echo "&nbsp;";
			}else{
				echo $item['metadata']['top-widths'];
			}
			echo "</span></td>";

			//drop widths each
			if($item['product_type'] == 'newcatchall-bedding'){
			    echo "<td width=\"6%\" colspan=\"2\" style=\"font-size:7px; text-align:center;\" align=\"center\">&nbsp;</td>";
			}else{
    			if($item['metadata']['railroaded'] == '1'){
    				echo "<td width=\"6%\" colspan=\"2\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">N/A</span></td>";
    			}else{
    				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
    				echo (ceil( ($each * floatval($item['metadata']['qty']) ) ) - floatval($item['metadata']['qty']));
    				echo "</span></td>";
    
    
    				//drop widths length
    				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
    				if($item['product_type'] == 'calculator'){
    					echo str_replace("CL (drops)","",$clsplit[1]);
    				}else{
    					echo $item['metadata']['drop-widths'];
    				}
    				echo "</span></td>";
    			}
			}



			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if($item['product_type'] == 'newcatchall-bedding'){
			    echo "&nbsp;";
			}else{
			    echo $item['qty'];
			}
			echo "</span></td>";





			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if($item['product_type'] == 'newcatchall-bedding'){
			    echo "&nbsp;";
			}else{
			    if($item['metadata']['railroaded'] == '1'){
				    echo (floatval($item['metadata']['length']) + (floatval($item['metadata']['extra-inches-seam-hems']) * 2));
			    }else{
				    echo $item['metadata']['top-cut'];
			    }
			}
			echo "</span></td>";


			
			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if($item['product_type'] == 'newcatchall-bedding'){
			    echo "&nbsp;";
			}else{
			    if($item['product_type'] == 'calculator'){
				    echo str_replace("CL (tops)","",$clsplit[0]);
			    }else{
				    echo $item['metadata']['top-widths'];
			    }
			}
			echo "</span></td>";




			
			


            if($item['product_type'] == 'newcatchall-bedding'){
			    echo "<td width=\"9%\" colspan=\"3\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">&nbsp;</span></td>";
			}else{
    			if($item['metadata']['railroaded'] == '1'){
    				echo "<td width=\"9%\" colspan=\"3\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">N/A</span></td>";
    			}else{
    				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
    				echo (floatval($item['qty'])*2);
    				echo "</span></td>";
    
    				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;";
    				if($item['metadata']['railroaded']=='1'){
    					echo "background-color:".$highlightbg.";";
    				}
    				echo "\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
    				if($item['metadata']['railroaded']=='1'){
    					echo "N/A";
    				}else{
    					if($item['product_type'] == 'bedspreads'){
    						echo $item['metadata']['drop-cut'];
    					}else{
    						echo $item['metadata']['drop'];
    					}
    				}
    				echo "</span></td>";
    
    
    				echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
    				if($item['product_type'] == 'calculator'){
    					echo str_replace("CL (drops)","",$clsplit[1]);
    				}else{
    					echo $item['metadata']['drop-widths'];
    				}
    				echo "</span></td>";
    
    			}
			}


			echo "<td width=\"2%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			echo $item['metadata']['vertical-repeat'];
			echo "</span></td>";


			echo "<td width=\"2%\" style=\"font-size:6px; background-color:".$highlightbg."; text-align:center;\" align=\"center\"><span style=\"font-size:6px;color:#000000;\">";
			if($item['product_type'] == 'newcatchall-bedding'){
			    echo $item['fabricdata']['fabric_width'];
			}else{
			    echo $item['metadata']['fabric-width'];
			}
			
			echo "</span></td>";
		


			echo "<td width=\"3%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			echo $item['metadata']['yds-per-unit'];
			echo "</span></td>";

			echo "<td width=\"4%\" style=\"font-size:6px; text-align:center;\" align=\"center\"><span style=\"font-size:6px;color:#000000;\">";
			if($item['product_type'] == 'calculator'){
				echo $item['metadata']['total-yds'];
			}else{
				echo (floatval($item['metadata']['yds-per-unit']) * floatval($item['metadata']['qty']));
			}
			echo "</span></td>";



			echo "</tr>";

			if(count($item['notesdata']) >0){
				echo "<tr>
					<!-- PPSASCRUM-409: start -->
					<!-- <td colspan=\"4\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"25\" valign=\"top\" align=\"left\" style=\"font-size:6px;\"> -->
					<td colspan=\"2\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"27\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">
					<!-- PPSASCRUM-409: end -->";
				$notenum=1;
				foreach($item['notesdata'] as $note){
					/* PPSASCRUM-409: start */
					echo nl2br($note['message']);
					/* PPSASCRUM-409: end */
					if($notenum < count($item['notesdata'])){
						echo "<hr>";
					}
					$notenum++;
				}
				echo "</td></tr>";
			}


			$i++;
		}elseif($item['product_type'] == 'custom' && $item['parent_line'] > 0 && $item['parent_line'] == $lastparent){
			echo "<tr bgcolor=\"".$bgcolor."\">
			<td style=\"font-size:7px; text-align:center\">".$item['line_number']."</td>
			<td style=\"font-size:7px; text-align:center\">";
			if(isset($item['metadata']['location'])){
			    echo $item['metadata']['location'];
			}else{
			    echo $item['room_number'];
			}
			echo "</td>
			<td style=\"font-size:7px; text-align:center\">".$item['qty']."</td>
			<td style=\"font-size:7px; text-align:center\">".$item['fabricdata']['fabric_name']."</td>
			<td style=\"font-size:7px; text-align:center\">".$item['fabricdata']['color']."</td>
			<td style=\"font-size:7px;\" colspan=\"4\">";
			if(isset($item['metadata']['line_item_title'])){
			    echo $item['metadata']['line_item_title'];
			}else{
			    echo $item['title'];
			}
			echo "</td>
			<td style=\"font-size:7px;\" colspan=\"20\">";
				if(isset($item['metadata']['description'])){
				    echo $item['metadata']['description'];
				}else{
				    echo $item['description'];
				}
				echo " (".(floatval($item['metadata']['yds-per-unit'])*floatval($item['qty']))." yards)</td>
			</tr>";

			if(count($item['notesdata']) >0){
				echo "<tr>
					<!-- PPSASCRUM-409: start -->
					<!-- <td colspan=\"4\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"25\" valign=\"top\" align=\"left\" style=\"font-size:6px;\"> -->
					<td colspan=\"2\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"27\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">
					<!-- PPSASCRUM-409: end -->";
				$notenum=1;
				foreach($item['notesdata'] as $note){
					/* PPSASCRUM-409: start */
					echo nl2br($note['message']);
					/* PPSASCRUM-409: end */
					if($notenum < count($item['notesdata'])){
						echo "<hr>";
					}
					$notenum++;
				}
				echo "</td></tr>";
			}
		}
		
	}
echo "</tbody></table>";

echo $globalNotesOutput;

//BEDSPREADS END
}



/*if(in_array('bs',$types) && ( in_array('swtcornice',$types) || in_array('swtvalance',$types) || in_array('swtdraperies',$types) || in_array('swtmisc',$types)  || in_array('track',$types) )){
	echo "<br pagebreak=\"true\"/>";
}*/

if(in_array('swtcornice',$types) && count($types) > 1){
	echo "<br pagebreak=\"true\"/>";
}





if(in_array('swtcornice',$types)){
//SWT CORNICES

	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"65%\" valign=\"top\" align=\"left\">
	<br>";
	echo "<table width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">FROM:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($customerData['company_name'])."</span></td>
	</tr>
	<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">PURCHASE ORDER</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">SHIP TO:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);

	if(strlen(trim($orderData['shipping_address_2'])) > 0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode'])."<Br></span></td>
	</tr>";
	
	if(strlen(trim($orderData['attention'])) > 0){
		echo "<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">ATTN:</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['attention'])."</span></td>
		</tr>";
	}

	echo "</table>";


	echo "</td>
	<td width=\"35%\" valign=\"top\" align=\"right\">
	<Br><Br>
	<b>SWT CORNICES WORK ORDER</b>

	</td>
	</tr>
	</table>
	<br>";

	if(count($globalNotes) > 0){
		echo "<b>GLOBAL NOTES BELOW</b>";
	}
	echo "<br>";

$lastparent=0;

	//build an array of all PARENTS
	$parents=array();
	foreach($quoteItems as $item){
		if(($item['product_type'] == 'calculator' && $item['metadata']['calculator-used'] == 'straight-cornice') ||	$item['product_type'] == 'newcatchall-cornice'){
			if($item['parent_line'] == '0' && !in_array($item['line_number'],$parents)){
				$parents[]=$item['line_number'];
			}
		}
	}


	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#555555\">
	<thead>
	<tr bgcolor=\"#111111\">
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WO LINE</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>SO LINE</b></span></th>
		<th width=\"6%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LOCATION</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>QTY</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>PRODUCT</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FABRIC</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>COLOR</b></span></th>
		<th width=\"6%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LINING</b></span></th>
		<th width=\"20%\" colspan=\"5\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>DIMENSIONS</b></span></th>
		<th width=\"5%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FULLNESS</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WIDTHS</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LF</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>VRPT</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FAB<br>WIDTH</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>YARDS</b></span></th>

	</tr>
	<tr bgcolor=\"#111111\">

	<!--DIMENSIONS COLS START-->
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FACE / FW</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>ROD W</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FL SHORT</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FL LONG</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>RET</b></span></th>
	<!--DIMENSIONS COLS END-->

	</tr>
	</thead>
	<tbody>";

	$usedLines=array();
	$lineRowTotals=array();
	$COMFabricsTotals=array();

	foreach($quoteItems as $item){
		if(isset($lineRowTotals[$item['line_number']]) && is_numeric($lineRowTotals[$item['line_number']])){
			$lineRowTotals[$item['line_number']]++;
		}else{
			$lineRowTotals[$item['line_number']]=1;
		}
	}

	$metaout='';

	

	$i=1;
	foreach($quoteItems as $item){
		$metaout .= nl2br(print_r($item,1));
		if(($item['product_type'] == 'calculator' && $item['metadata']['calculator-used'] == 'straight-cornice') ||	$item['product_type'] == 'newcatchall-cornice'){

			if($item['parent_line'] == 0){
				$lastparent=$item['primarykey'];
			}

			if($i % 2 == 0){
				$bgcolor='#e8e8e8';
				$highlightbg='#AAAAAA';
			}else{
				$bgcolor='#FFFFFF';
				$highlightbg='#CCCCCC';
			}

			$childofline=$item['id'];

			echo "<tr bgcolor=\"".$bgcolor."\">";
			echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['wo_line_number']."</span></td><td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['line_number']."</span></td>";
			echo "<td width=\"6%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['location'])){
			    echo $item['metadata']['location'];
			}else{
			    echo $item['room_number'];
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['qty']."</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			
			if(isset($item['metadata']['line_item_title'])){
			    echo $item['metadata']['line_item_title'];
			}else{
				
    			//product type
    			switch($item['product_type']){
    			    case "newcatchall-cornice":
    			        echo $item['title'];
    			    break;
    				case "calculator":
    					switch($item['metadata']['calculator-used']){
    						case "straight-cornice":
    							echo $item['metadata']['cornice-type']." Cornice";
    						break;
    					}
    				break;
    			}
			}
			/* PPSASCRUM-367: start */
			if($item['metadata']['hinged'] == '1'){
				echo"<br><b>Hinges</b>= Yes (".$item['metadata']['hingecount']."), ";
			}else{
				echo "<br><b>Hinges</b>= No (0), ";
			}
			/* PPSASCRUM-367: end */
			echo "</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";

			if($item['metadata']['railroaded'] == '1'){
				echo "RR ";
			}
			if($item['metadata']['com-fabric'] == '1'){
				echo "COM ";
			}
			echo $item['fabricdata']['fabric_name']."</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['fabricdata']['color']."</span></td>";

			echo "<td width=\"6%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			echo $alllinings[$item['metadata']['linings_id']];
			echo "</span></td>";

			echo "<!--DIMENSIONS COLS START-->";
			//start FACE/FW
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			
			if(isset($item['metadata']['face'])){
				echo $item['metadata']['face'];
			}elseif(isset($item['metadata']['fw'])){
				echo $item['metadata']['fw'];
			}elseif(isset($item['metadata']['width'])){
				echo $item['metadata']['width'];
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			//end FACE/FW

			//start ROD WIDTH
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['rod-width'])){
				echo $item['metadata']['rod-width'];
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			//end ROD WIDTH

			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['fl-short'])){
				echo $item['metadata']['fl-short'];
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";

			switch($item['product_type']){
				case "calculator":
					switch($item['metadata']['calculator-used']){
						case "straight-cornice":
							echo $item['metadata']['height'];
						break;
						case "box-pleated":
							echo $item['metadata']['height'];
						break;
						case "pinch-pleated":
					    /* PPSASCRUM-56: start */
						case "new-pinch-pleated":
						/* PPSASCRUM-56: end */
						/* PPSASCRUM-305: start */
            			case "ripplefold-drapery":
            		    /* PPSASCRUM-305: end */
            		    /* PPSASCRUM-384: start */
                        case 'accordiafold-drapery':
                        /* PPSASCRUM-384: end */
							echo $item['metadata']['length'];
						break;
					}
				break;
				case "newcatchall-valance":
				    echo $item['metadata']['height'];
				break;
				case "newcatchall-drapery":
				    echo $item['metadata']['length'];
				break;
				case "newcatchall-cornice":
				    echo $item['metadata']['height'];
				break;
				case "newcatchall-swtmisc":
				    echo $item['metadata']['height'];
				break;
				case "window_treatments":
					echo $item['metadata']['length'];
				break;
			}

			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			
		    /* PPSASCRUM-56: start */
		    /* PPSASCRUM-305: start */
// 			if($item['metadata']['wttype'] == 'Pinch Pleated Drapery' || $item['metadata']['calculator-used'] == 'pinch-pleated' || $item['product_type'] == 'newcatchall-drapery' || $item['metadata']['calculator-used'] == 'new-pinch-pleated'){
		    /* PPSASCRUM-384: start */
		    if($item['metadata']['wttype'] == 'Pinch Pleated Drapery' || $item['metadata']['calculator-used'] == 'pinch-pleated' || $item['product_type'] == 'newcatchall-drapery' || $item['metadata']['calculator-used'] == 'new-pinch-pleated' || 
		       $item['metadata']['calculator-used'] == "ripplefold-drapery" || $item['metadata']['calculator-used'] == "accordiafold-drapery"){
            /* PPSASCRUM-384: end */
			/* PPSASCRUM-305: end */
			/* PPSASCRUM-56: end */
				echo $item['metadata']['default-return'];
			}else{
				echo $item['metadata']['return'];
			}
			
			echo "</span></td>";
			echo "<!--DIMENSIONS COLS END-->";

			echo "<td width=\"5%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['fullness'])){
				echo $item['metadata']['fullness']."X";
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['labor-widths'])){
				echo $item['metadata']['labor-widths'];
			}elseif(isset($item['metadata']['labor-billable-widths'])){
			    if(substr($item['metadata']['labor-billable-widths'],-3) == '.00'){
			        echo round($item['metadata']['labor-billable-widths']);
			    }else{
			        echo $item['metadata']['labor-billable-widths'];
			    }
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".(floatval($item['metadata']['labor-billable'])*floatval($item['qty']))."</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if(isset($item['metadata']['vertical-repeat'])){
					echo $item['metadata']['vertical-repeat'];
				}elseif(isset($item['metadata']['vert-repeat'])){
					echo $item['metadata']['vert-repeat'];
				}else{
					if(isset($item['fabricdata']['vertical_repeat'])){
						echo $item['fabricdata']['vertical_repeat'];
					}
				}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center; background-color:".$highlightbg.";\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if(isset($item['metadata']['fab-width'])){
					echo $item['metadata']['fab-width'];
				}elseif(isset($item['metadata']['fabric-width'])){
					echo $item['metadata']['fabric-width'];
				}else{
					if(isset($item['fabricdata']['fabric_width'])){
						echo $item['fabricdata']['fabric_width'];
					}
				}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".$item['metadata']['total-yds']."</span></td>";
			echo "</tr>";


			//look for child items of this line
			$childrow=1;
			foreach($quoteItems as $childitem){

				if($childitem['parent_line'] == $item['primarykey']){
					echo "<tr bgcolor=\"".$bgcolor."\">";
					echo "
					<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$childitem['wo_line_number']."</span></td><td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$childitem['line_number']."</span></td>";
					echo "<td width=\"6%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
        			if(isset($childitem['metadata']['location'])){
        			    echo $childitem['metadata']['location'];
        			}else{
        			    echo $childitem['room_number'];
        			}
        			echo "</span></td>";
					echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$childitem['qty']."</span></td>";
					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
    				if(isset($childitem['metadata']['line_item_title'])){
    				    echo $childitem['metadata']['line_item_title'];
    				}else{
    				    echo $childitem['title'];
    				}
    				echo "</span></td>";
					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";

					if($childitem['metadata']['fabrictype'] == 'typein'){
						echo $childitem['metadata']['fabric_name'];
					}else{
						echo $childitem['fabricdata']['fabric_name'];
					}
					echo "</span></td>";

					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
					if($childitem['metadata']['fabrictype']=='typein'){
						echo $childitem['metadata']['fabric_color'];
					}else{
						echo $childitem['fabricdata']['color'];
					}
					echo "</span></td>";


					echo "<td width=\"47%\" colspan=\"11\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
    				if(isset($childitem['metadata']['description'])){
    				    echo $childitem['metadata']['description'];
    				}else{
    				    echo $childitem['description'];
    				}
				    echo "</span></td>";

					echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
					echo (floatval($childitem['metadata']['yds-per-unit'])*floatval($childitem['qty']));
					echo "</span></td>";

					echo "</tr>";
					$childrow++;
				}

			}



			if(count($item['notesdata']) >0){
				echo "<tr>
					<!-- PPSASCRUM-409: start -->
					<!-- <td colspan=\"4\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"14\" valign=\"top\" align=\"left\" style=\"font-size:6px;\"> -->
					<td colspan=\"2\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"16\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">
					<!-- PPSASCRUM-409: end -->";
				$notenum=1;
				foreach($item['notesdata'] as $note){
					/* PPSASCRUM-409: start */
					echo nl2br($note['message']);
					/* PPSASCRUM-409: end */
					if($notenum < count($item['notesdata'])){
						echo "<hr>";
					}
					$notenum++;
				}
				echo "</td></tr>";
			}


			if($item['parent_line'] == '0'){
				$i++;
			}
		}


	}
    echo "</tbody></table>";
    echo $globalNotesOutput;
    //SWT CORNICES END
}




/*if(in_array('swtcornice',$types) && ( in_array('swtvalance',$types) || in_array('swtdraperies',$types) || in_array('swtmisc',$types)  || in_array('track',$types) )){
	echo "<br pagebreak=\"true\"/>";
}*/

if( ( in_array('swtvalance',$types) && count($types) > 1)){
	echo "<br pagebreak=\"true\"/>";
}






if(in_array('swtvalance',$types)){
//SWT VALANCES BEGIN

	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"65%\" valign=\"top\" align=\"left\">
	<br>";
	echo "<table width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">FROM:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($customerData['company_name'])."</span></td>
	</tr>
	<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">PURCHASE ORDER</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">SHIP TO:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);

	if(strlen(trim($orderData['shipping_address_2'])) > 0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode'])."<Br></span></td>
	</tr>";
	
	if(strlen(trim($orderData['attention'])) > 0){
		echo "<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">ATTN:</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['attention'])."</span></td>
		</tr>";
	}

	echo "</table>";


	echo "</td>
	<td width=\"35%\" valign=\"top\" align=\"right\">
	<Br><Br>
	<b>SWT VALANCES WORK ORDER</b>

	</td>
	</tr>
	</table>
	<br>";

	if(count($globalNotes) > 0){
		echo "<b>GLOBAL NOTES BELOW</b>";
	}
	echo "<br>";

$lastparent=0;

	//build an array of all PARENTS
	$parents=array();
	foreach($quoteItems as $item){
		if(($item['product_type'] == 'calculator' && $item['metadata']['calculator-used'] == 'box-pleated') || $item['product_type'] == 'newcatchall-valance'){
			if($item['parent_line'] == '0' && !in_array($item['line_number'],$parents)){
				$parents[]=$item['line_number'];
			}
		}
	}


	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#555555\">
	<thead>
	<tr bgcolor=\"#111111\">
	<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WO LINE</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>SO LINE</b></span></th>
		<th width=\"6%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LOCATION</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>QTY</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>PRODUCT</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FABRIC</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>COLOR</b></span></th>
		<th width=\"6%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LINING</b></span></th>
		<th width=\"20%\" colspan=\"5\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>DIMENSIONS</b></span></th>
		<th width=\"5%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FULLNESS</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WIDTHS</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LF</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>VRPT</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FAB<br>WIDTH</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>YARDS</b></span></th>

	</tr>
	<tr bgcolor=\"#111111\">

	<!--DIMENSIONS COLS START-->
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FACE / FW</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>ROD W</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FL SHORT</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FL LONG</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>RET</b></span></th>
	<!--DIMENSIONS COLS END-->

	</tr>
	</thead>
	<tbody>";

	$usedLines=array();
	$lineRowTotals=array();
	$COMFabricsTotals=array();

	foreach($quoteItems as $item){
		if(isset($lineRowTotals[$item['line_number']]) && is_numeric($lineRowTotals[$item['line_number']])){
			$lineRowTotals[$item['line_number']]++;
		}else{
			$lineRowTotals[$item['line_number']]=1;
		}
	}

	$metaout='';

	

	$i=1;
	foreach($quoteItems as $item){
		$metaout .= nl2br(print_r($item,1));
		if(($item['product_type'] == 'calculator' && $item['metadata']['calculator-used'] == 'box-pleated') || $item['product_type'] == 'newcatchall-valance'){

			if($item['parent_line'] == 0){
				$lastparent=$item['primarykey'];
			}

			if($i % 2 == 0){
				$bgcolor='#e8e8e8';
				$highlightbg='#AAAAAA';
			}else{
				$bgcolor='#FFFFFF';
				$highlightbg='#CCCCCC';
			}

			$childofline=$item['id'];

			echo "<tr bgcolor=\"".$bgcolor."\">";
			echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['wo_line_number']."</span></td>
			    <td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['line_number']."</span></td>";
			echo "<td width=\"6%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['location'])){
			    echo $item['metadata']['location'];
			}else{
			    echo $item['room_number'];
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['qty']."</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			//product type
			
			if(isset($item['metadata']['line_item_title'])){
			    echo $item['metadata']['line_item_title'];
			}else{
    			switch($item['product_type']){
    			    case "newcatchall-valance":
    			        echo $item['title'];
    			    break;
    				case "calculator":
    					switch($item['metadata']['calculator-used']){
    						case "box-pleated":
    							echo $item['metadata']['valance-type']." Valance";
    						break;
    					}
    				break;
    			}
			}
			 /**PPSASCRUM-92 start **/
					if($item['metadata']['hinged'] == '1'){
                        echo"<br><b>Hinges</b>= Yes (".$item['metadata']['hingecount']."), ";
					}else{
					    echo "<br><b>Hinges</b>= No (0), ";
					}
			/**PPSASCRUM-92 end **/
			echo "</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";

			if($item['metadata']['railroaded'] == '1'){
				echo "RR ";
			}
			if($item['metadata']['com-fabric'] == '1'){
				echo "COM ";
			}
			echo $item['fabricdata']['fabric_name']."</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['fabricdata']['color']."</span></td>";

			echo "<td width=\"6%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			echo $alllinings[$item['metadata']['linings_id']];
			echo "</span></td>";

			echo "<!--DIMENSIONS COLS START-->";
			//start FACE/FW
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			
			if(isset($item['metadata']['face'])){
				echo $item['metadata']['face'];
			}elseif(isset($item['metadata']['fw'])){
				echo $item['metadata']['fw'];
			}elseif(isset($item['metadata']['width'])){
				echo $item['metadata']['width'];
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			//end FACE/FW

			//start ROD WIDTH
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['rod-width'])){
				echo $item['metadata']['rod-width'];
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			//end ROD WIDTH

			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['fl-short'])){
				echo $item['metadata']['fl-short'];
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";

			switch($item['product_type']){
				case "calculator":
					switch($item['metadata']['calculator-used']){
						case "straight-cornice":
							echo $item['metadata']['height'];
						break;
						case "box-pleated":
							echo $item['metadata']['height'];
						break;
						case "pinch-pleated":
					    /* PPSASCRUM-56: start */
						case "new-pinch-pleated":
						/* PPSASCRUM-56: end */
						/* PPSASCRUM-305: start */
        				case "ripplefold-drapery":
        			    /* PPSASCRUM-305: end */
        			    /* PPSASCRUM-384: start */
                        case 'accordiafold-drapery':
                        /* PPSASCRUM-384: end */
							echo $item['metadata']['length'];
						break;
					}
				break;
				case "newcatchall-valance":
				    echo $item['metadata']['height'];
				break;
				case "newcatchall-drapery":
				    echo $item['metadata']['length'];
				break;
				case "newcatchall-cornice":
				    echo $item['metadata']['height'];
				break;
				case "newcatchall-swtmisc":
				    echo $item['metadata']['height'];
				break;
				case "window_treatments":
					echo $item['metadata']['length'];
				break;
			}

			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			
		    /* PPSASCRUM-56: start */
		    /* PPSASCRUM-305: start */
// 			if($item['metadata']['wttype'] == 'Pinch Pleated Drapery' || $item['metadata']['calculator-used'] == 'pinch-pleated' || $item['product_type'] == 'newcatchall-drapery' || $item['metadata']['calculator-used'] == 'new-pinch-pleated'){
		    /* PPSASCRUM-384: start */
		    if($item['metadata']['wttype'] == 'Pinch Pleated Drapery' || $item['metadata']['calculator-used'] == 'pinch-pleated' || $item['product_type'] == 'newcatchall-drapery' || $item['metadata']['calculator-used'] == 'new-pinch-pleated' || 
		       $item['metadata']['calculator-used'] == "ripplefold-drapery" || $item['metadata']['calculator-used'] == "accordiafold-drapery"){
	        /* PPSASCRUM-384: end */
	        /* PPSASCRUM-305: end */
			/* PPSASCRUM-56: end */
				echo $item['metadata']['default-return'];
			}else{
				echo $item['metadata']['return'];
			}
			
			echo "</span></td>";
			echo "<!--DIMENSIONS COLS END-->";

			echo "<td width=\"5%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['fullness'])){
				echo $item['metadata']['fullness']."X";
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['labor-widths'])){
				echo $item['metadata']['labor-widths'];
			}elseif(isset($item['metadata']['labor-billable-widths'])){
			    if(substr($item['metadata']['labor-billable-widths'],-3) == '.00'){
			        echo round($item['metadata']['labor-billable-widths']);
			    }else{
			        echo $item['metadata']['labor-billable-widths'];
			    }
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".(floatval($item['metadata']['labor-billable'])*floatval($item['qty']))."</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if(isset($item['metadata']['vertical-repeat'])){
					echo $item['metadata']['vertical-repeat'];
				}elseif(isset($item['metadata']['vert-repeat'])){
					echo $item['metadata']['vert-repeat'];
				}else{
					if(isset($item['fabricdata']['vertical_repeat'])){
						echo $item['fabricdata']['vertical_repeat'];
					}
				}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center; background-color:".$highlightbg.";\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if(isset($item['metadata']['fab-width'])){
					echo $item['metadata']['fab-width'];
				}elseif(isset($item['metadata']['fabric-width'])){
					echo $item['metadata']['fabric-width'];
				}else{
					if(isset($item['fabricdata']['fabric_width'])){
						echo $item['fabricdata']['fabric_width'];
					}
				}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".$item['metadata']['total-yds']."</span></td>";
			echo "</tr>";


			//look for child items of this line
			$childrow=1;
			foreach($quoteItems as $childitem){

				if($childitem['parent_line'] == $item['primarykey']){
					echo "<tr bgcolor=\"".$bgcolor."\">";
					echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$childitem['line_number']."</span></td>";
					echo "<td width=\"8%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
        			if(isset($childitem['metadata']['location'])){
        			    echo $childitem['metadata']['location'];
        			}else{
        			    echo $childitem['room_number'];
        			}
        			echo "</span></td>";
					echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$childitem['qty']."</span></td>";
					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
    				if(isset($childitem['metadata']['line_item_title'])){
    				    echo $childitem['metadata']['line_item_title'];
    				}else{
    				    echo $childitem['title'];
    				}
    				echo "</span></td>";
					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";

					if($childitem['metadata']['fabrictype'] == 'typein'){
						echo $childitem['metadata']['fabric_name'];
					}else{
						echo $childitem['fabricdata']['fabric_name'];
					}
					echo "</span></td>";

					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
					if($childitem['metadata']['fabrictype']=='typein'){
						echo $childitem['metadata']['fabric_color'];
					}else{
						echo $childitem['fabricdata']['color'];
					}
					echo "</span></td>";


					echo "<td width=\"47%\" colspan=\"11\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
    				if(isset($childitem['metadata']['description'])){
    				    echo $childitem['metadata']['description'];
    				}else{
    				    echo $childitem['description'];
    				}
    				echo "</span></td>";

					echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
					echo (floatval($childitem['metadata']['yds-per-unit'])*floatval($childitem['qty']));
					echo "</span></td>";

					echo "</tr>";
					$childrow++;
				}

			}



			if(count($item['notesdata']) >0){
				echo "<tr>
					<!-- PPSASCRUM-409: start -->
					<!-- <td colspan=\"4\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"14\" valign=\"top\" align=\"left\" style=\"font-size:6px;\"> -->
					<td colspan=\"2\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"16\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">
					<!-- PPSASCRUM-409: end -->";
				$notenum=1;
				foreach($item['notesdata'] as $note){
					/* PPSASCRUM-409: start */
					echo nl2br($note['message']);
					/* PPSASCRUM-409: end */
					if($notenum < count($item['notesdata'])){
						echo "<hr>";
					}
					$notenum++;
				}
				echo "</td></tr>";
			}


			if($item['parent_line'] == '0'){
				$i++;
			}
		}
		

	}
    echo "</tbody></table>";
    echo $globalNotesOutput;
    //SWT VALANCE END
}




/*if(in_array('swtvalance',$types) && ( in_array('swtdraperies',$types) || in_array('swtmisc',$types)  || in_array('track',$types) )){
	echo "<br pagebreak=\"true\"/>";
}*/




if(( in_array('swtdraperies',$types))&& count($types) > 1){
	echo "<br pagebreak=\"true\"/>";
}


//echo "<br pagebreak=\"true\"/>";




if(in_array('swtdraperies',$types)){
//SWT DRAPERIES BEGIN

	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"65%\" valign=\"top\" align=\"left\">
	<br>";
	echo "<table width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">FROM:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($customerData['company_name'])."</span></td>
	</tr>
	<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">PURCHASE ORDER</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">SHIP TO:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);

	if(strlen(trim($orderData['shipping_address_2'])) > 0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode'])."<Br></span></td>
	</tr>";
	
	if(strlen(trim($orderData['attention'])) > 0){
		echo "<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">ATTN:</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['attention'])."</span></td>
		</tr>";
	}

	echo "</table>";


	echo "</td>
	<td width=\"35%\" valign=\"top\" align=\"right\">
	<Br><Br>
	<b>SWT DRAPERIES WORK ORDER</b>

	</td>
	</tr>
	</table>
	<br>";

	if(count($globalNotes) > 0){
		echo "<b>GLOBAL NOTES BELOW</b>";
	}
	echo "<br>";

$lastparent=0;

	//build an array of all PARENTS
	$parents=array();
	foreach($quoteItems as $item){
	    /* PPSASCRUM-56: start */
	    /* PPSASCRUM-305: start */
// 		if(($item['product_type'] == 'calculator' && ($item['metadata']['calculator-used'] == 'pinch-pleated' || $item['metadata']['calculator-used'] == 'pinch-pleated-new' || $item['metadata']['calculator-used'] == 'new-pinch-pleated')) || $item['product_type'] == 'newcatchall-drapery'){
	    /* PPSASCRUM-384: start */
	    if(($item['product_type'] == 'calculator' && ($item['metadata']['calculator-used'] == 'pinch-pleated' || $item['metadata']['calculator-used'] == 'pinch-pleated-new' || $item['metadata']['calculator-used'] == 'new-pinch-pleated' || 
	        $item['metadata']['calculator-used'] == "ripplefold-drapery" || $item['metadata']['calculator-used'] == "accordiafold-drapery")) || $item['product_type'] == 'newcatchall-drapery'){
        /* PPSASCRUM-384: end */
        /* PPSASCRUM-305: end */
	    /* PPSASCRUM-56: end */
			if($item['parent_line'] == '0' && !in_array($item['line_number'],$parents)){
				$parents[]=$item['line_number'];
			}
		}
	}

	echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\" bordercolor=\"#555555\">
	<thead>
	<tr bgcolor=\"#111111\">
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WO LINE</b></span></th>
			<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>SO LINE</b></span></th>
		<th width=\"7%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LOCATION</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>QTY</b></span></th>
		<th width=\"7%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>PRODUCT</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FABRIC</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>COLOR</b></span></th>
		<th width=\"6%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LINING</b></span></th>
		<th width=\"20%\" colspan=\"5\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>DIMENSIONS</b></span></th>
		<th width=\"5%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FULLNESS</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WIDTHS</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LF</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>VRPT</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FAB<br>WIDTH</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>YARDS</b></span></th>

	</tr>
	<tr bgcolor=\"#111111\">

	<!--DIMENSIONS COLS START-->
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FACE / FW</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>ROD W</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FL SHORT</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FL LONG</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>RET</b></span></th>
	<!--DIMENSIONS COLS END-->

	</tr>
	</thead>
	<tbody>";

	$usedLines=array();
	$lineRowTotals=array();
	$COMFabricsTotals=array();

	foreach($quoteItems as $item){
		if(isset($lineRowTotals[$item['line_number']]) && is_numeric($lineRowTotals[$item['line_number']])){
			$lineRowTotals[$item['line_number']]++;
		}else{
			$lineRowTotals[$item['line_number']]=1;
		}
	}

	$metaout='';

	

	$i=1;
	foreach($quoteItems as $item){
		$metaout .= nl2br(print_r($item,1));
		/* PPSASCRUM-56: start */
	    /* PPSASCRUM-305: start */
// 		if(($item['product_type'] == 'calculator' && ($item['metadata']['calculator-used'] == 'pinch-pleated' || $item['metadata']['calculator-used'] == 'pinch-pleated-new' || $item['metadata']['calculator-used'] == 'new-pinch-pleated')) || $item['product_type'] == 'newcatchall-drapery'){
	    /* PPSASCRUM-384: start */
	    if(($item['product_type'] == 'calculator' && ($item['metadata']['calculator-used'] == 'pinch-pleated' || $item['metadata']['calculator-used'] == 'pinch-pleated-new' || $item['metadata']['calculator-used'] == 'new-pinch-pleated' || 
    	    $item['metadata']['calculator-used'] == "ripplefold-drapery" || $item['metadata']['calculator-used'] == "accordiafold-drapery")) || $item['product_type'] == 'newcatchall-drapery'){
        /* PPSASCRUM-384: end */
        /* PPSASCRUM-305: end */
	    /* PPSASCRUM-56: end */

			if($item['parent_line'] == 0){
				$lastparent=$item['primarykey'];
			}

			if($i % 2 == 0){
				$bgcolor='#e8e8e8';
				$highlightbg='#AAAAAA';
			}else{
				$bgcolor='#FFFFFF';
				$highlightbg='#CCCCCC';
			}

			$childofline=$item['id'];

			echo "<tr bgcolor=\"".$bgcolor."\">";
			echo "
			<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['wo_line_number']."</span></td>
			<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['line_number']."</span></td>";
			echo "<td width=\"7%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['location'])){
			    echo $item['metadata']['location'];
			}else{
			    echo $item['room_number'];
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['qty']."</span></td>";
			echo "<td width=\"7%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			//product type
    		if(isset($item['metadata']['line_item_title'])){
			    echo $item['metadata']['line_item_title'];
			}else{
    			switch($item['product_type']){
    			    case "newcatchall-drapery":
    			        echo $item['title'];
    			    break;
    				case "calculator":
    					switch($item['metadata']['calculator-used']){
    						case "pinch-pleated":
    						case "pinch-pleated-new":
						    /* PPSASCRUM-56: start */
    						case "new-pinch-pleated":
    						/* PPSASCRUM-56: end */
    							echo "Pinch Pleated Drapery";
    						break;
    						/* PPSASCRUM-305: start */
    						case "ripplefold-drapery":
				                echo "Ripplefold Drapery";
			                break;
			                /* PPSASCRUM-305: end */
			                /* PPSASCRUM-384: start */
                            case 'accordiafold-drapery':
                                echo "Accordiafold Drapery";
                            break;
                            /* PPSASCRUM-384: end */
    					}
    				break;
    			}
			}
			echo "</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";

			if($item['metadata']['railroaded'] == '1'){
				echo "RR ";
			}
			if($item['metadata']['com-fabric'] == '1'){
				echo "COM ";
			}
			echo $item['fabricdata']['fabric_name']."</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['fabricdata']['color']."</span></td>";

			echo "<td width=\"6%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			echo $alllinings[$item['metadata']['linings_id']];
			echo "</span></td>";

			echo "<!--DIMENSIONS COLS START-->";
			//start FACE/FW
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			
			if(isset($item['metadata']['face'])){
				echo $item['metadata']['face'];
			}elseif(isset($item['metadata']['fw'])){
				echo $item['metadata']['fw'];
			}elseif(isset($item['metadata']['width'])){
				echo $item['metadata']['width'];
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			//end FACE/FW

			//start ROD WIDTH
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['rod-width'])){
				echo $item['metadata']['rod-width'];
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			//end ROD WIDTH

			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['fl-short'])){
				echo $item['metadata']['fl-short'];
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";

			switch($item['product_type']){
				case "calculator":
					switch($item['metadata']['calculator-used']){
						case "straight-cornice":
							echo $item['metadata']['height'];
						break;
						case "box-pleated":
							echo $item['metadata']['height'];
						break;
						case "pinch-pleated":
					    /* PPSASCRUM-56: start */
						case "new-pinch-pleated":
						/* PPSASCRUM-56: end */
						/* PPSASCRUM-305: start */
                		case "ripplefold-drapery":
                	    /* PPSASCRUM-305: end */
                	    /* PPSASCRUM-384: start */
                        case 'accordiafold-drapery':
                        /* PPSASCRUM-384: end */
							echo $item['metadata']['length'];
						break;
					}
				break;
				case "newcatchall-valance":
				    echo $item['metadata']['height'];
				break;
				case "newcatchall-drapery":
				    echo $item['metadata']['length'];
				break;
				case "newcatchall-cornice":
				    echo $item['metadata']['height'];
				break;
				case "newcatchall-swtmisc":
				    echo $item['metadata']['height'];
				break;
				case "window_treatments":
					echo $item['metadata']['length'];
				break;
			}

			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			
		    /* PPSASCRUM-56: start */
		    /* PPSASCRUM-305: start */
// 			if($item['metadata']['wttype'] == 'Pinch Pleated Drapery' || $item['metadata']['calculator-used'] == 'pinch-pleated' || $item['product_type'] == 'newcatchall-drapery' || $item['metadata']['calculator-used'] == 'new-pinch-pleated'){
            /* PPSASCRUM-384: start */
            if($item['metadata']['wttype'] == 'Pinch Pleated Drapery' || $item['metadata']['calculator-used'] == 'pinch-pleated' || $item['product_type'] == 'newcatchall-drapery' || $item['metadata']['calculator-used'] == 'new-pinch-pleated' || 
               $item['metadata']['calculator-used'] == "ripplefold-drapery" || $item['metadata']['calculator-used'] == "accordiafold-drapery"){
			/* PPSASCRUM-384: end */
			/* PPSASCRUM-305: end */
			/* PPSASCRUM-56: end */
				echo $item['metadata']['default-return'];
			}else{
				echo $item['metadata']['return'];
			}
			
			echo "</span></td>";
			echo "<!--DIMENSIONS COLS END-->";

			echo "<td width=\"5%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['fullness'])){
				echo $item['metadata']['fullness']."X";
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['labor-widths'])){
				echo $item['metadata']['labor-widths'];
			}elseif(isset($item['metadata']['labor-billable-widths'])){
			    if(substr($item['metadata']['labor-billable-widths'],-3) == '.00'){
			        echo round($item['metadata']['labor-billable-widths']);
			    }else{
			        echo $item['metadata']['labor-billable-widths'];
			    }
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".(floatval($item['metadata']['labor-billable'])*floatval($item['qty']))."</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if(isset($item['metadata']['vertical-repeat'])){
					echo $item['metadata']['vertical-repeat'];
				}elseif(isset($item['metadata']['vert-repeat'])){
					echo $item['metadata']['vert-repeat'];
				}else{
					if(isset($item['fabricdata']['vertical_repeat'])){
						echo $item['fabricdata']['vertical_repeat'];
					}
				}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center; background-color:".$highlightbg.";\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if(isset($item['metadata']['fab-width'])){
					echo $item['metadata']['fab-width'];
				}elseif(isset($item['metadata']['fabric-width'])){
					echo $item['metadata']['fabric-width'];
				}else{
					if(isset($item['fabricdata']['fabric_width'])){
						echo $item['fabricdata']['fabric_width'];
					}
				}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".$item['metadata']['total-yds']."</span></td>";
			echo "</tr>";


			//look for child items of this line
			$childrow=1;
			foreach($quoteItems as $childitem){

				if($childitem['parent_line'] == $item['primarykey']){
					echo "<tr bgcolor=\"".$bgcolor."\">";
					echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$childitem['wo_line_number']."</span></td>
					<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$childitem['line_number']."</span></td>";
					echo "<td width=\"8%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
        			if(isset($childitem['metadata']['location'])){
        			    echo $childitem['metadata']['location'];
        			}else{
        			    echo $childitem['room_number'];
        			}
        			echo "</span></td>";
					echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$childitem['qty']."</span></td>";
					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
    				if(isset($childitem['metadata']['line_item_title'])){
    				    echo $childitem['metadata']['line_item_title'];
    				}else{
    				    echo $childitem['title'];
    				}
    				echo "</span></td>";
					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";

					if($childitem['metadata']['fabrictype'] == 'typein'){
						echo $childitem['metadata']['fabric_name'];
					}else{
						echo $childitem['fabricdata']['fabric_name'];
					}
					echo "</span></td>";

					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
					if($childitem['metadata']['fabrictype']=='typein'){
						echo $childitem['metadata']['fabric_color'];
					}else{
						echo $childitem['fabricdata']['color'];
					}
					echo "</span></td>";


					echo "<td width=\"47%\" colspan=\"11\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
    				if(isset($childitem['metadata']['description'])){
    				    echo $childitem['metadata']['description'];
    				}else{
    				    echo $childitem['description'];
    				}
    				echo "</span></td>";

					echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
					echo (floatval($childitem['metadata']['yds-per-unit'])*floatval($childitem['qty']));
					echo "</span></td>";

					echo "</tr>";
					$childrow++;
				}

			}



			if(count($item['notesdata']) >0){
				echo "<tr>
					<!-- PPSASCRUM-409: start -->
					<!-- <td colspan=\"4\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"14\" valign=\"top\" align=\"left\" style=\"font-size:6px;\"> -->
					<td colspan=\"2\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"16\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">
					<!-- PPSASCRUM-409: end -->";
				$notenum=1;
				foreach($item['notesdata'] as $note){
					/* PPSASCRUM-409: start */
					echo nl2br($note['message']);
					/* PPSASCRUM-409: end */
					if($notenum < count($item['notesdata'])){
						echo "<hr>";
					}
					$notenum++;
				}
				echo "</td></tr>";
			}


			if($item['parent_line'] == '0'){
				$i++;
			}
		}


	}
    echo "</tbody></table>";
    echo $globalNotesOutput;
    //SWT DRAPERIES END
}




/*if(in_array('swtdraperies',$types) && ( in_array('swtmisc',$types)  || in_array('track',$types) )){
	echo "<br pagebreak=\"true\"/>";
}*/

if( in_array('swtmisc',$types) && count($types) > 1){
	echo "<br pagebreak=\"true\"/>";
}




if(in_array('swtmisc',$types)){
//SWT MISC BEGIN

	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"65%\" valign=\"top\" align=\"left\">
	<br>";
	echo "<table width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">FROM:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($customerData['company_name'])."</span></td>
	</tr>
	<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">PURCHASE ORDER</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">SHIP TO:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);

	if(strlen(trim($orderData['shipping_address_2'])) > 0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode'])."<Br></span></td>
	</tr>";
	
	if(strlen(trim($orderData['attention'])) > 0){
		echo "<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">ATTN:</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['attention'])."</span></td>
		</tr>";
	}

	echo "</table>";


	echo "</td>
	<td width=\"35%\" valign=\"top\" align=\"right\">
	<Br><Br>
	<b>SWT MISC WORK ORDER</b>

	</td>
	</tr>
	</table>
	<br>";

	if(count($globalNotes) > 0){
		echo "<b>GLOBAL NOTES BELOW</b>";
	}
	echo "<br>";

$lastparent=0;

	//build an array of all PARENTS
	$parents=array();
	foreach($quoteItems as $item){
		if($item['product_type'] == 'newcatchall-swtmisc'){
			if($item['parent_line'] == '0' && !in_array($item['line_number'],$parents)){
				$parents[]=$item['line_number'];
			}
		}
	}


	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#555555\">
	<thead>
	<tr bgcolor=\"#111111\">
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WO LINE</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>SO LINE</b></span></th>
		<th width=\"6%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LOCATION</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>QTY</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>PRODUCT</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FABRIC</b></span></th>
		<th width=\"11%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>COLOR</b></span></th>
		<th width=\"6%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LINING</b></span></th>
		<th width=\"20%\" colspan=\"5\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>DIMENSIONS</b></span></th>
		<th width=\"5%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FULLNESS</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WIDTHS</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LF</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>VRPT</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FAB<br>WIDTH</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>YARDS</b></span></th>

	</tr>
	<tr bgcolor=\"#111111\">

	<!--DIMENSIONS COLS START-->
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FACE / FW</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>ROD W</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FL SHORT</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>FL LONG</b></span></th>
		<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>RET</b></span></th>
	<!--DIMENSIONS COLS END-->

	</tr>
	</thead>
	<tbody>";

	$usedLines=array();
	$lineRowTotals=array();
	$COMFabricsTotals=array();

	foreach($quoteItems as $item){
		if(isset($lineRowTotals[$item['line_number']]) && is_numeric($lineRowTotals[$item['line_number']])){
			$lineRowTotals[$item['line_number']]++;
		}else{
			$lineRowTotals[$item['line_number']]=1;
		}
	}

	$metaout='';

	

	$i=1;
	foreach($quoteItems as $item){
		$metaout .= nl2br(print_r($item,1));
		if($item['product_type'] == 'newcatchall-swtmisc'){

			if($item['parent_line'] == 0){
				$lastparent=$item['primarykey'];
			}

			if($i % 2 == 0){
				$bgcolor='#e8e8e8';
				$highlightbg='#AAAAAA';
			}else{
				$bgcolor='#FFFFFF';
				$highlightbg='#CCCCCC';
			}

			$childofline=$item['id'];

			echo "<tr bgcolor=\"".$bgcolor."\">";
			echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['wo_line_number']."</span></td>
			<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['line_number']."</span></td>";
			echo "<td width=\"6%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['location'])){
			    echo $item['metadata']['location'];
			}else{
			    echo $item['room_number'];
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['qty']."</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			//product type
			if(isset($item['metadata']['line_item_title'])){
			    echo $item['metadata']['line_item_title'];
			}else{
    			switch($item['product_type']){
    			    case "newcatchall-swtmisc":
    			        echo $item['title'];
    			    break;
    			}
			}
			echo "</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";

			if($item['metadata']['railroaded'] == '1'){
				echo "RR ";
			}
			if($item['metadata']['com-fabric'] == '1'){
				echo "COM ";
			}
			echo $item['fabricdata']['fabric_name']."</span></td>";
			echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['fabricdata']['color']."</span></td>";

			echo "<td width=\"6%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
			echo $alllinings[$item['metadata']['linings_id']];
			echo "</span></td>";

			echo "<!--DIMENSIONS COLS START-->";
			//start FACE/FW
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			
			if(isset($item['metadata']['face'])){
				echo $item['metadata']['face'];
			}elseif(isset($item['metadata']['fw'])){
				echo $item['metadata']['fw'];
			}elseif(isset($item['metadata']['width'])){
				echo $item['metadata']['width'];
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			//end FACE/FW

			//start ROD WIDTH
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['rod-width'])){
				echo $item['metadata']['rod-width'];
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			//end ROD WIDTH

			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['fl-short'])){
				echo $item['metadata']['fl-short'];
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";

			switch($item['product_type']){
				case "calculator":
					switch($item['metadata']['calculator-used']){
						case "straight-cornice":
							echo $item['metadata']['height'];
						break;
						case "box-pleated":
							echo $item['metadata']['height'];
						break;
						case "pinch-pleated":
					    /* PPSASCRUM-56: start */
						case "new-pinch-pleated":
						/* PPSASCRUM-56: end */
						/* PPSASCRUM-305: start */
        				case "ripplefold-drapery":
        			    /* PPSASCRUM-305: end */
        			    /* PPSASCRUM-384: start */
                        case 'accordiafold-drapery':
                        /* PPSASCRUM-384: end */
							echo $item['metadata']['length'];
						break;
					}
				break;
				case "newcatchall-valance":
				    echo $item['metadata']['height'];
				break;
				case "newcatchall-drapery":
				    echo $item['metadata']['length'];
				break;
				case "newcatchall-cornice":
				    echo $item['metadata']['height'];
				break;
				case "newcatchall-swtmisc":
				    echo $item['metadata']['height'];
				break;
				case "window_treatments":
					echo $item['metadata']['length'];
				break;
			}

			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			
		    /* PPSASCRUM-56: start */
		    /* PPSASCRUM-305: start */
// 			if($item['metadata']['wttype'] == 'Pinch Pleated Drapery' || $item['metadata']['calculator-used'] == 'pinch-pleated' || $item['product_type'] == 'newcatchall-drapery' || $item['metadata']['calculator-used'] == 'new-pinch-pleated'){
		    /* PPSASCRUM-384: start */
		    if($item['metadata']['wttype'] == 'Pinch Pleated Drapery' || $item['metadata']['calculator-used'] == 'pinch-pleated' || $item['product_type'] == 'newcatchall-drapery' || $item['metadata']['calculator-used'] == 'new-pinch-pleated' || 
		       $item['metadata']['calculator-used'] == "ripplefold-drapery" || $item['metadata']['calculator-used'] == "accordiafold-drapery"){
		    /* PPSASCRUM-384: end */
		    /* PPSASCRUM-305: end */
			/* PPSASCRUM-56: end */
				echo $item['metadata']['default-return'];
			}else{
				echo $item['metadata']['return'];
			}
			
			echo "</span></td>";
			echo "<!--DIMENSIONS COLS END-->";

			echo "<td width=\"5%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['fullness'])){
				echo $item['metadata']['fullness']."X";
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
			if(isset($item['metadata']['labor-widths'])){
				echo $item['metadata']['labor-widths'];
			}elseif(isset($item['metadata']['labor-billable-widths'])){
			    if(substr($item['metadata']['labor-billable-widths'],-3) == '.00'){
			        echo round($item['metadata']['labor-billable-widths']);
			    }else{
			        echo $item['metadata']['labor-billable-widths'];
			    }
			}else{
				echo "&nbsp;";
			}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".(floatval($item['metadata']['labor-billable'])*floatval($item['qty']))."</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if(isset($item['metadata']['vertical-repeat'])){
					echo $item['metadata']['vertical-repeat'];
				}elseif(isset($item['metadata']['vert-repeat'])){
					echo $item['metadata']['vert-repeat'];
				}else{
					if(isset($item['fabricdata']['vertical_repeat'])){
						echo $item['fabricdata']['vertical_repeat'];
					}
				}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center; background-color:".$highlightbg.";\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
				if(isset($item['metadata']['fab-width'])){
					echo $item['metadata']['fab-width'];
				}elseif(isset($item['metadata']['fabric-width'])){
					echo $item['metadata']['fabric-width'];
				}else{
					if(isset($item['fabricdata']['fabric_width'])){
						echo $item['fabricdata']['fabric_width'];
					}
				}
			echo "</span></td>";
			echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">".$item['metadata']['total-yds']."</span></td>";
			echo "</tr>";


			//look for child items of this line
			$childrow=1;
			foreach($quoteItems as $childitem){

				if($childitem['parent_line'] == $item['primarykey']){
					echo "<tr bgcolor=\"".$bgcolor."\">";
					echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$childitem['line_number']."</span></td>";
					echo "<td width=\"8%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
        			if(isset($childitem['metadata']['location'])){
        			    echo $childitem['metadata']['location'];
        			}else{
        			    echo $childitem['room_number'];
        			}
        			echo "</span></td>";
					echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$childitem['qty']."</span></td>";
					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
    				if(isset($childitem['metadata']['line_item_title'])){
    				    echo $childitem['metadata']['line_item_title'];
    				}else{
    				    echo $childitem['title'];
    				}
    				echo "</span></td>";
					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";

					if($childitem['metadata']['fabrictype'] == 'typein'){
						echo $childitem['metadata']['fabric_name'];
					}else{
						echo $childitem['fabricdata']['fabric_name'];
					}
					echo "</span></td>";

					echo "<td width=\"11%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
					if($childitem['metadata']['fabrictype']=='typein'){
						echo $childitem['metadata']['fabric_color'];
					}else{
						echo $childitem['fabricdata']['color'];
					}
					echo "</span></td>";


					echo "<td width=\"47%\" colspan=\"11\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
    				if(isset($childitem['metadata']['description'])){
    				    echo $childitem['metadata']['description'];
    				}else{
    				    echo $childitem['description'];
    				}
    				echo "</span></td>";

					echo "<td width=\"4%\" style=\"font-size:7px; text-align:center;\" align=\"center\"><span style=\"font-size:7px;color:#000000;\">";
					echo (floatval($childitem['metadata']['yds-per-unit'])*floatval($childitem['qty']));
					echo "</span></td>";

					echo "</tr>";
					$childrow++;
				}

			}



			if(count($item['notesdata']) >0){
				echo "<tr>
					<!-- PPSASCRUM-409: start -->
					<!-- <td colspan=\"4\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"14\" valign=\"top\" align=\"left\" style=\"font-size:6px;\"> -->
					<td colspan=\"2\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"16\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">
					<!-- PPSASCRUM-409: end -->";
				$notenum=1;
				foreach($item['notesdata'] as $note){
					/* PPSASCRUM-409: start */
					echo nl2br($note['message']);
					/* PPSASCRUM-409: end */
					if($notenum < count($item['notesdata'])){
						echo "<hr>";
					}
					$notenum++;
				}
				echo "</td></tr>";
			}


			if($item['parent_line'] == '0'){
				$i++;
			}
		}


	}
    echo "</tbody></table>";
    echo $globalNotesOutput;
    //SWT MISC END
}




if(in_array('track',$types)  && count($types) > 1){
	echo "<br pagebreak=\"true\"/>";
}









if(in_array('track',$types)){
//TRACK BEGIN

echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"65%\" valign=\"top\" align=\"left\">
	<br>";
	echo "<table width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">FROM:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($customerData['company_name'])."</span></td>
	</tr>
	<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">PURCHASE ORDER</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">SHIP TO:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);

	if(strlen(trim($orderData['shipping_address_2'])) > 0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode'])."<Br></span></td>
	</tr>";
	
	if(strlen(trim($orderData['attention'])) > 0){
		echo "<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">ATTN:</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['attention'])."</span></td>
		</tr>";
	}

	echo "</table>";


	echo "</td>
	<td width=\"35%\" valign=\"top\" align=\"right\">
	<Br><Br>
	<b>TRACK WORK ORDER</b>

	</td>
	</tr>
	</table>
	<br>";

	if(count($globalNotes) > 0){
		echo "<b>GLOBAL NOTES BELOW</b>";
	}
	echo "<br>";


	echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#555555\">
	<thead>
	<tr bgcolor=\"#111111\">
	<th width=\"5%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:9px;\"><span style=\"font-size:9px;color:#FFFFFF;\"><b>WO LINE #</b></span></th>
		<th width=\"5%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:9px;\"><span style=\"font-size:9px;color:#FFFFFF;\"><b>SO LINE #</b></span></th>
		<th width=\"5%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:9px;\"><span style=\"font-size:9px;color:#FFFFFF;\"><b>ITEM #</b></span></th>
		<th width=\"5%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:9px;\"><span style=\"font-size:9px;color:#FFFFFF;\"><b>QTY</b></span></th>
		<th width=\"8%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:9px;\"><span style=\"font-size:9px;color:#FFFFFF;\"><b>INCHES</b></span></th>
		<th width=\"25%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:9px;\"><span style=\"font-size:9px;color:#FFFFFF;\"><b>COMPONENT</b></span></th>
		<th width=\"52%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:9px;\"><span style=\"font-size:9px;color:#FFFFFF;\"><b>COMMENT</b></span></th>
	</tr>
	</thead><tbody>";

	$i=1;
	foreach($quoteItems as $item){
		$metaout .= nl2br(print_r($item,1));
		
		if($item['product_type'] == 'track_systems' || $item['product_subclass'] == 3){
			

			for($si=1; $si <= floatval($item['metadata']['_component_numlines']); $si++){

				if($i % 2 == 0){
					$bgcolor='#e8e8e8';
					$highlightbg='#AAAAAA';
				}else{
					$bgcolor='#FFFFFF';
					$highlightbg='#CCCCCC';
				}

				echo "<tr nobr=\"true\" bgcolor=\"".$bgcolor."\">";
				echo "<td width=\"5%\" style=\"font-size:9px;\"><span style=\"font-size:9px;color:#000000;\">".$item['wo_line_number']."</span></td>";
					echo "<td width=\"5%\" style=\"font-size:9px;\"><span style=\"font-size:9px;color:#000000;\">".$item['line_number']."</span></td>";
				echo "<td width=\"5%\" style=\"font-size:9px;\"><span style=\"font-size:9px;color:#000000;\">".$si."</span></td>";
				echo "<td width=\"5%\" style=\"font-size:9px;\"><span style=\"font-size:9px;color:#000000;\">".$item['metadata']["_component_".$si."_qty"]."</span></td>";
				echo "<td width=\"8%\" style=\"font-size:9px;\"><span style=\"font-size:9px;color:#000000;\">".$item['metadata']["_component_".$si."_inches"]."</span></td>";
				echo "<td width=\"25%\" style=\"font-size:9px;\"><span style=\"font-size:9px;color:#000000;\">".$componentsList[$item['metadata']["_component_".$si."_componentid"]]['title']."</span></td>";
				echo "<td width=\"52%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['metadata']["_component_".$si."_comment"]."</span></td>";
				echo "</tr>";
				$i++;
			}
		}
	}

	echo "</tbody></table>";
echo $globalNotesOutput;
//TRACK END

}




if(in_array('catchall',$types)&& count($types) > 1 ){
	echo "<br pagebreak=\"true\"/>";
}




if(in_array('catchall',$types)){
//CATCH-ALL BEGIN
	$lastparent=0;


	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"65%\" valign=\"top\" align=\"left\">
	<br>";
	echo "<table width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">FROM:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($customerData['company_name'])."</span></td>
	</tr>
	<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">PURCHASE ORDER</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".$orderData['po_number']."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">SHIP TO:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['facility']."<br>".$orderData['shipping_address_1']);

	if(strlen(trim($orderData['shipping_address_2'])) > 0){
		echo "<br>".strtoupper($orderData['shipping_address_2']);
	}
	echo "<br>".strtoupper($orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode'])."<Br></span></td>
	</tr>";
	
	if(strlen(trim($orderData['attention'])) > 0){
		echo "<tr>
		<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">ATTN:</span></td>
		<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($orderData['attention'])."</span></td>
		</tr>";
	}

	echo "</table>";


	echo "</td>
	<td width=\"35%\" valign=\"top\" align=\"right\">
	<Br><Br>
	<b>(OTHERS) WORK ORDER</b>

	</td>
	</tr>
	</table>
	<br>";

	if(count($globalNotes) > 0){
		echo "<b>GLOBAL NOTES BELOW</b>";
	}
	echo "<br>
	<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#555555\">
	<thead>
	<tr bgcolor=\"#111111\">
		<th width=\"4%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>WO LINE</b></span></th>
		<th width=\"4%\" rowspan=\"2\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>SO LINE</b></span></th>
		<th width=\"5%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LOCATION</b></span></th>
		<th width=\"5%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>QTY</b></span></th>
		<th width=\"6%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>UNITS</b></span></th>
		<th width=\"15%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;line-height:400%;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>LINE ITEM TITLE</b></span></th>
		<th width=\"15%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>DESCRIPTION</b></span></th>
		<th width=\"16%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>IMAGE</b></span></th>
		<th width=\"16%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>PATTERN</b></span></th>
		<th width=\"16%\" valign=\"middle\" align=\"center\" style=\"vertical-align:middle; font-size:5px;\"><span style=\"font-size:5px;color:#FFFFFF;\"><b>COLOR</b></span></th>
	</tr>
	</thead>
	<tbody>";

	$usedLines=array();
	$lineRowTotals=array();
	$COMFabricsTotals=array();

	foreach($quoteItems as $itemid => $item){
		if(isset($lineRowTotals[$item['line_number']]) && is_numeric($lineRowTotals[$item['line_number']])){
			$lineRowTotals[$item['line_number']]++;
		}else{
			$lineRowTotals[$item['line_number']]=1;
		}
	}

	$metaout='';

	$i=1;
	
	
	foreach($quoteItems as $itemid => $item){
		$metaout .= nl2br(print_r($item,1));
		
		if(!in_array($itemid,$usedLines)){
			if($item['product_type'] == 'custom' && $item['parent_line'] == 0){

				if($i % 2 == 0){
					$bgcolor='#e8e8e8';
					$highlightbg='#AAAAAA';
				}else{
					$bgcolor='#FFFFFF';
					$highlightbg='#CCCCCC';
				}
				echo "<tr bgcolor=\"".$bgcolor."\">";
				echo "<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['wo_line_number']."</span></td>
				<td width=\"4%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['line_number']."</span></td>";
				echo "<td width=\"5%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
    			if(isset($item['metadata']['location'])){
    			    echo $item['metadata']['location'];
    			}else{
    			    echo $item['room_number'];
    			}
    			echo "</span></td>";
				echo "<td width=\"5%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['qty']."</span></td>";
				echo "<td width=\"6%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['unit']."</span></td>";
				echo "<td width=\"15%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
				if(isset($item['metadata']['line_item_title'])){
				    echo $item['metadata']['line_item_title'];
				}else{
				    echo $item['title'];
				}
				echo "</span></td>";
				echo "<td width=\"15%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
				if(isset($item['metadata']['description'])){
				    $thisDesc=$item['metadata']['description'];
				    echo $thisDesc;
				}else{
				    $thisDesc=$item['description'];
				    echo $thisDesc;
				}

				if($item['product_type'] == 'custom' && strlen(trim($item['metadata']['specs'])) > 0){
					if(strlen(trim($thisDesc)) >0){
						echo "<br>";
					}
					echo $item['metadata']['specs'];
				}

				echo "</span></td>";
				echo "<td width=\"16%\" style=\"font-size:7px;\">";
					//find image
					echo "<img src=\"".$item['imagesrc']."\" width=\"150\" />";
				echo "</td>";
				echo "<td width=\"16%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">";
				if($item['metadata']['com-fabric'] == '1'){
					echo "COM ";
				}
				echo $item['fabricdata']['fabric_name']."</span></td>";


				echo "<td width=\"16%\" style=\"font-size:7px;\"><span style=\"font-size:7px;color:#000000;\">".$item['fabricdata']['color']."</span></td>
				</tr>";


				if(count($item['notesdata']) >0){
					echo "<tr>
						<!-- PPSASCRUM-409: start -->
    					<!-- <td colspan=\"3\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"6\" valign=\"top\" align=\"left\" style=\"font-size:6px;\"> -->
    					<td colspan=\"2\" align=\"right\" valign=\"top\" style=\"font-size:6px;\"><b>NOTES:</b></td><td colspan=\"7\" valign=\"top\" align=\"left\" style=\"font-size:6px;\">
    					<!-- PPSASCRUM-409: end -->";
					$notenum=1;
					foreach($item['notesdata'] as $note){
						/* PPSASCRUM-409: start */
						echo nl2br($note['message']);
						/* PPSASCRUM-409: end */
						if($notenum < count($item['notesdata'])){
							echo "<hr>";
						}
						$notenum++;
					}
					echo "</td></tr>";
				}


				$usedLines[]=$itemid;
				$i++;
			}


		}
	}
echo "</tbody></table>";
	echo $globalNotesOutput;
//CATCH-ALL END
}

?>