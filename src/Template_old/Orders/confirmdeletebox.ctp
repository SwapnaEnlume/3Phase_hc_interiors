<?php
echo $this->Form->create(null);

echo "<br><br><h2 style=\"text-align:center;color:red;\">Are you sure you want to delete <u>Box# ".$thisBox['box_number']."</u>?</h2><br>";
echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);

echo "<p style=\"text-align:center;\"><button type=\"submit\">Yes, Delete Now</button> &nbsp;&nbsp;&nbsp;&nbsp; <button style=\"background:#444\" type=\"button\" onclick=\"history.go(-1);\">No, Go Back</button></p>";

echo $this->Form->end();