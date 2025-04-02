<!-- src/Template/Products/editservice.ctp -->

<h3>Edit Service:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo $this->Form->input('title',['value'=>$serviceData['title'],'label'=>'Service Title','required'=>true]);

echo $this->Form->input('description',['value'=>$serviceData['description'],'label'=>'Service Description']);

$options=array();
foreach($allServiceSubclasses as $subclass){
    $options[$subclass['id']]=$subclass['subclass_name'];
}

echo "<div class=\"select input required\"><label>Service Sub-Class:</label>";
echo $this->Form->select('subclass',$options,['required'=>true,'empty'=>'--Select Sub-Class--','value'=>$serviceData['subclass']]);
echo "</div>";

echo $this->Form->input('qb_item_code',['value' => $serviceData['qb_item_code'], 'label' => 'Quickbooks Item Code', 'required' => true]);

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Service Status');
echo $this->Form->radio('status',['Active'=>'Active','Discontinued'=>'Discontinued'],['required'=>true,'value'=>$serviceData['status']]);
echo "</div>";

echo $this->Form->input('changeimage',['type'=>'hidden','value'=>'no']);

echo "<div id=\"dochangeimageno\" class=\"input imagechanger\"><br><br><label>Image File</label>
<img src=\"/files/services/".$serviceData['id']."/".$serviceData['image_file']."\" style=\"width:200px; height:auto;\" /><br /><a href=\"javascript:doChangeImage();\">Change Image</a>
<br><br>
</div>";

echo "<div id=\"dochangeimageyes\" style=\"display:none;\"><br><br>";
echo $this->Form->input('primary_image',['type'=>'file','label'=>'New Service Image/Thumbnail']);
echo "<a href=\"javascript:cancelChangeImage();\">Cancel Image Change</a><br><br>";
echo "</div>";


echo $this->Form->input('price',['type'=>'number','step'=>'any','min'=>'0.0','label'=>'Service Price','required'=>true,'value'=>$serviceData['price']]);

echo $this->Form->input('cost',['type'=>'number','step'=>'any','label'=>'Service Cost','required'=>false,'value'=>$serviceData['cost']]);

echo $this->Form->button('Save Changes',['type'=>'submit']);

echo $this->Form->end();
?>
<script>
function doChangeImage(){
	$('#changeimage').val('yes');
	$('#dochangeimageno').hide('fast');
	$('#dochangeimageyes').show('fast');
	
}
	
function cancelChangeImage(){
	$('#changeimage').val('no');
	$('#dochangeimageno').show('fast');
	$('#dochangeimageyes').hide('fast');
	
}
</script>
<style>
	
</style>