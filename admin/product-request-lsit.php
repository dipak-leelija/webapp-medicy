<?php
$page = "product-request-lsit";

require_once dirname(__DIR__) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';




//Intitilizing Doctor class for fetching doctors
$Products       = new Products();
$Pagination     = new Pagination();
$ProductImages  = new ProductImages();
$Request        = new Request;



// Function INitilized 
$col = 'admin_id';
$result = json_decode($Pagination->productRequestWithPagination()); //showAllProducts
// print_r($result);

$allProducts    = $result->products;
$totalPtoducts  = $result->totalPtoducts;

$productList = json_decode($Products->showProductsByLimit());


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

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/product-request-list.css">
    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include SUP_ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include SUP_ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin container-fluid -->
                <div class="container-fluid">

                    <!-- New Section -->
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 col-12 d-flex">
                                <div class="col-md-6">
                                    <h6 class="m-0 font-weight-bold text-primary">Total Items:
                                        <?= $totalPtoducts ?>
                                    </h6>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end">
                                    <a class="btn btn-sm btn-primary" href="add-products.php" style="margin-left: 4rem;"><i class="fas fa-plus"></i> Add</a>
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
                                                        // print_r($item);
                                                        $image = json_decode($ProductImages->showImagesByProduct($item->product_id));

                                                        // print_r($image);
                                                        if ($image->status) {
                                                            $imgData = $image->data;

                                                            $productImage = $imgData->image;
                                                        } else {
                                                            $productImage = 'default-product-image/medicy-default-product-image.jpg';
                                                        }

                                                        if (isset($item->dsc)) {
                                                            $dsc = $item->dsc . '...';
                                                        } else {
                                                            $dsc = ' ';
                                                        }


                                                        if ($item->prod_req_status == 0) {
                                                            if ($item->old_prod_flag == 0) {
                                                                $modalHeading = 'New Product Request';
                                                            } else {
                                                                $modalHeading = 'Existing Product Edit Request';
                                                            }
                                                        }
                                                ?>

                                                        <div class="item col-12 col-sm-6 col-md-4 col-lg-3 ">
                                                            <div class="card  mb-3 p-3" style="min-width: 14rem; min-height: 11rem;">
                                                                <img src="<?php echo PROD_IMG_PATH ?><?php echo $productImage ?>" class="card-img-top" alt="...">
                                                                <div class="card-body">
                                                                    <label><b><?php echo $item->name; ?></b></label>
                                                                    <p class="mb-0"><b><?php $item->name ?></b></p>
                                                                    <small class="card-text mt-0" style="text-align: justify;"><?php echo substr($dsc, 0, 65) ?></small>

                                                                </div>


                                                                <div class="row px-3 pb-2">
                                                                    <div class="col-6">₹ <?php echo $item->mrp ?></div>
                                                                    <div class="col-6 d-flex justify-content-end">
                                                                        <button class="btn btn-sm border border-info" data-toggle="modal" data-target="#productViewModal" id="<?php echo $item->product_id ?>" value="<?php echo $modalHeading; ?>" onclick="viewItem(this)">View</button>
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
                                                    <?= $result->paginationHTML ?>
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
            <?php include_once SUP_ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!--End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


    <!-- Product Modal -->
    <div class="modal fade" id="productViewModal" tabindex="-1" aria-labelledby="product-view-edit-modal" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="product-view-edit-modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body productViewModal">
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
        var xmlhttp = new XMLHttpRequest();

        // ========================== view and edit fucntion =========================
        const viewItem = (t) => {
            let prodId = t.id;
            let modalHeading = t.value;

            let modalTitle = document.getElementById('product-view-edit-modal-title');
            modalTitle.textContent = modalHeading;

            let url = '';
            url = `ajax/product-view-modal.ajax.php?id=${prodId}&table=${'product_request'}`;

            $(".productViewModal").html(
                '<iframe width="100%" height="500px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        }
        // === end of view and edit ==================================================


        // ========================== PRODUCT SEARCH START ===========================

        // const productsSearch = document.getElementById("prodcut-search");
        // const productsDropdown = document.getElementsByClassName("c-dropdown")[0];

        // document.addEventListener("click", (event) => {
        //     if (!productsSearch.contains(event.target) && !productsDropdown.contains(event.target)) {
        //         productsDropdown.style.display = "none";
        //     }
        // });


        // document.addEventListener("blur", (event) => {
        //     if (!productsDropdown.contains(event.relatedTarget)) {
        //         setTimeout(() => {
        //             productsDropdown.style.display = "none";
        //         }, 100);
        //     }
        // });



        // productsSearch.addEventListener("keydown", () => {

        //     let list = document.getElementsByClassName('lists')[0];
        //     let searchVal = document.getElementById("prodcut-search").value;

        //     if (searchVal.length > 2) {

        //         let manufURL = `ajax/products.list-view.ajax.php?match=${searchVal}`;
        //         xmlhttp.open("GET", manufURL, false);
        //         xmlhttp.send(null);

        //         list.innerHTML = xmlhttp.responseText;
        //         document.getElementById('product-list').style.display = 'block';
        //     } else if (searchVal == '') {

        //         searchVal = 'all';

        //         let manufURL = `ajax/products.list-view.ajax.php?match=${searchVal}`;
        //         xmlhttp.open("GET", manufURL, false);
        //         xmlhttp.send(null);
        //         // console.log();
        //         list.innerHTML = xmlhttp.responseText;
        //         document.getElementById('product-list').style.display = 'block';
        //     } else {
        //         document.getElementById('product-list').style.display = 'none';
        //         list.innerHTML = '';
        //         productsDropdown.style.display = "none";
        //     }

        // });

        //================================================================

        // const searchProduct = (t) => {
        //     var prodId = t.id.trim();
        //     var prodName = t.innerHTML.trim();

        //     var currentURLWithoutQuery = window.location.origin + window.location.pathname;

        //     let newUrl = `${currentURLWithoutQuery}?search=${prodId}`;

        //     localStorage.setItem('prodName', prodName);

        //     window.location.href = newUrl;

        // }


        // document.addEventListener('DOMContentLoaded', function() {

        //     let storedProdName = localStorage.getItem('prodName');

        //     if (storedProdName !== null) {
        //         document.getElementById("prodcut-search").value = storedProdName;
        //         localStorage.setItem('prodName', '');
        //     }
        // });
    </script>

</body>

</html>