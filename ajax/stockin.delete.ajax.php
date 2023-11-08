<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';

require_once CLASS_DIR.'stockIn.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'currentStock.class.php';

$Stockin = new StockIn();
$StockInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();


    $stockInId  = $_POST['DeleteId'];

    $selectStockInDetails = $StockInDetails->showStockInDetailsByStokId($stockInId);
    
    foreach($selectStockInDetails as $stockInDetails){
        $stockInDetailsId = $stockInDetails['id'];
        // echo "<br>$stockInDetailsId";
       
        $table = 'stock_in_details_id';
        $deleteFromCurrentStock = $CurrentStock->deleteByTabelData($table, $stockInDetailsId);
    }

    foreach($selectStockInDetails as $stockInDetails){
        $stockInDetailsId = $stockInDetails['id'];
        // echo "<br>$stockInDetailsId";
        $deleteStockInDetails = $StockInDetails->stockInDeletebyDetailsId($stockInDetailsId);             
    }
    

    if($deleteFromCurrentStock == true && $deleteStockInDetails == true){
        // echo "<br>$stockInId";
        $deleteFromStockIn = $Stockin->deleteStock($stockInId); 
    }

    
    if($deleteFromStockIn == true){
        echo 1;
    }else{
        echo 0;
    }
