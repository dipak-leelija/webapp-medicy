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
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'component.class.php';
require_once CLASS_DIR . 'utility.class.php';


// require_once ROOT_DIR . '_config/accessPermission.php';
// require_once ROOT_DIR . '_config/toastOnPermission.php';


$appoinments       = new Appointments();
$CurrentStock      = new CurrentStock();
$StockOut          = new StockOut();
$StockIn           = new StockIn();
$StockInDetails    = new StockInDetails();
$Distributor       = new Distributor;
$Patients          = new Patients;
$LabBilling        = new LabBilling;
$Component         = new Component;
$Utility           = new Utility;
// $LabAppointments   = new LabAppointments();
// $AccessPermission  = new AccessPermission();
// $Employees         = new Employees;


$totalAppointments = $appoinments->appointmentsDisplay($adminId);
$totalAppointments = json_decode($totalAppointments);

if ($totalAppointments->status) {
    $doctorAppointmentsCount = count($totalAppointments->data);
} else {
    $doctorAppointmentsCount = 0;
}

// $labAppointmentCount     = $LabAppointments->labAppointmentNos($adminId);
$labBills = json_decode($LabBilling->labBillDisplay($adminId));
if ($labBills->status == 1) {
    $labBillNos = count($labBills->data);
}else {
    $labBillNos = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - <?= $healthCareName ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH; ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= CSS_PATH; ?>sb-admin-2.css">
    <link rel="stylesheet" href="<?= CSS_PATH; ?>custom/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= CSS_PATH; ?>custom-dashboard.css">
    <script src="<?php echo JS_PATH; ?>ajax.custom-lib.js"></script>

    
    <!-- ======== CUSTOM JS FOR INDEX PAGE ======= -->
    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
    <script src="<?= PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>
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
                    <div class="row" style="z-index: 999;">
                        <div class="col-12">
                            <?php include ROOT_COMPONENT . "drugPermitDataAlert.php"; ?>
                        </div>
                    </div>

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

                            <!-- Number of Appointments -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "AppointmentNumbers.php"; ?>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <?php require_once DASHBOARD_COMPONENT . "NewPatientCount.php"; ?>
                            </div>

                        <?php endif; ?>
                    </div>

                    <?php
                    if ($userRole != 2 || $userRole == 'ADMIN') : ?>
                        <!-- ================ THIRD ROW ================ -->
                        <div class="row d-flex">
                            <div class="col-md-4 mb-4">
                                <!-- <div class="row"> -->
                                    <!-- Expiring in 3 Months Card -->
                                    <!-- <div class="col-xl-12 col-md-12 mb-4">
                                        <?php require_once ROOT_COMPONENT . "expiring.php"; ?>
                                    </div> -->

                                <!-- </div> -->

                                <!-- <div class="row"> -->

                                    <!----------- Sales of the day card ----------->
                                    <!-- <div class="col-xl-12 col-md-12 mb-4">
                                        <?php // require_once ROOT_COMPONENT . "salesoftheday.php"; ?>
                                    </div> -->

                                <!-- </div> -->

                                <div class="row">
                                    <!----------- Purchase today card ----------->
                                    <div class="col-xl-12 col-md-12 mb-4">
                                        <?php require_once DASHBOARD_COMPONENT . "SalesByEmployee.php"; ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <!----------- Purchase today card ----------->
                                    <div class="col-xl-12 col-md-12 mb-4">
                                        <?php require_once ROOT_COMPONENT . "most-purchased.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8 mb-4">
                                <?php require_once ROOT_COMPONENT . "sales-purchase.php"; ?>
                            </div>
                        </div>
                        <!-- ================ FORTH ROW ROW ================ -->
                        <div class="row">
                            <!-- <div class="col-xl-6 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "soldItems.php"; ?>
                            </div> -->

                            <div class="col-xl-6 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "soldItems-updated.php"; ?>
                            </div>

                            <div class="col-xl-6 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "mostvisitedcustomer.php"; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "soldItems-updated.php"; ?>
                            </div>

                            <!-- <div class="col-xl-6 col-md-6 mb-4">
                                <?php require_once ROOT_COMPONENT . "mopdByItems.php"; ?>
                            </div> -->

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
                                <?php require_once DASHBOARD_COMPONENT . "StockSummary.php"; ?>
                                <!------------- end Stock Summary -------------->

                            </div>
                        </div>
                        <!-- ================ END OF THIRD ROW ================ -->
                    <?php endif; ?>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
            
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>
    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH; ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH; ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH; ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH; ?>sb-admin-2.js"></script>

    <script src="<?= JS_PATH; ?>index.js"></script>

</body>

</html>