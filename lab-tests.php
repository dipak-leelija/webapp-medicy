<?php

$page = "lab-tests";

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';

require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'labTestTypes.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'pagination.class.php';

$labTypes       = new LabTestTypes;
$subTests       = new SubTests;
$Pagination     = new Pagination;


//================================================
// ============= APPOINTMENT DATA ================
$searchVal = '';
$match = '';

if (isset($_GET['search'])) {
    $searchVal = $match = $_GET['search'];
    $showLabTypes = json_decode($labTypes->searchLabTest($searchVal));
} else {
    $showLabTypes = json_decode($labTypes->showLabTypes());
}

if ($showLabTypes->status) {
    $labData = $showLabTypes->data;

    if (!empty($labData)) {

        $allLabTestData = $labData;

        if (is_array($allLabTestData)) {

            $response = json_decode($Pagination->arrayPagination($allLabTestData));

            $slicedLabTestData = '';
            $paginationHTML = '';
            $labTestTotalItem = $slicedLabTestData = $response->totalitem;

            if ($response->status == 1) {
                $slicedLabTestData = $response->items;
                $paginationHTML = $response->paginationHTML;
            }
        } else {
            $labTestTotalItem = 0;
        }
    } else {
        $labTestTotalItem = 0;
        $paginationHTML = '';
    }
} else {
    $labTestTotalItem = 0;
    $paginationHTML = '';
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>SB Admin 2 - Buttons</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-test.css">

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

                        <div class="col-12 col-md-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 booked_btn">
                                    <div class="row d-flex">
                                        <div class="col-md-4">
                                            <h6 class="m-0 font-weight-bold text-primary">Lab Test List</h6>
                                        </div>
                                        <label class="d-none" for="" id="base-url-holder"></label>
                                        <label class="d-none" for="" id="srch-btn-controler">0</label>
                                        <div class="col-md-8 input-group d-flex justify-content-end">
                                            <input class="cvx-inp w-75" type="text" placeholder="Search test..." name="search-test" id="search-test" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>" autocomplete="off">

                                            <div class="input-group-append" id="dataSearch-btnDiv">
                                                <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="dataSearch-btn" onclick="testDataSearch(this)"><i class="fas fa-search"></i></button>
                                            </div>

                                            <div class="input-group-append" id="reset-searchBtn-div">
                                                <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="reset-search" onclick="testDataSearch(this)"><i class="fas fa-times"></i></button>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive" id="filter-table">

                                        <!-- table start -->
                                        <table class="table table-sm table-hover w-100">
                                            <thead class="bg-primary text-light">
                                                <tr>
                                                    <th></th>
                                                    <th>Test Name</th>
                                                    <th>Provided By</th>
                                                    <th>No of Tests</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-data">

                                                <?php

                                                if ($labTestTotalItem == 0) {
                                                    echo "No Test Type Avilable.";
                                                } else {

                                                    foreach ($slicedLabTestData as $showLabTypesShow) {
                                                        $testTypeId = $showLabTypesShow->id;
                                                        $testName   = $showLabTypesShow->test_type_name;
                                                        $testDsc    = $showLabTypesShow->dsc;
                                                        $testPvdBy  = $showLabTypesShow->provided_by;
                                                        $testImg    =  $showLabTypesShow->image;

                                                        // $testImg = LABTEST_IMG_PATH . $testImg;
                                                        if (!empty($testImg)) {
                                                            $testImg = LABTEST_IMG_PATH . $testImg;
                                                        } else {
                                                            $testImg = LABTEST_IMG_PATH . 'default-lab-test/labtest.svg';
                                                        }

                                                        $delTestTypeId = "test-type-delete.php?deletetestype=" . $testTypeId;

                                                ?>

                                                        <tr>
                                                            <td class='testImg'>
                                                                <img src="<?php echo $testImg; ?>" alt="">
                                                            </td>
                                                            <td><?php echo $testName; ?></td>
                                                            <td><?php echo $testPvdBy; ?></td>
                                                            <td></td>
                                                            <td class='text-center'>
                                                                <span class='badge badge-secondary'>
                                                                    <a class='text-light' href=" single-lab-page.php?labtypeid=<?php echo url_enc($testTypeId) ?>">
                                                                        View
                                                                    </a>
                                                                </span>

                                                                <span class='badge badge-primary cursor-pointer' data-bs-toggle='modal' data-bs-target="#testEditModal" onclick="LabCategoryEditModal(<?php echo $testTypeId; ?>)">
                                                                    Edit
                                                                </span>

                                                                <span class='badge badge-danger'>
                                                                    <a class='text-light' href="<?php echo $delTestTypeId ?>" onclick="deleteConfirmation()">Delete</i></a>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php 
                                        if($labTestTotalItem > 16){
                                            ?>
                                            <div class="d-flex justify-content-center" id="pagination-control">
                                        <?= $paginationHTML ?>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Add New Catagory Section -->
                        <div class="col-12 col-md-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 booked_btn">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="m-0 font-weight-bold text-primary">Add Tests</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="border rounded bg-light text-center py-2 mb-2" data-bs-toggle='modal' data-bs-target="#addTestTypeModel">
                                        Add Test Types
                                    </div>
                                    <div class="border rounded bg-light text-center py-2">
                                        Add Sub Test
                                    </div>

                                    <button type="button" id="add-testType" class="btn btn-primary btn-small border rounded text-center" data-toggle="modal" data-target="#addTestDataModel" onclick="addTestAndSubTest(this)">
                                        Add Test Types
                                    </button>

                                    <button type="button" id="add-subTest" class="btn btn-primary btn-small border rounded text-center" data-toggle="modal" data-target="#addTestDataModel" onclick="addTestAndSubTest(this)">
                                        Add Sub Test
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->

            <?php include ROOT_COMPONENT . 'footer-text.php'; ?>

        </div>
        <!-- End of Main Content -->
    </div>
    <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->


    <!-- Category Edit Modal -->
    <div class="modal fade" id="addTestDataModel" tabindex="-1" aria-labelledby="addTestDataModelLabel" aria-hidden="true">
        <div class="modal-dialog" id="modal-sizeId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNicheDetails"></h5>
                    <button type="button" onClick="refreshPage()" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="far fa-times-circle"></i>
                    </button>
                </div>
                <!-- MODAL BODY -->
                <div class="modal-body add-new-test-data-modal">

                </div>
                <!-- Modal Body end -->

            </div>
        </div>
    </div>
    <!-- End of Category Edit Modal -->


    <!-- Category Edit Modal -->
    <div class="modal fade" id="testEditModal" tabindex="-1" aria-labelledby="testEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNicheDetails">Edit Lab Test Category</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="far fa-times-circle"></i></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <button type="button" class="btn btn-sm btn-primary" onClick="refreshPage()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Category Edit Modal -->

    <!-- Custom Javascript  -->
    <!-- LabCategoryEditModal function body gose hear -->
    <script src="<?= JS_PATH ?>custom-js.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?= JS_PATH ?>lab-tests.js"></script>

    <!-- inline script -->
    <script>
        const baseUrl = window.location.origin + window.location.pathname;
        document.getElementById('base-url-holder').innerHTML = baseUrl;

        function testDataSearch(t) {
            console.log(t.id);
            let searchParameter = '';
            let currentUrl = window.location.origin + window.location.pathname

            if (t.id == 'dataSearch-btn') {
                let searchData = document.getElementById('search-test');
                if (searchData.value.length > 2) {

                    searchParameter += `&search=${searchData.value}`

                    let searchUrl = `${currentUrl}?${searchParameter}`;

                    window.location.replace(searchUrl);
                } else {
                    alert('Enter minimum 3 charechter');
                }

                document.getElementById('srch-btn-controler').innerHTML = '1';
            }

            if (t.id == 'reset-search') {
                window.location.replace(baseUrl);

                document.getElementById('dataSearch-btnDiv').classList.remove('d-none');
                document.getElementById('reset-searchBtn-div').classList.add('d-none');

                document.getElementById('srch-btn-controler').innerHTML == '0'
            }
        }
    </script>



</body>

</html>