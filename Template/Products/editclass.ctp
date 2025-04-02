<h3>Edit Product Class:</h3>
<?php
echo $this->Form->create(null);

echo $this->Form->input('class_name',['label'=>'Class Name','required'=>true,'value'=>$thisClass['class_name']]);

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Status');
echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['required'=>true,'value'=>$thisClass['status']]);
echo "</div>";


echo $this->Form->button('Save Changes',['type'=>'submit']);

echo $this->Form->end();
?>