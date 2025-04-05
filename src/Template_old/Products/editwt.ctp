<script src="/js/jquery.jexcel.js"></script>
<link rel="stylesheet" href="/css/jquery.jexcel.css" type="text/css" />

<!-- src/Template/Products/editwt.ctp -->
<h3>Edit Window Treatment:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo $this->Form->input('title',['label'=>'Name of this WT Product','required'=>true,'value'=>$wtData['title']]);

echo "<div class=\"input selectbox\"><label>Type of Window Treatment</label>";
echo $this->Form->select('wt_type',['Straight Cornice' => 'Straight Cornice','Shaped Cornice' => 'Shaped Cornice','Box Pleated Valance' => 'Box Pleated Valance','Tailored Valance' => 'Tailored Valance','Pinch Pleated Drapery'=>'Pinch Pleated Drapery'],['value'=>$wtData['wt_type']]);
echo "</div>";

echo $this->Form->label('Does this product have Welts?');
echo $this->Form->radio('has_welt',[1=>'Yes',0=>'No'],['value'=>$wtData['has_welt']]);


echo "<div class=\"input selectbox\"><label>Lining Option</label>";
echo $this->Form->select('lining',['Unlined'=>'Unlined','BO Lining'=>'B/O Lining','FR Lining'=>'FR Lining'],['value'=>$wtData['lining_option']]);
echo "</div>";

echo $this->Form->input('qb_item_code',['label'=>'QuickBooks Item Code/Number','required'=>true,'value'=>$wtData['qb_item_code']]);


echo "<div id=\"fabricselector\"><h3>Select a Fabric</h3>";
echo "<select id=\"fabricid\" name=\"fabric_name\" onchange=\"changeFabricColorOptions(this.value)\">
<option value=\"0\" selected disabled>--Select A Fabric--</option>";
foreach($thefabrics as $fabricname => $fabricdata){
	echo "<option value=\"".$fabricname."\"";
	if($fabricname == $wtData['fabric_name']){
		echo " selected";
	}
	echo ">".$fabricname."</option>\n";
}
echo "</select>";
echo "</div><br>";

