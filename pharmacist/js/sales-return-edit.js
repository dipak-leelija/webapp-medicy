
const xmlhttp = new XMLHttpRequest();
const listArea = document.getElementById("bills-list");

invoiceID = document.getElementById("invoiceID").value;
salesReturnId = document.getElementById("sales-return-id").value;

patientName = document.getElementById("patient-name");
billDate = document.getElementById("bill-date");
reffBy = document.getElementById("reff-by");

itemList = document.getElementById("items-list");
returnitemid = document.getElementById('returned-item-id');
expDate = document.getElementById("exp-date");
unit = document.getElementById("unit");
unitType = document.getElementById("unit-type");
itemWeatage = document.getElementById("item-weatage");

prodId = document.getElementById('product-id');
batch = document.getElementById("batch-no");
mrp = document.getElementById("mrp");
pqty = document.getElementById("P-qty");

rtnqty = document.getElementById("return-qty");
discount = document.getElementById("discount");

gst = document.getElementById("gst")
taxable = document.getElementById("taxable");
billAmount = document.getElementById("refund");

var invoiceNo = document.getElementById("invoice-no");
var refundMode = document.getElementById("refund-mode");

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
document.getElementById("bill-date").setAttribute("max", todayFullDate);

const getReturnDate = (date) => {
    document.getElementById('return-date').value = date;
};


if(invoiceID != null){
    document.getElementById('invoice').value = `#${invoiceID}`;
    productsUrl = `ajax/salesReturnEdit.ajax.php?products=${invoiceID}&salesreturnID=${salesReturnId}`;
    xmlhttp.open("GET", productsUrl, false);
    xmlhttp.send(null);
    // console.log(xmlhttp.responseText);
    itemList.innerHTML = xmlhttp.responseText;
}

const getRefundMode = (ref) => {
    document.getElementById("refund-mode-val").value = ref;
}

