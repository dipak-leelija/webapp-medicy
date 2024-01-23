<?php
require_once dirname(dirname(dirname(__DIR__))) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';
require_once CLASS_DIR . 'gst.class.php';
require_once CLASS_DIR . 'productCategory.class.php';
require_once CLASS_DIR . 'request.class.php';


//objects Initilization
$Products           = new Products();
$Manufacturer       = new Manufacturer();
$MeasureOfUnits     = new MeasureOfUnits();
$PackagingUnits     = new PackagingUnits();
$ProductImages      = new ProductImages();
$ItemUnit           = new ItemUnit();
$GST                = new Gst;
$ProductCategory    = new ProductCategory;
$Request            = new Request;



$showManufacturer   = json_decode($Manufacturer->showManufacturerWithLimit());
$showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();
$showPackagingUnits = $PackagingUnits->showPackagingUnits();
$itemUnits          = $ItemUnit->showItemUnits();

$gstData            = json_decode($GST->seletGst());
$gstData = $gstData->data;


$Category = json_decode($ProductCategory->selectAllProdCategory());
$Category = $Category->data;
// print_r($Category);
// echo PROD_IMG;
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

        $table            = $_POST['table-info'];
        // echo $table;
        
        $productid        = $_POST['product-id'];
        $productName      = $_POST['product-name'];

        $productComp1     = $_POST['product-composition1'];
        $productComp2     = $_POST['product-composition2'];

        $hsnNumber        = $_POST['hsn-number'];
        $category         = $_POST['product-category'];
        $packagingType    = $_POST['packaging-type'];

        $medicinePower    = $_POST['medicine-power'];
        $quantity         = $_POST['quantity'];
        $qtyUnit          = $_POST['qty-unit'];
        $itemUnit         = $_POST['item-unit'];

        $manufacturerId     = $_POST['manufacturer'];

        $mrp              = $_POST['mrp'];
        $gst              = $_POST['gst'];
        $productDesc      = $_POST['product-description'];

        $prodReqStatus            = $_POST['prod-req-status'];
        $oldProdFlag              = $_POST['old-prod-flag'];

        // for img //
        $imageName        = $_FILES['img-files']['name'];
        $tempImgName       = $_FILES['img-files']['tmp_name'];

        $imageArrayCaount = count($imageName);
        $tempImageNameArrayCaount = count($tempImgName);

        $verifyStatus = 1;


        if($table == 'products'){
            // edit query on products table
            $updateProduct = json_decode($Products->updateProductBySuperAdmin($productid, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $supAdminId, NOW,  $verifyStatus));

            // print_r($updateProduct);
            if($updateProduct->status){
                $addProductOnRequest = json_encode(['status'=>'1']);
                $addProductOnRequest = json_decode($addProductOnRequest);
            }
        }

        if($table == 'product_request'){

            if ($prodReqStatus == 0 && $oldProdFlag == 0) {
                $addProductOnRequest = $Products->addProductBySuperAdmin($productid, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $supAdminId, $verifyStatus, NOW);
    
                // print_r($addProductOnRequest);
                $addProductOnRequest = json_decode($addProductOnRequest);
                if ($addProductOnRequest->status) {
                    $deleteRequest = $Request->deleteRequest($productid);
                    $updateProduct = true;
                }
            } elseif ($prodReqStatus == 0 && $oldProdFlag == 1) {
                // echo 'add to sku data';
                $randNum = rand(1, 999999999999);
                $newProductId = 'PR' . $randNum;
    
                $addProductOnRequest = $Products->addProductBySuperAdmin($newProductId, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $supAdminId, $verifyStatus, NOW);
    
                // print_r($addProductOnRequest);
                $addProductOnRequest = json_decode($addProductOnRequest);
                if ($addProductOnRequest->status) {
                    $deleteRequest = $Request->deleteRequest($productid);
                    $updateProduct = true;
                    $productid = $newProductId;
                }
            }
        }

        
        // echo "$productid"; 

        // $updateProduct = false;
        if ($addProductOnRequest->status) {

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
                    $addImages = $ProductImages->addImages($productid, $image, $supAdminId, NOW, $supAdminId);
                } else {
                    $addImages = true;
                }
            }
        }



        // $updateImage = true;
        
        if ($addProductOnRequest->status) {
            // echo "<br>before image";
            // print_r($addProductOnRequest);
            if ($addImages === true) {
            // echo "<br>after image";
            // // print_r($addProductOnRequest);
            // print_r($addImages);
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
        }else{
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