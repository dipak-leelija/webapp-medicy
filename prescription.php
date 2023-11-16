<?php
require_once 'config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';
require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'appoinments.class.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'doctor.category.class.php';


// Fetching Appointments Info
$appointmentId = $_GET['prescription'];

$appointments   = new Appointments();
$DoctorCategory = new DoctorCategory();


$currentAppointments = $appointments->appointmentsDisplaybyId($appointmentId);
//    print_r($currentAppointments); exit;

foreach($currentAppointments as $currentAppointmentDetails){
    $appointmentDate     = $currentAppointmentDetails['appointment_date'];
    $apntId              = $currentAppointmentDetails['appointment_id'];
    $patientId           = $currentAppointmentDetails['patient_id'];
    $patientName         = $currentAppointmentDetails['patient_name'];
    $patientGurdianName  = $currentAppointmentDetails['patient_gurdian_name'];
    $patientEmail        = $currentAppointmentDetails['patient_email'];
    $patientPhno         = $currentAppointmentDetails['patient_phno'];
    $patientDob          = $currentAppointmentDetails['patient_age'];
    $patientGender       = $currentAppointmentDetails['patient_gender'];
    $patientAddress1     = $currentAppointmentDetails['patient_addres1'];
    $patientAddress2     = $currentAppointmentDetails['patient_addres2'];
    $patientPs           = $currentAppointmentDetails['patient_ps'];
    $patientDist         = $currentAppointmentDetails['patient_dist'];
    $patientPin          = $currentAppointmentDetails['patient_pin'];
    $patientState        = $currentAppointmentDetails['patient_state'];
    $getDoctorForPatient = $currentAppointmentDetails['doctor_id'];
 //    echo var_dump($getDoctorForPatient); exit;

}


// Fetching Doctor Info
$doctors = new Doctors(); //Doctor Class 
$selectDoctorByid = $doctors->showDoctorsForPatient($getDoctorForPatient);
// print_r($selectDoctorByid); exit;
foreach($selectDoctorByid as $DoctorByidDetails){
    $DoctorReg          = $DoctorByidDetails['doctor_reg_no'];
    $DoctorName         = $DoctorByidDetails['doctor_name'];
    $docSpecialization  = $DoctorByidDetails['doctor_specialization'];
    $DoctorDegree       = $DoctorByidDetails['doctor_degree'];
    $DoctorAlsoWith     = $DoctorByidDetails['also_with'];
    $DoctorAddress      = $DoctorByidDetails['doctor_address'];
    $DoctorEmail        = $DoctorByidDetails['doctor_email'];
    $DoctorPhno         = $DoctorByidDetails['doctor_phno'];
}

