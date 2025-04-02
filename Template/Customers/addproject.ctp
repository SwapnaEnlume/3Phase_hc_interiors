<!-- src/Template/Customers/addproject.ctp -->
<h3>New Project:</h3>
<?php
if(isset($customerID)){
	echo "<link href=\"/css/base.css\" rel=\"stylesheet\" type=\"text/css\" />";
}

echo $this->Form->create(null,['type'=>'file']);

echo $this->Form->input('title',['label'=>'Name of this Project','required'=>true]);

if(isset($customerID)){
	echo $this->Form->input('customer_id',['type'=>'hidden','value'=>$customerID]);
}else{
	echo "<div class=\"input selectbox required\"><label>Select Customer</label>";
	echo $this->Form->select('customer_id',$customers,['empty'=>'--Select A Company--']);
	echo "</div>";
}


echo "<div class=\"input selectbox required\"><label>Project Manager</label>";
echo $this->Form->select('project_manager',$managers,['empty'=>'--Select PM--','required'=>true]);
echo "</div>";


echo "<div class=\"input radio required\"><label>Project Status</label>";
echo $this->Form->radio('project_status',['Active'=>'Active','Inactive'=>'Inactive','Canceled'=>'Canceled'],['required'=>true,'value'=>'Active']);
echo "</div>";


echo $this->Form->input('full_description',['type'=>'textarea']);

echo $this->Form->input('brief_description');

if(isset($customerID)){
	echo "<div><div style=\"float:left;\"><button style=\"outline:0; color:#333; background:none;border:0;\" type=\"button\" onclick=\"cancelForm()\">Cancel</button></div>
	<div style=\"float:right;\">";
}
echo $this->Form->submit('Create Project');

if(isset($customerID)){
	echo "</div><div style=\"clear:both;\"></div></div>";
}

echo $this->Form->end();
?>
<script>
function cancelForm(){
	parent.$('#projectid').find('option:nth-of-type(1)').attr('selected','selected');
	parent.$.fancybox.close();
}
</script>
<style>
h3{ text-align: center; margin:15px 0; }
div.radio label:nth-of-type(2),
div.radio label:nth-of-type(3),
div.radio label:nth-of-type(4){ font-weight:normal; display:inline-block;  margin-left:15px; }
	
div.radio label:nth-of-type(2):after,
div.radio label:nth-of-type(3):after,
div.radio label:nth-of-type(4):after{
	display:none;
}
	
form{ max-width:450px; margin:0 auto; }

div.submit{ text-align: right; }
div.submit input{ background:#26337A; color:#FFF; padding:15px 25px; font-size:18px; font-weight:bold; border:1px solid #000; }
</style>
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