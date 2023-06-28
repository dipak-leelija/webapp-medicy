<?php
require_once '../../php_control/stockReturn.class.php';
require_once '../../php_control/currentStock.class.php';

$StockReturn = new StockReturn();
$CurrentStock = new CurrentStock();

// echo var_dump($_POST);exit;

if (isset($_POST["id"])) {

    $returnId = $_POST['id'];
    $statusValue = "cancelled";
    $updated = $StockReturn->stockReturnStatus($returnId, $statusValue);

    $StockReturnData = $StockReturn->showStockReturnDetails($returnId);
    foreach($StockReturnData as $stock){
        $stokInId = $stock['stokIn_details_id'];
        $returnQTY = $stock['return_qty'];
        $returnFQTY = $stock['return_free_qty'];
        $totalReturnQTY = intval($returnQTY) + intval($returnFQTY);
    
        $stockCheck = $CurrentStock->showCurrentStockbyStokInId($stokInId);
        foreach($stockCheck as $stock){
            $currentStockQTY = $stock['qty'];
            $currentStockLQTY = $stock['loosely_count'];
            $currentStockWeightage = $stock['weightage'];
            $currentStockUnit = $stock['unit'];
        }

        if($currentStockUnit == 'tab' || $currentStockUnit == 'cap'){
            $updatedQTY = $currentStockQTY + $totalReturnQTY;
            $updatedFQTY = intval($currentStockLQTY) + (intval($updatedQTY) * intval($currentStockWeightage));
        }
        
        if($currentStockUnit != 'tab' || $currentStockUnit != 'cap'){
            $updatedQTY = $currentStockQTY + $totalReturnQTY;
            $updatedFQTY = 0;
        }

        $updateCurretnStock = $CurrentStock->updateStockBStockDetialsId($stokInId, $updatedQTY, $updatedFQTY);

    }

    if ($updateCurretnStock == true) {
        echo 1;
    }else{
        echo 0;
    }
}

?>