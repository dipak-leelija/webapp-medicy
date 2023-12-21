const searchFor = () => {
    let searchForData = document.getElementById("search-all");

    let searchReult = document.getElementById('searchAll-list');

    if (searchForData.value == "") {
        searchReult.style.display = "none";
        // document.getElementById("search-all-form").reset();
        // event.preventDefault();
    }

    if (searchForData.value.length > 5) {
        let searchAllUrl = `ajax/search-for-all.ajax.php?searchKey=${searchForData.value}`;
        xmlhttp.open("GET", searchAllUrl, false);
        xmlhttp.send(null);
        // let response = xmlhttp.responseText;
        searchReult.style.display = "block";
    }

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            searchReult.innerHTML = xmlhttp.responseText;
            // console.log(xmlhttp.responseText);
        }
    };
}

const getDtls = ($key, $id) =>{
    if($key == 'appointments'){
        console.log('app');
    }
}