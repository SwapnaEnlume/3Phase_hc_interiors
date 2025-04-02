<?php
if($orderData['status'] == 'Editing'){
    echo "<h3>ERROR: This order is being edited and cannot be scheduled until editing is finished or canceled.</h3>";
}else{
echo $this->Form->create(null,array('target'=>'_blank'));

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$cookieID=generateRandomString(10);
echo $this->Form->input('batch_cookie_id',['type'=>'hidden','value'=>$cookieID]);

echo '<h1 style="font-size:22px;margin:20px 0;">WO# '.$orderData['order_number'].' - SCHEDULE ';
if($ignoreTallyFlag == 1){
    echo ' ANY';
}else{
    echo ' MFG';
}
echo ' ITEM(S)';
if(!is_null($orderData['due']) && intval($orderData['due']) > 1000){
	echo ' - SHIP-BY DATE: '.date('n/j/Y',$orderData['due']);
}
echo '</h1>';
echo $this->Form->input('process',['type'=>'hidden','value'=>'step2']);

echo $this->Form->input('dateselection',['type'=>'hidden','value'=>'0']);

echo "<table id=\"scheduletablewrap\" width=\"1100\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
<thead>
<tr>
<th width=\"77%\">Lines and Quantities</th>";
echo   ($customerData['on_credit_hold']) ? '            <h4><span style="color: red;"> On Credit Hold</span><h4>' : ' ';
echo "</th>
<th width=\"23%\">Date Selection</th>
</tr>
</thead>
<tbody>
<tr>
<td width=\"77%\" valign=\"top\" align=\"left\">
<table id=\"schedulebuild\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
echo "<thead>
<tr>
<th width=\"8%\">ALL";
echo $this->Form->input('selectalllines',['type'=>'checkbox','label'=>false]);
echo "</th>
<th width=\"8%\">LINE</th>
<th width=\"14%\">QTY<br>ORDERED</th>
<th width=\"14%\">PREV<br>SCHEDULED</th>
<th width=\"14%\">QTY TO<br>SCHEDULE</th>
<th width=\"42%\" colspan=\"4\">DESCRIPTION</th>
</tr>
</thead>";

echo "<tbody>";

$i=1;
foreach($lineItems as $itemID => $itemData){
	if($itemData['data']['enable_tally'] == 1 || $ignoreTallyFlag == 1){
		echo "<tr data-quote-line-item-id=\"".$itemID."\" data-order-line-id=\"".$itemData['order_item_id']."\" data-qtyordered=\"".$itemData['data']['qty']."\" data-previouslyscheduled=\"".$itemData['data']['previously_scheduled']."\" class=\"notscheduling ";
	
	echo "\">
	<td width=\"8%\">".$this->Form->input('useline_'.$itemID,['label'=>false,'type'=>'checkbox','value'=>1])."<br><button class=\"toggletally\" type=\"button\" onclick=\"toggleThisLineVisibility('".$itemID."')\">Remove</button></td>
	<td width=\"8%\">".$itemData['data']['line_number']."</td>
	<td width=\"14%\">".floatval($itemData['data']['qty'])."</td>
	<td width=\"14%\">".floatval($itemData['previously_scheduled'])."</td>
	<td width=\"14%\">";
	echo $this->Form->input('qty_line_'.$itemID,['label'=>false,'type'=>'number','min'=>0,'max'=>(floatval($itemData['data']['qty'])-floatval($itemData['previously_scheduled'])),'disabled'=>true,'readonly'=>true]);
	echo "</td>
	<td width=\"11%\">".$itemData['fabricdata']['fabric_name']."</td>
	<td width=\"11%\">".$itemData['fabricdata']['color']."</td>
	<td width=\"10%\">".$itemData['metadata']['width']." x ".$itemData['metadata']['length']."</td>
	<td width=\"10%\">";
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
		case "custom":
			echo $itemData['metadata']['catchallcategory'];
			
		break;
		case "track_systems":
			echo "Track Systems";
		break;
		case "services":
			echo $itemData['data']['title'];
		break;
	}
	echo "</td>";
	echo "</tr>";
	$i++;
	}
}

