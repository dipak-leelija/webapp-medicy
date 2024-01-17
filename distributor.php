<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'distributor.class.php';


//Class Initilizing
$Distributor = new Distributor();

$showDistributor = json_decode($Distributor->showDistributor());
$showDistributor = $showDistributor->data;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Distributor of <?= $healthCareName ?> | <?= SITE_NAME ?> </title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <link href="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


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

                    <div class="row">

                        <!-- Show Distributor -->
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-end">
                                    <button class="btn btn-sm btn-primary shadow-none" data-toggle="modal" data-target="#add-distributor" onclick="addDistributor()">
                                        Add new
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>SL.</th>
                                                    <th>Name</th>
                                                    <th>Contact</th>
                                                    <th>Area PIN</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (is_array($showDistributor)) {
                                                    foreach ($showDistributor as $rowDistributor) {
                                                        $distributorId      = $rowDistributor->id;
                                                        $distributorName    = $rowDistributor->name;
                                                        $distributorPhno    = $rowDistributor->phno;
                                                        $distributorPin     = $rowDistributor->area_pin_code;
                                                        $distributorStatus  = $rowDistributor->dis_status;

                                                        $statusLabel = '';
                                                        $statusColor = '';
                                                        switch ($distributorStatus) {
                                                            case 0:
                                                                $statusLabel = 'Disabled';
                                                                $statusColor = 'red';
                                                                break;
                                                            case 1:
                                                                $statusLabel = 'Pending';
                                                                $statusColor = '#4e73df';
                                                                break;
                                                            case 2:
                                                                $statusLabel = 'Active';
                                                                $statusColor = 'green';
                                                                break;
                                                            default:
                                                                $statusLabel = 'Disabled';
                                                                break;
                                                        }
                                                        echo '<tr>
                                                                <td>' . $distributorId . '</td>
                                                                <td>' . $distributorName . '</td>
                                                                <td>' . $distributorPhno . '</td>
                                                                <td>' . $distributorPin . '</td>
                                                                <td style="color: ' . $statusColor . ';">' . $statusLabel . '</td>
                                                                <td>
                                                                    <a class="mx-1" data-toggle="modal" data-target="#distributorModal" onclick="distViewAndEdit(' . $distributorId . ')"><i class="fas fa-edit"></i></a>

                                                                    <a class="mx-1" id="delete-btn" data-id="' . $distributorId . '"><i class="far fa-trash-alt"></i></a>
                                                                </td>
                                                               </tr>';
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /end Show Distributor -->

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

    <!-- Manufacturer View and Edit Modal -->
    <div class="modal fade" id="distributorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View and Edit Distributor Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body distributorModal">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
            </div>
        </div>
    </div>
    <!--/end Manufacturer View and Edit Modal -->


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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= JS_PATH ?>demo/datatables-demo.js"></script>

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


        //View and Edit Manufacturer function
        distViewAndEdit = (distributorId) => {
            let ViewAndEdit = distributorId;
            let url = "ajax/distributor.View.ajax.php?Id=" + ViewAndEdit;
            $(".distributorModal").html(
                '<iframe width="99%" height="530px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function



        //delete distributor
        $(document).ready(function() {
            $(document).on("click", "#delete-btn", function() {

                swal({
                        title: "Are you sure?",
                        text: "Want to Delete This Distributor?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {

                            distributorId = $(this).data("id");
                            btn = this;

                            $.ajax({
                                url: "ajax/distributor.Delete.ajax.php",
                                type: "POST",
                                data: {
                                    id: distributorId
                                },
                                success: function(data) {
                                    if (data == 1) {
                                        $(btn).closest("tr").fadeOut()
                                        swal("Deleted", "Distributor Has Been Deleted",
                                            "success");
                                    } else {
                                        swal("Failed", data, "error");
                                    }
                                }
                            });

                        }
                        return false;
                    });

            })

        })
    </script>


</body>

</html>