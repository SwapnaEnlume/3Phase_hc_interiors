<!-- src/Template/Libraries/editimage.ctp -->
<h3>Edit Library Image:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo "<div style=\"padding:10px 25px;\" id=\"currentimage\">";

echo "<div><img src=\"/img/library/".$imageData['filename']."\" style=\"max-width:500px; height:auto; margin:20px 0\" /></div>";
echo "<div style=\"padding:10px 0;\"><a href=\"javascript:changeImage();\">Change Image</a></div>";
echo "</div>";
echo "<div id=\"newimage\" style=\"padding:10px 25px; visibility:hidden; height:0px;\">";
/*PPSASCRUM-83: start*/
echo $this->Form->input('new_image',['label'=>'New Image','required'=>false,'type'=>'file','accept'=>'image/jpeg, image/png, image/gif']);
/*PPSASCRUM-83: end*/
echo "<div><a href=\"javascript:unchangeImage();\">Cancel Change</a></div>";
/*PPSASCRUM-83: start*/
echo "<div class=\"input\" id=\"imageselection\"></div>";
/*PPSASCRUM-83: end*/
echo "</div>";

echo $this->Form->input('image_title',['label'=>'Name of this Library Image','required'=>true,'value'=>$imageData['image_title']]);



echo "<div class=\"input selectbox required\"><label>Category</label>";
echo $this->Form->select('categories',$allcategories,['empty'=>'Select Category','value'=>$imageData['categories'],'required'=>true]);
echo "</div>";

echo $this->Form->input('tags',['label'=>'Image Tags','required'=>false,'value'=>$imageData['tags']]);

echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['required'=>true,'value'=>$imageData['status']]);

echo $this->Form->button('Save Changes',['type'=>'submit']);

echo $this->Form->end();
?>
<script>
$('form').submit(function(){
             $('div.submit input[type=submit]').prop('disabled',true).val('Processing...'); 
});
function changeImage(){
//	$('#currentimage').css({'visibility':'hidden','height':'0px'});
	$('#newimage').css({'visibility':'visible','height':'auto'});
}

function unchangeImage(){
	$('#currentimage').css({'visibility':'visible','height':'auto'});
	$('#newimage').css({'visibility':'hidden','height':'0px'});
	/*PPSASCRUM-83: start*/
	$('#imageselection').find('div.error').remove();
	$('button[type=submit]').prop('disabled', false);
	/*PPSASCRUM-83: end*/
}

/*PPSASCRUM-83: start*/
$('input[type=file][name=new_image]').change(function (event) {
        if (this.val != '') {
            var errorCount = 0;
            var errorMessage = 'Image ';
            var fileObj = event.target.files[0];
           
            var fileExtension = fileObj.name.split('.').pop();
            /*PPSASCRUM-185: start*/
            var validImageExtensions = ['jpg', 'jpeg', 'gif', 'png'];
			/*PPSASCRUM-185: end*/
            /*if (!validImageExtensions.includes(fileExtension)) {
                errorCount++;
                // if (errorCount > 1) errorMessage += ' and ';
                errorMessage += 'should be either of JPG, GIF, or PNG file format';
            }*/
            $('#imageselection').find('div.error').remove();
            if (errorCount > 0) {
                event.target.value = '';
                $('#imageselection').append(`<div class="error">${errorMessage}</div>`);
                $('button[type=submit]').prop('disabled', true);
            } else {
				$('button[type=submit]').prop('disabled', false);
			}

        }
    });
    /*PPSASCRUM-83: end*/
    
</script>
<style>
div.input > label{ font-weight:bold; font-size:16px; }
fieldset input[type=text]{ width:90px; height:22px; font-size:12px; padding:2px; }
fieldset label{ font-weight:normal !important; font-size:12px !important; }
fieldset legend a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }
	
#currentimage img{ max-width:250px; height:auto; }
/* PPSASCRUM-185: start */
div.error{ display:inline-block; margin:0 0 20px 10px; background:#F9F3B7; border:2px solid red; color:red; padding:10px; font-size:12px; font-weight:bold; }
/* PPSASCRUM-185: end */
</style>