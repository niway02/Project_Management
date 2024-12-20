'use strict';

function queryParams(p) {
    return {
        "type": $('#type_filter').val(),
        "status": $('#hidden_status').val(),
        "client_id": $('#client_filter').val(),
        "start_date_from": $('#start_date_from').val(),
        "start_date_to": $('#start_date_to').val(),
        "end_date_from": $('#end_date_from').val(),
        "end_date_to": $('#end_date_to').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

$('#type_filter,#client_filter,#created_by_filter,#filter_starts_at,#filter_ends_at').on('change', function (e) {
    e.preventDefault();
    $('#table').bootstrapTable('refresh');
});


$('#start_date_between').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');

    $('#start_date_from').val(startDate);
    $('#start_date_to').val(endDate);

    $('#table').bootstrapTable('refresh');
});

$('#start_date_between').on('cancel.daterangepicker', function (ev, picker) {
    $('#start_date_from').val('');
    $('#start_date_to').val('');
    $('#table').bootstrapTable('refresh');
    $('#start_date_between').val('');
});

$('#end_date_between').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');

    $('#end_date_from').val(startDate);
    $('#end_date_to').val(endDate);

    $('#table').bootstrapTable('refresh');
});
$('#end_date_between').on('cancel.daterangepicker', function (ev, picker) {
    $('#end_date_from').val('');
    $('#end_date_to').val('');
    $('#table').bootstrapTable('refresh');
    $('#end_date_between').val('');
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
        '<a href="/estimates-invoices/edit/' + row.id + '" title=' + label_update + ' class="card-link"><i class="bx bx-edit mx-1"></i></a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="estimates-invoices">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>' +
        '<a href="javascript:void(0);" class="duplicate" data-id=' + row.id + ' data-type="estimates-invoices" title=' + label_duplicate + '>' +
        '<i class="bx bx-copy text-warning mx-2"></i>' +
        '</a>' +
        '<a href="/estimates-invoices/pdf/' + row.id + '" title="PDF" target="_blank">' +
        '<i class="bx bxs-file-pdf text-secondary mx-2"></i>' +
        '</a>'
    ]
}

function idFormatter(value, row, index) {
    var idPrefix = (row.type == 'Estimate') ? label_estimate_id_prefix : (row.type == 'Invoice') ? label_invoice_id_prefix : '';
    return [
        '<a href="/estimates-invoices/view/' + row.id + '" target="_blank">' + idPrefix + row.id + '</a>'
    ];
}

$(document).on('click', '.status-badge', function (e) {
    var status = $(this).data('status');
    var type = $(this).data('type');
    $('#hidden_status').val(status);
    $('#type_filter').val(type);
    $('#table').bootstrapTable('refresh');
});

// Define status options for each type
const statusOptions = {
    'estimate': [
        { value: 'sent', text: label_sent },
        { value: 'accepted', text: label_accepted },
        { value: 'draft', text: label_draft },
        { value: 'declined', text: label_declined },
        { value: 'expired', text: label_expired }
    ],
    'invoice': [
        { value: 'fully_paid', text: label_fully_paid },
        { value: 'partially_paid', text: label_partially_paid },
        { value: 'draft', text: label_draft },
        { value: 'cancelled', text: label_cancelled },
        { value: 'due', text: label_due }
    ]
};

// Function to update status dropdown options
function updateStatusOptions(type) {
    const statusSelect = $('#status');
    const defaultOption = statusSelect.find('option:first');
    statusSelect.empty().append(defaultOption);

    // Add new options based on selected type
    const options = statusOptions[type] || [];
    options.forEach(function (option) {
        statusSelect.append($('<option></option>').attr('value', option.value).text(option.text));
    });
}

// Event listener for type selection change
$('#type').on('change', function (e) {
    const selectedType = $(this).val();
    updateStatusOptions(selectedType);
});

