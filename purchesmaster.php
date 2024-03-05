<?php
$page = "appointments";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not


require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR  . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'distributor.class.php';
require_once CLASS_DIR . 'manufacturer.class.php';
require_once CLASS_DIR . 'packagingUnit.class.php';
// require_once CLASS_DIR . 'measureOfUnit.class.php';
require_once CLASS_DIR . 'itemUnit.class.php';


$Distributor    = new Distributor();
$Manufacturer   = new Manufacturer();
$PackagingUnits = new PackagingUnits();
// $MeasureOfUnits = new MeasureOfUnits();
$ItemUnit       = new ItemUnit();

// Count Distributor Data
$showDistributor = $Distributor->showDistributor($adminId);
$response        = json_decode($showDistributor, true);
$decodedDistributor = !empty($response['data']) ? $response['data'] : null;
$countDistributor   = $decodedDistributor !== null ? count($decodedDistributor) : 0;

// Count Manufacturer Data
$showManufacturer    = $Manufacturer->showManufacturer($adminId);
$decodedManufacturer = ($showManufacturer !== null) ? $showManufacturer : null;
$countManufacturer   = ($decodedManufacturer !== null) ? count($decodedManufacturer) : 0;

// Count Packaging Data
$countPackagingUnits = count($PackagingUnits->showPackagingUnits($adminId));
// Count Product Data
// showItemUnits()

