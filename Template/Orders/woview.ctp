<style>
select#quotetypeid{ width:145px; }
tr.hasbatches{ background:#F4D9FD !important; border: 3px dashed purple !important; }

#canceleditorderlinesbutton{ position:fixed; bottom:-5px; right:175px; z-index:6666; background:#777; padding:5px 5px 5px 5px !important; font-size:13px !important; }
#canceleditorderlinesbutton:hover{ background:#999; color:#fff; }

#savechangesbigbutton{ position:fixed; bottom:-12px; right:8px; z-index:6666; background:#007700; padding:10px 10px 10px 10px !important; font-size:16px !important; }
#savechangesbigbutton:hover{ background:#40FF00; color:#009900; }

#savechangesbigbutton:disabled,
#savechangesbigbutton[disabled]{ cursor:not-allowed !important; background:#444 !important; color:#CCC !important; }

#editbordertop{ height:8px; background:yellow; width:100%; position:fixed; top:0; left:0; z-index:5599; }
#editborderleft{ height:8px; background:yellow; width:8px; position:fixed; top:0; left:0; z-index:5598; }
#editborderright{ height:8px; background:yellow; width:8px; position:fixed; top:0; right:0; z-index:5598; }
#editborderbottom{ height:8px; background:yellow; width:100%; position:fixed; bottom:0; left:0; z-index:5599; }

#remindereditingorder{ position:relative; display:inline-block; background:yellow; font-size:12px; font-weight:bold; color:#660000; padding:5px; position:fixed; bottom:0px; left:8px; z-index:6000; }
#remindereditingorder:after{ content:''; position:absolute; top:0; right:-30px;
display:block;
width: 0;
height: 0;
border-style: solid;
border-width: 22px 0 0 30px;
border-color: transparent transparent transparent #ffff00;
}

button#jumptoformtoggle.toggleoff{ left:0 !important; }

#changeproblemslist{ width:620px; border:3px solid red; padding:4px; background:#FFCFBF; position:fixed; bottom:55px; right:20px; z-index:555555; min-height:40px; }
#changeproblemslist ul{list-style:none; margin:0 0 8px 0; }
#changeproblemslist ul li{ color:red; font-weight:bold; font-size:13px; }
#changeproblemslist ul li small{ font-size:11px; color:#111; padding-left:18px; }

h1#womainlabel{ position:absolute; top:185px; width:300px; left:50%; margin-left:-150px; font-size:22px; font-weight:bold !important; }
</style>



<div class="form noMargin">
    <div class="customRow">
<?php
$ordermode = "workorder";
$mode = "order";
echo "<div id=\"newquoteheading\">
    <div id=\"quoteheadingleft\">";

	   	 echo "<h2>Work Order# <u>".$orderData['order_number']."</u> &nbsp;&nbsp;&nbsp;<a href=\"/orders/vieworderschedule/".$orderData['id']."\" target=\"_blank\"><img src=\"/img/view.png\" alt=\"Batch Breakdown\" title=\"Batch Breakdown\" /></a> &nbsp;&nbsp;<a href=\"/orders/woboxes/".$orderData['id']."\" target=\"_blank\"><img src=\"/img/box-icon.png\" width=\"24\" alt=\"Work Order Boxes\" title=\"Work Order Boxes\" /></a></h2>";
	   


	    echo "<h3>Original Quote# <u>".$quoteData['quote_number']."</u></h3>";
	    
	    echo "<h1 id=\"womainlabel\">WORK ORDER &nbsp;&nbsp;<a href=\"/orders/editlines/".$orderData['id']."/order\" target=\"_blank\" style=\"padding:4px 4px 4px 4px !important; font-size:11px !important;background: #9CE447 !important;\">Open Sales Order</a></h1>";
        if($orderData['status'] == 'Canceled'){
             echo "<h3 style=\"color:red;\">&nbsp;&nbsp;&nbsp;SO CANCELED </h3>";
        }
	    echo "<h3>Work Order Title <u>".$quoteData['quote_title']."</u>";
	    echo "&nbsp;&nbsp;&nbsp;<label>Type:</label> ";
	    
	    foreach($allTypes as $type){
	        if(isset($orderData['type_id']) && !is_null($orderData['type_id']) && $orderData['type_id'] == $type['id']){
	            echo "<B>".$type['type_label']."</B>";
	        }
	    }
	    
	    echo "</h3>";
    

	    echo "<div id=\"projectfieldwrap\"";
	    if($orderData['type_id'] != '1'){ echo " style=\"display:none;\""; } 
	    echo "><label>Project:</label> 
		<span id=\"projectidwrap\"><select name=\"project_id\" onchange=\"updateProjectID(this.value)\" id=\"projectid\" tabindex=\"2\"><option value=\"0\">--Select Project--</option><option value=\"createnew\">Create New Project</option>";
		foreach($availableProjects as $projectRow){
			echo "<option value=\"".$projectRow['id']."\"";
			if($projectRow['id'] == $quoteData['project_id']){
				echo " selected=\"selected\"";
			}
			echo ">".$projectRow['title']."</option>";
		}
		echo "</select></span>
		</div>
	
	
	    <h3>Customer PO# <u>".$orderData['po_number']."</u></h3>";



    echo "&nbsp;&nbsp;&nbsp;";

    //if($quoteData['status'] == 'published' || $quoteData['status'] == 'orderplaced'){
        /* PPSASCRUM-388: start */
        // echo "<button type=\"button\" onclick=\"location.href='/quotes/editpublishedorder/".$orderData['id']."/workorder';\" style=\"padding:4px 4px 4px 4px !important; font-size:11px !important;\">Edit This WorkOrder</button>";
		echo "<button type=\"button\" onclick=\"checkOrderStatus('".$orderData['id']."','".$ordermode."');\" style=\"padding:4px 4px 4px 4px !important; font-size:11px !important;\">Edit This WorkOrder</button>";
		/* PPSASCRUM-388: end */
    
    echo "<script>
	/* PPSASCRUM-388: start */
	function checkOrderStatus(orderid,ordermode){
		$.get('/quotes/checkOrderStatus/'+orderid+'/'+ordermode,function(data){
			if(data=='OK'){
				if (ordermode == 'order') {
					$('#remindereditingorder').css('display', 'none');
					alert('This Sales Order cannot be edited at this moment because the WorkOrder is being EDITED');
				} else if (ordermode == 'workorder') {
					$('#remindereditingorder').css('display', 'none');
					alert('This Work Order cannot be edited at this moment because the SalesOrder is being EDITED');
				}
			} else {
				location.href='/quotes/editpublishedorder/'+orderid+'/'+ordermode;
			}

				//location.href='/quotes/checkOrderStatus/'+orderid+'/'+'/'+ordermode;
		});
	}
	/* PPSASCRUM-388: end */
	
    function removeOverridePrice(quotelineitemid){		
    		$.fancybox.showLoading();		
    		$.get('/quotes/removepriceoverride/'+quotelineitemid,function(data){	
    			if(data=='OK'){		
    				$.fancybox.hideLoading();		
    				updateQuoteTable();		
    			}		
    		});		
    }
    
    function pmimodal(quoteid){
    	$.fancybox({
    		'type':'iframe',
    		'href':'/quotes/pmsurchargeform/'+quoteid,
    		'width':800,
    		'height':650,
    		'autoSize':false,
    		'modal':true
    	});
    }
    
    
    function genworkorder(){
		$.fancybox({
			'type':'iframe',
			'modal':false,
			'width':500,
			'height':260,
			'autoSize':false,
			'href': '/orders/generateworkorderform/".$orderData['id']."'
		});
	}
    
    
    
    $(function(){
        /* updateQuoteTable(); */
    });
    </script>";

   
   echo "<div id=\"exportbuttons\">
   <a href=\"/orders/packingslipform/".$orderData['id']."/Packing%20Slip%20-%20Order%20".$orderData['order_number'].".pdf\" id=\"pdfpackslip\" target=\"_blank\"><img src=\"/img/pdf.png\" /> Create Packing Slip</a>
        	&nbsp;&nbsp; 
        	<a href=\"/orders/buildbompdf/".$orderData['id']."/Order ".$orderData['order_number']." BOM.pdf\" id=\"pdfbom\" target=\"_blank\"><img src=\"/img/pdf.png\" /> Bill of Materials</a>
        	&nbsp;&nbsp; 
        	<a href=\"/orders/bombreakdown/".$orderData['id']."/".$orderData['quote_id']."/Order ".$orderData['order_number']." BOM Breakdown.pdf\" id=\"pdfbombreakdown\" target=\"_blank\"><img src=\"/img/pdf.png\" /> BOM Breakdown</a>
        	&nbsp;&nbsp; 
   <a href=\"javascript:genworkorder()\" id=\"pdfexport\"><img src=\"/img/pdf.png\" />Work Order PDF</a>
        	&nbsp;&nbsp; 
    
    </div>";

   
	
    echo "</div>";

if($customerData['status']=='OnHold'){
    echo "<div id=\"onholdalert\"><img src=\"/img/stopsignicon.png\" /> THIS CUSTOMER IS <em>ON HOLD</em></div>
    <style>
    #onholdalert{ text-align:center; position:fixed; z-index:9999; top:0; left:0; width:100%; background:yellow; padding:10px; font-size:20px; font-weight:bold; color:red; }
    #onholdalert img{ vertical-align:middle; width:28px; height:28px; }
    </style>";
}

echo '<style>#defaultsalert{ display:none; width:500px; border:2px solid red; padding:4px; background:#FFCFBF; position:fixed; bottom:55px; right:20px; z-index:555555; min-height:40px; }</style>
<div id="defaultsalert">This Quote/Order contains class-less items (set as Default/Default).<br>Consider converting them to their appropriate class</div>';

echo "<div id=\"newquotecustomer\">";
echo "<h4>Bill To:</h4>";
echo "<b>".$customerData['company_name']."</b><br>";
if($customerContact){
	echo "ATTN: <em>".$customerContact['first_name']." ".$customerContact['last_name']."</em><br>";
}
echo $customerData['billing_address']."<br>".$customerData['billing_address_city'].", ".$customerData['billing_address_state']." ".$customerData['billing_address_zipcode']."<br><br>Ph: <em>".$customerData['phone']."</em>";
/** PPSASCRUM-3 Start **/
//if($mode=='editorderlines' || $ordermode=='workorder'){
    echo "<br><br><b>Order Date: </b><em>"; echo date('n/j/Y',$orderData['created'])."</em><br>";
    echo "<b>Project Manager: </b><em>"; echo $thisProjectManagerName."</em><br>";
    echo "<b>Facility: </b><em>"; echo $orderData['facility']."</em><br>";
    echo "<b>Ship-By Date : </b><em>"; echo $thisShipByDate."</em><br>";
//}
echo "<br/>";
echo "<br><br><b>On Credit Hold: </b><em>";echo ($customerData['on_credit_hold']) ? "Yes<br>" : "No"."</em><br>";
echo "<b>Deposit Threshold: </b><em>$";echo $customerData['deposit_threshold']."</em><br>";
echo "<b>Deposit %: </b><em>";echo $customerData['deposit_perc']."%</em><br>";
echo "<b>Terms : </b><em>";echo $customerData['payment_terms']."</em><br>";
/** PPSASCRUM-3 End **/
echo "</div>
<div style=\"clear:both;\"></div></div>";


echo "<div id=\"jumptoform\" class=\"toggleoff\">
<!-- PPSASCRUM-139: start -->
<!-- <button type=\"button\" onclick=\"jumpToLine(1);\">Jump To First Line</button> -->
<button type=\"button\" onclick=\"jumpToTop();\">Jump To First Line</button>
<!-- PPSASCRUM-139: end -->
<label>Jump To Line: <input type=\"number\" min=\"1\" id=\"jumptolinenumber\" /></label><button type=\"button\" onclick=\"jumpToLine($('#jumptolinenumber').val());\">Go</button>
<button type=\"button\" onclick=\"jumpToBottom();\">Jump To Last Line</button>
</div>
<button id=\"jumptoformtoggle\" class=\"toggleoff\" type=\"button\" onclick=\"toggleShowJumpForm(false)\">JumpTo</button>

