<!-- PPSASCRUM-287: start -->

<h3>Add New Commonly Used Global Notes Categories:</h3>
<div id="add-gn-cateogory-form">
<?php

echo $this->Form->create(null);

echo $this->Form->input('global-note-category-name', ['label' => 'Global Note Category Name', 'required' => true]);

/* 
echo "<label>Status</label>";
echo $this->Form->radio('global-note-cateogry-status', ['Active' => 'Active', 'Inactive' => 'Inactive']);
*/

echo $this->Form->button('Add This Global Note Category', ['type' => 'submit']);

echo $this->Form->end();

?>
</div>
<style>
    div#add-gn-cateogory-form {margin-top: 30px;}
    button[type=submit] {margin-top: 20px;}
</style>
<!-- PPSASCRUM-287: end -->