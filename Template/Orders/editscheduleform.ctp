<?php
$dateFormatted=$date;

echo $this->Form->create(null);

    if($mode=='editdateonly'){
        echo '<h2 style="font-size:30px;margin:10px 0; color:blue; text-align:center;">WO# '.$orderData['order_number'].' CHANGE DATE ON <u>BATCH ID# '.$thisBatch['id'].'</u>';
    }else{
        echo '<h2 style="font-size:30px;margin:10px 0; color:blue; text-align:center;">WO# '.$orderData['order_number'].' EDIT SHERRY <u>BATCH ID# '.$thisBatch['id'].'</u>';
    }
    if(!is_null($orderData['due']) && intval($orderData['due']) > 1000){
	    echo ' - SHIP-BY DATE: '.date('n/j/Y',$orderData['due']);
    }
    echo '</h2>';


//echo "<pre style=\"text-align:left;\">"; print_r($batchBoxes); echo "</pre>";



    echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);
    echo $this->Form->input('dateselection',['type'=>'hidden','value'=>$dateFormatted]);
    echo $this->Form->input('batchitemcount',['type'=>'hidden','value'=>$batchitemcount]);

echo "<table id=\"schedulebuild\" width=\"1400\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
echo "<thead>
<tr>
<th width=\"12%\">LINE</th>
<th width=\"8%\">QTY<br>ORDERED</th>
<th width=\"8%\">OTHER<br>BATCHES</th>
<th width=\"8%\">REMAINING<br>UNSCHEDULED</th>
<th width=\"8%\">QTY THIS<br>BATCH</th>
<th width=\"30%\" colspan=\"4\">DESCRIPTION</th>
<th width=\"21%\">CALENDAR</th>
</tr>
</thead>";

echo "<tbody>";

$i=1;
foreach($lineItems as $itemID => $itemData){
	//if($itemData['data']['enable_tally'] == 1){

	echo "<tr data-order-line-id=\"".$itemData['order_item_id']."\" data-qtyordered=\"".$itemData['data']['qty']."\" data-previouslyscheduled=\"".$itemData['data']['this_batch']."\">
	<td width=\"12%\" style=\"vertical-align:middle;\">".$itemData['data']['line_number'];
	
	echo "</td>
	<td width=\"8%\" class=\"qtyordered\" style=\"vertical-align:middle;\">".floatval($itemData['data']['qty'])."</td>
	<td width=\"8%\" class=\"otherbatches\" style=\"vertical-align:middle;\">".floatval($itemData['other_batches'])."</td>
	<td width=\"8%\" class=\"remainingunscheduled\" style=\"vertical-align:middle;\">".floatval($itemData['remaining_unscheduled'])."</td>
	<td width=\"8%\" class=\"thisbatch\" style=\"vertical-align:middle;\" data-alreadyboxedthisline=\"";
	
	//loop through the existing boxes and figure out how many of this line item are already boxed
	$boxedThisLine=0;
	foreach($batchBoxes as $box){
	    foreach($box['contents'] as $content){
	        if($content['line_number'] == $itemData['data']['line_number'] && ($content['quote_item_id'] == $itemData['data']['order_item_id'])){ //PPSASCRUM-290
	            $boxedThisLine=($boxedThisLine + intval($content['qty']));
	        }
	    }
	}
	
	echo $boxedThisLine;
	echo "\">";
	
	if($mode=='editdateonly'){
	    $isReadonly=true;
	}else{
	    $isReadonly=false;
	}
	if(floatval($itemData['this_batch']) > 0){
	    $min = 1;
	}else{
	    $min =0;
	}

//	print_r($exisitingSheduleLineItems);
	echo $this->Form->input('qty_line_'.$itemID,['label'=>false,'readonly'=>$isReadonly,'type'=>'number','min'=>0,'max'=>(floatval($itemData['data']['qty'])-floatval($itemData['other_batches'])),'value'=>floatval($itemData['this_batch'])]);

	echo "</td>
	<td width=\"9%\" style=\"vertical-align:middle;\">".$itemData['fabricdata']['fabric_name']."</td>
	<td width=\"7%\" style=\"vertical-align:middle;\">".$itemData['fabricdata']['color']."</td>
	<td width=\"7%\" style=\"vertical-align:middle;\">".$itemData['metadata']['width']." x ".$itemData['metadata']['length']."</td>
	<td width=\"7%\" style=\"vertical-align:middle;\">";
	switch($itemData['data']['product_type']){
		case "bedspreads":
			echo "Bedspread";
		break;
		case "cubicle_curtains":
			echo "Cubicle Curtain";
		break;
		case "window_treatments":
			echo $itemData['metadata']['wttype'];
		break;
		case "calculator":
			switch($itemData['data']['calculator_used']){
				case "pinch-pleated":
					echo "Pinch Pleated Drapery";
				break;
				case "bedspread":
					echo "Bedspread";
				break;
				case "cubicle-curtain":
					echo "Cubicle Curtain";
				break;
				case "box-pleated":
					echo $itemData['metadata']['valance-type']." Valance";
				break;
				case "straight-cornice":
					echo $itemData['metadata']['cornice-type']." Cornice";
				break;
			}
		break;
	}
	echo "</td>";
	if($i==1){
		echo "<td width=\"21%\" valign=\"middle\" rowspan=\"".count($lineItems)."\" style=\"vertical-align:top;\">";
		echo '<div id="datepicker"';
		echo '></div>';
		echo "</td>";
	}
	echo "</tr>";
	$i++;
	//}
}

