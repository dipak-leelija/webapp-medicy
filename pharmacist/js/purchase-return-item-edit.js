//===================================== ON SELECT EDIT DATA ============================RD==============

const customEdit = (id, value) => {

    var value;
    var row = document.getElementById(id);

    // console.log(value);
    // console.log(row);
    // console.log(id);

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
            
            // var stokReturnDetailId = dataObject.id;
            // alert(stokReturnDetailId);
            
            // console.log(stokReturnDetailId);

            // var stokReturnId = dataObject.stock_return_id;

            var distributor = dataObject.distributor_name;
            // alert(distributor);
            var distributorId = dataObject.distributor_id;
            var productId = dataObject.product_id;
            var productName = dataObject.product_Name;
            var refundMode = dataObject.refund_mode;
            var batchNumbe = dataObject.batch_no;
            var purchaseDate = dataObject.return_date;
            var expiry = dataObject.exp_date;
            var weatag = dataObject.weightage;
            var unit = dataObject.unit;
            var priceToRetaile = dataObject.ptr;
            var discountPercentage = dataObject.discount;
            var gstPercentage = dataObject.gst;
            var gstPerItem = dataObject.gstAmount;
            var taxableAmount = dataObject.taxable_amount;
            var maxRetailPrice = dataObject.mrp;
            var purchaseAmount = dataObject.purchase_amount;
            var purchasedqty = dataObject.purchase_qty;
            var freeQty = dataObject.free_qty;
            var curretnQty = dataObject.current_stock_qty;
            var returnQty = dataObject.return_qty;
            var returnFreeqty = dataObject.return_free_qty;
            // alert(returnFreeqty);
            var refundAmunt = dataObject.refund_amount;

            let ReturnQty = dataObject.return_qty;
            let refund = dataObject.refund_amount;

            var gstPerQtyCalculation = gstPerItem/returnQty;
            //+++++++------  Adding data to is subsequent form body  ---------++++++++++++++++
            
            document.getElementById("stock-returned-details-item-id").value = dataObject.StokReturnDetailsItemId;
            document.getElementById("stock-return-id").value = dataObject.stock_return_id;
            // document.getElementById("stock-returned-id").value = dataObject.stock_return_id;
            // document.getElementById("distributor_name").value = distributor;
            // document.getElementById("dist-name").value = distributor;
            document.getElementById("dist-id").value = distributorId;
            document.getElementById("product-id").value = productId;
            document.getElementById("product_name").value = productName;
            document.getElementById("return-mode").value = refundMode;
            document.getElementById("batch-number").value = batchNumbe;
            document.getElementById("returnDate").value = purchaseDate;
            document.getElementById("exp-date").value = expiry;
            document.getElementById("weatage").value = weatag;
            document.getElementById("unit").value = unit;
            document.getElementById("ptr").value = priceToRetaile;
            document.getElementById("discount").value = discountPercentage;
            document.getElementById("gst").value = gstPercentage;
            document.getElementById("gstAmount").value = gstPerItem;
            document.getElementById("taxable").value = taxableAmount;
            document.getElementById("mrp").value = maxRetailPrice;
            document.getElementById("amount").value = purchaseAmount;
            document.getElementById('purchased-qty').value = purchasedqty;
            document.getElementById("free-qty").value = freeQty;
            document.getElementById("current-qty").value = curretnQty;
            document.getElementById("return-qty").value = returnQty;
            document.getElementById("return-free-qty").value = returnFreeqty;
            document.getElementById("refund-amount").value = refundAmunt;
            document.getElementById("gstPerItem").value = gstPerQtyCalculation;
            //++++++++++++++++++---  removing selected row  -----+++++++++++++++++++
            // row.parentNode.removeChild(row);
            var totalReturnQty = parseInt(returnQty) + parseInt(returnFreeqty);

            delData (slno, gstPerItem, totalReturnQty, refund);
        }
        
    })
    return false;
}


//========================================================================================================//
//########################################################################################################//

// const getItemList = (t) => {

//     let id = t.name;
//     let distributirName = t.selectedOptions[0].text;
//     console.log(id);

//     var xmlhttp = new XMLHttpRequest();
//     let billIdUrl = `ajax/return-item-list.ajax.php?dist-id=${id}`;
//     xmlhttp.open("GET", billIdUrl, false);
//     xmlhttp.send(null);
//     document.getElementById("product-select").innerHTML = xmlhttp.responseText;
//     // alert(xmlhttp.responseText);

//     document.getElementById("dist-id").value = id;
//     document.getElementById("dist-name").value = distributirName;

//     document.getElementById("product-select").style.display = "block";
// }


// function searchItem(input) {
//     if (input != '') {
//         document.getElementById("product-select").style.display = "block";

//         // let input = document.getElementById('searchbar').value
//         input = input.toLowerCase();
//         let x = document.getElementsByClassName('item-list');

