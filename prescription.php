<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


// Fetching Appointments Info
$appointmentId = url_dec($_GET['prescription']);
$appointments       = new Appointments();
$DoctorCategory     = new DoctorCategory();


$currentAppointments = $appointments->appointmentsDisplaybyId($appointmentId);

foreach ($currentAppointments as $currentAppointmentDetails) {
    $appointmentDate     = $currentAppointmentDetails['appointment_date'];
    $apntId              = $currentAppointmentDetails['appointment_id'];
    $patientId           = $currentAppointmentDetails['patient_id'];
    $patientName         = $currentAppointmentDetails['patient_name'];
    $patientGurdianName  = $currentAppointmentDetails['patient_gurdian_name'];
    $patientEmail        = $currentAppointmentDetails['patient_email'];
    $patientPhno         = $currentAppointmentDetails['patient_phno'];
    $patientDob          = $currentAppointmentDetails['patient_age'];
    $patientWeight       = $currentAppointmentDetails['patient_weight'];
    $patientGender       = $currentAppointmentDetails['patient_gender'];
    $patientAddress1     = $currentAppointmentDetails['patient_addres1'];
    $patientAddress2     = $currentAppointmentDetails['patient_addres2'];
    $patientPs           = $currentAppointmentDetails['patient_ps'];
    $patientDist         = $currentAppointmentDetails['patient_dist'];
    $patientPin          = $currentAppointmentDetails['patient_pin'];
    $patientState        = $currentAppointmentDetails['patient_state'];
    $getDoctorForPatient = $currentAppointmentDetails['doctor_id'];
}


// Fetching Doctor Info
$doctors = new Doctors(); //Doctor Class 
$selectDoctorByid = $doctors->showDoctorsForPatient($getDoctorForPatient);

if ($selectDoctorByid != '') {
    foreach ($selectDoctorByid as $DoctorByidDetails) {
        $DoctorReg          = $DoctorByidDetails['doctor_reg_no'];
        $DoctorName         = $DoctorByidDetails['doctor_name'];
        $docSpecialization  = $DoctorByidDetails['doctor_specialization'];
        $DoctorDegree       = $DoctorByidDetails['doctor_degree'];
        $DoctorAlsoWith     = $DoctorByidDetails['also_with'];
    }
} else {
    $DoctorReg  = '';
    $DoctorName = '';
    $docSpecialization  = '';
    $DoctorDegree   = '';
    $DoctorAlsoWith = '';
}

