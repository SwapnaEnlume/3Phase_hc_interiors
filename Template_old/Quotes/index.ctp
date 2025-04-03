<!-- src/Template/Quotes/index.ctp -->
<style>
.dataTables_wrapper .dataTables_info{ max-width:230px; }
</style>
<div id="headingblock">
<h1 class="pageheading">Active Quotes</h1>
<button type="button" id="newquote">+ New Quote</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="quotesTable">
<thead>
<tr>
<th>Identifiers</th>
<th>Quote Number / Type</th>
<th>Status</th>
<th>Converted to WO#</th>
<th>Created</th>
<th>Modified</th>
<th># Items</th>
<th>Quote Total</th>
<th>Created By</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
<tfoot>
<tr>
<th>Identifiers</th>
<th>Quote Number</th>
<th>Status</th>
<th>Converted to WO#</th>
<th>Created</th>
<th>Modified</th>
<th># Items</th>
<th>Quote Total</th>
<th>Created By</th>
<th>Actions</th>
</tr>
</tfoot>
</table>
<script>
$(function(){
		$('#quotesTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/quotes/getquoteslist.json"},
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
				{"className":'company',"orderable": false},
				{"className":"quote_number","orderable":true},
				{"className":'status',"orderable": true},
				{"className":'wonumber','orderable':true},
				{"className":'created',"orderable": true},
				{"className":'modified',"orderable": true},
				{"className":'itemcount',"orderable":false},
				{"className":'total',"orderable": true},
				{"className":'user','orderable':false},
				{"className":'actions',"orderable": false}
			  ]
		});
		
		$('#newquote').click(function(){
			location.href='/quotes/add/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newquote{ float:right; }
#quotesTable th.company, #quotesTable td.company{ width:19% !important; }
#quotesTable th.status, #quotesTable td.status{ width:9% !important; }
#quotesTable th.wonumber,#quotesTable td.wonumber{ width:9% !important; }
#quotesTable th.created, #quotesTable td.created{ width:9% !important; }
#quotesTable th.modified, #quotesTable td.modified{ width:11% !important; }
#quotesTable th.itemcount, #quotesTable td.itemcount{ width:8% !important; }
#quotesTable th.total, #quotesTable td.total{ width:11% !important; }
#quotesTable th.user, #quotesTable td.user{ width:15% !important; }
#quotesTable th.actions, #quotesTable td.actions{ width:11% !important; }

#quotesTable tr td span.revisionlabel{ color:blue; }	
#quotesTable tr.expired{ 
	background: rgb(169,3,41) !important;
	background: -moz-linear-gradient(top, rgba(169,3,41,1) 0%, rgba(109,0,25,1) 100%) !important;
	background: -webkit-linear-gradient(top, rgba(169,3,41,1) 0%,rgba(109,0,25,1) 100%) !important;
	background: linear-gradient(to bottom, rgba(169,3,41,1) 0%,rgba(109,0,25,1) 100%) !important;
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a90329', endColorstr='#6d0019',GradientType=0 ) !important;
	color:#FFF !important; 
}
#quotesTable tr.expired td{ color:#FFF !important; }
#quotesTable tr.expired td span.revisionlabel{ color:#F1E680 !important; }
	
#quotesTable tbody tr td{ border-bottom:1px solid #888 !important; }
</style>