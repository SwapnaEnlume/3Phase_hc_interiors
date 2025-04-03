<style>
#topsummary tfoot tr{ background:#000000; }
#topsummary tfoot tr th{ color:#FFFFFF; text-align:left; font-weight:bold; }
#topsummary tfoot tr td{ color:#FFFFFF; text-align:center; font-weight:bold; }

#bottomavailabletallies tr{ background:#000000; }
#bottomavailabletallies tr td{ color:#FFFFFF; text-align:center; font-weight:bold; }

.alertred{ background:#FF0000 !important; color:#FFFFFF !important; }

.col1{ width:12%; }
.col2{ width:12%; }
.combined1and2{ width:24%; }
.col3{ width:10%; text-align:center; }
.combined12and3{ width:34%; }
.col4{ width:10%; background:rgba(0,0,0,0.2); }
.col5{ width:10%; background:rgba(0,0,0,0.2); text-align:center; }
.col6{ width:10%; text-align:center; }
.col7{ width:12%; }
.col8{ width:12%; }
.col9{ width:12%; }

.combined78and9{ width:36%; }
.combined678and9{ width:46%; }


#wrapall{ width:1050px; margin:0 auto; }

#wrapall table{ margin-bottom:0; }

#wrapall table tr td,
#wrapall table tr th{ border-bottom:1px solid #000 !important; }

#wrapall table thead tr{ background:#26337A !important; }
#wrapall table tfoot tr th, #wrapall table tfoot tr td{ border:1px solid #000 !important; }
#wrapall table thead tr th{ color:#FFFFFF !important; text-align:center !important; font-weight:bold; font-size:12px; }
#wrapall table thead tr th{ border:1px solid #000 !important; }

#wrapall table tbody tr td.col7,
#wrapall table tbody tr td.col8,
#wrapall table tbody tr td.col9{ font-size:11px; }

#wrapall table tbody td{ font-size:11px; }


#wrapall h1, #wrapall h2{ font-size:20px; }
#breadcrumb{ padding:10px; margin:10px; background:#e8e8e8; border:1px solid #CCC; }
#fabricimage{ padding:15px; }
#fabricimage h1{ margin:0; }


#bottomavailabletallies tr th:nth-of-type(1),
#bottomavailabletallies tr th:nth-of-type(2),
#bottomavailabletallies tr th:nth-of-type(3),
#bottomavailabletallies tr th:nth-of-type(4),
#bottomavailabletallies tr th:nth-of-type(5),
#bottomavailabletallies tr th:nth-of-type(6){
	border-top:1px solid #000 !important;
	border-bottom:1px solid #000 !important;
	border-right:1px solid #444 !important;
}
#bottomavailabletallies tr th:nth-of-type(1){
	border-left:1px solid #000 !important;
}

#bottomavailabletallies tr th:nth-of-type(7){
	border-left:0px !important;
	border-right:1px solid #000 !important;
	border-top:1px solid #000 !important;
}



#onhandlist tfoot tr, #committmentslist tfoot tr{ background:#000 !important; }
#onhandlist tfoot tr th, #committmentslist tfoot tr th, #bottomavailabletallies tfoot tr th{ color:#FFF !important; font-weight:bold !important; }


#onhandlist tfoot tr th:nth-of-type(1),
#onhandlist tfoot tr th:nth-of-type(2),
#onhandlist tfoot tr th:nth-of-type(3),
#onhandlist tfoot tr th:nth-of-type(4),
#onhandlist tfoot tr th:nth-of-type(5),
#onhandlist tfoot tr th:nth-of-type(6){ border-right:1px solid #444 !important; }

#committmentslist tfoot tr th:nth-of-type(1),
#committmentslist tfoot tr th:nth-of-type(2),
#committmentslist tfoot tr th:nth-of-type(3),
#committmentslist tfoot tr th:nth-of-type(4),
#committmentslist tfoot tr th:nth-of-type(5),
#committmentslist tfoot tr th:nth-of-type(6){ border-right:1px solid #444 !important; }

