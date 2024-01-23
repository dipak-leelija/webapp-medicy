<?php
require_once 'config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'packagingUnit.class.php';

$page = "pack-unit";

//Class Initilization
$PackagingUnits = new PackagingUnits();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Measure of Unit - Medicy Health Care</title>

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
        <?php include ROOT_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT.'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- =========================== Packaging of Units Content =========================== -->
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="card shadow mb-4">
                        <!-- Page Heading -->
                        <h1 class="h3 m-3 text-gray-800">Packaging Units</h1>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="card m-2">
                                    <div class="card-body">
                                        <!-- Showing Unit Table -->
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>SL. No.</th>
                                                        <th>Unit Name</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $showPackagingUnits = $PackagingUnits->showPackagingUnits();
                                                    foreach ($showPackagingUnits as $rowPackagingUnits) {
                                                        $unitId     = $rowPackagingUnits['id'];
                                                        $unitName   = $rowPackagingUnits['unit_name'];
                                                        $packStatus = $rowPackagingUnits['status'];

                                                        $statusLabel = '';
                                                        $statusColor = '';
                                                        switch ($packStatus) {
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
                                                                <td>' . $unitId . '</td>
                                                                <td>' . $unitName . '</td>
                                                                <td style="color: ' . $statusColor . ';">' . $statusLabel . '</td>
                                                                <td>
                                                                    <a class="mx-1" data-toggle="modal" data-target="#unitModal" onclick="unitViewAndEdit(' . $unitId . ')"><i class="fas fa-edit"></i></a>
    
                                                                    <a class="mx-1" id="delete-btn" data-id="' . $unitId . '"><i class="far fa-trash-alt"></i></a>
                                                                </td>
                                                            </tr>';
                                                        }
                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="card m-2">
                                    <div class="card-body">
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
                    </div>
                </div>
                <!-- /.container-fluid -->
                <!-- =========================== End of Packaging of Units Content =========================== -->



            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include_once ROOT_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


    <!-- Manufacturer View and Edit Modal -->
    <div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-labelledby="unitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unitModalLabel">View and Edit Units</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="pageReload()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body unitModal">
                    <!-- Details Appeare Here by Ajax  -->
                </div>
                <!-- <div class="modal-footer"> -->
                <!-- <button type="button" class="btn btn-sm btn-primary my-0 py-0" data-dismiss="modal">Close</button> -->
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                <!-- </div> -->
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

    <!-- Sweet Alert Js  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

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
        //View and Edit Manufacturer function
        unitViewAndEdit = (unitId) => {
            let ViewAndEdit = unitId;
            let url = "ajax/packagingUnit.View.ajax.php?Id=" + ViewAndEdit;
            $(".unitModal").html(
                '<iframe width="99%" height="120rem" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function



        //delete unit

        $(document).ready(function() {
            $(document).on("click", "#delete-btn", function() {
                //if (confirm("Are You Sure want to delete?"))
                unitid = $(this).data("id");
                btn = this;
                swal({
                        title: "Are you sure?",
                        text: "Want to Delete This Manufacturer?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "ajax/packagingUnit.Delete.ajax.php",
                                type: "POST",
                                data: {
                                    id: unitid
                                },
                                success: function(response) {
                                    if (response.includes("1")) {
                                        $(btn).closest("tr").fadeOut()
                                    } else {
                                        $("#error-message").html("Deletion Field !!!").slideDown();
                                        $("success-message").slideUp();
                                    }

                                }
                            });

                        }
                        return false;
                    })
            });

        });


        //========================== on edit modal cloase page reload ======================
        function pageReload() {
            parent.location.reload();
        }
    </script>


</body>

</html>