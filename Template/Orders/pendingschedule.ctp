<!-- src/Template/Orders/schedule.ctp -->

<style>

#tabs{ border:0 !important; clear:both;  }
#tabs > ul{ width:100%; height:36px; overflow:hidden; }
#tabs .ui-widget-header{ background:#FF7D67 !important; border:0 !important; }

#daterangepicker{ text-align: right; }

#content div.row{ max-width:99% !important; width:96% !important; }
	
tr.shipnote td{ color:#440f70; }
tr.mfgnote td{ color:#d65308; }
tr.miscnote td{ color:#2777f7; }
	
#clear{ 
	background:#EEE !important; 
	border:1px solid #000; 
	border-radius:3px; 
	color:#000; 
	font-size:11px; 
	padding:2px 3px;
	margin-right: -55px;
    z-index: 55;
}

#daterangepicker div.month-wrapper{ border:0 !important; }
#daterangepicker div.date-picker-wrapper{ background:none !important; border:0 !important; }

table.summaryview tbody tr.entryrow{ display:none !important; }
table.summaryview tbody tr.batchnoterow{ display:none !important; }

	
table.month1 thead tr.week-name,
table.month2 thead tr.week-name{ background:#EEE; }
	
table#sherrytable{ width:2200px !important; }
	
table#sherrytable thead tr{ background:#004A87; }
	
table#sherrytable thead tr th{ color:#FFF; font-size:12px; text-align: center; padding:5px !important; }
table#sherrytable thead tr th.cubicles,
table#sherrytable thead tr th.trk,
table#sherrytable thead tr th.bedspreads,
table#sherrytable thead tr th.draperies,
table#sherrytable thead tr th.toptreatments,
table#sherrytable thead tr th.valances,
table#sherrytable thead tr th.cornices,
table#sherrytable thead tr th.wthw,
table#sherrytable thead tr th.blinds
{ border-bottom:1px solid #CCC; }
	
table#sherrytable tfoot tr td{ padding:5px !important; text-align: center; }
table#sherrytable tfoot tr td.totalslabel{ background: #004A87; color:#FFF; font-size:12px; text-align: center; }

table#sherrytable tbody tr.daterow{ background:#000; }
table#sherrytable tbody tr.daterow td{ color:#FFF; font-weight: bold; font-size:12px; text-align:left; padding:5px !important; }

table#sherrytable tbody tr.entryrow td{ padding:5px !important; font-size:12px; }
	
table#sherrytable tbody tr.notcompletednotshipped:nth-of-type(even){
	background:rgba(0,0,0,0.06);
}
table#sherrytable tbody tr.notcompletednotshipped:nth-of-type(odd){
	background:rgba(0,0,0,0.12);
}

	

table#sherrytable tbody tr.daterow td:nth-of-type(2),
table#sherrytable tbody tr.daterow td:nth-of-type(3),
table#sherrytable tbody tr.daterow td:nth-of-type(4),
table#sherrytable tbody tr.daterow td:nth-of-type(5),
table#sherrytable tbody tr.daterow td:nth-of-type(6),
table#sherrytable tbody tr.daterow td:nth-of-type(7),
table#sherrytable tbody tr.daterow td:nth-of-type(8),
table#sherrytable tbody tr.daterow td:nth-of-type(9),
table#sherrytable tbody tr.daterow td:nth-of-type(10),
table#sherrytable tbody tr.daterow td:nth-of-type(11),
table#sherrytable tbody tr.daterow td:nth-of-type(12),
table#sherrytable tbody tr.daterow td:nth-of-type(13),
table#sherrytable tbody tr.daterow td:nth-of-type(14),
table#sherrytable tbody tr.daterow td:nth-of-type(15),
table#sherrytable tbody tr.daterow td:nth-of-type(16),
table#sherrytable tbody tr.daterow td:nth-of-type(17),
table#sherrytable tbody tr.daterow td:nth-of-type(18),
table#sherrytable tbody tr.daterow td:nth-of-type(19),
table#sherrytable tbody tr.daterow td:nth-of-type(20),
table#sherrytable tbody tr.daterow td:nth-of-type(21),
table#sherrytable tbody tr.daterow td:nth-of-type(22),
table#sherrytable tbody tr.daterow td:nth-of-type(23),
table#sherrytable tbody tr.daterow td:nth-of-type(24)
{ text-align: center; }

	
table#sherrytable tbody tr.entryrow td:nth-of-type(1),
table#sherrytable tbody tr.entryrow td:nth-of-type(2),
table#sherrytable tbody tr.entryrow td:nth-of-type(8),
table#sherrytable tbody tr.entryrow td:nth-of-type(9),
table#sherrytable tbody tr.entryrow td:nth-of-type(10),
table#sherrytable tbody tr.entryrow td:nth-of-type(11),
table#sherrytable tbody tr.entryrow td:nth-of-type(12),
table#sherrytable tbody tr.entryrow td:nth-of-type(13),
table#sherrytable tbody tr.entryrow td:nth-of-type(14),
table#sherrytable tbody tr.entryrow td:nth-of-type(15),
table#sherrytable tbody tr.entryrow td:nth-of-type(16),
table#sherrytable tbody tr.entryrow td:nth-of-type(17),
table#sherrytable tbody tr.entryrow td:nth-of-type(18),
table#sherrytable tbody tr.entryrow td:nth-of-type(19),
table#sherrytable tbody tr.entryrow td:nth-of-type(20),
table#sherrytable tbody tr.entryrow td:nth-of-type(21),
table#sherrytable tbody tr.entryrow td:nth-of-type(22),
table#sherrytable tbody tr.entryrow td:nth-of-type(23),
table#sherrytable tbody tr.entryrow td:nth-of-type(24)
{ text-align: center; }
	
table#sherrytable tfoot, table#sherrytable tfoot tr{ background:transparent !important; }
table#sherrytable tfoot tr td:nth-of-type(1){ border-left:0 !important; background:transparent !important; }
	
table#sherrytable tbody tr.notcompletednotshipped:hover td{ background:rgba(0,0,115,0.22); }

table#sherrytable tbody tr.completednotshipped{ background:#216620 !important; }
table#sherrytable tbody tr.completednotshipped td{ color:#FFF !important; }
table#sherrytable tbody tr.completednotshipped td a{ color:#FFF !important; }


table#sherrytable tbody tr.batchnotecompletednotshipped{ background:#216620 !important; }

	
table#sherrytable tbody tr.completedandshipped{ background:#505050 !important; color:#FFF !important; }
table#sherrytable tbody tr.completedandshipped td{ color:#FFF !important; }
table#sherrytable tbody tr.completedandshipped td a{ color:#FFF !important; }



table#sherrytable tbody tr.batchnotecompletedandshipped{ background:#505050 !important; color:#FFF !important; }


	

#lastrefreshtext{ float:left; padding-top:0px; padding-left:10px; font-size:11px; color:#444; }
#lastrefreshtext span{ font-weight:bold; }
	
#reloadnow{ padding:4px !important; margin-left:15px !important; background:#CCC !important; color:#111 !important; }
	
	
.hovercol{ background:rgba(0,255,0,0.12) !important; }
	
.totalslabel.hovercol{ background:#254651 !important; }
tfoot .hovercol{ background:#CBD8E0 !important; }
	

	
#tabs .ui-state-default,
#tabs .ui-widget-content .ui-state-default,
#tabs .ui-widget-header .ui-state-default,
#tabs .ui-button,
#tabs .ui-button.ui-state-disabled:hover,
#tabs .ui-button.ui-state-disabled:active{
	background:#565656 !important; color:#fff !important;
}

#tabs .ui-state-default a,
#tabs .ui-state-default a:link,
#tabs .ui-state-default a:visited,
#tabs a.ui-button,
#tabs a:link.ui-button,
#tabs a:visited.ui-button,
#tabs .ui-button{
	color:#fff !important;
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

#sherrysearchform{ position:absolute; top:145px; left:1%; z-index:55; width:330px; height:50px; }
#sherrysearchquery{ width:185px; display:inline-block; }
#sherrysearchresultsbox{ display:none; width:430px; height:auto; background:#FFF; border:1px solid #CCC; position:absolute; top:36px; left:83px; z-index:555; }

#sherrysearchresultsbox table thead tr th{ font-size:14px; }
#sherrysearchresultsbox table tbody tr:nth-of-type(even){ background:rgba(0,0,0,0.02); }
#sherrysearchresultsbox table tbody tr:nth-of-type(odd){ background:rgba(0,0,0,0.07); }


table#sherrytable tr.pastduebatch.notcompletednotshipped{ background:#B20000 !important; }
table#sherrytable tr.pastduebatch.notcompletednotshipped td{ color:#FFF !important; }
table#sherrytable tr.pastduebatch.notcompletednotshipped td a{ color:#FFF !important; }


table#sherrytable tr.batchnotepastduebatch.batchnotenotcompletednotshipped{ background:#B20000 !important; }

table#sherrytable tr.batchnoterow td{ padding:5px !important; font-size:small !important; }
table#sherrytable tr.batchnoterow td.noterowlabel{ text-align:right; }
table#sherrytable tr.batchnoterow td.notecontent{ text-align:left; }


#viewtoggle{ position:absolute; top:120px; left:800px; z-index:77; width:200px; height:100px; }


#fullviewbutton{
	display:inline-block; padding:5px !important;
}

button.fullviewbutton.active{ background:green !important; }
button.fullviewbutton.inactive{ background:#444 !important; }

#summaryviewbutton{
	display:inline-block; padding:5px !important;
}

button.summaryviewbutton.active{ background:green !important; }
button.summaryviewbutton.inactive{ background:#444 !important; }


#printviewform{ position:absolute; top:65px; left:600px; z-index:55; width:180px; height:250px; }
#printviewform fieldset{ padding:0; margin:0; }
#printviewform fieldset legend{
	font-size: small;
    border: 0;
    line-height: 1.0;
    padding-bottom: 6px;
    padding-left: 0;
}
#printviewform p{ margin:0 0 0 0; line-height:1.0 !important; }
#printviewform p label{ line-height:1.4 !important; }
#printviewform input[type=checkbox]{ margin:0 0px 0 0 !important; }
#printviewform button{ padding:5px !important; font-size:small !important; margin-top:5px; }

#sherrytable th.donotprint,#sherrytable td.donotprint{ display:none !important; }

#sherrytable tbody tr.searchhighlighted td{
	border-top:3px solid orange;
	border-bottom:3px solid orange;
}

#sherrytable tbody tr.searchhighlighted td:nth-of-type(1){
	border-left:3px solid orange;
}

