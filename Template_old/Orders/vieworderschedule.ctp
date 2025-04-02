<!-- src/Template/Orders/vieworderschedule.ctp -->
<?php

echo "<div id=\"pagegen\">Generated ".date('Y-m-d g:iA T')."</div>";
/**PPSASCRUM-3 start **/
$creditHold =  ($thisCustomer['on_credit_hold']) ? '<span style="color: red;"> On Credit Hold</span>  ' : ' ';
/**PPSASCRUM-3 end **/
echo "<h1>Manage <u>Order ".$thisOrder['order_number']."</u> Scheduling</h1>";
if($thisOrder['due'] > 1000){
	echo "<h4>Order Ship-By Date: <u>".date("n/j/Y",$thisOrder['due'])."</u>";

	if(!is_null($thisOrder['due']) && intval($thisOrder['due']) > 1000 && time() > strtotime(date('Y-m-d',intval($thisOrder['due'])).' 18:00:00') ){
		
			echo "<span id=\"mainpastduealert\" style=\"font-size:11px; vertical-align:middle; line-height:16px; text-align:center;color:red;font-weight:bold;\">&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"/img/alert.png\" style=\"vertical-align:middle;\" /> PAST DUE</span>";
		
	}
	echo "</h4>";
}
?>
<style>
@media only print{
	@page {size: landscape}

	body {
  		-webkit-print-color-adjust: exact !important;
	}

	#pagegen{ position:absolute; top:0; left:0; font-size:12px; }
	body > header,div#welcomebar{ display:none !important; }
	table#scheduledtable{ width:100% !important; }
	table#scheduledtable > thead > tr > th{ background:#26337A !important; color:#FFF !important; font-size:9px !important; padding:2px 2px 2px 2px !important; text-transform:uppercase; }
	table#scheduledtable tbody tr td{ font-size:9px !important; }
	
	table#scheduledtable tbody tr td table thead tr th{ font-size:9px !important; padding:2px 2px 2px 2px !important; text-transform:uppercase; }	

	table#scheduledtable tbody tr td table thead tr th:nth-of-type(1),
	table#scheduledtable tbody tr td table tbody tr td:nth-of-type(1){ width:10% !important; }

	table#scheduledtable tbody tr td table thead tr th:nth-of-type(2),
	table#scheduledtable tbody tr td table tbody tr td:nth-of-type(2){ width:12% !important; }

	table#scheduledtable tbody tr td table thead tr th:nth-of-type(3),
	table#scheduledtable tbody tr td table tbody tr td:nth-of-type(3){ width:13% !important; }

	table#scheduledtable tbody tr td table thead tr th:nth-of-type(4),
	table#scheduledtable tbody tr td table tbody tr td:nth-of-type(4){ width:13% !important; }

	table#scheduledtable tbody tr td table thead tr th:nth-of-type(5),
	table#scheduledtable tbody tr td table tbody tr td:nth-of-type(5){ width:12% !important; }

	table#scheduledtable tbody tr td table thead tr th:nth-of-type(6),
	table#scheduledtable tbody tr td table tbody tr td:nth-of-type(6){ width:40% !important; }

	table#scheduledtable tbody tr td table thead tr th:nth-of-type(7),
	table#scheduledtable tbody tr td table tbody tr td:nth-of-type(7){ display:none !important; }
	
	
	
	
	
	table#scheduledtablewithboxes{ width:100% !important; }
	table#scheduledtablewithboxes > thead > tr > th{ background:#26337A !important; color:#FFF !important; font-size:9px !important; padding:2px 2px 2px 2px !important; text-transform:uppercase; }
	table#scheduledtablewithboxes tbody tr td{ font-size:9px !important; }
	
	
	
	table#scheduledtablewithboxes thead > tr > th:nth-of-type(1),
	table#scheduledtablewithboxes tbody > tr > td:nth-of-type(1){ width:4% !important; }
	
	table#scheduledtablewithboxes thead > tr > th:nth-of-type(2),
	table#scheduledtablewithboxes tbody > tr > td:nth-of-type(2){ width:8% !important; }
	
	table#scheduledtablewithboxes thead > tr > th:nth-of-type(3),
	table#scheduledtablewithboxes tbody > tr > td:nth-of-type(3){ width:4% !important; }
	
	table#scheduledtablewithboxes thead > tr > th:nth-of-type(4),
	table#scheduledtablewithboxes tbody > tr > td:nth-of-type(4){ width:4% !important; }
	
	table#scheduledtablewithboxes thead > tr > th:nth-of-type(5),
	table#scheduledtablewithboxes tbody > tr > td:nth-of-type(5){ width:11% !important; }
	
	table#scheduledtablewithboxes thead > tr > th:nth-of-type(6),
	table#scheduledtablewithboxes tbody > tr > td:nth-of-type(6){ width:12% !important; }
	
	table#scheduledtablewithboxes thead > tr > th:nth-of-type(7),
	table#scheduledtablewithboxes tbody > tr > td:nth-of-type(7){ width:57% !important; }
	
	
	table#scheduledtablewithboxes tbody tr td table thead tr th{ font-size:9px !important; padding:2px 2px 2px 2px !important; text-transform:uppercase; }	

	table#scheduledtablewithboxes tbody tr td table thead tr th:nth-of-type(1),
	table#scheduledtablewithboxes tbody tr td table tbody tr td:nth-of-type(1){ width:10% !important; }

	table#scheduledtablewithboxes tbody tr td table thead tr th:nth-of-type(2),
	table#scheduledtablewithboxes tbody tr td table tbody tr td:nth-of-type(2){ width:12% !important; }

	table#scheduledtablewithboxes tbody tr td table thead tr th:nth-of-type(3),
	table#scheduledtablewithboxes tbody tr td table tbody tr td:nth-of-type(3){ width:11% !important; }

	table#scheduledtablewithboxes tbody tr td table thead tr th:nth-of-type(4),
	table#scheduledtablewithboxes tbody tr td table tbody tr td:nth-of-type(4){ width:13% !important; }

	table#scheduledtablewithboxes tbody tr td table thead tr th:nth-of-type(5),
	table#scheduledtablewithboxes tbody tr td table tbody tr td:nth-of-type(5){ width:22% !important; }

	table#scheduledtablewithboxes tbody tr td table thead tr th:nth-of-type(6),
	table#scheduledtablewithboxes tbody tr td table tbody tr td:nth-of-type(6){ width:12% !important; }
	
	
	table#scheduledtablewithboxes tbody tr td table thead tr th:nth-of-type(7),
	table#scheduledtablewithboxes tbody tr td table tbody tr td:nth-of-type(7){ width:20% !important; }

	table#scheduledtablewithboxes tbody tr td table thead tr th:nth-of-type(8),
	table#scheduledtablewithboxes tbody tr td table tbody tr td:nth-of-type(8){ display:none !important; }
	
	

	
	

	div#buttons{ display:none !important; }
}

