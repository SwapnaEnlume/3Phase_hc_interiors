<!-- src/Template/Admin/bluesheettemplates.ctp -->
<div id="headingblock">
<h1 class="pageheading">Blue Sheet Templates</h1>
<button type="button" id="newtemplate">+ Add New Template</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="bluesheetTable">
<thead>
<tr>
<th>Template</th>
<th>Status</th>
<th>Last Modified</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
<script>
$(function(){
		$('#bluesheetTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/admin/getbluesheettemplatelist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			searchHighlight: true,
			"order": [[ 0, "asc" ]],
			"columns": [
				{"className":'templatename',"orderable": false},
				{"className":"status","orderable":false},
				{"className":'modifiedtime',"orderable": false},
				{"className":'actions',"orderable": false}
			  ],
			buttons:[]
		});
	
		
		
		$('#newtemplate').click(function(){
			location.href='/admin/bluesheettemplates/add/';
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
#headingblock #newtemplate{ float:right; }

input.checkallbutton{ margin:0 0 0 0 !important; }

.dt-buttons a.dt-button{ padding:5px !important; font-size:12px !important; margin:0 10px !important; }

#bluesheetTable th.bulkedit,#bluesheetTable td.bulkedit{ width:3% !important; }
#bluesheetTable td.bulkedit input[type=checkbox]{ margin:0 0 0 0 !important; }
#bluesheetTable td.bulkedit{ text-align:center; }
#bluesheetTable th{ padding:10px 0 !important; text-align:center; }
#bluesheetTable th.images, #bluesheetTable td.images{ width:8% !important; }
#bluesheetTable th.name, #bluesheetTable td.name{ width:11% !important; }
#bluesheetTable th.color, #bluesheetTable td.color{ width:11% !important; }
#bluesheetTable th.antimicrobial, #bluesheetTable td.antimicrobial{ width:6% !important; }
#bluesheetTable th.railroaded, #bluesheetTable td.railroaded{ width:6% !important; }
#bluesheetTable th.price, #bluesheetTable td.price{ width:10% !important; }
#bluesheetTable th.cost, #bluesheetTable td.cost{ width:10% !important; }
#bluesheetTable th.products, #bluesheetTable td.products{ width:14% !important; }
#bluesheetTable td.products ul{ font-size:12px; }
#bluesheetTable th.status, #bluesheetTable td.status{ width:10% !important; }
#bluesheetTable th.actions, #bluesheetTable td.actions{ width:11% !important; }
</style>