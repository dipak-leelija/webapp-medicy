<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'stockInDetails.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'employee.class.php';



$StockOut = new StockOut;
$StockInDetails = new StockInDetails;
$Distributor = new Distributor;
$Products = new Products;
$Employees   = new Employees;


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

if ($employeeDetails->status) {
    $employeeDetails = $employeeDetails->data;
    // print_r($employeeDetails);
} else {
    $employeeDetails = array();
}




if (isset($_GET['reportGenerat'])) {
    echo 'generating report....';
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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

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
                        <div class="col-md-9 pt-3 pl-4">
                            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="reports.php"
                                            class="text-decoration-none">Reports</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Sales Summery</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-3 d-flex text-center p-2">
                            <div class="col-sm-4">
                                <button type="button" id="print-report" name="print-report"
                                    class="btn btn-primary w-100 border rounded text-center">
                                    Print
                                </button>
                            </div>
                            <div class="col-sm-8">
                                <select class="c-inp p-1 w-100" id="download-file-type" name="download-file-type"
                                    onchange="selectDownloadType(this)">
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
                            <!-- optional search filter by item name or composition -->
                            <!-- <div class="col-md-3 mt-2">
                                <div class="input-group h-100">
                                    <input class="cvx-inp border-0 w-75" type="text" placeholder="Search By Item Name/Composition" name="appointment-search" id="search-by-item-name" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>" />

                                    <button class="d-none btn btn-sm btn-outline-primary shadow-none input-group-append h-100" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                </div>
                            </div> -->
                            <!-- filter range -->
                            <div class="col-md-2 mt-2">
                                <select class="cvx-inp1 border-0 w-75 h-100" name="day-filter" id="day-filter"
                                    onchange="dayFilter(this)">
                                    <option value="DW" selected>Day Wise</option>
                                    <option value="WW">Week Wise</option>
                                    <option value="MW">Month Wise</option>
                                </select>
                                <label class="d-none" id="day-filter-val">DW</label>
                            </div>

                            <!-- filter date range -->
                            <div class="col-md-2 mt-2" id="date-range-select-div">
                                <select class="cvx-inp1 border-0 w-75 h-100" name="date-range" id="date-filter" onchange="dateRangeFilter(this)" required>
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

                            <div class="d-none col-md-2 mt-2" id="inputed-date-range-div">
                                <div class="input-group h-100">
                                    <input class="cvx-inp border-0 w-100" type="text" name="inputed-date-range"
                                        id="inputed-date-range" style="outline: none;" />

                                    <button class="btn btn-sm btn-outline-none shadow-none input-group-append"
                                        id="date-reset" style="margin-left: -2rem;" type="button"
                                        onclick="dateRangeReset(this.id)"><i class="fas fa-calendar"></i></button>
                                </div>
                            </div>

                            <!-- filter on category -->
                            <div class="col-md-2 mt-2" id="category-filter-div">
                                <select class="cvx-inp1 border-0 w-75 h-100" name="category-filter" id="category-filter"
                                    onchange="categoryFilterSelect(this)">
                                    <option value="" disabled selected>Filter By</option>
                                    <option value="ICAT">Item Category</option>
                                    <option value="PM">Payment Mode</option>
                                    <option value="STF">Staff</option>
                                </select>
                                <label class="d-none" id="filter-by-val"></label>
                            </div>

                            <!-- control list filter -->
                            <!-- filter purchase type -->
                            <div class="d-none col-md-2 mt-2" id="prod-category-select-div">
                                <select class="cvx-inp1 border-0 w-75 h-100" name="prod-category" id="prod-category">
                                    <option value="AC" disabled selected>All Category</option>
                                    <?php
                                    if (!empty($prodCategoryData)) {
                                        foreach ($prodCategoryData as $categoryData) {
                                            echo '<option value="' . $categoryData->id . '">' . $categoryData->name . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <label class="d-none" id="filter-by-prod-categoty-val"></label>
                            </div>

                            <!-- filter payment mode -->
                            <!-- <div class="d-none col-md-2 mt-2" id="payment-mode-div">
                                <select class="cvx-inp1 border-0 w-75 h-100" name="payment-mode" id="payment-mode">
                                    <option value="">jb</option>
                                    <option value="APM" disabled selected>All Payment Mode</option>
                                    <option value="CSH">Cash</option>
                                    <option value="CRDT">Credit</option>
                                    <option value="UPI">UPI</option>
                                    <option value="CRD">Card</option>
                                </select>
                                <label class="d-none" id="filter-by-payment-mode-val">APM</label>
                            </div> -->

                            <div class="dropdown d-none col-md-2 mt-2" id="payment-mode-div">
                                <button class="btn btn-default dropdown-toggle" type="button" id="payment-mode"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="caret">All Payment Mode</span>
                                </button>
                                <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">

                                    <li>
                                        <label>
                                            <input type="checkbox"> Cheese
                                        </label>
                                    </li>

                                    <li>
                                        <label>
                                            <input type="checkbox"> Pepperoni
                                        </label>
                                    </li>

                                    <li>
                                        <label>
                                            <input type="checkbox"> Peppers
                                        </label>
                                    </li>

                                </ul>
                            </div>

                            <!-- filter on staff -->
                            <div class="d-none col-md-2 mt-2" id="staff-filter-div">
                                <select class="cvx-inp1 border-0 w-75 h-100" name="staff-filter" id="staff-filter">
                                    <option value="AS" disabled selected>All Staff</option>
                                    <?php
                                    if (!empty($employeeDetails)) {
                                        foreach ($employeeDetails as $empData) {
                                    ?>
                                    <option value="<?= $empData->emp_id; ?>"><?= $empData->emp_name; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <label class="d-none" id="filter-by-staff-val"></label>
                            </div>

                            <!-- additional filter  -->
                            <div class="d-none col-md-2 mt-2" id="report-filter-div">
                                <select class="cvx-inp1 border-0 w-75 h-100" name="sales-report-on"
                                    id="sales-report-on">
                                    <option value="TS" selected>Total Sales (&#8377)</option>
                                    <option value="TM">Payment Mode (&#8377)</option>
                                    <option value="AVM">Average Margin (%)</option>
                                    <option value="TD">Total Discount (&#8377)</option>
                                    <option value="AD">Average Discount (%)</option>
                                </select>
                                <label class="d-none" id="report-filter-val"></label>
                            </div>
                            <!--  -->


                            <!-- find button on filter -->
                            <div class="col-md-2 mt-2" id="search-btn-div">
                                <button type="button" id="search-filter" name="find-report" class="btn btn-primary w-50 border rounded text-center mr-4" onclick="filterSearch()">
                                    Go <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row d-flex bg-primary bg-opacity-10 py-2">
                            <!-- date picker dive -->
                            <label class="d-none" id="date-range-control-flag">0</label>
                            <label class="d-none" id="url-control-flag">0</label>
                            <div class="dropdown-menu  p-2 row" id="dtPickerDiv"
                                style="display:none; position: relative; background-color: rgba(255, 255, 255, 0.8);">
                                <div class=" col-md-12">
                                    <div class="d-flex">
                                        <div class="dtPicker" style="margin-right: 1rem;">
                                            <label>Strat Date</label>
                                            <input type="date" id="from-date" name="from-date"
                                                onchange="selectStartDate(this)">
                                        </div>
                                        <div class="dtPicker" style="margin-right: 1rem;">
                                            <label>End Date</label>
                                            <input type="date" id="to-date" name="to-date"
                                                onchange="selectEndDate(this)">
                                        </div>
                                    </div>
                                </div>
                                <label class="d-none" id="selected-start-date"></label>
                                <label class="d-none" id="selected-end-date"></label>
                            </div>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Bill No.</th>
                                    <th>Entry Date</th>
                                    <th>Bill Date</th>
                                    <th>Distributor</th>
                                    <th class="text-end">Taxable</th>
                                    <th class="text-end">CESS</th>
                                    <th class="text-end">SGST</th>
                                    <th class="text-end">CGST</th>
                                    <th class="text-end">IGST</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($purchaseReport)) {
                                    if ($purchaseReport->status) {
                                        $purchaseReportData = $purchaseReport->data;
                                        $sl = 0;
                                        foreach ($purchaseReportData as $purchaseData) {
                                            // print_r($purchaseData);
                                            // echo "<br><br>";
                                            $sl++;
                                            $billNo = $purchaseData->distributor_bill;
                                            $entryDate = $purchaseData->added_on;
                                            $billDate = $purchaseData->bill_date;
                                            $distData = json_decode($Distributor->showDistributorById($purchaseData->distributor_id));
                                            if ($distData->status) {
                                                $distName = $distData->data->name;
                                            } else {
                                                $distName = '';
                                            }
                                            $taxable = floatval($purchaseData->amount) - floatval($purchaseData->gst);
                                            $totalGst = $purchaseData->gst;
                                            $cess = '';
                                            $cgst = floatval($totalGst) / 2;
                                            $cgst = $sgst = number_format($cgst, 2);
                                            $igst = '';
                                            $totalAmount = $purchaseData->amount;
                                ?>
                                <tr>
                                    <th><?= $sl; ?></th>
                                    <td><?= $billNo; ?></td>
                                    <td><?= $entryDate; ?></td>
                                    <td><?= $billDate; ?></td>
                                    <th><?= $distName; ?></th>
                                    <td class="text-end"><?= $taxable; ?></td>
                                    <td class="text-end"><?= $cess; ?></td>
                                    <td class="text-end"><?= $cgst; ?></td>
                                    <th class="text-end"><?= $sgst; ?></th>
                                    <td class="text-end"><?= $igst; ?></td>
                                    <td class="text-end"><?= $totalAmount; ?></td>
                                </tr>
                                <?php
                                        }
                                    } else {
                                        echo "no data found";
                                    }
                                }
                                ?>
                            </tbody>

                        </table>
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
    <script src="<?php echo JS_PATH; ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH; ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH; ?>sb-admin-2.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script>

    <!-- custom script for report filter -->
    <script src="<?php echo JS_PATH; ?>sales-report-control.js"></script>
</body>

</html>