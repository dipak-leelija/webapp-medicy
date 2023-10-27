<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
$page = "patients";

require_once CLASS_DIR . 'dbconnect.php';
require_once ADM_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'labBilling.class.php';


$patientId = url_dec($_GET['patient']);

$Patients   = new Patients;
$LabBilling = new LabBilling;

$patientDetails = json_decode($Patients->patientsDisplayByPId($patientId));
// print_r($patientDetails);
$Name = $patientDetails->name;
$Age  = $patientDetails->age;
$sex  = $patientDetails->gender;
$address = $patientDetails->address_1;
$labVisited = $patientDetails->lab_visited;
$lastVisited = $patientDetails->added_on;

// if ($patientId) {
//     $patientCount = $Patients->patientVisitCount($Name, $patientId);
//     // print_r($patientCount['count']);
//     //$patientCount['Last_Visited']
// }

/// for amount spend ///
$labBillingDetails = $LabBilling->labBiilingDetailsByPatientId($patientId);

$spent = 0;
if (is_array($labBillingDetails) && !empty($labBillingDetails)) {
    foreach ($labBillingDetails as $row) {
        $spent = $row->paid_amount + $spent;
    }
} elseif ($labBillingDetails === null) {
    //  echo "No results found.";
} else {
    echo "Error: " . $labBillingDetails;
} //--end--//

///bar chart for Most taken Tests as graph//
$dataPoints2 = array(
    array("y" => 7, "label" => "March"),
    array("y" => 12, "label" => "April"),
    array("y" => 28, "label" => "May"),
    array("y" => 18, "label" => "June"),
    array("y" => 41, "label" => "July")
);

///for pie chart ///
$dataPoints1 = array(
    array("label" => "Oxygen", "symbol" => "O", "y" => 46.6),
    array("label" => "Silicon", "symbol" => "Si", "y" => 27.7),
    array("label" => "Aluminium", "symbol" => "Al", "y" => 13.9),
    array("label" => "Iron", "symbol" => "Fe", "y" => 5),
    array("label" => "Calcium", "symbol" => "Ca", "y" => 3.6),
    array("label" => "Sodium", "symbol" => "Na", "y" => 2.6),
    array("label" => "Magnesium", "symbol" => "Mg", "y" => 2.1),
    array("label" => "Others", "symbol" => "Others", "y" => 1.5),

)
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script>
        window.onload = function() {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "Most taken Tests"
                },
                axisY: {
                    // title: "Lab Test",
                    includeZero: true,
                    prefix: "$",
                    suffix: "k"
                },
                data: [{
                    type: "splineArea",
                    // color: "background: rgb(255,255,255);
                    // background: "background: rgb(255,255,255)",
                    background: "linear - gradient(0 deg, rgba(255, 255, 255, 1) 55 % , rgba(34, 195, 193, 1) 100 % )",
                    markerSize: 5,
                    yValueFormatString: "$#,##0K",
                    indexLabel: "{y}",
                    // indexLabelPlacement: "inside",
                    indexLabelFontWeight: "bolder",
                    indexLabelFontColor: "white",
                    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

            var chart1 = new CanvasJS.Chart("chartContainer1", {
                theme: "light2",
                animationEnabled: true,
                title: {
                    text: "Most purchased medicine"
                },
                data: [{
                    type: "doughnut",
                    indexLabel: "{symbol} - {y}",
                    yValueFormatString: "#,##0.0\"%\"",
                    showInLegend: true,
                    legendText: "{label} : {y}",
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart1.render();

        }
    </script>
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
                            <div style="display: flex; align-items:flex-start;justify-content:space-between; flex-wrap: wrap">
                                <div style="width: 60%; margin:0px; padding:8px; border-radius:4px; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                                    <div style="display: flex; align-items:flex-start; justify-content:space-around;flex-wrap: wrap">
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
                                            <p><samp>Last Visited &nbsp&nbsp: <small><?= $lastVisited ?></small></spam>
                                            </p>
                                            <p><samp>Amount spend &nbsp&nbsp: ₹ <small><?= ($spent) ? $spent : '0.0' ?></small></spam>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div style="width: 38%; margin:0px; padding:4px; border-radius:4px; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                                    <div id="chartContainer" style="height: 169px; width: 100%;"></div>
                                </div>
                            </div>
                            <div style="width: 100%; margin-top:16px; padding:4px; border-radius:4px; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                                <div id="chartContainer1" style="height: 250px; width: 100%;"></div>
                            </div>
                            <div style="display: flex; align-items:flex-start;justify-content:space-between; flex-wrap: wrap;width: 100%;margin-top:15px; padding:4px; border-radius:4px;">
                                <div style="width: 49%; padding:8px; border-radius:4px; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
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
                                <div style="width: 49%; padding:8px; border-radius:4px; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                                    <p>List Of Test</p>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Test</th>
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

    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>

</html>

<!-- <?= $patientCount['count'] ?> -->