const getItemList = (t) => {

    let id = t.value;
    let distributirName = t.selectedOptions[0].text;


    var xmlhttp = new XMLHttpRequest();
    let billIdUrl = `ajax/return-item-list.ajax.php?dist-id=${id}`;
    xmlhttp.open("GET", billIdUrl, false);
    xmlhttp.send(null);
    document.getElementById("product-select").innerHTML = xmlhttp.responseText;
    // alert(xmlhttp.responseText);

    document.getElementById("dist-id").value = id;
    document.getElementById("dist-name").value = distributirName;

    document.getElementById("product-select").style.display = "block";
}





const getItem = (batch) => {
    var xmlhttp = new XMLHttpRequest();
    let billIdUrl = `ajax/purchase-bill-details.ajax.php?batch-item=${batch}`;
    xmlhttp.open("GET", billIdUrl, false);
    xmlhttp.send(null);
    document.getElementById("product-id").innerHTML = xmlhttp.responseText;
    // alert(xmlhttp.responseText);
}



// item search
function searchItem(input) {
    if (input != '') {
        document.getElementById("product-select").style.display = "block";

        // let input = document.getElementById('searchbar').value
        input = input.toLowerCase();
        let x = document.getElementsByClassName('item-list');

        for (i = 0; i < x.length; i++) {
            if (!x[i].innerHTML.toLowerCase().includes(input)) {
                x[i].style.display = "none";
            } else {
                x[i].style.display = "flex";
            }
        }
    } else {
        document.getElementById("product-select").style.display = "none";

    }
}


const setMode = (returnMode) => {
    document.getElementById("refund-mode").value = returnMode;
}


