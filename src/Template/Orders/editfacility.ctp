<!-- src/Template/Products/addfacility.ctp -->

<h3>Edit Recipient:</h3>
<?php
echo $this->Form->create(null);
echo $this->Form->input('facility_code',['label'=>'Recipient Chain','required'=>true ,'value'=>$faciltiyData['facility_code']]);
echo $this->Form->input('facility_name',['label'=>'Recipient Name','required'=>true, 'value'=>$faciltiyData['facility_name']]);
echo $this->Form->input('attention',['label'=>'(Recipient)Attention','required'=>false,'value'=>$faciltiyData['attention']]);

/*

echo "<div id=\"shipToselection\">Default Address";
echo $this->Form->select('default_address',$shipTooptions,['required'=>true,'empty'=>'--Select a ShipTo Address--','value'=>$faciltiyData['default_address']]);
echo "</div>";
*/
echo "<legend>Default Address </legend>";
echo "<a href=\"#\" id=\"new_address\"  style=\"display: block; height: 36px; color: white; width: 10%; text-align: center; padding: 6px 0px 0px; background: rgb(71, 1, 1) !important; float: right !important;\" id='new_address'>+ New Address </a>";

echo $this->Form->radio('new_or_existing', ['new'=>'Add a New Address','exisiting'=>'Pick an Existing Address']);
//echo $this->Form->select('default_address',$shipTooptions,['required'=>false,'empty'=>'--Select address--','id'=>'default_address','value'=>$faciltiyData['default_address']]);

echo $this->Form->input('addressSelected',['type'=>'hidden','id'=>'addressSelected','required'=>false]);
	echo '<input type="text" name="default_address" id="default_address"/>';
				
			//	echo "</fieldset>";
				
				echo "<script>
                    $(function(){
                        $('input[name=default_address]').autocomplete({
                           source: function( request, response){
                               $.ajax({
                                  'url': '/orders/searchAddressfield/'+escapeTextFieldforURL(request.term),
                                  'dataType':'json',
                                  success: function(data){
                                      response(data);
                                  }
                               });
                           },
                           minLength: 2,
                           select: function(event,ui){
                               //$.fancybox.showLoading();
                              // var spiltvalue = ui.item.value.split(' :,: ');
                              // $.get('/orders/getShipAddressNameDetails/'+spiltvalue[0],function(data){
                               var spiltvalue = ui.item.value.split(' :,: ');
                               $.get('/orders/getShipAddressNameDetails/'+escapeTextFieldforURL(spiltvalue[0]),function(data){
                            				const myArray2 = data.split(' :|: ')
                            				$('#default_address').val(spiltvalue[0]);
                            				$('#shipping_address_1').val(myArray2[0]);
                            				$('#shipping_address_2').val(myArray2[1]);
                            				$('#shipping_city').val(myArray2[2]);
                            				$('#shipping_state').val(myArray2[3]);
                            				$('#shipping_country').val(myArray2[4]);
                                			$('#shipping_zipcode').val(myArray2[5]);
                                			$('#attentionship').val(myArray2[6]);
                                			$('#ship_to_name').val(myArray2[7]);
                                			$('#addressSelected').val(myArray2[8]);$('#default_address').show();
                                			$('#addressFeilds').show();
                            		});
    			                    
    			                                        			//	$.fancybox.hideLoading();

                    			
                        }
                    });
                    });
                    </script>";

