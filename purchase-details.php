<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'pagination.class.php';


$page = "purchase-details";

//objects Initilization
$Distributor        = new Distributor();
$StockIn            = new StockIn();
$UtilityFiles       = new UtilityFiles;
$Pagination         = new Pagination;

$showDistributor       = $Distributor->showDistributor();


/// ======== SEARCH FILTER ACTION AREA ========
$searchVal = '';
$match = '';
$startDate = '';
$endDate = '';
$payment = '';


if (isset($_GET['search']) || isset($_GET['dateFilterStart']) || isset($_GET['dateFilterEnd']) || isset($_GET['paymentMode'])) {

    if(isset($_GET['search'])){
        $searchVal = $match = $_GET['search'];
    }
    
    if(isset($_GET['dateFilterStart'])){
        $startDate = $_GET['dateFilterStart'];
        $endDate = $_GET['dateFilterEnd'];
    }

    if(isset($_GET['paymentMode'])){
        $payment = $_GET['paymentMode'];
    }

    $showStockIn = json_decode($StockIn->stockInSearch($searchVal, $startDate, $endDate, $payment, $adminId));
    if($showStockIn->status){
        $showStockIn = $showStockIn->data;
    }else{
        $showStockIn = [];
    }

    // $soldItems = json_decode($StockOut->stockOutSearch($searchOn, $startDate, $endDate, $payment, $adminId));
    // $soldItems = $soldItems->data;

} else {
    $showStockIn = $StockIn->showStockInDecendingOrder($adminId);
    // print_r($showStockIn);
    if ($showStockIn != null) {
        $StockInId = $showStockIn[0]['id'];
    }
}


