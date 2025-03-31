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

#fabricname{ width:43%; float:right; }
#fabriccolor{ width:43%; float:right; clear:right; }
#changefabricbutton{ background:#DDD !important; color:#000 !important; padding:2px 2px 2px 2px !important; border:0; font-size:12px !important; float:right; clear:right; }

/*PPSA-33 */
#fabric-cost-per-yard-custom{ float:none !important; display:block; width:100% !important; margin:0 0 0 0 !important; }
#fabric-cost-per-yard-custom-value{ float:none !important; display:inline-block; width:100% !important; margin:4px 0 0 0 !important; }

#fabriccostwrap label{ width:55% !important; }
#fabriccostwrap small{ font-style:italic; font-size:11px; color:#ff0000;     display: block; width: 30%; float: right; }
	
#fabriccostwrap-custom-value{ float:none !important; display:inline-block; width:100% !important; margin:4px 0 0 0 !important; }
#fabriccostwrap{ float:none !important; display:block; width:100% !important; margin:0 0 0 0 !important; }
#fabriccostwrapinnerwrap{ float:right; width:28%; text-align: right }
/*PPSA-33 */

</style>
<script>
function changefabricmodal(){
    $.fancybox({
        'type':'iframe',
        /* PPSASCRUM-100: start */
        // 'href':'/quotes/changecalcitemfabric/'+$('#fabricid').val(),
        'href':'/quotes/changecalcitemfabric/'+$('#fabricid').val()+'/bedspread',
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
//echo "<pre>"; print_r($allFabrics); echo "</pre><hr>";

echo "<div id=\"scrolloptions\">Calculator Scrolling Columns: ";
if($userData['scroll_calcs'] == 1){
	echo "<b>Enabled</b> | <a href=\"/users/setscrolloption/0\">Disable</a>";
}else{
	echo "<b>Disabled</b> | <a href=\"/users/setscrolloption/1\">Enable</a>";
}
echo "</div>";


if(intval($quoteID) > 0){
	if(isset($isedit) && $isedit=='1'){
		echo "<h1>Edit Calculated Line Item</h1>";
	}else{
		echo "<h1>Add Calculated Line Item</h1>";
	}
}else{
	echo "<h1>Standalone BS Calculator</h1>";
}



echo "<hr style=\"clear:both;\" />";


echo $this->Form->create();
echo "<div id=\"calcformleft\">";
echo "<h2>Bedspread</h2>";
echo "<div class=\"input\">
    <label style=\"width:44% !important;\">Fabric/Pattern</label>
    <div id=\"fabricname\">".$fabricData['fabric_name']."</div>
</div>
<div class=\"input\">
    <label style=\"width:44% !important;\">Color</label>
    <div id=\"fabriccolor\">".$fabricData['color']."</div>";
    /* PPSASCRUM-371: start */
    // if($ordermode != 'workorder' && isset($isedit) && $isedit=='1')  echo "<button id=\"changefabricbutton\" type=\"button\" onclick=\"changefabricmodal()\">Change Fabric/Color</button>";else '';
    if ($ordermode != 'workorder') {
        echo "<button id=\"changefabricbutton\" type=\"button\" onclick=\"changefabricmodal()\">Change Fabric/Color</button>";
    }
    /* PPSASCRUM-371: end */
echo "</div>";

echo $this->Form->input('fabricid',['type'=>'hidden','value'=>$fabricData['id']]);

echo $this->Form->input('process',['type'=>'hidden','value'=>'insertlineitem']);
echo $this->Form->input('calculator-used',['type'=>'hidden','value'=>'bedspread']);

//echo $this->Form->input('fabricid',['type'=>'hidden','value'=>$fabricid]);


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
echo $this->Form->input('com-fabric',['type'=>'checkbox','label'=>'COM Fabric','checked'=>$comChecked]);






if(isset($thisItemMeta['qty']) && is_numeric($thisItemMeta['qty'])){
	$qtyval=$thisItemMeta['qty'];
}else{
	$qtyval=1;
}
//echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','autocomplete'=>'off','min'=>0,'value'=>$qtyval]);&& isset($thisLineItem['qty'])
if($ordermode == 'workorder' && isset($thisItemMeta['qty']))
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

if(isset($thisItemMeta['railroaded'])){
	if($thisItemMeta['railroaded']=='1'){
		$railroadedChecked=true;
	}else{
		$railroadedChecked=false;
	}
}else{
	if($fabricData['railroaded']=='1'){
		$railroadedChecked=true;
	}else{
		$railroadedChecked=false;
	}
}
/**PPSASCRUM-24 end **/

echo $this->Form->input('railroaded',['type'=>'checkbox','label'=>'Railroaded','checked'=>$railroadedChecked]);






if(isset($thisItemMeta['quilted']) && $thisItemMeta['quilted']=='1'){
	$quiltedChecked=true;
}else{
	$quiltedChecked=false;
}
echo $this->Form->input('quilted',['type'=>'checkbox','label'=>'Quilted','checked'=>$quiltedChecked]);


echo "<div id=\"quiltpatternwrap\" class=\"input select\"";
if(!$quiltedChecked){ echo " style=\"display:none;\""; }
//PPSASCRUM-88 Start
echo ">

<label>Quilting Pattern</label>";
echo "<select name=\"quilting-pattern\" id=\"quilting-pattern\" disabled =true>";
//echo $thisItemMeta['quilting-pattern']."---->";
if(isset($thisItemMeta['quilting-pattern'])){

    $quiltingPatterns=explode("|",$settings['quilting_patterns']);
    foreach($quiltingPatterns as $pattern){
    	echo "<option value=\"".htmlspecialchars($pattern)."\"";
    	if(isset($thisItemMeta['quilting-pattern']) && htmlspecialchars_decode($thisItemMeta['quilting-pattern']) == $pattern){
    		echo " selected";
    	}
    	echo ">".$pattern."</option>\n";
    }
}else {
 
        $quiltingPatterns=explode("|",$settings['quilting_patterns']);
        foreach($quiltingPatterns as $pattern){
    	echo "<option value=\"".htmlspecialchars($pattern)."\"";
    	if("Double Onion" == $pattern){
    		echo " selected";
    	}
    	echo ">".$pattern."</option>\n";
         }
}
echo "</select>";
//PPSASCRUM-88 End
echo "</div>";





if(isset($thisItemMeta['style']) && strlen(trim($thisItemMeta['style'])) >0){
	$styleval=array('value'=>$thisItemMeta['style'],'required'=>true);
}else{
	$styleval=array('empty'=>'--Select One--','required'=>true);
}
echo "<div class=\"input\">";
echo $this->Form->label('Style');
echo $this->Form->select('style',['Throw'=>'Throw','Fitted'=>'Fitted'],$styleval);
echo "</div>";





//PPSASCRUM-88 Start
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
echo $this->Form->input('matching-thread',['type'=>'checkbox','label'=>'Matching Thread?','checked'=>$matchingthreadChecked,'disabled'=>true]);
echo "</div>";
//PPSASCRUM-88 End



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
echo $this->Form->input('labor-per-bs',['type'=>'number','value'=>$laborperbsval,'label'=>'Labor per BS','readonly'=>true,'step'=>'any','min'=>0]);



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
	
	if($fabricid=='custom'){
		$extrainchesseamhemsval=$settings['default_extra_inches_value'];
	}else{
		if($fabricData['railroaded'] == '1'){
			$extrainchesseamhemsval=$fabricData['bs_hem_multiplier_rr'];
		}else{
			$extrainchesseamhemsval=$fabricData['bs_hem_multiplier_utr'];
		}
	}

}
echo $this->Form->input('extra-inches-seam-hems',['type'=>'number','step'=>'any','min'=>'0','value'=>$extrainchesseamhemsval,'label'=>'Extra Inches (seam/hems)']);

echo "</fieldset>";




echo "</div>";
?>

<div id="resultsblock">
<h2>Calculator Results</h2>
<div id="cannotcalculate">Cannot calculate. Missing information.</div>
<?php
	
if(isset($thisItemMeta['waste-overhead']) && strlen(trim($thisItemMeta['waste-overhead'])) > 0){
	$wasteoverheadval=$thisItemMeta['waste-overhead'];
}else{
	$wasteoverheadval='';
}
echo $this->Form->input('waste-overhead',['label'=>'Waste/Overhead','readonly'=>true,'value'=>$wasteoverheadval]);
	


echo $this->Form->input('top-widths',['type'=>'hidden','value'=>'0']);
echo $this->Form->input('drop-widths',['type'=>'hidden','value'=>'0']);

	
if(isset($thisItemMeta['adjusted-fabric-width']) && strlen(trim($thisItemMeta['adjusted-fabric-width'])) > 0){
	$adjustedfabricwidthval=$thisItemMeta['adjusted-fabric-width'];
}else{
	$adjustedfabricwidthval='';
}
echo $this->Form->input('adjusted-fabric-width',['label'=>'Adjusted Fab Width','readonly'=>true,'value'=>$adjustedfabricwidthval]);
	

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
echo $this->Form->input('top',['label'=>'Calculated Mattress Width','readonly'=>true,'value'=>$topval]);
*/

/*
if(isset($thisItemMeta['mattress-width-status']) && strlen(trim($thisItemMeta['mattress-width-status'])) >0){
	$mattresswidthstatusval=$thisItemMeta['mattress-width-status'];
}else{
	$mattresswidthstatusval='';
}
echo $this->Form->input('mattress-width-status',['style'=>'width:91% !important;','label'=>'Mattress Width Status','readonly'=>true,'value'=>$mattresswidthstatusval]);

*/



if(isset($thisItemMeta['top-cut']) && strlen(trim($thisItemMeta['top-cut'])) > 0){
	$topcutval=$thisItemMeta['top-cut'];
}else{
	$topcutval='';
}
echo $this->Form->input('top-cut',['label'=>'Top (Cut)','readonly'=>true,'value'=>$topcutval]);




if(isset($thisItemMeta['drop']) && strlen(trim($thisItemMeta['drop'])) > 0){
	$dropval=$thisItemMeta['drop'];
}else{
	$dropval='';
}
echo $this->Form->input('drop',['label'=>'Drop (Cut)','readonly'=>true,'value'=>$dropval]);





if(isset($thisItemMeta['layout']) && strlen(trim($thisItemMeta['layout'])) > 0){
	$layoutval=$thisItemMeta['layout'];
}else{
	$layoutval='';
}
echo $this->Form->input('layout',['label'=>'Layout','readonly'=>true,'value'=>$layoutval]);


if(isset($thisItemMeta['layout-status']) && strlen(trim($thisItemMeta['layout-status'])) > 0){
	$layoutstatusval=$thisItemMeta['layout-status'];
}else{
	$layoutstatusval='';
}
echo $this->Form->input('layout-status',['style'=>'width:91% !important;','label'=>'Layout Status','readonly'=>true,'value'=>$layoutstatusval]);
	
	

if(isset($thisItemMeta['cluster-status']) && strlen(trim($thisItemMeta['cluster-status'])) > 0){
	$clusterstatusval=$thisItemMeta['cluster-status'];
}else{
	$clusterstatusval='';
}
echo $this->Form->input('cluster-status',['type'=>'textarea','style'=>'width:91% !important;','label'=>'Cluster Status','readonly'=>true,'value'=>$clusterstatusval]);
	


if(isset($thisItemMeta['cl']) && strlen(trim($thisItemMeta['cl'])) > 0){
	$clval=$thisItemMeta['cl'];
}else{
	$clval='';
}
echo $this->Form->input('cl',['style'=>'width:58% !important;','label'=>'CL','readonly'=>true,'value'=>$clval]);
	
	

if(isset($thisItemMeta['total-widths']) && strlen(trim($thisItemMeta['total-widths'])) > 0){
	$totalwidthsval=$thisItemMeta['total-widths'];
}else{
	$totalwidthsval='';
}
echo $this->Form->input('total-widths',['label'=>'Total widths','readonly'=>true,'value'=>$totalwidthsval]);
	
	
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
echo $this->Form->input('total-yds',['label'=>'Total Yards','readonly'=>true,'value'=>$totalydsval]);
	
	
if(isset($thisItemMeta['fabric-cost']) && strlen(trim($thisItemMeta['fabric-cost'])) > 0){
	$fabriccostval=number_format($thisItemMeta['fabric-cost'],2,'.','');
}else{
	$fabriccostval='0.00';
}
echo $this->Form->input('fabric-cost',['label'=>'Fabric Cost','readonly'=>true,'type'=>'number','step'=>'any','min'=>0,'value'=>$fabriccostval]);
	
	

if(isset($thisItemMeta['backing-quilting-cost']) && strlen(trim($thisItemMeta['backing-quilting-cost'])) > 0){
	$backingquiltingcostval=number_format($thisItemMeta['backing-quilting-cost'],2,'.','');
}else{
	$backingquiltingcostval='0.00';
}
echo $this->Form->input('backing-quilting-cost',['label'=>'Backing/Quilting Cost','readonly'=>true,'type'=>'number','step'=>'any','min'=>0,'value'=>$backingquiltingcostval]);
	
	
	
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
echo $this->Form->input('price',['label'=>'Base Price','readonly'=>true,'type'=>'number','step'=>'any','min'=>0,'value'=>$priceval]);
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
		/* PPSASCRUM-317: start */
 		// echo $this->Form->button('Save Changes',['type'=>'submit','class'=>'SubmitButton']);
		echo $this->Form->button('Save Changes',['type'=>'submit','class'=>'SubmitButton','onClick'=>'return checkSubmission();']);
		/* PPSASCRUM-317: end */
	}else{
	    /* PPSASCRUM-317: start */
 		// echo $this->Form->button('Add To Quote',['type'=>'submit','class'=>'SubmitButton']);
		echo $this->Form->button('Add To Quote',['type'=>'submit','class'=>'SubmitButton','onClick'=>'return checkSubmission();']);
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
	var qty=parseFloat($('#qty').val());
	var fab_w=parseFloat($('#fabric-width').val());

	if($('#quilted').is(':checked')){
		var adj_fab_w=(fab_w-<?php echo $settings['quilting_shrinkage']; ?>);
	}else{
		var adj_fab_w=fab_w;
	}

	$('#adjusted-fabric-width').val(adj_fab_w);

	console.clear();

	if($('#railroaded').is(':checked')){
		var rr=1;


        /* PPSASCRUM-318: start */
		// var laborValue=0;
		/* PPSASCRUM-318: end */
		var laborsurcharge=0;
		if($('#com-fabric').is(':checked')){
		    /* PPSASCRUM-318: start */
			// laborValue=labor_rules['wide_com'];
			/* PPSASCRUM-318: end */
			$('#backing-quilting-price-per-yd').val('<?php echo $settings['bedspread_quilting_com_per_yard']; ?>');
		}else{
			/* PPSASCRUM-318: start */
			// laborValue=labor_rules['wide_mom'];
			/* PPSASCRUM-318: end */
			$('#backing-quilting-price-per-yd').val('<?php echo $settings['bedspread_quilting_mom_per_yard']; ?>');
		}



		//determine if a surcharge needs to be added for mattress size (labor surcharge)
		if(parseFloat($('#custom-top-width-mattress-w').val()) >= 54 && parseFloat($('#custom-top-width-mattress-w').val()) < 60){
			//full size
			/* PPSASCRUM-318: start */
			// laborValue = (parseFloat(laborValue) + parseFloat(<?php //echo $settings['full_size_mattress_surcharge']; ?>));
			/* PPSASCRUM-318: end */
			console.log('$<?php //echo $settings['full_size_mattress_surcharge']; ?> added to LABOR rate for FULL SIZE mattress size');
			var laborsurcharge=1;
		}else if(parseFloat($('#custom-top-width-mattress-w').val()) >= 60 && parseFloat($('#custom-top-width-mattress-w').val()) < 76){
			//queen size
			/* PPSASCRUM-318: start */
			// laborValue = (parseFloat(laborValue) + parseFloat(<?php //echo $settings['queen_size_mattress_surcharge']; ?>));
			/* PPSASCRUM-318: end */
			console.log('$<?php //echo $settings['queen_size_mattress_surcharge']; ?> added to LABOR rate for QUEEN SIZE mattress size');
			var laborsurcharge=2;
		}else if(parseFloat($('#custom-top-width-mattress-w').val()) >= 76){
			//king size
			/* PPSASCRUM-318: start */
			// laborValue = (parseFloat(laborValue) + parseFloat(<?php //echo $settings['king_size_mattress_surcharge']; ?>));
			/* PPSASCRUM-318: end */
			console.log('$<?php //echo $settings['king_size_mattress_surcharge']; ?> added to LABOR rate for KING SIZE mattress size');
			var laborsurcharge=3;
		}else{
			//no change
		}


        /* PPSASCRUM-318: start */
		// $('#labor-per-bs').val(laborValue);
		/* PPSASCRUM-318: end */


		$('#verticalrepeatwrap').hide();
		$('#assumeddropwidthwrap').hide();
		$('#custom-top-width').parent().hide();
		$('#force-fitted-style-yds-accuracy').parent().parent().hide();
		$('#force-full-widths-ea-bs').parent().parent().hide();
		$('#force-full-widths-eol').parent().parent().hide();
		$('#top').parent().hide();
		$('#drop').parent().hide();
		$('#cluster-status').parent().hide();

        var qty=parseFloat($('#qty').val());		
		
		
		
		<?php if(intval($quoteID) == 0){ ?>
		if($('#custom-base-labor').val() != '' && parseFloat($('#custom-base-labor').val()) > 0.00){
			var lbr = parseFloat($('#custom-base-labor').val());
		}else{
			var lbr = parseFloat($('#labor-per-bs').val());	
		}
		<?php }else{ ?>
		var lbr=parseFloat($('#labor-per-bs').val());
		<?php } ?>




		
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
			var oh=1.09;
			var quilt_l='Quilted';
			var oh_l='9%';
		}else{
			var quilted=0;
			//overhead waste percentage
			var oh=1.05;
			var quilt_l='Unquilted';
			var oh_l='5%';
		}
		console.log('Overhead '+oh_l);
		


		if(quilted == 1){
			if(style==1){
				//quilted throw...
				var difficultybase=parseFloat(<?php echo $settings['quilted_bs_calc_diff_rr_throw']; ?>);
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_throw']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['unquilted_bs_calc_weight_throw']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_throw']; ?>) * weightbase);
			}else if(style==0){
				//quilted fitted
				var difficultybase=parseFloat(<?php echo $settings['quilted_bs_calc_diff_rr_fitted']; ?>);
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_fitted']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['quilted_bs_calc_weight_fitted']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_fitted']; ?>) * weightbase);
			}
		}else{
			if(style==1){
				//unquilted throw
				var difficultybase=parseFloat(<?php echo $settings['unquilted_bs_calc_diff_rr_throw']; ?>);
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_throw']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['unquilted_bs_calc_weight_throw']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_throw']; ?>) * weightbase);
			}else if(style==0){
				//unquilted fitted
				var difficultybase=parseFloat(<?php echo $settings['unquilted_bs_calc_diff_rr_fitted']; ?>);
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_fitted']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['unquilted_bs_calc_weight_fitted']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_fitted']; ?>) * weightbase);
			}
		}
		console.log('DIFFICULTY BASE = '+difficultybase);
		console.log('DIFFICULTY (ADJUSTED) = '+difficulty);
		difficulty=(difficulty * qty);
		$('#difficulty-rating').val(roundToTwo(difficulty).toFixed(2));

		console.log('WEIGHT BASE = '+weightbase);
		console.log('WEIGHT (ADJUSTED) = '+weight);
		weight=(weight*qty);
		$('#bs-calculated-weight').val(roundToTwo(weight).toFixed(2));


		if($('#com-fabric').is(':checked')){
			var mato=1;
			var mato_l='COM';
		}else{
			var mato=0;
			var mato_l='MOM';
		}
		
		
		if($('#force-distributed-rounded-yds').is(':checked')){
			var fry_dist=1;
		}else{
			var fry_dist=0;
		}
		
		
		if($('#force-rounded-yds-ea-bs').is(':checked')){
			var fry=1;
		}else{
			var fry=0;
		}
		

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
       
       
       /*******************FIND LAYOUT*************************/
        if((l+(2*xtra)) <= adj_fab_w){
			var lay=1;
			var lay_img="/img/bedspreadlayouts/1_0.gif";
			var tot_weol=qty;
		}else{
			var lay=0;
			var lay_img="/img/bedspreadlayouts/error.gif";
			var tot_weol=0;
		}
	
        $('#layoutimgwrap').html('<img src="'+lay_img+'" />');


		/******************CALCULATE CL*********************/
		var cl = (w + (2 * xtra));
		
		
		/*****************Calculate YDS****************/
		//step1: raw yds
		var yds1 = ((cl/36) * oh);
		
		//step2: if forced rounded yds ea BS
		var yds2 = Math.ceil(yds1);
		
		//step3: when forcing distributed rounded yds on the line 
		var yds3 = (Math.ceil((yds1 * qty)) / qty);
		
		//route the appropiate value to output
		if(fry == 1){
			var yds_pbs = yds2;	//step 3
			console.log('fry = 1, yds_pbs = '+yds2);
		}else if(fry_dist == 1){
			var yds_pbs = yds3;	//step 2
			console.log('fry_dist = 1, yds_pbs = '+yds3);
		}else{
			var yds_pbs = yds1;	//step 1
			console.log('fry = 0, fry_dist = 0, yds_pbs = '+yds1);
			console.log ('yds1 = ((('+cl+' * ('+tot_weol+' / '+qty+')) / 36) * '+oh+')');
		}


		//TOTAL YDS PER LINE//
		var tot_yds = (yds_pbs * qty);
			
			

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
				if(qty >0 && $('select[name=fabric-cost-per-yard]').val() != '' && tot_yds > 0){
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




		
		//inbound freight calculation
		var ibfrtpy=0;
		
		/* PPSASCRUM-317: start */
		/* if(fab_w >= 54 && fab_w <= 72){
			if(tot_yds < 25){
				ibfrtpy=roundToTwo((25.00/tot_yds)).toFixed(2);
			}else if(tot_yds >= 25 && tot_yds < 60){
				ibfrtpy=<//?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds25-59']; ?>;
			}else if(tot_yds >= 60 && tot_yds < 250){
				ibfrtpy=<//?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds60-249']; ?>;
			}else if(tot_yds >= 250){
				ibfrtpy=<//?php echo $settings['inbound_freight_multiplier_fabw54-72_totyds250plus']; ?>;
			}
		}else if(fab_w >= 73 && fab_w <= 130){
			if(tot_yds < 25){
				ibfrtpy=roundToTwo((25.00/tot_yds)).toFixed(2);
			}else if(tot_yds >= 25 && tot_yds < 60){
				ibfrtpy=<//?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds25-59']; ?>;
			}else if(tot_yds >= 60 && tot_yds < 250){
				ibfrtpy=<//?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds60-249']; ?>;
			}else if(tot_yds >= 250){
				ibfrtpy=<//?php echo $settings['inbound_freight_multiplier_fabw73-130_totyds250plus']; ?>;
			}
		} */
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
			
		

		if($('#fabric-markup-custom-value').val() != ''){
			var fabricMarkup=(1+(parseFloat($('#fabric-markup-custom-value').val())/100));
		}else{
			var fabricMarkup=(1+(parseFloat($('#fabric-markup').val())/100));
		}

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
			/*PPSA-33 start*/
			$('#fabric-markup-custom-value').parent().show('fast');
			$('select[name=fabric-cost-per-yard]').parent().show('fast');
			$('#inboundfreightwrap').show('fast');
			$('#fabricmarkupwrap').show('fast');
			/*PPSA-33 end*/
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
		
		/****************************BEDSPREAD COST****************************************/
		var cost_pbs = (lbr + fab_cost + back_cost);
		
		//TOTAL FAB COST PER LINE//
		var totcost_bs = (cost_pbs * qty);
		
		
		//LAYOUT WARNNG
		if(lay == 0){
			var warn1 = "LAYOUT ERROR: Check your fabric width and/or BS length";
		}else{
			var warn1 = "LAYOUT OK";
		}
		
		


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
		if(warn1 != 'LAYOUT OK'){
			warningboxcontent += '<img src="/img/delete.png" /> '+warn1+'<br>';
	        warncount++;
		}
			
		
		/* PPSASCRUM-318: start */
		/*if(laborsurcharge == 1){
			warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> Labor Rate increased by $<?php // echo $settings['full_size_mattress_surcharge']; ?> for FULL SIZE mattress.</span>';
			warncount++;
		}else if(laborsurcharge == 2){
			warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> Labor Rate increased by $<?php // echo $settings['queen_size_mattress_surcharge']; ?> for QUEEN SIZE mattress.</span>';
			warncount++;
		}else if(laborsurcharge == 3){
			warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> Labor Rate increased by $<?php // echo $settings['king_size_mattress_surcharge']; ?> for KING SIZE mattress.</span>';
			warncount++;
		}*/
		/* PPSASCRUM-318: end */

		
        if(warncount == 0){
    	     $('#warningbox').hide('fast');
        }else{
             $('#warningbox').html(warningboxcontent).show('fast');
        }





		//fill in the calculator results form values
		$('#item').val(quilt_l+" "+mato_l+" "+style_l+" BS");
		$('#waste-overhead').val(oh_l);
		$('#layout').val(lay);
		$('#layout-status').val(warn1);
		$('#cl').val(cl);
		$('#total-widths').val(tot_weol);
		$('#yds-per-unit').val(roundToTwo(yds_pbs).toFixed(2));
		
		$('#total-yds').val(roundToTwo(tot_yds).toFixed(2));
		
		$('#backing-quilting-cost').val(roundToTwo(back_cost).toFixed(2));
		$('#fabric-cost').val(roundToTwo(fab_cost).toFixed(2));
		
		$('#cost').val(roundToTwo(cost_pbs).toFixed(2));
		//var priceval=roundToTwo((cost_pbs*fabricMarkup)).toFixed(2);
		$('#price').val(roundToTwo(cost_pbs).toFixed(2));

		$('#top-widths').val(qty);
		$('#drop-widths').val('0');

	}else{
		//not RR
		var rr=0;
		

		$('#verticalrepeatwrap').show();
		$('#assumeddropwidthwrap').show();
		$('#custom-top-width').parent().show();
		$('#force-fitted-style-yds-accuracy').parent().parent().show();
		$('#force-full-widths-ea-bs').parent().parent().show();
		$('#force-full-widths-eol').parent().parent().show();
		$('#top').parent().show();
		$('#drop').parent().show();
		$('#cluster-status').parent().show();
		
		
		var l = parseFloat($('#length').val());
		var w = parseFloat($('#width').val());
		var xtra=parseFloat($('#extra-inches-seam-hems').val());


		<?php if(intval($quoteID) == 0){ ?>
		if($('#custom-base-labor').val() != '' && parseFloat($('#custom-base-labor').val()) > 0.00){
			var lbr = parseFloat($('#custom-base-labor').val());
		}else{
			var lbr = parseFloat($('#labor-per-bs').val());	
		}
		<?php }else{ ?>
		var lbr=parseFloat($('#labor-per-bs').val());
		<?php } ?>



		
		
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
			var oh=1.09;
			var quilt_l='Quilted';
			var oh_l='9%';
		}else{
			var quilted=0;
			//overhead waste percentage
			var oh=1.05;
			var quilt_l='Unquilted';
			var oh_l='5%';
		}
		console.log('Overhead '+oh_l);
		


		if(quilted == 1){
			if(style==1){
				//quilted throw...
				var difficultybase=parseFloat(<?php echo $settings['quilted_bs_calc_diff_utr_throw']; ?>);
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_throw']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['unquilted_bs_calc_weight_throw']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_throw']; ?>) * weightbase);

			}else if(style==0){
				//quilted fitted
				var difficultybase=parseFloat(<?php echo $settings['quilted_bs_calc_diff_utr_fitted']; ?>);
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_fitted']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['quilted_bs_calc_weight_fitted']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_fitted']; ?>) * weightbase);
			}
		}else{
			if(style==1){
				//unquilted throw
				var difficultybase=parseFloat(<?php echo $settings['unquilted_bs_calc_diff_utr_throw']; ?>);
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_throw']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['unquilted_bs_calc_weight_throw']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_throw']; ?>) * weightbase);
			}else if(style==0){
				//unquilted fitted
				var difficultybase=parseFloat(<?php echo $settings['unquilted_bs_calc_diff_utr_fitted']; ?>);
				var difficulty=(parseFloat(<?php echo $fabricData['bs_diff_fitted']; ?>)*difficultybase);

				var weightbase=parseFloat(<?php echo $settings['unquilted_bs_calc_weight_fitted']; ?>);
				var weight=(parseFloat(<?php echo $fabricData['bs_weight_fitted']; ?>) * weightbase);
			}
		}
		console.log('DIFFICULTY BASE = '+difficultybase);
		console.log('DIFFICULTY (ADJUSTED) = '+difficulty);
		difficulty=(difficulty * qty);
		$('#difficulty-rating').val(roundToTwo(difficulty).toFixed(2));

		console.log('WEIGHT BASE = '+weightbase);
		console.log('WEIGHT (ADJUSTED) = '+weight);
		weight=(weight*qty);
		$('#bs-calculated-weight').val(roundToTwo(weight).toFixed(2));



		if($('#com-fabric').is(':checked')){
			var mato=1;
			var mato_l='COM';
			fab_cost=0;
			$('#fabric-cost').val('0.00');
			$('#fabric-price').val('0.00');
			$('select[name=fabric-cost-per-yard]').parent().hide('fast');
			$('#fabricmarkupwrap,#inboundfreightwrap').hide('fast');
		}else{
			var mato=0;
			var mato_l='MOM';
			/*
			console.log('fab_cost = '+fab_cost);
			$('#fabric-cost').val(roundToTwo(fab_cost).toFixed(2));
			console.log('#fabric-cost = '+fab_cost);
			var markupforprice=(1+(parseFloat($('#fabric-markup').val())/100));
			$('#fabric-price').val(roundToTwo((fab_cost*markupforprice)).toFixed(2));
			console.log('#fabric-price = '+fab_cost+' * '+markupforprice);
			$('select[name=fabric-cost-per-yard]').parent().show('fast');
			$('#fabricmarkupwrap,#inboundfreightwrap').show('fast');
			*/
		}
		
		
		if($('#force-distributed-rounded-yds').is(':checked')){
			var fry_dist=1;
		}else{
			var fry_dist=0;
		}
		
		
		if($('#force-full-widths-ea-bs').is(':checked')){
			var ffw=1;
		}else{
			var ffw=0;
		}
		
		if($('#force-rounded-yds-ea-bs').is(':checked')){
			var fry=1;
		}else{
			var fry=0;
		}
		
		
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
		}else if($('select[name=fabric-cost-per-yard]').val() == 'bolt'){
			var fab_ppy = parseFloat($('#fcpbolt').val());
		}else if($('select[name=fabric-cost-per-yard]').val() == 'case'){
			var fab_ppy = parseFloat($('#fcpcase').val());
		}

		<?php
		}
		?>
		

		
		if($('#force-full-widths-eol').is(':checked')){
			var ffw_eol=1;
		}else{
			var ffw_eol=0;
		}
		
		
		//calculate drop width
		var dropwidth=(parseFloat($('#assumed-drop-width').val())+parseFloat($('#extra-inches-seam-hems').val()));
		
		/*calculate raw top width
		//step1
		var top1 = ((parseFloat($('#width').val()) + (2 * parseFloat($('#extra-inches-seam-hems').val()))) - (2 * dropwidth));
		//var top1 = ((parseFloat($('#width').val())) - (2 * (dropwidth + parseFloat($('#extra-inches-seam-hems').val()))) + (2 * parseFloat($('#extra-inches-seam-hems').val())));
		

		//step2: if present, a custom top (AKA matress width) will replace the calculated raw top W
        if(parseFloat($('#custom-top-width-mattress-w').val()) > 0 && parseFloat($('#custom-top-width-mattress-w').val()) != 36){    
	    	var top = (parseFloat($('#custom-top-width-mattress-w').val()) + (2 * parseFloat($('#extra-inches-seam-hems').val())));
	    	var calc_mattress = parseFloat($('#custom-top-width-mattress-w').val());
    	}else{
        	var top = top1;
        	var calc_mattress = (w - (2 * dropwidth));
        }
        
        var topCut = (top + (2 * parseFloat($('#extra-inches-seam-hems').val())));
        $('#top-cut').val(topCut);
        */

        var top = (parseFloat($('#custom-top-width-mattress-w').val()) + (2 * parseFloat($('#extra-inches-seam-hems').val())));
        $('#top-cut').val(top);
       


        if(w < (parseFloat($('#custom-top-width-mattress-w').val()) + (2 * parseFloat($('#assumed-drop-width').val())))){
        	var warn5 = 'WRONG BS WIDTH INPUT [';
        	var warn6 = '] : LESS THAN [';
        	var warn7 = 1;
        	var rec_drop = ((w - parseFloat($('#custom-top-width-mattress-w').val())) / 2);
        }else if(w > (parseFloat($('#custom-top-width-mattress-w').val()) + (2 * parseFloat($('#assumed-drop-width').val())))){
        	var warn5 = 'WRONG BS WIDTH INPUT [';
        	var warn6 = '] : BIGGER THAN [';
        	var warn7=1;
        	var rec_drop = ((w - parseFloat($('#custom-top-width-mattress-w').val())) / 2);
        }else{
        	var warn5 = 'OK';
        	var warn6 = 'OK';
        	var warn7 = 'OK';
        	var rec_drop = 'OK';
        }


        var calc_w = (parseFloat($('#custom-top-width-mattress-w').val()) + (2 * parseFloat($('#assumed-drop-width').val())));




       
       	/*******************FIND LAYOUT*************************/
        //find a layout that fits the [dimensions/fabric_width] combo
		if((top + (2 * dropwidth)) <= adj_fab_w){
	        var lay1 = 1;
       	}else if(((top + dropwidth) <= adj_fab_w) && ((top +(2 * dropwidth)) > adj_fab_w) && ((3 * dropwidth)<= adj_fab_w)){
        	var lay1 = 1.33;
		}else if((top < adj_fab_w)  && ((top + dropwidth) > adj_fab_w) && ((3 * dropwidth)<= adj_fab_w)){
         	var lay1 =1.66;
		}else if((top < adj_fab_w)  && ((top + dropwidth) > adj_fab_w) && ((2 * dropwidth)<= adj_fab_w)){
           	var lay1 = 2;
        }else if(top > adj_fab_w || dropwidth > adj_fab_w){
        	var lay1 = 0;
        }else{
			var lay1 = 0;
		}	
		
		
		/************CALCULATE TOTAL LAYOUT, ADJUSTING FOR .33 and .66 losses over quantity*****************/
		/****************************ADJUST FOR Forced_Full_Widths_ea_BS************************************/
		if(ffw == 1){
			var lay = Math.ceil(lay1);
		}else{
			var lay = lay1;
		}	
		
		
		/* PPSASCRUM-318: start */
		// var laborValue=0;
		/* PPSASCRUM-318: end */
		var laborsurcharge=0;
		if(lay==1){

			if($('#com-fabric').is(':checked')){
			    /* PPSASCRUM-318: start */
				//laborValue=labor_rules['wide_com'];
				/* PPSASCRUM-318: end */
				$('#backing-quilting-price-per-yd').val('<?php echo $settings['bedspread_quilting_com_per_yard']; ?>');
				console.log('NO SEAMS, WIDE GOODS NO SEAMS COM LABOR Override');
			}else{
				/* PPSASCRUM-318: start */
				//laborValue=labor_rules['wide_mom'];
				/* PPSASCRUM-318: end */
				$('#backing-quilting-price-per-yd').val('<?php echo $settings['bedspread_quilting_mom_per_yard']; ?>');
				console.log('NO SEAMS, WIDE GOODS NO SEAMS MOM LABOR Override');
			}
		}else{
			if($('#com-fabric').is(':checked')){
				/* PPSASCRUM-318: start */
				//laborValue=labor_rules['narrow_com'];
				/* PPSASCRUM-318: end */
				$('#backing-quilting-price-per-yd').val('<?php echo $settings['bedspread_quilting_com_per_yard']; ?>');
			}else{
				/* PPSASCRUM-318: start */
				//laborValue=labor_rules['narrow_mom'];
				/* PPSASCRUM-318: end */
				$('#backing-quilting-price-per-yd').val('<?php echo $settings['bedspread_quilting_mom_per_yard']; ?>');
			}
		}




		//determine if a surcharge needs to be added for mattress size (labor surcharge)
		if(parseFloat($('#custom-top-width-mattress-w').val()) >= 54 && parseFloat($('#custom-top-width-mattress-w').val()) < 60){
			//full size
			/* PPSASCRUM-318: start */
			// laborValue = (parseFloat(laborValue) + parseFloat(<?php //echo $settings['full_size_mattress_surcharge']; ?>));
			/* PPSASCRUM-318: end */
			console.log('$<?php //echo $settings['full_size_mattress_surcharge']; ?> added to LABOR rate for FULL SIZE mattress size');
			var laborsurcharge=1;
		}else if(parseFloat($('#custom-top-width-mattress-w').val()) >= 60 && parseFloat($('#custom-top-width-mattress-w').val()) < 76){
			//queen size
			/* PPSASCRUM-318: start */
			// laborValue = (parseFloat(laborValue) + parseFloat(<?php //echo $settings['queen_size_mattress_surcharge']; ?>));
			/* PPSASCRUM-318: end */
			console.log('$<?php //echo $settings['queen_size_mattress_surcharge']; ?> added to LABOR rate for QUEEN SIZE mattress size');
			var laborsurcharge=2;
		}else if(parseFloat($('#custom-top-width-mattress-w').val()) >= 76){
			//king size
			/* PPSASCRUM-318: start */
			// laborValue = (parseFloat(laborValue) + parseFloat(<?php //echo $settings['king_size_mattress_surcharge']; ?>));
			/* PPSASCRUM-318: end */
			console.log('$<?php //echo $settings['king_size_mattress_surcharge']; ?> added to LABOR rate for KING SIZE mattress size');
			var laborsurcharge=3;
		}else{
			//no change
		}


        /* PPSASCRUM-318: start */
		//$('#labor-per-bs').val(laborValue);
		/* PPSASCRUM-318: end */




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
        

        //calculate total fulls
		var tot_full = (parseInt(lay) * qty);
		

		//step1: convert full to thirds "THIRDS"
		var f2t = floordec(tot_full/3);
		console.log('f2t = floordec('+tot_full+'/3) = '+f2t);
		
		//break "THIRDS" further into integers and decimals
		var f2t_int = parseInt(f2t);
		console.log('f2t_int = intval('+f2t+') = '+f2t_int);

		var f2t_dec = (f2t-(parseInt(f2t)));
		console.log('f2t_dec = ('+f2t+' - intval('+f2t+') = '+f2t_dec);


		//calculate total integers of "THIRDS:"
		if(lay == 1.33){
			var tot_int = f2t_int;
		}else if(lay == 1.66){
			var tot_int = ((f2t_int) * 2);
		}else{
			var tot_int = 0;
		}
		console.log('tot_int = '+tot_int);	
		
		//calculate total decimals of "THIRDS"
		if(lay == 1.33){
			var tot_dec = f2t_dec;
		}else if(lay == 1.66){
			var tot_dec = (f2t_dec * 2);
		}else{
			var tot_dec = 0;
		}
		console.log('tot_dec = '+tot_dec);
		
		//////////////////////add them up for TOTAL WIDTHS///////////////////////////////////
        if(ffw_eol == 1){
			var tot_weol = Math.ceil(tot_full + tot_int + tot_dec);
			console.log('tot_weol = ceil('+tot_full+' + '+tot_int+' + '+tot_dec+')');
		}else{
			var tot_weol = (tot_full + tot_int + tot_dec);
			console.log('tot_weol = ('+tot_full+' + '+tot_int+' + '+tot_dec+')');
		}
		console.log('tot_weol = '+tot_weol);
		
		/**********************CALCULATE CL****************************/
		////////////////////////CUT LENGHT/////////////////////////////
		//calculate how many widths will be used exclusively for tops and how many exclusively for drops
		if(lay == 1){
			var topwidths = qty;
			var dropwidths = 0;			
		}else if(((lay == 1.33) || (lay == 1.66)) && (style == 1)){
			var topwidths = Math.ceil(tot_weol);
			var dropwidths = 0;			
		}else if(((lay == 1.33) || (lay == 1.66)) && (style == 0)){
			var topwidths = qty;
			var dropwidths = (Math.ceil(tot_weol) - qty);			
		}else if((lay == 2) && (style == 1)){
			var topwidths = (qty * 2);
			var dropwidths = 0;
		}else if((lay == 2) && (style == 0)){
			var topwidths = qty;
			var dropwidths = qty;			
		}
		
		/////////////calculate top length
		// [SAME TOP & DROP WIDTH LENGTH]
		var top_l = (parseFloat($('#length').val()) + (2 * parseFloat($('#extra-inches-seam-hems').val())));
		console.log('tol_l = ('+$('#length').val()+' + (2 * '+$('#extra-inches-seam-hems').val()+'))');
		//adj for vertical repeat
		if(parseFloat($('#vertical-repeat').val()) == 0){
			var cl = top_l;
		}else{
			var cl = ((Math.ceil(top_l / parseFloat($('#vertical-repeat').val()))) * parseFloat($('#vertical-repeat').val()));
		}
		
		// [TOP WIDTHS SEPARATELY ]
		var cl_t = cl;	
		
		// [DROP WIDTHS SEPARATELY ]
		if($('#force-fitted-style-yds-accuracy').is(':checked')){
			var cl_d1 = (top_l - 10);	// [SHORTER 10" IF FITTED ACCURACY IS ENABLED]
		}else{
			var cl_d1 = top_l;		// [SAME LENGTH AS TOPS IF FITTED ACCURACY IS DISABLED]
		}
		
		//adj for vertical repeat
		if(parseFloat($('#vertical-repeat').val()) == 0){
			var cl_d = cl_d1;
		}else{
			var cl_d = ((Math.ceil(cl_d1 / parseFloat($('#vertical-repeat').val()))) * parseFloat($('#vertical-repeat').val()));
		}
		
		
		/***********CALCULATE YDS [SAME TOP & DROP WIDTH LENGTH]*********************/
		//step1: raw yds
		var yds1 = (((cl * (tot_weol / qty))/36) * oh);
		//step2: if forced rounded yds ea BS
		var yds2 = Math.ceil(yds1);
		//step3: when forcing distributed rounded yds on the line 
		var yds3 = Math.ceil(yds1 * qty) / qty;
		
		//route the appropiate value to output
		if(fry == 1){
			var yds_pbs_s = yds2;	//step 3
			console.log('fry = 1, yds_pbs = '+yds2);
		}else if(fry_dist == 1){
			var yds_pbs_s = yds3;	//step 2
			console.log('fry_dist = 1, yds_pbs = '+yds3);
		}else{
			var yds_pbs_s = yds1;	//step 1
			console.log('fry = 0, fry_dist = 0, yds_pbs = '+yds1);
			console.log ('yds1 = ((('+cl+' * ('+tot_weol+' / '+qty+')) / 36) * '+oh+')');
		}
		
		/****************CALCULATE YDS [TOP WIDTHS SEPARATELY ]************/
		//step1: raw yds
		var yds1_t = (((cl_t * (topwidths / qty))/36) * oh);
		//step2: if forced rounded yds ea BS
		var yds2_t = Math.ceil(yds1_t);
		//step3: when forcing distributed rounded yds on the line 
		var yds3_t = Math.ceil(yds1_t * qty) / qty;
		//route the appropiate value to output
		if($('#force-rounded-yds-ea-bs').is(':checked')){
			var yds_pbs_t = yds2_t;	//step 3
		}else if($('#force-distributed-rounded-yds').is(':checked')){
			var yds_pbs_t = yds3_t;	//step 2
		}else{
			var yds_pbs_t = yds1_t;	//step 1
		}

		/***************CALCULATE YDS [DROP WIDTHS SEPARATELY ]***********/
		//step1: raw yds
		var yds1_d = (((cl_d * (dropwidths / qty))/36) * oh);
		//step2: if forced rounded yds ea BS
		var yds2_d = Math.ceil(yds1_d);
		//step3: when forcing distributed rounded yds on the line 
		var yds3_d = Math.ceil(yds1_d * qty) / qty;
		//route the appropiate value to output
		
		if($('#force-rounded-yds-ea-bs').is(':checked')){
			var yds_pbs_d = yds2_d;	//step 3
		}else if($('#force-distributed-rounded-yds').is(':checked')){
			var yds_pbs_d = yds3_d;	//step 2
		}else{
			var yds_pbs_d = yds1_d;	//step 1
		}
		
		
		//TOTAL YDS PER LINE//
		if($('#force-fitted-style-yds-accuracy').is(':checked')){
			var yds_pbs = (yds_pbs_t + yds_pbs_d);
			console.log('yds_pbs = ('+yds_pbs_t+' + '+yds_pbs_d+')');
		}else{	
			var yds_pbs = yds_pbs_s;
			console.log('yds_pbs = '+yds_pbs_s);
		}	
		


		var tot_yds = (yds_pbs * qty);
		console.log('tot_yds = ('+yds_pbs+' * '+qty+')');


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
		if(qty >0 && $('select[name=fabric-cost-per-yard]').val() != '' && tot_yds > 0){
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
		
		console.log('tot_yds = '+tot_yds);
		console.log('ibfrtpy = '+ibfrtpy);
		$('#inbound-freight').val(ibfrtpy);
	
		
		
		if($('#inbound-freight-custom-value').val() != ''){
			ibfrtpy=parseFloat($('#inbound-freight-custom-value').val());
		}
			
		

		if($('#fabric-markup-custom-value').val() != ''){
			var fabricMarkup=(1+(parseFloat($('#fabric-markup-custom-value').val())/100));
		}else{
			var fabricMarkup=(1+(parseFloat($('#fabric-markup').val())/100));
		}
		
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
			console.log('fab_cost_math = ('+parseFloat(yds_pbs)+' * '+markedupFabric+')');
			var totfab_cost = (fab_cost * qty);
				/** start PPSA-33 **/
			$('select[name=fabric-cost-per-yard]').parent().show('fast');
			$('#fabricmarkupwrap,#inboundfreightwrap').show('fast');
			/** end PPSA -33 **/
		}
		
		/*******************************BACKING COST******************************/
		
		if(quilted == 0){
			var back_cost1 = 0;
		}else{
			var back_cost1 = parseFloat($('#backing-quilting-price-per-yd').val());
		}	

		var back_cost = (back_cost1 * yds_pbs);
		var totback_cost = (back_cost * qty);
		
		/*****************************BEDSPREAD COST****************************/
		var cost_pbs = (lbr + fab_cost + back_cost);
		console.log('cost_pbs_math = ('+lbr+' + '+fab_cost+' + '+back_cost+')');
		//TOTAL FAB COST PER LINE//
		var totcost_bs = (cost_pbs * qty);
		
		/************************************WARNINGS*************************************/
		//LAYOUT WARNNG
		if(lay1 == 0){
			var warn1 = "LAYOUT ERROR: Check your fabric width and/or BS width";
		}else{
			var warn1 = "LAYOUT OK";
		}
		
		//SINGLE/CLUSTER BS WARNING
		if((qty == 1 && ffw == 0 && ffw_eol == 0) && (lay == 1.33 || lay == 1.66)){
			var warn2 = "You're making a Single BS that uses partial widths. Consider forcing Full Widths ea BS";
		}else if((qty > 1 && ffw == 0 && ffw_eol == 0) && (lay == 1.33 || lay == 1.66) && (((qty / 3) - Math.floor((qty/3))) > 0)){
			var warn2 = "You're making a set of BS that uses partial widths. Consider forcing Full Widths on the line";
		}else{
			var warn2 = "Your combination of Qty and Layout seems OK";
		}
		
		/*WARNING TOP < 36"//
		if(top < 36){
			var warn3 = "WARNING, TOP WIDTH LESS THAN 36\"";
		}else{
			var warn3 = "";
		}*/
		

		console.log('FLOAT '+tot_weol+' ,  INT '+parseInt(tot_weol));


		
		
		$('#item').val(quilt_l+" "+mato_l+" "+style_l+" BS");
		$('#waste-overhead').val(oh_l);
		$('#top').val(top);

		

		//$('#mattress-width-status').val(warn3);
		$('#drop').val(dropwidth);
		$('#layout').val(lay);
		$('#layout-status').val(warn1);
		$('#cluster-status').val(warn2);
		$('#cl').val(cl_t+"CL (tops) / "+cl_d+"CL (drops)");
		$('#total-widths').val(roundToTwo(tot_weol).toFixed(2));
		$('#yds-per-unit').val(roundToTwo(yds_pbs).toFixed(2));

		$('#top-widths').val(topwidths);
		$('#drop-widths').val(dropwidths);

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
		if(warn1 != 'LAYOUT OK'){
			warningboxcontent += '<img src="/img/delete.png" /> '+warn1+'<br>';
	        warncount++;
		}
			
		//warn2
		if(warn2 != 'Your combination of Qty and Layout seems OK'){
			
	        
	        warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> '+warn2+'</span><br>';
	        
	        if(warn2 == "You're making a Single BS that uses partial widths. Consider forcing Full Widths ea BS"){
	        	$('#qty,#layout,#total-widths').parent().addClass('alertcontent');
	        	$('#force-full-widths-ea-bs').parent().parent().addClass('alertcontent');
	        	$('#force-full-widths-eol').parent().parent().removeClass('alertcontent');
			}else if(warn2=="You're making a set of BS that uses partial widths. Consider forcing Full Widths on the line"){
				$('#qty,#layout,#total-widths').parent().addClass('alertcontent');
				$('#force-full-widths-ea-bs').parent().parent().removeClass('alertcontent');
	        	$('#force-full-widths-eol').parent().parent().addClass('alertcontent');
			}else{
				$('#qty,#layout,#total-widths').parent().removeClass('alertcontent');
	        	$('#force-full-widths-ea-bs,#force-full-widths-eol').parent().parent().removeClass('alertcontent');
			}

	        warncount++;
		}else{
			$('#qty,#layout,#total-widths').parent().removeClass('alertcontent');
	        $('#force-full-widths-ea-bs,#force-full-widths-eol').parent().parent().removeClass('alertcontent');
		}
			
		



		if(!$('#railroaded').prop('checked')){
			if(warn5 != 'OK'){
				warningboxcontent += '<img src="/img/delete.png" /> '+warn5+$('#width').val()+warn6+calc_w+'] = ('+$('#custom-top-width-mattress-w').val()+' + '+$('#assumed-drop-width').val()+' + '+$('#assumed-drop-width').val()+')<br>';
				$('#width').parent().addClass('badvalue');
				$('#assumed-drop-width').parent().addClass('badvalue');
				$('#custom-top-width-mattress-w').parent().addClass('badvalue');

				warningboxcontent += '<img src="/img/delete.png" /> RECOMMENDED FINISHED DROP <u>'+rec_drop+'</u><br>';

				warncount++;
				warncount++;
			}else{
				$('#width').parent().removeClass('badvalue');
				$('#assumed-drop-width').parent().removeClass('badvalue');
				$('#custom-top-width-mattress-w').parent().removeClass('badvalue');
			}
		}


        /* PPSASCRUM-318: start */
		/*if(laborsurcharge == 1){
			warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> Labor Rate increased by $<?php // echo $settings['full_size_mattress_surcharge']; ?> for FULL SIZE mattress.</span>';
			warncount++;
		}else if(laborsurcharge == 2){
			warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> Labor Rate increased by $<?php // echo $settings['queen_size_mattress_surcharge']; ?> for QUEEN SIZE mattress.</span>';
			warncount++;
		}else if(laborsurcharge == 3){
			warningboxcontent += '<span style="color:#D96D00 !important;"><img src="/img/alert.png" /> Labor Rate increased by $<?php // echo $settings['king_size_mattress_surcharge']; ?> for KING SIZE mattress.</span>';
			warncount++;
		}*/
		/* PPSASCRUM-318: end */



        if(warncount == 0){
    	     $('#warningbox').hide('fast');
        }else{
             $('#warningbox').html(warningboxcontent).show('fast');
        }




		$('#fabric-cost').val(roundToTwo(fab_cost).toFixed(2));
		$('#backing-quilting-cost').val(roundToTwo(back_cost).toFixed(2));
		$('#cost').val(roundToTwo(cost_pbs).toFixed(2));
		//var priceval=roundToTwo((cost_pbs*fabricMarkup)).toFixed(2);
		//console.log(priceval);
		//console.log('cost_pbs = '+cost_pbs+' markup '+fabricMarkup);
		$('#price').val(roundToTwo(cost_pbs).toFixed(2));

	}

    /* PPSASCRUM-318: start */
    let laborCostPerBS = 0;
    if ((parseFloat($('#width').val()) < 84 && parseFloat($('#length').val()) >= 90) && parseFloat($('#custom-top-width-mattress-w').val()) < 54) {
        if($('#com-fabric').is(':checked')) {
            laborCostPerBS = <?php echo $settings['twin_size_com_bs_labor_wid<84_len>=90_OR_matt<54']; ?>;
        } else {
            laborCostPerBS = <?php echo $settings['twin_size_mom_bs_labor_wid<84_len>=90_OR_matt<54']; ?>;
        }
    } else if ((parseFloat($('#width').val()) < 90 && parseFloat($('#length').val()) >= 90) && parseFloat($('#custom-top-width-mattress-w').val()) < 60) {
        if($('#com-fabric').is(':checked')) {
            laborCostPerBS = <?php echo $settings['full_size_com_bs_labor_wid<90_len>=90_OR_matt<60']; ?>;
        } else {
            laborCostPerBS = <?php echo $settings['full_size_mom_bs_labor_wid<90_len>=90_OR_matt<60']; ?>;
        }
    } else if ((parseFloat($('#width').val()) < 108 && parseFloat($('#length').val()) >= 95) && parseFloat($('#custom-top-width-mattress-w').val()) < 78) {
        if($('#com-fabric').is(':checked')) {
            laborCostPerBS = <?php echo $settings['queen_size_com_bs_labor_wid<108_len>=95_OR_matt<78']; ?>;
        } else {
            laborCostPerBS = <?php echo $settings['queen_size_mom_bs_labor_wid<108_len>=95_OR_matt<78']; ?>;
        }
    } else if ((parseFloat($('#width').val()) >= 108 && parseFloat($('#length').val()) >= 95) && parseFloat($('#custom-top-width-mattress-w').val()) >= 78) {
        if($('#com-fabric').is(':checked')) {
            laborCostPerBS = <?php echo $settings['king_size_com_bs_labor_wid>=108_len>=95_OR_matt>=78']; ?>;
        } else {
            laborCostPerBS = <?php echo $settings['king_size_mom_bs_labor_wid>=108_len>=95_OR_matt>=78']; ?>;
        }
    }
    $('#labor-per-bs').val(laborCostPerBS);
    if (!laborCostPerBS || !laborCosts.includes(parseFloat($('#labor-per-bs').val()))) {
        $('#labor-per-bs').removeClass('notvalid').removeClass('validated').addClass('notvalid');
    } else {
		$('#labor-per-bs').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
    
    /* PPSASCRUM-318: end */

		
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
			$('#extra-inches-seam-hems').val('<?php echo $fabricData['bs_hem_multiplier_rr']; ?>');
			$('#assumed-drop-width').parent().hide('fast');
			$('#top-cut').parent().hide('fast');
			$('#drop').parent().hide('fast');
		}else{
			$('#extra-inches-seam-hems').val('<?php echo $fabricData['bs_hem_multiplier_utr']; ?>');
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
if($userData['scroll_calcs'] == 1){
?>
correctColHeights();
setInterval('correctColHeights()',500);
<?php } ?>


	$('#calcformleft input,#calcformleft select,#add_surcharge').keyup(function(){
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
	    /* PPSASCRUM-385: start */
		// location.replace('/quotes/add/<?php //echo $quoteID; ?>');
		location.replace('/quotes/add/<?php echo $quoteID; ?>/<?php echo $ordermode; ?>');
		/* PPSASCRUM-385: end */
	});
	
	
	$('#com-fabric').change(function(){
		if($(this).prop('checked')){
			$('#fab-price-per-yd').val('0.00');
					/*PPSA-33start */	$('#fabric-cost-per-yard-custom-value').val('');/*PPSA-33 end */

			$('.calculatebutton button').trigger('click');
			$('#custom-fabric-cost-per-yard').removeClass('notvalid').removeClass('validated');
		}
	});

	$('#com-fabric').click(function(){
		if($(this).prop('checked')){
		    		/*PPSA-33start */	$('#fabric-cost-per-yard-custom-value').val('');/*PPSA-33 end */

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
	
	
	/* PPSASCRUM-318: start */
    $('#width, #length, #custom-top-width-mattress-w, #com-fabric').change(function() {
        let laborCostPerBS = 0;
        if ((parseFloat($('#width').val()) < 84 && parseFloat($('#length').val()) >= 90) && parseFloat($('#custom-top-width-mattress-w').val()) < 54) {
            if($('#com-fabric').is(':checked')) {
                laborCostPerBS = <?php echo $settings['twin_size_com_bs_labor_wid<84_len>=90_OR_matt<54']; ?>;
            } else {
                laborCostPerBS = <?php echo $settings['twin_size_mom_bs_labor_wid<84_len>=90_OR_matt<54']; ?>;
            }
        } else if ((parseFloat($('#width').val()) < 90 && parseFloat($('#length').val()) >= 90) && parseFloat($('#custom-top-width-mattress-w').val()) < 60) {
            if($('#com-fabric').is(':checked')) {
                laborCostPerBS = <?php echo $settings['full_size_com_bs_labor_wid<90_len>=90_OR_matt<60']; ?>;
            } else {
                laborCostPerBS = <?php echo $settings['full_size_mom_bs_labor_wid<90_len>=90_OR_matt<60']; ?>;
            }
        } else if ((parseFloat($('#width').val()) < 108 && parseFloat($('#length').val()) >= 95) && parseFloat($('#custom-top-width-mattress-w').val()) < 78) {
            if($('#com-fabric').is(':checked')) {
                laborCostPerBS = <?php echo $settings['queen_size_com_bs_labor_wid<108_len>=95_OR_matt<78']; ?>;
            } else {
                laborCostPerBS = <?php echo $settings['queen_size_mom_bs_labor_wid<108_len>=95_OR_matt<78']; ?>;
            }
        } else if ((parseFloat($('#width').val()) >= 108 && parseFloat($('#length').val()) >= 95) && parseFloat($('#custom-top-width-mattress-w').val()) >= 78) {
            if($('#com-fabric').is(':checked')) {
                laborCostPerBS = <?php echo $settings['king_size_com_bs_labor_wid>=108_len>=95_OR_matt>=78']; ?>;
            } else {
                laborCostPerBS = <?php echo $settings['king_size_mom_bs_labor_wid>=108_len>=95_OR_matt>=78']; ?>;
            }
        }
        $('#labor-per-bs').val(laborCostPerBS);
        if (!laborCostPerBS || !laborCosts.includes(parseFloat($('#labor-per-bs').val()))) {
            $('#labor-per-bs').removeClass('notvalid').removeClass('validated').addClass('notvalid');
        } else {
    		$('#labor-per-bs').removeClass('notvalid').removeClass('validated').addClass('validated');
    	}
    });
    /* PPSASCRUM-318: end */


	if(canCalculate()){	
		$('#cannotcalculate').hide();
		doCalculation(); 
	}else{
		$('#cannotcalculate').show();
	}
});

/* PPSASCRUM-318: start */
var laborCosts = [<?php echo $settings['twin_size_com_bs_labor_wid<84_len>=90_OR_matt<54']; ?>, <?php echo $settings['twin_size_mom_bs_labor_wid<84_len>=90_OR_matt<54']; ?>,
    <?php echo $settings['full_size_com_bs_labor_wid<90_len>=90_OR_matt<60']; ?>, <?php echo $settings['full_size_mom_bs_labor_wid<90_len>=90_OR_matt<60']; ?>,
    <?php echo $settings['queen_size_com_bs_labor_wid<108_len>=95_OR_matt<78']; ?>, <?php echo $settings['queen_size_mom_bs_labor_wid<108_len>=95_OR_matt<78']; ?>,
    <?php echo $settings['king_size_com_bs_labor_wid>=108_len>=95_OR_matt>=78']; ?>, <?php echo $settings['king_size_mom_bs_labor_wid>=108_len>=95_OR_matt>=78']; ?>];
/* PPSASCRUM-318: end */

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
	
	<?php } ?>


	if(parseFloat($('#labor-per-bs').val()) == 0){
		//console.log('Cannot calculate without a Labor Per Linear Foot value');
		$('#labor-per-bs').removeClass('notvalid').removeClass('validated').addClass('notvalid');
		errorcount++;
	}else{
		$('#labor-per-bs').removeClass('notvalid').removeClass('validated').addClass('validated');
	}
	
	
	if($('select[name=fabric-price-per-yard]').val() == '0'){
		//console.log('Cannot calculate without a Fabric Price (per yard) selection');
		errorcount++;
	}
	/*PPSASSCRUM-201 start */
	if(($('#quotetype').val() == 1)  &&  $('#location').val()==''){
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