$doctorCategory = json_decode($DoctorCategory->showDoctorCategoryById($docSpecialization));
if ($doctorCategory->status == 1) {
    $doctorCategories = $doctorCategory->data;
    foreach ($doctorCategories as $rowDocCatName) {
        $doccategoryName = $rowDocCatName->category_name;
    }
} else {
    $doccategoryName = '';
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>bootstrap/5.3.3/dist/css/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>prescription.css">
    <title>Prescription - <?= url_enc($patientId) ?></title>
</head>

<body>
    <div class="custom-container">
        <div class="custom-body">
            <div class="hospitslDetails mb-0">
                <div class="row">
                    <div class="col-2 headerHospitalLogo">
                        <img class="mt-2" src="<?= $healthCareLogo ?>" alt="<?= $healthCareName ?>">
                    </div>
                    <div class="col-4 headerHospitalDetails ps-0">
                        <h1 class="text-primary fs-4 text-start fw-bold mb-2 mt-3 me-0"><?= $healthCareName ?></h1>
                        <p class="text-start mb-0 me-0">
                            <small><?= $healthCareAddress1 ?></small>
                        </p>
                        <p class="text-start mb-0 me-0" style="width:18rem;"><small><?= $healthCareCity . ', ' .  $patientDist ?></small></p>
                        <p class="text-start  me-0">
                            <small><?php echo $healthCareState . ', ' . $healthCarePin; ?></small>
                        </p>
                    </div>
                    <div class="col-1 header-doc-img d-flex justify-content-left"> <img src="<?= IMG_PATH ?>medicy-doctor-logo.png" alt=""> </div>
                    <div class=" text-danger col-5 headerDoctorDetails">
                        <h2 class="text-end mt-3 fs-4 mb-0"><?= $DoctorName ?></h2>
                        <p class="text-end fs-6 mb-0 ">
                            <small><?= $DoctorReg != NULL ? 'REG NO : ' . $DoctorReg : ''; ?></small>
                        </p>

                        <p class="text-end fs-6 mb-0 ">
                            <small><?= $DoctorDegree . ', ' . $doccategoryName ?></small>
                        </p>
                        <p class="text-end fs-6 mb-0"> <?php echo $DoctorAlsoWith ?></p>
                        <h6 class="text-end fs-6 text-primary">
                            <strong>Call for Appointment: <?= $healthCareApntbkNo ?></strong>
                        </h6>

                    </div>
                </div>
                <hr class="mb-0 mt-0" style="color: #00f;">
            </div>

            <div class="row space mt-1">
                <div class="col-3 border-end " style="border-color: #0000ff59 !important;">
                    <small class="mt-4">
                        <b>A-ID:</b> <?php echo $apntId ?>
                        <br>
                        <b>P-ID:</b> <?php echo $patientId ?>
                        <div class="mt-2">
                            <b>BP:</b>
                            <br>
                            <b>WT:</b> <?php echo $patientWeight ?>
                        </div>
                    </small>
                    <div class="my-4">
                        <h6 class="text-start mb-4"><u> DIAGNOSIS </u></h6>
                        <p class="mb-2">TC,DC,Hb%,ESR</p>
                        <p class="mb-1">BT,CT</p>
                        <p class="mb-1">BI,Sugar(F. & P.P)</p>
                        <p class="mb-1">GR. & Rh.type</p>
                        <p class="mb-1">VDRL</p>
                        <p class="mb-1">Lipid Profile</p>
                        <p class="mb-1">HIV-I & II</p>
                        <p class="mb-1">HBsAg</p>
                        <p class="mb-1">Urea</p>
                        <p class="mb-1">Creatine</p>
                        <p class="mb-1">TSH,T3,T4</p>
                        <p class="mb-1">Bilirubin</p>
                        <p class="mb-1">M.P.</p>
                        <p class="mb-1">L.F.T</p>
                        <p class="mb-1">Urine (RE/ME/CS)</p>
                        <p class="mb-1">Urine Pregnency</p>
                        <p class="mb-1">X-Ray Chest = PA</p>
                        <p class="mb-1">E.C.G</p>
                        <p class="mb-1">Serum PSA Titre</p>
                        <p class="mb-1">USG-W/A-L/A,FPP</p>
                    </div>
                </div>
                <div class="col-9">
                    <div class="row mt-1">
                        <div class="col-12 d-flex justify-content-between">
                            <p class="col-9 mb-0 mt-0">
                                Name: <?php echo $patientName; ?>
                                <span class="ms-3"> Age: <?php echo $patientDob; ?> </span>
                                <span class="ms-3"> Sex: <?php echo $patientGender; ?> </span>
                            </p>

                            <p class="col-3 mb-0 mt-0">
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
            <footer class="footer">
                <div class="d-flex justify-content-between border border-primary px-2 py-1">
                    <div>
                        <img id="healthcare-name-box" class="pe-2" src="<?= IMG_PATH ?>icons/hospital.png" />
                        <?php echo $healthCareName ?>

                    </div>

                    <div>
                        <img id="email-box" class="pe-2" src="<?= IMG_PATH ?>icons/email-logo.png" />
                        <?= $healthCareEmail ?>
                    </div>

                    <div>
                        <img id="number-box" class="pe-2" src="<?= IMG_PATH ?>icons/call-logo.png" />
                        <span><?= $healthCarePhno ?></span>
                    </div>
                </div>
                <p class="text-center text-info"><strong>বিঃ দ্রঃ - যেকোন জরুরি অবস্থায় অনুগ্রহ করে নিকটবর্তি হাসপাতালে
                        যোগাযোগ করুন।</strong></p>
            </footer>
        </div>
        <div class="printButton mb-5">
            <button class="btn btn-primary" onclick="history.back()">Go Back</button>
            <button class="btn btn-primary" onclick="window.print()">Print Prescription</button>
        </div>
</body>

</html>