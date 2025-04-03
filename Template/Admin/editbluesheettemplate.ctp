<?php
echo $this->Form->create(null);
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jcanvas/20.1.4/min/jcanvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jcanvas/20.1.4/min/jcanvas-handles.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.7/js/jquery.fancybox.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.7/css/jquery.fancybox.min.css" type="text/css" rel="stylesheet" />

<div id="overlayraw">
<?php echo $this->Form->input('rawjson',['type'=>'textarea','value'=>$thisTemplate['json_preload_layers']]); ?>
</div>

<?php
echo $this->Form->input('pngdata',['type'=>'hidden']);
?>

<div id="wrapper">
	<div id="canvaswrap">
		<canvas id="thecanvas" width="1000" height="770"></canvas>
	</div>

	<div id="rightside">
		
		<h1>BLUE SHEET TEMPLATE</h1>

		<p style="text-align: center;"><small>To Delete a Layer, Double Click on it</small></p>
		
		
		<button type="button" id="addimage">+ Add Library Image</button>
		<button type="button" id="addtext">+ Add Text</button>
		<button type="button" id="addline">+ Add Line</button>
		<button type="button" id="addrectangle">+ Add Rectangle</button>
		<button type="button" id="addcircle">+ Add Circle</button>
	</div>
	<div style="clear:both;"></div>
</div>

<style>
body{ background: #CCC; font-family:'Helvetica',Arial,sans-serif; }
	
#welcomebar{ display: none !important; }
	
#wrapper h1{ text-align: center; margin:5px 0; font-size:large; }
hr{ margin:5px 0; }
canvas{ margin:0px auto; }
#canvaswrap{ text-align: center; }
	
#wrapper{ width:1250px; margin:15px auto 0 auto; }
button[type=button]{ background:#131851; color:#FFF; border:1px solid #000; padding:10px 2%; width:98%; display:block; margin:12px 0px; cursor:pointer; }
button[type=button]:hover{ background:#385AB8; }

#canvaswrap{ width:1000px; float:left; }
#rightside{ width:230px; float:right; }
	
fieldset{ width:195px; background:rgba(255,255,255,0.3); border:1px solid #333; margin-top:35px; }
fieldset legend{ font-weight:bold; color:blue; font-weight:bold; }
fieldset dl dt{ font-weight:bold; }
	
fieldset dl dd{ padding-left:10px; margin-left:0; margin-bottom:10px; }

fieldset input{ background:transparent !important;  border:0; padding:5px 5px; width:155px; outline:0; }
	
div#savebar{ text-align:center; width:1250px; margin:0px auto; }
	
div#savebar select{ display:inline-block; padding:10px 15px; width:auto !important; }
div#savebar div.submit{ display:inline-block; padding:10px 15px; }

div#savebar label{ display:inline-block; font-weight:bold; color:blue; margin:0px 10px 0px 30px; }

div#savebar div.input.text{ display:inline-block; margin:0px 10px 0px 30px; }
div#savebar div.input.text input[type=text]{ padding:10px; width:300px !important; display:inline-block !important; }

input[type=submit]{ background:#19560F; color:#FFF; cursor:pointer; font-weight:bold; padding:10px 20px; border:1px solid #000;  }	
	
#overlayraw{ 
	/*display:none; */
	visibility: hidden;
	word-wrap:break-word; font-size:small; background:rgba(255,0,0,0.3); position:absolute; top:0px; left:0px; z-index:555; width:260px; height:500px; border:1px solid #333; overflow-y:scroll; }
	
#overlayraw textarea{ background:none; border:0; width:98%; height:450px; }
	
#hoveractionoverlay{ font-size:10px; position:absolute; top:10px; right:10px; z-index:5555; width:150px; height:30px; text-align: center; line-height:30px; background:rgba(255,0,0,0.09); }
</style>

<script>
$(function(){
	
	var currentMousePos = { x: -1, y: -1 };
    $(document).mousemove(function(event) {
        currentMousePos.x = event.pageX;
        currentMousePos.y = event.pageY;
    });
	
	//preload the white canvas base layer
	$('canvas').addLayer({
	  name:'whitebackgroundlayer',
	  type: 'image',
	  draggable: false,
	  source: '/img/whitebase.jpg',
	  x:0, y:0,
	  height:1540, width:2000
	}).drawLayers();
	
	
	<?php
	//preload the saved template layer data
	$layers=json_decode($thisTemplate['json_preload_layers'],true);
			
			
	foreach($layers as $layername => $layerData){
		switch($layerData['layerType']){
			case "text":

				$parsedTextValue=$layerData['textValue'];

				
				echo "$('canvas').addLayer({
					type:'text',
					draggable:".$layerData['draggable'].",
					fillStyle: '".$layerData['fillStyle']."',
					x:".$layerData['posX'].",
					y:".$layerData['posY'].",
					fromCenter:".$layerData['fromCenter'].",
					fontSize: '".$layerData['fontSize']."',
					fontFamily: '".$layerData['font']."',
					fontStyle:'".$layerData['fontStyle']."',
					text: '".$layerData['textValue']."',
					align:'".$layerData['textAlign']."',
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
				});\n";


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
				});\n";


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
				});\n";


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
				});\n";
			break;
		}
	}		
		
	?>
	$('canvas').drawLayers();
	

	
	
	
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
			height:510,
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
	
	//$('#previewimg').attr('src',$('canvas').getCanvasImage('png'));
	
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
</script>


<?php
echo "<div id=\"savebar\">";
echo $this->Form->input('template_title',['required'=>true,'autocomplete'=>'off','value'=>$thisTemplate['template_name']]);

echo $this->Form->radio('status',['Active'=>'Active','Inactive'=>'Incative'],['value'=>$thisTemplate['status'],'required'=>true]);

echo $this->Form->submit('SAVE CHANGES');
echo "</div>";
echo "<br><Br>";
echo $this->Form->end();

//<img id="previewimg" width="600" height="300" />
?>