<script>
/* PPSASCRUM-139: start */
var orderID;
var quoteID;
/* PPSASCRUM-139: end */
$(function(){
	var unhoverclosejumpform;
	$('#jumptoform').hover(function(){
		clearTimeout(unhoverclosejumpform);
	},function(){
		if(!$('#jumptoform').hasClass('toggleoff')){
			unhoverclosejumpform=setTimeout('toggleShowJumpForm(true)',2000);
		}
	});

	/* PPSASCRUM-139: start */
            orderID = ".$orderData['id']."
            quoteID = ".$quoteID."
            lineItemRelocationAction = false;
            relocationSourceLineItem = undefined;
            relocationDestinationLineItem = undefined;

            window.addEventListener('keydown', function(event) {
                if (event.code == 'Escape' && relocationSourceLineItem != undefined) {
                    relocationSourceLineItem = undefined;
                    $('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
                        $($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
                            // if ($(ele).hasClass('movehandle') || $(ele).hasClass('tallyaction')) { return; }
                            if ($(ele).hasClass('movehandle')) { return; }

                            $(ele).css('opacity', 1);
                            $(ele).css('cursor', 'pointer');
                            $($(ele).children()[0]).css('pointer-events', 'auto');
                        });
                    });
                    const relocationModal = document.getElementById('relocation-modal');
                    relocationModal.style.display = 'none';
                    $('#line-relocation-active-status-label').removeAttr('value');
                    $('#line-relocation-active-status-label').css('display', 'none');
                }
            });

            window.addEventListener('popstate', function(event) {
                if (relocationSourceLineItem != undefined) {
                    relocationSourceLineItem = undefined;
                    $('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
                        $($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
                            // if ($(ele).hasClass('movehandle') || $(ele).hasClass('tallyaction')) { return; }
                            if ($(ele).hasClass('movehandle')) { return; }

                            $(ele).css('opacity', 1);
                            $(ele).css('cursor', 'pointer');
                            $($(ele).children()[0]).css('pointer-events', 'auto');
                        });
                    });
                    const relocationModal = document.getElementById('relocation-modal');
                    relocationModal.style.display = 'none';
                    $('#line-relocation-active-status-label').removeAttr('value');
                    $('#line-relocation-active-status-label').css('display', 'none');
                }
            });
            /* PPSASCRUM-139: end */
});


function toggleShowJumpForm(fromtimeout){
	if(fromtimeout){
		$('#jumptoform,button#jumptoformtoggle').addClass('toggleoff');
	}else{
		if($('#jumptoform').hasClass('toggleoff')){
			$('#jumptoform,button#jumptoformtoggle').removeClass('toggleoff');
		}else{
			$('#jumptoform,button#jumptoformtoggle').addClass('toggleoff');
		}
	}
}

function jumpToLine(line){
	var foundLine=0;
	/* $('table#quoteitems tbody tr').each(function(){

		var thisLineNumber;
		if($(this).find('td.linenumber input[type=number]').length){
			thisLineNumber=parseInt($(this).find('td.linenumber input[type=number]').val());
		}else{
			thisLineNumber=parseInt($(this).find('td.linenumber').html());
		} */
	/*PPSASCRUM-139: start*/
	$('table#quoteitems tbody tr.quotelineitem').each(function(){

		var thisLineNumber;
		// case: unpublished/textbox
		if($(this).find('td.linenumber div input.linenumber').length){
			thisLineNumber=parseInt($(this).find('td.linenumber div input.linenumber').val());
		}
		// case: published/no-textbox
		else if ($(this).find('td.linenumber div').length) {
			thisLineNumber=parseInt($(this).find('td.linenumber div').text());
		}
		
		else if ($(this).find('td.linkedtoSO').length) {
			thisLineNumber=parseInt($(this).find('td.linkedtoSO').text());
		}
		/*PPSASCRUM-139: end*/


		if(thisLineNumber == line && foundLine==0){

			var thisScrollTop=(($(this).offset().top)-35);
			foundLine++;

			$([document.documentElement, document.body]).animate({
				scrollTop: thisScrollTop
			}, 350);

		}
	});
}

/*PPSASCRUM-139: start*/
function jumpToTop(){
	var firstline;
	/*PPSASCRUM-139: start*/
	// case: unpublished/textbox
	if($('table#quoteitems tbody tr.quotelineitem td.linenumber:first div').find('input.linenumber').length){
		firstline=parseInt($('table#quoteitems tbody tr.quotelineitem td.linenumber:first div').find('input.linenumber').val());
	}
	// case: published/no-textbox
	else{
		firstline=parseInt($('table#quoteitems tbody tr.quotelineitem td.linenumber:first div').text());
	}
	/*PPSASCRUM-139: end*/

	jumpToLine(firstline);
}
/*PPSASCRUM-139: end*/

function jumpToBottom(){
	var lastline;
	/*PPSASCRUM-139: start*/
	// case: unpublished/textbox
	if($('table#quoteitems tbody tr.quotelineitem td.linenumber:last div').find('input.linenumber').length){
		lastline=parseInt($('table#quoteitems tbody tr.quotelineitem td.linenumber:last div').find('input.linenumber').val());
	}
	// case: published/no-textbox
	else{
		lastline=parseInt($('table#quoteitems tbody tr.quotelineitem td.linenumber:last div').text());
	}
	/*PPSASCRUM-139: end*/

	jumpToLine(lastline);
}
</script>
<style>
#jumptoform{ 
	position: fixed;
    top: 50%;
    left: -2px;
    z-index: 5555;
    width: 175px;
    height: 120px;
    padding: 15px 10px;
    background: #e8e8e8;
    border: 2px solid navy;
    text-align: center;
    margin-top: -60px;
}

div#jumptoform.toggleoff{ left:-179px !important; }

#jumptoformtoggle{ width:16px; text-indent:155%; overflow:hidden; white-space:nowrap; padding:0; height:26px; background:url('/img/jumptoicon.png'); position:fixed; top:50%; margin-top:-15px; left: 172px; z-index:5555; outline:0 !important; }

#jumptoform label{ font-size:11px; display:inline-block; }
#jumptoform button:nth-of-type(1){ padding:4px 4px 4px 4px !important; font-size:11px !important; margin:1px 1px 6px 1px !important; border:0 !important; }
#jumptoform input[type=number]{ font-size:11px !important; padding:3px 3px 3px 3px !important; width:45px !important; display:inline-block !important; margin: 0 5px 0 0 !important; height:22px !important; }
#jumptoform button:nth-of-type(2){ padding:4px 4px 4px 4px !important; font-size:11px !important; margin:1px 1px 1px 1px !important; border:0 !important; display:inline-block !important; }
#jumptoform button:nth-of-type(3){ padding:4px 4px 4px 4px !important; font-size:11px !important; margin:6px 1px 1px 1px !important; border:0 !important; }
</style>
";

/** PPSASCRUM_85 Start **/

echo "<div style=\"width:100%\"><br><br><b>Customer Specific Notes: </b><em><br/>
 <div  id='customerNotes'><table><tr><td style=\"width: 25%;\"><b>Customer Notes : </b></td>";
echo "<td>".substr(html_entity_decode(nl2br($customerData['customer_notes'])),0, 300);
if(!empty($customerData['customer_notes']))
echo "<a href='javascript:showMoreCustomerNotes(true)' style=\"color:blue\" >  show more...</a>";
echo "</td><tr></table></div>
<div style=\" display:none\" id='customerNotesShow' ><table><tr><td style=\"width: 25%;\"><b>Customer Notes : </b></td>";
echo "<td>".html_entity_decode(nl2br($customerData['customer_notes'])) . "<a href='javascript:showMoreCustomerNotes(false)' style=\"color:blue\" >  show less...</a>";
echo "</td><tr></table></div><div style=\"\" id='pricingPrograms'><table><tr><td style=\"width: 25%;\"><b>Pricing Programs : </b></td>";
echo "<td>".substr(html_entity_decode(nl2br($customerData['pricing_programs'])),0, 300) ;
if(!empty($customerData['pricing_programs']))
echo "<a href='javascript:showMorepricingPrograms(true)' style=\"color:blue\" >  show more...</a></td><tr></table></div>
<div style=\"display:none\" id='pricingProgramsStauts' >
 <table><tr><td style=\"width: 25%;\"><b>Pricing Programs : </b></td>";
  echo "<td>".html_entity_decode(nl2br($customerData['pricing_programs'])) ;
 if(!empty($customerData['pricing_programs']))
  echo "<a href='javascript:showMorepricingPrograms(false)' style=\"color:blue\" >  show less...</a>";
echo "</td><tr></table></div></div>";
/**PPSASCRUM-85 End **/
?>
</div>
<?php
	
/*  PPSASCRUM-139: start */

/* echo "<div id=\"quotetablewrap\">

</div>"; */

/* PPSASCRUM-139: end */

/*PPSASCRUM-139: start*/
?>
</div>
<?php
$ordermode = "workorder";
$mode = "order";
echo "<div id=\"quotetablewrap\"><div class=\"tableWidth\">";
echo "<div id=\"lineItemRelocationModal\" class=\"modal\" data-backdrop=\"static\" data-keyboard=\"false\"></div>";
echo "<input id=\"line-relocation-active-status-label\" type=\"button\" style=\"display: none; height:30px; background-color:yellow; border-radius: 10px; width:280px; position:fixed; top:20%; left:42%; font-style: normal; cursor: auto; z-index:5599;\" />";
echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\" class=\"innerrow\" id=\"quoteitems\" bordercolor=\"#000000\">
	<thead>
		<tr>
			<th class=\"innerrow\" width=\"8%\" style=\"border: 1px\">
				<!-- <table style=\"border: none\">
					<tr>
						<th class=\"innerrow\" width=\"8%\">Actions</th>
					</tr>
				</table> -->
				<div width=\"8%\" height=\"44px\" style=\"padding-left: 5px;\">Actions</div>
			</th>";

if ($ordermode == "workorder") {
echo "<th class=\"innerrow\" width=\"3%\" style=\"border: 1px\">
		<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
			<tr class=\"innerrow\">
				<th width=\"3%\" class=\"linenumberheading\">Wo Line #</th>
			</tr>
		</table> -->
		<div width=\"3%\" class=\"linenumberheading\" style=\"padding-left: 5px;\">Wo Line #</div>
	</th>
	<th class=\"innerrow\" width=\"3%\" style=\"border: 1px\">
		<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
			<tr class=\"innerrow\">
				<th width=\"3%\" class=\"linenumberheading\">Linked to SOLine #</th>
			</tr>
		</table> -->
		<div width=\"3%\" class=\"linenumberheading\" style=\"padding-left: 5px;\">Linked to SOLine #</div>
	</th>";
} else {
echo "<th class=\"innerrow\" width=\"3%\" style=\"border: 1px; width: 30px;\">
		<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
			<tr class=\"innerrow\">
				<th width=\"3%\" class=\"linenumberheading\">Line #</th>
			</tr>
		</table> -->
		<div width=\"3%\" class=\"linenumberheading\" style=\"padding-left: 5px;\">Line #</div>
	</th>";
}

echo "<th class=\"innerrow\" width=\"6%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"4%\">Location</th>
		</tr>
	</table> -->
	<div width=\"6%\" style=\"padding-left: 5px;\">Location</div>
</th>
<!-- <th width=\"3%\">
	<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"3%\">Vis</th>
		</tr>
	</table>
</th> -->
<th class=\"innerrow\" width=\"3%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"4%\">Qty</th>
		</tr>
	</table> -->
	<div width=\"3%\" style=\"padding-left: 5px;\">Qty</div>
</th>
<th class=\"innerrow\" width=\"3%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"4%\">Unit</th>
		</tr>
	</table> -->
	<div width=\"3%\" style=\"padding-left: 5px;\">Unit</div>
</th>
<th class=\"innerrow\" width=\"13%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"10%\">Line Item</th>
		</tr>
	</table> -->
	<div width=\"13%\" style=\"padding-left: 5px;\">Line Item</div>
</th>
<th class=\"innerrow\" width=\"11.109%\" style=\"border: 1px; width: 99.04px;\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"10%\">Image</th>
		</tr>
	</table> -->
	<div width=\"10%\" style=\"padding-left: 5px;\">Image</div>
</th>
<th class=\"innerrow\" width=\"5%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"5%\">Fabric</th>
		</tr>
	</table> -->
	<div width=\"5%\" style=\"padding-left: 5px;\">Fabric</div>
</th>
<th class=\"innerrow\" width=\"5%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"5%\">Color</th>
		</tr>
	</table> -->
	<div width=\"5%\" style=\"padding-left: 5px;\">Color</div>
</th>
<th class=\"innerrow\" width=\"4%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"3%\">Width</th>
		</tr>
	</table> -->
	<div width=\"4%\" style=\"padding-left: 5px;\">Width</div>
</th>
<th class=\"innerrow\" width=\"4%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"3%\">Length</th>
		</tr>
	</table> -->
	<div width=\"4%\" style=\"padding-left: 5px;\">Length</div>
