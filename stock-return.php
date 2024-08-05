<?php

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'stockReturn.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'employee.class.php';


$page = "stock-return";

//objects Initilization
// $Products           = new Products();
$Distributor        = new Distributor();
$StockReturn        = new StockReturn();
$Employees          = new Employees();
$Pagination         = new Pagination;
$Admin          = new Admin;
$Employees       = new Employees;

//function's called
$showDistributor       = $Distributor->showDistributor();

// print_r($stockReturnLists);
$empLists              = $Employees->employeesDisplay($adminId);


$flag = 0;
/// ======== SEARCH FILTER ACTION AREA ========
$searchVal = '';
$match = '';
$startDate = '';
$endDate = '';
$dataAddedBy = '';
$payment = '';


if (isset($_GET['search']) || isset($_GET['dateFilterStart']) || isset($_GET['dateFilterEnd']) || isset($_GET['addedBy']) || isset($_GET['paymentMode'])) {

    if (isset($_GET['search'])) {
        $searchVal = $match = $_GET['search'];
    }

    if (isset($_GET['dateFilterStart'])) {
        $startDate = $_GET['dateFilterStart'];
        $endDate = $_GET['dateFilterEnd'];
    }

    if (isset($_GET['addedBy'])) {
        $dataAddedBy = $_GET['addedBy'];
    }

    if (isset($_GET['paymentMode'])) {
        $payment = $_GET['paymentMode'];
    }


    $stockReturnLists = json_decode($StockReturn->stockReturnUpdatedFilter($searchVal, $startDate, $endDate, $dataAddedBy, $payment, $adminId));

    if (isset($stockReturnLists->status)) {
        $flag = 1;
    } else {
        $flag = 0;
    }
} else {
    $col = 'admin_id';
    $stockReturnLists = json_decode($StockReturn->stockReturnFilter($col, $adminId));
    if (isset($stockReturnLists->status)) {
        $flag = 1;
    } else {
        $flag = 0;
    }
}

