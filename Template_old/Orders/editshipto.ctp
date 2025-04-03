<!-- src/Template/Products/addshipto.ctp -->

<h3>Edit ShipTo:</h3>
<?php
echo $this->Form->create(null);

echo $this->Form->input('shipping_address_1',['label'=>'ShipTo Address -Line1','required'=>true, 'value'=>$shiptoData['shipping_address_1']]);

echo $this->Form->input('shipping_address_2',['label'=>'ShipTo Address -Line2', 'value'=>$shiptoData['shipping_address_2']]);

echo $this->Form->input('shipping_city',['label'=>'ShipTo City','required'=>true, 'value'=>$shiptoData['shipping_city']]);

echo $this->Form->input('shipping_state',['label'=>'ShipTo State','required'=>true, 'value'=>$shiptoData['shipping_state']]);
echo $this->Form->input('shipping_zipcode',['label'=>'ShipTo Zipcode','required'=>true, 'value'=>$shiptoData['shipping_zipcode']]);
echo "</div>";


echo $this->Form->button('Save Changes',['type'=>'submit']);

echo $this->Form->end();
?>