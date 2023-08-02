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

$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();
$Search       = new Search();


require_once '../../employee/config/dbconnect.php';

$searchResult = FALSE;
 

if(isset($_GET['data'])){
    $data = $_GET['data'];

    $searchSql ="Select * From `products` WHERE `products`.`name` LIKE '%$data%'";
    $searchResult = mysqli_query($conn, $searchSql) or die ("Connection Error") ;
    // $searchResult = $Search->searchForSale($data);
}

if($searchResult){
    // echo "<h5 style='padding-left: 12px ; padding-top: 5px ;'><a>".$serchR."</a></h5>";
    ?>
<div class="row mx-2 p-1 text-muted border-bottom">
    <div class="col-md-6">Searched For</div>
    <div class="col-md-3">Unit/Pack</div>
    <div class="col-md-3">Stock</div>
</div>
<?php
    while($resultRow = mysqli_fetch_array($searchResult)){

        $productId  = $resultRow['product_id'];
        $productName = $resultRow['name'];
        $weightage   = $resultRow['unit_quantity'];
        $unit        = $resultRow['unit'];
        $packOf      = $weightage.'/'.$unit;
        $manufacturerId = $resultRow['manufacturer_id'];
        $manufacturer = $Manufacturer->showManufacturerById($manufacturerId);

        foreach ($manufacturer as $row) {
            $manufacturerName = $row['name'];
        }
        
        $power = '';
        $power       = $resultRow['power'];
        if ($power != NULL) {
            $power = ' | '.$resultRow['power'];
        }

        if ($unit == "tab" || $unit == "cap") {
            $unitType = 'loosely_count';
            $stock = $CurrentStock->showCurrentStocByUnit($productId, $unitType);
        }else{
            $unitType = 'qty';
            $stock = $CurrentStock->showCurrentStocByUnit($productId, $unitType);
        }


        $stockQty = 0;
        $looseQty = 0;
        
            if ($stock != NULL) {
                foreach ($stock as $row) {
                    $stockQty += $row['qty'];
                    $looseQty += $row['loosely_count'];
                }
            }

            ?>
            <div class="row mx-2 p-1 border-bottom searched-list" id="<?php echo $productId ?>" value1="<?php echo $productName ?>" value2="<?php echo $stockQty ?>" onclick="itemsBatchDetails('<?php echo $productId ?>','<?php echo $productName ?>','<?php echo $stockQty ?>', this.id, this.value1, this.value2);">
                <div class="col-md-6"><?php echo $productName, $power, "hello" ?><br>
                <small><?php echo $manufacturerName ?></small></div>
                <div class="col-md-3"><small><?php echo $packOf ?></small></div>
                <div class="col-md-3"><small><?php echo $stockQty;
                if($looseQty > 0){
                    echo "($looseQty)";
                } 
                echo "" ?> </small></div>
            </div> 
<?php

    }
}
else{
    echo "Result Not Found";
}
?>

