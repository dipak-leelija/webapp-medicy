<?php
require_once dirname(__DIR__) . '/config/constant.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'doctors.class.php';

$billId = $_GET['billId'];

$LabBilling         = new LabBilling;
$LabBillDetails     = new LabBillDetails;
$SubTests           = new SubTests;
$Patients           = new Patients;
$Doctors            = new Doctors;

$labBil      = json_decode($LabBilling->labBillDisplayById($billId));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            overflow-x: hidden;
            overflow-y: scroll;
        }
    </style>

</head>

<body class="mx-2">

    <table class="table table-striped">
        <?php
        if ($labBil->status) {

            $billId         = $labBil->data->bill_id;
            $billDate       = $labBil->data->bill_date;
            $patientId      = $labBil->data->patient_id;
            $docId          = $labBil->data->refered_doctor;
            $testDate       = $labBil->data->test_date;
            $totalAmount    = $labBil->data->total_amount;
            $totalDiscount  = $labBil->data->discount;
            $afterDiscount  = $labBil->data->total_after_discount;
            $cgst           = $labBil->data->cgst;
            $sgst           = $labBil->data->sgst;
            $paidAmount     = $labBil->data->paid_amount;
            $dueAmount      = $labBil->data->due_amount;
            $status         = $labBil->data->status;
            $addedBy        = $labBil->data->added_by;
            $BillOn         = $labBil->data->added_on;
        } else {
            echo "Bill Not found";
        }

        $patient = $Patients->patientsDisplayByPId($patientId);

        if ($patient !== false) {
            $patientData = json_decode($patient, true);
            if ($patientData !== null) {
                $patientName   = isset($patientData['name']) ? $patientData['name'] : 'N/A';
                $patientPhno   = isset($patientData['phno']) ? $patientData['phno'] : 'N/A';
                $patientAge    = isset($patientData['age'])  ? $patientData['age']  : 'N/A';
                $patientGender = isset($patientData['gender']) ? $patientData['gender'] : 'N/A';
            } else {
                echo "Error decoding patient data.";
            }
        }


        if (is_numeric($docId)) {
            $showDoctor = $Doctors->showDoctorNameById($docId);
            $showDoctor = json_decode($showDoctor);
            if ($showDoctor->status == 1) {
                foreach ($showDoctor->data as $rowDoctor) {
                    $doctorName = $rowDoctor->doctor_name;
                }
            }
        } else {
            $doctorName = $docId;
        }

        ?>
        <div class="row mb-4">
            <div class="col-sm-4">
                <h6><b>Patient Name:</b> <?php echo $patientName; ?></h6>
                <h6><b>Patient Contact:</b> <?php echo $patientPhno; ?></h6>
            </div>
            <div class="col-sm-4">
                <h6><b>Refered By:</b> <?php echo $doctorName; ?></h6>
                <h6><b>Test Date:</b> <?php echo $testDate; ?></h6>

            </div>
            <div class="col-sm-4">
                <div class="d-flex justify-content-between">

                    <h6><b>Bill ID:</b> <?php echo $billId; ?></h6>
                    <base target=" _parent">
                    <?php
                    if ($status != "Cancelled") {

                        echo '<h6><a class="btn btn-sm btn-primary" href="' . LOCAL_DIR . 'edit-lab-billing.php?invoice=' . url_enc($billId) . '">Edit</a></h6>';
                    } else {
                        echo '<h6 class="border border-danger text-danger p-1"> Bill Cancelled</h6>';
                    }
                    ?>

                </div>

                <h6><b>Bill Date:</b> <?php echo $billDate; ?></h6>
            </div>

        </div>

        <thead>
            <tr>
                <th scope="col">SL. NO</th>
                <th scope="col">Test Name</th>
                <th scope="col">Test Price</th>
                <th scope="col">Disc(%)</th>
                <th scope="col">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $slno = 0;
            $billDetails = json_decode($LabBillDetails->billDetailsById($billId));

            foreach ($billDetails->data as $rowbillDetails) {

                $slno += 1;
                $billId     = $rowbillDetails->bill_id;
                $subTestId     = $rowbillDetails->test_id;
                $testPrice  = $rowbillDetails->test_price;
                $discOnTest = $rowbillDetails->percentage_of_discount_on_test;
                $amount     = $rowbillDetails->price_after_discount;

                $subTest = $SubTests->showSubTestsId($subTestId);
                foreach ($subTest as $rowsubTest) {
                    $testName = $rowsubTest['sub_test_name'];
                }

                echo '<tr>
                        <th scope="row">' . $slno . '</th>
                        <td>' . $testName . '</td>
                        <td>' . $testPrice . '</td>
                        <td>' . $discOnTest . '</td>
                        <td>' . $amount . '</td>
                      </tr>';
            }
            ?>
        </tbody>
    </table>

    <table class="table mt-5 mb-0 pb-0">
        <thead class="thead-dark mb-0 pb-0">
            <tr>
                <?php
                echo '
                <th scope="col">Sub Total: ' . $totalAmount . '</th>
                <th scope="col">Disc(₹): ' . $totalDiscount . '</th>
                <th scope="col">After Disc(₹): ' . $afterDiscount . '</th>
                <th scope="col">Due: ' . $dueAmount . '</th>
                <th scope="col">Paid: ' . $paidAmount . '</th>';
                ?>
            </tr>
        </thead>
    </table>

</body>

</html>