<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php';//check admin loggedin or not
$page = "patients";

require_once CLASS_DIR.'dbconnect.php';
require_once ADM_DIR.'_config/user-details.inc.php';
require_once CLASS_DIR.'encrypt.inc.php';
require_once CLASS_DIR.'patients.class.php';

$Patients   = new Patients;
$allPatients = $Patients->allPatients($adminId);
$allPatients = json_decode($allPatients);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Patients - <?= COMPANY_S?></title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include PORTAL_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include PORTAL_COMPONENT.'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">List of Patients</h6>
                            <a data-toggle="modal" data-target="#appointmentSelection"><button
                                    class="btn btn-sm btn-primary"><i class="fas fa-edit"></i>Add New</button></a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Patient ID</th>
                                            <th>Patient Name</th>
                                            <th>Age</th>
                                            <th>Contact</th>
                                            <th>Visits</th>
                                            <th>Area PIN</th>
                                            <th class="text-center">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allPatients as $eachPatient) {?>
                                        <tr>
                                            <td><?= $eachPatient->patient_id ?></td>
                                            <td><?= $eachPatient->name ?></td>
                                            <td><?= $eachPatient->age ?></td>
                                            <td><a class="text-decoration-none" href="tel:<?= $eachPatient->phno ?>"><?= $eachPatient->phno ?></a></td>
                                            <td class="align-middle pb-0 pt-0">
                                                <small class="small">
                                                    <span>Doctor: <?= $eachPatient->visited ?></span>
                                                    <br>
                                                    <span>Lab: <?= $eachPatient->lab_visited ?></span></small>
                                            </td>
                                            <td><?= $eachPatient->patient_pin ?></td>

                                            <td class="text-center">
                                                <a class="text-primary" href="patient-details.php?patient=<?= url_enc($eachPatient->patient_id) ?>"
                                                    title="View and Edit"><i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } ?>
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
            <?php include PORTAL_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>