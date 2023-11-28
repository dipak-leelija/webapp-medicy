<?php
$page = "appointments";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'employee.class.php';


$Appoinments = new Appointments();
$Pagination  = new Pagination;
$Doctors     = new Doctors();
$Employees   = new Employees;

$allAppointments = $Appoinments->appointmentsDisplay($adminId);
$allAppointments = json_decode($allAppointments);
// print_r($allAppointments);

if ($allAppointments->status) {
    if ($allAppointments->data != '') {
        $allAppointmentsData = $allAppointments->data;

        if (is_array($allAppointmentsData)) {
            // print_r($allAppointmentsData);
            $response = json_decode($Pagination->arrayPagination($allAppointmentsData));
            
            $slicedAppointments = '';
            $paginationHTML = '';
            $totalItem = $slicedAppointments = $response->totalitem;

            if ($response->status == 1) {
                $slicedAppointments = $response->items;
                $paginationHTML = $response->paginationHTML;
            }
        } else {
            $totalItem = 0;
        }
    } 
} else {
    $totalItem = 0;
    $paginationHTML = '';
}


$col = 'admin_id';
$employeeDetails = $Employees->selectEmpByCol($col, $adminId);
$employeeDetails = json_decode($employeeDetails);

if($employeeDetails->status){
    $employeeDetails = $employeeDetails->data;
}else{
    $employeeDetails = array();
}

$doctorDetails = $Doctors->showDoctors($adminId);
$doctorDetails = json_decode($doctorDetails);
// print_r($doctorDetails);

