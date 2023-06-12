<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock = new CurrentStock();

//=================== CHECKING STOCK EXISTANCE ===================
if (isset($_GET["id"])) {
    $stock = $CurrentStock->checkStockExist($_GET["id"]);
    if ($stock != NULL) {
        echo 1;
    }else {
        echo 0;
    }
}
// echo "Hi";

?>