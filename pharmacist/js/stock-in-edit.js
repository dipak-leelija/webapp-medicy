// const editQTYarray = [];

const customClick = (id, value1, value2, value3) => {

    var prodId = value1;
    var billNo = value2;
    var batchNo = value3;

    var row = document.getElementById(id);

    $.ajax({
        url: "ajax/stokInEditAll.ajax.php",
        type: "POST",
        data: {
            pId: prodId,
            blNo: billNo,
            bhNo: batchNo
        },
        success: function (data) {

            alert(data);

            var dataObject = JSON.parse(data);

            var totalItmQty = parseInt(dataObject.qty) + parseInt(dataObject.FreeQty);
            var gstPerItem  = parseFloat(dataObject.GstAmount);
            var totalAmnt   = parseFloat(dataObject.amnt);

            var slno = id;
            slno = slno.replace(/\D/g, '');
            console.log(slno);
            var itemQty = totalItmQty;
            gstPerItem = gstPerItem.toFixed(2);
            var total = totalAmnt.toFixed(2);

            var purchaseDetailsMfdDate = dataObject.mfdDate;
            var mfdMonth = purchaseDetailsMfdDate.slice(0, 2);
            var mfdYear = purchaseDetailsMfdDate.slice(3, 5);

            var purchaseDetailsExpDate = dataObject.expDate;
            var expMonth = purchaseDetailsExpDate.slice(0, 2);
            var expYear = purchaseDetailsExpDate.slice(3, 5);
            var manuf = dataObject.manufacturer;

            manuf = manuf.replace(/&#39/g, "'");
            manuf = manuf.replace(/&lt/g, "<");
            manuf = manuf.replace(/&gt/g, ">");

            var totalQty = parseInt(dataObject.qty) + parseInt(dataObject.FreeQty);
            // //+++++++------  Adding data to is subsequent form body  ---------++++++++++++++++

            document.getElementById("purchase-id").value = dataObject.purchaseId;
            document.getElementById("product-id").value = dataObject.productId;
            // document.getElementById("dist-bill-no").value = dataObject.billNo;
            document.getElementById("batch-no").value = dataObject.batchNo;

            document.getElementById("product-name").value = dataObject.productName;
            document.getElementById("manufacturer-name").value = manuf;

            document.getElementById("weightage").value = dataObject.weightage;
            document.getElementById("unit").value = dataObject.unit;
            document.getElementById("packaging-in").value = dataObject.packageType;
            document.getElementById("medicine-power").value = dataObject.power;

            document.getElementById("MFD-month").value = mfdMonth;
            document.getElementById("MFD-year").value = mfdYear;

            document.getElementById("exp-month").value = expMonth;
            document.getElementById("exp-year").value = expYear;

            document.getElementById("mrp").value = dataObject.mrp;
            document.getElementById("ptr").value = dataObject.ptr;
            document.getElementById("qty").value = dataObject.qty;
            document.getElementById("free-qty").value = dataObject.FreeQty;
            document.getElementById("updtQTYS").value = totalQty;
            document.getElementById("packaging-type-edit").value = dataObject.packageType;

            document.getElementById("discount").value = dataObject.disc;
            document.getElementById("gst").value = dataObject.gst;
            document.getElementById("crntGstAmnt").value = dataObject.GstAmount;
            document.getElementById('base').value = dataObject.baseAmount;
            document.getElementById("bill-amount").value = dataObject.amnt;
            document.getElementById("temp-bill-amount").value = dataObject.amnt;
            // document.getElementById("refund-amount").value = refundAmunt;


            //++++++++++++++++++---  removing selected row  -----+++++++++++++++++++
            
            // row.parentNode.removeChild(row);
            
            deleteData(slno, itemQty, gstPerItem, total);
        }
    })
    return false;
}

const getDtls = (value) => {

    // console.log(value);
    // alert(value);
    var xmlhttp = new XMLHttpRequest();
    if (value != "") {

        //==================== Product Id ====================
        manufacturerurl = 'ajax/product.getManufacturer.ajax.php?id=' + value;
        // alert(url);
        xmlhttp.open("GET", manufacturerurl, false);
        xmlhttp.send(null);
        document.getElementById("manufacturer-id").value = xmlhttp.responseText;

        //==================== Manufacturere List ====================
        manufacturerurl = 'ajax/product.getManufacturer.ajax.php?id=' + value;
        // alert(url);
        xmlhttp.open("GET", manufacturerurl, false);
        xmlhttp.send(null);
        document.getElementById("manufacturer-id").value = xmlhttp.responseText;

        manufacturerName = 'ajax/product.getManufacturer.ajax.php?name=' + value;
        // alert(url);
        xmlhttp.open("GET", manufacturerName, false);
        xmlhttp.send(null);
        document.getElementById("manufacturer-name").value = xmlhttp.responseText;

        //==================== Medicine Power ====================
        powerurl = 'ajax/product.getMedicinePower.ajax.php?id=' + value;
        // alert(url);
        xmlhttp.open("GET", powerurl, false);
        xmlhttp.send(null);
        document.getElementById("medicine-power").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Packaging Type ====================
        packTypeUrl = 'ajax/product.getpackType.ajax.php?id=' + value;
        // alert(url);
        xmlhttp.open("GET", packTypeUrl, false);
        xmlhttp.send(null);
        document.getElementById("packaging-type").innerHTML = xmlhttp.responseText;

        packTypeFieldUrl = 'ajax/product.getpackTypefield.ajax.php?id=' + value;
        // // alert(url);
        xmlhttp.open("GET", packTypeFieldUrl, false);
        xmlhttp.send(null);
        document.getElementById("packaging-in").value = xmlhttp.responseText;

        // alert(xmlhttp.responseText);

        //==================== Weightage ====================
        weightage = 'ajax/product.getWeightage.ajax.php?id=' + value;
        // alert(url);
        xmlhttp.open("GET", weightage, false);
        xmlhttp.send(null);
        document.getElementById("weightage").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Unit ====================
        unitUrl = 'ajax/product.getUnit.ajax.php?id=' + value;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", unitUrl, false);
        xmlhttp.send(null);
        document.getElementById("unit").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== MRP ====================
        mrpUrl = 'ajax/product.getMrp.ajax.php?id=' + value;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", mrpUrl, false);
        xmlhttp.send(null);
        document.getElementById("mrp").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== GST ====================
        gstUrl = 'ajax/product.getGst.ajax.php?id=' + value;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", gstUrl, false);
        xmlhttp.send(null);
        document.getElementById("gst").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

        //==================== Product Id ====================
        document.getElementById("product-id").value = value;

        //==================== Product Name ====================
        nameUrl = 'ajax/product.getName.ajax.php?id=' + value;
        // alert(unitUrl);
        xmlhttp.open("GET", nameUrl, false);
        xmlhttp.send(null);
        document.getElementById("product-name").value = xmlhttp.responseText;
        // alert(xmlhttp.responseText);

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
}

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
    // billDate = document.getElementById("bill-date").value;
    // alert(billDate.value);
    billDate = billDate.value;
    // alert(billDate.substr(0, 4));
    // alert(billDate.substr(5, 2));
    // alert(billDate.substr(8, 2));
    document.getElementById("due-date").setAttribute("min", billDate);

    var date2 = todayDate.getDate() + 7;
    // // console.log(date2);
    var todayFullDate2 = year + "-" + month + "-" + date2;
    document.getElementById("due-date").setAttribute("max", todayFullDate2);

}


const getBillAmount = () => {
    // let mrp = document.getElementById("mrp").value;
    let ptr = document.getElementById("ptr").value;

    let qty = document.getElementById("qty").value;
    // let freeQty    = document.getElementById("free-qty").value;
    let discount = document.getElementById("discount").value;
    let base = document.getElementById("base");
    let gst = document.getElementById("gst").value;
    let billAmount = document.getElementById("bill-amount");

    if (ptr == "") {
        billAmount.value = "";
        base.value = "";

    }
    if (qty != "" && ptr != "" && gst != "") {
        let subAamount = ptr * qty;

        let baseAmount = subAamount / qty;

        base.value = parseFloat(baseAmount).toFixed(2);

        let totalGst = (gst / 100 * subAamount);
        let amount = subAamount + totalGst;

        billAmount.value = parseFloat(amount).toFixed(2);
    }

    if (discount != "") {
        if (qty != "" && ptr != "" && gst != "") {

            let subAamount = ptr * qty;

            let afterDiscount = subAamount - (discount / 100 * subAamount);

            let baseAmount = afterDiscount / qty;

            base.value = parseFloat(baseAmount).toFixed(2);

            let amount = afterDiscount + (gst / 100 * afterDiscount);

            billAmount.value = parseFloat(amount).toFixed(2);
        }
    }

    ///======== QUANTITY CALCULETION AFTER EDIT UPDATE ============

    var crntQTY = document.getElementById("qty").value;
    var crntFreeQTY = document.getElementById("free-qty").value;
    document.getElementById("updtQTYS").value = Number(crntQTY) + Number(crntFreeQTY);

    //=================== GST CALCULETION AFTER EDIT UPDATE =============================

    let qtys = document.getElementById("qty").value;
    let updtBasePrice = document.getElementById("base").value;
    let GST = document.getElementById("gst").value;

    if (GST != 0) {
        var gstVal = parseFloat(GST) / 100;
    } else {
        var gstVal = 0;
    }

    updtGstAmt = parseFloat(qtys) * parseFloat(updtBasePrice) * parseFloat(gstVal);

    document.getElementById("crntGstAmnt").value = updtGstAmt.toFixed(2);

    console.log("GST AMNT => ", updtGstAmt.toFixed(2));
    //eof gst calculetion after edit data ---------------

} //eof getBillAmount function

// ============= QTY CALCULETION ON FREE QTY UPDATE ==================
const editQTY = () => {
    var crntQTY = document.getElementById("qty").value;
    var crntFreeQTY = document.getElementById("free-qty").value;
    document.getElementById("updtQTYS").value = Number(crntQTY) + Number(crntFreeQTY);
}
// ##################################################################################


// ##################################################################################

//geeting bills by clicking on add button
const addData = () => {
    var distId = document.getElementById("distributor-id");
    var distBillid = document.getElementById("distributor-bill");
    distBill = distBillid.value.toUpperCase();
    var billDate = document.getElementById("bill-date");
    var dueDate = document.getElementById("due-date");
    var paymentMode = document.getElementById("payment-mode");

    var productName = document.getElementById("product-name");
    var productId = document.getElementById("product-id");
    var batch = document.getElementById("batch-no");
    batchNo = batch.value.toUpperCase();
    var manufId = document.getElementById("manufacturer-id");
    var medicinePower = document.getElementById("medicine-power");
    var mfdMonth = document.getElementById("MFD-month");
    var mfdYear = document.getElementById("MFD-year");
    var mfdDate = `${mfdMonth.value}/${mfdYear.value}`;
    mfdDate = mfdDate.toString()
    var expMonth = document.getElementById("exp-month");
    var expYear = document.getElementById("exp-year");
    var expDate = `${expMonth.value}/${expYear.value}`;
    expDate = expDate.toString()
    // var producDsc       = document.getElementById("product-descreption");
    var weightage = document.getElementById("weightage");
    var unit = document.getElementById("unit");
    var packagingIn = document.getElementById("packaging-in");
    var mrp = document.getElementById("mrp");
    var ptr = document.getElementById("ptr");
    var qty = document.getElementById("qty");
    var freeQty = document.getElementById("free-qty");
    
    var discount = document.getElementById("discount");
    var gst = document.getElementById("gst");
    var base = document.getElementById("base");
    var billAmount = document.getElementById("bill-amount");
    var prevAmount = document.getElementById("temp-bill-amount");
    var purchaseId = document.getElementById("purchase-id");
    var crntGstAmount = document.getElementById("crntGstAmnt");
    var itemQty = document.getElementById("updtQTYS").value;
    
    
    // alert("gst : ",gst);

    

    if (distId.value == "") {
        swal("Blank Field", "Please Selet Distributor First!", "error")
            .then((value) => {
                distId.focus();
            });
    } else {
        if (distBillid.value == "") {
            swal("Blank Field", "Please Enter Distributor Bill Number!", "error")
                .then((value) => {
                    distBillid.focus();
                });
        } else {
            if (billDate.value == "") {
                swal("Blank Field", "Please Select Bill Date!", "error")
                    .then((value) => {
                        billDate.focus();
                    });
            } else {
                if (dueDate.value == "") {
                    swal("Blank Field", "Please Select Bill Payment Date!", "error")
                        .then((value) => {
                            dueDate.focus();
                        });
                } else {
                    if (paymentMode.value == "") {
                        swal("Blank Field", "Please Select Payment Mode!", "error")
                            .then((value) => {
                                paymentMode.focus();
                            });
                    } else {
                        if (productName.value == "") {
                            swal("Blank Field", "Please Search & Select Product!", "error")
                                .then((value) => {
                                    productName.focus();
                                });
                        } else {
                            if (batch.value == "") {

                                swal("Blank Field", "Please Enter Product Batch Number!", "error")
                                    .then((value) => {
                                        batch.focus();
                                    });
                            } else {
                                if (medicinePower.value == "") {
                                    medicinePower.focus();
                                } else {
                                    if (mfdMonth.value == "") {
                                        swal("Blank Field", "Please Enter Manufacturing Date as MM/YY", "error")
                                            .then((value) => {
                                                mfdMonth.focus();
                                            });
                                    } else {
                                        if (expMonth.value == "") {
                                            swal("Blank Field", "Please Enter Expiry Date as MM/YY", "error")
                                                .then((value) => {
                                                    expMonth.focus();
                                                });
                                        } else {
                                            if (weightage.value == "") {
                                                weightage.focus();
                                            } else {
                                                if (unit.value == "") {
                                                    unit.focus();
                                                } else {
                                                    if (packagingIn.value == "") {
                                                        packagingIn.focus();
                                                    } else {
                                                        if (mrp.value == "") {
                                                            mrp.focus();
                                                        } else {
                                                            if (ptr.value == "") {
                                                                swal("Blank Field",
                                                                    "Please enter PTR value",
                                                                    "error")
                                                                    .then((value) => {
                                                                        ptr.focus();
                                                                    });
                                                            } else {
                                                                var Ptr = parseFloat(ptr.value);
                                                                var Mrp = parseFloat(mrp.value);
                                                                if (Ptr > Mrp) {
                                                                    swal("Blank Field",
                                                                        "Please check PTR value",
                                                                        "error")
                                                                        .then((value) => {
                                                                            ptr.focus();
                                                                        });
                                                                } else {
                                                                    if (qty.value == "") {
                                                                        swal("Blank Field",
                                                                            "Please Enter Quantity",
                                                                            "error")
                                                                            .then((value) => {
                                                                                qty.focus();
                                                                            });
                                                                    } else {
                                                                        if (freeQty.value == "") {
                                                                            swal("Blank Field",
                                                                                "Please Enter Free Quantity",
                                                                                "error")
                                                                                .then((value) => {
                                                                                    freeQty.focus();
                                                                                });
                                                                        } else {
                                                                            if (discount.value == "") {
                                                                                swal("Blank Field",
                                                                                    "Please Enter Discount at least 0",
                                                                                    "error")
                                                                                    .then((value) => {
                                                                                        discount.focus();
                                                                                    });
                                                                            } else {
                                                                                if (gst.value == "") {
                                                                                    gst.focus();
                                                                                } else {
                                                                                    if (base.value == "") {
                                                                                        base.focus();
                                                                                    } else {
                                                                                        if (billAmount.value ==
                                                                                            "") {
                                                                                            billAmount.focus();
                                                                                        } else {
                                                                                            let slno = document
                                                                                                .getElementById(
                                                                                                    "dynamic-id"
                                                                                                )
                                                                                                .value;
                                                                                            slno++;
                                                                                            document
                                                                                                .getElementById(
                                                                                                    "dynamic-id"
                                                                                                )
                                                                                                .value = slno;

                                                                                            var qtyVal =
                                                                                                document
                                                                                                    .getElementById(
                                                                                                        "qty-val")
                                                                                                    .value;
                                                                                            // let itemQty =
                                                                                            //     parseFloat(qty
                                                                                            //         .value) +
                                                                                            //     parseFloat(
                                                                                            //         freeQty
                                                                                            //             .value);

                                                                                            totalQty =
                                                                                                parseFloat(
                                                                                                    qtyVal) +
                                                                                                parseFloat(itemQty);

                                                                                            // console.log(totalQty);

                                                                                            var net = document
                                                                                                .getElementById(
                                                                                                    "net-amount"
                                                                                                )
                                                                                                .value;
                                                                                            //    console.log(net);
                                                                                            var addAmount = parseFloat(billAmount.value);
                                                                                            netAmount =
                                                                                                parseFloat(
                                                                                                    net) +
                                                                                                addAmount;

                                                                                            console.log("net amnt =>", netAmount);
                                                                                            // console.log("Net Value");


                                                                                            let total = qty
                                                                                                .value *
                                                                                                ptr.value;
                                                                                            let totalWithDisc =
                                                                                                total - (
                                                                                                    discount
                                                                                                        .value /
                                                                                                    100 *
                                                                                                    total);

                                                                                            let gstPerItem = parseFloat(crntGstAmount.value);
                                                                                            // let gstPerItem = withGst - total;

                                                                                            let gstVal =
                                                                                                document
                                                                                                    .getElementById(
                                                                                                        "gst-val")
                                                                                                    .value;

                                                                                            let onlyGst =
                                                                                                parseFloat(
                                                                                                    gstVal) +
                                                                                                gstPerItem;
                                                                                            onlyGst = onlyGst.toFixed(2);

                                                                                            //////////////////////
                                                                                            // let totalQty = (parseFloat(qty.value) + parseFloat(freeQty.value));
                                                                                            let totalMrp =
                                                                                                parseFloat(mrp
                                                                                                    .value) * (
                                                                                                    parseFloat(
                                                                                                        qty
                                                                                                            .value
                                                                                                    ) +
                                                                                                    parseFloat(
                                                                                                        freeQty
                                                                                                            .value)
                                                                                                );
                                                                                            // console.log(totalMrp);
                                                                                            let margin =
                                                                                                totalMrp -
                                                                                                billAmount
                                                                                                    .value;
                                                                                            // console.log(margin);
                                                                                            let marginP = (
                                                                                                margin /
                                                                                                totalMrp) *
                                                                                                100;
                                                                                            // console.log(marginP);
                                                                                            // let profit

                                                                                            jQuery("#dataBody")
                                                                                                .append(`<tr id="table-row-${slno}">
            <td style="color: red; padding-top:1.2rem "<i class="fas fa-trash " onclick="deleteData(${slno}, ${itemQty}, ${gstPerItem}, ${billAmount.value})"></i></td>
            <td style="font-size:.8rem ; padding-top:1.2rem"scope="row">${slno}</td>
            <td class="pt-3" hidden>
                <input class="table-data w-12r" type="text" name="purchaseId[]" value="${purchaseId.value}" readonly>
            </td>
            <td class="pt-3">
                <input class="table-data w-12r" type="text" value="${productName.value}" readonly>
                <input type="text" name="productId[]" value="${productId.value}" style="display: none">
            </td>
            <td class=" pt-3" >
                <input class="table-data w-5r" type="text" name="batchNo[]" value="${batchNo}" readonly>
            </td>
            <td class=" pt-3">
                <input class="table-data w-3r" type="text" name="mfdDate[]" value="${mfdDate}" readonly>
            </td>
            <td class=" pt-3">
                <input class="table-data w-3r" type="text" name="expDate[]" value="${expDate}" readonly>
            </td>
            <td class=" pt-3" hidden>
                <input class="table-data w-4r" type="text" name="power[]" value="${medicinePower.value}" readonly " style="display: none">
            </td>
            <td class=" pt-3">
                <input class="table-data w-4r" type="text" name="setof[]" value="${weightage.value}${unit.value}" readonly>
                <input class="table-data line-inp50" type="text" name="weightage[]" value="${weightage.value}" style="display: none" >
                <input class="table-data line-inp50" type="text" name="unit[]" value="${unit.value}" style="display: none">

            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="qty[]" value="${qty.value}" readonly>
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="freeQty[]" value="${freeQty.value}" readonly>
            </td>
            <td class="pt-3">
                <input class="table-data w-4r" type="text" name="mrp[]" value="${mrp.value}" readonly></td class="p-0">
            <td class="pt-3">
                <input class="table-data w-4r" type="text" name="ptr[]" value="${ptr.value}" readonly>
            </td>
            <td class="pt-3">
                <input type="text" name="base[]" value="${base.value}" style="display: none">
                <input  type="text" name="discount[]" value="${discount.value}" style="display: none ;">
                <p style="color: #000; font-size: .8rem;">${base.value} <span class="bg-primary text-light p-1 disc-span" style="border-radius: 28%; font-size: .6rem;">${discount.value}%</span> </p>
            </td>
            <td class="ps-1 pt-3">
                <input class="table-data w-3r" type="text" name="margin[]" value="${marginP.toFixed(2)}" readonly>
            </td>
            <td class="pt-3">
                <input class="table-data w-3r" type="text" name="gst[]" value="${gst.value}" readonly>
                <input type="text" name="gstPerItem[]" value="${gstPerItem}" hidden>
            </td class="pt-3">
            <td class="amnt-td pt-3">
                <input class="table-data w-5r amnt-inp" type="text" name="billAmount[]" value="${billAmount.value}" readonly>
            </td>
        </tr>`);

                                                                                            document.getElementById(
                                                                                                "distributor-name"
                                                                                            ).value = distId
                                                                                                    .value;

                                                                                            document
                                                                                                .getElementById(
                                                                                                    "distributor-bill-no"
                                                                                                ).value =
                                                                                                distBill;

                                                                                            document
                                                                                                .getElementById(
                                                                                                    "bill-date-val"
                                                                                                )
                                                                                                .value =
                                                                                                billDate.value;

                                                                                            document
                                                                                                .getElementById(
                                                                                                    "due-date-val"
                                                                                                )
                                                                                                .value = dueDate
                                                                                                    .value;

                                                                                            document
                                                                                                .getElementById(
                                                                                                    "payment-mode-val"
                                                                                                ).value =
                                                                                                paymentMode
                                                                                                    .value;

                                                                                            //item-table

                                                                                            if (slno > 1) {
                                                                                                let id = document.getElementById("items-val");
                                                                                                let newId = parseFloat(id.value) + 1;
                                                                                                document.getElementById("items-val").value = newId;

                                                                                            } else {
                                                                                                document.getElementById("items-val").value = slno;
                                                                                            }

                                                                                            document
                                                                                                .getElementById(
                                                                                                    "qty-val")
                                                                                                .value =
                                                                                                totalQty;

                                                                                            document
                                                                                                .getElementById(
                                                                                                    "gst-val")
                                                                                                .value = onlyGst;
                                                                                            // document.getElementById(
                                                                                            //         "margin-val")
                                                                                            //     .value = marginP.toFixed(2);
                                                                                            document
                                                                                                .getElementById(
                                                                                                    "net-amount"
                                                                                                )
                                                                                                .value =
                                                                                                netAmount.toFixed(2);


                                                                                            // clearing all fied value                                                                    
                                                                                            document.getElementById("product-name").value = "";
                                                                                            document.getElementById("manufacturer-id").value = "";
                                                                                            document.getElementById("manufacturer-name").value = "";
                                                                                            document.getElementById("weightage").value = "";
                                                                                            document.getElementById("unit").value = "";
                                                                                            document.getElementById("packaging-in").value = "";
                                                                                            document.getElementById("medicine-power").value = "";
                                                                                            document.getElementById("batch-no").value = "";

                                                                                            document.getElementById("MFD-month").value = "";
                                                                                            document.getElementById("MFD-year").value = "";
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
                                                                                            document.getElementById("purchase-id").value = "";
                                                                                            document.getElementById("updtQTYS").value = "";
                                                                                            document.getElementById("temp-bill-amount").value = "";
                                                                                            
                                                                                            document.getElementById("crntGstAmnt").value = "";

                                                                                            // 
                                                                                            // document.getElementById("product-detail").reset();



                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    }

    // freeQty.value = '';
    // freeQty.value = '';

    //========== Clearing fields after adding Data =============

} //eof addData  

// ================================ Delet Data ================================


function deleteData(slno, itemQty, gstPerItem, total) {
    alert(slno);
    jQuery(`#table-row-${slno}`).remove();
    slno--;
    document.getElementById("dynamic-id").value = slno;

    //minus item
    let items = document.getElementById("items-val");
    let finalItem = items.value - 1;
    items.value = finalItem;

    // minus quantity
    let qty = document.getElementById("qty-val");
    let finalQty = qty.value - itemQty
    qty.value = finalQty;


    // minus gst
    let gst = document.getElementById("gst-val");
    let finalGst = gst.value - gstPerItem;
    gst.value = finalGst.toFixed(2);

    // minus netAmount
    let net = document.getElementById("net-amount");
    let finalAmount = net.value - total;
    net.value = finalAmount.toFixed(2);

}

// ======================= Manufacturing date setting ===================

const setMfdMonth = (month) => {
    if (month.value.length > 2) {
        month.value = '';
    } else {
        if (month.value > 12) {
            // console.log("Its Over");
            month.value = '';
        } else {
            if (month.value.length == 2) {
                document.getElementById("MFD-year").focus();
            }

        }
    }
}


const setMfdYear = (year) => {
    if (year.value.length > 2) {
        year.value = '';
    }
    if (year.value < 2) {
        year.value = '';
    }
}
// ========================= Expiry Date Setting =========================

const setMonth = (month) => {
    // alert(month.value);
    // alert(month.value.length);
    if (month.value.length > 2) {
        month.value = '';
        // console.log("Ok");
    } else {
        if (month.value > 12) {
            // console.log("Its Over");
            month.value = '';
        } else {
            if (month.value.length == 2) {
                document.getElementById("exp-year").focus();
            }

        }
    }

}

const setYear = (year) => {
    // alert(month.value);
    // alert(month.value.length);
    if (year.value.length > 2) {
        year.value = '';
        // console.log("Ok");
    }
    if (year.value < 2) {
        year.value = '';
    }
}