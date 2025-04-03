<!-- src/Template/Quotes/converttoorder.ctp -->
<style>
.converttoorder{ width:600px; margin:0 auto; }
.converttoorder h2{ text-align:center; }
</style>

<?php
if($customerData['status']=='OnHold'){
    echo "<div id=\"onholdalert\"><img src=\"/img/stopsignicon.png\" /> THIS CUSTOMER IS <em>ON HOLD</em></div>
    <style>
    #onholdalert{ text-align:center; position:fixed; z-index:9999; top:0; left:0; width:100%; background:yellow; padding:10px; font-size:20px; font-weight:bold; color:red; }
    #onholdalert img{ vertical-align:middle; width:28px; height:28px; }
    </style>";
}
?>
<div class="converttoorder form">
<h2>Convert Quote to Order</h2>
<?php /** PPSASCRUM-2 Start **/echo ($customerData['on_credit_hold']) ? '<div><span ><h2><b style="color: red;"> On Credit Hold</b><h2></span></div>' : "";/** PPSASCRUM-3 End **/?>

<hr>
<?php
echo $this->Form->create(); 


    $typeOptions=array();
    foreach($allTypes as $type){
        $typeOptions[$type['id']]=$type['type_label'];
    }
    
    echo "<div class=\"input select required\"><label>Type:</label>";
    echo $this->Form->select('type_id',$typeOptions,['empty'=>'--Select Type--','required'=>true, 'value' => $quoteData['type_id'] ]);
    echo "</div>";

	echo $this->Form->input('po_number',['label'=>'Customer PO Number','required'=>true]);
	echo "<div id=\"polookupresult\"></div>";
	
	
	
	
	echo "<h3>Order Date:</h3>
	<div id=\"orderdatepicker\"></div><br>";
	
	echo $this->Form->input('created',['type'=>'hidden','value'=>date('n/j/Y')]);
	
	
	
	$managers=array();
foreach($allUsers as $user){
    $managers[$user['id']]=$user['last_name'].', '.$user['first_name'];
}

echo "<div class=\"input selectbox required\"><label>Order/Project Manager</label>";
echo $this->Form->select('project_manager_id',$managers,['required'=>true,'empty'=>'--Select--','value'=>$orderData['project_manager_id']]);
echo "</div>";
	
	/** PPSACRUM-7 Start **/
	/*
	echo $this->Form->input('facility',['label'=>'Facility','required'=>false]);
	echo $this->Form->input('shipping_address_1',['label'=>'Ship-To Address (Line 1)','required'=>true]);
	echo $this->Form->input('shipping_address_2',['label'=>'Ship-To Address (Line 2)','required'=>false]);
	echo $this->Form->input('shipping_city',['label'=>'Ship-To City','required'=>true]);
	echo $this->Form->input('shipping_state',['label'=>'Ship-To State','required'=>true]);
	echo $this->Form->input('shipping_zipcode',['label'=>'Ship-To Zipcode','required'=>true]);
		echo $this->Form->input('attention',['label'=>'Attention','required'=>false]);
*/
	echo "<div class=\"input selectbox required\"><label>Recipient</label>";
    echo "<a href=\"#\" id=\"new_facility\"  style=\"display: block; height: 36px; color: white; width: 21%; text-align: center; padding: 6px 0px 0px; background: #74BC1F; !important; float: right !important;\" id='new_facility'>+ New Recipient </a>";

//	echo $this->Form->select('allfacility',$allFacility,['required'=>true,'empty'=>'--Select a Recipient--','id'=>'allfacility']);

