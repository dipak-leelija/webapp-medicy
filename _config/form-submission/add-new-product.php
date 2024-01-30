<?php
// echo dirname(dirname(__DIR__)) . '/config/constant.php';
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'request.class.php';


$Products      = new Products();
$ProductImages = new ProductImages();
$Unit = new MeasureOfUnits();
$Session = new SessionHandler();
$Request = new Request;



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

    <?php

    if (isset($_POST['add-new-product'])) {

        $imagesName           = $_FILES['img-files']['name'];
        $tempImgsName       = $_FILES['img-files']['tmp_name'];
        // print_r($tempImgsName);
        $imageArrayCaount = count($imagesName);
        $tempImageArrayCaount = count($tempImgsName);


        $prodName = $_POST['product-name'];
        $prodCategory = $_POST['product-catagory'];
        $packegingType = $_POST['packeging-type'];
        $qantity        = $_POST['qantity'];
        $packegingUnit = $_POST['unit'];
        $medicinePower = $_POST['medicine-power'];
        $mrp = $_POST['mrp'];
        $gst = $_POST['gst'];
        $hsnoNumber = $_POST['hsno-number'];

        $description = 'New Product Request';

        $randNum = rand(1, 999999999999);
        $productId = 'PR' . $randNum;

        $status = 0;

        // echo "<br>PRODUCT ID : $productId";
        // echo "<br>PRODUCT NAME : $prodName";
        // echo "<br>PRODUCT CATAGORY : $prodCategory";
        // echo "<br>PRODUCT PACKAGING TYPE : $packegingType";
        // echo "<br>PRODUCT UNIT : $qantity";
        // echo "<br>PRODUCT UNIT TYPE : $packegingUnit";
        // echo "<br>PRODUCT POWER : $medicinePower";
        // echo "<br>MRP : $mrp";
        // echo "<br>GST : $gst";
        // echo "<br>PRODUCT HSNO NUMBER : $hsnoNumber<br><br>";



        //Insert into request table 

        $addProductRequest = $Request->addNewProductRequest($productId, $prodName, $prodCategory, $packegingType,  $qantity, $packegingUnit, $medicinePower, $mrp, $gst, $hsnoNumber, $description, $employeeId, NOW, $adminId, $status);


        // print_r($addProducts);

        $addProductRequest = true;

        if ($addProductRequest === true) {

            for ($i = 0, $j = 0; $i < $imageArrayCaount && $j < $tempImageArrayCaount; $i++, $j++) {
                ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PRIFIX \\\\\\\\\\\\\
                $imgStatus = 0;

                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';

                for ($k = 0; $k < 9; $k++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                //===== Main Image 
                $image          = $imagesName[$i];
                $tempImage        = $tempImgsName[$j];


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

                if($image != null){
                    $addImagesRequest = $Request->addImageRequest($productId, $image, $addedBy, NOW, $adminId, $imgStatus);
                } else {
                    $addImagesRequest = true;
                }
            }

    ?>
            <script>
                swal("Success", "Product Added!", "success")
                    .then((value) => {
                        window.location = '<?php echo LOCAL_DIR ?>add-new-product.php';
                    });
            </script>
        <?php
        } else {
        ?>
            <script>
                swal("Error", "Product Not Added!", "error")
                    .then((value) => {
                        window.location = '<?php echo LOCAL_DIR ?>add-new-product.php';
                    });
            </script>
    <?php
        }
    }

    ?>

</body>

</html>