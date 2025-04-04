<!-- src/Template/Quotes/newlineitem.ctp -->
<script>
function str_replace(search, replace, subject, countObj) {
  var i = 0
  var j = 0
  var temp = ''
  var repl = ''
  var sl = 0
  var fl = 0
  var f = [].concat(search)
  var r = [].concat(replace)
  var s = subject
  var ra = Object.prototype.toString.call(r) === '[object Array]'
  var sa = Object.prototype.toString.call(s) === '[object Array]'
  s = [].concat(s)

  var $global = (typeof window !== 'undefined' ? window : global)
  $global.$locutus = $global.$locutus || {}
  var $locutus = $global.$locutus
  $locutus.php = $locutus.php || {}

  if (typeof (search) === 'object' && typeof (replace) === 'string') {
    temp = replace
    replace = []
    for (i = 0; i < search.length; i += 1) {
      replace[i] = temp
    }
    temp = ''
    r = [].concat(replace)
    ra = Object.prototype.toString.call(r) === '[object Array]'
  }

  if (typeof countObj !== 'undefined') {
    countObj.value = 0
  }

  for (i = 0, sl = s.length; i < sl; i++) {
    if (s[i] === '') {
      continue
    }
    for (j = 0, fl = f.length; j < fl; j++) {
      temp = s[i] + ''
      repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0]
      s[i] = (temp).split(f[j]).join(repl)
      if (typeof countObj !== 'undefined') {
        countObj.value += ((temp.split(f[j])).length - 1)
      }
    }
  }
  return sa ? s : s[0]
}

function escapeTextFieldforURL(thetext){
	var output = str_replace('?','__question__',thetext);
	output = str_replace('#','__pound__',output);
	output = str_replace(' ','__space__',output);
	output = str_replace('/','__slash__',output);
	return output;
}
</script>

