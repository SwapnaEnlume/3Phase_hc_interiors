<style>
table.ui-datepicker-calendar td a.ui-state-active{ border: 1px solid #003eff !important;  background: #007fff !important; color:#FFF !important; }
</style>

<h1 class="pageheading">Edit Sales Order <u><?php echo $orderData['order_number']; ?></u> Details</h1>

<?php
echo $this->Form->create(null);

$typeOptions=array();
    foreach($allTypes as $type){
        $typeOptions[$type['id']]=$type['type_label'];
    }
    
    echo "<div class=\"input select required\"><label>Type:</label>";
    echo $this->Form->select('type_id',$typeOptions,['empty'=>'--Select Type--','required'=>true,'value'=>$orderData['type_id'] ]);
    echo "</div>";

echo $this->Form->input('po_number',['value'=>$orderData['po_number']]);

 echo "<h3>Order Date:</h3>
	<div id=\"orderdatepicker\"></div><br>";
	
	echo $this->Form->input('created',['type'=>'hidden','value'=>date('n/j/Y',$orderData['created'])]);
	

$managers=array();
foreach($allUsers as $user){
    $managers[$user['id']]=$user['last_name'].', '.$user['first_name'];
}

echo "<div class=\"input selectbox required\"><label>Project Manager</label>";
echo $this->Form->select('project_manager_id',$managers,['required'=>true,'empty'=>'--Select--','value'=>$orderData['project_manager_id']]);
echo "</div>";

/**PPSASCRUM-7 start **/
/**PPSASCRUM-7 start **/
/*echo $this->Form->input('facility',['value'=>$orderData['facility']]);

echo $this->Form->input('attention',['value'=>$orderData['attention']]);

echo $this->Form->input('shipping_address_1',['label'=>'Ship-To Address - Line 1','value'=>$orderData['shipping_address_1']]);
echo $this->Form->input('shipping_address_2',['label'=>'Ship-To Address - Line 2','value'=>$orderData['shipping_address_2']]);

echo $this->Form->input('shipping_city',['label'=>'Ship-To City','value'=>$orderData['shipping_city']]);

echo $this->Form->input('shipping_state',['label'=>'Ship-To State','value'=>$orderData['shipping_state']]);

echo $this->Form->input('shipping_zipcode',['label'=>'Ship-To Zipcode','value'=>$orderData['shipping_zipcode']]);

   
*/
echo "<div class=\"input selectbox required\"><label>Facility</label>";
    echo "<a href=\"#\" id=\"new_facility\"  style=\"display: block; height: 36px; color: white; width: 10%; text-align: center; padding: 6px 0px 0px; background: rgb(71, 1, 1) !important; float: right !important;\" id='new_facility'>+ New Facility </a>";

	echo $this->Form->select('allfacility',$allFacility,['required'=>true,'empty'=>'--Select a Facility--','id'=>'allfacility','value'=>$orderData['facility_id']]);
	echo "</div>";
	echo $this->Form->input('facilityCode',['label'=>'Facility Code','required'=>false,'id'=>'facilityCode']);

	echo $this->Form->input('facilityAttention',['label'=>'(Facility)Attention','required'=>false,'id'=>'facilityAttention']);
	
	echo $this->Form->radio('userAddressesSelection', [
		'default'=>'Use Default Facility Address','customer'=>'Use Customer Address', 'new'=>'Add a New Address','exisiting'=>'Pick an Existing Address'
	],['id'=>'userAddressesSelection','value'=>$orderData['userType']]);
	echo "<a href=\"#\" id=\"new_address\"  style=\"display: block; height: 36px; color: white; width: 10%; text-align: center; padding: 6px 0px 0px; background: rgb(71, 1, 1) !important; float: right !important;\" id='new_address'>+ New Address </a>";
	echo "<div class=\"exisitingAddress\"> ";
	echo $this->Form->select('default_address',$shipTooptions,['required'=>false,'empty'=>'--Select address--','id'=>'default_address','value'=>$orderData['shipto_id']]);
	echo "</div>";
	
	echo "<div class=\"defaultAddress\">";
    echo $this->Form->input('userSelection',['type'=>'hidden','value'=>$orderData['userType'],'id'=>'userSelection']);
	echo $this->Form->input('shipping_address_1',['label'=>'Ship-To Address (Line 1)','required'=>true, 'id'=>'shipping_address_1','value'=>$orderData['shipping_address_1'], 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_address_2',['label'=>'Ship-To Address (Line 2)','required'=>false, 'id'=>'shipping_address_2','value'=>$orderData['shipping_address_2'], 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_city',['label'=>'Ship-To City','required'=>true, 'id'=>'shipping_city','value'=>$orderData['shipping_city'], 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_state',['label'=>'Ship-To State','required'=>true, 'id'=>'shipping_state','value'=>$orderData['shipping_state'], 'disabled'=>'disabled']);
    echo $this->Form->input('shipping_zipcode',['label'=>'Ship-To Zipcode','required'=>true, 'id'=>'shipping_zipcode','value'=>$orderData['shipping_zipcode'], 'disabled'=>'disabled']);

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
	echo $this->Form->select('shipping_country',$country_list,['required'=>false,'empty'=>'--Select Country--','id'=>'shipping_country','value'=>$orderData['shipping_country'], 'disabled'=>'disabled','default' => 'US']);
	
	echo $this->Form->input('attentionship',['label'=>'(ShipTo)Attention','required'=>false, 'id'=>'attentionship','value'=>$orderData['attention'], 'disabled'=>'disabled']);

	echo "</div>";
	/**PPSASCRUM-7 end **/


    echo $this->Form->input('hasduedate',['type'=>'hidden','value'=>'1']);

	echo $this->Form->input('due',['type'=>'hidden','value'=>date('n/j/Y',$orderData['due'])]);
	
	echo "<h3>Ship-By Date (Due Date):</h3>
	<div id=\"datepicker\"></div><br>";


echo "<div class=\"input selectbox required\"><label>Shipping Method</label>";
echo $this->Form->select('shipping_method_id',$availableShippingMethods,['requried'=>true,'empty'=>'--Select a Shipping Method--','value'=>$orderData['shipping_method_id']]);
echo "</div>";

echo $this->Form->textarea('shipping_instructions',['value'=>$orderData['shipping_instructions']]);


echo "<div class=\"input selectbox required\"><label>Order Stage</label>";
echo $this->Form->select('order_stage',[
    'FABRIC/B&S ORDERED' => 'FABRIC/B&S ORDERED',
    'IN PRODUCTION' => 'IN PRODUCTION',
    'COMPLETE' => 'COMPLETE',
    'HOLD - NO ACTION UNTIL MEASURE' => 'HOLD - NO ACTION UNTIL MEASURE',
    'DEAD' => 'DEAD',
    'REPLACED' => 'REPLACED',
    'OTHER 1' => 'OTHER 1',
    'OTHER 2' => 'OTHER 2',
    'OTHER 3' => 'OTHER 3',
    'OTHER 4' => 'OTHER 4',
    'PROBS' => 'PROBS',
    'WILL COMPLETE BY EOM - PUP' => 'WILL COMPLETE BY EOM - PUP',
    'SVCS ONLY' => 'SVCS ONLY',
    'M1' => 'M1',
    'WH2 – INV’D – NEED TO SHIP' => 'WH2 – INV’D – NEED TO SHIP',
    'PARTIALLY COMPLETE – M1' => 'PARTIALLY COMPLETE – M1'
    ],['value' => $orderData['stage']]);
echo "</div>";



echo $this->Form->submit('Save Changes');

echo $this->Form->end();

?><br><br><br>

<script>
$(function(){
     /**PPSASCRUM-7 start **/
     $('#default_address').hide();
     $('#new_address').hide();
         var previousModalLink = null;

     $('input[type=radio][name=userAddressesSelection][value=\"<?php echo $orderData['userType'];?>\"]').prop('checked', true);
     //$("input[name='userAddressesSelection']").val("<?php echo $orderData['userType'];?>");
    $('select[name=allfacility]').change();
    

    /**PPSASCRUM-7 end **/
     var today=new Date();
     
    $( "#orderdatepicker" ).datepicker({
		minDate: new Date().setDate(today.getDate()-30),
		setDate: '<?php echo date('m/d/Y',$orderData['created']); ?>',
		defaultDate: '<?php echo date('m/d/Y',$orderData['created']); ?>',
		onSelect: function(dateText,instance){
			$('#created').val(dateText);
		}
	});
    
   
	$( "#datepicker" ).datepicker({
		minDate: new Date().setDate(today.getDate()-30),
		setDate: '<?php echo date('m/d/Y',$orderData['due']); ?>',
		defaultDate: '<?php echo date('m/d/Y',$orderData['due']); ?>',
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
	   
	   var orderDate=$('#orderdatepicker').datepicker('getDate');
	   var shipDate=$('#datepicker').datepicker('getDate');
	   
	   console.log('OrderDate='+orderDate+' |||  ShipDate='+shipDate);
	   //return false;
	   
	   if(orderDate > shipDate){
	       alert('ERROR: You cannot have a PO Date later than Ship Date.');
	       return false;
	   }
	    
	});
	/**PPSASCRUM-7 start **/
	<?php if($orderData['userType'] == 'default') {?>
	$.get('/orders/getFacilityDetails/'+$('#allfacility').val(),function(data){
		const myArray = data.split(":")
		$('#facilityCode').val(myArray[0]);
		$('#facilityAttention').val(myArray[1]);
		if($('input[type=radio][name=userAddressesSelection]').val() == 'default') {
			$.get('/orders/getFacilityAddressDetails/'+$('#allfacility').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
				$('#default_address').val('');
		    });
		}
	});
		<?php } ?>
		<?php if($orderData['userType'] == 'exisiting') {?>
			$('#default_address').show();
     		$('#new_address').hide();
			$.get('/orders/getShipAddressDetails/'+$('#default_address').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
					$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
		});
		<?php } ?>	
		<?php if($orderData['userType'] == 'customer') {?>
			$('#default_address').hide();
			$('#default_address').val('');
     		$('#new_address').hide();
     //	$("input[name='userAddressesSelection']").val("<?php echo $orderData['userType'];?>");
			$.get('/orders/getCustomerAddressDetails/<?php echo $orderData['customer_id']?>',function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
});
		<?php } ?>
	
});

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
    			   const myArray = data.split(":")
    			    $('#allfacility').val(myArray[0]);
    			    $('#facilityCode').val(myArray[1]);
    			    $('#facilityAttention').val(myArray[2]);
    			    
    			    <?php if($orderData['userType'] != 'customer') { ?>
    			          $('input[type=radio][name=userAddressesSelection][value=\"default\"]').prop('checked', true);
            			  $.get('/orders/getFacilityAddressDetails/'+$('#allfacility').val(),function(data){
            				const myArray = data.split(":")
            				$('#shipping_address_1').val(myArray[0]);
            				$('#shipping_address_2').val(myArray[1]);
            				$('#shipping_city').val(myArray[2]);
            				$('#shipping_state').val(myArray[3]);
            				$('#shipping_country').val(myArray[4]);
        			    	$('#shipping_zipcode').val(myArray[5]);
        			    	$('#attentionship').val(myArray[6]);
        			    	
            		    });
    			   <?php } ?>
    			    
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
			   const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
				$('#default_address').val(myArray[7]);
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
			$.get('/orders/getFacilityAddressDetails/'+$('#allfacility').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
		});
		}
		});
});