#wrapall table tbody tr:nth-of-type(odd){ background:#ffffff; }
#wrapall table tbody tr:nth-of-type(even){ background:#e8e8e8; }
</style>

<div id="wrapall">
<div id="breadcrumb">
	<a href="/orders/materials/inventory">Inventory</a> &nbsp;&gt;&gt;&nbsp; 
	<a href="/orders/materials/inventory#<?php echo $fromtype; ?>"><?php 
	switch($fromtype){
	case "roster":
		echo "HCI Roster Fabrics";
	break;
	case "nonroster":
		echo "Non-Roster MOM Fabrics";
	break;
	case "com":
		echo "COM Fabrics";
	break;
	}
	?>
	</a> &nbsp;&gt;&gt;&nbsp; 
	<a href="/orders/materials/inventory#<?php echo $fromtype; ?>||<?php echo str_replace("'",'',$thisFabric['fabric_name']); ?>"><?php echo $thisFabric['fabric_name']; ?></a> &nbsp;&gt;&gt;&nbsp; <b><?php echo $thisFabric['color']; ?></b>
</div>
<div id="fabricimage">
	<h1 style="float:left;"><a href="/files/fabrics/<?php echo $thisFabric['id']; ?>/<?php echo $thisFabric['image_file']; ?>" target="_blank"><img src="/files/fabrics/<?php echo $thisFabric['id']; ?>/<?php echo $thisFabric['image_file']; ?>" width="50" height="50" style="display:inline-block; margin-right:10px;" /></a> <?php echo $thisFabric['fabric_name'].' '.$thisFabric['color']; ?></h1>
	<div style="float:right;font-size:11px;">Current as of <b><?php echo date('n/j/y g:i:sA'); ?></b></div>
	<div style="clear:both;"></div>
</div>


<table id="topsummary" width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000">
<thead>
	<tr>
		<th class="combined1and2" colspan="2" style="text-align:left !important;"><h2 style="color:#FFF;margin:0;">SUMMARY</h2></th>
		<th class="col3">UNQUILTED</th>
		<th class="col4">&nbsp;</th>
		<th class="col5">QUILTED</th>
		<th class="col6">TOTAL</th>
		<th class="combined78and9" colspan="3">&nbsp;</th>
	</tr>
</thead>
<tbody>
	<tr>
		<th class="combined1and2" colspan="2">ON HAND</th>
		<td class="col3"><?php echo $unquiltedYardsOnHand; ?></td>
		<td class="col4">&nbsp;</td>
		<td class="col5"><?php echo $quiltedYardsOnHand; ?></td>
		<td class="col6"><?php echo ($quiltedYardsOnHand + $unquiltedYardsOnHand); ?></td>
		<td class="combined78and9" colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<th class="combined1and2" colspan="2">COMMITTED</th>
		<td class="col3"><?php
		$unquiltedCommittedTotal=0;
		foreach($orderYardsUnquilted as $order => $total){
			$unquiltedCommittedTotal = ($unquiltedCommittedTotal + $total);
		}
		 
		echo $unquiltedCommittedTotal;
		?></td>
		<td class="col4">&nbsp;</td>
		<td class="col5"><?php
		$quiltedCommittedTotal=0;
		foreach($orderYardsQuilted as $order => $total){
			$quiltedCommittedTotal = ($quiltedCommittedTotal + $total);
		}
		 
		echo $quiltedCommittedTotal;
		?></td>
		<td class="col6"><?php echo ($unquiltedCommittedTotal + $quiltedCommittedTotal); ?></td>
		<td class="combined78and9" colspan="3">&nbsp;</td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th class="combined1and2" colspan="2">AVAILABLE</th>
		<td class="col3<?php
		if((($quiltedYardsOnHand + $unquiltedYardsOnHand) - ($unquiltedCommittedTotal + $quiltedCommittedTotal)) < 0){
			echo " alertred";
		}
		?>"><?php echo (($quiltedYardsOnHand + $unquiltedYardsOnHand) - ($unquiltedCommittedTotal + $quiltedCommittedTotal)); ?></td>
		<td class="col4">&nbsp;</td>
		<td class="col5<?php
		if(($quiltedYardsOnHand - $quiltedCommittedTotal) < 0){
			echo " alertred";
		}
		?>"><?php echo ($quiltedYardsOnHand - $quiltedCommittedTotal); ?></td>
		<td class="combined678and9" colspan="4">&nbsp;</td>
	</tr>
