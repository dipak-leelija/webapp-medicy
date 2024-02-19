<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';
require_once CLASS_DIR . 'productCategory.class.php';
require_once CLASS_DIR . 'gst.class.php';



//objects Initilization
$Products           = new Products();
$Request            = new Request;
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$ProductImages      = new ProductImages();
$ItemUnit           = new ItemUnit();
$ProductCategory    = new ProductCategory;
$Gst                = new Gst;


$showManufacturer   = json_decode($Manufacturer->showManufacturerWithLimit());
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();

$showPackagingUnits = $PackagingUnits->showPackagingUnits();

$itemUnits          = $ItemUnit->showItemUnits();
// print_r($itemUnits);
$prodCategoryList   = json_decode($ProductCategory->selectAllProdCategory());
$prodCategoryList   = $prodCategoryList->data;

$gstDetails = json_decode($Gst->seletGst());
$gstDetails = $gstDetails->data;



$allowedPackegingUnits = ["strip", "bottle", "tube", "box", "sachet", "packet", "jar", "kit", "bag", "vial", "ampoule", "respules", "cartridge"];

$allowedItemUnits = ["tablet", "tablets", "syrup", "capsules", "capsule", "soflets", "soflet", "lozenges", "bolus"];


$addedBy = ($_SESSION['ADMIN']) ? $adminId : $employeeId;
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
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Fontawsome Link -->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= CSS_PATH ?>font-awesome.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!--Custom CSS -->
    <link href="<?php echo CSS_PATH ?>add-new-product.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

    <!-- <link href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" rel="stylesheet" /> -->

    <!-- sweetAlert link -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

</head>

