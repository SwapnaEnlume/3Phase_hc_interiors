<!-- src/Template/Products/editspecialprice.ctp -->
<?php
echo $this->Form->create(null);

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


echo $this->Form->input('newprice',['label'=>'Special Price','type'=>'number','step'=>'any','min'=>'0.01','required'=>true,'value'=>number_format(floatval($thisException['price']),2,'.','')]);


echo $this->Form->submit('Save New Price');


echo $this->Form->end();

?>
<style>
div.input.nofield label{ font-weight:bold; }
div.input.nofield div{ padding:5px 5px 5px 20px; }
div.input.number input{ width:300px; }
</style>