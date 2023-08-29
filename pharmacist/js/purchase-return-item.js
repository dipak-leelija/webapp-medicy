const getBillList = (t) => {

    let id = t.value;
    let distributirName = t.selectedOptions[0].text;
    var xmlhttp = new XMLHttpRequest();
    let distIdUrl = `ajax/return-distributor-bill-list.ajax.php?dist-id=${id}`;
    xmlhttp.open("GET", distIdUrl, false);
    xmlhttp.send(null);
    document.getElementById("select-bill").innerHTML = xmlhttp.responseText;
    // alert(xmlhttp.responseText);

    document.getElementById("dist-id").value = id;
    document.getElementById("dist-name").value = distributirName;

    document.getElementById("select-bill").style.display = "block";
}


const getItemList = (distId, billNo) =>{

    document.getElementById("select-bill-no").value = billNo;
    document.getElementById("select-bill").style.display = "none";
    let distBillNoCheck = document.getElementById("dist-bill-no").value;

    if(distBillNoCheck == ""){
        document.getElementById("dist-bill-no").value = billNo;
    }

    if(distBillNoCheck != ""){
        if(billNo != distBillNoCheck){
            swal("Oops", "Distributor bill no changed!", "error");
            window.location.reload();
        }
        
    }

    var xmlhttp = new XMLHttpRequest();
    let billNoUrl = `ajax/return-item-list.ajax.php?bill-no=${billNo}&dist-id=${distId}`;
    xmlhttp.open("GET", billNoUrl, false);
    xmlhttp.send(null);
    document.getElementById("product-select").innerHTML = xmlhttp.responseText;
    // alert(xmlhttp.responseText);

    document.getElementById("dist-id").value = distId;

    document.getElementById("product-select").style.display = "block";
    document.getElementById("select-bill").style.display = "none";

}

