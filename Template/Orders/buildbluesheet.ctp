<?php
echo $this->Form->create(null);
?>
<div id="overlayraw">
<?php echo $this->Form->input('rawjson',['type'=>'textarea']); ?>
</div>

<?php
echo $this->Form->input('pngdata',['type'=>'hidden']);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jcanvas/20.1.4/min/jcanvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jcanvas/20.1.4/min/jcanvas-handles.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.7/js/jquery.fancybox.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.7/css/jquery.fancybox.min.css" type="text/css" rel="stylesheet" />


<script>
$(function(){
	//preload the white canvas base layer
	$('canvas').addLayer({
	  type: 'image',
	  draggable: false,
	  source: '/img/whitebase.jpg',
	  x:0, y:0,
	  height:1540, width:2000
	});
	
	
	
	
	
	
	$('#addimage').click(function(){
		$.fancybox({
			type:'iframe',
			width:500,
			height:600,
			href:'/admin/addimagetotemplate/',
			autoSize:false,
			modal:true
		});
	});
	
	
	$('#addtext').click(function(){
		$.fancybox({
			type:'iframe',
			width:700,
			height:670,
			href:'/admin/addtexttotemplate/',
			autoSize:false,
			modal:true
		});
	});
	
	
	$('#addline').click(function(){
		$('canvas').addLayer({
			type: 'line',
			strokeStyle:'#000',
			strokeWidth:2,
			x1:200,
			y1:200,
			x2:400,
			y2:200,
			fromCenter:false,
			draggable:true,
			handle: {
				type: 'rectangle',
				fillStyle: '#fff',
				strokeStyle: '#f00',
				strokeWidth: 1,
				width:7,height:7,
				radius:0
			  },
		mousemove: function(layer){
			updatelayerdata();
		},
		mouseup: function(layer){
			updatelayerdata();
		},
		drag: function(layer){
			updatelayerdata();
		},
		dragstop: function(layer){
			updatelayerdata();
		},
		touchend: function(layer){
			updatelayerdata();
		},
		touchmove: function(layer){
			updatelayerdata();
		},
		click: function(layer){
			updatelayerdata();
		},
		dblclick: function(layer){
			$('canvas').removeLayer(layer).drawLayers();
			updatelayerdata();
		}
		}).drawLayers();
		updatelayerdata();
	});
	
	
	
	
	$('#addrectangle').click(function(){
		$('canvas').addLayer({
			type: 'rectangle',
			strokeStyle:'#000',
			strokeWidth:2,
			x:200,
			y:200,
			width:300,
			height:200,
			draggable:true,
			fromCenter:false,
			handle: {
				type: 'rectangle',
				fillStyle: '#fff',
				strokeStyle: '#f00',
				strokeWidth: 1,
				width:7,height:7,
				radius:0
			  },
		mousemove: function(layer){
			updatelayerdata();
		},
		mouseup: function(layer){
			updatelayerdata();
		},
		drag: function(layer){
			updatelayerdata();
		},
		dragstop: function(layer){
			updatelayerdata();
		},
		touchend: function(layer){
			updatelayerdata();
		},
		touchmove: function(layer){
			updatelayerdata();
		},
		click: function(layer){
			updatelayerdata();
		},
		dblclick: function(layer){
			$('canvas').removeLayer(layer).drawLayers();
			updatelayerdata();
		}
		}).drawLayers();
		updatelayerdata();
	});
	
	
	
	$('#addcircle').click(function(){
		$('canvas').addLayer({
			type: 'ellipse',
			strokeStyle:'#000',
			strokeWidth:2,
			x:200,
			y:200,
			width:200,
			radius:50,
			height:200,
			draggable:true,
			fromCenter:false,
			handle: {
				type: 'rectangle',
				fillStyle: '#fff',
				strokeStyle: '#f00',
				strokeWidth: 1,
				width:7,height:7,
				radius:0
			  },
		mousemove: function(layer){
			updatelayerdata();
		},
		mouseup: function(layer){
			updatelayerdata();
		},
		drag: function(layer){
			updatelayerdata();
		},
		dragstop: function(layer){
			updatelayerdata();
		},
		touchend: function(layer){
			updatelayerdata();
		},
		touchmove: function(layer){
			updatelayerdata();
		},
		click: function(layer){
			updatelayerdata();
		},
		dblclick: function(layer){
			$('canvas').removeLayer(layer).drawLayers();
			updatelayerdata();
		}
		}).drawLayers();
		updatelayerdata();
	});
	
	
	
	
	<?php
	$yesnocheckboxfields=array(
		'hinged',
		'brackets',
		'individual-nailheads',
		'nailhead-trim',
		'covered-buttons',
		'trim-sewn-on',
		'tassels',
		'railroaded',
		'com-fabric',
		'welt-top',
		'welt-bottom'
	);
	
	$layerCoordHiddenFields=array();
	
	
			$layers=json_decode($template['json_preload_layers'],true);
			
			
			foreach($layers as $layername => $layerData){
				switch($layerData['layerType']){
					case "text":
						
						$parsedTextValue=str_replace("#ORDERNUMBER#",$thisOrder['order_number'],$layerData['textValue']);
						
						$parsedTextValue=str_replace("#THISUSER#",$thisUser['first_name']." ".$thisUser['last_name'],$parsedTextValue);

						if($thisOrder['due'] < 1000){
							$duedate="N/A";
						}else{
							$duedate=date('n/j/Y',$thisOrder['due']);
						}
						
						$parsedTextValue=str_replace("#DUEDATE#",$duedate,$parsedTextValue);

						$parsedTextValue=str_replace("#ROOMNUMBER#",$lineItemData['room_number'],$parsedTextValue);

						$parsedTextValue=str_replace("#CUSTOMER#",$thisCustomer['company_name'],$parsedTextValue);

						$parsedTextValue=str_replace("#FACILITY#",$thisOrder['facility'],$parsedTextValue);
						
						
						foreach($lineItemMetas as $itemMeta){
						    if($itemMeta['meta_key'] == 'fabricid'){
						        foreach($allFabrics as $fabricRow){
						            if($fabricRow['id'] == intval($itemMeta['meta_value'])){
						                $parsedTextValue=str_replace("#FABRICNAME#",$fabricRow['fabric_name'],$parsedTextValue);
						                $parsedTextValue=str_replace("#FABRICCOLOR#",$fabricRow['color'],$parsedTextValue);
						            }
						        }
						    }
						    
						    if($itemMeta['meta_key'] == 'linings_id'){
						        foreach($allLinings as $liningRow){
						            if($liningRow['id'] == $itemMeta['meta_value']){
						                $parsedTextValue=str_replace("#LININGNAME#",$liningRow['title'],$parsedTextValue);
						            }
						        }
						    }
							
							if(in_array($itemMeta['meta_key'],$yesnocheckboxfields)){
								
								if($itemMeta['meta_value'] == '0'){
									$thisvalue='NO';
								}elseif($itemMeta['meta_value'] == '1'){
									$thisvalue='YES';
								}
								
								$parsedTextValue=str_replace('#META='.$itemMeta['meta_key'].'#',$thisvalue,$parsedTextValue);
								
							}else{
						    	$parsedTextValue=str_replace('#META='.$itemMeta['meta_key'].'#',$itemMeta['meta_value'],$parsedTextValue);
							}
						}
						
						
						echo "$('canvas').drawText({
							layer: true,
							name: '".$layername."Layer',
							draggable:".$layerData['draggable'].",
							fillStyle: '".$layerData['fillStyle']."',
							x:".$layerData['posX'].",
							y:".$layerData['posY'].",
							fromCenter:".$layerData['fromCenter'].",
							width:'".$layerData['width']."',
							fontSize: '".$layerData['fontSize']."',
							fontFamily: '".$layerData['font']."',
							fontStyle:'".$layerData['fontStyle']."',
							text: '".$parsedTextValue."',
							align:'".$layerData['textAlign']."',
							dblclick: function(layer){
								$('canvas').removeLayer(layer).drawLayers();
								updatelayerdata();
							}
						});\n";
						
						$layerCoordHiddenFields[$layername]="{\"x\":".$layerData['posX'].",\"y\":".$layerData['posY']."}";
						
					break;
					case "image":
						echo "$('canvas').addLayer({
							type:'image',
							draggable: ".$layerData['draggable'].",
							source: '".$layerData['imageSrc']."',
							x:".$layerData['posX'].",
							y:".$layerData['posY'].",
							width:".$layerData['width'].",
							height:".$layerData['height'].",
							fromCenter:".$layerData['fromCenter'].",
							dblclick: function(layer){
								$('canvas').removeLayer(layer).drawLayers();
								updatelayerdata();
							}
						});\n";
						
						$layerCoordHiddenFields[$layername]="{\"x\":".$layerData['posX'].",\"y\":".$layerData['posY'].",\"w\":".$layerData['width'].",\"h\":".$layerData['height']."}";
						
					break;
					case "line":						
						echo "$('canvas').addLayer({
	  						type: 'line',
	  						strokeStyle:'".$layerData['strokeStyle']."',
							strokeWidth:".$layerData['strokeWidth'].",
							x:".$layerData['x'].",
							y:".$layerData['y'].",
							x1:".$layerData['x1'].",
							y1:".$layerData['y1'].",
							x2:".$layerData['x2'].",
							y2:".$layerData['y2'].",
							draggable:".$layerData['draggable'].",
							fromCenter:".$layerData['fromCenter'].",
							dblclick: function(layer){
								$('canvas').removeLayer(layer).drawLayers();
								updatelayerdata();
							}
						});\n";
						
						
						$layerCoordHiddenFields[$layername]="{\"x1\":".$layerData['x1'].",\"y1\":".$layerData['y1'].",\"x2\":".$layerData['x2'].",\"y2\":".$layerData['y2']."}";
						
					break;
					case "rectangle":
						echo "$('canvas').addLayer({
							type:'rectangle',
							strokeStyle:'".$layerData['strokeStyle']."',
							strokeWidth:'".$layerData['strokeWidth']."',
							x:".$layerData['x'].",
							y:".$layerData['y'].",
							draggable:".$layerData['draggable'].",
							fromCenter:".$layerData['fromCenter'].",
							width:".$layerData['width'].",
							height:".$layerData['height'].",
							dblclick: function(layer){
								$('canvas').removeLayer(layer).drawLayers();
								updatelayerdata();
							}
						});\n";
					break;
					case "ellipse":
						echo "$('canvas').addLayer({
							type:'ellipse',
							strokeStyle:'".$layerData['strokeStyle']."',
							strokeWidth:'".$layerData['strokeWidth']."',
							x:".$layerData['x'].",
							y:".$layerData['y'].",
							draggable:".$layerData['draggable'].",
							fromCenter:".$layerData['fromCenter'].",
							width:".$layerData['width'].",
							height:".$layerData['height'].",
							dblclick: function(layer){
								$('canvas').removeLayer(layer).drawLayers();
								updatelayerdata();
							}
						});\n";
					break;
				}
			}
			
		
	?>
	$('canvas').drawLayers();
	
	

	$('#availableLayers input[type=checkbox]').change(function(){
		if($(this).is(':checked')){
			
			if($(this).attr('name')=='customlayer'){
				var thistext=$(this).attr('data-layervalue');
			}else{
				var thistext=$(this).attr('data-layervalue')+' '+$(this).parent().find('b').html().replace(':','');
			}
			
			var thislayercoordelementid='#coords-'+$(this).attr('data-layername').replace('_','-');
			
			$('canvas').drawText({
		  		layer: true,
				name: $(this).attr('data-layername'),
				draggable:true,
		  		fillStyle: '#000',
		  		x:250, y:320,
		  		fontSize: '11pt',
		  		fontFamily: 'Verdana, sans-serif',
		  		text: thistext,
				mousemove: function(layer) {
					var thislayerjsonval={"x":layer.x,"y":layer.y,"w":Math.round(layer.width),"h":Math.round(layer.height)};
					$(thislayercoordelementid).val(JSON.stringify(thislayerjsonval));	
				  }
			}).drawLayers();
		}else{
			$('canvas').removeLayer($(this).attr('data-layername')).drawLayers();
		}
	});
		
		
	$('#customvalue').keyup(function(){
		$('canvas').removeLayer('customlayer').drawLayers();
			
		if($(this).val() != ''){
			if(!$('input[name=customlayer]').is(':checked')){
				$('input[name=customlayer]').prop('checked',true);
			}
			$('input[name=customlayer]').attr('data-layervalue',$(this).val());
			
			$('canvas').drawText({
		  		layer: true,
				name: 'customlayer',
				draggable:true,
		  		fillStyle: '#000',
		  		x:250, y:320,
		  		fontSize: '11pt',
		  		fontFamily: 'Verdana, sans-serif',
		  		text: $(this).val()
			}).drawLayers();
		}
	});
	
});
		
	

	
	
	
function escapeHtml(text) {
  return text
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;")
	  .replace(/\[/g, "&#91;")
	  .replace(/\]/g, "&#93;");
}
	
	
function updatelayerdata(){
	//console.clear();
	var output='{';
	var allLayers=$('canvas').getLayers();
	$.each(allLayers,function(index,data){
		//console.log(data);
		if(data.name == 'whitebackgroundlayer' || (data.type=='rectangle' && data.width==7 && data.height==7)){
			//ignore
		}else{
			output += '"layer_'+index+'":{"layerType":"'+data.type+'",';
			if(data.type=='image'){
				output += '"imageSrc":"'+data.source+'","draggable":"true","fromCenter":"false","posX":'+data.x+',"posY":'+data.y+',"width":'+Math.round(data.width)+',"height":'+Math.round(data.height);
			}else if(data.type=='text'){
				output += '"textValue":"'+escapeHtml(data.text)+'","fillStyle":"'+data.fillStyle+'","font":"'+data.fontFamily+'","fontSize":"'+data.fontSize+'","fontStyle":"'+data.fontStyle+'","draggable":"true","fromCenter":"false","textAlign":"left","posX":'+data.x+',"posY":'+data.y+',"width":'+Math.round(data.width)+',"height":'+Math.round(data.height);
			}else if(data.type == 'line'){
				output += '"strokeStyle":"#000","strokeWidth":"2","x":"'+data.x+'","y":"'+data.y+'","x1":"'+data.x1+'","y1":"'+data.y1+'","x2":"'+data.x2+'","y2":"'+data.y2+'","draggable":"true","fromCenter":"false"';
			}else if(data.type == 'rectangle'){
				output += '"strokeStyle":"'+data.strokeStyle+'","strokeWidth":"'+data.strokeWidth+'","x":"'+data.x+'","y":"'+data.y+'","width":"'+data.width+'","height":"'+data.height+'","draggable":"true","fromCenter":"false"';
			}else if(data.type == 'ellipse'){
				output += '"strokeStyle":"'+data.strokeStyle+'","strokeWidth":"'+data.strokeWidth+'","x":"'+data.x+'","y":"'+data.y+'","width":"'+data.width+'","height":"'+data.height+'","draggable":"true","fromCenter":"false"';
			}
			output += "},\n";
		}
	});
	
	var fulloutput = output.substring(0,(output.length - 2));
	fulloutput += "\n}";
	$('#overlayraw textarea').val(fulloutput);
	
	$('input#pngdata').val($('canvas').getCanvasImage('png'));
	
	
}
	
	
function addtextlayer(newval,colorval,fontval,fontsizeval,fontstyleval){
	$('canvas').addLayer({
		type:'text',
		draggable:true,
		fillStyle: colorval,
		x:250,
		y:165,
		fromCenter:false,
		fontSize: fontsizeval,
		fontFamily: fontval,
		fontStyle:fontstyleval,
		text: newval,
		align:'left',
		mousemove: function(layer){
			updatelayerdata();
		},
		mouseup: function(layer){
			updatelayerdata();
		},
		drag: function(layer){
			updatelayerdata();
		},
		dragstop: function(layer){
			updatelayerdata();
		},
		touchend: function(layer){
			updatelayerdata();
		},
		touchmove: function(layer){
			updatelayerdata();
		},
		click: function(layer){
			updatelayerdata();
		},
		dblclick: function(layer){
			$('canvas').removeLayer(layer).drawLayers();
			updatelayerdata();
		}
	}).drawLayers();
	$.fancybox.close();
	updatelayerdata();
}

