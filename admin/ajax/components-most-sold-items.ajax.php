<?php

require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'stockOut.class.php';

$Products   = new Products;
$StockOut   = new StockOut;

if (isset($_POST['prodId'])) {
    $productId =  $_POST['prodId'];
    $productId = json_decode($productId);
    $prodName = array();
    for($i = 0; $i<count($productId); $i++){
        $proData = $Products->showProductsById($productId[$i]);
        array_push($prodName, $proData[0]['name']);
    }
    $prodName = json_encode($prodName);
    echo $prodName;
}



if (isset($_POST['dtRange'])) {
    $dtRange =  new DateTime($_POST['dtRange']);
    $curDate = new DateTime();
    $interval = $curDate->diff($dtRange);
    $daysDiff = $interval->days;
    $dateRangeMostStoldItems = $StockOut->mostSoldStockOutDataGroupByDtRange($daysDiff, $adminId);
    echo json_encode($dateRangeMostStoldItems);
}
?>