<!-- PPSASCRUM-287: start -->

<h3>Edit Commonly Used Global Notes:</h3>
<div id="edit-gn-form">
<?php


echo $this->Form->create(null);


if (isset($thisGlobalNote['global_note_comment_text']) && strlen($thisGlobalNote['global_note_comment_text']) > 0) {
    $globalNote = $thisGlobalNote['global_note_comment_text'];
} else {
    $globalNote = '';
}
echo $this->Form->input('global-note', ['label' => 'Global Note', 'rows' => 2, 'required' => true, 'value' => $globalNote]);


echo "<div class=\"required\"><label for=\"global-note-category\">Global Note Category</label>";
if (isset($thisGlobalNote['category_id']) && intval($thisGlobalNote['category_id']) > 0) {
    $globalNotesCategory = $thisGlobalNote['category_id'];
} else {
    $globalNotesCategory = '';
}
echo $this->Form->select('global-note-category', $globalNoteCategories, ['empty' => '--Select Lining--', 'label' => 'Global Note Category', 'required' => true, 'value' => $globalNotesCategory]);
echo "</div>";

/* 
echo "<label>Status</label>";
echo $this->Form->radio('global-note-cateogry-status', ['Active' => 'Active', 'Inactive' => 'Inactive']);
*/

echo $this->Form->button('Save Changes', ['type' => 'submit']);

echo $this->Form->end();

?>
</div>
<script>
    $(function() {
        $($('select[name=global-note-category] option')[0]).attr('disabled', 'disabled');
    });
</script>
<style>
    div#edit-gn-form {margin-top: 30px;}
    button[type=submit] {margin-top: 20px;}
    .required > label:after { content: ' *'; color: #C3232D; }
</style>
<!-- PPSASCRUM-287: end -->