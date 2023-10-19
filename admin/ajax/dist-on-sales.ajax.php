<?php 

require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'distributor.class.php';

$StockIn           = new StockIn();
$Distributor       = new Distributor;

if(isset($_GET["id"])){
    $maxPurchase = $StockIn->selectDistOnMaxPurchase($adminId);
    $maxPurchase = json_encode($maxPurchase);
    $distId = $maxPurchase->distributor_id;
    $distName = $Distributor->distributorDetail();
    
    return $maxPurchase;
}



if(isset($_GET["id"])){
    $maxPurchase = $StockIn->selectDistOnMaxPurchase($adminId);
    $distName = $Distributor->distributorDetail($maxItems->distributor_id);
}









// $distName = $Distributor->distributorDetail($maxItems->distributor_id);
// print_r($distName);
?>