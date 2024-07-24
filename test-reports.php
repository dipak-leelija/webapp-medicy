<?php
$page = "patients";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not


require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'PathologyReport.class.php';
require_once CLASS_DIR . 'utility.class.php';

$Patients        = new Patients;
$PathologyReport = new PathologyReport;

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Patients - <?= SITE_NAME ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">List of Report</h6>
                            <a data-toggle="modal" data-target="#appointmentSelection"><button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i>Add New</button></a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Report ID</th>
                                            <th>Invoice</th>
                                            <th>Patient Name</th>
                                            <th>Admin ID</th>
                                            <th>Date</th>
                                            <th>Prepared By</th>
                                            <th class="text-center">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $labreportfetch = $PathologyReport->testReportFetch($adminId);
                                        $labreportfetch = json_decode($labreportfetch, true);
                                        if ($labreportfetch) {
                                            foreach ($labreportfetch as $entry) {
                                                $reportId   = $entry['id'];
                                                $billId     = $entry['bill_id'];
                                                $adminId    = $entry['admin_id'];
                                                $date       = $entry['added_on'];
                                        ?>
                                                <tr class="appointment-row">
                                                    <td><?= $reportId ?></td>
                                                    <td> <a href="<?=URL ?>test-appointments.php?&search=<?= $billId ?>">#<?= $billId ?></a></td>
                                                    <td></td>
                                                    <td><?= $adminId ?></td>
                                                    <td><?= formatDateTime($date, '-') ?></td>
                                                    <td></td>
                                                    <td class="text-center">
                                                        <!-- <a title="show" href="test-report-show.php?id=<?= $reportId ?>"><i class="fa fa-eye" aria-hidden="true"></i></a> -->
                                                        <a title="show" onclick="openPrint(this.href); return false;" href="invoices/print.php?name=report&id=<?= $reportId ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                        <?php

                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <?php include ROOT_COMPONENT . 'footer-text.php'; ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <!-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a> -->
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>;


    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="<?php echo PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="<?php echo JS_PATH ?>demo/datatables-demo.js"></script>
    <script src="<?php echo JS_PATH ?>/main.js"></script>
</body>

</html>