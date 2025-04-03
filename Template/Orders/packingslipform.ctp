<?php
echo $this->Form->create(null,['url'=>['action' => '/packingslipform/'.$orderData['id'].'/Order%20'.$orderData['order_number'].'%20Packing%20Slip.pdf']]);

echo '<h1 style="font-size:22px;margin:20px 0;">ORDER # '.$orderData['order_number'].' - BUILD PACKING SLIP</h1>';
echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);

echo "<div id=\"packslipnumberwrap\">";
echo $this->Form->input('packslip_number',['label'=>'Packing List Number']);
echo "</div>";

echo "<table id=\"packslipbuild\" width=\"900\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
echo "<thead>
<tr>
<th width=\"5%\">&nbsp;</th>
<th width=\"7%\">LINE</th>
<th width=\"9%\">QTY ORDERED</th>
<th width=\"9%\">PREVIOUSLY SHIPPED</th>
<th width=\"10%\">QTY IN PACKAGE</th>
<th width=\"10%\">BACK ORDER</th>
<th width=\"40%\" colspan=\"4\">DESCRIPTION</th>
<th width=\"10%\">BOX #</th>
</tr>
</thead>";

echo "<tbody>";

foreach($lineItems as $itemID => $itemData){
	echo "<tr data-order-line-id=\"".$itemData['order_item_id']."\" data-previouslyshipped=\"".$itemData['previously_shipped']."\" data-qtyordered=\"".$itemData['data']['qty']."\" class=\"";
	if(floatval($itemData['data']['qty']) == floatval($itemData['previously_shipped'])){
		echo "allcompleted";
	}elseif(floatval($itemData['data']['qty']) < floatval($itemData['previously_shipped'])){
		echo "duplicated";
	}
	echo "\">
	<td width=\"5%\">".$this->Form->input('useline_'.$itemID,['label'=>false,'type'=>'checkbox','value'=>1])."</td>
	<td width=\"7%\">".$itemData['data']['line_number']."</td>
	<td width=\"9%\">".$itemData['data']['qty']."</td>
	<td width=\"9%\">".$itemData['previously_shipped']."</td>
	<td width=\"10%\">".$this->Form->input('qty_in_package_'.$itemID,['class'=>'packagecontains','min'=>0,'max'=>(floatval($itemData['data']['qty'])-floatval($itemData['previously_shipped'])),'type'=>'number','label'=>false])."</td>
	<td width=\"10%\">".$this->Form->input('backorder_count_'.$itemID,['class'=>'backordercount','type'=>'number','label'=>false])."</td>
	<td width=\"10%\">".$itemData['fabricdata']['fabric_name']."</td>
	<td width=\"7%\">".$itemData['fabricdata']['color']."</td>
	<td width=\"7%\">".$itemData['metadata']['width']." x ".$itemData['metadata']['length']."</td>
	<td width=\"16%\">Cubicle Curtain</td>
	<td width=\"10%\">".$this->Form->input('box_number_'.$itemID,['label'=>false])."</td>
	</tr>";
}

echo "</tbody>";
echo "</table>";

echo $this->Form->submit('Create Packing Slip');

echo $this->Form->end();
?>
<style>
	
#packslipnumberwrap div.input label{ display:inline-block !important; width:30%; text-align:right; padding-right:15px; font-weight: bold; }
#packslipnumberwrap div.input input{ display:inline-block !important; width:38%; text-align:left; margin-right:20%; }
	
form{ text-align: center; padding:0 0 35px 0; width:900px; margin:0 auto; }
table#packslipbuild{ width:900px !important; margin:0 auto; }
table#packslipbuild thead tr{ background:#2345A0; }
table#packslipbuild thead tr th{ color:#FFF; }
table#packslipbuild th,
table#packslipbuild td{ padding:5px !important; text-align:center; font-size:11px !important; }
table#packslipbuild tbody tr:nth-of-type(odd){ background:#FFFFFF; }
table#packslipbuild tbody tr:nth-of-type(even){ background:#e8e8e8; }

table#packslipbuild tr{ border-bottom:inherit !important; }
	
table#packslipbuild tbody tr.allcompleted{ background: #5DA654 !important; }
table#packslipbuild tbody tr.allcompleted input{ background:rgba(255,255,255,0.4) !important; }
table#packslipbuild tbody tr.allcompleted td{ color:#FFFFFF !important; }

table#packslipbuild tbody tr.allcompleted td:last-child{ position: relative; }
table#packslipbuild tbody tr.allcompleted td{ color:#FFFFFF !important; }
table#packslipbuild tbody tr.allcompleted td:last-child:after{ 
	position:absolute;
	top:14px;
	right:-168px;
	background:#D3F3D1;
	color:#177019;
	border:2px solid #177019; 
	font-size:10px;
	font-weight:bold !important;
	content:' PACKED AND/OR SHIPPED ';
	display:block;
	z-index:555;
	width:170px;
	padding:4px;
}
	
table#packslipbuild tbody tr.allcompleted td:nth-of-type(4){ background:#34C533 !important; }
	
	
	
table#packslipbuild tbody tr.duplicated{ background:#971618 !important; }
table#packslipbuild tbody tr.duplicated td:last-child{ position: relative; }
table#packslipbuild tbody tr.duplicated td{ color:#FFFF00 !important; }
table#packslipbuild tbody tr.duplicated td:last-child:after{ 
	position:absolute;
	top:14px;
	right:-198px;
	background:#FFFF00; 
	color:#FF0000;
	border:2px solid #FF0000; 
	font-size:10px;
	font-weight:bold !important;
	content:' DUPLICATE SHIPMENT(S) DETECTED ';
	display:block;
	z-index:555;
	width:200px;
	padding:4px;
}

table#packslipbuild tbody tr.duplicated td:nth-of-type(4){ background:#FF0000 !important; }
	
form div.submit{ padding:15px 0; text-align: center; }
form div.submit input[type=submit]{ background:#2345A0; border:2px solid #000; font-size:22px !important; color:#FFFFFF; font-weight:bold; padding:10px 25px !important; }
</style>
<script>
function recalcBackorders(){
	$('table#packslipbuild tbody tr').each(function(){
		if($(this).hasClass('allcompleted') || $(this).hasClass('duplicated')){
			//ignore
			$(this).find('input[type=checkbox]').prop('checked',false);
		}else{
			if($(this).find('input[type=number],input[type=text]').val() != ''){
				$(this).find('input[type=checkbox]').prop('checked','checked');
			}
			var thisOrderedQty=parseInt($(this).attr('data-qtyordered'));
			var thisPackageContainsQty=parseInt($(this).find('input.packagecontains').val());
			var previouslyShippedQty=parseInt($(this).attr('data-previouslyshipped'));
			var newBackOrderVal=((thisOrderedQty-previouslyShippedQty)-thisPackageContainsQty);
			$(this).find('input.backordercount').val(newBackOrderVal);
		}
	});
}
	
$(function(){
	$('table#packslipbuild tbody tr.allcompleted input').attr('readonly','readonly').attr('disabled','disabled');
	
	$('form input').change(function(){ recalcBackorders(); });
	$('form input').keyup(function(){ recalcBackorders(); });
});
</script>