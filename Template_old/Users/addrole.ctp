<!-- src/Template/Users/addrole.ctp -->

<div class="admin userroles addrole">
<?= $this->Flash->render('auth') ?>
<h1>Add New Role</h1>

<div class="clear"></div>

	<?php
	echo $this->Form->create(false);
	
	echo $this->Form->input('role_name',['type'=>'text','required'=>true]);
	
	echo "<br><br>";
	echo $this->Form->submit('Continue To Permissions');
	echo "<br><Br><Br>";
	
	echo $this->Form->end();
	
	?>
	
</div>