// const searchFor = () => {
//     let searchForData = document.getElementById("search-all");

//     let searchReult = document.getElementById('searchAll-list');

//     if (searchForData.value == "") {
//         searchReult.style.display = "none";
//         // document.getElementById("search-all-form").reset();
//         // event.preventDefault();
//     }

//     if (searchForData.value.length > 5) {
//         let searchAllUrl = `ajax/search-for-all.ajax.php?searchKey=${searchForData.value}`;
//         xmlhttp.open("GET", searchAllUrl, false);
//         xmlhttp.send(null);
//         // let response = xmlhttp.responseText;
//         searchReult.style.display = "block";
//     }

//     xmlhttp.onreadystatechange = function () {
//         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
//             searchReult.innerHTML = xmlhttp.responseText;
//             // console.log(xmlhttp.responseText);
//         }
//     };
// }


const searchFor = () => {
    let searchForData = document.getElementById("search-all");
    let searchResult = document.getElementById('searchAll-list');

    if (searchForData.value == "") {
        searchResult.style.display = "none";
        return;
    }

    if (searchForData.value.length > 2) {
        let searchAllUrl = `ajax/search-for-all.ajax.php?searchKey=${searchForData.value}`;
        let xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                searchResult.innerHTML = xmlhttp.responseText;
                searchResult.style.display = "block";
            }
        };

        xmlhttp.open("GET", searchAllUrl, true);
        xmlhttp.send();
    }
    console.log(xmlhttp.responseText);
}

const getDtls = (key, id) =>{
    console.log(key);
    console.log(id);
   
    if(key == 'appointments'){
        window.location.href = `appointments.php?search=${'appointment_search'}&searchKey=${id}`;
    }

    if(key == 'patient_details'){
        window.location.href = `patients.php?search=${'search-by-id-name'}&searchKey=${id}`;
    }


    if(key == 'lab_billing'){
        window.location.href = `test-appointments.php?search=${'appointment-search'}&searchKey=${id}`;
    }


    if(key == 'stock_in'){
        window.location.href = `stock-in-details.php?search=${'stock_in'}&searchKey=${id}`;
    }


    if(key == 'stock_out'){
        window.location.href = `stock-in-details.php?search=${'stock_out'}&searchKey=${id}`;
    }


}