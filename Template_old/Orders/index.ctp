<!-- src/Template/Orders/index.ctp -->
<script src="/js/jquery.multiselect.min.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jquery.multiselect.css" />

<style>
div.dt-buttons{ margin-left:10px; }
</style>
<script>
$(function(){
	$('select#as-fabricsearch,select#as-lineitem,select#as-customer,select#as-hciagent,select#includestatuses,select#includestages').multiselect({
		multiple:true,
		noneSelectedText: "--Select--",
		header:false
	});
});
</script>

<h1 class="pageheading">Orders</h1>

<button type="button" id="newquickorder" onclick="location.href='/orders/addnew/';">+ New Order</button>


<?php
$rowcols='<th rowspan="2">Actions</th>
	<th rowspan="2">WO #</th>
	<th rowspan="2">Customer</th>
	<th rowspan="2">PO #</th>
	<th rowspan="2">PO Date</th>
	<th rowspan="2">Quote Title / Project</th>
	<th rowspan="2">Facility / Type</th>
	<th rowspan="2">Status</th>
	<th rowspan="2">Stage</th>
	<th rowspan="2">Ship Date</th>
	<th rowspan="2">Ship Method</th>
	<th rowspan="2">Totals</th>
	<th colspan="2" class="ccparent">CC</th>
	<th class="trkparent">TRK</th>
	<th class="bsparent">BS</th>
	<th colspan="2" class="drapeparent">DRAPERIES</th>
	<th colspan="2" class="valparent">VALANCES</th>
	<th colspan="2" class="cornparent">CORNICES</th>
	<th class="wthwparent">WT HW</th>
	<th class="basparent">B&amp;S</th>
	<th rowspan="2">Shipments</th>
</tr>
<tr>
	<th>QTY</th><!--CC-->
	<th>LF</th><!--CC-->
	<th>LF</th><!--TRK-->
	<th>QTY</th><!--BS-->
	<th>QTY</th><!--DRAPE-->
	<th>WIDTHS</th><!--DRAPE-->
	<th>QTY</th><!--VAL-->
	<th>LF</th><!--VAL-->
	<th>QTY</th><!--CORN-->
	<th>LF</th><!--CORN-->
	<th>QTY</th><!--WTHW-->
	<th>QTY</th><!--B&S-->
	';
?>

<div id="orderstatustabs">
	<ul>
		<li><a href="#allactive">All Active <span class="tabcount" id="allactive-count">(<?php echo $statusCounts['All Active']; ?>)</span></a></li>
		<li class="needlineitemstab"><a href="#needlineitems">Unprocessed <span class="tabcount" id="needlineitems-count">(<?php echo $statusCounts['Needs Line Items']; ?>)</span></a></li>
		<li class="allorderstab"><a href="#allorders">All Orders <span class="tabcount" id="allorders-count">(<?php echo $statusCounts['All Orders']; ?>)</span></a></li>
		<li class="pastduetab"><a href="#pastdue">Due/Past Due <span class="tabcount" id="pastdue-count">(<?php echo $statusCounts['Past Due']; ?>)</span></a></li>
		<li class="completedtab"><a href="#completed">Completed <span class="tabcount" id="completed-count">(<?php echo $statusCounts['Shipped']; ?>)</span></a></li>
		<li class="canceled"><a href="#canceled">Canceled <span class="tabcount" id="canceled-count">(<?php echo $statusCounts['Canceled']; ?>)</span></a></li>
		<li class="advancedsearch"><a href="#advancedsearch">Advanced Search</a></li>
	</ul>


<div id="allactive">
	<table width="100%" cellpadding="5" border="0" id="allactive-ordersList">
	<thead>
	<tr>
	<?php echo $rowcols; ?>
	</tr>
	</thead>
	<tbody>

	</tbody>
	</table>
</div>



<div id="needlineitems">
	<table width="100%" cellpadding="5" border="0" id="needlineitems-ordersList">
	<thead>
	<tr>
	<?php echo $rowcols; ?>
	</tr>
	</thead>
	<tbody>

	</tbody>
	</table>
</div>


