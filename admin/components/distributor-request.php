<?php
require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once SUP_ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once SUP_ADM_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once SUP_ADM_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'distributor.class.php';


//Class Initilizing
$Distributor = new Distributor();


$showDistRequest  = json_decode($Distributor->showDistRequest());
$showDistRequest  = $showDistRequest->data;

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

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="row">

                        <!-- Show Distributor -->
                        <div class="col-12">
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
                                        if (is_array($showDistRequest)) {
                                            foreach ($showDistRequest  as $rowDistributor) {
                                                $distributorId      = $rowDistributor->dist_id;
                                                // print_r($distributorId);
                                                $distributorName    = $rowDistributor->name;
                                                $distributorPhno    = $rowDistributor->phno;
                                                $distributorPin     = $rowDistributor->area_pin_code;
                                                $distributorStatus  = $rowDistributor->status;

                                                $statusLabel = '';
                                                $statusColor = '';
                                                switch ($distributorStatus) {
                                                    case 2:
                                                        $statusLabel = 'Disabled';
                                                        $statusColor = 'red';
                                                        break;
                                                    case 0:
                                                        $statusLabel = 'Pending';
                                                        $statusColor = '#4e73df';
                                                        break;
                                                    case 1:
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
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle bg-white border-0 " type="button" id="statusDropdown' . $distributorId . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: ' . $statusColor . ';">
                                                                            ' . $statusLabel . '
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="statusDropdown' . $distributorId . '">
                                                                            <a class="dropdown-item" href="#" onclick="updateReqStatus(' . $distributorId . ', 2, this)">Disabled</a>
                                                                            <a class="dropdown-item" href="#" onclick="updateReqStatus(' . $distributorId . ', 0, this)">Pending</a>
                                                                            <a class="dropdown-item" href="#" onclick="updateReqStatus(' . $distributorId . ', 1, this)">Active</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <a class="mx-1" href="#" onclick="deleteReq(' . $distributorId . ')" ><i class="far fa-trash-alt"></i></a>
                                                                </td>
                                                               </tr>';
                                            }
                                        } else {
                                            echo '<tr class="odd">
                                            <td valign="top" colspan="6" class="dataTables_empty" style="text-align: center;">Distributor Request Not Found</td>
                                         </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /end Show Distributor -->

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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
        //View and Edit Manufacturer function
        distViewAndEdit = (distributorId) => {
            let ViewAndEdit = distributorId;
            let url = "ajax/distributor.View.ajax.php?Id=" + ViewAndEdit;
            $(".distributorModal").html(
                '<iframe width="99%" height="530px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function
    </script>


</body>

</html>