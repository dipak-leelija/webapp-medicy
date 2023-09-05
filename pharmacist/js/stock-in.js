var firstInput = document.getElementById('product-name');

window.addEventListener('load', function () {
    firstInput.focus();
});

function searchItem(input) {

    let checkLength = input.length;

    let xmlhttp = new XMLHttpRequest();

    let searchReult = document.getElementById('product-select');

    xmlhttp.open('GET', 'ajax/purchase-item-list.ajax.php?data=' + input, true);
    xmlhttp.send();

    if (input == "") {
        document.getElementById("product-select").style.display = "none";
    }

    if (checkLength > 2) {
        if (input != "") {
            document.getElementById("product-select").style.display = "block";
        }
    }

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            searchReult.innerHTML = xmlhttp.responseText;
        }
    };


}

const getDtls = (productId) => {

    // console.log(productId);
    // alert(productId);
    let xmlhttp = new XMLHttpRequest();

    if (productId != "") {
        // console.log(productId);
        //==================== Manufacturere List ====================
        manufacturerurl = 'ajax/product.getManufacturer.ajax.php?id=' + productId;
        // alert(url);
        xmlhttp.open("GET", manufacturerurl, false);
        xmlhttp.send(null);
        document.getElementById("manufacturer-id").value = xmlhttp.responseText;

        manufacturerName = 'ajax/product.getManufacturer.ajax.php?name=' + productId;
        xmlhttp.open("GET", manufacturerName, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("manufacturer-name").value = xmlhttp.responseText;

        //==================== Medicine Power ====================
        powerurl = 'ajax/product.getMedicineDetails.ajax.php?power=' + productId;
        // alert(url);
        xmlhttp.open("GET", powerurl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("medicine-power").value = xmlhttp.responseText;

        //==================== Packaging Type ====================
        packTypeUrl = 'ajax/product.getMedicineDetails.ajax.php?pType=' + productId;
        // alert(url);
        xmlhttp.open("GET", packTypeUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("packaging-type").innerHTML = xmlhttp.responseText;

        packTypeFieldUrl = 'ajax/product.getMedicineDetails.ajax.php?packegeIn=' + productId;
        // // alert(url);
        xmlhttp.open("GET", packTypeFieldUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("packaging-in").value = xmlhttp.responseText;

        //==================== Weightage ====================
        weightage = 'ajax/product.getMedicineDetails.ajax.php?weightage=' + productId;
        // alert(url);
        xmlhttp.open("GET", weightage, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("weightage").value = xmlhttp.responseText;


        //==================== Unit ====================
        unitUrl = 'ajax/product.getMedicineDetails.ajax.php?unit=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        document.getElementById("unit").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== MRP ====================
        mrpUrl = 'ajax/product.getMrp.ajax.php?id=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== ptr check url ===================
        chkPtr = 'ajax/product.getMrp.ajax.php?ptrChk=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", chkPtr, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("chk-ptr").value = xmlhttp.responseText;
        document.getElementById("ptr").value = xmlhttp.responseText;
        console.log(xmlhttp.responseText);
        //==================== GST ====================
        gstUrl = 'ajax/product.getGst.ajax.php?id=' + productId;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        document.getElementById("gst").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Product Id ====================
        document.getElementById("product-id").value = productId;

        // idUrl = `ajax/product.getName.ajax.php?Pid=${productId}`
        // // alert(unitUrl);
        // xmlhttp.open("GET", idUrl, false);
        // xmlhttp.send(null);
        // document.getElementById("product-ID").value = xmlhttp.responseText;
        // console.log(xmlhttp.responseText);

        //==================== Product Name ====================
        nameUrl = 'ajax/product.getMedicineDetails.ajax.php?pName=' + productId;
        // alert(unitUrl);
        xmlhttp.open("GET", nameUrl, false);
        xmlhttp.send(null);
        document.getElementById("product-name").value = xmlhttp.responseText;
        // console.log(xmlhttp.responseText);


        document.getElementById('batch-no').focus();

    } else {

        document.getElementById("manufacturer-id").innerHTML = "";
        document.getElementById("medicine-power").value = "";
        document.getElementById("packaging-type").innerHTML = "";
        document.getElementById("packaging-in").value = "";
        document.getElementById("weightage").value = "";
        document.getElementById("unit").value = "";
        document.getElementById("mrp").value = "";
        document.getElementById("gst").value = "";
        document.getElementById("product-id").value = "";
        document.getElementById("product-name").value = "";
    }
    document.getElementById("product-select").style.display = "none";
}


//==================== INPUT FUILD BLOCK FUNCTION ==================================
// ===== ON QTY ========
let QtyInput = document.getElementById('qty');
QtyInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (QtyInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});

// ========= ON FREE QTY ==========
let FreeQtyInput = document.getElementById('free-qty');
FreeQtyInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (FreeQtyInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});

// ========= ON DISCOUNT ==========
let DiscPercentInput = document.getElementById('discount');
DiscPercentInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (DiscPercentInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});

// ========= ON BATCH NUMBER ==========
let BatchNoInput = document.getElementById('batch-no');
BatchNoInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (BatchNoInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});

//=========================================================================================


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

const getbillDate = (billDate) => {
    billDate = billDate.value;
    document.getElementById("due-date").setAttribute("min", billDate);

    var date2 = todayDate.getDate() + 7;
    // // console.log(date2);
    var todayFullDate2 = year + "-" + month + "-" + date2;
    document.getElementById("due-date").setAttribute("max", todayFullDate2);
}


const getBillAmount = () => {

    let ptr = document.getElementById("ptr").value;
    let Mrp = document.getElementById("mrp").value;
    let chkPtr = document.getElementById("chk-ptr").value;

    let PTR = parseFloat(ptr);
    let MRP = parseFloat(Mrp);
    let ChkPtr = parseFloat(chkPtr);

    if (PTR > ChkPtr) {
        swal("Error Input", "PTR must be lesser than Calculated Value. Please enter proper PTR value!", "error");
        document.getElementById("ptr").value = "";
        document.getElementById("bill-amount").value = "";
        document.getElementById("ptr").value = "";
        document.getElementById("ptr").focus();
    }

    let qty = document.getElementById("qty").value;
    // let freeQty    = document.getElementById("free-qty").value;
    let discount = document.getElementById("discount").value;

    //========= base amount calculation area ===========

    let base = PTR - ((PTR * discount) / 100);
    document.getElementById("base").value = parseFloat(base).toFixed(2);
    // ======= eof base amount calculation =============

    let gst = document.getElementById("gst").value;
    let billAmount = document.getElementById("bill-amount");



    if (ptr == "") {
        billAmount.value = "";
        base = "";
    }

    if (qty != "" && ptr != "" && gst != "") {
        subAamount = base * qty;

        // console.log("subamount check : ");
        // console.log(subAamount);

        let totalGst = ((gst / 100) * subAamount);
        let amount = subAamount + totalGst;
        // console.log(amount);
        // console.log(amount - totalGst);
        let chkBillAmount = MRP * qty;

        if (amount > chkBillAmount) {
            amount = chkBillAmount;
        }

        billAmount.value = parseFloat(amount).toFixed(2);
    }
} //eof getBillAmount function

// ##################################################################################
// ##################################################################################

//geeting bills by clicking on add button
const addData = () => {
    let distId = document.getElementById("distributor-id");
    // console.log(distId.value1);
    let distBillid = document.getElementById("distributor-bill");
    let distBill = distBillid.value.toUpperCase();

    let billDate = document.getElementById("bill-date");
    let dueDate = document.getElementById("due-date");
    let paymentMode = document.getElementById("payment-mode");

    let productName = document.getElementById("product-name");
    let productId = document.getElementById("product-id");
    let batch = document.getElementById("batch-no");
    let batchNo = batch.value.toUpperCase();
    let manufId = document.getElementById("manufacturer-id");
    let medicinePower = document.getElementById("medicine-power");
    let expMonth = document.getElementById("exp-month");
    let expYear = document.getElementById("exp-year");
    let expDate = `${expMonth.value}/${expYear.value}`;
    expDate = expDate.toString();
    let mfdMonth = document.getElementById("mfd-month");
    let mfdYear = document.getElementById("mfd-year");
    let mfdDate = `${mfdMonth.value}/${mfdYear.value}`;
    mfdDate = mfdDate.toString()
    // var producDsc       = document.getElementById("product-descreption");
    let weightage = document.getElementById("weightage");
    let unit = document.getElementById("unit");
    let packagingIn = document.getElementById("packaging-in");
    let mrp = document.getElementById("mrp");
    let ptr = document.getElementById("ptr");
    let qty = document.getElementById("qty");
    let freeQty = document.getElementById("free-qty");
    let discount = document.getElementById("discount");
    let gst = document.getElementById("gst");
    let base = document.getElementById("base");
    let billAmount = document.getElementById("bill-amount");

    if (distId.value == "") {
        swal("Blank Field", "Please Selet Distributor First!", "error")
            .then((value) => {
                distId.focus();
            });
        return;
    }

    if (distBillid.value == "") {
        swal("Blank Field", "Please Enter Distributor Bill Number!", "error")
            .then((value) => {
                distBillid.focus();
            });
        return;
    }


    if (billDate.value == "") {
        swal("Blank Field", "Please Select Bill Date!", "error")
            .then((value) => {
                billDate.focus();
            });
        return;
    }

    if (dueDate.value == "") {
        swal("Blank Field", "Please Select Bill Payment Date!", "error")
            .then((value) => {
                dueDate.focus();
            });
        return;
    }

    if (paymentMode.value == "") {
        swal("Blank Field", "Please Select Payment Mode!", "error")
            .then((value) => {
                paymentMode.focus();
            });
        return;
    }
    if (productName.value == "") {
        swal("Blank Field", "Please Search & Select Product!", "error")
            .then((value) => {
                productName.focus();
            });
        return;
    }
    if (batch.value == "") {

        swal("Blank Field", "Please Enter Product Batch Number!", "error")
            .then((value) => {
                batch.focus();
            });
        return;
    }
    if (medicinePower.value == "") {
        medicinePower.focus();
        return;
    }
    if (mfdMonth.value == "") {
        swal("Blank field", "Please Enter Manufacturing Date as MM/YY", "error")
            .then((value) => {
                mfdMonth.focus();
            });
        return;
    }
    if (expMonth.value == "") {
        swal("Blank Field", "Please Enter Expiry Date as MM/YY", "error")
            .then((value) => {
                expMonth.focus();
            });
        return;
    }
    if (weightage.value == "") {
        weightage.focus();
        return;
    }
    if (unit.value == "") {
        unit.focus();
        return;
    }
    if (packagingIn.value == "") {
        packagingIn.focus();
        return;
    } else
        if (mrp.value == "") {
            mrp.focus();
            return;
        }
    if (ptr.value == "") {
        swal("Blank Field",
            "Please enter PTR value",
            "error")
            .then((value) => {
                ptr.focus();
            });
        return;
    }
    var Ptr = parseFloat(ptr.value);
    var Mrp = parseFloat(mrp.value);
    if (Ptr > Mrp) {
        swal("Blank Field",
            "Please check PTR value",
            "error")
            .then((value) => {
                ptr.focus();
            });
        return;
    }
    if (qty.value == "" || qty.value == 0) {
        swal("Blank Field",
            "Please Enter Quantity",
            "error")
            .then((value) => {
                qty.focus();
            });
        return;
    }
    if (freeQty.value == "") {
        swal("Free Qantity value is null",
            "Free Qantity Cannot be null. Minimum value 0",
            "error")
            .then((value) => {
                freeQty.focus();
            });
        return;
    }
    if (discount.value == "") {
        swal("Blank Field",
            "Please Enter Discount at least 0",
            "error")
            .then((value) => {
                discount.focus();
            });
        return;
    }
    if (gst.value == "") {
        gst.focus();
        return;
    }
    if (base.value == "") {
        base.focus();
        return;
    }
    if (billAmount.value == "") {
        billAmount.focus();
        return;
    }

    let slno = document.getElementById("dynamic-id").value;
    slno++;
    document.getElementById("dynamic-id").value = slno;

    var qtyVal = document.getElementById("qty-val").value;
    let itemQty = parseFloat(qty.value) + parseFloat(freeQty.value);
    totalQty = parseFloat(qtyVal) + itemQty;

    // console.log(totalQty);

    var net = document.getElementById("net-amount").value;
    netAmount = parseFloat(net) + parseFloat(billAmount.value);

    let total = qty.value * ptr.value;
    let totalWithDisc = total - (discount.value / 100 * total);

    let gstPerItem = (totalWithDisc + (gst.value / 100 * totalWithDisc)) - totalWithDisc;
    // let gstPerItem = withGst - total;
    let gstVal = document.getElementById("gst-val").value;
    let onlyGst = parseFloat(gstVal) + gstPerItem;

    //////////////////////
    // let totalQty = (parseFloat(qty.value) + parseFloat(freeQty.value));
    let totalMrp = parseFloat(mrp.value) * (parseFloat(qty.value) + parseFloat(freeQty.value));
    // console.log(totalMrp);
    let margin = totalMrp - billAmount.value;
    // console.log(margin);
    let marginP = (margin / totalMrp) * 100;
    // console.log(marginP);
    // let profit

    // console.log("discount percent check : ", discount.value);

    jQuery("#dataBody")
        .append(`<tr id="table-row-${slno}">
            <td style="color: red; padding-top:1.2rem;" <i class="fas fa-trash " onclick="deleteData(${slno}, ${itemQty}, ${gstPerItem}, ${billAmount.value})" style="font-size:.7rem;"></i></td>
            <td style="font-size:.7rem; padding-top:1.2rem; " scope="row">${slno}</td>
            <td class="pt-3">
                <input class="table-data w-8r" type="text" value="${productName.value}" style="word-wrap: break-word; font-size: .7rem;" readonly>
                <input type="text" name="productId[]" value="${productId.value}" style="display: none">
            </td>
            <td class="pt-3" >
                <input class="table-data w-4r" type="text" name="batchNo[]" value="${batchNo}" readonly style="font-size: .7rem;">
            </td>
            <td class=" pt-3">
                <input class="table-data w-3r" type="text" name="mfdDate[]" value="${mfdDate}" readonly style="font-size: .7rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="expDate[]" value="${expDate}" readonly style="font-size: .7rem;">
            </td>
            <td class="d-none pt-3" style="width: 2rem;">
                <input class="table-data w-2r" type="text" name="power[]" value="${medicinePower.value}" readonly style="font-size: .7rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="setof[]" value="${weightage.value}${unit.value}" readonly style="width: 3rem; font-size: .7rem;">
                <input class="table-data line-inp50" type="text" name="weightage[]" value="${weightage.value}" style="display: none" hidden>
                <input class="table-data line-inp50" type="text" name="unit[]" value="${unit.value}" style="display: none" hidden>

            </td>
            <td class="pt-3" style="width: 2rem;">
                <input class="table-data w-2r" type="text" name="qty[]" value="${qty.value}" readonly style="font-size: .7rem;">
            </td>
            <td class="pt-3" style="width: 2rem;">
                <input class="table-data w-2r" type="text" name="freeQty[]" value="${freeQty.value}" readonly style="font-size: .7rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="mrp[]" value="${mrp.value}" readonly style="font-size: .7rem;">
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="ptr[]" value="${ptr.value}" readonly style="font-size: .7rem;">
            </td>
            <td class="d-none pt-3">
                <input type="text" name="base[]" value="${base.value}" style="display: none">
                <p style="color: #000; font-size: .7rem; ">${base.value} <span class="bg-primary text-light p-1 disc-span" style="border-radius: 27%; font-size: .6rem; width:2rem; padding: 0%;">${discount.value}%</span> </p> 
            </td>
            <td class="d-none ps-1 pt-3" style="width: 2rem;">
                <input class="table-data w-2r" type="text" name="margin[]" value="${marginP.toFixed(0)}%" readonly style="font-size: .7rem;">
            </td>

            <td class="pt-3">
                <input class="table-data w-2r"  type="text" name="discount[]" value="${discount.value}" style="font-size: .7rem;">
            </td>
            
            <td class="pt-3" style="width: 2rem;">
                <input class="table-data w-2r" type="text" name="gst[]" value="${gst.value}%" readonly style="font-size: .7rem;">
                <input type="text" name="gstPerItem[]" value="${gstPerItem}" hidden>
            </td class="pt-3">
            <td class="amnt-td pt-3">
                <input class="table-data w-4r amnt-inp" type="text" name="billAmount[]" value="${billAmount.value}" readonly style="padding: 0%; font-size: .7rem;">
            </td>
        </tr>`);

    document.getElementById("product-name").value = "";
    document.getElementById("manufacturer-id").value = "";
    document.getElementById("manufacturer-name").value = "";
    document.getElementById("weightage").value = "";
    document.getElementById("unit").value = "";
    document.getElementById("packaging-in").value = "";
    document.getElementById("medicine-power").value = "";
    document.getElementById("batch-no").value = "";
    document.getElementById("mfd-month").value = "";
    document.getElementById("mfd-year").value = "";
    document.getElementById("exp-month").value = "";
    document.getElementById("exp-year").value = "";
    document.getElementById("mrp").value = "";
    document.getElementById("ptr").value = "";
    document.getElementById("qty").value = "";
    document.getElementById("free-qty").value = "";
    document.getElementById("packaging-type").value = "";
    document.getElementById("discount").value = "";
    document.getElementById("gst").value = "";
    document.getElementById("base").value = "";
    document.getElementById("bill-amount").value = "";

    document.getElementById("distributor-name").value = distId.value;
    document.getElementById("distributor-bill-no").value = distBill;
    document.getElementById("bill-date-val").value = billDate.value;
    document.getElementById("due-date-val").value = dueDate.value;
    document.getElementById("payment-mode-val").value = paymentMode.value;

    if (slno > 1) {
        let id = document.getElementById("items-val");
        let newId = parseFloat(id.value) + 1;
        document.getElementById("items-val").value = newId;
    }
    else {
        document.getElementById("items-val").value = slno;
    }

    document.getElementById("qty-val").value = totalQty;
    document.getElementById("gst-val").value = onlyGst.toFixed(2);
    document.getElementById("net-amount").value = netAmount.toFixed(2);

}

// ================================ Delet Data ================================

function deleteData(slno, itemQty, gstPerItem, total) {

    // == tabel row lenth and deleted row number ===
    let delRow = slno;
    //  ============================================

    jQuery(`#table-row-${slno}`).remove();
    let slVal = document.getElementById("dynamic-id").value;
    document.getElementById("dynamic-id").value = parseInt(slVal) - 1;


    //minus item
    let items = document.getElementById("items-val");
    let finalItem = items.value - 1;
    items.value = finalItem;

    // minus quantity
    let qty = document.getElementById("qty-val");
    let finalQty = qty.value - itemQty
    qty.value = finalQty;


    // minus netAmount
    let gst = document.getElementById("gst-val");
    let finalGst = gst.value - gstPerItem;
    gst.value = finalGst.toFixed(2);

    // minus netAmount
    let net = document.getElementById("net-amount");
    let finalAmount = net.value - total;
    net.value = finalAmount.toFixed(2);

    rowAdjustment(delRow);

}

function rowAdjustment(delRow) {

    let tableId = document.getElementById("dataBody");
    let j = 0;
    let colIndex = 1;

    for (let i = 0; i < tableId.rows.length; i++) {
        j++;
        let row = tableId.rows[i];
        let cell = row.cells[colIndex];
        cell.innerHTML = j;
    }
}


// ========================= Mfd and Expiry Date Setting =========================
let mfdMonthInput = document.getElementById('mfd-month');
mfdMonthInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (mfdMonthInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});

