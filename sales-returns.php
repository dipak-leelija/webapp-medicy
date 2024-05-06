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

$SalesReturn   = new SalesReturn();
$Patients      = new Patients();
$stockOut      = new StockOut();
$currentStock  = new CurrentStock();
$Pagination      = new Pagination;



if (isset($_GET['searchKey'])) {
    $searchOn = $_GET['searchKey'];
    $salesReturns = $SalesReturn->salesReturnSearch($searchOn, $adminId);
    // print_r($salesReturns);
} else {
    $table1 = 'admin_id';
    $salesReturns = $SalesReturn->selectSalesReturn($table1, $adminId);
}

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



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sales Return || Medicy Healthcare</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/return-page.css">

    <!-- Data Table CSS  -->
    <!-- <link href="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->


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

                    <!-- Page Heading -->

                    <!-- Showing Sell Items  -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <div class="col-md-10">
                                <div class="input-group w-25">
                                    <input class="cvx-inp" type="text" placeholder="Search..." name="sales-return-search" id="sales-return-search" style="outline: none;" aria-describedby="button-addon2" value="<?= isset($searchOn) ? $searchOn : ''; ?>">

                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon2" onclick="filterSalesReturnSearch()"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex justify-content-end">
                                <a class="btn btn-sm btn-primary" href="sales-returns-items.php"> New <i class="fas fa-plus"></i></a>
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

                                                echo '<tr ' . $rowStyle . '>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $invoiceId . '</td>
                                                        <td hidden>' . $salesReturnId . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $patientName . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $item->items . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . date('d-m-Y', strtotime($item->bill_date)) . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . date('d-m-Y', strtotime($item->return_date)) . '</td>
                                                        <td data-toggle="modal" data-target="#viewReturnModal" onclick="viewReturnItem(' . $invoiceId . ',' . $salesReturnId . ')">' . $item->added_by . '</td>
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
            <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?>
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
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="<?= PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script> -->
    <!-- <script src="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script> -->
    <!-- <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script> -->

    <!-- Page level custom scripts -->

    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <script>
        const xmlhttp = new XMLHttpRequest();

        const viewReturnItem = (invoice, id) => {
            let url = `ajax/viewSalesReturn.ajax.php?invoice=${invoice}&id=${id}`;
            xmlhttp.open("GET", url, false);
            xmlhttp.send(null);
            document.getElementById('viewReturnModalBody').innerHTML = xmlhttp.responseText
        }


        const editSalesReturn = (invoiceId, salesReturnId) => {
            let editUrl = `sales-return-edit.php?invoice=${invoiceId}&salesReturnId=${salesReturnId}`;
            window.location.href = editUrl;
        };


        const cancelSalesReturn = (t) => {

            cancelId = t.id;

            swal({
                    title: "Are you sure?",
                    text: "Do you really cancel theis transaction?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        $.ajax({
                            url: "ajax/salesReturnCancle.ajax.php?",
                            type: "POST",
                            data: {
                                id: cancelId
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.includes('1')) {
                                    swal(
                                        "Canceled",
                                        "Transaction Has Been Canceled",
                                        "success"
                                    ).then(function() {
                                        $(t).closest("tr").css({
                                            "background-color": "red",
                                            "color": "white"
                                        });
                                        window.location.reload();
                                    });

                                } else {
                                    swal("Failed", "Transaction Deletion Failed!",
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

        // ====================================================================

        const filterSalesReturnSearch = () => {

            var searchFor = document.getElementById('sales-return-search').value;
            console.log(searchFor);
            var currentURLWithoutQuery = window.location.origin + window.location.pathname;
            if (searchFor.length > 0) {
                var newURL = `${currentURLWithoutQuery}?searchKey=${searchFor}`;
                window.location.replace(newURL);
            } else {
                alert('Please Enter Minimum 3 Character!');
            }
        }
    </script>


</body>

</html>