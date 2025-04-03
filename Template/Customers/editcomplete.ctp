<!-- src/Template/Customers/edit.ctp -->

<div class="editcustomer form">
<?= $this->Form->create(null) ?>
	<h1 class="pageheading">Edit Customer</h1>
    <fieldset>
        <legend>Company Detials</legend>
        <?php
		echo $this->Form->input('company_name',['required'=>true,'autocomplete'=>'off','value'=>$customer['company_name']]); 
		echo $this->Form->input('phone_number',['autocomplete'=>'off','value'=>$customer['phone']]);
		echo $this->Form->input('company_website',['autocomplete'=>'off','value'=>$customer['website']]);
		
		if($customer['surcharge_addon']==1.00){
			$surchargeAmount=0;
		}else{
			$surcharge=($customer['surcharge_addon']-1.00);
			$surchargeAmount=($surcharge*100);
		}
		
		echo $this->Form->input('surcharge_addon',['type'=>'number','value'=>$surchargeAmount,'required'=>true,'autocomplete'=>'off']);
		?>
	</fieldset>
	<fieldset>
		<legend>Billing Address</legend>
        <?php
		echo $this->Form->input('billing_address',['autocomplete'=>'off','value'=>$customer['billing_address']]);
        echo $this->Form->input('billing_city',['autocomplete'=>'off','value'=>$customer['billing_address_city']]);
        echo $this->Form->input('billing_state',['autocomplete'=>'off','value'=>$customer['billing_address_state']]);
        echo $this->Form->input('billing_zipcode',['autocomplete'=>'off','value'=>$customer['billing_address_zipcode']]);
		echo $this->Form->label('Billing Country');
		echo $this->Form->select('billing_country',['US'=>'United States','CA'=>'Canada','MX'=>'Mexico'],['autocomplete'=>'off','value'=>'US']);
		?>
   </fieldset>
   <fieldset>
   		<legend>Shipping Address</legend>
		<div style="text-align:right;font-size:12px;"><a href="javascript:loadBillingToShipping();">Same as Billing Address</a></div>
		<?php
		echo $this->Form->input('shipping_address',['autocomplete'=>'off','value'=>$customer['shipping_address']]);
        echo $this->Form->input('shipping_city',['autocomplete'=>'off','value'=>$customer['shipping_address_city']]);
        echo $this->Form->input('shipping_state',['autocomplete'=>'off','value'=>$customer['shipping_address_state']]);
        echo $this->Form->input('shipping_zipcode',['autocomplete'=>'off','value'=>$customer['shipping_address_zipcode']]);
		echo $this->Form->label('Shipping Country');
		echo $this->Form->select('shipping_country',['US'=>'United States','CA'=>'Canada','MX'=>'Mexico'],['autocomplete'=>'off','value'=>'US']);
		?>
	</fieldset>
	
<?= $this->Form->button('Save Changes'); ?>
<?= $this->Form->end() ?>
</div>
<script>
function loadBillingToShipping(){
	$('#shipping-address').val($('#billing-address').val());
	$('#shipping-city').val($('#billing-city').val());
	$('#shipping-state').val($('#billing-state').val());
	$('#shipping-zipcode').val($('#billing-zipcode').val());
}
</script>
<style>.customers.form form{ width:600px; margin:0 auto; }</style>
<script>
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