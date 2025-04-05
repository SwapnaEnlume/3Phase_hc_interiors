<style>
@media only print{
	@page {size: landscape}

	body {
  		-webkit-print-color-adjust: exact !important;
	}
	
	table thead tr th:nth-of-type(1), 
	table tbody tr td:nth-of-type(1),
	table thead tr th:nth-of-type(11),
	table tbody tr td:nth-of-type(11){ display:none !important; }
	
	table thead tr th:nth-of-type(2),
	table tbody tr td:nth-of-type(2){ width:8% !important; }
	
	table thead tr th:nth-of-type(3),
	table tbody tr td:nth-of-type(3){ width:8% !important;  }
	
	table thead tr th:nth-of-type(4),
	table tbody tr td:nth-of-type(4){ width:8% !important;  }
	
	table thead tr th:nth-of-type(5),
	table tbody tr td:nth-of-type(5){ width:11% !important;  }
	
	table thead tr th:nth-of-type(6),
	table tbody tr td:nth-of-type(6){ width:8% !important;  }
	
	table thead tr th:nth-of-type(7),
	table tbody tr td:nth-of-type(7){ width:33% !important;  }
    
	table thead tr th:nth-of-type(8),
	table tbody tr td:nth-of-type(8){ width:8% !important;  }
	
	table thead tr th:nth-of-type(9),
	table tbody tr td:nth-of-type(9){ width:8% !important;  }
	
	table thead tr th:nth-of-type(10),
	table tbody tr td:nth-of-type(10){ width:8% !important;  }
}

body.dt-print-view table thead tr th:nth-of-type(1), 
body.dt-print-view table tbody tr td:nth-of-type(1),
body.dt-print-view table thead tr th:nth-of-type(11),
body.dt-print-view table tbody tr td:nth-of-type(11){ display:none !important; }

body.dt-print-view table thead tr th:nth-of-type(2),
body.dt-print-view table tbody tr td:nth-of-type(2){ width:8% !important; }

body.dt-print-view table thead tr th:nth-of-type(3),
body.dt-print-view table tbody tr td:nth-of-type(3){ width:8% !important;  }

body.dt-print-view table thead tr th:nth-of-type(4),
body.dt-print-view table tbody tr td:nth-of-type(4){ width:8% !important;  }

body.dt-print-view table thead tr th:nth-of-type(5),
body.dt-print-view table tbody tr td:nth-of-type(5){ width:11% !important;  }

body.dt-print-view table thead tr th:nth-of-type(6),
body.dt-print-view table tbody tr td:nth-of-type(6){ width:8% !important;  }

body.dt-print-view table thead tr th:nth-of-type(7),
body.dt-print-view table tbody tr td:nth-of-type(7){ width:33% !important;  }

body.dt-print-view table thead tr th:nth-of-type(8),
body.dt-print-view table tbody tr td:nth-of-type(8){ width:8% !important;  }

body.dt-print-view table thead tr th:nth-of-type(9),
body.dt-print-view table tbody tr td:nth-of-type(9){ width:8% !important;  }

body.dt-print-view table thead tr th:nth-of-type(10),
body.dt-print-view table tbody tr td:nth-of-type(10){ width:8% !important;  }
	

div.row{ max-width:100% !important; }

#boxesTable th.bulkedit,#boxesTable td.bulkedit{ width:2% !important; text-align:center; }
#boxesTable th.boxnumber,#boxesTable td.boxnumber{ width:5% !important; text-align:center; }
#boxesTable th.itemcount,#boxesTable td.itemcount{ width:5% !important; text-align:center; }
#boxesTable th.dimensions,#boxesTable td.dimensions{ width:8% !important; text-align:center; }
#boxesTable th.weight,#boxesTable td.weight{ width:7% !important; text-align:center; }
#boxesTable th.status,#boxesTable td.status{ width:6% !important; text-align:center; }
#boxesTable th.description,#boxesTable td.description{ width:29% !important; }
#boxesTable th.location,#boxesTable td.location{ width:8% !important; text-align:center; }
#boxesTable th.user,#boxesTable td.user{ width:8% !important; text-align:center; }
#boxesTable th.datetime,#boxesTable td.datetime{ width:9% !important; text-align:center; }
#boxesTable th.actions,#boxesTable td.actions{ width:13% !important; }

table#boxesTable thead tr th{ text-align:center; }
</style>

