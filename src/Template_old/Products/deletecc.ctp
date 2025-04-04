<!-- src/Template/Products/deletecc.ctp -->
<h2 style="color:red;">Are you sure you want to delete this Cubicle Curtain?</h2>
<h4>This cannot be undone</h4>
<?php
echo $this->Form->create(null);
echo $this->Form->input('process',['type'=>'hidden','value'=>'yes']);

echo $this->Form->submit('Yes, Delete Now');
echo $this->Form->button('No, Go Back',['type'=>'button']);

echo $this->Form->end();
?>