<?php

require_once __DIR__.'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not
require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'products.class.php';
require_once CLASS_DIR.'productsImages.class.php';
require_once CLASS_DIR.'pagination.class.php';
require_once ROOT_DIR . '_config/accessPermission.php';



$page = "products";

//Intitilizing Doctor class for fetching doctors
$Products       = new Products();
$Pagination     = new Pagination();
$ProductImages  = new ProductImages();



// Function INitilized 
$col = 'admin_id';

$result = $Pagination->productsWithPagination();
$allProducts    = $result['products'];
$totalPtoducts  = $result['totalPtoducts']
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
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/products.css">
    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT.'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin container-fluid -->
                <div class="container-fluid">

                    <!-- New Section -->
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Total Items:
                                        <?= $totalPtoducts ?>
                                    </h6>
                                    <a class="btn btn-sm btn-primary" href="add-products.php"><i
                                            class="fas fa-plus"></i> Add</a>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="d-flex justify-content-center">
                                    <div class="row card-div">

                                        <section>
                                            <div class="row ">

                                                <?php
                                                if ($allProducts != null) {
                                                    foreach ($allProducts as $item) {
                                                        
                                                        $image = $ProductImages->showImageById($item['product_id']);
                                                
                                                        if ($image != null) {
                                                            $imgData = $image[0]['image'];
                                                            if($imgData == ''){
                                                                $productImage = 'medicy-default-product-image.jpg';
                                                            }else{
                                                                $productImage = $imgData;
                                                            }
                                                        } else {
                                                            $productImage = 'medicy-default-product-image.jpg';
                                                        }

                                                        if ($item['dsc'] == null) {
                                                            $dsc = '';
                                                        } else {
                                                            $dsc = $item['dsc'].'...';
                                                        }
                                                        
                                                ?>

                                                <div class="item col-12 col-sm-6 col-md-3 " style="width: 100%;">
                                                    <div class="card  m-2">
                                                        <img src="<?php echo PROD_IMG_PATH?><?php echo $productImage ?>"
                                                            class="card-img-top" alt="...">
                                                        <div class="card-body">
                                                            <label><b><?php echo $item['name']; ?></b></label>
                                                            <p class="mb-0"><b><?php $item['name'] ?></b></p>
                                                            <small class="card-text mt-0"
                                                                style="text-align: justify;"><?php echo substr($dsc, 0, 65) ?>...</small>

                                                        </div>


                                                        <div class="row px-3 pb-2">
                                                            <div class="col-6">₹ <?php echo $item['mrp'] ?></div>
                                                            <div class="col-6 d-flex justify-content-end">
                                                                <button class="btn btn-sm border border-info"
                                                                    data-toggle="modal" data-target="#productModal"
                                                                    id="<?php echo $item['product_id'] ?>"
                                                                    onclick="viewItem(this.id)">View</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                    }
                                                } else {
                                                    echo "No Item Avilable";
                                                }

                                                ?>

                                            </div>
                                            <div class="d-flex justify-content-center mt-3">
                                                <nav aria-label="Page navigation">
                                                    <?= $result['paginationHTML'] ?>
                                                </nav>
                                            </div>
                                        </section>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- End of Wrapper -->

                    </div>
                    <!-- End of Container -->

                </div>
                <!-- End of container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once ROOT_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!--End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">View Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body productModal">
                    <!-- Product Details goes here by ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--End of Product Modal -->

    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

    <script>
    const viewItem = (value) => {
        // console.info(value);
        let url = 'ajax/product-view-modal.ajax.php?id=' + value;

        $(".productModal").html(
            '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');
    }
    </script>


</body>

</html>