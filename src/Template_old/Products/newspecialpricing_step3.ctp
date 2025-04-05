<!-- src/Template/Products/newspecialpricing.ctp -->
<h2>Add New Customer Special Product Pricing:</h2>
<hr />
<?php
echo $this->Form->create(null,['type'=>'file']);
//select fabric(s) and size(s) for this special pricing
echo $this->Form->input('process',['type'=>'hidden','value'=>'step4']);

echo $this->Form->input('customer_id',['type'=>'hidden','value'=>$postdata['customer_id']]);
echo $this->Form->input('product_type',['type'=>'hidden','value'=>$postdata['product_type']]);
echo $this->Form->input('fabric_name',['type'=>'hidden','value'=>$postdata['fabric_name']]);
foreach($postdata as $key => $val){
	if(substr($key,0,4) == "use_"){
		echo $this->Form->input($key,['type'=>'hidden','value'=>$val]);
	}
}
echo $this->Form->input('mesh',['type'=>'hidden','value'=>$postdata['mesh']]);




echo $this->Form->end();
?>