<!-- src/Template/Quotes/addstep2.ctp -->
<style>
select#quotetypeid{ width:145px; }
</style>
<?php
if($quoteData['status'] == 'editorder'){
//echo "<pre>"; print_r($totals); echo "</pre>";
?>
<style>
tr.hasbatches{ background:#F4D9FD !important; border: 3px dashed purple !important; }



#canceleditorderlinesbutton{ position:fixed; bottom:-5px; right:175px; z-index:6666; background:#777; padding:5px 5px 5px 5px !important; font-size:13px !important; }
#canceleditorderlinesbutton:hover{ background:#999; color:#fff; }

#verifyeditorderlinesbutton{ position:fixed; bottom:-5px; right:266px; z-index:6666; background:#777; padding:5px 5px 5px 5px !important; font-size:13px !important; }
#verifyeditorderlinesbutton:hover{ background:#999; color:#fff; }


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

button#jumptoformtoggle.toggleoff{ left:8px !important; }

#changeproblemslist{ width:620px; border:3px solid red; padding:4px; background:#FFCFBF; position:fixed; bottom:55px; right:20px; z-index:555555; min-height:40px; }
#changeproblemslist ul{list-style:none; margin:0 0 8px 0; }
#changeproblemslist ul li{ color:red; font-weight:bold; font-size:13px; }
#changeproblemslist ul li small{ font-size:11px; color:#111; padding-left:18px; }
</style>

<div id="editbordertop"></div>
<div id="editborderleft"></div>
<div id="editborderright"></div>
<div id="editborderbottom"></div>
<div id="remindereditingorder"><img style="vertical-align:middle;" src="/img/alert.png"/> You are in <em>Edit Order</em> mode, for your changes to be applied to the order, you must click <em>APPLY CHANGES</em> in bottom right corner.</div>
<script>
$(function(){
   $('#editborderleft,#editborderright').height($(window).height()+40); 
});
</script>
<?php }else{ ?>
<style>
button#jumptoformtoggle.toggleoff{ left:0 !important; }
</style>
<?php } ?>
<div class="newquote step2 form">
<?php
echo '<div class="verifiedMessage" style="
    background: #B3f5AE;
    border: 1px solid green;
    text-align: center !important;
    display: none;
">All Extended Prices have been Verified OK</div>';
echo $this->Form->create(); 
	
if($quoteData['expires'] < time() && $mode != 'order'){
	echo "<div class=\"expiremessage\">This Quote is EXPIRED.</div>";
}
	
echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);
echo "<div id=\"newquoteheading\">
    <div id=\"quoteheadingleft\">";

    if($mode=='order' || $mode=='editorderlines'){
	    echo "<h2>Order# <u>".$orderData['order_number']."</u> &nbsp;&nbsp;&nbsp;<a href=\"/orders/vieworderschedule/".$orderData['id']."\" target=\"_blank\"><img src=\"/img/view.png\" alt=\"Batch Breakdown\" title=\"Batch Breakdown\" /></a> &nbsp;<a href=\"/orders/edit/".$orderData['id']."\"><img src=\"/img/edit.png\" alt=\"Edit Order Details\" title=\"Edit Order Details\" /></a> &nbsp;<a href=\"/orders/woboxes/".$orderData['id']."\" target=\"_blank\"><img src=\"/img/box-icon.png\" width=\"24\" alt=\"Work Order Boxes\" title=\"Work Order Boxes\" /></a></h2>";


	    if($quoteData['status'] != 'orderplaced'){
		    echo "<h3>Original Quote# <u>".$quoteData['quote_number']."</u></h3>";
	    }else{
		    echo "<h3>Original Quote# <u>N/A</u></h3>";
	    }

	    echo "<h3>Order Title <u>".$quoteData['quote_title']."</u>";
	    echo "&nbsp;&nbsp;&nbsp;<label>Type:</label> ";
	    
	    if($mode=='order'){
	        foreach($allTypes as $type){
	            if(isset($orderData['type_id']) && !is_null($orderData['type_id']) && $orderData['type_id'] == $type['id']){
	                echo "<B>".$type['type_label']."</B>";
	            }
	        }
	    }else{
	        
	        echo "<select id=\"quotetypeid\" name=\"type_id\" onchange=\"changeordertype(this.value)\">";
    	    if(is_null($orderData['type_id']) || $orderData['type_id'] == 0){
    	        echo "<option value=\"0\" selected disabled>--Select Type--</option>";
    	    }
    	
        	foreach($allTypes as $type){
        	    echo "<option value=\"".$type['id']."\"";
        	    if(isset($orderData['type_id']) && !is_null($orderData['type_id']) && $orderData['type_id'] == $type['id']){
        	        echo " selected=\"selected\"";
        	    }
        	    echo ">".$type['type_label']."</option>";
        	}
        	echo "</select>";
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

	
    }elseif($mode=='quote'){
	    echo "<h2>Quote ";
	    if(is_numeric($quoteData['quote_number']) && $quoteData['quote_number'] > 1){
    		echo  "#".$quoteData['quote_number']." ";
	    }
	    if($quoteData['revision'] >0){
		    echo "<span style=\"color:blue;\">Revision ".$quoteData['revision']."</span>&nbsp;&nbsp;<a style=\"font-weight:normal;font-size:12px; color:red;\" href=\"/quotes/add/".$quoteData['parent_quote']."\" target=\"_blank\">View Origin</a>&nbsp;&nbsp;<a href=\"/quotes/?search=".$quoteData['quote_number']."\" target=\"_blank\" style=\"color:green;font-size:12px;font-weight:normal;\">Show All Revisions</a>";
	    }else{
		    if(count($revisions) >1){
			    echo "<br><u>There are ".(count($revisions)-1)." Revisions to this Quote</u>&nbsp;&nbsp;<a href=\"/quotes/?search=".$quoteData['quote_number']."\" target=\"_blank\" style=\"color:green;font-size:12px;font-weight:normal;\">Show All Revisions</a>";
		    }
	    }
	    echo "</h2>
	    <div><label>Quote Title:</label> ";
		if($quoteData['status']=="draft"){
			echo "<input type=\"text\" name=\"quotetitle\" id=\"quotetitle\" tabindex=\"1\" placeholder=\"Quote Title\" value=\"".$quoteData['title']."\" />";
		}else{
			echo "<b>".$quoteData['title']."</b>";
		}
	echo "&nbsp;&nbsp;&nbsp;<label>Type:</label> <select id=\"quotetypeid\" name=\"type_id\" onchange=\"changequotetype(this.value)\">";
	if(is_null($quoteData['type_id']) || $quoteData['type_id'] == 0){
	    echo "<option value=\"0\" selected disabled>--Select Type--</option>";
	}
	
	foreach($allTypes as $type){
	    echo "<option value=\"".$type['id']."\"";
	    if(isset($quoteData['type_id']) && !is_null($quoteData['type_id']) && $quoteData['type_id'] == $type['id']){
	        echo " selected=\"selected\"";
	    }
	    echo ">".$type['type_label']."</option>";
	}
	echo "</select>";
	
	echo "</div>
	<div id=\"projectfieldwrap\"";
	if($quoteData['type_id'] != '1'){ echo " style=\"display:none;\""; } 
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
	<div><label>Quote Status:</label> ";
		if($quoteData['status']=="published"){
			echo "Published";
		}else{
			echo "<select name=\"quote-status\" id=\"quote-status\"><option value=\"draft\"";
			if($quoteData['status']=="draft"){ echo " selected"; }
			echo ">Draft</option><option value=\"published\"";
			if($quoteData['status']=="published"){ echo " selected"; }
			echo ">Published</option></select>";
		}
		echo " &nbsp;&nbsp;Last Modified: <b>".date('n/j/Y g:iA',$quoteData['modified'])."</b></div>";
    }


    echo "<button type=\"button\" onclick=\"pmimodal('".$quoteData['id']."')\" style=\"padding:4px 4px 4px 4px !important; font-size:11px !important;\">PM Surcharge Configuration</button>&nbsp;&nbsp;&nbsp;";

    //if($quoteData['status'] == 'published' || $quoteData['status'] == 'orderplaced'){
    if($mode=='order'){
        echo "<button type=\"button\" onclick=\"location.href='/quotes/editpublishedorder/".$orderData['id']."';\" style=\"padding:4px 4px 4px 4px !important; font-size:11px !important;\">Edit This Order</button>";
    }elseif($quoteData['status'] == 'editorder'){
        
        $oldquoteid=$orderData['quote_id'];
        $newquoteid=$quoteData['id'];
        
        if(count($ruleErrors) > 0){
            echo "<div id=\"changeproblemslist\"><ul>";
            foreach($ruleErrors as $errorMsg){
                echo "<li>".$errorMsg."</li>";
            }
            echo "</ul></div>";
        }
        
		echo "<button type=\"button\" onclick=\"location.href='/quotes/canceleditordermode/".$orderData['id']."';\" id=\"canceleditorderlinesbutton\">Cancel Edit</button> <button ";
       // if(count($ruleErrors) > 0){ echo "disabled=\"disabled\" "; } echo "type=\"button\" onclick=\"location.href='/quotes/overwriteorderfromedit/".$orderData['id']."/".$oldquoteid."/".$newquoteid."';\" id=\"savechangesbigbutton\">APPLY CHANGES</button>";
         if(count($ruleErrors) > 0){ echo "disabled=\"disabled\" "; } echo "type=\"button\" onclick=\"verifyApplyQuoteItems('".$orderData['id']."','".$oldquoteid."','".$newquoteid."')\"  id=\"savechangesbigbutton\">APPLY CHANGES</button>";
    }
    echo "<script>
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
    
    function verifyQuoteItems(quoteid){
      $('#exPriceFailedMessage').empty();
      $('.verifiedMessage').css('display','none');
     $.fancybox.showLoading();		
		$.get('/quotes/verifyeditordermode/'+quoteid,function(data){
			$.fancybox.hideLoading();	
			if(data=='OK'){	
    		$.fancybox.hideLoading();
    		$('.verifiedMessage').css('display','block');
            window.scrollTo(0, 0);
    		$('.verifiedMessage').delay(5000).fadeOut('slow');

    }	else {
                    $('.verifiedMessage').css('display','none');
                    $('#exPriceFailedMessage').append(data);
    			    $.fancybox.hideLoading();
       $.fancybox({
        'modal' : true,
        'content' : \"<div style='margin:1px;width:240px;'>The Extended Price on some of the Quote Line Items has been incorrectly calculated.<div style='margin:1px;width:240px;'> Please fix those values before attempting to Publish this Quote. <div style='text-align:right;margin-top:10px;'><input style='margin:3px;padding:0px;' type='button' onclick='jQuery.fancybox.close();' value='OK'></div></div></div>\"
    });
    			}
	});	
	}
	
	function verifyApplyQuoteItems(orderid,oldquoteid,newquoteid){
		$('#exPriceFailedMessage').empty();
		$('.verifiedMessage').css('display','none');
		$.get('/quotes/verifyeditordermode/'+newquoteid,function(data){
			if(data=='OK'){	
    			  $('.verifiedMessage').css('display','block');
    			  location.href='/quotes/overwriteorderfromedit/'+orderid+'/'+oldquoteid+'/'+newquoteid;
  
	         }else {
				 $('.verifiedMessage').css('display','none');
				 $('#exPriceFailedMessage').append(data);
        		 $.fancybox({
        		  'modal' : true,
        		  'content' : \"<div style='margin:1px;width:240px;'>The Extended Price on some of the Quote Line Items has been incorrectly calculated.<div style='margin:1px;width:240px;'> Please fix those values before attempting to Publish this Quote. <div style='text-align:right;margin-top:10px;'><input style='margin:3px;padding:0px;' type='button' onclick='jQuery.fancybox.close();' value='OK'></div></div></div>\"
        	        });
			}
		 });	
	  }
	  
    </script>";

    if($mode=='editorderlines'){
        echo '<style>
        button#savechangebutton{
            position:fixed; cursor:pointer; bottom:0; right:0; margin:0 0 0 0; z-index:5555; background:#008800; color:#FFF; padding:15px 30px; border:1px solid #000; font-size:22px; font-weight:bold;
        }
        button#savechangebutton:hover{
            background:#00FF00;
        }
        </style>';
    
    }

    if($mode=='order'){
	
    	echo "<script>
    	function genworkorder(){
    		$.fancybox({
    			'type':'iframe',
    			'modal':false,
    			'width':500,
    			'height':260,
    			'autoSize':false,
    			'href': '/quotes/generateworkorderform/".$quoteID."'
    		});
    	}
    	</script>";
	
    	if($quoteData['status'] != 'editorder'){
        	echo "<div id=\"exportbuttons\">";
        	if($mode!='order'){
				echo "<a href=\"javascript:genworkorder()\" id=\"pdfexport\"><img src=\"/img/pdf.png\" />Work Order PDF</a>
					&nbsp;&nbsp; 
					<a href=\"/orders/packingslipform/".$orderData['id']."/Packing%20Slip%20-%20Order%20".$orderData['order_number'].".pdf\" id=\"pdfpackslip\" target=\"_blank\"><img src=\"/img/pdf.png\" /> Create Packing Slip</a>
					&nbsp;&nbsp; 
					<a href=\"/orders/buildbompdf/".$orderData['id']."/Order ".$orderData['order_number']." BOM.pdf\" id=\"pdfbom\" target=\"_blank\"><img src=\"/img/pdf.png\" /> Bill of Materials</a>
					&nbsp;&nbsp; 
					<a href=\"/orders/bombreakdown/".$orderData['id']."/Order ".$orderData['order_number']." BOM Breakdown.pdf\" id=\"pdfbombreakdown\" target=\"_blank\"><img src=\"/img/pdf.png\" /> BOM Breakdown</a>
					&nbsp;&nbsp; ";
			}
        echo "	<a href=\"/quotes/buildquotepdf/".$quoteID."/HCI%20Quote%20".$quoteData['quote_number'];
        
        	if($quoteData['revision'] >0){
        	 echo "%20REV%20".$quoteData['revision'];
        	}
        
        	echo ".pdf\" target=\"_blank\" id=\"pdfquoteexport\"><img src=\"/img/pdf.png\" />Quote PDF</a>
        	&nbsp;&nbsp;
        	<a href=\"/quotes/buildquotesummary/".$quoteID."/HCI%20Order%20".$orderData['order_number']."%20Summary.pdf\" id=\"pdfquotesummary\" target=\"_blank\"><img src=\"/img/pdf.png\" /> Summary PDF</a></div>";
    	}
    }else{
        if($quoteData['status'] != 'editorder'){
        	if($quoteData['status']=="published"){
        		echo "<div id=\"exportbuttons\"><a href=\"/quotes/buildquotepdf/".$quoteID."/HCI%20Quote%20".$quoteData['quote_number'];
        
            	if($quoteData['revision'] >0){
            	 echo "%20REV%20".$quoteData['revision'];
            	}
            
            	echo ".pdf\" target=\"_blank\" id=\"pdfexport\"><img src=\"/img/pdf.png\" />Download PDF</a> <!--<a href=\"/quotes/buildquotexls/".$quoteID.".xlsx\" type=\"button\" id=\"xlsexport\" target=\"_blank\"><img src=\"/img/excel.png\" />Download XLS</a>--></div>";
        	}else{
        		echo "<div id=\"exportbuttons\"><a href=\"/quotes/buildquotepdf/".$quoteID."/HCI%20Quote%20".$quoteData['quote_number'];
        
            	if($quoteData['revision'] >0){
            	 echo "%20REV%20".$quoteData['revision'];
            	}
        
        	    echo ".pdf\" target=\"_blank\" id=\"pdfexport\"><img src=\"/img/pdf.png\" />PREVIEW QUOTE PDF</a> <!--<a href=\"/quotes/buildquotexls/".$quoteID.".xlsx\" type=\"button\" id=\"xlsexport\" target=\"_blank\"><img src=\"/img/excel.png\" />PREVIEW QUOTE XLS</a>--></div>";
    	    }
        }
    }
	
    echo "</div>";

