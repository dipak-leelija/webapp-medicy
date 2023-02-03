<?php
require_once '../../php_control/products.class.php';
require_once '../../php_control/stockInDetails.class.php';


$Products       = new Products();
$StockInDetails = new StockInDetails();


if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsById($_GET["id"]);
    echo $showProducts[0]['gst'];
}



if (isset($_GET["stockgst"])) {
    $showProducts = $StockInDetails->showStockInDetailsByPId($_GET["stockgst"]);
    echo $showProducts[0]['gst'];
}


?>