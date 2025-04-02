<style>
body{font-family:'Helvetica',Arial,sans-serif; font-size:medium;}
form{ text-align:center; width:650px; }
form label{ font-weight:bold; float:left; width:75%; text-align:right; font-size:small; vertical-align:middle; }
form input{ float:right; padding:2px; width:20%; vertical-align:middle; font-size:12px; }
form select{ float:right; padding:2px; width:22%; vertical-align:middle; font-size:12px; }
form div.input{ clear:both; padding:10px 0; }
#calculatedPrice{ clear:both; padding:20px 0; font-size:x-large;  }
.clear{ clear:both; }
#calcformleft{ width:49.5%; float:left; }
#resultsblock{ width:49.5%; float:right; }

label[for=com-fabric]{ width:97% !important; }
label[for=quilted]{ width:97% !important; }
label[for=force-fitted-style-yds-accuracy]{ width:97% !important; }
label[for=force-full-widths-ea-bs]{ width:97% !important; }
label[for=force-full-widths-eol]{ width:97% !important; }
label[for=force-distributed-rounded-yds]{ width:97% !important; }
label[for=force-rounded-yds-ea-bs]{ width:97% !important; }

h2{ font-size:medium; margin:10px 0; }

label[for=lining-half-width-status]{ display:none; }
#lining-half-width-status{ width: 91% !important; font-size:12px; text-align:center; background:#FFF; border:0; }

label[for=fabric-half-width-status]{ display:none; }
#fabric-half-width-status{ width: 91% !important; font-size:12px; text-align:center; background:#FFF; border:0; }

label[for=total-fabric-widths]{ display:none; }
#total-fabric-widths{ resize:none; font-family:'Helvetica',Arial,sans-serif; width: 91% !important; height: 40px; font-size:12px; text-align:center; background:#FFF; border:0; }

label[for=fabric-yards]{ width:40% !important; }
#fabric-yards{ width:54% !important; font-size:12px; }

label[for=fabric-cost]{ width:40% !important; }
#fabric-cost{ width:54% !important; font-size:12px; }

.greenline{ background:#fff; border:0; color:green; }
.redline{ background:#fff; border:0; color:red; }
.grayline{ background:#fff; border:0; font-style:italic; }

.calculatebutton{ float:left; width:40%; padding:15px 0; }
.addaslineitembutton{ float:right; width:40%; padding:15px 0; }
</style>

<?php
echo $this->Form->create();
echo "<div id=\"calcformleft\">";
echo "<h2>BS Layout Calc (UTR ONLY)</h2>";

echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','min'=>0,'step'=>'any','value'=>0]);

echo "<div class=\"input\">";
echo $this->Form->label('Style');
echo $this->Form->select('style',['Throw'=>'Throw','Fitted'=>'Fitted'],['value'=>'Throw']);
echo "</div>";

echo $this->Form->input('com-fabric',['type'=>'checkbox','label'=>'COM Fabric']);

echo $this->Form->input('quilted',['type'=>'checkbox','label'=>'Quilted']);

echo $this->Form->input('width',['type'=>'number','min'=>'0','value'=>'0','label'=>'WIDTH']);

echo $this->Form->input('length',['type'=>'number','min'=>'0','value'=>'0','label'=>'LENGTH']);

echo $this->Form->input('fabric-width',['type'=>'number','min'=>'0','value'=>'0','label'=>'Fabric Width']);

echo $this->Form->input('vert-repeat',['type'=>'number','min'=>'0','value'=>'0','label'=>'Vert Rpt']);

echo $this->Form->input('fab-price-per-yd',['type'=>'number','min'=>'0','value'=>'0','label'=>'Fab Price per yd']);

echo $this->Form->input('backing-quilting-price-per-yd',['type'=>'number','min'=>'0','value'=>'0','label'=>'Backing/Quilting Price per yd']);

echo $this->Form->input('labor-per-bs',['type'=>'number','min'=>'0','value'=>'0','label'=>'Labor per BS']);

echo $this->Form->input('assumed-drop-width',['type'=>'number','min'=>'0','value'=>'0','label'=>'Assumed drop width']);

echo $this->Form->input('custom-top-width-mattress-w',['type'=>'number','min'=>'0','value'=>'0','label'=>'Custom top width (Mattress W)']);

echo $this->Form->input('extra-inches-seam-hems',['type'=>'number','min'=>'0','value'=>'0','label'=>'Extra Inches (seam/hems)']);

echo $this->Form->input('force-fitted-style-yds-accuracy',['type'=>'checkbox','label'=>'Force Fitted Style Yds Accuracy']);

echo $this->Form->input('force-full-widths-ea-bs',['type'=>'checkbox','label'=>'Force full widths ea BS']);

echo $this->Form->input('force-full-widths-eol',['type'=>'checkbox','label'=>'Force Full Widths EOL']);

echo $this->Form->input('force-distributed-rounded-yds',['type'=>'checkbox','label'=>'Force Distributed rounded yds']);

echo $this->Form->input('force-rounded-yds-ea-bs',['type'=>'checkbox','label'=>'Force rounded yds ea BS']);

echo "</div>";
?>

<div id="resultsblock">
<h2>Calculator Results</h2>

<?php
echo $this->Form->input('item',['label'=>'Item','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('waste-overhead',['label'=>'Waste/Overhead','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('top',['label'=>'Top','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('drop',['label'=>'Drop','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('layout',['label'=>'Layout','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('layout-status',['label'=>'Layout Status','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('cluster-status',['label'=>'Cluster Status','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('cl',['label'=>'Cl','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('total-widths',['label'=>'Total widths','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('yds-pbs',['label'=>'Yds p/BS','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('backing-quilting-pbs',['label'=>'Backing/Quilting p/BS','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('fabric-cost',['label'=>'Fabric Cost','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('backing-quilting-cost',['label'=>'Backing/Quilting Cost','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('bedspread-cost',['label'=>'Bedspread Cost','readonly'=>true,'disabled'=>true]);
?>
</div>
<div class="clear"></div>
<?php
echo "<div class=\"calculatebutton\">";
echo $this->Form->button('Calculate',['type'=>'button']);
echo "</div>";

echo "<div class=\"addaslineitembutton\">";
echo $this->Form->button('Add as Line Item',['type'=>'button']);
echo "</div>";
echo "<div class=\"clear\"></div>";

echo $this->Form->end();
?>
<script>

$(function(){
	$('.calculatebutton button').click(function(){
	
		
	});
	
	$('#calcformleft input,#calcformleft select').change(function(){
			$('.calculatebutton button').trigger('click');
	});
});
</script>