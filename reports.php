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
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

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
                    <div class="row mx-1">
                        <div class="col-12">
                            <div class="d-flex">
                                <div class="col-4 bg-white " style="min-height: 75vh;">
                                    <div class="p-3 pb-1">
                                        <h3>Reports</h3>
                                    </div>
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button class="focus-out accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                    <i class="fas fa-file-invoice-dollar" style="color: #085996;margin-right:8px"></i> Purchae Report
                                                </button>
                                            </h2>

                                            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <a href="purchase-report.php">Purchae Report</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingTwo">
                                                <button class="focus-out accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                                    <i class="fas fa-file-alt" style="color: #EB449F;margin-right:8px"></i>
                                                    Sales Report
                                                </button>
                                            </h2>
                                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <a href="sales-summery-report.php">Sales Summery Report</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="gst-sales-report.php">GST Sales Report</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingThree">
                                                <button class="focus-out accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                                    <i class="fas fa-percent" style="color: #009999;margin-right:8px"></i>Margin Report
                                                </button>
                                            </h2>
                                            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <a href="item-wise-margin-report.php">Item Wise Margin</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="bill-item-wise-margin-report.php">Bill-Item Wise Margin</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="purchase-analysis-mergin-report.php">Purchase Analysis Report</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingFour">
                                                <button class="focus-out accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                                    <i class="fas fa-box fa-fw fa-solid" style="color: #1affb2;margin-right:8px"></i>Stock Reports
                                                </button>
                                            </h2>
                                            <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <a href="expiry-report.php">Expiry report</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="item-batch-wise-stock-report.php">Item batch wise stock</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="annual-audit-report.php">Annual audit</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="annual-audit-report.php">Item wise closing stock</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingFive">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                                                    <i class="fas fa-list" style="color: #ff4da6;margin-right:8px"></i> Other Transaction Report
                                                </button>
                                            </h2>
                                            <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <a href="credit-transaction-report.php">Credit Transactional Report</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingSix">
                                                <button class="focus-out accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                                                    Inventory Report
                                                </button>
                                            </h2>
                                            <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <a href="">Inventory Report</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="">Inventory Report</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="">Inventory Report</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="">Inventory Report</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="">Inventory Report</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="">Inventory Report</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="">Inventory Report</a>
                                                </div>
                                                <div class="accordion-body">
                                                    <a href="">Inventory Report</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="vr mx-2"></div>
                                <div class="col-8">
                                    <div class="col-md-12 mt-3 me-5 d-flex p-3 flex-wrap">
                                        <a href="purchase-report.php" class="reportCard rounded m-2">
                                            <span><i class="fas fa-file-invoice-dollar" style="color: #085996;font-size:3rem"></i></span>
                                            <div class="ms-2">
                                                <p class="m-0 p-0">Purchase Report</p>
                                            </div>
                                        </a>
                                        <a href="gst-sales-report.php" class="reportCard rounded m-2">
                                            <span><i class="fas fa-file-alt" style="color: #B197FC;font-size:3rem"></i></span>
                                            <div class="ms-2">
                                                <p class="m-0 p-0">GST Sales Report</p>
                                            </div>
                                        </a>
                                        <a href="sales-summery-report.php" class="reportCard rounded m-2">
                                            <span><i class="fas fa-file-alt" style="color: #B197FC;font-size:3rem"></i></span>
                                            <div class="ms-2">
                                                <p class="m-0 p-0">Sales Summary Report</p>
                                            </div>
                                        </a>
                                        <a href="" class="reportCard rounded m-2">
                                            <span><i class="fas fa-file-alt" style="color: #B197FC;font-size:3rem"></i></span>
                                            <div class="ms-2">
                                                <p class="m-0 p-0">Inventory Report</p>
                                            </div>
                                        </a>
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
    <script src="<?php echo JS_PATH; ?>bootstrap-js-5/bootstrap-5-3-3.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH; ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH; ?>sb-admin-2.min.js"></script>

</body>

</html>