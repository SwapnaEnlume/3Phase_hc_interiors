<!-- src/Template/Users/index.ctp -->

<div class="admin users">
<?= $this->Flash->render('auth') ?>
<h1>Users</h1>

<section id="controls">
	<a href="/users/add/">+ Add User</a>
</section>

<div class="clear"></div>

<section id="dataTable">
	<table width="100%" cellpadding="5" cellspacing="0" border="1" id="usersList" style="border-collapse:collapse;">
                <thead>
                    <tr>
                    	<th nowrap>Name</th>
						<th nowrap>Email</th>
						<th nowrap>Role</th>
						<th nowrap>Status</th>
						<th nowrap>Joined</th>
						<th nowrap>Actions</th>
						
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    	<th nowrap>Name</th>
						<th nowrap>Email</th>
						<th nowrap>Role</th>
						<th nowrap>Status</th>
						<th nowrap>Joined</th>
						<th nowrap>Actions</th>
                    </tr>
                </tfoot>
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

.dataTable tbody tr td:nth-child(1) a{ background:#000; color:#FFF; border:1px solid #ccc; display:inline-block; padding:3px; margin:3px; }


.dataTable thead tr th:nth-child(2),
.dataTable tfoot tr th:nth-child(2),
.dataTable tbody tr td:nth-child(2){
	width:180px !important;
}

.dataTable thead tr th:nth-child(4),
.dataTable tfoot tr th:nth-child(4),
.dataTable tbody tr td:nth-child(4){
	width:70px !important;
}


.dataTable thead tr th:nth-child(3),
.dataTable tfoot tr th:nth-child(3),
.dataTable tbody tr td:nth-child(3){
	width:100px !important;
}

.dataTable thead tr th:nth-child(5),
.dataTable tfoot tr th:nth-child(5),
.dataTable tbody tr td:nth-child(5){
	width:180px !important;
}

.dataTable thead tr th:nth-child(6),
.dataTable tfoot tr th:nth-child(6),
.dataTable tbody tr td:nth-child(6){
	width:140px !important;
}

.dataTable thead tr th:nth-child(7),
.dataTable tfoot tr th:nth-child(7),
.dataTable tbody tr td:nth-child(7){
	width:100px !important;
}

.dataTable thead tr th:nth-child(8),
.dataTable tfoot tr th:nth-child(8),
.dataTable tbody tr td:nth-child(8){
	width:100px !important;
}

.dataTable thead tr th:nth-child(9),
.dataTable tfoot tr th:nth-child(9),
.dataTable tbody tr td:nth-child(9){
	width:170px !important;
}

.dataTable thead tr th:nth-child(10),
.dataTable tfoot tr th:nth-child(10),
.dataTable tbody tr td:nth-child(10){
	width:110px !important;
}

.dataTable thead tr th:nth-child(11),
.dataTable tfoot tr th:nth-child(11),
.dataTable tbody tr td:nth-child(11){
	width:120px !important;
}

.dataTable thead tr th:nth-child(12),
.dataTable tfoot tr th:nth-child(12),
.dataTable tbody tr td:nth-child(12){
	width:180px !important;
}

.dataTable thead tr th:nth-child(13),
.dataTable tfoot tr th:nth-child(13),
.dataTable tbody tr td:nth-child(13){
	width:220px !important;
}

.dataTable thead tr th:nth-child(14),
.dataTable tfoot tr th:nth-child(14),
.dataTable tbody tr td:nth-child(14){
	width:240px !important;
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
						"ajax":{"url":"/users/getusers.json"},
						"dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						stateSave: true,
						searchHighlight: true,
						fixedHeader: true,
						"columns": [
							{ "orderable": true },
							{ "orderable": true },
							{ "orderable": false },
							{ "orderable": false },
							{ "orderable": true },
							{ "orderable": false }
						  ]
				});
				
				
				$('table.dataTable tbody').on( 'click', 'tr', function () {
			        if ( $(this).hasClass('highlight') ) {
            			$(this).removeClass('highlight');
						$(this).find('td center input.doHighlight').removeAttr('checked');
        			}else {
			            //table.$('tr.highlight').removeClass('highlight');
            			$(this).addClass('highlight');
						$(this).find('td center input.doHighlight').attr('checked','checked');
        			}
    			} );
		
			});
			

			</script> 
</div>