

// function displayImage() {
//     let imageType = image.type;
//     // console.log(imageType);

//     let validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];

//     if (validExtensions.includes(imageType)) {
//         let fileReader = new FileReader();

//         // return upload(image);

//         fileReader.onload = () => {
//             let fileUrl = fileReader.result;
//             // console.log(fileUrl);

//             let imgTag = `<img src="${fileUrl}" alt="">`;
//             dragArea.innerHTML = imgTag;
//         };
//         fileReader.readAsDataURL(image);
//     } else {
//         alert("Please Upload JPEG, JPG or an PNG Image.");
//     }
//     // console.log("Image is Droped");
// }


/*##########################################################################################################################################
#                                                                                                                                          #
#                                                 BACK IMAGE                                   #
#                                                                                                                                          #
##########################################################################################################################################*/



////////////////// IMGAE UPLOAD CONTROL AREA \\\\\\\\\\\\\\\\\


let fileInput = document.getElementById('img-file-input');
let imageContainer = document.getElementById('images');
let numOFFiles = document.getElementById('num-of-files');

function updateFilesCount() {
    numOFFiles.textContent = `${imageContainer.childElementCount} Files Selected`;
}

function preview() {
    imageContainer.innerHTML = '';
    numOFFiles.textContent = `${fileInput.files.length} Files Selected`;

    for (let i of fileInput.files) {
        let reader = new FileReader();
        let figure = document.createElement("figure");
        figure.className = 'figure-style';
        // console.log(figure);
        let figCap = document.createElement("figcaption");
        // console.log(figCap);
        let radioButton = document.createElement('input');
        radioButton.className = 'radio-button';
        // console.log(radioButton);
        let closeButton = document.createElement('button');
        closeButton.innerHTML = '&times;'; // Close button text (×)
        closeButton.className = 'close-button';

        radioButton.type = 'radio';
        radioButton.name = 'priority-group';
        radioButton.id = 'radio' + (imageContainer.childElementCount + 1);

        closeButton.type = 'button';
        closeButton.id = 'close' + (imageContainer.childElementCount + 1);

        figCap.innerText = i.name;
        figCap.style.display = 'none';
        figure.appendChild(figCap);
        figure.appendChild(radioButton);
        figure.appendChild(closeButton);

        closeButton.onclick = function () {
            figure.remove();
            updateFilesCount();
        };

        radioButton.onclick = function () {
            console.log(`Radio button  clicked`);
        };

        reader.onload = () => {
            let img = document.createElement("img");
            img.setAttribute("src", reader.result);
            figure.insertBefore(img, figCap);
            figure.insertBefore(figCap, radioButton);
            figure.insertBefore(figCap, closeButton);
        };

        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}


////////////////////////////// manufacturur search control \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\



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
