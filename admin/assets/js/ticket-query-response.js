
const masterTable = document.getElementById('master-table');
const responseTable = document.getElementById('respnse-table-name');
const adminId = document.getElementById('user-id');
const ticketNo = document.getElementById('ticket-no');

const adminUsername = document.getElementById('user-name');
const msgSender = document.getElementById('msg-sender');
const email = document.getElementById('email');
const msgTitle = document.getElementById('msg-title');
const contact = document.getElementById('contact-no');

const inputedDocument = document.getElementById('fileInput1');
const oldFileData = document.getElementById('db-file-data-holder');

const queryResponse = document.getElementById('query-responce');
const masterUrl = document.getElementById('master-url');


function responseOfQuery(t) {
    let fileName ='';
    let filePath ='';

    if (masterTable.value === '') {
        Swal.fire('Error', 'Master Table not found', 'error');
        return;
    }
    if (responseTable.value === '') {
        Swal.fire('Error', 'Response table not found', 'error');
        return;
    }
    if (adminId.value === '') {
        Swal.fire('Error', 'Admin id not found', 'error');
        return;
    }
    if (ticketNo.value === '') {
        Swal.fire('Error', 'Admin id not found', 'error');
        return;
    }
    if (adminUsername.value === '') {
        Swal.fire('Error', 'Admin Username not found', 'error');
        return;
    }
    if (msgSender.value === '') {
        Swal.fire('Error', 'Message sender not found', 'error');
        return;
    }
    if (email.value === '') {
        Swal.fire('Error', 'Email not found', 'error');
        return;
    }
    if (msgTitle.value === '') {
        Swal.fire('Error', 'Message Title not found', 'error');
        return;
    }
    if (contact.value === '') { 
        Swal.fire('Error', 'User Contact not found', 'error');
        return;
    }
    if (queryResponse.value === '') {
        Swal.fire('Error', 'Enter Response', 'error');
        return;
    }

    let formData = new FormData();
    if (inputedDocument.value !== '') {
        let file = inputedDocument.files[0]; // Get the selected file
        fileName = file.name;
        filePath = inputedDocument.value;
    }else{
        fileName    = oldFileData.value;
        filePath    = '';
    }

    formData.append('masterTable', masterTable.value);
    formData.append('responseTable', responseTable.value);
    formData.append('adminId', adminId.value);
    formData.append('ticketNo', ticketNo.value);
    formData.append('adminUsername', adminUsername.value);
    formData.append('msgSender', msgSender.value);
    formData.append('email', email.value);
    formData.append('msgTitle', msgTitle.value);
    formData.append('contact', contact.value);
    formData.append('queryResponse', queryResponse.value);
 
    formData.append('fileName',fileName);
    formData.append('filePath',filePath);

    $.ajax({
        url: 'ajax/ticket-query-response.ajax.php',
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false, 
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
            responseTable.value = '';
            adminId.value = '';
            adminUsername.value = '';
            msgSender.value = '';
            email.value = '';
            msgTitle.value = '';
            contact.value = '';
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