$('#client_id').on('change', function (e) {

    var client_id = $('#client_id').val();
    if (client_id != '') {
        $.ajax({
            url: '/clients/get/' + client_id,
            type: 'get',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
            },
            success: function (result) {
                $('.billing_name').html(result.client.first_name + ' ' + result.client.last_name);
                $('#update_name').val(result.client.first_name + ' ' + result.client.last_name);
                $('#name').val(result.client.first_name + ' ' + result.client.last_name);

                $('.billing_address').html(result.client.address);
                $("textarea#update_address").val(result.client.address);
                $('#address').val(result.client.address);

                $('.billing_contact').html(result.client.phone);
                $('#update_contact').val(result.client.phone);
                $('#contact').val(result.client.phone);

                $('.billing_city').html(result.client.city);
                $('#update_city').val(result.client.city);
                $('#city').val(result.client.city);


                $('.billing_state').html(result.client.state);
                $('#update_state').val(result.client.state);
                $('#state').val(result.client.state);

                $('.billing_country').html(result.client.country);
                $('#update_country').val(result.client.country);
                $('#country').val(result.client.country);

                $('.billing_zip').html(result.client.zip);
                $('#update_zip_code').val(result.client.zip);
                $('#zip_code').val(result.client.zip);

            }
        });
    } else {
        $('.billing_name').html('--');
        $('.billing_address').html('--');
        $('.billing_city').html('--');
        $('.billing_state').html('--');
        $('.billing_country').html('--');
        $('.billing_zip').html('--');
        $('.billing_contact').html('--');

        $('#update_name').val('');
        $("textarea#update_address").val('');
        $('#update_city').val('');
        $('#update_state').val('');
        $('#update_zip_code').val('');
        $('#update_country').val('');
        $('#update_contact').val('');

        $('#name').val('');
        $("textarea#address").val('');
        $('#contact').val('');
    }

});

$(document).on('click', '.edit-billing-details', function () {
    $('#edit-billing-address').modal('show');
});

$(document).on('click', '#apply_billing_details', function (e) {
    e.preventDefault();
    $('#apply_billing_details').html(label_please_wait).attr('disabled', true);
    var name = $('#update_name').val();
    var address = $("textarea#update_address").val();
    var city = $('#update_city').val();
    var state = $('#update_state').val();
    var country = $('#update_country').val();
    var zip_code = $('#update_zip_code').val();
    var contact = $('#update_contact').val();

    $('.billing_name').html(name);
    $('.billing_address').html(address);
    $('.billing_city').html(city);
    $('.billing_state').html(state);
    $('.billing_country').html(country);
    $('.billing_zip').html(zip_code);
    $('.billing_contact').html(contact);

    $('#name').val(name);
    $("textarea#address").val(address);
    $('#city').val(city);
    $('#state').val(state);
    $('#country').val(country);
    $('#zip_code').val(zip_code);
    $('#contact').val(contact);

    $('#apply_billing_details').html(label_apply).attr('disabled', false);
    $('#edit-billing-address').modal('hide');
    toastr.success(label_billing_details_updated_successfully);
});

$('#item_id').on('change', function (e) {

    var item_id = $('#item_id').val();
    if (item_id != '') {
        $.ajax({
            type: 'get',
            url: '/items/get/' + item_id,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
            },
            success: function (result) {
                $('#item_0_title').val(result.item.title);
                $("textarea#item_0_description").val(result.item.description);
                $('#item_0_rate').val(result.item.price.toFixed(decimal_points));
                $('#item_0_quantity').val(1).trigger('change');
                $('#item_0_unit').val(result.item.unit_id);
            }
        });
    } else {
        $('#item_0_title').val('');
        $("textarea#item_0_description").val('');
        $('#item_0_quantity').val('');
        $('#item_0_unit').val('');
        $('#item_0_rate').val('');
        $('#item_0_tax').val('');
        $('#item_0_amount').val('');
    }
});


