<!-- src/Template/Products/addfabric.ctp -->

<link rel="stylesheet" href="/css/jquery.Jcrop.min.css">
<script src="/js/jquery.Jcrop.min.js"></script>
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

//on enter keydown treat it as tab
$(function(){
    $(document).on('keydown', 'input:visible, select:visible, button:visible,feildset:visible, [type="radio"]:visible, [type="checkbox"]:visible, [tabindex]', function(e) {
            if (e.which === 13) { // 13 is the Enter key code
                e.preventDefault(); // Prevent default action, which is form submission
                moveToNextFocusableElement(this);
            }
        });
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

<h3>Add New Fabric:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo $this->Form->input('fabric_name',['label'=>'HCI Fabric Name','required'=>true]);
echo $this->Form->input('color',['label'=>'HCI Color','required'=>true]);



echo "<div id=\"vendorselection\"><h3>Vendor</h3>";
echo $this->Form->select('vendor_id',$vendoroptions,['required'=>true,'empty'=>'--Select Vendor--']);
echo "</div>";

echo $this->Form->input('vendor_fabric_name',['label'=>'Vendor Fabric Name','required'=>true]);

echo $this->Form->input('vendor_color_name',['label'=>'Vendor Fabric Color Name','required'=>true]);



echo $this->Form->input('description',['label'=>'Fabric Description','type'=>'textarea','required'=>false]);

/*
echo "<fieldset><legend>Ownership</legend>";
echo $this->Form->radio('ownership',['roster-fabric'=>'HCI Roster Fabric','mom-nonroster'=>'MOM Non-Roster Fabric','com-fabric'=>'COM Fabric'],['value'=>'mom-nonroster']);
echo "</fieldset>";
*/

echo $this->Form->input('ownership',['type'=>'hidden','value'=>'mom-nonroster']);


echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Fabric Status');
echo $this->Form->radio('fabric_status',['Active'=>'Active','Discontinued'=>'Discontinued'],['required'=>true,'value'=>'Active']);
echo "</div>";


echo $this->Form->input('cost_per_yard_cut',['type'=>'number','step'=>'any','min'=>'0.00','label'=>'Cost Per Yard - Cut','required'=>false,'value'=>'0.00']);
echo $this->Form->input('cost_per_yard_bolt',['type'=>'number','step'=>'any','min'=>'0.00','label'=>'Cost Per Yard - Bolt','required'=>false,'value'=>'0.00']);
echo $this->Form->input('cost_per_yard_case',['type'=>'number','step'=>'any','min'=>'0.00','label'=>'Cost Per Yard - Case','required'=>false,'value'=>'0.00']);

echo $this->Form->input('yards_per_bolt',['type'=>'number','min'=>'0.01','step'=>'any','label'=>'Yards per Bolt','required'=>true,'value'=>'0.00']);
echo $this->Form->input('yards_per_case',['type'=>'number','min'=>'0.01','step'=>'any','label'=>'Yards per Case','required'=>true,'value'=>'0.00']);


echo "<div class=\"input\" id=\"imageselection\">";
echo "<label>Fabric Image</label>";
echo "<div id=\"imageselectradios\"><input type=\"radio\" name=\"imagemethod\" value=\"printscreencapture\" id=\"imagemethodprintscreen\" /><label for=\"imagemethodprintscreen\">PrintScreen Capture</label> <input type=\"radio\" name=\"imagemethod\" value=\"fileupload\" id=\"imagemethodfile\" /><label for=\"imagemethodfile\">File Upload</label></div>";
/*PPSASCRUM-83: start*/
echo "<div id=\"imageuploadfield\" style=\"display:none;\"><input type=\"file\" name=\"imageuploadfile\" accept=\"image/jpeg, image/png, image/gif\" /></div>";
/*PPSASCRUM-83: end*/
echo "<div id=\"printscreencapturefield\" style=\"display:none;\">
	<div id=\"pastebutton\" style=\"display:none; background:#FCF9D8; border:2px solid red; color:#660000; font-weight:bold; padding:10px; margin:10px 0;\"><!--<button onclick=\"doCaptureClipboardImage()\">Paste from Clipboard</button>-->Press CTRL and V keys on your keyboard at the same time to paste the image from your clipboard.</div>
	
	
	<canvas id=\"pastecropareawrap\" width=\"900\" height=\"650\"></canvas>
	
	<div id=\"step2instructions\" style=\"display:none; background:#FCF9D8; border:2px solid red; color:#660000; font-weight:bold; padding:10px; margin:10px 0;\">Crop the pasted image to only include the portion you want as this fabric image. 
	<button id=\"donecroppingbutton\" onclick=\"finishedCropping()\" type=\"button\">Click Here when done Cropping</button></div>
	
	<input type=\"hidden\" name=\"cropx1\" value=\"0\">
	<input type=\"hidden\" name=\"cropx2\" value=\"0\">
	<input type=\"hidden\" name=\"cropy1\" value=\"0\">
	<input type=\"hidden\" name=\"cropy2\" value=\"0\">
	<input type=\"hidden\" name=\"cropw\" value=\"0\">
	<input type=\"hidden\" name=\"croph\" value=\"0\">
</div>";
echo "</div>";


echo $this->Form->input('material',['label'=>'Material','required'=>false]);

echo $this->Form->input('print_or_dye',['label'=>'Print or Dye','required'=>false]);

echo $this->Form->input('weaves',['label'=>'Weaves','required'=>false]);

echo "<div id=\"antimicrobialboolean\"><h3>Is this fabric Anti-Microbial?</h3>";
echo $this->Form->radio('antimicrobial',['1'=>'Yes','0'=>'No'],['required'=>true,'value'=>'0']);
echo "</div>";

echo "<div id=\"railroadedboolean\"><h3>Is this fabric Railroaded?</h3>";
echo $this->Form->radio('railroaded',['1'=>'Yes','0'=>'No'],['required'=>true]);
echo "</div>";

/*
echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Quilted?');
echo $this->Form->radio('quilted',['1'=>'Yes','0'=>'No'],['required'=>true,'value'=>'0']);
echo "</div>";
*/

echo $this->Form->input('quilted',['type'=>'hidden','value'=>'0']);


echo $this->Form->input('collection',['label'=>'HCI Collection']);

echo $this->Form->input('finish',['label'=>'Finish','required'=>false]);

echo $this->Form->input('flammability',['label'=>'Flammability','type'=>'textarea','required'=>true]);

/* PPSASCRUM-358: start */
echo $this->Form->input('pfas_status', ['type' => 'select', 'empty' => '--Select Status--', 'options' => ['free-of-pfas' => 'Free of PFAS', 'contains-pfas' => 'Contains PFAS', 'not-verified' => 'Not Verified'], 'label' => 'PFAS Status', 'required' => true]);
/* PPSASCRUM-358: end */

echo $this->Form->input('fabric_width',['type'=>'number','label'=>'Fabric Width (inches)','step'=>'any','min'=>0,'required'=>true]);

echo $this->Form->input('vertical_repeat',['type'=>'number','step'=>'any', 'label'=>'Vertical Repeat (inches)','min'=>0,'required'=>true,'value'=>'0']);

echo $this->Form->input('horizontal_repeat',['type'=>'number','step'=>'any','label'=>'Horizontal Repeat (inches)','min'=>0,'required'=>true,'value'=>'0']);

echo $this->Form->input('laundering',['label'=>'Laundering/Care Instructions','type'=>'textarea','required'=>false]);



//echo $this->Form->input('weight_per_sqin',['label'=>'Weight Per Sq Inch','required'=>false]);
echo $this->Form->input('weight_per_sqin',['type'=>'hidden','value'=>'0.000285']);


echo $this->Form->input('minimum',['label'=>'Minimum','required'=>false]);

//echo $this->Form->input('sku',['label'=>'SKU','required'=>false]);


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

/*
echo "<fieldset><legend>Bedspread Backing Material</legend>";
echo $this->Form->radio('bs_backing_material',['Poly Cotton'=>'Poly Cotton','Poly'=>'Poly', 'NA' => 'NA'],['value' => 'Poly Cotton', 'required'=>true]);
echo "</fieldset>";
*/
echo $this->Form->input('bs_backing_material',['type'=>'hidden','value'=>'Poly Cotton']);




echo "<fieldset><legend>Select all products this fabric can be used in:</legend>";
echo "<label>".$this->Form->checkbox('use_in_cc',['value'=>1])." Cubicle Curtains</label>";
echo "<label>".$this->Form->checkbox('use_in_bs',['value'=>1])." Bedspreads</label>";
echo "<label>".$this->Form->checkbox('use_in_window',['value'=>1])." Window Treatments</label>";
echo "<label>".$this->Form->checkbox('use_in_sc',['value'=>1])." Shower Curtains</label>";
echo "</fieldset>";


echo $this->Form->button('Add This Fabric',['type'=>'submit']);

echo $this->Form->end();
?>
<script>
var jcrop_api;
var canvas;


function doCaptureClipboardImage(){
	
}


function finishedCropping(){
	
	$('#step2instructions').css('display','none');
	
	$.fancybox.showLoading();
	$.ajax({
		'url':'/products/uploadimagefilefromclipboard/',
		'method':'POST',
		'data':{
			'imagefile':canvas.toDataURL(),
			'x1':$('input[name=cropx1]').val(),
			'y1':$('input[name=cropy1]').val(),
			'x2':$('input[name=cropx2]').val(),
			'y2':$('input[name=cropy2]').val(),
			'w':$('input[name=cropw]').val(),
			'h':$('input[name=croph]').val()
		},
		'success':function(value){
			$('#printscreencapturefield').html(value);
			$('#imageselectradios').css('visibility','hidden');
			$.fancybox.hideLoading();
		}
	});
}
	
function doneCoords(c){
	
	$('input[name=cropx1]').val(Math.round(c.x));
	$('input[name=cropx2]').val(Math.round(c.x2));
	$('input[name=cropy1]').val(Math.round(c.y));
	$('input[name=cropy2]').val(Math.round(c.y2));
	$('input[name=cropw]').val(Math.round(c.w));
	$('input[name=croph]').val(Math.round(c.h));
}

function showCoords(c){
	//nothing to do
}	

function clearCoords(){
	//nothing to do
}
	
$(function(){

	$('#quilted-0').click(function(){
		$('#bs-backing-material-poly-cotton').trigger('click');
		$('#bs-backing-material-poly-cotton').parent().parent().hide();
	});

	$('#quilted-1').click(function(){
		$('#bs-backing-material-poly-cotton').parent().parent().show();
		$('#bs-backing-material-poly-cotton').prop('checked',false);
	});


	$('#imageselection input[type=radio]').click(function(){
		if($('#imagemethodprintscreen').is(':checked')){
			$('#printscreencapturefield').show();
			$('#pastebutton').show();
			$('#imageuploadfield').hide();
			
			/*PPSASCRUM-83: start*/
			$('#imageselection').find('div.error').remove();
			/*PPSASCRUM-83: end*/
			
			//draw the stuff
			//#pastecropareawrap
			
			var CLIPBOARD = new CLIPBOARD_CLASS("pastecropareawrap", true);

			
			function CLIPBOARD_CLASS(canvas_id, autoresize) {
				var _self = this;
				canvas = document.getElementById(canvas_id);
				var ctx = document.getElementById(canvas_id).getContext("2d");

				//handlers
				document.addEventListener('paste', function (e) { _self.paste_auto(e); }, false);

				//on paste
				this.paste_auto = function (e) {
					if (e.clipboardData) {
						var items = e.clipboardData.items;
						if (!items) return;

						//access data directly
						for (var i = 0; i < items.length; i++) {
							if (items[i].type.indexOf("image") !== -1) {
								//image
								var blob = items[i].getAsFile();
								var URLObj = window.URL || window.webkitURL;
								var source = URLObj.createObjectURL(blob);
								this.paste_createImage(source);
							}
						}
						e.preventDefault();
					}
				};


				//draw pasted image to canvas
				this.paste_createImage = function (source) {
					var pastedImage = new Image();
					pastedImage.onload = function () {
						if(autoresize == true){
							//resize
							var newwidth=0;
							var newheight=0;
							/*
							if(pastedImage.width > 900){
								newwidth=900;
								var imgratio=(900/pastedImage.width);
								newheight=Math.round((imgratio*pastedImage.height));
							}else{
							*/
								newwidth=pastedImage.width;
								newheight=pastedImage.height;
							//}
							
							/*
							canvas.width = newwidth;
							canvas.height = newheight;
							*/
							canvas.width = pastedImage.width;
							canvas.height = pastedImage.height;
							
							
							$('#pastecropareawrap').Jcrop({
								allowSelect: true,
								allowMove: true,
								allowResize: true,
								onChange: showCoords,
      							onSelect: doneCoords,
      							onRelease:  clearCoords,
								trueSize: [pastedImage.width,pastedImage.height],
								aspectRatio:1
							},function(){
								jcrop_api = this;
								jcrop_api.setSelect([130, 65, 130 + 350, 65 + 285]);
								jcrop_api.setOptions({ bgFade: true });
               					jcrop_api.ui.selection.addClass('jcrop-selection');
								$('#pastebutton').hide();
								$('#step2instructions').css('display','inline-block');
               					
							});
										
						}else{
							//clear canvas
							ctx.clearRect(0, 0, 300, 300);
							
						}
						ctx.drawImage(pastedImage, 0, 0, newwidth, newheight);


					};
					pastedImage.src = source;
					
				};
			}
			
			
			
			
		}else if($('#imagemethodfile').is(':checked')){
			$('#printscreencapturefield').hide();
			$('#imageuploadfield').show();
		}
	});
	
	
	$('#content form').submit(function(){
		if(!$('#imagemethodfile').is(':checked') && !$('#imagemethodprintscreen').is(':checked')){
			alert('You must provide an image for the fabric. Select a method of adding the image before continuing.');
			return false;
		}else if($('#imagemethodprintscreen').is(':checked')){
			if($('input[name=croppedimagefilename]').length != 1){
				alert('You must continue all steps for adding a PrintScreen capture and crop the image for this Fabric.');
				return false;
			}
		}else if($('#imagemethodfile').is(':checked')){
			if($('input[name=imageuploadfile]').val() == ''){
				alert('You must select a file to upload for this Fabric image');
				return false;
			}
		}
		
	});


	$('#cost-per-yard-cut,#cost-per-yard-bolt,#cost-per-yard-case').change(function(){
		if(parseFloat($('#cost-per-yard-cut').val()) > 0){
			if(parseFloat($('#cost-per-yard-bolt').val()) > 0){
				if(parseFloat($('#cost-per-yard-bolt').val()) > parseFloat($('#cost-per-yard-cut').val())){
					if(!$('#cost-per-yard-bolt').parent().find('div.error').length){
						$('#cost-per-yard-bolt').parent().append('<div class="error">Bolt per-yard prices cannot be higher than Cut per-yard prices</div>');
					}
					$('#cost-per-yard-bolt').addClass('invalid');
				}else{
					$('#cost-per-yard-bolt').removeClass('invalid');
					$('#cost-per-yard-bolt').parent().find('div.error').remove();
				}

				if(parseFloat($('#cost-per-yard-case').val()) > 0){
					if(parseFloat($('#cost-per-yard-case').val()) > parseFloat($('#cost-per-yard-bolt').val())){
						if(!$('#cost-per-yard-case').parent().find('div.error').length){
							$('#cost-per-yard-case').parent().append('<div class="error">Case per-yard prices cannot be higher than Bolt per-yard prices</div>');
						}
						$('#cost-per-yard-case').addClass('invalid');
					}else{
						$('#cost-per-yard-case').removeClass('invalid');
						$('#cost-per-yard-case').parent().find('div.error').remove();
					}
				}else{
					$('#cost-per-yard-case').removeClass('invalid');
					$('#cost-per-yard-case').parent().find('div.error').remove();
				}
			}else{
				$('#cost-per-yard-bolt').removeClass('invalid');
				$('#cost-per-yard-bolt').parent().find('div.error').remove();
			}
		}else{
			$('#cost-per-yard-bolt').removeClass('invalid');
			$('#cost-per-yard-case').removeClass('invalid');
			$('#cost-per-yard-case').parent().find('div.error').remove();
			$('#cost-per-yard-bolt').parent().find('div.error').remove();
		}
	});

	$('#yards-per-case,#yards-per-bolt').change(function(){
		var ypcerrors=0;
		var ypberrors=0;

		if(parseFloat($('#yards-per-bolt').val()) > 0.00){
			if(parseFloat($('#yards-per-case').val()) > 0.00){

				if(parseFloat($('#yards-per-case').val()) <= parseFloat($('#yards-per-bolt').val())){
					//per case must be bigger than per bolt!
					$('#yards-per-case').addClass('invalid');

					if(!$('#yards-per-case').parent().find('div.error').length){
						$('#yards-per-case').parent().append('<div class="error">Yards Per Case must be bigger than Yards Per Bolt</div>');
						ypcerrors++;
					}
				}else{
					$('#yards-per-case').removeClass('invalid');
					$('#yards-per-case').parent().find('div.error').remove();
				}

				$('#yards-per-case').parent().find('div.error.zero').remove();
			}else{
				//needs to be bigger than zero!
				$('#yards-per-case').addClass('invalid');
				if(!$('#yards-per-case').parent().find('div.error').length){
					$('#yards-per-case').parent().append('<div class="error zero">Yards Per Case must be bigger than zero</div>');
					ypberrors++;
				}
			}

			$('#yards-per-bolt').parent().find('div.error.zero').remove();
			
		}else{
			//needs to be bigger than zero!
			$('#yards-per-bolt').addClass('invalid');
			if(!$('#yards-per-bolt').parent().find('div.error').length){
				$('#yards-per-bolt').parent().append('<div class="error zero">Yards Per Bolt must be bigger than zero</div>');
				ypberrors++;
			}
		}


		if(ypcerrors==0){
			$('#yards-per-case').removeClass('invalid');
		}

		if(ypberrors==0){
			$('#yards-per-bolt').removeClass('invalid');
		}
	});
	
	/*PPSASCRUM-83: start*/
    $('input[type=file][name=imageuploadfile]').change(function (event) {
        if (this.val != '') {
            var errorCount = 0;
            var errorMessage = 'Image ';
            var fileObj = event.target.files[0];
            // if (fileObj.size > 4 * 1024 * 1024) {
            //     errorCount++;
            //     errorMessage += 'file-size should not be greater than 4 MB';
            // }
            var fileExtension = fileObj.name.split('.').pop();
            /*PPSASCRUM-185: start*/
            var validImageExtensions = ['jpg', 'jpeg', 'gif', 'png'];
			/*PPSASCRUM-185: end*/

            if (!validImageExtensions.includes(fileExtension)) {
                errorCount++;
                // if (errorCount > 1) errorMessage += ' and ';
                errorMessage += 'should be either of JPG, GIF, or PNG file format';
            }
            $('#imageselection').find('div.error').remove();
            if (errorCount > 0) {
                event.target.value = '';
                $('#imageselection').append(`<div class="error">${errorMessage}</div>`);
            }
        }
    });
    /*PPSASCRUM-83: end*/
	
	/* PPSASCRUM-358: start */
	$('select#pfas-status').change(function() {
		$('select#pfas-status option').each(function(index, element) {
			let currentPfasStatus = $(element);
			if (currentPfasStatus.is(':selected')) {
				currentPfasStatus.attr('selected', 'selected');
			} else {
				currentPfasStatus.removeAttr('selected');
			}
		});
	});
	/* PPSASCRUM-358: end */
	
});
</script>
<style>
div.input div.error{ display:inline-block; margin:0 0 20px 10px; background:#F9F3B7; border:2px solid red; color:red; padding:10px; font-size:12px; font-weight:bold; }

input.invalid{ border:2px solid red !important; }

#antimicrobialboolean h3{ font-size:16px; margin:0; font-weight:bold; }
#antimicrobialboolean label{ display:inline-block; margin-right:20px; }
div.input > label{ font-weight:bold; font-size:16px; }
	
#railroadedboolean h3{ font-size:16px; margin:0; font-weight:bold; }
#railroadedboolean label{ display:inline-block; margin-right:20px; }

	#vendorselection h3{ font-size:16px; margin:0; font-weight:bold; }
	
	#donecroppingbutton{ background:#1F5B1C; margin:0px 0 0 0; padding:5px !important; font-weight:bold; font-size:14px; border:1px solid #000; }
	
	#imageselection{ margin:15px 0 35px 0; }
	#imageselection input[type=radio]{ margin:0 0 0 0 !important; }
	
	img.croppedimage{ 
		margin:10px 0 30px 10px;
		-webkit-box-shadow: 3px 3px 33px 1px rgba(0,0,0,0.75);
		-moz-box-shadow: 3px 3px 33px 1px rgba(0,0,0,0.75);
		box-shadow: 3px 3px 33px 1px rgba(0,0,0,0.75);
		width:185px; height:auto;
	}
	#pastecropareawrap{ background:url('/paste/default2.gif'); max-width:900px; margin:0 auto; }
</style>