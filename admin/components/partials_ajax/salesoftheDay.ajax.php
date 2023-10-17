<?php
require_once dirname(dirname(dirname(__DIR__))).'/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'stockOut.class.php';

$StockOut = new StockOut();



// ========== last 7 days sales data =================
if (isset($_GET["lstWeek"])) {
    $value = $_GET["lstWeek"];
    
    echo $value;

}

//========== last 30 days sales data =================
if (isset($_GET["lstMnth"])) {
    $value = $_GET["lstMnth"];
    
    echo $value;

}




?>
