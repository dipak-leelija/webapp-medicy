<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';



$StockIn = new StockIn;
$StockInDetails = new StockInDetails;
$Distributor = new Distributor;


$stockInData = $StockIn->showStockIn($adminId);
$distributorList = json_decode($Distributor->showDistributor($adminId));


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
    <link rel="stylesheet" href="<?php echo PLUGIN_PATH ?>/bootstrap/5.3.3/dist/css/bootstrap.css">
    <link href="<?php echo CSS_PATH; ?>custom/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">
    <link href="<?php echo CSS_PATH; ?>date-picker/daterangepicker.css" rel="stylesheet">

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
                        <div class="col-md-9 pt-2 pl-4">
                            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="reports.php" class="text-decoration-none">Reports</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Purchase Report</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-3 d-flex text-center p-3 pt-0">
                            <div class="col-sm-4">
                            </div>
                            <div class="col-sm-8 bg-info bg-opacity-10">
                                <select class="focus-out c-inp p-1 w-100 text-primary bg-transparent" id="download-file-type" name="download-file-type" onchange="selectDownloadType(this)">
                                    <option value='' disabled selected>Download</option>
                                    <option value='exl'>Download Excel</option>
                                    <option value='csv'>Download CSV File</option>
                                    <!-- <option value='pdf'>Download PDF File</option> -->
                                </select>
                                <label class="d-none" id="download-checking">0</label>
                                <label class="d-none" id="selected-start-date"></label>
                                <label class="d-none" id="selected-end-date"></label>
                                <label class="d-none" id="report-generation-date-time-holder"></label>
                            </div>
                        </div>
                    </div>
                    <div class=" shadow rounded" style="min-height: 70vh;">
                        <div class="row reportNavbar mx-0 rounded d-flex justify-content-start align-items-center">

                            <div class="col-md-2 bg-white me-3 selectDiv d-flex text-center justify-content-between align-items-center p-1" id="date-range-select-div">
                                <span id="selected-month" style="flex-grow: 1;"></span>
                                <i class="fa fa-calendar"></i>
                                <input type="hidden" id="date-input" />
                            </div>

                            <!-- filter report type -->
                            <div class="col-md-2 bg-white me-3 selectDiv">
                                <select class="focus-out cvx-inp1 border-0 p-1 w-100" name="p-type" id="p-type" onchange="filterReportType(this)">
                                    <option value="" disabled selected>Report Type</option>
                                    <option value="P">Purchase</option>
                                    <option value="PR">Purchase Return</option>
                                </select>
                                <label class="d-none" id="selected-report-type"></label>
                            </div>

                            <!-- filter purchase type -->
                            <div class="col-md-2 bg-white me-3 selectDiv">
                                <select class="focus-out cvx-inp1 border-0 p-1 w-100" name="p-type" id="p-type" onchange="filterPurchaseType(this)">
                                    <option value="" disabled selected>Purchase Type</option>
                                    <option value="WG">With GST</option>
                                    <option value="NG">Without GST</option>
                                </select>
                                <label class="d-none" id="selected-purchse-type"></label>
                            </div>

                            <!-- find button on filter -->
                            <div class="col-md-2 searchFilterDiv">
                                <button type="button" id="search-filter" name="search-filter" class="focus-out btn btn-primary text-center btn-sm" onclick="filterSearch()">
                                    Go <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row text-end d-none font-weight-bold" id="total-purchase-amount-show-div">
                                <label for="" style="color: blue;">Total Purchase Amount:</label>
                                <label for="" id="total-purchase-amount"></label>
                            </div>
                            <div class="row">
                                <table class="table" id="gst-purchase-purchasereturn-table">
                                    <!-- dynamic table gose hear -->
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <div id="pagination-controls" class="d-flex justify-content-center mt-3"></div>
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

    <script src="<?php echo JS_PATH; ?>ajax.custom-lib.js"></script>
    <script src="<?php echo PLUGIN_PATH; ?>jquery/jquery.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>/bootstrap/5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH; ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH; ?>sb-admin-2.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script>

    <!-- date range jquery plugin -->
    <script type="text/javascript" src="<?= PLUGIN_PATH ?>jquery/date-picker/jquery.min.js"></script>
    <script type="text/javascript" src="<?= PLUGIN_PATH ?>jquery/date-picker/moment.min.js"></script>
    <script type="text/javascript" src="<?= PLUGIN_PATH ?>jquery/date-picker/daterangepicker.min.js"></script>

    <!-- plugin script for excel and pdf download -->
    <script src="<?= PLUGIN_PATH ?>report-export-script/excel-download-script/xlsx.full.min.js"></script>
    <!-- ExcelJS CDN -->
    <script src="<?= PLUGIN_PATH ?>report-export-script/excel-download-script/exceljs.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>report-export-script/excel-download-script/exceljs-4.3.0.min.js"></script>
    <!-- FileSaver.js CDN -->
    <script src="<?= PLUGIN_PATH ?>report-export-script/excel-download-script/FileSaver.min-2.5.0.js"></script>

    <!-- pdf cdn -->
    <script src="<?= PLUGIN_PATH ?>report-export-script/pdf-download-script/jspdf.umd.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>report-export-script/pdf-download-script/jspdf.plugin.autotable.min.js"></script>

    <!-- custom script for report filter -->
    <script src="<?php echo JS_PATH; ?>purchase-report-filter.js"></script>
</body>

</html>