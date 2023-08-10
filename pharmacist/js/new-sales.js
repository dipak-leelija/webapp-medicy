const getDate = (date) => {
    document.getElementById("final-bill-date").value = date;
}
// ADD NEW CUSTOMER 
const addCustomerModal = () => {
    let url = "ajax/customer.addNew.ajax.php";
    $(".add-customer-modal").html(
        '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
        url + '"></iframe>');
}
// GET CUSTOMER DETAILS
const getCustomer = (customer) => {
    if (customer.length > 0) {
        let xmlhttp = new XMLHttpRequest();
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
} // end getCustomer

const setCustomer = (id) => {
    var xmlhttp = new XMLHttpRequest();

    // ================ get Name ================
    stockCheckUrl = 'ajax/customer.getDetails.ajax.php?name=' + id;
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    document.getElementById("customer").value = xmlhttp.responseText;
    document.getElementById("customer-name").value = xmlhttp.responseText;
    document.getElementById("customer-id").value = id;

    // ================ get Contact ================
    stockCheckUrl = 'ajax/customer.getDetails.ajax.php?contact=' + id;
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    document.getElementById("contact").innerHTML = xmlhttp.responseText;
    document.getElementById("customer-list").style.display = "none";
}

const counterBill = () => {
    document.getElementById("contact").innerHTML = "";
    document.getElementById("customer").value = "Cash Sales";
    document.getElementById("customer-id").value = "Cash Sales";
    document.getElementById("customer-name").value = "Cash Sales";
}

const getPaymentMode = (mode) => {
    document.getElementById("final-payment").value = mode;
}

const searchItem = (searchFor) => {

    let searchReult = document.getElementById('searched-items');
    document.getElementById("searched-items").style.display = "block";
    document.getElementById("exta-details").style.display = "none";

    if (document.getElementById("product-name").value == "") {
        document.getElementById("searched-items").style.display = "none";
        document.getElementById("searched-batchNo").style.display = "none";
    }

    if (searchFor.length == "") {
        searchReult.innerHTML = '';

        document.getElementById("product-name").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("batch-no").value = '';
        document.getElementById("exp-date").value = '';
        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

        document.getElementById("item-weightage").value = '';
        document.getElementById("item-unit-type").value = '';
        document.getElementById("aqty").value = '';
        document.getElementById("type-check").value = '';
        document.getElementById("qty").value = '';
        document.getElementById("disc").value = '';
        document.getElementById("dPrice").value = '';
        document.getElementById("taxable").value = '';
        document.getElementById("amount").value = '';
    } else {
        var XML = new XMLHttpRequest();
        XML.onreadystatechange = function () {
            if (XML.readyState == 4 && XML.status == 200) {
                searchReult.innerHTML = XML.responseText;
            }
        };
        XML.open('GET', 'ajax/sales-item-list.ajax.php?data=' + searchFor, true);
        XML.send();
    }
}

const itemsBatchDetails = (prodcutId, name, stock) => {

    if (stock > 0) {
        // ==================== SEARCH PRODUCT NAME =====================
        document.getElementById("product-name").value = name;
        document.getElementById("searched-items").style.display = "none";
        // ==================== EOF PRODUCT NAME SEARCH ================

        let searchReult = document.getElementById('searched-batchNo');

        document.getElementById("searched-batchNo").style.display = "block";
        document.getElementById("exta-details").style.display = "none";

        document.getElementById("batch-no").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("exp-date").value = '';
        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

        document.getElementById("item-weightage").value = '';
        document.getElementById("item-unit-type").value = '';
        document.getElementById("aqty").value = '';
        document.getElementById("type-check").value = '';
        document.getElementById("qty").value = '';
        document.getElementById("disc").value = '';
        document.getElementById("dPrice").value = '';
        document.getElementById("taxable").value = '';
        document.getElementById("amount").value = '';

        var XML = new XMLHttpRequest();
        XML.onreadystatechange = function () {
            if (XML.readyState == 4 && XML.status == 200) {
                searchReult.innerHTML = XML.responseText;
            }
        };
        XML.open('GET', 'ajax/sales-item-batch-list.ajax.php?batchDetails=' + prodcutId, true);
        XML.send();


    }

    if (stock <= 0) {

        document.getElementById("product-name").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("batch-no").value = '';
        document.getElementById("exp-date").value = '';
        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

        document.getElementById("item-weightage").value = '';
        document.getElementById("item-unit-type").value = '';
        document.getElementById("aqty").value = '';
        document.getElementById("type-check").value = '';
        document.getElementById("qty").value = '';
        document.getElementById("disc").value = '';
        document.getElementById("dPrice").value = '';
        document.getElementById("taxable").value = '';
        document.getElementById("amount").value = '';
        document.getElementById("loose-stock").value = 'None';
        document.getElementById("loose-price").value = 'None';

        // document.getElementById("qty-type").setAttribute("disabled", true);

        document.getElementById("exta-details").style.display = "none";
        document.getElementById("searched-items").style.display = "none";

        swal({
            title: "Want Add This Item?",
            text: "This Item is not avilable in your stock, do you want to add?",
            // icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = "stock-in.php";
                }
            });
    }
}

