<?php
session_start();
if (!isset($_SESSION['employee_username']) || $_SESSION['loggedin'] != true) {
  header("location: config/login.php");
  exit();
}

require_once '../../php_control/hospital.class.php';
require_once '../../php_control/appoinments.class.php';
require_once '../../php_control/patients.class.php';


//Initilizing Classes
$hospital         = new HelthCare();
$appointments     = new Appointments();
$Patients         = new Patients();


// Fetching Hospital Info
$hospitalDetails = $hospital->showhelthCare();
foreach($hospitalDetails as $showShowHospital){
    $hospitalName = $showShowHospital['hospital_name'];
}


    if (isset($_POST['submit'])) {
    $patientId          = $_POST['patientId'];
    $appointmentDate    = $_POST["appointmentDate"];
    $patientName        = $_POST["patientName"];
    $patientGurdianName = $_POST["patientGurdianName"];
    $patientEmail       = $_POST["patientEmail"];
    $patientPhoneNumber = $_POST["patientPhoneNumber"];
    $patientAge         = $_POST["patientAge"];
    $patientWeight      = $_POST["patientWeight"];
    $gender             = $_POST["gender"];
    $patientAddress1    = $_POST["patientAddress1"];
    $patientAddress2    = $_POST["patientAddress2"];
    $patientPS          = $_POST["patientPS"];
    $patientDist        = $_POST["patientDist"];
    $patientPIN         = $_POST["patientPIN"];
    $patientState       = $_POST["patientState"];
    $patientDoctor      = $_POST["patientDoctor"];
    // echo 'Working';
    // exit;
    
    $healthCareNameTrimed = strtoupper(substr($hospitalName, 0, 2));//first 2 leter oh healthcare center name
    $appointmentDateForId = str_replace("-", "", $appointmentDate);//removing hyphen from appointment date
    $randCode = rand(1000, 9999);//generating random number

    // Appointment iD Generated
    $appointmentId = $healthCareNameTrimed.''.$appointmentDateForId.''.$randCode ;
    

    // Inserting Into Appointments Database
    $addAppointment = $appointments->addFromInternal($appointmentId, $appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor);

    if ($addAppointment) {
      $patientsDisplayByPId = $Patients->patientsDisplayByPId($patientId);
      foreach($patientsDisplayByPId as $rowPatient){
        $visited = $rowPatient['visited'];
        // echo $visited;
        // exit;
        $visited = $visited + 1;
      }
      echo $visited;
       // Inserting Into Patients Database
      $updatePatientsVisitingTime = $Patients->updatePatientsVisitingTime($patientId, $patientEmail, $patientPhoneNumber, $patientAge, $visited);
      if ($updatePatientsVisitingTime) {
        echo '<script>alert(Appointment Added!)</script>';
        setcookie("appointmentId", $appointmentId, time() + (120 * 30), "/");
        header("location: ../appointment-sucess.php");
        exit();
      }else{
        echo "<script>alert('Patient Not Inserted, Something is Wrong!')</script>";
      }
    }else{
      echo "Something is wrong! ";
    }
}
      ?>