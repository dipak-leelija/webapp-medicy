<?php
##########################################################################################################
#                                                                                                        #
#                                      Stock In Edit Page                              (RD)              #
#                                                                                                        #
##########################################################################################################

require_once "../../php_control/stockInDetails.class.php";
require_once "../../php_control/stockIn.class.php";
require_once "../../php_control/products.class.php";
require_once "../../php_control/manufacturer.class.php";
require_once "../../php_control/packagingUnit.class.php";

$StockIn = new StockIn();
$StockInDetails = new StockInDetails();
$Products  = new Products();
$Manufacturer = new Manufacturer();
$Packaging = new PackagingUnits();

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
        $prodMfdDate = $purchase['mfd_date'];
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

    $productDetails = $Products->showProductsById($productId);
    foreach($productDetails as $products){
        $prodName = $products['name'];
        $manufID = $products['manufacturer_id'];
        $packagingTyp = $products['packaging_type'];
        $power = $products['power'];
    }


    $ManufDetails = $Manufacturer->showManufacturerById($manufID);
    foreach($ManufDetails as $manuf){
        $manufName = $manuf['name'];
    }

    // $manufName = str_replace()
    

    $packagingDetails = $Packaging->showPackagingUnitById($packagingTyp);
    foreach($packagingDetails as $packageType){
        $packType = $packageType['unit_name'];
    }


    $purchaseDetialArray = array(
        "purchaseId"    => $purchaseId,
        "productId"     => $productId,
        "productName"   => $prodName,
        "manufId"       => $manufID,
        "manufacturer"  => $manufName,
        "billNo"        => $distBillNo,
        "batchNo"       => $prodBatchNo,
        "mfdDate"       => $prodMfdDate,
        "expDate"       => $prodExpDate,
        "weightage"     => $prodWeightage,
        "unit"          => $prodUnit,
        "power"         => $power,
        "packageType"   => $packType,
        "qty"           => $QTY,
        "FreeQty"       => $freeQTY,
        "looseQty"      => $looseCount,
        "mrp"           => $MRP,
        "ptr"           => $PTR,
        "disc"          => $discunt,
        "baseAmount"    => $base,    
        "gst"           => $GST,
        "GstAmount"     => $gstAmount,
        "mrgn"          => $margin,
        "amnt"          => $amount
    );


    $purchaseDetialArray = json_encode($purchaseDetialArray);

    echo $purchaseDetialArray;
}
