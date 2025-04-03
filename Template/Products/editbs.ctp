<!-- src/Template/Products/editbs.ctp -->
<h3>Edit Bedspread:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('new_image');

echo $this->Form->input('title',['label'=>'Name of this BS Product','required'=>true,'value'=>$bsData['title']]);


echo $this->Form->label('Is this bedspread Quilted?');
echo $this->Form->radio('quilted',[1=>'Yes',0=>'No'],['value'=>$bsData['quilted']]);

echo $this->Form->input('qb_item_code',['label'=>'QuickBooks Item Code/Number','required'=>true,'value'=>$bsData['qb_item_code']]);

echo "<div class=\"selectbox input\"><label for=\"status\">Status</label>";
echo $this->Form->select('status',['Active'=>'Active','Inactive'=>'Inactive'],['value'=>$bsData['status']]);
echo "</div>";


echo "<div id=\"fabricselector\"><h3>Select a Fabric</h3>";
echo "<select id=\"fabricid\" name=\"fabric_name\" onchange=\"changeFabricColorOptions(this.value)\">
<option value=\"0\" selected disabled>--Select A Fabric--</option>";
foreach($thefabrics as $fabricname => $fabricdata){
	echo "<option value=\"".$fabricname."\"";
	if($fabricname == $bsData['fabric_name']){
		echo " selected";
	}
	echo ">".$fabricname."</option>\n";
}
echo "</select>";
echo "</div><br>";

$thisavailcolors=json_decode($bsData['available_colors'],true);
echo "<div id=\"fabricselectionwrap\">";
foreach($thefabrics as $fabricname => $fabriccolors){
	if($fabricname == $bsData['fabric_name']){
		echo "<div id=\"colorselections_".str_replace("'","",str_replace(" ","_",$fabricname))."\" class=\"coloroptionsblock\">
		<h4>Select Which <em>".$fabricname."</em> Colors You Want Available For This Window Treatment <a href=\"javascript:checkallcolors('".str_replace("'","\'",$fabricname)."');\">Check All</a> <a href=\"javascript:uncheckallcolors('".str_replace("'","\'",$fabricname)."');\">Uncheck All</a></h4>";
		foreach($fabriccolors as $num => $color){
			echo "<div class=\"colorselection\"><label><input type=\"checkbox\" name=\"use_".str_replace("'","",str_replace(" ","_",$fabricname))."_color_".str_replace(" ","_",$color['color'])."\" value=\"yes\"";
			foreach($thisavailcolors as $colorinclude){
				if(str_replace("_"," ",$colorinclude) == $color['color']){
					echo " checked=\"checked\"";
				}
			}
			echo " /> <img src=\"/files/fabrics/".$color['id']."/".$color['image']."\" height=\"14\" width=\"14\" /> ".$color['color']."</label></div>";
		}
		echo "<div style=\"clear:both;\"></div></div>";
	}
}
echo "</div>";


/*$thisavailcolors=json_decode($bsData['available_colors'],true);

foreach($thefabrics as $fabricname => $fabriccolors){
	echo "<div id=\"colorselections_".str_replace(" ","_",$fabricname)."\" class=\"coloroptionsblock\"";
	if($fabricname != $bsData['fabric_name']){
		echo " style=\"display:none;\"";
	}
	echo "><h4>Select Which <em>".$fabricname."</em> Colors You Want Available For This Cubicle Curtain <a href=\"javascript:checkallcolors('".$fabricname."');\">Check All</a> <a href=\"javascript:uncheckallcolors('".$fabricname."');\">Uncheck All</a></h4>";
	foreach($fabriccolors as $num => $color){
		echo "<div class=\"colorselection\"><label><input type=\"checkbox\" name=\"use_".str_replace(" ","_",$fabricname)."_color_".str_replace(" ","_",$color['color'])."\" value=\"yes\"";
		foreach($thisavailcolors as $colorinclude){
			if(str_replace("_"," ",$colorinclude) == $color['color']){
				echo " checked=\"checked\"";
			}
		}
		echo " /> <img src=\"/files/fabrics/".$color['id']."/".$color['image']."\" height=\"14\" width=\"14\" /> ".$color['color']."</label></div>";
	}
	echo "<div style=\"clear:both;\"></div></div>";
}
*/




echo "<div class=\"input selectbox\"><label>Mattress Size</label>";
echo $this->Form->select('mattress_size',['36"'=>'36"','42"'=>'42"'],['value'=>$bsData['mattress_size']]);
echo "</div>";



echo $this->Form->input('description',['label'=>'Cubicle Curtain Description','type'=>'textarea','required'=>false,'value'=>$ccData['description']]);


//image
echo "<div style=\"padding:10px 25px;\" id=\"currentimage\">";
echo "<div><img src=\"/files/bedspreads/".$bsData['id']."/".$bsData['image_file']."\" /></div>";
echo "<div style=\"padding:10px 0;\"><a href=\"javascript:changeImage();\">Change Image</a></div>";
echo "</div>";

