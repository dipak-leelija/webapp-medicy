<?php
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';

$Distributor = new Distributor();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['distributorId']) && isset($_POST['newStatus'])) {
    $distributorId = $_POST['distributorId'];
    $newStatus = $_POST['newStatus'];
    
    $updateDistStatus = $Distributor->updateDistStatus($newStatus,$distributorId);
    if($updateDistStatus){
        echo json_encode($updateDistStatus);
    }
} else {
    echo 'Invalid request';
}
?>
