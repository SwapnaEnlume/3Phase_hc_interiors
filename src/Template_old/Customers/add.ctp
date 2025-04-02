<!-- src/Template/Customers/add.ctp -->

<div class="customers form">
<?= $this->Form->create($customer) ?>
		<?php if(!is_null($from)){ ?><input type="hidden" name="from" value="<?php echo $from; ?>" /><?php } ?>
	<h1 class="pageheading">Add Customer</h1>
    <fieldset>
        <legend>Company Detials</legend>
        <?php
		echo $this->Form->input('company_name',['required'=>true,'autocomplete'=>'off']); 
		echo $this->Form->input('phone_number',['autocomplete'=>'off']);
		echo $this->Form->input('fax_number',['autocomplete'=>'off']);
		echo $this->Form->input('company_website',['autocomplete'=>'off']);
		
		echo $this->Form->input('surcharge_percent',['label'=>'Surcharge Add-On (%)','type'=>'number','value'=>'0','min'=>0,'max'=>100,'step'=>'any','required'=>true,'autocomplete'=>'off']);
		echo $this->Form->input('surcharge_dollars',['label'=>'PMI Surcharge ($)','title'=>'Per Manufactured Item Surcharge','type'=>'number','value'=>'0','min'=>'0.00','step'=>'any','required'=>true,'autocomplete'=>'off']);
		?>
	</fieldset>
	<?php /**PPSASCRUM-3 start **/ ?>
	<fieldset>
        <legend>RISK MANAGEMENT</legend>
        <?php
		echo "<div class=\"input selectbox\">
		<label for=\"on_credit_hold\">ON CREDIT HOLD ?</label>";
		echo $this->Form->select('on_credit_hold',[
			'1'=>'Yes',
			'0' => 'No',
			],['id'=>'on_credit_hold','value'=>'0']);
		echo "</div>";
		echo $this->Form->input('deposit_threshold',['label'=>'Deposit Threshold','type'=>'number','value'=>'']); 
		echo $this->Form->input('deposit_perc',['label'=>'Deposit %','type'=>'number','value'=>'0']);
			echo "<div class=\"input \">";
		echo "<label for=\"\" style=\"padding-left: 200px;\"> Early Pay Discount % / <br/> Days for Early Pay Discount/ <br/>Payments Days </label>" .$this->Form->input('payment_terms',['label'=>'Terms','type'=>'text','value'=>'']) ; 
		echo "</div>";
	//	echo $this->Form->input('payment_terms',['label'=>'Terms Early Pay Discount % / Days for Early Pay Discount/ Payments Days','type'=>'text','value'=>'']); echo "<span>  Early Pay Discount % / <br/> Days for Early Pay Discount/ <br/>Payments Days </span>";
		?>
	</fieldset>
		<?php /**PPSASCRUM-3 end **/ ?>
	<fieldset>
		<legend>Billing Address</legend>
        <?php
		echo $this->Form->input('billing_address',['autocomplete'=>'off']);
        echo $this->Form->input('billing_city',['autocomplete'=>'off']);
        echo $this->Form->input('billing_state',['autocomplete'=>'off']);
        echo $this->Form->input('billing_zipcode',['autocomplete'=>'off']);
		echo $this->Form->label('Billing Country');
		echo $this->Form->select('billing_country',['US'=>'United States','CA'=>'Canada','MX'=>'Mexico'],['autocomplete'=>'off','value'=>'US']);
		?>
   </fieldset>
   <fieldset>
   		<legend>Shipping Address</legend>
		<div style="text-align:right;font-size:12px;"><a href="javascript:loadBillingToShipping();">Same as Billing Address</a></div>
		<?php
		echo $this->Form->input('shipping_address',['autocomplete'=>'off']);
        echo $this->Form->input('shipping_city',['autocomplete'=>'off']);
        echo $this->Form->input('shipping_state',['autocomplete'=>'off']);
        echo $this->Form->input('shipping_zipcode',['autocomplete'=>'off']);
		echo $this->Form->label('Shipping Country');
		echo $this->Form->select('shipping_country',['US'=>'United States','CA'=>'Canada','MX'=>'Mexico'],['autocomplete'=>'off','value'=>'US']);
		?>
	</fieldset>
	
	<fieldset><legend>Tiers</legend>
	<?php 
		echo $this->Form->input('tier_bs',['label'=>'Bedding Tier','type'=>'number','min'=>'1','max'=>'12','value'=>'7']);
		echo $this->Form->input('tier_cc',['label'=>'CCs Tier','type'=>'number','min'=>'1','max'=>'12','value'=>'7']); 
		echo $this->Form->input('tier_swt',['label'=>'SWTs Tier','type'=>'number','min'=>'1','max'=>'12','value'=>'7']);
		echo $this->Form->input('tier_hwt',['label'=>'HWTs Tier','type'=>'number','min'=>'1','max'=>'12','value'=>'7']); 
		echo $this->Form->input('tier_track',['label'=>'Hardware Tier','type'=>'number','min'=>'1','max'=>'12','value'=>'7']);
		echo $this->Form->input('tier_install',['label'=>'Services Tier','type'=>'number','min'=>'1','max'=>'12','value'=>'7']);
		echo $this->Form->input('tier_fabric',['label'=>'Miscellaneous Tier','type'=>'number','min'=>'1','max'=>'12','value'=>'7']); 
		?>
	</fieldset>
<?= $this->Form->button('Continue'); ?>
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