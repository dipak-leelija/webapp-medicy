<?php
require_once "../../php_control/stockReturn.class.php";
require_once "../../php_control/distributor.class.php";
require_once "../../php_control/products.class.php";

$PurchaseReturn = new StockReturn();
$DistributorDetils = new Distributor();
$Product = new Products();

$table1 = 'id';
$table2 = 'status';
$data2 = 'active';

$checkId = $_POST['Id'];

$checkReturnEdit = $PurchaseReturn->stockReturnByTables($table1, $checkId, $table2, $data2);
// print_r($checkReturnEdit)

if($checkReturnEdit != null){
    echo 1;
}else{
    echo 0;
}

?>