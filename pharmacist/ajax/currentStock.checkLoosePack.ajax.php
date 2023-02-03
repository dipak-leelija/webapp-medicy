<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock       = new CurrentStock();

if (isset($_GET["id"])) {
    $stock = $CurrentStock->showCurrentStocByPId($_GET["id"]);
    if ($stock) {
        echo $stock[0]['loosely_count'];
        // print_r($stock);
    }
}
// echo "Hi";

?>