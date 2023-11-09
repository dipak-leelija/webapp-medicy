<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';


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