<?php
function fixcharacters($string){
	$out = htmlspecialchars($string);
	return $out;
}

echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
<tr>
<td width=\"65%\" valign=\"top\" align=\"left\">
	<br><br>
	<table width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">To:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($customerData['company_name'])."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">Address:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($customerData['billing_address'])."<br>".strtoupper($customerData['billing_address_city'].", ".$customerData['billing_address_state']." ".$customerData['billing_address_zipcode'])."<Br></span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">ATTN:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".strtoupper($contactData['first_name']." ".$contactData['last_name'])."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">PHONE:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".$contactData['phone']."</span></td>
	</tr>
	<tr>
	<td width=\"30%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">EMAIL:</span></td>
	<td width=\"70%\" valign=\"top\" align=\"left\" style=\"font-size:8px;\"><span style=\"font-size:8px;\">".$contactData['email_address']."</span></td>
	</tr>
	</table>
</td>
<td width=\"35%\" valign=\"top\" align=\"right\">
<Br><br>


</td>
</tr>
</table>
<br><br>
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
<thead>
<tr bgcolor=\"#000000\">
<th width=\"3%\" valign=\"bottom\" align=\"center\" style=\"border-top:1px solid #000000; border-left:1px solid #000000; font-size:7px;\"><span style=\"font-size:7px;color:#FFF;\"><b>LINE</b></span></th>
<th width=\"6%\" valign=\"bottom\" align=\"center\" style=\"border-top:1px solid #000000; border-left:1px solid #CCC; font-size:7px;\"><span style=\"font-size:7px;color:#FFF;\"><b>LOCATION</b></span></th>
<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"border-top:1px solid #000000; border-left:1px solid #CCC; font-size:7px;\"><span style=\"font-size:7px;color:#FFF;\"><b>QTY</b></span></th>
<th width=\"4%\" valign=\"bottom\" align=\"center\" style=\"border-top:1px solid #000000; border-left:1px solid #CCC; font-size:7px;\"><span style=\"font-size:7px;color:#FFF;\"><b>UNIT</b></span></th>
<th width=\"10%\" valign=\"bottom\" align=\"center\" style=\"border-top:1px solid #000000; border-left:1px solid #CCC; font-size:7px;\"><span style=\"font-size:7px;color:#FFF;\"><b>ITEM</b></span></th>
<th width=\"16%\" valign=\"bottom\" align=\"center\" style=\"border-top:1px solid #000000; border-left:1px solid #CCC; font-size:7px;\" colspan=\"2\"><span style=\"font-size:7px;color:#FFF;\"><b>FABRIC &middot; COLOR</b></span></th>
<th width=\"45%\" style=\"border-top:1px solid #000000; border-left:1px solid #CCC; font-size:7px;\" colspan=\"2\" align=\"center\"><span style=\"font-size:7px;color:#FFF;\"><b>DIMENSIONS &amp; SPECS</b></span></th>
<th width=\"6%\" valign=\"bottom\" align=\"center\" style=\"border-top:1px solid #000000; border-left:1px solid #CCC; font-size:7px;\"><span style=\"font-size:7px;color:#FFF;\"><b>PRICE EA</b></span></th>
<th width=\"6%\" valign=\"bottom\" align=\"center\" style=\"border-top:1px solid #000000; border-left:1px solid #CCC; border-right:1px solid #000000; font-size:7px;\"><span style=\"font-size:7px;color:#FFF;\"><b>EXT</b></span></th>
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


