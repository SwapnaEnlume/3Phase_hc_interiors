<div id="headingblock">
<h1 class="pageheading">Add New Order/Quote Type</h1>
</div>

<?php
echo $this->Form->create(null);

echo $this->Form->input('type_label',['type'=>'text','required'=>true]);

echo $this->Form->submit('Create');

echo $this->Form->end();

?>
<style>
form{ max-width:600px; margin:0 auto; }
.submit input{ font-size:large; font-weight:bold; color:#FFF; border:1px solid #000; background:#26337A; padding:10px 15px; }
</style>