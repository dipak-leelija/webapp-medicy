<?php
require_once '../../php_control/appoinments.class.php';


$appointmentId = $_POST['id'];

$appointments = new Appointments();

$apntDel = $appointments->deleteAppointmentsById($appointmentId);

if ($apntDel) {
    echo 1;
}else {
    echo 0;
}


?>