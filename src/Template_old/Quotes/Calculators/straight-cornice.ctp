<style>
body{font-family:'Helvetica',Arial,sans-serif; font-size:medium;}
h1{ text-align:center; font-size:x-large; font-weight:bold; color:#1F2E67; margin:15px 0 0 0; }
form{ text-align:center; width:95%; max-width:750px; margin:20px auto; }
	
form label{ font-weight:bold; float:left; width:68%; text-align:left; font-size:small; vertical-align:middle; }
form input{ float:right; padding:5px !important; height:auto !important; width:20%; vertical-align:middle; font-size:12px; margin-bottom:0 !important; }

#calcformleft input{ width:27%; }

form select{ float:right; padding:5px !important; height:auto !important; width:22% !important; vertical-align:middle; font-size:12px; margin-bottom:0 !important; }
form div.input{ clear:both; padding:10px 0; }
#calculatedPrice{ clear:both; padding:20px 0; font-size:x-large;  }

#cannotcalculate{ color:red; text-align: center; font-weight:normal; padding:5px; font-size:12px; display:block; border:1px solid red; background:#FDDDDE; }

#explainmath{ max-width:650px; margin:0 auto; text-align:center; }

div.suboptions label{ text-indent:22px; }


fieldset.fieldsection{ padding:0px !important; margin:0 0 20px 0 !important; background:#dddddd; }

/*fieldset.fieldsection .input{ background:none !important; }*/


.badvalue{ background:#F1B7B8 !important; }
.alertcontent{ background:#F9E6A6 !important; }



#goadvanced{ font-size:12px; color:#000; background:#ccc; border:1px solid #000; padding:5px 10px; margin:0; display:inline-block; }	

#calcformleft .selectbox label{ width:55% !important; }
#calcformleft .selectbox select{ width:36% !important; }

#calcformleft label[for=style]{ width:55% !important; }
#calcformleft select[name=style]{ width:36% !important; }

#calcformleft input.notvalid,#calcformleft select.notvalid{ border:1px solid red; }
#calcformleft input.validated,#calcformleft select.validated{ border:1px solid green; }
	
.clear{ clear:both; }


#calcformleft{ width:48.5%; float:left; }
#resultsblock{ width:48.5%; padding:10px; float:right; background:#EEE; }

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
#resultsblock .input input{ width:42%; }

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


#layoutimgwrap{ float:right; width:43%; }
#layoutimgwrap img{ width:100%;height:auto; }
#layoutimgwrap label[for=layoutimg]{ width:48% !important; }


label[for=location]{ width:35% !important; }
#location{ width:60% !important; }



label[for=halfwidths-recommendation]{ display:none; }
#halfwidths-recommendation{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }


label[for=fabric-half-widths-status]{ display:none; }
#fabric-half-widths-status{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }


label[for=lining-halfwidths-status]{ display:none; }
#lining-halfwidths-status{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }


label[for=fabric-usage-for-welts]{ display:none; }
#fabric-usage-for-welts{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; height:55px; }

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
        'href':'/quotes/changecalcitemfabric/'+$('#fabricid').val(),
        'autoSize':false,
        'width':450,
        'height':300,
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
	echo "<h1>Standalone Cornice Calculator</h1><hr>";
}


echo $this->Form->create();
echo "<div id=\"calcformleft\">";
echo "<h2>Cornice</h2>";// - ".$fabricData['fabric_name']." (".$fabricData['color'].")</h2>";

echo "<div class=\"input\">
    <label style=\"width:44% !important;\">Fabric/Pattern</label>
    <div id=\"fabricname\">".$fabricData['fabric_name']."</div>
</div>
<div class=\"input\">
    <label style=\"width:44% !important;\">Color</label>
    <div id=\"fabriccolor\">".$fabricData['color']."</div>
    <button id=\"changefabricbutton\" type=\"button\" onclick=\"changefabricmodal()\">Change Fabric/Color</button>
</div>";

echo $this->Form->input('process',['type'=>'hidden','value'=>'insertlineitem']);
echo $this->Form->input('calculator-used',['type'=>'hidden','value'=>'straight-cornice']);

echo $this->Form->input('fabricid',['type'=>'hidden','value'=>$fabricid]);


echo $this->Form->input('start-price-val',['type'=>'hidden','value'=>$thisItemMeta['start-price-val']]);
echo $this->Form->input('add-on-total-val',['type'=>'hidden','value'=>$thisItemMeta['add-on-total-val']]);
echo $this->Form->input('add-on-text-val',['type'=>'hidden','value'=>$thisItemMeta['add-on-text-val']]);


if(isset($thisItemMeta['advancedfieldsvisible']) && $thisItemMeta['advancedfieldsvisible']=='1'){
	echo $this->Form->input('advancedfieldsvisible',['type'=>"hidden","value"=>'1']);
}else{
	echo $this->Form->input('advancedfieldsvisible',['type'=>"hidden","value"=>'0']);
}


if(isset($thisItemMeta['cornice-type']) && strlen(trim($thisItemMeta['cornice-type'])) >0){
	$cornicetypeval=$thisItemMeta['cornice-type'];
}else{
	$cornicetypeval='Straight';
}
echo "<div class=\"input selectbox\">
<label for=\"cornice-type\">Cornice Type</label>";
echo $this->Form->select('cornice-type',[
	'Straight'=>'Straight',
	'Shaped Style A' => 'Shaped Style A',
	'Shaped Style B' => 'Shaped Style B',
	'Shaped Style C' => 'Shaped Style C',
	'Shaped Style D' => 'Shaped Style D'
	],['id'=>'cornice-type','value'=>$cornicetypeval]);
echo "</div>";



if(isset($thisLineItem['room_number']) && strlen(trim($thisLineItem['room_number'])) >0){
	$locationValue=$thisLineItem['room_number'];
}else{
	$locationValue='';
}

echo $this->Form->input('location',['label'=>'Location','value'=>$locationValue]);




if(isset($thisItemMeta['com-fabric']) && $thisItemMeta['com-fabric']=='1'){
	$comChecked=true;
}else{
	$comChecked=false;
}
echo $this->Form->input('com-fabric',['type'=>'checkbox','label'=>'COM Fabric','checked'=>$comChecked]);







if(isset($thisItemMeta['qty']) && is_numeric($thisItemMeta['qty'])){
	$qtyval=$thisItemMeta['qty'];
}else{
	$qtyval=1;
}
echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','min'=>0,'value'=>$qtyval,'autocomplete'=>'off']);









if(isset($thisItemMeta['railroaded']) && $thisItemMeta['railroaded']=='1'){
	$railroadedChecked=true;
}else{
	if($fabricData['railroaded']=='1'){
		$railroadedChecked=true;
	}else{
		$railroadedChecked=false;
	}
}
echo $this->Form->input('railroaded',['type'=>'checkbox','label'=>'Railroaded','checked'=>$railroadedChecked]);







echo "<fieldset class=\"fieldsection\"><legend>DIMENSIONS</legend>";

if(isset($thisItemMeta['face']) && is_numeric($thisItemMeta['face'])){
	$faceval=$thisItemMeta['face'];
}else{
	$faceval='0';
}
echo $this->Form->input('face',['label'=>'Face','type'=>'number','min'=>0,'step'=>'any','value'=>$faceval]);




if(isset($thisItemMeta['hinged']) && $thisItemMeta['hinged']=='1'){
	$hingedChecked=true;
	$hingecountdisplay='block';
}else{
	$hingedChecked=false;
	$hingecountdisplay='none';
}
echo $this->Form->input('hinged',['type'=>'checkbox','label'=>'Hinged?','checked'=>$hingedChecked]);




echo "<div id=\"hingecountwrap\" class=\"suboptions\" style=\"display:".$hingecountdisplay.";\">";
if(isset($thisItemMeta['hingecount']) && is_numeric($thisItemMeta['hingecount'])){
	$hingecountval=$thisItemMeta['hingecount'];
}else{
	$hingecountval='1';
}
echo $this->Form->input('hingecount',['label'=>'Hinge Count','type'=>'number','min'=>1,'step'=>1,'value'=>$hingecountval]);
echo "</div>";




echo "<div id=\"flshortwrap\" style=\"";
	if(isset($thisItemMeta['cornice-type']) && substr($thisItemMeta['cornice-type'],0,6) == 'Shaped'){
		//display it
		echo "display:block;";
	}else{
		echo "display:none;";
	}
echo "\">";
if(isset($thisItemMeta['fl-short']) && is_numeric($thisItemMeta['fl-short'])){
	$flshortval=$thisItemMeta['fl-short'];
}else{
	$flshortval='0';
}
echo $this->Form->input('fl-short',['label'=>'Height (Short Point)','type'=>'number','min'=>0,'step'=>'any','value'=>$flshortval]);
echo "</div>";



echo "<div id=\"flmidwrap\" style=\"";
	if(isset($thisItemMeta['cornice-type']) && substr($thisItemMeta['cornice-type'],0,6) == 'Shaped'){
		//display it
		echo "display:block;";
	}else{
		echo "display:none;";
	}
echo "\">";
if(isset($thisItemMeta['fl-mid']) && is_numeric($thisItemMeta['fl-mid'])){
	$flmidval=$thisItemMeta['fl-mid'];
}else{
	$flmidval='0';
}
echo $this->Form->input('fl-mid',['label'=>'Height (Mid Point)','type'=>'number','min'=>0,'step'=>'any','value'=>$flmidval]);
echo "</div>";



if(isset($thisItemMeta['height']) && is_numeric($thisItemMeta['height'])){
	$heightval=$thisItemMeta['height'];
}else{
	$heightval='0';
}
echo $this->Form->input('height',['label'=>'Height (Long Point)','type'=>'number','min'=>0,'step'=>'any','value'=>$heightval]);





if(isset($thisItemMeta['return']) && is_numeric($thisItemMeta['return'])){
	$returnval=$thisItemMeta['return'];
}else{
	$returnval='0';
}
echo $this->Form->input('return',['label'=>'Return','type'=>'number','min'=>0,'step'=>'any','value'=>$returnval]);







if(isset($thisItemMeta['welt-top']) && $thisItemMeta['welt-top']=='1'){
	$welttopChecked=true;
}else{
	$welttopChecked=false;
}
echo $this->Form->input('welt-top',['type'=>'checkbox','label'=>'Welt Top?','checked'=>$welttopChecked]);



if(isset($thisItemMeta['welt-bottom']) && $thisItemMeta['welt-bottom']=='1'){
	$weltbottomChecked=true;
}else{
	$weltbottomChecked=false;
}
echo $this->Form->input('welt-bottom',['type'=>'checkbox','label'=>'Welt Bottom?','checked'=>$weltbottomChecked]);


echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>FABRIC SPECS &amp; PRICING</legend>";

if(isset($thisItemMeta['fab-width']) && is_numeric($thisItemMeta['fab-width'])){
	$fabwidthval=$thisItemMeta['fab-width'];
}else{
	$fabwidthval=$fabricData['fabric_width'];
}
echo $this->Form->input('fab-width',['label'=>'Fab Width','type'=>'number','min'=>0,'step'=>'any','value'=>$fabwidthval]);






echo "<div id=\"verticalrepeatwrap\"";
if($thisItemMeta['railroaded'] == '1' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '1')){
	echo " style=\"display:none;\"";
}
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
echo $this->Form->input('vertical-repeat',['type'=>'number','min'=>'0','step'=>'any','value'=>$verticalrepeatval,'label'=>'Vertical Repeat']);
echo "</div>";




if(isset($thisItemMeta['fabric-cost-per-yard']) && strlen(trim($thisItemMeta['fabric-cost-per-yard'])) >0){
	$premarkupfabcostvals=array('value'=>$thisItemMeta['fabric-cost-per-yard']);
}else{
	$premarkupfabcostvals=array('value'=>number_format($fabricData['cost_per_yard_cut'],2,'.',','));
}



if($fabricid=="custom"){
	echo $this->Form->input('custom-fabric-cost-per-yard',['type'=>'number','step'=>'any']);	
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
echo "<div><input type=\"number\" min=\"0\" step=\"any\" name=\"inbound-freight-custom-value\" id=\"inbound-freight-custom-value\" placeholder=\"Override\" value=\"".$ibfrtcustomval."\" /></div></div></div>";





if(isset($thisItemMeta['fabric-markup']) && strlen(trim($thisItemMeta['fabric-markup'])) > 0){
	$markupval=$thisItemMeta['fabric-markup'];
}else{
	$markupval=$settings['bedspread_markup_default'];
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


if(isset($thisItemMeta['fabric-markup-custom-value']) && strlen(trim($thisItemMeta['fabric-markup-custom-value'])) > 0){
    $markupcustomval=$thisItemMeta['fabric-markup-custom-value'];
}else{
    $markupcustomval='';
}

echo "<div><input type=\"number\" name=\"fabric-markup-custom-value\" step=\"any\" min=\"0\" id=\"fabric-markup-custom-value\" value=\"".$markupcustomval."\" placeholder=\"Override\" /></div>";
echo "</div></div>";

echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>LINING SPECS &amp; PRICING</legend>";
echo "<div class=\"input selectbox\">";
echo "<label>Lining</label>";

echo "<select name=\"linings_id\" id=\"linings_id\"><option value=\"\" data-price=\"0.00\">--Select Lining--</option>";
foreach($linings as $lining){
	echo "<option value=\"".$lining['id']."\" data-price=\"".number_format($lining['price'],2,'.',',')."\"";
	if($isEdit && $thisItemMeta['linings_id'] == $lining['id']){
		echo " selected=\"selected\"";
	}elseif(!$isEdit && $lining['id'] == 12){
	    echo " selected=\"selected\"";
	}
	echo ">".$lining['short_title']."</option>";
}
echo "</select>";

echo "</div>";



if(isset($thisItemMeta['lining-price-per-yd']) && is_numeric($thisItemMeta['lining-price-per-yd'])){
	$liningpriceperydval=$thisItemMeta['lining-price-per-yd'];
}else{
	$liningpriceperydval='0.00';
}
echo $this->Form->input('lining-price-per-yd',['type'=>'number','min'=>'0','step'=>'any','value'=>$liningpriceperydval,'label'=>'Lining Price per yd']);



if(isset($thisItemMeta['lining-width']) && is_numeric($thisItemMeta['lining-width'])){
	$liningwidthval=$thisItemMeta['lining-width'];
}else{
	$liningwidthval='54';
}
echo $this->Form->input('lining-width',['label'=>'Lining Width','value'=>$liningwidthval]);


echo "</fieldset>";


echo "<fieldset class=\"fieldsection\"><legend>LABOR</legend>";

if(isset($thisItemMeta['base-labor-cost']) && is_numeric($thisItemMeta['base-labor-cost'])){
	$baselaborcostval=$thisItemMeta['base-labor-cost'];
}else{
	$baselaborcostval=$settings['straight_cornice_base_labor_cost'];
}
echo $this->Form->input('base-labor-cost',['type'=>'number','min'=>'0','step'=>'any','value'=>$baselaborcostval,'label'=>'Base Labor Cost']);


if(intval($quoteID) == 0){
	echo $this->Form->input('custom-base-labor',['value'=> '','label'=>'Labor Override']);
}

echo "</fieldset>";






echo "<fieldset class=\"fieldsection\"><legend>HARDWARE</legend>";


if((isset($thisItemMeta['brackets']) && $thisItemMeta['brackets']=='1') || !isset($thisItemMeta['brackets'])){
	$bracketsChecked=true;
	$bracketwrapdisplay='block';
}else{
	$bracketsChecked=false;
	$bracketwrapdisplay='none';
}
echo $this->Form->input('brackets',['type'=>'checkbox','label'=>'Brackets?','checked'=>$bracketsChecked]);

$bracketsizes=explode('|',$settings['angle_iron_bracket_sizes']);

echo "<div id=\"bracketdetailwrap\" class=\"suboptions\" style=\"display:".$bracketwrapdisplay.";\">";
	echo "<div class=\"input selectbox\">";
	echo $this->Form->label('Size');

	if(isset($thisItemMeta['bracket-size']) && strlen(trim($thisItemMeta['bracket-size'])) >0){
		$bracketSizeValue=$thisItemMeta['bracket-size'];
	}else{
		$bracketSizeValue='';
	}

	echo $this->Form->select('bracket-size',$bracketsizes,['empty'=>'Select Size','id'=>'bracket-size','value'=>$bracketSizeValue]);
	echo "</div>";
	
	if(isset($thisItemMeta['bracket-count']) && strlen(trim($thisItemMeta['bracket-count'])) >0){
		$bracketCountValue=$thisItemMeta['bracket-count'];
	}else{
		$bracketCountValue=0;
	}

	echo $this->Form->input('bracket-count',['type'=>'number','value'=>$bracketCountValue,'min'=>'0']);
echo "</div>";

echo "</fieldset>";




echo "<fieldset class=\"fieldsection\"><legend>FABRIC ROUNDING</legend>";




if(isset($thisItemMeta['disable-welt-rescue']) && $thisItemMeta['disable-welt-rescue']=='1'){
	$disableweltrescueChecked=true;
}else{
	$disableweltrescueChecked=false;
}
echo $this->Form->input('disable-welt-rescue',['type'=>'checkbox','label'=>'Disable Welt Rescue?','checked'=>$disableweltrescueChecked]);






if(isset($thisItemMeta['force-full-fabric-widths']) && $thisItemMeta['force-full-fabric-widths']=='1'){
	$forcefullfabricwidthsChecked=true;
}else{
	$forcefullfabricwidthsChecked=false;
}
echo $this->Form->input('force-full-fabric-widths',['type'=>'checkbox','label'=>'Force full widths ea Cornice','checked'=>$forcefullfabricwidthsChecked]);






if(isset($thisItemMeta['force-round-total-widths-eol']) && $thisItemMeta['force-round-total-widths-eol']=='1'){
	$forceroundtotalwidthseolChecked=true;
}else{
	$forceroundtotalwidthseolChecked=false;
}
echo $this->Form->input('force-round-total-widths-eol',['type'=>'checkbox','label'=>'Force full widths EOL','checked'=>$forceroundtotalwidthseolChecked]);




if(isset($thisItemMeta['force-round-yds-ea']) && $thisItemMeta['force-round-yds-ea']=='1'){
	$forceroundydseaChecked=true;
}else{
	$forceroundydseaChecked=false;
}
echo $this->Form->input('force-round-yds-ea',['type'=>'checkbox','label'=>'Force Round yds ea Cornice','checked'=>$forceroundydseaChecked]);







if(isset($thisItemMeta['force-round-distributed-yds']) && $thisItemMeta['force-round-distributed-yds']=='1'){
	$forcerounddistributedydsChecked=true;
}else{
	$forcerounddistributedydsChecked=false;
}
echo $this->Form->input('force-round-distributed-yds',['type'=>'checkbox','label'=>'Force Rounded Distributed yds EOL','checked'=>$forcerounddistributedydsChecked]);





echo "</fieldset>";


echo "<fieldset class=\"fieldsection\"><legend>LINING ROUNDING</legend>";

if(isset($thisItemMeta['force-full-lining-widths']) && $thisItemMeta['force-full-lining-widths']=='1'){
	$forcefullliningwidthsChecked=true;
}else{
	$forcefullliningwidthsChecked=false;
}
echo $this->Form->input('force-full-lining-widths',['type'=>'checkbox','label'=>'Force Full Lining Widths?','checked'=>$forcefullliningwidthsChecked]);

echo "</fieldset>";





if(isset($thisItemMeta['tolerance-in-widths']) && is_numeric($thisItemMeta['tolerance-in-widths'])){
	$toleranceinwidthsval=$thisItemMeta['tolerance-in-widths'];
}else{
	$toleranceinwidthsval='0';
}
echo $this->Form->input('tolerance-in-widths',['label'=>false,'type'=>'hidden','value'=>$toleranceinwidthsval]);


if(isset($thisItemMeta['tolerance-in-yds']) && is_numeric($thisItemMeta['tolerance-in-yds'])){
	$toleranceinydsval=$thisItemMeta['tolerance-in-yds'];
}else{
	$toleranceinydsval='0.05';
}
echo $this->Form->input('tolerance-in-yds',['label'=>false,'type'=>'hidden','value'=>$toleranceinydsval,'step'=>'any']);



echo "<button type=\"button\" id=\"goadvanced\">Advanced</button>";


//expand/colapse begin
echo "<div id=\"expandcollapse\" style=\"display:";
if(isset($thisItemMeta['advancedfieldsvisible']) && $thisItemMeta['advancedfieldsvisible']=='1'){
	echo " block";
}else{
	echo "none";
}
echo ";\">";


if(isset($thisItemMeta['fab-tolerance']) && is_numeric($thisItemMeta['fab-tolerance'])){
	$fabtoleranceval=$thisItemMeta['fab-tolerance'];
}else{
	$fabtoleranceval='0.9';
}
echo $this->Form->input('fab-tolerance',['label'=>false,'type'=>'hidden','value'=>$fabtoleranceval,'step'=>'any']);






if(isset($thisItemMeta['individual-nailheads']) && $thisItemMeta['individual-nailheads']=='1'){
	$individualNailheadsChecked=true;
	$indivNailheadsWrapDisplay='block';
}else{
	$individualNailheadsChecked=false;
	$indivNailheadsWrapDisplay='none';
}
echo $this->Form->input('individual-nailheads',['type'=>'checkbox','label'=>'Individual Nailheads?','checked'=>$individualNailheadsChecked]);

echo "<div class=\"suboptions\" id=\"individualNailheadsWrap\" style=\"display:".$indivNailheadsWrapDisplay.";\">";

echo $this->Form->input('nailhead-boxes-per-line',['type'=>'number','label'=>'Boxes per Line','value'=>'0','min'=>'0','step'=>'any']);

echo $this->Form->input('nailhead-dollars-per-box',['type'=>'number','label'=>'Price per Box','value'=>'0','min'=>'0','step'=>'any']);

echo "</div>";



if(isset($thisItemMeta['nailhead-trim']) && $thisItemMeta['nailhead-trim']=='1'){
	$nailheadTrimChecked=true;
	$nailheadTrimWrapDisplay='block';
}else{
	$nailheadTrimChecked=false;
	$nailheadTrimWrapDisplay='none';
}
echo $this->Form->input('nailhead-trim',['type'=>'checkbox','label'=>'Nailhead Trim?','checked'=>$nailheadTrimChecked]);

echo "<div class=\"suboptions\" id=\"nailheadTrimWrap\" style=\"display:".$nailheadTrimWrapDisplay.";\">";

echo $this->Form->input('nailhead-trim-rolls-per-line',['type'=>'number','label'=>'Rolls per Line','value'=>'0','min'=>'0','step'=>'any']);

echo $this->Form->input('nailhead-trim-dollars-per-roll',['type'=>'number','label'=>'Price per Roll','value'=>'0','min'=>'0','step'=>'any']);

echo "</div>";





if(isset($thisItemMeta['covered-buttons']) && $thisItemMeta['covered-buttons']=='1'){
	$coveredbuttonsChecked=true;
	$coveredbuttonscountdisplay='block';
}else{
	$coveredbuttonsChecked=false;
	$coveredbuttonscountdisplay='none';
}
echo $this->Form->input('covered-buttons',['type'=>'checkbox','label'=>'Covered Buttons?','checked'=>$coveredbuttonsChecked]);

echo "<div id=\"coveredbuttonscountwrap\" class=\"suboptions\" style=\"display:".$coveredbuttonscountdisplay.";\">";
if(isset($thisItemMeta['covered-buttons-count']) && strlen(trim($thisItemMeta['covered-buttons-count'])) >0){
	$coveredButtonsCountVal=$thisItemMeta['covered-buttons-count'];
}else{
	$coveredButtonsCountVal='0';
}
echo $this->Form->input('covered-buttons-count',['type'=>'number','step'=>1,'min'=>'0','label'=>'Covered Button Count','value'=>$coveredButtonsCountVal]);
echo "</div>";




if(isset($thisItemMeta['horizontal-straight-banding']) && $thisItemMeta['horizontal-straight-banding']=='1'){
	$horizontalStraightBandingChecked=true;
	$horizontalStraightBandingcountdisplay='block';
}else{
	$horizontalStraightBandingChecked=false;
	$horizontalStraightBandingcountdisplay='none';
}
echo $this->Form->input('horizontal-straight-banding',['type'=>'checkbox','label'=>'Horiz Straight Banding?','checked'=>$horizontalStraightBandingChecked]);

echo "<div id=\"horizontalstraightbandingcountwrap\" class=\"suboptions\" style=\"display:".$horizontalStraightBandingcountdisplay.";\">";
if(isset($thisItemMeta['horizontal-straight-banding-count']) && strlen(trim($thisItemMeta['horizontal-straight-banding-count'])) >0){
	$horizontalStraightBandingCountVal=$thisItemMeta['horizontal-straight-banding-count'];
}else{
	$horizontalStraightBandingCountVal='0';
}
echo $this->Form->input('horizontal-straight-banding-count',['type'=>'number','min'=>'0','step'=>1,'label'=>'Count','value'=>$horizontalStraightBandingCountVal]);


if(isset($thisItemMeta['horizontal-straight-banding-yards-per-cornice']) && strlen(trim($thisItemMeta['horizontal-straight-banding-yards-per-cornice'])) >0){
	$horizontalStraightBandingYardsPerCorniceVal=$thisItemMeta['horizontal-straight-banding-yards-per-cornice'];
}else{
	$horizontalStraightBandingYardsPerCorniceVal='0';
}
echo $this->Form->input('horizontal-straight-banding-yards-per-cornice',['type'=>'number','min'=>0,'step'=>'any','label'=>'Yards per cornice','value'=>$horizontalStraightBandingYardsPerCorniceVal]);


if(isset($thisItemMeta['horizontal-straight-banding-price-per-yard']) && strlen(trim($thisItemMeta['horizontal-straight-banding-price-per-yard'])) >0){
	$horizontalStraightBandingPricePerYardVal=$thisItemMeta['horizontal-straight-banding-price-per-yard'];
}else{
	$horizontalStraightBandingPricePerYardVal='0.00';
}
echo $this->Form->input('horizontal-straight-banding-price-per-yard',['type'=>'number','min'=>0,'step'=>'any','label'=>'Price per yard','value'=>$horizontalStraightBandingPricePerYardVal]);
echo "</div>";




if(isset($thisItemMeta['horizontal-shaped-banding']) && $thisItemMeta['horizontal-shaped-banding']=='1'){
	$horizontalshapedBandingChecked=true;
	$horizontalshapedBandingcountdisplay='block';
}else{
	$horizontalshapedBandingChecked=false;
	$horizontalshapedBandingcountdisplay='none';
}
echo $this->Form->input('horizontal-shaped-banding',['type'=>'checkbox','label'=>'Horiz Shaped Banding?','checked'=>$horizontalshapedBandingChecked]);

echo "<div id=\"horizontalshapedbandingcountwrap\" class=\"suboptions\" style=\"display:".$horizontalshapedBandingcountdisplay.";\">";
if(isset($thisItemMeta['horizontal-shaped-banding-count']) && strlen(trim($thisItemMeta['horizontal-shaped-banding-count'])) >0){
	$horizontalshapedBandingCountVal=$thisItemMeta['horizontal-shaped-banding-count'];
}else{
	$horizontalshapedBandingCountVal='0';
}
echo $this->Form->input('horizontal-shaped-banding-count',['type'=>'number','min'=>'0','step'=>1,'label'=>'Count','value'=>$horizontalshapedBandingCountVal]);


if(isset($thisItemMeta['horizontal-shaped-banding-yards-per-cornice']) && strlen(trim($thisItemMeta['horizontal-shaped-banding-yards-per-cornice'])) >0){
	$horizontalShapedBandingYardsPerCorniceVal=$thisItemMeta['horizontal-shaped-banding-yards-per-cornice'];
}else{
	$horizontalShapedBandingYardsPerCorniceVal='0';
}
echo $this->Form->input('horizontal-shaped-banding-yards-per-cornice',['type'=>'number','min'=>0,'step'=>'any','label'=>'Yards per cornice','value'=>$horizontalShapedBandingYardsPerCorniceVal]);


if(isset($thisItemMeta['horizontal-shaped-banding-price-per-yard']) && strlen(trim($thisItemMeta['horizontal-shaped-banding-price-per-yard'])) >0){
	$horizontalShapedBandingPricePerYardVal=$thisItemMeta['horizontal-shaped-banding-price-per-yard'];
}else{
	$horizontalShapedBandingPricePerYardVal='0.00';
}
echo $this->Form->input('horizontal-shaped-banding-price-per-yard',['type'=>'number','min'=>0,'step'=>'any','label'=>'Price per yard','value'=>$horizontalShapedBandingPricePerYardVal]);

echo "</div>";






if(isset($thisItemMeta['extra-welts']) && $thisItemMeta['extra-welts']=='1'){
	$extraweltsChecked=true;
	$extraweltscountdisplay='block';
}else{
	$extraweltsChecked=false;
	$extraweltscountdisplay='none';
}
echo $this->Form->input('extra-welts',['type'=>'checkbox','label'=>'Extra Welts?','checked'=>$extraweltsChecked]);

echo "<div class=\"suboptions\" id=\"extraweltscountwrap\" style=\"display:".$extraweltscountdisplay.";\">";
if(isset($thisItemMeta['extra-welts-count']) && strlen(trim($thisItemMeta['extra-welts-count'])) >0){
	$extraweltsCountVal=$thisItemMeta['extra-welts-count'];
}else{
	$extraweltsCountVal='0';
}
echo $this->Form->input('extra-welts-count',['type'=>'number','step'=>1,'min'=>'0','label'=>'Count','value'=>$extraweltsCountVal]);



if(isset($thisItemMeta['extra-welt-yards-per-cornice']) && strlen(trim($thisItemMeta['extra-welt-yards-per-cornice'])) >0){
	$extraWeltYardsPerCorniceVal=$thisItemMeta['extra-welt-yards-per-cornice'];
}else{
	$extraWeltYardsPerCorniceVal=0;
}
echo $this->Form->input('extra-welt-yards-per-cornice',['type'=>'number','step'=>'any','min'=>0,'label'=>'Yards per cornice','value'=>$extraWeltYardsPerCorniceVal]);


if(isset($thisItemMeta['extra-welt-price-per-yard']) && strlen(trim($thisItemMeta['extra-welt-price-per-yard'])) >0){
	$extraWeltPricePerYardVal=$thisItemMeta['extra-welt-price-per-yard'];
}else{
	$extraWeltPricePerYardVal=0;
}
echo $this->Form->input('extra-welt-price-per-yard',['type'=>'number','step'=>'any','min'=>0,'label'=>'Price per yard','value'=>$extraWeltPricePerYardVal]);


echo "</div>";






if(isset($thisItemMeta['trim-sewn-on']) && $thisItemMeta['trim-sewn-on']=='1'){
	$trimsewnonChecked=true;
	$trimlfdisplay='block';
}else{
	$trimsewnonChecked=false;
	$trimlfdisplay='none';
}
echo $this->Form->input('trim-sewn-on',['type'=>'checkbox','label'=>'Sewn-On Trim?','checked'=>$trimsewnonChecked]);


echo "<div id=\"trimlfwrap\" class=\"suboptions\" style=\"display:".$trimlfdisplay.";\">";
if(isset($thisItemMeta['trim-lf']) && strlen(trim($thisItemMeta['trim-lf'])) >0){
	$trimLFVal=$thisItemMeta['trim-lf'];
}else{
	$trimLFVal='0';
}
echo $this->Form->input('trim-lf',['type'=>'number','step'=>1,'min'=>'0','label'=>'Trim LF','value'=>$trimLFVal]);


if(isset($thisItemMeta['trim-yards-per-cornice']) && strlen(trim($thisItemMeta['trim-yards-per-cornice'])) >0){
	$trimYardsEachVal=$thisItemMeta['trim-yards-per-cornice'];
}else{
	$trimYardsEachVal=0;
}
echo $this->Form->input('trim-yards-per-cornice',['type'=>'number','min'=>0,'step'=>'any','label'=>'Yards per cornice','value'=>$trimYardsEachVal]);


if(isset($thisItemMeta['trim-price-per-yard']) && strlen(trim($thisItemMeta['trim-price-per-yard'])) >0){
	$trimPricePerYardVal=$thisItemMeta['trim-price-per-yard'];
}else{
	$trimPricePerYardVal='0.00';
}
echo $this->Form->input('trim-price-per-yard',['type'=>'number','min'=>0,'step'=>'any','label'=>'Price per yard','value'=>$trimPricePerYardVal]);
echo "</div>";




/*

if(isset($thisItemMeta['rings']) && $thisItemMeta['rings']=='1'){
	$ringsChecked=true;
	$ringscountdisplay='block';
}else{
	$ringsChecked=false;
	$ringscountdisplay='none';
}
echo $this->Form->input('rings',['type'=>'checkbox','label'=>'Sewn-On Rings?','checked'=>$ringsChecked]);


echo "<div id=\"ringscountwrap\" class=\"suboptions\" style=\"display:".$ringscountdisplay.";\">";
if(isset($thisItemMeta['rings-count']) && strlen(trim($thisItemMeta['rings-count'])) >0){
	$ringsCountVal=$thisItemMeta['rings-count'];
}else{
	$ringsCountVal='0';
}
echo $this->Form->input('rings-count',['type'=>'number','step'=>1,'min'=>'0','label'=>'Ring Count','value'=>$ringsCountVal]);
echo "</div>";

*/


if(isset($thisItemMeta['tassels']) && $thisItemMeta['tassels']=='1'){
	$tasselsChecked=true;
	$tasselscountdisplay='block';
}else{
	$tasselsChecked=false;
	$tasselscountdisplay='none';
}
echo $this->Form->input('tassels',['type'=>'checkbox','label'=>'Sewn-On Tassels?','checked'=>$tasselsChecked]);


echo "<div id=\"tasselscountwrap\" class=\"suboptions\" style=\"display:".$tasselscountdisplay.";\">";
if(isset($thisItemMeta['tassels-count']) && strlen(trim($thisItemMeta['tassels-count'])) >0){
	$tasselsCountVal=$thisItemMeta['tassels-count'];
}else{
	$tasselsCountVal='0';
}
echo $this->Form->input('tassels-count',['type'=>'number','step'=>1,'min'=>'0','label'=>'Tassel Count','value'=>$tasselsCountVal]);


if(isset($thisItemMeta['tassels-price-each']) && strlen(trim($thisItemMeta['tassels-price-each'])) >0){
	$tasselsPriceEachVal=$thisItemMeta['tassels-price-each'];
}else{
	$tasselsPriceEachVal='0.00';
}
echo $this->Form->input('tassels-price-each',['type'=>'number','min'=>0,'step'=>'any','label'=>'Price per tassel','value'=>$tasselsPriceEachVal]);
echo "</div>";






if(isset($thisItemMeta['drill-holes']) && $thisItemMeta['drill-holes']=='1'){
	$drillholesChecked=true;
	$drillholescountdisplay='block';
}else{
	$drillholesChecked=false;
	$drillholescountdisplay='none';
}
echo $this->Form->input('drill-holes',['type'=>'checkbox','label'=>'Drill Holes?','checked'=>$drillholesChecked]);


echo "<div id=\"drillholescountwrap\" class=\"suboptions\" style=\"display:".$drillholescountdisplay.";\">";
if(isset($thisItemMeta['drill-hole-count']) && strlen(trim($thisItemMeta['drill-hole-count'])) >0){
	$drillholeCountVal=$thisItemMeta['drill-hole-count'];
}else{
	$drillholeCountVal='0';
}
echo $this->Form->input('drill-hole-count',['type'=>'number','step'=>1,'min'=>'0','label'=>'Count','value'=>$drillholeCountVal]);
echo "</div>";








if(isset($thisItemMeta['wraparound-inches']) && is_numeric($thisItemMeta['wraparound-inches'])){
	$wraparoundinchesval=$thisItemMeta['wraparound-inches'];
}else{
	$wraparoundinchesval=$settings['default_cornice_wraparound_inches'];
}
echo $this->Form->input('wraparound-inches',['label'=>'Wraparound Inches','type'=>'number','min'=>0,'value'=>$wraparoundinchesval]);






if(isset($thisItemMeta['inches-per-welt']) && is_numeric($thisItemMeta['inches-per-welt'])){
	$inchesperweltval=$thisItemMeta['inches-per-welt'];
}else{
	$inchesperweltval=$settings['cornice_inches_per_welt'];
}
echo $this->Form->input('inches-per-welt',['label'=>'Inches per Welt','value'=>$inchesperweltval]);



if(isset($thisItemMeta['extra-inches-height']) && is_numeric($thisItemMeta['extra-inches-height'])){
	$extrainchesheightval=$thisItemMeta['extra-inches-height'];
}else{
	$extrainchesheightval=$settings['cornice_extra_inches_height'];
}
echo $this->Form->input('extra-inches-height',['label'=>'Extra Inches Height','value'=>$extrainchesheightval]);



echo "</div>";
//expand/collapse end


echo "</div>";
?>

<div id="resultsblock">
<h2>Calculator Results</h2>
<div id="cannotcalculate">Cannot calculate. Missing information.</div>

<?php
	
	
if(isset($thisItemMeta['face-retret-wraparound']) && strlen(trim($thisItemMeta['face-retret-wraparound'])) > 0){
	$faceretretwraparoundval=$thisItemMeta['face-retret-wraparound'];
}else{
	$faceretretwraparoundval='';
}	
echo $this->Form->input('face-retret-wraparound',['label'=>'CORN_X','readonly'=>true,'value'=>$faceretretwraparoundval]);
	
	

echo "<div id=\"rawfabwwrap\"";
if($thisItemMeta['railroaded'] == '1' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '1')){
	echo " style=\"display:none;\"";
}
echo ">";
if(isset($thisItemMeta['raw-fabric-widths']) && strlen(trim($thisItemMeta['raw-fabric-widths'])) > 0){
	$rawfabricwidthsval=$thisItemMeta['raw-fabric-widths'];
}else{
	$rawfabricwidthsval='';
}	
echo $this->Form->input('raw-fabric-widths',['label'=>'Raw Fabric Widths','readonly'=>true,'value'=>$rawfabricwidthsval]);
echo "</div>";
	
	
	
	
	
echo "<div id=\"fabrichalfwidthsstatuswrap\"";
if($thisItemMeta['railroaded'] == '1' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '1')){
	echo " style=\"display:none;\"";
}
echo ">";	
if(isset($thisItemMeta['fabric-half-widths-status']) && strlen(trim($thisItemMeta['fabric-half-widths-status'])) > 0){
	$fabrichalfwidthsstatusval=$thisItemMeta['fabric-half-widths-status'];
}else{
	$fabrichalfwidthsstatusval='';
}	
echo $this->Form->input('fabric-half-widths-status',['label'=>'Fabric Half Widths Status','readonly'=>true,'value'=>$fabrichalfwidthsstatusval]);
echo "</div>";






	
echo "<div id=\"feasiblecorniceswrap\"";
if($thisItemMeta['railroaded'] == '0' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '0')){
	echo " style=\"display:none;\"";
}
echo ">";
if(isset($thisItemMeta['cornices-per-cw-fabric']) && strlen(trim($thisItemMeta['cornices-per-cw-fabric'])) > 0){
	$cornicespercwfabricval=$thisItemMeta['cornices-per-cw-fabric'];
}else{
	$cornicespercwfabricval='';
}
echo $this->Form->input('cornices-per-cw-fabric',['label'=>'Feasible Cornices Per CW of Fabric','readonly'=>true,'value'=>$cornicespercwfabricval]);
echo "</div>";




	
echo "<div id=\"realfabricwidthsneededwrap\"";
if($thisItemMeta['railroaded'] == '0' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '0')){
	echo " style=\"display:none;\"";
}
echo ">";
if(isset($thisItemMeta['real-fabric-widths-needed']) && strlen(trim($thisItemMeta['real-fabric-widths-needed'])) > 0){
	$realfabricwidthsneededval=$thisItemMeta['real-fabric-widths-needed'];
}else{
	$realfabricwidthsneededval='';
}
echo $this->Form->input('real-fabric-widths-needed',['label'=>'Actual Fabric Widths Needed','readonly'=>true,'value'=>$realfabricwidthsneededval]);
echo "</div>";



	
	