if($customerData['status']=='OnHold'){
    echo "<div id=\"onholdalert\"><img src=\"/img/stopsignicon.png\" /> THIS CUSTOMER IS <em>ON HOLD</em></div>
    <style>
    #onholdalert{ text-align:center; position:fixed; z-index:9999; top:0; left:0; width:100%; background:yellow; padding:10px; font-size:20px; font-weight:bold; color:red; }
    #onholdalert img{ vertical-align:middle; width:28px; height:28px; }
    </style>";
}
/** PPSASCRUM-3 Start **/echo ($customerData['on_credit_hold']) ? '<div><span ><h2><b style="color: red;"> On Credit Hold</b><h2></span></div>' : "";/** PPSASCRUM-3 End **/

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
if($mode=='editorderlines' || $mode=='order'){
    echo "<br><br><b>Order Date: </b><em>"; echo date('n/j/Y',$orderData['created'])."</em><br>";
    echo "<b>Project Manager: </b><em>"; echo $thisProjectManagerName."</em><br>";
    echo "<b>Facility: </b><em>"; echo $orderData['facility']."</em><br>";
    echo "<b>Ship-By Date : </b><em>"; echo $thisShipByDate."</em><br>";
}
echo "<br/>";
echo "<br><br><b>On Credit Hold: </b><em>";echo ($customerData['on_credit_hold']) ? "Yes<br>" : "No"."</em><br>";
echo "<b>Deposit Threshold: </b><em>$";echo $customerData['deposit_threshold']."</em><br>";
echo "<b>Deposit %: </b><em>";echo $customerData['deposit_perc']."%</em><br>";
echo "<b>Terms : </b><em>";echo $customerData['payment_terms']."</em><br>";
/** PPSASCRUM-3 End **/
echo "</div>
<div style=\"clear:both;\"></div></div>";