// item search
function searchItem(input) {
    console.log(input);
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

const getDtls = (stokInDetialsId, batchNo, productId, productName, billdate) => {
    // alert(stokInDetialsId);
    document.getElementById('stokInDetailsId').value = stokInDetialsId;
    document.getElementById('batch-number').value = batchNo;
    document.getElementById('product-name').value = productName;
    document.getElementById('bill-date').value = billdate;

    var xmlhttp = new XMLHttpRequest();
    if (productId != "") {

        document.getElementById("product-id").value = productId;

        //==================== Expiry Date ====================
        let expUrl = `ajax/stockIn.all.ajax.php?stock-exp=${stokInDetialsId}`;
        // alert(expUrl);
        xmlhttp.open("GET", expUrl, false);
        xmlhttp.send(null);
        document.getElementById("exp-date").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Weightage ====================
        let weatageUrl = `ajax/stockIn.all.ajax.php?weightage=${stokInDetialsId}`;
        // alert(url);
        xmlhttp.open("GET", weatageUrl, false);
        xmlhttp.send(null);
        document.getElementById("weatage").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Unit ====================
        let unitUrl = `ajax/stockIn.all.ajax.php?unit=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        document.getElementById("unit").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== PTR ====================
        let ptrUrl = `ajax/stockIn.all.ajax.php?ptr=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", ptrUrl, false);
        xmlhttp.send(null);
        document.getElementById("ptr").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== DISC ====================
        let discUrl = `ajax/stockIn.all.ajax.php?discount=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", discUrl, false);
        xmlhttp.send(null);
        document.getElementById("discount").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== GST ====================
        let gstUrl = `ajax/stockIn.all.ajax.php?gst=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        document.getElementById("gst").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== GST Amount Per Quantity ====================
        let GstAmountPerQuantity = `ajax/stockIn.all.ajax.php?gstAmountPerQuantity=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", GstAmountPerQuantity, false);
        xmlhttp.send(null);
        document.getElementById("gstAmountPerQty").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        // //==================== gstAmount ====================
        // let gstAmountUrl = `ajax/stockIn.all.ajax.php?gstAmountUrl=${stokInDetialsId}`;
        // // alert(unitUrl);
        // // window.location.href = unitUrl;
        // xmlhttp.open("GET", gstAmountUrl, false);
        // xmlhttp.send(null);
        // document.getElementById("gst-amount").value = xmlhttp.responseText;
        // // alert(xmlhttp.responseText);

        //==================== taxable ====================
        let taxableUrl = `ajax/stockIn.all.ajax.php?taxable=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", taxableUrl, false);
        xmlhttp.send(null);
        document.getElementById("taxable").value = parseFloat(xmlhttp.responseText).toFixed(2);
        // alert(xmlhttp.responseText);

        //==================== base price ====================
        let baseUrl = `ajax/stockIn.all.ajax.php?base=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", baseUrl, false);
        xmlhttp.send(null);
        document.getElementById("base").value = parseFloat(xmlhttp.responseText).toFixed(2);

        //==================== MRP ====================
        let mrpUrl = `ajax/stockIn.all.ajax.php?mrp=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Amount ====================
        let amountUrl = `ajax/stockIn.all.ajax.php?amount=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", amountUrl, false);
        xmlhttp.send(null);
        document.getElementById("amount").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== QTY ====================
        let qtyUrl = `ajax/stockIn.all.ajax.php?purchased-qty=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", qtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("purchased-qty").value = xmlhttp.responseText;

        //==================== FREE QTY ====================
        let freeQtyUrl = `ajax/stockIn.all.ajax.php?free-qty=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", freeQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("free-qty").value = xmlhttp.responseText;

        //==================== NET BUY QTY ====================
        let netBuyQtyUrl = `ajax/stockIn.all.ajax.php?net-buy-qty=${stokInDetialsId}`;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", netBuyQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("net-buy-qty").value = xmlhttp.responseText;

        //==================== LIVE BUY QTY ====================
        let liveBuyQtyUrl = `ajax/stokReturn.allDetails.ajax.php?current-stock-qty=${stokInDetialsId}`;
        // alert(currentQtyUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", liveBuyQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("current-purchase-qty").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== LIVE FREE QTY ====================
        let liveFreeQtyUrl = `ajax/stokReturn.allDetails.ajax.php?current-free-qty=${stokInDetialsId}`;
        // alert(currentQtyUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", liveFreeQtyUrl, false);
        xmlhttp.send(null);
        document.getElementById("current-free-qty").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== CURRENT QTY ====================
        let currentQtyUrl = `ajax/currentStock.liveQtyDetails.ajax.php?currentQTY=${stokInDetialsId}`;
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
        document.getElementById('gstAmountPerQty').value = "";

    }
}

const checkFQty = (returnFqty) => {
    returnFqty = parseInt(returnFqty);
    var CurrentFQty = document.getElementById("current-free-qty").value;

    if (CurrentFQty < returnFqty) {
        swal("Oops", "Return Quantity must be leser than Current Free Qantity!", "error")
        document.getElementById("return-free-qty").value = 0;
    }
}

const getRefund = (returnQty) => {
    returnQty = parseInt(returnQty);
    let currentQTY = document.getElementById("current-purchase-qty").value;

    if (parseInt(currentQTY) < parseInt(returnQty)) {

        swal("Oops", "Return Quantity must be leser than Current Buy Qantity!", "error")
        document.getElementById("return-qty").value = 0;
    }

    if (isNaN(returnQty)) {
        document.getElementById("refund-amount").value = '';
        re
        turn;
    }
    if (returnQty != '') {
        console.log(returnQty);
        let ptr = document.getElementById("ptr").value;
        let gst = document.getElementById("gst").value;
        let discParcent = document.getElementById("discount").value;
        console.log("check ptr, gst parcent, and discount parcent : ", ptr, gst, discParcent);
        let subtotal = (parseFloat(ptr) - (parseFloat(ptr) * parseFloat(discParcent)/100)) * returnQty;
        console.log("sub total on qty : ", subtotal);
        let refund = subtotal + (subtotal * (parseFloat(gst) / 100 ));

        document.getElementById("refund-amount").value = refund.toFixed(2);


    } else {
        document.getElementById("refund-amount").value = '';
    }

    // let gstPercetn = document.getElementById("");

    let returnGstAmount = document.getElementById('gstAmountPerQty').value;
    returnGstAmount = returnGstAmount * returnQty;
    document.getElementById('return-gst-amount').value = returnGstAmount;
}






// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
function addData() {
    
    var distId = document.getElementById("distributor-id");
    //var billNumber = document.getElementById("bill-number");
    var stokInDetailsId = document.getElementById("stokInDetailsId");
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
    var RtrnGstAmount = document.getElementById("return-gst-amount");
    var gstAmount = document.getElementById("gst-amount");
    var mrp = document.getElementById("mrp");
    var amount = document.getElementById("amount");
    var purchasedQty = document.getElementById("purchased-qty");
    var freeQty = document.getElementById("free-qty");
    var currentQty = document.getElementById("current-qty");
    var returnQty = document.getElementById("return-qty");
    var returnFreeQty = document.getElementById("return-free-qty");

    var refundAmount = document.getElementById("refund-amount");

    var qtyVal = document.getElementById("total-return-qty");

    // console.log("return free qty : ",returnFreeQty.value);
    // console.log("return qty : ",returnQty.value);
    var totalReturnQty = parseInt(returnFreeQty.value) + parseInt(returnQty.value);
    // console.log("total return qty : ",totalReturnQty);

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
    let withoutGst = (parseFloat(ptr.value) - (parseFloat(ptr.value) * parseFloat(discount.value) / 100)) * returnQty.value;
    let taxAmount = parseFloat(refundAmount.value) - withoutGst;
    

    var returnGstAmount = document.getElementById("return-gst-val").value;
    // console.log("return gst total amount =>");
    // console.log(returnGstAmount);
    returnGstAmount = parseFloat(returnGstAmount) + parseFloat(taxAmount);
    let ReturnGstAmount = returnGstAmount.toFixed(2);
    // console.log("return gst total amount calculation =>");
    // console.log(ReturnGstAmount);
    document.getElementById("return-gst-val").value = ReturnGstAmount;

    const appendData = () => {

        jQuery("#dataBody")
            .append(`<tr id="table-row-${slno}">
                    <td  style="color: red;">
                        <i class="fas fa-trash pt-3" onclick="deleteData(${slno}, ${returnQty.value}, ${taxAmount}, ${refundAmount.value})"></i>
                    </td>
                    <td style="font-size:.8rem ; padding-top:1.2rem"scope="row">${slno}</td>
                    <td class="p-0 pt-3" hidden>
                        <input class="d-none col table-data w-6r" type="text" name="stok-in-details-id[]" value="${stokInDetailsId.value}" readonly>
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-10r" type="text" name="productName[]" value="${productName}" readonly style="text-align: start; font-size:0.7rem">
                        <input class="col table-data w-10r" type="text" name="productId[]" value="${productId.value}" readonly style="text-align: start;" hidden>
                    </td>
                    <td class="p-0 pt-3" >
                        <input class="col table-data w-6r" type="text" name="batchNo[]" value="${batchNumber.value}" readonly  style="text-align: start; font-size:0.7rem">
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="expDate[]" value="${expDate.value}" readonly  style="text-align: start; font-size:0.7rem padding-right:0.1rem"">
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="setof[]" value="${weatage.value}${unit.value}" readonly  style="text-align: start; font-size:0.7rem;">
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-3r" type="text" name="purchasedQty[]" value="${purchasedQty.value}" readonly style="text-align: start; font-size:0.7rem">
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-3r" type="text" name="freeQty[]" value="${freeQty.value}" readonly  style="text-align: start; font-size:0.7rem">
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="mrp[]" value="${mrp.value}" readonly  style="text-align: start; font-size:0.7rem">
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="ptr[]" value="${ptr.value}" readonly style="text-align: start; font-size:0.7rem">
                    </td>
                    <td class="p-0 pt-3" >
                        <input class="col table-data w-3r" type="text" name="disc-percent[]" value="${discount.value}%" readonly  style="text-align: start; font-size:0.7rem">
                    </td>
                    <td class="p-0 ps-1 pt-3">
                        <input class="col table-data w-3r" type="text" name="gst[]" value="${gst.value}%" readonly style="border-radius: 30%; font-size: .7rem; width:2rem; text-align:center; padding-right:0rem; padding-left:0rem">
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-3r" type="text" name="return-qty[]" value="${parseFloat(returnQty.value)}" readonly  style="text-align: start; font-size:0.7rem">
                    </td>
                    <td class="p-0 pt-3">
                        <input class="col table-data w-3r" type="text" name="return-free-qty[]" value="${parseFloat(returnFreeQty.value)}" readonly  style="text-align: start; font-size:0.7rem">
                    </td>
                    <td class=" amnt-td p-0 pt-3">
                        <input class="col table-data W-4r" type="text" name="refund-amount[]" value="${refundAmount.value}" readonly style="text-align: start; font-size:0.7rem"></td>
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

            let newQty = Qty + totalReturnQty;
            document.getElementById("total-return-qty").value = newQty;

        } else {
            document.getElementById("total-return-qty").value = totalReturnQty;
        }

        // document.getElementById("demo").innerHTML = await myPromise;
        document.getElementById("stokInDetailsId").value = '';
        document.getElementById("product-id").value = '';
        document.getElementById('product-name').value = '';

        document.getElementById("batch-number").value = '';
        document.getElementById("bill-date").value = '';
        document.getElementById("exp-date").value = '';
        document.getElementById("weatage").value = '';
        document.getElementById("unit").value = '';

        document.getElementById("ptr").value = '';
        document.getElementById("discount").value = '';
        document.getElementById("gst").value = '';
        // document.getElementById("gst-amount").value = '';
        document.getElementById("mrp").value = '';
        document.getElementById("amount").value = '';

        document.getElementById("purchased-qty").value = '';
        document.getElementById("free-qty").value = '';
        document.getElementById("net-buy-qty").value = '';
        document.getElementById("current-qty").value = '';
        document.getElementById("return-qty").value = '';
        document.getElementById("return-free-qty").value = '';
        document.getElementById("refund-amount").value = '';
        document.getElementById('gstAmountPerQty').value = "";

    }

} //eof addData  

// ================================ Delet Data ================================


const deleteData = (slno, itemQty, gstPerItem, refundPerItem) => {
    jQuery(`#table-row-${slno}`).remove();
    slno--;
    document.getElementById("dynamic-id").value = slno;

    //minus item
    let items = document.getElementById("items-qty");
    let finalItem = items.value - 1;
    items.value = finalItem;

    // minus quantity
    let qty = document.getElementById("total-return-qty");
    let finalQty = qty.value - itemQty
    qty.value = finalQty;


    // minus gst
    let gst = document.getElementById("return-gst-val");
    let finalGst = gst.value - gstPerItem;
    gst.value = finalGst.toFixed(2);

    // minus netAmount
    let net = document.getElementById("refund");
    console.log("on delte check : ", net.value);
    if (net.value == null) {
        net.value = 0;
        let finalAmount = parseFloat(net.value) - parseFloat(refundPerItem);
        net.value = finalAmount.toFixed(2);
    } else {
        let finalAmount = parseFloat(net.value) - parseFloat(refundPerItem);
        net.value = finalAmount.toFixed(2);
    }


}