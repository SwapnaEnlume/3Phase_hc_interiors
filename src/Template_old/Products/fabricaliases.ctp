<!-- src/Template/Products/fabricaliases.ctp -->
<div id="headingblock">
<h1 class="pageheading">Fabric Aliases</h1>
<button type="button" id="newfabric">+ Add New Alias</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="fabricsTable">
<thead>
<tr>
<th>Image</th>
<th>Fab ID</th>
<th>HCI Name</th>
<th>HCI Color</th>
<th>Customer</th>
<th>Alias Name</th>
<th>Alias Color</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
<tfoot>
<tr>
<th>Image</th>
<th>Fab ID</th>
<th>HCI Name</th>
<th>HCI Color</th>
<th>Customer</th>
<th>Alias Name</th>
<th>Alias Color</th>
<th>Actions</th>
</tr>
</tfoot>
</table>
<script>
$(function(){
		$('#fabricsTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/getfabricsaliaslist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			searchHighlight: true,
			"order": [[ 3, "asc" ]],
			"columns": [
				{"className":'images',"orderable": false},
				{"className":'idnumber','orderable':false},
				{"className":'realname',"orderable": false},
				{"className":'realcolor',"orderable": false},
				{"className":'customer',"orderable": false},
				{"className":'aliasname',"orderable": true},
				{"className":'aliascolor',"orderable": true},
				{"className":'actions',"orderable": false}
			  ],
			buttons:['excelHtml5']
		});
	
		
		
		$('#newfabric').click(function(){
			location.href='/products/fabricaliases/add/';
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
#headingblock #newfabric{ float:right; }

input.checkallbutton{ margin:0 0 0 0 !important; }

.dt-buttons a.dt-button{ padding:5px !important; font-size:12px !important; margin:0 10px !important; }

#fabricsTable th.bulkedit,#fabricsTable td.bulkedit{ width:3% !important; }
#fabricsTable td.bulkedit input[type=checkbox]{ margin:0 0 0 0 !important; }
#fabricsTable td.bulkedit{ text-align:center; }
#fabricsTable th{ padding:10px 0 !important; text-align:center; }
#fabricsTable th.images, #fabricsTable td.images{ width:8% !important; }
#fabricsTable th.name, #fabricsTable td.name{ width:11% !important; }
#fabricsTable th.color, #fabricsTable td.color{ width:11% !important; }
#fabricsTable th.antimicrobial, #fabricsTable td.antimicrobial{ width:6% !important; }
#fabricsTable th.railroaded, #fabricsTable td.railroaded{ width:6% !important; }
#fabricsTable th.price, #fabricsTable td.price{ width:10% !important; }
#fabricsTable th.cost, #fabricsTable td.cost{ width:10% !important; }
#fabricsTable th.products, #fabricsTable td.products{ width:14% !important; }
#fabricsTable td.products ul{ font-size:12px; }
#fabricsTable th.status, #fabricsTable td.status{ width:10% !important; }
#fabricsTable th.actions, #fabricsTable td.actions{ width:11% !important; }
</style>