// print_r($showStockIn);
// ===================== pagination area =========================
$slicedData = '';
if (!empty($showStockIn)) {
    if (is_array($showStockIn)) {
        $response = json_decode($Pagination->arrayPagination($showStockIn));

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
// print_r($totalItem);


// =================== eof pagination ===========================
if (isset($_POST) && isset($_FILES['import-file'])) {
    $filename = $_FILES["import-file"]["tmp_name"];
    if ($_FILES["import-file"]["size"] > 0) {

        $UtilityFiles->purchaseImport($filename, $ADMINID);
    }
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

    <title>Medicy Items</title>

    <!-- Custom fonts for this template -->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- <link rel="stylesheet" href="../css/font-awesome-6.1.1-pro.css"> -->

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>main.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sb-admin-2.css" rel="stylesheet">

    <!-- Datatable Style CSS -->
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
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">

                        <div class="card-header booked_btn">
                            <!-- <div class="col-12 p-2"> -->
                                <div class="row mt-2 p-2">
                                    <!-- <div class="col-3 col-md-3"> -->
                                        <h6 class="m-0 font-weight-bold text-primary">Number of Purchase :&nbsp;<?php echo $totalItem; ?></h6>
                                    <!-- </div> -->
                                </div>
                                <!-- data search filter -->
                                <div class="row mt-2 p-2">
                                    <div class="col-3 col-md-4">
                                        <div class="input-group">
                                            <input class="cvx-inp" type="text" placeholder="Search..." name="data-search" id="data-search" style="outline: none;" aria-describedby="button-addon2" value="<?= isset($match) ? $match : ''; ?>" autocomplete="off">

                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon2" onclick="pharmacySearchFilter1()"><i class="fas fa-search"></i></button>
                                            </div>

                                            <button class="btn btn-sm btn-outline-primary shadow-none input-group-append" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                    <!-- date filter -->
                                    <div class="col-3 col-md-3 d-flex">
                                        <select class="input-group cvx-inp1" name="added_on" id="added_on" onchange="pharmacySearchFilter1()">
                                            <option value="" disabled selected>Select purchase date</option>
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
                                    <!-- payment mode filter -->
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
                                    <!-- add and import button seccession -->
                                    <div class="col-3 col-md-2 d-flex justify-content-end">
                                        <button class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#staticBackdrop">Import </button>
                                        <a class="btn btn-sm btn-primary" href="<?= URL ?>stock-in.php">New + </a>
                                    </div>
                                </div>
                            <!-- </div> -->
                        </div>


                        <!-- date picker div -->
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
                                <table class="table table-sm" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Dist. Bill No</th>
                                            <th>Dist. Name</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Payment Mode</th>
                                            <th class="d-flex justify-content-around">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($totalItem > 0) {
                                            // $StockInId = $slicedData[0]['id'];
                                            // $id = $slicedData[0]->id;
                                            // $slNo = $id - $StockInId;
                                            $slNo = 0;
                                            foreach ($slicedData as $stockIn) {
                                                $distributor = json_decode($Distributor->showDistributorById($stockIn->distributor_id));
                                                if ($distributor->status == 1) {
                                                    $fetchedDistributor = $distributor->data;
                                                    $distName = $fetchedDistributor->name;
                                                } else {
                                                    $distName = '';
                                                }

                                                $slNo++;

                                        ?>

                                                <tr>
                                                    <td onclick="stockDetails('<?php echo $stockIn->distributor_bill ?>','<?php echo $stockIn->id ?>', )" data-toggle="modal" data-target="#exampleModal"><?php echo $slNo ?>
                                                    </td>

                                                    <td onclick="stockDetails('<?php echo $stockIn->distributor_bill ?>','<?php echo $stockIn->id ?>' )" data-toggle="modal" data-target="#exampleModal"><?php echo $stockIn->distributor_bill ?>
                                                    </td>

                                                    <td onclick="stockDetails('<?php echo $stockIn->distributor_bill ?>','<?php echo $stockIn->id ?>' )" data-toggle="modal" data-target="#exampleModal"><?= $distName ?>
                                                    </td>

                                                    <td onclick="stockDetails('<?php echo $stockIn->distributor_bill ?>','<?php echo $stockIn->id ?>' )" data-toggle="modal" data-target="#exampleModal"><?php echo $stockIn->bill_date ?>
                                                    </td>

                                                    <td onclick="stockDetails('<?php echo $stockIn->distributor_bill ?>','<?php echo $stockIn->id ?>' )" data-toggle="modal" data-target="#exampleModal"><?php echo $stockIn->amount ?>
                                                    </td>

                                                    <td onclick="stockDetails('<?php echo $stockIn->distributor_bill ?>','<?php echo $stockIn->id ?>' )" data-toggle="modal" data-target="#exampleModal"><?php echo $stockIn->payment_mode ?>
                                                    </td>

                                                    <td class="d-flex justify-content-around align-middle">
                                                        <a class="text-primary pe-auto" role="button" onclick="stockDetails('<?php echo $stockIn->distributor_bill ?>','<?php echo $stockIn->id ?>')" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-eye"></i>
                                                        </a>
                                                        <a class="text-primary" id="<?php echo $stockIn->distributor_bill ?>" href="stock-in-edit.php?edit=<?php echo $stockIn->distributor_bill ?>&editId=<?php echo $stockIn->id ?>" role="button"><i class=" fas fa-edit"></i>
                                                        </a>
                                                        <a class="text-danger" role="button"><i class="fas fa-trash" id="<?php echo $stockIn->id ?>" onclick="deleteStock(this.id)"></i></a>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                <?= $paginationHTML ?>
                            </div>

                            <?php 
                            if($totalItem == 0){
                                echo '<div class="d-flex justify-content-center text-danger font-weight-bold">
                                        NO MATCH FOUND
                                    </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <!-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a> -->
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Purchase Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body stockDetails">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="staticBackdropLabel">Import CSV File of Your Purchase Records </span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="import-body">
                    <form id="importPurchaseForm" action="<?php CURRENT_URL ?>" method="post" enctype="multipart/form-data">
                        <div class="px-2">
                            <input type="file" class="form-control" id="chooseFile" name="import-file" accept=".csv">
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" id="importPurchaseBtn" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <!-- data search filter script -->
    <script src="<?= JS_PATH ?>pharmacy-stockIn-stokOut-searchFilter.js"></script>


    <script>
        const stockDetails = (distBill, id) => {

            url = `ajax/stockInDetails.view.ajax.php?distBill=${distBill}`;

            $(".stockDetails").html(
                '<iframe width="99%" height="350px" frameborder="0" overflow-x: hidden; overflow-y: scroll; allowtransparency="true"  src="' +
                url + '"></iframe>');

        } //end of viewAndEdit


        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';

        }

        //=================delete stock in delete=======================

        const deleteStock = (id) => {
            swal({
                    title: "Are you sure?",
                    text: "Want to Delete This Data?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        //alert(id);
                        $.ajax({
                            url: "ajax/stockin.delete.ajax.php",
                            type: "POST",
                            data: {
                                DeleteId: id,
                            },
                            success: function(response) {
                                // alert(response);
                                // console.log("final response", response);
                                if (response == true) {
                                    swal(
                                        "Deleted",
                                        "Stcok In data has been deleted",
                                        "success"
                                    ).then(function() {
                                        parent.location.reload();
                                    });

                                } else {
                                    swal("Failed", "Product Deletion Failed!",
                                        "error");
                                    $("#error-message").html("Deletion Field !!!")
                                        .slideDown();
                                    $("success-message").slideUp();
                                }

                            }
                        });
                    }
                    return false;
                });


        }
    </script>

</body>

</html>