<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Prosuct</title>
    <script src="../../../js/sweetAlert.min.js"></script>
</head>
<body>

<?php
require_once '../../../php_control/products.class.php';
require_once '../../../php_control/productsImages.class.php';



$Products      = new Products();
$ProductImages = new ProductImages();

$defaultImage = "../../../images/ default_medicine.jpg";


if (isset($_POST['add-product'])) {

//   print_r($_POST);
//      ?><br><br><?php
//   print_r($_FILES);

    //===== Main Image 
    $image         = $_FILES['product-image']['name'];
    $tempImgname   = $_FILES['product-image']['tmp_name'];
    if (file_exists("../../../images/product-image/".$image)) {
        $image = 'medicy-'.$image;
    }

    $imgFolder     = "../../../images/product-image/".$image;
    move_uploaded_file($tempImgname, $imgFolder);
    $image         = str_replace("<", "&lt", $image);
    $image         = str_replace(">", "&gt", $image);
    $image         = str_replace("'", "&#39", $image);

    //===== Back Image 
    $backImage         = $_FILES['back-image']['name'];
    $tempBackImg       = $_FILES['back-image']['tmp_name'];
    if (file_exists("../../../images/product-image/".$backImage)) {
        $backImage = 'medicy-'.$backImage;
    }

    $imgFolder     = "../../../images/product-image/".$backImage;
    move_uploaded_file($tempBackImg, $imgFolder);
    $backImage         = str_replace("<", "&lt", $backImage);
    $backImage         = str_replace(">", "&gt", $backImage);
    $backImage         = str_replace("'", "&#39", $backImage);


     //===== Side Image 
     $sideImage         = $_FILES['side-image']['name'];
     $tempSideImg       = $_FILES['side-image']['tmp_name'];
     if (file_exists("../../../images/product-image/".$sideImage)) {
         $sideImage = 'medicy-'.$sideImage;
     }
 
     $imgFolder         = "../../../images/product-image/".$sideImage;
     move_uploaded_file($tempSideImg, $imgFolder);
     $sideImage         = str_replace("<", "&lt", $sideImage);
     $sideImage         = str_replace(">", "&gt", $sideImage);
     $sideImage         = str_replace("'", "&#39", $sideImage);



    $manufacturerid     = $_POST['manufacturer'];

    $productName        = $_POST['product-name'];
    $productName        = str_replace("<", "&lt", $productName);
    $productName        = str_replace(">", "&gt", $productName);
    $productName        = str_replace("'", "&#39", $productName);

    $productComposition        = $_POST['product-composition'];
    $productComposition        = str_replace("<", "&lt", $productComposition);
    $productComposition        = str_replace(">", "&gt", $productComposition);
    $productComposition        = str_replace("'", "&#39", $productComposition);

    $power              = $_POST['medicine-power'];

    $productDsc         = $_POST['product-descreption'];
    $productDsc         = str_replace("<", "&lt", $productDsc);
    $productDsc         = str_replace(">", "&gt", $productDsc);
    $productDsc         = str_replace("'", "&#39", $productDsc);


    $packagingType      = $_POST['packaging-type'];
    // $weatage            = $_POST['unit-quantity'];
    // $unit               = $_POST['unit'];
    $mrp                = $_POST['mrp'];
    $gst                = $_POST['gst'];

    $weatage            = $_POST['unit-quantity'];
    $unit               = $_POST['unit'];
    // $unit               = $weatage.' '.$unit;

    $addedBy            = '';

    //ProductId Generation
    $randNum = rand(1, 9999999999);
    $productId = 'PR'.$randNum;

    //Insert into products table of DB
    $addProducts = $Products->addProducts($productId, $manufacturerid, $productName, $power, $productDsc, $packagingType, $weatage, $unit, $mrp, $gst, $productComposition);
        if($addProducts == TRUE){
            $addImage = $ProductImages->addImage($productId, $image, $backImage, $sideImage, $addedBy);
            if ($addImage == TRUE) {
                ?>
                <script>
                swal("Success", "Product Added!", "success")
                    .then((value) => {
                        window.location= '../../add-products.php';
                    });
                </script>
                <?php
            }else{
                ?>
                <script>
                    swal("Error", "Image Not Added!", "error")
                        .then((value) => {
                        window.location= '../../add-products.php';
                });
             </script>
             <?php
            }
        }else{
            ?>
             <script>
            swal("Error", "Product Insertion Faield!", "error")
                .then((value) => {
                    window.location= '../../add-products.php';
                });
             </script>
             <?php
        }

}

?>
    
</body>
</html>