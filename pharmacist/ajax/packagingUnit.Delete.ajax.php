<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/packagingUnit.class.php';


$unitId = $_POST['id'];

$PackagingUnits       = new PackagingUnits();
$deleteUnit = $PackagingUnits->deleteUnit($unitId);

if ($deleteUnit) {
    echo 1;
}else {
    echo 0;
}


?>