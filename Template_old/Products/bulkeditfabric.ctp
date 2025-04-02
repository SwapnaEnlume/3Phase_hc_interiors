<?php
if($step=="step1"){
echo $this->Form->create(false);
echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);

echo "<h3>Bulk Edit Fabrics</h3>";
echo "<div style=\"margin:10px auto;background:#FDF8D9;padding:5px 10px;border:1px solid #990000;text-align:center;\"><b style=\"color:#990000;\">IMPORTANT:</b> <em>Do not change fields you do not wish to overwrite.</em></div>";

echo "<fieldset id=\"selectedfabrics\"><legend>Apply Changes To <small><em>(".count($fabricids)." fabrics selected)</em></small></legend><ul>";
foreach($selectedfabrics as $fabric){
	echo "<li><img src=\"/files/fabrics/".$fabric['id']."/".$fabric['image_file']."\" /> ".$fabric['fabric_name']." ".$fabric['color']."</li>";
}
echo "</ul><div style=\"clear:both;\"></div></fieldset>";
	

echo "<fieldset><legend>Ownership</legend>";
echo $this->Form->radio('ownership',['roster-fabric'=>'HCI Roster Fabric','mom-nonroster'=>'MOM Non-Roster Fabric','com-fabric'=>'COM Fabric']);
echo "</fieldset>";

echo "<fieldset><legend>Costs</legend>";
echo $this->Form->input('cost_per_yard_cut',['label'=>'Cost Per Yard (Cut)','type'=>'number','step'=>'any']);
echo $this->Form->input('cost_per_yard_bolt',['label'=>'Cost Per Yard (Bolt)','type'=>'number','step'=>'any']);
echo $this->Form->input('cost_per_yard_case',['label'=>'Cost Per Yard (Case)','type'=>'number','step'=>'any']);
echo "</fieldset>";

echo "<fieldset><legend>Metrics</legend>";
echo $this->Form->input('yards_per_bolt',['label'=>'Yards Per Bolt','type'=>'number']);
echo $this->Form->input('yards_per_case',['label'=>'Yards Per Case','type'=>'number']);
echo $this->Form->input('fabric_width',['label'=>'Fabric Width','type'=>'number']);
echo $this->Form->input('vertical_repeat',['label'=>'Vertical Repeat','type'=>'number','step'=>'any']);
echo $this->Form->input('horizontal_repeat',['label'=>'Horizontal Repeat','type'=>'number','step'=>'any']);
echo $this->Form->input('weight_per_sqin',['label'=>'Weight Per Sq Inch','type'=>'number','step'=>'any']);
echo $this->Form->input('minimum',['label'=>'Minimum','type'=>'number']);
echo "</fieldset>";

echo "<fieldset><legend>Other Details</legend>";
echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Fabric Status');
echo $this->Form->radio('fabric_status',['Active'=>'Active','Discontinued'=>'Discontinued']);
echo "</div>";

/*
echo "<div class=\"input checkboxes\">";
echo $this->Form->label('These Fabrics Used In:');
echo $this->Form->input('used_in_cc',['type'=>'checkbox','label'=>'CC','value'=>'yes','checked'=>false]);
echo $this->Form->input('used_in_bs',['type'=>'checkbox','label'=>'BS','value'=>'yes','checked'=>false]);
echo $this->Form->input('used_in_wt',['type'=>'checkbox','label'=>'WT','value'=>'yes','checked'=>false]);
echo $this->Form->input('used_in_sc',['type'=>'checkbox','label'=>'SC','value'=>'yes','checked'=>false]);
echo "</div>";
*/

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Is this fabric used in Cubicle Curtains?');
echo $this->Form->radio('used_in_cc',['Yes'=>'Yes','No'=>'No']);
echo "</div>";


echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Is this fabric used in Bedspreads?');
echo $this->Form->radio('used_in_bs',['Yes'=>'Yes','No'=>'No']);
echo "</div>";


echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Is this fabric used in Window Treatments?');
echo $this->Form->radio('used_in_wt',['Yes'=>'Yes','No'=>'No']);
echo "</div>";


echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Is this fabric used in Shower Curtains?');
echo $this->Form->radio('used_in_sc',['Yes'=>'Yes','No'=>'No']);
echo "</div>";


echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Antimicrobial?');
echo $this->Form->radio('antimicrobial',['Yes'=>'Yes','No'=>'No']);
echo "</div>";


echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Railroaded?');
echo $this->Form->radio('railroaded',['Yes'=>'Yes','No'=>'No']);
echo "</div>";

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Quilted?');
echo $this->Form->radio('quilted',['Yes'=>'Yes','No'=>'No']);
echo "</div>";

echo $this->Form->input('collection',['label'=>'Collection']);

echo "<div class=\"input select\">";
echo $this->Form->label('Vendor');
echo $this->Form->select('vendors_id',$vendorslist,['empty'=>'--Select Vendor--']);
echo "</div>";

	
echo $this->Form->input('vendor_fabric_name',['label'=>'Vendor Fabric Name']);
	
	
	
$country_array = array(
"AF" => "Afghanistan",
"AL" => "Albania",
"DZ" => "Algeria",
"AS" => "American Samoa",
"AD" => "Andorra",
"AO" => "Angola",
"AI" => "Anguilla",
"AQ" => "Antarctica",
"AG" => "Antigua and Barbuda",
"AR" => "Argentina",
"AM" => "Armenia",
"AW" => "Aruba",
"AU" => "Australia",
"AT" => "Austria",
"AZ" => "Azerbaijan",
"BS" => "Bahamas",
"BH" => "Bahrain",
"BD" => "Bangladesh",
"BB" => "Barbados",
"BY" => "Belarus",
"BE" => "Belgium",
"BZ" => "Belize",
"BJ" => "Benin",
"BM" => "Bermuda",
"BT" => "Bhutan",
"BO" => "Bolivia",
"BA" => "Bosnia and Herzegovina",
"BW" => "Botswana",
"BV" => "Bouvet Island",
"BR" => "Brazil",
"BQ" => "British Antarctic Territory",
"IO" => "British Indian Ocean Territory",
"VG" => "British Virgin Islands",
"BN" => "Brunei",
"BG" => "Bulgaria",
"BF" => "Burkina Faso",
"BI" => "Burundi",
"KH" => "Cambodia",
"CM" => "Cameroon",
"CA" => "Canada",
"CT" => "Canton and Enderbury Islands",
"CV" => "Cape Verde",
"KY" => "Cayman Islands",
"CF" => "Central African Republic",
"TD" => "Chad",
"CL" => "Chile",
"CN" => "China",
"CX" => "Christmas Island",
"CC" => "Cocos [Keeling] Islands",
"CO" => "Colombia",
"KM" => "Comoros",
"CG" => "Congo - Brazzaville",
"CD" => "Congo - Kinshasa",
"CK" => "Cook Islands",
"CR" => "Costa Rica",
"HR" => "Croatia",
"CU" => "Cuba",
"CY" => "Cyprus",
"CZ" => "Czech Republic",
"CI" => "Côte d’Ivoire",
"DK" => "Denmark",
"DJ" => "Djibouti",
"DM" => "Dominica",
"DO" => "Dominican Republic",
"NQ" => "Dronning Maud Land",
"DD" => "East Germany",
"EC" => "Ecuador",
"EG" => "Egypt",
"SV" => "El Salvador",
"GQ" => "Equatorial Guinea",
"ER" => "Eritrea",
"EE" => "Estonia",
"ET" => "Ethiopia",
"FK" => "Falkland Islands",
"FO" => "Faroe Islands",
"FJ" => "Fiji",
"FI" => "Finland",
"FR" => "France",
"GF" => "French Guiana",
"PF" => "French Polynesia",
"TF" => "French Southern Territories",
"FQ" => "French Southern and Antarctic Territories",
"GA" => "Gabon",
"GM" => "Gambia",
"GE" => "Georgia",
"DE" => "Germany",
"GH" => "Ghana",
"GI" => "Gibraltar",
"GR" => "Greece",
"GL" => "Greenland",
"GD" => "Grenada",
"GP" => "Guadeloupe",
"GU" => "Guam",
"GT" => "Guatemala",
"GG" => "Guernsey",
"GN" => "Guinea",
"GW" => "Guinea-Bissau",
"GY" => "Guyana",
"HT" => "Haiti",
"HM" => "Heard Island and McDonald Islands",
"HN" => "Honduras",
"HK" => "Hong Kong SAR China",
"HU" => "Hungary",
"IS" => "Iceland",
"IN" => "India",
"ID" => "Indonesia",
"IR" => "Iran",
"IQ" => "Iraq",
"IE" => "Ireland",
"IM" => "Isle of Man",
"IL" => "Israel",
"IT" => "Italy",
"JM" => "Jamaica",
"JP" => "Japan",
"JE" => "Jersey",
"JT" => "Johnston Island",
"JO" => "Jordan",
"KZ" => "Kazakhstan",
"KE" => "Kenya",
"KI" => "Kiribati",
"KW" => "Kuwait",
"KG" => "Kyrgyzstan",
"LA" => "Laos",
"LV" => "Latvia",
"LB" => "Lebanon",
"LS" => "Lesotho",
"LR" => "Liberia",
"LY" => "Libya",
"LI" => "Liechtenstein",
"LT" => "Lithuania",
"LU" => "Luxembourg",
"MO" => "Macau SAR China",
"MK" => "Macedonia",
"MG" => "Madagascar",
"MW" => "Malawi",
"MY" => "Malaysia",
"MV" => "Maldives",
"ML" => "Mali",
"MT" => "Malta",
"MH" => "Marshall Islands",
"MQ" => "Martinique",
"MR" => "Mauritania",
"MU" => "Mauritius",
"YT" => "Mayotte",
"FX" => "Metropolitan France",
"MX" => "Mexico",
"FM" => "Micronesia",
"MI" => "Midway Islands",
"MD" => "Moldova",
"MC" => "Monaco",
"MN" => "Mongolia",
"ME" => "Montenegro",
"MS" => "Montserrat",
"MA" => "Morocco",
"MZ" => "Mozambique",
"MM" => "Myanmar [Burma]",
"NA" => "Namibia",
"NR" => "Nauru",
"NP" => "Nepal",
"NL" => "Netherlands",
"AN" => "Netherlands Antilles",
"NT" => "Neutral Zone",
"NC" => "New Caledonia",
"NZ" => "New Zealand",
"NI" => "Nicaragua",
"NE" => "Niger",
"NG" => "Nigeria",
"NU" => "Niue",
"NF" => "Norfolk Island",
"KP" => "North Korea",
"VD" => "North Vietnam",
"MP" => "Northern Mariana Islands",
"NO" => "Norway",
"OM" => "Oman",
"PC" => "Pacific Islands Trust Territory",
"PK" => "Pakistan",
"PW" => "Palau",
"PS" => "Palestinian Territories",
"PA" => "Panama",
"PZ" => "Panama Canal Zone",
"PG" => "Papua New Guinea",
"PY" => "Paraguay",
"YD" => "People's Democratic Republic of Yemen",
"PE" => "Peru",
"PH" => "Philippines",
"PN" => "Pitcairn Islands",
"PL" => "Poland",
"PT" => "Portugal",
"PR" => "Puerto Rico",
"QA" => "Qatar",
"RO" => "Romania",
"RU" => "Russia",
"RW" => "Rwanda",
"RE" => "Réunion",
"BL" => "Saint Barthélemy",
"SH" => "Saint Helena",
"KN" => "Saint Kitts and Nevis",
"LC" => "Saint Lucia",
"MF" => "Saint Martin",
"PM" => "Saint Pierre and Miquelon",
"VC" => "Saint Vincent and the Grenadines",
"WS" => "Samoa",
"SM" => "San Marino",
"SA" => "Saudi Arabia",
"SN" => "Senegal",
"RS" => "Serbia",
"CS" => "Serbia and Montenegro",
"SC" => "Seychelles",
"SL" => "Sierra Leone",
"SG" => "Singapore",
"SK" => "Slovakia",
"SI" => "Slovenia",
"SB" => "Solomon Islands",
"SO" => "Somalia",
"ZA" => "South Africa",
"GS" => "South Georgia and the South Sandwich Islands",
"KR" => "South Korea",
"ES" => "Spain",
"LK" => "Sri Lanka",
"SD" => "Sudan",
"SR" => "Suriname",
"SJ" => "Svalbard and Jan Mayen",
"SZ" => "Swaziland",
"SE" => "Sweden",
"CH" => "Switzerland",
"SY" => "Syria",
"ST" => "São Tomé and Príncipe",
"TW" => "Taiwan",
"TJ" => "Tajikistan",
"TZ" => "Tanzania",
"TH" => "Thailand",
"TL" => "Timor-Leste",
"TG" => "Togo",
"TK" => "Tokelau",
"TO" => "Tonga",
"TT" => "Trinidad and Tobago",
"TN" => "Tunisia",
"TR" => "Turkey",
"TM" => "Turkmenistan",
"TC" => "Turks and Caicos Islands",
"TV" => "Tuvalu",
"UM" => "U.S. Minor Outlying Islands",
"PU" => "U.S. Miscellaneous Pacific Islands",
"VI" => "U.S. Virgin Islands",
"UG" => "Uganda",
"UA" => "Ukraine",
"SU" => "Union of Soviet Socialist Republics",
"AE" => "United Arab Emirates",
"GB" => "United Kingdom",
"US" => "United States",
"ZZ" => "Unknown or Invalid Region",
"UY" => "Uruguay",
"UZ" => "Uzbekistan",
"VU" => "Vanuatu",
"VA" => "Vatican City",
"VE" => "Venezuela",
"VN" => "Vietnam",
"WK" => "Wake Island",
"WF" => "Wallis and Futuna",
"EH" => "Western Sahara",
"YE" => "Yemen",
"ZM" => "Zambia",
"ZW" => "Zimbabwe",
"AX" => "Åland Islands",
);
	
