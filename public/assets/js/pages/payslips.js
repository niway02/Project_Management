
'use strict';

function queryParams(p) {
    return {
        "status": $('#status_filter').val(),
        "user_id": $('#user_filter').val(),
        "created_by": $('#created_by_filter').val(),
        "month": $('#filter_payslip_month').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

$('#status_filter,#user_filter,#created_by_filter,#filter_payslip_month').on('change', function (e) {
    e.preventDefault();
    $('#payslips_table').bootstrapTable('refresh');
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
        '<a href="/payslips/edit/' + row.id + '" title=' + label_update + ' class="card-link"><i class="bx bx-edit mx-1"></i></a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="payslips" data-table="payslips_table">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>' +
        '<a href="javascript:void(0);" class="duplicate" data-id=' + row.id + ' data-type="payslips" data-table="payslips_table" title=' + label_duplicate + '>' +
        '<i class="bx bx-copy text-warning mx-2"></i>' +
        '</a>'
    ]
}

function idFormatter(value, row, index) {
    return [
        '<a href="/payslips/view/' + row.id + '" target="_blank">' + label_payslip_id_prefix + row.id + '</a>'
    ]
}

var currentDate = new Date();

// Get the year and month of the previous month
var previousMonth = new Date(currentDate);
previousMonth.setMonth(currentDate.getMonth() - 1);
var previousYear = previousMonth.getFullYear();
var previousMonthNumber = previousMonth.getMonth() + 1; // Month is zero-based, so add 1

// Calculate the last day of the previous month
var lastDayOfPreviousMonth = new Date(previousYear, previousMonthNumber, 0).getDate();

// Format the date as "YYYY-MM"
var formattedDate = previousYear + '-' + (previousMonthNumber < 10 ? '0' : '') + previousMonthNumber;

// Set the formatted date and last day as input values
$('#payslip_month').val(formattedDate);
$('#working_days').val(lastDayOfPreviousMonth);


$('#payslip_month').on('change', function () {
    var selectedValue = $(this).val();

    if (selectedValue) {
        // Split the selected value into year and month
        var selectedYear = parseInt(selectedValue.split("-")[0]);
        var selectedMonth = parseInt(selectedValue.split("-")[1]);

        // Calculate the last day of the selected month
        var lastDay = new Date(selectedYear, selectedMonth, 0).getDate();

        // Display the total days in a paragraph
        $('#working_days').val(lastDay);
    } else {
        // If no value is selected, clear the total days display
        $('#working_days').val("");
    }
});


// $(document).on('ready', function () {
// Get references to the input fields
var basicSalaryInput = $('#basic_salary');
var workingDaysInput = $('#working_days');
var lopDaysInput = $('#lop_days');
var paidDaysInput = $('#paid_days');
var leaveDeductionInput = $('#leave_deduction');
var overTimeHoursInput = $('#over_time_hours');
var overTimeRateInput = $('#over_time_rate');
var overTimePaymentInput = $('#over_time_payment');
var bonusInput = $('#bonus');
var incentivesInput = $('#incentives');
var perDayPayment = 0;

// Function to calculate over time payment
function calculateOverTimePayment() {
    var overtimeHours = parseFloat(overTimeHoursInput.val()) || 0;
    var overtimeRate = parseFloat(overTimeRateInput.val()) || 0;
    var overTimePayment = overtimeHours * overtimeRate;
    overTimePaymentInput.val(overTimePayment.toFixed(decimal_points));
    calculateTotalEarning();
}

// Calculate over time payment on change of hours or rate
overTimeHoursInput.on('change', calculateOverTimePayment);
overTimeRateInput.on('change', calculateOverTimePayment);

// Function to calculate per-day payment
function calculatePerDayPayment() {
    var basicSalary = parseFloat(basicSalaryInput.val()) || 0;
    var workingDays = parseFloat(workingDaysInput.val()) || 0;
    perDayPayment = basicSalary / workingDays;
    perDayPayment = perDayPayment.toFixed(decimal_points);
    calculateLeaveDeduction();
    calculatePaidDays();
}

// Calculate per-day payment on change of basic salary or working days
basicSalaryInput.on('change', calculatePerDayPayment);
workingDaysInput.on('change', calculatePerDayPayment);

// Function to calculate leave deduction
function calculateLeaveDeduction() {
    var lopDays = parseFloat(lopDaysInput.val()) || 0;
    var leaveDeduction = perDayPayment * lopDays;
    leaveDeductionInput.val(leaveDeduction.toFixed(decimal_points));
    calculateTotalEarning();
}

// Function to calculate paid days (working days minus LOP days)
function calculatePaidDays() {
    var workingDays = parseFloat(workingDaysInput.val()) || 0;
    var lopDays = parseFloat(lopDaysInput.val()) || 0;
    var paidDays = workingDays - lopDays;
    paidDaysInput.val(paidDays);
}

// Function to calculate total earnings based on inputs
function calculateTotalEarning() {
    var basicSalary = parseFloat(basicSalaryInput.val()) || 0;
    var bonus = parseFloat(bonusInput.val()) || 0;
    var incentives = parseFloat(incentivesInput.val()) || 0;
    var leaveDeduction = parseFloat(leaveDeductionInput.val()) || 0;
    var overTimePayment = parseFloat(overTimePaymentInput.val()) || 0;
    var totalEarning = basicSalary + bonus + incentives - leaveDeduction + overTimePayment;

    // Update the Total Earnings field
    $('#total_earning').text(totalEarning.toFixed(decimal_points));

    // Calculate Net Payable
    calculateNetPayable(totalEarning);
}

// Function to calculate net payable based on inputs
function calculateNetPayable(totalEarning) {
    var netPayable = totalEarning;
    $('#net_payable').text(netPayable.toFixed(decimal_points));
}

// Attach change event listeners to all related input fields
$('#basic_salary, #working_days, #lop_days, #bonus, #incentives').on('change', function () {
    calculatePerDayPayment();
});

// Initially, calculate the values based on the default values
calculateOverTimePayment();
calculatePerDayPayment();
calculateLeaveDeduction();
calculatePaidDays();
calculateTotalEarning();



// });

$('#allowance_id').on('change', function () {
    var id = $(this).val();
    if (id != '') {
        $.ajax({
            url: '/allowances/get/' + id,
            type: 'get',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
            },
            dataType: 'json',
            success: function (response) {
                $('#allowance_0_title').val(response.allowance.title);
                $('#allowance_0_amount').val(parseFloat(response.allowance.amount).toFixed(decimal_points));
            },

        });
    } else {
        $('#allowance_0_title').val('');
        $('#allowance_0_amount').val('');
    }

});

$('.add-allowance').on('click', function (e) {
    e.preventDefault();
    var html = '';
    var title = $("#allowance_0_title").val();
    if (title != '') {
        var allowance_id = $("#allowance_id").val();
        var allowance_ids = $("#allowance_ids").val();

        allowance_ids = allowance_ids.split(',');

        var exists = allowance_ids.includes(allowance_id);

        if (!exists) {
            allowance_count++
            allowance_ids = allowance_ids.toString();
            if (allowance_ids != '') {
                allowance_ids = allowance_ids + ',' + allowance_id;
            } else {
                allowance_ids = allowance_id;
            }
            $("#allowance_ids").val(allowance_ids)
            var amount = $("#allowance_0_amount").val();
            html = '<div class="payslip-allowance"><div class="d-flex">' +
                '<input type="hidden" id=allowance_' + allowance_count + ' name="allowances[]">' +
                '<div class="mb-3 col-md-6 mx-1">' +
                '<input type="text" id="allowance_' + allowance_count + '_title" class="form-control" placeholder="Allowance" readonly>' +
                '</div>' +
                '<div class="mb-3 col-md-4 mx-1">' +
                '<input type="number" id="allowance_' + allowance_count + '_amount" class="form-control" placeholder="Amount" disabled>' +
                '</div>' +
                '<div class="mb-3 col-md-1 mx-1">' +
                '<button type="button" class="btn btn-sm btn-danger remove-allowance" data-count="' + allowance_count + '"><i class="bx bx-trash"></i></button>' +
                '</div>' +
                '</div></div>';

            $('#payslip-allowances').append(html);
            $('#allowance_' + allowance_count).val(allowance_id);
            $('#allowance_' + allowance_count + '_title').val(title);
            $('#allowance_' + allowance_count + '_amount').val(parseFloat(amount).toFixed(decimal_points));

            // Update Total Earnings and Net Pay when an allowance is added
            var total_allowance = parseFloat($('#total_allowance').text());
            var total_earning = parseFloat($('#total_earning').text());
            var net_pay = parseFloat($('#net_payable').text());

            if (!isNaN(total_allowance) && !isNaN(total_earning) && !isNaN(net_pay)) {
                total_allowance += parseFloat(amount);
                total_earning += parseFloat(amount);
                net_pay += parseFloat(amount);

                $('#total_allowance').text(total_allowance.toFixed(decimal_points));
                $('#hidden_total_allowance').val(total_allowance.toFixed(decimal_points));
                $('#total_earning').text(total_earning.toFixed(decimal_points));
                $('#total_earnings').val(total_earning.toFixed(decimal_points));
                $('#net_payable').text(net_pay.toFixed(decimal_points));
            }

            $("#allowance_0_title").val('');
            $("#allowance_0_amount").val('');
            $('#allowance_id').val('');

        } else {
            toastr.error('Allowance already added.');
        }

    } else {
        toastr.error('Please choose allowance.');
    }

});



$(document).on('click', '.remove-allowance', function (e) {
    e.preventDefault();
    var count = $(this).data('count');
    var allowance_id = $("#allowance_" + count).val();
    var amount = parseFloat($("#allowance_" + count + "_amount").val());
    var allowance_ids = $("#allowance_ids").val().split(','); // Split the string into an array
    var index = $.inArray(allowance_id.toString(), allowance_ids);
    if (index !== -1) {
        // Remove the allowance_id from the array
        allowance_ids.splice(index, 1);
        // Update the #allowance_ids input value with the modified string
        $("#allowance_ids").val(allowance_ids.join(',')); // Join the array back into a string
    }

    var total_allowance = parseFloat($('#total_allowance').text());
    total_allowance = total_allowance - amount;

    if (isNaN(total_allowance) || total_allowance < 0) {
        total_allowance = 0;
    }

    total_allowance = total_allowance.toFixed(decimal_points);
    $('#total_allowance').text(total_allowance);
    $('#hidden_total_allowance').val(total_allowance);

    var total_earning = parseFloat($('#total_earning').text());
    total_earning = total_earning - amount;
    total_earning = total_earning.toFixed(decimal_points);
    $('#total_earning').text(total_earning);
    $('#total_earnings').val(total_earning);

    var net_pay = parseFloat($('#net_payable').text());
    net_pay = net_pay - amount;
    net_pay = net_pay.toFixed(decimal_points);
    $('#net_payable').text(net_pay);

    $(this).closest('.payslip-allowance').remove();
});



$('#deduction_id').on('change', function () {
    var id = $(this).val();
    if (id != '') {
        $.ajax({
            url: '/deductions/get/' + id,
            type: 'get',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
            },
            dataType: 'json',
            success: function (response) {
                $('#deduction_0_title').val(response.deduction.title);
                $('#deduction_0_type').val(response.deduction.type);
                $('#deduction_0_amount').val(parseFloat(response.deduction.amount).toFixed(decimal_points));
                $('#deduction_0_percentage').val(response.deduction.percentage);
            },

        });
    } else {
        $('#deduction_0_title').val('');
        $('#deduction_0_amount').val('');
        $('#deduction_0_percentage').val('');
        $('#deduction_0_type').val('');
    }

});

