<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'report-generate.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';

$billId = $_GET['bill-id'];
$LabReport = new LabReport();
$LabBilling = new LabBilling();
$LabBillDetails = new LabBillDetails();

$labBillingData = $LabBilling->labBillDisplayById($billId); ///
$labBillingDetails = $LabBillDetails->billDetailsById($billId); //labBillingDetails
$showpatient = $LabReport->patientDatafetch($labBillingData[0]['patient_id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lab Report Generate</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Sweet Alert Link  -->
    <script src="../js/sweetAlert.min.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 booked_btn">

                            <div class="card-body">
                                <?php
                                $showpatient = json_decode($showpatient);
                                if ($showpatient !== null) {
                                    $patientName = $showpatient->name;
                                    $patientAge = $showpatient->age;
                                    $patientSex = $showpatient->gender;
                                }
                                $testDate = $labBillingData[0]['test_date'];
                                ?>
                                <div style="display: flex; justify-content:space-between; align-items: center;flex-wrap: wrap;">
                                    <h6><b>Patient Name:</b> <?php echo $patientName; ?></h6>
                                    <h6><b>Age:</b> <?php echo $patientAge; ?></h6>
                                    <h6><b>Sex:</b> <?php echo $patientSex; ?></h6>
                                    <h6><b>Test Date:</b> <?php echo $testDate; ?></h6>
                                </div>

                                <hr class="sidebar-divider">
        
                                <div>
                                    <?php
                                    $unitCounts = array();

                                    foreach ($labBillingDetails as $index => $test) {
                                        $testId = $test['test_id'];
                                        $showTestName = $LabReport->patientTest($testId);
                                        $showTestName = json_decode($showTestName);
                                        $testId = $showTestName->id;
                                        $subTestName = $showTestName->sub_test_name;
                                        $unitNames = $showTestName->unit;

                                        echo "<div style='margin:5px 0px 10px 0px;width:100%;heigh:auto;padding:10px;box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;'>";
                                        echo "<div>
                                                    <div style='width:40%; margin-left:20px;'>$subTestName</div>";
                                        if (!empty($unitNames)) {
                                            // Split the unitNames by comma and store them in an array
                                            $unitValues = explode(',', $unitNames);
                                            foreach ($unitValues as $unitValue) {
                                                // Trim to remove any leading or trailing whitespace
                                                $unitValue = trim($unitValue);
                                                // Count the occurrences of each unit value
                                                if (isset($unitCounts[$unitValue])) {
                                                    $unitCounts[$unitValue]++;
                                                } else {
                                                    $unitCounts[$unitValue] = 1;
                                                }

                                                // Generate input boxes based on the count of unit values
                                                for ($i = 0; $i < $unitCounts[$unitValue]; $i++) {
                                                    echo "<div class='d-flex justify-content-end' style='margin-left: 50%;'>";
                                                    echo "<input type='text' name='unit_$unitValue' placeholder='$unitValue' style='width:200px; margin-right:20px; border: none; border-bottom: 1px solid #000; padding: 5px; box-sizing: border-box; outline: none; background-color: transparent;' onfocus='this.style.borderBottom=\"2px solid #000\";' onblur='this.style.borderBottom=\"1px solid #000\";'>";
                                                    echo "</div>";
                                                }
                                            }
                                        }
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                    ?>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button id="generateReport" class="btn btn-primary btn-sm" onclick="reportgenerate()">Generate Report</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->






    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>




    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>