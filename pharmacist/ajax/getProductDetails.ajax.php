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
    // echo $stock[0]['mrp'];
}
        //------- MRP CHECK ON PACK OR LOOSE --------

        if (isset($_GET["crntStockMrp"])) {
            $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["crntStockMrp"], $_GET["batchNo"]);
            echo $stock[0]['mrp'];
        }

        // ---------- loose mrp check ---------------
        if (isset($_GET["crntStockLoosePrice"])) {
            $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["crntStockLoosePrice"], $_GET["batchNo"]);
                echo $stock[0]['loosely_price'];
                // print_r($stock);
        }
//=========================== end of mrp access ===========================


// ======================= PTR ACCESS FROM CURRENT STOCK =====================

if (isset($_GET["stockptr"])) {
    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["stockptr"], $_GET["batchNo"]);
        echo $stock[0]['ptr'];
        // print_r($stock);
}

// ============ CURRENT STOCK ITEM LOOSE STOCK CHEK BLOCK ===============
if (isset($_GET["stockmrp"])) {
    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["stockmrp"], $_GET["batchNo"]);
    echo $stock[0]['mrp'];
}


// ========================== CURRENT STOCK ITEM LOOSE STOCK CHECKING =============================
if (isset($_GET["loosePack"])) {
    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($_GET["loosePack"], $_GET["batchNo"]);
    if ($stock) {
        echo $stock[0]['loosely_count'];
        // print_r($stock);
    }
}

?>