const xmlhttp = new XMLHttpRequest();


//  file upload function (image/pdf)
function takeInputFile(fileInput, fileShowDivId) {
    const file = fileInput.files[0];

    if (file) {
        const filePreview = document.getElementById(fileShowDivId);
        filePreview.innerHTML = ''; // Clear any existing content

        const reader = new FileReader();
        reader.onload = function(e) {
            if (file.type.includes('image')) {
                filePreview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 100%; max-height: 12rem;" ><i class="" style="position: absolute; align-items: center; width: auto; max-width: 60%; height: 12rem;"></i>';
            } else if (file.type === 'application/pdf') {
                filePreview.innerHTML = '<embed src="' + e.target.result + '" style="max-width: 100%; max-height: 12rem;" class="fas fa-upload"><i class="" style="position: absolute; align-items: center; width: auto; max-width: 60%; height: 12rem;"></i>';
            } else {
                filePreview.innerHTML = '<p>Select PDF or JPEG/JPG/PNG files.</p>';
                document.getElementById(fileInput.id).value = '';
                document.getElementById(fileShowDivId).innerHTML = '';
                alert('Select PDF or JPEG/JPG/PNG files.')
            }
        };
        reader.readAsDataURL(file);
    }
}


function requestSubmit(t) {
    let table = '';
    let fileInput = '';
    let currentUser = '';
    let email = '';
    let contact = '';
    let title = '';
    let message = '';
    let documentShow = '';
    const formFlag = '1';

    if (t.id === 'ticket-submit') {
        table = 'ticket_request';
        fileInput = 'fileInput1';
        currentUser = 'current-usr1';
        email = 'email1';
        contact = 'mobile-number1';
        title = 'title1';
        message = 'ticket-description1';
        documentShow = 'document-show-1';
    }

    if (t.id === 'query-submit') {
        table = 'query_request';
        fileInput = 'fileInput2';
        currentUser = 'current-usr2';
        email = 'email2';
        contact = 'mobile-number2';
        title = 'title2';
        message = 'ticket-description2';
        documentShow = 'document-show-2';
    }

    let user = document.getElementById(currentUser);
    let mail = document.getElementById(email);
    let phno = document.getElementById(contact);
    let msgTitle = document.getElementById(title);
    let msg = document.getElementById(message);
    let documentShowDiv = document.getElementById(documentShow);

    if (user.value === '') {
        alert('user not found');
        return;
    }

    if (mail.value === '') {
        alert('email not found');
        return;
    }

    if (phno.value === '') {
        alert('contact details not found');
        return;
    }

    if (msgTitle.value === '') {
        alert('msg title not found');
        return;
    }

    if (msg.value === '') {
        alert('msg not found');
        return;
    }

    let inputFile = document.getElementById(fileInput);
    let formData = new FormData();

    if (inputFile.value !== '') {
        let file = inputFile.files[0]; // Get the selected file
        formData.append('file', file);
    }

    formData.append('table', table);
    formData.append('user', user.value);
    formData.append('email', mail.value);
    formData.append('contact', phno.value);
    formData.append('title', msgTitle.value);
    formData.append('message', msg.value);
    formData.append('formFlag',formFlag);

    $.ajax({
        url: 'ajax/ticket-query-submit.ajax.php', 
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            var jsonResponse = JSON.parse(response);
            // console.log(jsonResponse);
            if (jsonResponse.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: jsonResponse.message
                });
                // window.location.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: jsonResponse.message
                });
            }

            
            // Clear all fields
            user.value = '';
            msgTitle.value = '';
            msg.value = '';

            inputFile.value = '';
            let clone = inputFile.cloneNode(true);
            inputFile.parentNode.replaceChild(clone, inputFile);
            documentShowDiv.innerHTML = '';
        },
    });
}




function reQuery(t){
    
    const masterTable = document.getElementById('master-table-name');
    const responseTable = document.getElementById('response-table-name');
    const masterTicketNumber = document.getElementById('master-ticket-number');
    const msgTitle = document.getElementById('title');
    const newQuery = document.getElementById('re-query');

    console.log(newQuery.value);

    const formFlag = '2';

    let formData = new FormData();

    formData.append('masterTable', masterTable.value);
    formData.append('requestTable', responseTable.value);
    formData.append('masterTicket', masterTicketNumber.value);
    formData.append('msgTitle', msgTitle.value);
    formData.append('newQuery', newQuery.value);
    formData.append('formFlag', formFlag);

    $.ajax({
        url: 'ajax/ticket-query-submit.ajax.php', 
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false, 
        success: function(response) {
            try {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: jsonResponse.message
                    }).then(function() {
                        window.location.href = 'ticket-details.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: jsonResponse.message
                    }).then(function() {
                        window.location.reload();
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Invalid response from the server.'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred: ' + error
            });
        }
    });
    
}