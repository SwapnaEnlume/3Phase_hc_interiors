<!-- src/Template/Products/track_systems.ctp -->
<div id="headingblock">
<h1 class="pageheading">Track Systems</h1>
<button type="button" id="newtsc">+ Add New Track System Component</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="tssTable">
<thead>
<tr>
<th>Image(s)</th>
<th>Product Name</th>
<th>System/Component</th>
<th>Description</th>
<th>Price</th>
<th>Unit</th>
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
<th>System/Component</th>
<th>Description</th>
<th>Price</th>
<th>Unit</th>
<th>Status</th>
<th>Actions</th>
</tr>
</tfoot>
</table>
<script>
$(function(){
		$('#tssTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/gettsslist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			buttons:['excelHtml5'],
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'images',"orderable": false},
				{"className":'name',"orderable": true},
				{"className":'componentsystem','orderable':false},
				{"className":'qbcode',"orderable": false},
				{"className":'price',"orderable": false},
				{"className":'unit',"orderable": false},
				{"className":'status',"orderable": false},
				{"className":'actions',"orderable": false}
			  ]
		});
		
		/* PPSASCRUM-303: start */
		$('#tssTable').on('draw.dt', function() {
			const trackSystemDataTable = $('#tssTable').DataTable();
			
			if ($('td.dataTables_empty').is(':visible') && (trackSystemDataTable.page.info().recordsTotal - 1) > 0 && (trackSystemDataTable.page() - 1) > 0) {
				trackSystemDataTable.page(trackSystemDataTable.page() - 1).draw(false);
				window.location.reload();
			}
			
			/* PPSASCRUM-312: start */
			if ($('div.message.success').is(':visible') && ($('div.message.success').text().includes('Successfully cloned the Track System Component') || $('div.message.success').text().includes('Successfully added new Track System Component'))) {
				if (Math.ceil(trackSystemDataTable.page.info().recordsDisplay / trackSystemDataTable.page.info().length) == trackSystemDataTable.page.info().pages) {
					window.location.reload();
					trackSystemDataTable.page('last').draw('page');
				}
				isNavigatedLast = true;
			}
			/* PPSASCRUM-312: end */
		});
        /* PPSASCRUM-303: end */
		
		$('#newtsc').click(function(){
			location.href='/products/track-systems/add/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newtsc{ float:right; }
#tssTable th.images, #wtsTable td.images{ width:10% !important; }
#tssTable th.name, #wtsTable td.name{ width:25% !important; }
#tssTable th.price, #wtsTable td.price{ width:15% !important; }
#tssTable th.status, #wtsTable td.status{ width:8% !important; }
#tssTable th.actions, #wtsTable td.actions{ width:10% !important; }
</style>