echo "</tbody>";
echo "</table>
</td>
<td valign=\"top\" width=\"23%\" align=\"left\">";
echo '<div id="datepicker"></div>';
echo "</td>
</tr>
</tbody>
</table>";

echo $this->Form->submit('Schedule Selected Item(s)');

echo "<div id=\"schedulealertboxwrap\"><div id=\"schedulealertbox\"><img src=\"/img/alert.png\" /> Selected date occurs after<br />the order's Ship-By Date</div></div>";

echo $this->Form->end();
?>
<style>
div#schedulealertboxwrap{ display:none; text-align:right; margin-top:10px; padding-bottom:55px; }
div#schedulealertbox{ display:inline-block; background:#FF6A00; padding:8px 20px; border:2px solid #FF5A5B; color:#000; font-weight:bold; font-size:14px; text-align:center; }

button.toggletally{ font-size:10px; padding:3px 3px 3px 3px; }

#packslipnumberwrap div.input label{ display:inline-block !important; width:30%; text-align:right; padding-right:15px; font-weight: bold; }
#packslipnumberwrap div.input input{ display:inline-block !important; width:38%; text-align:left; margin-right:20%; }

#datepicker .ui-datepicker-days-cell-over.ui-datepicker-current-day.ui-datepicker-today .ui-state-highlight, 
#datepicker .ui-datepicker-days-cell-over.ui-datepicker-current-day.ui-datepicker-today .ui-widget-content .ui-state-highlight,
#datepicker .ui-datepicker-days-cell-over.ui-datepicker-current-day.ui-datepicker-today .ui-widget-header .ui-state-highlight{
	border: 1px solid #003eff !important;
    background: #007fff !important;
    font-weight: normal !important;
    color: #ffffff !important;
}

form{ text-align: center; padding:0 0 35px 0; width:1100px; margin:0 auto; }
table#schedulebuild{ margin:0 auto; }
table#schedulebuild thead tr{ background:#2345A0; }
table#schedulebuild thead tr th{ color:#FFF; }
table#schedulebuild th,
table#schedulebuild td{ padding:5px !important; text-align:center; font-size:11px !important; }
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


#selectalllines{ margin:0 0 0 0; }

#datepicker div.ui-datepicker{ width:100% !important; max-height: 270px; }
	
