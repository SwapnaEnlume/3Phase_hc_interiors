<!-- src/Template/Orders/materials.ctp -->
<h1 class="pageheading">Materials Management</h1>

<div id="materialstabs">
	<ul style="margin:0 0 25px 0; padding:0;">
		<li<?php if($thisAction=='overview'){ echo " class=\"current\""; } ?> style="margin-left:20px;"><a href="/orders/materials/overview">Committed / Quoted Overview</a></li>
		<li<?php if($thisAction=='purchases'){ echo " class=\"current\""; } ?>><a href="/orders/materials/purchases">Purchases</a></li>
		<li<?php if($thisAction=='flagged'){ echo " class=\"current\""; } ?>><a href="/orders/materials/flagged">Flagged</a></li>
		<li<?php if($thisAction=='inventory'){ echo " class=\"current\""; } ?>><a href="/orders/materials/inventory">Inventory</a></li>
	</ul>

<?php
switch($thisAction){
	case "inventory":
	?>
	<div id="inventory">
		<button type="button" id="addinventory" onclick="location.href='/products/addinventory/';">+ Add Inventory</button>
		<button type="button" id="lookuproll" onclick="location.href='/products/lookuproll/';">Lookup Roll</button>
		<div style="clear:both;"></div>

		<div style="float:left; width:75%;" id="inventorysteps">
			<div>
				<div style="float:left; width:30%; background:#BBB;" id="inventorypatterntypes">
					<ul>
						<li id="rosterfabricsparent"><a href="javascript:loadPatternPanels('rosterfabrics');">HCI Roster Fabrics</a></li>
						<li id="nonrosterfabricsparent"><a href="javascript:loadPatternPanels('nonrosterfabrics');">Non-Roster MOM Fabrics</a></li>
						<li id="comfabricsparent"><a href="javascript:loadPatternPanels('comfabrics');">COM Fabrics</a></li>
					</ul>
				</div>
				<div style="float:left; width:30%; display:none;" id="rosterfabrics"><ul>
				<?php 
				$usedrosterfabs=array();
				foreach($allfabrics as $fabricid => $fabricRow){
					if($fabricRow['is_hci_fabric'] == '1' && !in_array($fabricRow['fabric_name'],$usedrosterfabs)){
						echo "<li id=\"fab".$fabricRow['id']."\"><a href=\"javascript:loadInventoryPattern('roster','".str_replace("'",'',$fabricRow['fabric_name'])."','fab".$fabricRow['id']."');\">".str_replace("'",'',$fabricRow['fabric_name'])."</a></li>\n";
						$usedrosterfabs[]=$fabricRow['fabric_name'];
					}
				}
				?></ul>
				</div>
				<div style="float:left; width:30%; display:none;" id="nonrosterfabrics"><ul>
				<?php 
				$usednonrosterfabs=array();
				foreach($allfabrics as $fabricid => $fabricRow){
					if($fabricRow['is_hci_fabric'] == '0' && $fabricRow['com_fabric'] == '0' && !in_array($fabricRow['fabric_name'],$usednonrosterfabs)){
						echo "<li id=\"fab".$fabricRow['id']."\"><a href=\"javascript:loadInventoryPattern('nonroster','".str_replace("'",'',$fabricRow['fabric_name'])."','fab".$fabricRow['id']."');\">".str_replace("'",'',$fabricRow['fabric_name'])."</a></li>\n";
						$usednonrosterfabs[]=$fabricRow['fabric_name'];
					}
				}
				?>
				</ul>
				</div>
				<div style="float:left; width:30%; display:none;" id="comfabrics"><ul>
				<?php 
				$usedcoms=array();
				foreach($allfabrics as $fabricid => $fabricRow){
					if($fabricRow['com_fabric'] == '1' && !in_array($fabricRow['fabric_name'],$usedcoms)){
						echo "<li id=\"fab".$fabricRow['id']."\"><a href=\"javascript:loadInventoryPattern('com','".str_replace("'",'',$fabricRow['fabric_name'])."','fab".$fabricRow['id']."');\">".str_replace("'",'',$fabricRow['fabric_name'])."</a></li>\n";
						$usedcoms[]=$fabricRow['fabric_name'];
					}
				}
				?></ul>
				</div>

				<div style="float:right; width:40%; display:none;" id="selectedfabricscolorspane">
					No fabric selected!
				</div>

				<div style="clear:both;"></div>
				<script>
					function loadPatternPanels(patterntype){
						$('#rosterfabrics,#nonrosterfabrics,#comfabrics').hide();
						$('#rosterfabricsparent,#nonrosterfabricsparent,#comfabricsparent').removeClass('current');
						$('#selectedfabricscolorspane').html('');
						$('#'+patterntype+'parent').addClass('current');
						$('#'+patterntype).show();
						window.location.hash='#'+patterntype.replace('fabrics','');
					}

					var allfabrics;

					function loadInventoryPattern(patterntype,fabricname,fabpatternrowid){
						//build and load this fabric's colors list

						$('#rosterfabrics ul li,#nonrosterfabrics ul li,#comfabrics ul li').removeClass('current');
						$('li#'+fabpatternrowid).addClass('current');


						<?php
						$fabsjson=array();
						foreach($allfabrics as $fabrow){
							if($fabrow['is_hci_fabric'] == '1'){
								$ownership='roster';
							}elseif($fabrow['com_fabric'] == '1'){
								$ownership='com';
							}elseif($fabrow['com_fabric'] == '0' && $fabrow['is_hci_fabric'] == '0'){
								$ownership='nonroster';
							}
							$fabsjson[$fabrow['id']]=array('fabric_name' => str_replace("'",'',$fabrow['fabric_name']), 'image_file' => $fabrow['image_file'], 'color' => $fabrow['color'], 'ownership' => $ownership, 'rollcount' => $fabrow['rollcount']);
						}
						echo "allfabrics=".json_encode($fabsjson).";";
						?>
						var newout='<ul>';
						$.each(allfabrics,function(fabid,fabdata){
							if(fabdata.ownership==patterntype && fabdata.fabric_name==fabricname){
								newout += '<li><a href="javascript:selectfabricandcolorfinal(\''+patterntype+'\',\''+fabid+'\');">'+fabdata.color+' ('+fabdata.rollcount+' rolls)</a></li>';
							}
						});
						newout +='</ul>';

						$('#selectedfabricscolorspane').html(newout);

						var $list = $('#selectedfabricscolorspane ul');

  						$list.children().detach().sort(function(a, b) {
    						return $(a).text().localeCompare($(b).text());
  						}).appendTo($list);


						$('#selectedfabricscolorspane').show();
						window.location.hash='#'+patterntype.replace('fabrics','')+'||'+fabricname;
					}

					function selectfabricandcolorfinal(patterntype,fabricID){						
						
						window.location.href='/products/fabricinventory/'+fabricID+'/'+patterntype;

					}
				</script>

			</div>
		</div>

		
	</div>

<script>
$(function(){

	

	//detect if we are inside a type or fabric
	var hash = window.location.hash.replace('#','');
	var hashsplit=hash.split('||');
	if(hashsplit.length == 1){
		//type selection, no fabric selection
		loadPatternPanels(hashsplit[0]+'fabrics');
	    
	}else if(hashsplit.length == 2){
		//type and fabric selection
		loadPatternPanels(hashsplit[0]+'fabrics');
		var findrowid=$('#'+hashsplit[0]+'fabrics').find("li a:contains('"+hashsplit[1]+"')").parent().attr('id');
		loadInventoryPattern(hashsplit[0],hashsplit[1],findrowid);
	}
	
});
</script>


	<?php
	break;
	case "overview":
	/*?>
	<div id="overview">

		<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000" id="listtable">
			<thead>
				<tr>
					<th class="fabricname" rowspan="2" width="12%">Fabric + Color</th>
					<th class="committedoverall" colspan="4" width="58%">COMMITTED</th>
					<th class="quotedoverall" width="10%">QUOTED</th>
					<th class="unquiltedyardsonhand" rowspan="2" width="7%">Unquilted<br>On-Hand</th>
					<th class="quiltedyardsonhand" rowspan="2" width="7%">Quilted<br>On-Hand</th>
					<th class="yardsonorder" rowspan="2" width="7%">On-Order</th>
					<th class="actions" rowspan="2" width="6%">Actions</th>
				</tr>
				<tr>
					<th class="committedmom" width="6%">MOM</th>
					<th class="committedmomyards" width="6%">Yds</th>
					<th class="committedmomwo" width="6%">WO</th>
					<th class="committedmompo" width="11%">PO</th>
					<th class="quotedmom" width="4%">MOM</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($allfabrics as $fabric){
					$usedWOs=array();
					$usedPOs=array();
					$totalyardsthisfabric=0;

					foreach($requiredMaterialsMOM as $fabricid => $data){
						if($fabricid==$fabric['fabric_id']){
							$totalyardsthisfabric=($totalyardsthisfabric+$data['yards']);
						}
					}


					foreach($requiredMaterialsCOM as $fabricid => $data){
						if($fabricid==$fabric['fabric_id']){
							$totalyardsthisfabric=($totalyardsthisfabric+$data['yards']);
						}
					}


					foreach($maybeMaterialsMOM as $fabricid => $data){
						if($fabricid==$fabric['fabric_id']){
							$totalyardsthisfabric=($totalyardsthisfabric+$data['yards']);
						}
					}


					foreach($maybeMaterialsCOM as $fabricid => $data){
						if($fabricid==$fabric['id']){
							$totalyardsthisfabric=($totalyardsthisfabric+$data['yards']);
						}
					}


					if($totalyardsthisfabric > 0){

						echo "<tr>
						<td class=\"fabricname\" valign=\"top\" width=\"12%\"><img src=\"/files/fabrics/".$fabric['id']."/".$fabric['image_file']."\" width=\"75\" /><br><b>".ucfirst($fabric['fabric_name'])."</b><br><em>".ucfirst($fabric['color'])."</em></td>
						<td class=\"committedmom\" valign=\"top\" width=\"6%\">";
						foreach($requiredMaterialsMOM as $fabricid => $data){
							if($fabricid==$fabric['fabric_id']){
								echo $data['yards'];
							}
						}
						echo "</td>

						<td class=\"committedmomwo\" colspan=\"3\" valign=\"top\" width=\"23%\"><table class=\"committedtable\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\">";
						foreach($requiredMaterialsMOM as $fabricid => $data){
							$usedWOsThisMOMFabric=array();

							if($fabricid==$fabric['fabric_id']){
								foreach($data['yardages'] as $num => $linedata){
									if(!in_array($linedata['order_number'],$usedWOsThisMOMFabric)){
										echo "<tr><td valign=\"top\">";
										$thisOrderThisFabricTotal=0;
										foreach($data['yardages'] as $linenum => $linesubdata){
											if($linesubdata['order_id'] == $linedata['order_id']){
												$thisOrderThisFabricTotal=($thisOrderThisFabricTotal + $linesubdata['yards']);
											}
										}
										echo $thisOrderThisFabricTotal;
										echo "</td><td valign=\"top\"><a href=\"/orders/editlines/".$linedata['order_id']."?highlightFabric=".$fabric['id']."\" target=\"_blank\" title=\"View Order Details with Highlighting\">".$linedata['order_number']."</a></td><td valign=\"top\">".$linedata['po_number']."</td></tr>";
										$usedWOsThisMOMFabric[]=$linedata['order_number'];
									}
								}
							}
						}
						echo "</table></td>";


						echo "<td class=\"yardsquotesmom\" width=\"5%\">";
						foreach($maybeMaterialsMOM as $fabricid => $data){
							if($fabricid==$fabric['fabric_id']){
								echo $data['yards'];
							}
						}
						echo "</td>
						<td class=\"unquiltedyardsonhand\" valign=\"top\" width=\"7%\">";
						foreach($maybeMaterialsMOM as $fabricid => $data){
							if($fabricid == $fabric['fabric_id']){
								echo $data['available'];
							}
						}
						echo "</td>
						<td class=\"quiltedyardsonhand\" valign=\"top\" width=\"7%\">";
						foreach($maybeMaterialsMOM as $fabricid => $data){
							if($fabricid == $fabric['fabric_id']){
								echo $data['available'];
							}
						}
						echo "</td>
						<td class=\"yardsonorder\" valign=\"top\" width=\"7%\">";
						
						$totalYardsOnOrderThisFabric=0;
						foreach($fabricOrders as $fabricorder){
							if($fabricorder['material_id'] == $fabric['fabric_id'] && $fabricorder['actual_arrival'] == 0){
								$totalYardsOnOrderThisFabric=($totalYardsOnOrderThisFabric + $fabricorder['amount_ordered']);
							}
						}
						
						echo $totalYardsOnOrderThisFabric;
						
						echo "</td>
						<td class=\"actions\" valign=\"top\" width=\"6%\"><a href=\"/orders/ordermaterial/".$fabric['fabric_id']."/Fabrics/\" target=\"_blank\"><img src=\"/img/poicon.png\" alt=\"Order This Material\" title=\"Order This Material\" width=\"16\" height=\"16\" /></a> <a href=\"/products/fabricinventory/".$fabric['fabric_id']."/overview\" target=\"_blank\"><img src=\"/img/inventoryicon.png\" alt=\"View Fabric Inventory\" title=\"View Fabric Inventory\" /></a></td>
						</tr>";
					}
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
	*/
	    echo "<pre>";
	    print_r($allfabrics);
	    echo "</pre>";
	    
	break;
	case "purchases":
?>
	<div id="purchases">
		<table width="100%" id="purchasetable" cellpadding="5" cellspacing="0" border="1" bordercolor="#000000">
			<thead>
				<tr>
					<th rowspan="2">HCI PO#</th>
					<th colspan="3">Fabric</th>
					<th rowspan="2">Date Ordered</th>
					<th rowspan="2">Status</th>
					<th rowspan="2">Yards</th>
					<th rowspan="2">Ship Date</th>
					<th colspan="2">Requirements Met</th>
					<th rowspan="2">Work Orders</th>
				</tr>
				<tr>
					<th>Name</th>
					<th>Color</th>
					<th>Vendor</th>
					<th>Purchase</th>
					<th>Receive</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($fabricOrders as $fabricOrder){
					echo "<tr class=\"";
					echo "\">
					<td>".$fabricOrder['qb_po_number']."</td>
					<td>";
					foreach($allfabrics as $fabric){
						if($fabric['id'] == $fabricOrder['material_id'] && $fabricOrder['material_type']=='Fabrics'){
							echo $fabric['fabric_name']."</td><td>".$fabric['color']."</td><td>";

							foreach($allVendors as $vendor){
								if($vendor['id'] == $fabric['vendors_id']){
									echo $vendor['vendor_name'];
								}
							}
						}
					}
					echo "</td>
					<td>".date("n/j/Y",$fabricOrder['created'])."</td>
					<td>".ucfirst($fabricOrder['status'])."</td>
					<td>".$fabricOrder['amount_ordered']."</td>
					<td>".date("n/j/Y",$fabricOrder['expected_arrival'])."</td>
					<td";
					if($fabricOrder['requirements_met'] == '1'){
						echo " class=\"good\"><img src=\"/img/Ok-icon.png\" width=\"18\" />";
					}else{
						echo " class=\"alert\"><img src=\"/img/delete.png\" width=\"18\" />";
					}
					echo "</td><td";
					if($fabricOrder['receive_requirement_met'] == '1'){
						echo " class=\"good\"><img src=\"/img/Ok-icon.png\" width=\"18\" />";
					}else{
						echo " class=\"alert\"><img src=\"/img/delete.png\" width=\"18\" />";
					}
					echo "</td>
					<td>";
					$wo=json_decode($fabricOrder['orders_affected'],true);
					echo "<ul>";
					foreach($wo as $worow){
						echo "<li>";
						echo "<a href=\"/orders/editlines/".$worow."?scrolltobom=1\" target=\"_blank\">".$wonums[$worow]."</a>";
						echo "</li>\n";
					}
					echo "</ul>";
					echo "</td>
					</tr>";
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
	break;
	case "flagged":
	?>

<div id="flagged">
</div>
<?php break;
}
?>

</div>



<script>

function loadmatordermodal(fabricid){
	$.fancybox({
		'type':'iframe',
		'href': '/orders/ordermaterial/'+fabricid+'/Fabrics/',
		'width':500,
		'height':700,
		'autoSize':false,
		'modal':true,
		helpers: {
			overlay: {
			  locked: false
			}
		  }
	});
}
</script>
