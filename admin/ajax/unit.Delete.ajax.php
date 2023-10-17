<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';

require_once CLASS_DIR.'measureOfUnit.class.php';


$unitId = $_POST['id'];

$MeasureOfUnits       = new MeasureOfUnits();
$deleteUnit           = $MeasureOfUnits->deleteUnit($unitId);

if ($deleteUnit) {
    echo 1;
}else {
    echo 0;
}


?>