// const checkBatchByPid = [];  //

const getDate = (date) => {
    // alert(date);
    document.getElementById("final-bill-date").value = date;
}

const addCustomerModal = () => {
    // alert("Hi");
    let url = "ajax/customer.addNew.ajax.php";
    $(".add-customer-modal").html(
        '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
        url + '"></iframe>');
}

const getCustomer = (customer) => {
    if (customer.length > 0) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            // console.log(customer);
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("customer-list").style.display = "block";

                document.getElementById("customer-list").innerHTML = xmlhttp.responseText;
            } //if
        }; //eof
        xmlhttp.open("GET", `ajax/customerSearch.ajax.php?data=${customer}`, true);
        xmlhttp.send();
    } else {
        document.getElementById("customer-list").style.display = "none";
    }
} // end getCustomer

const setCustomer = (id) => {
    // console.log(id);
    // document.getElementById("contact-box").style.display = "block";

    var xmlhttp = new XMLHttpRequest();

    // ================ get Name ================
    stockCheckUrl = 'ajax/customer.getDetails.ajax.php?name=' + id;
    // alert(url);
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    document.getElementById("customer").value = xmlhttp.responseText;
    document.getElementById("customer-name").value = xmlhttp.responseText;
    document.getElementById("customer-id").value = id;


    // ================ get Contact ================
    stockCheckUrl = 'ajax/customer.getDetails.ajax.php?contact=' + id;
    // alert(url);
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    document.getElementById("contact").innerHTML = xmlhttp.responseText;


    document.getElementById("customer-list").style.display = "none";

}

