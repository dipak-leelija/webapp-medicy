<?php
$page = "product-unit";
require_once 'config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'measureOfUnit.class.php';


//Class Initilization
$MeasureOfUnits = new MeasureOfUnits();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Measure of Unit - <?= $HEALTHCARENAME ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" type="text/css" />
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

                <!-- =========================== Measure of Units Content =========================== -->
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="card shadow mb-4">
                        <!-- Page Heading -->
                        <h1 class="h3 m-3 text-gray-800">Mesure of Unit</h1>
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
                                                        <th>Short Name</th>
                                                        <th>Full Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $showMeasureOfUnits = $MeasureOfUnits->showMeasureOfUnits();

                                                    if ($showMeasureOfUnits != NULL) {

                                                        foreach ($showMeasureOfUnits as $rowMeasureOfUnits) {
                                                            $unitId     = $rowMeasureOfUnits['id'];
                                                            $shortName  = $rowMeasureOfUnits['short_name'];
                                                            $fullName   = $rowMeasureOfUnits['full_name'];

                                                            echo '<tr>
                                                        <td>' . $unitId . '</td>
                                                                <td>' . $shortName . '</td>
                                                                <td>' . $fullName . '</td>
                                                                <td>
                                                                <a class="mx-1" data-toggle="modal" data-target="#unitModal" onclick="unitViewAndEdit(' . $unitId . ')"><i class="fas fa-edit"></i></a>
                                                                
                                                                    <a class="mx-1" id="delete-btn" data-id="' . $unitId . '"><i class="far fa-trash-alt"></i></a>
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

                            <div class="col-md-5">
                                <div class="card m-2">
                                    <div class="card-body">
                                        <form method="post" action="ajax/unit.add.ajax.php">

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="unit-srt-name">Short Name</Address></label>
                                                <input class="form-control" id="unit-srt-name" name="unit-srt-name" placeholder="Short Name of Unit" required>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label class="mb-0 mt-1" for="unit-full-name">Full Name</Address></label>
                                                <input type="text" class="form-control" id="unit-full-name" name="unit-full-name" placeholder="Full Name of Unit" required>
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
                <!-- =========================== End of Measure of Units Content =========================== -->

            </div>
            <!-- End of Main Content -->

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
            </div>
        </div>
    </div>
    <!--/end Manufacturer View and Edit Modal -->


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
        //View and Edit Manufacturer function
        unitViewAndEdit = (unitId) => {
            let ViewAndEdit = unitId;
            let url = "ajax/unit.View.ajax.php?Id=" + ViewAndEdit;
            $(".unitModal").html(
                '<iframe width="99%" height="250px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function


        //delete unit
        $(document).ready(function() {
            $(document).on("click", "#delete-btn", function() {
                manufacturerId = $(this).data("id");
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
                                url: "ajax/unit.Delete.ajax.php",
                                type: "POST",
                                data: {
                                    id: manufacturerId
                                },
                                success: function(data) {
                                    if (data == 1) {
                                        $(btn).closest("tr").fadeOut()
                                    } else {
                                        $("#error-message").html("Deletion Field !!!").slideDown();
                                        $("success-message").slideUp();
                                    }

                                }
                            });
                        }
                        return false;
                    });
            });

        });
    
//  =============on modal close page reload ===================== 

function pageReload(){
    parent.location.reload();
}

</script>
</body>

</html>