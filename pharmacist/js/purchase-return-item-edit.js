const rtnQtyInputField = document.getElementById('return-qty');
const rtnFreeQtyInputField = document.getElementById('ret-free-qty');

//===================================== ON SELECT EDIT DATA ============================RD==============

const customEdit = (id, value) => {

    var value;
    var row = document.getElementById(id);

    // console.log(value);
    // console.log(row);
    console.log(id);

    $.ajax({
        url: "ajax/stockReturnEdit.ajax.php",
        type: "POST",
        data: {
            EditId: value
        },
        success: function (data) {
            alert(data);

            var dataObject = JSON.parse(data);
            // alert("hello");
            // alert(dataObject.StokReturnDetailsItemId);
            slno = id.replace(/\D/g, '');


            let purchaseQty = dataObject.purchase_qty;
            let purchaseFreeQty = dataObject.free_qty;
            let returnQty = dataObject.return_qty;
            let returnFreeQty = dataObject.return_free_qty;
            let currentStockQty = dataObject.current_stock_qty;
            let currentFreeQty = parseInt(purchaseFreeQty) - parseInt(returnFreeQty);
            let currentByuQty = parseInt(currentStockQty) - parseInt(currentFreeQty);

            let totalReturnQty = parseInt(returnQty) + parseInt(returnFreeQty);

            //=============== GST AMOUNT PER ITEM CALCULATION ==================
            let perItemRefundAmount = parseFloat(dataObject.per_item_refund);
            let ptrPerItem = parseFloat(dataObject.ptr);
            let discount = parseFloat(dataObject.disParcent);
            let Qty = parseInt(dataObject.return_qty);
            let gstAmount = perItemRefundAmount - ((ptrPerItem - (ptrPerItem * discount / 100)) * Qty);

            //+++++++------  Adding data to is subsequent form body  -----++++++++++++++++

            document.getElementById("stock-returned-details-item-id").value = dataObject.StokReturnDetailsId;
            document.getElementById("stock-return-id").value = dataObject.stock_return_id;
            document.getElementById("stock-in-item-id").value = dataObject.stock_in_details_item_id;

            document.getElementById("dist-name").value = dataObject.distributor_name;
            document.getElementById("dist-id").value = dataObject.distributor_id;

            document.getElementById("product-id").value = dataObject.product_id;
            document.getElementById("product_name").value = dataObject.product_Name;
            document.getElementById("batch-number").value = dataObject.batch_no;

            document.getElementById("bill-date").value = dataObject.bill_date;
            document.getElementById("returnDate").value = dataObject.return_date;
            document.getElementById("exp-date").value = dataObject.exp_date;

            // document.getElementById("return-mode").value = refundMode;

            document.getElementById("unit").value = dataObject.unit;
            document.getElementById('current-qty').value = currentByuQty;
            document.getElementById("current-free-qty").value = currentFreeQty;

            document.getElementById("mrp").value = dataObject.mrp;
            document.getElementById("ptr").value = dataObject.ptr;
            document.getElementById("gst").value = dataObject.gst;
            document.getElementById("discount").value = dataObject.disParcent;

            document.getElementById("purchse-qty").value = dataObject.purchase_qty;
            document.getElementById("purchse-free-qty").value = dataObject.free_qty;

            // document.getElementById("prev-ret-qty").value = dataObject.return_qty;
            // document.getElementById("prev-ret-free-qty").value = dataObject.return_free_qty;

            document.getElementById("return-qty").value = dataObject.return_qty;
            document.getElementById("ret-free-qty").value = dataObject.return_free_qty;
            document.getElementById("refund-amount").value = dataObject.per_item_refund;

            document.getElementById("current-total-qty").value = dataObject.current_stock_qty;

            document.getElementById("gst-amount").value = gstAmount.toFixed(2);

            //++++++++++++++++++---  removing selected row  -----+++++++++++++++++++
            // row.parentNode.removeChild(row);

            delData(slno, gstAmount.toFixed(2), totalReturnQty, perItemRefundAmount.toFixed(2));
        }
    })
    return false;
}


//=================================================================================================

// ======= set refund mode ===========

// const setReturnMode = (t) =>{
//     value = t.value;
//     console.log(value);
//     document.getElementById("refund-mode").value = value;
// }

// const returnCheck = (freeQty) =>{
//     let freeReturnQty = parseInt(freeQty);

//     let purchaeQty = document.getElementById("purchse-qty").value;
//     let freeQtyOnPurchase = document.getElementById("purchse-free-qty").value;