const getDtls = (batchNo, productId, productName, billdate) => {

    document.getElementById('batch-number').value = batchNo;
    document.getElementById('product-name').value = productName;
    document.getElementById('bill-date').value = billdate;

    var xmlhttp = new XMLHttpRequest();
    if (productId != "") {

        document.getElementById("product-id").value = productId;

        //==================== Expiry Date ====================
        let expUrl = `ajax/stockIn.all.ajax.php?stock-exp=${batchNo}`;
        // alert(expUrl);
        xmlhttp.open("GET", expUrl, false);
        xmlhttp.send(null);
        document.getElementById("exp-date").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Weightage ====================
        let weatageUrl = `ajax/stockIn.all.ajax.php?weightage=${batchNo}`;
        // alert(url);
        xmlhttp.open("GET", weatageUrl, false);
        xmlhttp.send(null);
        document.getElementById("weatage").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Unit ====================
        let unitUrl = `ajax/stockIn.all.ajax.php?unit=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        document.getElementById("unit").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);


        //==================== PTR ====================
        let ptrUrl = `ajax/stockIn.all.ajax.php?ptr=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", ptrUrl, false);
        xmlhttp.send(null);
        document.getElementById("ptr").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);


        //==================== DISC ====================
        let discUrl = `ajax/stockIn.all.ajax.php?discount=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", discUrl, false);
        xmlhttp.send(null);
        document.getElementById("discount").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);


        //==================== GST ====================
        let gstUrl = `ajax/stockIn.all.ajax.php?gst=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        document.getElementById("gst").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);


        //==================== Taxable ====================
        let taxableUrl = `ajax/stockIn.all.ajax.php?taxableUrl=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", taxableUrl, false);
        xmlhttp.send(null);
        document.getElementById("taxable").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);


        //==================== MRP ====================
        let mrpUrl = `ajax/stockIn.all.ajax.php?mrp=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);


        //==================== Amount ====================
        let amountUrl = `ajax/stockIn.all.ajax.php?amount=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", amountUrl, false);
        xmlhttp.send(null);
        document.getElementById("amount").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);


        //==================== QTY ====================
        let qtyUrl = `ajax/stockIn.all.ajax.php?purchased-qty=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", qtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("purchased-qty").value = xmlhttp.responseText;



        //==================== FREE QTY ====================
        let freeQtyUrl = `ajax/stockIn.all.ajax.php?free-qty=${batchNo}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", freeQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("free-qty").value = xmlhttp.responseText;


        //==================== CURRENT QTY ====================
        let currentQtyUrl = `ajax/currentStock.allDetails.ajax.php?batch=${batchNo}&product=${productId}`;
        // alert(currentQtyUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", currentQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("current-qty").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);



        document.getElementById("return-qty").focus();
        document.getElementById("product-select").style.display = "none";


    } else {

        document.getElementById("ptr").value = "";
        document.getElementById("unit").value = "";
        document.getElementById("mrp").value = "";
        document.getElementById("gst").value = "";
        document.getElementById("product-id").value = "";
        document.getElementById("product-id").value = "";
        document.getElementById('product-name').value = "";

    }

}


// #######################################################################

// var todayDate = new Date();

// var date = todayDate.getDate();
// var month = todayDate.getMonth() + 1;
// var year = todayDate.getFullYear();

// if (date < 10) {
//     date = '0' + date;
// }
// if (month < 10) {
//     month = '0' + month;
// }
// var todayFullDate = year + "-" + month + "-" + date;
// // console.log(todayFullDate);
// document.getElementById("bill-date").setAttribute("max", todayFullDate);

// #######################################################################


// const getbillDate = (billDate) => {
//     billDate = document.getElementById("bill-date").value;
//     // alert(billDate.value);
//     billDate = billDate.value;
//     // alert(billDate.substr(0, 4));
//     // alert(billDate.substr(5, 2));
//     // alert(billDate.substr(8, 2));
//     // document.getElementById("due-date").setAttribute("min", billDate);

//     // var date2 = todayDate.getDate() + 7;
//     // // console.log(date2);
//     // var todayFullDate2 = year + "-" + month + "-" + date2;
//     // document.getElementById("due-date").setAttribute("max", todayFullDate2);

// }

const getRefund = (returnQty) => {
    returnQty = parseInt(returnQty);

    if (isNaN(returnQty)) {
        document.getElementById("refund-amount").value = '';
        return;
    }
    if (returnQty != '') {
        // alert(returnQty);
        let ptr = document.getElementById("ptr");
        let currentQty = document.getElementById("current-qty");
        let gst = document.getElementById("gst");
        //console.log(parseInt(currentQty.value));
        if (returnQty <= currentQty.value) {
            //console.log(returnQty);
            let subtotal = returnQty * ptr.value;
            let refund = subtotal + (gst.value / 100 * subtotal);

            document.getElementById("refund-amount").value = refund.toFixed(2);
        } else {
            document.getElementById("refund-amount").value = '';
            swal("Error", "Return Quantity Must Less Then Avilable Quantity", "error");
        }
    } else {
        alert("NULL");
        document.getElementById("refund-amount").value = '';
    }
}






// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
function addData() {
    var distId = document.getElementById("distributor-id");
    //var billNumber = document.getElementById("bill-number");
    var batchNumber = document.getElementById("batch-number");
    var billDate = document.getElementById("bill-date");
    var returnMode = document.getElementById("return-mode");

    var productId = document.getElementById("product-id");
    var productName = document.getElementById('product-name').value;
    // productName = productName.slice(11);
    // alert(productId.value);
    // alert(productName);

    // return;


    var expDate = document.getElementById("exp-date");
    var weatage = document.getElementById("weatage");
    var unit = document.getElementById("unit");
    var ptr = document.getElementById("ptr");
    var discount = document.getElementById("discount");
    var gst = document.getElementById("gst");
    var taxable = document.getElementById("taxable");
    var mrp = document.getElementById("mrp");
    var amount = document.getElementById("amount");
    var purchasedQty = document.getElementById("purchased-qty");
    var freeQty = document.getElementById("free-qty");
    var currentQty = document.getElementById("current-qty");
    var returnQty = document.getElementById("return-qty");
    var returnFreeQty = document.getElementById("return-free-qty");

    var refundAmount = document.getElementById("refund-amount");

    var qtyVal = document.getElementById("total-refund-qty");




    if (distId.value == "") {
        swal("Oops", "Please select Distributor!", "error");
        distId.focus();
        return;
    }
    // if (billNumber.value == "") {
    //     swal("Oops", "Please select Bill Number!", "error");
    //     billNumber.focus();
    //     return;
    // }
    if (batchNumber.value == "") {
        swal("Oops", "Please select Batch Number!", "error");
        batchNumber.focus();
        return;
    }
    if (billDate.value == "") {
        swal("Oops", "Unable to Select Bill Date!", "error");
        billDate.focus();
        return;
    }
    if (returnMode.value == "") {
        swal("Oops", "Please select your refund mode!", "error");
        returnMode.focus();
        return;
    }


    if (productName == "") {
        swal("Oops", "Product name can't find!", "error");
        return;
    }
    if (productId.value == "") {
        swal("Oops", "Product name can't be empty!", "error");
        productId.focus();
        return;
    }
    if (expDate.value == "") {
        swal("Oops", "Unable to get Expiry Date!", "error");
        expDate.focus();
        return;
    }
    if (weatage.value == "") {
        weatage.focus();
        swal("Oops", "Unable to get product weatage!", "error");
        return;
    }
    if (unit.value == "") {
        unit.focus();
        swal("Oops", "Unable to get product unit!", "error");
        return;
    }
    if (ptr.value == "") {
        ptr.focus();
        swal("Oops", "Unable to get product ptr!", "error");
        return;
    }
    if (discount.value == "") {
        discount.focus();
        swal("Oops", "Unable to get product discount!", "error");
        return;
    }
    if (gst.value == "") {
        gst.focus();
        swal("Oops", "Unable to get product GST!", "error");
        return;
    }
    if (taxable.value == "") {
        taxable.focus();
        swal("Oops", "Unable to get product tax amount!", "error");
        return;
    }
    if (mrp.value == "") {
        mrp.focus();
        swal("Oops", "Unable to get product MRP!", "error");
        return;
    }
    if (amount.value == "") {
        amount.focus();
        swal("Oops", "Unable to get product amount!", "error");
        return;
    }
    if (purchasedQty.value == "") {
        purchasedQty.focus();
        swal("Oops", "Unable to get product purchased quantity!", "error");
        return;
    }
    if (freeQty.value == "") {
        freeQty.focus();
        swal("Oops", "Unable to get product free quantity!", "error");
        return;
    }
    if (currentQty.value == "") {
        currentQty.focus();
        swal("Oops", "Unable to get product current quantity!", "error");
        return;
    }
    if (returnQty.value == "") {
        returnQty.focus();
        swal("Oops", "Please Enter How many Quantity You Want to Return!", "error");
        return;
    }
    if (returnFreeQty.value == "") {
        returnQty.focus();
        swal("Oops", "Free Quantity Field can not be blank!", "error");
        return;
    }

    if (refundAmount.value == "") {
        refundAmount.focus();
        swal("Oops", "Unable to get Refund Amount!", "error");
        return;
    }


    // swal("Nice", "Working Fine", "success")

    let slno = document.getElementById("dynamic-id").value;
    slno++;

    document.getElementById("dynamic-id").value = slno;

    //geting total qty value
    //qtyVal.value = parseFloat(returnQty.value) + parseFloat(qtyVal.value);


    //geeting total refund amount
    var refund = document.getElementById("refund");
    refund.value = parseFloat(refund.value) + parseFloat(refundAmount.value);


    // return gst generating
    let withoutGst = (ptr.value * returnQty.value);
    // console.log(withoutGst);
    let taxAmount = (gst.value / 100 * withoutGst);
    // console.log(taxAmount);
    var returnGstAmount = document.getElementById("return-gst");
    returnGstAmount.value = parseFloat(returnGstAmount.value) + taxAmount;


    const appendData = () => {

        jQuery("#dataBody")
            .append(`<tr id="table-row-${slno}">
                    <td  style="color: red;">
                        <i class="fas fa-trash pt-3" onclick="deleteData(${slno}, ${returnQty.value}, ${taxAmount}, ${refundAmount.value})"></i>
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-12r" type="text" name="productName[]" value="${productName}" readonly style="text-align: start;">
                        <input class="col table-data w-12r" type="text" name="productId[]" value="${productId.value}" readonly style="text-align: start;" hidden>
                    </td>
                    <td class="p-0 pt-3" >
                        <input class="col table-data w-6r" type="text" name="batchNo[]" value="${batchNumber.value}" readonly>
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-5r" type="text" name="expDate[]" value="${expDate.value}" readonly>
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-5r" type="text" name="setof[]" value="${weatage.value}${unit.value}" readonly>
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-5r" type="text" name="purchasedQty[]" value="${purchasedQty.value}" readonly>
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-5r" type="text" name="freeQty[]" value="${freeQty.value}" readonly>
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-5r" type="text" name="mrp[]" value="${mrp.value}" readonly>
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-6r" type="text" name="ptr[]" value="${ptr.value}" readonly>
                    </td>
                    <td class="p-0 pt-3" >
                        <input class="col table-data w-6r" type="text" name="purchase-amount[]" value="${amount.value}" readonly>
                    </td>
                    <td class="p-0 ps-1 pt-3">
                        <input class="col table-data w-4r" type="text" name="gst[]" value="${gst.value}" readonly>
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-8r" type="text" name="return-qty[]" value="${parseFloat(returnQty.value) + parseFloat(returnFreeQty.value)}" readonly>
                    </td>
                    <td class=" amnt-td p-0 pt-3">
                        <input class="col table-data W-6r" type="text" name="refund-amount[]" value="${refundAmount.value}" readonly></td>
                </tr>`);

        return true;
    }

    if (appendData() === true) {



        if (slno > 1) {
            let id = document.getElementById("items-qty");
            let newId = parseFloat(id.value) + 1;
            document.getElementById("items-qty").value = newId;

        } else {
            document.getElementById("items-qty").value = slno;
        }


        if (slno > 1) {
            let Qty = parseInt(qtyVal.value);

            let newQty = Qty + parseInt(returnQty.value);
            document.getElementById("total-refund-qty").value = newQty;

        } else {
            document.getElementById("total-refund-qty").value = parseInt(returnQty.value);
        }

        // document.getElementById("demo").innerHTML = await myPromise;
        productId.value = '';
        productName = '';

        batchNumber.value = '';
        billDate.value = '';

        expDate.value = '';
        weatage.value = '';
        unit.value = '';
        ptr.value = '';
        discount.value = '';
        gst.value = '';
        taxable.value = '';
        mrp.value = '';
        amount.value = '';
        purchasedQty.value = '';
        freeQty.value = '';
        currentQty.value = '';
        returnQty.value = '';
        returnFreeQty.value = '';
        refundAmount.value = '';
    };

} //eof addData  

// ================================ Delet Data ================================


const deleteData = (slno, itemQty, gstPerItem, total) => {
    jQuery(`#table-row-${slno}`).remove();
    slno--;
    document.getElementById("dynamic-id").value = slno;

    //minus item
    let items = document.getElementById("items-qty");
    let finalItem = items.value - 1;
    items.value = finalItem;

    // minus quantity
    let qty = document.getElementById("total-refund-qty");
    let finalQty = qty.value - itemQty
    qty.value = finalQty;


    // minus gst
    let gst = document.getElementById("return-gst");
    let finalGst = gst.value - gstPerItem;
    gst.value = finalGst;

    // minus netAmount
    let net = document.getElementById("net-amount");
    if (net.value == null) {
        net.value = 0;
        let finalAmount = net.value - total;
        net.value = finalAmount;
    } else {
        let finalAmount = net.value - total;
        net.value = finalAmount;
    }


}