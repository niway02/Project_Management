'use strict';

$(document).on('click', '#createBasicToken', function (e) {
    e.preventDefault();
    const username = $("#converterInputAccountSID").val();
    const password = $("#converterInputAuthToken").val();

    if (username && password) {
        const stringToEncode = `${username}:${password}`;
        $("#basicToken").text(`Authorization: Basic ${btoa(stringToEncode)}`);
    } else {
        // Handle the case where either username or password is empty
        toastr.error("Please provide both account SID and Auth Token.");
    }
});

$(document).on('click', '#add-header', function (e) {
    e.preventDefault();

    var key = $("#header_key").val().trim();
    var value = $("#header_value").val().trim();

    if (key !== '' && value !== '') {
        var html = `
        <div class="header-row"><div class="d-flex">

        <div class="mb-3 col-md-5 mx-1">
        <input type="text" class="form-control" name="header_key[]" value="${key}">
                                                </div>
                                                <div class="mb-3 col-md-5 mx-1">                                                    
                                                <input type="text" class="form-control" name="header_value[]" value="${value}">
                                                </div>
                                                <div class="mb-3 col-md-1 mx-3">
                                                    <button type="button" class="btn btn-sm btn-danger remove-header"><i class="bx bx-trash"></i></button>
                                                </div>
                
            </div>
            </div>
        `;

        $('#header-rows').append(html);
        $("#header_key").val('');
        $("#header_value").val('');
    } else {
        toastr.error('Please enter both key and value.');
    }
});


$(document).on('click', '#add-body-formdata', function (e) {
    e.preventDefault();

    var key = $("#body_formdata_key").val().trim();
    var value = $("#body_formdata_value").val().trim();

    if (key !== '' && value !== '') {
        var html = `
        <div class="body-formdata-row"><div class="d-flex">
        <div class="mb-3 col-md-5 mx-1">
            <input type="text" class="form-control" name="body_key[]" value="${key}">
        </div>
        <div class="mb-3 col-md-5 mx-1">
            <input type="text" class="form-control" name="body_value[]" value="${value}">
        </div>
        <div class="mb-3 col-md-1 mx-3">
            <button type="button" class="btn btn-sm btn-danger remove-body-formdata"><i class="bx bx-trash"></i></button>
        </div>
    </div>
            </div>
        `;

        $('#body-formdata-rows').append(html);
        $("#body_formdata_key").val('');
        $("#body_formdata_value").val('');
    } else {
        toastr.error('Please enter both key and value.');
    }
});

$(document).on('click', '#add-params', function (e) {
    e.preventDefault();

    var key = $("#params_key").val().trim();
    var value = $("#params_value").val().trim();

    if (key !== '' && value !== '') {
        var html = `
        <div class="params-row"><div class="d-flex">

        <div class="mb-3 col-md-5 mx-1">
        <input type="text" class="form-control" name="params_key[]" value="${key}">
                                                </div>
                                                <div class="mb-3 col-md-5 mx-1">                                                    
                                                <input type="text" class="form-control" name="params_value[]" value="${value}">
                                                </div>
                                                <div class="mb-3 col-md-1 mx-3">                                                    
                                                    <button type="button" class="btn btn-sm btn-danger remove-params"><i class="bx bx-trash"></i></button>
                                                </div>
                
            </div>
            </div>
        `;

        $('#params-rows').append(html);
        $("#params_key").val('');
        $("#params_value").val('');
    } else {
        toastr.error('Please enter both key and value.');
    }
});

$(document).on('click', '.remove-header', function () {
    $(this).closest('.header-row').remove();
});
$(document).on('click', '.remove-body-formdata', function () {
    $(this).closest('.body-formdata-row').remove();
});
$(document).on('click', '.remove-params', function () {
    $(this).closest('.params-row').remove();
});

function copyToClipboard(rowNumber) {
    /* Get the text content of the specific row */
    var copyText = document.getElementsByClassName("copyText")[rowNumber].innerText;

    /* Create a temporary input element */
    var tempInput = document.createElement("input");

    /* Set its value to the text content */
    tempInput.value = copyText;

    /* Append the input element to the body */
    document.body.appendChild(tempInput);

    /* Select the input element */
    tempInput.select();

    /* Execute copy command */
    document.execCommand("copy");

    /* Remove the temporary input element */
    document.body.removeChild(tempInput);

    /* Alert the user */
    toastr.success('Copied to clipboard successfully.');
}
