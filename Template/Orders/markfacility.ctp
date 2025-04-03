<h3>Create New Recipient:</h3>
<?php
echo $this->Form->create(null);
echo $this->Form->input('facility_name',['label'=>'Recipient Name','required'=>true, 'id'=> 'facility_name']);

echo $this->Form->input('facility_code',['label'=>'Recipient Chain','required'=>true, 'id'=> 'facility_code', 'default'=>'N/A']);
echo $this->Form->input('attention',['label'=>'(Recipient)Attention','required'=>false, 'id'=> 'attention']);
echo "<legend>Default Address </legend>";
//echo  $this->request->referer();

//echo "<a href=\"#\" id=\"new_address\"  style=\"display: block; height: 36px; color: white; width: 21%; text-align: center; padding: 6px 0px 0px; background: rgb(71, 1, 1) !important; float: right !important;\" id='new_address'>+ Add New Ship-To Address </a>";
//echo $this->Form->select('default_address',$shipTooptions,['required'=>false,'empty'=>'--Select address--','id'=>'default_address','class'=>'input selectbox required','style'=>'width: 63%; padding: 1% 0 0 0%;']);
echo "<a href=\"#\" id=\"new_address\"  style=\"display: block; height: 36px; color: white; width: 20%; text-align: center; padding: 6px 0px 0px; background: rgb(71, 1, 1) !important; !important; float: right !important;\" id='new_address'>+ New Address </a>";

echo $this->Form->input('addressSelected',['type'=>'hidden','id'=>'addressSelected','required'=>false]);
	echo '<input type="text" name="default_address" id="default_address" />';
				
			//	echo "</fieldset>";
				
				echo "<script>
                    $(function(){
                        $('input[name=default_address]').autocomplete({
                           source: function( request, response){
                               $.ajax({
                                  //'url': '/orders/searchAddressfield/'+request.term,
                                   'url': '/orders/searchAddressfield/'+escapeTextFieldforURL(request.term),
                                  'dataType':'json',
                                  'dataType':'json',
                                  success: function(data){
                                      response(data);
                                  }
                               });
                           },
                           minLength: 2,
                           select: function(event,ui){
                               //$.fancybox.showLoading();
                              // var spiltvalue = ui.item.value.split(',');
                              // $.get('/orders/getShipAddressNameDetails/'+spiltvalue[0],function(data){
                               var spiltvalue = ui.item.value.split(' :,: ');
                               $.get('/orders/getShipAddressNameDetails/'+escapeTextFieldforURL(spiltvalue[0]),function(data){
                               $('#addressFeilds').show();
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
                            		});
    			                    
    			                                        			//	$.fancybox.hideLoading();

                    			
                        }
                    });
                    });
                    </script>";
echo "<br><div id=\"addressFeilds\">";
echo $this->Form->input('ship_to_name',['label'=>'Address Name', 'id'=>'ship_to_name', 'disabled'=>'disabled', 'required'=>false]);

echo $this->Form->input('shipping_address_1',['label'=>'Address (Line 1)', 'id'=>'shipping_address_1', 'disabled'=>'disabled', 'required'=>false]);

