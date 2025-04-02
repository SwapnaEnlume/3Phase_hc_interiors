<!-- src/Template/Products/newspecialpricing.ctp -->
<style>
div.sizeEntry{ width:300px; float:left; padding:10px; }
div.sizeEntry input{ display:inline-block; width:150px; }
	
fieldset legend em{ color:blue; }
h3 u{ color:blue; }
</style>
<h2>Add New Customer Special Product Pricing:</h2>
<hr />
<?php

echo $this->Form->create(null,['type'=>'file']);
//select fabric(s) and size(s) for this special pricing
echo $this->Form->input('process',['type'=>'hidden','value'=>'step4']);

foreach($postdata as $key => $val){
	if($key != 'process'){
		echo $this->Form->input($key,['type'=>'hidden','value'=>$val]);
	}
}

echo "<h3>Customer: <u>".$thisCustomer['company_name']."</u></h3>";

if(count($priceListResults) == 0){
	echo "<h4 style=\"color:red;\">No products found matching your inputs.</h4>";
}else{
	foreach($priceListResults as $productid => $WTproduct){
		echo "<fieldset>";

		echo "<legend>".$WTproduct['title'].", <em>Colors: <u>";
		$colorOut='';
		$thisWTcolors=json_decode($WTproduct['available_colors'],true);
		//print_r($thisWTcolors);
		//print_r($postdata);

		foreach($thisWTcolors as $color){
			if($postdata["use_".str_replace(" ","_",$postdata['fabric_name'])."_color_".str_replace(" ","_",$color)] == "yes"){
				$colorOut .= $color.", ";
			}
		}
		echo substr($colorOut,0,(strlen($colorOut)-2));
		echo "</u></em></legend>";

		foreach($WTproduct['sizes'] as $num => $size){

			foreach($allWTSizes as $sizeRow){
				if($sizeRow['id'] == $size['size_id']){
					echo "<div class=\"sizeEntry\"><label>".$sizeRow['title']." Price:</label> $<input type=\"number\" step=\"any\" name=\"wt_".$productid."_size_".$sizeRow['id']."_price\" placeholder=\"".$size['price']."\" /></label></div>";
				}
			}
		}
		echo "<div style=\"clear:both;\"></div>
		</fieldset>";
	}

	echo $this->Form->submit('Save Special Pricing');
}

echo "<br><Br><Br><Br><br>";

echo $this->Form->end();
?>