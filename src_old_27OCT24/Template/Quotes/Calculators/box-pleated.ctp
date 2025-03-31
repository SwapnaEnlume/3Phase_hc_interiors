<style>
body{font-family:'Helvetica',Arial,sans-serif; font-size:medium;}
h1{ text-align:center; font-size:x-large; font-weight:bold; color:#1F2E67; margin:15px 0 0 0; }
form{ text-align:center; width:95%; max-width:750px; margin:20px auto; }
	
form label{ font-weight:bold; float:left; width:75%; text-align:left; font-size:small; vertical-align:middle; }
form input{ float:right; padding:5px !important; height:auto !important; width:20% !important; vertical-align:middle; font-size:12px; margin-bottom:0 !important; }
form select{ float:right; padding:5px !important; height:auto !important; width:22% !important; vertical-align:middle; font-size:12px; margin-bottom:0 !important; }
form div.input{ clear:both; padding:10px 0; }
#calculatedPrice{ clear:both; padding:20px 0; font-size:x-large;  }

#cannotcalculate{ color:red; text-align: center; font-weight:normal; padding:5px; font-size:12px; display:block; border:1px solid red; background:#FDDDDE; }
	

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


label[for=total-fabric-widths]{ display:none; }
#total-fabric-widths{ resize:none; font-family:'Helvetica',Arial,sans-serif; width: 91% !important; height: 40px; font-size:12px; text-align:center; background:transparent; border:0; }

label[for=yds-per-unit]{ width:40% !important; }
#yds-per-unit{ width:54% !important; font-size:12px; }

label[for=fabric-cost]{ width:40% !important; }
#fabric-cost{ width:54% !important; font-size:12px; }

#explainmath{ max-width:650px; margin:0 auto; text-align:center; }

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


label[for=lining-half-width-status]{ display:none; }
#lining-half-width-status{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }



label[for=fabric-half-width-status]{ display:none; }
#fabric-half-width-status{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }



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
	echo "<h1>Standalone BPV Calculator</h1><hr>";
}

echo $this->Form->create();
echo "<div id=\"calcformleft\">";

//echo "<h2>BPV - ".$fabricData['fabric_name']." (".$fabricData['color'].")</h2>";
echo "<h2>Box-Pleated Valance</h2>";

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

echo $this->Form->input('calculator-used',['type'=>'hidden','value'=>'box-pleated']);

echo $this->Form->input('fabricid',['type'=>'hidden','value'=>$fabricid]);


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

echo $this->Form->input('location',['label'=>'Location','value'=>$locationValue]);





if(isset($thisItemMeta['valance-type']) && strlen(trim($thisItemMeta['valance-type'])) >0){
	$valancetypeval=$thisItemMeta['valance-type'];
}else{
	$valancetypeval='Box Pleated';
}
echo "<div class=\"input selectbox\">
<label for=\"valance-type\">Valance Type</label>";
echo $this->Form->select('valance-type',[
	'Box Pleated'=>'Box Pleated',
	'Tailored' => 'Tailored',
	'Angled Tailored'=>'Angled Tailored',
	'Scalloped' => 'Scalloped'
	],['id'=>'valance-type','value'=>$valancetypeval]);
echo "</div>";




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
//echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','min'=>0,'value'=>$qtyval,'autocomplete'=>'off']);

if($ordermode == 'workorder'  && isset($thisItemMeta['qty']))
echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','min'=>0,'value'=>$qtyval,'autocomplete'=>'off', 'readonly'=>true]);
else 
echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','min'=>0,'value'=>$qtyval,'autocomplete'=>'off']);