</th>
<th class=\"innerrow\" width=\"3%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"4%\">Total LF</th>
		</tr>
	</table> -->
	<div width=\"3%\" style=\"padding-left: 5px;\">Total LF</div>
</th>
<th class=\"innerrow\" width=\"4%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"5%\">Yds/unit</th>
		</tr>
	</table> -->
	<div width=\"4%\" style=\"padding-left: 5px;\">Yds/unit</div>
</th>
<th class=\"innerrow\" width=\"5%\" style=\"border: 1px\">
	<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"6%\">Total Yds</th>
		</tr>
	</table> -->
	<div width=\"5%\" style=\"padding-left: 5px;\">Total Yds</div>
</th>
<!-- Columns not required currently
<th width=\"5%\">
	<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"5%\">Fab Margin</th>
		</tr>
	</table>
</th>
<th width=\"5%\">
	<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
		<tr class=\"innerrow\">
			<th width=\"5%\">Fab Profit</th>
		</tr>
	</table>
</th> -->";

if ($ordermode != "workorder") {
echo "<th class=\"innerrow\" width=\"8%\" style=\"border: 1px\">
		<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
			<tr class=\"innerrow\">
				<th width=\"4%\">Base</th>
			</tr>
		</table> -->
		<div width=\"8%\" style=\"padding-left: 5px;\">Base</div>
	</th>
	<th class=\"innerrow\" width=\"4.1%\" style=\"border: 1px\">
		<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
			<tr class=\"innerrow\">
				<th width=\"4%\" class=\"adjustedprice\">Adj</th>
			</tr>
		</table> -->
		<div width=\"4.1%\" style=\"padding-left: 5px;\">Adj</div>
	</th>
	<th class=\"innerrow\" width=\"4%\" style=\"border: 1px\">
		<!-- <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
			<tr class=\"innerrow\">
				<th width=\"5%\" class=\"extendedprice\">Ext</th>
			</tr>
		</table> -->
		<div width=\"4%\" style=\"padding-left: 5px;\">Ext</div>
	</th>";
}

echo "</tr>
</thead>
<tbody>

