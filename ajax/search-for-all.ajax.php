<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';


$StockOut = new StockOut;
$StockIn = new StockIn;


// === sod fixd date data fetch =======
if(isset($_GET['searchKey'])){
    $searchFor = $_GET['searchKey'];
    
    $SodOnDateData = $StockOut->salesOfTheDayRange($onDate, $onDate, $adminId);
    echo json_encode($SodOnDateData);
}

?>