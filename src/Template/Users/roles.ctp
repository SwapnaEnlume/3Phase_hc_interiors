<!-- src/Template/Users/roles.ctp -->

<div class="admin userroles">
<?= $this->Flash->render('auth') ?>
<h1>User Roles &amp; Permissions</h1>

<section id="controls">
	<a href="/users/roles/add/">+ Add New Role</a>
</section>

<div class="clear"></div>

<section id="dataTable">
	<table width="100%" cellpadding="5" cellspacing="0" border="1" id="usersList" style="border-collapse:collapse;">
                <thead>
                    <tr>
                    	<th nowrap>Role Name</th>
						<th nowrap># Users</th>
						<th nowrap>Actions</th>
                    </tr>
                </thead>
                <tbody>
                	
                </tbody>
            </table>

</section>
<style>
div.FixedHeader_Cloned th,
div.FixedHeader_Cloned td {
	background-color: white !important;
}
table, table tr{
	border: none;
}
.dataTable tbody tr.odd{ background-color:#EBF2FC; }
.dataTable tbody tr.even{ background-color:#F4F6F9; }
.dataTable tbody tr:hover{ background-color:#BBF5B4; }

tr.highlight{ background:#ffa155 !important; }

.dataTable tbody tr td{ vertical-align:top; }

.dataTable thead tr th:nth-child(1),
.dataTable tfoot tr th:nth-child(1),
.dataTable tbody tr td:nth-child(1){
	width:170px !important;
}


#controls a{ display:inline-block; padding:10px 20px; background:#26337A; color:#FFF; font-weight:bold; float:right; }
</style>


<script>
			$(function(){
				var cachebuster=Math.floor(Date.now() / 1000);
				$('#usersList').dataTable({
						"lengthMenu": [[100, 250, 1000000000], [100,250, "All"]],
						"processing":true,
						"serverSide":true,
						"sServerMethod":"POST",
						"ajax":{"url":"/users/getuserroles.json"},
						"dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						stateSave: true,
						searchHighlight: true,
						fixedHeader: true,
						"columns": [
							{ "orderable": true },
							{ "orderable": false },
							{ "orderable": false }
						  ]
				});
				
		
			});
			

			</script> 
</div>