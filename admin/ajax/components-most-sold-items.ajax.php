<?php

require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';

$Products   = new Products;

if (isset($_POST['prodId'])) {
    $productId =  $_POST['prodId'];
    $productId = json_decode($productId);
    $prodName = array();
    for($i = 0; $i<count($productId); $i++){
        $proData = $Products->showProductsById($productId[$i]);
        array_push($prodName, $proData[0]['name']);
    }
    $prodName = json_encode($prodName);
    echo $prodName;
}
?>