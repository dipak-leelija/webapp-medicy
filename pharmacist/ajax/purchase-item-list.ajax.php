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
require_once '../../php_control/packagingUnit.class.php';


$CurrentStock = new CurrentStock();
$Manufacturer = new Manufacturer();
$Search       = new Search();
$PackagingUnits = new PackagingUnits();

require_once '../../employee/config/dbconnect.php';

$searchResult = null;
if (isset($_GET['data'])) {
    $data = $_GET['data'];
    // echo $data;
    $resultData = array();
    $searchSql = "Select * From `products` WHERE `products`.`name` LIKE '%$data%'";
    $searchResult = mysqli_query($conn, $searchSql) or die("Connection Error");
    while ($result = $searchResult->fetch_array()) {
        $resultData[] = $result;
    }
    // $searchResult = $Search->searchForSale($data);
    print_r($resultData);
}

if ($resultData) {

    // echo "<h5 style='padding-left: 12px ; padding-top: 5px ;'><a>".$searchResult."</a></h5>";
?>
    <div class="row border-bottom border-primary small mx-0 mb-2">
        <div class="col-md-4">Searched For</div>
        <div class="col-md-4">Composition</div>
        <div class="col-md-2">Unit/Pack</div>
        <div class="col-md-2">Stock</div>
    </div>
    <?php
    foreach ($resultData as $resultRow) {

        $productId  = $resultRow['product_id'];
        $productName = $resultRow['name'];
        $pComposition = $resultRow['product_composition'];
        $weightage   = $resultRow['unit_quantity'];
        $unit        = $resultRow['unit'];
        $packagingType = $resultRow['packaging_type'];
        $packDetails = $PackagingUnits->showPackagingUnitById($packagingType);
        foreach ($packDetails as $packData) {
            $packageType = $packData['unit_name'];
        }
        $packOf      = $weightage . $unit . '/' . $packageType;
        $manufacturerId = $resultRow['manufacturer_id'];
        $manufacturer = $Manufacturer->showManufacturerById($manufacturerId);

        foreach ($manufacturer as $row) {
            $manufacturerName = $row['name'];
        }

        $power = '';
        $power       = $resultRow['power'];
        if ($power != NULL) {
            $power = ' | ' . $resultRow['power'];
        }

        if ($unit == "tab" || $unit == "cap") {
            $unitType = 'loosely_count';
            $stock = $CurrentStock->showCurrentStocByUnit($productId, $unitType);
        } else {
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
        <div class="row mx-0 py-2 border-bottom p-row item-list" id="<?php echo $productId ?>" onclick="getDtls(this.id);">
            <div class="col-md-4"><?php echo $productName, $power ?><br>
                <small><?php echo $manufacturerName ?></small>
            </div>
            <div class="col-md-4"><small><?php echo $pComposition ?></small></div>
            <div class="col-md-2"><small><?php echo $packOf ?></small></div>
            <div class="col-md-2"><small><?php echo $stockQty;
                                            if ($looseQty > 0) {
                                                echo "($looseQty)";
                                            }
                                            echo "" ?> </small></div>
        </div>
<?php

    }

} else {
    echo '<div class="row border-bottom border-primary small mx-0 mb-2">
    <label style="color: red;"><b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"Product Not Found / Check Spelling";</b></label>
    </div>';
}
?>