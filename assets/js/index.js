const searchFor = () =>{
    let searchForData = document.getElementById("search-all");

    let searchAllUrl = `ajax/search-for-all.ajax.php?searchKey=${searchForData.value}`;
    xmlhttp.open("GET", searchAllUrl , false);
    xmlhttp.send(null);
    let response = xmlhttp.responseText;

    console.log(response);


    // if (searchForData.value == "") {
    //     document.getElementById("search-for").style.display = "none";
    // }

    // if (searchForData.value.length > 2) {
    //     if (searchForData.value != "") {
    //         document.getElementById("search-for").style.display = "block";
    //     }
    // }

    // xmlhttp.onreadystatechange = function () {
    //     if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
    //         searchReult.innerHTML = xmlhttp.responseText;
    //     }
    // };

}