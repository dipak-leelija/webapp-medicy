<?php
require_once __DIR__.'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not
require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'appoinments.class.php';
require_once CLASS_DIR.'pagination.class.php';
require_once CLASS_DIR.'doctors.class.php';

$page = "appointments";

$Appoinments = new Appointments();
$Pagination  = new Pagination;
$Doctors     = new Doctors();

$allAppointments = $Appoinments->appointmentsDisplay($adminId);
$response = json_decode($Pagination->arrayPagination($allAppointments));

$slicedAppointments = '';
$paginationHTML     = '';

if ($response->status == 1) {
    $slicedAppointments = $response->items;
    $paginationHTML = $response->paginationHTML;
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

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 booked_btn">
                            <h6 class="m-0 font-weight-bold text-primary">Booked Appointments</h6>
                            <a data-toggle="modal" data-target="#appointmentSelection"><button class="btn btn-primary" ><i class="fas fa-edit"></i>Entry</button></a>
                        </div> 
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered sortable-table" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Patient Name</th>
                                            <th>Assigned Doctor</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if (!empty($slicedAppointments)) {
                                        
                                            foreach($slicedAppointments as $showAppointDetails){
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
                                                    if($showDoctorsForPatient != NULL){

                                                        foreach ($showDoctorsForPatient as $rowDoc) {
                                                            $docName = $rowDoc['doctor_name'];
                                                            // echo $docName.'<br><br>';
                                                        }
                                                    } else {
                                                        $docName = '';
                                                    }
                                            
                                                echo '<tr>
                                                        
                                                        <td>'.$appointmentID.'</td>
                                                        <td>'.$appointmentName.'</td>
                                                        <td>'.$docName.'</td>
                                                        <td>'.$appointmentDate.'</td>

                                                        <td><a class="text-primary" data-toggle="modal" data-target=".AppointmntViewAndEdit" onclick="appointmentViewAndEditModal('.$appointmentTableID.')" title="View and Edit"><i class="far fa-edit"></i></a>

                                                        <a href="prescription.php?prescription='.$appointmentID.'" class="text-primary" title="View and Print"><i class="fas fa-print"></i></a>
                                                        </td>

                                                        <td><a class="delete-btn" data-id="'.$appointmentID.'"  title="Delete"><i class="far fa-trash-alt"></i></a></td>
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
            <?php include ROOT_COMPONENT.'footer-text.php'; ?>
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
    <div class="modal fade" id="appointmentSelection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Patient Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-around">
                    <a href="add-patient.php" class="btn btn-info" href="">New Patient</a>
                    or
                    <a href="patient-selection.php" class="btn btn-info" href="">Returning Patient</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--/end Select Appointment Type Modal  -->

    <!-- View & Edit Appointment Modal -->
    <div class="modal fade AppointmntViewAndEdit" tabindex="-1" role="dialog"
        aria-labelledby="AppointmntViewAndEditLabel" aria-hidden="true">
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
        // let appointmentId = appointmentId;
        // alert(appointmentTableID);
        let url = "ajax/appointment.view.ajax.php?appointmentTableID=" + appointmentTableID;
        $(".view-and-edit-appointments").html(
            '<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
            url + '"></iframe>');

    } // end of LabCategoryEditModal function
    </script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

</body>

</html>