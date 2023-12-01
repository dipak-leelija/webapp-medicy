

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
    // updateFilesCount();

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


