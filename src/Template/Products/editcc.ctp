<script src="/js/jquery.jexcel.js"></script>
<link rel="stylesheet" href="/css/jquery.jexcel.css" type="text/css" />

<!-- src/Template/Products/editcc.ctp -->
<h3>Edit Cubicle Curtain:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo $this->Form->input('title',['label'=>'Name of this CC Product','required'=>true,'value'=>$ccData['title']]);

echo $this->Form->label('Does this product have Mesh?');
echo $this->Form->radio('has_mesh',[1=>'Yes',0=>'No'],['value'=>$ccData['has_mesh']]);

echo $this->Form->input('qb_item_code',['label'=>'QuickBooks Item Code/Number','required'=>true,'value'=>$ccData['qb_item_code']]);


echo "<div id=\"fabricselector\"><h3>Select a Fabric</h3>";
echo "<select id=\"fabricid\" name=\"fabric_name\" onchange=\"changeFabricColorOptions(this.value)\">
<option value=\"0\" selected disabled>--Select A Fabric--</option>";
foreach($thefabrics as $fabricname => $fabricdata){
	echo "<option value=\"".$fabricname."\"";
	if($fabricname == $ccData['fabric_name']){
		echo " selected";
	}
	echo ">".$fabricname."</option>\n";
}
echo "</select>";
echo "</div><br>";