if(isset($thisItemMeta['cornice-widths-fabric']) && strlen(trim($thisItemMeta['cornice-widths-fabric'])) > 0){
	$cornicewidthsfabricval=$thisItemMeta['cornice-widths-fabric'];
}else{
	$cornicewidthsfabricval='';
}	
echo $this->Form->input('cornice-widths-fabric',['label'=>'CORNICE WIDTHS FABRIC','readonly'=>true,'value'=>$cornicewidthsfabricval]);
	
	
	
if(isset($thisItemMeta['total-fabric-widths']) && strlen(trim($thisItemMeta['total-fabric-widths'])) > 0){
	$totalfabricwidthsval=$thisItemMeta['total-fabric-widths'];
}else{
	$totalfabricwidthsval='';
}	
echo $this->Form->input('total-fabric-widths',['label'=>'Total Fabric Widths','readonly'=>true,'value'=>$totalfabricwidthsval]);
	
	
	
if(isset($thisItemMeta['halfwidths-recommendation']) && strlen(trim($thisItemMeta['halfwidths-recommendation'])) > 0){
	$halfwidthsrecommendationval=$thisItemMeta['halfwidths-recommendation'];
}else{
	$halfwidthsrecommendationval='';
}	
echo $this->Form->input('halfwidths-recommendation',['label'=>'Half-widths Recommendation','readonly'=>true,'value'=>$halfwidthsrecommendationval]);
	
	
	
	
if(isset($thisItemMeta['lining-halfwidths-status']) && strlen(trim($thisItemMeta['lining-halfwidths-status'])) > 0){
	$lininghalfwidthsstatusval=$thisItemMeta['lining-halfwidths-status'];
}else{
	$lininghalfwidthsstatusval='';
}
echo $this->Form->input('lining-halfwidths-status',['label'=>'Lining Half Widths Status','readonly'=>true,'value'=>$lininghalfwidthsstatusval]);
	
	
	
	
	