@media only screen{

	#pagegen{ display:none; }
	table#scheduledtable{ width:1800px !important; }

	table#scheduledtable > thead > tr > th{ background:#26337A; color:#FFF; padding:4px 4px 4px 4px !important; vertical-align:middle !important; text-transform:uppercase; font-size:small !important; }
	
	table#scheduledtablewithboxes{ width:2000px !important; }

	table#scheduledtablewithboxes > thead > tr > th{ background:#26337A; color:#FFF; padding:4px 4px 4px 4px !important; vertical-align:middle !important; text-transform:uppercase; font-size:small !important; }
}

h1,h4{ text-align:center; }
/*#content div.row{ max-width:100% !important; width:100% !important; }*/

#content table thead tr th{ text-align:center; color:#FFF; }
#content h2{ text-align:center; color:#26337A; margin-top:30px; }

div#content > div.row{ width:98% !important; max-width:98% !important; }
table#scheduledtable{ display:block; border-top:1px solid #26337A !important; border-left:1px solid #26337A !important; border-right:1px solid #26337A !important; border-bottom:10px solid #26337A !important; margin:0 auto; }

table#scheduledtable > tbody > tr.lineitemrow > td{ border-top:10px solid #26337A !important; border-bottom:1px solid #26337A !important; }
table#scheduledtable > tbody > tr.lineitemrow > td:nth-of-type(1),
table#scheduledtable > tbody > tr.lineitemrow > td:nth-of-type(3),
table#scheduledtable > tbody > tr.lineitemrow > td:nth-of-type(4){ text-align:center !important; }

table#scheduledtable > tbody > tr > td > table > tbody > td{ border-bottom:1px solid #26337A !important; }
table#scheduledtable > tbody > tr > td > table{ border-bottom:1px solid #26337A !important; }


table#scheduledtable > tbody > tr > td:nth-of-type(7){ padding:0 0 0 0 !important; }
table#scheduledtable > tbody > tr > td:nth-of-type(7) > table > thead > tr{ background:rgba(0,0,0,0.15) !important; color:#111 !important; }


table#scheduledtable > tbody > tr.linenotesheading{ background:rgba(0,0,0,0.15) !important; }
table#scheduledtable > tbody > tr.linenotesheading th { color:#111 !important; padding:2px 2px 2px 2px !important; font-size:11px !important; text-transform:uppercase; font-weight:normal; }


table#scheduledtable > tbody > tr > td:nth-of-type(7) > table > thead > tr > th{ color:#111 !important; padding:2px 2px 2px 2px !important; font-weight:normal !important; text-transform:uppercase; font-size:11px; line-height:1.0 !important; }
table#scheduledtable > tbody > tr > td:nth-of-type(7) > table > tbody > tr.batchrow > td{ padding:1px 1px 1px 1px !important; font-size:13px; border-top:5px solid #26337A; }
table#scheduledtable > tbody > tr > td:nth-of-type(7) > table > tbody > tr.batchrow:nth-of-type(1) > td{ border-top:1px solid #444 !important; }

table#scheduledtable > tbody > tr > td:nth-of-type(7) > table > tbody > tr > td:nth-of-type(6){ text-align:left !important; font-size:11px; }

table#scheduledtable > tbody > tr.lineitemrow:nth-of-type(even){ background:rgba(0,0,255,0.05); }
table#scheduledtable > tbody > tr.lineitemrow:nth-of-type(odd){ background:rgba(0,0,255,0.01); }

table#scheduledtable > tbody > tr > td:nth-of-type(7) > table > tbody > tr.batchrow > td{ text-align:center !important; }

table#scheduledtable > tbody > tr > td:nth-of-type(7) > table{ background:transparent !important; margin-bottom:0 !important; }


