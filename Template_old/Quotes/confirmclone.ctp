<!-- src/Template/Quotes/confirmclone.ctp -->

<h1>Are you sure you want to Clone <?php
if($fromOrder==1){
	echo "Order # ".$orderNumber;
}else{
	echo "Quote # ".$quoteNumber;
}
?>?</h1>
<?php
echo $this->Form->create(false);
echo $this->Form->input('process',['type'=>'hidden','value'=>'yes']);
echo "<div style=\"text-align:center;\">";
echo $this->Form->submit('Yes, Clone Now');
echo "&nbsp;&nbsp;";
echo $this->Form->button('No, Go Back',['type'=>'button', 'onclick' => 'history.go(-1)']);
echo "</div>";
echo $this->Form->end();
?>
<style>
form{ width:700px; margin:20px auto; }
h1,h4{ text-align: center; }
div.submit{ display:inline-block; }
div.submit input[type=submit]{ background:green; padding:10px; font-size:16px; color:#FFF; font-weight:bold; border:1px solid #000; }
button[type=button]{ background:red; color:#FFF; padding:5px; font-size:13px; font-weight:bold; display:inline-block; border:1px solid #660000; }
</style>