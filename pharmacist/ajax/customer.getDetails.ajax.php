<?php
require_once '../../php_control/patients.class.php';

$Patients = new Patients();

if (isset($_GET["name"])) {
   $customer =  $Patients->patientsDisplayByPId($_GET["name"]);
   echo $customer[0][2];
}

if (isset($_GET["contact"])) {
   $customer =  $Patients->patientsDisplayByPId($_GET["contact"]);
   echo $customer[0][5];
}


?>