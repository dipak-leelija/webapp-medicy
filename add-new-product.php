<?php
$page = "add-new-product";

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
// require_once CLASS_DIR . 'productCategory.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';
require_once CLASS_DIR . 'gst.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';



//objects Initilization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
// $ProductCategory    = new ProductCategory;
$ItemUnit           = new ItemUnit;
$Gst                = new Gst;
$ItemUnit           = new ItemUnit;


$showManufacturer   = $Manufacturer->showManufacturerWithLimit();
$showManufacturer   = json_decode($showManufacturer);
// print_r($showManufacturer);
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
$packagingUnits     = $PackagingUnits->showPackagingUnits();
$prodCategory       = json_decode($Products->productCategory());
$itemUnists         = $ItemUnit->showItemUnits();
// print_r($itemUnists);
$gstData            = json_decode($Gst->seletGst());
$gstData            = $gstData->data;
$packagingUnitData  = $PackagingUnits->showPackagingUnits();
$unitData           = $ItemUnit->showItemUnits();


$allowedPackegingUnits = ["strip", "bottle", "tube", "box", "sachet", "packet", "jar", "kit", "bag", "vial", "ampoule", "respules", "cartridge"];

$allowedItemUnits = ["tablet", "tablets", "syrup", "capsules", "capsule", "soflets", "soflet", "lozenges", "bolus"];

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
    <!-- <link href="<?php echo CSS_PATH ?>custom/add-products.css" rel="stylesheet"> -->
    <link href="<?php echo CSS_PATH ?>add-new-product.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">


    <!-- css path for bootstrap 5-->
    <!-- <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.min.css"> -->
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
                    <div class="d-flex justify-content-center">
                        <div class="card-body shadow mb-4 col-12 d-flex flex-wrap" style="min-height: 70vh; max-width: 150vh;">
                            <!-- <div class="card-body col-12 d-flex flex-wrap"> -->
                            <form action="_config\form-submission\add-new-product.php" enctype="multipart/form-data" method="post" id="add-new-product-details">
                                <div class="row justify-content-center">
                                    <h4 class="h4 mt-4"> Add New Product</h4>
                                </div>

                                <div class="row flex-wrap-reverse">
                                    <div class="col-md-6" id="first-div">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label for="product-name">Product Name</label>
                                                <span class="text-danger">*</span>
                                                <input class="c-inp w-100 p-1" id="product-name" name="product-name" required autocomplete="off">
                                            </div>
                                        </div>

                                        <!-- product category row -->
                                        <div class="row mt-2">
                                            <div class="col-sm-6 mt-2">
                                                <label for="product-catagory">Prodcut Catagory</label>
                                                <span class="text-danger">*</span>
                                                <select class="c-inp p-1 w-100  " name="product-catagory" id="product-catagory" required>
                                                    <option value="" disabled selected>Select</option>
                                                    <?php
                                                    if ($prodCategory->status == 1 && is_array($prodCategory->data)) {
                                                        $prodCategory = $prodCategory->data;

                                                        foreach ($prodCategory as $category) {
                                                            echo '<option value="' . $category->id . '">' . $category->name . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-sm-6 mt-2">
                                                <label for="product-catagory">Packeging In</label>
                                                <span class="text-danger">*</span>
                                                <select class="c-inp p-1 w-100  " name="packeging-type" id="packeging-type" required>
                                                    <option value="" disabled selected>Select</option>
                                                    <?php
                                                    foreach ($packagingUnits as $eachPackUnit) {

                                                        if (in_array(strtolower($eachPackUnit['unit_name']), $allowedPackegingUnits)) {
                                                            echo "<option value='{$eachPackUnit['id']}'>{$eachPackUnit['unit_name']}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>


                                        <!-- catagory - packging - power and unit row  -->
                                        <div class="row mt-2">
                                            <div class="col-sm-6 mt-2">
                                                <label for="qantity">Qantity</label>
                                                <span class="text-danger">*</span>
                                                <input class="c-inp w-100 p-1" type="number" id="qantity" name="qantity" placeholder="e.g. 10,20,200">
                                            </div>


                                            <div class="col-sm-6 mt-2">
                                                <label for="unit">Unit</label>
                                                <span class="text-danger">*</span>
                                                <select class="c-inp p-1 w-100" id="unit" name="unit" required>
                                                    <option value='' disabled selected>Select</option>
                                                    <?php
                                                    foreach ($itemUnists as $eachUnit) {

                                                        if (in_array(strtolower($eachUnit['name']), $allowedItemUnits)) {
                                                            echo "<option value='" . $eachUnit['id'] . "'>" . $eachUnit['name'] . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- catagory - packging - power and unit row  -->
                                        <div class="row mt-2">
                                            <div class="col-sm-6 mt-2">
                                                <label for="medicine-power">Medicine Power</label>
                                                <span class="text-danger">*</span>
                                                <input class="c-inp w-100 p-1" type="number" id="medicine-power" name="medicine-power" required>
                                            </div>


                                            <div class="col-sm-6 mt-2">
                                                <label for="mrp">Enter MRP</label>
                                                <span class="text-danger">*</span>
                                                <input class="c-inp w-100 p-1" type="number" id="mrp" name="mrp" step="0.01" required>
                                            </div>
                                        </div>

                                        <!-- gst and hsno number row  -->
                                        <div class="row mt-2">
                                            <div class="col-sm-6 mt-2">
                                                <label for="mrp">Enter GST</label>
                                                <span class="text-danger">*</span>
                                                <select class="c-inp p-1 w-100" name="gst" id="gst" required>
                                                    <option value="" disabled selected>Select</option>
                                                    <?php
                                                    if (is_array($gstData)) {
                                                        foreach ($gstData as $gstPercent) {
                                                            echo '<option value="' . $gstPercent->id . '" >' . $gstPercent->percentage . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-sm-6 mt-2">
                                                <label for="hsno-number">HSNO Number</label>
                                                <span class="text-danger">*</span>
                                                <input class="c-inp w-100 p-1" type="number" id="hsno-number" name="hsno-number" minlength="4" maxlength="6" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- image and add/reset button secssion -->
                                    <div class="col-md-6" id="second-div">
                                        <!-- <div class="col-12"> -->
                                        <div id="img-div">
                                            <div class="container-fluid" id="img-container">
                                                <input type="file" name="img-files[]" id="img-file-input" accept=".jpg,.png" onchange="preview()" multiple>
                                                <label for="img-file-input" id="img-container-label">Choose Images &nbsp;<i class="fas fa-upload"></i></label>
                                                <p id="num-of-files">No files chosen</p>
                                                <div>
                                                    <div id="images">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-6"></div>
                                    <div class="col-6 d-flex justify-content-around">
                                        <div class="col-sm-6 text-center">
                                            <button class="btn btn-danger text-center" id="reset" type="reset" onclick="resetImg()"> Reset</button>
                                        </div>
                                        <div class="col-sm-6 text-center">
                                            <button class="btn btn-primary text-center" name="add-new-product" id="add-btn" type="submit">Add new</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /end Add Product  -->
            <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?>
        </div>
        <!-- /.container-fluid -->
        <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

    <!-- Footer -->

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
    <script src="<?php echo JS_PATH ?>custom/add-new-product.js"></script>

    <!-- Sweet Alert Js  -->
    <script src="<?php echo JS_PATH ?>sweetAlert.min.js"></script>

</body>

</html>