
<?php

require_once '../../php_control/stockInDetails.class.php';
require_once '../../php_control/currentStock.class.php';

$StockInDetails = new StockInDetails();
$currentStock = new CurrentStock();


//======================== QUANTITY CALCULETION =========================

if (isset($_GET["qtyId"])) {
    
    $productId  = $_GET["qtyId"];
    $batchNo    = $_GET["Bid"];

    $stockInQantity = $currentStock->checkStock($productId, $batchNo);
    echo $stockInQantity[0]['qty'];

}
?>