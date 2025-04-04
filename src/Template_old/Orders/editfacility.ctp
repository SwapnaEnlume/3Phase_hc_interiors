<!-- src/Template/Products/addfacility.ctp -->

<h3>Edit Facility:</h3>
<?php
echo $this->Form->create(null);
echo $this->Form->input('facility_code',['label'=>'Facility Code','required'=>true ,'value'=>$faciltiyData['facility_code']]);
/*
echo $this->Form->input('facility_name',['label'=>'Facility Name','required'=>true, 'value'=>$faciltiyData['facility_name']]);
echo "<div id=\"shipToselection\">Default Address";
echo $this->Form->select('default_address',$shipTooptions,['required'=>true,'empty'=>'--Select a ShipTo Address--','value'=>$faciltiyData['default_address']]);
echo "</div>";
*/
echo "<legend>Default Address </legend>";
echo "<a href=\"#\" id=\"new_address\"  style=\"display: block; height: 36px; color: white; width: 10%; text-align: center; padding: 6px 0px 0px; background: rgb(71, 1, 1) !important; float: right !important;\" id='new_address'>+ New Address </a>";

echo $this->Form->radio('new_or_existing', ['new'=>'Add a New Address','exisiting'=>'Pick an Existing Address']);
echo $this->Form->select('default_address',$shipTooptions,['required'=>false,'empty'=>'--Select address--','id'=>'default_address','value'=>$faciltiyData['default_address']]);
echo "<div class =\"defaultAddressselection\" id =\"defaultAddressselection\" >";
echo $this->Form->input('shipping_address_1',['label'=>'ShipTo Address -Line1','id'=>'shipping_address_1','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_address_2',['label'=>'ShipTo Address -Line2','id'=>'shipping_address_2','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_city',['label'=>'ShipTo City','id'=>'shipping_city','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_state',['label'=>'ShipTo State','id'=>'shipping_state' ,'readonly' => 'readonly' ]);
echo $this->Form->input('shipping_zipcode',['label'=>'ShipTo Zipcode','id'=>'shipping_zipcode' ,'readonly' => 'readonly']);

echo "</div>";

echo $this->Form->input('attention',['label'=>'(Facility)Attention','required'=>true,'value'=>$faciltiyData['attention']]);

echo "</div>";


echo $this->Form->button('Save Facility',['type'=>'submit']);

echo $this->Form->end();
?>
<script>
    $(function(){
        $('#default_address').show();
        $('#new_address').hide();
                $('#defaultAddressselection').hide();

        $('input[type=radio][name=new_or_existing][value=\"exisiting\"]').prop('checked', true);
        $('#default_address').val(<?php echo $faciltiyData['default_address'] ?>);
        <?php if(!empty($faciltiyData['default_address'])) {?>
        $.get('/orders/getShipAddressDetails/'+<?php echo $faciltiyData['default_address'] ?>,function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_zipcode').val(myArray[4]);
				$('#attentionship').val(myArray[5]);
				$('#defaultAddressselection').show();

		});
		<?php }?>
        

});

$('input[type=radio][name=new_or_existing]').change(function() {
    if (this.value == 'new') {
        $('#default_address').hide();
        $('#new_address').show();
                $('#defaultAddressselection').hide();

    }
    else if (this.value == 'exisiting') {
        $('#default_address').show();
        $('#new_address').hide();
                $('#defaultAddressselection').show();

    }

});


$("#new_address").on('click', function() {
        $.fancybox({
			'href':'/orders/markshipto',
			'type':'iframe',
			'autoSize':false,
			'width':680,
			'height':480,
			'modal':true,
			'beforeClose': function() {
				$('#defaultAddressselection').show();
			 },
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
    });
     $('select[name=default_address]').change(function() {
        	$('#defaultAddressselection').hide();
	$.get('/orders/getShipAddressDetails/'+$('#default_address').val(),function(data){
				const myArray = data.split(":")
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_zipcode').val(myArray[4]);
				$('#attentionship').val(myArray[5]);
				$('#defaultAddressselection').show();

		});
     });
</script>