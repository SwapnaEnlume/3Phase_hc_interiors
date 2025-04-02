<!-- src/Template/Products/addwt.ctp -->
<h3>Add New Window Treatment:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo $this->Form->input('title',['label'=>'Name of this WT Product','required'=>true]);


echo "<div class=\"input selectbox\"><label>Type of Window Treatment</label>";
echo $this->Form->select('wt_type',['Straight Cornice' => 'Straight Cornice','Shaped Cornice' => 'Shaped Cornice','Box Pleated Valance' => 'Box Pleated Valance','Tailored Valance' => 'Tailored Valance','Pinch Pleated Drapery'=>'Pinch Pleated Drapery'],['value'=>$wtData['wt_type']]);
echo "</div>";

echo $this->Form->label('Does this product have Welts?');
echo $this->Form->radio('has_welt',[1=>'Yes',0=>'No'],['value'=>0]);



echo "<div class=\"input selectbox\"><label>Lining Option</label>";
echo $this->Form->select('lining',['Unlined'=>'Unlined','BO Lining'=>'B/O Lining','FR Lining'=>'FR Lining'],['value'=>'Unlined']);
echo "</div>";


echo $this->Form->input('qb_item_code',['label'=>'QuickBooks Item Code/Number','required'=>true]);

echo "<div id=\"fabricselector\"><h3>Select a Fabric</h3>";
echo "<select id=\"fabricid\" name=\"fabric_name\" onchange=\"changeFabricColorOptions(this.value)\">
<option value=\"0\" selected disabled>--Select A Fabric--</option>";
foreach($thefabrics as $fabricname => $fabricdata){
	echo "<option value=\"".$fabricname."\">".$fabricname."</option>\n";
}
echo "</select>";

echo "</div>";


echo "<div id=\"fabricselectionwrap\">";
foreach($thefabrics as $fabricname => $fabriccolors){
	if($fabricname == $wtData['fabric_name']){
		echo "<div id=\"colorselections_".str_replace("'","",str_replace(" ","_",$fabricname))."\" class=\"coloroptionsblock\">
		<h4>Select Which <em>".$fabricname."</em> Colors You Want Available For This Window Treatment <a href=\"javascript:checkallcolors('".str_replace("'","\'",$fabricname)."');\">Check All</a> <a href=\"javascript:uncheckallcolors('".str_replace("'","\'",$fabricname)."');\">Uncheck All</a></h4>";
		foreach($fabriccolors as $num => $color){
			echo "<div class=\"colorselection\"><label><input type=\"checkbox\" name=\"use_".str_replace("'","",str_replace(" ","_",$fabricname))."_color_".str_replace(" ","_",$color['color'])."\" value=\"yes\" /> <img src=\"/files/fabrics/".$color['id']."/".$color['image_file']."\" height=\"14\" width=\"14\" /> ".$color['color']."</label></div>";
		}
		echo "<div style=\"clear:both;\"></div></div>";
	}
}
echo "</div>";

/*
foreach($thefabrics as $fabricname => $fabriccolors){
	echo "<div id=\"colorselections_".str_replace("'","",str_replace(" ","_",$fabricname))."\" class=\"coloroptionsblock\" style=\"display:none;\"><h4>Select Which <em>".$fabricname."</em> Colors You Want Available For This Cubicle Curtain <a href=\"javascript:checkallcolors('".str_replace("'","\'",$fabricname)."');\">Check All</a> <a href=\"javascript:uncheckallcolors('".str_replace("'","\'",$fabricname)."');\">Uncheck All</a></h4>";
	foreach($fabriccolors as $num => $color){
		echo "<div class=\"colorselection\"><label><input type=\"checkbox\" name=\"use_".str_replace("'","",str_replace(" ","_",$fabricname))."_color_".$color['color']."\" value=\"yes\" /> <img src=\"/files/fabrics/".$color['id']."/".$color['image']."\" height=\"14\" width=\"14\" /> ".$color['color']."</label></div>";
	}
	echo "<div style=\"clear:both;\"></div></div>";
}
*/
echo $this->Form->input('description',['label'=>'Window Treatment Description','type'=>'textarea','required'=>false]);

echo $this->Form->input('primary_image',['label'=>'Window Treatment Image','type'=>'file','required'=>false]);




echo "<fieldset><legend>Which of these sizes are applicable to this window treatment? <a href=\"javascript:checkallsizes();\">Check All</a> <a href=\"javascript:uncheckallsizes();\">Uncheck All</a></legend>";

//$availableSizes=json_decode($ccData['available_sizes'],true);

