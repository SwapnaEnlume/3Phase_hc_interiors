<?php
echo "<h1>Bill Of Materials - Order ".$orderData['order_number']."</h1>";

$fabricTotals=array();
$calculatorFabricTotals=array();
$backingTotals=array();
$fillToatls=array();
$fabricWidthTotals=array();
$calculatorFabricWidthTotals=array();
$meshTotals=array();

foreach($lineItems as $lineID => $lineData){
	
	if(isset($lineData['metadata']['fabricid']) && is_numeric($lineData['metadata']['fabricid'])){
		if(!isset($fabricTotals[$lineData['metadata']['fabricid']])){
			$fabricTotals[$lineData['metadata']['fabricid']]=(floatval($lineData['metadata']['yds-per-unit']) * floatval($lineData['metadata']['qty']));
		}else{
			$fabricTotals[$lineData['metadata']['fabricid']]=($fabricTotals[$lineData['metadata']['fabricid']]+(floatval($lineData['metadata']['yds-per-unit']) * floatval($lineData['metadata']['qty'])));
		}

		if(!isset($fabricWidthTotals[$lineData['metadata']['fabricid']])){
			$fabricWidthTotals[$lineData['metadata']['fabricid']]=floatval($lineData['metadata']['total-widths']);
		}else{
			$fabricWidthTotals[$lineData['metadata']['fabricid']]=($fabricWidthTotals[$lineData['metadata']['fabricid']] + floatval($lineData['metadata']['total-widths']));
		}

		//calculator items only tally
		if($quoteItem['product_type'] == 'calculator'){
			if(!isset($calculatorFabricTotals[$lineData['metadata']['fabricid']])){
				$calculatorFabricTotals[$lineData['metadata']['fabricid']]=(floatval($lineData['metadata']['yds-per-unit']) * floatval($lineData['metadata']['qty']));
			}else{
				$calculatorFabricTotals[$lineData['metadata']['fabricid']]=($calculatorFabricTotals[$lineData['metadata']['fabricid']]+(floatval($lineData['metadata']['yds-per-unit']) * floatval($lineData['metadata']['qty'])));
			}
			
			if(!isset($calculatorFabricWidthTotals[$lineData['metadata']['fabricid']])){
				$calculatorFabricWidthTotals[$lineData['metadata']['fabricid']]=floatval($lineData['metadata']['total-widths']);
			}else{
				$calculatorFabricWidthTotals[$lineData['metadata']['fabricid']]=($calculatorFabricWidthTotals[$lineData['metadata']['fabricid']] + floatval($lineData['metadata']['total-widths']));
			}

		}

		//end calc only tally
		if(isset($this->request->query['highlightFabric'])){
			if($this->request->query['highlightFabric'] == $lineData['metadata']['fabricid']){
				$thisrowbg='highlightedfabric';
			}
		}

	}


	//mesh tallies
	if($quoteItem['product_type'] == 'cubicle_curtains'){
		//price list metas
		if(!isset($meshTotals[$lineData['metadata']['mesh']])){
			$meshTotals[$lineData['metadata']['mesh']." MOM Mesh"]=(floatval($lineData['metadata']['labor-billable'])*floatval($lineData['metadata']['qty']));
		}else{
			$meshTotals[$lineData['metadata']['mesh']." MOM Mesh"]=(floatval($meshTotals[$lineData['metadata']['mesh']." MOM Mesh"]) + (floatval($lineData['metadata']['labor-billable'])*floatval($lineData['metadata']['qty'])));
		}

	}elseif($quoteItem['product_type']=='calculator' && $lineData['metadata']['calculator-used'] == 'cubicle-curtain'){
		//calculator metas
		if(!isset($meshTotals[$lineData['metadata']['mesh']."\" (".$lineData['metadata']['mesh-color'].") ".$lineData['metadata']['mesh-type']])){
			$meshTotals[$lineData['metadata']['mesh']."\" (".$lineData['metadata']['mesh-color'].") ".$lineData['metadata']['mesh-type']]=(floatval($lineData['metadata']['labor-billable']) * floatval($lineData['metadata']['qty']));
		}else{
			$meshTotals[$lineData['metadata']['mesh']."\" (".$lineData['metadata']['mesh-color'].") ".$lineData['metadata']['mesh-type']]=(floatval($meshTotals[$lineData['metadata']['mesh']."\" (".$lineData['metadata']['mesh-color'].") ".$lineData['metadata']['mesh-type']]) + (floatval($lineData['metadata']['labor-billable']) * floatval($lineData['metadata']['qty'])));
		}

	}
}


