$(function() {
    var $table = $('#equipments_table');
    var $tableContainer = $('.table-responsive');

    // Initialize the table
    $table.bootstrapTable({
        onPostBody: function() {
            // Add event listeners for edit and delete actions
            addEventListeners();
        }
    });

    function addEventListeners() {
        // Add event listener for edit button
        $table.find('.btn-primary').click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            window.location.href = url;
        });

        // Add event listener for delete button
        $table.find('.btn-danger').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            if (confirm('Are you sure you want to delete this item?')) {
                form.submit();
            }
        });
    }

    // Function to handle query parameters for server-side pagination
    function queryParamsEquipments(params) {
        var queryParams = {
            type: $('#data_type').val(),
            limit: params.limit,
            offset: params.offset,
            search: params.search,
            sort: params.sort,
            order: params.order
        };
        return queryParams;
    }
});