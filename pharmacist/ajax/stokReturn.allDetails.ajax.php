<?php
require_once '../../php_control/stockReturn.class.php';
require_once '../../php_control/stockInDetails.class.php';

$StokReturnDetails = new StockReturn();
$StokInDetails = new StockInDetails();


// ===================== CURRENT PURCHASE QTY ======================

if (isset($_GET['current-stock-qty'])) {
    $stockInDetailsId = $_GET['current-stock-qty'];

    $stokInDetails = $StokInDetails->showStockInDetailsByStokinId($stockInDetailsId);
    $purchaseQty = $stokInDetails[0]['qty'];

    $stockReturnDetails = $StokReturnDetails->showStockReturnDataByStokinId($stockInDetailsId);
    // print_r($stockReturnDetails);

    if($stockReturnDetails == null){
        $ReturnQty = 0;
    }else{
        $ReturnQty = $stockReturnDetails[0]['return_qty'];
    }
    echo ($purchaseQty - $ReturnQty);
}



// ======================== CURRENT FREE QTY ======================

if (isset($_GET['current-free-qty'])) {
    $stockInDetailsId = $_GET['current-free-qty'];

    $stokInDetails = $StokInDetails->showStockInDetailsByStokinId($stockInDetailsId);
    $purchaseFQty = $stokInDetails[0]['free_qty'];

    $stockReturnDetails = $StokReturnDetails->showStockReturnDataByStokinId($stockInDetailsId);
    // print_r($stockReturnDetails);

    if($stockReturnDetails == null){
        $ReturnFQty = 0;
    }else{
        $ReturnFQty = $stockReturnDetails[0]['return_free_qty'];
    }
    echo ($purchaseFQty - $ReturnFQty);
}

?>