echo "<div id=\"jumptoform\" class=\"toggleoff\">
<button type=\"button\" onclick=\"jumpToLine(1);\">Jump To First Line</button>
<label>Jump To Line: <input type=\"number\" min=\"1\" id=\"jumptolinenumber\" /></label><button type=\"button\" onclick=\"jumpToLine($('#jumptolinenumber').val());\">Go</button>
<button type=\"button\" onclick=\"jumpToBottom();\">Jump To Last Line</button>
</div>
<button id=\"jumptoformtoggle\" class=\"toggleoff\" type=\"button\" onclick=\"toggleShowJumpForm(false)\">JumpTo</button>

<script>
$(function(){
	var unhoverclosejumpform;
	$('#jumptoform').hover(function(){
		clearTimeout(unhoverclosejumpform);
	},function(){
		if(!$('#jumptoform').hasClass('toggleoff')){
			unhoverclosejumpform=setTimeout('toggleShowJumpForm(true)',2000);
		}
	});
});


function addpurchasingnotebom(quoteid,type,typeid){
    $.fancybox({
		'type':'iframe',
		'href':'/quotes/newbompurchasingnote/'+quoteid+'/'+type+'/'+typeid,
		'autoSize':false,
		'width':420,
		'height':400,
		'modal':true,
		helpers: {
			overlay: {
				locked: false
			}
		}
	});
}


