<style>
.disabledoption label{ color:#CCC; }
</style>
<?php
echo "<div style=\"width:800px; margin:0 auto;\"><h1 style=\"margin-top:30px; font-size:24px; text-align:center;\">Advanced Inventory Search</h1><hr>";

echo "<div id=\"accordion\">";
echo "<h3 data-sectiontag=\"rollnumber\">Lookup Roll By Roll Number</h3><div>";
echo "<p><label>Roll Number: <input type=\"number\" min=\"0\" name=\"rollnumber\" style=\"display:inline-block; width:155px;\" /></label></p>";
echo "</div>";

echo "<h3 data-sectiontag=\"wonumber\">Lookup By Work Order #</h3><div>";
echo "<p><label>Work Order #: <input type=\"text\" name=\"wonumber\" style=\"display:inline-block; width:200px;\" /></label></p>";
echo "</div>";

echo "<h3 data-sectiontag=\"namecollectionvendor\">Lookup By Fabric Name, Collection, or Vendor</h3><div>";

echo "<p><label style=\"display:inline;\">Fabric Name <input type=\"text\" name=\"fabricname\" style=\"display:inline-block; width:115px;\" /></label> &nbsp;&nbsp;&nbsp;&nbsp; <label style=\"display:inline;\">Collection: <select name=\"collection_name\" style=\"display:inline-block; width:160px;\"><option value=\"0\" selected>--Select Collection--</option>";
foreach($allCollections as $collection){
	echo "<option value=\"".$collection."\">".$collection."</option>";
}
echo "</select></label> &nbsp;&nbsp;&nbsp;&nbsp; <label style=\"display:inline;\">Vendor: <select name=\"vendor_id\" style=\"display:inline-block; width:160px;\"><option value=\"0\" selected>--Select Vendor--</option>";
foreach($allVendors as $vendor){
	echo "<option value=\"".$vendor['id']."\">".$vendor['vendor_name']."</option>\n";
}
echo "</select></label></p>";

echo "</div>";


echo "<h3 data-sectiontag=\"comowner\">Lookup By Owner (COM)</h3><div>";

echo "<p><label>Customer: <select name=\"comowners\" style=\"display:inline-block; width:250px;\"><option value=\"0\" selected>--Select a Customer--</option>";
foreach($allCOMs as $com){
	echo "<option value=\"".$com['id']."\">".$com['company_name']."</option>\n";
}
echo "</select></label></p>";
echo "</div>";



echo "<h3 data-sectiontag=\"yardages\">Lookup By Yardage + Configuration</h3><div>";

echo "<p><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
<tr style=\"border-bottom:0 !important;\">
<td width=\"40%\" valign=\"top\" align=\"left\">
	Find Fabrics with <input type=\"number\" value=\"0\" style=\"display:inline-block; margin:0 5px; width:90px;\" step=\"any\" name=\"totalyards\" /> yards
</td>
<td width=\"3%\">&nbsp;</td>
<td width=\"21%\" valign=\"top\">
	<div><label><input type=\"radio\" id=\"totaloptiontotal\" name=\"totaloption\" value=\"total\" /> Total</label></div>
	<div><label><input type=\"radio\" id=\"totaloptiononhand\" name=\"totaloption\" value=\"onhand\" /> On Hand</label></div>
	<div><label><input type=\"radio\" id=\"totaloptionavailable\" name=\"totaloption\" value=\"available\" checked=\"checked\" /> Available</label></div>
</td>
<td width=\"2%\">&nbsp;</td>
<td width=\"23%\" align=\"left\" valign=\"top\">
	<div style=\"margin-top:32px;\" id=\"quiltingoptionwrap\"><label><input type=\"checkbox\" id=\"totalquiltingquilted\" name=\"totalquiltingquilted\" value=\"yes\" /> Quilted</label></div>
	<div id=\"unquiltingoptionwrap\"><label><input type=\"checkbox\" id=\"totalquiltingunquilted\" name=\"totalquiltingunquilted\" value=\"yes\" /> Unquilted</label></div>
</td>
<td width=\"11%\">&nbsp;</td>
</tr></table></p>";
echo "<p>Configuration:
		<label><input id=\"nonspecific\" type=\"radio\" name=\"yardageconfig\" checked=\"checked\" value=\"any\" /> Any Configuration</label>
		<label><input id=\"yardspecific\" type=\"radio\" name=\"yardageconfig\" value=\"specific\" /> Specific Configuration</label>

		<div id=\"configoptions\" style=\"padding-left:40px; display:none;\">
			<p>Lookup fabrics having <input name=\"config-numrolls\" type=\"number\" step=\"any\" value=\"0\" style=\"display:inline-block; margin:0 5px; width:90px;\" /> rolls with <input name=\"config-yardsperroll\" type=\"number\" step=\"any\" value=\"0\" style=\"display:inline-block; margin:0 5px; width:90px;\" /> yards per roll</p>
		</div>
		</p>";
echo "<p>Find Fabrics In:
		<label><input type=\"checkbox\" name=\"momrosterfabrics\" value=\"yes\" checked=\"checked\" /> HCI Roster Fabrics</label>
		<label><input type=\"checkbox\" name=\"nonrostermom\" value=\"yes\" /> Non-Roster MOM Fabrics</label>
		<label><input type=\"checkbox\" name=\"comfabrics\" value=\"yes\" /> COM Fabrics</label>
		</p>";
echo "</div>";

echo "</div>";

echo "<input type=\"hidden\" name=\"activesection\" value=\"rollnumber\" />";

echo "<div style=\"padding:15px 0; text-align:right;\"><button type=\"button\" id=\"dosearch\">Perform Search</button></div>";

?>
</div>

<div id="resultsblock"></div>

<script>
$(function(){

	$('#totaloptiontotal,#totaloptionavailable,#totaloptiononhand').click(function(){
		if($('#totaloptiontotal').is(':checked')){
			$('#quiltingoptionwrap,#unquiltingoptionwrap').addClass('disabledoption');
			$('#totalquiltingunquilted,#totalquiltingquilted').prop('disabled',true).prop('checked',false);
		}else{
			$('#quiltingoptionwrap,#unquiltingoptionwrap').removeClass('disabledoption');
			$('#totalquiltingunquilted,#totalquiltingquilted').prop('disabled',false);
		}
	});


	$('input[name=rollnumber]').focus();

	$('input[type=radio]').change(function(){
		if($('#yardspecific').is(':checked')){
			$('#configoptions').show('fast');
		}else{
			$('#configoptions').hide('fast');
		}
	});

    $( "#accordion" ).accordion({
    	header:'h3',
    	heightStyle: 'content',
    	animate: 100,
    	active: 0,
    	activate: function( event, ui ) {
    		if(ui.newHeader.attr('data-sectiontag')=='wonumber'){
    			$('input[name=wonumber]').focus();
    		}else if(ui.newHeader.attr('data-sectiontag') == 'rollnumber'){
    			$('input[name=rollnumber]').focus();
    		}else if(ui.newHeader.attr('data-sectiontag') == 'namecollectionvendor'){
    			$('input[name=fabricname]').focus();
    		}else if(ui.newHeader.attr('data-sectiontag') == 'comowner'){
    			$('select[name=comowners]').focus();
    		}else if(ui.newHeader.attr('data-sectiontag') == 'yardages'){
    			$('input[name=totalyards]').focus();
    		}
    		$('input[name=activesection]').val(ui.newHeader.attr('data-sectiontag'));
    	}
    });

    $('#dosearch').click(function(){

    	var searcharray;

    	//validate the inputs
    	if($('input[name=activesection]').val() == 'yardages'){
    		if(parseFloat($('input[name=totalyards]').val()) == 0){
    			alert('Please enter a minimum total yards to look for');
    			$('input[name=totalyards]').focus();
    			return false;
    		}

    		if($('#yardspecific').is(':checked')){
    			if(parseFloat($('input[name=\'config-numrolls\']').val()) == 0){
    				alert('Please enter the Number of Rolls you are requiring');
    				$('input[name=\'config-numrolls\']').focus();
    				return false;
    			}

    			if(parseFloat($('input[name=\'config-yardsperroll\']').val()) == 0){
    				alert('Please enter the number of Yards per Roll minimum you require.');
    				$('input[name=\'config-yardsperroll\']').focus();
    				return false;
    			}

    			searcharray={
    				'activesection':'yardages',
    				'totalyards':$('input[name=totalyards]').val(),
    				'yardageconfig': 'specific',
    				'config-numrolls':$('input[name=config-numrolls]').val(),
    				'config-yardsperroll':$('input[name=config-yardsperroll]').val()
    			};

    		}else{

    			searcharray={
    				'activesection':'yardages',
    				'totalyards':$('input[name=totalyards]').val(),
    				'yardageconfig': 'any',
    			};

    		}

    		if(!$('input[name=momrosterfabrics]').is(':checked') && !$('input[name=nonrostermom]').is(':checked') && !$('input[name=comfabrics]').is(':checked')){
    			alert('Please check which types of fabrics you want to search in.');
    			return false;
    		}


    		if($('input[name=momrosterfabrics]').is(':checked')){
    			searcharray.momrosterfabrics='yes';
    		}

			if($('input[name=nonrostermom]').is(':checked')){
    			searcharray.nonrostermom='yes';
    		}

    		if($('input[name=comfabrics]').is(':checked')){
    			searcharray.comfabrics='yes';
    		}


    		if($('#totaloptiontotal').is(':checked')){
    			searcharray.totaloption='total';
    		}else if($('#totaloptiononhand').is(':checked')){
    			searcharray.totaloption='onhand';
    		}else if($('#totaloptionavailable').is(':checked')){
    			searcharray.totaloption='available';
    		}


    		if($('#totalquiltingquilted').is(':checked')){
    			searcharray.searchingquilted='yes';
    		}else{
    			searcharray.searchingquilted='no';
    		}


    		if($('#totalquiltingunquilted').is(':checked')){
    			searcharray.searchingunquilted='yes';
    		}else{
    			searcharray.searchingunquilted='no';
    		}


    	}else if($('input[name=activesection]').val() == 'comowner'){
    		searcharray={'activesection':'comowner','comowners':$('select[name=comowners]').val()};

    	}else if($('input[name=activesection]').val() == 'namecollectionvendor'){
    		

    	}else if($('input[name=activesection]').val() == 'wonumber'){
    		searcharray={'activesection':'wonumber','wonumber':$('input[name=wonumber]').val()};

    	}else if($('input[name=activesection]').val() == 'rollnumber'){
    		if($('input[name=rollnumber]').val() == ''){
    			alert('Please enter a Roll Number to search for.');
    			$('input[name=rollnumber]').focus();
    			return false;
    		}

    		searcharray={'activesection':'rollnumber','rollnumber':$('input[name=rollnumber]').val()};
    	}


    	//do the search
    	$.fancybox.showLoading();
    	

    	$.ajax({
    		'url':'/products/lookuprollresults/',
    		'method':'POST',
    		'data':searcharray,
    		'dataType':'html',
    		'success':function(response){
    			$('#resultsblock').html(response);
    			$.fancybox.hideLoading();
    		}
    	});

    });
});
</script>