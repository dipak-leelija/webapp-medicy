<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
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

    if (isset($_POST['update-product'])) {

        $productId  =   $_POST['product-id'];
        $oldProdId = $productId;

        $productName      = $_POST['product-name'];

        $productCategory = $_POST['product-category']; // like : allopathy, drugs,  cosmetics etc.
        $packagingIn    = $_POST['packeging-type']; // strip, bottle, tubes etc.

        $quantity = $_POST['qantity']; // e.g. 10,20,100 etc.
        $unit = $_POST['unit']; // e.g. tablet, capsule, syrup etc.
        // echo "check unit : $unit";

        $medicinePower = $_POST['medicine-power']; // e.g. 5, 10, 25, 50, 500 etc.
        $mrp = $_POST['mrp'];

        $gstPercent = $_POST['gst'];
        $hsnoNumber = $_POST['hsno-number'];

        $comp1 = $_POST['comp-1'];
        $comp2 = $_POST['comp-2'];

        // ==================== for img ===================== //
        $imageName        = $_FILES['img-files']['name'];
        $tempImgName       = $_FILES['img-files']['tmp_name'];

        $imageArrayCaount = count($imageName);
        $tempImageNameArrayCaount = count($tempImgName);
        // ====================================================

        // echo "images name : ";
        // print_r($imageName);
        // echo "<br>";
        // echo "temp images name : ";
        // print_r($tempImgName);
        // echo "<br>";

        // $column = 'product_id';
        $prodDataFromProducts = json_decode($Products->showProductsById($productId));
        // print_r($prodDataFromProducts);

        if ($prodDataFromProducts->status) {
            if ($prodDataFromProducts->data->edit_request_flag == 0) {
                $oldProdFlag = 1;
                $prodReqStatus = 0;

                $randNum = rand(1, 999999999999);
                $newProductId = 'PR' . $randNum;

                // $prdReq = "PRDREQ";

                $addOldProdEditRequest = $Request->addOldProductRequest($oldProdId, $newProductId, $productName, $comp1, $comp2, $productCategory, $packagingIn,  $quantity, $unit, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $addedBy, NOW, $adminId, $prodReqStatus, $oldProdFlag);
                $addOldProdEditRequest = json_decode($addOldProdEditRequest);

                // print_r($addOldProdEditRequest);

                $editRqstFlgData = intval($prodDataFromProducts->data->edit_request_flag);
                if ($addOldProdEditRequest->status) {
                    $col = 'edit_request_flag';
                    $editRqstFlgData += 1;
                    $updateProduct = $Products->updateOnColData($col, $editRqstFlgData, $productId);

                    $editRequest = true;
                }
            } else {

                $selectFromProdReqTable = json_decode($Request->selectProductById($productId, $adminId));
                // print_r($selectFromProdReqTable);
                if ($selectFromProdReqTable->status) {

                    $modifiedProdId = $selectFromProdReqTable->data[0]->$product_id;
                    $prodReqStatus = 0;
                    $oldProdFlag = 1;

                    $editRequest = $Request->editUpdateProductRequest($modifiedProdId, $productName, $comp1, $comp2, $productCategory, $packagingIn, $quantity, $unit, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $addedBy, NOW, $prodReqStatus, $oldProdFlag, $adminId);

                    // echo "hello2";
                    // echo "check edit request data : ";
                    // print_r($editRequest);
                } else {

                    $oldProdFlag = 1;
                    $prodReqStatus = 0;

                    $randNum = rand(1, 999999999999);
                    $productId = 'PR' . $randNum;

                    // $prdReq = "PRDREQ";

                    $addOldProdEditRequest = $Request->addOldProductRequest($oldProdId, $productId, $productName, $comp1, $comp2, $productCategory, $packagingIn,  $quantity, $unit, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $addedBy, NOW, $adminId, $prodReqStatus, $oldProdFlag);

                    // echo "hello3";

                    $editRqstFlgData = intval($prodDataFromProducts->data->edit_request_flag);
                    if ($addOldProdEditRequest) {
                        $col = 'edit_request_flag';
                        $editRqstFlgData += 1;
                        $updateProduct = $Products->updateOnColData($col, $editRqstFlgData, $productId);

                        $editRequest = true;
                    }
                }
            }
        }
        // else {

        //     $prodReqStatus = 0;
        //     $oldProdFlag = 0;

        //     $editRequest = $Request->editUpdateProductRequest($productId, $productName, $productCategory, $packagingIn, $quantity, $unit, $medicinePower, $mrp, $gstPercent, $hsnoNumber, $addedBy, NOW, $prodReqStatus, $oldProdFlag, $adminId);

        //     // echo "check edit request data : ";
        //     // print_r($editRequest);
        // }

        // $editRequest = false;

        if ($editRequest) {

            for ($i = 0, $j = 0; $i < $imageArrayCaount && $j < $tempImageNameArrayCaount; $i++, $j++) {
                ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PRIFIX \\\\\\\\\\\\\
                $imgStatus = 0;

                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';

                for ($k = 0; $k < 9; $k++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                //===== Main Image 
                $image          = $imageName[$i];
                $tempImage        = $tempImgName[$j];


                $extention = substr($image, -4);
                $imageFileName = substr($image, 0, -4);


                if ($imageFileName != null) {

                    $imageFile  =   $imageFileName . '-' . $randomString . $extention;
                    $imgFolder     = PROD_IMG . $imageFile;

                    move_uploaded_file($tempImage, $imgFolder);
                    $image         = addslashes($imageFile);
                } else {
                    $image = null;
                }

                if ($image != null) {
                    $addImagesRequest = $Request->addImageRequest($productId, $image, $addedBy, NOW, $adminId, $imgStatus);
                } else {
                    $addImagesRequest = true;
                }
            }
        }

        if ($editRequest === true) {
            // if ($updateImage === true) {
    ?>
            <script>
                swal("Success", "Product updated successfully!", "success").then((value) => {
                    parent.location.reload();
                });
            </script>
        <?php
        } else {
        ?>
            <script>
                swal("Error", "Product(image) updatation fail!", "error").then((value) => {
                    parent.location.reload();
                });
            </script>
    <?php
        }
    }

    ?>

</body>

</html>