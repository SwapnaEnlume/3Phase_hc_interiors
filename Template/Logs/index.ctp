<!-- File: src/Template/logs/activitylog.ctp -->

<div class="admin activitylog">
<?= $this->Flash->render('auth') ?>
<h1>Activity Log</h1>

<table width="100%" cellpadding="5" cellspacing="0" border="1" id="allActivityLog" style="border-collapse:collapse;">
                <thead>
                    <tr>
                    	<th nowrap>Action</th>
						<th nowrap>User</th>
						<th nowrap>Date/Time</th>
						<th nowrap>IP Address</th>
						<th nowrap>Browser</th>
						
						
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    	<th nowrap>Action</th>
						<th nowrap>User</th>
						<th nowrap>Date/Time</th>
						<th nowrap>IP Address</th>
						<th nowrap>Browser</th>
                    </tr>
                </tfoot>
                <tbody>
                	
                </tbody>
            </table>
			</div>
			
			
</div>
<style>
div.FixedHeader_Cloned th,
div.FixedHeader_Cloned td {
	background-color: white !important;
}
table, table tr{
	border: none;
}

td.logValue ul{ font-size:11px; margin-bottom:0; }

.dataTable tbody tr.odd{ background-color:#EBF2FC; }
.dataTable tbody tr.even{ background-color:#F4F6F9; }
.dataTable tbody tr:hover{ background-color:#BBF5B4; }

.dataTable thead tr th.logValue,
.dataTable tfoot tr th.logValue,
.dataTable tbody tr td.logValue{ width:350px !important; }

.dataTable thead tr th.userValue,
.dataTable tfoot tr th.userValue,
.dataTable tbody tr td.userValue{ width:130px !important; }

.dataTable thead tr th.ipAddress,
.dataTable tfoot tr th.ipAddress,
.dataTable tbody tr td.ipAddress{ width:130px !important; }

.dataTable thead tr th.browser,
.dataTable tfoot tr th.browser,
.dataTable tbody tr td.browser{ width:130px !important; }

.dataTable thead tr th.dateTime,
.dataTable tfoot tr th.dateTime,
.dataTable tbody tr td.dateTime{ width:130px !important; }
</style>

<script>

var dtArray=new Array();

			$(function(){
				$('#allActivityLog').dataTable({
						"lengthMenu": [[50,100, 300, 750, -1], [50,100,300,750,"All"]],
						"processing":true,
						"serverSide":true,
						"ajax":{"url":"/logs/getactivitylog/all.json"},
						dom:'<"top"ifrlp<"clear">>rt<"bottom"ifrlp<"clear">>',
						stateSave: true,
						searchHighlight: true,
						"order": [[ 4, "desc" ]],
						"columns": [
							{ "className":"logValue","orderable": false },
							{ "className":"userValue","orderable": false },
							{ "className":"ipAddress","orderable": false },
							{ "className":"browser","orderable": false },
							{ "className":"dateTime","orderable": true }
						  ]
				});
	
				});
				</script>