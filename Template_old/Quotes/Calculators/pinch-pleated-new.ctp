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
	

#goadvanced{ font-size:12px; color:#000; background:#ccc; border:1px solid #000; padding:5px 10px; margin:0; display:inline-block; }

#explainmath{ max-width:650px; margin:0 auto; text-align:center; }

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



div.suboptions label{ text-indent:22px; }


#calcformleft .selectbox label{ width:55% !important; }
#calcformleft .selectbox select{ width:36% !important; }

#calcformleft label[for=style]{ width:55% !important; }
#calcformleft select[name=style]{ width:36% !important; }

#calcformleft input.notvalid,#calcformleft select.notvalid{ border:1px solid red; }
#calcformleft input.validated,#calcformleft select.validated{ border:1px solid green; }
	
.clear{ clear:both; }
#calcformleft{ width:48.5%; float:left; }
#resultsblock{ width:48.5%; padding:10px; float:right; background:#EEE; }

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
label[for=total-surcharges]{ color:blue; }
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

label[for=yds-per-unit]{ width:40% !important; }
#yds-per-unit{ width:54% !important; font-size:12px; }

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


#layoutimgwrap{ float:right; width:43%; }
#layoutimgwrap img{ width:100%;height:auto; }
#layoutimgwrap label[for=layoutimg]{ width:48% !important; }

#fabricname{ width:43%; float:right; }
#fabriccolor{ width:43%; float:right; clear:right; }
#changefabricbutton{ background:#DDD !important; color:#000 !important; padding:2px 2px 2px 2px !important; border:0; font-size:12px !important; float:right; clear:right; }
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
	echo "<h1>Standalone PPD Calculator</h1><hr>";
}

echo $this->Form->create();
echo "<div id=\"calcformleft\">";

echo "<h2>Pinch Pleated Drapery</h2>";

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

echo $this->Form->input('calculator-used',['type'=>'hidden','value'=>'pinch-pleated']);

echo $this->Form->input('fabricid',['type'=>'hidden','value'=>$fabricid]);

echo $this->Form->input('start-price-val',['type'=>'hidden','value'=>$thisItemMeta['start-price-val']]);
echo $this->Form->input('add-on-total-val',['type'=>'hidden','value'=>$thisItemMeta['add-on-total-val']]);
echo $this->Form->input('add-on-text-val',['type'=>'hidden','value'=>$thisItemMeta['add-on-text-val']]);

if(isset($thisItemMeta['advancedfieldsvisible']) && $thisItemMeta['advancedfieldsvisible']=='1'){
	echo $this->Form->input('advancedfieldsvisible',['type'=>"hidden","value"=>'1']);
}else{
	echo $this->Form->input('advancedfieldsvisible',['type'=>"hidden","value"=>'0']);
}



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





if(isset($thisItemMeta['unit-of-measure']) && strlen(trim($thisItemMeta['unit-of-measure'])) >0){
	$unitofmeasureval=array('value'=>$thisItemMeta['unit-of-measure'],'id'=>'unit-of-measure');
	$unitsAvail=['pair'=>'Pair','panel'=>'Panel'];
}else{
	$unitofmeasureval=array('empty'=>'--Select--','id'=>'unit-of-measure');
	$unitsAvail=['pair'=>'Pair'];
}
echo "<div class=\"input select\">";
echo $this->Form->label('Unit of Measure');
echo $this->Form->select('unit-of-measure',$unitsAvail,$unitofmeasureval);
echo "</div>";



if($unitofmeasureval == 'panel'){
	$paneltypedisplay='block';
	if(isset($thisItemMeta['panel-type']) && strlen(trim($thisItemMeta['panel-type'])) >0){
		$paneltypeval=array('value'=>$thisItemMeta['panel-type'],'id'=>'panel-type');
	}else{
		$paneltypeval=array('empty'=>'--Select--','id'=>'panel-type');
	}
}else{
	$paneltypedisplay='none';
	$paneltypeval=array('empty'=>'--Select--','id'=>'panel-type');
}
echo "<div id=\"paneltypewrap\">";
echo "<div class=\"input selectbox\">";
echo $this->Form->label('Type');
echo $this->Form->select('panel-type',['Stationary'=>'Stationary','Operable'=>'Operable'],$paneltypeval);
echo "</div>";
echo "</div>";




if($unitofmeasureval == 'panel'){
	$displayorientation='display:block;';
	if(isset($thisItemMeta['orientation']) && strlen(trim($thisItemMeta['orientation'])) >0){
		$orientationval=array('value'=>$thisItemMeta['orientation'],'id'=>'orientation');
	}else{
		$orientationval=array('empty'=>'--Select--','id'=>'orientation');
	}

	if(isset($thisItemMeta['panel-type']) && $thisItemMeta['panel-type'] == "Operable"){
		$possibleorientations=array('Left'=>'Left','Right'=>'Right');
	}elseif(isset($thisItemMeta['panel-type']) && $thisItemMeta['panel-type'] == 'Stationary'){
		$possibleorientations=array('Left'=>'Left','Right'=>'Right','Center'=>'Center');
	}else{
		$possibleorientations=array('Left'=>'Left','Right'=>'Right','Center'=>'Center');
	}

}else{
	$displayorientation='display:none;';
	$orientationval=array('empty'=>'--Select--','id'=>'orientation');

	$possibleorientations=array('Left'=>'Left','Right'=>'Right','Center'=>'Center');
}
echo "<div id=\"orientationwrap\" style=\"".$displayorientation."\">";
echo "<div class=\"input selectbox\">";
echo $this->Form->label('Orientation');
echo $this->Form->select('orientation',$possibleorientations,$orientationval);
echo "</div>";
echo "</div>";







echo "<fieldset class=\"fieldsection\"><legend>DIMENSIONS</legend>";

if(isset($thisItemMeta['width-of-window']) && is_numeric($thisItemMeta['width-of-window'])){
	$widthofwindowval=$thisItemMeta['width-of-window'];
}else{
	$widthofwindowval='0';
}

echo $this->Form->input('width-of-window',['label'=>'Width of Window','type'=>'number','step'=>'any','value'=>$widthofwindowval]);




if(isset($thisItemMeta['wall-left']) && is_numeric($thisItemMeta['wall-left'])){
	$wallleftval=$thisItemMeta['wall-left'];
}else{
	$wallleftval='4';
}

echo $this->Form->input('wall-left',['label'=>'Wall Left','type'=>'number','step'=>'any','value'=>$wallleftval]);




if(isset($thisItemMeta['wall-right']) && is_numeric($thisItemMeta['wall-right'])){
	$wallrightval=$thisItemMeta['wall-right'];
}else{
	$wallrightval='4';
}

echo $this->Form->input('wall-right',['label'=>'Wall Right','type'=>'number','step'=>'any','value'=>$wallrightval]);



/*
if(isset($thisItemMeta['fabric-widths-per-panel']) && strlen(trim($thisItemMeta['fabric-widths-per-panel']))> 0){
	$fabricwidthsperpanelval=$thisItemMeta['fabric-widths-per-panel'];
	$fabricwidthsperpaneldisplay='block';
}else{
	$fabricwidthsperpanelval='1';
	$fabricwidthsperpaneldisplay='none';
}
echo "<div id=\"fabricwidthsperpanelwrap\" class=\"input selectbox\">
<label for=\"fabric-widths-per-panel\">Fabric Widths per Panel</label>";
echo $this->Form->select('fabric-widths-per-panel',['0.5'=>'Half Width','1'=>'1 Width','1.5'=>'1.5 Widths','2'=>'2 Widths'],['id'=>'fabric-widths-per-panel','value'=>$fabricwidthsperpanelval]);
echo "</div>";
*/



if(isset($thisItemMeta['rod-width']) && is_numeric($thisItemMeta['rod-width'])){
	$rodwidthval=$thisItemMeta['rod-width'];
}else{
	$rodwidthval='';
}

echo $this->Form->input('rod-width',['label'=>'Rod Width','type'=>'number','step'=>'any','value'=>$rodwidthval]);






if(isset($thisItemMeta['length']) && is_numeric($thisItemMeta['length'])){
	$lengthval=$thisItemMeta['length'];
}else{
	$lengthval='0';
}
echo $this->Form->input('length',['type'=>'number','min'=>'0.00','step'=>'0.01','value'=>$lengthval,'label'=>'FINISH LENGTH']);







if(isset($thisItemMeta['fullness']) && is_numeric($thisItemMeta['fullness'])){
	$fullnessval=$thisItemMeta['fullness'];
}else{
	$fullnessval='200';
}

echo $this->Form->input('fullness',['label'=>'Fullness %','type'=>'number','step'=>'10','min'=>'150','max'=>'350','value'=>$fullnessval]);







if(isset($thisItemMeta['default-return']) && is_numeric($thisItemMeta['default-return'])){
	$defreturnval=$thisItemMeta['default-return'];
}else{
	$defreturnval='3.5';
}
echo $this->Form->input('default-return',['label'=>'Default Return','type'=>'number','min'=>0,'step'=>'any','value'=>$defreturnval]);




if(isset($thisItemMeta['default-overlap']) && is_numeric($thisItemMeta['default-overlap'])){
	$defoverlapval=$thisItemMeta['default-overlap'];
}else{
	$defoverlapval='3.5';
}
echo $this->Form->input('default-overlap',['label'=>'Default Overlap','type'=>'number','min'=>0,'step'=>'any','value'=>$defoverlapval]);






if(isset($thisItemMeta['draw']) && strlen(trim($thisItemMeta['draw'])) >0){
	$drawval=array('value'=>$thisItemMeta['draw'],'id'=>'draw');
}else{
	$drawval=array('empty'=>'--Select--','id'=>'draw');
}
echo "<div class=\"input select\">";
echo $this->Form->label('Draw');
echo $this->Form->select('draw',['Baton'=>'Baton','Cord' => 'Cord','Other'=>'Other'],$drawval);
echo "</div>";