#sherrytable tbody tr.searchhighlighted td:nth-of-type(24){
	border-right:3px solid orange;
}

/*@media(min-width:1200px){*/
    #tabletpopoutbuttons{ display:none; }
/*}*/

/*@media(max-width:1199px){*/
    #tabletpopoutbuttons{ display:block; text-align:center; }
    #tabletpopoutbuttons button{ margin:0 10px 20px 10px; }
    #printviewform{ margin:0 auto; padding:15px; display:none; position:inherit !important; top:inherit !important; left:inherit !important; z-index:inherit !important;  }
    #printviewform fieldset legend{ background:transparent !important; }
    #sherrysearchform{ margin:0 auto; display:none; position:relative !important; top:inherit !important; left:inherit !important; z-index:inherit !important;  }
    #viewtoggle{ text-align:center !important; display:none; position:inherit !important; top:inherit !important; left:inherit !important; z-index:inherit !important; width:inherit !important; height:inherit !important; }
    #daterangepicker{ text-align:center !important; display:none; position:inherit !important; top:inherit !important; left:inherit !important; z-index:inherit !important; }
    #lastrefreshtext{ padding-top:0 !important; position:inherit !important; top:inherit !important; left:inherit !important; z-index:inherit !important;  }
    
    #daterangepicker table.month1{ margin-right:10px !important; }
    #daterangepicker table.month2{ margin-left:10px !important; }
