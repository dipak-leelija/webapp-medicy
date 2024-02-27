<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';


require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'currentStock.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';

$page = "current-stock";

// INITILIZATION OF CLASSES
$CurrentStock   = new CurrentStock();
$Products       = new Products();
$Distributor    = new Distributor();

$ProductImages  = new ProductImages();
$Manufacturer   = new Manufacturer();

$showCurrentStock = $CurrentStock->showCurrentStockbyAdminId($adminId);
// print_r($showCurrentStock);
if ($showCurrentStock != null) {
    $countCurrentStock = count($showCurrentStock);
}
// echo "$countCurrentStock";
$currentStockGroup = $CurrentStock->currentStockGroupbyPidOnAdmin($adminId);

// print_r($currentStockGroup);
// echo "<br><br>";

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
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?= CSS_PATH ?>custom/current-stock.css" rel="stylesheet">

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

                    <!-- Page Heading -->
                    <!-- <h1 class="h3 mb-2 text-gray-800">Current Stock</h1> -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <!-- <div class="card-header py-3 booked_btn">
                            <h6 class="m-0 font-weight-bold text-primary">Total Avilable Product is :
                                <?php if ($showCurrentStock != NULL) {
                                    echo count($showCurrentStock);
                                } else {
                                    echo "No Stock";
                                } ?>
                            </h6>
                        </div> -->
                        <div class="card-header py-3 booked_btn">
                            <h6 class="m-0 font-weight-bold text-primary">Total Avilable Product is :
                                <?php if ($showCurrentStock != NULL) {
                                    echo count($currentStockGroup);
                                } else {
                                    echo "No Stock";
                                } ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-light">
                                        <tr>
                                            <th hidden>ID</th>
                                            <th></th>
                                            <th>Product Name</th>
                                            <th>Stock Qty</th>
                                            <th>Loose Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($showCurrentStock != NULL) {

                                            foreach ($currentStockGroup as $rowStock) {

                                                $currentStockId      = $rowStock['id'];
                                                $productId           = $rowStock['product_id']; // fetch 
                                                $image               = json_decode($ProductImages->showImageById($productId));

                                                if ($image->status) {
                                                    $image = $image->data;
                                                    $mainImage = $image[0]->image;
                                                } else {
                                                    $image  =   json_decode($ProductImages->showImageByPrimay($productId, $adminId));

                                                    // print_r($image);
                                                    if ($image->status) {
                                                        $image = $image->data;
                                                        $mainImage = $image[0]->image;
                                                    }else{
                                                        $mainImage = 'default-product-image/medicy-default-product-image.jpg';
                                                    }
                                                }

                                                // =============== fetch each product data from current stock group by product id ========================

                                                $productData = json_decode($CurrentStock->showCurrentStockByPIdAndAdmin($productId, $adminId));

                                                if ($productData->status) {
                                                    $productData = $productData->data;
                                                    // echo "product data from current stock : "; print_r($productData); echo "<br><br>";
                                                } else {
                                                    echo "no product found!";
                                                }

                                                // =========== edit req flag key check ==========
                                                $prodCheck = json_decode($Products->productExistanceCheck($productId));
                                                if($prodCheck->status == 1){
                                                    $editReqFlag = 0;
                                                }else{
                                                    $editReqFlag = '';
                                                }

                                                //=========================================
                                                $checkProduct = json_decode($Products->productExistanceCheck($productId));
                                                if($checkProduct->status){
                                                    $flag = 1;
                                                }else{
                                                    $flag = '';
                                                }

                                                // ==== fetch product details from product table ====
                                                $showProducts = json_decode($Products->showProductsByIdOnUser($productId, $adminId, $flag));
                                                // print_r($showProducts);
                                                $showProducts = $showProducts->data;
                                                // echo "<br>";
                                                // print_r($showProducts);

                                                if (isset($showProducts[0]->manufacturer_id)) {
                                                    $manufId = $showProducts[0]->manufacturer_id;

                                                    $ManufData = json_decode($Manufacturer->showManufacturerById($manufId));

                                                    if ($ManufData->status) {
                                                        $ManufData = $ManufData->data;
                                                        $manufName =  $ManufData->name;
                                                    } else {
                                                        $manufName = "manfucaturer not found";
                                                    }
                                                } else {
                                                    $manufName = '';
                                                }


                                                // ==== fetch product manufacturer details ====


                                                $productName = $showProducts[0]->name;
                                                // $manufName =  $ManufData->name;

                                        ?>
                                                <tr>

                                                    <td class='align-middle d-dlex'>
                                                        <img class="p-img" src="<?= PROD_IMG_PATH ?><?php echo $mainImage; ?>" alt="">
                                                        <img class="p-img ml-n4 position-absolute" src="<?= PROD_IMG_PATH ?><?php echo $mainImage; ?>" alt="">

                                                    </td>

                                                    <td class='align-middle'><?php echo "$productName " ?> <br>
                                                        <small><?php echo " $manufName " ?></small>
                                                    </td>

                                                    <td class='align-middle'>
                                                        <?php

                                                        $productQty = 0;

                                                        foreach ($productData as $pData) {
                                                            $productQty = $productQty +     $pData->qty;
                                                        }
                                                        echo "$productQty";
                                                        ?>
                                                    </td>
                                                    <td class='align-middle'>
                                                        <?php
                                                        if ($productData != null) {
                                                            $looselyCount = 0;
                                                            foreach ($productData as $pData) {

                                                                $loose_C = $pData->loosely_count;
                                                                // print_r("hk".$loose_C);

                                                                if ($loose_C != 0) {
                                                                    // echo "helo";
                                                                    $looselyCount = $looselyCount +     $pData->loosely_count;
                                                                } else {
                                                                    $looselyCount = 0;
                                                                }
                                                            }
                                                        }

                                                        echo "$looselyCount"
                                                        ?>
                                                    </td>


                                                    <td class='align-middle'>
                                                        <span class="badge badge-primary cursor-pointer p-1" onclick='currentStockView("<?php echo $productId ?>", "<?php echo $editReqFlag ?>")' data-toggle='modal' data-target='#currentStockModal'>View <i class='fas fa-eye'></i></span>
                                                    </td>
                                                </tr>
                                        <?php
                                            $mainImage = '';
                                                
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


    <!-- View currentStockModal Modal -->
    <div class="modal fade" id="currentStockModal" tabindex="-1" role="dialog" aria-labelledby="currentStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="currentStockModalTitle">Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body current-stock-view">
                </div>
            </div>
        </div>
    </div>
    <!-- End of View currentStockModal Modal -->


    <!-- DeleteCurrentStockModal Modal -->
    <div class="modal fade" id="DeleteCurrentStockModal" tabindex="-1" role="dialog" aria-labelledby="DeleteCurrentStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DeleteCurrentStockModalTitle">Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body current-stock-view">
                    <!-- Appointments Details Goes Here By Ajax -->
                </div>
                <!-- <div class="modal-footer">
                     <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button> -->
                <!-- <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload()">Save
                        changes</button> 
                </div> -->
            </div>
        </div>
    </div>
    <!-- End of View DeleteCurrentStockModal Modal -->


    <!-- Page level custom scripts -->
    <!-- <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script> -->

    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>product-table/jquery.dataTables.js"></script>
    <script src="<?= PLUGIN_PATH ?>product-table/dataTables.bootstrap4.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <!-- <script src="js/custom-js.js"></script> -->
    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <script>
        const customDelete = (productId) => {
            alert(productId);
            let url = "ajax/currentStock.delete.ajax.php?currentStockProductId=" + productId;
            $(".current-stock-view").html(
                '<iframe width="99%" height="520px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');

        } // end of customDelete function

        //======================================= CURRENT STOCK VIEW ========================================

        const currentStockView = (productId, editReqFlag) => {
            // alert(productId);
            let url = `ajax/currentStock.view.ajax.php?currentStockId=${productId}&editReqFlag=${editReqFlag}`;
            $(".current-stock-view").html(
                '<iframe width="99%" height="520px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of currentStockView function
    </script>
</body>

</html>