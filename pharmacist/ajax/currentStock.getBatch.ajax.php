<?php
require_once '../../php_control/currentStock.class.php';

$CurrentStock = new CurrentStock();
if (isset($_GET["id"])) {

    $pid = $_GET["id"];
    $productIds = $_GET["chkBtch"];
    $productIds = explode (",", $productIds);
    $count = 0;
    $flag =0;

    for($i = 0; $i<count($productIds); $i++){
        if($productIds[$i] == $pid){
            $count++;
        }
    }

    $count = $count - 1;

    $stock = $CurrentStock->showCurrentStocByPId($pid);
    
    echo $stock[$count]['batch_no'];
    
}
?> 