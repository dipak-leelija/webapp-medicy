<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock = new CurrentStock();

if (isset($_GET['currentQTY'])) {
    $stockInDetailsId = $_GET['currentQTY'];

    $currentQty = $CurrentStock->showCurrentStocByStokInDetialsId($stockInDetailsId);
    if($currentQty == null){
        $qty = 0;
    }else{
        $qty = $currentQty[0]['qty'];
    }
    echo $qty;
}

?>