tr.batchnoterow td{ text-align:left !important; padding:2px 2px 2px 2px !important; font-size:11px !important; }
tr.batchnoterow.batchmfgrow td{ color:#d65308 !important; }
tr.batchnoterow.batchshiprow td{ color:#440f70 !important; }
tr.batchnoterow.batchmiscrow td{ color:#2777f7 !important; }




td.shippeditem{ background:#505050 !important; color:#FFF !important; }
td.completeditem{ background:#216620 !important; color:#FFF !important; }

/*div#buttons{ text-align:right; }*/
div#buttons button{ margin-left:20px; }

tr.linenoterow td{ text-align:left !important; font-size:11px !important; padding:2px 2px 2px 2px !important; }

table.batchinnertable tr td{ border-bottom:1px solid #444 !important; border-right:1px solid #444 !important; }
table.batchinnertable tr{ border-left:1px solid #444 !important; border-top:1px solid #444 !important; }

#boxestogglewrap{ float:left; width:300px; }
#boxesonbutton{ padding:5px 10px 5px 10px !important; border:0 !important; background:transparent; color:#111; border-radius:20px; margin:0 0 0 0 !important; }
#boxesoffbutton{ padding:5px 10px 5px 10px !important; border:0 !important; background:transparent; color:#111; border-radius:20px; margin:0 0 0 0 !important; }
#boxestogglewrap button.toggleOff{ background:transparent !important; color:#111 !important; font-weight:normal; }
#boxestogglewrap button.toggleOn{ background:#222 !important; color:#FFF !important; font-weight:bold; }







table#scheduledtablewithboxes{ display:none; border-top:1px solid #26337A !important; border-left:1px solid #26337A !important; border-right:1px solid #26337A !important; border-bottom:10px solid #26337A !important; margin:0 auto; }

table#scheduledtablewithboxes > tbody > tr.lineitemrow > td{ border-top:10px solid #26337A !important; border-bottom:1px solid #26337A !important; }
table#scheduledtablewithboxes > tbody > tr.lineitemrow > td:nth-of-type(1),
table#scheduledtablewithboxes > tbody > tr.lineitemrow > td:nth-of-type(3),
table#scheduledtablewithboxes > tbody > tr.lineitemrow > td:nth-of-type(4){ text-align:center !important; }

table#scheduledtablewithboxes > tbody > tr > td > table > tbody > td{ border-bottom:1px solid #26337A !important; }
table#scheduledtablewithboxes > tbody > tr > td > table{ border-bottom:1px solid #26337A !important; }


table#scheduledtablewithboxes > tbody > tr > td:nth-of-type(7){ padding:0 0 0 0 !important; }
table#scheduledtablewithboxes > tbody > tr > td:nth-of-type(7) > table > thead > tr{ background:rgba(0,0,0,0.15) !important; color:#111 !important; }


table#scheduledtablewithboxes > tbody > tr.linenotesheading{ background:rgba(0,0,0,0.15) !important; }
table#scheduledtablewithboxes > tbody > tr.linenotesheading th { color:#111 !important; padding:2px 2px 2px 2px !important; font-size:11px !important; text-transform:uppercase; font-weight:normal; }


table#scheduledtablewithboxes > tbody > tr > td:nth-of-type(7) > table > thead > tr > th{ color:#111 !important; padding:2px 2px 2px 2px !important; font-weight:normal !important; text-transform:uppercase; font-size:11px; line-height:1.0 !important; }
table#scheduledtablewithboxes > tbody > tr > td:nth-of-type(7) > table > tbody > tr.batchrow > td{ padding:1px 1px 1px 1px !important; font-size:13px; border-top:5px solid #26337A; }
table#scheduledtablewithboxes > tbody > tr > td:nth-of-type(7) > table > tbody > tr.batchrow:nth-of-type(1) > td{ border-top:1px solid #444 !important; }

table#scheduledtablewithboxes > tbody > tr > td:nth-of-type(7) > table > tbody > tr > td:nth-of-type(6){ text-align:left !important; font-size:11px; }

table#scheduledtablewithboxes > tbody > tr.lineitemrow:nth-of-type(even){ background:rgba(0,0,255,0.05); }
table#scheduledtablewithboxes > tbody > tr.lineitemrow:nth-of-type(odd){ background:rgba(0,0,255,0.01); }

table#scheduledtablewithboxes > tbody > tr > td:nth-of-type(7) > table > tbody > tr.batchrow > td{ text-align:center !important; }

table#scheduledtablewithboxes > tbody > tr > td:nth-of-type(7) > table{ background:transparent !important; margin-bottom:0 !important; }


tr.invoicedbatch{ background:#FFD800 !important; }
tr.invoicedbatch td.shippeditem:after{ content:' (INV)'; }
</style>
<div id="buttons">
    <div id="boxestogglewrap">
        <button id="boxesonbutton" type="button" onclick="turnboxeson()" class="toggleOff">Boxes On</button>
        <button id="boxesoffbutton" type="button" onclick="turnboxesoff()" class="toggleOn">Boxes Off</button>
    </div>
    
	<button type="button" onclick="window.print();" style="float:right;">PRINT</button>
	<button type="button" onclick="location.href='/orders/scheduleorder/<?php echo $thisOrder['id']; ?>/1'" style="float:right;">+ NEW BATCH (ANY ITEMS)</button>
	<button type="button" onclick="location.href='/orders/scheduleorder/<?php echo $thisOrder['id']; ?>/0'" style="float:right;">+ NEW BATCH (MFG ITEMS)</button>
	
	<div style="clear:both;"></div>
</div>

<script>
function turnboxeson(){
    $('#boxesonbutton').removeClass('toggleOff').addClass('toggleOn');
    $('#boxesoffbutton').removeClass('toggleOn').addClass('toggleOff');
    $('table#scheduledtable').hide();
    $('table#scheduledtablewithboxes').show();
}

function turnboxesoff(){
    $('#boxesonbutton').removeClass('toggleOn').addClass('toggleOff');
    $('#boxesoffbutton').removeClass('toggleOff').addClass('toggleOn');
    $('table#scheduledtablewithboxes').hide();
    $('table#scheduledtable').show();
}


function markitemshipped(batchID){
		$.fancybox({
			'href':'/orders/markscheduleditemshipped/'+batchID+'/1',
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
	
function markitemcompleted(batchID){
		$.fancybox({
			'href':'/orders/markscheduleditemcompleted/'+batchID+'/1',
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

function addBatchNote(batchid){
		$.fancybox({
			'type':'iframe',
			'href':'/orders/addbatchnote/'+batchid+'/1',
			'autoSize':false,
			'width':500,
			'height':350,
			'modal':true
		});
	}


function voidShipmentBatch(batchid){
	$.fancybox({
		'type':'iframe',
		'href':'/orders/voidshipmentbatch/'+batchid+'/1',
		'autoSize':false,
		'width':750,
		'height':200,
		'modal':true
	});
}


function voidBatchCompletion(batchid){
	$.fancybox({
		'type':'iframe',
		'href':'/orders/voidbatchcompletion/'+batchid+'/1',
		'autoSize':false,
		'width':750,
		'height':200,
		'modal':true
	});
}

</script>

<?php

$totalNumPending=0;

if(count($allBatchesThisWO) == 0){
	echo "<center><em>There are currently no Batches in the sherry schedule for this work order.</em></center>";
}else{

	echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\" id=\"scheduledtable\">
	<thead>
		<tr>
			<th width=\"6%\" align=\"center\">Line#</th>
			<th width=\"8%\">Location</th>
			<th width=\"4%\">QTY<br>PEND</th>
			<th width=\"4%\">QTY<br>ORD</th>
			<th width=\"12%\" align=\"center\">Item</th>
			<th width=\"13%\">Description</th>
			<th width=\"53%\" align=\"center\">Sherry Batches</th>
		</tr>
	</thead>
	<tbody>";

	foreach($quoteLines as $quoteLine){
		/*$numOutstanding=0;
foreach($allUnscheduledItems as $item){
	if($item['outstanding_qty'] > 0){
		$numOutstanding=($numOutstanding + $item['outstanding_qty']);
	}
}*/
		$thisOrderItemID=0;
		//find this matching Order Item id
		foreach($orderLines as $orderLine){
			if($orderLine['quote_line_item_id'] == $quoteLine['id']){
				$thisOrderItemID=$orderLine['id'];
			}
		}

		echo "<tr class=\"lineitemrow\">
		<td width=\"6%\" align=\"center\" valign=\"top\">".$quoteLine['line_number']."</td>
		<td width=\"8%\" valign=\"top\">".$quoteLine['room_number']."</td>
		<td width=\"4%\" valign=\"top\" align=\"center\">";
		
		echo $allUnscheduledItems[$thisOrderItemID]['outstanding_qty'];
		$totalNumPending=($totalNumPending + intval($allUnscheduledItems[$thisOrderItemID]['outstanding_qty']));

		echo "</td>
		<td width=\"4%\" valign=\"top\" align=\"center\">";
		
		echo ($allUnscheduledItems[$thisOrderItemID]['scheduled'] + $allUnscheduledItems[$thisOrderItemID]['outstanding_qty']);
		
		echo "</td>
		<td width=\"12%\" valign=\"top\" align=\"center\">";
		//display item description
		echo "<b>";
		switch($quoteLine['product_type']){
			case "bedspreads":
				echo "Bedspread";
			break;
			case "cubicle_curtains":
				echo "Cubicle Curtain";
			break;
			case "window_treatments":
				echo $quoteLine['metadata']['wttype'];
			break;
			case "calculator":
				switch($quoteLine['calculator_used']){
					case "pinch-pleated":
						echo "Drapery";
					break;
					case "bedspread":
						echo "Bedspread";
					break;
					case "cubicle-curtain":
						echo "Cubicle Curtain";
					break;
					case "box-pleated":
						echo $quoteLine['metadata']['valance-type']." Valance";
					break;
					case "straight-cornice":
						echo $quoteLine['metadata']['cornice-type']." Cornice";
					break;
				}
			break;
			case "custom":
			case 'newcatchall-bedding':
    		case 'newcatchall-blinds':
            case 'newcatchall-cornice':
            case 'newcatchall-cubicle':
            case 'newcatchall-drapery':
            case 'newcatchall-hardware':
            case 'newcatchall-hwtmisc':
            case 'newcatchall-misc':
            case 'newcatchall-service':
            case 'newcatchall-shades':
            case 'newcatchall-shutters':
            case 'newcatchall-swtmisc':
            case 'newcatchall-valance':
				echo $quoteLine['title'];
			break;
			case "services":
				echo $quoteLine['title'];
			break;
			case "track_systems":
				echo "Track";
			break;
		}

		echo "</b><br>".$quoteLine['fabricdata']['fabric_name'].'&nbsp;&nbsp;&nbsp;'.$quoteLine['fabricdata']['color'];

		echo "</td>
		<td width=\"13%\" valign=\"top\" align=\"center\">";



        if($quoteLine['lineitemtype'] == 'newcatchall'){
            echo $quoteLine['description'];
        }else{
    
    		if($quoteLine['product_type']=="cubicle_curtains"){
    
    			echo $quoteLine['metadata']['width']."&quot; CW x ".$quoteLine['metadata']['length']."&quot; FL<br>";
    
    			if(isset($quoteLine['metadata']['expected-finish-width']) && floatval($quoteLine['metadata']['expected-finish-width']) >0){
    				echo $quoteLine['metadata']['expected-finish-width'].'&quot; Finished Width<br>';
    			}else{
    				echo "(approx ";
    
    				if(floatval($quoteLine['metadata']['width']) == 54){
    					echo "46&quot;-49&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 72){
    					echo "66&quot;-68&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 90){
    					echo "82&quot;-84&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 108){
    					echo "100&quot;-102&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 126){
    					echo "114&quot;-117&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 144){
    					echo "134&quot;-136&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 162){
    					echo "150&quot;-152&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 180){
    					echo "166&quot;-168&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 198){
    					echo "182&quot;-186&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 216){
    					echo "202&quot;-204&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 234){
    					echo "218&quot;-220&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 252){
    					echo "236&quot;-238&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 270){
    					echo "248&quot;-250&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 288){
    					echo "273&quot;-275&quot;";
    				}
    
    				echo " FW)";
    			}
    
    		}elseif($quoteLine['product_type']=="calculator" && $quoteLine['metadata']['calculator-used']=='cubicle-curtain'){
    
    			echo $quoteLine['metadata']['width']."&quot; CW x ".$quoteLine['metadata']['length']."&quot; FL<br>(";
    			if(isset($quoteLine['metadata']['expected-finish-width']) && ($quoteLine['metadata']['expected-finish-width'] != '0' && strlen(trim($quoteLine['metadata']['expected-finish-width'])) >0)){
    				echo $quoteLine['metadata']['expected-finish-width']."&quot; FW";
    			}else{
    				echo "approx ".$quoteLine['metadata']['fw']."&quot; FW";
    			}
    			echo ")";
    
    			/*
    			if($quoteLine['metadata']['expected-finish-width']=='0' || $quoteLine['metadata']['fw']==''){
    				echo $quoteLine['metadata']['width']."&quot; CW x ".$quoteLine['metadata']['length']."&quot; FL<br>(approx ".$quoteLine['metadata']['fw']."&quot; FW)";
    			}else{
    				echo $quoteLine['metadata']['width']."&quot; CW x ".$quoteLine['metadata']['length']."&quot; FL<br>(".$quoteLine['metadata']['expected-finish-width']."&quot; FW)";
    			}
    			*/
    		}elseif(($quoteLine['product_type']=="window_treatments" && $quoteLine['metadata']['wttype'] == 'Pinch Pleated Drapery') || ($quoteLine['metadata']['calculator-used']=='pinch-pleated')){
    
    			if($quoteLine['metadata']['unit-of-measure'] == 'pair'){
    				echo "Rod W: ".$quoteLine['metadata']['rod-width']." x L: ".$quoteLine['metadata']['length'];
    			}else{
    				echo $quoteLine['metadata']['fabric-widths-per-panel']." Widths x L: ".$quoteLine['metadata']['length'];
    			}
    
    		}else{
    
    			if(isset($quoteLine['metadata']['width']) && isset($quoteLine['metadata']['length'])){
    				echo "W: ".$quoteLine['metadata']['width'].'&quot; x L: '.$quoteLine['metadata']['length'].'&quot;';
    			}
    			if(isset($quoteLine['metadata']['face']) && isset($quoteLine['metadata']['height'])){
    				echo "F: ".$quoteLine['metadata']['face']."&quot; x H: ".$quoteLine['metadata']['height']."&quot;";
    			}
    		}
        
        }
        
        

		if($quoteLine['product_type'] == 'bedspreads' || ($quoteLine['product_type']=="calculator" && ($quoteLine['metadata']['calculator-used']=='bedspread' || $quoteLine['metadata']['calculator-used']=='bedspread-manual'))){

			echo "<br>Mattress: ";
			if(!isset($quoteLine['metadata']['custom-top-width-mattress-w'])){
				echo "36&quot;";
			}else{
				echo $quoteLine['metadata']['custom-top-width-mattress-w']."&quot;";
			}

		}






		echo "</td>
		<td width=\"53%\" valign=\"top\">";
		//build batches table

		echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" class=\"batchinnertable\">
		<thead>
		<tr>
			<th width=\"10%\">Batch #</th>
			<th width=\"10%\">Date</th>
			<th width=\"10%\">QTY Sched</th>
			<th width=\"13%\">Status</th>
			<th width=\"12%\">Date</th>
			<th width=\"35%\">Carrier / Tracking #</th>
			<th width=\"10%\">Actions</th>
		</tr>
		</thead><tbody>";
		foreach($allScheduledItems as $thisScheduledOrderLineItemID => $scheduledItemRow){
			if($thisScheduledOrderLineItemID == $thisOrderItemID){
				
				foreach($scheduledItemRow as $batchID => $batchTotals){
					echo "<tr class=\"batchrow";
                    if(count($allInvoicedItems[$thisScheduledOrderLineItemID]) > 0){
                        foreach($allInvoicedItems[$thisScheduledOrderLineItemID] as $invoicedBatch => $invData){
                            if($invoicedBatch == $batchID){
                                echo " invoicedbatch";
                            }
					    }
                    }
					echo "\"><td width=\"10%\">";
					echo $batchID;
					echo "</td>
					<td width=\"10%\">";
					echo $batchTotals['date'];
					echo "</td>
					<td width=\"10%\">";
					echo $batchTotals['qty'];
					echo "</td>
					<td width=\"13%\"";

					$isShipped=0;
					$isCompleted=0;
					$isScheduled=0;

					//is it completed?
					if(isset($allCompletedItems[$thisScheduledOrderLineItemID][$batchID]) && intval($allCompletedItems[$thisScheduledOrderLineItemID][$batchID]['qty']) == intval($batchTotals['qty'])){
						//is it shipped?

						if(isset($allShippedItems[$thisScheduledOrderLineItemID][$batchID]) && intval($allShippedItems[$thisScheduledOrderLineItemID][$batchID]['qty']) == intval($batchTotals['qty'])){
							echo " class=\"shippeditem\">SHIPPED";
							echo "</td><td width=\"12%\">";
							if(isset($allShippedItems[$thisScheduledOrderLineItemID][$batchID]['shipped_date']) && is_numeric($allShippedItems[$thisScheduledOrderLineItemID][$batchID]['shipped_date']) && intval($allShippedItems[$thisScheduledOrderLineItemID][$batchID]['shipped_date']) > 1000){
								echo date('m/d/Y',$allShippedItems[$thisScheduledOrderLineItemID][$batchID]['shipped_date']);
							}else{
								echo "---";
							}
							$isShipped++;
							$isCompleted++;
							$isScheduled++;
						}else{
							echo " class=\"completeditem\">COMPLETED</td><td width=\"12%\">";
							if(isset($allCompletedItems[$thisScheduledOrderLineItemID][$batchID]['completion_date']) && is_numeric($allCompletedItems[$thisScheduledOrderLineItemID][$batchID]['completion_date']) && intval($allCompletedItems[$thisScheduledOrderLineItemID][$batchID]['completion_date']) > 1000){
								echo date('m/d/Y',$allCompletedItems[$thisScheduledOrderLineItemID][$batchID]['completion_date']);
							}else{
								echo "---";
							}
							$isCompleted++;
							$isScheduled++;
						}
					}else{
						echo " class=\"scheduleditem\">SCHEDULED</td><td width=\"12%\">".$batchTotals['date'];
						$isScheduled++;
					}
					echo "</td>
					<td width=\"35%\">";

					//figure out if some warnings are needed here?
					if(time() > strtotime($batchTotals['date'].' 18:00:00') && $isShipped==0 && $isCompleted==0){
						echo "<span style=\"color:red;font-weight:bold;\"><img src=\"/img/alert.png\" /> Failed To Produce</span>";
					}elseif(time() > strtotime($batchTotals['date'].' 18:00:00') && $isShipped==0 && $isCompleted >0){
						echo "<span style=\"color:red;font-weight:bold;\"><img src=\"/img/alert.png\" /> Failed To Ship</span>";
					}else{

						if(isset($allShippedItems[$thisScheduledOrderLineItemID][$batchID]) && intval($allShippedItems[$thisScheduledOrderLineItemID][$batchID]['qty']) == intval($batchTotals['qty'])){
							
							foreach($allShipMethods as $shipMethod){
								if($shipMethod['id'] == $thisOrder['shipping_method_id']){
									if(strlen(trim($shipMethod['tracking_link_base'])) > 0){
										echo $allShippedItems[$thisScheduledOrderLineItemID][$batchID]['carrier']." &nbsp;&nbsp;&nbsp; <a href=\"".$shipMethod['tracking_link_base'].$allShippedItems[$thisScheduledOrderLineItemID][$batchID]['tracking']."\" target=\"_blank\">".$allShippedItems[$thisScheduledOrderLineItemID][$batchID]['tracking']."</a>";
									}else{
										echo $allShippedItems[$thisScheduledOrderLineItemID][$batchID]['carrier']." &nbsp;&nbsp;&nbsp; ".$allShippedItems[$thisScheduledOrderLineItemID][$batchID]['tracking'];
									}
								}
							}
							
						}else{

							if(!is_null($thisOrder['due']) && strlen(trim($thisOrder['due'])) >0 && intval($thisOrder['due']) > 1000 && strtotime($batchTotals['date'].' 12:00:00') > strtotime(date('Y-m-d',$thisOrder['due']).' 18:00:00')){
								echo "<span style=\"color:red;font-weight:bold;\"><img src=\"/img/alert.png\" /> Sched After Ship-By Date</span>";
							}else{

								echo "&nbsp;";
							}
						}
					}

					echo "</td>
					<td width=\"10%\">";
					if($isScheduled >0  && $isShipped == 0 && $isCompleted==0){
						echo "<a href=\"/orders/editschedule/".$batchID."\"><img src=\"/img/edit.png\" alt=\"Edit This Batch\" title=\"Edit This Batch\" /></a> ";
					}
					
					if($isScheduled > 0 && $isCompleted==0 && $isShipped==0){
						echo "<a href=\"javascript:markitemcompleted(".$batchID.");\"><img src=\"/img/completed.png\" alt=\"Mark This Batch As Produced (Not Yet Shipped)\" title=\"Mark This Batch As Produced (Not Yet Shipped)\" /></a> ";
					}

					if($isScheduled > 0 && $isCompleted > 0 && $isShipped==0){
						echo "<a href=\"javascript:markitemshipped(".$batchID.")\"><img src=\"/img/Transport-Truck-icon.png\" alt=\"Mark This Batch As Shipped\" title=\"Mark This Batch As Shipped\" /></a> ";
					}

					if($isScheduled > 0 && $isCompleted > 0 && $isShipped > 0){
						echo "<a href=\"javascript:voidShipmentBatch(".$batchID.");\"><img src=\"/img/void.png\" alt=\"Void This Shipment, Revert to Completed\" title=\"Void This Shipment, Revert to Completed\" /></a> ";
					}
					
					if($isScheduled > 0 && $isCompleted > 0 && $isShipped == 0){
						echo "<a href=\"javascript:voidBatchCompletion(".$batchID.");\"><img src=\"/img/void.png\" alt=\"Void This Completion, Revert to Scheduled\" title=\"Void This Completion, Revert to Scheduled\" /></a>";
					}
					
					echo "<a href=\"javascript:addBatchNote(".$batchID.");\"><img src=\"/img/stickynote.png\" alt=\"Add Note To This Batch\" title=\"Add Note To This Batch\" /></a> ";

					echo "</td>
					</tr>";


					//batch notes go here!
					foreach($allBatchesThisWO as $batchEntryID => $batchEntry){
						if($batchEntryID == $batchID){
							foreach($batchEntry['notes'] as $batchEntryNote){
								$noteTypeSplit=explode(" NOTE:",$batchEntryNote);
								$notetype=strtolower($noteTypeSplit[0]);
								echo "<tr class=\"batchnoterow batch".$notetype."row\">
								<td colspan=\"7\" align=\"left\" valign=\"top\">".$batchEntryNote."</td>
								</tr>";
							}
						}
					}

				}
			}
		}
		echo "</tbody>
		</table>";

		echo "</td>

		</tr>";

		//if there are line notes, add them here
		if(count($quoteLine['linenotes']) >0){
			echo "<tr class=\"linenotesheading\"><th colspan=\"7\">Line ".$quoteLine['line_number']." Notes:</th></tr>";

			foreach($quoteLine['linenotes'] as $lineNote){
				echo "<tr class=\"linenoterow\">
				<td colspan=\"7\" valign=\"top\" align=\"left\">";
				foreach($allUsers as $user){
					if($user['id'] == $lineNote['user_id']){
						echo "<b>".$user['first_name']." ".substr($user['last_name'],0,1).":</b> ";
					}
				}
					echo $lineNote['message']."</td>
				</tr>";
			}
		}
	}

	echo "</tbody></table>";



///////////////////////////////////////////////////////////////




echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\" id=\"scheduledtablewithboxes\">
	<thead>
		<tr>
			<th width=\"4%\" align=\"center\">Line#</th>
			<th width=\"6%\">Location</th>
			<th width=\"4%\">QTY<br>PEND</th>
			<th width=\"4%\">QTY<br>ORD</th>
			<th width=\"11%\" align=\"center\">Item</th>
			<th width=\"11%\">Description</th>
			<th width=\"60%\" align=\"center\">Sherry Batches</th>
		</tr>
	</thead>
	<tbody>";

	foreach($quoteLines as $quoteLine){
		/*$numOutstanding=0;
foreach($allUnscheduledItems as $item){
	if($item['outstanding_qty'] > 0){
		$numOutstanding=($numOutstanding + $item['outstanding_qty']);
	}
}*/
		$thisOrderItemID=0;
		//find this matching Order Item id
		foreach($orderLines as $orderLine){
			if($orderLine['quote_line_item_id'] == $quoteLine['id']){
				$thisOrderItemID=$orderLine['id'];
			}
		}

		echo "<tr class=\"lineitemrow\">
		<td width=\"4%\" align=\"center\" valign=\"top\">".$quoteLine['line_number']."</td>
		<td width=\"6%\" valign=\"top\">".$quoteLine['room_number']."</td>
		<td width=\"4%\" valign=\"top\" align=\"center\">";
		
		echo $allUnscheduledItems[$thisOrderItemID]['outstanding_qty'];
		$totalNumPending=($totalNumPending + intval($allUnscheduledItems[$thisOrderItemID]['outstanding_qty']));

		echo "</td>
		<td width=\"4%\" valign=\"top\" align=\"center\">";
		
		echo ($allUnscheduledItems[$thisOrderItemID]['scheduled'] + $allUnscheduledItems[$thisOrderItemID]['outstanding_qty']);
		
		echo "</td>
		<td width=\"11%\" valign=\"top\" align=\"center\">";
		//display item description
		echo "<b>";
		switch($quoteLine['product_type']){
			case "bedspreads":
				echo "Bedspread";
			break;
			case "cubicle_curtains":
				echo "Cubicle Curtain";
			break;
			case "window_treatments":
				echo $quoteLine['metadata']['wttype'];
			break;
			case "calculator":
				switch($quoteLine['calculator_used']){
					case "pinch-pleated":
						echo "Drapery";
					break;
					case "bedspread":
						echo "Bedspread";
					break;
					case "cubicle-curtain":
						echo "Cubicle Curtain";
					break;
					case "box-pleated":
						echo $quoteLine['metadata']['valance-type']." Valance";
					break;
					case "straight-cornice":
						echo $quoteLine['metadata']['cornice-type']." Cornice";
					break;
				}
			break;
			case 'newcatchall-bedding':
    		case 'newcatchall-blinds':
            case 'newcatchall-cornice':
            case 'newcatchall-cubicle':
            case 'newcatchall-drapery':
            case 'newcatchall-hardware':
            case 'newcatchall-hwtmisc':
            case 'newcatchall-misc':
            case 'newcatchall-service':
            case 'newcatchall-shades':
            case 'newcatchall-shutters':
            case 'newcatchall-swtmisc':
            case 'newcatchall-valance':
			case "custom":
				echo $quoteLine['title'];
			break;
			case "services":
				echo $quoteLine['title'];
			break;
			case "track_systems":
				echo "Track";
			break;
		}

		echo "</b><br>".$quoteLine['fabricdata']['fabric_name'].'&nbsp;&nbsp;&nbsp;'.$quoteLine['fabricdata']['color'];

		echo "</td>
		<td width=\"11%\" valign=\"top\" align=\"center\">";


        if($quoteLine['lineitemtype'] == 'newcatchall'){
            echo $quoteLine['description'];
        }else{



    		if($quoteLine['product_type']=="cubicle_curtains"){
    
    			echo $quoteLine['metadata']['width']."&quot; CW x ".$quoteLine['metadata']['length']."&quot; FL<br>";
    
    			if(isset($quoteLine['metadata']['expected-finish-width']) && floatval($quoteLine['metadata']['expected-finish-width']) >0){
    				echo $quoteLine['metadata']['expected-finish-width'].'&quot; Finished Width<br>';
    			}else{
    				echo "(approx ";
    
    				if(floatval($quoteLine['metadata']['width']) == 54){
    					echo "46&quot;-49&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 72){
    					echo "66&quot;-68&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 90){
    					echo "82&quot;-84&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 108){
    					echo "100&quot;-102&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 126){
    					echo "114&quot;-117&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 144){
    					echo "134&quot;-136&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 162){
    					echo "150&quot;-152&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 180){
    					echo "166&quot;-168&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 198){
    					echo "182&quot;-186&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 216){
    					echo "202&quot;-204&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 234){
    					echo "218&quot;-220&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 252){
    					echo "236&quot;-238&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 270){
    					echo "248&quot;-250&quot;";
    				}elseif(floatval($quoteLine['metadata']['width']) == 288){
    					echo "273&quot;-275&quot;";
    				}
    
    				echo " FW)";
    			}
    
    		}elseif($quoteLine['product_type']=="calculator" && $quoteLine['metadata']['calculator-used']=='cubicle-curtain'){
    
    			echo $quoteLine['metadata']['width']."&quot; CW x ".$quoteLine['metadata']['length']."&quot; FL<br>(";
    			if(isset($quoteLine['metadata']['expected-finish-width']) && ($quoteLine['metadata']['expected-finish-width'] != '0' && strlen(trim($quoteLine['metadata']['expected-finish-width'])) >0)){
    				echo $quoteLine['metadata']['expected-finish-width']."&quot; FW";
    			}else{
    				echo "approx ".$quoteLine['metadata']['fw']."&quot; FW";
    			}
    			echo ")";
    
    			/*
    			if($quoteLine['metadata']['expected-finish-width']=='0' || $quoteLine['metadata']['fw']==''){
    				echo $quoteLine['metadata']['width']."&quot; CW x ".$quoteLine['metadata']['length']."&quot; FL<br>(approx ".$quoteLine['metadata']['fw']."&quot; FW)";
    			}else{
    				echo $quoteLine['metadata']['width']."&quot; CW x ".$quoteLine['metadata']['length']."&quot; FL<br>(".$quoteLine['metadata']['expected-finish-width']."&quot; FW)";
    			}
    			*/
    		}elseif(($quoteLine['product_type']=="window_treatments" && $quoteLine['metadata']['wttype'] == 'Pinch Pleated Drapery') || ($quoteLine['metadata']['calculator-used']=='pinch-pleated')){
    
    			if($quoteLine['metadata']['unit-of-measure'] == 'pair'){
    				echo "Rod W: ".$quoteLine['metadata']['rod-width']." x L: ".$quoteLine['metadata']['length'];
    			}else{
    				echo $quoteLine['metadata']['fabric-widths-per-panel']." Widths x L: ".$quoteLine['metadata']['length'];
    			}
    
    		}else{
    
    			if(isset($quoteLine['metadata']['width']) && isset($quoteLine['metadata']['length'])){
    				echo "W: ".$quoteLine['metadata']['width'].'&quot; x L: '.$quoteLine['metadata']['length'].'&quot;';
    			}
    			if(isset($quoteLine['metadata']['face']) && isset($quoteLine['metadata']['height'])){
    				echo "F: ".$quoteLine['metadata']['face']."&quot; x H: ".$quoteLine['metadata']['height']."&quot;";
    			}
    		}
        }


		if($quoteLine['product_type'] == 'bedspreads' || ($quoteLine['product_type']=="calculator" && ($quoteLine['metadata']['calculator-used']=='bedspread' || $quoteLine['metadata']['calculator-used']=='bedspread-manual'))){

			echo "<br>Mattress: ";
			if(!isset($quoteLine['metadata']['custom-top-width-mattress-w'])){
				echo "36&quot;";
			}else{
				echo $quoteLine['metadata']['custom-top-width-mattress-w']."&quot;";
			}

		}






		echo "</td>
		<td width=\"60%\" valign=\"top\">";
		//build batches table

		echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" class=\"batchinnertable\">
		<thead>
		<tr>
			<th width=\"9%\">Batch #</th>
			<th width=\"10%\">Date</th>
			<th width=\"9%\">QTY Sched</th>
			<th width=\"12%\">Status</th>
			<th width=\"22%\">Boxes</th>
			<th width=\"10%\">Date</th>
			<th width=\"20%\">Carrier / Tracking #</th>
			<th width=\"8%\">Actions</th>
		</tr>
		</thead><tbody>";
		foreach($allScheduledItems as $thisScheduledOrderLineItemID => $scheduledItemRow){
			if($thisScheduledOrderLineItemID == $thisOrderItemID){
				
				foreach($scheduledItemRow as $batchID => $batchTotals){

				    
					echo "<tr class=\"batchrow";
                    if(count($allInvoicedItems[$thisScheduledOrderLineItemID]) > 0){
                        foreach($allInvoicedItems[$thisScheduledOrderLineItemID] as $invoicedBatch => $invData){
                            if($invoicedBatch == $batchID){
                                echo " invoicedbatch";
                            }
					    }
                    }
					echo "\">
					<td width=\"9%\" valign=\"top\">";
					echo $batchID;
					echo "</td>
					<td width=\"10%\" valign=\"top\">";
					echo $batchTotals['date'];
					echo "</td>
					<td width=\"9%\" valign=\"top\">";
					echo $batchTotals['qty'];
					echo "</td>
					<td width=\"12%\" valign=\"top\"";

					$isShipped=0;
					$isCompleted=0;
					$isScheduled=0;
					
					
					//find boxes that contain this line item
					$batchBoxesTable='';
					foreach($quoteLine['lineboxes'] as $box){
					    if($box['batch_id'] == $batchID){
					        $batchBoxesTable .= '<b>('.$box['qty'].')</b> Box# '.$box['box_number'].'<br>';
					    }
					}
					if($batchBoxesTable != ''){
					    $batchBoxesTable = substr($batchBoxesTable,0,(strlen($batchBoxesTable)-4));
					}else{
					    $batchBoxesTable='<em>No Boxes Contain This Item</em>';
					}

					//is it completed?
					if(isset($allCompletedItems[$thisScheduledOrderLineItemID][$batchID]) && intval($allCompletedItems[$thisScheduledOrderLineItemID][$batchID]['qty']) == intval($batchTotals['qty'])){
						//is it shipped?

						if(isset($allShippedItems[$thisScheduledOrderLineItemID][$batchID]) && intval($allShippedItems[$thisScheduledOrderLineItemID][$batchID]['qty']) == intval($batchTotals['qty'])){
							echo " class=\"shippeditem\">SHIPPED";
							
							echo "</td>
							<td width=\"22%\" valign=\"top\">".$batchBoxesTable."</td>
							<td width=\"10%\" valign=\"top\">";
							if(isset($allShippedItems[$thisScheduledOrderLineItemID][$batchID]['shipped_date']) && is_numeric($allShippedItems[$thisScheduledOrderLineItemID][$batchID]['shipped_date']) && intval($allShippedItems[$thisScheduledOrderLineItemID][$batchID]['shipped_date']) > 1000){
								echo date('m/d/Y',$allShippedItems[$thisScheduledOrderLineItemID][$batchID]['shipped_date']);
							}else{
								echo "---";
							}
							$isShipped++;
							$isCompleted++;
							$isScheduled++;
						}else{
							echo " class=\"completeditem\">COMPLETED</td>
							<td width=\"22%\" valign=\"top\">".$batchBoxesTable."</td>
							<td width=\"10%\" valign=\"top\">";
							if(isset($allCompletedItems[$thisScheduledOrderLineItemID][$batchID]['completion_date']) && is_numeric($allCompletedItems[$thisScheduledOrderLineItemID][$batchID]['completion_date']) && intval($allCompletedItems[$thisScheduledOrderLineItemID][$batchID]['completion_date']) > 1000){
								echo date('m/d/Y',$allCompletedItems[$thisScheduledOrderLineItemID][$batchID]['completion_date']);
							}else{
								echo "---";
							}
							$isCompleted++;
							$isScheduled++;
						}
						
						
					}else{
						echo " class=\"scheduleditem\">SCHEDULED</td>
						<td width=\"22%\" valign=\"top\">".$batchBoxesTable."</td>
						<td width=\"10%\" valign=\"top\">".$batchTotals['date'];
						$isScheduled++;
					}
					echo "</td>
					
					<td width=\"20%\" valign=\"top\">";

					//figure out if some warnings are needed here?
					if(time() > strtotime($batchTotals['date'].' 18:00:00') && $isShipped==0 && $isCompleted==0){
						echo "<span style=\"color:red;font-weight:bold;\"><img src=\"/img/alert.png\" /> Failed To Produce</span>";
					}elseif(time() > strtotime($batchTotals['date'].' 18:00:00') && $isShipped==0 && $isCompleted >0){
						echo "<span style=\"color:red;font-weight:bold;\"><img src=\"/img/alert.png\" /> Failed To Ship</span>";
					}else{

						if(isset($allShippedItems[$thisScheduledOrderLineItemID][$batchID]) && intval($allShippedItems[$thisScheduledOrderLineItemID][$batchID]['qty']) == intval($batchTotals['qty'])){
							
							foreach($allShipMethods as $shipMethod){
								if($shipMethod['id'] == $thisOrder['shipping_method_id']){
									if(strlen(trim($shipMethod['tracking_link_base'])) > 0){
										echo $allShippedItems[$thisScheduledOrderLineItemID][$batchID]['carrier']." &nbsp;&nbsp;&nbsp; <a href=\"".$shipMethod['tracking_link_base'].$allShippedItems[$thisScheduledOrderLineItemID][$batchID]['tracking']."\" target=\"_blank\">".$allShippedItems[$thisScheduledOrderLineItemID][$batchID]['tracking']."</a>";
									}else{
										echo $allShippedItems[$thisScheduledOrderLineItemID][$batchID]['carrier']." &nbsp;&nbsp;&nbsp; ".$allShippedItems[$thisScheduledOrderLineItemID][$batchID]['tracking'];
									}
								}
							}
							
						}else{

							if(!is_null($thisOrder['due']) && strlen(trim($thisOrder['due'])) >0 && intval($thisOrder['due']) > 1000 && strtotime($batchTotals['date'].' 12:00:00') > strtotime(date('Y-m-d',$thisOrder['due']).' 18:00:00')){
								echo "<span style=\"color:red;font-weight:bold;\"><img src=\"/img/alert.png\" /> Sched After Ship-By Date</span>";
							}else{

								echo "&nbsp;";
							}
						}
					}

					echo "</td>
					<td width=\"8%\" valign=\"top\">";
					if($isScheduled >0  && $isShipped == 0 && $isCompleted==0){
						echo "<a href=\"/orders/editschedule/".$batchID."\"><img src=\"/img/edit.png\" alt=\"Edit This Batch\" title=\"Edit This Batch\" /></a> ";
					}
					
					if($isScheduled > 0 && $isCompleted==0 && $isShipped==0){
						echo "<a href=\"javascript:markitemcompleted(".$batchID.");\"><img src=\"/img/completed.png\" alt=\"Mark This Batch As Produced (Not Yet Shipped)\" title=\"Mark This Batch As Produced (Not Yet Shipped)\" /></a> ";
					}

					if($isScheduled > 0 && $isCompleted > 0 && $isShipped==0){
						echo "<a href=\"javascript:markitemshipped(".$batchID.")\"><img src=\"/img/Transport-Truck-icon.png\" alt=\"Mark This Batch As Shipped\" title=\"Mark This Batch As Shipped\" /></a> ";
					}

					if($isScheduled > 0 && $isCompleted > 0 && $isShipped > 0){
						echo "<a href=\"javascript:voidShipmentBatch(".$batchID.");\"><img src=\"/img/void.png\" alt=\"Void This Shipment, Revert to Completed\" title=\"Void This Shipment, Revert to Completed\" /></a> ";
					}
					
					if($isScheduled > 0 && $isCompleted > 0 && $isShipped == 0){
						echo "<a href=\"javascript:voidBatchCompletion(".$batchID.");\"><img src=\"/img/void.png\" alt=\"Void This Completion, Revert to Scheduled\" title=\"Void This Completion, Revert to Scheduled\" /></a>";
					}
					
					echo "<a href=\"javascript:addBatchNote(".$batchID.");\"><img src=\"/img/stickynote.png\" alt=\"Add Note To This Batch\" title=\"Add Note To This Batch\" /></a> ";

					echo "</td>
					</tr>";


					//batch notes go here!
					foreach($allBatchesThisWO as $batchEntryID => $batchEntry){
						if($batchEntryID == $batchID){
							foreach($batchEntry['notes'] as $batchEntryNote){
								$noteTypeSplit=explode(" NOTE:",$batchEntryNote);
								$notetype=strtolower($noteTypeSplit[0]);
								echo "<tr class=\"batchnoterow batch".$notetype."row\">
								<td colspan=\"7\" align=\"left\" valign=\"top\">".$batchEntryNote."</td>
								</tr>";
							}
						}
					}

				}
			}
		}
		echo "</tbody>
		</table>";

		echo "</td>

		</tr>";

		//if there are line notes, add them here
		if(count($quoteLine['linenotes']) >0){
			echo "<tr class=\"linenotesheading\"><th colspan=\"7\">Line ".$quoteLine['line_number']." Notes:</th></tr>";

			foreach($quoteLine['linenotes'] as $lineNote){
				echo "<tr class=\"linenoterow\">
				<td colspan=\"7\" valign=\"top\" align=\"left\">";
				foreach($allUsers as $user){
					if($user['id'] == $lineNote['user_id']){
						echo "<b>".$user['first_name']." ".substr($user['last_name'],0,1).":</b> ";
					}
				}
					echo $lineNote['message']."</td>
				</tr>";
			}
		}
	}

	echo "</tbody></table>";



    echo "<br><br><br><br>";
















///////////////////////////////////////////////////////////////











	if($totalNumPending == 0){
		echo "<script>$('#mainpastduealert').remove();</script>";
	}
}



?>