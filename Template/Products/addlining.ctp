<!-- src/Template/Products/addlining.ctp -->

<h3>Add New Lining:</h3>
<?php
echo $this->Form->create(null);

echo $this->Form->input('title',['label'=>'Lining Name','required'=>true]);

echo $this->Form->input('short_title',['label'=>'Shortened Name','required'=>true]);

echo $this->Form->input('width',['label'=>'Lining Width','required'=>true]);

echo $this->Form->input('price',['label'=>'Lining Price','required'=>true]);


echo "<div id=\"vendorselection\"><h3>Vendor</h3>";
echo $this->Form->select('vendors_id',$vendoroptions,['required'=>true,'empty'=>'--Select Vendor--']);
echo "</div>";


echo $this->Form->button('Add This Lining',['type'=>'submit']);

echo $this->Form->end();
?>

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