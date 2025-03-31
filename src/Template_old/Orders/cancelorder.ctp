<h1 class="pageheading">Cancel/Void Order</h1>

<?php
echo $this->Form->create(null);

echo "<h2>Please confirm and explain why you are canceling this order:</h2>";

echo $this->Form->input('cancel_reason',['required'=>true]);

echo $this->Form->submit('Cancel This Order');

echo $this->Form->end();

?>