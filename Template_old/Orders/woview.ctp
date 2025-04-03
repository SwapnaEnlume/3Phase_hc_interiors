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



<div class="form">
<?php
echo "<div id=\"newquoteheading\">
    <div id=\"quoteheadingleft\">";

	    echo "<h2>Work Order# <u>".$orderData['order_number']."</u> &nbsp;&nbsp;&nbsp;<a href=\"/orders/vieworderschedule/".$quoteData['order_id']."\" target=\"_blank\"><img src=\"/img/view.png\" alt=\"Batch Breakdown\" title=\"Batch Breakdown\" /></a> &nbsp;<a href=\"/orders/woedit/".$orderData['id']."\"><img src=\"/img/edit.png\" alt=\"Edit Work Order Details\" title=\"Edit Work Order Details\" /></a> &nbsp;<a href=\"/orders/woboxes/".$orderData['id']."\" target=\"_blank\"><img src=\"/img/box-icon.png\" width=\"24\" alt=\"Work Order Boxes\" title=\"Work Order Boxes\" /></a> &nbsp;<a href=\"/orders/editlines/".$orderData['id']."/workorder"><img src=\"/img/soview.png\" width=\"24\" alt=\"View Sales Order\" title=\"View Sales Order\" /></a></h2>";


	    echo "<h3>Original Quote# <u>".$quoteData['quote_number']."</u></h3>";
	    
	    echo "<h1 id=\"womainlabel\">WORK ORDER</h1>";
	    
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



    //echo "<button type=\"button\" onclick=\"pmimodal('".$quoteData['id']."')\" style=\"padding:4px 4px 4px 4px !important; font-size:11px !important;\">PM Surcharge Configuration</button>&nbsp;&nbsp;&nbsp;";

    //if($quoteData['status'] == 'published' || $quoteData['status'] == 'orderplaced'){
    //echo "<button type=\"button\" onclick=\"location.href='/orders/editworkorder/".$orderData['id']."';\" style=\"padding:4px 4px 4px 4px !important; font-size:11px !important;\">Edit This Work Order</button>";
    
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
        updateQuoteTable();
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
#quotetablewrap{ min-width:2155px; }
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
	border:1px solid #2345A0;
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
table#quoteitems thead{ background:#26337A; color:#FFF; }
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

table#quoteitems{ border-bottom:2px solid #26337a !important; }
table#quoteitems tbody tr.lightrow{ background:#f8f8f8; }
table#quoteitems tbody tr.darkrow{ background:#e8e8e8; }
table#quoteitems tbody tr.hoverRow{ background:#D2E2E9 !important; }
table#quoteitems tbody tr.highlightedfabric{ background:#F5E889 !important; }

table#quoteitems tbody tr.quotelineitem td{ border-top:2px solid #26337A !important; border-bottom:1px solid #CCC !important; }
	
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
	
function changeSherryTallySetting(lineitemid,newvalue){
	$.fancybox.showLoading();
	$.get('/orders/changelinetally/'+lineitemid+'/'+newvalue+'/workorder',function(data){
		if(data=='OK'){
			$.fancybox.hideLoading();
			updateQuoteTable();
		}
	});
}

function updateQuoteTable(scrolltoele=''){
	console.log(<?php echo $orderData; ?>);
	console.log("test");
	$.get('/quotes/updatevals/<?php echo $orderData['quote_id']; ?>/workorder<?php
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
</script>