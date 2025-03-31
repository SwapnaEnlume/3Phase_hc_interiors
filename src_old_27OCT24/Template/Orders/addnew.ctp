<!-- src/Template/Orders/addnew.ctp -->
<h1 class="pageheading">Create New Order</h1>

<?php

echo $this->Form->create(null);

	echo "<fieldset>
	<legend>Customer Selection</legend>
	<div class=\"input text required\">";
	echo $this->Form->label('Select existing customer');
	echo $this->Form->select('customer_id',$customers,['empty'=>'--Select A Company--']);
	echo "<div id=\"customerContactsBlock\" style=\"display:none;\">";
	echo $this->Form->label('Select customer contact');
	echo $this->Form->select('contact_id',[],['empty'=>'--Contacts--']);
	echo "</div>			
	</div>
	</fieldset>";

    $typeOptions=array();
    foreach($allTypes as $type){
        $typeOptions[$type['id']]=$type['type_label'];
    }
    
    echo "<div class=\"input select required\"><label>Type:</label>";
    echo $this->Form->select('type_id',$typeOptions,['empty'=>'--Select Type--','required'=>true]);
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
		/*echo $this->Form->input('facility',['label'=>'Facility','required'=>false]);
	echo $this->Form->input('shipping_address_1',['label'=>'Ship-To Address (Line 1)','required'=>false]);
	echo $this->Form->input('shipping_address_2',['label'=>'Ship-To Address (Line 2)','required'=>false]);
	echo $this->Form->input('shipping_city',['label'=>'Ship-To City','required'=>false]);
	echo $this->Form->input('shipping_state',['label'=>'Ship-To State','required'=>false]);
	echo $this->Form->input('shipping_zipcode',['label'=>'Ship-To Zipcode','required'=>false]);
		echo $this->Form->input('attention',['label'=>'Attention','required'=>false]);
*/
	echo "<div class=\"input selectbox required\"><label>Recipient</label>";
    echo "<a href=\"#\" id=\"new_facility\"  style=\"display: block; height: 36px; color: white; width: 12%; text-align: center; padding: 6px 0px 0px; background: #74BC1F !important; float: right !important;\" id='new_facility'>+ New Recipient </a>";

	//echo $this->Form->select('allfacility',$allFacility,['required'=>true,'empty'=>'--Select a Recipient--','id'=>'allfacility']);
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
                               
                               $.get('/orders/getFacilityByName/'+escapeTextFieldforURL(spiltvalue[0]),function(response){
                    	         $('input[name=allfacility]').val(spiltvalue[0]);
                    				const myArray1 = response.split(' :|: ')
    			                    $('#facilitySelected').val(myArray1[0]);
    			                    $('#facilityCode').val(myArray1[2]);
    			                    $('#facilityAttention').val(myArray1[4]);
    			                    $('input[name=allfacility]').val(myArray1[1]);
    			                     $('input[type=radio][name=userAddressesSelection][value=\"default\"]').prop('checked', true);
    			   $('#userAddressesSelection').val('default');
    			                    if($('input[type=radio][name=userAddressesSelection]').val() == 'default') {
                            			$.get('/orders/getFacilityAddressDetails/'+$('#facilitySelected').val(),function(data){
                            				const myArray2 = data.split(' :|: ')
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

//, 'new'=>'Add a New Address','customer'=>'Use Customer Address'
	echo $this->Form->radio('userAddressesSelection', ['default'=>'Use Default Recipient Address','exisiting'=>'Pick a Different Address'],['id'=>'userAddressesSelection']);
	echo "<a href=\"#\" id=\"new_address\"  style=\"display: block; height: 36px; color: white; width: 10%; text-align: center; padding: 6px 0px 0px; background: #74BC1F !important; float: right !important;\" id='new_address'>+ New Address </a>";
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
                               $.get('/orders/getShipAddressNameDetails/'+escapeTextFieldforURL(spiltvalue[0]),function(data){
                            				const myArray2 = data.split('  :|:  ')
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

	echo $this->Form->input('shipping_address_1',['label'=>'Ship-To Address (Line 1)','required'=>true, 'id'=>'shipping_address_1', 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_address_2',['label'=>'Ship-To Address (Line 2)', 'id'=>'shipping_address_2', 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_city',['label'=>'Ship-To City','required'=>true, 'id'=>'shipping_city', 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_state',['label'=>'Ship-To State','required'=>true, 'id'=>'shipping_state', 'disabled'=>'disabled']);
	echo $this->Form->input('shipping_zipcode',['label'=>'Ship-To Zipcode','required'=>true, 'id'=>'shipping_zipcode', 'disabled'=>'disabled']);
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
	
	echo $this->Form->input('attention',['label'=>'(ShipTo)Attention','required'=>false, 'id'=>'attention']);

	echo "</div>";
	

	/** PPSASCRUM-7 End **/


	

	echo $this->Form->input('hasduedate',['type'=>'hidden','value'=>'1']);

	echo $this->Form->input('due',['type'=>'hidden']);
	
	echo "<h3>Ship-By Date (Due Date):</h3>
	<div id=\"datepicker\"></div><br>";
	


	echo "<div class=\"input selectbox required\"><label>Shipping Method</label>";
	echo $this->Form->select('shipping_method_id',$availableShippingMethods,['requried'=>true,'empty'=>'--Select a Shipping Method--']);
	echo "</div>";

	echo $this->Form->input('shipping_instructions',['type'=>'textarea','label'=>'Shipping Instructions']);


	echo $this->Form->input('order_total',['type'=>'number','step'=>'any','min'=>'0.00','label'=>'Expected Order Total','required'=>true]);

	echo "<div>";
	echo $this->Form->input('saveaction',['type'=>'hidden','value'=>'']);
	echo $this->Form->button('Save and Close',['type'=>'button','onclick'=>"dosaveaction('close')",'id'=>'saveclosebutton']);
	echo " &nbsp; ";
	echo $this->Form->button('Save and Continue',['type'=>'button','onclick'=>"dosaveaction('continue')",'id'=>'savecontinuebutton']);
	echo "</div>";


echo $this->Form->end();

?>
<style>
#saveclosebutton{  font-weight:bold; border:1px solid #000; }
#savecontinuebutton{ background:#CCC !important; color:#000; border:1px solid #000; font-weight:normal; }

input.alreadyusednumber{ border:3px solid red !important; background:#FFCFBF; color:red; }
input.availablenumber{ border:2px solid green !important; }

#polookupresult{ display:none; font-size:12px; padding:10px; background:#FFFF73; color:red; border:1px solid maroon; margin-bottom:20px; }
table.ui-datepicker-calendar td a.ui-state-active{ border: 1px solid #003eff !important;  background: #007fff !important; color:#FFF !important; }
</style>
<script>

function dosaveaction(action){
    
	$('#saveaction').val(action);

	if($('input#po-number').hasClass('alreadyusednumber')){
			alert('This PO Number is already in the system!');
			return false;
	}else{
		
		//validate the form
		if($('select[name=customer_id]').val() == ''){
			alert('You must select a Customer');
			return false;
		}

        if($('select[name=\'type_id\']').val() == '' || $('select[name=\'type_id\']').val() === undefined || $('select[name=\'type_id\']').val() == null){
           alert('You must select an Order Type.');
           return false;
       }

		if($('input#po-number').val() == ''){
			alert('You must enter a Customer PO Number');
			$('input#po-number').focus();
			return false;
		}
		
		if($('select[name=project_manager_id]').val() == ''){
            alert('You must select a Project Manager');
            return false;
        }

		if($('select[name=shipping_method_id]').val() == ''){
			alert('Please select a Shipping Method');
			return false;
		}

		if($('input#order-total').val() == ''){
			alert('Please enter an Expected Order Total');
			$('input#order-total').focus();
			return false;
		}
		
		$('form').submit();
	}
}


	
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

    $('form').submit(function(){
       if($('#due').val() == ''){
           alert('You must select a Ship-By Date.');
           return false;
       }
       if($('select[name=\'type_id\']').val() == '' || $('select[name=\'type_id\']').val() === undefined || $('select[name=\'type_id\']').val() == null){
           alert('You must select an Order Type.');
           return false;
       }
       
       
	   var orderDate=$('#orderdatepicker').datepicker('getDate');
	   var shipDate=$('#datepicker').datepicker('getDate');
	   
	   console.log('OrderDate='+orderDate+' |||  ShipDate='+shipDate);
	   //return false;
	   
	   if(orderDate > shipDate){
	       alert('ERROR: You cannot have a PO Date later than Ship Date.');
	       return false;
	   }
	   
    });
    
    
	$('#po-number').keyup($.debounce( 700, function(){
		$.fancybox.showLoading();
		$.get('/orders/checkponumber/'+encodeURIComponent($(this).val())+'/'+encodeURIComponent($('select[name=customer_id]').val()),function(data){
			if(data=='NO'){
				$('#po-number').removeClass('availablenumber').removeClass('alreadyusednumber').addClass('alreadyusednumber');
				$('#polookupresult').html('This PO Number already exists in the system for <b>'+$('select[name=customer_id] option:selected').text()+'</b>!').show('fast');
			}else{
				$('#po-number').removeClass('availablenumber').removeClass('alreadyusednumber').addClass('availablenumber');
				$('#polookupresult').html('').hide('fast');
			}
			$.fancybox.hideLoading();
		});
	}));
	
	
	$('select[name=customer_id]').change(function(){
		$.get('/quotes/getcustomercontacts/'+$(this).val(),function(data){
			$('#customerContactsBlock').html(data).show('fast');
		});
	});
	
});

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
    			   const myArray = data.split(" :|: ")
    			     $('#allfacility').val(myArray[3]);
			         $('#facilitySelected').val(myArray[0]);
			        $('#facilityCode').val(myArray[1]);
			        $('#facilityAttention').val(myArray[2]);
    			    
    			    $('input[type=radio][name=userAddressesSelection][value=\"default\"]').prop('checked', true);
    			    
    			    $.get('/orders/getFacilityAddressDetails/'+$('#allfacility').val(),function(data){
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
        }
        
    function doneTyping(type,value) {
		var query = value;
		alert(query);

        $.ajax({
            type: "GET",
            url: "/orders/getShipTodetails",
            data: { search: query , type: type},
            success: function(data) {
               console.log(data); // handle the search results here
			   const myArray = data.split(" :|: ")
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
		const myArray = data.split(" :|: ")
		$('#facilityCode').val(myArray[0]);
		$('#facilityAttention').val(myArray[1]);
		if($('input[type=radio][name=userAddressesSelection]').val() == 'default') {
			$.get('/orders/getFacilityAddressDetails/'+$('#facilitySelected').val(),function(data){
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
});

$('select[name=default_address]').change(function() {
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
    }
	else if (this.value == 'customer') {
	    //$('#facilitySelected').val('');
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
		$('#ship_to_name').val('');

			$.get('/orders/getCustomerAddressDetails/'+$("select[name=customer_id]").val(),function(data){
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
			     localStorage.setItem('ship_to_name','');

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
    /**PPSASCRUM-7 end **/
</script>