<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
$page = "patients";

require_once CLASS_DIR . 'dbconnect.php';
require_once ADM_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'labAppointments.class.php';
require_once CLASS_DIR . 'report-generate.class.php';
require_once CLASS_DIR . 'stockOut.class.php';


$patientId = url_dec($_GET['patient']);

$Patients       = new Patients;
$LabBilling     = new LabBilling;
$LabBillDetails = new LabBillDetails();
$SubTests       = new SubTests();
$LabAppointments = new LabAppointments();
$LabReport      = new LabReport;
$StockOut       = new StockOut;

$patientDetails = json_decode($Patients->patientsDisplayByPId($patientId));

// print_r($patientDetails) . "<br>";
$Name = $patientDetails->name;
$Age  = $patientDetails->age;
$sex  = $patientDetails->gender;
$address = $patientDetails->address_1;
$labVisited = $patientDetails->lab_visited;
$lastVisited = $patientDetails->added_on;


/// list of invoice with bill from stokOut table ///
$stockOutdatas = $StockOut->stockOutByPatientId($patientId);
$stockOutdatas = json_decode($stockOutdatas, true);
$invoiceId = [];
foreach ($stockOutdatas as $stockData) {
    echo $invoiceId[] = $stockData['invoice_id'];
}
/// find itemname from stockOutDetils for pie chart ///
$stockOutDetailsBYinvoiveID = $StockOut->stockOutDetailsBYinvoiveID($invoiceId);
$stockDetails = json_decode($stockOutDetailsBYinvoiveID);
$itemNames = [];
foreach ($stockDetails as $details) {
    echo $itemNames[] = $details->item_name;
} 
$occurrenceschart2 = array_count_values($itemNames);
//end...

//=====find labreport by Id=====//
$labreportfetch = $LabReport->labreportfetch();
$labReportData = json_decode($labreportfetch, true);
// if ($labReportData) {
//     foreach ($labReportData as $entry) {
//         // $reportId  = $entry['id'] . "<br>";
//         // $patientId = $entry['patient_id'] . "<br>";
//     }
// } ////end....

///........ for amount spend and find bill_id for finding test_id....... ///
$labBillingDetails = $LabBilling->labBiilingDetailsByPatientId($patientId);
$bill_ids = [];
$billDates = [];
$spent = 0;
if (is_array($labBillingDetails) && !empty($labBillingDetails)) {
    foreach ($labBillingDetails as $row) {
        $spent = $row->paid_amount + $spent;
        $billDate = $row->bill_date . "<br>";
        $bill_ids[] = $row->bill_id . "<br>";
        $billDates[] = $billDate;
    }
    $maxBillDate = max($billDates);
} //--end--//

///..... find test_id from bill_id for finding sub_test....//////
$test_ids = [];
$billDetailsByMultiId = $LabBillDetails->billDetailsByMultiId($bill_ids);
if (is_array($billDetailsByMultiId)) {
    foreach ($billDetailsByMultiId as $MultiId) {
        $test_ids[] = $MultiId['test_id'] . "<br>";
        $billId = $MultiId['bill_id'];
        $date   = $MultiId['test_date'];
    }
} ///---end--///


///......find multiple subtest name from multiple test_id.....//////
$subTestNames = [];
foreach ($test_ids as $test_id) {
    $subTestDetails = $SubTests->showSubTestsId($test_id);
    if (is_array($subTestDetails)) {
        foreach ($subTestDetails as $subTest) {
            $subTestNames[] = $subTest['sub_test_name'];
        }
    }
}
$occurrences = array_count_values($subTestNames);




