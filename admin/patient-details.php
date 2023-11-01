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


$patientId = url_dec($_GET['patient']);

$Patients       = new Patients;
$LabBilling     = new LabBilling;
$LabBillDetails = new LabBillDetails();
$SubTests       = new SubTests();
$LabAppointments = new LabAppointments();

$patientDetails = json_decode($Patients->patientsDisplayByPId('PE725663040'));

// print_r($patientDetails) . "<br>";
$Name = $patientDetails->name;
$Age  = $patientDetails->age;
$sex  = $patientDetails->gender;
$address = $patientDetails->address_1;
$labVisited = $patientDetails->lab_visited;
$lastVisited = $patientDetails->added_on;

// if ($patientId) {
//     $patientCount = $Patients->patientVisitCount($Name, $patientId);
//     // print_r($patientCount['count']);
//     // echo $patientCount['Last_Visited'];
// }


///........ for amount spend and find bill_id for finding test_id....... ///
$labBillingDetails = $LabBilling->labBiilingDetailsByPatientId('PE725663040');

$bill_ids = [];
$billDates = [];
$spent = 0;
if (is_array($labBillingDetails) && !empty($labBillingDetails)) {
    foreach ($labBillingDetails as $row) {
        $spent = $row->paid_amount + $spent;
        $billDate = $row->bill_date;
        $bill_ids[] = $row->bill_id;
        $billDates[] = $billDate;
    }
    $maxBillDate = max($billDates);
} elseif ($labBillingDetails === null) {
    echo "No results found.";
} else {
    echo "Error: " . $labBillingDetails;
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
// $uniqueSubTestNames = array_unique($subTestNames);
///bar chart for Most taken Tests as graph//
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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
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
                            <a data-toggle="modal" data-target="#appointmentSelection"><button
                                    class="btn btn-sm btn-primary"><i class="fas fa-edit"></i>Add New</button></a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 shadow-sm">
                                    <div class="row justify-content-between">
                                        <div class="col-6 p-2">
                                            <div class="d-flex justify-content-between">

                                                <div>
                                                    <table class="text-sm">
                                                        <tbody>
                                                            <tr>
                                                                <th>Patient Id</th>
                                                                <td class="px-2">:</td>
                                                                <td><?= $patientId ?></td>
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
                                                                <td><?= $labVisited ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Last Visited</th>
                                                                <td class="px-2">:</td>
                                                                <td><?= $maxBillDate ?></td>
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

                                        <div class="col-5 p-2">
                                            <canvas style="height: 167px; width: 100%;" id="pieChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="graph-Chart col-3">
                                    <!-- <div id="chartContainer" style="height: 250px; width: 100%;"></div> -->
                                    <canvas id="myChart"></canvas>
                                </div>

                                <div class="col-12 table-div">
                                    <div class="left-table">
                                        <p>List Of Invoice</p>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Invoice</th>
                                                    <th scope="col">Bill Number</th>
                                                    <th scope="col">Date</th>
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
                                    <div class="right-table">
                                        <p>List Of Test</p>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Bill Number</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Report</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                            $showLabAppointmentsById = $LabAppointments->showLabAppointmentsById('PE725663040');

                                            if ($showLabAppointmentsById) {
                                                $count = 0;
                                                foreach ($showLabAppointmentsById as $appointment) {
                                                    $billId = $appointment['bill_id'];
                                                    $date = $appointment['test_date'];
                                                    $count++;
                                            ?>
                                                <tr class="appointment-row">
                                                    <td><?= $billId ?></td>
                                                    <td><?= $date ?></td>
                                                    <td><a class="text-primary text-center" title="Print"
                                                            href="test-report-generate.php?bill-id='.$billId.'"><i
                                                                class="fa fa-link" aria-hidden="true"></i></a></td>
                                                </tr>
                                                <?php

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

    <script>
    const labels = <?php echo json_encode(array_keys($occurrences)) ?>;
    const data = <?php echo json_encode(array_values($occurrences)) ?>;

    const getRandomColor = () => {
        const r = Math.floor(Math.random() * 256);
        const g = Math.floor(Math.random() * 256);
        const b = Math.floor(Math.random() * 256);
        const randomColor = `rgba(${r}, ${g}, ${b}, 0.6)`;
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