const getEditItemDetails = (t) => {

    let fieldId = t.id;
    let productId = t.value;

    let invoice = t.selectedOptions[0].getAttribute('data-invoice');
    let batchNo = t.selectedOptions[0].getAttribute('data-batch');
    let salesReturnId = t.selectedOptions[0].getAttribute('sales-return-id');
    let currentStockItemId = t.selectedOptions[0].getAttribute('current-stock-item-id');
    let returndItemId = t.selectedOptions[0].getAttribute('returned-item-id');
    
    if (t != "") {
        // ====== sales return details id ======
        document.getElementById('sales-return-id').value = salesReturnId;
        document.getElementById('returned-item-id').value = returndItemId;
        //==================== Exp Date Date ====================
        let expUrl = `ajax/salesReturnEdit.ajax.php?exp-date=${returndItemId}`;
        xmlhttp.open("GET", expUrl, false);
        xmlhttp.send(null);
        expDate.value = xmlhttp.responseText;

        //==================== Unit ====================
        let unitUrl = `ajax/salesReturnEdit.ajax.php?unit=${returndItemId}`;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        unit.value = xmlhttp.responseText;

        let unitTypeUrl = `ajax/salesReturnEdit.ajax.php?unitType=${returndItemId}`;
        xmlhttp.open("GET", unitTypeUrl, false);
        xmlhttp.send(null);
        unitType.value = xmlhttp.responseText;

        let itemWeatageUrl = `ajax/salesReturnEdit.ajax.php?itemWeatage=${returndItemId}`;
        xmlhttp.open("GET", itemWeatageUrl, false);
        xmlhttp.send(null);
        itemWeatage.value = xmlhttp.responseText;

        // ================== product id ===============
        prodId.value = productId;
        
        //==================== Batch ====================
        let batchUrl = `ajax/salesReturnEdit.ajax.php?batchNo=${returndItemId}`;
        xmlhttp.open("GET", batchUrl, false);
        xmlhttp.send(null);
        batch.value = xmlhttp.responseText;

        //==================== Mrp ====================
        let mrpUrl = `ajax/salesReturnEdit.ajax.php?mrp=${invoice}&p-id=${currentStockItemId}`;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        mrp.value = xmlhttp.responseText;

        //==================== Purchase QTY ====================
        let purchaseqtyUrl = `ajax/salesReturnEdit.ajax.php?pqty=${invoice}&p-id=${currentStockItemId}`;
        xmlhttp.open("GET", purchaseqtyUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText)
        pqty.value = xmlhttp.responseText;

        //==================== Return QTY ====================
        let rtnQtyUrl = `ajax/salesReturnEdit.ajax.php?rtnqty=${returndItemId}`;
        xmlhttp.open("GET", rtnQtyUrl, false);
        xmlhttp.send(null);
        let returnQantity = xmlhttp.responseText;
        returnQantity = returnQantity.replace(/\D/g, '');
     
        document.getElementById('return-qty').value = returnQantity;

        //==================== DISC ====================
        let discUrl = `ajax/salesReturnEdit.ajax.php?disc=${returndItemId}`;
        xmlhttp.open("GET", discUrl, false);
        xmlhttp.send(null);
        discount.value = xmlhttp.responseText;

        //==================== GST ====================
        let gstUrl = `ajax/salesReturnEdit.ajax.php?gst=${returndItemId}`;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        gst.value = xmlhttp.responseText;

        //==================== taxable ====================
        let taxableUrl = `ajax/salesReturnEdit.ajax.php?taxable=${returndItemId}`;
        xmlhttp.open("GET", taxableUrl, false);
        xmlhttp.send(null);
        taxable.value = xmlhttp.responseText;

        //==================== refund amount ====================
        let amountUrl = `ajax/salesReturnEdit.ajax.php?amount=${returndItemId}`;
        xmlhttp.open("GET", amountUrl, false);
        xmlhttp.send(null);
        billAmount.value = xmlhttp.responseText;

        listArea.style.display = 'none';

        document.getElementById('return-qty').focus();

    } else {

        returnitemid.value = "";
        expDate.value = "";
        unit.value = "";
        unitType.value = "";
        weatage.value = "";
        prodId.value = "";
        batchNo.value = "";
        mrp.value = "";
        pqty.value = "";
     
        rtnqty.value = "";
        discount.value = "";
        gst.value = "";
        taxable.value = "";
        billAmount.value = "";

    }
}

