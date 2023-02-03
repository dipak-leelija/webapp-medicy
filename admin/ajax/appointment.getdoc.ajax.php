<?php

require_once '../../php_control/doctors.class.php';



$doctorId = $_GET['doctor_shift'];



$doctors = new Doctors();

$showTiming = $doctors->doctorsTimingByDoctor($doctorId);



// if($doctor!=""){

    foreach($showTiming as $showTimingDetails){

        $days = $showTimingDetails['days'];

        $shift = $showTimingDetails['shift'];

        // echo $days , $shift;

        echo'<option value='.$days.'-'.$shift.'>'. $days.'   ('. $shift.')</option>';

    }

// }

                                 

?>