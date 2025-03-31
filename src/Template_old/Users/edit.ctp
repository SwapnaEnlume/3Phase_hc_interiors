<!-- src/Template/Users/edit.ctp -->

<div class="users form">
<?= $this->Form->create($user,['type'=>'file']) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?= $this->Form->input('username',['value'=>$userData['username']]) ?>
        <?= $this->Form->input('password',['label'=>'New Password (Leave blank to keep current password)']) ?>
		<?= $this->Form->input('email',['value'=>$userData['email']]) ?>
       	<div class="input selectbox"><label>Role</label>
        <?= $this->Form->select('user_type_id', $allroles, ['value'=>$userData['user_type_id'],'empty'=>'---Select A Role--','required'=>true]); ?>
		</div>
        <?= $this->Form->input('first_name',['value'=>$userData['first_name']]); ?>
        <?= $this->Form->input('last_name',['value'=>$userData['last_name']]); ?>
        <?= $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['value'=>$userData['status']]); ?>
        
		<?php
		if(file_exists($_SERVER['DOCUMENT_ROOT']."/webroot/img/signatures/".$userData['signature_image'])){
			echo $this->Form->input('changeimage',['type'=>'hidden','value'=>'no']);
			echo "<div id=\"currentimage\"><img src=\"/img/signatures/".$userData['signature_image']."\" style=\"width:300px; height:auto;\" /> <a href=\"javascript:changeImage();\">Change Image</a></div>";
			echo "<div id=\"newimagefield\" style=\"display:none;\">";
			echo $this->Form->input('newimagefile',['type'=>'file','label'=>'Signature PNG']);
			echo " <a href=\"javascript:cancelChange();\">Cancel Change</a></div>";
			
			echo "<script>
			function changeImage(){
				$('#currentimage').hide('fast');
				$('#newimagefield').show('fast');
				$('input[name=changeimage]').val('yes');
			}
			
			function cancelChange(){
				$('#currentimage').show('fast');
				$('#newimagefield').hide('fast');
				$('input[name=changeimage]').val('no');
			}
			</script>";
		}else{
			echo $this->Form->input('changeimage',['type'=>'hidden','value'=>'yes']);
			echo $this->Form->input('newimagefile',['type'=>'file','label'=>'Signature PNG']);
		}
		?>
		
   </fieldset>
<?= $this->Form->button(__('Save')); ?>
<?= $this->Form->end() ?>
</div>