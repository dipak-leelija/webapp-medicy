<?php
$page = "appointments";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not


require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'distributor.class.php';


$Appoinments = new Appointments();
$Pagination  = new Pagination;
$Doctors     = new Doctors();
$Employees   = new Employees;
$Distributor = new Distributor();

$showDistributor = json_decode($Distributor->showDistributor());
$countDistributor = count($showDistributor->data);
$showDistributor = count($showDistributor->data);
// print_r($showDistributor);

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

    <title>Appointments - <?= $healthCareName ?> | <?= SITE_NAME ?></title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/return-page.css">
    <style>
        .card-hover:hover img {
            width: 85px;
            height: 65px;
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
                                    <div class="card bg-gradient-info card-hover">
                                        <div class="card-body mb-0 pb-0">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title text-white">Distributor</h5>
                                                <button type="button" class="btn btn-sm text-white bg-transparent" data-toggle="modal" data-target="#DistributorModal">
                                                    Find
                                                </button>
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
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title">Manufacturer</h5>
                                                <a href="#" class="btn btn-sm btn-primary">Add New</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title">Packaging Unit</h5>
                                                <a href="#" class="btn btn-sm btn-primary">Add New</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title">Product Unit</h5>
                                                <a href="#" class="btn btn-sm btn-primary">Add New</a>
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
        <div class="modal fade" id="DistributorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header d-flex">
                        <h5 class="modal-title" id="exampleModalLongTitle">Search Distributor : &nbsp;</h5>
                        <input type="search" class="form-control form-control-sm w-50" placeholder="" aria-controls="dataTable">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end distributor modal  -->
        <!-- Manufacturer View and Edit Modal -->
        <div class="modal fade" id="add-distributor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <!--/end Manufacturer View and Edit Modal -->

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
</body>

</html>