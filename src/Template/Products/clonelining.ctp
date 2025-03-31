<!-- PPSASCRUM-299: start -->
<!-- src/Template/Products/editlining.ctp -->

<?php
echo "<h3>Clone \"" . $oldLining['title'] . "\" Lining:</h3>";

echo $this->Form->create(null);

echo $this->Form->input('title',['label'=>'Lining Name','required'=>true]);

echo $this->Form->input('short_title',['label'=>'Shortened Name','required'=>true]);

echo $this->Form->input('width',['label'=>'Lining Width','required'=>true,'value'=>$oldLining['width']]);

echo $this->Form->input('price',['label'=>'Lining Price','required'=>true,'value'=>$oldLining['price']]);


echo "<div id=\"vendorselection\"><h3>Vendor</h3>";
echo $this->Form->select('vendors_id',$vendoroptions,['required'=>true,'empty'=>'--Select Vendor--']);
echo "</div>";


echo $this->Form->button('Submit',['type'=>'submit']);

echo $this->Form->end();
?>
<!-- PPSASCRUM-299: end -->