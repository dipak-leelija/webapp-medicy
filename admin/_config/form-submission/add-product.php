<?php
require_once dirname(dirname(dirname(__DIR__))) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'request.class.php';


$Products       = new Products();
$ProductImages  = new ProductImages();
$Unit           = new MeasureOfUnits();
$Session        = new SessionHandler();
$Request        = new Request;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <script src="<?php echo JS_PATH ?>sweetAlert.min.js"></script>
</head>

<body>
    <div>
    </div>
    <?php

    if (isset($_POST['add-product'])) {

        $imagesName           = $_FILES['img-files']['name'];
        $tempImgsName       = $_FILES['img-files']['tmp_name'];
        // print_r($tempImgsName);
        $imageArrayCaount = count($imagesName);
        $tempImageArrayCaount = count($tempImgsName);

        $productName = $_POST['product-name'];
        // print_r($prodName);
        $productComp1  = $_POST['product-composition-1'];
        $productComp2  = $_POST['product-composition-2'];

        $hsnNumber  = $_POST['product-composition-2'];
        $category  = $_POST['product-composition-2'];
        $packagingType  = $_POST['product-composition-2'];

        $medicinePower = $_POST['medicine-power'];
        $unitQuantity  = $_POST['unit-quantity'];
        $unit          = $_POST['unit'];
        $packagingType = $_POST['packaging-type'];

        $manufacturer  = $_POST['manufacturer'];
        
        $mrp           = $_POST['mrp'];
        $gst           = $_POST['gst'];
        $productDesc   = $_POST['product-descreption'];
        $addedBy       = $supAdminId;

        //ProductId Generation
        $randNum = rand(1, 999999999999);
        $newProductId = 'PR' . $randNum;

        //Insert into products table 
        $addProducts = $Products->addProductBySuperAdmin($newProductId, $productName, $productComp1, $productComp2, $hsnNumber, $category, $packagingType, $medicinePower, $quantity, $qtyUnit, $itemUnit, $manufacturerId, $mrp, $gst, $productDesc, $supAdminId, $verifyStatus, NOW);

        // 

        print_r($addProducts);


        $addProducts = true;

        if ($addProducts === true) {

            for ($j = 0; $j < $imageArrayCaount && $j < $tempImageArrayCaount; $j++) {
                ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PRIFIX \\\\\\\\\\\\\

                $imgStatus = 0;

                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for ($i = 0; $i < 9; $i++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                $randomString = $randomString;

                ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                //===== Main Image 
                $imageNmame        = $imagesName[$j];
                $tempImgname   = $tempImgsName[$j];

                $extention = substr($imageNmame, -4);
                $imageNm = substr($imageNmame, 0, -4);

                if ($imageNmame != null) {

                    $image         = $imageNm . '-' . $randomString . $extention;
                    $imgFolder     = PROD_IMG . $image;

                    // echo $image."<br>";

                    move_uploaded_file($tempImgname, $imgFolder);
                    $image         = addslashes($image);
                } else {
                    $image = '';
                }


                $addImagesRequest = $Request->addImageRequest($productId, $image, $addedBy, NOW, $supAdminId, $imgStatus);

                print_r($addImagesRequest);
                exit;
            }
  
    ?>
            <script>
                swal("Success", "Product Added!", "success")
                    .then((value) => {
                        window.location = '<?php echo SUP_ADM_DIR ?>add-products.php';
                    });
            </script>
        <?php
        } else {
        ?>
            <script>
                swal("Error", "Product Not Added!", "error")
                    .then((value) => {
                        window.location = '<?php echo SUP_ADM_DIR ?>add-products.php';
                    });
            </script>
        <?php
        }


    //     if ($addProducts === true) {
    //     ?>
    //         <script>
    //             swal("Success", "Product Added!", "success")
    //                 .then((value) => {
    //                     window.location = '<?php echo SUP_ADM_DIR ?>_config/form-submission/add-new-product.php';
    //                 });
    //         </script>
    //     <?php
    //     } else {
    //     ?>
    //         <script>
    //             swal("Error", "Product Not Added!", "error")
    //                 .then((value) => {
    //                     window.location = '<?php echo SUP_ADM_DIR ?>_config/form-submission/add-new-product.php';
    //                 });
    //         </script>
    // <?php
    //     }
    }

    ?>

</body>

</html>