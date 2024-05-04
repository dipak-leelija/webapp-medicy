<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';

$Subscription   = new Subscription;
$Utility        = new Utility;

$currentUrl = $Utility->currentUrl();
// Healthcare Addesss and details


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
    <!-- <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet"> -->

    <!-- Sweet alert plugins -->
    <!-- <script src="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css"></script> -->

    <!-- Custom styles for this template-->
    <!-- <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>upload-design.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>helth-care.css" rel="stylesheet">
    <link href="<?= PLUGIN_PATH ?>img-uv/img-uv.css" rel="stylesheet"> -->


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">


        <!-- New Section -->
        <div class="col">
            <div class="mt-4 mb-4">
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Plan</th>
                                        <th scope="col">Start</th>
                                        <th scope="col">Upto</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($allBills as $eachBill) {
                                        echo "
                                            <tr>
                                                <th scope='row'>$eachBill->plan</th>
                                                <td>$eachBill->start</td>
                                                <td>$eachBill->end</td>
                                                <td>$eachBill->paid</td>
                                                <td>$eachBill->status</td>
                                            </tr>
                                            ";
                                    } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <script>
        function validateFileType() {
            var fileName = document.getElementById("img-uv-input").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                document.getElementById("err-show").classList.add("d-none");
            } else {
                document.getElementById("err-show").classList.remove("d-none");
                // Show current image when error occurs
                document.querySelector('.img-uv-view').src = "<?= $healthCareLogo; ?>";
            }
        }
    </script>
    <!-- Bootstrap core JavaScript-->
    <!-- <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script> -->

    <!-- Core plugin JavaScript-->
    <!-- <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>img-uv/img-uv.js"></script> -->

    <!-- Sweet alert plugins -->
    <!-- <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script> -->

    <!-- Custom scripts for all pages-->
    <!-- <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script> -->


</body>

</html>