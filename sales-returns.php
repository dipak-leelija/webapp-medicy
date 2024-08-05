<?php
$page = "sales-returns";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'salesReturn.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'employee.class.php';

$SalesReturn   = new SalesReturn();
$Patients      = new Patients();
$stockOut      = new StockOut();
$currentStock  = new CurrentStock();
$Pagination      = new Pagination;
$Admin          = new Admin;
$Employees       = new Employees;

$empLists              = $Employees->employeesDisplay($adminId);

/// ======== SEARCH FILTER ACTION AREA ========
$searchVal = '';
$match = '';
$salesFrom = '';
$salesTo = '';
$returnFrom = '';
$returnTo = '';
$returnedBy = '';


if (isset($_GET['search']) || isset($_GET['dateFilterStart']) || isset($_GET['dateFilterEnd']) || isset($_GET['itemReturnStartDt']) || isset($_GET['itemReturnEndDt']) || isset($_GET['addedBy'])) {

    if (isset($_GET['search'])) {
        $searchVal = $match = $_GET['search'];
    }

    if (isset($_GET['dateFilterStart'])) {
        $salesFrom = $_GET['dateFilterStart'];
        $salesTo = $_GET['dateFilterEnd'];
    }

    if (isset($_GET['itemReturnStartDt'])) {
        $returnFrom = $_GET['itemReturnStartDt'];
        $returnTo = $_GET['itemReturnEndDt'];
    }


    if (isset($_GET['addedBy'])) {
        if(($_GET['addedBy']) == 'admin'){
            $returnedBy = $adminId;
        }else{
            $returnedBy = $_GET['addedBy'];
        }
    }

    $salesReturns = json_decode($SalesReturn->salesReturnSearchFilter($searchVal, $salesFrom, $salesTo, $returnFrom, $returnTo, $returnedBy,  $adminId));

    if ($salesReturns->status) {
        $salesReturns = $salesReturns->data;
    } else {
        $salesReturns = [];
    }
} else {
    $table1 = 'admin_id';
    $salesReturns = $SalesReturn->selectSalesReturn($table1, $adminId);
}

// print_r($salesReturns);
/// ======== EOF SEARCH FILTER =========


if (!empty($salesReturns)) {
    if (is_array($salesReturns)) {
        $response = json_decode($Pagination->arrayPagination($salesReturns));

        $paginationHTML = '';
        $totalItem = $slicedLabBills = $response->totalitem;

        if ($response->status == 1) {
            $slicedData = $response->items;
            $paginationHTML = $response->paginationHTML;
        }
    } else {
        $totalItem = 0;
    }
} else {
    $totalItem = 0;
    $paginationHTML = '';
}

// print_r($slicedData);

