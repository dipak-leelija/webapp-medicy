<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockOut.class.php';


$StockIn   = new StockIn;
$StockOut   = new StockOut;

if (isset($_GET['startDt']) && isset($_GET['endDt'])) {

    $salesData = $StockOut->selectStockOutDataOnDateFilter($_GET['startDt'], $_GET['endDt'], $adminId);
    $purchaseData = $StockIn->purchaseDatafetchByDateRange($_GET['startDt'], $_GET['endDt'], $adminId);

    $mergedArray = [];

    $totalSalesCount = 0;
    $totalSalesAmount = 0;
    if ($salesData != null) {
        foreach ($salesData as $item) {
            $totalSalesCount += intval($item->invoice_count);
            $totalSalesAmount += floatval($item->sell_amount);
        }
        $totalSalesAmount = round($totalSalesAmount, 2);


        foreach ($salesData as $item) {
            $date = $item->{'sell_date'};
            $mergedArray[$date]['sales_amount'] = $item->{'sell_amount'};
        }
    } else {
        $totalSalesCount = 0;
        $totalSalesAmount = 0;
    }


    $totalPurchseCount = 0;
    $totalPurchaseAmount = 0;
    if ($purchaseData != null) {
        
        foreach ($purchaseData as $item) {
            $totalPurchseCount += intval($item->id);
            $totalPurchaseAmount += floatval($item->stockin_amount);
        }
        $totalPurchaseAmount = round($totalPurchaseAmount, 2);

        foreach ($purchaseData as $item) {
            $date = $item->{'purchase_date'};
            $mergedArray[$date]['purchase_amount'] = $item->{'stockin_amount'};
        }
    } else {
        $totalPurchseCount = 0;
        $totalPurchaseAmount = 0;
    }

    // filling missing dates
    $dates = array_keys($mergedArray);
    $earliestDate = min($dates);
    $latestDate = max($dates);
    $currentDate = $earliestDate;
    while ($currentDate <= $latestDate) {
        if (!isset($mergedArray[$currentDate])) {
            $mergedArray[$currentDate] = ['sales_amount' => 0, 'purchase_amount' => 0];
        }
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    }

    // sorting data
    ksort($mergedArray);

    // modify dates
    $updatedDataArray = [];
    foreach ($mergedArray as $date => $values) {
        $formattedDate = date('M d', strtotime($date));
        $updatedDataArray[$formattedDate] = $values;
    }


    $returnData = json_encode(['status' => '1', 'totalSellCount' => $totalSalesCount, 'totalSellAmount' => $totalSalesAmount, 'totalPurchaseCount' => $totalPurchseCount, 'totalPurchaseAmount' => $totalPurchaseAmount, 'sellPurchaseDataArray' => $updatedDataArray]);

    print_r($returnData);
}
