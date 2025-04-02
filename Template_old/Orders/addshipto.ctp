<!-- src/Template/Products/addshipto.ctp -->

<h3>Create New ShipTo Address:</h3>
<?php
echo $this->Form->create(null);

echo $this->Form->input('shipping_address_1',['label'=>'ShipTo Address -Line1','required'=>true]);

echo $this->Form->input('shipping_address_2',['label'=>'ShipTo Address -Line2']);

echo $this->Form->input('shipping_city',['label'=>'ShipTo City','required'=>true]);

echo $this->Form->input('shipping_state',['label'=>'ShipTo State','required'=>true]);
echo $this->Form->input('shipping_zipcode',['label'=>'ShipTo Zipcode','required'=>true]);

echo "</div>";


echo $this->Form->button('Add This ShipTo',['type'=>'submit']);

echo $this->Form->end();
?>