<!-- src/Template/Quotes/add.ctp -->

<div class="newquote form">
<?php 
	
echo $this->Form->create(); 
	
echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']); ?>
    <fieldset>
        
		<?php
		if(!isset($customerID) || is_null($customerID)){
		?>
		<legend>New Quote</legend>
		<button id="existingcustomer" type="button">Existing Customer</button>
		<button id="newcustomer" type="button">New Customer</button>
		<div id="newcustomerform" style="display:none;">
			<?php echo $this->Form->input('customer_company'); ?>
		</div>
		<div id="existingcustomerform" style="display:none;">
			<fieldset>
			<div class="input text required">
    			<?php echo $this->Form->label('Select existing customer to create a Quote'); ?>
    			<?php echo $this->Form->select('customer_id',$customers,['empty'=>'--Select A Company--']); ?>
    			<div id="customerContactsBlock" style="display:none;">
    				<?php echo $this->Form->label('Select customer contact to assign this Quote to'); ?>
    				<?php echo $this->Form->select('contact_id',[],['empty'=>'--Contacts--']); ?>
    			</div>
			</div>
			
			
			<?php
            $typeOptions=array();
            foreach($allTypes as $type){
                $typeOptions[$type['id']]=$type['type_label'];
            }
            
            echo "<div class=\"input select required\"><label>Type:</label>";
            echo $this->Form->select('type_id',$typeOptions,['empty'=>'--Select Type--','required'=>true]);
            echo "</div>";
			?>
			
			</fieldset>
			<?php echo $this->Form->submit('Continue'); ?>
		</div>
		 </fieldset>
<?php echo $this->Form->end(); ?>
</div>
<script>
$(function(){
	$('#newcustomer').click(function(){
		window.location.href='/customers/add/from_add_request';
	});
	$('#existingcustomer').click(function(){
		$('#newcustomerform').hide();
		$('#existingcustomerform').show();
	});
	
	$('select[name=customer_id]').change(function(){
		$.get('/quotes/getcustomercontacts/'+$(this).val(),function(data){
			$('#customerContactsBlock').html(data).show('fast');
		});
	});
	
	$('.newquote.form form').submit(function(){
	   if($('select[name=customer_id]').val() == ''){
	       alert('ERROR: You must select a Customer before continuing');
	       return false;
	   } 
	});
	
});
</script>
<?php }else{ ?>
<legend>New Request for Customer: <em><?php echo $customerData->company_name; ?></em></legend>
<?php } ?>