<?php
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'packagingUnit.class.php';

$PackagingUnits = new PackagingUnits();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unitId']) && isset($_POST['newStatus'])){
    $packagingUnitId = $_POST['unitId'];
    $newStatus       = $_POST['newStatus'];
    
    $updatePackagingStatus = $PackagingUnits->updatePackStatus($newStatus , $packagingUnitId);
    if($updatePackagingStatus){
        echo json_encode($updatePackagingStatus);
    }
} else {
    echo 'Invalid request';
}
?>