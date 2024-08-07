<?php

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'gst.class.php';


//objects Initilization
$Products           = new Products();
$Distributor        = new Distributor();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$Gst                = new Gst;

//function's called
$showProducts          = $Products->showProducts();
$showDistributor       = json_decode($Distributor->showDistributor());
$showDistributors      = $showDistributor->data;

$gstData = json_decode($Gst->seletGst());
$gstData = $gstData->data;

$showMeasureOfUnits    = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits    = $PackagingUnits->showPackagingUnits();

$edit = FALSE;
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once CLASS_DIR . 'stockIn.class.php';
    require_once CLASS_DIR . 'stockInDetails.class.php';

    if (isset($_GET['edit'])) {
        $edit = TRUE;

        $distBill           = $_GET['edit'];

        $StockIn            = new StockIn();
        $StockInDetails     = new StockInDetails();

        $stockIn        = $StockIn->showStockInById($distBill);
        $details = $StockInDetails->showStockInDetailsById($distBill);
    }
}

$todayYr = date("y");
// echo $todayYr;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Purchase - <?= $HEALTHCARENAME ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css"/>
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css" type="text/css"/>
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/stock-in.css" type="text/css"/>
    <!-- <link href="<?= PLUGIN_PATH ?>font-asesome-5/font-awesome-5.15.4-all.min.css" rel="stylesheet" type="text/css"> -->
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
                    <div class="row" style="z-index: 999;">
                        <div class="col-12">
                            <?php include ROOT_COMPONENT . "drugPermitDataAlert.php"; ?>
                        </div>
                    </div>
                    <!-- Add Product -->
                    <div class="card shadow mb-5">
                        <div class="card-body">

                            <!-- Distributor Details  -->
                            <div class="row bg-distributor rounded pt-2 pb-4">

                                <div class="col-sm-6 col-md-3">

                                    <label class="mb-1" for="distributor-id">Distributor</label>
                                    <input type="text" name="" id="distributor-id" class="upr-inp">


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
                                                <button type="button" id="add-distributor" class="text-primary border-0">
                                                    <i class="fas fa-plus-circle"></i>Add Now</button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="mb-1" for="dist-bill-no">Distributor Bill No.</label>
                                    <input type="text" class="text-uppercase upr-inp " name="dist-bill-no" id="dist-bill-no" value="" autocomplete="off" onkeyup="setDistBillNo(this)">
                                </div>

                                <div class="col-sm-6 col-md-2">
                                    <label class="mb-1" for="bill-date">Bill Date</label>
                                    <input type="date" class="text-uppercase upr-inp" name="bill-date" id="bill-date" onchange="getbillDate(this)">
                                </div>

                                <div class="col-sm-6 col-md-2">
                                    <label class="mb-1" for="due-date">Due Date</label>
                                    <input type="date" class="text-uppercase upr-inp" name="due-date" id="due-date" /*onchange="getDueDate(this)*/">
                                </div>

                                <div class="col-sm-6 col-md-2">
                                    <label class="mb-1" for="payment-mode">Payment Mode</label>
                                    <select class="upr-inp" name="payment-mode" id="payment-mode" onchange="setPaymentMode(this)">
                                        <option value="" selected disabled>Select</option>
                                        <option value="Credit">Credit</option>
                                        <option value="Cash">Cash</option>
                                        <option value="UPI">UPI</option>
                                        <option value="Paypal">Paypal</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Debit Card">Debit Card</option>
                                        <option value="Net Banking">Net Banking</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Distributor Details  -->


                            <!-- <div class="h-divider"></div> -->
                            <hr class="sidebar-divider">

                            <div class="row">
                                <?php require_once  ROOT_COMPONENT . "purchase-product-fields.php" ?>
                            </div>

                            <!-- /end Add Product  -->
                            <br>
                            <!--=========================== Show Bill Items ===========================-->
                            <div class="card shadow mb-4">

                                <?php require_once  ROOT_COMPONENT . "purchase-summary.php" ?>
                            </div>
                            <!--=========================== Show Bill Items ===========================-->

                        </div>
                        <!-- /.Card -->
                    </div>
                    <!-- /.Card End -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

    <!-- Distributor Add Modal -->
    <div class="modal fade" id="add-distributor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" /*onload="captureCurrentLocation()" * />
                <h5 class="modal-title" id="exampleModalLabel">Add Distributor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body add-distributor">
                <!-- Details Appeare Here by Ajax  -->
            </div>
        </div>
    </div>
    </div>
    <!--/end Distributor Add Modal -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?= JS_PATH ?>stock-in.js"></script>

</body>

</html>