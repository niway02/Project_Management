'use strict';
$(document).on('click', '.delete', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var type = $(this).data('type');
    var reload = $(this).data('reload'); // Get the value of data-reload attribute
    if (typeof reload !== 'undefined' && reload === true) {
        reload = true;
    } else {
        reload = false;
    }
    var tableID = $(this).data('table') || 'table';
    var destroy = type == 'users' ? 'delete_user' : (type == 'contract-type' ? 'delete-contract-type' : (type == 'project-media' || type == 'task-media' ? 'delete-media' : (type == 'expense-type' ? 'delete-expense-type' : (type == 'milestone' ? 'delete-milestone' : 'destroy'))));
    type = type == 'contract-type' ? 'contracts' : (type == 'project-media' ? 'projects' : (type == 'task-media' ? 'tasks' : (type == 'expense-type' ? 'expenses' : (type == 'milestone' ? 'projects' : type))));
    $('#deleteModal').modal('show'); // show the confirmation modal
    $('#deleteModal').off('click', '#confirmDelete');
    $('#deleteModal').on('click', '#confirmDelete', function (e) {
        $('#confirmDelete').html(label_please_wait).attr('disabled', true);
        $.ajax({
            url: '/' + type + '/' + destroy + '/' + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
            },
            success: function (response) {
                $('#confirmDelete').html(label_yes).attr('disabled', false);
                $('#deleteModal').modal('hide');
                if (response.error == false) {
                    if (reload) {
                        location.reload();
                    } else {
                        toastr.success(response.message);
                        if (tableID) {
                            $('#' + tableID).bootstrapTable('refresh');
                        }

                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (data) {
                $('#confirmDelete').html(label_yes).attr('disabled', false);
                $('#deleteModal').modal('hide');
                toastr.error(label_something_went_wrong);
            }

        });
    });
});

$(document).on('click', '.delete-selected', function (e) {
    e.preventDefault();
    var table = $(this).data('table');
    var type = $(this).data('type');
    var destroy = type == 'users' ? 'delete_multiple_user' : (type == 'contract-type' ? 'delete-multiple-contract-type' : (type == 'project-media' || type == 'task-media' ? 'delete-multiple-media' : (type == 'expense-type' ? 'delete-multiple-expense-type' : (type == 'milestone' ? 'delete-multiple-milestone' : 'destroy_multiple'))));
    type = type == 'contract-type' ? 'contracts' : (type == 'project-media' ? 'projects' : (type == 'task-media' ? 'tasks' : (type == 'expense-type' ? 'expenses' : (type == 'milestone' ? 'projects' : type))));
    var selections = $('#' + table).bootstrapTable('getSelections');
    var selectedIds = selections.map(function (row) {
        return row.id; // Replace 'id' with the field containing the unique ID
    });
    if (selectedIds.length > 0) {

        $('#confirmDeleteSelectedModal').modal('show'); // show the confirmation modal
        $('#confirmDeleteSelectedModal').off('click', '#confirmDeleteSelections');
        $('#confirmDeleteSelectedModal').on('click', '#confirmDeleteSelections', function (e) {
            $('#confirmDeleteSelections').html(label_please_wait).attr('disabled', true);
            $.ajax({
                url: '/' + type + '/' + destroy,
                data: {
                    'ids': selectedIds,
                },
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
                },
                success: function (response) {
                    if (type == 'settings/languages') {
                        location.reload();
                    } else {
                        $('#confirmDeleteSelections').html(label_yes).attr('disabled', false);
                        $('#confirmDeleteSelectedModal').modal('hide');
                        $('#' + table).bootstrapTable('refresh');
                        if (response.error == false) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                },
                error: function (data) {
                    $('#confirmDeleteSelections').html(label_yes).attr('disabled', false);
                    $('#confirmDeleteSelectedModal').modal('hide');
                    toastr.error(label_something_went_wrong);
                }

            });
        });


    } else {
        toastr.error(label_please_select_records_to_delete);
    }



});

function update_status(e) {
    var id = e['id'];
    var name = e['name'];
    var status;
    var is_checked = $('input[name=' + name + ']:checked');

    if (is_checked.length >= 1) {
        status = 1;
    } else {
        status = 0;
    }
    $.ajax({
        url: '/todos/update_status',
        type: 'POST', // Use POST method
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        data: {
            _method: 'PUT', // Specify the desired method
            id: id,
            status: status
        },
        success: function (response) {
            if (response.error == false) {
                toastr.success(response.message); // show a success message
                $('#' + id + '_title').toggleClass('striked');
            } else {
                toastr.error(response.message);
            }

        }

    });
}

$(document).on('click', '.edit-todo', function () {
    var id = $(this).data('id');
    $('#edit_todo_modal').modal('show');
    $.ajax({
        url: '/todos/get/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            $('#todo_id').val(response.todo.id)
            $('#todo_title').val(response.todo.title)
            $('#todo_priority').val(response.todo.priority)
            $('#todo_description').val(response.todo.description)
        },

    });
});


$(document).on('click', '.edit-note', function () {
    var id = $(this).data('id');
    $('#edit_note_modal').modal('show');
    $.ajax({
        url: '/notes/get/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            $('#note_id').val(response.note.id)
            $('#note_title').val(response.note.title)
            $('#note_color').val(response.note.color)
            $('#note_description').val(response.note.description)
        },

    });
});


$(document).on('click', '.edit-status', function () {
    var id = $(this).data('id');
    $('#edit_status_modal').modal('show');
    $.ajax({
        url: '/status/get/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            $('#status_id').val(response.status.id)
            $('#status_title').val(response.status.title)
            $('#status_color').val(response.status.color)
        },

    });
});


$(document).on('click', '.edit-tag', function () {
    var id = $(this).data('id');
    $('#edit_tag_modal').modal('show');
    $.ajax({
        url: '/tags/get/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            $('#tag_id').val(response.tag.id)
            $('#tag_title').val(response.tag.title)
            $('#tag_color').val(response.tag.color)
        },

    });
});

$(document).on('click', '.edit-leave-request', function () {
    var id = $(this).data('id');
    $('#edit_leave_request_modal').modal('show');
    $.ajax({
        url: '/leave-requests/get/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            $('#lr_id').val(response.lr.id);
            $("input[name=status][value=" + response.lr.status + "]").prop('checked', true);
        }
    });
});

$(document).on('click', '.edit-contract-type', function () {
    var id = $(this).data('id');
    $('#edit_contract_type_modal').modal('show');
    $.ajax({
        url: '/contracts/get-contract-type/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            $('#update_contract_type_id').val(response.ct.id);
            $('#contract_type').val(response.ct.type);
        }
    });
});

