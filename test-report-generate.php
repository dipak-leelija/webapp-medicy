<?php

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
// require_once CLASS_DIR . 'report-generate.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'PathologyReport.class.php';
require_once CLASS_DIR . 'patients.class.php';

require_once CLASS_DIR . 'utility.class.php';


if (isset($_GET['bill-id'])) {
    $testBillId  = $_GET['bill-id'];
} else {
    header('Location: ' . URL);
    exit;
}

$LabBilling         = new LabBilling();
$LabBillDetails     = new LabBillDetails();
$Pathology          = new Pathology;
$PathologyReport    = new PathologyReport;
// $LabReport          = new LabReport();
$Patients           = new Patients;

$labBillingData     = json_decode($LabBilling->labBillDisplayById($testBillId));
if (!$labBillingData->status) {
    $resErrMsg = $labBillingData->message;
} else {

    // $reportDetails = $PathologyReport->getReportParamsByBill($testBillId);
    // if (!empty($reportDetails)) {
    //     foreach ($reportDetails as $eachParam) {
    //         $details = json_decode($Pathology->showTestByParameter($eachParam));
    //         if($details->status){
    //             $existingTests[] = $details->data->test_id;
    //         }
    //     }
    //     $existingTests = array_unique($existingTests);
    // }else {
    //     $existingTests = [];
    // }

    $labBillingDetails  = json_decode($LabBillDetails->billDetailsById($testBillId)); //labBillingDetails
    // $showpatient        = $LabReport->patientDatafetch($labBillingData->data->patient_id);
    $showpatient        = $Patients->patientsDisplayByPId($labBillingData->data->patient_id);
    // $testBillId         = $labBillingData->data->bill_id;
    // $patientId          = $labBillingData->data->patient_id;




    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $parameterData =  array_combine($_POST['params'], $_POST['values']);
        // print_r($parameterData);

        $reportResponse = $PathologyReport->addTestReport($testBillId, $ADMINID, NOW);
        $reportResponse = json_decode($reportResponse);
        if ($reportResponse->status) {
            $addedReportId = $reportResponse->reportid;

            foreach ($parameterData as $parameter => $value) {
                $PathologyReport->addReportDetails($addedReportId, $parameter, $value);
            }
        }

        // if ($exists) {

        // $cheackUpdate = $LabReport->labReportUpdate($billId, $patientId, NOW, $ADMINID);
        // if ($cheackUpdate['status']) {
        //     $testIds    = $_POST['testId'];
        //     $testValue  = $_POST['values'];
        //     $unitValues = $_POST['unitValues'];

        //     if (is_array($testValue)) {
        //         $deleted = $LabReport->deleteLabReportDetails($billId);
        //         if ($deleted) {
        //             foreach ($testValue as $index => $value) {
        //                 $unitValue = $unitValues[$index];
        //                 $testId = $testIds[$index];

        //                 $addresponse = $LabReport->labReportDetailsUpdate($value, $unitValue, $testId, $billId);
        //                 if ($addresponse == false) {
        //                     echo 'Something is wrong!';
        //                     exit;
        //                 }
        //             }
        //             header('Location: lab-report.php?bill_id=' . base64_encode($billId));
        //             exit;
        //         }
        //     }
        // }
        // } else {
        // $addedeReport = $LabReport->labReportAdd($testBillId, $patientId, NOW, $ADMINID);
        // $reportId       = $addedeReport['insert_id'];
        // $reportStatus   = $addedeReport['result'];

        // if ($reportStatus) {
        //     $testIds    = $_POST['testId'];
        //     $testValue  = $_POST['values'];
        //     $unitValues = $_POST['unitValues'];
        //     if (is_array($testValue))
        //         foreach ($testValue as $index => $value) {
        //             $unitValue = $unitValues[$index];
        //             $testId = $testIds[$index];

        //             $labReportAdd = $LabReport->labReportDetailsAdd($value, $unitValue, $testId, intval($reportId));
        //             if (!$labReportAdd) {
        //                 $errMsg = "Something is wrong with the value : {$unitValue}";
        //                 break;
        //             }
        //         }
        // }

        // if ($labReportAdd) {
        //     header('Location: lab-report.php?bill_id=' . base64_encode($testBillId));
        //     exit;
        // }
        // }
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

    <title>Generate Lab Report </title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>/sb-admin-2.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>/lab-section.css" rel="stylesheet">

    <!-- Sweet Alert Link  -->
    <script src="<?php echo JS_PATH ?>/sweetAlert.min.js"></script>

    <!-- Choices includes -->
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" />
    <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.min.js"></script>


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

                                    <div>
                                        <label for="choices-multiple-remove-button">Select Tests</label>
                                        <select class="form-control text-dark" name="choices-multiple-remove-button" id="select-test" placeholder="Select Test" multiple>
                                            <?php
                                            foreach ($labBillingDetails->data as $index => $test) {
                                                $testId = $test->test_id;
                                                $showTestName = $Pathology->showTestById($testId);

                                                // $disabled = in_array($showTestName['id'], $existingTests) ? 'disabled' : '' ;
                                                // $msg = in_array($showTestName['id'], $existingTests) ? '- Report Generated' : '' ;


                                                echo "<option value='{$showTestName['id']}' >{$showTestName['name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <form method="POST" action="">

                                        <div class="mt-2" id="testReportBody"></div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" id="generateReport" class="btn btn-primary btn-sm">Generate Report</button>
                                        </div>
                                    </form>
                                </div>
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
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>/jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>/jquery-easing/jquery.easing.min.js"></script>




    <!-- ================================== -->
    <script src="<?php echo JS_PATH; ?>ajax.custom-lib.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>/bootstrap/5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH; ?>sb-admin-2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var choice = new Choices(
                "#select-test", {
                    allowHTML: true,
                    removeItemButton: true,
                }
            );
            let previousValues = choice.getValue(true);

            document.getElementById('select-test').addEventListener('change', function(event) {
                const currentValues = choice.getValue(true);
                const billId = '<?= $testBillId ?>'; // replace 'your_bill_id_here' with the actual billId

                var xhr = new XMLHttpRequest();
                xhr.open('POST', "components/TestReportBody.inc.php", true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Handle success
                        document.getElementById('testReportBody').innerHTML = xhr.responseText;
                        // You can update the DOM or do other actions with the response data
                    } else {
                        // Handle error
                        console.error("Error:", xhr.statusText);
                        alert("An error occurred: " + xhr.statusText);
                    }
                };

                xhr.onerror = function() {
                    // Handle error
                    console.error("Request failed");
                    alert("An error occurred during the transaction");
                };

                // xhr.send("testId=" + encodeURIComponent(currentValues));
                xhr.send("testId=" + encodeURIComponent(currentValues) + "&billId=" + encodeURIComponent(billId));
            });


            document.getElementById('select-test').addEventListener('change', function(event) {
                const currentValues = choice.getValue(true);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', "ajax/TestReportConditions.ajax.php", true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Handle success
                        if (xhr.responseText) {
                            const response = JSON.parse(xhr.responseText);
                            const items = choice._store.activeChoices; // Use the correct internal method

                            items.forEach(function(item) {

                                if (!response.includes(Number(item.value))) {
                                    // console.log(item)
                                    const itemElement = document.querySelector(`#choices--select-test-item-choice-${item.id}`);
                                    itemElement.classList.remove('choices__item--selectable');
                                    itemElement.classList.remove('is-highlighted');
                                    itemElement.classList.add('choices__item--disabled');
                                    item.disabled = true
                                    // item.active = false
                                    itemElement.innerText = `${item.label}  ---  Multiple Department's Report Can not generate at the same time`;
                                }

                            });
                        } else {

                            const items = choice._store.activeChoices; // Use the correct internal method

                            items.forEach(function(item) {
                                const itemElement = document.querySelector(`#choices--select-test-item-choice-${item.id}`);

                                if (item.disabled) {
                                    item.disabled = false
                                }
                                if (!item.active) {
                                    item.active = true
                                }

                                itemElement.innerText = item.label

                                itemElement.classList.add('choices__item--selectable');
                                itemElement.classList.add('is-highlighted');
                                itemElement.classList.remove('choices__item--disabled');
                            });

                        }

                    } else {
                        // Handle error
                        console.error("Error:", xhr.statusText);
                        alert("An error occurred: " + xhr.statusText);
                    }
                };

                xhr.onerror = function() {
                    // Handle error
                    console.error("Request failed");
                    alert("An error occurred during the transaction");
                };

                xhr.send("testId=" + encodeURIComponent(currentValues));
            });



        });


        const toggleParameter = (element) => {
            const parentElement = element.closest('#parameter');
            if (parentElement) {
                console.log(parentElement);
                // Add your toggle logic here, for example, hiding the parent element
                parentElement.style.opacity = parentElement.style.opacity === '0.3' ? '' : '0.3';
                console.log(parentElement.style.opacity);
            }
        }
    </script>
</body>

</html>