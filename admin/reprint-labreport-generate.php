<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once dirname(__DIR__).'/config/constant.php';
require_once CLASS_DIR.'sub-test.class.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'labBilling.class.php';
require_once CLASS_DIR.'labBillDetails.class.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'patients.class.php';




//  INSTANTIATING CLASS
$HelthCare       = new HelthCare();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();
$SubTests        = new SubTests();
$Doctors         = new Doctors();
$Patients        = new Patients();
// $LabAppointments = new LabAppointments();

if (isset($_GET['bill-id'])) {

$billId = $_GET['bill-id'];

$labBil      = $LabBilling->labBillDisplayById($billId);
foreach ($labBil as $rowlabBil) {
            
    $billId         = $rowlabBil['bill_id'];
    $billingDate    = $rowlabBil['bill_date'];
    $patientId      = $rowlabBil['patient_id'];
    $docId          = $rowlabBil['refered_doctor'];
    $testDate       = $rowlabBil['test_date'];
    $totalAmount    = $rowlabBil['total_amount'];
    $totalDiscount  = $rowlabBil['discount'];
    $afterDiscount  = $rowlabBil['total_after_discount'];
    $cgst           = $rowlabBil['cgst'];
    $sgst           = $rowlabBil['sgst'];
    $paidAmount     = $rowlabBil['paid_amount'];
    $dueAmount      = $rowlabBil['due_amount'];
    $status         = $rowlabBil['status'];
    $addedBy        = $rowlabBil['added_by'];
    $BillOn         = $rowlabBil['added_on'];

}

$patient = $Patients->patientsDisplayByPId($patientId);
foreach ($patient as $rowPatient) {
    $patientName = $rowPatient['name'];
    $patientPhno = $rowPatient['phno'];
    $patientAge  = $rowPatient['age'];
    $patientGender = $rowPatient['gender'];

}

if (is_numeric($docId)) {
    $showDoctor = $Doctors->showDoctorById($docId);
    foreach ($showDoctor as $rowDoctor) {
        $doctorName = $rowDoctor['doctor_name'];
        $doctorReg = $rowDoctor['doctor_reg_no'];

    }
}else {
    $doctorName = $docId;
    $doctorReg  = NULL;
}

    
}//eof cheaking post method


$showhelthCare = $HelthCare->showhelthCare();
foreach ($showhelthCare as $rowhelthCare) {
    $healthCareName     = $rowhelthCare['hospital_name'];
    $healthCareAddress1 = $rowhelthCare['address_1'];
    $healthCareAddress2 = $rowhelthCare['address_2'];
    $healthCareCity     = $rowhelthCare['city'];
    $healthCarePIN      = $rowhelthCare['pin'];
    $healthCarePhno     = $rowhelthCare['hospital_phno'];
    $healthCareApntbkNo = $rowhelthCare['appointment_help_line'];

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../css/custom/test-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body">
           
            
           
            
           
        </div>
        <div class="justify-content-center print-sec d-flex my-5">
            <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
            <a class="btn btn-primary shadow mx-2" href="test-appointments.php">Go Back</a>
            <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
        </div>
    </div>
    <?php



    ?>
</body>
<script src="../js/bootstrap-js-5/bootstrap.js"></script>

</html>