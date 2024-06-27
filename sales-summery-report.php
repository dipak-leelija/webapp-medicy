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

    <title>Sales Summery Reports</title>

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
                                    <li class="breadcrumb-item active" aria-current="page">Sales Summery</li>
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
                                    <option value='pdf'>Download PDF File</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="shadow rounded" style="min-height: 70vh;">
                        <div class="row reportNavbar mx-0 rounded d-flex justify-content-start align-items-center">
                            <!-- filter range -->
                            <div class="col-md-2 bg-white me-3 selectDiv">
                                <select class="cvx-inp1 border-0 rounded p-1 w-100" name="day-filter" id="day-filter" onchange="dayFilter(this)">
                                    <option value="0" selected>Day Wise</option>
                                    <option value="1">Week Wise</option>
                                    <option value="2">Month Wise</option>
                                </select>
                                <label class="d-none" id="day-filter-val">0</label>
                            </div>

                            <!-- filter date range -->
                            <div class="col-md-2 bg-white me-3 selectDiv" id="date-range-select-div">
                                <select class="cvx-inp1 border-0 p-1 w-100" name="date-range" id="date-filter" onchange="dateRangeFilter(this)" required>
                                    <option value="" disabled selected>Select Date Range</option>
                                    <option value="T">Today</option>
                                    <option value="Y">Yesterday</option>
                                    <option value="LW">Last 7 Days</option>
                                    <option value="LM">Last 30 Days</option>
                                    <option value="LQ">Last 90 Days</option>
                                    <option value="CFY">Current Fiscal Year</option>
                                    <option value="PFY">Previous Fiscal Year</option>
                                    <option value="SDR">Select Date Range</option>
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
                                <select class="cvx-inp1 border-0 p-1 w-100" name="category-filter" id="category-filter" onchange="categoryFilterSelect(this)">
                                    <option value="" disabled selected>Filter By</option>
                                    <option value="ICAT">Item Category</option>
                                    <option value="PM">Payment Mode</option>
                                    <option value="STF">Staff</option>
                                </select>
                                <label class="d-none" id="filter-by-val"></label>
                            </div>

                            <!-- product category list -->
                            <div class="dropdown d-none col-md-2 bg-white me-3 selectDiv" id="prod-category-select-div">
                                <button class="btn cvx-inp1 dropdown-toggle bg-white w-100 p-1 border-0" type="button" id="prod-category" name="prod-category" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Select Item Category
                                </button>
                                <ul class="dropdown-menu item-category-select-checkbox-menu allow-focus border-0 shadow" aria-labelledby="dropdownMenu1">
                                    <li>
                                        <label><input class="activeCheckedBox" type="checkbox" id="ac-chkBx" value="AC" onclick="toggleCheckboxes1(this)">All Category</label>
                                    </li>
                                    <?php
                                    if (!empty($prodCategoryData)) {
                                        foreach ($prodCategoryData as $categoryData) {
                                            echo '<li><label><input type="checkbox" value="' . $categoryData->id . '" id="' . $categoryData->name . '" onclick="toggleCheckboxes1(this)"> ' . $categoryData->name . ' </label></li>';
                                        }
                                    }
                                    ?>
                                </ul>
                                <label class="d-none" id="filter-by-prod-categoty-id-val"></label>
                                <label class="d-none" id="filter-by-prod-categoty-name"></label>
                            </div>


                            <!-- filter payment mode -->

                            <div class="dropdown d-none col-md-2 bg-white me-3 selectDiv" id="payment-mode-div">
                                <button class="btn cvx-inp1 btn-default dropdown-toggle p-1" type="button" id="payment-mode" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="caret" id="payment-mode-select-span">Select Payment Mode</span>
                                </button>
                                <ul class="dropdown-menu payment-mode-checkbox-menu allow-focus border-0 shadow" aria-labelledby="dropdownMenu1">
                                    <li>
                                        <label><input class="activeCheckedBox" id="apm-chkBx" type="checkbox" value="APM" onclick="toggleCheckboxes2(this)">All Payment Mode</label>
                                    </li>

                                    <li>
                                        <label><input id="csh-chkBx" type="checkbox" value="Cash" onclick="toggleCheckboxes2(this)">Cash</label>
                                    </li>

                                    <li>
                                        <label>
                                            <input id="crdt-chkBx" type="checkbox" value="Credit" onclick="toggleCheckboxes2(this)">Credit
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input id="upi-chkBx" type="checkbox" value="UPI" onclick="toggleCheckboxes2(this)">UPI
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input id="crd-chkBx" type="checkbox" value="Card" onclick="toggleCheckboxes2(this)">Card
                                        </label>
                                    </li>
                                    <label class="d-none" id="filter-by-payment-mode-val"></label>
                                </ul>
                            </div>


                            <!-- filter on staff -->
                            <div class="dropdown d-none col-md-2" id="staff-filter-div">
                                <button class="btn dropdown-toggle bg-white w-100" type="button" id="staff-filter" name="staff-filter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Select Staff
                                </button>
                                <ul class="dropdown-menu staff-list-checkbox-menu allow-focus border-0 shadow" aria-labelledby="dropdownMenu1">
                                    <li>
                                        <label><input class="activeCheckedBox" type="checkbox" id="stf-chkBx" value="AS" onclick="toggleCheckboxes3(this)">All Staff</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox" id="adm-chkBx" value="<?= $adminId; ?>" name="<?= $adminName; ?>" onclick="toggleCheckboxes3(this)"><?= $adminName; ?> (admin)</label>
                                    </li>
                                    <?php
                                    if (!empty($employeeDetails)) {
                                        foreach ($employeeDetails as $empData) {
                                            echo '<li><label><input type="checkbox" onclick="toggleCheckboxes3(this)" id="' . $empData->emp_username . '" value="' . $empData->emp_id . '"> ' . $empData->emp_username . ' </label></li>';
                                        }
                                    }
                                    ?>
                                    <label class="d-none" id="all-stuff-name-data"><?php echo $allEmpNameString; ?></label>
                                    <label class="d-none" id="all-stuff-id-data"><?php echo $allUserIdString; ?></label>

                                    <label class="d-none" id="filter-by-staff-name"></label>
                                    <label class="d-none" id="filter-by-staff-id"></label>
                                </ul>
                            </div>


                            <!-- additional filter  -->
                            <div class="d-none col-md-2 mt-2" id="report-filter-div">
                                <select class="cvx-inp1 border-0 w-75 h-100" name="sales-report-on" id="sales-report-on" onchange="filterReportOn(this)">
                                    <option value="" selected>Select Report Filter</option>
                                    <option value="TS">Total Sales (&#8377)</option>
                                    <option value="TM">Total Margin (&#8377)</option>
                                    <option value="TD">Total Discount (&#8377)</option>
                                </select>
                                <label class="d-none" id="report-filter-val"></label>
                            </div>
                            <!--  -->


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