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
        radioButton.id = 'radio'+radioCount;

        figCap.innerText = i.name;
        figCap.style.display='none';
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

////////////////////////////// UPPY ONLINE PLUGINS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


  