<!-- src/Template/Products/linings.ctp -->
<div id="headingblock">
<h1 class="pageheading">Linings</h1>
<button type="button" id="newlining">+ Add New Lining</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="liningsTable">
<thead>
<tr>
<th>Title</th>
<th>Width</th>
<th>Price</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
<tfoot>
<tr>
<th>Title</th>
<th>Width</th>
<th>Price</th>
<th>Actions</th>
</tr>
</tfoot>
</table>
<script>
$(function(){
		$('#liningsTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/getliningslist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			searchHighlight: true,
			"order": [[ 3, "asc" ]],
			"columns": [
				{"className":"title","orderable":false},
				{"className":'width',"orderable": false},
				{"className":'price',"orderable":false},
				{"className":'actions',"orderable": false}
			  ],
			'buttons': []
		});
	
		$('input.checkallbutton').click(function(){
			if($(this).is(':checked')){
				$('#liningsTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',true);
				$('input.checkallbutton').prop('checked',true);
			}else{
				$('#liningsTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',false);
				$('input.checkallbutton').prop('checked',false);
			}
		});
		
		/* PPSASCRUM-312: start */
		$('#liningsTable').on('draw.dt', function() {
			const liningsDataTable = $('#liningsTable').DataTable();
			if ($('td.dataTables_empty').is(':visible') && (liningsDataTable.page.info().recordsTotal - 1) > 0 && (liningsDataTable.page() - 1) > 0) {
				liningsDataTable.page(liningsDataTable.page() - 1).draw(false);
				window.location.reload();
			}
		});
		/* PPSASCRUM-312: end */
		
		$('#newlining').click(function(){
			location.href='/products/linings/add/';
		});
	
		
});
</script>
<style>
button.dobulkedit{
    border: 1px solid #979797;
	color:#333 !important;
    background-color: white;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fff), color-stop(100%, #dcdcdc));
    background: -webkit-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -moz-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -ms-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -o-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: linear-gradient(to bottom, #fff 0%, #dcdcdc 100%);
	margin:0; padding:3px 6px !important;
	font-size:11px; 
}
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newlining{ float:right; }

input.checkallbutton{ margin:0 0 0 0 !important; }

.dt-buttons a.dt-button{ padding:5px !important; font-size:12px !important; margin:0 10px !important; }

#liningsTable th.bulkedit,#liningsTable td.bulkedit{ width:3% !important; }
#liningsTable td.bulkedit input[type=checkbox]{ margin:0 0 0 0 !important; }
#liningsTable td.bulkedit{ text-align:center; }
#liningsTable th{ padding:10px 0 !important; text-align:center; }
#liningsTable th.images, #liningsTable td.images{ width:8% !important; }
#liningsTable th.name, #liningsTable td.name{ width:11% !important; }
#liningsTable th.color, #liningsTable td.color{ width:11% !important; }
#liningsTable th.antimicrobial, #liningsTable td.antimicrobial{ width:6% !important; }
#liningsTable th.railroaded, #liningsTable td.railroaded{ width:6% !important; }
#liningsTable th.price, #liningsTable td.price{ width:10% !important; }
#liningsTable th.cost, #liningsTable td.cost{ width:10% !important; }
#liningsTable th.products, #liningsTable td.products{ width:14% !important; }
#liningsTable td.products ul{ font-size:12px; }
#liningsTable th.status, #liningsTable td.status{ width:10% !important; }
#liningsTable th.actions, #liningsTable td.actions{ width:11% !important; }
</style>