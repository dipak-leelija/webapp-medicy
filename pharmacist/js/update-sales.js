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
            xmlhttp.onreadystatechange = function() {
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


const counterBill = () => {
    document.getElementById("contact").innerHTML = "";
    document.getElementById("customer").value = "Cash Sales";
    document.getElementById("customer-id").value = "Cash Sales";
    document.getElementById("customer-name").value = "Cash Sales";

}


const getDoctor = (doctor) => {
        document.getElementById("final-doctor-name").value = doctor;
    } // end getCustomer

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
        XML.onreadystatechange = function() {
            if (XML.readyState == 4 && XML.status == 200) {
                searchReult.innerHTML = XML.responseText;
            }
        };
        XML.open('GET', 'ajax/sales-item-list.ajax.php?data=' + searchFor, true);
        XML.send();
    }
}

////////////////////////////////////////////////////////
///////////////////////////////////////////////////////
const stockDetails = (productId) => {
    document.getElementById("product-id").value = productId;
    // alert(productId);
    // console.log(productId);
    document.getElementById("searched-items").style.display = "none";

    var xmlhttp = new XMLHttpRequest();

    // ============== Check Existence ==============
    stockCheckUrl = 'ajax/stock.checkExists.ajax.php?id=' + productId;
    // alert(url);
    xmlhttp.open("GET", stockCheckUrl, false);
    xmlhttp.send(null);
    exist = xmlhttp.responseText;
    if (exist == 1) {
        document.getElementById("exta-details").style.display = "block";

        // ============== Product Name ==============
        stockItemUrl = 'ajax/product.getName.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", stockItemUrl, false);
        xmlhttp.send(null);
        document.getElementById("search-Item").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Weightage ====================
        weightage = 'ajax/product.getWeightage.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", weightage, false);
        xmlhttp.send(null);
        let packWeightage = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Unit ====================
        unitUrl = 'ajax/product.getUnit.ajax.php?id=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        let packUnit = xmlhttp.responseText;
        let packOf = `${packWeightage}${packUnit}`;

        document.getElementById("weightage").value = packOf;

        //==================== Batch-no ====================
        batchUrl = 'ajax/currentStock.getBatch.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", batchUrl, false);
        xmlhttp.send(null);
        document.getElementById("batch-no").value = xmlhttp.responseText;

        //==================== Expiry Date ====================
        expDateUrl = 'ajax/currentStock.getExp.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", expDateUrl, false);
        xmlhttp.send(null);
        document.getElementById("exp-date").value = xmlhttp.responseText;

        //==================== MRP ====================
        mrpUrl = 'ajax/product.getMrp.ajax.php?stockmrp=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== PTR ====================
        ptrUrl = 'ajax/currentStock.getPtr.ajax.php?stockptr=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", ptrUrl, false);
        xmlhttp.send(null);
        document.getElementById("ptr").innerHTML = xmlhttp.responseText;
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
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", manufUrl, false);
        xmlhttp.send(null);
        document.getElementById("manuf").innerHTML = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Margin on an Item ====================
        marginUrl = 'ajax/product.stockDetails.getMargin.ajax.php?id=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", marginUrl, false);
        xmlhttp.send(null);
        document.getElementById("margin").innerHTML = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        // ============== Check Loose Qty ==============
        checkLoosePackUrl = 'ajax/currentStock.checkLoosePack.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", checkLoosePackUrl, false);
        xmlhttp.send(null);
        let loosePack = xmlhttp.responseText;
        if (loosePack > 0) {
            document.getElementById("mrp").value = '';
            document.getElementById("loose-stock").innerHTML = ' ' + loosePack;

            loosePriceUrl = 'ajax/currentStock.looseMrp.ajax.php?id=' + productId;
            // alert(url);
            xmlhttp.open("GET", loosePriceUrl, false);
            xmlhttp.send(null);

            document.getElementById("loose-price").innerHTML = ' ' + xmlhttp.responseText;
            document.getElementById("qty-type").removeAttribute("disabled");
        } else {
            document.getElementById("qty-type").setAttribute("disabled", true);
            document.getElementById("loose-stock").innerHTML = 'None';
            document.getElementById("loose-price").innerHTML = 'None';
        }
        // alert(xmlhttp.responseText);   
    } else {
        document.getElementById("search-Item").value = '';

        document.getElementById("weightage").value = '';

        document.getElementById("batch-no").value = '';

        document.getElementById("exp-date").value = '';

        document.getElementById("mrp").value = '';

        document.getElementById("gst").value = '';
        document.getElementById("qty-type").setAttribute("disabled", true);

        document.getElementById("loose-stock").innerHTML = 'None';
        document.getElementById("loose-price").innerHTML = 'None';

        document.getElementById("exta-details").style.display = "none";

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

const onQty = (qty) => {

    if (qty > 0) {
        let mrp = document.getElementById("mrp").value;
        let disc = document.getElementById("disc");
        // alert(disc.value);

        let subtotal = mrp * qty;
        let amount = subtotal - (disc.value / 100 * subtotal);
        document.getElementById("amount").value = amount.toFixed(2);

        let discPrice = amount / qty;
        document.getElementById("dPrice").value = discPrice.toFixed(2);
    } else {
        document.getElementById("dPrice").value = '';
        document.getElementById("amount").value = '';

    }

}

const ondDisc = (disc) => {
    let mrp = document.getElementById("mrp").value;
    let qty = document.getElementById("qty").value;
    // alert(disc);

    let subtotal = mrp * qty;
    let amount = subtotal - (disc / 100 * subtotal);
    document.getElementById("amount").value = amount.toFixed(2);

    let discPrice = amount / qty;
    document.getElementById("dPrice").value = discPrice.toFixed(2);

}
const mrpUpdate = (mrpType) => {
    let xmlhttp = new XMLHttpRequest();
    let productId = document.getElementById("product-id").value;

    if (mrpType == "Pack") {
        mrpUrl = 'ajax/product.getMrp.ajax.php?stockmrp=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

    } else {
        loosePriceUrl = 'ajax/currentStock.looseMrp.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", loosePriceUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("mrp").value = xmlhttp.responseText;
    }
}

const addSummary = () => {
    let billDAte = document.getElementById("bill-date");
    let customer = document.getElementById("customer");
    let doctorName = document.getElementById("doctor-name");
    let paymentMode = document.getElementById("payment-mode");


    let productId = document.getElementById("product-id");
    let productName = document.getElementById("search-Item");
    let Manuf = document.getElementById("manuf");
    let weightage = document.getElementById("weightage");
    let batchNo = document.getElementById("batch-no");
    let expDate = document.getElementById("exp-date");
    let mrp = document.getElementById("mrp");
    let qty = document.getElementById("qty");
    let qtyType = document.getElementById("qty-type");
    let disc = document.getElementById("disc");
    let dPrice = document.getElementById("dPrice");
    let gst = document.getElementById("gst");
    let amount = document.getElementById("amount");

    // console.log(productId.value);
    // console.log(productName.value);
    // console.log(weightage.value);
    // console.log(batchNo.value);
    // console.log(expDate.value);
    // console.log(mrp.value);
    // console.log(qty.value);
    // console.log(disc.value);
    // console.log(dPrice.value);
    // console.log(gst.value);
    // console.log(amount.value);
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


                                                                let slno = document.getElementById("dynamic-id").value;
                                                                slno++;
                                                                document.getElementById("dynamic-id").value = slno;
                                                                document.getElementById("items").value = slno;


                                                                let finalQty = document.getElementById("final-qty");
                                                                let totalQty = parseFloat(finalQty.value) + parseFloat(qty.value);
                                                                // console.log(totalQty);
                                                                finalQty.value = totalQty;

                                                                ////////////////TOTAL GST CALCULATION//////////////////////
                                                                let existsGst = parseFloat(document.getElementById("total-gst").value);
                                                                var itemAmount = parseFloat(amount.value);
                                                                var itemGst = parseFloat(gst.value);
                                                                let withoutGst = itemAmount - (itemGst / 100 * itemAmount)
                                                                let netGst = itemAmount - withoutGst;
                                                                let totalGst = existsGst + netGst;
                                                                // console.log(totalGst);
                                                                document.getElementById("total-gst").value = totalGst.toFixed(2);
                                                                // =========================================
                                                                // dPrice.value * qty.value



                                                                ////////////////TOTAL MRP CALCULATION//////////////////////
                                                                let totalPrice = document.getElementById("total-price").value;
                                                                let existsPrice = parseFloat(totalPrice);
                                                                var itemMrp = parseFloat(mrp.value);
                                                                itemQty = parseFloat(qty.value);
                                                                itemMrp = itemQty * itemMrp;
                                                                let totalMrp = existsPrice + itemMrp;
                                                                // console.log(totalMrp);
                                                                document.getElementById("total-price").value = totalMrp.toFixed(2);



                                                                /////////////////TOTAL PAYABLE ///////////////////////
                                                                let payable = document.getElementById("payable").value;
                                                                let existsPayable = parseFloat(payable);
                                                                itemAmount = parseFloat(amount.value);
                                                                let sum = existsPayable + itemAmount;
                                                                // console.log(sum);
                                                                document.getElementById("payable").value = sum.toFixed(2);


                                                                jQuery("#item-body").append(`<tr id="table-row-${slno}">
                                                <td  style="color: red;><i class="fas fa-trash" onclick="deleteItem(${slno}, ${qty.value}, ${netGst}, ${mrp.value}, ${amount.value})"></i></td>
                                                <td>
                                                    <input class="summary-product" type="text" name="product-name[]" value="${productName.value}" readonly>
                                                    <input type="text" name="product-id[]" value="${productId.value}" hidden>
                                                    <input type="text" name="Manuf[]" value="${Manuf.innerText}" hidden>

                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="weightage[]" value="${weightage.value}" readonly>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="batch-no[]" value="${batchNo.value}" readonly>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="exp-date[]" value="${expDate.value}" readonly>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="mrp[]" value="${mrp.value}" readonly>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="qty[]" value="${qty.value}" readonly>
                                                    <input type="text" id="qty-types" name="qty-types[]" value="${qtyType.value}" hidden>

                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="disc[]" value="${disc.value}" readonly>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="dPrice[]" value="${dPrice.value}" readonly>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="gst[]" value="${gst.value}" readonly>
                                                    <input type="text" name="netGst[]" value="${netGst}" hidden>
                                                </td>
                                                <td>
                                                    <input class="summary-items" type="text" name="amount[]" value="${amount.value}" readonly>
                                                </td>
                                            </tr>`);





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

}


const deleteItem = (slno, itemQty, gstPerItem, totalMrp, itemAmount) => {
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
    leftGst = leftGst.toFixed(2);
    existGst.value = leftGst;


    var existMrp = document.getElementById("total-price");
    leftMrp = existMrp.value - totalMrp;
    existMrp.value = leftMrp;

    var existAmount = document.getElementById("payable");
    leftAmount = existAmount.value - itemAmount;
    existAmount.value = leftAmount;

    // document.getElementById("no-item").style.display = "none";
}