<div id="allorders">
	<table width="100%" cellpadding="5" border="0" id="allorders-ordersList">
	<thead>
	<tr>
	<?php echo $rowcols; ?>
	</tr>
	</thead>
	<tbody>

	</tbody>
	</table>
</div>


<div id="pastdue">
	<table width="100%" cellpadding="5" border="0" id="pastdue-ordersList">
	<thead>
	<tr>
	<?php echo $rowcols; ?>
	</tr>
	</thead>
	<tbody>

	</tbody>
	</table>
</div>


<div id="completed">
	<table width="100%" cellpadding="5" border="0" id="completed-ordersList">
	<thead>
	<tr>
	<?php echo $rowcols; ?>
	</tr>
	</thead>
	<tbody>

	</tbody>
	</table>
</div>



<div id="canceled">
	<table width="100%" cellpadding="5" border="0" id="canceled-ordersList">
	<thead>
	<tr>
	<?php echo $rowcols; ?>
	</tr>
	</thead>
	<tbody>

	</tbody>
	</table>
</div>


<div id="advancedsearch">
	
	<table width="100%" id="asearchform" cellpadding="10" cellspacing="0" border="0">
		<tr>
			<td><label>Identifiers<input type="text" id="as-identifiers" /></label></td>
			<td><label>Quote #<input type="text" id="as-quotenumber" /></label></td>
			<td><label>Order #<input type="text" id="as-ordernumber" /></label></td>
			<td><label>Client PO #<input type="text" id="as-clientpo" /></label></td>
		</tr>
		<tr>
			<td><label>Date Range</label><input type="text" id="as-daterangestart" placeholder="START" /> <input type="text" id="as-daterangeend" placeholder="END" /></td>
			<td><label>Customer<select id="as-customer" multiple="multiple">
			<?php
				foreach($allcompanies as $company){
					echo "<option value=\"".$company['id']."\">".$company['company_name']."</option>";
				}
				?></select></label></td>
			<td><label>Contact Name<input type="text" id="as-contactname" /></label></td>
			<td><label>Phone Number<input type="text" id="as-phonenumber" /></label></td>
		</tr>
		<tr>
			<td><label>Fabric<select id="as-fabricsearch" multiple="multiple">
			<?php
				foreach($allfabrics as $fabric){
					echo "<option value=\"".$fabric['id']."\">".$fabric['fabric_name']." - ".$fabric['color']."</option>";
				}
				?></select></label></td>
			<td><label>Line Item</label><select id="as-lineitem" multiple="multiple">
			<option value="pl_bedspread">Price List Bedspread</option>
			<option value="pl_cubicle_curtain">Price List Cubicle Curtain</option>
			<option value="pl_box_pleated">Price List Valance</option>
			<option value="pl_pinch_pleated_drapery">Price List Pinch Pleated Drapery</option>
			<option value="pl_cornice">Price List Cornice</option>
			<option value="calculated_bedspread">Calculated Bedspread</option>
			<option value="calculated_cubicle_curtain">Calculated Cubicle Curtain</option>
			<option value="calculated_box_pleated">Calculated Valance</option>
			<option value="calculated_straight_cornice">Calculated Cornice</option>
			<option value="calculated_pinch_pleated_drapery">Calculated Pinch Pleated Drapery</option>
			<option value="custom">Custom Line Item</option>
				</select> <input type="text" id="as-lineitemkeywords" placeholder="Keywords" /></td>
			<td><label>Order Total Range</label><input type="number" min="0" step="any" id="as-ordertotalmin" placeholder="MIN" /> <input type="number" min="0" step="any" id="as-ordertotalmax" placeholder="MAX" /></td>
			<td><label>HCI Agent<select id="as-hciagent" multiple="multiple">
			<?php
				foreach($allusers as $user){
					echo "<option value=\"".$user['id']."\">".$user['last_name'].", ".$user['first_name']."</option>";
				}
				?></select></label></td>
		</tr>
		<tr>
			<td><label>Order Status</label><select id="includestatuses" multiple="multiple">
			<option value="Needs Line Items" selected>Needs Line Items</option>
			<option value="Pre-Production" selected>Pre-Production</option>
			<option value="Production" selected>Production</option>
			<option value="On Hold" selected>On Hold</option>
			<option value="Complete" selected>Complete</option>
			<option value="Returned">Returned</option>
			<option value="Canceled">Canceled</option>
			<option value="Shipped" selected>Shipped</option>
			</select></td>
			<td><label>Order Stage</label><select id="includestages" multiple="multiple">
			    <option value="FABRIC/B&S ORDERED" selected>FABRIC/B&S ORDERED</option>
			    <option value="IN PRODUCTION" selected>IN PRODUCTION</option>
			    <option value="COMPLETE" selected>COMPLETE</option>
			    <option value="HOLD - NO ACTION UNTIL MEASURE" selected>HOLD - NO ACTION UNTIL MEASURE</option>
			    <option value="DEAD" selected>DEAD</option>
			    <option value="REPLACED" selected>REPLACED</option>
			    <option value="OTHER 1" selected>OTHER 1</option>
			    <option value="OTHER 2" selected>OTHER 2</option>
			    <option value="OTHER 3" selected>OTHER 3</option>
			    <option value="OTHER 4" selected>OTHER 4</option>
			    <option value="PROBS" selected>PROBS</option>
			    <option value="WILL COMPLETE BY EOM - PUP" selected>WILL COMPLETE BY EOM - PUP</option>
			    <option value="SVCS ONLY" selected>SVCS ONLY</option>
			    <option value="M1" selected>M1</option>
			    <option value="WH2 – INV’D – NEED TO SHIP" selected>WH2 – INV’D – NEED TO SHIP</option>
			    <option value="PARTIALLY COMPLETE – M1" selected>PARTIALLY COMPLETE – M1</option>
			</select></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr id="bigsearchbuttons">
			<td colspan="4"><button type="button" id="bigsearchgo">Perform Search</button>  <button id="resetbutton" type="button">Clear Form</button></td>
		</tr>
	</table>
	
	
	<table width="100%" cellpadding="5" border="0" id="advancedsearch-ordersList">
	<thead>
	<tr>
	<?php echo $rowcols; ?>
	</tr>
	</thead>
	<tbody>

	</tbody>
	</table>
	
	
