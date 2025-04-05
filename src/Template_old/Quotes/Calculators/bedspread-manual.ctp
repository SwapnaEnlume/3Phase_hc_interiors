<style>



label[for=cl]{ width:40% !important; }
#cl{ width: 58% !important; }


body{font-family:'Helvetica',Arial,sans-serif; font-size:medium;}
h1{ text-align:center; font-size:x-large; font-weight:bold; color:#1F2E67; margin:15px 0 0 0; }
form{ text-align:center; width:95%; max-width:750px; margin:20px auto; }
	
form label{ font-weight:bold; float:left; width:68%; text-align:left; font-size:small; vertical-align:middle; }
form input{ float:right; padding:5px !important; height:auto !important; width:26% !important; vertical-align:middle; font-size:12px; margin-bottom:0 !important; }
form select{ float:right; padding:5px !important; height:auto !important; width:22% !important; vertical-align:middle; font-size:12px; margin-bottom:0 !important; }
form div.input{ clear:both; padding:10px 0; }
#calculatedPrice{ clear:both; padding:20px 0; font-size:x-large;  }

#cannotcalculate{ color:red; text-align: center; font-weight:normal; padding:5px; font-size:12px; display:block; border:1px solid red; background:#FDDDDE; }
	

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


#calcformleft .selectbox label{ width:55% !important; }
#calcformleft .selectbox select{ width:36% !important; }

#calcformleft label[for=style]{ width:55% !important; }
#calcformleft select[name=style]{ width:36% !important; }

#calcformleft #quiltpatternwrap label{ width:55% !important; }
#calcformleft select#quilting-pattern{ width:36% !important; }

input.notvalid,select.notvalid{ border:1px solid red; }
input.validated,select.validated{ border:1px solid green; }
	
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
#fabric-yards{ width:54% !important; }

label[for=fabric-cost]{ width:40% !important; }
#fabric-cost{ width:54% !important; }

.greenline{ background:#fff; border:0; color:green; }
.redline{ background:#fff; border:0; color:red; }
.grayline{ background:#fff; border:0; font-style:italic; }

.calculatebutton{ float:left; width:40%; padding:5px 0; }
.calculatebutton button{ padding:5px 10px !important; background:#000 !important; font-size:12px; }
.addaslineitembutton{ float:right; width:50%; padding:5px 0; }

#cancelbutton{ font-size:12px !important; padding:5px !important; font-size:14px !important; background:#F7E4E4 !important; color:#000 !important; border:1px solid #534546 !important; }

#resultsblock .input label{ width:55%; }
#resultsblock .input input{ width:42% !important; }
#resultsblock .input select{ width:44% !important; }

	
form .input.checkbox label{ width:90% !important; }
	



label[for=layout-status]{ display:none; }
#layout-status{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }

label[for=cluster-status]{ display:none; }
#cluster-status{ width: 91% !important; font-size:12px; height: 40px; text-align:center; background:transparent; border:0; }


label[for=mattress-width-status]{ display:none; }
#mattress-width-status{ width: 91% !important; font-size:12px; text-align:center; background:transparent; border:0; }



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


</style>

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
		echo "<h1>Edit Manual Bedspread</h1>";
	}else{
		echo "<h1>Add Manual Bedspread</h1>";
	}
}else{
	echo "<h1>ERROR: You cannot do a Standalone Manual calculation</h1>";exit;
}


echo "<hr style=\"clear:both;\" />";


echo $this->Form->create();
echo "<div id=\"calcformleft\">";
echo "<h2>Manual BS - ".$fabricData['fabric_name']." (".$fabricData['color'].")</h2>";

echo $this->Form->input('process',['type'=>'hidden','value'=>'insertlineitem']);
echo $this->Form->input('calculator-used',['type'=>'hidden','value'=>'bedspread-manual']);

echo $this->Form->input('fabricid',['type'=>'hidden','value'=>$fabricid]);




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
echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','autocomplete'=>'off','min'=>0,'value'=>$qtyval]);





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





if(isset($thisItemMeta['quilted']) && $thisItemMeta['quilted']=='1'){
	$quiltedChecked=true;
}else{
	$quiltedChecked=false;
}
echo $this->Form->input('quilted',['type'=>'checkbox','label'=>'Quilted','checked'=>$quiltedChecked]);


echo "<div id=\"quiltpatternwrap\" class=\"input select\"";
if(!$quiltedChecked){ echo " style=\"display:none;\""; }
echo ">
<label>Quilting Pattern</label>
<select name=\"quilting-pattern\" id=\"quilting-pattern\">";
$quiltingPatterns=explode("|",$settings['quilting_patterns']);
foreach($quiltingPatterns as $pattern){
	echo "<option value=\"".htmlspecialchars($pattern)."\"";
	if(isset($thisItemMeta['quilting-pattern']) && htmlspecialchars_decode($thisItemMeta['quilting-pattern']) == $pattern){
		echo " selected";
	}
	echo ">".$pattern."</option>\n";
}
echo "</select>
</div>";





if(isset($thisItemMeta['style']) && strlen(trim($thisItemMeta['style'])) >0){
	$styleval=array('value'=>$thisItemMeta['style'],'required'=>true);
}else{
	$styleval=array('empty'=>'--Select One--','required'=>true);
}
echo "<div class=\"input\">";
echo $this->Form->label('Style');
echo $this->Form->select('style',['Throw'=>'Throw','Fitted'=>'Fitted'],$styleval);
echo "</div>";






echo "<div id=\"matchingthreadwrap\"";
if(!$quiltedChecked){
	echo " style=\"display:none;\"";
}
echo ">";
if(isset($thisItemMeta['matching-thread']) && $thisItemMeta['matching-thread']=='1'){
	$matchingthreadChecked=true;
}else{
	$matchingthreadChecked=false;
}
echo $this->Form->input('matching-thread',['type'=>'checkbox','label'=>'Matching Thread?','checked'=>$matchingthreadChecked]);
echo "</div>";




echo "<fieldset class=\"fieldsection\"><legend>DIMENSIONS</legend>";


if(isset($thisItemMeta['width']) && is_numeric($thisItemMeta['width'])){
	$widthval=$thisItemMeta['width'];
}else{
	$widthval='0';
}
echo $this->Form->input('width',['type'=>'number','min'=>'0','value'=>$widthval,'label'=>'FINISHED WIDTH']);


if(isset($thisItemMeta['length']) && is_numeric($thisItemMeta['length'])){
	$lengthval=$thisItemMeta['length'];
}else{
	$lengthval='0';
}
echo $this->Form->input('length',['type'=>'number','min'=>'0','value'=>$lengthval,'label'=>'FINISHED LENGTH']);





echo "<div id=\"assumeddropwidthwrap\"";
if($thisItemMeta['railroaded'] == '1' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '1')){
	echo " style=\"display:none;\"";
}
echo ">";
if(isset($thisItemMeta['assumed-drop-width']) && is_numeric($thisItemMeta['assumed-drop-width'])){
	$assumeddropwidthval=$thisItemMeta['assumed-drop-width'];
}else{
	$assumeddropwidthval='19';
}
echo $this->Form->input('assumed-drop-width',['type'=>'number','step'=>'any','value'=>$assumeddropwidthval,'label'=>'Finished drop']);
echo "</div>";



if(isset($thisItemMeta['custom-top-width-mattress-w']) && is_numeric($thisItemMeta['custom-top-width-mattress-w'])){
	$customtopwidthmattresswval=$thisItemMeta['custom-top-width-mattress-w'];
}else{
	$customtopwidthmattresswval='36';
}
echo $this->Form->input('custom-top-width-mattress-w',['type'=>'number','min'=>'0','value'=>$customtopwidthmattresswval,'label'=>'Mattress Width']);


echo "</fieldset>";




echo "<fieldset class=\"fieldsection\"><legend>FABRIC SPECS &amp; PRICING</legend>";



if(isset($thisItemMeta['fabric-width']) && is_numeric($thisItemMeta['fabric-width'])){
	$fabricwidthval=$thisItemMeta['fabric-width'];
}else{
	$fabricwidthval=$fabricData['fabric_width'];
}
echo $this->Form->input('fabric-width',['type'=>'number','min'=>'0','value'=>$fabricwidthval,'label'=>'Fabric Width']);





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
echo $this->Form->input('vertical-repeat',['type'=>'number','step'=>'any','min'=>'0','value'=>$verticalrepeatval,'label'=>'Vertical Repeat']);
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






if(isset($thisItemMeta['backing-quilting-price-per-yd']) && is_numeric($thisItemMeta['backing-quilting-price-per-yd'])){
	$backingquiltingpriceperydval=number_format($thisItemMeta['backing-quilting-price-per-yd'],2,'.','');
}else{
	$backingquiltingpriceperydval=0;
}
echo $this->Form->input('backing-quilting-price-per-yd',['type'=>'number','value'=>$backingquiltingpriceperydval,'label'=>'Backing/Quilting Price per yd','step'=>'any','min'=>0]);



if(intval($quoteID) == 0){
	echo $this->Form->input('custom-backing-quilting-price-per-yd',['value'=> '','label'=>'Backing Price Override']);
}

echo "</fieldset>";


echo "<fieldset class=\"fieldsection\"><legend>LABOR</legend>";

if(isset($thisItemMeta['labor-per-bs']) && is_numeric($thisItemMeta['labor-per-bs'])){
	$laborperbsval=number_format($thisItemMeta['labor-per-bs'],2,'.','');
}else{
	$laborperbsval='';
}
echo $this->Form->input('labor-per-bs',['type'=>'number','value'=>$laborperbsval,'label'=>'Labor per BS','step'=>'any','min'=>0]);



if(intval($quoteID) == 0){
	echo $this->Form->input('custom-base-labor',['value'=> '','label'=>'Labor Override']);
}

echo "</fieldset>";








echo "<fieldset class=\"fieldsection\"><legend>FABRIC ROUNDING</legend>";


if(isset($thisItemMeta['force-fitted-style-yds-accuracy']) && $thisItemMeta['force-fitted-style-yds-accuracy']=='1'){
	$forcefittedstyleydsaccuracyChecked=true;
}else{
	$forcefittedstyleydsaccuracyChecked=false;
}
echo $this->Form->input('force-fitted-style-yds-accuracy',['type'=>'checkbox','label'=>'Force Fitted Style Yds Accuracy','checked'=>$forcefittedstyleydsaccuracyChecked]);



if(isset($thisItemMeta['force-full-widths-ea-bs']) && $thisItemMeta['force-full-widths-ea-bs']=='1'){
	$forcefullwidthseabsChecked=true;
}else{
	$forcefullwidthseabsChecked=false;
}
echo $this->Form->input('force-full-widths-ea-bs',['type'=>'checkbox','label'=>'Force full widths ea BS','checked'=>$forcefullwidthseabsChecked]);



if(isset($thisItemMeta['force-full-widths-eol']) && $thisItemMeta['force-full-widths-eol']=='1'){
	$forcefullwidthseolChecked=true;
}elseif(isset($thisItemMeta['force-full-widths-eol']) && $thisItemMeta['force-full-widths-eol']=='0'){
	$forcefullwidthseolChecked=false;
}else{
	$forcefullwidthseolChecked=true;
}
echo $this->Form->input('force-full-widths-eol',['type'=>'checkbox','label'=>'Force Full Widths EOL','checked'=>$forcefullwidthseolChecked]);







if(isset($thisItemMeta['force-rounded-yds-ea-bs']) && $thisItemMeta['force-rounded-yds-ea-bs']=='1'){
	$forceroundedydseabsChecked=true;
}else{
	$forceroundedydseabsChecked=false;
}
echo $this->Form->input('force-rounded-yds-ea-bs',['type'=>'checkbox','label'=>'Force rounded yds ea BS','checked'=>$forceroundedydseabsChecked]);




if(isset($thisItemMeta['force-distributed-rounded-yds']) && $thisItemMeta['force-distributed-rounded-yds']=='1'){
	$forcedistributedroundedydsChecked=true;
}else{
	$forcedistributedroundedydsChecked=false;
}
echo $this->Form->input('force-distributed-rounded-yds',['type'=>'checkbox','label'=>'Force Distributed rounded yds EOL','checked'=>$forcedistributedroundedydsChecked]);


echo "</fieldset>";



echo "<fieldset class=\"fieldsection\"><legend>ADVANCED</legend>";

if(isset($thisItemMeta['extra-inches-seam-hems']) && is_numeric($thisItemMeta['extra-inches-seam-hems'])){
	$extrainchesseamhemsval=$thisItemMeta['extra-inches-seam-hems'];
}else{
	$extrainchesseamhemsval='1';
}
echo $this->Form->input('extra-inches-seam-hems',['type'=>'number','min'=>'0','value'=>$extrainchesseamhemsval,'label'=>'Extra Inches (seam/hems)']);

echo "</fieldset>";




echo "</div>";
?>

<div id="resultsblock">

<div id="cannotcalculate">Missing required information.</div>
<?php
	
if(isset($thisItemMeta['waste-overhead']) && strlen(trim($thisItemMeta['waste-overhead'])) > 0){
	$wasteoverheadval=$thisItemMeta['waste-overhead'];
}else{
	$wasteoverheadval='';
}
echo $this->Form->input('waste-overhead',['label'=>'Waste/Overhead','value'=>$wasteoverheadval]);
	


echo $this->Form->input('top-widths',['type'=>'hidden','value'=>'0']);
echo $this->Form->input('drop-widths',['type'=>'hidden','value'=>'0']);

	
if(isset($thisItemMeta['adjusted-fabric-width']) && strlen(trim($thisItemMeta['adjusted-fabric-width'])) > 0){
	$adjustedfabricwidthval=$thisItemMeta['adjusted-fabric-width'];
}else{
	$adjustedfabricwidthval='';
}
echo $this->Form->input('adjusted-fabric-width',['label'=>'Adjusted Fab Width','value'=>$adjustedfabricwidthval]);
	


//difficulty rating
if(isset($thisItemMeta['difficulty-rating']) && strlen(trim($thisItemMeta['difficulty-rating'])) >0){
	$difficultyratingval=$thisItemMeta['difficulty-rating'];
}else{
	$difficultyratingval='';
}
echo $this->Form->input('difficulty-rating',['label'=>'Total Difficulty','readonly'=>true,'value'=>$difficultyratingval]);




/*	
if(isset($thisItemMeta['top']) && strlen(trim($thisItemMeta['top'])) > 0){
	$topval=$thisItemMeta['top'];
}else{
	$topval='';
}
echo $this->Form->input('top',['label'=>'Calculated Mattress Width','value'=>$topval]);
*/

/*
if(isset($thisItemMeta['mattress-width-status']) && strlen(trim($thisItemMeta['mattress-width-status'])) >0){
	$mattresswidthstatusval=$thisItemMeta['mattress-width-status'];
}else{
	$mattresswidthstatusval='';
}
echo $this->Form->input('mattress-width-status',['style'=>'width:91% !important;','label'=>'Mattress Width Status','value'=>$mattresswidthstatusval]);

*/



if(isset($thisItemMeta['top-cut']) && strlen(trim($thisItemMeta['top-cut'])) > 0){
	$topcutval=$thisItemMeta['top-cut'];
}else{
	$topcutval='';
}
echo $this->Form->input('top-cut',['label'=>'Top (Cut)','value'=>$topcutval]);




if(isset($thisItemMeta['drop']) && strlen(trim($thisItemMeta['drop'])) > 0){
	$dropval=$thisItemMeta['drop'];
}else{
	$dropval='';
}
echo $this->Form->input('drop',['label'=>'Drop (Cut)','value'=>$dropval]);





if(isset($thisItemMeta['layout']) && strlen(trim($thisItemMeta['layout'])) > 0){
	$layoutval=$thisItemMeta['layout'];
}else{
	$layoutval='';
}
echo $this->Form->input('layout',['label'=>'Layout','value'=>$layoutval]);





if(isset($thisItemMeta['cl-tops']) && strlen(trim($thisItemMeta['cl-tops'])) > 0){
	$cltopsval=$thisItemMeta['cl-tops'];
}else{
	$cltopsval='';
}
echo $this->Form->input('cl-tops',['label'=>'CL Top','value'=>$cltopsval]);
	



if(isset($thisItemMeta['cl-drops']) && strlen(trim($thisItemMeta['cl-drops'])) > 0){
	$cldropsval=$thisItemMeta['cl-drops'];
}else{
	$cldropsval='';
}
echo $this->Form->input('cl-drops',['label'=>'CL Drop','value'=>$cldropsval]);
	





if(isset($thisItemMeta['cl']) && strlen(trim($thisItemMeta['cl'])) > 0){
	$clval=$thisItemMeta['cl'];
}else{
	$clval='';
}
echo $this->Form->input('cl',['type'=>'hidden','style'=>'width:58% !important;','label'=>'CL','value'=>$clval]);
	
	

if(isset($thisItemMeta['total-widths']) && strlen(trim($thisItemMeta['total-widths'])) > 0){
	$totalwidthsval=$thisItemMeta['total-widths'];
}else{
	$totalwidthsval='';
}
echo $this->Form->input('total-widths',['label'=>'Total widths','value'=>$totalwidthsval]);
	
	
if(isset($thisItemMeta['yds-per-unit']) && strlen(trim($thisItemMeta['yds-per-unit'])) > 0){
	$ydspbsval=$thisItemMeta['yds-per-unit'];
}else{
	$ydspbsval='';
}
echo $this->Form->input('yds-per-unit',['label'=>'Yds p/BS','readonly'=>true,'value'=>$ydspbsval]);
	
	
if(isset($thisItemMeta['total-yds']) && strlen(trim($thisItemMeta['total-yds'])) > 0){
	$totalydsval=$thisItemMeta['total-yds'];
}else{
	$totalydsval='';
}
echo $this->Form->input('total-yds',['label'=>'Total Yards','value'=>$totalydsval]);
	
	
if(isset($thisItemMeta['fabric-cost']) && strlen(trim($thisItemMeta['fabric-cost'])) > 0){
	$fabriccostval=number_format($thisItemMeta['fabric-cost'],2,'.','');
}else{
	$fabriccostval='0.00';
}
echo $this->Form->input('fabric-cost',['label'=>'Fabric Cost','type'=>'number','step'=>'any','min'=>0,'value'=>$fabriccostval]);
	
	

if(isset($thisItemMeta['backing-quilting-cost']) && strlen(trim($thisItemMeta['backing-quilting-cost'])) > 0){
	$backingquiltingcostval=number_format($thisItemMeta['backing-quilting-cost'],2,'.','');
}else{
	$backingquiltingcostval='0.00';
}
echo $this->Form->input('backing-quilting-cost',['label'=>'Backing/Quilting Cost','type'=>'number','step'=>'any','min'=>0,'value'=>$backingquiltingcostval]);
	
	
	
if(isset($thisItemMeta['cost']) && strlen(trim($thisItemMeta['cost'])) > 0){
	$costval=number_format($thisItemMeta['cost'],2,'.','');
}else{
	$costval='0.00';
}
echo $this->Form->input('cost',['label'=>false,'type'=>'hidden','value'=>$costval]);

	

if(isset($thisItemMeta['price']) && strlen(trim($thisItemMeta['price'])) > 0){
	$priceval=number_format($thisItemMeta['price'],2,'.','');
}else{
	$priceval='0.00';
}
echo $this->Form->input('price',['label'=>'Base Price','type'=>'number','step'=>'any','min'=>0,'value'=>$priceval]);
?>



<?php
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
</select>
</div>

<div class="input checkbox"><label>Installation Surcharge?</label><input type="checkbox" name="install_surcharge" id="install_surcharge" value="yes" /></div>


<div class="input number"><label>ADD (%)</label> <input type="number" step="any" name="add_surcharge" id="add_surcharge" value="0" /></div>


<div class="input adjustedtotal"><label>Adjusted Price</label> <input type="number" step="any" readonly="readonly" value="" id="adjusted-price" name="adjusted-price" /></div>


<div class="input extendedprice"><label>Extended Price</label> <input type="number" step="any" readonly="readonly" value="" id="extended-price" name="extended-price" /></div>
<?php } ?>



<?php
if(isset($thisItemMeta['bs_calculated_weight']) && strlen(trim($thisItemMeta['bs_calculated_weight'])) >0){
	$bsweightval=floatval($thisItemMeta['bs_calculated_weight']);
}else{
	$bsweightval='';
}
echo $this->Form->input('bs_calculated_weight',['label'=>'Tot Calc Weight','readonly'=>true,'type'=>'number','step'=>'any','min'=>0,'value'=>$bsweightval]);

?>


<div class="input image"><label for="layoutimg">Layout Image</label><div id="layoutimgwrap"></div></div>

</div>
<div class="clear"></div>


<div class="clear"></div>
<?php
echo "<div class=\"calculatebutton\">";
echo $this->Form->button('Recalculate',['type'=>'button']);
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

?><br><Br><br><Br>

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

echo "var labor_rules=";
echo json_encode(array(
	"narrow_com"=>$settings['narrow_goods_com_bedspread_labor'],
	"narrow_mom"=>$settings['narrow_goods_mom_bedspread_labor'],
	"wide_com"=>$settings['wide_goods_com_bedspread_labor'],
	"wide_mom"=>$settings['wide_goods_mom_bedspread_labor']
	));
echo ";\n\n";
?>

function doCalculation(){
	
	console.clear();
	var qty=parseFloat($('#qty').val());
	var fab_w=parseFloat($('#fabric-width').val());


	var tot_yds = parseFloat($('#total-yds').val());


	var yds_pbs = (tot_yds / qty);

	$('#yds-per-unit').val(yds_pbs);


	if($('#quilted').is(':checked')){
		var adj_fab_w=(fab_w-<?php echo $settings['quilting_shrinkage']; ?>);
	}else{
		var adj_fab_w=fab_w;
	}

	$('#adjusted-fabric-width').val(adj_fab_w);




	if($('select[name=style]').val()=="Throw"){
		var style=1;
		var style_l='Throw';
	}else{
		var style=0;
		var style_l='Fitted';
	}



	if($('#quilted').is(':checked')){
		var quilted=1;
		//overhead waste percentage
		var quilt_l='Quilted';
		var oh_l='9%';
	}else{
		var quilted=0;
		//overhead waste percentage
		var quilt_l='Unquilted';
		var oh_l='5%';
	}







		if(quilted == 1){
			if(style==1){
				//quilted throw...
				if($('#railroaded').is(':checked')){
					var difficultybase=parseFloat(<?php echo $settings['quilted_bs_calc_diff_rr_throw']; ?>);
				}else{
					var difficultybase=parseFloat(<?php echo $settings['quilted_bs_calc_diff_utr_throw']; ?>);
				}
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_throw']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['unquilted_bs_calc_weight_throw']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_throw']; ?>) * weightbase);
			}else if(style==0){
				//quilted fitted
				if($('#railroaded').is(':checked')){
					var difficultybase=parseFloat(<?php echo $settings['quilted_bs_calc_diff_rr_fitted']; ?>);
				}else{
					var difficultybase=parseFloat(<?php echo $settings['quilted_bs_calc_diff_utr_fitted']; ?>);
				}
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_fitted']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['quilted_bs_calc_weight_fitted']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_fitted']; ?>) * weightbase);
			}
		}else{
			if(style==1){
				//unquilted throw
				if($('#railroaded').is(':checked')){
					var difficultybase=parseFloat(<?php echo $settings['unquilted_bs_calc_diff_rr_throw']; ?>);
				}else{
					var difficultybase=parseFloat(<?php echo $settings['unquilted_bs_calc_diff_utr_throw']; ?>);
				}
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_throw']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['unquilted_bs_calc_weight_throw']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_throw']; ?>) * weightbase);
			}else if(style==0){
				//unquilted fitted
				if($('#railroaded').is(':checked')){
					var difficultybase=parseFloat(<?php echo $settings['unquilted_bs_calc_diff_rr_fitted']; ?>);
				}else{
					var difficultybase=parseFloat(<?php echo $settings['unquilted_bs_calc_diff_utr_fitted']; ?>);
				}
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_fitted']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['unquilted_bs_calc_weight_fitted']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_fitted']; ?>) * weightbase);
			}
		}
		console.log('DIFFICULTY BASE = '+difficultybase);
		console.log('DIFFICULTY (ADJUSTED) = '+difficulty );
		difficulty=(difficulty * qty);
		$('#difficulty-rating').val(roundToTwo(difficulty).toFixed(2));

		console.log('WEIGHT BASE = '+weightbase);
		console.log('WEIGHT (ADJUSTED) = '+weight+' ('+weightbase+' * <?php echo $fabricData['bs_weight_fitted']; ?>)');
		weight=(weight*qty);
		$('#bs-calculated-weight').val(roundToTwo(weight).toFixed(2));







	$('#waste-overhead').val(oh_l);




		var l = parseFloat($('#length').val());
		var w = parseFloat($('#width').val());
		var xtra=parseFloat($('#extra-inches-seam-hems').val());


		<?php if(intval($quoteID) == 0){ ?>
		if($('#custom-backing-quilting-price-per-yd').val() != '' && parseFloat($('#custom-backing-quilting-price-per-yd').val()) > 0.00){
			var back_ppy = parseFloat($('#custom-backing-quilting-price-per-yd').val());
		}else{
			var back_ppy = parseFloat($('#backing-quilting-price-per-yd').val());
		}
		<?php }else{ ?>
		var back_ppy = parseFloat($('#backing-quilting-price-per-yd').val());
		<?php } ?>
		

		<?php if(intval($quoteID) == 0){ ?>
		if($('#custom-base-labor').val() != '' && parseFloat($('#custom-base-labor').val()) > 0.00){
			var lbr = parseFloat($('#custom-base-labor').val());
		}else{
			var lbr = parseFloat($('#labor-per-bs').val());	
		}
		<?php }else{ ?>
		var lbr=parseFloat($('#labor-per-bs').val());
		<?php } ?>

	







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





	var lay = parseFloat($('#layout').val());

	var laborValue=0;
		var laborsurcharge=0;
		if(lay==1){

			if($('#com-fabric').is(':checked')){
				laborValue=labor_rules['wide_com'];
				$('#backing-quilting-price-per-yd').val('<?php echo $settings['bedspread_quilting_com_per_yard']; ?>');
				console.log('NO SEAMS, WIDE GOODS NO SEAMS COM LABOR Override');
			}else{
				laborValue=labor_rules['wide_mom'];
				$('#backing-quilting-price-per-yd').val('<?php echo $settings['bedspread_quilting_mom_per_yard']; ?>');
				console.log('NO SEAMS, WIDE GOODS NO SEAMS MOM LABOR Override');
			}
		}else{
			if($('#com-fabric').is(':checked')){
				laborValue=labor_rules['narrow_com'];
				$('#backing-quilting-price-per-yd').val('<?php echo $settings['bedspread_quilting_com_per_yard']; ?>');
			}else{
				laborValue=labor_rules['narrow_mom'];
				$('#backing-quilting-price-per-yd').val('<?php echo $settings['bedspread_quilting_mom_per_yard']; ?>');
			}
		}




		//determine if a surcharge needs to be added for mattress size (labor surcharge)
		if(parseFloat($('#custom-top-width-mattress-w').val()) >= 54 && parseFloat($('#custom-top-width-mattress-w').val()) < 60){
			//full size
			laborValue = (parseFloat(laborValue) + parseFloat(<?php echo $settings['full_size_mattress_surcharge']; ?>));
			console.log('$<?php echo $settings['full_size_mattress_surcharge']; ?> added to LABOR rate for FULL SIZE mattress size');
			var laborsurcharge=1;
		}else if(parseFloat($('#custom-top-width-mattress-w').val()) >= 60 && parseFloat($('#custom-top-width-mattress-w').val()) < 76){
			//queen size
			laborValue = (parseFloat(laborValue) + parseFloat(<?php echo $settings['queen_size_mattress_surcharge']; ?>));
			console.log('$<?php echo $settings['queen_size_mattress_surcharge']; ?> added to LABOR rate for QUEEN SIZE mattress size');
			var laborsurcharge=2;
		}else if(parseFloat($('#custom-top-width-mattress-w').val()) >= 76){
			//king size
			laborValue = (parseFloat(laborValue) + parseFloat(<?php echo $settings['king_size_mattress_surcharge']; ?>));
			console.log('$<?php echo $settings['king_size_mattress_surcharge']; ?> added to LABOR rate for KING SIZE mattress size');
			var laborsurcharge=3;
		}else{
			//no change
		}


		$('#labor-per-bs').val(laborValue);




		////////////SET IMAGE////////////
		if(lay == 1){
			var lay_img = "/img/bedspreadlayouts/1_0.gif";
		}else if(lay == 1.33){
			var lay_img = "/img/bedspreadlayouts/1_33.gif";
		}else if(lay == 1.66){
			var lay_img = "/img/bedspreadlayouts/1_66.gif";
		}else if(lay == 2){
			var lay_img = "/img/bedspreadlayouts/2_0.gif";
		}else if(lay == 0){
			var lay_img = "/img/bedspreadlayouts/error.gif";
		}


        $('#layoutimgwrap').html('<img src="'+lay_img+'" />');






	//inbound freight calculation
	var ibfrtpy=0;
	if(fab_w >= 54 && fab_w <= 72){
		if(tot_yds < 25){
			ibfrtpy=roundToTwo((25.00/tot_yds)).toFixed(2);
		}else if(tot_yds >= 25 && tot_yds <= 59.99){
			ibfrtpy=1.20;
		}else if(tot_yds >= 60 && tot_yds <= 249.99){
			ibfrtpy=0.65;
		}else if(tot_yds >= 250){
			ibfrtpy=0.35;
		}
	}else if(fab_w >= 73 && fab_w <= 130){
		if(tot_yds < 25){
			ibfrtpy=roundToTwo((25.00/qty)).toFixed(2);
		}else if(tot_yds >= 25 && tot_yds <= 59.99){
			ibfrtpy=1.75;
		}else if(tot_yds >= 60 && tot_yds <= 249.99){
			ibfrtpy=0.95;
		}else if(tot_yds >= 250){
			ibfrtpy=0.50;
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

	console.log('fabricMarkup = '+fabricMarkup);

	var markedupFabric=((parseFloat(fab_ppy)+parseFloat(ibfrtpy))*parseFloat(fabricMarkup));

	console.log('markedupfabric = '+markedupFabric);

	

	if($('#com-fabric').is(':checked')){
		$('#fabric-cost').val('0.00');
		$('#fabric-price').val('0.00');
		$('select[name=fabric-cost-per-yard]').parent().hide('fast');
		$('#fabricmarkupwrap,#inboundfreightwrap').hide('fast');
		var fab_cost=0;
		var totfab_cost=0;
	}else{
		var fab_cost=(parseFloat(yds_pbs)*markedupFabric);
		console.log('fab_cost = '+fab_cost);
		console.log('fab_cost_math2 = ('+parseFloat(yds_pbs)+' * '+markedupFabric+')');
		var totfab_cost = (fab_cost * qty);
	}




	/********************FABRIC COST*************************
		var fab_cost = (fab_ppy * yds_pbs);
		
		//TOTAL FAB COST PER LINE//
		var totfab_cost = (fab_cost * qty);

		/********************BACKING COST************************/
		if(quilted == 0){
			var back_cost1 = 0;
		}else{
			var back_cost1 = back_ppy;
		}

		var back_cost = (back_cost1 * yds_pbs);
		var totback_cost = (back_cost * qty);		

		console.log('back_cost = ('+back_cost1+' * '+yds_pbs+') = '+back_cost);
		
		/****************************BEDSPREAD COST****************************************/
		var cost_pbs = (lbr + fab_cost + back_cost);
		
		//TOTAL FAB COST PER LINE//
		var totcost_bs = (cost_pbs * qty);



	$('#backing-quilting-cost').val(roundToTwo(back_cost).toFixed(2));
	$('#fabric-cost').val(roundToTwo(fab_cost).toFixed(2));
	
	$('#cost').val(roundToTwo(cost_pbs).toFixed(2));
	//var priceval=roundToTwo((cost_pbs*fabricMarkup)).toFixed(2);
	$('#price').val(roundToTwo(cost_pbs).toFixed(2));



	//adjustments?
	<?php
	if(intval($quoteID) == 0){
	echo "var tierAdjustments={'1':'".$settings['tier_1_premium']."','2':'".$settings['tier_2_premium']."','3':'".$settings['tier_3_premium']."','4':'".$settings['tier_4_premium']."','5':'".$settings['tier_5_premium']."','6':'".$settings['tier_6_premium']."','7':'".$settings['tier_7_premium']."','8':'".$settings['tier_8_premium']."'};
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
	

<?php
if($userData['scroll_calcs'] == 1){
?>

function correctColHeights(){
	$('#calcformleft,#resultsblock').height(($(window).height()-240));
}

<?php } ?>

$(function(){

	$('input, :input').attr('autocomplete', 'off');
	
	$('input[type=text],input[type=number]').focus(function(){
		$(this).select();
	});
	

	$('#cl-tops,#cl-drops').change(function(){
		$('#cl').val($('#cl-tops').val()+'CL (tops) / '+$('#cl-drops').val()+'CL (drops)');
	});

	$('#cl-tops,#cl-drops').keyup(function(){
		$('#cl').val($('#cl-tops').val()+'CL (tops) / '+$('#cl-drops').val()+'CL (drops)');
	});
	

	$('#railroaded').click(function(){
		if($(this).is(':checked')){
			$('#assumed-drop-width').parent().hide('fast');
			$('#top-cut').parent().hide('fast');
			$('#drop').parent().hide('fast');
		}else{
			$('#assumed-drop-width').parent().show('fast');
			$('#top-cut').parent().show('fast');
			$('#drop').parent().show('fast');
		}
	});


	$('#railroaded').change(function(){
		if($(this).is(':checked')){
			$('#assumed-drop-width').hide('fast');
		}else{
			$('#assumed-drop-width').show('fast');
		}
	});
	
<?php
if($userData['scroll_calcs'] == 1){
?>
correctColHeights();
setInterval('correctColHeights()',500);
<?php } ?>


	$('input,select').keyup(function(){
		if(canCalculate()){	
			$('#cannotcalculate').hide();
			doCalculation(); 
		}else{
			$('#cannotcalculate').show();
		}
	});
	

	$('#quilted').change(function(){
		
		if($('#quilted').is(':checked')){
			if(parseFloat($('#fabric-width').val()) > 65 || $('#fabric-width').val()==''){
				$('#fabric-width').val('65');
			}
			$('#quiltpatternwrap').show('fast');
			$('#matchingthreadwrap').show('fast');
			$('#backing-quilting-price-per-yd').parent().show('fast');
		}else{
			$('#fabric-width').val('<?php echo $fabricData['fabric_width']; ?>');
			$('#quiltpatternwrap').hide('fast');
			$('#matchingthreadwrap').hide('fast');
			$('#backing-quilting-price-per-yd').parent().hide('fast');
		}
	});

	$('#quilted').click(function(){
		
		if($('#quilted').is(':checked')){
			if(parseFloat($('#fabric-width').val()) > 65 || $('#fabric-width').val()==''){
				$('#fabric-width').val('65');
			}
			$('#quiltpatternwrap').show('fast');
			$('#matching-thread').parent().show('fast');
			$('#backing-quilting-price-per-yd').parent().show('fast');
		}else{
			$('#fabric-width').val('<?php echo $fabricData['fabric_width']; ?>');
			$('#quiltpatternwrap').hide('fast');
			$('#matching-thread').parent().hide('fast');
			$('#backing-quilting-price-per-yd').parent().hide('fast');
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
	
	
	$('#com-fabric').change(function(){
		if($(this).prop('checked')){
			$('#fab-price-per-yd').val('0.00');
			$('.calculatebutton button').trigger('click');
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated');
		}
	});

	$('#com-fabric').click(function(){
		if($(this).prop('checked')){
			$('#fab-price-per-yd').val('0.00');
			$('.calculatebutton button').trigger('click');
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated');
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





	if(canCalculate()){	
		$('#cannotcalculate').hide();
		doCalculation(); 
	}else{
		$('#cannotcalculate').show();
	}
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



	if($('select[name=style]').val()=='0' || $('select[name=style]').val()==''){
		//console.log('Cannot calculate without a Style value');
		$('select[name=style]').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('select[name=style]').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	

	
	if($('#width').val()=='0' || $('#width').val()==''){
		//console.log('Cannot calculate without a Cut Width value');
		$('#width').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#width').removeClass('notvalid').removeClass('validated').addClass('validated');
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
	if(!$('#com-fabric').is(':checked')){
		if($('#custom-fabric-cost-per-yard').val() == '' || parseFloat($('#custom-fabric-cost-per-yard').val()) == 0){
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated').addClass('notvalid');
			errorcount++;
		}else{
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated').addClass('validated');
		}
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
	if(parseFloat($('#labor-per-bs').val()) == 0 || $('#labor-per-bs').val() == ''){
		//console.log('Cannot calculate without a Labor Per Linear Foot value');
		$('#labor-per-bs').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#labor-per-bs').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
*/	
	
	if($('select[name=fabric-price-per-yard]').val() == '0'){
		//console.log('Cannot calculate without a Fabric Price (per yard) selection');
		errorcount++;
	}


	if(!$('#railroaded').prop('checked')){
		if($('#top-cut').val() == ''){
			$('#top-cut').removeClass('notvalid').removeClass('validated').addClass('notvalid');
			errorcount++;
		}else{
			$('#top-cut').removeClass('notvalid').removeClass('validated').addClass('validated');
		}


		if($('#drop').val() == ''){
			$('#drop').removeClass('notvalid').removeClass('validated').addClass('notvalid');
			errorcount++;
		}else{
			$('#drop').removeClass('notvalid').removeClass('validated').addClass('validated');
		}

		$('#top-cut').parent().show('fast');
		$('#drop').parent().show('fast');

	}else{
		$('#top-cut').parent().hide('fast');
		$('#drop').parent().hide('fast');
		
		$('#top-cut').removeClass('notvalid').removeClass('validated');
		$('#drop').removeClass('notvalid').removeClass('validated');
		
	}


	if($('#layout').val() == ''){
		$('#layout').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#layout').removeClass('notvalid').removeClass('validated').addClass('validated');
	}


	if($('#cl-tops').val() == ''){
		$('#cl-tops').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#cl-tops').removeClass('notvalid').removeClass('validated').addClass('validated');
	}


	if(!$('#railroaded').prop('checked')){

		if($('#cl-drops').val() == ''){
			$('#cl-drops').removeClass('notvalid').removeClass('validated').addClass('notvalid');
			errorcount++;
		}else{
			$('#cl-drops').removeClass('notvalid').removeClass('validated').addClass('validated');
		}

		$('#cl-drops').parent().show('fast');

	}else{

		$('#cl-drops').parent().hide('fast');
		$('#cl-drops').removeClass('notvalid').removeClass('validated');
	}



	if($('#total-widths').val() == ''){
		$('#total-widths').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#total-widths').removeClass('notvalid').removeClass('validated').addClass('validated');
	}



	if($('#total-yds').val() == ''){
		$('#total-yds').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#total-yds').removeClass('notvalid').removeClass('validated').addClass('validated');
	}


	
	if(errorcount > 0){
		return false;
	}else{
		return true;
	}
}
</script>
<br><br><Br><Br>
<div id="explainmath"></div>