if($doctorDetails->status){
    $doctorList = $doctorDetails->data;
}else{
    $doctorList = array();
}

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
                        <div class="card-header py-3 align-items-center booked_btn">
                            <h6 class="m-0 font-weight-bold text-primary">Total Appointments: <?= $totalItem ?></h6>

                            <div class="row mt-2">
                                <div class="col-md-2 col-6">
                                    <input class="cvx-inp" type="text" placeholder="Appointment ID / Patient Name" name="appointment-search" id="appointment-search" style="outline: none;" onkeyup="filterAppointment(this)">
                                </div>

                                <div class="col-md-3 col-12">
                                    <select class="cvx-inp1" name="added-on" id="added-on" onchange="filterAppointment(this)">
                                        <option value="" disabled="" selected="">Select Duration</option>
                                        <option value="T">Today</option>
                                        <option value="Y">yesterday</option>
                                        <option value="LW">Last 7 Days</option>
                                        <option value="LM">Last 30 Days</option>
                                        <option value="LQ">Last 90 Days</option>
                                        <option value="CFY">Current Fiscal Year</option>
                                        <option value="PFY">Previous Fiscal Year</option>
                                        <option value="CR">Custom Range </option>
                                    </select>

                                </div>
                                <div class="col-md-2 col-6">
                                    <select class="cvx-inp1" name="doctor-filter" id="doctor-filter" onchange="filterAppointment(this)">
                                        <option value="" selected="" disabled="">Find By Doctor</option>

                                        <?php
                                            
                                            foreach($doctorList as $doctorList){
                                                echo '<option value="'.$doctorList->doctor_id.'">'.$doctorList->doctor_name.'</option>';
                                            }

                                        ?>

                                    </select>
                                </div>
                                <div class="col-md-2 col-6">
                                    <select class="cvx-inp1" id="added-by" onchange="filterAppointment(this)">
                                        <option value="" disabled="" selected="">Select Staff</option>

                                        <?php
                                            
                                            foreach($employeeDetails as $employeeData){
                                                echo '<option value="'.$employeeData->emp_id.'">'.$employeeData->emp_name.'</option>';
                                            }

                                        ?>
                                        
                                    </select>
                                </div>

                                <div class="col-md-2 col-6">
                                    <select class="cvx-inp1" name="payment-mode" id="payment-mode" onchange="filterAppointment(this)">
                                        <option value="" selected="" disabled="">payment Mode</option>
                                        <option value="Credit">Credit</option>
                                        <option value="Cash">Cash</option>
                                        <option value="UPI">UPI</option>
                                        <option value="Paypal">Paypal</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Debit Card">Debit Card</option>
                                        <option value="Net Banking">Net Banking</option>
                                    </select>
                                </div>

                                <div class="col-md-1 col-6 text-right">
                                    <a class="btn btn-sm btn-primary " data-toggle="modal" data-target="#appointmentSelection">
                                        <i class="fas fa-edit"></i>Entry
                                    </a>
                                </div>
                            </div>


                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered sortable-table" id="appointments-dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Patient Name</th>
                                            <th>Assigned Doctor</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($slicedAppointments)) {
                                            // print_r($slicedAppointments);
                                            foreach ($slicedAppointments as $showAppointDetails) {
                                                $appointmentTableID = $showAppointDetails->id;
                                                $appointmentID = $showAppointDetails->appointment_id;
                                                $appointmentDate = date("d-m-Y", strtotime($showAppointDetails->appointment_date));
                                                $appointmentName = $showAppointDetails->patient_name;
                                                $getDoctorForPatient = $showAppointDetails->doctor_id;

                                                $deleteAppointmentLink = "delete-appointment.php?delete-appointment=$appointmentID";
                                                $updateAppointmentLink = "update-appointment.php?update-prescription=$appointmentID";
                                                $prescriptionViewLink = "prescription.php?prescription=$appointmentID";

                                                $showDoctorsForPatient = $Doctors->showDoctorsForPatient($getDoctorForPatient);

                                                // echo $appointmentTableID.'<br>';
                                                if ($showDoctorsForPatient != NULL) {

                                                    foreach ($showDoctorsForPatient as $rowDoc) {
                                                        $docName = $rowDoc['doctor_name'];
                                                        // echo $docName.'<br><br>';
                                                    }
                                                } else {
                                                    $docName = '';
                                                }

                                                echo '<tr>
                                                        
                                                        <td>' . $appointmentID . '</td>
                                                        <td>' . $appointmentName . '</td>
                                                        <td>' . $docName . '</td>
                                                        <td>' . $appointmentDate . '</td>

                                                        <td><a class="text-primary" data-toggle="modal" data-target=".AppointmntViewAndEdit" onclick="appointmentViewAndEditModal(' . $appointmentTableID . ')" title="View and Edit"><i class="far fa-edit"></i></a>


                                                        <a href="prescription.php?prescription=' . $appointmentID . '" class="text-primary" title="View and Print"><i class="fas fa-print"></i></a>


                                                        <a class="delete-btn" data-id="' . $appointmentID . '"  title="Delete"><i class="far fa-trash-alt"></i></a>
                                                        </td>
                                                    </tr>';
                                            }
                                        }
                                        // href="ajax/appointment.delete.ajax.php?appointmentId='.$appointmentID.'"
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                <?= $paginationHTML ?>
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

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Select Appointment Type Modal  -->
    <div class="modal fade" id="appointmentSelection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog centered" role="document">
            <div class="modal-content">
                <div class="modal-body d-flex justify-content-around align-items-center py-4">
                    <a href="add-patient.php" class="btn btn-info" href="">New Patient</a>
                    OR
                    <a href="patient-selection.php" class="btn btn-info" href="">Returning Patient</a>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--/end Select Appointment Type Modal  -->

    <!-- View & Edit Appointment Modal -->
    <div class="modal fade AppointmntViewAndEdit" tabindex="-1" role="dialog" aria-labelledby="AppointmntViewAndEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body view-and-edit-appointments">
                    <!-- Appointments Details Goes Here By Ajax -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload()">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of View & Edit Appointment Modal -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <script>
        $(document).ready(function() {
            $(document).on("click", ".delete-btn", function() {

                if (confirm("Are You Sure?")) {
                    apntID = $(this).data("id");
                    btn = this;

                    $.ajax({
                        url: "ajax/appointment.delete.ajax.php",
                        type: "POST",
                        data: {
                            id: apntID
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
            })

        })
    </script>
    <script>
        appointmentViewAndEditModal = (appointmentTableID) => {
            
            let url = "ajax/appointment.view.ajax.php?appointmentTableID=" + appointmentTableID;
            $(".view-and-edit-appointments").html(
                '<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');

        } // end of LabCategoryEditModal function

    </script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="<?= JS_PATH ?>filter.js"></script>


</body>

</html>