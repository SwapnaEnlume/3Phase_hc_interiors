<!-- src/Template/Reports/addmsr.ctp -->

<style>
form{ max-width:600px; margin:auto; }
label[for=vendor-fabric-name]{ float:left; width:280px; }
label[for=vendor-color-name]{ float:left; width:280px; }
input#vendor-fabric-name{ clear:both; }
input#vendor-color-name{ clear:both; }
</style>

<h3 style="text-align:center;">Add New MSR Entry:</h3>
<?php
echo $this->Form->create(null);

$vendoroptions=array();
foreach($allVendors as $vendor){
    $vendoroptions[$vendor['id']]=$vendor['vendor_name'];
}

echo "<div id=\"vendorselection\" class=\"required\"><label for=\"vendor_id\">Vendor</label>";
echo $this->Form->select('vendor_id',$vendoroptions,['required'=>true,'empty'=>'--Select Vendor--']);
echo "</div>";

echo $this->Form->input('qty',['type'=>'number','required'=>true,'label'=>'QTY']);

echo "<p><h4>Part Type:</h4><label style=\"display:inline-block;\"><input id=\"existingfabselector\" type=\"radio\" name=\"parttype\" value=\"existingfabric\" onchange=\"toggleTypes()\" /> Existing Fabric</label> &nbsp;&nbsp;
<label style=\"display:inline-block;\"><input id=\"typeinselector\" type=\"radio\" name=\"parttype\" value=\"typein\" onchange=\"toggleTypes()\" /> Type-In Part</label></p>";

$fabnameoptions=array();
foreach($allFabrics as $fabric){
    $fabnameoptions[$fabric['fabric_name']]=$fabric['fabric_name'];
}

$fabcoloroptions=array();

echo "<fieldset id=\"selectfabric\" style=\"display:none;\">";
echo "<div><label>Fabric Name</label>";
echo $this->Form->select('fabric_name',$fabnameoptions,['empty'=>'--Select A Fabric--']);
echo "</div>";

echo "<div><label>Fabric Color</label>";
echo $this->Form->select('fabric_color',$fabcoloroptions,['empty'=>'--Select A Color--']);
echo "</div>";

echo "</fieldset>";

echo "<fieldset id=\"typeinfields\" style=\"display:none;\">";

echo $this->Form->input('part_name',['label'=>'Part Name']);

echo $this->Form->input('revision_name',['label'=>'Revision Name']);

echo "</fieldset>";


echo $this->Form->input('work_order_number',['label'=>'Work Order #','required'=>true]);

echo "<div class=\"required\"><label>Order Status</label>";
echo $this->Form->select('order_status',['Not Acknowledged'=>'Not Acknowledged','Will Advise'=>'Will Advise','Backorder'=>'Backorder','In Stock' => 'In Stock','Blanket'=>'Blanket'],['required'=>true,'value'=>'Not Acknowledged']);
echo "</div>";

echo "<div class=\"input required\"><label>Order Date:</label>
<input type=\"date\" name=\"order_date\" value=\"".date('Y-m-d')."\" /></div>";

echo "<div class=\"input\"><label>Estimated Ship Date:</label>
<input type=\"date\" name=\"estimated_ship_date\" /></div>";

echo "<div class=\"input\"><label>ETA:</label>
<input type=\"date\" name=\"eta\" /></div>";

echo "<div class=\"required\"><label>Shipment Status</label>";
echo $this->Form->select('shipment_status',['TBD'=>'TBD','Hold'=>'Hold','In Transit'=>'In Transit','Delivered'=>'Delivered','Cancelled'=>'Cancelled'],['required'=>true,'value'=>'TBD']);
echo "</div>";

echo $this->Form->input('qb_po_number',['label'=>'QB PO#','required'=>true]);

echo $this->Form->input('notes',['type'=>'textarea']);

echo $this->Form->input('msrstatus',['type'=>'hidden','value'=>'Open']);

//echo "<p><h4>MSR Entry Status:</h4><label style=\"display:inline-block;\"><input id=\"msropen\" type=\"radio\" checked=\"checked\" name=\"msrstatus\" value=\"Open\" /> Open</label> &nbsp;&nbsp;<label style=\"display:inline-block;\"><input id=\"msrclosed\" type=\"radio\" name=\"msrstatus\" value=\"Closed\" /> Closed</label></p>";

echo $this->Form->button('Add This Entry',['type'=>'submit']);

echo $this->Form->end();
?><br><br><br>
<script>
$(function(){
    $('form').submit(function(){
        
        
        if($('#existingfabselector').is(':checked')){
            if($('select[name=\'fabric_name\']').val() == '' || ($('select[name=\'fabric_name\']').val() === undefined || $('select[name=\'fabric_name\']').val() === null)){
                alert('You must select a Fabric Name');
                return false;
            }
            if($('select[name=\'fabric_color\']').val() == '' || ($('select[name=\'fabric_color\']').val() === undefined || $('select[name=\'fabric_color\']').val() === null) ){
                alert('You must select a Fabric Color');
                return false;
            }
        }else if($('#typeinselector').is(':checked')){
            if($('#part-name').val() == ''){
               alert('You must enter a Part Name.');
               $('#part-name').focus();
               return false;
            }
        }else{
            alert('You must select Existing Fabric  or  Type-In Part before continuing.');
            return false;
        }
    });
    
    $('select[name=\'fabric_name\']').change(function(){
       $('select[name=\'fabric_color\']').html('<option disabled selected>..Loading colors...</option>');
       $.ajax({
          url:'/reports/msr/getfabcolors/'+$(this).val(),
          method:'GET',
          success:function(responseval){
              $('select[name=\'fabric_color\']').html(responseval);
          }
       });
    });
});

function toggleTypes(){
    if($('#existingfabselector').is(':checked')){
        $('#typeinfields').hide();
        $('#selectfabric').show();
    }else if($('#typeinselector').is(':checked')){
        $('#selectfabric').hide();
        $('#typeinfields').show();
    }
}
</script>
<style>
fieldset{ border:1px solid navy; background:rgba(0,0,0,0.06); }
div.input div.error{ display:inline-block; margin:0 0 20px 10px; background:#F9F3B7; border:2px solid red; color:red; padding:10px; font-size:12px; font-weight:bold; }

input.invalid{ border:2px solid red !important; }

#vendorselection h3{ font-size:16px; margin:0; font-weight:bold; }
</style>
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