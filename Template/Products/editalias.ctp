<!-- src/Template/Products/editalias.ctp -->

<script>
var allFabrics=<?php echo json_encode($allFabrics); ?>;
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
<h3>Edit Fabric Alias:</h3>
<?php
$fabricNames=array();
foreach($allFabrics as $fabric){
	if(!in_array($fabric['fabric_name'],$fabricNames)){
		$fabricNames[$fabric['fabric_name']]=$fabric['fabric_name'];
	}
}

echo $this->Form->create(null,['type'=>'file']);


echo "<div class=\"input selectbox required\"><label>Select a Customer:</label>";
echo "<select name=\"customer_id\" id=\"customer-id\" disabled>";
foreach($allCustomers as $customer){
	echo "<option value=\"".$customer['id']."\"";
	if($customer['id'] == $thisCustomer['id']){
		echo " selected=\"selected\"";
	}
	echo ">".$customer['company_name']."</option>";
}
echo "</select>";
echo "</div>";

echo "<div class=\"input selectbox required\"><label>Select a Fabric Pattern:</label>";
echo $this->Form->select('fabric_name',$fabricNames,['disabled'=>true,'value'=>$thisFabric['fabric_name'],'required'=>true]);
echo "</div>";

echo "<div class=\"input selectbox required\"><label>Select a Color:</label>";
echo "<select name=\"fabric_color\" id=\"fabric-color\" disabled>";
foreach($allFabrics as $fabricRow){
	if($fabricRow['fabric_name'] == $thisFabric['fabric_name']){
		echo "<option value=\"".$fabricRow['id']."\"";
		if($thisFabric['id'] == $fabricRow['id']){
			echo " selected=\"selected\"";
		}
		echo ">".$fabricRow['color']."</option>";
	}
}
echo "</select>";
echo "</div>";


echo $this->Form->input('alias_fabric_name',['label'=>'Alias Name','value'=>$thisAlias['fabric_name'],'required'=>true]);
echo $this->Form->input('alias_color',['label'=>'Alias Color','value'=>$thisAlias['color'],'required'=>true]);



echo $this->Form->button('Save Changes',['type'=>'submit']);

echo $this->Form->end();
?>

<script>
$(function(){
	$('select[name=\'fabric_name\']').change(function(){
		//fill the Colors list
		$('#fabric-color').html('<option value="0" selected disabled>--Select a Color--</option>');
		$.each(allFabrics,function(num,fabdata){
			if(fabdata.fabric_name==$('select[name=\'fabric_name\']').val()){
				$('select#fabric-color').append('<option value="'+fabdata.id+'">'+fabdata.color+'</option>');
			}
		});
	});
});
</script>
<style>
div.input > label{ font-weight:bold; font-size:16px; }
</style>