table.ui-datepicker-calendar thead tr th{ color:#000 !important; }

.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight{ border:1px solid #c5c5c5; background:#f6f6f6; color:#454545; }

.ui-datepicker-current-day a{
	background:#007fff; border:1px solid #003eff; color:#FFF;
}

	
form div.submit{ padding:30px 0 0 0; text-align: right; }
form div.submit input[type=submit]{ background:#2345A0; border:2px solid #000; font-size:18px !important; color:#FFFFFF; font-weight:bold; padding:10px 25px !important; }
</style>
<script>
function toggleThisLineVisibility(itemID){
	$.get('/quotes/changelinetally/'+itemID+'/0/workorder',
		function(response){
			if(response == 'OK'){
				$('tr[data-quote-line-item-id='+itemID+']').remove();
			}
		});
}

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



function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0)
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}


function listenforRedirect(){
    if(readCookie('newbatchsuccess_<?php echo $cookieID; ?>') == 'doredirect'){
        eraseCookie('newbatchsuccess_<?php echo $cookieID; ?>');
        window.location.replace('/orders/vieworderschedule/<?php echo $orderData['id']; ?>');
    }
}


$(function(){
	
	$( "#datepicker" ).datepicker({
		minDate: new Date(),
		setDate:null,
		defaultDate:null,
		onSelect: function(dateText,instance){
			<?php if(!is_null($orderData['due']) && intval($orderData['due']) >1000){ ?>checkSelectionAgainstShipDate(dateText);<?php } ?>
			$('#dateselection').val(dateText);
		}
	});
	
	
	$('#selectalllines').click(function(){
		if($(this).is(':checked')){
			$('#schedulebuild > tbody > tr').each(function(){
				if(parseInt($(this).find('input[type=number]').attr('max')) > 0){
					$(this).removeClass('notscheduling');
					$(this).find('select').removeAttr('disabled').removeAttr('readonly').prop('disabled',false);
					$(this).find('input[type=number]').removeAttr('disabled').removeAttr('readonly').prop('disabled',false);
					$(this).find('input[type=checkbox]').prop('checked',true);
					$(this).find('input[type=number]').val($(this).find('input[type=number]').attr('max'));
				}else{
					$(this).find('input[type=number]').val('');
				}
			});
		}else{
			$('#schedulebuild > tbody > tr').each(function(){
				$(this).addClass('notscheduling');
				$(this).find('select').attr('disabled','disabled').attr('readonly','readonly').prop('disabled',true);
				$(this).find('input[type=number]').attr('disabled','disabled').attr('readonly','readonly').prop('disabled',true);
				$(this).find('input[type=checkbox]').prop('checked',false);
				$(this).find('input[type=number]').val('');
			});
		}
	});
	
	$('#schedulebuild > tbody > tr td:nth-of-type(1) input[type=checkbox]').change(function(){
		if($(this).is(':checked')){
			$(this).parent().parent().parent().removeClass('notscheduling');
			$(this).parent().parent().parent().find('select').removeAttr('disabled').removeAttr('readonly').prop('disabled',false);
			$(this).parent().parent().parent().find('input[type=number]').removeAttr('disabled').removeAttr('readonly').prop('disabled',false);
			if(parseInt($(this).parent().parent().parent().find('input[type=number]').attr('max')) > 0){
				$(this).parent().parent().parent().find('input[type=number]').val($(this).parent().parent().parent().find('input[type=number]').attr('max'));
			}
		}else{
			$(this).parent().parent().parent().addClass('notscheduling');
			$(this).parent().parent().parent().find('select').attr('disabled','disabled').attr('readonly','readonly').prop('disabled',true);
			$(this).parent().parent().parent().find('input[type=number]').attr('disabled','disabled').attr('readonly','readonly').prop('disabled',true);

			$(this).parent().parent().parent().find('input[type=number]').val('');
		}
	});
	
	
	$('form').submit(function(e){
		var numErrors=0;
		var totalCheckedRows=0;
		var totalRows=0;
		var totalQTYChecked=0;
		var totalQTYItems=0;
		
		$('#schedulebuild > tbody > tr').each(function(){
			if($(this).find('input[type=checkbox]').is(':checked')){
				var thislinenumber=$(this).find('td:nth-of-type(2)').text();
				totalCheckedRows++;
			
				
				if($(this).find('input[type=number]').val() == '' || $(this).find('input[type=number]').val() == 0){
					e.preventDefault();
					alert('Please enter a QTY for line '+thislinenumber+' or uncheck line '+thislinenumber);
					numErrors++;
					return false;
				}
				
				totalQTYChecked = (totalQTYChecked + parseInt($(this).find('input[type=number]').val()));
				
			}
			totalRows++;
			totalQTYItems = (totalQTYItems + parseInt($(this).attr('data-qtyordered')));
		});
		
		
		if(totalCheckedRows==0 && numErrors==0){
			e.preventDefault();
			alert('Please select at least one line to schedule');
			return false;
		}
		
		if($('#dateselection').val() == '' || $('#dateselection').val() == '0'){
			e.preventDefault();
			alert('You must select a date on the calendar on which to schedule the selected items.');
			return false;
		}
		
		if( (parseInt($(this).attr('data-previouslyscheduled')) + totalQTYChecked) < totalQTYItems && numErrors == 0){
			if(!confirm('You are about to schedule only part of this order, please note that this will set the order status to "Partially Scheduled" and will require additional scheduling of remaining lines')){
				e.preventDefault();
				return false;
			}
		}
		
		$('input[type=submit]').prop('disabled',true);
		
		createCookie('newbatchsuccess_<?php echo $cookieID; ?>','waiting',1);
		
		setInterval('listenforRedirect()',300);
		
		//$.fancybox.showLoading();
		
	});
});
</script><?php } ?>