$(document).on('click', '.add-deduction', function (e) {
    e.preventDefault();
    var html = '';
    var title = $("#deduction_0_title").val();
    if (title != '') {
        var deduction_id = $("#deduction_id").val();
        var deduction_ids = $("#deduction_ids").val();

        deduction_ids = deduction_ids.split(',');

        var exists = deduction_ids.includes(deduction_id);

        if (!exists) {
            deduction_count++;
            deduction_ids = deduction_ids.toString();
            if (deduction_ids != '') {
                deduction_ids = deduction_ids + ',' + deduction_id;
            }
            else {
                deduction_ids = deduction_id;
            }
            $("#deduction_ids").val(deduction_ids); // Update the hidden input value

            var amount = $("#deduction_0_amount").val();
            var percentage = $("#deduction_0_percentage").val();
            var type = $("#deduction_0_type").val();

            html = '<div class="payslip-deduction"><div class="d-flex">' +
                '<input type="hidden" id="deduction_' + deduction_count + '" name="deductions[]">' +
                '<div class="mb-3 col-md-5 mx-1">' +
                '<input type="text" id="deduction_' + deduction_count + '_title" class="form-control" placeholder="Deduction" readonly>' +
                '</div>' +
                '<input type="hidden" id="deduction_' + deduction_count + '_type"></input>' +
                '<div class="mb-3 col-md-3 mx-1">' +
                '<input type="number" id="deduction_' + deduction_count + '_amount" class="form-control" placeholder="Amount" disabled>' +
                '</div>' +
                '<div class="mb-3 col-md-3 mx-1">' +
                '<input type="number" id="deduction_' + deduction_count + '_percentage" class="form-control" placeholder="Percentage" disabled>' +
                '</div>' +
                '<div class="mb-3 col-md-1 mx-1">' +
                '<button type="button" class="btn btn-sm btn-danger remove-deduction" data-count="' + deduction_count + '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="bx bx-trash"></i></button>' +
                '</div>' +
                '</div></div>';

            $('#payslip-deductions').append(html);
            $('#deduction_' + deduction_count).val(deduction_id);
            $('#deduction_' + deduction_count + '_title').val(title);
            $('#deduction_' + deduction_count + '_type').val(type);

            var total_deduction = parseFloat($('#total_deduction').text());
            var total_earning = parseFloat($('#total_earning').text());
            var net_pay = parseFloat($('#net_payable').text());
            if (type == 'amount') {
                total_deduction = +total_deduction + +amount;
                total_earning = total_earning - parseFloat(amount);
                net_pay = net_pay - parseFloat(amount);
                $('#deduction_'+deduction_count+'_amount').val(parseFloat(amount).toFixed(decimal_points));
                var deduction_amount = $('#deduction_0_amount').val();

            } else {
                $('#deduction_' + deduction_count + '_percentage').val(percentage);
                var net_payable = parseFloat($('#net_payable').text()) || 0;
                var deduction_amount = (percentage / 100) * net_payable;
                total_deduction = +total_deduction + +deduction_amount;
            }
            total_deduction = parseFloat(total_deduction).toFixed(decimal_points);
            $('#total_deduction').text(total_deduction);
            $('#hidden_total_deductions').val(total_deduction);

            var total_earning = parseFloat($('#total_earning').text());
            total_earning = total_earning - deduction_amount;
            total_earning = total_earning.toFixed(decimal_points);
            $('#total_earning').text(total_earning);
            $('#total_earnings').val(total_earning);

            var net_pay = parseFloat($('#net_payable').text());
            net_pay = net_pay - deduction_amount;
            net_pay = net_pay.toFixed(decimal_points);            
            $('#net_payable').text(net_pay);

            $("#deduction_0_title").val('');
            $("#deduction_0_amount").val('');
            $("#deduction_0_percentage").val('');
            $("#deduction_0_type").val('');
            $('#deduction_id').val('');

        } else {
            toastr.error('Deduction already added.');
        }

    } else {
        toastr.error('Please choose deduction.');
    }

});


