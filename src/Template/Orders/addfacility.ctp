<!-- src/Template/Products/addfacility.ctp -->
<!-- changes for PPSASCRUM-79 -->
<style>
    #recipientForm input.notvalid,#recipientForm select.notvalid{ border:1px solid red; }
</style>
<div id="recipientForm">
<!-- changes for PPSASCRUM-79 -->
<h3>Create New Recipient:</h3>
<?php
echo $this->Form->create(null);
echo $this->Form->input('facility_name',['label'=>'Recipient Name','required'=>true]);

echo $this->Form->input('facility_code',['label'=>'Recipient Chain','required'=>true, 'default'=>'N/A']);
echo $this->Form->input('attention',['label'=>'(Recipient)Attention','required'=>false]);

echo "<legend>Default Address </legend>";
echo "<a href=\"#\" id=\"new_address\"  style=\"display: block; height: 36px; color: white; width: 20%; text-align: center; padding: 6px 0px 0px; background: rgb(71, 1, 1) !important; float: right !important;\" id='new_address'>+ Add New Ship-to Address </a>";

echo $this->Form->radio('new_or_existing', ['new'=>'Add a New Address','exisiting'=>'Pick an Existing Address']);
//echo $this->Form->select('default_address',$shipTooptions,['required'=>false,'empty'=>'--Select address--','id'=>'default_address']);

