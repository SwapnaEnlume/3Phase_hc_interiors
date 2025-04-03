<!-- src/Template/Quotes/deleteconfirm.ctp -->

<h1>Are you sure you want to delete this Quote?</h1>
<h4>This cannot be undone.</h4>
<?php
echo $this->Form->create(false);
echo $this->Form->submit('Yes, Delete Now');
echo $this->Form->button('No, Go Back',['type'=>'button']);
echo $this->Form->end();
?>