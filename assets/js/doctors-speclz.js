
//////////////////// set specialization /////////////////////
const docSpecializationInput = document.getElementById("doc-speclz");
const dropdown = document.getElementsByClassName("c-dropdown")[0];

docSpecializationInput.addEventListener("click", ()=>{
    dropdown.style.display = "block";
});

document.addEventListener("click", (event) => {
    if (!docSpecializationInput.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = "none";
    }
});

document.addEventListener("blur", (event) => {
    if (!dropdown.contains(event.relatedTarget)) {
        setTimeout(() => {
            dropdown.style.display = "none";
        }, 100);
    }
});

docSpecializationInput.addEventListener("keyup", () => {
    let list = document.getElementsByClassName('lists')[0];

    if (docSpecializationInput.value.length > 2) {
        // console.log('check spe data : '+docSpecializationInput.value);
        var docSpecializationUrl = 'doc-specialization-list-view.ajax.php?match=' + docSpecializationInput.value;
        request.open("GET", docSpecializationUrl, false);
        request.send(null);
        // console.log();
        list.innerHTML = request.responseText
    } else if (docSpecializationInput.value == '') {
        var docSpecializationUrl = 'doc-specialization-list-view.ajax.php?match=all';
        request.open("GET", docSpecializationUrl, false);
        request.send(null);
        // console.log();
        list.innerHTML = request.responseText
    } else {
        list.innerHTML = '';
    }
// console.log("check return : "+request.responseText);
});

const setDocSpecialization = (t) => {
    let specializationId = t.id.trim();
    let specializationName = t.innerHTML.trim();

    document.getElementById("doc-speclz-id").value = specializationId;
    document.getElementById("doc-speclz").value = specializationName;
    // document.getElementById("distributor-id").value = distributirName;

    document.getElementsByClassName("c-dropdown")[0].style.display = "none";
}


const addDocSpecialization = () => {

    // var parentLocation = window.location.origin + window.location.pathname;

    // $.ajax({
    //     url: "components/distributor-add.php",
    //     type: "POST",
    //     data: { urlData: parentLocation },
    //     success: function (response) {
    //         let body = document.querySelector('.add-distributor');
    //         body.innerHTML = response;
    //     },
    //     error: function (error) {
    //         console.error("Error: ", error);
    //     }
    // });

    window.location.href = '<?php echo LOCAL_DIR ?>doctor-specialization.php';
}