$thisavailcolors=json_decode($wtData['available_colors'],true);
echo "<div id=\"fabricselectionwrap\">";
foreach($thefabrics as $fabricname => $fabriccolors){
	if($fabricname == $wtData['fabric_name']){
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

/*
foreach($thefabrics as $fabricname => $fabriccolors){
	echo "<div id=\"colorselections_".str_replace("'","",str_replace(" ","_",$fabricname))."\" class=\"coloroptionsblock\"";
	if($fabricname != $wtData['fabric_name']){
		echo " style=\"display:none;\"";
	}
	echo "><h4>Select Which <em>".$fabricname."</em> Colors You Want Available For This Window Treatment <a href=\"javascript:checkallcolors('".str_replace("'","\'",$fabricname)."');\">Check All</a> <a href=\"javascript:uncheckallcolors('".str_replace("'","\'",$fabricname)."');\">Uncheck All</a></h4>";
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
*/

echo $this->Form->input('description',['label'=>'Window Treatment Description','type'=>'textarea','required'=>false,'value'=>$wtData['description']]);

//image
echo "<div style=\"padding:10px 25px;\" id=\"currentimage\">";
echo "<div><img src=\"/files/window-treatments/".$wtData['id']."/".$wtData['image_file']."\" /></div>";
echo "<div style=\"padding:10px 0;\"><a href=\"javascript:changeImage();\">Change Image</a></div>";
echo "</div>";

echo "<div id=\"newimage\" style=\"padding:10px 25px; visibility:hidden; height:0px;\">";
echo $this->Form->input('new_image',['label'=>'New Window Treatment Image','required'=>false,'type'=>'file']);
echo "<div><a href=\"javascript:unchangeImage();\">Cancel Change</a></div>";
echo "</div>";

echo "<script>
function changeImage(){
	$('#currentimage').css({'visibility':'hidden','height':'0px'});
	$('#newimage').css({'visibility':'visible','height':'auto'});
}

function unchangeImage(){
	$('#currentimage').css({'visibility':'visible','height':'auto'});
	$('#newimage').css({'visibility':'hidden','height':'0px'});
}
</script>";



echo "<fieldset><legend>Which of these sizes are applicable to this curtain? <a href=\"javascript:checkallsizes();\">Check All</a> <a href=\"javascript:uncheckallsizes();\">Uncheck All</a></legend>";

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
	echo "</div>";
}


echo "<div style=\"clear:both;\"></div>";

echo "<br><br>";

echo "<div id=\"gridwrap\">";
echo "<h3 class=\"gridlabel\">Price Grid</h3>";

//price grid
/*
echo "<pre>";
print_r($availableLengths);
echo "</pre>";
echo "<hr>";
echo "<pre>";
print_r($availableWidths);
echo "</pre>";
*/
/*
echo "<table class=\"wtgrid dollars\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
echo "<thead>
<tr>
<th class=\"empty\">&nbsp;</th>";
foreach($availableLengths as $length){
	echo "<th><b>".$length."&quot;</b></th>";
}
echo "</tr>
</thead><tbody>";*/
foreach($availableLengths as $length){
	//echo "<tr>
		//<th><b>".$width."&quot;</b></td>";
		foreach($availableWidths as $width){
			echo "<input type=\"hidden\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_price\" data-sizewidth=\"".$width."\" class=\"priceval\" data-sizelength=\"".$length."\" data-sizecombined=\"".$length."x".$width."\" value=\"".number_format($sizedata['price'],2,'.','');
				}
			}
			echo "\" />\n";
			
			echo "<input type=\"hidden\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_weight\" data-sizewidth=\"".$width."\" class=\"weightval\" data-sizelength=\"".$length."\" data-sizecombined=\"".$length."x".$width."\" value=\"".number_format($sizedata['weight'],2,'.','');
				}
			}
			echo "\" />\n";
			
			echo "<input type=\"hidden\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_yards\" data-sizewidth=\"".$width."\" class=\"yardsval\" data-sizelength=\"".$length."\" data-sizecombined=\"".$length."x".$width."\" value=\"".number_format($sizedata['yards'],2,'.','');
				}
			}
			echo "\" />\n";
			
			echo "<input type=\"hidden\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_difficulty\" data-sizewidth=\"".$width."\" class=\"difficultyval\" data-sizelength=\"".$length."\" data-sizecombined=\"".$length."x".$width."\" value=\"".number_format($sizedata['difficulty'],2,'.','');
				}
			}
			echo "\" />\n";
			
			echo "<input type=\"hidden\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_laborlf\" data-sizewidth=\"".$width."\" class=\"laborlfval\" data-sizelength=\"".$length."\" data-sizecombined=\"".$length."x".$width."\" value=\"".number_format($sizedata['labor_lf'],2,'.','');
				}
			}
			echo "\" />\n";
		}
	//echo "</tr>";
}
/*echo "</tbody>
</table>";
*/
echo '<div id="pricegrid"></div>';

//weight grid
echo "<br><br>";
echo "<h3 class=\"gridlabel\">Weight Grid</h3>";
/*
echo "<table class=\"wtgrid\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
echo "<thead>
<tr>
<th class=\"empty\">&nbsp;</th>";
foreach($availableLengths as $length){
	echo "<th><b>".$length."&quot;</b></th>";
}
echo "</tr>
</thead><tbody>";
foreach($availableWidths as $width){
	echo "<tr>
		<th><b>".$width."&quot;</b></td>";
		foreach($availableLengths as $length){
			echo "<td><input type=\"text\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_weight\" value=\"".number_format($sizedata['weight'],2,'.','');
				}
			}
			echo "\" /></td>";
		}
	echo "</tr>";
}
echo "</tbody>
</table>";
*/
echo '<div id="weightgrid"></div>';


