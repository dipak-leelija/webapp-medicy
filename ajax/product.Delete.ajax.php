<?php

require_once dirname(__DIR__).'/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR.'_config/sessionCheck.php';

require_once CLASS_DIR."products.class.php";
require_once CLASS_DIR."packagingUnit.class.php";
require_once CLASS_DIR."productsImages.class.php";
require_once CLASS_DIR."manufacturer.class.php";
require_once CLASS_DIR."currentStock.class.php";

$Products       = new Products();
$CurrentStock   = new CurrentStock();


$Products       = new Products();


$productTableId = $_POST['id'];

$deleteProduct  = $Products->deleteProduct($productTableId);

if ($deleteProduct) {
    echo 1;
}else {
    echo 0;
}


?>