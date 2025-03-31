<!-- File: src/Template/orders/shipto.ctp -->
<div id="headingblock">
<h1 class="pageheading">Ship To Addresses</h1>
<button type="button" id="newshipto">+ Add New ShipTo Address</button>
<div style="clear:both;"></div>
</div>
<table width="100%" cellpadding="5" border="0" id="shiptoTable">
<thead>
                    <tr>
                        <th nowrap>Action</th>
						<th nowrap>Shipping Address1</th>
                        <th nowrap>Shipping Address2</th>
						<th nowrap>Shipping City</th>
                        <th nowrap>Shipping State</th>
                        <th nowrap>Shipping Zipcode</th>
						<th nowrap>Date/Time</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    	<th nowrap>Action</th>
						<th nowrap>Shipping Address1</th>
                        <th nowrap>Shipping Address2</th>
						<th nowrap>Shipping City</th>
                        <th nowrap>Shipping State</th>
                        <th nowrap>Shipping Zipcode</th>
						<th nowrap>Date/Time</th>
                    </tr>
                </tfoot>
</table>
<script>
$(function(){
		$('#shiptoTable').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
            "ajax":{"url":"/orders/getshiptoslist.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			searchHighlight: true,
			buttons:['excelHtml5'],
			"columns": [
				{ "className":"action","orderable": false },
                { "className":"shipping_address_1","orderable": true },
                { "className":"shipping_address_2","orderable": true },
                { "className":"shipping_city","orderable": true },
                { "className":"shipping_state","orderable": false },
                { "className":"shipping_zipcode","orderable": false },
                { "className":"attention","orderable": false },
			  ],
			  "aaSorting": [[ 4, "desc" ]]
		});
	
		$('input.checkallbutton').click(function(){
			if($(this).is(':checked')){
				$('#shiptoTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',true);
				$('input.checkallbutton').prop('checked',true);
			}else{
				$('#shiptoTable tbody tr td.bulkedit input[type=checkbox]').prop('checked',false);
				$('input.checkallbutton').prop('checked',false);
			}
		});
		
		$('#newshipto').click(function(){
			location.href='/orders/shipto/add/';
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
#headingblock #newshipto{ float:right; }

input.checkallbutton{ margin:0 0 0 0 !important; }

.dt-buttons a.dt-button{ padding:5px !important; font-size:12px !important; margin:0 10px !important; }

#shiptoTable th.bulkedit,#shiptoTable td.bulkedit{ width:3% !important; }
#shiptoTable td.bulkedit input[type=checkbox]{ margin:0 0 0 0 !important; }
#shiptoTable td.bulkedit{ text-align:center; }
#shiptoTable th{ padding:10px 0 !important; text-align:center; }
#shiptoTable th.action, #shiptoTable td.action{ width:8% !important; }
#shiptoTable th.shipping_address1, #shiptoTable td.shipping_address1{ width:15% !important; }
#shiptoTable th.shipping_address2, #shiptoTable td.shipping_address2{ width:15% !important; }
#shiptoTable th.shipping_city, #shiptoTable td.shipping_city{ width:10% !important; }
#shiptoTable th.shipping_state, #shiptoTable td.shipping_state{ width:10% !important; }
#shiptoTable th.shipping_zipcode, #shiptoTable td.shipping_zipcode{ width:10% !important; }
#shiptoTable th.dateTime, #shiptoTable td.dateTime{ width:10% !important; }

</style>
