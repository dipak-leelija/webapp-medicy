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

    if (isset($_POST['update-new-product-data'])) {


        echo "hello";

        


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
        // $addProducts = $Products->addProductByUser($productId, $prodName, $hsnoNumber, $prodCategory, $medicinePower, $qantityUnit, $packegingUnit, $packegingType, $mrp, $gst, $employeeId, NOW, $adminId);

        $addProducts = true;

        if ($addProducts === true) {
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