function deletepurchasingnote(noteID){
    if(confirm('Are you sure you want to delete this purchasing note?')){
        $.fancybox.showLoading();
		$.get('/quotes/deletepurchasingnote/'+noteID,function(data){
			if(data==\"OK\"){
				updateQuoteTable();
				$.fancybox.hideLoading();
			}
		});
    }
}


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
	$('table#quoteitems tbody tr').each(function(){

		var thisLineNumber;
		if($(this).find('td.linenumber input[type=number]').length){
			thisLineNumber=parseInt($(this).find('td.linenumber input[type=number]').val());
		}else{
			thisLineNumber=parseInt($(this).find('td.linenumber').html());
		}


		if(thisLineNumber == line && foundLine==0){
			
			var thisScrollTop=(($(this).offset().top)-35);
			foundLine++;

			$([document.documentElement, document.body]).animate({
        		scrollTop: thisScrollTop
    		}, 350);

		}
	});
}

function jumpToBottom(){
	var lastline;
	if($('table#quoteitems tbody td.linenumber:last').find('input[type=number]').length){
		lastline=parseInt($('table#quoteitems tbody td.linenumber:last').find('input[type=number]').val());
	}else{
		lastline=parseInt($('table#quoteitems tbody td.linenumber:last').html());
	}

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

	
echo "<div id=\"quotetablewrap\">

</div>";


echo $this->Form->end();
?>
</div>
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
#quotetablewrap{ min-width:2455px; }
img.movehandle{ cursor:move; }

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
table#quoteitems tbody tr td table tr td,
table#quoteitems tbody tr td table tr td{
	border:1px solid #BBB;
}
	