let expMonthInput = document.getElementById('exp-month');
expMonthInput.addEventListener('keydown', function (event) {
    if (event.keyCode === 9) {
        if (expMonthInput.value.trim() === '') {
            event.preventDefault();
        }
    }
});

const setMfdMonth = (month) => {
    let yr = new Date();
    let thisMonth = yr.getMonth();

    if (month.value.length > 2) {
        month.value = '';
        month.focus();
    } else if (month.value.length < 2) {
        month.focus();
    } else if (month.value.length == 2) {
        if (month.value > 12) {
            month.value = '';
            month.focus();
        } else {
            document.getElementById("mfd-year").focus();
        }
    } else {
        month.value = '';
        month.focus();
    }
}

const setExpMonth = (month) => {

    if (month.value <= 12) {
        if (month.value.length > 2) {
            month.value = '';
            month.focus();
        } else if (month.value.length < 2) {
            month.focus();
        } else if (month.value.length == 2) {
            if (month.value == 0) {
                month.value = '';
                month.focus();
            } else {
                document.getElementById("exp-year").focus();
            }
        } else {
            month.value = '';
            month.focus();
        }
    } else if (month.value == '') {
        month.focus();
    } else {
        month.value = '';
        month.focus();
    }
}


function setMfdYEAR(year) {
    if (year.value.length == 4) {
        document.getElementById("exp-month").focus();
    } else if (year.value.length > 4) {
        year.value = '';
        year.focus();
    }
}

