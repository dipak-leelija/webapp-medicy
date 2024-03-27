<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'currentStock.class.php';

$StockInDetails = new StockInDetails();
$CurrentStock = new CurrentStock();

if (isset($_GET["Pid"])) {

    $productId      = $_GET["Pid"];
    $batchNo        = $_GET["Bid"];
    $qtyTyp         = $_GET["qtype"];
    // echo $qtyTyp;
    $mrp            = $_GET["Mrp"];
    $qty            = $_GET["Qty"];
    $discPercent    = $_GET["disc"];
    $currentStockId = $_GET['currentItemId'];

    // echo "<br>Product id : $productId<br>Batch no : $batchNo<br>Qantity Type : $qtyTyp<br>MRP : $mrp<br>Qantity : $qty<br>Discounted Price : $discPrice<br>";
    $col = 'id';
    $currentStockData = $CurrentStock->selectByColAndData($col, $currentStockId);
    // print_r($currentStockData);
    
    $stockInData = $StockInDetails->showStockInDetailsByStokinId($currentStockData[0]['stock_in_details_id']);
    // print_r($stockInData);
     

    if($mrp == null || $qty == null || $discPercent == null){
        $mrp = 0;
        $qty = 0;
        $disc = 0;
    }

    $mrp = floatval($mrp);
    $qty = intval($qty);
    $discPercent = floatval($discPercent);

    $discPrice = $mrp - ($mrp * $discPercent/100);

    //echo "<br>$mrp<br>$qty<br>$discPrice<br><br>";

    if($qtyTyp == 'others'){
        $base = $stockInData[0]['base'];
        $ptr = floatval($base) + (floatval($base)*($currentStockData[0]['gst']/100));
        $margin = ($discPrice * $qty) - ($ptr * $qty);
    }else{
        $base = $stockInData[0]['base'];
        $ptr = floatval($base) + (floatval($base)*($currentStockData[0]['gst']/100));
        $ptr = floatval($ptr) / intval($currentStockData[0]['weightage']);
        $discPrice = $discPrice / intval($currentStockData[0]['weightage']);
        $margin = ($discPrice * $qty) - ($ptr * $qty);
        // echo $margin;
    }
    echo number_format($margin,2);
}

?>