table#quoteitems thead tr th table tr,
table#quoteitems thead tr th table tr th{
	border:1px solid #004A87;
}
	
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

#quoteitems tbody tr td table tr td input{ margin:0 0 10px 0; }

	
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
table#quoteitems tr th table tr th, table#quoteitems tr td table tr td{ padding:4px 4px 4px 4px !important; }
table#quoteitems thead{ background:#004A87; color:#FFF; }
table#quoteitems thead tr th{ color:#FFF; font-weight:bold; font-size:10px; }
table#quoteitems tbody tr td{ font-size:11px; }
	
table#quoteitems > thead > tr > th,
table#quoteitems > tbody > tr > td{
	padding:0 !important;
}
	
table#quotefooter tr#subtotalLine,
table#quotefooter tr#discountsLine,
table#quotefooter tr#taxLine{ font-weight:bold; }
table#quotefooter tr#totaldueLine{ font-weight:bold; }


.priceoverridenotice{ font-size:10px; color:#440000; background:#ffffd8; padding:2px; display:inline-block; line-height:12px; margin:5px 0 5px 8px; border:1px solid #d1774d; }
.priceoverridenotice a{ font-weight:bold; }

table#quotefooter tr td a{ color:#000 !important; text-decoration:none !important; font-size:11px; }

table#quoteitems{ border-bottom:2px solid #004A87 !important; }
table#quoteitems tbody tr.lightrow{ background:#f8f8f8; }
table#quoteitems tbody tr.darkrow{ background:#e8e8e8; }
table#quoteitems tbody tr.hoverRow{ background:#D2E2E9 !important; }
table#quoteitems tbody tr.highlightedfabric{ background:#F5E889 !important; }

table#quoteitems tbody tr.quotelineitem td{ border-top:2px solid #004A87 !important; border-bottom:1px solid #CCC !important; }
	
.linedescription{ font-size:11px; font-style:italic; }

#quotetitle{ width:300px !important; display: inline-block; }

#quoteitems tr td table,
#quoteitems tr th table{ margin-bottom:0 !important; }
	
#quoteitems table,
#quoteitems table tr.innerrow{ background:none !important; }

table#quotefooter tr#subtotalLine td,
table#quotefooter tr#discountsLine td,
table#quotefooter tr#surchargeLine td,
table#quotefooter tr#taxLine td{ color:#000; font-weight:normal; }
table#quotefooter tr#totaldueLine td{ color:#000; }

table#quotefooter tr td.alignleft{ text-align:left !important; }
table#quotefooter tr td.alignright{ text-align:right !important; }

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
	
	
div.tallyaction{ padding:20px 2px 10px 2px; }
</style>
<script>
/*
function doGoChildLineCatchall(){
    location.href="/quotes/newlineitem/<?php echo $quoteData['id']; ?>/newcatchall-"+$('#catchall-type').val()+"/"+$('input#addchild_linenumber').val()+"/";
}

function addChildLine(lineNumber){
    $.fancybox({
       'type':'html',
       'content':'<h3>Add a Child Line to Line# '+lineNumber+'</h3><input type="hidden" id="addchild_linenumber" value="'+lineNumber+'"><p>Select a Catch-All Type: <select id="catchall-type"><option value="0" selected disabled>--Select--</option><option value="misc">Miscellaneous</option><option value="hardware">Hardware</option><option value="service">Services</option><option value="bedding">Bedding</option><option value="cubicle">Cubicle Curtains</option><optgroup label="Soft Window Treatments">	<option value="valance">Valance Catch-All</option><option value="drapery">Drapery Catch-All</option><option value="cornice">Cornice Catch-All</option><option value="swtmisc/">SWT Misc Catch-All</option></optgroup><optgroup label="Hard Window Treatments"><option value="blinds">Blinds Catch-All</option><option value="shades">Shades Catch-All</option><option value="shutters">Shutters Catch-All</option><option value="hwtmisc">HWT Misc Catch-All</option></optgroup></select></p><p><button type="button" onclick="$.fancybox.close()" style="float:left; background:#CCC !important; color:#000; border:1px solid #000; font-size:12px; padding:4px 6px;">Cancel</button> <button style="float:right;" type="button" onclick="doGoChildLineCatchall()">Continue</button><div style="clear:both;"></div></p>',
       'autoSize':false,
       'width':400,
       'height':250,
       'modal':true
    });
}
*/

function checkdefaultdaultrows(){
    if($('#quoteitems tbody tr.class8').length > 0 && $('#quoteitems tbody tr.subclass25').length > 0){
        
        //see if the rules error shows, and add that many PX to the "bottom" css definition
        if($('#changeproblemslist').is(':visible') && $('#changeproblemslist').height() > 0){
            $('#defaultsalert').css('bottom',($('#changeproblemslist').height() + 75)+'px');
        }else{
            $('#defaultsalert').css('bottom','55px');
        }
        
        $('#defaultsalert').show();
        //console.log('SHOW DEFAULTS ALERT');
    }else{
        $('#defaultsalert').css('bottom','55px');
        $('#defaultsalert').hide();
        //console.log('HIDE DEFAULTS ALERT');
    }
}

setInterval('checkdefaultdaultrows()',800);


function str_replace(search, replace, subject, countObj) {
  var i = 0
  var j = 0
  var temp = ''
  var repl = ''
  var sl = 0
  var fl = 0
  var f = [].concat(search)
  var r = [].concat(replace)
  var s = subject
  var ra = Object.prototype.toString.call(r) === '[object Array]'
  var sa = Object.prototype.toString.call(s) === '[object Array]'
  s = [].concat(s)

  var $global = (typeof window !== 'undefined' ? window : global)
  $global.$locutus = $global.$locutus || {}
  var $locutus = $global.$locutus
  $locutus.php = $locutus.php || {}

  if (typeof (search) === 'object' && typeof (replace) === 'string') {
    temp = replace
    replace = []
    for (i = 0; i < search.length; i += 1) {
      replace[i] = temp
    }
    temp = ''
    r = [].concat(replace)
    ra = Object.prototype.toString.call(r) === '[object Array]'
  }

  if (typeof countObj !== 'undefined') {
    countObj.value = 0
  }

  for (i = 0, sl = s.length; i < sl; i++) {
    if (s[i] === '') {
      continue
    }
    for (j = 0, fl = f.length; j < fl; j++) {
      temp = s[i] + ''
      repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0]
      s[i] = (temp).split(f[j]).join(repl)
      if (typeof countObj !== 'undefined') {
        countObj.value += ((temp.split(f[j])).length - 1)
      }
    }
  }
  return sa ? s : s[0]
}

