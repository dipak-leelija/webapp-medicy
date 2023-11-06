<?php 
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockOut.class.php';

$StockOut = new StockOut;


// === sod fixd date data fetch =======
if(isset($_GET['sodONDate'])){
    $onDate = $_GET['sodONDate'];
    
    $onDateData = $StockOut->salesOfTheDayRange($onDate, $onDate, $adminId);
    echo json_encode($onDateData);
}


// === sod(sales of the day) fixd date data fetch =======
if(isset($_GET['sodStartDate']) && isset($_GET['sodEndDate'])){
    $strtDt = $_GET['sodStartDate'];
    $endDt = $_GET['sodEndDate'];
    
    $sodOnDateRangeData = $StockOut->salesOfTheDayRange($endDt, $strtDt, $adminId);
    echo json_encode($sodOnDateRangeData);
}
?>