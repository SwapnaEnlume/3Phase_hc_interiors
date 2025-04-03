<div id="headingblock">
<h1 class="pageheading">Order/Quote Types</h1>
<button type="button" id="newimg">+ New Type</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="typesTable">
<thead>
<tr>
<th>Type</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
</tbody>
</table>
<script>
var ListViewDataTable;

$(function(){
		ListViewDataTable=$('#typesTable').DataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{
			    "url":"/settings/getquotetypeslist.json"
			},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			buttons:[],
			searchHighlight: true,
			"order": [[ 0, "asc" ]],
			"columns": [
				{"className":'type',"orderable":false},
				{"className":'actions',"orderable": false}
			  ]
		});
		
		$('#newimg').click(function(){
			location.href='/settings/quotetypes/add/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newimg{ float:right; }
</style>