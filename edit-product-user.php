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


if ($_SESSION['ADMIN']) {
    $addedBy = $adminId;
} else {
    $addedBy = $employeeId;
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

    <title>Add Items</title>

    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Fontawsome Link -->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= CSS_PATH ?>font-awesome.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!--Custom CSS -->
    <link href="<?php echo CSS_PATH ?>custom/add-products.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

    <!-- <link href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" rel="stylesheet" /> -->

    <!-- sweetAlert link -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>


</head>

<body id="page-top">
    <?php
    if (isset($_GET['id'])) {

        $productId = $_GET['id'];

        if (isset($_POST['update-product'])) {

            $productId  =   $_POST['product-id'];
            $productName      = $_POST['product-name'];

            $productCategory = $_POST['product-category']; // like : allopathy, drugs,  cosmetics etc.
            $packagingIn    = $_POST['packeging-type']; // strip, bottle, tubes etc.

            $quantity = $_POST['qantity']; // e.g. 10,20,100 etc.
            $unit = $_POST['unit']; // e.g. tablet, capsule, syrup etc.

            $medicinePower = $_POST['medicine-power']; // e.g. 5, 10, 25, 50, 500 etc.
            $mrp = $_POST['mrp'];

            $gstPercent = $_POST['gst'];
            $hsnoNumber = $_POST['hsno-number'];



            // ============ for img ============ //
            $imageName        = $_FILES['img-files']['name'];
            $tempImgName       = $_FILES['img-files']['tmp_name'];

            $imageArrayCaount = count($imageName);
            $tempImageNameArrayCaount = count($tempImgName);



            echo "Product iD : $productId <br>";
            echo "productName : ";
            print_r($productName);
            echo "<br>";
            echo "product category : ";
            print_r($productCategory);
            echo "<br>";
            echo "packeging in : ";
            print_r($packagingIn);
            echo "<br>";
            echo "qantity : ";
            print_r($quantity);
            echo "<br>";
            echo "unit : ";
            print_r($unit);
            echo "<br>";
            echo "medicine power : ";
            print_r($medicinePower);
            echo "<br>";
            echo "mrp : ";
            print_r($mrp);
            echo "<br>";
            echo "gst : ";
            print_r($gstPercent);
            echo "<br>";
            echo "hsno : ";
            print_r($hsnoNumber);
            echo "<br>";

            echo "images name : ";
            print_r($imageName);
            echo "<br>";
            echo "temp images name : ";
            print_r($tempImgName);
            echo "<br>";



            // $column = 'product_id';
            $prodDataFromProducts = json_decode($Products->showProductsById($productId));
            if ($prodDataFromProducts->status) {
                if ($prodDataFromProducts->data[0]->edit_request_flag == 0) {
                    $oldProdFlag = 1;
                    $status = 0;
                    // print_r($prodDataFromProducts->data);
                    // add product request to product request table through request class.
                    $addOldProdEditRequest = $Request->addOldProductRequest($productId, $productName, $productCategory, $packagingIn,  $quantity, $unit, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $addedBy, NOW, $adminId, $status, $oldProdFlag);

                    //update product tabel
                    if ($addOldProdEditRequest) {
                        $col = 'edit_request_flag';
                        $data = 1;
                        $updateProduct = $Products->updateOnColData($col, $data, $productId);
                    }
                } else {
                    // echo "request submitted previously. wt for responce";
                    //update product request by prod id and admin id
                    $prodDetailsFromProductRequest = json_decode($Request->selectProductById($productId, $adminId));
                    print_r($prodDetaislFromProductRequest);
                }
            } else {
                echo "2";
                $prodDetaislFromProductRequest = json_decode($Request->selectProductById($productId, $adminId));
                print_r($prodDetaislFromProductRequest);
                // update product request
            }


            // $updateProduct = $Products->updateProduct($productId, $productName, $manufacturer, $type = '', $productComp1, $productComp2, $medicinePower, $productDesc, $quantity, $qtyUnit, $itemUnit, $packagingType, $mrp, $gst, $employeeId, NOW);



            /*if ($updateProduct === true) {

                // $delProdImage = $ProductImages->deleteImage($productId);

                for ($j = 0; $j < $imageArrayCaount && $j < $tempImageNameArrayCaount; $j++) {
                    ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PRIFIX \\\\\\\\\\\\\

                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $randomString = '';
                    for ($i = 0; $i < 9; $i++) {
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                    }

                    $randomString = $randomString;

                    ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                    //===== Main Image 
                    $image         = $imageName[$j];


                    if ($image) {

                        $ImgNm = '';
                        $extention = '';
                        $countImageLen = 0;


                        // echo "<br>Checking image name on entry : $image";

                        if ($image != '') {
                            if ($image != null) {
                                if (file_exists(PROD_IMG_PATH . $randomString . '_' . $image)) {
                                    $image = 'medicy-' . $randomString . $image;
                                }
                            }

                            $countImageLen = strlen($image);
                            for ($l = 0; $l < intval($countImageLen) - 4; $l++) {
                                $ImgNm .= $image[$l];
                            }
                            for ($k = intval($countImageLen) - 4; $k < $countImageLen; $k++) {
                                $extention .= $image[$k];
                            }

                            $image         = $ImgNm . '-' . $randomString . $extention;
                            $imgFolder     = PROD_IMG_DIR . $image;

                            // move_uploaded_file($tempImgname, $imgFolder);
                            move_uploaded_file($tempImgName[$j], $imgFolder);

                            $image         = addslashes($image);
                        }

                        if ($image == '') {
                            $image = '';
                        }

                        $setPriority = isset($_POST['priority-group']) ? $_POST['priority-group'] : 0;


                        // $updateImage = $ProductImages->addImages($productId, $image, $employeeId, NOW, $adminId);
                        if ($updateImage) {
                            $updatePriority = $ProductImages->updatePriority($image, $setPriority, $productId);
                        }
                    } else {
                        $addImage = true;
                    }
                }
            }*/



            // $updateImage = true;
            if ($updateProduct === true) {
                // if ($updateImage === true) {
    ?>
                <script>
                    swal("Success", "Product updated successfully!", "success").then((value) => {
                        parent.location.reload();
                    });
                </script>
        <?php
            } else {

                // }
            }
        }


        // ===================== Fetching Product Details =====================

        $product = json_decode($Products->showProductsByIdOnUser($productId, $adminId));
        $product = $product->data;
        // print_r($product);

        $productName    = $product[0]->name;

        $type           = $product[0]->type;
        $prodCategoryName   = json_decode($ProductCategory->selectNameById($type));
        $prodCategoryName   = $prodCategoryName->data;



        $qty            = $product[0]->unit_quantity;
        // $qtyUnit        = $product[0]->unit_id;
        $itemUnit       = $product[0]->unit;
        // echo "<br>Item unit : $itemUnit";        
        $packagingType  = $product[0]->packaging_type;
        // echo "<br>Packaging Type : $packagingType";    

        $power          = $product[0]->power;

        $mrp            = $product[0]->mrp;
        $gst            = $product[0]->gst;
        // $added_by       = $product[0]->added_by;
        // $added_on       = $product[0]->added_on;
        // $updated_by     = $product[0]->updated_by;
        // $updated_on     = $product[0]->updated_on;
        $admin_id       = $product[0]->admin_id;

        $images = json_decode($ProductImages->showImageById($productId));

        $allImg = array();
        $allImgId = array();
        if ($images->status == 1 && !empty($images->data)) {
            foreach ($images->data as $image) {
                $allImg[] = $image->image;
                $allImgId[] = $image->id;
            }
        } else {
            $allImg[] = "medicy-default-product-image.jpg";
        }

        // foreach ($allImgId as $index => $imageID) {
        //     print_r($imageID);
        // }

        ?>

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">
                    <!-- Add Product -->
                    <div class="card shadow mb-4 h-100">
                        <div class="card-body">
                            <form action="<?= htmlspecialchars(CURRENT_URL); ?>" method="post" enctype="multipart/form-data">
                                <div class="d-flex flex-wrap">

                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="border p-1 rounded">
                                                    <div class="row h-75 mt-2 justify-content-center">
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
                                        </div>
                                        <!--/End Product Image Row  -->
                                        <br>
                                        <!-- <div class="row"> -->
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <!-- <button class="btn btn-danger mr-3" id="reset" type="button">Reset</button> -->
                                            <button class="btn btn-primary" name="update-product" id="update-btn" type="submit">Update</button>
                                        </div>

                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="col-md-12">
                                                    <b>Prodcut Name</b>
                                                    <input class="c-inp w-100 p-1" id="product-name" name="product-name" placeholder="Product Name" value="<?= $productName ?>" required>

                                                    <input class="d-none c-inp w-100 p-1" id="product-id" name="product-id" value="<?= $productId ?>" required>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="d-flex col-12">
                                                <div class="col-md-6 mt-3">
                                                    Prodcut Catagory
                                                    <select class="c-inp p-1 w-100" name="product-category" id="product-category" required>
                                                        <!-- <option value="<?php echo $type ?>" disabled selected><?php echo $prodCategoryList[0]->name ?></option> -->
                                                        <option value="" disabled selected>Select</option>
                                                        <?php
                                                        foreach ($prodCategoryList as $category) {
                                                            echo '<option value="' . $category->id . '">' . $category->name . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-6 mt-3">
                                                    Packeging In
                                                    <select class="c-inp p-1 w-100" name="packeging-type" id="packeging-type" required>
                                                        <!-- <option value="<?php echo $packagingType; ?>" disabled selected><?php echo $showPackagingUnits[0]['unit_name'] ?></option> -->
                                                        <option value="" disabled selected>Select</option>
                                                        <?php
                                                        foreach ($showPackagingUnits as $eachPackUnit) {
                                                            if (in_array(strtolower($eachPackUnit['unit_name']), $allowedPackegingUnits)) {
                                                                echo "<option value='{$eachPackUnit['id']}'>{$eachPackUnit['unit_name']}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="d-flex col-12 mt-3">
                                                <div class="col-md-6">
                                                    Qantity
                                                    <input class="c-inp w-100 p-1 mt-1" id="qantity" name="qantity" value="<?php echo $qty; ?>" placeholder="e.g. 10,20,200" required>
                                                </div>

                                                <div class="col-md-6">
                                                    Unit
                                                    <select class="c-inp p-1 w-100 mt-1" id="unit" name="unit" required>
                                                        <!-- <option value='<?php echo $itemUnit; ?>' disabled selected><?php echo $itemUnits[0]['name']; ?></option> -->
                                                        <option value="" disabled selected>Select</option>
                                                        <?php
                                                        foreach ($itemUnits as $itemUnits) {
                                                            if (in_array(strtolower($itemUnits['name']), $allowedItemUnits)) {
                                                                echo "<option value=''>" . $itemUnits['name'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/End Price Row -->


                                        <div class="row">
                                            <div class="d-flex col-12 mt-3">
                                                <div class="col-md-6">
                                                    Medicine Power
                                                    <input class="c-inp w-100 p-1 mt-1" id="medicine-power" name="medicine-power" value="<?php echo $power; ?>" required>
                                                </div>

                                                <div class="col-md-6">
                                                    Enter MRP
                                                    <input class="c-inp w-100 p-1 mt-1" id="mrp" name="mrp" value="<?php echo $mrp; ?>" required>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-12 d-flex mt-3">
                                                <div class="col-sm-6">
                                                    Enter GST
                                                    <select class="c-inp p-1 w-100 mt-1" name="gst" id="gst" required>
                                                        <!-- <option value="" disabled selected><?php echo $gstDetails[0]->percentage; ?></option> -->
                                                        <option value="" disabled selected>Select</option>
                                                        <?php
                                                        foreach ($gstDetails as $gstDetail) {
                                                            echo '<option value="' . $gstDetail->id . '" >' . $gstDetail->percentage . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    HSNO Number
                                                    <input class="c-inp w-100 p-1 mt-1" id="hsno-number" name="hsno-number" value="<?php echo $product[0]->hsno_number; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
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