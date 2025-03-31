<!-- src/Template/Quotes/addlineitemnote.ctp -->
<?php
echo "<h2>Add ";
if($mode=='order'){
	echo "Internal ";
}
echo "Line Item Note</h2>";
echo "<hr>";
echo $this->Form->create(false);
echo $this->Form->textarea('message',['placeholder'=>'Note text goes here.','required'=>true]);

if($mode=='quote'){
	echo "<div class=\"input radio\"><h4>Should this customer be able to see this note?</h4>";
	echo $this->Form->radio('visible_to_customer',['0'=>'No','1'=>'Yes'],['label'=>'Visible To Customer?','required'=>true]);
	echo "</div>";
}else{
	echo $this->Form->input('visible_to_customer',['value'=>'0','type'=>'hidden']);
}

echo "<br><br>";

echo $this->Form->submit('Submit');
echo $this->Form->end();
?>
<button type="button" id="cancelbutton">Cancel</button>
<div style="clear:both;"></div>

<style>
body{ font-family:'Helvetica',Arial,sans-serif; }
textarea{ width:90%; padding:2%; }
	
#cancelbutton{ float:right; }
form h4{ margin:0; display:inline-block; font-size:14px; }

div.input.radio{ margin:20px 0; }
div.input.radio input{ maragin-left:15px; }

form .submit{ float:left; }
form .submit input[type=submit]{ background:#26337A; color:#FFF; padding:10px 15px; font-weight:bold; border:1px solid #000; font-size:14px; cursor:pointer; }
</style>

<script>
$(function(){
	$('#cancelbutton').click(function(){
		parent.$.fancybox.close();
	});
});
</script>