foreach($quoteItems as $item){
    

if($item['internal_line'] == 0){
	if(floatval($item['line_number']) % 2 == 0){
		$linebgcolor='#E4EEFA';
	}else{
		$linebgcolor='#FFFFFF';
	}
	
	if(isset($usedLines[$item['line_number']]) && is_numeric($usedLines[$item['line_number']])){
		$usedLines[$item['line_number']]++;
	}else{
		$usedLines[$item['line_number']]=1;
	}
	
	if($usedLines[$item['line_number']] == $lineRowTotals[$item['line_number']]){
		$bottomborder='1px solid #000000';
	}else{
		$bottomborder='1px solid #CCCCCC';
	}


	$visiblenotecount=0;
	if(count($item['notes']) >0){
		foreach($item['notes'] as $noteRow){
			if($noteRow['visible_to_customer'] == '1'){
				$visiblenotecount++;
			}
		}
	}

	if($visiblenotecount > 0){
		$bottomborder='1px solid #CCCCCC';
	}


	echo "<tr bgcolor=\"".$linebgcolor."\" nobr=\"true\">
	<td width=\"3%\" align=\"center\" style=\"border-left:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">".$item['line_number']."</span></td>
	<td width=\"6%\" align=\"center\" style=\"border-left:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">".fixcharacters($item['room_number'])."</span></td>
	<td width=\"4%\" align=\"center\" style=\"border-left:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">".$item['qty']."</span></td>
	<td width=\"4%\" align=\"center\" style=\"border-left:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">";
	if($item['unit']=='Linear feet'){
		echo "LF";
	}else{
		echo $item['unit'];
	}
	echo "</span></td>
	<td width=\"10%\" align=\"center\" style=\"border-left:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">";
	switch($item['product_type']){
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
            echo $item['title'];
        break;
		case "cubicle_curtains":
			//price list CC's
			echo "Cubicle Curtain";
		break;
		case "calculator":
			//calculated item
			switch($item['metadata']['calculator-used']){
				case "cubicle-curtain":
					echo "Cubicle Curtain";
				break;
				case "bedspread":
				case "bedspread-manual":
					echo "Bedspread";
				break;
				case "box-pleated":
					echo $item['metadata']['valance-type']." Valance";
				break;
				case "straight-cornice":
					echo $item['metadata']['cornice-type']." Cornice";
				break;
				case "pinch-pleated":
			    /* PPSASCRUM-56: start */
				case "new-pinch-pleated":
				/* PPSASCRUM-56: end */
					echo "Pinch Pleated Drapery";
					if($item['metadata']['unit-of-measure'] == 'pair'){
						echo "<br>Pair";
					}else{
						echo "<br>".$item['metadata']['panel-type']." Panel";
					}
				break;
				/* PPSASCRUM-305: start */
				case "ripplefold-drapery":
					echo "Ripplefold Drapery";
					if($item['metadata']['unit-of-measure'] == 'pair'){
						echo "<br>Pair";
					}else{
						echo "<br>".$item['metadata']['panel-type']." Panel";
					}
				break;
				/* PPSASCRUM-305: end */
				/* PPSASCRUM-384: start */
                case 'accordiafold-drapery':
					echo "Accordiafold Drapery";
					if($item['metadata']['unit-of-measure'] == 'pair'){
						echo "<br>Pair";
					}else{
						echo "<br>".$item['metadata']['panel-type']." Panel";
					}
				break;
				/* PPSASCRUM-384: end */
			}
		break;
		case "bedspreads":
			//price list BS
			echo "Bedspread";
		break;
		case "custom":
			echo fixcharacters($item['title'])."<br>".fixcharacters($item['description']);
		break;
		case "services":
			echo "Other";
		break;

		case "track_systems":
			echo "Track";
		break;
		case "window_treatments":
			echo $item['metadata']['wttype'];
			if($item['metadata']['wttype']=='Pinch Pleated Drapery'){
				echo "<br>".$item['metadata']['unit-of-measure'];
			}
			/*
			switch($item['metadata']['wt_type']){
				echo $item['']
			}
			*/
		break;
	}
	echo "</span></td>
	<td width=\"8%\" align=\"center\" style=\"border-left:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">";

	if(isset($item['metadata']['com-fabric']) && $item['metadata']['com-fabric'] == "1"){
		echo "COM ";
		if($item['metadata']['fabrictype']=='typein'){
			if($item['internal_line']=='0'){
				//echo fixcharacters($item['metadata']['fabric_name']);
			
				if(isset($COMFabricsTotals[$item['metadata']['fabric_name']])){
					$COMFabricsTotals[$item['metadata']['fabric_name']]=(floatval($COMFabricsTotals[$item['metadata']['fabric_name']])+ (floatval($item['metadata']['yds-per-unit']) * floatval($item['qty'])));
				}else{
					$COMFabricsTotals[$item['metadata']['fabric_name']]=(floatval($item['metadata']['yds-per-unit']) * floatval($item['qty']));
				}
			}


		}else{
			if(isset($COMFabricsTotals[$item['metadata']['fabricid']])){
				$COMFabricsTotals[$item['metadata']['fabricid']]=(floatval($COMFabricsTotals[$item['metadata']['fabricid']])+(floatval($item['metadata']['yds-per-unit']) * floatval($item['qty'])));
			}else{
				$COMFabricsTotals[$item['metadata']['fabricid']]=(floatval($item['metadata']['yds-per-unit']) * floatval($item['qty']));
			}
		}
	}

	
	if($item['metadata']['usealias']=='yes' && $item['fabricdata']['alias_name']){
		echo $item['fabricdata']['alias_name'];
	}else{
		
		
		if(isset($item['metadata']['fabrictype']) && $item['metadata']['fabrictype']=='typein'){
			echo fixcharacters($item['metadata']['fabric_name']);
		}else{
			echo $item['fabricdata']['fabric_name'];
		}
	}
	echo "</span></td>
	<td width=\"8%\" align=\"center\" style=\"border-left:1px solid #CCCCCC; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">";

	if($item['metadata']['fabrictype']=='typein'){
		echo fixcharacters($item['metadata']['fabric_color']);
	}else{
		if($item['metadata']['usealias']=='yes' && $item['fabricdata']['alias_color']){
			echo $item['fabricdata']['alias_color'];
		}else{
			echo $item['fabricdata']['color'];
		}
	}
	echo "</span></td>";

	if($item['product_type'] == 'track_systems'){
		echo "<td width=\"45%\" colspan=\"2\" align=\"center\" style=\"border-left:1px solid #000000; border-right:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">";
		echo $item['title']."<br>".$item['description'];
		echo "</span></td>";

	}elseif($item['product_type'] == 'services'){
		echo "<td width=\"45%\" colspan=\"2\" align=\"center\" style=\"border-left:1px solid #000000; border-right:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">";
		echo $item['title'];
		echo "</span></td>";

	}else{
		echo "<td width=\"18%\" align=\"center\" style=\"border-left:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">";
		/*if(isset($item['metadata']['fw']) && isset($item['metadata']['length'])){
			echo $item['metadata']['fw']."&quot; x ".$item['metadata']['length']."&quot;";
		}else*/


		if($item['product_type']=="cubicle_curtains"){

			echo $item['metadata']['width']."&quot; CW x ".$item['metadata']['length']."&quot; FL<br>";

			if(isset($item['metadata']['expected-finish-width']) && floatval($item['metadata']['expected-finish-width']) >0){
				echo $item['metadata']['expected-finish-width'].'&quot; Finished Width<br>';
			}else{
				echo "(approx ";

				if(floatval($item['metadata']['width']) == 54){
					echo "46&quot;-49&quot;";
				}elseif(floatval($item['metadata']['width']) == 72){
					echo "66&quot;-68&quot;";
				}elseif(floatval($item['metadata']['width']) == 90){
					echo "82&quot;-84&quot;";
				}elseif(floatval($item['metadata']['width']) == 108){
					echo "100&quot;-102&quot;";
				}elseif(floatval($item['metadata']['width']) == 126){
					echo "114&quot;-117&quot;";
				}elseif(floatval($item['metadata']['width']) == 144){
					echo "134&quot;-136&quot;";
				}elseif(floatval($item['metadata']['width']) == 162){
					echo "150&quot;-152&quot;";
				}elseif(floatval($item['metadata']['width']) == 180){
					echo "166&quot;-168&quot;";
				}elseif(floatval($item['metadata']['width']) == 198){
					echo "182&quot;-186&quot;";
				}elseif(floatval($item['metadata']['width']) == 216){
					echo "202&quot;-204&quot;";
				}elseif(floatval($item['metadata']['width']) == 234){
					echo "218&quot;-220&quot;";
				}elseif(floatval($item['metadata']['width']) == 252){
					echo "236&quot;-238&quot;";
				}elseif(floatval($item['metadata']['width']) == 270){
					echo "248&quot;-250&quot;";
				}elseif(floatval($item['metadata']['width']) == 288){
					echo "273&quot;-275&quot;";
				}

				echo " FW)";
			}

		}elseif($item['product_type']=="calculator" && $item['metadata']['calculator-used']=='cubicle-curtain'){

			echo $item['metadata']['width']."&quot; CW x ".$item['metadata']['length']."&quot; FL<br>(";
			if(isset($item['metadata']['expected-finish-width']) && ($item['metadata']['expected-finish-width'] != '0' && strlen(trim($item['metadata']['expected-finish-width'])) >0)){
				echo $item['metadata']['expected-finish-width']."&quot; FW";
			}else{
				echo "approx ".$item['metadata']['fw']."&quot; FW";
			}
			echo ")";

			/*
			if($item['metadata']['expected-finish-width']=='0' || $item['metadata']['fw']==''){
				echo $item['metadata']['width']."&quot; CW x ".$item['metadata']['length']."&quot; FL<br>(approx ".$item['metadata']['fw']."&quot; FW)";
			}else{
				echo $item['metadata']['width']."&quot; CW x ".$item['metadata']['length']."&quot; FL<br>(".$item['metadata']['expected-finish-width']."&quot; FW)";
			}
			*/
	    /* PPSASCRUM-56: start */
	    /* PPSASCRUM-305: start */
// 		}elseif(($item['product_type']=="window_treatments" && $item['metadata']['wttype'] == 'Pinch Pleated Drapery') || ($item['metadata']['calculator-used']=='pinch-pleated' || $item['metadata']['calculator-used']=='new-pinch-pleated')){
		/* PPSASCRUM-384: start */
		}elseif(($item['product_type']=="window_treatments" && $item['metadata']['wttype'] == 'Pinch Pleated Drapery') || 
		        ($item['metadata']['calculator-used']=='pinch-pleated' || $item['metadata']['calculator-used']=='new-pinch-pleated' || 
		        $item['metadata']['calculator-used'] == "ripplefold-drapery" || $item['metadata']['calculator-used'] == "accordiafold-drapery")){
        /* PPSASCRUM-384: end */
		/* PPSASCRUM-305: end */
		/* PPSASCRUM-56: end */

			if($item['metadata']['unit-of-measure'] == 'pair'){
				echo "Rod W: ".$item['metadata']['rod-width']."&quot; x L: ".$item['metadata']['length']."&quot;";
			}else{
				echo $item['metadata']['fabric-widths-per-panel']." Widths x L: ".$item['metadata']['length']."&quot;";
			}

		}elseif($item['product_type'] == 'newcatchall-drapery'){
		    
		    if(isset($item['metadata']['rod-width'])){
		        echo "Rod W: ".$item['metadata']['rod-width']."&quot; x L: ".$item['metadata']['length']."&quot;";
		    }elseif(isset($item['metadata']['fabric-widths-per-panel'])){
		        echo $item['metadata']['fabric-widths-per-panel']." Widths x L: ".$item['metadata']['length'];
		    }
		    
		}else{

			if(isset($item['metadata']['width']) && isset($item['metadata']['length'])){
				echo "W: ".$item['metadata']['width'].'&quot; x L: '.$item['metadata']['length'].'&quot;';
			}
			if(isset($item['metadata']['face']) && isset($item['metadata']['height'])){
				echo "F: ".$item['metadata']['face']."&quot; x H: ".$item['metadata']['height']."&quot;";
			}
		}


		if($item['product_type'] == 'bedspreads' || ($item['product_type']=="calculator" && ($item['metadata']['calculator-used']=='bedspread' || $item['metadata']['calculator-used']=='bedspread-manual'))){

			echo "<br>Mattress: ";
			if(!isset($item['metadata']['custom-top-width-mattress-w'])){
				echo "36&quot;";
			}else{
				echo $item['metadata']['custom-top-width-mattress-w']."&quot;";
			}

		}

		echo "</span></td>
		<td width=\"27%\" align=\"center\" style=\"border-left:1px solid #CCCCCC; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">";
	
		$celloutput='';
		
		switch($item['product_type']){
            case 'newcatchall-cornice':
            case 'newcatchall-drapery':
            case 'newcatchall-swtmisc':
            case 'newcatchall-valance':
                $celloutput .= nl2br(fixcharacters($item['metadata']['specs'])).", ";
                	/**PPSASCRUM-13 start **/
			/* PPSASCRUM-359: start */
			$celloutput .= ($item['metadata']['linings_id'] != 'none') ? $alllinings[$item['metadata']['linings_id']]." Lining, " : '';
			/* PPSASCRUM-359: end */
			/**PPSASCRUM-13 end **/
            break;
            case 'custom':
                $celloutput .= nl2br(fixcharacters($item['metadata']['specs'])).", ";
            break;
		}

		
		if(($item['product_type'] == 'window_treatments' && (preg_match("#Cornice#i",$item['metadata']['wttype']) || preg_match("#Valance#i",$item['metadata']['wttype']))) || ($item['product_type']=="calculator" && ($item['metadata']['calculator-used']=='straight-cornice' || $item['metadata']['calculator-used']=='box-pleated'))){
			
				/**PPSASCRUM-13 start **/
			$celloutput .= ($item['metadata']['linings_id'] != 'none') ? $alllinings[$item['metadata']['linings_id']]." Lining, " : '';
			/**PPSASCRUM-13 end **/
		}


	    /* PPSASCRUM-56: start */
	    /* PPSASCRUM-305: start */
// 		if(($item['product_type'] == 'window_treatments' && $item['metadata']['wttype'] == "Pinch Pleated Drapery") || ($item['product_type']=="calculator" && ($item['metadata']['calculator-used']=='pinch-pleated' || $item['metadata']['calculator-used']=='new-pinch-pleated'))){
	    /* PPSASCRUM-384: start */
	    if(($item['product_type'] == 'window_treatments' && $item['metadata']['wttype'] == "Pinch Pleated Drapery") || 
	        ($item['product_type']=="calculator" && ($item['metadata']['calculator-used']=='pinch-pleated' || $item['metadata']['calculator-used']=='new-pinch-pleated' || 
	        $item['metadata']['calculator-used'] == "ripplefold-drapery" || $item['metadata']['calculator-used'] == "accordiafold-drapery"))){
        /* PPSASCRUM-384: end */
        /* PPSASCRUM-305: end */
		/* PPSASCRUM-56: end */
			
			/**PPSASCRUM-13 start **/
			$celloutput .= ($item['metadata']['linings_id'] != 'none') ? $alllinings[$item['metadata']['linings_id']]." Lining, " : '';
			/**PPSASCRUM-13 end **/
			$celloutput .= $item['metadata']['fullness']."X Fullness, ";
			$celloutput .= $item['metadata']['default-return']."&quot; Return, ";
			$celloutput .= $item['metadata']['default-overlap']."&quot; Overlap, ";

			if($item['metadata']['railroaded'] == '1'){
				$celloutput .= "Fabric Railroaded, ";
			}else{
				$celloutput .= "Fabric Vertically Seamed, ";
			}

			if($item['product_type']=="calculator" && $item['metadata']['calculator-used']=='pinch-pleated'){

				

				//check advanced options and display if they exist
				if(isset($item['metadata']['grommet-stiffener']) && $item['metadata']['grommet-stiffener'] == '1'){
					$celloutput .= "Grommet Stiffener, ";
				}

				if(isset($item['metadata']['trim-sewn-on']) && $item['metadata']['trim-sewn-on'] == '1'){
					$celloutput .= "Sewn-On Trim, ";
				}


				if(isset($item['metadata']['covered-buttons']) && $item['metadata']['covered-buttons'] == '1'){
					$celloutput .= "Covered Buttons, ";
				}

				if(isset($item['metadata']['weights']) && $item['metadata']['weights'] == '1'){
					$celloutput .= "Sewn-In Weights, ";
				}

				if(isset($item['metadata']['rings']) && $item['metadata']['rings'] == '1'){
					$celloutput .= "Sewn-On Rings, ";
				}

				if(isset($item['metadata']['tassels']) && $item['metadata']['tassels'] == '1'){
					$celloutput .= "Sewn-On Tassels, ";
				}

				if(isset($item['metadata']['slanted-hems']) && $item['metadata']['slanted-hems'] == '1'){
					$celloutput .= "Slanted Hems, ";
				}

				if(isset($item['metadata']['chain-weight']) && $item['metadata']['chain-weight'] == '1'){
					$celloutput .= "Chain Weight, ";
				}

				if(isset($item['metadata']['vertical-contrast-banding']) && $item['metadata']['vertical-contrast-banding'] != 'None'){
					$celloutput .= "Vertical Contrast Banding (".$item['metadata']['vertical-contrast-banding']."), ";
				}

				if(isset($item['metadata']['horizontal-contrast-banding']) && $item['metadata']['horizontal-contrast-banding'] != 'None'){
					$celloutput .= "Horizontal Contrast Banding (".$item['metadata']['horizontal-contrast-banding']."), ";
				}

			}

			switch($item['metadata']['hardware']){
				case "none":
				case "No Hardware":
					$celloutput .= "No Hardware, ";
				break;
				case "basic":
					$celloutput .= "Basic Hardware, ";
				break;
				case "decorative":
					$celloutput .= "Decorative Hardware, ";
				break;
			}

		}



		if($item['product_type']=="calculator" && $item['metadata']['calculator-used']=="box-pleated"){
			if($item['metadata']['return']== '0'){
				$celloutput .= "No Return, ";
			}else{
				$celloutput .= $item['metadata']['return'].'&quot; Return, ';
			}


			if($item['metadata']['straight-banding']=='1'){
				$celloutput .= "Straight Banding, ";
			}


			if($item['metadata']['shaped-banding']=='1'){
				$celloutput .= "Shaped Banding, ";
			}


			if($item['metadata']['trim-sewn-on'] == '1'){
				$celloutput .= "Sewn-On Trim, ";
			}

			if($item['metadata']['welt-covered-in-fabric'] == '1'){
				$celloutput .= "Welt Covered in Fabric, ";
			}

			if($item['metadata']['contrast-fabric-inside-pleat'] == '1'){
				$celloutput .= "Contrast Fabric Inside Pleat, ";
			}



			if($item['metadata']['railroaded'] == '1'){
				$celloutput .= "Fabric Railroaded, ";
			}else{
				$celloutput .= "Fabric Vertically Seamed, ";
			}


			if(isset($item['metadata']['vert-repeat']) && floatval($item['metadata']['vert-repeat']) >0){
				$celloutput .= "Matched Repeat (".$item['metadata']['vert-repeat']."&quot;), ";
			}

		}


		if($item['product_type']=="bedspreads" || ($item['product_type']=="calculator" && ($item['metadata']['calculator-used']=="bedspread" || $item['metadata']['calculator-used']=="bedspread-manual"))){
			
			$celloutput .= $item['metadata']['style'].", ";

			if(isset($item['metadata']['quilted'])){
				if($item['metadata']['quilted']=='1'){
					$celloutput .= "Quilted, ";
				}else{
					$celloutput .= "Unquilted, ";
				}
			}

			
			if(isset($item['metadata']['quilted'])){
				if($item['metadata']['quilted']=='1'){

					if(isset($item['metadata']['quilting-pattern']) && strlen(trim($item['metadata']['quilting-pattern'])) >0){
						$celloutput .= $item['metadata']['quilting-pattern'].", ";
					}

					if(isset($item['metadata']['matching-thread']) && $item['metadata']['matching-thread'] == '1'){
						$celloutput .= "Matching Thread, ";
					}

					$celloutput .= $item['fabricdata']['bs_backing_material']." Backing, ";


				}
			}




			if($item['product_type']=="calculator" && $item['metadata']['calculator-used']=="bedspread"){
				if($item['metadata']['railroaded'] == '1'){
					$celloutput .= "Fabric Railroaded, ";
				}else{
					$celloutput .= "Fabric Up-the-Roll, ";
				}
			}else{
				foreach($allFabrics as $fabric){
					if($fabric['id']==$item['metadata']['fabricid']){
						if($fabric['railroaded'] == '1'){
							$celloutput .= "Fabric Railroaded, ";
						}else{
							$celloutput .= "Fabric Up-the-Roll, ";
						}
					}
				}
			}


			if(isset($item['metadata']['vertical-repeat']) && floatval($item['metadata']['vertical-repeat']) >0){
				$celloutput .= "Matched Repeat (".$item['metadata']['vertical-repeat']."&quot;), ";
			}

		}

	///////////////////
		if($item['product_type'] == 'bedspreads'){

			foreach($allFabrics as $fabric){
				if($fabric['id']==$item['metadata']['fabricid']){
					if(floatval($fabric['vertical_repeat']) >0){
						$celloutput .= "Matched Repeat (".$fabric['vertical_repeat']."&quot;), ";
					}
				}
			}


		}
	/////////////////////	


		if($item['product_type']=="cubicle_curtains"){


			if($item['metadata']['mesh'] == 'No Mesh' || $item['metadata']['mesh'] == '0'){
				$celloutput .= "NO MESH, ";
			}else{
				$celloutput .= "Mesh ".$item['metadata']['mesh']."&quot; ".$item['metadata']['mesh-color']." (MOM), ";
			}

			foreach($allFabrics as $fabric){
				if($fabric['id']==$item['metadata']['fabricid']){
					if($fabric['railroaded'] == '1'){
						$celloutput .= "Fabric Railroaded, ";
					}else{
						$celloutput .= "Fabric Vertically Seamed, ";
					}
				}
			}



			foreach($allFabrics as $fabric){
				if($fabric['id']==$item['metadata']['fabricid']){
					if(floatval($fabric['vertical_repeat']) >0){
						$celloutput .= "Matched Repeat (".$fabric['vertical_repeat']."&quot;), ";
					}
				}
			}
			

		}else{
			if(isset($item['metadata']['mesh-type']) && $item['metadata']['mesh-type']=='None'){
				$celloutput .= "NO MESH, ";
			}elseif(isset($item['metadata']['mesh-type']) && $item['metadata']['mesh-type']=='Integral Mesh'){
				$celloutput .= "INTEGRAL MESH, ";
			}else{
				if(isset($item['metadata']['mesh']) && floatval($item['metadata']['mesh']) >0){
					$celloutput .= "Mesh ".$item['metadata']['mesh']."&quot;";
					if(isset($item['metadata']['mesh-color']) && strlen(trim($item['metadata']['mesh-color'])) >0){
							$celloutput .= " ".$item['metadata']['mesh-color']."";
						//PPSASCRUM-213 start
						$celloutput .= " (".str_replace(" Mesh","",
                                    $item['metadata']['mesh-type'] ).")";
                        //PPSASCRUM-213 end
					}
					$celloutput .= ", ";
				}
			}
		}
		

		if($item['metadata']['calculator-used'] == "cubicle-curtain"){

			if($item['metadata']['railroaded'] == '1'){
				$celloutput .= "Fabric Railroaded, ";
			}else{
				$celloutput .= "Fabric Vertically Seamed, ";
			}


			if($item['metadata']['liner'] == '1'){
				$celloutput .= "Liner, ";
			}

			if($item['metadata']['nylon-mesh'] == '1'){
				$celloutput .= "Nylon Mesh, ";
			}

			if($item['metadata']['angled-mesh'] == '1'){
				$celloutput .= "Angled Mesh, ";
			}

			if(isset($item['metadata']['mesh-frame']) && $item['metadata']['mesh-frame'] != 'No Frame'){
				$celloutput .= "Mesh Frame: ".$item['metadata']['mesh-frame'].", ";
			}


			if($item['metadata']['hidden-mesh'] == '1'){
				$celloutput .= "Hidden Mesh, ";
			}

			if(isset($item['metadata']['snap-tape']) && $item['metadata']['snap-tape'] != 'None'){
				$celloutput .= $item['metadata']['snap-tape']." Snap Tape (".$item['metadata']['snaptape-lf']."), ";
			}


			if(isset($item['metadata']['velcro']) && $item['metadata']['velcro'] != 'None'){
				$celloutput .= $item['metadata']['velcro']." Velcro (".$item['metadata']['velcro-lf']." LF), ";
			}

			if($item['metadata']['weights'] == '1'){
				$celloutput .= $item['metadata']['weight-count']." Weights, ";
			}

			if($item['metadata']['magnets'] == '1'){
				$celloutput .= $item['metadata']['magnet-count']." Magnets, ";
			}


			if($item['metadata']['banding'] == '1'){
				$celloutput .= "Banding, ";
			}

			if($item['metadata']['buttonholes'] == '1'){
				$celloutput .= $item['metadata']['buttonhole-count']." Buttonholes, ";
			}


			if(isset($item['metadata']['vertical-repeat']) && floatval($item['metadata']['vertical-repeat']) >0){
				$celloutput .= "Matched Repeat (".$item['metadata']['vertical-repeat']."&quot;), ";
			}

		}


		if($item['product_type'] == 'window_treatments' && preg_match("#Cornice#i",$item['metadata']['wttype'])){

			$celloutput .= $item['metadata']['return']."&quot; Return, ";

			$welts='';
			if($item['metadata']['welt-top']=='1' && $item['metadata']['welt-bottom']=='1'){
				$welts = "Welts Top &amp; Bottom, ";
			}else{
				if($item['metadata']['welt-top']=='1'){
					$welts = "Welt Top Only, ";
				}elseif($item['metadata']['welt-bottom'] == '1'){
					$welts = "Welt Bottom Only, ";
				}
			}
			if($welts != ''){
				$celloutput .= $welts;
			}else{
				$celloutput .= "No Welts, ";
			}

			if($item['fabricdata']['railroaded'] == '1'){
				$celloutput .= "Fabric Railroaded, ";
			}else{
				$celloutput .= "Fabric Vertically Seamed, ";
			}


			if(isset($item['fabricdata']['vertical_repeat']) && floatval($item['fabricdata']['vertical_repeat']) >0){
				$celloutput .= "Matched Repeat (".$item['fabricdata']['vertical_repeat']."&quot;), ";
			}

		}




		if($item['product_type'] == 'window_treatments' && preg_match("#Valance#i",$item['metadata']['wttype'])){

			$celloutput .= $item['metadata']['return']."&quot; Return, ";


			if($item['fabricdata']['railroaded'] == '1'){
				$celloutput .= "Fabric Railroaded, ";
			}else{
				$celloutput .= "Fabric Vertically Seamed, ";
			}


			if(isset($item['fabricdata']['vertical_repeat']) && floatval($item['fabricdata']['vertical_repeat']) >0){
				$celloutput .= "Matched Repeat (".$item['fabricdata']['vertical_repeat']."&quot;), ";
			}

		}




		if($item['metadata']['calculator-used']=="straight-cornice"){
			
			if($item['metadata']['return']== '0'){
				$celloutput .= "No Return, ";
			}else{
				$celloutput .= $item['metadata']['return'].'&quot; Return, ';
			}
			
			$welts='';
			if($item['metadata']['welt-top']=='1' && $item['metadata']['welt-bottom']=='1'){
				$welts = "Welts Top &amp; Bottom, ";
			}else{
				if($item['metadata']['welt-top']=='1'){
					$welts = "Welt Top Only, ";
				}elseif($item['metadata']['welt-bottom'] == '1'){
					$welts = "Welt Bottom Only, ";
				}
			}
			if($welts != ''){
				$celloutput .= $welts;
			}else{
				$celloutput .= "No Welts, ";
			}


			if($item['metadata']['individual-nailheads'] == '1'){
				$celloutput .= "Individual Nailheads, ";
			}

			if($item['metadata']['nailhead-trim'] == '1'){
				$celloutput .= "Nailhead Trim, ";
			}

			if($item['metadata']['covered-buttons'] == '1'){
				$celloutput .= $item['metadata']['covered-buttons-count']." Covered Buttons, ";
			}


			if($item['metadata']['horizontal-straight-banding'] == '1'){
				$celloutput .= $item['metadata']['horizontal-straight-banding-count']." H Straight Banding, ";
			}

			if($item['metadata']['horizontal-shaped-banding'] == '1'){
				$celloutput .= $item['metadata']['horizontal-shaped-banding-count']." H Shaped Banding, ";
			}

			if($item['metadata']['extra-welts'] == '1'){
				$celloutput .= $item['metadata']['extra-welts-count']." Extra Welts, ";
			}


			if($item['metadata']['trim-sewn-on'] == '1'){
				$celloutput .= $item['metadata']['trim-lf']." LF Sewn-On Trim, ";
			}

			if($item['metadata']['tassels'] == '1'){
				$celloutput .= $item['metadata']['tassels-count']." Tassels, ";
			}

			if($item['metadata']['drill-holes'] == '1'){
				$celloutout .= $item['metadata']['drill-hole-count']." Drill Holes, ";
			}




			if($item['metadata']['railroaded'] == '1'){
				$celloutput .= "Fabric Railroaded, ";
			}else{
				$celloutput .= "Fabric Vertically Seamed, ";
			}


			if(isset($item['metadata']['vertical-repeat']) && floatval($item['metadata']['vertical-repeat']) >0){
				$celloutput .= "Matched Repeat (".$item['metadata']['vertical-repeat']."&quot;), ";
			}
		}
		
		echo substr($celloutput,0,(strlen($celloutput)-2));

		/* PPSASCRUM-367: start */
		if (
			$item["product_type"] == "newcatchall-valance" || 
			$item["product_type"] == "newcatchall-cornice" || 
			$item['metadata']['calculator-used']=="straight-cornice" || 
			$item['metadata']['calculator-used']=='box-pleated'
		) {
			if ($item['metadata']["hinged"] == "1") {
				echo "<br><b>" .
					"Hinges </b>= Yes (" .
					$item['metadata']["hingecount"] .
					")";
			} else {
				echo "<br><b>" . "Hinges </b>= No (0)";
			}
		}
		/* PPSASCRUM-367: end */
		
		echo "</span></td>";
	}
	echo "<td width=\"6%\" align=\"center\" style=\"border-left:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">";
	if($item['override_active'] == 1){
		echo "\$".number_format($item['override_price'],2,'.',',');
	}else{
		if(floatval($item['pmi_adjusted']) >0){
			echo "\$".number_format($item['pmi_adjusted'],2,'.',',');
		}else{
			echo "---";
		}
	}
	echo "</span></td>
	<td width=\"6%\" align=\"center\" style=\"border-left:1px solid #000000; border-right:1px solid #000000; border-bottom:".$bottomborder."; font-size:7px;\" valign=\"top\"><span style=\"font-size:7px;\">";
	//if(floatval($item['extended_price']) >0){
		echo "\$".number_format(floatval($item['extended_price']),2,'.',',');
	//}else{
		//echo "---";
	//}
	echo "</span></td>
	</tr>";

	//line notes
	$visiblenotedisplaycount=0;
	if(count($item['notes']) >0){

		foreach($item['notes'] as $noteRow){
			if($noteRow['visible_to_customer'] == '1'){
				$visiblenotedisplaycount++;

				if($visiblenotedisplaycount == $visiblenotecount){
					$bottombordercolorcode='#000000';
				}else{
					$bottombordercolorcode='#CCCCCC';
				}

				echo "<tr bgcolor=\"".$linebgcolor."\" nobr=\"true\">
				<td colspan=\"4\" align=\"right\" style=\"border-left:1px solid #000000; border-bottom:1px solid ".$bottombordercolorcode.";font-size:6px;\">"." from ".$noteRow['name'].":</td>
				<td colspan=\"7\" align=\"left\" style=\"border-left:1px solid #CCCCCC; border-right:1px solid #000000; border-bottom:1px solid ".$bottombordercolorcode.";font-size:6px;\">".nl2br(fixcharacters($noteRow['message']))."</td>
				</tr>";
			}
		}

	}
}
}