</tfoot>
</table>


<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000">
<tr>
	<td class="combined12and3" colspan="3" style="border-bottom:8px solid #EF9836 !important; border-left:0 !important; font-size:6px;line-height: 6px;"><span style="font-size:6px;">&nbsp;</span></td>
	<td class="col4" style="border-bottom:8px solid #EF9836 !important; font-size:6px;line-height: 6px;"><span style="font-size:6px;">&nbsp;</span></td>
	<td class="col5" style="border-bottom:8px solid #EF9836 !important; font-size:6px;line-height: 6px;"><span style="font-size:6px;">&nbsp;</span></td>
	<td class="combined678and9" colspan="4" style="border-bottom:8px solid #EF9836 !important; font-size:6px;line-height: 6px;"><span style="font-size:6px;">&nbsp;</span></td>
</tr>
<tr style="background:#FFFFFF !important;">
<td class="combined12and3" colspan="3" style="border-left:0 !important;"><h1 style="margin:0;">ON HAND</h1></td>
<td class="col4">&nbsp;</td>
<td class="col5">&nbsp;</td>
<td class="combined678and9" colspan="4">&nbsp;</td>
</tr>
</table>

<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" id="onhandlist">
<thead>
	<tr>
		<th class="col1">ROLL #</th>
		<th class="col2">LOCATION</th>
		<th class="col3">UNQUILTED</th>
		<th class="col4">&nbsp;</th>
		<th class="col5">QUILTED</th>
		<th class="col6">RECEIVED</th>
		<th class="col7">WO #</th>
		<th class="col8">NOTES</th>
		<th class="col9">BACKING / QP</th>
	</tr>
</thead>
<tbody>
<?php
$totalUnquilted=0;
$totalQuilted=0;

foreach($fabricRolls as $rollID => $roll){
	echo "<tr>
	<td class=\"col1\">".$roll['roll_number']." <a href=\"/products/getfabricrolltag/".$rollID.".pdf\" target=\"_blank\"><img src=\"/img/printlabel.png\" width=\"15\" height=\"15\" /></a></td>
	<td class=\"col2\">".$roll['location']."</td>
	<td class=\"col3\">".$roll['unquilted_yards']."</td>
	<td class=\"col4\">&nbsp;</td>
	<td class=\"col5\">".$roll['quilted_yards']."</td>
	<td class=\"col6\">".$roll['date_received']."</td>
	<td class=\"col7\">";
	if(intval($roll['quote_id']) >0){
		echo "<a href=\"/orders/editlines/".$roll['quote_id']."?highlightFabric=".$roll['fabric_id']."\" target=\"_blank\">".$roll['work_order']."</a>";
	}else{
		echo $roll['work_order'];
	}
	echo "</td>
	<td class=\"col8\">".$roll['notes']."</td>
	<td class=\"col9\">";
	if(strlen(trim($roll['quilting_pattern'])) >0){
		echo $roll['backing_material']." / ".$roll['quilting_pattern'];
	}elseif(strlen(trim($roll['backing_material'])) >0){
		echo $roll['backing_material'];
	}
	echo "</td>
	</tr>";
	$totalUnquilted=($totalUnquilted+$roll['unquilted_yards']);
	$totalQuilted=($totalQuilted+$roll['quilted_yards']);
}
?>
</tbody>
<tfoot>
	<tr>
		<th class="combined1and2" colspan="2">&nbsp;</th>
		<th class="col3"><?php
		echo $totalUnquilted;
		?></th>
		<th class="col4">&nbsp;</th>
		<th class="col5"><?php
		echo $totalQuilted;
		?></th>
		<th class="combined678and9" colspan="4">&nbsp;</th>
	</tr>
