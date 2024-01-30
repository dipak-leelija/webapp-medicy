<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'packagingUnit.class.php';

$PackagingUnits = new PackagingUnits();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unitId']) && isset($_POST['newStatus'])) {
    $packagingUnitId = $_POST['unitId'];
    $newStatus       = $_POST['newStatus'];

    $updatePackagingStatus = $PackagingUnits->updatePackStatus($newStatus, $packagingUnitId);
    if ($updatePackagingStatus) {
        $deletePackRequest = $PackagingUnits->deletePackRequest($packagingUnitId);
        $updateNewBadges   = $PackagingUnits->updateNewBadges($packagingUnitId);
        if ($deletePackRequest || $updateNewBadges) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete Manufacturer request']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update Manufacturer status']);
    }
} else {
    echo 'Invalid request';
}