//yards grid
echo "<br><br>";
echo "<h3 class=\"gridlabel\">Yards Grid</h3>";
/*
echo "<table class=\"wtgrid\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
echo "<thead>
<tr>
<th class=\"empty\">&nbsp;</th>";
foreach($availableLengths as $length){
	echo "<th><b>".$length."&quot;</b></th>";
}
echo "</tr>
</thead><tbody>";
foreach($availableWidths as $width){
	echo "<tr>
		<th><b>".$width."&quot;</b></td>";
		foreach($availableLengths as $length){
			echo "<td><input type=\"text\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_yards\" value=\"".number_format($sizedata['yards'],2,'.','');
				}
			}
			echo "\" /></td>";
		}
	echo "</tr>";
}
echo "</tbody>
</table>";
*/
echo '<div id="yardsgrid"></div>';


//diff grid
echo "<br><br>";
echo "<h3 class=\"gridlabel\">Difficulty Grid</h3>";
/*
echo "<table class=\"wtgrid\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
echo "<thead>
<tr>
<th class=\"empty\">&nbsp;</th>";
foreach($availableLengths as $length){
	echo "<th><b>".$length."&quot;</b></th>";
}
echo "</tr>
</thead><tbody>";
foreach($availableWidths as $width){
	echo "<tr>
		<th><b>".$width."&quot;</b></td>";
		foreach($availableLengths as $length){
			echo "<td><input type=\"text\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_difficulty\" value=\"".number_format($sizedata['difficulty'],2,'.','');
				}
			}
			echo "\" /></td>";
		}
	echo "</tr>";
}
echo "</tbody>
</table>";
*/
echo '<div id="difficultygrid"></div>';



//lf grid
echo "<br><br>";
echo "<h3 class=\"gridlabel\">Labor Grid (Panel only if Drapery)</h3>";

/*
echo "<table class=\"wtgrid\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
echo "<thead>
<tr>
<th class=\"empty\">&nbsp;</th>";
foreach($availableLengths as $length){
	echo "<th><b>".$length."&quot;</b></th>";
}
echo "</tr>
</thead><tbody>";
foreach($availableWidths as $width){
	echo "<tr>
		<th><b>".$width."&quot;</b></td>";
		foreach($availableLengths as $length){
			echo "<td><input type=\"text\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_laborlf\" value=\"".$sizedata['labor_lf'];
				}
			}
			echo "\" /></td>";
		}
	echo "</tr>";
}
echo "</tbody>
</table>";
*/
echo '<div id="laborgrid"></div>';



echo "<div style=\"clear:both;\"></div>";
echo "</div>";

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
	
	
	
function updatePriceGridValues(){
	$('#pricegrid table.jexcel tbody tr td').each(function(){
		var attr = $(this).attr('data-length');
		if (typeof attr !== typeof undefined && attr !== false) {
			var width=$(this).attr('data-width');
			var length=$(this).attr('data-length');
			$('input.priceval[data-sizecombined="'+length+'x'+width+'"]').val($(this).text().replace('$','').replace(',',''));
			$(this).text($(this).text().replace('$','').replace(',',''));
		}
	});
	
	if($('#pricegrid table.jexcel tbody tr').length > <?php echo count($availableLengths); ?>){
	   var toomany=($('#pricegrid table.jexcel tbody tr').length-<?php echo count($availableLengths); ?>);
		for(var i=1; i<=toomany; i++){
			$('#pricegrid table.jexcel tbody tr:last').remove();
		}
	}
}

function updateWeightGridValues(){
	$('#weightgrid table.jexcel tbody tr td').each(function(){
		var attr = $(this).attr('data-width');
		if (typeof attr !== typeof undefined && attr !== false) {
			var width=$(this).attr('data-width');
			var length=$(this).attr('data-length');
			$('input.weightval[data-sizecombined="'+width+'x'+length+'"]').val($(this).text());
		}
	});
	
	if($('#weightgrid table.jexcel tbody tr').length > <?php echo count($availableLengths); ?>){
	   var toomany=($('#weightgrid table.jexcel tbody tr').length-<?php echo count($availableLengths); ?>);
		for(var i=1; i<=toomany; i++){
			$('#weightgrid table.jexcel tbody tr:last').remove();
		}
	}
}

