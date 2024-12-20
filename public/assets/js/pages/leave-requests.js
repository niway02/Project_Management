
'use strict';
function queryParamsLr(p) {
    return {
        "status": $('#lr_status_filter').val(),
        "user_id": $('#lr_user_filter').val(),
        "action_by_id": $('#lr_action_by_filter').val(),
        "start_date_from": $('#lr_start_date_from').val(),
        "start_date_to": $('#lr_start_date_to').val(),
        "end_date_from": $('#lr_end_date_from').val(),
        "end_date_to": $('#lr_end_date_to').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$('#lr_status_filter,#lr_user_filter,#lr_action_by_filter').on('change', function (e) {
    e.preventDefault();
    $('#lr_table').bootstrapTable('refresh');
});


$('#lr_start_date_between').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');

    $('#lr_start_date_from').val(startDate);
    $('#lr_start_date_to').val(endDate);

    $('#lr_table').bootstrapTable('refresh');
});

$('#lr_start_date_between').on('cancel.daterangepicker', function (ev, picker) {
    $('#lr_start_date_from').val('');
    $('#lr_start_date_to').val('');
    $('#lr_table').bootstrapTable('refresh');
    $('#lr_start_date_between').val('');
});

$('#lr_end_date_between').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');

    $('#lr_end_date_from').val(startDate);
    $('#lr_end_date_to').val(endDate);

    $('#lr_table').bootstrapTable('refresh');
});
$('#lr_end_date_between').on('cancel.daterangepicker', function (ev, picker) {
    $('#lr_end_date_from').val('');
    $('#lr_end_date_to').val('');
    $('#lr_table').bootstrapTable('refresh');
    $('#lr_end_date_between').val('');
});


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
        '<a href="javascript:void(0);" class="edit-leave-request" data-bs-toggle="modal" data-bs-target="#edit_leave_request_modal" data-id=' + row.id + ' title=' + label_update + ' class="card-link"><i class="bx bx-edit mx-1"></i></a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="leave-requests" data-table="lr_table">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>'
    ]
}