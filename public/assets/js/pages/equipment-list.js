'use strict';

function queryParamsEquipments(p) {
    return {
        "status": $('#status_filter').val(),
        "id": $('#equipments_user_filter').val(),
        "created_by": $('#equipments_client_filter').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

window.icons = {
    refresh: 'bx-refresh',
    toggleOn: 'bx-toggle-right',
    toggleOff: 'bx-toggle-left'
}

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical"></i>';
}

function actionsFormatter(value, row, index) {
    return [
        '<div class="dropdown">' +
            '<button class="btn btn-secondary dropdown-toggle" type="button" id="actionMenu' + row.id + '" data-bs-toggle="dropdown" aria-expanded="false">' +
                'Actions' +
            '</button>' +
            '<ul class="dropdown-menu" aria-labelledby="actionMenu' + row.id + '">' +
            '<li>' +
            '<a class="dropdown-item" href="/equipments/' + row.id + '" title="' + label_update + '">' +
            '<i class="bx bx-info-circle text-primary mx-3"></i> Quick View' +
            '</a>' +
        '</li>' +
                '<li>' +
                    '<a class="dropdown-item" href="/equipments/' + row.id + '/edit" title="' + label_update + '">' +
                        '<i class="bx bx-edit mx-1"></i> Edit' +
                    '</a>' +
                '</li>' +
                '<li>' +
                    '<button class="dropdown-item delete" type="button" onclick="confirmDelete(' + row.id + ')" title="' + label_delete + '">' +
                        '<i class="bx bx-trash text-danger mx-1"></i> Delete' +
                    '</button>' +
                '</li>' +
                '<li>' +
                    '<a class="dropdown-item quick-view" href="javascript:void(0);" onclick="showQuickView(' + row.id + ')" title="' + label_quick_view + '">' +
                        '<i class="bx bx-info-circle text-primary mx-3"></i> Quick View' +
                    '</a>' +
                '</li>' +
            '</ul>' +
        '</div>'
    ].join('');
}

// Event listener for filters
$('#status_filter, #equipments_user_filter, #equipments_client_filter').on('change', function (e) {
    e.preventDefault();
    $('#equipments_table').bootstrapTable('refresh');
});

// Confirm delete function
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this equipment?")) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Show quick view function
function showQuickView(id) {
    // Logic to load equipment details (e.g., via AJAX) and display in a modal can be added here
    console.log("Quick view for equipment ID:", id);
    // Example: Load data via AJAX and show in a modal
}

// Add any additional necessary JavaScript here