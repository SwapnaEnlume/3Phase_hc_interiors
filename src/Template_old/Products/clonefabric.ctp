<!-- src/Template/Products/clonefabric.ctp -->
<h3>Duplicate "<?php echo $oldFabric['fabric_name']." (".$oldFabric['color'].")"; ?>" to New Fabric:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');
echo $this->Form->input('fabric_name',['label'=>'New Fabric Name','required'=>true,'autocomplete'=>'off','value'=>$oldFabric['fabric_name']]);
echo $this->Form->input('color',['label'=>'New Fabric Color','required'=>true,'autocomplete'=>'off']);

echo $this->Form->input('vendor_fabric_name',['label'=>'Vendor Fabric Name','autocomplete'=>'off','required'=>false,'value'=>$oldFabric['vendor_fabric_name']]);
echo $this->Form->input('vendor_color_name',['label'=>'Vendor Fabric Color','autocomplete'=>'off', 'required'=>false,'value'=>$oldFabric['vendor_fabric_color']]);


echo $this->Form->input('primary_image',['label'=>'New Fabric Image','type'=>'file','required'=>false]);

echo $this->Form->button('Submit',['type'=>'submit']);

echo $this->Form->end();
?>