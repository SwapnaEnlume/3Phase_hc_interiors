<!-- src/Template/Products/editalias.ctp -->

<script>
var allFabrics=<?php echo json_encode($allFabrics); ?>;
</script>

<h2 style="color:red; text-align: center">Are you sure you want to delete this Fabric Alias?</h2>

<?php
echo $this->Form->create(null,['type'=>'file']);

echo $this->Form->input('dodelete',['type'=>'hidden','value'=>'yes']);
echo "<div style=\"text-align:center;\">";
echo "<button type=\"submit\" style=\"display:inline-block; background:green; margin:0 30px 0 0;\">Yes, Delete Now</button>";
echo "<button type=\"button\" style=\"display:inline-block;\" onclick=\"location.replace('/products/fabricaliases/');\">No, Go Back</button>";
echo "</div>";

echo $this->Form->end();
?>
