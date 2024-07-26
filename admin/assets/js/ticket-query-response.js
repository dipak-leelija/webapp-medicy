
const masterTable = document.getElementById('master-table');
const masterTicketNo = document.getElementById('master-ticket-no');
const userAdmin = document.getElementById('user-id');
const responseTableName = document.getElementById('respnse-table-name');

const ticketNo = document.getElementById('ticket-no');
const adminUsername = document.getElementById('user-name');
const querySender = document.getElementById('msg-sender');
const email = document.getElementById('email');
const contact = document.getElementById('contact-no');

const msgTitle = document.getElementById('msg-title');
const documentInput = document.getElementById('document-data');
const queryResponse = document.getElementById('query-responce');

const masterUrl = document.getElementById('master-url');


function responseOfQuery(t) {
    /*if (masterTicketNo.value === '') {
        alert('Master ticket no not found');
        return;
    }

    if (userAdmin.value === '') {
        alert('Admin data not found');
        return;
    }

    if (table.value === '') {
        alert('Table not found');
        return;
    }

    if (ticketNo.value === '') {
        alert('Ticket number not found');
        return;
    }

    if (adminUsername.value === '') {
        alert('Admin username not found');
        return;
    }

    if (querySender.value === '') {
        alert('Query sender not found');
        return;
    }

    if (email.value === '') {
        alert('Email not found');
        return;
    }

    if (msgTitle.value === '') {
        alert('Message title not found');
        return;
    }

    if (contact.value === '') {
        alert('Contact data not found');
        return;
    }

    let documentData = '';
    if (documentInput.value != '') {
        documentData = documentInput.value; // Get the selected file
    }

    if(queryResponse.value === ''){
        alert('must add a response');
        return;
    }*/

    let formData = new FormData();

    formData.append('masterTable', masterTable.value);
    formData.append('masterTicektNo', masterTicketNo.value);
    formData.append('adminId', userAdmin.value);
    formData.append('responseTableName', responseTableName.value);
    formData.append('ticketNo', ticketNo.value);
    formData.append('querySender', querySender.value);
    formData.append('adminUsername', adminUsername.value);
    formData.append('querySender', querySender.value);
    formData.append('email', email.value);
    formData.append('contact', contact.value);
    formData.append('msgTitle', msgTitle);
    formData.append('document',documentInput.value);
    formData.append('response',queryResponse.value)

    $.ajax({
        url: 'ajax/ticket-query-response.ajax.php',
        type: 'POST',
        data: formData,
        contentType: false, // Prevent jQuery from setting the content type
        processData: false, // Prevent jQuery from processing the data
        success: function(response) {
            console.log(response);
            var jsonResponse = JSON.parse(response);
                if (jsonResponse.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: jsonResponse.message
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'requests.php'; 
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: jsonResponse.message
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href ='requests.php';
                        }
                    });;
                }

            // Clear all fields
            masterTable.value = '';
            masterTicketNo.value = '';
            userAdmin.value = '';
            responseTableName.value = '';
            ticketNo.value = '';
            adminUsername.value = '';
            querySender.value = '';
            email.value = '';
            contact.value = '';
            msgTitle.value = '';
            documentInput.value = '';
            queryResponse.value = '';
        },
        
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while submitting the form.'
            });
        }
    });
}

