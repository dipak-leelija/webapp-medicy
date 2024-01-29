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
// require_once ROOT_DIR . '_config/accessPermission.php';
require_once CLASS_DIR . 'encrypt.inc.php';



$page = "products";

//Intitilizing Doctor class for fetching doctors
$Products       = new Products();
$Request            = new Request;
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

    // print_r($productList);

    $pagination = json_decode($Pagination->arrayPagination($productList));

    // $result = $pagination;
    // $allProducts = $pagination->items;
    // $totalPtoducts = $pagination->totalitem;

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
    // print_r($productList);
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
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/products.css">
    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css">

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

                    
                </div>
                <!-- End of container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once ROOT_COMPONENT . 'footer-text.php'; ?>
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

        // =============== modal size control funcion ==============
        /*function changeModalSize(flag, modalId) {

            let modal = document.getElementById(modalId);

            if (modal) {
                if (flag == 0) {
                    modal.querySelector('.modal-dialog').classList.remove('modal-sm', 'modal-md', 'modal-lg', 'modal-xl');

                    // modal.querySelector('.modal-dialog').classList.add('modal-md'); 

                    modal.querySelector('.modal-dialog').classList.add('modal-xl');
                }

                if (flag == 1) {
                    modal.querySelector('.modal-dialog').classList.remove('modal-sm', 'modal-md', 'modal-lg', 'modal-xl');

                    modal.querySelector('.modal-dialog').classList.add('modal-xl');
                }
            }
        }*/
        // ================ end of modal size control =============

        // ========================== view and edit fucntion =========================
        const viewItem = (t) => {
            let prodId = t.id;
            let prodReqStatus = t.getAttribute("prodReqStatus");
            let oldProdFlag = t.getAttribute("oldProdFlag");
            let editRequestFlag = t.getAttribute("editRequestFlag");

            url = `ajax/product-view-modal-for-user.ajax.php?id=${prodId}&prodReqStatus=${prodReqStatus}&oldProdFlag=${oldProdFlag}&editRequestFlag=${editRequestFlag}`; //  updated path for user.

            $(".productViewModal").html(
                '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        }
        // === end of view and edit ==================================================


        // ========================== PRODUCT SEARCH START ===========================

        const productsSearch = document.getElementById("prodcut-search");
        const productsDropdown = document.getElementsByClassName("c-dropdown")[0];


        // productsSearch.addEventListener("focus", () => {
        //     productsDropdown.style.display = "block";
        // });


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