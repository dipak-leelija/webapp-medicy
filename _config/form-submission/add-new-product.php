<?php
// echo dirname(dirname(__DIR__)) . '/config/constant.php';
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';

$Products      = new Products();
$ProductImages = new ProductImages();
$Unit = new MeasureOfUnits();
$Session = new SessionHandler();

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
        $hsnoNumber = $_POST['hsno-number'];
        $prodCategory = $_POST['product-catagory'];
        $medicinePower = $_POST['medicine-power'];
        $qantityUnit = $_POST['qantity-unit'];
        $packegingUnit = $_POST['unit'];
        $packegingType = $_POST['packeging-type'];
        $mrp = $_POST['mrp'];
        $gst = $_POST['gst'];

        $addedBy            = $employeeId;

        //ProductId Generation
        $randNum = rand(1, 999999999999);
        $productId = 'PR' . $randNum;

        // echo "<br>PRODUCT ID : $productId";
        // echo "<br>PRODUCT NAME : $prodName";
        // echo "<br>PRODUCT HSNO NUMBER : $hsnoNumber";
        // echo "<br>PRODUCT CATAGORY : $prodCategory";
        // echo "<br>PRODUCT POWER : $medicinePower";
        // echo "<br>PRODUCT UNIT : $qantityUnit";
        // echo "<br>PRODUCT UNIT TYPE : $packegingUnit";
        // echo "<br>PRODUCT PACKAGING TYPE : $packegingType";
        // echo "<br>MRP : $mrp";
        // echo "<br>GST : $gst<br>";

        //Insert into products table 
        $addProducts = $Products->addProductByUser($productId, $prodName, $hsnoNumber, $prodCategory, $medicinePower, $qantityUnit, $packegingUnit, $packegingType, $mrp, $gst, $employeeId, NOW, $adminId);

        // print_r($addProducts);

        if ($addProducts === true) {

            for ($j = 0; $j < $imageArrayCaount && $j < $tempImageArrayCaount; $j++) {
                ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PRIFIX \\\\\\\\\\\\\

                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for ($i = 0; $i < 9; $i++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                $randomString = $randomString;

                ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                //===== Main Image 
                $imageNm        = $imagesName[$j];
                $tempImgname   = $tempImgsName[$j];

                // $ImgNm = '';
                // $extention = '';
                // $countImageLen = 0;


                if ($image != '') {
                    if ($image != null) {
                        if (file_exists(ROOT_DIR."images/product-image/" . $randomString . '_' . $image)) {
                            $image = 'medicy-' . $randomString . $image;
                        }
                    }


                    // $countImageLen = strlen($image);
                    // for($l=0; $l<intval($countImageLen)-4; $l++){
                    //     $ImgNm .= $image[$l];
                    // }

                    // for($k=intval($countImageLen)-4; $k<$countImageLen; $k++){
                    //     $extention .= $image[$k];
                    // }

                    $image         = $imageNm . '-' . $randomString . $extention;
                    $imgFolder     = ROOT_DIR."images/product-image/" . $image;

                    move_uploaded_file($tempImgname, $imgFolder);
                    $image         = addslashes($image);
                }

                if ($image == '') {
                    $image = '';
                }

                $addImage = $ProductImages->addImages($productId, $image, $addedBy, NOW, $adminId);
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