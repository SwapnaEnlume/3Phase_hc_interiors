<style>
body{ font-family:'Helvetica',Arial,sans-serif; }
button{ background:#2345A0; color:#FFF; border:1px solid #000; padding:10px 20px; font-size:large; cursor:pointer; }
</style>
<h3 style="text-align: center;">Generate Work Order PDF:</h3>
<?php
/*
echo "<pre>";
print_r($orderData);
echo "</pre>";
*/
?>
<ul style="list-style:none;">
	<?php if(intval($orderData['cc_qty']) >0){ ?>
	<li>
		<label><input type="checkbox" name="cc" value="y" checked="checked" /> Cubicle Curtains</label>
	</li>
	<?php } ?>
	<?php if(intval($orderData['bs_qty']) >0){ ?>
	<li>
		<label><input type="checkbox" name="bs" value="y" checked="checked" /> Bedding</label>
	</li>
	<?php } ?>
	
	<?php if(intval($orderData['corn_qty']) >0){ ?>
	<li>
		<label><input type="checkbox" name="swtcornice" value="y" checked="checked" /> SWT Cornices</label>
	</li>
	<?php } ?>
	
	<?php if(intval($orderData['drape_qty']) >0){ ?>
	<li>
		<label><input type="checkbox" name="swtdraperies" value="y" checked="checked" /> SWT Draperies</label>
	</li>
	<?php } ?>
	
	<?php if(intval($orderData['val_qty']) >0){ ?>
	<li>
		<label><input type="checkbox" name="swtvalance" value="y" checked="checked" /> SWT Valances</label>
	</li>
	<?php } ?>
	
	<?php if(intval($orderData['swtmisc_qty']) >0){ ?>
	<li>
		<label><input type="checkbox" name="swtmisc" value="y" checked="checked" /> SWT Misc</label>
	</li>
	<?php } ?>
	
	
	
	
	
	<?php if(floatval($orderData['track_lf']) >0){ ?>
	<li>
		<label><input type="checkbox" name="track" value="y" checked="checked" /> Track</label>
	</li>
	<?php } ?>
	<?php if(intval($orderData['catchall_qty']) >0){ ?>
	<li>
		<label><input type="checkbox" name="catchall" value="y" checked="checked" /> Catch-All/Other</label>
	</li>
	<?php } ?>
</ul>

<p style="text-align: center; "><button type="button" onclick="gotowopdf()">Generate PDF</button></p>

<script>
function gotowopdf(){
	var types='';
	var filenametypes='';
	if($('input[name=cc]').is(':checked')){
		types += 'cc_';
		filenametypes += 'CC, ';
	}
	if($('input[name=bs]').is(':checked')){
		types += 'bs_';
		filenametypes += 'BS, ';
	}
	/*
	if($('input[name=wt]').is(':checked')){
		types += 'wt_';
		filenametypes += 'WT, ';
	}
	*/

    if($('input[name=swtcornice]').is(':checked')){
		types += 'swtcornice_';
		filenametypes += 'Cornice, ';
	}
	
	if($('input[name=swtvalance]').is(':checked')){
		types += 'swtvalance_';
		filenametypes += 'Valance, ';
	}
	
	if($('input[name=swtdraperies]').is(':checked')){
		types += 'swtdraperies_';
		filenametypes += 'Draperies, ';
	}
	
	if($('input[name=swtmisc]').is(':checked')){
		types += 'swtmisc_';
		filenametypes += 'SWTMisc, ';
	}
	
	
	if($('input[name=track]').is(':checked')){
		types += 'track_';
		filenametypes += 'Track, ';
	}
	if($('input[name=catchall]').is(':checked')){
		types += 'catchall_';
		filenametypes += 'Other, ';
	}
	
	var urlTypes=types.substring(0,(types.length -1));
	var fileAppend=filenametypes.substring(0,(filenametypes.length - 2));
	
	parent.location.href='/orders/buildworkorderpdf/<?php echo $orderData['id']; ?>/'+urlTypes+'/WO <?php echo $orderData['order_number']; ?> ('+fileAppend+').pdf';
}
</script>