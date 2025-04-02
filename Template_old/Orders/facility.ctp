<!-- File: src/Template/orders/facility.ctp -->
<div id="headingblock">
<h1 class="pageheading">Facility</h1>
<button type="button" id="newfacility">+ Add New Facility</button>
<div style="clear:both;"></div>
</div>
<table width="100%" cellpadding="5" border="0" id="facilityTable">
<thead>
                    <tr>
                        <th nowrap>Action</th>
						<th nowrap>Facility Code</th>
                        <th nowrap>Facility Name</th>
						<th nowrap>Default Address</th>
						<th nowrap>Attention</th>
						<th nowrap>Date/Time</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th nowrap>Action</th>
						<th nowrap>Facility Code</th>
                        <th nowrap>Facility Name</th>
						<th nowrap>Default Address</th>
						<th nowrap>Attention</th>
						<th nowrap>Date/Time</th>
                    </tr>
                </tfoot>
</table>
<script>
$(function(){
		$('#facilityTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
            "ajax":{"url":"/orders/getfacilitylist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			searchHighlight: true,
			"order": [[ 0, "asc" ]],
			"columns": [
				{ "className":"action","orderable": false },
                { "className":"facility_code","orderable": true },
                { "className":"facility_name","orderable": true },
                { "className":"default_address","orderable": true },
                { "className":"attention","orderable": false },
                { "className":"dateTime","orderable": false }
			  ],
              buttons:['excelHtml5'],
            		});
	
		$('input.checkallbutton').click(function(){
			if($(this).is(':checked')){
				$('#facilityTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',true);
				$('input.checkallbutton').prop('checked',true);
			}else{
				$('#facilityTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',false);
				$('input.checkallbutton').prop('checked',false);
			}
		});
		
		$('#newfacility').click(function(){
			location.href='/orders/facility/add/';
		});
	
		
});
</script>
<style>
button.dobulkedit{
    border: 1px solid #979797;
	color:#333 !important;
    background-color: white;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fff), color-stop(100%, #dcdcdc));
    background: -webkit-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -moz-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -ms-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -o-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: linear-gradient(to bottom, #fff 0%, #dcdcdc 100%);
	margin:0; padding:3px 6px !important;
	font-size:11px; 
}
#headingblock{ padding:20px 0; }
#headingblock h1{ float:left; }
#headingblock #newfacility{ float:right; }

input.checkallbutton{ margin:0 0 0 0 !important; }

.dt-buttons a.dt-button{ padding:5px !important; font-size:12px !important; margin:0 10px !important; }

#facilityTable th.bulkedit,#facilityTable td.bulkedit{ width:3% !important; }
#facilityTable td.bulkedit input[type=checkbox]{ margin:0 0 0 0 !important; }
#facilityTable td.bulkedit{ text-align:center; }
#facilityTable th{ padding:10px 0 !important; text-align:center; }
#facilityTable th.facility_code, #facilityTable td.facility_code{ width:20% !important; }
#facilityTable th.facility_code, #facilityTable td.facility_name{ width:20% !important; }
#facilityTable th.default_address, #facilityTable td.default_address{ width:20% !important; }
#facilityTable th.attention, #facilityTable td.attention{ width:10% !important; }
#facilityTable th.dateTime, #facilityTable td.dateTime{ width:10% !important; }

</style>
