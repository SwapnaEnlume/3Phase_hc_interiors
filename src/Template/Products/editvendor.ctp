<!-- src/Template/Products/editvendor.ctp -->
<h3>Edit Vendor:</h3>
<?php
echo $this->Form->create(null);

echo $this->Form->input('vendor_name',['label'=>'Vendor Name','required'=>true,'value'=>$vendorData['vendor_name']]);

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Vendor Status');
echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['value'=>$vendorData['status']]);
echo "</div>";

echo $this->Form->button('Save Changes',['type'=>'submit']);

echo $this->Form->end();
?>

<style>
	#antimicrobialboolean h3,#rrboolean h3{ font-size:16px; margin:0; font-weight:bold; }
	#antimicrobialboolean label,#rrboolean label{ display:inline-block; margin-right:20px; }
	div.input > label{ font-weight:bold; font-size:16px; }
</style>
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