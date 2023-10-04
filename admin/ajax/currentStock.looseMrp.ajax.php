<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock       = new CurrentStock();

if (isset($_GET["id"])) {
    $stock = $CurrentStock->showCurrentStocByPId($_GET["id"]);
        echo $stock[0]['loosely_price'];
        // print_r($stock);
}
// echo "Hi";

?>