$(document).on('click', '.edit-contract', function () {
    var id = $(this).data('id');
    $('#edit_contract_modal').modal('show');
    $.ajax({
        url: '/contracts/get/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            if (response.error == false) {
                var formattedStartDate = moment(response.contract.start_date).format(js_date_format);
                var formattedEndDate = moment(response.contract.end_date).format(js_date_format);
                var value = parseFloat(response.contract.value);
                $('#contract_id').val(response.contract.id);
                $('#title').val(response.contract.title);
                $('#value').val(value.toFixed(decimal_points));
                $('#client_id').val(response.contract.client_id);
                $('#project_id').val(response.contract.project_id);
                $('#contract_type_id').val(response.contract.contract_type_id);
                $('#update_contract_description').val(response.contract.description);
                $('#update_start_date').val(formattedStartDate);
                $('#update_end_date').val(formattedEndDate);
                initializeDateRangePicker('#update_start_date, #update_end_date');
            } else {
                location.reload();
            }


        }
    });
});
$(document).on('click', '.edit-expense-type', function () {
    var id = $(this).data('id');
    $('#edit_expense_type_modal').modal('show');
    $.ajax({
        url: '/expenses/get-expense-type/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            $('#update_expense_type_id').val(response.et.id);
            $('#expense_type_title').val(response.et.title);
            $('#expense_type_description').val(response.et.description);
        }
    });
});

$(document).on('click', '.edit-expense', function () {
    var id = $(this).data('id');
    $('#edit_expense_modal').modal('show');
    $.ajax({
        url: '/expenses/get/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            var formattedExpDate = moment(response.exp.expense_date).format(js_date_format);
            var amount = parseFloat(response.exp.amount);
            $('#update_expense_id').val(response.exp.id);
            $('#expense_title').val(response.exp.title);
            $('#expense_type_id').val(response.exp.expense_type_id);
            $('#expense_user_id').val(response.exp.user_id);
            $('#expense_amount').val(amount.toFixed(decimal_points));
            $('#update_expense_date').val(formattedExpDate);
            $('#expense_note').val(response.exp.note);
        }
    });
});

$(document).on('click', '.edit-language', function () {
    var id = $(this).data('id');
    $('#edit_language_modal').modal('show');
    $.ajax({
        url: '/settings/languages/get/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            $('#language_id').val(response.language.id)
            $('#language_title').val(response.language.name)
        },

    });
});

$(document).on('click', '.edit-payment', function () {
    var id = $(this).data('id');
    $('#edit_payment_modal').modal('show');
    $.ajax({
        url: '/payments/get/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            var formattedExpDate = moment(response.payment.payment_date).format(js_date_format);
            var amount = parseFloat(response.payment.amount);
            $('#update_payment_id').val(response.payment.id);
            $('#payment_user_id').val(response.payment.user_id);
            $('#payment_invoice_id').val(response.payment.invoice_id);
            $('#payment_pm_id').val(response.payment.payment_method_id);
            $('#payment_amount').val(amount.toFixed(decimal_points));
            $('#update_payment_date').val(formattedExpDate);
            $('#payment_note').val(response.payment.note);
        }
    });
});
function initializeDateRangePicker(inputSelector) {
    $(inputSelector).daterangepicker({
        alwaysShowCalendars: true,
        showCustomRangeLabel: true,
        // minDate: moment($(inputSelector).val(), js_date_format),
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: true,
        locale: {
            cancelLabel: 'Clear',
            format: js_date_format
        }
    });
}

$(document).on('click', '#set-as-default', function (e) {
    e.preventDefault();
    var lang = $(this).data('lang');
    $('#default_language_modal').modal('show'); // show the confirmation modal
    $('#default_language_modal').on('click', '#confirm', function () {
        $.ajax({
            url: '/settings/languages/set-default',
            type: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
            },
            data: {
                lang: lang
            },
            success: function (response) {
                if (response.error == false) {
                    location.reload();
                } else {
                    toastr.error(response.message);
                }

            }

        });
    });
});

$(document).on('click', '#remove-participant', function (e) {
    e.preventDefault();
    $('#leaveWorkspaceModal').modal('show'); // show the confirmation modal
    $('#leaveWorkspaceModal').on('click', '#confirm', function () {
        $.ajax({
            url: '/workspaces/remove_participant',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
            },
            success: function (response) {
                location.reload();
            },
            error: function (data) {
                location.reload();
            }
        });
    });
});
function resetDateFields($form) {
    var currentDate = moment(new Date()).format(js_date_format); // Get current date
    $form.find('input').each(function () {
        var $this = $(this);
        if ($this.data('daterangepicker')) {
            // Destroy old instance
            $this.data('daterangepicker').remove();

            // Reinitialize with new value
            $this.val(currentDate).daterangepicker({
                alwaysShowCalendars: true,
                showCustomRangeLabel: true,
                // minDate: moment($(id).val(), js_date_format),
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: true,
                locale: {
                    cancelLabel: 'Clear',
                    format: js_date_format
                }
            });
        }
    });
}

$(document).ready(function () {
    // Define the IDs you want to process
    var idsToProcess = ['#start_date', '#end_date', '#expense_date', '#update_expense_date', '#payment_date', '#update_payment_date', '#update_milestone_start_date', '#update_milestone_end_date', '#task_start_date', '#task_end_date'];

    // Loop through the IDs
    for (var i = 0; i < idsToProcess.length; i++) {
        var id = idsToProcess[i];

        if ($(id).length) {
            if ($(id).val() == '') {
                $(id).val(moment(new Date()).format(js_date_format));
            }
            $(id).daterangepicker({
                alwaysShowCalendars: true,
                showCustomRangeLabel: true,
                // minDate: moment($(id).val(), js_date_format),
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: true,
                locale: {
                    cancelLabel: 'Clear',
                    format: js_date_format
                }
            });
        }
    }



    // Define the IDs you want to process
    var idsToProcess = ['#payment_date', '#dob', '#doj'];

    // Loop through the IDs
    for (var i = 0; i < idsToProcess.length; i++) {
        var id = idsToProcess[i];

        if ($(id).length) {
            $(id).daterangepicker({
                alwaysShowCalendars: true,
                showCustomRangeLabel: true,
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                minDate: '01/01/1950',
                locale: {
                    cancelLabel: 'Clear',
                    format: js_date_format
                }
            });

            $(id).on('apply.daterangepicker', function (ev, picker) {
                // Update the input with the selected date
                $(this).val(picker.startDate.format(js_date_format));
            });
        }
    }
});



