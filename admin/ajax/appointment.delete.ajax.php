<?php
require_once realpath(dirname(dirname(__DIR__)).'/config/constant.php');
// require_once dirname(__DIR__).'/config/constant.php';
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'appoinments.class.php';


$appointmentId = $_POST['id'];
print_r($appointmentId);

$appointments = new Appointments();

$apntDel = $appointments->deleteAppointmentsById($appointmentId);

if ($apntDel) {
    echo 1;
}else {
    echo 0;
}


?>