// Constant data declaration
const xmlhttp = new XMLHttpRequest();
const allowedUnits = ["tablets", "tablet", "capsules", "capsule"];

// Element selection
const tableBody = document.getElementById('item-body');
const newSellGenerateBill = document.getElementById('new-sell-bill-generate');
newSellGenerateBill.setAttribute("disabled", true);

// Set date
const setDate = new Date();
document.getElementById("bill-date").value = setDate.toISOString().slice(0, 10);
document.getElementById('final-bill-date').value = setDate.toISOString().slice(0, 10);

// Function to get date
const getDate = (date) => {
    document.getElementById("final-bill-date").value = date;
}

// Add new customer modal
const addCustomerModal = () => {
    const url = "ajax/customer.addNew.ajax.php";
    $(".add-customer-modal").html(`<iframe width="99%" height="330px" frameborder="0" allowtransparency="true" src="${url}"></iframe>`);
}

// Get customer details
const getCustomer = (customer) => {
    if (customer.length > 0) {
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("customer-list").style.display = "block";
                document.getElementById("customer-list").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", `ajax/customerSearch.ajax.php?data=${customer}`, true);
        xmlhttp.send();
    } else {
        document.getElementById("customer-list").style.display = "none";
    }
}

// Set customer
const setCustomer = (id) => {
    const getDetails = (type) => {
        const stockCheckUrl = `ajax/customer.getDetails.ajax.php?${type}=${id}`;
        xmlhttp.open("GET", stockCheckUrl, false);
        xmlhttp.send(null);
        return xmlhttp.responseText;
    };

    document.getElementById("customer").value = getDetails('name');
    document.getElementById("customer-name").value = getDetails('name');
    document.getElementById("customer-id").value = id;
    document.getElementById("contact").innerHTML = getDetails('contact');
    document.getElementById("customer-list").style.display = "none";
}

// Counter bill
const counterBill = () => {
    document.getElementById("contact").innerHTML = "";
    document.getElementById("customer").value = "Cash Sales";
    document.getElementById("customer-id").value = "Cash Sales";
    document.getElementById("customer-name").value = "Cash Sales";
    document.getElementById("final-doctor-name").value = 'Cash Sales';
    document.getElementById("doctor-select").value = 'Cash Sales';
}

// Set payment mode
document.getElementById("payment-mode").value = 'Cash';
document.getElementById("final-payment").value = 'Cash';

const getPaymentMode = (mode) => {
    document.getElementById("final-payment").value = mode;
}

// Ensure search item is focused first value not a space
const firstInput = document.getElementById('product-name');
window.addEventListener('load', () => {
    firstInput.focus();
});

firstInput.addEventListener('input', function (event) {
    const inputValue = this.value;
    // Check if the first character is a space
    if (inputValue.length > 0 && inputValue[0] === ' ') {
        this.value = inputValue.slice(1);
    }
});







// Search for item
const searchItem = (searchFor) => {
    const productInput = document.getElementById("product-name");
    const searchedItems = document.getElementById('searched-items');

    if (productInput.value === "") {
        searchedItems.style.display = "none";
        document.getElementById("searched-batchNo").style.display = "none";
    }

    if (searchFor.length === 0) {
        clearInputFields1();
    } else {
        if (searchFor.length > 2) {
            searchedItems.style.display = "block";
            document.getElementById("exta-details").style.display = "none";

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    searchedItems.innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.open('GET', 'ajax/sales-item-list.ajax.php?data=' + searchFor, true);
            xmlhttp.send();
        }
        newSellGenerateBill.setAttribute("disabled", true);
    }
}

// Clear input fields
const clearInputFields1 = () => {
    const clearFields = [
        "product-name", "weightage", "batch-no", "exp-date", "mrp", "gst",
        "item-weightage", "item-unit-type", "aqty", "type-check", "qty", "disc",
        "dPrice", "taxable", "amount"
    ];

    clearFields.forEach(field => document.getElementById(field).value = '');

    document.getElementById("loose-stock").value = 'None';
    document.getElementById("loose-price").value = 'None';
    document.getElementById("exta-details").style.display = "none";
    document.getElementById("searched-items").style.display = "none";

    Swal.fire({
        title: "Want Add This Item?",
        text: "This Item is not available in your stock, do you want to add?",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            window.location.href = "stock-in.php";
        }
    });
}

