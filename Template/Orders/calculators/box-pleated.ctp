<style>
body{font-family:'Helvetica',Arial,sans-serif; font-size:medium;}
form{ text-align:center; width:650px; }
form label{ font-weight:bold; float:left; width:75%; text-align:right; font-size:small; vertical-align:middle; }
form input{ float:right; padding:2px; width:20%; vertical-align:middle; font-size:12px; }
form select{ float:right; padding:2px; width:22%; vertical-align:middle; font-size:12px; }
form div.input{ clear:both; padding:10px 0; }
#calculatedPrice{ clear:both; padding:20px 0; font-size:x-large;  }
.clear{ clear:both; }
#calcformleft{ width:49.5%; float:left; }
#resultsblock{ width:49.5%; float:right; }

label[for=fabric-half-width-status]{ color:rgb(255, 165, 0); }
label[for=total-fabric-widths]{ color:rgb(255, 165, 0); }
label[for=lining-half-width-status]{ color:rgb(255, 165, 0); }

label[for=valance-fabric-widths]{ color:blue; }
label[for=valance-lining-widths]{ color:blue; }
label[for=fabric-cost]{ color:blue; }
label[for=lining-cost]{ color:blue; }
label[for=labor-cost]{ color:blue; }

label[for=fabric-cl]{ color:rgb(0, 128, 0); }
label[for=lining-cl]{ color:rgb(0, 128, 0); }
label[for=fabric-yards]{ color:rgb(0, 128, 0); }
label[for=lining-yards]{ color:rgb(0, 128, 0); }

label[for=price-per-valance]{ color:red; }

h2{ font-size:medium; margin:10px 0; }

label[for=lining-half-width-status]{ display:none; }
#lining-half-width-status{ width: 91% !important; font-size:12px; text-align:center; background:#FFF; border:0; }

label[for=fabric-half-width-status]{ display:none; }
#fabric-half-width-status{ width: 91% !important; font-size:12px; text-align:center; background:#FFF; border:0; }

label[for=total-fabric-widths]{ display:none; }
#total-fabric-widths{ resize:none; font-family:'Helvetica',Arial,sans-serif; width: 91% !important; height: 40px; font-size:12px; text-align:center; background:#FFF; border:0; }

label[for=fabric-yards]{ width:40% !important; }
#fabric-yards{ width:54% !important; font-size:12px; }

label[for=fabric-cost]{ width:40% !important; }
#fabric-cost{ width:54% !important; font-size:12px; }