echo "<div id=\"rawlinwwrap\"";
if($thisItemMeta['railroaded'] == '1' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '1')){
	echo " style=\"display:none;\"";
}
echo ">";	
if(isset($thisItemMeta['raw-lining-widths']) && strlen(trim($thisItemMeta['raw-lining-widths'])) > 0){
	$rawliningwidthsval=$thisItemMeta['raw-lining-widths'];
}else{
	$rawliningwidthsval='';
}	
echo $this->Form->input('raw-lining-widths',['label'=>'Raw Lining Widths','readonly'=>true,'value'=>$rawliningwidthsval]);
echo "</div>";
	
	
	
if(isset($thisItemMeta['cornice-widths-lining']) && strlen(trim($thisItemMeta['cornice-widths-lining'])) > 0){
	$cornicewidthsliningval=$thisItemMeta['cornice-widths-lining'];
}else{
	$cornicewidthsliningval='';
}	
echo $this->Form->input('cornice-widths-lining',['label'=>'CORNICE WIDTHS LINING','readonly'=>true,'value'=>$cornicewidthsliningval]);
	
	
	
if(isset($thisItemMeta['welt-height']) && strlen(trim($thisItemMeta['welt-height'])) > 0){
	$weltheightval=$thisItemMeta['welt-height'];
}else{
	$weltheightval='';
}	
echo $this->Form->input('welt-height',['label'=>'Welt Height','readonly'=>true,'value'=>$weltheightval]);
	
	
	
if(isset($thisItemMeta['welt-yds']) && strlen(trim($thisItemMeta['welt-yds'])) > 0){
	$weltydsval=$thisItemMeta['welt-yds'];
}else{
	$weltydsval='';
}
echo $this->Form->input('welt-yds',['label'=>'Welt Yds','readonly'=>true,'value'=>$weltydsval]);
	
	
	
	
echo "<div id=\"fabricwastefromvrptwrap\"";
if($thisItemMeta['railroaded'] == '1' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '1')){
	echo " style=\"display:none;\"";
}
echo ">";
if(isset($thisItemMeta['fabric-waste-from-vrpt']) && strlen(trim($thisItemMeta['fabric-waste-from-vrpt'])) > 0){
	$fabricwastefromvrptval=$thisItemMeta['fabric-waste-from-vrpt'];
}else{
	$fabricwastefromvrptval='';
}	
echo $this->Form->input('fabric-waste-from-vrpt',['label'=>'Fabric Waste From V Rpt','readonly'=>true,'value'=>$fabricwastefromvrptval]);
echo "</div>";
	

	
	
if(isset($thisItemMeta['fabric-usage-for-welts']) && strlen(trim($thisItemMeta['fabric-usage-for-welts'])) > 0){
	$fabricusageforweltsval=$thisItemMeta['fabric-usage-for-welts'];
}else{
	$fabricusageforweltsval='';
}
echo $this->Form->textarea('fabric-usage-for-welts',['id'=>'fabric-usage-for-welts','label'=>'Fabric Usage for Welts','readonly'=>true,'value'=>$fabricusageforweltsval]);
	
	
	
if(isset($thisItemMeta['fabric-cl']) && strlen(trim($thisItemMeta['fabric-cl'])) > 0){
	$fabricclval=$thisItemMeta['fabric-cl'];
}else{
	$fabricclval='';
}	
echo $this->Form->input('fabric-cl',['label'=>'Fabric CL','readonly'=>true,'value'=>$fabricclval]);
	
	
	
if(isset($thisItemMeta['lining-cl']) && strlen(trim($thisItemMeta['lining-cl'])) > 0){
	$liningclval=$thisItemMeta['lining-cl'];
}else{
	$liningclval='';
}
echo $this->Form->input('lining-cl',['label'=>'Lining CL','readonly'=>true,'value'=>$liningclval]);
	
	



	
if(isset($thisItemMeta['yds-per-unit']) && strlen(trim($thisItemMeta['yds-per-unit'])) > 0){
	$ydsoffabricval=$thisItemMeta['yds-per-unit'];
}else{
	$ydsoffabricval='';
}
echo $this->Form->input('yds-per-unit',['label'=>'Yds of Fabric','readonly'=>true,'value'=>$ydsoffabricval]);
	
	
if(isset($thisItemMeta['total-yds']) && strlen(trim($thisItemMeta['total-yds'])) > 0){
	$totalydsval=$thisItemMeta['total-yds'];
}else{
	$totalydsval='';
}
echo $this->Form->input('total-yds',['label'=>'Total Yards','readonly'=>true,'value'=>$totalydsval]);
	




if(isset($thisItemMeta['yds-of-lining']) && strlen(trim($thisItemMeta['yds-of-lining'])) > 0){
	$ydsofliningval=$thisItemMeta['yds-of-lining'];
}else{
	$ydsofliningval='';
}
echo $this->Form->input('yds-of-lining',['label'=>'Yds of Lining','readonly'=>true,'value'=>$ydsofliningval]);
	
	
	
if(isset($thisItemMeta['fabric-cost']) && strlen(trim($thisItemMeta['fabric-cost'])) > 0){
	$fabriccostval=$thisItemMeta['fabric-cost'];
}else{
	$fabriccostval='';
}
echo $this->Form->input('fabric-cost',['label'=>'Fabric Cost','readonly'=>true,'value'=>$fabriccostval]);
	
	
	
if(isset($thisItemMeta['lining-cost']) && strlen(trim($thisItemMeta['lining-cost'])) > 0){
	$liningcostval=$thisItemMeta['lining-cost'];
}else{
	$liningcostval='';
}
echo $this->Form->input('lining-cost',['label'=>'Lining Cost','readonly'=>true,'value'=>$liningcostval]);
	
	
	