echo "<div id=\"newimage\" style=\"padding:10px 25px; visibility:hidden; height:0px;\">";
echo $this->Form->input('new_image',['label'=>'New Cubicle Curtain Image','required'=>false,'type'=>'file']);
echo "<div><a href=\"javascript:unchangeImage();\">Cancel Change</a></div>";
echo "</div>";




echo "<fieldset><legend>Which of these sizes are applicable to this bedspread? <!--<a href=\"javascript:checkallsizes();\">Check All</a> <a href=\"javascript:uncheckallsizes();\">Uncheck All</a>--></legend>";

//$availableSizes=json_decode($ccData['available_sizes'],true);

//print_r($availableSizes);

echo "<table id=\"sizeeditstable\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
<thead>
<tr>
<th>Enabled</th>
<th>ID#</th>
<th>Description</th>
<th>Price</th>
<th>Weight</th>
<th>Yards</th>
<th>Diff</th>
<th>Q.Patt</th>
<th>Rpt</th>
<th>Top W CL</th>
<th>Drop W CL</th>
<th>Top Cuts W</th>
<th>Drop Cuts W</th>
<th>Layout</th>
</tr>
</thead>
<tbody>";

foreach($sizes as $size){
	
	//determine if this size is Checked
	$ifchecked=false;
	$thisprice=0;
	$thisdifficulty=0;
	$thisyards=0;
	$thisweight=0;
	$thislaborlf=0;
	$thisqp='None';
	$thisvertrepeat=0;
	$thistopwidthl=0;
	$thisdropwidthl=0;
	$thistopcutsw=0;
	$thisdropcutsw=0;
	$thislayout=1.33;

	
	foreach($availableSizes as $sizerow => $sizedata){
		if($size['id'] == $sizedata['id']){
			$ifchecked=true;
			if(floatval($sizedata['price']) >0){
				$thisprice=$sizedata['price'];
				$thisdifficulty=$sizedata['difficulty'];
				$thisyards=$sizedata['yards'];
				$thisweight=$sizedata['weight'];
				$thislaborlf=$sizedata['labor_lf'];
				$thisqp=$sizedata['quilting_pattern'];
				$thisvertrepeat=$sizedata['vertical_repeat'];
				$thistopwidthl=$sizedata['top_width_l'];
				$thisdropwidthl=$sizedata['drop_width_l'];
				$thistopcutsw=$sizedata['top_cuts_w'];
				$thisdropcutsw=$sizedata['drop_cuts_w'];
				$thislayout=$sizedata['layout'];
			}
			
		}
	}
	
	echo "<tr>
	<td>";
	echo $this->Form->input('size_'.$size['id'].'_enabled',['type'=>'checkbox','value'=>1,'label'=>false,'data-sizeid'=>$size['id'],'checked'=>$ifchecked]);
	echo "</td>
	<td>".$size['id']."</td>
	<td>".$size['title']."</td>
	<td>";
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-price-wrap\"";
	
	if(!$ifchecked){
		//if unchecked
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	
	echo ">".$this->Form->input('size_'.$size['id'].'_price',['readonly'=>$readonly,'label'=>false,'value'=>number_format($thisprice,2,'.','')])."</div>";
	
	echo "</td>
	<td>";
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-weight-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_weight',['readonly'=>$readonly,'label'=>false,'value'=>$thisweight]);
	echo "</div>";
	
	echo "</td><td>";
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-yards-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_yards',['readonly'=>$readonly,'label'=>false,'value'=>$thisyards]);
	echo "</div>";
	
	echo "</td><td>";
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-difficulty-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_difficulty',['readonly'=>$readonly,'label'=>false,'value'=>$thisdifficulty]);
	echo "</div>";
	
	echo "</td><td>";
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-quiltingpattern-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	$qpoptions=array();
	$allopts=explode('|',$settings['pricelist_bedspread_quilting_patterns']);
	foreach($allopts as $num => $qpopt){
		$qpoptions[$qpopt]=$qpopt;

	}


	echo ">".$this->Form->select('size_'.$size['id'].'_quiltingpattern',$qpoptions,['readonly'=>$readonly,'value'=>$thisqp]);
	echo "</div>";

	echo "</td><td>";


	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-vertrepeat-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_vertrepeat',['readonly'=>$readonly,'label'=>false,'value'=>$thisvertrepeat]);
	echo "</div>";

	echo "</td><td>";


	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-topwidthl-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_topwidthl',['readonly'=>$readonly,'label'=>false,'value'=>$thistopwidthl]);
	echo "</div>";

	echo "</td><td>";

	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-dropwidthl-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_dropwidthl',['readonly'=>$readonly,'label'=>false,'value'=>$thisdropwidthl]);
	echo "</div>";


	echo "</td><td>";

	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-topcutsw-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_topcutsw',['readonly'=>$readonly,'label'=>false,'value'=>$thistopcutsw]);
	echo "</div>";

	echo "</td><td>";

	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-dropcutsw-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_dropcutsw',['readonly'=>$readonly,'label'=>false,'value'=>$thisdropcutsw]);
	echo "</div>";

	echo "</td><td>";

	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-layout-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_layout',['readonly'=>$readonly,'label'=>false,'value'=>$thislayout]);
	echo "</div>";
	
	echo "</td></tr>";
	
}
echo "</tbody></table>";

echo "</fieldset>";


echo $this->Form->button('Save Changes',['type'=>'submit']);

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
	
	
function changeImage(){
	$('#currentimage').css({'visibility':'hidden','height':'0px'});
	$('#newimage').css({'visibility':'visible','height':'auto'});
}

function unchangeImage(){
	$('#currentimage').css({'visibility':'visible','height':'auto'});
	$('#newimage').css({'visibility':'hidden','height':'0px'});
}
	
	
function checkallsizes(){
	$('fieldset input[type=checkbox]').each(function(){
		$(this).prop('checked',true);
		//$('#size-'+$(this).attr('data-sizeid')+'-price').css({'visibility':'visible','height':'auto'});
		$('#size-'+$(this).attr('data-sizeid')+'-price').css({'opacity':'1.0','height':'auto'});
	})
}
	
function uncheckallsizes(){
	$('fieldset input[type=checkbox]').each(function(){
		$(this).prop('checked',false);
		//$('#size-'+$(this).attr('data-sizeid')+'-price').css({'visibility':'hidden','height':'0px'});
		$('#size-'+$(this).attr('data-sizeid')+'-price').css({'opacity':'1.0','height':'0px'});
	})
}
	
$(function(){
	$('fieldset input[type=checkbox]').change(function(){
		if($(this).prop('checked')){
			
			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-weight-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-yards-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap').css({'opacity':'1.0','height':'auto'});

			$('#size-'+$(this).attr('data-sizeid')+'-quiltingpattern-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-vertrepeat-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-topwidthl-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-dropwidthl-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-topcutsw-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-dropcutsw-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-layout-wrap').css({'opacity':'1.0','height':'auto'});


			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap input').removeAttr('readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-weight-wrap input').removeAttr('readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-yards-wrap input').removeAttr('readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap input').removeAttr('readonly');

			$('#size-'+$(this).attr('data-sizeid')+'-quiltingpattern-wrap select').removeAttr('readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-vertrepeat-wrap input').removeAttr('readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-topwidthl-wrap input').removeAttr('readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-dropwidthl-wrap input').removeAttr('readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-topcutsw-wrap input').removeAttr('readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-dropcutsw-wrap input').removeAttr('readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-layout-wrap input').removeAttr('readonly');
			
		}else{
			
			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-weight-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-yards-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap').css({'opacity':'0.2'});


			$('#size-'+$(this).attr('data-sizeid')+'-quiltingpattern-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-vertrepeat-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-topwidthl-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-dropwidthl-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-topcutsw-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-dropcutsw-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-layout-wrap').css({'opacity':'0.2'});



			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap input').attr('readonly','readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-weight-wrap input').attr('readonly','readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-yards-wrap input').attr('readonly','readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap input').attr('readonly','readonly');

			$('#size-'+$(this).attr('data-sizeid')+'-quiltingpattern-wrap select').attr('readonly','readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-vertrepeat-wrap input').attr('readonly','readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-topwidthl-wrap input').attr('readonly','readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-dropwidthl-wrap input').attr('readonly','readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-topcutsw-wrap input').attr('readonly','readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-dropcutsw-wrap input').attr('readonly','readonly');
			$('#size-'+$(this).attr('data-sizeid')+'-layout-wrap input').attr('readonly','readonly');
			
		}
	});
});
	
		
function changeFabricColorOptions(fabricname){
	//fabricname=fabricname.replace(/ /g,"_");
	//fabricname=fabricname.replace("'","");
	$('div.coloroptionsblock input[type=checkbox]').removeProp('checked');
	//$.get('/products/getwtfabriccolorcheckboxes/<?php echo $bsData['id']; ?>/'+encodeURIComponent(fabricname),
	$.get('/products/getfabriccolorscheckboxes/bs/'+encodeURIComponent(fabricname),
	function(result){
		$('#fabricselectionwrap').html(result);
	});
}
</script>


<style>
#fabricselector h3{ font-size:16px; margin:0; font-weight:bold; }
#fabricselector label{ display:inline-block; margin-right:20px; }



fieldset legend a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }

div.coloroptionsblock{ padding:20px 20px 35px 20px; }
div.coloroptionsblock h4{ line-height: 2rem; margin:0 0 10px 0; font-size:16px; font-weight:bold; color:#26337A; border-bottom:2px solid #26337A; }

div.coloroptionsblock h4 a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }

div.colorselection{ width:185px; float:left; height:35px; }
	
div.input.checkbox{ display:block !important; }
	
	
#currentimage img{ max-width:250px; height:auto; }

#sizeeditstable thead tr{ background:#26337A; }
#sizeeditstable thead tr th{ color:#FFF; text-align:center; }
#sizeeditstable tbody tr:nth-of-type(even){
	background:#e8e8e8;
}
#sizeeditstable tbody tr:nth-of-type(odd){
	background:#f8f8f8;
}

#sizeeditstable th, #sizeeditstable td{ padding:3px !important; }
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