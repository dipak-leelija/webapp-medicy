<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';
require_once CLASS_DIR . 'report-generate.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'patients.class.php';

$billId = base64_decode($_GET['bill_id']);

$LabReport     = new LabReport();
$Patients      = new Patients();
$LabBilling    = new LabBilling();
$labBillingData      = $LabBilling->labBillDisplayById($billId); /// geting for test_date
$labReportShow       = $LabReport->labReportShow($billId);

///find patient Id //
$labReportShow = json_decode($labReportShow);
if ($labReportShow !== null) {
    $patienId = $labReportShow->patient_id;
    $reportId = $labReportShow->id;
    // print_r($reportId);
}
/// find patient details
$showPatientData = json_decode($Patients->patientsDisplayByPId($patienId));
$patientName = $showPatientData->name;
$patientAge  = $showPatientData->age;
$patientSex  = $showPatientData->gender;

///fetch labreportdetails data by id //
$labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
$labReportDetailbyId = json_decode($labReportDetailbyId);
// print_r($labReportDetailbyId);
if ($labReportDetailbyId !== null) {
}


// Fetching Hospital Info
$hospital = new HelthCare();
$hospitalShow = $hospital->showhelthCare();
foreach ($hospitalShow as $hospitalDetails) {
    $hospitalName = $hospitalDetails['hospital_name'];
    $address1 = $hospitalDetails['address_1'];
    $address2 = $hospitalDetails['address_2'];
    $city = $hospitalDetails['city'];
    $pin = $hospitalDetails['pin'];
    $state = $hospitalDetails['health_care_state'];

    $hospitalEmail = $hospitalDetails['hospital_email'];
    $hospitalPhno = $hospitalDetails['hospital_phno'];
    $appointmentNumber = $hospitalDetails['appointment_help_line'];
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../css/lab-report.css">
    <title>Prescription</title>
</head>

<body>

<div id="wave"></div>
    <div style="box-shadow:none" class="card">
        <div class="hospitslDetails mb-0">
            <div class="row">
                <div class="col-1 headerHospitalLogo">
                    <img class="mt-4" src="../images/logo-p.jpg" alt="XYZ Hospital">
                </div>
                <div class="col-4 headerHospitalDetails">
                    <h1 class="text-primary text-start fw-bold mb-2 mt-4 me-3"><?php echo $hospitalName ?></h1>
                    <p class="text-start  me-3">
                        <small><?php echo $address1 . ', ' . $address2 . ', ' . $city . ',<br>' . $state . ', ' . $pin; ?></small>
                    </p>
                </div>
                <div class="col-2 header-doc-img"> <img src="../images/medicy-doctor-logo.png" alt=""> </div>

            </div>

        </div>
        <hr class="mb-0 mt-0" style="color: #00f;">
        <div>

        </div>
        <!-- <hr> -->
        <!-- <div class="space">
        </div> -->
        <div class="row space mt-1">
            <div class="col-3 border-end " style="border-color: #0000ff59 !important;">
                <small>

                    P-ID: <?php echo $patienId ?>

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
                            <span class="ms-3"> Age: <?php echo $patientAge; ?> </span>
                            <span class="ms-3"> Sex: <?php echo $patientSex; ?> </span>
                        </p>

                        <p class="mb-0 mt-0 text-end">
                            Date:
                            <?php
                            $testDate = $labBillingData[0]['test_date'];
                            $date = date_create($testDate);
                            echo date_format($date, "d-m-Y");
                            ?>
                        </p>
                    </div>
                </div>
                <hr class="row mt-2 m-auto" style="color: #00f;">
                <div style="margin-top: 20px; margin-left:30px;">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Test Name</th>
                                <th scope="col">Ref. Value</th>
                                <th scope="col">Unit</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class=" footer ">
            <div class="row border border-primary pt-2 pb-0 d-flex justify-content-between">

                <div class="col-md-4 custom-width-name mb-0">
                    <ul style="margin-bottom: 8px">
                        <li class=" list-unstyled"><img id="healthcare-name-box" class="pe-2" src="../employee/partials/hospital.png" alt="Healt Care" style="width:28px; height:20px;" /><?php echo $hospitalName ?></li>
                    </ul>
                </div>

                <div class="col-md-4 custom-width-email mb-0">
                    <ul style="margin-bottom: 8px">
                        <li class="list-unstyled"><img id="email-box" class="pe-2" src="../employee/partials/email-logo.png" alt="Email" style="width:28px; height:20px;" /><?php echo $hospitalEmail ?></li>

                    </ul>
                </div>

                <div class="col-md-4 custom-width-number mb-0">
                    <ul style="margin-bottom: 8px">
                        <li class="list-unstyled"><img id="number-box" class="pe-2" src="../employee/partials/call-logo.png" alt="Contact" style="width:28px; height:20px;" /><span><?php echo $appointmentNumber . ', ' . $hospitalPhno ?></span>
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