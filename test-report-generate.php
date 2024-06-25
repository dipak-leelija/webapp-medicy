<?php

use function PHPSTORM_META\type;

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'report-generate.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'sub-test.class.php';

require_once CLASS_DIR . 'utility.class.php';


if (isset($_GET['bill-id'])) {
    $billId  = $_GET['bill-id'];
} else {
    header('Location: ' . URL);
    exit;
}

$SubTests           = new SubTests;
$LabReport          = new LabReport();
$LabBilling         = new LabBilling();
$LabBillDetails     = new LabBillDetails();

$labBillingData     = json_decode($LabBilling->labBillDisplayById($billId));
if (!$labBillingData->status) {
    $resErrMsg = $labBillingData->message;
} else {


    $labBillingDetails  = json_decode($LabBillDetails->billDetailsById($billId)); //labBillingDetails
    $showpatient        = $LabReport->patientDatafetch($labBillingData->data->patient_id);
    $billId             = $labBillingData->data->bill_id;
    $patientId          = $labBillingData->data->patient_id;




    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $exists = $LabReport->checkBill($billId);
        if ($exists) {

            $cheackUpdate = $LabReport->labReportUpdate($billId, $patientId, NOW, $ADMINID);
            if ($cheackUpdate['status']) {
                $testIds    = $_POST['testId'];
                $testValue  = $_POST['values'];
                $unitValues = $_POST['unitValues'];

                if (is_array($testValue)) {
                    $deleted = $LabReport->deleteLabReportDetails($billId);
                    if ($deleted) {
                        foreach ($testValue as $index => $value) {
                            $unitValue = $unitValues[$index];
                            $testId = $testIds[$index];

                            $addresponse = $LabReport->labReportDetailsUpdate($value, $unitValue, $testId, $billId);
                            if($addresponse == false){
                                echo 'Something is wrong!'; exit;
                            }
                        }
                        header('Location: lab-report.php?bill_id=' . base64_encode($billId));
                        exit;
                    }
                }
            }
        } else {
            $addedeReport = $LabReport->labReportAdd($billId, $patientId, NOW, $ADMINID);
            $reportId       = $addedeReport['insert_id'];
            $reportStatus   = $addedeReport['result'];

            if ($reportStatus) {
                $testIds    = $_POST['testId'];
                $testValue  = $_POST['values'];
                $unitValues = $_POST['unitValues'];
                if (is_array($testValue))
                    foreach ($testValue as $index => $value) {
                        $unitValue = $unitValues[$index];
                        $testId = $testIds[$index];

                        $labReportAdd = $LabReport->labReportDetailsAdd($value, $unitValue, $testId, intval($reportId));
                        if (!$labReportAdd) {
                            $errMsg = "Something is wrong with the value : {$unitValue}";
                            break;
                        }
                    }
            }

            if ($labReportAdd) {
                header('Location: lab-report.php?bill_id=' . base64_encode($billId));
                exit;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lab Report Generate</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>/lab-section.css" rel="stylesheet">

    <!-- Sweet Alert Link  -->
    <script src="<?php echo JS_PATH ?>/sweetAlert.min.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 booked_btn">

                            <?php
                            if (isset($resErrMsg)) {
                                echo $resErrMsg;
                                exit;
                            }
                            ?>
                            <?php if (isset($errMsg)) { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= $errMsg ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php } ?>
                            <form method="POST" action="">
                                <div class="card-body">
                                    <?php
                                    $showpatient = json_decode($showpatient);
                                    if ($showpatient !== null) {
                                        $patientName = isset($showpatient->name)   ? $showpatient->name   : 'N/A';
                                        $patientAge  = isset($showpatient->age)    ? $showpatient->age    : 'N/A';
                                        $patientSex  = isset($showpatient->gender) ? $showpatient->gender : 'N/A';
                                    }
                                    $testDate = $labBillingData->data->test_date;
                                    ?>
                                    <div style="display: flex; justify-content:space-between; align-items: center;flex-wrap: wrap;">
                                        <h6><b>Patient Name:</b> <?php echo $patientName; ?></h6>
                                        <h6><b>Age:</b> <?php echo $patientAge; ?></h6>
                                        <h6><b>Sex:</b> <?php echo $patientSex; ?></h6>
                                        <h6><b>Test Date:</b> <?= formatDateTime($testDate, '/') ?></h6>
                                    </div>

                                    <hr class="sidebar-divider">

                                    <div>
                                        <?php
                                        $unitCounts = array();
                                        foreach ($labBillingDetails->data as $index => $test) {
                                            $testId = $test->test_id;
                                            $showTestName = json_decode($SubTests->subTestById($testId));

                                            $testId         = $showTestName->id;
                                            $subTestName    = $showTestName->sub_test_name;
                                            $unitNames      = $showTestName->unit;

                                            echo "<div class='shadow-sm mb-4 py-2'>";
                                            echo "<div class='d-flex justify-content-between px-3'>";
                                            echo "<div>$subTestName</div>";
                                            echo "<div>";

                                            if (!empty($unitNames)) {
                                                $unitValues = explode(',', $unitNames); // Split the unitNames by comma and store them in an array
                                                $unitValues = array_map('trim', $unitValues); // Trim to remove any leading or trailing whitespace

                                                foreach ($unitValues as $unitValue) {
                                                    if (!isset($unitCounts[$unitValue])) {
                                                        $unitCounts[$unitValue] = 0;
                                                    }
                                                    $unitCounts[$unitValue]++;

                                                    // Generate input boxes based on the count of unit values
                                                    for ($i = 0; $i < $unitCounts[$unitValue]; $i++) {
                                                        echo "<div class='d-flex justify-content-start align-items-baseline'>";
                                                        echo "<input type='text' class='lab-val-inp' name='values[]' required >";
                                                        echo "<label>$unitValue</label>";
                                                        echo "<input type='hidden' name='unitValues[]' value='$unitValue'>";
                                                        echo "<input type='hidden' name='testId[]' value='$testId'>";
                                                        echo "</div>";
                                                    }
                                                }
                                            }

                                            echo "</div>";
                                            echo "</div>";
                                            echo "</div>";
                                        }
                                        ?>

                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" id="generateReport" class="btn btn-primary btn-sm">Generate Report</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>/jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>/sb-admin-2.min.js"></script>

</body>

</html>