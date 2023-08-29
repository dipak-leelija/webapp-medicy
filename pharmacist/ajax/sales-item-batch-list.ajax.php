<!-- <style>
.searched-list:hover {
    background: #3e059b26;
    cursor: pointer;
}
</style> -->

<?php
require_once '../../php_control/search.class.php';
require_once '../../php_control/currentStock.class.php';
require_once '../../php_control/manufacturer.class.php';
require_once '../../php_control/products.class.php';

$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();
$Search       = new Search();
$Products     = new Products();

require_once '../../employee/config/dbconnect.php';
$searchBatch = FALSE;
if(isset($_GET['batchDetails'])){
    $productID = $_GET['batchDetails'];
    $ProductBatchData = $CurrentStock->showCurrentStocByProductId($productID);
}

if($ProductBatchData != null){
    // echo "<h5 style='padding-left: 12px ; padding-top: 5px ;'><a>".$serchR."</a></h5>";
    ?>
<div class="row mx-2 p-1 text-muted border-bottom" style="max-width: 20rem;">
    <!-- <div class="col-md-5">Preoduct</div> -->
    <div class="col-md-6">Batch no</div>
    <div class="col-md-6">Stock</div>
</div>
<?php
    foreach($ProductBatchData as $itemData){
        // print_r($itemData);
        $productId  = $itemData['product_id'];
        $id = $itemData['id'];

        $prodNameFetch = $Products->showProductsById($productId);
        foreach($prodNameFetch as $productData){
            $prodName = $productData['name'];
        }

        $prodBatch   = $itemData['batch_no'];
        $qantity   = $itemData['qty'];
        $looseQty   = $itemData['loosely_count'];
        $weightage   = $itemData['weightage'];
        $unit        = $itemData['unit'];
        $packOf      = $weightage.'/'.$unit;
        ?>
            <div class="row mx-2 p-1 border-bottom searched-list" id="<?php echo $productId ?>" value="<?php echo $prodBatch ?>" value1="<?php echo $id ?>" onclick="stockDetails('<?php echo $productId ?>','<?php echo $prodBatch ?>', '<?php echo $id ?>', this.id, this.value, this.value1);">
                <!-- <div class="col-md-5"><?php echo $prodName ?></div> -->
                <div class="col-md-6"><?php echo $prodBatch ?></div>
                <div class="col-md-6"><?php echo $qantity;
                if($looseQty > 0){
                    echo "($looseQty)";
                }else
                echo "" ?></div>
            </div> 
<?php

    }
}
// else{
//     echo "Result Not Found";
// }
?>