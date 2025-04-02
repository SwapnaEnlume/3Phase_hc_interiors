<style>
.stillneeded{ border:2px solid red !important; }
form div.submit input[type=submit]:disabled,
form div.submit input[type=submit][disabled]{ cursor:not-allowed !important; background:#444 !important; color:#CCC !important; }

#content .row{ width:600px; margin:0 auto; }
form div.submit{ float:right; }
form div.submit input[type=submit]{ background:#26337A; color:#FFF; border:1px solid #000; padding:10px 20px; font-size:15px; font-weight:bold; }



form fieldset div.input{ width:46%; float:left; margin:0 3% 2% 0; }
form fieldset{ background:#f8f8f8; border:1px solid #777; }
form fieldset legend{ border-bottom:0 !important; display: inline-block !important; width: auto; background: none;}

label[for=fabric-type-none]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
label[for=fabric-type-existing]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
/*PPSA -36 start */ label[for=fabric-type-typein]{ display:none; font-weight:normal !important; }/*PPSA -36 end */


#imagelibrarycontents{ width:100%; height:400px; background:#FFF; padding:10px; overflow-y:scroll; overflow-x:none; }
#imagelibrarycontents ul{ list-style:none; margin:0px; padding:0px; }
#imagelibrarycontents ul li img{ width:auto; max-width:100%; height:85px; max-height:85px;  cursor:pointer; }
#imagelibrarycontents ul li{ width:48%; float:left; margin:15px 1%; text-align:center; border:2px solid white; }
#imagelibrarycontents ul li:hover{ border:2px solid green; }

label[for=image-method-none]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
label[for=image-method-library]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
label[for=image-method-upload]{display:inline-block; font-weight:normal !important; }

table td h4{ text-align:center; margin:0; }
table td h5{ text-align:center; color:navy; }

fieldset#subclasswrap label{ display:inline-block; margin:5px; }
fieldset#subclasswrap label input{ margin-bottom:0; }

fieldset#optionalfields input{ width:100%; margin-bottom:0; }
fieldset#optionalfields td div.input{ width:100%; }

li.selectedimage{ position:relative; border:2px solid green !important; }
li.selectedimage:after{ content:''; width:36px; height:36px; background-image:url('/img/Ok-icon.png'); background-size:100% auto; position:absolute; bottom:5px; right:5px; z-index:55; }
#imageuploadform div.input{ width:98% !important; float:none !important; }

/* PPSASCRUM-100: start */
.custom-fabric-selection-dropdown { position: relative; width: 558px; background-color: #FAFAFA; border-color: #ccc; color: rgba(0, 0, 0, 0.75); font-family: 'inherit'; font-size: 0.875rem; line-height: normal; height: 2.3125rem; cursor: pointer; justify-content: flex-start; align-items: center; }

div.custom-fabric-selection-dropdown:not(:-internal-list-box) { overflow: visible !important; }

.current-custom-fabric-selection { background-color: #FAFAFA; border-radius: 0; border-style: solid; border-width: 1px; color: rgba(0, 0, 0, 0.60); font-family: 'Helvetica Neue'; font-size: 0.875rem; line-height: normal; letter-spacing: 0.4px; word-spacing: 0.5px; padding: 0.5rem; height: 2.3125rem; cursor: pointer; border: 1px solid #ccc; width: 558px; display: flex; justify-content: space-between; align-items: center; }

.custom-fabric-dropdown-options { display: none; border: 1px solid #ccc; background-color: #646267; opacity: 0.95; background-attachment: scroll; position: absolute; width: 558px; z-index: 1; align-items: center; font-size: 14px; font-family: 'metropolislight'; font: 1px 800 #000000 !important; font-weight: 900; text-decoration-color: rgba(0, 0, 0, 0.75); max-height: 1500%; overflow-x: hidden; overflow-y: auto; box-sizing: inherit; border-radius: 0.4rem; }

.scroll-button { padding: 10px; text-align: center; background-color: #646267; cursor: pointer; font-weight: bold; }

.custom-fabric-dropdown-options div { display: flex; justify-content: space-between; padding: 10px; cursor: pointer; width: 558px; align-items: center; font-size: 14px; font-family: 'metropolislight'; color: #F0EFF0; }

.custom-fabric-dropdown-options div:hover { background-color: #548BEB !important; }

div.custom-fabric-dropdown-options > div#label:hover { background-color: transparent !important; }

.fabric-name { flex: 1; }

.vendor-name { flex: 1; white-space: nowrap; justify-content: flex-start; }
/* PPSASCRUM-100: end */
/* PPSASCRUM-371: start */
.disable-custom-dropdown { pointer-events: none; opacity: 0.5; background-color: #dddddd; border-color:normal  #dddddd; }
/* PPSASCRUM-371: end */
</style>
<script>
$(function(){
    $(document).on('keydown', 'input:visible, select:visible, button:visible,feildset:visible, [type="radio"]:visible, [type="checkbox"]:visible, [tabindex]', function(e) {
            if (e.which === 13) { // 13 is the Enter key code
                e.preventDefault(); // Prevent default action, which is form submission
                moveToNextFocusableElement(this);
            }
        });
});
</script>
<?php
if($mode=='edit'){
            $typeTitle = ($ordermode == 'workorder') ? "WorkOrder ": "SalesOrder ";

    echo "<h3>Edit " .$typeTitle." Miscellaneous Catch-All Line Item</h3><hr>";
    echo "<!--\n"; print_r($lineitemmeta); echo "\n-->";
}else{
    if(isset($subaction) && is_numeric($subaction)){
        echo "<h3>Add Child Line Misc Catch-All to Line ".$subaction."</h3>";    
    }else{
        echo "<h3>Add Miscellaneous Catch-All Line to Quote</h3><hr>";
    }
}

echo $this->Form->create(null,['type'=>'file','class'=>'formAction']);
echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);


echo $this->Form->input('line_item_title',['label'=>'Line Item Title','required'=>true,'autocomplete'=>'off','value'=>$thisLineItem['title']]);

$subclassList=array();
foreach($subclasses as $subclass){
    $subclassList[$subclass['id']]=$subclass['subclass_name'];
}

echo "<div class=\"input select required\">";
echo $this->Form->label('Miscellaneous Sub-Class');
if($ordermode == 'workorder' && isset($thisLineItem['product_subclass'])){
    echo $this->Form->input('product_subclass',['type'=>'hidden','value'=> $thisLineItem['product_subclass']]);
 echo $this->Form->select('product_subclass',$subclassList,['disabled'=>true,'empty'=>'--Select Sub-Class--','value'=>$thisLineItem['product_subclass']]);
}
else
echo $this->Form->select('product_subclass',$subclassList,['required'=>true,'empty'=>'--Select Sub-Class--','value'=>$thisLineItem['product_subclass']]);

echo "</div>";


echo $this->Form->input('description',['required'=>false,'maxlength'=>300,'label'=>'Description (300 characters max)','value'=>$thisLineItem['description']]);

echo $this->Form->input('specs',['type'=>'textarea','placeholder'=>'Other details and specs for this custom line item','autocomplete'=>'off','value'=>$lineitemmeta['specs']]);
			
			
if($ordermode == 'workorder'&& isset($thisLineItem['qty']))
echo $this->Form->input('qty',['type'=>'number','label'=>'Qty','readonly'=>true,'autocomplete'=>'off', 'min' => '1','value'=>$thisLineItem['qty']]);
else 
echo $this->Form->input('qty',['type'=>'number','label'=>'Qty','required'=>true,'autocomplete'=>'off', 'min' => '1','value'=>$thisLineItem['qty']]);

if(!isset($subaction) && !is_numeric($subaction)){
if($ordermode == 'workorder' && !isset($thisLineItem['so_line_number'])){
    
        $soLineNumberLists=array();
        foreach($soLineNumberList as $subclass){
            $soLineNumberLists[$subclass['line_number']]=$subclass['line_number'];
        }

    echo $this->Form->label('SOLineNumber');
   
    echo $this->Form->select('so_line_number',$soLineNumberLists,['required'=>false,'empty'=>'--Select SO Line Number--','value'=>$thisLineItem['so_line_number']]);
}elseif($ordermode == 'workorder'){
    echo $this->Form->label('SOLineNumber');
     $soLineNumberLists=array();
        foreach($soLineNumberList as $subclass){
            $soLineNumberLists[$subclass['line_number']]=$subclass['line_number'];
        }

    echo $this->Form->select('so_line_number',$soLineNumberLists,['disabled'=>true,'empty'=>'--Select SO Line Number--','value'=>$thisLineItem['so_line_number']]);
}
}


echo $this->Form->input('customer_id',['type'=>'hidden','value'=> $quoteData['customer_id']]);

echo "<div class=\"input select\">";
echo $this->Form->label('Units of Measure');
if(isset($thisLineItem['unit'])){
    $thisUnitVal=$thisLineItem['unit'];
}else{
    $thisUnitVal='each';
}
if($ordermode == 'workorder'&& isset($thisLineItem['unit']))
 echo $this->Form->select('unit_type',['dozen'=>'Dozen','each'=>'Each','lot'=>'Lot','package'=>'Package','pair'=>'Pair','set'=>'Set','yards'=>'Yards','box'=>'Box'],['readonly'=>true,'value'=>$thisUnitVal]);
else
 echo $this->Form->select('unit_type',['dozen'=>'Dozen','each'=>'Each','lot'=>'Lot','package'=>'Package','pair'=>'Pair','set'=>'Set','yards'=>'Yards','box'=>'Box'],['required'=>true,'value'=>$thisUnitVal]);
echo "</div>";

if($ordermode == 'workorder') {
    echo $this->Form->input('price',['label'=>'Price Per Unit','readonly'=>true,'autocomplete'=>'off','value'=>isset($thisLineItem['best_price']) ? $thisLineItem['best_price']: 0.00]);
}else 
{ echo $this->Form->input('price',['label'=>'Price Per Unit','required'=>true,'autocomplete'=>'off','value'=>$thisLineItem['best_price']]);
 }
echo "<fieldset id=\"lineitemimagewrap\"><legend>Line Item Image</legend>";

if($mode=='edit' && $lineitemmeta['image_method']=='upload'){
    $thisMethod='library';
}else{
    $thisMethod=$lineitemmeta['image_method'];
}

echo $this->Form->radio('image_method',['none'=>'No Image','library'=>'From Image Library','upload'=>'Upload New Image'],['required'=>true,'value'=>$thisMethod]);
echo $this->Form->input('libraryimageid',['type'=>'hidden','value'=>$lineitemmeta['libraryimageid']]);
echo "<div id=\"imagelibrarycontents\"";
if($mode=='edit' && ($lineitemmeta['image_method'] == 'library' || $lineitemmeta['image_method'] == 'upload')){
    echo " style=\"display:block;\"><ul>";
}else{
    echo " style=\"display:none;\"><ul>";
}

foreach($libraryimages as $image){
	echo "<li id=\"image".$image['id']."\"";
	if($image['id'] == $lineitemmeta['libraryimageid']){
	    echo " class=\"selectedimage\"";
	}
	echo "><img data-src=\"/img/library/".$image['filename']."\" onclick=\"setselectedlibraryimage(".$image['id'].")\" /></li>";
}
echo "</ul><div style=\"clear:both;\"></div></div>";
	
echo "<div id=\"imageuploadform\" style=\"display:none;\">";


echo $this->Form->input('imagefileupload',['label'=>'Image File','type'=>'file']);
echo $this->Form->input('save_to_library',['type'=>'checkbox','onchange'=>'changeUploadSaveSettings()','onclick'=>'changeUploadSaveSettings()']);
echo "<div style=\"clear:both;\"></div>";
echo "<div id=\"imageuploadsavetolibrarywrap\" style=\"display:none;\">";
echo $this->Form->input('image_title',['label'=>'Title this Image','autocomplete'=>'off']);
	
echo "<div class=\"input selectbox\"><label>Image Category</label>";
echo $this->Form->select('image_category',$allLibraryCats,['empty'=>'--Select Category--']);
echo "</div>";
	
echo $this->Form->input('image_tags',['autocomplete'=>'off']);
echo "</div>";
	
echo "</div>";
echo "</fieldset>";
$disabledPro ='';
if( $ordermode =='workorder' && isset($thisLineItem['fabrictype'])){
$disabledPro = "disabled";
}
echo "<fieldset id=\"fabricinfo\" $disabledPro><legend>Fabric Information</legend>";
if($ordermode == 'workorder' && isset($thisLineItem['fabrictype'])) {
       echo $this->Form->radio('fabric_type',['none'=>'No Fabric','existing'=>'Existing Fabric','typein'=>'Type-In Fabric'],['disabled'=>true,'value'=>$lineitemmeta['fabrictype']]);
} else {
    echo $this->Form->radio('fabric_type',['none'=>'No Fabric','existing'=>'Existing Fabric','typein'=>'Type-In Fabric'],['required'=>true,'value'=>$lineitemmeta['fabrictype']]);

}/*PPSA -36 start */echo "<div id=\"fabric-selector-none\"";
if(($mode=='edit' || $mode=='workorderedit') && $lineitemmeta['fabrictype'] == 'none'){
    echo " style=\"display:block;\">";
}else{
    if(!isset($lineitemmeta['fabrictype'])) {
        echo " style=\"display:block;\">";
    } else 
    echo " style=\"display:none;\">";
}
echo "</div>";
/*PPSA -36 end */
echo "<div id=\"fabric-selector-block\"";
if($mode=='edit' && $lineitemmeta['fabrictype'] == 'existing'){
    echo " style=\"display:block;\">";
}else{
    echo " style=\"display:none;\">";
}

/* PPSASCRUM-100: start */
// $fabricVendorNameSpacing = str_repeat("&nbsp;", 20);
// // echo "<p><label>Select a Fabric</label><select id=\"fabricname\" name=\"fabricname\" onchange=\"getfabriccoloroptions(this.value)\""; 
// echo "<p><label>Select a Fabric</label><select id=\"fabricname\" name=\"fabricname\" onchange=\"getfabriccoloroptions(this)\"";
// /* PPSASCRUM-100: end */
// if($ordermode == 'workorder'&& isset($thisLineItem['fabrictype'])) {
//     echo "diabled=\"true\"";
// }else echo ">";
// echo "<option value=\"0\" disabled>--Select A Fabric--</option>";
// foreach($fabrics as $fabric){
// 	//echo "<option value=\"".urlencode($fabric['fabric_name'])."\"";
// 	/* PPSASCRUM-100: start */
// // 	echo "<option value=\"".$fabric['fabric_name']."\"";
//     echo "<option value=\"".$fabric['fabric_name']."\" vendorId=\"".$fabric['vendors_id']."\"";
//     /* PPSASCRUM-100: end */
// 	if($mode=='edit' && $lineitemmeta['fabrictype'] == 'existing' && $fabric['fabric_name'] == $lineitemmeta['fabricname']){ echo " selected=\"selected\""; }
// 	/* PPSASCRUM-100: start */
// // 	echo ">".$fabric['fabric_name']."</option>";
//     echo ">".$fabric['fabric_name'] .$fabricVendorNameSpacing." [" . $fabric['vendor_name'] ."]</option>";
    /* PPSASCRUM-100: end */
// }
// echo "</select></p>";


/* PPSASCRUM-100: start */
echo "
    <label>Select a Fabric</label>
    <div class=\"custom-fabric-selection-dropdown\">
        <div class=\"current-custom-fabric-selection\" value=\"label\">
            --Select A Fabric--
            <span style=\"padding-bottom: 5px;\">⌵</span>
        </div>
        <div class=\"custom-fabric-dropdown-options\" id=\"custom-fabric-dropdown-options\">
        <div class=\"scroll-button up\" style=\"position: sticky; top: 0; z-index: 1; justify-content: center; height: 25px; padding-bottom: 23px; display: none\">⌃</div>
        <div id=\"label\" style=\"display: flex; justify-content: center; font-weight: 900; color: #A19FA4\"
            onclick=\"getfabriccoloroptions(event, '', 0, '')\">
            --Select A Fabric--
        </div>
        ";
foreach($fabrics as $fabric) {
    $fabricName = strval($fabric['fabric_name']);
    $vendorId = $fabric['vendors_id'];
    echo "
        <div
            id=\"" . $fabric['id'] . "\" vendorId=\"" . $vendorId . "\"
            onclick=\"getfabriccoloroptions(event,'" . str_replace("'", "\'", $fabricName) . "', " . $vendorId . ", '" . str_replace("'", "\'", $fabric['vendor_name']) . "')\"";
        if($mode=='edit' && $lineitemmeta['fabrictype'] == 'existing' && strtolower($fabric['fabric_name']) == strtolower($lineitemmeta['fabric_name']) && $vendorId == $lineitemmeta['vendors_id']){
            echo " selected=\"selected\">✓" . str_repeat('&nbsp;', 5) . "<span class=\"fabric-name\">" . $fabricName . "</span>";
        } else {
            echo "><span class=\"fabric-name\">" . $fabricName . "</span>";
        }
            
    echo "<span class=\"vendor-name\">[" . $fabric['vendor_name'] . "]</span>
        </div>";
}
echo "
        <div class=\"scroll-button down\" style=\"position: sticky; bottom: 0; z-index: 1; justify-content: center; height: 25px; padding-bottom: 16px;\">⌵</div>
        </div>
    </div>
    <div style=\"height: 38px;\"></div>
";

/* PPSASCRUM-100: end */


echo "<p id=\"colorselectionblock\"><label>Select a Fabric Color</label><select name=\"fabric_id_with_color\" id=\"fabricidwithcolor\""; 
if($ordermode == 'workorder' && isset($thisLineItem['fabrictype'])) {
    echo "diabled=\"true\"";
}else echo ">";
echo "<option value disabled>--Select A Color--</option>";
if($mode=='edit' && $lineitemmeta['fabrictype'] == 'existing'){
    foreach($thisFabricColors as $color){
        echo "<option value=\"".$color['id']."\"";
        if($color['id'] == $lineitemmeta['fabricid']){
            echo " selected=\"selected\"";
        }
        echo ">".$color['color']."</option>";
    }
}
echo "</select></p>";
echo "</div>";
/**PPSA-33 start **/
echo "<div id=\"fabric-selector-block-mom\"";
if(($mode=='edit') && $lineitemmeta['fabrictype'] == 'existing'){
    echo " style=\"display:block;\">";
}else{
    echo " style=\"display:none;\">";
}
$momcheckboxArr=['label'=>'MOM Fabric Special Cost p/yd','type'=>'checkbox','value'=>0];
if($mode=='edit' && isset($lineitemmeta['fabric-cost-per-yard-custom-value']) && !empty($lineitemmeta['fabric-cost-per-yard-custom-value'])){
    $momcheckboxArr['checked']=true;
}else{
    $momcheckboxArr['checked']=false;
}
echo $this->Form->input('mom-fabric',$momcheckboxArr);
echo $this->Form->input('fabric-cost-per-yard-custom-value',['type'=>'number','min'=>'0','step'=>'any','autocomplete'=>'off','value' => $lineitemmeta['fabric-cost-per-yard-custom-value']]);

echo "</div>";
/**PPSA-33 end **/
echo "<div id=\"fabric-manual-entry-block\"";
if($mode=='edit' && $lineitemmeta['fabrictype'] == 'typein'){
    echo " style=\"display:block;\">";
}else{
    echo " style=\"display:none;\">";
}
echo $this->Form->input('fabric_name',['label'=>'Fabric Name','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false,'value' => $lineitemmeta['fabric_name']]);
echo $this->Form->input('fabric_color',['label'=>'Fabric Color','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false,'value'=>$lineitemmeta['fabric_color']]);
echo "</div>";

$comcheckboxArr=['label'=>'COM Fabric','type'=>'checkbox','value'=>1];
if($mode=='edit' && isset($lineitemmeta['com-fabric']) && $lineitemmeta['com-fabric']=='1'){
    $comcheckboxArr['checked']=true;
}else{
    $comcheckboxArr['checked']=false;
}
echo $this->Form->input('com-fabric',$comcheckboxArr);

echo "</fieldset>";


echo "<fieldset id=\"optionalfields\"><legend>Optional Fields</legend>";
echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"background:transparent;\">
<tr>
<td width=\"50%\" valign=\"top\">";
echo $this->Form->input('cut-width',['label'=>'Cut Width','autocomplete'=>'off', 'placeholder' => 'If Applicable','value'=>$lineitemmeta['cut-width']]);
echo "</td>
<td width=\"50%\" valign=\"top\">";
echo $this->Form->input('finished-width',['label'=>'Finished Width','autocomplete'=>'off', 'placeholder' => 'If Applicable','value'=>$lineitemmeta['finished-width']]);
echo "</td>
</tr>
<tr>
<td width=\"50%\" valign=\"top\">";
echo $this->Form->input('finished-length',['label'=>'Finished Length','autocomplete'=>'off', 'placeholder' => 'If Applicable','value'=>$lineitemmeta['finished-length']]);
echo "</td>
<td width=\"50%\" valign=\"top\">";
echo $this->Form->input('yds-per-unit',['label'=>'Yards Per Unit','autocomplete'=>'off', 'placeholder' => 'If Applicable','value'=>$lineitemmeta['yds-per-unit']]);
echo "</td>
</tr>
<tr>
<td width=\"50%\" valign=\"top\">";
	/**PPSASCRUM-201 start **/
echo $this->Form->input('quoteType',['type'=>'hidden','value'=>$quoteData['type_id']]); //PPSASCRUM-201

 $typeID=false;
if($quoteData['type_id'] == 1){
    $typeID=true;
    
}
echo $this->Form->input('location',['label'=>'Location / Room Number','autocomplete'=>'off', 'placeholder' => 'If Applicable','value'=>$thisLineItem['room_number'],'required'=>$typeID,]);
echo "</td>
<td width=\"50%\" valign=\"top\"><label>Vendor</label>";
echo $this->Form->select('vendors_id',$vendorsList,['empty'=>'--Select Vendor--','value'=>$lineitemmeta['vendors_id']]);
echo "</td>
</tr>
<tr>
<td width=\"50%\" valign=\"top\">&nbsp;</td>
<td width=\"50%\" valign=\"top\">";
//total yds field
if(isset($lineitemmeta['total-yds']) && strlen(trim($lineitemmeta['total-yds'])) > 0){
	$totalydsval=$lineitemmeta['total-yds'];
}else{
	$totalydsval='';
}
echo $this->Form->input('total-yds',['label'=>'Total Fab Yards','readonly'=>true,'value'=>$totalydsval]);
/*PPSA-33*/echo "<div id=\"specialplay\" style=\"color: red;text-align: center; padding: 10px;
        font-size: 16px; FONT-WEIGHT: BOLDER; display:none\"> Special Cost p/yd at play </div>";/*PPSA-33*/
echo "</td>
</tr>
</table>";
echo "</fieldset>";


echo "<div>";
echo "<button type=\"button\" id=\"cancelbutton\" style=\"float:left; padding:5px 5px 5px 5px;\">Cancel</button>";
if($mode=='edit'){
    echo $this->Form->submit('Save Changes',['disabled'=>true]);
}else{
    echo $this->Form->submit('Add To Quote',['disabled'=>true]);
}
echo "<div style=\"clear:both;\"></div></div>";

echo $this->Form->end();
echo "<br><br><Br>";

echo "<script>
//PPSASCRUM-260 start
 $('form').submit(function(){
   console.log('before');
   $('div.submit input[type=submit]').prop('disabled',true).val('Processing...'); 
   $(this).attr('action', '');
   clearInterval(checkSubmittable); // stop the interval
   console.log('after');
});
 //PPSASCRUM-260 end
 
function checkSubmittable(){
    
    //determine if all REQUIRED fields are set
    var missingCount=0;
    
    if($('#line-item-title').val() == ''){
        missingCount++;
        $('#line-item-title').addClass('stillneeded');
    }else{
        $('#line-item-title').removeClass('stillneeded');
    }
    
    
    if($('select[name=product_subclass]').val() == ''){
        missingCount++;
        $('select[name=product_subclass]').addClass('stillneeded');
    }else{
        $('select[name=product_subclass]').removeClass('stillneeded');
    }
    
    
    if($('#qty').val() == ''){
        missingCount++;
        $('#qty').addClass('stillneeded');
    }else{
        $('#qty').removeClass('stillneeded');
    }
    
    
    if($('#qty').val() != '' && $('#yds-per-unit').val() != ''){
        $('#total-yds').val( ( parseFloat($('#yds-per-unit').val()) * parseInt($('#qty').val()) ).toFixed(2) );
    }else{
        $('#total-yds').val('');
    }
    
    
    if($('#price').val() == '' ){
        missingCount++;
        $('#price').addClass('stillneeded');
    }else{
        $('#price').removeClass('stillneeded');
    }
    
    
    if(!$('#image-method-none').prop('checked') && !$('#image-method-library').prop('checked') && !$('#image-method-upload').prop('checked')){
        missingCount++;
        $('fieldset#lineitemimagewrap').addClass('stillneeded');
    }else{
        $('fieldset#lineitemimagewrap').removeClass('stillneeded');
    }
    
    
    if($('#image-method-library').prop('checked')){
        //make sure a Library Image has been selected
        
        if($('#libraryimageid').val() == '0'){
            missingCount++;
            $('#imagelibrarycontents').addClass('stillneeded');
        }else{
            $('#imagelibrarycontents').removeClass('stillneeded');
        }
        
    }else if($('#image-method-upload').prop('checked')){
        //make sure a image file has been selected in the upload field
        if($('input#imagefileupload').val() == ''){
            missingCount++;
            $('#imageuploadform').addClass('stillneeded');
        }else{
            $('#imageuploadform').removeClass('stillneeded');
        }
    }
    
    
    
    if(!$('#fabric-type-none').prop('checked') && !$('#fabric-type-existing').prop('checked') && !$('#fabric-type-typein').prop('checked')){
        missingCount++;
        $('fieldset#fabricinfo').addClass('stillneeded');
    }else{
        $('fieldset#fabricinfo').removeClass('stillneeded');
    }
    
    
    if($('#fabric-type-existing').prop('checked')){
        
        
        /* PPSASCRUM-100: start */
        let fabricValue = $('div.current-custom-fabric-selection').attr('value');
        // if($('#fabricname').val() == '' || $('#fabricname').val() == '0' || $('#fabricname').val() == 'undefined' || $('#fabricname').val() == null){
        if(fabricValue == 'label' || fabricValue == '' || fabricValue == '0' || fabricValue == 'undefined' || fabricValue == null){
            missingCount++;
            $('div.current-custom-fabric-selection').addClass('stillneeded');
        } else {
            $('div.current-custom-fabric-selection').removeClass('stillneeded');
        }
        /* PPSASCRUM-100: end */
        
        if($('#fabricidwithcolor').val() == '' || $('#fabricidwithcolor').val() == '0' || $('#fabricidwithcolor').val() == 'undefined' || $('#fabricidwithcolor').val() == null){
            missingCount++;
            $('#fabricidwithcolor').addClass('stillneeded');
        }else{
            $('#fabricidwithcolor').removeClass('stillneeded');
        }
        
        
    }else if($('#fabric-type-typein').prop('checked')){
        if($('#fabric-name').val() == ''){
            missingCount++;
            $('#fabric-name').addClass('stillneeded');
        }else{
            $('#fabric-name').removeClass('stillneeded');
        }
        
        if($('#fabric-color').val() == ''){
            missingCount++;
            $('#fabric-color').addClass('stillneeded');
        }else{
            $('#fabric-color').removeClass('stillneeded');
        }
    }else if($('#fabric-type-none').prop('checked')){
        $('#fabricidwithcolor').removeClass('stillneeded');
        $('#fabricname').removeClass('stillneeded');
    }
    
    
    //if all complete, enable the Add To Quote button
   /* if(missingCount == 0){
        $('div.submit input[type=submit]').prop('disabled',false);
    }else{
        $('div.submit input[type=submit]').prop('disabled',true);
    } */
        
    /*PPSA-33 start */
        if ($('#com-fabric').prop('checked')) {
              //missingCount--;
              /*PPSASCRUM-96: start */
            if (!$('#fabricidwithcolor').hasClass('stillneeded')) {
              missingCount--;
            }
            /*PPSASCRUM-96: end */
              $('#mom-fabric').hide('fast');
              $('#mom-fabric').prop('checked', false);
              $('#fabric-cost-per-yard-custom-value').hide('fast');
              $('#fabric-cost-per-yard-custom-value').prop('required', false);
              if (
                $('#fabric-cost-per-yard-custom-value').val().length > 0 &&
                !$('#mom-fabric').prop('checked') && !$('#fabricidwithcolor').hasClass('stillneeded')
              ) {
                missingCount--;
              }
              $('#fabric-cost-per-yard-custom-value').val('');
              $('label[for=\"mom-fabric\"]').hide('fast');
              $('label[for=\"fabric-cost-per-yard-custom-value\"]').hide('fast');
        } 
        if (($('#fabric-type-existing').prop('checked')) && !($('label[for=\"mom-fabric\"]').is(':visible')) && (!$('#com-fabric').prop('checked'))) 
        {
                 $('#mom-fabric').show();
                  $('#fabric-cost-per-yard-custom-value').show();
                  console.log(1);
                  $('#fabric-cost-per-yard-custom-value').attr('required', 'required');
                  $('label[for=\"mom-fabric\"]').show('fast');
                  $('label[for=\"fabric-cost-per-yard-custom-value\"]').show('fast');
                  if ($('#fabric-cost-per-yard-custom-value').val() == '') {
                    missingCount++;
                  }
        }
        
        if ($('#mom-fabric').prop('checked')) {
              if ($('#fabric-cost-per-yard-custom-value').val() == '') {
                missingCount++;
                $('#fabric-cost-per-yard-custom-value').addClass('stillneeded');
                $('#specialplay').show('fast');
              } else {
                $('#fabric-cost-per-yard-custom-value').removeClass('stillneeded');
                $('#specialplay').show('fast');
              }
        } else {
              $('#fabric-cost-per-yard-custom-value').removeClass('stillneeded');
              $('#fabric-cost-per-yard-custom-value').prop('required', false);
              $('#fabric-cost-per-yard-custom-value').attr('value', '');
              $('#fabric-cost-per-yard-custom-value').val('');
            
              $('#specialplay').hide('fast');
              //missingCount--;
              /*PPSASCRUM-96: start */
              if (!$('#fabricidwithcolor').hasClass('stillneeded')) {
                missingCount--;
              }
              /*PPSASCRUM-96: end */
        }
        if (!$('#fabric-type-existing').prop('checked')) {
              $('#mom-fabric').hide('fast');
              $('#mom-fabric').prop('checked', false);
              $('#fabric-cost-per-yard-custom-value').hide('fast');
              $('#fabric-cost-per-yard-custom-value').prop('required', false);
              /*PPSASCRUM-96: start */
              if (!$('#fabric-cost-per-yard-custom-value').val().length > 0) 
                missingCount++;
              /*PPSASCRUM-96: end */
              $('#fabric-cost-per-yard-custom-value').val('');
              $('label[for=\"mom-fabric\"]').hide('fast');
              $('label[for=\"fabric-cost-per-yard-custom-value\"]').hide('fast');
        }

     /*PPSASCRUM-201 start
    
    */
    if(($('#quotetype').val() == 1)  && $('#location').val() == ''){
        missingCount++;
        $('#location').addClass('stillneeded');
    }else{
        $('#location').removeClass('stillneeded');
    }
    /*PPSASCRUM-201 end */
    //if all complete, enable the Add To Quote button
    if(missingCount >0){
        $('div.submit input[type=submit]').prop('disabled',true);
    }else{
    console.log($('.formAction').attr('action'));
        if($('.formAction').attr('action').length > 0)$('div.submit input[type=submit]').prop('disabled',false); //PPSASCRUM-261
    }
    /*PPSA-33 end */
    
    
}

$(function(){

    $('#cancelbutton').click(function(){
        history.go(-1);
    });
    
    $('input,select,textarea').change(function(){
       checkSubmittable(); 
    });
    
    setInterval('checkSubmittable()',400);
    
    $('#fabric-type-existing').click(function(){
		$('#fabric-selector-block').show('fast');
		$('#fabric-manual-entry-block').hide('fast');
			/**PPSA-33 start **/ $('#fabric-selector-block-mom').show('fast');	/**PPSA-33 end **/
		   /*PPSA -36 start */$('#fabric-selector-none').hide('fast');/*PPSA -36 end */

	});

	$('#fabric-type-typein').click(function(){
		$('#fabric-manual-entry-block').show('fast');
		$('#fabric-selector-block').hide('fast');
			/**PPSA-33 start **/ $('#fabric-selector-block-mom').hide('fast');	/**PPSA-33 end **/
			/*PPSA -36 start */$('#fabric-selector-none').hide('fast');/*PPSA -36 end */

	});
	
	$('#fabric-type-none').click(function(){
		$('#fabric-manual-entry-block').hide('fast');
		$('#fabric-selector-block').hide('fast');	
			/**PPSA-33 start **/ $('#fabric-selector-block-mom').hide('fast');	/**PPSA-33 end **/
			/*PPSA -36 start */$('#fabric-selector-none').show('fast');/*PPSA -36 end */

	});

	$('#image-method-none').click(function(){
		$('#imagelibrarycontents').hide('fast');
		$('#imageuploadform').hide('fast');
	});

	$('#image-method-library').click(function(){
		$('#imagelibrarycontents').show('fast',function(){
		    $('#imagelibrarycontents img').lazy({appendScroll:'#imagelibrarycontents'});
		});
		$('#imageuploadform').hide('fast');
		
	});

	$('#image-method-upload').click(function(){
		$('#imagelibrarycontents').hide('fast');
		$('#imageuploadform').show('fast');
	});
	
	/* PPSASCRUM-100: start */

    const fabricSelectionDropdown = $('div.current-custom-fabric-selection');
    const fabricDropdownOptions = $('div.custom-fabric-dropdown-options');

    if($('#fabric-type-existing').prop('checked')) {";
        if ($mode == 'edit' && $lineitemmeta['fabrictype'] == 'existing') {
            echo "
            fabricSelectionDropdown.html(
                $('div[selected=\"selected\"]').html().replaceAll('✓', '')
                    .replaceAll('&nbsp;', '').replace(/\\n\s+$/gm, '').concat('<span>⌵</span>')
            );
            $('div.current-custom-fabric-selection').removeClass('stillneeded');
            fabricSelectionDropdown.attr('value', $('div[selected=\"selected\"]').attr('id'));
            ";
        }
    echo "}
    
    $(window).click(function(event) {
        if (!fabricSelectionDropdown.is(event.target) && fabricSelectionDropdown.has(event.target).length === 0) {
            fabricDropdownOptions.hide();
        } else {
            fabricDropdownOptions.toggle();
        }
    });

    const dropdownOptions = document.getElementById('custom-fabric-dropdown-options');
    const scrollUp = document.querySelector('.scroll-button.up');
    const scrollDown = document.querySelector('.scroll-button.down');

    scrollUp.addEventListener('mouseenter', () => {
        scrollInterval = setInterval(() => {
            dropdownOptions.scrollTop -= 32; // Scroll up

            if (dropdownOptions.scrollTop > 60) {
                $('div.scroll-button.up').css('display','block');
            } else {
                $('div.scroll-button.up').css('display','none');
            }

        }, 100);
    });

    scrollUp.addEventListener('mouseleave', () => {
        clearInterval(scrollInterval);
    });

    scrollDown.addEventListener('mouseenter', () => {
        scrollInterval = setInterval(() => {
            dropdownOptions.scrollTop += 32; // Scroll down

            if (dropdownOptions.scrollTop > 60) {
                $('div.scroll-button.up').css('display','block');
            } else {
                $('div.scroll-button.up').css('display','none');
            }

        }, 100);
    });

    scrollDown.addEventListener('mouseleave', () => {
        clearInterval(scrollInterval);
    });

    $('div.current-custom-fabric-selection').click(function() {
        setTimeout(function() {
            if ($('div.custom-fabric-dropdown-options').is(':visible')) {
                document.getElementById('custom-fabric-dropdown-options').scrollTop = document.getElementById($('div.current-custom-fabric-selection').attr('value')).offsetTop - 36;
            }   
        }, 1200);
    });
    
    window.addEventListener('keydown', (event) => {
          let eventObj = event;
          if (
            $('div.custom-fabric-dropdown-options').is(':visible')
          ) {
            const currentTime = Date.now();
            const timeSinceLastTyped = currentTime - lastTypedTime;

            if (
              lastTypedTime == 0 ||
              (searchTerm.length < 4 && timeSinceLastTyped < 1500)
            ) {
              if (event.key.length == 1) {
                searchTerm += event.key.toLowerCase();
              }
              console.log('Current searchTerm: ', searchTerm);
              search(searchTerm, eventObj);
              lastTypedTime = currentTime; // Update last typed time
              console.log('Done search for: ', searchTerm);
            } else {
              if (searchTerm != '' && searchTerm.length == 4) {
                // searchTerm += event.key.toLowerCase();
                searchTerm = '';
                if (event.key.length == 1) {
                    searchTerm = event.key.toLowerCase();
                }
              }
              else {
                if (event.key.length == 1) {
                    searchTerm += event.key.toLowerCase();
                }
              }
              console.log('Searching in else for: ', searchTerm);
              search(searchTerm, eventObj);
              lastTypedTime = currentTime; // Update last typed time
              console.log('Done search in else for: ', searchTerm);
            }
          }
          setInterval(function() {
            let currentTime = Date.now();
            if ((currentTime - lastTypedTime) > 1500) { searchTerm = ''; }
          }, 100);
        });
    /* PPSASCRUM-100: end */
    
    /* PPSASCRUM-371: start */
    if ('" . $ordermode . "' == 'workorder') {
        $('div.current-custom-fabric-selection').addClass('disable-custom-dropdown');
        $('select#fabricidwithcolor').attr('disabled', true);
    }
    /* PPSASCRUM-371: end */

});

	
/* PPSASCRUM-100: start */
let searchTerm = ''; // Initialize an empty string to store typed characters
let lastTypedTime = 0; // Store the last time a character was typed
let searchTimeout;

function search(searchTerm, event) {
    console.log('Searching for: ', searchTerm);

    const listItems = Array.from($('div.custom-fabric-dropdown-options div')).slice(1,-1);

    for (let item of listItems) {
        const itemText = item.textContent.toLowerCase();
        if (itemText.startsWith(searchTerm.toLowerCase())) {
            console.log('This is the matching option: ', itemText);
            // item.scrollIntoView(true);
			item.parentNode.scrollTop = item.offsetTop;
            event.preventDefault(); // Prevent default scrolling behavior
            break;
        }
    }
}

function getfabriccoloroptions(event, fabricName, vendorId, vendorName) {
    console.log('Selected fabric: ', event.target);
    if (event.target.id == 'label' && fabricName == '' && vendorId == 0) {
        console.log('label option clicked');
        event.preventDefault();
        event.stopPropagation();
        return;
    }

    const selectedFabricHtml = event.currentTarget.innerHTML;
    console.log('Clicked: ', selectedFabricHtml);

    Array.from(
        document.querySelector('div#custom-fabric-dropdown-options.custom-fabric-dropdown-options').children
    ).forEach((arg) => (arg.innerHTML = arg.innerHTML.replaceAll('✓', '').replaceAll('&nbsp;', '')));
    
    event.currentTarget.children[0].innerHTML =
        '✓' +
        '&nbsp;'.repeat(5) +
        event.currentTarget.children[0].innerHTML.replaceAll('✓', '').replaceAll('&nbsp;', '');

    const selected = document.querySelector('.current-custom-fabric-selection');
     
    selected.innerHTML = selectedFabricHtml.replaceAll('✓', '')
        .replaceAll('&nbsp;', '').replace(/\\n\s+$/gm, '').concat('<span>⌵</span>');

    document.getElementById('custom-fabric-dropdown-options').style.display = 'none';

    console.log('This is fabric name: ', fabricName);
    console.log('This is vendor id: ', vendorId);

    $('div.current-custom-fabric-selection').attr('value', fabricName);

	$.get('/quotes/getfabriccolors/'+fabricName+'/custom/".$quoteData['customer_id']."/false/false/'+vendorId,function(data){
    /* PPSASCRUM-100: end */
		$('#colorselectionblock').html(data);
	});
}

function setselectedlibraryimage(imageid){
	$('#libraryimageid').val(imageid);
	$('#imagelibrarycontents li').removeClass('selectedimage');
	$('li#image'+imageid).addClass('selectedimage');
}


</script>";