foreach($sizes as $size){
	
	//determine if this size is Checked
	$ifchecked=false;
	$thisprice=0;
	$thisdifficulty=0;
	$thisyards=0;
	$thisweight=0;
	$thislaborlf=0;
	
	foreach($availableSizes as $sizerow => $sizedata){
		if($size['id'] == $sizedata['id']){
			$ifchecked=true;
			if(floatval($sizedata['price']) >0){
				$thisprice=$sizedata['price'];
				$thisdifficulty=$sizedata['difficulty'];
				$thisyards=$sizedata['yards'];
				$thisweight=$sizedata['weight'];
				$thislaborlf=$sizedata['labor_lf'];
			}
			
		}
	}
	
	echo "<div class=\"sizeblock\">";
	echo $this->Form->input('size_'.$size['id'].'_enabled',['type'=>'checkbox','value'=>1,'label'=>$size['title'],'data-sizeid'=>$size['id'],'checked'=>$ifchecked]);
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-price-wrap\"";
	
	if(!$ifchecked){
		//if unchecked
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	
	echo ">".$this->Form->input('size_'.$size['id'].'_price',['readonly'=>$readonly,'label'=>'Price','value'=>number_format($thisprice,2,'.','')])."</div>";
	
	
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-weight-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_weight',['readonly'=>$readonly,'label'=>'Weight','value'=>$thisweight]);
	echo "</div>";
	
	
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-yards-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_yards',['readonly'=>$readonly,'label'=>'Yards','value'=>$thisyards]);
	echo "</div>";
	
	
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-difficulty-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_difficulty',['readonly'=>$readonly,'label'=>'Difficulty','value'=>$thisdifficulty]);
	echo "</div>";
	
	
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-laborlf-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_laborlf',['readonly'=>$readonly,'label'=>'Labor LF','value'=>$thislaborlf]);
	echo "</div>";
	
	
	
	echo "</div>";
}

echo "<div style=\"clear:both;\"></div>";
echo "</fieldset>";


echo $this->Form->button('Add This Window Treatment',['type'=>'submit']);

echo $this->Form->end();
?>
<script>
function checkallcolors(fabricname){
	var block=fabricname.replace(/ /g,"_");
	block=block.replace("'","");
	$('#colorselections_'+block).find('input[type=checkbox]').prop('checked',true);
}
	
function uncheckallcolors(fabricname){
	var block=fabricname.replace(/ /g,"_");
	block=block.replace("'","");
	$('#colorselections_'+block).find('input[type=checkbox]').prop('checked',false);
}
	
function checkallsizes(){
	$('fieldset input[type=checkbox]').each(function(){
		$(this).prop('checked',true);
		$('#size-'+$(this).attr('data-sizeid')+'-price').css({'visibility':'visible','height':'auto'});
	})
}
	
function uncheckallsizes(){
	$('fieldset input[type=checkbox]').each(function(){
		$(this).prop('checked',false);
		$('#size-'+$(this).attr('data-sizeid')+'-price').css({'visibility':'hidden','height':'0px'});
	})
}
	
$(function(){
	$('fieldset input[type=checkbox]').change(function(){
		if($(this).prop('checked')){
			
			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap,#size-'+$(this).attr('data-sizeid')+'-weight-wrap,#size-'+$(this).attr('data-sizeid')+'-yards-wrap,#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap,#size-'+$(this).attr('data-sizeid')+'-laborlf-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap input,#size-'+$(this).attr('data-sizeid')+'-weight-wrap input,#size-'+$(this).attr('data-sizeid')+'-yards-wrap input,#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap input,#size-'+$(this).attr('data-sizeid')+'-laborlf-wrap input').removeAttr('readonly');
			
		}else{
			
			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap,#size-'+$(this).attr('data-sizeid')+'-weight-wrap,#size-'+$(this).attr('data-sizeid')+'-yards-wrap,#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap,#size-'+$(this).attr('data-sizeid')+'-laborlf-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap input,#size-'+$(this).attr('data-sizeid')+'-weight-wrap input,#size-'+$(this).attr('data-sizeid')+'-yards-wrap input,#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap input,#size-'+$(this).attr('data-sizeid')+'-laborlf-wrap input').attr('readonly','readonly');
			
		}
	});
});
	
function changeFabricColorOptions(fabricname){
	//fabricname=fabricname.replace(/ /g,"_");
	//fabricname=fabricname.replace("'","");
	$('div.coloroptionsblock input[type=checkbox]').removeProp('checked');
	//$.get('/products/getwtfabriccolorcheckboxes/<?php echo $wtData['id']; ?>/'+encodeURIComponent(fabricname),
	$.get('/products/getfabriccolorscheckboxes/wt/'+encodeURIComponent(fabricname),
	function(result){
		$('#fabricselectionwrap').html(result);
	});
}
</script>
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