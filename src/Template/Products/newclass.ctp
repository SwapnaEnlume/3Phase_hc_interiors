<h3>Add New Product Class:</h3>
<?php
echo $this->Form->create(null);
echo $this->Form->input('class_name',['label'=>'Class Name','required'=>true]);

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Status');
echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['required'=>true,'value'=>'Active']);
echo "</div>";


echo $this->Form->button('Add This Class',['type'=>'submit']);

echo $this->Form->end();
?>