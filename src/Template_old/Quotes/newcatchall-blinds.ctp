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
label[for=fabric-type-typein]{ display:inline-block; font-weight:normal !important; }

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

fieldset#dimensionfields input{ width:100%; margin-bottom:0; }
fieldset#dimensionfields td div.input{ width:100%; }

li.selectedimage{ position:relative; border:2px solid green !important; }
li.selectedimage:after{ content:''; width:36px; height:36px; background-image:url('/img/Ok-icon.png'); background-size:100% auto; position:absolute; bottom:5px; right:5px; z-index:55; }
#imageuploadform div.input{ width:98% !important; float:none !important; }
</style>
<?php
if($mode=='edit'){
    echo "<h3>Edit Blinds Catch-All Line Item</h3>";
}else{
    echo "<h3>Add Blinds Catch-All Line to Quote</h3>";
}
echo "<h5>Class: Hard Window Treatments</h5><hr>";
echo $this->Form->create(null,['type'=>'file']);
echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);


echo $this->Form->input('line_item_title',['label'=>'Line Item Title','required'=>true,'autocomplete'=>'off','value'=>$thisLineItem['title']]);

echo $this->Form->input('description',['required'=>false,'maxlength'=>300,'label'=>'Description (300 characters max)','value'=>$thisLineItem['description']]);

echo $this->Form->input('specs',['type'=>'textarea','placeholder'=>'Other details and specs for this custom line item','autocomplete'=>'off','value'=>$lineitemmeta['specs']]);
			
			
echo $this->Form->input('qty',['type'=>'number','label'=>'Qty','required'=>true,'autocomplete'=>'off', 'min' => '1','value'=>$thisLineItem['qty']]);

echo $this->Form->input('customer_id',['type'=>'hidden','value'=> $quoteData['customer_id']]);

echo "<div class=\"input select\">";
echo $this->Form->label('Units of Measure');
if(isset($thisLineItem['unit'])){
    $thisUnitVal=$thisLineItem['unit'];
}else{
    $thisUnitVal='each';
}
echo $this->Form->select('unit_type',['dozen'=>'Dozen','each'=>'Each','lot'=>'Lot','package'=>'Package','pair'=>'Pair','set'=>'Set','yards'=>'Yards'],['required'=>true,'value'=>$thisUnitVal]);
echo "</div>";

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

/*
echo "<fieldset id=\"fabricinfo\"><legend>Fabric Information</legend>";
echo $this->Form->radio('fabric_type',['none'=>'No Fabric','existing'=>'Existing Fabric','typein'=>'Type-In Fabric'],['required'=>true,'value'=>$lineitemmeta['fabrictype']]);
			
echo "<div id=\"fabric-selector-block\"";
if($mode=='edit' && $lineitemmeta['fabrictype'] == 'existing'){
    echo " style=\"display:block;\">";
}else{
    echo " style=\"display:none;\">";
}

echo "<p><label>Select a Fabric</label><select id=\"fabricname\" name=\"fabricname\" onchange=\"getfabriccoloroptions(this.value)\"><option value=\"0\" disabled>--Select A Fabric--</option>";
foreach($fabrics as $fabric){
	echo "<option value=\"".urlencode($fabric['fabric_name'])."\"";
	if($mode=='edit' && $lineitemmeta['fabrictype'] == 'existing' && $fabric['fabric_name'] == $lineitemmeta['fabric_name']){ echo " selected=\"selected\""; }
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
    $comcheckboxArr['checked']='checked';
}
echo $this->Form->input('com-fabric',$comcheckboxArr);

echo "</fieldset>";
*/

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


