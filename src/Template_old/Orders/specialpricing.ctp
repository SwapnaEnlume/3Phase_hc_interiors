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
<th>Customer</th>
<th>Product Name</th>
<th>Colors</th>
<th>Size</th>
<th>Price</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
<tfoot>
<tr>
<th>Customer</th>
<th>Product Name</th>
<th>Colors</th>
<th>Size</th>
<th>Price</th>
<th>Actions</th>
</tr>
</tfoot>
</table>
<script>

$(function(){
		$('#specialPricingTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/getspecialpricinglist/<?php echo $productType; ?>/<?php 
					if(isset($productID)){
						echo $productID;
					}else{
						echo "all";
					}
					?>.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			buttons:[
				'excelHtml5','print'
			],
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'customer',"orderable": false},
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
#specialPricingTable th.customer, #specialPricingTable td.customer{ width:20% !important; font-family:Arial !important; }
#specialPricingTable th.product_name, #specialPricingTable td.product_name{ width:12% !important; font-family:Arial !important; }
#specialPricingTable th.colors, #specialPricingTable td.colors{ width:18% !important; font-family:Arial !important; }
#specialPricingTable th.size, #specialPricingTable td.size{ width:15% !important; font-family:Arial !important; }
#specialPricingTable th.price, #specialPricingTable td.price{ width:15% !important; font-family:Arial !important; }
#specialPricingTable th.actions, #specialPricingTable td.actions{ width:20% !important; font-family:Arial !important; }
</style>