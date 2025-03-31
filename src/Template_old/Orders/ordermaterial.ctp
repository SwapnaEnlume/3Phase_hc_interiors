<h1 style="font-size:22px;">NEW FABRIC PURCHASE</h1>
<h2 style="font-size:24px;"><img src="/files/fabrics/<?php echo $materialData['id']; ?>/<?php echo $materialData['image_file']; ?>" style="width:40px; height:auto; display:inline-block; vertical-align: middle;" /> <?php echo $materialData['fabric_name']." - ".$materialData['color']; ?></h2>

<h2 style="font-size:22px; color:blue;">Vendor: <b><?php echo $materialVendor['vendor_name']; ?></b></h2>

<?php
$fabric=$materialData;

$usedWOs=array();
$usedPOs=array();
$totalyardsthisfabric=0;
$totalquoted=0;
$totalrequired=0;

foreach($requiredMaterialsMOM as $fabricid => $data){
	if($fabricid==$fabric['id']){
		$totalyardsthisfabric=($totalyardsthisfabric+$data['total-yards']);
		$totalrequired=($totalrequired + $data['total-yards']);
	}
}


foreach($requiredMaterialsCOM as $fabricid => $data){
	if($fabricid==$fabric['id']){
		$totalyardsthisfabric=($totalyardsthisfabric+$data['total-yards']);
		
	}
}


foreach($maybeMaterialsMOM as $fabricid => $data){
	if($fabricid==$fabric['id']){
		$totalyardsthisfabric=($totalyardsthisfabric+$data['total-yards']);
		$totalquoted=($totalquoted + $data['total-yards']);
	}
}


foreach($maybeMaterialsCOM as $fabricid => $data){
	if($fabricid==$fabric['id']){
		$totalyardsthisfabric=($totalyardsthisfabric+$data['total-yards']);
	}
}


if($totalyardsthisfabric > 0){
	
}
?>

<h4>Cut: $<?php echo $materialData['cost_per_yard_cut']; ?> /yd &nbsp;&nbsp;<span style="color:blue;">|</span>&nbsp;&nbsp; Bolt (<?php echo $materialData['yards_per_bolt']; ?>): $<?php echo $materialData['cost_per_yard_bolt']; ?> /yd &nbsp;&nbsp;<span style="color:blue;">|</span>&nbsp;&nbsp; Case (<?php echo $materialData['yards_per_case']; ?>): $<?php echo $materialData['cost_per_yard_case']; ?> /yd</h4>

<h3><u><?php echo $totalrequired; ?></u> Total Committed Yards  &nbsp;&nbsp;<span style="color:blue;">|</span>&nbsp;&nbsp; <u><?php echo $totalquoted; ?></u> Quoted Yards</h4>