$('#add-item').on('click', function (e) {
    e.preventDefault();
    var html = '';

    var title = $("#item_0_title").val();
    var quantity = $("#item_0_quantity").val();
    var rate = $("#item_0_rate").val();
    var amount = $("#item_0_amount").val();
    var description = $("#item_0_description").val();
    var unit = $("#item_0_unit").val();
    var tax = $("#item_0_tax").val();
    var tax_title = $(".item_0_tax_title").text();
    var tax_percentage = $("#item_0_tax option:selected").text();
    if (title != '' && quantity != '' && rate != '' && amount != '') {
        var item_id = $("#item_id").val();
        var item_ids = $("#item_ids").val();

        item_ids = item_ids.split(',');

        var exists = item_ids.includes(item_id);

        if (!exists) {
            $('#item_id').val('').trigger('change');
            items_count++
            item_ids = item_ids.toString();
            if (item_ids != '') {
                item_ids = item_ids + ',' + item_id;
            } else {
                item_ids = item_id;
            }
            $("#item_ids").val(item_ids)
            if (amount == '') {
                amount = rate * quantity;
            }
            amount = +amount + +0;
            amount = amount.toFixed(decimal_points);
            html = '<div class="estimate-invoice-item"><div class="d-flex">' +
                '<input type="hidden" id=item_' + items_count + ' name="item[]">' +
                '<div class="mb-3 col-md-2 mx-1">' +
                '<input type="text" id="item_' + items_count + '_title" name="title[]" class="form-control" readonly>' +
                '</div>' +
                '<div class="mb-3 col-md-2 mx-1">' +
                '<textarea class="form-control" id="item_' + items_count + '_description" name="description[]" readonly></textarea>' +
                '</div>' +
                '<div class="mb-3 col-md-1 mx-1">' +
                '<input type="number" name="quantity[]" step="0.25" id="item_' + items_count + '_quantity" onchange="update_amount(' + items_count + ')" class="form-control" min="0.25">' +
                '</div>' +
                '<div class="mb-3 col-md-1 mx-1">' +
                '<select class="form-select" name="unit[]" id="item_' + items_count + '_unit">' +
                units +
                '</select>' +
                '</div>' +
                '<div class="mb-3 col-md-2 mx-1">' +
                '<input type="text" name="rate[]" id="item_' + items_count + '_rate" onchange="update_amount(' + items_count + ')" class="form-control">' +
                '</div>' +
                '<div class="mb-3 col-md-1 mx-1">' +
                '<select class="form-select" name="tax[]" id="item_' + items_count + '_tax" onchange="update_amount(' + items_count + ');">' +
                taxes +
                '</select>' +
                '<div class="item_' + items_count + '_tax_title"></div>' +
                '<input class="item_' + items_count + '_tax_title" type="hidden" name="tax_amount[]"></input>' +
                '</div>' +
                '<div class="mb-3 col-md-2 mx-1">' +
                '<input type="text" id="item_' + items_count + '_amount" class="form-control" name="amount[]" onchange="updateTotals()">' +
                '</div>' +
                '<div class="mb-3 col-md-1 mx-1">' +
                '<button type="button" class="btn btn-sm btn-danger my-1 remove-estimate-invoice-item" data-count=' + items_count + '><i class="bx bx-trash"></i></button>' +
                '</div>' +
                '</div></div>';

            $('#estimate-invoice-items').append(html);
            $('#item_' + items_count).val(item_id);
            $('#item_' + items_count + '_title').val(title);
            $('#item_' + items_count + '_description').val(description);
            $('#item_' + items_count + '_quantity').val(quantity);
            $('#item_' + items_count + '_unit').val(unit);
            $('#item_' + items_count + '_rate').val(rate);
            $('#item_' + items_count + '_tax').val(tax);
            $('#item_' + items_count + '_amount').val(amount);
            $('.item_' + items_count + '_tax_title').text(tax_title);
            updateTotals();

            $("#item_0_title").val('');
            $("#item_0_description").val('');
            $("#item_0_quantity").val('');
            $("#item_0_unit").val('');
            $("#item_0_rate").val('');
            $("#item_0_tax").val('');
            $("#item_0_amount").val('');
            $(".item_0_tax_title").text('');

        } else {
            toastr.error('Item already added.');
        }

    } else {
        toastr.error('Please fill all required fields.');
    }

});

