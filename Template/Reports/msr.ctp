<!-- src/Template/Reports/msr.ctp -->

<div id="headingblock">
<h1 class="pageheading">Materials Shortage Report</h1>
<button type="button" id="newentry">+ New Entry</button>
<div style="clear:both;"></div>
</div>

<div id="tabs">
<ul>
	<li><a href="#openmsr">Open</a></li>
	<li><a href="#closedmsr">Closed</a></li>
	
</ul>

<div id="openmsr">

    <table width="100%" cellpadding="5" border="0" id="msrTable">
    <thead>
    <tr>
    <th><input type="checkbox" class="checkallbutton" /></th>
    <th>Vendor</th>
    <th>QTY</th>
    <th>Part#/Fabric</th>
    <th>Rev/Color</th>
    <th>SO#</th>
    <th>Order Status</th>
    <th>Order Date</th>
    <th>Ship Date</th>
    <th>ETA</th>
    <th>Shipment Status</th>
    <th>PO#</th>
    <th>Notes</th>
    <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    
    </tbody>
    </table>
</div>
<div id="closedmsr">
    <table width="100%" cellpadding="5" border="0" id="closedmsrTable">
    <thead>
    <tr>
    <th><input type="checkbox" class="checkallbutton" /></th>
    <th>Vendor</th>
    <th>QTY</th>
    <th>Part#/Fabric</th>
    <th>Rev/Color</th>
    <th>WO#</th>
    <th>Order Status</th>
    <th>Order Date</th>
    <th>Ship Date</th>
    <th>ETA</th>
    <th>Shipment Status</th>
    <th>PO#</th>
    <th>Notes</th>
    <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    
    </tbody>
    </table>
</div>
<script>
var msrTable;
var closedmsrTable;