</tbody>
</table>" . "</div></div>";
/*PPSASCRUM-139: end*/
echo $this->Form->end();
?>
<!-- PPSASCRUM-139: start -->
</div>
<!-- PPSASCRUM-139: end -->
<style>
span.newchildrowicon{ position:relative; }
span.newchildrowicon div.childrowpopout{ position:absolute; width:190px; top:14px; left:0; z-index:555; display:none; background:#1C2559; color:#FFF; border-right:1px solid #000; border-bottom:1px solid #000; }
span.newchildrowicon:hover div.childrowpopout{ display:block; }


div.childrowpopout ul{ list-style:none; margin:0; padding:0; }
div.childrowpopout > ul > li{ position:relative; display:block; }
div.childrowpopout > ul > li > ul{ position:absolute; left:190px; top:0; z-index:555; display:none; background:#1C2559; width:160px; }
div.childrowpopout > ul > li:hover ul{ display:block; }

div.childrowpopout a{ display:block; color:#FFF; text-decoration:none; padding:3px 5px; text-align:left; font-size:10px; border-top:1px solid #000; border-left:1px solid #000; }
div.childrowpopout a:hover{ background:#0059B2; }

div.childrowpopout > ul > li.hassubmenu:after{
    content:'';
    position:absolute;
    top:11px; right:7px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 5px 0 5px 6px;
    border-color: transparent transparent transparent #ffffff;
}



.expiremessage{ background:#C40D10; color:#FFF; text-align:center; padding:10px; font-size:16px; }
	
button.usagebutton{ font-size:11px !important; padding:5px !important; margin:0 !important; font-weight:normal !important; }
h2{ margin-bottom:10px !important; }
.row:has(.noMargin) {
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}
/* .customRow{
     max-width: 80rem;
     margin: 0 auto;
     width: 90%;
} */
/*#quotetablewrap{ min-width:2455px; }*/
.tableWidth{
    width: 97% !important;
    margin:auto !important;
}
/* PPSASCRUM-211 start */
.customRow{
     max-width: 80rem;
     margin: 0 auto;
     width: 90%;
}
/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.no-spinners {
         -moz-appearance: textfield;
      }

      .no-spinners::-webkit-outer-spin-button,
      .no-spinners::-webkit-inner-spin-button {
         -webkit-appearance: none;
         margin: 0;
      }

.row:has(.noMargin) {
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}
.customRow{
     max-width: 80rem;
     margin: 0 auto;
     width: 90%;
}/* PPSASCRUM-211 end */

/* PPSASCRUM-139: start */
/* img.movehandle{ cursor:move; } */
img.movehandle{ cursor:pointer; }
/* PPSASCRUM-139: end */

#catchalllistpopup ul{ list-style:none; margin:0; padding:0; }
#catchalllistpopup > ul > li{ position:relative; display:block; }
#catchalllistpopup > ul > li > ul{ position:absolute; left:190px; top:0; z-index:555; display:none; background:#1C2559; width:160px; }
#catchalllistpopup > ul > li:hover ul{ display:block; }

#catchalllistpopup{ display:none; width:190px; position:absolute; bottom:45px; right:0; z-index:555; background:#1C2559; color:#FFF; border-right:1px solid #000; border-bottom:1px solid #000; }
#catchalllistbuttonwrap{ display:inline-block; position:relative; }
#catchalllistbuttonwrap:hover #catchalllistpopup{ display:block; }
#catchalllistpopup a{ display:block; color:#FFF; text-decoration:none; padding:5px 10px; text-align:left; font-size:12px; border-top:1px solid #000; border-left:1px solid #000; }
#catchalllistpopup a:hover{ background:#0059B2; }

#catchalllistpopup > ul > li.hassubmenu:after{
    content:'';
    position:absolute;
    top:11px; right:7px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 5px 0 5px 6px;
    border-color: transparent transparent transparent #ffffff;
}


#pricelistpopup{ display:none; width:100%; position:absolute; bottom:45px; right:0; z-index:555; background:#1C2559; color:#FFF; border-right:1px solid #000; border-bottom:1px solid #000; }
#pricelistbuttonwrap{ display:inline-block; position:relative; }
#pricelistbuttonwrap:hover #pricelistpopup{ display:block; }
#pricelistpopup a{ display:block; color:#FFF; text-decoration:none; padding:5px 10px; text-align:left; font-size:12px; border-top:1px solid #000; border-left:1px solid #000; }
#pricelistpopup a:hover{ background:#0059B2; }
	

td.unacceptableloss{ background:#FED3D3; font-weight:bold; color:red; }
td.acceptableloss{ background:#D9F7CC; font-weight:bold; color:green; }

#calculatorpopup{ display:none; width:100%; position:absolute; bottom:45px; right:0; z-index:555; background:#1C2559; color:#FFF; border-right:1px solid #000; border-bottom:1px solid #000; }
#calculatorbuttonwrap{ display:inline-block; position:relative; }
#calculatorbuttonwrap:hover #calculatorpopup{ display:block; }
#calculatorpopup a{ display:block; color:#FFF; text-decoration:none; padding:5px 10px; text-align:left; font-size:12px; border-top:1px solid #000; border-left:1px solid #000; }
#calculatorpopup a:hover{ background:#0059B2; }




#manualpopup{ display:none; width:100%; position:absolute; bottom:45px; right:0; z-index:555; background:#1C2559; color:#FFF; border-right:1px solid #000; border-bottom:1px solid #000; }
#manualbuttonwrap{ display:inline-block; position:relative; }
#manualbuttonwrap:hover #manualpopup{ display:block; }
#manualpopup a{ display:block; color:#FFF; text-decoration:none; padding:5px 10px; text-align:left; font-size:12px; border-top:1px solid #000; border-left:1px solid #000; }
#manualpopup a:hover{ background:#0059B2; }




#belowtableactions{ width:1100px; margin-top:20px; padding:15px; }
#actions{ text-align:right; float:right; }
/* PPSASCRUM-139: start */
table#quoteitems tbody tr td,
table#quoteitems tbody tr td{
	border:1px solid #BBB;
}
/* PPSASCRUM-139: end */

/* PPSASCRUM-139: start */
table#quoteitems thead tr,
table#quoteitems thead tr th{
	border:1px solid #2345A0;
}
/* PPSASCRUM-139: end */
	
table#quotefooter tr,
table#quotefooter th,
table#quotefooter td{
	border:0;
}


td.linenumber{ background:rgba(0,0,175,0.1); }
td.linenumber input{ font-size:16px !important; font-weight:bold; padding:4px !important; }

th.linenumberheading{ background:rgba(0,0,0,0.2); }

td.actionscol img{ width:14px !important; height:14px !important; }

div.priceoverriden{ background:#ECF2A4; color:maroon; font-size:10px; padding:2px; line-height:12px; }

td.adjustedprice,td.extendedprice,th.adjustedprice,th.extendedprice{ font-weight:bold; }
td.adjustedprice input{ font-size:10px; padding:2px; border:0; height:auto; margin:0 0 5px 0; }

/* PPSASCRUM-139: start */
#quoteitems tbody tr td input{ margin:0 0 10px 0; }
/* PPSASCRUM-139: end */
	
input.linenumber{ background:rgba(255,255,255,0.6); font-size:11px; padding:4px; width:98%; line-height:14px !important; height:20px !important; }
input.roomnumber{ background:rgba(255,255,255,0.6); font-size:11px; padding:4px; width:98%; line-height:14px !important; height:20px !important; }
input.qtyvalue{ background:rgba(255,255,255,0.6); font-size:11px; padding:4px; width:40%; line-height:14px !important; height:20px !important; display:inline-block; }




input.costfield{ width:75% !important; display:inline-block !important; margin:0; padding:2px 4px !important; border:0; background:rgba(0,0,0,0.04); }
	
input.markupfield{ width:75% !important; display:inline-block !important; margin:0; padding:2px 4px !important; border:0; background:rgba(0,0,0,0.04); }
	
#newquoteheading #quoteheadingleft{ float:left; width:73%; padding:15px 0px 15px 0; }
#quoteheadingleft h2,#quoteheadingleft h3{ font-size:18px; margin:0; font-weight:bold; }
#quoteheadingleft h2 u,#quoteheadingleft h3 u{ color:#3438F5; }
	
#newquoteheading #newquotecustomer{ float:right; padding:15px 0 15px 0; font-size:14px; width:240px; text-align:right; }

input.priceoverride{ width:80% !important; display:inline-block !important; margin:0; padding:2px 4px !important; border:0; background:rgba(0,0,0,0.04); }

#exportbuttons{ padding:0; }
#exportbuttons img{ vertical-align: middle; }
	
#pdfexport,#xlsexport,#pdfquoteexport,#pdfmaterials,#pdfpackslip,#pdfbom,#pdfquotesummary,#pdfbombreakdown{ font-size:12px; padding:3px 5px; line-height:20px; border:0; }

table#quoteitems{ margin-bottom: 0 !important; }
/* PPSASCRUM-139: start */
table#quoteitems tr th, table#quoteitems tr td{ padding:4px 4px 4px 4px !important; }
/* PPSASCRUM-139: end */
table#quoteitems thead{ background:#26337A; color:#FFF; }
table#quoteitems thead tr th{ color:#FFF; font-weight:bold; font-size:10px; }
table#quoteitems tbody tr td{ font-size:11px; }
	
/* PPSASCRUM-139: start */
table#quoteitems > thead > tr,
table#quoteitems > tbody > tr{
	padding:0 !important;
}
/*#quoteitems_wrapper { margin-left: -58px; margin-right: -58px; }*/
/* PPSASCRUM-139: end */
	
table#quotefooter tr#subtotalLine,
table#quotefooter tr#discountsLine,
table#quotefooter tr#taxLine{ font-weight:bold; }
table#quotefooter tr#totaldueLine{ font-weight:bold; }


.priceoverridenotice{ font-size:10px; color:#440000; background:#ffffd8; padding:2px; display:inline-block; line-height:12px; margin:5px 0 5px 8px; border:1px solid #d1774d; }
.priceoverridenotice a{ font-weight:bold; }

table#quotefooter tr td a{ color:#000 !important; text-decoration:none !important; font-size:11px; }

table#quoteitems{ border-bottom:2px solid #26337a !important; }
table#quoteitems tbody tr.lightrow{ background:#f8f8f8; }
table#quoteitems tbody tr.darkrow{ background:#e8e8e8; }
table#quoteitems tbody tr.hoverRow{ background:#D2E2E9 !important; }
table#quoteitems tbody tr.highlightedfabric{ background:#F5E889 !important; }

table#quoteitems tbody tr.quotelineitem td{ border-top:2px solid #26337A !important; border-bottom:1px solid #CCC !important; }
	
.linedescription{ font-size:11px; font-style:italic; }

#quotetitle{ width:300px !important; display: inline-block; }

/* PPSASCRUM-139: start */
#quoteitems tbody tr,
#quoteitems thead tr{ margin-bottom:0 !important;  margin-left:0 !important;}

/* #quoteitems tr,
#quoteitems thead tr{ background:none !important; } */
/* PPSASCRUM-139: end */
	
#quoteitems table,
#quoteitems table tr.innerrow{ background:none !important; }

table#quotefooter tr#subtotalLine td,
table#quotefooter tr#discountsLine td,
table#quotefooter tr#surchargeLine td,
table#quotefooter tr#taxLine td{ color:#000; font-weight:normal; }
table#quotefooter tr#totaldueLine td{ color:#000; }

table#quotefooter tr td.alignleft{ text-align:left !important; }
table#quotefooter tr td.alignright{ text-align:right !important; }
table#quotefooter {display: none }
button#verifyeditorderlinesbutton {display: none}
img.deletelineitem{ cursor:pointer; }

#actions button{ padding:6px 12px !important; font-size:12px; font-weight:bold; margin-left:12px; }


#newquoteheading label{ display:inline-block !important; }
/*#newquoteheading select{ min-width:120px !important; }*/
	

#projectid{ width:300px !important; }
#quote-status{ width:145px !important; }
	
	
#newquotecustomer h4{ margin:0; font-weight:bold; font-size:18px; }
	
.internalnoteentry{ background:#fff2f2; border:1px solid #dd9b9b; padding:3px; font-size:11px; color:#560101; }
.internalnoteentry a{ float:right; }

textarea.fabrequirements{ font-size:11px; padding:2px; }
	
div.rtm{ padding:5px 2px 15px 2px; }

div.tallyaction{ padding:20px 2px 10px 2px; }
/* PPSASCRUM-139: start */
.modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5); }

.modal-head { text-align: center; }

.modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; width: 60%; max-width: 500px; position: relative; }

.close-button { position: absolute; top: -10px; right: -10px; width: 30px; height: 30px; border-radius: 50%; background-color: #fefefe; cursor: pointer; font-size: 16px; font-weight: bold; text-align: center; justify-content: center; }

.relocation-modal { display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; }

.relocation-modal-content { background-color: white; padding: 5px; border-radius: 15px; text-align: center; position: absolute; bottom: 88px; left: 104px; display: flex; flex-direction: column; height: 58px; width: 170px; align-items: center; }
/* PPSASCRUM-139: end */
</style>
<script>
function changeRTMSetting(lineitemid,newvalue){
	$.fancybox.showLoading();
	$.get('/orders/togglertmline/'+lineitemid+'/'+newvalue,function(data){
		if(data=='OK'){
			$.fancybox.hideLoading();
			updateQuoteTable();
		}
	});
}
	
function changeSherryTallySetting(lineitemid,newvalue,ordermode="order"){
	$.fancybox.showLoading();

	/* PPSASCRUM-323: start */
    	// if(ordermode.length > 0)ordermode =ordermode; else ordermode =' ';
    	/* PPSASCRUM-323: end */

    /* PPSASCRUM-323: start */
	if (ordermode != ' ') {
	/* PPSASCRUM-323: end */
    	$.get('/orders/changelinetally/'+lineitemid+'/'+newvalue+'/'+ordermode,function(data){
    		if(data=='OK'){
    			$.fancybox.hideLoading();
    			/*PPSASCRUM-139: start*/
    			// updateQuoteTable();
    			lineItemsPaginatedTable.ajax.reload();
    			/*PPSASCRUM-139: end*/
    		}
    	});
	/* PPSASCRUM-323: start */
	} else {
	    $.get('/quotes/changelinetally/'+lineitemid+'/'+newvalue,function(data){
			if(data=='OK'){
				$.fancybox.hideLoading();
				lineItemsPaginatedTable.ajax.reload();
			}
		});
	}
	/* PPSASCRUM-323: end */
}

function updateQuoteTable(scrolltoele=''){
	console.log("test"<?php echo $orderData['$orderData']; ?>);
	$.get('/quotes/updatevals/<?php echo $orderData['quote_id']; ?>/order/workorder<?php
	if(isset($urlparams->query['highlightFabric']) && strlen(trim($urlparams->query['highlightFabric'])) >0){
		echo "?highlightFabric=".$urlparams->query['highlightFabric'];
	}
	?>',function(data){
		$('#quotetablewrap').html(data);
		
		if(scrolltoele != ''){
		    document.getElementById(scrolltoele).scrollIntoView();
		}
		
		<?php
		if($quoteData['status'] == 'editorder'){
        
            $oldquoteid=$orderData['quote_id'];
            $newquoteid=$quoteData['id'];
           
        
            foreach($totals as $lineTotals){
                if($lineTotals['Scheduled'] > 0){
                    echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol img.deletelineitem').parent().remove();";
                    echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol div.tallyaction').remove();";
                }
            }
		}
		?>
		
		
	});
}

function addInternalNote(lineitemid,mode){
	$.fancybox({
	'type':'iframe',
	'href':'/quotes/addlineitemnote/'+lineitemid+'/'+mode,
	'autoSize':false,
	'width':500,
	'height':350,
	'modal':true
	});
}
function addGlobalNote(quoteid){
	$.fancybox({
		'type':'iframe',
		'href': '/quotes/addglobalnote/'+quoteid,
		'autoSize':false,
		'width':500,
		'height':350,
		'modal':true
	});
}
function deleteGlobalNote(noteid){
    if(confirm('Are you sure you want to delete this global note?')){
        $.get('/quotes/deleteglobalnote/'+noteid,function(data){
            if(data=='SUCCESS'){
                //alert('Global Note removed.');
                updateQuoteTable();
            }
        });
    }
}
function loadTrackBreakdown(lineID){
	$.fancybox({
		'type':'iframe',
		/* PPSASCRUM-361: start */
		/*'href': '/quotes/viewtrackbreakdown/'+lineID,*/
		'href': '/quotes/viewtrackbreakdown/'+lineID+'/<?php echo $ordermode; ?>',
		/* PPSASCRUM-361: end */
		'autoSize':false,
		'width':700,
		'height':700
	});
}

function addManualDiscount(){
	$.fancybox({
		'type':'iframe',
		'href':'/quotes/addmanualdiscount/<?php echo $quoteID; ?>',
		'autoSize':false,
		'width':500,
		'height':200,
		'modal':true
	});
}	

function doChangeLineItemImage(lineitemid){
	$.fancybox({
		'type':'iframe',
		'href':'/quotes/editlineitemimage/<?php echo $quoteID; ?>/'+lineitemid,
		'autoSize':false,
		'width':600,
		'height':515,
		'modal':true
	});
}
function doRecordUsage(fabricid){
	$.fancybox({
		'type':'iframe',
		'href': '/quotes/recordusage/<?php echo $quoteID; ?>/'+fabricid,
		'autoSize':false,
		'width':600,
		'height':400,
		'modal':true,
		helpers: {
			overlay: {
			  locked: false
			}
		  }
	});
}

function clonelineItemPopup(lineitemid,ordermode){
    	location.href='/quotes/clonelineItemPopup/'+lineitemid+'/'+ordermode;

}

function changeLineItemPrice(lineItemID,newprice,ordermode){
	$.fancybox.showLoading();
	if(ordermode.length > 0)ordermode =ordermode; else ordermode =' ';
	$.get('/quotes/changelineitemprice/'+lineItemID+'/'+newprice+'/'+ordermode,function(data){
		if(data=='OK'){
			$.fancybox.hideLoading();
			updateQuoteTable();
		}
	});
}

$(function() {
	/*PPSASCRUM-139: start*/
	$('#quoteitems').on('draw.dt', function () {
		$.get('/quotes/evalfabricspecialcostatplay/<?php echo $quoteID; ?>', function (data) {
			if (data && !$('#fabric-cost-per-yard').length) {
				$('#quotetablewrap').prepend("<div id='fabric-cost-per-yard' style=\"color: red;text-align: center; padding: 10px; font-size: 16px; FONT-WEIGHT: BOLDER;\">Fabric Special Cost p/yd at play on some items</div>");
			}
		});

		if (relocationSourceLineItem != undefined) {
			$('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
				$($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
					// if ($(ele).hasClass('movehandle') || $(ele).hasClass('tallyaction')) { return; }
					if ($(ele).hasClass('movehandle')) { return; }

					$(ele).css('opacity', 0.4);
					$(ele).css('cursor', 'not-allowed');
					$($(ele).children()[0]).css('pointer-events', 'none');
				});
			});
		}

		$('input.linenumber').keyup($.debounce( 650, function(){
			updatelinenumber($(this).attr('data-itemid'),$(this).val());
		}));

		$('input.roomnumber').keyup($.debounce( 650, function(){
			updateroomnumber($(this).attr('data-itemid'),$(this).val());
		}));

		$('#quoteitems tbody tr').hover(function(){
			$('tr[data-lineitemid=\''+$(this).attr('data-lineitemid')+'\']').addClass('hoverRow');
		},function(){
			$('tr[data-lineitemid=\''+$(this).attr('data-lineitemid')+'\']').removeClass('hoverRow');
		});

		$('#quoteitems tbody tr td.adjustedprice input').on('keyup click change', $.debounce( 1000, function(){
			changeLineItemPrice($(this).attr('data-lineitemid'),$(this).val());
		}));

		$('#pg-tbtn').css("margin-right", 1580);
		$('#pg-bbtn').css("margin-right", 1580);

		<?php
			if($quoteData['status'] == 'editorder'){
				$oldquoteid=$orderData['quote_id'];
				$newquoteid=$quoteData['id'];
				foreach($totals as $lineTotals){
					if($lineTotals['Scheduled'] > 0){
						echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol img.deletelineitem').parent().remove();";
						echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol div.tallyaction').remove();";
					}
				}
			}
		?>

		$(document).mouseup(function (e) {
			let relocationModal = $('#nodal-box');
			if (!relocationModal.is(e.target) && relocationModal.has(e.target).length === 0) {
				console.log('Modal was clicked to hide');
				$('#lineItemRelocationModal').hide();
			}
        });

		$('select[name=quoteitems_length]').change(function() {
			relocationSourceLineItem = undefined;
			$('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
				$($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
					// if ($(ele).hasClass('movehandle') || $(ele).hasClass('tallyaction')) { return; }
					if ($(ele).hasClass('movehandle')) { return; }

					$(ele).css('opacity', 1);
					$(ele).css('cursor', 'pointer');
					$($(ele).children()[0]).css('pointer-events', 'auto');
				});
			});
			const relocationModal = document.getElementById('relocation-modal');
			relocationModal.style.display = 'none';
			$('#line-relocation-active-status-label').removeAttr('value');
			$('#line-relocation-active-status-label').css('display', 'none');
		});
	});

	$('#quoteitems').on('draw.dt', function () {
		$.get('/quotes/evalfabricspecialcostatplay/<?php echo $quoteID; ?>', function (data) {
			if (data && !$('#fabric-cost-per-yard').length) {
				$('#quotetablewrap').prepend("<div id='fabric-cost-per-yard' style=\"color: red;text-align: center; padding: 10px; font-size: 16px; FONT-WEIGHT: BOLDER;\">Fabric Special Cost p/yd at play on some items</div>");
			}
		});

		if (relocationSourceLineItem != undefined) {
			$('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
				$($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
					// if ($(ele).hasClass('movehandle') || $(ele).hasClass('tallyaction')) { return; }
					if ($(ele).hasClass('movehandle')) { return; }

					$(ele).css('opacity', 0.4);
					$(ele).css('cursor', 'not-allowed');
					$($(ele).children()[0]).css('pointer-events', 'none');
				});
			});
		}

		$('input.linenumber').keyup($.debounce( 650, function(){
			updatelinenumber($(this).attr('data-itemid'),$(this).val());
		}));

		$('input.roomnumber').keyup($.debounce( 650, function(){
			updateroomnumber($(this).attr('data-itemid'),$(this).val());
		}));

		$('#quoteitems tbody tr').hover(function(){
			$('tr[data-lineitemid=\''+$(this).attr('data-lineitemid')+'\']').addClass('hoverRow');
		},function(){
			$('tr[data-lineitemid=\''+$(this).attr('data-lineitemid')+'\']').removeClass('hoverRow');
		});

		$('#quoteitems tbody tr td.adjustedprice input').on('keyup click change', $.debounce( 1000, function(){
			changeLineItemPrice($(this).attr('data-lineitemid'),$(this).val());
		}));

		$('#pg-tbtn').css("margin-right", 1580);
		$('#pg-bbtn').css("margin-right", 1580);

		<?php
			if($quoteData['status'] == 'editorder'){
				$oldquoteid=$orderData['quote_id'];
				$newquoteid=$quoteData['id'];
				foreach($totals as $lineTotals){
					if($lineTotals['Scheduled'] > 0){
						echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol img.deletelineitem').parent().remove();";
						echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol div.tallyaction').remove();";
					}
				}
			}
		?>

		$(document).mouseup(function (e) {
			let relocationModal = $('#nodal-box');
			if (!relocationModal.is(e.target) && relocationModal.has(e.target).length === 0) {
				console.log('Modal was clicked to hide');
				$('#lineItemRelocationModal').hide();
			}
        });

		$('select[name=quoteitems_length]').change(function() {
			relocationSourceLineItem = undefined;
			$('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
				$($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
					// if ($(ele).hasClass('movehandle') || $(ele).hasClass('tallyaction')) { return; }
					if ($(ele).hasClass('movehandle')) { return; }

					$(ele).css('opacity', 1);
					$(ele).css('cursor', 'pointer');
					$($(ele).children()[0]).css('pointer-events', 'auto');
				});
			});
			const relocationModal = document.getElementById('relocation-modal');
			relocationModal.style.display = 'none';
			$('#line-relocation-active-status-label').removeAttr('value');
			$('#line-relocation-active-status-label').css('display', 'none');
		});
	});

	var urlParams = new URLSearchParams(window.location.search);

	// case: NEW Price-List line item
	var queryParamPriceList = urlParams.has('price_list') ? urlParams.get('price_list') : '';

	// case: NEW child line item
	var queryParamChildLine = urlParams.has('page') && urlParams.has('li_no') ? urlParams.get('page') : '';

	var queryParamLineNo = urlParams.has('li_no') ? urlParams.get('li_no') : '';

	var queryParamRelocationPageNo = urlParams.has('page') ? urlParams.get('page') : '';
    
    var urlPath = urlParams.has('url') ? urlParams.get('url') : '';

	console.log(`Line item relocation: page=${queryParamRelocationPageNo} and line no: ${queryParamLineNo}`);

	var msgTxt = $('div#container > div#content > div.message.success').text();

	if (($('div#container > div#content > div.message.success').is(':visible') || queryParamPriceList != '' || queryParamLineNo != '') && !msgTxt.includes('Changed quote ') && !msgTxt.includes('Successfully created Revision')) {
		if (msgTxt == 'Successfully added Calculator line item to Quote' || msgTxt == 'Successfully added SWT Misc Catchall line item to quote' ||
			msgTxt == 'Successfully added Miscellaneous Catchall line item to quote' || msgTxt == 'Successfully added Service Catchall line item to quote' ||
			msgTxt == 'Successfully added HWT Misc Catchall line item to quote' || msgTxt == 'Successfully added HWT Shutters Catchall line item to quote' ||
			msgTxt == 'Successfully added HWT Shades Catchall line item to quote' || msgTxt == 'Successfully added Hardware Catchall line item to quote' ||
			msgTxt == 'Successfully added SWT Valance Catchall line item to quote' || msgTxt == 'Successfully added SWT Drapery Catchall line item to quote' ||
			msgTxt == 'Successfully added SWT Cornice Catchall line item to quote' || msgTxt == 'Successfully added Cubicle Catchall line item to quote' ||
			msgTxt == 'Successfully added Bedding Catchall line item to quote' || msgTxt == 'Successfully added HWT Blinds Catchall line item to quote' ||
			msgTxt.includes('Successfully cloned line') || msgTxt == 'Successfully relocated line item' || queryParamPriceList != '' || queryParamLineNo != ''
		) {
			// for clone operation
			if (!msgTxt.includes('Successfully cloned line')) {
				if (false) {
					alert('Undesirable code executed!');
					var lineItemCreated = true;
					$('#quoteitems').on('draw.dt', function () {
						var initJumpToNewLineItemPage = false;
						// console.log(`Init page: ${parseInt($('#quoteitems').DataTable().page())}`);
						// console.log(`Init condition status: ${parseInt($('#quoteitems').DataTable().page()) != pageNo && !initJumpToNewLineItemPage}`);
						setTimeout(function() {
							console.log('Inside the new line item navigation handler');
							// console.log(`Recurring page: ${parseInt($('#quoteitems').DataTable().page())}`);
							// console.log(`Recurring condition status: ${parseInt($('#quoteitems').DataTable().page()) != pageNo && !initJumpToNewLineItemPage}`);
							var pageNo = Math.floor(parseInt($('#quoteitems').DataTable().page.info().recordsTotal) / parseInt($('#quoteitems').DataTable().page.len()));
							if (parseInt($('#quoteitems').DataTable().page.info().recordsTotal) % parseInt($('#quoteitems').DataTable().page.len()) == 0) {
								pageNo -= 1;
							}
							console.log(`Navigating to page: ${pageNo}`);
							// if (parseInt($('#quoteitems').DataTable().page()) != pageNo && !initJumpToNewLineItemPage && lineItemCreated) {
							if (!initJumpToNewLineItemPage && lineItemCreated) {
								if (queryParamLineNo == '') {
									if (queryParamChildLine == '') {
										$('#quoteitems').DataTable().page(pageNo).draw('page');
									} else {
										console.log(`Navigating to page ${queryParamChildLine} for child line item`);
										$('#quoteitems').DataTable().page(parseInt(queryParamChildLine)).draw('page');
									}
								}
								console.log(`Navigated to page: ${pageNo}`);
								initJumpToNewLineItemPage = true;
								lineItemCreated = false;
								var removeQParam = false;
								if (queryParamPriceList != '') {
									console.log('Delete query param for Price List line item');
									urlParams.delete('price_list');
									removeQParam = true;
								}
								if (queryParamChildLine != '') {
									urlParams.delete('page');
									removeQParam = true;
								}
								setTimeout(function() {
									if (queryParamChildLine != '' || queryParamLineNo != '' || queryParamPriceList == 'edit_price-lst_success') {
										console.log('scrolling down for NEW Child line item or EDIT line item');
										jumpToLine(parseInt(queryParamLineNo));
										console.log('DONE scrolling down for NEW Child line item or EDIT line item');
										urlParams.delete('li_no');
										removeQParam = true;
									} else {
										console.log('scrolling down to last line item');
										jumpToBottom();
										console.log('DONE scrolling down to last line item');
									}
									if (removeQParam) {
										// TODO: try to replace this logic with window.history.pushState()
										// window.location.replace(window.location.href.replace(/\?.+/,''));
										window.history.pushState('object', document.title, location.href.split("?")[0]);
									}
								}, 2900);
								return;
							}
						}, 1850);
					});
				}

				$.get('/quotes/fetchglobalnotesandcalculations/<?php echo $quoteID; ?>/<?php
					if($orderData['status'] == 'Editing'){
						echo 'editorder';
					}else{
						/* PPSASCRUM-320: start */
						// echo $ordermode;
						echo "order";
						/* PPSASCRUM-320: end */
					}
					/* PPSASCRUM-320: start */
					// echo "/". $ordermode;
					echo "/". "workorder";
					/* PPSASCRUM-320: end */
					if(isset($urlparams->query['highlightFabric']) && strlen(trim($urlparams->query['highlightFabric'])) >0){
						echo "?highlightFabric=".$urlparams->query['highlightFabric'];
					}
					/* ?>', function (data) { if (data && !$('#quotenotes').length) { alert('appending'); $('#quotetablewrap').append(data); $('#quotenotes').html(data); } else { alert('replacing'); $('#quotenotes').html(data); } }); */
					?>', function (data) {
						if (data && !$('#quotenotes').length) {
							$('#quotetablewrap').append(data);
							$('#quotetablewrap > #bomtable').remove();
							$('#quotetablewrap > #quotenotes').empty();
							$('#quotetablewrap > #quotenotes').append(data);
						}
					}
				);

				console.log('Child line item CREATE reached here');

				lineItemsPaginatedTable = $('#quoteitems').DataTable({
					"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
					"processing": true,
					"bServerSide": true,
					"sServerMethod": "GET",
					"ajax": {
						"url": "/quotes/getquotelineitemslist/<?php echo $quoteID; ?>/<?php
							if($orderData['status'] == 'Editing'){
								echo 'editorder';
							}else{
								/* PPSASCRUM-320: start */
								// echo $ordermode;
								echo "order";
								/* PPSASCRUM-320: end */
							}
							/* PPSASCRUM-320: start */
							// echo "/". $ordermode;
							echo "/". "workorder";
							/* PPSASCRUM-320: end */
							if(isset($urlparams->query['highlightFabric']) && strlen(trim($urlparams->query['highlightFabric'])) >0){
								echo "?highlightFabric=".$urlparams->query['highlightFabric'];
							}
							?>.json"
					},
					"language": {
						"info": `<p style="font-size: 13.5px;font-weight: 370;">Showing _START_ to _END_ of _TOTAL_ entries<br/>(filtered from _MAX_ total entries)</p>`,
					},
					createdRow: function(row, data, dataIndex){
						if(data[0].includes('Line Notes:') || data[3] == ''){
							$('td:eq(0)', row).attr('colspan', 4);

							$('td:eq(1)', row).css('display', 'none');
							$('td:eq(2)', row).css('display', 'none');
							$('td:eq(3)', row).css('display', 'none');

							// $('td:eq(4)', row).attr('colspan', 13);

							<?php
								if ($ordermode != "workorder") {
							?>
								$('td:eq(4)', row).attr('colspan', 13);
							<?php } else { ?>
								$('td:eq(4)', row).attr('colspan', 11);
							<?php } ?>

							if (data[3] == '') {
								$('td:eq(0)', row).css('border-top', 'none');
								$('td:eq(0)', row).css('border-bottom', 'none');
							}

							$('td:eq(5)', row).css('display', 'none');
							$('td:eq(6)', row).css('display', 'none');
							$('td:eq(7)', row).css('display', 'none');
							$('td:eq(8)', row).css('display', 'none');
							$('td:eq(9)', row).css('display', 'none');
							$('td:eq(10)', row).css('display', 'none');
							$('td:eq(11)', row).css('display', 'none');
							$('td:eq(12)', row).css('display', 'none');
							$('td:eq(13)', row).css('display', 'none');
							$('td:eq(14)', row).css('display', 'none');

							if ("<?php echo $ordermode; ?>" != "workorder") {
								// $('td:eq(14)', row).css('display', 'none');
								/* Columns not required currently: 15, 16
								$('td:eq(15)', row).css('display', 'none');
								$('td:eq(16)', row).css('display', 'none');
								$('td:eq(17)', row).css('display', 'none');
								$('td:eq(18)', row).css('display', 'none');
								$('td:eq(19)', row).css('display', 'none'); */
								$('td:eq(15)', row).css('display', 'none');
								$('td:eq(16)', row).css('display', 'none');
								/* $('td:eq(17)', row).css('display', 'none'); */
							}
						}
					},
					searching: false,
					/* dom:'<"top"iBfr<"#pg-tbtn"p>l<"clear">>rt<"bottom"iBfr<"#pg-bbtn"p>l<"clear">>', */
					dom:'<"top"iBfrl<"#pg-tbtn">p<"clear">>rt<"bottom"iBfrl<"#pg-bbtn">p<"clear">>',
					stateSave: true,
					//searchHighlight: true,
					buttons:[],

					// <//?php
					// 	if(isset($_GET['search'])){
					// 	?>
					// 	"oSearch": {"sSearch": "<//?php echo $_GET['search']; ?>"},
					// 	<//?php } ?>
					<?php
						if ($ordermode != "workorder") {
					?>
					"columns": [
						{"className": 'actionscol',"orderable": false},
						{"className": 'linenumber',"orderable": false},
						{"className": 'location',"orderable": false},
						/* {"className": 'vis',"orderable": false}, */
						{"className": 'qty',"orderable": false},
						{"className": 'unit',"orderable": false},
						{"className": 'lineItem',"orderable": false},
						{"className": 'image',"orderable": false},
						{"className": 'fabric',"orderable": false},
						{"className": 'color',"orderable": false},
						{"className": 'width',"orderable": false},
						{"className": 'length',"orderable": false},
						{"className": 'totalLF',"orderable": false},
						{"className": 'ydsUnit',"orderable": false},
						{"className": 'totalYds',"orderable": false},
						/* Columns not required currently
						{"className": 'fabMargin',"orderable": false},
						{"className": 'fabProfit',"orderable": false}, */
						{"className": 'base',"orderable": false},
						{"className": 'adjPrice',"orderable": false},
						{"className": 'extPrice',"orderable": false}
					]
					<?php } else { ?>
						"columns": [
						{"className": 'actionscol',"orderable": false},
						{"className": 'linenumber',"orderable": false},
						{"className": 'linkedtoSO',"orderable": false},
						{"className": 'location',"orderable": false},
						/* {"className": 'vis',"orderable": false}, */
						{"className": 'qty',"orderable": false},
						{"className": 'unit',"orderable": false},
						{"className": 'lineItem',"orderable": false},
						{"className": 'image',"orderable": false},
						{"className": 'fabric',"orderable": false},
						{"className": 'color',"orderable": false},
						{"className": 'width',"orderable": false},
						{"className": 'length',"orderable": false},
						{"className": 'totalLF',"orderable": false},
						{"className": 'ydsUnit',"orderable": false},
						{"className": 'totalYds',"orderable": false},
						/* Columns not required currently
						{"className": 'fabMargin',"orderable": false},
						{"className": 'fabProfit',"orderable": false}, */
						/* {"className": 'base',"orderable": false},
						{"className": 'adjPrice',"orderable": false},
						{"className": 'extPrice',"orderable": false} */
					]
					<?php } ?>
					,
					initComplete: function() {
						<?php
							if($quoteData['status'] == 'editorder'){

								$oldquoteid=$orderData['quote_id'];
								$newquoteid=$quoteData['id'];


								foreach($totals as $lineTotals){
									if($lineTotals['Scheduled'] > 0){
										//  echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol img.deletelineitem').parent().remove();";
										echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol div.tallyaction').remove();";
									}
								}
							}
						?>
						var lineItemCreated = true;
						var initJumpToNewLineItemPage = false;
						console.log('Inside the new line item navigation handler');

						var pageNo = Math.floor(parseInt($('#quoteitems').DataTable().page.info().recordsTotal) / parseInt($('#quoteitems').DataTable().page.len()));
						if (parseInt($('#quoteitems').DataTable().page.info().recordsTotal) % parseInt($('#quoteitems').DataTable().page.len()) == 0) {
							pageNo -= 1;
						}

						setTimeout(function() {
							console.log(`Navigating to page: ${pageNo}`);

							if (!initJumpToNewLineItemPage && lineItemCreated) {
								if (queryParamLineNo == '') {
									if (queryParamChildLine == '') {
										$('#quoteitems').DataTable().page(pageNo).draw('page');
									} else {
										console.log(`Navigating to page ${queryParamChildLine} for child line item`);
										$('#quoteitems').DataTable().page(parseInt(queryParamChildLine)).draw('page');
									}
								} else if (queryParamRelocationPageNo != '') {
									console.log(`Navigating to page ${queryParamRelocationPageNo} for line item relocation`);
								// 	$('#quoteitems').DataTable().page(parseInt(queryParamRelocationPageNo)).draw('page');
									if (!(urlPath && urlPath.includes("quotes/add"))) {
										$('#quoteitems').DataTable().page(parseInt(queryParamRelocationPageNo)).draw('page');
									} else {
										$('#quoteitems').DataTable().page(0).draw('page');
										$('#quoteitems').DataTable().page('last').draw('page');
									}
								}
								console.log(`Navigated to page: ${pageNo}`);
								initJumpToNewLineItemPage = true;
								lineItemCreated = false;
								var removeQParam = false;
								if (queryParamPriceList != '') {
									console.log('Delete query param for Price List line item');
									urlParams.delete('price_list');
									removeQParam = true;
								}
								if (queryParamChildLine != '') {
									urlParams.delete('page');
									removeQParam = true;
								}
							}

							// $('#quoteitems').DataTable().page(pageNo).draw('page');
							return;
						// }, 2000);
						}, 4000);
						// setTimeout(function() {
							// alert('New page navigation successful');
							// lineItemsPaginatedTable.api().row(parseInt($('#quoteitems').DataTable().page.info().recordsTotal) - 1).scrollTo();
							// $('#quoteitems').DataTable().fnSettings().oScroller.fnScrollToRow(100000);
						// }, 4000);
						setTimeout(function() {
							if (queryParamChildLine != '' || queryParamLineNo != '' || queryParamPriceList == 'edit_price-lst_success') {
									console.log('scrolling down for NEW Child line item or EDIT line item or line item relocation to line no ' + queryParamLineNo);
									jumpToLine(parseInt(queryParamLineNo));
									console.log('DONE scrolling down for NEW Child line item or EDIT line item or line item relocation to line no ' + queryParamLineNo);
									urlParams.delete('li_no');
									removeQParam = true;
								} else {
									console.log('scrolling down to last line item');
									jumpToBottom();
									console.log('DONE scrolling down to last line item');
								}
								if (removeQParam) {
									// TODO: try to replace this logic with window.history.pushState()
									// window.location.replace(window.location.href.replace(/\?.+/,''));
									window.history.pushState('object', document.title, location.href.split("?")[0]);
								}
							// $('#quoteitems').DataTable().page(pageNo).draw('page');
							// jumpToBottom();
						// }, 5000);
						}, 10000);
					}
				});
			} else {
				// alert('Clone performed');

				$.get('/quotes/fetchglobalnotesandcalculations/<?php echo $quoteID; ?>/<?php
					if($orderData['status'] == 'Editing'){
						echo 'editorder';
					}else{
						/* PPSASCRUM-320: start */
						// echo $ordermode;
						echo "order";
						/* PPSASCRUM-320: end */
					}
					/* PPSASCRUM-320: start */
					// echo "/". $ordermode;
					echo "/". "workorder";
					/* PPSASCRUM-320: end */
					if(isset($urlparams->query['highlightFabric']) && strlen(trim($urlparams->query['highlightFabric'])) >0){
						echo "?highlightFabric=".$urlparams->query['highlightFabric'];
					}
					/* ?>', function (data) { if (data && !$('#quotenotes').length) { alert('appending'); $('#quotetablewrap').append(data); $('#quotenotes').html(data); } else { alert('replacing'); $('#quotenotes').html(data); } }); */
					?>', function (data) {
						if (data && !$('#quotenotes').length) {
							$('#quotetablewrap').append(data);
							$('#quotetablewrap > #bomtable').remove();
							$('#quotetablewrap > #quotenotes').empty();
							$('#quotetablewrap > #quotenotes').append(data);
						}
					}
				);

				lineItemsPaginatedTable = $('#quoteitems').DataTable({
					"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
					"processing": true,
					"bServerSide": true,
					"sServerMethod": "GET",
					"ajax": {
						"url": "/quotes/getquotelineitemslist/<?php echo $quoteID; ?>/<?php
							if($orderData['status'] == 'Editing'){
								echo 'editorder';
							}else{
								/* PPSASCRUM-320: start */
								// echo $ordermode;
								echo "order";
								/* PPSASCRUM-320: end */
							}
							/* PPSASCRUM-320: start */
							// echo "/". $ordermode;
							echo "/". "workorder";
							/* PPSASCRUM-320: end */
							if(isset($urlparams->query['highlightFabric']) && strlen(trim($urlparams->query['highlightFabric'])) >0){
								echo "?highlightFabric=".$urlparams->query['highlightFabric'];
							}
							?>.json"
					},
					"language": {
						"info": `<p style="font-size: 13.5px;font-weight: 370;">Showing _START_ to _END_ of _TOTAL_ entries<br/>(filtered from _MAX_ total entries)</p>`,
					},
					createdRow: function(row, data, dataIndex){
						if(data[0].includes('Line Notes:') || data[3] == ''){
							$('td:eq(0)', row).attr('colspan', 4);

							$('td:eq(1)', row).css('display', 'none');
							$('td:eq(2)', row).css('display', 'none');
							$('td:eq(3)', row).css('display', 'none');

							// $('td:eq(4)', row).attr('colspan', 13);

							<?php
								if ($ordermode != "workorder") {
							?>
								$('td:eq(4)', row).attr('colspan', 13);
							<?php } else { ?>
								$('td:eq(4)', row).attr('colspan', 11);
							<?php } ?>

							if (data[3] == '') {
								$('td:eq(0)', row).css('border-top', 'none');
								$('td:eq(0)', row).css('border-bottom', 'none');
							}

							$('td:eq(5)', row).css('display', 'none');
							$('td:eq(6)', row).css('display', 'none');
							$('td:eq(7)', row).css('display', 'none');
							$('td:eq(8)', row).css('display', 'none');
							$('td:eq(9)', row).css('display', 'none');
							$('td:eq(10)', row).css('display', 'none');
							$('td:eq(11)', row).css('display', 'none');
							$('td:eq(12)', row).css('display', 'none');
							$('td:eq(13)', row).css('display', 'none');
							$('td:eq(14)', row).css('display', 'none');

							if ("<?php echo $ordermode; ?>" != "workorder") {
								// $('td:eq(14)', row).css('display', 'none');
								/* Columns not required currently: 15, 16
								$('td:eq(15)', row).css('display', 'none');
								$('td:eq(16)', row).css('display', 'none');
								$('td:eq(17)', row).css('display', 'none');
								$('td:eq(18)', row).css('display', 'none');
								$('td:eq(19)', row).css('display', 'none'); */
								$('td:eq(15)', row).css('display', 'none');
								$('td:eq(16)', row).css('display', 'none');
								/* $('td:eq(17)', row).css('display', 'none'); */
							}
						}
					},
					searching: false,
					/* dom:'<"top"iBfr<"#pg-tbtn"p>l<"clear">>rt<"bottom"iBfr<"#pg-bbtn"p>l<"clear">>', */
					dom:'<"top"iBfrl<"#pg-tbtn">p<"clear">>rt<"bottom"iBfrl<"#pg-bbtn">p<"clear">>',
					stateSave: true,
					//searchHighlight: true,
					buttons:[],

					// <//?php
					// 	if(isset($_GET['search'])){
					// 	?>
					// 	"oSearch": {"sSearch": "<//?php echo $_GET['search']; ?>"},
					// 	<//?php } ?>
					<?php
						if ($ordermode != "workorder") {
					?>
					"columns": [
						{"className": 'actionscol',"orderable": false},
						{"className": 'linenumber',"orderable": false},
						{"className": 'location',"orderable": false},
						/* {"className": 'vis',"orderable": false}, */
						{"className": 'qty',"orderable": false},
						{"className": 'unit',"orderable": false},
						{"className": 'lineItem',"orderable": false},
						{"className": 'image',"orderable": false},
						{"className": 'fabric',"orderable": false},
						{"className": 'color',"orderable": false},
						{"className": 'width',"orderable": false},
						{"className": 'length',"orderable": false},
						{"className": 'totalLF',"orderable": false},
						{"className": 'ydsUnit',"orderable": false},
						{"className": 'totalYds',"orderable": false},
						/* Columns not required currently
						{"className": 'fabMargin',"orderable": false},
						{"className": 'fabProfit',"orderable": false}, */
						{"className": 'base',"orderable": false},
						{"className": 'adjPrice',"orderable": false},
						{"className": 'extPrice',"orderable": false}
					]
					<?php } else { ?>
						"columns": [
						{"className": 'actionscol',"orderable": false},
						{"className": 'linenumber',"orderable": false},
						{"className": 'linkedtoSO',"orderable": false},
						{"className": 'location',"orderable": false},
						/* {"className": 'vis',"orderable": false}, */
						{"className": 'qty',"orderable": false},
						{"className": 'unit',"orderable": false},
						{"className": 'lineItem',"orderable": false},
						{"className": 'image',"orderable": false},
						{"className": 'fabric',"orderable": false},
						{"className": 'color',"orderable": false},
						{"className": 'width',"orderable": false},
						{"className": 'length',"orderable": false},
						{"className": 'totalLF',"orderable": false},
						{"className": 'ydsUnit',"orderable": false},
						{"className": 'totalYds',"orderable": false},
						/* Columns not required currently
						{"className": 'fabMargin',"orderable": false},
						{"className": 'fabProfit',"orderable": false}, */
						/* {"className": 'base',"orderable": false},
						{"className": 'adjPrice',"orderable": false},
						{"className": 'extPrice',"orderable": false} */
					]
					<?php } ?>
					,
					initComplete: function() {
						<?php
							if($quoteData['status'] == 'editorder'){

								$oldquoteid=$orderData['quote_id'];
								$newquoteid=$quoteData['id'];


								foreach($totals as $lineTotals){
									if($lineTotals['Scheduled'] > 0){
										//  echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol img.deletelineitem').parent().remove();";
										echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol div.tallyaction').remove();";
									}
								}
							}
						?>

						// alert('init completed for clone');
						var pageNo = Math.floor(parseInt($('#quoteitems').DataTable().page.info().recordsTotal) / parseInt($('#quoteitems').DataTable().page.len()));
						if (parseInt($('#quoteitems').DataTable().page.info().recordsTotal) % parseInt($('#quoteitems').DataTable().page.len()) == 0) {
							pageNo -= 1;
						}
						setTimeout(function() {
							$('#quoteitems').DataTable().page(pageNo).draw('page');
						}, 1000);
						// setTimeout(function() {
							// alert('New page navigation successful');
							// lineItemsPaginatedTable.api().row(parseInt($('#quoteitems').DataTable().page.info().recordsTotal) - 1).scrollTo();
							// $('#quoteitems').DataTable().fnSettings().oScroller.fnScrollToRow(100000);
						// }, 4000);
						setTimeout(function() {
							// $('#quoteitems').DataTable().page(pageNo).draw('page');
							jumpToBottom();
						}, 2000);
					}
				});
			}
		}
	} else {
		$.get('/quotes/fetchglobalnotesandcalculations/<?php echo $quoteID; ?>/<?php
			if($orderData['status'] == 'Editing'){
				echo 'editorder';
			}else{
    			/* PPSASCRUM-320: start */
				// echo $ordermode;
				echo "order";
				/* PPSASCRUM-320: end */
			}
			/* PPSASCRUM-320: start */
			// echo "/". $ordermode;
			echo "/". "workorder";
			/* PPSASCRUM-320: end */
			if(isset($urlparams->query['highlightFabric']) && strlen(trim($urlparams->query['highlightFabric'])) >0){
				echo "?highlightFabric=".$urlparams->query['highlightFabric'];
			}
			/* ?>', function (data) { if (data && !$('#quotenotes').length) { alert('appending'); $('#quotetablewrap').append(data); $('#quotenotes').html(data); } else { alert('replacing'); $('#quotenotes').html(data); } }); */
			?>', function (data) { if (data && !$('#quotenotes').length) { $('#quotetablewrap').append(data); $('#quotetablewrap > #bomtable').remove(); $('#quotetablewrap > #quotenotes').empty(); $('#quotetablewrap > #quotenotes').append(data); } }
		);

		lineItemsPaginatedTable = $('#quoteitems').DataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"ajax": {
				"url": "/quotes/getquotelineitemslist/<?php echo $quoteID; ?>/<?php
					if($orderData['status'] == 'Editing'){
						echo 'editorder';
					}else{
						/* PPSASCRUM-320: start */
						// echo $ordermode;
						echo "order";
						/* PPSASCRUM-320: end */
					}
					/* PPSASCRUM-320: start */
					// echo "/". $ordermode;
					echo "/". "workorder";
					/* PPSASCRUM-320: end */
					if(isset($urlparams->query['highlightFabric']) && strlen(trim($urlparams->query['highlightFabric'])) >0){
						echo "?highlightFabric=".$urlparams->query['highlightFabric'];
					}
					?>.json"
			},
			"language": {
				"info": `<p style="font-size: 13.5px;font-weight: 370;">Showing _START_ to _END_ of _TOTAL_ entries<br/>(filtered from _MAX_ total entries)</p>`,
			},
			createdRow: function(row, data, dataIndex){
				if(data[0].includes('Line Notes:') || data[3] == ''){
					$('td:eq(0)', row).attr('colspan', 4);

					$('td:eq(1)', row).css('display', 'none');
					$('td:eq(2)', row).css('display', 'none');
					$('td:eq(3)', row).css('display', 'none');

					// $('td:eq(4)', row).attr('colspan', 13);

					<?php
						if ($ordermode != "workorder") {
					?>
						$('td:eq(4)', row).attr('colspan', 13);
					<?php } else { ?>
						$('td:eq(4)', row).attr('colspan', 11);
					<?php } ?>

					if (data[3] == '') {
						$('td:eq(0)', row).css('border-top', 'none');
						$('td:eq(0)', row).css('border-bottom', 'none');
					}

					$('td:eq(5)', row).css('display', 'none');
					$('td:eq(6)', row).css('display', 'none');
					$('td:eq(7)', row).css('display', 'none');
					$('td:eq(8)', row).css('display', 'none');
					$('td:eq(9)', row).css('display', 'none');
					$('td:eq(10)', row).css('display', 'none');
					$('td:eq(11)', row).css('display', 'none');
					$('td:eq(12)', row).css('display', 'none');
					$('td:eq(13)', row).css('display', 'none');
					$('td:eq(14)', row).css('display', 'none');

					if ("<?php echo $ordermode; ?>" != "workorder") {
						// $('td:eq(14)', row).css('display', 'none');
						/* Columns not required currently: 15, 16
						$('td:eq(15)', row).css('display', 'none');
						$('td:eq(16)', row).css('display', 'none');
						$('td:eq(17)', row).css('display', 'none');
						$('td:eq(18)', row).css('display', 'none');
						$('td:eq(19)', row).css('display', 'none'); */
						$('td:eq(15)', row).css('display', 'none');
						$('td:eq(16)', row).css('display', 'none');
						/* $('td:eq(17)', row).css('display', 'none'); */
					}
				}
			},
			searching: false,
			/* dom:'<"top"iBfr<"#pg-tbtn"p>l<"clear">>rt<"bottom"iBfr<"#pg-bbtn"p>l<"clear">>', */
			dom:'<"top"iBfrl<"#pg-tbtn">p<"clear">>rt<"bottom"iBfrl<"#pg-bbtn">p<"clear">>',
			stateSave: true,
			//searchHighlight: true,
			buttons:[],

			// <//?php
			// 	if(isset($_GET['search'])){
			// 	?>
			// 	"oSearch": {"sSearch": "<//?php echo $_GET['search']; ?>"},
			// 	<//?php } ?>
			<?php
				if ($ordermode != "workorder") {
			?>
			"columns": [
				{"className": 'actionscol',"orderable": false},
				{"className": 'linenumber',"orderable": false},
				{"className": 'location',"orderable": false},
				/* {"className": 'vis',"orderable": false}, */
				{"className": 'qty',"orderable": false},
				{"className": 'unit',"orderable": false},
				{"className": 'lineItem',"orderable": false},
				{"className": 'image',"orderable": false},
				{"className": 'fabric',"orderable": false},
				{"className": 'color',"orderable": false},
				{"className": 'width',"orderable": false},
				{"className": 'length',"orderable": false},
				{"className": 'totalLF',"orderable": false},
				{"className": 'ydsUnit',"orderable": false},
				{"className": 'totalYds',"orderable": false},
				/* Columns not required currently
				{"className": 'fabMargin',"orderable": false},
				{"className": 'fabProfit',"orderable": false}, */
				{"className": 'base',"orderable": false},
				{"className": 'adjPrice',"orderable": false},
				{"className": 'extPrice',"orderable": false}
			]
			<?php } else { ?>
				"columns": [
				{"className": 'actionscol',"orderable": false},
				{"className": 'linenumber',"orderable": false},
				{"className": 'linkedtoSO',"orderable": false},
				{"className": 'location',"orderable": false},
				/* {"className": 'vis',"orderable": false}, */
				{"className": 'qty',"orderable": false},
				{"className": 'unit',"orderable": false},
				{"className": 'lineItem',"orderable": false},
				{"className": 'image',"orderable": false},
				{"className": 'fabric',"orderable": false},
				{"className": 'color',"orderable": false},
				{"className": 'width',"orderable": false},
				{"className": 'length',"orderable": false},
				{"className": 'totalLF',"orderable": false},
				{"className": 'ydsUnit',"orderable": false},
				{"className": 'totalYds',"orderable": false},
				/* Columns not required currently
				{"className": 'fabMargin',"orderable": false},
				{"className": 'fabProfit',"orderable": false}, */
				/* {"className": 'base',"orderable": false},
				{"className": 'adjPrice',"orderable": false},
				{"className": 'extPrice',"orderable": false} */
			]
			<?php } ?>
			,
			initComplete: function() {
				// alert('initial loading completed');
				// var pageNo = Math.floor(parseInt($('#quoteitems').DataTable().page.info().recordsTotal) / parseInt($('#quoteitems').DataTable().page.len()));
				// if (parseInt($('#quoteitems').DataTable().page.info().recordsTotal) % parseInt($('#quoteitems').DataTable().page.len()) == 0) {
				// 	pageNo -= 1;
				// }
				// $('#quoteitems').DataTable().page('last').draw('page');
				// alert('New page navigation successful');
				<?php
					if($quoteData['status'] == 'editorder'){

						$oldquoteid=$orderData['quote_id'];
						$newquoteid=$quoteData['id'];


						foreach($totals as $lineTotals){
							if($lineTotals['Scheduled'] > 0){
								//  echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol img.deletelineitem').parent().remove();";
								echo "$('#quoteitems tbody tr[data-lineitemid=\'".$lineTotals['newItemID']."\'] td table.innerrow tbody tr td.actionscol div.tallyaction').remove();";
							}
						}
					}
				?>
			}
		});
	}

	/*PPSASCRUM-139: end*/
});

