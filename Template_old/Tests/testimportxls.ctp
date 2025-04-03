<h3>Import XLS Data</h3>
<?php
if($data){
	print_r($data);
}else{
	echo $this->Form->create(null,['type'=>'file']);
	$this->Form->unlockField('xlsfile');

	echo $this->Form->input('xlsfile',['type'=>'file','label'=>'XLS File']);
	echo $this->Form->submit();
	echo $this->Form->end();
}
?>