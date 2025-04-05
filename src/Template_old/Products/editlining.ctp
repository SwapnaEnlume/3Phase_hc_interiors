<!-- src/Template/Products/editlining.ctp -->

<h3>Edit Lining:</h3>
<?php
echo $this->Form->create(null);

echo $this->Form->input('title',['label'=>'Lining Name','required'=>true,'value'=>$liningData['title']]);

echo $this->Form->input('short_title',['label'=>'Shortened Name','required'=>true,'value'=>$liningData['short_title']]);

echo $this->Form->input('width',['label'=>'Lining Width','required'=>true,'value'=>$liningData['width']]);

echo $this->Form->input('price',['label'=>'Lining Price','required'=>true,'value'=>$liningData['price']]);


echo "<div id=\"vendorselection\"><h3>Vendor</h3>";
echo $this->Form->select('vendors_id',$vendoroptions,['required'=>true,'empty'=>'--Select Vendor--','value'=>$liningData['vendors_id']]);
echo "</div>";


echo $this->Form->button('Save Changes',['type'=>'submit']);

echo $this->Form->end();
?>