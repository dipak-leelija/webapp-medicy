<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/currentStock.class.php';
require_once '../../php_control/patients.class.php';
require_once '../../php_control/idsgeneration.class.php';
require_once '../../php_control/stockIn.class.php';
require_once '../../php_control/stockInDetails.class.php';
require_once '../../php_control/productsImages.class.php';
require_once '../../php_control/distributor.class.php';
require_once '../../php_control/products.class.php';
require_once '../../php_control/manufacturer.class.php';
require_once '../../php_control/packagingUnit.class.php';

$CurrentStock = new CurrentStock();
$Patients       =   new Patients();
$IdGeneration   =   new IdGeneration();
$StockIn        =   new StockIn();
$StockInDetail  =   new StockInDetails();
$Product        =   new Products();
$ProductImages  =   new ProductImages();
$distributor    =   new Distributor();
$manufacturer   =   new Manufacturer();
$packagUnit     =   new PackagingUnits();


if (isset($_POST['delID'])) {
    $productId =  $_POST['delID'];
    // echo $productId;

    if($productId != null){
        echo 1;
    }else{
        echo 0;
    }
    // $showStock = $CurrentStock->showCurrentStocByPId($productId);
    // print_r($showStock);
    // echo count($showStock);

}


if (isset($_POST['pBatchNO'])) {
    $productId =  $_POST['pId'];
    $productBatchNo = $_POST['pBatchNO'];
    // echo $productId;

    if($productId != null){
        echo 1;
    }else{
        echo 0;
    }
    // $showStock = $CurrentStock->showCurrentStocByPId($productId);
    // print_r($showStock);
    // echo count($showStock);

}

?>