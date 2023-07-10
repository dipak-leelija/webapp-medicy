function searchItem(input) {
    console.log(input);
    // alert(value);
    let xmlhttp = new XMLHttpRequest();

    let searchReult = document.getElementById('product-select');

    if (input == "") {
        document.getElementById("product-select").style.display = "none";
    }

    if (input != "") {
        document.getElementById("product-select").style.display = "block";
    }

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            searchReult.innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open('GET', 'ajax/purchase-item-list.ajax.php?data=' + input, true);
    xmlhttp.send();
}


const getDtls = (value) => {

    // console.log(value);
    // alert(value);
    let xmlhttp = new XMLHttpRequest();

    if (value != "") {
        // console.log(value);
        //==================== Manufacturere List ====================
        manufacturerurl = 'ajax/product.getManufacturer.ajax.php?id=' + value;
        // alert(url);
        xmlhttp.open("GET", manufacturerurl, false);
        xmlhttp.send(null);
        document.getElementById("manufacturer-id").value = xmlhttp.responseText;

        manufacturerName = 'ajax/product.getManufacturer.ajax.php?name=' + value;
        xmlhttp.open("GET", manufacturerName, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("manufacturer-name").value = xmlhttp.responseText;

        //==================== Medicine Power ====================
        powerurl = 'ajax/product.getMedicineDetails.ajax.php?power=' + value;
        // alert(url);
        xmlhttp.open("GET", powerurl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("medicine-power").value = xmlhttp.responseText;

        //==================== Packaging Type ====================
        packTypeUrl = 'ajax/product.getMedicineDetails.ajax.php?pType=' + value;
        // alert(url);
        xmlhttp.open("GET", packTypeUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("packaging-type").innerHTML = xmlhttp.responseText;

        packTypeFieldUrl = 'ajax/product.getMedicineDetails.ajax.php?packegeIn=' + value;
        // // alert(url);
        xmlhttp.open("GET", packTypeFieldUrl, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("packaging-in").value = xmlhttp.responseText;

        //==================== Weightage ====================
        weightage = 'ajax/product.getMedicineDetails.ajax.php?weightage=' + value;
        // alert(url);
        xmlhttp.open("GET", weightage, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("weightage").value = xmlhttp.responseText;


        //==================== Unit ====================
        unitUrl = 'ajax/product.getMedicineDetails.ajax.php?unit=' + value;
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

        //==================== ptr check url ===================

        chkPtr = 'ajax/product.getMrp.ajax.php?ptrChk=' + value;
        // alert(unitUrl);
        // window.location.href = unitUrl;
        xmlhttp.open("GET", chkPtr, false);
        xmlhttp.send(null);
        // alert(xmlhttp.responseText);
        document.getElementById("chk-ptr").value = xmlhttp.responseText;
        document.getElementById("ptr").value = xmlhttp.responseText;


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

        // idUrl = `ajax/product.getName.ajax.php?Pid=${value}`
        // // alert(unitUrl);
        // xmlhttp.open("GET", idUrl, false);
        // xmlhttp.send(null);
        // document.getElementById("product-ID").value = xmlhttp.responseText;
        // console.log(xmlhttp.responseText);

        //==================== Product Name ====================
        nameUrl = 'ajax/product.getMedicineDetails.ajax.php?pName=' + value;
        // alert(unitUrl);
        xmlhttp.open("GET", nameUrl, false);
        xmlhttp.send(null);
        document.getElementById("product-name").value = xmlhttp.responseText;
        // console.log(xmlhttp.responseText);

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
    // let mrp = document.getElementById("mrp").value;
    let ptr = document.getElementById("ptr").value;
    let Mrp = document.getElementById("mrp").value;
    let chkPtr = document.getElementById("chk-ptr").value;

    let PTR = parseFloat(ptr);
    let MRP = parseFloat(Mrp);
    let ChkPtr = parseFloat(chkPtr);

    if (PTR > ChkPtr) {
        // console.log(Mrp);
        // console.log(ptr);
        swal("Error Input", "PTR must be lesser than Calculated Value. Please enter proper PTR value!", "error");
        document.getElementById("ptr").value = "";
        document.getElementById("bill-amount").value = "";
        document.getElementById("ptr").value = "";
    }

    let qty = document.getElementById("qty").value;
    // let freeQty    = document.getElementById("free-qty").value;
    let discount = document.getElementById("discount").value;

    //========= base amount calculation area ===========
    // console.log("check ptr");
    // console.log(PTR);
    let base = PTR - ((PTR * discount) / 100);
    // console.log("base amount check : ");
    // console.log(base);
    document.getElementById("base").value = parseFloat(base).toFixed(2);
    // ======= eof base amount calculation =============

    let gst = document.getElementById("gst").value;

    let billAmount = document.getElementById("bill-amount");
    // alert(ptr)
    // console.log(gst);


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
                                        swal("Blank field", "Please Enter Manufacturing Date as MM/YY", "error")
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
                                                                    if (qty.value == "" || qty.value == 0) {
                                                                        swal("Blank Field",
                                                                            "Please Enter Quantity",
                                                                            "error")
                                                                            .then((value) => {
                                                                                qty.focus();
                                                                            });
                                                                    } else {
                                                                        if (freeQty.value == "") {
                                                                            swal("Qantity Value Zero",
                                                                                "Qantity Cannot be 0",
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
                                                                                            let itemQty =
                                                                                                parseFloat(qty
                                                                                                    .value) +
                                                                                                parseFloat(
                                                                                                    freeQty
                                                                                                        .value);
                                                                                            totalQty =
                                                                                                parseFloat(
                                                                                                    qtyVal) +
                                                                                                itemQty;

                                                                                            // console.log(totalQty);

                                                                                            var net = document
                                                                                                .getElementById(
                                                                                                    "net-amount"
                                                                                                )
                                                                                                .value;
                                                                                            //    console.log(net);
                                                                                            netAmount =
                                                                                                parseFloat(
                                                                                                    net) +
                                                                                                parseFloat(
                                                                                                    billAmount
                                                                                                        .value
                                                                                                );
                                                                                            // console.log(netAmount);
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

                                                                                            let gstPerItem = (
                                                                                                totalWithDisc +
                                                                                                (gst.value /
                                                                                                    100 *
                                                                                                    totalWithDisc
                                                                                                )) -
                                                                                                totalWithDisc;
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
            <td style="color: red; padding-top:1.2rem;" <i class="fas fa-trash " onclick="deleteData(${slno}, ${itemQty}, ${gstPerItem}, ${billAmount.value})" style="font-size:.7rem;"></i></td>
            <td style="font-size:.7rem; padding-top:1.2rem; " scope="row">${slno}</td>
            <td class="pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" value="${productName.value}" style="word-wrap: break-word; width:9rem; font-size: .7rem;" readonly>
                <input type="text" name="productId[]" value="${productId.value}" style="display: none">
            </td>
            <td class=" pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="batchNo[]" value="${batchNo}" readonly style="width: 6rem; font-size: .7rem;">
            </td>
            <td class=" pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="mfdDate[]" value="${mfdDate}" readonly style="width: 3rem; font-size: .7rem;">
            </td>
            <td class=" pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="expDate[]" value="${expDate}" readonly style="width: 3rem; font-size: .7rem;">
            </td>
            <td class=" pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="power[]" value="${medicinePower.value}" readonly style="width: 2rem; font-size: .7rem;">
            </td>
            <td class=" pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="setof[]" value="${weightage.value}${unit.value}" readonly style="width: 3rem; font-size: .7rem;">
                <input class="table-data line-inp50" type="text" name="weightage[]" value="${weightage.value}" style="display: none" hidden>
                <input class="table-data line-inp50" type="text" name="unit[]" value="${unit.value}" style="display: none" hidden>

            </td>
            <td class="pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="qty[]" value="${qty.value}" readonly style="width: 2rem; padding: 0%; font-size: .7rem;">
            </td>
            <td class="pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="freeQty[]" value="${freeQty.value}" readonly style="width: 2rem; padding: 0%; font-size: .7rem;">
            </td>
            <td class="pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="mrp[]" value="${mrp.value}" readonly style="width: 3rem; padding: 0%; font-size: .7rem;">
            </td>
            <td class="pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="ptr[]" value="${ptr.value}" readonly style="width: 3rem; padding: 0%; font-size: .7rem;">
            </td>
            <td class="pt-3" style="padding: .3rem;">
                <input type="text" name="base[]" value="${base.value}" style="display: none">
                <input  type="text" name="discount[]" value="${discount.value}" style="display: none ;">
                <p style="color: #000; font-size: .7rem; ">${base.value} <span class="bg-primary text-light p-1 disc-span" style="border-radius: 27%; font-size: .7rem; width:2rem; padding: 0%;">${discount.value}%</span> </p>
            </td>
            <td class="ps-1 pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="margin[]" value="${marginP.toFixed(0)}%" readonly style="width: 2rem; padding: 0%; font-size: .7rem;">
            </td>
            <td class="pt-3" style="padding: .3rem;">
                <input class="table-data w-3r" type="text" name="gst[]" value="${gst.value}" readonly style="width: 2rem; padding: 0%; font-size: .7rem;">
                <input type="text" name="gstPerItem[]" value="${gstPerItem}" hidden>
            </td class="pt-3">
            <td class="amnt-td pt-3" style="padding: .3rem;">
                <input class="table-data w-3r amnt-inp" type="text" name="billAmount[]" value="${billAmount.value}" readonly style="width: 4rem; padding: 0%; font-size: .7rem;">
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


                                                                                            document
                                                                                                .getElementById("distributor-name").value = distId
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


                                                                                            if (slno > 1) {
                                                                                                let id =
                                                                                                    document
                                                                                                        .getElementById(
                                                                                                            "items-val"
                                                                                                        );
                                                                                                let newId =
                                                                                                    parseFloat(
                                                                                                        id
                                                                                                            .value
                                                                                                    ) + 1;
                                                                                                document
                                                                                                    .getElementById(
                                                                                                        "items-val"
                                                                                                    )
                                                                                                    .value =
                                                                                                    newId;

                                                                                            } else {
                                                                                                document
                                                                                                    .getElementById(
                                                                                                        "items-val"
                                                                                                    )
                                                                                                    .value =
                                                                                                    slno;
                                                                                            }

                                                                                            document
                                                                                                .getElementById(
                                                                                                    "qty-val")
                                                                                                .value =
                                                                                                totalQty;

                                                                                            document
                                                                                                .getElementById(
                                                                                                    "gst-val")
                                                                                                .value = onlyGst
                                                                                                    .toFixed(2);
                                                                                            // document.getElementById(
                                                                                            //         "margin-val")
                                                                                            //     .value = marginP.toFixed(2);
                                                                                            document
                                                                                                .getElementById(
                                                                                                    "net-amount"
                                                                                                )
                                                                                                .value =
                                                                                                netAmount
                                                                                                    .toFixed(2);

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


    // minus netAmount
    let gst = document.getElementById("gst-val");
    let finalGst = gst.value - gstPerItem;
    gst.value = finalGst;

    // minus netAmount
    let net = document.getElementById("net-amount");
    let finalAmount = net.value - total;
    net.value = finalAmount;
}


// ========================= Mfd and Expiry Date Setting =========================

const setMonth = (month) => {

    // let expYr = document.getElementById("exp-year");
    // let mfdYr = document.getElementById("mfd-year");
    
    // if(expYr.value < mfdYr.value){

    // }

    if (month.value.length > 2) {
        month.value = '';
    } else {
        if (month.value > 12) {
            month.value = '';
        } else {
            if (month.value.length == 2) {
                document.getElementById("exp-year").focus();
            }
        }
    }
}

const setMfdMonth = (month) => {

    if (month.value > 12) {
        // console.log("Its Over");
        month.value = '';
    }

    if (month.value.length == 2) {
        document.getElementById("mfd-year").focus();
    }
}

function setMfdYear(year) {
    let thisYear = new Date().getFullYear();
    let thisMnth = new Date().getMonth();
    // let yr = year.value;
    let mnthChk = document.getElementById("mfd-month").value;
    // console.log("CHECK YEAR");
    // console.log(thisYear);
    // console.log(yr);
    // console.log("CHECK Month");
    // console.log(mnthChk);

    if (year.value.length == 2) {
        thisYear = thisYear % 100;
        if (year.value > thisYear) {
            document.getElementById("mfd-year").value = "";
            ocument.getElementById("mfd-month").focus();
        }
    }

    if (year.value.length == 4) {
        if (year.value > thisYear) {
            document.getElementById("mfd-year").value = "";
            ocument.getElementById("mfd-month").focus();
        }

        if (year.value == thisYear) {
            if (mnthChk > thisMnth) {
                document.getElementById("mfd-month").value = "";
                document.getElementById("mfd-year").value = "";
                document.getElementById("mfd-month").focus();
            }
        }

        document.getElementById("exp-month").focus();
    }

}

const setYear = (year) => {
    var MFDYR = document.getElementById("mfd-year");
    var mfdMnth = document.getElementById("mfd-month");
    var expMnth = document.getElementById("exp-month");
    // var mfdLn = MFD.value.length;

    // console.log(mfdLn);


    if (year.value.length == 4) {
        if (year.value < MFDYR.value ) {
            document.getElementById("exp-year").value = "";
            document.getElementById("exp-year").focus();
        }

        if (year.value == MFDYR.value) {
            if (mfdMnth.value > expMnth.value) {
                document.getElementById("exp-month").value = "";
                document.getElementById("exp-month").focus();
            }
        }
    }

    if (year.value.length == 2) {
        if(MFDYR.value.length == 4){
            MFDYR = MFDYR.value % 100;
            if(MFDYR < year.value){
                document.getElementById("exp-year").value="";
                document.getElementById("exp-year").focus();
            }
        }
        if(MFDYR.value.length == 2){
            if(year.value < MFDYR.value){
                document.getElementById("exp-year").value="";
                document.getElementById("exp-year").focus();
            }
        }

        if(MFDYR.value.length == year.value.length){
            if (mfdMnth.value > expMnth.value) {
                document.getElementById("exp-month").value = "";
                document.getElementById("exp-month").focus();
            }
        }
    }
}





