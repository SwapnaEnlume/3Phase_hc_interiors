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
	

/* PPSASCRUM-56: start */
/* #goadvanced{ font-size:12px; color:#000; background:#ccc; border:1px solid #000; padding:5px 10px; margin:0; display:inline-block; } */
#goadvanced { float:center; width:40%; height: 55px; padding:5px 0; }
#goadvanced button { padding:5px 10px !important; background:#000 !important; font-size:12px; }
/* PPSASCRUM-56: end */

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

/* PPSASCRUM-56: start */
#fabric_notes { width:50% !important; }

#unit-of-measure, #full_width, #crinoline, #stiffener { width: 25% !important; }

#fullness { width: 21% !important; }

label[for=location], label[for=usage], label[for='pleat_type'] { width:35% !important; }
#location, #usage { width:60% !important; }
#pleat_type { width:40% !important; }

#calcformleft > fieldset.fieldsection.orphan-section div.input.select:nth-of-type(odd){ background:#f8f8f8; display: inline-block; width: 96%; padding: 1.5%; height: auto; }
#calcformleft > fieldset.fieldsection.orphan-section div.input.select:nth-of-type(even){ background:#FFF; display: inline-block; width: 96%; padding: 1.5%; height: auto; }
.orphan-section { background:#dddddd; width: 96%; padding: 0.9%; }
/* PPSASCRUM-56: end */

label[for=fabric-half-width-status]{ color:rgb(255, 165, 0); }
label[for=total-fabric-widths]{ color:rgb(255, 165, 0); }
label[for=lining-half-width-status]{ color:rgb(255, 165, 0); }

label[for=valance-fabric-widths]{ color:blue; }
label[for=valance-lining-widths]{ color:blue; }
/* PPSASCRUM-56: start */
label[for=fabric-price-pu]{ color:blue; }
/* PPSASCRUM-56: end */
label[for=lining-cost]{ color:blue; }
label[for=total-surcharges]{ color:blue; }
label[for=labor-cost]{ color:blue; }
label[for=price]{ color:blue; }

label[for=fabric-cl]{ color:rgb(0, 128, 0); }
/* PPSASCRUM-56: start */
/* label[for=lining-cl]{ color:rgb(0, 128, 0); } */
/* PPSASCRUM-56: end */
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
/* PPSASCRUM-56: start */
#yds-per-unit{ width:54% !important; font-size:14px; }
/* PPSASCRUM-56: end */

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

/*PPSA-33 start*/
#fabric-cost-per-yard-custom{ float:none !important; display:block; width:100% !important; margin:0 0 0 0 !important; }
#fabric-cost-per-yard-custom-value{ float:none !important; display:inline-block; width:100% !important; margin:4px 0 0 0 !important; }

#fabriccostwrap label{ width:55% !important; }
#fabriccostwrap small{ font-style:italic; font-size:11px; color:#ff0000;     display: block; width: 30%; float: right; }
	
#fabriccostwrap-custom-value{ float:none !important; display:inline-block; width:100% !important; margin:4px 0 0 0 !important; }
#fabriccostwrap{ float:none !important; display:block; width:100% !important; margin:0 0 0 0 !important; }
#fabriccostwrapinnerwrap{ float:right; width:28%; text-align: right }
/*PPSA-33 end*/

/* PPSASCRUM-56: start */
input#pinset::-webkit-outer-spin-button,
input#pinset::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input#pinset {
  -moz-appearance: textfield;
}
/* div.addaslineitembutton button[type=submit].notallowed { cursor:not-allowed; background:#888 !important; color:#CCC !important; } */
div.addaslineitembutton button[type=submit].notallowed { cursor:not-allowed; }
/* PPSASCRUM-56: end */
/* PPSASCRUM-339: start */
.no-spinner {
	-moz-appearance: textfield;
}
.no-spinner::-webkit-inner-spin-button,
.no-spinner::-webkit-outer-spin-button {
	-webkit-appearance: none;
	margin: 0;
}
#laborperwidthwrap label{ width:55% !important; }
#laborperwidthinnerwrap{ float:right; width:28%; text-align: right }
#labor-per-width{ float:none !important; display:block; width:100% !important; margin:0 0 0 0 !important; }
#labor-per-width-custom-value{ float:none !important; display:inline-block; width:100% !important; margin:4px 0 0 0 !important; }
/* PPSASCRUM-339: end */
</style>
<script>
/*PPSASCRUM-269 start */
$(document).on('keydown', 'input:visible, number:visible,textarea:visible, select:visible, button:visible,feildset:visible, [type="radio"]:visible, [type="checkbox"]:visible, [tabindex]', function(e) {
              console.log(this);

        if (e.which === 13) { // 13 is the Enter key code
            e.preventDefault(); // Prevent default action, which is form submission
            moveToNextFocusableElement(this);
        }
    });
