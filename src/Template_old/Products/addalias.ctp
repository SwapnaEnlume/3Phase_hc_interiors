<!-- src/Template/Products/addalias.ctp -->

<script>
var allFabrics=<?php echo json_encode($allFabrics); ?>;
</script>
<h3>Add New Fabric Alias:</h3>
<?php
$fabricNames=array();
foreach($allFabrics as $fabric){
	if(!in_array($fabric['fabric_name'],$fabricNames)){
		$fabricNames[$fabric['fabric_name']]=$fabric['fabric_name'];
	}
}

echo $this->Form->create(null,['type'=>'file']);


echo "<div class=\"input selectbox required\"><label>Select a Customer:</label>";
echo "<select name=\"customer_id\" id=\"customer-id\"><option value=\"0\" selected disabled>--Select a Customer--</option>";
foreach($allCustomers as $customer){
	echo "<option value=\"".$customer['id']."\">".$customer['company_name']."</option>";
}
echo "</select>";
echo "</div>";

echo "<div class=\"input selectbox required\"><label>Select a Fabric Pattern:</label>";
echo $this->Form->select('fabric_name',$fabricNames,['empty'=>'--Select Fabric Pattern--','required'=>true]);
echo "</div>";

echo "<div class=\"input selectbox required\"><label>Select a Color:</label>";
echo "<select name=\"fabric_color\" id=\"fabric-color\"><option value=\"0\" disabled selected>--Select a Color--</option></select>";
echo "</div>";


echo $this->Form->input('alias_fabric_name',['label'=>'Alias Name','required'=>true]);
echo $this->Form->input('alias_color',['label'=>'Alias Color','required'=>true]);



echo $this->Form->button('Add Alias',['type'=>'submit']);

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