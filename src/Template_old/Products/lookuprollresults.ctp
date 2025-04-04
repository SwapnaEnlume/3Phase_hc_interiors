<?php

if(isset($yardagematches)){
	echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"1\">
	<thead>
	<tr>
	<th width=\"15%\">Fabric</th>
	<th width=\"55%\">Roll Details</th>
	<th width=\"10%\">Total Rolls</th>
	<th width=\"10%\">Total Yards</th>
	<th width=\"10%\">Actions</th>
	</tr>
	</thead>
	<tbody>";
	foreach($yardagematches as $match){
		echo "<tr>
		<td valign=\"top\">".$match['fabric_name']." ".$match['color']."</td>
		<td valign=\"top\"><table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\">
		<thead>
		<tr>
		<th>Roll</th>
		<th>Yards</th>
		<th>Location</th>
		<th>Quilting</th>
		<th>Received</th>
		<th>Work Order</th>
		<th>Notes</th>
		</tr>
		</thead>
		<tbody>";
		foreach($match['allrolls'] as $rollrow){
			if(($yardageconfig=='specific' && in_array($rollrow['id'],$matchingRolls)) || $yardageconfig=='any'){
				echo "<tr><td>".$rollrow['roll_number']." <a href=\"/products/getfabricrolltag/".$rollrow['id']."\" target=\"_blank\"><img src=\"/img/printlabel.png\" /></a></td>
				<td>".$rollLiveYards[$rollrow['id']]."</td>
				<td>";
				foreach($allWarehouseLocations as $location){
					if($location['id']==$rollrow['warehouse_location']){
						echo $location['location'];
					}
				}
				echo "</td>
				<td>";
				if($rollrow['quilted']=='1'){
					echo "Quilted<br>".$rollrow['quilting_pattern']." (".$rollrow['backing_material']." Backing)";
				}else{
					echo "Unquilted";
				}
				echo "</td>
				<td>".date("n/j/Y",$rollrow['date_received'])."</td>
				<td><a href=\"/\" target=\"_blank\">".$rollrow['work_order']."</a></td>
				<td>".$rollrow['notes']."</td>
				</tr>";

				//Location' , 'Quilted' , 'Received' , 'Workorder' , 'Notes' , 'Quilting Pattern', 'Backing'
			}
		}
		echo "</tbody></table></td>
		<td valign=\"top\">".$match['num_rolls']."</td>
		<td valign=\"top\">".$match['total_yards']."</td>
		<td valign=\"top\">&nbsp;</td>
		</tr>";
	}
	echo "</tbody></table>";
}

if(isset($resultRows)){
	echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"1\">
	<thead>
	<tr>
	<th>Fabric</th>
	<th># Yards</th>
	<th>Actions</th>
	</tr>
	</thead>
	<tbody>";
	foreach($resultRows as $match){
		echo "<tr>
		<td>".$match['material_id']."</td>
		<td></td>
		<td>&nbsp;</td>
		</tr>";
	}
	echo "</tbody></table>";
}
?>