$('select[name=default_address]').change(function() {
	$.get('/orders/getShipAddressDetails/'+$('#default_address').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
		});
});


$('input[type=radio][name=userAddressesSelection]').change(function() {
   	$('#userSelection').val(this.value);
	var checkedValue = $( 'input[name=userAddressesSelection]:checked' ).val();

    if ($('#userSelection').val() == 'default') {
		$('#default_address').hide();
        $('#new_address').hide();
		if($('select[name=allfacility]').val() == ''){
            alert('You must select a Facility');
            return false;
        } else {
            $('#shipping_address_1').val('');
				$('#shipping_address_2').val('');
				$('#shipping_city').val('');
				$('#shipping_state').val('');
				$('#shipping_zipcode').val('');
				$('#shipping_country').val('');
				$('#attentionship').val('');
				$('#default_address').val('');
			$.get('/orders/getFacilityAddressDetails/'+$('#allfacility').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
					$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
		});
		}
       
    }
    else if ($('#userSelection').val() == 'exisiting') {
        $('#default_address').show();
        $('#new_address').hide();
		if($('#default_address').val() != '') {
		    $('#shipping_address_1').val('');
				$('#shipping_address_2').val('');
				$('#shipping_city').val('');
				$('#shipping_state').val('');
				$('#shipping_zipcode').val('');
				$('#shipping_country').val('');
				$('#attentionship').val('');
				$('#default_address').val('');
			$.get('/orders/getShipAddressDetails/'+$('#default_address').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
		});
		}
    }

	else if ($('#userSelection').val() == 'new') {
        $('#default_address').hide();
		$('#default_address').val('');
		$('#shipping_address_1').val('');
				$('#shipping_address_2').val('');
				$('#shipping_city').val('');
				$('#shipping_state').val('');
				$('#shipping_zipcode').val('');
				$('#attentionship').val('');
				$('#default_address').val('');
        $('#new_address').show();
    }
	else if ($('#userSelection').val() == 'customer') {
        $('#default_address').hide();
        $('#new_address').hide();
		$('#default_address').val('');
		
		       $('#shipping_address_1').val('');
				$('#shipping_address_2').val('');
				$('#shipping_city').val('');
				$('#shipping_state').val('');
				$('#shipping_country').val('');
				$('#shipping_zipcode').val('');
				$('#attentionship').val('');
				$('#default_address').val('');
			$.get('/orders/getCustomerAddressDetails/<?php echo $orderData['customer_id']?>',function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
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
			
			'afterShow': function(current, previous) {
			    //load(localStorage.getItem("options"), localStorage.getItem("shipping_address_1"), localStorage.getItem("shipping_address_2"), localStorage.getItem("shipping_city"), localStorage.getItem("shipping_state"), localStorage.getItem("shipping_country"), localStorage.getItem("shipping_zipcode"), localStorage.getItem("id"));
			 },
			 'beforeClose' : function() {
			     localStorage.setItem("shipping_address_1",''); 
			     localStorage.setItem("shipping_address_2",'');
			     localStorage.setItem("shipping_city",'');
			     localStorage.setItem("shipping_state",'');
			      localStorage.setItem("shipping_country",'');
			     localStorage.setItem("shipping_zipcode",'');
			     localStorage.setItem("facilityDetails",'');
			     localStorage.setItem('options', '');
			     localStorage.setItem('lastHref','');
			     localStorage.setItem('default_address', '');
			     $('select[name=allfacility]').change();
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
			'autoSize':false,
			'width':680,
			'height':480,
			'modal':true,
			'beforeClose': function() {
				$('select[name=default_address]').change();
				parent.$('input[type=radio][name=userAddressesSelection][value=\"exisiting\"]').prop('checked', true)
			 },
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
		
    });

	function getAdresses() {
		$.get('/orders/getShipAddressDetails/'+$('#default_address').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
		});
	}
	/**PPSASCRUM-7 end **/
</script>