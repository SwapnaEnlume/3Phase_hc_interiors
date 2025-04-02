<!-- PPSASCRUM-287: start -->
<div id="headingblock">
    <h1 class="pageheading">Commonly Used Global Notes Categories</h1>
    <button type="button" id="new-global-note-category">+ Add New Global Notes Categories</button>
    <div style="clear:both;"></div>
</div>

<table width="100%" cellpadding="5" id="commonGlobalNoteCategoriesTable">
    <thead>
        <tr>
            <th>Action</th>
            <th>Category</th>
            <th>Created</th>
            <th>Last Modified</th>
        </tr>
    </thead>
<tbody>

</tbody>
    <tfoot>
        <tr>
            <th>Action</th>
            <th>Category</th>
            <th>Created</th>
            <th>Last Modified</th>
        </tr>
    </tfoot>
</table>

<script>
    $(function() {
        const clientTimezoneOffset = new Date().getTimezoneOffset();
        $("#commonGlobalNoteCategoriesTable").dataTable({
            "lengthMenu": [[25, 50, 100, 150, 10000], [25, 50, 100, 150, "All"]],
            "processing": true,
            "bServerSide": true,
            "sServerMethod": "GET",
            "ajax": { "url": "/quotes/commonGnoteCategories.json?tz-offset=" + clientTimezoneOffset },
            "language": {
                "info": `<p style="font-size: 13.5px;font-weight: 370;">Showing _START_ to _END_ of _TOTAL_ entries<br/>(filtered from _MAX_ total entries)</p>`,
            },
            dom: '<"top"iBfrlp<"clear">>rt<"bottom"iBfrlp<"clear">>',
            searching: true,
            stateSave: true,
            buttons: [ "excelHtml5" ],
            buttons: [{
                extend: 'excelHtml5',
                className: 'excel-export',
            }],
            searchHighlight: true,
            "order": [[ 1, "asc" ]],
            "columns": [
                { "className": "action", "orderable": false },
                { "className": "category", "orderable": true },
                { "className": "created", "orderable": false },
                { "className": "last-modified", "orderable": false }
            ]
        });
        
        $('#new-global-note-category').click(function() {
            location.href = '/quotes/common-gnote-categories/add';
        });
    });
</script>

<style>
    #headingblock { padding:20px 0; }
    #headingblock h1 { float:left; }
    #headingblock #new-global-note-category { float:right; }
    .excel-export { margin-left: 30px; }
</style>
<!-- PPSASCRUM-287: end -->