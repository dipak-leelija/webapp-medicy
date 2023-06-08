<?php
require_once '../../php_control/stockInDetails.class.php';
$StockInDetails = new StockInDetails();


//get expiry date
if (isset($_GET["stock-exp"])) {
    // echo "error";
    $id = $_GET["stock-exp"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    echo $stock[0]['exp_date'];
    // print_r($stock);
}


//get waitage
if (isset($_GET["weightage"])) {
    $id = $_GET["weightage"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        echo $stock[0]['weightage'];
    }
}


//get unit
if (isset($_GET["unit"])) {
    $id = $_GET["unit"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        echo $stock[0]['unit'];
    }
}


//get mrp
if (isset($_GET["mrp"])) {
    $id = $_GET["mrp"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        echo $stock[0]['mrp'];
    }
}


//get gst
if (isset($_GET["gst"])) {
    $id = $_GET["gst"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        echo $stock[0]['gst'];
    }
}


//get ptr
if (isset($_GET["ptr"])) {
    $id = $_GET["ptr"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        echo $stock[0]['ptr'];
    }
}


//get discount
if (isset($_GET["discount"])) {
    $id = $_GET["discount"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        echo $stock[0]['discount'];
    }
}

//get taxableUrl
if (isset($_GET["taxableUrl"])) {
    $id = $_GET["taxableUrl"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        echo $stock[0]['gst_amount'];
    }
}


// get amount
if (isset($_GET["amount"])) {
    $id = $_GET["amount"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        echo $stock[0]['amount'];
    }
}


// get purchased qty
if (isset($_GET["purchased-qty"])) {
    $id = $_GET["purchased-qty"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        echo $stock[0]['qty'];
    }
}


// get free-qty
if (isset($_GET["free-qty"])) {
    $id = $_GET["free-qty"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        echo $stock[0]['free_qty'];
    }
}


//get net-buy-qty
if (isset($_GET["net-buy-qty"])) {
    $id = $_GET["net-buy-qty"];
    $stock = $StockInDetails->showStockInDetailsByStokinId($id);
    if ($stock > 0) {
        $fqty =  $stock[0]['free_qty'];
        $qty = $stock[0]['qty'];;
        echo $fqty+$qty;
    }
}

?>

