<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'stockIn.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'productsImages.class.php';
require_once CLASS_DIR.'distributor.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';

$CurrentStock   = new CurrentStock();
$Patients       =   new Patients();
$StockIn        =   new StockIn();
$StockInDetail  =   new StockInDetails();
$Product        =   new Products();
$ProductImages  =   new ProductImages();
$distributor    =   new Distributor();
$manufacturer   =   new Manufacturer();
$packagUnit     =   new PackagingUnits();


if (isset($_POST['delID'])) {
    $productID =  $_POST['delID'];

    $deleteProductFromCrntStock = $CurrentStock->deleteCurrentStockbyId($productID);
   

    // ===== fetching  stock in details data for adjusting stock in data =======
    $itemDetailsFromStockInDetails = $StockInDetail->showStockInDetailsByPId($productID);

    foreach($itemDetailsFromStockInDetails as $perItemStockInDetaisl){
        $itemDetaislId = $perItemStockInDetaisl['id'];
        $itemQty = $perItemStockInDetaisl['qty'];
        $itemGstAmount = $perItemStockInDetaisl['gst_amount'];
        $itemAmount = $perItemStockInDetaisl['amount'];
        $StockInId = $perItemStockInDetaisl['stokIn_id'];
        
        // echo "<br>$itemDetaislId<br>$itemQty<br>$itemGstAmount<br>$itemAmount<br>$StockInId<br><br>";
        // ======== delete item from stock in detaisl =========
        $deleteFromStocInDetails = $StockInDetail->stockInDeletebyDetailsId($itemDetaislId);
    
        // ====== fetching stock in data for adjustment ========
        $selectStockInData = $StockIn->selectStockInById($StockInId);
        foreach($selectStockInData as $stockIn){
            $itemCount = $stockIn['items'];
            $totalQty = $stockIn['total_qty'];
            $gstAmount = $stockIn['gst'];
            $amount = $stockIn['amount'];

            // echo "<br>$itemCount<br>$totalQty<br>$gstAmount<br>$amount<br>$StockInId<br><br>";
            // =========== adjust stock in data ============
            $updatedItemsCount = intval($itemCount) - 1;
            $updatedTotalQty = intval($totalQty) - intval($itemQty);
            $updatedGstAmount = intval($gstAmount) - intval($itemGstAmount);
            $updatedAmount = intval($amount) - intval($itemAmount);

            // echo "<br>$updatedItemsCount<br>$updatedTotalQty<br>$updatedGstAmount<br>$updatedAmount<br>$StockInId<br><br>";

            $updateStockInData = $StockIn->updateStockInOnModifyCurrentStock($StockInId, $updatedItemsCount, $updatedTotalQty, $updatedGstAmount, $updatedAmount, $employeeId, NOW);

        }
        
    }
    
    if($deleteProductFromCrntStock == true && $deleteFromStocInDetails == true && $updateStockInData == true){
        echo true;
    }else{
        echo 0;
    }
}




if (isset($_POST['delItemId'])) {

    $itemId =  $_POST['delItemId'];

    // echo $productId;

    // =============== delete form current stock ===============
    // $deleteProductStockByBatch = $CurrentStock->deleteCurrentStockbyStockIndetailsId($itemId);

    // ============== select stock in detaisl data =============
    $sockInDetaislData = $StockInDetail->showStockInDetailsByStokinId($itemId);
    print_r($sockInDetaislData);
    // if($productId != null){
    //     echo 1;
    // }else{
    //     echo 0;
    // }
    // $showStock = $CurrentStock->showCurrentStocByPId($productId);
    // print_r($showStock);
    // echo count($showStock);

}

?>