$(function(){
        $('#openmsr input.checkallbutton').click(function(){
			if($(this).is(':checked')){
				$('#msrTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',true);
				$('input.checkallbutton').prop('checked',true);
			}else{
				$('#msrTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',false);
				$('input.checkallbutton').prop('checked',false);
			}
		});
		
		$('#closedmsr input.checkallbutton').click(function(){
			if($(this).is(':checked')){
				$('#closedmsrTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',true);
				$('input.checkallbutton').prop('checked',true);
			}else{
				$('#closedmsrTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',false);
				$('input.checkallbutton').prop('checked',false);
			}
		});
		
		
		msrTable=$('#msrTable').DataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/reports/getmsrlist/open.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			searchHighlight: true,
			"order": [[ 6, "asc" ]],
			"columns": [
			    {"className":'bulkedit','orderable':false},
				{"className":"vendor","orderable":true},
				{"className":'qty',"orderable": false},
				{"className":'partnumber',"orderable":true},
				{"className":'revision',"orderable":true},
				{"className":'workorder',"orderable":true},
				{"className":'orderstatus',"orderable":true},
				{"className":'orderdate',"orderable":true},
				{"className":'shipdate',"orderable":true},
				{"className":'eta',"orderable":false},
				{"className":'shipmentstatus',"orderable":true},
				{"className":'ponumber',"orderable":true},
				{"className":'notes',"orderable":false},
				{"className":'actions',"orderable": false}
			  ],
			'buttons': [{
					"text": 'Bulk Edit',
					"action": function ( e, dt, node, config ) {
						if($('#msrTable tbody input[type=checkbox]:checked').length > 1){
							var bulkids=$('#msrTable tbody input[type=checkbox]:checked').serialize();
							location.href='/reports/bulkeditmsr/'+encodeURIComponent(bulkids);
						}else{
							alert('You must select at least 2 MSR entries before using Bulk Edit.');
						}
					}
				}],
			"drawCallback": function( settings ) {
                $('#msrTable td.orderstatus').dblclick(function(){
                    //close any other open fields
                    $('#msrTable td.isediting').each(function(){
                        $(this).removeClass('isediting').html($(this).attr('data-originalvalue')).removeAttr('data-originalvalue');
                    });
                    
                    var thisid=$(this).parent().attr('id');
                    $(this).attr('data-originalvalue',$(this).html());
                    var newval='<select onchange="changeOrderStatus(this.value,\'order_status\',\''+thisid+'\')"><option value="Not Acknowledged"';
                    if($(this).html() == 'Not Acknowledged'){ newval += ' selected="selected"'; }
                    newval += '>Not Acknowledged</option><option value="Will Advise"';
                    if($(this).html() == 'Will Advise'){ newval += ' selected="selected"'; }
                    newval += '>Will Advise</option><option value="Backorder"';
                    if($(this).html() == 'Backorder'){ newval += ' selected="selected"'; }
                    newval += '>Backorder</option><option value="In Stock"';
                    if($(this).html() == 'In Stock'){ newval += ' selected="selected"'; }
                    newval += '>In Stock</option><option value="Blanket"';
                    if($(this).html() == 'Blanket'){ newval += ' selected="selected"'; }
                    newval += '>Blanket</option></select><div style="font-size:9px;"><a href="javascript:cancelchangefield(\'orderstatus\',\''+thisid+'\')">Cancel Change</a></div>';
		            $(this).html(newval).addClass('isediting');
		        });
		        
		        
		        $('#msrTable td.shipdate').dblclick(function(){
                    //close any other open fields
                    $('#msrTable td.isediting').each(function(){
                        $(this).removeClass('isediting').html($(this).attr('data-originalvalue')).removeAttr('data-originalvalue');
                    });
                    
                    var thisid=$(this).parent().attr('id');
                    $(this).attr('data-originalvalue',$(this).html());
                    var newval='<input type="date" onchange="changeOrderStatus(this.value,\'estimated_ship_date\',\''+thisid+'\')" value="';
                    
                    var oldDateSplit=$(this).html().split('/');
                    var oldMonth=oldDateSplit[0];
                    var oldDay=oldDateSplit[1];
                    var oldYear=oldDateSplit[2];
                    
                    var newYear='20'+oldYear;
                    if(parseInt(oldMonth) < 10){
                        var newMonth='0'+oldMonth;
                    }else{
                        var newMonth=oldMonth;
                    }
                    if(parseInt(oldDay) < 10){
                        var newDay='0'+oldDay;
                    }else{
                        var newDay=oldDay;
                    }
                    
                    newval += newYear+'-'+newMonth+'-'+newDay;
                    newval += '" /><div style="font-size:9px;"><a href="javascript:cancelchangefield(\'shipdate\',\''+thisid+'\')">Cancel Change</a></div>';
		            $(this).html(newval).addClass('isediting');
		        });
		        
		        
		        $('#msrTable td.eta').dblclick(function(){
                    //close any other open fields
                    $('#msrTable td.isediting').each(function(){
                        $(this).removeClass('isediting').html($(this).attr('data-originalvalue')).removeAttr('data-originalvalue');
                    });
                    
                    var thisid=$(this).parent().attr('id');
                    $(this).attr('data-originalvalue',$(this).html());
                    var newval='<input type="date" onchange="changeOrderStatus(this.value,\'eta\',\''+thisid+'\')" value="';
                    
                    var oldDateSplit=$(this).html().split('/');
                    var oldMonth=oldDateSplit[0];
                    var oldDay=oldDateSplit[1];
                    var oldYear=oldDateSplit[2];
                    
                    var newYear='20'+oldYear;
                    if(parseInt(oldMonth) < 10){
                        var newMonth='0'+oldMonth;
                    }else{
                        var newMonth=oldMonth;
                    }
                    if(parseInt(oldDay) < 10){
                        var newDay='0'+oldDay;
                    }else{
                        var newDay=oldDay;
                    }
                    
                    newval += newYear+'-'+newMonth+'-'+newDay;
                    newval += '" /><div style="font-size:9px;"><a href="javascript:cancelchangefield(\'eta\',\''+thisid+'\')">Cancel Change</a></div>';
		            $(this).html(newval).addClass('isediting');
		        });
		        
		        
		        $('#msrTable td.shipmentstatus').dblclick(function(){
                    //close any other open fields
                    $('#msrTable td.isediting').each(function(){
                        $(this).removeClass('isediting').html($(this).attr('data-originalvalue')).removeAttr('data-originalvalue');
                    });
                    
                    var thisid=$(this).parent().attr('id');
                    $(this).attr('data-originalvalue',$(this).html());
                    //'TBD','Hold','In Transit','Delivered','Cancelled'
                    var newval='<select onchange="changeOrderStatus(this.value,\'shipment_status\',\''+thisid+'\')"><option value="TBD"';
                    if($(this).html() == 'TBD'){ newval += ' selected="selected"'; }
                    newval += '>TBD</option><option value="Hold"';
                    if($(this).html() == 'Hold'){ newval += ' selected="selected"'; }
                    newval += '>Hold</option><option value="In Transit"';
                    if($(this).html() == 'In Transit'){ newval += ' selected="selected"'; }
                    newval += '>In Transit</option><option value="Delivered"';
                    if($(this).html() == 'Delivered'){ newval += ' selected="selected"'; }
                    newval += '>Delivered</option><option value="Cancelled"';
                    if($(this).html() == 'Cancelled'){ newval += ' selected="selected"'; }
                    newval += '>Cancelled</option></select><div style="font-size:9px;"><a href="javascript:cancelchangefield(\'shipmentstatus\',\''+thisid+'\')">Cancel Change</a></div>';
		            $(this).html(newval).addClass('isediting');
		        });
		        
		        
		        
		        $('#msrTable td.notes').dblclick(function(){
                    //close any other open fields
                    $('#msrTable td.isediting').each(function(){
                        $(this).removeClass('isediting').html($(this).attr('data-originalvalue')).removeAttr('data-originalvalue');
                    });
                    
                    var thisid=$(this).parent().attr('id');
                    $(this).attr('data-originalvalue',$(this).html());
                    var newval='<input type="text" value="'+$(this).html()+'" /><div style="font-size:9px;"><a href="javascript:closenotesfield(\''+thisid+'\')">Done</a></div>';
		            $(this).html(newval).addClass('isediting');
		            
		            $('tr#'+thisid+' td.notes input[type=text]').keyup($.debounce( 650, function(){
                		
                		$.ajax({
                            url: '/reports/msr/updatefield/'+thisid+'/notes/'+escapeTextFieldforURL($('tr#'+thisid+' td.notes input[type=text]').val())+'/nolog',
                            method:'GET'
                        });
                		
                		
                	}));
		            
		        });
		        
		        
		        
            }
		});
		
		
		
		
		
		
		
		closedmsrTable=$('#closedmsrTable').DataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/reports/getmsrlist/closed.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			searchHighlight: true,
			"order": [[ 6, "asc" ]],
			"columns": [
			    {"className":'bulkedit','orderable':false},
				{"className":"vendor","orderable":true},
				{"className":'qty',"orderable": false},
				{"className":'partnumber',"orderable":true},
				{"className":'revision',"orderable":true},
				{"className":'workorder',"orderable":true},
				{"className":'orderstatus',"orderable":true},
				{"className":'orderdate',"orderable":true},
				{"className":'shipdate',"orderable":false},
				{"className":'eta',"orderable":false},
				{"className":'shipmentstatus',"orderable":true},
				{"className":'ponumber',"orderable":true},
				{"className":'notes',"orderable":false},
				{"className":'actions',"orderable": false}
			  ],
			'buttons': [{
					"text": 'Bulk Edit',
					"action": function ( e, dt, node, config ) {
						if($('#closedmsrTable tbody input[type=checkbox]:checked').length > 1){
							var bulkids=$('#closedmsrTable tbody input[type=checkbox]:checked').serialize();
							location.href='/reports/bulkeditmsr/'+encodeURIComponent(bulkids);
						}else{
							alert('You must select at least 2 fabrics before using Bulk Edit.');
						}
					}
				}],
			"drawCallback": function( settings ) {
                $('#closedmsrTable td.orderstatus').dblclick(function(){
                    //close any other open fields
                    $('#closedmsrTable td.isediting').each(function(){
                        $(this).removeClass('isediting').html($(this).attr('data-originalvalue')).removeAttr('data-originalvalue');
                    });
                    
                    var thisid=$(this).parent().attr('id');
                    $(this).attr('data-originalvalue',$(this).html());
                    var newval='<select onchange="changeClosedOrderStatus(this.value,\'order_status\',\''+thisid+'\')"><option value="Not Acknowledged"';
                    if($(this).html() == 'Not Acknowledged'){ newval += ' selected="selected"'; }
                    newval += '>Not Acknowledged</option><option value="Will Advise"';
                    if($(this).html() == 'Will Advise'){ newval += ' selected="selected"'; }
                    newval += '>Will Advise</option><option value="Backorder"';
                    if($(this).html() == 'Backorder'){ newval += ' selected="selected"'; }
                    newval += '>Backorder</option><option value="In Stock"';
                    if($(this).html() == 'In Stock'){ newval += ' selected="selected"'; }
                    newval += '>In Stock</option><option value="Blanket"';
                    if($(this).html() == 'Blanket'){ newval += ' selected="selected"'; }
                    newval += '>Blanket</option></select><div style="font-size:9px;"><a href="javascript:cancelclosedchangefield(\'orderstatus\',\''+thisid+'\')">Cancel Change</a></div>';
		            $(this).html(newval).addClass('isediting');
		        });
		        
		        
		        $('#closedmsrTable td.shipdate').dblclick(function(){
                    //close any other open fields
                    $('#closedmsrTable td.isediting').each(function(){
                        $(this).removeClass('isediting').html($(this).attr('data-originalvalue')).removeAttr('data-originalvalue');
                    });
                    
                    var thisid=$(this).parent().attr('id');
                    $(this).attr('data-originalvalue',$(this).html());
                    var newval='<input type="date" onchange="changeClosedOrderStatus(this.value,\'estimated_ship_date\',\''+thisid+'\')" value="';
                    
                    var oldDateSplit=$(this).html().split('/');
                    var oldMonth=oldDateSplit[0];
                    var oldDay=oldDateSplit[1];
                    var oldYear=oldDateSplit[2];
                    
                    var newYear='20'+oldYear;
                    if(parseInt(oldMonth) < 10){
                        var newMonth='0'+oldMonth;
                    }else{
                        var newMonth=oldMonth;
                    }
                    if(parseInt(oldDay) < 10){
                        var newDay='0'+oldDay;
                    }else{
                        var newDay=oldDay;
                    }
                    
                    newval += newYear+'-'+newMonth+'-'+newDay;
                    newval += '" /><div style="font-size:9px;"><a href="javascript:cancelclosedchangefield(\'shipdate\',\''+thisid+'\')">Cancel Change</a></div>';
		            $(this).html(newval).addClass('isediting');
		        });
		        
		        
		        $('#closedmsrTable td.eta').dblclick(function(){
                    //close any other open fields
                    $('#closedmsrTable td.isediting').each(function(){
                        $(this).removeClass('isediting').html($(this).attr('data-originalvalue')).removeAttr('data-originalvalue');
                    });
                    
                    var thisid=$(this).parent().attr('id');
                    $(this).attr('data-originalvalue',$(this).html());
                    var newval='<input type="date" onchange="changeClosedOrderStatus(this.value,\'eta\',\''+thisid+'\')" value="';
                    
                    var oldDateSplit=$(this).html().split('/');
                    var oldMonth=oldDateSplit[0];
                    var oldDay=oldDateSplit[1];
                    var oldYear=oldDateSplit[2];
                    
                    var newYear='20'+oldYear;
                    if(parseInt(oldMonth) < 10){
                        var newMonth='0'+oldMonth;
                    }else{
                        var newMonth=oldMonth;
                    }
                    if(parseInt(oldDay) < 10){
                        var newDay='0'+oldDay;
                    }else{
                        var newDay=oldDay;
                    }
                    
                    newval += newYear+'-'+newMonth+'-'+newDay;
                    newval += '" /><div style="font-size:9px;"><a href="javascript:cancelclosedchangefield(\'eta\',\''+thisid+'\')">Cancel Change</a></div>';
		            $(this).html(newval).addClass('isediting');
		        });
		        
		        
		        $('#closedmsrTable td.shipmentstatus').dblclick(function(){
                    //close any other open fields
                    $('#closedmsrTable td.isediting').each(function(){
                        $(this).removeClass('isediting').html($(this).attr('data-originalvalue')).removeAttr('data-originalvalue');
                    });
                    
                    var thisid=$(this).parent().attr('id');
                    $(this).attr('data-originalvalue',$(this).html());
                    //'TBD','Hold','In Transit','Delivered','Cancelled'
                    var newval='<select onchange="changeClosedOrderStatus(this.value,\'shipment_status\',\''+thisid+'\')"><option value="TBD"';
                    if($(this).html() == 'TBD'){ newval += ' selected="selected"'; }
                    newval += '>TBD</option><option value="Hold"';
                    if($(this).html() == 'Hold'){ newval += ' selected="selected"'; }
                    newval += '>Hold</option><option value="In Transit"';
                    if($(this).html() == 'In Transit'){ newval += ' selected="selected"'; }
                    newval += '>In Transit</option><option value="Delivered"';
                    if($(this).html() == 'Delivered'){ newval += ' selected="selected"'; }
                    newval += '>Delivered</option><option value="Cancelled"';
                    if($(this).html() == 'Cancelled'){ newval += ' selected="selected"'; }
                    newval += '>Cancelled</option></select><div style="font-size:9px;"><a href="javascript:cancelclosedchangefield(\'shipmentstatus\',\''+thisid+'\')">Cancel Change</a></div>';
		            $(this).html(newval).addClass('isediting');
		        });
		        
		        
		        
		        $('#closedmsrTable td.notes').dblclick(function(){
                    //close any other open fields
                    $('#closedmsrTable td.isediting').each(function(){
                        $(this).removeClass('isediting').html($(this).attr('data-originalvalue')).removeAttr('data-originalvalue');
                    });
                    
                    var thisid=$(this).parent().attr('id');
                    $(this).attr('data-originalvalue',$(this).html());
                    var newval='<input type="text" value="'+$(this).html()+'" /><div style="font-size:9px;"><a href="javascript:closeclosednotesfield(\''+thisid+'\')">Done</a></div>';
		            $(this).html(newval).addClass('isediting');
		            
		            $('#closedmsrTable tr#'+thisid+' td.notes input[type=text]').keyup($.debounce( 650, function(){
                		
                		$.ajax({
                            url: '/reports/msr/updatefield/'+thisid+'/notes/'+escapeTextFieldforURL($('#closedmsrTable tr#'+thisid+' td.notes input[type=text]').val())+'/nolog',
                            method:'GET'
                        });
                		
                		
                	}));
		            
		        });
		        
		        
		        
            }
		});
		
		
		
		
		
	
		
		$('#newentry').click(function(){
			location.href='/reports/msr/add/';
		});
		
	
	
	    if(window.location.hash == '#openmsr' || window.location.hash == ''){
    	 	msrTable.ajax.reload();
    		var activeTab=0;
    	}else if(window.location.hash == '#closedmsr'){
    		var activeTab=1;
    	}
    	
    	$('#tabs').tabs({
    		active:activeTab,
    		activate:function(event,ui){
    			if(ui.newPanel.attr('id') == "closedmsr"){
    			   closedmsrTable.ajax.reload();
    			}else if(ui.newPanel.attr('id') == "openmsr"){
    			   msrTable.ajax.reload();
    			}
    			window.location.hash = '#'+ui.newPanel.attr('id');
            }
    	});
    	
    	$('#tabs > li > a').click(function(e){
          e.preventDefault();
    	});	
});

