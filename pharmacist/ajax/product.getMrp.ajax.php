<?php
require_once '../../php_control/products.class.php';
require_once '../../php_control/currentStock.class.php';


$Products       = new Products();
$CurrentStock = new CurrentStock();



if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);
    echo $showProducts[0]['mrp'];
}

if (isset($_GET["stockmrp"])) {
    $stock = $CurrentStock->showCurrentStocByPId($_GET["stockmrp"]);
    echo $stock[0]['mrp'];
}
?>