if ($("#total_days").length) {
    $('#end_date').on('apply.daterangepicker', function (ev, picker) {
        // Calculate the inclusive difference in days between start_date and end_date
        var start_date = moment($('#start_date').val(), js_date_format);
        var end_date = picker.startDate;
        var total_days = end_date.diff(start_date, 'days') + 1;

        // Display the total_days in the total_days input field
        $('#total_days').val(total_days);
    });
}
$(document).ready(function () {

    $('#start_date_between,#end_date_between,#project_start_date_between,#project_end_date_between,#task_start_date_between,#task_end_date_between,#lr_start_date_between,#lr_end_date_between,#contract_start_date_between,#contract_end_date_between,#timesheet_start_date_between,#timesheet_end_date_between,#meeting_start_date_between,#meeting_end_date_between,#activity_log_between_date,#expense_from_date_between,#payment_date_between').daterangepicker({
        alwaysShowCalendars: true,
        showCustomRangeLabel: true,
        singleDatePicker: false,
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: js_date_format
        },
    });
    $('#start_date_between,#end_date_between,#project_start_date_between,#project_end_date_between,#task_start_date_between,#task_end_date_between,#lr_start_date_between,#lr_end_date_between,#contract_start_date_between,#contract_end_date_between,#timesheet_start_date_between,#timesheet_end_date_between,#meeting_start_date_between,#meeting_end_date_between,#activity_log_between_date,#expense_from_date_between,#payment_date_between').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(js_date_format) + ' To ' + picker.endDate.format(js_date_format));
    });
});


if ($("#project_start_date_between").length) {
    $('#project_start_date_between').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');

        $('#project_start_date_from').val(startDate);
        $('#project_start_date_to').val(endDate);

        $('#projects_table').bootstrapTable('refresh');
    });

    $('#project_start_date_between').on('cancel.daterangepicker', function (ev, picker) {
        $('#project_start_date_from').val('');
        $('#project_start_date_to').val('');
        $('#projects_table').bootstrapTable('refresh');
        $('#project_start_date_between').val('');
    });

    $('#project_end_date_between').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');

        $('#project_end_date_from').val(startDate);
        $('#project_end_date_to').val(endDate);

        $('#projects_table').bootstrapTable('refresh');
    });
    $('#project_end_date_between').on('cancel.daterangepicker', function (ev, picker) {
        $('#project_end_date_from').val('');
        $('#project_end_date_to').val('');
        $('#projects_table').bootstrapTable('refresh');
        $('#project_end_date_between').val('');
    });
}

if ($("#task_start_date_between").length) {

    $('#task_start_date_between').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');

        $('#task_start_date_from').val(startDate);
        $('#task_start_date_to').val(endDate);

        $('#task_table').bootstrapTable('refresh');
    });

    $('#task_start_date_between').on('cancel.daterangepicker', function (ev, picker) {
        $('#task_start_date_from').val('');
        $('#task_start_date_to').val('');
        $('#task_table').bootstrapTable('refresh');
        $('#task_start_date_between').val('');
    });

    $('#task_end_date_between').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');

        $('#task_end_date_from').val(startDate);
        $('#task_end_date_to').val(endDate);

        $('#task_table').bootstrapTable('refresh');
    });
    $('#task_end_date_between').on('cancel.daterangepicker', function (ev, picker) {
        $('#task_end_date_from').val('');
        $('#task_end_date_to').val('');
        $('#task_table').bootstrapTable('refresh');
        $('#task_end_date_between').val('');
    });
}

if ($("#timesheet_start_date_between").length) {
    $('#timesheet_start_date_between').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');

        $('#timesheet_start_date_from').val(startDate);
        $('#timesheet_start_date_to').val(endDate);

        $('#timesheet_table').bootstrapTable('refresh');
    });

    $('#timesheet_start_date_between').on('cancel.daterangepicker', function (ev, picker) {
        $('#timesheet_start_date_from').val('');
        $('#timesheet_start_date_to').val('');
        $('#timesheet_table').bootstrapTable('refresh');
        $('#timesheet_start_date_between').val('');
    });

    $('#timesheet_end_date_between').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');

        $('#timesheet_end_date_from').val(startDate);
        $('#timesheet_end_date_to').val(endDate);

        $('#timesheet_table').bootstrapTable('refresh');
    });
    $('#timesheet_end_date_between').on('cancel.daterangepicker', function (ev, picker) {
        $('#timesheet_end_date_from').val('');
        $('#timesheet_end_date_to').val('');
        $('#timesheet_table').bootstrapTable('refresh');
        $('#timesheet_end_date_between').val('');
    });
}

if ($("#meeting_start_date_between").length) {
    $('#meeting_start_date_between').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');

        $('#meeting_start_date_from').val(startDate);
        $('#meeting_start_date_to').val(endDate);

        $('#meetings_table').bootstrapTable('refresh');
    });

    $('#meeting_start_date_between').on('cancel.daterangepicker', function (ev, picker) {
        $('#meeting_start_date_from').val('');
        $('#meeting_start_date_to').val('');
        $('#meetings_table').bootstrapTable('refresh');
        $('#meeting_start_date_between').val('');
    });

    $('#meeting_end_date_between').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');

        $('#meeting_end_date_from').val(startDate);
        $('#meeting_end_date_to').val(endDate);

        $('#meetings_table').bootstrapTable('refresh');
    });
    $('#meeting_end_date_between').on('cancel.daterangepicker', function (ev, picker) {
        $('#meeting_end_date_from').val('');
        $('#meeting_end_date_to').val('');
        $('#meetings_table').bootstrapTable('refresh');
        $('#meeting_end_date_between').val('');
    });
}

$('textarea#footer_text,textarea#contract_description,textarea#update_contract_description').tinymce({
    height: 250,
    menubar: false,
    plugins: [
        'link', 'a11ychecker', 'advlist', 'advcode', 'advtable', 'autolink', 'checklist', 'export',
        'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks',
        'powerpaste', 'fullscreen', 'formatpainter', 'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons', 'code'
    ],
    toolbar: 'link | undo redo | a11ycheck casechange blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist checklist outdent indent | removeformat | code blockquote emoticons table help'
});




