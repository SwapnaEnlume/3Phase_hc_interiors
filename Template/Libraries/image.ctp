<!-- src/Template/Libraries/image.ctp -->
<div id="headingblock">
<h1 class="pageheading">Image Library</h1>
<button type="button" id="newimg">+ Add New Image</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="imgTable">
<thead>
<tr>
<th>ID#</th>
<th>Thumbnail</th>
<th>Image Name</th>
<th>Type</th>
<th>Category</th>
<th>Tags</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
<tfoot>
<tr>
<th>ID#</th>
<th>Thumbnail</th>
<th>Image Name</th>
<th>Type</th>
<th>Category</th>
<th>Tags</th>
<th>Status</th>
<th>Actions</th>
</tr>
</tfoot>
</table>
<script>
var ListViewDataTable;

$(function(){
		ListViewDataTable=$('#imgTable').DataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{
			    "url":"/libraries/getimglist/active.json"
			},
			dom:'<"top"iBfrlp<"clear"><"activestatus">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			buttons:[],
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'id',"orderable": false},
				{"className":'thumb',"orderable": false},
				{"className":'name',"orderable": true},
				{"className":'type',"orderable":false},
				{"className":'category',"orderable": false},
				{"className":'tags',"orderable": false},
				{"className":'status',"orderable": false},
				{"className":'actions',"orderable": false}
			  ]
		});
		
		$('#newimg').click(function(){
			location.href='/libraries/image/add/';
		});
	
		$('a.fancybox').fancybox({
		  helpers: {
			overlay: {
			  locked: false
			}
		  }
		});
		
		$('div.top div.activestatus').html('<label><input type="checkbox" id="activestatus" value="active" checked="checked" onchange="toggleStatus()" /> Active Images Only</label>');
});

function toggleStatus(){
    if($('input#activestatus').is(':checked')){
        ListViewDataTable.ajax.url('/libraries/getimglist/active.json').load();
    }else{
        ListViewDataTable.ajax.url('/libraries/getimglist/all.json').load();
    }
}
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newimg{ float:right; }
#imgTable th.thumb, #imgTable td.thumb{ width:10% !important; }
#imgTable th.name, #imgTable td.name{ width:25% !important; }
#imgTable th.type, #imgTable td.type{ width:12% !important; }
#imgTable th.category, #imgTable td.category{ width:12% !important; }
#imgTable th.tags, #imgTable td.tags{ width:12% !important; }
#imgTable th.status, #imgTable td.status{ width:8% !important; }
#imgTable th.actions, #imgTable td.actions{ width:10% !important; }
#imgTable td.name h3{ font-size:14px; }
#imgTable td.tags ul{ font-size:11px; }
</style>