/*PPSASCRUM-139: start*/
var quoteLineItemNumbers;
function showLineItemRelocationModal(sourceLineItemId, sourceLineItemNumber, lineNumbers) {
	quoteLineItemNumbers = lineNumbers;
	let borderStyle = window.location.host.includes('3phasehci')
		? '3px solid #74bc1f'
		: '3px solid #700101';
	if (lineNumbers.length == 1) {
		showExceptionModal("This action require at least two line items to exist on the Quote/Order", borderStyle);
		return;
	}
	$('#lineItemRelocationModal').html(getRelocationModalContent(borderStyle));
	$('#relocating-line-number').html(sourceLineItemNumber);
	let textIndent = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	let lineNumberOptions = lineNumbers.map((arg, index) => {
		let [lineItemId, lineNumber] = arg.toString().split(";;");
		return "<option value='"+index+"' lineItemId="+lineItemId+">"+textIndent+lineNumber+"</option>"
	});
	$('#relocation-line-no-selector').append(
		"<option value=\"default\" style=\"text-align: center;\">&lt;-- Select line number --&gt;</option>"
			.concat(lineNumberOptions)
			.concat("<option value=\""+lineNumberOptions.length+"\" lineItemId=\"last\">"+textIndent+"Last</option>")
		);
	$('#relocation-line-no-selector').val('default');
	if (!$('#lineItemRelocationModal').has('input#relocating-line-item-id').length) {
		$('#lineItemRelocationModal').append("<input id=\"relocating-line-item-id\" type=\"hidden\" value=\""+sourceLineItemId+"\">");
	} else {
		$('input#relocating-line-item-id').val(sourceLineItemId);
	}
	$('#lineItemRelocationModal').show();
}

