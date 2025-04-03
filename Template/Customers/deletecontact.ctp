<!-- src/Template/Customers/deletecontact.ctp -->

<div class="deletecontact form">
<?php
echo $this->Form->create(null);
echo "<h2>Are you sure you want to delete this contact?</h2>";
echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);
echo $this->Form->button('Yes, Delete Now',['type'=>'submit']); 
echo $this->Form->button('No, Go Back',['type'=>'button']);
echo $this->Form->end();
?>
</div>