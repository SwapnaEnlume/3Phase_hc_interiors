<!-- src/Template/Products/window_treatments.ctp -->
<div id="headingblock">
<h1 class="pageheading">Product Sub-Classes</h1>
<button type="button" id="newpc">+ Add New</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="pcsTable">
<thead>
<tr>
<th>Sub-Class Name</th>
<th>Class</th>
<th>Status</th>
<th>Tally Enabled?</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
<script>
$(function(){
		$('#pcsTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/getproductsubclasses.json"},
			dom:'<"top"ifrlp<"clear">>rt',
			stateSave: true,
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'namecol',"orderable": true},
				{"className":'classnamecol',"orderable": true},
				{"className":'statuscol',"orderable": false},
				{"className":'tally','orderable':false},
				{"className":'actionscol',"orderable": false}
			  ]
		});
		
		$('#newpc').click(function(){
			location.href='/products/newsubclass/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newpc{ float:right; }
</style>