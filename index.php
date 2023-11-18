<?php
$page = "dashboard";

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'labAppointments.class.php';

$appoinments       = new Appointments();
$CurrentStock      = new CurrentStock();
$StockOut          = new StockOut();
$StockIn           = new StockIn();
$StockInDetails    = new StockInDetails();
$Distributor       = new Distributor;
$Patients          = new Patients;
$LabAppointments   = new LabAppointments();

$totalAppointments = $appoinments->appointmentsDisplay($adminId);
$labAppointment     = $LabAppointments->showLabAppointmentsByAdminId($adminId);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Health Care - Admin Portal</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH; ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH; ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH; ?>custom/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>custom-dashboard.css">

    <script src="<?php echo JS_PATH; ?>ajax.custom-lib.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT . "sidebar.php"; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar-->
                <?php include ROOT_COMPONENT . "topbar.php"; ?>
                <!-- End of Tobbar-->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>


                    <!-- CONTENT USER DATA ROW -->
                    <div class="row">
                        <!-- Sold By Card  -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once ROOT_COMPONENT . "soldby.php"; ?>
                        </div>
                    </div>


                    <!-- Content Row -->
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Appointments</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo count($totalAppointments); ?> </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo count($totalAppointments); ?> </div>
                                    <p class="mb-0 pb-0"><small class="mb-0 pb-0">
                                           Lab Appointments: <?php  echo ($labAppointment > 0) ? $labAppointment : '0';?></small></p>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once ROOT_COMPONENT . "newPatient.php"; ?>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Patient
                                                Treated
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-theater-masks"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2 pending_border">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Pending Requests</div>
                                                <div class="col-auto  mr-n3">
                                                    <i class="fas fa-pencil-ruler"></i>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-pencil-ruler"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- ================ THIRD ROW ================ -->
                    <div class="row">

                        <!-- Expiring in 3 Months Card -->
                        <div class="col-xl-4 col-md-4 mb-4">
                            <?php require_once ROOT_COMPONENT . "expiring.php"; ?>
                        </div>

                        <!----------- Sales of the day card ----------->
                        <div class="col-xl-4 col-md-4 mb-4">
                            <?php require_once ROOT_COMPONENT . "salesoftheday.php"; ?>
                        </div>

                        <!----------- Purchase today card ----------->
                        <div class="col-xl-4 col-md-4 mb-4">
                            <?php require_once ROOT_COMPONENT . "purchasedToday.php"; ?>
                        </div>

                    </div>

                    <!-- ================ FORTH ROW ROW ================ -->
                    <div class="row">

                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once ROOT_COMPONENT . "mostsolditems.php"; ?>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once ROOT_COMPONENT . "lesssolditems.php"; ?>
                        </div>
                        <br>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once ROOT_COMPONENT . "mostvisitedcustomer.php"; ?>
                        </div>
                        <br>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <?php require_once ROOT_COMPONENT . "highestpurchasedcustomer.php"; ?>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once ROOT_COMPONENT . "mopdByAmount.php"; ?>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <?php require_once ROOT_COMPONENT . "mopdByItems.php"; ?>
                        </div>

                    </div>

                    <!-- ================== SECOND ROW ================== -->
                    <div class="row">

                        <!------------- SALES MARGIN CARD -------------->
                        <!-- <div class="col-xl-6 col-md-12"> -->
                            <?php //require_once ROOT_COMPONENT . "salesmargin.php"; ?>
                        <!-- </div> -->
                        <!------------- END of SALES MARGIN CARD -------------->

                        <div class="col-xl-6 col-md-6">
                            <!------------- NEEDS TO COLLECT PAYMENTS -------------->
                            <?php require_once ROOT_COMPONENT . "needstocollect.php"; ?>
                            <!------------- END NEEDS TO COLLECT PAYMENTS -------------->

                        </div>

                        <div class="col-xl-6 col-md-6">
                            <!------------- NEEDS TO PAY  -------------->
                            <?php require_once ROOT_COMPONENT . "needtopay.php"; ?>
                            <!------------- END NEEDS TO PAY  -------------->
                        </div>

                    </div>
                    <!-- ================ END SECOND ROW ================ -->

                    <!-- ================ STRAT THIRD ROW ================ -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <!-- Current Stock Quantity & MRP  -->
                            <?php require_once ROOT_COMPONENT . "stock-qty-mrp.php"; ?>
                        </div>
                        <div class="col-xl-9 col-md-6">
                            <!------------- Stock Summary -------------->
                            <?php require_once ROOT_COMPONENT . "stock-summary.php"; ?>
                            <!------------- end Stock Summary -------------->

                        </div>
                    </div>
                    <!-- ================ END OF THIRD ROW ================ -->

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include ROOT_COMPONENT . 'footer-text.php'; ?>
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
    <script src="<?php echo PLUGIN_PATH; ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH; ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH; ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH; ?>sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

    <!-- Page level custom scripts -->
    <!-- <script src="js/demo/chart-area-demo.js"></script>
    

     ======== CUSTOM JS FOR INDEX PAGE ======= -->
    <script src="<?php echo PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>

</body>

</html>