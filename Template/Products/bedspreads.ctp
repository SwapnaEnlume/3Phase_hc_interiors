<!-- src/Template/Products/bedspreads.ctp -->
<div id="headingblock">
<h1 class="pageheading">Bedspreads</h1>
<button type="button" id="newbs">+ Add New Bedspread</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="bsTable">
<thead>
<tr>
<th>Image(s)</th>
<th>Product Name</th>
<th>Fabric</th>
<th>Quilted?</th>
<th>Mattress</th>
<th>QB Item Code</th>
<th>Price</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
<tfoot>
<tr>
<th>Image(s)</th>
<th>Product Name</th>
<th>Fabric</th>
<th>Quilted?</th>
<th>Mattress</th>
<th>QB Item Code</th>
<th>Price</th>
<th>Status</th>
<th>Actions</th>
</tr>
</tfoot>
</table>
<script>
$(function(){
		$('#bsTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/getbslist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			buttons:['excelHtml5'],
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'images',"orderable": false},
				{"className":'name',"orderable": true},
				{"className":'fabric',"orderable": true},
				{"className":'quilted',"orderable": false},
				{"className":'mattress',"orderable": false},
				{"className":'qbcode',"orderable": true},
				{"className":'price',"orderable": true},
				{"className":'status',"orderable": false},
				{"className":'actions',"orderable": false}
			  ]
		});
		
		$('#newbs').click(function(){
			location.href='/products/bedspreads/add/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newbs{ float:right; }
#bsTable th.images, #bsTable td.images{ width:10% !important; }
#bsTable th.name, #bsTable td.name{ width:23% !important; }
#bsTable th.fabric, #bsTable td.fabric{ width:15% !important; }
#bsTable th.mattress, #bsTable td.mattress{ width:10% !important; }
#bsTable th.quilted, #bsTable td.quilted{ width:10% !important; }
#bsTable th.price, #bsTable td.price{ width:10% !important; }
#bsTable th.status, #bsTable td.status{ width:10% !important; }
#bsTable th.actions, #bsTable td.actions{ width:12% !important; }
</style>