<style>
fieldset#optionalfields input{ width:100%; margin-bottom:0; }
fieldset#optionalfields td div.input{ width:100%; }

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


li.selectedimage{ position:relative; border:2px solid green !important; }
li.selectedimage:after{ content:''; width:36px; height:36px; background-image:url('/img/Ok-icon.png'); background-size:100% auto; position:absolute; bottom:5px; right:5px; z-index:55; }
#imageuploadform div.input{ width:98% !important; float:none !important; }
</style>
<?php
if($mode=='edit'){
    echo "<h3>Edit Bedding Catch-All Line Item</h3><hr>";
}else{
    echo "<h3>Add Bedding Catch-All Line to Quote</h3><hr>";
}

echo $this->Form->create(null,['type'=>'file']);
echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);


echo $this->Form->input('line_item_title',['label'=>'Line Item Title','required'=>true,'autocomplete'=>'off','value'=>$thisLineItem['title']]);

$subclassList=array();
foreach($subclasses as $subclass){
    $subclassList[$subclass['id']]=$subclass['subclass_name'];
}

echo "<div class=\"input select required\">";
echo $this->Form->label('Bedding Sub-Class');
if($ordermode == 'workorder')
echo $this->Form->select('product_subclass',$subclassList,['disabled'=>true,'empty'=>'--Select Sub-Class--','value'=>$thisLineItem['product_subclass']]);
else
echo $this->Form->select('product_subclass',$subclassList,['required'=>true,'empty'=>'--Select Sub-Class--','value'=>$thisLineItem['product_subclass']]);

echo "</div>";


echo $this->Form->input('description',['required'=>false,'maxlength'=>300,'label'=>'Description (300 characters max)','value'=>$thisLineItem['description']]);

echo $this->Form->input('specs',['type'=>'textarea','placeholder'=>'Other details and specs for this custom line item','autocomplete'=>'off','value'=>$lineitemmeta['specs']]);
			
if($ordermode == 'workorder')
		echo $this->Form->input('qty',['type'=>'number','label'=>'Qty','readonly'=>true,'autocomplete'=>'off', 'min' => '1','value'=>$thisLineItem['qty']]);
else
echo $this->Form->input('qty',['type'=>'number','label'=>'Qty','required'=>true,'autocomplete'=>'off', 'min' => '1','value'=>$thisLineItem['qty']]);

echo $this->Form->input('customer_id',['type'=>'hidden','value'=> $quoteData['customer_id']]);

echo "<div class=\"input select\">";
echo $this->Form->label('Units of Measure');
if(isset($thisLineItem['unit'])){
    $thisUnitVal=$thisLineItem['unit'];
}else{
    $thisUnitVal='each';
}
if($ordermode == 'workorder')
echo $this->Form->select('unit_type',['dozen'=>'Dozen','each'=>'Each','lot'=>'Lot','package'=>'Package','pair'=>'Pair','set'=>'Set','yards'=>'Yards'],['readonly'=>true,'value'=>$thisUnitVal]);
else
echo $this->Form->select('unit_type',['dozen'=>'Dozen','each'=>'Each','lot'=>'Lot','package'=>'Package','pair'=>'Pair','set'=>'Set','yards'=>'Yards'],['required'=>true,'value'=>$thisUnitVal]);

echo "</div>";
if($ordermode == 'workorder')
echo $this->Form->input('price',['label'=>'Price Per Unit','readonly'=>true,'autocomplete'=>'off','value'=>$thisLineItem['best_price']]);
else
echo $this->Form->input('price',['label'=>'Price Per Unit','required'=>true,'autocomplete'=>'off','value'=>$thisLineItem['best_price']]);

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

echo "<fieldset id=\"fabricinfo\"><legend>Fabric Information</legend>";
if($ordermode == 'workorder')
echo $this->Form->radio('fabric_type',['none'=>'No Fabric','existing'=>'Existing Fabric','typein'=>'Type-In Fabric'],['readonly'=>true,'value'=>$lineitemmeta['fabrictype']]);
else
echo $this->Form->radio('fabric_type',['none'=>'No Fabric','existing'=>'Existing Fabric','typein'=>'Type-In Fabric'],['required'=>true,'value'=>$lineitemmeta['fabrictype']]);

