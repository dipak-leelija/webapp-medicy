<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';

require_once CLASS_DIR.'doctors.class.php';


$docId = $_GET['doctor_id'];

$Doctors = new Doctors();
$showDoctor = $Doctors->showDoctorById($docId);

if($showDoctor != 0 ){
    foreach($showDoctor as $rowDoctor){
        $doctorName = $rowDoctor['doctor_name'];
        echo $doctorName;
    
    }
}


?>