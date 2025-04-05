<!-- src/Template/Libraries/image.ctp -->
<div id="headingblock">
<h1 class="pageheading">Add New Image to Image Library</h1>
</div>

<?php
echo $this->Form->create(null,['type'=>'file', 'class'=>'myForm']);

echo $this->Form->input('image_title',['type'=>'text','required'=>true]);

echo "<div class=\"input selectbox required\"><label>Category</label>";
echo $this->Form->select('categories',$allcategories,['empty'=>'Select Category','required'=>true]);
echo "</div>";

/*PPSASCRUM-185: start*/
// echo $this->Form->input('imagefile',['type'=>'file','required'=>true]);
echo $this->Form->input('imagefile',['type'=>'file','required'=>true, 'accept'=>'image/jpeg, image/png, image/gif']);
/*PPSASCRUM-185: end*/


echo $this->Form->input('tags',['required'=>false]);

echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Inactive'],['required'=>true]);


echo $this->Form->submit('Add To Library');

echo $this->Form->end();

?>
<!-- PPSASCRUM-185: start -->
<script>
$('form').submit(function(){
             $('div.submit input[type=submit]').prop('disabled',true).val('Processing...'); 
});
    $(function() {
        $('#imagefile').change(function() {
            var errorCount = 0;
            var imageExtension = $('#imagefile').val().split('.');
            imageExtension = imageExtension[imageExtension.length - 1];
            var validExtensions = ['JPEG', 'JPG', 'PNG', 'GIF'];
            validExtensions = [...validExtensions, ...validExtensions.map(extension => extension.toLowerCase())];
            if (!validExtensions.includes(imageExtension)) {
                $('#imagefile').val('');
                errorCount++;
            }
            $('div.input.file.required').find('div.error').remove();
            if (errorCount > 0) {
                event.target.value = '';
                $('div.input.file.required').append(`<div class="error">Image should be either of JPG, GIF, or PNG file format</div>`);
            }
        });
    });
  
  
</script>
<!-- PPSASCRUM-185: end -->
<style>
form{ max-width:600px; margin:0 auto; }
.submit input{ font-size:large; font-weight:bold; color:#FFF; border:1px solid #000; background:#26337A; padding:10px 15px; }
/* PPSASCRUM-185: start */
div.error{ display:inline-block; margin:0 0 20px 10px; background:#F9F3B7; border:2px solid red; color:red; padding:10px; font-size:12px; font-weight:bold; }
/* PPSASCRUM-185: end */
</style>