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
	
	
	echo $this->Form->input('facility',['label'=>'Facility','required'=>false]);
	echo $this->Form->input('shipping_address_1',['label'=>'Ship-To Address (Line 1)','required'=>true]);
	echo $this->Form->input('shipping_address_2',['label'=>'Ship-To Address (Line 2)','required'=>false]);
	echo $this->Form->input('shipping_city',['label'=>'Ship-To City','required'=>true]);
	echo $this->Form->input('shipping_state',['label'=>'Ship-To State','required'=>true]);
	echo $this->Form->input('shipping_zipcode',['label'=>'Ship-To Zipcode','required'=>true]);


	echo $this->Form->input('attention',['label'=>'Attention','required'=>false]);

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
</script>
<br><br><br><Br>
<style>
.submit input[type=submit]{ font-weight:bold; color:#FFF; background:#1F2965; font-size:14px; padding:10px 20px; border:1px solid #000; }
	
input.alreadyusednumber{ border:3px solid red !important; background:#FFCFBF; color:red; }
input.availablenumber{ border:2px solid green !important; }

#polookupresult{ display:none; font-size:12px; padding:10px; background:#FFFF73; color:red; border:1px solid maroon; margin-bottom:20px; }
</style>