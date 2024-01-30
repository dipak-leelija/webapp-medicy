<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'manufacturer.class.php';

$Manufacturer = new Manufacturer();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manufacturerId']) && isset($_POST['newStatus'])) {
    $manufacturerId = $_POST['manufacturerId'];
    $newStatus = $_POST['newStatus'];

    $updateManuStatus = $Manufacturer->updateManuStatus($newStatus, $manufacturerId);
    if ($updateManuStatus) {

        $deleteManufacturer = $Manufacturer->deleteRequestManufacturer($manufacturerId);
        $updateNewBadges = $Manufacturer->updateNewBadges($manufacturerId);
        if ($deleteManufacturer || $updateNewBadges) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete Manufacturer request']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update Manufacturer status']);
    }
    // if($updateManuStatus){
    //     echo json_encode($updateManuStatus);
    // }
} else {
    echo 'Invalid request';
}
