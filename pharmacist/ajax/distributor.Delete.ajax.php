<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/distributor.class.php';


$distributorId = $_POST['id'];

$Distributor       = new Distributor();
$deleteDist = $Distributor-> deleteDist($distributorId);

if ($deleteDist) {
    echo 1;
}else {
    echo 0;
}


?>