/*}*/
</style>

<script>
function toggleWOLookupTablet(){
    if($('#sherrysearchform').is(':visible')){
        $('#sherrysearchform').hide();
        $('#daterangepicker').hide();
        $('#viewtoggle').hide();
        $('#printviewform').hide();
    }else{
        $('#sherrysearchform').show();
        $('#daterangepicker').hide();
        $('#viewtoggle').hide();
        $('#printviewform').hide();
    }
}

function toggleChangeDatesTablet(){
    if($('#daterangepicker').is(':visible')){
        $('#sherrysearchform').hide();
        $('#daterangepicker').hide();
        $('#viewtoggle').hide();
        $('#printviewform').hide();
    }else{
        $('#daterangepicker').show();
        $('#sherrysearchform').hide();
        $('#viewtoggle').hide();
        $('#printviewform').hide();
    }
}

function toggleViewOptionsTablet(){
    if($('#viewtoggle').is(':visible')){
        $('#sherrysearchform').hide();
        $('#daterangepicker').hide();
        $('#viewtoggle').hide();
        $('#printviewform').hide();
    }else{
        $('#viewtoggle').show();
        $('#sherrysearchform').hide();
        $('#daterangepicker').hide();
        $('#printviewform').hide();
    }
}

function togglePrintOptionsTablet(){
    if($('#printviewform').is(':visible')){
        $('#sherrysearchform').hide();
        $('#daterangepicker').hide();
        $('#viewtoggle').hide();
        $('#printviewform').hide();
    }else{
        $('#printviewform').show();
        $('#sherrysearchform').hide();
        $('#daterangepicker').hide();
        $('#viewtoggle').hide();
    }
}
</script>

