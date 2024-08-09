<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';

require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';



if (isset($_GET['labTypeId'])) {
    $showLabtypeId = url_dec($_GET['labTypeId']);
    echo $showLabtypeId;
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

    <title>Add Items</title>

    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Fontawsome Link -->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= CSS_PATH ?>font-awesome.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!--Custom CSS -->
    <link href="<?php echo CSS_PATH ?>custom/add-products.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

    <!-- <link href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" rel="stylesheet" /> -->

    <!-- sweetAlert link -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <!-- Add Product -->
                <div class="card shadow mb-4 h-100">
                    HELLO CHECK
                </div>
            </div>
            <!-- /end Add Product  -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->



    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
    <!-- <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script> -->

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="<?= JS_PATH ?>custom/add-products.js"></script>
    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>

</body>

</html>