<!-- src/Template/Products/specialpricing.ctp -->

<div id="headingblock">
<h1 class="pageheading"><u><?php switch($productType){
	case "bs":
		echo "Bedspread";
	break;
	case "cc":
		echo "Cubicle/Shower Curtain";
	break;
	case "wt":
		echo "Window Treatment";
	break;
} ?></u> Special Customer Pricing</h1>
<button type="button" id="newspecialpricing">+ Add New Special Pricing</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="specialPricingTable">
<thead>
<tr>
<th><input type="checkbox" id="checkall" name="checkall" value="1" /></th>
<th>Rule ID</th>
<th>Customer</th>
<th>Product Type</th>
<th>Product Name</th>
<th>Colors</th>
<th>Size</th>
<th>Price</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
<script>
$(function(){
	
	$('#checkall').click(function(){
		$('#specialPricingTable tbody tr').each(function(){
			if($('#checkall').is(':checked')){
				$(this).find('input[type=checkbox]').prop('checked',true);
			}else{
				$(this).find('input[type=checkbox]').prop('checked',false);
			}
		});
	});
	
	$('#specialPricingTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/getspecialpricinglist/<?php echo $productType; ?>/<?php 
					if(isset($productID) && strlen(trim($productID)) >0){
						echo $productID;
					}else{
						echo "all";
					}
					?>.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			buttons:[
				'excelHtml5','print',
				{
					"text": 'Bulk Delete',
					"action": function ( e, dt, node, config ) {
						if($('#specialPricingTable tbody input[type=checkbox]:checked').length > 1){
							var bulkids=$('#specialPricingTable tbody input[type=checkbox]:checked').serialize();
							location.href='/products/bulkdeletespecialprices/'+encodeURIComponent(bulkids);
						}else{
							alert('You must select at least 2 rules before using Bulk Delete.');
						}
					}
				}	
			],
					
			
					
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'selectbox','orderable':false},
				{"className":"ruleid","orderable":false},
				{"className":'customer',"orderable": false},
				{"className":'product_type',"orderable":false},
				{"className":'product_name',"orderable":false},
				{"className":'colors',"orderable":false},
				{"className":'size',"orderable": false},
				{"className":'price',"orderable": false},
				{"className":'actions',"orderable": false}
			  ]
		});
		
		$('#newspecialpricing').click(function(){
			location.href='/products/newspecialpricing/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newspecialpricing{ float:right; }
	
#specialPricingTable th.selectbox{ width:3% !important; text-align: center; padding:3px !important; }
#specialPricingTable td.selectbox{ width:3% !important; text-align: center; padding:12px 3px !important; }

#specialPricingTable th.selectbox input[type=checkbox],
#specialPricingTable td.selectbox input[type=checkbox]{ margin:0 0 0 0 !important; padding:0 0 0 0 !important; }

#specialPricingTable th.ruleid, #specialPricingTable td.ruleid{ width:8% !important; font-family:Arial !important; }
	
#specialPricingTable th.customer, #specialPricingTable td.customer{ width:16% !important; font-family:Arial !important; }
	
#specialPricingTable th.product_type, #specialPricingTable td.product_type{ width:12% !important; font-family:Arial !important; }	

#specialPricingTable th.product_name, #specialPricingTable td.product_name{ width:11% !important; font-family:Arial !important; }
#specialPricingTable th.colors, #specialPricingTable td.colors{ width:14% !important; font-family:Arial !important; }
#specialPricingTable th.size, #specialPricingTable td.size{ width:12% !important; font-family:Arial !important; }
#specialPricingTable th.price, #specialPricingTable td.price{ width:12% !important; font-family:Arial !important; }
#specialPricingTable th.actions, #specialPricingTable td.actions{ width:12% !important; font-family:Arial !important; }
</style>