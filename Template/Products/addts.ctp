<!-- src/Template/Products/addts.ctp -->
<h3>Add New Track System Component:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo "<fieldset><legend>Track Item Type</legend>";
echo $this->Form->radio('system_or_component', ['component'=>'Component','system'=>'System']);
echo "</fieldset>";


echo "<fieldset><legend>Track Item Unit of Measure</legend>";
echo $this->Form->radio('unit', ['plf'=>'Per Linear Foot','each'=>'Each']);
echo "</fieldset>";



echo $this->Form->input('title',['label'=>'Name of this Track System / Component','required'=>true]);


echo $this->Form->input('qb_item_code',['label'=>'QuickBooks Item Code/Number','required'=>true]);

echo $this->Form->input('description',['label'=>'Description','type'=>'textarea','required'=>false]);

echo $this->Form->input('primary_image',['label'=>'Track Item Image','type'=>'file','required'=>true]);

echo $this->Form->input('price',['type'=>'number','step'=>'any','min'=>'0.00','required'=>true]);

//echo $this->Form->inpit('inches_equivalent',['type'=>'number','step'=>'any','min'=>'0.00','required'=>false]);
/*PPSASCRUM-124: start*/
echo $this->Form->input('inches_equivalent',['label'=>'Inches Equivalent','type'=>'number','step'=>'any','min'=>'0','default'=>0,'required'=>true]);
/*PPSASCRUM-124: end*/

echo "<fieldset><legend>Track Item Status</legend>";
echo $this->Form->radio('status', ['Active'=>'Active','Inactive'=>'Inactive']);
echo "</fieldset>";



echo $this->Form->button('Add This Track System Component',['type'=>'submit']);

echo $this->Form->end();
?>

<style>
#fabricselector h3{ font-size:16px; margin:0; font-weight:bold; }
#fabricselector label{ display:inline-block; margin-right:20px; }
div.input > label{ font-weight:bold; font-size:16px; }
/*fieldset label{ font-weight:normal !important; font-size:14px !important; display:inline-block; width:135px; float:left; height: 22px; margin-bottom: 8px; }*/

fieldset div.sizeblock{ font-weight:normal !important; font-size:14px !important; display:inline-block; width:185px; float:left; height:205px; margin-bottom: 8px; }

div.coloroptionsblock{ padding:20px 20px 35px 20px; }
div.coloroptionsblock h4{ line-height: 2rem; margin:0 0 10px 0; font-size:16px; font-weight:bold; color:#26337A; border-bottom:2px solid #26337A; }

div.coloroptionsblock h4 a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }

div.colorselection{ width:185px; float:left; height:35px; }

	
fieldset input[type=text]{ width:90px; height:22px; font-size:12px; padding:2px; }
fieldset label{ font-weight:normal !important; font-size:12px !important; }
fieldset legend a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }
div.sizeblock input[type=checkbox]{ margin:0 10px 0 0 !important; }

	
#currentimage img{ max-width:250px; height:auto; }

div.sizedatarow{ padding:4px 2px 4px 18px; }
div.sizeblock div div.input.text label{ display:inline-block; margin-right:5px; width:70px; }
div.sizeblock div div.input.text input[type=text]{ display:inline-block; width:55px; padding:2px; margin-bottom:0; }
div.sizeblock div.checkbox label{ font-weight:bold !important; font-size:16px !important; }
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