// const dragArea = document.querySelector('.image-area');
// const dragText = document.querySelector('.upload-img-span1');

// let browse = document.querySelector('.browse');
// let input = document.getElementById("product-image");
// // let input  = document.querySelector('input');
// let image;

// browse.onclick = () => {
//     input.click();
// };

// //when browse
// input.addEventListener('change', function() {
//     image = this.files[0];
//     dragArea.classList.add('activeted');
//     displayImage();
// });

// //when image is inside tha image area
// dragArea.addEventListener('dragover', (event) => {
//     event.preventDefault();
//     dragText.textContent = 'Release to Upload';
//     dragArea.classList.add('activeted');
//     // console.log("Image in inside the image area");
// });

// //when Image is dragleave inside the image area
// dragArea.addEventListener('dragleave', () => {
//     // console.log("Image is dragleaved");
//     dragText.textContent = 'Drag & Drop';
//     dragArea.classList.remove('activeted');
// });

// //when the image is droped in image area
// dragArea.addEventListener('drop', (event) => {
//     event.preventDefault();
//     image = event.dataTransfer.files[0];
//     // console.log(image);
//     displayImage();
// });


function displayImage() {
    let imageType = image.type;
    // console.log(imageType);

    let validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];

    if (validExtensions.includes(imageType)) {
        let fileReader = new FileReader();

        // return upload(image);

        fileReader.onload = () => {
            let fileUrl = fileReader.result;
            // console.log(fileUrl);

            let imgTag = `<img src="${fileUrl}" alt="">`;
            dragArea.innerHTML = imgTag;
        };
        fileReader.readAsDataURL(image);
    } else {
        alert("Please Upload JPEG, JPG or an PNG Image.");
    }
    // console.log("Image is Droped");
}


/*##########################################################################################################################################
#                                                                                                                                          #
#                                                 BACK IMAGE                                   #
#                                                                                                                                          #
##########################################################################################################################################*/



////////////////// IMGAE UPLOAD CONTROL AREA \\\\\\\\\\\\\\\\\
let fileInput = document.getElementById('img-file-input');
let imageContainer = document.getElementById('images');
let numOFFiles = document.getElementById('num-of-files');

function preview() {
    imageContainer.innerHTML = '';
    numOFFiles.textContent = `${fileInput.files.length} Files Selected`;

    let radioCount = 0;
    for (i of fileInput.files) {
        radioCount++;

        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");
        let radioButton = document.createElement('priority-check');
        radioButton.type = 'radio';
        radioButton.name = 'priority-group';
        radioButton.id = 'radio' + radioCount;

        figCap.innerText = i.name;
        figCap.style.display = 'none';
        figure.appendChild(figCap);
        figure.appendChild(radioButton);

        reader.onload = () => {
            let img = document.createElement("img");
            img.setAttribute("src", reader.result);
            figure.insertBefore(img, figCap);
            figure.insertBefore(figCap, radioButton);
        }
        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}

////////////////////////////// manufacturer search control \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

// const manufacturerInput = document.getElementById("manufacturer");
// const manufDropdown = document.getElementsByClassName("c-dropdown")[0];

// manufacturerInput.addEventListener("focus", () => {
//     manufDropdown.style.display = "block";
// });

// document.addEventListener("click", (event) => {
//     // Check if the clicked element is not the input field or the manufDropdown
//     if (!manufacturerInput.contains(event.target) && !manufDropdown.contains(event.target)) {
//         manufDropdown.style.display = "none";
//     }
// });

// document.addEventListener("blur", (event) => {
//     // Check if the element losing focus is not the manufDropdown or its descendants
//     if (!manufDropdown.contains(event.relatedTarget)) {
//         // Delay the hiding to allow the click event to be processed
//         setTimeout(() => {
//             manufDropdown.style.display = "none";
//         }, 100);
//     }
// });



// manufacturerInput.addEventListener("keyup", () => {
//     // Delay the hiding to allow the click event to be processed
//     let list = document.getElementsByClassName('lists')[0];

//     if (manufacturerInput.value.length > 2) {

//         let distributorURL = 'ajax/distributor.list-view.ajax.php?match=' + manufacturerInput.value;
//         request.open("GET", distributorURL, false);
//         request.send(null);
//         // console.log();
//         list.innerHTML = request.responseText
//     } else if (manufacturerInput.value == '') {

//         let distributorURL = 'ajax/distributor.list-view.ajax.php?match=all';
//         request.open("GET", distributorURL, false);
//         request.send(null);
//         // console.log();
//         list.innerHTML = request.responseText
//     } else {

//         list.innerHTML = '';
//     }
// });



// const setmanufacturer = (t) => {
//     let manufId = t.id.trim();
//     let manufName = t.innerHTML.trim();

//     document.getElementById("manufacturer").value = manufName;

//     document.getElementsByClassName("c-dropdown")[0].style.display = "none";
// }


// const addManufacturer = () => {
//     $.ajax({
//         url: "components/manufacturer-add.php",
//         type: "POST",
//         success: function(response) {
//             let body = document.querySelector('.add-manufacturer');
//             body.innerHTML = response;
//         },
//         error: function(error) {
//             console.error("Error: ", error);
//         }
//     });
// }
