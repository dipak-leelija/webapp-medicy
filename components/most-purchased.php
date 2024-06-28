<?php

$maxPurchasedDistAmount = json_decode($StockIn->maxPurchasedDistAmount($adminId));

$totalAmount = '';
$distributorName        = '';

if ($maxPurchasedDistAmount->status != 0) {
    $maxPurchasedDistAmount = $maxPurchasedDistAmount->data;
    $totalAmount = $maxPurchasedDistAmount->total;

    $distributorName = $Distributor->distributorName($maxPurchasedDistAmount->distributor_id);
}


//========================================================================
$maxItemPurchase = json_decode($StockIn->selectDistOnMaxItems($adminId));

$NosOfPurchased     = '';
$distNameOnMaxItem  = '';

if ($maxItemPurchase->status != 0) {
    $maxItemPurchase = $maxItemPurchase->data;
    $NosOfPurchased    = $maxItemPurchase->number_of_purchases;
    $distNameOnMaxItem = $Distributor->distributorName($maxItemPurchase->distributor_id);
}

?>

<div class="card border-left-info shadow border-right-info h-100 py-2 pending_border animated--grow-in">
    <div class="card-body pb-0">
        <div class="row">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Most purchased distributor
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-12 col-lg-6 mb-3">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    by amount
                </div>
                <div class="mb-0 font-weight-bold text-gray-800" id="mopdByAmount-info-div">
                    <label type="text" id="distName" name="distName"><?= $distributorName; ?></label>
                    <br>
                    <?= CURRENCY ?>
                    <label type="text" id="salesAmount" name="salesAmount"><?= $totalAmount; ?></label>
                </div>
                <div class="mb-0 font-weight-bold text-gray-800" id="mopdByAmount-no-data-div" style="display: none;">
                    <label for="no-data">NO DATA FOUND</label>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-12 col-lg-6 mb-3">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    by items
                </div>
                <div class="mb-0 font-weight-bold text-gray-800" id="mopdByItems-info-div">
                    <label type="text" id="distName" name="distName"><?= $distNameOnMaxItem; ?></label>
                    <br>
                    <label type="text" id="itemCount" name="itemCount"><?= $NosOfPurchased; ?> Times</label>
                </div>
                <div class="mb-0 font-weight-bold text-gray-800" id="mopdByItems-no-data-div">
                    <label for="no-data">NO DATA FOUND</label>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    var mopdByItemData = <?php echo json_encode($maxItemPurchase); ?>;

    if (mopdByItemData != null) {
        document.getElementById("mopdByItems-no-data-div").style.display = 'none';
        document.getElementById("mopdByItems-info-div").style.display = 'block';
    } else {
        document.getElementById("mopdByItems-no-data-div").style.display = 'block';
        document.getElementById("mopdByItems-info-div").style.display = 'none';
    }


    // =============================================
    var mopdByAmountData = <?php echo json_encode($totalAmount); ?>;

    if (mopdByAmountData != null) {
        document.getElementById("mopdByAmount-no-data-div").style.display = 'none';
        document.getElementById("mopdByAmount-info-div").style.display = 'block';
    } else {
        document.getElementById("mopdByAmount-no-data-div").style.display = 'block';
        document.getElementById("mopdByAmount-info-div").style.display = 'none';
    }
</script>