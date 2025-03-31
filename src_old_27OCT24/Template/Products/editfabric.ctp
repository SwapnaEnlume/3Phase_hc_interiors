<!-- src/Template/Products/editfabric.ctp -->
<h3>Edit Fabric:</h3>
<script>
function copyhcifabname(){
	$('#vendor-fabric-name').val($('#fabric-name').val());
}
function copyhcifabcolor(){
	$('#vendor-color-name').val($('#color').val());
}

$(function(){
	$('label[for=vendor-fabric-name]').after('<button style="font-size:12px; padding:5px;" onclick="copyhcifabname()" type="button" id="copyhcifabnamebutton">Use HCI Fabric Name</button>');
	$('label[for=vendor-color-name]').after('<button style="font-size:12px; padding:5px;" onclick="copyhcifabcolor()" type="button" id="copyhcifabcolorbutton">Use HCI Fabric Color</button>');

});
</script>

<style>
label[for=vendor-fabric-name]{ float:left; width:280px; }
label[for=vendor-color-name]{ float:left; width:280px; }
button#copyhcifabnamebutton{ float:left; }
button#copyhcifabcolorbutton{ float:left; }
input#vendor-fabric-name{ clear:both; }
input#vendor-color-name{ clear:both; }
</style>

<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo $this->Form->input('fabric_name',['label'=>'HCI Fabric Name','required'=>true,'value'=>$fabricData['fabric_name']]);
echo $this->Form->input('color',['label'=>'HCI Color','required'=>true,'value'=>$fabricData['color']]);


echo "<div class=\"input selectbox\"><label>Select Vendor</label>";
echo $this->Form->select('vendors_id',$vendoroptions,['empty'=>'--Select Vendor--','value'=>$fabricData['vendors_id']]);
echo "</div>";

echo $this->Form->input('vendor_fabric_name',['label'=>'Vendor Fabric Name', 'value'=>$fabricData['vendor_fabric_name'],'required'=>true]);

echo $this->Form->input('vendor_color_name',['label'=>'Vendor Fabric Color Name','value'=>$fabricData['vendor_color_name'],'required'=>true]);



echo $this->Form->input('description',['label'=>'Fabric Description','type'=>'textarea','required'=>false,'value'=>$fabricData['description']]);

/*
echo "<fieldset><legend>Ownership</legend>";

if($fabricData['is_hci_fabric'] == '1' && $fabricData['com_fabric'] == '0'){
    //mom Roster fabric
    $ownershipValue='roster-fabric';
}elseif($fabricData['is_hci_fabric'] == '0' && $fabricData['com_fabric'] == '0'){
    //non roster MOM fabric
    $ownershipValue='mom-nonroster';
}elseif($fabricData['com_fabric'] == '1'){
    //com
    $ownershipValue='com-fabric';
}

echo $this->Form->radio('ownership',['roster-fabric'=>'HCI Roster Fabric','mom-nonroster'=>'MOM Non-Roster Fabric','com-fabric'=>'COM Fabric'],['value'=>$ownershipValue]);
echo "</fieldset>";

*/


echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Fabric Status');
echo $this->Form->radio('fabric_status',['Active'=>'Active','Discontinued'=>'Discontinued'],['value'=>$fabricData['status']]);
echo "</div>";


echo $this->Form->input('cost_per_yard_cut',['type'=>'number','step'=>'any','min'=>'0.00','label'=>'Cost Per Yard - Cut','required'=>false,'value'=>$fabricData['cost_per_yard_cut']]);

echo $this->Form->input('cost_per_yard_bolt',['type'=>'number','step'=>'any','min'=>'0.00','label'=>'Cost Per Yard - Bolt','required'=>false,'value'=>$fabricData['cost_per_yard_bolt']]);

echo $this->Form->input('cost_per_yard_case',['type'=>'number','step'=>'any','min'=>'0.00','label'=>'Cost Per Yard - Case','required'=>false,'value'=>$fabricData['cost_per_yard_case']]);

echo $this->Form->input('yards_per_bolt',['type'=>'number','min'=>'1','label'=>'Yards per Bolt','required'=>false,'value'=>$fabricData['yards_per_bolt']]);
echo $this->Form->input('yards_per_case',['type'=>'number','min'=>'1','label'=>'Yards per Case','required'=>false,'value'=>$fabricData['yards_per_case']]);


//echo $this->Form->input('price_per_yard',['label'=>'Price Per Yard','required'=>false,'value'=>$fabricData['price_per_yard']]);

//image
echo "<div style=\"padding:10px 25px;\" id=\"currentimage\">";
echo "<div><img src=\"/files/fabrics/".$fabricData['id']."/".$fabricData['image_file']."\" /></div>";
echo "<div style=\"padding:10px 0;\"><a href=\"javascript:changeImage();\">Change Image</a></div>";
echo "</div>";

echo "<div id=\"newimage\" style=\"padding:10px 25px; visibility:hidden; height:0px;\">";
echo $this->Form->input('new_image',['label'=>'New Fabric Image','required'=>false,'type'=>'file']);
echo "<div><a href=\"javascript:unchangeImage();\">Cancel Change</a></div>";
echo "</div>";

echo "<script>
function changeImage(){
	$('#currentimage').css({'visibility':'hidden','height':'0px'});
	$('#newimage').css({'visibility':'visible','height':'auto'});
}

