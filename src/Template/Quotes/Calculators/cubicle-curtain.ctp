<style>
body{font-family:'Helvetica',Arial,sans-serif; font-size:medium;}
h1{ text-align:center; font-size:x-large; font-weight:bold; color:#1F2E67; margin:15px 0 0 0; }
form{ text-align:center; width:95%; max-width:750px; margin:20px auto; }
	
form label{ font-weight:bold; float:left; width:68%; text-align:left; font-size:small; vertical-align:middle; }
form input{ float:right; padding:5px !important; height:auto !important; width:26% !important; vertical-align:middle; font-size:12px; margin-bottom:0 !important; }
form select{ float:right; padding:5px !important; height:auto !important; width:26% !important; vertical-align:middle; font-size:12px; margin-bottom:0 !important; }
form div.input{ clear:both; padding:10px 0; }
#calculatedPrice{ clear:both; padding:20px 0; font-size:x-large;  }

#explainmath{ max-width:650px; margin:0 auto; text-align:center; }

#custom-utr-width{ width:130px !important; }

fieldset.fieldsection{ padding:0px !important; margin:0 0 20px 0 !important; background:#dddddd; }

/*fieldset.fieldsection .input{ background:none !important; }*/

.badvalue{ background:#F1B7B8 !important; }
.alertcontent{ background:#F9E6A6 !important; }

<?php
if($userData['scroll_calcs'] == 1){
?>

@media screen{
	#calcformleft{ width:48.5%; float:left; overflow-y:scroll; height:100%; }
	#resultsblock{ width:48.5%; padding:10px; float:right; background:#EEE; overflow-y:scroll; height:100%; }
}

@media print{
	#calcformleft{ width:48.5%; float:left; overflow-y:none !important; height:auto !important; }
	#resultsblock{ width:48.5%; padding:10px; float:right; background:#EEE; overflow-y:none !important; height:auto !important; }
}

<?php	
}
?>



#cannotcalculate{ color:red; text-align: center; font-weight:normal; padding:5px; font-size:12px; display:block; border:1px solid red; background:#FDDDDE; }
	
#calcformleft .selectbox label{ width:55% !important; }
#calcformleft .selectbox select{ width:36% !important; }

#calcformleft input.notvalid,#calcformleft select.notvalid{ border:1px solid red; }
#calcformleft input.validated,#calcformleft select.validated{ border:1px solid green; }
	
.clear{ clear:both; }
#calcformleft{ width:48.5%; float:left; }
#resultsblock{ width:48.5%; padding:10px; float:right; background:#EEE; }

#goadvanced{ font-size:12px; color:#000; background:#ccc; border:1px solid #000; padding:5px 10px; margin:0; display:inline-block; }

#calcformleft div.input:nth-of-type(even){
	background:#f8f8f8;
	display: inline-block;
    width: 96%;
    padding: 1.5%;
    height: auto;
}
#calcformleft div.input:nth-of-type(odd){
	background:#FFF;
	display: inline-block;
    width: 96%;
    padding: 1.5%;
    height: auto;
}

#resultsblock div.input:nth-of-type(even){
	background:#E8E8E8;
	display: inline-block;
    width: 96%;
    padding: 2%;
    height: auto;
}
#resultsblock div.input:nth-of-type(odd){
	background:#f8f8f8;
	display: inline-block;
    width: 96%;
    padding: 2%;
    height: auto;
}


label[for=location]{ width:35% !important; }
#location{ width:60% !important; }


label[for=fabric-half-width-status]{ color:rgb(255, 165, 0); }
label[for=total-fabric-widths]{ color:rgb(255, 165, 0); }
label[for=lining-half-width-status]{ color:rgb(255, 165, 0); }

label[for=valance-fabric-widths]{ color:blue; }
label[for=valance-lining-widths]{ color:blue; }
label[for=fabric-price]{ color:blue; }
label[for=lining-cost]{ color:blue; }
label[for=labor-cost]{ color:blue; }
label[for=price]{ color:blue; }

label[for=fabric-cl]{ color:rgb(0, 128, 0); }
label[for=lining-cl]{ color:rgb(0, 128, 0); }
label[for=fabric-yards]{ color:rgb(0, 128, 0); }
label[for=lining-yards]{ color:rgb(0, 128, 0); }

label[for=price-per-valance]{ color:red; }

form h2{ font-size:medium; font-weight:bold !important; margin:0px 0 5px 0; }

label[for=lining-half-width-status]{ display:none; }
#lining-half-width-status{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }

label[for=fabric-half-width-status]{ display:none; }
#fabric-half-width-status{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }

label[for=total-fabric-widths]{ display:none; }
#total-fabric-widths{ resize:none; font-family:'Helvetica',Arial,sans-serif; width: 91% !important; height: 40px; font-size:12px; text-align:center; background:transparent; border:0; }

label[for=fabric-yards]{ width:40% !important; }
#fabric-yards{ width:54% !important; font-size:12px; }

label[for=fabric-cost]{ width:40% !important; }
#fabric-cost{ width:54% !important; font-size:12px; }

