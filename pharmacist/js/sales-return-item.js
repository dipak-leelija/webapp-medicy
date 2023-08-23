const xmlhttp = new XMLHttpRequest();

let listArea = document.getElementById("bills-list");

let patientName = document.getElementById("patient-name");
let billDate = document.getElementById("bill-date");
let reffBy = document.getElementById("reff-by");

let itemList = document.getElementById("items-list");

let currentItemID = document.getElementById("item-id");
let ProductID = document.getElementById("prod-id");
let expDate = document.getElementById("exp-date");
let unit = document.getElementById("unit");
let itemUnit = document.getElementById("item-unit");
let itemWeatage = document.getElementById("item-weatage");
let batch = document.getElementById("batch-no");

let mrp = document.getElementById("mrp");
let purchaseQuantity = document.getElementById("purchase-qty");
let qty = document.getElementById("qty");
let discount = document.getElementById("discount");
let gst = document.getElementById("gst")
let taxable = document.getElementById("taxable");
let billAmount = document.getElementById("bill-amount");


let invoiceNo = document.getElementById("invoice-no");
let refundMode = document.getElementById("refund-mode");

let returnQtyVal = document.getElementById("return");
let refundTaxable = document.getElementById("refund-taxable");
let refundAmount = document.getElementById("refund");


var todayDate = new Date();

var date = todayDate.getDate();
var month = todayDate.getMonth() + 1;
var year = todayDate.getFullYear();

if (date < 10) {
    date = '0' + date;
}
if (month < 10) {
    month = '0' + month;
}
var todayFullDate = year + "-" + month + "-" + date;
// console.log(todayFullDate);
document.getElementById("bill-date").setAttribute("max", todayFullDate);