function relocateLineItemTo(destinationLineItemId) {
	let borderStyle = window.location.host.includes('3phasehci')
		? '3px solid #74bc1f'
		: '3px solid #700101';
	if (destinationLineItemId != 'last') {
		destinationLineItemId = parseInt(destinationLineItemId);
	}
	let sourceLineItemId = $('input#relocating-line-item-id').val();
	if (sourceLineItemId == destinationLineItemId) {
		showExceptionModal("Source and destination line items are same, please select another line number and try again", borderStyle);
		return;
	}
	let lineItemIds = quoteLineItemNumbers.toString().split(',').map(arg => arg.toString().split(';;')[0].trim());
	if (lineItemIds.findIndex(arg => arg == destinationLineItemId) == lineItemIds.findIndex(arg => arg == sourceLineItemId) + 1) {
		showExceptionModal("The source and the destination line must be different", borderStyle);
		return;
	}
	lineItemIds.push('last');
	$('#lineItemRelocationModal').hide();
	$.post('/quotes/sortpaginatedlineitem/'+JSON.stringify(lineItemIds)+'/'+sourceLineItemId+'/'+destinationLineItemId+'?sourceLineItemNumber='+$('#relocating-line-number').text()+'&quoteID=<?php echo $quoteID; ?>&pageSize='+lineItemsPaginatedTable.page.len(), function(response) {
		$('#lineItemRelocationModal').hide();
		console.log('Relocation response: ', JSON.stringify(response));
		let relocationResponse = JSON.parse(response);

		let url;
		if(<?php echo $mode == 'order' || $mode == 'editorderlines' ? 1 : 0; ?>) {
			url = "/orders/editlines/" + orderID;
		}
		if (<?php echo $mode == 'quote' ? 1 : 0; ?>) {
			url = "/quotes/add/" + quoteID;
		}
		url += "?page="+relocationResponse.page+"&li_no="+relocationResponse.li_no;
		window.location.href = url;
	});
}

