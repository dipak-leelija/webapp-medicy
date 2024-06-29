<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php'; // healtcare data
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'utility.class.php';



$StockOut = new StockOut;
$StockInDetails = new StockInDetails;
$Distributor = new Distributor;
$Products = new Products;
$Admin = new Admin;
$Employees   = new Employees;
$Utility     = new Utility;


// product category list
$prodCategory       = json_decode($Products->productCategory());
if ($prodCategory) {
    $prodCategoryData = $prodCategory->data;
} else {
    $prodCategoryData = [];
}


// emp list
$col = 'admin_id';
$employeeDetails = json_decode($Employees->selectEmpByCol($col, $adminId));


$empIdString = '';
$empNameString = '';
if ($employeeDetails->status) {
    $employeeDetails = $employeeDetails->data;
    foreach ($employeeDetails as $empDetails) {
        if ($empIdString == '') {
            $empIdString = $empIdString . $empDetails->emp_id;
        } else {
            $empIdString = $empIdString . ',' . $empDetails->emp_id;
        }

        if ($empNameString == '') {
            $empNameString = $empNameString . $empDetails->emp_username;
        } else {
            $empNameString = $empNameString . ',' . $empDetails->emp_username;
        }
    }
} else {
    $employeeDetails = array();
}

// user id string generation 
$allUserIdString = $adminId . ',' . $empIdString;;

// user name string generation depend on session
if ($_SESSION['ADMIN']) {
    $allEmpNameString = $username . ',' . $empNameString;
} else {
    $adminData = json_decode($Admin->adminDetails($adminId));
    $adminData = $adminData->data;
    $adminName = $adminData[0]->fname;
    $allEmpNameString = $adminData[0]->username . ',' . $empNameString;
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

    <title>Item Wise Margin Reports</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH; ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="<?php echo CSS_PATH; ?>sb-admin-2.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH; ?>bootstrap 5/bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH; ?>custom/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

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
                                    <li class="breadcrumb-item active" aria-current="page">Item Wise Margin</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-3 d-flex text-center p-3 pt-0">
                            <div class="col-sm-4">
                                <!-- <button type="button" id="print-report" name="print-report"
                                    class="btn btn-sm btn-primary border-0 text-center">
                                    Print
                                </button> -->
                            </div>
                            <div class="col-sm-8 bg-info bg-opacity-10">
                                <select class="c-inp p-1 w-100 text-primary bg-transparent" id="download-file-type" name="download-file-type" onchange="selectDownloadType(this)">
                                    <option value='' disabled selected>Download</option>
                                    <option value='exl'>Download Excel</option>
                                    <option value='csv'>Download CSV File</option>
                                    <!-- <option value='pdf'>Download PDF File</option> -->
                                </select>
                                <label class="d-none" id="download-checking">0</label>
                            </div>
                        </div>
                    </div>
                    <div class="shadow rounded" style="min-height: 70vh;">
                        <div class="row reportNavbar mx-0 rounded d-flex justify-content-start align-items-center">
                            <!-- filter date range -->
                            <div class="col-md-2 bg-white me-3 selectDiv" id="date-range-select-div">
                                <select class="cvx-inp1 border-0 p-1 w-100" name="date-range" id="date-filter" onchange="dateRangeFilter(this)" required>
                                    <option value="" disabled selected>Select Date</option>
                                    <option value="T">Today</option>
                                    <option value="Y">Yesterday</option>
                                    <option value="TM">This Month</option>
                                    <option value="PM">Previous Month</option>
                                    <option value="CFY">Current Fiscal Year</option>
                                    <option value="PFY">Previous Fiscal Year</option>
                                    <option value="CR">Csutom Range</option>
                                </select>
                                <label class="d-none" id="dt-rng-val"></label>
                            </div>

                            <div class="d-none col-md-2 bg-white me-3 selectDiv" id="inputed-date-range-div">
                                <div class="input-group w-100">
                                    <input class="cvx-inp border-0 w-100" type="text" name="inputed-date-range" id="inputed-date-range" style="outline: none;" />

                                    <button class="btn btn-sm btn-outline-none shadow-none input-group-append" id="date-reset" style="margin-left: -31px;" type="button" onclick="dateRangeReset(this.id)"><i class="fas fa-calendar"></i></button>
                                </div>
                            </div>

                            <!-- filter on category -->
                            <div class="col-md-2 bg-white me-3 selectDiv" id="category-filter-div">
                                <select class="cvx-inp1 border-0 p-1 w-100" name="category-filter" id="category-filter" onchange="reportTypeFilterSelect(this)">
                                    <option value="" disabled selected>Report Type</option>
                                    <option value="S">Sales</option>
                                    <option value="SR">Sales Return</option>
                                </select>
                                <label class="d-none" id="filter-by-val"></label>
                            </div>

                            <div class="col-md-2 bg-white me-3 selectDiv" id="date-range-select-div">
                                <div class="input-group">
                                    <input class="cvx-inp" type="text" placeholder="Appointment ID / Patient Id / Patient Name" name="appointment-search" id="search-by-id-name-contact" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>" /*onkeyup="filterAppointmentByValue()" * />

                                    <div class="input-group-append" id="appointment-search-filter-1">
                                        <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon" onclick="filterAppointmentByValue()"><i class="fas fa-search"></i></button>
                                    </div>

                                    <!-- <div class="d-none input-group-append" > -->
                                    <button class="btn btn-sm btn-outline-primary shadow-none input-group-append" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                    <!-- </div> -->
                                </div>
                                <label class="d-none" id="dt-rng-val"></label>
                            </div>

                            <!-- find button on filter -->
                            <div class="col-md-1 searchFilterDiv" id="search-btn-div">
                                <button type="button" id="search-filter" name="find-report" class="btn btn-primary btn-sm text-center" onclick="salesSummerySearch()">
                                    Go <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row d-flex">
                            <!-- date picker dive -->
                            <label class="d-none" id="date-range-control-flag">0</label>
                            <label class="d-none" id="url-control-flag">0</label>
                            <div class="dropdown-menu  p-2 row" id="dtPickerDiv" style="display:none;">
                                <div class=" col-md-12">
                                    <div class="d-flex">
                                        <div class="dtPicker" style="margin-right: 1rem;">
                                            <label>Strat Date</label>
                                            <input type="date" id="from-date" name="from-date" onchange="selectStartDate(this)">
                                        </div>
                                        <div class="dtPicker" style="margin-right: 1rem;">
                                            <label>End Date</label>
                                            <input type="date" id="to-date" name="to-date" onchange="selectEndDate(this)">
                                        </div>
                                    </div>
                                </div>
                                <label class="d-none" id="selected-start-date"></label>
                                <label class="d-none" id="selected-end-date"></label>
                            </div>
                        </div>


                        <!-- report table start -->
                        <table class="table" id="report-table">
                            <!-- dynamic table gose hear -->

                        </table>
                        <div class="col-md-12 text-center">
                            <div id="pagination"></div>
                        </div>

                    </div>
                    <!-- end of dynamic table creation -->
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
    <script src="<?php echo JS_PATH; ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH; ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH; ?>sb-admin-2.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script>

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
    <script src="<?php echo JS_PATH; ?>sales-report-control.js"></script>

    <!-- checkbox checked or unchecked  -->
    <script>
        function toggleCheckboxes(source) {
            const checkboxes = document.querySelectorAll('.checkbox-menu input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = source.checked;
                }
            });

            if (source.value == 'AC') {
                document.getElementById('prod-category').innerHTML = 'All Category';
            }
        }
    </script>
</body>

</html>