<?php
switch($type){
	case "simple":
		echo "<input type=\"hidden\" name=\"quoteID\" id=\"quoteID\" value=\"".$quoteID."\" />";
		echo "<input type=\"hidden\" name=\"customer_id\" id=\"customerID\" value=\"".$quoteData['customer_id']."\" />";
		echo "<h3>Add A Price List Item to Quote</h3><hr>";
		echo "<p id=\"selectproduct\" style=\"visibility:hidden;height:0;width:0;\">
		<label for=\"productcat\">Select a Product</label>
			<select id=\"product\">
				<option value=\"0\" selected disabled>--Products--</option>
				<option value=\"bedspread\">Bedspreads</option>
				<option value=\"cubicle-curtain\">Cubicle or Shower Curtains</option>
				<option value=\"window-treatment\">Window Treatments</option>
				<option value=\"track-system\">Track Systems</option>
				<option value=\"service\">Services</option>
				<option value=\"miscellaneous\">Miscellaneous</option>	
			</select>
		</p>";
		
		if(isset($subaction) && $subaction == 'cubicle-curtain'){
			echo "<script>$(function(){ $('#product').val('cubicle-curtain'); setproducttype(); });</script>";
		}
		if(isset($subaction) && $subaction == 'bedspread'){
			echo "<script>$(function(){ $('#product').val('bedspread'); setproducttype(); });</script>";
		}
		if(isset($subaction) && $subaction == 'window-treatment'){
			echo "<script>$(function(){ $('#product').val('window-treatment'); setproducttype(); });</script>";
		}
		if(isset($subaction) && $subaction == 'services'){
			echo "<script>$(function(){ $('#product').val('service'); setproducttype(); });</script>";
		}
		if(isset($subaction) && $subaction == 'track-system'){
			echo "<script>$(function(){ $('#product').val('track-system'); setproducttype(); });</script>";
		}
		

		echo "<p><label for=\"location\">Location</label> <input type=\"text\" name=\"location\" id=\"location\" /></p>";

		echo "<div id=\"databox\"></div>";
		



		
		if(isset($subaction) && $subaction == 'track-system'){
			echo "<p id=\"qtyline\" style=\"display:none;\"><b></b> <input type=\"number\" id=\"qtyvalue\" style=\"width:160px;\" value=\"1\" min=\"1\" /> <button id=\"addsimpleproducttoquote\">Add to Quote</button></p>";

			echo "<script>
			function changetrackitem(){
					$('#qtyline b').html($('select[name=system_id] option:selected').attr('data-unitlabel')+':');
					$('#qtyline').show();
			}
			</script>";
		}else{
			echo "<p id=\"qtyline\"><b>";
			echo "QTY";
			echo ":</b> <input type=\"number\" id=\"qtyvalue\" value=\"1\" min=\"1\" /> <button id=\"addsimpleproducttoquote\">Add to Quote</button></p>";
		}

		
		echo "<style>";
		echo "p select{ padding:10px; width:65%; }
		#descriptionvalue{ width:90%; padding:10px; }
		#qtyvalue{ width:55px; padding:10px; }
		";

		
		echo "</style>";
		echo "<script>
		var variationID=0;
		
		function setVariationAttributeSelection(attributeID,variationIDvalue){
			//get the accurate price from ajax, then display the price
			var newval=parseFloat($('select#attribute_'+attributeID+'_values').find('option:selected').attr('data-pricevalue'));
			//alert(newval);
			variationID=variationIDvalue;
			$('#priceeachvalue').html(newval.toFixed(2));
			$('#priceeachline').show('fast');
		}
		
		function setproducttype(){
			if($('#product').val() != '0'){
					$('#databox').html('');
					parent.$.fancybox.showLoading();
					$.get('/quotes/getproductselectlist/'+$('#product').val(),function(data){
						//fill the dropdown results
						$('#databox').append(data);
						parent.$.fancybox.hideLoading();
					});
				}
		}
		
		$(function(){
			$('#product').change(function(){
				setproducttype();
			});
			
			$('#addsimpleproducttoquote').click(function(){
				

				if($('#quilted').is(':checked')){
					var isquilted=1;
				}else{
					var isquilted=0;
				}

";

if(isset($subaction) && $subaction=='bedspread'){
	echo "
		if($('#bssize').val() == '' || $('#bssize').val() == '0' || $('#bssize').val() == null || $('#bssize').val() === 'undefined'){
					alert('You must select a Bedspread Size before continuing.');
					return false;
			}else{

			parent.$.fancybox.showLoading();
			
			";
}
echo "

    if($('#fl-short').is(':visible') && $('#fl-short').val() != ''){
        var shortpointval=$('#fl-short').val();
    }else{
        var shortpointval='no';
    }

	if($('#usealias').is(':visible') && $('#usealias').is(':checked')){
		var usealiasyesno='yes';
	}else{
		var usealiasyesno='no';
	}
	
";

	if(isset($subaction) && $subaction == 'bedspread'){
	echo "
		if($('select#bssize option:selected').attr('data-specialpricing') == '1'){
			var specialpricingyesno='1';
		}else{
			var specialpricingyesno='0';
		}
	";
	}


if(isset($subaction) && $subaction == 'cubicle-curtain'){

	echo "
			if($('input[name=finished_width]').val() == ''){
				var usefinishwidthorno='no';
			}else{
				var usefinishwidthorno=$('input[name=finished_width]').val();
			}
			";

}


echo "
				$.get('/quotes/";
		
		if(isset($subaction) && $subaction == 'cubicle-curtain'){


			echo "addcctoquote/".$quoteID."/'+$('#fabricid').val()+'/'+$('#fabricidwithcolor').val()+'/'+$('#ccid').val()+'/'+$('#sizeid').val()+'/'+$('#qtyvalue').val()+'/'+parseFloat($('#priceeachvalue').html().replace('$',''))+'/'+$('#length').val()+'/'+$('#meshoption').val()+'/'+$('#weight').val()+'/'+$('#yards').val()+'/'+$('#labor-lf').val()+'/'+$('#difficulty').val()+'/'+$('#specialpricing').val()+'/'+usefinishwidthorno+'/'+usealiasyesno+'/'+escapeTextFieldforURL($('#location').val())";

			//$quoteID,$fabricname,$fabricid,$ccid,$sizeID,$qty,$priceeach,$length,$mesh,$weight,$yards,$lf,$difficulty

		}elseif(isset($subaction) && $subaction == 'bedspread'){

			echo "addbstoquote/".$quoteID."/'+$('#fabricid').val()+'/'+$('#fabricidwithcolor').val()+'/'+$('select#bssize option:selected').attr('data-bsid')+'/'+$('#sizeid').val()+'/'+$('#qtyvalue').val()+'/'+$('#price').val()+'/'+$('#weight').val()+'/'+$('#difficulty').val()+'/'+$('#yards').val()+'/'+isquilted+'/'+$('#mattresssize').val()+'/'+$('#topwidthsl').val()+'/'+$('#dropwidthsl').val()+'/'+$('#quiltpattern').val()+'/'+$('#topcutsw').val()+'/'+$('#dropcutsw').val()+'/'+$('#repeat').val()+'/'+usealiasyesno+'/'+specialpricingyesno+'/'+$('#layout').val()+'/'+escapeTextFieldforURL($('#location').val())";
			
			

		}elseif(isset($subaction) && $subaction == 'services'){
			echo "addservicetoquote/".$quoteID."/'+$('#serviceid').val()+'/'+$('#priceinput').val()+'/'+$('#qtyvalue').val()+'/0/0/'+escapeTextFieldforURL($('#location').val())";
		}elseif(isset($subaction) && $subaction=='window-treatment'){
			
			echo "addwttoquote/".$quoteID."/'+$('#wttype').val()+'/'+$('#fabricid').val()+'/'+$('#fabricidwithcolor').val()+'/'+$('#wtid').val()+'/'+$('#sizeid').val()+'/'+$('#qtyvalue').val()+'/'+$('#price').val()+'/'+$('#weight').val()+'/'+$('#difficulty').val()+'/'+$('#yards').val()+'/'+$('#haswelts').val()+'/'+$('#liningoption').val()+'/'+$('#pairorpanel').val()+'/'+$('#labor-lf').val()+'/'+$('#specialpricing').val()+'/'+$('#length').val()+'/'+usealiasyesno+'/'+shortpointval+'/'+escapeTextFieldforURL($('#location').val())";
			//$quoteID,$wttype,$fabricid,$fabricwithcolorid,$wtID,$wtSizeID,$qty,$priceEach,$weight,$diff,$yards,$haswelts
		}elseif(isset($subaction) && $subaction=='track-system'){
			//echo "addtracktoquote/".$quoteID."/'";
			echo "addtracktoquote/".$quoteID."/'+$('select[name=system_id]').val()+'/'+$('#qtyvalue').val()+'/'+$('select[name=system_id] option:selected').attr('data-price')+'/'+encodeURIComponent($('#description').val())+'/'+escapeTextFieldforURL($('#location').val())";
			//addtracktoquote($quoteID,$trackItemID,$qty,$price)
		}
		

		echo ",function(response){
					if(response=='OK'){
						location.replace('/quotes/add/".$quoteID."/');
					}
				});

				";

			if(isset($subaction) && $subaction=='bedspread'){
				echo "}
				";
			}
		
		echo "
			});
			
		});
		</script>";
	break;
	case "calculator":
		switch($subsubaction){
			default:
				echo "<style>
  .ui-autocomplete-loading {
    background: white url('/img/ui-anim_basic_16x16.gif') right center no-repeat;
  }
</style>
<fieldset id=\"calculatorfabric\"><legend>Fabric Selection</legend>";
				
				if($quoteID==0){
					$thefabrics=array('0'=>'--CUSTOM / UNLISTED--');
				}else{
					$thefabrics=array('0'=>'--Create A New Fabric--');
				}

				
				foreach($fabrics as $fabric){
					if(!in_array($fabric['fabric_name'],$thefabrics)){
						$thefabrics[$fabric['fabric_name']]=$fabric['fabric_name'];
					}
				}
				//echo "<p>".$this->Form->select('fabric_name',$thefabrics,['empty'=>'--Select A Fabric--'])."</p>
				
				
				echo '<div class="ui-widget"><label>Search Fabrics:</label> <input type="text" name="fabric_name" /></div>';
				
				echo "</fieldset>";
				
				echo "<script>
                    $(function(){
                        $('input[name=fabric_name]').autocomplete({
                           source: function( request, response){
                               $.ajax({
                                  'url': '/products/searchfabricsfield/".$subaction."/'+request.term,
                                  'dataType':'json',
                                  success: function(data){
                                      response(data);
                                  }
                               });
                           },
                           minLength: 2,
                           select: function(event,ui){
                               $.fancybox.showLoading();
                               $.get('/quotes/getfabriccolorlist/'+encodeURIComponent(ui.item.value)+'/'+".$quoteID.",function(response){
                    				$('#coloroptionwrap').html(response);
                    				$.fancybox.hideLoading();
                    			});
                           }
                        });
                    });
                    </script>";
				
				echo "<fieldset><legend>Color Selection</legend>";
				echo "<div id=\"coloroptionwrap\"><em>Please search and select Fabric above.</em></div>";
				echo "</fieldset>";
				



				echo "<script>
				function doconfirmaliascontinue(fabricid){
					if($('#usealiasconfirm').is(':checked')){
						var usealias=1;
					}else{
						var usealias=0;
					}
					location.replace('/quotes/newlineitem/".$quoteID."/calculator/".$subaction."/'+fabricid+'/'+usealias);
				}

				function doselectfabriccolor(fabricid,hasalias){
					
					if(hasalias==0){
						location.replace('/quotes/newlineitem/".$quoteID."/calculator/".$subaction."/'+fabricid);
					}else{
						$.fancybox({
							'type':'html',
							'width':500,
							'height':110,
							'autoSize':false,
							'content':'<center><input type=\"checkbox\" checked=\"checked\" id=\"usealiasconfirm\" name=\"usealias\" value=\"yes\" />Use Alias <u>'+$('div.colorswatch[data-fabricid=\''+fabricid+'\'] span.aliasname').text()+'</u><br><button type=\"button\" onclick=\"doconfirmaliascontinue('+fabricid+');\">GO</button></center>',
							'modal':true
						});
					}
				}
				</script>";

			break;
		}
		
	break;
	case "custom":
		
		if($lineitemdata['product_type'] == 'track_systems'){

			echo $this->Form->create(null);
			echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);
			

			if(isset($lineitemmetadata['_component_numlines']) && intval($lineitemmetadata['_component_numlines']) >0){
				$isEdit=true;
				echo $this->Form->input('isedit',['type'=>'hidden','value'=>1]);
				echo $this->Form->input('numlines',['type'=>'hidden','value'=>$lineitemmetadata['_component_numlines']]);
				echo "<h3>Edit Track System Components</h3>";
			}else{
				$isEdit=false;
				echo $this->Form->input('isedit',['type'=>'hidden','value'=>0]);
				echo $this->Form->input('numlines',['type'=>'hidden','value'=>'0']);
				echo "<h3>Add Track System Components</h3>";
			}



			echo "<div id=\"trackcomponentwrap\">
			<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
			<thead>
			<tr>
			<th width=\"7%\">#</th>
			<th width=\"36%\">COMPONENT</th>
			<th width=\"15%\">QTY</th>
			<th width=\"18%\">INCHES</th>
			<th width=\"18%\">COMMENT</th>
			<th width=\"6%\">&nbsp;</th>
			</tr>
			</thead>
			<tbody>";

			if($isEdit){
				for($si=1; $si <= intval($lineitemmetadata['_component_numlines']); $si++){
					echo "<tr>
					<td class=\"linenumber\">".$si."</td>
					<td><select onchange=\"calculateLF(".$si.");\" name=\"component_id_line_".$si."\">";
					foreach($componentsarr as $componentRow){
						echo "<option value=\"".$componentRow['id']."\" data-inches-equivalent=\"".$componentRow['inches_equivalent']."\"";
						if($componentRow['id'] == $lineitemmetadata["_component_".$si."_componentid"]){
							echo " selected";
						}
						echo ">".$componentRow['title']."</option>";
					}
					echo "</select></td>
					<td><input onchange=\"calculateLF();\" type=\"number\" name=\"qty_line_".$si."\" class=\"qty\" value=\"".$lineitemmetadata["_component_".$si."_qty"]."\" /></td>
					<td><input onchange=\"calculateLF();\" type=\"number\" name=\"inches_line_".$si."\" step=\"any\" class=\"inches\" value=\"".$lineitemmetadata["_component_".$si."_inches"]."\" /></td>
					<td><input onchange=\"calculateLF();\" type=\"text\" name=\"comment_line_".$si."\" class=\"comment\" value=\"".$lineitemmetadata["_component_".$si."_comment"]."\" /></td>
					<td><button type=\"button\" onclick=\"deleteRow(".$si.")\" class=\"delete\">Delete</button></td>
					</tr>";
				}
			}

			echo "</tbody>
			<tfoot>
			<tr>
			<td colspan=\"4\" align=\"right\">QUOTE LF</td>
			<td colspan=\"2\"><b>".$lineitemdata['qty']."</b></td>
			</tr>
			<tr>
			<td colspan=\"4\" align=\"right\">COMPONENTS LF</td>
			<td colspan=\"2\"><input name=\"component_lf_total\" value=\"0\" id=\"componentlftotal\" /></td>
			</tr>
			</tfoot>
			</table>
			<div id=\"percentdiff\">Difference: <span id=\"differencevalue\">0%</span></div>

			<p style=\"text-align:right;\"><button onclick=\"addTrackComponent()\" type=\"button\" style=\"padding:5px 10px; font-size:12px; background:#444; color:#FFF; border:1px solid #000;\">+ Add Line</button></p>
			
			<div id=\"reallybad\"><img src=\"/img/delete.png\" /> <b>Difference is more than 12%</b></div>
			<br><br></div>";

			echo "<script>";
			if($isEdit){
				echo "
				var numlines=".$lineitemmetadata['_component_numlines'].";";
			}else{
				echo "
				var numlines=0;";
			}
			echo "
			function addTrackComponent(){
				var newline=(numlines+1);
				$('#trackcomponentwrap table tbody').append('<tr><td class=\"linenumber\">'+newline+'</td><td><select onchange=\"calculateLF('+newline+');\" name=\"component_id_line_'+newline+'\"><option value=\"0\" selected disabled>--Select Component--</option>";
				echo $allchildcomponents;
				echo "</select></td><td><input onchange=\"calculateLF();\" type=\"number\" class=\"qty\" name=\"qty_line_'+newline+'\" min=\"0\" value=\"0\" /></td><td><input onchange=\"calculateLF();\" class=\"inches\" step=\"any\" type=\"number\" min=\"0\" value=\"0\" name=\"inches_line_'+newline+'\" /></td><td><input onchange=\"calculateLF();\" type=\"text\" class=\"comment\" name=\"comment_line_'+newline+'\" /></td><td><button class=\"delete\" type=\"button\" onclick=\"deleteRow('+newline+')\">Delete</button></td></tr>');
				numlines++;
				$('#numlines').val(numlines);
			}

			function deleteRow(rownumber){
				$('#trackcomponentwrap table tbody tr:nth-child('+rownumber+')').remove();
				renumberRows();
			}

			function renumberRows(){
				var i=1;
				$('#trackcomponentwrap table tbody tr').each(function(){

					$(this).find('td.linenumber').html(i);

					//rename selectbox for this row
					$(this).find('select').attr('name','component_id_line_'+i).attr('onchange','calculateLF('+i+');');

					//rename qty box for this row
					$(this).find('input.qty').attr('name','qty_line_'+i).attr('onchange','calculateLF();');

					//rename inches box for this row
					$(this).find('input.inches').attr('name','inches_line_'+i).attr('onchange','calculateLF();');

					//rename comment box for this row
					$(this).find('input.comment').attr('name','comment_line_'+i).attr('onchange','calculateLF();');

					//fix delete function number for this row
					$(this).find('button.delete').attr('onclick','deleteRow('+i+')');

					i++;
				});

				$('#numlines').val((i-1));
				calculateLF();
			}


			function calculateLF(selectbox){
				var total=0;

				if(selectbox > 0){
				
					$('input[name=\'inches_line_'+selectbox+'\']').val($('select[name=\'component_id_line_'+selectbox+'\'] option:selected').attr('data-inches-equivalent'));
					$('input[name=\'qty_line_'+selectbox+'\']').val(1);
				}
				

				$('#trackcomponentwrap table tbody tr').each(function(){
					if($(this).find('input.qty').val() > 0 && $(this).find('input.inches').val() > 0){
						total = (total + ( ($(this).find('input.qty').val()*$(this).find('input.inches').val()) / 12) );
					}
				});
				var roundedup=Math.ceil(total);
				$('#componentlftotal').val(roundedup);

				if(roundedup > ".$lineitemdata['qty']."){
					var lfdiff = (roundedup-".$lineitemdata['qty'].");
					var lfdiffperc = ((lfdiff / ".$lineitemdata['qty'].") * 100);
					var lfdiffplusminus='+';
				}

				if(roundedup < ".$lineitemdata['qty']."){
					var lfdiff = (".$lineitemdata['qty']."-roundedup);
					var lfdiffperc = ((lfdiff / ".$lineitemdata['qty'].") * 100);
					var lfdiffplusminus='-';
				}
				
				if(roundedup == ".$lineitemdata['qty']."){
					var lfdiff = 0;
					var lfdiffperc=0.0;
					var lfdiffplusminus='';
				}

				$('#differencevalue').html(lfdiffplusminus+lfdiffperc.toFixed(1)+'%');

				if(lfdiffperc > 3){
					$('#trackcomponentwrap table tfoot tr').addClass('badtotals');
				}else if(lfdiffperc <=3){
					$('#trackcomponentwrap table tfoot tr').removeClass('badtotals');
				}

				if(lfdiffperc > 12){
					$('#reallybad').show('fast');
				}else{
					$('#reallybad').hide('fast');
				}

			}

			$(function(){
			";

			if($isEdit){
				echo "calculateLF(-1)
				";
			}else{
				echo "addTrackComponent();
				";
			}
			
			echo "
			    /*$('div.submit input[type=submit]').click(function(){
			       $('div.submit input[type=submit]').prop('disabled',true).val('Processing...'); 
			    });*/
			    
			});
			</script>";

			

			echo "<div>";
			echo "<button type=\"button\" id=\"cancelbutton\" style=\"float:left;\">Cancel</button>";
			
			if($isEdit){
				echo $this->Form->submit('Save Changes');
			}else{
				echo $this->Form->submit('Add To Quote');
			}
			echo "<div style=\"clear:both;\"></div></div>";
			
			echo $this->Form->end();


			echo "<style>
			#reallybad{ display:none; padding:15px; border:3px solid red; background:#FFDDDD; }

			tr.badtotals{ background:red !important; }

			button.delete{ border:0; width:14px; height:14px; padding:0; text-indent:155%; overflow:hidden; background-image:url('/img/delete.png'); background-size:100% 100%; background-color:transparent; }

			#componentlftotal{ border:0; background:transparent !important; color:#FFF; width:95%; }
			#content .row{ width:600px; margin:0 auto; }
			#content .row h3{ text-align:center; }
			#trackcomponentwrap table thead tr{ background:#26337A; color:#FFF; }
			#trackcomponentwrap table thead tr th{ color:#FFF; }

			#trackcomponentwrap table tfoot tr{ background:#333; color:#FFF; }
			#trackcomponentwrap table tfoot tr td{ color:#FFF; }
			#trackcomponentwrap table tfoot tr td:nth-of-type(1){ text-align:right; }

			#trackcomponentwrap table tbody tr:nth-of-type(even){ background:#e8e8e8; }
			#trackcomponentwrap table tbody tr:nth-of-type(odd){ background:#f8f8f8; }

			#trackcomponentwrap table tbody td{ vertical-align:middle; }
			#trackcomponentwrap table tbody tr td select,
			#trackcomponentwrap table tbody tr td input,
			#trackcomponentwrap table tbody tr td button{ margin:0 0 0 0 !important; }

			form div.submit{ float:right; }
			form div.submit input[type=submit]{ background:#26337A; color:#FFF; border:1px solid #000; padding:10px 20px; font-size:15px; font-weight:bold; }
			form fieldset div.input{ width:46%; float:left; margin:0 3% 2% 0; }
			form fieldset{ background:#f8f8f8; border:1px solid #777; }
			form fieldset legend{ border-bottom:0 !important; display: inline-block !important; width: auto; background: none;}
			label[for=fabric-type-existing]{ display:inline-block; font-weight:normal !important; margin-right:20px; }
			label[for=fabric-type-typein]{ display:inline-block; font-weight:normal !important; }

			#imagelibrarycontents{ width:100%; height:400px; background:#FFF; padding:10px; overflow-y:scroll; overflow-x:none; }
			#imagelibrarycontents ul{ list-style:none; margin:0px; padding:0px; }
			#imagelibrarycontents ul li img{ width:auto; max-width:100%; height:85px; max-height:85px; border:2px solid white; cursor:pointer; }
			#imagelibrarycontents ul li{ width:48%; float:left; margin:15px 1%; text-align:center; }
			#imagelibrarycontents ul li:hover img{ border:2px solid green; }

			label[for=image-method-library]{ display:inline-block; font-weight:normal !important; margin-right:20px; }
			label[for=image-method-upload]{display:inline-block; font-weight:normal !important; }

			fieldset#fabricinfo div.input{ float:none !important; }
			</style>";

		}else{

			echo "<h3>Add Catch-All Line to Quote</h3><hr>";
			echo $this->Form->create(null,['type'=>'file']);
			echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);
			
			echo "<fieldset id=\"catchallcategory\"><legend>Type of Catch-All</legend>";
			echo $this->Form->radio('category',['Catch-All' => 'Catch-All', 'WT Hardware' => 'WT Hardware', 'Blinds & Shades' => 'Blinds & Shades', 'Finished Product' => 'Finished Product'],['required'=>true,'value'=>'Catch-All']);
			echo "</fieldset>";
			
			echo $this->Form->input('line_item_title',['label'=>'Line Item Title','required'=>true,'autocomplete'=>'off']);
			echo $this->Form->input('description',['required'=>false,'maxlength'=>300,'label'=>'Description (300 characters max)']);
			
			
			echo $this->Form->input('specs',['type'=>'textarea','placeholder'=>'Other details and specs for this custom line item','autocomplete'=>'off']);
			
			
			echo $this->Form->input('qty',['type'=>'number','label'=>'Qty','required'=>true,'value'=>1,'autocomplete'=>'off', 'min' => '1']);

			echo $this->Form->input('customer_id',['type'=>'hidden','value'=> $quoteData['customer_id']]);

			echo "<div class=\"input select\">";
			echo $this->Form->label('Units of Measure');
			echo $this->Form->select('unit_type',['each'=>'Each','pair'=>'Pair','set'=>'Set','yards'=>'Yards'],['required'=>true]);
			echo "</div>";


			echo $this->Form->input('price',['label'=>'Price Per Unit','required'=>true,'autocomplete'=>'off']);
			

			echo "<fieldset><legend>Line Item Image</legend>";

			echo $this->Form->radio('image_method',['none'=>'No Image','library'=>'From Image Library','upload'=>'Upload New Image'],['required'=>true]);
			echo $this->Form->input('libraryimageid',['type'=>'hidden','value'=>'0']);

			echo "<div id=\"imagelibrarycontents\" style=\"display:none;\"><ul>";
			foreach($libraryimages as $image){
				echo "<li id=\"image".$image['id']."\"><img src=\"/img/library/".$image['filename']."\" onclick=\"setselectedlibraryimage(".$image['id'].")\" /></li>";
			}
			echo "</ul><div style=\"clear:both;\"></div></div>";
			
			
			echo "<div id=\"imageuploadform\" style=\"display:none;\">";
			echo $this->Form->input('imagefileupload',['label'=>'Image File','type'=>'file']);
			echo $this->Form->input('save_to_library',['type'=>'checkbox','onchange'=>'changeUploadSaveSettings()','onclick'=>'changeUploadSaveSettings()']);
			echo "<div id=\"imageuploadsavetolibrarywrap\" style=\"display:none;\">";
			echo $this->Form->input('image_title',['label'=>'Title this Image','autocomplete'=>'off']);
			
			echo "<div class=\"input selectbox\"><label>Image Category</label>";
			echo $this->Form->select('image_category',$allLibraryCats,['empty'=>'--Select Category--']);
			echo "</div>";
			
			echo $this->Form->input('image_tags',['autocomplete'=>'off']);
			echo "</div>";
			
			echo "</div>";

			echo "</fieldset>";

			echo "<fieldset id=\"fabricinfo\"><legend>Fabric Information</legend>";
			echo $this->Form->radio('fabric_type',['none'=>'No Fabric','existing'=>'Existing Fabric','typein'=>'Type-In Fabric'],['required'=>true]);
			
			echo "<div id=\"fabric-selector-block\" style=\"display:none;\">";
			echo "<p><label>Select a Fabric</label><select id=\"fabricname\" name=\"fabricname\" onchange=\"getfabriccoloroptions(this.value)\"><option value=\"0\" selected disabled>--Select A Fabric--</option>";
			foreach($fabrics as $fabric){
				echo "<option value=\"".urlencode($fabric['fabric_name'])."\">".$fabric['fabric_name']."</option>";
			}
			echo "</select></p>";
			echo "<p id=\"colorselectionblock\"><label>Select a Fabric Color</label><select name=\"fabric_id_with_color\" id=\"fabricidwithcolor\"><option value selected disabled>--Select A Color--</option></select></p>";
			echo "</div>";

			echo "<div id=\"fabric-manual-entry-block\" style=\"display:none;\">";
			echo $this->Form->input('fabric_name',['label'=>'Fabric Name','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false]);
			echo $this->Form->input('fabric_color',['label'=>'Fabric Color','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false]);
			echo "</div>";

			echo $this->Form->input('com-fabric',['label'=>'COM Fabric','type'=>'checkbox','value'=>1]);

			echo "</fieldset>";


			echo "<fieldset><legend>Optional Fields</legend>";
			echo $this->Form->input('cut_width',['label'=>'Cut Width','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false]);
			echo $this->Form->input('finished_width',['label'=>'Finished Width','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false]);
			echo $this->Form->input('finished_length',['label'=>'Finished Length','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false]);
			echo $this->Form->input('fabric_ydsper',['label'=>'Yards Per Unit','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false]);
			echo $this->Form->input('fabric_total_yds',['label'=>false,'autocomplete'=>'off','type'=>'hidden','required'=>false]);
			
			//echo $this->Form->input('fabric_margin',['label'=>'Fabric Margin','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false]);
			//echo $this->Form->input('fabric_profit',['label'=>'Fabric Profit','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false]);
			
			echo $this->Form->input('room_number',['label'=>'Location / Room Number','autocomplete'=>'off','placeholder'=>'If Applicable','required'=>false]);

			echo "<div class=\"input select\">";
			echo $this->Form->label('Select Vendor');
			echo $this->Form->select('vendors_id',$vendorsList,['required'=>false,'empty'=>'--Select a Vendor--']);
			echo "</div>";

			echo "<div style=\"clear:both;\"></div></fieldset>";
			
			
			echo "<div>";
			echo "<button type=\"button\" id=\"cancelbutton\" style=\"float:left;\">Cancel</button>";
			echo $this->Form->submit('Add To Quote');
			echo "<div style=\"clear:both;\"></div></div>";
			
			echo $this->Form->end();
			
			echo "<style>
			fieldset#catchallcategory{ text-align:center; }
			fieldset#catchallcategory label{ display:inline-block; margin-right:22px; font-weight:normal; }
			label[for=category-finished-product]{ margin-right:0 !important; }
			
			#specsfield div.input{ width:100% !important; }
			#specs{ width:100%; }
			#content .row{ width:600px; margin:0 auto; }
			#content .row h3{ text-align:center; }
			form div.submit{ float:right; }
			form div.submit input[type=submit]{ background:#26337A; color:#FFF; border:1px solid #000; padding:10px 20px; font-size:15px; font-weight:bold; }
			form fieldset div.input{ width:46%; float:left; margin:0 3% 2% 0; }
			form fieldset{ background:#f8f8f8; border:1px solid #777; }
			form fieldset legend{ border-bottom:0 !important; display: inline-block !important; width: auto; background: none;}

			label[for=fabric-type-none]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
			label[for=fabric-type-existing]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
			label[for=fabric-type-typein]{ display:inline-block; font-weight:normal !important; }

			#imagelibrarycontents{ width:100%; height:400px; background:#FFF; padding:10px; overflow-y:scroll; overflow-x:none; }
			#imagelibrarycontents ul{ list-style:none; margin:0px; padding:0px; }
			#imagelibrarycontents ul li img{ width:auto; max-width:100%; height:85px; max-height:85px;  cursor:pointer; }
			#imagelibrarycontents ul li{ width:48%; float:left; margin:15px 1%; text-align:center; border:2px solid white; }
			#imagelibrarycontents ul li:hover{ border:2px solid green; }

			label[for=image-method-none]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
			label[for=image-method-library]{ display:inline-block; font-weight:normal !important; margin-right:10px; }
			label[for=image-method-upload]{display:inline-block; font-weight:normal !important; }

			fieldset#fabricinfo div.input{ float:none !important; }

			li.selectedimage{ position:relative; border:2px solid green !important; }
			li.selectedimage:after{ content:''; width:36px; height:36px; background-image:url('/img/Ok-icon.png'); background-size:100% auto; position:absolute; bottom:5px; right:5px; z-index:55; }
			
			#imageuploadform div.input{ width:98% !important; float:none !important; }
			</style>";

			echo "<script>
			function changeUploadSaveSettings(){
				if($('input#save-to-library').is(':checked')){
					$('#imageuploadsavetolibrarywrap').show('fast');
				}else{
					$('#imageuploadsavetolibrarywrap').hide('fast');
				}
			}
			
			
			$(function(){
			
			    $('form').submit(function(){
			    
			        if($('#fabric-type-existing').is(':checked')){
			            if(($('#fabricname').val() == '' || $('#fabricname').val() == '0' || $('#fabricname').val() == 'undefined' || $('#fabricname').val() == null) || ($('#fabricidwithcolor').val() == '' || $('#fabricidwithcolor').val() == '0' || $('#fabricidwithcolor').val() == 'undefined' || $('#fabricidwithcolor').val() == null )){
			            
			                alert(\"ERROR: Since you selected Existing Fabric, you must select a Fabric and Color from the fabric fieldset.\");
			                
			                return false;
			            }
			            
			        }
			        
			       $('div.submit input[type=submit]').prop('disabled',true).val('Processing...'); 
			    });
			    
			    
				$('#fabric-type-existing').click(function(){
					$('#fabric-selector-block').show('fast');
					$('#fabric-manual-entry-block').hide('fast');
				});
			
				$('#fabric-type-typein').click(function(){
					$('#fabric-manual-entry-block').show('fast');
					$('#fabric-selector-block').hide('fast');
				});
				
				$('#fabric-type-none').click(function(){
					$('#fabric-manual-entry-block').hide('fast');
					$('#fabric-selector-block').hide('fast');					
				});

				$('#image-method-none').click(function(){
					$('#imagelibrarycontents').hide('fast');
					$('#imageuploadform').hide('fast');
				});

				$('#image-method-library').click(function(){
					$('#imagelibrarycontents').show('fast');
					$('#imageuploadform').hide('fast');
				});

				$('#image-method-upload').click(function(){
					$('#imagelibrarycontents').hide('fast');
					$('#imageuploadform').show('fast');
				});

			});
			function getfabriccoloroptions(fabricname){
				$.get('/quotes/getfabriccolors/'+fabricname+'/custom/".$quoteData['customer_id']."',function(data){
					$('#colorselectionblock').html(data);
				});
			}

			function setselectedlibraryimage(imageid){
				$('#libraryimageid').val(imageid);
				$('#imagelibrarycontents li').removeClass('selectedimage');
				$('li#image'+imageid).addClass('selectedimage');
			}
			</script>";
		}
		
	break;
}
?>

<script>
$(function(){
	
	$('#cancelbutton').click(function(){
		location.replace('/quotes/add/<?php echo $quoteID; ?>/'); //parent.$.fancybox.close();
	});
});
</script>
<style>
body{ font-family:'Helvetica',Arial,sans-serif; }
#lineitemtitle{ width:85%; padding:5px; }
#lineitemdescription{ display:block; width:85%; height:95px; }
fieldset{ margin-bottom:20px; }
#calculatorfabric,#calculatorfabric p{ margin-bottom:0 !important; }
fieldset legend{ font-weight:bold; color:#26337A; }
#calculatorlistbuttons button{ background:#26337A; color:#FFF; margin:5px; border:1px solid #000; font-size:14px; font-weight:bold; padding:10px 15px; cursor:pointer; }

#priceeachline{ margin:25px 0; }

label{ display:block; font-size:14px; font-weight:bold; color:#26337A; }
select{ width:100% !important; padding:8px !important; background:#FFF; font-size:14px; }
.ui-selectmenu-button.ui-button{ width:92% !important; padding:8px !important; background:#FFF; font-size:14px; border-radius:0 !important; }
#addsimpleproducttoquote{ background:#26337A; color:#FFF; padding:10px 15px; font-weight:bold; border:1px solid #000; font-size:14px; cursor:pointer; }
#cancelbutton{ background:#CCC; border:1px solid #333; color:#333; padding:10px 15px; cursor:pointer; }

#trackrows input[type=number]::-webkit-input-placeholder,
#trackrows input[type=text]::-webkit-input-placeholder { 
  color: #444;
}
#trackrows input[type=number]::-moz-placeholder,
#trackrows input[type=text]::-moz-placeholder{
  color: #444;
}
#trackrows input[type=number]:-ms-input-placeholder,
#trackrows input[type=text]:-ms-input-placeholder{
  color: #444;
}
#trackrows input[type=number]:-moz-placeholder,
#trackrows input[type=text]:-moz-placeholder{
  color:#444;
}

#finishwidthselection label{ display:inline-block; margin-right:20px; }
</style>