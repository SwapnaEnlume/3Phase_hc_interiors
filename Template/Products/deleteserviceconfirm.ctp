<!-- src/Template/Products/deleteserviceconfirm.ctp -->

<h3>Are you sure you want to delete this service?</h3>
<?php
echo $this->Form->create(null);
echo $this->Form->input('process',['type'=>'hidden','value'=>'yes']);
echo "<dl>
<dt>Service Title:</dt><dd>".$serviceData['title']."</dd>
<dt>Service Description:</dt><dd>".$serviceData['description']."</dd>
<dt>Service Image/Thumbnail:</dt><dd><img src=\"/files/services/".$serviceData['id']."/".$serviceData['image_file']."\" style=\"width:150px;height:auto;\" /></dd>
<dt>Service Price:</dt><dd>\$".number_format($serviceData['price'],2,'.',',')."</dd>
</dl>";

echo "<div style=\"margin:15px 0; text-align:center;\">";
echo $this->Form->submit('Yes, Delete Now');
echo $this->Form->button('No, Go Back',['type'=>'button','onclick'=>'history.go(-1)']);

echo "<div style=\"clear:both;\"></div>
</div>";

echo $this->Form->end();
?>
<style>
h3{ text-align:center; font-weight:bold; color:red; }
dl{ width:300px; margin:0 auto; }
div.submit{ display:inline-block; }
input[type=submit]{ background:#106F12; color:#FFF; border:0; font-size:x-large; padding:10px 15px; font-weight:bold; }
button[type=button]{ margin:0 0 0 20px; font-size:large; color:#FFF; background:#9A0B0D; border:0; padding:10px 15px; }
</style>