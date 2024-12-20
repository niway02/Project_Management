'use strict';
var elements = [];

// Loop through the statusArray and generate elements
for (var i = 0; i < statusArray.length; i++) {
    var sts = statusArray[i];    
    var element = document.getElementById(sts.slug);

    // Check if the element exists before adding it to the elements array
    if (element) {
        elements.push(element);
    }
}

$(function () {
    dragula(elements, {
        revertOnSpill: true
    })
        .on('drop', function (el, target, source) {
            // Get the task ID and new status
            var taskId = el.getAttribute('data-task-id');
            var newStatus = target.getAttribute('data-status');

            // Make an AJAX call to update the task status
            $.ajax({
                method: "PUT",
                url: "/tasks/" + taskId + "/update-status/" + newStatus,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'flash_message_only': 1,
                },
                success: function (response) {
                    if (response.error == false) {
                        toastr.success(response.message); // show a success message
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });

});