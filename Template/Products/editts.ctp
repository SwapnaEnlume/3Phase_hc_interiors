<!-- src/Template/Products/editts.ctp -->
<h3>Edit Track Systems:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo "<fieldset><legend>Track Item Type</legend>";
echo $this->Form->radio('system_or_component', ['component'=>'Component','system'=>'System'],['value'=>$tsData['system_or_component']]);
echo "</fieldset>";


echo "<fieldset><legend>Track Item Unit of Measure</legend>";
echo $this->Form->radio('unit', ['plf'=>'Per Linear Foot','each'=>'Each'],['value'=>$tsData['unit']]);
echo "</fieldset>";



echo $this->Form->input('title',['label'=>'Name of this Track System / Component','required'=>true,'value'=>$tsData['title']]);


echo $this->Form->input('qb_item_code',['label'=>'QuickBooks Item Code/Number','required'=>true,'value'=>$tsData['qb_item_code']]);

echo $this->Form->input('description',['label'=>'Description','type'=>'textarea','required'=>false, 'value'=>$tsData['description']]);



//image
echo "<div style=\"padding:10px 25px;\" id=\"currentimage\">";
//echo "<div><img src=\"/files/track-systems/".$tsData['id']."/".$tsData['image_file']."\" /></div>";
/*PPSASCRUM-124: start*/
echo "<div><img src=\"/files/track-systems/".$tsData['id']."/".$tsData['primary_image']."\" width=\"75\" height=\"75\" /></div>";
/*PPSASCRUM-124: end*/
echo "<div style=\"padding:10px 0;\"><a href=\"javascript:changeImage();\">Change Image</a></div>";
echo "</div>";

echo "<div id=\"newimage\" style=\"padding:10px 25px; visibility:hidden; height:0px;\">";
echo $this->Form->input('new_image',['label'=>'New Track Item Image','required'=>false,'type'=>'file']);
echo "<div><a href=\"javascript:unchangeImage();\">Cancel Change</a></div>";
echo "</div>";

echo "<script>
function changeImage(){
	$('#currentimage').css({'visibility':'hidden','height':'0px'});
	$('#newimage').css({'visibility':'visible','height':'auto'});
}

function unchangeImage(){
	$('#currentimage').css({'visibility':'visible','height':'auto'});
	$('#newimage').css({'visibility':'hidden','height':'0px'});
}
</script>";



//echo $this->Form->input('primary_image',['label'=>'Track Item Image','type'=>'file','required'=>false]);


echo $this->Form->input('price',['type'=>'number','step'=>'any','min'=>'0.00','required'=>true, 'value'=>$tsData['price']]);

//echo $this->Form->inpit('inches_equivalent',['type'=>'number','step'=>'any','min'=>'0.00','required'=>false,'value'=>$tsData['inches_equivalent']]);
/*PPSASCRUM-124: start*/
echo $this->Form->input('inches_equivalent',['label'=>'Inches Equivalent','type'=>'number','step'=>'any','min'=>'0.00','required'=>true,'value'=>$tsData['inches_equivalent']]);
/*PPSASCRUM-124: end*/

echo "<fieldset><legend>Track Item Status</legend>";
echo $this->Form->radio('status', ['Active'=>'Active','Inactive'=>'Inactive'],['value'=>$tsData['status']]);
echo "</fieldset>";

echo $this->Form->button('Save Changes',['type'=>'submit']);

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