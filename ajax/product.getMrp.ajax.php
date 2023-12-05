<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'currentStock.class.php';


$Products       = new Products();
$CurrentStock = new CurrentStock();



if (isset($_GET["id"])) {
    $showProducts = json_decode($Products->showProductsById($_GET["id"]));
    $showProducts = $showProducts->data;

    echo $showProducts[0]->mrp;
}

if (isset($_GET["stockmrp"])) {
    $stock = $CurrentStock->showCurrentStocByPId($_GET["stockmrp"]);
    echo $stock[0]['mrp'];
}


// =============== ptr check =====================

if (isset($_GET["ptrChk"])) {
    $showProducts = json_decode($Products->showProductsById($_GET["ptrChk"]));
    $showProducts = $showProducts->data;

    $mrp = $showProducts[0]->mrp;
    $gst = $showProducts[0]->gst;

    $maxptr = ($mrp*100)/($gst+100);
    $maxptr = floatval($maxptr);
    $maxptr = round($maxptr,2);
    echo $maxptr;
}
?>