</tfoot>
</table>

<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000">
<tr>
<td class="combined12and3" colspan="3" style="border-left:0 !important;"><h1 style="margin:0;"><br>COMMITTED</h1></td>
<td class="col4">&nbsp;</td>
<td class="col5">&nbsp;</td>
<td class="combined678and9" colspan="4">&nbsp;</td>
</tr>
</table>

<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" id="committmentslist">
<thead>
	<tr>
		<th class="col1">&nbsp;</th>
		<th class="col2">WO#</th>
		<th class="col3">UNQUILTED</th>
		<th class="col4">WO#</th>
		<th class="col5">QUILTED</th>
		<th class="combined678and9" colspan="4">&nbsp;</th>
	</tr>
</thead>
<tbody>
	<?php
	$totalCommittedUnquilted=0;
	foreach($orderYardsUnquilted as $order => $yards){
		echo "<tr>
			<td class=\"col1\">&nbsp;</td>
			<td class=\"col2\"><a href=\"/products/gotowo/".$order."/".$thisFabric['id']."\" target=\"_blank\">".$order."</a></td>
			<td class=\"col3\">".$yards."</td>
			<td class=\"col4\">&nbsp;</td>
			<td class=\"col5\">&nbsp;</td>
			<td class=\"combined678and9\" colspan=\"4\">&nbsp;</td>
		</tr>";
		$totalCommittedUnquilted=($totalCommittedUnquilted + $yards);
	}


	$totalCommittedQuilted=0;
	foreach($orderYardsQuilted as $order => $yards){
		echo "<tr>
			<td class=\"col1\">&nbsp;</td>
			<td class=\"col2\">&nbsp;</td>
			<td class=\"col3\">&nbsp;</td>
			<td class=\"col4\"><a href=\"/products/gotowo/".$order."/".$thisFabric['id']."\" target=\"_blank\">".$order."</a></td>
			<td class=\"col5\">".$yards."</td>
			<td class=\"combined678and9\" colspan=\"4\">&nbsp;</td>
		</tr>";
		$totalCommittedQuilted=($totalCommittedQuilted+$yards);
	}
	?>
</tbody>
<tfoot>
	<tr>
		<th class="combined1and2" colspan="2">&nbsp;</th>
		<th class="col3"><?php echo $totalCommittedUnquilted; ?></th>
		<th class="col4">&nbsp;</th>
		<th class="col5"><?php echo $totalCommittedQuilted; ?></th>
		<th class="combined678and9" colspan="4">&nbsp;</th>
	</tr>
</tfoot>
</table>


<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000">
<tr>
<td class="combined12and3" colspan="3" style="border-left:0 !important;"><h1 style="margin:0;"><br>AVAILABLE</h1></td>
<td class="col4">&nbsp;</td>
<td class="col5">&nbsp;</td>
<td class="combined678and9" colspan="4">&nbsp;</td>
</tr>
</table>
<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000" id="bottomavailabletallies">
<tfoot>
<tr>
<th class="combined1and2" colspan="2">&nbsp;</th>
<th class="col3<?php
if((($quiltedYardsOnHand + $unquiltedYardsOnHand) - ($unquiltedCommittedTotal + $quiltedCommittedTotal)) < 0){
	echo " alertred";
}
?>"><?php echo (($quiltedYardsOnHand + $unquiltedYardsOnHand) - ($unquiltedCommittedTotal + $quiltedCommittedTotal)); ?></th>
<th class="col4">&nbsp;</th>
<th class="col5<?php
	if(($quiltedYardsOnHand - $quiltedCommittedTotal) < 0){
		echo " alertred";
	}
?>"><?php echo ($quiltedYardsOnHand - $quiltedCommittedTotal); ?></th>
<th class="combined678and9" colspan="4">&nbsp;</th>
</tr>
</tfoot>
</table>
<Br><Br><Br><Br><Br>
</div>
<script>setTimeout('window.location.reload(true)',30000);</script>