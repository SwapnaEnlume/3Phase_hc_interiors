<?php

if($rollData['yards_received'] < 100 && $rollData['yards_received'] >= 10){
	$fixednumyards='0'.$rollData['yards_received'];
}else{
	$fixednumyards=$rollData['yards_received'];
}

echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
<tr>
	<td width=\"100%\" align=\"left\" colspan=\"2\" style=\"line-height:93%;\">
		<font size=\"9\"><b>Product Pattern</b></font><br>
		<font size=\"20\"><b>".strtoupper($fabricData['fabric_name'])."</b></font>
	</td>
</tr>
<tr>
	<td colspan=\"2\" align=\"left\" style=\"line-height:93%;\">
		<font size=\"9\"><b>Product Color</b></font><br>
		<font size=\"20\"><b>".strtoupper($fabricData['color'])."</b></font>
	</td>
</tr>
<tr>
	<td width=\"55%\" align=\"left\" style=\"line-height:93%;\">
		<font size=\"9\"><b>Location</b></font><br>
		<font size=\"20\"><b>".strtoupper($warehouseLocation)."</b></font>
	</td>
	<td width=\"45%\" align=\"center\" rowspan=\"4\">
		<font size=\"6\">Roll Info:</font><br>
		<img src=\"https://orders.hcinteriors.com/qrcode/genqrcode.php?value=".urlencode($rollData['bitly'])."\" />";
		if($rollData['com_or_mom'] == 'COM'){
			echo "<br><table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\"><tr bgcolor=\"#000000\"><td align=\"center\"><font size=\"16\" color=\"#FFFFFF\"><b>COM</b></font></td></tr></table>";
		}
		echo "
	</td>
</tr>
<tr>
	<td align=\"left\">
		<font size=\"7\"><b>Received Date</b></font><br>
		<font size=\"10\"><b>".date("m/d/Y",$rollData['date_received'])."</b></font>
	</td>
</tr>
<tr>
	<td align=\"left\">
		<font size=\"9\"><b>Yards</b></font><br>
		<font size=\"22\"><b>".$currentRollYards."</b></font>";
		if($currentRollYards != $rollData['yards_received']){
			echo " <font size=\"8\">MODIFIED ".date("n/j/y",$rollData['date_modified'])."</font>";
		}else{
			echo " <font size=\"8\">ORIGINAL</font>";
		}
	echo "</td>
</tr>
<tr>
	<td align=\"left\">
		<font size=\"7\"><b>Work Order#</b></font><br>
		<font size=\"10\"><b>".$rollData['work_order']."</b></font>
	</td>
</tr>
<tr>
	<td align=\"left\" align=\"center\" colspan=\"2\">
		<img src=\"https://orders.hcinteriors.com/barcodes/src/test5.php?type=128&code=".$rollData['roll_number']."\" />
	</td>
</tr>	
<tr>
	<td bgcolor=\"#000000\" align=\"center\" colspan=\"2\">
	<font size=\"30\" color=\"#FFFFFF\"><b>".$rollData['roll_number']."</b></font>
	</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\"><tr><td align=\"center\"><font size=\"7\">PRINTED ".date('n/j/y g:iA')." BY ".strtoupper($thisuser['first_name']." ".substr($thisuser['last_name'],0,1))."</font></td></tr></table>";
?>