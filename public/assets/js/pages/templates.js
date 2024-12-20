'use strict';
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
