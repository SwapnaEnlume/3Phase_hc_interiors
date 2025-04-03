<!-- src/Template/Quotes/editlineitemnote.ctp -->
<?php
echo "<h2>Edit <em>Internal</em> Line Item Note</h2>";
echo "<hr>";
echo $this->Form->create(false);
echo $this->Form->textarea('message',['placeholder'=>'Note text goes here.','value'=>$thisNote['message']]);
echo $this->Form->submit('Save Changes');
echo $this->Form->end();
?>
<p>&nbsp;</p>

<p><button type="button" id="cancelbutton">Cancel</button></p>


<style>
body{ font-family:'Helvetica',Arial,sans-serif; }
textarea{ width:90%; padding:2%; }
form .submit input[type=submit]{ background:#26337A; color:#FFF; padding:10px 15px; font-weight:bold; border:1px solid #000; font-size:14px; cursor:pointer; }
</style>

<script>
$(function(){
	$('#cancelbutton').click(function(){
		parent.$.fancybox.close();
	});
});
</script>