</div>

</div>
<script>
var dtArray=new Array();

$(function(){
	
	var postData;
	
	$('#as-daterangestart,#as-daterangeend').datepicker();
	
	$(window).load(function(){
    	// Do stuff after everything has been loaded
		var activetab=$('#orderstatustabs ul.ui-tabs-nav li.ui-state-active a').attr('href');
		dtArray[activetab.replace('#','')].ajax.reload();
		
		
		$('div.top,div.bottom').css('width',($(window).width()*0.7));
   	});

	
	$('#bigsearchgo').click(function(){
		dtArray['advancedsearch'].ajax.reload();
	});
	
	$('#orderstatustabs').tabs({
		activate: function(event, ui) { 
				window.location.hash=ui.newPanel.selector;
				//find and refresh this tab's datatable
				dtArray[ui.newPanel.selector.replace('#','')].ajax.reload();
		},
		create: function(event, ui) { 
        	//executed after tab is created.
			$('#orderstatustabs').show();
    	}
	});
	
	
	
	
	
	
	$('#orderstatustabs ul li').each(function(){
			var thistab=$(this).find('a').attr('href').replace('#','');
		
		    console.log('thistab='+thistab);
		    
			if(thistab == 'allorders'){
			    var doButtonsVal=['excel'];
			}else{
			    var doButtonsVal=[];
			}
			
		
			dtArray[thistab]=$('#'+thistab+'-ordersList').DataTable({
				"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
				"processing":true,
				"bServerSide":true,
				"deferLoading": false,
				"sServerMethod":"POST",
				"buttons": doButtonsVal,
				"ajax":{
					"url":"/orders/getorderslist/"+thistab+".json",
					"data":function (d){
                		//d.myKey = "myValue";
						if(thistab=='advancedsearch'){
							d.identifiers=$('#as-identifiers').val();
							d.quotenumber=$('#as-quotenumber').val();
							d.ordernumber=$('#as-ordernumber').val();
							d.ponumber=$('#as-clientpo').val();
							d.daterangestart=$('#as-daterangestart').val();
							d.daterangeend=$('#as-daterangeend').val();
							d.customerid=$('#as-customer').val();
							d.contactname=$('#as-contactname').val();
							d.phonenumber=$('#as-phonenumber').val();
							d.fabricids=$('#as-fabricsearch').val();
							d.lineitemtype=$('#as-lineitem').val();
							d.lineitemkeywords=$('#as-lineitemkeywords').val();
							d.ordertotalmin=$('#as-ordertotalmin').val();
							d.ordertotalmax=$('#as-ordertotalmax').val();
							d.userid=$('#as-hciagent').val();
							d.orderstatus=$('#includestatuses').val();
							d.orderstage=$('#includestages').val();
						}
						
					}
				},
				"dom": '<"top"iflpB<"clear">>rt<"bottom"iflp<"clear">>',
				stateSave: true,
				searchHighlight: true,
				fixedHeader: false,
				"language": {
					"infoFiltered": ''
				 },
				 "order": [[ 1, "desc" ]],
				"columns": [
					{"className":'orderactions',"orderable": false},
					{"className":'order_number',"orderable": true},
					{"className":"company","orderable":false},
					{"className":"po_number","orderable":true},
					{"className":'created',"orderable": true},
					{"className":'project','orderable':false},
					{"className":'facility',"orderable": false},
					{"className":'status',"orderable": false},
					{"className":'stage',"orderable": false},
					{"className":'duedate',"orderable": true},
					{"className":'shipmethod','orderable':false},
					{"className":'total',"orderable": false},
					{"className":'cc_qty',"orderable": false},
					{"className":'cc_lf',"orderable": false},
					{"className":'track_qty',"orderable": false},
					{"className":'bs_qty',"orderable": false},
					{"className":'drape_qty',"orderable": false},
					{"className":'drape_widths',"orderable": false},
					{"className":'val_qty',"orderable": false},
					{"className":'val_lf',"orderable": false},
					{"className":'corn_qty',"orderable": false},
					{"className":'corn_lf',"orderable": false},
					{"className":'hardware',"orderable": false},
					{"className":'blinds',"orderable": false},
					{"className":'shipments_list',"orderable":false}
				  ]
			});
				
		});
	
	
	$('#resetbutton').click(function(){
		$('#as-identifiers').val('');
		$('#as-quotenumber').val('');
		$('#as-ordernumber').val('');
		$('#as-clientpo').val('');
		$('#as-daterangestart').val('');
		$('#as-daterangeend').val('');
		
		$('#as-contactname').val('');
		$('#as-phonenumber').val('');
		
		
		$('#as-lineitemkeywords').val('');
		$('#as-ordertotalmin').val('');
		$('#as-ordertotalmax').val('');
		

		$('select#as-fabricsearch,select#as-lineitem,select#as-customer,select#as-hciagent,select#includestatuses,select#includestages').multiselect('uncheckAll');

		dtArray['advancedsearch'].ajax.reload();
		
	});
	
});
	
