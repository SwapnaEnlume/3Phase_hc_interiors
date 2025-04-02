<h2>Record Usage of <u><?php echo $fabricData['fabric_name']." - ".$fabricData['color']; ?></u></h2>
<hr>
<?php
echo $this->Form->create(null);

echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\">
<thead>
<tr>
<th>Line#</th>
<th>Roll#</th>
<th>Actual Yards</th>
</tr>
</thead>
<tbody>";

//print_r($allLines);

echo "<tr>
<td><select name=\"line_number\"><option value=\"0\" selected disabled>--Select a Line--</option>";

foreach($allLines as $lineid => $line){
	if($line['metadata']['fabricid'] == $fabricID){
		echo "<option value=\"".$line['line_number']."\">".$line['line_number']." (";
		echo (floatval($line['metadata']['yds-per-unit']) * floatval($line['metadata']['qty']));
		echo ")</option>";
	}
}
	echo "</select></td>";
	echo "<td><select name=\"rollnumber\"><option value=\"0\" selected disabled>--Select a Roll--</option>";
	
	foreach($allRolls as $roll){
		if(floatval($roll['yards_on_roll']) > 0.00){
			echo "<option value=\"".$roll['id']."\" data-yards=\"".$roll['yards_on_roll']."\">".$roll['roll_number']." (".$roll['yards_on_roll'].")</option>";
		}
	}

	echo "</select></td>
	<td><input type=\"number\" step=\"any\" name=\"actualyards\" placeholder=\"Actual Yards\" /></td>";

echo "</tbody>
</table>";
echo "<input type=\"hidden\" name=\"startingyards\" value=\"\" />";
echo $this->Form->end();

echo "<button type=\"button\" onclick=\"submitform()\">Submit Usage</button> <button type=\"button\" onclick=\"submitform()\">Submit and Enter More Usage</button>";
echo "<Br><Br><button type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";
?>
<script>
function submitform(){
	var yardsonroll=$('select[name=rollnumber] option:selected').attr('data-yards');
	$('input[name=startingyards]').val(yardsonroll);
	$('form').submit();
}
</script>
<style>
#totalyardstoorder{ font-size:20px; padding:5px; width:85px; }
fieldset ul li{ padding:5px; }
body{     font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; }
h1{ text-align:center; font-size:22px; text-decoration: underline; color:#1D3292 }
h2{ text-align:center; }
h3{ text-align:center; margin:0; }
h4{ text-align:center; margin:15px 0 10px 0; }
h3 u{ color:#A62426; }
h4 u{ color:#A62426; }
button[type=submit]{ background:#2F2E94; color:#FFF; border:2px solid #000; padding:15px 30px; font-weight:bold; font-size:16px; cursor: pointer; float:right; }
button.cancel{ background:#780204; color:#FFF; border:1px solid #000; padding:10px 20px; font-size:12px; font-weight:bold; float:left; cursor:pointer; }
</style>