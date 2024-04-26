<?php
$page = "dashboard";
require_once __DIR__ . '/config/constant.php';
require_once __DIR__ . '/config/service.const.php';
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

// require_once ROOT_DIR . '_config/accessPermission.php';
// require_once ROOT_DIR . '_config/toastOnPermission.php';


$appoinments       = new Appointments();
$CurrentStock      = new CurrentStock();
$StockOut          = new StockOut();
$StockIn           = new StockIn();
$StockInDetails    = new StockInDetails();
$Distributor       = new Distributor;
$Patients          = new Patients;
$LabAppointments   = new LabAppointments();
// $AccessPermission  = new AccessPermission();
// $Employees         = new Employees;


$totalAppointments = $appoinments->appointmentsDisplay($adminId);
$totalAppointments = json_decode($totalAppointments);

if ($totalAppointments->status) {
    $totalAppointmentsCount = count($totalAppointments->data);
} else {
    $totalAppointmentsCount = 0;
}

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
    <link href="<?php echo CSS_PATH; ?>sb-admin-2.css" rel="stylesheet">
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
                    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div> -->


                    <!-- CONTENT USER DATA ROW -->


                    <!-- Content Row -->
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <?php
                        if ($userRole != 2 || $userRole == 'ADMIN') : ?>
                            <!-- Sold By Card  -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "soldby.php"; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($userRole != 1 || $userRole == 'ADMIN') : ?>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "appointment-nos.php"; ?>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "newPatient.php"; ?>
                            </div>

                        <?php endif; ?>
                    </div>

                    <?php
                    if ($userRole != 2 || $userRole == 'ADMIN') : ?>
                        <!-- ================ THIRD ROW ================ -->
                        <div class="row d-flex">
                            <div class="col-md-4 mb-4">
                                <div class="row">
                                    <!-- Expiring in 3 Months Card -->
                                    <div class="col-xl-12 col-md-12 mb-4">
                                        <?php require_once ROOT_COMPONENT . "expiring.php"; ?>
                                    </div>

                                </div>

                                <div class="row">

                                    <!----------- Sales of the day card ----------->
                                    <div class="col-xl-12 col-md-12 mb-4">
                                        <?php require_once ROOT_COMPONENT . "salesoftheday.php"; ?>
                                    </div>

                                </div>

                                <div class="row">
                                    <!----------- Purchase today card ----------->
                                    <div class="col-xl-12 col-md-12 mb-4">
                                        <?php require_once ROOT_COMPONENT . "purchasedToday.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8 mb-4">
                                <?php require_once ROOT_COMPONENT . "sales-purchase.php"; ?>
                            </div>
                        </div>
                        <!-- ================ FORTH ROW ROW ================ -->
                        <div class="row">
                            <div class="col-xl-6 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "soldItems.php"; ?>
                            </div>

                            <div class="col-xl-6 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "mostvisitedcustomer.php"; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "mopdByAmount.php"; ?>
                            </div>

                            <div class="col-xl-6 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "mopdByItems.php"; ?>
                            </div>

                        </div>

                        <!-- ================== SECOND ROW ================== -->
                        <div class="row">

                            <!------------- SALES MARGIN CARD -------------->
                            <!-- <div class="col-xl-6 col-md-12"> -->
                            <?php //require_once ROOT_COMPONENT . "salesmargin.php"; 
                            ?>
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
                    <?php endif; ?>

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


    <script src="<?php echo JS_PATH; ?>index.js"></script>
</body>

</html>