<?php
require_once realpath(dirname(dirname(__DIR__)) . '/config/constant.php');
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'distributor.class.php';

$Distributor = new Distributor();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['distributorId']) && isset($_POST['newStatus'])) {
    $distributorId = $_POST['distributorId'];
    $newStatus = $_POST['newStatus'];

    $updateDistStatus = $Distributor->updateDistStatus($newStatus, $distributorId);
    if ($updateDistStatus) {
         
        $deleteDistRequest = $Distributor->deleteDistRequest($distributorId);
        // echo json_encode($deleteDistRequest);
        if ($deleteDistRequest) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete distributor request']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update distributor status']);
    }
} else {
    echo 'Invalid request';
}
