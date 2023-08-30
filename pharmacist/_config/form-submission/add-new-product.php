<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <script src="../../../js/sweetAlert.min.js"></script>
</head>

<body>
    <?php
    require_once '../../../php_control/products.class.php';
    require_once '../../../php_control/productsImages.class.php';
    require_once '../../_config/sessionCheck.php';

    $Products      = new Products();
    $ProductImages = new ProductImages();
    $Session = new SessionHandler();


    if (isset($_POST['add-product'])) {

        // print_r($_FILES);
        // echo "<br><br>";
        $imageName         = $_FILES['img-files']['name'];
        $tempImgName   = $_FILES['img-files']['tmp_name'];
        $imageArrayCaount = count($imageName);
        $tempImageNameArrayCaount = count($tempImgName);
        // print_r($imageName);
        // echo "<br><br>";
        // print_r($tempImgName);
        // echo "<br><br>";
        // echo "<br>$imageArrayCaount<br>";
        // echo "<br>$tempImageNameArrayCaount<br>";

        $productName        = $_POST['product-name'];
        $productName        = addslashes($productName);

        $productComposition        = $_POST['product-composition'];
        $productComposition        = addslashes($productComposition);

        $power              = $_POST['medicine-power'];
        $manufacturerid     = $_POST['manufacturer'];


        $weatage            = $_POST['unit-quantity'];
        $unit               = $_POST['unit'];
        $packagingType      = $_POST['packaging-type'];
        $mrp                = $_POST['mrp'];
        $gst                = $_POST['gst'];

        $productDsc         = $_POST['product-descreption'];
        $productDsc         = addslashes($productDsc);

        $addedBy            = $_SESSION['employee_username'];

        // echo "<br>Product Name : $productName";
        // echo "<br>Product Composition : $productComposition";
        // echo "<br>Power : $power";
        // echo "<br>Manufacture id : $manufacturerid";
        // echo "<br>Product weatage : $weatage";
        // echo "<br>Product Unit : $unit";
        // echo "<br>Packaging Type : $packagingType";
        // echo "<br>MRP : $mrp";
        // echo "<br>GST parcent : $gst";
        // echo "<br>Product Description : $productDsc";
        // echo "<br>Added by : $addedBy";
        // echo "<br><br><br>";

        //ProductId Generation
        $randNum = rand(1, 999999999999);
        $productId = 'PR' . $randNum;

        //Insert into products table of DB
        $addProducts = $Products->addProducts($productId, $manufacturerid, $productName, $power, $productDsc, $packagingType, $weatage, $unit, $mrp, $gst, $productComposition);
        // $addProducts = TRUE;
        // IMAGE UPLOAD SECTION ====================
        if ($addProducts == TRUE) {

            for ($j = 0; $j<$imageArrayCaount && $j<$tempImageNameArrayCaount; $j++) {
                ////////// RANDOM 12DIGIT STRING GENERATOR FOR IMAGE NAME PRIFIX \\\\\\\\\\\\\

                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for ($i = 0; $i < 18; $i++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                $randomString = $randomString;

                ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                //===== Main Image 
                $image         = $imageName[$j];
                $tempImgname   = $tempImgName[$j];

                if ($image != null) {
                    if (file_exists("../../../images/product-image/".$randomString.'_'.$image)) {
                        $image = 'medicy-'.$randomString.$image;
                        echo "<br>if file exists : $image";
                    }
                }

                $image         = $randomString.'_'.$image;
                $imgFolder     = "../../../images/product-image/".$image;
              
                move_uploaded_file($tempImgname, $imgFolder);
                $image         = addslashes($image);
                
                // =========================================
                // echo "<br>product id on image : $productId";
                // echo "<br>image name : $image";
                // echo "<br>image temp name : $tempImgname";
                // echo "<br><br><br>";

                $addImage = $ProductImages->addImages($productId, $image, $addedBy);
            }

            if ($addImage == TRUE) {
    ?>
                <script>
                    swal("Success", "Product Added!", "success")
                        .then((value) => {
                            window.location = '../../add-products.php';
                        });
                </script>
            <?php
            } else {
            ?>
                <script>
                    swal("Error", "Image Not Added!", "error")
                        .then((value) => {
                            window.location = '../../add-products.php';
                        });
                </script>
            <?php
            }
        } else {
            ?>
            <script>
                swal("Error", "Product Insertion Faield!", "error")
                    .then((value) => {
                        window.location = '../../add-products.php';
                    });
            </script>
    <?php
        }
    }

    ?>

</body>

</html>