<div id="tabs">
<ul>
	<li><a href="#sherryschedule">Sh.Sched: Pending Schedule</a></li>	
</ul>

<div id="sherryschedule">
	<div id="scriptsoutput"></div>
	
	<div id="tabletpopoutbuttons"><button onclick="toggleWOLookupTablet()" type="button">WO Lookup</button> <button onclick="toggleChangeDatesTablet()" type="button">Change Dates</button> <button onclick="toggleViewOptionsTablet()" type="button">View Options</button> <button onclick="togglePrintOptionsTablet()" type="button">Print Options</button></div>
	
	<div id="sherrysearchform"><label>WO Lookup: <input type="text" name="sherrysearchquery" id="sherrysearchquery" placeholder="WO Number" /></label><div id="sherrysearchresultsbox"></div></div>


	<div id="printviewform">
	<fieldset><legend>Print Setup:</legend>
	<p><label><input type="checkbox" id="printcubicles" checked="checked">Cubicle Curtains</label></p>
	<p><label><input type="checkbox" id="printtrack" checked="checked">Track</label></p>
	<p><label><input type="checkbox" id="printbedspreads" checked="checked">Bedspreads</label></p>
	<p><label><input type="checkbox" id="printdraperies" checked="checked">Draperies</label></p>
	<p><label><input type="checkbox" id="printtoptreatments" checked="checked">Top Treatments</label></p>
	<p><label><input type="checkbox" id="printwthw" checked="checked">Hardware</label></p>
	<p><label><input type="checkbox" id="printblinds" checked="checked">Blinds &amp; Shades</label></p>
	<button>Print Now</button>
	</fieldset>
	</div>

	<div id="viewtoggle">
		<button id="fullviewbutton" class="fullviewbutton active">Full View</button>
		<button id="summaryviewbutton" class="summaryviewbutton inactive">Summary View</button>
	</div>

	<input type="hidden" id="highlightrowids" value="" />
	<input type="hidden" id="calendar" value="<?php echo date("Y-m-d"); ?> to <?php echo date("Y-m-d",(time()+604800)); ?>" />
	<div id="daterangepicker"><button id="clear" type="button">Clear</button></div>
	
	
	<div id="lastrefreshtext">Next Refresh: <span id="lastrefreshcounter"><b>10</b> seconds</span> <button id="reloadnow" type="button">Reload Now</button></div>
	
	
	<script>
	function checkHighlightRows(){
		if($('#highlightrowids').val() != ''){
			var ordersToHighlight=$('#highlightrowids').val().split(',');
			$.each(ordersToHighlight,function(num,orderid){
				if(!$('#sherrytable tbody tr[data-workorder-number='+orderid+']').hasClass('searchhighlighted')){
					$('#sherrytable tbody tr[data-workorder-number='+orderid+']').addClass('searchhighlighted');
				}
			});
		}
	}

	function clearSearchHighlightRows(){
		$('#highlightrowids').val('');
		$('#sherrytable tbody tr.searchhighlighted').removeClass('searchhighlighted');
	}

	function changeCalendarDateRange(firstDate,lastDate,highlightLine){
		//alert(firstDate+' - '+lastDate);
		$('#calendar').data('dateRangePicker').setDateRange(firstDate,lastDate);

		//highlight the order number
		$('#sherrytable tbody tr[data-workorder-number='+highlightLine+']').addClass('searchhighlighted');

		if($('#highlightrowids').val() == ''){
			$('#highlightrowids').val(highlightLine);
		}else{
			$('#highlightrowids').val($('#highlightrowids').val()+','+highlightLine);
		}

		checkHighlightRows();

		//reset the search box
		$('#sherrysearchresultsbox').hide();
	}
	
	$(function(){

		$('#fullviewbutton').click(function(){
			if(!$('table#sherrytable').hasClass('fullview')){
				$('table#sherrytable').addClass('fullview');
				$('table#sherrytable').removeClass('summaryview');
				$('button#fullviewbutton').addClass('active').removeClass('inactive');
				$('button#summaryviewbutton').addClass('inactive').removeClass('active');
			}
		});


		$('#summaryviewbutton').click(function(){
			if(!$('table#sherrytable').hasClass('summaryview')){
				$('table#sherrytable').addClass('summaryview');
				$('table#sherrytable').removeClass('fullview');
				$('button#summaryviewbutton').addClass('active').removeClass('inactive');
				$('button#fullviewbutton').addClass('inactive').removeClass('active');
			}
		});


		$('#printviewform button').click(function(){
			
			var dateSplit=$('#calendar').val().split(' to ');
			var startDate=dateSplit[0];
			var endDate=dateSplit[1];

			var urlgo='/orders/printschedule';
		
			urlgo += '/'+startDate+'/'+endDate;
            urlgo += '/pendingschedule';

			
			if($('#printcubicles').is(':checked')){
				urlgo += '/1';
			}else{
				urlgo += '/0';
			}
			if($('#printtrack').is(':checked')){
				urlgo += '/1';
			}else{
				urlgo += '/0';
			}

			if($('#printbedspreads').is(':checked')){
				urlgo += '/1';
			}else{
				urlgo += '/0';
			}

			if($('#printdraperies').is(':checked')){
				urlgo += '/1';
			}else{
				urlgo += '/0';
			}

			if($('#printtoptreatments').is(':checked')){
				urlgo += '/1';
			}else{
				urlgo += '/0';
			}

			

			if($('#printwthw').is(':checked')){
				urlgo += '/1';
			}else{
				urlgo += '/0';
			}

			if($('#printblinds').is(':checked')){
				urlgo += '/1';
			}else{
				urlgo += '/0';
			}

			urlgo += '/Sherry%20Schedule%20'+startDate+'-'+endDate+'.pdf';

			window.open(urlgo,'_blank');
		});

		$('#sherrysearchform #sherrysearchquery').keyup(function(){
			if($(this).val() != '' && $(this).val().length > 3){
				$.get('/orders/searchsherryschedule/'+$(this).val(),
				function(result){
					$('#sherrysearchresultsbox').html(result).show();
				});
			}else{
				clearSearchHighlightRows();
				$('#sherrysearchresultsbox').html('').hide();
			}
		});



		$('#calendar').dateRangePicker({
			inline:true,
			container: '#daterangepicker',
			alwaysOpen:true
		}).bind('datepicker-change',function(event,obj){
			$.fancybox.showLoading();
			clearSearchHighlightRows();
			$.get('/orders/getsherryschedulerows/'+obj.date1.toISOString().substring(0, 10)+'/'+obj.date2.toISOString().substring(0, 10)+'/'+'pendingschedule'+'/'+$('#highlightrowids').val(),
				function(responseText){
					$('#scriptsoutput').html(responseText);
					checkHighlightRows();
					$.fancybox.hideLoading();
				}
			);
		});
		
		$('#clear').click(function(evt){
			evt.stopPropagation();
			$('#calendar').data('dateRangePicker').clear();
			$('#sherrytable tbody').html('');
			$('#sherrytable tfoot').html('');
		});
		
		$.fancybox.showLoading();
		$.get('/orders/getsherryschedulerows/<?php echo date("Y-m-d"); ?>/<?php echo date("Y-m-d",(time()+604800)); ?>'+'/'+'pendingschedule'+'/'+$('#highlightrowids').val(),function(responseText){
			$('#scriptsoutput').html(responseText);
			$.fancybox.hideLoading();
			setInterval('updateRefreshCounter()',1000);
		});
		
		$('#reloadnow').click(function(){
			reloadSchedule();
			checkHighlightRows();
			$('#lastrefreshcounter b').html('10');
		});
	});
		


	

	function reloadSchedule(){
		var dateStart;
		var dateEnd;
		
		var caldata=$('#calendar').val();
		//console.log(caldata);
		dateSplit=caldata.split(' to ');
		dateStart=dateSplit[0];
		dateEnd=dateSplit[1];
		
		
		//$.fancybox.showLoading();
		$.get('/orders/getsherryschedulerows/'+dateStart+'/'+dateEnd
		+'/'+'pendingschedule'+'/'+$('#highlightrowids').val(),
			function(responseText){
				$('#scriptsoutput').html(responseText);
				//$.fancybox.hideLoading();

			}
		);
		
	}
		


	function addBatchNote(batchid){
		$.fancybox({
			'type':'iframe',
			'href':'/orders/addbatchnote/'+batchid,
			'autoSize':false,
			'width':500,
			'height':350,
			'modal':true
		});
	}


	function voidShipmentBatch(batchid){
		$.fancybox({
			'type':'iframe',
			'href':'/orders/voidshipmentbatch/'+batchid,
			'autoSize':false,
			'width':750,
			'height':200,
			'modal':true
		});
	}


	function voidBatchCompletion(batchid){
		$.fancybox({
			'type':'iframe',
			'href':'/orders/voidbatchcompletion/'+batchid,
			'autoSize':false,
			'width':750,
			'height':200,
			'modal':true
		});
	}

		
	function updateRefreshCounter(){
		if($('#tabs').tabs('option', 'active') != 0){
		   //calendar view tab not active... do not call refresh
			console.log('Calendar tab not active. Do not refresh AJAX');
		   $('#lastrefreshcounter b').html('1');
		}else{
			console.log('Calendar tab active. Activate refresh AJAX');
		   var currentCount=parseInt($('#lastrefreshcounter b').html());
			var newCount=(currentCount-1);
			if(newCount==0){
				reloadSchedule();
				$('#lastrefreshcounter b').html('10');
			}else{
				if(newCount < 10){
					newCount='0'+newCount;
				}
				$('#lastrefreshcounter b').html(newCount);
			}
		}
		checkHighlightRows();
	}								
	</script>
	
	
	<table id="sherrytable" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" class="fullview">
	<thead>
		<tr>
			<th class="actions" data-colnum="1" rowspan="2">Actions</th>
			<th class="batchid" data-colnum="2" rowspan="2">Batch #</th>
			<th class="wonumber" data-colnum="3" rowspan="2">WO#</th>
			<th class="customer" data-colnum="4" rowspan="2">Customer</th>
			<th class="ponumber" data-colnum="5" rowspan="2">PO#</th>
			<th class="wodate" data-colnum="6" rowspan="2">Date</th>
			<th class="project" data-colnum="7" rowspan="2">Project</th>
			<th class="facility" data-colnum="8" rowspan="2">Recipient</th>
			<th class="woshipdate" data-colnum="9" rowspan="2">WO Ship Date<br>Ship Via</th>
			<th class="totals" data-colnum="10" rowspan="2">Totals</th>
			<th class="cubicles" colspan="2">CC</th>
			<th class="trk" data-colnum="13">TRK</th>
			<th class="bedspreads">BS</th>
			<th class="draperies" colspan="2">DRAPERIES</th>
			<th class="valances" colspan="2">VALANCES</th>
			<th class="cornices" colspan="2">CORNICES</th>
			<th class="wthw" data-colnum="19">WT HW</th>
			<th class="blinds" data-colnum="20">B&amp;S</th>
		</tr>
		<tr>
			<th class="ccqty" data-colnum="11">QTY</th>
			<th class="cclf" data-colnum="12">LF</th>
			<th class="trklf" data-colnum="13">LF</th>
			<th class="bsqty" data-colnum="14">QTY</th>
			<th class="drapeqty" data-colnum="15">QTY</th>
			<th class="drapewidths" data-colnum="16">WIDTHS</th>
			
			<th class="valqty" data-colnum="17">QTY</th>
			<th class="vallf" data-colnum="18">LF</th>
			
			<th class="cornqty" data-colnum="19">QTY</th>
			<th class="cornqty" data-colnum="20">LF</th>
			
			<th class="wthwqty" data-colnum="21">QTY</th>
			<th class="blindqty" data-colnum="22">QTY</th>
		</tr>
	</thead>
	<tbody>
		
	</tbody>
	<tfoot>
		<tr>
			<td colspan="9">&nbsp;</td>
			<td data-colnum="10" class="totalslabel">TOTALS</td>
			<td data-colnum="11"></td>
			<td data-colnum="12"></td>
			
			<td data-colnum="13"></td>
			<td data-colnum="14"></td>
			
			<td data-colnum="15"></td>
			<td data-colnum="16"></td>
			
			<td data-colnum="17"></td>
			<td data-colnum="18"></td>
			
			<td data-colnum="19"></td>
			<td data-colnum="20"></td>

			<td data-colnum="21"></td>
			<td data-colnum="22"></td>
		</tr>
	</tfoot>
	</table>
	
