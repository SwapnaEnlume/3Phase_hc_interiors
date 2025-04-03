<h3>Edit Product Sub-Class:</h3>
<?php
echo $this->Form->create(null);

echo "<div class=\"input selectbox\">";
echo $this->Form->label('Parent Class');
echo $this->Form->select('class_id',$availableClasses,['required'=>true,'empty'=>'--Select Class--','value'=>$thisSubclass['class_id'] ]);
echo "</div>";


echo $this->Form->input('subclass_name',['label'=>'Sub-Class Name','required'=>true,'value'=>$thisSubclass['subclass_name']]);

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Status');
echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['required'=>true,'value'=>$thisSubclass['status']]);
echo "</div>";

echo "<div class=\"input radiobuttons\">";
echo $this->Form->label('Sherry Tally Default');
echo $this->Form->radio('tally',['1'=>'Count in Sherry Tally','0'=>'Do not count in Sherry Tally'],['required'=>true,'value'=>$thisSubclass['tally']]);
echo "</div>";


echo $this->Form->button('Save Changes',['type'=>'submit']);

echo $this->Form->end();
?>