function escapeTextFieldforURL(thetext){
	var output = str_replace('?','__question__',thetext);
	output = str_replace('#','__pound__',output);
	output = str_replace(' ','__space__',output);
	output = str_replace('/','__slash__',output);
	return output;
}


function changeordertype(newval){
    $.ajax({
		  type: "POST",
		  url: '/orders/changeordertype/<?php echo $orderData['id']; ?>/'+newval,
		  data: {},
		  success: function(result){
			  //done
			  
			  if(newval != 1){
			      $('#projectfieldwrap').hide();
			  }else if(newval == 1){
			      $('#projectfieldwrap').show();
			  }
			  
		  },
		  dataType:'text'
		});
}


function changequotetype(newval){
    $.ajax({
		  type: "POST",
		  url: '/quotes/changequotetype/<?php echo $quoteID; ?>/'+newval,
		  data: {},
		  success: function(result){
			  //done
			  
			  if(newval != 1){
			      $('#projectfieldwrap').hide();
			  }else if(newval == 1){
			      $('#projectfieldwrap').show();
			  }
			  
		  },
		  dataType:'text'
		});
}


function updateProjectID(projectid){
	
	if(projectid=='createnew'){
		
		$.fancybox({
			'type':'iframe',
			'href':'/customers/projects/add/iframe/<?php echo $quoteData['customer_id']; ?>',
			'autoSize':false,
			'width':580,
			'height':600,
			'modal':true,
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
		
	}else{
		$.ajax({
		  type: "POST",
		  url: '/quotes/changequoteproject/<?php echo $quoteID; ?>/'+projectid,
		  data: {},
		  success: function(result){
			  //done
		  },
		  dataType:'text'
		});
	}
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


function loadTrackBreakdown(lineID){
	$.fancybox({
		'type':'iframe',
		'href': '/quotes/viewtrackbreakdown/'+lineID,
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


function deleteLineItem(lineitemid){
	if(confirm('Are you sure you want to delete this line item?')){
		$.fancybox.showLoading();
		$.get('/quotes/deletelineitem/'+lineitemid,function(data){
			if(data=="OK"){
				updateQuoteTable();
				$.fancybox.hideLoading();
			}
		});
	}
}

function editLineItem(lineitemid){
	/*$.fancybox({
		'type':'iframe',
		'href':'/quotes/editlineitem/'+lineitemid,
		'autoSize':false,
		'width':750,
		'height':700,
		'modal':true
	});*/
	location.href='/quotes/editlineitem/'+lineitemid;
}
	
	
function editCalcLineItem(lineitemid){
	$.fancybox({
		'type':'iframe',
		'href':'/quotes/editcalclineitem/'+lineitemid,
		'autoSize':false,
		'width':750,
		'height':700,
		'modal':true
	});
}
	

function lineItemPriceOverride(lineitemid,newprice){
	$.fancybox.showLoading();
	$.get('/quotes/overridelineitemprice/'+lineitemid+'/'+newprice,function(data){
		if(data=="OK"){
			updateQuoteTable();
			$.fancybox.hideLoading();
		}
	});
}

	
function resetLineItemPrice(lineitemid,resetprice){
	$.fancybox.showLoading();
	$.get('/quotes/overridelineitemprice/'+lineitemid+'/'+resetprice,function(data){
		if(data=="OK"){
			updateQuoteTable();
			$.fancybox.hideLoading();
		}
	});
}

function addQty(lineitemid,currentqty){
	var newval=(parseInt(currentqty)+1);
	$('#qty_line_item_'+lineitemid).val(newval);
	updateQTYvalue(lineitemid,newval);
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


function editInternalNote(noteid){
	$.fancybox({
	'type':'iframe',
	'href':'/quotes/editlineitemnote/'+noteid,
	'autoSize':false,
	'width':500,
	'height':350,
	'modal':true
	});
}

function subtractQty(lineitemid,currentqty){
	if(currentqty==1){
		return false;
	}
	var newval=(parseInt(currentqty)-1);
	$('#qty_line_item_'+lineitemid).val(newval);
	updateQTYvalue(lineitemid,newval);
}

function updateQuoteTable(){
	$.get('/quotes/updatevals/<?php echo $quoteID; ?>/<?php 
	if($orderData['status'] == 'Editing'){
	    echo 'editorder';
	}else{
	    echo $mode; 
	}
	if(isset($urlparams->query['highlightFabric']) && strlen(trim($urlparams->query['highlightFabric'])) >0){
		echo "?highlightFabric=".$urlparams->query['highlightFabric'];
	}
	?>',function(data){
		$('#quotetablewrap').html(data);
		
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

function updateQTYvalue(lineitemid,newqty){
	$.fancybox.showLoading();
	$.get('/quotes/changeqty/'+lineitemid+'/'+newqty,function(data){
		if(data=="OK"){
			updateQuoteTable();
			$.fancybox.hideLoading();
		}
	});
}

function updatelinenumber(lineitemid,newlinenumber){
	$.fancybox.showLoading();
	$.get('/quotes/changelinenumber/'+lineitemid+'/'+newlinenumber,function(data){
		if(data=="OK"){
			$.fancybox.hideLoading();
		}
	});
}
	
function updateroomnumber(lineitemid,newroomnumber){
	$.fancybox.showLoading();
	$.get('/quotes/changeroomnumber/'+lineitemid+'/'+escapeTextFieldforURL(newroomnumber),function(data){
		if(data=="OK"){
			$.fancybox.hideLoading();
		}
	});
}


function autoHideFlashScreen(){
	$('div.message.success,div.message.error').hide('fast');
}



function updatefabrequirement(fabid,value){
	$.fancybox.showLoading();
	$.ajax({
		  type: "POST",
		  url: '/quotes/updatefabrequirement/<?php echo $quoteID; ?>/'+fabid,
		  data: {'newvalue':value},
		  success: function(result){
			  //done
			  $.fancybox.hideLoading();
		  },
		  dataType:'text'
		});
}


setTimeout('autoHideFlashScreen()',8000);
	
$(function(){

	$('#belowtableactions').width($(window).width());

	$('#quotetitle').keyup($.debounce( 650, function(){
		$.ajax({
		  type: "POST",
		  url: '/quotes/changequotetitle/<?php echo $quoteID; ?>/',
		  data: {'newtitle':$(this).val()},
		  success: function(result){
			  //done
		  },
		  dataType:'text'
		});
	}));



	$('#converttoorder').click(function(){
		location.replace('/quotes/converttoorder/<?php echo $quoteID; ?>');
	});

	
	/*$('#quote-status').change(function(){
		if($(this).val()=='published'){
			if(confirm('Publishing a quote is FINAL, you will no longer be able to make changes to this quote without creating a Revision. Are you sure you want to proceed?')){
				location.href='/quotes/changequotestatus/<?php echo $quoteID; ?>/'+$(this).val();
			}else{
				$(this).val('<?php echo $quoteData['status']; ?>').blur();
				return false;
			}
		}
		
	});*/
	$('#quote-status').change(function(){
	   $('#exPriceFailedMessage').empty();
		if($(this).val()=='published'){
		    $.get('/quotes/verifyeditordermode/<?php echo $quoteID; ?>',function(data){
			$.fancybox.hideLoading();	
			if(data=='OK'){		
    			if(confirm('Publishing a quote is FINAL, you will no longer be able to make changes to this quote without creating a Revision. Are you sure you want to proceed?')){
				location.href='/quotes/changequotestatus/<?php echo $quoteID; ?>/published';
			}else{
				$('#quote-status').val('<?php echo $quoteData['status']; ?>');
				 
                $('#exPriceFailedMessage').append(data);
				return false;
			}			
		   } else {
		       $('#quote-status').val('<?php echo $quoteData['status']; ?>');
                    $('#exPriceFailedMessage').append(data);
		       $.fancybox({
        			'modal' : true,
                    'content' : "<div style='margin:1px;width:240px;'>The Extended Price on some of the Quote Line Items has been incorrectly calculated. <div style='margin:1px;width:240px;'>Please fix those values before attempting to Publish this Quote. <div style='text-align:right;margin-top:10px;'><input style='margin:3px;padding:0px;' type='button' onclick='jQuery.fancybox.close();' value='OK'></div></div></div>"
    		   });
		   } 

		   });
		}
	});
	
	$('#unpublish').click(function(){
		location.href='/quotes/changequotestatus/<?php echo $quoteID; ?>/draft';
	});
	
	$('#dorevision').click(function(){
		location.href='/quotes/clonequote/<?php echo $quoteID; ?>/1/';
	});
	
	$('#doclone').click(function(){
		location.href='/quotes/clonequote/<?php echo $quoteID; ?>/0/';
	});
	
	$('#actions button#addsimple').click(function(){
		location.href='/quotes/newlineitem/<?php echo $quoteID; ?>/simple';
	});

	$('input.qtyvalue').change(function(){
		//save the new QTY, update the table
		alert('test');
		
		
	});
	

	updateQuoteTable();

	
	$('#pricelistpopup').width(($('#addpricelist').outerWidth()-1));
	$('#calculatorpopup').width(($('#addcalculator').outerWidth()-1));
	$('#manualpopup').width(($('#addmanual').outerWidth()-1));
});


function changeLineToInternal(lineitemid,newvalue){
	$.fancybox.showLoading();
	$.get('/quotes/changelinetointernal/'+lineitemid+'/'+newvalue,function(data){
		if(data=='OK'){
			$.fancybox.hideLoading();
			updateQuoteTable();
		}
	});
}
	
	
function changeSherryTallySetting(lineitemid,newvalue){
	$.fancybox.showLoading();
	$.get('/quotes/changelinetally/'+lineitemid+'/'+newvalue,function(data){
		if(data=='OK'){
			$.fancybox.hideLoading();
			updateQuoteTable();
		}
	});
}
	

function changeLineItemPrice(lineItemID,newprice){
	$.fancybox.showLoading();
	$.get('/quotes/changelineitemprice/'+lineItemID+'/'+newprice,function(data){
		if(data=='OK'){
			$.fancybox.hideLoading();
			updateQuoteTable();
		}
	});
}
	
	
function addpricelistitem(itemtype){
	location.href='/quotes/newlineitem/<?php echo $quoteID; ?>/simple/'+itemtype;
}

function addcatchallitem(catchallType){
    location.href='/quotes/newlineitem/<?php echo $quoteID; ?>/newcatchall-'+catchallType;
}
	
function addcalculatoritem(calculator){
	/*
	$.fancybox({
		'type':'iframe',
		'href':'/quotes/newlineitem/<?php echo $quoteID; ?>/calculator/'+calculator+'/',
		'autoSize':false,
		'width':750,
		'height':780,
		'modal':true
	});
	*/
	location.href='/quotes/newlineitem/<?php echo $quoteID; ?>/calculator/'+calculator+'/';
}
</script>