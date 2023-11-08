<?php 

require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'patients.class.php';

$Patients   = new Patients;


/// find new patient by selected date ///
if (isset($_POST['newPatientDt'])) {
    $newPatientDt = $_POST['newPatientDt'];
    $newPatientsByDay = $Patients->newPatientByDay($adminId, $newPatientDt);
    echo json_encode($newPatientsByDay);
}



/// find new patient by  date range ///
if (isset($_GET['newStartDate']) && isset($_GET['newEndDate'])) {
    $newStartDate = $_GET['newStartDate'];
    $newEndDate = $_GET['newEndDate'];
    $newPatientsInRangeDate = $Patients->findPatientsInRangeDate($adminId, $newStartDate, $newEndDate);

    echo json_encode($newPatientsInRangeDate);
}

?>
