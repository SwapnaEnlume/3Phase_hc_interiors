<!-- src/Template/Users/permissions.ctp -->

<div class="admin userroles permissions">
<?= $this->Flash->render('auth') ?>
<h2 style="display:inline-block;">Permissions for <u><?php echo $thisRole['user_type_name']; ?></u></h2>

<button type="button" onclick="location.href='/users/xlspermissions/<?php echo $thisRole['id']; ?>';" style="margin-top:15px; padding:4px 4px 4px 4px !important; float:right;">Excel</button>



<div class="clear"></div>

	<?php
	echo $this->Form->create(false);
	
	echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">";
	echo "<thead>
		<tr>
			<th>Controller</th>
			<th>Action</th>
			<th>Description</th>
			<th>Grant Permission?</th>
		</tr>
	</thead>";
	echo "<tbody>";
	
	foreach($allPermissions as $permission){
		echo "<tr>
		<td>".$permission['controller']."</td>
		<td>".$permission['action']."</td>
		<td>".$permission['description']."</td>
		<td class=\"permval\">";
		
		$atts=array();
		$thisRolePerms=json_decode($thisRole['permissions'],true);
		if(isset($thisRolePerms[$permission['slug']]) && $thisRolePerms[$permission['slug']] == '1'){
			$atts['value']='1';
		}elseif(isset($thisRolePerms[$permission['slug']]) && $thisRolePerms[$permission['slug']] == '0'){
			$atts['value']='0';
		}else{
		    $atts['value']='unknown';
		}
		
		$atts['required']=true;
		
		echo $this->Form->radio('permission_'.$permission['id'],['1' => 'YES', '0' => 'NO', 'unknown' => 'NOT SET'],$atts);
		echo "</td>
		</tr>";
	}
	
	echo "</tbody>
	</table>";
	
	echo "<br><br>";
	echo $this->Form->submit('Save Permissions');
	echo "<br><Br><Br>";
	
	echo $this->Form->end();
	
	?>
	
</div>
<style>
div.admin.userroles.permissions{ max-width:950px; margin:0 auto; }
div.admin.userroles.permissions table tbody tr:nth-of-type(even){
	background:#e8e8e8;
}
div.admin.userroles.permissions table tbody tr:nth-of-type(odd){
	background:#f8f8f8;
}

div.admin.userroles.permissions h2{ text-align: center; }	

table td.permval label{ display:inline-block; margin-right:15px; }
table thead tr{ background:#444444; }
table thead tr th{ color:#FFF; }
</style>