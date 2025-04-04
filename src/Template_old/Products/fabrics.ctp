<!-- src/Template/Products/fabrics.ctp -->

<style>
table.dataTable{ width:1700px !important; }

table.dataTable th.flammability,table.dataTable td.flammability{ width:120px !important; }

table.dataTable th.cost,table.dataTable td.cost{ width:120px !important; }


table.dataTable th.name,table.dataTable td.name{ width:150px !important; }
table.dataTable th.color,table.dataTable td.color{ width:150px !important; }

table.dataTable tbody tr td.name{ position:relative; }
table.dataTable tbody tr td.name div.fabricdescription{ display:none; position:absolute; top:15px; left:40%; z-index:555; width:400px; height:auto; background:#FFF; border:1px solid #111; padding:5px; font-size:12px; color:#111; }
table.dataTable tbody tr td.name:hover div.fabricdescription{ display:block; }
table.dataTable thead tr th.name,table.dataTable thead tr th.color{ font-size:11px; }

table.dataTable tbody td.products ul{ font-size:11px; margin:0 0 0 15px; }
</style>

<div id="headingblock">
<h1 class="pageheading">Fabrics</h1>
<button type="button" id="newfabric">+ Add New Fabric</button>
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="fabricsTable">
<thead>
<tr>
<th><input type="checkbox" class="checkallbutton"></th>
<th>Image</th>
<th>ID</th>
<th>HCI Fabric Name<br><em>(Vendor Fabric Name)</em></th>
<th>HCI Color<br><em>(Vendor Color)</em></th>
<th>FR</th>
<th>Width</th>
<th>RR</th>
<th>AM</th>
<th>Vendor</th>
<th>Cost</th>
<th>Products</th>
<th>Status</th>
<th>HCI Collections</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
<script>
$(function(){
		$('#fabricsTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/products/getfabricslist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			searchHighlight: true,
			"order": [[ 3, "asc" ]],
			"columns": [
				{"className":"bulkedit","orderable":false},
				{"className":'images',"orderable": false},
				{"className":'idnumber','orderable':true},
				{"className":'name',"orderable": true},
				{"className":'color',"orderable": true},
				{"className":'flammability',"orderable": false},
				{"className":'width','orderable':false},
				{"className":'railroaded',"orderable": false},
				{"className":'antimicrobial',"orderable": false},
				{"className":'vendor',"orderable": false},
				{"className":'cost',"orderable": false},
				{"className":'products',"orderable": false},
				{"className":'status',"orderable": false},
				{"className":"collection","orderable":false},
				{"className":'actions',"orderable": false}
			  ],
			"buttons": [
        		{
					"text": 'Bulk Edit',
					"action": function ( e, dt, node, config ) {
						if($('#fabricsTable tbody input[type=checkbox]:checked').length > 1){
							var bulkids=$('#fabricsTable tbody input[type=checkbox]:checked').serialize();
							location.href='/products/bulkeditfabric/'+encodeURIComponent(bulkids);
						}else{
							alert('You must select at least 2 fabrics before using Bulk Edit.');
						}
					}
				}
    		],
			"drawCallback": function( settings ) {
        		$('#fabricsTable tbody tr').click(function(event){
					var target = $( event.target );
					if(!target.is("input[type=checkbox]")){
						if($(this).find('input[type=checkbox]').is(':checked')){
							$(this).find('input[type=checkbox]').prop('checked',false);
						}else{
							$(this).find('input[type=checkbox]').prop('checked',true);
						}
					}
				});
    		}
		});
	
		$('input.checkallbutton').click(function(){
			if($(this).is(':checked')){
				$('#fabricsTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',true);
				$('input.checkallbutton').prop('checked',true);
			}else{
				$('#fabricsTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',false);
				$('input.checkallbutton').prop('checked',false);
			}
		});
		
		$('#newfabric').click(function(){
			location.href='/products/fabrics/add/';
		});
	
		
});
</script>
<style>
@media print {
  a[href]:after {
    content: none !important;
  }

  #fabricsTable_wrapper{ width:100% !important; }
}

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

@media(screen){
	#fabricsTable_wrapper{ width:1900px !important; }
}

</style>