<body id="page-top">
    <?php
    if (isset($_GET['id'])) {

        $productId = $_GET['id'];
        $prodReqStatus = $_GET['prodReqStatus'];
        $oldProdFlag = $_GET['oldProdFlag'];
        $editRequestFlag = $_GET['editRequestFlag'];
        $table = $_GET['table'];


        // ================================ Fetching Product Details =================================
        $product = json_decode($Products->showProductsByIdOnUser($productId, $adminId, $editRequestFlag, $prodReqStatus, $oldProdFlag));
        // print_r($product);
        $product = $product->data;

        $productName    = $product[0]->name;

        $type           = $product[0]->type;

        $qty            = $product[0]->unit_quantity;
        
        $prevItemUnit       = $product[0]->unit;

        $packagingType  = $product[0]->packaging_type;

        $power          = $product[0]->power;

        if(isset($product[0]->unit_id)){
            $unitType = $product[0]->unit_id;
        }else{
            $unitType = "";
        }

        if(isset($product[0]->manufacturer_id)){
            $manufId = $product[0]->manufacturer_id;
        }else{
            $manufId = " ";
        }

        // echo $manufId;

        $mrp            = $product[0]->mrp;
        $gst            = $product[0]->gst;
        
        $admin_id       = $product[0]->admin_id;

        $images = json_decode($ProductImages->showImageById($productId));
        if ($images->status == 0) {
            $images = json_decode($ProductImages->showImageByPrimay($productId, $adminId));
        }

        $allImg = array();
        $allImgId = array();
        if ($images->status == 1 && !empty($images->data)) {
            foreach ($images->data as $image) {
                $allImg[] = $image->image;
                $allImgId[] = $image->id;
            }
        } else {
            $allImg[] = "default-product-image/medicy-default-product-image.jpg";
        }

        

    ?>

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">
                    <!-- Add Product -->
                    <div class="card shadow mb-4 h-100">
                        <div class="card-body shadow mb-4 col-12 d-flex flex-wrap">
                            <form action="_config\form-submission\edit-product-user.php" method="post" enctype="multipart/form-data">
                                <div class="d-flex flex-wrap">
                                    <!-- <div class="col-md-5"> -->
                                    <div class="row">
                                        <div class="col-6" id="first-div">
                                            <div class="border p-1 rounded">
                                                <div class="row h-50 mt-2 justify-content-center">
                                                    <?php foreach ($allImg as $index => $imagePath) : ?>
                                                        <div class="col-2 border m-1 p-0">
                                                            <img src="<?= PROD_IMG_PATH ?><?php echo $imagePath; ?>" id="img-<?php echo $index; ?>" onclick="setImg(this.id)" class=" ob-cover h-100" alt="...">

                                                            <?php foreach ($allImgId as $idIndex => $imageID) : ?>
                                                                <?php if ($idIndex === $index) : ?>
                                                                    <input class="form-check-input mt-5 ml-n5" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                                                    <button type="button" class="btn-close position-absolute rounded border bg-danger text-white mt-n3 ml-n3" aria-label="Close" onclick="closeImage('<?php echo $imageID; ?>', '<?php echo $imagePath; ?>', <?php echo $index; ?>)">x</button>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
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
                                        <!--/End Product Image Row  -->

                                        <div class="col-6" id="second-div">

                                            <div class="col-sm-12">
                                                <label>Prodcut Name</label>
                                                <input class="c-inp w-100 p-1" id="product-name" name="product-name" placeholder="Product Name" value="<?= $productName ?>" required>

                                                <input class="d-none c-inp w-100 p-1" id="product-id" name="product-id" value="<?= $productId ?>" required>

                                                <input class="d-none c-inp w-100 p-1" id="table-name" name="table-name" value="<?= $table ?>" required>
                                            </div>


                                            <div class="d-flex flex-wrap col-md-12 mt-2">
                                                <div class="col-sm-6">
                                                    <label>Prodcut Catagory</label>
                                                    <select class="c-inp p-1 w-100" name="product-category" id="product-category" required>
                                                        <option value="" disabled selected>Product Category</option>
                                                        <?php
                                                        foreach ($prodCategoryList as $category) {
                                                            // print_r($category);
                                                        ?>
                                                            <option <?= $type == $category->id ? 'selected' : ''; ?> value="<?php echo $category->id ?>">
                                                                <?php echo $category->name ?>
                                                            </option>';

                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label>Packeging In</label>
                                                    <select class="c-inp p-1 w-100" name="packeging-type" id="packeging-type" required>
                                                        <option value="" disabled selected>Packeging In</option>

                                                        <?php
                                                        foreach ($showPackagingUnits as $eachPackUnit) {
                                                            if (in_array(strtolower($eachPackUnit['unit_name']), $allowedPackegingUnits)) {
                                                                print_r($eachPackUnit);
                                                        ?>
                                                                <option <?= $packagingType == $eachPackUnit['id'] ? 'selected' : ''; ?> value="<?php echo $eachPackUnit['id'] ?>">
                                                                    <?php echo $eachPackUnit['unit_name'] ?>
                                                                </option>';
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="d-flex flex-wrap col-md-12 mt-2">
                                                <div class="col-sm-6">
                                                    <label>Qantity</label>
                                                    <input class="c-inp w-100 p-1 mt-1" id="qantity" name="qantity" value="<?php echo $qty; ?>" placeholder="e.g. 10,20,200" required>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label>Unit</label>
                                                    <select class="c-inp p-1 w-100 mt-1" id="unit" name="unit" required>
                                                        <option value="" disabled selected>Select</option>
                                                        <?php
                                                        foreach ($itemUnits as $itemUnits) {
                                                            // print_r($itemUnits);
                                                            if (in_array(strtolower($itemUnits['name']), $allowedItemUnits)) {
                                                                echo $itemUnits['id'];

                                                        ?>
                                                                <option <?= $prevItemUnit == $itemUnits['id'] ? 'selected' : '' ?> value="<?php echo $itemUnits['id'] ?>">
                                                                    <?php echo $itemUnits['name'] ?>
                                                                </option>';
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-wrap col-md-12 mt-2">
                                                <div class="col-sm-6">
                                                    <label>Medicine Power</label>
                                                    <input class="c-inp w-100 p-1 mt-1" id="medicine-power" name="medicine-power" value="<?php echo $power; ?>" required>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label>Enter MRP</label>
                                                    <input class="c-inp w-100 p-1 mt-1" id="mrp" name="mrp" value="<?php echo $mrp; ?>" required>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-wrap col-md-12 mt-2">
                                                <div class="col-sm-6">
                                                    <label>Enter GST</label>
                                                    <select class="c-inp p-1 w-100 mt-1" name="gst" id="gst" required>
                                                        <option value="" disabled selected>GST</option>
                                                        <?php
                                                        foreach ($gstDetails as $gstDetail) {
                                                            // print_r($gstDetail);
                                                        ?>
                                                            <option <?= $gst == $gstDetail->id ? 'selected' : ''; ?> value="<?php echo $gstDetail->id ?>">
                                                                <?php echo $gstDetail->percentage ?>
                                                            </option>';

                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>HSNO Number</label>
                                                    <input class="c-inp w-100 p-1 mt-1" id="hsno-number" name="hsno-number" value="<?php echo $product[0]->hsno_number; ?>" required>
                                                </div>
                                            </div>

                                            <div class="d-none col-md-12 d-flex mt-3">
                                                <div class="d-none col-sm-3">
                                                    <label>Composition 1</label>
                                                    <input class="c-inp w-100 p-1 mt-1" id="comp-1" name="comp-1" value="<?php echo $product[0]->comp_1; ?>">
                                                </div>
                                                <div class="d-none col-sm-3">
                                                    <label>Composition 2</label>
                                                    <input class="c-inp w-100 p-1 mt-1" id="comp-2" name="comp-2" value="<?php echo $product[0]->comp_2; ?>">
                                                </div>
                                                <div class="d-none col-sm-3">
                                                    <label>Unit Type</label>
                                                    <input class="c-inp w-100 p-1 mt-1" id="unitType" name="unitType" value="<?php echo $unitType; ?>">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Manuf Id</label>
                                                    <input class="c-inp w-100 p-1 mt-1" id="manufId" name="manufId" value="<?php echo $manufId; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-primary btn-lg d-flex justify-content-end" name="update-product" id="update-btn" type="submit">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <!-- /end Add Product  -->
            <?php
        }
            ?>
            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Bootstrap core JavaScript-->
        <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <!-- <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script> -->

        <!-- Custom scripts for all pages-->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
        <script src="<?= JS_PATH ?>custom/add-products-user.js"></script>
        <!-- Sweet Alert Js  -->
        <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

        <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>




        <script>
            //calculating profit only after entering MRP
            function getMarginMrp(value) {
                this.value = parseFloat(this.value).toFixed(2);
                const mrp = parseFloat(value);
                const ptr = parseFloat(document.getElementById("ptr").value);
                const gst = parseFloat(document.getElementById("gst").value);

                var profit = (mrp - ptr);

                profit = parseFloat(profit - ((gst / 100) * ptr));

                document.getElementById("profit").value = profit.toFixed(2);
            }


            //calculate after entering PTR
            function getMarginPtr(value) {
                const ptr = parseFloat(value);
                const mrp = parseFloat(document.getElementById("mrp").value);
                const gst = parseFloat(document.getElementById("gst").value);

                var profit = parseFloat(mrp - ptr);

                profit = parseFloat(profit - ((gst / 100) * ptr));

                document.getElementById("profit").value = profit.toFixed(2);
            }


            //calculate after entering GST
            function getMarginGst(value) {
                const gst = parseFloat(value);
                const ptr = parseFloat(document.getElementById("ptr").value);
                const mrp = parseFloat(document.getElementById("mrp").value);

                var profit = parseFloat(mrp - ptr);

                profit = parseFloat(profit - ((gst / 100) * ptr));

                document.getElementById("profit").value = profit.toFixed(2);
            }
            //image selection//
        </script>

        <script>
            $(document).on("click", ".back", function() {
                var backFile = $(this).parents().find(".back-file");
                backFile.trigger("click");
            });
            $('.back-file').change(function(e) {
                $(".back-img-field").hide();
                $("#back-preview").show();


                var fileName = e.target.files[0].name;
                $("#back-file").val(fileName);

                var reader = new FileReader();
                reader.onload = function(e) {
                    // get loaded data and render thumbnail.
                    document.getElementById("back-preview").src = e.target.result;
                };
                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
            });
        </script>

        <script>
            $(document).on("click", ".side", function() {
                var SideFile = $(this).parents().find(".side-file");
                SideFile.trigger("click");
            });
            $('.side-file').change(function(img) {
                $(".side-img-field").hide();
                $("#side-preview").show();


                var sideImgName = img.target.files[0].name;
                $("#side-file").val(sideImgName);

                var reader = new FileReader();
                reader.onload = function(img) {
                    // get loaded data and render thumbnail.
                    document.getElementById("side-preview").src = img.target.result;
                };
                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
            });
        </script>

</body>

</html>