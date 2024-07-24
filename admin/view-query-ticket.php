<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

//Intitilizing Doctor class for fetching doctors
$Products       = new Products();
$Request        = new Request;
$Pagination     = new Pagination();
$ProductImages  = new ProductImages();
$Admin = new Admin;

$token = $_GET['tokenNo'];
$tableName = $_GET['table'];


if ($tableName == 'Generate Quarry') {
    $table = 'query_request';
} elseif ($tableName == 'Generate Ticket') {
    $table = 'ticket_request';
}

$queryDetails = json_decode($Request->fetchDataByTableName($token, $table));
$tableData = $queryDetails->data;
// print_r($tableData);

$adminData = json_decode($Admin->adminDetails($tableData->admin_id));
$username = $adminData->data->fname . ' ' . $adminData->data->lname;


/// dcument detaisl
$filePath = TICKET_DOCUMEN_PATH;
if($table == 'query_request'){
    $fileName = $tableData->attachment;
    $message = $tableData->message;
    $contact = $tableData->contact;
}else if($table == 'ticket_request'){
    $fileName = $tableData->attachment;
    $message = $tableData->message;
    $contact = $tableData->contact;
}

$fullFilePath = $filePath . $fileName;
$fileType = pathinfo($fullFilePath, PATHINFO_EXTENSION);


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
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin container-fluid -->
                <div class="container-fluid">

                    <div class="card-body shadow">
                        <form action="_config\form-submission\ticket-query-response-submit.php" enctype="multipart/form-data" method="post" id="query-ticket-response-form">
                            <div class="row d-flex text-center">
                                <div class="col-md-7">
                                    <div class="row d-flex">
                                        <div class="col-md-4 form-group d-none">
                                            <input type="text" class="med-input" id="req-id" name="req-id" value="<?= $tableData->id ?>" required readonly>
                                        </div>
                                        <div class="col-md-4 form-group d-none">
                                            <input type="text" class=" med-input" id="user-id" name="user-id" value="<?= $tableData->admin_id ?>" required readonly>
                                        </div>
                                        <div class="col-md-4 form-group d-none">
                                            <input type="text" class=" med-input" id="table-name" name="table-name" value="<?= $tableName ?>" required readonly>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div class="col-md-6 form-group">
                                            <input type="text" class="med-input" id="ticket-no" name="ticket-no" value="<?= $tableData->ticket_no ?>" required readonly>
                                            <label class="med-label" style="margin-left:10px;" for="ticket-no">Ticket No</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <input type="text" class=" med-input" id="user-name" name="user-name" value="<?= $username ?>" required readonly>
                                            <label class="med-label" style="margin-left:10px;" for="user">User</label>
                                        </div>
                                    </div>

                                    <div class="row d-flex">
                                        <div class="col-md-6 form-group">
                                            <input type="text" class=" med-input" id="msg-sender" name="msg-sender" value="<?= $tableData->name ?>" required readonly>
                                            <label class="med-label" style="margin-left:10px;" for="msg-sender">Sender</label>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <input type="text" class=" med-input" id="email" name="email" value="<?= $tableData->email ?>" required readonly>
                                            <label class="med-label" style="margin-left:10px;" for="emial">Email</label>
                                        </div>
                                    </div>

                                    <div class="row d-flex">
                                        <div class="col-md-6 form-group">
                                            <input type="text" class="med-input" id="msg-title" name="msg-title" value="<?= $tableData->title ?>" required readonly>
                                            <label class="med-label" style="margin-left:10px;" for="msg-title">Title</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <input type="text" class="med-input" id="contact-no" name="contact-no" value="<?= $contact ?>" required readonly>
                                            <label class="med-label" style="margin-left:10px;" for="contact-no">Contact No</label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <textarea class="med-input form-control" name="message" id="message" style="max-height: 105px; min-height: 105px;" required readonly><?= $message ?></textarea>
                                            <label class="med-label" style="margin-left: 10px;" for="message">Description</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <?php
                                    if (file_exists($fullFilePath)) {
                                        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                            // If the file is an image, generate an img tag
                                            echo '<div class="col-md-12 form-group card med-card" style="border: 1px solid #ced4da; padding: 1rem; height: 18rem; position: relative;">
                                                    <img src="' . $fullFilePath . '" alt="Image" style="max-width: 100%; max-height: 100%;">
                                                  </div>';
                                        } elseif ($fileType == 'pdf') {
                                            // If the file is a PDF, generate an iframe tag
                                            echo '<div class="col-md-12 form-group card med-card" style="border: 1px solid #ced4da; padding: 1rem; height: 18rem; position: relative;">
                                                    <iframe src="' . $fullFilePath . '" style="width: 100%; height: 100%;" frameborder="0"></iframe>
                                                  </div>';
                                        } else {
                                            echo '<div class="col-md-12 form-group card med-card" style="border: 1px solid #ced4da; padding: 1rem; height: 18rem; position: relative;">
                                                    <p>Unsupported file type.</p>
                                                  </div>';
                                        }
                                    } else {
                                        echo '<div class="col-md-12 form-group card med-card" style="border: 1px solid #ced4da; padding: 1rem; height: 18rem; position: relative;">
                                                <p>File not found.</p>
                                              </div>';
                                    }
                                    ?>
                                    <!-- <div class="col-md-12 form-group card med-card" style="border: 1px solid #ced4da; padding: 1rem; height: 18rem; position: relative;">
                                    </div> -->
                                    <label class="med-label mt-n4" style="margin-left: 10px;" for="document">Document</label>
                                    <input type="text" class="d-none med-input" id="document-data" name="document-data" value="<?= $fileName ?>" required readonly>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row text-center">
                                <div class="col-md-12 mt-2">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <textarea class="med-input" placeholder="" name="query-responce" id="query-responce" style="max-height: 90px; min-height: 90px;" required></textarea>
                                            <label class="med-label" style="margin-left:10px;" for="query-responce">Responce Message</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex justify-content-end mt-2">
                                    <button type="submit" name="ticket-query-response-submit" id="ticket-query-response-submit" class="btn btn-sm btn-primary">Send Responce</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <!-- End of container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

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