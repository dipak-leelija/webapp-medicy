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
        // echo "<br>";
        // print_r($tempImgName);
        // echo "<br>";
        // echo $imageArrayCaount;
        // echo "<br>";
        // echo $tempImageNameArrayCaount;
        
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
                for ($i = 0; $i < 9; $i++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                $randomString = $randomString;

                ////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                //===== Main Image 
                $image         = $imageName[$j];
                $tempImgname   = $tempImgName[$j];

                $ImgNm = '';
                $extention ='';
                $countImageLen = 0;
                

                // echo "<br>Checking image name on entry : $image";

                if($image != ''){
                    if ($image != null) {
                        if (file_exists("../../../images/product-image/".$randomString.'_'.$image)) {
                            $image = 'medicy-'.$randomString.$image;
                            echo "<br>if file exists : $image";
                        }
                    }

                    
                    $countImageLen = strlen($image);
                    for($l=0; $l<intval($countImageLen)-4; $l++){
                        $ImgNm .= $image[$l];
                    }
                    for($k=intval($countImageLen)-4; $k<$countImageLen; $k++){
                        $extention .= $image[$k];
                    }
                
                    $image         = $ImgNm.'-'.$randomString.$extention;
                    $imgFolder     = "../../../images/product-image/".$image;
                  
                    move_uploaded_file($tempImgname, $imgFolder);
                    $image         = addslashes($image);
                }

                if($image == ''){
                    $image = '';
                }

                // if($image == ''){
                //     // echo "<br>no images";
                //     if ($image != null) {
                //         if (file_exists("../../../images/product-image/".$image)) {
                //             $image = 'medicy-'.$randomString.$image;
                //             echo "<br>if file exists : $image";
                //         }
                //     }
    
                //     $image         = $image;
                //     $imgFolder     = "../../../images/product-image/".$image;
                  
                //     move_uploaded_file($tempImgname, $imgFolder);
                //     $image         = addslashes($image);
                // }

                // echo "<br>$productId";
                // echo "<br>$image";
                // echo "<br>$addedBy<br>";
                
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