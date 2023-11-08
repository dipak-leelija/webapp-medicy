<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'currentStock.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'idsgeneration.class.php';
require_once CLASS_DIR.'stockIn.class.php';
require_once CLASS_DIR.'stockInDetails.class.php';
require_once CLASS_DIR.'productsImages.class.php';
require_once CLASS_DIR.'distributor.class.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'manufacturer.class.php';
require_once CLASS_DIR.'packagingUnit.class.php';

$CurrentStock   = new CurrentStock();
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

    $deleteProductStock = $CurrentStock->deleteCurrentStockbyId($productId);

    // var_dump($deleteProductStock);
    if($deleteProductStock == true){
        echo $productId;
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

    $deleteProductStockByBatch = $CurrentStock->deleteCurrentStock($productId, $productBatchNo);

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