<!-- src/Template/Products/fabrics.ctp -->

<style>
/*table.dataTable{ width:1700px !important; }
*/
table.dataTable th.flammability,table.dataTable td.flammability{ width:70px !important; }

table.dataTable th.cost,table.dataTable td.cost{ width:60px !important; }

.dataTable thead tr th.actions, .dataTable tbody tr td.actions, .dataTable tfoot tr th.actions{width:50px !important; }

table.dataTable th.name,table.dataTable td.name{ width:120px !important; }
table.dataTable th.color,table.dataTable td.color{ width:90px !important; }

table.dataTable tbody tr td.name{ position:relative; }
table.dataTable tbody tr td.name div.fabricdescription{ display:none; position:absolute; top:15px; left:40%; z-index:555; width:400px; height:auto; background:#FFF; border:1px solid #111; padding:5px; font-size:12px; color:#111; }
table.dataTable tbody tr td.name:hover div.fabricdescription{ display:block; }
table.dataTable thead tr th.name,table.dataTable thead tr th.color{ font-size:11px; }

table.dataTable th.vendor,table.dataTable td.vendor{ width:45px !important; }

table.dataTable th.collection,table.dataTable td.collection{ width:50px !important; }
table.dataTable th.products,table.dataTable td.products{ width:58px !important; }
table.dataTable th.images,table.dataTable td.images{ width:65px !important; }

table.dataTable th.width,table.dataTable td.width{ width:40px !important; }
table.dataTable th.width,table.dataTable td.width{ width:40px !important; }
table.dataTable th.status,table.dataTable td.status{ width:20px !important; }







table.dataTable tbody td.products ul{ font-size:11px; margin:0 0 0 15px; }

.row:has(.noMargin) {
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}
.customRow{
     max-width: 80rem;
     margin: 0 auto;
     width: 90%;
}
.tableWidth{
    width: 97% !important;
    margin:auto !important;
}


</style>

<div id="headingblock" class="noMargin">
    <div class="customRow">
<h1 class="pageheading">Fabrics</h1>
<button type="button" id="newfabric">+ Add New Fabric</button>
<div style="clear:both;"></div>
</div>
</div>
<div class="tableWidth">
<table width="100%" cellpadding="5" border="0" id="fabricsTable"> 
<thead>
<tr>
<th width="3%"><input type="checkbox" class="checkallbutton"></th>
<th width="10%" style="width:50px !important; min-width:50px !important;">Actions</th>
<th width="6%">Image</th>
<!-- PPSASCRUM-358: start -->
<th width="4%">ID</th>
<!-- PPSASCRUM-358: end -->
<th width="6%">HCI Fabric Name<br><em>(Vendor Fabric Name)</em></th>
<th width="3%">HCI Color<br><em>(Vendor Color)</em></th>
<!-- PPSASCRUM-358: start -->
<th width="3%">FR</th>
<th width="4%">PFAS Status</th>
<th width="3%">Width</th>
<!-- PPSASCRUM-358: end -->
<th width="3%">RR</th>
<th width="3%">AM</th>
<th width="6%" >Vendor</th>
<th width="4%">Cost</th>
<th width="11%">Products</th>
<th width="6%">Status</th>
<th width="9%">HCI Collections</th>

</tr>
</thead>
<tbody>

</tbody>
</table>
</div>
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
				{"className":'actions',"orderable": false},
				{"className":'images',"orderable": false},
				{"className":'idnumber','orderable':true},
				{"className":'name',"orderable": true},
				{"className":'color',"orderable": true},
				{"className":'flammability',"orderable": false},
				/* PPSASCRUM-358: start */
				{"className":'pfas_status',"orderable": false},
				/* PPSASCRUM-358: end */
				{"className":'width','orderable':false},
				{"className":'railroaded',"orderable": false},
				{"className":'antimicrobial',"orderable": false},
				{"className":'vendor',"orderable": false},
				{"className":'cost',"orderable": false},
				{"className":'products',"orderable": false},
				{"className":'status',"orderable": false},
				{"className":"collection","orderable":false},
				
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
		
		$('#fabricsTable_wrapper').parent().parent().parent().parent().css('margin', '0');
            $('#fabricsTable_wrapper').parent().parent().parent().parent().css('max-width', '100%');
            $('#fabricsTable_wrapper').parent().parent().parent().parent().css('width', '100% ');


            $('#fabricsTable_wrapper').parent().parent().parent().css('margin', '0');
            $('#fabricsTable_wrapper').parent().parent().parent().css('max-width', '100%');
            $('#fabricsTable_wrapper').parent().parent().parent().css('width', '100% ');

            $('#fabricsTable_wrapper').parent().parent().css('margin', '0');
            $('#fabricsTable_wrapper').parent().parent().css('max-width', '100%');
            $('#fabricsTable_wrapper').parent().parent().css('width', '100% ');
	
		
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