$showDoctorCategoryById = $DoctorCategory->showDoctorCategoryById($docSpecialization);
foreach ($showDoctorCategoryById as $rowDocCatName) {
    $doctorName = $rowDocCatName['category_name'];
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>prescription.css">
    <title>Prescription - <?= $patientId ?></title>
</head>

<body>
    <div style="box-shadow:none" class="card">
        <div class="hospitslDetails mb-0">
            <div class="row">
                <div class="col-1 headerHospitalLogo">
                    <img class="mt-4" src="<?= $healthCareLogo ?>" alt="<?= $healthCareName ?>">
                </div>
                <div class="col-4 headerHospitalDetails">
                    <h1 class="text-primary text-start fw-bold mb-2 mt-4 me-3"><?= $healthCareName ?></h1>
                    <p class="text-start  me-3">
                        <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ',<br>' . $healthCareState . ', ' . $healthCarePin; ?></small>
                    </p>
                </div>
                <div class="col-2 header-doc-img"> <img src="<?= IMG_PATH ?>medicy-doctor-logo.png" alt=""> </div>
                <div class=" text-danger col-5 headerDoctorDetails">
                    <h2 class="text-end mt-3  mb-0"><?php echo $DoctorName ?></h2>
                    <p class="text-end  mb-0 ">
                        <small><?php if ($DoctorReg != NULL) {
                                    echo 'REG NO : ' . $DoctorReg;
                                } ?></small>
                    </p>

                    <p class="text-end  mb-0 ">
                        <small><?php echo $DoctorDegree . ', ' . $doctorName ?></small>
                    </p>
                    <p class="text-end  mb-0"> <?php echo $DoctorAlsoWith ?></p>
                    <!-- Member of: -->
                    <p class="text-end  mb-0"><?php // echo $DoctorAddress 
                                                ?></p>
                    <h6 class="text-end text-primary"><strong>Call for Appointment:
                            <?= $healthCareApntbkNo ?></strong></h6>
                </div>
            </div>
        </div>
        <hr class="mb-0 mt-0" style="color: #00f;">
        <div>
            <div class="row justify-content-between text-left mt-0">
                <!-- <div class="form-group col-sm-6 flex-column d-flex mt-0">
                    <p class="text-start">Appointment ID: <?php echo $apntId ?></p>
                </div>
                <div class="form-group col-sm-6 flex-column d-flex mt-0">
                    <p class="text-end">Appointment Date: <?php echo $appointmentDate ?></p>
                </div> -->
                <!-- <div class="form-group col-sm-6 flex-column d-flex">
                    <h5 class="text-start">Patient</h5>
                    <h6 class="text-start ms-2 mb-0">Name: <b><?php echo $patientName; ?></b></h6>
                    <p class="text-start ms-2 mb-0">Gurdian Name: <?php echo $patientGurdianName; ?></p>
                    <p class="text-start ms-2 mb-0">Age: <?php echo $patientDob; ?>, Sex: <?php echo $patientGender; ?>
                    </p>
                    <p class="text-start ms-2 mb-0">Patient ID: <?php echo $patientId; ?></p>
                    <p class="text-start ms-2 mb-0">Mobile: <?php echo $patientPhno; ?></p>
                </div>
                <div class="form-group col-sm-6 flex-column d-flex">
                    <h5 class="text-start">Address</h5>
                    <p class="text-start ms-2 mb-0"><?php echo $patientAddress1 ?></p>
                    <p class="text-start ms-2 mb-0"><?php echo $patientPs . ', ' . $patientDist . ', ' . $patientPin ?></p>
                    <p class="text-start ms-2 mb-0"><?php echo $patientState ?></p>
                </div> -->
            </div>
        </div>
        <!-- <hr> -->
        <!-- <div class="space">
        </div> -->
        <div class="row space mt-1">
            <div class="col-3 border-end " style="border-color: #0000ff59 !important;">
                <small>
                    A-ID: <?php echo $apntId ?>
                    <br>
                    P-ID: <?php echo $patientId ?>
                    <div class="mt-2">
                        BP:
                        <br>
                        WT:
                    </div>
                </small>
                <div class="mt-5">
                    <h6 class="text-center"><u> DIAGNOSIS </u></h6>
                    TC,DC,Hb%,ESR
                    <br>
                    BT,CT
                    <br>
                    BI,Sugar(F. & P.P)
                    <br>
                    GR. & Rh.type
                    <br>
                    VDRL
                    <br>
                    Lipid Profile
                    <br>
                    HIV-I & II
                    <br>
                    HBsAg
                    <br>
                    Urea
                    <br>
                    Creatine
                    <br>
                    TSH,T3,T4
                    <br>
                    Bilirubin
                    <br>
                    M.P.
                    <br>
                    L.F.T
                    <br>
                    Urine (RE/ME/CS)
                    <br>
                    Urine Pregnency
                    <br>
                    X-Ray Chest = PA
                    <br>
                    E.C.G
                    <br>
                    Serum PSA Titre
                    <br>
                    USG-W/A-L/A,FPP

                </div>
            </div>
            <div class="col-9">
                <div class="row mt-1">
                    <div class="col-12 d-flex justify-content-between">
                        <p class="mb-0 mt-0">
                            Name: <?php echo $patientName; ?>
                            <span class="ms-3"> Age: <?php echo $patientDob; ?> </span>
                            <span class="ms-3"> Sex: <?php echo $patientGender; ?> </span>
                        </p>

                        <p class="mb-0 mt-0 text-end">
                            Date:
                            <?php
                            $date = date_create($appointmentDate);
                            echo date_format($date, "d-m-Y");
                            ?>
                        </p>
                    </div>
                </div>
                <hr class="row mt-2 m-auto" style="color: #00f;">
            </div>
        </div>
        <div class=" footer ">
            <div class="row border border-primary pt-2 pb-0 d-flex justify-content-between">

                <div class="col-md-4 custom-width-name mb-0">
                    <ul style="margin-bottom: 8px">
                        <li class=" list-unstyled"><img id="healthcare-name-box" class="pe-2"
                                src="<?= IMG_PATH ?>icons/hospital.png" alt="Healt Care"
                                style="width:28px; height:20px;" /><?php echo $healthCareName ?></li>
                    </ul>
                </div>

                <div class="col-md-4 custom-width-email mb-0">
                    <ul style="margin-bottom: 8px">
                        <li class="list-unstyled"><img id="email-box" class="pe-2"
                                src="<?= IMG_PATH ?>icons/email-logo.png" alt="Email"
                                style="width:28px; height:20px;" /><?= $healthCareEmail ?></li>

                    </ul>
                </div>

                <div class="col-md-4 custom-width-number mb-0">
                    <ul style="margin-bottom: 8px">
                        <li class="list-unstyled"><img id="number-box" class="pe-2"
                                src="<?= IMG_PATH ?>icons/call-logo.png" alt="Contact"
                                style="width:28px; height:20px;" /><span><?= $healthCareApntbkNo.', '.$healthCarePhno ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <p class="text-center text-info"><strong>বিঃ দ্রঃ - যেকোন জরুরি অবস্থায় অনুগ্রহ করে নিকটবর্তি হাসপাতালে
                    যোগাযোগ করুন।</strong></p>
        </div>
        <!-- <div class="row">
        </div> -->
    </div>
    <div class="printButton mb-5">
        <button class="btn btn-primary" onclick="history.back()">Go Back</button>
        <button class="btn btn-primary" onclick="window.print()">Print Prescription</button>
    </div>
</body>

</html>