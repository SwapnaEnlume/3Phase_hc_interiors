<!-- src/Template/Products/services.ctp -->
<div id="headingblock">
<h1 class="pageheading">Services</h1>
<button type="button" id="newservice">+ Add New Service</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="servicesTable">
<thead>
<tr>
<th>Image</th>
<th>Service Name</th>
<th>Price</th>
<th>Sub-Class</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
<script>
$(function(){
		$('#servicesTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/getserviceslist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			buttons:['excelHtml5'],
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'images',"orderable": false},
				{"className":'name',"orderable": true},
				{"className":'price',"orderable": true},
				{"className":'subclass','orderable':false},
				{"className":'status',"orderable": false},
				{"className":'actions',"orderable": false}
			  ]
		});
		
		$('#newservice').click(function(){
			location.href='/products/services/add/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newservice{ float:right; }
#servicesTable th.images, #servicesTable td.images{ width:8% !important; }
#servicesTable th.name, #servicesTable td.name{ width:12% !important; }
#servicesTable th.color, #servicesTable td.color{ width:12% !important; }
#servicesTable th.antimicrobial, #servicesTable td.antimicrobial{ width:6% !important; }
#servicesTable th.railroaded, #servicesTable td.railroaded{ width:6% !important; }
#servicesTable th.price, #servicesTable td.price{ width:10% !important; }
#servicesTable th.cost, #servicesTable td.cost{ width:10% !important; }
#servicesTable th.products, #servicesTable td.products{ width:14% !important; }
#servicesTable td.products ul{ font-size:12px; }
#servicesTable th.status, #servicesTable td.status{ width:10% !important; }
#servicesTable th.actions, #servicesTable td.actions{ width:12% !important; }
</style>