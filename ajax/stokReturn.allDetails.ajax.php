<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'currentStock.class.php';

$StokReturnDetails = new StockReturn();
$StokInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();

// ===================== CURRENT PURCHASE QTY ======================

if (isset($_GET['current-stock-qty'])) {

    $stockInDetailsId = $_GET['current-stock-qty'];
    // echo $stockInDetailsId;
    $stokInDetails = $StokInDetails->showStockInDetailsByStokinId($stockInDetailsId);
    $purchaseQty = $stokInDetails[0]['qty'];
    $freeQty = $stokInDetails[0]['free_qty'];
    // echo $freeQty;
    $stockReturnDetails = json_decode($StokReturnDetails->showStockReturnDataByStokinId($stockInDetailsId));
    // print_r($stockReturnDetails);
    $totalRtnQty = 0;
    if($stockReturnDetails->status){
        $stockReturnDetails = $stockReturnDetails->data;
        foreach($stockReturnDetails as $stockReturnDetails){
            $ReturnQty = $stockReturnDetails->return_qty;
            // $ReturnFQty = $stockReturnDetails->return_free_qty;
           
            $totalRtnQty = intval($totalRtnQty) + intval($ReturnQty) ;
            // echo $totalRtnQty; echo "<br>";
        }
        
    }else{
        $ReturnQty = 0;
        $ReturnFQty = 0;
    }

    $currentData = json_decode($CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId));
    // print_r($currentData);
    echo ($currentData->qty - $totalRtnQty);
    // echo $stockInDetailsId;
}


// ======================== CURRENT FREE QTY ======================

if (isset($_GET['current-free-qty'])) {
    $stockInDetailsId = $_GET['current-free-qty'];

    $stokInDetails = $StokInDetails->showStockInDetailsByStokinId($stockInDetailsId);
    $purchaseFQty = $stokInDetails[0]['free_qty'];

    $stockReturnDetails = json_decode($StokReturnDetails->showStockReturnDataByStokinId($stockInDetailsId));
    // print_r($stockReturnDetails);
    // $currentData = $CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId);

    if($stockReturnDetails->status){
        $stockReturnDetails = $stockReturnDetails->data;
        $ReturnFQty = 0;
        foreach($stockReturnDetails as $stockReturnDetails){
            $ReturnFQty = intval($ReturnFQty) + intval($stockReturnDetails->return_free_qty);
        }
    }else{
        $ReturnFQty = 0;
    }

    echo ($purchaseFQty - $ReturnFQty);
}

?>