function setMfdYear(year) {
    let yr = new Date();
    let thisYear = yr.getFullYear();
    let thisMonth = yr.getMonth();
    let mfdMnth = document.getElementById("mfd-month").value;

    if (year.value.length < 4) {
        document.getElementById("mfd-year").value = '';
        document.getElementById("mfd-year").focus();
    }
    if (year.value.length == 4) {
        if (year.value > thisYear) {
            document.getElementById("mfd-year").value = '';
            document.getElementById("mfd-year").focus();
        }

        if (year.value < thisYear) {
            document.getElementById("exp-month").focus();
        }

        if (year.value == thisYear) {
            if (mfdMnth > thisMonth) {
                document.getElementById("mfd-month").value = '';
                document.getElementById("mfd-month").focus();
            } else if (mfdMnth <= thisMonth) {
                document.getElementById("exp-month").focus();
            }
        }
        document.getElementById("exp-month").focus();
    }
}


function setExpYEAR(year) {
    if (year.value.length == 4) {
        document.getElementById('ptr').focus();
    } else if (year.value.length > 4) {
        year.value = '';
        year.focus();
    }
}

const setExpYear = (year) => {
    var MFDYR = document.getElementById("mfd-year").value;
    var mfdMnth = document.getElementById("mfd-month").value;
    var expMnth = document.getElementById("exp-month").value;

    if (year.value.length < 4) {
        year.value = '';
        year.focus();
    }

    if (year.value.length == 4) {
        if (year.value == MFDYR) {
            if (expMnth > mfdMnth) {
                document.getElementById("exp-month").value = '';
                document.getElementById("exp-month").focus();
            }

            if (expMnth < mfdMnth) {
                document.getElementById("exp-month").value = '';
                document.getElementById("exp-month").focus();
            }
        } else if (year.value < MFDYR) {
            year.value = '';
            year.focus();
        }
    }
}





