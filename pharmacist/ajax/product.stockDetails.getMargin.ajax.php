<?php

require_once '../../php_control/stockInDetails.class.php';

$StockInDetails = new StockInDetails();

if (isset($_GET["id"])) {
    $stockInMargin = $StockInDetails->showStockInMargin(($_GET["id"]));

    //   print_r($stockInMargin[0][0]);
    echo $stockInMargin[0]['margin'];

}

?>