/*PPSA -36 start */echo "<div id=\"fabric-selector-none\"";
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
if($ordermode == 'workorder'){
    echo " style=\"display:block;\">";
}else{
    if($mode=='edit' && $lineitemmeta['fabrictype'] == 'existing'){
        echo " style=\"display:block;\">";
    }else{
        echo " style=\"display:none;\">";
    }
}



echo "<p><label>Select a Fabric</label><select id=\"fabricname\" name=\"fabricname\" onchange=\"getfabriccoloroptions(this.value)\""; 
if($ordermode == 'workorder'){
    echo " style=\"display:block;\"";
}
echo "><option value=\"0\" disabled>--Select A Fabric--</option>";
foreach($fabrics as $fabric){
	echo "<option value=\"".urlencode($fabric['fabric_name'])."\"";
	if($mode=='edit' && $lineitemmeta['fabrictype'] == 'existing' && $fabric['fabric_name'] == $lineitemmeta['fabricname']){ echo " selected=\"selected\""; }
	echo ">".$fabric['fabric_name']."</option>";
}
echo "</select></p>";
echo "<p id=\"colorselectionblock\"><label>Select a Fabric Color</label><select name=\"fabric_id_with_color\" id=\"fabricidwithcolor\"><option value disabled>--Select A Color--</option>";
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
if($ordermode == 'workorder'){
    echo " style=\"display:block;\">";
} else {
if(($mode=='edit') && $lineitemmeta['fabrictype'] == 'existing'){
    echo " style=\"display:block;\">";
}else{
    echo " style=\"display:none;\">";
}
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
echo $this->Form->input('location',['label'=>'Location / Room Number','autocomplete'=>'off', 'placeholder' => 'If Applicable','value'=>$thisLineItem['room_number']]);
echo "</td>
<td width=\"50%\" valign=\"top\"><label>Vendor</label>";
echo $this->Form->select('vendors_id',$vendorsList,['empty'=>'--Select Vendor--','value'=>$lineitemmeta['vendors_id']]);
echo "</td>
</tr>
</table>";
echo "</fieldset>";



echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
<tr>
<td width=\"50%\" valign=\"top\" style=\"padding:15px;\">
    <h4>Inputs</h4>";
    
    //railroaded field
    if(isset($lineitemmeta['railroaded']) && $lineitemmeta['railroaded']=='1'){
	    $railroadedChecked=true;
    }else{
	    if($fabricData['railroaded']=='1'){
		    $railroadedChecked=true;
	    }else{
		    $railroadedChecked=false;
	    }
    }
    echo $this->Form->input('railroaded',['type'=>'checkbox','label'=>'Railroaded','checked'=>$railroadedChecked]);
    
    
    //quilted field
    if(isset($lineitemmeta['quilted']) && $lineitemmeta['quilted']=='1'){
    	$quiltedChecked=true;
    }else{
    	$quiltedChecked=false;
    }
    echo $this->Form->input('quilted',['type'=>'checkbox','label'=>'Quilted','checked'=>$quiltedChecked]);
    
    
    //quilting pattern field
    echo "<div id=\"quiltpatternwrap\" class=\"input select\">
    <label>Quilting Pattern</label>
    <select name=\"quilting-pattern\" id=\"quilting-pattern\"><option value=\"\">--Select Quilt Pattern--</option>";
    $quiltingPatterns=explode("|",$settings['quilting_patterns']);
    foreach($quiltingPatterns as $pattern){
    	echo "<option value=\"".htmlspecialchars($pattern)."\"";
    	if(isset($lineitemmeta['quilting-pattern']) && htmlspecialchars_decode($lineitemmeta['quilting-pattern']) == $pattern){
    		echo " selected";
    	}
    	echo ">".$pattern."</option>\n";
    }
    echo "</select>
    </div>";
    
    
    //style field
    if(isset($lineitemmeta['style']) && strlen(trim($lineitemmeta['style'])) >0){
	    $styleval=array('value'=>$lineitemmeta['style'],'required'=>false);
    }else{
	    $styleval=array('empty'=>'--Select Style--','required'=>false);
    }
    echo "<div class=\"input\">";
    echo $this->Form->label('Style');
    echo $this->Form->select('style',['Throw'=>'Throw','Fitted'=>'Fitted', 'Other'=>'Other'],$styleval);
    echo "</div>";


    echo "<h5>Dimensions</h5>";
    
    //Finished Width field *required
    if(isset($lineitemmeta['width']) && is_numeric($lineitemmeta['width'])){
    	$widthval=$lineitemmeta['width'];
    }else{
    	$widthval='';
    }
    echo $this->Form->input('width',['type'=>'number','min'=>'0','value'=>$widthval,'label'=>'FINISHED WIDTH', 'required'=>true]);


    
    //Finished Length field *required
    if(isset($lineitemmeta['length']) && is_numeric($lineitemmeta['length'])){
    	$lengthval=$lineitemmeta['length'];
    }else{
    	$lengthval='';
    }
    echo $this->Form->input('length',['type'=>'number','min'=>'0','value'=>$lengthval,'label'=>'FINISHED LENGTH','required'=>true]);


    
    //finished drop field *optional
    echo "<div id=\"assumeddropwidthwrap\"";
    if($lineitemmeta['railroaded'] == '1' || ($fabricid != 'custom' && $fabricData['vertical_repeat'] == '1')){
    	echo " style=\"display:none;\"";
    }
    echo ">";
    if(isset($lineitemmeta['assumed-drop-width']) && is_numeric($lineitemmeta['assumed-drop-width'])){
    	$assumeddropwidthval=$lineitemmeta['assumed-drop-width'];
    }else{
    	$assumeddropwidthval='';
    }
    echo $this->Form->input('assumed-drop-width',['type'=>'number','step'=>'any','value'=>$assumeddropwidthval,'label'=>'Finished drop']);
    echo "</div>";
    
    
    //mattress width field *optional
    if(isset($lineitemmeta['custom-top-width-mattress-w']) && is_numeric($lineitemmeta['custom-top-width-mattress-w'])){
    	$customtopwidthmattresswval=$lineitemmeta['custom-top-width-mattress-w'];
    }else{
    	$customtopwidthmattresswval='';
    }
    echo $this->Form->input('custom-top-width-mattress-w',['type'=>'number','min'=>'0','value'=>$customtopwidthmattresswval,'label'=>'Mattress Width']);
    
    
    echo "</td>
    <td width=\"50%\" valign=\"top\" bgcolor=\"#EEEEEE\" style=\"padding:15px;\">
        <h4>Outputs</h4>";
        //top cut field
        if(isset($lineitemmeta['top-cut']) && strlen(trim($lineitemmeta['top-cut'])) > 0){
        	$topcutval=$lineitemmeta['top-cut'];
        }else{
        	$topcutval='';
        }
        echo $this->Form->input('top-cut',['label'=>'Top (Cut)','value'=>$topcutval]);
        
        
        //drop cut field
        if(isset($lineitemmeta['drop']) && strlen(trim($lineitemmeta['drop'])) > 0){
        	$dropval=$lineitemmeta['drop'];
        }else{
        	$dropval='';
        }
        echo $this->Form->input('drop',['label'=>'Drop (Cut)','value'=>$dropval]);
        

        //layout field
        if(isset($lineitemmeta['layout']) && strlen(trim($lineitemmeta['layout'])) > 0){
        	$layoutval=$lineitemmeta['layout'];
        }else{
        	$layoutval='';
        }
        echo $this->Form->input('layout',['label'=>'Layout','value'=>$layoutval]);
        
        
        //cl field
        if(isset($lineitemmeta['cl']) && strlen(trim($lineitemmeta['cl'])) > 0){
        	$clval=$lineitemmeta['cl'];
        }else{
        	$clval='';
        }
        echo $this->Form->input('cl',['style'=>'width:58% !important;','label'=>'CL','value'=>$clval]);
        
        
        //total widths field
        if(isset($lineitemmeta['total-widths']) && strlen(trim($lineitemmeta['total-widths'])) > 0){
        	$totalwidthsval=$lineitemmeta['total-widths'];
        }else{
        	$totalwidthsval='';
        }
        echo $this->Form->input('total-widths',['label'=>'Total widths','value'=>$totalwidthsval]);
        	
        	
        //yds per bs field
        if(isset($lineitemmeta['yds-per-unit']) && strlen(trim($lineitemmeta['yds-per-unit'])) > 0){
        	$ydspbsval=$lineitemmeta['yds-per-unit'];
        }else{
        	$ydspbsval='';
        }
        echo $this->Form->input('yds-per-unit',['label'=>'Yds EA','value'=>$ydspbsval, 'required'=>true]);
        	
        	
        //total yds field
        if(isset($lineitemmeta['total-yds']) && strlen(trim($lineitemmeta['total-yds'])) > 0){
        	$totalydsval=$lineitemmeta['total-yds'];
        }else{
        	$totalydsval='';
        }
        echo $this->Form->input('total-yds',['label'=>'Total Yards','readonly'=>true,'value'=>$totalydsval]);
        
        /*PPSA-33*/echo "<div id=\"specialplay\" style=\"color: red;text-align: center; padding: 10px;
        font-size: 16px; FONT-WEIGHT: BOLDER; display:none\"> Special Cost p/yd at play </div>";/*PPSA-33*/
        
    echo "</td>
</tr>
</table>";

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
    
    
    
    if($('#price').val() == ''){
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
        
        
        if($('#fabricname').val() == '' || $('#fabricname').val() == '0' || $('#fabricname').val() == 'undefined' || $('#fabricname').val() == null){
            missingCount++;
            $('#fabricname').addClass('stillneeded');
        }else{
            $('#fabricname').removeClass('stillneeded');
        }
        
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
    }
    
    
    if($('#width').val() == ''){
        missingCount++;
        $('#width').addClass('stillneeded');
    }else{
        $('#width').removeClass('stillneeded');
    }
    
    
    if($('#length').val() == ''){
        missingCount++;
        $('#length').addClass('stillneeded');
    }else{
        $('#length').removeClass('stillneeded');
    }
    
    
    if($('#yds-per-unit').val() == ''){
        missingCount++;
        $('#yds-per-unit').addClass('stillneeded');
    }else{
        $('#yds-per-unit').removeClass('stillneeded');
    }
    
    
    //update any autocalc value fields
    if($('#qty').val() != '' && $('#yds-per-unit').val() != ''){
        $('#total-yds').val( ( parseFloat($('#yds-per-unit').val()) * parseInt($('#qty').val()) ).toFixed(2) );
    }else{
        $('#total-yds').val('');
    }
    
    
    //if all complete, enable the Add To Quote button
   /* if(missingCount == 0){
        $('div.submit input[type=submit]').prop('disabled',false);
    }else{
        $('div.submit input[type=submit]').prop('disabled',true);
    } */
    /*PPSA-33 start */
        if ($('#com-fabric').prop('checked')) {
              missingCount--;
              $('#mom-fabric').hide('fast');
              $('#mom-fabric').prop('checked', false);
              $('#fabric-cost-per-yard-custom-value').hide('fast');
              $('#fabric-cost-per-yard-custom-value').prop('required', false);
              if (
                $('#fabric-cost-per-yard-custom-value').val().length > 0 &&
                !$('#mom-fabric').prop('checked')
              ) {
                missingCount--;
              }
              $('#fabric-cost-per-yard-custom-value').val('');
              $('label[for=\"mom-fabric\"]').hide('fast');
              $('label[for=\"fabric-cost-per-yard-custom-value\"]').hide('fast');
        } 
        if (($('#fabric-type-existing').prop('checked')) 
          && !($('label[for=\"mom-fabric\"]').is(':visible')) 
              && (!$('#com-fabric').prop('checked'))) 
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
              missingCount--;
        }
        if (!$('#fabric-type-existing').prop('checked')) {
              $('#mom-fabric').hide('fast');
              $('#mom-fabric').prop('checked', false);
              $('#fabric-cost-per-yard-custom-value').hide('fast');
              $('#fabric-cost-per-yard-custom-value').prop('required', false);
              if ($('#fabric-cost-per-yard-custom-value').val().length > 0) {
                missingCount--;
              }
              $('#fabric-cost-per-yard-custom-value').val('');
              $('label[for=\"mom-fabric\"]').hide('fast');
              $('label[for=\"fabric-cost-per-yard-custom-value\"]').hide('fast');
        }

    
    //if all complete, enable the Add To Quote button
    if(missingCount >0){
        $('div.submit input[type=submit]').prop('disabled',true);
    }else{
        $('div.submit input[type=submit]').prop('disabled',false);
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

});

function getfabriccoloroptions(fabricname){
	$.get('/quotes/getfabriccolors/'+fabricname+'/custom/".$quoteData['customer_id']."',function(data){
		$('#colorselectionblock').html(data);
	});
}

function setselectedlibraryimage(imageid){
	$('#libraryimageid').val(imageid);
	$('#imagelibrarycontents li').removeClass('selectedimage');
	$('li#image'+imageid).addClass('selectedimage');
}
</script>";