//         for (i = 0; i < x.length; i++) {
//             if (!x[i].innerHTML.toLowerCase().includes(input)) {
//                 x[i].style.display = "none";
//             } else {
//                 x[i].style.display = "flex";
//             }
//         }
//     } else {
//         document.getElementById("product-select").style.display = "none";

//     }
// }


//========================================================================================================//

const checkReturn = (returnFQty) => {
    let qty = document.getElementById("return-qty").value;
    let currentQty = document.getElementById("current-qty").value;

    if((parseInt(returnFQty) + parseInt(qty)) > currentQty){
        swal("Error", "SUM of Return Quantitys Must Less Then Avilable Quantity", "error")
        document.getElementById("return-qty").value = "";
        document.getElementById("return-free-qty").value = "";
    }
}


const getRefund = (returnQty) => {
    returnQty = parseInt(returnQty);

    let Fqty = document.getElementById("return-free-qty").value;
    let currentQty = document.getElementById("current-qty").value;

    if((parseInt(Fqty) + returnQty) > currentQty){
        swal("Error", "SUM of Return Quantitys Must Less Then Avilable Quantity", "error");
        // console.log("check return");
        document.getElementById("return-qty").value = "";
        // console.log("check return");
        document.getElementById("return-free-qty").value = "";
    }

    if (isNaN(returnQty)) {
        document.getElementById("refund-amount").value = '';
        return;
    }
    if (returnQty != '') {
        // alert(returnQty);
        let ptr = document.getElementById("ptr");
        let currentQty = document.getElementById("current-qty");
        let gst = document.getElementById("gst");
        // console.log(parseInt(currentQty.value));
        if (returnQty <= currentQty.value) {
            // console.log(returnQty);
            let subtotal = returnQty * ptr.value;
            let refund = subtotal + (gst.value / 100 * subtotal);

            document.getElementById("refund-amount").value = refund.toFixed(2);
        } else {
            document.getElementById("refund-amount").value = '';
            swal("Error", "Return Quantity Must Less Then Avilable Quantity", "error");
        }
    } else {
        // alert("NULL");
        document.getElementById("refund-amount").value = '0';
    }

    let perQtyGst = document.getElementById("gstPerItem").value;
    // console.log("perQtyGst=>");
    // console.log(perQtyGst);

    let updatedGstAmount = parseFloat(perQtyGst) * returnQty;
    // console.log("perQtyGst=>");
    // console.log(perQtyGst);

    document.getElementById("updatedGSTamount").value = updatedGstAmount;

}



// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button

