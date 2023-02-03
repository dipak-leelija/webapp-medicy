<?php
require_once '../../php_control/stockReturn.class.php';

$StockReturn = new StockReturn();

// echo var_dump($_POST);exit;

if (isset($_POST["id"])) {
    $statusValue = "cancelled";
    $updated = $StockReturn->stockReturnStatus($_POST['id'], $statusValue);
    if ($updated == 1) {
        echo 1;
    }else{
        echo 0;
    }
}





?>