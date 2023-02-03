<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock       = new CurrentStock();

if (isset($_GET["stockptr"])) {
    $stock = $CurrentStock->showCurrentStocByPId($_GET["stockptr"]);
        echo $stock[0]['ptr'];
        // print_r($stock);
}
// echo "Hi";

?>