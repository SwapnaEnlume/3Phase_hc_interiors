<!-- src/Template/Quotes/addglobalnote.ctp -->
<?php
echo "<h2>Add Global Note</h2>";
echo "<hr>";
echo $this->Form->create(false);
echo $this->Form->textarea('message',['placeholder'=>'Note text goes here.','required'=>true]);


echo "<div class=\"input radio\"><h4>Should this note appear on Quote PDF?</h4>";
echo $this->Form->radio('appear_on_pdf',['0'=>'No','1'=>'Yes'],['label'=>'Should this note appear on Quote PDF?','required'=>true]);
echo "</div>";


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
$('form').submit(function(){
     $('div.submit input[type=submit]').prop('disabled',true).val('Processing...'); 
});
$(function(){
	$('#cancelbutton').click(function(){
		parent.$.fancybox.close();
	});
});
</script>
<script>
//on enter keydown treat it as tab
$(function(){
    $(document).on('keydown', 'input:visible, select:visible, button:visible,feildset:visible, [type="radio"]:visible, [type="checkbox"]:visible, [tabindex]', function(e) {
            if (e.which === 13) { // 13 is the Enter key code
                e.preventDefault(); // Prevent default action, which is form submission
                moveToNextFocusableElement(this);
            }
        });
});
</script>