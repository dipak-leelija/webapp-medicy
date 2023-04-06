
<?php

require_once '../../php_control/stockInDetails.class.php';
require_once '../../php_control/currentStock.class.php';

$StockInDetails = new StockInDetails();
$currentStock = new CurrentStock();


//======================== QUANTITY CALCULETION =========================

if (isset($_GET["qtyId"])) {
    
    $productId  = $_GET["qtyId"];
    $batchNo    = $_GET["Bid"];

    echo "<br>$productId<br>$batchNo";

    // $stockInMargin = $currentStock->checkStock($productId, $batchNo);

    // //print_r($stockInMargin);

    // if($mrp == null || $qty == null || $discPrice == null){
    //     $mrp = 0;
    //     $qty = 0;
    //     $discPrice = 0;
    // }

    // $mrp = floatval($mrp);
    // $qty = intval($qty);
    // $discPrice = floatval($discPrice);

    // //echo "<br>$mrp<br>$qty<br>$discPrice<br><br>";

    // if($qtyTyp == 'Loose'){
    //     $ptr = $stockInMargin[0]['ptr'];
    //     $ptr = $ptr / $stockInMargin[0]['weightage'];
    //     $margin = ($discPrice * $qty) - ($ptr * $qty);
    // }else{
    //     $ptr = $stockInMargin[0]['ptr'];
    //     $margin = ($discPrice * $qty) - ($ptr * $qty);
    // }
    
    // echo number_format($margin,2);

}
?>