function closenotesfield(rowid){
    changeOrderStatus($('#msrTable tr#'+rowid+' td.notes input[type=text]').val(),'notes',rowid);
}

function cancelchangefield(fieldcol,rowid){
    $('#msrTable tr#'+rowid+' td.'+fieldcol).removeClass('isediting').html($('#msrTable tr#'+rowid+' td.'+fieldcol).attr('data-originalvalue')).removeAttr('data-originalvalue');
}

function changeOrderStatus(newval,field,rowid){
    $.ajax({
        url: '/reports/msr/updatefield/'+rowid+'/'+field+'/'+escapeTextFieldforURL(newval),
        success:function(resultval){
            if(resultval=='SUCCESS'){
                msrTable.ajax.reload();
            }
        },
        method:'GET'
    });
}





function closeclosednotesfield(rowid){
    changeClosedOrderStatus($('#closedmsrTable tr#'+rowid+' td.notes input[type=text]').val(),'notes',rowid);
}

function cancelclosedchangefield(fieldcol,rowid){
    $('#closedmsrTable tr#'+rowid+' td.'+fieldcol).removeClass('isediting').html($('#closedmsrTable tr#'+rowid+' td.'+fieldcol).attr('data-originalvalue')).removeAttr('data-originalvalue');
}

function changeClosedOrderStatus(newval,field,rowid){
    $.ajax({
        url: '/reports/msr/updatefield/'+rowid+'/'+field+'/'+escapeTextFieldforURL(newval),
        success:function(resultval){
            if(resultval=='SUCCESS'){
                closedmsrTable.ajax.reload();
            }
        },
        method:'GET'
    });
}

