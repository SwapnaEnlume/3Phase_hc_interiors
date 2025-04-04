<!-- src/Template/Quotes/pricelistcheck.ctp -->
<?php
echo "<div class=\"message error\"><b>NOTICE:</b> This tool is still undergoing testing...</div>";

echo "<h3 style=\"text-align:center;\">Lookup Price List ";
switch($product){
	case "window-treatments":
		echo "Window Treatment";
	break;
	case "bedspreads":
		echo "Bedspread";
	break;
	case "cubicle-curtains":
		echo "Cubicle Curtain";
	break;
	case "track-systems":
		echo "Track Systems";
	break;
	case "services":
		echo "Service";
	break;
}

echo " Pricing</h3><hr>";



/*
echo "<p id=\"qtyline\"><b>";
echo "QTY";
echo ":</b> <input type=\"number\" id=\"qtyvalue\" value=\"1\" min=\"1\" /></p>";
*/
echo "<input type=\"hidden\" name=\"qtyvalue\" id=\"qtyvalue\" value=\"1\" />";



echo "<input type=\"hidden\" name=\"customer_id\" id=\"customer_id\" value=\"0\" />";
echo "<p><label for=\"customeridselector\">Select a Customer</label> <select name=\"customeridselector\" id=\"customeridselector\"><option value=\"0\" selected disabled>--Select Customer--</option>";
foreach($allCustomers as $customerRow){
	echo "<option value=\"".$customerRow['id']."\">".$customerRow['company_name']."</option>\n";
}
echo "</select></p>";


echo "<p id=\"selectproduct\">
<label for=\"productcat\">Select a Product</label>
	<select id=\"product\">
		<option value=\"0\" selected disabled>--Products--</option>
		<option value=\"bedspread\">Bedspreads</option>
		<option value=\"cubicle-curtain\">Cubicle or Shower Curtains</option>
		<option value=\"window-treatment\">Window Treatments</option>
		<option value=\"track-system\">Track Systems</option>
		<option value=\"service\">Services</option>
	</select>
</p>";

if($product == 'cubicle-curtains'){
	echo "<script>$(function(){ $('#product').val('cubicle-curtain'); setproducttype(); });</script>";
}
if($product=='bedspreads'){
	echo "<script>$(function(){ $('#product').val('bedspread'); setproducttype(); });</script>";
}
if($product=='window-treatments'){
	echo "<script>$(function(){ $('#product').val('window-treatment'); setproducttype(); });</script>";
}
if($product=='services'){
	echo "<script>$(function(){ $('#product').val('service'); setproducttype(); });</script>";
}
if($product == 'track-systems'){
	echo "<script>$(function(){ $('#product').val('track-system'); setproducttype(); });</script>";
}



echo "<div id=\"databox\"></div>";


echo "<p><button type=\"button\" onclick=\"location.reload(true);\">Reset</button></p>";

echo "<style>";
echo "p select{ padding:10px; width:65%; }
#descriptionvalue{ width:90%; padding:10px; }
#qtyvalue{ width:95px; padding:10px; }

</style>
<script>
var variationID=0;

function setVariationAttributeSelection(attributeID,variationIDvalue){
	//get the accurate price from ajax, then display the price
	var newval=parseFloat($('select#attribute_'+attributeID+'_values').find('option:selected').attr('data-pricevalue'));
	//alert(newval);
	variationID=variationIDvalue;
	$('#priceeachvalue').html(newval.toFixed(2));
	$('#priceeachline').show('fast');
}

function setproducttype(){
	if($('#product').val() != '0'){
		$('#databox').html('');
		parent.$.fancybox.showLoading();
		$.get('/quotes/getproductselectlist/'+$('#product').val(),function(data){
			//fill the dropdown results
			$('#databox').append(data);
			parent.$.fancybox.hideLoading();
		});
	}
}