?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Patients - <?= COMPANY_S ?></title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/patient-details.css">
    <!-- <script src="../../../medicy.in/admin/vendor/chartjs-4.4.0/updatedChart.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include PORTAL_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include PORTAL_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">List of Patients</h6>
                            <a data-toggle="modal" data-target="#appointmentSelection"><button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i>Add New</button></a>
                        </div>
                        <div class="card-body">
                            <div class="main-infodiv">
                                <div class="main-infoleft">
                                    <div class="infoleft">
                                        <div>
                                            <p><samp>Name &nbsp &nbsp&nbsp: <small><?= $Name ?></small></samp></p>
                                            <p><samp>Age &nbsp &nbsp &nbsp: <small><?= $Age ?></small></spam>
                                            </p>
                                            <p><samp>Sex &nbsp &nbsp &nbsp: <small><?= $sex ?></small></spam>
                                            </p>
                                            <p><samp>Address &nbsp: <small><?= $address ?></small></spam>
                                            </p>
                                        </div>
                                        <div>
                                            <p><samp>Times of visits: <small></small><?= $labVisited ?></samp></p>
                                            <p><samp>Last Visited &nbsp&nbsp: <small><?= $maxBillDate ?></small></spam>
                                            </p>
                                            <p><samp>Amount spend &nbsp&nbsp: ₹ <small><?= ($spent) ? $spent : '0.0' ?></small></spam>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="main-inforight">
                                    <canvas id="chart2" style="height: 167px; width: 100%;" id="pieChart"></canvas>
                                </div>
                            </div>
                            <div class="graph-Chart">
                                <canvas id="myChart"></canvas>
                            </div>
                            <div class="table-div">
                                <div class="left-table">
                                    <p>List Of Invoice</p>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Invoice</th>
                                                <th scope="col">Bill Number</th>
                                                <th scope="col">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- <tr>
                                                <th scope="row">1</th>
                                                <td><?= $invoiceId ?></td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr> -->
                                            <?php foreach ($stockOutdatas as $index => $stockOutData) : ?>
                                                <tr>
                                                    <td><?= $stockOutData['invoice_id'] ?></td>
                                                    <td></td>
                                                    <td><?= $stockOutData['bill_date'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="right-table">
                                    <p>List Of Test</p>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col">#</th> -->
                                                <th scope="col">Bill Number</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Report</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $labReportData = json_decode($labreportfetch, true);
                                            $showLabAppointmentsById = $LabAppointments->showLabAppointmentsById($patientId);

                                            if ($labReportData && $showLabAppointmentsById) {
                                                // Create an associative array with patient_id as key and reportId as value
                                                $reportIdMap = [];
                                                foreach ($labReportData as $entry) {
                                                    $reportIdMap[$entry['patient_id']] = $entry['id'];
                                                }

                                                if ($showLabAppointmentsById) {
                                                    $count = 0;
                                                    foreach ($showLabAppointmentsById as $appointment) {
                                                        // $date = $appointment['test_date'];
                                                        $patient_id = $appointment['patient_id'];
                                                        $bill_id    = $appointment['bill_id'];
                                                        $test_date  = $appointment['test_date'];
                                                        $count++;
                                                        if (isset($reportIdMap[$patient_id])) {
                                                            $reportId = $reportIdMap[$patient_id];
                                            ?>
                                                            <tr class="appointment-row">
                                                                <td><?= $bill_id ?></td>
                                                                <td><?= $test_date ?></td>
                                                                <td><a class="text-primary text-center" title="Print" href="test-report-show.php?id=<?= $reportId ?>"><i class="fa fa-link" aria-hidden="true"></i></a></td>
                                                            </tr>
                                            <?php
                                                        }
                                                    }
                                                }
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

            <!-- Footer -->
            <?php include PORTAL_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        const labels = <?php echo json_encode(array_keys($occurrences)) ?>;
        const data = <?php echo json_encode(array_values($occurrences)) ?>;

        const getRandomColor = () => {
            const r = Math.floor(Math.random() * 256);
            const g = Math.floor(Math.random() * 256);
            const b = Math.floor(Math.random() * 256);
            const randomColor = `rgba(${r}, ${g}, ${b}, 0.8)`;
            const borderColor = `rgb(${255 - r}, ${255 - g}, ${255 - b})`;
            return {
                backgroundColor: randomColor,
                borderColor: borderColor
            };
        }

        const backgroundColors = [];
        const borderColors = [];

        for (let i = 0; i < data.length; i++) {
            const randomColors = getRandomColor();
            backgroundColors.push(randomColors.backgroundColor);
            borderColors.push(randomColors.borderColor);
        }

        /// bar chart for most taken test///
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Most taken Tests',
                    data: data,
                    backgroundColor: backgroundColors,
                    // borderColor: borderColors,
                    // borderWidth: 1
                    hoverOffset: 1
                }]
            },
            // options: {
            //     scales: {
            //         y: {
            //             beginAtZero: true
            //         }
            //     }
            // }
        });

        const labels2 = <?php echo json_encode(array_keys($occurrenceschart2)) ?>;
        const data2 = <?php echo json_encode(array_keys($occurrenceschart2)) ?>;
        const ctx2 = document.getElementById('chart2');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labels2,
                datasets: [{
                    label: '# Most purches',
                    backgroundColor: backgroundColors,
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

        //

        ///toggle button ///
        document.addEventListener("DOMContentLoaded", function() {
            var rows = document.querySelectorAll(".appointment-row");
            var toggleButton = document.getElementById("toggleButton");

            // Initially hide all rows except the first three
            for (var i = 3; i < rows.length; i++) {
                rows[i].style.display = "none";
            }

            toggleButton.addEventListener("click", function() {
                for (var i = 3; i < rows.length; i++) {
                    if (rows[i].style.display === "none") {
                        rows[i].style.display = "table-row";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            });
        });
    </script>
</body>

</html>