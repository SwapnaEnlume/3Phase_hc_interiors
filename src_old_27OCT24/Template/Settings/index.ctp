<!-- src/Template/Settings/index.ctp -->
<h1 class="pageheading">Settings</h1>


<?php
echo $this->Form->create(null);

$sections=array();
foreach($allsettings as $settingrow){
	if(!in_array($settingrow['setting_group'],$sections)){
		$sections[]=$settingrow['setting_group'];
	}
}

echo "<div id=\"accordion\">";
foreach($sections as $section){
	echo "<h2>";
	if($section == ''){
		echo "Uncategorized Settings";
	}else{
		echo $section;
	}
	echo "</h2><div class=\"sectioncontentwrap\">";
	foreach($allsettings as $settingrow){
		if($settingrow['setting_key'] != 'zoho_auth_token' && $settingrow['setting_group'] == $section){
			switch($settingrow['fieldtype']){
				case 'textarea':
					echo "<div class=\"input textarea\"><label>".$settingrow['setting_label']."</label>";
					echo $this->Form->textarea($settingrow['setting_key'],['value'=>$settingrow['setting_value']]);
					echo "</div>";
				break;
				case "tpmstable":
					echo $this->Form->input('pm_surcharge_total_quote_values',['type'=>'hidden','value'=>$settingrow['setting_value']]);
					
					echo "<table width=\"500\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\" id=\"tpmstable\">
					<thead>
						<tr>
						<th width=\"50%\" colspan=\"2\">Total Value of Quote Range</th>
						<th width=\"25%\">MOM</th>
						<th width=\"25%\">COM</th>
						</tr>
					</thead>
					<tbody>";

					//loop through existing and populate
					$vals=json_decode(urldecode($settingrow['setting_value']),true);

					foreach($vals as $rownum => $row){
						echo "<tr data-rownum=\"".$rownum."\"><td width=\"25%\"><input type=\"number\" onchange=\"updatetpmstablevalues();\" onkeyup=\"updatetpmstablevalues();\" name=\"rangemin_".$rownum."\" min=\"0\" step=\"any\" class=\"rangemin\" placeholder=\"Range Min\" value=\"".$row['rangeLow']."\" /></td><td width=\"25%\"><input type=\"number\" step=\"any\" onchange=\"updatetpmstablevalues();\" onkeyup=\"updatetpmstablevalues();\" name=\"rangemax_".$rownum."\" class=\"rangemax\" min=\"0\" placeholder=\"Range Max\" value=\"".$row['rangeHigh']."\" /></td><td><input type=\"number\" onchange=\"updatetpmstablevalues();\" onkeyup=\"updatetpmstablevalues();\" name=\"mompercent_".$rownum."\" class=\"momperc\" min=\"0\" placeholder=\"MOM %\" value=\"".$row['momPercent']."\" /></td><td><input type=\"number\" onchange=\"updatetpmstablevalues();\" onkeyup=\"updatetpmstablevalues();\" name=\"compercent_".$rownum."\" class=\"comperc\" min=\"0\" placeholder=\"COM %\" value=\"".$row['comPercent']."\" /></td></tr>";
					}

					echo "</tbody>
					</table>
					<div><button type=\"button\" onclick=\"tpmstableaddrange()\" style=\"padding:5px 10px; font-size:11px;\">+ Add Range</button></div>
					<script>
					function updatetpmstablevalues(){
						var tpmsout=[];
						$('#tpmstable tbody tr').each(function(){
							tpmsout.push({'rangeLow':$(this).find('input.rangemin').val(), 'rangeHigh': $(this).find('input.rangemax').val(), 'momPercent':$(this).find('input.momperc').val(), 'comPercent':$(this).find('input.comperc').val() });
						});

						$('#pm-surcharge-total-quote-values').val(encodeURIComponent(JSON.stringify(tpmsout)));
					}

					function tpmstableaddrange(){
						var newrow=($('#tpmstable tbody tr').length+1);
						$('#tpmstable tbody').append('<tr data-rownum=\"'+newrow+'\"><td width=\"25%\"><input type=\"number\" onchange=\"updatetpmstablevalues();\" onkeyup=\"updatetpmstablevalues();\" name=\"rangemin_'+newrow+'\" min=\"0\" class=\"rangemin\" placeholder=\"Range Min\" /></td><td width=\"25%\"><input type=\"number\" onchange=\"updatetpmstablevalues();\" onkeyup=\"updatetpmstablevalues();\" name=\"rangemax_'+newrow+'\" class=\"rangemax\" min=\"0\" placeholder=\"Range Max\" /></td><td><input type=\"number\" onchange=\"updatetpmstablevalues();\" onkeyup=\"updatetpmstablevalues();\" name=\"mompercent_'+newrow+'\" class=\"momperc\" min=\"0\" placeholder=\"MOM %\" /></td><td><input type=\"number\" onchange=\"updatetpmstablevalues();\" onkeyup=\"updatetpmstablevalues();\" name=\"compercent_'+newrow+'\" class=\"comperc\" min=\"0\" placeholder=\"COM %\" /></td></tr>');

						updatetpmstablevalues();
					}
					</script>";
					
				break;
				case "phasecounttable":
					echo $this->Form->input('pm_surcharge_phase_count_percents',['type'=>'hidden','value'=>$settingrow['setting_value']]);
					
					echo "<table width=\"500\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\" id=\"phasetable\">
					<thead>
						<tr>
						<th width=\"50%\" colspan=\"2\">Number of Phases: Ranges</th>
						<th width=\"25%\">MOM</th>
						<th width=\"25%\">COM</th>
						</tr>
					</thead>
					<tbody>";

					//loop through existing and populate
					$vals=json_decode(urldecode($settingrow['setting_value']),true);

					foreach($vals as $rownum => $row){
						echo "<tr data-rownum=\"".$rownum."\"><td width=\"25%\"><input type=\"number\" onchange=\"updatephasetablevalues();\" onkeyup=\"updatephasetablevalues();\" name=\"rangemin_".$rownum."\" min=\"0\" class=\"rangemin\" placeholder=\"Range Min\" value=\"".$row['rangeLow']."\" /></td><td width=\"25%\"><input type=\"number\" onchange=\"updatephasetablevalues();\" onkeyup=\"updatephasetablevalues();\" name=\"rangemax_".$rownum."\" class=\"rangemax\" min=\"0\" placeholder=\"Range Max\" value=\"".$row['rangeHigh']."\" /></td><td><input type=\"number\" onchange=\"updatephasetablevalues();\" onkeyup=\"updatephasetablevalues();\" name=\"mompercent_".$rownum."\" class=\"momperc\" min=\"0\" placeholder=\"MOM %\" value=\"".$row['momPercent']."\" /></td><td><input type=\"number\" onchange=\"updatephasetablevalues();\" onkeyup=\"updatephasetablevalues();\" name=\"compercent_".$rownum."\" class=\"comperc\" min=\"0\" placeholder=\"COM %\" value=\"".$row['comPercent']."\" /></td></tr>";
					}

					echo "</tbody>
					</table>
					<div><button type=\"button\" onclick=\"phasetableaddrange()\" style=\"padding:5px 10px; font-size:11px;\">+ Add Range</button></div>
					<script>
					function updatephasetablevalues(){
						var phaseout=[];
						$('#phasetable tbody tr').each(function(){
							phaseout.push({'rangeLow':$(this).find('input.rangemin').val(), 'rangeHigh': $(this).find('input.rangemax').val(), 'momPercent':$(this).find('input.momperc').val(), 'comPercent':$(this).find('input.comperc').val() });
						});

						$('#pm-surcharge-phase-count-percents').val(encodeURIComponent(JSON.stringify(phaseout)));
					}

					function phasetableaddrange(){
						var newrow=($('#phasetable tbody tr').length+1);
						$('#phasetable tbody').append('<tr data-rownum=\"'+newrow+'\"><td width=\"25%\"><input type=\"number\" onchange=\"updatephasetablevalues();\" onkeyup=\"updatephasetablevalues();\" name=\"rangemin_'+newrow+'\" min=\"0\" class=\"rangemin\" placeholder=\"Range Min\" /></td><td width=\"25%\"><input type=\"number\" onchange=\"updatephasetablevalues();\" onkeyup=\"updatephasetablevalues();\" name=\"rangemax_'+newrow+'\" class=\"rangemax\" min=\"0\" placeholder=\"Range Max\" /></td><td><input type=\"number\" onchange=\"updatephasetablevalues();\" onkeyup=\"updatephasetablevalues();\" name=\"mompercent_'+newrow+'\" class=\"momperc\" min=\"0\" placeholder=\"MOM %\" /></td><td><input type=\"number\" onchange=\"updatephasetablevalues();\" onkeyup=\"updatephasetablevalues();\" name=\"compercent_'+newrow+'\" class=\"comperc\" min=\"0\" placeholder=\"COM %\" /></td></tr>');

						updatephasetablevalues();
					}
					</script>";
				break;
				case "facilitytable":
					echo $this->Form->input('pm_surcharge_facility_types',['type'=>'hidden','value'=>$settingrow['setting_value']]);
					
					echo "<table width=\"500\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\" id=\"facilitytable\">
					<thead>
						<tr>
						<th width=\"50%\">Facility Type</th>
						<th width=\"25%\">MOM</th>
						<th width=\"25%\">COM</th>
						</tr>
					</thead>
					<tbody>";

					//loop through existing and populate
					$vals=json_decode(urldecode($settingrow['setting_value']),true);

					foreach($vals as $rownum => $row){
						echo "<tr data-rownum=\"".$rownum."\"><td width=\"50%\"><input type=\"text\" onchange=\"updatefacilitytablevalues();\" onkeyup=\"updatefacilitytablevalues();\" name=\"facilitytype_".$rownum."\" class=\"facilitytype\" placeholder=\"Facility Type\" value=\"".$row['facilityType']."\" /></td><td><input type=\"number\" onchange=\"updatefacilitytablevalues();\" onkeyup=\"updatefacilitytablevalues();\" name=\"mompercent_".$rownum."\" class=\"momperc\" min=\"0\" placeholder=\"MOM %\" value=\"".$row['momPercent']."\" /></td><td><input type=\"number\" onchange=\"updatefacilitytablevalues();\" onkeyup=\"updatefacilitytablevalues();\" name=\"compercent_".$rownum."\" class=\"comperc\" min=\"0\" placeholder=\"COM %\" value=\"".$row['comPercent']."\" /></td></tr>";
					}

					echo "</tbody>
					</table>
					<div><button type=\"button\" onclick=\"facilitytableaddrange()\" style=\"padding:5px 10px; font-size:11px;\">+ Add Facility Type</button></div>
					<script>
					function updatefacilitytablevalues(){
						var facout=[];
						$('#facilitytable tbody tr').each(function(){
							facout.push({'facilityType': $(this).find('input.facilitytype').val(), 'momPercent':$(this).find('input.momperc').val(), 'comPercent':$(this).find('input.comperc').val() });
						});

						$('#pm-surcharge-facility-types').val(encodeURIComponent(JSON.stringify(facout)));
					}

					function facilitytableaddrange(){
						var newrow=($('#facilitytable tbody tr').length+1);
						$('#facilitytable tbody').append('<tr data-rownum=\"'+newrow+'\"><td width=\"50%\"><input type=\"text\" onchange=\"updatefacilitytablevalues();\" onkeyup=\"updatefacilitytablevalues();\" name=\"facilitytype_'+newrow+'\" class=\"facilitytype\" placeholder=\"Facility Type\" /></td><td><input type=\"number\" onchange=\"updatefacilitytablevalues();\" onkeyup=\"updatefacilitytablevalues();\" name=\"mompercent_'+newrow+'\" class=\"momperc\" min=\"0\" placeholder=\"MOM %\" /></td><td><input type=\"number\" onchange=\"updatefacilitytablevalues();\" onkeyup=\"updatefacilitytablevalues();\" name=\"compercent_'+newrow+'\" class=\"comperc\" min=\"0\" placeholder=\"COM %\" /></td></tr>');

						updatefacilitytablevalues();
					}
					</script>";
				break;
				case "readinesstable":
					echo $this->Form->input('pm_surcharge_readiness',['type'=>'hidden','value'=>$settingrow['setting_value']]);
					
					echo "<table width=\"500\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\" id=\"readinesstable\">
					<thead>
						<tr>
						<th width=\"50%\" colspan=\"2\">Timing Ranges (Weeks)</th>
						<th width=\"25%\">MOM</th>
						<th width=\"25%\">COM</th>
						</tr>
					</thead>
					<tbody>";

					//loop through existing and populate
					$vals=json_decode(urldecode($settingrow['setting_value']),true);

					foreach($vals as $rownum => $row){
						echo "<tr data-rownum=\"".$rownum."\"><td width=\"25%\"><input type=\"number\" onchange=\"updatereadinesstablevalues();\" onkeyup=\"updatereadinesstablevalues();\" name=\"rangemin_".$rownum."\" min=\"0\" class=\"rangemin\" placeholder=\"Range Min\" value=\"".$row['rangeLow']."\" /></td><td width=\"25%\"><input type=\"number\" onchange=\"updatereadinesstablevalues();\" onkeyup=\"updatereadinesstablevalues();\" name=\"rangemax_".$rownum."\" class=\"rangemax\" min=\"0\" placeholder=\"Range Max\" value=\"".$row['rangeHigh']."\" /></td><td><input type=\"number\" onchange=\"updatereadinesstablevalues();\" onkeyup=\"updatereadinesstablevalues();\" name=\"mompercent_".$rownum."\" class=\"momperc\" min=\"0\" placeholder=\"MOM %\" value=\"".$row['momPercent']."\" /></td><td><input type=\"number\" onchange=\"updatereadinesstablevalues();\" onkeyup=\"updatereadinesstablevalues();\" name=\"compercent_".$rownum."\" class=\"comperc\" min=\"0\" placeholder=\"COM %\" value=\"".$row['comPercent']."\" /></td></tr>";
					}

					echo "</tbody>
					</table>
					<div><button type=\"button\" onclick=\"readinesstableaddrange()\" style=\"padding:5px 10px; font-size:11px;\">+ Add Range</button></div>
					<script>
					function updatereadinesstablevalues(){
						var readout=[];
						$('#readinesstable tbody tr').each(function(){
							readout.push({'rangeLow':$(this).find('input.rangemin').val(), 'rangeHigh': $(this).find('input.rangemax').val(), 'momPercent':$(this).find('input.momperc').val(), 'comPercent':$(this).find('input.comperc').val() });
						});

						$('#pm-surcharge-readiness').val(encodeURIComponent(JSON.stringify(readout)));
					}

					function readinesstableaddrange(){
						var newrow=($('#readinesstable tbody tr').length+1);
						$('#readinesstable tbody').append('<tr data-rownum=\"'+newrow+'\"><td width=\"25%\"><input type=\"number\" onchange=\"updatereadinesstablevalues();\" onkeyup=\"updatereadinesstablevalues();\" name=\"rangemin_'+newrow+'\" min=\"0\" class=\"rangemin\" placeholder=\"Range Min\" /></td><td width=\"25%\"><input type=\"number\" onchange=\"updatereadinesstablevalues();\" onkeyup=\"updatereadinesstablevalues();\" name=\"rangemax_'+newrow+'\" class=\"rangemax\" min=\"0\" placeholder=\"Range Max\" /></td><td><input type=\"number\" onchange=\"updatereadinesstablevalues();\" onkeyup=\"updatereadinesstablevalues();\" name=\"mompercent_'+newrow+'\" class=\"momperc\" min=\"0\" placeholder=\"MOM %\" /></td><td><input type=\"number\" onchange=\"updatereadinesstablevalues();\" onkeyup=\"updatereadinesstablevalues();\" name=\"compercent_'+newrow+'\" class=\"comperc\" min=\"0\" placeholder=\"COM %\" /></td></tr>');

						updatereadinesstablevalues();
					}
					</script>";
				break;
				case "tagcloud":
					if(strlen($settingrow['setting_label']) > 0){
						$thislabel=$settingrow['setting_label'];
					}else{
						$thislabel='';
						$keysplit=explode(" ",str_replace("_"," ",$settingrow['setting_key']));
						for($g=0; $g<count($keysplit); $g++){
							$thislabel .= ucfirst($keysplit[$g])." ";
						}
						$thislabel=substr($thislabel,0,(strlen($thislabel)-1));
					}
					echo $this->Form->input($settingrow['setting_key'],['type'=>'text','data-istagcloud'=>'1','label'=>$thislabel,'value'=>$settingrow['setting_value']]);
				break;
				default:
					if(strlen($settingrow['setting_label']) > 0){
						$thislabel=$settingrow['setting_label'];
					}else{
						$thislabel='';
						$keysplit=explode(" ",str_replace("_"," ",$settingrow['setting_key']));
						for($g=0; $g<count($keysplit); $g++){
							$thislabel .= ucfirst($keysplit[$g])." ";
						}
						$thislabel=substr($thislabel,0,(strlen($thislabel)-1));
					}
					echo $this->Form->input($settingrow['setting_key'],['type'=>'text','value'=>$settingrow['setting_value'],'label'=>$thislabel]);
				break;
			}
		}
	}
	echo "</div>";
}
echo "</div>";

