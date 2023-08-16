<?php
require_once '../../php_control/stockIn.class.php';
require_once '../../php_control/stockInDetails.class.php';
require_once '../../php_control/products.class.php';
require_once '../../php_control/currentStock.class.php';
require_once '../../php_control/stockReturn.class.php';
require_once "../../php_control/distributor.class.php";



$StockIn        = new StockIn();
$StockInDetails = new StockInDetails();
$Products       = new Products();
$CurrentStock   = new CurrentStock();
$StockReturn    = new StockReturn();
$Distributor    = new Distributor();

// getBillList function
if (isset($_GET['dist-id'])) {
?>
    <div class="row border-bottom border-primary small mx-2 p-1">
        <div class="col-3 mb-1">Bill No</div>
    </div>
    <?php
    $distributorId = $_GET['dist-id'];
    $attribute = 'distributor_id';
    $details = $StockIn->stockInByAttribute($attribute, $distributorId);
    foreach ($details as $details) {
        $billNo = $details['distributor_bill'];
    ?>

    <div class="row mx-2 p-1 border-bottom p-row item-list" onclick="getItemList('<?php echo $distributorId; ?>','<?php echo $billNo; ?>');">
                <div class="col-6 mb-0"><?php echo $billNo; ?></div>
            </div>
    <?php
    }
}
?>