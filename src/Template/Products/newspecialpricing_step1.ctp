<!-- src/Template/Products/newspecialpricing.ctp -->
<h2>Add New Customer Special Product Pricing:</h2>
<hr />
<?php
echo $this->Form->create(null,['type'=>'file']);
echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);

echo "<div class=\"input selectbox\">";
echo $this->Form->label('Select existing customer to create a Quote');
echo $this->Form->select('customer_id',$customers,['empty'=>'--Select A Company--','required'=>true]);
echo "</div>";

echo "<div class=\"input selectbox\">";
echo $this->Form->label('Product Type');
echo $this->Form->select('product_type',['cc' => 'Cubicle Curtains','wt' => 'Window Treatments','bs' => 'Bedspreads'],['empty'=>'--Product Type--','required'=>true]);
echo "</div>";

echo $this->Form->submit('Continue');

echo $this->Form->end();
?>