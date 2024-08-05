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
$searchVal = '';
$match = '';
$startDate = '';
$endDate = '';
$docId = '';
$empId = '';


if (isset($_GET['search']) || isset($_GET['dateFilterStart']) || isset($_GET['dateFilterEnd']) || isset($_GET['docIdFilter']) || isset($_GET['staffIdFilter'])) {

    if (isset($_GET['search'])) {
        $searchVal = $match = $_GET['search'];
    }

    if (isset($_GET['dateFilterStart'])) {
        $startDate = $_GET['dateFilterStart'];
        $endDate = $_GET['dateFilterEnd'];
    }

    if (isset($_GET['docIdFilter'])) {
        $docId = $_GET['docIdFilter'];
    }

    if (isset($_GET['staffIdFilter'])) {
        $empId = $_GET['staffIdFilter'];
    }

    $allAppointments = $Appoinments->appointmentsDataSearchFilter($searchVal, $startDate, $endDate, $docId, $empId, $adminId);
    $allAppointments = json_decode($allAppointments);
    // print_r($allAppointments);
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
    <link href="<?php echo CSS_PATH ?>sb-admin-2.css" rel="stylesheet">

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

                    <?php include ROOT_COMPONENT . "drugPermitDataAlert.php"; ?>

                    <!-- DataTales Example -->
                    <div class="card shadow-sm mb-4">

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
                                <label class="d-none" id="parent-url"><?php echo URL; ?></label>
                                <div class="d-flex">
                                    <div class="col-md-6 col-6 mt-2">
                                        <div class="input-group">
                                            <input class="cvx-inp" type="text" placeholder="Appointment ID / Patient Id / Patient Name" name="appointment-search" id="search-by-id-name-contact" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>" /*onkeyup="filterAppointmentByValue()" * />

                                            <div class="input-group-append" id="appointment-search-filter-1">
                                                <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon" onclick="filterAppointmentByValue()"><i class="fas fa-search"></i></button>
                                            </div>

                                            <!-- <div class="d-none input-group-append" > -->
                                            <button class=" d-none btn btn-sm btn-outline-primary shadow-none input-group-append" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                            <!-- </div> -->
                                        </div>
                                    </div>


                                    <div class="d-flex col-md-6 col-6  mt-2">
                                        <select class="cvx-inp1" name="added_on" id="added_on" onchange="filterAppointmentByValue()">
                                            <option value="" disabled selected>Select Duration</option>
                                            <option value="T">Today</option>
                                            <option value="Y">yesterday</option>
                                            <option value="LW">Last 7 Days</option>
                                            <option value="LM">Last 30 Days</option>
                                            <option value="LQ">Last 90 Days</option>
                                            <option value="CFY">Current Fiscal Year</option>
                                            <option value="PFY">Previous Fiscal Year</option>
                                            <option value="CR">Custom Range </option>
                                        </select>
                                        <button class="d-none btn btn-sm btn-outline-primary rounded-0 shadow-none" type="button" id="filter-reset-2" onclick="resteUrl(this.id)" style="margin-left: -26px; z-index: 100; background: white;"><i class="fas fa-times"></i></button>

                                        <label class="d-none" id="select-start-date"><?php echo $startDate; ?></label>
                                        <label class="d-none" id="select-end-date"><?php echo $endDate; ?></label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="col-md-6 col-6 mt-2 d-flex">
                                        <select class="col-md-11 cvx-inp1" name="doctor-filter" id="doctor_id" onchange="filterAppointmentByValue()">
                                            <option value="" selected disabled>Find By Doctor</option>

                                            <?php

                                            foreach ($doctorList as $doctorList) {
                                                echo '<option value="' . $doctorList->doctor_id . '">' . $doctorList->doctor_name . '</option>';
                                            }

                                            ?>
                                        </select>
                                        <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="filter-reset-3" style="margin-left: -26px; z-index: 100; background: white;" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>

                                        <label class="d-none" id="select-docId"><?php echo $docId; ?></label>
                                    </div>

                                    <div class="col-md-6 col-6 mt-2 d-flex">
                                        <select class="cl-md-11 cvx-inp1" id="added_by" onchange="filterAppointmentByValue()">
                                            <option value="" disabled="" selected="">Select Staff</option>

                                            <?php

                                            foreach ($employeeDetails as $employeeData) {
                                                $empName = $employeeData->fname.' '.$employeeData->lname;
                                                echo '<option value="' . $employeeData->emp_id . '">' . $empName . '</option>';
                                            }

                                            ?>
                                        </select>
                                        <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="filter-reset-4" style="margin-left: -26px; z-index: 100; background: white;" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>

                                        <label class="d-none" id="select-empId"><?php echo $empId; ?></label>
                                    </div>
                                </div>
                            </div>

                            <label class="d-none" id="date-range-control-flag">0</label>
                            <label class="d-none" id="url-control-flag">0</label>
                            <div class="dropdown-menu  p-2 row ml-4 mt-2" id="dtPickerDiv" style="display: none; position: relative; background-color: rgba(255, 255, 255, 0.8);">
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
                                            <button class="btn btn-sm btn-primary" onclick="filterAppointmentByValue()">Find</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered sortable-table" id="appointments-dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Patient Name</th>
                                            <th>Contact</th>
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
                                                $appointmentTableID   = $showAppointDetails->id;
                                                $appointmentID        = $showAppointDetails->appointment_id;
                                                $appointmentDate      = date("d-m-Y", strtotime($showAppointDetails->appointment_date));
                                                $appointmentName      = $showAppointDetails->patient_name;
                                                $patientContact       = $showAppointDetails->patient_phno;
                                                $getDoctorForPatient  = $showAppointDetails->doctor_id;

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
                                                        <td>' . $patientContact . '</td>
                                                        <td>' . $docName . '</td>
                                                        <td>' . $appointmentDate . '</td>

                                                        <td>

                                                        
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-primary rounded dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-sliders-h"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <span class="dropdown-item cursor-pointer" data-toggle="modal" data-target=".AppointmntViewAndEdit" onclick="appointmentViewAndEditModal(' . $appointmentTableID . ')" title="View and Edit">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                View & Edit
                                                                </span>

                                                                
                                                                <a class="dropdown-item" onclick="openPrint(this.href); return false;" href="' . URL . 'prescription.php?prescription=' . url_enc($appointmentID) . '">
                                                                    <i class="fas fa-print"></i>
                                                                    Print Prescription
                                                                </a>
                                                        
                                                                <span class="dropdown-item cursor-pointer text-danger" data-id="' . $appointmentID . '">
                                                                <i class="far fa-trash-alt"></i>
                                                                Delete
                                                                </span>
                                                        
                                                            </div>
                                                        </div>

                                                        
                                                        




                                                        <a class="delete-btn" ></a>
                                                        </td>
                                                    </tr>';
                                            }
                                        }else {
                                            echo '<tr class="text-center"><td colspan="5">No Appointment Found!</td></tr>';
                                        }

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

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>
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

    <!-- custom scripts for appointments serarch and filter -->
    <script src="<?= JS_PATH ?>appointments.js"></script>
    <script src="<?= JS_PATH ?>polyclinic-searchFilter.js"></script>


</body>

</html>