//print_r($fabricTotals);exit;


echo "<table width=\"550\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
<thead>
<tr bgcolor=\"#000000\">
<th width=\"20%\" align=\"center\" style=\"font-size:9px; color:#FFFFFF;\">Fabric</th>
<th width=\"20%\" align=\"center\" style=\"font-size:9px; color:#FFFFFF;\">Color</th>
<th width=\"15%\" align=\"center\" style=\"font-size:9px; color:#FFFFFF;\">Yards</th>
<th width=\"45%\" align=\"left\" style=\"font-size:9px; color:#FFFFFF;\">Purchasing Notes</th>
</tr>
</thead>
<tbody>";


foreach($fabricTotals as $fabid => $fabtot){
	echo "<tr>
	<td width=\"20%\" align=\"left\" valign=\"top\" style=\"font-size:8px;\">";
	$thisfabriclookup=$fabricsDataArray[$fabid];
	if($thisfabriclookup['com_fabric'] == '1'){
		echo "COM ";
	}
	echo $thisfabriclookup['fabric_name']."</td>
	<td width=\"20%\" align=\"left\" style=\"font-size:8px;\">".$thisfabriclookup['color']."</td>
	<td width=\"15%\" align=\"center\" valign=\"top\" style=\"font-size:8px;\">".$fabtot."</td>
	<td width=\"45%\" align=\"left\" valign=\"top\" style=\"font-size:8px;\">";
	foreach($pNotes as $note){
	    if($note['type']=='fabric' && $note['material'] == $fabid){
	        echo "<div>-".$note['note']." <em>-";
	        foreach($allUsers as $user){
	            if($user['id'] == $note['user_id']){
	                echo $user['first_name']." ".substr($user['last_name'],0,1)." @ ".date('n/j/y g:iA',$note['time']);
	            }
	        }
	        echo "</em></div>";
	    }
	}
	echo "</td>
	</tr>";

}


		//backing and fill

		foreach($backingTotals as $backing => $totals){

			echo "<tr>

			<td width=\"40%\" colspan=\"2\" class=\"alignright\" valign=\"top\">".$backing."</td>

			<td width=\"15%\" classs=\"alignleft\" valign=\"top\">".$totals."</td>

			<td width=\"45%\" align=\"left\" valign=\"top\" style=\"font-size:8px;\">";
        	foreach($pNotes as $note){
        	    if($note['type']=='backing' && $note['material'] == $backing){
        	        echo "<div>-".$note['note']."</div>";
        	    }
        	}
        	echo "</td>

			</tr>";

		}

		foreach($fillTotals as $fill => $totals){

			echo "<tr>

			<td width=\"40%\" colspan=\"2\" class=\"alignright\" valign=\"top\">".$fill."</td>

			<td width=\"15%\" classs=\"alignleft\" valign=\"top\">".$totals."</td>

			<td width=\"45%\" align=\"left\" valign=\"top\" style=\"font-size:8px;\">";
        	foreach($pNotes as $note){
        	    if($note['type']=='fill' && $note['material'] == $fill){
        	        echo "<div>-".$note['note']."</div>";
        	    }
        	}
        	echo "</td>

			</tr>";

		}





		//echo "<tr><td colspan=\"4\">".print_r($meshTotals,1)."</td></tr>";

		foreach($meshTotals as $mesh => $total){

			echo "<tr>

			<td width=\"40%\" colspan=\"2\" class=\"alignright\">".$mesh."</td>

			<td width=\"15%\" class=\"alignleft\">".round(($total/3),2)."<br>(".$total." LF)</td>

			<td width=\"45%\" align=\"left\" valign=\"top\" style=\"font-size:8px;\">";
        	foreach($pNotes as $note){
        	    if($note['type']=='mesh' && $note['material'] == $mesh){
        	        echo "<div>-".$note['note']."</div>";
        	    }
        	}
        	echo "</td>

			</tr>";

		}

echo $out;

echo "</tbody>
</table>";
?>