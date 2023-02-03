<?php

require_once '../php_control/appoinments.class.php';
require_once '../php_control/hospital.class.php';
require_once '../php_control/doctors.class.php';


   // Fetching Appointments Info
   $appointmentId = $_GET['prescription'];

   $appointments = new Appointments();
   $currentAppointments =$appointments->appointmentsDisplaybyId($appointmentId);
   foreach($currentAppointments as $currentAppointmentDetails){
       $appointmentDate =$currentAppointmentDetails['appointment_date'];
       $apntId =$currentAppointmentDetails['appointment_id'];
       $patientName =$currentAppointmentDetails['patient_name'];
       $patientGurdianName =$currentAppointmentDetails['patient_gurdian_name'];
       $patientEmail =$currentAppointmentDetails['patient_email'];
       $patientPhno =$currentAppointmentDetails['patient_phno'];
       $patientDob =$currentAppointmentDetails['patient_dob'];
       $patientGender =$currentAppointmentDetails['patient_gender'];
       $patientAddress1 =$currentAppointmentDetails['patient_addres1'];
       $patientAddress2 =$currentAppointmentDetails['patient_addres2'];
       $patientPs =$currentAppointmentDetails['patient_ps'];
       $patientDist =$currentAppointmentDetails['patient_dist'];
       $patientPin =$currentAppointmentDetails['patient_pin'];
       $patientState =$currentAppointmentDetails['patient_state'];
       $getDoctorForPatient =$currentAppointmentDetails['doctor_id'];
    }

        // Fetching Hospital Info
        $hospital = new HelthCare();
        $hospitalShow = $hospital->showhelthCare();
        foreach($hospitalShow as $hospitalDetails){
            $hospitalName = $hospitalDetails['hospital_name'];
            $address1 = $hospitalDetails['address_1'];
            $address2 = $hospitalDetails['address_2'];
            $city = $hospitalDetails['city'];
            $pin = $hospitalDetails['pin'];
            $state = $hospitalDetails['health_care_state'];

            $hospitalEmail = $hospitalDetails['hospital_email'];
            $hospitalPhno = $hospitalDetails['hospital_phno'];
        }

        // Fetching Doctor Info
        $doctors = new Doctors(); //Doctor Class 
    $selectDoctorByid = $doctors->showDoctorsForPatient($getDoctorForPatient);
    foreach($selectDoctorByid as $DoctorByidDetails){
        $DoctorReg = $DoctorByidDetails['doctor_reg_no'];
        $DoctorName = $DoctorByidDetails['doctor_name'];
        $DoctorSpecialization = $DoctorByidDetails['doctor_specialization'];
        $DoctorDegree = $DoctorByidDetails['doctor_degree'];
        $DoctorAlsoWith = $DoctorByidDetails['also_with'];
        $DoctorAddress = $DoctorByidDetails['doctor_address'];
        $DoctorEmail = $DoctorByidDetails['doctor_email'];
        $DoctorPhno = $DoctorByidDetails['doctor_phno'];
    }


?>

<!doctype html> 
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../employee/require/patient-style.css">
    <title>Prescription</title>
</head>

<body>
    <div class="card">
        <div class="hospitslDetails mb-0">
            <div class="row">
                <div class="col-2 headerHospitalLogo">
                    <img class="mt-4 ms-4" src="../images/logo-p.jpg" alt="XYZ Hospital"
                        style="width:110px; height:100px;">
                </div>
                <div class="col-6 headerHospitalDetails">
                    <h1 class="text-primary text-start fw-bold mb-2 mt-4 me-3"><?php echo $hospitalName ?></h1>
                    <p class="text-start  me-3"><small><?php echo $address1.', '.$address2.', '.$city.',<br>'.$state.', '.$pin; ?></small></p>
                </div>
                <div class="headerDoctorDetails">
                    <h2 class="text-end mt-4 me-3 mb-0"><?php echo $DoctorName ?></h2>
                    <p class="text-end mb-1 mb-0 me-3">
                        <small><?php echo $DoctorDegree.', '.$DoctorSpecialization ?></small></p>
                    <p class="text-end me-3 mb-0">Member of: <?php echo $DoctorAlsoWith ?></p>
                    <p class="text-end me-3 mb-0"><?php // echo $DoctorAddress ?></p>
                    <h6 class="text-end me-3"><small>Call for Appointment: <?php echo $DoctorPhno ?></small></h6>
                </div>
            </div>
        </div>
        <hr class="mb-0 mt-0">
        <div>
            <div class="row justify-content-between text-left mt-0">
                <div class="form-group col-sm-6 flex-column d-flex mt-0">
                    <p class="text-start">Appointment ID: <?php echo $apntId ?></p>
                </div>
                <div class="form-group col-sm-6 flex-column d-flex mt-0">
                    <p class="text-end">Appointment Date: <?php echo $appointmentDate ?></p>
                </div>
                <div class="form-group col-sm-6 flex-column d-flex">
                    <h5 class="text-start">Patient</h5>
                    <h6 class="text-start ms-2 mb-0">Name: <b><?php echo $patientName ?></b></h6>
                    <p class="text-start ms-2 mb-0">Gurdian Name: <?php echo $patientGurdianName ?></p>
                    <p class="text-start ms-2 mb-0">Age: <?php echo $patientDob ?>, Sex: <?php echo $patientGender ?>
                    </p>
                    <p class="text-start ms-2 mb-0">Email: <?php echo $patientEmail ?></p>
                    <p class="text-start ms-2 mb-0">Mobile: <?php echo $patientPhno ?></p>
                </div>
                <div class="form-group col-sm-6 flex-column d-flex">
                    <h5 class="text-start">Address</h5>
                    <p class="text-start ms-2 mb-0"><?php echo $patientAddress1 ?></p>
                    <p class="text-start ms-2 mb-0"><?php echo $patientAddress2 ?></p>
                    <p class="text-start ms-2 mb-0"><?php echo $patientPs.', '. $patientDist.', '. $patientPin ?></p>
                    <p class="text-start ms-2 mb-0"><?php echo $patientState ?></p>
                </div>
            </div>
        </div>
        <hr>
        <div class="space">
        </div>
        <hr>
        <div class="row footer border border-primary pt-2 pb-0">
            <div class="col-md-4 custom-width-name mb-0">
                <ul>
                    <li class=" list-unstyled"><img id="healthcare-name-box" class="pe-2" src="../employee/partials/hospital.png" alt="Healt Care" style="width:28px; height:20px;" /><?php echo $hospitalName ?></li>
                </ul>
            </div>

            <div class="col-md-4 custom-width-email mb-0">
                <ul>
                    <li class="list-unstyled"><img id="email-box" class="pe-2" src="../employee/partials/email-logo.png" alt="Email" style="width:28px; height:20px;" /><?php echo $hospitalEmail ?></li>

                </ul>
            </div>

            <div class="col-md-4 custom-width-number mb-0">
                <ul>
                    <li class="list-unstyled"><img id="number-box" class="pe-2" src="../employee/partials/call-logo.png" alt="Contact" style="width:28px; height:20px;" /><span><?php echo $hospitalPhno.', '.$hospitalPhno ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="printButton">
        <button onclick="history.back()">Go Back</button>
        <button onclick="window.print()">Print Prescription</button>
    </div>
</body>

</html>