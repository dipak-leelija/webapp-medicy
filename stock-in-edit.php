<?php

require_once __DIR__ . '/config/constant.php';
require_once __DIR__ . '/config/service.const.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'gst.class.php';


$page = "stock-in-details";


//objects Initilization
$Products           = new Products();
$Distributor        = new Distributor();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$StockIn            = new StockIn();
$StockInDetails     = new StockInDetails();
$Gst                = new Gst;
$CurrentStock       = new CurrentStock;


//function's called

$gstData = json_decode($Gst->seletGst());
$gstData = $gstData->data;
// print_r($gstData);


$showProducts          = $Products->showProducts();
$showDistributor       = json_decode($Distributor->showDistributor());
$showDistributors      = $showDistributor->data;

$showMeasureOfUnits    = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();

$edit = FALSE;
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['edit'])) {
        $edit = TRUE;

        $distBill           = $_GET['edit']; // get distributor bill no

        $stockIn_id         = $_GET['editId']; // get stock in id

        //fetching stok in data by stok in id
        $stockInAttribute = 'id';
        $stockIn        = $StockIn->stockInByAttributeByTable($stockInAttribute, $stockIn_id);

        //fetching stok in details data by stok in id and store in stockInDetails variable
        $stockInDetails = $StockInDetails->showStockInDetailsByStokId($stockIn_id);

        //fetching distributo details data by stok in id
        $distData = json_decode($Distributor->showDistributorById($stockIn[0]['distributor_id']), true);
        // $distData = json_decode($distData, true);

        if (isset($distData['status']) && $distData['status'] == '1') {
            $data     = $distData['data'];
            $distName = $data['name'];
        }
        // set distributo name in a variable
        // $distName = $distData[0]->name;


        $stockInDetailsIds = array();
        if (is_array($stockInDetails) && !empty($stockInDetails)) {
            foreach ($stockInDetails as $stockInData) {
                array_push($stockInDetailsIds, $stockInData['id']); // store stok in details id for additional use
            }
        } else {
            echo 'No Data Found';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Items</title>

    <!-- Custom fonts for this template -->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- <link rel="stylesheet" href="../css/font-awesome-6.1.1-pro.css"> -->

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>custom/stock-in.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Add Product -->
                    <div class="card shadow mb-5">
                        <div class="card-body">

                            <!-- Distributor Details  -->
                            <div class="row bg-distributor rounded pt-2 pb-4">

                                <div class="col-sm-6 col-md-3">

                                    <label class="mb-1" for="distributor-id">Distributor</label>
                                    <input type="text" name="" id="distributor-id" class="upr-inp" value="<?= $distName ?>">


                                    <div class="p-2 bg-light col-md-6 c-dropdown" id="distributor-list">
                                        <?php if (!empty($showDistributors)) : ?>

                                            <div class="lists" id="lists">
                                                <?php foreach ($showDistributors as $eachDistributor) { ?>
                                                    <div class="p-1 border-bottom list" id="<?= $eachDistributor->id ?>" onclick="setDistributor(this)">
                                                        <?= $eachDistributor->name ?>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <div class="d-flex flex-column justify-content-center mt-1" data-toggle="modal" data-target="#add-distributor" onclick="addDistributor()">
                                                <button type="button" id="add-customer" class="text-primary border-0">
                                                    <i class="fas fa-plus-circle"></i> Add Now</button>
                                            </div>

                                        <?php else : ?>
                                            <p class="text-center font-weight-bold">Distributor Not Found!</p>
                                            <div class="d-flex flex-column justify-content-center" data-toggle="modal" data-target="#add-distributor" onclick="addDistributor()">
                                                <button type="button" id="add-customer" class="text-primary border-0">
                                                    <i class="fas fa-plus-circle"></i>Add Now</button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>


                                <div class="col-sm-6 col-md-3">
                                    <label class="mb-1" for="distributor-bill">Distributor Bill No.</label>
                                    <input type="text" class="upr-inp" style="text-transform: uppercase;" name="distributor-bill" id="distributor-bill" value="<?= $edit == TRUE ? $stockIn[0]['distributor_bill'] : ''; ?>" onkeyup="setDistBillNo(this)">
                                </div>


                                <div class="col-sm-6 col-md-2">
                                    <label class="mb-1" for="bill-date">Bill Date</label>
                                    <input type="date" class="upr-inp" name="bill-date" id="bill-date" value="<?= $edit == TRUE ? date_format(date_create($stockIn[0]['bill_date']), "Y-m-d") : ''; ?>" onchange="getbillDate(this)">
                                </div>
                                <div class="col-sm-6 col-md-2">
                                    <label class="mb-1" for="due-date">Due Date</label>
                                    <input type="date" class="upr-inp" name="due-date" id="due-date" value="<?= $edit == TRUE ? date_format(date_create($stockIn[0]['due_date']), "Y-m-d") : ''; ?>" onchange="getDueDate(this)">
                                </div>
                                <div class="col-md-2">
                                    <label class="mb-1" for="payment-mode">Payment Mode</label>
                                    <select class="upr-inp" name="payment-mode" id="payment-mode" onchange="setPaymentMode(this)">
                                        <option value="" selected disabled>Select</option>
                                        <option value="Credit" <?php if ($edit == TRUE) {
                                                                    if ($stockIn[0]['payment_mode'] == "Credit") {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>Credit
                                        </option>
                                        <option value="Cash" <?php if ($edit == TRUE) {
                                                                    if ($stockIn[0]['payment_mode'] == "Cash") {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>Cash
                                        </option>
                                        <option value="UPI" <?php if ($edit == TRUE) {
                                                                if ($stockIn[0]['payment_mode'] == "UPI") {
                                                                    echo 'selected';
                                                                }
                                                            } ?>>UPI</option>
                                        <option value="Paypal" <?php if ($edit == TRUE) {
                                                                    if ($stockIn[0]['payment_mode'] == "Paypal") {
                                                                        echo 'selected';
                                                                    }
                                                                } ?>>Paypal</option>
                                        <option value="Bank Transfer" <?php if ($edit == TRUE) {
                                                                            if ($stockIn[0]['payment_mode'] == "Bank Transfer") {
                                                                                echo 'selected';
                                                                            }
                                                                        } ?>>Bank Transfer</option>
                                        <option value="Credit Card" <?php if ($edit == TRUE) {
                                                                        if ($stockIn[0]['payment_mode'] == "Credit Card") {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?>>Credit Card</option>
                                        <option value="Debit Card" <?php if ($edit == TRUE) {
                                                                        if ($stockIn[0]['payment_mode'] == "Debit Card") {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?>>Debit Card</option>
                                        <option value="Net Banking" <?php if ($edit == TRUE) {
                                                                        if ($stockIn[0]['payment_mode'] == "Net Banking") {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?>>Net Banking</option>
                                    </select>
                                </div>
                            </div>

                            <div>

                                <hr class="sidebar-divider">

                                <form id="data-details">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mt-4 mb-2">

                                                <div class="d-none col-md-4 mt-2">
                                                    <label class="mb-0" for="purchase-details-id">Stock In details
                                                        Id</label>
                                                    <input type="text" class="upr-inp" name="purchase-id" id="purchase-id" value="" readonly>
                                                </div>

                                                <div class="d-none col-md-4 mt-2">
                                                    <label class="mb-0" for="product-id">Product Id</label>
                                                    <input class="upr-inp" id="product-id" name="product-id" readonly>
                                                </div>


                                                <div class="col-md-12 ">
                                                    <input class="upr-inp mt-2" list="datalistOptions" id="product-name" name="product-name" placeholder="Search Product" onkeyup="searchItem(this.value);" onkeydown="chekForm()" autocomplete="off">
                                                    
                                                    <div class="p-2 bg-light" id="product-select" style="max-height: 20rem; max-width: 100%;">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-sm-6 col-md-6 mt-2">
                                                    <label class="mb-0" for="mrp">MRP/Package</label>
                                                    <input type="number" class="upr-inp" name="mrp" id="mrp" readonly>
                                                </div>

                                                <div class="col-sm-6 col-md-6 mt-2">
                                                    <label class="mb-0" for="gst">GST%</label>

                                                    <input type="number" class="upr-inp" name="gst-check" id="gst-check" hidden>

                                                    <select class="upr-inp" name="gst" id="gst" onchange="getBillAmount(this)">

                                                        <option value="" selected disabled>Select GST%</option>

                                                        <?php
                                                        foreach ($gstData as $gstData) {
                                                            echo '<option value="' . $gstData->percentage . '" >' . $gstData->percentage . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>


                                                <div class="d-none col-md-12 mt-2">
                                                    <label class="mb-0" for="manufacturer-id">Manufacturer</label>

                                                    <input class="upr-inp" id="manufacturer-id" name="manufacturer-id" value="">
                                                    <input class="upr-inp" id="manufacturer-name" name="manufacturer-name" value="">


                                                </div>
                                            </div>

                                            <div class="d-none row">
                                                <div class="col-md-12 ">
                                                    <div class="row ">

                                                        <div class="col-sm-6 col-md-3 mt-2 ">
                                                            <label class="mb-0" for="weightage">Weightage</label>
                                                            <input type="text" class="upr-inp" id="weightage" value="" readonly>
                                                        </div>

                                                        <div class="col-sm-6 col-md-3 mt-2 ">
                                                            <label class="mb-0" for="unit"> Unit</label>
                                                            <input type="text" class="upr-inp" id="unit" value="" readonly>
                                                        </div>

                                                        <div class="col-sm-6 col-md-3 mt-2 ">
                                                            <label class="mb-0" for="packaging-in">Packaging-in</label>
                                                            <input type="text" class="upr-inp" id="packaging-in" readonly>
                                                        </div>
                                                        <div class="col-sm-6 col-md-3 mt-2 ">
                                                            <label class="mb-0" for="medicine-power">Medicine
                                                                Power</label>
                                                            <input class="upr-inp" type="text" name="medicine-power" id="medicine-power">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-6 mt-2">
                                                    <label class="mb-0" for="batch-no">Batch No.</label>
                                                    <input type="text" class="upr-inp" name="batch-no" id="batch-no" style="text-transform: uppercase;">
                                                </div>

                                                <div class="col-sm-6 mt-2">
                                                    <label class="mb-0 mt-1" for="exp-date">Expiry Date</label>
                                                    <div class="d-flex date-field">
                                                        <input class="month " type="number" id="exp-month" onkeyup="setExpMonth(this);" onfocusout="setexpMonth(this);">
                                                        <span class="date-divider">&#47;</span>
                                                        <input class="year" type="number" id="exp-year" /*onkeyup="setExpYear(this)" */ onfocusout="setExpYEAR(this);">
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/End Quantity Row  -->
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Price Row -->
                                            <div class="row mb-2">

                                                <div class="col-sm-4 col-md-4 mt-2">
                                                    <label class="mb-0" for="purchase-price">PTR/Package</label>
                                                    <input type="text" class="upr-inp" name="ptr" id="ptr" onkeyup="getBillAmount()">
                                                </div>

                                                <div class="col-sm-4 col-md-4 mt-2">
                                                    <label class="mb-0" for="qty">Quantity</label>
                                                    <input type="number" class="upr-inp" name="qty" id="qty" onkeyup="getBillAmount()" onfocusout="qtyCheck(this)">

                                                </div>

                                                <div class="col-sm-4 col-md-4 mt-2">
                                                    <label class="mb-0" for="free-qty">Free</label>
                                                    <input type="number" class="upr-inp" name="free-qty" id="free-qty" onkeyup="editQTY()">
                                                </div>

                                                <div class="d-none col-sm-4 col-md-4 mt-2">
                                                    <label class="mb-0" for="purchsed-qty">Buy Qty</label>
                                                    <input type="number" class="upr-inp" name="purchsed-qty" id="purchsed-qty" readonly>
                                                </div>

                                                <div class="d-none col-sm-4 col-md-4 mt-2">
                                                    <label class="mb-0" for="current-qty">Current Qty</label>
                                                    <input type="number" class="upr-inp" name="current-qty" id="current-qty" readonly>
                                                </div>

                                                <div class="d-none col-sm-4 col-md-4 mt-2">
                                                    <label class="mb-0" for="current-qty">Delete Flag</label>
                                                    <input type="number" class="upr-inp" name="del-flag" id="del-flag" readonly>
                                                </div>

                                                <div class="d-none col-sm-6 col-md-6 mt-2">
                                                    <label class="mb-0" for="purchase-price">Check PTR</label>
                                                    <input type="text" class="upr-inp" name="chk-ptr" id="chk-ptr">
                                                </div>
                                            </div>
                                            <!--/End Price Row -->

                                            <div class="row">
                                                <div class="d-none col-sm-6 col-md-6 mt-2">
                                                    <label class="mb-0" for="packaging-type">Packaging Type</label>
                                                    <select class="upr-inp" name="packaging-type" id="packaging-type">
                                                        <option value="" disabled selected>Select Packaging Type
                                                        </option>
                                                        <?php
                                                        foreach ($showPackagingUnits as $rowPackagingUnits) {
                                                            echo '<option value="' . $rowPackagingUnits['id'] . '">' . $rowPackagingUnits['unit_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>

                                                    <input type="text" class="upr-inp" id="packaging-type-edit" readonly>

                                                    <label class="mb-0" for="free-qty">Updtd qtys</label>
                                                    <input type="number" class="upr-inp" name="updtQTYS" id="updtQTYS">
                                                </div>

                                                <!-- </div> -->
                                                <!--/End Quantity Row  -->

                                                <!-- Price Row -->

                                                <div class="col-sm-4 col-md-4 mt-2">
                                                    <label class="mb-0" for="discount">Discount %</label>
                                                    <input type="number" class="upr-inp" name="discount" id="discount" placeholder="Discount Percentage" value="0" onkeyup="getBillAmount()">
                                                </div>

                                                <div class="d-none col-md-4 mt-2">
                                                    <label class="mb-0" for="discount">Gst Amnt.</label>
                                                    <input type="text" class="upr-inp" name="crntGstAmnt" id="crntGstAmnt" readonly>
                                                </div>

                                                <!--/End Price Row -->

                                                <!-- Quantity Row  -->
                                                <!-- <div class="row"> -->
                                                <div class="col-sm-4 col-md-4 mt-2">
                                                    <label class="mb-0" for="base">Base</label>
                                                    <input type="number" class="upr-inp" name="base" id="base" readonly>
                                                    <!-- <label class="mb-0" for="bill-amount">Updated GST Amount</label>
                                                    <input type="number" class="upr-inp" name="updtGstAmt" id="updtGstAmt"> -->
                                                </div>

                                                <div class="col-md-4 mt-2">
                                                    <label class="mb-0" for="bill-amount">Bill Amount</label>
                                                    <input type="any" class="upr-inp" name="bill-amount" id="bill-amount" readonly required>
                                                </div>

                                                <div class="d-none col-md-4 mt-2">
                                                    <label class="mb-0" for="bill-amount">Prev. Bill Amount</label>
                                                    <input type="any" class="upr-inp" name="temp-bill-amount" id="temp-bill-amount">
                                                </div>
                                            </div>
                                            <!--/End Quantity Row  -->

                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                                <button type="button" class="btn btn-primary me-md-2" id="add-button" onclick="addData()">Add
                                                    <i class="fas fa-plus"></i></button>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                    <button class="btn btn-primary me-md-2" onclick="addData()">Add
                                        <i class="fas fa-plus"></i></button>
                                </div> -->

                            <!-- </form> -->
                        </div>
                    </div>
                    <!-- /end Add Product  -->

                    <!--=========================== Show Bill Items ===========================-->
                    <div class="card shadow mb-4">
                        <form action="_config\form-submission\stock-in-update-form.php" method="post">
                            <div class="card-body stock-in-summary">
                                <div class="table-responsive">


                                    <table class="table item-table" id="item-table" style="width: 100%;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">
                                                    <input class="d-none" id="dynamic-id" type="number" value="<?= $edit == TRUE ? count($stockInDetails) : ''; ?>">
                                                </th>
                                                <th scope="col">
                                                    <input class="d-none" type="number" id="serial-control" value="<?= $edit == TRUE ? count($stockInDetails) : ''; ?>">
                                                </th>
                                                <th scope="col" hidden>StockInDetaislId</th>
                                                <!-- <th scope="col"></th> -->
                                                <th scope="col">Items</th>
                                                <th scope="col">Batch</th>
                                                <!-- <th scope="col">Mfd</th> -->
                                                <th scope="col">Exp</th>
                                                <th scope="col">Unit</th>
                                                <th scope="col">Qty.</th>
                                                <th scope="col">Free</th>
                                                <th scope="col">MRP</th>
                                                <th scope="col">PTR</th>
                                                <th scope="col">GST%</th>
                                                <th scope="col">Disc%</th>
                                                <th scope="col">Margin%</th>
                                                <th scope="col">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dataBody" style="cursor: pointer;">
                                            <?php
                                            if ($edit == TRUE) {
                                                $slno = 0;

                                                // print_r($stockInDetails);
                                                // echo sizeof($stockInDetails);
                                                foreach ($stockInDetails as $detail) {
                                                    // print_r($detail);
                                                    // echo "<br>";
                                                    $detailsId = $detail['id'];

                                                    $slno += 1;

                                                    $prdCheck = json_decode($Products->productExistanceCheck($detail['product_id']));
                                                    if ($prdCheck->status) {
                                                        $editReqFlag = 0;
                                                    } else {
                                                        $editReqFlag = '';
                                                    }

                                                    $product = json_decode($Products->showProductsByIdOnUser($detail['product_id'], $adminId, $editReqFlag));

                                                    $product = $product->data;
                                                    // print_r($product);


                                                    // purchase data 
                                                    if (in_array(strtolower(trim($detail['unit'])), LOOSEUNITS)) {
                                                        $purchaeQty = (intval($detail['qty']) + intval($detail['free_qty'])) * intval($detail['weightage']);
                                                    } else {
                                                        $purchaeQty = intval($detail['qty']) + intval($detail['free_qty']);
                                                    }

                                                    // current stock data 
                                                    $col = 'stock_in_details_id ';
                                                    $currentStockData = $CurrentStock->selectByColAndData($col, $detailsId);

                                                    foreach($currentStockData as $currentData){
                                                        if (in_array(strtolower(trim($currentData['unit'])), LOOSEUNITS)) {
                                                            $currentQty = $currentData['loosely_count'];
                                                        } else {
                                                            $currentQty = $currentData['qty'];
                                                        }
                                                    }

                                                    $delFlag = 1;
                                            ?>
                                                    <tr id="<?php echo 'table-row-' . $slno; ?>">

                                                        <td style="color: red; width:1rem"><i class="fas fa-trash " style="padding-top: .5rem;" onclick="deleteData(<?php echo $slno . ',' . intval($detail['qty']) + intval($detail['free_qty']) . ',' . $detail['gst_amount'] . ',' . $detail['amount'] .','. $purchaeQty .','. $currentQty .','. $delFlag ?>)">
                                                            </i>
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')" style="width: 1rem; padding-top: 1rem"><?php echo $slno ?>
                                                        </td>

                                                        <td class="d-none p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-6r" type="text" name="purchaseId[]" id="purchaseId" value="<?php echo $detail['id'] ?>" readonly>
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-9r" type="text" name="productNm[]" value="<?php echo $product[0]->name ?>" readonly style="text-align: start; font-size: 0.65rem;">
                                                            <input class="d-none col table-data w-9r" type="text" name="productId[]" value="<?php echo $detail['product_id'] ?>" readonly>
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-6r" type="text" name="batchNo[]" value="<?php echo $detail['batch_no'] ?>" readonly style="font-size: 0.65rem; text-align:start;">
                                                        </td>

                                                        <!-- <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-4r" type="text" style="font-size: 0.65rem; text-align:start;" name="mfdDate[]" id="mfdDate" value="<?php echo $detail['mfd_date'] ?>" readonly>
                                                        </td> -->

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-4r" type="text" name="expDate[]" value="<?php echo $detail['exp_date'] ?>" readonly style="text-align:start; font-size: 0.65rem">
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-4r" type="text" name="setof[]" value="<?php echo $detail['weightage'] . ',' . $detail['unit'] ?>" readonly style="text-align:start; font-size: 0.65rem">
                                                            <input class="d-none col table-data w-4r" type="text" name="weightage[]" value="<?php echo $detail['weightage'] ?>">
                                                            <input class="d-none col table-data w-4r" type="text" name="unit[]" value="<?php echo $detail['unit'] ?>">
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')" style="text-align:start;">
                                                            <input class="col table-data w-3r" type="text" name="qty[]" value="<?php echo $detail['qty'] ?>" readonly style="font-size: 0.65rem; text-align: end">
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-3r" type="text" name="freeQty[]" value="<?php echo $detail['free_qty'] ?>" readonly style="font-size: 0.65rem; text-align: end;">
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-4r" type="text" name="mrp[]" value="<?php echo $detail['mrp'] ?>" readonly style="font-size: 0.65rem; text-align: end;">
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-4r" type="text" name="ptr[]" value="<?php echo $detail['ptr'] ?>" readonly style="font-size: 0.65rem; text-align: end;">
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-3r" type="text" name="gst[]" value="<?php echo $detail['gst'] ?>%" readonly style="font-size:0.65rem; text-align:end">
                                                            <input class="d-none col table-data w-3r" name="gstPerItem[]" value="<?php echo $detail['gst_amount'] ?>">
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="d-none col table-data w-4r" type="text" name="base[]" value="<?php echo $detail['base'] ?>" readonly style="font-size:0.65rem">

                                                            <input class="col table-data w-3r" type="text" name="discount[]" value="<?php echo $detail['discount'] ?>%" readonly style="font-size:0.65rem; text-align: end;">

                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-4r" type="text" name="margin[]" value="<?php echo $detail['margin'] ?>" readonly style="font-size:0.65rem;text-align: end;">
                                                        </td>

                                                        <td class="p-0 pt-3" onclick="customClick('<?php echo 'table-row-' . $slno ?>','<?php echo $detail['product_id'] ?>','<?php echo $detail['distributor_bill'] ?>','<?php echo $detail['batch_no'] ?>')">
                                                            <input class="col table-data w-5r" type="text" name="billAmount[]" value="<?php echo $detail['amount'] ?>" readonly style="font-size:0.65rem; text-align:end;">
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="m-3 p-3 pt-3 font-weight-bold text-light purchase-items-summary rounded">
                                <div class="row mb-3">
                                    <div class="col-md-3  d-flex justify-content-start">
                                        <p>Distributor :
                                            <input class="d-none summary-inp" name="prev-distributor-id" id="prev-dist-id" type="text" value="<?= $edit == TRUE ? $stockIn[0]['distributor_id'] : ''; ?>" readonly>
                                            <input class="d-none summary-inp" name="updated-distributor-id" id="updated-dist-id" type="text" value="<?= $edit == TRUE ? $stockIn[0]['distributor_id'] : ''; ?>" readonly>

                                            <input class="summary-inp" name="distributor-name" id="distributor-name" type="text" value="<?= $distName ?>" readonly>
                                        </p>
                                    </div>

                                    <div class="col-md-3 d-flex justify-content-start">
                                        <p>Dist. Bill :
                                            <input class="d-none summary-inp" name="prev-distributor-bill" id="prev-distributor-bill-no" type="text" value="<?= $edit == TRUE ? $stockIn[0]['distributor_bill'] : ''; ?>" readonly>

                                            <input class="summary-inp" name="distributor-bill" id="distributor-bill-no" type="text" value="<?= $edit == TRUE ? $stockIn[0]['distributor_bill'] : ''; ?>" readonly>
                                        </p>
                                    </div>

                                    <div class="col-md-3  d-flex justify-content-start">
                                        <p>Bill Date :
                                            <input class="summary-inp" name="bill-date-val" id="bill-date-val" type="text" value="<?= $edit == TRUE ? $stockIn[0]['bill_date'] : ''; ?>" readonly>
                                        </p>
                                    </div>
                                    <div class="col-md-3  d-flex justify-content-start">
                                        <p>Due Date :
                                            <input class="summary-inp" name="due-date-val" id="due-date-val" type="text" value="<?= $edit == TRUE ? $stockIn[0]['due_date'] : ''; ?>" readonly>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-md-3 d-flex justify-content-start">
                                        <span>Payment :
                                            <input class="summary-inp" name="payment-mode-val" id="payment-mode-val" type="text" value="<?= $edit == TRUE ? $stockIn[0]['payment_mode'] : ''; ?>" readonly>
                                        </span>
                                    </div>

                                    <div class="col-sm-6 col-md-2  d-flex justify-content-start">
                                        <p>Items :
                                            <input class="summary-inp" name="items" id="items-val" type="text" value="<?= $edit == TRUE ? $stockIn[0]['items'] : ''; ?>" readonly>
                                        </p>
                                    </div>
                                    <div class="col-sm-6 col-md-2 d-flex justify-content-start">
                                        <p>Qty :
                                            <input class="summary-inp" name="total-qty" id="qty-val" type="text" value="<?= $edit == TRUE ? $stockIn[0]['total_qty'] : ''; ?>" readonly>
                                        </p>
                                    </div>
                                    <div class="col-sm-6 col-md-2 d-flex justify-content-start">
                                        <p>GST :
                                            <input class="summary-inp" name="totalGst" id="gst-val" type="text" value="<?= $edit == TRUE ? $stockIn[0]['gst'] : '' ?>" readonly>
                                        </p>
                                    </div>
                                    <div class="col-sm-6 col-md-3  d-flex justify-content-start">
                                        <p>Net :
                                            <input class="summary-inp" name="netAmount" id="net-amount" type="text" value="<?= $edit == TRUE ? $stockIn[0]['amount'] : ''; ?>" readonly>
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex  justify-content-end">
                                    <?php
                                    if ($edit == TRUE) {
                                        echo '<button class="btn btn-sm btn-primary" style="width: 8rem;" type="submit"
                                        name="update" id="stockInEdit-update-btn">Update</button>';
                                    } else {
                                        echo '<button class="btn btn-sm btn-primary" style="width: 8rem;" type="submit"
                                        name="stock-in">Save</button>';
                                    }
                                    ?>

                                </div>

                                <!-- <input class="summary-inp" name="stok-in-data-array" id="stok-in-data-array" type="text" value="<?php // print_r($stockInDetailsIds) ?>" hidden> -->

                                <input class="summary-inp" name="stok-in-id" id="stok-in-id" type="number" value="<?php echo $stockIn_id ?>" hidden>
                            </div>

                        </form>
                    </div>
                    <!--=========================== Show Bill Items ===========================-->


                </div>
                <!-- /.container-fluid -->
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->
    </div>

    <!-- Distributor Add Modal -->
    <div class="modal fade" id="add-distributor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Distributor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body add-distributor">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
                <div id="url">
                </div>
            </div>
        </div>
    </div>
    <!--/end Distributor Add Modal -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- custom script for stock in edit page  -->
    <script src="<?= JS_PATH ?>stock-in-edit.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>


</body>


</html>