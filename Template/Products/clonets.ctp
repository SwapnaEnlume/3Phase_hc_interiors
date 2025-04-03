<!-- PPSASCRUM-300: start -->
<h3>Clone "<?php echo $tsData['title']; ?>" Track Systems:</h3>

<?php

echo $this->Form->create(null, ['type' => 'file']);


echo "<fieldset><legend>Track Item Type</legend>";
echo $this->Form->radio('system_or_component', ['component'=>'Component','system'=>'System'],['value'=>$tsData['system_or_component']]);
echo "</fieldset>";


echo "<fieldset><legend>Track Item Unit of Measure</legend>";
echo $this->Form->radio('unit', ['plf'=>'Per Linear Foot','each'=>'Each'],['value'=>$tsData['unit']]);
echo "</fieldset>";


echo $this->Form->input('title',['label'=>'Name of this Track System / Component','required'=>true]);

echo $this->Form->input('qb_item_code',['label'=>'QuickBooks Item Code/Number','required'=>true,'value'=>$tsData['qb_item_code']]);

echo $this->Form->input('description',['label'=>'Description','type'=>'textarea','required'=>false, 'value'=>$tsData['description']]);

echo $this->Form->input('primary_image',['label'=>'Track Item Image','type'=>'file','required'=>true]);

echo $this->Form->input('price',['type'=>'number','step'=>'any','min'=>'0.00','required'=>true, 'value'=>$tsData['price']]);

echo $this->Form->input('inches_equivalent',['label'=>'Inches Equivalent','type'=>'number','step'=>'any','min'=>'0.00','required'=>true,'value'=>$tsData['inches_equivalent']]);

echo "<fieldset><legend>Track Item Status</legend>";
echo $this->Form->radio('status', ['Active'=>'Active','Inactive'=>'Inactive'],['value'=>$tsData['status']]);
echo "</fieldset>";

echo $this->Form->button('Submit',['type'=>'submit']);

echo $this->Form->end();
?>
<!-- PPSASCRUM-300: end -->