<?php
$page = "appointments";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not


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


// ================ EMPLOYEES DATA ==================
$col = 'admin_id';
$employeeDetails = json_decode($Employees->selectEmpByCol($col, $adminId));

if ($employeeDetails->status) {
    $employeeDetails = $employeeDetails->data;
} else {
    $employeeDetails = array();
}


// ============ DOCTOR LIST ====================
$doctorDetails = $Doctors->showDoctors($adminId);
$doctorDetails = json_decode($doctorDetails);

if ($doctorDetails->status) {
    $doctorList = $doctorDetails->data;
} else {
    $doctorList = array();
}


// ============= APPOINTMENT DATA ================

if (isset($_GET['search'])) {

    $search = $_GET['search'];

    if ($search == 'appointment_search') {
        $flagVal = 1;
        $searchPattern = $match =  $_GET['searchKey'];
        $allAppointments = $Appoinments->filterAppointmentsByIdOrName($searchPattern, $adminId);
        $allAppointments = json_decode($allAppointments);
        // print_r($allAppointments);
    }
    
    if ($search == 'doctor_id') {
        $flagVal = 3;
        $doctorID = $_GET['searchKey'];
        $col = $_GET['search'];
        $allAppointments = $Appoinments->appointmentsFilter($col, $doctorID, $adminId);
        $allAppointments = json_decode($allAppointments);
    }

    if ($search == 'added_by') {
        $flagVal = 4;
        $doctorID = $_GET['searchKey'];
        $col = $_GET['search'];
        $allAppointments = $Appoinments->appointmentsFilter($col, $doctorID, $adminId);
        $allAppointments = json_decode($allAppointments);
    }

    if ($search == 'added_on') {
        $flagVal = 2;
        $value = $_GET['searchKey'];

        if ($value == 'T') {
            $fromDt = date('Y-m-d');
            $toDt = date('Y-m-d');
        }

        if ($value == 'Y') {
            $fromDt = new DateTime('yesterday');
            $fromDt = $fromDt->format('Y-m-d');
            $toDt = $fromDt;
        }

        if ($value == 'LW') {
            $fromDt = new DateTime('-7 days');
            $fromDt = $fromDt->format('Y-m-d');
            $toDt = date('Y-m-d');
        }

        if ($value == 'LM') {
            $fromDt = new DateTime('-30 days');
            $fromDt = $fromDt->format('Y-m-d');
            $toDt = date('Y-m-d');
        }

        if ($value == 'LQ') {
            $fromDt = new DateTime('-90 days');
            $fromDt = $fromDt->format('Y-m-d');
            $toDt = date('Y-m-d');
        }

        if ($value == 'CFY') {
            $currentYear = new DateTime();
            $fiscalYear = $currentYear->format('Y');
            $fiscalYear = intval($fiscalYear) + 1;
            $currentYear = $currentYear->format('Y');

            $fromDt = $currentYear . '-04-01';
            $toDt = $fiscalYear . '-03-31';
        }

        if ($value == 'PFY') {
            $currentYear = new DateTime();
            $prevFiscalYr = $currentYear->format('Y');
            $prevFiscalYr = intval($prevFiscalYr) - 1;
            $currentYear = $currentYear->format('Y');

            $fromDt = $prevFiscalYr . '-04-01';
            $toDt = $currentYear . '-03-31';
        }

        if ($value == 'CR') {
            $fromDt = $_GET['fromDt'];
            $toDt = $_GET['toDt'];
        }

        $allAppointments = $Appoinments->appointmentsFilterByDate($fromDt, $toDt, $adminId);
        $allAppointments = json_decode($allAppointments);
    }
} else {
    $flagVal = 0;

    $allAppointments = $Appoinments->appointmentsDisplay($adminId);
    $allAppointments = json_decode($allAppointments);
}


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
                    <div class="row" style="z-index: 999;">
                        <div class="col-12">
                            <?php include ROOT_COMPONENT . "drugPermitDataAlert.php"; ?>
                        </div>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3 justify-content-between">

                            <div class="col-12 d-flex justify-content-between">
                                <div class="">
                                    <h6 class="font-weight-bold text-primary">Total Appointments: <?= $totalItem ?></h6>
                                </div>
                                <div class="ml-4">
                                    <a class="btn btn-sm btn-primary" href='patient-selection.php'>
                                        <p class="m-0 p-0">Entry</p>
                                    </a>
                                    <!-- data-toggle="modal" data-target="#appointmentSelection" -->
                                </div>
                            </div>


                            <div class="row mt-2">
                                <label class="d-none" id="control-flag"><?= $flagVal; ?></label>
                                <div class="d-flex">
                                    <div class="col-md-6 col-6 mt-2">
                                        <div class="input-group">
                                            <input class="cvx-inp" type="text" placeholder="Appointment ID / Patient Id / Patient Name" name="appointment-search" id="appointment_search" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>">

                                            <div class="input-group-append" id="appointment-search-filter-1">
                                                <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon" onclick="filterAppointmentByValue(this)"><i class="fas fa-search"></i></button>
                                            </div>

                                            <div class="d-none input-group-append" id="appointment-search-reset-1">
                                                <button class="btn btn-sm btn-outline-primary shadow-none" type="button" onclick="resteUrl()"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="d-flex col-md-6 col-6  mt-2">
                                        <select class="cvx-inp1" name="added_on" id="added_on" onchange="filterAppointmentByValue(this)">
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
                                        <button class="d-none btn btn-sm btn-outline-primary rounded-0 shadow-none" type="button" id="appointment-search-reset-2" onclick="resteUrl()" style="margin-left: -26px; z-index: 100; background: white;"><i class="fas fa-times"></i></button>

                                    </div>
                                </div>

                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="col-md-6 col-6 mt-2 d-flex">
                                        <select class="col-md-11 cvx-inp1" name="doctor-filter" id="doctor_id" onchange="filterAppointmentByValue(this)">
                                            <option value="" selected="" disabled="">Find By Doctor</option>

                                            <?php

                                            foreach ($doctorList as $doctorList) {
                                                echo '<option value="' . $doctorList->doctor_id . '">' . $doctorList->doctor_name . '</option>';
                                            }

                                            ?>
                                        </select>
                                        <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="appointment-search-reset-3" style="margin-left: -26px; z-index: 100; background: white;"onclick="resteUrl()"><i class="fas fa-times"></i></button>
                                    </div>

                                    <div class="col-md-6 col-6 mt-2 d-flex">
                                        <select class="cl-md-11 cvx-inp1" id="added_by" onchange="filterAppointmentByValue(this)">
                                            <option value="" disabled="" selected="">Select Staff</option>

                                            <?php

                                            foreach ($employeeDetails as $employeeData) {
                                                echo '<option value="' . $employeeData->emp_id . '">' . $employeeData->emp_name . '</option>';
                                            }

                                            ?>
                                        </select>
                                        <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="appointment-search-reset-4" style="margin-left: -26px; z-index: 100; background: white;"onclick="resteUrl()"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-menu  p-2 row ml-4" id="dtPickerDiv" style="display: none; position: relative; background-color: rgba(255, 255, 255, 0.8);">
                                <div class=" col-md-12">
                                    <div class="d-flex">
                                        <div class="dtPicker" style="margin-right: 1rem;">
                                            <label>Strat Date</label>
                                            <input type="date" id="from-date" name="from-date">
                                        </div>
                                        <div class="dtPicker" style="margin-right: 1rem;">
                                            <label>End Date</label>
                                            <input type="date" id="to-date" name="to-date">
                                        </div>
                                        <div class="dtPicker">
                                            <button class="btn btn-sm btn-primary" onclick="customDate()">Find</button>
                                        </div>
                                    </div>
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


                                                        <a href="prescription.php?prescription=' . url_enc($appointmentID) . '" class="text-primary" title="View and Print"><i class="fas fa-print"></i></a>


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
                            <div class="d-flex justify-content-center" id="pagination-control">
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.reload()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body view-and-edit-appointments">
                    <!-- Appointments Details Goes Here By Ajax -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" onclick="window.location.reload()">Close</button>
                    <!-- <button type="button" class="btn btn-sm btn-primary">Save Changes</button> -->
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


    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <!-- <script src="<?= JS_PATH ?>filter.js"></script> -->



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

        // =======================================================
        appointmentViewAndEditModal = (appointmentTableID) => {

            let url = "ajax/appointment.view.ajax.php?appointmentTableID=" + appointmentTableID;
            $(".view-and-edit-appointments").html(
                '<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');

        } // end of LabCategoryEditModal function

        // ==========================================================

        const filterAppointmentByValue = (t) => {

            document.getElementById('dtPickerDiv').style.display = 'none';

            key = t.id;
            val = t.value;

            if (t.id == 'button-addon') {
                var key = document.getElementById("appointment_search").id;
                var val = document.getElementById("appointment_search").value;

                if (val.length < 2) {
                    console.log("min 3 char");
                }
            }

            if (val != 'CR') {
                var currentURL = window.location.href;

                // Get the current URL without the query string
                var currentURLWithoutQuery = window.location.origin + window.location.pathname;

                var newURL = `${currentURLWithoutQuery}?search=${key}&searchKey=${val}`;

                window.location.replace(newURL);
            }

            if (val == 'CR') {
                document.getElementById('dtPickerDiv').style.display = 'block';
            }
        }


        const customDate = () => {
            let fromDate = document.getElementById('from-date').value;
            let toDate = document.getElementById('to-date').value;

            //fetch current url and pathname
            var currentURLWithoutQuery = window.location.origin + window.location.pathname;
            // create new url with added value to previous url
            var newUrl = `${currentURLWithoutQuery}?search=${'added_on'}&searchKey=${'CR'}&fromDt=${fromDate}&toDt=${toDate}`;
            // replace previous url with new url
            window.location.replace(newUrl);
        }


        const resteUrl = (t) => {
            let url = '<?php echo URL ?>appointments.php';
            window.location.replace(url);
        }


        if (document.getElementById('control-flag').innerHTML.trim() === '1') {
            document.getElementById('button-addon').classList.add('d-none');
            document.getElementById('appointment-search-reset-1').classList.remove('d-none');
        }

        if (document.getElementById('control-flag').innerHTML.trim() === '2') {
            document.getElementById('appointment-search-reset-2').classList.remove('d-none');
        }

        if (document.getElementById('control-flag').innerHTML.trim() === '3') {
            document.getElementById('appointment-search-reset-3').classList.remove('d-none');
        }

        if (document.getElementById('control-flag').innerHTML.trim() === '4') {
            document.getElementById('appointment-search-reset-4').classList.remove('d-none');
        }
    </script>

</body>

</html>