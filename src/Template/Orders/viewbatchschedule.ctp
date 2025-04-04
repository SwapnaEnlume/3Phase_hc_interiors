<?php
$dateFormatted=$date;

?>
    <style>
    /*table#schedulebuild thead tr:nth-of-type(1) th:nth-of-type(1){
        width:10% !important;
    }
    
    table#schedulebuild thead tr:nth-of-type(1) th:nth-of-type(2){
        width:10% !important;
    }
    
    table#schedulebuild thead tr:nth-of-type(1) th:nth-of-type(3){
        width:80% !important;
    }
    
    table#schedulebuild thead tr:nth-of-type(2) th:nth-of-type(1){
        width:8% !important;
    }
    
    table#schedulebuild thead tr:nth-of-type(2) th:nth-of-type(2){
        width:8% !important;
    }
    
    table#schedulebuild thead tr:nth-of-type(2) th:nth-of-type(3){
        width:8% !important;
    }
    
    table#schedulebuild thead tr:nth-of-type(2) th:nth-of-type(4){
        width:8% !important;
    }
    
    table#schedulebuild thead tr:nth-of-type(2) th:nth-of-type(5){
        width:20% !important;
    }
    
    table#schedulebuild thead tr:nth-of-type(2) th:nth-of-type(6){
        width:28% !important;
    }
    
    
    table#schedulebuild tbody tr td:nth-of-type(1){
        width:10% !important;
    }
    
    table#schedulebuild tbody tr td:nth-of-type(2){
        width:10% !important;
    }
    
    
    table#schedulebuild tbody tr td:nth-of-type(3){
        width:8% !important;
    }
    table#schedulebuild tbody tr td:nth-of-type(4){
        width:8% !important;
    }
    table#schedulebuild tbody tr td:nth-of-type(5){
        width:8% !important;
    }
    table#schedulebuild tbody tr td:nth-of-type(6){
        width:8% !important;
    }
    table#schedulebuild tbody tr td:nth-of-type(7){
        width:20% !important;
    }
    table#schedulebuild tbody tr td:nth-of-type(8){
        width:28% !important;
    }
    */
    
    @media print{
        @page{
            size: landscape;
            margin: 12mm 5mm 12mm 0mm;
        }
        body{
            zoom:0.70;
            
        }
        body header,div#welcomebar,div#printbuttonrow{ display:none; }
        
     }
    </style>
    <?php
    /*echo "<table style=\"width:1400px;margin:0 auto;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"batchschedheader\">
    <tr>
    <td width=\"20%\" valign=\"middle\" style=\"vertical-align:middle\">
    <img src=\"https://orders.hcinteriors.com/barcodes/genbarcode.php?value=".urlencode($orderData['order_number'])."\" style=\"width:150px; height:65px !important; \" />
    </td>
    <td width=\"60%\" valign=\"middle\" style=\"vertical-align:middle;\" align=\"center\"><h2 style=\"text-align:center; font-size:28px; margin:0; color:blue;\">WO# ".$orderData['order_number']." VIEW SHERRY <u>BATCH ID# ".$thisBatch['id']."</u>";
    if(!is_null($orderData['due']) && intval($orderData['due']) > 1000){
	    echo '<br>SHIP-BY DATE: '.date('n/j/Y',$orderData['due']);
    }
    echo "</h2></td><td width=\"20%\" valign=\"middle\">&nbsp;</td>
    </tr></table>";*/
    
echo "<div id=\"tablewrap\"><table id=\"schedulebuild\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";

echo "<thead>
<tr style=\"border:0 !important;background:#FFF !important; color:#000 !important;\">
<th width=\"15%\" valign=\"middle\" style=\"vertical-align:middle;border:0 !important;background:#FFF !important; color:#000 !important; \">
    <img src=\"https://orders.hcinteriors.com/barcodes/genbarcode.php?value=".urlencode($orderData['order_number'])."\" style=\"width:150px; height:65px !important; \" />