$(document).on('click', '.remove-deduction', function (e) {
    e.preventDefault();
    var count = $(this).data('count');
    var deduction_ids = $("#deduction_ids").val();
    var deduction_id = $("#deduction_" + count).val();
    var type = $("#deduction_" + count + "_type").val();
    var deduction_amount = 0;

    if (type == 'amount') {
        deduction_amount = parseFloat($("#deduction_" + count + "_amount").val()) || 0;
    } else {
        var percentage = parseFloat($("#deduction_" + count + "_percentage").val()) || 0;
        var net_payable = parseFloat($('#net_payable').text()) || 0;
        deduction_amount = (percentage / 100) * net_payable;
    }

    var total_deduction = parseFloat($('#total_deduction').text()) || 0;
    total_deduction = total_deduction - deduction_amount;
    total_deduction = total_deduction.toFixed(decimal_points);

    $('#total_deduction').text(total_deduction);
    $('#hidden_total_deductions').val(total_deduction);

    var total_earning = parseFloat($('#total_earning').text()) || 0;
    total_earning = total_earning + deduction_amount;
    total_earning = total_earning.toFixed(decimal_points);

    $('#total_earning').text(total_earning);
    $('#total_earnings').val(total_earning);

    var net_pay = parseFloat($('#net_payable').text()) || 0;
    net_pay = net_pay + deduction_amount;
    net_pay = net_pay.toFixed(decimal_points);
    
    $('#net_payable').text(net_pay);

    // Remove the deduction from the hidden input
    var deduction_ids = $("#deduction_ids").val().split(','); // Split the string into an array
    var index = $.inArray(deduction_id.toString(), deduction_ids);
    if (index !== -1) {
        // Remove the allowance_id from the array
        deduction_ids.splice(index, 1);
        // Update the #allowance_ids input value with the modified string
        $("#deduction_ids").val(deduction_ids.join(',')); // Join the array back into a string
    }

    // Remove the deduction element from the DOM
    $(this).closest('.payslip-deduction').remove();
});