// ========= set doctor name =============
// const getDoctor = (docname) =>{
//     console.log(docname);
// }

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
    if (searchFor.length == 0) {
        searchReult.innerHTML = '';

        document.getElementById("search-Item").value = '';

        document.getElementById("weightage").value = '';

        document.getElementById("batch-no").value = '';

        document.getElementById("exp-date").value = '';

        document.getElementById("mrp").value = '';

        document.getElementById("gst").value = '';

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
    // console.log("chek prod id for batch no : ", prodcutId);
    // console.log("chek prod name for batch no : ", name);
    // console.log("chek prod stock for batch no : ", stock);

    if (stock > 0) {
        // ==================== SEARCH PRODUCT NAME =====================
        document.getElementById("search-Item").value = name;
        document.getElementById("searched-items").style.display = "none";
        // ==================== EOF PRODUCT NAME SEARCH ================

        let searchReult = document.getElementById('searched-batchNo');

        document.getElementById("searched-batchNo").style.display = "block";
        document.getElementById("exta-details").style.display = "none";

        // if (searchFor.length == 0) {
        //     searchReult.innerHTML = '';

        document.getElementById("batch-no").value = '';

        document.getElementById("weightage").value = '';

        document.getElementById("exp-date").value = '';

        document.getElementById("mrp").value = '';

        document.getElementById("gst").value = '';


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
    
        document.getElementById("search-Item").value = '';

        document.getElementById("weightage").value = '';

        document.getElementById("batch-no").value = '';

        document.getElementById("exp-date").value = '';

        document.getElementById("mrp").value = '';

        document.getElementById("gst").value = '';
        document.getElementById("qty-type").setAttribute("disabled", true);

        document.getElementById("loose-stock").value = 'None';
        document.getElementById("loose-price").value = 'None';

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

    // console.log("BATCH SEARCHED PRODUCT ID : ",productId);
    // console.log("BATCH SEARCHED PRODUCT BATCH NO : ",batchNo);

    document.getElementById("product-id").value = productId;
    document.getElementById("batch_no").value = batchNo;
    document.getElementById("batch-no").value = batchNo;
    document.getElementById("searched-batchNo").style.display = "none";

    // var qntity = document.getElementById('qty').value;

    // checkBatchByPid.push(productId); //product id array
    // console.log(qntity);
    // alert(productId);
    // console.log(productId);
    // console.log(checkBatchByPid);

    var xmlhttp = new XMLHttpRequest();
    var qtytp = document.getElementById('qty-type').value;

    // ============== Check Existence ==============
    stockCheckUrl = `ajax/stock.checkExists.ajax.php?Pid=${productId}&batchNo=${batchNo}`;
    // alert(url);
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    exist = xmlhttp.responseText;
    // console.log("STOCK EXIST ALERT", exist);

    if (exist == 1) {
        document.getElementById("exta-details").style.display = "block";

        // ============== Product Name ==============
        stockItemUrl = 'ajax/getProductDetails.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", stockItemUrl, false);
        xmlhttp.send(null);
        document.getElementById("search-Item").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Weightage ====================
        weightageUrl = `ajax/getProductDetails.ajax.php?weightage=${productId}`;
        // alert(url);
        xmlhttp.open("GET", weightageUrl, false);
        xmlhttp.send(null);
        let packWeightage = xmlhttp.responseText;
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

        // //=========== QANTITY CHECK ON BATCH NUMBER =============
        qtyChkOnBathcUrl = `ajax/product.stockDetails.getMargin.ajax.php?qtyCheck=${productId}&qtp=${qtytp}`;
        xmlhttp.open("GET", qtyChkOnBathcUrl, false);
        xmlhttp.send(null);
        document.getElementById("aqty").value = "hello";
        document.getElementById("aqty").value = xmlhttp.responseText;

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

        //==================== Manufacturer ====================
        manufUrl = 'ajax/product.getManufacturer.ajax.php?id=' + productId;
        //console.log(productId);
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", manufUrl, false);
        xmlhttp.send(null);
        document.getElementById("manuf").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);


        manufNameUrl = 'ajax/product.getManufacturer.ajax.php?manufName=' + productId;
        //console.log(productId);
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", manufNameUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("manufName").value = xmlhttp.responseText;

        //==================== Content ====================
        contentUrl = 'ajax/product.getContent.ajax.php?pid=' + productId;
        xmlhttp.open("GET", contentUrl, false);
        xmlhttp.send(null);
        document.getElementById("productComposition").value = xmlhttp.responseText;

        // ============== Check Loose Qty ==============
        checkLoosePackUrl =`ajax/getProductDetails.ajax.php?loosePack=${productId}&batchNo=${batchNo} `;
        // alert(checkLoosePackUrl);
        xmlhttp.open("GET", checkLoosePackUrl, false);
        xmlhttp.send(null);
        let loosePack = xmlhttp.responseText;

        if (loosePack > 0) {
            document.getElementById("mrp").value = '';
            document.getElementById("loose-stock").value = ' ' + loosePack;

            loosePriceUrl = 'ajax/currentStock.looseMrp.ajax.php?id=' + productId;
            // alert(url);
            xmlhttp.open("GET", loosePriceUrl, false);
            xmlhttp.send(null);

            document.getElementById("loose-price").value = ' ' + xmlhttp.responseText;
            document.getElementById("qty-type").removeAttribute("disabled");
        } else {
            let type = document.getElementById("qty-type").value = '';
            // alert(type);
            document.getElementById("qty-type").setAttribute("disabled", true);
            document.getElementById("loose-stock").value = 'None';
            document.getElementById("loose-price").value = 'None';
        }

        // ============== TAXABLE AMOUNT CALCULATION ==============
        // taxableAmntCalculateUrl = `ajax/getProductDetails.ajax.php?getTaxable=${productId}&batchNo=${batchNo}`;
        // // alert(ptrUrl);
        // // window.location.href = unitUrl;
        // xmlhttp.open("GET", taxableAmntCalculateUrl, false);
        // xmlhttp.send(null);
        // document.getElementById("taxableAmnt").value = xmlhttp.responseText;
         
    } else {
        // document.getElementById("search-Item").value = '';

        document.getElementById("weightage").value = '';

        document.getElementById("batch-no").value = '';

        document.getElementById("exp-date").value = '';

        document.getElementById("mrp").value = '';

        document.getElementById("gst").value = '';
        document.getElementById("qty-type").setAttribute("disabled", true);

        document.getElementById("loose-stock").value = 'None';
        document.getElementById("loose-price").value = 'None';

        document.getElementById("exta-details").style.display = "none";
    }
}

