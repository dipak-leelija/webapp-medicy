// const xmlhttp = new XMLHttpRequest();

// // data input
// const user1 = document.getElementById('current-usr1');
// const email1 = document.getElementById('email1');
// const contact1 = document.getElementById('mobile-number1');
// const queryDescription1 = document.getElementById('ticket-description1');
// const document1 = document.getElementById('fileInput1');

// const user2 = document.getElementById('current-usr2');
// const email2 = document.getElementById('email2');
// const contact2 = document.getElementById('mobile-number2');
// const queryDescription2 = document.getElementById('ticket-description2');
// const document2 = document.getElementById('fileInput2');

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




// function requestSubmit(t){
//     let userId = '';
//     let email = '';
//     let contact = '';
//     let query = '';
//     let document = '';

//     if(t.id == 'ticket-submit'){
//         userId = user1.value;
//         email = email1.value;
//         contact = contact1.value;
//         query = queryDescription1.value;
//         document = document1.value;

//         console.log(userId);
//         console.log(email);
//         console.log(contact);
//         console.log(query);
//         console.log(document);
//     }

//     // if(t.id == 'query-submit'){

//     // }
// }

