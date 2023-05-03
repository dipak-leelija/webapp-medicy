<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock = new CurrentStock();

if (isset($_GET['product']) && isset($_GET['batch'])) {
    $batchNo =  $batchNo = $_GET['batch'];
    $productId = $_GET['product'];

    $currentQty = $CurrentStock->checkStock($productId, $batchNo);
    if($currentQty == null){
        $qty = 0;
    }else{
        $qty = $currentQty[0]['qty'];
    }
    echo $qty;
}

?>