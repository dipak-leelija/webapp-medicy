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


// =============== ptr check =====================

if (isset($_GET["ptrChk"])) {
    $showProducts = $Products->showProductsById($_GET["ptrChk"]);
    $mrp = $showProducts[0]['mrp'];
    $gst = $showProducts[0]['gst'];

    $maxptr = ($mrp*100)/($gst+100);
    $maxptr = number_format($maxptr, 2);
    
    echo $maxptr;
}
?>