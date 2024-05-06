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

// ============ data fetch area =======================
if (isset($_GET['searchKey'])) {
    $searchData = $_GET['searchKey'];
    $showStockIn = $StockIn->stockInDataAsLike($searchData, $adminId);
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
// print_r($slicedData);


// =================== eof pagination ===========================
if (isset($_POST) && isset($_FILES['import-file'])) {
    $filename = $_FILES["import-file"]["tmp_name"];
    if ($_FILES["import-file"]["size"] > 0) {

        $UtilityFiles->purchaseImport($filename);
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
    <link href="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.css" rel="stylesheet">

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
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">

                        <div class="card-header booked_btn">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="col-md-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Number of Purchase :<?php echo $totalItem; ?></h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input class="cvx-inp" type="text" placeholder="Search..." name="purchase-data-search" id="purchase-data-search" style="outline: none;" aria-describedby="button-addon2" value="<?= isset($searchData) ? $searchData : ''; ?>">

                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon2" onclick="filterData()"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 d-flex justify-content-end">
                                    <button class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#staticBackdrop">Import </button>
                                    <a class="btn btn-sm btn-primary" href="<?= URL ?>stock-in.php">New + </a>
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
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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

    <!-- <script src="<?= PLUGIN_PATH ?>product-table/jquery.dataTables.js"></script> -->
    <script src="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <script>
        const stockDetails = (distBill, id) => {

            url = `ajax/stockInDetails.view.ajax.php?distBill=${distBill}`;

            $(".stockDetails").html(
                '<iframe width="99%" height="350px" frameborder="0" overflow-x: hidden; overflow-y: scroll; allowtransparency="true"  src="' +
                url + '"></iframe>');

        } //end of viewAndEdit

        // const showImportModal = () => {
        //     url = "ajax/import-purchase.ajax.php";
        //     let frameBody = document.getElementById("import-body");

        //     // Fetch content from the specified URL
        //     fetch(url)
        //         .then(response => response.text())
        //         .then(htmlContent => {
        //             // Inject fetched HTML content into the frameBody element
        //             frameBody.innerHTML = htmlContent;
        //         })
        //         .catch(error => {
        //             console.error('Error fetching content:', error);
        //         });
        // }

        // const purchaseImport = (e) => {

        //     // Serialize the form data
        //     var formData = new FormData($('#importPurchaseForm')[0]);
        //     console.log(formData);
        //     // Perform AJAX request
        //     $.ajax({
        //         type: 'POST',
        //         url: 'ajax/import-purchase.ajax.php',
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function(response) {
        //             // Handle success response here
        //             console.log('Success:', response);
        //         },
        //         error: function(xhr, status, error) {
        //             // Handle error response here
        //             console.error('Error:', error);
        //         }
        //     });
        // }

        // const purchaseImport = (e) => {
        //     // Prevent the default form submission behavior
        //     e.preventDefault();

        //     // Get all input elements within the form
        //     const inputs = document.querySelectorAll('#importPurchaseForm input');

        //     // Create a FormData object to serialize the form data
        //     var formData = new FormData();

        //     // Iterate over each input element and append its data to the FormData object
        //     inputs.forEach(input => {
        //         // Check if the input element is a file input
        //         if (input.type === 'file') {
        //             // Append each file separately if multiple files are allowed
        //             const files = input.files;
        //             for (let i = 0; i < files.length; i++) {
        //                 formData.append(input.name, files[i]);
        //             }
        //         } else {
        //             // For non-file inputs, append their name and value to the FormData object
        //             formData.append(input.name, input.value);
        //         }
        //     });

        //     // Perform AJAX request
        //     $.ajax({
        //         type: 'POST',
        //         url: 'ajax/import-purchase.ajax.php', // Specify your endpoint URL here
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function(response) {
        //             // Handle success response here
        //             console.log('Success:', response);
        //         },
        //         error: function(xhr, status, error) {
        //             // Handle error response here
        //             console.error('Error:', error);
        //         }
        //     });
        // }



        // document.getElementById("importPurchaseBtn").addEventListener("click", purchaseImport);



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

        //====================== url modification for data search ======================
        const filterData = () => {
            var value = document.getElementById('purchase-data-search').value;

            var currentURLWithoutQuery = window.location.origin + window.location.pathname;
            if (value.length > 2) {
                var newURL = `${currentURLWithoutQuery}?searchKey=${value}`;
                window.location.replace(newURL);
            } else {
                alert('Please Enter Minimum 3 Character!');
            }
        }
    </script>

</body>

</html>