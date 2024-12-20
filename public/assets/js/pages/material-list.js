'use strict';

function queryParams(p) {
    return {
        "id": $('#warehouses_user_filter').val(),
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
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical" ></i>'
}

function actionsFormatter(value, row, index) {
    return [
        '<a href="javascript:void(0);" class="edit-warehouses" data-id=' + row.id + ' title=' + label_update + '>' +
        '<i class="bx bx-edit mx-1">' +
        '</i>' +
        '</a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="warehouses" data-table="warehouses_table">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>' +
        '</a>'+
        '<a href="javascript:void(0);" class="quick-view" data-id=' + row.id + ' data-type="project" title="' + label_quick_view + '">' +
        '<i class="bx bx-info-circle text-primary mx-3"></i>' +
        '</a>'
    ]
}

$('#status_filter,#warehouses_user_filter,#warehouses_client_filter').on('change', function (e) {
    e.preventDefault();
    $('#warehouses_table').bootstrapTable('refresh');
});

function actionFormatterClients(value, row, index) {
    return [
        '<a href="/clients/edit/' + row.id + '" title=' + label_update + '>' +
        '<i class="bx bx-edit mx-1">' +
        '</i>' +
        '</a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="clients">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>'
    ]
}

function ProjectUserFormatter(value, row, index) {
    let formattedValue = '';
  
    // Check if the row has a 'created_by' property
    if (row.created_by) {
      // Create a formatted string for the created_by value
      formattedValue = `
        <div class="d-flex align-items-center">
          <img src="${row.created_by.avatar}" alt="${row.name}" class="rounded-circle me-2" width="30" height="30">
          <span>${row.created_by.name}</span>
        </div>
      `;
    }
  
    return formattedValue;
  }
  $(document).ready(function() {
    // Handle the "Create Warehouse" button click
    $('#saveWarehouse').click(function() {
        var name = $('#warehouseName').val();
        var description = $('#warehouseDescription').val();
        var location = $('#warehouseLocation').val();
        var manager = $('#warehouseManager').val();
        var contact_info = $('#warehouseContactInfo').val();
        var created_by = $('#warehouseCreatedBy').val();

        // Validate the form fields
        if (name.trim() === '' || description.trim() === '' || location.trim() === '' || manager.trim() === '' || contact_info.trim() === '' || created_by.trim() === '') {
            // Display an error message or handle the validation in your desired way
            alert('Please fill in all the required fields.');
            return;
        }

        // Prepare the data to be sent to the server
        var data = {
            name: name,
            description: description,
            location: location,
            manager: manager,
            contact_info: contact_info,
            created_by: created_by
        };

        // Use AJAX to send the form data to the server
        $.ajax({
            url: '{{ route("warehouses.store") }}',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Handle the successful response
                // For example, you can update the warehouses table
                $('#warehouses_table').bootstrapTable('refresh');
                $('#createWarehouseModal').modal('hide');
            },
            error: function(xhr, status, error) {
                // Handle the error response
                console.error(error);
                alert('An error occurred while creating the warehouse. Please try again later.');
            }
        });
    });
});