const addData = async () => {

    let stockReturnId = document.getElementById("stock-return-id");
    let stockReturnDetailsItemId = document.getElementById("stock-returned-details-item-id");

    let productId = document.getElementById("product-id");
    let productName = document.getElementById('product_name');
    let batchNumber = document.getElementById("batch-number");
    let weatage = document.getElementById("weatage");
    let unit = document.getElementById("unit");
    let ptr = document.getElementById("ptr");
    let discount = document.getElementById("discount");
    let gst = document.getElementById("gst");
    let gstAmount = document.getElementById("updatedGSTamount").value;
    let purchasedQty = document.getElementById("purchased-qty");
    let freeQty = document.getElementById("free-qty");
    let currentQty = document.getElementById("current-qty");
    let returnQty = document.getElementById("return-qty")
    let expDate = document.getElementById("exp-date");
    let returnFreeQty = document.getElementById("return-free-qty");
    let refundAmount = document.getElementById("refund-amount");
    let returnMode = document.getElementById("return-mode").value;
    let taxable = document.getElementById("taxable");
    let mrp = document.getElementById("mrp");
    let amount = document.getElementById("amount");

    let billDate = document.getElementById("returnDate").value;

    document.getElementById("refund-mode").value = returnMode;

    let slno = document.getElementById("dynamic-id").value;

    slno++;

    document.getElementById("dynamic-id").value = slno;

    // return gst generating
    let withoutGst = (ptr.value * returnQty.value);
    // console.log(withoutGst);
    let taxAmount = (gst.value / 100 * withoutGst);
    // console.log(taxAmount);
    var returnGstAmount = document.getElementById("return-gst");
    returnGstAmount = parseFloat(returnGstAmount.value) + taxAmount;
    // console.log("tax amount : ",taxAmount);
    returnGstAmount = returnGstAmount.toFixed(2);
    // console.log("teturn gst amount : ",returnGstAmount);
    document.getElementById("return-gst").value = returnGstAmount;
    

    if(gstAmount!= ""){
        gstAmount = gstAmount;
    }
    if(gstAmount == ""){
        gstAmount = document.getElementById("gstAmount").value;
    }
    gstAmount = parseFloat(gstAmount);
    gstAmount = gstAmount.toFixed(2);
    // console.log("updated gst amount check=>");
    // console.log(gstAmount);


    let netAmountCalculation = document.getElementById("NetRefund");
    netAmountCalculation = parseFloat(netAmountCalculation.value) + parseFloat(refundAmount.value);
    // console.log("Updated refund amount=>");
    // console.log(netAmountCalculation);
    document.getElementById("NetRefund").value = netAmountCalculation;


    if (refundAmount.value != null) {
        const appendData = () => {

            jQuery("#dataBody")
                .append(`<tr id="table-row-${slno}">
                    <td  style="color: red;">
                        <i class="fas fa-trash pt-3" onclick="delData(${slno}, ${taxAmount}, ${parseInt(returnQty.value)+parseInt(returnFreeQty.value)}, ${refundAmount.value})"></i>
                    </td>

                    <td class="p-0 pt-3" style="padding:1.2rem; text-align: center; font-size:0.75rem;" scope="row">${slno}</td>

                    <td class="p-0 pt-3">
                        <input class="col table-data w-9r" type="text" name="productName[]" value="${productName.value}" readonly style="text-align: start; font-size:0.6rem;">
                        <input class="col table-data w-9r" type="text" name="productId[]" value="${productId.value}" readonly style="text-align: start;" hidden>
                    </td>

                    <td class="d-none p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="stock-return-id[]" value="${stockReturnId.value}" readonly >
                    </td>

                    <td class="d-none p-0 pt-3">
                        <input class="col table-data w-6r" type="text" name="stock-return-details-item-id[]" value="${stockReturnDetailsItemId.value}" readonly style="font-size: 0.75rem">
                    </td>

                    <td class="p-0 pt-3">
                        <input class="col table-data w-6r" type="text" name="batchNo[]" value="${batchNumber.value}" readonly style="font-size: 0.6rem">
                    </td>

                    <td class="p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="expDate[]" value="${expDate.value}" readonly style="font-size: 0.6rem">
                    </td>

                    <td class="p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="setof[]" value="${weatage.value}${unit.value}" readonly style="font-size: 0.6rem">
                    </td>

                    <td class="p-0 pt-3">
                        <input class="col table-data w-3r" type="text" name="purchasedQty[]" value="${purchasedQty.value}" readonly style="font-size: 0.65rem">
                    </td>

                    <td class="p-0 pt-3">
                        <input class="col table-data w-3r" type="text" name="freeQty[]" value="${freeQty.value}" readonly style="font-size: 0.6rem">
                    </td>

                    <td class="p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="mrp[]" value="${mrp.value}" readonly style="font-size: 0.6rem">
                    </td>

                    <td class="p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="ptr[]" value="${ptr.value}" readonly style="font-size: 0.6rem">
                    </td>

                    <td class="p-0 pt-3">
                        <input class="col table-data w-4r" type="text" name="purchase-amount[]" value="${amount.value}" readonly style="font-size: 0.6rem">
                    </td>

                    <td class="p-0 ps-1 pt-3">
                        <input class="col table-data w-4r" type="text" name="gstAmount[]" value="${gstAmount}" readonly style="font-size: 0.6rem"><br>
                        <input class="col table-data w-3r" type="text" name="gst[]" value="${gst.value}%" readonly style="border-radius: 40%; font-size: .6rem; background-color: #4e73df!important; text-align: center;"> 
                    </td>

                    <td class="p-0 pt-3">
                        <input class="col table-data w-3r" type="text" name="return-qty[]" value="${parseFloat(returnQty.value)}" readonly style="font-size: 0.6rem">
                    </td>

                    <td class="p-0 pt-3"> 
                        <input class="col table-data w-3r" type="text" name="return-free-qty[]" value="${parseFloat(returnFreeQty.value)}" readonly style="font-size: 0.6rem">
                    </td>

                    <td class=" amnt-td p-0 pt-3">
                        <input class="col table-data W-4r" type="text" name="refund-amount[]" value="${refundAmount.value}" readonly style="font-size: 0.6rem">
                    </td>
                </tr>`);

            return true;
        }

        if (appendData() === true) {

            // document.getElementById("demo").innerHTML = await myPromise;

            if (slno > 1) {
                let id = document.getElementById("items-qty");
                let newId = parseFloat(id.value) + 1;
                document.getElementById("items-qty").value = newId;

            } else {
                document.getElementById("items-qty").value = slno;
            }

            // var Qantity = document.getElementById("total-refund-qty");
            var totalQty = parseInt(returnQty.value) + parseInt(returnFreeQty.value);

            if (slno > 1) {
                let Qty = parseInt(document.getElementById("total-return-qty").value);
                let newQty = Qty + totalQty;
                document.getElementById("total-return-qty").value = newQty;

            } else {
                document.getElementById("total-return-qty").value = totalQty;
            }

            var netRefund = document.getElementById("NetRefund").value;
            // netRefund = netRefund + 

            // distributor_name.value = '';

            productId.value = '';
            product_name.value = '';

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
            stockReturnDetailsId = '';
        };

    }

}

// ================================ Delet Data ================================
const  delData = (slno, gstPerItem, ReturnQty, refund) => {
    alert(ReturnQty);
    jQuery(`#table-row-${slno}`).remove();
    slno--;
    document.getElementById("dynamic-id").value = slno;

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
}