$(function(){
	$('#product').change(function(){
		$('#content').html('<div style=\"text-align:center;\"><br><Br>Loading...</div>');
		if($(this).val() == 'bedspread'){
			location.replace('/quotes/pricelistcheck/bedspreads');
		}else if($(this).val() == 'cubicle-curtain'){
			location.replace('/quotes/pricelistcheck/cubicle-curtains');
		}else if($(this).val() == 'window-treatment'){
			location.replace('/quotes/pricelistcheck/window-treatments');
		}else if($(this).val() == 'track-system'){
			location.replace('/quotes/pricelistcheck/track-systems');
		}else if($(this).val() == 'service'){
			location.replace('/quotes/pricelistcheck/services');
		}
	});
	
	
	$('#customeridselector').change(function(){
		$('#customeridselector').prop('disabled',true);
		setInterval('checkReadonlyFields()',300);
	});
	
	
	$('#customeridselector').change(function(){
		$('input#customer_id').val($(this).val());
	});
});

function checkReadonlyFields(){
	
	if($('#content select#fabricid').length && $('#content select#fabricid').val() !== null && $('#content select#fabricid').val() != '' && $('#content select#fabricid').val() != '0'){
		if(!$('#content select#fabricid').prop('disabled')){
			$('#content select#fabricid').prop('disabled',true);
			console.log('fabricid value is '+$('#content select#fabricid').val());
		}
	}else{
		$('#content select#fabricid').prop('disabled',false);
		console.log('fabricid value is NULL');
	}
	
	if($('#content select#fabricidwithcolor').length && $('#content select#fabricidwithcolor').val() !== null && $('#content select#fabricidwithcolor').val() != '' && $('#content select#fabricidwithcolor').val() != '0'){
		if(!$('#content select#fabricidwithcolor').prop('disabled')){
			$('#content select#fabricidwithcolor').prop('disabled',true);
			console.log('fabricidwithcolor value is '+$('#content select#fabricidwithcolor').val());
		}
	}else{
		$('#content select#fabricidwithcolor').prop('disabled',false);
		console.log('fabricidwithcolor value is NULL');
	}
}

</script>";
	
?>

<style>
#selectproduct{ visibility: hidden; width:0; height:0; }
	
body{ font-family:'Helvetica',Arial,sans-serif; }
#lineitemtitle{ width:85%; padding:5px; }
#lineitemdescription{ display:block; width:85%; height:95px; }
fieldset{ margin-bottom:20px; }
#calculatorfabric,#calculatorfabric p{ margin-bottom:0 !important; }
fieldset legend{ font-weight:bold; color:#26337A; }
#calculatorlistbuttons button{ background:#26337A; color:#FFF; margin:5px; border:1px solid #000; font-size:14px; font-weight:bold; padding:10px 15px; cursor:pointer; }

#content{ width:600px; margin:0 auto; padding-bottom:150px; }
	
#priceeachline{ margin:25px 0; }

label{ display:block; font-size:14px; font-weight:bold; color:#26337A; }
select{ width:100% !important; padding:8px !important; background:#FFF; font-size:14px; }
.ui-selectmenu-button.ui-button{ width:92% !important; padding:8px !important; background:#FFF; font-size:14px; border-radius:0 !important; }
#addsimpleproducttoquote{ background:#26337A; color:#FFF; padding:10px 15px; font-weight:bold; border:1px solid #000; font-size:14px; cursor:pointer; }
#cancelbutton{ background:#CCC; border:1px solid #333; color:#333; padding:10px 15px; cursor:pointer; }

#trackrows input[type=number]::-webkit-input-placeholder,
#trackrows input[type=text]::-webkit-input-placeholder { 
  color: #444;
}
#trackrows input[type=number]::-moz-placeholder,
#trackrows input[type=text]::-moz-placeholder{
  color: #444;
}
#trackrows input[type=number]:-ms-input-placeholder,
#trackrows input[type=text]:-ms-input-placeholder{
  color: #444;
}
#trackrows input[type=number]:-moz-placeholder,
#trackrows input[type=text]:-moz-placeholder{
  color:#444;
}

#finishwidthselection label{ display:inline-block; margin-right:20px; }

#pricerow{ font-size:large; }
#priceeachvalue{ font-size:x-large; }
</style>