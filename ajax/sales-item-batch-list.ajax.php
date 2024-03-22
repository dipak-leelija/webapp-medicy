<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . "dbconnect.php";
require_once CLASS_DIR . 'search.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'products.class.php';

$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();
$Search       = new Search();
$Products     = new Products();


$searchBatch = FALSE;

if (isset($_GET['prodId'])) {
    $productID = $_GET['prodId'];

    $ProductBatchData = $CurrentStock->showCurrentStocByProductId($productID, $adminId);
    $ProductBatchData = json_decode($ProductBatchData);
}

if ($ProductBatchData != '') {
?>
    <div class="row mx-2 p-1 text-muted border-bottom" style="max-width: 20rem;">
        <div class="col-md-6">Batch no</div>
        <div class="col-md-6">Stock</div>
    </div>
    <?php
    foreach ($ProductBatchData as $itemData) {
        // print_r($itemData);
        $productId  = $itemData->product_id;
        $id = $itemData->id;

        $prodNameFetch = $Products->showProductsById($productId);
        $prodNameFetch = json_decode($prodNameFetch, true);

        if (isset($prodNameFetch['status']) && $prodNameFetch['status'] == '1') {
            $productData = $prodNameFetch['data'];
            $prodName = $productData['name'];
        } else {
            $prodName = 'No Data Found';
        }

        $prodBatch      = $itemData->batch_no;
        $qantity        = $itemData->qty;
        $looseQty       = $itemData->loosely_count;
        $weightage      = $itemData->weightage;
        $unit           = $itemData->unit;
        $packOf         = $weightage . '/' . $unit;
    ?>
        <div class="row mx-2 p-1 border-bottom searched-list" id="<?= $productId ?>" value="<?= $prodBatch ?>" value1="<?= $id ?>" onclick="stockDetails('<?= $productId ?>','<?= $prodBatch ?>', '<?= $id ?>', this.id, this.value, this.value1);">
            <div class="col-md-6"><?= $prodBatch ?></div>
            <div class="col-md-6"><?= $qantity;
                                    ($looseQty > 0) ? "($looseQty)" : "" ?></div>
        </div>
<?php

    }
} else {
    echo "Result Not Found";
}
?>