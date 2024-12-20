/**
 * Dashboard Analytics
 */
'use strict';

(function () {
    let cardColor, headingColor, axisColor, shadeColor, borderColor;

    cardColor = config.colors.white;
    headingColor = config.colors.headingColor;
    axisColor = config.colors.axisColor;
    borderColor = config.colors.borderColor;

    // Projects Statistics Chart
    var options = {
        series: project_data,
        colors: bg_colors,
        labels: labels,
        chart: {
            type: 'donut',
            height: 300,
            width: 300,
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },

            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#projectStatisticsChart"), options);
    chart.render();

    // Tasks Statistics Chart

    var options = {
        labels: labels,
        series: task_data,
        colors: bg_colors,
        chart: {
            type: 'donut',
            height: 300,
            width: 300,
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },

            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#taskStatisticsChart"), options);
    chart.render();


    // Todos Statistics Chart
    var options = {
        labels: [done, pending],
        series: todo_data,
        colors: [config.colors.success, config.colors.danger],
        chart: {
            type: 'donut',
            height: 300,
            width: 300,
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },

            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#todoStatisticsChart"), options);
    chart.render();
})();

window.icons = {
    refresh: 'bx-refresh',
    toggleOn: 'bx-toggle-right',
    toggleOff: 'bx-toggle-left'
}

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical" ></i>'
}


function queryParamsUpcomingBirthdays(p) {
    return {
        "upcoming_days": $('#upcoming_days_bd').val(),
        "user_id": $('#birthday_user_filter').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

$('#upcoming_days_birthday_filter').on('click', function (e) {
    e.preventDefault();
    $('#birthdays_table').bootstrapTable('refresh');


})

$('#birthday_user_filter').on('change', function (e) {
    e.preventDefault();
    $('#birthdays_table').bootstrapTable('refresh');


})


function queryParamsUpcomingWa(p) {
    return {
        "upcoming_days": $('#upcoming_days_wa').val(),
        "user_id": $('#wa_user_filter').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

$('#upcoming_days_wa_filter').on('click', function (e) {
    e.preventDefault();
    $('#wa_table').bootstrapTable('refresh');


})

$('#wa_user_filter').on('change', function (e) {
    e.preventDefault();
    $('#wa_table').bootstrapTable('refresh');

})

function queryParamsMol(p) {
    return {
        "upcoming_days": $('#upcoming_days_mol').val(),
        "user_id": $('#mol_user_filter').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

$('#upcoming_days_mol_filter').on('click', function (e) {
    e.preventDefault();
    $('#mol_table').bootstrapTable('refresh');

})

$('#mol_user_filter').on('change', function (e) {
    e.preventDefault();
    $('#mol_table').bootstrapTable('refresh');

})