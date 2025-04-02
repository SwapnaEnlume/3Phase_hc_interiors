<!-- src/Template/Products/addservice.ctp -->

<h3>Add New Service:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo $this->Form->input('title',['label'=>'Service Title','required'=>true]);

echo $this->Form->input('description',['label'=>'Service Description']);

$options=array();
foreach($allServiceSubclasses as $subclass){
    $options[$subclass['id']]=$subclass['subclass_name'];
}

echo "<div class=\"select input required\"><label>Service Sub-Class:</label>";
echo $this->Form->select('subclass',$options,['required'=>true,'empty'=>'--Select Sub-Class--']);
echo "</div>";


echo $this->Form->input('qb_item_code',['label' => 'Quickbooks Item Code', 'required' => true]);

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Service Status');
echo $this->Form->radio('status',['Active'=>'Active','Discontinued'=>'Discontinued'],['required'=>true,'value'=>'Active']);
echo "</div>";


echo $this->Form->input('primary_image',['type'=>'file','label'=>'Service Image/Thumbnail']);


echo $this->Form->input('price',['type'=>'number','step'=>'any','min'=>'0.00','label'=>'Service Price','required'=>true]);

echo $this->Form->input('cost',['type'=>'number','step'=>'any','label'=>'Service Cost','required'=>false]);

echo $this->Form->button('Add This Service',['type'=>'submit']);

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