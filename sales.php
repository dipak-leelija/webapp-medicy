<?php
$page = "sales";
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . "dbconnect.php";
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . "encrypt.inc.php";
require_once CLASS_DIR . "stockOut.class.php";
require_once CLASS_DIR . "patients.class.php";
require_once CLASS_DIR . 'pagination.class.php';


// CLASS INTIATING 
$StockOut = new StockOut();
$Patients = new Patients();
$Pagination      = new Pagination;


/// ======== SEARCH FILTER ACTION AREA ========
$searchVal = '';
$match = '';
$startDate = '';
$endDate = '';
$payment = '';


if (isset($_GET['search']) || isset($_GET['dateFilterStart']) || isset($_GET['dateFilterEnd']) || isset($_GET['paymentMode'])) {

    if (isset($_GET['search'])) {
        $searchVal = $match = $_GET['search'];
    }

    if (isset($_GET['dateFilterStart'])) {
        $startDate = $_GET['dateFilterStart'];
        $endDate = $_GET['dateFilterEnd'];
    }

    if (isset($_GET['paymentMode'])) {
        $payment = $_GET['paymentMode'];
    }

    $soldItems = json_decode($StockOut->stockOutSearch($searchVal, $startDate, $endDate, $payment, $adminId));
    $soldItems = $soldItems->data;
} else {
    $soldItems = $StockOut->stockOutDisplay(strval($adminId));
}

/// ======== EOF SEARCH FILTER =========