function unchangeImage(){
	$('#currentimage').css({'visibility':'visible','height':'auto'});
	$('#newimage').css({'visibility':'hidden','height':'0px'});
}
</script>";

echo $this->Form->input('material',['label'=>'Material','required'=>false,'value'=>$fabricData['material']]);

echo $this->Form->input('print_or_dye',['label'=>'Print or Dye','required'=>false,'value'=>$fabricData['print_or_dye']]);

echo $this->Form->input('weaves',['label'=>'Weaves','required'=>false,'value'=>$fabricData['weaves']]);

echo "<div id=\"antimicrobialboolean\"><h3>Is this fabric Anti-Microbial?</h3>";
echo $this->Form->radio('antimicrobial',['1'=>'Yes','0'=>'No'],['value'=>$fabricData['antimicrobial']]);
echo "</div>";

echo "<div id=\"rrboolean\"><h3>Is this fabric Railroaded?</h3>";
echo $this->Form->radio('railroaded',['1'=>'Yes','0'=>'No'],['value'=>$fabricData['railroaded']]);
echo "</div>";

/*
echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Quilted?');
echo $this->Form->radio('quilted',['1'=>'Yes','0'=>'No'],['value'=>$fabricData['quilted']]);
echo "</div>";
*/

echo $this->Form->input('collection',['label'=>'Collection','value'=>$fabricData['collection']]);





echo $this->Form->input('finish',['label'=>'Finish','required'=>false,'value'=>$fabricData['finish']]);

echo $this->Form->input('flammability',['label'=>'Flammability','type'=>'textarea','required'=>true,'value'=>$fabricData['flammability']]);

echo $this->Form->input('fabric_width',['type'=>'number','step'=>'any','min'=>0,'label'=>'Fabric Width (inches)','required'=>true,'value'=>$fabricData['fabric_width']]);

echo $this->Form->input('vertical_repeat',['type'=>'number','step'=>'any','min'=>0,'label'=>'Vertical Repeat (inches)','required'=>true,'value'=>$fabricData['vertical_repeat']]);

echo $this->Form->input('horizontal_repeat',['type'=>'number','step'=>'any','min'=>0,'label'=>'Horizontal Repeat (inches)','required'=>true,'value'=>$fabricData['horizontal_repeat']]);

echo $this->Form->input('laundering',['label'=>'Laundering/Care Instructions','type'=>'textarea','required'=>false,'value'=>$fabricData['laundering']]);


//echo $this->Form->input('weight_per_sqin',['label'=>'Weight Per Sq Inch','required'=>false, 'value' => $fabricData['weight_per_sqin']]);

echo $this->Form->input('minimum',['label'=>'Minimum','required'=>false, 'value' => $fabricData['minimum']]);

//echo $this->Form->input('sku',['label'=>'SKU','required'=>false, 'value'=>$fabricData['sku']]);


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
echo $this->Form->select('country_of_origin',$country_array,['empty'=>'--Select Country--', 'value'=>$fabricData['country_of_origin']]);
echo "</div>";



echo "<fieldset";
if($fabricData['quilted'] == '0'){
echo " style=\"display:none;\"";
}
echo "><legend>Bedspread Backing Material</legend>";
echo $this->Form->radio('bs_backing_material',['Poly Cotton'=>'Poly Cotton','Poly'=>'Poly'],['required'=>true, 'value' => $fabricData['bs_backing_material']]);
echo "</fieldset>";


echo "<fieldset><legend>Select all products this fabric can be used in:</legend>";

if($fabricData['use_in_cc']==1){
	$ifccchecked=true;
}else{
	$ifccchecked=false;
}
echo "<label>".$this->Form->checkbox('use_in_cc',['value'=>1,'checked'=>$ifccchecked])." Cubicle Curtains</label>";

if($fabricData['use_in_bs']==1){
	$ifbschecked=true;
}else{
	$ifbschecked=false;
}
echo "<label>".$this->Form->checkbox('use_in_bs',['value'=>1,'checked'=>$ifbschecked])." Bedspreads</label>";

if($fabricData['use_in_window']==1){
	$ifwtchecked=true;
}else{
	$ifwtchecked=false;
}
echo "<label>".$this->Form->checkbox('use_in_window',['value'=>1,'checked'=>$ifwtchecked])." Window Treatments</label>";

if($fabricData['use_in_sc']==1){
	$ifscchecked=true;
}else{
	$ifscchecked=false;
}
echo "<label>".$this->Form->checkbox('use_in_sc',['value'=>1,'checked'=>$ifscchecked])." Shower Curtains</label>";

echo "</fieldset>";


echo $this->Form->button('Save Changes',['type'=>'submit']);

echo $this->Form->end();
?>


<script>
$(function(){

	$('#quilted-0').click(function(){
		$('#bs-backing-material-poly-cotton').trigger('click');
		$('#bs-backing-material-poly-cotton').parent().parent().hide();
	});

	$('#quilted-1').click(function(){
		$('#bs-backing-material-poly-cotton').parent().parent().show();
		$('#bs-backing-material-poly-cotton').prop('checked',false);
	});

});
</script>

<style>
	#antimicrobialboolean h3,#rrboolean h3{ font-size:16px; margin:0; font-weight:bold; }
	#antimicrobialboolean label,#rrboolean label{ display:inline-block; margin-right:20px; }
	div.input > label{ font-weight:bold; font-size:16px; }
</style>