.greenline{ background:#fff; border:0; color:green; }
.redline{ background:#fff; border:0; color:red; }
.grayline{ background:#fff; border:0; font-style:italic; }

.calculatebutton{ float:left; width:40%; padding:5px 0; }
.calculatebutton button{ padding:5px 10px !important; background:#000 !important; font-size:12px; }
.addaslineitembutton{ float:right; width:50%; padding:5px 0; }

#cancelbutton{ font-size:12px !important; padding:5px !important; font-size:14px !important; background:#F7E4E4 !important; color:#000 !important; border:1px solid #534546 !important; }

#resultsblock .input label{ width:55% !important; }
#resultsblock .input input{ width:42% !important; }
#resultsblock .input select{ width:44% !important; }

	
form .input.checkbox label{ width:90% !important; }
	
#fabricmarkupwrap label{ width:55% !important; }
#fabricmarkupwrap small{ font-style:italic; font-size:11px; color:#6B4546;     display: block; width: 30%; float: right; }
	
#inboundfreightwrap label{ width:55% !important; }
#inboundfreightwrap small{ font-style:italic; font-size:11px; color:#6B4546;     display: block; width: 30%; float: right; }
	
#inbound-freight-custom-value{ float:none !important; display:inline-block; width:100% !important; margin:4px 0 0 0 !important; }
#inbound-freight{ float:none !important; display:block; width:100% !important; margin:0 0 0 0 !important; }
#inboundfreightinnerwrap{ float:right; width:28%; text-align: right }
	
#fabric-markup{ float:none !important; display:block; width:100% !important; margin:0 0 0 0 !important; }
#fabric-markup-custom-value{ float:none !important; display:inline-block; width:100% !important; margin:4px 0 0 0 !important; }
#fabricmarkupinnerwrap{ float:right; width:28%; text-align: right }



label[for=layout-status]{ display:none; }
#layout-status{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }

label[for=rr-fl-status]{ display:none; }
#rr-fl-status{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }


label[for=fw-vs-expected-fw]{ display:none; }
#fw-vs-expected-fw{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }

#fabricname{ width:43%; float:right; }
#fabriccolor{ width:43%; float:right; clear:right; }
#changefabricbutton{ background:#DDD !important; color:#000 !important; padding:2px 2px 2px 2px !important; border:0; font-size:12px !important; float:right; clear:right; }
/*PPSA-33 start*/
#fabric-cost-per-yard-custom{ float:none !important; display:block; width:100% !important; margin:0 0 0 0 !important; }
#fabric-cost-per-yard-custom-value{ float:none !important; display:inline-block; width:100% !important; margin:4px 0 0 0 !important; }

#fabriccostwrap label{ width:55% !important; }
#fabriccostwrap small{ font-style:italic; font-size:11px; color:#ff0000;     display: block; width: 30%; float: right; }
	
#fabriccostwrap-custom-value{ float:none !important; display:inline-block; width:100% !important; margin:4px 0 0 0 !important; }
#fabriccostwrap{ float:none !important; display:block; width:100% !important; margin:0 0 0 0 !important; }
#fabriccostwrapinnerwrap{ float:right; width:28%; text-align: right }
/*PPSA-33 end*/
</style>
<script>
function changefabricmodal(){
    $.fancybox({
        'type':'iframe',
        /* PPSASCRUM-100: start */
        // 'href':'/quotes/changecalcitemfabric/'+$('#fabricid').val(),
        'href':'/quotes/changecalcitemfabric/'+$('#fabricid').val()+'/cubicle-curtain',
		/* PPSASCRUM-100: end */
        'autoSize':false,
        /* PPSASCRUM-100: start */
        /* 'width':450, */
		'width':650,
        /* 'height':300, */
        'height':550,
		/* PPSASCRUM-100: end */
        'modal':true
    });
}
</script>

<?php

echo "<div id=\"scrolloptions\">Calculator Scrolling Columns: ";
if($userData['scroll_calcs'] == 1){
	echo "<b>Enabled</b> | <a href=\"/users/setscrolloption/0\">Disable</a>";
}else{
	echo "<b>Disabled</b> | <a href=\"/users/setscrolloption/1\">Enable</a>";
}
echo "</div>";


if(intval($quoteID) > 0){
	if(isset($isedit) && $isedit=='1'){
		echo "<h1>Edit Calculated Line Item</h1><hr>";
	}else{
		echo "<h1>Add Calculated Line Item</h1><hr>";
	}
}else{
	echo "<h1>Standalone CC Calculator</h1><hr>";
}

echo $this->Form->create();

echo $this->Form->input('overrideLayoutWarnings',['type'=>'hidden','value'=>'0']);
echo $this->Form->input('overrideFinishedWidthWarnings',['type'=>'hidden','value'=>'0']);

echo "<div id=\"calcformleft\">";
//echo "<h2>CC - ".$fabricData['fabric_name']." (".$fabricData['color'].")</h2>";
echo "<h2>Cubicle Curtain</h2>";

echo "<div class=\"input\">
    <label style=\"width:44% !important;\">Fabric/Pattern</label>
    <div id=\"fabricname\">".$fabricData['fabric_name']."</div>
</div>
<div class=\"input\">
    <label style=\"width:44% !important;\">Color</label>
    <div id=\"fabriccolor\">".$fabricData['color']."</div>";
    if($ordermode != 'workorder')  echo "<button id=\"changefabricbutton\" type=\"button\" onclick=\"changefabricmodal()\">Change Fabric/Color</button>";else '';
echo "</div>";

echo $this->Form->input('process',['type'=>'hidden','value'=>'insertlineitem']);
echo $this->Form->input('calculator-used',['type'=>'hidden','value'=>'cubicle-curtain']);

echo $this->Form->input('fabricid',['type'=>'hidden','value'=>$fabricid]);


echo $this->Form->input('width',['type'=>'hidden','value'=>$thisItemMeta['width']]);


echo $this->Form->input('start-price-val',['type'=>'hidden','value'=>$thisItemMeta['start-price-val']]);
echo $this->Form->input('add-on-total-val',['type'=>'hidden','value'=>$thisItemMeta['add-on-total-val']]);
echo $this->Form->input('add-on-text-val',['type'=>'hidden','value'=>$thisItemMeta['add-on-text-val']]);




if(isset($thisItemMeta['advancedfieldsvisible']) && $thisItemMeta['advancedfieldsvisible']=='1'){
	echo $this->Form->input('advancedfieldsvisible',['type'=>"hidden","value"=>'1']);
}else{
	echo $this->Form->input('advancedfieldsvisible',['type'=>"hidden","value"=>'0']);
}


if(isset($thisItemMeta['location'])){
    $locationValue=$thisItemMeta['location'];
}else{
    if(isset($thisLineItem['room_number']) && strlen(trim($thisLineItem['room_number'])) >0){
    	$locationValue=$thisLineItem['room_number'];
    }else{
    	$locationValue='';
    }
}
	/**PPSASCRUM-201 start **/

echo $this->Form->input('quoteType',['type'=>'hidden','value'=>$quoteData['type_id']]); //PPSASCRUM-201

 $typeID=false;
if($quoteData['type_id'] == 1){
    $typeID=true;
    
}
echo $this->Form->input('location',['label'=>'Location','value'=>$locationValue,'required'=>$typeID]);




if(isset($thisItemMeta['com-fabric']) && $thisItemMeta['com-fabric']=='1'){
	$comChecked=true;
}else{
	$comChecked=false;
}
echo $this->Form->input('com-fabric',['type'=>'checkbox','label'=>'COM Fabric?','checked'=>$comChecked]);



if(isset($thisItemMeta['qty']) && is_numeric($thisItemMeta['qty'])){
	$qtyval=$thisItemMeta['qty'];
}else{
	$qtyval='1';
}
//echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','min'=>0,'value'=>$qtyval]);
if($ordermode == 'workorder'  &&  isset($thisItemMeta['qty']))
echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','min'=>0,'value'=>$qtyval,'autocomplete'=>'off', 'readonly'=>true]);
else 
echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','min'=>0,'value'=>$qtyval,'autocomplete'=>'off']);

if($ordermode == 'workorder' && !isset($thisItemMeta['so_line_number'])){
    
        $soLineNumberLists=array();
        foreach($soLineNumberList as $subclass){
            $soLineNumberLists[$subclass['line_number']]=$subclass['line_number'];
        }

    echo $this->Form->label('SOLineNumber');
   
    echo $this->Form->select('so_line_number',$soLineNumberLists,['required'=>false,'empty'=>'--Select SO Line Number--','value'=>$thisItemMeta['so_line_number']]);
}elseif($ordermode == 'workorder'){
    echo $this->Form->label('SOLineNumber');
     $soLineNumberLists=array();
        foreach($soLineNumberList as $subclass){
            $soLineNumberLists[$subclass['line_number']]=$subclass['line_number'];
        }

    echo $this->Form->select('so_line_number',$soLineNumberLists,['disabled'=>true,'empty'=>'--Select SO Line Number--','value'=>$thisItemMeta['so_line_number']]);
}







/**PPSASCRUM-24 Start **/
/**if(isset($thisItemMeta['railroaded']) && $thisItemMeta['railroaded']=='1'){
	$railroadedChecked=true;
	$displayrrwidth='display:block';
}else{
	if($fabricData['railroaded']=='1'){
		$railroadedChecked=true;
		$displayrrwidth='display:block';
	}else{
		$railroadedChecked=false;
		$displayrrwidth='display:none';
	}
} **/

if(isset($thisItemMeta['railroaded'])){
    if($thisItemMeta['railroaded']=='1')
	    $railroadedChecked=true;
	 else 
	   $railroadedChecked=false;
}else{
	if($fabricData['railroaded']=='1'){
		$railroadedChecked=true;
		$displayrrwidth='display:block';
	}else{
		$railroadedChecked=false;
		$displayrrwidth='display:none';
	}
}
/** PPSASSCRUM-24 end **/
echo $this->Form->input('railroaded',['type'=>'checkbox','label'=>'Railroaded','checked'=>$railroadedChecked]);




echo "<fieldset class=\"fieldsection\"><legend>DIMENSIONS</legend>";


if(isset($thisItemMeta['expected-finish-width']) && is_numeric($thisItemMeta['expected-finish-width'])){
	$expectedfinishwidthval=$thisItemMeta['expected-finish-width'];
}else{
	$expectedfinishwidthval='0';
}
echo $this->Form->input('expected-finish-width',['type'=>'number','min'=>'0','value'=>$expectedfinishwidthval,'label'=>'Desired Finished Width']);




if(isset($thisItemMeta['width']) && is_numeric($thisItemMeta['width'])){
	$widthval=$thisItemMeta['width'];
}else{
	$widthval='0';
}

$cutwidthoptions=array('0'=>'Custom');
for($i=36; $i <= 360; $i++){
	if($i % 18 == 0){
		$cutwidthoptions[$i]=$i."\"";
	}
}
echo "<div id=\"utrwidthwrap\" class=\"input selectbox\"";
/* PPSASCRUM-265: start */
/* if($thisItemMeta['railroaded'] == '1' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '1')){
	echo " style=\"display:none;\"";
} */
if (isset($thisItemMeta['railroaded']) && strlen(trim($thisItemMeta['railroaded'])) > 0) {
	if (trim($thisItemMeta['railroaded']) == '1') {
		echo " style=\"display:none;\"";
	}
} else if ($fabricid != 'custom' && $fabricData['railroaded'] == '1') {
	echo " style=\"display:none;\"";
}
/* PPSASCRUM-265: end */
echo ">";
echo "<label>CUT WIDTH</label>";

if(isset($thisItemMeta['width']) && is_numeric($thisItemMeta['width'])){
	echo $this->Form->select('utr-width',$cutwidthoptions,['value'=>$widthval,'id'=>'utr-width']);
}else{
	echo $this->Form->select('utr-width',$cutwidthoptions,['empty'=>'Select Cut Width','id'=>'utr-width']);
}

if(isset($thisItemMeta['custom-utr-width']) && $thisItemMeta['custom-utr-width'] != ''){
    $ifCustomUTRWidth=$thisItemMeta['custom-utr-width'];
    $customUTRwidthDisplay='block';
}else{
    $ifCustomUTRWidth='';
    $customUTRwidthDisplay='none';
}
echo "<div id=\"customutrwidthwrap\" style=\"display:".$customUTRwidthDisplay.";\">";
echo $this->Form->input('custom-utr-width',['type'=>'number','min'=>'1','step'=>'0.01','value'=>$ifCustomUTRWidth]);
echo "</div>";
echo "</div>";


/*

echo "<div id=\"rrwidthwrap\" style=\"".$displayrrwidth."\">";
echo $this->Form->input('rr-width',['label'=>'RR CUT WIDTH','type'=>'number','min'=>'0','step'=>'any','value'=>$widthval]);
echo "</div>";

*/


if(isset($thisItemMeta['length']) && is_numeric($thisItemMeta['length'])){
	$lengthval=$thisItemMeta['length'];
}else{
	$lengthval='0';
}
echo $this->Form->input('length',['type'=>'number','min'=>'0','value'=>$lengthval,'label'=>'FINISH LENGTH']);


echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>FABRIC SPECS &amp; PRICING</legend>";

if(isset($thisItemMeta['fabric-width']) && is_numeric($thisItemMeta['fabric-width'])){
	$fabricwidthval=$thisItemMeta['fabric-width'];
}else{
	$fabricwidthval=$fabricData['fabric_width'];
}
echo $this->Form->input('fabric-width',['type'=>'number','min'=>'0','value'=>$fabricwidthval,'label'=>'Fabric Width']);




echo "<div id=\"verticalrepeatwrap\"";
/* PPSASCRUM-265: start */
/* if($thisItemMeta['railroaded'] == '1' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '1')){
	echo " style=\"display:none;\"";
} */
if (isset($thisItemMeta['railroaded']) && strlen(trim($thisItemMeta['railroaded'])) > 0) {
	if (trim($thisItemMeta['railroaded']) == '1') {
		echo " style=\"display:none;\"";
	}
} else if ($fabricid != 'custom' && $fabricData['railroaded'] == '1') {
	echo " style=\"display:none;\"";
}
/* PPSASCRUM-265: end */
echo ">";

if(isset($thisItemMeta['vertical-repeat']) && is_numeric($thisItemMeta['vertical-repeat'])){
	$verticalrepeatval=$thisItemMeta['vertical-repeat'];
}else{
	if($fabricid=='custom'){
		$verticalrepeatval='0';
	}else{
		$verticalrepeatval=$fabricData['vertical_repeat'];
	}
}
echo $this->Form->input('vertical-repeat',['min'=>'0','value'=>$verticalrepeatval,'label'=>'Vertical Repeat']);
echo "</div>";


if(isset($thisItemMeta['fabric-price-per-yard']) && is_numeric($thisItemMeta['fabric-price-per-yard'])){
	$fabricpriceperyardval=number_format($thisItemMeta['fabric-price-per-yard'],2,'.','');
}else{
	$fabricpriceperyardval='0.00';
}

if(isset($thisItemMeta['fabric-cost-per-yard']) && strlen(trim($thisItemMeta['fabric-cost-per-yard'])) >0){
	$premarkupfabcostvals=array('value'=>$thisItemMeta['fabric-cost-per-yard']);
}else{
	$premarkupfabcostvals=array('value'=>number_format($fabricData['cost_per_yard_cut'],2,'.',','));
}


if($fabricid=="custom"){
	echo $this->Form->input('custom-fabric-cost-per-yard',['type'=>'number','step'=>'any','onkeyup'=>'if(canCalculate()){ doCalculation(); }']);	
}else{

	echo "<div class=\"input selectbox\">";
	echo "<label>Pre-Markup Fab Cost /yd</label>";
	echo $this->Form->select('fabric-cost-per-yard',['cut'=>'Cut - $'.number_format($fabricData['cost_per_yard_cut'],2,'.',','),'bolt'=>'Bolt - $'.number_format($fabricData['cost_per_yard_bolt'],2,'.',','),'case'=>'Case - $'.number_format($fabricData['cost_per_yard_case'],2,'.',',')],$premarkupfabcostvals);
	/*PPSA-33 start*/
    if(isset($thisItemMeta['fabric-cost-per-yard-custom-value']) && strlen(trim($thisItemMeta['fabric-cost-per-yard-custom-value'])) >0){
    	$facoperyardcustomval=$thisItemMeta['fabric-cost-per-yard-custom-value'];
    }else{
    	$facoperyardcustomval='';
    }
    echo "<div id=\"fabriccostwrap\"  class=\" number\">";
   echo "<label id =\"specialCost\" style=\"color:#ff0000;\" >special fabric cost p/yd at play</label>";
   
    echo "<div id=\"fabriccostwrapinnerwrap\" style=\"display:block;clear: both;padding:10px 0;\"";
    
	if(isset($thisItemMeta['fabric-cost-per-yard-custom-value']) && strlen(trim($thisItemMeta['fabric-cost-per-yard-custom-value'])) >0){
	//ignore

    }else{
    	echo " style=\"display:block;\"";
    }
    echo ">";
   // echo "<label id =\"specialCost\" style=\"color:#ff0000;\" >special fabric cost p/yd at play</label>";
    echo "<div><input type=\"number\" min=\"0\" step=\"any\" name=\"fabric-cost-per-yard-custom-value\" id=\"fabric-cost-per-yard-custom-value\" placeholder=\"Override\" value=\"".$facoperyardcustomval."\" /></div>";
    echo "</div></div>";
  /*PPSA-33 end*/
	echo "</div>";
	echo $this->Form->input('fcpcut',['type'=>'hidden','value'=>$fabricData['cost_per_yard_cut']]);
	echo $this->Form->input('fcpbolt',['type'=>'hidden','value'=>$fabricData['cost_per_yard_bolt']]);
	echo $this->Form->input('fcpcase',['type'=>'hidden','value'=>$fabricData['cost_per_yard_case']]);
	
	echo $this->Form->input('weight_per_sqin',['type'=>'hidden','value'=> floatval($fabricData['weight_per_sqin'])]);
}


if(isset($thisItemMeta['inbound-freight']) && strlen(trim($thisItemMeta['inbound-freight'])) > 0){
	$inboundfreightval=$thisItemMeta['inbound-freight'];
}else{
	$inboundfreightval=$inboundfreightpdefault;
}
if(isset($thisItemMeta['inbound-freight-custom-value']) && strlen(trim($thisItemMeta['inbound-freight-custom-value'])) >0){
	$ibfrtcustomval=$thisItemMeta['inbound-freight-custom-value'];
}else{
	$ibfrtcustomval='';
}
echo "<div id=\"inboundfreightwrap\" class=\"input number\">";
echo "<label>Inb Freight Cost /yd</label><small";
if(isset($thisItemMeta['fabric-cost-per-yard']) && strlen(trim($thisItemMeta['fabric-cost-per-yard'])) >0){
	echo " style=\"display:none;\"";
}
echo ">Need more info</small><div id=\"inboundfreightinnerwrap\"";
if(isset($thisItemMeta['fabric-cost-per-yard']) && strlen(trim($thisItemMeta['fabric-cost-per-yard'])) >0){
	//ignore
}else{
	echo " style=\"display:none;\"";
}
echo ">";
echo "<div><input type=\"number\" name=\"inbound-freight\" tabindex=\"8888\" readonly=\"readonly\" step=\"any\" min=\"0\" required=\"required\" id=\"inbound-freight\" value=\"".$inboundfreightval."\" /></div>";
echo "<div><input type=\"text\" name=\"inbound-freight-custom-value\" id=\"inbound-freight-custom-value\" placeholder=\"Override\" value=\"".$ibfrtcustomval."\" /></div></div></div>";





if(isset($thisItemMeta['fabric-markup']) && strlen(trim($thisItemMeta['fabric-markup'])) > 0){
	$markupval=$thisItemMeta['fabric-markup'];
}else{
	$markupval=$settings['cubicle_curtain_markup_default'];
}

if(isset($thisItemMeta['fabric-markup-custom-value']) && strlen(trim($thisItemMeta['fabric-markup-custom-value'])) >0){
	$markupcustomval=$thisItemMeta['fabric-markup-custom-value'];
}else{
	$markupcustomval='';
}

echo "<div id=\"fabricmarkupwrap\" class=\"input number\">";
echo "<label>Fabric Markup (%)</label><small";
if(isset($thisItemMeta['fabric-cost-per-yard']) && strlen(trim($thisItemMeta['fabric-cost-per-yard'])) >0){
	echo " style=\"display:none;\"";
}
echo ">Need more info</small><div id=\"fabricmarkupinnerwrap\"";
if(isset($thisItemMeta['fabric-cost-per-yard']) && strlen(trim($thisItemMeta['fabric-cost-per-yard'])) >0){
	//ignore
}else{
	echo "style=\"display:none;\"";
}
echo ">";
echo "<div><input type=\"number\" name=\"fabric-markup\" tabindex=\"8889\" step=\"any\" min=\"0\" required=\"required\" id=\"fabric-markup\" value=\"".$markupval."\" readonly=\"readonly\" /></div>";



echo "<div><input name=\"fabric-markup-custom-value\" type=\"text\" id=\"fabric-markup-custom-value\" value=\"".$markupcustomval."\" placeholder=\"Override\" /></div>";
echo "</div></div>";


echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>MESH</legend>";

echo "<div class=\"input selectbox\">
<label for=\"mesh-type\">Mesh</label>";
if(isset($thisItemMeta['mesh-type']) && $thisItemMeta['mesh-type'] != ''){
	$meshtypeval=$thisItemMeta['mesh-type'];
}else{
	$meshtypeval='MOM Mesh';
}
echo $this->Form->select('mesh-type',['None'=>'None','MOM Mesh'=>'MOM Mesh','COM Mesh'=>'COM Mesh','Integral Mesh'=>'Integral Mesh'],['id'=>'mesh-type','value'=>$meshtypeval]);
echo "</div>";



if(isset($thisItemMeta['mesh']) && is_numeric($thisItemMeta['mesh'])){
	$meshval=$thisItemMeta['mesh'];
}else{
	$meshval=$settings['mesh_default'];
}
echo $this->Form->input('mesh',['type'=>'number','min'=>'0','step'=>'any','value'=>$meshval,'label'=>'MESH SIZE']);




if(isset($thisItemMeta['finished-mesh']) && is_numeric($thisItemMeta['finished-mesh'])){
	$fmeshval=$thisItemMeta['finished-mesh'];
}else{
	$fmeshval=(floatval($settings['mesh_default'])+floatval($settings['mesh_heading']));
}
echo $this->Form->input('finished-mesh',['type'=>'number','min'=>'0','step'=>'any','value'=>$fmeshval,'label'=>'Finished Mesh','readonly'=>true]);






echo "<div class=\"input selectbox\">";
echo "<label>Mesh Color</label>";
$rawmeshcolors=explode("|",$settings['mesh_color_options']);
$meshcolors=array();
foreach($rawmeshcolors as $meshcolor){
    $meshcolors[$meshcolor]=$meshcolor;
}
$meshcolors['Other']='Other';

if(isset($thisItemMeta['mesh-color']) && strlen(trim($thisItemMeta['mesh-color'])) >0){
	$meshcolorval=$thisItemMeta['mesh-color'];
}else{
	$meshcolorval='White';
}

echo $this->Form->select('mesh-color',$meshcolors,['id'=>'mesh-color','value'=>$meshcolorval]);
echo "</div>";



if(isset($thisItemMeta['nonregmeshcolor']) && strlen(trim($thisItemMeta['nonregmeshcolor'])) >0){
	$nonregmeshcolorval=$thisItemMeta['nonregmeshcolor'];
}else{
	$nonregmeshcolorval='0';
}
echo $this->Form->input('nonregmeshcolor',['type'=>'hidden','label'=>false,'value'=>$nonregmeshcolorval]);

echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>LABOR</legend>";

if(intval($quoteID) == 0){
	$laborlabel='Final Labor Rate (per LF)';
}else{
	$laborlabel='Labor per linear foot';
}

if(isset($thisItemMeta['labor-per-lf']) && is_numeric($thisItemMeta['labor-per-lf'])){
	$laborperlfval=number_format($thisItemMeta['labor-per-lf'],2,'.','');
}else{
	$laborperlfval=number_format($settings['labor_cost_cc_mom'],2,'.','');
}
echo $this->Form->input('labor-per-lf',['value'=>$laborperlfval,'label'=>$laborlabel]);


if(intval($quoteID) == 0){
	echo $this->Form->input('custom-base-labor',['value'=> '','label'=>'Labor Override']);
}

echo "</fieldset>";


echo "<fieldset class=\"fieldsection\"><legend>FABRIC ROUNDING</legend>";

if(isset($thisItemMeta['force-full-widths-ea-cc']) && $thisItemMeta['force-full-widths-ea-cc']=='1'){
	$forcefullwidthseaccChecked=true;
}else{
	$forcefullwidthseaccChecked=false;
}
echo $this->Form->input('force-full-widths-ea-cc',['type'=>'checkbox','label'=>'Force full widths ea CC','checked'=>$forcefullwidthseaccChecked]);



if(isset($thisItemMeta['force-full-widths-eol']) && $thisItemMeta['force-full-widths-eol']=='1'){
	$forcefullwidthseolChecked=true;
}else{
	$forcefullwidthseolChecked=false;
}
echo $this->Form->input('force-full-widths-eol',['type'=>'checkbox','label'=>'Force full widths EOL','checked'=>$forcefullwidthseolChecked]);





if(isset($thisItemMeta['force-rounded-yards-ea-cc']) && $thisItemMeta['force-rounded-yards-ea-cc']=='1'){
	$forceroundedyardseaccChecked=true;
}else{
	$forceroundedyardseaccChecked=false;
}
echo $this->Form->input('force-rounded-yards-ea-cc',['type'=>'checkbox','label'=>'Force rounded yards ea CC','checked'=>$forceroundedyardseaccChecked]);




if(isset($thisItemMeta['force-dist-yds-eol']) && $thisItemMeta['force-dist-yds-eol']=='1'){
	$forcedistydseolChecked=true;
}else{
	$forcedistydseolChecked=true;
}
echo $this->Form->input('force-dist-yds-eol',['type'=>'checkbox','label'=>'Force Distributed yds EOL','checked'=>$forcedistydseolChecked]);





echo "</fieldset>";




echo "<button type=\"button\" id=\"goadvanced\">Advanced</button>";

//expand/colapse begin
echo "<div id=\"expandcollapse\" style=\"display:";
if(isset($thisItemMeta['advancedfieldsvisible']) && $thisItemMeta['advancedfieldsvisible']=='1'){
	echo " block";
}else{
	echo "none";
}
echo ";\">";

if(isset($thisItemMeta['nylon-mesh']) && $thisItemMeta['nylon-mesh']=='1'){
	$nylonmeshChecked=true;
	$angledmeshwrapdisplay='block';
}else{
	$nylonmeshChecked=false;
	$angledmeshwrapdisplay='none';
}
echo $this->Form->input('nylon-mesh',['type'=>'checkbox','label'=>'Nylon Mesh?','checked'=>$nylonmeshChecked]);



echo "<div id=\"angledmeshwrap\" style=\"display:".$angledmeshwrapdisplay.";\">";
if(isset($thisItemMeta['angled-mesh']) && $thisItemMeta['angled-mesh']=='1'){
	$angledmeshChecked=true;
}else{
	$angledmeshChecked=false;
}
echo $this->Form->input('angled-mesh',['type'=>'checkbox','label'=>'Angled Mesh?','checked'=>$angledmeshChecked]);
echo "</div>";



if(isset($thisItemMeta['mesh-frame']) && strlen(trim($thisItemMeta['mesh-frame'])) >0){
	$meshframeval=$thisItemMeta['mesh-frame'];
}else{
	$meshframeval='No Frame';
}
echo "<div class=\"input selectbox\">
<label for=\"mesh-frame\">Mesh Frame</label>";
echo $this->Form->select('mesh-frame',['No Frame'=>'No Frame','Capped Top Only'=>'Capped Top Only','Full Frame'=>'Full Frame'],['id'=>'mesh-frame','value'=>$meshframeval]);
echo "</div>";





if(isset($thisItemMeta['snap-tape']) && strlen(trim($thisItemMeta['snap-tape']))>0){
	$snaptapeval=$thisItemMeta['snap-tape'];
	$snaptapelfdisplay='block';
}else{
	$snaptapeval='None';
	$snaptapelfdisplay='none';
}
echo "<div class=\"input selectbox\">
<label for=\"snap-tape\">Snap Tape</label>";
echo $this->Form->select('snap-tape',['None'=>'None','MOM'=>'MOM','COM'=>'COM'],['value'=>$snaptapeval,'id'=>'snap-tape']);
echo "</div>";


echo "<div id=\"snaptapelfwrap\" style=\"display:".$snaptapelfdisplay.";\">";
if(isset($thisItemMeta['snaptape-lf']) && strlen(trim($thisItemMeta['snaptape-lf']))>0){
	$snaptapelfval=$thisItemMeta['snaptape-lf'];
}else{
	$snaptapelfval='Top Only';
}
echo "<div class=\"input selectbox\"><label for=\"snaptape-lf\">Snap Placement</label>";
echo $this->Form->select('snaptape-lf',['Top Only'=>'Top Only','Sides Only'=>'Sides Only','Top + Sides' => 'Top + Sides'],['value'=>$snaptapelfval,'id'=>'snaptape-lf']);
echo "</div>";
echo "</div>";




if(isset($thisItemMeta['velcro']) && strlen(trim($thisItemMeta['velcro']))>0){
	$velcroval=$thisItemMeta['velcro'];
	$velcrolfdisplay='block';
}else{
	$velcroval='None';
	$velcrolfdisplay='none';
}
echo "<div class=\"input selectbox\">
<label for=\"velcro\">Velcro</label>";
echo $this->Form->select('velcro',['None'=>'None','MOM'=>'MOM','COM'=>'COM'],['value'=>$velcroval,'id'=>'velcro']);
echo "</div>";


echo "<div id=\"velcrolfwrap\" style=\"display:".$velcrolfdisplay.";\">";
if(isset($thisItemMeta['velcro-lf']) && strlen(trim($thisItemMeta['velcro-lf']))>0){
	$velcrolfval=$thisItemMeta['velcro-lf'];
}else{
	$velcrolfval='0';
}
echo $this->Form->input('velcro-lf',['label'=>'Velcro LF','type'=>'number','step'=>1,'min'=>'0','value'=>$velcrolfval]);
echo "</div>";





if(isset($thisItemMeta['liner']) && $thisItemMeta['liner']=='1'){
	$linerChecked=true;
}else{
	if($fabricData['liner']=='1'){
		$linerChecked=true;
	}else{
		$linerChecked=false;
	}
}
echo $this->Form->input('liner',['type'=>'checkbox','label'=>'Liner','checked'=>$linerChecked]);



if(isset($thisItemMeta['antimicrobial']) && $thisItemMeta['antimicrobial']=='1'){
	$amChecked=true;
}else{
	if($fabricData['antimicrobial']=='1'){
		$amChecked=true;
	}else{
		$amChecked=false;
	}
}
echo $this->Form->input('antimicrobial',['type'=>'checkbox','label'=>'Antimicrobial','checked'=>$amChecked]);





if(isset($thisItemMeta['weights']) && $thisItemMeta['weights']=='1'){
	$weightsChecked=true;
	$weightcountdisplay='block';
}else{
	$weightsChecked=false;
	$weightcountdisplay='none';
}
echo $this->Form->input('weights',['type'=>'checkbox','label'=>'Weights?','checked'=>$weightsChecked]);


echo "<div id=\"weightcountwrap\" style=\"display:".$weightcountdisplay.";\">";
if(isset($thisItemMeta['weight-count']) && strlen(trim($thisItemMeta['weight-count'])) >0){
	$weightCountVal=$thisItemMeta['weight-count'];
}else{
	$weightCountVal='0';
}
echo $this->Form->input('weight-count',['type'=>'number','step'=>1,'min'=>'0','label'=>'Weight Count','value'=>$weightCountVal]);
echo "</div>";



if(isset($thisItemMeta['magnets']) && $thisItemMeta['magnets']=='1'){
	$magnetsChecked=true;
	$magnetcountdisplay='block';
}else{
	$magnetsChecked=false;
	$magnetcountdisplay='none';
}
echo $this->Form->input('magnets',['type'=>'checkbox','label'=>'Magnets?','checked'=>$magnetsChecked]);

echo "<div id=\"magnetcountwrap\" style=\"display:".$magnetcountdisplay.";\">";
if(isset($thisItemMeta['magnet-count']) && strlen(trim($thisItemMeta['magnet-count'])) >0){
	$magnetCountVal=$thisItemMeta['magnet-count'];
}else{
	$magnetCountVal='0';
}
echo $this->Form->input('magnet-count',['type'=>'number','step'=>1,'min'=>'0','label'=>'Magnet Count','value'=>$magnetCountVal]);
echo "</div>";




if(isset($thisItemMeta['banding']) && $thisItemMeta['banding']=='1'){
	$bandingChecked=true;
}else{
	$bandingChecked=false;
}
echo $this->Form->input('banding',['type'=>'checkbox','label'=>'Banding?','checked'=>$bandingChecked]);




if(isset($thisItemMeta['hidden-mesh']) && $thisItemMeta['hidden-mesh']=='1'){
	$hiddenMeshChecked=true;
}else{
	$hiddenMeshChecked=false;
}
echo $this->Form->input('hidden-mesh',['type'=>'checkbox','label'=>'Hidden Mesh?','checked'=>$hiddenMeshChecked]);





if(isset($thisItemMeta['buttonholes']) && $thisItemMeta['buttonholes']=='1'){
	$buttonholesChecked=true;
	$buttonholecountdisplay='block';
}else{
	$buttonholesChecked=false;
	$buttonholecountdisplay='none';
}
echo $this->Form->input('buttonholes',['type'=>'checkbox','label'=>'Buttonholes instead of Grommets?','checked'=>$buttonholesChecked]);

echo "<div id=\"buttonholecountwrap\" style=\"display:".$buttonholecountdisplay.";\">";
if(isset($thisItemMeta['buttonhole-count']) && strlen(trim($thisItemMeta['buttonhole-count'])) >0){
	$buttonholeCountVal=$thisItemMeta['buttonhole-count'];
}else{
	$buttonholeCountVal='0';
}
echo $this->Form->input('buttonhole-count',['type'=>'number','step'=>1,'min'=>'0','label'=>'Buttonhole Count','value'=>$buttonholeCountVal]);
echo "</div>";




if(isset($thisItemMeta['inches-per-seam']) && is_numeric($thisItemMeta['inches-per-seam'])){
	$inchesperseamval=$thisItemMeta['inches-per-seam'];
}else{
	$inchesperseamval=$settings['inches_per_seam'];
}
echo $this->Form->input('inches-per-seam',['type'=>'number','min'=>'0','value'=>$inchesperseamval,'label'=>'Inches per seam']);



if(isset($thisItemMeta['inches-per-hem']) && is_numeric($thisItemMeta['inches-per-hem'])){
	$inchesperhemval=$thisItemMeta['inches-per-hem'];
}else{
	$inchesperhemval=$settings['inches_per_hem'];
}
echo $this->Form->input('inches-per-hem',['type'=>'number','min'=>'0','value'=>$inchesperhemval,'label'=>'Inches per hem']);



if(isset($thisItemMeta['inches-for-header']) && is_numeric($thisItemMeta['inches-for-header'])){
	$inchesforheaderval=$thisItemMeta['inches-for-header'];
}else{
	$inchesforheaderval=$settings['inches_for_header'];
}
echo $this->Form->input('inches-for-header',['type'=>'number','min'=>'0','value'=>$inchesforheaderval,'label'=>'Inches for header']);



if(isset($thisItemMeta['fabric-waste-overhead']) && is_numeric($thisItemMeta['fabric-waste-overhead'])){
	$fabricwasteoverheadval=$thisItemMeta['fabric-waste-overhead'];
}else{
	$fabricwasteoverheadval='5';
}
echo $this->Form->input('fabric-waste-overhead',['type'=>'number','min'=>'0','max'=>'100','value'=>$fabricwasteoverheadval,'label'=>'Fabric waste OH (%)']);




echo "</div>";
//expand/collapse end


echo "</div>";
?>

<div id="resultsblock">
<h2>Calculator Results</h2>
<div id="cannotcalculate">Cannot calculate. Missing information.</div>
<?php
/*if(isset($thisItemMeta['layout']) && strlen(trim($thisItemMeta['layout'])) > 0){
	$layoutval=$thisItemMeta['layout'];
}else{
	$layoutval='';
}
echo $this->Form->input('layout',['style'=>'width:91% !important;','label'=>'Layout','readonly'=>true,'value'=>$layoutval]);
*/



if(isset($thisItemMeta['widths-each']) && strlen(trim($thisItemMeta['widths-each'])) > 0){
	$widthseachval=$thisItemMeta['widths-each'];
}else{
	$widthseachval='';
}
echo $this->Form->input('widths-each',['label'=>'Widths Each','readonly'=>true,'value'=>$widthseachval]);



if(isset($thisItemMeta['total-widths']) && strlen(trim($thisItemMeta['total-widths'])) > 0){
	$totalwidthsval=$thisItemMeta['layout'];
}else{
	$totalwidthsval='';
}
echo $this->Form->input('total-widths',['label'=>'Total Widths','readonly'=>true,'value'=>$totalwidthsval]);





//difficulty rating
if(isset($thisItemMeta['difficulty-rating']) && strlen(trim($thisItemMeta['difficulty-rating'])) >0){
	$difficultyratingval=$thisItemMeta['difficulty-rating'];
}else{
	$difficultyratingval='';
}
echo $this->Form->input('difficulty-rating',['label'=>'Total Difficulty','readonly'=>true,'value'=>$difficultyratingval]);

	
	
if(isset($thisItemMeta['cl']) && strlen(trim($thisItemMeta['cl'])) > 0){
	$clval=$thisItemMeta['cl'];
}else{
	$clval='';
}
echo $this->Form->input('cl',['label'=>'CL','readonly'=>true,'value'=>$clval]);
	
	
	
if(isset($thisItemMeta['labor-billable']) && strlen(trim($thisItemMeta['labor-billable'])) > 0){
	$laborbillableval=$thisItemMeta['labor-billable'];
}else{
	$laborbillableval='';
}	
echo $this->Form->input('labor-billable',['label'=>'Labor Billable','readonly'=>true,'value'=>$laborbillableval]);

	
	
if(isset($thisItemMeta['layout-status']) && strlen(trim($thisItemMeta['layout-status'])) > 0){
	$layoutstatusval=$thisItemMeta['layout-status'];
}else{
	$layoutstatusval='';
}	
echo $this->Form->input('layout-status',['style'=>'width:91% !important;','label'=>'Layout Status','readonly'=>true,'value'=>$layoutstatusval]);

	

if(isset($thisItemMeta['fw']) && strlen(trim($thisItemMeta['fw'])) > 0){
	$fwval=$thisItemMeta['fw'];
}else{
	$fwval='';
}
echo $this->Form->input('fw',['label'=>'FW','readonly'=>true,'value'=>$fwval]);

	
	
	
if(isset($thisItemMeta['rr-fl-status']) && strlen(trim($thisItemMeta['rr-fl-status'])) > 0){
	$rrflstatusval=$thisItemMeta['rr-fl-status'];
}else{
	$rrflstatusval='';
}
echo $this->Form->input('rr-fl-status',['style'=>'width:91% !important;','label'=>'RR FL Status','readonly'=>true,'value'=>$rrflstatusval]);



	
echo "<div id=\"vrptwastewrap\"";
if($thisItemMeta['railroaded'] == '1' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '1')){
	echo " style=\"display:none;\"";
}
echo ">";

if(isset($thisItemMeta['vrpt-waste']) && strlen(trim($thisItemMeta['vrpt-waste'])) > 0){
	$vrptwasteval=$thisItemMeta['vrpt-waste'];
}else{
	$vrptwasteval='';
}
echo $this->Form->input('vrpt-waste',['label'=>'VRPT Waste','readonly'=>true,'value'=>$vrptwasteval]);
echo "</div>";




echo "<div id=\"horizontalwastewrap\"";
if($thisItemMeta['railroaded'] == '0' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '0')){
	echo " style=\"display:none;\"";
}
echo ">";
if(isset($thisItemMeta['horiz-waste']) && strlen(trim($thisItemMeta['horiz-waste'])) > 0){
	$horizwasteval=$thisItemMeta['horiz-waste'];
}else{
	$horizwasteval='';
}
echo $this->Form->input('horiz-waste',['label'=>'RR Waste','readonly'=>true,'value'=>$horizwasteval]);
echo "</div>";
	




if(isset($thisItemMeta['captop-yards']) && strlen(trim($thisItemMeta['captop-yards'])) > 0){
	$captopyardsval=$thisItemMeta['captop-yards'];
}else{
	$captopyardsval='';
}
echo $this->Form->input('captop-yards',['label'=>'Captop Yards','readonly'=>true,'value'=>$captopyardsval]);



if(isset($thisItemMeta['framed-sides-yards']) && strlen(trim($thisItemMeta['framed-sides-yards'])) > 0){
	$framedsidesyardsval=$thisItemMeta['framed-sides-yards'];
}else{
	$framedsidesyardsval='';
}
echo $this->Form->input('framed-sides-yards',['label'=>'Framed Sides Yards','readonly'=>true,'value'=>$framedsidesyardsval]);



if(isset($thisItemMeta['fw-vs-expected-fw']) && strlen(trim($thisItemMeta['fw-vs-expected-fw'])) > 0){
	$fwvsexpectedfwval=$thisItemMeta['fw-vs-expected-fw'];
}else{
	$fwvsexpectedfwval='';
}
echo $this->Form->input('fw-vs-expected-fw',['style'=>'width:91% !important;','label'=>'FW vs Expected FW','readonly'=>true,'value'=>$fwvsexpectedfwval]);

	
	
if(isset($thisItemMeta['yds-per-unit']) && strlen(trim($thisItemMeta['yds-per-unit'])) > 0){
	$ydsperunitval=$thisItemMeta['yds-per-unit'];
}else{
	$ydsperunitval='';
}
echo $this->Form->input('yds-per-unit',['label'=>'Yards per unit','readonly'=>true,'value'=>$ydsperunitval]);
	


if(isset($thisItemMeta['total-yds']) && strlen(trim($thisItemMeta['total-yds'])) > 0){
	$totalydsval=$thisItemMeta['total-yds'];
}else{
	$totalydsval='';
}
echo $this->Form->input('total-yds',['label'=>'Total Yards','readonly'=>true,'value'=>$ydsperccval]);
	
//echo $this->Form->input('mesh-cost',['label'=>'Mesh Cost','readonly'=>true]);
	
if(isset($thisItemMeta['fabric-cost']) && strlen(trim($thisItemMeta['fabric-cost'])) > 0){
	$fabriccostval=$thisItemMeta['fabric-cost'];
}else{
	$fabriccostval='';
}
echo $this->Form->input('fabric-cost',['type'=>'hidden','label'=>'Fabric Cost (per yd)','readonly'=>true,'value'=>$fabriccostval]);



if(isset($thisItemMeta['fabric-price-per-yard']) && strlen(trim($thisItemMeta['fabric-price-per-yard'])) > 0){
	$fabricpriceperyardval=$thisItemMeta['fabric-price-per-yard'];
}else{
	$fabricpriceperyardval='';
}
echo $this->Form->input('fabric-price-per-yard',['label'=>'Fabric Price (per yd)','readonly'=>true,'value'=>$fabricpriceperyardval]);




if(isset($thisItemMeta['fabric-price']) && strlen(trim($thisItemMeta['fabric-price'])) > 0){
	$fabricpriceval=$thisItemMeta['fabric-price'];
}else{
	$fabricpriceval='';
}
echo $this->Form->input('fabric-price',['label'=>'Fabric Price (per CC)','readonly'=>true,'value'=>$fabricpriceval]);




	
if(isset($thisItemMeta['labor-cost']) && strlen(trim($thisItemMeta['labor-cost'])) > 0){
	$laborcostval=$thisItemMeta['labor-cost'];
}else{
	$laborcostval='';
}	
echo $this->Form->input('labor-cost',['label'=>'Labor Cost (per CC)','readonly'=>true,'value'=>$laborcostval]);




	
	
if(isset($thisItemMeta['cost']) && strlen(trim($thisItemMeta['cost'])) > 0){
	$costval=number_format($thisItemMeta['cost'],2,'.','');
}else{
	$costval='';
}
echo $this->Form->input('cost',['label'=>false,'type'=>'hidden','value'=>$costval]);

	



if(isset($thisItemMeta['total-surcharges']) && strlen(trim($thisItemMeta['total-surcharges'])) >0){
	$totalsurchargesval=$thisItemMeta['total-surcharges'];
}else{
	$totalsurchargesval='';
}
echo $this->Form->input('total-surcharges',['type'=>'number','step'=>'any','readonly'=>true,'value'=>$totalsurchargesval]);


echo "<div style=\"text-align:right; font-size:12px; padding:5px 10px 10px 0;\"><a href=\"#explainmath\">View Surcharge Breakdown</a></div>";





if(isset($thisItemMeta['price']) && strlen(trim($thisItemMeta['price'])) > 0){
	$priceval=number_format($thisItemMeta['price'],2,'.','');
}else{
	$priceval='';
}
echo $this->Form->input('price',['label'=>'Base Price','readonly'=>true,'type'=>'number','step'=>'any','min'=>0,'value'=>$priceval]);




if(intval($quoteID) == 0){
?>
<div class="input select">
<label>Tier</label>
<select name="tier_adjustment" id="tier_adjustment">
<option value="1">Tier 1 (<?php echo $settings['tier_1_premium']; ?>% Prem)</option>
<option value="2">Tier 2 (<?php echo $settings['tier_2_premium']; ?>% Prem)</option>
<option value="3">Tier 3 (<?php echo $settings['tier_3_premium']; ?>% Prem)</option>
<option value="4">Tier 4 (<?php echo $settings['tier_4_premium']; ?>% Prem)</option>
<option value="5">Tier 5 (<?php echo $settings['tier_5_premium']; ?>% Prem)</option>
<option value="6">Tier 6 (<?php echo $settings['tier_6_premium']; ?>% Prem)</option>
<option value="7" selected>Tier 7 (<?php echo $settings['tier_7_premium']; ?>% Prem)</option>
<option value="8">Tier 8 (<?php echo $settings['tier_8_premium']; ?>% Prem)</option>
<option value="9">Tier 9 (<?php echo $settings['tier_9_premium']; ?>% Prem)</option>
<option value="10">Tier 10 (<?php echo $settings['tier_10_premium']; ?>% Prem)</option>
<option value="11">Tier 11 (<?php echo $settings['tier_11_premium']; ?>% Prem)</option>
<option value="12">Tier 12 (<?php echo $settings['tier_12_premium']; ?>% Prem)</option>
</select>
</div>

<div class="input checkbox"><label>Installation Surcharge?</label><input type="checkbox" name="install_surcharge" id="install_surcharge" value="yes" /></div>


<div class="input number"><label>ADD (%)</label> <input type="number" step="any" name="add_surcharge" id="add_surcharge" value="0" /></div>


<div class="input adjustedtotal"><label>Adjusted Price</label> <input type="number" step="any" readonly="readonly" value="" id="adjusted-price" name="adjusted-price" /></div>


<div class="input extendedprice"><label>Extended Price</label> <input type="number" step="any" readonly="readonly" value="" id="extended-price" name="extended-price" /></div>
<?php } 

	
if(isset($thisItemMeta['cc_calculated_weight']) && strlen(trim($thisItemMeta['cc_calculated_weight'])) >0){
	$ccweightval=floatval($thisItemMeta['cc_calculated_weight']);
}else{
	$ccweightval='';
}
echo $this->Form->input('cc_calculated_weight',['label'=>'Tot Calc Weight','readonly'=>true,'type'=>'number','step'=>'any','min'=>0,'value'=>$ccweightval]);
	
?>
</div>
<div class="clear"></div>
<?php
echo "<div class=\"calculatebutton\">";
echo $this->Form->button('Recalculate',['type'=>'button']);
echo "</div>";

if(intval($quoteID) >0){
	echo "<div class=\"addaslineitembutton\">";
	echo "<button type=\"button\" id=\"cancelbutton\">Cancel</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
	if($isEdit){
		/* PPSASCRUM-317: start */
// 		echo $this->Form->button('Save Changes',['type'=>'submit','class'=>'SubmitButton','class'=>'SubmitButton']);
		echo $this->Form->button('Save Changes',['type'=>'submit','onClick'=>'return checkSubmission();','class'=>'SubmitButton']);
		/* PPSASCRUM-317: end */
	}else{
		/* PPSASCRUM-317: start */
// 		echo $this->Form->button('Add To Quote',['type'=>'submit','class'=>'SubmitButton','class'=>'SubmitButton']);
		echo $this->Form->button('Add To Quote',['type'=>'submit','onClick'=>'return checkSubmission();','class'=>'SubmitButton']);
		/* PPSASCRUM-317: end */
	}
	echo "</div>";
}

echo "<div class=\"clear\"></div>";
echo $this->Form->end();

?><br><Br><br><Br>

<div id="warningbox"></div>

<style>
#warningbox{ display:none; position:fixed; padding:5px; bottom:20px; right:20px; width:510px; background:#FFCFBF; color:red; font-weight:bold; border:3px solid red; z-index:6666; font-size:12px; }
</style>

<script>
//PPSASCRUM-260 start
 $('form').submit(function(){
      $('.SubmitButton').prop('disabled',true).val('Processing...'); 
 });
 
 //PPSASCRUM-260 end
<?php
echo "var rulesets=";
$rulesets=array();
foreach($markuprulesets as $ruleset){
	$rulesets[]=array(
		'price_low' => $ruleset['price_range_low'],
		'price_high' => $ruleset['price_range_high'],
		'yds_low' => $ruleset['yds_range_low'],
		'yds_high' => $ruleset['yds_range_high'],
		'markup' => $ruleset['range_markup']
	);
}

echo json_encode($rulesets);
echo ";\n\n";
?>

function doCalculation(){
	var qty=parseFloat($('#qty').val());
	console.clear();


	calculateLabor();


	if($('#railroaded').is(':checked')){
			var rr=1;
			//$('#width').removeAttr('step');
			var w = parseFloat($('#expected-finish-width').val());
			$('#width').val(w);
	}else{
			var rr=0;
			//$('#width').attr('step','18');
			if($('#utr-width').val() == '0' && $('#custom-utr-width').val() != ''){
				var w = parseFloat($('#custom-utr-width').val());
			}else{
				var w = parseFloat($('#utr-width').val());
			}
			$('#width').val(w);
	}

		
		var l = parseFloat($('#length').val());
		var m = parseFloat($('#mesh').val());

		if($('#mesh-type').val() != 'None'){
			var m=(m + parseFloat(<?php echo $settings['mesh_heading']; ?>));
			console.log('Mesh variable increased by <?php echo $settings['mesh_heading']; ?> to '+m);
			$('#finished-mesh').val(m);
		}
		
		var cap_fra=<?php echo $settings['cap-top-and-frame-sides-cut-length']; ?>;

		var mcolor = $('select[name=mesh-color]').val();
		if((m == 20 || m==36) && (mcolor=='White' || mcolor=='Beige')){
			$('#nonregmeshcolor').val('0');
			var mc=0;
		}else{
			$('#nonregmeshcolor').val('1');
			var mc=1;
		}
		//console.log('MC? '+mc+' (mcolor='+mcolor+' m='+m+')');



		if($('#mesh-frame').val() == 'No Frame'){
			var newcaptop=0;
			var fram=0;
		}else if($('#mesh-frame').val() == 'Capped Top Only'){
			var newcaptop=1;
			var fram=0;
		}else if($('#mesh-frame').val() == 'Full Frame'){
			var newcaptop=1;
			var fram=1;
		}



		var fab_w = parseFloat($('#fabric-width').val());
		var v_rpt = parseFloat($('#vertical-repeat').val());
		
		<?php
		if($fabricid=='custom'){
		?>
		if($('#custom-fabric-cost-per-yard').val() != '' && parseFloat($('#custom-fabric-cost-per-yard').val()) >0){
			var fab_ppy = parseFloat($('#custom-fabric-cost-per-yard').val());
		}
		<?php
		}else{
		?>
			/*PPSA-33 start*/
		if($('#fabric-cost-per-yard-custom-value').val() != '' && parseFloat($('#fabric-cost-per-yard-custom-value').val()) >0){
			var fab_ppy = parseFloat($('#fabric-cost-per-yard-custom-value').val());
		} else /*PPSA-33 end*/
		if($('select[name=fabric-cost-per-yard]').val() == 'cut'){
			var fab_ppy = parseFloat($('#fcpcut').val());
		}else if($('select[name=fabric-cost-per-yard]').val() == 'bolt'){
			var fab_ppy = parseFloat($('#fcpbolt').val());
		}else if($('select[name=fabric-cost-per-yard]').val() == 'case'){
			var fab_ppy = parseFloat($('#fcpcase').val());
		}
	
		<?php } ?>
	
		var fwx = parseFloat($('#expected-finish-width').val());
		
		<?php if(intval($quoteID) == 0){ ?>
		if($('#custom-base-labor').val() != '' && parseFloat($('#custom-base-labor').val()) > 0.00){
			var lbr_plf = parseFloat($('#custom-base-labor').val());
		}else{
			var lbr_plf = parseFloat($('#labor-per-lf').val());	
		}
		<?php }else{ ?>
		var lbr_plf = parseFloat($('#labor-per-lf').val());
		<?php } ?>

		//console.log('lbr_plf = '+lbr_plf);		

		//var mesh_plf = parseFloat($('#mesh-plf').val());
		
		var sea = parseFloat($('#inches-per-seam').val());
		var hem =  parseFloat($('#inches-per-hem').val());
		var head = parseFloat($('#inches-for-header').val());
		
		var oh = (1+(parseFloat($('#fabric-waste-overhead').val())/100));
		



		if($('#mesh-frame').val()=='Capped Top Only' || $('#mesh-frame').val() == 'Full Frame'){
			var cap=1;
		}else{
			var cap=0;
		}
		
		
		if($('#mesh-frame').val()=='Full Frame'){
			var frm=1;
		}else{
			var frm=0;
		}
		var fram=frm;


		
		

		
		if($('#liner').is(':checked')){
			var liner=1;
		}else{
			var liner=0;
		}

		
		
		if($('#force-full-widths-ea-cc').is(':checked')){
			var ffw=1;
		}else{
			var ffw=0;
		}
		
		if($('#force-full-widths-eol').is(':checked')){
			var ffw_eol=1;
		}else{
			var ffw_eol=0;
		}
		
		
		if($('#force-dist-yds-eol').is(':checked')){
			var fry_dist=1;
		}else{
			var fry_dist=0;
		}
		
		
		if($('#force-rounded-yards-ea-cc').is(':checked')){
			var fry=1;
		}else{
			var fry=0;
		}
		
		/*************CALCULATE**************/
		
		if(rr==1){
			var lay=1;
		}else{
			var lay=(w/fab_w);
		}
		
		
		//isolate layout decimals (if exist) and rounded them down to two decimals
		var lay_frac = floordec(lay-parseInt(lay));
		
		
		//ensure that we're dealing with the correct CutWidth-FabricWidth ratio (CW needs to be a multiple/submultiple of Fab W) in fulls, halves, quarters & thirds)
		if(lay_frac == 0 || lay_frac == 0.25 || lay_frac == 0.5 || lay_frac == 0.75 || lay_frac == 0.33 || lay_frac == 0.66  || lay_frac == 0.99){
			var frac_ok = true;
		}else{
			var frac_ok = false;
		}
		
		if(frac_ok){ 
			var warn1 = 'LAYOUT OK';
		}else{
			var warn1 = 'BAD LAYOUT - CHECK YOUR INPUTS';
		}
		
		
		//FIND SEAMS & HEMS
		
		//find fulls needed per each curtain
		if(lay_frac == 0.99){
			var full = (parseInt(lay)+1);
		}else{		
			var full = parseInt(lay);
		}
		
		//find quarters needed per each curtain
		if(lay_frac == 0.25 || lay_frac == 0.75){
			var qrt = 1;
		}else{
			var qrt = 0;
		}

		//find halves needed per each curtain
		if(lay_frac == 0.5 || lay_frac == 0.75){
			var hlv = 1;
		}else{
			var hlv = 0;
		}

		//find thirds needed per each curtain
		if(lay_frac == 0.33){
			var thrd = 1;
		}else if(lay_frac == 0.66){
			var thrd = 2;
		}else{
			var thrd = 0;
		}	
		
		
		/********FIND SEAMS needed per each curtain*******/
		var tsea = ((full + hlv + qrt + thrd)-1);
		console.log('var tsea = (('+full+' + '+hlv+' + '+qrt+' + '+thrd+') -1)');

		
		/********FIND HEMS needed per each curtain WIDTH-WISE*******/
		if(liner == 1){
			var them = 0;
		}else{
			var them = 2.5; // increased form 2 to 2.5 after initial testing
		}
		
		/*******FIND FINISHED WIDTH*******/
		if($('#railroaded').is(':checked')){
			var fw = w;
		}else{
			var fw = (w-((them*hem)+(tsea*sea)));
			console.log('var fw = ('+w+' - ( ('+them+' * '+hem+') + ('+tsea+' * '+sea+')) )');
		}

		
		//find cut length	<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        //step 1: raw value
        if(m == 0){
           var cl_1 = (l + head +hem);
        }else{
           var cl_1 = ( (l - m) + (sea + hem));
        }
		
        //step 2: adj if liner
        if(liner == 1){
            var cl_2 = (cl_1-hem);
        }else{
            var cl_2 = cl_1;
        }
		
        //step 3: adj for vert repeat
        if(v_rpt > 0){
           var cl_3 = (Math.ceil(cl_2/v_rpt)*v_rpt);
        }else{
           var cl_3 = cl_2;         
        }
		
        //step 4: adj for RR
        if(rr == 0){
            var cl = cl_3;
        }else if(rr == 1 && m > 0){
            var cl = (w + (2.5 * hem));
        }else if(rr == 1 && liner == 1 && m == 0){
			var cl = w;
        }else if(rr == 1){
			var cl = (w +(2.5*hem));
        }		



        //find RR Waste (formerly Horiz Waste)
        if(rr==0){
        	var rr_wast1=0;
        }else if(liner==1 && m == 0){
        	var rr_wast1=(fab_w - (l + head));
        }else if(liner==0 && m==0){
        	var rr_wast1=(fab_w - (l+head+hem));
        }else if(liner == 1 && m >0){
        	var rr_wast1=(fab_w - ((l-m)+sea));
        }else if(liner==0 && m > 0){
        	var rr_wast1=(fab_w - ((l-m)+sea+hem));
        }



		//find waste
		if(rr == 1){
			var wast1 = rr_wast1;
		}else{	
			var wast1 = floordec(cl_3 - cl_2);
		}
		console.log('Vertical Waste = '+wast1);
		$('#vrpt-waste').val(wast1);

		
		
		//RAILROADED WARNING
		if(rr == 0){
			var warn2 = 'CC is UTR - YOU ARE OK';
			var wast2 = 0;
		}else if(cl_1 <= fab_w){
			var warn2 = 'RR FL OK' ;
			var wast2 = floordec(fab_w - cl_1);
		}else{	
			var warn2 = 'BAD RR FL - CHECK YOUR INPUTS';
			var wast2 = 0;
		}
		
		
		//COMPARE EXPECTED FINISHED VERSUS ACTUAL FINISHED WIDTH - WARNING
		if(fwx == 0 || $('#expected-finish-width').val() == ''){
			var warn3 = ' NO EXPECTED FINISHED WIDTH';
		}else if(fwx > fw){
			var warn3 = 'BAD FINISHED WIDTH vs EXPECTED FINISHED WIDTH - CHECK YOUR INPUTS';
		}else{
			var warn3 = 'YOU ARE OK';
		}	
		
		//adjusting if forced to use full widths on each CC
		if(ffw ==1){
			var adj_lay = Math.ceil(lay);
		}else{
			var adj_lay = lay;
		}
		
		//calculate total amount of widths per line 
		if(ffw_eol ==1){
			var tot_weol = Math.ceil(lay * qty);
		}else{
			var tot_weol = (adj_lay * qty);
		}	
		



		////////////captop and frames sides added yardages
        //captop

        if(rr==1){
        	//begin RR captops calcs

        	if(cap == 0 || m == 0){
        		var captops_needed_each = 0;
        	}else if(cap == 1 && m > 0 && rr_wast1 >= cap_fra){
        		var captops_needed_each = 0;
        	}else if(cap == 1 && m >0 && rr_wast1 < cap_fra){
        		var captops_needed_each = 1;
        	}

        	var captops_needed_total = (captops_needed_each * qty);

        	var possible_captops = parseInt((fab_w/cap_fra));

        	var captop_widths = Math.ceil((captops_needed_total / possible_captops));

        	if(cap == 0 || m == 0){
        		var captop_cl = 0;
        	}else{
        		var captop_cl = cl;
        	}

        	var captop_yards = ((((captop_cl / 36) * oh) * captop_widths) / qty);

        	var captop_left = ((captop_widths - (captops_needed_total / possible_captops)) * fab_w);

        	if(captop_widths == 0){
        		var possible_left_vertstrips = 0;
        	}else{
        		var raw_possible_left_vertstrips = parseInt((cl/cap_fra));
        		if(raw_possible_left_vertstrips % 2 == 1){
        			var possible_left_vertstrips = (raw_possible_left_vertstrips-1);
        		}else{
        			var possible_left_vertstrips = raw_possible_left_vertstrips;
        		}
        	}

        	if(captop_left >= (2 * (m+(2*sea)))){
        		var usable_left_sides = (possible_left_vertstrips * (2*sea));
        	}else if(captop_left < (2 * (m+(2*sea))) && captop_left >= (m+(2*sea))){
        		var usable_left_sides = possible_left_vertstrips;
        	}else{
        		var usable_left_sides = 0;
        	}


        	if(fram == 0){
        		var sides_needed_each = 0;
        	}else{
        		var sides_needed_each = 2;
        	}

        	var sides_needed_total = (sides_needed_each * qty);

        	if((sides_needed_total - usable_left_sides) < 0){
        		var adj_sides_needed_total = 0;
        	}else{
        		var adj_sides_needed_total = (sides_needed_total - usable_left_sides);
        	}


        	if(fab_w >= (2 * (m + (2*sea)))){
        		var new_vertstrips_needed = (adj_sides_needed_total / 2);
        	}else if(fab_w >= (m + (2*sea)) && fab_w < (2 * (m + (2*sea)))){
        		var new_vertstrips_needed = adj_sides_needed_total;
        	}else{
        		var new_vertstrips_needed = (adj_sides_needed_total * 2);
        	}


        	var sides_yards = ((((new_vertstrips_needed * cap_fra) / 36) * oh) / qty);


        	//end RR captops calcs
        }else{
        	//begin UTR captops calcs
	        
	        if(cap == 0 || m==0){
		        var captop_yards = 0;
		        console.log('captop_yards = 0');
		    }else if(cap == 1 && m > 0 && wast1 >= cap_fra){
		    	var captop_yards = 0;
		    	console.log('captop_yards = 0');
		    }else if(cap == 1 && m > 0 && wast1 < cap_fra){
		    	var captop_yards = ((cap_fra * (tot_weol / qty) / 36) * oh);
		    	console.log('captop_yards = (('+cap_fra+' * ('+tot_weol+' / '+qty+') / 36) * '+oh);
		    }

		    if(fram == 0){
		    	var sides_needed_each=0;
		    }else{
		    	var sides_needed_each=2;
		    }

		    var sides_needed_total = (sides_needed_each * qty);

		    var possible_sides = parseInt(fab_w / cap_fra);

		    var sides_widths = Math.ceil((sides_needed_total / possible_sides));

		    if(fram == 0 || m == 0){
		    	var sides_cl = 0;
		    }else{
		    	var sides_cl = (m + (2 * sea));
		    }


		    var sides_yards = ((((sides_cl / 36) * oh) * sides_widths) / qty);


			//end UTR captop calcs
		}



		//CAPTOP AND FRAMED SIDES YDS ADDED per curtain	//updated 4/30/17
		var cap_fra_yds = (captop_yards + sides_yards);

		console.log('cap_fra_yds = ('+captop_yards+' + '+sides_yards+')');
      

		//calculate amount of yds per CC
		//step 1: raw yds per CC

		var yds1 = ((((cl * (tot_weol / qty)) / 36) * oh) + cap_fra_yds);


		console.log('yds1 = ((( '+cl+' * ('+tot_weol+' / '+qty+')) / 36 ) * ('+oh+') + '+cap_fra_yds+')');


		$('#captop-yards').val(roundToTwo(captop_yards).toFixed(2));
	    $('#framed-sides-yards').val(roundToTwo(sides_yards).toFixed(2));
	    

		
		//step 2: when forced to use full yds on ea CC
		var yds2 = Math.ceil(yds1);
		
		//step 3: when forcing distributed rounded yds on the line
		var yds3 = (Math.ceil(yds1 * qty))/ qty;
		
		//route the appropiate value to output
		if(fry == 1){
			var yds_pcc1 = yds2; //step 2
		}else if(fry_dist == 1){
			var yds_pcc1 = yds3;	//step 3
		}else{
			var yds_pcc1 = yds1;	//step 1
		}	
		
		var yds_pcc = roundToTwo(yds_pcc1).toFixed(2);
		
		
		////TOTALS
		//total YDS EOL
		var tot_yds = (yds_pcc1 * qty);
		
	    console.log('tot_yds = '+tot_yds);
		
		//inbound freight calculation
		var ibfrtpy=0;
		
		/* PPSASCRUM-317: start */
		/* if(fab_w >= 54 && fab_w <= 72){
			if(tot_yds < 25){
				ibfrtpy=roundToTwo((25.00/tot_yds)).toFixed(2);
			}else if(tot_yds >= 25 && tot_yds < 60){
				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds25-59']; ?>;
			}else if(tot_yds >= 60 && tot_yds < 250){
				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds60-249']; ?>;
			}else if(tot_yds >= 250){
				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds250plus']; ?>;
			}
		}else if(fab_w >= 73 && fab_w <= 130){
			if(tot_yds < 25){
				ibfrtpy=roundToTwo((25.00/tot_yds)).toFixed(2);
			}else if(tot_yds >= 25 && tot_yds < 60){
				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds25-59']; ?>;
			}else if(tot_yds >= 60 && tot_yds < 250){
				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds60-249']; ?>;
			}else if(tot_yds >= 250){
				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds250plus']; ?>;
			}
		} */
		/* PPSASCRUM-317: end */
		
		/* PPSASCRUM-317: start */
		let totalFabricYds = parseFloat($('#total-yds').val());
		if (totalFabricYds <= 50) {
			ibfrtpy=Math.ceil(<?php echo $settings['inbound_freight_multiplier_totyds50']; ?>/totalFabricYds * 100) / 100;
			console.log('IN FR 1: roundToTwo(<?php echo $settings['inbound_freight_multiplier_totyds50']; ?>', totalFabricYds);
		} else if (totalFabricYds > 50 && totalFabricYds <= 100) {
			ibfrtpy=Math.ceil(<?php echo $settings['inbound_freight_multiplier_totyds51-100']; ?>/totalFabricYds * 100) / 100;
			console.log('IN FR 2: roundToTwo(<?php echo $settings['inbound_freight_multiplier_totyds51-100']; ?>', totalFabricYds);
		} else if (totalFabricYds > 100 && totalFabricYds <= 200) {
			ibfrtpy=Math.ceil(<?php echo $settings['inbound_freight_multiplier_totyds101-200']; ?>/totalFabricYds * 100) / 100;
			console.log('IN FR 3: roundToTwo(<?php echo $settings['inbound_freight_multiplier_totyds101-200']; ?>', totalFabricYds);
		} else if (totalFabricYds > 200 && totalFabricYds <= 300) {
			ibfrtpy=Math.ceil(<?php echo $settings['inbound_freight_multiplier_totyds201-300']; ?>/totalFabricYds * 100) / 100;
			console.log('IN FR 4: roundToTwo(<?php echo $settings['inbound_freight_multiplier_totyds201-300']; ?>', totalFabricYds);
		} else if (totalFabricYds > 300 && totalFabricYds <= 500) {
			ibfrtpy=Math.ceil(<?php echo $settings['inbound_freight_multiplier_totyds301-500']; ?>/totalFabricYds * 100) / 100;
			console.log('IN FR 5: roundToTwo(<?php echo $settings['inbound_freight_multiplier_totyds301-500']; ?>', totalFabricYds);
		} else if (totalFabricYds > 500 && totalFabricYds <= 600) {
			ibfrtpy=Math.ceil(<?php echo $settings['inbound_freight_multiplier_totyds501-600']; ?>/totalFabricYds * 100) / 100;
			console.log('IN FR 6: roundToTwo(<?php echo $settings['inbound_freight_multiplier_totyds501-600']; ?>', totalFabricYds);
		} else if (totalFabricYds > 600) {
			ibfrtpy=Math.ceil(<?php echo $settings['inbound_freight_multiplier_totyds600plus']; ?>/totalFabricYds * 100) / 100;
			// ibfrtpy=<//?php echo $settings['inbound_freight_multiplier_totyds600plus']; ?>;
			console.log('IN FR 7: roundToTwo(<?php echo $settings['inbound_freight_multiplier_totyds600plus']; ?>');
		}
		
		$('#inbound-freight').val(ibfrtpy);
	
	
		if($('#inbound-freight-custom-value').val() != ''){
			ibfrtpy=parseFloat($('#inbound-freight-custom-value').val());
		}
		/* PPSASCRUM-317: end */
		
	
		//cost of fabric
	
		var fab_cost=(roundToTwo(parseFloat(yds_pcc)).toFixed(2)*roundToTwo((parseFloat(fab_ppy)+parseFloat(ibfrtpy))).toFixed(2));
		var fab_cost_pyd=((parseFloat(fab_ppy)+parseFloat(ibfrtpy)));
		console.log('fab_cost = ('+yds_pcc+' * ('+fab_ppy+' + '+ibfrtpy+')) ==> '+(yds_pcc * (fab_ppy+ibfrtpy)));
		var tfab_cost = (fab_cost * qty);
		
		// FIND LABOR COST
		if(rr == 0){
			var lbr_bill = Math.ceil(w / 12);
		}else{	
			var lbr_bill = Math.ceil((w + (2.5 * hem)) / 12);
		}	
		var lbr_cost = (lbr_bill * lbr_plf);

		//cost of mesh
		//var mesh_cost = (mesh_plf * lbr_bill);
		
		if($('#fabric-markup-custom-value').val() != ''){
			var fabricMarkup=(1+(parseFloat($('#fabric-markup-custom-value').val())/100));
		}else{
			var fabricMarkup=(1+(parseFloat($('#fabric-markup').val())/100));
		}
	
		//// CUBICLE CURTAIN COST
		var cc_cost = roundToTwo((lbr_cost + fab_cost*fabricMarkup)).toFixed(2); // + mesh_cost);
		
		//$('#layout').val(adj_lay+" widths ea, "+tot_weol+" widths total");

		$('#widths-each').val(adj_lay);
		$('#total-widths').val(tot_weol);

		if(tot_weol != parseInt(tot_weol)){
			var warn4 = 'WARNING - YOUR LINE TOTAL CONTAINS A FRACTIONAL WIDTH, PLEASE CONSIDER ROUNDING W EOL';
		}else{
			var warn4 = '';
		}


		$('#cl').val(cl);
		$('#labor-billable').val(lbr_bill);
		$('#layout-status').val(warn1);
		$('#fw').val(fw);
		$('#rr-fl-status').val(warn2);
		$('#horiz-waste').val(wast2);
		$('#fw-vs-expected-fw').val(warn3);
		$('#yds-per-unit').val(roundToTwo(yds_pcc).toFixed(2));
		
		
		$('#total-yds').val(roundToTwo(tot_yds).toFixed(2));
		


		<?php
		if($fabricid=='custom'){
		?>
				if(qty > 0 && $('#custom-fabric-cost-per-yard').val() != '' && tot_yds > 0){
					$.each(rulesets,function(index,value){
					    /* PPSASCRUM-278: start */
						if (fab_ppy == 0) {
							$('#fabricmarkupwrap small').hide();
							$('#fabric-markup').val(0);
							$('#fabricmarkupinnerwrap').show();
							$('#inboundfreightwrap small').hide();
							$('#inboundfreightinnerwrap').show();
							return;
						}
						/* PPSASCRUM-278: end */
						if(fab_ppy >= parseFloat(value.price_low) && fab_ppy <= parseFloat(value.price_high) && tot_yds >= parseFloat(value.yds_low) && tot_yds < parseFloat(value.yds_high)){
							$('#fabricmarkupwrap small').hide();
							$('#fabric-markup').val(value.markup);
							$('#fabricmarkupinnerwrap').show();
							$('#inboundfreightwrap small').hide();
							$('#inboundfreightinnerwrap').show();
						}
				   	});
				}

		<?php
		}else{
		?>
		
		
		
		if(qty >0 && $('select[name=fabric-cost-per-yard]').val() != '' ||  $('#fabric-cost-per-yard-custom-value').val() != '' && tot_yds > 0){
			//fab_ppy
		   	$.each(rulesets,function(index,value){
		   	    /* PPSASCRUM-278: start */
				if (fab_ppy == 0) {
					$('#fabricmarkupwrap small').hide();
					$('#fabric-markup').val(0);
					$('#fabricmarkupinnerwrap').show();
					$('#inboundfreightwrap small').hide();
					$('#inboundfreightinnerwrap').show();
					return;
				}
				/* PPSASCRUM-278: end */
				if(fab_ppy >= parseFloat(value.price_low) && fab_ppy <= parseFloat(value.price_high) && tot_yds >= parseFloat(value.yds_low) && tot_yds < parseFloat(value.yds_high)){
					$('#fabricmarkupwrap small').hide();
					$('#fabric-markup').val(value.markup);
					$('#fabricmarkupinnerwrap').show();
					$('#inboundfreightwrap small').hide();
					$('#inboundfreightinnerwrap').show();
				}
		   	});
		}
		
		<?php } ?>
		
		$('#labor-cost').val(roundToTwo(lbr_cost).toFixed(2));
	
	
		if($('#com-fabric').is(':checked')){
			$('#fabric-cost').val('0.00');
			$('#fabric-price-per-yard').val('0.00');
			$('#fabric-price').val('0.00');
			$('select[name=fabric-cost-per-yard]').parent().hide('fast');
			$('#fabricmarkupwrap,#inboundfreightwrap').hide('fast');
		}else{
			console.log('fab_cost = '+fab_cost);
			$('#fabric-cost').val(roundToTwo(fab_cost_pyd).toFixed(2));
			console.log('#fabric-cost = '+fab_cost);

			var markupforprice=fabricMarkup;
			//var markupforprice=(1+(parseFloat($('#fabric-markup').val())/100));

			$('#fabric-price-per-yard').val(roundToTwo((fab_cost_pyd*markupforprice)).toFixed(2));

			$('#fabric-price').val(roundToTwo((fab_cost*markupforprice)).toFixed(2));
			console.log('#fabric-price = '+fab_cost+' * '+markupforprice);
			$('select[name=fabric-cost-per-yard]').parent().show('fast');
			$('#fabricmarkupwrap,#inboundfreightwrap').show('fast');
		}
		

		var fabcostval=parseFloat($('#fabric-cost').val());
		var laborcostval=parseFloat($('#labor-cost').val());


		var costval=roundToTwo((fabcostval+laborcostval)).toFixed(2);
		$('#cost').val(costval);

		var fabpriceval=parseFloat($('#fabric-price').val());

		var startPrice=roundToTwo((fabpriceval+laborcostval)).toFixed(2);
		$('#start-price-val').val(startPrice);
		var addOnTotals=0;
		var addOnText='';


		if($('#mesh-frame').val() == 'Capped Top Only' || $('#mesh-frame').val() == 'Full Frame'){
			var captopincrease=(parseFloat($('#labor-billable').val()) * <?php echo $settings['cap_top_labor_increase_per_lf']; ?>);
			fabpriceval=(fabpriceval+captopincrease);
			addOnTotals=(parseFloat(addOnTotals)+parseFloat(captopincrease));
			addOnText += '<tr><td>Mesh Frame, Capped Top ('+$('#labor-billable').val()+' LF)</td><td>$'+roundToTwo(parseFloat(captopincrease)).toFixed(2)+'</td></tr>';
		}

		//add Side Matching Band increase from Mesh Frame = FULL (if applicable)
		if($('#mesh-frame').val() == 'Full Frame'){
			var sideMatchingBandIncrease=<?php echo $settings['side_matching_band_addon_per_curtain']; ?>;
			fabpriceval=(fabpriceval+sideMatchingBandIncrease);
			addOnTotals=(parseFloat(addOnTotals) + parseFloat(sideMatchingBandIncrease));
			addOnText += '<tr><td>Mesh Frame, Sides</td><td>$'+roundToTwo(parseFloat(sideMatchingBandIncrease)).toFixed(2)+'</td></tr>';
		}

		//add VELCRO MOM/COM increase math (if applicable)
		if($('#velcro').val() != 'None' && parseFloat($('#velcro-lf').val()) > 0){
			if($('#velcro').val() == 'MOM'){
				var velcroIncrease=(parseFloat($('#velcro-lf').val()) * <?php echo $settings['velcro_mom_labor_per_lf']; ?>);
			}else if($('#velcro').val() == 'COM'){
				var velcroIncrease=(parseFloat($('#velcro-lf').val()) * <?php echo $settings['velcro_com_labor_per_lf']; ?>);
			}
			fabpriceval=(fabpriceval+velcroIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(velcroIncrease));

			addOnText += '<tr><td>'+$('#velcro').val()+' Velcro ('+$('#velcro-lf').val()+' LF)</td><td>$'+roundToTwo(parseFloat(velcroIncrease)).toFixed(2)+'</td></tr>';
		}

		//add MESH TAPE MOM/COM increase math (if applicable)
		if($('#snap-tape').val() != 'None'){
			if($('#snaptape-lf').val() == 'Top Only'){
				var snapAmount=parseFloat($('#labor-billable').val());
			}else if($('#snaptape-lf').val() == 'Sides Only'){
				var snapAmount=( Math.ceil( (parseFloat($('#cl').val()) / 12 ) ) * 2);
			}else if($('#snaptape-lf').val() == 'Top + Sides'){
				var snapAmount=(parseFloat($('#labor-billable').val())+( Math.ceil( (parseFloat($('#cl').val()) / 12 ) ) * 2));
			}

			if($('#snap-tape').val() == 'MOM'){
				var snapIncrease=(snapAmount * <?php echo $settings['snap_tape_mom_price_per_lf']; ?>);
				console.log('snapIncrease = ('+snapAmount+' * <?php echo $settings['snap_tape_mom_price_per_lf']; ?>)');
			}else if($('#snap-tape').val() == 'COM'){
				var snapIncrease=(snapAmount * <?php echo $settings['snap_tape_com_price_per_lf']; ?>);
				console.log('snapIncrease = ('+snapAmount+' * <?php echo $settings['snap_tape_com_price_per_lf']; ?>)');
			}

			fabpriceval=(fabpriceval + snapIncrease);
			addOnTotals=(parseFloat(addOnTotals) + parseFloat(fabpriceval));
			addOnText += '<tr><td>'+$('#snap-tape').val()+' Snap Tape, '+$('#snaptape-lf').val()+' ('+snapAmount+' LF)</td><td>$'+roundToTwo(parseFloat(snapIncrease)).toFixed(2)+'</td></tr>';
		}



		//add Weights increase match (if applicable)
		if($('#weights').is(':checked')){
			var weightIncrease=(parseFloat($('#weight-count').val()) * <?php echo $settings['sewn_in_drapery_weights']; ?>);
			fabpriceval=(fabpriceval+weightIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(weightIncrease));
			addOnText += '<tr><td>'+$('#weight-count').val()+'X Weights</td><td>$'+roundToTwo(parseFloat(weightIncrease)).toFixed(2)+'</td></tr>';
		}


		//add Magnets increase match (if applicable)
		if($('#magnets').is(':checked')){
			var magnetIncrease=(parseFloat($('#magnet-count').val()) * <?php echo $settings['sewn_in_magnets']; ?>);
			fabpriceval=(fabpriceval+magnetIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(magnetIncrease));
			addOnText += '<tr><td>'+$('#magnet-count').val()+'X Magnets</td><td>$'+roundToTwo(parseFloat(magnetIncrease)).toFixed(2)+'</td></tr>';
		}


		//add Banding increase match (if applicable)
		if($('#banding').is(':checked')){
			var bandingIncrease=(lbr_bill * <?php echo $settings['banding_contrasting_fabric_seamed']; ?>);
			fabpriceval=(fabpriceval+bandingIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(magnetIncrease));
			addOnText += '<tr><td>Banding ('+lbr_bill+' LF)</td><td>$'+roundToTwo(parseFloat(bandingIncrease)).toFixed(2)+'</td></tr>';
		}



		//add Hidden Mesh increase match (if applicable)
		if($('#hidden-mesh').is(':checked')){
			var hiddenMeshIncrease=(lbr_bill * <?php echo $settings['hidden_mesh_price_per_lf']; ?>);
			fabpriceval=(fabpriceval+hiddenMeshIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(hiddenMeshIncrease));
			addOnText += '<tr><td>Hidden Mesh ('+lbr_bill+' LF)</td><td>$'+roundToTwo(parseFloat(hiddenMeshIncrease)).toFixed(2)+'</td></tr>';
		}



		//add Buttonholes increase match (if applicable)
		if($('#buttonholes').is(':checked')){
			var buttonholeIncrease=(parseFloat($('#buttonhole-count').val()) * <?php echo $settings['buttonholes_insteadof_grommets']; ?>);
			fabpriceval=(fabpriceval+buttonholeIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(buttonholeIncrease));
			addOnText += '<tr><td>'+$('#buttonhole-count').val()+'X Buttonholes (instead of Grommets)</td><td>$'+roundToTwo(parseFloat(buttonholeIncrease)).toFixed(2)+'</td></tr>';
		}



		var priceval=roundToTwo((fabpriceval+laborcostval)).toFixed(2);
		$('#price').val(priceval);
		console.log('#price = '+fabpriceval+' + '+laborcostval +'===>'+roundToTwo(parseFloat(fabpriceval+laborcostval)).toFixed(2));
	
	


		//end add-ons
		$('#add-on-total-val').val(addOnTotals);
		$('#add-on-text-val').val(addOnText);


		$('#total-surcharges').val(roundToTwo(addOnTotals).toFixed(2));

		$('#explainmath').html('<h3 id="pricebreakdown">Base Price Surcharge Breakdown</h3><hr><table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000"><tr><td>Starting Price (before surcharges/add-ons)</td><td><B>$'+roundToTwo(parseFloat($('#start-price-val').val())).toFixed(2)+'</B></td></tr>'+$('#add-on-text-val').val()+'<tr><td><b>RESULTING BASE PRICE:</b></td><td><b><u>$'+roundToTwo(parseFloat($('#price').val())).toFixed(2)+'</u></b></td></tr></table><Br><Br><Br><Br><Br><br><Br><Br><Br>');







		//difficulty calculation
		
       	var wea = adj_lay;
		

	
	

		if($('#snap-tape').val() != 'None'){
			var snp=1;
			$('#snaptapelfwrap').show('fast');
		}else{
			var snp=0;
			$('#snaptapelfwrap').hide('fast');
		}



		if($('#velcro').val() != 'None'){
			var vel=1;
			$('#velcrolfwrap').show('fast');
		}else{
			var vel=0;
			$('#velcrolfwrap').hide('fast');
		}

	
	
		if($('#weights').is(':checked')){
			var wei=1;
		}else{
			var wei=0;
		}
	
	
		if($('#antimicrobial').is(':checked')){
			var am=1;
		}else{
			var am=0;
		}
	


		//mesh present?
		if(m >0){
			var mok = 1;
		}else{
			var mok = 0;
		}	
				
		//cut mesh?
		if((m >0) && (m == 20 || m == 36) && mc == 0){
			var cut_mesh = w;
		}else{
			var cut_mesh = (w * 2.44);
		}	
		
		//captop
		if(cap == 1){
			var captop = (w * 2.44);
		}else{
			var captop = 0;
		}

		//framed mesh
		if(frm == 1){
			var framed = (m * 2.44);
		}else{	
			var framed = 0;	
		}	
		
		//snap mesh
		if(snp == 1){
			var snapm = (w * 2.44);
		}else{	
			var snapm = 0;
		}
		
		//velcro
		if(vel == 1){
			var velcro = Math.ceil(cl);
		}else{
			var velcro = 0;
		}
		
		//weights
		if(wei == 1){
			var weights = (Math.ceil(wea) * 10);
		}else{
			var weights = 0;
		}	
		
		//AM treated fabric
		if(am == 1){
			var am_factor = 1.423;
		}else{
			var am_factor = 1;
		}
		
		////////////////RATINGS///////////////////////
		var ps = (1.5 * am_factor);	//ADJ PANEL SEAMS DIFFICULTY RATING
		var ms = (2.5 * am_factor);	//ADJ MESH SEAM DIFFICULTY RATING
		var he = (2.4 * am_factor);	//ADJ HEMS DIFFICULTY RATING
		
		
		////////////////SEAM PANELS DIFFICULTY///////////////
		var seamp1 = (parseInt(wea)-1);
		//echo("WEA INT " . $seamp1. " / ");
		//console.log('WEA INT: '+seamp1);

		var seamp2 = (wea - parseInt(wea));
		//echo("WEA MOD " . $seamp2. " / ");
		//console.log('WEA MOD: '+seamp2);
		
		if(seamp2 == 0.5 || seamp2 ==0.25 || seamp2 ==0.33 || seamp2 == 0.66){
			var seamp3 = 1;
		}else if(seamp2 == 0.75){	
			var seamp3 = 2;
		}else{
			var seamp3 = 0;
		}
		
		var seams = (seamp1 + seamp3);
		//console.log('SEAMS: '+seams);
		//echo("SEAMS " . $seams . " / ");
		
		if(rr ==1){
			var seam_panel_diff = 0;
		}else{	
			var seam_panel_diff = ((cl * seams) * ps);
		}
		//echo("SEAM PANELS " . $seam_panel_diff . " / ");
		console.log('seam_panel_diff = ('+cl+' * '+seams+') * '+ps);
		
		
		////////////////SEAM MESH DIFFICULTY/////////////////
		var seamm = (cut_mesh + captop + framed); 
		
		if(mok == 1){
			var seam_mesh_diff = (seamm * ms);
		}else{	
			var seam_mesh_diff = 0;
		}
		//console.log('('+cut_mesh+' + '+captop+' + '+framed+')');
		//console.log('('+seamm+' * '+ms+')');

		//echo("SEAM MESH " . $seam_mesh_diff . " / ");
		
		
		///////////////HEMS DIFFICULTY////////////////
		if(liner == 1){
			var hems = 0;
		}else{	
			var hems = (l * 2);
		}	
		console.log('HEMS = '+hems);

		var hems_diff = ((w + hems + snapm + velcro + weights) * he);
		console.log('hems_diff = (('+w+' + '+hems+' + '+snapm+' + '+velcro+' + '+weights+') * '+he+')');
		//echo("HEMS " . $hems_diff);
		
		//////////////////TOTALS///////////////////////
		//PER CURTAIN
		console.log('((SEAM PANEL DIFF: '+seam_panel_diff+' + SEAM MESH DIFF:'+seam_mesh_diff+' + HEMS DIFF:'+hems_diff+') / 100)');
		var cc_diff = ((seam_panel_diff + seam_mesh_diff + hems_diff) / 100);
		//PER LINE
		var diff_eol = (cc_diff * qty);
	
		$('#difficulty-rating').val(Math.ceil(diff_eol));
	
	
	
	
	
	
	
	
	
	
	//////////RUN CALCULATIONS/////////////
		
		//layout trap
		var weightstep1 = roundToTwo(((w / fab_w) - parseFloat(w / fab_w)));
		
		//fabric_width ranging
		if(rr == 1){
			var weightstep2 = 1;
			var weightwarn4 = "";
		}else if(fab_w >= 58 && (weightstep1 == 0 || weightstep1 == 0.51 || weightstep1 == 0.25 || weightstep1 == 0.75 || weightstep1 == 0.26 || weightstep1 == 0.5 || weightstep1 == 0.76)){
			var weightstep2 = 1;
			var weightwarn4 = "";
		}else if(fab_w < 58 && (weightstep1 == 0 || weightstep1 == 0.34 || weightstep1 == 0.51 || weightstep1 == 0.33 || weightstep1 == 0.5 || weightstep1 == 0.66 || weightstep1 == 0.67)){	
			var weightstep2 = 1;
			var weightwarn4 = "";
		}else{
			var weightstep2 = 0;
			var weightwarn4 = " (Check Width & Fabric Width) ";
		}	
		
		//fabric_width classifying
		if(fab_w >=54 && fab_w < 63){
			var weightstep3 = 1;
			var weightwarn2 = "";
		}else if(fab_w >= 63 && fab_w < 70){
			var weightstep3 = 2;
			var weightwarn2 = "";
		}else if(fab_w < 54 || fab_w > 130){
			var weightstep3 = 0;
			var weightwarn2 = " (Fabric Width Invalid)";
		}else{
			var weightstep3 = 3;
			var weightwarn2 = "";
		}	
	
		//RR layout warning
		if(m == 0){
			var ded = 5;
		}else{
			var ded = 3;
		}	
		if(rr == 1 && (((fab_w - ded) + m) < l)){
			var weightstep4 = 0;
			var weightwarn3 = " (RR: Check Length and Mesh) ";
		}else if(rr == 1 && (((fab_w - ded) + m) >= l)){
			var weightstep4 = 1;
			var weightwarn3 = "";
		}else{	
			var weightstep4 = 1;
			var weightwarn3 = "";
		}	
	
		//layout warning
		if(weightstep2 == 1 && weightstep3 != 0 && weightstep4 == 1){ 
			var weightwarn1 = "LAYOUT OK";
		}else{	
			var weightwarn1 = "LAYOUT ERROR";
		}	
		

				//calc fab lbs p/yd  ##########UPDATED 2/28/17############
		
		var fab_lbs_psqin = $('#weight-per-sqin').val();
		
		var fab_lbspy = ((36 * fab_w) * fab_lbs_psqin);
		

		//calc mesh lbs p/lf #########UPDATED 2/28/17############
		var mesh_lbs_psqin = 0.0001769;    // MESH LBS PER SQIN SHOULD BE PULLED OFF A DB##
		
		var mesh_lbsplf = ((mesh_lbs_psqin * m )* 12);
		
		// FIND LAYOUT
		if(rr ==1){
			var weightlay = 1;	//layout always 1 when RR
		}else{
			var weightlay = (w / fab_w);
		}

		//find cut length	<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		var weightsea = 1 ; // inches needed per seam ##THIS SHOULD BE PULLED OFF A DB##
		var weighthem = 2 ; // inches needed per hem ##THIS SHOULD BE PULLED OFF A DB##
		var weighthead = 3; // inches needed for header ##THIS SHOULD BE PULLED OFF A DB##

		//step 1: raw value
        if(m == 0){
            var weightcl_1 = (l + weighthead + weighthem);
        }else{
            var weightcl_1 = (l - m) + (weightsea + weighthem);
        }
        //step 4: adj for RR
        if(rr == 0){
            var weightcl = weightcl_1;
        }else if(rr == 1 && m > 0){
            var weightcl = (w + (2.5 * weighthem));
        }else if(rr == 1){
			var weightcl = (w + (2.5 * weighthem));
        }		
		
		//CALC FABRIC WEIGHT
		var fab_wei = (((weightlay * weightcl)/36) * fab_lbspy);
		
		//CALC MESH WEIGHT
		var mesh_wei = ((w / 12) * mesh_lbsplf);
		
		//CALC CUBICLE WEIGHT
		var wei = (fab_wei + mesh_wei);
		//total line weight
		var tot_wei = (wei * qty);
	
	
		$('#cc-calculated-weight').val(roundToTwo(tot_wei).toFixed(2));
	
	
	
	$('#warningbox').html('');
        var warningboxcontent='';
        var warncount=0;
    
    /* PPSASCRUM-317: start */
	/* PPSASCRUM-382: start */
	// if (!$('#com-fabric').is(':checked') && parseFloat($('#total-yds').val()) > 600 && $('#inbound-freight-custom-value').val() == '') {
	if (!$('#com-fabric').is(':checked')) {
		if (parseFloat($('#total-yds').val()) > 600 && $('#inbound-freight-custom-value').val() == '') {
		    warningboxcontent += '<span style="color:red !important;"><img src="/img/delete.png" /> Total Yards greater than 600. You must check inbound freight cost with Purchasing and must type in an override value.</span><br>';
	/* PPSASCRUM-382: end */
    		$('#inbound-freight-custom-value').removeClass('notvalid').removeClass('validated').addClass('notvalid');
    		warncount++;
		} 
		/* PPSASCRUM-382: start */
		else if (parseFloat($('#total-yds').val()) <= 600 && !isNaN(parseFloat($('#inbound-freight-custom-value').val())) && parseFloat($('#inbound-freight-custom-value').val()) > 0) {
			warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> Total Yards is less than 600, yet an override inbound freight cost p/Yd has been entered</span><br>';
			warncount++;
		} 
		/* PPSASCRUM-382: end */
	} else {
		if ($('#inbound-freight-custom-value').val() != '') {
			$('#inbound-freight-custom-value').removeClass('notvalid').removeClass('validated').addClass('validated');
		} else {
			$('#inbound-freight-custom-value').removeClass('notvalid').removeClass('validated');
		}
	}
	/* PPSASCRUM-317: end */

        //warn1
	if($('#layout-status').val() != 'LAYOUT OK'){
		warningboxcontent += '<img src="/img/delete.png" /> '+$('#layout-status').val()+'<br>';
        warncount++;
		if($('#railroaded').is(':checked')){
			$('#expected-finish-width,#fabric-width,#widths-each,#total-widths').parent().addClass('badvalue');
		}else{
			$('#utr-width,#fabric-width,#widths-each,#total-widths').parent().addClass('badvalue');
		}
		
	}else{
		if($('#railroaded').is(':checked')){
			$('#expected-finish-width,#fabric-width,#widths-each,#total-widths').parent().removeClass('badvalue');
		}else{
			$('#utr-width,#fabric-width,#widths-each,#total-widths').parent().removeClass('badvalue');
		}
	}
		
	//warn2
	if($('#rr-fl-status').val() != 'CC is UTR - YOU ARE OK' && $('#rr-fl-status').val() != 'RR FL OK'){
            warningboxcontent += '<img src="/img/delete.png" /> '+$('#rr-fl-status').val()+'<br>';
            warncount++;
			if($('#railroaded').is(':checked')){
				$('#length,#mesh,#finished-mesh,#fabric-width').parent().addClass('badvalue');
			}else{
				$('#length,#mesh,#finished-mesh,#fabric-width').parent().removeClass('badvalue');
			}
	}else{
		$('#length,#mesh,#finished-mesh,#fabric-width').parent().removeClass('badvalue');
	}
		
	//warn3
	if($('#fw-vs-expected-fw').val() != 'YOU ARE OK'){

		if(warn3 == ' NO EXPECTED FINISHED WIDTH'){
			warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> NO EXPECTED FINISHED WIDTH</span><br>';
			$('#expected-finish-width').parent().addClass('alertcontent');
			warncount++;
		}else{
			warningboxcontent += '<img src="/img/delete.png" /> '+$('#fw-vs-expected-fw').val()+'<br>';

			if(warn3=='BAD FINISHED WIDTH vs EXPECTED FINISHED WIDTH - CHECK YOUR INPUTS'){
				$('#expected-finish-width,#utr-width,#fw').parent().addClass('badvalue');
			}else{
				$('#expected-finish-width,#utr-width,#fw').parent().removeClass('badvalue');
			}

			$('#expected-finish-widths').parent().removeClass('alertcontent');
	        warncount++;
	    }
	}else{
		$('#expected-finish-width,#utr-width,#fw').parent().removeClass('badvalue');
		$('#expected-finish-width').parent().removeClass('alertcontent');
	}


	//warn 4
	if(warn4 != ''){
		warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> YOUR LINE TOTAL CONTAINS A FRACTIONAL WIDTH, PLEASE CONSIDER ROUNDING W EOL</span><br>';
		$('#total-widths').parent().addClass('alertcontent');
		$('#force-full-widths-eol').parent().parent().addClass('alertcontent');
		warncount++;
	}else{
		$('#total-widths').parent().removeClass('alertcontent');
		$('#force-full-widths-eol').parent().parent().removeClass('alertcontent');
	}

        if(warncount == 0){
    	     $('#warningbox').hide('fast');
        }else{
             $('#warningbox').html(warningboxcontent).show('fast');
        }
        //alert(warningboxcontent);



        //adjustments?
		<?php
		if(intval($quoteID) == 0){
		echo "var tierAdjustments={	'1':'".$settings['tier_1_premium']."','2':'".$settings['tier_2_premium']."','3':'".$settings['tier_3_premium']."','4':'".$settings['tier_4_premium']."','5':'".$settings['tier_5_premium']."','6':'".$settings['tier_6_premium']."','7':'".$settings['tier_7_premium']."','8':'".$settings['tier_8_premium']."','9':'".$settings['tier_9_premium']."','10':'".$settings['tier_10_premium']."','11':'".$settings['tier_11_premium']."','12':'".$settings['tier_12_premium']."'};
		";
		?>
		var adjprice=(parseFloat($('#price').val()) * (1+(tierAdjustments[$('#tier_adjustment').val()]/100)));

		if($('#install_surcharge').is(':checked')){
			if($('#com-fabric').is(':checked')){
				//com install adjustment
				adjprice=(adjprice*(1+(<?php echo $settings['install_surcharge_com_percent']; ?>/100)));
			}else{
				//mom install adjustment
				adjprice=(adjprice*(1+(<?php echo $settings['install_surcharge_mom_percent']; ?>/100)));
			}
		}

		if(parseFloat($('#add_surcharge').val()) >0){
			adjprice=(adjprice*(1+(parseFloat($('#add_surcharge').val())/100)));
		}

		var extendedprice=(adjprice*parseFloat($('#qty').val()));

		$('#adjusted-price').val(roundToTwo(adjprice).toFixed(2));

		$('#extended-price').val(roundToTwo(extendedprice).toFixed(2));
		<?php
		}
		?>


}
	
function calculateLabor(){
	if($('#com-fabric').is(':checked')){
		var baselaborrate=<?php echo $settings['labor_cost_cc_com']; ?>;
		var com_or_mom='com';
	}else{
		var baselaborrate=<?php echo $settings['labor_cost_cc_mom']; ?>;
		var com_or_mom='mom';
	}

	

	var finalLaborRate=baselaborrate;
	
	

	if($('#mesh-type').val() == 'COM Mesh'){
		//override base labor rate
		finalLaborRate=<?php echo $settings['com_mesh_labor_rate_per_lf']; ?>;
		console.log('Base labor rate overridden to <?php echo $settings['com_mesh_labor_rate_per_lf']; ?> for COM Mesh option');
	}else{


		if($('#angled-mesh').is(':checked')){
			if($('#com-fabric').is(':checked')){
				finalLaborRate=<?php echo $settings['angled_mesh_com_per_lf']; ?>;
				console.log('Labor rate overridden to <?php echo $settings['angled_mesh_com_per_lf']; ?> for COM Angled Mesh');
			}else{
				finalLaborRate=<?php echo $settings['angled_mesh_mom_per_lf']; ?>;
				console.log('Labor rate overridden to <?php echo $settings['angled_mesh_mom_per_lf']; ?> for MOM Angled Mesh');
			}
		}else{
			if($('#nylon-mesh').is(':checked')){
				//determine the color and size of mesh to get the correct rate
				var meshSize=parseFloat($('#mesh').val());
				if($('select[name=mesh-color]').val() == 'White'){
					if(meshSize > 0 && meshSize <= 33){
						if(com_or_mom=='mom'){
							finalLaborRate=<?php echo $settings['nylon_mesh_mom_white_0-33_inches_per_lf']; ?>;
						}else if(com_or_mom=='com'){
							finalLaborRate=<?php echo $settings['nylon_mesh_com_white_0-33_inches_per_lf']; ?>;
						}
					}else if(meshSize > 33 && meshSize <= 68){
						if(com_or_mom=='mom'){
							finalLaborRate=<?php echo $settings['nylon_mesh_mom_white_34-68_inches_per_lf']; ?>;
						}else if(com_or_mom=='com'){
							finalLaborRate=<?php echo $settings['nylon_mesh_com_white_34-68_inches_per_lf']; ?>;
						}
					}
				}else if($('select[name=mesh-color]').val() == 'Beige'){
					if(meshSize > 0 && meshSize <= 33){
						if(com_or_mom=='mom'){
							finalLaborRate=<?php echo $settings['nylon_mesh_mom_beige_0-33_inches_per_lf']; ?>;
						}else if(com_or_mom=='com'){
							finalLaborRate=<?php echo $settings['nylon_mesh_com_beige_0-33_inches_per_lf']; ?>;
						}
					}else if(meshSize > 33 && meshSize <= 68){
						if(com_or_mom=='mom'){
							finalLaborRate=<?php echo $settings['nylon_mesh_mom_beige_34-68_inches_per_lf']; ?>;
						}else if(com_or_mom=='com'){
							finalLaborRate=<?php echo $settings['nylon_mesh_com_beige_34-68_inches_per_lf']; ?>;
						}
					}
				}
			}else{
				if($('#mesh-type').val() == 'None'){
					if($('#com-fabric').is(':checked')){
						finalLaborRate=<?php echo $settings['no_mesh_com_per_lf']; ?>;
					}else{
						finalLaborRate=<?php echo $settings['no_mesh_mom_per_lf']; ?>;
					}
				}else if($('#mesh-type').val() == 'Integral Mesh'){
					if($('#com-fabric').is(':checked')){
						finalLaborRate=<?php echo $settings['integral_mesh_com_per_lf']; ?>;
					}else{
						finalLaborRate=<?php echo $settings['integral_mesh_mom_per_lf']; ?>;
					}
				}
			}

		}

	}


	$('#labor-per-lf').val(finalLaborRate.toFixed(2));

}



<?php
if($userData['scroll_calcs'] == 1){
?>

function correctColHeights(){
	$('#calcformleft,#resultsblock').height(($(window).height()-240));
}

<?php } ?>

$(function(){

	
	$('input[type=text],input[type=number]').focus(function(){
		$(this).select();
	});
	
	
	$('#custom-base-labor,#vertical-repeat,#inbound-freight-custom-value,#fabric-markup-custom-value').keyup(function(){
  		var newval=$(this).val().replace(/[^0-9.]+/, '');
      	newval=newval.replace('..', '.');
      	$(this).val(newval);
  	});

<?php
if($userData['scroll_calcs'] == 1){
?>
correctColHeights();
setInterval('correctColHeights()',500);
<?php } ?>


	/*
	$('#com-fabric').click(function(){
		if(canCalculate(){
			doCalculation();
		}
	});
	$('#com-fabric').change(function(){
		if(canCalculate(){
			doCalculation();
		}
	});
	*/
	/**PPSA-33 start **/
	$('#com-fabric').click(function(){
			if($(this).prop('checked')){
			      $('#fabric-cost-per-yard-custom-value').val('');
			}
	});
	$('#com-fabric').change(function(){
	    	if($(this).prop('checked')){
	    	      $('#fabric-cost-per-yard-custom-value').val('');
	    	}
		
	});
	/**PPSA-33 end **/

	if($('#mesh-type').val() == 'None'){
		$('#mesh').parent().hide('fast');
		$('#mesh-color').parent().hide('fast');
		$('#mesh-frame').parent().hide('fast');
		$('#hidden-mesh').parent().hide('fast');
		$('#nylon-mesh').prop('checked',false);
		$('#nylon-mesh').parent().hide('fast');
		$('#angled-mesh').prop('checked',false);
		$('#angledmeshwrap').hide('fast');
		$('#finished-mesh').parent().hide('fast');
	}else{
		$('#mesh').parent().show('fast');

		$('#mesh-color').parent().show('fast');
		$('#mesh-frame').parent().show('fast');
		$('#hidden-mesh').parent().show('fast');
		$('#finished-mesh').parent().show('fast');
		if($(this).val() != 'COM Mesh'){
			$('#nylon-mesh').parent().show('fast');
		}else{
			$('#nylon-mesh').parent().hide('fast');
		}
	}


	$('#mesh-type').change(function(){
		if($(this).val() == 'None'){
			$('#mesh').val('0');
			$('#mesh').parent().hide('fast');
			$('#mesh-color').parent().hide('fast');
			$('#mesh-frame').parent().hide('fast');
			$('#hidden-mesh').parent().hide('fast');
			$('#nylon-mesh').prop('checked',false);
			$('#nylon-mesh').parent().hide('fast');
			$('#angled-mesh').prop('checked',false);
			$('#angledmeshwrap').hide('fast');
			$('#finished-mesh').parent().hide('fast');
		}else{
			$('#mesh').parent().show('fast');

			$('#mesh-color').parent().show('fast');
			$('#mesh-frame').parent().show('fast');
			$('#hidden-mesh').parent().show('fast');
			$('#finished-mesh').parent().show('fast');

			if($(this).val() != 'COM Mesh'){
				$('#nylon-mesh').parent().show('fast');
			}else{
				$('#nylon-mesh').parent().hide('fast');
			}

			if($(this).val() == 'Integral Mesh'){
				$('#mesh').val('0');
				//$('#mesh').val('<?php echo $settings['mesh_default']; ?>');

				if(!$('#railroaded').is(':checked')){
					$('#railroaded').trigger('click');
				}
			}else{
				$('#mesh').val('<?php echo $settings['mesh_default']; ?>');
			}

		}
	});



	$('form input, form select, form').on('keypress', function(e) {
		return e.which !== 13;
	});

	$('.calculatebutton button').click(function(){
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});
	
	
	$('#velcro').change(function(){
		if($(this).val() != 'None'){
			$('#velcrolfwrap').show('fast');
		}else{
			$('#velcrolfwrap').hide('fast');
		}
	});


	$('#snap-tape').change(function(){
		if($(this).val() != 'None'){
			$('#snaptapelfwrap').show('fast');
		}else{
			$('#snaptapelfwrap').hide('fast');
		}
	});



	
	$('#goadvanced').click(function(){
		if($('#expandcollapse').is(':visible')){
			$('#expandcollapse').hide('fast');
			$('#advancedfieldsvisible').val('0');
		}else{
			$('#expandcollapse').show('fast');
			$('#advancedfieldsvisible').val('1');
		}
	});


	$('#nylon-mesh').click(function(){
		if($(this).is(':checked')){
			$('#angledmeshwrap').show('fast');
		}else{
			$('#angled-mesh').prop('checked',false);
			$('#angledmeshwrap').hide('fast');
		}
	});

	$('#nylon-mesh').change(function(){
		if($(this).is(':checked')){
			$('#angledmeshwrap').show('fast');
		}else{
			$('#angled-mesh').prop('checked',false);
			$('#angledmeshwrap').hide('fast');
		}
	});



	$('#weights').click(function(){
		if($(this).is(':checked')){
			$('#weightcountwrap').show('fast');
		}else{
			$('#weightcountwrap').hide('fast');
		}
	});


	$('#weights').change(function(){
		if($(this).is(':checked')){
			$('#weightcountwrap').show('fast');
		}else{
			$('#weightcountwrap').hide('fast');
		}
	});




	$('#buttonholes').click(function(){
		if($(this).is(':checked')){
			$('#buttonholecountwrap').show('fast');
		}else{
			$('#buttonholecountwrap').hide('fast');
		}
	});


	$('#buttonholes').change(function(){
		if($(this).is(':checked')){
			$('#buttonholecountwrap').show('fast');
		}else{
			$('#buttonholecountwrap').hide('fast');
		}
	});


	$('#mesh').keyup($.debounce( 1100, function(){
		if(parseFloat($(this).val()) < 20 || parseFloat($(this).val()) > 20){
			$('#nylon-mesh').prop('checked',true);
		}
	}));



	$('#magnets').click(function(){
		if($(this).is(':checked')){
			$('#magnetcountwrap').show('fast');
		}else{
			$('#magnetcountwrap').hide('fast');
		}
	});


	$('#magnets').change(function(){
		if($(this).is(':checked')){
			$('#magnetcountwrap').show('fast');
		}else{
			$('#magnetcountwrap').hide('fast');
		}
	});


	$('#railroaded').click(function(){
		if($(this).prop('checked')){
			//$('#rrwidthwrap').show('fast');
			$('#utrwidthwrap').hide('fast');
			$('#width').val($('#expected-finish-width').val());
			
			$('#verticalrepeatwrap').hide('fast');
			$('#vrptwastewrap').hide('fast');
			$('#horizontalwastewrap').show('fast');
		}else{
			$('#utrwidthwrap').show('fast');
			//$('#rrwidthwrap').hide('fast');
			$('#width').val($('#utr-width').val());
			
			$('#verticalrepeatwrap').show('fast');
			$('#vrptwastewrap').show('fast');
			$('#horizontalwastewrap').hide('fast');
		}
	});

	$('#railroaded').change(function(){
		if($(this).prop('checked')){
			//$('#rrwidthwrap').show('fast');
			$('#utrwidthwrap').hide('fast');
			$('#width').val($('#expected-finish-width').val());
			
			$('#verticalrepeatwrap').hide('fast');
			$('#vrptwastewrap').hide('fast');
			$('#horizontalwastewrap').show('fast');
		}else{
			$('#utrwidthwrap').show('fast');
			//$('#rrwidthwrap').hide('fast');
			$('#width').val($('#utr-width').val());
			
			$('#verticalrepeatwrap').show('fast');
			$('#vrptwastewrap').show('fast');
			$('#horizontalwastewrap').hide('fast');
		}
	});





	$('#expected-finish-width,#utr-width').keyup(function(){
		$('#width').val($(this).val());
	});


	$('#expected-finish-width,#utr-width').change(function(){
		if($('#utr-width').val() == '0'){
			$('#customutrwidthwrap').show();
			//$('select#utr-width').hide();
		}else{
			//$('select#utr-width').show();
			$('#customutrwidthwrap').hide();
			$('#width').val($(this).val());
		}
	});

	$('#custom-utr-width').change(function(){
		$('#width').val($(this).val());
	});



	
	

	$('#calcformleft input,#calcformleft select').keyup(function(){
			if(canCalculate()){	
				$('#cannotcalculate').hide();
				doCalculation(); 
			}else{
				$('#cannotcalculate').show();
			}
	});
	
	$('#calcformleft input,#calcformleft select,#tier_adjustment,#add_surcharge').change(function(){
			if(canCalculate()){	
				$('#cannotcalculate').hide();
				doCalculation(); 
			}else{
				$('#cannotcalculate').show();
			}
	});
	
	/*PPSASCRUM-PPSASCRUM-259 start */
	
	$('#length').keyup(function(){
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});
	
/*PPSASCRUM-PPSASCRUM-259 end */
	

	$('#install_surcharge').click(function(){
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});
	
	
	$('#cancelbutton').click(function(){
		/* PPSASCRUM-385: start */
		// location.replace('/quotes/add/<?php //echo $quoteID; ?>/');//parent.$.fancybox.close();
		location.replace('/quotes/add/<?php echo $quoteID; ?>/<?php echo $ordermode; ?>');
		/* PPSASCRUM-385: end */
	});
	
	
	/* PPSASCRUM-317: start */
	/* PPSASCRUM-382: start */
	$("#qty").change(function () {
		/* PPSASCRUM-382: end */
		console.log("TOTAL YDS CHANGED!");
		/* PPSASCRUM-382: start */
		if (
			parseFloat($("#total-yds").val()) > 600 &&
			$("#inbound-freight-custom-value").hasClass("notvalid")
		) {
		/* PPSASCRUM-382: end */
			if ($("#inbound-freight-custom-value").val() != "") {
				$("#inbound-freight-custom-value")
				.removeClass("notvalid")
				.removeClass("validated")
				.addClass("validated");
			}
		} else if (
			/* PPSASCRUM-382: start */
			parseFloat($("#total-yds").val()) <= 600 &&
			$("#inbound-freight-custom-value").hasClass("notvalid")
		) {
		$("#inbound-freight-custom-value")
			.removeClass("notvalid")
			.removeClass("validated");
			}
		/* PPSASCRUM-382: end */
	});

	$("#inbound-freight-custom-value").change(function () {
		/* PPSASCRUM-382: start */
		// if (parseFloat($('#total-yds').val()) > 600 && $('#inbound-freight-custom-value').hasClass('notvalid') && $('#warningbox').text().includes(' Total Yards greater than 600. You must check inbound freight cost with Purchasing and must type in an override value.')) {
		if (
			parseFloat($("#total-yds").val()) > 600 &&
			$("#inbound-freight-custom-value").hasClass("notvalid")
		) {
			/* PPSASCRUM-382: end */
			if ($("#inbound-freight-custom-value").val() != "") {
			$("#inbound-freight-custom-value")
				.removeClass("notvalid")
				.removeClass("validated")
				.addClass("validated");
			}
		} else if (
			parseFloat($("#total-yds").val()) > 600 &&
			$("#inbound-freight-custom-value").val() == ""
		) {
			$("#inbound-freight-custom-value")
			.removeClass("notvalid")
			.removeClass("validated")
			.addClass("notvalid");
		} else if (
			/* PPSASCRUM-382: start */
			parseFloat($("#total-yds").val()) <= 600 &&
			$("#inbound-freight-custom-value").hasClass("notvalid")
		) {
			$("#inbound-freight-custom-value")
			.removeClass("notvalid")
			.removeClass("validated")
			.addClass("validated");
		}
	});

	$("input, select").on("change", function () {
		if (canCalculate()) {
			doCalculation();
			console.log("Input changed");
			// doCalculation();
		}
	});

	$("button").on("click", function () {
		if (canCalculate()) {
			doCalculation();
			console.log("Input changed");
			// doCalculation();
		}
	});
	/* PPSASCRUM-317: end */

	
	if(canCalculate()){	
		$('#cannotcalculate').hide();
		doCalculation(); 
	}else{
		$('#cannotcalculate').show();
	}
	
	$('form').submit(function(){
		//warn1
		if($('#layout-status').val() != 'LAYOUT OK' && $('#overrideLayoutWarnings').val()=='0'){
			$.fancybox({
				'type':'inline',
				'content':'<div style="text-align:center;"><span style="color:red;"><b>WARNING:</b></span><br>'+$('#layout-status').val()+'<br><br><button style="padding:10px;" onclick="$(\'#overrideLayoutWarnings\').val(\'1\');$(\'form\').submit();$.fancybox.close();">Proceed</button> <button type="button" style="padding:10px;" onclick="$.fancybox.close()">Go Back</button></div>',
				'modal':true,
				'width':'500',
				'height':'140',
				'autoSize':false
			});
			return false;
		}
		
		//warn2
		if($('#rr-fl-status').val() != 'CC is UTR - YOU ARE OK' && $('#rr-fl-status').val() != 'RR FL OK' && $('#overrideRRWarnings').val()=='0'){
			$.fancybox({
				'type':'inline',
				'content':'<div style="text-align:center;"><span style="color:red;"><b>WARNING:</b></span><br>'+$('#rr-fl-status').val()+'<br><br><button style="padding:10px;" onclick="$(\'#overrideRRWarnings\').val(\'1\');$(\'form\').submit();$.fancybox.close();">Proceed</button> <button type="button" style="padding:10px;" onclick="$.fancybox.close()">Go Back</button></div>',
				'modal':true,
				'width':'500',
				'height':'140',
				'autoSize':false
			});
			return false;
		}
		
		//warn3
		if($('#fw-vs-expected-fw').val() != 'YOU ARE OK' && $('#overrideFinishedWidthWarnings').val()=='0'){
			$.fancybox({
				'type':'inline',
				'content':'<div style="text-align:center;"><span style="color:red;"><b>WARNING:</b></span><br>'+$('#fw-vs-expected-fw').val()+'<br><br><button style="padding:10px;" onclick="$(\'#overrideFinishedWidthWarnings\').val(\'1\');$(\'form\').submit();$.fancybox.close();">Proceed</button> <button type="button" style="padding:10px;" onclick="$.fancybox.close()">Go Back</button></div>',
				'modal':true,
				'width':'500',
				'height':'140',
				'autoSize':false
			});
			return false;
		}
		
	});

});
	
function canCalculate(){
	var errorcount=0;
	
	if($('#qty').val()=='0' || $('#qty').val()==''){
		//console.log('Cannot calculate without a Qty value');
		$('#qty').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#qty').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	
	
	if($('#railroaded').is(':checked')){

		
		if($('#expected-finish-width').val()=='0' || $('#expected-finish-width').val()==''){
			//console.log('Cannot calculate without a Cut Width value');
			$('#expected-finish-width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
			errorcount++;
		}else{
			$('#expected-fininsh-width').removeClass('notvalid').removeClass('validated').addClass('validated');
		}
		
		$('#utr-width').removeClass('notvalid').removeClass('validated');
		$('#width').val($('#expected-finish-width').val());
		

		if($('#expected-finish-width').val() == '0' || $('#expected-finish-width').val() == ''){
			$('#expected-finish-width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
			errorcount++;
		}else{
			$('#expected-finish-width').removeClass('notvalid').removeClass('validated').addClass('validated');
		}

	}else{
		
		if(($('#utr-width').val()=='0' && $('#custom-utr-width').val() == '') || $('#utr-width').val()==''){
			//console.log('Cannot calculate without a Cut Width value');
			$('#utr-width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
			errorcount++;
		}else{
			$('#utr-width').removeClass('notvalid').removeClass('validated').addClass('validated');
		}
		$('#expected-finish-width').removeClass('notvalid').removeClass('validated');
		$('#width').val($('#utr-width').val());
	}
	
	
	if($('#length').val()=='0' || $('#length').val()==''){
		//console.log('Cannot calculate without a Length value');
		$('#length').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#length').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	
	
	if($('#fabric-width').val() == '0' || $('#fabric-width').val()==''){
		//console.log('Cannot calculate without a Fabric Width value');
		$('#fabric-width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#fabric-width').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	
	
	/*if($('#vertical-repeat').val() == '0' || $('#vertical-repeat').val()==''){
		//console.log('Cannot calculate without a Vertical Repeat value');
		$('#vertical-repeat').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#vertical-repeat').removeClass('notvalid').removeClass('validated').addClass('validated');
	}*/
	
	
	<?php
	if($fabricid=='custom'){
	?>
	if($('#custom-fabric-cost-per-yard').val() == '' || parseFloat($('#custom-fabric-cost-per-yard').val()) == 0){
		if(!$('#com-fabric').is(':checked')){
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated').addClass('notvalid');
			errorcount++;
		}else{
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated');
		}
	}else{
		$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	<?php
	}else{
	?>

	if($('select[name=fabric-cost-per-yard]').val()=='0' || $('select[name=fabric-cost-per-yard]').val()==''){
		//console.log('Cannot calculate without a Fabric Cost (per CC) value');
		$('select[name=fabric-cost-per-yard]').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('select[name=fabric-cost-per-yard]').removeClass('notvalid').removeClass('validated').addClass('validated');
	}

	<?php } ?>
	
	if(parseFloat($('#labor-per-lf').val()) == 0){
		//console.log('Cannot calculate without a Labor Per Linear Foot value');
		$('#labor-per-lf').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#labor-per-lf').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	
	
	if($('select[name=fabric-price-per-yard]').val() == '0'){
		//console.log('Cannot calculate without a Fabric Price (per yard) selection');
		errorcount++;
	}
		/*ppsa-33 start */
	if ($('#fabric-cost-per-yard-custom-value').val().trim().length != 0) {
        $('#specialCost').show();
        $("#fabriccostwrapinnerwrap").css({'clear': 'none','padding': ''});

    }
    else {
        $('#specialCost').hide();
        $("#fabriccostwrapinnerwrap").css({'clear': 'both','padding': '10px 0px'});
    }
    
    $('select[name=fabric-cost-per-yard]').on('change', function (e) {
        $('#specialCost').hide();
        $('#fabric-cost-per-yard-custom-value').val('');
        $("#fabriccostwrapinnerwrap").css({'clear': 'both','padding': '10px 0px'});
        
    });
    /*ppsa-33 end */
    
    /*PPSASSCRUM-201 start */
	if(($('#quotetype').val() == 1)  &&   $('#location').val()==''){
		//console.log('Cannot calculate without a Length value');
		$('#location').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#location').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	/*PPSASSCRUM-201 end */
	
	/* PPSASCRUM-317: start */
	/* if (parseFloat($('#total-yds').val()) > 600) {
		if ($('#inbound-freight-custom-value').val() == '') {
			$('#inbound-freight-custom-value').removeClass('notvalid').removeClass('validated').addClass('notvalid');
			errorcount++;
		} else {
			$('#inbound-freight-custom-value').removeClass('notvalid').removeClass('validated').addClass('validated');
		}
	} else {
		$('#inbound-freight-custom-value').removeClass('notvalid').removeClass('validated');
	} */
	/* PPSASCRUM-317: end */
	
	if(errorcount > 0){
		return false;
	}else{
		return true;
	}
}

/* PPSASCRUM-317: start */
function checkSubmission() {
	let errorCount = 0;

	if (!$('#com-fabric').is(':checked') && parseFloat($('#total-yds').val()) > 600 && $('#inbound-freight-custom-value').val() == '') {
		errorCount++;
	}

	return errorCount > 0 ? false : canCalculate();
}
/* PPSASCRUM-317: end */

</script>
<script>
//on enter keydown treat it as tab
$(function(){
    $(document).on('keydown', 'input:visible, select:visible, button:visible,feildset:visible, [type="radio"]:visible, [type="checkbox"]:visible, [tabindex]', function(e) {
            if (e.which === 13) { // 13 is the Enter key code
                e.preventDefault(); // Prevent default action, which is form submission
                moveToNextFocusableElement(this);
            }
        });
});
</script>
<br><br><Br><Br>
<div id="explainmath"></div>