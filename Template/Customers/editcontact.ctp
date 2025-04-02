<!-- src/Template/Customers/editcontact.ctp -->

<div class="editcontact form">
<?= $this->Form->create(null) ?>
    <fieldset>
        <legend>Edit Contact</legend>
		<?php
		echo $this->Form->input('customer',['readonly'=>true,'value'=>$customer['company_name']]); 
		echo $this->Form->input('first_name',['required'=>true,'value'=>$contactdetails['first_name']]);
		echo $this->Form->input('last_name',['required'=>true,'value'=>$contactdetails['last_name']]);
		echo $this->Form->input('email_address',['type'=>'email','required'=>true,'value'=>$contactdetails['email_address']]);
        echo $this->Form->input('secondary_email_address',['type'=>'email','required'=>false,'value'=>$contactdetails['secondary_email']]);
		echo $this->Form->input('primary_phone_number',['required'=>false,'value'=>$contactdetails['phone']]);
		
		echo $this->Form->input('mobile_phone_number',['value'=>$contactdetails['mobile_phone']]);
		
		?>
   </fieldset>
<?= $this->Form->button(__('Save Changes')); ?>
<?= $this->Form->end() ?>
</div>
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