<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

// echo $adminId;

$Utility    = new Utility;
$Request    = new Request;

$ticketNo = url_dec($_GET['ticket']);

$ticketDetails = json_decode($Request->selectFromTable($ticketNo));
$ticketData = $ticketDetails->data;

if ($_SESSION['ADMIN']) {
    $name = $USERFNAME . ' ' . $USERLNAME;
    $contact = $adminContact;
} else {
    $name = $USERFNAME;
    $contact = $empContact;
}

$ticketResponseDetails = json_decode($Request->selectFromResponseTable($ticketNo));
$responseData = $ticketResponseDetails->data;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Employees</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/employees.css">
    <link href="<?php echo CSS_PATH ?>form.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>/custom/password-show-hide.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">

    <style>
        /*  */
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
                    <div class="card-body">
                        <div class=" d-flex justify-content-center align-items-center">
                            <div class=" shadow bg-white rounded  profile">

                                <div id="home" class="tab-pane active">
                                    <form action="_config\form-submission\ticket-query-submit.php" enctype="multipart/form-data" method="post" id="ticket-form">
                                        <div class="container mt-5">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="form-group d-none">
                                                        <input type="text" class="form-control med-input" id="current-usr" name="current-usr" value="<?= $name; ?>" required readonly>
                                                        <input type="text" class="form-control med-input" id="table-name" name="table-name" value="<?= $ticketDetails->table ?>" required readonly>
                                                    </div>
                                                    <!-- Applicant Name and Email -->
                                                    <div class="row">
                                                        <div class="col-md-6 form-group">
                                                            <input type="text" class="form-control med-input" id="applicant-name" name="applicant-name" maxlength="100" value="<?= $ticketData->name; ?>" required readonly>
                                                            <label class="med-label" for="applicant-name" style="margin-left:10px;">Past Applicant Name</label>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <input type="email" class="form-control med-input" id="email1" name="email1" maxlength="100" value="<?= $userEmail ?>" required readonly>
                                                            <label class="med-label" for="email1" style="margin-left:10px;">Email id</label>
                                                        </div>
                                                    </div>
                                                    <!-- Message Title and Contact Number -->
                                                    <div class="row">
                                                        <div class="col-md-6 form-group">
                                                            <input type="text" class="form-control med-input" id="title" name="title" value="<?= $ticketData->title; ?>" required>
                                                            <label class="med-label" for="title" style="margin-left:10px;">Message Title</label>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <input type="number" class="form-control med-input" id="mobile-number1" name="mobile-number1" maxlength="10" max="9999999999" value="<?= $contact; ?>" required readonly>
                                                            <label class="med-label" for="mobile-number1" style="margin-left:10px;">Contact Number</label>
                                                        </div>
                                                    </div>
                                                    <!-- Description -->
                                                    <div class="row">
                                                        <div class="col-md-12 form-group">
                                                            <textarea class="form-control med-input" id="ticket-description1" name="ticket-description1" style="height: 116px;" required><?= $ticketData->message; ?></textarea>
                                                            <label class="med-label" for="ticket-description1" style="margin-left:10px;">Description</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Document Upload Section -->
                                                <div class="col-md-5">
                                                    <div class="card med-card" style="border: 1px solid #ced4da; padding: 1rem; height: 14rem; position: relative;">
                                                        <div id="document-show-1" class="col-sm-11 card med-card"></div>
                                                    </div>
                                                    <label class="med-label text-primary mt-n4" for="fileInput1" style="margin-left:10px;">Document</label>
                                                    <i class="fas fa-upload text-primary" id="upload-document1" style="position: absolute; left: 19rem; bottom: 30px; cursor: pointer;" onclick="document.getElementById('fileInput1').click();"></i>
                                                    <input type="file" class="d-none" name="fileInput1" id="fileInput1" onchange="takeInputFile(this, 'document-show-1')" 
                                                    >
                                                </div>
                                            </div>

                                            <!-- Response Details -->
                                            <div class="row mt-3">
                                                <div class="col-md-12 form-group">
                                                    <textarea class="form-control med-input" id="ticket-response" name="ticket-response" style="height: 105px;" required readonly><?= $responseData->response; ?></textarea>
                                                    <label class="med-label" for="ticket-response" style="margin-left:10px;">Response Details</label>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="row mt-3">
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button class="btn btn-sm btn-primary" type="submit" name="regenerate-query" id="regenerate-query">Submit Query</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Custom Javascript -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    <script src="<?php echo JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.js"></script>
    <script src="<?php echo JS_PATH ?>ticket-query-generator.js"></script>

</body>

</html>