if (!empty($soldItems)) {
    if (is_array($soldItems)) {
        $response = json_decode($Pagination->arrayPagination($soldItems));

        $paginationHTML = '';
        $totalItem = $slicedLabBills = $response->totalitem;

        if ($response->status == 1) {
            $slicedLabBills = $response->items;
            $paginationHTML = $response->paginationHTML;
        }
    } else {
        $totalItem = 0;
    }
} else {
    $totalItem = 0;
    $paginationHTML = '';
}
// print_r($slicedLabBills);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sales</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Default styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.css" rel="stylesheet">

    <!-- Custom styles for this Page-->
    <link rel="stylesheet" href="<?= CSS_PATH ?>sales.css">

    <!-- Data Table CSS  -->
    <!-- <link href="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.css" rel="stylesheet"> -->


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
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <!-- search on invoice, patient name, contact number -->
                            <div class="col-3 col-md-4">
                                <div class="input-group">
                                    <input class="cvx-inp" type="text" placeholder="Search..." name="data-search" id="data-search" style="outline: none;" aria-describedby="button-addon2" value="<?= isset($match) ? $match : ''; ?>" autocomplete="off">

                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon2" onclick="pharmacySearchFilter1()"><i class="fas fa-search"></i></button>
                                    </div>

                                    <button class="btn btn-sm btn-outline-primary shadow-none input-group-append" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                </div>
                            </div>

                            <!-- date filter select -->
                            <div class="col-3 col-md-3 d-flex">
                                <select class="input-group cvx-inp1" name="added_on" id="added_on" onchange="pharmacySearchFilter1()">
                                    <option value="" disabled selected>Select Duration</option>
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

                            <!-- payment mode filter select -->
                            <div class="col-3 col-md-3 d-flex">
                                <select class="input-group cvx-inp1" name="payment_mode" id="payment_mode" onchange="pharmacySearchFilter1()">
                                    <option value="" disabled selected>Payment Mode</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Credit">Credit</option>
                                    <option value="UPI">UPI</option>
                                    <option value="CARD">CARD</option>
                                </select>
                                <button class="btn btn-sm btn-outline-primary rounded-0 shadow-none input-group-append" type="button" id="filter-reset-3" onclick="resteUrl(this.id)" style="z-index: 100; background: white;"><i class="fas fa-times"></i></button>

                                <label class="d-none" id="select-payment-mode"><?php echo $payment; ?></label>
                            </div>

                            <div class="col-3 col-md-2 d-flex justify-content-end">
                                <a class="btn btn-sm btn-primary" href="new-sales.php"> New Sell <i class="fas fa-plus"></i></a>
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
                                        <button class="btn btn-sm btn-primary" onclick="pharmacySearchFilter1()">Find</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm " id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-light">
                                        <tr class="text-center">
                                            <th>Invoice</th>
                                            <th>Patient</th>
                                            <th>Bill Date</th>
                                            <th>Item</th>
                                            <th>Amount</th>
                                            <th>Payment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        if ($totalItem > 0) :
                                            foreach ($slicedLabBills  as $soldItem) {
                                                $invoice    = $soldItem->invoice_id;
                                                $patient    = $soldItem->customer_id;
                                                $billDate   = date_create($soldItem->bill_date);
                                                $billDate   = date_format($billDate, "d-m-Y");
                                                $billAmount = $soldItem->amount;
                                                $paymentMode = $soldItem->payment_mode;

                                                if ($patient != 'Cash Sales') {
                                                    $patientName = json_decode($Patients->patientsDisplayByPId($patient));

                                                    if ($patientName != null) {
                                                        $patientName = $patientName->name;
                                                    } else {
                                                        $patientName = "";
                                                    }
                                                } else {
                                                    $patientName = $patient;
                                                }

                                                echo "<tr class='text-center sales-table";
                                        ?>
                                                <?php
                                                $creditIcon = "";
                                                if ($paymentMode == "Credit") {
                                                    echo "text-danger";
                                                    $creditIcon = "<i class='ml-1 fas fa-exclamation-circle' data-toggle='tooltip' data-placement='top' title='This payment is due, Collect all the due payments.'></i>";
                                                }
                                                ?>
                                        <?php echo "'data-toggle='modal' data-target='#viewBillModal'>
                                                        <td onclick='viewBills(" . $invoice . ")'>" . $invoice . "</td>
                                                        <td onclick='viewBills(" . $invoice . ")'>" . $patientName . "</td>
                                                        <td onclick='viewBills(" . $invoice . ")'>" . $billDate . "</td>
                                                        <td onclick='viewBills(" . $invoice . ")'>" . $soldItem->items . "</td>
                                                        <td onclick='viewBills(" . $invoice . ")'>" . $billAmount . "</td>
                                                        <td onclick='viewBills(" . $invoice . ")'>" . $paymentMode, $creditIcon . "</td>
                                                        <td>
                                                        <a class='ml-2' href='update-sales.php?id=" . url_enc($invoice) . "'><i class='fas fa-edit'></i></a>
                                                        
                                                        <a class='ml-2' onclick='openPrint(this.href); return false;' href='" . URL . "invoices/print.php?name=sales&id=" . url_enc($invoice) . "'><i class='fas fa-print'></i></a>

                                                        <a class='ml-2' data-id=" . $invoice . "><i class='fab fa-whatsapp'></i></i></a>

                                                    </td>
                                                    </tr>";
                                            }
                                        endif;
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

    </div>
    <!-- End of Page Wrapper -->

    <!-- View Bill Modal -->

    <div class="modal fade" id="viewBillModal" tabindex="-1" aria-labelledby="viewBillModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewBillModalLabel">View Bill Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body viewBillModal">

                </div>
            </div>
        </div>
    </div>
    <!-- View Bill Modal -->



    <!-- Scroll to Top Button-->
    <!-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a> -->
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>



    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="<?= JS_PATH ?>pharmacy-stockIn-stokOut-searchFilter.js"></script>
    <!-- new tab for invoice print  -->
    <script src="<?php echo JS_PATH ?>/main.js"></script>

    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })

        const viewBills = (invoice) => {
            // alert(invoice);
            url = `ajax/viewBill.ajax.php?invoice=${invoice}`;
            $(".viewBillModal").html(
                '<iframe width="99%" height="340px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        }
    </script>
</body>

</html>