</script>
<style>

button#as-customer_ms,
button#as-fabricsearch_ms,
button#as-hciagent_ms,
button#includestatuses_ms,
button#includestages_ms{ display:block !important; width:100% !important; padding:10px; }

button#as-lineitem_ms{ width:48% !important; padding:10px; }

#container #content > div.row{ max-width:96% !important; }
	
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newquote{ float:right; }
	
#orderstatustabs > ul > li.ui-state-default{
	background:#2F63B0;
	border:0 !important;
}
#orderstatustabs > ul > li.ui-state-default > a{
	color:#FFF;
}

#orderstatustabs > ul > li.ui-state-active{
	background:#FFF !important;
	border:0 !important;
}
#orderstatustabs > ul > li.ui-state-active > a{
	color:#26337A !important;
}
	
#orderstatustabs > ul.ui-tabs-nav > li{ margin:5px 5px 0px 5px !important; font-size:12px; }

#orderstatustabs table th{ font-size:12px; }
#orderstatustabs table td{ font-size:12px; }
	
	/*
#orderstatustabs div table th.company, #orderstatustabs div table td.company{ width:16% !important; }
#orderstatustabs div table th.order_number, #orderstatustabs div table td.order_number{ width:7% !important; }
#orderstatustabs div table th.status, #orderstatustabs div table td.status{ width:12% !important; }
#orderstatustabs div table th.created, #orderstatustabs div table td.created{ width:7% !important; }
#orderstatustabs div table th.duedate, #orderstatustabs div table td.duedate{ width:7% !important; }







#orderstatustabs div table th.shipments_list, #orderstatustabs div table td.shipments_list{ width:10% !important; }
#orderstatustabs div table th.invoices_list, #orderstatustabs div table td.invoices_list{ width:10% !important; }
#orderstatustabs div table th.total, #orderstatustabs div table td.total{ width:10% !important; }
#orderstatustabs div table th.actions, #orderstatustabs div table td.actions{ width:14% !important; }

*/

