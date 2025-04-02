<!-- src/Template/Products/newspecialpricing.ctp -->
<h2>Add New Customer Special Product Pricing:</h2>
<hr />
<?php
echo $this->Form->create(null,['type'=>'file']);
//select fabric(s) and size(s) for this special pricing
echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);

echo $this->Form->input('customer_id',['type'=>'hidden','value'=>$postdata['customer_id']]);
echo $this->Form->input('product_type',['type'=>'hidden','value'=>$postata['product_type']]);		


echo "<div id=\"fabricselector\"><h3>Select a Fabric</h3>";
echo "<select id=\"fabricid\" name=\"fabric_name\" onchange=\"changeFabricColorOptions(this.value)\">
<option value=\"0\" selected disabled>--Select A Fabric--</option>";
foreach($thefabrics as $fabricname => $fabricdata){
	echo "<option value=\"".$fabricname."\">".$fabricname."</option>\n";
}
echo "</select>";

echo "</div>";

echo "<div id=\"fabricColorSelectionWrap\"></div>";

switch($productType){
	case "cc":
		echo "<p><label><input type=\"radio\" name=\"mesh\" value=\"yes\" /> With Mesh</label> <label><input type=\"radio\" name=\"mesh\" value=\"no\" /> No Mesh</label></p>";
	break;
	case "bs":
		echo "<p><label><input type=\"radio\" name=\"quilted\" value=\"yes\" /> Quilted</label> <label><input type=\"radio\" name=\"quilted\" value=\"no\" /> Unquilted</label></p>";
	break;
	case "wt":
		echo "<h3>WT Type</h3>
		<p id=\"wttypeoptions\">
		<label><input type=\"radio\" onchange=\"wttypechange()\" name=\"wt_type\" id=\"wttype_straightcornice\" value=\"Straight Cornice\" /> Straight Cornice</label> 
		<label><input type=\"radio\" onchange=\"wttypechange()\" name=\"wt_type\" id=\"wttype_shapedcornice\" value=\"Shaped Cornice\" /> Shaped Cornice</label> 
		<label><input type=\"radio\" onchange=\"wttypechange()\" name=\"wt_type\" id=\"wttype_boxpleatedvalance\" value=\"Box Pleated Valance\" /> Box Pleated Valance</label> 
		<label><input type=\"radio\" onchange=\"wttypechange()\" name=\"wt_type\" id=\"wttype_tailoredvalance\" value=\"Tailored Valance\" /> Tailored Valance</label> 
		<label><input type=\"radio\" onchange=\"wttypechange()\" name=\"wt_type\" id=\"wttype_pinchpleateddrapery\" value=\"Pinch Pleated Drapery\"> Pinch Pleated Drapery</label>
		</p>";

		echo "<div id=\"weltoptions\"><h3>Welts</h3><p><label><input type=\"radio\" name=\"welts\" value=\"yes\" /> Has Welts</label> <label><input type=\"radio\" name=\"welts\" value=\"no\" /> No Welts</label></p></div>";

		echo "<div id=\"liningoptions\"><h3>Lining</h3><p><label><input type=\"radio\" name=\"lining\" value=\"Unlined\" /> Unlined</label> <label><input type=\"radio\" name=\"lining\" value=\"BO Lining\" /> BO Lining</label> <label><input type=\"radio\" name=\"lining\" value=\"FR Lining\" /> FR Lining</label></p></div>";
		
		echo "<script>
		function wttypechange(){
			$('#wttypeoptions input[type=radio]').each(function(){
				if($(this).is(':checked')){
					if($(this).val() == 'Straight Cornice' || $(this).val() == 'Shaped Cornice'){
						$('#weltoptions').show('fast');
						$('#liningoptions').hide('fast');
					}else if($(this).val() == 'Pinch Pleated Drapery'){
						$('#weltoptions').hide('fast');
						$('#liningoptions').show('fast');
					}else{
						$('#weltoptions').hide('fast');
						$('#liningoptions').hide('fast');
					}
					
				}
			});
		}
		</script>";
		
	break;
}

echo $this->Form->submit('Continue');

echo "<style>
#fabricselections label{ width:20%; float:left; margin:5px; }
#sizeselections label{ width:20%; float:left; margin:5px; }
div.coloroptionsblock{ padding:20px 20px 35px 20px; }
div.coloroptionsblock h4{ line-height: 2rem; margin:0 0 10px 0; font-size:16px; font-weight:bold; color:#26337A; border-bottom:2px solid #26337A; }

div.coloroptionsblock h4 a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }

div.colorselection{ width:185px; float:left; height:35px; }
</style>

<script>
function changeFabricColorOptions(newcolor){
	$.get('/products/getfabriccolorscheckboxes/".$productType."/'+newcolor,function(result){
		$('#fabricColorSelectionWrap').html(result);
	});
}

function checkallcolors(){
	$('#fabricColorSelectionWrap input[type=checkbox]').each(function(){
		$(this).prop('checked',true);
	});
}

function uncheckallcolors(){
	$('#fabricColorSelectionWrap input[type=checkbox]').each(function(){
		$(this).prop('checked',false);
	});
}
</script>";

echo $this->Form->end();
?>