const getCustomer = (invoice) => {

    if (invoice != "") {
        invoiceUrl = `ajax/return-item-list.ajax.php?invoice=${invoice}`;
        // alert(invoiceUrl);
        xmlhttp.open("GET", invoiceUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        listArea.style.display = 'block';
        listArea.innerHTML = xmlhttp.responseText;
    } else {
        listArea.style.display = 'none';

        patientName.value = "";
        billDate.value = "";
        reffBy.value = "";

        currentItemID.value = "";
        ProductID.value = "";
        expDate.value = "";
        unit.value = "";
        itemUnit.value = "";
        itemWeatage.value = "";
        batch.value = "";
        mrp.value = "";
        purchaseQuantity.value = "";
        qty.value = "";
        discount.value = "";
        gst.value = "";
        taxable.value = "";
        billAmount.value = "";
        refundAmount.value = "";
        itemList.innerHTML = '<option value="" selected disabled>Select Invoice Number First</option>';
    };
};

const getRefundMode = (ref) => {
    document.getElementById("refund-mode-val").value = ref;
}

const getReturnDate = (date) => {
    document.getElementById('return-date').value = date;
};


const getDtls = (invoiceId, customerId) => {

    if (invoiceId != "" && customerId != "") {

        //==================== Reff By ====================
        patientUrl = 'ajax/stockOut.all.ajax.php?patient=' + invoiceId;
        // alert(url);
        xmlhttp.open("GET", patientUrl, false);
        xmlhttp.send(null);
        patientName.value = xmlhttp.responseText;

        //==================== Bill Date ====================
        billDateUrl = 'ajax/stockOut.all.ajax.php?bill-date=' + invoiceId;
        // alert(url);
        xmlhttp.open("GET", billDateUrl, false);
        xmlhttp.send(null);
        billDate.value = xmlhttp.responseText;
        document.getElementById('purchased-date').value = xmlhttp.responseText;

        //==================== Reff By ====================
        reffUrl = 'ajax/stockOut.all.ajax.php?reff-by=' + invoiceId;
        // alert(url);
        xmlhttp.open("GET", reffUrl, false);
        xmlhttp.send(null);
        reffBy.value = xmlhttp.responseText;

        //==================== Products List ====================
        productsUrl = 'ajax/stockOut.all.ajax.php?products=' + invoiceId;
        xmlhttp.open("GET", productsUrl, false);
        xmlhttp.send(null);
        itemList.innerHTML = xmlhttp.responseText;

        document.getElementById('invoice-no').value = invoiceId;

        listArea.style.display = 'none';

    } else {

        patientName.value = "";
        billDate.value = "";
        reffBy.value = "";
        currentItemID.value = "";
        ProductID.value = "";
        expDate.value = "";
        unit.value = "";
        itemUnit.value = "";
        itemWeatage.value = "";
        batchNo.value = "";
        mrp.value = "";
        purchaseQuantity.value = "";
        qty.value = "";
        discount.value = "";
        gst.value = "";
        taxable.value = "";
        billAmount.value = "";
    }
}

const getItemDetails = (t) => {

    let invoice = t.selectedOptions[0].getAttribute('data-invoice');
    let itemId = t.value;
    console.log(itemId);
    let batchNo = t.selectedOptions[0].getAttribute('data-batch');

    if (itemId != "") {

        //==================== Product id ====================
        let productId = `ajax/stockOut.all.ajax.php?prod-id=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", productId, false);
        xmlhttp.send(null);
        ProductID.value = xmlhttp.responseText;

        //==================== Exp Date ====================
        let expUrl = `ajax/stockOut.all.ajax.php?exp-date=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", expUrl, false);
        xmlhttp.send(null);
        expDate.value = xmlhttp.responseText;

        //==================== Unit ====================
        let unitUrl = `ajax/stockOut.all.ajax.php?unit=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        unit.value = xmlhttp.responseText;

        let itemUnitUrl = `ajax/stockOut.all.ajax.php?itemUnit=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", itemUnitUrl, false);
        xmlhttp.send(null);
        itemUnit.value = xmlhttp.responseText;

        let itemWeatageUrl = `ajax/stockOut.all.ajax.php?itemWeatage=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", itemWeatageUrl, false);
        xmlhttp.send(null);
        itemWeatage.value = xmlhttp.responseText;

        //==================== Batch ====================
        batch.value = batchNo;

        // ================= ITEM ID ================
        currentItemID.value = t.value;
        //==================== Mrp ====================
        let mrpUrl = `ajax/stockOut.all.ajax.php?mrp=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        mrp.value = xmlhttp.responseText;

        //==================== PURCHASE QTY ====================
        let purchaseqtyUrl = `ajax/stockOut.all.ajax.php?p_qty=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", purchaseqtyUrl, false);
        xmlhttp.send(null);
        purchaseQuantity.value = xmlhttp.responseText;

        //==================== QTY ====================
        let qtyUrl = `ajax/stockOut.all.ajax.php?qty=${invoice}&p-id=${itemId}&batch=${batchNo}`;
        xmlhttp.open("GET", qtyUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText)
        qty.value = xmlhttp.responseText;

        //==================== DISC ====================
        let discUrl = `ajax/stockOut.all.ajax.php?disc=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", discUrl, false);
        xmlhttp.send(null);
        discount.value = xmlhttp.responseText;

        //==================== GST ====================
        let gstUrl = `ajax/stockOut.all.ajax.php?gst=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        gst.value = xmlhttp.responseText;

        //==================== Taxable ====================
        let taxableUrl = `ajax/stockOut.all.ajax.php?taxable=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", taxableUrl, false);
        xmlhttp.send(null);
        taxable.value = xmlhttp.responseText;

        //==================== AMOUNT ====================
        let amountUrl = `ajax/stockOut.all.ajax.php?amount=${invoice}&p-id=${itemId}`;
        xmlhttp.open("GET", amountUrl, false);
        xmlhttp.send(null);
        billAmount.value = xmlhttp.responseText;

        listArea.style.display = 'none';

        returnQtyVal.value = "";
        refundAmount.value = "";

        document.getElementById('return').focus();

    } else {
        currentItemID.value = "";
        ProductID.value = "";
        expDate.value = "";
        unit.value = "";
        itemUnit.value = "";
        itemWeatage.value = "";
        batchNo.value = "";
        mrp.value = "";
        purchaseQuantity.value = "";
        qty.value = "";
        discount.value = "";
        gst.value = "";
        taxable.value = "";
        refundAmount.value = "";
        billAmount.value = "";
    }
}


const getRefund = (returnQty) => {
    console.log("return qantity test", returnQty);
    
    let currenQty = document.getElementById('qty').value;
    let mrp = document.getElementById('mrp').value;
    let disc = document.getElementById('discount').value;
    let gst = document.getElementById('gst').value;
    let weatage = document.getElementById('item-weatage').value;
    let itemUnit = document.getElementById('item-unit').value;
    
    if (parseInt(returnQty) <= parseInt(currenQty)) {
        if(itemUnit == 'tab' || itemUnit == 'cap'){
            let refundAmount = ((parseFloat(mrp) / parseInt(weatage)) - ((parseFloat(mrp) / parseInt(weatage)) * parseFloat(disc)/100)) * parseInt(returnQty);

            refundTaxable = (parseFloat(refundAmount) * 100) / (parseFloat(gst) + 100);

            document.getElementById('refund').value = refundAmount.toFixed(2);
            document.getElementById('refund-taxable').value = refundTaxable.toFixed(2);
        }else{
            let refundAmount = (parseFloat(mrp) - (parseFloat(mrp) * parseFloat(disc)/100)) * returnQty;

            refundTaxable = (parseFloat(refundAmount) * 100) / (parseFloat(gst) + 100);

            document.getElementById('refund').value = refundAmount.toFixed(2);
            document.getElementById('refund-taxable').value = refundTaxable.toFixed(2);
        }
    } 

    if (parseInt(returnQty) > parseInt(currenQty)) {
        document.getElementById("refund").value = '';
        document.getElementById("add-btn").disabled = true;
        swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Inserted value might be grater than sold qty.',
        })
    }
}


// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
const addData = () => {

    let currentItemID = document.getElementById("item-id").value;
    let pId = document.getElementById("prod-id").value;
    let expDate = document.getElementById("exp-date").value;
    let unit = document.getElementById("unit").value;
    let batch = document.getElementById("batch-no").value;
    let mrp = document.getElementById("mrp").value;
    let purchaseQuantity = document.getElementById("purchase-qty").value;
    let qty = document.getElementById("qty").value;
    let discount = document.getElementById("discount").value;
    let gst = document.getElementById("gst").value;
    let taxable = document.getElementById("taxable").value;
    let billAmount = document.getElementById("bill-amount").value;

    //============================ set and filter invoice number ==================================
    let invoiceNo = document.getElementById("invoice-no").value;
    let returnInvoiceId = document.getElementById('invoice').value;
    if (returnInvoiceId != "") {
        if (returnInvoiceId != invoiceNo) {
            window.alert("INVOICE NUMBER CHANGED");
            window.location.reload();
        }
    } else {
        document.getElementById('invoice').value = invoiceNo;
    }

    //=============================================================================================

    let refundMode = document.getElementById("refund-mode").value;

    let returnQtyVal = document.getElementById("return").value;
    let refundTaxable = document.getElementById("refund-taxable").value;
    refundTaxable = parseFloat(refundTaxable);
    let refundAmount = document.getElementById("refund").value;
    refundAmount = parseFloat(refundAmount);



    if (invoiceNo.value == "") {
        swal("Failed!", "Please Select invoice no!", "error");
        invoiceNo.focus();
        return;
    }

    if (patientName.value == "") {
        swal("Failed!", "Patient name must be not noull", "error");
        patientName.focus();
        return;
    }


    if (billDate.value == "") {
        swal("Failed!", "Please enter Date!", "error");
        billDate.focus();
        return;
    }

    if (reffBy.value == "") {
        swal("Failed!", "Doctor name must be not null", "error");
        reffBy.focus();
        return;
    }


    if (refundMode.value == "") {
        swal("Failed!", "Please Select refund mode!", "error");
        refundMode.focus();
        return;
    }

    if (itemList.value == "") {
        swal("Failed!", "Please Select returning item!", "error");
        itemList.focus();
    } else { }

    if (currentItemID.value == "") {
        swal("Failed!", "Please select an item", "error");
        expDate.focus();
        return;
    }

    if (expDate.value == "") {
        swal("Failed!", "Expiary date must be not null!", "error");
        expDate.focus();
        return;
    }

    if (unit.value == "") {
        swal("Failed!", "Unit value must be not null!", "error");
        unit.focus();
        return;
    }

    if (batch.value == "") {
        swal("Failed!", "Batch number must be not null", "error");
        batch.focus();
        return;
    }

    if (mrp.value == "") {
        swal("Failed!", "MRP must be not null!", "error");
        mrp.focus();
        return;
    }

    if (qty.value == "") {
        swal("Failed!", "Qantity must be not null", "error");
        qty.focus();
        return;
    }

    if (discount.value == "") {
        swal("Failed!", "Discount must be not null", "error");
        discount.focus();
        return;
    }

    if (gst.value == "") {
        swal("Failed!", "GST must be not null!", "error");
        gst.focus();
        return;
    }

    if (taxable.value == "") {
        swal("Failed!", "taxable must be not null!", "error");
        taxable.focus();
        return;
    }

    if (billAmount.value == "") {
        swal("Failed!", "bill amount must be not null!", "error");
        billAmount.focus();
        return;
    }

    if (returnQtyVal.value == "") {
        swal("Failed!", "return qantity must be not null!", "error");
        returnQtyVal.focus();
        return;
    }

    if (refundTaxable.value == "") {
        swal("Failed!", "refund amount must be not null!", "error");
        refund.focus();
        return;
    }

    if (refundAmount.value == "") {
        swal("Failed!", "refund amount must be not null!", "error");
        refund.focus();
        return;
    }

    let existsItems = document.querySelectorAll('tr');
    for (let i = 0; i < existsItems.length; i++) {
        if (i > 0) {

            const item = existsItems[i];
            if (item.childNodes[5].childNodes[3].value == itemList.value) {
                swal("You can not add same item more than one!");
                ProductID.value = "";
                expDate.value = "";
                unit.value = "";
                itemUnit.value = "";
                itemWeatage.value = "";
                batch.value = "";
                mrp.value = "";
                qty.value = "";
                discount.value = "";
                gst.value = "";
                taxable.value = "";
                billAmount.value = "";
                refundTaxable.value = "";
                refundAmount.value = "";
                return;
            }
        }

    }

    let itemName = itemList.selectedOptions[0].text;

    let items = document.querySelectorAll('tr');
    let slno = items.length;
    document.getElementById("total-items").value = slno;

    //total Refund Amount
    var totalRefund = document.getElementById("refund-amount");
    let netRefund = parseFloat(totalRefund.value) + parseFloat(refundAmount);
    // console.log(netRefund);
    totalRefund.value = netRefund.toFixed(2);

    //total item qty
    var totalQty = document.getElementById("total-qty");
    let totalQtyTemp = parseFloat(totalQty.value) + parseFloat(returnQtyVal);
    totalQty.value = totalQtyTemp;

    // generate gst amount on refund
    var netGstAmount = document.getElementById("gst-amount");
    var totalGstAmount = parseFloat(refundAmount) - parseFloat(refundTaxable);
    netGstAmount.value = totalGstAmount.toFixed(2);
    let gstPerItem = totalGstAmount.toFixed(2);

    const appendData = () => {

        jQuery("#dataBody")
            .append(`<tr id="table-row-${slno}">
            <td class='text-danger pt-3'>
                <i class="fas fa-trash" id="${slno}"
                    onclick="deleteData(this.id, ${parseFloat(returnQtyVal)}, ${gstPerItem}, ${refundAmount.toFixed(2)})"></i>
            </td>
            <td class="pt-3" style="font-size: 0.7rem;">${slno}</td>
            <td class="pt-3">
                <input class="table-data w-10r" type="text" value="${itemName}" readonly style="font-size: .65rem;">
                <input class="d-none" type="text" name="itemId[]" value="${itemList.value}">

            </td>
            <td class="d-none pt-3">
                <input class="table-data w-6r" type="text" name="productId[]" value="${pId}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-6r" type="text" name="batchNo[]" value="${batch}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="expDate[]" value="${expDate}" readonly style="font-size: 0.65rem;">
            </td>

            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="setof[]" value="${unit}" readonly style="font-size: 0.65rem;">
            </td>

            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="qty[]" value="${qty}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="mrp[]" value="${mrp}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-2r" type="text" name="disc[]" value="${discount}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-2r" type="text" name="gst[]" value="${gst}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-2r" type="text" name="taxable[]" value="${refundTaxable.toFixed(2)}"  style="font-size: 0.65rem;">
            </td>
            <td class="ps-1 pt-3">
                <input class="table-data w-3r" type="text" name="return[]" value="${returnQtyVal}" readonly style="font-size: 0.65rem;">
            </td>
            <td class="pt-3">
            <input class="table-data w-3r" type="any" name="refundPerItem[]" value="${refundAmount.toFixed(2)}" readonly style="font-size: 0.65rem;">
            </td>
        </tr>`);
        return true;
    };

    if (appendData() == true) {
        itemList.remove(itemList.selectedIndex);
        itemList.options[0].selected = true;



    }
    document.getElementById("return-item-details").reset();
} //eof addData  



// ================================ Delet Data ================================


function deleteData(slno, returnQty, gstPerItem, itemRefund) {
    jQuery(`#table-row-${slno}`).remove();

    let existitems = document.querySelectorAll('tr');
    for (let i = 1; i < existitems.length; i++) {
        existitems[i].id = `table-row-${i}`;
        existitems[i].childNodes[1].childNodes[1].id = i;
        existitems[i].childNodes[3].innerText = i;
    }

    //minus item
    let items = document.getElementById("total-items");
    let finalItem = items.value - 1;
    items.value = finalItem;

    // minus quantity
    let qty = document.getElementById("total-qty");
    let finalQty = qty.value - returnQty
    qty.value = finalQty;


    // minus netAmount
    let gst = document.getElementById("gst-amount");
    let finalGst = parseFloat(gst.value) - parseFloat(gstPerItem);
    gst.value = finalGst.toFixed(2);


    // minus netAmount
    let refundAmount = document.getElementById("refund-amount");
    let finalAmount = refundAmount.value - itemRefund;
    refundAmount.value = finalAmount.toFixed(2);

}