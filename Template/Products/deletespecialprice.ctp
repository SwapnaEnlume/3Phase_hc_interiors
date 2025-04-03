<!-- src/Template/Products/deletespecialprice.ctp -->
<?php
echo $this->Form->create(null);


echo $this->Form->input('doprocess',['type'=>'hidden','value'=>'yes']);
echo "<h2>Are you sure you want to delete this special price rule?</h2>";



echo "<div class=\"input nofield\">
	<label>Customer</label>
	<div>".$thisCustomer['company_name']."</div>
</div>";

echo "<div class=\"input nofield\">
	<label>Product</label>
	<div>".$thisProduct['title']."</div>
</div>";

foreach($allSizes as $size){
	if($size['id'] == $thisException['size_id']){
		echo "<div class=\"input nofield\">
			<label>Product Size</label>
			<div>".$size['title']."</div>
		</div>";
	}
}


echo "<div class=\"input nofield\">
	<label>Fabric Color(s)</label>
	<div>";
	$thiscolors=json_decode($thisException['included_colors'],true);
	$colorout='';
	foreach($thiscolors as $color){
		$colorout .= $thisProduct['fabric_name']." ".$color.", ";
	}
	echo substr($colorout,0,(strlen($colorout)-2));
	echo "</div>
</div>";

echo "<div class=\"input nofield\">
	<label>Special Price:</label>
	<div>\$".number_format(floatval($thisException['price']),2,'.',',')."</div>
</div>";

echo "<br><br>";

echo $this->Form->submit('Yes, Delete Now');

echo "<button type=\"button\" onclick=\"location.replace('/products/specialpricing/');\">No, Go back</button>";


echo $this->Form->end();

?>
<style>
h2{ text-align: center; }
form{ width:550px; margin:0 auto; }
div.input.nofield label{ font-weight:bold; }
div.input.nofield div{ padding:5px 5px 5px 20px; }
div.input.number input{ width:300px; }
div.submit{ float:left; width:40%; }
input[type=submit]{ font-weight:bold; color:#FFF; background:#177310; border:0; padding:15px; font-size:large; }
button[type=button]{ float:right; }
</style>