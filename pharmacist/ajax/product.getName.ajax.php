<?php
require_once '../../php_control/products.class.php';

$Products       = new Products();

if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);
    echo $showProducts[0]['name'];
}
// echo "Hi";


if (isset($_GET["Pid"])) {

    $productDetails = $Products->showProductsById($_GET["Pid"]);
    echo ($_GET["Pid"]);
}
?>