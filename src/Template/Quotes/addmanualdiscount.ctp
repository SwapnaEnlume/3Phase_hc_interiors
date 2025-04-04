<!-- src/Template/Quotes/addmanualdiscount.ctp -->

<div class="newquote step2 form">
<h2>Add Manual Discount</h2>
<?php
echo $this->Form->create(); 

echo "<p><label>Quote Total:</label> <b>\$".number_format($quoteData['quote_total'],2,'.',',')."</b></p>";

echo "<div id=\"inputsrow\">";
echo $this->Form->input('discount_name',['label'=>false,'placeholder'=>'Discount Name','required'=>true]);

echo $this->Form->input('discount_amount',['label'=>false,'placeholder'=>'Amount','required'=>true,'type'=>'number','step'=>'any']);

echo $this->Form->select('discount_math',['percent'=>'%','dollar'=>'$'],['empty'=>'Math','required'=>true]);
echo "</div>";

echo $this->Form->submit('Add This Discount');
	
echo $this->Form->end();
?>
</div>

<p><button type="button" id="cancelbutton">Cancel</button></p>

<style>
body{ font-family:'Helvetica',Arial,sans-serif; }
h2{ margin:0; font-size:18px; }

#inputsrow div{ display:inline-block !important; }
#inputsrow input{ padding:5px; width:130px; margin:0 3px; }
#inputsrow select{ padding:5px; width:80px; margin:0 3px; }
</style>
<script>
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