const getRefund = (returnQty) => {

    let mrp = document.getElementById('mrp').value;
    let disc = document.getElementById('discount').value;
    let gst = document.getElementById('gst').value;
    let unitType = document.getElementById('unit-type').value;
    let itemWeatage = document.getElementById('item-weatage').value;
    let reviceTaxable = '';
    let reviceRefund = '';
    let reviceDiscAmt = '';

    if (returnQty != '') {

        let returnQty1 = parseFloat(pqty.value) -  parseFloat(returnQty) 

        if (parseFloat(returnQty1) < 0) {
            // alert("Return Quantity must be lesser than current quantity!");
            swal("Error", "Return edit Quantity must be lesser than Purchase quantity!", "error");
            document.getElementById('return-qty').value = '';
        }
        else if (parseFloat(returnQty1) > parseFloat(pqty.value)) {
            // alert("Return Quantity must be lesser than current quantity!");
            swal("Error", "Enter valid quantity", "error");
            document.getElementById('return-qty').value = '';
        }
        else if (parseFloat(returnQty1) > 0 ) {
            if(unitType == 'tab' || unitType == 'cap'){
                reviceDiscAmt = (parseFloat(mrp) - (parseFloat(mrp)*parseFloat(disc)/100)) / parseInt(itemWeatage);
                reviceRefund = parseFloat(reviceDiscAmt) * parseInt(returnQty);
                reviceTaxable = (parseFloat(reviceRefund) * 100) / (parseFloat(gst) + 100);

                reviceRefund = parseFloat(reviceRefund).toFixed(2);
                reviceTaxable = parseFloat(reviceTaxable).toFixed(2);

                document.getElementById('taxable').value = reviceTaxable;
                document.getElementById('refund').value = reviceRefund;
                 
                document.getElementById("add-btn").disabled = false; 
            }else{
                reviceDiscAmt = (parseFloat(mrp) - (parseFloat(mrp)*parseFloat(disc)/100));
                reviceRefund = parseFloat(reviceDiscAmt) * parseInt(returnQty);
                reviceTaxable = (parseFloat(reviceRefund) * 100) / (parseFloat(gst) + 100);

                reviceRefund = parseFloat(reviceRefund).toFixed(2);
                reviceTaxable = parseFloat(reviceTaxable).toFixed(2);

                document.getElementById('taxable').value = reviceTaxable;
                document.getElementById('refund').value = reviceRefund;
                
                document.getElementById("add-btn").disabled = false;
            }
                
        } else if (parseFloat(returnQty1) == 0 ) {
            
            if(unitType == 'tab' || unitType == 'cap'){
                reviceDiscAmt = (parseFloat(mrp) - (parseFloat(mrp)*parseFloat(disc)/100)) / parseInt(itemWeatage);
                reviceRefund = parseFloat(reviceDiscAmt) * parseInt(returnQty);
                reviceTaxable = (parseFloat(reviceRefund) * 100) / (parseFloat(gst) + 100);

                reviceRefund = parseFloat(reviceRefund).toFixed(2);
                reviceTaxable = parseFloat(reviceTaxable).toFixed(2);

                document.getElementById('taxable').value = reviceTaxable;
                document.getElementById('refund').value = reviceRefund;
                
                document.getElementById("add-btn").disabled = false;  
            }else{
                reviceDiscAmt = (parseFloat(mrp) - (parseFloat(mrp)*parseFloat(disc)/100));
                reviceRefund = parseFloat(reviceDiscAmt) * parseInt(returnQty);
                reviceTaxable = (parseFloat(reviceRefund) * 100) / (parseFloat(gst) + 100);

                reviceRefund = parseFloat(reviceRefund).toFixed(2);
                reviceTaxable = parseFloat(reviceTaxable).toFixed(2);

                document.getElementById('taxable').value = reviceTaxable;
                document.getElementById('refund').value = reviceRefund;
                
                document.getElementById("add-btn").disabled = false;
            }
            
        } else {
            document.getElementById("refund").value = '';
            document.getElementById("add-btn").disabled = true;
            // swal("Inserted value might be grater than sold qty.");
        }
    } else {
        // swal("Return Quantity can not be blank.");
        document.getElementById("refund").value = '';
        document.getElementById("add-btn").disabled = true;
    }
// console.log("taxable check : ",reviceTaxable)
    //================checking return quantity is not exceded than purchase quantity==================

}



// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
const addData = () => {

    // console.log(returnitemid);

    document.getElementById("salesreturn-id").value = salesReturnId;
    //alert(invoiceNo.value);
    
    // if (invoiceNo.value == "") {
    //     invoiceNo.focus();
    //     return;
    // }

    if (patientName.value == "") {
        patientName.focus();
        return;
    }


    if (billDate.value == "") {
        billDate.focus();
        return;
    }

    if (reffBy.value == "") {
        reffBy.focus();
        return;
    }

    if (refundMode.value == "") {
        refundMode.focus();
        return;
    }

    if (itemList.value == "") {
        itemList.focus();
        return;
    } 

    if(returnitemid.value == ""){
        returnitemid.focus();
        return;
    }

    if (expDate.value == "") {
        expDate.focus();
        return;
    }

    if (unit.value == "") {
        unit.focus();
        return;
    }

    if(prodId.value == ""){
        prodId.focus();
        return;
    }

    if (batch.value == "") {
        batch.focus();
        return;
    }

    if (mrp.value == "") {
        mrp.focus();
        return;
    }

    if (pqty.value == "") {
        pqty.focus();
        return;
    }

    if (rtnqty.value == "") {
        rtnqty.focus();
        return;
    }

    if (discount.value == "") {
        discount.focus();
        return;
    }

    if (gst.value == "") {
        gst.focus();
        return;
    }

    if (taxable.value == "") {
        taxable.focus();
        return;
    }

    if (billAmount.value == "") {
        billAmount.focus();
        return;
    }

    let existsItems = document.querySelectorAll('tr');
    for (let i = 0; i < existsItems.length; i++) {
        if (i > 0) {

            const item = existsItems[i];
            if (item.childNodes[5].childNodes[3].value == itemList.value) {
                swal("You can not add same item more than one!");

                returnitemid.value = "";
                expDate.value = "";
                unit.value = "";
                unitType.value = "";
                itemWeatage.value = "";
                prodId.value = "";
                batch.value = "";
                mrp.value = "";
                pqty.value = "";
                rtnqty.value = "";
                discount.value = "";
                gst.value = "";
                taxable.value = "";
                billAmount.value = "";

                return;
            }
        }

    }


    let itemName = itemList.selectedOptions[0].text;

    let items = document.querySelectorAll('tr');
    let slno = items.length;
    document.getElementById("total-items").value = slno;

    //get total Refund Amount
    var refundAmount = document.getElementById("refund-amount");
    let totalRefund = parseFloat(refundAmount.value) + parseFloat(refund.value);
    refundAmount.value = totalRefund.toFixed(2);

    //get total item qty
    var totalQty = document.getElementById("total-qty");
    let totalQtyOnEdit = parseInt(totalQty.value) + parseFloat(rtnqty.value);
    totalQty.value = totalQtyOnEdit;


    // generate gstamount per item and store 
    let gstPerItem = parseFloat(billAmount.value) - parseFloat(taxable.value);
    let gstAmount = document.getElementById("gst-amount");
    let totalGstAmount = parseFloat(gstAmount.value) + parseFloat(gstPerItem);
    let taxableAmnt = gstPerItem.toFixed(2);
    gstAmount.value = totalGstAmount.toFixed(2);


    const appendData = () => {

        jQuery("#dataBody")
            .append(`<tr id="table-row-${slno}">
            <td class='text-danger pt-3'>
                <i class="fas fa-trash" id="${slno}"
                    onclick="deleteData(this.id, ${parseFloat(rtnqty.value)}, ${gstPerItem}, ${parseFloat(refund.value)})"></i>
            </td>
            <td class="pt-3" style="width: 0.5rem">${slno}</td>
            <td class="pt-3">
                <input class="table-data w-10r" type="text" value="${itemName}" readonly style="font-size: 0.7rem;">
                <input class="d-none" type="text" name="productId[]" value="${prodId.value}">

            </td>

            <td class="d-none pt-3">
                <input class="table-data w-5r" type="text" name="return-Item-Id[]" value="${returnitemid.value}" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3">
                <input class="table-data w-5r" type="text" name="batchNo[]" value="${batch.value}" readonly style="font-size: 0.7rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="expDate[]" value="${expDate.value}" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="setof[]" value="${unit.value}" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="p-qty[]" value="${pqty.value}" readonly style="font-size: 0.7rem;">
            </td>
            
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="mrp[]" value="${mrp.value}" readonly style="font-size: 0.7rem;">
            </td>

            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="disc[]" value="${discount.value}" readonly style="font-size: 0.7rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="gst[]" value="${gst.value}" readonly style="font-size: 0.7rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="taxable[]" value="${taxable.value}"  style="font-size: 0.7rem;">
            </td>
            <td class="ps-1 pt-3">
                <input class="table-data w-3r" type="text" name="return[]" value="${rtnqty.value}" readonly style="font-size: 0.7rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-4r" type="any" name="refund[]" value="${billAmount.value}" readonly style="font-size: 0.7rem;">
            </td>
        </tr>`);
        return true;
    };

    if (appendData() == true) {
        itemList.remove(itemList.selectedIndex)
        itemList.options[0].selected = true;
        returnitemid.value = "";
        expDate.value = "";
        unit.value = "";
        unitType.value = "";
        itemWeatage.value = "";
        prodId.value = "";
        batch.value = "";
        mrp.value = "";
        pqty.value = "";
        rtnqty.value = "";
        discount.value = "";
        gst.value = "";
        taxable.value = "";
        billAmount.value = "";
        refund.value = "";
    }

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