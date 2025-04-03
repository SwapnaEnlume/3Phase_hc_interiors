<!-- src/Template/Libraries/deleteimage.ctp -->
<?php
echo $this->Form->create(null);
echo $this->Form->input('process',['type'=>'hidden','value'=>'yes']);

?>
<h2 style="color:red;">Are you sure you want to delete this Image from the Library?</h2>
<h4>This cannot be undone</h4>
<?php

echo "<img src=\"/img/library/".$imageData['filename']."\" style=\"max-width:450px; height:auto; margin:20px auto;\" />";

echo $this->Form->submit('Yes, Delete Now');
echo $this->Form->button('No, Go Back',['type'=>'button','onclick'=>'location.replace(\'/libraries/image/\')']);

echo $this->Form->end();
?>
<style>
form{ max-width: 600px; margin:0 auto; text-align: center; }
div.submit{ display:inline-block; margin-right:30px; }
div.submit input{ font-size:medium; font-weight:bold; color:#FFF; background:#065205; padding:15px 15px; border:1px solid #000; }
</style>