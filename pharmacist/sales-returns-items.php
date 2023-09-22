<?php
require_once '_config/sessionCheck.php'; //check admin loggedin or not
require_once "../php_control/doctors.class.php";
require_once '../php_control/products.class.php';
require_once '../php_control/distributor.class.php';
require_once '../php_control/measureOfUnit.class.php';
require_once '../php_control/packagingUnit.class.php';
$page = "sales-returns";


//class Initilization
$Products           = new Products();
$Distributor        = new Distributor();
// $Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
// $StockIn            = new StockIn();
// $CurrentStock       = new CurrentStock();
$PackagingUnits     = new PackagingUnits();


//function's called
// $showStockIn           = $StockIn->showStockIn();
$showProducts          = $Products->showProducts();
$showDistributor       = $Distributor->showDistributor();
// $showManufacturer      = $Manufacturer->showManufacturer();
$showMeasureOfUnits    = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();
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


    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="../css/sweetalert2/sweetalert2.min.css" rel="stylesheet">


    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom CSS  -->
    <link rel="stylesheet" href="css/custom/stock-in.css">
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
                    <h1 class="h3 mb-4 text-gray-800"> Sales Return</h1>

                    <!-- Add Product -->
                    <div class="card shadow mb-5">
                        <div class="card-body">
                            <!--============= select Bill and details =============-->
                            <div class="row">
                                <div class="col-md-2 col-6 mt-3">
                                    <label class="mb-0 mt-2" for="invoice-no">Invoice No.</label>
                                    <input type="number" class="upr-inp" name="invoice-no" id="invoice-no" placeholder="Search Invoice No." onkeyup="getCustomer(this.value)" value="">
                                </div>
                                <div class="col-md-2 col-6 mt-3">
                                    <label class="mb-0 mt-2" for="patient-name">Patient Name.</label>
                                    <input type="text" class="upr-inp" name="patient-name" id="patient-name" placeholder="Select Invoice First." autocomplete="off" readonly>
                                </div>
                                <div class="col-md-2 col-6 mt-3 ">
                                    <label class="mb-0 mt-2" for="bill-date">Bill Date</label>
                                    <input type="text" class="upr-inp" name="" id="bill-date" autocomplete="off" readonly>
                                </div>
                                <div class="col-md-2 col-6 mt-3 ">
                                    <label class="mb-0 mt-2" for="reff-by">Reff By</label>
                                    <input type="text" class="upr-inp" id="reff-by" autocomplete="off" readonly>
                                </div>
                                <div class="col-md-2 col-12 mt-3 ">
                                    <label class="mb-0 mt-2" for="refund-mode">Refund Mode</label>
                                    <select class="upr-inp" id="refund-mode" onchange="getRefundMode(this.value);">
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
                                <div class="col-md-2 col-6 mt-3 ">
                                    <label class="mb-0 mt-2" for="bill-date">Return Date</label>
                                    <input type="date" class="upr-inp" name="" id="select-return-date" onchange="getReturnDate(this.value)" autocomplete="off">
                                </div>
                            </div>
                            <div id="bills-list" class="row mt-1 m-0">

                            </div>
                            <!--============= end select Bill and details =============-->

                            <div class="row">
                                <div class="col-md-8 col-12 mt-3">
                                    <label for="items-list" class="mb-0">Product Name</label>
                                    <select id="items-list" class="upr-inp mt-1" onchange="getItemDetails(this);">
                                        <option value="" selected disabled>Select Invoice Number First</option>
                                    </select>
                                    <!-- <input type="text" class="upr-inp" id="item-name" readonly style="display: none;"> -->
                                </div>
                            </div>

                            <form id="return-item-details">
                                <div class="row">
                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="stock-out-details-item-id">Stock out details item Id</label>
                                        <div class="d-flex date-field">
                                            <input type="text" class="upr-inp" id="stock-out-details-item-id" readonly>
                                        </div>
                                    </div>

                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="pharmacy-invoice-item-details-id">Pharmacy Invoice Item Details Id</label>
                                        <div class="d-flex date-field">
                                            <input type="text" class="upr-inp" id="pharmacy-invoice-item-details-id" readonly>
                                        </div>
                                    </div>

                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="item-id">Item Id</label>
                                        <div class="d-flex date-field">
                                            <input type="text" class="upr-inp" id="item-id" readonly>
                                        </div>
                                    </div>

                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="prod-id">Product Id</label>
                                        <div class="d-flex date-field">
                                            <input type="text" class="upr-inp" id="prod-id" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="exp-date">Expiry</label>
                                        <div class="d-flex date-field">
                                            <input type="text" class="upr-inp" id="exp-date" readonly style="font-size: 0.85rem;">
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="unit"> Unit</label>
                                        <input type="text" class="upr-inp" id="unit" value="" readonly>
                                    </div>

                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="unit"> Item Unit</label>
                                        <input type="text" class="upr-inp" id="item-unit" value="" readonly>
                                    </div>

                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="unit">Item Weatage</label>
                                        <input type="text" class="upr-inp" id="item-weatage" value="" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="batch-no">Batch</label>
                                        <input type="text" class="upr-inp" name="batch-no" id="batch-no" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="mrp">MRP</label>
                                        <input type="text" class="upr-inp" name="mrp" id="mrp" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="purchase-qty">P.Qty</label>
                                        <input type="text" class="upr-inp" name="purchase-qty" id="purchase-qty" readonly>
                                    </div>

                                    <div class=" col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="qty">Cr.Qty</label>
                                        <input type="text" class="upr-inp" name="qty" id="qty" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="discount">Disc% </label>
                                        <input type="text" class="upr-inp" name="discount" id="discount" value="" readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="gst">GST</label>
                                        <input type="text" class="upr-inp" name="gst" id="gst" readonly>
                                    </div>

                                    <div class="d-none col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="taxable">Sales Taxable</label>
                                        <input type="any" class="upr-inp" name="taxable" id="taxable">
                                    </div>
                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="bill-amount">Amount</label>
                                        <input type="any" class="upr-inp" name="bill-amount" id="bill-amount" readonly required>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="return">Return</label>
                                        <input type="number" class="upr-inp" name="return" id="return" onkeyup="getRefund(this.value)" required>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="return">Taxable</label>
                                        <input type="number" class="upr-inp" name="refund-taxable" id="refund-taxable" onkeyup="getRefund(this.value)" required readonly>
                                    </div>

                                    <div class="col-md-1 col-6 mt-3">
                                        <label class="mb-0 mt-1" for="refund">Refund</label>
                                        <input type="any" class="upr-inp" name="refund" id="refund" readonly>
                                    </div>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                        <button type="button" class="btn btn-primary me-md-2" onclick="addData()" id="add-btn">Add<i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!-- /end Add Product  -->
                    <!--=========================== Show Bill Items ===========================-->
                    <div class="card shadow mb-4">
                        <form action="_config\form-submission\sales-return-form.php" method="post">
                            <div class="card-body stock-in-summary">
                                <div class="table-responsive">
                                    <table class="table item-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">
                                                    <input type="number" value="0" id="dynamic-id" style="width: 2rem;" readonly class="d-none">
                                                </th>
                                                <th scope="col">
                                                    <input type="number" value="0" id="serial-control" style="width: 2rem;" readonly class="d-none">
                                                </th>
                                                <th scope="col">Items</th>
                                                <th scope="col" hidden>Ids of tables</th>
                                                <th scope="col" hidden>Item id</th>
                                                <th scope="col" hidden>Prodcut id</th>
                                                <th scope="col">Batch</th>
                                                <th scope="col">Exp.</th>
                                                <th scope="col">Unit</th>
                                                <th scope="col">Cr Qty</th>
                                                <th scope="col">MRP</th>
                                                <th scope="col">Disc</th>
                                                <th scope="col">GST%</th>
                                                <th scope="col">Return</th>
                                                <th scope="col">Taxable</th>
                                                <th scope="col">Refund</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dataBody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="  p-3 m-3  font-weight-bold text-light purchase-items-summary rounded">
                                <div class="row ">
                                    <div class="col-md-3 col-6 mb-3 d-flex justify-content-start">
                                        <label for="invoice">Invoice :</label>
                                        <input class="summary-inp w-60" name="invoice" id="invoice" type="text" readonly required>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3 d-flex justify-content-start">
                                        <label for="">Return Date :</label>
                                        <input class="summary-inp w-60" name="return-date" id="return-date" type="text" readonly required>
                                        <input class="d-none" name="purchased-date" id="purchased-date" type="text">
                                    </div>
                                    <div class="col-md-2 col-6 mb-3  d-flex justify-content-start">
                                        <p>Items : <input class="summary-inp w-60" name="total-items" id="total-items" type="number" value="0" readonly required></p>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>Refund Mode : <input class="summary-inp w-60" name="refund-mode" id="refund-mode-val" type="text" readonly required> </p>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>Qty : <input class="summary-inp" name="total-qty" id="total-qty" type="any" value="0" readonly required> </p>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2 d-flex justify-content-start">
                                        <p>GST : <input class="summary-inp" name="gst-amount" id="gst-amount" type="number" value="0" readonly required> </p>
                                    </div>
                                    <div class="col-md-3 mb-2 col-6 mb-2 d-flex justify-content-start">
                                        <p>Refund : <input class="summary-inp" name="refund-amount" id="refund-amount" type="any" value="0" readonly required> </p>
                                    </div>
                                    <div class="col-md-2 mb-2 col-6 justify-content-end">
                                        <button class="btn btn-sm btn-primary" style="width: 100%;" type="submit" name="sales-return" id="sales-return-btn">Return</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--=========================== Show Bill Items ===========================-->

                </div>
                <!-- /.container-fluid -->

                <!-- Footer -->
                <?php include_once 'partials/footer-text.php'; ?>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
        <!-- Logout Modal-->
        <?php require_once '_config/logoutModal.php'; ?>
        <!--End of Logout Modal-->

        <!-- Bootstrap core JavaScript-->
        <script src="../assets/jquery/jquery.min.js"></script>
        <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="../assets/jquery-easing/jquery.easing.min.js"></script>

        <!-- Include SweetAlert2 JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        <script src="../js/sweetalert2/sweetalert2.all.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>
        <script src="../js/ajax.custom-lib.js"></script>
        <script src="../js/sweetAlert.min.js"></script>
        
        <script src="js/sales-return-item.js"></script>

</body>

</html>