'use strict';

function queryParams(p) {
    return {
        "status": $('#status_filter').val(),
        "id": $('#warehouses_user_filter').val(),
        "created_by": $('#warehouses_user_filter').val(),
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
};

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical" ></i>';
}

function actionsFormatter(value, row, index) {
    return [
        '<a href="javascript:void(0);" class="show-warehouses" data-id=' + row.id + ' title=' + label_show + '>' +
        '<i class="bx bx-show mx-1">' +
        '</i>' +
        '</a>' +
        '</a>' +
        '</a>'
    ];
}

$('#status_filter,#warehouses_user_filter,#warehouses_client_filter').on('change', function (e) {
    e.preventDefault();
    $('#warehouses_table').bootstrapTable('refresh');
});

$(document).ready(function() {
    $(document).on('click', '.show-warehouses', function() {
      const warehouseId = $(this).data('id');
      showWarehouseDetails(warehouseId);
    });
  
    $('#saveWarehouse').click(function() {
      const formData = {
        name: $('#warehouseName').val(),
        description: $('#warehouseDescription').val(),
        location: $('#warehouseLocation').val(),
        manager: $('#warehouseManager').val(),
        contact_info: $('#warehouseContactInfo').val(),
        created_by: $('#warehouseCreatedBy').val()
      };
  
      if (Object.values(formData).some(value => value.trim() === '')) {
        alert('Please fill in all the required fields.');
        return;
      }
  
      createWarehouse(formData);
    });
  });
  
  function showWarehouseDetails(warehouseId) {
    window.location.href = `{{ route("warehouses.show", ":id") }}`.replace(':id', warehouseId);
  }
  
  function createWarehouse(formData) {
    $.ajax({
      url: '{{ route("warehouses.create") }}',
      type: 'POST',
      data: formData,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        $('#warehouses_table').bootstrapTable('refresh');
        $('#createWarehouseModal').modal('hide');
      },
      error: function(xhr, status, error) {
        console.error(error);
        alert('An error occurred while creating the warehouse. Please try again later.');
      }
    });
  }