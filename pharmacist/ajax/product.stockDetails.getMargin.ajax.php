<?php

require_once '../../php_control/stockInDetails.class.php';
require_once '../../php_control/currentStock.class.php';

$StockInDetails = new StockInDetails();
$currentStock = new CurrentStock();

if (isset($_GET["Pid"])) {

    $productId  = $_GET["Pid"];
    $batchNo    = $_GET["Bid"];
    $qtyTyp     = $_GET["qtype"];
    $mrp        = $_GET["Mrp"];
    $qty        = $_GET["Qty"];
    $discPrice  = $_GET["Dprice"];

    // echo "<br>Product id : $productId<br>Batch no : $batchNo<br>Qantity Type : $qtyTyp<br>MRP : $mrp<br>Qantity : $qty<br>Discounted Price : $discPrice<br>";

    $stockInMargin = $currentStock->checkStock($productId, $batchNo);

    // print_r($stockInMargin);

    if($mrp == null || $qty == null || $discPrice == null){
        $mrp = 0;
        $qty = 0;
        $discPrice = 0;
    }

    $mrp = floatval($mrp);
    $qty = intval($qty);
    $discPrice = floatval($discPrice);

    //echo "<br>$mrp<br>$qty<br>$discPrice<br><br>";

    if($qtyTyp == ''){
        $ptr = $stockInMargin[0]['ptr'];
        $margin = ($discPrice * $qty) - ($ptr * $qty);
    }else{
        $ptr = $stockInMargin[0]['ptr'];
        $ptr = $ptr / $stockInMargin[0]['weightage'];
        $margin = ($discPrice * $qty) - ($ptr * $qty);
    }
    echo number_format($margin,2);
}

?>