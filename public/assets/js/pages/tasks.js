
window.icons = {
    refresh: 'bx-refresh'
}

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical" ></i>'
}
function actionFormatter(value, row, index) {
    return [
        '<a href="javascript:void(0);" class="edit-task" data-id=' + row.id + ' title=' + label_update_task + '>' +
        '<i class="bx bx-edit text-primary mx-2"></i>' +
        '</a>' +

        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="tasks" data-table="task_table">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>' +

        '<a href="javascript:void(0);" class="duplicate" data-id=' + row.id + ' data-type="tasks" data-table="task_table" title=' + label_duplicate + '>' +
        '<i class="bx bx-copy text-warning mx-2"></i>' +
        '</a>'+

        '<a href="javascript:void(0);" class="quick-view" data-id=' + row.id + ' title="' + label_quick_view + '">' +
        '<i class="bx bx-info-circle text-primary mx-3"></i>' +
        '</a>'
    ]
}
function progressFormatter(value, row, index) {
    let color = 'black';
    if (value > 80) {
        color = 'green';
    } else if (value > 65) {
        color = 'blue';
    } else if (value > 40) {
        color = 'orange';
    } else if (value > 20) {
        color = 'white';
    }

    let progressBar = `
    <div class="progress" style="height: 20px;">
    <div class="progress-bar bg-blue progress-bar-lg" role="progressbar" style="width: 40%;" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">
       <span style="font-size: 16px;">40%</span>
    </div>
  </div>
    `;

    return progressBar;
}
function titleFormatter(value, row, index) {
    let title = row.title;
    let subtaskUrl = '/subtasks/' + row.id; // Replace with your actual subtask URL
  
    // Create an anchor tag with the subtask URL
    let link = '<a href="' + subtaskUrl + '">' + title + '</a>';
  
    return link;
  }
function issueFormatter(value, row, index) { 
    return 'Becuase of weaither';
}
function actionFormatterUsers(value, row, index) {
    return [
        '<a href="/users/edit/' + row.id + '" title=' + label_update + '>' +
        '<i class="bx bx-edit mx-1">' +
        '</i>' +
        '</a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="users">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>'
    ]
}
function StatusUpdateFormatter(value, row, index) {
    let title = row.status_id;
    return `<span>${title}</span>`;
}
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

function TaskUserFormatter(value, row, index) {
    if (Array.isArray(row.users) && row.users.length) {
        var users = row.users;
        users = users.map(user => '<li>' + user + '</li>');
        var userListHtml = '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">' + users.join('') +
            '<li title="' + label_update + '">' +
            '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-task update-users-clients" data-id="' + row.id + '">' +
            '<span class="bx bx-edit"></span>' +
            '</a>' +
            '</li></ul>';
        return userListHtml;
    } else {
        var notAssignedHtml = '<span class="badge bg-primary">' + label_not_assigned + '</span>';
        notAssignedHtml += '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-task update-users-clients" data-id="' + row.id + '">' +
            '<span class="bx bx-edit"></span>' +
            '</a>';
        return notAssignedHtml;
    }
}

function TaskClientFormatter(value, row, index) {
    if (Array.isArray(row.clients) && row.clients.length) {
        var clients = row.clients;
        clients = clients.map(user => '<li>' + user + '</li>');
        var clientListHtml = '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">' + clients.join('') +
            '</ul>';
        return clientListHtml;
    } else {
        var notAssignedHtml = '<span class="badge bg-primary">' + label_not_assigned + '</span>';        
        return notAssignedHtml;
    }
}
function PriorityFormatter(value, row, index) {
    let title = row.priority_id;

    // Return the title with a green background
    return `<span class="badge bg-primary">${title}</span>`;
}
function StatusFormatter(value, row, index) {
    let title = row.status;

    // Return the title with a green background
    return `<span class="badge bg-primary">${title}</span>`;
}
function queryParamsTasks(p) {
    return {
        "status": $('#task_status_filter').val(),
        "user_id": $('#tasks_user_filter').val(),
        "client_id": $('#tasks_client_filter').val(),
        "project_id": $('#tasks_project_filter').val(),
        "task_start_date_from": $('#task_start_date_from').val(),
        "task_start_date_to": $('#task_start_date_to').val(),
        "task_end_date_from": $('#task_end_date_from').val(),
        "task_end_date_to": $('#task_end_date_to').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$('#task_status_filter,#tasks_user_filter,#tasks_client_filter,#tasks_project_filter').on('change', function (e) {
    e.preventDefault();
    $('#task_table').bootstrapTable('refresh');
});

function userFormatter(value, row, index) {
    return '<div class="d-flex">' + row.photo + '<div class="mx-2 mt-2"><h6 class="mb-1">' + row.first_name + ' ' + row.last_name +
    (row.status === 1 ? ' <span class="badge bg-success">Active</span>' : ' <span class="badge bg-danger">Deactive</span>') +
    '</h6><p class="text-muted">' + row.email + '</p></div>' +
    '</div>';

}

function clientFormatter(value, row, index) {
    return '<div class="d-flex">' + row.profile + '<div class="mx-2 mt-2"><h6 class="mb-1">' + row.first_name + ' ' + row.last_name +
    (row.status === 1 ? ' <span class="badge bg-success">Active</span>' : ' <span class="badge bg-danger">Deactive</span>') +
    '</h6><p class="text-muted">' + row.email + '</p></div>' +
    '</div>';

}

function assignedFormatter(value, row, index) {
    return '<div class="d-flex justify-content-start align-items-center"><div class="text-center mx-4"><span class="badge rounded-pill bg-primary" >' + row.projects + '</span><div>' + label_projects + '</div></div>' +
        '<div class="text-center"><span class="badge rounded-pill bg-primary" >' + row.tasks + '</span><div>' + label_tasks + '</div></div></div>'
}

function queryParamsUsersClients(p) {
    return {
        type: $('#type').val(),
        typeId: $('#typeId').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

