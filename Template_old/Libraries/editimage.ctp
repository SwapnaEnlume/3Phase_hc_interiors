<!-- src/Template/Libraries/editimage.ctp -->
<h3>Edit Library Image:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo "<div><img src=\"/img/library/".$imageData['filename']."\" style=\"max-width:500px; height:auto; margin:20px 0\" /></div>";

echo $this->Form->input('image_title',['label'=>'Name of this Library Image','required'=>true,'value'=>$imageData['image_title']]);

echo "<div class=\"input selectbox required\"><label>Category</label>";
echo $this->Form->select('categories',$allcategories,['empty'=>'Select Category','value'=>$imageData['categories'],'required'=>true]);
echo "</div>";

echo $this->Form->input('tags',['label'=>'Image Tags','required'=>false,'value'=>$imageData['tags']]);

echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['required'=>true,'value'=>$imageData['status']]);

echo $this->Form->button('Save Changes',['type'=>'submit']);

echo $this->Form->end();
?>
<script>
</script>
<style>
div.input > label{ font-weight:bold; font-size:16px; }
fieldset input[type=text]{ width:90px; height:22px; font-size:12px; padding:2px; }
fieldset label{ font-weight:normal !important; font-size:12px !important; }
fieldset legend a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }
	
#currentimage img{ max-width:250px; height:auto; }

</style>