$form20Data;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sales Return - <?= $healthCareName ?></title>

    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/return-page.css">


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
                    <div class="row" style="z-index: 999;">
                        <div class="col-12">
                            <?php include ROOT_COMPONENT . "drugPermitDataAlert.php"; ?>
                        </div>
                    </div>
                    <!-- Page Heading -->

                    <!-- Showing Sell Items  -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <!-- search on invoice, patient name, contact number -->
                            <div class="col-10 d-flex">
                                <div class="col-md-3 col-md-3">
                                    <div class="input-group">
                                        <input class="cvx-inp" type="text" placeholder="Search..." name="data-search" id="data-search" style="outline: none;" aria-describedby="button-addon2" value="<?= isset($match) ? $match : ''; ?>" autocomplete="off">

                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon2" onclick="pharmacySearchFilter3()"><i class="fas fa-search"></i></button>
                                        </div>

                                        <button class="btn btn-sm btn-outline-primary shadow-none input-group-append" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>

                                <!-- sales return date filter -->
                                <div class="col-3 col-md-3 d-flex">
                                    <select class="input-group cvx-inp1" name="sales-return-on" id="sales-return-on" onchange="pharmacySearchFilter3()">
                                        <option value="" disabled selected>Return Date Duration</option>
                                        <option value="T">Today</option>
                                        <option value="Y">yesterday</option>
                                        <option value="LW">Last 7 Days</option>
                                        <option value="LM">Last 30 Days</option>
                                        <option value="LQ">Last 90 Days</option>
                                        <option value="CFY">Current Fiscal Year</option>
                                        <option value="PFY">Previous Fiscal Year</option>
                                        <option value="CR">Custom Range </option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary rounded-0 shadow-none input-group-append" type="button" id="filter-reset-2" onclick="resteUrl(this.id)" style="z-index: 100; background: white;"><i class="fas fa-times"></i></button>

                                    <label class="d-none" id="select-sales-return-start-date"><?php echo $returnFrom; ?></label>
                                    <label class="d-none" id="select-sales-return-end-date"><?php echo $returnTo; ?></label>
                                </div>

                                <div class="col-3 col-md-3 d-flex">
                                    <select class="input-group cvx-inp1" name="sales-return-processed-by" id="sales-return-processed-by" onchange="pharmacySearchFilter3()">
                                        <option value="" disabled selected>Filter by staff</option>
                                        <option value="admin">Admin</option>
                                        <?php
                                        foreach ($empLists as $emp) {
                                            echo '<option value="' . $emp['emp_id'] . '">' . $emp['emp_username'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary rounded-0 shadow-none input-group-append" type="button" id="filter-reset-3" onclick="resteUrl(this.id)" style="z-index: 100; background: white;"><i class="fas fa-times"></i></button>

                                    <label class="d-none" id="return-processed-by"><?php echo $returnedBy; ?></label>
                                </div>

                            </div>
                            <!-- add new -->
                            <div class="col-2">
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <a class="btn btn-sm btn-primary" href="sales-returns-items.php"> New <i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                       
                            <label class="d-none" id="date-range-control-flag">0</label>
                            <label class="d-none" id="url-control-flag">0</label>
                            <div class="dropdown-menu  p-2 row" id="dtPickerDiv" style="position: relative; background-color: rgba(255, 255, 255, 0.8);">
                                <div class=" col-md-12" style="margin-left: 15rem;">
                                    <div class="d-flex">
                                        <div class="dtPicker" style="margin-right: 1rem;">
                                            <label>Strat Date</label>
                                            <input type="date" id="from-date" name="from-date">
                                        </div>
                                        <div class="dtPicker" style="margin-right: 1rem;">
                                            <label>End Date</label>
                                            <input type="date" id="to-date" name="to-date">
                                        </div>
                                        <div class="dtPicker">
                                            <button class="btn btn-sm btn-primary" onclick="pharmacySearchFilter3('from-date','to-date')">Find</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        

                        

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="dataTable" style="width: 100%;">
                                    <thead class="thead-white bg-primary text-light">
                                        <tr>
                                            <th>Invoice</th>
                                            <th hidden>Sales Return Id</th>
                                            <th>Patient Name</th>
                                            <th>Items</th>
                                            <th>Bill Date</th>
                                            <th>Return Date</th>
                                            <th>Entry By</th>
                                            <th>Amount</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataBody">
                                        <?php



                                        if ($totalItem > 0) {
                                            foreach ($slicedData as $item) {

                                                $invoiceId = $item->invoice_id;
                                                $salesReturnId = $item->id;
                                                $patientName = ($item->patient_id == "Cash Sales") ? "Cash Sales" : json_decode($Patients->patientsDisplayByPId($item->patient_id))->name;
                                                $rowStyle = ($item->status == 0) ? 'style="color: white; background-color: red;"' : '';

                                                $salesReturnAddedBy = $item->added_by;

                                                $adminData = json_decode($Admin->adminDetails($salesReturnAddedBy));
                                                // print_r($adminData);
                                                if ($adminData->status) {
                                                    $adminData = $adminData->data;
                                                    // print_r($adminData);
                                                    $salesReturnInitiatedBy = $adminData->fname . ' ' . $adminData->lname;
                                                }


                                                $empData = json_decode($Employees->employeeDetails($salesReturnAddedBy, $adminId));
                                                if ($empData->status) {
                                                    $empData = $empData->data;
                                                    // print_r($adminData);
                                                    $salesReturnInitiatedBy = $empData[0]->emp_name;
                                                }
                                                // echo $salesReturnInitiatedBy;


                                                echo '<tr ' . $rowStyle . '>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $invoiceId . '</td>
                                                        <td hidden>' . $salesReturnId . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $patientName . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $item->items . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . date('d-m-Y', strtotime($item->bill_date)) . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . date('d-m-Y', strtotime($item->return_date)) . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $salesReturnInitiatedBy . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $item->refund_amount . '</td>
                                                        <td>';

                                                if ($item->status != 0) {
                                                    echo '<a class="text-primary ml-4" onclick="editSalesReturn(' . $invoiceId . ',' . $salesReturnId . ')"><i class="fas fa-edit"></i></a>
                                                          <a class="text-danger ml-2" onclick="cancelSalesReturn(this)" id="' . $salesReturnId . '"><i class="fas fa-window-close"></i></a>';
                                                } else {
                                                    echo '</td>
                                                      </tr>';
                                                }
                                            }
                                        }else {
                                            echo "<tr class='text-center sales-table'><td colspan='7'>No Sales Return Found<tr>";

                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                <?= $paginationHTML ?>
                            </div>
                        </div>
                    </div>

                    <!-- End of Showing Sell Items  -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

        <!-- Return View Modal" -->
        <div class="modal fade" id="viewReturnModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Return Items</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="viewReturnModalBody">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal" -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <!-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a> -->
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>


    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>
    <!-- sales return control script -->
    <script src="<?= JS_PATH ?>sales-return.js"></script>

</body>

</html>