echo "</tbody>";
echo "</table>";

echo "<div style=\"position:relative;\">";
echo "<div id=\"changeallnotice\"><b>IMPORTANT:</b> You are about to change the date on this entire batch. This will move all <span class=\"greenhighlighted\">green highlighted items</span> in this batch to the new date <span id=\"newdatevalue\"></span>.</div>";
/**PPSASCRUM-3 start **/
echo  ($customerData['on_credit_hold']) ? '<div style="padding: 0px 351px 0px 0px;text-align: right;font-size: 20px;font-weight: bold;"><span style="color: red;"> On Credit Hold</span> </div> ' : '';
/**PPSASCRUM-3 end **/
echo $this->Form->submit('Save Schedule Change(s)');


echo "</div>";

echo "<div id=\"schedulealertboxwrap\"><div id=\"schedulealertbox\"><img src=\"/img/alert.png\" /> Selected date occurs after<br />the order's Ship-By Date</div></div>";


    echo $this->Form->end();

?>
<div id="errorslist" style="display:none;"></div>

<style>
div#schedulealertboxwrap{ 
	<?php 
	if((strtotime($thisBatch['date'].' 18:00:00') <= strtotime(date('Y-m-d',$orderData['due']).' 18:00:00')) || !is_null($orderData['due']) || intval($orderData['due']) < 1000){
	?>
	display:none;
	<?php 
	}else{
	?>
	display:block;
	<?php
	}
	?>
	text-align:right;
	margin-top:10px;
	padding-bottom:55px; 
}
div#schedulealertbox{ display:inline-block; background:#FF6A00; padding:8px 20px; border:2px solid #FF5A5B; color:#000; font-weight:bold; font-size:14px; text-align:center; }


#errorslist{ position:fixed; bottom:0; right:0; width:500px; background:#FFDBDB; border:2px solid red; padding:8px; font-size:14px; }

span.greenhighlighted{ background:rgba(0,255,0,0.2); padding:3px; }
#changeallnotice{ display:none; position:absolute; z-index:5555; background:#EFECB4; border:2px solid red; padding:15px; font-size:14px; text-align:center; top:27px; left:0; }

#changeallnotice b{ color:red; }
	
#changeallnotice span#newdatevalue{ font-weight:bold; color:blue; text-decoration: underline; }
	
#content div.row{ max-width:none !important; }
#packslipnumberwrap div.input label{ display:inline-block !important; width:30%; text-align:right; padding-right:15px; font-weight: bold; }
#packslipnumberwrap div.input input{ display:inline-block !important; width:38%; text-align:left; margin-right:20%; }
	
form{ text-align: center; padding:0 0 35px 0; width:1400px; margin:0 auto; }
table#schedulebuild{ width:1400px !important; margin:0 auto; }
table#schedulebuild thead tr{ background:#2345A0; }
table#schedulebuild thead tr th{ color:#FFF; }
table#schedulebuild th,
table#schedulebuild td{ padding:5px !important; text-align:center; }

table#schedulebuild > tbody > tr > td:nth-of-type(6),
table#schedulebuild > tbody > tr > td:nth-of-type(7),
table#schedulebuild > tbody > tr > td:nth-of-type(8),
table#schedulebuild > tbody > tr > td:nth-of-type(9)
{ font-size:11px !important; }

table#schedulebuild tbody tr{ background:#FFFFFF; }