.greenline{ background:#fff; border:0; color:green; }
.redline{ background:#fff; border:0; color:red; }
.grayline{ background:#fff; border:0; font-style:italic; }

.calculatebutton{ float:left; width:40%; padding:15px 0; }
.addaslineitembutton{ float:right; width:40%; padding:15px 0; }
</style>

<?php
echo $this->Form->create();
echo "<div id=\"calcformleft\">";
echo "<h2>Box Pleated Valance Calculator</h2>";

echo $this->Form->input('face',['label'=>'Face','type'=>'number','min'=>0,'step'=>'any','value'=>0]);
echo $this->Form->input('height',['label'=>'Height','type'=>'number','min'=>0,'step'=>'any','value'=>0]);
echo $this->Form->input('return',['label'=>'Return','type'=>'number','min'=>0,'step'=>'any','value'=>0]);
echo $this->Form->input('pleats',['label'=>'Pleats','type'=>'number','min'=>0,'step'=>'any','value'=>0]);
echo $this->Form->input('qty',['label'=>'Quantity','type'=>'number','min'=>0,'step'=>'any','value'=>0]);
echo $this->Form->input('fab-width',['label'=>'Fab Width','type'=>'number','min'=>0,'step'=>'any','value'=>54]);

echo "<div class=\"input\">";
echo $this->Form->label('RailRoaded?');
echo $this->Form->select('railroaded',['Yes'=>'Yes','No'=>'No'],['value'=>'No']);
echo "</div>";

echo $this->Form->input('vert-repeat',['label'=>'Vert Repeat','type'=>'number','min'=>0,'step'=>'any','value'=>0]);
echo $this->Form->input('fabric-price-per-yard',['label'=>'Fabric Price per yd','type'=>'number','min'=>0,'step'=>'any','value'=>0]);
echo $this->Form->input('labor-cost-plf',['label'=>'Labor Cost p/LF','type'=>'number','min'=>0,'step'=>'any','value'=>'9.50']);
echo $this->Form->input('extra-inches-on-height',['label'=>'Extra Inches on Height','type'=>'number','min'=>0,'step'=>'any','value'=>8]);
echo $this->Form->input('wraparound-inches',['label'=>'Wraparound Inches','type'=>'number','min'=>0,'step'=>'any','value'=>2]);

echo "<div class=\"input\">";
echo $this->Form->label('Distributed Rounded Yards?');
echo $this->Form->select('distributed-rounded-yds',['Yes'=>'Yes','No'=>'No'],['value'=>'No']);
echo "</div>";

echo "<div class=\"input\">";
echo $this->Form->label('Rounded Yds End of Line?');
echo $this->Form->select('rounded-yds-end-of-line',['Yes'=>'Yes','No'=>'No'],['value'=>'No']);
echo "</div>";

echo "<div class=\"input\">";
echo $this->Form->label('Force Fabric Full Widths?');
echo $this->Form->select('force-fabric-full-widths',['Yes'=>'Yes','No'=>'No'],['value'=>'No']);
echo "</div>";

echo "<div class=\"input\">";
echo $this->Form->label('Force Lining Full Widths?');
echo $this->Form->select('force-lining-full-widths',['Yes'=>'Yes','No'=>'No'],['value'=>'No']);
echo "</div>";

echo "<div class=\"input\">";
echo $this->Form->label('Rounded Widths End of Line?');
echo $this->Form->select('rounded-widths-end-of-line',['Yes'=>'Yes','No'=>'No'],['value'=>'No']);
echo "</div>";

echo $this->Form->input('tolerance-on-widths',['label'=>'Tolerance on Widths','type'=>'number','min'=>0,'step'=>'any','value'=>'0.5']);
echo $this->Form->input('lining-price-per-yd',['label'=>'Lining Price per yd','type'=>'number','min'=>0,'step'=>'any','value'=>'5.50']);

echo $this->Form->input('lining-width',['label'=>'Lining Width','type'=>'number','min'=>0,'step'=>'any','value'=>54]);
echo $this->Form->input('inches-used-by-pleat',['label'=>'Inches used by Pleat','type'=>'number','min'=>0,'step'=>'any','value'=>12]);


echo "</div>";
?>

<div id="resultsblock">
<h2>Calculator Results</h2>

<?php
echo $this->Form->input('visible-width-dimension',['label'=>'Visible Width Dimension','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('pleat-usage',['label'=>'Pleat Usage','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('val-x',['label'=>'VAL_X','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('raw-widths-of-fabric',['label'=>'Raw Widths of Fabric','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('fabric-half-width-status',['label'=>'Fabric Half-Width Status','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('valance-fabric-widths',['label'=>'Valance Fabric Widths','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('total-fabric-widths',['type'=>'textarea','label'=>'Total Fabric Widths','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('raw-widths-of-lining',['label'=>'Raw Widths of Lining','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('lining-half-width-status',['label'=>'Lining Half-Width Status','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('valance-lining-widths',['label'=>'Valance Lining Widths','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('fabric-cl',['label'=>'Fabric CL','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('lining-cl',['label'=>'Lining CL','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('valances-out-of-a-single-cut-val-pc',['label'=>'Valances / single cut VAL_PC','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('fabric-yards',['label'=>'Fabric Yards','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('lining-yards',['label'=>'Lining Yards','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('fabric-cost',['label'=>'Fabric Cost','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('lining-cost',['label'=>'Lining Cost','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('labor-cost',['label'=>'Labor Cost','readonly'=>true,'disabled'=>true]);
echo $this->Form->input('price-per-valance',['label'=>'Price Per Valance','readonly'=>true,'disabled'=>true]);

?>
</div>
<div class="clear"></div>
<?php
echo "<div class=\"calculatebutton\">";
echo $this->Form->button('Calculate',['type'=>'button']);
echo "</div>";

echo "<div class=\"addaslineitembutton\">";
echo $this->Form->button('Add as Line Item',['type'=>'button']);
echo "</div>";
echo "<div class=\"clear\"></div>";

echo $this->Form->end();
?>
<script>


$(function(){
	$('.calculatebutton button').click(function(){
	
		var raw_valx = (parseFloat($('#face').val()) + parseFloat($('#return').val()) + parseFloat($('#return').val()) + parseFloat($('#wraparound-inches').val()));
		$('#visible-width-dimension').val(raw_valx);
		
		
		var plt_usg=(parseFloat($('#pleats').val()) * parseFloat($('#inches-used-by-pleat').val()));
		$('#pleat-usage').val(plt_usg);
		
		
		var val_x = (raw_valx + plt_usg);
		$('#val-x').val(val_x);
		
		
		if($('select[name=railroaded]').val() == "Yes"){
			var val_wrv = 1;
		}else{
			var val_wrv = ( val_x / parseFloat($('#fab-width').val()) );
		}
		$('#raw-widths-of-fabric').val(val_wrv.toFixed(2));
		
		
		if($('select[name=railroaded]').val() == "Yes"){
			var val_w = 1;	
		}else{
			var val_w = Math.ceil(val_wrv);
		}
		

		if(parseFloat($('#qty').val()) == 1 || $('select[name=railroaded]').val() == "Yes" || parseFloat($('#tolerance-on-widths').val()) == 1){
			var val_hw = 0;	
		}else if((val_w - val_wrv) >= parseFloat($('#tolerance-on-widths').val())){
			var val_hw = 1;
		}else{
			var val_hw = 0;		
		}
		
		
		if($('select[name=railroaded]').val() == "Yes"){
			var val_wrvl = 1;	
		}else{
			var val_wrvl = ( val_x / parseFloat($('#lining-width').val()) );			
		}
		$('#raw-widths-of-lining').val(val_wrvl.toFixed(2));
		
		
		if($('select[name=railroaded]').val() == "Yes"){
			var val_wl = 1;	
		}else{
			var val_wl = Math.ceil(val_wrvl);
		}
		
		
		if(parseFloat($('#qty').val()) == 1 || $('select[name=force-lining-full-widths]').val() == "Yes" || $('select[name=railroaded]').val() == "Yes"){
			var val_hwl = 0;
		}else if((val_wl - val_wrvl) >= parseFloat($('#tolerance-on-widths').val())){
			var val_hwl = 1;
		}else{
			var val_hwl = 0;
		}
		
		
		if($('select[name=railroaded]').val() == "Yes"){
			var val_wadjl = 1;	
		}else{
			var val_wadjl = (val_hwl == 1) ? (sigceiling(val_wrvl,0.5)) : val_wl;
		}
		
		
		
		if(val_hwl == 1){
			var val_wlin = val_wadjl;
		}else{
			var val_wlin = val_wl;
		}
		$('#valance-lining-widths').val(val_wlin);
		
		
		
		if($('select[name=railroaded]').val() == "Yes"){
			var val_wadj = 1;	
		}else if(val_hw == 0){
			var val_wadj = val_w;
		}else{
			var val_wadj = (sigceiling(val_wrv,0.5));
		}
		
		
		if(val_hw == 0){
			var val_wfab = val_w;
		}else{
			var val_wfab = val_wadj;
		}
		$('#valance-fabric-widths').val(val_wfab);
		

		
		if($('select[name=rounded-widths-end-of-line]').val() == "No"){
			var tot_weol = (val_wfab*parseFloat($('#qty').val()));	
		}else{
			var tot_weol = (Math.ceil(val_wfab*parseFloat($('#qty').val())));
		}
		
		
		if((tot_weol - parseInt(tot_weol)) > 0){
			var warn1 = " --> YOUR LINE TOTAL CONTAINS A HALF-WIDTH, PLEASE CONSIDER ROUNDING W EOL";
			var totalfabwidthsClass='redline';
		}else{
			var warn1 = " --> Your line contains only full widths. You seem to be OK";
			var totalfabwidthsClass='greenline';
		}
		$('#total-fabric-widths').removeClass('grayline').removeClass('greenline').removeClass('redline');
		$('#total-fabric-widths').val(tot_weol + " W Total " + warn1);
		$('#total-fabric-widths').addClass(totalfabwidthsClass);
		
		
		if($('select[name=force-lining-full-widths]').val() == "Yes"){
			var warn2 = "FABRIC HALF-WIDTHS HAVE BEEN DISABLED";
			var fabrichalfwidthClass='grayline';
		}else if(val_hw == 1){
			var warn2 = "Fabric Half-Widths are feasible";
			var fabrichalfwidthClass='greenline';
		}else{
			var warn2 = "FABRIC HALF-WIDTHS ARE NOT FEASIBLE";
			var fabrichalfwidthClass='redline';	
		}
		$('#fabric-half-width-status').removeClass('grayline').removeClass('greenline').removeClass('redline');
		$('#fabric-half-width-status').val(warn2);
		$('#fabric-half-width-status').addClass(fabrichalfwidthClass);
		
		
		
		if($('select[name=force-lining-full-widths]').val() == "Yes"){
			var warn3 = "LINING HALF-WIDTHS HAVE BEEN DISABLED";
			var feasibleClass='grayline';
		}else if(val_hwl == 1){
			var warn3 = "Lining Half-Widths are feasible";
			var feasibleClass='greenline';
		}else{
			var warn3 = "LINING HALF-WIDTHS ARE NOT FEASIBLE";
			var feasibleClass='redline';
		}
		$('#lining-half-width-status').removeClass('grayline').removeClass('greenline').removeClass('redline');
		$('#lining-half-width-status').val(warn3);
		$('#lining-half-width-status').addClass(feasibleClass);
		

		if($('select[name=railroaded]').val() == "Yes"){
			var fab_cl = (parseFloat($('#height').val()) + parseFloat($('#extra-inches-on-height').val()));
		}else if(parseFloat($('#vert-repeat').val()) == 0){
			var fab_cl = (parseFloat($('#height').val()) + parseFloat($('#extra-inches-on-height').val()));
		}else{
			var fab_cl = Math.ceil(((parseFloat($('#height').val()) + parseFloat($('#extra-inches-on-height').val())) / parseFloat($('#vert-repeat').val()))) * parseFloat($('#vert-repeat').val());
		}
		$('#fabric-cl').val(fab_cl);
		
		
		
		if($('select[name=railroaded]').val() == "No"){
			var val_pc = "N/A";	
		}else if(parseFloat($('#qty').val()) == 1){
			var val_pc = 1;
		}else{
			var val_pc = Math.floor(parseFloat($('#fab-width').val()) / fab_cl);
		}
		$('#valances-out-of-a-single-cut-val-pc').val(val_pc);


		var lin_cl = (parseFloat($('#height').val()) + parseFloat($('#extra-inches-on-height').val()));
		$('#lining-cl').val(lin_cl);
		
		
		if($('select[name=railroaded]').val() == "Yes"){
			var yds_pu = ((val_x / 36) * 1.05);
		}else{
			var yds_pu = ((((tot_weol / parseFloat($('#qty').val())) * fab_cl) / 36) * 1.05);
		}
		
		var dist_fabyds = ((Math.ceil(yds_pu * parseFloat($('#qty').val()) )) / parseFloat($('#qty').val()));
		
		
		if($('select[name=rounded-yds-end-of-line]').val() == "Yes"){
			var fab_yds = Math.ceil(yds_pu);
		}else if($('select[name=distributed-rounded-yds]').val() == "Yes"){
			var fab_yds = dist_fabyds;
		}else{
			var fab_yds = yds_pu;
		}
		
		var totfab_yds = (fab_yds * parseFloat($('#qty').val()));
		
		$('#fabric-yards').val(fab_yds.toFixed(2) + " yds ea, --> " + totfab_yds.toFixed(2) + " yds total");


		if($('select[name=railroaded]').val() == "Yes"){
			var lin_yds = ((val_x / 36) * 1.05);
		}else{
			var lin_yds = (((val_wlin * lin_cl) / 36) * 1.05);
		}
		$('#lining-yards').val(lin_yds.toFixed(2));


		var fab_cost = (fab_yds * parseFloat($('#fabric-price-per-yard').val()));
		
		var totfab_yds = (fab_yds * parseFloat($('#qty').val()));
		
		
		
		var totfab_cost = (fab_cost * parseFloat($('#qty').val()));
		//$('#fabric-cost').val(fab_yds.toFixed(2) + " yds ea, --> " + totfab_yds.toFixed(2) + " yds total");
		$('#fabric-cost').val(fab_cost.toFixed(2) + " yds ea, --> $" + totfab_cost.toFixed(2) + " total");
		
		
		
		var lin_cost = (parseFloat($('#lining-price-per-yd').val()) * lin_yds);
		$('#lining-cost').val(lin_cost.toFixed(2));
		
		
		var lbr_cost = ((Math.ceil(parseFloat($('#face').val()) / 12)) * parseFloat($('#labor-cost-plf').val()));
		$('#labor-cost').val(lbr_cost.toFixed(2));
		
		
		var tot_cost = (fab_cost + lin_cost + lbr_cost);
		$('#price-per-valance').val(tot_cost.toFixed(2));

		
		
	});
	
	$('#calcformleft input,#calcformleft select').change(function(){
		//if(parseFloat($('#qty').val()) >0){
			$('.calculatebutton button').trigger('click');
		//}
	});
});
</script>