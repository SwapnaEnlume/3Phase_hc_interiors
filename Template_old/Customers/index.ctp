<!-- src/Template/Customers/index.ctp -->

<div class="customers form">
<h1 class="pageheading">Customers</h1>

<div id="newproductbutton"><a href="/customers/add/">+ Add Customer</a></div>

<table width="100%" cellpadding="5" cellspacing="0" border="0" id="customersList" style="border-collapse:collapse;">
                <thead>
                    <tr>
                    	<th nowrap>Action</th>
						<th nowrap>Company Name</th>
						<th nowrap>Billing Address</th>
						<th nowrap>Phone</th>
						<th nowrap>Surcharge</th>
						<th nowrap>Source</th>
                        <th nowrap>ZOHO Acct #</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    	<th nowrap>Action</th>
						<th nowrap>Company Name</th>
						<th nowrap>Billing Address</th>
						<th nowrap>Phone</th>
						<th nowrap>Surcharge</th>
						<th nowrap>Source</th>
                        <th nowrap>ZOHO Acct #</th>
                    </tr>
                </tfoot>
                <tbody>
                	
                </tbody>
            </table>

<script>
$(function(){
		$('#customersList').dataTable({
			"lengthMenu": [[25, 50, 100, 150, 10000000], [25, 50, 100, 150, "All"]],
			"processing":true,
			"bServerSide":true,
			"sServerMethod":"POST",
			"ajax":{"url":"/customers/getcustomers.json"},
			dom:'<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
			stateSave: true,
			buttons:[],
			searchHighlight: true,
			"order": [[ 1, "asc" ]],
			"columns": [
				{"className":'actions',"orderable": false},
				{"className":'companyname',"orderable": true},
				{"className":'billingaddress',"orderable": false},
				{"className":'phone',"orderable": false},
				{"className":'surcharge','orderable':false},
				{"className":'source','orderable':false},
				{"className":'zohoacct',"orderable": false}
			  ]
		});
});
</script>

</div>