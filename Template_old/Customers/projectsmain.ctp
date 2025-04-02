<!-- src/Template/Customers/projectsmain.ctp -->
<div id="headingblock">
<h1 class="pageheading">Projects</h1>
<button type="button" id="newquote">+ New Project</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="projectsTable">
<thead>
<tr>
<th rowspan="2">Project Name</th>
<th rowspan="2">Customer</th>
<th rowspan="2">PM</th>
<th rowspan="2">Status</th>
<th colspan="2" class="quotetwocol">Quotes</th>
<th colspan="2" class="ordertwocol">Orders</th>
<th rowspan="2">Actions</th>
</tr>
<tr>
<th>Count</th>
<th>Dollars</th>
<th>Count</th>
<th>Dollars</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
<script>
$(function(){
		$('#projectsTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/customers/getprojectslist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: false,
			searchHighlight: true,
			buttons:[],
			
			<?php
			if(isset($_GET['search'])){
			?>
			"oSearch": {"sSearch": "<?php echo $_GET['search']; ?>"},
			"order": [[ 4, "desc" ]],
			<?php }else{ ?>
			"order": [[ 1, "desc" ]],
			<?php } ?>
			"columns": [
				{"className":'projectname',"orderable": false},
				{"className":'customer',"orderable": false},
				{"className":'pm',"orderable": false},
				{"className":'status',"orderable": false},
				{"className":'quotescount',"orderable": false},
				{"className":'quotesdollar',"orderable": false},
				{"className":'orderscount',"orderable": false},
				{"className":'ordersdollar',"orderable": false},
				{"className":'actions',"orderable": false}
			  ]
		});
		
		$('#newquote').click(function(){
			location.href='/customers/projects/add/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newquote{ float:right; }

#projectsTable thead tr th{ text-align:center; }
#projectsTable th.projectname, #projectsTable td.projectname{ width:22% !important; }
#projectsTable th.customer, #projectsTable td.customer{ width:11% !important; }
#projectsTable th.pm, #projectsTable td.pm{ width:10% !important; }
#projectsTable th.status, #projectsTable td.status{ width:10% !important; }
#projectsTable th.quotescount, #projectsTable td.quotescount{ width:8% !important; }
#projectsTable th.quotesdollar, #projectsTable td.quotesdollar{ width:8% !important; }
#projectsTable th.orderscount, #projectsTable td.orderscount{ width:10% !important; }
#projectsTable th.ordersdollar, #projectsTable td.ordersdollar{ width:10% !important; }
#projectsTable th.actions, #projectsTable td.actions{ width:11% !important; }
#projectsTable thead th.quotetwocol{ border-bottom:1px solid #ccc; }
#projectsTable thead th.ordertwocol{ border-bottom:1px solid #ccc; }
</style>