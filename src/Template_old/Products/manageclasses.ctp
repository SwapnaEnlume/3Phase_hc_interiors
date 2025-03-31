<!-- src/Template/Products/window_treatments.ctp -->
<div id="headingblock">
<h1 class="pageheading">Product Classes</h1>
<button type="button" id="newpc">+ Add New</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="pcsTable">
<thead>
<tr>
<th>Class Name</th>
<th>Status</th>
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
			"ajax":{"url":"/products/getproductclasses.json"},
			dom:'<"top"ifrlp<"clear">>rt',
			stateSave: true,
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'namecol',"orderable": true},
				{"className":'statuscol',"orderable": false},
				{"className":'actionscol',"orderable": false}
			  ]
		});
		
		$('#newpc').click(function(){
			location.href='/products/newclass/';
		});
});
</script>
<style>
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newpc{ float:right; }
</style>