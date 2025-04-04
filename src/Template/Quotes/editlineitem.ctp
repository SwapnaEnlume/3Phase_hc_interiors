<!-- src/Template/Quotes/editlineitem.ctp -->
<?php
switch($thisLineItem['lineitemtype']){
   
	case "simple":
		echo "<input type=\"hidden\" name=\"quoteID\" id=\"quoteID\" value=\"".$quoteID."\" />";
		if(!empty($ordermode)) $modevalue = $ordermode;  else $modevalue=" "; 
		echo "<input type=\"hidden\" name=\"ordermode\" id=\"ordermode\" value=\"".$modevalue."\" />";

		echo "<input type=\"hidden\" name=\"customer_id\" id=\"customerID\" value=\"".$quoteData['customer_id']."\" />";
		$typeTitle = ($ordermode == 'workorder') ? "Work Order": ($ordermode == 'order')? "Sales Order" : " ";
		echo "<h3>Edit ".$typeTitle." Price List Line Item</h3><hr>";
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
		
		if($thisLineItem['product_type']=='cubicle_curtains'){
			echo "<script>$(function(){ $('#product').val('cubicle-curtain'); setproducttype(); });</script>";
		}
		if($thisLineItem['product_type'] == 'bedspreads'){
			echo "<script>$(function(){ $('#product').val('bedspread'); setproducttype(); });</script>";
		}
		if($thisLineItem['product_type'] == 'window_treatments'){
			echo "<script>
			$(function(){
				$('#product').val('window-treatment');
				setproducttype();
				$('#wttype').val('".$lineitemdata['wttype']."');
			});
			</script>";
		}
		if($thisLineItem['product_type'] == 'services'){
			echo "<script>$(function(){ $('#product').val('service'); setproducttype(); });</script>";
		}
		if($thisLineItem['product_type'] == 'track_systems'){
			echo "<script>$(function(){ $('#product').val('track-system'); setproducttype(); });</script>";
		}
		if(!empty($thisLineItem['room_number'])) $location =$thisLineItem['room_number']; else $location = " ";
			/**PPSASCRUM-201 start **/
echo $this->Form->input('quoteType',['type'=>'hidden','value'=>$quoteData['type_id']]); //PPSASCRUM-201
echo $this->Form->input('itemId',['type'=>'hidden','value'=>$thisLineItem['id']]); //PPSASCRUM-196


 $typeID=false;
if($quoteData['type_id'] == 1)    	    
echo $this->Form->input('location',['label'=>'Location','required'=>true,'value'=>$location]);
else
	echo "<p><label for=\"location\">Location</label> <input type=\"text\" name=\"location\" id=\"location\" value=\"".$location."\" /></p>";
	

		echo "<div id=\"databox\"></div>";
		
		
		if($thisLineItem['product_type'] == 'track_systems'){
			if($ordermode == 'workorder')
			echo "<p id=\"qtyline\"><b></b> <p><label for=\"qtyvalue\">LINEAR FEET</label> <input type=\"number\" id=\"qtyvalue\" style=\"width:160px;\" value=\"".$thisLineItem['qty']."\" min=\"1\" readonly =\"true\"/> <button id=\"addsimpleproducttoquote\">Save Changes</button></p>";
			else 
			echo "<p id=\"qtyline\"><p><label for=\"qtyvalue\">LINEAR FEET</label> <b></b> <input type=\"number\" id=\"qtyvalue\" style=\"width:160px;\"value=\"".$thisLineItem['qty']."\" min=\"1\" /> <button id=\"addsimpleproducttoquote\">Save Changes</button></p>";

			echo "<script>
			function changetrackitem(){
					$('#qtyline b').html($('select[name=system_id] option:selected').attr('data-unitlabel')+':');
					$('#qtyline').show();
			}
			</script>";
		}else{
			if($ordermode == "workorder") {
				echo "<p id=\"qtyline\"><b>";
				echo "QTY";
				echo ":</b> <input type=\"number\" id=\"qtyvalue\" readonly=\"true\" value=\"";
				echo $thisLineItem['qty'];
				echo "\" min=\"1\" /> <button id=\"addsimpleproducttoquote\">Save Changes</button></p>";
			
			}else {
				echo "<p id=\"qtyline\"><b>";
				echo "QTY";
				echo ":</b> <input type=\"number\" id=\"qtyvalue\" value=\"";
				echo $thisLineItem['qty'];
				echo "\" min=\"1\" /> <button id=\"addsimpleproducttoquote\">Save Changes</button></p>";
			}
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
					$.get('/quotes/getproductselectlist/'+$('#product').val()+'/fromedit/".$thisLineItem['id']."/$ordermode/',function(data){
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

	if($thisLineItem['product_type']=='bedspreads'){
		echo "
			if($('#bssize').val() == '' || $('#bssize').val() == '0' || $('#bssize').val() == null || $('#bssize').val() === 'undefined'){
					alert('You must select a Bedspread Size before continuing.');
					return false;
			}else{
				parent.$.fancybox.showLoading();
			";
	}
	//$('#usealias').is(':visible') &&
	echo "
	if($('#usealias').is(':checked')){
		var usealiasyesno='yes';
	}else{
		var usealiasyesno='no';
	}
	
	if($('#fl-short').is(':visible') && $('#fl-short').val() != ''){
        var shortpointval=$('#fl-short').val();
    }else{
        var shortpointval='no';
    }
    
	";

	if($thisLineItem['product_type'] == 'bedspreads'){
		echo "
			if($('select#bssize option:selected').attr('data-specialpricing') == '1'){
				var specialpricingyesno='1';
			}else{
				var specialpricingyesno='0';
			}
		";
	
	}elseif($thisLineItem['product_type'] == 'cubicle_curtains'){
		echo "
			if($('input[name=finished_width]').val() == ''){
				var finishedwidthval='0';
			}else{
				var finishedwidthval=$('input[name=finished_width]').val();
			}
			";
	}
	
	echo " if(($('#quotetype').val() == 1)  && $('#location').val().length <= 1){
	 $('#location').addClass('stillneeded');
	 $('#fancybox-loading').css({'display': 'none'});
	 return false;
	 }";
	echo "
				$.get('/quotes/";
		
		if($thisLineItem['product_type'] == 'cubicle_curtains'){
		    if(!empty($ordermode))
		    	echo "editquotecc/".$quoteID."/".$thisLineItem['id']."/'+$('#fabricid').val()+'/'+$('#fabricidwithcolor').val()+'/'+$('#ccid').val()+'/'+$('#sizeid').val()+'/'+$('#qtyvalue').val()+'/'+parseFloat($('#priceeachvalue').html().replace('$',''))+'/'+$('#length').val()+'/'+$('#meshoption').val()+'/'+$('#weight').val()+'/'+$('#yards').val()+'/'+$('#labor-lf').val()+'/'+$('#difficulty').val()+'/'+$('#specialpricing').val()+'/'+finishedwidthval+'/'+usealiasyesno+'/'+$('#location').val()+'/'+$('#ordermode').val() ";
            else
              	echo "editquotecc/".$quoteID."/".$thisLineItem['id']."/'+$('#fabricid').val()+'/'+$('#fabricidwithcolor').val()+'/'+$('#ccid').val()+'/'+$('#sizeid').val()+'/'+$('#qtyvalue').val()+'/'+parseFloat($('#priceeachvalue').html().replace('$',''))+'/'+$('#length').val()+'/'+$('#meshoption').val()+'/'+$('#weight').val()+'/'+$('#yards').val()+'/'+$('#labor-lf').val()+'/'+$('#difficulty').val()+'/'+$('#specialpricing').val()+'/'+finishedwidthval+'/'+usealiasyesno+'/'+$('#location').val() ";

			//$quoteID,$lineID,$fabricname,$fabricid,$ccid,$sizeID,$qty,$priceeach,$length,$mesh,            $weight,$yards,$lf,$difficulty,$specialpricing,$finishedwidth,$usealias,$location


		}elseif($thisLineItem['product_type'] == 'bedspreads'){
		   
 if(!empty($ordermode))
			echo "editquotebs/".$quoteID."/".$thisLineItem['id']."/'+$('#fabricid').val()+'/'+$('#fabricidwithcolor').val()+'/'+$('select#bssize option:selected').attr('data-bsid')+'/'+$('#sizeid').val()+'/'+$('#qtyvalue').val()+'/'+$('#price').val()+'/'+$('#weight').val()+'/'+$('#difficulty').val()+'/'+$('#yards').val()+'/'+isquilted+'/'+$('#mattresssize').val()+'/'+$('#topwidthsl').val()+'/'+$('#dropwidthsl').val()+'/'+$('#quiltpattern').val()+'/'+$('#topcutsw').val()+'/'+$('#dropcutsw').val()+'/'+$('#repeat').val()+'/'+usealiasyesno+'/'+specialpricingyesno+'/1.33/'+$('#location').val()+'/'+$('#ordermode').val() ";
			else
		echo "editquotebs/".$quoteID."/".$thisLineItem['id']."/'+$('#fabricid').val()+'/'+$('#fabricidwithcolor').val()+'/'+$('select#bssize option:selected').attr('data-bsid')+'/'+$('#sizeid').val()+'/'+$('#qtyvalue').val()+'/'+$('#price').val()+'/'+$('#weight').val()+'/'+$('#difficulty').val()+'/'+$('#yards').val()+'/'+isquilted+'/'+$('#mattresssize').val()+'/'+$('#topwidthsl').val()+'/'+$('#dropwidthsl').val()+'/'+$('#quiltpattern').val()+'/'+$('#topcutsw').val()+'/'+$('#dropcutsw').val()+'/'+$('#repeat').val()+'/'+usealiasyesno+'/'+specialpricingyesno+'/1.33/'+$('#location').val()";
		}elseif($thisLineItem['product_type'] == 'services'){
		    

		    if(!empty($ordermode))
		    	echo "editquoteservice/".$quoteID."/'+$('#itemid').val()+'/'+$('#serviceid').val()+'/'+$('#priceinput').val()+'/'+$('#qtyvalue').val()+'/'+$('#location').val()+'/'+$('#ordermode').val()";

			else 
			   echo "editquoteservice/".$quoteID."/'+$('#itemid').val()+'/'+$('#serviceid').val()+'/'+$('#priceinput').val()+'/'+$('#qtyvalue').val()+'/'+$('#location').val()" ;

		    
		}elseif($thisLineItem['product_type'] == 'window_treatments'){
			
			echo "editquotewt/".$quoteID."/".$thisLineItem['id']."/'+$('#wttype').val()+'/'+$('#fabricid').val()+'/'+$('#fabricidwithcolor').val()+'/'+$('#wtid').val()+'/'+$('#sizeid').val()+'/'+$('#qtyvalue').val()+'/'+$('#price').val()+'/'+$('#weight').val()+'/'+$('#difficulty').val()+'/'+$('#yards').val()+'/'+$('#haswelts').val()+'/'+$('#liningoption').val()+'/'+$('#pairorpanel').val()+'/'+$('#labor-lf').val()+'/'+$('#specialpricing').val()+'/no/'+usealiasyesno+'/'+shortpointval+'/'+$('#location').val()+'/'+$('#ordermode').val()";
			//$quoteID,$wttype,$fabricid,$fabricwithcolorid,$wtID,$wtSizeID,$qty,$priceEach,$weight,$diff,$yards,$haswelts

		}elseif($thisLineItem['product_type'] == 'track_systems'){
			//echo "addtracktoquote/".$quoteID."/'";
			//echo "editquotetrack/".$thisLineItem['id']."/".$quoteID."/'+$('select[name=system_id]').val()+'/'+$('#qtyvalue').val()+'/'+$('select[name=system_id] option:selected').attr('data-price')+'/'+encodeURIComponent($('#description').val())+'/'+$('#location').val()";
			//addtracktoquote($quoteID,$trackItemID,$qty,$price)
				/**PPSASCRUM-27 start **/
			echo "editquotetrack/".$thisLineItem['id']."/".$quoteID."/'+$('select[name=system_id]').val()+'/'+$('#qtyvalue').val()+'/'+$('select[name=system_id] option:selected').attr('data-price')+'/'+escapeTextFieldforURL($('#description').val())+'/'+escapeTextFieldforURL($('#location').val())+'/'+$('#ordermode').val()";
			/**PPSASCRUM-27 end **/
		}
		

		/* PPSASCRUM-139: start */
		echo ",function(response){
			if(response=='OK'){";
				// location.replace('/quotes/add/".$quoteID."/".$ordermode."');
				if (isset($this->request->query['li_no'])) {
					echo "location.replace('/quotes/add/".$quoteID."/".$ordermode."?price_list=edit_price-lst_success&li_no=".$this->request->query['li_no']."');";
				} else {
					echo "location.replace('/quotes/add/".$quoteID."/".$ordermode."');";
				}

		echo "}
			});
			";
		/* PPSASCRUM-139: end */

			if($thisLineItem['product_type'] == 'bedspreads'){
				echo "}
				";
			}
		
		echo "
			});
			
		});
		</script>";
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
			
			echo "});
			</script>";

			

			echo "<div>";
			echo "<button type=\"button\" id=\"cancelbutton\" style=\"float:left;\">Cancel</button>";
			
			echo $this->Form->submit('Save Changes');
			
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

			echo "<h3>Edit Catch-All Line Item</h3><hr>";
			
			//echo "<div class=\"message error\">This edit form is still being tested for accuracy.</div>";
			
			echo $this->Form->create(null);
			echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);
			
			echo "<fieldset id=\"catchallcategory\"><legend>Type of Catch-All</legend>";
			echo $this->Form->radio('category',['Catch-All' => 'Catch-All', 'WT Hardware' => 'WT Hardware', 'Blinds & Shades' => 'Blinds & Shades', 'Finished Product' => 'Finished Product'],['required'=>true,'value'=>$lineitemmeta['catchallcategory']]);
			echo "</fieldset>";
			
			
			echo $this->Form->input('line_item_title',['label'=>'Line Item Title','required'=>true,'value'=>$thisLineItem['title']]);
			echo $this->Form->input('description',['required'=>false,'value'=>$thisLineItem['description']]);
			
			
			echo $this->Form->input('specs',['type'=>'textarea','placeholder'=>'Other details and specs for this custom line item','value'=>$lineitemmeta['specs']]);
			if($ordermode == "workorder") 
			echo $this->Form->input('qty',['type'=>'number','label'=>'Qty','readonly'=>true,'value'=>1, 'min' => '1','value'=>$thisLineItem['qty']]);
			else
			echo $this->Form->input('qty',['type'=>'number','label'=>'Qty','required'=>true,'value'=>1, 'min' => '1','value'=>$thisLineItem['qty']]);


			echo $this->Form->input('customer_id',['type'=>'hidden','value'=> $quoteData['customer_id']]);

			echo "<div class=\"input select\">";
			echo $this->Form->label('Units of Measure');
			if($ordermode == "workorder") 
				echo $this->Form->select('unit_type',['each'=>'Each','pair'=>'Pair','set'=>'Set','yards'=>'Yards'],['disabled'=>true,'value'=>$lineitemmeta['unit_type']]);
			else
			echo $this->Form->select('unit_type',['each'=>'Each','pair'=>'Pair','set'=>'Set','yards'=>'Yards'],['required'=>true,'value'=>$lineitemmeta['unit_type']]);
			echo "</div>";
			if($ordermode != "workorder") 
				echo $this->Form->input('price',['label'=>'Price Per','required'=>true,'value'=>$lineitemmeta['price']]);
			else 
				echo $this->Form->input('price',['label'=>'Price Per','readonly'=>true,'value'=>$lineitemmeta['price']]);

			/*
			echo "<fieldset><legend>Line Item Image</legend>";

			echo $this->Form->radio('image_method',['none'=>'No Image','library'=>'From Image Library','upload'=>'Upload New Image'],['required'=>true]);
			echo $this->Form->input('libraryimageid',['type'=>'hidden','value'=>'0']);

			echo "<div id=\"imagelibrarycontents\" style=\"display:none;\"><ul>";
			foreach($libraryimages as $image){
				echo "<li id=\"image".$image['id']."\"><img src=\"/img/library/".$image['filename']."\" onclick=\"setselectedlibraryimage(".$image['id'].")\" /></li>";
			}
			echo "</ul><div style=\"clear:both;\"></div></div>";

			echo "</fieldset>";
			*/