</div>

<div id="shipped">
	
</div>
	
</div>

<script>
function markitemshipped(batchID){
		$.fancybox({
			'href':'/orders/markscheduleditemshipped/'+batchID,
			'type':'iframe',
			'autoSize':false,
			'width':880,
			'height':680,
			'modal':true,
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
	}
	
function markiteminvoiced(batchID){
		    
	$.fancybox({
			'href':'/orders/markschedulediteminvoiced/'+batchID,
			'type':'iframe',
			'autoSize':false,
			'width':680,
			'height':480,
			'modal':true,
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
}
	
function markitemuninvoiced(batchID){
        $.fancybox.showLoading();
		$.ajax({
		    'method':'GET',
		    'url':'/orders/markscheduleditemuninvoiced/'+batchID,
		    'success':function(responsedata){
		        if(responsedata=='OK'){
		            //it is done
		            $.fancybox.hideLoading();
		            reloadSchedule();
			        checkHighlightRows();
			        $('#lastrefreshcounter b').html('10');
		        }
		    },
		    'dataType':'html'
		    });
	}
	
function markitemcompleted(batchID){
		$.fancybox({
			'href':'/orders/markscheduleditemcompleted/'+batchID,
			'type':'iframe',
			'autoSize':false,
			'width':680,
			'height':680,
			'modal':true,
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
}

	
$(function(){
	
	//if(window.location.hash == '#unscheduled'){
	   var deferloadval=false;
	//}else{
	//   var deferloadval=true;
	//}
	
    if(window.location.hash == '#sherryschedule'){
		var activeTab=0;
	}else if(window.location.hash == '#shipped'){
		var activeTab=1;
	}
	
	$('#tabs').tabs({
		active:activeTab,
		activate:function(event,ui){
			window.location.hash = '#'+ui.newPanel.attr('id');
        },
		load:function(event,ui){

		},
		create:function(event,ui){

		}
	});
	$('#tabs > li > a').click(function(e){
      e.preventDefault();
	});
	
	//unscheduledTab.ajax.reload();

	
	
	$('#sherrytable tbody tr.notcompletednotshipped td').hover(function(){
					//add hover column class
					$('#sherrytable thead tr th[data-colnum='+$(this).attr('data-colnum')+'],#sherrytable tbody tr.notcompletednotshipped td[data-colnum='+$(this).attr('data-colnum')+'],#sherrytable tfoot tr td[data-colnum='+$(this).attr('data-colnum')+']').addClass('hovercol');
					
					if($(this).attr('data-colnum') == '11' || $(this).attr('data-colnum') == '12'){
						$('#sherrytable thead th.cubicles').addClass('hovercol');
					}else if($(this).attr('data-colnum') == '14'){
						$('#sherrytable thead th.bedspreads').addClass('hovercol');
					}else if($(this).attr('data-colnum') == '15' || $(this).attr('data-colnum') == '16'){
						$('#sherrytable thead th.draperies').addClass('hovercol');
					}else if($(this).attr('data-colnum') == '17' || $(this).attr('data-colnum') == '18'){
						$('#sherrytable thead th.toptreatments').addClass('hovercol');
					}
					
				},function(){
					//remove the class
					$('#sherrytable thead tr th[data-colnum='+$(this).attr('data-colnum')+'],#sherrytable tbody tr.notcompletednotshipped td[data-colnum='+$(this).attr('data-colnum')+'],#sherrytable tfoot tr td[data-colnum='+$(this).attr('data-colnum')+']').removeClass('hovercol');
					
					if($(this).attr('data-colnum') == '11' || $(this).attr('data-colnum') == '12'){
						$('#sherrytable thead th.cubicles').removeClass('hovercol');
					}else if($(this).attr('data-colnum') == '14'){
						$('#sherrytable thead th.bedspreads').removeClass('hovercol');
					}else if($(this).attr('data-colnum') == '15' || $(this).attr('data-colnum') == '16'){
						$('#sherrytable thead th.draperies').removeClass('hovercol');
					}else if($(this).attr('data-colnum') == '17' || $(this).attr('data-colnum') == '18'){
						$('#sherrytable thead th.toptreatments').removeClass('hovercol');
					}
				});
});

</script>