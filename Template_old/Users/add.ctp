<!-- src/Template/Users/add.ctp -->

<div class="users form">
<?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?= $this->Form->input('username') ?>
        <?= $this->Form->input('password') ?>
		<?= $this->Form->input('email') ?>
       	<div class="input selectbox required"><label>Role</label>
        <?= $this->Form->select('user_type_id', $allroles, ['empty'=>'---Select A Role--','required'=>true]); ?>
		</div>
        <?= $this->Form->input('first_name'); ?>
        <?= $this->Form->input('last_name'); ?>
        <?= $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive']); ?>
        
   </fieldset>
<?= $this->Form->button(__('Submit')); ?>
<?= $this->Form->end() ?>
</div>