if(isset($thisItemMeta['draw-other']) && is_numeric($thisItemMeta['draw-other'])){
	$drawotherdisplay='display:block';
	$drawotherval=$thisItemMeta['draw-other'];
}else{
	$drawotherdisplay='display:none';
	$drawotherval='';
}

echo "<div id=\"drawotherwrap\" style=\"".$drawotherdisplay."\">";
echo $this->Form->input('draw-other',['label'=>'Other "Draw"','type'=>'text','value'=>$drawotherval]);
echo "</div>";





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
echo "<div><input type=\"number\" name=\"fabric-markup-custom-value\" step=\"any\" min=\"0\" id=\"fabric-markup-custom-value\" value=\"".$markupcustomval."\" placeholder=\"Override\" /></div>";
echo "</div></div>";


echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>LINING SPECS &amp; PRICING</legend>";

echo "<div class=\"input selectbox\">";
echo "<label>Lining</label>";

echo "<select name=\"linings_id\" id=\"linings_id\"><option value=\"\" data-price=\"0.00\">--Select Lining--</option><option value=\"none\" data-price=\"0.00\">No Lining</option>";
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
if(isset($thisItemMeta['labor-per-width']) && strlen(trim($thisItemMeta['labor-per-width'])) >0){
	$laborperwidthval=$thisItemMeta['labor-per-width'];
}else{
	$laborperwidthval='';
}
echo $this->Form->input('labor-per-width',['type'=>'number','step'=>'any','value'=>$laborperwidthval,'label'=>'Labor Per Width']);

echo "</fieldset>";




echo "<fieldset class=\"fieldsection\"><legend>HARDWARE</legend>";


if(isset($thisItemMeta['hardware']) && strlen(trim($thisItemMeta['hardware'])) >0){
	$hardwarevals=array('value'=>$thisItemMeta['hardware'],'id'=>'hardware');
}else{
	$hardwarevals=array('value'=>'none','id'=>'hardware');
}
echo "<div class=\"input selectbox\">";
echo "<label>Hardware</label>";
echo $this->Form->select('hardware',['none'=>'No Hardware','basic'=>'Basic','decorative'=>'Decorative'],$hardwarevals);
echo "</div>";



if(isset($thisItemMeta['mount-type']) && strlen(trim($thisItemMeta['mount-type'])) >0){
	$mounttypevals=array('value'=>$thisItemMeta['mount-type'],'id'=>'mount-type');
}else{
	$mounttypevals=array('value'=>'ceiling','id'=>'mount-type');
}
echo "<div id=\"mounttyperow\" class=\"input selectbox\"";
//if(!isset($thisItemMeta['hardware']) || ($thisItemMeta['hardware']=="none" || $thisItemMeta['hardware']=='')){
//	echo " style=\"display:none;\"";
//}
echo ">";
echo "<label>Mount Type</label>";
echo $this->Form->select('mount-type',['ceiling'=>'Ceiling','wall'=>'Wall'],$mounttypevals);
echo "</div>";


if(isset($thisItemMeta['draw']) && strlen(trim($thisItemMeta['draw'])) >0){
	$drawvals=array('value'=>$thisItemMeta['draw'],'id'=>'draw');
}else{
	$drawvals=array('value'=>'center','id'=>'draw');
}
echo "<div id=\"drawrow\" class=\"input selectbox\"";
//if(!isset($thisItemMeta['hardware']) || ($thisItemMeta['hardware']=="none" || $thisItemMeta['hardware']=='')){
//	echo " style=\"display:none;\"";
//}
echo ">";
echo "<label>Draw</label>";
echo $this->Form->select('draw',['left'=>'Left','center'=>'Center','right'=>'Right'],$drawvals);
echo "</div>";





if(isset($thisItemMeta['pinset']) && is_numeric($thisItemMeta['pinset'])){
	$pinsetval=$thisItemMeta['pinset'];
}else{
	$pinsetval=$settings['pinch_pleated_hardware_pinset_default'];
}
echo $this->Form->input('pinset',['type'=>'number','step'=>'any','min'=>'0.5','max'=>'1.75','label'=>'Pin Set','value'=>$pinsetval]);

echo "</fieldset>";





echo "<fieldset class=\"fieldsection\"><legend>FABRIC ROUNDING</legend>";


if(isset($thisItemMeta['force-round-distributed-yds']) && $thisItemMeta['force-round-distributed-yds']=='1'){
	$forcerounddistributedydsChecked=true;
}else{
	$forcerounddistributedydsChecked=false;
}
echo $this->Form->input('force-round-distributed-yds',['type'=>'checkbox','label'=>'Force Rounded Distributed yds','checked'=>$forcerounddistributedydsChecked]);




if(isset($thisItemMeta['force-rounded-widths-eol']) && $thisItemMeta['force-rounded-widths-eol']=='1'){
	$forceroundedwidthseolChecked=true;
}else{
	$forceroundedwidthseolChecked=false;
}
echo $this->Form->input('force-rounded-widths-eol',['type'=>'checkbox','label'=>'Force Rounded Widths EOL','checked'=>$forceroundedwidthseolChecked]);






if(isset($thisItemMeta['force-round-yds-ea']) && $thisItemMeta['force-round-yds-ea']=='1'){
	$forceroundydseaChecked=true;
}else{
	$forceroundydseaChecked=false;
}
echo $this->Form->input('force-round-yds-ea',['type'=>'checkbox','label'=>'Force Rounded yds ea Drapery','checked'=>$forceroundydseaChecked]);


echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>LINING ROUNDING</legend>";

if(isset($thisItemMeta['force-round-lining-widths-ea']) && $thisItemMeta['force-round-lining-widths-ea']=='1'){
	$forceroundliningwidthseaChecked=true;
}else{
	$forceroundliningwidthseaChecked=true;
}
echo $this->Form->input('force-round-lining-widths-ea',['type'=>'checkbox','label'=>'Force Rounded Widths ea Drapery','checked'=>$forceroundliningwidthseaChecked]);





if(isset($thisItemMeta['force-round-lining-widths-eol']) && $thisItemMeta['force-round-lining-widths-eol']=='1'){
	$forceroundliningwidthseolChecked=true;
}else{
	$forceroundliningwidthseolChecked=false;
}
echo $this->Form->input('force-round-lining-widths-eol',['type'=>'checkbox','label'=>'Force Rounded Widths EOL','checked'=>$forceroundliningwidthseolChecked]);



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







if(isset($thisItemMeta['ease']) && is_numeric($thisItemMeta['ease'])){
	$easeval=$thisItemMeta['ease'];
}else{
	$easeval='0';
}
echo $this->Form->input('ease',['type'=>'number','min'=>'0','value'=>$easeval,'label'=>'Ease (%)']);





if(isset($thisItemMeta['header-hem-allowance']) && is_numeric($thisItemMeta['header-hem-allowance'])){
	$headerhemallowanceval=$thisItemMeta['header-hem-allowance'];
}else{
	$headerhemallowanceval=$settings['header-hem-allowance'];
}
echo $this->Form->input('header-hem-allowance',['label'=>'Header/Hem Allowance','value'=>$headerhemallowanceval]);








if(isset($thisItemMeta['grommet-stiffener']) && $thisItemMeta['grommet-stiffener']=='1'){
	$grommetStiffenerChecked=true;
}else{
	$grommetStiffenerChecked=false;
}
echo $this->Form->input('grommet-stiffener',['type'=>'checkbox','label'=>'Grommet Stiffener?','checked'=>$grommetStiffenerChecked]);










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



if(isset($thisItemMeta['trim-yards-per-unit']) && strlen(trim($thisItemMeta['trim-yards-per-unit'])) >0){
	$trimYardsPerUnitVal=$thisItemMeta['trim-yards-per-unit'];
}else{
	$trimYardsPerUnitVal='0';
}
echo $this->Form->input('trim-yards-per-unit',['type'=>'number','step'=>'any','min'=>'0','label'=>'Trim Yards per unit','value'=>$trimYardsPerUnitVal]);



if(isset($thisItemMeta['trim-cost-per-yard']) && strlen(trim($thisItemMeta['trim-cost-per-yard'])) >0){
	$trimCostPerYardVal=$thisItemMeta['trim-cost-per-yard'];
}else{
	$trimCostPerYardVal='0';
}
echo $this->Form->input('trim-cost-per-yard',['type'=>'number','step'=>'any','min'=>'0','label'=>'Trim Cost Per Yard','value'=>$trimCostPerYardVal]);




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





if(isset($thisItemMeta['weights']) && $thisItemMeta['weights']=='1'){
	$weightsChecked=true;
	$weightcountdisplay='block';
}else{
	$weightsChecked=false;
	$weightcountdisplay='none';
}
echo $this->Form->input('weights',['type'=>'checkbox','label'=>'Sewn-In Weights?','checked'=>$weightsChecked]);


echo "<div id=\"weightcountwrap\" class=\"suboptions\" style=\"display:".$weightcountdisplay.";\">";
if(isset($thisItemMeta['weight-count']) && strlen(trim($thisItemMeta['weight-count'])) >0){
	$weightCountVal=$thisItemMeta['weight-count'];
}else{
	$weightCountVal='0';
}
echo $this->Form->input('weight-count',['type'=>'number','step'=>1,'min'=>'0','label'=>'Weight Count','value'=>$weightCountVal]);
echo "</div>";





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
echo "</div>";






