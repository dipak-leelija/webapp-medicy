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


if (isset($_GET['ticket'])) {
    $ticketNo = url_dec($_GET['ticket']);

    // Assuming $Request->selectFromTables($ticketNo) returns a JSON string
    $ticketDetails = json_decode($Request->selectFromTables($ticketNo));
    if ($ticketDetails && isset($ticketDetails->data)) {
        $ticketData = $ticketDetails->data;
        $attachedFile = null; // Initialize attachedFile variable
        foreach ($ticketData as $details) {
            $attachedFile = $details->attachment;
        }

        $masterTable = $ticketDetails->masterTable;
        $responseTable = $ticketDetails->responseTable;

        if (isset($_SESSION['ADMIN']) && $_SESSION['ADMIN']) {
            $name = $userFname . ' ' . $adminLname;
            $contact = $adminContact;
        } else {
            $name = $userFname;
            $contact = $empContact;
        }

        $masterTableData = json_decode($Request->fetchMasterTicketData($masterTable, $ticketNo));
        if ($masterTableData && isset($masterTableData->data)) {
            $queryCreater = $masterTableData->data->name;
            $msgTitle = $masterTableData->data->title;

            // ============= image file path control ==============
            if ($attachedFile) {
                $fileName = $attachedFile;
                $filePath = TICKET_DOCUMENT_PATH;
                $fullFilePath = $filePath . $fileName;

                // You can now use $fullFilePath for displaying or further processing
            } else {
                echo 'No attached file found.';
            }
        } else {
            echo 'Error fetching master table data';
        }
    } else {
        echo 'Error decoding ticket details';
    }
} else {
    $contact = '';
    $queryCreater = '';
    $msgTitle = '';
    $ticketData = [];
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

                                <div id="home" class="tab-pane active">
                                    <div class="container mt-5">
                                        <div class="form-group d-none">
                                            <input type="text" class="form-control med-input" id="current-usr" name="current-usr" value="<?= $name; ?>" required readonly>
                                            <input type="text" class="form-control med-input" id="master-table-name" name="master-table-name" value="<?= $masterTable ?>" required readonly>
                                            <input type="text" class="form-control med-input" id="response-table-name" name="response-table-name" value="<?= $responseTable ?>" required readonly>
                                            <input type="text" class="form-control med-input" id="master-ticket-number" name="master-ticket-number" value="<?= $ticketNo ?>" required readonly>
                                            <input type="text" class="med-input" id="form-flag" name="form-flag" value="2" required readonly>
                                        </div>
                                        <div class="row d-flex">
                                            <div class="col-md-4 form-group">
                                                <input type="text" class="form-control med-input" id="prev-applicant-name" name="prev-applicant-name" maxlength="100" value="<?= $queryCreater; ?>" required readonly>
                                                <label class="med-label" for="applicant-name" style="margin-left:10px;">Previous Query Launcher</label>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <input type="email" class="form-control med-input" id="email" name="email" maxlength="100" value="<?= $userEmail ?>" required readonly>
                                                <label class="med-label" for="email1" style="margin-left:10px;">Email id</label>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <input type="number" class="form-control med-input" id="mobile-number" name="mobile-number" maxlength="10" max="9999999999" value="<?= $contact; ?>" required readonly>
                                                <label class="med-label" for="mobile-number1" style="margin-left:10px;">Contact Number</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- Left Side: Form Inputs -->
                                            <div class="col-md-7">
                                                <div class="row">
                                                    <div class="col-md-12 form-group">
                                                        <input type="text" class="form-control med-input" id="title" name="title" value="<?= $msgTitle; ?>" required autocomplete="off">
                                                        <label class="med-label" for="title" style="margin-left:10px;">Message Title</label>
                                                    </div>
                                                </div>
                                                <!-- Description -->
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="messaging-response-area">
                                                            <div class="message mb-4 p-3 border rounded bg-light" style="overflow-y:scroll; max-height: 15rem;">
                                                                <?php foreach ($ticketData as $msgData) : ?>
                                                                    <?php if (!empty($msgData->message) || !empty($msgData->response)) : ?>
                                                                        <div class="query text-start mb-2">
                                                                            <?php if (!empty($msgData->message)) : ?>
                                                                                <small><?php
                                                                                        $dateString = $msgData->added_on;
                                                                                        $dateTime = new DateTime($dateString);
                                                                                        $formattedDate = $dateTime->format('F j, Y H:i:s');
                                                                                        echo $formattedDate; ?></small>
                                                                                <div class="form-control w-50" readonly style="height: auto; width:auto; background-color:#ffd9b3; color:black;"><?php echo htmlentities($msgData->message); ?></div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <div class="response d-flex flex-column align-items-end">
                                                                            <?php if (!empty($msgData->response)) : ?>
                                                                                <small class="text-end">
                                                                                    <?php
                                                                                    $dateString = $msgData->added_on;
                                                                                    $dateTime = new DateTime($dateString);
                                                                                    $formattedDate = $dateTime->format('F j, Y H:i:s');
                                                                                    echo $formattedDate;
                                                                                    ?>
                                                                                </small>
                                                                                <div class="form-control mt-1 w-50" readonly style="height: auto; width:auto; background-color:#b3e6ff; color:black;"><?php echo htmlentities($msgData->response); ?></div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Side: Document Upload -->
                                            <div class="col-md-5">
                                                <div class="card med-card" style="border: 1px solid #ced4da; padding: 1rem; height: 18rem; position: relative;">
                                                    <div id="document-show-1" class="col-sm-11 card med-card"></div>
                                                </div>
                                                <label class="med-label text-primary mt-n4" for="fileInput1" style="margin-left:10px;">Document</label>
                                                <i class="fas fa-upload text-primary" id="upload-document1" style="position: absolute; left: 18rem; bottom: 3rem; cursor: pointer;" onclick="document.getElementById('fileInput1').click();"></i>
                                                <input type="file" class="d-none" name="fileInput1" id="fileInput1" value="<?= $fileName; ?>" onchange="takeInputFile(this, 'document-show-1')">
                                                <input type="text" class="d-none" id="db-file-data-holder" value="<?= $fileName; ?>">
                                            </div>
                                        </div>
                                        <!-- Re query and Submit Button -->
                                        <div class="row mt-3 d-flex">
                                            <div class="col-md-8 form-group">
                                                <input type="text" class="form-control med-input" id="re-query" name="re-query" required autocomplete="off">
                                                <label class="med-label" for="title" style="margin-left:10px;">Query</label>
                                            </div>
                                            <div class="col-md-4 d-flex justify-content-end">
                                                <col-sm-6>
                                                    <button class="btn btn-sm btn-primary mb-3 w-100" type="submit" name="regenerate-query" id="regenerate-query" onclick="reQuery()">Submit Query</button>
                                                </col-sm-6>

                                                <div class="col-sm-6">
                                                    <button class="btn btn-sm btn-primary mb-3 w-100" type="submit" name="back-to-ticket" id="back-to-ticket" onclick="backBtn(this)">Back</button>
                                                </div>
                                            </div>
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

    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

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



    <script>
        function displayFileFromDatabase(fileUrl, previewId) {

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const preview = document.getElementById(previewId);
                    const fileType = fileUrl.split('.').pop().toLowerCase();
                    const contentType = xhr.getResponseHeader("Content-Type");
                    const blob = new Blob([xhr.response], {
                        type: contentType
                    });
                    const reader = new FileReader();
                    reader.onload = function() {
                        const base64data = reader.result;
                        if (fileType === 'pdf') {
                            preview.innerHTML = `<embed src="${base64data}" type="application/pdf" width="100%" height="100%">`;
                        } else if (fileType === 'jpg' || fileType === 'jpeg' || fileType === 'png') {
                            preview.innerHTML = `<img src="${base64data}" style="max-width: 100%; max-height: 12rem;">`;
                        } else {
                            preview.innerHTML = `<p>Unsupported file format</p>`;
                        }
                    };
                    reader.readAsDataURL(blob);
                }
            };
            xhr.open('GET', fileUrl, true);
            xhr.responseType = 'arraybuffer';
            xhr.send();
        }

        // PHP code to embed JavaScript
        <?php if (!empty($fileName)) : ?>
            console.log(<?php $attachedFile; ?>);
            const fileUrl = <?php echo json_encode($fullFilePath); ?>;
            displayFileFromDatabase(fileUrl, 'document-show-1');
        <?php endif; ?>
    </script>


</body>

</html>