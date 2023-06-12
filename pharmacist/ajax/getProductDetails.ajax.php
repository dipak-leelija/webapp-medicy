<?php
require_once '../../php_control/products.class.php';
require_once '../../php_control/currentStock.class.php';


$Products       = new Products();
$CurrentStock = new CurrentStock();


// ================ get product name =========================
if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);
    echo $showProducts[0]['name'];
}
// echo "Hi";


if (isset($_GET["Pid"])) {
    $productDetails = $Products->showProductsById($_GET["Pid"]);
    echo ($_GET["Pid"]);
}

// ===================== PRODUCT WEIGHTAGE ======================

if (isset($_GET["weightage"])) {
    $productId = $_GET["weightage"];
    $showProducts = $Products->showProductsById($productId);
    if ($showProducts) {
        echo $showProducts[0]['unit_quantity'];
    }
}

// ============== UNIT ====================

if (isset($_GET["unit"])) {
    $prodId = $_GET["unit"];
    $showProducts = $Products->showProductsById($prodId);
    echo $showProducts[0]['unit']; 
}


// ======== get curretn stock expiary date =========

if (isset($_GET["exp"])) {
    $stock = $CurrentStock->showCurrentStocByPId($_GET["exp"]);
    echo $stock[0]['exp_date'];
    // print_r($stock);
}


?>