tr.canceled td.order_number:after{ content:'CANCELED'; display:block; font-size:11px; font-weight:bold; }
tr.completed td.order_number:after{ content:'COMPLETED'; display:block; font-size:11px; font-weight:bold; }

tr.completed.pastdue td.order_number{ background:#006600 !important; }


tr.needlineitems td.order_number:after{ content:'UNPROCESSED'; display:block; font-size:11px; font-weight:bold; }

tr.activeorder td.order_number:after{ content:'ACTIVE'; display:block; font-size:11px; font-weight:bold; }

#as-ordertotalmin,
#as-ordertotalmax{ width:48%; display:inline-block; }
	
#as-daterangestart,
#as-daterangeend{ width:48%; display:inline-block; }
	
#as-lineitem,
#as-lineitemkeywords{ width:48%; display:inline-block; }
	
#bigsearchbuttons td{ text-align:center !important; }	


#advancedsearch table#asearchform tr{ border-bottom:0 !important; }
#advancedsearch table#asearchform tr td{ padding:10px; }

#bigsearchgo{ font-size:14px; font-weight:bold; padding:10px; }
#resetbutton{ background:#CCC; color:#444; border:1px solid #444; font-size:12px; padding:5px; margin-left:20px; }
	
.pastduetab{ background:#B20000 !important; }
.pastduetab a{ color:#FFF !important; }
	
.duesoontab{ background:#F1EEA0 !important; }
.duesoontab a{ color:#26337A !important; }
	
	
.onholdtab{ background:#5E0094 !important; }
.onholdtab a{ color:#FFF !important; }
	
.needlineitemstab{ background:#FFC926 !important; }
.needlineitemstab a{ color:#000 !important; }
	
.completedtab{ background:#006600 !important; }
.completedtab a{ color:#FFF !important; }	

	
.canceled{ background:#444444 !important; }
.canceledtab a{ color:#ccc !important; }	
	
span.tabcount{ font-weight:bold; }
	
td.status div.orderlabel{ font-weight:bold; font-size:10px; }
td.status div.progressbar{ width:97%; padding:2px; border:1px solid #000099; height:12px; display:block; position:relative; }
td.status div.progressbar .progresscompleted{ background:#000099; height:12px; display:block; }
td.status div.percentlabel{ display:block; color:#000099; font-size:10px; }
	
tr.duesoon{ background:#F1EEA0 !important; color:#26337A !important; }
tr.pastdue{ background:#B20000 !important; color:#FFF !important; }
tr.pastdue td{ color:#FFF !important; }
tr.pastdue td span.highlight{ color:#000 !important; }
tr.onhold{ background:#5E0094 !important; color:#FFF !important; }
tr.onhold td{ color:#FFF !important; }
tr.completed{ background:#006600 !important; color:#FFF !important; }

tr.completed.pastdue{ background:#B20000 !important; color:#FFF !important; }


tr.completed td{ color:#FFF !important; }
tr.needlineitems{ background:#FFC926 !important; color:#000!important; }
tr.needlineitems td{ color:#000 !important; }

tr.canceled{ background:#444 !important; color:#ccc !important; }
tr.canceled td{ color:#ccc !important; }

tr.editinglock td.orderactions{ position:relative; overflow:visible; }
tr.editinglock td.orderactions:before{ content: '';
    width: 16px;
    height: 16px;
    position: absolute;
    left: -17px;
    top: 0px;
    background-image: url(/img/system-lock-icon.png);
    padding: 0;
    background-color: red;
    background-repeat: no-repeat; }
	
	
tr.pastdue td.status div.progressbar{ border:1px solid #FFF !important; }
tr.pastdue td.status div.progressbar .progresscompleted{ background:#FFF !important; }	
tr.pastdue td.status div.percentlabel{ color:#FFF !important; }
tr.pastdue td a{ color:#F7F99A !important; }
	
	
tr.onhold td.status div.progressbar{ border:1px solid #FFF !important; }
tr.onhold td.status div.progressbar .progresscompleted{ background:#FFF !important; }	
tr.onhold td.status div.percentlabel{ color:#FFF !important; }
tr.onhold td a{ color:#F7F99A !important; }
	
tr.completed td.status div.progressbar{ border:1px solid #FFF !important; }
tr.completed td.status div.progressbar .progresscompleted{ background:#FFF !important; }	
tr.completed td.status div.percentlabel{ color:#FFF !important; }
tr.completed td a{ color:#F7F99A !important; }

table.dataTable thead tr th,
table.dataTable tfoot tr th{ text-align:center; }

table.dataTable thead th, table.dataTable tfoot th{ padding:3px !important; font-size:12px !important; }

@media screen{
	.dataTables_wrapper,
	ul.ui-tabs-nav{ width:2640px !important; }
}
@media print{
	.dataTables_wrapper,ul.ui-tabs-nav{
		width:100% !important;
		.dataTables th, .dataTables td{ font-size:10px !important; }
	}
}
	
	
td.orderactions,th.orderactions{
	width:135px !important;
}

td.company,th.company{
	width:100px !important;
}

td.order_number,th.order_number{
	width:100px !important;
}

td.po_number,th.po_number{
	width:100px !important;
}

td.created,th.created{
	width:100px !important;
}


td.project,th.project{
	width:100px !important;
}

td.facility,th.facility{
	width:100px !important;
}

td.status,th.status{
	width:100px !important;
}

td.stage,th.stage{
    width:140px !important;
}

td.duedate,th.duedate{
	width:100px !important;
}

td.shipmethod,th.shipmethod{
    width:120px !important;
}

td.total,th.total{
	width:160px !important;
}

td.cc_qty,th.cc_qty{
	width:75px !important;
}

td.cc_lf,th.cc_lf{
	width:75px !important;
}

td.cc_diff,th.cc_diff{
	width:75px !important;
}

td.track_qty,th.track_qty{
	width:75px !important;
}

td.bs_qty,th.bs_qty{
	width:75px !important;
}

td.bs_diff,th.bs_diff{
	width:75px !important;
}

td.drape_qty,th.drape_qty{
	width:100px !important;
}

td.drape_widths,th.drape_widths{
	width:100px !important;
}

td.drape_diff,th.drape_diff{
	width:100px !important;
}

td.tt_qty,th.tt_qty{
	width:100px !important;
}

td.tt_lf,th.tt_lf{
	width:100px !important;
}

td.tt_diff,th.tt_diff{
	width:100px !important;
}

td.hardware,th.hardware{
	width:100px !important;
}

td.blinds,th.blinds{
	width:100px !important;
}

td.shipments_list,th.shipments_list{
	width:100px !important;
}
	
th.ccparent{ width:225px !important; border-bottom:1px solid #CCC !important; }
	
th.bsparent{ width:150px !important; border-bottom:1px solid #CCC !important; }
	
th.drapeparent{ width:225px !important; border-bottom:1px solid #CCC !important; }
	
th.trkparent{ width:75px !important; border-bottom:1px solid #CCC !important; }

th.wtparent{ width:225px !important; border-bottom:1px solid #CCC !important; }
	
th.wthwparent{ width:80px !important; border-bottom:1px solid #CCC !important; }

th.valparent{ width:225px !important; border-bottom:1px solid #CCC !important; }

th.cornparent{ width:225px !important; border-bottom:1px solid #CCC !important; }

	
th.basparent{ width:80px !important; border-bottom:1px solid #CCC !important; }
	
#newquickorder{ float:right; padding:6px 12px !important; font-size:14px; }
	
tr.canceled .status .progressbar,
tr.canceled .status .percentlabel{ display:none !important; }
	
table.dataTable tbody tr td{ border-bottom:1px solid #888 !important; }
</style>