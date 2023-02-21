<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/currentStock.class.php';

$CurrentStock = new CurrentStock();

    $productId  = $_POST['Currentid'];
    $batchNo    = $_POST['bachElemId'];


    $CurrentStockDelete = $CurrentStock -> deleteCurrentStock($productId, $batchNo);

    if($CurrentStockDelete){
        echo 1;
    }else{
        echo 0;
    }


?>