/*PPSASCRUM-269 start */
function changefabricmodal(){
    $.fancybox({
        'type':'iframe',
        /* PPSASCRUM-100: start */
        // 'href':'/quotes/changecalcitemfabric/'+$('#fabricid').val(),
        'href':'/quotes/changecalcitemfabric/'+$('#fabricid').val()+'/pinch-pleated-drapery',
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
	echo "<h1>Standalone PPD Calculator</h1><hr>";
}

echo $this->Form->create();
echo "<div id=\"calcformleft\">";

echo "<h2>Pinch Pleated Drapery</h2>";

echo "<div class=\"input\">
    <label style=\"width:44% !important;\">Fabric/Pattern</label>
    <div id=\"fabricname\">".$fabricData['fabric_name']."</div>
<!-- PPSASCRUM-371: start -->
</div>";
echo "<div class=\"input\">
    <label style=\"width:44% !important;\">Color</label>
    <div id=\"fabriccolor\">".$fabricData['color']."</div>";
if ($ordermode != 'workorder') {
    echo "<button id=\"changefabricbutton\" type=\"button\" onclick=\"changefabricmodal()\">Change Fabric/Color</button>";
}
echo "</div>";
/* PPSASCRUM-371: end */

echo $this->Form->input('process',['type'=>'hidden','value'=>'insertlineitem']);

echo $this->Form->input('calculator-used',['type'=>'hidden','value'=>'new-pinch-pleated']);

echo $this->Form->input('fabricid',['type'=>'hidden','value'=>$fabricid]);

echo $this->Form->input('start-price-val',['type'=>'hidden','value'=>$thisItemMeta['start-price-val']]);
echo $this->Form->input('add-on-total-val',['type'=>'hidden','value'=>$thisItemMeta['add-on-total-val']]);
echo $this->Form->input('add-on-text-val',['type'=>'hidden','value'=>$thisItemMeta['add-on-text-val']]);

if(isset($thisItemMeta['advancedfieldsvisible']) && $thisItemMeta['advancedfieldsvisible']=='1'){
	echo $this->Form->input('advancedfieldsvisible',['type'=>"hidden","value"=>'1']);
}else{
	echo $this->Form->input('advancedfieldsvisible',['type'=>"hidden","value"=>'0']);
}



/* PPSASCRUM-56: start */
// changes for PPSASCRUM-50
if(isset($thisItemMeta['railroaded']) ){
    if($thisItemMeta['railroaded']=='1'){
        $railroadedChecked=true;
    } else {
		$railroadedChecked=false;
	}
}else{
    if($fabricData['railroaded']=='1'){
        $railroadedChecked=true;
    }else{
        $railroadedChecked=false;
    }
}
// changes for PPSASCRUM-50
echo $this->Form->input('railroaded',['type'=>'checkbox','label'=>'Railroaded','checked'=>$railroadedChecked]);



if(isset($thisItemMeta['com-fabric']) && $thisItemMeta['com-fabric']=='1'){
	$comChecked=true;
}else{
	$comChecked=false;
}
echo $this->Form->input('com-fabric',['type'=>'checkbox','label'=>'COM Fabric','checked'=>$comChecked]);



if (isset($thisItemMeta['fabric_notes']) && strlen(trim($thisItemMeta['fabric_notes'])) > 0) {
	$fabricNotesValue = $thisItemMeta['fabric_notes'];
} else {
	$fabricNotesValue = '';
}
echo $this->Form->input('fabric_notes', ['type' => 'textarea', 'label' => 'Fabric Notes', 'rows' => 1, 'value' => $fabricNotesValue]);



if (isset($thisItemMeta['usage']) && strlen(trim($thisItemMeta['usage'])) > 0) {
	$usageValue = $thisItemMeta['usage'];
} else {
	$usageValue = 'Main';
}
echo $this->Form->input('usage', ['label' => 'Usage', 'value' => $usageValue]);
/* PPSASCRUM-56: end */


if(isset($thisLineItem['room_number']) && strlen(trim($thisLineItem['room_number'])) >0){
	$locationValue=$thisLineItem['room_number'];
}else{
	$locationValue='';
}
/**PPSASCRUM-201 start **/
echo $this->Form->input('quoteType',['type'=>'hidden','value'=>$quoteData['type_id']]); //PPSASCRUM-201

 $typeID=false;
if($quoteData['type_id'] == 1){
    $typeID=true;
    
}
echo $this->Form->input('location',['label'=>'Location','value'=>$locationValue,'required'=>$typeID]);




/* PPSASCRUM-56: start */
/* if($unitofmeasureval == 'panel'){
	$paneltypedisplay='block';
	if(isset($thisItemMeta['panel-type']) && strlen(trim($thisItemMeta['panel-type'])) >0){
		$paneltypeval=array('value'=>$thisItemMeta['panel-type'],'id'=>'panel-type');
	}else{
		$paneltypeval=array('value'=>'Stationary','id'=>'panel-type');
	}
}else{
	$paneltypedisplay='none';
	$paneltypeval=array('value'=>'Stationary','id'=>'panel-type');
}
echo "<div id=\"paneltypewrap\" style=\"display:".$paneltypedisplay.";\">";
echo "<div class=\"input selectbox\">";
echo $this->Form->label('Panel Type');
echo $this->Form->select('panel-type',['Stationary'=>'Stationary','Operable'=>'Operable'],$paneltypeval);
echo "</div>";
echo "</div>"; */


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
/* PPSASCRUM-56: start */
if(isset($thisItemMeta['fabric-markup-custom-value']) && strlen(trim($thisItemMeta['fabric-markup-custom-value'])) > 0){
	$markupcustomval=$thisItemMeta['fabric-markup-custom-value'];
}else{
	$markupcustomval=$settings['fabric-markup-custom-value'];
}
/* PPSASCRUM-56: end */
echo "<div><input type=\"number\" name=\"fabric-markup-custom-value\" step=\"any\" min=\"0\" id=\"fabric-markup-custom-value\" value=\"".$markupcustomval."\" placeholder=\"Override\" /></div>";
echo "</div></div>";


echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>LINING SPECS &amp; PRICING</legend>";

echo "<div class=\"input selectbox\">";
echo "<label>Lining</label>";

/* PPSASCRUM-56: start */
// echo "<select name=\"linings_id\" id=\"linings_id\"><option value=\"\" data-price=\"0.00\">--Select Lining--</option><option value=\"1\" data-price=\"0.00\"";
echo "<select name=\"linings_id\" id=\"linings_id\"><option value=\"\" data-price=\"0.00\">--Select Lining--</option><option value=\"default\" data-price=\"0.00\"";
$isLiningSelected = false;
if (is_null($thisItemMeta['linings_id']) || $thisItemMeta['linings_id'] == 'default') {
	$isLiningSelected = true;
/* PPSASCRUM-56: end */
	echo " selected";
}
echo ">No Lining</option>";
        foreach($linings as $lining){
	echo "<option value=\"".$lining['id']."\" data-price=\"".number_format($lining['price'],2,'.',',')."\"";
	/* PPSASCRUM-56: start */
	if(!$isLiningSelected && $thisItemMeta['linings_id'] == $lining['id']){
	/* PPSASCRUM-56: end */
		echo " selected=\"selected\"";
	}
	echo ">".$lining['short_title']."</option>";
}
echo "</select>";

echo "</div>";

/* PPSASCRUM-56: start */
if(isset($thisItemMeta['lining_rr']) && is_bool(boolval($thisItemMeta['lining_rr']))){
	$liningRRVal=$thisItemMeta['lining_rr'];
}else{
	$liningRRVal=false;
}
echo $this->Form->input('lining_rr', ['id'=>'lining_rr', 'type'=>'checkbox', 'label'=>'Lining RR', 'checked' => $liningRRVal]);
/* PPSASCRUM-56: end */


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

/* PPSASCRUM-56: start */
/*
if(isset($thisItemMeta['lining_notes']) && is_string($thisItemMeta['lining_notes'])){
	$liningNotesVal=$thisItemMeta['lining_notes'];
}else{
	$liningNotesVal='';
}
echo $this->Form->input('lining_notes', ['type' => 'textarea', 'label' => 'Lining Notes', 'rows' => 2, 'value' => $liningNotesVal]);

echo "<div class=\"orphan-section\"></div>";
*/
/* PPSASCRUM-56: end */

echo "</fieldset>";


/* PPSASCRUM-56: end */




/* PPSASCRUM-56: start */
echo "<fieldset class=\"fieldsection\"><legend>DRAPE SPECS</legend>";

if (isset($thisItemMeta['pleat_type']) && strlen(trim($thisItemMeta['pleat_type'])) > 0) {
	$pleatTypeVal = $thisItemMeta['pleat_type'];
} else {
	$pleatTypeVal = '3_prong_pleat';
}
echo $this->Form->input('pleat_type', ['id' => 'pleat_type', 'type' => 'select', 'label' => 'Pleat Type', 'options' => ["cartridge_pleat"=>"Cartridge Pleat", "single_pleat"=>"Single Pleat", "2_prong_pleat"=>"2 Prong Pleat", "3_prong_pleat"=>"3 Prong Pleat", "inverted_pleat"=>"Inverted Pleat", "goblet_pleat"=>"Goblet Pleat", "2_prong_euro_pleat"=>"2 Prong Euro Pleat", "3_prong_euro_pleat"=>"3 Prong Euro Pleat", "other"=>"OTHER"], 'value'=>$pleatTypeVal]);


if(isset($thisItemMeta['fullness']) && is_numeric($thisItemMeta['fullness'])){
	$fullnessval=$thisItemMeta['fullness'];
}else{
	$fullnessval='200';
}

// echo $this->Form->input('fullness',['label'=>'Fullness %','type'=>'number','list'=>'selectable_fullness_values','min'=>'150','max'=>'300','value'=>$fullnessval]);
echo $this->Form->input('fullness',['label'=>'Fullness %','type'=>'select','options'=>[ '150'=>150 , '180'=>180 , '200'=>200 , '225'=>225 , '250'=>250 , '300'=>300 ],'value'=>$fullnessval]);

if (isset($thisItemMeta['crinoline']) && strlen(trim($thisItemMeta['crinoline'])) > 0) {
	$crinolineVal = $thisItemMeta['crinoline'];
} else {
	$crinolineVal = '4_double';
}
echo $this->Form->input('crinoline', ['id' => 'crinoline', 'type' => 'select', 'label' => 'Crinoline', 'options' => ["4_single"=>"4\" Single", "4_double"=>"4\" Double", "6_single"=>"6\" Single", "6_double"=>"6\" Double" , "none"=>"NONE"], 'value' => $crinolineVal]);

if (isset($thisItemMeta['bottom_hem_size']) && strlen(trim($thisItemMeta['bottom_hem_size'])) > 0) {
	$bottomHemSizeVal = $thisItemMeta['bottom_hem_size'];
} else {
	$bottomHemSizeVal = '4';
}
/* PPSASCRUM-56: start */
// echo $this->Form->input('bottom_hem_size', ['id' => 'bottom_hem_size', 'type' => 'select', 'label' => 'Bottom Hem Size', 'options' => ['0.5'=>0.5, '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6], 'value'=>$bottomHemSizeVal]);
echo $this->Form->input('bottom_hem_size', ['id' => 'bottom_hem_size', 'type' => 'select', 'label' => 'Bottom Hem Size', 'options' => ['0.5'=>0.5, '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6, '7' => 7, '8' => 8, '9' => 9, '10' => 10], 'value'=>$bottomHemSizeVal]);
/* PPSASCRUM-56: end */

if (isset($thisItemMeta['bottom_hem_fold']) && strlen(trim($thisItemMeta['bottom_hem_fold'])) > 0) {
	$bottomHemSizeVal = $thisItemMeta['bottom_hem_fold'];
} else {
	$bottomHemSizeVal = 'double';
}
echo $this->Form->input('bottom_hem_fold', ['id' => 'bottom_hem_fold', 'type' => 'select', 'label' => 'Bottom Hem Fold', 'options' => ["single"=>"Single", "double"=>"Double"], 'value'=>$bottomHemSizeVal]);

if (isset($thisItemMeta['stiffener']) && strlen(trim($thisItemMeta['stiffener'])) > 0) {
	$stiffenerVal = $thisItemMeta['stiffener'];
} else {
	$stiffenerVal = '';
}
/* PPSASCRUM-337: start */
echo $this->Form->input('stiffener', ['id' => 'stiffener', 'type' => 'select', 'label' => 'Stiffener', 'options' => ["yes"=>"Yes", "no"=>"No", "unknown" => "Unknown"], 'value'=>$stiffenerVal, 'empty' => "--Select--"]);
/* PPSASCRUM-337: end */

/* PPSASCRUM-56: end */


echo "</fieldset>";


/* PPSASCRUM-56: start */
// TODO: Unnamed section below 'DIMENSION & SPECS'
echo "<fieldset class=\"fieldsection\"><legend>DRAPE DIMENSIONS</legend>";

if(isset($thisItemMeta['qty']) && is_numeric($thisItemMeta['qty'])){
	$qtyval=$thisItemMeta['qty'];
}else{
	$qtyval=1;
}
echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','min'=>0,'value'=>$qtyval,'autocomplete'=>'off']);

if(isset($thisItemMeta['unit-of-measure']) && strlen(trim($thisItemMeta['unit-of-measure'])) >0){
	$unitofmeasureval=array('value'=>$thisItemMeta['unit-of-measure'],'id'=>'unit-of-measure');
}else{
	$unitofmeasureval=array('empty'=>'--Select--','id'=>'unit-of-measure');
}
echo "<div class=\"input select\">";
echo $this->Form->label('Pair / Panel');
echo $this->Form->select('unit-of-measure',['pair'=>'Pair','panel'=>'Panel'],$unitofmeasureval);
echo "</div>";

if (isset($thisItemMeta['draw']) && strlen(trim($thisItemMeta['draw'])) > 0) {
	$drawvals = array('value' => $thisItemMeta['draw'], 'id' => 'draw');
} else {
	$drawvals = array('value' => 'center', 'id' => 'draw');
}
echo "<div id=\"drawrow\" class=\"input selectbox\"";

echo ">";
echo "<label>Draw</label>";
echo $this->Form->select('draw',['left'=>'Left','center'=>'Center','right'=>'Right'],$drawvals);
echo "</div>";

/* if(isset($thisItemMeta['width-of-window']) && is_numeric($thisItemMeta['width-of-window'])){
	$widthofwindowval=$thisItemMeta['width-of-window'];
}else{
	$widthofwindowval='0';
} */
echo $this->Form->input('width-of-window',['label'=>'Width of Window','type'=>'hidden','step'=>'any','value'=>$widthofwindowval]);

/* if(isset($thisItemMeta['wall-left']) && is_numeric($thisItemMeta['wall-left'])){
	$wallleftval=$thisItemMeta['wall-left'];
}else{
	$wallleftval='4';
} */
echo $this->Form->input('wall-left',['label'=>'Wall Left','type'=>'hidden','step'=>'any','value'=>$wallleftval]);

/* if(isset($thisItemMeta['wall-right']) && is_numeric($thisItemMeta['wall-right'])){
	$wallrightval=$thisItemMeta['wall-right'];
}else{
	$wallrightval='4';
} */
echo $this->Form->input('wall-right',['label'=>'Wall Right','type'=>'hidden','step'=>'any','value'=>$wallrightval]);

/* if(isset($thisItemMeta['fabric-widths-per-panel']) && strlen(trim($thisItemMeta['fabric-widths-per-panel']))> 0){
	$fabricwidthsperpanelval=$thisItemMeta['fabric-widths-per-panel'];
	$fabricwidthsperpaneldisplay='block';
}else{
	$fabricwidthsperpanelval='1';
	$fabricwidthsperpaneldisplay='none';
} */
echo "<div id=\"fabricwidthsperpanelwrap\" class=\"input selectbox\" style=\"display: none\">
<!-- PPSASCRUM-56: end -->
<label for=\"fabric-widths-per-panel\">Fabric Widths per Panel</label>";
echo $this->Form->select('fabric-widths-per-panel',['0.5'=>'Half Width','1'=>'1 Width','1.5'=>'1.5 Widths','2'=>'2 Widths'],['id'=>'fabric-widths-per-panel','value'=>$fabricwidthsperpanelval]);
echo "</div>";




if(isset($thisItemMeta['rod-width']) && is_numeric($thisItemMeta['rod-width'])){
	$rodwidthval=$thisItemMeta['rod-width'];
}else{
	$rodwidthval='';
}
/* PPSASCRUM-56: start */
echo $this->Form->input('rod-width',['label'=>'Rod Width','type'=>'number','min'=>0,'step'=>'any','value'=>$rodwidthval]);
/* PPSASCRUM-56: end */




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


/* PPSASCRUM-56: start */
if(isset($thisItemMeta['length']) && is_numeric($thisItemMeta['length'])){
	$lengthval=$thisItemMeta['length'];
}else{
	$lengthval='0';
}
echo $this->Form->input('length',['type'=>'number','min'=>'0.00','step'=>'0.01','value'=>$lengthval,'label'=>'FINISH LENGTH']);


if (isset($thisItemMeta['pinset']) && is_numeric($thisItemMeta['pinset'])) {
	$pinsetval = $thisItemMeta['pinset'];
} else {
	$pinsetval = $settings['pinch_pleated_hardware_pinset_default'];
}
echo $this->Form->input('pinset', ['type' => 'number', 'step' => 'any', 'min' => '0.5', 'max' => '1.75', 'label' => 'Pin Set', 'value' => $pinsetval]);


/*
	TODO: - store this value in metadata with meta_key 'full_width' in the Controller function
		  - read the value dynamically from meta table for this field, if not set, use the default as '--Select--'
		  - make valid option selection based on the value stored in the meta table 
*/
if (isset($thisItemMeta['full_width']) && is_numeric($thisItemMeta['full_width'])) {
	$fullWidthVal = $thisItemMeta['full_width'];
} else {
	$fullWidthVal = '';
}
echo $this->Form->input('full_width', ['id' => 'full_width', 'type' => 'select', 'label' => 'Full Width', 'empty' => '--Select--', 'options'=>['Yes', 'No'], 'value'=>$fullWidthVal]);
// echo $this->Form->select('full_width', ['id' => 'full_width', 'label' => 'Full Width', 'empty' => '--Select--', 'options'=>['Yes', 'No']], $fullWidthVal);

// echo "<div class=\"orphan-section\"></div>";



echo "</fieldset>";
/* PPSASCRUM-56: end */




echo "<fieldset class=\"fieldsection\"><legend>LABOR</legend>";
if(isset($thisItemMeta['labor-per-width']) && strlen(trim($thisItemMeta['labor-per-width'])) >0){
	$laborperwidthval=$thisItemMeta['labor-per-width'];
}else{
	$laborperwidthval='';
}
/* PPSASCRUM-339: start */
echo "<div id=\"laborperwidthwrap\" class=\"input number\">
<label>Labor Per Width</label>
<div id=\"laborperwidthinnerwrap\">
<div><input type=\"number\" id=\"labor-per-width\" name=\"labor-per-width\" step=\"any\" value=\"".$laborperwidthval."\" class=\"no-spinner\" readonly></div>";
if(isset($thisItemMeta['labor-per-width-custom-value']) && strlen(trim($thisItemMeta['labor-per-width-custom-value'])) >= 0){
	$laborCostPWCustomVal=$thisItemMeta['labor-per-width-custom-value'];
}else{
	$laborCostPWCustomVal='';
}
echo "<div><input type=\"number\" name=\"labor-per-width-custom-value\" step=\"any\" min=\"0\" id=\"labor-per-width-custom-value\" value=\"".$laborCostPWCustomVal."\" placeholder=\"Override\" /></div>
</div></div>";
/* PPSASCRUM-339: end */

echo "</fieldset>";



/* PPSASCRUM-298: start */

/*

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


/* PPSASCRUM-56: start */
/* echo "</fieldset>";

*/

/* PPSASCRUM-298: end */


echo "<fieldset class=\"fieldsection\"><legend>NOTES FOR WORKROOM</legend>";

if(isset($thisItemMeta['notes_for_workroom']) && strlen(trim($thisItemMeta['notes_for_workroom'])) > 0){
	$notesForWorkroomVal=$thisItemMeta['notes_for_workroom'];
}else{
	$notesForWorkroomVal='';
}
echo $this->Form->input('notes_for_workroom', ['label'=>'', 'type' => 'textarea', 'rows' => 2, 'value' => $notesForWorkroomVal]);

echo "<div class=\"orphan-section\"></div>";
/* PPSASCRUM-56: end */
echo "</fieldset>";


/* PPSASCRUM-56: start */
echo "<fieldset class=\"fieldsection\" style=\"display: none\"><legend>FABRIC ROUNDING</legend>";
/* PPSASCRUM-56: end */


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



/* PPSASCRUM-56: start */
echo "<fieldset class=\"fieldsection\" style=\"display: none\"><legend>LINING ROUNDING</legend>";
/* PPSASCRUM-56: end */

if(isset($thisItemMeta['force-round-lining-widths-ea']) && $thisItemMeta['force-round-lining-widths-ea']=='1'){
	$forceroundliningwidthseaChecked=true;
}else{
    // changes for PPSASCRUM-44
	$forceroundliningwidthseaChecked=false;
    // changes for PPSASCRUM-44
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







/* PPSASCRUM-56: start *//* if(isset($thisItemMeta['ease']) && is_numeric($thisItemMeta['ease'])){
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
*/

if(isset($thisItemMeta['table-allowance']) && is_numeric($thisItemMeta['table-allowance'])){
	$tableAllowance=$thisItemMeta['table-allowance'];
}else{
	$tableAllowance=$settings['ppd_table_allowance'];
}
echo $this->Form->input('table-allowance',['label'=>'Table Allowance','value'=>$tableAllowance]);
/* PPSASCRUM-56: end */








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

/* PPSASCRUM-56: start */

/* if(isset($thisItemMeta['return']) && strlen(trim($thisItemMeta['return'])) > 0){
	$returnval=$thisItemMeta['return'];
}else{
	$returnval='';
} */

echo $this->Form->input('return',['label'=>'Return','readonly'=>true,'value'=>$returnval,'type'=>'hidden']);


/* if(isset($thisItemMeta['overlap']) && strlen(trim($thisItemMeta['overlap'])) > 0){
	$overlapval=$thisItemMeta['overlap'];
}else{
	$overlapval='';
} */	

echo $this->Form->input('overlap',['label'=>'Overlap','readonly'=>true,'value'=>$overlapval,'type'=>'hidden']);


/* if(isset($thisItemMeta['widths-each']) && strlen(trim($thisItemMeta['widths-each'])) > 0){
	$widthseachval=$thisItemMeta['widths-each'];
}else{
	$widthseachval='';
} */

echo $this->Form->input('widths-each',['label'=>'Fab Widths Each','readonly'=>true,'value'=>$widthseachval,'type'=>'hidden']);


/* if(isset($thisItemMeta['rounded-widths-each']) && strlen(trim($thisItemMeta['rounded-widths-each'])) > 0){
	$roundedwidthseachval=$thisItemMeta['rounded-widths-each'];
}else{
	$roundedwidthseachval='';
} */

echo $this->Form->input('rounded-widths-each',['label'=>'Fab Rnd Widths per unit','readonly'=>true,'value'=>$roundedwidthseachval,'type'=>'hidden']);


/* if(isset($thisItemMeta['raw-labor-widths']) && strlen(trim($thisItemMeta['raw-labor-widths'])) > 0){
	$rawlaborwidthsval=$thisItemMeta['raw-labor-widths'];
}else{
	$rawlaborwidthsval='';
} */

echo $this->Form->input('raw-labor-widths',['label'=>'Raw Labor Widths per unit','readonly'=>true,'value'=>$rawlaborwidthsval,'type'=>'hidden']);


/* if(isset($thisItemMeta['cl']) && strlen(trim($thisItemMeta['cl'])) > 0){
	$clval=$thisItemMeta['cl'];
}else{
	$clval='';
} */

echo $this->Form->input('cl',['label'=>'CL','readonly'=>true,'value'=>$clval,'type'=>'hidden']);


/* if(isset($thisItemMeta['vertical-waste']) && strlen(trim($thisItemMeta['vertical-waste'])) > 0){
	$vertwasteval=$thisItemMeta['vertical-waste'];
}else{
	$vertwasteval='';
} */

echo $this->Form->input('vertical-waste',['label'=>'Vertical Waste','readonly'=>true,'value'=>$vertwasteval,'type'=>'hidden']);


/* PPSASCRUM-56: start */
echo "<div id=\"cutwidthwrap\" style=\"display:none;";
/* if($thisItemMeta['railroaded'] == '0' || !isset($thisItemMeta['railroaded'])){
	echo "display:none;";
}else{
	echo "display:block;";
} */
/* PPSASCRUM-56: end */
echo "\">";
if(isset($thisItemMeta['cw']) && strlen(trim($thisItemMeta['cw'])) > 0){
	$cwval=$thisItemMeta['cw'];
}else{
	$cwval='';
}	
echo $this->Form->input('cw',['label'=>'CW','readonly'=>true,'value'=>$cwval]);
echo "</div>";


if (isset($thisItemMeta['fw']) && strlen(trim($thisItemMeta['fw'])) > 0) {
	$fwVal = $thisItemMeta['fw'];
} else {
	$fwVal = '';
}

echo $this->Form->Input('fw', ['label' => 'FW', 'readonly' => true, 'value' => $fwVal]);


if(isset($thisItemMeta['total-widths']) && strlen(trim($thisItemMeta['total-widths'])) > 0){
	$totalwidthsval=$thisItemMeta['total-widths'];
}else{
	$totalwidthsval='';
}

echo $this->Form->input('total-widths',['label'=>'TOTAL FAB WIDTHS','readonly'=>true,'value'=>$totalwidthsval]);


if(isset($thisItemMeta['labor-widths']) && strlen(trim($thisItemMeta['labor-widths'])) > 0){
	$laborwidthsval=$thisItemMeta['labor-widths'];
}else{
	$laborwidthsval='';
}	

echo $this->Form->input('labor-widths',['label'=>'Billable Widths','readonly'=>true,'value'=>$laborwidthsval]);


if(isset($thisItemMeta['adjusted-cl']) && strlen(trim($thisItemMeta['adjusted-cl'])) > 0){
	$adjclval=$thisItemMeta['adjusted-cl'];
}else{
	$adjclval='';
}

echo $this->Form->input('adjusted-cl',['label'=>'Fab Adj CL','readonly'=>true,'value'=>$adjclval]);


if(isset($thisItemMeta['fab-rr-cl']) && strlen(trim($thisItemMeta['fab-rr-cl'])) > 0){
	$adjclval=$thisItemMeta['fab-rr-cl'];
}else{
	$adjclval='';
}

echo $this->Form->input('fab-rr-cl',['label'=>'Fab RR CL','readonly'=>true,'value'=>$adjclval]);

/* PPSASCRUM-56: end */




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



/* PPSASCRUM-56: start */
if(isset($thisItemMeta['lining-cl']) && strlen(trim($thisItemMeta['lining-cl'])) > 0){
	$liningClVal=$thisItemMeta['lining-cl'];
}else{
	$liningClVal='';
}	
echo $this->Form->input('lining-cl',['label'=>'Lining CL','readonly'=>true,'value'=>$liningClVal]);




if(isset($thisItemMeta['lining-rr-cl']) && strlen(trim($thisItemMeta['lining-rr-cl'])) > 0){
	$liningRRClVal=$thisItemMeta['lining-rr-cl'];
}else{
	$liningRRClVal='';
}	
echo $this->Form->input('lining-rr-cl',['label'=>'Lining RR CL','readonly'=>true,'value'=>$liningRRClVal]);
/* PPSASCRUM-56: end */




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
/* PPSASCRUM-56: start */
echo $this->Form->input('fabric-price',['label'=>'Fabric Cost per yard','readonly'=>true,'value'=>$fabricpriceval]);
/* PPSASCRUM-56: end */






if(isset($thisItemMeta['fabric-price-pu']) && strlen(trim($thisItemMeta['fabric-price-pu'])) > 0){
	$fabricpricepuval=$thisItemMeta['fabric-price-pu'];
}else{
	$fabricpricepuval='';
}	
echo $this->Form->input('fabric-price-pu',['label'=>'Fabric Cost per unit','readonly'=>true,'value'=>$fabricpricepuval]);






if(isset($thisItemMeta['total-fabric-price']) && strlen(trim($thisItemMeta['total-fabric-price'])) > 0){
	$totalfabricpriceval=$thisItemMeta['total-fabric-price'];
}else{
	$totalfabricpriceval='';
}	
echo $this->Form->input('total-fabric-price',['label'=>'TOTAL FAB COST','readonly'=>true,'value'=>$totalfabricpriceval]);




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
		/* PPSASCRUM-56: start */
		/* PPSASCRUM-317: start */
		// echo $this->Form->button('Save Changes',['type'=>'submit','onClick'=>'return canCalculate();']);
		echo $this->Form->button('Save Changes',['type'=>'submit','onClick'=>'return checkSubmission();']);
		/* PPSASCRUM-317: end */
		/* PPSASCRUM-56: end */
	}else{
		/* PPSASCRUM-56: start */
		/* PPSASCRUM-317: start */
		// echo $this->Form->button('Add To Quote',['type'=>'submit','onClick'=>'return canCalculate();']);
		echo $this->Form->button('Add To Quote',['type'=>'submit','onClick'=>'return checkSubmission();']);
		/* PPSASCRUM-317: end */
		/* PPSASCRUM-56: end */
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

// setInterval(function() {
//     console.log(`This is rulesets: ${JSON.stringify(rulesets)}`);
// }, 3000);


function calculateLabor(){
	var laborValue;
	var liningValue=$('select[name=linings_id] option:selected').text();
	console.log('Lining Label = '+liningValue);

	pattern = new RegExp(/interlin/gi);
	var interlinedTest = pattern.test(liningValue);

	if($('#com-fabric').is(':checked')){
		//COM
		//determine lining
		/* PPSASCRUM-56: start */
// 		if($('select[name=linings_id]').val()=='' || $('select[name=linings_id]').val()=='none'){
		if($('select[name=linings_id]').val()=='' || $('select[name=linings_id]').val()=='none' || $('select[name=linings_id]').val()=='default'){
		/* PPSASCRUM-56: end */
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
		/* PPSASCRUM-56: start */
// 		if($('select[name=linings_id]').val()=='' || $('select[name=linings_id]').val()=='none'){
		if($('select[name=linings_id]').val()=='' || $('select[name=linings_id]').val()=='none' || $('select[name=linings_id]').val()=='default'){
		/* PPSASCRUM-56: end */
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








	if($('#railroaded').prop('checked')){
		//railroaded calculation start

	// TODO: This is hidden - $('#return')
	// 	if($('#unit-of-measure').val() == 'panel'){
	// 		$('#return').val($('#default-return').val());
	// 	}else if($('#unit-of-measure').val() == 'pair'){
	// 		$('#return').val((parseFloat($('#default-return').val())*2));
	// 	}else{
	// 		$('#return').val('WARNING: BAD UNIT OF MEASURE');
	// 	}


	// TODO: This is hidden - $('#overlap')
	// 	if($('#unit-of-measure').val() == 'panel'){
	// 		$('#overlap').val($('#default-overlap').val());
	// 	}else if($('#unit-of-measure').val() == 'pair'){
	// 		$('#overlap').val((parseFloat($('#default-overlap').val())*2));
	// 	}else{
	// 		$('#overlap').val('WARNING: BAD UNIT OF MEASURE');
	// 	}

		

	// TODO: New formula - $('#fw')
	// 	//finished width
	// 	if($('#unit-of-measure').val() == 'panel' && $('#panel-type').val() == 'Stationary'){
	// 		var multiplier=parseFloat($('#fabric-widths-per-panel').val());
	// 		var fabricWidth=parseFloat($('#fabric-width').val());
	// 		var fullness=(parseFloat($('#fullness').val()) / 100);
	// 		if( multiplier > 1){
	// 			var warn1='OK';
	// 			//add seams, more than 1 fabwidth
	// 			var fw = (((multiplier * fabricWidth) - ((2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) + <?php echo $settings['wt_drapery_seam_width']; ?>)) / fullness);
	// 			console.log('Finished Width = (('+multiplier+' * '+fabricWidth+') - (( 2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) - <?php echo $settings['wt_drapery_seam_width']; ?>) ) / '+fullness+')');
	// 		}else if(multiplier == 1){
	// 			//no seams, 1 full width
	// 			var warn1='OK';
	// 			var fw = (( (multiplier * 54) - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / fullness);
	// 			console.log('Finished Width = (('+multiplier+' * 54) - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / '+fullness+')');
	// 		}else if(multiplier < 1){
	// 			if(fabricWidth < 108){
	// 				var warn1 = 'FABRIC MUST BE AT LEAST 108 FOR HALF WIDTHS';
	// 			}else{
	// 				var warn1='OK';
	// 			}
	// 			//no seams, half width
	// 			var fw = (( (multiplier * fabricWidth) - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / fullness);
	// 			console.log('Finished Width = (('+multiplier+' * '+fabricWidth+') - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / '+fullness+')');
	// 		}
	// 	}else if($('#unit-of-measure').val() == 'pair' || $('#panel-type').val() == 'Operable'){
	// 		var warn1='OK';
	// 		var easepercent=roundToTwo((parseFloat($('#ease').val())/100)).toFixed(2);
	// 		var easeval=roundToTwo((parseFloat($('#rod-width').val())*easepercent)).toFixed(2);
	// 		var fw = ( Math.round((parseFloat($('#rod-width').val())+parseFloat(easeval))) + parseFloat($('#return').val()) + parseFloat($('#overlap').val())  );

	// 	}
	// 	$('#fw').val(fw);







	// TODO: This is hidden - $('#widths-each')
	// 	//lining widths
	// 	if($('#unit-of-measure').val() == 'panel' && $('#panel-type').val() == 'Stationary'){
	// 		var widthseach=parseFloat($('#fabric-widths-per-panel').val());
	// 		var liningWidths=parseFloat($('#fabric-widths-per-panel').val());
	// 		var laborwidthseach=parseFloat($('#fabric-widths-per-panel').val());

	// 	}else{
	// 		var widthseach=((((parseFloat($('#rod-width').val()) + parseFloat(easeval)) * fullness)+(parseFloat($('#return').val()) + parseFloat($('#overlap').val())))/parseFloat($('#fabric-width').val()));

	// 		var laborwidthseach=((((( parseFloat($('#rod-width').val()) * fullness) + parseFloat($('#overlap').val()) + parseFloat($('#return').val())) + parseFloat(easeval) ) + <?php echo $settings['drapery_railroaded_side_hems']; ?>)/54);

			
	// 		var liningWidths=((((parseFloat($('#rod-width').val()) + parseFloat(easeval)) * fullness)+(parseFloat($('#return').val()) + parseFloat($('#overlap').val())))/ parseFloat($('#lining-width').val()));

	// 	}
	// 	$('#widths-each').val(roundToTwo(widthseach).toFixed(2));




	// TODO: New formula - $('#lining-widths-each')
	// 	if($('#force-round-lining-widths-ea').is(':checked')){
	// 		liningWidths=Math.ceil(liningWidths);	
	// 	}
    //     /*PPSASCRUM-142: start*/
    //     var eachLiningWidths = roundToTwo(liningWidths).toFixed(2);
	// 	$('#lining-widths-each').val(!isNaN(eachLiningWidths) && isFinite(eachLiningWidths) ? eachLiningWidths : 0);
    //     /*PPSASCRUM-142: end*/



	// TODO: New formula - $('#total-lining-widths')
	// 	if($('#force-round-lining-widths-eol').is(':checked')){
	// 		var tot_lin_widths = Math.ceil((liningWidths * qty));
	// 	}else{
	// 		var tot_lin_widths = (liningWidths * qty);
	// 	}
    //     /*PPSASCRUM-142: start*/
    //     var totalLiningWidths = roundToTwo(tot_lin_widths).toFixed(2);
	// 	$('#total-lining-widths').val(!isNaN(totalLiningWidths) && isFinite(totalLiningWidths) ? totalLiningWidths : 0);
    //     /*PPSASCRUM-142: end*/


	// 	console.log('lining widths each = '+liningWidths);


	// TODO: This is hidden - $('#rounded-widths-each')
	// 	var roundedwidths=Math.ceil(widthseach);
	// 	$('#rounded-widths-each').val(roundedwidths);

		

	// TODO: This is hidden - $('#widths-each')
	// 	$('#widths-each').val(roundToTwo(widthseach).toFixed(2));
	// 	console.log('widths each = '+widthseach);



	// TODO: New formula - $('#total-widths')
	// 	if($('#unit-of-measure').val() == 'panel'){
	// 		if($('#force-rounded-widths-eol').is(':checked')){
	// 			$('#total-widths').val(roundToTwo(Math.ceil((widthseach*qty))).toFixed(2));
	// 		}else{
	// 			$('#total-widths').val(roundToTwo((widthseach*qty)).toFixed(2));
	// 		}
	// 	}else{
	// 		$('#total-widths').val((roundedwidths*qty));
	// 	}



	// TODO: This is hidden - $('#raw-labor-widths')
	// 	console.log('labor widths = '+laborwidthseach);
	// 	$('#raw-labor-widths').val(roundToTwo(laborwidthseach).toFixed(2));



	// TODO: New formula - $('#labor-widths')
	// 	var roundedlaborwidthseach=Math.ceil(laborwidthseach);
	// 	//labor widths
	// 	if($('#unit-of-measure').val() == 'panel'){
	// 		var laborwidths=roundedlaborwidthseach;
	// 	}else if($('#unit-of-measure').val() == 'pair'){
	// 		if(roundedlaborwidthseach % 2 == 0){
	// 			var laborwidths=roundedlaborwidthseach;
	// 		}else{
	// 			var laborwidths=(roundedlaborwidthseach+1);
	// 		}
	// 	}
	// 	$('#labor-widths').val(laborwidths);



		var finishLength=parseFloat($('#length').val());
		var headerHemAllowance=parseFloat($('#header-hem-allowance').val());
		var cl=(finishLength + headerHemAllowance);


	// TODO: New formula - $('#adjusted-cl')
	// 	var verticalRepeat=parseFloat($('#vert-repeat').val());
	// 	if($('#vert-repeat').val() == 0){
	// 		$('#adjusted-cl').val(cl);
	// 	}else{
	// 		var adjustedCL=(Math.ceil((cl/verticalRepeat))*verticalRepeat);
	// 		$('#adjusted-cl').val(adjustedCL);
	// 	}



	// TODO: This is hidden - $('#cl')
	// 	$('#cl').val(cl);


/*
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
*/

		/* PPSASCRUM-56: start */
		// $('#cw').val(!isNaN(cw) && isFinite(cw) ? roundToTwo(cw).toFixed(2) : 0);
		/* PPSASCRUM-56: end */




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



	// TODO: New formula - $('#yds-per-unit')
	// 	console.log('yds_pu = '+yds_pu);
	// 	$('#yds-per-unit').val(roundToTwo(yds_pu).toFixed(2));



	// TODO: New formula - $('#total-yds')
		console.log('tot_yds = '+tot_yds);
	// 	$('#total-yds').val(roundToTwo(tot_yds).toFixed(2));



	// 	//lining yardages

		
	// 	var liningYardsPU=( ( ( (liningWidths * cl) / 36) * 1.05));
	// 	console.log('liningYardsPU = (('+liningWidths+' * '+cl+') / 36) * 1.05)');


	// TODO: New formula - $('#lining-yds-per-unit')
    //     /*PPSASCRUM-142: start*/
    //     var liningYdsPerUnit = roundToTwo(liningYardsPU).toFixed(2);
	// 	$('#lining-yds-per-unit').val(!isNaN(liningYdsPerUnit) && isFinite(liningYdsPerUnit) ? liningYdsPerUnit : 0);
    //     /*PPSASCRUM-142: end*/


	// TODO: New formula - $('#total-lining-yards')
	// 	var totalLiningYards=(liningYardsPU * qty);
    //     /*PPSASCRUM-142: start*/
    //     var totalLiningYds = roundToTwo(totalLiningYards).toFixed(2);
	// 	$('#total-lining-yards').val(!isNaN(totalLiningYds) && isFinite(totalLiningYds) ? totalLiningYds : 0);
    //     /*PPSASCRUM-142: end*/



		//railroaded calculation end
	}else{
		//utr calculation start
	

	
	// TODO: This is hidden - $('#return')
	// 	if($('#unit-of-measure').val() == 'panel'){
	// 		$('#return').val($('#default-return').val());
	// 	}else if($('#unit-of-measure').val() == 'pair'){
	// 		$('#return').val((parseFloat($('#default-return').val())*2));
	// 	}else{
	// 		$('#return').val('WARNING: BAD UNIT OF MEASURE');
	// 	}


	// TODO: This is hidden - $('#overlap')
	// 	if($('#unit-of-measure').val() == 'panel'){
	// 		$('#overlap').val($('#default-overlap').val());
	// 	}else if($('#unit-of-measure').val() == 'pair'){
	// 		$('#overlap').val((parseFloat($('#default-overlap').val())*2));
	// 	}else{
	// 		$('#overlap').val('WARNING: BAD UNIT OF MEASURE');
	// 	}


    //     var fullness=(parseFloat($('#fullness').val()) / 100);



	// TODO: New formula - $('#fw')
	// 	//finished width
	// 	if($('#unit-of-measure').val() == 'panel' && $('#panel-type').val() == 'Stationary'){
	// 		var multiplier=parseFloat($('#fabric-widths-per-panel').val());
	// 		var fabricWidth=parseFloat($('#fabric-width').val());
	// 		if( multiplier > 1){
	// 			var warn1='OK';
	// 			//add seams, more than 1 fabwidth
	// 			var fw = (((multiplier * fabricWidth) - ((2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) + <?php echo $settings['wt_drapery_seam_width']; ?>)) / fullness);
	// 			console.log('Finished Width = (('+multiplier+' * '+fabricWidth+') - (( 2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) - <?php echo $settings['wt_drapery_seam_width']; ?>) ) / '+fullness+')');
	// 		}else if(multiplier == 1){
	// 			//no seams, 1 full width
	// 			var warn1='OK';
	// 			var fw = (( (multiplier * 54) - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / fullness);
	// 			console.log('Finished Width = (('+multiplier+' * 54) - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / '+fullness+')');
	// 		}else if(multiplier < 1){
	// 			if(fabricWidth < 108){
	// 				var warn1 = 'FABRIC MUST BE AT LEAST 108 FOR HALF WIDTHS';
	// 			}else{
	// 				var warn1='OK';
	// 			}
	// 			//no seams, half width
	// 			var fw = (( (multiplier * fabricWidth) - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / fullness);
	// 			console.log('Finished Width = (('+multiplier+' * '+fabricWidth+') - (2 * <?php echo $settings['wt_drapery_side_hems_width']; ?>) ) / '+fullness+')');
	// 		}
	// 	}else if($('#unit-of-measure').val() == 'pair' || $('#panel-type').val() == 'Operable'){
	// 		var warn1='OK';
	// 		var easepercent=roundToTwo((parseFloat($('#ease').val())/100)).toFixed(2);
	// 		var easeval=roundToTwo((parseFloat($('#rod-width').val())*easepercent)).toFixed(2);
	// 		var fw = ( Math.round((parseFloat($('#rod-width').val())+parseFloat(easeval))) + parseFloat($('#return').val()) + parseFloat($('#overlap').val())  );
	// 	}
	// 	$('#fw').val(fw);
		


	// TODO: This is hidden - $('#widths-each')
	// 	//widths each
	// 	//lining widths
	// 	if($('#unit-of-measure').val() == 'panel' && $('#panel-type').val() == 'Stationary'){
	// 		var widthseach=parseFloat($('#fabric-widths-per-panel').val());
	// 		var liningWidths=parseFloat($('#fabric-widths-per-panel').val());

	// 		var laborwidthseach=Math.ceil(parseFloat($('#fabric-widths-per-panel').val()));

	// 	}else{
	// 		var widthseach=((((parseFloat($('#rod-width').val()) + parseFloat(easeval)) * fullness)+(parseFloat($('#return').val()) + parseFloat($('#overlap').val())))/parseFloat($('#fabric-width').val()));

	// 		var laborwidthseach=((((parseFloat($('#rod-width').val()) + parseFloat(easeval)) * fullness)+(parseFloat($('#return').val()) + parseFloat($('#overlap').val())))/54);

			
	// 		var liningWidths=((((parseFloat($('#rod-width').val()) + parseFloat(easeval)) * fullness)+(parseFloat($('#return').val()) + parseFloat($('#overlap').val())))/ parseFloat($('#lining-width').val()));

	// 		//var liningWidths=((Math.ceil(widthseach) * parseFloat($('#fabric-width').val())) / parseFloat($('#lining-width').val()) );

	// 	}
	// 	$('#widths-each').val(roundToTwo(widthseach).toFixed(2));




	// TODO: New formula - $('#lining-widths-each')
	// 	if($('#force-round-lining-widths-ea').is(':checked')){
	// 		liningWidths=Math.ceil(liningWidths);	
	// 	}
    //     /*PPSASCRUM-142: start*/
    //     var eachLiningWidths = roundToTwo(liningWidths).toFixed(2);
	// 	$('#lining-widths-each').val(!isNaN(eachLiningWidths) && isFinite(eachLiningWidths) ? eachLiningWidths : 0);
    //     /*PPSASCRUM-142: end*/



	// TODO: New formula - $('#total-lining-widths')
	// 	if($('#force-round-lining-widths-eol').is(':checked')){
	// 		var tot_lin_widths = Math.ceil((liningWidths * qty));
	// 	}else{
	// 		var tot_lin_widths = (liningWidths * qty);
	// 	}
    //     /*PPSASCRUM-142: start*/
	// 	$('#total-lining-widths').val(!isNaN(tot_lin_widths) && isFinite(tot_lin_widths) ? tot_lin_widths : 0);
    //     /*PPSASCRUM-142: end*/



	// TODO: This is hidden - $('#raw-labor-widths')
	// 	$('#raw-labor-widths').val(roundToTwo(laborwidthseach).toFixed(2));



	// TODO: This is hidden - $('#rounded-widths-each')
	// 	var roundedwidths=Math.ceil(widthseach);
	// 	$('#rounded-widths-each').val(roundedwidths);

	// 	console.log('widths each = '+widthseach);
	// 	console.log('lining widths each = '+liningWidths);



	// TODO: New formula - $('#total-widths')
	// 	if($('#unit-of-measure').val() == 'panel'){
	// 		if($('#force-rounded-widths-eol').is(':checked')){
	// 			$('#total-widths').val(roundToTwo(Math.ceil((widthseach*qty))).toFixed(2));
	// 		}else{
	// 			$('#total-widths').val(roundToTwo((widthseach*qty)).toFixed(2));
	// 		}
	// 	}else{
	// 		$('#total-widths').val((roundedwidths*qty));
	// 	}





	// TODO: New formula - $('#labor-widths')
	// 	var roundedlaborwidthseach=Math.ceil(laborwidthseach);
	// 	//labor widths
	// 	if($('#unit-of-measure').val() == 'panel'){
	// 		var laborwidths=roundedlaborwidthseach;
	// 	}else if($('#unit-of-measure').val() == 'pair'){
	// 		if(roundedlaborwidthseach % 2 == 0){
	// 			var laborwidths=roundedlaborwidthseach;
	// 		}else{
	// 			var laborwidths=(roundedlaborwidthseach+1);
	// 		}
	// 	}
	// 	$('#labor-widths').val(laborwidths);



	// TODO: New formula - $('#adjusted-cl')
	// 	var finishLength=parseFloat($('#length').val());
	// 	var headerHemAllowance=parseFloat($('#header-hem-allowance').val());
	// 	var cl=(finishLength + headerHemAllowance);
	// 	var verticalRepeat=parseFloat($('#vert-repeat').val());
	// 	if($('#vert-repeat').val() == 0){
	// 		$('#adjusted-cl').val(cl);
	// 	}else{
	// 		var adjustedCL=(Math.ceil((cl/verticalRepeat))*verticalRepeat);
	// 		$('#adjusted-cl').val(adjustedCL);
	// 	}



	// TODO: This is hidden - $('#cl')
	// 	$('#cl').val(cl);



	// TODO: This is hidden - $('#vertical-waste')
	// 	var vertwaste=(parseFloat($('#adjusted-cl').val()) - parseFloat($('#cl').val()));
	// 	$('#vertical-waste').val(vertwaste);



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



	// TODO: New formula - $('#yds-per-unit')
	// 	console.log('yds_pu = '+yds_pu);
	// 	$('#yds-per-unit').val(roundToTwo(yds_pu).toFixed(2));



	// TODO: New formula - $('#total-yds')
		console.log('tot_yds = '+tot_yds);
	// 	$('#total-yds').val(roundToTwo(tot_yds).toFixed(2));



	// 	//lining yardages


	// TODO: New formula - $('#lining-yds-per-unit')
	// 	var liningYardsPU=( ( ( (tot_lin_widths * cl) / 36) * 1.05) / qty);
    //     /*PPSASCRUM-142: start*/
    //     var liningYdsPerUnit = roundToTwo(liningYardsPU).toFixed(2);
	// 	$('#lining-yds-per-unit').val(!isNaN(liningYdsPerUnit) && isFinite(liningYdsPerUnit) ? liningYdsPerUnit : 0);
    //     /*PPSASCRUM-142: end*/



	// TODO: New formula - $('#total-lining-yards')
	// 	var totalLiningYards=(liningYardsPU * qty);
    //     /*PPSASCRUM-142: start*/
    //     var totalLiningYds = roundToTwo(totalLiningYards).toFixed(2);
	// 	$('#total-lining-yards').val(!isNaN(totalLiningYds) && isFinite(totalLiningYds) ? totalLiningYds : 0);
    //     /*PPSASCRUM-142: end*/

	// 	//utr calculation end

	}




    qty=parseFloat($('#qty').val());
    
    console.log('qty for Inbound Freight:', qty);
    console.log('fab_ppy for Inbound Freight:', fab_ppy);
    console.log('tot_yds for Inbound Freight:', tot_yds);
    

	<?php
		if($fabricid=='custom'){
		?>
			if(qty > 0 && $('#custom-fabric-cost-per-yard').val() != '' && tot_yds > 0){
            // if(qty > 0 && $('#custom-fabric-cost-per-yard').val() != ''){
                console.log('Inside IF condition for Inbound Freight and Fabric Markup Inner Wrap');
                console.log('qty > 0: ' + qty);
                console.log('$("#custom-fabric-cost-per-yard").val() != "": ' + $('#custom-fabric-cost-per-yard').val());
                console.log('tot_yds > 0: ' + tot_yds);
				$.each(rulesets,function(index,value){
				    if (fab_ppy == 0) {
    		   	        $('#fabricmarkupwrap small').hide();
    		   	        $('#fabric-markup').val(0);
    		   	        $('#fabricmarkupinnerwrap').show();
    					$('#inboundfreightwrap small').hide();
    					$('#inboundfreightinnerwrap').show();
    		   	        return;
    		   	    }
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

		/* inbound freight calculation fix */
		tot_yds = parseFloat($('#total-yds').val());
		fab_ppy = parseFloat(fab_ppy);
		console.log('tot_yds for Fabric Markup calculation: ', tot_yds);
		console.log('fab_ppy for Fabric Markup calculation: ', fab_ppy);
		/* inbound freight calculation fix */
		
  		if(qty >0 && $('select[name=fabric-cost-per-yard]').val() != '' && tot_yds > 0){
            // if(qty >0 && $('select[name=fabric-cost-per-yard]').val() != ''){
                console.log('Inside ELSE condition for Inbound Freight and Fabric Markup Inner Wrap');
                console.log('qty > 0: ' + qty);
                console.log('$("select[name=fabric-cost-per-yard]").val() != "": ' + $('select[name=fabric-cost-per-yard]').val());
                console.log('tot_yds > 0: ' + tot_yds);
				console.log('rulesets for fabric markup calculation: ', rulesets);
    			//fab_ppy
    		   	$.each(rulesets,function(index,value){
    		   	    if (fab_ppy == 0) {
    		   	        $('#fabricmarkupwrap small').hide();
    		   	        $('#fabric-markup').val(0);
    		   	        $('#fabricmarkupinnerwrap').show();
    					$('#inboundfreightwrap small').hide();
    					$('#inboundfreightinnerwrap').show();
    		   	        return;
    		   	    }
    				if(fab_ppy >= parseFloat(value.price_low) && fab_ppy <= parseFloat(value.price_high) && tot_yds >= parseFloat(value.yds_low) && tot_yds < parseFloat(value.yds_high)){
						console.log('fab_ppy passing for fab markup', fab_ppy);
    				    console.log('tot_yds passing for fab markup', tot_yds);
    				    console.log('Approved fabric markup: ', value.markup);
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

        /* PPSASCRUM-317: start */
// 		if(fab_w >= 54 && fab_w <= 72){
// 			if(tot_yds < 25){
// 				/* PPSASCRUM-56: start */
// 				// ibfrtpy=roundToTwo(25.00/tot_yds).toFixed(2);
// 				ibfrtpy=roundToTwo(<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds25']; ?>/tot_yds).toFixed(2);
// 				if ($('#railroaded').is(':checked')) {
//     				/* ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds25']; ?>/tot_yds + 0.01;
//     				ibfrtpyArr = ibfrtpy.toString().split('.');
//     				ibfrtpy = parseFloat(ibfrtpyArr[0] + '.' + ibfrtpyArr[1].substring(0,2)); */
// 					ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds25']; ?>/tot_yds;
// 					if (ibfrtpy.toString().includes('.')) {
// 						let ibfrtpyArr = ibfrtpy.toString().split('.');
// 						let integerPart = ibfrtpyArr[0];
// 						let decimalPart = ibfrtpyArr[1];
// 						if (decimalPart.toString().length > 2) {
// 							// let laterDecimalPart = parseFloat(decimalPart.toString().substring(1,3));
// 							let laterDecimalPart = parseFloat(decimalPart.toString().substring(2,3));
// 							decimalPart = parseFloat(decimalPart.toString().substring(0,2));
// 							if (laterDecimalPart != 0) {
// 								/* if (decimalPart < 9) {
// 									decimalPart = '0' + (decimalPart + 1).toString();
// 								} else {
// 									decimalPart = (decimalPart + 1).toString();
// 									if (decimalPart == '100') { decimalPart = '00'; }
// 								} */
// 								if ((decimalPart < 9 && decimalPart > 0) || (decimalPart == 0 && laterDecimalPart >= 5)) {
// 									decimalPart = '0' + (decimalPart + 1).toString();
// 								} else if (decimalPart == 0 && laterDecimalPart < 5) {
// 									decimalPart = '00';
// 								} else {
// 									decimalPart = (decimalPart + 1).toString();
// 									if (decimalPart == '100') { decimalPart = '00'; }
// 								}
// 							}
// 							if (parseInt(roundToTwo(fFabCostPY).toFixed(2)) > parseInt(fFabCostPY)) {
// 								integerPart = parseInt(roundToTwo(fFabCostPY).toFixed(2));
// 							}
// 						}
// 						ibfrtpy = parseFloat(integerPart.toString() + '.' + decimalPart.toString());
// 					}
// 				}
// 				/* PPSASCRUM-56: end */
// 			}else if(tot_yds >= 25 && tot_yds < 60){
// 				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds25-59']; ?>;
// 			}else if(tot_yds >= 60 && tot_yds < 250){
// 				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds60-249']; ?>;
// 			}else if(tot_yds >= 250){
// 				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds250plus']; ?>;
// 			}
// 		}else if(fab_w >= 73 && fab_w <= 130){
// 			if(tot_yds < 25){
// 				/* PPSASCRUM-56: start */
// 				// ibfrtpy=roundToTwo((25.00/tot_yds)).toFixed(2);
// 				ibfrtpy=roundToTwo(<?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds25']; ?>/tot_yds).toFixed(2);
// 				if ($('#railroaded').is(':checked')) {
//     				/* ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds25']; ?>/tot_yds + 0.01;
//     				ibfrtpyArr = ibfrtpy.toString().split('.');
//     				ibfrtpy = parseFloat(ibfrtpyArr[0] + '.' + ibfrtpyArr[1].substring(0,2)); */
// 					ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds25']; ?>/tot_yds;
// 					if (ibfrtpy.toString().includes('.')) {
// 						let ibfrtpyArr = ibfrtpy.toString().split('.');
// 						let integerPart = ibfrtpyArr[0];
// 						let decimalPart = ibfrtpyArr[1];
// 						if (decimalPart.toString().length > 2) {
// 							// let laterDecimalPart = parseFloat(decimalPart.toString().substring(1,3));
// 							let laterDecimalPart = parseFloat(decimalPart.toString().substring(2,3));
// 							decimalPart = parseFloat(decimalPart.toString().substring(0,2));
// 							if (laterDecimalPart != 0) {
// 								/* if (decimalPart < 9) {
// 									decimalPart = '0' + (decimalPart + 1).toString();
// 								} else {
// 									decimalPart = (decimalPart + 1).toString();
// 									if (decimalPart == '100') { decimalPart = '00'; }
// 								} */
// 								if ((decimalPart < 9 && decimalPart > 0) || (decimalPart == 0 && laterDecimalPart >= 5)) {
// 									decimalPart = '0' + (decimalPart + 1).toString();
// 								} else if (decimalPart == 0 && laterDecimalPart < 5) {
// 									decimalPart = '00';
// 								} else {
// 									decimalPart = (decimalPart + 1).toString();
// 									if (decimalPart == '100') { decimalPart = '00'; }
// 								}
// 							}
// 							if (parseInt(roundToTwo(fFabCostPY).toFixed(2)) > parseInt(fFabCostPY)) {
// 								integerPart = parseInt(roundToTwo(fFabCostPY).toFixed(2));
// 							}
// 						}
// 						ibfrtpy = parseFloat(integerPart.toString() + '.' + decimalPart.toString());
// 					}
// 				}
// 				/* PPSASCRUM-56: end */
// 			}else if(tot_yds >= 25 && tot_yds < 60){
// 				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds25-59']; ?>;
// 			}else if(tot_yds >= 60 && tot_yds < 250){
// 				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds60-249']; ?>;
// 			}else if(tot_yds >= 250){
// 				ibfrtpy=<?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds250plus']; ?>;
// 			}
// 		}

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
		/* PPSASCRUM-317: end */
		
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

		/*
		var fabricCost=(parseFloat(fab_ppy)+parseFloat(ibfrtpy));
		console.log('Fabric COST per yard = '+fabricCost);
		$('#fabric-cost').val(fabricCost);
		*/

		var fab_yds=parseFloat($('#yds-per-unit').val());


		if($('#com-fabric').prop('checked')){
			$('#fabric-cost').val('0.00');
			/* PPSASCRUM-56: start */
			$('#fabric-price-pu').val('0.00');
			$('#total-fabric-price').val('0.00');
			/* PPSASCRUM-56: end */
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

		/* PPSASCRUM--56: start */
		markedupFabric = !isNaN(markedupFabric) && isFinite(markedupFabric) ? markedupFabric : 0;
		/* PPSASCRUM--56: end */

		$('#fabric-price').val(roundToTwo(markedupFabric).toFixed(2));

		var fabPricePU=(parseFloat(markedupFabric)*parseFloat($('#yds-per-unit').val()));

		/* PPSASCRUM--56: start */
		fabPricePU = !isNaN(fabPricePU) && isFinite(fabPricePU) ? fabPricePU : 0;
		/* PPSASCRUM--56: end */

		$('#fabric-price-pu').val(roundToTwo(fabPricePU).toFixed(2));

		var totalFabricPrice=(parseFloat(fabPricePU) * qty);
		/* PPSASCRUM--56: start */
		totalFabricPrice = !isNaN(totalFabricPrice) && isFinite(totalFabricPrice) ? totalFabricPrice : 0;
		/* PPSASCRUM--56: end */
		$('#total-fabric-price').val(roundToTwo(totalFabricPrice).toFixed(2));


		// var liningCost=(parseFloat($('#lining-price-per-yd').val()) * parseFloat($('#lining-yds-per-unit').val()) );
		var liningCost = parseFloat($('#total-lining-yards').val()) / parseFloat($('#qty').val()) * parseFloat($('#lining-price-per-yd').val());
        /*PPSASCRUM-142: start*/
		/* PPSASCRUM--56: start */
		var liningCst = parseFloat(roundToTwo(liningCost).toFixed(2));
		if (liningCost.toString().length > 5) {
		    liningCst = roundToTwo(parseFloat(liningCost.toString().substring(0,5)) == liningCst ? liningCst + 0.01 : liningCst).toFixed(2);
		}
		/* PPSASCRUM--56: end */
        $('#lining-cost').val(!isNaN(liningCst) && isFinite(liningCst) ? liningCst : 0);
        /*PPSASCRUM-142: end*/

		var totalLiningCost=(parseFloat(liningCost) * qty);
        /*PPSASCRUM-142: start*/
        var totalLiningCst = roundToTwo(totalLiningCost).toFixed(2);
		$('#total-lining-cost').val(!isNaN(totalLiningCst) && isFinite(totalLiningCst) ? totalLiningCst : 0);
        /*PPSASCRUM-142: end*/


		var laborPU=(parseFloat($('#labor-per-width').val()) * parseFloat($('#labor-widths').val()));
		/* PPSASCRUM--56: start */
		laborPU = !isNaN(laborPU) && isFinite(laborPU) ? laborPU : 0;
		/* PPSASCRUM--56: end */
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




        /*PPSASCRUM-142: start*/
		var totalSurgesVal = roundToTwo(addOnTotals).toFixed(2);
		totalSurgesVal = !isNaN(totalSurgesVal) && isFinite(totalSurgesVal) ? totalSurgesVal : 0;
		$('#total-surcharges').val(totalSurgesVal);

        // var fBasePrice = roundToTwo(basePrice).toFixed(2);
		// var fBasePrice = parseFloat($('#labor-cost').val()) + parseFloat($('#fabric-price-pu').val()) + parseFloat($('#lining-cost').val());
		var fBasePrice = parseFloat($('#labor-cost').val()) + parseFloat($('#fabric-price-pu').val()) + parseFloat($('#lining-cost').val()) + parseFloat($('#total-surcharges').val());
		fBasePrice = roundToTwo(fBasePrice).toFixed(2);
		$('#price').val(!isNaN(fBasePrice) && isFinite(fBasePrice) ? fBasePrice : 0);
        /*PPSASCRUM-142: end*/


		/* PPSASCRUM-56: start */
		// var startingPriceVal = roundToTwo(parseFloat($('#start-price-val').val())).toFixed(2);
		// var startingPriceVal = parseFloat($('#price').val()) - parseFloat($('#total-surcharges').val());
		// var startingPriceVal = fBasePrice - totalSurgesVal;
		// startingPriceVal = roundToTwo(!isNaN(startingPriceVal) && isFinite(startingPriceVal) ? startingPriceVal : 0).toFixed(2);
		// $('#explainmath').html('<h3 id="pricebreakdown">Base Price Surcharge Breakdown</h3><hr><table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000"><tr><td>Starting Price (before surcharges/add-ons)</td><td><B>$'+startingPriceVal+'</B></td></tr>'+$('#add-on-text-val').val()+'<tr><td><b>RESULTING BASE PRICE:</b></td><td><b><u>$'+roundToTwo(parseFloat($('#price').val())).toFixed(2)+'</u></b></td></tr></table><Br><Br><Br><Br><Br><br><Br><Br><Br>');
		/* PPSASCRUM-56: end */


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
	// TODO: Based on old formulae
	// if(warn1 != 'OK'){
	// 	warningboxcontent += '<img src="/img/delete.png" /> '+warn1+'<br>';

	// 	if(warn1 == 'FABRIC MUST BE AT LEAST 108 FOR HALF WIDTHS'){
	// 		$('#fabric-widths-per-panel,#fabric-width').parent().addClass('badvalue');
	// 	}else{
	// 		$('#fabric-widths-per-panel,#fabric-width').parent().removeClass('badvalue');
	// 	}
    //     warncount++;
	// }else{
	// 	$('#fabric-widths-per-panel,#fabric-width').parent().removeClass('badvalue');
	// }


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


	/* PPSASCRUM-56: start */

	fabPricePU = !isNaN(fabPricePU) && isFinite(fabPricePU) ? fabPricePU : 0;

	$('#fabric-price-pu').val(roundToTwo(fabPricePU).toFixed(2));

	// new calculations

		// Output#C (“FW”)

		var fwOut;

		if ($('#unit-of-measure').val() == 'pair') {
			fwOut = parseFloat($('#rod-width').val()) + (2 * parseFloat($('#default-return').val())) + (2 * parseFloat($('#default-overlap').val()));
		} else if ($('#draw').val() == 'center') {
			fwOut = parseFloat($('#rod-width').val()) + (2 * parseFloat($('#default-return').val()));
		} else {
			fwOut = parseFloat($('#rod-width').val()) + parseFloat($('#default-return').val());
		}
		
		fwOut = !isNaN(fwOut) && isFinite(fwOut) ? fwOut : 0;

		console.log(`Output: FW: ${fwOut}`);
		
		$('#fw').val(fwOut);
		
		
		// Output#F (“Fabric Widths”)
		
		var fabricWidthsOut;
		
		if ($('#railroaded').is(':checked')) {
			if ($('#unit-of-measure').val() == 'pair') {
				fabricWidthsOut = 2;
			} else {
				fabricWidthsOut = 1;
			}
		} else {
			var roundUpCal;
			
			if ($('#unit-of-measure').val() == 'pair') {
				roundUpCal = parseFloat($('#rod-width').val()) * (parseFloat($('#fullness').val()) / 100) + (2 * parseFloat($('#default-return').val())) + (2 * parseFloat($('#default-overlap').val())) + (8 * <?php echo $settings['ppd_single_side_hem']; ?>);
			} else if ($('#draw').val() == 'Center') {
				roundUpCal = (parseFloat($('#rod-width').val()) * (parseFloat($('#fullness').val()) / 100)) + (2 * parseFloat($('#default-return').val())) + (4 * <?php echo $settings['ppd_single_side_hem']; ?>);
			} else {
				roundUpCal = (parseFloat($('#rod-width').val()) * (parseFloat($('#fullness').val()) / 100)) + parseFloat($('#default-return').val()) + (4 * <?php echo $settings['ppd_single_side_hem']; ?>);
			}
			
			fabricWidthsOut = roundToTwo(Math.ceil(roundUpCal / parseFloat($('#fabric-width').val()))).toFixed(2);
		}

		fabricWidthsOut = !isNaN(fabricWidthsOut) && isFinite(fabricWidthsOut) ? fabricWidthsOut : 0;
		
		console.log(`Output: Fabric Widths: ${fabricWidthsOut}`);

		$('#total-widths').val(fabricWidthsOut);
		

		// Output#H (“Billable Widths”)

		var billableWidthsOut;
		
		if ($('#unit-of-measure').val() == 'pair') {
			billableWidthsOut = Math.ceil(((parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + (2 * parseFloat($('#default-return').val())) + (2 * parseFloat($('#default-overlap').val())) + (8 * <?php echo $settings['ppd_single_side_hem']; ?>)) / 54);
			billableWidthsOut = !(billableWidthsOut % 2) ? billableWidthsOut : billableWidthsOut + 1;
		} else if ($('#draw').val() == 'center') {
			billableWidthsOut = Math.ceil(((parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + (2 * parseFloat($('#default-return').val())) + (4 * <?php echo $settings['ppd_single_side_hem']; ?>)) / 54);
		} else {
			billableWidthsOut = Math.ceil(((parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + parseFloat($('#default-return').val()) + (4 * <?php echo $settings['ppd_single_side_hem']; ?>)) / 54);
		}

		billableWidthsOut = !isNaN(billableWidthsOut) && isFinite(billableWidthsOut) ? billableWidthsOut : 0;

		console.log(`Output: Billable Widths: ${billableWidthsOut}`);

		$('#labor-widths').val(billableWidthsOut);
		
		/* PPSASCRUM-339: start */
// 		var laborCostPerUnit = parseFloat($('#labor-widths').val()) * parseFloat($('#labor-per-width').val());
		let laborCostPerWidth = parseFloat($('#labor-per-width').val());
		if ($('#labor-per-width-custom-value').val() != '' && parseFloat($('#labor-per-width-custom-value').val().trim()) >= 0) {
			laborCostPerWidth = parseFloat($('#labor-per-width-custom-value').val());
		}
		
		var laborCostPerUnit = parseFloat($('#labor-widths').val()) * laborCostPerWidth;
		/* PPSASCRUM-339: end */

        laborCostPerUnit = !isNaN(laborCostPerUnit) && isFinite(laborCostPerUnit) ? laborCostPerUnit : 0;
        
        $('#labor-cost').val(laborCostPerUnit);
		
		// Output#J (“Fabric CL”)

		var fabricCLOut;
		
		var vertRepeatOutputVal = parseFloat($('#vert-repeat').val());
		if (vertRepeatOutputVal == 0) {
		    vertRepeatOutputVal = 1;
		}

		if (!$('#railroaded').is(':checked')) {
			// fabricCLOut = Math.ceil((parseFloat($('#length').val()) + crinoline_value_fabric + bottom_hem_value_fabric + <//?php echo $settings['ppd_table_allowance']; ?>) / vertRepeatOutputVal) * vertRepeatOutputVal;
			fabricCLOut = Math.ceil((parseFloat($('#length').val()) + crinoline_value_fabric + bottom_hem_value_fabric + parseFloat($('input[name=table-allowance]').val())) / vertRepeatOutputVal) * vertRepeatOutputVal;
		} else {
			var roundUpCal;

			if ($('#unit-of-measure').val() == 'pair') {
				roundUpCal = (parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + (2 * parseFloat($('#default-return').val())) + (2 * parseFloat($('#default-overlap').val())) + (8 * <?php echo $settings['ppd_single_side_hem']; ?>);
			} else if ($('#draw').val() == 'center') {
				roundUpCal = (parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + (2 * parseFloat($('#default-return').val())) + (4 * <?php echo $settings['ppd_single_side_hem']; ?>);
			} else {
				roundUpCal = (parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + parseFloat($('#default-return').val()) + (4 * <?php echo $settings['ppd_single_side_hem']; ?>);
			}

			
			fabricCLOut = Math.ceil(roundUpCal) / parseFloat($('#total-widths').val());
		}

		fabricCLOut = !isNaN(fabricCLOut) && isFinite(fabricCLOut) ? fabricCLOut : 0;
		
		console.log(`Output: Fabric CL: ${fabricCLOut}`);

		$('#adjusted-cl').val(fabricCLOut);


		// Output#J2 (“Fabric RR CL”)

		// TODO: Show error message when this result is triggered “ERROR on FL vs Fabric Width on RR”

		var fabricRRCLOut;
		var fabricInputErrorCondition = false;

		if (!$('#railroaded').is(':checked')) {
			fabricRRCLOut = 0;
		// } else if (Math.ceil((parseFloat($('#length').val()) + crinoline_value_fabric + bottom_hem_value_fabric + <//?php echo $settings['ppd_table_allowance']; ?>) / vertRepeatOutputVal) * vertRepeatOutputVal > parseFloat($('#fabric-width').val())) {
		} else if (Math.ceil((parseFloat($('#length').val()) + crinoline_value_fabric + bottom_hem_value_fabric + parseFloat($('input[name=table-allowance]').val())) / vertRepeatOutputVal) * vertRepeatOutputVal > parseFloat($('#fabric-width').val())) {
			fabricRRCLOut = 0;
			fabricInputErrorCondition = true;
			warncount++;
			warningboxcontent += '<span style="color:red !important;"><img src="/img/delete.png" /> ERROR on FL vs Fabric Width on RR</span><br>';
			$('input#fabric-width').parent().addClass('badvalue');
			// $('input#length').parent().addClass('badvalue');
			// $('div.addaslineitembutton button[type=submit]').prop('disabled', true).addClass('notallowed');
		} else {
			// fabricRRCLOut = Math.ceil((parseFloat($('#length').val()) + crinoline_value_fabric + bottom_hem_value_fabric + <//?php echo $settings['ppd_table_allowance']; ?>) / vertRepeatOutputVal) * vertRepeatOutputVal;
			fabricRRCLOut = Math.ceil((parseFloat($('#length').val()) + crinoline_value_fabric + bottom_hem_value_fabric + parseFloat($('input[name=table-allowance]').val())) / vertRepeatOutputVal) * vertRepeatOutputVal;
		}

		if (!fabricInputErrorCondition) {
			$('input#fabric-width').parent().removeClass('badvalue');
		// 	$('input#length').parent().removeClass('badvalue');
		// 	$('div.addaslineitembutton button[type=submit]').prop('disabled', false).removeClass('notallowed');
		}

		fabricRRCLOut = !isNaN(fabricRRCLOut) && isFinite(fabricRRCLOut) ? fabricRRCLOut : 0;

		console.log(`Output: Fabric RR CL: ${fabricRRCLOut}`);

		$('#fab-rr-cl').val(fabricRRCLOut);


		// Output#L (“Fabric Yds per Unit”)

		var fabYdsPerUnitOut = (parseFloat($('#total-widths').val()) * parseFloat($('#adjusted-cl').val()) / 36) * 1.05;

		var rawFabYdsPerUnitOut = parseFloat(roundToTwo(fabYdsPerUnitOut).toFixed(2));

		var intVal = Math.floor(fabYdsPerUnitOut);
		var floatVal = fabYdsPerUnitOut - intVal;

		if (floatVal < 0.5) {
			fabYdsPerUnitOut = intVal + 0.5;
		} else {
			fabYdsPerUnitOut = Math.ceil(fabYdsPerUnitOut);
		}

		fabYdsPerUnitOut = !isNaN(fabYdsPerUnitOut) && isFinite(fabYdsPerUnitOut) ? fabYdsPerUnitOut : 0;

		console.log(`Output: Fabric Yds per Unit: ${fabYdsPerUnitOut}`);

		$('#yds-per-unit').val(fabYdsPerUnitOut);


		// Output#M (“Total Fabric Yards”)

		// var totalFabYdsVal = rawFabYdsPerUnitOut * parseFloat($('#qty').val());
		var totalFabYdsVal = (((parseFloat($('#total-widths').val()) * parseFloat($('#adjusted-cl').val())) / 36) * 1.05) * parseFloat($('#qty').val());

		// if (parseFloat($('#qty').val()) % 2 != 0) {
			// totalFabYdsVal = (Math.floor(totalFabYdsVal / 0.5) + 1) * 0.5;
		// }

		/* if (!$('#railroaded').is(':checked')) {
			if (parseFloat($('#qty').val()) % 2 != 0) {
				totalFabYdsVal = (Math.floor(totalFabYdsVal / 0.5) + 1) * 0.5;
			}
		} else {
			if (parseFloat(totalFabYdsVal.toString().substring(0,5)) % 0.5 != 0) {
				totalFabYdsVal = (Math.floor(totalFabYdsVal / 0.5) + 1) * 0.5;
			}
		} */
		if (!totalFabYdsVal.toString().includes('.')) { totalFabYdsVal = totalFabYdsVal.toString().concat('.00'); }
		if (parseFloat('0.' + totalFabYdsVal.toString().split('.')[1].substring(0,2)) % 0.5 != 0) {
		// if (parseFloat('0.' + totalFabYdsVal.toString().split('.')[1].substring(0,2) == undefined ? totalFabYdsVal.toString().split('.')[1].substring(0,2) : '00') % 0.5 != 0) {
			totalFabYdsVal = (Math.floor(parseFloat(totalFabYdsVal) / 0.5) + 1) * 0.5;
		}

		totalFabYdsVal = roundToTwo(!isNaN(totalFabYdsVal) && isFinite(totalFabYdsVal) ? totalFabYdsVal : 0).toFixed(2);

		console.log(`Output: Total Fabric Yards: ${totalFabYdsVal}`);

		$('#total-yds').val(totalFabYdsVal);

		// fabPricePU = parseFloat(markedupFabric) * rawFabYdsPerUnitOut;
		
		// fabPricePU = parseFloat($('#fabric-price').val()) * parseFloat($('#yds-per-unit').val());
		let fabricCostPerYard = parseFloat($('select[name=fabric-cost-per-yard] option:selected').text().split('$')[1]);
		if ($('#fabric-cost-per-yard-custom-value').val() != '') {
			fabricCostPerYard = parseFloat($('#fabric-cost-per-yard-custom-value').val());
		}

		let inboundFreight = parseFloat($('#inbound-freight').val());
		if ($('#inbound-freight-custom-value').val() != '') {
			inboundFreight = parseFloat($('#inbound-freight-custom-value').val());
		}

		let fabMarkup = parseFloat($('#fabric-markup').val());
		if ($('#fabric-markup-custom-value').val() != '') {
			fabMarkup = parseFloat($('#fabric-markup-custom-value').val());
		}

		if (fabMarkup != 0) {
			fabMarkup = fabMarkup / 100 + 1;
// 		} else if ($('#fabric-markup-custom-value').val() != '') {
        } else {
			fabMarkup = 1;
		}

		var fFabCostPY = (fabricCostPerYard + inboundFreight) * fabMarkup;
		// fFabCostPY = parseFloat(roundToTwo(fFabCostPY).toFixed(2));
		// fFabCostPY = parseFloat(roundToTwo(fFabCostPY + 0.01).toFixed(2));
		// fFabCostPY = !isNaN(fFabCostPY) && isFinite(fFabCostPY) ? fFabCostPY : 0;

		/* if (parseFloat($('#qty').val()) == 1) {
			fFabCostPY = parseFloat(roundToTwo(fFabCostPY + 0.01).toFixed(2));
		} else {
			fFabCostPY = parseFloat(roundToTwo(fFabCostPY).toFixed(2));
		} */
		
		/* fFabCostPY = parseFloat(roundToTwo(fFabCostPY).toFixed(2)); */

		if (fFabCostPY.toString().includes('.')) {
			let fFabCostPYArr = fFabCostPY.toString().split('.');
			let integerPart = fFabCostPYArr[0];
			let decimalPart = fFabCostPYArr[1];
			if (decimalPart.toString().length > 2) {
				// decimalPart = parseFloat(fFabCostPYArr[0].toString() + '.' + (parseFloat(fFabCostPYArr[1].substring(0,2)) + 1).toString());
				// let laterDecimalPart = parseFloat(decimalPart.toString().substring(1,3));
				let laterDecimalPart = parseFloat(decimalPart.toString().substring(2,3));
				decimalPart = parseFloat(decimalPart.toString().substring(0,2));
				if (laterDecimalPart != 0) {
					/* if (decimalPart < 9) {
						decimalPart = '0' + (decimalPart + 1).toString();
					} else {
						decimalPart = (decimalPart + 1).toString();
						if (decimalPart == '100') { decimalPart = '00'; }
					} */
					if ((decimalPart < 9 && decimalPart > 0) || (decimalPart == 0 && laterDecimalPart >= 5)) {
						decimalPart = '0' + (decimalPart + 1).toString();
					} else if (decimalPart == 0 && laterDecimalPart < 5) {
						decimalPart = '00';
					} else {
						decimalPart = (decimalPart + 1).toString();
						if (decimalPart == '100') { decimalPart = '00'; }
					}
				}
				if (parseInt(roundToTwo(fFabCostPY).toFixed(2)) > parseInt(fFabCostPY)) {
					integerPart = parseInt(roundToTwo(fFabCostPY).toFixed(2));
				}
			}
			fFabCostPY = parseFloat(integerPart.toString() + '.' + decimalPart.toString());
		}

		$('#fabric-price').val(fFabCostPY);

		var fFabPricePU = parseFloat($('#total-yds').val()) / parseFloat($('#qty').val()) * fFabCostPY;
		// fFabPricePU = roundToTwo(fFabPricePU).toFixed(2);

		if (fFabPricePU.toString().includes('.')) {
			let fFabPricePUArr = fFabPricePU.toString().split('.');
			let integerPart = fFabPricePUArr[0];
			let decimalPart = fFabPricePUArr[1];
			if (decimalPart.toString().length > 2) {
				// let laterDecimalPart = parseFloat(decimalPart.toString().substring(1,3));
				let laterDecimalPart = parseFloat(decimalPart.toString().substring(2,3));
				decimalPart = parseFloat(decimalPart.toString().substring(0,2));
				if (laterDecimalPart != 0) {
					/* if (decimalPart < 9) {
						decimalPart = '0' + (decimalPart + 1).toString();
					} else {
						decimalPart = (decimalPart + 1).toString();
						if (decimalPart == '100') { decimalPart = '00'; }
					} */
					if ((decimalPart < 9 && decimalPart > 0) || (decimalPart == 0 && laterDecimalPart >= 5)) {
						decimalPart = '0' + (decimalPart + 1).toString();
					} else if (decimalPart == 0 && laterDecimalPart < 5) {
						decimalPart = '00';
					} else {
						decimalPart = (decimalPart + 1).toString();
						if (decimalPart == '100') { decimalPart = '00'; }
					}
				}
				if (parseInt(roundToTwo(fFabPricePU).toFixed(2)) > parseInt(fFabPricePU)) {
					integerPart = parseInt(roundToTwo(fFabPricePU).toFixed(2));
				}
			}
			fFabPricePU = parseFloat(integerPart.toString() + '.' + decimalPart.toString());
		}

		fFabPricePU = !isNaN(fFabPricePU) && isFinite(fFabPricePU) ? fFabPricePU : 0;

		// console.log('Fabric price PU cal: ' + parseFloat($('#fabric-price').val()) + " * " + parseFloat($('#yds-per-unit').val()));

		/* if (!$('#railroaded').is(':checked')) {
			if (parseFloat($('#qty').val()) % 2 == 0) {
				fFabPricePU = roundToTwo(parseFloat($('#fabric-price').val()) * parseFloat($('#total-yds').val()) / parseFloat($('#qty').val())).toFixed(2);
			} else if (parseFloat($('#qty').val()) != 1 && parseFloat($('#qty').val()) % 2 != 0) {
				fFabPricePU = ((parseFloat($('select[name=fabric-cost-per-yard] option:selected').text().split('$')[1]) + parseFloat($('#inbound-freight').val())) * (parseFloat($('#fabric-markup').val()) / 100 + 1) * parseFloat($('#total-yds').val()) / parseFloat($('#qty').val()));
				fFabPricePUArr = fFabPricePU.toString().split('.');
				let decimalPart = fFabPricePUArr[1].substring(0,2);
				if (parseInt(decimalPart.substring(0,1)) > 5) {
					fFabPricePU = parseFloat(fFabPricePUArr[0] + '.' + (parseInt(decimalPart.substring(0,1)) + 1).toString() + '0');
				} else {
					fFabPricePU = fFabPricePUArr[0] + '.' + parseInt(decimalPart.substring(0,1)).toString() + (parseInt(decimalPart.substring(1,2)) + 1).toString()
				}
			}
		} */

		/* if (parseFloat($('#qty').val()) % 2 == 0) {
			fFabPricePU = roundToTwo(parseFloat($('#fabric-price').val()) * parseFloat($('#total-yds').val()) / parseFloat($('#qty').val())).toFixed(2);
		} else if (parseFloat($('#qty').val()) != 1 && parseFloat($('#qty').val()) % 2 != 0) {
			fFabPricePU = ((parseFloat($('select[name=fabric-cost-per-yard] option:selected').text().split('$')[1]) + parseFloat($('#inbound-freight').val())) * (parseFloat($('#fabric-markup').val()) / 100 + 1) * parseFloat($('#total-yds').val()) / parseFloat($('#qty').val()));
			fFabPricePUArr = fFabPricePU.toString().split('.');
			let decimalPart = fFabPricePUArr[1].substring(0,2);
			if (parseInt(decimalPart.substring(0,1)) > 5) {
				fFabPricePU = parseFloat(fFabPricePUArr[0] + '.' + (parseInt(decimalPart.substring(0,1)) + 1).toString() + '0');
			} else {
				fFabPricePU = fFabPricePUArr[0] + '.' + parseInt(decimalPart.substring(0,1)).toString() + (parseInt(decimalPart.substring(1,2)) + 1).toString()
			}
		} */

		$('#fabric-price-pu').val(fFabPricePU);

		$('#total-fabric-price').val(roundToTwo(fFabPricePU * parseFloat($('#qty').val())).toFixed(2));


		// Output#O (“Lining Widths Per Unit”)

		var liningWidthsPerUnitVal;

		if ($('#linings_id option:selected').text() == 'No Lining') {
			liningWidthsPerUnitVal = 0;
		} else if ($('#lining_rr').is(':checked')) {
			if ($('#unit-of-measure').val() == 'pair') {
				liningWidthsPerUnitVal = 2;
			} else {
				liningWidthsPerUnitVal = 1;
			}
		} else {
			var roundUpCal;

			if ($('#unit-of-measure').val() == 'pair') {
				roundUpCal = (parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + (2 * parseFloat($('#default-return').val())) + (2 * parseFloat($('#default-overlap').val())) + (8 * <?php echo $settings['ppd_single_side_hem']; ?>);
			} else if ($('#draw').val() == 'Center') {
				roundUpCal = (parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + (2 * parseFloat($('#default-return').val())) + (4 * <?php echo $settings['ppd_single_side_hem']; ?>);
			} else {
				roundUpCal = (parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + parseFloat($('#default-return').val()) + (4 * <?php echo $settings['ppd_single_side_hem']; ?>);
			}

			liningWidthsPerUnitVal = roundToTwo(Math.ceil(roundUpCal / parseFloat($('#lining-width').val()))).toFixed(2);
		}

		liningWidthsPerUnitVal = !isNaN(liningWidthsPerUnitVal) && isFinite(liningWidthsPerUnitVal) ? liningWidthsPerUnitVal : 0;

		console.log(`Output: Lining Widths Per Unit: ${liningWidthsPerUnitVal}`);

		$('#lining-widths-each').val(liningWidthsPerUnitVal);


		// Output#OT (“TOTAL LIN WIDTHS”)

		var totalLinWidthsVal = roundToTwo(parseFloat($('#lining-widths-each').val()) * parseFloat($('#qty').val())).toFixed(2);

		totalLinWidthsVal = !isNaN(totalLinWidthsVal) && isFinite(totalLinWidthsVal) ? totalLinWidthsVal : 0;

		console.log(`Output: Total Lin Widths: ${totalLinWidthsVal}`);
		
		$('#total-lining-widths').val(totalLinWidthsVal);
		

		// Output#O2 (“Lining CL”)

		var liningClOut;

		if (!$('#lining_rr').is(':checked')) {
			// liningClOut = (parseFloat($('#length').val()) - 1.5) + crinoline_value_lining + <//?php echo $settings['ppd_bottom_hem_value_lining']; ?> + <//?php echo $settings['ppd_table_allowance']; ?>;
			liningClOut = (parseFloat($('#length').val()) - 1.5) + crinoline_value_lining + <?php echo $settings['ppd_bottom_hem_value_lining']; ?> + parseFloat($('input[name=table-allowance]').val());
		} else {
			var roundUpCal;

			if ($('#unit-of-measure').val() == 'pair') {
				roundUpCal = (parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + (2 * parseFloat($('#default-return').val())) + (2 * parseFloat($('#default-overlap').val())) + (8 * <?php echo $settings['ppd_single_side_hem']; ?>);
			} else if ($('#draw').val() == 'center') {
				roundUpCal = (parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + (2 * parseFloat($('#default-return').val())) + (4 * <?php echo $settings['ppd_single_side_hem']; ?>);
			} else {
				roundUpCal = (parseFloat($('#rod-width').val()) * parseFloat($('#fullness').val()) / 100) + parseFloat($('#default-return').val()) + (4 * <?php echo $settings['ppd_single_side_hem']; ?>);
			}

			liningClOut = roundUpCal / parseFloat($('#lining-widths-each').val());
		}

		liningClOut = Math.ceil(!isNaN(liningClOut) && isFinite(liningClOut) ? liningClOut : 0);

		console.log(`Output: Lining CL: ${liningClOut}`);

		$('#lining-cl').val(liningClOut);


		// Output#O3 (“Lining RR CL”)

		var liningRRClOut;
		var liningInputErrorCondition = false;

		if (!$('#lining_rr').is(':checked')) {
			liningRRClOut = 0;
		} else if (Math.ceil((parseFloat($('#length').val()) - 1.5) + crinoline_value_lining + <?php echo $settings['ppd_bottom_hem_value_lining']; ?> + parseFloat($('input[name=table-allowance]').val())) > parseFloat($('#lining-width').val())) {
			liningRRClOut = 0;
			warncount++;
			warningboxcontent += '<span style="color:#red !important;"><img src="/img/delete.png" /> ERROR on FL vs Lining Width on RR</span><br>';
			liningInputErrorCondition = true;
			$('input#lining-width').parent().addClass('badvalue');
			// $('input#length').parent().addClass('badvalue');
			// $('div.addaslineitembutton button[type=submit]').prop('disabled', true).addClass('notallowed');
		} else {
			liningRRClOut = Math.ceil((parseFloat($('#length').val()) - 1.5) + crinoline_value_lining + <?php echo $settings['ppd_bottom_hem_value_lining']; ?> + parseFloat($('input[name=table-allowance]').val()));
		}

		if (fabricInputErrorCondition || liningInputErrorCondition) {
			$('input#length').parent().addClass('badvalue');
			$('div.addaslineitembutton button[type=submit]').prop('disabled', true).addClass('notallowed');
		} else {
			$('input#length').parent().removeClass('badvalue');
			$('div.addaslineitembutton button[type=submit]').prop('disabled', false).removeClass('notallowed');
		}

		if (!liningInputErrorCondition) {
			$('input#lining-width').parent().removeClass('badvalue');
			// $('input#length').parent().removeClass('badvalue');
			// $('div.addaslineitembutton button[type=submit]').prop('disabled', false).removeClass('notallowed');
		}

		liningRRClOut = !isNaN(liningRRClOut) && isFinite(liningRRClOut) ? liningRRClOut : 0;

		console.log(`Output: Lining RR CL: ${liningRRClOut}`);
		
		$('#lining-rr-cl').val(liningRRClOut);


		// Output#P (“Lining Yds per Unit”)

		var liningYdsPerUnitOut = ((parseFloat($('#lining-widths-each').val()) * parseFloat($('#lining-cl').val())) / 36) * 1.05;
		
		var rawLiningYdsPerUnitOut = liningYdsPerUnitOut;

		var liningYdsPerUnitIntVal = Math.floor(liningYdsPerUnitOut);
		var liningYdsPerUnitIntValFloatVal = liningYdsPerUnitOut - liningYdsPerUnitIntVal;

		if (liningYdsPerUnitIntValFloatVal < 0.5) {
			liningYdsPerUnitOut = liningYdsPerUnitIntVal + 0.5;
		} else {
			liningYdsPerUnitOut = Math.ceil(liningYdsPerUnitOut);
		}

		liningYdsPerUnitOut = !isNaN(liningYdsPerUnitOut) && isFinite(liningYdsPerUnitOut) ? liningYdsPerUnitOut : 0;

		console.log(`Output: Lining Yds per Unit: ${liningYdsPerUnitOut}`);
		
		$('#lining-yds-per-unit').val(liningYdsPerUnitOut);
		

		// Output#Q (“TOTAL LINING YARDS”)

		// var totalLiningYdsOut = parseFloat($('#lining-yds-per-unit').val()) * parseFloat($('#qty').val());
		// var totalLiningYdsOut = rawLiningYdsPerUnitOut * parseFloat($('#qty').val());
		var totalLiningYdsOut = ((parseFloat($('#total-lining-widths').val()) * parseFloat($('#lining-cl').val()) / 36) * 1.05);

		if (totalLiningYdsOut % 0.5 != 0) {
		    totalLiningYdsOut = (Math.floor(totalLiningYdsOut / 0.5) + 1) * 0.5;
        }

		totalLiningYdsOut = !isNaN(totalLiningYdsOut) && isFinite(totalLiningYdsOut) ? totalLiningYdsOut : 0;

		console.log(`Output: TOTAL LINING YARDS: ${totalLiningYdsOut}`);
		
		$('#total-lining-yards').val(totalLiningYdsOut);
		
		
		if ($('#linings_id option:selected').text() == 'No Lining') {
    		$('#lining-widths-each').val(0.0);
    		$('#total-lining-widths').val(0.0);
    		$('#lining-cl').val(0.0);
    		$('#lining-rr-cl').val(0.0);
    		$('#lining-yds-per-unit').val(0.0);
    		$('#total-lining-yards').val(0.0);
    	}

		/* PPSASCRUM-56: start */
		if($('#com-fabric').prop('checked')) {
			$('#fabric-cost').val('0.00');
			$('#fabric-price').val('0.00');
			$('#fabric-price-pu').val('0.00');
			$('#total-fabric-price').val('0.00');
		}
		
		$('#com-fabric').change(function() {
			if($('#com-fabric').prop('checked')) {
				$('#fabric-cost').val('0.00');
				$('#fabric-price').val('0.00');
				$('#fabric-price-pu').val('0.00');
				$('#total-fabric-price').val('0.00');
			}	
		});
		/* PPSASCRUM-56: end */

		// fBasePrice = parseFloat($('#labor-cost').val()) + parseFloat($('#fabric-price-pu').val()) + parseFloat($('#lining-cost').val());
		fBasePrice = parseFloat($('#labor-cost').val()) + parseFloat($('#fabric-price-pu').val()) + parseFloat($('#lining-cost').val()) + parseFloat($('#total-surcharges').val());
		fBasePrice = roundToTwo(fBasePrice).toFixed(2);
		console.log('OUTPUT: Base Price: ', fBasePrice);
		$('#price').val(!isNaN(fBasePrice) && isFinite(fBasePrice) ? fBasePrice : 0);


		/* PPSASCRUM-56: start */
		var startingPriceVal = parseFloat($('#price').val()) - parseFloat($('#total-surcharges').val());
		startingPriceVal = roundToTwo(!isNaN(startingPriceVal) && isFinite(startingPriceVal) ? startingPriceVal : 0).toFixed(2);
		$('#explainmath').html('<h3 id="pricebreakdown">Base Price Surcharge Breakdown</h3><hr><table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000"><tr><td>Starting Price (before surcharges/add-ons)</td><td><B>$'+startingPriceVal+'</B></td></tr>'+$('#add-on-text-val').val()+'<tr><td><b>RESULTING BASE PRICE:</b></td><td><b><u>$'+roundToTwo(parseFloat($('#price').val())).toFixed(2)+'</u></b></td></tr></table><Br><Br><Br><Br><Br><br><Br><Br><Br>');
		/* PPSASCRUM-56: end */


/* PPSASCRUM-56: end */

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

	/* PPSASCRUM-56: start */
	$('input#lining-width, input#rod-width').change(function() {
		const currentValue = parseFloat($(this).val());
		if (!isNaN(currentValue) && currentValue < 0) {
			$(this).val(0);
		}
		if (isNaN(currentValue)) {
			$(this).val('');
		}
	});
	
	$('input#vert-repeat, input#fabric-width').change(function() {
		const currentValue = parseFloat($(this).val());
		if (!isNaN(currentValue) && currentValue < 0) {
			$(this).val(0);
		}
		if (isNaN(currentValue)) {
			$(this).val(0);
		}
	});

	if ($('select#linings_id').val() == 'default') {
		$('input#lining_rr').attr('disabled', true);
		$('input#lining-price-per-yd').attr('disabled', true);
		$('input#lining-width').attr('disabled', true);
	} else {
		$('input#lining_rr').attr('disabled', false);
		$('input#lining-price-per-yd').attr('disabled', false);
		$('input#lining-width').attr('disabled', false);
	}

	$('select#linings_id').change(function() {
		if ($(this).val() == 'default') {
			$('input#lining_rr').attr('disabled', true);
			$('input#lining-price-per-yd').attr('disabled', true);
			$('input#lining-width').attr('disabled', true);
		} else {
			$('input#lining_rr').attr('disabled', false);
			$('input#lining-price-per-yd').attr('disabled', false);
			$('input#lining-width').attr('disabled', false);
		}
	});
	/* PPSASCRUM-56: end */
	
<?php
if($userData['scroll_calcs'] == 1){
?>
correctColHeights();
setInterval('correctColHeights()',500);
<?php } ?>


$('#unit-of-measure').change(function(){
	if($(this).val() == 'panel'){
		$('#paneltypewrap').show('fast');
		if($('#panel-type').val() == 'Stationary'){
			/* PPSASCRUM-56: start */
			// $('#width-of-window').parent().hide('fast');
			// $('#wall-left').parent().hide('fast');
			// $('#wall-right').parent().hide('fast');
			// $('#rod-width').parent().hide('fast');
			// $('#fabric-widths-per-panel').parent().show('fast');
			/* PPSASCRUM-56: end */
		}else{
			/* PPSASCRUM-56: start */
			// $('#width-of-window').parent().show('fast');
			// $('#wall-left').parent().show('fast');
			// $('#wall-right').parent().show('fast');
			// $('#rod-width').parent().show('fast');
			// $('#fabric-widths-per-panel').parent().hide('fast');
			/* PPSASCRUM-56: end */
		}
	}else{
		/* PPSASCRUM-56: start */
		// $('#width-of-window').parent().show('fast');
		// $('#wall-left').parent().show('fast');
		// $('#wall-right').parent().show('fast');
		// $('#rod-width').parent().show('fast');
		// $('#fabric-widths-per-panel').parent().hide('fast');
		/* PPSASCRUM-56: end */
		// $('#paneltypewrap').hide('fast');
	}
});



$('#railroaded').click(function(){
	
	if($('#unit-of-measure').val() == 'panel' && $('#panel-type').val() == 'Stationary'){
		/* PPSASCRUM-56: start */
		// $('#width-of-window').parent().hide('fast');
		// $('#wall-left').parent().hide('fast');
		// $('#wall-right').parent().hide('fast');
		// $('#rod-width').parent().hide('fast');
		// $('#fabric-widths-per-panel').parent().show('fast');
		/* PPSASCRUM-56: end */
	}else{
		/* PPSASCRUM-56: start */
		// $('#width-of-window').parent().show('fast');
		// $('#wall-left').parent().show('fast');
		// $('#wall-right').parent().show('fast');
		// $('#rod-width').parent().show('fast');
		// $('#fabric-widths-per-panel').parent().hide('fast');
		/* PPSASCRUM-56: end */
	}

	if($(this).prop('checked')){
		// $('#cutwidthwrap').show('fast');
		/* PPSASCRUM-56: start */
		// $('#widths-each').parent().hide('fast');
		// $('#rounded-widths-each').parent().hide('fast');
		/* PPSASCRUM-56: end */
		// $('#total-widths').parent().hide('fast');
		// $('#adjusted-cl').parent().hide('fast');
		// $('#vert-repeat').parent().hide('fast');
		/* PPSASCRUM-56: start */
		// $('#vertical-waste').parent().hide('fast');
		/* PPSASCRUM-56: end */
	}else{
		$('#cutwidthwrap').hide('fast');
		/* PPSASCRUM-56: start */
		// $('#widths-each').parent().show('fast');
		// $('#rounded-widths-each').parent().show('fast');
		/* PPSASCRUM-56: end */
		$('#total-widths').parent().show('fast');
		$('#adjusted-cl').parent().show('fast');
		$('#vert-repeat').parent().show('fast');
		/* PPSASCRUM-56: start */
		// $('#vertical-waste').parent().show('fast');
		/* PPSASCRUM-56: end */
	}

});

$('#railroaded').change(function(){
		
	if($('#unit-of-measure').val() == 'panel' && $('#panel-type').val() == 'Stationary'){
		/* PPSASCRUM-56: start */
		// $('#width-of-window').parent().hide('fast');
		// $('#wall-left').parent().hide('fast');
		// $('#wall-right').parent().hide('fast');
		// $('#rod-width').parent().hide('fast');
		// $('#fabric-widths-per-panel').parent().show('fast');
		/* PPSASCRUM-56: end */
	}else{
		/* PPSASCRUM-56: start */
		// $('#width-of-window').parent().show('fast');
		// $('#wall-left').parent().show('fast');
		// $('#wall-right').parent().show('fast');
		// $('#rod-width').parent().show('fast');
		// $('#fabric-widths-per-panel').parent().hide('fast');
		/* PPSASCRUM-56: end */
	}

	if($(this).prop('checked')){
		// $('#cutwidthwrap').show('fast');
		/* PPSASCRUM-56: start */
		// $('#widths-each').parent().hide('fast');
		// $('#rounded-widths-each').parent().hide('fast');
		/* PPSASCRUM-56: end */
		// $('#total-widths').parent().hide('fast');
		// $('#adjusted-cl').parent().hide('fast');
		// $('#vert-repeat').parent().hide('fast');
		/* PPSASCRUM-56: start */
		// $('#vertical-waste').parent().hide('fast');
		/* PPSASCRUM-56: end */
	}else{
		$('#cutwidthwrap').hide('fast');
		/* PPSASCRUM-56: start */
		// $('#widths-each').parent().show('fast');
		// $('#rounded-widths-each').parent().show('fast');
		/* PPSASCRUM-56: end */
		$('#total-widths').parent().show('fast');
		$('#adjusted-cl').parent().show('fast');
		$('#vert-repeat').parent().show('fast');
		/* PPSASCRUM-56: start */
		// $('#vertical-waste').parent().show('fast');
		/* PPSASCRUM-56: end */
	}

});



$('#panel-type').change(function(){
	if($(this).val() == 'Stationary'){
		/* PPSASCRUM-56: start */
		// $('#width-of-window').parent().hide('fast');
		// $('#wall-left').parent().hide('fast');
		// $('#wall-right').parent().hide('fast');
		// $('#rod-width').parent().hide('fast');
		// $('#fabric-widths-per-panel').parent().show('fast');
		/* PPSASCRUM-56: end */
	}else{
		/* PPSASCRUM-56: start */
		// $('#width-of-window').parent().show('fast');
		// $('#wall-left').parent().show('fast');
		// $('#wall-right').parent().show('fast');
		// $('#rod-width').parent().show('fast');
		// $('#fabric-widths-per-panel').parent().hide('fast');
		/* PPSASCRUM-56: end */
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
// 		location.replace('/quotes/add/<?php echo $quoteID; ?>');
		location.replace('/quotes/add/<?php echo $quoteID; ?>/<?php echo $ordermode; ?>');
	});




<?php
if(isset($isedit) && $isedit=='1'){
//do not set default

}else{
?>
	/* PPSASCRUM-56: start */
	$('#linings_id').val('default');
	/* PPSASCRUM-56: end */
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
	});
*/


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



	$('#linings_id').change(function(){
		/*PPSASCRUM-142: start*/
		if(canCalculate()){
			doCalculation();
		}
		/*PPSASCRUM-142: end*/

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

		/* PPSASCRUM-56: start */
		if ($(this).val() == 'default') {
    	    $('#lining_rr').prop('checked', false);
    	    $('#lining-price-per-yd').val('5.75');
    	    $('#lining-width').val('54');
    	}
        /* PPSASCRUM-56: end */


		$('#lining-price-per-yd').val($(this).find('option:selected').attr('data-price'));

	});



	/*
	$('#unit-of-measure').change(function(){
		if($(this).val() == 'panel'){
			$('#fabricwidthsperpanelwrap').show('fast');
			$('#width-of-window').val('0').parent().hide('fast');
			$('#wall-left').val('0').parent().hide('fast');
			$('#wall-right').val('0').parent().hide('fast');
			$('#rod-width').val('0').parent().hide('fast');
			//$('#rounded-widths-each').parent().hide('fast');
		}else{
			$('#fabricwidthsperpanelwrap').hide('fast');
			$('#width-of-window').val('0').parent().show('fast');
			$('#wall-left').val('4').parent().show('fast');
			$('#wall-right').val('4').parent().show('fast');
			$('#rod-width').val('').parent().show('fast');
			//$('#rounded-widths-each').parent().show('fast');
		}
	});
	*/

	/* PPSASCRUM-56: start */
// 	$('.fieldsection').append('<datalist id="selectable_fullness_values">' +
// 	fullnessSelectableValues.map(value => `<option value="${value}">`).join('') +
// 	'</datalist>');

	if ($('#unit-of-measure').val() == 'pair') {
		$('#draw').val('center');
		$('#draw').attr('disabled',true);
	} else {
		$('#draw').attr('disabled',false);		
	}

	$('#unit-of-measure').change(function() {
		if ($('#unit-of-measure').val() == 'pair') {
			$('#draw').val('center');
			$('#draw').attr('disabled',true);
		} else {
			$('#draw').attr('disabled',false);
		}
	});
	
	switch ($('#crinoline').val()) {
		case "4_single":
			crinoline_value_fabric = 5;
			crinoline_value_lining = 2;
			break;
		case "4_double":
			crinoline_value_fabric = 8;
			crinoline_value_lining = 2;
			break;
		case "6_single":
			crinoline_value_fabric = 7;
			crinoline_value_lining = 2;
			break;
		case "6_double":
			crinoline_value_fabric = 12;
			crinoline_value_lining = 2;
			break;
		case "none":
			crinoline_value_fabric = 3;
			crinoline_value_lining = 3;
			break;
		default:
			break;
	}

	$('#crinoline').change(function() {
		switch ($('#crinoline').val()) {
			case "4_single":
				crinoline_value_fabric = 5;
				crinoline_value_lining = 2;
				break;
			case "4_double":
				crinoline_value_fabric = 8;
				crinoline_value_lining = 2;
				break;
			case "6_single":
				crinoline_value_fabric = 7;
				crinoline_value_lining = 2;
				break;
			case "6_double":
				crinoline_value_fabric = 12;
				crinoline_value_lining = 2;
				break;
			case "none":
				crinoline_value_fabric = 3;
				crinoline_value_lining = 3;
				break;
			default:
				break;
		}
	});
	
	if ($('#bottom_hem_fold').val() == "single") {
		bottom_hem_value_fabric = parseFloat($('#bottom_hem_size').val()) + 1;
	} else if ($('#bottom_hem_fold').val() == "double") {
		bottom_hem_value_fabric = parseFloat($('#bottom_hem_size').val()) * 2;
	}

	$('#bottom_hem_fold').change(function() {
		if ($('#bottom_hem_fold').val() == "single") {
			bottom_hem_value_fabric = parseFloat($('#bottom_hem_size').val()) + 1;
		} else if ($('#bottom_hem_fold').val() == "double") {
			bottom_hem_value_fabric = parseFloat($('#bottom_hem_size').val()) * 2;
		}
	});

	$('#bottom_hem_size').change(function() {
		if ($('#bottom_hem_fold').val() == "single") {
			bottom_hem_value_fabric = parseFloat($('#bottom_hem_size').val()) + 1;
		} else if ($('#bottom_hem_fold').val() == "double") {
			bottom_hem_value_fabric = parseFloat($('#bottom_hem_size').val()) * 2;
		}
	});

	if ($('#linings_id option:selected').text() == 'No Lining') {
		$('#lining-widths-each').val(0.0);
		$('#total-lining-widths').val(0.0);
		$('#lining-cl').val(0.0);
		$('#lining-rr-cl').val(0.0);
		$('#lining-yds-per-unit').val(0.0);
		$('#total-lining-yards').val(0.0);
	}

	$('#linings_id').change(function() {
		if ($('#linings_id option:selected').text() == 'No Lining') {
			$('#lining-widths-each').val(0.0);
			$('#total-lining-widths').val(0.0);
			$('#lining-cl').val(0.0);
			$('#lining-rr-cl').val(0.0);
			$('#lining-yds-per-unit').val(0.0);
			$('#total-lining-yards').val(0.0);
		}
	});
	
	// mandatory fields triggering calculation
	
	$('#stiffener').change(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});
	
	$('#unit-of-measure').change(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});
	
	$('#rod-width').change(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});
	
	$('#length').change(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});
	
	$('#full_width').change(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});
	
	$('#default-overlap').keyup(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});
	
	$('#default-return').keyup(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});

	$('#qty').change(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});

	$('#railroaded').change(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});

	$('#com-fabric').change(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});

	$('#lining_rr').change(function() {
	    if (canCalculate()) {
    		doCalculation();
    	}
	});

	if (canCalculate()) {
		doCalculation();
		doCalculation();
	}

	/* PPSASCRUM-56: start */
	$('input[name=table-allowance]').change(function() {
		if (canCalculate()) {
    		doCalculation();
    	}
	});

	$('#lining_rr').change(function() {
		if (canCalculate()) {
    		doCalculation();
    	}
	});
	/* PPSASCRUM-56: end */

	$('input, select').on('change', function() {
	// $('input').change(function() {
		if (canCalculate()) {
			doCalculation();
			console.log('Input change alert invoked');
			doCalculation();
		}
		// else {
		// 	return false;
		// }
	});

	$('button').on('click', function() {
	// $('input').change(function() {
		if (canCalculate()) {
			doCalculation();
			console.log('Input change alert invoked');
			doCalculation();
		}
		// else {
		// 	return false;
		// }
	});

	if ($('#linings_id > option[selected=selected]').val()) {
		if ($('#linings_id').val() == 'default' && $('#linings_id > option:selected').val() != $('#linings_id > option[selected=selected]').val()) {
			$('#linings_id').val(parseInt($('#linings_id > option[selected=selected]').val()));
			if (canCalculate()) {
				doCalculation();
				doCalculation();
			}
		}
	}
	
	/* PPSASCRUM-56: start */
	$('textarea#fabric-notes, textarea#notes-for-workroom').keydown(function(event) {
		if (event.key === 'Enter') {
			event.stopPropagation();
		}
	});
	/* PPSASCRUM-56: end */
	
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
	/* PPSASCRUM-317: end */
});

var fullnessSelectableValues = [ 150 , 180 , 200 , 225 , 250 , 300 ];
var crinoline_value_fabric;
var crinoline_value_lining;
var bottom_hem_value_fabric;
/* PPSASCRUM-56: end */

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
		/* PPSASCRUM-56: start */
		errorcount++;
		/* PPSASCRUM-56: end */
}else{
		$('#unit-of-measure').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	
	
	// TODO: Not sure if this validation is required
	// if($('#unit-of-measure').val() == 'panel'){
	// 	$('#rod-width').removeClass('notvalid').removeClass('validated');
		
	// }else{
	// 	if($('#rod-width').val() == ''){
	// 		$('#rod-width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
	// 	}else{
	// 		$('#rod-width').removeClass('notvalid').removeClass('validated').addClass('validated');
	// 	}
	// }

	if ($('#rod-width').val() == '' || $('#rod-width').val() == '0') {
		$('#rod-width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		/* PPSASCRUM-56: start */
		errorcount++;
		/* PPSASCRUM-56: end */
	} else {
		$('#rod-width').removeClass('notvalid').removeClass('validated').addClass('validated');
	}


	/* PPSASCRUM-56: start */
	if ($('#lining-width').val() == '' || $('#lining-width').val() == '0') {
		if ($('#lining-width').val() == '0') {
			$('#lining-width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		} else {
			$('#lining-width').removeClass('notvalid').removeClass('validated').addClass('validated');
		}
		errorcount++;
	} else {
		$('#lining-width').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	/* PPSASCRUM-56: end */


	
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

	/* PPSASCRUM-56: start */
	if (!fullnessSelectableValues.includes(parseInt($('#fullness').val()))) {
		$('#fullness').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	} else {
		$('#fullness').removeClass('notvalid').removeClass('validated').addClass('validated');
	}

	if ($('select#full_width').val() == '') {
		$('select#full_width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	} else {
		$('select#full_width').removeClass('notvalid').removeClass('validated').addClass('validated');
	}

	if ($('select#stiffener').val() == '') {
		$('select#stiffener').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	} else {
		$('select#stiffener').removeClass('notvalid').removeClass('validated').addClass('validated');
	}

	/* PPSASCRUM-56: end */

    	/*PPSASCRUM-201 start */
	if(($('#quotetype').val() == 1)  &&  $('#location').val()==''){
		//console.log('Cannot calculate without a Length value');
		$('#location').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#location').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
/*PPSASCRUM-201 end */

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
		console.log('canCalculate(): false');
		return false;
	}else{
		console.log('canCalculate(): true');
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
<Br><Br><Br><Br>
<div id="explainmath"></div>