</th>
<th width=\"85%\" colspan=\"7\" valign=\"middle\" style=\"vertical-align:middle;border:0 !important;background:#FFF !important; color:#000 !important;  padding-right:10% !important;\">
    <h2 style=\"text-align:center; font-size:28px; margin:0; color:blue;\">WO# ".$orderData['order_number']." VIEW SHERRY <u>BATCH ID# ".$thisBatch['id']."</u>";
    if(!is_null($orderData['due']) && intval($orderData['due']) > 1000){
	    echo '<br>SCHEDULED DATE: '.date('n/j/Y',strtotime($thisBatch['date'].' 12:00:00')).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SHIP-BY DATE: '.date('n/j/Y',$orderData['due']);
    }
    echo "</h2>
</th>
</tr>
<tr>
<th width=\"8%\" rowspan=\"3\" valign=\"middle\">WO LINE</th>
<th width=\"2%\" rowspan=\"3\" valign=\"middle\">SO LINE</th>
<th width=\"10%\" rowspan=\"3\" valign=\"middle\">QTY THIS<br>BATCH</th>
<th width=\"80%\" colspan=\"5\">DESCRIPTION</th>
</tr>
<tr>
<th width=\"10%\">FABRIC</th>
<th width=\"10%\">COLOR</th>
<th width=\"10%\">DIMENSIONS</th>
<th width=\"10%\">PRODUCT</th>
<th width=\"40%\">ITEM DESCRIPTION</th>
</tr>
</thead>";

echo "<tbody>";

$i=1;
foreach($lineItems as $itemID => $itemData){
   // print_r($itemData['this_batch']);
//	if($itemData['data']['enable_tally'] == 1){
        if($itemData['this_batch'] > 0){
        	echo "<tr data-order-line-id=\"".$itemData['order_item_id']."\" data-qtyordered=\"".$itemData['data']['qty']."\" data-previouslyscheduled=\"".$itemData['data']['this_batch']."\">
        	<td width=\"8%\" style=\"vertical-align:middle;\">".$itemData['data']['wo_line_number'];
        	echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"https://orders.hcinteriors.com/barcodes/genbarcode.php?value=".urlencode($itemData['data']['wo_line_number'])."\" style=\"width:140px; height:65px !important;\" />";
        	
        	echo "</td>
        	<td width=\"2%\" style=\"vertical-align:middle;\">".$itemData['data']['line_number'];
        //	echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"https://orders.hcinteriors.com/barcodes/genbarcode.php?value=".urlencode($itemData['data']['so_line_number'])."\" style=\"width:85px; height:65px !important;\" />";
        	
        	echo "</td>
        	<td width=\"10%\" class=\"thisbatch\" style=\"vertical-align:middle;\">";
        	    echo $itemData['this_batch'];
        
        	echo "</td>
        	<td width=\"10%\" style=\"vertical-align:middle;\">";
        	/* PPSASCRUM-160: start */
			if(isset($itemData['metadata']['com-fabric']) && $itemData['metadata']['com-fabric'] == 1) {
				echo 'COM &nbsp;';
			}
			/* PPSASCRUM-160: end */
        	echo $itemData['fabricdata']['fabric_name']."</td>
        	<td width=\"10%\" style=\"vertical-align:middle;\">";
        	if(isset($itemData['fabricdata']['id'])){
        	    echo "<a href=\"/files/fabrics/".$itemData['fabricdata']['id']."/".$itemData['fabricdata']['image_file']."\" style=\"color:blue;\" target=\"_blank\">";
        	}
        	echo $itemData['fabricdata']['color'];
        	if(isset($itemData['fabricdata']['id'])){
        	    echo "</a>";
        	}
        	
        	echo "</td>
        	<td width=\"10%\" style=\"vertical-align:middle;\">";
        	
        	if(isset($itemData['metadata']['width']) && isset($itemData['metadata']['length']) && strlen($itemData['metadata']['width']) > 0 && strlen($itemData['metadata']['length']) > 0){
        	    echo $itemData['metadata']['width']." x ".$itemData['metadata']['length'];
        	}elseif(isset($itemData['metadata']['face']) && isset($itemData['metadata']['height']) && strlen($itemData['metadata']['face']) > 0 && strlen($itemData['metadata']['height']) > 0){
        	    echo $itemData['metadata']['face']." x ".$itemData['metadata']['height'];
        	}elseif(isset($itemData['metadata']['rod-width']) && isset($itemData['metadata']['length']) && strlen($itemData['metadata']['rod-width']) > 0 && strlen($itemData['metadata']['length']) > 0){
        	    echo "Rod: ".$itemData['metadata']['rod-width']." x ".$itemData['metadata']['length'];
        	}else{
        	    echo "&nbsp;";
        	}
        	echo "</td>
        	<td width=\"10%\" style=\"vertical-align:middle;\">";
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
        		case 'track_systems':
        		    echo "TRACK";
        		break;
        		case 'custom':
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
        		    //echo "Catch-All";
        		    echo $itemData['data']['title'];
        		break;
        	}
        	echo "</td>";
        	echo "<td width=\"40%\" style=\"vertical-align:middle;\">";
        	switch($itemData['data']['product_type']){
        	    case 'track_systems':
        	        echo $itemData['data']['title'];
        	   break;
        	   default:
        	       echo $itemData['data']['description'];
        	   break;
        	}
        	echo "</td>";
        	
        	echo "</tr>";
        	$i++;
        }

	    
	//}
}