if(isset($thisItemMeta['slanted-hems']) && $thisItemMeta['slanted-hems']=='1'){
	$slantedHemsChecked=true;
}else{
	$slantedHemsChecked=false;
}
echo $this->Form->input('slanted-hems',['type'=>'checkbox','label'=>'Slanted Hems?','checked'=>$slantedHemsChecked]);






if(isset($thisItemMeta['chain-weight']) && $thisItemMeta['chain-weight']=='1'){
	$chainWeightChecked=true;
}else{
	$chainWeightChecked=false;
}
echo $this->Form->input('chain-weight',['type'=>'checkbox','label'=>'Chain Weight?','checked'=>$chainWeightChecked]);





if(isset($thisItemMeta['difficult-fabric-surcharge']) && $thisItemMeta['difficult-fabric-surcharge']=='1'){
	$difficultFabricSurchargeChecked=true;
}else{
	$difficultFabricSurchargeChecked=false;
}
echo $this->Form->input('difficult-fabric-surcharge',['type'=>'checkbox','label'=>'Difficult Fabric Surcharge?','checked'=>$difficultFabricSurchargeChecked]);




if(isset($thisItemMeta['vertical-contrast-banding']) && strlen(trim($thisItemMeta['vertical-contrast-banding'])) >0){
	$verticalcontrastbandingval=$thisItemMeta['vertical-contrast-banding'];
	$vcontrastdisplay='block';
}else{
	$verticalcontrastbandingval='None';
	$vcontrastdisplay='none';
}
echo "<div class=\"input selectbox\">
<label for=\"mesh-frame\">V Contrast Banding</label>";
echo $this->Form->select('vertical-contrast-banding',['None'=>'None','One Side'=>'One Side','Both Sides'=>'Both Sides'],['id'=>'vertical-contrast-banding','value'=>$verticalcontrastbandingval]);
echo "</div>";



echo "<div id=\"vcontrastwrap\" style=\"display:".$vcontrastdisplay.";\">";

if(isset($thisItemMeta['vcontrast-yards-per-unit']) && strlen(trim($thisItemMeta['vcontrast-yards-per-unit'])) >0){
	$vcontrastYardsPerUnitVal=$thisItemMeta['vcontrast-yards-per-unit'];
}else{
	$vcontrastYardsPerUnitVal='0';
}
echo $this->Form->input('vcontrast-yards-per-unit',['type'=>'number','step'=>1,'min'=>'0','label'=>'V Contrast Banding Yards per unit','value'=>$vcontrastYardsPerUnitVal]);



if(isset($thisItemMeta['vcontrast-cost-per-yard']) && strlen(trim($thisItemMeta['vcontrast-cost-per-yard'])) >0){
	$vcontrastCostPerYardVal=$thisItemMeta['vcontrast-cost-per-yard'];
}else{
	$vcontrastCostPerYardVal='0';
}
echo $this->Form->input('vcontrast-cost-per-yard',['type'=>'number','step'=>1,'min'=>'0','label'=>'V Contrast Banding Cost Per Yard','value'=>$vcontrastCostPerYardVal]);


echo "</div>";






if(isset($thisItemMeta['horizontal-contrast-banding']) && strlen(trim($thisItemMeta['horizontal-contrast-banding'])) >0){
	$horizontalcontrastbandingval=$thisItemMeta['horizontal-contrast-banding'];
}else{
	$horizontalcontrastbandingval='None';
}
echo "<div class=\"input selectbox\">
<label for=\"mesh-frame\">H Contrast Banding</label>";
echo $this->Form->select('horizontal-contrast-banding',['None'=>'None','Top Only'=>'Top Only','Bottom Only'=>'Bottom Only','Top + Bottom'=>'Top + Bottom'],['id'=>'horizontal-contrast-banding','value'=>$horizontalcontrastbandingval]);
echo "</div>";





echo "</div>";
//expand/collapse end
echo "<br><Br><Br><br>";



echo "</div>";

//end left column

//begin right column
?>


<div id="resultsblock">
<h2>Calculator Results</h2>
<div id="cannotcalculate">Cannot calculate. Missing information.</div>

<?php
	
if(isset($thisItemMeta['return']) && strlen(trim($thisItemMeta['return'])) > 0){
	$returnval=$thisItemMeta['return'];
}else{
	$returnval='';
}	
echo $this->Form->input('return',['label'=>'Return','readonly'=>true,'value'=>$returnval]);



if(isset($thisItemMeta['overlap']) && strlen(trim($thisItemMeta['overlap'])) > 0){
	$overlapval=$thisItemMeta['overlap'];
}else{
	$overlapval='';
}	
echo $this->Form->input('overlap',['label'=>'Overlap','readonly'=>true,'value'=>$overlapval]);




if(isset($thisItemMeta['fw']) && strlen(trim($thisItemMeta['fw'])) > 0){
	$fwval=$thisItemMeta['fw'];
}else{
	$fwval='';
}	
echo $this->Form->input('fw',['label'=>'FW','readonly'=>true,'value'=>$fwval]);





echo "<div id=\"cutwidthwrap\" style=\"";
if($thisItemMeta['railroaded'] == '0' || !isset($thisItemMeta['railroaded'])){
	echo "display:none;";
}else{
	echo "display:block;";
}
echo "\">";
if(isset($thisItemMeta['cw']) && strlen(trim($thisItemMeta['cw'])) > 0){
	$cwval=$thisItemMeta['cw'];
}else{
	$cwval='';
}	
echo $this->Form->input('cw',['label'=>'CW','readonly'=>true,'value'=>$cwval]);
echo "</div>";






if(isset($thisItemMeta['widths-each']) && strlen(trim($thisItemMeta['widths-each'])) > 0){
	$widthseachval=$thisItemMeta['widths-each'];
}else{
	$widthseachval='';
}	
echo $this->Form->input('widths-each',['label'=>'Fab Widths Each','readonly'=>true,'value'=>$widthseachval]);







if(isset($thisItemMeta['rounded-widths-each']) && strlen(trim($thisItemMeta['rounded-widths-each'])) > 0){
	$roundedwidthseachval=$thisItemMeta['rounded-widths-each'];
}else{
	$roundedwidthseachval='';
}	
echo $this->Form->input('rounded-widths-each',['label'=>'Fab Rnd Widths per unit','readonly'=>true,'value'=>$roundedwidthseachval]);






if(isset($thisItemMeta['total-widths']) && strlen(trim($thisItemMeta['total-widths'])) > 0){
	$totalwidthsval=$thisItemMeta['total-widths'];
}else{
	$totalwidthsval='';
}	
echo $this->Form->input('total-widths',['label'=>'TOTAL FAB WIDTHS','readonly'=>true,'value'=>$totalwidthsval]);





if(isset($thisItemMeta['raw-labor-widths']) && strlen(trim($thisItemMeta['raw-labor-widths'])) > 0){
	$rawlaborwidthsval=$thisItemMeta['raw-labor-widths'];
}else{
	$rawlaborwidthsval='';
}	
echo $this->Form->input('raw-labor-widths',['label'=>'Raw Labor Widths per unit','readonly'=>true,'value'=>$rawlaborwidthsval]);






if(isset($thisItemMeta['labor-widths']) && strlen(trim($thisItemMeta['labor-widths'])) > 0){
	$laborwidthsval=$thisItemMeta['labor-widths'];
}else{
	$laborwidthsval='';
}	
echo $this->Form->input('labor-widths',['label'=>'Rnd Labor Widths per unit','readonly'=>true,'value'=>$laborwidthsval]);







if(isset($thisItemMeta['cl']) && strlen(trim($thisItemMeta['cl'])) > 0){
	$clval=$thisItemMeta['cl'];
}else{
	$clval='';
}	
echo $this->Form->input('cl',['label'=>'CL','readonly'=>true,'value'=>$clval]);



if(isset($thisItemMeta['adjusted-cl']) && strlen(trim($thisItemMeta['adjusted-cl'])) > 0){
	$adjclval=$thisItemMeta['adjusted-cl'];
}else{
	$adjclval='';
}	
echo $this->Form->input('adjusted-cl',['label'=>'Fab Adj CL','readonly'=>true,'value'=>$adjclval]);



if(isset($thisItemMeta['vertical-waste']) && strlen(trim($thisItemMeta['vertical-waste'])) > 0){
	$vertwasteval=$thisItemMeta['vertical-waste'];
}else{
	$vertwasteval='';
}	
echo $this->Form->input('vertical-waste',['label'=>'Vertical Waste','readonly'=>true,'value'=>$vertwasteval]);




if(isset($thisItemMeta['yds-per-unit']) && strlen(trim($thisItemMeta['yds-per-unit'])) > 0){
	$yardseachval=$thisItemMeta['yds-per-unit'];
}else{
	$yardseachval='';
}	
echo $this->Form->input('yds-per-unit',['label'=>'Fab Yards per unit','readonly'=>true,'value'=>$yardseachval]);





if(isset($thisItemMeta['total-yds']) && strlen(trim($thisItemMeta['total-yds'])) > 0){
	$totalyardsval=$thisItemMeta['total-yds'];
}else{
	$totalyardsval='';
}	
echo $this->Form->input('total-yds',['label'=>'Total Fab Yards','readonly'=>true,'value'=>$totalyardsval]);





if(isset($thisItemMeta['lining-widths-each']) && strlen(trim($thisItemMeta['lining-widths-each'])) > 0){
	$liningwidthseachval=$thisItemMeta['lining-widths-each'];
}else{
	$liningwidthseachval='';
}	
echo $this->Form->input('lining-widths-each',['label'=>'Lining Widths per unit','readonly'=>true,'value'=>$liningwidthseachval]);




if(isset($thisItemMeta['total-lining-widths']) && strlen(trim($thisItemMeta['total-lining-widths'])) > 0){
	$totalliningwidthsval=$thisItemMeta['total-lining-widths'];
}else{
	$totalliningwidthsval='';
}	
echo $this->Form->input('total-lining-widths',['label'=>'TOTAL LIN WIDTHS','readonly'=>true,'value'=>$totalliningwidthsval]);