function addimagelayer(filename,widthval,heightval){
	$('canvas').addLayer({
		type:'image',
		draggable: true,
		source: filename,
		x:300,
		y:245,
		width:widthval,
		height:heightval,
		fromCenter:false,
		handle: {
			type: 'rectangle',
			fillStyle: '#fff',
			strokeStyle: '#f00',
			strokeWidth: 1,
			width:7,height:7,
			radius:0
		  },
		mousemove: function(layer){
			updatelayerdata();
		},
		mouseup: function(layer){
			updatelayerdata();
		},
		drag: function(layer){
			updatelayerdata();
		},
		dragstop: function(layer){
			updatelayerdata();
		},
		touchend: function(layer){
			updatelayerdata();
		},
		touchmove: function(layer){
			updatelayerdata();
		},
		click: function(layer){
			updatelayerdata();
		},
		dblclick: function(layer){
			$('canvas').removeLayer(layer).drawLayers();
			updatelayerdata();
		}
	}).drawLayers();
	$.fancybox.close();
	updatelayerdata();
}

//setInterval('updatelayerdata()',850);
	
	
	
	
	
		
function downloadCanvas(link,filename) {

	updatelayerdata();
	
	var canvasPNGdata=$('canvas').getCanvasImage('jpeg',0.9);
	link.href = canvasPNGdata;
	link.download = filename;
	
	
	var imgData = $('canvas').getCanvasImage("jpeg", 1.0);
  	var pdf = new jsPDF('l','in',[11,8.5]);
	pdf.addImage(imgData, 'JPEG', 0, 0);
  	pdf.save("WO <?php echo $thisOrder['order_number']." - Line ".$lineItemData['line_number']." Blue Sheet"; ?>.pdf");
	
}

		
</script>
<?php
foreach($layerCoordHiddenFields as $fieldname => $fieldval){
	echo $this->Form->input($fieldname,["type"=>"hidden","id"=>"coords-".$fieldname,"value"=>$fieldval]);
}
?>
<style>
body{ background:#ccc; text-align:center; font-family:'Verdana'; }
canvas{ margin:15px auto; }
	
#overlayraw{ display:none; }
	
#availableLayers{ text-align:left; padding:0px; margin:15px auto; }
#availableLayers div{ padding:3px 0; }

#availableLayers blockquote{ font-style:italic; margin:15px 0; }
	

#canvaswrap{ float:left; width:1000px; }
#availableLayers{ width:230px; float:right; }

#availableLayers h1{ font-size:large; font-weight:bold; }
#availableLayers h3{ font-size:medium; font-weight:bold; }
	
#builderwrap{ width:1250px; margin:15px auto 0 auto; }
	
button[type=button]{ background:#131851; color:#FFF; border:1px solid #000; padding:10px 2%; width:98%; display:block; margin:12px 0px; cursor:pointer; }
button[type=button]:hover{ background:#385AB8; }
</style>

<div id="builderwrap">
	<div id="canvaswrap">
	<canvas id="thecanvas" width="1000" height="770"></canvas>
	</div>


<div id="availableLayers">
		<h3>ORDER <?php echo $thisOrder['order_number']; ?> &mdash; LINE <?php echo $lineItemData['line_number']; ?></h3>
		<h1>BUILD BLUE SHEET</h1>
<hr>
		
<button type="button" id="addimage">+ Add Library Image</button>
<button type="button" id="addtext">+ Add Text</button>
<button type="button" id="addline">+ Add Line</button>
<button type="button" id="addrectangle">+ Add Rectangle</button>
<button type="button" id="addcircle">+ Add Circle</button>

<hr>
	<div style="text-align:center;"><a id="downloadpng" onclick="downloadCanvas(this,'diagram.png');"><img src="/img/downloadpdf.png" style="width:85%; margin:auto;" /></a></div>
</div>
	
	<div style="clear:both;"></div>
</div>
<?php
echo $this->Form->end();
?>