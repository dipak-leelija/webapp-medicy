<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not


require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once ROOT_DIR  . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'labAppointments.class.php';
// require_once CLASS_DIR . 'report-generate.class.php';
require_once CLASS_DIR . 'PathologyReport.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'stockOut.class.php';




$Patients       = new Patients;
$LabBilling     = new LabBilling;
$LabBillDetails = new LabBillDetails();
$SubTests       = new SubTests();
$LabAppointments = new LabAppointments();
$PathologyReport = new PathologyReport;
$Pathology        = new Pathology;
// $LabReport      = new LabReport;
$StockOut       = new StockOut;



$getPatientId = url_dec($_GET['patient']); // on click get value

// patient details by patient id
$patientDetails = json_decode($Patients->patientsDisplayByPId($getPatientId));

$Name           = $patientDetails->name;
$Age            = $patientDetails->age;
$sex            = $patientDetails->gender;
$address        = $patientDetails->address_1;
$labVisited     = $patientDetails->lab_visited;
$lastVisited    = $patientDetails->added_on;


/// list of invoice with bill from stokOut table /// stock out data by patient id
$stockOutdatas = $StockOut->stockOutByPatientId($getPatientId);
$stockOutdatas = json_decode($stockOutdatas, true);
// print_r($stockOutdatas);
$invoiceId = [];
foreach ($stockOutdatas as $stockData) {
    $invoiceId[] = $stockData['invoice_id'];
}


// stock out details by invoice id // 
$stockOutDetailsBYinvoiveID = $StockOut->stockOutDetailsBYinvoiveID($invoiceId);

if ($stockOutDetailsBYinvoiveID !== null) {
    $stockDetails = json_decode($stockOutDetailsBYinvoiveID);

    if ($stockDetails !== null) {
        $itemNames = [];
        foreach ($stockDetails as $details) {
            if (isset($details->item_name)) {
                $itemNames[] = $details->item_name;
            }
        }
        $occurrenceschart2 = array_count_values($itemNames);
        echo "<script>var occurrenceschart2 = " . json_encode($occurrenceschart2) . ";</script>";
    }
}
//end...

// =====find labreport by Id=====// data fetch form lab_report table // by patient id and adminid
$labreport = json_decode($PathologyReport->testReportByPatient($getPatientId), true);


// lab billing details
$labBillingDetails = $LabBilling->labBiilingDetailsByPatientId($getPatientId);

$bill_ids = [];
$billDates = [];
$spent = 0;
if (is_array($labBillingDetails) && !empty($labBillingDetails)) {
    foreach ($labBillingDetails as $row) {
        $spent = $row->paid_amount + $spent;
        $billDate = $row->bill_date;
        $billID   = $row->bill_id;
        $bill_ids[] = $row->bill_id;
        $billDates[] = $billDate;
    }
    $maxBillDate = max($billDates);
}




///..... find test_id from bill_id for finding sub_test....//////
if (!empty($labBillingDetails)) {
    $test_ids = [];
    $billDetailsByMultiId = json_decode($LabBillDetails->billDetailsByMultiId($bill_ids));

    foreach ($billDetailsByMultiId->data as $MultiId) {
        $test_ids[] = $MultiId->test_id;
        $billId = $MultiId->bill_id;
        $date   = $MultiId->test_date;
    }
}

///---end--///


