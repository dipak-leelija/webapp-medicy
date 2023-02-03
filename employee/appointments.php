<?php
  include 'config/dbconnect.php';
  require_once '../php_control/appoinments.class.php';

session_start();
if 
(!isset($_SESSION['employee_username']) || $_SESSION['loggedin'] != true){
  header("location: config/login.php");
  exit();
}

$empUsername = $_SESSION['employee_username'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />

    <!-- For Data Table Only -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Datatable - srtdash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- <link rel="stylesheet" href="../css/bootstrap 4/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="appointments-assets/css/font-awesome.min.css"> -->
    <!-- <link rel="stylesheet" href="appointments-assets/css/themify-icons.css"> -->
    <!-- <link rel="stylesheet" href="appointments-assets/css/metisMenu.css"> -->
    <!-- <link rel="stylesheet" href="appointments-assets/css/owl.carousel.min.css"> -->
    <!-- <link rel="stylesheet" href="appointments-assets/css/slicknav.min.css"> -->
    <!-- amcharts css -->
    <!-- <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" /> -->
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="appointments-assets/css/typography.css">
    <link rel="stylesheet" href="appointments-assets/css/default-css.css">
    <link rel="stylesheet" href="appointments-assets/css/data-table-styles.css">
    <link rel="stylesheet" href="appointments-assets/css/responsive.css">
    <!-- modernizr css -->
    <script src="appointments-assets/js/vendor/modernizr-2.8.3.min.js"></script>
    <!-- For Data Table Files End -->

</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <?php include 'partials/_navbar.php' ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <?php include 'partials/_sidebar.php' ?>
            <!-- partial:partials/_sidebar.html -->

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                                <i class="mdi mdi-home"></i>
                            </span> Dashboard
                        </h3>
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
                                    <span class="btn btn-primary" data-toggle="modal" data-target="#selectionModal">Book
                                        Appointment</span>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- Primary table start -->
                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Data Table Primary</h4>
                                <div class="data-tables datatable-primary">
                                    <table id="dataTable2" class="text-center">
                                        <thead class="text-capitalize">

                                            <tr>
                                                <th>Appointment ID</th>
                                                <th>Appointment Date</th>
                                                <th>Patient Name</th>
                                                <th>Doctor</th>
                                                <th>Action</th>
                                                <th>Print</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                      $appointments = new Appointments();
                                      $allAppointments = $appointments->appointmentsDisplay();
                                      foreach($allAppointments as $appointmentDetails){
                                        $appointmentID = $appointmentDetails['appointment_id'];
                                        $appointmentDate = $appointmentDetails['appointment_date'];
                                        $appointmentName = $appointmentDetails['patient_name'];
                                        $appointmentWith = $appointmentDetails['doctor_id'];
                                      
                                            $deleteAppointmentLink = "delete-appointment.php?delete-appointment=$appointmentID";
                                            $updateAppointmentLink = "update-appointment.php?update-prescription=$appointmentID";
                                            $prescriptionViewLink = "prescription.php?prescription=$appointmentID";
                                            echo"
                                            <tr>
                                              <td>$appointmentID</td>
                                              <td>".$appointmentDate."</td>
                                              <td>".$appointmentName."</td>
                                              <td>".$appointmentWith."</td>
                                              <td>
                                                <a href='$updateAppointmentLink'><span class='material-icons'>visibility</span></a>
                                                <a href='$deleteAppointmentLink'><span class='material-icons ms-2' onclick='return confirmation()'>delete</span></a>
                                              </td>
                                              <td>
                                                <a href='$prescriptionViewLink'><span class='material-icons'>print</span></a>
                                              </td>
                                              <td></td>
                                          </tr>";
                                          }
                                    ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Selection Modal -->
                <div class="modal fade" id="selectionModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body d-flex justify-content-around">
                                <a href="patient-registration.php" class="btn btn-info" href="">New Patient</a>
                                or
                                <a href="patient-selectioin.php" class="btn btn-info" href="">Returning Patient</a>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/end Selection Modal -->

                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <?php include 'partials/_footer.html'?>
                <!-- partial -->

            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script type="text/javascript">
    function confirmation() {
        return confirm('Are you sure you want to do this?');
    }
    </script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- End custom js for this page -->

    <!-- Data Table Files Start -->

    <!-- offset area end -->
    <!-- jquery latest version -->
    <script src="appointments-assets/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="appointments-assets/js/popper.min.js"></script>
    <script src="appointments-assets/js/bootstrap.min.js"></script>
    <script src="appointments-assets/js/owl.carousel.min.js"></script>
    <script src="appointments-assets/js/metisMenu.min.js"></script>
    <script src="appointments-assets/js/jquery.slimscroll.min.js"></script>
    <script src="appointments-assets/js/jquery.slicknav.min.js"></script>

    <!-- Start datatable js -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <!-- Data Table Files End -->

    <!-- others plugins -->
    <script src="appointments-assets/js/plugins.js"></script>
    <script src="appointments-assets/js/scripts.js"></script>

    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>

</body>

</html>