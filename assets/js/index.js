const xmlhttp = new XMLHttpRequest();
const searchFor = () =>{
    let searchForData = document.getElementById('search-all');
    console.log(searchForData.value);
    // document.getElementById()
    let searchAllUrl = `ajax/search-for-all.ajax.php?searchKey=${searchForData.value}`;
    xmlhttp.open('GET', searchAllUrl , true);
    xmlhttp.send();

    if (input == "") {
        document.getElementById("search-for").style.display = "none";
        parent.window.location.reload();
        event.preventDefault();
    }

    if (searchForData.value.length > 2) {
        if (input != "") {
            document.getElementById("search-for").style.display = "block";
        }
    }

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            searchReult.innerHTML = xmlhttp.responseText;
        }
    };

    console.log('search all result : '+xmlhttp.responseText);
}