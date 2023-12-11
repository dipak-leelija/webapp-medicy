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
require_once CLASS_DIR . 'productCategory.class.php';
require_once CLASS_DIR . 'gst.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';



//objects Initilization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$ProductCategory    = new ProductCategory;
$Gst                = new Gst;
$ItemUnit           = new ItemUnit;


$showManufacturer   = $Manufacturer->showManufacturerWithLimit();
$showManufacturer = json_decode($showManufacturer);
// print_r($showManufacturer);
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();

$prodCategory = json_decode($ProductCategory->selectAllProdCategory());


$gstData = json_decode($Gst->seletGst());
$gstData = $gstData->data;


$packagingUnitData = $PackagingUnits->showPackagingUnits();

$unitData = $ItemUnit->showItemUnits();

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
                    <div class="d-flex justify-content-center">
                        <div class="card shadow mb-4" style="min-height: 80vh; max-width: 80vh;">
                            <h4 class="h4 d-flex justify-content-center aligen-item-center mt-4"> Add New Product</h1>
                                <div class="card-body">
                                    <form action="_config\form-submission\add-new-product.php" enctype="multipart/form-data" method="post" id="add-new-product-details">
                                        <!-- product name row -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="product-name">Enter Prodcut Name</label>
                                                    <input class="c-inp w-100 p-1" id="product-name" name="product-name" placeholder="Product Name" required>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- product hsno and category row -->
                                        <div class="row mt-2">
                                            <div class="d-flex col-12">
                                                <div class="col-md-6">
                                                    <label class="mb-0 mt-1" for="hsno-number">Enter HSNO Number</label>
                                                    <input class="c-inp w-100 p-1" id="hsno-number" name="hsno-number" placeholder="HSNO Number" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="mb-0 mt-1" for="product-catagory">Select Prodcut Catagory</label>
                                                    <select class="c-inp p-1 w-100" name="product-catagory" id="product-catagory" required>
                                                        <option value="" disabled selected>Select Catagory</option>
                                                        <?php
                                                        if ($prodCategory->status == 1 && is_array($prodCategory->data)) {
                                                            $prodCategory = $prodCategory->data;

                                                            foreach ($prodCategory as $category) {
                                                                echo '<option value="' . $category->id . '">' . $category->prod_category_name . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- catagory - packging - power and unit row  -->
                                        <div class="row mt-2">
                                            <div class="d-flex col-12">
                                                <div class="col-md-6">
                                                    <label class="mb-0 mt-1" for="medicine-power">Enter Medicine Power</label>
                                                    <input class="c-inp w-100 p-1" id="medicine-power" name="medicine-power" placeholder="Power" required oninput="validateNumber(this)">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="mb-0 mt-1" for="qantity-unit">Enter Qantity</label>
                                                    <input class="c-inp w-100 p-1" id="qantity-unit" name="qantity-unit" placeholder="Enter Qantity" required oninput="validateNumber(this)">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- catagory - packging - power and unit row  -->
                                        <div class="row mt-2">
                                            <div class="d-flex col-12">
                                                <div class="col-md-6">
                                                    <label class="mb-0 mt-1" for="unit">Enter Unit</label>
                                                    <select class="c-inp p-1 w-100" name="unit" id="unit" required>
                                                        <option value="" disabled selected>Select Unit Type</option>
                                                        <?php 
                                                            if(is_array($unitData)){
                                                                foreach($unitData as $unit){
                                                                    print_r($unit);
                                                                    echo '<option value="'.$unit['id'].'" >'.$unit['name'].'</option>';
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="mb-0 mt-1" for="packeging-type">Enter Packeging Type</label>
                                                    <select class="c-inp p-1 w-100" name="packeging-type" id="packeging-type" required>
                                                        <option value="" disabled selected>Select Packeging Type</option>
                                                        <?php 
                                                            if(is_array($packagingUnitData)){
                                                                foreach($packagingUnitData as $packaging){
                                                                    echo '<option value="'.$packaging['id'].'" >'.$packaging['unit_name'].'</option>';
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- mrp, gst and hsno number row  -->
                                        <div class="row mt-2">
                                            <div class="d-flex col-12">
                                                <div class="col-md-6">
                                                    <label class="mb-0 mt-1" for="mrp">Enter MRP</label>
                                                    <input class="c-inp w-100 p-1" id="mrp" name="mrp" placeholder="mrp" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="mb-0 mt-1" for="gst">Enter GST</label>
                                                    <select class="c-inp p-1 w-100" name="gst" id="gst" required>
                                                        <option value="" disabled selected>Select GST%</option>
                                                        <?php 
                                                        if(is_array($gstData)){
                                                            foreach($gstData as $gstPercent){
                                                                echo '<option value="'.$gstPercent->id.'" >'.$gstPercent->percentage.'</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12 d-flex">
                                                <div class="col-sm-6 d-flex justify-content-center">
                                                    <button class="btn btn-danger col-sm-12" id="reset" type="reset"> Reset </button>
                                                </div>

                                                <div class="col-sm-6 d-flex justify-content-center">
                                                    <button class="btn btn-primary col-sm-12" name="add-new-product" id="add-new-product" type="submit">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
    <!-- <script src="<?php echo JS_PATH ?>custom/add-products.js"></script> -->

    <!-- Sweet Alert Js  -->
    <script src="<?php echo JS_PATH ?>sweetAlert.min.js"></script>

    <script>
        function validateNumber(input) {
            input.value = input.value.replace(/\D/g, '');
        }
    </script>
</body>

</html>