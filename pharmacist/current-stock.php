<?php
require_once '_config/sessionCheck.php'; //check admin loggedin or not
require_once '../php_control/currentStock.class.php';
require_once '../php_control/manufacturer.class.php';
require_once '../php_control/distributor.class.php';
require_once '../php_control/measureOfUnit.class.php';
require_once '../php_control/products.class.php';
require_once '../php_control/productsImages.class.php';

$page = "current-stock";

// INITILIZATION OF CLASSES
$CurrentStock   = new CurrentStock();
$Products       = new Products();
$Distributor    = new Distributor();
$ProductImages  = new ProductImages();
$Manufacturer   = new Manufacturer();

$showCurrentStock = $CurrentStock->showCurrentStock();


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Current Stock - Medicy Health Care</title>

    <!-- Custom fonts for this template -->
    <link href="../assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="css/custom/current-stock.css" rel="stylesheet">

    <!-- Datatable Style CSS -->
    <link href="vendor/product-table/dataTables.bootstrap4.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include 'partials/sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'partials/topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <!-- <h1 class="h3 mb-2 text-gray-800">Current Stock</h1> -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 booked_btn">
                            <h6 class="m-0 font-weight-bold text-primary">Total Avilable Stock is :
                                <?php if ($showCurrentStock != NULL) {
                                    echo count($showCurrentStock);
                                } else {
                                    echo "No Stock";
                                } ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table " id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <!-- <th>ID</th> -->
                                            <th>Image</th>
                                            <th>Product Name</th>
                                            <th>Batch No</th>
                                            <th>Exp Date</th>
                                            <th>Qty.</th>
                                            <th>MRP</th>
                                            <th>L. Qty.</th>
                                            <th>L. MRP</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($showCurrentStock != NULL) {

                                            foreach ($showCurrentStock as $rowStock) {
                                                //print_r($rowStock);
                                                $currentStockId      = $rowStock['id'];
                                                //echo $currentStockId;
                                                $productId           = $rowStock['product_id'];
                                                //echo $productId;
                                                $image               = $ProductImages->showImageById($productId);
                                                //print_r($image);exit;
                                                $mainImage = 'medicy-default-product-image.jpg';
                                                $backImage = 'medicy-default-product-image.jpg';
                                                if ($image != NULL) {
                                                    // echo $mainImage;
                                                    if ($image[0]['image'] == NULL) {
                                                        $mainImage == 'medicy-default-product-image.jpg';
                                                    } else {
                                                        $mainImage = $image[0]['image'];
                                                    }

                                                    if ($image[0]['back_image'] == NULL) {
                                                        $backImage == 'medicy-default-product-image.jpg';
                                                    } else {
                                                        $backImage = $image[0]['back_image'];
                                                    }
                                                }
                                                $expDate              = $rowStock['exp_date'];
                                                $distributorId        = $rowStock['distributor_id'];
                                                $looselyCount         = $rowStock['loosely_count'];
                                                $looselyPrice         = $rowStock['loosely_price'];
                                                $weightage            = $rowStock['weightage'];

                                                $productUnit          = $rowStock['unit'];
                                                $productQty           = $rowStock['qty'];
                                                $productMRP           = $rowStock['mrp'];
                                                $batchNo              = $rowStock['batch_no'];
                                                $gst                  = $rowStock['gst'];

                                                //echo $productId;
                                                

                                                $currentProduct = $Products->showProductsById($productId);
                                                //echo "\n";
                                                //echo $currentProduct;

                                                $Manuf = $Manufacturer->showManufacturerById($currentProduct[0]['manufacturer_id']);
                                                // print_r($Manuf[0]['name']);exit;

                                                $showProducts = $Products->showProductsById($productId);
                                                foreach ($showProducts as $rowProducts) {
                                                    $productName = $rowProducts['name'];
                                                    $showDistributor = $Distributor->showDistributorById($distributorId);
                                                    foreach ($showDistributor as $rowDistributor) {
                                                        $distributorName = $rowDistributor['name'];

                                                        $bachElemId = 'batch-id'.$batchNo;

                                                        echo "<tr>
                                                        <td class='align-middle d-dlex'>";
                                        ?>
                                                        <img class="p-img" src="../images/product-image/<?php echo $mainImage; ?>" alt="">
                                                        <img class="p-img ml-n4 position-absolute" src="../images/product-image/<?php echo $backImage; ?>" alt="">

                                        <?php echo "</td> 
                                                                <td class='align-middle'>" . $productName . "<br>
                                                                <small>" . $Manuf[0]['name'] . "</small>
                                                                </td>
                                                                <td class='align-middle' id='".$bachElemId."' >" . $batchNo . "</td>
                                                                <td class='align-middle'>" . $expDate . "</td>
                                                                <td class='align-middle'>" . $productQty . "</td>
                                                                <td class='align-middle'>" . $productMRP . "</td>
                                                                <td class='align-middle'>" . $looselyCount . "</td>
                                                                <td class='align-middle'>" . $looselyPrice . "</td>
                                                                
                                                                <td class='align-middle'>
                                                                    <a class='text-primary mr-2' id='" . $currentStockId . "' onclick='currentStockView(this.id)' data-toggle='modal' data-target='#currentStockModal'><i class='fas fa-edit'></i></a>
                                                                    <a class='text-danger' id='" . $currentStockId . "' onclick='customClick(this.id,\"".$bachElemId."\")'><i class='fas fa-trash'></i></a>
                                                                </td>
                                                            </tr>";
                                                    }
                                                }
                                            }
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once 'partials/footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- View currentStockModal Modal -->
    <div class="modal fade" id="currentStockModal" tabindex="-1" role="dialog" aria-labelledby="currentStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="currentStockModalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body current-stock-view">
                    <!-- Appointments Details Goes Here By Ajax -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload()">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of View currentStockModal Modal -->

    <!-- Logout Modal-->
    <?php require_once '_config/logoutModal.php'; ?>
    <!-- End Logout Modal-->

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <!-- <script src="js/custom-js.js"></script> -->
    <script src="js/ajax.custom-lib.js"></script>

    <!-- Sweet Alert Js  -->
    <script src="../js/sweetAlert.min.js"></script>

    <script>
        const customClick = (id, bachElemId) => {
            var bachElemId    = document.getElementById(bachElemId).innerHTML;
            //var currentStockId = id;

            //alert(id);
            //alert(bachElemId);

            swal({
                    title: "Are you sure?",
                    text: "Want to Delete This Data?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "ajax/currentStock.delete.ajax.php",
                            type: "POST",
                            data: {
                                Currentid: id,
                                bachElemId: bachElemId
                            },
                            success: function(response) {
                                if (response.includes('1')) {
                                    swal(
                                        "Deleted",
                                        "Manufacturer Has Been Deleted",
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




        const currentStockView = (currentStockId) => {
            // alert(appointmentTableID);
            let url = "ajax/currentStock.view.ajax.php?currentStockId=" + currentStockId;
            $(".current-stock-view").html(
                '<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');

        } // end of currentStockView function
    </script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script src="vendor/product-table/jquery.dataTables.js"></script>
    <script src="vendor/product-table/dataTables.bootstrap4.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>