<h2>Are you sure you want to delete order/quote type <u><?php echo $thisType['type_label']; ?></u>?</h2>
<?php
echo $this->Form->create(null);

echo $this->Form->input('process',['value'=>'yes','type'=>'hidden']);

echo $this->Form->submit('Yes, Delete Now');

echo "<button type=\"button\" onclick=\"location.replace('/settings/quotetypes/');\">No, Cancel Deletion</button>";

echo $this->Form->end();

?>