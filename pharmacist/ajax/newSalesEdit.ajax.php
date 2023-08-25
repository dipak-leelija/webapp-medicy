<?php
require_once '../../php_control/stockOut.class.php';
require_once '../../php_control/products.class.php';
require_once '../../php_control/currentStock.class.php';
require_once '../../php_control/manufacturer.class.php';

$StockOut = new StockOut();
$Products = new Products();
$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();


$itemId = $_POST['Stock_out_item_id'];
$table = 'item_id';

////////////// STOCK OUT DATA AND SALES ITEM DATA FETCH AREA \\\\\\\\\\\\\\\\\

//==================== ITEM DETAILS FROM PHARMACY INVOICE TABLE =====================
$itemInvoiceData = $StockOut->invoiceDetialsByTableData($table, $itemId);
foreach($itemInvoiceData as $itemSellData){
    $pharmacyId = $itemSellData['id'];
    $pharmacyInvoiceId = $itemSellData['invoice_id'];
    $pharmacyItemId = $itemSellData['item_id'];
    $pharmacyItemName = $itemSellData['item_name'];
    $pharmacyBatchNo = $itemSellData['batch_no'];
    $pharmacyItemPackOf = $itemSellData['weatage'];
    $pharmacyItemExpDate = $itemSellData['exp_date'];
    $pharmacyItemQty = $itemSellData['qty'];
    $pharmacyItemLooseQty = $itemSellData['loosely_count'];
    $pharmacyItemMrp = $itemSellData['mrp'];
    $pharmacyItemDiscPercent = $itemSellData['disc'];
    $pharmacyItemTaxable = $itemSellData['taxable'];
    $pharmacyItemGstPercent = $itemSellData['gst'];
    $pharmacyItemGstAmount = $itemSellData['gst_amount'];
    $pharmacyItemAmount = $itemSellData['amount'];
}

//==================== ITEM DETAILS FROM STOK OUT DETAILS TABLE =====================
$stockOutItemDetails = $StockOut->stokOutDetailsDataOnTable($table, $itemId);
foreach($stockOutItemDetails as $selsItemData){
    $stockOutDetailsId = $selsItemData['id'];
    $stockOutDetailsInvoiceId = $selsItemData['invoice_id'];
    $stockOutDetailsItemId = $selsItemData['item_id'];
    $stockOutDetailsProductId = $selsItemData['product_id'];
    $stockOutDetailsBatchNo = $selsItemData['batch_no'];
    $stockOutDetailsExpDate = $selsItemData['exp_date'];
    $stockOutDetailsItemWeatage = $selsItemData['weightage'];
    $stockOutDetailsItemUnit = $selsItemData['unit'];
    $stockOutDetailsItemQty = $selsItemData['qty'];
    $stockOutDetailsLooselyCount = $selsItemData['loosely_count'];
    $stockOutDetailsMrp = $selsItemData['mrp'];
    $stockOutDetailsPtr = $selsItemData['ptr'];
    $stockOutDetailsDiscount = $selsItemData['discount'];
    $stockOutDetailsGst = $selsItemData['gst'];
    $stockOutDetailsMargin = $selsItemData['margin'];
    $stockOutDetailsamount = $selsItemData['amount'];


    if($stockOutDetailsItemUnit == 'tab' || $stockOutDetailsItemUnit == 'cap'){
        $sellQty = $stockOutDetailsLooselyCount;
    }else{
        $sellQty = $stockOutDetailsItemQty;
    }
}

//================== AVAILIBILITY CHECK FROM CURRENT STOCK ====================
$currentStockData = $CurrentStock->showCurrentStocById($stockOutDetailsItemId);
foreach($currentStockData as $currenStock){
    $currentStockUnit = $currenStock['unit'];

    if($currentStockUnit == 'tab' || $currentStockUnit == 'cap'){
        $currentStockAvailibility = $currenStock['loosely_count'];
    }else{
        $currentStockAvailibility = $currenStock['qty'];
    }

    $currentStockPtr = $currenStock['ptr'];
}


// ============================== MANUFACTURUR DETAILS ===================================
$prodDetails = $Products->showProductsById($stockOutDetailsProductId);
$composition = $prodDetails[0]['product_composition'];

$manufData = $Manufacturer->showManufacturerById($prodDetails[0]['manufacturer_id']);
foreach($manufData as $manufData){
    $manufId = $manufData['id'];
    $manufName = $manufData['name'];
}
//////////////////////\\\\\\\\\\\\\\\\\\\\\\\\================///////////////////////\\\\\\\\\\\\\\\\\\\\\\
$stockOutDetailsDataArry = array(
    "pharmacyId"                =>  $pharmacyId,
    "stockOutDetailsId"         =>  $stockOutDetailsId,
    "invoiceId"                 =>  $pharmacyInvoiceId,
    "itemId"                    =>  $pharmacyItemId,
    "productId"                 =>  $stockOutDetailsProductId,
    "productName"               =>  $pharmacyItemName,
    "manufId"                   =>  $manufId,                   
    "manufName"                 =>  $manufName,
    "productComposition"        =>  $composition,
    "batchNo"                   =>  $pharmacyBatchNo,
    "packOf"                    =>  $pharmacyItemPackOf,
    "itemWeatage"               =>  $stockOutDetailsItemWeatage,
    "itemUnit"                  =>  $stockOutDetailsItemUnit,
    "expDate"                   =>  $stockOutDetailsExpDate,
    "qantity"                   =>  $pharmacyItemQty,  
    "looseCount"                =>  $pharmacyItemLooseQty,
    "availableQty"              =>  $currentStockAvailibility,
    "sellQty"                   =>  $sellQty,
    "Mrp"                       =>  $pharmacyItemMrp,
    "Ptr"                       =>  $currentStockPtr,
    "dicPercent"                =>  $pharmacyItemDiscPercent,
    "gstPercent"                =>  $pharmacyItemGstPercent,
    "gstAmount"                 =>  $pharmacyItemGstAmount,
    "margin"                    =>  $stockOutDetailsMargin,
    "taxable"                   =>  $pharmacyItemTaxable,
    "paybleAmount"              =>  $pharmacyItemAmount
);


$stockOutDetailsDataArry = json_encode($stockOutDetailsDataArry);

if ($itemId == true) {
    echo $stockOutDetailsDataArry;
} else {
    echo 0;
}