const onQty = (qty) => {

    var xmlhttp = new XMLHttpRequest();

    var pid = document.getElementById("product-id").value;
    var qType = document.getElementById("qty-type").value;
    let mrp = document.getElementById("mrp").value;
    var checkAvailibility = document.getElementById("aqty").value;
    qty = document.getElementById("qty").value;
    let bno = document.getElementById("batch-no").value;
    let disc = document.getElementById("disc").value;
    let discPrice = document.getElementById('dPrice').value;
    let gst = document.getElementById('gst').value;

    
    //=========== QANTITY CHECK ON BATCH NUMBER =================
    var bNo = document.getElementById("batch-no").value;
    qtyChkOnBathcUrl = `ajax/product.stockDetails.getMargin.ajax.php?qtyCheck=${pid}&batch=${bNo}&qtp=${qType}`;
    xmlhttp.open("GET", qtyChkOnBathcUrl, false);
    xmlhttp.send(null);
    document.getElementById("aqty").value = "hello";
    document.getElementById("aqty").value = xmlhttp.responseText;
    //===============================================================================================

    console.log("check availibility check : ",checkAvailibility);

    checkAvailibility = Number(checkAvailibility);
    if (qty > checkAvailibility) {
        qty = checkAvailibility;
        document.getElementById("qty").value = qty;
        string_1 = "Current Batch have only "
        string_2 = " quantity of this product. please add the rest of qantity after adding this."
        string_3 = string_1.concat(qty).concat(string_2);
        //console.log(string_3);
        window.alert(string_3);
    }

    if (disc!= null) {
        disc = disc;
    }
    else {
        disc = 0;
    }

    if (qty > 0) {
        discPrice = parseFloat(mrp) - ((parseFloat(mrp) * disc)/100)
        document.getElementById("dPrice").value = discPrice.toFixed(2);

        let taxable = (discPrice * 100) / (100 + parseInt(gst));
        let subtotal = discPrice * parseInt(qty);

        // console.log("taxable check on qty : ", taxable);

        document.getElementById('taxable').value = taxable;
        document.getElementById('amount').value = subtotal.toFixed(2);
    } else {
        document.getElementById("dPrice").value = '';
        document.getElementById("amount").value = '';
    }

    console.log("DISCOUNT PRICE CHECK ON MARGINE  : ", discPrice);
    //==================== Margin on an Item ====================
    marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${qType}&Mrp=${mrp}&Qty=${qty}&Dprice=${discPrice}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    xmlhttp.open("GET", marginUrl, false);
    xmlhttp.send(null);
    document.getElementById("margin").value = xmlhttp.responseText;
    //console.log(xmlhttp.responseText);
    // alert(xmlhttp.responseText);

    //==================== Batch-no ====================
    // var productId = document.getElementById("product-id").value;
    // batchUrl = `ajax/currentStock.getBatch.ajax.php?id=${productId}&chkBtch=${checkBatchByPid}`;
    // // alert(url);
    // xmlhttp.open("GET", batchUrl, false);
    // xmlhttp.send(null);
    // document.getElementById("batch-no").value = xmlhttp.responseText;
}


const ondDisc = (disc) => {

    var xmlhttp = new XMLHttpRequest();
    var checkAvailibility = document.getElementById("aqty").value;
    // console.log(checkAvailibility);
    var pid = document.getElementById("product-id").value;
    var qType = document.getElementById("qty-type").value;
    let mrp = document.getElementById("mrp").value;
    let qty = document.getElementById("qty").value;
    let bno = document.getElementById("batch-no").value;
    let gst = document.getElementById('gst').value;
    // alert(disc);

    checkAvailibility = Number(checkAvailibility);
    if (qty > checkAvailibility) {
        qty = checkAvailibility;
    }

    if (disc!= null) {
        disc = disc;
    }
    else {
        disc = 0;
    }

    if (qty > 0) {
        discPrice = parseFloat(mrp) - ((parseFloat(mrp) * disc)/100)
        document.getElementById("dPrice").value = discPrice.toFixed(2);

        let taxable = (discPrice * 100) / (100 + parseInt(gst));
        let subtotal = discPrice * parseInt(qty);

        // console.log("taxable check on qty : ", taxable);

        document.getElementById('taxable').value = taxable;
        document.getElementById('amount').value = subtotal.toFixed(2);
    } else {
        document.getElementById("dPrice").value = '';
        document.getElementById("amount").value = '';
    }
    
    //==================== Margin on an Item ====================
    marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${pid}&Bid=${bno}&qtype=${qType}&Mrp=${mrp}&Qty=${qty}&Dprice=${discPrice}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    xmlhttp.open("GET", marginUrl, false);
    xmlhttp.send(null);
    document.getElementById("margin").value = xmlhttp.responseText;
    //console.log(xmlhttp.responseText);
}

