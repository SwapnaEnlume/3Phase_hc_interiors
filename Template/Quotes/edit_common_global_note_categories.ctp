<!-- PPSASCRUM-287: start -->

<h3>Edit Commonly Used Global Notes Categories:</h3>

<div id="add-gn-cateogory-form">


<?php

echo $this->Form->create(null);


if (isset($thisGlobalNoteCategory['global_note_category_name']) && strlen($thisGlobalNoteCategory['global_note_category_name']) > 0) {
    $globalNotesCategoryName = $thisGlobalNoteCategory['global_note_category_name'];
} else {
    $globalNotesCategoryName = '';
}
echo $this->Form->input('global-note-category-name', ['label' => 'Global Note Category Name', 'required' => true, 'value' => $globalNotesCategoryName]);


/* 
if (isset($thisGlobalNoteCategory['status']) && strlen($thisGlobalNoteCategory['status']) > 0 && in_array($thisGlobalNoteCategory['status'], ['Active', 'Inactive'])) {
    $globalNotesCategoryStatus = $thisGlobalNoteCategory['status'];
} else {
    $globalNotesCategoryStatus = '';
}
echo "<label>Status</label>";
echo $this->Form->radio('global-note-cateogry-status', ['Active' => 'Active', 'Inactive' => 'Inactive'], ['value' => $globalNotesCategoryStatus]);
*/


echo $this->Form->button('Save Changes', ['type' => 'submit']);


echo $this->Form->end();

?>


</div>

<style>
    div#add-gn-cateogory-form {margin-top: 30px;}
    button[type=submit] {margin-top: 20px;}
</style>

<!-- PPSASCRUM-287: end -->