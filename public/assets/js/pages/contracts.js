
'use strict';
function queryParams(p) {
    return {
        "status": $('#status_filter').val(),
        "client_id": $('#client_filter').val(),
        "project_id": $('#project_filter').val(),
        "type_id": $('#type_filter').val(),
        "start_date_from": $('#contract_start_date_from').val(),
        "start_date_to": $('#contract_start_date_to').val(),
        "end_date_from": $('#contract_end_date_from').val(),
        "end_date_to": $('#contract_end_date_to').val(),
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
        '<a href="javascript:void(0);" class="edit-contract" data-bs-toggle="modal" data-bs-target="#edit_contract_modal" data-id=' + row.id + ' title=' + label_update + ' class="card-link"><i class="bx bx-edit mx-1"></i></a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="contracts" data-table="contracts_table">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>' +
        '<a href="javascript:void(0);" class="duplicate" data-id=' + row.id + ' data-type="contracts" data-table="contracts_table" title=' + label_duplicate + '>' +
        '<i class="bx bx-copy text-warning mx-2"></i>' +
        '</a>'
    ]
}

function idFormatter(value, row, index) {
    return [
        '<a href="/contracts/sign/' + row.id + '" target="_blank">' + label_contract_id_prefix + row.id + '</a>'
    ]
}
if ($('#promisor_sign').length) {
    var canvas = document.getElementById('promisor_sign');
    var signaturePad = new SignaturePad(canvas);
    $('#create_contract_sign_modal #submit_btn').on('click', function (e) {
        e.preventDefault();
        if (!isSignatureEmpty()) {
            var img_data = signaturePad.toDataURL('image/png');
            $("<input>").attr({
                type: "hidden",
                name: "signatureImage",
                value: img_data
            }).appendTo("#contract_sign_form");
            $("#contract_sign_form").submit();
        } else {
            toastr.error('Please draw signature.');
        }
    });
}

$('#contract_sign_form').on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var submit_btn = $(this).find('#submit_btn');
    var btn_html = submit_btn.html();
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
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            if (result.error == false) {
                location.reload();
            }
            else {
                submit_btn.html(btn_html);
                submit_btn.attr('disabled', false);
                toastr.error(result.message);
            }
        }
    });

});

$(document).on('click', '#reset_promisor_sign', function (e) {
    e.preventDefault();
    signaturePad.clear();
});

function isSignatureEmpty() {
    // Get the data URL of the canvas
    var dataURL = signaturePad.toDataURL();

    // Define an initial state or known empty state
    var initialStateDataURL = 'data:image/png;base64,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'; // Replace with your empty state data URL

    // Check if the data URL matches the initial state data URL
    return dataURL === initialStateDataURL;
}

$(document).on('click', '.delete_contract_sign', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('#delete_contract_sign_modal').off('click', '#confirmDelete');
    $('#delete_contract_sign_modal').on('click', '#confirmDelete', function (e) {
        e.preventDefault();
        $.ajax({
            url: '/contracts/delete-sign/' + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
            },
            beforeSend: function () {
                $('#confirmDelete').html(label_please_wait).attr('disabled', true);
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

$('#contract_start_date_between').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');

    $('#contract_start_date_from').val(startDate);
    $('#contract_start_date_to').val(endDate);

    $('#contracts_table').bootstrapTable('refresh');
});

$('#contract_start_date_between').on('cancel.daterangepicker', function (ev, picker) {
    $('#contract_start_date_from').val('');
    $('#contract_start_date_to').val('');
    $('#contracts_table').bootstrapTable('refresh');
    $('#contract_start_date_between').val('');
});

$('#contract_end_date_between').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');

    $('#contract_end_date_from').val(startDate);
    $('#contract_end_date_to').val(endDate);

    $('#contracts_table').bootstrapTable('refresh');
});
$('#contract_end_date_between').on('cancel.daterangepicker', function (ev, picker) {
    $('#contract_end_date_from').val('');
    $('#contract_end_date_to').val('');
    $('#contracts_table').bootstrapTable('refresh');
    $('#contract_end_date_between').val('');
});

$('#status_filter,#client_filter,#project_filter,#type_filter').on('change', function (e) {
    e.preventDefault();
    $('#contracts_table').bootstrapTable('refresh');
});