function updateYardsGridValues(){
	$('#yardsgrid table.jexcel tbody tr td').each(function(){
		var attr = $(this).attr('data-width');
		if (typeof attr !== typeof undefined && attr !== false) {
			var width=$(this).attr('data-width');
			var length=$(this).attr('data-length');
			$('input.yardsval[data-sizecombined="'+width+'x'+length+'"]').val($(this).text());
		}
	});
	
	if($('#yardsgrid table.jexcel tbody tr').length > <?php echo count($availableLengths); ?>){
	   var toomany=($('#yardsgrid table.jexcel tbody tr').length-<?php echo count($availableLengths); ?>);
		for(var i=1; i<=toomany; i++){
			$('#yardsgrid table.jexcel tbody tr:last').remove();
		}
	}
}

function updateDifficultyGridValues(){
	$('#difficultygrid table.jexcel tbody tr td').each(function(){
		var attr = $(this).attr('data-width');
		if (typeof attr !== typeof undefined && attr !== false) {
			var width=$(this).attr('data-width');
			var length=$(this).attr('data-length');
			$('input.difficultyval[data-sizecombined="'+width+'x'+length+'"]').val($(this).text());
		}
	});
	
	if($('#difficultygrid table.jexcel tbody tr').length > <?php echo count($availableLengths); ?>){
	   var toomany=($('#difficultygrid table.jexcel tbody tr').length-<?php echo count($availableLengths); ?>);
		for(var i=1; i<=toomany; i++){
			$('#difficultygrid table.jexcel tbody tr:last').remove();
		}
	}
}

function updateLaborGridValues(){
	$('#laborgrid table.jexcel tbody tr td').each(function(){
		var attr = $(this).attr('data-width');
		if (typeof attr !== typeof undefined && attr !== false) {
			var width=$(this).attr('data-width');
			var length=$(this).attr('data-length');
			$('input.laborlfval[data-sizecombined="'+width+'x'+length+'"]').val($(this).text());
		}
	});
	
	if($('#laborgrid table.jexcel tbody tr').length > <?php echo count($availableLengths); ?>){
	   var toomany=($('#laborgrid table.jexcel tbody tr').length-<?php echo count($availableLengths); ?>);
		for(var i=1; i<=toomany; i++){
			$('#laborgrid table.jexcel tbody tr:last').remove();
		}
	}
}


function gridalignments(){
	$('h3.gridlabel').width(($('#pricegrid table.jexcel').width()-19));
}
	
setInterval('gridalignments()',500);
	