if(isset($thisItemMeta['labor-billable']) && strlen(trim($thisItemMeta['labor-billable'])) > 0){
	$laborbillableval=$thisItemMeta['labor-billable'];
}else{
	$laborbillableval='';
}	
echo $this->Form->input('labor-billable',['label'=>'Labor Billable (LF)','readonly'=>true,'value'=>$laborbillableval]);





if(isset($thisItemMeta['labor-cost']) && strlen(trim($thisItemMeta['labor-cost'])) > 0){
	$laborcostval=$thisItemMeta['labor-cost'];
}else{
	$laborcostval='';
}	
echo $this->Form->input('labor-cost',['label'=>'Labor Cost','readonly'=>true,'value'=>$laborcostval]);


	
	
if(isset($thisItemMeta['cost']) && strlen(trim($thisItemMeta['cost'])) > 0){
	$costval=number_format($thisItemMeta['cost'],2,'.','');
}else{
	$costval='0.00';
}
echo $this->Form->input('cost',['label'=>'Total Cost','readonly'=>true,'type'=>'hidden','step'=>'any','min'=>0,'value'=>$costval]);

	



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
	$priceval='0.00';
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
<?php } ?>
</div>
<div class="clear"></div>
<?php
echo "<div class=\"calculatebutton\">";
echo $this->Form->button('Calculate',['type'=>'button']);
echo "</div>";


if(intval($quoteID) >0){
	echo "<div class=\"addaslineitembutton\">";
	echo "<button type=\"button\" id=\"cancelbutton\">Cancel</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
	if($isEdit){
		echo $this->Form->button('Save Changes',['type'=>'submit']);
	}else{
		echo $this->Form->button('Add To Quote',['type'=>'submit']);
	}
	echo "</div>";
}


echo "<div class=\"clear\"></div>";
echo $this->Form->end();

?>


<div id="warningbox"></div>

<style>
#warningbox{ display:none; position:fixed; padding:5px; bottom:20px; right:20px; width:590px; background:#FFCFBF; color:red; font-weight:bold; border:3px solid red; z-index:6666; font-size:12px; }
</style>

<script>

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



function calculateLabor(){
	var laborValue;
	if($('#cornice-type').val() == 'Straight'){
		if($('#com-fabric').is(':checked')){
			laborValue=<?php echo $settings['cornice_straight_labor_com_per_lf']; ?>;
		}else{
			laborValue=<?php echo $settings['cornice_straight_labor_mom_per_lf']; ?>;
		}
	}else if($('#cornice-type').val() == 'Shaped' || $('#cornice-type').val() == 'Shaped Style A'){
		if($('#com-fabric').is(':checked')){
			laborValue=<?php echo $settings['cornice_shaped_style_a_labor_com_per_lf']; ?>;
		}else{
			laborValue=<?php echo $settings['cornice_shaped_style_a_labor_mom_per_lf']; ?>;
		}
	}else if($('#cornice-type').val() == 'Shaped Style B'){
		if($('#com-fabric').is(':checked')){
			laborValue=<?php echo $settings['cornice_shaped_style_b_labor_com_per_lf']; ?>;
		}else{
			laborValue=<?php echo $settings['cornice_shaped_style_b_labor_mom_per_lf']; ?>;
		}
	}else if($('#cornice-type').val() == 'Shaped Style C'){
		if($('#com-fabric').is(':checked')){
			laborValue=<?php echo $settings['cornice_shaped_style_c_labor_com_per_lf']; ?>;
		}else{
			laborValue=<?php echo $settings['cornice_shaped_style_c_labor_mom_per_lf']; ?>;
		}
	}else if($('#cornice-type').val() == 'Shaped Style D'){
		if($('#com-fabric').is(':checked')){
			laborValue=<?php echo $settings['cornice_shaped_style_d_labor_com_per_lf']; ?>;
		}else{
			laborValue=<?php echo $settings['cornice_shaped_style_d_labor_mom_per_lf']; ?>;
		}
	}

	if($('#welt-top').is(':checked')){
		laborValue=(laborValue+<?php echo $settings['wt_welt_covered_per_lf']; ?>);
		console.log('Labor increased by <?php echo $settings['wt_welt_covered_per_lf']; ?> for Welt Top');
	}

	if($('#welt-bottom').is(':checked')){
		laborValue=(laborValue+<?php echo $settings['wt_welt_covered_per_lf']; ?>);
		console.log('Labor increased by <?php echo $settings['wt_welt_covered_per_lf']; ?> for Welt Bottom');
	}

	$('#base-labor-cost').val(laborValue.toFixed(2));
}



