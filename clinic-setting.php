<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';

$Subscription   = new Subscription;
$Utility        = new Utility;
// $$HealthCare    = new HealthCare;

$currentUrl = $Utility->currentUrl();
// Healthcare Addesss and details

if (isset($_POST['update']) ==  true) {

    // print_r($_FILES);exit;
    if (!empty($_FILES['site-logo']['name'])) {
        $logo = $_FILES['site-logo']['name'];
        $tempImgname    = $_FILES['site-logo']['tmp_name'];
        $imgFolder      = "assets/images/orgs/" . $logo;
        move_uploaded_file($tempImgname, $imgFolder);
    } else {
        $imgFolder  = '';
    }

    $healthCareName          = $_POST['helthcare-name'];
    $healthCareAddress1      = $_POST['address-1'];
    $healthCareAddress2      = $_POST['address-2'];
    $healthCareCity          = $_POST['city'];
    $healthCareDist          = $_POST['dist'];
    $healthCarePin           = $_POST['pin'];
    $healthCareState         = $_POST['state'];
    $healthCareEmail         = $_POST['email'];
    $healthCareHelpLineNo    = $_POST['helpline-no'];
    $healthCareApntBookingNo = $_POST['apnt-booking-no'];

    $UpdateHealthcare = $HealthCare->updateHealthCare($imgFolder, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCarePin, $healthCareState, $healthCareEmail, $healthCareHelpLineNo, $healthCareApntBookingNo, $adminId);
    // print_r($UpdateHealthcare);

    if ($UpdateHealthcare) {
        header("Location: $currentUrl?setup=Clinic Data Updated");
        exit;
    } else {
        header("Location: $currentUrl?setup=Updation Failed!");
        exit;
    }
}

$bills = json_decode($Subscription->getSubscription($adminId));
if ($bills->status) {
    $allBills = $bills->data;
} else {
    $allBills = array();
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

    <title><?= $healthCareName . " - " . SITE_NAME ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Sweet alert plugins -->
    <!-- <script src="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css"></script> -->

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>upload-design.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>helth-care.css" rel="stylesheet">
    <link href="<?= PLUGIN_PATH ?>img-uv/img-uv.css" rel="stylesheet">

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
                    <div class="card shadow h-100 py-2 pending_border animated--grow-in">
                        <div class="row mt-1">
                            <div class="col-12">
                                <div class="container-fluid">
                                    <div class="col-md-12">
                                        <ul class="nav nav-tabs row" role="tablist">
                                            <li class="nav-item col-4">
                                                <a class="nav-link active" data-toggle="tab" href="#home">Helth Care Details</a>

                                            </li>
                                            <li class="nav-item col-4">
                                                <a class="nav-link" data-toggle="tab" href="#menu1">Drug Permit Documents</a>
                                            </li>
                                            <li class="nav-item col-4">
                                                <a class="nav-link" data-toggle="tab" href="#menu2">Subscriptions Details</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="home" class="tab-pane active">
                                                <div class="col-xl-12 col-md-12 mb-4">
                                                    <?php require_once ROOT_COMPONENT . "navTab-clinic-setup.php"; ?>
                                                </div>
                                            </div>

                                            <div id="menu1" class="tab-pane fade">
                                                <div class="col-xl-12 col-md-12 mb-4">
                                                    <?php require_once ROOT_COMPONENT . "navTab-drug-permit-documents.php"; ?>
                                                </div>
                                            </div>

                                            <div id="menu2" class="tab-pane fade">
                                                <div class="col-xl-12 col-md-12 mb-4">
                                                    <?php require_once ROOT_COMPONENT . "navTab-subscription-tab.php"; ?>
                                                </div>
                                            </div>

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
            <div class="d-flex justify-content-center">
                <?php include ROOT_COMPONENT . 'footer-text.php'; ?>
            </div>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <script>

    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    <!-- <script src="<?= PLUGIN_PATH ?>img-uv/img-uv.js"></script> -->

    <!-- Sweet alert plugins -->
    <!-- <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script> -->

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>


</body>

</html>