if(isset($thisItemMeta['lining-yds-per-unit']) && strlen(trim($thisItemMeta['lining-yds-per-unit'])) > 0){
	$liningyardseachval=$thisItemMeta['lining-yds-per-unit'];
}else{
	$liningyardseachval='';
}	
echo $this->Form->input('lining-yds-per-unit',['label'=>'Lining Yards per unit','readonly'=>true,'value'=>$liningyardseachval]);



if(isset($thisItemMeta['total-lining-yards']) && strlen(trim($thisItemMeta['total-lining-yards'])) > 0){
	$totalliningyardsval=$thisItemMeta['total-lining-yards'];
}else{
	$totalliningyardsval='';
}	
echo $this->Form->input('total-lining-yards',['label'=>'TOTAL LINING YARDS','readonly'=>true,'value'=>$totalliningyardsval]);




if(isset($thisItemMeta['fabric-price']) && strlen(trim($thisItemMeta['fabric-price'])) > 0){
	$fabricpriceval=$thisItemMeta['fabric-price'];
}else{
	$fabricpriceval='';
}	
echo $this->Form->input('fabric-price',['label'=>'Fabric Price per yard','readonly'=>true,'value'=>$fabricpriceval]);






if(isset($thisItemMeta['fabric-price-pu']) && strlen(trim($thisItemMeta['fabric-price-pu'])) > 0){
	$fabricpricepuval=$thisItemMeta['fabric-price-pu'];
}else{
	$fabricpricepuval='';
}	
echo $this->Form->input('fabric-price-pu',['label'=>'Fabric Price per unit','readonly'=>true,'value'=>$fabricpricepuval]);






if(isset($thisItemMeta['total-fabric-price']) && strlen(trim($thisItemMeta['total-fabric-price'])) > 0){
	$totalfabricpriceval=$thisItemMeta['total-fabric-price'];
}else{
	$totalfabricpriceval='';
}	
echo $this->Form->input('total-fabric-price',['label'=>'TOTAL FAB PRICE','readonly'=>true,'value'=>$totalfabricpriceval]);




if(isset($thisItemMeta['lining-cost']) && strlen(trim($thisItemMeta['lining-cost'])) > 0){
	$liningcostval=$thisItemMeta['lining-cost'];
}else{
	$liningcostval='';
}	
echo $this->Form->input('lining-cost',['label'=>'Lining Cost per unit','readonly'=>true,'value'=>$liningcostval]);





if(isset($thisItemMeta['total-lining-cost']) && strlen(trim($thisItemMeta['total-lining-cost'])) > 0){
	$totalliningcostval=$thisItemMeta['total-lining-cost'];
}else{
	$totalliningcostval='';
}	
echo $this->Form->input('total-lining-cost',['label'=>'TOTAL LINING COST','readonly'=>true,'value'=>$totalliningcostval]);







if(isset($thisItemMeta['labor-cost']) && strlen(trim($thisItemMeta['labor-cost'])) > 0){
	$laborcostval=$thisItemMeta['labor-cost'];
}else{
	$laborcostval='';
}	
echo $this->Form->input('labor-cost',['label'=>'Labor Cost per unit','readonly'=>true,'value'=>$laborcostval]);




if(isset($thisItemMeta['total-surcharges']) && strlen(trim($thisItemMeta['total-surcharges'])) >0){
	$totalsurchargesval=$thisItemMeta['total-surcharges'];
}else{
	$totalsurchargesval='';
}
echo $this->Form->input('total-surcharges',['type'=>'number','step'=>'any','readonly'=>true,'value'=>$totalsurchargesval]);


echo "<div style=\"text-align:right; font-size:12px; padding:5px 10px 10px 0;\"><a href=\"#explainmath\">View Surcharge Breakdown</a></div>";



if(isset($thisItemMeta['price']) && strlen(trim($thisItemMeta['price'])) > 0){
	$priceval=$thisItemMeta['price'];
}else{
	$priceval='';
}	
echo $this->Form->input('price',['label'=>'Base Price','readonly'=>true,'value'=>$priceval]);






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
<?php 
} 
?>
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
	var liningValue=$('select[name=linings_id] option:selected').text();
	console.log('Lining Label = '+liningValue);

	pattern = new RegExp(/interlin/gi);
	var interlinedTest = pattern.test(liningValue);

	if($('#com-fabric').is(':checked')){
		//COM
		//determine lining
		if($('select[name=linings_id]').val()=='' || $('select[name=linings_id]').val()=='none'){
			laborValue=<?php echo $settings['wt_pinchpleated_unlined_labor_com_per_width']; ?>;
			console.log('Labor Rate changed to <?php echo $settings['wt_pinchpleated_unlined_labor_com_per_width']; ?> for COM UNLINED');
		}else if(interlinedTest){
			laborValue=<?php echo $settings['wt_pinchpleated_interlined_labor_com_per_width']; ?>;
			console.log('Labor Rate changed to <?php echo $settings['wt_pinchpleated_interlined_labor_com_per_width']; ?> for COM INTERLINED');
		}else{
			laborValue=<?php echo $settings['wt_pinchpleated_lined_labor_com_per_width']; ?>;
			console.log('Labor Rate changed to <?php echo $settings['wt_pinchpleated_lined_labor_com_per_width']; ?> for COM LINED');
		}
	}else{
		//MOM
		//determine lining
		if($('select[name=linings_id]').val()=='' || $('select[name=linings_id]').val()=='none'){
			laborValue=<?php echo $settings['wt_pinchpleated_unlined_labor_mom_per_width']; ?>;
			console.log('Labor Rate changed to <?php echo $settings['wt_pinchpleated_unlined_labor_mom_per_width']; ?> for MOM UNLINED');
		}else if(interlinedTest){
			laborValue=<?php echo $settings['wt_pinchpleated_interlined_labor_mom_per_width']; ?>;
			console.log('Labor Rate changed to <?php echo $settings['wt_pinchpleated_interlined_labor_mom_per_width']; ?> for MOM INTERLINED');
		}else{
			laborValue=<?php echo $settings['wt_pinchpleated_lined_labor_mom_per_width']; ?>;
			console.log('Labor Rate changed to <?php echo $settings['wt_pinchpleated_lined_labor_mom_per_width']; ?> for MOM LINED');
		}

	}





	$('#labor-per-width').val(laborValue);

}



