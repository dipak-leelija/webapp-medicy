<?php
$page = "doctors";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not


require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


//Intitilizing Doctor class for fetching doctors
$doctors        = new Doctors();
$DoctorCategory = new DoctorCategory;

$showDoctors = $doctors->showDoctors($adminId);
$showDoctors = json_decode($showDoctors, true);
// print_r($showDoctors);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Doctors</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/doctors.css">
    <link href="<?php echo CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">

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
                    
                    <!-- Page Heading -->
                    <!-- <h1 class="h3 mb-2 text-gray-800">Doctors</h1> -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="d-flex">
                            <div class="card-header py-3 col-md-6">
                                <h6 class="m-0 font-weight-bold text-primary">Doctors</h6>
                            </div>
                            <div class="card-header py-3 col-md-6 d-flex justify-content-end">
                                <div class="d-md-flex justify-content-md-end">
                                    <button class="btn btn-success" name="add-new-doc-data" id="add-new-doc-data" data-toggle="modal" data-target="#addDoctorDataModal" onclick="addDoctor()">Add New</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Specialization</th>
                                            <th>PH. No</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($showDoctors && isset($showDoctors['status']) && $showDoctors['status'] == 1) {
                                            $showDoctors = $showDoctors['data'];
                                            foreach ($showDoctors as $doctors) {
                                                $docId              = $doctors['doctor_id'];
                                                $docRegNo           = $doctors['doctor_reg_no'];
                                                $docName            = $doctors['doctor_name'];
                                                $docSpecialization  = $doctors['doctor_specialization'];
                                                $docDeg             = $doctors['doctor_degree'];
                                                $docAlsoWith        = $doctors['also_with'];
                                                $docAddrs           = $doctors['doctor_address'];
                                                $docEmail           = $doctors['doctor_email'];
                                                $docPhno            = $doctors['doctor_phno'];

                                                //initilizing Doctors Category
                                                $docSplz = $DoctorCategory->showDoctorCategoryById($docSpecialization);
                                                $docSplz = json_decode($docSplz, true);
                                                if ($docSplz && $docSplz['status'] == 1 && !empty($docSplz))
                                                    foreach ($docSplz['data'] as $docSplzShow) {
                                                        $docSpecializn = $docSplzShow['category_name'];

                                                        echo '<tr>
                                                        <td>' . $docId . '</td>
                                                        <td>' . $docName . '</td>
                                                        <td>' . $docSpecializn . '</td>
                                                        <td>' . $docPhno . '</td>
                                                        <td>' . $docEmail . '</td>
                                                        <td>
                                                        <a href="dr-prescription.php?prescription=' . url_enc($docId) . '" class="text-primary" title="View and Print"><i class="fas fa-print"></i></a>
                                                        
                                                        <a class="" data-toggle="modal" data-target="#docViewAndEditModal" onclick="docViewAndEdit(' . $docId . ')"><i class="fas fa-edit"></i></a>
                                                        
                                                        <a class="delete-btn" data-id="' . $docId . '"  title="Delete"><i class="far fa-trash-alt"></i></a>
    
                                                        
                                                            </td>
                                                    </tr>';
                                                    }
                                            }
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
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
        <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

        <!-- add doctor Modal -->
        <div class="modal fade" id="addDoctorDataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Doctor Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="refreshPage()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body addDoctorDataModal">
                        <!-- Doctors Details Will Appeare Here By AJAX -->
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refreshPage()">Ok</button>
                        <button type="button" class="btn btn-primary" onclick="refreshPage()">Update</button> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End of add Doctor Modal -->

        <!-- Doctor View and Edit Modal -->
        <div class="modal fade" id="docViewAndEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Doctor Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="refreshPage()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body docViewAndEditModal">
                        <!-- Doctors Details Will Appeare Here By AJAX -->
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refreshPage()">Ok</button>
                        <button type="button" class="btn btn-primary" onclick="refreshPage()">Update</button> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Doctor View and Edit Modal -->

        <!-- Bootstrap core JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

        <!-- Custom Javascript  -->
        <script src="<?php echo JS_PATH ?>custom-js.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="<?php echo PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="<?php echo JS_PATH ?>demo/datatables-demo.js"></script>
        <script src="<?php echo JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

        <!-- custom js for custom script -->
        <script src="<?php echo JS_PATH ?>doctors.js"></script>


</body>

</html>