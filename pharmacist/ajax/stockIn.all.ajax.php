<?php
require_once '../../php_control/stockInDetails.class.php';
$StockInDetails = new StockInDetails();


//get expiry date
if (isset($_GET["stock-exp"])) {
    // echo "error";
    $batchNo = $_GET["stock-exp"];
    $stock = $StockInDetails->showStockInByBatch($batchNo);
    echo $stock[0]['exp_date'];
    // print_r($stock);
}


//get waitage
if (isset($_GET["weightage"])) {
    $batchId = $_GET["weightage"];
    $stock = $StockInDetails->showStockInByBatch($batchId);
    if ($stock > 0) {
        echo $stock[0]['weightage'];
    }
}


//get unit
if (isset($_GET["unit"])) {
    $batchId = $_GET["unit"];
    $stock = $StockInDetails->showStockInByBatch($batchId);
    if ($stock > 0) {
        echo $stock[0]['unit'];
    }
}


//get mrp
if (isset($_GET["mrp"])) {
    $batchId = $_GET["mrp"];
    $stock = $StockInDetails->showStockInByBatch($batchId);
    if ($stock > 0) {
        echo $stock[0]['mrp'];
    }
}


//get gst
if (isset($_GET["gst"])) {
    $batchId = $_GET["gst"];
    $stock = $StockInDetails->showStockInByBatch($batchId);
    if ($stock > 0) {
        echo $stock[0]['gst'];
    }
}


//get ptr
if (isset($_GET["ptr"])) {
    $batchId = $_GET["ptr"];
    $stock = $StockInDetails->showStockInByBatch($batchId);
    if ($stock > 0) {
        echo $stock[0]['ptr'];
    }
}


//get discount
if (isset($_GET["discount"])) {
    $batchId = $_GET["discount"];
    $stock = $StockInDetails->showStockInByBatch($batchId);
    if ($stock > 0) {
        echo $stock[0]['discount'];
    }
}

//get taxableUrl
if (isset($_GET["taxableUrl"])) {
    $batchId = $_GET["taxableUrl"];
    $stock = $StockInDetails->showStockInByBatch($batchId);
    if ($stock > 0) {
        echo $stock[0]['gst_amount'];
    }
}


// get amount
if (isset($_GET["amount"])) {
    $batchId = $_GET["amount"];
    $stock = $StockInDetails->showStockInByBatch($batchId);
    if ($stock > 0) {
        echo $stock[0]['amount'];
    }
}


// get purchased qty
if (isset($_GET["purchased-qty"])) {
    $batchId = $_GET["purchased-qty"];
    $stock = $StockInDetails->showStockInByBatch($batchId);
    if ($stock > 0) {
        echo $stock[0]['qty'];
    }
}


// get free-qty
if (isset($_GET["free-qty"])) {
    $batchId = $_GET["free-qty"];
    $stock = $StockInDetails->showStockInByBatch($batchId);
    if ($stock > 0) {
        echo $stock[0]['free_qty'];
    }
}

?>