echo $this->Form->input('facilitySelected',['type'=>'hidden','id'=>'facilitySelected','required'=>false]);
	echo '<input type="text" name="allfacility" id="allfacility"/>';
				
			//	echo "</fieldset>";
				
				echo "<script>
                    $(function(){
                        $('input[name=allfacility]').autocomplete({
                        
                           source: function( request, response){
                               $.ajax({
                                  'url': '/orders/searchFacilityfield/'+request.term,
                                  'dataType':'json',
                                  success: function(data){
                                      response(data);
                                  }
                               });
                           },
                           minLength: 2,
                           select: function(event,ui){
                               $.fancybox.showLoading();
                                var spiltvalue = ui.item.value.split(' :,: ');
                               //$.get('/orders/getFacilityByName/'+escapeTextFieldforURL(spiltvalue[0]),function(response){
                    	       $.get('/orders/getFacilityByID/'+spiltvalue[7],function(response){
                    	        
                    				const myArray1 = response.split(' :|: ')
    			                    $('#facilitySelected').val(myArray1[0]);
    			                    $('#facilityCode').val(myArray1[2]);
    			                    $('#facilityAttention').val(myArray1[4]);
    			                    $('#allfacility').val(spiltvalue[0]);
    			                      $('input[name=allfacility]').val(spiltvalue[0]);
                             
    			                     $('input[type=radio][name=userAddressesSelection][value=\"default\"]').prop('checked', true);
    			   $('#userAddressesSelection').val('default');
    			                    if($('input[type=radio][name=userAddressesSelection]').val() == 'default') {
                            			$.get('/orders/getFacilityAddressDetails/'+$('#facilitySelected').val(),function(data){
                            				const myArray2 = data.split(' :|: ');
                            				$('#shipping_address_1').val(myArray2[0]);
                            				$('#shipping_address_2').val(myArray2[1]);
                            				$('#shipping_city').val(myArray2[2]);
                            				$('#shipping_state').val(myArray2[3]);
                            				$('#shipping_country').val(myArray2[4]);
                                			$('#shipping_zipcode').val(myArray2[5]);
                                			$('#attentionship').val(myArray2[6]);
                                			$('#ship_to_name').val(myArray2[7]);
                            		});
    			                    }
    			                                        				$.fancybox.hideLoading();

                    			});
                        }
                    });
                    });
                    </script>";

	echo "</div>";
	echo $this->Form->input('facilityCode',['label'=>'Recipient Chain','required'=>false,'id'=>'facilityCode', 'default'=>'N/A']);

	echo $this->Form->input('facilityAttention',['label'=>'(Recipient)Attention','required'=>false,'id'=>'facilityAttention']);

// 'new'=>'Add a New Address','customer'=>'Use Customer Address'
	echo $this->Form->radio('userAddressesSelection', ['default'=>'Use Default Recipient Address','exisiting'=>'Pick a Different Address']);
	echo "<a href=\"#\" id=\"new_address\"  style=\"display: block; height: 36px; color: white; width: 21%; text-align: right; padding: 6px 0px 0px; background: ##74BC1F;!important; float: right !important;\" id='new_address'>+ New Address </a>";
	echo "<div class=\"exisitingAddress\"> ";
