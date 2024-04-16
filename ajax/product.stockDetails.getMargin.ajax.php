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

    // Common assignments
    $stockInQty = ($qtyTyp == 'others') ? intval($stockInData[0]['qty']) + intval($stockInData[0]['free_qty'])  : $stockInData[0]['loosely_count'];
    $stockInAmount = $stockInData[0]['amount'];
    $ptrPerItem = $stockInData[0]['ptr'];
    $perItemBasePrice = $stockInData[0]['d_price'];
    $purchasedGstPaid = $stockInData[0]['gst_amount'];
    $perQtyStockInAmount = floatval($stockInAmount) / $stockInQty;

    // Sell GST calculation
    $sellGstAmount = floatval($taxableAmount) * (floatval($stockInData[0]['gst']) / 100);

    // Purchased amount on sell qty
    $pAmntOnSellQty = ($stockInAmount / $stockInQty) * intval($qty);

    // Paid purchased GST amount per item
    $paidPurchasedGstAmountPerItem = $purchasedGstPaid / $stockInQty;

    // Margin calculation
    $margin = (floatval($sellAmount) - $pAmntOnSellQty) - ($sellGstAmount - ($paidPurchasedGstAmountPerItem *   $qty));

    // Output formatted margin
    echo round($margin, 2);

}





// if (isset($_GET["smPid"])) {

//     $productId      = $_GET["smPid"];
//     $batchNo        = $_GET["Bid"];
//     $qtyTyp         = $_GET["qtype"];
    
//     $mrp            = $_GET["Mrp"];
//     $qty            = $_GET["Qty"];
//     $discPercent    = $_GET["disc"];

//     $taxableAmount  = $_GET["taxable"];
//     $sellAmount     = $_GET['sellAmount'];

//     $currentStockId = $_GET['currentItemId'];


//     // echo "<br>Product id : $productId<br>Batch no : $batchNo<br>Qantity Type : $qtyTyp<br>MRP : $mrp<br>Qantity : $qty<br>Discounted Price : $discPrice<br>";
//     $col = 'id';
//     $currentStockData = $CurrentStock->selectByColAndData($col, $currentStockId);
//     // print_r($currentStockData);
    
//     $stockInData = $StockInDetails->showStockInDetailsByStokinId($currentStockData[0]['stock_in_details_id']);
//     // print_r($stockInData);
     

//     if($mrp == null || $qty == null || $discPercent == null){
//         $mrp = 0;
//         $qty = 0;
//         $disc = 0;
//     }

//     $mrp = floatval($mrp);
//     $qty = intval($qty);
//     $discPercent = floatval($discPercent);

//     $discPrice = $mrp - ($mrp * $discPercent/100);

//     // Common assignments
//     $stockInQty = ($qtyTyp == 'others') ? intval($stockInData[0]['qty']) + intval($stockInData[0]['free_qty'])  : $stockInData[0]['loosely_count'];
//     $stockInAmount = $stockInData[0]['amount'];
//     $ptrPerItem = $stockInData[0]['ptr'];
//     $perItemBasePrice = $stockInData[0]['d_price'];
//     $purchasedGstPaid = $stockInData[0]['gst_amount'];
//     $perQtyStockInAmount = floatval($stockInAmount) / $stockInQty;

//     // Sell GST calculation
//     $sellGstAmount = floatval($taxableAmount) * (floatval($stockInData[0]['gst']) / 100);

//     // Purchased amount on sell qty
//     $pAmntOnSellQty = ($stockInAmount / $stockInQty) * intval($qty);

//     // Paid purchased GST amount per item
//     $paidPurchasedGstAmountPerItem = $purchasedGstPaid / $stockInQty;

//     // Margin calculation
//     $sellmargin = (floatval($sellAmount) - $pAmntOnSellQty) ;

//     // Output formatted margin
//     echo round($sellmargin, 2);

// }

?>