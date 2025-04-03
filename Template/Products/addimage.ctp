<!-- src/Template/Products/addimage.ctp -->
<h3>Upload an Image:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);
echo $this->Form->input('primary_image',['label'=>false,'type'=>'file','required'=>true]);
echo "<br>";
echo $this->Form->button('Continue',['type'=>'submit']);
echo "&nbsp;&nbsp;<a href=\"javascript:parent.$.fancybox.close()\">Cancel</a>";
echo $this->Form->end();
?>
<style>
body{font-family:'Helvetica',Arial,sans-serif; text-align:center; }
</style>