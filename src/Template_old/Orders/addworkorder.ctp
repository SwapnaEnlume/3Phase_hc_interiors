<!-- src/Template/Orders/addworkorder.ctp -->
<h1 class="pageheading">Create New Work Order</h1>

<blockquote>Please enter quantities of each line item you would like to add to this new work order.</blockquote>

<table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000">
	<thead>
		<tr>
			<th>Item</th>
			<th>QTY</th>
			<th>Work Orders</th>
			<th>Shipments</th>
			<th>Qty To Include</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($orderItems as $item){
			$remaining_for_wo=intval($item['item_qty']);
			foreach($item['item_work_orders'] as $workorder){
				$remaining_for_wo = ($remaining_for_wo - intval($workorder['qty_involved']));
			}
			
			echo "<tr>
			<td>".$item['item_title']."</td>
			<td>".$item['item_qty']."</td>
			<td>".print_r($item['item_work_orders'],1)."</td>
			<td>".print_r($item['item_shipments'],1)."</td>
			<td><input type=\"number\" step=\"1\" min=\"0\" value=\"0\" max=\"".$remaining_for_wo."\" /></td>
			</tr>";
		}
		?>
	</tbody>
</table>
<button>Create Work Order with these quantities</button>
<script>
$(function(){
	$('input[type=number]').keyup(function(){
		if(parseInt($(this).val()) > parseInt($(this).attr('max'))){
			$(this).val($(this).attr('max'));
		}
	});
	$('input[type=number]').change(function(){
		if(parseInt($(this).val()) > parseInt($(this).attr('max'))){
			$(this).val($(this).attr('max'));
		}
	});
});
</script>