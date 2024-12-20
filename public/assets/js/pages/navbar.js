
'use strict';

$(document).ready(function () {
    $('#global-search').select2({
        placeholder: label_search,
        minimumInputLength: 1, // Search initiated on typing the first character
        ajax: {
            url: '/search', // Laravel route
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // Pass the search query
                };
            },
            processResults: function (data) {
                var formattedResults = [];

                for (var module in data.results) {
                    if (data.results.hasOwnProperty(module) && data.results[module].length > 0) {
                        formattedResults.push({
                            text: module.charAt(0).toUpperCase() + module.slice(1), // Capitalize the parent name
                            children: $.map(data.results[module], function (item) {
                                return {
                                    text: item.title,
                                    id: module.toLowerCase() + '/' + item.id,
                                    type: module.toLowerCase() // Add type to data attributes
                                };
                            })
                        });
                    }
                }

                return {
                    results: formattedResults
                };
            },
            cache: true
        }
    });

    $('#global-search').on('select2:select', function (e) {
        var data = e.params.data;
        var moduleType = data.type;

        var redirectUrl;

        // Customize redirection based on module type
        switch (moduleType) {
            case 'projects':
                redirectUrl = '/projects/information/' + data.id.split('/')[1];
                break;
            case 'tasks':
                redirectUrl = '/tasks/information/' + data.id.split('/')[1];
                break;
            case 'meetings':
                redirectUrl = '/meetings';
                break;
            case 'workspaces':
                redirectUrl = '/workspaces';
                break;
            case 'users':
                redirectUrl = '/users/profile/' + data.id.split('/')[1];
                break;
            case 'clients':
                redirectUrl = '/clients/profile/' + data.id.split('/')[1];
                break;
            case 'todos':
                redirectUrl = '/todos';
                break;
            case 'notes':
                redirectUrl = '/notes';
                break;
            default:
                redirectUrl = '/';
                break;
        }

        window.location.href = redirectUrl; // Redirect to customized URL
    });
});
