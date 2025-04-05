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

echo "<div class=\"input selectbox required\"><label>Project Manager</label>";
echo $this->Form->select('project_manager_id',$managers,['required'=>true,'empty'=>'--Select--','value'=>$orderData['project_manager_id']]);
echo "</div>";
	
	
	/** PPSACRUM-7 Start **/
	/*	echo $this->Form->input('facility',['label'=>'Facility','required'=>false]);
	echo $this->Form->input('shipping_address_1',['label'=>'Ship-To Address (Line 1)','required'=>true]);
	echo $this->Form->input('shipping_address_2',['label'=>'Ship-To Address (Line 2)','required'=>false]);
	echo $this->Form->input('shipping_city',['label'=>'Ship-To City','required'=>true]);
	echo $this->Form->input('shipping_state',['label'=>'Ship-To State','required'=>true]);
	echo $this->Form->input('shipping_zipcode',['label'=>'Ship-To Zipcode','required'=>true]);
*/
echo "<div class=\"input selectbox required\"><label>Facility</label>";
echo "<a href=\"#\" id=\"new_facility\"  style=\"display: block; height: 36px; color: white; width: 21%; text-align: center; padding: 6px 0px 0px; background: rgb(71, 1, 1) !important; float: right !important;\" id='new_facility'>+ New Facility </a>";

echo $this->Form->select('allfacility',$allFacility,['required'=>true,'empty'=>'--Select a Facility--','id'=>'allfacility']);
echo "</div>";
echo $this->Form->input('facilityCode',['label'=>'Facility Code','required'=>false,'id'=>'facilityCode']);

echo $this->Form->input('facilityAttention',['label'=>'(Facility)Attention','required'=>false,'id'=>'facilityAttention']);


echo $this->Form->radio('userAddressesSelection', ['default'=>'User Default Facility Address','customer'=>'User Customer Address', 'new'=>'Add a New Address','exisiting'=>'Pick an Existing Address']);
echo "<a href=\"#\" id=\"new_address\"  style=\"display: block; height: 36px; color: white; width: 21%; text-align: right; padding: 6px 0px 0px; background: rgb(71, 1, 1) !important; float: right !important;\" id='new_address'>+ New Address </a>";
echo "<div class=\"exisitingAddress\"> ";
echo $this->Form->select('default_address',$shipTooptions,['required'=>false,'empty'=>'--Select address--','id'=>'default_address']);
echo "</div>";

echo "<div class=\"defaultAddress\">";
echo $this->Form->input('userSelection',['type'=>'hidden','value'=>'','id'=>'userSelection']);

echo $this->Form->input('shipping_address_1',['label'=>'Ship-To Address (Line 1)','required'=>true, 'id'=>'shipping_address_1']);
echo $this->Form->input('shipping_address_2',['label'=>'Ship-To Address (Line 2)','required'=>false, 'id'=>'shipping_address_2']);
echo $this->Form->input('shipping_city',['label'=>'Ship-To City','required'=>true, 'id'=>'shipping_city']);
echo $this->Form->input('shipping_state',['label'=>'Ship-To State','required'=>true, 'id'=>'shipping_state']);
echo $this->Form->input('shipping_zipcode',['label'=>'Ship-To Zipcode','required'=>true, 'id'=>'shipping_zipcode']);
echo $this->Form->input('attentionship',['label'=>'(ShipTo)Attention','required'=>false, 'id'=>'attentionship']);

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
				$('#polookupresult').html('This PO Number already exists in the system for <b><?php echo $customerData['company_name']; ?></b>!').show('fast');
			}else{
				$('#po-number').removeClass('availablenumber').removeClass('alreadyusednumber').addClass('availablenumber');
				$('#polookupresult').html('').hide('fast');
			}
			$.fancybox.hideLoading();
		});
	}));
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
	

    function doneTyping(type,value) {
		var query = value;
		alert(query);

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
				$('#shipping_zipcode').val(myArray[4]);
				$('#attentionship').val(myArray[5]);
				$('#default_address').val(myArray[6]);
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
				$('#shipping_zipcode').val(myArray[4]);
				$('#attentionship').val(myArray[5]);
		});
		}
		});
});

$('select[name=default_address]').change(function() {
    if($('input[type=radio][name=userAddressesSelection]').val() == 'default') {
	$.get('/orders/getShipAddressDetails/'+$('#default_address').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_zipcode').val(myArray[4]);
				$('#attentionship').val(myArray[5]);
		});
    }
});



$('input[type=radio][name=userAddressesSelection]').change(function() {
	$('#userSelection').val(this.value);

    if (this.value == 'default') {
		$('#default_address').hide();
        $('#new_address').hide();
		$('#default_address').val('');
		if($('select[name=allfacility]').val() == ''){
            alert('You must select a Facility');
            return false;
        } else {
			$.get('/orders/getFacilityAddressDetails/'+$('#allfacility').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_zipcode').val(myArray[4]);
				$('#attentionship').val(myArray[5]);
		});
		}
       
    }
    else if (this.value == 'exisiting') {
        $('#default_address').show();
        $('#new_address').hide();
		if($('#default_address').val() != '') {
			$.get('/orders/getShipAddressDetails/'+$('#default_address').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_zipcode').val(myArray[4]);
				$('#attentionship').val(myArray[5]);
		});
		}
    }

	else if (this.value == 'new') {
        $('#default_address').hide();
        $('#new_address').show();
		$('#default_address').val('');
    }
	else if (this.value == 'customer') {
        $('#default_address').hide();
        $('#new_address').hide();
			$.get('/orders/getCustomerAddressDetails/<?php echo $customerData['id']?>',function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_zipcode').val(myArray[4]);
				$('#attentionship').val(myArray[5]);
		
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
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
    });
});
</script>
<br><br><br><Br>
<style>
.submit input[type=submit]{ font-weight:bold; color:#FFF; background:#1F2965; font-size:14px; padding:10px 20px; border:1px solid #000; }
	
input.alreadyusednumber{ border:3px solid red !important; background:#FFCFBF; color:red; }
input.availablenumber{ border:2px solid green !important; }

#polookupresult{ display:none; font-size:12px; padding:10px; background:#FFFF73; color:red; border:1px solid maroon; margin-bottom:20px; }
</style>