function updateTotals() {
    var subTotal = 0;
    var finalTotal = 0;
    var Taxamount = 0;

    // Loop through items and calculate subTotal
    $("input[name='amount[]']").each(function () {
        var amount = parseFloat($(this).val()) || 0;
        subTotal += amount;
    });

    // Update sub_total
    $("#sub_total").val(subTotal.toFixed(decimal_points));

    $("input[name='tax_amount[]']").each(function () {
        var amount = parseFloat($(this).text()) || 0;
        Taxamount += amount;
    });
    $("#total_tax").val(Taxamount.toFixed(decimal_points));

    finalTotal = subTotal += Taxamount;
    $("#final_total").val(finalTotal.toFixed(decimal_points));
}

function update_amount(itemIndex, isUpdateTotals = 1) {
    // Get values from input fields
    var quantity = parseFloat($("#item_" + itemIndex + "_quantity").val()) || 0;
    var rate = parseFloat($("#item_" + itemIndex + "_rate").val()) || 0;
    var disp_tax = '';
    var tax_id = $('#item_' + itemIndex + '_tax').val();
    if (tax_id != '') {
        $.ajax({
            url: '/taxes/get/' + tax_id,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val() // Replace with your method of getting the CSRF token
            },
            success: function (result) {
                // Calculate tax amount based on the retrieved tax rate
                var taxRate = parseFloat(result.tax.amount) || 0;
                var taxType = result.tax.type; // Assuming tax type is provided in the response
                var taxAmount = 0;
                if (taxType == 'percentage') {
                    taxAmount = ((quantity * rate) * result.tax.percentage) / 100;
                    disp_tax = taxAmount.toFixed(decimal_points) + '(' + result.tax.percentage + '%)';
                } else if (taxType == 'amount') {
                    taxAmount = taxRate;
                    disp_tax = taxAmount.toFixed(decimal_points);
                }

                // Update tax amount field
                // $("#item_" + itemIndex + "_tax_amount").val(taxAmount.toFixed(decimal_points));

                // Update tax title display
                $('.item_' + itemIndex + '_tax_title').text(disp_tax);
                $('.item_' + itemIndex + '_tax_amount').val(taxAmount);

                // Update item amount
                var amount = quantity * rate + taxAmount;
                $("#item_" + itemIndex + "_amount").val(amount.toFixed(decimal_points));

                if (isUpdateTotals) {
                    // Update sub_total, total_tax, and final_total
                    updateTotals();
                }
            }
        });
    } else {
        // Clear tax details if no tax selected
        $('.item_' + itemIndex + '_tax_title').text('');
        $('.item_' + itemIndex + '_tax_amount').val('');
        // $("#item_" + itemIndex + "_tax_amount").val('0');

        // Calculate amount
        var amount = quantity * rate;

        // Update item amount
        $("#item_" + itemIndex + "_amount").val(amount.toFixed(decimal_points));

        if (isUpdateTotals) {
            // Update sub_total, total_tax, and final_total
            updateTotals();
        }
    }
}




function updateFinalTotal() {
    var finalTotal = 0;

    var taxAmountField = $("#total_tax");
    var Taxamount = parseFloat(taxAmountField.val()) || 0;

    var subTotalField = $("#sub_total");
    var subTotal = parseFloat(subTotalField.val()) || 0;

    finalTotal = subTotal += Taxamount;
    $("#final_total").val(finalTotal.toFixed(decimal_points));
}

$(document).on('click', '.remove-estimate-invoice-item', function (e) {
    e.preventDefault();
    var count = $(this).data('count');
    var item_id = $("#item_" + count).val();
    var item_ids = $("#item_ids").val().split(','); // Split the string into an array
    var index = $.inArray(item_id.toString(), item_ids);
    if (index !== -1) {
        // Remove the allowance_id from the array
        item_ids.splice(index, 1);
        // Update the #allowance_ids input value with the modified string
        $("#item_ids").val(item_ids.join(',')); // Join the array back into a string
    }
    // console.log(item_id);
    var quantity = $("#item_" + count + "_quantity").val();
    var rate = $("#item_" + count + "_rate").val();

    var amount = rate * quantity;
    var tax_percentage = '';
    var tax_amount = amount / 100 * tax_percentage;
    var final_amount = +amount + +tax_amount;
    var rounded_final_amount = final_amount.toFixed(decimal_points);

    $(this).closest('.estimate-invoice-item').remove();
    updateTotals();
    items_count--;
});