echo "<div class =\"defaultAddressselection\" id =\"defaultAddressselection\" >";
echo $this->Form->input('ship_to_name',['label'=>'Ship-To Name','id'=>'ship_to_name','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_address_1',['label'=>'ShipTo Address -Line1','id'=>'shipping_address_1','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_address_2',['label'=>'ShipTo Address -Line2','id'=>'shipping_address_2','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_city',['label'=>'ShipTo City','id'=>'shipping_city','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_state',['label'=>'ShipTo State','id'=>'shipping_state' ,'readonly' => 'readonly' ]);
$country_list = array(
    "AF" => "Afghanistan",
    "AX" => "Aland Islands",
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
    "BQ" => "Bonaire, Sint Eustatius and Saba",
    "BA" => "Bosnia and Herzegovina",
    "BW" => "Botswana",
    "BV" => "Bouvet Island",
    "BR" => "Brazil",
    "IO" => "British Indian Ocean Territory",
    "BN" => "Brunei Darussalam",
    "BG" => "Bulgaria",
    "BF" => "Burkina Faso",
    "BI" => "Burundi",
    "KH" => "Cambodia",
    "CM" => "Cameroon",
    "CA" => "Canada",
    "CV" => "Cape Verde",
    "KY" => "Cayman Islands",
    "CF" => "Central African Republic",
    "TD" => "Chad",
    "CL" => "Chile",
    "CN" => "China",
    "CX" => "Christmas Island",
    "CC" => "Cocos (Keeling) Islands",
    "CO" => "Colombia",
    "KM" => "Comoros",
    "CG" => "Congo",
    "CD" => "Congo, the Democratic Republic of the",
    "CK" => "Cook Islands",
    "CR" => "Costa Rica",
    "CI" => "Cote D'Ivoire",
    "HR" => "Croatia",
    "CU" => "Cuba",
    "CW" => "Curacao",
    "CY" => "Cyprus",
    "CZ" => "Czech Republic",
    "DK" => "Denmark",
    "DJ" => "Djibouti",
    "DM" => "Dominica",
    "DO" => "Dominican Republic",
    "EC" => "Ecuador",
    "EG" => "Egypt",
    "SV" => "El Salvador",
    "GQ" => "Equatorial Guinea",
    "ER" => "Eritrea",
    "EE" => "Estonia",
    "ET" => "Ethiopia",
    "FK" => "Falkland Islands (Malvinas)",
    "FO" => "Faroe Islands",
    "FJ" => "Fiji",
    "FI" => "Finland",
    "FR" => "France",
    "GF" => "French Guiana",
    "PF" => "French Polynesia",
    "TF" => "French Southern Territories",
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
    "HM" => "Heard Island and Mcdonald Islands",
    "VA" => "Holy See (Vatican City State)",
    "HN" => "Honduras",
    "HK" => "Hong Kong",
    "HU" => "Hungary",
    "IS" => "Iceland",
    "IN" => "India",
    "ID" => "Indonesia",
    "IR" => "Iran, Islamic Republic of",
    "IQ" => "Iraq",
    "IE" => "Ireland",
    "IM" => "Isle of Man",
    "IL" => "Israel",
    "IT" => "Italy",
    "JM" => "Jamaica",
    "JP" => "Japan",
    "JE" => "Jersey",
    "JO" => "Jordan",
    "KZ" => "Kazakhstan",
    "KE" => "Kenya",
    "KI" => "Kiribati",
    "KP" => "Korea, Democratic People's Republic of",
    "KR" => "Korea, Republic of",
    "XK" => "Kosovo",
    "KW" => "Kuwait",
    "KG" => "Kyrgyzstan",
    "LA" => "Lao People's Democratic Republic",
    "LV" => "Latvia",
    "LB" => "Lebanon",
    "LS" => "Lesotho",
    "LR" => "Liberia",
    "LY" => "Libyan Arab Jamahiriya",
    "LI" => "Liechtenstein",
    "LT" => "Lithuania",
    "LU" => "Luxembourg",
    "MO" => "Macao",
    "MK" => "Macedonia, the Former Yugoslav Republic of",
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
    "MX" => "Mexico",
    "FM" => "Micronesia, Federated States of",
    "MD" => "Moldova, Republic of",
    "MC" => "Monaco",
    "MN" => "Mongolia",
    "ME" => "Montenegro",
    "MS" => "Montserrat",
    "MA" => "Morocco",
    "MZ" => "Mozambique",
    "MM" => "Myanmar",
    "NA" => "Namibia",
    "NR" => "Nauru",
    "NP" => "Nepal",
    "NL" => "Netherlands",
    "AN" => "Netherlands Antilles",
    "NC" => "New Caledonia",
    "NZ" => "New Zealand",
    "NI" => "Nicaragua",
    "NE" => "Niger",
    "NG" => "Nigeria",
    "NU" => "Niue",
    "NF" => "Norfolk Island",
    "MP" => "Northern Mariana Islands",
    "NO" => "Norway",
    "OM" => "Oman",
    "PK" => "Pakistan",
    "PW" => "Palau",
    "PS" => "Palestinian Territory, Occupied",
    "PA" => "Panama",
    "PG" => "Papua New Guinea",
    "PY" => "Paraguay",
    "PE" => "Peru",
    "PH" => "Philippines",
    "PN" => "Pitcairn",
    "PL" => "Poland",
    "PT" => "Portugal",
    "PR" => "Puerto Rico",
    "QA" => "Qatar",
    "RE" => "Reunion",
    "RO" => "Romania",
    "RU" => "Russian Federation",
    "RW" => "Rwanda",
    "BL" => "Saint Barthelemy",
    "SH" => "Saint Helena",
    "KN" => "Saint Kitts and Nevis",
    "LC" => "Saint Lucia",
    "MF" => "Saint Martin",
    "PM" => "Saint Pierre and Miquelon",
    "VC" => "Saint Vincent and the Grenadines",
    "WS" => "Samoa",
    "SM" => "San Marino",
    "ST" => "Sao Tome and Principe",
    "SA" => "Saudi Arabia",
    "SN" => "Senegal",
    "RS" => "Serbia",
    "CS" => "Serbia and Montenegro",
    "SC" => "Seychelles",
    "SL" => "Sierra Leone",
    "SG" => "Singapore",
    "SX" => "Sint Maarten",
    "SK" => "Slovakia",
    "SI" => "Slovenia",
    "SB" => "Solomon Islands",
    "SO" => "Somalia",
    "ZA" => "South Africa",
    "GS" => "South Georgia and the South Sandwich Islands",
    "SS" => "South Sudan",
    "ES" => "Spain",
    "LK" => "Sri Lanka",
    "SD" => "Sudan",
    "SR" => "Suriname",
    "SJ" => "Svalbard and Jan Mayen",
    "SZ" => "Swaziland",
    "SE" => "Sweden",
    "CH" => "Switzerland",
    "SY" => "Syrian Arab Republic",
    "TW" => "Taiwan, Province of China",
    "TJ" => "Tajikistan",
    "TZ" => "Tanzania, United Republic of",
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
    "UG" => "Uganda",
    "UA" => "Ukraine",
    "AE" => "United Arab Emirates",
    "GB" => "United Kingdom",
    "US" => "United States",
    "UM" => "United States Minor Outlying Islands",
    "UY" => "Uruguay",
    "UZ" => "Uzbekistan",
    "VU" => "Vanuatu",
    "VE" => "Venezuela",
    "VN" => "Viet Nam",
    "VG" => "Virgin Islands, British",
    "VI" => "Virgin Islands, U.s.",
    "WF" => "Wallis and Futuna",
    "EH" => "Western Sahara",
    "YE" => "Yemen",
    "ZM" => "Zambia",
    "ZW" => "Zimbabwe"
);
    echo "<label>Ship-To Country</label>";
	echo $this->Form->select('shipping_country',$country_list,['required'=>true,'empty'=>'--Select Country--','id'=>'shipping_country', 'readonly' => 'readonly', 'disabled'=>'disabled']);
	
echo $this->Form->input('shipping_zipcode',['label'=>'ShipTo Zipcode','id'=>'shipping_zipcode' ,'readonly' => 'readonly']);

echo "</div>";


echo "</div>";


echo $this->Form->button('Save Recipient',['type'=>'submit']);

echo $this->Form->end();
?>
<script>
    $(function(){
        $('#default_address').show();
        $('#new_address').hide();
                $('#defaultAddressselection').hide();

        $('input[type=radio][name=new_or_existing][value=\"exisiting\"]').prop('checked', true);
        $('#addressSelected').val(<?php echo $faciltiyData['default_address'] ?>);
        <?php if(!empty($faciltiyData['default_address'])) {?>
        $.get('/orders/getShipAddressDetails/'+<?php echo $faciltiyData['default_address'] ?>,function(data){
				const myArray = data.split(" :|: ")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
				$('#ship_to_name').val(myArray[7]);
				 $('#default_address').val(myArray[7]);
				$('#defaultAddressselection').show();

		});
		<?php }?>
        

});

$('input[type=radio][name=new_or_existing]').change(function() {
    if (this.value == 'new') {
        $('#default_address').hide();
        $('#new_address').show();
                $('#defaultAddressselection').hide();

    }
    else if (this.value == 'exisiting') {
        $('#default_address').show();
        $('#new_address').hide();
                $('#defaultAddressselection').show();

    }

});


$("#new_address").on('click', function() {
        $.fancybox({
			'href':'/orders/markshipto',
			'type':'iframe',
			'autoSize':false,
			'width':680,
			'height':480,
			'modal':true,
			'beforeClose': function() {
				$('#defaultAddressselection').show();
			 },
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
    });
     $('select[name=default_address]').change(function() {
        	$('#defaultAddressselection').hide();
	$.get('/orders/getShipAddressDetails/'+$('#default_address').val(),function(data){
				const myArray = data.split(" :|: ")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
				$('#ship_to_name').val(myArray[7]);
				$('#defaultAddressselection').show();

		});
     });
     function escapeTextFieldforURL(thetext){
    var output =thetext.replace(/\\/g, "__bbbbslash__");
	 output = output.replace(/\//g, "__aaabslash__");
	output = output.replace('?','__question__',output);
	output = output.replace(' ','__space__',output);
	output = output.replace('#','__pound__',output);
	output = output.replace('%','__percentage__',output);
	output = output.replace('&','__ampersand__',output);
	return output;
}

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