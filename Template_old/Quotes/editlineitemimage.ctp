<!-- src/Template/Quotes/editlineitemimage.ctp -->
<?php
echo "<h2>Edit Line Item Image</h2>";

echo $this->Form->create(null,['type'=>'file']);

if($currentImage != 'None'){
	echo "<fieldset id=\"currentimage\"><legend>Current Line Item Image:</legend>";

	foreach($libraryimages as $image){
		if($image['id'] == $currentImage){
			echo "<p><img src=\"/img/library/".$image['filename']."\" style=\"max-width:130px; width:auto; max-height:130px; height:auto;\" /></p>";
		}
	}

	echo "</fieldset>";
}

echo "<fieldset><legend>New Image Source</legend>";

echo $this->Form->radio('image_method',['none'=>'No Image','library'=>'From Image Library','upload'=>'Upload New Image'],['required'=>true]);
echo $this->Form->input('newlibraryimageid',['type'=>'hidden','value'=>'0']);

echo "<div id=\"imagelibrarycontents\" style=\"display:none;\"><ul>";
foreach($libraryimages as $image){
	if($image['status'] == 'Active'){
		echo "<li id=\"image".$image['id']."\"><img src=\"/img/library/".$image['filename']."\" onclick=\"setselectedlibraryimage(".$image['id'].")\" /></li>";
	}
}
echo "</ul><div style=\"clear:both;\"></div></div>";


echo "<div id=\"imageuploadform\" style=\"display:none;\">";
echo $this->Form->input('imagefileupload',['label'=>'Image File','type'=>'file','options'=>['accept'=>'image/jpeg,image/jpg,image/png,image/gif']]);
echo $this->Form->input('save_to_library',['type'=>'checkbox','onchange'=>'changeUploadSaveSettings()','onclick'=>'changeUploadSaveSettings()']);
echo "<div id=\"imageuploadsavetolibrarywrap\" style=\"display:none;\">";
echo $this->Form->input('image_title',['label'=>'Title this Image']);

echo "<div class=\"input selectbox\"><label>Image Category</label>";
echo $this->Form->select('image_category',$allLibraryCats,['empty'=>'--Select Category--']);
echo "</div>";

echo $this->Form->input('image_tags');
echo "</div>";

echo "</div>";

echo "</fieldset>";


echo "<div style=\"padding-top:20px;\">";
echo $this->Form->submit('Save Changes');
echo '<button type="button" id="cancelbutton">Cancel</button>';
echo "<div style=\"clear:both;\"></div></div>";
	
echo $this->Form->end();
?>


<style>
body{ font-family:'Helvetica',Arial,sans-serif; }
textarea{ width:90%; padding:2%; }
form div.submit{ float:left; display:inline-block; }

form .submit input[type=submit]{ background:#26337A; color:#FFF; padding:10px 15px; font-weight:bold; border:1px solid #000; font-size:14px; cursor:pointer; }
#cancelbutton{ float:right; }
	
fieldset{ border:0; }
fieldset#currentimage{ text-align:center; }
	
fieldset legend{ font-weight:bold; color:#26337A; }
	
label[for=fabric-type-none]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
label[for=fabric-type-existing]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
label[for=fabric-type-typein]{ display:inline-block; font-weight:normal !important; }

#imageuploadform{ padding:10px; }
#imagelibrarycontents{ width:100%; height:310px; background:#FFF; padding:10px; overflow-y:scroll; overflow-x:none; }
#imagelibrarycontents ul{ list-style:none; margin:0px; padding:0px; }
#imagelibrarycontents ul li img{ width:auto; max-width:100%; height:85px; max-height:85px;  cursor:pointer; }
#imagelibrarycontents ul li{ width:45%; float:left; margin:15px 2%; text-align:center; border:2px solid white; }
#imagelibrarycontents ul li:hover{ border:2px solid green; }

label[for=image-method-none]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
label[for=image-method-library]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
label[for=image-method-upload]{display:inline-block; font-weight:normal !important; }

li.selectedimage{ position:relative; border:2px solid green !important; }
li.selectedimage:after{ content:''; width:36px; height:36px; background-image:url('/img/Ok-icon.png'); background-size:100% auto; position:absolute; bottom:5px; right:5px; z-index:55; }
	
label[for=imagefileupload]{ font-weight:bold; margin-right:10px; }

#imageuploadform div.input{ width:98% !important; float:none !important; padding:10px; }
</style>

<script>
$(function(){
	$('#cancelbutton').click(function(){
		parent.$.fancybox.close();
	});
	
	<?php if($currentImage == 'None'){ ?>
	$('#image-method-none').prop('checked',true);
	<?php } ?>
	
	$('#image-method-none').click(function(){
		$('#imagelibrarycontents').hide('fast');
		$('#imageuploadform').hide('fast');
		$('fieldset#currentimage').hide('fast');
	});

	$('#image-method-library').click(function(){
		$('fieldset#currentimage').hide('fast');
		$('#imagelibrarycontents').show('fast');
		$('#imageuploadform').hide('fast');
	});

	$('#image-method-upload').click(function(){
		$('fieldset#currentimage').hide('fast');
		$('#imagelibrarycontents').hide('fast');
		$('#imageuploadform').show('fast');
	});
});
	
function setselectedlibraryimage(imageid){
	$('#imagelibrarycontents ul li').removeClass('selectedimage');
	$('#newlibraryimageid').val(imageid);
	$('li#image'+imageid).addClass('selectedimage');
}
	
	
function changeUploadSaveSettings(){
	if($('input#save-to-library').is(':checked')){
		$('#imageuploadsavetolibrarywrap').show('fast');
	}else{
		$('#imageuploadsavetolibrarywrap').hide('fast');
	}
}
</script>