<?php
$page = "add-new-product";

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once ROOT_DIR . '_config/accessPermission.php';


//objects Initilization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();

$showManufacturer   = $Manufacturer->showManufacturerWithLimit();
$showManufacturer = json_decode($showManufacturer);
// print_r($showManufacturer);
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
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

    <title>Add Items</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Fontawsome Link -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>font-awesome.css">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!--Custom CSS -->
    <!-- <link href="css/add-products.css" rel="stylesheet"> -->
    <link href="<?php echo CSS_PATH ?>add-new-product.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

    <!-- css path for bootstrap 5-->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>choices.min.css">

    <link href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" rel="stylesheet" />

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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800"> Add New Product</h1>

                    <!-- Add Product -->
                    <div class="card shadow mb-4" style="min-height: 70vh;">
                        <div class="card-body">
                            <form action="_config\form-submission\add-new-product.php" enctype="multipart/form-data" method="post" id="add-new-product-details">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="col-12">
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="product-name">Enter Prodcut Name</label>
                                                <input class="c-inp w-100 p-1" id="product-name" name="product-name" placeholder="Product Name" required>
                                            </div><br>
                                            
                                            <!-- Price Row -->
                                            <div class="row p-3">

                                                <div class="col-12 col-sm-6 col-md-3 mt-3">
                                                    <input class="c-inp w-100 p-1" type="text" name="medicine-power" id="medicine-power" placeholder="Enter med Power" required>
                                                </div>

                                                <div class="col-12 col-sm-6 col-md-3 mt-3">
                                                    <!-- <label class="mb-0 mt-1" for="unit-quantity">Unit Quantity</label> -->
                                                    <input type="number" class="c-inp p-1 w-100" name="unit-quantity" id="unit-quantity" placeholder="Enter Unit" step="0.01" required>
                                                </div>

                                                <div class="col-12 col-sm-6 col-md-3 mt-3">
                                                    <!-- <label class="mb-0 mt-1" for="unit">Select Unit</label> -->
                                                    <select class="c-inp p-1 w-100" name="unit" id="unit" required>
                                                        <option value="" disabled selected>Select Unit</option>
                                                        <?php
                                                        foreach ($showMeasureOfUnits as $rowUnit) {

                                                            echo '<option value="' . $rowUnit['id'] . '">' . $rowUnit['short_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mt-3">
                                                    <!-- <label class="mb-0 mt-1" for="packaging-unit">Packaging Type</label> -->
                                                    <select class="c-inp p-1 w-100" name="packaging-type" id="packaging-type" required>
                                                        <option value="" disabled selected>Packaging Unit</option>
                                                        <?php
                                                        foreach ($showPackagingUnits as $rowPackagingUnits) {
                                                            echo '<option value="' . $rowPackagingUnits['id'] . '">' . $rowPackagingUnits['unit_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/End Price Row -->

                                            <!-- Price Row -->
                                            <div class="row p-3">
                                                <div class="col-12 col-sm-6 col-md-6 mt-3">
                                                    <!-- <label class="mb-0 mt-1" for="mrp">MRP ₹</label> -->
                                                    <input type="number" class="c-inp w-100 p-1" name="mrp" id="mrp" placeholder="Enter MRP" step="0.01" required>
                                                </div>

                                                <div class="col-12 col-sm-6 col-md-6 mt-3">
                                                    <!-- <label class="mb-0 mt-1" for="gst">GST %</label> -->
                                                    <select class="c-inp w-100 p-1" name="gst" id="gst">
                                                        <option value="" disabled selected>GST%</option>
                                                        <option value="0">0</option>
                                                        <option value="5">5</option>
                                                        <option value="12">12</option>
                                                        <option value="18">18</option>
                                                        <option value="28">28</option>
                                                    </select>

                                                </div>

                                            </div>
                                            <!--/End Price Row -->

                                            <div class="col-md-12 mt-3">
                                                <!-- <label for="product-descreption">Product Description</label> -->
                                                <textarea class="form-control" name="product-descreption" id="product-descreption" cols="30" rows="3" placeholder="Product Description" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /end Add Product  -->
                </div>
                <!-- /.container-fluid -->
                <!-- End of Main Content -->
            </div>
            <!-- End of Content Wrapper -->

            <!-- Footer -->
            <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Page Wrapper -->

       


        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>



        <!-- Bootstrap core JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <!-- <script src="<?php echo JS_PATH ?>>bootstrap-js-5/bootstrap.bundle.min.js"></script> -->
        <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>
        <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
        <!-- <script src="<?php echo JS_PATH ?>custom/add-products.js"></script> -->

        <!-- Sweet Alert Js  -->
        <script src="<?php echo JS_PATH ?>sweetAlert.min.js"></script>


</body>

</html>