$totalItemUnit       = count($ItemUnit->showItemUnits());
// $countMeasureOfUnits = count($MeasureOfUnits->showMeasureOfUnits($adminId));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--git test -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Purchase Master - <?= $healthCareName ?> | <?= SITE_NAME ?></title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/return-page.css">
    <style>
        .card-hover img {
            -webkit-transform: scale(1);
            transform: scale(1);
            -webkit-transition: .3s ease-in-out;
            transition: .3s ease-in-out;
        }

        .card-hover:hover img {
            -webkit-transform: scale(1.3);
            transform: scale(1.3);
        }
    </style>
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

                        <div class="card-header py-3 justify-content-between">
                            <div class="row">
                                <div class="col-sm-6 mt-2">
                                    <div class="card bg-gradient-warning card-hover">
                                        <div class="card-body mb-0 pb-0">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title text-white">Distributor</h5>
                                                <a class="btn btn-sm text-white bg-transparent" data-bs-toggle="modal" href="#DistributorModal" role="button" onclick="findDistributor('all')">Find</a>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <img src="<?= IMG_PATH . 'Distributor.png' ?>" class="ml-0" style="width: 80px; height: 60px; opacity: 0.5;" alt="">
                                                <h4 class=" text-white" style="margin-right: 50%;"><?= $countDistributor ?></h4>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm text-white bg-transparent mt-n2 mr-3 mb-2" data-toggle="modal" data-target="#add-distributor" onclick="addDistributor()">
                                                Add new
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <div class="card bg-gradient-info card-hover">
                                        <div class="card-body mb-0 pb-0">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title text-white">Manufacturer</h5>
                                                <a class="btn btn-sm text-white bg-transparent" data-bs-toggle="modal" href="#ManufacturModal" role="button" onclick="findManufacturer('all')"> Find</a>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <img src="<?= IMG_PATH . 'manufacturer.png' ?>" class="ml-0" style="width: 80px; height: 60px; opacity: 0.5;" alt="">
                                                <h4 class=" text-white" style="margin-right: 50%;"><?= $countManufacturer ?></h4>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm text-white bg-transparent mt-n2 mr-3 mb-2" data-toggle="modal" data-target="#add-manufacturer" onclick="addManufacture()">
                                                Add new
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <div class="card bg-gradient-primary card-hover">
                                        <div class="card-body mb-0 pb-0">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title text-white">Packaging Unit</h5>
                                                <a class="btn btn-sm text-white bg-transparent" data-bs-toggle="modal" href="#PackUnitModal" role="button" onclick="findPackUnit('all')"> Find</a>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <img src="<?= IMG_PATH . 'packUnit.png' ?>" class="ml-0" style="width: 80px; height: 60px; opacity: 0.5;" alt="">
                                                <h4 class=" text-white" style="margin-right: 50%;"><?= $countPackagingUnits ?></h4>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm text-white bg-transparent mt-n2 mr-3 mb-2" data-toggle="modal" data-target="#add-packagingUnit">
                                                Add new
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <div class="card bg-gradient-success card-hover">
                                        <div class="card-body mb-0 p-4">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title text-white">Product Unit</h5>
                                                <a class="btn btn-sm text-white bg-transparent" data-bs-toggle="modal" href="#ProdUnitModal" role="button" onclick="findProdUnit('all')"> Find</a>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <img src="<?= IMG_PATH . 'prodUnit.png' ?>" class="ml-0" style="width: 80px; height: 60px; opacity: 0.5;" alt="">
                                                <h4 class=" text-white" style="margin-right: 50%;"><?= $totalItemUnit ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

        <!-- distributor search modal -->
        <div class="modal fade" id="DistributorModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Search Distributor : &nbsp;</h5>
                        <div class="input-group w-50">
                            <input id="searchInput" type="search" class="form-control form-control-sm" placeholder="Search by name" aria-label="Search">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-success" onclick="distSearch('all')"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body DistributorModal">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="distRequestModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content ">
                    <div class="modal-header">
                        <div class="d-flex justify-content-between w-75">
                            <h5 class="modal-title" id="exampleModalToggleLabel2">Distributor Request</h5>
                            <!-- <button class="btn btn-primary btn-sm" data-bs-target="#DistributorModal" data-bs-toggle="modal" data-bs-dismiss="modal">Back to Distributor</button> -->
                        </div>
                        <button type="button" class="btn btn-transparent fs-2 p-0" data-bs-target="#DistributorModal" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body distRequestModal">

                    </div>
                </div>
            </div>
        </div> <!-- end distributor modal --->

        <!-- add distributor Modal -->
        <div class="modal fade" id="add-distributor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Distributor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body add-distributor">
                        <!-- Details Appeare Here by Ajax  -->
                    </div>
                </div>
            </div>
        </div>
        <!--end add distributor Modal -->


        <!-- Manufacture search modal -->

        <div class="modal fade" id="ManufacturModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Search Manufacturer : &nbsp;</h5>
                        <div class="input-group w-50">
                            <input id="manuSearchInput" type="search" class="form-control form-control-sm" placeholder="Search by name" aria-label="Search">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-success" onclick="manuSearch('all')"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body ManufacturModal">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="manufacturerModal" aria-hidden="true" aria-labelledby="exampleModalToggleManufacture" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content ">
                    <div class="modal-header">
                        <div class="d-flex justify-content-between w-75">
                            <h5 class="modal-title" id="exampleModalToggleManufacture">Manufacturer Request</h5>
                            <!-- <button class="btn btn-primary btn-sm" data-bs-target="#DistributorModal" data-bs-toggle="modal" data-bs-dismiss="modal">Back to Distributor</button> -->
                        </div>
                        <button type="button" class="btn btn-transparent fs-2 p-0" data-bs-target="#ManufacturModal" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body manufacturerModal">

                    </div>
                </div>
            </div>
        </div> <!-- end manufacture modal  -->

        <!-- add manufacturer modal -->
        <div class="modal fade" id="add-manufacturer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Manufacturer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body add-manufacturer">
                        <!-- Details Appeare Here by Ajax  -->
                    </div>
                </div>
            </div>
        </div><!-- end manufacturer modal -->

        <!-- Packaging search modal  -->
        <div class="modal fade" id="PackUnitModal" aria-hidden="true" aria-labelledby="exampleModalToggle" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Search Pack Unit : &nbsp;</h5>
                        <div class="input-group w-50">
                            <input id="packSearchInput" type="search" class="form-control form-control-sm" placeholder="Search by name" aria-label="Search">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-success" onclick="packSearch('all')"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body PackUnitModal">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="unitModal" aria-hidden="true" aria-labelledby="unitModalLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content ">
                    <div class="modal-header">
                        <div class="d-flex justify-content-between w-75">
                            <h5 class="modal-title" id="unitModalLabel">Units Request</h5>
                            <!-- <button class="btn btn-primary btn-sm" data-bs-target="#DistributorModal" data-bs-toggle="modal" data-bs-dismiss="modal">Back to Distributor</button> -->
                        </div>
                        <button type="button" class="btn btn-transparent fs-2 p-0" data-bs-target="#PackUnitModal" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body unitModal">

                    </div>
                </div>
            </div>
        </div> <!-- end Packaging search modal  -->

        <!-- add packaging unit -->
        <div class="modal fade" id="add-packagingUnit" tabindex="-1" role="dialog" aria-labelledby="packUnitModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="packUnitModalLabel">Add Packaging Unit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body add-packagingUnit">
                        <form method="post" action="ajax/packagingUnit.add.ajax.php">

                            <div class="col-md-12">
                                <label class="mb-0 mt-1" for="unit-name">Unit Name</Address></label>
                                <input class="form-control" id="unit-name" name="uni-name" placeholder="Unit Name" required>
                            </div>


                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 me-md-2">
                                <button class="btn btn-primary me-md-2" name="add-unit" type="submit">Add
                                    Unit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end packaging unit -->
        <!-- Prod Unit search modal  -->

        <div class="modal fade" id="ProdUnitModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Search Product Unit : &nbsp;</h5>
                        <div class="input-group w-50">
                            <input id="prodSearchInput" type="search" class="form-control form-control-sm" placeholder="Search by name" aria-label="Search">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-success" onclick="prodSearch('all')"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-transparent p-1" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ProdUnitModal">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="prodUnitReqModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content ">
                    <div class="modal-header">
                        <div class="d-flex justify-content-between w-75">
                            <h5 class="modal-title" id="exampleModalToggleLabel2">Update Item Unit</h5>
                            <!-- <button class="btn btn-primary btn-sm" data-bs-target="#DistributorModal" data-bs-toggle="modal" data-bs-dismiss="modal">Back to Distributor</button> -->
                        </div>
                        <button type="button" class="btn btn-transparent fs-2 p-0" data-bs-target="#ProdUnitModal" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body prodUnitReqModal">

                    </div>
                </div>
            </div>
        </div>
        <!-- end Prod Unit search modal  -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <!-- <script src="<?= JS_PATH ?>filter.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        const addDistributor = () => {
            var parentLocation = window.location.origin + window.location.pathname;
            $.ajax({
                url: "components/distributor-add.php",
                type: "POST",
                data: {
                    urlData: parentLocation
                },
                success: function(response) {
                    let body = document.querySelector('.add-distributor');
                    body.innerHTML = response;
                },
                error: function(error) {
                    console.error("Error: ", error);
                }
            });
        }
    </script>

    <script>
        const addManufacture = () => {
            var parentLocation = window.location.origin + window.location.pathname;
            $.ajax({
                url: "components/manufacturer-add.php",
                type: "POST",
                data: {
                    urlData: parentLocation
                },
                success: function(response) {
                    let body = document.querySelector('.add-manufacturer');
                    body.innerHTML = response;
                },
                error: function(error) {
                    console.error("Error: ", error);
                }
            });
        }
    </script>

    <script>
        function distSearch(defaultSearch) {
            var search = document.getElementById('searchInput').value.trim();
            // distributorSearch(search);
            if (search === '') {
                distributorSearch(defaultSearch);
            } else {
                distributorSearch(search);
            }
        }

        function manuSearch(defaultSearch) {
            var manusearch = document.getElementById('manuSearchInput').value.trim();
            if (manusearch === '') {
                manufacturerSearch(defaultSearch);
            } else {
                manufacturerSearch(manusearch);
            }
        }

        function packSearch(defaultSearch) {
            var packSearchInput = document.getElementById('packSearchInput').value.trim();
            if (packSearchInput === '') {
                packUnitSearch(defaultSearch);
            } else {
                packUnitSearch(packSearchInput);
            }
        }

        function prodSearch(defaultSearch) {
            var prodSearchInput = document.getElementById('prodSearchInput').value.trim();
            // prodUnitSearch(prodSearchInput);
            if (prodSearchInput === '') {
                prodUnitSearch(defaultSearch);
            } else {
                prodUnitSearch(prodSearchInput);
            }
        }
    </script>

    <script>
        function findDistributor(defaultSearch) {
            var search = document.getElementById('searchInput').value || defaultSearch;
            distributorSearch(search);
        }

        function findManufacturer(defaultSearch) {
            var manusearch = document.getElementById('manuSearchInput').value || defaultSearch;
            manufacturerSearch(manusearch);
        }

        function findPackUnit(defaultSearch) {
            var packSearchInput = document.getElementById('packSearchInput').value || defaultSearch;
            packUnitSearch(packSearchInput);
        }

        function findProdUnit(defaultSearch) {
            var prodSearchInput = document.getElementById('prodSearchInput').value || defaultSearch;
            prodUnitSearch(prodSearchInput);
        }

        function distributorSearch(search) {
            $.ajax({
                url: 'ajax/distributor.list-view.ajax.php',
                type: 'POST',
                data: {
                    search: search
                },
                success: function(data) {
                    $('.DistributorModal').html(data);
                },
                error: function(error) {
                    console.error('Error loading distributor modal:', error);
                }
            });
        }

        function manufacturerSearch(manusearch) {
            $.ajax({
                url: 'ajax/manufacturer.list-view.ajax.php',
                type: 'POST',
                data: {
                    search: manusearch
                },
                success: function(data) {
                    $('.ManufacturModal').html(data);
                },
                error: function(error) {
                    console.error('Error loading distributor modal:', error);
                }
            });
        }

        function packUnitSearch(packSearchInput) {
            $.ajax({
                url: 'ajax/packUnit.search.ajax.php',
                type: 'POST',
                data: {
                    search: packSearchInput
                },
                success: function(data) {
                    $('.PackUnitModal').html(data);
                },
                error: function(error) {
                    console.error('Error loading distributor modal:', error);
                }
            });
        }

        function prodUnitSearch(prodSearchInput) {
            $.ajax({
                url: 'ajax/prodUnit.search.ajax.php',
                type: 'POST',
                data: {
                    search: prodSearchInput
                },
                success: function(data) {
                    $('.ProdUnitModal').html(data);
                },
                error: function(error) {
                    console.error('Error loading distributor modal:', error);
                }
            });
        }
    </script>
    <script>
        //View and Edit Manufacturer function
        distViewAndEdit = (distributorId) => {
            let ViewAndEdit = distributorId;
            let url = "ajax/distributor.request.ajax.php?Id=" + ViewAndEdit;
            $(".distRequestModal").html(
                '<iframe width="99%" height="530px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function
    </script>
    <script>
        const manufactViewRequest = (manufacturerId) => {
            let ViewAndEdit = manufacturerId;
            let url = "ajax/manufacturer.request.ajax.php?Id=" + ViewAndEdit;
            $(".manufacturerModal").html(
                '<iframe width="99%" height="330px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        }
    </script>

</body>

</html>