table#schedulebuild tr{ border-bottom:inherit !important; }
	
table#schedulebuild tbody tr.allcompleted{ background: #5DA654 !important; }
table#schedulebuild tbody tr.allcompleted input{ background:rgba(255,255,255,0.4) !important; }
table#schedulebuild tbody tr.allcompleted td{ color:#FFFFFF !important; }

table#schedulebuild tbody tr.allcompleted td:last-child{ position: relative; }
table#schedulebuild tbody tr.allcompleted td{ color:#FFFFFF !important; }
table#schedulebuild tbody tr.allcompleted td:last-child:after{ 
	position:absolute;
	top:14px;
	right:-168px;
	background:#D3F3D1;
	color:#177019;
	border:2px solid #177019; 
	font-size:10px;
	font-weight:bold !important;
	content:' PACKED AND/OR SHIPPED ';
	display:block;
	z-index:555;
	width:170px;
	padding:4px;
}
	
table#schedulebuild tbody tr.allcompleted td:nth-of-type(4){ background:#34C533 !important; }
	
table#schedulebuild > thead > tr > th:nth-of-type(5),
table#schedulebuild > tbody > tr > td:nth-of-type(5){
	background:rgba(0,255,0,0.22);
}
	
table#schedulebuild tbody tr.duplicated{ background:#971618 !important; }
table#schedulebuild tbody tr.duplicated td:last-child{ position: relative; }
table#schedulebuild tbody tr.duplicated td{ color:#FFFF00 !important; }
table#schedulebuild tbody tr.duplicated td:last-child:after{ 
	position:absolute;
	top:14px;
	right:-198px;
	background:#FFFF00; 
	color:#FF0000;
	border:2px solid #FF0000; 
	font-size:10px;
	font-weight:bold !important;
	content:' DUPLICATE SHIPMENT(S) DETECTED ';
	display:block;
	z-index:555;
	width:200px;
	padding:4px;
}

table#schedulebuild tbody tr.duplicated td:nth-of-type(4){ background:#FF0000 !important; }

table#schedulebuild tbody > tr.notscheduling > td{ background:rgba(0,0,0,0.3); }
table#schedulebuild tbody tr td:nth-of-type(10){ background:#FFFFFF !important; }

table#schedulebuild tbody td:nth-of-type(1),
table#schedulebuild tbody td:nth-of-type(2),
table#schedulebuild tbody td:nth-of-type(3),
table#schedulebuild tbody td:nth-of-type(4){ font-size:16px; }
	
#selectalllines{ margin:0 0 0 0; }

#datepicker div.ui-datepicker{ width:100% !important; max-height: 270px; }
	