//     let prevReturnQty = document.getElementById("prev-ret-qty").value; 
//     let prevReturnFreeQty = document.getElementById("prev-ret-free-qty").value; 

//     let currentFreeQty = document.getElementById("current-free-qty").value;
//     let currentPurchasedItemQty = document.getElementById("current-qty").value;
//     let currentQty = document.getElementById("current-total-qty").value;

//     let checkReturnFreeQty = parseInt(prevReturnFreeQty) - freeReturnQty;

//     if(freeReturnQty <= parseInt(freeQtyOnPurchase)){
//          if(checkReturnFreeQty <= (parseInt(currentQty) - parseInt(currentPurchasedItemQty))){
//             document.getElementById("return-free-qty").value = freeReturnQty;
//          }else{
//             swal("Error", "Return Quantity Must Less Then Avilable Quantity", "error");
//          }
//     }else{
//         swal("Error", "Return Quantity Must Less Then Avilable Quantity", "error");
//     }
// }


//////////////////////////////////////////////////////////////////////////////////

rtnQtyInputField.addEventListener('input', function () {
    const inputValue1 = rtnQtyInputField.value;
    const sanitizedValue1 = inputValue1.replace(/[.]/g, '');
    rtnQtyInputField.value = sanitizedValue1;
});

rtnFreeQtyInputField.addEventListener('input', function () {
    const inputValue2 = inputField2.value;
    const sanitizedValue2 = inputValue2.replace(/[.]/g, '');
    inputField2.value = sanitizedValue2;
});

//////////////////////////////////////////////////////////////////////////////////

const freeReturnCheck = () => {
    let editFreeRetunr = document.getElementById('ret-free-qty').value;
    console.log("edit free return qty : ", editFreeRetunr);
    let currentFreeQty = document.getElementById('current-free-qty').value;
    let purchaseFreeQty = document.getElementById('purchse-free-qty').value;
    let currenQty = document.getElementById('current-total-qty').value;
    if (parseInt(currenQty) < parseInt(purchaseFreeQty)) {
        swal("Error", "Return Quantity Must Less Then Avilable Quantity", "error");
        document.getElementById('ret-free-qty').value = currenQty;
    } else if (parseInt(editFreeRetunr) > parseInt(purchaseFreeQty)) {
        swal("Error", "Return Quantity Must Less Then Avilable Quantity", "error");
        document.getElementById('ret-free-qty').value = purchaseFreeQty;
    }
}


const getRefund = (returnQty) => {
    returnQty = parseInt(returnQty);

    if (isNaN(returnQty)) {
        document.getElementById("refund-amount").value = '';
        return;
    }

    if (returnQty != '') {
        // alert(returnQty);
        let ptr = document.getElementById("ptr").value;
        let disc = document.getElementById("discount").value;
        let gstParcent = document.getElementById("gst").value;

        let livePurchseQty = document.getElementById('current-qty').value;

        // console.log(parseInt(currentQty.value));
        if (returnQty <= parseInt(livePurchseQty)) {
            let taxable = (parseFloat(ptr) - (parseFloat(ptr) * parseFloat(disc) / 100)) * returnQty;
            let refund = taxable + (taxable * parseFloat(gstParcent) / 100);
            // console.log(refund);
            let gstAmount = parseFloat(refund) - parseFloat(taxable);

            document.getElementById("refund-amount").value = refund.toFixed(2);
            document.getElementById("gst-amount").value = gstAmount.toFixed(2);
        } else {
            document.getElementById("return-qty").value = '0';
            document.getElementById("refund-amount").value = '0';
            document.getElementById("gst-amount").value = '0';
            swal("Error", "Return Quantity Must Less Then Avilable Quantity", "error");
        }
    } else {
        document.getElementById("refund-amount").value = '0';
    }
}

// ##################################################################################

//geeting bills by clicking on add button

