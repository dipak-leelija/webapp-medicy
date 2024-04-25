<?php
$checkDt = date('Y-m');

$currentStockData = $CurrentStock->showCurrentStockbyAdminId($adminId);
$currentStockDataForJS = json_encode($currentStockData);


$netCurrentMrp = 0;
$netCurrentPtr = 0;
$netSalesMargin = 0;

if ($currentStockData != null) {
    foreach ($currentStockData as $currentItemData) {
        $currentItemMrp = $currentItemData['mrp'];
        
        if(in_array(strtolower($currentItemData['unit']), LOOSEUNITS)){
            $perQtyMrp = floatval($currentItemData['mrp']) / floatval($currentItemData['weightage']);
            $currentItemMrp = floatval($perQtyMrp) * intval($currentItemData['loosely_count']);
        }else{
            $currentItemMrp = floatval($currentItemData['mrp']) * intval($currentItemData['qty']);
        }

        $netCurrentMrp = floatval($netCurrentMrp) + floatval($currentItemMrp); // calculating total mrp in curnt stock

        $itemStockIndetailsData = $StockInDetails->showStockInDetailsByStokinId($currentItemData['stock_in_details_id']);

        foreach($itemStockIndetailsData as $itemStockIndetailsData){
            $itemBasePrice = $itemStockIndetailsData['base'];
            $itemGst = $itemStockIndetailsData['gst'];
            $itemPtr = floatval($itemBasePrice) + (floatval($itemBasePrice) * (floatval($itemGst)/100));

            if (in_array(strtolower($itemStockIndetailsData['unit']), LOOSEUNITS)) {
                $perQtyPtr = floatval($itemPtr) / intval($itemStockIndetailsData['weightage']);
                $perItemPtr = floatval($perQtyPtr) * intval($currentItemData['loosely_count']);
            } else {
                $perItemPtr = floatval($itemPtr) * intval($currentItemData['qty']);
            }

            $netCurrentPtr = floatval($netCurrentPtr) + floatval($perItemPtr);  // calculating total ptr in curnt stock
        }
        $netSalesMargin = floatval($netCurrentMrp) - floatval($netCurrentPtr);  // calculating total margin
    }
}


$currentStockExpItemData = $CurrentStock->showExpStockForStocksummaryCard($checkDt, $adminId);
// print_r($currentStockExpItemData);

$netCurrentMrpOfExpItems = 0;
$netCurrentPtrOfExpItems = 0;
$netCurrentMarginOfExpItems = 0;
if ($currentStockExpItemData != null) {
    foreach ($currentStockExpItemData as $currentExpItemData) {

        if(in_array(strtolower($currentExpItemData['unit']), LOOSEUNITS)){
            $perQtyMrp = floatval($currentExpItemData['mrp']) / floatval($currentExpItemData['weightage']);
            $expItemMrp = floatval($perQtyMrp) * intval($currentExpItemData['loosely_count']);
        }else{
            $expItemMrp = floatval($currentItemData['mrp']) * intval($currentItemData['qty']);
        }

        $netCurrentMrpOfExpItems = floatval($netCurrentMrpOfExpItems) + floatval($expItemMrp); // calculating total mrp in curnt stock

        $itemStockIndetailsData = $StockInDetails->showStockInDetailsByStokinId($currentExpItemData['stock_in_details_id']);

        foreach($itemStockIndetailsData as $itemStockIndetailsData){
            $itemBasePrice = $itemStockIndetailsData['base'];
            $itemGst = $itemStockIndetailsData['gst'];
            $itemPtr = floatval($itemBasePrice) + (floatval($itemBasePrice) * (floatval($itemGst)/100));

            if (in_array(strtolower($itemStockIndetailsData['unit']), LOOSEUNITS)) {
                $perQtyPtr = floatval($itemPtr) / intval($itemStockIndetailsData['weightage']);
                $perItemPtr = floatval($perQtyPtr) * intval($currentItemData['loosely_count']);
            } else {
                $perItemPtr = floatval($itemPtr) * intval($currentItemData['qty']);
            }

            $netCurrentPtrOfExpItems = floatval($netCurrentPtrOfExpItems) + floatval($perItemPtr);  // calculating total ptr in curnt stock
        }
        $netCurrentMarginOfExpItems = floatval($netCurrentMrpOfExpItems) - floatval($netCurrentPtrOfExpItems);  // calculating total margin
    }
}





?>
<div class="mb-4">
    <div class="card border-top-primary shadow pending_border animated--grow-in">
        <div class="card-body">
            <a class="text-decoration-none" href="#">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Stock details
                        </div>
                        <div class="table-responsive" id="stocksummary-data-table">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Stock</th>
                                        <th scope="col">By MRP</th>
                                        <th scope="col">By PTR</th>
                                        <th scope="col">Sales Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Current</th>
                                        <td><?php echo number_format($netCurrentMrp, 2); ?></td>
                                        <td><?php echo number_format($netCurrentPtr, 2); ?></td>
                                        <td><?php echo number_format($netSalesMargin, 2); ?></td>

                                    </tr>
                                    <tr>
                                        <th scope="row">Expired</th>
                                        <td><?php echo number_format($netCurrentMrpOfExpItems, 2); ?></td>
                                        <td><?php echo number_format($netCurrentPtrOfExpItems, 2); ?></td>
                                        <td><?php echo number_format($netCurrentMarginOfExpItems, 2); ?></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" id="stocksummary-no-data-found-div">
                            <label style="color: red;">no data found</label>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>


<script>
    var chkCurrentStockData = JSON.stringify(<?php echo $currentStockDataForJS; ?>);

    var chkCurrentStockExpData = JSON.stringify(<?php echo json_encode($currentStockExpItemData) ?>);

    if (chkCurrentStockData != null && chkCurrentStockExpData != null) {
        document.getElementById('stocksummary-data-table').style.display = 'block';
        document.getElementById('stocksummary-no-data-found-div').style.display = 'none';
    } else {
        document.getElementById('stocksummary-data-table').style.display = 'none';
        document.getElementById('stocksummary-no-data-found-div').style.display = 'block';
    }

</script>