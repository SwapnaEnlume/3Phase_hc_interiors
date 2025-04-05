<?php
$currentData=json_decode(urldecode($quoteData['project_management_surcharge_configs']),true);

echo $this->Form->create(null);

echo $this->Form->input('pms_percentage',['type'=>'hidden','value'=>$currentData['pms_percentage']]);


echo "<html>
<head>
<style>
body{ margin:0; font-family:'Helvetica',Arial,sans-serif; }
h2{ margin:0 0 8px 0px; text-align:center; }
table#pmconfigtable tr th{ text-align:left; font-weight:bold; vertical-align:top; width:52%; background:#CCCCCC; }
table#pmconfigtable tr td{ width:48%; vertical-align:top; text-align:left; }

#buttonsrow button[type=button]{ float:left; }
#buttonsrow div.submit{ float:right; width:45%; text-align:right;  }

#buttonsrow{ margin-top:20px; }
div.submit input{ background:green; color:#FFF; border:0; padding:10px 15px; font-weight:bold; cursor:pointer; }

label[for=pm-com-or-mom-mom]{ margin-right:15px; }
label[for=pm-type-of-construction-new]{ margin-right:15px; }
label[for=pm-clear-documentation-yes]{ margin-right:15px; }
label[for=pm-shipnearbypriortoinstall-allowed]{ margin-right:15px; }
select{ padding:4px; width:98%; }
input[type=number]{ padding:4px; width:60%; }
textarea{ width:98%; padding:4px; }

table#pmconfigtable tr th small{ font-style:italic; }

table#pmconfigtable tr td:nth-of-type(2){ text-align:center; font-weight:bold; color:green; }
</style>

<script>
var thisQuoteVal=parseFloat(".$quoteData['quote_total'].");
var quoteValueRanges=".urldecode($settings['pm_surcharge_total_quote_values']).";
var baseProjectSurchargeMOM=".$settings['pm_surcharge_base_percentage_mom'].";
var baseProjectSurchargeCOM=".$settings['pm_surcharge_base_percentage_com'].";
var facilityTypePercentages=".urldecode($settings['pm_surcharge_facility_types']).";
var constructionTypeNewMOM=".$settings['pm_surcharge_construction_type_new_mom_percent'].";
var constructionTypeNewCOM=".$settings['pm_surcharge_construction_type_new_com_percent'].";
var constructionTypeRenovationMOM=".$settings['pm_surcharge_construction_type_renovation_mom_percent'].";
var constructionTypeRenovationCOM=".$settings['pm_surcharge_construction_type_renovation_com_percent'].";
var phaseCountPercentages=".urldecode($settings['pm_surcharge_phase_count_percents']).";
var documentationYesMOM=".$settings['pm_surcharge_documentation_yes_percentage_mom'].";
var documentationYesCOM=".$settings['pm_surcharge_documentation_yes_percentage_com'].";
var documentationNoMOM=".$settings['pm_surcharge_documentation_no_percentage_mom'].";
var documentationNoCOM=".$settings['pm_surcharge_documentation_no_percentage_com'].";
var readinessPercentages=".urldecode($settings['pm_surcharge_readiness']).";
var clientStorageYesMOM=".$settings['pm_surcharge_client_storage_yes_mom'].";
var clientStorageYesCOM=".$settings['pm_surcharge_client_storage_yes_com'].";
var clientStorageNoMOM=".$settings['pm_surcharge_client_storage_no_mom'].";
var clientStorageNoCOM=".$settings['pm_surcharge_client_storage_no_com'].";

function recalculatePMSurchargePercentage(){
	var finalPercentage=0;
	var momorcom='';
	console.clear();
	if($('#pm-com-or-mom-mom').is(':checked')){
		finalPercentage=(finalPercentage + parseFloat(baseProjectSurchargeMOM));
		console.log('Base Project Surcharge (MOM) +'+baseProjectSurchargeMOM);
		$('td#basepercentage').html('+'+baseProjectSurchargeMOM+'%');
		momorcom='mom';
	}else if($('#pm-com-or-mom-com').is(':checked')){
		finalPercentage=(finalPercentage + parseFloat(baseProjectSurchargeCOM));
		$('td#basepercentage').html('+'+baseProjectSurchargeCOM+'%');
		console.log('Base Project Surcharge (COM) +'+baseProjectSurchargeCOM);
		momorcom='com';
	}



	$.each(quoteValueRanges,function(i,val){

		if(thisQuoteVal >= parseFloat(val.rangeLow) && thisQuoteVal <= parseFloat(val.rangeHigh)){
			if(momorcom=='mom'){
				finalPercentage=(finalPercentage + parseFloat(val.momPercent));
				$('td#quotevaluepercentage').html('+'+val.momPercent+'%');
				console.log('Quote Total Range '+val.rangeLow+'-'+val.rangeHigh+' MOM +'+val.momPercent);
			}else if(momorcom=='com'){
				finalPercentage=(finalPercentage + parseFloat(val.comPercent));
				$('td#quotevaluepercentage').html('+'+val.comPercent+'%');
				console.log('Quote Total Range '+val.rangeLow+'-'+val.rangeHigh+' COM +'+val.comPercent);
			}
		}

	});


	$.each(facilityTypePercentages,function(i,val){

		if($('select[name=type-of-facility]').val() == val.facilityType){
			if(momorcom=='mom'){
				finalPercentage = (finalPercentage + parseFloat(val.momPercent));
				$('td#facilitytypepercentage').html('+'+val.momPercent+'%');
				console.log('Facility Type '+val.facilityType+' MOM +'+val.momPercent);
			}else if(momorcom == 'com'){
				finalPercentage = (finalPercentage + parseFloat(val.comPercent));
				$('td#facilitytypepercentage').html('+'+val.comPercent+'%');
				console.log('Facility Type '+val.facilityType+' COM +'+val.comPercent);
			}
		}

	});


	if($('#pm-type-of-construction-new').is(':checked')){
		if(momorcom=='mom'){
			finalPercentage = (finalPercentage + parseFloat(constructionTypeNewMOM));
			$('td#constructiontypepercentage').html('+'+constructionTypeNewMOM+'%');
			console.log('Construction Type NEW (MOM) +'+constructionTypeNewMOM);
		}else if(momorcom == 'com'){
			finalPercentage = (finalPercentage + parseFloat(constructionTypeNewCOM));
			$('td#constructiontypepercentage').html('+'+constructionTypeNewCOM+'%');
			console.log('Construction Type NEW (COM) +'+constructionTypeNewCOM);
		}
	}else if($('#pm-type-of-construction-renovation').is(':checked')){
		if(momorcom=='mom'){
			finalPercentage = (finalPercentage + parseFloat(constructionTypeRenovationMOM));
			$('td#constructiontypepercentage').html('+'+constructionTypeRenovationMOM+'%');
			console.log('Construction Type RENOVATION (MOM) +'+constructionTypeRenovationMOM);
		}else if(momorcom == 'com'){
			finalPercentage = (finalPercentage + parseFloat(constructionTypeRenovationCOM));
			$('td#constructiontypepercentage').html('+'+constructionTypeRenovationCOM+'%');
			console.log('Construction Type RENOVATION (COM) +'+constructionTypeRenovationCOM);
		}
	}


	$.each(phaseCountPercentages,function(i,val){

		var selectedPhaseSplit=$('select[name=phase_count]').val().split('-');
		var selectedPhaseRangeLow=selectedPhaseSplit[0];
		var selectedPhaseRangeHigh=selectedPhaseSplit[1];

		if(parseFloat(selectedPhaseRangeLow) >= parseFloat(val.rangeLow) && parseFloat(selectedPhaseRangeHigh) <= parseFloat(val.rangeHigh)){
			if(momorcom=='mom'){
				finalPercentage = (finalPercentage + parseFloat(val.momPercent));
				$('td#phasecountpercentage').html('+'+val.momPercent+'%');
				console.log('Phase Count Range '+val.rangeLow+'-'+val.rangeHigh+' (MOM) +'+val.momPercent);
			}else if(momorcom == 'com'){
				finalPercentage = (finalPercentage + parseFloat(val.comPercent));
				$('td#phasecountpercentage').html('+'+val.comPercent+'%');
				console.log('Phase Count Range '+val.rangeLow+'-'+val.rangeHigh+' (COM) +'+val.comPercent);
			}
		}

	});


	if($('#pm-clear-documentation-yes').is(':checked')){
		if(momorcom=='mom'){
			finalPercentage=(finalPercentage + parseFloat(documentationYesMOM));
			$('td#cleardocspercentage').html('+'+documentationYesMOM+'%');
			console.log('Clear Documentation YES (MOM) +'+documentationYesMOM);
		}else if(momorcom == 'com'){
			finalPercentage=(finalPercentage + parseFloat(documentationYesCOM));
			$('td#cleardocspercentage').html('+'+documentationYesCOM+'%');
			console.log('Clear Documentation YES (COM) +'+documentationYesCOM);
		}
	}else if($('#pm-clear-documentation-no').is(':checked')){
		if(momorcom=='mom'){
			finalPercentage=(finalPercentage + parseFloat(documentationNoMOM));
			$('td#cleardocspercentage').html('+'+documentationNoMOM+'%');
			console.log('Clear Documentation NO (MOM) +'+documentationNoMOM);
		}else if(momorcom == 'com'){
			finalPercentage=(finalPercentage + parseFloat(documentationNoCOM));
			$('td#cleardocspercentage').html('+'+documentationNoCOM+'%');
			console.log('Clear Documentation NO (COM) +'+documentationNoCOM);
		}
	}


	$.each(readinessPercentages,function(i,val){
		var selectedReadinessSplit=$('select[name=time_between_readiness_and_deadline]').val().split('-');
		var selectedReadinessRangeLow=selectedReadinessSplit[0];
		var selectedReadinessRangeHigh=selectedReadinessSplit[1];

		if(parseFloat(selectedReadinessRangeLow) >= parseFloat(val.rangeLow) && parseFloat(selectedReadinessRangeHigh) <= parseFloat(val.rangeHigh)){
			if(momorcom=='mom'){
				finalPercentage = (finalPercentage + parseFloat(val.momPercent));
				$('td#readinesspercentage').html('+'+val.momPercent+'%');
				console.log('Readiness Range '+val.rangeLow+'-'+val.rangeHigh+' (MOM) +'+val.momPercent);
			}else if(momorcom == 'com'){
				finalPercentage = (finalPercentage + parseFloat(val.comPercent));
				$('td#readinesspercentage').html('+'+val.comPercent+'%');
				console.log('Readiness Range '+val.rangeLow+'-'+val.rangeHigh+' (COM) +'+val.comPercent);
			}
		}
	});


	if($('#pm-shipnearbypriortoinstall-allowed').is(':checked')){
		if(momorcom=='mom'){
			finalPercentage=(finalPercentage + parseFloat(clientStorageYesMOM));
			$('td#clientstoragepercentage').html('+'+clientStorageYesMOM+'%');
			console.log('Client Storage YES (MOM) +'+clientStorageYesMOM);
		}else if(momorcom=='com'){
			finalPercentage=(finalPercentage + parseFloat(clientStorageYesCOM));
			$('td#clientstoragepercentage').html('+'+clientStorageYesCOM+'%');
			console.log('Client Storage YES (COM) +'+clientStorageYesCOM);
		}
	}else if($('#pm-shipnearbypriortoinstall-not-allowed').is(':checked')){
		if(momorcom=='mom'){
			finalPercentage=(finalPercentage + parseFloat(clientStorageNoMOM));
			$('td#clientstoragepercentage').html('+'+clientStorageNoMOM+'%');
			console.log('Client Storage NO (MOM) +'+clientStorageNoMOM);
		}else if(momorcom=='com'){
			finalPercentage=(finalPercentage + parseFloat(clientStorageNoCOM));
			$('td#clientstoragepercentage').html('+'+clientStorageNoCOM+'%');
			console.log('Client Storage NO (COM) +'+clientStorageNoCOM);
		}
	}


	finalPercentage=(finalPercentage + parseFloat($('#pm-surcharge-unusual-addon-percent').val()));
	console.log('Add-On Unusual Percentage +'+$('#pm-surcharge-unusual-addon-percent').val());
	$('td#unusualaddonpercentage').html('+'+$('#pm-surcharge-unusual-addon-percent').val()+'%');

	$('#pms-percentage').val(finalPercentage);
	$('#totalperc').html(finalPercentage+'%');

}

setInterval('recalculatePMSurchargePercentage()',500);
</script>

</head>
<body>
<h2>PM Surcharge Configuration</h2>";

echo "<table width=\"98%\" cellpadding=\"5\" cellspacing=\"0\" border=\"1\" bordercolor=\"#888888\" id=\"pmconfigtable\">
<tr>
<th>Base Project Management Surcharge</th>
<td>";

if(isset($currentData['pm_com_or_mom']) && strlen(trim($currentData['pm_com_or_mom'])) >0){
	$currentvalComMom=$currentData['pm_com_or_mom'];
}else{
	$currentvalComMom='';
}

echo $this->Form->radio('pm_com_or_mom',['MOM'=>'MOM','COM'=>'COM'],['required'=>true,'value'=>$currentvalComMom]);
echo "</td>
<td id=\"basepercentage\"></td>
</tr>

<tr>
<th>Total quote value</th>
<td>";
echo '$'.number_format(floatval($quoteData['quote_total']),2,'.',',');
echo "</td>
<td id=\"quotevaluepercentage\"></td>
</tr>
<tr>
<th>Type of facility</th>
<td>";

if(isset($currentData['type-of-facility']) && strlen(trim($currentData['type-of-facility'])) >0){
	$currentvalTypeOfFacility=$currentData['type-of-facility'];
}else{
	$currentvalTypeOfFacility='';
}

$facilityTypesArr=array();
foreach($facilityTypes as $facilityType){
	$facilityTypesArr[$facilityType['facilityType']]=$facilityType['facilityType'];
}

echo $this->Form->select('type-of-facility',$facilityTypesArr,['required'=>true,'empty'=>'--Select facility type--','value'=>$currentvalTypeOfFacility]);
echo "</td>
<td id=\"facilitytypepercentage\"></td>
</tr>
<tr>
<th>Type of Construction</th>
<td>";

if(isset($currentData['pm_type_of_construction']) && strlen(trim($currentData['pm_type_of_construction'])) >0){
	$currentvalTypeOfConstruction=$currentData['pm_type_of_construction'];
}else{
	$currentvalTypeOfConstruction='';
}

echo $this->Form->radio('pm_type_of_construction',['NEW'=>'NEW','RENOVATION'=>'RENOVATION'],['required'=>true,'value'=>$currentvalTypeOfConstruction]);
echo "</td>
<td id=\"constructiontypepercentage\"></td>
</tr>
<tr>
<th>How many phases to complete the Project?</th>
<td>";

if(isset($currentData['phase_count']) && strlen(trim($currentData['phase_count'])) >0){
	$currentvalPhaseCount=$currentData['phase_count'];
}else{
	$currentvalPhaseCount='';
}

$allPhaseCountsConfig=json_decode(urldecode($settings['pm_surcharge_phase_count_percents']),true);
$availablePhaseCounts=array();
foreach($allPhaseCountsConfig as $phaseRange){
	$availablePhaseCounts[$phaseRange['rangeLow']."-".$phaseRange['rangeHigh']]=$phaseRange['rangeLow'].'-'.$phaseRange['rangeHigh'];
}


echo $this->Form->select('phase_count',$availablePhaseCounts,['required'=>true,'empty'=>'--Select number of Phases--','value'=>$currentvalPhaseCount]);
echo "</td>
<td id=\"phasecountpercentage\"></td>
</tr>
<tr>
<th>Clear documentation available?<br><small>(floor plans, product specs, room numbers, etc)</small></th>
<td>";

if(isset($currentData['pm_clear_documentation']) && strlen(trim($currentData['pm_clear_documentation'])) >0){
	$currentvalClearDocs=$currentData['pm_clear_documentation'];
}else{
	$currentvalClearDocs='';
}

echo $this->Form->radio('pm_clear_documentation',['YES'=>'YES','NO'=>'NO'],['required'=>true,'value'=>$currentvalClearDocs]);
echo "</td>
<td id=\"cleardocspercentage\"></td>
</tr>
<tr>
<th>Time expected between building readiness and install deadline</th>
<td>";

if(isset($currentData['time_between_readiness_and_deadline']) && strlen(trim($currentData['time_between_readiness_and_deadline'])) >0){
	$currentvalTimeBetween=$currentData['time_between_readiness_and_deadline'];
}else{
	$currentvalTimeBetween='';
}

$availableReadinessOptions=array();
$allReadinessOptionsConfig=json_decode(urldecode($settings['pm_surcharge_readiness']),true);

foreach($allReadinessOptionsConfig as $readinessRange){
	$availableReadinessOptions[$readinessRange['rangeLow']."-".$readinessRange['rangeHigh']]=$readinessRange['rangeLow'].'-'.$readinessRange['rangeHigh'].' weeks';;
}


echo $this->Form->select('time_between_readiness_and_deadline',$availableReadinessOptions,['required'=>true,'empty'=>'--Select Timing','value' => $currentvalTimeBetween]);
echo "</td>
<td id=\"readinesspercentage\"></td>
</tr>
<tr>
<th>Shipping products to the facility or nearby warehouse for storage prior to install</th>
<td>";

if(isset($currentData['pm_shipnearbypriortoinstall']) && strlen(trim($currentData['pm_shipnearbypriortoinstall'])) >0){
	$currentvalShipNearbyPrior=$currentData['pm_shipnearbypriortoinstall'];
}else{
	$currentvalShipNearbyPrior='';
}

echo $this->Form->radio('pm_shipnearbypriortoinstall',['ALLOWED'=>'ALLOWED','NOT ALLOWED'=>'NOT ALLOWED'],['required'=>true,'value'=>$currentvalShipNearbyPrior]);
echo "</td>
<td id=\"clientstoragepercentage\"></td>
</tr>
<tr>
<th>Is there anything unusual about this project of which we should be aware?</th>
<td>";
echo $this->Form->textarea('anything_unusual',['placeholder'=>'--Type in details (300 characters max) --','maxlength'=>300,'label'=>false,'required'=>false,'value'=>$currentData['anything_unusual']]);
echo "</td>
<td id=\"unusualaddonpercentage\" rowspan=\"2\"></td>
</tr>
<tr>
<th>Unusual Add-on Percentage</th>
<td>";

if(isset($currentData['pm_surcharge_unusual_addon_percent']) && strlen(trim($currentData['pm_surcharge_unusual_addon_percent'])) >0){
	$currentvalUnusualAddonPercentage=$currentData['pm_surcharge_unusual_addon_percent'];
}else{
	$currentvalUnusualAddonPercentage='0';
}

echo $this->Form->input('pm_surcharge_unusual_addon_percent',['type'=>'number','label'=>false,'value'=>$currentvalUnusualAddonPercentage]);
echo "</td>
</tr>
<tr>
<th>Total Project Management Surcharge<br><small>(applied to Soft Window Treatments only)</small></th>
<td>&nbsp;</td>
<td id=\"totalperc\">";

if($currentData['pms_percentage'] == ''){
	echo "";
}else{
	echo $currentData['pms_percentage'];
}

echo "</td>
</tr>
</table>";

echo "<div id=\"buttonsrow\"><button type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";
echo $this->Form->submit('Save and Apply');
echo "<div style=\"clear:both;\"></div>";
echo "</div>";

echo $this->Form->end();

echo "</body>
</html>";
