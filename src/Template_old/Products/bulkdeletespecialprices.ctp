<!-- src/Template/Products/bulkdeletespecialprices.ctp -->
<?php
echo $this->Form->create(null);


echo $this->Form->input('doprocess',['type'=>'hidden','value'=>'yes']);
echo "<br><h2>Are you sure you want to delete thes special price rules?</h2><br>";

echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
<thead>
<tr>
<th>Rule ID</th>
<th>Customer</th>
<th>Product</th>
<th>Color(s)</th>
<th>Size</th>
<th>Price</th>
</tr>
</thead>
<tbody>";
foreach($theseExceptions as $rule){
	
	echo "<tr>
	<td>".$rule['id']."</td>
	<td>";
	
	foreach($allCustomers as $customer){
		if($customer['id'] == $rule['customer_id']){
			echo $customer['company_name'];
		}
	}
	echo "</td>
	<td>";
	echo $thisProduct[$rule['id']]['title'];
	echo "</td>
	<td>";
	
	$thiscolors=json_decode($rule['included_colors'],true);
	$colorout='';
	foreach($thiscolors as $color){
		$colorout .= $thisProduct[$rule['id']]['fabric_name']." ".$color.", ";
	}
	echo substr($colorout,0,(strlen($colorout)-2));
	
	echo "</td>
	<td>";
	foreach($allSizes as $size){
		if($size['id'] == $rule['size_id']){
			echo $size['title'];
		}
	}
	echo "</td>
	<td>\$".number_format(floatval($rule['price']),2,'.',',')."</td>
	</tr>";
	
}
echo "</tbody>
</table>";

echo "<br><br>";

echo $this->Form->submit('Yes, Delete Now');

echo "<button type=\"button\" onclick=\"location.replace('/products/specialpricing/');\">No, Go back</button>";


echo $this->Form->end();

?>
<style>
h2{ text-align: center; }
form{ margin:0 auto; }
form table thead tr{ background:#000; }
form table thead tr th{ color:#FFF; }

form table tbody tr:nth-of-type(even){ background:#f8f8f8; }
	
div.input.nofield label{ font-weight:bold; }
div.input.nofield div{ padding:5px 5px 5px 20px; }
div.input.number input{ width:300px; }
div.submit{ float:left; width:40%; }
input[type=submit]{ font-weight:bold; color:#FFF; background:#177310; border:0; padding:15px; font-size:large; }
button[type=button]{ float:right; }
</style>