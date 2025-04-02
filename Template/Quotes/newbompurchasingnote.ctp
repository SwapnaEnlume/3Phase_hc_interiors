<style>
textarea{ width:100%; height:110px; }
div.submit{ width:50%; float:left; }
button.cancel{ float:right; }
</style>
<?php
echo $this->Form->create(null);

echo "<h3>Add New Purchasing Note</h3>";

echo $this->Form->textarea('note',['placeholder'=>'Note text goes here.','required'=>true]);

echo $this->Form->submit('Submit');

echo "<button class=\"cancel\" type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";

echo "<div style=\"clear:both;\"></div>";

echo $this->Form->end();
?>
<script>$(function(){
$('textarea').focus();
});
</script>