echo $this->Form->input('addressSelected',['type'=>'hidden','id'=>'addressSelected','required'=>false]);
//	echo '<input type="text" name="default_address" id="default_address"/>';
// changes for PPSASCRUM-79
echo '<input type="text" name="default_address" id="default_address" required/>';
echo "<label id='existingAddressUnselectedMsg' style='color: rgb(255, 0, 0); display: none; margin-top: -5px;'></label>";
// changes for PPSASCRUM-79
				
			//	echo "</fieldset>";
				
				echo "<script>
                    $(function(){
                        $('input[name=default_address]').autocomplete({
                           source: function( request, response){
                               $.ajax({
                                  'url': '/orders/searchAddressfield/'+escapeTextFieldforURL(request.term),
                                  'dataType':'json',
                                  success: function(data){
                                      response(data);
                                      // changes for PPSASCRUM-79
                                    $('#existingAddressUnselectedMsg').empty();
                                    if ($('#default_address').val() == request.term && Object.keys(data).length > 0) {
                                        $('#existingAddressUnselectedMsg').append(\"Please select an address matching <b>\" + request.term + \"</b> from the search results\").show();
                                    }
                                  }
                               });
                           },
                           minLength: 2,
                           select: function(event,ui){
                               //$.fancybox.showLoading();
                               //var spiltvalue = ui.item.value.split(',');
                               //$.get('/orders/getShipAddressNameDetails/'+spiltvalue[0],function(data){
                               var spiltvalue = ui.item.value.split(' :,: ');
                               //$.get('/orders/getShipAddressNameDetails/'+escapeTextFieldforURL(spiltvalue[0]),function(data){ //PPSASCRUM-79 
                               $.get('/orders/getShipToAddressDetails/'+escapeTextFieldforURL(spiltvalue[spiltvalue.length - 1]),function(data){ //PPSASCRUM-79 
                               $('#addressFeilds').show();
                            				const myArray2 = data.split(' :|: ')
                            				 $('#default_address').val(spiltvalue[0]);
                            				$('#shipping_address_1').val(myArray2[0]);
                            				$('#shipping_address_2').val(myArray2[1]);
                            				$('#shipping_city').val(myArray2[2]);
                            				$('#shipping_state').val(myArray2[3]);
                            				$('#shipping_country').val(myArray2[4]);
                                			$('#shipping_zipcode').val(myArray2[5]);
                                			$('#attentionship').val(myArray2[6]);
                                			$('#ship_to_name').val(myArray2[7]);
                                			$('#addressSelected').val(myArray2[8]); //PPSASCRUM-79 
                                			$('#default_address').show();
                                			$('#defaultAddressselection').show();
                                			
                                			// changes for PPSASCRUM-79
                                            $('#existingAddressUnselectedMsg').hide();
                                            $('#default_address').removeClass('notvalid');
                                            
                                            // changes for PPSASCRUM-79
                            		});
    			                    
    			                                        			//	$.fancybox.hideLoading();

                    			
                        }
                    });
                    });
                    </script>";
                    
echo "<div class =\"defaultAddressselection\" id =\"defaultAddressselection\" >";
echo $this->Form->input('ship_to_name',['label'=>'Ship-To Name','id'=>'ship_to_name','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_address_1',['label'=>'ShipTo Address -Line1','id'=>'shipping_address_1','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_address_2',['label'=>'ShipTo Address -Line2','id'=>'shipping_address_2','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_city',['label'=>'ShipTo City','id'=>'shipping_city','readonly' => 'readonly' ]);

echo $this->Form->input('shipping_state',['label'=>'ShipTo State','id'=>'shipping_state' ,'readonly' => 'readonly' ]);
echo $this->Form->input('shipping_zipcode',['label'=>'ShipTo Zipcode','id'=>'shipping_zipcode' ,'readonly' => 'readonly']);

echo "</div>";

echo $this->Form->button('Add This Recipient',['type'=>'submit','style'=>"float: right;margin: 82px;margin-top: 0px;"]);

echo $this->Form->end();

?>
</div>
<script>
    $(function(){
        $('#default_address').hide();
        $('#new_address').hide();
        $('#defaultAddressselection').hide();
        // changes for PPSASCRUM-79
       $('form').submit(function() {
            if ($('input[type=radio][name=new_or_existing]:checked').val() == 'exisiting' && $('#defaultAddressselection').is(':hidden')) {
                return false;
            }
            return true;
        });
        // changes for PPSASCRUM-79

});

$('input[type=radio][name=new_or_existing]').change(function() {
    if (this.value == 'new') {
        $('#default_address').hide();
        $('#new_address').show();
         $('#defaultAddressselection').hide();
         // changes for PPSASCRUM-79
        $('#default_address').val('');
        $('#existingAddressUnselectedMsg').hide();
        // changes for PPSASCRUM-79
    }
    else if (this.value == 'exisiting') {
        $('#default_address').show();
        $('#new_address').hide();
         $('#defaultAddressselection').hide();
         // changes for PPSASCRUM-79
        $('#default_address').removeClass('notvalid').addClass('notvalid');
        // changes for PPSASCRUM-79
    }

});

// changes for PPSASCRUM-79
$('#default_address').change(function() {
   if ($(this).val() == '') {
    $('#existingAddressUnselectedMsg').empty();
} else if ($('#ui-id-1').is(":hidden") && $('#defaultAddressselection').is(":hidden") && $('#existingAddressUnselectedMsg')[0].innerText == '') {
    $('#existingAddressUnselectedMsg').append("<b>" + $(this).val() + "</b> does not exist").show();
}
if (($('#existingAddressUnselectedMsg')[0].innerText != '' && !$('#existingAddressUnselectedMsg')[0].innerText.includes(' ' + $(this).val() + ' '))) {
    $('#existingAddressUnselectedMsg').empty();
    if ($('#ui-id-1').is(":hidden") && $('#defaultAddressselection').is(":hidden")) {
        $('#existingAddressUnselectedMsg').append("<b>" + $(this).val() + "</b> does not exist").show();
    }
}
        
});
// changes for PPSASCRUM-79

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
				const myArray = data.split(' :,: ')
				$('#shipping_address_1').val(myArray[0]);
				$('#shipping_address_2').val(myArray[1]);
				$('#shipping_city').val(myArray[2]);
				$('#shipping_state').val(myArray[3]);
				$('#shipping_country').val(myArray[4]);
				$('#shipping_zipcode').val(myArray[5]);
				$('#attentionship').val(myArray[6]);
				$('#ship_to_name').val(myArray[7]);
				
				$('#defaultAddressselection').show();

		});
});

/**PPSASCRUM-79 start **/
 function escapeTextFieldforURL(thetext){
        var output = thetext.replace(/\\/g, ":__bbbbslash__:");
	    output = output.replace(/\//g, ":__aaabslash__:");
	    output = output.replace('?',':__question__:',output);
	    output = output.replace(' ',':__space__:',output);
	    output = output.replace('#',':__pound__:',output);
	    output = output.replace('%',':__percentage__:',output);
        output = output.replace('&','__ampersand__',output);
		return output;
    }
    /**PPSASCRUM-79 end **/
</script>

<script>
//on enter keydown treat it as tab
$(function(){
    $(document).on('keydown', 'input:visible, select:visible, button:visible,feildset:visible, [type="radio"]:visible, [type="checkbox"]:visible, [tabindex]', function(e) {
            if (e.which === 13) { // 13 is the Enter key code
                e.preventDefault(); // Prevent default action, which is form submission
                moveToNextFocusableElement(this);
            }
        });
});
</script>