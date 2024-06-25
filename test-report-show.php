<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';

require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';
require_once CLASS_DIR . 'report-generate.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'utility.class.php';


$reportId = $_GET['id'];

// $patientId = $_GET['patient_id'];
$SubTests           = new SubTests;
$LabReport     = new LabReport();
$Patients      = new Patients();
$LabBilling    = new LabBilling();
$LabBillDetails     = new LabBillDetails();


$labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
// echo $labReportDetailbyId;
$testIds = [];
$labReportDetailbyId = json_decode($labReportDetailbyId);
if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {
    foreach ($labReportDetailbyId as $report) {
        $testIds[] = $report->test_id;
        $testData[] = $report->test_value;
        $reportId = $report->report_id;
    }
}


/// find patient id ///
$findPatienttId = $LabReport->labReportbyReportId($reportId);
// echo $findPatienttId;
$findPatienttId = json_decode($findPatienttId, true);
if ($findPatienttId !== null) {
    $patient_id = $findPatienttId['patient_id'];
    $billId    = $findPatienttId['bill_id'];
    // echo "<div style='width:40%; margin-left:20px;' >  $billId</div>";
} //end..//

/// geting for test_date ///
$labBillingDetails   = json_decode($LabBilling->labBillDisplayById($billId)); /// geting for test_date
$labBillingDetails = $labBillingDetails->data;

$testDate = $labBillingDetails->test_date;


//===find patient details ===//
$patientDatafetch = $LabReport->patientDatafetch($patient_id);
$patientDatafetch = json_decode($patientDatafetch, true);
if ($patientDatafetch !== null) {
    $name       = isset($patientDatafetch['name']) ? $patientDatafetch['name'] : 'N/A';
    $patient_id = isset($patientDatafetch['patient_id']) ? $patientDatafetch['patient_id'] : 'N/A';
    $age        = isset($patientDatafetch['age']) ? $patientDatafetch['age'] : 'N/A';
    $gender     = isset($patientDatafetch['gender']) ? $patientDatafetch['gender'] : 'N/A';
}


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-report.css">
    <title>Prescription</title>
</head>

<body>
    <section class="print-page">


        <div>
            <div>
                <img src="./assets/images/top-wave.svg" alt="">
            </div>
            <h1><?= $healthCareName ?></h1>
            <div>
                <span>DIAGNOSTIC & POLYCLINIC</span>
            </div>
            <div>
                <p>Daulatabad, Murshidabad,(W.B.),Pin -742302, Mobile:8695494415/9064390598,Website:www.medicy.in</p>
            </div>
            <hr>
            <div>
                <div>
                    <p><b>Patient's Name :</b> <?= $name; ?></p>
                    <p><b>Patient id :</b> <?= $patient_id ?></p>
                    <p><b>Place of collection :</b> LAB </p>
                    <p><b>Ref. by :</b> DR. SELF </p>
                </div>
                <div>
                    <p><b>Age :</b> <?php echo $age; ?> <b>Sex :</b> <?php echo $gender; ?></p>
                    <p><b>Collection Date :</b> <?= formatDateTime($testDate, '/') ?></p>
                    <p><b>Reporting Date :</b> <?= formatDateTime($testDate, '/') ?></p>
                </div>
            </div>
            <hr>
        </div>

        <div>
            <h5><U><b>REPORT OF LIVER FUNCTION TEST</b></U></h5>

            <table>

                <?php
                $labReportDetailbyId = $LabReport->labReportDetailbyId($reportId);
                $labReportDetailbyId = json_decode($labReportDetailbyId);

                if (is_array($labReportDetailbyId) && !empty($labReportDetailbyId)) {
                    for ($i = 0; $i < count($testIds); $i++) {
                        $patientTest = $SubTests->subTestById($testIds[$i]);
                        $decodedData = json_decode($patientTest, true);
                        if ($decodedData !== null) {
                            $sub_test_name = $decodedData['sub_test_name'];
                ?>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $sub_test_name ?>
                        <br>
                        <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</small>
                        <br>
                        <small>Lorem ipsum dolor 12h - 76gh </small>
                    </td>
                    <td><?= $testData[$i] ?></td>
                </tr>
                <?php
                        }
                    }
                }

                ?>

            </table>
        </div>

        <div class="footer">

            <div>
                <p>Reference values are obtained from the literature provided with reagent kit.</p>
                <hr>
            </div>

            <p><b>***END OF REPORT***</b></p>
            <div>
                <div>
                    <p><small><i><b>A Health Care Unit for :-</b></i></small></p>
                    <p><small><b>Advance Assay, USG & ECHO, Colour Doppler,</b></small></p>
                    <p><small><b>Digital X-Ray, Special X-Ray, OPG, ECG & Eye.</b></small></p>
                </div>
                <div><small><i><b>Verified by :</b></i></small></div>
                <div>
                    <p><b>DR. S.BISWAS</b></p>
                    <p><b>Consultant Pathologist(MD)</b></p>
                    <p><b>Reg. No: 59304 (WBMC)</b></p>

                </div>
            </div>
            <div>
                <img src="./assets/images/bottom-wave.svg" alt="">
            </div>
        </div>
    </section>
        <!-- <div class="footer">

            <div>
                <p>Reference values are obtained from the literature provided with reagent kit.</p>
                <hr>
            </div>

            <p><b>***END OF REPORT***</b></p>
            <div>
                <div>
                    <p><small><i><b>A Health Care Unit for :-</b></i></small></p>
                    <p><small><b>Advance Assay, USG & ECHO, Colour Doppler,</b></small></p>
                    <p><small><b>Digital X-Ray, Special X-Ray, OPG, ECG & Eye.</b></small></p>
                </div>
                <div><small><i><b>Verified by :</b></i></small></div>
                <div>
                    <p><b>DR. S.BISWAS</b></p>
                    <p><b>Consultant Pathologist(MD)</b></p>
                    <p><b>Reg. No: 59304 (WBMC)</b></p>

                </div>
            </div>
            <div>
                <img src="./assets/images/bottom-wave.svg" alt="">
            </div>
        </div> -->

    <div class="printButton mb-5">
        <button onclick="history.back()">Go Back</button>
        <button onclick="window.print()">Print Prescription</button>
    </div>
</body>

</html>