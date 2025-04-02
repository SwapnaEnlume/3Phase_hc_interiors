<?php
if($doaction=='selectfabric'){

	echo "<fieldset id=\"calculatorfabric\"><legend>Fabric Selection</legend>";
	$thefabrics=array('0'=>'--Create A New Fabric--');
	foreach($fabrics as $fabric){
		if(!in_array($fabric['fabric_name'],$thefabrics)){
			$thefabrics[$fabric['fabric_name']]=$fabric['fabric_name'];
		}
	}
	echo "<p>".$this->Form->select('fabric_name',$thefabrics,['empty'=>'--Select A Fabric--'])."</p>
	</fieldset>";
	echo "<fieldset><legend>Color Selection</legend>";
	echo "<div id=\"coloroptionwrap\"><em>Please select a Fabric above.</em></div>";
	echo "</fieldset>";

	?>

	<script>
	$(function(){
		$('#calculatorfabric select').change(function(){
			if($(this).val()=='0'){
				location.href='/products/fabrics/add/0/addinventory/addinventory';
			}else{
				//get all color options for this fabric
				$.fancybox.showLoading();
				$.get('/quotes/getfabriccolorlist/'+$(this).val(),function(response){
					$('#coloroptionwrap').html(response);
					$.fancybox.hideLoading();
				});
			}
		});
	});

	function doselectfabriccolor(fabricid){
		location.replace('/products/addinventory/'+fabricid);
	}
	</script>
<?php
}elseif($doaction=='newinventory'){
	
	$allquiltpatterns=explode("|",$settings['quilting_patterns']);
	$quiltingPatterns=array();
	foreach($allquiltpatterns as $pattern){
		$quiltingPatterns[$pattern]=$pattern;
	}
	
	echo "<h1 style=\"margin:auto;text-align:center;\">Add New Inventory</h1><hr>";
	echo $this->Form->create(null);

	echo "<p style=\"text-align:center;\"><img src=\"/files/fabrics/".$fabricData['id']."/".$fabricData['image_file']."\" style=\"width:80px;height:auto;display:inline-block;margin-right:15px;\" /><span style=\"display:inline-block; font-weight:bold; font-size:x-large;\">".$fabricData['fabric_name']." - ".$fabricData['color']."</span></p>";

	echo $this->Form->input('yards',['type'=>'number','step'=>'any','label'=>'Number of Yards','tabindex'=>'1', 'required'=>true]);

	echo $this->Form->input('carrier',['label'=>'Carrier/Shipper','tabindex'=>'2', 'required'=>false]);
	echo $this->Form->input('tracking_number',['label'=>'Carrier Tracking Code','tabindex'=>'3', 'required'=>false]);

	echo "<div class=\"selectbox required input\"><label>Warehouse Location</label>";
	echo $this->Form->select('location', $warehouselocations, ['empty'=>'--Select Location--','required' => true]);
	echo "</div>";

	echo $this->Form->input('quilted',['label'=>'Quilted?','type'=>'checkbox']);

	echo "<div style=\"display:none;\" id=\"quiltedoptions\"><h4>Quilting Details</h4>";
	echo "<div class=\"input selectbox\"><label>Backing Material</label>";
	echo $this->Form->select('backing_material',array('Poly'=>'Poly','Poly Cotton' => 'Poly Cotton'),['empty'=>'--Select Backing--']);
	echo "</div>";

	echo "<div class=\"input selectbox\"><label>Quilting Pattern</label>";
	echo $this->Form->select('quilting_pattern',$quiltingPatterns,['empty'=>'--Select Pattern--']);
	echo "</div>";

	echo "</div>";

	echo $this->Form->input('work_order',['label'=>'Work Order #']);


	echo "<div class=\"input textarea\"><label>Notes</label>";
	echo $this->Form->textarea('notes');
	echo "</div>";

	echo "<br><Br>";
	echo $this->Form->submit('Continue');

	echo $this->Form->end();
	echo "<br><br><br><br><br>";
	echo "<script>$(function(){
	$('input[name=yards]').focus();

	$('input[name=quilted]').click(function(){
		if($(this).is(':checked')){
			$('#quiltedoptions').show('fast');
		}else{
			$('#quiltedoptions').hide('fast');
		}
	});

	});</script>";
	echo "<style>form{ width:750px; margin:auto; }</style>";
	
}elseif($doaction=='reviewnewroll'){
	
	echo "<h2>New Roll Added: <u>".$newrollinfo['roll_number']."</u></h2><hr>TAG:<br>";
	echo '<iframe src="/products/getfabricrolltag/'.$newrollinfo['id'].'.pdf" width="400" height="620" style="float:left; margin-right:20px;"></iframe>';
	echo '<button type="button" onclick="window.location.replace(\'/products/addinventory/\');">+ Add Another Roll</button><br><br>';
	echo '<button type="button" onclick="window.location.replace(\'/orders/materials/inventory/\');">&lt; Back to Inventory Overview</button>';
	echo "<div style=\"clear:both;\"></div>";

}
?>