$(document).on('submit', '.form-submit-event', function (e) {
    e.preventDefault();
    if ($('#net_payable').length > 0) {
        var net_payable = $('#net_payable').text();
        $('#net_pay').val(net_payable);
    }
    var formData = new FormData(this);
    var currentForm = $(this);
    var submit_btn = $(this).find('#submit_btn');
    var btn_html = submit_btn.html();
    var btn_val = submit_btn.val();
    var redirect_url = currentForm.find('input[name="redirect_url"]').val();
    redirect_url = (typeof redirect_url !== 'undefined' && redirect_url) ? redirect_url : '';
    var button_text = (btn_html != '' || btn_html != 'undefined') ? btn_html : btn_val;
    var tableInput = currentForm.find('input[name="table"]');
    var tableID = tableInput.length ? tableInput.val() : 'table';
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        beforeSend: function () {
            submit_btn.html(label_please_wait);
            submit_btn.attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (result) {

            submit_btn.html(button_text);
            submit_btn.attr('disabled', false);
            if (result['error'] == true) {
                toastr.error(result['message']);
            } else {
                if ($('.empty-state').length > 0 || currentForm.find('input[name="reload"]').length > 0) {
                    window.location.reload();
                } else {
                    if (currentForm.find('input[name="dnr"]').length > 0) {
                        var modalWithClass = $('.modal.fade.show');
                        if (modalWithClass.length > 0) {
                            var idOfModal = modalWithClass.attr('id');
                            $('#' + idOfModal).modal('hide');
                            $('#' + tableID).bootstrapTable('refresh');
                            currentForm[0].reset();
                            resetDateFields(currentForm);
                        }
                        toastr.success(result['message']);
                    } else {
                        if (result.hasOwnProperty('message')) {
                            toastr.success(result['message']);
                            // Show toastr for 3 seconds before reloading or redirecting
                            setTimeout(function () {
                                if (redirect_url == '') {
                                    window.location.reload(); // Reload the current page
                                } else {
                                    window.location.href = redirect_url; // Redirect to specified URL
                                }
                            }, 3000);
                        } else {
                            // No 'message' key, proceed to redirection immediately
                            if (redirect_url === '') {
                                window.location.reload(); // Reload the current page
                            } else {
                                window.location.href = redirect_url; // Redirect to specified URL
                            }
                        }

                    }
                }
            }
        },
        error: function (xhr, status, error) {
            submit_btn.html(button_text);
            submit_btn.attr('disabled', false);
            if (xhr.status === 422) {
                // Handle validation errors here
                var response = xhr.responseJSON; // Assuming you're returning JSON

                // You can access validation errors from the response object
                var errors = response.errors;

                // Example: Display the first validation error message
                toastr.error(label_please_correct_errors);
                // Assuming you have a list of all input fields with error messages
                var inputFields = currentForm.find('input[name], select[name], textarea[name]');
                inputFields = $(inputFields.toArray().reverse());
                // Iterate through all input fields
                inputFields.each(function () {
                    var inputField = $(this);
                    var fieldName = inputField.attr('name');
                    if (inputField.attr('type') !== 'radio') {
                        var errorMessageElement = inputField.next('.error-message');
                        if (errorMessageElement.length === 0) {
                            errorMessageElement = inputField.parent().nextAll('.error-message').first();
                        }
                        if (errorMessageElement.length === 0) {
                            // If it doesn't exist, create and append it
                            errorMessageElement = $('<p class="text-danger text-xs mt-1 error-message"></p>');
                            inputField.after(errorMessageElement);
                        }
                    }

                    if (errors && errors[fieldName]) {
                        // If there is a validation error message for this field, display it
                        if (errorMessageElement && errorMessageElement.length > 0) {
                            if (errors[fieldName][0].includes('required')) {
                                errorMessageElement.text('This field is required');
                            } if (errors[fieldName][0].includes('format is invalid')) {
                                errorMessageElement.text('Only numbers allowed.');
                            } else {
                                errorMessageElement.text(errors[fieldName]);
                            }
                            inputField[0].scrollIntoView({ behavior: "smooth", block: "start" });
                            inputField.focus();
                        }
                    } else {
                        // If there is no validation error message, clear the existing message
                        if (errorMessageElement && errorMessageElement.length > 0) {
                            errorMessageElement.text('');
                        }
                    }

                });
            } else {
                // Handle other errors (non-validation errors) here
                toastr.error(error);
            }
        }
    });
});


// Click event handler for the favorite icon
$(document).on('click', '.favorite-icon', function () {
    var icon = $(this);
    var projectId = $(this).data('id');
    var isFavorite = icon.attr('data-favorite');
    isFavorite = isFavorite == 1 ? 0 : 1;
    var reload = $(this).data("require_reload") !== undefined ? 1 : 0;
    var dataTitle = icon.data('bs-original-title');
    var temp = dataTitle !== undefined ? "data-bs-original-title" : "title";
    // Send an AJAX request to update the favorite status
    $.ajax({
        url: '/projects/update-favorite/' + projectId,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            is_favorite: isFavorite
        },
        success: function (response) {
            if (reload) {
                location.reload();
            } else {
                icon.attr('data-favorite', isFavorite);
                // Update the tooltip text
                if (isFavorite == 0) {
                    icon.removeClass("bxs-star");
                    icon.addClass("bx-star");
                    icon.attr(temp, add_favorite); // Update the tooltip text
                    toastr.success(label_project_removed_from_favorite_successfully);
                } else {
                    icon.removeClass("bx-star");
                    icon.addClass("bxs-star");
                    icon.attr(temp, remove_favorite); // Update the tooltip text
                    toastr.success(label_project_marked_as_favorite_successfully);
                }
            }

        },
        error: function (data) {
            // Handle errors if necessary
            toastr.error(error);
        }
    });
});

