<style>
.searched-list:hover {
    background: #3e059b26;
    cursor: pointer;
}
</style>

<?php
require_once '../../php_control/search.class.php';
require_once '../../php_control/currentStock.class.php';
require_once '../../php_control/manufacturer.class.php';

$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();


require_once '../../employee/config/dbconnect.php';

$searchResult = FALSE;
if(isset($_GET['data'])){
    $data = $_GET['data'];

    $searchSql ="Select * From `products` WHERE `products`.`name` LIKE '%$data%'";
    $searchResult = mysqli_query($conn, $searchSql) or die ("Connection Error") ;
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
    while($result = mysqli_fetch_array($searchResult)){
        print_r($result);
        foreach( $result as $resultRow){
        
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

        // echo array_shift($productId);

        $stock = $CurrentStock->showCurrentStocByPId($productId);
        // echo $productId;
        print_r($stock);
        $stockQty = 0;
        $looseQty = 0;
        
            // if ($stock != NULL) {
                foreach ($stock as $row) {
                    $stockQty = $row['qty'];
                   echo $looseQty = $row['loosely_count'];

            //         echo '
            // <div class="row mx-2 p-1 border-bottom searched-list" id="'.$productId.'" onclick="stockDetails(this.id);">
            //     <div class="col-md-6">'.$productName, $power.'<br>
            //     <small>'.$manufacturerName.'</small></div>
            //     <div class="col-md-3"><small>'.$packOf.'</small></div>
            //     <div class="col-md-3"><small>'.$stockQty;
            //     if($looseQty > 0){
            //         echo '('.$looseQty.')';
            //     } 
            //     echo'</small></div>
            // </div>';

            
                }
                // exit;
            // }

        }
            
    }

}
else{
    echo "Result Not Found";
}

?>