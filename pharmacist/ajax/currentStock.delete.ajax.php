<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock = new CurrentStock();

    $productId  = $_POST['id'];
    $batchNo    = $_POST['batchNo'];


    
    $CurrentStockDelete = $CurrentStock -> deleteCurrentStock($productId, $batchNo);

    if($CurrentStockDelete){
        echo 1;
    }else{
        echo 0;
    }


?>