$(document).on('click', '.duplicate', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var type = $(this).data('type');
    var reload = $(this).data('reload'); // Get the value of data-reload attribute
    if (typeof reload !== 'undefined' && reload === true) {
        reload = true;
    } else {
        reload = false;
    }
    var tableID = $(this).data('table') || 'table';
    $('#duplicateModal').modal('show'); // show the confirmation modal
    $('#duplicateModal').off('click', '#confirmDuplicate');
    $('#duplicateModal').on('click', '#confirmDuplicate', function (e) {
        e.preventDefault();
        $('#confirmDuplicate').html(label_please_wait).attr('disabled', true);
        $.ajax({
            url: '/' + type + '/duplicate/' + id + '?reload=' + reload,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#confirmDuplicate').html(label_yes).attr('disabled', false);
                $('#duplicateModal').modal('hide');
                if (response.error == false) {
                    if (reload) {
                        location.reload();
                    } else {
                        toastr.success(response.message);
                        if (tableID) {
                            $('#' + tableID).bootstrapTable('refresh');
                        }

                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (data) {
                $('#confirmDuplicate').html(label_yes).attr('disabled', false);
                $('#duplicateModal').modal('hide');
                toastr.error(label_something_went_wrong);
            }

        });
    });
});

$('#deduction_type').on('change', function (e) {
    if ($('#deduction_type').val() == 'amount') {
        $('#amount_div').removeClass('d-none');
        $('#percentage_div').addClass('d-none');
    } else if ($('#deduction_type').val() == 'percentage') {
        $('#amount_div').addClass('d-none');
        $('#percentage_div').removeClass('d-none');
    } else {
        $('#amount_div').addClass('d-none');
        $('#percentage_div').addClass('d-none');
    }
});

$('#update_deduction_type').on('change', function (e) {
    if ($('#update_deduction_type').val() == 'amount') {
        $('#update_amount_div').removeClass('d-none');
        $('#update_percentage_div').addClass('d-none');
    } else if ($('#update_deduction_type').val() == 'percentage') {
        $('#update_amount_div').addClass('d-none');
        $('#update_percentage_div').removeClass('d-none');
    } else {
        $('#update_amount_div').addClass('d-none');
        $('#update_percentage_div').addClass('d-none');
    }
});


$('#tax_type').on('change', function (e) {
    if ($('#tax_type').val() == 'amount') {
        $('#amount_div').removeClass('d-none');
        $('#percentage_div').addClass('d-none');
    } else if ($('#tax_type').val() == 'percentage') {
        $('#amount_div').addClass('d-none');
        $('#percentage_div').removeClass('d-none');
    } else {
        $('#amount_div').addClass('d-none');
        $('#percentage_div').addClass('d-none');
    }
});

$('#update_tax_type').on('change', function (e) {
    if ($('#update_tax_type').val() == 'amount') {
        $('#update_amount_div').removeClass('d-none');
        $('#update_percentage_div').addClass('d-none');
    } else if ($('#update_tax_type').val() == 'percentage') {
        $('#update_amount_div').addClass('d-none');
        $('#update_percentage_div').removeClass('d-none');
    } else {
        $('#update_amount_div').addClass('d-none');
        $('#update_percentage_div').addClass('d-none');
    }
});


if (document.getElementById("system-update-dropzone")) {
    var is_error = false;
    if (!$("#system-update").hasClass("dropzone")) {
        var systemDropzone = new Dropzone("#system-update-dropzone", {
            url: $("#system-update").attr("action"),
            paramName: "update_file",
            autoProcessQueue: false,
            parallelUploads: 1,
            maxFiles: 1,
            acceptedFiles: ".zip",
            timeout: 360000,
            autoDiscover: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Pass the CSRF token as a header
            },
            addRemoveLinks: true,
            dictRemoveFile: "x",
            dictMaxFilesExceeded: "Only 1 file can be uploaded at a time ",
            dictResponseError: "Error",
            uploadMultiple: true,
            dictDefaultMessage:
                '<p><input type="button" value="Select Files" class="btn btn-primary" /><br> or <br> Drag & Drop System Update / Installable / Plugin\'s .zip file Here</p>',
        });

        systemDropzone.on("addedfile", function (file) {
            var i = 0;
            if (this.files.length) {
                var _i, _len;
                for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) {
                    if (
                        this.files[_i].name === file.name &&
                        this.files[_i].size === file.size &&
                        this.files[_i].lastModifiedDate.toString() ===
                        file.lastModifiedDate.toString()
                    ) {
                        this.removeFile(file);
                        i++;
                    }
                }
            }
        });

        systemDropzone.on("error", function (file, response) {
            console.log(response);
        });

        systemDropzone.on("sending", function (file, xhr, formData) {
            formData.append("flash_message", 1);
            xhr.onreadystatechange = function (response) {
                setTimeout(function () {
                    location.reload();
                }, 2000);
            };
        });
        $("#system_update_btn").on("click", function (e) {
            e.preventDefault();
            var queuedFiles = systemDropzone.getQueuedFiles();
            if (queuedFiles.length > 0) {
                if (is_error == false) {
                    $("#system_update_btn").attr('disabled', true).text(label_please_wait);
                    systemDropzone.processQueue();
                }
            } else {
                toastr.error('Please add a file to upload.');
            }

        });
    }
}


if (document.getElementById("media-upload-dropzone")) {
    var is_error = false;
    var mediaDropzone = new Dropzone("#media-upload-dropzone", {
        url: $("#media-upload").attr("action"),
        paramName: "media_files",
        autoProcessQueue: false,
        timeout: 360000,
        autoDiscover: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Pass the CSRF token as a header
        },
        addRemoveLinks: true,
        dictRemoveFile: "x",
        dictResponseError: "Error",
        uploadMultiple: true,
        dictDefaultMessage:
            '<p><input type="button" value="Select" class="btn btn-primary" /><br> or <br> Drag & Drop Files Here</p>',
    });

    mediaDropzone.on("addedfile", function (file) {
        var i = 0;
        if (this.files.length) {
            var _i, _len;
            for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) {
                if (
                    this.files[_i].name === file.name &&
                    this.files[_i].size === file.size &&
                    this.files[_i].lastModifiedDate.toString() ===
                    file.lastModifiedDate.toString()
                ) {
                    this.removeFile(file);
                    i++;
                }
            }
        }
    });

    mediaDropzone.on("error", function (file, response) {
        console.log(response);
    });

    mediaDropzone.on("sending", function (file, xhr, formData) {
        var id = $("#media_type_id").val();
        formData.append("flash_message", 1);
        formData.append("id", id);
        xhr.onreadystatechange = function (response) {
            location.reload();
            // $("#upload_media_btn").attr('disabled', false).text(label_upload);
            // if (response.error == false) {
            //     toastr.success(response.message);
            //     if ($('#add_media_modal').length) {
            //         $('#add_media_modal').modal('hide');
            //     }

            //     if ($('#project_media_table').length) {
            //         $('#project_media_table').bootstrapTable('refresh');
            //     }

            //     if ($('#task_media_table').length) {
            //         $('#task_media_table').bootstrapTable('refresh');
            //     }
            // } else {
            //     toastr.error(response.message);
            //     if ($('#add_media_modal').length) {
            //         $('#add_media_modal').modal('hide');
            //     }
            // }
        };
    });
    $("#upload_media_btn").on("click", function (e) {
        e.preventDefault();
        if (mediaDropzone.getQueuedFiles().length > 0) {
            if (is_error == false) {
                $("#upload_media_btn").attr('disabled', true).text(label_please_wait);
                mediaDropzone.processQueue();
            }
        } else {
            toastr.error('No file(s) chosen.');
        }

    });
}

$(document).on('click', '.admin-login', function (e) {
    e.preventDefault();
    $('#email').val('admin@gmail.com');
    $('#password').val('123456');
});
$(document).on('click', '.member-login', function (e) {
    e.preventDefault();
    $('#email').val('member@gmail.com');
    $('#password').val('123456');
});
$(document).on('click', '.client-login', function (e) {
    e.preventDefault();
    $('#email').val('client@gmail.com');
    $('#password').val('123456');
});


// Row-wise Select/Deselect All
$('.row-permission-checkbox').change(function () {
    var module = $(this).data('module');
    var isChecked = $(this).prop('checked');
    $(`.permission-checkbox[data-module="${module}"]`).prop('checked', isChecked);
});

$('#selectAllColumnPermissions').change(function () {
    var isChecked = $(this).prop('checked');
    $('.permission-checkbox').prop('checked', isChecked);
    if (isChecked) {
        $('.row-permission-checkbox').prop('checked', true).trigger('change'); // Check all row permissions when select all is checked
    } else {
        $('.row-permission-checkbox').prop('checked', false).trigger('change'); // Uncheck all row permissions when select all is unchecked
    }
    checkAllPermissions(); // Check all permissions
});

// Select/Deselect All for Rows
$('#selectAllPermissions').change(function () {
    var isChecked = $(this).prop('checked');
    $('.row-permission-checkbox').prop('checked', isChecked).trigger('change');
});


// Function to check/uncheck all permissions for a module
function checkModulePermissions(module) {
    var allChecked = true;
    $('.permission-checkbox[data-module="' + module + '"]').each(function () {
        if (!$(this).prop('checked')) {
            allChecked = false;
        }
    });
    $('#selectRow' + module).prop('checked', allChecked);
}

