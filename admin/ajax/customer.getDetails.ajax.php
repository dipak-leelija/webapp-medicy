<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR."dbconnect.php";
require_once CLASS_DIR.'patients.class.php';

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