<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock = new CurrentStock();

if (isset($_GET['product']) && isset($_GET['batch'])) {
    $batchNo =  $batchNo = $_GET['batch'];
    $productId = $_GET['product'];

    $currentQty = $CurrentStock->checkStock($productId, $batchNo);
    echo $currentQty[0]['qty'];

}

?>