<?php
echo $this->Form->create(null);
?>
	
	<fieldset>
		<legend>Check all Work Orders to include in this fabric order:</legend>
		<table id="purchaseordertable" width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#000000">
		<thead>
			<tr>
				<th width="7%">Include</th>
				<th width="15%">Work Order</th>
				<th width="15%">Yards</th>
				<th width="33%">Requirements</th>
				<th width="30%">Purchaser Notes</th>
			</tr>
		</thead>
		<tbody>
		<?php
		//print_r($requiredMaterialsMOM);
		$orderTotals=array();
		$orderNumbers=array();
		foreach($requiredMaterialsMOM as $fabricid => $data){
			if($fabricid==$fabric['id']){
				foreach($data['yardages'] as $num => $orderdata){
					if(isset($orderTotals[$orderdata['order_id']])){
						$orderTotals[$orderdata['order_id']]=($orderTotals[$orderdata['order_id']] + $orderdata['yards']);
					}else{
						$orderTotals[$orderdata['order_id']]=$orderdata['yards'];
					}
					if(!in_array($orderdata['order_id'],$orderNumbers)){
						$orderNumbers[$orderdata['order_id']]=$orderdata['order_number'];
					}
				}
			}
		}
			
		foreach($orderTotals as $orderid => $yards){
			echo "<tr>
			<td><input type=\"checkbox\" onclick=\"recalculateTotalOrderYards();\" data-yards=\"".$yards."\" name=\"include_order_".$orderid."\" value=\"1\" /></td>
			<td><b>".$orderNumbers[$orderid]."</b></td>
			<td>".$yards." yards</td>
			<td>";
			foreach($requiredMaterialsMOM as $fabricid=>$data){
				foreach($data['yardages'] as $num => $orderdata){
					if($orderdata['order_id'] == $orderid){
						echo $orderdata['requirements'];
					}
				}
			}
			echo "<div style=\"margin-top:10px;\"><label style=\"color:green; display:inline-block;\"><input type=\"radio\" name=\"reqmet_".$orderid."\" value=\"1\" />Met</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label style=\"color:red; display:inline-block;\"><input type=\"radio\" name=\"reqmet_".$orderid."\" value=\"0\" checked=\"checked\" />Not Met</div></td>
			<td><input type=\"text\" name=\"wo_note_".$orderid."\" placeholder=\"Purchaser Notes\" /></td>
			</tr>";
		}
		?>
		</tbody>
		<tfoot>
		<tr>
			<th colspan="2">&nbsp;</th>
			<th><label>Total Yards: <input type="number" name="totalyardstoorder" id="totalyardstoorder" value="0" /></label></th>
			<th><label>Estimated Arrival/Receive Date: <input type="date" name="shipdate" id="shipdate" /></label></th>
			<th><button type="submit">Place Order</button></th>
		</tr>
		</tfoot>
		</table>
	</fieldset>
	
	<input type="hidden" name="totalyardsneeded" id="totalyardsneeded" value="<?php echo $totalrequired; ?>" />
	
	
<?php
echo $this->Form->end();
?>

<script>
function recalculateTotalOrderYards(){
	var total=0;
	$('input[type=checkbox]').each(function(){
		if($(this).is(':checked')){
			total=(total+parseFloat($(this).attr('data-yards')));
		}
	});
	
	total=Math.ceil(total);
	
	$('#totalyardstoorder').val(total);
}

$(function(){
	$('table#purchaseordertable tbody tr td:first-child').click(function(event){
		if(event.target.nodeName == 'TD'){
			if($(this).find('input[type=checkbox]').is(':checked')){
				$(this).find('input[type=checkbox]').prop('checked',false);
			}else{
				$(this).find('input[type=checkbox]').prop('checked',true);
			}
			recalculateTotalOrderYards();
		}
	});
});
</script>

<style>
#totalyardstoorder{ font-size:20px; padding:5px; width:85px; }
fieldset ul li{ padding:5px; }
body{     font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; }
h1{ text-align:center; font-size:22px; text-decoration: underline; color:#1D3292 }
h2{ text-align:center; }
h3{ text-align:center; margin:0; }
h4{ text-align:center; margin:15px 0 10px 0; }
h3 u{ color:#A62426; }
h4 u{ color:#A62426; }
button[type=submit]{ background:#2F2E94; color:#FFF; border:2px solid #000; padding:15px 30px; font-weight:bold; font-size:16px; cursor: pointer; float:right; }
button.cancel{ background:#780204; color:#FFF; border:1px solid #000; padding:10px 20px; font-size:12px; font-weight:bold; float:left; cursor:pointer; }

table#purchaseordertable{ border:2px solid #000; }
table#purchaseordertable td,table#purchaseordertable th{ border-right:1px solid #000; border-bottom:1px solid #000; }
table#purchaseordertable tr{ border-top:1px solid #000; border-left:1px solid #000; }

table#purchaseordertable thead tr{ background:#2F2E94; }
table#purchaseordertable thead tr th{ color:#FFF; text-align:center; }

table#purchaseordertable tbody tr:nth-of-type(even){
	background:#e8e8e8;
}
table#purchaseordertable tbody tr:nth-of-type(odd){
	background:#f8f8f8;
}
table#purchaseordertable tbody tr td:nth-of-type(1){ text-align:center; }
table#purchaseordertable tbody tr td input[type=radio]{ margin:0 5px 0 0; }

table#purchaseordertable tfoot tr{ background:#222; }
table#purchaseordertable tfoot tr th{ color:#FFF; }
table#purchaseordertable tfoot tr th label{ color:#FFF !important; }
</style>