<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/stockIn.class.php';

$Stockin = new StockIn();

    $stockId  = $_POST['Currentid'];

    $stockDelete = $Stockin->deleteStock($stockId);

    if($stockDelete){
        echo 1;
    }else{
        echo 0;
    }


?>