<?php

require_once realpath(dirname(dirname(__DIR__)). '/config/constant.php');
require_once SUP_ADM_DIR.'_config/sessionCheck.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR."products.class.php";
require_once CLASS_DIR."request.class.php";
require_once CLASS_DIR."packagingUnit.class.php";
require_once CLASS_DIR."productsImages.class.php";
require_once CLASS_DIR."manufacturer.class.php";
require_once CLASS_DIR."currentStock.class.php";

$Products       = new Products();
$CurrentStock   = new CurrentStock();


$Request       = new Request();
$ProductImages = new ProductImages;


// $productTableId = $_POST['id'];
$productId      = $_POST['productId'];

$deleteProduct  = $Request->deleteRequest($productId);
$deleteProductImg = $ProductImages->deleteImageByPID($productId);


if ($deleteProduct && $deleteProductImg) {
    echo 1;
}else {
    echo 0;
}


?>