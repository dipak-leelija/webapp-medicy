<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR."dbconnect.php";
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'itemUnit.class.php';
require_once CLASS_DIR.'currentStock.class.php';


$Products       = new Products();
$ItemUnit       = new itemUnit();
$CurrentStock   = new CurrentStock();


// ================ get product name =========================
if (isset($_GET["id"])) {
    $showProducts = $Products->showProductsByIdOnUser($_GET["id"], $adminId);
    $showProductsData = json_decode($showProducts,true);
    // print_r($showProductsData);
    if($showProductsData['status']){
        $productData  = $showProductsData['data'];
        $showProducts = $productData[0]['name'];
    }else{
        $showProducts = 'Data Not Found';
    }
    echo $showProducts;
}
// echo "Hi";


if (isset($_GET["Pid"])) {
    // $productDetails = $Products->showProductsById($_GET["Pid"]);
    echo ($_GET["Pid"]);
}

// ===================== PRODUCT WEIGHTAGE ======================

if (isset($_GET["itemWeightage"])) {
    $productId = $_GET["itemWeightage"];
    $showProducts = json_decode($Products->showProductsByIdOnUser($productId, $adminId));
    
    // print_r($showProducts);

    if($showProducts->status){
        $productData  = $showProducts->data;
        $showProducts = $productData[0]->unit_quantity;
    }else{
        $showProducts = 'No Data Found'; 
    }
    // if ($showProducts) {
        // echo $showProducts[0]['unit_quantity'];
    // }
    echo $showProducts;
}

// ============== UNIT ====================

if (isset($_GET["itemUnit"])) {
    $prodId = $_GET["itemUnit"];
    $showProducts = json_decode($Products->showProductsByIdOnUser($prodId, $adminId));
    
    if ($showProducts->status) {
        $productData = $showProducts->data;
        
        echo $ItemUnit->itemUnitName($productData[0]->unit);
    }else{
        echo 'Product Unit Not Found';
    }
    // echo $ItemUnit->itemUnitName($showProducts[0]['unit']);
    // echo $showProducts[0]['unit'];
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
        if ($stock['unit'] == 'tablets' || $stock['unit'] == 'capsules') {
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

    $allowedUnits = ["tablets", "tablet", "capsules", "capsule"];

    foreach($stock as $stock){
        // print_r($stock);
        if (in_array(strtolower($stock['unit']), $allowedUnits)) {
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
