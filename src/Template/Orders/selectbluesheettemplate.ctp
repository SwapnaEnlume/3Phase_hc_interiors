<?php
echo $this->Form->create(null);
echo "<br><center><b>Select a Template:</b></center><Br>";

echo $this->Form->input('template_id',['type'=>'hidden','value'=>'0']);

$templateOptions=array();
foreach($bluesheetTemplates as $templateRow){
	$templateOptions[$templateRow['id']]=$templateRow['template_name'];
}


echo "<div class=\"input selectbox\">";
echo $this->Form->select('template_id_select',$templateOptions,['empty'=>'--Select a Blue Sheet Template--','required'=>true]);
echo "</div>";


foreach($bluesheetTemplates as $templateRow){
	echo "<div class=\"templateEntry\"><img src=\"".$templateRow['pngdata']."\" onclick=\"selectTemplate('".$templateRow['id']."');\" /><h4>".$templateRow['template_name']."</h4></div>";
}


//echo $this->Form->button('Continue',['type'=>'button']);


echo $this->Form->end();

?>
<br><br><br>

<script>
function selectTemplate(newid){
		window.location.href='/orders/buildbluesheet/<?php echo $lineItemID; ?>/step2/'+newid;
}

$(function(){
	$('select[name=template_id_select]').change(function(){
		window.location.href='/orders/buildbluesheet/<?php echo $lineItemID; ?>/step2/'+$(this).val();
	});
});
</script>

<style>
body{ background:#CCC; }
div.templateEntry{ width:30%; float:left; text-align:center; cursor:pointer; margin:1%; }
div.templateEntry h4{ margin:0; }
div.templateEntry img{ width:100%; height:auto; }
#container{ background:#CCC;  }
form div.selectbox{ display:inline-block; width:50%; }
div.button{ display:inline-block; width:48%; margin-left:1%; }
button[type=button]{ margin-left:20px; }
</style>