function showExceptionModal(message, borderStyle) {
	$('#lineItemRelocationModal').html(
		`
		<div id=\"nodal-box\" style=\"border: `+borderStyle+`\" class=\"modal-content\">
			<input type="button" class="close-button" style="border:`+borderStyle+`;color:`+borderStyle.slice(-7)+`;" onclick="closeRelocationModal()" value="X" />
			<h2 class=\"modal-head\">Line Item Relocation</h2><hr>
			<p>`+message+`</p>
			<!-- <div style="display: flex; justify-content: center;">
				<button id=\"relocation\" onclick=\"closeRelocationModal();\">Close</button>
			</div> -->
		</div>
		`
	);
	$('#lineItemRelocationModal').show();
}

function getRelocationModalContent(borderStyle) {
	return `
		<div id="nodal-box" style="border: `+borderStyle+`" class="modal-content">
			<input type="button" class="close-button" style="border:`+borderStyle+`;color:`+borderStyle.slice(-7)+`;" onclick="closeRelocationModal()" value="X" />
			<h2 class="modal-head">Line Item Relocation</h2><hr>
			<div style="display: flex;flex-direction: horizontal;justify-content: space-between;margin-top: 35px;">
			<div style="display: flex;flex-direction: horizontal;"><b>From:&nbsp;&nbsp;</b>Line&nbsp;&nbsp;<div id='relocating-line-number'></div></div>
			<div style="display: flex;flex-direction: horizontal;margin-bottom: 15px;">
				<b style="margin-top: 5px;">To Above:&nbsp;&nbsp;</b><select id='relocation-line-no-selector' style="width:198px;"></select>
			</div>
			</div>
			<div style="display: flex;/* justify-content: space-between; */justify-content: space-between;margin-top: 35px;">
				<button id="relocation" onclick="relocateLineItemTo($('#relocation-line-no-selector option:selected').attr('lineitemid'));">Relocate</button>
			</div>
		</div>
	`;
}

