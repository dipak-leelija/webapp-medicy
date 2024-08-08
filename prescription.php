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
    $doccategoryName = $doctorCategories->category_name;
} else {
    $doccategoryName = '';
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Prescription - <?= url_enc($patientId) ?></title>

    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>bootstrap/5.3.3/dist/css/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>prescription.css">
</head>

<body>
    <div class="custom-container">
        <div class="custom-body">
            <div class="header hospitslDetails mb-0">
                <div class="details-bx">
                    <div class="col-5 left-header">
                        <h1><?= $healthCareName ?></h1>
                        <p><?= $healthCareAddress1 . ', ' . $healthCareCity . ', ' .  $patientDist ?></p>
                        <p><?= $healthCareState . ', ' . $healthCarePin; ?></p>
                    </div>
                    <div class="col-5 right-header">
                        <h2><?= $DoctorName ?></h2>
                        <p><?= $DoctorReg != NULL ? 'REG NO : ' . $DoctorReg : ''; ?></p>
                        <p><?= $DoctorDegree . ', ' . $doccategoryName ?></p>
                        <p><?= $DoctorAlsoWith ?></p>
                        <h6>Call for Appointment: <?= $healthCareApntbkNo ?></h>
                    </div>
                </div>

                <div class="middle-icon"> <img src="<?= IMG_PATH ?>medicy-doctor-logo.png" alt=""> </div>
                <hr class="mb-0 mt-1" style="color: #00f;">
            </div>

            <div class="row space mt-1">
                <div class="col-3 border-end " style="border-color: #0000ff59 !important;">
                    <small class="mt-4">
                        <b>A-ID:</b> <?= $apntId ?>
                        <br>
                        <b>P-ID:</b> <?= $patientId ?>
                        <div class="mt-2">
                            <b>BP:</b>
                            <br>
                            <b>WT:</b> <?= $patientWeight ?>
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

                            <p class="col-3 text-end mb-0 mt-0">
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
                <div class="d-flex justify-content-around border border-primary px-2 py-1">
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
        <div class="printButton p-3 mb-3">
            <button class="btn btn-primary" onclick="history.back()">Go Back</button>
            <button class="btn btn-primary" onclick="window.print()">Print Prescription</button>
        </div>
</body>

</html>