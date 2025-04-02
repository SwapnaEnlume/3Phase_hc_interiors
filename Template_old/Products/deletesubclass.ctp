<h2>Are you sure you want to delete product sub-class <u><?php echo $subclassData['subclass_name']; ?></u>?</h2>
<?php
echo $this->Form->create(null);

echo $this->Form->input('process',['value'=>'yes','type'=>'hidden']);

echo $this->Form->submit('Yes, Delete Now');

echo "<button type=\"button\" onclick=\"location.replace('/products/managesubclasses/');\">No, Cancel Deletion</button>";

echo $this->Form->end();

?>