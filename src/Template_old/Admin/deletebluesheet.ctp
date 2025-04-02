<?php
echo $this->Form->create(null);

echo $this->Form->input('process',['type'=>'hidden','value'=>'1']);

echo "<h2 style=\"text-align:center;\">Are you sure you want to delete this Blue Sheet Template?</h2>";

echo "<p style=\"text-align:center;\"><img src=\"";
echo $pngdata;
echo "\" style=\"border:2px solid red;\" width=\"600\" height=\"300\" /></p>";

echo "<div>
<div style=\"width:48%; float:left; text-align:left;\">";
echo $this->Form->submit('Yes, Delete Now');
echo "</div>
<div style=\"width:48%; text-align:right; float:right;\">";
echo $this->Form->button('No, Go Back',['type'=>'button','onclick'=>'location.replace(\'/admin/bluesheettemplates/\');']);
echo "</div>
<div style=\"clear:both;\"></div>
</div>";

echo $this->Form->end();

?>
<style>
div.submit input[type=submit]{ background:#0F4D12; color:#FFF; border:0; font-size:large; font-weight:bold; padding:20px; }
</style>