<!-- src/Template/Products/cubicle_curtains.ctp -->
<div id="headingblock">
<h1 class="pageheading">Cubicle + Shower Curtains</h1>
<button type="button" id="newcc">+ Add New Curtain</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="ccsTable">
<thead>
<tr>
<th>Image(s)</th>
<th>Product Name</th>
<th>Fabric</th>
<th>Mesh?</th>
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
<th>Mesh?</th>
<th>QB Item Code</th>
<th>Price</th>
<th>Status</th>
<th>Actions</th>
</tr>
</tfoot>
</table>
<script>
$(function(){
		$('#ccsTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/getccslist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			buttons:['excelHtml5'],
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'images',"orderable": false},
				{"className":'name',"orderable": true},
				{"className":'fabric',"orderable": false},
				{"className":'hasmesh',"orderable": false},
				{"className":'qbcode',"orderable": false},
				{"className":'price',"orderable": false},
				{"className":'status',"orderable": false},
				{"className":'actions',"orderable": false}
			  ]
		});
		
		$('#newcc').click(function(){
			location.href='/products/cubicle-curtains/add/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newcc{ float:right; }
#ccsTable th.images, #ccsTable td.images{ width:11% !important; }
#ccsTable th.name, #ccsTable td.name{ width:15% !important; }
#ccsTable th.hasmesh, #ccsTable td.hasmesh{ width:7% !important; }
#ccsTable th.fabric, #ccsTable td.fabric{ width:15% !important; }
#ccsTable th.qbcode, #ccsTable td.qbcode{ width:13% !important; }
#ccsTable th.price, #ccsTable td.price{ width:15% !important; }
#ccsTable th.status, #ccsTable td.status{ width:10% !important; }
#ccsTable th.actions, #ccsTable td.actions{ width:14% !important; }
</style>