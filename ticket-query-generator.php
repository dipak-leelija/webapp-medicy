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
    $currentUserId  = $adminId;
    $firstName      = $adminName;
    $lastName       = $adminLname;
    $userName       = $username;
    $email          = $userEmail;
    $phone          = $adminContact;

    $name = $firstName . ' ' . $lastName;
} else {
    $currentUserId  = $employeeId;
    $adminid        = $adminId;
    $firstName      = $USERFNAME;
    $lastName       = $USERLNAME;
    $userName       = $username;
    $email          = $userEmail;
    $phone          = $EMPCONTACT;

    $name = $firstName . ' ' . $lastName;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Generate New Ticket - <?= $HEALTHCARENAME ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/employees.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>form.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
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
                        <div class=" d-flex justify-content-center shadow bg-white rounded">
                            <div class="col-3 text-white">
                                <div class=" text-center rounded m-3 mt-0 p-5 " style="height:200px;background-color:#4e73df;">
                                    <svg width="2.5rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="#ffffff" d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z" />
                                    </svg>
                                    <h4 class="mt-5">Contact</h4>
                                </div>
                                <div class=" text-center rounded  m-3 p-5 " style="height:200px;background-color:#4e73df;">
                                    <svg width="2.5rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="#ffffff" d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z" />
                                    </svg>
                                    <h4 class="mt-5">Email</h4>
                                </div>
                            </div>
                            <div class="col-9 mt-5 px-3 ">

                                <div>
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs row" role="tablist">
                                        <li class="nav-item col-6">
                                            <a class="nav-link active" data-toggle="tab" href="#home">Raise a Ticket</a>
                                        </li>
                                        <li class="nav-item col-6">
                                            <a class="nav-link" data-toggle="tab" href="#menu1">Add Request / Query</a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- tab 1 -->
                                        <div id="home" class="tab-pane active">
                                            <!-- <form enctype="multipart/form-data" method="post" id="ticket-form"> -->
                                            <div class="col-12 d-flex mt-5">
                                                <div class="col-md-7">
                                                    <div class="d-none col-md-12 form-group">
                                                        <input type="text" class="med-input" id="current-usr1" name="current-usr1" maxlength="20" value="<?= $name; ?>" required readonly>
                                                    </div>
                                                    <div class="row d-flex">
                                                        <div class="col-md-6 form-group">
                                                            <input type="email" class=" med-input" id="email1" name="email1" maxlength="100" value="<?= $email; ?>" required>
                                                            <label class="med-label" style="margin-left:10px;" for="emial">Email id</label>
                                                        </div>

                                                        <div class="col-md-6 form-group">
                                                            <input type="number" class=" med-input" id="mobile-number1" name="mobile-number1" minlength="10" maxlength="10" max="9999999999" value="<?= $phone; ?>" required>
                                                            <label class="med-label" for="mobile-number">Contact
                                                                Number</label>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 form-group">
                                                            <input type="text" class=" med-input" id="title1" name="title1" required autocomplete="off">
                                                            <label class="med-label" style="margin-left:10px;" for="title1">Message Title</label>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 form-group">
                                                            <textarea class="med-input" placeholder="" name="ticket-description1" id="ticket-description1" style="height: 105px;" required></textarea>
                                                            <label class="med-label" style="margin-left:10px;" for="address">Description</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="col-md-12 form-group card med-card" style="border: 1px solid #ced4da; padding: 8px; height: 14rem; position: relative;">
                                                        <div class="col-sm-12 card med-card" id="document-show-1">
                                                        </div>
                                                    </div>
                                                    <label class="med-label mt-n4 text-primary" style="margin-left:10px;" for="address">Document</label>
                                                    <i class="fas fa-upload text-primary" id="upload-document1" style="position: absolute; left: 18rem; bottom: 30px; cursor: pointer;" onclick="document.getElementById('fileInput1').click();"></i>
                                                    <input type="file" class="d-none" name="fileInput1" id="fileInput1" onchange="takeInputFile(this, 'document-show-1')">
                                                </div>

                                            </div>
                                            <div class="d-flex justify-content-end mr-4 mt-2">
                                                <button class="btn btn-sm btn-primary" type="submit" name="ticket-submit" id="ticket-submit" onclick="requestSubmit(this)">Rais Ticket</button>
                                            </div>
                                            <!-- </form> -->
                                        </div>
                                        <!-- tab 2 -->
                                        <div id="menu1" class="tab-pane fade">
                                            <!-- <form enctype="multipart/form-data" method="post" id="query-form"> -->
                                            <div class="col-12 d-flex mt-5">
                                                <div class="col-md-7">
                                                    <div class="d-none col-md-12 form-group">
                                                        <input type="text" class="med-input" id="current-usr2" name="current-usr2" maxlength="20" value="<?= $name; ?>" required readonly>
                                                        <input type="text" class="med-input" id="form-flag" name="form-flag" value="1" required readonly>
                                                    </div>
                                                    <div class="row d-flex">
                                                        <div class="col-md-6 form-group">
                                                            <input type="email" class=" med-input" id="email2" name="email2" maxlength="100" value="<?= $email; ?>" required>
                                                            <label class="med-label" style="margin-left:10px;" for="emial">Email id</label>
                                                        </div>

                                                        <div class="col-md-6 form-group">
                                                            <input type="number" class=" med-input" id="mobile-number2" name="mobile-number2" minlength="10" maxlength="10" max="9999999999" value="<?= $phone; ?>" required>
                                                            <label class="med-label" for="mobile-number">Contact
                                                                Number</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 form-group">
                                                            <input type="text" class=" med-input" id="title2" name="title2" required autocomplete="off">
                                                            <label class="med-label" style="margin-left:10px;" for="title2">Message Title</label>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 form-group">
                                                            <textarea class="med-input" placeholder="" name="ticket-description2" id="ticket-description2" style="height: 105px;" required></textarea>
                                                            <label class="med-label" style="margin-left:10px;" for="address">Description</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="col-md-12 form-group card med-card" style="border: 1px solid #ced4da; padding: 8px; height: 14rem; position: relative;">
                                                        <div class="col-sm-12 card med-card" id="document-show-2">
                                                        </div>
                                                    </div>
                                                    <label class="med-label mt-n4" style="margin-left: 10px;" for="address">Document</label>
                                                    <i class="fas fa-upload text-primary" id="upload-document2" style="position: absolute; left: 18rem; bottom: 30px; cursor: pointer;" onclick="document.getElementById('fileInput2').click();"></i>
                                                    <input type="file" class="d-none" name="fileInput2" id="fileInput2" onchange="takeInputFile(this, 'document-show-2')">
                                                </div>
                                            </div>
                                            <div class="mt-2 d-flex justify-content-end mr-4 mt-2">
                                                <button type="submit" name="query-submit" id="query-submit" class="btn btn-sm btn-primary" onclick="requestSubmit(this)">Add Query</button>
                                            </div>
                                            <!-- </form> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- End of Page Content -->
            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

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