echo "<fieldset id=\"blindsfields\"><legend>Inputs</legend>";

    echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"background:transparent;\">
    <tr id=\"inputmountfield\">
    <td width=\"34%\">Mount</td>
    <td width=\"33%\"><label><input type=\"radio\"";
    if($mode=='edit'){
        if($lineitemmeta['blind-mount'] == 'in'){
            echo " checked=\"checked\"";
        }
    }
    echo " name=\"blind-mount\" value=\"in\" id=\"blindmountin\" /> IN</label></td>
    <td width=\"33%\"><label><input type=\"radio\"";
    if($mode=='edit'){
        if($lineitemmeta['blind-mount'] == 'out'){
            echo " checked=\"checked\"";
        }
    }
    echo " name=\"blind-mount\" value=\"out\" id=\"blindmountout\" /> OUT</label></td>
    </tr>
    <tr id=\"inputholddownbracketsfield\">
    <td width=\"34%\">Hold down brackets?</td>
    <td width=\"33%\"><label><input type=\"radio\"";
    if($mode=='edit'){
        if($lineitemmeta['blind-holddownbrackets'] == 'yes'){
            echo " checked=\"checked\"";
        }
    }
    echo " name=\"blind-holddownbrackets\" value=\"yes\" id=\"blindholddownbracketsyes\" /> Yes</label></td>
    <td width=\"33%\"><label><input type=\"radio\"";
    if($mode=='edit'){
        if($lineitemmeta['blind-holddownbrackets'] == 'no'){
            echo " checked=\"checked\"";
        }
    }
    echo " name=\"blind-holddownbrackets\" value=\"no\" id=\"blindholddownbracketsno\"  /> No</label></td>
    </tr>
    <tr id=\"inputvalancefield\">
    <td width=\"34%\">Valance</td>
    <td width=\"33%\"><label><input type=\"radio\"";
    if($mode=='edit'){
        if($lineitemmeta['blind-valance'] == 'yes'){
            echo " checked=\"checked\"";
        }
    }
    echo " name=\"blind-valance\" value=\"yes\" id=\"blindvalanceyes\" /> Yes</label></td>
    <td width=\"33%\"><label><input type=\"radio\"";
    if($mode=='edit'){
        if($lineitemmeta['blind-valance'] == 'no'){
            echo " checked=\"checked\"";
        }
    }
    echo " name=\"blind-valance\" value=\"no\" id=\"blindvalanceno\" /> No</label></td>
    </tr>
    <tr id=\"inputvalancereturnsfield\">
    <td width=\"34%\">Valance Returns</td>
    <td width=\"33%\"><label><input type=\"radio\"";
    if($mode=='edit'){
        if($lineitemmeta['blind-valancereturns'] == 'yes'){
            echo " checked=\"checked\"";
        }
    }
    echo " name=\"blind-valancereturns\" value=\"yes\" id=\"blindvalancereturnsyes\" /> Yes</label></td>
    <td width=\"33%\"><label><input type=\"radio\"";
    if($mode=='edit'){
        if($lineitemmeta['blind-valancereturns'] == 'no'){
            echo " checked=\"checked\"";
        }
    }
    echo " name=\"blind-valancereturns\" value=\"no\" id=\"blindvalancereturnsno\" /> No</label></td>
    </tr>
    </table>";

echo "</fieldset>";


echo "<fieldset id=\"slatfields\"><legend>Slat Size</legend>";
echo $this->Form->select('slat-size',['0.5"','1"','2"','2.5"','3"','3.5"'],['empty'=>'--Select Size','required'=>true,'value'=>$lineitemmeta['slat-size']]);
echo "</fieldset>";


echo "<fieldset id=\"dimensionfields\"><legend>Dimensions</legend>";

    echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"background:transparent;\">
    <tr>
    <td width=\"50%\">";
    echo $this->Form->input('width',['label'=>'Width','required'=>true,'value'=>$lineitemmeta['width']]);
    echo "</td>
    <td width=\"50%\">";
    echo $this->Form->input('length',['label'=>'Length','required'=>true,'value'=>$lineitemmeta['length']]);
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
    
    
    /*
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
    */
    
    if($('select[name=\'slat-size\']').val() == ''){
        missingCount++;
        $('fieldset#slatfields').addClass('stillneeded');
    }else{
        $('fieldset#slatfields').removeClass('stillneeded');
    }
    
    
    
    var inputsMissing=0;
    
    if(!$('#blindmountin').prop('checked') && !$('#blindmountout').prop('checked')){
        missingCount++;
        $('tr#inputmountfield').addClass('stillneeded');
        $('fieldset#blindsfields').addClass('stillneeded');
        inputsMissing++;
    }else{
        $('tr#inputmountfield').removeClass('stillneeded');
    }
    
    if(!$('#blindholddownbracketsyes').prop('checked') && !$('#blindholddownbracketsno').prop('checked')){
        missingCount++;
        $('tr#inputholddownbracketsfield').addClass('stillneeded');
        $('fieldset#blindsfields').addClass('stillneeded');
        inputsMissing++;
    }else{
        $('tr#inputholddownbracketsfield').removeClass('stillneeded');
    }
    
    if(!$('#blindvalanceyes').prop('checked')  && !$('#blindvalanceno').prop('checked')){
        missingCount++;
        $('tr#inputvalancefield').addClass('stillneeded');
        $('fieldset#blindsfields').addClass('stillneeded');
        inputsMissing++;
    }else{
        $('tr#inputvalancefield').removeClass('stillneeded');
    }
    
    if(!$('#blindvalancereturnsyes').prop('checked') && !$('#blindvalancereturnsno').prop('checked')){
        missingCount++;
        $('tr#inputvalancereturnsfield').addClass('stillneeded');
        $('fieldset#blindsfields').addClass('stillneeded');
        inputsMissing++;
    }else{
        $('tr#inputvalancereturnsfield').removeClass('stillneeded');
    }
    

    if(inputsMissing==0){
        $('fieldset#blindsfields').removeClass('stillneeded');
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
    
    
    
    //if all complete, enable the Add To Quote button
    if(missingCount == 0){
        $('div.submit input[type=submit]').prop('disabled',false);
    }else{
        $('div.submit input[type=submit]').prop('disabled',true);
    }
    
    
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
	});

	$('#fabric-type-typein').click(function(){
		$('#fabric-manual-entry-block').show('fast');
		$('#fabric-selector-block').hide('fast');
	});
	
	$('#fabric-type-none').click(function(){
		$('#fabric-manual-entry-block').hide('fast');
		$('#fabric-selector-block').hide('fast');					
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