echo "</tbody></table>

<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr>
		<td width=\"40%\" valign=\"top\">";

//global notes loop
		$numVisibleGlobalNotes=0;
		$globalNotesOut='';
		foreach($globalNotes as $globalNote){
			if($globalNote['appear_on_pdf'] == 1){
				$numVisibleGlobalNotes++;
				$globalNotesOut .= '<div style="font-size:9px;">'.nl2br($globalNote['note_text']).'</div>';
			}
		}

		if($numVisibleGlobalNotes > 0){
			echo "<div style=\"font-size:9px;\"><b>GLOBAL NOTES:</b>";
			echo $globalNotesOut;
			echo "</div><br>";
		}

		echo "</td>
		<td width=\"24%\">&nbsp;</td>
		<td width=\"36%\" valign=\"top\">
			<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\">
				<tr bgcolor=\"#333333\">
					<td width=\"56%\" align=\"left\" style=\"border-bottom:1px solid #000000; border-left:1px solid #000000; color:#FFF; font-weight:bold; font-size:8px;\">Sub-Total</td>
					<td width=\"44%\" align=\"right\" style=\"border-bottom:1px solid #000000; border-right:1px solid #000000; border-left:1px solid #000000; color:#FFF; font-weight:bold; font-size:8px; text-align:right;\">\$".number_format($subTotal,2,'.',',')."</td>
				</tr>
				
				
				
				<tr bgcolor=\"#555555\">
					<td width=\"56%\" align=\"left\" style=\"border-bottom:1px solid #000000; border-left:1px solid #000000; color:#FFF; font-weight:bold; font-size:8px;\">Shipping &amp; Handling</td>
					<td width=\"44%\" align=\"right\" style=\"border-bottom:1px solid #000000; border-right:1px solid #000000; border-left:1px solid #000000; color:#FFF; font-weight:bold; font-size:8px; text-align:right;\">TBD</td>
				</tr>
				";

				/*
				if()
					echo "<tr bgcolor=\"#121212\">
					<td width=\"56%\" align=\"left\" style=\"border-bottom:1px solid #000000; border-left:1px solid #000000; color:#FFF; font-weight:bold; font-size:8px;\">Quote Total</td>
					<td width=\"44%\" align=\"right\" style=\"border-bottom:1px solid #000000; border-right:1px solid #000000; border-left:1px solid #000000; color:#FFF; font-weight:bold; font-size:8px; text-align:right;\">\$".number_format($quoteData['quote_total'],2,'.',',')."</td>
					</tr>";
				}
				*/
			echo "</table>
		</td>
	</tr>
</table>";



if(isset($_GET['debug']) && $_GET['debug'] == '1'){
	exit;
}
?>