$thisavailcolors=json_decode($ccData['available_colors'],true);
echo "<div id=\"fabricselectionwrap\">";
foreach($thefabrics as $fabricname => $fabriccolors){
	if($fabricname == $ccData['fabric_name']){
		echo "<div id=\"colorselections_".str_replace("'","",str_replace(" ","_",$fabricname))."\" class=\"coloroptionsblock\">
		<h4>Select Which <em>".$fabricname."</em> Colors You Want Available For This Window Treatment <a href=\"javascript:checkallcolors('".str_replace("'","\'",$fabricname)."');\">Check All</a> <a href=\"javascript:uncheckallcolors('".str_replace("'","\'",$fabricname)."');\">Uncheck All</a></h4>";
		foreach($fabriccolors as $num => $color){
			echo "<!--";
			print_r($color);
			echo "-->";
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
	echo "<div id=\"colorselections_".str_replace(" ","_",$fabricname)."\" class=\"coloroptionsblock\"";
	if($fabricname != $ccData['fabric_name']){
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
}*/


echo $this->Form->input('description',['label'=>'Cubicle Curtain Description','type'=>'textarea','required'=>false,'value'=>$ccData['description']]);

//image
echo "<div style=\"padding:10px 25px;\" id=\"currentimage\">";
echo "<div><img src=\"/files/cubicle-curtains/".$ccData['id']."/".$ccData['image_file']."\" /></div>";
echo "<div style=\"padding:10px 0;\"><a href=\"javascript:changeImage();\">Change Image</a></div>";
echo "</div>";

echo "<div id=\"newimage\" style=\"padding:10px 25px; visibility:hidden; height:0px;\">";
echo $this->Form->input('new_image',['label'=>'New Cubicle Curtain Image','required'=>false,'type'=>'file']);
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
echo "<h3 class=\"gridlabel\">Price Grid</h3>";//<h4 class=\"gridsublabelx\">Finished Length</h4><h4 class=\"gridsublabely\">Cut Width</h4>";

//price grid

/*
echo "<table class=\"ccgrid dollars\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
echo "<thead>
<tr class=\"overall_x_label\">
	<th colspan=\"2\">&nbsp;</th>
	<th colspan=\"".count($availableLengths)."\" align=\"center\">LENGTH</th>
</tr>
<tr>
<th rowspan=\"".count($availableWidths)."\" valign=\"middle\">WIDTHS</th>
<th class=\"empty\">&nbsp;</th>";
foreach($availableLengths as $length){
	echo "<th><b>".$length."&quot;</b></th>";
}
echo "</tr>
</thead><tbody>";*/
foreach($availableWidths as $width){
	//echo "<tr>
		//<th><b>".$width."&quot;</b></td>";
		foreach($availableLengths as $length){
			echo "<input type=\"hidden\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_price\" data-sizewidth=\"".$width."\" class=\"priceval\" data-sizelength=\"".$length."\" data-sizecombined=\"".$width."x".$length."\" value=\"".number_format($sizedata['price'],2,'.','');
				}
			}
			echo "\" />";
			
			echo "<input type=\"hidden\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_weight\" data-sizewidth=\"".$width."\" class=\"weightval\" data-sizelength=\"".$length."\" data-sizecombined=\"".$width."x".$length."\" value=\"".number_format($sizedata['weight'],2,'.','');
				}
			}
			echo "\" />";
			
			echo "<input type=\"hidden\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_yards\" data-sizewidth=\"".$width."\" class=\"yardsval\" data-sizelength=\"".$length."\" data-sizecombined=\"".$width."x".$length."\" value=\"".number_format($sizedata['yards'],2,'.','');
				}
			}
			echo "\" />";
			
			echo "<input type=\"hidden\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_difficulty\" data-sizewidth=\"".$width."\" class=\"difficultyval\" data-sizelength=\"".$length."\" data-sizecombined=\"".$width."x".$length."\" value=\"".number_format($sizedata['difficulty'],2,'.','');
				}
			}
			echo "\" />";
			
			echo "<input type=\"hidden\" name=\"size_";
			foreach($availableSizes as $sizedata){
				if($sizedata['width'] == $width && $sizedata['length'] == $length){
					echo $sizedata['id']."_laborlf\" data-sizewidth=\"".$width."\" class=\"laborlfval\" data-sizelength=\"".$length."\" data-sizecombined=\"".$width."x".$length."\" value=\"".number_format($sizedata['laborlf'],2,'.','');
				}
			}
			echo "\" />";
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
echo "<table class=\"ccgrid\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
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
echo "<table class=\"ccgrid\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
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
echo "<table class=\"ccgrid\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
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
echo "<h3 class=\"gridlabel\">Labor Grid</h3>";
/*
echo "<table class=\"ccgrid\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
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
		var attr = $(this).attr('data-width');
		if (typeof attr !== typeof undefined && attr !== false) {
			var width=$(this).attr('data-width');
			var length=$(this).attr('data-length');
			$('input.priceval[data-sizecombined="'+width+'x'+length+'"]').val($(this).text().replace('$','').replace(',',''));
			$(this).text($(this).text().replace('$','').replace(',',''));
		}
	});
	
	if($('#pricegrid table.jexcel tbody tr').length > <?php echo count($availableWidths); ?>){
	   var toomany=($('#pricegrid table.jexcel tbody tr').length-<?php echo count($availableWidths); ?>);
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
	
	if($('#weightgrid table.jexcel tbody tr').length > <?php echo count($availableWidths); ?>){
	   var toomany=($('#weightgrid table.jexcel tbody tr').length-<?php echo count($availableWidths); ?>);
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
	
	if($('#yardsgrid table.jexcel tbody tr').length > <?php echo count($availableWidths); ?>){
	   var toomany=($('#yardsgrid table.jexcel tbody tr').length-<?php echo count($availableWidths); ?>);
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
	
	if($('#difficultygrid table.jexcel tbody tr').length > <?php echo count($availableWidths); ?>){
	   var toomany=($('#difficultygrid table.jexcel tbody tr').length-<?php echo count($availableWidths); ?>);
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
	
	if($('#laborgrid table.jexcel tbody tr').length > <?php echo count($availableWidths); ?>){
	   var toomany=($('#laborgrid table.jexcel tbody tr').length-<?php echo count($availableWidths); ?>);
		for(var i=1; i<=toomany; i++){
			$('#laborgrid table.jexcel tbody tr:last').remove();
		}
	}
}
	
	
$(function(){
	
	data = [
		<?php
		$colwidths=array();
		$colheaders=array();
		
		asort($availableWidths);
		foreach($availableLengths as $length){
			$colheaders[]=$length."\"";
		}
		
		asort($availableLengths);
		
		foreach($availableWidths as $width){
			$colwidths[]=130;
			echo "[";//'".$width."\"',";
			$colout='';
			foreach($availableLengths as $length){
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
		$colwidths=array();
		$colheaders=array();
		
		asort($availableWidths);
		foreach($availableLengths as $length){
			$colheaders[]=$length."\"";
		}
		
		asort($availableLengths);
		
		foreach($availableWidths as $width){
			$colwidths[]=130;
			echo "[";//'".$width."\"',";
			$colout='';
			foreach($availableLengths as $length){
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
		$colwidths=array();
		$colheaders=array();
		
		asort($availableWidths);
		foreach($availableLengths as $length){
			$colheaders[]=$length."\"";
		}
		
		asort($availableLengths);
		
		foreach($availableWidths as $width){
			$colwidths[]=130;
			echo "[";//'".$width."\"',";
			$colout='';
			foreach($availableLengths as $length){
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
		$colwidths=array();
		$colheaders=array();
		
		asort($availableWidths);
		foreach($availableLengths as $length){
			$colheaders[]=$length."\"";
		}
		
		asort($availableLengths);
		
		foreach($availableWidths as $width){
			$colwidths[]=130;
			echo "[";//'".$width."\"',";
			$colout='';
			foreach($availableLengths as $length){
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
		$colwidths=array();
		$colheaders=array();
		
		asort($availableWidths);
		foreach($availableLengths as $length){
			$colheaders[]=$length."\"";
		}
		
		asort($availableLengths);
		
		foreach($availableWidths as $width){
			$colwidths[]=130;
			echo "[";//'".$width."\"',";
			$colout='';
			foreach($availableLengths as $length){
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
	
	
	
	

	function vardump(obj) {
		var out = '';
		for (var i in obj) {
			out += i + ": " + obj[i] + "\n";
		}

		alert(out);

		// or, if you wanted to avoid alerts...

		var pre = document.createElement('pre');
		pre.innerHTML = out;
		document.body.appendChild(pre)
	}
	
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
	
	
	

	$('#pricegrid').jexcel({ 
		data:data, 
		colWidths: <?php echo json_encode($colwidths); ?>, 
		colHeaders: <?php echo json_encode($colheaders); ?>,
		columnSorting:false,
		onload: function(){
			<?php
			$i=1;
			asort($availableWidths);
			foreach($availableWidths as $width){
				echo "$('#pricegrid table.jexcel tbody tr:nth-of-type(".$i.") td:nth-of-type(1)').text('".$width."\"');\n";
				$i++;
			}
		
			$y=1;
			asort($availableLengths);
			foreach($availableWidths as $width){
				$x=1;
				foreach($availableLengths as $length){
					echo "$('#pricegrid table.jexcel tbody tr:nth-of-type(".$y.") td').attr('data-width','".$width."');
					$('#pricegrid table.jexcel tbody tr td:nth-of-type(".($x+1).")').attr('data-length','".$length."');\n";
					$x++;
				}
				$y++;
			}
			?>
		
			setInterval('updatePriceGridValues()',500);
			setInterval('updateWeightGridValues()',500);
			setInterval('updateYardsGridValues()',500);
			setInterval('updateDifficultyGridValues()',500);
			setInterval('updateLaborGridValues()',500);
		}
	});
	
	
	
	$('#weightgrid').jexcel({ 
		data:weightdata, 
		colWidths: <?php echo json_encode($colwidths); ?>, 
		colHeaders: <?php echo json_encode($colheaders); ?>,
		columnSorting:false,
		onload: function(){
			<?php
			$i=1;
			asort($availableWidths);
			foreach($availableWidths as $width){
				echo "$('#weightgrid table.jexcel tbody tr:nth-of-type(".$i.") td:nth-of-type(1)').text('".$width."\"');\n";
				$i++;
			}
		
			$y=1;
			asort($availableLengths);
			foreach($availableWidths as $width){
				$x=1;
				foreach($availableLengths as $length){
					echo "$('#weightgrid table.jexcel tbody tr:nth-of-type(".$y.") td').attr('data-width','".$width."');
					$('#weightgrid table.jexcel tbody tr td:nth-of-type(".($x+1).")').attr('data-length','".$length."');\n";
					$x++;
				}
				$y++;
			}
			?>
		}
	});
	
	
	$('#yardsgrid').jexcel({ 
		data:yardsdata, 
		colWidths: <?php echo json_encode($colwidths); ?>, 
		colHeaders: <?php echo json_encode($colheaders); ?>,
		columnSorting:false,
		onload: function(){
			<?php
			$i=1;
			asort($availableWidths);
			foreach($availableWidths as $width){
				echo "$('#yardsgrid table.jexcel tbody tr:nth-of-type(".$i.") td:nth-of-type(1)').text('".$width."\"');\n";
				$i++;
			}
		
			$y=1;
			asort($availableLengths);
			foreach($availableWidths as $width){
				$x=1;
				foreach($availableLengths as $length){
					echo "$('#yardsgrid table.jexcel tbody tr:nth-of-type(".$y.") td').attr('data-width','".$width."');
					$('#yardsgrid table.jexcel tbody tr td:nth-of-type(".($x+1).")').attr('data-length','".$length."');\n";
					$x++;
				}
				$y++;
			}
			?>
		}
	});
	
	
	
	$('#difficultygrid').jexcel({ 
		data:difficultydata, 
		colWidths: <?php echo json_encode($colwidths); ?>, 
		colHeaders: <?php echo json_encode($colheaders); ?>,
		columnSorting:false,
		onload: function(){
			<?php
			$i=1;
			asort($availableWidths);
			foreach($availableWidths as $width){
				echo "$('#difficultygrid table.jexcel tbody tr:nth-of-type(".$i.") td:nth-of-type(1)').text('".$width."\"');\n";
				$i++;
			}
		
			$y=1;
			asort($availableLengths);
			foreach($availableWidths as $width){
				$x=1;
				foreach($availableLengths as $length){
					echo "$('#difficultygrid table.jexcel tbody tr:nth-of-type(".$y.") td').attr('data-width','".$width."');
					$('#difficultygrid table.jexcel tbody tr td:nth-of-type(".($x+1).")').attr('data-length','".$length."');\n";
					$x++;
				}
				$y++;
			}
			?>
		}
	});
	
	
	
	$('#laborgrid').jexcel({ 
		data:labordata, 
		colWidths: <?php echo json_encode($colwidths); ?>, 
		colHeaders: <?php echo json_encode($colheaders); ?>,
		columnSorting:false,
		onload: function(){
			<?php
			$i=1;
			asort($availableWidths);
			foreach($availableWidths as $width){
				echo "$('#laborgrid table.jexcel tbody tr:nth-of-type(".$i.") td:nth-of-type(1)').text('".$width."\"');\n";
				$i++;
			}
		
			$y=1;
			asort($availableLengths);
			foreach($availableWidths as $width){
				$x=1;
				foreach($availableLengths as $length){
					echo "$('#laborgrid table.jexcel tbody tr:nth-of-type(".$y.") td').attr('data-width','".$width."');
					$('#laborgrid table.jexcel tbody tr td:nth-of-type(".($x+1).")').attr('data-length','".$length."');\n";
					$x++;
				}
				$y++;
			}
			?>
		}
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
	
	
	$('table.ccgrid tbody tr td input').focus(function(){
		$(this).select();
	});
	
});
	
	
	
function changeFabricColorOptions(fabricname){
	//fabricname=fabricname.replace(/ /g,"_");
	//fabricname=fabricname.replace("'","");
	$('div.coloroptionsblock input[type=checkbox]').removeProp('checked');
	//$.get('/products/getwtfabriccolorcheckboxes/<?php echo $ccData['id']; ?>/'+encodeURIComponent(fabricname),
	$.get('/products/getfabriccolorscheckboxes/cc/'+encodeURIComponent(fabricname),
	function(result){
		$('#fabricselectionwrap').html(result);
	});
}	
	
/*function changeFabricColorOptions(newcolor){
	var color=newcolor.replace(/ /g,"_");
	
	$('div.coloroptionsblock input[type=checkbox]').removeProp('checked');
	
	$('div.coloroptionsblock').hide('fast');
	
	$('#colorselections_'+color).show('fast');
}*/
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
	
