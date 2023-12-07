<?php
require_once dirname(__DIR__).'/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'doctors.class.php';


$docId = $_GET['doctor_id'];

$Doctors = new Doctors();
$showDoctor = $Doctors->showDoctorNameById($docId);
$showDoctor = json_decode($showDoctor);

// if( is_array($decodedData) && $showDoctor->status == 1 && !empty($showDoctor->data)){
//     foreach($showDoctor->data as $doctorName){
//         echo $doctorName;
//     }
// } else {
//     echo "No data found";
// }

if($showDoctor != 0 ){
    foreach($showDoctor as $rowDoctor){
        $doctorName = $rowDoctor['doctor_name'];
        echo $doctorName;
    
    }
}


?>