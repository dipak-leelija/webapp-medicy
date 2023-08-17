<?php      
# RD #
require_once "../../php_control/stockReturn.class.php";
require_once "../../php_control/distributor.class.php";
require_once "../../php_control/products.class.php";
require_once "../../php_control/stockIn.class.php";
require_once "../../php_control/stockInDetails.class.php";
require_once "../../php_control/currentStock.class.php";

$StockReturnEdit        =   new StockReturn();
$DistributorDetails     =   new Distributor();
$ProdcutDetails         =   new Products();
$StockIn                =   new StockIn();
$StockInDetails         =   new StockInDetails();
$CurrentStock           =   new CurrentStock();


$EditId = $_POST['EditId'];

//==========================fetching data from stock return details table========================

$stockReturnDetailsData = $StockReturnEdit->showStockReturnDetailsById($EditId);

foreach($stockReturnDetailsData as $stockReturn){
    $Id                     =  $stockReturn['id'];
    $StockReturnId          =  $stockReturn['stock_return_id'];
    $stokinDetialsItemID    =  $stockReturn['stokIn_details_id'];
    $ProductId              =  $stockReturn['product_id'];
    $BatchNo                =  $stockReturn['batch_no'];
    $ExpDate                =  $stockReturn['exp_date'];
    $Unit                   =  $stockReturn['unit'];
    $PurchaseQTY            =  $stockReturn['purchase_qty'];
    $FreeQTY                =  $stockReturn['free_qty'];
    $MRP                    =  $stockReturn['mrp'];
    $PTR                    =  $stockReturn['ptr'];
    $GST                    =  $stockReturn['gst'];
    $discount               =  $stockReturn['disc'];
    $ReturnQTY              =  $stockReturn['return_qty'];
    $ReturnFreeQTY          =  $stockReturn['return_free_qty'];
    $RefundAmount           =  $stockReturn['refund_amount'];

    $AddedBy                =  $stockReturn['added_by'];
    $AddedOn                =  $stockReturn['added_on'];
}

$stockReturnData = json_encode($stockReturnDetailsData);
//==========================fetching data from stock_return table=================================
$stockReturn = $StockReturnEdit->showStockReturnById($StockReturnId);

foreach($stockReturn as $stocks){
    $DistributorId   =   $stocks['distributor_id'];
    $BillNo = $stocks['bill_no'];
    $ReturnDate      =   $stocks['return_date'];
    $RefundMode      =   $stocks['refund_mode'];
    $Items           =   $stocks['items'];
    $TotalQTY        =   $stocks['total_qty'];
    $GSTamount       =   $stocks['gst_amount'];
    $refundMode      =   $stocks['refund_mode'];
    $NetRefundAmount =   $stocks['refund_amount'];
}

//$stockReturn = json_encode($stockReturn);
//===============================================================================================

//==========================fetching data from distributor table==================================
$distributorDetails = $DistributorDetails ->showDistributorById($DistributorId);

foreach($distributorDetails as $distributor){
    $distributorName    =   $distributor['name'];
    $distributorId      =   $distributor['id'];
}

// $distributorDetails = json_encode($distributorDetails);
//===============================================================================================

//==========================fetching data from products table=====================================
$productDetails = $ProdcutDetails -> showProductsById($ProductId);

foreach($productDetails as $products){
    $productName    =   $products['name'];
}

$productDetails = json_encode($productDetails);
//===============================================================================================

//===============================================================================================

//==========================fetchin data from stock in table=====================================
$stockIn = $StockIn->showStockInById($BillNo);

    foreach($stockIn as $stock){
        $stockInBillDate    =   $stock['bill_date'];
    }

//$stockIn = json_encode($stockIn);
//===============================================================================================

//================================fetchin data from current stock================================
$currentStockDetails = $CurrentStock->showCurrentStocByStokInDetialsId($stokinDetialsItemID);
// print_r()
if($currentStockDetails != null){
    foreach($currentStockDetails as $currentStock){
        $currentStockQty   =   $currentStock['qty'];
    }
}else{
    $currentStockQty = 0;
}

$currentStockDetails = json_encode($currentStockDetails);
//===============================================================================================

$stockReturnDetailsDataArry = array(
                                    "StokReturnDetailsId"       =>  $Id,
                                    "stock_return_id"           =>  $StockReturnId,
                                    "stock_in_details_item_id"  =>  $stokinDetialsItemID,

                                    "distributor_name"          =>  $distributorName,
                                    "distributor_id"            =>  $distributorId,
                                    
                                    "product_id"                =>  $ProductId,
                                    "product_Name"              =>  $productName,
                                    "batch_no"                  =>  $BatchNo,

                                    "bill_date"                 =>  $stockInBillDate,
                                    "return_date"               =>  $ReturnDate,
                                    "exp_date"                  =>  $ExpDate,
                                    "unit"                      =>  $Unit,
                                    
                                    "purchase_qty"              =>  $PurchaseQTY,
                                    "free_qty"                  =>  $FreeQTY,
                                    "mrp"                       =>  $MRP,
                                    "ptr"                       =>  $PTR,
    
                                    "gst"                       =>  $GST,
                                    "disParcent"                =>  $discount,
                                    "return_qty"                =>  $ReturnQTY,
                                    "return_free_qty"           =>  $ReturnFreeQTY,
                                    "per_item_refund"           =>  $RefundAmount,

                                    "net_refund_amount"         =>  $NetRefundAmount,

                                    "current_stock_qty"         =>  $currentStockQty,
                                    
                                    "refund_mode"               =>  $RefundMode,
                                    "added_by"                  =>  $AddedBy,
                                    "added_on"                  =>  $AddedOn);
                             
$stockReturnDetailsDataArry = json_encode($stockReturnDetailsDataArry);

if($stockReturnDetailsDataArry == true){
    echo $stockReturnDetailsDataArry;
    
}else{
    echo 0;
}

?>