function escapeTextFieldforURL(thetext){
    var output =thetext.replace(/\\/g, "__bbbbslash__");
	 output = output.replace(/\//g, "__aaabslash__");
	output = output.replace('?','__question__',output);
	output = output.replace(' ','__space__',output);
	output = output.replace('#','__pound__',output);
	output = output.replace('%','__percentage__',output);
	output = output.replace('&','__ampersand__',output);
	return output;
}

</script>
<style>
#tabs{ border:0 !important; clear:both;  }
#tabs > ul{ width:100%; height:36px; overflow:hidden; }
#tabs .ui-widget-header{ background:#26337A !important; border:0 !important; padding-left:30px !important; }


	
#tabs .ui-state-default,
#tabs .ui-widget-content .ui-state-default,
#tabs .ui-widget-header .ui-state-default,
#tabs .ui-button,
#tabs .ui-button.ui-state-disabled:hover,
#tabs .ui-button.ui-state-disabled:active{
	background:#363DBB !important; color:#CADEF3 !important;
}

#tabs .ui-state-default a,
#tabs .ui-state-default a:link,
#tabs .ui-state-default a:visited,
#tabs a.ui-button,
#tabs a:link.ui-button,
#tabs a:visited.ui-button,
#tabs .ui-button{
	color:#CADEF3 !important;
}
	
	
	
