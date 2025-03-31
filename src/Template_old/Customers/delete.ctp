<!-- src/Template/Customers/delete.ctp -->

<div class="deletecustomer form">
<?php
echo $this->Form->create(null);
echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);
?>
<h3>Are you sure you want to delete this customer?</h3>
<ul>
<li>This will remove all orders and quotes from the system for this customer</li>
<li>This will remove this customer from <img src="/img/zohoicon.png" /></li>
</ul>

<table width="350" border="1" bordercolor="#000000" cellpadding="5" cellspacing="0">
<tr>
<th>Company Name:</th>
<td><?php echo $customer['company_name']; ?></td>
</tr>

<tr>
<th>Billing Address:</th>
<td><?php echo $customer['billing_address']."<br>".$customer['billing_address_city'].", ".$customer['billing_address_state']." ".$customer['billing_address_zipcode']; ?></td>
</tr>

<tr>
<th>Shipping Address:</th>
<td><?php echo $customer['shipping_address']."<br>".$customer['shipping_address_city'].", ".$customer['shipping_address_state']." ".$customer['shipping_address_zipcode']; ?></td>
</tr>

<tr>
<th>Phone #:</th>
<td><?php echo $customer['phone']; ?>&nbsp;</td>
</tr>

<tr>
<th>Fax #:</th>
<td><?php echo $customer['fax']; ?>&nbsp;</td>
</tr>

<tr>
<th>Website:</th>
<td><?php echo $customer['website']; ?>&nbsp;</td>
</tr>


<tr>
<th>ZOHO Account ID:</th>
<td><?php echo $customer['zoho_account_id']; ?></td>
</tr>

</table>

<br><Br><br>
<?php
echo $this->Form->button('Yes, Delete Now',['type'=>'submit']); 
echo $this->Form->button('No, Go Back',['type'=>'button','class'=>'cancelbutton']);
echo $this->Form->end();
?>
</div>

<style>
.deletecustomer form{ width:650px; margin:0 auto; text-align:center; }
.deletecustomer form h3{ font-size:x-large; margin:10px 0; font-weight:bold; color:red; }
.deletecustomer form ul{ font-size:medium; font-weight:bold; text-align:left; list-style:none; background:#FAF8D8; border:2px solid #8C6900; padding:5px 10px; }
.deletecustomer form ul li{ background:url('/img/alert.png') center left no-repeat; padding-left:20px; line-height:38px; }
.deletecustomer form button[type=submit]{ float:none !important; background:green; margin-right:15px;  }

.deletecustomer table tr:nth-of-type(even) th{ width:35%; background:#333; color:#FFF; border-bottom:1px solid #000; }
.deletecustomer table tr:nth-of-type(odd) th{ width:35%; background:#555; color:#FFF; border-bottom:1px solid #000; }

.deletecustomer table tr:nth-of-type(even) td{ width:65%; background:#e8e8e8; border-bottom:1px solid #000; }
.deletecustomer table tr:nth-of-type(odd) td{ width:65%; background:#F7F7F7; border-bottom:1px solid #000; }

.cancelbutton{ background:#333 !important; }
</style>
<script>
$(function(){
	$('.cancelbutton').click(function(){
		history.go(-1);
	});
});
</script>