const mrpUpdate = (mrpType) => {
    // console.log("mrp update check : ", mrpType);
    let xmlhttp = new XMLHttpRequest();
    let productId = document.getElementById("product-id").value;
    let batchNo = document.getElementById("batch_no").value;

    var qType = document.getElementById("qty-type").value;

    let mrp = document.getElementById("mrp").value;
    let qty = document.getElementById("qty").value;

    let bno = document.getElementById("batch-no").value;
    let discPr = document.getElementById("dPrice").value;

    // console.log("mrp update check batch no & product id : ", bno, productId);

    if (mrpType == "Pack") {
        mrpUrl = `ajax/getProductDetails.ajax.php?crntStockMrp=${productId}&batchNo=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);
    } else {
        loosePriceUrl = `ajax/getProductDetails.ajax.php?crntStockLoosePrice=${productId}&batchNo=${batchNo}`;
        // alert(loosePriceUrl);
        xmlhttp.open("GET", loosePriceUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("mrp").value = xmlhttp.responseText;
    }
    
    //==================== Margin on an Item ====================
    marginUrl = `ajax/product.stockDetails.getMargin.ajax.php?Pid=${productId}&Bid=${bno}&qtype=${qType}&Mrp=${mrp}&Qty=${qty}&Dprice=${discPr}`;
    // alert(unitUrl);
    // window.location.href = unitUrl;
    xmlhttp.open("GET", marginUrl, false);
    xmlhttp.send(null);
    document.getElementById("margin").value = xmlhttp.responseText;
    //console.log(xmlhttp.responseText);

    //=========== QANTITY CHECK ON BATCH NUMBER =============
    qtyChkOnBathcUrl = `ajax/product.stockDetails.getMargin.ajax.php?qtyCheck=${productId}&batch=${bno}&qtp=${qType}`;
    xmlhttp.open("GET", qtyChkOnBathcUrl, false);
    xmlhttp.send(null);
    document.getElementById("aqty").value = "hello";
    document.getElementById("aqty").value = xmlhttp.responseText;
    //var checkAvailibility = document.getElementById("aqty").value;
}


const addSummary = () => {

    let billDAte = document.getElementById("bill-date");
    let customer = document.getElementById("customer");
    let doctorName = document.getElementById("doctor-select");
    let paymentMode = document.getElementById("payment-mode");

    let productId = document.getElementById("product-id");
    let productName = document.getElementById("search-Item");
    let Manuf = document.getElementById("manuf");
    let weightage = document.getElementById("weightage");
    let batchNo = document.getElementById("batch-no");
    let expDate = document.getElementById("exp-date");
    let mrp = document.getElementById("mrp");

    let qty = document.getElementById("qty");
    var checkAvailibility = document.getElementById("aqty").value;

    let qtyType = document.getElementById("qty-type");
    let disc = document.getElementById("disc");
    let dPrice = document.getElementById("dPrice");
    let marginAmount = document.getElementById("margin");
    let gst = document.getElementById("gst");
    let amount = document.getElementById("amount");

    // ============= per item taxable amount ==============
    let taxableAmount = document.getElementById('taxable').value;
    taxableAmount = parseFloat(taxableAmount);

    // ============== per item gst amount calculation ============
    let netGstAmount = (amount.value - taxableAmount * qty.value);
    netGstAmount = parseFloat(netGstAmount);
    // ============ end of amount calculation ==============

    checkAvailibility = Number(checkAvailibility);
    if (qty > checkAvailibility) {
        qty = checkAvailibility;
    }

    let qval = qty.value;
    if (qtyType.value == 'Loose') {
        let typ = ' (L)';
        qval = qval.concat(typ);
    } else {
        qval = qty.value;
    }

    // console.log(qval);
    // console.log(productId.value);
    // console.log(productName.value);
    // console.log(weightage.value);
    // console.log(batchNo.value);
    // console.log(expDate.value);
    // console.log(mrp.value);
    // console.log(qty.value);
    // console.log(disc.value);
    // console.log(dPrice.value);
    // console.log("check gst % : ",gst.value);
    // console.log("check net payble amount : ",amount.value);
    // console.log("taxable amount check : ",taxableAmount); 
    // console.log("net gst amount check : ",netGstAmount); 

    if (billDAte.value != '') {
        if (customer.value != '') {
            if (doctorName.value != '') {
                if (paymentMode.value != '') {
                    if (productId.value != '') {
                        if (productName.value != '') {
                            if (weightage.value != '') {
                                if (batchNo.value != '') {
                                    if (expDate.value != '') {
                                        if (mrp.value != '') {
                                            if (qty.value != '') {
                                                if (disc.value != '') {
                                                    if (dPrice.value != '') {
                                                        if (gst.value != '') {
                                                            if (amount.value != '') {
                                                                // console.log("Working Fine");
                                                                document.getElementById("no-item").style.display = "none";


                                                                let slno = document.getElementById("dynamic-id").value;
                                                                slno++;
                                                                document.getElementById("dynamic-id").value = slno;
                                                                document.getElementById("items").value = slno;


                                                                let finalQty = document.getElementById("final-qty");
                                                                let totalQty = parseFloat(finalQty.value) + parseFloat(qty.value);
                                                                // console.log(totalQty);
                                                                finalQty.value = totalQty;

                                                                ///////////TOTAL GST CALCULATION////////////
                                                                let existsGst = parseFloat(document.getElementById("total-gst").value);
                                                                // var itemAmount = parseFloat(amount.value); // perticular item amount
                                                                // var itemGst = parseFloat(gst.value); // percentage on the perticular item
                                                                // let withoutGst = itemAmount - (itemGst / 100 * itemAmount)
                                                                let netGst = netGstAmount;
                                                                let totalGst = existsGst + netGst;
                                                                // console.log(totalGst);
                                                                document.getElementById("total-gst").value = totalGst.toFixed(2);
                                                                // =========================================
                                                                // dPrice.value * qty.value



                                                                /////////NET MRP CALCULATION//////////
                                                                let totalPrice = document.getElementById("total-price").value;
                                                                let existsPrice = parseFloat(totalPrice);
                                                                var itemMrp = parseFloat(mrp.value);
                                                                itemQty = parseFloat(qty.value);
                                                                itemMrp = itemQty * itemMrp;
                                                                let totalMrp = existsPrice + itemMrp;
                                                                // console.log(totalMrp);
                                                                document.getElementById("total-price").value = totalMrp.toFixed(2);



                                                                ////////////TOTAL PAYABLE //////////////
                                                                let payable = document.getElementById("payable").value;
                                                                let existsPayable = parseFloat(payable);
                                                                itemAmount = parseFloat(amount.value);
                                                                let sum = existsPayable + itemAmount;
                                                                // console.log(sum);
                                                                document.getElementById("payable").value = sum.toFixed(2);

                                                                jQuery("#item-body").append(`<tr id="table-row-${slno}">
                                                <td><i class="fas fa-trash text-danger" onclick="deleteItem(${slno}, ${qty.value}, ${netGst.toFixed(2)}, ${itemMrp.toFixed(2)}, ${amount.value})" style="font-size:.7rem; width: .3rem"></i></td>
                                                <td style="font-size:.7rem; padding-top:1rem; width: .3rem" scope="row">${slno}</td>
                                                <td>
                                                    <input class="summary-product" type="text" name="product-name[]" value="${productName.value}" style="word-wrap: break-word; width:9rem; font-size: .7rem; margin-top: .7rem;" readonly>
                                                    <input type="text" name="product-id[]" value="${productId.value}" hidden>
                                                    <input type="text" name="Manuf[]" value="${Manuf.value}" hidden>

                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="batch-no[]" id="batch-no" value="${batchNo.value}" style="word-wrap: break-word; width:7rem; font-size: .7rem; margin-top: .7rem;" readonly>
                                                </td>

                                                <td>
                                                    <input class="summary-items" type="text" name="weightage[]" value="${weightage.value}" style="word-wrap: break-word; width:3rem; font-size: .7rem; margin-top: .7rem;" readonly>
                                                </td>
                                                
                                                <td>
                                                    <input class="summary-items" type="text" name="exp-date[]" value="${expDate.value}" style="word-wrap: break-word; width:3rem; font-size: .7rem; margin-top: .7rem;" readonly>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="mrp[]" value="${mrp.value}" style="word-wrap: break-word; width:3rem; font-size: .7rem; margin-top: .7rem; text-align: right;" readonly>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="qtyT[]" value="${qval}" style="word-wrap: break-word; width:3rem; font-size: .7rem; margin-top: .7rem;" readonly>
                                                    <input class="summary-items" type="text" name="qty[]" value="${qty.value}" readonly hidden>
                                                </td>
                                                <td hidden>
                                                    <input type="text" id="qty-types" name="qty-types[]" value="${qtyType.value}" hidden>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="disc[]" value="${disc.value}" style="word-wrap: break-word; width:3rem; font-size: .7rem; margin-top: .7rem;" readonly>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="taxable[]" value="${taxableAmount.toFixed(2)}" style="word-wrap: break-word; width:3rem; font-size: .7rem; margin-top: .7rem; text-align: center" readonly>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="gst[]" value="${gst.value}" style="word-wrap: break-word; width:3rem; font-size: .7rem; margin-top: .7rem;" readonly>
                                                </td>

                                                <td class="d-none">
                                                    <input class="summary-items" type="text" name="gstVal[]" value="${netGst.toFixed(2)}" style="word-wrap: break-word; width:3rem; font-size: .7rem; margin-top: .7rem; text-align: right;" readonly>
                                                </td>

                                                <td>
                                                    <input class="summary-items" type="text" name="amount[]" value="${amount.value}" style="word-wrap: break-word; width:3rem; font-size: .7rem; margin-top: .7rem; text-align: right;" readonly>
                                                </td>
                                            </tr>`);


                                            document.getElementById('final-doctor-name').value = doctorName.value;
                                                                document.getElementById("aqty").value = "";
                                                                document.getElementById("add-item-details").reset();


                                                                // document.getElementById("product-id").value = "";
                                                                // document.getElementById("search-Item").value = "";
                                                                // document.getElementById("manuf").value = "";
                                                                // document.getElementById("weightage").value = "";
                                                                // document.getElementById("batch-no").value = "";
                                                                // document.getElementById("exp-date").value = "";
                                                                // document.getElementById("mrp").value = "";
                                                                // document.getElementById("qty").value = "";
                                                                // document.getElementById("qty-type").value = "";
                                                                // document.getElementById("disc").value = "";
                                                                // document.getElementById("dPrice").value = "";
                                                                // document.getElementById("gst").value = "";
                                                                // document.getElementById("amount").value = "";

                                                                // document.getElementById("manuf").value = "";
                                                                // document.getElementById("content").value = "";
                                                                // document.getElementById("loose-stock").value = "";
                                                                // document.getElementById("loose-price").value = "";
                                                                // document.getElementById("ptr").value = "";
                                                                // document.getElementById("margin").value = "";


                                                            } else {
                                                                swal("Failed!", "Total Amount Not Found!", "error");
                                                            }
                                                        } else {
                                                            swal("Failed!", "GST Not Found!", "error");
                                                        }
                                                    } else {
                                                        swal("Failed!", "Discounted Price Not Found!", "error");
                                                    }
                                                } else {
                                                    swal("Failed!", "Please Enter Discount Minimum: 0", "error");
                                                }
                                            } else {
                                                swal("Failed!", "Please Enter Quantity:", "error");
                                            }
                                        } else {
                                            swal("Failed!", "MRP Not Found!", "error");
                                        }

                                    } else {
                                        swal("Failed!", "Expiry Date Not Found!", "error");
                                    }

                                } else {
                                    swal("Failed!", "Batch No Not Found!", "error");
                                }

                            } else {
                                swal("Failed!", "Product Weatage/Unit Not Found!", "error");
                            }
                        } else {
                            swal("Failed!", "Product Name Not Found!", "error");
                        }

                    } else {
                        swal("Failed!", "Product ID Not Found!", "error");
                    }
                } else {
                    swal("Failed!", "Please Select a Payment Mode!", "error");
                }
            } else {
                swal("Failed!", "Please Select/Enter Doctor Name!", "error");

            }
        } else {
            swal("Failed!", "Please Select Customer Name!", "error");
        }
    } else {
        swal("Failed!", "Please Enter Bill Date!", "error");

    }
    event.preventDefault();
}

const deleteItem = (slno, itemQty, gstPerItem, totalMrp, itemAmount) => {

    var sl = slno - 1;

    jQuery(`#table-row-${slno}`).remove();
    slno--;
    document.getElementById("dynamic-id").value = slno;
    // Items 
    var items = document.getElementById("items");
    leftItems = items.value - 1;
    items.value = leftItems;

    var existQty = document.getElementById("final-qty");
    leftQty = existQty.value - itemQty;
    existQty.value = leftQty;

    var existGst = document.getElementById("total-gst");
    leftGst = existGst.value - gstPerItem;
    existGst.value = leftGst;


    var existMrp = document.getElementById("total-price");
    leftMrp = existMrp.value - totalMrp;
    existMrp.value = leftMrp;

    var existAmount = document.getElementById("payable");
    leftAmount = existAmount.value - itemAmount;
    existAmount.value = leftAmount;
}