echo "<div class=\"input select\">";
echo $this->Form->label('Country of Origin');
echo $this->Form->select('country_of_origin',$country_array,['empty'=>'--Select Country--']);
echo "</div>";
	
	
echo $this->Form->input('material',['label'=>'Material']);
	

echo "<div class=\"input radiobutton\"><label>Bedspread Backing Material</label>";
echo $this->Form->radio('bs_backing_material',['Poly Cotton'=>'Poly Cotton','Poly'=>'Poly']);
echo "</div>";


echo $this->Form->input('print_or_dye',['label'=>'Print or Dye']);

echo $this->Form->input('weaves',['label'=>'Weaves']);
	
echo $this->Form->input('finish',['label'=>'Finish']);
	
echo $this->Form->input('flammability',['label'=>'Flammability']);
	
echo $this->Form->input('description',['type'=>'textarea']);
	
echo $this->Form->input('care_instructions',['type'=>'textarea','label'=>'Care Instrucitons']);
	
echo "</fieldset>";

echo "<div id=\"buttonrow\">";
echo $this->Form->button('Cancel',['type'=>'button',"onclick"=>'location.replace(\'/products/fabrics/\')']);
echo $this->Form->submit('Review + Apply Changes');
echo "<div style=\"clear:both;\"></div></div>";

