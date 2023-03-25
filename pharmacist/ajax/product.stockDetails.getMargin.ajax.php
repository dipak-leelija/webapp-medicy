<?php

require_once '../../php_control/stockInDetails.class.php';

$StockInDetails = new StockInDetails();

if (isset($_GET["Pid"])) {

    $productId  = $_GET["Pid"];
    $batchNo    = $_GET["Bid"];
    $qtyTyp     = $_GET["qtype"];
    $mrp        = $_GET["Mrp"];
    $qty        = $_GET["Qty"];
    $discPrice  = $_GET["Dprice"];


    $stockInMargin = $StockInDetails->stockDistributorBillNo($batchNo, $productId);

    //print_r($stockInMargin);

    //echo "<br>$productId<br>$batchNo<br>$qtyTyp<br>$mrp<br>$qty<br>$discPrice<br>";

    if($qtyTyp == 'Loose'){
        $ptr = $stockInMargin[0]['ptr'];
        $ptr = $ptr / $stockInMargin[0]['weightage'];
        $margin = ($discPrice * $qty) - ($ptr * $qty);
    }else{
        $ptr = $stockInMargin[0]['ptr'];
        $margin = ($discPrice * $qty) - ($ptr * $qty);
    }
    
    echo $margin;
}

?>