function doCalculation(){
	calculateLabor();
	var qty=parseFloat($('#qty').val());
	var fab_w = parseFloat($('#fab-width').val());	
	console.clear();

	var oh=1.05;

	if($('#railroaded').is(':checked')){
		//railroaded calculation
		
		$('#vertical-repeat').parent().hide();
		//$('#force-full-fabric-widths').parent().hide();
		//$('#force-full-lining-widths').parent().hide();
		//$('#force-round-total-widths-eol').parent().hide();
		$('#disable-welt-rescue').parent().show();
		$('#cornices-per-cw-fabric').show();


		<?php if(intval($quoteID) == 0){ ?>
		if($('#custom-base-labor').val() != ''){
			var bl_plf = parseFloat($('#custom-base-labor').val());
		}else{
			var bl_plf = parseFloat($('#base-labor-cost').val());
		}
		<?php }else{ ?>
			var bl_plf = parseFloat($('#base-labor-cost').val());
		<?php } ?>
		

		if($('#welt-top').is(':checked')){
			var wlt_t=1;
		}else{
			var wlt_t=0;
		}

		
		if($('#welt-bottom').is(':checked')){
			var wlt_b=1;
		}else{
			var wlt_b=0;
		}

		var wlt_s = parseFloat($('#inches-per-welt').val());
		var xtr_h = parseFloat($('#extra-inches-height').val());
		var lin_ppy = parseFloat($('#lining-price-per-yd').val()); 
		var lin_w = parseFloat($('#lining-width').val());
		
		<?php
		if($fabricid=='custom'){
		?>

		if($('#custom-fabric-cost-per-yard').val() != '' && parseFloat($('#custom-fabric-cost-per-yard').val()) >0){
			var fab_ppy = parseFloat($('#custom-fabric-cost-per-yard').val());
		}

		<?php
		}else{
		?>

		//var fab_ppy = parseFloat($('#fab-price-per-yd').val());
		/*PPSA-33 start*/
		if($('#fabric-cost-per-yard-custom-value').val() != '' && parseFloat($('#fabric-cost-per-yard-custom-value').val()) >0){
			var fab_ppy = parseFloat($('#fabric-cost-per-yard-custom-value').val());
		} else /*PPSA-33 end*/
		if($('select[name=fabric-cost-per-yard]').val() == 'cut'){
			var fab_ppy = parseFloat($('#fcpcut').val());
			console.log('fab_ppy=CUT = '+fab_ppy);
		}else if($('select[name=fabric-cost-per-yard]').val() == 'bolt'){
			var fab_ppy = parseFloat($('#fcpbolt').val());
			console.log('fab_ppy=BOLT = '+fab_ppy);
		}else if($('select[name=fabric-cost-per-yard]').val() == 'case'){
			var fab_ppy = parseFloat($('#fcpcase').val());
			console.log('fab_ppy=CASE = '+fab_ppy);
		}

		<?php
		}
		?>



		if($('#force-full-fabric-widths').is(':checked')){
			var ffw=1;
		}else{
			var ffw=0;
		}


		if($('#force-full-lining-widths').is(':checked')){
			var ffwl=1;
		}else{
			var ffwl=0;
		}



		if($('#force-round-distributed-yds').is(':checked')){
			var rfy=1;
		}else{
			var rfy=0;
		}


		if($('#force-round-yds-ea').is(':checked')){
			var rfy_eol=1;
		}else{
			var rfy_eol=0;
		}


		if($('#force-round-total-widths-eol').is(':checked')){
			var rndw_eol = 1;
		}else{
			var rndw_eol = 0;
		}

		var wrpa = parseInt($('#wraparound-inches').val());


		if($('#disable-welt-rescue').is(':checked')){
			var dis_wltresc=1;
		}else{
			var dis_wltresc=0;
		}

		var face = parseFloat($('#face').val());
		var height = parseFloat($('#height').val());
		var returnval = parseFloat($('#return').val());
		
		//run calculations//

		//labor per linear foot ; dollars
		var lbr_plf = bl_plf;// + wlt_t + wlt_b);	
		
		//total horizontal dimension ; inches
		var corn_x = (face + returnval + returnval + wrpa); 
		
		//calculate welt height ; inches
		if((wlt_t + wlt_b) == 2){
			var wlt_h = (wlt_s * 2);
		}else if((wlt_t + wlt_b) == 1){
			var wlt_h = wlt_s ;
		}else{
			var wlt_h = 0;
		}
		
		//calculate Fab_cl Fabric cut length ; inches
		var fab_cl = (height + xtr_h);
		
		//calculate Lin_cl Lining cut length ; inches
		var lin_cl = (height + xtr_h);
		
		//calculate #rrcorn_pc #how many cornices can be made per cut of fabric
		//if(qty == 1 || ffw == 1){
		//	var rrcorn_pc = 1;
		//}else{
			var rrcorn_pc = parseInt((fab_w / fab_cl));
		//}
		

		var real_widths_needed = Math.ceil((qty / rrcorn_pc));

		$('#real-fabric-widths-needed').val(real_widths_needed);


		//calculate #rrcorn_pclin #how many cornices can be made per cut of lining
		if(qty == 1){
			var rrcorn_pclin = 1;
		}else{
			var rrcorn_pclin = parseInt((lin_w / lin_cl));
		}
		
		//calculate waste per CW ; inches
		//var wast_pcw = (fab_w - (fab_cl * rrcorn_pc));
		var wast_pcw = (fab_w - ( ( (fab_cl * rrcorn_pc) * (qty / rrcorn_pc) ) ));
		console.log('wast_pcw = ('+fab_w+' - ((('+fab_cl+'*'+rrcorn_pc+') * ('+qty+' / '+rrcorn_pc+')))) = '+wast_pcw);

		//console.log('wast_pcw = ('+fab_w+' - ('+fab_cl+' * '+rrcorn_pc+')) = '+wast_pcw);

		//calculate how many welts you need, total. $wlt_qty
		var wlt_qty = ((wlt_t + wlt_b) * qty);
		
		//calculate how many welts can be rescued from waste from a single CW, if any. $resc_wlt
		if(dis_wltresc == 1){
			var resc_wlt = 0;
		}else if(wast_pcw >= wlt_s){
			var resc_wlt = parseInt((wast_pcw / wlt_s));
		}else{
			var resc_wlt = 0;
		}		
		
		//prep welt rescue warning , $warn8
		if(dis_wltresc == 1){
			var warn8 = "WELT RESCUE DISABLED";
		}else{
			var warn8 = "WELT RESCUE ENABLED";
		}		
		
		//calculate total of rescuable welts, $totresc_wlt
		//step1
		var trw1 = (Math.ceil(qty / rrcorn_pc) * resc_wlt);
		
		//step2
		if(trw1 >= wlt_qty){
			var totresc_wlt = wlt_qty;
		}else{
			var totresc_wlt = trw1;
		}		
		
		//calculate how many welts you can make out of a CW , $wlt_pcw
		var wlt_pcw = parseInt((fab_w / wlt_s));
		
		//calculate welt CW , $wlt_cw
		var wlt_cw = Math.ceil((wlt_qty - totresc_wlt) / wlt_pcw);
		console.log('wlt_cw = CEIL( ('+wlt_qty+' - '+totresc_wlt+') / '+wlt_pcw+')');
		
		//prep welt recommendation , $wltrec
		//step1
		if(wlt_cw == 0){
			var wltrec = 0;
		}else{
			var wltrec = (wlt_cw - (wlt_qty / wlt_pcw));
		}
		
		//step2
		if(wltrec >= 0.9){
			var warn7 = "ADJUST WELT+HEIGHT PARAMETERS";
		}else{
			var warn7 = "WELT+HEIGHT PARAMETERS SEEM OK";
		}		
		
		//CALCULATE WELT YARDS , $wlt-yds
		var wlt_yds = (((wlt_cw * corn_x) / 36) / qty);
		console.log('wlt_yds = ((('+wlt_cw+' * '+corn_x+') / 36) / '+qty+') = '+wlt_yds);
		
		//calculate fabric tolerance , $fabtol
		if(qty == 1){
			var fabtol = "N/A";
		}else{
			var fabtol = (fab_w / fab_cl) - (parseInt((fab_w / fab_cl)));
		}
		
		//prop recommendation , $warn6
		if( qty == 1 ){
			var warn6 = "QTY = 1; EXTRA-HEIGHT PARAMETERS OK";
		}else if(fabtol > 0.9){
			var warn6 = "CONSIDER ADJUSTING EXTRA-HEIGHT PARAMETERS";
		}else{
			var warn6 = "EXTRA-HEIGHT PARAMETERS SEEM OK";
		}		
		
		//calculate Cut widths of fabric
		if(ffw == 1){
			var corn_wfab=1;
		}else{
			//var corn_wfab = (1 / rrcorn_pc);
			var corn_wfab = (real_widths_needed / qty);
		}
		
		//calculate Cut widths of lining
		var corn_wlin = (1 / rrcorn_pclin);
		
		//calc value of yds per CW, assuming 1 CW per cornice.
		var rr_cw = ((corn_x / 36) * oh);
		


		//tot_wc
		//pre-total widths for the line
		var tot_wc = (corn_wfab * qty);
		
		//tot_weol
		//total widths of fabric for the line
		if(rndw_eol == 0 ){	
			var tot_weol = tot_wc;
		}else{
			var tot_weol = (Math.ceil(tot_wc));
		}


		
		//prep calculations for yards of fabric , $fab_yds 
		//step1
		if(qty == 1){
			var fy_s1 = rr_cw;
		}else{
			//var fy_s1 = (((Math.ceil(qty / rrcorn_pc)) * rr_cw) / qty);
			var fy_s1 = ((((tot_weol * corn_x) / 36) * oh) / qty);
			console.log('fy_s1 = (((('+tot_weol+' * '+corn_x+') / 36) * '+oh+') / '+qty+')');
		}
		
		//step2
		var fy_s2 = ((Math.ceil(fy_s1 * qty)) / qty);
		
		//step3
		var fy_s3 = (Math.ceil(fy_s2));
		
		//calculate $fab_yds
		if(rfy_eol == 1){
			var fab_yds = fy_s3 + wlt_yds;
		}else if(rfy == 1){
			var fab_yds = fy_s2 + wlt_yds;
		}else{
			var fab_yds = fy_s1 + wlt_yds;
		}	

		//calculate yards of lining , $lin_yds
		if(qty == 1){
			var lin_yds = rr_cw;
		}else{
			var lin_yds = (((Math.ceil(qty / rrcorn_pclin)) * rr_cw) / qty);
		}		
		
		//total fabric yds, used only for notification
		var totfab_yds = (fab_yds * qty);
		var tot_yds=totfab_yds;
		
		//fabric cost per item, one of the THREE MAIN VALUES USED TO CALCULATE PRICE PER ITEM

		if($('#com-fabric').is(':checked')){
			//ignore all fabric cost stuff
			var fab_cost=0;
			var totfab_cost=0;
			$('#fabric-cost').val('0.00');
			$('#fabric-price').val('0.00');
			$('select[name=fabric-cost-per-yard]').parent().hide('fast');
			$('#fabricmarkupwrap,#inboundfreightwrap').hide('fast');
		}else{

			$('select[name=fabric-cost-per-yard]').parent().show('fast');
			$('#fabricmarkupwrap,#inboundfreightwrap').show('fast');

//PPSA-33
			if(qty >0 && $('select[name=fabric-cost-per-yard]').val() != '' || $('#fabric-cost-per-yard-custom-value').val() != '' && tot_yds > 0){
				//fab_ppy
				console.log('fab_ppy = '+fab_ppy);
		   		$.each(rulesets,function(index,value){
					if(fab_ppy >= parseFloat(value.price_low) && fab_ppy <= parseFloat(value.price_high) && tot_yds >= parseFloat(value.yds_low) && tot_yds < parseFloat(value.yds_high)){
						$('#fabricmarkupwrap small').hide();
						$('#fabric-markup').val(value.markup);
						$('#fabricmarkupinnerwrap').show();
						$('#inboundfreightwrap small').hide();
						$('#inboundfreightinnerwrap').show();
					}
		   		});
			}

		
		

			//inbound freight calculation
			var ibfrtpy=0;
			if(fab_w >= 54 && fab_w <= 72){
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
    		}
		
			$('#inbound-freight').val(ibfrtpy);
			console.log('tot_yds = '+tot_yds);
			console.log('ibfrtpy = '+ibfrtpy);
	
			if($('#inbound-freight-custom-value').val() != ''){
				ibfrtpy=parseFloat($('#inbound-freight-custom-value').val());
			}
			
		


			if($('#fabric-markup-custom-value').val() != ''){
				var fabricMarkup=(1+(parseFloat($('#fabric-markup-custom-value').val())/100));
			}else{
				var fabricMarkup=(1+(parseFloat($('#fabric-markup').val())/100));
			}

			var markedupFabric=((parseFloat(fab_ppy)+parseFloat(ibfrtpy))*parseFloat(fabricMarkup));


			var fab_cost=(parseFloat(fab_yds)*markedupFabric);
			console.log('fab_cost = '+fab_cost);
			console.log('fab_cost_math = ('+parseFloat(fab_yds)+' * '+markedupFabric+')');

			//total fabric cost, used only for notification
			var totfab_cost = (fab_cost * qty);
		}
		
		//lining cost per item, one of the THREE MAIN VALUES USED TO CALCULATE PRICE PER ITEM		
		var lin_cost = (lin_yds * lin_ppy);
				
		//labor cost per item, one of the THREE MAIN VALUES USED TO CALCULATE PRICE PER ITEM		
		var labor_billable=(Math.ceil( (face + returnval + returnval ) / 12));
        console.log('labor_billable = ('+face+' + '+returnval+' + '+returnval+') / 12 = '+labor_billable);
        
		var lbr_pu = (labor_billable * lbr_plf);
		console.log('lbr_plf = '+lbr_plf);
		console.log('lbr_pu = ('+labor_billable+' * '+lbr_plf+') = '+lbr_pu);
		
		
		//PRICE PER CORNICE , the sum of all THREE MAIN VALUES		
		var cost_pcorn = (fab_cost + lin_cost + lbr_pu);
	    console.log('cost_pcorn_math = ('+fab_cost+' + '+lin_cost+' + '+lbr_pu+')');
		console.log('cost_pcorn = '+cost_pcorn);
		


		//tot_wc
		//pre-total widths for the line
		var tot_wc = (corn_wfab * qty);
		
		//tot_weol
		//total widths of fabric for the line
		if(rndw_eol == 0 ){	
			var tot_weol = tot_wc;
		}else{
			var tot_weol = (Math.ceil(tot_wc));
		}		


		
		$('#face-retret-wraparound').val(corn_x);
		$('#cornice-widths-fabric').val(corn_wfab.toFixed(2));
		$('#cornice-widths-lining').val(corn_wlin.toFixed(2));
		$('#total-fabric-widths').val(roundToTwo(tot_weol).toFixed(2));

		$('#welt-height').val(wlt_h);
		$('#fabric-cl').val(fab_cl);
		$('#lining-cl').val(lin_cl);
		if(qty==1){
			$('#fabric-tolerance').val(fabtol);
		}else{
			$('#fabric-tolerance').val(" ("+roundToTwo(fabtol).toFixed(2)+")");
		}
		$('#cornices-per-cw-fabric').val(rrcorn_pc);
		$('#waste-per-cw').val(wast_pcw);
		$('#qty-cw-used-for-welts').val(wlt_cw+" ,  (each having up to "+wlt_pcw+" welts ea)");
		$('#welt-rescue-status').val(warn8);
		$('#cw-for-welts-tolerance').val(" ("+roundToTwo(wltrec).toFixed(2)+")");
		
		$('#welt-plus-height-parameters-recommendation').val(warn7);
		$('#welt-yds').val(roundToTwo(wlt_yds).toFixed(2));
		$('#extra-height-parameters-recommendation').val(warn6);
		$('#cornices-per-cw-of-lining').val(rrcorn_pclin);
		
		$('#yds-per-unit').val(roundToTwo(fab_yds).toFixed(2));
		$('#total-yds').val(roundToTwo(tot_yds).toFixed(2));

		$('#yds-of-lining').val(roundToTwo(lin_yds).toFixed(2));
		
		
		$('#fabric-cost').val(roundToTwo(fab_cost).toFixed(2));
		$('#lining-cost').val(roundToTwo(lin_cost).toFixed(2));
		$('#labor-cost').val(roundToTwo(lbr_pu).toFixed(2));
		$('#labor-billable').val(labor_billable);
		
		
		$('#cost').val(roundToTwo(cost_pcorn).toFixed(2));
		
		var priceval=roundToTwo(cost_pcorn).toFixed(2);


		if($('#individual-nailheads').is(':checked')){
			var indNailHeadCost = ((parseFloat($('#nailhead-boxes-per-line').val()) / parseFloat($('#qty').val()))*parseFloat($('#nailhead-dollars-per-box').val()));
			priceval = (parseFloat(priceval) + parseFloat(indNailHeadCost));
			console.log('Base Price increased by '+indNailHeadCost+' for Individual Nailheads ('+$('#nailhead-boxes-per-line').val()+'X at $'+$('#nailhead-dollars-per-box').val()+' ea)');

		}

		if($('#nailhead-trim').is(':checked')){
			var nailHeadTrimCost = ((parseFloat($('#nailhead-trim-rolls-per-line').val()) / parseFloat($('#qty').val())) * parseFloat($('#nailhead-trim-dollars-per-roll').val()));
			priceval = (parseFloat(priceval) + parseFloat(nailHeadTrimCost));
			console.log('Base Price increased by '+nailHeadTrimCost+' for Nailhead Trim ('+$('#nailhead-trim-rolls-per-line').val()+'X at $'+$('#nailhead-trim-dollars-per-roll').val()+')');
		}



		if($('#horizontal-straight-banding').is(':checked')){
			var horizontalStraightBandingCost = ( parseFloat($('#horizontal-straight-banding-yards-per-cornice').val()) * parseFloat($('#horizontal-straight-banding-price-per-yard').val()) );
			priceval = (parseFloat(priceval) + parseFloat(horizontalStraightBandingCost));
			console.log('Base Price increased by '+horizontalStraightBandingCost+' for Horizontal Straight Banding ('+$('#horizontal-straight-banding-yards-per-cornice').val()+' yards at $'+$('#horizontal-straight-banding-price-per-yard').val()+' per yard)');
		}



		if($('#horizontal-shaped-banding').is(':checked')){
			var horizontalShapedBandingCost = ( parseFloat($('#horizontal-shaped-banding-yards-per-cornice').val()) * parseFloat($('#horizontal-shaped-banding-price-per-yard').val()) );
			priceval = (parseFloat(priceval) + parseFloat(horizontalShapedBandingCost));
			console.log('Base Price increased by '+horizontalShapedBandingCost+' for Horizontal Shaped Banding ('+$('#horizontal-shaped-banding-yards-per-cornice').val()+' yards at $'+$('#horizontal-shaped-banding-price-per-yard').val()+' per yard)');
		}



		if($('#extra-welts').is(':checked')){
			var extraWeltCost = (parseFloat($('#extra-welt-yards-per-cornice').val()) * parseFloat($('#extra-welt-price-per-yard').val()));
			priceval = (parseFloat(priceval) + parseFloat(extraWeltCost));
			console.log('Base Price increased by '+extraWeltCost+' for Extra Welt ('+$('#extra-welt-yards per line').val()+' yards at $'+$('#extra-welt-price-per-yard').val()+')');
		}


		if($('#trim-sewn-on').is(':checked')){
			var trimSewnOnCost=(parseFloat($('#trim-yards-per-cornice').val()) * parseFloat($('#trim-price-per-yard').val()));
			priceval = (parseFloat(priceval) + parseFloat(trimSewnOnCost));
			console.log('Base Price increased by '+trimSewnOnCost+' for Sewn-On Trim ('+$('#trim-yards-per-cornice').val()+' yards at $'+$('#trim-price-per-yard').val()+' per yard)');
		}


		if($('#tassels').is(':checked')){
			var tasselsCost=( parseFloat($('#tassels-count').val()) * parseFloat($('#tassels-price-each').val()) );
			priceval = (parseFloat(priceval) + parseFloat(tasselsCost));
			console.log('Base Price increased by '+tasselsCost+' for Sewn-On Tassels ('+$('#tassels-count').val()+' tassels at $'+$('#tassels-price-each').val()+' each)');
		}


		//add ons
		var startPrice=priceval;
		$('#start-price-val').val(startPrice);
		var addOnTotals=0;
		var addOnText='';


		//individual nail heads
		if($('#individual-nailheads').is(':checked')){
			var individualNailheadsIncrease=(labor_billable * <?php echo $settings['cornice_individual_nailheads_per_lf']; ?>);
			priceval=(parseFloat(priceval)+parseFloat(individualNailheadsIncrease));
			addOnTotals=(parseFloat(addOnTotals)+parseFloat(individualNailheadsIncrease));
			addOnText += '<tr><td>Individual Nailheads ('+labor_billable+' LF)</td><td>$'+roundToTwo(parseFloat(individualNailheadsIncrease)).toFixed(2)+'</td></tr>';
		}


		//nailhead trim
		if($('#nailhead-trim').is(':checked')){
			var nailheadTrimIncrease=(labor_billable * <?php echo $settings['cornice_nailhead_trim_per_lf']; ?>);
			priceval=(parseFloat(priceval)+parseFloat(nailheadTrimIncrease));
			addOnTotals=(parseFloat(addOnTotals) + parseFloat(nailheadTrimIncrease));
			addOnText += '<tr><td>Nailhead Trim ('+labor_billable+' LF)</td><td>$'+roundToTwo(parseFloat(nailheadTrimIncrease)).toFixed(2)+'</td></tr>';
		}


		//add Magnets increase match (if applicable)
		if($('#covered-buttons').is(':checked')){
			var coveredButtonsIncrease=(parseFloat($('#covered-buttons-count').val()) * <?php echo $settings['cornice_covered_buttons_each']; ?>);
			priceval=(parseFloat(priceval)+parseFloat(coveredButtonsIncrease));
			addOnTotals=(parseFloat(addOnTotals) + parseFloat(coveredButtonsIncrease));
			addOnText += '<tr><td>'+$('#covered-buttons-count').val()+'X Covered Buttons</td><td>$'+roundToTwo(parseFloat(coveredButtonsIncrease)).toFixed(2)+'</td></tr>';
		}



		//add banding
		if($('#horizontal-straight-banding').is(':checked')){
			var horizontalStraightBandingIncrease=((parseFloat($('#horizontal-straight-banding-count').val()) * labor_billable) * <?php echo $settings['cornice_contrast_straight_banding_per_lf']; ?>);
			priceval=(parseFloat(priceval) + horizontalStraightBandingIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(horizontalStraightBandingIncrease));
			addOnText += '<tr><td>'+$('#horizontal-straight-banding-count').val()+'X Horizontal Straight Banding</td><td>$'+roundToTwo(parseFloat(horizontalStraightBandingIncrease)).toFixed(2)+'</td></tr>';
		}

		if($('#horizontal-shaped-banding').is(':checked')){
			var horizontalShapedBandingIncrease=((parseFloat($('#horizontal-shaped-banding-count').val()) * labor_billable) * <?php echo $settings['cornice_contrast_shaped_banding_per_lf']; ?>);
			priceval=(parseFloat(priceval) + horizontalShapedBandingIncrease);
			addOnTotals=(parseFloat(addOnTotals) + parseFloat(horizontalShapedBandingIncrease));
			addOnText += '<tr><td>'+$('#horizontal-shaped-banding-count').val()+'X Horizontal Shaped Banding</td><td>$'+roundToTwo(parseFloat(horizontalShapedBandingIncrease)).toFixed(2)+'</td></tr>';
		}


		//extra welts
		if($('#extra-welts').is(':checked')){
			var extraweltsIncrease=((parseFloat($('#extra-welts-count').val()) * labor_billable) * <?php echo $settings['wt_welt_covered_per_lf']; ?>);
			priceval=(parseFloat(priceval) + extraweltsIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(extraweltsIncrease));
			addOnText += '<tr><td>'+$('#extra-welts-count').val()+'X Extra Welts ('+labor_billable+' LF)</td><td>$'+roundToTwo(parseFloat(extraweltsIncrease)).toFixed(2)+'</td></tr>';
		}


		//tassels
		if($('#tassels').is(':checked')){
			var tasselsIncrease=(parseFloat($('#tassels-count').val()) * <?php echo $settings['drapery_rings_tassles_each']; ?>);
			priceval=(parseFloat(priceval)+tasselsIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(tasselsIncrease));
			addOnText += '<tr><td>'+$('#tassels-count').val()+'X Sewn-On Tassels</td><td>$'+roundToTwo(parseFloat(tasselsIncrease)).toFixed(2)+'</td></tr>';
		}



		//sewn on trim
		if($('#trim-sewn-on').is(':checked')){
			var trimsewnonIncrease=(parseFloat($('#trim-lf').val()) * <?php echo $settings['wt_trim_sewn_on_per_lf']; ?>);
			priceval=(parseFloat(priceval)+trimsewnonIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(trimsewnonIncrease));
			addOnText += '<tr><td>Sewn-On Trim ('+$('#trim-lf').val()+' LF)</td><td>$'+roundToTwo(parseFloat(trimsewnonIncrease)).toFixed(2)+'</td></tr>';
		}



		//drill holes
		if($('#drill-holes').is(':checked')){
			var drillholesIncrease=(parseFloat($('#drill-hole-count').val()) * <?php echo $settings['wt_drill_holes_each']; ?>);
			priceval=(parseFloat(priceval)+drillholesIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(drillholesIncrease));
			addOnText += '<tr><td>'+$('#drill-hole-count').val()+'X Drill Holes</td><td>$'+roundToTwo(parseFloat(drillholesIncrease)).toFixed(2)+'</td></tr>';
		}



		if($('#hinged').is(':checked')){
			var hingedIncrease = parseFloat(<?php echo $settings['hinged_surcharge']; ?>);
			priceval = (parseFloat(priceval) + hingedIncrease);
			addOnTotals = (addOnTotals + hingedIncrease);
			addOnText += '<tr><td>Hinged Surcharge</td><td>$<?php echo number_format($settings['hinged_surcharge'],2,'.',''); ?></td></tr>';
		}



		$('#fabric-usage-for-welts').val('');
		$('#halfwidths-recommendation').val('');
		$('#lining-halfwidths-status').val('');


		$('#add-on-total-val').val(addOnTotals);
		$('#add-on-text-val').val(addOnText);


		$('#price').val(roundToTwo(priceval).toFixed(2));

		$('#total-surcharges').val(roundToTwo(addOnTotals).toFixed(2));


		$('#explainmath').html('<h3 id="pricebreakdown">Base Price Surcharge Breakdown</h3><hr><table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000"><tr><td>Starting Price (before surcharges/add-ons)</td><td><B>$'+roundToTwo(parseFloat($('#start-price-val').val())).toFixed(2)+'</B></td></tr>'+$('#add-on-text-val').val()+'<tr><td><b>RESULTING BASE PRICE:</b></td><td><b><u>$'+roundToTwo(parseFloat($('#price').val())).toFixed(2)+'</u></b></td></tr></table><Br><Br><Br><Br><Br><br><Br><Br><Br>');


		//end railroaded calculation
	}else{
		//utr calculation

		$('#vertical-repeat').parent().show();
		//$('#force-full-fabric-widths').parent().show();
		//$('#force-full-lining-widths').parent().show();
		//$('#force-round-total-widths-eol').parent().show();
		$('#disable-welt-rescue').parent().hide();
		$('#cornices-per-cw-fabric').hide();

		var v_rpt = parseFloat($('#vertical-repeat').val());
		
		
		
		<?php if(intval($quoteID) == 0){ ?>
		if($('#custom-base-labor').val() != ''){
			var bl_plf = parseFloat($('#custom-base-labor').val());
		}else{
			var bl_plf = parseFloat($('#base-labor-cost').val());
		}
		<?php }else{ ?>
			var bl_plf = parseFloat($('#base-labor-cost').val());
		<?php } ?>
		
		

		if($('#welt-top').is(':checked')){
			var wlt_t=1;
		}else{
			var wlt_t=0;
		}

		
		if($('#welt-bottom').is(':checked')){
			var wlt_b=1;
		}else{
			var wlt_b=0;
		}
		
		var wlt_s = parseFloat($('#inches-per-welt').val());
		var xtr_h = parseFloat($('#extra-inches-height').val());
		var lin_ppy = parseFloat($('#lining-price-per-yd').val());
		var lin_w = parseFloat($('#lining-width').val());


		<?php
		if($fabricid=='custom'){
		?>

		if($('#custom-fabric-cost-per-yard').val() != '' && parseFloat($('#custom-fabric-cost-per-yard').val()) >0){
			var fab_ppy = parseFloat($('#custom-fabric-cost-per-yard').val());
		}

		<?php
		}else{
		?>

		//var fab_ppy = parseFloat($('#fab-price-per-yd').val());
		/*PPSA-33 start*/
		if($('#fabric-cost-per-yard-custom-value').val() != '' && parseFloat($('#fabric-cost-per-yard-custom-value').val()) >0){
			var fab_ppy = parseFloat($('#fabric-cost-per-yard-custom-value').val());
		} else /*PPSA-33 end*/
		if($('select[name=fabric-cost-per-yard]').val() == 'cut'){
			var fab_ppy = parseFloat($('#fcpcut').val());
			console.log('fab_ppy=CUT = '+fab_ppy);
		}else if($('select[name=fabric-cost-per-yard]').val() == 'bolt'){
			var fab_ppy = parseFloat($('#fcpbolt').val());
			console.log('fab_ppy=BOLT = '+fab_ppy);
		}else if($('select[name=fabric-cost-per-yard]').val() == 'case'){
			var fab_ppy = parseFloat($('#fcpcase').val());
			console.log('fab_ppy=CASE = '+fab_ppy);
		}

		<?php
		}
		?>


		if($('#force-full-fabric-widths').is(':checked')){
			var ffw=1;
		}else{
			var ffw=0;
		}


		if($('#force-full-lining-widths').is(':checked')){
			var ffwl=1;
		}else{
			var ffwl=0;
		}
		

		if($('#force-round-distributed-yds').is(':checked')){
			var rfy=1;
		}else{
			var rfy=0;
		}


		if($('#force-round-yds-ea').is(':checked')){
			var rfy_eol=1;
		}else{
			var rfy_eol=0;
		}

		
		if($('#force-round-total-widths-eol').is(':checked')){
			var rndw_eol = 1;
		}else{
			var rndw_eol = 0;
		}


		var dpti_w = parseFloat($('#tolerance-in-widths').val());
		var dpti_y = parseFloat($('#tolerance-in-yds').val());
		var wrpa = parseInt($('#wraparound-inches').val());
		var face = parseFloat($('#face').val());
		var height = parseFloat($('#height').val());
		var returnval = parseFloat($('#return').val());
		var fabt_fullw = parseFloat($('#fab-tolerance').val());
		
		
		//run calculations//
		/*******labor per linear foot ; dollars******/
		var lbr_plf = bl_plf;// + wlt_t + wlt_b);
		
		//total horizontal dimension ; inches
		var corn_x = (face + returnval + returnval + wrpa);
		
		//calculate widths of fabric
		
		//raw value of fabric widths
		var corn_wraw = (corn_x / fab_w);
		
		//corn_wwh
		//fabric widths when halves are allowed
		var corn_wwh = ( ceiling(corn_wraw,0.5));	
		
		//corn_wnh
		//fabric widths when halves are NOT allowed
		if((corn_wraw - parseInt(corn_wraw)) <= dpti_w){	
			var corn_wnh = parseInt(corn_wraw);		
		}else{
			var corn_wnh = Math.ceil(corn_wraw);
		}		
		
		//corn_hw
		//flags the necessity for half-widths of fabric
		if(ffw == 1 || qty ==1){
			//if either qty =1, or we're forcing full-widths, this flag will be summarily forced off.
			var corn_hw = 0;
		}else if((corn_wnh - corn_wraw) >= 0.5){
			//if this condition is met, this flag will set as on
			var corn_hw = 1;
		}else{	
			//otherwise, this flag will set as off 
			var corn_hw = 0;	
		}
		
		//corn_fab
		//value for further calculations gets selected, depending on the state of th corn_hw flag
		if(corn_hw == 1){
			var corn_wfab = corn_wwh;
		}else{
			var corn_wfab = corn_wnh;
		}
		
		//tot_wc
		//pre-total widths for the line
		var tot_wc = (corn_wfab * qty);
		
		//tot_weol
		//total widths of fabric for the line
		if(rndw_eol == 0 ){	
			var tot_weol = tot_wc;
			console.log('tot_weol NO round up');
		}else{
			var tot_weol = (Math.ceil(tot_wc));
			console.log('tot_weol YES round up');
		}		
				
		//prepare a warn2 status notification about corn_hw flag
		if(ffw == 1){		
			var warn2 = "FABRIC HALF-WIDTHS HAVE BEEN DISABLED";
		}else if(corn_hw == 1){
			var warn2 = "Fabric Half-Widths are Feasible";
		}else{
			var warn2 = "FABRIC HALF-WIDTHS ARE NOT FEASIBLE";
		}
				
		//calculate widths of lining
		
		//raw widths of lining
		var corn_wrawl = (corn_x / lin_w);
		
		//corn_wwhl #widths when halves are allowed
		var corn_wwhl = ( ceiling(corn_wrawl,0.5));
		
		//corn_wnhl #widths when halves are NOT allowed
		if((corn_wrawl - parseInt(corn_wrawl)) <= dpti_w){
			var corn_wnhl = parseInt(corn_wrawl);		
		}else{
			var corn_wnhl = Math.ceil(corn_wrawl);
		}	
		
		//corn_hwl
		//flags the necessity for half-widths of lining
		if(ffwl == 1 || qty ==1){
			var corn_hwl = 0;
		}else if((corn_wnhl - corn_wrawl) >= 0.5){
			var corn_hwl = 1;
		}else{
			var corn_hwl = 0;
		}
		
		//corn_lin
		//value for further calculations gets selected, depending on the state of th corn_hwl flag
		if(corn_hwl == 1){
			var corn_wlin = corn_wwhl;
		}else{
			var corn_wlin = corn_wnhl;
		}
		
		
		//tot_wc
		//pre-total widths for the line
		var tot_wc = (corn_wfab * qty);
		
		//tot_weol
		//total widths of fabric for the line
		if(rndw_eol == 0 ){	
			var tot_weol = tot_wc;
		}else{
			var tot_weol = (Math.ceil(tot_wc));
		}		
				
		//prepare a warn2 status notification about corn_hw flag
		if(ffw == 1){		
			var warn2 = "FABRIC HALF-WIDTHS HAVE BEEN DISABLED";
		}else if(corn_hw == 1){
			var warn2 = "Fabric Half-Widths are Feasible";
		}else{
			var warn2 = "FABRIC HALF-WIDTHS ARE NOT FEASIBLE";
		}
				
		//calculate widths of lining
		//raw widths of lining
		var corn_wrawl = (corn_x / lin_w);
		
		//corn_wwhl #widths when halves are allowed
		var corn_wwhl = ( ceiling(corn_wrawl,0.5));
		
		//corn_wnhl #widths when halves are NOT allowed
		if((corn_wrawl - parseInt(corn_wrawl)) <= dpti_w){
			var corn_wnhl = parseInt(corn_wrawl);		
		}else{
			var corn_wnhl = Math.ceil(corn_wrawl);
		}	
		
		//corn_hwl
		//flags the necessity for half-widths of lining
		if(ffwl == 1 || qty ==1){
			var corn_hwl = 0;
		}else if((corn_wnhl - corn_wrawl) >= 0.5){
			var corn_hwl = 1;
		}else{
			var corn_hwl = 0;
		}
		
		//corn_lin
		//value for further calculations gets selected, depending on the state of th corn_hwl flag
		if(corn_hwl == 1){
			var corn_wlin = corn_wwhl;
		}else{
			var corn_wlin = corn_wnhl;
		}			
		
		//prepare a $warn2 status notification about $corn_hwl flag
		if(ffwl == 1){		
			var warn3 = "LINING HALF-WIDTHS HAVE BEEN DISABLED";
		}else if(corn_hwl == 1){
			var warn3 = "Lining Half-Widths are Feasible";
		}else{
			var warn3 = "LINING HALF-WIDTHS ARE NOT FEASIBLE";
		}
		
		//sum of welts
		var wlt_sum = (wlt_t + wlt_b);
		console.log('wlt_sum = ('+wlt_t+' + '+wlt_b+')');
		
		//wlt_h #calculate welt height; inches
		if((wlt_t + wlt_b) == 2){
			var wlt_h = (wlt_s * 2);
		}else if((wlt_t + wlt_b) == 1){
			var wlt_h = wlt_s;
		}else{
			var wlt_h = 0;
		}
		
		//calculate fabric cut lenght	fab_cl ; inches
		if(v_rpt == 0){
			var fab_cl = (height + xtr_h);
		}else{
			var fab_cl = (Math.ceil(((height + xtr_h) / v_rpt)) * v_rpt);
		}
		
		//calculate fabric waste from V Rpt (if any) ; inches
		var fab_wast = (fab_cl - (height + xtr_h));
		
		//calculate welt yards $wlt_yds 
		if(wlt_sum == 0){
			var wlt_yds = 0;	
		}else if(wlt_sum == 1 && (fab_wast >= wlt_s)){
			var wlt_yds = 0;
		}else if(wlt_sum == 1 && (fab_wast < wlt_s)){
			var wlt_yds = ((((tot_weol / qty) * wlt_h) / 36) * 1.05); 
		}else if(wlt_sum == 2 && (fab_wast >= (wlt_s * 2))){
			var wlt_yds = 0;
		}else if(wlt_sum == 2 && (fab_wast >= wlt_s) && (fab_wast < (wlt_s * 2))){
			var wlt_yds = (((((tot_weol / qty) * wlt_h) / 36) * 1.05) / 2);
		}else if(wlt_sum == 2 && (fab_wast < wlt_s)){
			var wlt_yds = ((((tot_weol / qty) * wlt_h) / 36) * 1.05);
		}

		//prepare a warn2 status notification about fabric usage on welts
		//step1 #set phrases
		var w_a = " NO WELTS. ";
		var w_b = " ENOUGH WASTE FOR ONE WELT. ";
		var w_c = " ENOUGH WASTE FOR TWO WELTS. ";
		var w_d = " >> NO FABRIC ADDED FOR WELTS << ";
		var w_e = " --> FABRIC ADDED FOR ONE WELT ";
		var w_f = " --> FABRIC ADDED FOR ALL WELTS ";
		var w_g = " NOT ENOUGH WASTE. ";
		
		//step2
		//pick appropiate phrases depending on calculation results
		if(wlt_sum == 0){
			var ph1 = w_a;
			var ph2 = w_d;
		}else if(wlt_sum == 1 && (fab_wast >= wlt_h)){
			var ph1 = w_b;
			var ph2 = w_d;
		}else if(wlt_sum == 1 && (fab_wast < wlt_h)){
			var ph1 = w_g;
			var ph2 = w_e;			
		}else if(wlt_sum == 2 && (fab_wast >= wlt_h)){
			var ph1 = w_c;
			var ph2 = w_d;
		}else if(wlt_sum == 2 && (fab_wast < (wlt_h / 2))){
			var ph1 = w_g;
			var ph2 = w_f;
		}else if(wlt_sum == 2 && (fab_wast >= (wlt_h /2)) && (fab_wast < wlt_h)){
			var ph1 = w_b;
			var ph2 = w_e;
		}
		
		//step3
		//sets the final value of $warn4
		var warn4 = ph1+ph2;
		
		//lining cut lenght	lin_cl ; inches
		var lin_cl = (height + xtr_h);
		
		//fab_yds
		//calculate yards of fabric per item
		//yds_pu #raw value of yds per unit ; yds
		
		var yds_pu = (((((tot_weol / qty) * fab_cl) / 36) * 1.05) + wlt_yds);
		console.log('yds_pu = ((((('+tot_weol+' / '+qty+') * '+fab_cl+') / 36) * 1.05) + '+wlt_yds+')');


		//rounded distribution ; yds
		var yds_dist = (Math.ceil(yds_pu * qty) / qty);
		
		//rounded yds ea 
		var yds_rnd = (Math.ceil(yds_pu));
		
		//fab_yds
		//final value of yards per item
		if(rfy_eol == 1){
			var fab_yds = yds_rnd;
		}else if(rfy == 1){
			var fab_yds = yds_dist;
		}else{
			var fab_yds = yds_pu;
		}
				
		//lin_yds
		//calculate yards of lining per item
		var lin_yds = (((corn_wlin * lin_cl) / 36) * 1.05);		
		
		//Setup a warning warn1 for when fab widths are too close to desired size. 
		//step 1
		// stablish the percentage $tol_pct
		var tol_pct = (corn_wraw / corn_wfab);
		
		//step 2 
		// calculate
		if((tol_pct > fabt_fullw) && ffw == 0){
			var warn1 = "PLEASE CONSIDER FORCING FULL WIDTHS";		
		}else{
			var warn1 = "YOU SEEM TO BE OK";
		}
		
		//Setup a warning warn5 for when total fab widths contains a half-width.
		if((tot_weol - parseInt(tot_weol)) > 0){
			var warn5 = "YOUR LINE TOTAL CONTAINS A HALF-WIDTH, PLEASE CONSIDER ROUNDING W EOL";
		}else{
			var warn5 = "Your line contains only full widths. You seem to be OK";
		}		
		
		//total fabric yds, used only for notification
		var totfab_yds = (fab_yds * qty);
		var tot_yds=totfab_yds;

		//fabric cost per item, one of the THREE MAIN VALUES USED TO CALCULATE PRICE PER ITEM
		//var fab_cost = (fab_yds * fab_ppy);	
		



		if($('#com-fabric').is(':checked')){
			//ignore all fabric cost stuff
			var fab_cost=0;
			var totfab_cost=0;
			$('#fabric-cost').val('0.00');
			$('#fabric-price').val('0.00');
			$('select[name=fabric-cost-per-yard]').parent().hide('fast');
			$('#fabricmarkupwrap,#inboundfreightwrap').hide('fast');


		}else{

			$('#fabric-markup-custom-value').parent().show('fast');
			$('select[name=fabric-cost-per-yard]').parent().show('fast');
			$('#inboundfreightwrap').show('fast');
			$('#fabricmarkupwrap').show('fast');
			

			if(qty >0 && $('select[name=fabric-cost-per-yard]').val() != '' && tot_yds > 0){
				//fab_ppy
				console.log('fab_ppy = '+fab_ppy);
			   	$.each(rulesets,function(index,value){
					if(fab_ppy >= parseFloat(value.price_low) && fab_ppy <= parseFloat(value.price_high) && tot_yds >= parseFloat(value.yds_low) && tot_yds < parseFloat(value.yds_high)){
						$('#fabricmarkupwrap small').hide();
						$('#fabric-markup').val(value.markup);
						$('#fabricmarkupinnerwrap').show();
						$('#inboundfreightwrap small').hide();
						$('#inboundfreightinnerwrap').show();
					}
			   	});
			}


			//inbound freight calculation
			var ibfrtpy=0;
			
			if(fab_w >= 54 && fab_w <= 72){
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
    		}
			
			
			$('#inbound-freight').val(ibfrtpy);
			console.log('tot_yds = '+tot_yds);
			console.log('ibfrtpy = '+ibfrtpy);
		
			if($('#inbound-freight-custom-value').val() != ''){
				ibfrtpy=parseFloat($('#inbound-freight-custom-value').val());
			}
				
			


			if($('#fabric-markup-custom-value').val() != ''){
				var fabricMarkup=(1+(parseFloat($('#fabric-markup-custom-value').val())/100));
			}else{
				var fabricMarkup=(1+(parseFloat($('#fabric-markup').val())/100));
			}

			var markedupFabric=((parseFloat(fab_ppy)+parseFloat(ibfrtpy))*parseFloat(fabricMarkup));


			var fab_cost=(parseFloat(fab_yds)*markedupFabric);
			console.log('fab_cost = '+fab_cost);
			console.log('fab_cost_math = ('+parseFloat(fab_yds)+' * '+markedupFabric+')');



			//total fabric cost, used only for notification
			var totfab_cost = (fab_cost * qty);	

		}
					
		//lining cost per item, one of the THREE MAIN VALUES USED TO CALCULATE PRICE PER ITEM
		var lin_cost = (lin_yds * lin_ppy);	
		
		//labor cost per item, one of the THREE MAIN VALUES USED TO CALCULATE PRICE PER ITEM
		var labor_billable=(Math.ceil( (face+returnval+returnval) / 12));
        console.log('labor_billable = ('+face+' + '+returnval+' + '+returnval+') / 12 = '+labor_billable);
        
		var lbr_pu = (labor_billable * lbr_plf);
		console.log('lbr_plf = '+lbr_plf);
		console.log('lbr_pu = ('+labor_billable+' * '+lbr_plf+') = '+lbr_pu);
		
		//PRICE PER CORNICE , the sum of all THREE MAIN VALUES
		var cost_pcorn = (fab_cost + lin_cost + lbr_pu);
		console.log('cost_pcorn_math = ('+fab_cost+' + '+lin_cost+' + '+lbr_pu+')');
		console.log('cost_pcorn = '+cost_pcorn);
		
		$('#face-retret-wraparound').val(corn_x);
		$('#cornice-widths-fabric').val(corn_wfab.toFixed(2));
		$('#raw-fabric-widths').val(roundToTwo(corn_wraw).toFixed(2));
		$('#fabric-usage-for-welts').val(warn4);
		$('#total-fabric-widths').val(roundToTwo(tot_weol).toFixed(2));
		$('#halfwidths-recommendation').val(warn1);
		$('#fabric-half-widths-status').val(warn2);
		$('#lining-halfwidths-status').val(warn3);
		$('#raw-lining-widths').val(roundToTwo(corn_wrawl).toFixed(2));
		$('#cornice-widths-lining').val(corn_wlin.toFixed(2));
		$('#fabric-waste-from-vrpt').val(fab_wast);
		$('#fabric-cl').val(fab_cl);
		$('#lining-cl').val(lin_cl);

		$('#yds-per-unit').val(roundToTwo(fab_yds).toFixed(2));
		$('#total-yds').val(roundToTwo(tot_yds).toFixed(2));

		$('#yds-of-lining').val(roundToTwo(lin_yds).toFixed(2));
		$('#fabric-cost').val(roundToTwo(fab_cost).toFixed(2));
		$('#lining-cost').val(roundToTwo(lin_cost).toFixed(2));
		$('#welt-height').val(wlt_h);
		$('#welt-yds').val(roundToTwo(wlt_yds));
		$('#labor-cost').val(roundToTwo(lbr_pu).toFixed(2));
		$('#labor-billable').val(labor_billable);
		
		$('#cost').val(roundToTwo(cost_pcorn).toFixed(2));
		
		var priceval=roundToTwo(cost_pcorn).toFixed(2);


		if($('#individual-nailheads').is(':checked')){
			var indNailHeadCost = ((parseFloat($('#nailhead-boxes-per-line').val()) / parseFloat($('#qty').val()))*parseFloat($('#nailhead-dollars-per-box').val()));
			priceval = (parseFloat(priceval) + parseFloat(indNailHeadCost));
			console.log('Base Price increased by '+indNailHeadCost+' for Individual Nailheads ('+$('#nailhead-boxes-per-line').val()+'X at $'+$('#nailhead-dollars-per-box').val()+' ea)');

		}

		if($('#nailhead-trim').is(':checked')){
			var nailHeadTrimCost = ((parseFloat($('#nailhead-trim-rolls-per-line').val()) / parseFloat($('#qty').val())) * parseFloat($('#nailhead-trim-dollars-per-roll').val()));
			priceval = (parseFloat(priceval) + parseFloat(nailHeadTrimCost));
			console.log('Base Price increased by '+nailHeadTrimCost+' for Nailhead Trim ('+$('#nailhead-trim-rolls-per-line').val()+'X at $'+$('#nailhead-trim-dollars-per-roll').val()+')');
		}



		if($('#horizontal-straight-banding').is(':checked')){
			var horizontalStraightBandingCost = ( parseFloat($('#horizontal-straight-banding-yards-per-cornice').val()) * parseFloat($('#horizontal-straight-banding-price-per-yard').val()) );
			priceval = (parseFloat(priceval) + parseFloat(horizontalStraightBandingCost));
			console.log('Base Price increased by '+horizontalStraightBandingCost+' for Horizontal Straight Banding ('+$('#horizontal-straight-banding-yards-per-cornice').val()+' yards at $'+$('#horizontal-straight-banding-price-per-yard').val()+' per yard)');
		}



		if($('#horizontal-shaped-banding').is(':checked')){
			var horizontalShapedBandingCost = ( parseFloat($('#horizontal-shaped-banding-yards-per-cornice').val()) * parseFloat($('#horizontal-shaped-banding-price-per-yard').val()) );
			priceval = (parseFloat(priceval) + parseFloat(horizontalShapedBandingCost));
			console.log('Base Price increased by '+horizontalShapedBandingCost+' for Horizontal Shaped Banding ('+$('#horizontal-shaped-banding-yards-per-cornice').val()+' yards at $'+$('#horizontal-shaped-banding-price-per-yard').val()+' per yard)');
		}



		if($('#extra-welts').is(':checked')){
			var extraWeltCost = (parseFloat($('#extra-welt-yards-per-cornice').val()) * parseFloat($('#extra-welt-price-per-yard').val()));
			priceval = (parseFloat(priceval) + parseFloat(extraWeltCost));
			console.log('Base Price increased by '+extraWeltCost+' for Extra Welt ('+$('#extra-welt-yards per line').val()+' yards at $'+$('#extra-welt-price-per-yard').val()+')');
		}



		if($('#trim-sewn-on').is(':checked')){
			var trimSewnOnCost=(parseFloat($('#trim-yards-per-cornice').val()) * parseFloat($('#trim-price-per-yard').val()));
			priceval = (parseFloat(priceval) + parseFloat(trimSewnOnCost));
			console.log('Base Price increased by '+trimSewnOnCost+' for Sewn-On Trim ('+$('#trim-yards-per-cornice').val()+' yards at $'+$('#trim-price-per-yard').val()+' per yard)');
		}


		if($('#tassels').is(':checked')){
			var tasselsCost=( parseFloat($('#tassels-count').val()) * parseFloat($('#tassels-price-each').val()) );
			priceval = (parseFloat(priceval) + parseFloat(tasselsCost));
			console.log('Base Price increased by '+tasselsCost+' for Sewn-On Tassels ('+$('#tassels-count').val()+' tassels at $'+$('#tassels-price-each').val()+' each)');
		}


		//add ons
		var startPrice=priceval;
		$('#start-price-val').val(startPrice);
		var addOnTotals=0;
		var addOnText='';


		//individual nail heads
		if($('#individual-nailheads').is(':checked')){
			var individualNailheadsIncrease=(labor_billable * <?php echo $settings['cornice_individual_nailheads_per_lf']; ?>);
			priceval=(parseFloat(priceval)+parseFloat(individualNailheadsIncrease));
			addOnTotals=(parseFloat(addOnTotals) + parseFloat(individualNailheadsIncrease));
			addOnText += '<tr><td>Individual Nailheads ('+labor_billable+' LF)</td><td>$'+roundToTwo(parseFloat(individualNailheadsIncrease)).toFixed(2)+'</td></tr>';
		}


		//nailhead trim
		if($('#nailhead-trim').is(':checked')){
			var nailheadTrimIncrease=(labor_billable * <?php echo $settings['cornice_nailhead_trim_per_lf']; ?>);
			priceval=(parseFloat(priceval)+parseFloat(nailheadTrimIncrease));
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(nailheadTrimIncrease));
			addOnText += '<tr><td>Nailhead Trim ('+labor_billable+' LF)</td><td>$'+roundToTwo(parseFloat(nailheadTrimIncrease)).toFixed(2)+'</td></tr>';
		}


		//add Magnets increase match (if applicable)
		if($('#covered-buttons').is(':checked')){
			var coveredButtonsIncrease=(parseFloat($('#covered-buttons-count').val()) * <?php echo $settings['cornice_covered_buttons_each']; ?>);
			priceval=(parseFloat(priceval)+parseFloat(coveredButtonsIncrease));
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(coveredButtonsIncrease));
			addOnText += '<tr><td>'+$('#covered-buttons-count').val()+'X Covered Buttons</td><td>$'+roundToTwo(parseFloat(coveredButtonsIncrease)).toFixed(2)+'</td></tr>';
		}



		//add banding
		if($('#horizontal-straight-banding').is(':checked')){
			var horizontalStraightBandingIncrease=((parseFloat($('#horizontal-straight-banding-count').val()) * labor_billable) * <?php echo $settings['cornice_contrast_straight_banding_per_lf']; ?>);
			priceval=(parseFloat(priceval) + horizontalStraightBandingIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(horizontalStraightBandingIncrease));
			addOnText += '<tr><td>'+$('#horizontal-straight-banding-count').val()+'X Horizontal Straight Banding ('+labor_billable+' LF)</td><td>$'+roundToTwo(parseFloat(horizontalStraightBandingIncrease)).toFixed(2)+'</td></tr>';
		}

		if($('#horizontal-shaped-banding').is(':checked')){
			var horizontalShapedBandingIncrease=((parseFloat($('#horizontal-shaped-banding-count').val()) * labor_billable) * <?php echo $settings['cornice_contrast_shaped_banding_per_lf']; ?>);
			priceval=(parseFloat(priceval) + horizontalShapedBandingIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(horizontalShapedBandingIncrease));
			addOnText += '<tr><td>'+$('#horizontal-shaped-banding-count').val()+'X Horizontal Shaped Banding ('+labor_billable+' LF)</td><td>$'+roundToTwo(parseFloat(horizontalShapedBandingIncrease)).toFixed(2)+'</td></tr>';
		}


		//extra welts
		if($('#extra-welts').is(':checked')){
			var extraweltsIncrease=((parseFloat($('#extra-welts-count').val()) * labor_billable) * <?php echo $settings['wt_welt_covered_per_lf']; ?>);
			priceval=(parseFloat(priceval) + extraweltsIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(extraweltsIncrease));
			addOnText += '<tr><td>'+$('#extra-welts-count').val()+'X Extra Welts ('+labor_billable+' LF)</td><td>$'+roundToTwo(parseFloat(extraweltsIncrease)).toFixed(2)+'</td></tr>';
		}


		//tassels
		if($('#tassels').is(':checked')){
			var tasselsIncrease=(parseFloat($('#tassels-count').val()) * <?php echo $settings['drapery_rings_tassles_each']; ?>);
			priceval=(parseFloat(priceval)+tasselsIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(tasselsIncrease));
			addOnText += '<tr><td>'+$('#tassels-count').val()+'X Sewn-On Tassels</td><td>$'+roundToTwo(parseFloat(tasselsIncrease)).toFixed(2)+'</td></tr>';
		}



		//sewn on trim
		if($('#trim-sewn-on').is(':checked')){
			var trimsewnonIncrease=(parseFloat($('#trim-lf').val()) * <?php echo $settings['wt_trim_sewn_on_per_lf']; ?>);
			priceval=(parseFloat(priceval)+trimsewnonIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(trimsewnonIncrease));
			addOnText += '<tr><td>Sewn-On Trim ('+$('#trim-lf').val()+' LF)</td><td>$'+roundToTwo(parseFloat(trimsewnonIncrease)).toFixed(2)+'</td></tr>';
		}



		//drill holes
		if($('#drill-holes').is(':checked')){
			var drillholesIncrease=(parseFloat($('#drill-hole-count').val()) * <?php echo $settings['wt_drill_holes_each']; ?>);
			priceval=(parseFloat(priceval)+drillholesIncrease);
			addOnTotals = (parseFloat(addOnTotals) + parseFloat(drillholesIncrease));
			addOnText += '<tr><td>'+$('#drill-hole-count').val()+'X Drill Holes</td><td>$'+roundToTwo(parseFloat(drillholesIncrease)).toFixed(2)+'</td></tr>';
		}

        //hinges
		if($('#hinged').is(':checked')){
			var hingedIncrease = (parseFloat(<?php echo $settings['hinged_surcharge']; ?>) * parseInt($('#hingecount').val()));
			priceval = (parseFloat(priceval) + hingedIncrease);
			addOnTotals = (addOnTotals + hingedIncrease);
			addOnText += '<tr><td>'+$('#hingecount').val()+'X Hinged Surcharge</td><td>$'+roundToTwo(hingedIncrease).toFixed(2)+'</td></tr>';
		}
		
		
		//face >96 surcharge?
		if($('#face').val() > 96){
		    var bigfaceIncrease= (  (parseFloat(<?php echo $settings['cornice_face_greater_than_96']; ?>)/100) * lbr_pu);
		    priceval = (parseFloat(priceval) + bigfaceIncrease);
		    addOnTotals = (addOnTotals + bigfaceIncrease);
		    addOnText += '<tr><td>FACE &gt; 96in Surcharge</td><td>$'+roundToTwo(bigfaceIncrease).toFixed(2)+'</td></tr>';
		}
		
		//height (long point) >= 20 surcharge?
		if($('#height').val() >= 20){
		    var tallIncrease= (  (parseFloat(<?php echo $settings['cornice_height_20plus']; ?>)/100) * lbr_pu);
		    priceval = (parseFloat(priceval) + tallIncrease);
		    addOnTotals = (addOnTotals + tallIncrease);
		    addOnText += '<tr><td>HEIGHT &#8805; 20in Surcharge</td><td>$'+roundToTwo(tallIncrease).toFixed(2)+'</td></tr>';
		}
		

		$('#add-on-total-val').val(addOnTotals);
		$('#add-on-text-val').val(addOnText);


		$('#price').val(roundToTwo(priceval).toFixed(2));

		$('#total-surcharges').val(roundToTwo(addOnTotals).toFixed(2));


		$('#explainmath').html('<h3 id="pricebreakdown">Base Price Surcharge Breakdown</h3><hr><table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000"><tr><td>Starting Price (before surcharges/add-ons)</td><td><B>$'+roundToTwo(parseFloat($('#start-price-val').val())).toFixed(2)+'</B></td></tr>'+$('#add-on-text-val').val()+'<tr><td><b>RESULTING BASE PRICE:</b></td><td><b><u>$'+roundToTwo(parseFloat($('#price').val())).toFixed(2)+'</u></b></td></tr></table><Br><Br><Br><Br><Br><br><Br><Br><Br>');


		//end utr calculation
	}



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

		
				
		$('#warningbox').html('');
        var warningboxcontent='';
        var warncount=0;

		var heightLongErrors=0;
        var heightShortErrors=0;
        var heightLongWarnings=0
        var heightShortWarnings=0;

        var qtyErrors=0;
        var forceFullWidthsErrors=0;



        if($('#railroaded').is(':checked') && (parseFloat($('#qty').val()) > 1 && parseFloat($('#total-fabric-widths').val()) < parseFloat($('#qty').val()) )){
        	warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> MULTIPLE CORNICES PER WIDTH</span><br>';
        	$('#total-fabric-widths,#real-fabric-widths-needed,#cornices-per-cw-fabric').parent().addClass('alertcontent');
        	
        	qtyErrors++;
        	forceFullWidthsErrors++;

        	warncount++;
        }else{
        	$('#total-fabric-widths,#real-fabric-widths-needed,#cornices-per-cw-fabric').parent().removeClass('alertcontent');
        }



        if($('#cornice-type').val() != 'Straight' && ($('#fl-short').val() == '0' || $('#fl-short').val() == '')){
        	warningboxcontent += '<img src="/img/delete.png" /> MISSING HEIGHT (SHORT POINT)<br>';
        	heightShortErrors++;
        	warncount++;
        }


        if($('#cornice-type').val() != 'Straight' && ( parseFloat($('#fl-short').val()) >= parseFloat($('#height').val()) )){
        	warningboxcontent += '<img src="/img/delete.png" /> SHORT POINT MUST BE SMALLER THAN LONG POINT<br>';
        	heightShortErrors++;
        	heightLongErrors++;
        	warncount++;
        }



        if(($('#cornice-type').val() == 'Shaped' || $('#cornice-type').val() == 'Shaped Style A' || $('#cornice-type').val() == 'Shaped Style B' || $('#cornice-type').val() == 'Shaped Style C' || $('#cornice-type').val() == 'Shaped Style D') && !$('#welt-bottom').is(':checked')){
        	warningboxcontent += '<img src="/img/delete.png" /> SHAPED CORNICE MUST HAVE BOTTOM WELT<br>';
        	$('#welt-bottom').parent().parent().addClass('badvalue');
        	warncount++;
        }else{
        	$('#welt-bottom').parent().parent().removeClass('badvalue');
        }


		//warn2
		if(warn2 == 'FABRIC HALF-WIDTHS ARE NOT FEASIBLE'){
			warningboxcontent += '<span style=\"color:#D96D00 !important;\"><img src="/img/alert.png" /> FABRIC HALF-WIDTHS ARE NOT FEASIBLE</span><br>';

			$('#fab-width,#face').parent().addClass('alertcontent');
			qtyErrors++;
			forceFullWidthsErrors++;
			
	        warncount++;
		}else{
			$('#fab-width,#face').parent().removeClass('alertcontent');
		}



		//warn3
		if(warn3 == 'LINING HALF-WIDTHS ARE NOT FEASIBLE'){
			warningboxcontent += '<span style=\"color:#D96D00 !important;\"><img src="/img/alert.png" /> LINING HALF-WIDTHS ARE NOT FEASIBLE</span><br>';

			$('#force-full-lining-widths').parent().parent().addClass('alertcontent');

			warncount++;
		}else{
			$('#force-full-lining-widths').parent().parent().removeClass('alertcontent');
		}



			
		//warn 5
        if(warn5=="YOUR LINE TOTAL CONTAINS A HALF-WIDTH, PLEASE CONSIDER ROUNDING W EOL"){
       		warningboxcontent += '<img src="/img/delete.png" /> YOUR LINE TOTAL CONTAINS A HALF-WIDTH, PLEASE CONSIDER ROUNDING W EOL<br>';
       		$('#total-fabric-widths').parent().addClass('badvalue');
       		$('#force-round-total-widths-eol').parent().parent().addClass('badvalue');
       		warncount++;
        }else{
        	$('#total-fabric-widths').parent().removeClass('badvalue');
       		$('#force-round-total-widths-eol').parent().parent().removeClass('badvalue');
        }
		


        if(parseFloat($('#face').val()) > <?php echo $settings['standard_shipping_max_dimension']; ?> && !$('#hinged').prop('checked')){
        	warningboxcontent += '<img src="/img/delete.png" /> HINGES RECOMMENDED<br>';
        	$('#hinged').parent().parent().addClass('badvalue');
        	warncount++;
        }else{
        	$('#hinged').parent().parent().removeClass('badvalue');
        }



        if($('#brackets').is(':checked')){
			if($('#bracket-size').val() == ''){
				warningboxcontent += '<img src="/img/delete.png" /> BRACKET SIZE NOT SELECTED<br>';
				$('#brackets').parent().parent().addClass('badvalue');
				$('#bracket-size').parent().addClass('badvalue');
				warncount++;
			}else{
				$('#brackets').parent().parent().removeClass('badvalue');
				$('#bracket-size').parent().removeClass('badvalue');
			}

			if($('#bracket-count').val() == '0'){
				warningboxcontent += '<img src="/img/delete.png" /> BRACKET COUNT CANNOT BE ZERO<br>';
				$('#bracket-count').parent().addClass('badvalue');
				warncount++;
			}else{
				$('#bracket-count').parent().removeClass('badvalue');
			}
		}else{
			$('#brackets').parent().parent().removeClass('badvalue');
		}


		

		if(heightShortErrors > 0){
			$('#fl-short').parent().addClass('badvalue');
		}else{
			$('#fl-short').parent().removeClass('badvalue');
		}

		if(heightShortWarnings > 0){
			$('#fl-short').parent().addClass('alertcontent');
		}else{
			$('#fl-short').parent().removeClass('aertcontent');
		}



		if(heightLongErrors > 0){
			$('#height').parent().addClass('badvalue');
		}else{
			$('#height').parent().removeClass('badvalue');
		}

		if(heightLongWarnings > 0){
			$('#height').parent().addClass('alertcontent');
		}else{
			$('#height').parent().removeClass('aertcontent');
		}


		if(qtyErrors >0){
			$('#qty').parent().addClass('alertcontent');
		}else{
			$('#qty').parent().removeClass('alertcontent');
		}


		if(forceFullWidthsErrors > 0){
			$('#force-full-fabric-widths').parent().parent().addClass('alertcontent');
		}else{
			$('#force-full-fabric-widths').parent().parent().removeClass('alertcontent');
		}



        if(warncount == 0){
    	     $('#warningbox').hide('fast');
        }else{
             $('#warningbox').html(warningboxcontent).show('fast');
        }
				

}