// Fetch item batch details
const itemsBatchDetails = (productId, name, stock) => {
    if (stock > 0) {
        document.getElementById("product-name").value = name;
        document.getElementById("searched-items").style.display = "none";

        const searchedBatchNo = document.getElementById('searched-batchNo');
        searchedBatchNo.style.display = "block";
        document.getElementById("exta-details").style.display = "none";

        clearInputFields1();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                searchedBatchNo.innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open('GET', `ajax/sales-item-batch-list.ajax.php?prodId=${productId}`, true);
        xmlhttp.send();
    } else {
        clearInputFields1();
    }
}

// Check form before submission
const checkForm = () => {
    if (document.getElementById('product-name').value === '') {
        document.getElementById("exta-details").style.display = "none";
        document.getElementById("searched-items").style.display = "none";

        const tableBody = document.getElementById('item-body');

        if (tableBody.getElementsByTagName('tr') != null) {
            newSellGenerateBill.removeAttribute("disabled");
        } else {
            newSellGenerateBill.setAttribute("disabled", true);
        }
    }
}



/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
const stockDetails = (productId, batchNo, itemId) => {
    let selectedItem = productId;
    let selectedBatch = batchNo;

    let tableVal = parseInt(document.getElementById("dynamic-id").value);

    if (tableVal > 0) {
        let tableId = document.getElementById("item-body");
        let jsTableLength = tableId.rows.length;
        let cellIndex_1 = 3;
        let cellIndex_2 = 5;

        for (let i = 0; i < jsTableLength; i++) {
            let row = tableId.rows[i];
            let prodIdCell = row.cells[cellIndex_1];
            let prevSelectedProdId = prodIdCell.innerHTML;

            if (prevSelectedProdId == selectedItem) {
                let prodBatchNoCell = row.cells[cellIndex_2];
                let prevSelectedBatch = prodBatchNoCell.innerHTML;

                if (prevSelectedBatch == selectedBatch) {
                    Swal.fire("Failed!", "You have added this item previously.", "error");
                    clearInputFields2();
                    return;
                }
            }
        }
    }

    document.getElementById("product-id").value = productId;
    document.getElementById("batch_no").value = batchNo;
    document.getElementById("crnt-stck-itm-id").value = itemId;

    let stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            let exist = xmlhttp.responseText;
            if (exist == 1) {
                displayProductDetails(productId);
            } else {
                clearInputFields2();
            }
        }
    };

    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
}

const displayProductDetails = (productId) => {
    let productDetails = [
        { id: "product-name", url: 'ajax/getProductDetails.ajax.php?id=' + productId },
        { id: "item-weightage", url: 'ajax/getProductDetails.ajax.php?itemWeightage=' + productId },
        { id: "item-unit-type", url: 'ajax/getProductDetails.ajax.php?itemUnit=' + productId },
        { id: "exp-date", url: 'ajax/getProductDetails.ajax.php?exp=' + productId },
        { id: "mrp", url: 'ajax/getProductDetails.ajax.php?stockmrp=' + productId },
        { id: "purchased-cost", url: 'ajax/getProductDetails.ajax.php?stockptr=' + productId + '&currentStockId=' + itemId },
        { id: "loose-stock", url: 'ajax/getProductDetails.ajax.php?looseStock=' + productId + '&batchNo=' + batchNo },
        { id: "aqty", url: 'ajax/getProductDetails.ajax.php?availibility=' + productId + '&batchNo=' + batchNo },
        { id: "gst", url: 'ajax/product.getGst.ajax.php?stockgst=' + productId },
        { id: "manuf", url: 'ajax/product.getManufacturer.ajax.php?id=' + productId },
        { id: "manufName", url: 'ajax/product.getManufacturer.ajax.php?manufName=' + productId },
        { id: "productComposition", url: 'ajax/product.getContent.ajax.php?pid=' + productId }
    ];

    productDetails.forEach(detail => {
        xmlhttp.open("GET", detail.url, false);
        xmlhttp.send(null);
        document.getElementById(detail.id).value = xmlhttp.responseText;
    });

    document.getElementById("exta-details").style.display = "block";
    document.getElementById("qty").focus();
    newSellGenerateBill.setAttribute("disabled", true); 
}

const clearInputFields2 = () => {
    let fieldsToClear = [
        "product-name", "weightage", "batch-no", "exp-date", "mrp", "gst",
        "item-weightage", "item-unit-type", "aqty", "type-check", "qty", "disc",
        "dPrice", "taxable", "amount", "loose-stock", "loose-price"
    ];

    fieldsToClear.forEach(field => {
        document.getElementById(field).value = '';
    });

    document.getElementById("searched-batchNo").style.display = "none";
    document.getElementById("exta-details").style.display = "none";
}




// checkQty()