#tabs .ui-state-active,
#tabs .ui-widget-content .ui-state-active, 
#tabs .ui-widget-header .ui-state-active,
#tabs a.ui-button:active,
#tabs .ui-button:active,
#tabs .ui-button.ui-state-active:hover{
	background:#FFF !important; color:#18237C !important; border:1px solid #FFF !important;
}

#tabs .ui-state-active a,
#tabs .ui-state-active a:link,
#tabs .ui-state-active a:visited{
	color:#18237C !important;
}


div.row{ max-width:100% !important; width:100% !important; }

.greenrow{ background:#00661A !important; }
.greenrow td{ color:#FFF !important; }

.orangerow{ background:#FFBF00 !important; }
.yellowrow{ background:#FFFF73 !important; }
.grayrow{ background:#555555 !important; }
.grayrow td{ color:#FFF !important; }

td.isediting a{ color:blue; }

.greenrow td.isediting a{ color:yellow !important; }
.grayrow td.isediting a{ color:yellow !important; }

td.isediting{ padding:0 0 0 0 !important; }
td.orderstatus select{ padding:4px 2px 4px 2px !important; width:97% !important; margin-bottom:0 !important; height:auto !important; }

td.shipmentstatus select{ padding:4px 2px 4px 2px !important; width:97% !important; margin-bottom:0 !important; height:auto !important; }

td.isediting input{ padding:4px 2px 4px 2px !important; width:97% !important; margin-bottom:0 !important; height:auto !important; }

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
#headingblock{ padding:20px 30px; }
#headingblock h1{ float:left; }
#headingblock #newentry{ float:right; }

input.checkallbutton{ margin:0 0 0 0 !important; }

.dt-buttons a.dt-button{ padding:5px !important; font-size:12px !important; margin:0 10px !important; }

table.dataTable thead th.vendor,
table.dataTable tbody td.vendor{ width:11% !important; }

table.dataTable thead th.qty,
table.dataTable tbody td.qty{ width:6% !important; }

table.dataTable thead th.partnumber,
table.dataTable tbody td.partnumber{ width:8% !important; }

table.dataTable thead th.revision,
table.dataTable tbody td.revision{ width:8% !important; }

table.dataTable thead th.workorder,
table.dataTable tbody td.workorder{ width:4% !important; }

table.dataTable thead th.orderstatus,
table.dataTable tbody td.orderstatus{ width:6% !important; }

table.dataTable thead th.orderdate,
table.dataTable tbody td.orderdate{ width:5% !important; }

table.dataTable thead th.shipdate,
table.dataTable tbody td.shipdate{ width:5% !important; }

table.dataTable thead th.eta,
table.dataTable tbody td.eta{ width:5% !important; }

table.dataTable thead th.shipmentstatus,
table.dataTable tbody td.shipmentstatus{ width:6% !important; }

table.dataTable thead th.ponumber,
table.dataTable tbody td.ponumber{ width:4% !important; }

table.dataTable thead th.notes,
table.dataTable tbody td.notes{ width:25% !important; }

table.dataTable thead th.actions,
table.dataTable tbody td.actions{ width:7% !important; }
</style>