function canCalculate(){
	var errorcount=0;
	
	if($('#qty').val()=='0' || $('#qty').val()==''){
		//console.log('Cannot calculate without a Qty value');
		$('#qty').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#qty').removeClass('notvalid').removeClass('validated').addClass('validated');
	}


	if($('#face').val()=='0' || $('#face').val()==''){
		//console.log('Cannot calculate without a Face value');
		$('#face').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#face').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	
	
	if($('#height').val()=='0' || $('#height').val()==''){
		//console.log('Cannot calculate without a Height value');
		$('#height').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#height').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	
	


	if($('#return').val()==''){
		//console.log('Cannot calculate without a Return value');
		$('#return').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#return').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	
	
	
	if($('#fab-width').val() == '0' || $('#fab-width').val()==''){
		//console.log('Cannot calculate without a Fabric Width value');
		$('#fab-width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#fab-width').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	
		
	
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
	


	if($('#linings_id').val() == ''){
		$('#linings_id').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#linings_id').removeClass('notvalid').removeClass('validated').addClass('validated');
	}

	
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
	if(errorcount > 0){
		return false;
	}else{
		return true;
	}
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
	
	
	
	$('#railroaded').click(function(){
		if($(this).is(':checked')){
			//railroaded
			$('#rawfabwwrap').hide('fast');
			$('#rawlinwwrap').hide('fast');
			$('#verticalrepeatwrap').hide('fast');
			$('#fabricwastefromvrptwrap').hide('fast');
			$('#fabrichalfwidthsstatuswrap').hide('fast');
			$('#realfabricwidthsneededwrap').show('fast');
			$('#feasiblecorniceswrap').show('fast');
		}else{
			//utr
			$('#rawfabwwrap').show('fast');
			$('#rawlinwwrap').show('fast');
			$('#verticalrepeatwrap').show('fast');
			$('#fabricwastefromvrptwrap').show('fast');
			$('#fabrichalfwidthsstatuswrap').show('fast');
			$('#realfabricwidthsneededwrap').hide('fast');
			$('#feasiblecorniceswrap').hide('fast');
		}
	});
	
	
	$('#railroaded').change(function(){
		if($(this).is(':checked')){
			//railroaded
			$('#rawfabwwrap').hide('fast');
			$('#rawlinwwrap').hide('fast');
			$('#verticalrepeatwrap').hide('fast');
			$('#fabricwastefromvrptwrap').hide('fast');
			$('#fabrichalfwidthsstatuswrap').hide('fast');
			$('#realfabricwidthsneededwrap').show('fast');
			$('#feasiblecorniceswrap').show('fast');
		}else{
			//utr
			$('#rawfabwwrap').show('fast');
			$('#rawlinwwrap').show('fast');
			$('#verticalrepeatwrap').show('fast');
			$('#fabricwastefromvrptwrap').show('fast');
			$('#fabrichalfwidthsstatuswrap').show('fast');
			$('#realfabricwidthsneededwrap').hide('fast');
			$('#feasiblecorniceswrap').hide('fast');
		}
	});
	

<?php
if($userData['scroll_calcs'] == 1){
?>
correctColHeights();
setInterval('correctColHeights()',500);
<?php } ?>



	$('#individual-nailheads').click(function(){
		if($(this).is(':checked')){
			$('#individualNailheadsWrap').show('fast');
		}else{
			$('#individualNailheadsWrap').hide('fast');
		}
	});

	$('#individual-nailheads').change(function(){
		if($(this).is(':checked')){
			$('#individualNailheadsWrap').show('fast');
		}else{
			$('#individualNailheadsWrap').hide('fast');
		}
	});



	$('#nailhead-trim').click(function(){
		if($(this).is(':checked')){
			$('#nailheadTrimWrap').show('fast');
		}else{
			$('#nailheadTrimWrap').hide('fast');
		}
	});


	$('#nailhead-trim').change(function(){
		if($(this).is(':checked')){
			$('#nailheadTrimWrap').show('fast');
		}else{
			$('#nailheadTrimWrap').hide('fast');
		}
	});


	$('#brackets').click(function(){
		if($(this).is(':checked')){
			$('#bracketdetailwrap').show('fast');
		}else{
			$('#bracketdetailwrap').hide('fast');
		}
	});

	$('#brackets').change(function(){
		if($(this).is(':checked')){
			$('#bracketdetailwrap').show('fast');
		}else{
			$('#bracketdetailwrap').hide('fast');
		}
	});



	$('#com-fabric').change(function(){
		calculateLabor();
	});

	$('#com-fabric').click(function(){
		calculateLabor();
	});


	$('#covered-buttons').click(function(){
		if($(this).is(':checked')){
			$('#coveredbuttonscountwrap').show('fast');
		}else{
			$('#coveredbuttonscountwrap').hide('fast');
		}
	});


	$('#covered-buttons').change(function(){
		if($(this).is(':checked')){
			$('#coveredbuttonscountwrap').show('fast');
		}else{
			$('#coveredbuttonscountwrap').hide('fast');
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




	$('#com-fabric').change(function(){
		calculateLabor();
		if($(this).prop('checked')){
		    		/*PPSA-33start */	$('#fabric-cost-per-yard-custom-value').val('');/*PPSA-33 end */

			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated');
			if(canCalculate()){
				doCalculation();
			}
		}
	});

	$('#com-fabric').click(function(){
		calculateLabor();
		if($(this).prop('checked')){
		    		/*PPSA-33start */	$('#fabric-cost-per-yard-custom-value').val('');/*PPSA-33 end */

			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated');
			if(canCalculate()){
				doCalculation();
			}
		}
	});



	$('#welt-top,#welt-bottom').click(function(){
		calculateLabor();
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});


	$('#cornice-type').change(function(){
		$('#welt-bottom').prop('checked',true);

		if($(this).val() == 'Shaped' ||$(this).val() == 'Shaped Style A' || $(this).val() == 'Shaped Style B' || $(this).val() == 'Shaped Style C' || $(this).val() == 'Shaped Style D'){
			$('#flshortwrap').show('fast');
			$('#flmidwrap').show('fast');
		}else{
			$('#flshortwrap').hide('fast');
			$('#flmidwrap').hide('fast');
		}

		calculateLabor();
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});


	$('.calculatebutton button').click(function(){
	
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}

		
	});
	
	$('#calcformleft input,#calcformleft select').keyup(function(){
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});
	
	$('#calcformleft input,#calcformleft select').change(function(){
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
	

	$('#install_surcharge').click(function(){
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});


	
	$('#cancelbutton').click(function(){
		location.replace('/quotes/add/<?php echo $quoteID; ?>');
	});



	$('#linings_id').change(function(){
		$('#lining-price-per-yd').val($(this).find('option:selected').attr('data-price'));
		if(canCalculate()){
			doCalculation();
		}
	});


//process additional checkbox click functionality

	$('#trim-sewn-on').click(function(){
		if($(this).is(':checked')){
			$('#trimlfwrap').show('fast');
		}else{
			$('#trimlfwrap').hide('fast');
		}
	});


	$('#trim-sewn-on').change(function(){
		if($(this).is(':checked')){
			$('#trimlfwrap').show('fast');
		}else{
			$('#trimlfwrap').hide('fast');
		}
	});


	$('#rings').click(function(){
		if($(this).is(':checked')){
			$('#ringscountwrap').show('fast');
		}else{
			$('#ringscountwrap').hide('fast');
		}
	});


	$('#rings').change(function(){
		if($(this).is(':checked')){
			$('#ringscountwrap').show('fast');
		}else{
			$('#ringscountwrap').hide('fast');
		}
	});





	$('#tassels').click(function(){
		if($(this).is(':checked')){
			$('#tasselscountwrap').show('fast');
		}else{
			$('#tasselscountwrap').hide('fast');
		}
	});


	$('#tassels').change(function(){
		if($(this).is(':checked')){
			$('#tasselscountwrap').show('fast');
		}else{
			$('#tasselscountwrap').hide('fast');
		}
	});




	$('#horizontal-straight-banding').click(function(){
		if($(this).is(':checked')){
			$('#horizontalstraightbandingcountwrap').show('fast');
		}else{
			$('#horizontalstraightbandingcountwrap').hide('fast');
		}
	});


	$('#horizontal-straight-banding').change(function(){
		if($(this).is(':checked')){
			$('#horizontalstraightbandingcountwrap').show('fast');
		}else{
			$('#horizontalstraightbandingcountwrap').hide('fast');
		}
	});





	$('#horizontal-shaped-banding').click(function(){
		if($(this).is(':checked')){
			$('#horizontalshapedbandingcountwrap').show('fast');
		}else{
			$('#horizontalshapedbandingcountwrap').hide('fast');
		}
	});


	$('#horizontal-shaped-banding').change(function(){
		if($(this).is(':checked')){
			$('#horizontalshapedbandingcountwrap').show('fast');
		}else{
			$('#horizontalshapedbandingcountwrap').hide('fast');
		}
	});



	


	$('#extra-welts').click(function(){
		if($(this).is(':checked')){
			$('#extraweltscountwrap').show('fast');
		}else{
			$('#extraweltscountwrap').hide('fast');
		}
	});


	$('#extra-welts').change(function(){
		if($(this).is(':checked')){
			$('#extraweltscountwrap').show('fast');
		}else{
			$('#extraweltscountwrap').hide('fast');
		}
	});


	$('#hinged').click(function(){
	   if($(this).is(':checked')){
	       $('#hingecountwrap').show('fast');
	   }else{
	       $('#hingecountwrap').hide('fast');
	   }
	});

	$('#drill-holes').click(function(){
		if($(this).is(':checked')){
			$('#drillholescountwrap').show('fast');
		}else{
			$('#drillholescountwrap').hide('fast');
		}
	});


	$('#drill-holes').change(function(){
		if($(this).is(':checked')){
			$('#drillholescountwrap').show('fast');
		}else{
			$('#drillholescountwrap').hide('fast');
		}
	});





<?php
if(isset($isedit) && $isedit=='1'){
//do not set default

}else{
?>
	//$('#linings_id').val('2');
	$('#lining-price-per-yd').val($('#linings_id option[value=2]').attr('data-price'));
<?php } ?>

	if(canCalculate()){	
		$('#cannotcalculate').hide();
		doCalculation(); 
	}else{
		$('#cannotcalculate').show();
	}

});



</script>

<Br><Br><Br><Br>
<div id="explainmath"></div>