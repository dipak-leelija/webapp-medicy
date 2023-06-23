<?php 
require_once "../../../php_control/stockOut.class.php";

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
