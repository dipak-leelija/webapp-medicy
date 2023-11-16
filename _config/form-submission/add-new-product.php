<?php

require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';

$Products       = new Products();
$ProductImages  = new ProductImages();
$Unit           = new MeasureOfUnits();
$Session        = new SessionHandler();

?>

<?php
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
    <div>
    </div>
    <?php

    if (isset($_POST['add-product'])) {


        // print_r($_FILES);
        // echo "<br><br>";
        $imageName         = $_FILES['img-files']['name'];
        $tempImgName       = $_FILES['img-files']['tmp_name'];
        $imageArrayCaount = count($imageName);
        $tempImageNameArrayCaount = count($tempImgName);


        $productName        = $_POST['product-name'];
        $productName        = addslashes($productName);

        $productComposition        = $_POST['product-composition'];
        $productComposition        = addslashes($productComposition);

        $power              = $_POST['medicine-power'];
        $manufacturerid     = $_POST['manufacturer'];


        $weatage            = $_POST['unit-quantity'];
        $unit               = $_POST['unit'];
        $unitType = $Unit->showMeasureOfUnitsById($unit);
        $unitName = $unitType[0]['short_name'];

        $packagingType      = $_POST['packaging-type'];
        $mrp                = $_POST['mrp'];
        $gst                = $_POST['gst'];

        $productDsc         = $_POST['product-descreption'];
        $productDsc         = addslashes($productDsc);

        $addedBy            = $employeeId;
        $addedOn            = NOW;
        //ProductId Generation
        $randNum = rand(1, 999999999999);
        $productId = 'PR' . $randNum;


        //Insert into products table of DB
        $addProducts = $Products->addProducts($productId, $manufacturerid, $productName, $productComposition, $power, $productDsc, $packagingType, $weatage, $unit, $unitName, $mrp, $gst, $addedBy, $addedOn, $adminId);

        if ($addProducts === true) {

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
                $tempImgname   = $tempImgName[$j];


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

                        move_uploaded_file($tempImgname, $imgFolder);
                        $image         = addslashes($image);
                    }

                    if ($image == '') {
                        $image = '';
                    }

                    $setPriority = '';

                    $addImage = $ProductImages->addImages($productId, $image, $setPriority, $addedBy, $addedOn, $adminId);
                } else {
                    $addImage = true;
                }
            }

            if ($addImage === true) {
    ?>
                <script>
                    swal("Success", "Product Added!", "success")
                        .then((value) => {
                            window.location = '<?php echo LOCAL_DIR ?>add-products.php';
                        });
                </script>
            <?php
            } else {
            ?>
                <script>
                    swal("Error", "Image Not Added!", "error")
                        .then((value) => {
                            window.location = '<?php echo LOCAL_DIR ?>add-products.php';
                        });
                </script>
            <?php
            }
        } else {
            ?>
            <script>
                swal("Error", "Product Insertion Faield!", "error")
                    .then((value) => {
                        window.location = '<?php echo LOCAL_DIR ?>add-products.php';
                    });
            </script>
    <?php
        }
    }

    ?>

</body>

</html>