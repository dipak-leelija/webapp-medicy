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

$stockReturnData = $StockReturnEdit->showStockReturnDetailsById($EditId);

foreach($stockReturnData as $stockReturn){
    $stockReturnDetailsId                =  $stockReturn['id'];
    $stockReturnDetailsStockReturnId     =  $stockReturn['stock_return_id'];
    $stockReturnDetailsProductId         =  $stockReturn['product_id'];
    $stockReturnDetailsBatchNo           =  $stockReturn['batch_no'];
    $stockReturnDetailsExpDate           =  $stockReturn['exp_date'];
    $stockReturnDetailsUnit              =  $stockReturn['unit'];
    $stockReturnDetailsPurchaseQTY       =  $stockReturn['purchase_qty'];
    $stockReturnDetailsFreeQTY           =  $stockReturn['free_qty'];
    $stockReturnDetailsMRP               =  $stockReturn['mrp'];
    $stockReturnDetailsPTR               =  $stockReturn['ptr'];
    $stockReturnDetailsPurchaseAmount    =  $stockReturn['purchase_amount'];
    $stockReturnDetailsGST               =  $stockReturn['gst'];
    $stockReturnDetailsReturnQTY         =  $stockReturn['return_qty'];
    $stockReturnDetailsFreeQTY           =  $stockReturn['free_qty'];
    $stockReturnDetailsRefundAmount      =  $stockReturn['refund_amount'];
    $stockReturnDetailsAddedBy           =  $stockReturn['added_by'];
    $stockReturnDetailsAddedOn           =  $stockReturn['added_on'];
    $stockReturnDetailsAddedTime         =  $stockReturn['added_time'];
}

$stockReturnData = json_encode($stockReturnData);
//==========================fetching data from stock_return table=================================
$stockReturn = $StockReturnEdit->showStockReturnById($stockReturnDetailsStockReturnId);

foreach($stockReturn as $stocks){
    $stockReturnId              =   $stocks['id'];
    $stockReturnDistributorId   =   $stocks['distributor_id'];
    $stockReturnReturnDate      =   $stocks['return_date'];
    $stockReturnItems           =   $stocks['items'];
    $stockReturnTotalQTY        =   $stocks['total_qty'];
    $stockReturnGSTamount       =   $stocks['gst_amount'];
    $stockReturnRefundMode      =   $stocks['refund_mode'];
}

//$stockReturn = json_encode($stockReturn);
//===============================================================================================

//==========================fetching data from distributor table==================================
$distributorDetails = $DistributorDetails ->showDistributorById($stockReturnDistributorId);

foreach($distributorDetails as $distributor){
    $distributorName    =   $distributor['name'];
    $distributorId      =   $distributor['id'];
}

$distributorDetails = json_encode($distributorDetails);
//===============================================================================================

//==========================fetching data from products table=====================================
$productDetails = $ProdcutDetails -> showProductsById($stockReturnDetailsProductId);

foreach($productDetails as $products){
    $productName    =   $products['name'];
}

$productDetails = json_encode($productDetails);
//===============================================================================================

//==========================fetchin data from stock in details table=============================
$stockInDetails = $StockInDetails->stockDistributorBillNo($stockReturnDetailsBatchNo, $stockReturnDetailsProductId);

    foreach($stockInDetails as $stockindetail){
        $StockInDetailsDistributorBillNo    =   $stockindetail['distributor_bill'];
        $StockInDetailsGSTamount            =   $stockindetail['gst_amount'];
        $StockInDetailsWeatage              =   $stockindetail['weightage'];
        $StockInDetailsUnit                 =   $stockindetail['unit'];
        $StockInDetailsDiscount             =   $stockindetail['discount'];
        $StockInDetailsQTY                  =   $stockindetail['qty'];
    }
    

$stockInDetails = json_encode($stockInDetails); 
//===============================================================================================

//==========================fetchin data from stock in table=====================================
$stockIn = $StockIn->showStockInById($StockInDetailsDistributorBillNo);

    foreach($stockIn as $stock){
        $stockInBillDate    =   $stock['bill_date'];
    }

//$stockIn = json_encode($stockIn);
//===============================================================================================

//================================fetchin data from current stock================================
$currentStockDetails = $CurrentStock->showCurrentStocByProductIdandBatchNo($stockReturnDetailsProductId, $stockReturnDetailsBatchNo);

    foreach($currentStockDetails as $currentStock){

        $currentStockQty   =   $currentStock['qty'];
    }

//$currentStockDetails = json_encode($currentStockDetails);
//===============================================================================================
$stockReturnDetailsDataArry = array("id"                    =>  $stockReturnDetailsId,
                                    "stock_return_id"       =>  $stockReturnDetailsStockReturnId,
                                    "distributor_name"      =>  $distributorName,
                                    "distributor_id"        =>  $distributorId,
                                    "product_id"            =>  $stockReturnDetailsProductId,
                                    "product_Name"          =>  $productName,
                                    "discount"              =>  $StockInDetailsDiscount,
                                    "batch_no"              =>  $stockReturnDetailsBatchNo,
                                    "purchase_date"         =>  $stockInBillDate,
                                    "exp_date"              =>  $stockReturnDetailsExpDate,
                                    "unit"                  =>  $StockInDetailsUnit,
                                    "weightage"             =>  $StockInDetailsWeatage,
                                    "purchase_qty"          =>  $stockReturnDetailsPurchaseQTY,
                                    "free_qty"              =>  $stockReturnDetailsFreeQTY,
                                    "mrp"                   =>  $stockReturnDetailsMRP,
                                    "ptr"                   =>  $stockReturnDetailsPTR,
                                    "purchase_amount"       =>  $stockReturnDetailsPurchaseAmount,
                                    "gst"                   =>  $stockReturnDetailsGST,
                                    "taxable_amount"        =>  $StockInDetailsGSTamount,
                                    "current_stock_qty"     =>  $currentStockQty,
                                    "return_qty"            =>  $stockReturnDetailsReturnQTY,
                                    "free_qty"              =>  $stockReturnDetailsFreeQTY,
                                    "refund_amount"         =>  $stockReturnDetailsRefundAmount,
                                    "refund_mode"           =>  $stockReturnRefundMode,
                                    "added_by"              =>  $stockReturnDetailsAddedBy,
                                    "added_on"              =>  $stockReturnDetailsAddedOn,
                                    "added_time"            =>  $stockReturnDetailsAddedTime);
                             

$stockReturnDetailsDataArry = json_encode($stockReturnDetailsDataArry);

if($stockReturnData == true){
    echo $stockReturnDetailsDataArry;
    
}else{
    echo 0;
}

?>