table.ui-datepicker-calendar thead tr th{ color:#000 !important; }

.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight{ border:1px solid #c5c5c5; background:#f6f6f6; color:#454545; }

.ui-datepicker-current-day a{
	background:#007fff; border:1px solid #003eff; color:#FFF;
}

	
.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover{
	border: 1px solid #003eff !important;
	background: #007fff !important;
	font-weight: normal !important;
	color: #ffffff !important;
}
	
form div.submit{ padding:30px 0 0 0; text-align: right; }
form div.submit input[type=submit]{ background:#2345A0; border:2px solid #000; font-size:18px !important; color:#FFFFFF; font-weight:bold; padding:10px 25px !important; }

form div.submit input[type=submit]:disabled,
form div.submit input[type=submit][disabled]{ cursor:not-allowed !important; background:#444 !important; color:#CCC !important; }
</style>
<script>	

<?php
if(!is_null($orderData['due']) && intval($orderData['due']) >1000){
?>
function checkSelectionAgainstShipDate(selectedDate){

	var selectedDateSplit=selectedDate.split('/');

	var selectedTSms = new Date(selectedDateSplit[2]+'-'+selectedDateSplit[0]+'-'+selectedDateSplit[1]).valueOf();
	var selectedTS=(selectedTSms / 1000);

	if(selectedTS > <?php echo $orderData['due']; ?>){
		$('div#schedulealertboxwrap').show();
	}else{
		$('div#schedulealertboxwrap').hide();
	}
}
<?php } ?>


function checkAgainstExistingBoxes(){
    var numerrors=0;
    var errorslist='';
    $('#schedulebuild tbody tr').each(function(i){
        if(parseInt($(this).find('td.thisbatch input[type=number]').val()) < parseInt($(this).find('td.thisbatch').attr('data-alreadyboxedthisline'))){
            numerrors++;
            errorslist += '<li>Line '+$(this).find('td:nth-of-type(1)').html()+' cannot have less than the number already Boxed ('+$(this).find('td.thisbatch').attr('data-alreadyboxedthisline')+')</li>';
        }
       
    });
    
    if(numerrors > 0){
        $('div.submit input[type=submit]').prop('disabled',true);
        $('div#errorslist').html('<b>ERRORS:</b><ul>'+errorslist+'</ul>').show();
        $('div#errorslist').attr('data-errorcount',numerrors);
    }else{
        $('div.submit input[type=submit]').prop('disabled',false);
        $('div#errorslist').html('').hide();
        $('div#errorslist').attr('data-errorcount',0);
    }
}

setInterval('checkAgainstExistingBoxes()',300);


$(function(){
	var today=new Date();
	$( "#datepicker" ).datepicker({
		minDate: new Date().setDate(today.getDate()-30),
		setDate:'<?php 
		echo $dateFormatted;
		?>',
		defaultDate:'<?php 
		echo $dateFormatted;
		?>',
		onSelect: function(dateText,instance){
			<?php if(!is_null($orderData['due']) && intval($orderData['due']) >1000){ ?>checkSelectionAgainstShipDate(dateText);<?php } ?>
			$('#dateselection').val(dateText);
		}
	});
	
	 
	
	$('form').submit(function(e){
		var numErrors=0;
		var totalCheckedRows=$('#batchitemcount').val();
		var totalRows=0;
		var totalQTYChecked=0;
		var totalQTYItems=0;

		$('#schedulebuild > tbody > tr').each(function(){
		    	totalRows++;
			totalQTYItems = (totalQTYItems + parseInt($(this).attr('data-qtyordered')));
			var thislinenumber=$(this).find('td:nth-of-type(1)').text();
			var thisqtyordered=$(this).find('td:nth-of-type(2)').text();

		    if($(this).find('input[type=number]').val() == '' || $(this).find('input[type=number]').val() == 0){
		        numErrors++;
		    }

		});
	    
		
		if($('#dateselection').val() == '' || $('#dateselection').val() == '0'){
			e.preventDefault();
			alert('You must select a date on the calendar on which to schedule the selected items.');
			return false;
		}
		
		// PPSASCRUM 169 - Validate dynamic quantity fields 
        
        let atLeastOneNonZero = false;

        $('input[name^="qty_line_"]').each(function() {
            const qtyValue = $(this).val();
            if (qtyValue !== '0' && qtyValue !== '') {
                atLeastOneNonZero = true;
            }
        });

        if (!atLeastOneNonZero) {
            e.preventDefault();
            alert('At least one quantity field must be greater than zero.');
            return false;
        }
		//PPSASCRUM end 169
		
		if($('#errorslist').attr('data-errorcount') != '0'){
		    e.preventDefault();
			alert('You must address the errors listed before continuing.');
			return false;
		}
		
		$('input[type=submit]').prop('disabled',true);
		$.fancybox.showLoading();
	});
});
	
function checkDates(){
	//console.clear();
	//console.log('datepicker date = '+$('#datepicker').datepicker({ dateFormat: 'mm/dd/yyyy' }).val()+" ||| span date = "+$('#batchdate').html());
	if($('#datepicker').datepicker({ dateFormat: 'mm/dd/yyyy' }).val() != '<?php echo $dateFormatted; ?>'){
		$('#newdatevalue').html($('#datepicker').datepicker({ dateFormat: 'mm/dd/yyyy' }).val());
		$('#changeallnotice').show();
	}else{
		$('#changeallnotice').hide();
	}
	
	$('#schedulebuild tbody td.thisbatch div.input input[type=number]').each(function(){
		var themax=parseInt($(this).parent().parent().parent().find('td.qtyordered').html());
		var otherbatches=parseInt($(this).parent().parent().parent().find('td.otherbatches').html());
		var oldUnscheduled=parseInt($(this).parent().parent().parent().find('td.remainingunscheduled').html());
		
		//console.log('themax = '+themax+' || otherbatches = '+otherbatches+' || oldunscheduled = '+oldUnscheduled);
		
		var newUnscheduled=((themax - otherbatches) - parseInt($(this).val()));
		$(this).parent().parent().parent().find('td.remainingunscheduled').html(newUnscheduled);
	});
	
}
	
function initiateDateChecker(){
	setInterval('checkDates()',200);
	
}
setTimeout('initiateDateChecker()',800);
</script>