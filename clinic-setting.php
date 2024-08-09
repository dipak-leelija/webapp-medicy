<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';

// echo $healthCareDist;
// echo $panData;

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
        header("Location: $currentUrl?setup=Clinic Data Updated & flag=1");
        exit;
    } else {
        header("Location: $currentUrl?setup=Updation Failed! & flag=0");
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

    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title><?= $healthCareName . " - " . SITE_NAME ?></title>

    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.min.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>upload-design.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>form.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>helth-care.css" type="text/css">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>img-uv/img-uv.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css">


    <style>
        .image-preview {
            width: 100%;
            height: 200px;
            /* Set the fixed height as needed */
            border: 1px solid #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .image-preview img,
        .image-preview embed {
            width: auto;
            height: 100%;
            object-fit: contain;
        }
    </style>
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
                    <?php require_once ROOT_COMPONENT . "drugPermitDataAlert.php"; ?>

                    <div class="row mt-4 d-none" style="z-index: 999;" id="msg-div">
                        <?php require_once ROOT_COMPONENT . "drugPermitDataUpdateMsg.php"; ?>
                    </div>

                    <div class="card shadow h-100 py-2 pending_border animated--grow-in">
                        <div class="row mt-1">
                            <div class="col-12">
                                <div class="container-fluid">
                                    <div class="col-md-12">
                                        <label class="d-none" id="nav-pan-flag">nav flag</label>
                                        <?php
                                        // PHP code
                                        if (isset($_GET['tab-control'])) {
                                            // echo 'hello';
                                            echo '<script>document.getElementById("nav-pan-flag").innerHTML = "1";</script>'; // JavaScript code
                                        }

                                        ?>
                                        <ul class="nav nav-tabs row" role="tablist">
                                            <li class="nav-item col-4">
                                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home">Helth Care Details</a>
                                            </li>
                                            <li class="nav-item col-4">
                                                <a class="nav-link" id="menu1-tab" data-toggle="tab" href="#menu1">Drug Permits</a>
                                            </li>
                                            <li class="nav-item col-4">
                                                <a class="nav-link" id="menu2-tab" data-toggle="tab" href="#menu2">Subscriptions</a>
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

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>img-uv/img-uv.js"></script>

    <!-- Sweet alert plugins -->
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script>
        if (document.getElementById("nav-pan-flag").innerHTML.trim() === '1') {

            // control home menue
            document.getElementById("home").classList.remove("active");
            document.getElementById("home-tab").classList.remove("active");
            document.getElementById("home").classList.add("fade");

            // control menue 1
            document.getElementById("menu1").classList.remove("fade");
            document.getElementById("menu1").classList.add("show", "active");
            document.getElementById("menu1-tab").classList.add("active");

            // control menue 2
            document.getElementById("menu2").classList.remove("active");
            document.getElementById("menu2-tab").classList.remove("active");

            document.getElementById("nav-pan-flag").innerHTML = 'nav-tab';
        }


        const msgDivControlFun = () => {
            let alertDivControl = document.getElementById("alert-div-control");

            if (alertDivControl.innerHTML.trim() === '0') {
                document.getElementById("alert-div").classList.remove('d-none');
                document.getElementById("msg-div").classList.add('d-none');
            }

            if (alertDivControl.innerHTML.trim() === '1') {
                document.getElementById("alert-div").classList.add('d-none');
                document.getElementById("msg-div").classList.remove('d-none');
            }

            if (alertDivControl.innerHTML.trim() !== '0' && alertDivControl.innerHTML.trim() !== '1') {
                document.getElementById("alert-div").classList.remove('d-none');
                document.getElementById("msg-div").classList.remove('d-none');
            }
        }

        msgDivControlFun();
    </script>

</body>

</html>