// Function to check if all permissions are checked and select/deselect "Select all" checkbox
function checkAllPermissions() {
    var allPermissionsChecked = true;
    $('.permission-checkbox').each(function () {
        if (!$(this).prop('checked')) {
            allPermissionsChecked = false;
        }
    });
    $('#selectAllColumnPermissions').prop('checked', allPermissionsChecked);
}

// Event handler for individual permission checkboxes
$('.permission-checkbox').on('change', function () {
    var module = $(this).data('module');
    checkModulePermissions(module);
    checkAllPermissions();
});

// Event handler for "Select all" checkbox
$('#selectAllColumnPermissions').on('change', function () {
    var isChecked = $(this).prop('checked');
    $('.permission-checkbox').prop('checked', isChecked);
});

// Initial check for permissions on page load
$('.row-permission-checkbox').each(function () {
    var module = $(this).data('module');
    checkModulePermissions(module);
});
checkAllPermissions();




$(document).ready(function () {
    $('.fixed-table-toolbar').each(function () {
        var $toolbar = $(this);
        var $data_type = $toolbar.closest('.table-responsive').find('#data_type');
        var $data_table = $toolbar.closest('.table-responsive').find('#data_table');

        if ($data_type.length > 0) {
            var data_type = $data_type.val();
            var data_table = $data_table.val() || 'table';

            // Create the "Delete selected" button
            var $deleteButton = $('<div class="columns columns-left btn-group float-left">' +
                '<button type="button" class="btn btn-outline-danger delete-selected float-left" data-type="' + data_type + '" data-table="' + data_table + '">' +
                '<i class="bx bx-trash"></i> ' + label_delete_selected + '</button>' +
                '</div>');

            // Add the "Delete selected" button before the first element in the toolbar
            $toolbar.prepend($deleteButton);
        }
    });
});



$('#media_storage_type').on('change', function (e) {
    if ($('#media_storage_type').val() == 's3') {
        $('.aws-s3-fields').removeClass('d-none');
    } else {
        $('.aws-s3-fields').addClass('d-none');
    }
});

$(document).on('click', '.edit-milestone', function () {
    var id = $(this).data('id');
    $.ajax({
        url: '/projects/get-milestone/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            var formattedStartDate = moment(response.ms.start_date).format(js_date_format);
            var formattedEndDate = moment(response.ms.end_date).format(js_date_format);
            $('#milestone_id').val(response.ms.id)
            $('#milestone_title').val(response.ms.title)
            $('#update_milestone_start_date').val(formattedStartDate)
            $('#update_milestone_end_date').val(formattedEndDate)
            $('#milestone_status').val(response.ms.status)
            $('#milestone_cost').val(response.ms.cost)
            $('#milestone_description').val(response.ms.description)
            $('#milestone_progress').val(response.ms.progress)
            $('.milestone-progress').text(response.ms.progress + '%');
        },

    });
});


$(document).on('click', '#mark-all-notifications-as-read', function (e) {
    e.preventDefault();
    $('#mark_all_notifications_as_read_modal').modal('show'); // show the confirmation modal
    $('#mark_all_notifications_as_read_modal').on('click', '#confirmMarkAllAsRead', function () {
        $('#confirmMarkAllAsRead').html(label_please_wait).attr('disabled', true);
        $.ajax({
            url: '/notifications/mark-all-as-read',
            type: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
            },
            success: function (response) {
                location.reload();
                // $('#confirmMarkAllAsRead').html(label_yes).attr('disabled', false);
            }

        });
    });
});


$(document).on('click', '.update-notification-status', function (e) {
    var notificationId = $(this).data('id');
    var needConfirm = $(this).data('needconfirm') || false;
    if (needConfirm) {
        // Show the confirmation modal
        $('#update_notification_status_modal').modal('show');

        // Attach click event handler to the confirmation button
        $('#update_notification_status_modal').off('click', '#confirmNotificationStatus');
        $('#update_notification_status_modal').on('click', '#confirmNotificationStatus', function () {
            $('#confirmNotificationStatus').html(label_please_wait).attr('disabled', true);
            performUpdate(notificationId, needConfirm);
        });
    } else {
        // If confirmation is not needed, directly perform the update and handle response
        performUpdate(notificationId);
    }
});

function performUpdate(notificationId, needConfirm = '') {
    $.ajax({
        url: '/notifications/update-status',
        type: 'PUT',
        data: { id: notificationId, needConfirm: needConfirm },
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        success: function (response) {
            if (needConfirm) {
                $('#confirmNotificationStatus').html(label_yes).attr('disabled', false);
                if (response.error == false) {
                    toastr.success(response.message);
                    $('#table').bootstrapTable('refresh');
                } else {
                    toastr.error(response.message);
                }
                $('#update_notification_status_modal').modal('hide');
            }
        }
    });
}
if (typeof manage_notifications !== 'undefined' && manage_notifications == 'true') {
    function updateUnreadNotifications() {
        // Make an AJAX request to fetch the count and HTML of unread notifications
        $.ajax({
            url: '/notifications/get-unread-notifications',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                const unreadNotificationsCount = data.count;
                const unreadNotificationsHtml = data.html;

                // Update the count in the badge
                $('#unreadNotificationsCount').text(unreadNotificationsCount);
                // if (unreadNotificationsCount == 0) {
                //     $('#mark-all-notifications-as-read').addClass('disabled');
                // } else {
                //     $('#mark-all-notifications-as-read').removeClass('disabled');
                // }

                // Update the notifications list with the new HTML
                $('#unreadNotificationsContainer').html(unreadNotificationsHtml);
            },
            error: function (xhr, status, error) {
                console.error('Error fetching unread notifications:', error);
            }
        });
    }

    // Call the updateUnreadNotifications function initially
    updateUnreadNotifications();

    // Update the unread notifications every 30 seconds
    setInterval(updateUnreadNotifications, 30000);
}


$('textarea#email_verify_email,textarea#email_account_creation,textarea#email_forgot_password,textarea#email_project_assignment,textarea#email_task_assignment,textarea#email_workspace_assignment,textarea#email_meeting_assignment').tinymce({
    height: 821,
    menubar: true,
    plugins: [
        'link', 'a11ychecker', 'advlist', 'advcode', 'advtable', 'autolink', 'checklist', 'export',
        'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks',
        'powerpaste', 'fullscreen', 'formatpainter', 'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons', 'code'
    ],
    toolbar: false
    // toolbar: 'link | undo redo | a11ycheck casechange blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist checklist outdent indent | removeformat | code blockquote emoticons table help'
});

