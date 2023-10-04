<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/measureOfUnit.class.php';


$unitId = $_POST['id'];

$MeasureOfUnits       = new MeasureOfUnits();
$deleteUnit           = $MeasureOfUnits->deleteUnit($unitId);

if ($deleteUnit) {
    echo 1;
}else {
    echo 0;
}


?>