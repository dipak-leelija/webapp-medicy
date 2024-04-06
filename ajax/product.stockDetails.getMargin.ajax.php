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
    
    $mrp            = $_GET["Mrp"];
    $qty            = $_GET["Qty"];
    $discPercent    = $_GET["disc"];

    $taxableAmount  = $_GET["taxable"];
    $sellAmount     = $_GET['sellAmount'];

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

    if($qtyTyp == 'others'){

        $stockInQty =  intval($stockInData[0]['qty']) + intval($stockInData[0]['free_qty']);
        $stockInAmount = $stockInData[0]['amount'];
        $ptrPerItem = $stockInData[0]['ptr'];
        $perItemBasePrice = $stockInData[0]['base'];

        // per qantity stock in amount
        $perQtyStockInAmount = floatval($stockInAmount) / intval($stockInQty);

        // purchased gst amount
        $purchasedGstAmount = floatval($perItemBasePrice) * (floatval($stockInData[0]['gst'])/100);

        // sell gst calculation 
        $sellGstAmount =  floatval($taxableAmount) * (floatval($stockInData[0]['gst'])/100);

        // purchased amount on sell qty 
        $pAmntOnSellQty = (floatval($stockInAmount) / floatval($stockInQty)) * intval($qty);
        // echo $pAmntOnSellQty;

        // $margin = floatval($sellAmount) - (floatval($perQtyStockInAmount) * intval($qty)) - floatval($sellGstAmount);

        $margin = (floatval($sellAmount) - floatval($pAmntOnSellQty)) - (floatval($sellGstAmount) - floatval($purchasedGstAmount));

    }else{

        $stockInQty =  $stockInData[0]['loosely_count'];
        $stockInAmount = $stockInData[0]['amount'];
        $ptrPerItem = $stockInData[0]['ptr'];
        $perItemBasePrice = $stockInData[0]['base'];
        
        $perQtyStockInAmount = floatval($stockInAmount)/ intval($stockInQty);
        
        // purchased gst amount
        $purchasedGstAmount = floatval($perItemBasePrice) * (floatval($stockInData[0]['gst'])/100);

        // sell gst calculation 
        $sellGstAmount =  floatval($taxableAmount) * (floatval($stockInData[0]['gst'])/100);

        // purchased amount on sell qty 
        $pAmntOnSellQty = (floatval($stockInAmount) / floatval($stockInQty)) * intval($qty);
        // echo $purchasedGstAmount;

        // $margin = floatval($sellAmount) - ((floatval($perQtyStockInAmount) / intval(intval($stockInData[0]['weightage']))) * intval($qty)) - floatval($sellGstAmount);

        $margin = (floatval($sellAmount) - floatval($pAmntOnSellQty)) - (floatval($sellGstAmount) - floatval($purchasedGstAmount));

    }
    echo number_format($margin,2);
}

?>