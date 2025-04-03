<!-- src/Template/Quotes/canceleditordermode.ctp -->

<h1>Cancel Edit 
<?php
if ($ordermode == "order") echo "Sales";
else if ($ordermode == "workorder") echo "Work";
?>
 Order</h1>
<h4>All Changes will be lost</h4>
<h1>Please Confirm</h1>
<?php
echo $this->Form->create(false);
echo $this->Form->input('process',['type'=>'hidden','value'=>'yes']);
echo $this->Form->input('ordermode',['type'=>'hidden','value'=>$ordermode]);
echo "<div style=\"text-align:center;\">";
echo $this->Form->submit('Cancel Edit order');
echo "&nbsp;&nbsp;";
echo $this->Form->button("Don't Cancel Edit order",['type'=>'button', 'onclick' => 'history.go(-1)','class'=>'cancleButton']);
echo "</div>";
echo $this->Form->end();
?>
<style>
form{ width:700px; margin:20px auto; }
h1,h4{ text-align: center; }
div.submit{ display:inline-block; }
div.submit input[type=submit]{ background:green; padding:10px; font-size:16px; color:#FFF; font-weight:bold; border:1px solid #000; }
button[type=button]{ background:red; color:#FFF; padding:5px; font-size:13px; font-weight:bold; display:inline-block; border:1px solid #660000; }
</style>
<script>
<!--PPSASCRUM-284 -->
    $('form').submit(function(){
             $('div.submit input[type=submit]').prop('disabled',true).val('Processing...'); 
              const buttons = document.querySelectorAll('button');
              buttons.forEach(button => button.disabled = true);
    });
    <!--PPSASCRUM-284 -->
</script>