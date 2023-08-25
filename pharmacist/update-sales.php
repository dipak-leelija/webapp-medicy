<?php
require_once '_config/sessionCheck.php';
require_once "../php_control/doctors.class.php";
require_once '../php_control/stockOut.class.php';
require_once '../php_control/products.class.php';
require_once '../php_control/manufacturer.class.php';
require_once "../php_control/patients.class.php";


$page = "Product Management";
$Doctors    = new Doctors();
$StockOut   = new StockOut();
$Products   = new Products();
$Patients = new Patients();
$Manufacturer = new Manufacturer();



if ($_GET['id']) {
    // echo $_GET['id'];
    $stockOut = $StockOut->stockOutDisplayById($_GET['id']);
    print_r($stockOut);
    echo "<br><br>";

    $invoiceId      = $stockOut[0]['invoice_id'];
    $patientId   = $stockOut[0]['customer_id'];

    $patientName = $patientId;

    // echo $patientId; exit;
    if ($patientId != 'Cash Sales') {
        $patientName = $Patients->patientsDisplayByPId($patientId);
        // print_r($patientName);
        $patientName = $patientName[0]['name'];
    }

    // $patientName = $Patients->patientsDisplayByPId($patientId);

    $reffby         = $stockOut[0]['reff_by'];
    $items          = $stockOut[0]['items'];
    $temQtys        = $stockOut[0]['qty'];
    $totalMrp       = $stockOut[0]['mrp'];
    // $ = $stockOut[0]['disc'];	
    $totalGSt       = $stockOut[0]['gst'];
    $billAmout      = $stockOut[0]['amount'];
    $pMode          = $stockOut[0]['payment_mode'];
    // $ = $stockOut[0]['status'];	
    $billdate       = $stockOut[0]['bill_date'];
    // $ = $stockOut[0]['added_by'];
    // $ = $stockOut[0]['added_on'];

    $details = $StockOut->stockOutDetailsById($_GET['id']);
    print_r($details);
    echo "<br><br>";
    $stockOutDetails = $StockOut->stockOutDetailsDisplayById($_GET['id']);
    print_r($stockOutDetails);

    //=============== doctor data =================
    $doctor = $Doctors->showDoctors();
    // print_r($doctor);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>SB Admin 2 - Blank</title>

        <!-- Custom fonts for this template-->
        <link href="../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.css" rel="stylesheet">
        <!-- <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css"> -->

        <!-- Custom CSS  -->
        <link rel="stylesheet" href="css/update-sales.css">

    </head>

    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            <!-- sidebar -->
            <?php include 'partials/sidebar.php'; ?>
            <!-- end sidebar -->
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <!-- Topbar -->
                    <?php include 'partials/topbar.php'; ?>
                    <!-- End of Topbar -->
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <!-- Page Heading -->
                        <!-- <h1 class="h3 mb-4 text-gray-800">Sell Items</h1> -->
                        <!-- Add Product -->
                        <!-- mb-md-5 -->
                        <div class="card ">
                            <div class="card-body fisrt-card-body">
                                <div class="bill-head p-3 text-light rounded">
                                    <div class="row ">
                                        <div class="col-md-3   b-right date">
                                            <div class="row mt-3 mb-3">
                                                <div class="col-md-3 col-2  circle-bg text-light" onclick="datePick();">
                                                    <i class="fas fa-calendar"></i>
                                                </div>
                                                <div class="col-md-9 col-10 ">
                                                    <label for="">Bill Date</label><br>
                                                    <input type="date" class="bill-date" id="bill-dt" value="<?php echo $billdate; ?>" onchange="getDate(this.value)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4  b-right customer">
                                            <div class="row mt-3">
                                                <div class="col-md-2 col-2 circle-bg text-light">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="col-md-6 col-6">
                                                    <label for="">Customer</label><br>
                                                    <input type="text" class="customer-search" id="customer" placeholder="Customer Name/Mobile" value="<?php echo $patientName; ?>" onkeyup="getCustomer(this.value)">

                                                    <div id="customer-list"></div>
                                                </div>
                                                <div class="col-md-4 col-4" onclick="counterBill()">
                                                    <div class="rounded counter-bill">
                                                        Counter Bill <i class="fas fa-plus-circle"></i></div>
                                                    <div class="contact-box">
                                                        <span id="contact"></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-3  b-right doctor">
                                            <div class="row mt-3">
                                                <div class="col-md-3 col-2 circle-bg text-light ">
                                                    <i class="fas fa-stethoscope"></i>
                                                </div>
                                                <div class="col-md-9 col-10">
                                                    <label for="">Doctor</label><br>
                                                    <select class="doctor-select" id="doctor-select">
                                                        <option value="<?php echo $reffby; ?>" selected disabled><?php echo $reffby; ?></option>
                                                        <?php
                                                        foreach ($doctor as $doc) {
                                                            // print_r($row);
                                                            echo $doctorName = $doc['doctor_name'];
                                                            echo '<option value="' . $doctorName . '" style="color: black;">' . $doctorName . '</option>';
                                                        }
                                                        ?>
                                                    </select>

                                                </div>
                                            </div>



                                        </div>
                                        <div class="col-md-2 payment">
                                            <div class="row mt-3">
                                                <div class=" col-md-2 col-2 payment-icon circle-bg">
                                                    <i class="fas fa-stethoscope"></i>
                                                </div>
                                                <div class="col-md-10 col-10 payment-option">
                                                    <label for="">Payment Mode</label><br>
                                                    <select class="payment-mode" id="payment-mode" onchange="getPaymentMode(this.value)">

                                                        <option value="Cash" <?php if ($pMode == "Cash") {
                                                                                    echo "selected";
                                                                                } ?>> Cash</option>
                                                        <option value="Credit" <?php if ($pMode == "Credit") {
                                                                                    echo "selected";
                                                                                } ?>> Credit
                                                        </option>
                                                        <option value="UPI" <?php if ($pMode == "UPI") {
                                                                                echo "selected";
                                                                            } ?>>
                                                            UPI</option>
                                                        <option value="Card" <?php if ($pMode == "Card") {
                                                                                    echo "selected";
                                                                                } ?>> Card</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <form id='sales-edit-form'>
                                    <div class="row ">

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Edit row</label><br>
                                            <input class="sale-inp" type="text" id="edit-row" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Invoice Number</label><br>
                                            <input class="sale-inp" type="text" id="invoice-id" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Pharmacy data Id</label><br>
                                            <input class="sale-inp" type="text" id="pharmacy-data-id" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Stock Out Details Id</label><br>
                                            <input class="sale-inp" type="text" id="stock-out-details-id" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Item Id</label><br>
                                            <input class="sale-inp" type="text" id="item-id" readonly>
                                        </div>

                                        <div class="col-md-3 mt-3 col-12">
                                            <label for="">Item Name</label><br>
                                            <input type="any" id="product-id">
                                            <input type="text" id="search-Item" class="sale-inp-item" onkeyup="searchItem(this.value)">
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Batch</label><br>
                                            <input class="sale-inp" type="text" id="batch-no" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Unit/Pack</label><br>
                                            <input class="sale-inp" type="text" id="weightage" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Item Weatage</label><br>
                                            <input class="sale-inp" type="text" id="item-weightage" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Item Unit</label><br>
                                            <input class="sale-inp" type="text" id="item-unit-type" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Expiry</label><br>
                                            <input class="sale-inp" type="text" id="exp-date" readonly>

                                        </div>
                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">MRP</label><br>
                                            <input class="sale-inp" type="text" id="mrp" readonly>
                                        </div>

                                        <div class=" col-md-1 mt-3 col-6">
                                            <!-- Available qty on batch no -->
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Availability</label><br>
                                            <input class="sale-inp" type="text" id="aqty">
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Qty.</label><br>
                                            <input class="sale-inp" type="number" id="qty" onkeyup="onQty(this.value)">
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Typ Chk.</label><br>
                                            <input class="sale-inp" type="text" id="type-check" disabled>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">Disc%</label><br>
                                            <input class="sale-inp" type="any" id="disc" onkeyup="ondDisc(this.value)">

                                        </div>
                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">D.Price</label><br>
                                            <input class="sale-inp" type="any" id="dPrice" readonly>

                                        </div>
                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="">GST%</label><br>
                                            <input class="sale-inp" type="text" id="gst" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-6">
                                            <label for="" style="font-size: 0.96rem; font-weight: bold;">Taxable</label><br>
                                            <input class="sale-inp" type="text" id="taxable" readonly>
                                        </div>

                                        <div class="col-md-1 mt-3 col-12">
                                            <label for="">Amount</label><br>
                                            <input class="sale-inp" type="any" id="amount" readonly>

                                        </div>

                                    </div>

                                    <div class="d-flex col-md-12">
                                        <div class="p-2 bg-light col-md-6" id="searched-items" style="max-height: 15rem; max-width: 100%; overflow: auto; transition: 30ms; box-shadow: 0 5px 8px rgb(0 0 6 / 28%); display: none;">
                                        </div>

                                        <div class="p-2 bg-light col-md-3" id="select-batch" style="max-height: 7rem; max-width: 30rem; margin-left: 19rem; overflow: auto; display: none; transition: 30ms; box-shadow: 0 5px 8px rgb(0 0 6 / 28%);">
                                        </div>
                                    </div>



                                    <div id="exta-details">
                                        <div class=" row mt-4">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12 col-12 d-flex">
                                                        <label for="">Manf:</label><br>
                                                        <input class=" sale-inp" type="any" id="manuf" style="border-width: 0px;" readonly>
                                                        <input class="sale-inp" type="any" id="manufName" style="border-width: 0px; width:30rem; margin-top: -.6rem; word-wrap: break-word;" readonly>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 d-flex" style="word-wrap: break-word;">
                                                        <label for="" style="margin-top: 5px;">Content:</label>
                                                        <input class="sale-inp" type="textarea" id="productComposition" style="border-width: 0px;  width: 30rem; word-wrap: break-word;" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="row mt-3">
                                                    <div class="col-md-4 col-6 mb-4 d-flex">
                                                        <label for="">Loose Stock:</label>
                                                        <input class="sale-inp" type="any" id="loose-stock" style="border-width: 0px;" readonly>
                                                    </div>
                                                    <div class="col-md-4 col-6 mb-4 d-flex">
                                                        <label for="">Loose Price:</label>
                                                        <input class="sale-inp" type="any" id="loose-price" style="border-width: 0px;" readonly>
                                                    </div>

                                                    <div class="col-md-4 col-6 mb-4 d-flex">
                                                        <label for="" style="margin-top: 6px;">PTR:</label>
                                                        <input class="sale-inp" type="any" id="ptr" style="border-width: 0px;" readonly>
                                                    </div>

                                                    <div class="col-md-4 col-6 mb-4 d-flex">
                                                        <label for="" style="margin-top: 6px;">Margin:</label>
                                                        <input class="sale-inp" type="any" id="margin" style="border-width: 0px;" readonly>
                                                    </div>

                                                    <div class="col-md-4 col-6 mb-4 d-flex justify-content-end">
                                                        <button type='button' class="btn btn-sm btn-primary w-100" onclick="addSummary()"><i class="fas fa-check-circle"></i>Add</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </form>




                            </div>
                            <!-- /end Add Product  -->

                            <!-- card mt-md-5 -->
                            <div class=" mb-4  summary">
                                <div class="card-body fisrt-card-body">
                                    <form action="_config/form-submission/new-sell-edit.php" method="post">
                                        <div>
                                            <div class="table-responsive">
                                                <table class="table item-table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col"><input type="number" value="<?php echo $stockOut[0]['items']; ?>" id="dynamic-id" style="width: 2rem;"></th>
                                                            <th scope="col"></th>
                                                            <th scope="col">Item Name</th>
                                                            <th scope="col" hidden>Item Id</th>
                                                            <th scope="col">Phamacy data id</th>
                                                            <th scope="col">Batch</th>
                                                            <th scope="col">Unit/Pack</th>
                                                            <th scope="col">Expiry</th>
                                                            <th scope="col">MRP</th>
                                                            <th scope="col">Disc%</th>
                                                            <th scope="col">GST%</th>
                                                            <th scope="col">Qty.</th>
                                                            <th scope="col">Taxable</th>
                                                            <th scope="col">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="item-body">
                                                        <?php
                                                        $slno = 0;
                                                        foreach ($details as $detail) {
                                                            
                                                            $mrp = $detail['mrp'];
                                                            $itemUnit = preg_replace('/[0-9]/', '', $detail['weatage']);
                                                            $itemWeatage = preg_replace('/[a-z]/', '', $detail['weatage']);
                                                            //========================
                                                            if ($itemUnit == 'tab' || $itemUnit == 'cap') {
                                                                $qty = $detail['loosely_count'];
                                                                $MRP = floatval($mrp) / intval($itemWeatage);
                                                                $billAmountPerItem = floatval($MRP) * intval($qty);
                                                                
                                                                if((intval($qty) % intval($itemWeatage)) == 0){
                                                                    $qtyType = 'Pack';
                                                                }else{
                                                                    $qtyType = 'Loose';
                                                                }
                                                                
                                                            } else {
                                                                $qty = $detail['qty'];
                                                                $billAmountPerItem = floatval($mrp) * intval($qty);

                                                                $qtyType = '';
                                                            }
                                                            //=======================
                                                            $productData = $Products->showProductsById($stockOutDetails[0]['product_id']);
                                                            $manufId = $productData[0]['manufacturer_id'];
                                                            //=======================

                                                            $slno = $slno + 1;

                                                        ?>

                                                            <tr id="table-row-<?php echo $slno; ?>">
                                                                <td style="color: red;">
                                                                    <i class="fas fa-trash" onclick="deleteItem(<?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)"></i>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="sl-no[]" value="<?php echo $slno; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-product" type="text" name="product-name[]" value="<?php echo $detail['item_name']; ?>" readonly>

                                                                    <input type="text" style="width: 4rem;" name="item-id[]" value="<?php echo $detail['item_id']; ?>">

                                                                    <input type="text" name="product-id[]" value="<?php echo $stockOutDetails[0]['product_id']; ?>">

                                                                    <input type="text" name="product-id[]" value="<?php echo $manufId; ?>">

                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-product" type="text" name="pharmacy-data-id[]" value="<?php echo $detail['id']; ?>" readonly>

                                                                    <input class="summary-product" type="text" name="stockOut-details-id[]" value="" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="batch-no[]" value="<?php echo $detail['batch_no']; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="weightage[]" value="<?php echo $detail['weatage']; ?>" readonly>

                                                                    <!-- <input class="summary-items" type="text" name="weightage[]" value="<?php echo $detail['weatage']; ?>" readonly>

                                                                    <input class="summary-items" type="text" name="weightage[]" value="<?php echo $detail['weatage']; ?>" readonly> -->
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="exp-date[]" value="<?php echo $detail['exp_date']; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="mrp[]" value="<?php echo $mrp; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="disc[]" value="<?php echo $detail['disc']; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="gst[]" value="<?php echo $detail['gst']; ?>" readonly>

                                                                    <input type="text" style="width: 3rem;" name="netGst[]" value="<?php echo $detail['gst_amount']; ?>">
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="qty[]" value="<?php echo $qty; ?>" readonly>
                                                                    
                                                                    <input class="summary-items" type="text" name="qty-type[]" value="<?php echo $qtyType; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="taxable[]" value="<?php echo $detail['taxable']; ?>" readonly>
                                                                </td>

                                                                <td onclick="editItem(<?php echo $detail['item_id']; ?>, <?php echo $slno ?>, <?php echo $qty ?>, <?php echo $detail['gst_amount'] ?>, <?php echo $billAmountPerItem ?>, <?php echo $detail['amount'] ?>)">
                                                                    <input class="summary-items" type="text" name="amount[]" value="<?php echo $detail['amount']; ?>" readonly>
                                                                </td>
                                                            </tr>

                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>

                                            </div>

                                            <div class="listed-sumary p-3 text-light rounded">
                                                <div class="row mb-3">
                                                    <div class="col-md-2 col-6 mb-3 d-flex">
                                                        Items: <input class="sumary-inp" id="items" value="<?php echo $items; ?>" type="text" name="total-items">
                                                    </div>


                                                    <div class="col-md-2 col-6  mb-3 d-flex">
                                                        Quantity: <input class="sumary-inp" id="final-qty" value="<?php echo $temQtys; ?>" type="text" name="total-qty">
                                                    </div>
                                                    <div class="col-md-2 col-6  mb-3 d-flex">
                                                        GST: <input class="sumary-inp" id="total-gst" value="<?php echo $totalGSt; ?>" type="text" name="total-gst">
                                                    </div>
                                                    <div class="col-md-3 col-6  mb-3 d-flex">
                                                        Total: <input class="sumary-inp" id="total-price" value="<?php echo $totalMrp; ?>" type="text" name="total-mrp">
                                                    </div>

                                                    <div class="col-md-3 d-flex">
                                                        Payable: <input class="sumary-inp" id="payable" value="<?php echo $billAmout; ?>" type="any" name="bill-amount">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-2 col-6  mb-3 b-right d-flex">
                                                        <span>
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                        <input class="sumary-inp" id="final-bill-date" type="text" name="bill-date" value="<?php echo $billdate; ?>">
                                                    </div>

                                                    <div class="col-md-3 col-6  mb-3 b-right d-flex">
                                                        <span>
                                                            <i class="fas fa-user"></i>
                                                        </span>
                                                        <input class="sumary-inp" type="text" id="customer-name" name="customer-name" value="<?php echo $patientName; ?>" readonly>

                                                        <input class="" type="text" id="customer-id" name="customer-id" value="<?php echo $patientId; ?>">

                                                        <input class="" type="text" id="invoice-id" name="invoice-id" value="<?php echo $invoiceId; ?>">

                                                    </div>

                                                    <div class="col-md-3 col-8  mb-3 b-right d-flex">
                                                        <span>
                                                            <i class="fas fa-stethoscope"></i>
                                                        </span>
                                                        <input class="sumary-inp" type="text" id="final-doctor-name" name="final-doctor-name" value="<?php echo $reffby; ?>" readonly>
                                                    </div>
                                                    
                                                    <div class="  col-md-2 col-4  mb-3 b-right d-flex">
                                                        <span>
                                                            <i class="fas fa-wallet"></i>
                                                        </span>
                                                        <input class="sumary-inp" type="text" id="final-payment" name="payment-mode" value="<?php echo $pMode; ?>" readonly>
                                                    </div>

                                                    <div class="col-md-2  mb-3">
                                                        <div class="d-md-flex justify-content-end">
                                                            <button type="submit" name="update" class="btn btn-sm btn-primary w-100">Update Bill</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include_once 'partials/footer-text.php'; ?>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->


        <!--============= Add New Customer Modal =============-->
        <!-- Modal -->
        <div class="modal fade" id="add-customer-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body add-customer-modal">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--============= End Add New Customer Modal =============-->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <?php require_once '_config/logoutModal.php'; ?>
        <!-- End Logout Modal-->

        <!-- Bootstrap core JavaScript-->
        <script src="../assets/jquery/jquery.min.js"></script>
        <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <script src="../js/sweetAlert.min.js"></script>
        <!-- Core plugin JavaScript-->
        <!-- <script src="../assets/jquery-easing/jquery.easing.min.js"></script> -->

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>
        <!-- <script src="../js/ajax.custom-lib.js"></script> -->
        <!-- <script src="../js/sweetAlert.min.js"></script> -->
        <script src="js/update-sales.js"></script>



        <script>
            const datePick = () => {
                console.log("Clicked");
                document.getElementById("bill-date").focus();
            }
        </script>

    </body>


    </html>

<?php
}
?>