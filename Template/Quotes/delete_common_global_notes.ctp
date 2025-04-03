<!-- PPSASCRUM-287: start -->
<!-- src/Template/Products/deletebs.ctp -->
<h2 style="color:red;">Are you sure you want to delete this Commonly Used Global Notes?</h2>
<h4>This cannot be undone</h4>
<?php
echo $this->Form->create(null);
echo $this->Form->input('process',['type'=>'hidden','value'=>'yes']);

echo $this->Form->submit('Yes, Delete Now');
echo $this->Form->button('No, Go Back',['type'=>'button', 'onClick'=>'navigateBack();']);

echo $this->Form->end();
?>
<script>
    function navigateBack() {
        history.back();
    }
</script>
<!-- PPSASCRUM-287: end -->