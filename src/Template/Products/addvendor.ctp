<!-- src/Template/Products/addvendor.ctp -->

<link rel="stylesheet" href="/css/jquery.Jcrop.min.css">
<script src="/js/jquery.Jcrop.min.js"></script>
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

<h3>Add New Vendor:</h3>
<?php
echo $this->Form->create(null);

echo $this->Form->input('vendor_name',['label'=>'Vendor Name','required'=>true]);


echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Vendor Status');
echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['value'=>'Active']);
echo "</div>";

echo $this->Form->button('Add This Vendor',['type'=>'submit']);

echo $this->Form->end();
?>