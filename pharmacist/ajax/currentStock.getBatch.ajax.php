<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock = new CurrentStock();
if (isset($_GET["id"])) {
    $stock = $CurrentStock->showCurrentStocByPId($_GET["id"]);
    // print_r($stock);
    echo $stock[0]['batch_no'];
}
// echo "Hi";
?> 