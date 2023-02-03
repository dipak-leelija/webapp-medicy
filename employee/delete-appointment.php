<?php  
require_once '../php_control/appoinments.class.php';


session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
  header("location: login.php");
  exit();
}

$appointmentId = $_GET['delete-appointment'];

$appointments = new Appointments();
$deleteAppointmnt = $appointments->deleteAppointmentsById($appointmentId);
if ($deleteAppointmnt) {
	header("location: appointments.php");
	echo "<script>alert('Record Deleted!')</script>";


}else{
  echo "<script>alert('Deletion Faield')</script>";
}


?>