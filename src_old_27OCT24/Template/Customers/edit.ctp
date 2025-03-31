<!-- src/Template/Customers/edit.ctp -->

<div class="editcustomer form">
<?= $this->Form->create(null) ?>
	<h1 class="pageheading">Edit Customer</h1>
    <fieldset>
        <legend>Company Detials</legend>
        <?php
		echo $this->Form->input('company_name',['required'=>true,'autocomplete'=>'off','value'=>$customer['company_name']]); 
		echo $this->Form->input('phone_number',['autocomplete'=>'off','value'=>$customer['phone']]);
		echo $this->Form->input('fax_number',['autocomplete'=>'off','value'=>$customer['fax']]);
		echo $this->Form->input('company_website',['autocomplete'=>'off','value'=>$customer['website']]);
		
		echo $this->Form->input('surcharge_percent',['label'=>'Surcharge Add-On (%)','type'=>'number','value'=>$customer['surcharge_percent'],'min'=>0,'max'=>100,'step'=>'any','required'=>true,'autocomplete'=>'off']);
		echo $this->Form->input('surcharge_dollars',['label'=>'PMI Surcharge ($)','title'=>'Per Manufactured Item Surcharge','type'=>'number','value'=>$customer['surcharge_dollars'],'min'=>'0.00','step'=>'any','required'=>true,'autocomplete'=>'off']);
		?>
	</fieldset>
	<?php /**PPSASCRUM-3 start **/ ?>
	<fieldset>
        <legend>RISK MANAGEMENT</legend>
        <?php
		echo "<div class=\"input selectbox\">
		<label for=\"on_credit_hold\">ON CREDIT HOLD</label>";
		echo $this->Form->select('on_credit_hold',[
		    '0' => 'No',
			'1'=>'Yes',
			],['id'=>'on_credit_hold','value'=>$customer['on_credit_hold']]);
		echo "</div>";
		echo $this->Form->input('deposit_threshold',['label'=>'Deposit Threshold','type'=>'number','value'=>$customer['deposit_threshold']]); 
		echo $this->Form->input('deposit_perc',['label'=>'Deposit %','type'=>'number','value'=>$customer['deposit_perc']]);
		echo "<div class=\"input \">";
		echo "<label for=\"\" style=\"padding-left: 200px;\"> Early Pay Discount % / <br/> Days for Early Pay Discount/ <br/>Payments Days </label>" .$this->Form->input('payment_terms',['label'=>'Terms','type'=>'text','value'=>$customer['payment_terms']]) ; 
		echo "</div>";
		?>
	</fieldset>
    <?php /**PPSASCRUM-3 end **/ ?>
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
		/**PPSASCRUM-7 Start **/
		echo $this->Form->input('shipping_name',['autocomplete'=>'off','value'=>$customer['shipping_name']]);
		/**PPSASCRUM-7 end **/

		echo $this->Form->input('shipping_address',['autocomplete'=>'off','value'=>$customer['shipping_address']]);
        echo $this->Form->input('shipping_city',['autocomplete'=>'off','value'=>$customer['shipping_address_city']]);
        echo $this->Form->input('shipping_state',['autocomplete'=>'off','value'=>$customer['shipping_address_state']]);
        echo $this->Form->input('shipping_zipcode',['autocomplete'=>'off','value'=>$customer['shipping_address_zipcode']]);
		echo $this->Form->label('Shipping Country');
		echo $this->Form->select('shipping_country',['US'=>'United States','CA'=>'Canada','MX'=>'Mexico'],['autocomplete'=>'off','value'=>'US']);
		?>
	</fieldset>
	
	
	<fieldset><legend>Tiers</legend>
	<?php 
		echo $this->Form->input('tier_bs',['label'=>'Bedding Tier','type'=>'number','min'=>'1','max'=>'12','value'=>$customer['tier_bs']]);
		echo $this->Form->input('tier_cc',['label'=>'CCs Tier','type'=>'number','min'=>'1','max'=>'12','value'=>$customer['tier_cc']]); 
		echo $this->Form->input('tier_swt',['label'=>'SWTs Tier','type'=>'number','min'=>'1','max'=>'12','value'=>$customer['tier_swt']]);
		echo $this->Form->input('tier_hwt',['label'=>'HWTs Tier','type'=>'number','min'=>'1','max'=>'12','value'=>$customer['tier_hwt']]); 
		echo $this->Form->input('tier_track',['label'=>'Hardware Tier','type'=>'number','min'=>'1','max'=>'12','value'=>$customer['tier_track']]);
		echo $this->Form->input('tier_install',['label'=>'Services Tier','type'=>'number','min'=>'1','max'=>'12','value'=>$customer['tier_install']]);
		echo $this->Form->input('tier_fabric',['label'=>'Miscellaneous Tier','type'=>'number','min'=>'1','max'=>'12','value'=>$customer['tier_fabric']]); 
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