if($ordermode == 'workorder' && !isset($thisItemMeta['so_line_number'])){
    
        $soLineNumberLists=array();
        foreach($soLineNumberList as $subclass){
            $soLineNumberLists[$subclass['line_number']]=$subclass['line_number'];
        }

    echo $this->Form->label('SoLineNumber');
   
    echo $this->Form->select('so_line_number',$soLineNumberLists,['required'=>false,'empty'=>'--Select SO Line Number--','value'=>$thisItemMeta['so_line_number']]);
}elseif($ordermode == 'workorder'){
    echo $this->Form->label('SOLineNumber');
     $soLineNumberLists=array();
        foreach($soLineNumberList as $subclass){
            $soLineNumberLists[$subclass['line_number']]=$subclass['line_number'];
        }

    echo $this->Form->select('so_line_number',$soLineNumberLists,['disabled'=>true,'empty'=>'--Select SO Line Number--','value'=>$thisItemMeta['so_line_number']]);
}



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
	if(isset($thisItemMeta['valance-type']) && ($thisItemMeta['valance-type'] != 'Box Pleated' && $thisItemMeta['valance-type'] != 'Tailored')){
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
	if(isset($thisItemMeta['valance-type']) && ($thisItemMeta['valance-type'] != 'Box Pleated' && $thisItemMeta['valance-type'] != 'Tailored')){
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



if(isset($thisItemMeta['pleats']) && is_numeric($thisItemMeta['pleats'])){
	$pleatsval=$thisItemMeta['pleats'];
}else{
	$pleatsval='0';
}
echo $this->Form->input('pleats',['label'=>'Pleats','type'=>'number','min'=>0,'step'=>'any','value'=>$pleatsval]);

echo "</fieldset>";





echo "<fieldset class=\"fieldsection\"><legend>FABRIC SPECS &amp; PRICING</legend>";

if(isset($thisItemMeta['fabric-width']) && is_numeric($thisItemMeta['fabric-width'])){
	$fabricwidthval=$thisItemMeta['fabric-width'];
}else{
	$fabricwidthval=$fabricData['fabric_width'];
}
echo $this->Form->input('fabric-width',['type'=>'number','min'=>'0','step'=>'any','value'=>$fabricwidthval,'label'=>'Fabric Width']);




if(isset($thisItemMeta['vert-repeat']) && is_numeric($thisItemMeta['vert-repeat'])){
	$vertrepeatval=$thisItemMeta['vert-repeat'];
}else{
	$vertrepeatval=$fabricData['vertical_repeat'];
}
echo $this->Form->input('vert-repeat',['label'=>'Vert Repeat','type'=>'number','min'=>0,'step'=>'any','value'=>$vertrepeatval]);





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
echo "<div><input type=\"number\" name=\"inbound-freight\" readonly=\"readonly\" step=\"any\" min=\"0\" required=\"required\" id=\"inbound-freight\" value=\"".$inboundfreightval."\" /></div>";
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
echo "<div><input type=\"number\" name=\"fabric-markup\"  step=\"any\" min=\"0\" required=\"required\" id=\"fabric-markup\" value=\"".$markupval."\" readonly=\"readonly\" /></div>";
echo "<div><input type=\"number\" name=\"fabric-markup-custom-value\" step=\"any\" min=\"0\" id=\"fabric-markup-custom-value\" value=\"".$markupcustomval."\" placeholder=\"Override\" /></div>";
echo "</div></div>";

echo "</fieldset>";




echo "<fieldset class=\"fieldsection\"><legend>LINING SPECS &amp; PRICING</legend>";

echo "<div class=\"input selectbox\">";
echo "<label>Lining</label>";

echo "<select name=\"linings_id\" id=\"linings_id\"><option value=\"\" data-price=\"0.00\">--Select Lining--</option>";
foreach($linings as $lining){
	echo "<option value=\"".$lining['id']."\" data-price=\"".number_format($lining['price'],2,'.',',')."\"";
	if($thisItemMeta['linings_id'] == $lining['id']){
		echo " selected=\"selected\"";
	}
	echo ">".$lining['short_title']."</option>";
}
echo "</select>";

echo "</div>";






if(isset($thisItemMeta['lining-price-per-yd']) && is_numeric($thisItemMeta['lining-price-per-yd'])){
	$liningpriceperydval=$thisItemMeta['lining-price-per-yd'];
}else{
	$liningpriceperydval=$settings['lining_price_per_yard'];
}
echo $this->Form->input('lining-price-per-yd',['label'=>'Lining Price per yd','type'=>'number','min'=>0,'step'=>'any','value'=>$liningpriceperydval]);



if(isset($thisItemMeta['lining-width']) && is_numeric($thisItemMeta['lining-width'])){
	$liningwidthval=$thisItemMeta['lining-width'];
}else{
	$liningwidthval='54';
}
echo $this->Form->input('lining-width',['label'=>'Lining Width','type'=>'number','min'=>0,'step'=>'any','value'=>$liningwidthval]);

echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>LABOR</legend>";

if(isset($thisItemMeta['labor-per-lf']) && is_numeric($thisItemMeta['labor-per-lf'])){
	$laborperlfval=number_format($thisItemMeta['labor-per-lf'],2,'.','');
}else{
	$laborperlfval=number_format($settings['bpv_labor_cost_mom'],2,'.','');
}
echo $this->Form->input('labor-per-lf',['value'=>$laborperlfval,'label'=>'Labor per linear foot']);



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

$getbracketsizes=explode('|',$settings['angle_iron_bracket_sizes']);
$bracketsizes=array();
foreach($getbracketsizes as $bracketsize){
	$bracketsizes[$bracketsize]=$bracketsize;
}

echo "<div id=\"bracketdetailwrap\" class=\"suboptions\" style=\"display:".$bracketwrapdisplay.";\">";
	echo "<div class=\"input selectbox\">";
	echo $this->Form->label('Size');
	echo $this->Form->select('bracket-size',$bracketsizes,['empty'=>'Select Size','id'=>'bracket-size','value'=>$thisItemMeta['bracket-size']]);
	echo "</div>";

if(isset($thisItemMeta['bracket-count'])){
	$bracketcountval=$thisItemMeta['bracket-count'];
}else{
	$bracketcountval='0';
}

	echo $this->Form->input('bracket-count',['type'=>'number','value'=>$bracketcountval,'min'=>'0']);
echo "</div>";



if((isset($thisItemMeta['has-board']) && $thisItemMeta['has-board'] == '1') || !isset($thisItemMeta['has-board'])){
	$boardChecked=true;
	$boardwrapdisplay='block';
}else{
	$boardChecked=false;
	$boardwrapdisplay='none';
}
echo $this->Form->input('has-board',['type'=>'checkbox','label'=>'Has Board?','checked'=>$boardChecked]);

echo "<div class=\"suboptions\" id=\"boarddetailwrap\" style=\"display:".$boardwrapdisplay.";\">";
$getboardsizes=explode('|',$settings['valance_board_sizes']);
$boardsizes=array();

foreach($getboardsizes as $boardsize){
	$boardsizes[$boardsize]=$boardsize;
}

echo "<div class=\"input selectbox\">";
echo $this->Form->label('Board Sizes');
echo $this->Form->select('board-size',$boardsizes,['empty'=>'Select Size','id'=>'board-size','value'=>$thisItemMeta['board-size']]);
echo "</div>";

echo "</div>";




if((isset($thisItemMeta['has-board-attachment']) && $thisItemMeta['has-board-attachment'] == '1') || !isset($thisItemMeta['has-board-attachment'])){
	$boardAttachChecked=true;
	$boardattachwrapdisplay='block';
}else{
	$boardAttachChecked=false;
	$boardattachwrapdisplay='none';
}
echo $this->Form->input('has-board-attachment',['type'=>'checkbox','label'=>'Board Attachment?','checked'=>$boardAttachChecked]);

echo "<div class=\"suboptions\" id=\"boardattachmentwrap\" style=\"display:".$boardattachwrapdisplay.";\">";
$getboardattachments=explode("|",$settings['valance_board_attachment']);
$boardattachments=array();
foreach($getboardattachments as $boardatt){
	$boardattachments[$boardatt]=$boardatt;
}

echo "<div class=\"input selectbox\">";
echo $this->Form->label('Board Attachment');
echo $this->Form->select('board-attachment',$boardattachments,['empty'=>'Select Attachment','id'=>'board-attachment','value'=>$boardattachments]);
echo "</div>";

echo "</div>";

echo "</fieldset>";




echo "<fieldset class=\"fieldsection\"><legend>FABRIC ROUNDING</legend>";



if(isset($thisItemMeta['force-fabric-full-widths']) && $thisItemMeta['force-fabric-full-widths']=='1'){
	$forcefabricfullwidthsChecked=true;
}else{
	$forcefabricfullwidthsChecked=false;
}
echo $this->Form->input('force-fabric-full-widths',['type'=>'checkbox','label'=>'Force full widths ea BPV','checked'=>$forcefabricfullwidthsChecked]);




if(isset($thisItemMeta['rounded-widths-end-of-line']) && $thisItemMeta['rounded-widths-end-of-line']=='1'){
	$roundedwidthsendoflineChecked=true;
}else{
	$roundedwidthsendoflineChecked=false;
}
echo $this->Form->input('rounded-widths-end-of-line',['type'=>'checkbox','label'=>'Force full widths EOL','checked'=>$roundedwidthsendoflineChecked]);




if(isset($thisItemMeta['rounded-yds-end-of-line']) && $thisItemMeta['rounded-yds-end-of-line']=='1'){
	$roundedydsendoflineChecked=true;
}else{
	$roundedydsendoflineChecked=false;
}
echo $this->Form->input('rounded-yds-end-of-line',['type'=>'checkbox','label'=>'Force rounded yds each BPV','checked'=>$roundedydsendoflineChecked]);




if(isset($thisItemMeta['distributed-rounded-yds']) && $thisItemMeta['distributed-rounded-yds']=='1'){
	$distributedroundedydsChecked=true;
}else{
	$distributedroundedydsChecked=false;
}
echo $this->Form->input('distributed-rounded-yds',['type'=>'checkbox','label'=>'Distributed Rounded yds EOL','checked'=>$distributedroundedydsChecked]);



echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>LINING ROUNDING</legend>";

if(isset($thisItemMeta['force-lining-full-widths']) && $thisItemMeta['force-lining-full-widths']=='1'){
	$forceliningfullwidthsChecked=true;
}else{
	$forceliningfullwidthsChecked=false;
}
echo $this->Form->input('force-lining-full-widths',['type'=>'checkbox','label'=>'Force Lining Full Widths?','checked'=>$forceliningfullwidthsChecked]);



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



if(isset($thisItemMeta['inches-used-by-pleat']) && is_numeric($thisItemMeta['inches-used-by-pleat'])){
	$inchesusedbypleatval=$thisItemMeta['inches-used-by-pleat'];
}else{
	$inchesusedbypleatval='12';
}
echo $this->Form->input('inches-used-by-pleat',['label'=>'Inches used by Pleat','type'=>'number','min'=>0,'step'=>'any','value'=>$inchesusedbypleatval]);





if(isset($thisItemMeta['straight-banding']) && $thisItemMeta['straight-banding']=='1'){
	$straightbandingChecked=true;
	$hbandingmathwrapdisplay="block";
}else{
	$straightbandingChecked=false;
	$hbandingmathwrapdisplay="none";
}
echo $this->Form->input('straight-banding',['type'=>'checkbox','label'=>'Horiz Straight Banding?','checked'=>$straightbandingChecked]);



echo "<div id=\"hbandingmathwrap\" class=\"suboptions\" style=\"display:".$hbandingmathwrapdisplay.";\">";

if(isset($thisItemMeta['straight-banding-yards']) && strlen(trim($thisItemMeta['straight-banding-yards'])) >0){
	$straightBandingYardsVal=$thisItemMeta['straight-banding-yards'];
}else{
	$straightBandingYardsVal='0';
}
echo $this->Form->input('straight-banding-yards',['type'=>'number','step'=>'any','label' => 'Yards each','value'=>$straightBandingYardsVal]);


if(isset($thisItemMeta['straight-banding-price-per-yard']) && strlen(trim($thisItemMeta['straight-banding-price-per-yard'])) >0){
	$straightBandingPricePerYardVal=$thisItemMeta['straight-banding-price-per-yard'];
}else{
	$straightBandingPricePerYardVal='0';
}
echo $this->Form->input('straight-banding-price-per-yard',['type'=>'number','step'=>'any','label' => 'Price per yard','value'=>$straightBandingPricePerYardVal]);

echo "</div>";





if(isset($thisItemMeta['shaped-banding']) && $thisItemMeta['shaped-banding']=='1'){
	$shapedbandingChecked=true;
	$hshapedbandingmathwrapdisplay="block";
}else{
	$shapedbandingChecked=false;
	$hshapedbandingmathwrapdisplay="none";
}
echo $this->Form->input('shaped-banding',['type'=>'checkbox','label'=>'Horiz Shaped Banding?','checked'=>$shapedbandingChecked]);


echo "<div id=\"hshapedbandingmathwrap\" class=\"suboptions\" style=\"display:".$hshapedbandingmathwrapdisplay.";\">";

if(isset($thisItemMeta['shaped-banding-yards']) && strlen(trim($thisItemMeta['shaped-banding-yards'])) >0){
	$shapedBandingYardsVal=$thisItemMeta['shaped-banding-yards'];
}else{
	$shapedBandingYardsVal='0';
}
echo $this->Form->input('shaped-banding-yards',['type'=>'number','step'=>'any','label' => 'Yards each','value'=>$shapedBandingYardsVal]);


if(isset($thisItemMeta['shaped-banding-price-per-yard']) && strlen(trim($thisItemMeta['shaped-banding-price-per-yard'])) >0){
	$shapedBandingPricePerYardVal=$thisItemMeta['shaped-banding-price-per-yard'];
}else{
	$shapedBandingPricePerYardVal='0';
}
echo $this->Form->input('shaped-banding-price-per-yard',['type'=>'number','step'=>'any','label' => 'Price per yard','value'=>$shapedBandingPricePerYardVal]);

echo "</div>";






if(isset($thisItemMeta['trim-sewn-on']) && $thisItemMeta['trim-sewn-on']=='1'){
	$trimsewnonChecked=true;
	$trimsewnonmathwrapdisplay='block';
}else{
	$trimsewnonChecked=false;
	$trimsewnonmathwrapdisplay='none';
}
echo $this->Form->input('trim-sewn-on',['type'=>'checkbox','label'=>'Horiz Sewn-On Trim?','checked'=>$trimsewnonChecked]);



echo "<div id=\"trimsewnonmathwrap\" class=\"suboptions\" style=\"display:".$trimsewnonmathwrapdisplay."\">";
if(isset($thisItemMeta['trim-yards-each']) && strlen(trim($thisItemMeta['trim-yards-each'])) >0){
	$trimYardsEachVal=$thisItemMeta['trim-yards-each'];
}else{
	$trimYardsEachVal='0';
}
echo $this->Form->input('trim-yards-each',['type'=>'number','step'=>'any','label'=>'Yards each','value'=>$trimYardsEachVal]);

if(isset($thisItemMeta['trim-price-per-yard']) && strlen(trim($thisItemMeta['trim-price-per-yard'])) >0){
	$trimPricePerYardVal=$thisItemMeta['trim-price-per-yard'];
}else{
	$trimPricePerYardVal='0';
}
echo $this->Form->input('trim-price-per-yard',['type'=>'number','step'=>'any','label'=>'Price per yard','value'=>$trimPricePerYardVal]);

echo "</div>";




if(isset($thisItemMeta['welt-covered-in-fabric']) && $thisItemMeta['welt-covered-in-fabric']=='1'){
	$weltcoveredinfabricChecked=true;
	$weltmathwrapdisplay='block';
}else{
	$weltcoveredinfabricChecked=false;
	$weltmathwrapdisplay='none';
}
echo $this->Form->input('welt-covered-in-fabric',['type'=>'checkbox','label'=>'Welt Covered in Fabric?','checked'=>$weltcoveredinfabricChecked]);


echo "<div id=\"weltmathwrap\" style=\"display:".$weltmathwrapdisplay."\" class=\"suboptions\">";

if(isset($thisItemMeta['welts-same-fabric']) && $thisItemMeta['welts-same-fabric'] == '1'){
	$weltSameFabricChecked=true;
	$weltyardagesdisplay='none';

}else{
	$weltSameFabricChecked=false;
	$weltyardagesdisplay='block';
}
echo $this->Form->input('welts-same-fabric',['type'=>'checkbox','label'=>'Same Fabric as Main?','checked'=>$weltSameFabricChecked]);

echo "<div id=\"weltyardageswrap\" style=\"display:".$weltyardagesdisplay.";\">";

if(isset($thisItemMeta['welt-yards-each']) && strlen(trim($thisItemMeta['welt-yards-each'])) >0){
	$weltYardsEachVal=$thisItemMeta['welt-yards-each'];
}else{
	$weltYardsEachVal=0;
}
echo $this->Form->input('welt-yards-each',['type'=>'number','step'=>'any','label'=>'Yards per valance','value'=>$weltYardsEachVal]);

if(isset($thisItemMeta['welt-price-per-yard']) && strlen(trim($thisItemMeta['welt-price-per-yard'])) >0){
	$weltPricePerYardVal=$thisItemMeta['welt-price-per-yard'];
}else{
	$weltPricePerYardVal='0';
}
echo $this->Form->input('welt-price-per-yard',['type'=>'number','step'=>'any','label'=>'Price per yard','value'=>$weltPricePerYardVal]);
echo "</div>";

echo "</div>";





if(isset($thisItemMeta['contrast-fabric-inside-pleat']) && $thisItemMeta['contrast-fabric-inside-pleat']=='1'){
	$contrastfabricinsidepleatChecked=true;
	$contrastfabpleatwrapdisplay='block';
}else{
	$contrastfabricinsidepleatChecked=false;
	$contrastfabpleatwrapdisplay='none';
}
echo $this->Form->input('contrast-fabric-inside-pleat',['type'=>'checkbox','label'=>'Contrast Fabric Inside Pleat?','checked'=>$contrastfabricinsidepleatChecked]);



echo "<div id=\"contrastfabpleatwrap\" class=\"suboptions\" style=\"display:".$contrastfabpleatwrapdisplay.";\">";
if(isset($thisItemMeta['contrast-fab-inside-pleat-yards-each']) && strlen(trim($thisItemMeta['contrast-fab-inside-pleat-yards-each'])) >0){
	$contrastFabInsidePleatYardsVal=$thisItemMeta['contrast-fab-inside-pleat-yards-each'];
}else{
	$contrastFabInsidePleatYardsVal='0';
}
echo $this->Form->input('contrast-fab-inside-pleat-yards-each',['type'=>'number','step'=>'any','label'=>'Yards per valance','value'=>$contrastFabInsidePleatYardsVal]);

if(isset($thisItemMeta['contrast-fab-inside-pleat-price-per-yard']) && strlen(trim($thisItemMeta['contrast-fab-inside-pleat-price-per-yard'])) >0){
	$contrastFabInsidePleatPriceVal=$thisItemMeta['contrast-fab-inside-pleat-price-per-yard'];
}else{
	$contrastFabInsidePleatPriceVal='0';
}
echo $this->Form->input('contrast-fab-inside-pleat-price-per-yard',['type'=>'number','step'=>'any','label'=>'Price per yard','value'=>$contrastFabInsidePleatPriceVal]);

echo "</div>";





if(isset($thisItemMeta['extra-inches-on-height']) && is_numeric($thisItemMeta['extra-inches-on-height'])){
	$extrainchesonheightval=$thisItemMeta['extra-inches-on-height'];
}else{
	$extrainchesonheightval=$settings['bpv_extra_inches_height'];
}
echo $this->Form->input('extra-inches-on-height',['label'=>'Extra Inches on Height','type'=>'number','min'=>0,'step'=>'any','value'=>$extrainchesonheightval]);



if(isset($thisItemMeta['wraparound-inches']) && is_numeric($thisItemMeta['wraparound-inches'])){
	$wraparoundinchesval=$thisItemMeta['wraparound-inches'];
}else{
	$wraparoundinchesval=$settings['bpv_wraparound_inches'];
}
echo $this->Form->input('wraparound-inches',['label'=>'Wraparound Inches','type'=>'number','min'=>0,'step'=>'any','value'=>$wraparoundinchesval]);








if(isset($thisItemMeta['tolerance-on-widths']) && is_numeric($thisItemMeta['tolerance-on-widths'])){
	$toleranceonwidthsval=$thisItemMeta['tolerance-on-widths'];
}else{
	$toleranceonwidthsval='0.5';
}
echo $this->Form->input('tolerance-on-widths',['label'=>'Tolerance on Widths','type'=>'number','min'=>0,'step'=>'any','value'=>$toleranceonwidthsval]);







echo "</div>";
//expand/collapse end




echo "</div>";
?>

<div id="resultsblock">
<h2>Calculator Results</h2>

<?php
if(isset($thisItemMeta['visible-width-dimension']) && strlen(trim($thisItemMeta['visible-width-dimension'])) > 0){
	$visiblewidthdimensionval=$thisItemMeta['visible-width-dimension'];
}else{
	$visiblewidthdimensionval='';
}
echo $this->Form->input('visible-width-dimension',['label'=>'Visible Width Dimension','readonly'=>true,'value'=>$visiblewidthdimensionval]);
	

if(isset($thisItemMeta['pleat-usage']) && strlen(trim($thisItemMeta['pleat-usage'])) > 0){
	$pleatusageval=$thisItemMeta['pleat-usage'];
}else{
	$pleatusageval='';
}
echo $this->Form->input('pleat-usage',['label'=>'Pleat Usage','readonly'=>true,'value'=>$pleatusageval]);
	
	
	
if(isset($thisItemMeta['val-x']) && strlen(trim($thisItemMeta['val-x'])) > 0){
	$valxval=$thisItemMeta['val-x'];
}else{
	$valxval='';
}	
echo $this->Form->input('val-x',['label'=>'VAL_X','readonly'=>true,'value'=>$valxval]);
	


if(isset($thisItemMeta['blf']) && strlen(trim($thisItemMeta['blf'])) > 0){
	$blfval=$thisItemMeta['blf'];
}else{
	$blfval='';
}	
echo $this->Form->input('blf',['label'=>'Banding LF','readonly'=>true,'value'=>$blfval]);

	
if(isset($thisItemMeta['wlf']) && strlen(trim($thisItemMeta['wlf'])) > 0){
	$wlfval=$thisItemMeta['wlf'];
}else{
	$wlfval='';
}	
echo $this->Form->input('wlf',['type'=>'hidden','label'=>'Welt LF','readonly'=>true,'value'=>$wlfval]);
	


if(isset($thisItemMeta['raw-widths-of-fabric']) && strlen(trim($thisItemMeta['raw-widths-of-fabric'])) > 0){
	$rawwidthsoffabricval=$thisItemMeta['raw-widths-of-fabric'];
}else{
	$rawwidthsoffabricval='';
}
echo $this->Form->input('raw-widths-of-fabric',['label'=>'Raw Widths of Fabric','readonly'=>true,'value'=>$rawwidthsoffabricval]);
	
	

if(isset($thisItemMeta['fabric-half-width-status']) && strlen(trim($thisItemMeta['fabric-half-width-status'])) > 0){
	$fabrichalfwidthstatusval=$thisItemMeta['fabric-half-width-status'];
}else{
	$fabrichalfwidthstatusval='';
}
echo $this->Form->input('fabric-half-width-status',['style'=>'width:91% !important;','label'=>'Fabric Half-Width Status','readonly'=>true,'value'=>$fabrichalfwidthstatusval]);
	
	
	
if(isset($thisItemMeta['valance-fabric-widths']) && strlen(trim($thisItemMeta['valance-fabric-widths'])) > 0){
	$valancefabricwidthsval=$thisItemMeta['valance-fabric-widths'];
}else{
	$valancefabricwidthsval='';
}
echo $this->Form->input('valance-fabric-widths',['label'=>'Valance Fabric Widths','readonly'=>true,'value'=>$valancefabricwidthsval]);
	
	
	
if(isset($thisItemMeta['total-fabric-widths']) && strlen(trim($thisItemMeta['total-fabric-widths'])) > 0){
	$totalfabricwidthsval=$thisItemMeta['total-fabric-widths'];
}else{
	$totalfabricwidthsval='';
}
echo $this->Form->input('total-fabric-widths',['type'=>'textarea','label'=>'Total Fabric Widths','readonly'=>true,'value'=>$totalfabricwidthsval]);
	
	
	
if(isset($thisItemMeta['raw-widths-of-lining']) && strlen(trim($thisItemMeta['raw-widths-of-lining'])) > 0){
	$rawwidthsofliningval=$thisItemMeta['raw-widths-of-lining'];
}else{
	$rawwidthsofliningval='';
}
echo $this->Form->input('raw-widths-of-lining',['label'=>'Raw Widths of Lining','readonly'=>true,'value'=>$rawwidthsofliningval]);
	
	
	
	
if(isset($thisItemMeta['lining-half-width-status']) && strlen(trim($thisItemMeta['lining-half-width-status'])) > 0){
	$lininghalfwidthstatusval=$thisItemMeta['lining-half-width-status'];
}else{
	$lininghalfwidthstatusval='';
}
echo $this->Form->input('lining-half-width-status',['style'=>'width:91% !important;','label'=>'Lining Half-Width Status','readonly'=>true,'value'=>$lininghalfwidthstatusval]);
	
	
	
	
if(isset($thisItemMeta['valance-lining-widths']) && strlen(trim($thisItemMeta['valance-lining-widths'])) > 0){
	$valanceliningwidthsval=$thisItemMeta['valance-lining-widths'];
}else{
	$valanceliningwidthsval='';
}
echo $this->Form->input('valance-lining-widths',['label'=>'Valance Lining Widths','readonly'=>true,'value'=>$valanceliningwidthsval]);
	
	
	
	
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
	
	
	
if(isset($thisItemMeta['valances-out-of-a-single-cut-val-pc']) && strlen(trim($thisItemMeta['valances-out-of-a-single-cut-val-pc'])) > 0){
	$valancesoutofasinglecutvalpcval=$thisItemMeta['valances-out-of-a-single-cut-val-pc'];
}else{
	$valancesoutofasinglecutvalpcval='';
}
echo $this->Form->input('valances-out-of-a-single-cut-val-pc',['label'=>'Valances / single cut VAL_PC','readonly'=>true,'value'=>$valancesoutofasinglecutvalpcval]);
	
	
	
if(isset($thisItemMeta['yds-per-unit']) && strlen(trim($thisItemMeta['yds-per-unit'])) > 0){
	$fabricyardsval=$thisItemMeta['yds-per-unit'];
}else{
	$fabricyardsval='';
}
echo $this->Form->input('yds-per-unit',['label'=>'Fabric Yards ea','readonly'=>true,'value'=>$fabricyardsval]);
	



	
if(isset($thisItemMeta['total-yds']) && strlen(trim($thisItemMeta['total-yds'])) > 0){
	$fabricyardstotalval=$thisItemMeta['total-yds'];
}else{
	$fabricyardstotalval='';
}
echo $this->Form->input('total-yds',['label'=>'Fabric Yards Total','readonly'=>true,'value'=>$fabricyardstotalval]);
	


	

if(isset($thisItemMeta['lining-yards']) && strlen(trim($thisItemMeta['lining-yards'])) > 0){
	$liningyardsval=$thisItemMeta['lining-yards'];
}else{
	$liningyardsval='';
}
echo $this->Form->input('lining-yards',['label'=>'Lining Yards','readonly'=>true,'value'=>$liningyardsval]);
	
	
	
	
if(isset($thisItemMeta['fabric-cost']) && strlen(trim($thisItemMeta['fabric-cost'])) > 0){
	$fabriccostval=$thisItemMeta['fabric-cost'];
}else{
	$fabriccostval='';
}	
echo $this->Form->input('fabric-cost',['label'=>'Fabric Cost per Valance','readonly'=>true,'value'=>$fabriccostval]);
	
	
	

if(isset($thisItemMeta['lining-cost']) && strlen(trim($thisItemMeta['lining-cost'])) > 0){
	$liningcostval=$thisItemMeta['lining-cost'];
}else{
	$liningcostval='';
}
echo $this->Form->input('lining-cost',['label'=>'Lining Cost per Valance','readonly'=>true,'value'=>$liningcostval]);
	
	


	
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
echo $this->Form->input('labor-cost',['label'=>'Labor Cost per Valance','readonly'=>true,'value'=>$laborcostval]);

	
	
	
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

<br><Br><br><Br>

<div id="warningbox"></div>

<style>
#warningbox{ display:none; position:fixed; padding:5px; bottom:20px; right:20px; width:510px; background:#FFCFBF; color:red; font-weight:bold; border:3px solid red; z-index:6666; font-size:12px; }
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
	if($('#valance-type').val() == 'Box Pleated'){
		if($('#com-fabric').is(':checked')){
			laborValue=<?php echo $settings['bpv_labor_cost_com']; ?>;
		}else{
			laborValue=<?php echo $settings['bpv_labor_cost_mom']; ?>;
		}
	}else if($('#valance-type').val() == 'Angled Tailored'){
		if($('#com-fabric').is(':checked')){
			laborValue=<?php echo $settings['bpv_angled_tailored_com_plf']; ?>;
		}else{
			laborValue=<?php echo $settings['bpv_angled_tailored_mom_plf']; ?>;
		}
	}else if($('#valance-type').val() == 'Tailored'){
		if($('#com-fabric').is(':checked')){
			laborValue=<?php echo $settings['bpv_tailored_com_plf']; ?>;
		}else{
			laborValue=<?php echo $settings['bpv_tailored_mom_plf']; ?>;
		}
	}else if($('#valance-type').val() == 'Scalloped'){
		if($('#com-fabric').is(':checked')){
			laborValue=<?php echo $settings['bpv_scalloped_com_plf']; ?>;
		}else{
			laborValue=<?php echo $settings['bpv_scalloped_mom_plf']; ?>;
		}
	}
	$('#labor-per-lf').val(laborValue.toFixed(2));
}



function doCalculation(){
	
	var qty=parseFloat($('#qty').val());
	var fab_w=parseFloat($('#fabric-width').val());
	calculateLabor();

	console.clear();


		var raw_valx = (parseFloat($('#face').val()) + parseFloat($('#return').val()) + parseFloat($('#return').val()) + parseFloat($('#wraparound-inches').val()));
		$('#visible-width-dimension').val(raw_valx);
		
		

		var plt_usg=(parseFloat($('#pleats').val()) * parseFloat($('#inches-used-by-pleat').val()));
		
		if($('#contrast-fabric-inside-pleat').is(':checked')){
			$('#pleat-usage').val('0');
			var val_x = raw_valx;
		}else{
			$('#pleat-usage').val(plt_usg);
			var val_x = (raw_valx + plt_usg);
		}
		
		
		$('#val-x').val(val_x);
		

		var banding_lf=Math.ceil(( (parseFloat($('#face').val()) + plt_usg) / 12));
		$('#blf').val(banding_lf);
		console.log('Banding LF = '+banding_lf);


		var welt_lf=Math.ceil((parseFloat($('#face').val()) / 12));
		$('#wlf').val(welt_lf);
		console.log('Welt LF = '+welt_lf);

		
		if($('#railroaded').is(':checked')){
			var val_wrv = 1;
		}else{
			var val_wrv = ( val_x / parseFloat($('#fabric-width').val()) );
		}
		$('#raw-widths-of-fabric').val(roundToTwo(val_wrv).toFixed(2));
		
		
		if($('#railroaded').is(':checked')){
			var val_w = 1;	
		}else{
			var val_w = Math.ceil(val_wrv);
		}
		


		if(parseFloat($('#qty').val()) == 1 || $('#railroaded').is(':checked') || $('#force-fabric-full-widths').is(':checked')){
			var val_hw = 0;	
		}else if((val_w - val_wrv) >= parseFloat($('#tolerance-on-widths').val())){
			var val_hw = 1;
		}else{
			var val_hw = 0;		
		}
		
		
		if($('#railroaded').is(':checked')){
			var val_wrvl = 1;	
		}else{
			var val_wrvl = ( val_x / parseFloat($('#lining-width').val()) );			
		}
		$('#raw-widths-of-lining').val(roundToTwo(val_wrvl).toFixed(2));
		
		
		if($('#railroaded').is(':checked')){
			var val_wl = 1;	
		}else{
			var val_wl = Math.ceil(val_wrvl);
		}
		
		
		if(parseFloat($('#qty').val()) == 1 || $('#force-lining-full-widths').is(':checked') || $('#railroaded').is(':checked')){
			var val_hwl = 0;
		}else if((val_wl - val_wrvl) >= parseFloat($('#tolerance-on-widths').val())){
			var val_hwl = 1;
		}else{
			var val_hwl = 0;
		}
		
		
		if($('#railroaded').is(':checked')){
			var val_wadjl = 1;	
		}else{
			var val_wadjl = (val_hwl == 1) ? (ceiling(val_wrvl,0.5)) : val_wl;
		}
		
		
		
		if(val_hwl == 1){
			var val_wlin = val_wadjl;
		}else{
			var val_wlin = val_wl;
		}
		$('#valance-lining-widths').val(val_wlin);
		
		
		
		if($('#railroaded').is(':checked')){
			var val_wadj = 1;	
		}else if(val_hw == 0){
			var val_wadj = val_w;
		}else{
			var val_wadj = (ceiling(val_wrv,0.5));
		}
		
		
		if(val_hw == 0){
			var val_wfab = val_w;
		}else{
			var val_wfab = val_wadj;
		}
		$('#valance-fabric-widths').val(val_wfab);
		

		
		if(!$('#rounded-widths-end-of-line').is(':checked')){
			var tot_weol = (val_wfab*parseFloat($('#qty').val()));	
		}else{
			var tot_weol = (Math.ceil(val_wfab*parseFloat($('#qty').val())));
		}
		
		
		if((tot_weol - parseInt(tot_weol)) > 0){
			var warn1 = " --> YOUR LINE TOTAL CONTAINS A HALF-WIDTH, PLEASE CONSIDER ROUNDING W EOL";
			var totalfabwidthsClass='redline';
		}else{
			var warn1 = " --> Your line contains only full widths. You seem to be OK";
			var totalfabwidthsClass='greenline';
		}
		$('#total-fabric-widths').removeClass('grayline').removeClass('greenline').removeClass('redline');
		$('#total-fabric-widths').val(tot_weol + " W Total " + warn1);
		$('#total-fabric-widths').addClass(totalfabwidthsClass);
		
		
		if($('#force-fabric-full-widths').is(':checked')){
			var warn2 = "FABRIC HALF-WIDTHS HAVE BEEN DISABLED";
			var fabrichalfwidthClass='grayline';
		}else if(val_hw == 1){
			var warn2 = "Fabric Half-Widths are feasible";
			var fabrichalfwidthClass='greenline';
		}else{
			var warn2 = "FABRIC HALF-WIDTHS ARE NOT FEASIBLE";
			var fabrichalfwidthClass='redline';	
		}
		$('#fabric-half-width-status').removeClass('grayline').removeClass('greenline').removeClass('redline');
		$('#fabric-half-width-status').val(warn2);
		$('#fabric-half-width-status').addClass(fabrichalfwidthClass);
		
		
		
		if($('#force-lining-full-widths').is(':checked')){
			var warn3 = "LINING HALF-WIDTHS HAVE BEEN DISABLED";
			var feasibleClass='grayline';
		}else if(val_hwl == 1){
			var warn3 = "Lining Half-Widths are feasible";
			var feasibleClass='greenline';
		}else{
			var warn3 = "LINING HALF-WIDTHS ARE NOT FEASIBLE";
			var feasibleClass='redline';
		}
		$('#lining-half-width-status').removeClass('grayline').removeClass('greenline').removeClass('redline');
		$('#lining-half-width-status').val(warn3);
		$('#lining-half-width-status').addClass(feasibleClass);
		

		if($('#railroaded').is(':checked')){
			var fab_cl = (parseFloat($('#height').val()) + parseFloat($('#extra-inches-on-height').val()));
		}else if(parseFloat($('#vert-repeat').val()) == 0){
			var fab_cl = (parseFloat($('#height').val()) + parseFloat($('#extra-inches-on-height').val()));
		}else{
			var fab_cl = Math.ceil(((parseFloat($('#height').val()) + parseFloat($('#extra-inches-on-height').val())) / parseFloat($('#vert-repeat').val()))) * parseFloat($('#vert-repeat').val());
		}
		$('#fabric-cl').val(fab_cl);
		
		
		
		if(!$('#railroaded').is(':checked')){
			var val_pc = "N/A";	
		}else if(parseFloat($('#qty').val()) == 1){
			var val_pc = 1;
		}else{
			var val_pc = Math.floor(parseFloat($('#fabric-width').val()) / fab_cl);
		}
		$('#valances-out-of-a-single-cut-val-pc').val(val_pc);


		var lin_cl = (parseFloat($('#height').val()) + parseFloat($('#extra-inches-on-height').val()));
		$('#lining-cl').val(lin_cl);
		
		
		if($('#railroaded').is(':checked')){
			//var yds_pu = ((val_x / 36) * 1.05) / val_pc;
			var yds_pu = ((((((tot_weol / parseFloat($('#qty').val())) * val_x) / 36) * 1.05) / val_pc) * parseFloat($('#qty').val()));
		}else{
			var yds_pu = ((((tot_weol / parseFloat($('#qty').val())) * fab_cl) / 36) * 1.05);
		}
		
		var dist_fabyds = ((Math.ceil(yds_pu * parseFloat($('#qty').val()) )) / parseFloat($('#qty').val()));
		
		
		if($('#rounded-yds-end-of-line').is(':checked')){
			var fab_yds = Math.ceil(yds_pu);
		}else if($('#distributed-rounded-yds').is(':checked')){
			var fab_yds = dist_fabyds;
		}else{
			var fab_yds = yds_pu;
		}
		
		var totfab_yds = (fab_yds * qty);
		
		$('#yds-per-unit').val(roundToTwo(fab_yds).toFixed(2));// + " yds ea, --> " + totfab_yds.toFixed(2) + " yds total");

		$('#total-yds').val(roundToTwo(totfab_yds).toFixed(2));



		if($('#railroaded').is(':checked')){
			var lin_yds = ((val_x / 36) * 1.05);
		}else{
			var lin_yds = (((val_wlin * lin_cl) / 36) * 1.05);
		}
		$('#lining-yards').val(roundToTwo(lin_yds).toFixed(2));



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
			console.log('fab_ppy=fabric-cost-per-yard-custom-value = '+fab_ppy);
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

var tot_yds=totfab_yds;




if(qty >0 && $('select[name=fabric-cost-per-yard]').val() != '' && tot_yds > 0){
			//fab_ppy
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
			
		
		if($('#com-fabric').prop('checked')){
			$('#fabric-cost').val('0.00');
			var fab_cost=0;
			$('#fabric-markup-custom-value').parent().hide('fast');
			$('select[name=fabric-cost-per-yard]').parent().hide('fast');
			$('#inboundfreightwrap').hide('fast');
			$('#fabricmarkupwrap').hide('fast');
		}else{
			$('#fabric-markup-custom-value').parent().show('fast');
			$('select[name=fabric-cost-per-yard]').parent().show('fast');
			$('#inboundfreightwrap').show('fast');
			$('#fabricmarkupwrap').show('fast');
		

			if($('#fabric-markup-custom-value').val() != ''){
				var fabricMarkup=(1+(parseFloat($('#fabric-markup-custom-value').val())/100));
			}else{
				var fabricMarkup=(1+(parseFloat($('#fabric-markup').val())/100));
			}

			var markedupFabric=((parseFloat(fab_ppy)+parseFloat(ibfrtpy))*parseFloat(fabricMarkup));


			var fab_cost=(parseFloat(fab_yds)*markedupFabric);
			console.log('fab_cost = '+fab_cost);
			console.log('fab_cost_math = ('+parseFloat(fab_yds)+' * '+markedupFabric+')');

		}

		var totfab_cost = (fab_cost * qty);

		$('#fabric-cost').val(roundToTwo(fab_cost).toFixed(2));
		
		
		
		var lin_cost = (parseFloat($('#lining-price-per-yd').val()) * lin_yds);
		console.log('lin_cost = ('+$('#lining-price-per-yd').val()+' * '+lin_yds+')');

		$('#lining-cost').val(roundToTwo(lin_cost).toFixed(2));
		
		
		var labor_billable=(Math.ceil( ( parseFloat($('#face').val()) + parseFloat($('#return').val()) + parseFloat($('#return').val()) ) / 12));
	
		<?php if(intval($quoteID) == 0){ ?>
		if($('#custom-base-labor').val() != ''){
			var lbr_cost = (labor_billable * parseFloat($('#custom-base-labor').val()));
			
			var lbr_plf = parseFloat($('#custom-base-labor').val());
			var lbr_pu = (lbr_plf * labor_billable);
			
		}else{
			var lbr_cost = (labor_billable * parseFloat($('#labor-per-lf').val()));
			
			var lbr_plf = parseFloat($('#labor-per-lf').val());
			var lbr_pu = (lbr_plf * labor_billable);
		}
		<?php }else{ ?>
			var lbr_cost = (labor_billable * parseFloat($('#labor-per-lf').val()));
			
			var lbr_plf = parseFloat($('#labor-per-lf').val());
			var lbr_pu = (lbr_plf * labor_billable);
		<?php } ?>


		$('#labor-cost').val(roundToTwo(lbr_cost).toFixed(2));
		$('#labor-billable').val(labor_billable);
		
		
		var tot_cost = (fab_cost + lin_cost + lbr_cost);
		




		if($('#straight-banding').is(':checked')){
			var horizontalStraightBandingCost = roundToTwo((parseFloat($('#straight-banding-yards').val()) * parseFloat($('#straight-banding-price-per-yard').val()))).toFixed(2);
			tot_cost = (parseFloat(tot_cost) + parseFloat(horizontalStraightBandingCost));
			console.log('Base Price increased by '+horizontalStraightBandingCost+' for '+$('#straight-banding-yards').val()+' yards of Horizontal Straight Banding fabric at $'+$('#straight-banding-price-per-yard').val()+' per yard');
		}


		if($('#shaped-banding').is(':checked')){
			var horizontalShapedBandingCost = roundToTwo((parseFloat($('#shaped-banding-yards').val()) * parseFloat($('#shaped-banding-price-per-yard').val()))).toFixed(2);
			tot_cost = (parseFloat(tot_cost) + parseFloat(horizontalShapedBandingCost));
			console.log('Base Price increased by '+horizontalShapedBandingCost+' for '+$('#shaped-banding-yards').val()+' yards of Horizontal Shaped Banding fabric at $'+$('#shaped-banding-price-per-yard').val()+' per yard');
		}


		if($('#trim-sewn-on').is(':checked')){
			var trimSewnOnCost = roundToTwo((parseFloat($('#trim-yards-each').val()) * parseFloat($('#trim-price-per-yard').val()) )).toFixed(2);
			tot_cost = (parseFloat(tot_cost) + parseFloat(trimSewnOnCost));
			console.log('Base Price increased by '+trimSewnOnCost+' for Sewn-On Trim ('+$('#trim-yards-each').val()+' yds at $'+$('#trim-price-per-yard').val()+')');
		}


		if($('#welt-covered-in-fabric').is(':checked')){
			if($('#welts-same-fabric').is(':checked')){
				//same fabric, calculate it and increase yardages
				var weltPricePerYard = fab_cost;
				var coveredWeltCost = roundToTwo((parseFloat($('#welt-yards-each').val()) * parseFloat(weltPricePerYard))).toFixed(2);
			}else{
				//new fabric, calculate it and increase base price
				var weltPricePerYard = $('#welt-price-per-yard').val()
				var coveredWeltCost = roundToTwo((parseFloat($('#welt-yards-each').val()) * parseFloat(weltPricePerYard))).toFixed(2);
			}

			tot_cost = (parseFloat(tot_cost) + parseFloat(coveredWeltCost));
			console.log('Base Price increased by '+coveredWeltCost+' for Covered Welts ('+$('#welt-yards-each').val() + ' yards at $'+weltPricePerYard+')');

		}



		if($('#contrast-fabric-inside-pleat').is(':checked')){
			var contrastPleatCost = (parseFloat($('#contrast-fab-inside-pleat-yards-each').val()) * parseFloat($('#contrast-fab-inside-pleat-price-per-yard').val()));
			tot_cost = (parseFloat(tot_cost) + parseFloat(contrastPleatCost));
			console.log('Base Price increased by '+contrastPleatCost+' for Contrast Inside Pleat ('+$('#contrast-fab-inside-pleat-yards-each').val()+' yards at $'+$('#contrast-fab-inside-pleat-price-per-yard').val()+')');
		}

	
		$('#cost').val(roundToTwo(tot_cost).toFixed(2));

		var priceval=roundToTwo(parseFloat(tot_cost)).toFixed(2);


		//add-ons
		var startPrice=priceval;
		$('#start-price-val').val(startPrice);
		var addOnTotals=0;
		var addOnText='';


		//straight banding?
		if($('#straight-banding').is(':checked')){
			var straightBandingIncrease=(banding_lf * <?php echo $settings['wt_contrast_straight_banding_per_lf']; ?>);
			priceval=(parseFloat(priceval)+parseFloat(straightBandingIncrease));
			addOnTotals=(addOnTotals+parseFloat(straightBandingIncrease));
			addOnText += '<tr><td>Horiz Straight Banding ('+banding_lf+' LF)</td><td>$'+roundToTwo(parseFloat(straightBandingIncrease)).toFixed(2)+'</td></tr>';
		}

		//shaped banding?
		if($('#shaped-banding').is(':checked')){
			var shapedBandingIncrease=(banding_lf * <?php echo $settings['wt_contrast_shaped_banding_per_lf']; ?>);
			priceval=(parseFloat(priceval)+parseFloat(shapedBandingIncrease));
			addOnTotals = (addOnTotals+parseFloat(shapedBandingIncrease));
			addOnText += '<tr><td>Horiz Shaped Banding ('+banding_lf+' LF)</td><td>$'+roundToTwo(parseFloat(shapedBandingIncrease)).toFixed(2)+'</td></tr>';
		}

		//trim sewn on?
		if($('#trim-sewn-on').is(':checked')){
			var trimSewnOnIncrease=(banding_lf * <?php echo $settings['wt_trim_sewn_on_per_lf']; ?>);
			priceval=(parseFloat(priceval)+parseFloat(trimSewnOnIncrease));
			addOnTotals = (addOnTotals + parseFloat(trimSewnOnIncrease));
			addOnText += '<tr><td>Horiz Sewn-On Trim ('+banding_lf+' LF)</td><td>$'+roundToTwo(parseFloat(trimSewnOnIncrease)).toFixed(2)+'</td></tr>';
		}

		//welt covered in fabric?
		if($('#welt-covered-in-fabric').is(':checked')){
			var weltcoveredinfabricIncrease=(welt_lf * <?php echo $settings['wt_welt_covered_per_lf']; ?>);
			priceval=(parseFloat(priceval)+parseFloat(weltcoveredinfabricIncrease));
			addOnTotals = (addOnTotals + parseFloat(weltcoveredinfabricIncrease));
			addOnText += '<tr><td>Welt Covered in Fabric ('+welt_lf+' LF)</td><td>$'+roundToTwo(parseFloat(weltcoveredinfabricIncrease)).toFixed(2)+'</td></tr>';
		}


		//contrast fabric inside pleat?
		if($('#contrast-fabric-inside-pleat').is(':checked')){
			var contrastfabricinsidepleatIncrease=(parseFloat($('#pleats').val()) * <?php echo $settings['wt_contrast_fab_inside_pleat_ea']; ?>);
			priceval=(parseFloat(priceval) + parseFloat(contrastfabricinsidepleatIncrease));
			addOnTotals = (addOnTotals + parseFloat(contrastfabricinsidepleatIncrease));
			addOnText += '<tr><td>'+$('#pleats').val()+'X Contrast Fabric Inside Pleats</td><td>$'+roundToTwo(parseFloat(contrastfabricinsidepleatIncrease)).toFixed(2)+'</td></tr>';
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
		    var bigfaceIncrease= (  (parseFloat(<?php echo $settings['val_face_greater_than_96']; ?>)/100) * lbr_pu);
		    priceval = (parseFloat(priceval) + bigfaceIncrease);
		    addOnTotals = (addOnTotals + bigfaceIncrease);
		    addOnText += '<tr><td>FACE &gt; 96in Surcharge</td><td>$'+roundToTwo(bigfaceIncrease).toFixed(2)+'</td></tr>';
		}
		
		//height (long point) >= 20 surcharge?
		if($('#height').val() >= 20){
		    var tallIncrease= (  (parseFloat(<?php echo $settings['val_height_20plus']; ?>)/100) * lbr_pu);
		    priceval = (parseFloat(priceval) + tallIncrease);
		    addOnTotals = (addOnTotals + tallIncrease);
		    addOnText += '<tr><td>HEIGHT &#8805; 20in Surcharge</td><td>$'+roundToTwo(tallIncrease).toFixed(2)+'</td></tr>';
		}
		


		$('#price').val(priceval);
		


		$('#add-on-total-val').val(addOnTotals);
		$('#add-on-text-val').val(addOnText);


		$('#total-surcharges').val(roundToTwo(addOnTotals).toFixed(2));


		$('#explainmath').html('<h3 id="pricebreakdown">Base Price Surcharge Breakdown</h3><hr><table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000"><tr><td>Starting Price (before surcharges/add-ons)</td><td><B>$'+roundToTwo(parseFloat($('#start-price-val').val())).toFixed(2)+'</B></td></tr>'+$('#add-on-text-val').val()+'<tr><td><b>RESULTING BASE PRICE:</b></td><td><b><u>$'+roundToTwo(parseFloat($('#price').val())).toFixed(2)+'</u></b></td></tr></table><Br><Br><Br><Br><Br><br><Br><Br><Br>');





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


        if($('#valance-type').val() != 'Box Pleated' && $('#valance-type').val() != 'Tailored' && ($('#fl-short').val() == '0' || $('#fl-short').val() == '')){
        	warningboxcontent += '<img src="/img/delete.png" /> MISSING HEIGHT (SHORT POINT)<br>';
        	heightShortErrors++;
        	warncount++;
        }


        if($('#valance-type').val() != 'Box Pleated' && $('#valance-type').val() != 'Tailored' && ( parseFloat($('#fl-short').val()) >= parseFloat($('#height').val()) )){
        	warningboxcontent += '<img src="/img/delete.png" /> SHORT POINT MUST BE SMALLER THAN LONG POINT<br>';
        	heightShortErrors++;
        	heightLongErrors++;
        	warncount++;
        }
        

        if($('#railroaded').is(':checked')){
        	if( parseFloat($('#fabric-width').val()) < ( parseFloat($('#height').val()) + parseFloat($('#extra-inches-on-height').val()) ) ){
        		warningboxcontent += '<img src="/img/delete.png" /> IMPOSSIBLE DIMENSIONS PROVIDED. CHECK INPUTS<br>';
        		
        		heightLongErrors++;
        		$('#fabric-width,#extra-inches-on-height').parent().addClass('badvalue');
        		$('#expandcollapse').show('fast');
				$('#advancedfieldsvisible').val('1');

        		warncount++;
        	}else{
        		$('#fabric-width,#extra-inches-on-height').parent().removeClass('badvalue');
        	}
        }else{
        	$('#fabric-width,#extra-inches-on-height').parent().removeClass('badvalue');
        }


        //warn1
        if(warn1 != ' --> Your line contains only full widths. You seem to be OK'){
        	warningboxcontent += '<img src="/img/delete.png" /> YOUR LINE TOTAL CONTAINS A HALF-WIDTH, PLEASE CONSIDER ROUNDING W EOL<br>';
        	$('#valance-fabric-widths').parent().addClass('badvalue');
        	$('#rounded-widths-end-of-line').parent().parent().addClass('badvalue');
        	warncount++;
        }else{
        	$('#valance-fabric-widths').parent().removeClass('badvalue');
        	$('#rounded-widths-end-of-line').parent().parent().removeClass('badvalue');
        }


        //warn2
		if(warn2 == 'FABRIC HALF-WIDTHS ARE NOT FEASIBLE'){
			warningboxcontent += '<span style=\"color:#D96D00 !important;\"><img src="/img/alert.png" /> FABRIC HALF-WIDTHS ARE NOT FEASIBLE</span><br>';

			$('#qty,#fabric-width,#face').parent().addClass('alertcontent');
			
			$('#force-fabric-full-widths').parent().parent().addClass('alertcontent');
			
	        warncount++;
		}else{
			$('#qty,#fabric-width,#face').parent().removeClass('alertcontent');
			$('#force-fabric-full-widths').parent().parent().removeClass('alertcontent');
		}


		//warn3
		if(warn3 == 'LINING HALF-WIDTHS ARE NOT FEASIBLE'){
			warningboxcontent += '<span style=\"color:#D96D00 !important;\"><img src="/img/alert.png" /> LINING HALF-WIDTHS ARE NOT FEASIBLE</span><br>';

			$('#force-lining-full-widths').parent().parent().addClass('alertcontent');

			warncount++;
		}else{
			$('#force-lining-full-widths').parent().parent().removeClass('alertcontent');
		}
			
		



        if($('#welt-covered-in-fabric').is(':checked') && $('#welts-same-fabric').is(':checked') && parseFloat($('#welt-yards-each').val()) == 0){
        	warningboxcontent += '<img src="/img/delete.png" /> WELT OUT OF MAIN FABRIC IS SET AT ZERO YARDS.<br>';
        	warncount++;
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
			$('#bracket-size').parent().removeClass('badvalue');
			$('#bracket-count').parent().removeClass('badvalue');
		}



		if($('#has-board').is(':checked') && $('#board-size').val() == ''){
			warningboxcontent += '<img src="/img/delete.png" /> BOARD SIZE NOT SELECTED<br>';
			$('#has-board').parent().parent().addClass('badvalue');
			$('#board-size').parent().addClass('badvalue');
			warncount++;
		}else{
			$('#has-board').parent().parent().removeClass('badvalue');
			$('#board-size').parent().removeClass('badvalue');
		}


		if($('#has-board-attachment').is(':checked') && $('#board-attachment').val() == ''){
			warningboxcontent += '<img src="/img/delete.png" /> BOARD ATTACHMENT NOT SELECTED<br>';
			$('#has-board-attachment').parent().parent().addClass('badvalue');
			$('#board-attachment').parent().addClass('badvalue');
			warncount++;
		}else{
			$('#has-board-attachment').parent().parent().removeClass('badvalue');
			$('#board-attachment').parent().removeClass('badvalue');
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



        if(warncount == 0){
    	     $('#warningbox').hide('fast');
        }else{
             $('#warningbox').html(warningboxcontent).show('fast');
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
	


	$('#valance-type').change(function(){
		if($(this).val() != 'Box Pleated' && $(this).val() != 'Tailored'){
			$('#flshortwrap').show('fast');
			$('#flmidwrap').show('fast');
		}else{
			$('#flshortwrap').hide('fast');
			$('#flmidwrap').hide('fast');
		}
	});
	
	
<?php
if($userData['scroll_calcs'] == 1){
?>
correctColHeights();
setInterval('correctColHeights()',500);
<?php } ?>

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


    $('#hinged').click(function(){
	   if($(this).is(':checked')){
	       $('#hingecountwrap').show('fast');
	   }else{
	       $('#hingecountwrap').hide('fast');
	   }
	});


	$('#has-board').click(function(){
		if($(this).is(':checked')){
			$('#boarddetailwrap').show('fast');
		}else{
			$('#boarddetailwrap').hide('fast');
		}
	});

	$('#has-board').change(function(){
		if($(this).is(':checked')){
			$('#boarddetailwrap').show('fast');
		}else{
			$('#boarddetailwrap').hide('fast');
		}
	});


	$('#has-board-attachment').click(function(){
		if($(this).is(':checked')){
			$('#boardattachmentwrap').show('fast');
		}else{
			$('#boardattachmentwrap').hide('fast');
		}
	});

	$('#has-board-attachment').change(function(){
		if($(this).is(':checked')){
			$('#boardattachmentwrap').show('fast');
		}else{
			$('#boardattachmentwrap').hide('fast');
		}
	});



	$('#straight-banding').click(function(){
		if($(this).is(':checked')){
			$('#hbandingmathwrap').show('fast');
		}else{
			$('#hbandingmathwrap').hide('fast');
		}
	});

	$('#straight-banding').change(function(){
		if($(this).is(':checked')){
			$('#hbandingmathwrap').show('fast');
		}else{
			$('#hbandingmathwrap').hide('fast');
		}
	});



	$('#shaped-banding').click(function(){
		if($(this).is(':checked')){
			$('#hshapedbandingmathwrap').show('fast');
		}else{
			$('#hshapedbandingmathwrap').hide('fast');
		}
	});

	$('#shaped-banding').change(function(){
		if($(this).is(':checked')){
			$('#hshapedbandingmathwrap').show('fast');
		}else{
			$('#hshapedbandingmathwrap').hide('fast');
		}
	});



	$('#trim-sewn-on').click(function(){
		if($(this).is(':checked')){
			$('#trimsewnonmathwrap').show('fast');
		}else{
			$('#trimsewnonmathwrap').hide('fast');
		}
	});



	$('#trim-sewn-on').change(function(){
		if($(this).is(':checked')){
			$('#trimsewnonmathwrap').show('fast');
		}else{
			$('#trimsewnonmathwrap').hide('fast');
		}
	});




	$('#welt-covered-in-fabric').click(function(){
		if($(this).is(':checked')){
			$('#weltmathwrap').show('fast');
		}else{
			$('#weltmathwrap').hide('fast');
		}
	});

	$('#welt-covered-in-fabric').change(function(){
		if($(this).is(':checked')){
			$('#weltmathwrap').show('fast');
		}else{
			$('#weltmathwrap').hide('fast');
		}
	});


	$('#welts-same-fabric').click(function(){
		if($(this).is(':checked')){
			//$('#welt-yards-each').parent().hide('fast');
			$('#welt-price-per-yard').parent().hide('fast');
		}else{
			//$('#welt-yards-each').parent().show('fast');
			$('#welt-price-per-yard').parent().show('fast');
		}
	});

	$('#welts-same-fabric').change(function(){
		if($(this).is(':checked')){
			//$('#welt-yards-each').parent().hide('fast');
			$('#welt-price-per-yard').parent().hide('fast');
		}else{
			//$('#welt-yards-each').parent().show('fast');
			$('#welt-price-per-yard').parent().show('fast');
		}
	});


	$('#contrast-fabric-inside-pleat').click(function(){
		if($(this).is(':checked')){
			$('#contrastfabpleatwrap').show('fast');
		}else{
			$('#contrastfabpleatwrap').hide('fast');
		}
	});

	$('#contrast-fabric-inside-pleat').change(function(){
		if($(this).is(':checked')){
			$('#contrastfabpleatwrap').show('fast');
		}else{
			$('#contrastfabpleatwrap').hide('fast');
		}
	});


if(canCalculate()){	
		$('#cannotcalculate').hide();
		doCalculation(); 
	}else{
		$('#cannotcalculate').show();
	}

$('.calculatebutton button').click(function(){
	if(canCalculate()){	
		$('#cannotcalculate').hide();
		doCalculation(); 
	}else{
		$('#cannotcalculate').show();
	}
});
	
	$('#com-fabric').change(function(){
		calculateLabor();
		if($(this).prop('checked')){
		    $('#fabric-cost-per-yard-custom-value').val('');
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated');
			if(canCalculate()){
				doCalculation();
			}
		}
	});

	$('#com-fabric').click(function(){
		calculateLabor();
		if($(this).prop('checked')){
		    $('#fabric-cost-per-yard-custom-value').val('');
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated');
			if(canCalculate()){
				doCalculation();
			}
		}
	});



	$('#calcformleft input,#calcformleft select,#add_surcharge').keyup(function(){
		calculateLabor();
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});
	
	$('#calcformleft input,#calcformleft select,#markup').change(function(){
		calculateLabor();
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
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



	$('#rounded-yds-end-of-line').click(function(){
		if($(this).prop('checked')){
			if($('#distributed-rounded-yds').is(':checked')){
				$('#distributed-rounded-yds').prop('checked',false);
			}
		}
	});


	$('#distributed-rounded-yds').click(function(){
		if($(this).prop('checked')){
			if($('#rounded-yds-end-of-line').is(':checked')){
				$('#rounded-yds-end-of-line').prop('checked',false);
			}
		}
	});


	$('#calcformleft input,#calcformleft select,#tier_adjustment,#add_surcharge').change(function(){
		calculateLabor();
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});

	$('#install_surcharge').click(function(){
		calculateLabor();
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
        $("#fabriccostwrapinnerwrap").css({'clear': 'both','padding': '10px 0px'});

        $('#fabric-cost-per-yard-custom-value').val('');
        
    });
    /*ppsa-33 end */
<?php
if(isset($isedit) && $isedit=='1'){
//do not set default

}else{
?>
	$('#linings_id').val('1');
	$('#lining-price-per-yd').val($('#linings_id option[value=1]').attr('data-price'));
<?php } ?>


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
	
	

	if($('#pleats').val() == '0' || $('#pleats').val()==''){
		//console.log('Cannot calculate without a Pleats value');
		$('#pleats').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#pleats').removeClass('notvalid').removeClass('validated').addClass('validated');
	}



	if($('#fabric-width').val() == '0' || $('#fabric-width').val()==''){
		//console.log('Cannot calculate without a Fabric Width value');
		$('#fabric-width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#fabric-width').removeClass('notvalid').removeClass('validated').addClass('validated');
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

	
	
	/*
	if($('select[name=fabric-price-per-yard]').val() == '0'){
		//console.log('Cannot calculate without a Fabric Price (per yard) selection');
		errorcount++;
	}
	*/

	if($('#linings_id').val() == ''){
		$('#linings_id').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#linings_id').removeClass('notvalid').removeClass('validated').addClass('validated');
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
</script>
<Br><Br><Br><Br>
<div id="explainmath"></div>