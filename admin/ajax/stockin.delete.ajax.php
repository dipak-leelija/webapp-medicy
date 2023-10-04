<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/stockIn.class.php';
require_once '../../php_control/stockInDetails.class.php';
require_once '../../php_control/currentStock.class.php';

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