if (!empty($labBillingDetails)) {
    ///......find multiple subtest name from multiple test_id.....//////
    $subTestNames = [];
    foreach ($test_ids as $test_id) {
        $testList = $Pathology->showTestById($test_id);
        if (is_array($testList)) {
            $subTestNames[] = $testList['name'];
        }
    }
    $occurrences = array_count_values($subTestNames);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title><?= $Name .' - '. $HEALTHCARENAME ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>patient-details.css" type="text/css">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css">
    <script src="<?php echo PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>

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

                    <!-- DataTales Example -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Patient Details</h6>
                            <!-- <a data-toggle="modal" data-target="#appointmentSelection"><button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i>Add New</button></a> -->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 shadow-sm">
                                    <div class="">
                                        <div class="p-2">
                                            <div class="d-flex justify-content-around flex-wrap">

                                                <div>
                                                    <table class="text-sm">
                                                        <tbody>
                                                            <tr>
                                                                <th>Patient Id</th>
                                                                <td class="px-2">:</td>
                                                                <td><?= $getPatientId ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Name</th>
                                                                <td class="px-2">:</td>
                                                                <td><?= $Name ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Age</th>
                                                                <td class="px-2">:</td>
                                                                <td><?= $Age ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Sex</th>
                                                                <td class="px-2">:</td>
                                                                <td><?= $sex ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Address</th>
                                                                <td class="px-2">:</td>
                                                                <td><?= $address ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div>
                                                    <table class="text-sm">
                                                        <tbody>
                                                            <tr>
                                                                <th>Total Visits:</th>
                                                                <td class="px-2">:</td>
                                                                <td><?= ($labVisited) ? $labVisited : "0" ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Last Visited</th>
                                                                <td class="px-2">:</td>
                                                                <td><?= isset($maxBillDate) ? $maxBillDate : ' _ / _ / _' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Amount Spend</th>
                                                                <td class="px-2">:</td>
                                                                <td><?= ($spent) ? $spent : '0.0' ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-flex justify-content-around pt-5">
                                <div class="col-12 col-md-6">
                                    <div class="shadow-sm p-4">
                                        <p class="text-xs font-weight-bold text-primary text-uppercase">Purchase Graph</p>
                                        <?php if (!empty($itemNames)) : ?>
                                            <canvas id="chart2" style="width: 100%;"></canvas>
                                        <?php else : ?>
                                            <div class="p-3">
                                                <p class="text-sm text-center text-danger">No purchase history found.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="shadow-sm p-4">
                                        <p class="text-xs font-weight-bold text-primary text-uppercase">Tests Taken</p>
                                        <?php if (!empty($subTestNames)) : ?>
                                            <div class="graph-Chart">
                                                <canvas id='myChart' style='width: 100%;'>Most taken Tests</canvas>
                                            </div>
                                        <?php else : ?>
                                            <div class="p-3">
                                                <p class="text-center text-sm text-danger">No Tests Taken</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="table-div mt-5">
                                <div class="left-table shadow-sm p-3">
                                    <p class="text-xs font-weight-bold text-primary text-uppercase">List Of Invoice</p>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Bill Number</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Invoice</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($stockOutdatas)) : ?>
                                                <tr>
                                                    <td colspan="3" style='text-align: center;'>Data Not Found</td>
                                                </tr>
                                            <?php else : ?>
                                                <?php foreach ($stockOutdatas as $index => $stockOutData) : ?>
                                                    <?php $invoice_id = $stockOutData['invoice_id']; ?>
                                                    <tr class="appoinment-row1">
                                                        <td><?= $invoice_id ?></td>
                                                        <td><?= $stockOutData['bill_date'] ?></td>
                                                        <td>

                                                            <a class="text-primary text-center" onclick="openPrint(this.href); return false;" href="<?= URL ?>invoices/print.php?name=sales&id=<?= url_enc($invoice_id) ?>">
                                                                <i class="fa fa-print" aria-hidden="true"></i>
                                                            </a>

                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary btn-sm" id="toggleButton1">More...</button>
                                    </div>
                                </div>
                                <div class="right-table shadow-sm p-3">
                                    <p class="text-xs font-weight-bold text-primary text-uppercase">List Of Test</p>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col">#</th> -->
                                                <th scope="col">Bill Number</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Bill</th>
                                                <th scope="col">Report</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            // $labReportData = $labreport;
                                            if (!empty($labreport)) {
                                                $count = 0;
                                                foreach ($labBillingDetails as $eachBill) {
                                                    $count++;
                                                    $billId    = $eachBill->bill_id;
                                                    $billDate  = $eachBill->bill_date;
                                                    $testDate  = $eachBill->test_date;


                                                    // $labBills = json_decode($LabBilling->labBillDisplayById($eachReport['bill_id']));

                                            ?>
                                                    <tr class="appointment-row">
                                                        <td><?= $billId ?></td>
                                                        <td><?= $testDate ?></td>
                                                        <td>
                                                            <a class="text-primary text-center" onclick="openPrint(this.href); return false;" href="<?= URL ?>invoices/print.php?name=lab_invoice&id=<?= url_enc($billId) ?>">
                                                                <i class="fa fa-link" aria-hidden="true"></i>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='3' style='text-align: center;'>Data Not found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary btn-sm" id="toggleButton">More...</button>
                                    </div>
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
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.js"></script>
    <script src="<?php echo JS_PATH ?>main.js"></script>

    <script>
        const labels = <?php echo json_encode(array_keys($occurrences)) ?>;
        const data = <?php echo json_encode(array_values($occurrences)) ?>;



        const backgroundColors = [];

        for (let i = 0; i < data.length; i++) {
            backgroundColors.push(getRandomColor());
        }

        /// pie chart for most taken test///
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: ' Most taken Tests',
                    data: data,
                    backgroundColor: backgroundColors,
                    // borderColor: borderColors,
                    // borderWidth: 1
                    hoverOffset: 1
                }]
            },
            responsive: true
        });

        /// bar chart for most purchased ///
        const ctx2 = document.getElementById('chart2');
        const labels2 = Object.keys(occurrenceschart2);
        const data2 = Object.values(occurrenceschart2);

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labels2,
                datasets: [{
                    label: ' Most Purchased Medicine',
                    data: data2,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });



        ///toggle button ///
        document.addEventListener("DOMContentLoaded", function() {
            var rows = document.querySelectorAll(".appointment-row");
            var rows1 = document.querySelectorAll(".appoinment-row1");
            var toggleButton = document.getElementById("toggleButton");
            var toggleButton1 = document.getElementById("toggleButton1");

            // Initially hide all rows except the first three///
            for (var i = 3; i < rows.length; i++) {
                rows[i].style.display = "none";
            }
            for (var i = 3; i < rows1.length; i++) {
                rows1[i].style.display = "none";
            }

            if (rows.length > 3 ? toggleButton.style.display = "block" : toggleButton.style.display = "none");
            if (rows1.length > 3 ? toggleButton1.style.display = "block" : toggleButton1.style.display = "none");

            toggleButton.addEventListener("click", function() {
                for (var i = 3; i < rows.length; i++) {
                    if (rows[i].style.display === "none" ? rows[i].style.display = "table-row" : rows[i]
                        .style.display = "none");
                }
            });

            toggleButton1.addEventListener("click", function() {
                for (var i = 3; i < rows1.length; i++) {
                    if (rows1[i].style.display === "none" ? rows1[i].style.display = "table-row" : rows1[i]
                        .style.display = "none");
                }
            });
        });
    </script>
</body>

</html>