const addData = async () => {

    let paymentMode = document.getElementById("return-mode").value;

    let stockReturnId = document.getElementById("stock-return-id").value;
    let stockReturnDetailsItemId = document.getElementById("stock-returned-details-item-id").value;
    let stockInItemId = document.getElementById("stock-in-item-id").value;

    let productId = document.getElementById("product-id").value;
    let productName = document.getElementById('product_name').value;
    let batchNumber = document.getElementById("batch-number").value;

    let expDate = document.getElementById("exp-date").value;

    let unit = document.getElementById("unit").value;
    let mrp = document.getElementById("mrp").value;
    let ptr = document.getElementById("ptr").value;
    let gst = document.getElementById("gst").value;
    let discount = document.getElementById("discount").value;

    let returnQty = document.getElementById("return-qty").value;
    let returnFreeQty = document.getElementById("ret-free-qty").value;
    let GSTAmount = document.getElementById("gst-amount").value;
    let refundAmount = document.getElementById("refund-amount").value;


    if (paymentMode == "") {
        swal("Failed!", "Payment mode not selected! Please select payment mode.", "error");
        document.getElementById("return-mode").value = focus();
        return;
    }

    if (returnFreeQty == "") {
        swal("Worning!", "Return Free qantity can't be blank. Minimum value is 0.", "worning");
        document.getElementById("ret-free-qty").value = 0;
    }

    //////////////// sl contorl \\\\\\\\\\\\\\\\\\\\\\\

    let slno = document.getElementById("dynamic-id").value;
    let slControl = document.getElementById("serial-control").value;
    slno++;
    slControl++;
    document.getElementById("dynamic-id").value = slno;
    document.getElementById("serial-control").value = slControl;

    ///////////////////////////////////////////////////////////

    if (refundAmount != null) {
        const appendData = () => {

            jQuery("#dataBody")
                .append(`<tr id="table-row-${slControl}" id="row-${slControl}-col-1">
                    <td  style="color: red;">
                        <i class="fas fa-trash p-0 pt-2" onclick="delData(${slControl}, ${GSTAmount}, ${parseInt(returnQty) + parseInt(returnFreeQty)}, ${refundAmount})"></i>
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-2" style="font-size:0.75rem;">${slno}</td>

                    <td class="d-none p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="stock-return-id[]" value="${stockReturnId}" readonly >
                    </td>

                    <td class="d-none p-0 pt-3">
                        <input class="col table-data w-6r" type="text" name="stock-return-details-item-id[]" value="${stockReturnDetailsItemId}" readonly style="font-size: 0.75rem">
                    </td>

                    <td class="d-none p-0 pt-3">
                        <input class="col table-data w-6r" type="text" name="stock-in-details-item-id[]" value="${stockInItemId}" readonly style="font-size: 0.75rem">
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-6">
                        <input class="col table-data w-10r" type="text" name="productName[]" value="${productName}" readonly style="text-align: start; font-size:0.7rem;">
                        <input class="d-none col table-data w-9r" type="text" name="productId[]" value="${productId}" readonly style="text-align: start;" hidden>
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-7">
                        <input class="col table-data w-6r" type="text" name="batchNo[]" value="${batchNumber}" readonly style="font-size: 0.7rem">
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-8">
                        <input class="col table-data w-5r" type="text" name="expDate[]" value="${expDate}" readonly style="font-size: 0.7rem">
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-9">
                        <input class="col table-data w-4r" type="text" name="setof[]" value="${unit}" readonly style="font-size: 0.7rem">
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-10">
                        <input class="col table-data w-4r" type="text" name="mrp[]" value="${mrp}" readonly style="text-align: end; font-size: 0.7rem">
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-11">
                        <input class="col table-data w-4r" type="text" name="ptr[]" value="${ptr}" readonly style="text-align: end; font-size: 0.7rem">
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-13">
                        <input class="col table-data w-4r" type="text" name="disc[]" value="${discount}" readonly style="text-align: end; font-size: 0.7rem">
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-12">
                        <input class="col table-data w-4r" type="text" name="gst[]" value="${gst}" readonly style="text-align: end; font-size: 0.7rem">
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-14">
                        <input class="col table-data w-3r" type="text" name="return-qty[]" value="${returnQty}" readonly style="text-align: end; font-size: 0.7rem">
                    </td>

                    <td class="p-0 pt-3" id="row-${slControl}-col-15"> 
                        <input class="col table-data w-3r" type="text" name="return-free-qty[]" value="${returnFreeQty}" readonly style="text-align: end; font-size: 0.7rem">
                    </td>

                    <td class=" amnt-td p-0 pt-3" id="row-${slControl}-col-16">
                        <input class="col table-data w-5r" type="text" name="refund-amount[]" value="${refundAmount}" readonly style="text-align: end; font-size: 0.7rem">
                    </td>
                </tr>`);

            return true;
        }

        if (appendData() === true) {

            document.getElementById("refund-mode").value = paymentMode;


            if (slno != null) {
                let id = document.getElementById("items-qty");
                let newId = parseFloat(id.value) + 1;
                document.getElementById("items-qty").value = newId;

            } else {
                document.getElementById("items-qty").value = slno;
            }

            // var Qantity = document.getElementById("total-refund-qty");
            var totalQty = parseInt(returnQty) + parseInt(returnFreeQty);
            if (slno != null) {
                let Qty = parseInt(document.getElementById("total-return-qty").value);
                let newQty = Qty + totalQty;
                document.getElementById("total-return-qty").value = newQty;

            } else {
                document.getElementById("total-return-qty").value = totalQty;
            }

            let perItemRefund = document.getElementById("refund-amount").value;
            if (slno != null) {
                var netRefund = document.getElementById("NetRefund").value;
                netRefund = parseFloat(netRefund) + parseFloat(perItemRefund);
                document.getElementById("NetRefund").value = netRefund.toFixed(2);
            } else {
                document.getElementById("NetRefund").value = perItemRefund;
            }

            let perItemGstAmount = document.getElementById("gst-amount").value;
            if (slno != null) {
                var netGst = document.getElementById("return-gst").value;
                console.log(netGst);
                netGst = parseFloat(netGst) + parseFloat(perItemGstAmount);
                document.getElementById("return-gst").value = netGst.toFixed(2);
            } else {
                document.getElementById("return-gst").value = perItemGstAmount;
            }

            // distributor_name.value = '';

            document.getElementById("stock-return-id").value = '';
            document.getElementById("stock-returned-details-item-id").value = '';
            document.getElementById("stock-in-item-id").value = '';

            document.getElementById("product-id").value = '';
            document.getElementById('product_name').value = '';

            // document.getElementById("batch-number").value = '';
            // document.getElementById("exp-date").value = '';
            // document.getElementById("unit").value = '';
            // document.getElementById("mrp").value = '';
            // document.getElementById("ptr").value = '';
            // document.getElementById("gst").value = '';
            // document.getElementById("discount").value = '';
            // document.getElementById("return-qty").value = '';
            // document.getElementById("ret-free-qty").value = '';
            // document.getElementById("gst-amount").value = '';
            // document.getElementById("refund-amount").value = '';
            // document.getElementById("current-qty").value = '';
            // document.getElementById("current-free-qty").value = '';
            // document.getElementById("current-total-qty").value = '';
            // document.getElementById("purchse-qty").value = '';
            // document.getElementById("purchse-free-qty").value = '';
            // // document.getElementById("prev-ret-qty").value = '';
            // // document.getElementById("prev-ret-free-qty").value = '';
            // document.getElementById("bill-date").value = '';
            // document.getElementById("returnDate").value = '';


            document.getElementById("stock-return-edit").reset();
            event.preventDefault();
        }
    }
}


