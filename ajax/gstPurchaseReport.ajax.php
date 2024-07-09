<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin logged in or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'utility.class.php';


$StockInDetails = new StockInDetails;
$Utility = new Utility;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dataArray'])) {

    $dataArray = $_GET['dataArray'];
    $dataArray = json_decode($dataArray);

    $searchOn = $dataArray->searchOn;

    if($searchOn == 'P'){
        $gstPurchaseData = $StockInDetails->gstPurchaseDetailsReport($dataArray->startDate, $dataArray->endDate, $adminId);
    }

    if($searchOn == 'PR'){
        
    }

    print_r($gstPurchaseData);

} else {
    echo json_encode(['status' => false, 'message' => 'Invalid request']);
}
?>