echo $this->Form->end();
?>
<style>
h3{ text-align: center; margin:0; }
#buttonrow button{ float:left; background:#CCC; border:1px solid #444; padding:5px !important; font-size:14px !important; color:#000 !important; }
#buttonrow .submit{ float:right; }
form{ max-width:600px; margin:30px auto 15px auto; }
fieldset{ background:#f8f8f8; margin-bottom:20px; border:1px solid #CCC; }
fieldset legend{ font-weight:bold; color:#172457; background:none !important; border-bottom:0 !important; display: inline-block !important; width: auto !important; }
fieldset label{ margin-right:10px; font-size:12px; }
fieldset div.input{ padding:5px; }
fieldset div.input.number{ width:45%; display:inline-block; }
fieldset div.input input{ padding:3px !important; }
.radiobuttons label{ display:inline-block !important; }
.submit input[type=submit]{ background:#10204B; color:#FFF; border:0; padding:10px 15px; font-size:14px; font-weight:bold; cursor:pointer; }
div.checkboxes div.input{ display:inline-block; }
	#selectedfabrics ul{ list-style:none; }
	#selectedfabrics ul li{ display:block; width:33%; float:left; margin:5px 0; font-size:12px; }
	#selectedfabrics ul li img{ width:20px; height:20px; }
</style>
<script>
$(function(){
	$('form').submit(function(){
		
	});
});
</script>
<?php 
}elseif($step=="step2"){
	echo $this->Form->create(false);
	echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);
	foreach($inputdata as $key => $value){
		if($key != 'process'){
			echo $this->Form->input($key,['type'=>'hidden','value'=>$value]);
		}
	}
	echo "<h3>Review + Apply Changes</h3>";
	echo "<fieldset id=\"selectedfabrics\"><legend>Apply Changes To <small><em>(".count($fabricids)." fabrics selected)</em></small></legend><ul>";
	foreach($selectedfabrics as $fabric){
		echo "<li><img src=\"/files/fabrics/".$fabric['id']."/".$fabric['image_file']."\" /> ".$fabric['fabric_name']." ".$fabric['color']."</li>";
	}
	echo "</ul><div style=\"clear:both;\"></div></fieldset>";

	
	$costsChanges=0;
	
	echo "<fieldset><legend>Costs</legend><dl>";
	if(strlen($inputdata['cost_per_yard_cut']) >0){
		echo "<dt>Cost Per Yard (Cut)</dt><dd>".$inputdata['cost_per_yard_cut']."</dd>";
		$costsChanges++;
	}
	if(strlen($inputdata['cost_per_yard_bolt']) >0){
		echo "<dt>Cost Per Yard (Bolt)</dt><dd>".$inputdata['cost_per_yard_bolt']."</dd>";
		$costsChanges++;
	}
	if(strlen($inputdata['cost_per_yard_case']) >0){
		echo "<dt>Cost Per Yard (Case)</dt><dd>".$inputdata['cost_per_yard_case']."</dd>";
		$costsChanges++;
	}
	
	if($costsChanges==0){
		echo "<dd><em>No cost changes submitted.</em></dd>";
	}
	echo "</dl></fieldset>";

	$metricsChanges=0;
	echo "<fieldset><legend>Metrics</legend><dl>";
	
	if(strlen($inputdata['yards_per_bolt']) >0){
		echo "<dt>Yards Per Bolt</dt><dd>".$inputdata['yards_per_bolt']."</dd>";
		$metricsChanges++;
	}
	if(strlen($inputdata['yards_per_case']) >0){
		echo "<dt>Yards Per Case</dt><dd>".$inputdata['yards_per_case']."</dd>";
		$metricsChanges++;
	}
	if(strlen($inputdata['fabric_width']) >0){
		echo "<dt>Fabric Width</dt><dd>".$inputdata['fabric_width']."</dd>";
		$metricsChanges++;
	}
	if(strlen($inputdata['vertical_repeat']) >0){
		echo "<dt>Vertical Repeat</dt><dd>".$inputdata['vertical_repeat']."</dd>";
		$metricsChanges++;
	}
	if(strlen($inputdata['horizontal_repeat']) >0){
		echo "<dt>Horizontal Repeat</dt><dd>".$inputdata['horizontal_repeat']."</dd>";
		$metricsChanges++;
	}
	if(strlen($inputdata['weight_per_sqin']) >0){
		echo "<dt>Weight Per Sq Inch</dt><dd>".$inputdata['weight_per_sqin']."</dd>";
		$metricsChanges++;
	}
	if(strlen($inputdata['minimum']) >0){
		echo "<dt>Minimum</dt><dd>".$inputdata['minimum']."</dd>";
		$metricsChanges++;
	}
	
	if($metricsChanges == 0){
		echo "<dd><em>No metrics changes submitted.</em></dd>";
	}
	echo "</dl></fieldset>";

	
	
	$otherChanges=0;
	echo "<fieldset><legend>Other Details</legend><dl>";
	if($inputdata['fabric_status'] == 'Active' || $inputdata['fabric_status']=='Discontinued'){
		echo "<div class=\"input radiobuttons\">";
		echo "<dt>Fabric Status</dt><dd>".$inputdata['fabric_status']."</dd>";
		echo "</div>";
		$otherChanges++;
	}
	

	
	if($inputdata['used_in_cc']=="Yes" || $inputdata['used_in_cc'] == "No"){
		echo "<div class=\"input radiobuttons\">";
		echo "<dt>Is this fabric used in Cubicle Curtains?</dt><dd>".$inputdata['used_in_cc']."</dd>";
		echo "</div>";
		$otherChanges++;
	}
	


	
	if($inputdata['used_in_bs']=="Yes" || $inputdata['used_in_bs'] == "No"){
		echo "<div class=\"input radiobuttons\">";
		echo "<dt>Is this fabric used in Bedspreads?</dt><dd>".$inputdata['used_in_bs']."</dd>";
		echo "</div>";
		$otherChanges++;
	}
	


	
	if($inputdata['used_in_wt']=="Yes" || $inputdata['used_in_wt'] == "No"){
		echo "<div class=\"input radiobuttons\">";
		echo "<dt>Is this fabric used in Window Treatments?</dt><dd>".$inputdata['used_in_wt']."</dd>";
		echo "</div>";
		$otherChanges++;
	}
	


	
	if($inputdata['used_in_sc']=="Yes" || $inputdata['used_in_sc'] == "No"){
		echo "<div class=\"input radiobuttons\">";
		echo "<dt>Is this fabric used in Shower Curtains?</dt><dd>".$inputdata['used_in_sc']."</dd>";
		echo "</div>";
		$otherChanges++;
	}
	


	
	if($inputdata['antimicrobial']=="Yes" || $inputdata['antimicrobial'] == "No"){
		echo "<div class=\"input radiobuttons\">";
		echo "<dt>Antimicrobial?</dt><dd>".$inputdata['antimicrobial']."</dd>";
		echo "</div>";
		$otherChanges++;
	}
	


	
	if($inputdata['railroaded']=="Yes" || $inputdata['railroaded'] == "No"){
		echo "<div class=\"input radiobuttons\">";
		echo "<dt>Railroaded?</dt><dd>".$inputdata['railroaded']."</dd>";
		echo "</div>";
		$otherChanges++;
	}
	

	
	if($inputdata['quilted']=="Yes" || $inputdata['quilted'] == "No"){
		echo "<div class=\"input radiobuttons\">";
		echo "<dt>Quilted?</dt><dd>".$inputdata['quilted']."</dd>";
		echo "</div>";
		$otherChanges++;
	}

	if(strlen($inputdata['collection']) >0){
		echo "<dt>Collection</dt><dd>".$inputdata['collection']."</dd>";
		$otherChanges++;
	}
	
	
	if($inputdata['vendors_id'] != '0' && $inputdata['vendors_id'] != ''){
		echo "<dt>Vendor</dt><dd>".$vendorslist[$inputdata['vendors_id']]."</dd>";
		$otherChanges++;
	}
	
	
	
	if(strlen($inputdata['vendor_fabric_name']) >0){
		echo "<dt>Vendor Fabric Name</dt><dd>".$inputdata['vendor_fabric_name']."</dd>";
		$otherChanges++;
	}
	
	
	if($inputdata['country_of_origin'] != '0' && $inputdata['country_of_origin'] != ''){
		echo "<dt>Country of Origin</dt><dd>".$inputdata['country_of_origin']."</dd>";
		$otherChanges++;
	}
	
	
	
	if(strlen($inputdata['material']) >0){
		echo "<dt>Material</dt><dd>".$inputdata['material']."</dd>";
		$otherChanges++;
	}
	
	if(isset($inputdata['print_or_dye']) && strlen(trim($inputdata['print_or_dye'])) >0){
		echo "<div class=\"input radiobuttons\">";
		echo "<dt>Print or Dye?</dt><dd>".$inputdata['print_or_dye']."</dd>";
		echo "</div>";
		$otherChanges++;
	}
	
	
	if(strlen($inputdata['weaves']) >0){
		echo "<dt>Weaves</dt><dd>".$inputdata['weaves']."</dd>";
		$otherChanges++;
	}
	
	
	if(strlen($inputdata['finish']) >0){
		echo "<dt>Finish</dt><dd>".$inputdata['finish']."</dd>";
		$otherChanges++;
	}
	
	if(strlen($inputdata['flammability']) >0){
		echo "<dt>Flammability</dt><dd>".$inputdata['flammability']."</dd>";
		$otherChanges++;
	}
	
	
	if(strlen($inputdata['description']) >0){
		echo "<dt>Description</dt><dd>".$inputdata['description']."</dd>";
		$otherChanges++;
	}
	
	
	if(strlen($inputdata['care_instructions']) >0){
		echo "<dt>Care Instructions</dt><dd>".$inputdata['care_instructions']."</dd>";
		$otherChanges++;
	}
	
	
	if($otherChanges==0){
		echo "<dd><em>No other changes submitted.</em></dd>";
	}
	echo "</dl></fieldset>";

	echo "<div id=\"buttonrow\">";
	echo $this->Form->button('Go Back',['type'=>'button',"onclick"=>'history.go(-1)']);
	echo $this->Form->submit('Apply Changes Now');
	echo "<div style=\"clear:both;\"></div></div>";
	echo $this->Form->end();
	?>
	<style>
h3{ text-align: center; margin:0; }
#buttonrow button{ float:left; background:#CCC; border:1px solid #444; padding:5px !important; font-size:14px !important; color:#000 !important; }
#buttonrow .submit{ float:right; }
form{ max-width:600px; margin:30px auto 15px auto; }
fieldset{ background:#f8f8f8; margin-bottom:20px; border:1px solid #CCC; }
fieldset legend{ font-weight:bold; color:#172457; background:none !important; border-bottom:0 !important; display: inline-block !important; width: auto !important; }
fieldset label{ margin-right:10px; font-size:12px; }
fieldset div.input{ padding:5px; }
fieldset div.input.number{ width:45%; display:inline-block; }
fieldset div.input input{ padding:3px !important; }
.radiobuttons label{ display:inline-block !important; }
.submit input[type=submit]{ background:#10204B; color:#FFF; border:0; padding:10px 15px; font-size:14px; font-weight:bold; cursor:pointer; }
div.checkboxes div.input{ display:inline-block; }

#selectedfabrics ul{ list-style:none; }
#selectedfabrics ul li{ display:block; width:33%; float:left; margin:5px 0; font-size:12px; }
#selectedfabrics ul li img{ width:20px; height:20px; }

fieldset dl{ font-size:12px; }
fieldset dl dd{ padding-left:30px; }
</style>
	<?php
}
?>