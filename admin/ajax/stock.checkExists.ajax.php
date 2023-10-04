<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock = new CurrentStock();

//=================== CHECKING STOCK EXISTANCE ===================
if (isset($_GET["Pid"])) {
    $prodId = $_GET["Pid"];
    $batchNo = $_GET["batchNo"];

    $stock = $CurrentStock->showCurrentStocByProductIdandBatchNo($prodId, $batchNo);
    if ($stock != NULL) {
        // print_r($stock);
        echo 1;
    }else {
        echo 0;
    }
}
// echo "Hi";

?>