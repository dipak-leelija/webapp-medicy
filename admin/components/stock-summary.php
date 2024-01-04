<?php
$checkDt = date('Y-m');

$currentStockData = $CurrentStock->showCurrentStockbyAdminId();

$currentStockDataForJS = json_encode($currentStockData);
// print_r($currentStockData);

$netCurrentMrp = 0;
$netCurrentPtr = 0;
$netCurrentMargin = 0;
if ($currentStockData != null) {
    foreach ($currentStockData as $currentItemData) {
        $currentItemMrp = $currentItemData['mrp'];
        $currentItemMrp = floatval($currentItemMrp) * $currentItemData['qty']; // calculating total mrp in curnt stock
        // echo "MRP : ".$currentItemMrp."<br>";
        $currentItemPtr = $currentItemData['ptr'];
        $currentItemPtr = floatval($currentItemPtr) * $currentItemData['qty']; // calculating total ptr in curnt stock
        // echo "PTR : ".$currentItemPtr."<br>";

        $netAmountPerItem = floatval($currentItemPtr) + ((floatval($currentItemPtr)) * intval($currentItemData['gst']) / 100);
        // echo "Item price : ".$netAmountPerItem."<br>";

        $perItemMargin = floatval($currentItemMrp) - floatval($netAmountPerItem);
        // echo "Item margin : ".$perItemMargin."<br><br>";

        $netCurrentMrp = floatval($netCurrentMrp) + floatval($currentItemMrp); // calculating total mrp all over
        $netCurrentPtr = floatval($netCurrentPtr) + floatval($currentItemPtr); // calculating total ptr all over
        $netCurrentMargin = floatval($netCurrentMargin) + floatval($perItemMargin); // calculating total margin all over
    }
}


$currentStockExpItemData = $CurrentStock->showExpStockForStocksummaryCard();
// print_r($currentStockExpItemData);

$netCurrentMrpOfExpItems = 0;
$netCurrentPtrOfExpItems = 0;
$netCurrentMarginOfExpItems = 0;
if ($currentStockExpItemData != null) {
    foreach ($currentStockExpItemData as $currentExpItemData) {
        $currentExpItemMrp = $currentExpItemData['mrp'];
        $currentExpItemMrp = floatval($currentExpItemMrp) * $currentExpItemData['qty']; // calculating total mrp in curnt stock
        // echo "MRP : ".$currentExpItemMrp."<br>";
        $currentExpItemPtr = $currentExpItemData['ptr'];
        $currentExpItemPtr = floatval($currentExpItemPtr) * $currentExpItemData['qty']; // calculating total ptr in curnt stock
        // echo "PTR : ".$currentExpItemPtr."<br>";

        $netExpItemAmountPerItem = floatval($currentExpItemPtr) + ((floatval($currentExpItemPtr)) * intval($currentExpItemData['gst']) / 100);
        // echo "Item price : ".$netExpItemAmountPerItem."<br>";

        $perExpItemMargin = floatval($currentExpItemMrp) - floatval($netExpItemAmountPerItem);
        // echo "Item margin : ".$perExpItemMargin."<br><br>";

        $netCurrentMrpOfExpItems = floatval($netCurrentMrpOfExpItems) + floatval($currentExpItemMrp); // calculating total mrp all over
        $netCurrentPtrOfExpItems = floatval($netCurrentPtrOfExpItems) + floatval($currentExpItemPtr); // calculating total ptr all over
        $netCurrentMarginOfExpItems = floatval($netCurrentMarginOfExpItems) + floatval($perExpItemMargin); // calculating total margin all over
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
                                        <th scope="col">By PTR</th>
                                        <th scope="col">By MRP</th>
                                        <th scope="col">Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Current</th>
                                        <td><?php echo number_format($netCurrentMrp, 2); ?></td>
                                        <td><?php echo number_format($netCurrentMrp, 2); ?></td>
                                        <td><?php echo number_format($netCurrentMargin, 2); ?></td>

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

    // console.log(chkCurrentStockData);
    // console.log(chkCurrentStockExpData);

    if (chkCurrentStockData != null && chkCurrentStockExpData != null) {
        document.getElementById('stocksummary-data-table').style.display = 'block';
        document.getElementById('stocksummary-no-data-found-div').style.display = 'none';
    } else {
        document.getElementById('stocksummary-data-table').style.display = 'none';
        document.getElementById('stocksummary-no-data-found-div').style.display = 'block';
    }

</script>