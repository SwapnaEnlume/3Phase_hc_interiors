<?php
echo $this->Form->create(null);

echo "<h2>Generate new ZOHO auth token</h2><hr>";

echo $this->Form->input('loginemail',['label'=>'ZOHO Login Email','required'=>true,'autocomplete'=>'off']);

echo $this->Form->input('loginpassword',['label'=>'ZOHO Login Password','required'=>true,'type'=>'password']);

echo $this->Form->submit('Generate New Token');

echo $this->Form->end();
?>