// print_r($stockReturnLists);
// ===================== pagination area =========================
$slicedData = '';
if ($stockReturnLists->status == '1') {
    $stockReturnListsData = $stockReturnLists->data;
    if (is_array($stockReturnListsData)) {
        $response = json_decode($Pagination->arrayPagination($stockReturnListsData));

        $paginationHTML = '';
        $totalItem = $slicedData = $response->totalitem;

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Purchase Return - <?= $healthCareName ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/return-page.css" type="text/css">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.css" type="text/css">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css">
    
    <!-- Datatable Style CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
                    <div class="d-flex justify-content-between">

                    </div>
                    <!-- Main Card Start -->
                    <div class="card shadow mb-2">
                        <div class="card-body">
                            <?php
                            if ($flag == 1 && $totalItem > 0) {
                            ?>
                                <div class="row p-3">
                                    <div class="col-10 d-flex">
                                        <div class="col-md-3 col-sm-4">
                                            <div class="input-group">
                                                <input class="cvx-inp" type="text" placeholder="Search..." name="data-search" id="data-search" style="outline: none;" aria-describedby="button-addon2" value="<?= isset($match) ? $match : ''; ?>" autocomplete="off">

                                                <div class="input-group-append">
                                                    <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon2" onclick="pharmacySearchFilter2()"><i class="fas fa-search"></i></button>
                                                </div>

                                                <button class="btn btn-sm btn-outline-primary shadow-none input-group-append" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>



                                        <div class="col-md-3 col-sm-3 d-flex">
                                            <select class="input-group cvx-inp1" name="added_on" id="added_on" onchange="pharmacySearchFilter2()">
                                                <option value="" disabled selected>Filter by date</option>
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

                                            <label class="d-none" id="select-start-date"><?php echo $startDate; ?></label>
                                            <label class="d-none" id="select-end-date"><?php echo $endDate; ?></label>
                                        </div>


                                        <div class="col-md-3 col-sm-3 d-flex">
                                            <select class="input-group cvx-inp1" id="added_by" onchange="pharmacySearchFilter2()">
                                                <option value="" disabled selected>Select Staff
                                                </option>
                                                <?php
                                                foreach ($empLists as $emp) {
                                                    echo '<option value="' . $emp['emp_id'] . '">' . $emp['emp_username'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <button class="btn btn-sm btn-outline-primary rounded-0 shadow-none input-group-append" type="button" id="filter-reset-3" onclick="resteUrl(this.id)" style="z-index: 100; background: white;"><i class="fas fa-times"></i></button>

                                            <label class="d-none" id="select-added-by"><?php echo $dataAddedBy; ?></label>
                                        </div>

                                        <div class="col-3 col-md-3 d-flex">
                                            <select class="input-group cvx-inp1" name="payment_mode" id="payment_mode" onchange="pharmacySearchFilter2()">
                                                <option value="" disabled selected>Payment Mode</option>
                                                <option value="Credit">Credit</option>
                                                <option value="Cash">Cash</option>
                                                <option value="UPI">UPI</option>
                                                <option value="Paypal">Paypal</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                                <option value="Credit Card">Credit Card</option>
                                                <option value="Debit Card">Debit Card</option>
                                                <option value="Net Banking">Net Banking</option>
                                            </select>
                                            <button class="btn btn-sm btn-outline-primary rounded-0 shadow-none input-group-append" type="button" id="filter-reset-4" onclick="resteUrl(this.id)" style="z-index: 100; background: white;"><i class="fas fa-times"></i></button>

                                            <label class="d-none" id="select-payment-mode"><?php echo $payment; ?></label>
                                        </div>

                                    </div>

                                    <div class="col-2">
                                        <div class="col-md-12 text-right">
                                            <a class="btn btn-sm btn-primary " href="stock-return-item.php">New<i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>


                                    <label class="d-none" id="date-range-control-flag">0</label>
                                    <label class="d-none" id="url-control-flag">0</label>
                                    <div class="dropdown-menu  p-2 row" id="dtPickerDiv" style="display: none; position: relative; background-color: rgba(255, 255, 255, 0.8);">
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
                                                    <button class="btn btn-sm btn-primary" onclick="pharmacySearchFilter2()">Find</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ============================= date picker div ================================== -->

                                <div id="hiddenDiv" class="hidden" style="display: none;">
                                    <div id=" date-range-container">
                                        <label for="start-date">From Date:</label>
                                        <input type="date" id="from-date" name="from-date">
                                        <label for="end-date">To Date:</label>
                                        <input type="date" id="to-date" name="to-date">
                                        <button class="btn btn-sm btn-primary" id="added_on" value="CR" onclick="getDates(this.id, this.value)" style="height: 2rem;">Find</button>
                                    </div>
                                </div>

                                <!-- ================== eof date picker div ======================== -->
                                <div class="card-body">
                                    <div class="table-responsive" id="filter-table">

                                        <!-- table start -->
                                        <table class="table table-sm table-hover" id="<?php if ($totalItem > 10) {
                                                                                            echo "dataTable";
                                                                                        } ?>" width="100%" cellspacing="0">
                                            <thead class="bg-primary text-light">
                                                <tr>
                                                    <th>Return Id</th>
                                                    <th>Distributor</th>
                                                    <th>Return Date</th>
                                                    <th>Entry By</th>
                                                    <th>Payment Mode</th>
                                                    <th>Amount</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-data">
                                                <?php
                                                foreach ($slicedData as $row) {
                                                    $dist = json_decode($Distributor->showDistributorById($row->distributor_id));
                                                    $dist = $dist->data;

                                                    if (!empty($row->added_by)) {
                                                        $col = 'emp_id';
                                                        $empData = json_decode($Employees->selectEmpByCol($col, $row->added_by));

                                                        if($empData->status != '0'){
                                                            $empData = $empData->data;
                                                            $empName = $empData[0]->emp_name;
                                                        }else{
                                                            $empName = '';
                                                        }
                                                    } else {
                                                        $empName = '';
                                                    }

                                                    $stockReturnAddedBy = $row->added_by;

                                                    $adminData = json_decode($Admin->adminDetails($stockReturnAddedBy));
                                                    if ($adminData->status) {
                                                        $adminData = $adminData->data;
                                                        // print_r($adminData);
                                                        $stockReturnInitiatedBy = $adminData->fname . ' ' . $adminData->lname;
                                                    }


                                                    $empData = json_decode($Employees->employeeDetails($stockReturnAddedBy, $adminId));
                                                    if ($empData->status) {
                                                        $empData = $empData->data;
                                                        // print_r($adminData);
                                                        $stockReturnInitiatedBy = $empData[0]->emp_name;
                                                    }

                                                    // print_r($empData);
                                                    // if (is_array($dist) || ($dist instanceof Countable) || is_countable($dist))
                                                    // if (count($dist) > 0 && count($empData) > 0) {

                                                    $returnDate = date("d-m-Y", strtotime($row->return_date));


                                                    $check = '';
                                                    if ($row->status == '0') {
                                                        $check  = 'style="background-color:#ff0000; color:#ffffff"';
                                                    }
                                                    echo '<tr ' . $check . '>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')" >' . $row->id . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $dist->name . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $returnDate . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $stockReturnInitiatedBy . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $row->refund_mode . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItems(' . $row->id . ')">' . $row->refund_amount . '</td>
                                                        <td >
                                                            <a class="text-primary ml-4" id="edit-btn-' . $row->id . '" onclick="editReturnItem(' . $row->id . ', this)"><i class="fas fa-edit" ></i></a>
                                                            <a class="text-danger ml-2" id="cancel-btn-' . $row->id . '" onclick="cancelPurchaseReturn(' . $row->id . ', this)"><i class="fas fa-window-close" ></i></a>
                                                        </td>
                                                    </tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <!-- table end -->

                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <?= $paginationHTML ?>
                                    </div>
                                </div>
                        </div>
                    <?php } elseif ($flag == 1 && $totalItem == 0) { ?>
                        <div class="col-12 align-item-center">
                            <div class="row p-3">
                                <div class="col-10 d-flex">
                                    <div class="col-md-3 col-sm-4">
                                        <div class="input-group">
                                            <input class="cvx-inp" type="text" placeholder="Search..." name="data-search" id="data-search" style="outline: none;" aria-describedby="button-addon2" value="<?= isset($match) ? $match : ''; ?>" autocomplete="off">

                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon2" onclick="pharmacySearchFilter2()"><i class="fas fa-search"></i></button>
                                            </div>

                                            <button class="btn btn-sm btn-outline-primary shadow-none input-group-append" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>



                                    <div class="col-md-3 col-sm-3 d-flex">
                                        <select class="input-group cvx-inp1" name="added_on" id="added_on" onchange="pharmacySearchFilter2()">
                                            <option value="" disabled selected>Filter by date</option>
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

                                        <label class="d-none" id="select-start-date"><?php echo $startDate; ?></label>
                                        <label class="d-none" id="select-end-date"><?php echo $endDate; ?></label>
                                    </div>


                                    <div class="col-md-3 col-sm-3 d-flex">
                                        <select class="input-group cvx-inp1" id="added_by" onchange="pharmacySearchFilter2()">
                                            <option value="" disabled selected>Select Staff
                                            </option>
                                            <?php
                                            foreach ($empLists as $emp) {
                                                echo '<option value="' . $emp['emp_id'] . '">' . $emp['emp_username'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary rounded-0 shadow-none input-group-append" type="button" id="filter-reset-3" onclick="resteUrl(this.id)" style="z-index: 100; background: white;"><i class="fas fa-times"></i></button>

                                        <label class="d-none" id="select-added-by"><?php echo $dataAddedBy; ?></label>
                                    </div>

                                    <div class="col-3 col-md-3 d-flex">
                                        <select class="input-group cvx-inp1" name="payment_mode" id="payment_mode" onchange="pharmacySearchFilter2()">
                                            <option value="" disabled selected>Payment Mode</option>
                                            <option value="Credit">Credit</option>
                                            <option value="Cash">Cash</option>
                                            <option value="UPI">UPI</option>
                                            <option value="Paypal">Paypal</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Credit Card">Credit Card</option>
                                            <option value="Debit Card">Debit Card</option>
                                            <option value="Net Banking">Net Banking</option>
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary rounded-0 shadow-none input-group-append" type="button" id="filter-reset-4" onclick="resteUrl(this.id)" style="z-index: 100; background: white;"><i class="fas fa-times"></i></button>

                                        <label class="d-none" id="select-payment-mode"><?php echo $payment; ?></label>
                                    </div>

                                </div>

                                <div class="col-2">
                                    <div class="col-md-12 text-right">
                                        <a class="btn btn-sm btn-primary " href="stock-return-item.php">New<i class="fas fa-plus"></i></a>
                                    </div>
                                </div>


                                <label class="d-none" id="date-range-control-flag">0</label>
                                <label class="d-none" id="url-control-flag">0</label>
                                <div class="dropdown-menu  p-2 row" id="dtPickerDiv" style="display: none; position: relative; background-color: rgba(255, 255, 255, 0.8);">
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
                                                <button class="btn btn-sm btn-primary" onclick="pharmacySearchFilter2()">Find</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 p-3 mt-5 bg-white rounded d-flex justify-content-center"><p class="font-weight-bold text-danger" for="">NO DATA FOUND</p></div>
                            </div>
                        </div>
                    <?php } elseif ($flag == 0 && $totalItem == 0) { ?>
                        <div class="container p-3">
                            <div class="row justify-content-center">
                                <div class="card-body">
                                    <div class="m-auto" style="width: 15%;">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path style="fill:#98CCFD;" d="M24.405,20.771c0.646-1.188,0.949-2.402,0.906-3.631c-0.101-2.912-2.115-5.258-3.787-6.713    l-0.352-0.307L18.5,12.793V4.5h8.293l-2.32,2.32l0.379,0.353c1.6,1.493,3.506,3.803,3.506,6.327    C28.357,16.728,26.229,19.224,24.405,20.771z" />
                                                </g>
                                                <g>
                                                    <path style="fill:#4788C7;" d="M25.586,5l-1.1,1.1l-0.732,0.732l0.756,0.706c1.25,1.167,3.347,3.493,3.347,5.962    c0,2.132-0.997,3.935-2.195,5.332c0.119-0.565,0.169-1.136,0.149-1.71c-0.107-3.092-2.212-5.553-3.959-7.073l-0.704-0.612    l-0.66,0.66L19,11.586V5H25.586 M28,4H18v10l3.196-3.196c2.537,2.208,6.227,6.844,0.875,12.196c0,0,6.786-3.5,6.786-9.5    c0-2.749-2.079-5.213-3.664-6.693L28,4L28,4z" />
                                                </g>
                                            </g>
                                            <path style="fill:none;stroke:#4788C7;stroke-linecap:round;stroke-miterlimit:10;" d="M37.368,11.682L33.9,24.395  c-0.177,0.65-0.772,1.105-1.447,1.105H11.911" />
                                            <g>
                                                <circle style="fill:#DFF0FE;" cx="2" cy="3" r="1.5" />
                                                <path style="fill:#4788C7;" d="M2,2c0.551,0,1,0.449,1,1S2.551,4,2,4S1,3.551,1,3S1.449,2,2,2 M2,1C0.895,1,0,1.895,0,3   s0.895,2,2,2s2-0.895,2-2S3.105,1,2,1L2,1z" />
                                            </g>
                                            <g>
                                                <circle style="fill:#98CCFD;" cx="30.5" cy="36.5" r="2" />
                                                <path style="fill:#4788C7;" d="M30.5,35c0.827,0,1.5,0.673,1.5,1.5S31.327,38,30.5,38S29,37.327,29,36.5S29.673,35,30.5,35    M30.5,34c-1.381,0-2.5,1.119-2.5,2.5s1.119,2.5,2.5,2.5s2.5-1.119,2.5-2.5S31.881,34,30.5,34L30.5,34z" />
                                            </g>
                                            <g>
                                                <circle style="fill:#98CCFD;" cx="17.5" cy="36.5" r="2" />
                                                <path style="fill:#4788C7;" d="M17.5,35c0.827,0,1.5,0.673,1.5,1.5S18.327,38,17.5,38S16,37.327,16,36.5S16.673,35,17.5,35    M17.5,34c-1.381,0-2.5,1.119-2.5,2.5s1.119,2.5,2.5,2.5s2.5-1.119,2.5-2.5S18.881,34,17.5,34L17.5,34z" />
                                            </g>
                                            <path style="fill:none;stroke:#4788C7;stroke-linecap:round;stroke-miterlimit:10;" d="M2,1.5h2.119  c2.093,0,3.882,1.505,4.241,3.567l4.498,25.866c0.359,2.062,2.148,3.567,4.241,3.567H34.5" />
                                        </svg>
                                    </div>
                                    <h5 class="card-title text-center py-2">Return not Initiated yet!</h5>
                                    <div class="d-flex justify-content-cenetr justify-content-center">
                                        <a class="btn btn-primary" href="stock-return-item.php">Initiate Return</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <!-- Main Card End -->

                <!--=========================== Show Bill Items ===========================-->


            </div>
            <!-- /.container-fluid -->
            <!-- End of Main Content -->
            <!-- Footer -->
            <!-- <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->



        <!-- go to stock-return-control.js for function contorl -->
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
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End of Page Wrapper -->

    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>


    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <!-- custom script for stock return page -->
    <script src="<?= JS_PATH ?>stock-return-control.js"></script>

</body>

</html>