<div id="textwrap">
<h4>New Text Layer Content:</h4>
<textarea id="textvalue" placeholder="Text content for this layer"></textarea>
<p><label>Font Face:</label><select id="fontface">
	<option value="Verdana" selected="selected">Verdana</option>
	<option value="Impact">Impact</option>
	<option value="Times New Roman">Times New Roman</option>
	<option value="Georgia">Georgia</option>
</select></p>
<p><label>Font Color:</label><select id="fontcolor">
	<option value="#000" selected="selected">Black</option>
	<option value="#F00">Red</option>
	<option value="#0F0">Green</option>
	<option value="#00F">Blue</option>
	<option value="#FF0">Yellow</option>
	<option value="#FFF">White</option>
	<option value="#777">Gray</option>
</select></p>
<p><label>Font Size:</label><select id="fontsize">
	<option value="6pt">6 point</option>
	<option value="8pt">8 point</option>
	<option value="10pt">10 point</option>
	<option value="11pt" selected="selected">11 point</option>
	<option value="12pt">12 point</option>
	<option value="14pt">14 point</option>
	<option value="18pt">18 point</option>
	<option value="20pt">20 point</option>
	<option value="24pt">24 point</option>
	<option value="28pt">28 point</option>
	<option value="32pt">32 point</option>
	<option value="44pt">44 point</option>
</select></p>
<p><label>Font Style:</label><select id="fontstyle">
	<option value="normal">Normal</option>
	<option value="bold">Bold</option>
	<option value="italic">Italic</option>
</select></p>
</div>

<fieldset>
			<legend>VARIABLE MACROS</legend>
			<dl>
				<dt>Due Date</dt>
				<dd><input type="text" readonly="readonly" value="#DUEDATE#" /></dd>

				<dt>Customer</dt>
				<dd><input type="text" readonly="readonly" value="#CUSTOMER#" /></dd>

				<dt>Current User's Name</dt>
				<dd><input type="text" readonly="readonly" value="#THISUSER#" /></dd>

				<dt>Fabric Name</dt>
				<dd><input type="text" readonly="readonly" value="#FABRICNAME#" /></dd>
				
				<dt>Fabric Color</dt>
				<dd><input type="text" readonly="readonly" value="#FABRICCOLOR#" /></dd>
				
				<dt>Lining Name</dt>
				<dd><input type="text" readonly="readonly" value="#LININGNAME#" /></dd>
				
				<dt>Facility</dt>
				<dd><input type="text" readonly="readonly" value="#FACILITY#" /></dd>

				<dt>Work Order Number</dt>
				<dd><input type="text" readonly="readonly" value="#ORDERNUMBER#" /></dd>
	
				<dt>Line Item Meta Data</dt>
				<dd><select id="metakeys" onchange="updatemetamacro(this.value)"><option value="meta_key" selected>--Select Meta Key--</option>
					<?php
					foreach($metaKeys as $key){
						echo "<option value=\"".$key['meta_key']."\">".$key['meta_key']."</option>\n";
					}
					?>
					</select></dd>
				<dd><input id="metamacro" type="text" value="#META=meta_key#" /></dd>
			</dl>
		</fieldset>


<div style="clear:both;"></div>
<br>
<p><button style="float:left;" type="button" onclick="parent.addtextlayer($('textarea#textvalue').val(),$('#fontcolor').val(),$('#fontface').val(),$('#fontsize').val(),$('#fontstyle').val());">Add As Layer To Template</button><button type="button" onclick="parent.$.fancybox.close();" style="float:right;">Cancel</button><div style="clear:both;"></div></p>

<style>
body{ background: #fff; font-family:'Helvetica',Arial,sans-serif; }

#textwrap h4{ color:blue; margin:5px 0 5px 0; font-size:medium; }
#textwrap{ width:56%; float:left; }
textarea#textvalue{ width:98%; height:210px; }

fieldset{ font-size:small; width:37%; float:right; background:rgba(0,0,0,0.04); border:1px solid #333; margin-top:0px; }
fieldset legend{ font-weight:bold; color:blue; font-weight:bold; }
fieldset dl dt{ font-weight:bold; }
	
fieldset dl dd{ padding-left:10px; margin-left:0; margin-bottom:10px; }

fieldset input{ background:transparent !important;  border:0; padding:5px 5px; width:230px; outline:0; }
	
#metakeys{ width:210px !important; }
</style>
<script>
function updatemetamacro(newval){
	$('#metamacro').val('#META='+newval+'#');
}
	
$(function(){
	$('fieldset input[type=text]').hover(function(){
		$(this).select();
	});
	$('fieldset input[type=text]').click(function(){
		$(this).select();
	});
});
</script>