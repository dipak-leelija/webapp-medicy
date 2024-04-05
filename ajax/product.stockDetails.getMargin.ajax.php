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

        $stockInQty =  intval($stockInData[0]['qty']) + intval($stockInData[0]['free_qty']);
        $stockInAmount = $stockInData[0]['amount'];
        $perQtyStockInAmount = number_format(floatval($stockInAmount) / intval($stockInQty), 2);

        $sellAmount = (floatval($mrp) * intval($qty)) - ((floatval($mrp) * intval($qty)) * (intval($discPercent)/100));
        $margin = floatval($sellAmount) - (floatval($perQtyStockInAmount) * intval($qty));

    }else{

        $stockInQty =  $stockInData[0]['loosely_count'];
        $stockInAmount = $stockInData[0]['amount'];
        $perQtyStockInAmount = floatval($stockInAmount)/ intval($stockInQty);

        $sellAmount = ((floatval($mrp) / intval($stockInData[0]['weightage'])) * intval($qty)) - (((floatval($mrp) / intval($stockInData[0]['weightage'])) * intval($qty)) * (intval($discPercent)/100));
        $margin = floatval($sellAmount) - (floatval($perQtyStockInAmount) * intval($qty));
        
    }
    echo number_format($margin,2);
}

?>