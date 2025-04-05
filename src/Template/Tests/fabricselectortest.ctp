<?php
$quoteID=30548;
?>
<style>
  .ui-autocomplete-loading {
    background: white url("/img/ui-anim_basic_16x16.gif") right center no-repeat;
  }
</style>
  
<div style="width:700px; margin:0 auto;">

<h2>CUBICLE CURTAINS DEMO</h2>
<hr>

<fieldset id="calculatorfabric"><legend>Fabric Selection</legend>

<div class="ui-widget"><label>Search Fabrics:</label> <input type="text" name="fabric_name" /></div>

</fieldset>

<script>
$(function(){
    $('input[name=fabric_name]').autocomplete({
       source: function( request, response){
           $.ajax({
              'url': '/products/searchfabricsfield/cubicle-curtain/'+request.term,
              'dataType':'json',
              success: function(data){
                  response(data);
              }
           });
       },
       minLength: 2,
       select: function(event,ui){
           $.fancybox.showLoading();
           $.get('/quotes/getfabriccolorlist/'+encodeURIComponent(ui.item.value)+'/'+<?php echo $quoteID; ?>,function(response){
				$('#coloroptionwrap').html(response);
				$.fancybox.hideLoading();
			});
       }
    });
});
</script>
<fieldset>
    <legend>Color Selection</legend>
    <div id="coloroptionwrap">
        <em>Please search and choose a Fabric above.</em>
    </div>
</fieldset>

</div>