echo $this->Form->input('shipping_address_2',['label'=>'Address (Line 2)', 'id'=>'shipping_address_2', 'disabled'=>'disabled', 'required'=>false]);
echo $this->Form->input('shipping_city',['label'=>'City','required'=>true, 'id'=>'shipping_city', 'disabled'=>'disabled', 'required'=>false]);
echo $this->Form->input('shipping_state',['label'=>'State','required'=>true, 'id'=>'shipping_state', 'disabled'=>'disabled', 'required'=>false]);
echo $this->Form->input('shipping_zipcode',['label'=>'Zipcode','required'=>true, 'id'=>'shipping_zipcode', 'disabled'=>'disabled', 'required'=>false]);
$country_list = array(
    "US" => "United States",
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
echo "<div class=\"input selectbox required\"><label style='display: block;font-weight: bold;'>Country</label>";
echo $this->Form->select('shipping_country',$country_list,['required'=>false,'empty'=>'--Select Country--','id'=>'shipping_country','class'=>'select', 'style'=>' width: 98%;padding-block: 1%;', 'disabled'=>'disabled','default' => 'US']);
echo "</br></div>";
echo "</div>";

echo "<br><BR><div id=\"buttonsbottom\">";

echo $this->Form->button('Add This Recipient',['type'=>'submit']);
//echo "<button type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";
echo "<button type=\"button\" id= \"cancleButton \" onclick=\"functionToExecute()\">Cancel</button>";

echo "</div><br><br>";
echo $this->Form->end();

?><script>

function functionToExecute(){
        parent.$.fancybox.close();
        localStorage.setItem('facilityDetails', '');
    }
    
 $('form').submit(function(){
       if($('#default_address').val() == ''){
           alert('Recipient cannot be added without having a Default Address assigned.');
           return false;
       }
      
	   
    });
    
$('#addressFeilds').hide();

load(localStorage.getItem("options"), localStorage.getItem("shipping_address_1"), localStorage.getItem("shipping_address_2"), localStorage.getItem("shipping_city"), localStorage.getItem("shipping_state"), localStorage.getItem("shipping_country"), localStorage.getItem("shipping_zipcode"), localStorage.getItem("id"));

$("#new_address").on('click', function() {
     //var prevVal =  $('#attention').val() +':' +$('#facility_code').val()+":"+$('#facility_name').val();
          var prevVal =  $('#attention').val() +' :|: ' +$('#facility_code').val()+' :|: ' +escapeTextFieldforURL($('#facility_name').val());

        parent.$.fancybox.close();
        parent.$.fancybox({
			'href':'/orders/markFacilityshipto?params='+prevVal,
			'type':'iframe',
			'iframe': {
                 'scrolling': 'no', // Disable scrolling in the iframe
                 'css': {
                        'height':  450,
                         'overflow': 'hidden' 
                         }
            },
			'autoSize':false,
			'width':680,
			'height':750,
			'modal':true,
			'helpers': {
				overlay: {
					locked: true
				}
			}
		});
    });
$('select[name=default_address]').change(function() {
    if($('#default_address').val() != "") {
	$.get('/orders/getShipAddressDetails/'+$('#default_address').val(),function(data){
				const myArray = data.split(" :|: ")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
					$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#ship_to_name').val(myArray[7]);
				$('#addressFeilds').show();

		});
    } else {
        $('#addressFeilds').hide();
       
    }
    
});

  
function load(options,ship1,ship2,city,state,country,zipcode,id){
              localStorage.setItem("params", "");

    var item = localStorage.getItem("lastHref");
    //alert(item);
//if (item.length > 0) {
if (!(item === null) && !(item === "")) {
  //  if(localStorage.getItem("lastHref")===null && localStorage.getItem("lastHref").length >0){
               $('#default_address').show();
			   $('#default_address').html(' ');
			  // var opt = options.slice(0,-1);;

 var $el = $('#default_address');
$el.html(' ');

/*var obj1 = localStorage.getItem("options");
            if(obj1.length > 0){
                var properties = obj1.split(',');
                var obj = {};
                properties.forEach(function(property) {
                    var tup = property.split(':');
                    obj[tup[0]] = tup[1];
                });
                $el.html(' ');
                $.each(obj, function(key, value) {
                    $el.append($("<option></option>")
                    .attr("value", value).text(key));
                });
             }*/
                $('#shipping_address_1').val(ship1);
				$('#shipping_address_2').val(ship2);
				$('#shipping_city').val(city);
				$('#shipping_state').val(state);
				$('#shipping_country').val(country);
				$('#shipping_zipcode').val(zipcode);
				$('#default_address').val(localStorage.getItem("ship_to_name"));
				$('#addressSelected').val(localStorage.getItem("default_address"));
				$('#ship_to_name').val(localStorage.getItem("ship_to_name"));
                $('#addressFeilds').show();
                 if((localStorage.getItem("facilityDetails")) != ""){
                     console.log("facilityDetails--",localStorage.getItem("facilityDetails"));
			        	const myArray = localStorage.getItem("facilityDetails").split(" :|: ")
			        	
			        	 previouslink = myArray[2];
			        	//	$('#facility_name').val(myArray[2]);
			        	if(myArray[2] != ":|:")
			            	$('#facility_name').val(reverseTextFeildforURL(myArray[2]));

                        if(myArray[1] != ":|:")
			        	    $('#facility_code').val(myArray[1]);
			        	//$('#facility_code').val(myArray[1]);
			        	$('#attention').val(myArray[0]);
			        		parent.$('input[type=radio][name=new_or_existing][value=\"default\"]').prop('checked', true);
			    }
    }else {
        $('#addressFeilds').hide();
        //alert('in');
        // alert('inside');
        ;var item1 = localStorage.getItem("facilityDetails");
        if (!(item1 === null) && !(item1 === "")) {
			        	const myArray = localStorage.getItem("facilityDetails").split(" :|: ")
			        	console.log(myArray[0] +"attens");
			        	console.log(myArray[1] + "code");
			        	console.log(myArray[2]+ "name");
			        	
			        	 previouslink = myArray[3];

			        	 if(myArray[2] != "undefined")
			        	 	$('#facility_name').val(reverseTextFeildforURL(myArray[2]));

			        	//	$('#facility_name').val(myArray[2]);
			        	if(myArray[1] != "N/A:|:")
			        	    $('#facility_code').val(myArray[1]);
			        	
			        		$('#attention').val(myArray[0]);
			        		parent.$('input[type=radio][name=new_or_existing][value=\"default\"]').prop('checked', true);
			    }
        
    }
        
    }
    
      function escapeTextFieldforURL(thetext){
           if(thetext == "" || thetext == undefined  || thetext.length == 0){
        
    }else{
    var output =thetext.replace(/\\/g, "__bbbbslash__");
	 output = output.replace(/\//g, "__aaabslash__");
	output = output.replace('?','__question__',output);
	output = output.replace(' ','__space__',output);
	output = output.replace('#','__pound__',output);
	output = output.replace('%','__percentage__',output);
	output = output.replace('&','__ampersand__',output);
	return output;
        
    }
}

function reverseTextFeildforURL(thetext){
    if(thetext == "" || thetext == undefined  || thetext.length == 0){
        
    }else{
        var output =thetext.replace('','');
        output = output.replace('__percentage__','%');
    	 output = output.replace( "__aaabslash__",'/');
    	output = output.replace('__question__','?',output);
    	output = output.replace('__space__',' ',output);
    	output = output.replace('__pound__','#',output);
    	output = output.replace("__ampersand__",'&',output);
    	output = output.replace('__bbbbslash__',"\\",output);
    
    	return output;
    }
   /* var output = thetext.replace('__percentage__','%');
	 output = output.replace( "__aaabslash__",'/');
	output = output.replace('__question__','?',output);
	output = output.replace('__space__',' ',output);
	output = output.replace('__pound__','#',output);
	output = output.replace("__ampersand__",'&',output);
	output = output.replace('__bbbbslash__',"\\",output);

	return output;*/
}
    
</script>
<style>
body{ font-family:Arial,Helvetica,sans-serif; }
form div.input{ margin-bottom:10px; }
form div.input label{ display:block; font-weight:bold; }
form div.input input[type=text]{ width:95%; padding:6px; }
form div.input textarea{ width:95%; padding:6px; }

#buttonsbottom div.submit{ float:left; }
#buttonsbottom button[type=button]{ float:right; }

tr.Mfgnote{ color:orange; }
tr.Shipnote{ color:purple; }

table thead tr{ background:#26337A; }
table thead tr th{ color:#FFF; }	
table{ border-bottom:2px solid #26337A; }
	
form div.input.date select{ padding-right:30px !important; }
	
table tbody tr:nth-of-type(even){ background:#f8f8f8; }
table tbody tr:nth-of-type(odd){ background:#e8e8e8; }
	
form h2{ font-size:large; font-weight:bold; color:#26337A; }
form{ max-width:600px; margin:15px auto; }
form dl dd{ padding-left:20px; }
form input[type=submit]{ background:#26337A; color:#FFF; font-weight:bold; padding:15px 25px; font-size:large; border:0; }
.required label:after {
    color: #d00;
    content: " *"
}
</style>