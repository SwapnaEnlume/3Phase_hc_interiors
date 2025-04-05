<h2>Are you sure you want to delete lining <u><?php echo $liningData['title']; ?></u>?</h2>
<?php
echo $this->Form->create(null);

echo $this->Form->input('process',['value'=>'yes','type'=>'hidden']);

echo $this->Form->submit('Yes, Delete Now');

echo "<button type=\"button\" onclick=\"location.replace('/products/linings/');\">No, Cancel Deletion</button>";

echo $this->Form->end();

?>