////////////////////////////////////////////////////////
///////////////////////////////////////////////////////
const stockDetails = (productId, batchNo) => {

    document.getElementById("product-id").value = productId;
    document.getElementById("batch_no").value = batchNo;
    document.getElementById("batch-no").value = batchNo;
    document.getElementById("searched-batchNo").style.display = "none";

    var xmlhttp = new XMLHttpRequest();

    // ============== Check Existence ==============
    stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    exist = xmlhttp.responseText;

    if (exist == 1) {
        document.getElementById("exta-details").style.display = "block";

        // ============== Product Name ==============
        stockItemUrl = 'ajax/getProductDetails.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", stockItemUrl, false);
        xmlhttp.send(null);
        document.getElementById("product-name").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Weightage ====================
        weightageUrl = `ajax/getProductDetails.ajax.php?weightage=${productId}`;
        // alert(url);
        xmlhttp.open("GET", weightageUrl, false);
        xmlhttp.send(null);
        let packWeightage = xmlhttp.responseText;
        document.getElementById("item-weightage").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Unit ====================
        unitUrl = 'ajax/getProductDetails.ajax.php?unit=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        let packUnit = xmlhttp.responseText;
        let packOf = `${packWeightage}${packUnit}`;
        document.getElementById("weightage").value = packOf;
        document.getElementById("item-unit-type").value = xmlhttp.responseText;
        // // alert(xmlhttp.responseText);

        //==================== Expiry Date ====================
        expDateUrl = `ajax/getProductDetails.ajax.php?exp=${productId}&batchNo=${batchNo}`;
        // alert(url);
        xmlhttp.open("GET", expDateUrl, false);
        xmlhttp.send(null);
        document.getElementById("exp-date").value = xmlhttp.responseText;

        //==================== MRP ====================
        mrpUrl = `ajax/getProductDetails.ajax.php?stockmrp=${productId}&batchNo=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== PTR ====================
        ptrUrl = `ajax/getProductDetails.ajax.php?stockptr=${productId}&batchNo=${batchNo}`;
        // alert(ptrUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", ptrUrl, false);
        xmlhttp.send(null);
        document.getElementById("ptr").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Loose Stock ====================
        looseStockUrl = `ajax/getProductDetails.ajax.php?looseStock=${productId}&batchNo=${batchNo}`;
        // alert(ptrUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", looseStockUrl, false);
        xmlhttp.send(null);
        document.getElementById("loose-stock").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Loose Price ====================
        loosePriceUrl = `ajax/getProductDetails.ajax.php?loosePrice=${productId}&batchNo=${batchNo}`;
        // alert(ptrUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", loosePriceUrl, false);
        xmlhttp.send(null);
        document.getElementById("loose-price").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        // ======================= AVAILIBILITY ===========================
        itemAvailibilityUrl = `ajax/getProductDetails.ajax.php?availibility=${productId}&batchNo=${batchNo}`;
        // alert(ptrUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", itemAvailibilityUrl, false);
        xmlhttp.send(null);
        document.getElementById("aqty").value = xmlhttp.responseText;

        //==================== GST ====================
        gstUrl = 'ajax/product.getGst.ajax.php?stockgst=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        document.getElementById("gst").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        // =========================================================
        // ===================== XTERA DETAILS =====================
        // =========================================================

        //==================== Manufacturer Details ====================
        manufUrl = 'ajax/product.getManufacturer.ajax.php?id=' + productId;
        xmlhttp.open("GET", manufUrl, false);
        xmlhttp.send(null);
        document.getElementById("manuf").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        manufNameUrl = 'ajax/product.getManufacturer.ajax.php?manufName=' + productId;
        xmlhttp.open("GET", manufNameUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("manufName").value = xmlhttp.responseText;

        //////// STRING REPLACE IN MANUFACTURER DETAILS //////////
        // let manufactururName = document.getElementById("manufName").value;
        // manufactururName = manufactururName.replace("<", "&lt");
        // manufactururName = manufactururName.replace(">", "&gt");
        // manufName = manufactururName.replace("'", "_");
        // document.getElementById("manufNameStrngReplace").value = manufName;
        // console.log(manufName);
        //==================== Content ====================
        contentUrl = 'ajax/product.getContent.ajax.php?pid=' + productId;
        xmlhttp.open("GET", contentUrl, false);
        xmlhttp.send(null);
        document.getElementById("productComposition").value = xmlhttp.responseText;
    } else {
        document.getElementById("product-name").value = '';
        document.getElementById("weightage").value = '';
        document.getElementById("batch-no").value = '';
        document.getElementById("exp-date").value = '';

        document.getElementById("weightage").value = '';
        document.getElementById("batch-no").value = '';
        document.getElementById("exp-date").value = '';

        document.getElementById("mrp").value = '';
        document.getElementById("gst").value = '';

        document.getElementById("item-weightage").value = '';
        document.getElementById("item-unit-type").value = '';
        document.getElementById("aqty").value = '';
        document.getElementById("type-check").value = '';
        document.getElementById("qty").value = '';
        document.getElementById("disc").value = '';
        document.getElementById("dPrice").value = '';
        document.getElementById("taxable").value = '';
        document.getElementById("amount").value = '';

        // document.getElementById("qty-type").setAttribute("disabled", true);
        document.getElementById("loose-stock").value = 'None';
        document.getElementById("loose-price").value = 'None';
        document.getElementById("exta-details").style.display = "none";
    }
}

const onQty = (qty) => {

    var xmlhttp = new XMLHttpRequest();

    //=============================== AVAILIBILITY CHECK ================================
    let availibility = document.getElementById('aqty').value;
    availibility = parseInt(availibility);

    if (qty > availibility) {
        qty = '';
        document.getElementById("qty").value = qty;
        string_1 = "Please selet another batch or input ";
        string_2 = availibility;
        string_3 = " as qantity.";
        string_4 = string_1.concat(string_2).concat(string_3);
        window.alert(string_4);
    }
    // =============================== Item pack type calculation ======================
    let unitType = document.getElementById("item-unit-type").value;
    let itemWeightage = document.getElementById("item-weightage").value;
    let checkSum = '';
    let itemPackType = '';

    if (unitType == 'tab' || unitType == 'cap') {
        checkSum = parseInt(qty) % parseInt(itemWeightage);
        if (checkSum == 0) {
            itemPackType = 'Pack';
        } else {
            itemPackType = 'Loose';
        }
    } else {
        itemPackType = '';
    }

    document.getElementById("type-check").value = itemPackType;
    // =========================== ========================== ====================

    var pid = document.getElementById("product-id").value;
    var bno = document.getElementById("batch-no").value;
    let mrp = document.getElementById("mrp").value;
    let loosePrice = document.getElementById("loose-price").value;
    let disc = document.getElementById("disc").value;
    let discPrice = document.getElementById('dPrice').value;
    let gst = document.getElementById('gst').value;

    if (disc != null) {
        disc = disc;
    }
    else {
        disc = 0;
    }
    // console.log("discount percent check on qty : ", disc);

    if (qty > 0) {
        if (itemPackType == '') {
            discPrice = parseFloat(mrp) - ((parseFloat(mrp) * disc) / 100);
            document.getElementById("dPrice").value = discPrice.toFixed(2);

            let taxable = ((discPrice * 100) / (100 + parseInt(gst)));
            let subtotal = discPrice * parseInt(qty);

            document.getElementById('taxable').value = taxable * parseInt(qty);
            document.getElementById('amount').value = subtotal.toFixed(2);
        } else {
            mrp = parseFloat(loosePrice);
            discPrice = parseFloat(mrp) - ((parseFloat(mrp) * disc) / 100)
            document.getElementById("dPrice").value = discPrice.toFixed(2);

            let taxable = (discPrice * 100) / (100 + parseInt(gst));
            let subtotal = discPrice * parseInt(qty);

            document.getElementById('taxable').value = parseFloat(taxable) * parseInt(qty);
            document.getElementById('amount').value = subtotal.toFixed(2);
        }
        // console.log("discount percent on qty : ",disc);
        // console.log("price after discount on qty : ",discPrice);
    } else {
        document.getElementById("dPrice").value = '';
        document.getElementById("amount").value = '';
        document.getElementById("type-check").value = '';
    }
    // console.log("DISCOUNT PRICE CHECK ON MARGINE  : ", discPrice);

    //==================== Margin on an Item ====================
    marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${itemPackType}&Mrp=${mrp}&Qty=${qty}&Dprice=${discPrice}`;
    xmlhttp.open("GET", marginUrl, false);
    xmlhttp.send(null);
    document.getElementById("margin").value = xmlhttp.responseText;
}


const ondDisc = (disc) => {

    var xmlhttp = new XMLHttpRequest();

    var pid = document.getElementById("product-id").value;
    var bno = document.getElementById("batch-no").value;
    let mrp = document.getElementById("mrp").value;
    let loosePrice = document.getElementById("loose-price").value;

    let gst = document.getElementById('gst').value;
    let discPrice = document.getElementById('dPrice').value;

    // let unitType = document.getElementById("item-unit-type").value;
    // let itemWeightage = document.getElementById("item-weightage").value;

    let itemTypeCheck = document.getElementById("type-check").value;
    // console.log("item type check on discount calculation block : ",itemTypeCheck);

    let qty = document.getElementById('qty').value;
    let availibility = document.getElementById('aqty').value;
    availibility = parseInt(availibility);

    availibility = parseInt(availibility);
    if (qty > availibility) {
        qty = availibility;
    }
    // console.log("check disc quantity : ", qty);

    if (disc != null) {
        disc = disc;
    }
    else {
        disc = 0;
    }

    if (qty > 0) {
        if (itemTypeCheck == '') {
            discPrice = parseFloat(mrp) - ((parseFloat(mrp) * disc) / 100);
            document.getElementById("dPrice").value = parseFloat(discPrice).toFixed(2);

            let taxable = (discPrice * 100) / (100 + parseInt(gst));
            let subtotal = discPrice * parseInt(qty);

            document.getElementById('taxable').value = taxable * parseInt(qty);
            document.getElementById('amount').value = subtotal.toFixed(2);
        } else {
            mrp = parseFloat(loosePrice);
            discPrice = parseFloat(mrp) - ((parseFloat(mrp) * disc) / 100)
            document.getElementById("dPrice").value = discPrice.toFixed(2);

            let taxable = (discPrice * 100) / (100 + parseInt(gst));
            let subtotal = discPrice * parseInt(qty);

            document.getElementById('taxable').value = parseFloat(taxable) * parseInt(qty);
            document.getElementById('amount').value = subtotal.toFixed(2);
            document.getElementById('dPrice').value = discPrice;
        }

    } else {
        document.getElementById("dPrice").value = '';
        document.getElementById("amount").value = '';
        document.getElementById("type-check").value = '';
        document.getElementById('dPrice').value = '';
    }

    //==================== Margin on an Item ====================
    marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${itemTypeCheck}&Mrp=${mrp}&Qty=${qty}&Dprice=${discPrice}`;
    xmlhttp.open("GET", marginUrl, false);
    xmlhttp.send(null);
    document.getElementById("margin").value = xmlhttp.responseText;
}


const addSummary = () => {

    let billDAte = document.getElementById("bill-date").value;
    let customer = document.getElementById("customer").value;
    let doctorName = document.getElementById("doctor-select").value;
    let paymentMode = document.getElementById("payment-mode").value;

    let productId = document.getElementById("product-id").value;
    let productName = document.getElementById("product-name").value;
    let batchNo = document.getElementById("batch-no").value;
    let weightage = document.getElementById("weightage").value;
    let itemWeightage = document.getElementById('item-weightage').value;
    let unitType = document.getElementById('item-unit-type').value;
    let expDate = document.getElementById("exp-date").value;
    let mrp = document.getElementById("mrp").value;
    let available = document.getElementById('aqty').value;
    let itemComposition = document.getElementById('productComposition').value;
    let qty = document.getElementById("qty").value;
    let qtyTypeCheck = document.getElementById("type-check").value;
    let Manuf = document.getElementById("manuf").value;
    let manufName = document.getElementById("manufName").value;
    // let rplceStrngManufName = document.getElementById("manufNameStrngReplace").value;
    let discPercent = document.getElementById("disc").value;
    // console.log("on add customer name check : ", customer);
    let discPrice = document.getElementById("dPrice").value;
    let gst = document.getElementById("gst").value;
    let taxable = document.getElementById("taxable").value;
    let taxableAmount = parseFloat(taxable);
    let amount = document.getElementById("amount").value;
    // let amnt = amount.toFixed(2);
    let looseStock = document.getElementById("loose-stock").value;
    let loosePrice = document.getElementById("loose-price").value;
    let ptr = document.getElementById("ptr").value;
    let marginAmount = document.getElementById("margin").value;


    // ============== per item gst amount calculation ============
    let netGstAmount = (parseFloat(amount) - parseFloat(taxable));
    netGstAmount = netGstAmount.toFixed(2);
    // console.log("net gst amount : ",netGstAmount);
    // ============ end of amount calculation ==============
    // ============ MRP SET ======================
    if (loosePrice != '') {
        calculatedMRP = loosePrice;
    }else{
        calculatedMRP = mrp;
    }
    console.log("mrp check : ",calculatedMRP);
    //===========================================

    if (billDAte == '') {
        swal("Failed!", "Please Select Bill Date!", "error");
        return;
    }
    if (customer == '') {
        swal("Failed!", "Please Select Customer Name!", "error");
        return;
    }
    if (doctorName =='') {
        swal("Failed!", "Please Select/Enter Doctor Name!", "error");
        return;
    }
    if (paymentMode == '') {
        swal("Failed!", "Please Select a Payment Mode!", "error");
        return;
    }
    if (productId == '') {
        swal("Failed!", "Product ID Not Found!", "error");
        return;
    }
    if (productName == '') {
        swal("Failed!", "Product Name Not Found!", "error");
        return;
    }
    if (batchNo == '') {
        swal("Failed!", "Batch No Not Found!", "error");
        return;
    }
    if (weightage == '') {
        swal("Failed!", "Product Weatage/Unit Not Found!", "error");
        return;
    }
    if (expDate == '') {
        swal("Failed!", "Expiry Date Not Found!", "error");
        return;
    }
    if (mrp == '') {
        swal("Failed!", "MRP Not Found!", "error");
        return;
    }
    if (qty == '') {
        swal("Failed!", "Please Enter Quantity:", "error");
        return;
    }
    if (discPercent == '') {
        swal("Failed!", "Please Enter Discount Minimum: 0", "error");
        return;
    }
    if (discPrice == '') {
        swal("Failed!", "Discounted Price Not Found!", "error");
        return;
    }
    if (gst == '') {
        swal("Failed!", "GST Not Found!", "error");
        return;
    }
    if (amount == '') {
        swal("Failed!", "Total Amount Not Found!", "error");
        return;
    }

    // console.log("Working Fine");

    document.getElementById("no-item").style.display = "none";

    /////// SERIAL NUMBER SET /////////
    let slno = document.getElementById("dynamic-id").value;
    slno++;
    document.getElementById("dynamic-id").value = slno;

    ////////// ITEMS COUNT ////////////

    document.getElementById("items").value = slno;

    /// TOTAL QUANTITY COUNT CALCULATION ///
    let finalQty = document.getElementById("final-qty");
    let totalQty = parseInt(finalQty.value) + parseInt(qty);
    document.getElementById("final-qty").value = totalQty;

    ///////////TOTAL GST CALCULATION////////////
    let existsGst = parseFloat(document.getElementById("total-gst").value);
    let netGst = parseFloat(netGstAmount);
    let totalGst = existsGst + netGst;
    document.getElementById("total-gst").value = totalGst.toFixed(2);
    // =========================================

    /////////NET MRP CALCULATION//////////
    let totalPrice = document.getElementById("total-price").value;
    let existsPrice = parseFloat(totalPrice);
    var itemMrp = parseFloat(calculatedMRP);
    let itemQty = parseInt(qty);
    itemMrp = itemQty * itemMrp;
    let totalMrp = existsPrice + itemMrp;
    document.getElementById("total-price").value = totalMrp.toFixed(2);


    ////////////TOTAL PAYABLE //////////////
    let payable = document.getElementById("payable").value;
    let existsPayable = parseFloat(payable);
    let itemAmount = parseFloat(amount);
    let sum = existsPayable + itemAmount;
    document.getElementById("payable").value = sum.toFixed(2);

    jQuery("#item-body").append(`<tr id="table-row-${slno}">

        <td><i class="fas fa-trash text-danger" onclick="deleteItem(${slno}, ${qty}, ${netGst.toFixed(2)}, ${itemMrp.toFixed(2)}, ${amount})" style="font-size:.7rem; width: .3rem"></i></td>

        <td style="font-size:.7rem; padding-top:1rem; width: .3rem" scope="row">${slno}</td>

        <td id="${slno}">
            <input class="summary-product" type="text" name="product-name[]" value="${productName}" style="word-wrap: break-word; width:9rem; font-size: .7rem;" readonly>
            <input type="text" class="d-none" name="product-id[]" value="${productId}" >
        </td>

        <td class="d-none">
            <input type="text" name="ManufId[]" value="${Manuf}">
            <input type="text" name="ManufName[]" value="${manufName}">
        </td>

        <td id="${batchNo}">
            <input class="summary-items" type="text" name="batch-no[]" id="batch-no" value="${batchNo}" style="word-wrap: break-word; width:7rem; font-size: .7rem; " readonly>
        </td>

        <td id="${weightage}">
            <input class="summary-items" type="text" name="weightage[]" value="${weightage}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="ItemWeightage[]" value="${itemWeightage}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="ItemUnit[]" value="${unitType}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>
                                                
        <td id="${expDate}">
            <input class="summary-items" type="text" name="exp-date[]" value="${expDate}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>

        <td id="${mrp}">
            <input class="summary-items" type="text" name="mrp[]" value="${mrp}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td id="${ptr}">
            <input class="summary-items" type="text" name="itemPtr[]" value="${ptr}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="qtyTp[]" value="${qtyTypeCheck}" style="word-wrap: break-word; width:3rem; font-size: .7rem;" readonly>
        </td>

        <td id="${qty}">
            <input class="summary-items" type="text" name="qty[]" value="${qty}" readonly>
        </td>

        <td id="${discPercent}">
            <input class="summary-items" type="text" name="discPercent[]" value="${discPercent}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="discPrice[]" value="${discPrice}" style="word-wrap: break-word; width:3rem; font-size: .7rem; " readonly>
        </td>

        <td id="${taxableAmount}">
            <input class="summary-items" type="text" name="taxable[]" value="${taxableAmount.toFixed(2)}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: center" readonly>
        </td>

        <td id="${gst}">
            <input class="summary-items" type="text" name="gst[]" value="${gst}" style="word-wrap: break-word; width:3rem; font-size: .7rem;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="gstVal[]" value="${netGst.toFixed(2)}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${marginAmount}">
            <input class="summary-items" type="text" name="marginAmount[]" value="${marginAmount}" style="word-wrap: break-word; width:3rem; font-size: .7rem;" readonly>
        </td>

        <td id="${amount}">
            <input class="summary-items" type="text" name="amount[]" value="${amount}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        /////////////////////\\\\\\\\\\\\\\\\\\\ EXTRA DATA /////////////////////\\\\\\\\\\\\\\\\\\\\

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="LooseStock[]" value="${looseStock}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="LoosePrice[]" value="${loosePrice}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="availibility[]" value="${available}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        <td class="d-none" id="${slno}">
            <input class="summary-items" type="text" name="itemComposition[]" value="${itemComposition}" style="word-wrap: break-word; width:3rem; font-size: .7rem; text-align: right;" readonly>
        </td>

        //////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    </tr>`);

    ////// TUPLE DECLEARATION and ON CLICK FUNCTION CALL///////

    taxable = taxableAmount.toFixed(2);

    const dataTuple = {
        slno: slno,
        productId: productId,
        batchNo: batchNo,
        productName: productName,
        ManufId: Manuf,
        manufName: manufName,
        weightage: weightage,
        itemWeightage: itemWeightage,
        unitType: unitType,
        expDate: expDate,
        mrp: mrp,
        ptr: ptr,
        qtyTypeCheck: qtyTypeCheck,
        qty: qty,
        discPercent: discPercent,
        discPrice: discPrice,
        taxable: taxable,
        gst: gst,
        gstAmountPerItem: netGst,
        marginAmount: marginAmount,
        amount: amount,
        looseStock: looseStock,
        loosePrice: loosePrice,
        available: available,
        itemComposition: itemComposition
    };

    let tupleData = JSON.stringify(dataTuple);

    document.getElementById(slno).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(batchNo).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(weightage).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(expDate).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(mrp).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(ptr).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(qty).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(discPercent).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(taxableAmount).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(gst).onclick = function () {
        editItem(tupleData);
    };
    document.getElementById(amount).onclick = function () {
        editItem(tupleData);
    };
    //////////////////////////////////////////


    document.getElementById('final-doctor-name').value = doctorName;
    document.getElementById("aqty").value = "";
    document.getElementById("add-item-details").reset();



    event.preventDefault();

}

const deleteItem = (slno, itemQty, gstPerItem, totalMrp, itemAmount) => {

    jQuery(`#table-row-${slno}`).remove();
    slno--;
    document.getElementById("dynamic-id").value = slno;

    // Items 
    var items = document.getElementById("items").value;
    leftItems = parseInt(items) - 1;
    document.getElementById("items").value = leftItems;

    var existQty = document.getElementById("final-qty");
    leftQty = existQty.value - itemQty;
    existQty.value = leftQty;

    var existGst = document.getElementById("total-gst");
    leftGst = existGst.value - gstPerItem;
    existGst.value = leftGst.toFixed(2);


    var existMrp = document.getElementById("total-price");
    leftMrp = existMrp.value - totalMrp;
    existMrp.value = leftMrp.toFixed(2);

    var existAmount = document.getElementById("payable");
    leftAmount = existAmount.value - parseFloat(itemAmount);
    existAmount.value = leftAmount.toFixed(2);
}


const editItem = (tuple) => {

    window.alert(tuple);

    Tupledata = JSON.parse(tuple);


    document.getElementById("product-id").value = Tupledata.productId;
    document.getElementById("product-name").value = Tupledata.productName;
    document.getElementById("batch-no").value = Tupledata.batchNo;
    document.getElementById("batch_no").value = Tupledata.batchNo;

    document.getElementById("weightage").value = Tupledata.weightage;
    document.getElementById('item-weightage').value = Tupledata.itemWeightage;
    document.getElementById('item-unit-type').value = Tupledata.unitType;

    document.getElementById("exp-date").value = Tupledata.expDate;
    document.getElementById("mrp").value = Tupledata.mrp;
    document.getElementById("ptr").value = Tupledata.ptr;

    document.getElementById("qty").value = Tupledata.qty;
    document.getElementById("type-check").value = Tupledata.qtyTypeCheck;
    document.getElementById("manuf").value = Tupledata.ManufId;
    document.getElementById("manufName").value = Tupledata.manufName;

    document.getElementById("disc").value = Tupledata.discPercent;
    document.getElementById("dPrice").value = Tupledata.discPrice;
    document.getElementById("gst").value = Tupledata.gst;

    document.getElementById("taxable").value = Tupledata.taxable;
    document.getElementById("margin").value = Tupledata.marginAmount;
    document.getElementById("amount").value = Tupledata.amount;

    document.getElementById("loose-stock").value = Tupledata.looseStock;
    document.getElementById("loose-price").value = Tupledata.loosePrice;
    document.getElementById("aqty").value = Tupledata.available;
    document.getElementById("productComposition").value = Tupledata.itemComposition;

    let netMRP = parseFloat(Tupledata.mrp) * parseInt(Tupledata.qty);

    deleteItem(Tupledata.slno, Tupledata.qty, Tupledata.gstAmountPerItem, netMRP, Tupledata.amount);
}