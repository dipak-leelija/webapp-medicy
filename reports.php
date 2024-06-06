<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Reports</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH; ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="<?php echo CSS_PATH; ?>sb-admin-2.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH; ?>bootstrap 5/bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH; ?>custom/custom.css" rel="stylesheet">

    <script src="<?php echo JS_PATH; ?>ajax.custom-lib.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php
        // include ROOT_COMPONENT.'sidebar.php'; 
        ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT . 'report-topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-12">
                            <div class="shadow d-flex" style="min-height: 70vh;">
                                <div class="col-3">
                                    <div class="card-body">
                                        <a href="purchase-report.php">
                                            <button type="button" id="add-testType" class="btn btn-light w-100 border rounded text-center py-2 mb-3 mt-3">
                                                Purchae Report
                                            </button>
                                        </a>

                                        <button type="button" id="add-subTest" class="btn btn-light w-100 border rounded text-center py-2">
                                            Sales Report
                                        </button>
                                    </div>
                                </div>
                                <div class="vr mx-2"></div>
                                <div class="col-8">
                                    <div class="col-md-12 mt-3 d-flex p-3">
                                        <div class="col-sm-2 p-2 shadow m-1 text-center">
                                            blcok 1
                                        </div>
                                        <div class="col-sm-2 p-2 shadow m-1 text-center">
                                            blcok 2
                                        </div>
                                        <div class="col-sm-2 p-2 shadow m-1 text-center">
                                            blcok 3
                                        </div>
                                        <div class="col-sm-2 p-2 shadow m-1 text-center">
                                            blcok 4
                                        </div>
                                        <div class="col-sm-2 p-2 shadow m-1 text-center">
                                            blcok 5
                                        </div>
                                        <div class="col-sm-2 p-2 shadow m-1 text-center">
                                            blcok 6
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="<?php echo PLUGIN_PATH; ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH; ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH; ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH; ?>sb-admin-2.min.js"></script>

</body>

</html>