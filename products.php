<?php

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'products.class.php';
require_once CLASS_DIR . 'request.class.php';
require_once CLASS_DIR . 'productsImages.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


//Intitilizing Doctor class for fetching doctors
$Products       = new Products();
$Request        = new Request;
$Pagination     = new Pagination();
$ProductImages  = new ProductImages();


if (isset($_GET['search'])) {

    $prodId = $_GET['search'];
    $prodReqStatus = $_GET['prodReqStatus'];
    $oldProdReqFlag = $_GET['oldProdReqFlag'];
    $editRequestStatus = $_GET['editRequestFlag'];

    if ($editRequestStatus != '') {
        $editRequestStatus = $editRequestStatus;
    } else {
        $editRequestStatus = 0;
    }

    $productList = json_decode($Products->showProductsByIdOnUser($prodId, $adminId, $editRequestStatus, $prodReqStatus, $oldProdReqFlag));

    $productList = $productList->data;

    $pagination = json_decode($Pagination->arrayPagination($productList));

    if ($pagination->status == 1) {
        $result = $pagination;
        $allProducts = $pagination->items;
        $totalPtoducts = $pagination->totalitem;
    } else {
        // Handle the case when status is not 1
        $result = $pagination;
        $allProducts = [];
        $totalPtoducts = 0;
    }

} else {

    // Function INitilized 
    $col = 'admin_id';
    $result = json_decode($Pagination->productsWithPaginationForUser($adminId));

    $allProducts    = $result->products;
    $totalPtoducts  = $result->totalPtoducts;

    $productList = json_decode($Products->showProductsByLimitForUser($adminId));
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Products - <?= SITE_NAME?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/products.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css" type="text/css">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css">

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

                <!-- Begin container-fluid -->
                <div class="container-fluid">
                    <div class="row" style="z-index: 999;">
                        <div class="col-12">
                            <?php include ROOT_COMPONENT . "drugPermitDataAlert.php"; ?>
                        </div>
                    </div>
                    <!-- New Section -->
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <!-- <div class="d-flex col-12"> -->
                                <div class="col-md-3 mt-2 p-2">
                                    <h6 class="m-0 font-weight-bold text-primary">Total Items:
                                        <?= $totalPtoducts ?>
                                    </h6>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="col-md-7">
                                        <input type="text" name="prodcut-search" id="prodcut-search" class="form-control prodcut-search w-100 " placeholder="Search Products (Product Name / Product Composition)" autocomplete="off">

                                        <div class="p-2 bg-light col-md-10 c-dropdown" id="product-list" style="z-index: 9999;">
                                            <div class="lists" id="lists">
                                                <?php
                                                if (!empty($productList->data) && is_array($productList->data)) {
                                                    foreach ($productList->data as $eachProd) {
                                                        // print_r($eachProd);
                                                ?>
                                                        <div class="p-1 border-bottom list">
                                                            <div class="" id="<?= $eachProd->product_id ?>" onclick="searchProduct(this)">
                                                                <?= $eachProd->name ?>
                                                            </div>
                                                            <div class="">
                                                                <small><?= $eachProd->comp_1 ?> , <?= $eachProd->comp_2 ?></small>
                                                            </div>
                                                        </div>

                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-sm btn-primary" href="add-new-product.php" style="margin-left: 4rem;"><i class="fas fa-plus"></i> Add</a>
                                    </div>
                                </div>
                                <!-- </div> -->
                            </div>
                            <div class="card-body">

                                <div class="d-flex justify-content-center">
                                    <div class="row card-div">

                                        <section>
                                            <div class="row py-4 px-5">
                                                <?php
                                                // print_r($allProducts);
                                                // echo count($allProducts);
                                                if ($allProducts != null) {
                                                    foreach ($allProducts as $item) {
                                                        // print_r($item);
                                                        $image = json_decode($ProductImages->showImageById($item->product_id));
                                                        // print_r($image);
                                                        if ($image->status) {
                                                            $imgData = $image->data;

                                                            $productImage = $imgData[0]->image;
                                                        } else {

                                                            $image = json_decode($ProductImages->showImageByPrimay($item->product_id, $adminId));
                                                            // print_r($image);
                                                            if ($image->status) {
                                                                $imgData = $image->data;
                                                                // print_r($imgData);
                                                                $productImage = $imgData[0]->image;
                                                            } else {
                                                                $productImage = 'default-product-image/medicy-default-product-image.jpg';
                                                            }
                                                        }

                                                        if (property_exists($item, 'dsc')) {
                                                            if ($item->dsc == null) {
                                                                $dsc = '';
                                                            } else {
                                                                $dsc = $item->dsc . '...';
                                                            }
                                                        } else {
                                                            $dsc = '';
                                                        }


                                                        if (isset($item->prod_req_status)) {
                                                            $prodReqStatus = $item->prod_req_status;
                                                        } else {
                                                            $prodReqStatus = '';
                                                        }

                                                        if (isset($item->old_prod_flag)) {
                                                            $oldProdFlag = $item->old_prod_flag;
                                                        } else {
                                                            $oldProdFlag = '';
                                                        }

                                                        if (isset($item->edit_request_flag)) {
                                                            $editRequestFlag = $item->edit_request_flag;
                                                            $table = 'products';
                                                        } else {
                                                            $editRequestFlag = '';
                                                            $table = 'product_request';
                                                        }

                                                        //======== check edit request ========
                                                        $editRequestCheck = $Request->selectProductById($item->product_id, $adminId);
                                                        $editRequestCheck = json_decode($editRequestCheck);

                                                        if ($editRequestCheck->status) {
                                                            $editRequestToken = 1;
                                                            $newProdRequestToken = 0;
                                                        } else {
                                                            $editRequestToken = 0;
                                                            $newProdRequestToken = 0;
                                                        }


                                                        //====== check new product request =======
                                                        if (isset($item->product_id) && isset($item->old_prod_flag)) {
                                                            if ($item->old_prod_flag == 0) {
                                                                $editRequestToken = 0;
                                                                $newProdRequestToken = 1;
                                                            }
                                                        }

                                                ?>
                                                        <div class="item col-12 col-md-6 col-lg-4 col-xl-3 ">

                                                            <div class="card mb-3 p-3" style="height: 95%;">

                                                                <?php
                                                                if ($editRequestToken) {
                                                                    echo '<div class="d-flex justify-content-end mt-n4 mr-n4" style="z-index: 999; position: absolute; top: 15px; right: 15px; background-color: rgb(0, 160, 152); color: white; padding: 5px;"><small>Edit Request Generated</small></div>
                                                                    ';
                                                                }
                                                                ?>


                                                                <?php
                                                                if ($newProdRequestToken) {
                                                                    echo '<div class="d-flex justify-content-end mt-n4 mr-n4" style="z-index: 999; position: absolute; top: 15px; right: 15px; background-color: rgb(238, 75, 43); color: white; padding: 5px;"><small>New Product Request</small></div>
                                                                    ';
                                                                }
                                                                ?>

                                                                <img src="<?php echo PROD_IMG_PATH ?><?php echo $productImage ?>" class="card-img-top" alt="...">

                                                                <div class="card-body">
                                                                    <label><b><?php echo $item->name; ?></b></label>
                                                                    <p class="mb-0"><b><?php $item->name ?></b></p>
                                                                    <small class="card-text mt-0" style="text-align: justify;"><?php echo substr($dsc, 0, 25) ?></small>

                                                                </div>


                                                                <div class="row px-3 pb-2 mt-2">
                                                                    <div class="col-6">₹ <?php echo $item->mrp ?></div>
                                                                    <div class="col-6 d-flex justify-content-end">
                                                                        <button class="btn btn-sm border border-info" data-toggle="modal" data-target="#productViewModal" id="<?php echo $item->product_id ?>" value="<?php echo $item->verified ?>" prodReqStatus="<?php echo $prodReqStatus ?>" oldProdFlag="<?php echo $oldProdFlag ?>" editRequestFlag="<?php echo $editRequestFlag ?>" table="<?php echo $table ?>" onclick="viewItem(this)">View</button>
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

        </div>
        <!--End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Product Modal -->
    <div class="modal fade" id="productViewModal" tabindex="-1" aria-labelledby="product-view-edit-modal" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="product-view-edit-modal">View/Edit Product</h5>
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
    <!-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a> -->
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

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
            let prodReqStatus = t.getAttribute("prodReqStatus");
            let oldProdFlag = t.getAttribute("oldProdFlag");
            let editRequestFlag = t.getAttribute("editRequestFlag");
            let table = t.getAttribute('table');

            url = `ajax/product-view-modal-for-user.ajax.php?id=${prodId}&prodReqStatus=${prodReqStatus}&oldProdFlag=${oldProdFlag}&editRequestFlag=${editRequestFlag}&table=${table}`; //  updated path for user.

            $(".productViewModal").html(
                '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        }
        // === end of view and edit ==================================================


        // ========================== PRODUCT SEARCH START ===========================

        const productsSearch = document.getElementById("prodcut-search");
        const productsDropdown = document.getElementsByClassName("c-dropdown")[0];

        document.addEventListener("click", (event) => {
            // Check if the clicked element is not the input field or the manufDropdown
            if (!productsSearch.contains(event.target) && !productsDropdown.contains(event.target)) {
                productsDropdown.style.display = "none";
            }
        });

        document.addEventListener("blur", (event) => {
            // Check if the element losing focus is not the manufDropdown or its descendants
            if (!productsDropdown.contains(event.relatedTarget)) {
                // Delay the hiding to allow the click event to be processed
                setTimeout(() => {
                    productsDropdown.style.display = "none";
                }, 100);
            }
        });

        productsSearch.addEventListener("keydown", () => {

            let list = document.getElementsByClassName('lists')[0];
            let searchVal = document.getElementById("prodcut-search").value;

            if (searchVal.length > 2) {

                let manufURL = `ajax/products.list-view.ajax.php?match=${searchVal}`;
                xmlhttp.open("GET", manufURL, false);
                xmlhttp.send(null);

                list.innerHTML = xmlhttp.responseText;
                document.getElementById('product-list').style.display = 'block';

            } else if (searchVal == '') {

                searchVal = 'all';

                let manufURL = `ajax/products.list-view.ajax.php?match=${searchVal}`;
                xmlhttp.open("GET", manufURL, false);
                xmlhttp.send(null);
                // console.log();
                list.innerHTML = xmlhttp.responseText;
                document.getElementById('product-list').style.display = 'block';
            } else {
                document.getElementById('product-list').style.display = 'none';
                list.innerHTML = '';
                productsDropdown.style.display = "none";
            }

        });

        //================================================================

        const searchProduct = (t) => {
            var prodId = t.id.trim();
            var prodName = t.innerHTML.trim();
            var prodReqStatus = t.getAttribute("prodReqStatus");
            var oldProdReqFlag = t.getAttribute("oldProdReqFlag");
            var editRequestFlag = t.getAttribute("editRequestFlag");

            // console.log("prod request status : "+editRequestFlag);

            var currentURLWithoutQuery = window.location.origin + window.location.pathname;

            let newUrl = `${currentURLWithoutQuery}?search=${prodId}&prodReqStatus=${prodReqStatus}&oldProdReqFlag=${oldProdReqFlag}&editRequestFlag=${editRequestFlag}`;

            localStorage.setItem('prodName', prodName);

            window.location.href = newUrl;

            document.getElementById("prodcut-search").value = prodName;
            productsDropdown.style.display = "none";

        }


        document.addEventListener('DOMContentLoaded', function() {

            let storedProdName = localStorage.getItem('prodName');

            if (storedProdName !== null) {
                document.getElementById("prodcut-search").value = storedProdName;
                localStorage.setItem('prodName', '');
            }
        });
    </script>

</body>

</html>