echo $this->Form->submit('Save Changes');
echo $this->Form->end();

?>
<script>
$(function(){
	$("input[data-istagcloud=1]").tagit({
		'allowSpaces':true,
		'singleField':true,
		'singleFieldDelimiter':'|'
	});

	$('#accordion').accordion({
		heightStyle:'content',
		collapsible:true,
		header:'h2',
		active:false
	});
});
</script>
<style>
#tpmstable{ margin-bottom:3px !important; }
#tpmstable thead{ background:#660000; }
#tpmstable thead tr th{ color:#FFF; }


#facilitytable{ margin-bottom:3px !important; }
#facilitytable thead{ background:#660000; }
#facilitytable thead tr th{ color:#FFF; }



#readinesstable{ margin-bottom:3px !important; }
#readinesstable thead{ background:#660000; }
#readinesstable thead tr th{ color:#FFF; }

#phasetable{ margin-bottom:3px !important; }
#phasetable thead{ background:#660000; }
#phasetable thead tr th{ color:#FFF; }

form{ width:700px; margin:20px auto; }
div.input.textarea textarea{ width:100%; padding:2%; height:290px; }
div.submit{ padding:25px 0; text-align:center; }
div.submit input[type=submit]{ background:green; font-weight:bold; color:white; padding:10px 20px; font-size:18px; border:0; cursor:pointer; }
</style>