echo "</tbody>";
echo "</table></div>";

echo "<div style=\"position:relative;\">";


echo "<div id=\"schedulealertboxwrap\"><div id=\"schedulealertbox\"><img src=\"/img/alert.png\" /> Selected date occurs after<br />the order's Ship-By Date</div></div>";

?>
<style>
div#schedulealertboxwrap{ 
	<?php 
	if((strtotime($thisBatch['date'].' 18:00:00') <= strtotime(date('Y-m-d',$orderData['due']).' 18:00:00')) || !is_null($orderData['due']) || intval($orderData['due']) < 1000){
	?>
	display:none;
	<?php 
	}else{
	?>
	display:block;
	<?php
	}
	?>
	text-align:right;
	margin-top:10px;
	padding-bottom:55px; 
}
div#schedulealertbox{ display:inline-block; background:#FF6A00; padding:8px 20px; border:2px solid #FF5A5B; color:#000; font-weight:bold; font-size:14px; text-align:center; }


span.greenhighlighted{ background:rgba(0,255,0,0.2); padding:3px; }
	
#content div.row{ max-width:none !important; }
#packslipnumberwrap div.input label{ display:inline-block !important; width:30%; text-align:right; padding-right:15px; font-weight: bold; }
#packslipnumberwrap div.input input{ display:inline-block !important; width:38%; text-align:left; margin-right:20%; }
	
form{ text-align: center; padding:0 0 35px 0; width:1400px; margin:0 auto; }
table#schedulebuild{ width:1400px !important; margin:0 auto; }
table#schedulebuild thead tr{ background:#2345A0; }
table#schedulebuild thead tr th{ color:#FFF; }
table#schedulebuild th,
table#schedulebuild td{ padding:5px !important; text-align:center; }

table#schedulebuild > tbody > tr > td:nth-of-type(6),
table#schedulebuild > tbody > tr > td:nth-of-type(7),
table#schedulebuild > tbody > tr > td:nth-of-type(8),
table#schedulebuild > tbody > tr > td:nth-of-type(9)
{ font-size:11px !important; }

table#schedulebuild tbody tr{ background:#FFFFFF; }

table#schedulebuild tr{ border-bottom:inherit !important; }
	
table#schedulebuild tbody tr.allcompleted{ background: #5DA654 !important; }
table#schedulebuild tbody tr.allcompleted input{ background:rgba(255,255,255,0.4) !important; }
table#schedulebuild tbody tr.allcompleted td{ color:#FFFFFF !important; }


table#schedulebuild tbody td:nth-of-type(1),
table#schedulebuild tbody td:nth-of-type(2),
table#schedulebuild tbody td:nth-of-type(3),
table#schedulebuild tbody td:nth-of-type(4){ font-size:16px; }
	
#selectalllines{ margin:0 0 0 0; }


.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight{ border:1px solid #c5c5c5; background:#f6f6f6; color:#454545; }

.ui-datepicker-current-day a{
	background:#007fff; border:1px solid #003eff; color:#FFF;
}

	
.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover{
	border: 1px solid #003eff !important;
	background: #007fff !important;
	font-weight: normal !important;
	color: #ffffff !important;
}
</style>