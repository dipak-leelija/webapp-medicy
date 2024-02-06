<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(dirname(dirname(__DIR__))) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';

// Require necessary classes
$required_classes = ['products', 'productsImages', 'manufacturer', 'measureOfUnit', 'packagingUnit', 'itemUnit', 'gst', 'productCategory', 'request'];

foreach ($required_classes as $class_name) {
    require_once CLASS_DIR . $class_name . '.class.php';
}


// Objects Initialization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$ProductImages      = new ProductImages();
$ItemUnit           = new ItemUnit();
$GST                = new Gst;
$ProductCategory    = new ProductCategory;
$Request            = new Request;

// Fetch necessary data
$showManufacturer   = json_decode($Manufacturer->showManufacturerWithLimit());
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();
$itemUnits          = $ItemUnit->showItemUnits();
$gstData            = json_decode($GST->seletGst())->data;
$Category           = json_decode($ProductCategory->selectAllProdCategory())->data;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
</head>

<body>
    <?php

    if (isset($_POST['update-product'])) {

        $table              = $_POST['table-info'];
        $productid          = $_POST['product-id'];
        $productName        = $_POST['product-name'];
        $productComp1       = $_POST['product-composition1'];
        $productComp2       = $_POST['product-composition2'];
        $hsnNumber          = $_POST['hsn-number'];
        $category           = $_POST['product-category'];
        $packagingType      = $_POST['packaging-type'];
        $medicinePower      = $_POST['medicine-power'];
        $quantity           = $_POST['quantity'];
        $qtyUnit            = $_POST['qty-unit'];
        $itemUnit           = $_POST['item-unit'];
        $manufacturerId     = $_POST['manufacturer'];
        $mrp                = $_POST['mrp'];
        $gst                = $_POST['gst'];
        $productDesc        = $_POST['product-description'];
        $prodReqStatus      = $_POST['prod-req-status'];
        $oldProdFlag        = $_POST['old-prod-flag'];
        $imageName          = $_FILES['img-files']['name'];
        $tempImgName        = $_FILES['img-files']['tmp_name'];
        $verifyStatus       = 1;




        if ($table == 'products') {
            $updateProduct = json_decode($Products->updateProductBySuperAdmin($productid, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $supAdminId, NOW, $verifyStatus));
            if ($updateProduct->status) {
                $updateProduct = true;
            }else{
                $updateProduct = false;
            }
        }

        if ($table == 'product_request') {
            if ($prodReqStatus == 0 && $oldProdFlag == 0) {
                $addProductOnRequest = $Products->addProductBySuperAdmin($productid, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $supAdminId, $verifyStatus, NOW);
                $addProductOnRequest = json_decode($addProductOnRequest);
                if ($addProductOnRequest->status) {
                    $updateProduct = true;
                }else{
                    $updateProduct = false;
                }
            } elseif ($prodReqStatus == 0 && $oldProdFlag == 1) {
                $addProductOnRequest = $Products->addProductBySuperAdmin($productid, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $supAdminId, $verifyStatus, NOW);
                $addProductOnRequest = json_decode($addProductOnRequest);
                if ($addProductOnRequest->status) {
                    $getProdReqData = json_decode($Request->selectProductData($productid));
                    $oldProdId = $getProdReqData->data->old_prod_id;
                    $oldProductData = json_decode($Products->showProductsById($oldProdId));
                    $prdReqFlagData = $oldProductData->data->edit_request_flag;
                    if ($prdReqFlagData > 0) {
                        $prdReqFlagData--;
                    }
                    $productsCol = 'edit_request_flag';
                    $updateProduct = $Products->updateProductValuebyCol($oldProdId, $productsCol, $prdReqFlagData, $supAdminId, NOW, $supAdminId);
                    $updateProduct = true;
                }else{
                    $updateProduct = false;
                }
            }
        }



    
        // Image Upload
        if (isset($updateProduct) && $updateProduct) {
            for ($i = 0, $j = 0; $i < count($imageName) && $j < count($tempImgName); $i++, $j++) {
                $imgStatus = 0;
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for ($k = 0; $k < 9; $k++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }
                $image          = $imageName[$i];
                $tempImage      = $tempImgName[$j];
                $extention      = substr($image, -4);
                $imageFileName  = substr($image, 0, -4);
                if ($imageFileName != null) {
                    $imageFile  =   $imageFileName . '-' . $randomString . $extention;
                    $imgFolder  = PROD_IMG . $imageFile;
                    move_uploaded_file($tempImage, $imgFolder);
                    $image      = addslashes($imageFile);
                } else {
                    $image = null;
                }
                if ($image != null) {
                    $status = 1;
                    $addImages = $ProductImages->addImagesBySupAdmin($productid, $image, $status, $supAdminId, NOW, $supAdminId);
                } else {
                    $addImages = true;
                }
            }
        }

        if ($updateProduct) {
            if ($addImages === true) {
                $deleteRequest = $Request->deleteRequest($productid);
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
                    swal("Error", "Product updated Fails!", "error").then((value) => {
                        parent.location.reload();
                    });
                </script>
    <?php
            }
        } else {
    ?>
            <script>
                swal("Error", "Product updated Fails!", "error").then((value) => {
                    parent.location.reload();
                });
            </script>
    <?php
        }
    }
    ?>
</body>

</html>

<!-- <?php
echo "delete call";
sleep(30);
$deleteRequest = $Request->deleteRequest($productid);
?> -->