function closeRelocationModal() {
	$('#lineItemRelocationModal').hide();
}

var lineItemRelocationAction = false;
var relocationSourceLineItem;
var relocationDestinationLineItem;
var relocateEventObj;

function relocateLineItem(event, lineItemId, lineItemNumber, lineNumbers, isLastLineItem) {
	console.log('isLastLineItem: ', isLastLineItem);
	if (isLastLineItem == true && relocationSourceLineItem != undefined && relocationSourceLineItem.attr('id') != lineItemId) {
		const relocationModal = document.getElementById('relocation-modal');
		relocationModal.style.display = 'flex';
		relocateEventObj = event;
		return;
	} else if (isLastLineItem == true && relocationSourceLineItem != undefined && relocationSourceLineItem.attr('id') == lineItemId) {
		relocationSourceLineItem = undefined;
		$('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
			$($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
				if ($(ele).hasClass('movehandle')) { return; }

				$(ele).css('opacity', 1);
				$(ele).css('cursor', 'pointer');
				$($(ele).children()[0]).css('pointer-events', 'auto');
			});
		});
		const relocationModal = document.getElementById('relocation-modal');
		relocationModal.style.display = 'none';
		$('#line-relocation-active-status-label').removeAttr('value');
		$('#line-relocation-active-status-label').css('display', 'none');
		return;
	}

	relocateLineItemCore(event, lineItemId, lineItemNumber, lineNumbers, isLastLineItem, '');
}

function relocationToQuoteEnd(position, lineItemId, lineItemNumber, lineNumbers, isLastLineItem) {
	if (position == 'above-end-line-item') {
		relocateLineItemCore(relocateEventObj, lineItemId, lineItemNumber, lineNumbers, isLastLineItem, 'above-end-line-item');
	} else if (position == 'below-end-line-item') {
		relocateLineItemCore(relocateEventObj, lineItemId, lineItemNumber, lineNumbers, isLastLineItem, 'below-end-line-item');
	}
	return;
}

/*function relocateLineItemCore(event, lineItemId, lineItemNumber, lineNumbers, isLastLineItem, position) {
	console.log('Clicked to relocate: ', $(event.target).parent().parent().parent().parent());

	$('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
		$($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
			if ($(ele).hasClass('movehandle')) { return; }

			$(ele).css('opacity', 0.4);
			$(ele).css('cursor', 'not-allowed');
			$($(ele).children()[0]).css('pointer-events', 'none');
		});
	});

	if (relocationSourceLineItem == undefined) {
		relocationSourceLineItem = $(event.target).parent().parent().parent().parent();
		if (!$('#line-relocation-active-status-label').attr('value')) {
			const sourceLineItemPageNo = parseInt($('#quoteitems').DataTable().page()) + 1;
			const sourceLineItemLineNo = $('tr[id='+relocationSourceLineItem.attr('id')+'] > td.linenumber >> input[type=number]').val();
			$('#line-relocation-active-status-label').attr('value', `Relocating Line ${sourceLineItemLineNo}, Page ${sourceLineItemPageNo}`);
		}
		$('#line-relocation-active-status-label').css('display', 'block');
	} else {
		relocationDestinationLineItem = $(event.target).parent().parent().parent().parent();

		if (relocationSourceLineItem.attr('id') == relocationDestinationLineItem.attr('id')) {
			relocationSourceLineItem = undefined;
			$('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
				$($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
					if ($(ele).hasClass('movehandle')) { return; }

					$(ele).css('opacity', 1);
					$(ele).css('cursor', 'pointer');
					$($(ele).children()[0]).css('pointer-events', 'auto');
				});
			});
			const relocationModal = document.getElementById('relocation-modal');
			relocationModal.style.display = 'none';
			$('#line-relocation-active-status-label').removeAttr('value');
			$('#line-relocation-active-status-label').css('display', 'none');
			return;
		}

		const sourceLineItemId = relocationSourceLineItem.attr('data-lineitemid');

		let destinationLineItemId = relocationDestinationLineItem.attr('data-lineitemid');
		if (position == 'below-end-line-item') {
			destinationLineItemId = 'last';
		}

		const lineItemIds = lineNumbers.toString().split(',').map(arg => arg.toString().split(';;')[0].trim());
		lineItemIds.push('last');

		const sourceLineItemNumber = $($(relocationSourceLineItem.children()[1]).children()[0]).children().val();

		$.post('/quotes/sortpaginatedlineitem/'+sourceLineItemId+'/'+destinationLineItemId+'?sourceLineItemNumber='+sourceLineItemNumber+'&quoteID=<?php echo $quoteID; ?>&pageSize='+lineItemsPaginatedTable.page.len()+'&lineItemIds='+JSON.stringify(lineItemIds), function(response) {
			console.log('Relocation response: ', JSON.stringify(response));
			let relocationResponse = JSON.parse(response);
			let url;
			if(<?php echo $mode == 'order' || $mode == 'editorderlines' ? 1 : 0; ?>) {
				url = "/orders/editlines/" + orderID;
			}
			if (<?php echo $mode == 'quote' ? 1 : 0; ?>) {
				url = "/quotes/add/" + quoteID;
			}
			url += "?page="+relocationResponse.page+"&li_no="+relocationResponse.li_no;
			window.location.href = url;
		});
	}
}*/

function relocateLineItemCore(event, lineItemId, lineItemNumber, lineNumbers, isLastLineItem, position) {
	console.log('Clicked to relocate: ', $(event.target).parent().parent().parent().parent());

	$('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
		$($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
			// if ($(ele).hasClass('movehandle') || $(ele).hasClass('tallyaction')) { return; }
			if ($(ele).hasClass('movehandle')) { return; }

			$(ele).css('opacity', 0.4);
			$(ele).css('cursor', 'not-allowed');
			$($(ele).children()[0]).css('pointer-events', 'none');
		});
	});

	if (relocationSourceLineItem == undefined) {
		relocationSourceLineItem = $(event.target).parent().parent().parent().parent();
		if (!$('#line-relocation-active-status-label').attr('value')) {
			const sourceLineItemPageNo = parseInt($('#quoteitems').DataTable().page()) + 1;
			let sourceLineItemLineNo = $('tr[id='+relocationSourceLineItem.attr('id')+'] > td.linenumber >> input[type=number]').val();
			if (!sourceLineItemLineNo) {
				sourceLineItemLineNo = $('tr[id='+relocationSourceLineItem.attr('id')+'] > td.linenumber > div').text().split('\n')[0].trim();
			}
			$('#line-relocation-active-status-label').attr('value', `Relocating Line ${sourceLineItemLineNo}, Page ${sourceLineItemPageNo}`);
		}
		$('#line-relocation-active-status-label').css('display', 'block');
	} else {
		// alert('relocation destination line item');
		relocationDestinationLineItem = $(event.target).parent().parent().parent().parent();

		if (relocationSourceLineItem.attr('id') == relocationDestinationLineItem.attr('id')) {
			relocationSourceLineItem = undefined;
			$('table#quoteitems tbody tr.quotelineitem td.actionscol').each(function(index, elem) {
				$($($(elem).children()[0]).children()[0]).children().each(function(innerIndex, ele) {
					// if ($(ele).hasClass('movehandle') || $(ele).hasClass('tallyaction')) { return; }
					if ($(ele).hasClass('movehandle')) { return; }

					$(ele).css('opacity', 1);
					$(ele).css('cursor', 'pointer');
					$($(ele).children()[0]).css('pointer-events', 'auto');
				});
			});
			const relocationModal = document.getElementById('relocation-modal');
			relocationModal.style.display = 'none';
			$('#line-relocation-active-status-label').removeAttr('value');
			$('#line-relocation-active-status-label').css('display', 'none');
			return;
		}

		const sourceLineItemId = relocationSourceLineItem.attr('data-lineitemid');

		let destinationLineItemId = relocationDestinationLineItem.attr('data-lineitemid');
		if (position == 'below-end-line-item') {
			destinationLineItemId = 'last';
		}

		const lineItemIds = lineNumbers.toString().split(',').map(arg => arg.toString().split(';;')[0].trim());
		lineItemIds.push('last');

		let sourceLineItemNumber = $($(relocationSourceLineItem.children()[1]).children()[0]).children().val();
		console.log(`BEFORE: sourceLineItemNumber - ${sourceLineItemNumber}`);
		if (!sourceLineItemNumber) {
			sourceLineItemNumber = $($(relocationSourceLineItem.children()[1]).children()[0]).text().split('\n')[0].trim();
		}
		console.log(`AFTER: sourceLineItemNumber - ${sourceLineItemNumber}`);

		$.ajax({
			type: "POST",
			url: '/quotes/sortpaginatedlineitem/'+sourceLineItemId+'/'+destinationLineItemId+'/<?php echo $ordermode; ?>',
			contentType: 'application/json',
			data: JSON.stringify({
				sourceLineItemNumber: sourceLineItemNumber,
				quoteID: "<?php echo $quoteID; ?>",
				pageSize: lineItemsPaginatedTable.page.len(),
				lineItemIds: lineItemIds // Send as a JSON string
			}),
			success: function(response) {
				console.log('Relocation response: ', response);
				let relocationResponse = JSON.parse(response);
				let url = "";
				if (<?php echo $mode == 'order' || $mode == 'editorderlines' ? 1 : 0; ?>) {
					url = "/orders/editlines/" + orderID + "/workorder";
				}
				if (<?php echo $mode == 'quote' ? 1 : 0; ?>) {
					url = "/quotes/add/" + quoteID + "/workorder";
				}
				url += "?page=" + relocationResponse.page + "&li_no=" + relocationResponse.li_no;
				window.location.href = url;
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		});


	}
}
/*PPSASCRUM-139: end*/
</script>

<style>

/*For 211 UI in firefox. as part of 297 comment start*/
/* Firefox-specific rule: Prevent the styles from being applied in Firefox */
@-moz-document url-prefix() {
    .row {
    margin: 0 !important;
    max-width: none !important;
    width: auto !important;
  }
}
/*For 211 UI in firefox. as part of 297 comment end*/
</style>
