<!-- src/Template/Orders/voidshipmentbatch.ctp -->
<style>
body{ font-family:Arial,Helvetica,sans-serif; }

form{ text-align:center; }
div.submit{ display:inline-block; }

input[type=submit]{ background:green; color:white; border:0; padding:15px 25px; font-size:large; font-weight:bold; margin:10px; cursor:pointer; }

button[type=button]{ display:inline-block; padding:15px 25px; border:0; font-size:large; margin:10px; cursor:pointer; }

h2{ text-align:center; color:red; }
h3{ text-align:center; color:blue; }
</style>

<div class="converttoorder form">
<h2>Are you sure you want to VOID this Shipment entry?</h2>
<h3>This will reset the batch to <u>Completed + Not Shipped</u></h3>

<?php
echo $this->Form->create(); 
echo $this->Form->input('process',['type'=>'hidden','value'=>'yes']);
echo $this->Form->submit('Yes, Void This Entry');
echo "<button type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";

echo $this->Form->end();
?>
<script>
$(function(){
	
});
</script>