$disabledPro ='';
if( $ordermode =='workorder' && isset($thisLineItem['best_price'])){
$disabledPro = "disabled";
}
			echo "<fieldset id=\"fabricinfo\" $disabledPro><legend>Fabric Information</legend>";
			if($ordermode == "workorder"){
				echo $this->Form->radio('fabric_type',['none'=>'No Fabric','existing'=>'Existing Fabric','typein'=>'Type-In Fabric'],['disabled'=>true,'value'=>$lineitemmeta['fabrictype']]);
			}else
			echo $this->Form->radio('fabric_type',['none'=>'No Fabric','existing'=>'Existing Fabric','typein'=>'Type-In Fabric'],['required'=>true,'value'=>$lineitemmeta['fabrictype']]);
			
			echo "<div id=\"fabric-selector-block\"";
			
			if($lineitemmeta['fabrictype'] != "existing"){
				echo " style=\"display:none;\"";
			}
			echo ">";
			
			/*
			echo "<pre>";
			//print_r($fabrics);
			print_r($lineitemmeta);
			echo "</pre>";
			*/
			if($ordermode == "workorder") {
			echo "<p><label>Select a Fabric</label><select id=\"fabricname\" name=\"fabricname\" onchange=\"getfabriccoloroptions(this.value)\" disabled=\"true\">";
			foreach($fabrics as $fabric){
				echo "<option value=\"".urlencode($fabric['fabric_name'])."\"";
				if($lineitemmeta['fabrictype'] == 'existing'){
					if(strtolower(trim($theFabric['fabric_name'])) == strtolower(trim($fabric['fabric_name']))){
						echo " selected=\"selected\"";
					}
					//echo " data-thefabricname=\"".$theFabric['fabric_name']."\" data-fabricname=\"".$fabric['fabric_name']."\"";
				}
				echo ">".$fabric['fabric_name']."</option>";
			}
			echo "</select></p>";
		}else {
			echo "<p><label>Select a Fabric</label><select id=\"fabricname\" name=\"fabricname\" onchange=\"getfabriccoloroptions(this.value)\" >";
			foreach($fabrics as $fabric){
				echo "<option value=\"".urlencode($fabric['fabric_name'])."\"";
				if($lineitemmeta['fabrictype'] == 'existing'){
					if(strtolower(trim($theFabric['fabric_name'])) == strtolower(trim($fabric['fabric_name']))){
						echo " selected=\"selected\"";
					}
					//echo " data-thefabricname=\"".$theFabric['fabric_name']."\" data-fabricname=\"".$fabric['fabric_name']."\"";
				}
				echo ">".$fabric['fabric_name']."</option>";
			}
			echo "</select></p>";
		}
		if($ordermode == "workorder") {
			echo "<p id=\"colorselectionblock\"><label>Select a Fabric Color</label><select name=\"fabric_id_with_color\" id=\"fabricidwithcolor\" disabled=\"true\">";
			
			if($lineitemmeta['fabrictype'] == 'existing'){
				foreach($thisFabricColors as $color){
					echo "<option value=\"".$color['id']."\"";
					if($color['id'] == $lineitemmeta['fabricid']){
						echo " selected=\"selected\"";
					}
					echo ">".$color['color']."</option>";
				}
			}
			
			echo "</select></p>";
		}else {
			echo "<p id=\"colorselectionblock\"><label>Select a Fabric Color</label><select name=\"fabric_id_with_color\" id=\"fabricidwithcolor\" >";
			
			if($lineitemmeta['fabrictype'] == 'existing'){
				foreach($thisFabricColors as $color){
					echo "<option value=\"".$color['id']."\"";
					if($color['id'] == $lineitemmeta['fabricid']){
						echo " selected=\"selected\"";
					}
					echo ">".$color['color']."</option>";
				}
			}
			
			echo "</select></p>";
		}
			echo "</div>";

			echo "<div id=\"fabric-manual-entry-block\"";
			if($lineitemmeta['fabrictype'] != 'typein'){
				echo " style=\"display:none;\"";
			}
			echo ">";
			echo $this->Form->input('fabric_name',['label'=>'Fabric Name','placeholder'=>'If Applicable','required'=>false,'value'=>$lineitemmeta['fabric_name']]);
			echo $this->Form->input('fabric_color',['label'=>'Fabric Color','placeholder'=>'If Applicable','required'=>false,'value'=>$lineitemmeta['fabric_color']]);
			echo "</div>";

			if($lineitemmeta['com-fabric'] == '1'){
				$ifcheckedCOMFabric=true;
			}else{
				$ifcheckedCOMFabric=false;
			}
			if($ordermode = 'workorder')
				echo $this->Form->input('com-fabric',['disabled'=>'true','label'=>'COM Fabric','type'=>'checkbox','value'=>1,'checked'=>$ifcheckedCOMFabric]);
			else
				echo $this->Form->input('com-fabric',['label'=>'COM Fabric','type'=>'checkbox','value'=>1,'checked'=>$ifcheckedCOMFabric]);


			echo "</fieldset>";


			echo "<fieldset><legend>Optional Fields</legend>";
			echo $this->Form->input('cut_width',['label'=>'Cut Width','placeholder'=>'If Applicable','required'=>false,'value'=>$lineitemmeta['width']]);
			echo $this->Form->input('finished_width',['label'=>'Finished Width','placeholder'=>'If Applicable','required'=>false,'value'=>$lineitemmeta['fw']]);
			echo $this->Form->input('finished_length',['label'=>'Finished Length','placeholder'=>'If Applicable','required'=>false,'value'=>$lineitemmeta['length']]);
			echo $this->Form->input('fabric_ydsper',['label'=>'Yards Per Unit','placeholder'=>'If Applicable','required'=>false,'value'=>$lineitemmeta['yds-per-unit']]);
			echo $this->Form->input('fabric_total_yds',['label'=>false,'type'=>'hidden','required'=>false,'value'=>$lineitemmeta['total-yds']]);
			echo $this->Form->input('fabric_margin',['label'=>'Fabric Margin','placeholder'=>'If Applicable','required'=>false,'value'=>$lineitemmeta['fabric-margin']]);
			echo $this->Form->input('fabric_profit',['label'=>'Fabric Profit','placeholder'=>'If Applicable','required'=>false,'value'=>$lineitemmeta['fabric-profit']]);
			echo $this->Form->input('quoteType',['type'=>'hidden','value'=>$quoteData['type_id']]); //PPSASCRUM-201

			if($quoteData['type_id'] ==1){
			   echo $this->Form->input('room_number',['label'=>'Location / Room Number','placeholder'=>'If Applicable','required'=>true,'value'=>$thisLineItem['room_number']]);
 
			}else
			echo $this->Form->input('room_number',['label'=>'Location / Room Number','placeholder'=>'If Applicable','required'=>false,'value'=>$thisLineItem['room_number']]);

			echo "<div class=\"input select\">";
			echo $this->Form->label('Select Vendor');
			echo $this->Form->select('vendors_id',$vendorsList,['required'=>false,'empty'=>'--Select a Vendor--','value'=>$lineitemmeta['vendors_id']]);
			echo "</div>";

			echo "<div style=\"clear:both;\"></div></fieldset>";
			
			
			
			echo "<div>";
			//echo "<button type=\"button\" id=\"cancelbutton\" style=\"float:left;\">Cancel</button>";
			echo $this->Form->submit('Save Changes');
			echo "<div style=\"clear:both;\"></div><br><Br><br></div>";
			
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
			</style>";

			echo "<script>
			$(function(){
			
			    $('#fabric-ydsper,#qty').keyup(function(){
			        var newTotalYards=(parseFloat($('#fabric-ydsper').val()) * parseInt($('#qty').val()));
			        $('#fabric-total-yds').val(newTotalYards);
			    });
			    
			    $('#fabric-ydsper,#qty').change(function(){
			        var newTotalYards=(parseFloat($('#fabric-ydsper').val()) * parseInt($('#qty').val()));
			        $('#fabric-total-yds').val(newTotalYards);
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
			console.log(fabricname);
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
	 $('#location').keyup(function () {  $('#location').removeClass('stillneeded')}); //PPSASCRUM-201
});
// PPSASCRUM-27 start
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
    var output =thetext.replace(/\\/g, ":__bbbbslash__:");
	 output = output.replace(/\//g, ":__aaabslash__:");
	output = output.replace('?',':__question__:',output);
	output = output.replace(' ',':__space__:',output);
	output = output.replace('#',':__pound__:',output);
	output = output.replace('%',':__percentage__:',output);
		return output;
}
// PPSASCRUM-27 end
</script>

<style>
.stillneeded{ border:2px solid red !important; }  <!--//PPSASCRUM-201-->

body{ font-family:'Helvetica',Arial,sans-serif; }
#lineitemtitle{ width:85%; padding:5px; }
#lineitemdescription{ display:block; width:85%; height:95px; }
fieldset{ margin-bottom:20px; }
#calculatorfabric,#calculatorfabric p{ margin-bottom:0 !important; }
fieldset legend{ font-weight:bold; color:#26337A; }
#calculatorlistbuttons button{ background:#26337A; color:#FFF; margin:5px; border:1px solid #000; font-size:14px; font-weight:bold; padding:10px 15px; cursor:pointer; }

#priceeachline{ margin:25px 0; }

label{ display:block; font-size:14px; font-weight:bold; color:#26337A; }
select{ width:96% !important; padding:8px !important; background:#FFF; font-size:14px; }
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
