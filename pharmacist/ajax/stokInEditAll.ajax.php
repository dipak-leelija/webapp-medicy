<?php
##########################################################################################################
#                                                                                                        #
#                                      Stock In Edit Page                              (RD)              #
#                                                                                                        #
##########################################################################################################
require_once "../../php_control/stockInDetails.class.php";
require_once "../../php_control/stockIn.class.php";

$StockIn = new StockIn();
$StockInDetails = new StockInDetails();

if(isset($_POST['blNo'])){

    $prodId = $_POST['pId'];
    $billNo = $_POST['blNo'];
    $batchNo = $_POST['bhNo'];

    $purchaseDetail = $StockInDetails->stokInDetials($prodId, $billNo, $batchNo);

    foreach($purchaseDetail as $purchase){
        $purchaseId = $purchase['id'];
        $productId = $purchase['product_id']; 
        $distBillNo = $purchase['distributor_bill'];
        $prodBatchNo = $purchase['batch_no'];
        $prodExpDate = $purchase['exp_date'];
        $prodWeightage = $purchase['weightage'];
        $prodUnit = $purchase['unit'];
        $QTY = $purchase['qty'];
        $freeQTY = $purchase['free_qty'];
        $looseCount = $purchase['loosely_count'];
        $MRP = $purchase['mrp'];
        $PTR = $purchase['ptr'];
        $discunt = $purchase['discount'];
        $base = $purchase['base'];
        $GST = $purchase['gst'];
        $gstAmount = $purchase['gst_amount'];
        $margin = $purchase['margin'];
        $amount = $purchase['amount'];
    }

    $purchaseDetail = json_encode($purchaseDetail);

    $purchaseDetialArray = array(
        "purchaseId" => $purchaseId,
        "productId"  => $productId,
        "billNo"     => $distBillNo,
        "batchNo"    => $prodBatchNo,
        "expDate"    => $prodExpDate,
        "weightage"  => $prodWeightage,
        "unit"       => $prodUnit,
        "qty"        => $QTY,
        "FreeQty"    => $freeQTY,
        "looseQty"   => $looseCount,
        "mrp"        => $MRP,
        "ptr"        => $PTR,
        "disc"       => $discunt,
        "baseAmount" => $base,    
        "gst"        => $GST,
        "GstAmount"  => $gstAmount,
        "mrgn"       => $margin,
        "amnt"       => $amount
    );

    $purchaseDetialArray = json_encode($purchaseDetialArray);

    echo $purchaseDetialArray;
}