function doCalculation(){
	

	var qty=parseFloat($('#qty').val());
	var fab_w=parseFloat($('#fabric-width').val());

	console.clear();

	calculateLabor();

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








	if($('#railroaded').prop('checked')){
		//railroaded calculation start

		if($('#unit-of-measure').val() == 'panel'){
			$('#return').val($('#default-return').val());
		}else if($('#unit-of-measure').val() == 'pair'){
			$('#return').val((parseFloat($('#default-return').val())*2));
		}else{
			$('#return').val('WARNING: BAD UNIT OF MEASURE');
		}


		if($('#unit-of-measure').val() == 'panel'){
			$('#overlap').val($('#default-overlap').val());
		}else if($('#unit-of-measure').val() == 'pair'){
			$('#overlap').val((parseFloat($('#default-overlap').val())*2));
		}else{
			$('#overlap').val('WARNING: BAD UNIT OF MEASURE');
		}

		

		//finished width
		if($('#unit-of-measure').val() == 'panel' && $('#panel-type').val() == 'Stationary'){
			var multiplier=parseFloat($('#fabric-widths-per-panel').val());
			var fabricWidth=parseFloat($('#fabric-width').val());
			var fullness=(parseFloat($('#fullness').val())/100);
			
			if( multiplier > 1){
				var warn1='OK';
				//add seams, more than 1 fabwidth
				var fw = (((multiplier * fabricWidth) - ((2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) + <?php echo $settings['wt_drapery_seam_width']; ?>)) / fullness);
				console.log('Finished Width = (('+multiplier+' * '+fabricWidth+') - (( 2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) - <?php echo $settings['wt_drapery_seam_width']; ?>) ) / '+fullness+')');
			}else if(multiplier == 1){
				//no seams, 1 full width
				var warn1='OK';
				var fw = (( (multiplier * 54) - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / fullness);
				console.log('Finished Width = (('+multiplier+' * 54) - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / '+fullness+')');
			}else if(multiplier < 1){
				if(fabricWidth < 108){
					var warn1 = 'FABRIC MUST BE AT LEAST 108 FOR HALF WIDTHS';
				}else{
					var warn1='OK';
				}
				//no seams, half width
				var fw = (( (multiplier * fabricWidth) - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / fullness);
				console.log('Finished Width = (('+multiplier+' * '+fabricWidth+') - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / '+fullness+')');
			}
		}else if($('#unit-of-measure').val() == 'pair' || $('#panel-type').val() == 'Operable'){
			var warn1='OK';
			var easepercent=roundToTwo((parseFloat($('#ease').val())/100)).toFixed(2);
			var easeval=roundToTwo((parseFloat($('#rod-width').val())*easepercent)).toFixed(2);
			var fw = ( Math.round((parseFloat($('#rod-width').val())+parseFloat(easeval))) + parseFloat($('#return').val()) + parseFloat($('#overlap').val())  );

		}
		$('#fw').val(fw);







		//lining widths
		if($('#unit-of-measure').val() == 'panel' && $('#panel-type').val() == 'Stationary'){
			
			var widthseach=(((((parseFloat($('#rod-width').val()) + parseFloat(easeval) ) * fullness) + parseFloat($('#return').val()) + parseFloat($('#overlap').val())) + (2*<?php echo $settings['wt_drapery_side_hems_width']; ?>)) / parseFloat($('#fabric-width').val()));

			var liningWidths=parseFloat($('#fabric-widths-per-panel').val());
			var laborwidthseach=parseFloat($('#fabric-widths-per-panel').val());

		}else{
			
			var widthseach =  (((((parseFloat($('#rod-width').val()) + parseFloat(easeval) ) * fullness) + parseFloat($('#return').val()) + parseFloat($('#overlap').val())) + (4*<?php echo $settings['wt_drapery_side_hems_width']; ?>)) / parseFloat($('#fabric-width').val()));

			var laborwidthseach=((((( parseFloat($('#rod-width').val()) * fullness) + parseFloat($('#overlap').val()) + parseFloat($('#return').val())) + parseFloat(easeval) ) + <?php echo $settings['drapery_railroaded_side_hems']; ?>)/54);

			
			var liningWidths=((((parseFloat($('#rod-width').val()) + parseFloat(easeval)) * fullness)+(parseFloat($('#return').val()) + parseFloat($('#overlap').val())))/ parseFloat($('#lining-width').val()));

		}
		
		
		
		$('#widths-each').val(roundToTwo(widthseach).toFixed(2));

		if($('#force-round-lining-widths-ea').is(':checked')){
			liningWidths=Math.ceil(liningWidths);	
		}


		$('#lining-widths-each').val(roundToTwo(liningWidths).toFixed(2));


		if($('#force-round-lining-widths-eol').is(':checked')){
			var tot_lin_widths = Math.ceil((liningWidths * qty));
		}else{
			var tot_lin_widths = (liningWidths * qty);
		}

		$('#total-lining-widths').val(roundToTwo(tot_lin_widths).toFixed(2));


		console.log('lining widths each = '+liningWidths);



		var roundedwidths=Math.ceil(widthseach);
		$('#rounded-widths-each').val(roundedwidths);

		






		$('#widths-each').val(roundToTwo(widthseach).toFixed(2));


		console.log('widths each = '+widthseach);





		if($('#unit-of-measure').val() == 'panel'){
			if($('#force-rounded-widths-eol').is(':checked')){
				$('#total-widths').val(roundToTwo(Math.ceil((widthseach*qty))).toFixed(2));
			}else{
				$('#total-widths').val(roundToTwo((widthseach*qty)).toFixed(2));
			}
		}else{
			$('#total-widths').val((roundedwidths*qty));
		}




		console.log('labor widths = '+laborwidthseach);

		$('#raw-labor-widths').val(roundToTwo(laborwidthseach).toFixed(2));

		var roundedlaborwidthseach=Math.ceil(laborwidthseach);

		//labor widths
		if($('#unit-of-measure').val() == 'panel'){
			var laborwidths=roundedlaborwidthseach;
		}else if($('#unit-of-measure').val() == 'pair'){
			if(roundedlaborwidthseach % 2 == 0){
				var laborwidths=roundedlaborwidthseach;
			}else{
				var laborwidths=(roundedlaborwidthseach+1);
			}
		}
		$('#labor-widths').val(laborwidths);



		var finishLength=parseFloat($('#length').val());
		var headerHemAllowance=parseFloat($('#header-hem-allowance').val());
		var cl=(finishLength + headerHemAllowance);



		var verticalRepeat=parseFloat($('#vert-repeat').val());

		if($('#vert-repeat').val() == 0){
			$('#adjusted-cl').val(cl);
		}else{
			var adjustedCL=(Math.ceil((cl/verticalRepeat))*verticalRepeat);
			$('#adjusted-cl').val(adjustedCL);
		}

		$('#cl').val(cl);



		//Cut Width
		if($('#unit-of-measure').val() == 'panel'){
			var numsides=2;
		}else if($('#unit-of-measure').val() == 'pair'){
			var numsides=4;
		}


		if($('#unit-of-measure').val() == 'panel'){
			if($('#panel-type').val() == 'Stationary'){
				var cw = (54*parseFloat($('#fabric-widths-per-panel').val()));
				console.log('cw = (54 * '+$('#fabric-widths-per-panel').val()+') = '+cw);
			}else{
				//var cw = ((parseFloat($('#rod-width').val()) * fullness) + parseFloat($('#overlap').val()) + parseFloat($('#return').val()) + <?php echo $settings['drapery_railroaded_side_hems']; ?>);
				
				var cw = (((( parseFloat($('#rod-width').val()) * fullness) + parseFloat($('#overlap').val()) + parseFloat($('#return').val())) + parseFloat(easeval) ) + <?php echo $settings['drapery_railroaded_side_hems']; ?>);

			}
		}else if($('#unit-of-measure').val() == 'pair'){
			var cw = ((parseFloat($('#rod-width').val()) * fullness) + parseFloat($('#overlap').val()) + parseFloat($('#return').val()) + (<?php echo $settings['drapery_railroaded_side_hems']; ?> * 2));
			console.log('cw = (('+$('#rod-width').val()+' * '+fullness+' ) + '+$('#overlap').val()+' + '+$('#return').val()+' + <?php echo $settings['drapery_railroaded_side_hems']; ?> *2) = '+cw);
		}
		
		$('#cw').val(cw);





		//RR yardages
		if($('#force-round-yds-ea').is(':checked')){
			var yds_pu = Math.ceil(((cw / 36) * 1.05));
		}else{
			var yds_pu = ((cw / 36) * 1.05);
		}
		



		var tot_yds = (yds_pu*qty);

		if($('#force-round-distributed-yds').is(':checked')){
			tot_yds = Math.ceil(tot_yds);
			//new yards each override
			yds_pu = (tot_yds / qty);
		}


		console.log('yds_pu = '+yds_pu);
		$('#yds-per-unit').val(roundToTwo(yds_pu).toFixed(2));

		console.log('tot_yds = '+tot_yds);
		$('#total-yds').val(roundToTwo(tot_yds).toFixed(2));



		//lining yardages

		
		var liningYardsPU=( ( ( (liningWidths * cl) / 36) * 1.05));
		console.log('liningYardsPU = (('+liningWidths+' * '+cl+') / 36) * 1.05)');

		$('#lining-yds-per-unit').val( roundToTwo(liningYardsPU).toFixed(2) );

		var totalLiningYards=(liningYardsPU * qty);
		$('#total-lining-yards').val( roundToTwo(totalLiningYards).toFixed(2) );



		//railroaded calculation end
	}else{
		//utr calculation start
		
		var fullness=(parseFloat($('#fullness').val())/100);
		
		
		if($('#unit-of-measure').val() == 'panel'){
			$('#return').val($('#default-return').val());
		}else if($('#unit-of-measure').val() == 'pair'){
			$('#return').val((parseFloat($('#default-return').val())*2));
		}else{
			$('#return').val('WARNING: BAD UNIT OF MEASURE');
		}


		if($('#unit-of-measure').val() == 'panel'){
			$('#overlap').val($('#default-overlap').val());
		}else if($('#unit-of-measure').val() == 'pair'){
			$('#overlap').val((parseFloat($('#default-overlap').val())*2));
		}else{
			$('#overlap').val('WARNING: BAD UNIT OF MEASURE');
		}



		//finished width
		var easepercent=roundToTwo((parseFloat($('#ease').val())/100)).toFixed(2);
		var easeval=roundToTwo((parseFloat($('#rod-width').val())*easepercent)).toFixed(2);
		var fw = ( Math.round((parseFloat($('#rod-width').val())+parseFloat(easeval))) + parseFloat($('#return').val()) + parseFloat($('#overlap').val())  );

		
		$('#fw').val(fw);
		


		//widths each
		//lining widths
		if($('#unit-of-measure').val() == 'panel'){
			
			var widthseach=(((((parseFloat($('#rod-width').val()) + parseFloat(easeval) ) * fullness) + parseFloat($('#return').val()) + parseFloat($('#overlap').val())) + (2*<?php echo $settings['wt_drapery_side_hems_width']; ?>)) / parseFloat($('#fabric-width').val()));

			var liningWidths=parseFloat($('#fabric-widths-per-panel').val());

			var laborwidthseach=Math.ceil(parseFloat($('#fabric-widths-per-panel').val()));

		}else if($('#unit-of-measure').val() == 'pair'){
			
			var widthseach =  (((((parseFloat($('#rod-width').val()) + parseFloat(easeval) ) * fullness) + parseFloat($('#return').val()) + parseFloat($('#overlap').val())) + (4*<?php echo $settings['wt_drapery_side_hems_width']; ?>)) / parseFloat($('#fabric-width').val()));

			var laborwidthseach=((((parseFloat($('#rod-width').val()) + parseFloat(easeval)) * fullness)+(parseFloat($('#return').val()) + parseFloat($('#overlap').val())))/54);

			
			var liningWidths=((((parseFloat($('#rod-width').val()) + parseFloat(easeval)) * fullness)+(parseFloat($('#return').val()) + parseFloat($('#overlap').val())))/ parseFloat($('#lining-width').val()));

			//var liningWidths=((Math.ceil(widthseach) * parseFloat($('#fabric-width').val())) / parseFloat($('#lining-width').val()) );

		}
		
		

		$('#widths-each').val(roundToTwo(widthseach).toFixed(2));

		if($('#force-round-lining-widths-ea').is(':checked')){
			liningWidths=Math.ceil(liningWidths);	
		}

		$('#lining-widths-each').val(roundToTwo(liningWidths).toFixed(2));


		if($('#force-round-lining-widths-eol').is(':checked')){
			var tot_lin_widths = Math.ceil((liningWidths * qty));
		}else{
			var tot_lin_widths = (liningWidths * qty);
		}

		$('#total-lining-widths').val(tot_lin_widths);



		$('#raw-labor-widths').val(roundToTwo(laborwidthseach).toFixed(2));

		/*
		var roundedwidths=Math.ceil(widthseach);
		$('#rounded-widths-each').val(roundedwidths);
		*/

		var widthseachFloor=Math.floor(widthseach);
		var widthseachDiff = (widthseach - widthseachFloor);

		if(widthseachDiff >= <?php echo floatval($settings['ppd_fab_widths_ea_rounding_trigger']); ?>){
			var roundedwidths = Math.ceil(widthseach);
		}else{
			var roundedwidths = Math.floor(widthseach);
		}

		$('#rounded-widths-each').val(roundedwidths);


		console.log('widths each = '+widthseach);
		console.log('lining widths each = '+liningWidths);

		if($('#unit-of-measure').val() == 'panel'){
			if($('#force-rounded-widths-eol').is(':checked')){
				$('#total-widths').val(roundToTwo(Math.ceil((widthseach*qty))).toFixed(2));
			}else{
				$('#total-widths').val(roundToTwo((widthseach*qty)).toFixed(2));
			}
		}else{
			$('#total-widths').val((roundedwidths*qty));
		}





		var roundedlaborwidthseach=Math.ceil(laborwidthseach);

		//labor widths
		if($('#unit-of-measure').val() == 'panel'){
			var laborwidths=roundedlaborwidthseach;
		}else if($('#unit-of-measure').val() == 'pair'){
			if(roundedlaborwidthseach % 2 == 0){
				var laborwidths=roundedlaborwidthseach;
			}else{
				var laborwidths=(roundedlaborwidthseach+1);
			}
		}
		$('#labor-widths').val(laborwidths);


		var finishLength=parseFloat($('#length').val());
		var headerHemAllowance=parseFloat($('#header-hem-allowance').val());
		var cl=(finishLength + headerHemAllowance);

		var verticalRepeat=parseFloat($('#vert-repeat').val());

		if($('#vert-repeat').val() == 0){
			$('#adjusted-cl').val(cl);
		}else{
			var adjustedCL=(Math.ceil((cl/verticalRepeat))*verticalRepeat);
			$('#adjusted-cl').val(adjustedCL);
		}

		$('#cl').val(cl);


		var vertwaste=(parseFloat($('#adjusted-cl').val()) - parseFloat($('#cl').val()));
		$('#vertical-waste').val(vertwaste);



		//UTR yardages
		if($('#force-round-yds-ea').is(':checked')){
			var yds_pu = Math.ceil((( ((parseFloat($('#total-widths').val())*parseFloat($('#adjusted-cl').val())) / qty ) /36)*1.05));
		}else{
			var yds_pu = ((( (parseFloat($('#total-widths').val())*parseFloat($('#adjusted-cl').val())) / qty ) /36)*1.05);
		}
		



		var tot_yds = (yds_pu*qty);

		if($('#force-round-distributed-yds').is(':checked')){
			tot_yds = Math.ceil(tot_yds);
			//new yards each override
			yds_pu = (tot_yds / qty);
		}


		console.log('yds_pu = '+yds_pu);
		$('#yds-per-unit').val(roundToTwo(yds_pu).toFixed(2));

		console.log('tot_yds = '+tot_yds);
		$('#total-yds').val(roundToTwo(tot_yds).toFixed(2));



		//lining yardages

		var liningYardsPU=( ( ( (tot_lin_widths * cl) / 36) * 1.05) / qty);
		$('#lining-yds-per-unit').val( roundToTwo(liningYardsPU).toFixed(2) );

		var totalLiningYards=(liningYardsPU * qty);
		$('#total-lining-yards').val( roundToTwo(totalLiningYards).toFixed(2) );

		//utr calculation end

	}






	<?php
		if($fabricid=='custom'){
		?>
				if(qty > 0 && $('#custom-fabric-cost-per-yard').val() != '' && tot_yds > 0){
					$.each(rulesets,function(index,value){
						if(fab_ppy >= parseFloat(value.price_low) && fab_ppy <= parseFloat(value.price_high) && tot_yds >= parseFloat(value.yds_low) && tot_yds <= parseFloat(value.yds_high)){
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
		
		if(qty >0 && $('select[name=fabric-cost-per-yard]').val() != '' && tot_yds > 0){
			//fab_ppy
		   	$.each(rulesets,function(index,value){
				if(fab_ppy >= parseFloat(value.price_low) && fab_ppy <= parseFloat(value.price_high) && tot_yds >= parseFloat(value.yds_low) && tot_yds <= parseFloat(value.yds_high)){
					$('#fabricmarkupwrap small').hide();
					$('#fabric-markup').val(value.markup);
					$('#fabricmarkupinnerwrap').show();
					$('#inboundfreightwrap small').hide();
					$('#inboundfreightinnerwrap').show();
				}
		   	});
		}
		
		<?php } ?>


		//inbound freight calculation
		var ibfrtpy=0;
		if(fab_w >= 54 && fab_w <= 72){
			if(tot_yds < 25){
				ibfrtpy=roundToTwo((25.00/tot_yds)).toFixed(2);
			}else if(tot_yds >= 25 && tot_yds <= 59.99){
				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds25-59']; ?>;
			}else if(tot_yds >= 60 && tot_yds <= 249.99){
				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds60-249']; ?>;
			}else if(tot_yds >= 250){
				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds250plus']; ?>;
			}
		}else if(fab_w >= 73 && fab_w <= 130){
			if(tot_yds < 25){
				ibfrtpy=roundToTwo((25.00/tot_yds)).toFixed(2);
			}else if(tot_yds >= 25 && tot_yds <= 59.99){
				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds25-59']; ?>;
			}else if(tot_yds >= 60 && tot_yds <= 249.99){
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



		//cost and price field crunch
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

		/*
		var fabricCost=(parseFloat(fab_ppy)+parseFloat(ibfrtpy));
		console.log('Fabric COST per yard = '+fabricCost);
		$('#fabric-cost').val(fabricCost);
		*/

		var fab_yds=parseFloat($('#yds-per-unit').val());


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
			console.log('fab_price = '+markedupFabric);

		}

		var totfab_cost = (fab_cost * qty);

		if($('#com-fabric').is(':checked')){
			markedupFabric=0;
		}

		$('#fabric-price').val(roundToTwo(markedupFabric).toFixed(2));

		var fabPricePU=(parseFloat(markedupFabric)*parseFloat($('#yds-per-unit').val()));

		$('#fabric-price-pu').val(roundToTwo(fabPricePU).toFixed(2));

		var totalFabricPrice=(parseFloat(fabPricePU) * qty);
		$('#total-fabric-price').val(roundToTwo(totalFabricPrice).toFixed(2));


		var liningCost=(parseFloat($('#lining-price-per-yd').val()) * parseFloat($('#lining-yds-per-unit').val()) );
		$('#lining-cost').val(roundToTwo(liningCost).toFixed(2));

		var totalLiningCost=(parseFloat(liningCost) * qty);
		$('#total-lining-cost').val(roundToTwo(totalLiningCost).toFixed(2));


		var laborPU=(parseFloat($('#labor-per-width').val()) * parseFloat($('#labor-widths').val()));
		$('#labor-cost').val(roundToTwo(laborPU).toFixed(2));



		//do the math for Trim costs
		var trimCost=(parseFloat($('#trim-cost-per-yard').val()) * parseFloat($('#trim-yards-per-unit').val()));
		console.log('Base Price increased by $'+trimCost+' for TRIM COST');

		var basePrice=(fabPricePU+liningCost+laborPU+trimCost);



		//add-ons
		var startPrice=basePrice;
		$('#start-price-val').val(startPrice);
		var addOnTotals=0;
		var addOnText='';

		if($('#trim-sewn-on').is(':checked')){
			var trimsewnonIncrease=(parseFloat($('#trim-lf').val()) * <?php echo $settings['wt_trim_sewn_on_per_lf']; ?>);
			basePrice=(basePrice+trimsewnonIncrease);
			addOnTotals=(addOnTotals+trimsewnonIncrease);
			addOnText += '<tr><td>Sewn-On Trim ('+$('#trim-lf').val()+' LF)</td><td>$'+roundToTwo(parseFloat(trimsewnonIncrease)).toFixed(2)+'</td></tr>';
		}


		if($('#covered-buttons').is(':checked')){
			var coveredButtonsIncrease=(parseFloat($('#covered-buttons-count').val()) * <?php echo $settings['cornice_covered_buttons_each']; ?>);
			basePrice=(basePrice+coveredButtonsIncrease);
			addOnTotals=(addOnTotals+coveredButtonsIncrease);
			addOnText += '<tr><td>'+$('#covered-buttons-count').val()+'X Covered Buttons</td><td>$'+roundToTwo(parseFloat(coveredButtonsIncrease)).toFixed(2)+'</td></tr>';
		}


		if($('#weights').is(':checked')){
			var weightsIncrease=(parseFloat($('#weight-count').val()) * <?php echo $settings['sewn_in_drapery_weights']; ?>);
			basePrice=(basePrice+weightsIncrease);
			addOnTotals=(addOnTotals+weightsIncrease);
			addOnText += '<tr><td>'+$('#weight-count').val()+'X Sewn-In Weights</td><td>$'+roundToTwo(parseFloat(weightsIncrease)).toFixed(2)+'</td></tr>';
		}


		if($('#rings').is(':checked')){
			var ringsIncrease=(parseFloat($('#rings-count').val()) * <?php echo $settings['drapery_rings_tassles_each']; ?>);
			basePrice=(basePrice+ringsIncrease);
			addOnTotals=(addOnTotals+ringsIncrease);
			addOnText += '<tr><td>'+$('#rings-count').val()+'X Sewn-On Rings</td><td>$'+roundToTwo(parseFloat(ringsIncrease)).toFixed(2)+'</td></tr>';
		}


		if($('#tassels').is(':checked')){
			var tasselsIncrease=(parseFloat($('#tassels-count').val()) * <?php echo $settings['drapery_rings_tassles_each']; ?>);
			basePrice=(basePrice+tasselsIncrease);
			addOnTotals=(addOnTotals+tasselsIncrease);
			addOnText += '<tr><td>'+$('#tassels-count').val()+'X Sewn-On Tassels</td><td>$'+roundToTwo(parseFloat(tasselsIncrease)).toFixed(2)+'</td></tr>';
		}


		if($('#slanted-hems').is(':checked')){
			var slantedHemsIncrease=(parseFloat($('#labor-widths').val()) * <?php echo $settings['drapery_slanted_hems_per_width']; ?>);
			basePrice=(basePrice+slantedHemsIncrease);
			addOnTotals=(addOnTotals+slantedHemsIncrease);
			addOnText += '<tr><td>Slanted Hems</td><td>$'+roundToTwo(parseFloat(slantedHemsIncrease)).toFixed(2)+'</td></tr>';
		}


		if($('#chain-weight').is(':checked')){
			var chainWeightIncrease=(parseFloat($('#labor-widths').val()) * <?php echo $settings['drapery_chain_weights_per_width']; ?>);
			basePrice=(basePrice+chainWeightIncrease);
			addOnTotals=(addOnTotals+chainWeightIncrease);
			addOnText += '<tr><td>Chain Weight</td><td>$'+roundToTwo(parseFloat(chainWeightIncrease)).toFixed(2)+'</td></tr>';
		}


		if($('#difficult-fabric-surcharge').is(':checked')){
			var difficultFabricIncrease=(parseFloat($('#labor-widths').val()) * <?php echo $settings['wt_difficult_fabric_surcharge_per_width']; ?>);
			basePrice=(basePrice+difficultFabricIncrease);
			addOnTotals=(addOnTotals+difficultFabricIncrease);
			addOnText += '<tr><td>Difficult Fabric Surcharge</td><td>$'+roundToTwo(parseFloat(difficultFabricIncrease)).toFixed(2)+'</td></tr>';
		}


		
		//CONTRAST BANDING INCREASE
		if($('#vertical-contrast-banding').val() == 'One Side'){
			var VerticalBandingLF=Math.ceil(parseFloat($('#length').val())/12);
			var verticalContrastOneSideIncrease=(<?php echo $settings['wt_contrast_straight_banding_per_lf']; ?>*VerticalBandingLF);
			basePrice=(basePrice + verticalContrastOneSideIncrease);
			addOnTotals=(addOnTotals+verticalContrastOneSideIncrease);

			addOnText += '<tr><td>Vertical Contrast Banding, One Side ('+VerticalBandingLF+')</td><td>$'+roundToTwo(parseFloat(verticalContrastOneSideIncrease)).toFixed(2)+'</td></tr>';

		}else if($('#vertical-contrast-banding').val() == 'Both Sides'){
			var VerticalBandingLF=Math.ceil(parseFloat($('#length').val())/12);
			var verticalContrastBothSidesIncrease=(2*(<?php echo $settings['wt_contrast_straight_banding_per_lf']; ?>*VerticalBandingLF));
			basePrice=(basePrice + verticalContrastBothSidesIncrease);
			addOnTotals=(addOnTotals+verticalContrastBothSidesIncrease);

			addOnText += '<tr><td>Vertical Contrast Banding, Both Sides ('+VerticalBandingLF+' LF)</td><td>$'+roundToTwo(parseFloat(verticalContrastBothSidesIncrease)).toFixed(2)+'</td></tr>';

		}

		if($('#horizontal-contrast-banding').val() == 'Top Only'){
			var HorizontalBandingLF=Math.ceil( ( ( parseFloat($('#labor-widths').val()) * parseFloat($('#fabric-width').val())) /12) )
			var horizontalContrastTopOnlyIncrease=(<?php echo $settings['wt_contrast_straight_banding_per_lf']; ?>*HorizontalBandingLF);
			basePrice=(basePrice+horizontalContrastTopOnlyIncrease);
			addOnTotals=(addOnTotals+horizontalContrastTopOnlyIncrease);

			addOnText += '<tr><td>Horizontal Contrast Banding, Top Only ('+HorizontalBandingLF+' LF)</td><td>$'+roundToTwo(parseFloat(horizontalContrastTopOnlyIncrease)).toFixed(2)+'</td></tr>';

		}else if($('#horizontal-contrast-banding').val() == 'Bottom Only'){
			var HorizontalBandingLF=Math.ceil( ( ( parseFloat($('#labor-widths').val()) * parseFloat($('#fabric-width').val())) /12) )
			var horizontalContrastBottomOnlyIncrease=(<?php echo $settings['wt_contrast_straight_banding_per_lf']; ?>*HorizontalBandingLF);
			basePrice=(basePrice+horizontalContrastBottomOnlyIncrease);
			addOnTotals=(addOnTotals+horizontalContrastBottomOnlyIncrease);
			
			addOnText += '<tr><td>Horizontal Contrast Banding, Bottom Only ('+HorizontalBandingLF+')</td><td>$'+roundToTwo(parseFloat(horizontalContrastBottomOnlyIncrease)).toFixed(2)+'</td></tr>';

		}else if($('#horizontal-contrast-banding').val() == 'Top + Bottom'){
			var HorizontalBandingLF=Math.ceil( ( ( parseFloat($('#labor-widths').val()) * parseFloat($('#fabric-width').val())) /12) )
			var horizontalContrastTopAndBottomIncrease=(2*(<?php echo $settings['wt_contrast_straight_banding_per_lf']; ?>*HorizontalBandingLF));
			basePrice=(basePrice+horizontalContrastTopAndBottomIncrease);
			addOnTotals=(addOnTotals+horizontalContrastTopAndBottomIncrease);
			
			addOnText += '<tr><td>Horizontal Contrast Banding, Top + Bottom ('+(HorizontalBandingLF+HorizontalBandingLF)+' LF)</td><td>$'+roundToTwo(parseFloat(horizontalContrastTopAndBottomIncrease)).toFixed(2)+'</td></tr>';

		}



		var finishedLength=parseFloat($('#length').val());
		if(finishedLength > 108 && finishedLength <= 168){
			var bigfloneIncrease=(parseFloat($('#labor-widths').val()) * <?php echo $settings['wt_drapes_108-168fl_per_width']; ?>);
			basePrice=(basePrice+bigfloneIncrease);
			addOnTotals=(addOnTotals+bigfloneIncrease);
			
			addOnText += '<tr><td>Oversize Finished Length (108&quot;-168&quot;)</td><td>$'+roundToTwo(parseFloat(bigfloneIncrease)).toFixed(2)+'</td></tr>';
		}


		if(finishedLength > 168){
			var bigfltwoIncrease=(parseFloat($('#labor-widths').val()) * <?php echo $settings['wt_drapes_greater_than_168_per_width']; ?>);
			basePrice=(basePrice+bigfltwoIncrease);
			addOnTotals=(addOnTotals+bigfltwoIncrease);
			
			addOnText += '<tr><td>Oversize Finished Length (168&quot; +)</td><td>$'+roundToTwo(parseFloat(bigfltwoIncrease)).toFixed(2)+'</td></tr>';
		}


		if(parseFloat($('#labor-widths').val()) > 6){
			var oversizeIncrease=(parseFloat($('#labor-widths').val()) * <?php echo $settings['wt_greater_than_six_widths_per_width']; ?>);
			basePrice=(basePrice+oversizeIncrease);
			addOnTotals=(addOnTotals+oversizeIncrease);
			
			addOnText += '<tr><td>OVERSIZE (> 6 widths)</td><td>$'+roundToTwo(parseFloat(oversizeIncrease)).toFixed(2)+'</td></tr>';
		}


		//end add-ons
		$('#add-on-total-val').val(addOnTotals);
		$('#add-on-text-val').val(addOnText);




		$('#price').val(roundToTwo(basePrice).toFixed(2));

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

    //warn1
	if(warn1 != 'OK'){
		warningboxcontent += '<img src="/img/delete.png" /> '+warn1+'<br>';

		if(warn1 == 'FABRIC MUST BE AT LEAST 108 FOR HALF WIDTHS'){
			$('#fabric-widths-per-panel,#fabric-width').parent().addClass('badvalue');
		}else{
			$('#fabric-widths-per-panel,#fabric-width').parent().removeClass('badvalue');
		}
        warncount++;
	}else{
		$('#fabric-widths-per-panel,#fabric-width').parent().removeClass('badvalue');
	}



	if($('#hardware').val() == 'none'){
		warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> NO HARDWARE SELECTED</span><br>';
		$('#hardware').parent().addClass('alertcontent');
	    warncount++;
	}else{
		$('#hardware').parent().removeClass('alertcontent');
	}



	if($('#railroaded').prop('checked')){
    	if((parseFloat($('#length').val()) + parseFloat($('#header-hem-allowance').val())) > parseFloat($('#fabric-width').val())){
    		warningboxcontent += '<img src="/img/delete.png" /> FABRIC TOO NARROW FOR YOUR INPUTS<br>';
    		$('#length,#header-hem-allowance,#fabric-width').parent().addClass('badvalue');
    		warncount++;
    	}else{
    		$('#length,#header-hem-allowance,#fabric-width').parent().removeClass('badvalue');
    	}
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
	
<?php
if($userData['scroll_calcs'] == 1){
?>
correctColHeights();
setInterval('correctColHeights()',500);
<?php } ?>


$('#unit-of-measure,#panel-type').change(function(){
	
	if($('#unit-of-measure').val() == 'panel'){
		$('#orientationwrap').show('fast');
		if($('#panel-type').val() == 'Stationary'){
			if($('#orientation option[value=\'Center\']').length == 0){
				$('#orientation').append('<option value="Center">Center</option>');
			}
		}else if($('#panel-type').val() == 'Operable'){
			if($('#orientation option[value=\'Center\']').length > 0){
				$('#orientation option[value=\'Center\']').remove();
			}
		}
		
	}else{
		$('#orientationwrap').hide('fast');
	}
	
});



$('#railroaded').click(function(){
	
	

	if($(this).prop('checked')){
		$('#cutwidthwrap').show('fast');
		$('#widths-each').parent().hide('fast');
		$('#rounded-widths-each').parent().hide('fast');
		$('#total-widths').parent().hide('fast');
		$('#adjusted-cl').parent().hide('fast');
		$('#vert-repeat').parent().hide('fast');
		$('#vertical-waste').parent().hide('fast');
	}else{
		$('#cutwidthwrap').hide('fast');
		$('#widths-each').parent().show('fast');
		$('#rounded-widths-each').parent().show('fast');
		$('#total-widths').parent().show('fast');
		$('#adjusted-cl').parent().show('fast');
		$('#vert-repeat').parent().show('fast');
		$('#vertical-waste').parent().show('fast');
	}

});

$('#railroaded').change(function(){
		/*
	if($('#unit-of-measure').val() == 'panel' && $('#panel-type').val() == 'Stationary'){
		$('#width-of-window').parent().hide('fast');
		$('#wall-left').parent().hide('fast');
		$('#wall-right').parent().hide('fast');
		$('#rod-width').parent().hide('fast');
		$('#fabric-widths-per-panel').parent().show('fast');
	}else{
		$('#width-of-window').parent().show('fast');
		$('#wall-left').parent().show('fast');
		$('#wall-right').parent().show('fast');
		$('#rod-width').parent().show('fast');
		$('#fabric-widths-per-panel').parent().hide('fast');
	}*/

	if($(this).prop('checked')){
		$('#cutwidthwrap').show('fast');
		$('#widths-each').parent().hide('fast');
		$('#rounded-widths-each').parent().hide('fast');
		$('#total-widths').parent().hide('fast');
		$('#adjusted-cl').parent().hide('fast');
		$('#vert-repeat').parent().hide('fast');
		$('#vertical-waste').parent().hide('fast');
	}else{
		$('#cutwidthwrap').hide('fast');
		$('#widths-each').parent().show('fast');
		$('#rounded-widths-each').parent().show('fast');
		$('#total-widths').parent().show('fast');
		$('#adjusted-cl').parent().show('fast');
		$('#vert-repeat').parent().show('fast');
		$('#vertical-waste').parent().show('fast');
	}

});



$('#panel-type').change(function(){
	/*
	if($(this).val() == 'Stationary'){
		$('#width-of-window').parent().hide('fast');
		$('#wall-left').parent().hide('fast');
		$('#wall-right').parent().hide('fast');
		$('#rod-width').parent().hide('fast');
		$('#fabric-widths-per-panel').parent().show('fast');
	}else{
		$('#width-of-window').parent().show('fast');
		$('#wall-left').parent().show('fast');
		$('#wall-right').parent().show('fast');
		$('#rod-width').parent().show('fast');
		$('#fabric-widths-per-panel').parent().hide('fast');
	}
	*/
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



	
	$('#goadvanced').click(function(){
		if($('#expandcollapse').is(':visible')){
			$('#expandcollapse').hide('fast');
			$('#advancedfieldsvisible').val('0');
		}else{
			$('#expandcollapse').show('fast');
			$('#advancedfieldsvisible').val('1');
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



	$('#vertical-contrast-banding').change(function(){
		if($(this).val() != 'None'){
			$('#vcontrastwrap').show('fast');
		}else{
			$('#vcontrastwrap').hide('fast');
		}
	});


	
	$('#calcformleft input,#calcformleft select,#markup,#tier_adjustment,#add_surcharge').keyup(function(){
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});
	
	$('#calcformleft input,#calcformleft select,#markup,#tier_adjustment,#add_surcharge').change(function(){
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




<?php
if(isset($isedit) && $isedit=='1'){
//do not set default

}else{
?>
	$('#linings_id').val('1');
	$('#lining-price-per-yd').val($('#linings_id option[value=1]').attr('data-price'));
	canCalculate();
<?php } ?>



	$('#width-of-window,#wall-left,#wall-right').change(function(){
		if($('#width-of-window').val() != '0' && $('#wall-left').val() != '' && $('#wall-right').val() != ''){
			$('#rod-width').val((parseFloat($('#width-of-window').val())+parseFloat($('#wall-left').val())+parseFloat($('#wall-right').val())));
			if(canCalculate()){
				doCalculation();
			}
		}else{
			$('#rod-width').val('');
			if(canCalculate()){
				doCalculation();
			}
		}
	});

	$('#width-of-window,#wall-left,#wall-right').keyup(function(){
		if($('#width-of-window').val() != '0' && $('#wall-left').val() != '' && $('#wall-right').val() != ''){
			$('#rod-width').val((parseFloat($('#width-of-window').val())+parseFloat($('#wall-left').val())+parseFloat($('#wall-right').val())));
			if(canCalculate()){
				doCalculation();
			}
		}else{
			$('#rod-width').val('');
			if(canCalculate()){
				doCalculation();
			}
		}
	});


	/*$('#hardware').change(function(){
		if($(this).val() == 'decorative' || $(this).val() == 'basic'){
			$('#mounttyperow').show('fast');
			$('#drawrow').show('fast');
		}else{
			$('#mounttyperow').hide('fast');
			$('#drawrow').hide('fast');
		}
	});*/



	$('#hardware,#mount-type').change(function(){
		if($('#hardware').val() == 'basic' && $('#mount-type').val() == 'ceiling'){
			$('#pinset').val('<?php echo $settings['pinch_pleated_basichw_ceiling_pinset']; ?>');
		}else if($('#hardware').val() == 'basic' && $('#mount-type').val() == 'wall'){
			$('#pinset').val('<?php echo $settings['pinch_pleated_basichw_wall_pinset']; ?>');
		}else if($('#hardware').val() == 'decorative' && $('#mount-type').val() == 'ceiling'){
			$('#pinset').val('<?php echo $settings['pinch_pleated_decohw_ceiling_pinset']; ?>');
		}else if($('#hardware').val() == 'decorative' && $('#mount-type').val() == 'wall'){
			$('#pinset').val('<?php echo $settings['pinch_pleated_decohw_wall_pinset']; ?>');
		}else if($('#hardware').val() == 'none' && $('#mount-type').val() == 'ceiling'){
			$('#pinset').val('<?php echo $settings['pinch_pleated_nohw_ceiling_pinset']; ?>');
		}else if($('#hardware').val() == 'none' && $('#mount-type').val() == 'wall'){
			$('#pinset').val('<?php echo $settings['pinch_pleated_nohw_wall_pinset']; ?>');
		}
	});



	$('#com-fabric').change(function(){
		calculateLabor();
		if($(this).prop('checked')){
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated');
			if(canCalculate()){
				doCalculation();
			}
		}
	});

	$('#com-fabric').click(function(){
		calculateLabor();
		if($(this).prop('checked')){
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated');
			if(canCalculate()){
				doCalculation();
			}
		}
	});



	$('#linings_id').change(function(){
		if($(this).val()=='5'){
			//alert($('#fabric-width').val());
			$('#lining-width').val($('#fabric-width').val());
		}else if($(this).val() == 'none'){
			//alert('0');
			$('#lining-width').val('0');
		}else{
			//alert('54');
			$('#lining-width').val('54');
		}

		if($(this).val()=='none' || $(this).val()=='5'){
			$('#lining-width').parent().hide('fast');
			$('#lining-price-per-yd').parent().hide('fast');
		}else{
			$('#lining-width').parent().show('fast');
			$('#lining-price-per-yd').parent().show('fast');
		}


		$('#lining-price-per-yd').val($(this).find('option:selected').attr('data-price'));

	});


	$('#draw').change(function(){
		if($(this).val() == 'Other'){
			$('#drawotherwrap').show('fast');
		}else{
			$('#drawotherwrap').hide('fast');
		}
	});


	
	$('#unit-of-measure').change(function(){
		if($(this).val() == 'panel'){
			$('#default-overlap').val(0);
		}else{
			$('#default-overlap').val(3.5);
		}

		$('div.calculatebutton button').trigger('click');
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


	if($('#unit-of-measure').val() == ''){
		$('#unit-of-measure').removeClass('notvalid').removeClass('validated').addClass('notvalid');
	}else{
		$('#unit-of-measure').removeClass('notvalid').removeClass('validated').addClass('validated');
	}



	if($('#panel-type').val() == ''){
		$('#panel-type').removeClass('notvalid').removeClass('validated').addClass('notvalid');
	}else{
		$('#panel-type').removeClass('notvalid').removeClass('validated').addClass('validated');
	}

	

	if($('#unit-of-measure').val() == 'panel' && $('#orientation').val() == ''){
		$('#orientation').removeClass('notvalid').removeClass('validated').addClass('notvalid');
	}else{
		$('#orientation').removeClass('notvalid').removeClass('validated').addClass('validated');
	}

	
	if($('#unit-of-measure').val() == 'panel'){
		$('#rod-width').removeClass('notvalid').removeClass('validated');
		
	}else{
		if($('#rod-width').val() == ''){
			$('#rod-width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		}else{
			$('#rod-width').removeClass('notvalid').removeClass('validated').addClass('validated');
		}
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



	if(errorcount > 0){
		return false;
	}else{
		return true;
	}
}


</script>
<Br><Br><Br><Br>
<div id="explainmath"></div>