// ================================ Delet Data ================================
const delData = (slno, gstPerItem, ReturnQty, refund) => {

    let delRow = slno;

    // console.log(slno, gstPerItem, ReturnQty, refund)
    jQuery(`#table-row-${slno}`).remove();
    slno--;
    let slVal = document.getElementById("dynamic-id").value;
    document.getElementById("dynamic-id").value = parseInt(slVal) - 1;

    //minus item
    let items = document.getElementById("items-qty");
    let finalItem = items.value - 1;
    items.value = finalItem;

    // minus quantity
    let qty = document.getElementById("total-return-qty");
    let finalQty = qty.value - ReturnQty;
    qty.value = finalQty;


    // minus netAmount
    let gst = document.getElementById("return-gst");
    let finalGst = gst.value - gstPerItem;
    gst.value = finalGst.toFixed(2);

    // minus netAmount
    let net = document.getElementById("NetRefund");
    let finalAmount = net.value - refund;
    net.value = finalAmount.toFixed(2);

    rowAdjustment(delRow);
}

////////////////// ROW ADJUSTMENT ///////////////////

function rowAdjustment(delRow) {
    let tableId = document.getElementById("dataBody");
    let j = 0;
    let colIndex1 = 1;

    for (let i = 0; i < tableId.rows.length; i++) {
        j++;

        let row = tableId.rows[i];
        let cell1 = row.cells[colIndex1];
        cell1.innerHTML = j;
    }
}



////////////////////////////////// return qty field disable control /////////////////////////////////////

rtnQtyInputField.addEventListener('input', function () {
    if (!isClickFunctionCalled) {
        rtnQtyInputField.setAttribute('disabled', 'true');
    }
});