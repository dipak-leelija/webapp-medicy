<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin logged in or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'utility.class.php';

$StockOut = new StockOut;
$StockInDetails = new StockInDetails;
$Distributor = new Distributor;
$Products = new Products;
$Employees = new Employees;
$Utility = new Utility;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dataArray'])) {

    $dataArray = $_GET['dataArray'];
    $dataArray = json_decode($dataArray);

    $startDt = $dataArray->startDt;
    $convertedStarDt =new DateTime($startDt);
    $endDt = $dataArray->endDt;
    $convertedEndDt =new DateTime($endDt);
    $filterBy = $dataArray->filterBy;;


    if($filterBy == 'PM'){
        $stockOutDataReport = $StockOut->stockOutReportOnPaymentMode($startDt, $endDt, $adminId);
    }

    if($filterBy == 'ICAT'){
        $stockOutDataReport = $StockOut->stockOutReportOnItemCategory($startDt, $endDt, $adminId);
    }
    
    print_r($stockOutDataReport);

} else {
    echo json_encode(['status' => false, 'message' => 'Invalid request']);
}
?>