// Handle click event on toolbar items
$('.tox-tbtn').click(function () {
    console.log('test');
    // Get the current editor instance
    var editor = tinyMCE.activeEditor;

    // Close any open toolbar dropdowns
    tinymce.ui.Factory.each(function (ctrl) {
        if (ctrl.type === 'toolbarbutton' && ctrl.settings.toolbar) {
            if (ctrl !== this && ctrl.settings.toolbar === 'toolbox') {
                ctrl.panel.hide();
            }
        }
    }, editor);

    // Execute the action associated with the clicked toolbar item
    editor.execCommand('mceInsertContent', false, 'Clicked!');
});


$(document).on('click', '.restore-default', function (e) {
    e.preventDefault();
    var form = $(this).closest('form');

    var type = form.find('input[name="type"]').val();
    var name = form.find('input[name="name"]').val();
    var textarea = type + '_' + name;

    $('#restore_default_modal').modal('show'); // show the confirmation modal
    $('#restore_default_modal').off('click', '#confirmRestoreDefault');
    $('#restore_default_modal').on('click', '#confirmRestoreDefault', function () {
        $('#confirmRestoreDefault').html(label_please_wait).attr('disabled', true);
        $.ajax({
            url: '/settings/get-default-template',
            type: 'POST',
            data: { type: type, name: name },
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
            },
            dataType: 'json',
            success: function (response) {
                $('#confirmRestoreDefault').html(label_yes).attr('disabled', false);
                $('#restore_default_modal').modal('hide');
                if (response.error == false) {
                    tinymce.get(textarea).setContent(response.content);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
});

$(document).on('click', '.sms-restore-default', function (e) {
    e.preventDefault();
    var form = $(this).closest('form');

    var type = form.find('input[name="type"]').val();
    var name = form.find('input[name="name"]').val();
    var textarea = type + '_' + name;

    $('#restore_default_modal').modal('show'); // show the confirmation modal
    $('#restore_default_modal').off('click', '#confirmRestoreDefault');
    $('#restore_default_modal').on('click', '#confirmRestoreDefault', function () {
        $('#confirmRestoreDefault').html(label_please_wait).attr('disabled', true);
        $.ajax({
            url: '/settings/get-default-template',
            type: 'POST',
            data: { type: type, name: name },
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
            },
            dataType: 'json',
            success: function (response) {
                $('#confirmRestoreDefault').html(label_yes).attr('disabled', false);
                $('#restore_default_modal').modal('hide');
                if (response.error == false) {
                    $('#' + textarea).val(response.content);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
});

$('.modal').on('hidden.bs.modal', function () {
    var $form = $(this).find('form'); // Find the form inside the modal
    $form.trigger('reset'); // Reset the form
    resetDateFields($form); // Pass the form as an argument to resetDateFields()
});

$(document).ready(function () {
    $('.js-example-basic-multiple[name="project"]').on('change', function (e) {
        var projectId = $(this).val();
        if (projectId) {
            $.ajax({
                url: "/projects/get/" + projectId,
                type: 'GET',
                success: function (response) {
                    $('#users_associated_with_project').html('(' + label_users_associated_with_project + ' <strong>' + response.project.title + '</strong>)');
                    var usersSelect = $('.js-example-basic-multiple[name="user_id[]"]');
                    usersSelect.empty(); // Clear existing options
                    // Check if task_accessibility is 'project_users'
                    $.each(response.users, function (index, user) {
                        var option = $('<option>', {
                            value: user.id,
                            text: user.first_name + ' ' + user.last_name,
                        });
                        usersSelect.append(option);
                    });
                    if (response.project.task_accessibility == 'project_users') {
                        var taskUsers = response.users.map(user => user.id);
                        usersSelect.val(taskUsers);
                    } else {
                        usersSelect.val(authUserId);
                    }
                    usersSelect.trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });
});


// $(document).ready(function () {
//     $('.js-example-basic-multiple[name="project"]').on('change', function (e) {
//         var projectId = $(this).val();
//         if (projectId) {
//             $.ajax({
//                 url: "/projects/get/" + projectId,
//                 type: 'GET',
//                 success: function (response) {
//                     $('#users_associated_with_project').html('(' + label_users_associated_with_project + ' <strong>' + response.project.title + '</strong>)');
//                     var usersSelect = $('.js-example-basic-multiple[name="user_id[]"]');
//                     usersSelect.empty(); // Clear existing options
//                     $.each(response.users, function (index, user) {
//                         var option = $('<option>', {
//                             value: user.id,
//                             text: user.first_name + ' ' + user.last_name
//                         });

//                         if (user.id == authUserId) {
//                             option.prop('selected', true); // Preselect the authenticated user
//                         }

//                         usersSelect.append(option);
//                     });
//                     // Trigger select2 plugin update
//                     usersSelect.trigger('change');
//                 },
//                 error: function (xhr, status, error) {
//                     console.error(error);
//                 }
//             });
//         }
//     });

//     // Trigger change event on project select if a project is preselected
//     $('.js-example-basic-multiple[name="project"]').trigger('change');
// });


$(document).on('click', '.edit-task', function () {
    var id = $(this).data('id');
    $('#edit_task_modal').modal('show');
    $.ajax({
        url: "/tasks/get/" + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        dataType: 'json',
        success: function (response) {
            var formattedStartDate = moment(response.task.start_date).format(js_date_format);
            var formattedEndDate = moment(response.task.end_date).format(js_date_format);
            $('#task_update_users_associated_with_project').html('(' + label_users_associated_with_project + ' <strong>' + response.project.title + '</strong>)');
            $('#id').val(response.task.id)
            $('#title').val(response.task.title)
            $('#status_id').val(response.task.status_id)
            $('#priority_id').val(response.task.priority_id ? response.task.priority_id : 0)
            $('#update_start_date').val(formattedStartDate);
            $('#update_end_date').val(formattedEndDate);
            initializeDateRangePicker('#update_start_date, #update_end_date');
            $('#project_title').val(response.project.title);
            $('#task_description').val(response.task.description);

            // Populate project users in the multi-select dropdown
            var usersSelect = $('.js-example-basic-multiple[name="user_id[]"]');
            usersSelect.empty(); // Clear existing options
            $.each(response.project.users, function (index, user) {
                var option = $('<option>', {
                    value: user.id,
                    text: user.first_name + ' ' + user.last_name
                });

                usersSelect.append(option);
            });

            // Preselect task users if they exist
            var taskUsers = response.task.users.map(user => user.id);
            usersSelect.val(taskUsers);
            usersSelect.trigger('change'); // Trigger change event to update select2
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
});

$(document).on('click', '.edit-project', function () {
    var id = $(this).data('id');
    $('#edit_project_modal').modal('show');
    $.ajax({
        url: "/projects/get/" + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        dataType: 'json',
        success: function (response) {
            var formattedStartDate = moment(response.project.start_date).format(js_date_format);
            var formattedEndDate = moment(response.project.end_date).format(js_date_format);
            $('#project_id').val(response.project.id)
            $('#project_title').val(response.project.title)
            $('#project_status_id').val(response.project.status_id)
            $('#project_priority_id').val(response.project.priority_id ? response.project.priority_id : 0)
            $('#project_budget').val(response.project.budget)
            $('#update_start_date').val(formattedStartDate);
            $('#update_end_date').val(formattedEndDate);
            initializeDateRangePicker('#update_start_date, #update_end_date');
            $('#task_accessiblity').val(response.project.task_accessiblity);
            $('#project_description').val(response.project.description);

            // Populate project users in the multi-select dropdown
            var usersSelect = $('.js-example-basic-multiple[name="user_id[]"]');

            // Preselect project users if they exist
            var projectUsers = response.users.map(user => user.id);
            usersSelect.val(projectUsers);
            usersSelect.trigger('change'); // Trigger change event to update select2



            var clientsSelect = $('.js-example-basic-multiple[name="client_id[]"]');

            var projectClients = response.clients.map(client => client.id);
            clientsSelect.val(projectClients);
            clientsSelect.trigger('change'); // Trigger change event to update select2


            var tagsSelect = $('.js-example-basic-multiple[name="tag_ids[]"]');

            var projectTags = response.tags.map(tag => tag.id);
            // Select old tags
            tagsSelect.val(projectTags);
            // Trigger change event to update Select2
            tagsSelect.trigger('change.select2');

        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
});

$(document).on('click', '.edit-priority', function () {
    var id = $(this).data('id');
    $('#edit_priority_modal').modal('show');
    $.ajax({
        url: '/priority/get/' + id,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        dataType: 'json',
        success: function (response) {
            $('#priority_id').val(response.priority.id)
            $('#priority_title').val(response.priority.title)
            $('#priority_color').val(response.priority.color)
        },

    });
});


$(document).on('change', '#statusSelect', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var statusId = this.value;
    var type = $(this).data('type') || 'project';
    var reload = $(this).data('reload') || false;
    var select = $(this);
    var originalStatusId = $(this).data('original-status-id');
    $.ajax({
        url: '/' + type + 's/get/' + id,
        type: 'GET',
        success: function (response) {
            if (response.error == false) {
                $('#confirmUpdateStatusModal').modal('show'); // show the confirmation modal
                $('#confirmUpdateStatusModal').off('click', '#confirmUpdateStatus');
                if (type == 'task' && response.task) {
                    $('#statusNote').val(response.task.note);
                    originalStatusId = response.task.status_id;
                } else if (type == 'project' && response.project) {
                    $('#statusNote').val(response.project.note);
                    originalStatusId = response.project.status_id;
                }
                $('#confirmUpdateStatusModal').on('click', '#confirmUpdateStatus', function (e) {
                    $('#confirmUpdateStatus').html(label_please_wait).attr('disabled', true);
                    // Send AJAX request to update status
                    $.ajax({
                        type: 'POST',
                        url: '/update-' + type + '-status',
                        headers: {
                            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
                        },
                        data: {
                            id: id,
                            statusId: statusId,
                            note: $('#statusNote').val()
                        },
                        success: function (response) {
                            $('#confirmUpdateStatus').html(label_yes).attr('disabled', false);
                            if (response.error == false) {
                                setTimeout(function () {
                                    if (reload) {
                                        window.location.reload(); // Reload the current page
                                    }
                                }, 3000);
                                $('#confirmUpdateStatusModal').modal('hide');
                                toastr.success(response.message);
                            } else {
                                select.val(originalStatusId);
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            $('#confirmUpdateStatus').html(label_yes).attr('disabled', false);
                            // Handle error
                            select.val(originalStatusId);
                            toastr.error('Something Went Wrong');
                        }
                    });
                });
            } else {
                $('#confirmUpdateStatus').html(label_yes).attr('disabled', false);
                select.val(originalStatusId);
                toastr.error(response.message);
            }
        },
        error: function (xhr, status, error) {
            // Handle error
            toastr.error('Something Went Wrong');
        }
    });
    // Handle modal close event
    $('#confirmUpdateStatusModal').off('click', '.btn-close, #declineUpdateStatus');
    $('#confirmUpdateStatusModal').on('click', '.btn-close, #declineUpdateStatus', function (e) {
        // Set original status when modal is closed without confirmation
        select.val(originalStatusId);
    });
});

$(document).on('change', '#prioritySelect', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var priorityId = this.value;
    var type = $(this).data('type') || 'project';
    var reload = $(this).data('reload') || false;
    var select = $(this);
    var originalPriorityId = $(this).data('original-priority-id') || 0;
    $('#confirmUpdatePriorityModal').modal('show'); // show the confirmation modal
    $('#confirmUpdatePriorityModal').off('click', '#confirmUpdatePriority');

    $('#confirmUpdatePriorityModal').on('click', '#confirmUpdatePriority', function (e) {
        $('#confirmUpdatePriority').html(label_please_wait).attr('disabled', true);
        $.ajax({
            type: 'POST',
            url: '/update-' + type + '-priority',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
            },
            data: {
                id: id,
                priorityId: priorityId
            },
            success: function (response) {
                $('#confirmUpdatePriority').html(label_yes).attr('disabled', false);
                if (response.error == false) {
                    setTimeout(function () {
                        if (reload) {
                            window.location.reload(); // Reload the current page
                        }
                    }, 3000);
                    $('#confirmUpdatePriorityModal').modal('hide');
                    toastr.success(response.message);
                } else {
                    select.val(originalPriorityId);
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                $('#confirmUpdatePriority').html(label_yes).attr('disabled', false);
                // Handle error
                select.val(originalPriorityId);
                toastr.error('Something Went Wrong');
            }
        });
    });
    // Handle modal close event
    $('#confirmUpdatePriorityModal').off('click', '.btn-close, #declineUpdatePriority');
    $('#confirmUpdatePriorityModal').on('click', '.btn-close, #declineUpdatePriority', function (e) {
        // Set original priority when modal is closed without confirmation
        select.val(originalPriorityId);
    });
});


$(document).on('click', '.quick-view', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var type = $(this).data('type') || 'task';
    $('#type').val(type);
    $('#typeId').val(id);
    $.ajax({
        url: '/' + type + 's/get/' + id,
        type: 'GET',
        success: function (response) {
            if (response.error == false) {
                if (type == 'task' && response.task) {
                    $('#quickViewTitlePlaceholder').text(response.task.title);
                    $('#quickViewDescPlaceholder').text(response.task.description);
                } else if (type == 'project' && response.project) {
                    $('#quickViewTitlePlaceholder').text(response.project.title);
                    $('#quickViewDescPlaceholder').text(response.project.description);
                }
                $('#typePlaceholder').text(type == 'task' ? label_task : label_project);
                $('#quickViewModal').modal('show');
                $('#usersTable').bootstrapTable('refresh');
                $('#clientsTable').bootstrapTable('refresh');

            } else {
                toastr.error(response.message);
            }
        },
        error: function (xhr, status, error) {
            // Handle error
            toastr.error('Something Went Wrong');
        }
    });

});

