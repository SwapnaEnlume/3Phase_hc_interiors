<!-- src/Template/Products/addvendor.ctp -->

<link rel="stylesheet" href="/css/jquery.Jcrop.min.css">
<script src="/js/jquery.Jcrop.min.js"></script>

<h3>Add New Vendor:</h3>
<?php
echo $this->Form->create(null);

echo $this->Form->input('vendor_name',['label'=>'Vendor Name','required'=>true]);


echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Vendor Status');
echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['value'=>'Active']);
echo "</div>";

echo $this->Form->button('Add This Vendor',['type'=>'submit']);

echo $this->Form->end();
?>