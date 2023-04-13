<?php

// echo $_GET['currentStockId'];


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/patients.class.php';
require_once '../../php_control/idsgeneration.class.php';
require_once '../../php_control/currentStock.class.php';
require_once '../../php_control/stockIn.class.php';
require_once '../../php_control/stockInDetails.class.php';
require_once '../../php_control/productsImages.class.php';
require_once '../../php_control/distributor.class.php';
require_once '../../php_control/products.class.php';
require_once '../../php_control/manufacturer.class.php';
require_once '../../php_control/packagingUnit.class.php';

//Classes Initilizing
$Patients       =   new Patients();
$IdGeneration   =   new IdGeneration();
$currentStoc    =   new CurrentStock();
$StockIn        =   new StockIn();
$StockInDetail  =   new StockInDetails();
$Product        =   new Products();
$ProductImages  =   new ProductImages();
$distributor    =   new Distributor();
$manufacturer   =   new Manufacturer();
$packagUnit     =   new PackagingUnits();


if (isset($_GET['currentStockId'])) {
    $stockId =  $_GET['currentStockId'];
    $showStock = $currentStoc->showCurrentStocById($stockId);
    // print_r($showStock);

    foreach ($showStock as $curntStk) {
        $productId      =  $curntStk['product_id'];
        $batchNo        =  $curntStk['batch_no'];
        $distributorId  =  $curntStk['distributor_id'];
        $date = date('Y-m-d', strtotime($curntStk['added_on']));
        $time = date('H:i:s', strtotime($curntStk['added_on']));
        $currentStockLooseQTY = $curntStk['loosely_count'];
        $currentStockQTY = $curntStk['qty'];
    }


    $prodcutDetails = $Product->showProductsById($productId);
    // echo "<br><br>"; print_r($prodcutDetails);
    foreach ($prodcutDetails as $productData) {
        $packagingType = $productData['packaging_type'];
        $unitQuantity = $productData['unit_quantity'];;
        $unit = $productData['unit'];
    }
    $unitQTY = $unitQuantity . $unit;

    $packageType = $packagUnit->showPackagingUnitById($packagingType);
    // echo "<br><br>"; print_r($packageType);
    $unitName = $packageType[0]['unit_name'];
    $packageDtls = "$unitQTY / $unitName";


    $manufDetails = $manufacturer->showManufacturerById($prodcutDetails[0]['manufacturer_id']);
    // echo "<br><br>"; print_r($manufDetails);

    $distributorDetails = $distributor->showDistributorById($distributorId);
    // echo "<br><br>"; print_r($distributorDetails);

    $stock = $StockIn->stockInDistIdandDateTime($distributorId, $date, $time);
    // echo "<br><br>Stock In : "; print_r($stock);

    $table1 = "product_id";
    $table2 = "distributor_bill";
    $data1 = $showStock[0]['product_id'];
    $data2 = $stock[0]['distributor_bill'];

    $stockDetails = $StockInDetail->showStockInDetailsByTable($table1, $table2, $data1, $data2);
    // echo "<br><br>"; print_r($stockDetails);

    foreach ($stockDetails as $details) {
        $expDate = $details['exp_date'];
        $Weightage = $details['weightage'];
        $Unit = $details['unit'];
        $QTY = $details['qty'];
        $freeQTY = $details['free_qty'];
        $looseQTY = $details['loosely_count'];
        $MRP = $details['mrp'];
        $PTR = $details['ptr'];
        $discountParcent = $details['discount'];
        $BasePrice = $details['base'];
        $GSTparcentage = $details['gst'];
        $gstAmount = $details['gst_amount'];
    }

    $GSTparcentage = " ($GSTparcentage% on PTR)";
    $gstDetails = $gstAmount . $GSTparcentage;

    $purchaseQTY = $QTY + $freeQTY;

    $discountParcent = "$discountParcent%";

    $image = $ProductImages->showImageById($productId);
    // print_r($image);
    if ($image[0][2] != NULL) {
        $productImage = $image[0][2];
    } else {
        $productImage = 'medicy-default-product-image.jpg';
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/bootstrap 5/bootstrap.css">
    <title>Product Details</title>

    <link href="../../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <style>
        .vl {
            border-left: .15rem solid gray;
            height: 8.4rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid d-flex justify-content-center mt-2">
        <div class="container-fluid">
            <div class="row p-4 justify-content-left">
                <div class="col-sm-1 justify-content-center">
                </div>
                <div class="col-sm-4 justify-content-center">
                    <div class="text-center border d-flex justify-content-center">
                        <img src="../../images/product-image/<?php echo $productImage ?>" class="img-fluid rounded" alt="...">
                        <!-- <hr class="hl justify-content-center" style="color: black;"> -->
                    </div>
                </div>
                <!-- <div class="col-sm-1 justify-content-center">
                 <div class="vl justify-content-center"></div> 
                </div> -->
                <div class="col-sm-7 justify-content-center" flex>
                    <h3><?php echo $prodcutDetails[0]['name']; ?></h3>
                    <h7>[<?php echo $prodcutDetails[0]['product_composition']; ?>]</h6>
                        <h5><?php echo $manufDetails[0]['name']; ?></h6>
                </div>
            </div>
            <div class="d-flex justify-content-top">
                <hr class="text-center w-100" style="height: 2px; color:black">
            </div>
            <div class="row mt-2 justify-content-center" flex>
                <div class="col-12 ps-2">
                    <div class="row p-4">
                        <div class="col-6">
                            <strong>Distributor Id: </strong><span><?php echo $distributorId; ?></span><br>
                            <strong>Product Id: </strong><span><?php echo $productId; ?></span><br>
                            <strong>MRP: </strong><span><?php echo $MRP; ?></span><br>
                            <strong>PTR: </strong><span><?php echo $PTR; ?></span><br>
                            <strong>Base Price: </strong><span><?php echo $BasePrice; ?></span><br>
                            <!-- <strong>Purchase Date: </strong><span>25</span><br> -->
                            <strong>Packaging Details : </strong><span><?php echo $packageDtls; ?></span><br>
                            <strong>In Stock: </strong><span><?php echo $currentStockQTY; ?></span><br>
                        </div>
                        <div class="col-6">
                            <strong>Distributor Name: </strong><span><?php echo $distributorDetails[0]['name']; ?></span><br>
                            <strong>Batch No: </strong><span><?php echo $batchNo; ?></span><br>
                            <strong>GST: </strong><span><?php echo $gstDetails; ?></span><br>
                            <strong>Discount: </strong><span><?php echo $discountParcent; ?></span><br>
                            <strong>Exp Date: </strong><span><?php echo $expDate; ?></span><br>
                            <strong>Purchase Quantity: </strong><span><?php echo $purchaseQTY; ?></span><br>
                            <strong>Loose Stock: </strong><span><?php echo $currentStockLooseQTY; ?></span><br>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>