<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/stockIn.class.php';
require_once '../../php_control/stockInDetails.class.php';

$Stockin = new StockIn();
$stockInDetails = new StockInDetails();

    $stockId  = $_POST['Currentid'];

    $stockInDetailsDelete = $stockInDetails->stockInDeletebyId($stockId);

    if($stockInDetailsDelete == true){
        $stockDelete = $Stockin->deleteStock($stockId);
    }

    if($stockDelete == true){
        echo 1;
    }else{
        echo 0;
    }


?>