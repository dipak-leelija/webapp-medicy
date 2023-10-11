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

    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["exp"], $_GET["batchNo"]);
    echo $stock[0]['exp_date'];
    // print_r($stock);
}

// ============== get MRP from current stock ==============

if (isset($_GET["stockmrp"])) {
    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["stockmrp"], $_GET["batchNo"]);
    echo $stock[0]['mrp'];
}

// ======================= PTR ACCESS FROM CURRENT STOCK =====================

if (isset($_GET["stockptr"])) {
    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["stockptr"], $_GET["batchNo"]);
    echo $stock[0]['ptr'];
    // print_r($stock);
}

// ============ CURRENT STOCK ITEM LOOSE STOCK CHEK BLOCK ===============
if (isset($_GET["looseStock"])) {
    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["looseStock"], $_GET["batchNo"]);
    foreach ($stock as $stock) {
        if ($stock['unit'] == 'tab' || $stock['unit'] == 'cap') {
            $looseCount = $stock['loosely_count'];
        } else {
            $looseCount = null;
        }
    }
    echo $looseCount;
}


// ========================== CURRENT STOCK ITEM LOOSE PRICE CHECKING =============================
// if (isset($_GET["loosePrice"])) {

//     $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["loosePrice"], $_GET["batchNo"]);
//     foreach($stock as $stock){
//         if ($stock['unit'] == 'tab' || $stock['unit'] == 'cap') {
//             $loosePrice = $stock['loosely_price'];
//         } else {
//             $loosePrice = null;
//         }
//     }
//     echo $loosePrice;
// }


// ========================== CURRENT STOCK AVAILIBILITY CHECK =============================
if (isset($_GET["availibility"])) {

    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["availibility"], $_GET["batchNo"]);
    foreach($stock as $stock){
        if ($stock['unit'] == 'tab' || $stock['unit'] == 'cap') {
            $availibility = $stock['loosely_count'];
        } else {
            $availibility = $stock['qty'];
        }
    }
    echo $availibility;
}

// ========================== CURRENT STOCK ITEM LOOSE STOCK CHECKING =============================
// if (isset($_GET["getTaxable"])) {
//     $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["getTaxable"], $_GET["batchNo"]);
//     // print_r($stock);
//     if ($stock) {
//         foreach($stock as $stock){
//             $mrp = $stock['mrp'];
//             $gstPercent = $stock['gst'];
//         }
//         $taxableAmount = ($mrp * 100)/(100+$gstPercent);
//     }
//     echo $taxableAmount;
// }