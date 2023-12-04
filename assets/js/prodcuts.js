const xmlhttp = new XMLHttpRequest();

const productsSearch = document.getElementById("prodcut-search");
const productsDropdown = document.getElementsByClassName("c-dropdown")[0];

productsSearch.addEventListener("focus", () => {
    productsDropdown.style.display = "block";
});

document.addEventListener("click", (event) => {
    // Check if the clicked element is not the input field or the manufDropdown
    if (!productsSearch.contains(event.target) && !productsDropdown.contains(event.target)) {
        productsDropdown.style.display = "none";
    }
});

document.addEventListener("blur", (event) => {
    // Check if the element losing focus is not the manufDropdown or its descendants
    if (!productsDropdown.contains(event.relatedTarget)) {
        // Delay the hiding to allow the click event to be processed
        setTimeout(() => {
            productsDropdown.style.display = "none";
        }, 100);
    }
});




productsSearch.addEventListener("keydown", () => {

    // Delay the hiding to allow the click event to be processed
    let list = document.getElementsByClassName('lists')[0];
    let searchVal = document.getElementById("prodcut-search").value;

    if (searchVal.length > 2) {

        let manufURL = `ajax/manufacturer.list-view.ajax.php?match=${searchVal}`;
        xmlhttp.open("GET", manufURL, false);
        xmlhttp.send(null);

        list.innerHTML = xmlhttp.responseText;


    } else if (searchVal == '') {

        console.log("input val blank ",searchVal);

        searchVal = 'all';

        let manufURL = `ajax/manufacturer.list-view.ajax.php?match=${searchVal}`;
        xmlhttp.open("GET", manufURL, false);
        xmlhttp.send(null);
        // console.log();
        list.innerHTML = xmlhttp.responseText;

    } else {

        list.innerHTML = '';
    }
});




const setProduct = (t) => {
    let prodId = t.id.trim();
    let prodName = t.innerHTML.trim();

    document.getElementById("prodcut-search").value = prodName;
    // document.getElementById("manufacturer").value = manufId;
    // document.getElementById("manufacturer").innerHTML = manufName;manufacturer-id

    document.getElementsByClassName("c-dropdown")[0].style.display = "none";
}






// ============== PRODUCT VIEW MODAL OPEN FUNCTION =============

const viewItem = (value) => {
    // console.info(value);
    let url = 'ajax/product-view-modal.ajax.php?id=' + value;
    $(".productModal").html(
        '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
        url + '"></iframe>');
}