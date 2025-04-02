<!-- src/Template/Libraries/image.ctp -->
<div id="headingblock">
<h1 class="pageheading">Add New Image to Image Library</h1>
</div>

<?php
echo $this->Form->create(null,['type'=>'file']);

echo $this->Form->input('image_title',['type'=>'text','required'=>true]);

echo "<div class=\"input selectbox required\"><label>Category</label>";
echo $this->Form->select('categories',$allcategories,['empty'=>'Select Category','required'=>true]);
echo "</div>";

echo $this->Form->input('imagefile',['type'=>'file','required'=>true]);

echo $this->Form->input('tags',['required'=>false]);

echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['required'=>true]);


echo $this->Form->submit('Add To Library');

echo $this->Form->end();

?>
<style>
form{ max-width:600px; margin:0 auto; }
.submit input{ font-size:large; font-weight:bold; color:#FFF; border:1px solid #000; background:#26337A; padding:10px 15px; }
</style>