//	echo $this->Form->select('default_address',$shipTooptions,['required'=>false,'empty'=>'--Select address--','id'=>'default_address']);
echo $this->Form->input('addressSelected',['type'=>'hidden','id'=>'addressSelected','required'=>false]);
	echo '<input type="text" name="default_address" id="default_address"/>';
				
			//	echo "</fieldset>";
				
				echo "<script>
                    $(function(){
                        $('input[name=default_address]').autocomplete({
                        
                           source: function( request, response){
                           $('#new_address').show();
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
                               $.fancybox.showLoading();
                               var spiltvalue = ui.item.value.split(' :,: ');
                               //$.get('/orders/getShipAddressNameDetails/'+escapeTextFieldforURL(spiltvalue[0]),function(data){
                                $.get('/orders/getShipToAddressDetails/'+spiltvalue[6],function(data){
                            	
                            				const myArray2 = data.split(' :|: ');
                            			//	const myArray2 = data.split(':')
                            				 $('#default_address').val(spiltvalue[0]);
                            				$('#shipping_address_1').val(myArray2[0]);
                            				$('#shipping_address_2').val(myArray2[1]);
                            				$('#shipping_city').val(myArray2[2]);
                            				$('#shipping_state').val(myArray2[3]);
                            				$('#shipping_country').val(myArray2[4]);
                                			$('#shipping_zipcode').val(myArray2[5]);
                                			$('#attentionship').val(myArray2[6]);
                                			$('#ship_to_name').val(myArray2[7]);
                                			$('#addressSelected').val(myArray2[8]);
                                			$('#default_address').val(myArray2[7]);
                                			$('#default_address').show();
                            		});
    			                    
    			                                        				$.fancybox.hideLoading();
    			                                        				$('#new_address').hide();

                    			
                        }
                    });
                    });
                    </script>";

	echo "</div>";
	
	echo "<div class=\"defaultAddress\">";
	echo $this->Form->input('userSelection',['type'=>'hidden','value'=>'','id'=>'userSelection']);
	echo $this->Form->input('ship_to_name',['label'=>'Ship-To Name','required'=>true, 'id'=>'ship_to_name', 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_address_1',['label'=>'Ship-To Address (Line 1)','required'=>false, 'id'=>'shipping_address_1', 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_address_2',['label'=>'Ship-To Address (Line 2)','required'=>false, 'id'=>'shipping_address_2', 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_city',['label'=>'Ship-To City','required'=>false, 'id'=>'shipping_city', 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_state',['label'=>'Ship-To State','required'=>false, 'id'=>'shipping_state', 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_zipcode',['label'=>'Ship-To Zipcode','required'=>false, 'id'=>'shipping_zipcode', 'disabled'=>'disabled']);

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
	echo $this->Form->select('shipping_country',$country_list,['required'=>false,'empty'=>'--Select Country--','id'=>'shipping_country', 'disabled'=>'disabled','default' => 'US']);
	
//	echo $this->Form->input('attentionship',['label'=>'(ShipTo)Attention','required'=>false, 'id'=>'attentionship', 'disabled'=>'disabled']);

	echo "</div>";
	

	/** PPSASCRUM-7 End **/

	echo $this->Form->input('attention',['label'=>'(ShipTo)Attention','required'=>false, 'id'=>'attentions']);

	echo $this->Form->input('hasduedate',['type'=>'hidden','value'=>'1']);

	echo $this->Form->input('due',['type'=>'hidden']);
	
	echo "<h3>Ship-By Date (Due Date):</h3>
	<div id=\"datepicker\"></div><br>";


	echo "<div class=\"input selectbox required\"><label>Shipping Method</label>";
	echo $this->Form->select('shipping_method_id',$availableShippingMethods,['requried'=>true,'empty'=>'--Select a Shipping Method--']);
	echo "</div>";

	echo $this->Form->input('shipping_instructions',['type'=>'textarea','label'=>'Shipping Instructions']);

	echo $this->Form->submit('Create Order');

echo $this->Form->end();
?>
<script>
$(function(){
    
    var today=new Date();
    /**PPSASCRUM-7 start **/
     $('#default_address').hide();
     $('#new_address').hide();
    /**PPSASCRUM-7 end **/
    $( "#orderdatepicker" ).datepicker({
		minDate: new Date().setDate(today.getDate()-30),
		setDate: '<?php echo date('m/d/Y'); ?>',
		defaultDate: '<?php echo date('m/d/Y'); ?>',
		onSelect: function(dateText,instance){
			$('#created').val(dateText);
		}
	});
	
	
	$( "#datepicker" ).datepicker({
		minDate: new Date().setDate(today.getDate()-30),
		onSelect: function(dateText,instance){
			$('#due').val(dateText);
		}
	});

    
    
	$('#hasduedate').click(function(){
		if($(this).is(':checked')){
			$('div.duedatewrap').show('fast');
		}else{
			$('div.duedatewrap').hide('fast');
		}
	});
	
	
	$('form').submit(function(){
		if($('#due').val() == ''){
           alert('You must select a Ship-By Date.');
           return false;
       }
       
       if($('input#po-number').hasClass('alreadyusednumber')){
			alert('This PO Number is already in the system!');
			$('#po-number').focus();
			return false;
		}else{
		    
		    if($('select[name=project_manager_id]').val() == ''){
                alert('You must select a Project Manager');
                return false;
            }
            
			$('form').submit();
		}
	});
	
	
	$('#po-number').keyup($.debounce( 700, function(){
		$.fancybox.showLoading();
		$.get('/orders/checkponumber/'+encodeURIComponent($(this).val())+'/<?php echo $quoteData['customer_id']; ?>',function(data){
			if(data=='NO'){
				$('#po-number').removeClass('availablenumber').removeClass('alreadyusednumber').addClass('alreadyusednumber');
				$('#polookupresult').html("This PO Number already exists in the system for <b><?php echo $customerData['company_name']; ?></b>!").show('fast');
			}else{
				$('#po-number').removeClass('availablenumber').removeClass('alreadyusednumber').addClass('availablenumber');
				$('#polookupresult').html('').hide('fast');
			}
			$.fancybox.hideLoading();
		});
	}));
	
	/**PPSACRUM-7 start **/
 var typingTimer;
    var doneTypingInterval = 500;

    $('#shipping_address_1').on('input', function() {
        clearTimeout(typingTimer);
        if (($('#shipping_address_1').val().length > 3 ) && ($('#userSelection').val() != "customer")) {
            typingTimer = setTimeout(doneTyping, doneTypingInterval,'shipping_address_1',$('#shipping_address_1').val());
        }
    });
	
	$('#shipping_city').on('input', function() {
        clearTimeout(typingTimer);
        if ($('#shipping_city').val().length > 3  && ($('#userSelection').val() != "customer")) {
            typingTimer = setTimeout(doneTyping, doneTypingInterval,'shipping_city',$('#shipping_city').val());
        }
    });

	$('#shipping_state').on('input', function() {
        clearTimeout(typingTimer);
        if ($('#shipping_state').val().length > 3   && ($('#userSelection').val() != "customer")) {
            typingTimer = setTimeout(doneTyping, doneTypingInterval,'shipping_state',$('#shipping_state').val());
        }
    });

	$('#shipping_zipcode').on('input', function() {
        clearTimeout(typingTimer);
        if ($('#shipping_zipcode').val().length > 3  && ($('#userSelection').val() != "customer")) {
            typingTimer = setTimeout(doneTyping, doneTypingInterval,'shipping_zipcode',$('#shipping_zipcode').val());
        }
    });
		$('#facilityCode').on('input', function() {
        clearTimeout(typingTimer);
        if ($('#facilityCode').val().length > 2) {
            typingTimer = setTimeout(doneFacilityTyping, doneTypingInterval,'facilityCode',$('#facilityCode').val());
        }
    });
function doneFacilityTyping(type,value) {
		var query = value;
        $.ajax({
            type: "GET",
            url: "/orders/getFacilityByCode",
            data: { search: query},
            success: function(data) {
               console.log(data); // handle the search results here
			   const myArray = data.split(" :|: ");
			    $('#allfacility').val(myArray[3]);
			     $('#facilitySelected').val(myArray[0]);
			    $('#facilityCode').val(myArray[1]);
			    $('#facilityAttention').val(myArray[2]);
			    
			    $('input[type=radio][name=userAddressesSelection][value=\"default\"]').prop('checked', true);
			    
			    $.get('/orders/getFacilityAddressDetails/'+$('#allfacility').val(),function(data){
				const myArray = data.split(" :|: ");
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
					$('#shipping_country').val(myArray[4]);
    				$('#shipping_zipcode').val(myArray[5]);
    				$('#attentionship').val(myArray[6]);
    					$('#ship_to_name').val(myArray[7]);
		});
            }
        });
    }
    function doneTyping(type,value) {
		var query = value;

        $.ajax({
            type: "GET",
            url: "/orders/getShipTodetails",
            data: { search: query , type: type},
            success: function(data) {
               console.log(data); // handle the search results here
			   const myArray = data.split("  :|:  ")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
					$('#shipping_country').val(myArray[4]);
    				$('#shipping_zipcode').val(myArray[5]);
    				$('#attentionship').val(myArray[6]);
				$('#default_address').val(myArray[7]);
					$('#ship_to_name').val(myArray[8]);
				$('#default_address').show();
				$('input[type=radio][name=userAddressesSelection][value=\"exisiting\"]').prop('checked', true);
            }
        });
    }
$('select[name=allfacility]').change(function() {
	$.get('/orders/getFacilityDetails/'+$(this).val(),function(data){
		const myArray = data.split(":")
		$('#facilityCode').val(myArray[0]);
		$('#facilityAttention').val(myArray[1]);
		if($('input[type=radio][name=userAddressesSelection]').val() == 'default') {
		    	$('input[type=radio][name=userAddressesSelection][value=\"default\"]').prop('checked', true);
			$.get('/orders/getFacilityAddressDetails/'+$('#allfacility').val(),function(data){
				const myArray = data.split(" :|: ");
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
    				$('#shipping_zipcode').val(myArray[5]);
    				$('#attentionship').val(myArray[6]);
    					$('#ship_to_name').val(myArray[7]);
		});
		}
		});
});

$('select[name=default_address]').change(function() {
    if($('input[type=radio][name=userAddressesSelection]').val() == 'default') {
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
		});
    }
});



$('input[type=radio][name=userAddressesSelection]').change(function() {
	$('#userSelection').val(this.value);

    if (this.value == 'default') {
		$('#default_address').hide();
        $('#new_address').hide();
		$('#default_address').val('');
		if($('#facilitySelected').val() == ''){
            alert('You must select a Facility');
            return false;
        } else {
			$.get('/orders/getFacilityAddressDetails/'+$('#facilitySelected').val(),function(data){
				const myArray = data.split(" :|: ");
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
    			$('#shipping_zipcode').val(myArray[5]);
    			$('#attentionship').val(myArray[6]);
    			$('#ship_to_name').val(myArray[7]);
		});
		}
       
    }
    else if (this.value == 'exisiting') {
       // $('#facilitySelected').val('');
        $('#default_address').show();
        $('#new_address').hide();
		if($('#default_address').val() != '') {
			$.get('/orders/getShipAddressDetails/'+$('#default_address').val(),function(data){
				const myArray = data.split("  :|:  ")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
    			$('#shipping_zipcode').val(myArray[5]);
    			$('#attentionship').val(myArray[6]);
    			$('#ship_to_name').val(myArray[7]);
		});
		}
    }

	else if (this.value == 'new') {
        $('#default_address').hide();
        $('#new_address').show();
		$('#default_address').val('');
		$('#facilitySelected').val('');
		$('#shipping_address_1').val('');
		$('#shipping_address_2').val('');
		$('#shipping_city').val('');
		$('#shipping_state').val('');
		$('#shipping_country').val('');
    	$('#shipping_zipcode').val('');
    	$('#attentionship').val('');
    	$('#ship_to_name').val('');
    }
	else if (this.value == 'customer') {
        $('#default_address').hide();
      //  $('#facilitySelected').val('');
        $('#new_address').hide();
			$.get('/orders/getCustomerAddressDetails/<?php echo $customerData['id']?>',function(data){
				const myArray = data.split("  :|:  ")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
    			$('#shipping_zipcode').val(myArray[5]);
    			$('#attentionship').val('');
    			$('#ship_to_name').val(myArray[6]);
		
});
	}

});

$("#new_facility").on('click', function() {
        $.fancybox({
			'href':'/orders/markfacility',
			'type':'iframe',
			'autoSize':false,
			'width':680,
			'height':480,
			'modal':true,
			'beforeClose': function() {
			    
			//	$('select[name=allfacility]').change();
			 },
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
    });
	$("#new_address").on('click', function() {
        $.fancybox({
			'href':'/orders/markshipto',
			'type':'iframe',
			'iframe': {
                 'scrolling': 'no', // Disable scrolling in the iframe
                 'css': {
                        'height':  480,
                        'overflow': 'hidden' 
                         }
            },
			'autoSize':false,
			'width':680,
			'height':750,
			'modal':true,
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
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
</script>
<br><br><br><Br>
<style>
.submit input[type=submit]{ font-weight:bold; color:#FFF; background:#1F2965; font-size:14px; padding:10px 20px; border:1px solid #000; }
	
input.alreadyusednumber{ border:3px solid red !important; background:#FFCFBF; color:red; }
input.availablenumber{ border:2px solid green !important; }

#polookupresult{ display:none; font-size:12px; padding:10px; background:#FFFF73; color:red; border:1px solid maroon; margin-bottom:20px; }
</style>