<div id="headingblock">
<h1 class="pageheading">Box Inventory</h1>
<!--<button type="button" id="newfabric">+ Add New Fabric</button>-->
<div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" border="0" id="boxesTable">
<thead>
<tr>
<th class="bulkedit"><input type="checkbox" class="checkallbutton"></th>
<th class="boxnumber">Box #</th>
<th class="itemcount">Item Count</th>
<th class="weight">Weight</th>
<th class="dimensions">Dimensions</th>
<th class="status">Status</th>
<th class="description">Content Description</th>
<th class="location">Location</th>
<th class="user">User</th>
<th class="datetime">Date/Time</th>
<th class="actions">Actions</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
<script>
var boxesDataTable;

$(function(){
		boxesDataTable=$('#boxesTable').DataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/orders/getboxeslist/1.json"},
			dom:'<"top"iBfrlp<"clear"><"shippedstatus">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":"bulkedit","orderable":false},
				{"className":'boxnumber',"orderable": true},
				{"className":'itemcount','orderable':false},
				{"className":'weight',"orderable": false},
				{"className":'dimensions',"orderable": false},
				{"className":'status',"orderable": false},
				{"className":'description','orderable':false},
				{"className":'location',"orderable": true},
				{"className":'user',"orderable": false},
				{"className":'datetime',"orderable": false},
				{"className":'actions',"orderable": false}
			  ],
			"buttons": [
			    {
			        "text": 'Excel',
			        "action": function(e,dt,node,config){
			            
			            if(dt.search() == ''){
			                var searchval='none';
			            }else{
			                var searchval=dt.search();
			            }
			            
			            if($('#shippedstatusvalue').is(':checked')){
			                var shippedstatusvalue='yes';
			            }else{
			                var shippedstatusvalue='no';
			            }
			         
			            var sortCol=dt.order()[0][0];
			            var sortDir=dt.order()[0][1];
			            
			            location.href='/orders/boxinventoryxls/'+searchval+'/'+dt.page.info().start+'/'+dt.page.info().length+'/'+shippedstatusvalue+'/'+sortCol+'/'+sortDir;
			        }
			    },
        		'print',
        		{
					"text": 'Bulk Transfer',
					"action": function ( e, dt, node, config ) {
						if($('#boxesTable tbody input[type=checkbox]:checked').length > 1){
							var bulkids=$('#boxesTable tbody input[type=checkbox]:checked').serialize();
							
							$.fancybox({
                                type:'iframe',
                                autoSize:false,
                                width:400,
                                height:500,
                                modal:true,
                                href: '/orders/bulktransferboxes/'+encodeURIComponent(bulkids)
                            });
							
						}else{
							alert('You must select at least 2 boxes before using Bulk Edit.');
						}
					}
				}
    		]/*,
			"drawCallback": function( settings ) {
        		$('#boxesTable tbody tr').click(function(event){
					var target = $( event.target );
					if(!target.is("input[type=checkbox]")){
						if($(this).find('input[type=checkbox]').is(':checked')){
							$(this).find('input[type=checkbox]').prop('checked',false);
						}else{
							$(this).find('input[type=checkbox]').prop('checked',true);
						}
					}
				});
    		}*/
		});
		
		$('div.top div.shippedstatus').html('<label><input type="checkbox" id="shippedstatusvalue" value="nonshippedonly" checked="checked" onchange="toggleStatus()" /> Non Shipped Boxes Only</label>');
	
		$('input.checkallbutton').click(function(){
			if($(this).is(':checked')){
				$('#boxesTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',true);
				$('input.checkallbutton').prop('checked',true);
			}else{
				$('#boxesTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',false);
				$('input.checkallbutton').prop('checked',false);
			}
		});
		
		/*$('#newfabric').click(function(){
			location.href='/products/fabrics/add/';
		});*/
	
		
});

function doTransferBox(boxID){
    $.fancybox({
        type:'iframe',
        autoSize:false,
        width:400,
        height:500,
        modal:true,
        href: '/orders/transferbox/'+boxID
    });
}

function toggleStatus(){
    if($('input#shippedstatusvalue').is(':checked')){
        boxesDataTable.ajax.url('/orders/getboxeslist/1.json').load();
    }else{
        boxesDataTable.ajax.url('/orders/getboxeslist/0.json').load();
    }
}
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

</style>