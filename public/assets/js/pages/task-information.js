/**
 * Dashboard Analytics
 */
'use strict';

function queryParamsTaskMedia(p) {
    return {
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function queryParams(p) {
    return {
        "user_id": $('#user_filter').val(),
        "client_id": $('#client_filter').val(),
        "activity": $('#activity_filter').val(),
        "type": 'task',
        "type_id": $('#type_id').val(),
        "date_from": $('#activity_log_between_date_from').val(),
        "date_to": $('#activity_log_between_date_to').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function actionsFormatter(value, row, index) {
    return [
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="activity-log" data-table="activity_log_table">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>'
    ]
}




$('#activity_log_between_date').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');

    $('#activity_log_between_date_from').val(startDate);
    $('#activity_log_between_date_to').val(endDate);

    $('#activity_log_table').bootstrapTable('refresh');
});

$('#activity_log_between_date').on('cancel.daterangepicker', function (ev, picker) {
    $('#activity_log_between_date_from').val('');
    $('#activity_log_between_date_to').val('');
    $('#activity_log_table').bootstrapTable('refresh');
    $('#activity_log_between_date').val('');
});


$('#user_filter,#client_filter,#activity_filter').on('change', function (e) {
    e.preventDefault();
    $('#activity_log_table').bootstrapTable('refresh');
});