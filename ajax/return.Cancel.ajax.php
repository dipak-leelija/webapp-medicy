<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockReturn.class.php';
require_once CLASS_DIR.'currentStock.class.php';

$StockReturn = new StockReturn();
$CurrentStock = new CurrentStock();

// echo var_dump($_POST);exit;

if (isset($_POST["id"])) {

    $table = 'id';
    $cancelId = $_POST['id'];
    $statusValue = "cancelled";

    // check what the status is

    $checkStatus = $StockReturn->stockReturnFilter($table, $cancelId);

    if($checkStatus[0]['status'] != 'cancelled'){

        $updated = $StockReturn->stockReturnStatus($cancelId, $statusValue);

        $StockReturnData = $StockReturn->showStockReturnDetails($cancelId);
        foreach($StockReturnData as $stock){
            $stokInId = $stock['stokIn_details_id'];
            $returnQTY = $stock['return_qty'];
            $returnFQTY = $stock['return_free_qty'];
            $totalReturnQTY = intval($returnQTY) + intval($returnFQTY);
    
            $stockCheck = $CurrentStock->showCurrentStocByStokInDetialsId($stokInId);
            foreach($stockCheck as $currentStock){
                $currentStockQTY = $currentStock['qty'];
                $currentStockLQTY = $currentStock['loosely_count'];
                $currentStockWeightage = $currentStock['weightage'];
                $currentStockUnit = $currentStock['unit'];
            }

            if($currentStockUnit == 'tab' || $currentStockUnit == 'cap'){
                $updatedLQTY = intval($currentStockLQTY) + (intval($totalReturnQTY) * intval($currentStockWeightage));

                $updatedQTY = intdiv(intval($updatedLQTY), intval($currentStockWeightage));
            }else{
                $updatedQTY = intval($currentStockQTY) + intval($totalReturnQTY);
                $updatedFQTY = 0;
            }

            $updateCurretnStock = $CurrentStock->updateStockByStockInDetailsId($stokInId, $updatedQTY, $updatedLQTY); 
            // $updateCurretnStock = true;
        }

        if ($updateCurretnStock == true) {
            echo 1;
        }else{
            echo 0;
        }
    }else{
        echo 'cancel';
    }
}
