<!-- src/Template/Settings/markuprules.ctp -->
<h1 class="pageheading">Fabric Markup Rules</h1>

<?php
echo $this->Form->create(false);


foreach($rulecalculators as $calc){
	
	echo "<h3 style=\"margin-top:45px; text-align:center;\">".strtoupper($calc['calculator'])."</h3>";
	echo "<table id=\"ruleslist\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<thead>
		<tr>
		<th width=\"40%\">Price Range</th>
		<th width=\"40%\">Yards Range</th>
		<th width=\"20%\">Markup</th>
		</tr>
		</thead>
		<tbody>";

	foreach($allrules as $rulerow){
		
		if($rulerow['calculator'] == $calc['calculator']){
			echo "<tr>
			<td width=\"40%\" valign=\"top\">\$";
			echo $this->Form->input("rulerow_".$rulerow['id']."_price_range_low",['label'=>false,'type'=>'number','step'=>'any','value'=>$rulerow['price_range_low']]);
			echo " - \$";
			echo $this->Form->input("rulerow_".$rulerow['id']."_price_range_high",['label'=>false,'type'=>'number','step'=>'any','value'=>$rulerow['price_range_high']]);
			echo "</td>
			<td width=\"40%\">";
			echo $this->Form->input("rulerow_".$rulerow['id']."_yds_range_low",['label'=>false,'type'=>'number','step'=>'any','value'=>$rulerow['yds_range_low']]);
			echo " - ";
			echo $this->Form->input("rulerow_".$rulerow['id']."_yds_range_high",['label'=>false,'type'=>'number','step'=>'any','value'=>$rulerow['yds_range_high']]);
			echo "</td>
			<td width=\"20%\" valign=\"top\">";
			echo $this->Form->input("rulerow_".$rulerow['id']."_markup",['label'=>false,'type'=>'number','step'=>'any','value'=>$rulerow['range_markup']]);
			echo "%</td>
			</tr>";
		}
		
	}
	echo "</tfoot></table>";
}
echo "<br><Br>";
echo $this->Form->submit('Save Changes');
echo $this->Form->end();
?>
<br><Br><br><Br>
<style>
#ruleslist{ width:98%; margin:auto; max-width:720px; }
#ruleslist thead tr{ background:#1F2965; color:#FFF; }
#ruleslist thead tr th{ color:#FFF; }
#ruleslist tbody tr td input{ width:98%; margin:0 0 0 0 !important; }
#ruleslist tbody tr td:nth-of-type(1) div.input,
#ruleslist tbody tr td:nth-of-type(2) div.input{ width:44%; display:inline-block; }
#ruleslist tbody tr td:nth-of-type(3) div.input{ width:85%; display:inline-block; }
input[type=submit]{ background:#26337A; color:#FFF; font-weight:bold; padding:10px 15px; border:1px solid #000000; }
.submit{ text-align:center; padding:15px; }
</style>