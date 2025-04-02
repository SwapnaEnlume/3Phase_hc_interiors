<style>
table.ui-datepicker-calendar td a.ui-state-active{ border: 1px solid #003eff !important;  background: #007fff !important; color:#FFF !important; }
</style>

<h1 class="pageheading">Edit Order <u><?php echo $orderData['order_number']; ?></u> Details</h1>

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


echo $this->Form->input('facility',['value'=>$orderData['facility']]);

echo $this->Form->input('attention',['value'=>$orderData['attention']]);

echo $this->Form->input('shipping_address_1',['label'=>'Ship-To Address - Line 1','value'=>$orderData['shipping_address_1']]);
echo $this->Form->input('shipping_address_2',['label'=>'Ship-To Address - Line 2','value'=>$orderData['shipping_address_2']]);

echo $this->Form->input('shipping_city',['label'=>'Ship-To City','value'=>$orderData['shipping_city']]);

echo $this->Form->input('shipping_state',['label'=>'Ship-To State','value'=>$orderData['shipping_state']]);

echo $this->Form->input('shipping_zipcode',['label'=>'Ship-To Zipcode','value'=>$orderData['shipping_zipcode']]);

   
	

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
	
});
</script>