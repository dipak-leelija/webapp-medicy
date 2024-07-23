<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'empRole.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

// echo $adminId;

$Utility    = new Utility;
$Admin      = new Admin;
$employees  = new Employees();
$desigRole = new Emproles();

$currentUrl = $Utility->currentUrl();

$showEmployees = $employees->employeesDisplay($adminId);
$showDesignation = $desigRole->designationRoleCheckForLogin();
$showDesignation = json_decode($showDesignation, true);



$profileDetails = array();
if ($_SESSION['ADMIN']) {
    $currentUserId = $adminId;
    $adminDetails = $Admin->adminDetails($adminId);
    $adminDetails = json_decode($adminDetails);
    if ($adminDetails->status) {
        $adminData = $adminDetails->data;

        $firstName  = $adminData->fname;
        $lastName   = $adminData->lname;
        $userName   = $adminData->username;
        $email      = $adminData->email;
        $phone      = $adminData->mobile_no;
        
    }

    $name = $firstName.' '.$lastName;
} else {
    $currentUserId = $employeeId;
    $employeeDetails = json_decode($employees->employeeDetails($employeeId, $adminId));

    if ($employeeDetails->status) {
        $employeeData = $employeeDetails->data;
        // print_r($employeeData);
        foreach ($employeeData as $employeeData) {

            $empName = $employeeData->emp_name;

            $lastSpacePos = strrpos($empName, ' ');

            if ($lastSpacePos !== false) {
                $firstName = substr($empName, 0, $lastSpacePos);
                $lastName = substr($empName, $lastSpacePos + 1);
            }

            $email      = $employeeData->emp_email;
        }
    }
    $name = $empName;
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

                                <div>
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs row" role="tablist">
                                        <li class="nav-item col-6">
                                            <a class="nav-link active" data-toggle="tab" href="#home">Rais Ticket</a>
                                        </li>
                                        <li class="nav-item col-6">
                                            <a class="nav-link" data-toggle="tab" href="#menu1">Add Request / Query</a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- tab 1 -->
                                        <div id="home" class="tab-pane active">
                                            <form action="_config\form-submission\ticket-query-submit.php" enctype="multipart/form-data" method="post" id="ticket-form">
                                                <div class="col-12 d-flex mt-5">
                                                    <div class="col-md-7">
                                                        <div class="d-none col-md-12 form-group">
                                                            <input type="text" class="med-input" id="current-usr1" name="current-usr1" maxlength="20" value="<?= $name; ?>" required readonly>
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <input type="text" class=" med-input" id="email1" name="email1" maxlength="100" value="<?= $email; ?>" required>
                                                            <label class="med-label" style="margin-left:10px;" for="emial">Email id</label>
                                                        </div>

                                                        <div class="col-md-12 form-group">
                                                            <input type="number" class=" med-input" id="mobile-number1" name="mobile-number1" maxlength="10" max="9999999999" value="<?= $phone; ?>" required>
                                                            <label class="med-label" for="mobile-number">Contact
                                                                Number</label>
                                                        </div>

                                                        <div class="col-md-12 form-group">
                                                            <textarea class="med-input" placeholder="" name="ticket-description1" id="ticket-description1" style="height: 105px;" required></textarea>
                                                            <label class="med-label" style="margin-left:10px;" for="address">Description</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="col-md-12 form-group card med-card" style="border: 1px solid #ced4da; padding: 1rem; height: 14rem; position: relative;">
                                                            <div class="col-sm-11 card med-card" id="document-show-1"></div>
                                                        </div>
                                                        <label class="med-label mt-n4 text-primary" style="margin-left:10px;" for="address">Document</label>
                                                        <i class="fas fa-upload text-primary" id="upload-document1" style="position: absolute; left: 19rem; bottom: 30px; cursor: pointer;" onclick="document.getElementById('fileInput1').click();"></i>
                                                        <input type="file" class="d-none" name="fileInput1" id="fileInput1" onchange="takeInputFile(this, 'document-show-1')" required>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-sm btn-primary" type="submit" name="ticket-submit" id="ticket-submit" /*onclick="requestSubmit(this)"*/>Rais Ticket</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- tab 2 -->
                                        <div id="menu1" class="tab-pane fade">
                                            <form action="_config\form-submission\ticket-query-submit.php" enctype="multipart/form-data" method="post" id="query-form">
                                                <div class="col-12 d-flex mt-5">
                                                    <div class="col-md-7">
                                                        <div class="d-none col-md-12 form-group">
                                                            <input type="text" class="med-input" id="current-usr2" name="current-usr2" maxlength="20" value="<?= $name; ?>" required readonly>
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <input type="text" class=" med-input" id="email2" name="email2" maxlength="100" value="<?= $email; ?>" required>
                                                            <label class="med-label" style="margin-left:10px;" for="emial">Email id</label>
                                                        </div>

                                                        <div class="col-md-12 form-group">
                                                            <input type="number" class=" med-input" id="mobile-number2" name="mobile-number2" maxlength="10" max="9999999999" value="<?= $phone; ?>" required>
                                                            <label class="med-label" for="mobile-number">Contact
                                                                Number</label>
                                                        </div>

                                                        <div class="col-md-12 form-group">
                                                            <textarea class="med-input" placeholder="" name="ticket-description2" id="ticket-description2" style="height: 105px;" required></textarea>
                                                            <label class="med-label" style="margin-left:10px;" for="address">Description</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="col-md-12 form-group card med-card" style="border: 1px solid #ced4da; padding: 1rem; height: 14rem; position: relative;">
                                                            <div class="col-sm-11 card med-card" id="document-show-2"></div>
                                                        </div>
                                                        <label class="med-label mt-n4" style="margin-left: 10px;" for="address">Document</label>
                                                        <i class="fas fa-upload text-primary" id="upload-document2" style="position: absolute; left: 19rem; bottom: 30px; cursor: pointer;" onclick="document.getElementById('fileInput2').click();"></i>
                                                        <input type="file" class="d-none" name="fileInput2" id="fileInput2" onchange="takeInputFile(this, 'document-show-2')" required>
                                                    </div>
                                                </div>
                                                <div class="mt-2 d-flex justify-content-end">
                                                    <button type="submit" name="query-submit" id="query-submit" class="btn btn-sm btn-primary" /*onclick="requestSubmit(this)"*/>Add Query</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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


    <!--  -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script> -->

</body>

</html>