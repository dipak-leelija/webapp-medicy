<?php
require_once '../../php_control/currentStock.class.php';


$CurrentStock = new CurrentStock();
if (isset($_GET["id"])) {
        $stock = $CurrentStock->showCurrentStocByPId($_GET["id"]);
        echo $stock[0]['exp_date'];
        // print_r($stock);
     
}


?>