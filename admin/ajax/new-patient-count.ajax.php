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
    echo $newPatientsByDay;
}
//  else {
//     echo "Error: 'newPatientDt' not set in POST request.";
// }


/// find new patient by  date range ///
if (isset($_POST['newPatientStartDate']) && isset($_POST['newPatientEndDate'])) {
    $newPatientStartDate = $_POST['newPatientStartDate'];
    $newPatientEndDate   = $_POST['newPatientEndDate'];
    $newPatientsInRangeDate = $Patients->findPatientsInRangeDate($adminId, $newPatientStartDate, $newPatientEndDate );
    echo $newPatientsInRangeDate;
} 
// else {
//     echo "Error: Start and end dates not set in POST request.";
// }

?>