table.ccgrid th{ background:#E1E1E1; padding:5px 0 !important; text-align:center; }
table.ccgrid tbody tr td{ border-bottom:1px solid #555 !important; padding:0 !important; }
table.ccgrid tbody tr td input{ margin:0 0 0 0 !important; font-size:16px; padding:20px 5%; border:0; width:100%; background:transparent !important; text-align: center }
h3.gridlabel{ background:#26337A; text-align: center; color:#FFF; padding:10px; font-size:26px; margin:20px 0 0 0; }

h4.gridsublabelx{ background:#26337A; text-align: center; color:#FFF; padding:10px; font-size:14px; margin:0px 0 0 0; }
h4.gridsublabely{ background:#26337A; text-align: center; color:#FFF; padding:10px; font-size:14px; margin:0px 0 0 0; }

table.ccgrid th.empty{ border:0 !important; background:#26337A !important; }
table.ccgrid tbody tr td:hover{ background:#f8f8f8; }


table.jexcel tbody tr td:nth-of-type(1),
table.jexcel thead td{ background:#E1E1E1; color:#26337A; text-align:center; font-weight:bold; }

table.jexcel thead td:nth-of-type(1){ background:#26337A !important; border-top:0 !important; border-left:0 !important; }
table.jexcel tr td:nth-of-type(1){ width:100px !important; }

table.jexcel tbody tr td{ text-align:center; }
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