$(function(){
	
	data = [
		<?php
		$collengths=array();
		$colheaders=array();
		
		asort($availableWidths);
		foreach($availableWidths as $width){
			$colheaders[]=$width."\"";
			$collengths[]=130;
		}
		
		asort($availableLengths);
		
		foreach($availableLengths as $length){
			echo "[";//'".$width."\"',";
			$colout='';
			foreach($availableWidths as $width){
				foreach($availableSizes as $sizedata){
					if($sizedata['width'] == $width && $sizedata['length'] == $length){
						$colout .= "'".number_format($sizedata['price'],2,'.','')."',";
					}
				}
			}
			echo substr($colout,0,(strlen($colout)-1));
			echo "],\n";	
		}
		?>
	];
	
	
	
	weightdata = [
		<?php
		$collengths=array();
		$colheaders=array();
		
		asort($availableWidths);
		foreach($availableWidths as $width){
			$colheaders[]=$width."\"";
			$collengths[]=130;
		}
		
		asort($availableLengths);
		
		foreach($availableLengths as $length){
			echo "[";//'".$width."\"',";
			$colout='';
			foreach($availableWidths as $width){
				foreach($availableSizes as $sizedata){
					if($sizedata['width'] == $width && $sizedata['length'] == $length){
						$colout .= "'".number_format($sizedata['weight'],2,'.','')."',";
					}
				}
			}
			echo substr($colout,0,(strlen($colout)-1));
			echo "],\n";	
		}
		?>
	];
	
	
	
	yardsdata = [
		<?php
		$collengths=array();
		$colheaders=array();
		
		asort($availableWidths);
		foreach($availableWidths as $width){
			$colheaders[]=$width."\"";
			$collengths[]=130;
		}
		
		asort($availableLengths);
		
		foreach($availableLengths as $length){
			echo "[";//'".$width."\"',";
			$colout='';
			foreach($availableWidths as $width){
				foreach($availableSizes as $sizedata){
					if($sizedata['width'] == $width && $sizedata['length'] == $length){
						$colout .= "'".number_format($sizedata['yards'],2,'.','')."',";
					}
				}
			}
			echo substr($colout,0,(strlen($colout)-1));
			echo "],\n";	
		}
		?>
	];
	
	
	
	
	difficultydata = [
		<?php
		$collengths=array();
		$colheaders=array();
		
		asort($availableWidths);
		foreach($availableWidths as $width){
			$colheaders[]=$width."\"";
			$collengths[]=130;
		}
		
		asort($availableLengths);
		
		foreach($availableLengths as $length){
			echo "[";//'".$width."\"',";
			$colout='';
			foreach($availableWidths as $width){
				foreach($availableSizes as $sizedata){
					if($sizedata['width'] == $width && $sizedata['length'] == $length){
						$colout .= "'".number_format($sizedata['difficulty'],2,'.','')."',";
					}
				}
			}
			echo substr($colout,0,(strlen($colout)-1));
			echo "],\n";	
		}
		?>
	];
	
	
	
	labordata = [
		<?php
		$collengths=array();
		$colheaders=array();
		
		asort($availableWidths);
		foreach($availableWidths as $width){
			$colheaders[]=$width."\"";
			$collengths[]=130;
		}
		
		asort($availableLengths);
		
		foreach($availableLengths as $length){
			echo "[";//'".$width."\"',";
			$colout='';
			foreach($availableWidths as $width){
				foreach($availableSizes as $sizedata){
					if($sizedata['width'] == $width && $sizedata['length'] == $length){
						$colout .= "'".$sizedata['labor_lf']."',";
					}
				}
			}
			echo substr($colout,0,(strlen($colout)-1));
			echo "],\n";	
		}
		?>
	];
	
	var updateGrid = function (obj, cel, val) {
		/* Get the cell position x, y
		var id = $(cel).prop('id').split('-');
		// If the related series does not exists create a new one
		if (! chart.series[id[1]]) {
			// Create a new series row
			var row = [];
			for (i = 1; i < data[id[1]].length; i++) {
				row.push(parseFloat(data[id[1]][i]));
			}
			// Append new series to the chart
			chart.addSeries({ name:data[id[1]][0], data:row });
		} else {
			// Update the value from the chart
			chart.series[id[1]].data[id[0]-1].update({y:parseFloat(val)});
		}*/
		
		//update all the correct hidden fields
		var gridid=obj.prop('id');
		
		
		
		if(gridid=='pricegrid'){
			//price grid update values
			$('input.priceval[data-sizecombined="'+cel.attr('data-width')+'x'+cel.attr('data-length')+'"]').val(val);
		}else if(gridid == 'weightgrid'){
			//weight grid update values
			$('input.weightval[data-sizecombined="'+cel.attr('data-width')+'x'+cel.attr('data-length')+'"]').val(val);
		}else if(gridid=='yardsgrid'){
			//yards grid update values
			$('input.yardsval[data-sizecombined="'+cel.attr('data-width')+'x'+cel.attr('data-length')+'"]').val(val);
		}else if(gridid=='difficultygrid'){
			//difficulty grid update values
			$('input.difficultyval[data-sizecombined="'+cel.attr('data-width')+'x'+cel.attr('data-length')+'"]').val(val);
		}else if(gridid=='laborgrid'){
			//labor grid update values
			$('input.laborlfval[data-sizecombined="'+cel.attr('data-width')+'x'+cel.attr('data-length')+'"]').val(val);
		}
	}
	
	
	pricechangehandler = function(obj, cell, val) {

		var thisWidth=$(cell).attr('data-width');
		var thisLength=$(cell).attr('data-length');
		var newval=val.replace('$','');
		$('input.priceval[data-sizecombined=\''+thisLength+'x'+thisWidth+'\']').val(newval);
		
		$(cell).text(newval);
		
		if($('#pricegrid table.jexcel tbody tr').length > <?php echo count($availableLengths); ?>){
		   var toomany=($('#pricegrid table.jexcel tbody tr').length-<?php echo count($availableLengths); ?>);
			for(var i=1; i<=toomany; i++){
				$('#pricegrid table.jexcel tbody tr:last').remove();
			}
		}
	};
  
  
  	weightchangehandler = function(obj, cell, val) {

		var thisWidth=$(cell).attr('data-width');
		var thisLength=$(cell).attr('data-length');
		var newval=val.replace('$','');
		$('input.weightval[data-sizecombined=\''+thisLength+'x'+thisWidth+'\']').val(newval);
		
		$(cell).text(newval);
		
		if($('#weightgrid table.jexcel tbody tr').length > <?php echo count($availableLengths); ?>){
		   var toomany=($('#weightgrid table.jexcel tbody tr').length-<?php echo count($availableLengths); ?>);
			for(var i=1; i<=toomany; i++){
				$('#weightgrid table.jexcel tbody tr:last').remove();
			}
		}
	};
	
	
	yardschangehandler = function(obj, cell, val) {

		var thisWidth=$(cell).attr('data-width');
		var thisLength=$(cell).attr('data-length');
		var newval=val.replace('$','');
		$('input.yardsval[data-sizecombined=\''+thisLength+'x'+thisWidth+'\']').val(newval);
		
		$(cell).text(newval);
		
		if($('#yardsgrid table.jexcel tbody tr').length > <?php echo count($availableLengths); ?>){
		   var toomany=($('#yardsgrid table.jexcel tbody tr').length-<?php echo count($availableLengths); ?>);
			for(var i=1; i<=toomany; i++){
				$('#yardsgrid table.jexcel tbody tr:last').remove();
			}
		}
	};
	
	
	difficultychangehandler = function(obj, cell, val) {

		var thisWidth=$(cell).attr('data-width');
		var thisLength=$(cell).attr('data-length');
		var newval=val.replace('$','');
		$('input.difficultyval[data-sizecombined=\''+thisLength+'x'+thisWidth+'\']').val(newval);
		
		$(cell).text(newval);
		
		if($('#difficultygrid table.jexcel tbody tr').length > <?php echo count($availableLengths); ?>){
		   var toomany=($('#difficultygrid table.jexcel tbody tr').length-<?php echo count($availableLengths); ?>);
			for(var i=1; i<=toomany; i++){
				$('#difficultygrid table.jexcel tbody tr:last').remove();
			}
		}
	};
	
	
	laborchangehandler = function(obj, cell, val) {

		var thisWidth=$(cell).attr('data-width');
		var thisLength=$(cell).attr('data-length');
		var newval=val.replace('$','');
		$('input.laborlfval[data-sizecombined=\''+thisLength+'x'+thisWidth+'\']').val(newval);
		
		$(cell).text(newval);
		
		if($('#laborgrid table.jexcel tbody tr').length > <?php echo count($availableLengths); ?>){
		   var toomany=($('#laborgrid table.jexcel tbody tr').length-<?php echo count($availableLengths); ?>);
			for(var i=1; i<=toomany; i++){
				$('#laborgrid table.jexcel tbody tr:last').remove();
			}
		}
	};
	
	

	$('#pricegrid').jexcel({ 
		data:data, 
		colWidths: <?php echo json_encode($collengths); ?>, 
		colHeaders: <?php echo json_encode($colheaders); ?>,
		columnSorting:false,
		onload: function(){
			<?php
			$i=1;
			asort($availableLengths);
			foreach($availableLengths as $length){
				echo "$('#pricegrid table.jexcel tbody tr:nth-of-type(".$i.") td:nth-of-type(1)').text('".$length."\"');\n";
				$i++;
			}
		
			$y=1;
			asort($availableLengths);
			foreach($availableLengths as $length){
				$x=1;
				foreach($availableWidths as $width){
					echo "$('#pricegrid table.jexcel tbody tr:nth-of-type(".$y.") td').attr('data-length','".$length."');
					$('#pricegrid table.jexcel tbody tr td:nth-of-type(".($x+1).")').attr('data-width','".$width."');\n";
					$x++;
				}
				$y++;
			}
			?>
		},
		onchange: pricechangehandler
	});
	
	
	
	$('#weightgrid').jexcel({ 
		data:weightdata, 
		colWidths: <?php echo json_encode($collengths); ?>, 
		colHeaders: <?php echo json_encode($colheaders); ?>,
		columnSorting:false,
		onload: function(){
			<?php
			$i=1;
			asort($availableLengths);
			foreach($availableLengths as $length){
				echo "$('#weightgrid table.jexcel tbody tr:nth-of-type(".$i.") td:nth-of-type(1)').text('".$length."\"');\n";
				$i++;
			}
		
			$y=1;
			asort($availableLengths);
			foreach($availableLengths as $length){
				$x=1;
				foreach($availableWidths as $width){
					echo "$('#weightgrid table.jexcel tbody tr:nth-of-type(".$y.") td').attr('data-length','".$length."');
					$('#weightgrid table.jexcel tbody tr td:nth-of-type(".($x+1).")').attr('data-width','".$width."');\n";
					$x++;
				}
				$y++;
			}
			?>
		},
		onchange: weightchangehandler
	});
	
	
	$('#yardsgrid').jexcel({ 
		data:yardsdata, 
		colWidths: <?php echo json_encode($collengths); ?>, 
		colHeaders: <?php echo json_encode($colheaders); ?>,
		columnSorting:false,
		onload: function(){
			<?php
			$i=1;
			asort($availableLengths);
			foreach($availableLengths as $length){
				echo "$('#yardsgrid table.jexcel tbody tr:nth-of-type(".$i.") td:nth-of-type(1)').text('".$length."\"');\n";
				$i++;
			}
		
			$y=1;
			asort($availableLengths);
			foreach($availableLengths as $length){
				$x=1;
				foreach($availableWidths as $width){
					echo "$('#yardsgrid table.jexcel tbody tr:nth-of-type(".$y.") td').attr('data-length','".$length."');
					$('#yardsgrid table.jexcel tbody tr td:nth-of-type(".($x+1).")').attr('data-width','".$width."');\n";
					$x++;
				}
				$y++;
			}
			?>
		},
		onchange: yardschangehandler
	});
	
	
	
	$('#difficultygrid').jexcel({ 
		data:difficultydata, 
		colWidths: <?php echo json_encode($collengths); ?>, 
		colHeaders: <?php echo json_encode($colheaders); ?>,
		columnSorting:false,
		onload: function(){
			<?php
			$i=1;
			asort($availableLengths);
			foreach($availableLengths as $length){
				echo "$('#difficultygrid table.jexcel tbody tr:nth-of-type(".$i.") td:nth-of-type(1)').text('".$length."\"');\n";
				$i++;
			}
		
			$y=1;
			asort($availableLengths);
			foreach($availableLengths as $length){
				$x=1;
				foreach($availableWidths as $width){
					echo "$('#difficultygrid table.jexcel tbody tr:nth-of-type(".$y.") td').attr('data-length','".$length."');
					$('#difficultygrid table.jexcel tbody tr td:nth-of-type(".($x+1).")').attr('data-width','".$width."');\n";
					$x++;
				}
				$y++;
			}
			?>
		},
		onchange: difficultychangehandler
	});
	
	
	
	$('#laborgrid').jexcel({ 
		data:labordata, 
		colWidths: <?php echo json_encode($collengths); ?>, 
		colHeaders: <?php echo json_encode($colheaders); ?>,
		columnSorting:false,
		onload: function(){
			<?php
			$i=1;
			asort($availableLengths);
			foreach($availableLengths as $length){
				echo "$('#laborgrid table.jexcel tbody tr:nth-of-type(".$i.") td:nth-of-type(1)').text('".$length."\"');\n";
				$i++;
			}
		
			$y=1;
			asort($availableLengths);
			foreach($availableLengths as $length){
				$x=1;
				foreach($availableWidths as $width){
					echo "$('#laborgrid table.jexcel tbody tr:nth-of-type(".$y.") td').attr('data-length','".$length."');
					$('#laborgrid table.jexcel tbody tr td:nth-of-type(".($x+1).")').attr('data-width','".$width."');\n";
					$x++;
				}
				$y++;
			}
			?>
		},
		onchange: laborchangehandler
	});
	
	
	
	
	$('table.wtgrid tbody tr td input').focus(function(){
		$(this).select();
	});
	
	
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
fieldset div.sizeblock{ font-weight:normal !important; font-size:12px !important; display:inline-block; width:185px; float:left; margin-bottom: 8px; }
fieldset input[type=text]{ width:90px; height:22px; font-size:12px; padding:2px; }
fieldset label{ font-weight:normal !important; font-size:12px !important; }
fieldset legend a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }
div.sizeblock input[type=checkbox]{ margin:0 10px 0 0 !important; }

div.coloroptionsblock{ padding:20px 20px 35px 20px; }
div.coloroptionsblock h4{ line-height: 2rem; margin:0 0 10px 0; font-size:16px; font-weight:bold; color:#26337A; border-bottom:2px solid #26337A; }

div.coloroptionsblock h4 a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }

div.colorselection{ width:185px; float:left; height:35px; }
	

	
#currentimage img{ max-width:250px; height:auto; }


div.sizedatarow{ padding:4px 2px 4px 18px; }
div.sizeblock div div.input.text label{ display:inline-block; margin-right:5px; width:70px; }
div.sizeblock div div.input.text input[type=text]{ display:inline-block; width:55px; padding:2px; margin-bottom:0; }
div.sizeblock div.checkbox label{ font-weight:normal !important; font-size:14px !important; }
	
table.wtgrid th{ background:#E1E1E1; padding:5px 0 !important; text-align:center; }
table.wtgrid tbody tr td{ border-bottom:1px solid #555 !important; padding:0 !important; }
table.wtgrid tbody tr td input{ margin:0 0 0 0 !important; font-size:16px; padding:20px 5%; border:0; width:100%; background:transparent !important; text-align: center }
h3.gridlabel{ background:#26337A; text-align: center; color:#FFF; padding:10px; font-size:26px; margin:20px 0 0 0; }
table.wtgrid th.empty{ border:0 !important; background:#26337A !important; }
table.wtgrid tbody tr td:hover{ background:#f8f8f8; }


table.jexcel tbody tr td:nth-of-type(1),
table.jexcel thead td{ background:#E1E1E1; color:#26337A; text-align:center; font-weight:bold; }

table.jexcel thead td:nth-of-type(1){ background:#26337A !important; border-top:0 !important; border-left:0 !important; }
table.jexcel tr td:nth-of-type(1){ width:100px !important; }

table.jexcel tbody tr td{ text-align:center; }
</style>