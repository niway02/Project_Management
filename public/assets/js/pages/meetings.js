
'use strict';
function queryParams(p) {
    return {
        "status": $('#status_filter').val(),
        "user_id": $('#meeting_user_filter').val(),
        "client_id": $('#meeting_client_filter').val(),
        "start_date_from": $('#meeting_start_date_from').val(),
        "start_date_to": $('#meeting_start_date_to').val(),
        "end_date_from": $('#meeting_end_date_from').val(),
        "end_date_to": $('#meeting_end_date_to').val(),
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
    var actions = [
        '<a href="/meetings/edit/' + row.id + '" title=' + label_update + '>' +
        '<i class="bx bx-edit mx-1"></i>' +
        '</a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="meetings" data-table="meetings_table">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>' +
        '<a href="javascript:void(0);" class="duplicate" data-id=' + row.id + ' data-type="meetings" data-table="meetings_table" title=' + label_duplicate + '>' +
        '<i class="bx bx-copy text-warning mx-2"></i>' +
        '</a>'
    ];

    if (row.status === 'Ongoing') {
        actions.push(
            '<a href="/meetings/join/' + row.id + '" target="_blank" title="Join">' +
            '<i class="bx bx-arrow-to-right text-success mx-3"></i>' +
            '</a>'
        );
    }

    return actions.join('');
}



function clientFormatter(value, row, index) {
    var clients = Array.isArray(row.clients) && row.clients.length ? row.clients : '<span class="badge bg-primary">' + label_not_assigned + '</span>';
    if (Array.isArray(clients)) {
        clients = clients.map(client => '<li>' + client + '</li>');
        return '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">' + clients.join('') + '</ul>';
    } else {
        return clients;
    }
}


function userFormatter(value, row, index) {
    var users = Array.isArray(row.users) && row.users.length ? row.users : '<span class="badge bg-primary">' + label_not_assigned + '</span>';
    if (Array.isArray(users)) {
        users = users.map(user => '<li>' + user + '</li>');
        return '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">' + users.join('') + '</ul>';
    } else {
        return users;
    }
}

$('#status_filter,#meeting_user_filter,#meeting_client_filter').on('change', function (e) {
    e.preventDefault();
    $('#meetings_table').bootstrapTable('refresh');
});