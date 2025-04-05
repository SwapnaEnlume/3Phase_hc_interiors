<h3>Add New Product Sub-Class:</h3>
<?php
echo $this->Form->create(null);

echo "<div class=\"input selectbox\">";
echo $this->Form->label('Parent Class');
echo $this->Form->select('class_id',$availableClasses,['required'=>true,'empty'=>'--Select Class--']);
echo "</div>";


echo $this->Form->input('subclass_name',['label'=>'Sub-Class Name','required'=>true]);

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Status');
echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['required'=>true,'value'=>'Active']);
echo "</div>";

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Sherry Tally Default');
echo $this->Form->radio('tally',['1'=>'Count in Sherry Tally','0'=>'Do not count in Sherry Tally'],['required'=>true,'value'=>'1']);
echo "</div>";


echo $this->Form->button('Add This Sub-Class',['type'=>'submit']);

echo $this->Form->end();
?>