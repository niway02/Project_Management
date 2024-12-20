
'use strict';
function queryParamsExpenseTypes(p) {
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
        "type_id": $('#type_filter').val(),
        "date_from": $('#expense_date_from').val(),
        "date_to": $('#expense_date_to').val(),
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
        '<a href="javascript:void(0);" class="edit-expense-type" data-bs-toggle="modal" data-id=' + row.id + ' title=' + label_update + ' class="card-link"><i class="bx bx-edit mx-1"></i></a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="expense-type">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>'
    ]
}

function expensesActionsFormatter(value, row, index) {
    return [
        '<a href="javascript:void(0);" class="edit-expense" data-bs-toggle="modal" data-id=' + row.id + ' title=' + label_update + ' class="card-link"><i class="bx bx-edit mx-1"></i></a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="expenses">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>' +
        '<a href="javascript:void(0);" class="duplicate" data-id=' + row.id + ' data-type="expenses" title=' + label_duplicate + '>' +
        '<i class="bx bx-copy text-warning mx-2"></i>' +
        '</a>'
    ]
}

$('#expense_from_date_between').on('apply.daterangepicker', function (ev, picker) {
    var fromDate = picker.startDate.format('YYYY-MM-DD');
    var toDate = picker.endDate.format('YYYY-MM-DD');

    $('#expense_date_from').val(fromDate);
    $('#expense_date_to').val(toDate);

    $('#table').bootstrapTable('refresh');
});

$('#expense_from_date_between').on('cancel.daterangepicker', function (ev, picker) {
    $('#expense_date_from').val('');
    $('#expense_date_to').val('');
    $('#table').bootstrapTable('refresh');
    $('#expense_from_date_between').val('');
});

$('#user_filter,#type_filter').on('change', function (e) {
    e.preventDefault();
    $('#table').bootstrapTable('refresh');
});