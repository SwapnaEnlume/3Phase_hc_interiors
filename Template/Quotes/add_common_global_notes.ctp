<!-- PPSASCRUM-287: start -->

<h3>Add New Commonly Used Global Notes:</h3>
<div id="add-gn-form">
<?php

echo $this->Form->create(null);

echo $this->Form->input('global-note', ['label' => 'Global Note', 'rows' => 2, 'required' => true, 'value' => $globalNote]);

echo "<div class=\"required\"><label for=\"global-note-category\">Global Note Category</label>";
echo $this->Form->select('global-note-category', $globalNoteCategories, ['empty' => '--Select Lining--', 'required' => true]);
echo "</div>";

/* 
echo "<label>Status</label>";
echo $this->Form->radio('global-note-cateogry-status', ['Active' => 'Active', 'Inactive' => 'Inactive']);
*/

echo $this->Form->button('Add This Global Note Category', ['type' => 'submit']);

echo $this->Form->end();

?>
</div>
<script>
    $(function() {
        $($('select[name=global-note-category] option')[0]).attr('disabled', 'disabled');
    });
</script>
<style>
    div#add-gn-form {margin-top: 30px;}
    button[type=submit] {margin-top: 20px;}
    .required > label:after { content: ' *'; color: #C3232D; }
</style>
<!-- PPSASCRUM-287: end -->