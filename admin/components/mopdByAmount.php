<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';

$includePath = get_include_path();

$maxPurchase = $StockIn->selectDistOnMaxPurchase($adminId);
$maxPurchase = json_decode($maxPurchase);
$distNameOnMaxPurchase = $Distributor->distributorDetail($maxPurchase->distributor_id);

// echo $maxPurchase->total_purchase_amount;
// echo $distNameOnMaxPurchase->name;

$maxItemPurchase = $StockIn->selectDistOnMaxItems($adminId);
$maxItemPurchase = json_decode($maxItemPurchase);

$distNameOnMaxItem = $Distributor->distributorDetail($maxItemPurchase->distributor_id);

// echo $maxItemPurchase->number_of_purchases;
// echo $distNameOnMaxItem->name;




?>

<div class="card border-left-info border-right-info h-100 py-2 pending_border animated--grow-in">
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    most purchaed distributor</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <i class="fas fa-rupee-sign"></i><label type="text" id="salesAmount" name="salesAmount"><?php echo $maxPurchase->total_purchase_amount; ?></label><br>
                    <label type="text" id="distName" name="distName"><?php echo $distNameOnMaxPurchase->name; ?></label>
                </div>
            </div>
        </div>
    </div>
</div>
