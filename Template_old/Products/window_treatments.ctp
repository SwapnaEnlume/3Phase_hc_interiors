<!-- src/Template/Products/window_treatments.ctp -->
<div id="headingblock">
<h1 class="pageheading">Window Treatments</h1>
<button type="button" id="newcc">+ Add New Window Treatment</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="wtsTable">
<thead>
<tr>
<th>Image(s)</th>
<th>Product Name</th>
<th>Type</th>
<th>Fabric</th>
<th>CORN Welts</th>
<th>Drapery Lining</th>
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
<th>Type</th>
<th>Fabric</th>
<th>CORN Welts</th>
<th>Drapery Lining</th>
<th>QB Item Code</th>
<th>Price</th>
<th>Status</th>
<th>Actions</th>
</tr>
</tfoot>
</table>
<script>
$(function(){
		$('#wtsTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/getwtslist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			buttons:['excelHtml5'],
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'images',"orderable": false},
				{"className":'name',"orderable": true},
				{"className":'wttype',"orderable":true},
				{"className":'fabric',"orderable": false},
				{"className":'haswelts',"orderable": false},
				{"className":'lining','orderable':false},
				{"className":'qbcode',"orderable": false},
				{"className":'price',"orderable": false},
				{"className":'status',"orderable": false},
				{"className":'actions',"orderable": false}
			  ]
		});
		
		$('#newcc').click(function(){
			location.href='/products/window-treatments/add/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newcc{ float:right; }
#wtsTable th.images, #wtsTable td.images{ width:10% !important; }
#wtsTable th.name, #wtsTable td.name{ width:25% !important; }
#wtsTable th.wttype, #wtsTable td.wttype{ width:12% !important; }
#wtsTable th.haswelts, #wtsTable td.haswelts{ width:8% !important; }
#wtsTable th.fabric, #wtsTable td.fabric{ width:12% !important; }
#wtsTable th.price, #wtsTable td.price{ width:15% !important; }
#wtsTable th.status, #wtsTable td.status{ width:8% !important; }
#wtsTable th.actions, #wtsTable td.actions{ width:10% !important; }
</style>