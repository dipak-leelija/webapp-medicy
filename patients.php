<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'pagination.class.php';
require_once CLASS_DIR . 'employee.class.php';

$Patients   = new Patients;
$Pagination = new Pagination;
$Employees  = new Employees;
// ============ STAFF LIST FETCHING SECTION ===========
$empCol = 'admin_id';
$employeeDetails = $Employees->selectEmpByCol($empCol, $adminId);
$employeeDetails = json_decode($employeeDetails);
$employeeDetails = $employeeDetails->data;


// ============== PATIENT DATA ===============
$searchVal = '';
$match = '';
$startDate = '';
$endDate = '';
$docId = '';
$empId = '';


if (isset($_GET['search']) || isset($_GET['dateFilterStart']) || isset($_GET['dateFilterEnd']) || isset($_GET['staffIdFilter'])) {

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

    $allPatients = $Patients->patientDataSearchFilter($searchVal, $startDate, $endDate, $empId, $adminId);
    // print_r($allAppointments);
} else {
    $allPatients = $Patients->allPatients($adminId);
}

$allPatients = json_decode($allPatients);

if ($allPatients->status) {
    if ($allPatients->data != '') {
        $allPatientsData = $allPatients->data;
        if (is_array($allPatientsData)) {
            $response = json_decode($Pagination->arrayPagination($allPatientsData));
            $slicedPatients = '';
            $paginationHTML = '';
            $totalItem = '';
            if (property_exists($response, 'totalitem'))
                $totalItem = $slicedPatients = $response->totalitem;

            if ($response->status == 1) {
                $slicedPatients = $response->items;
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

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Patients - <?= $HEALTHCARENAME ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/appointment.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/return-page.css" type="text/css">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css">


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
                    <div class="card shadow-sm mb-4">


                        <div class="card-header py-3 justify-content-between">

                            <div class="row">
                                <div class="col-3 col-md-3 mt-md-2">
                                    <h6 class="font-weight-bold text-primary">List of Patients : <?= $totalItem ?></h6>
                                </div>

                                <div class="col-2 col-md-3 mt-2">
                                    <div class="input-group">
                                        <input class="cvx-inp" type="text" placeholder="Patients ID / Patient Name" name="search-by-id-name" id="search-by-id-name-contact" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>" /*onkeyup="filterAppointmentByValue()" * />

                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-outline-primary shadow-none searchIcon" type="button" id="button-addon" onclick="filterAppointmentByValue()"><i class="fas fa-search"></i></button>
                                        </div>

                                        <button class=" btn btn-sm btn-outline-primary shadow-none input-group-append searchIcon" id="filter-reset-1" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Clear Search Filter" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>

                                <div class="col-3 col-md-3 mt-2 d-flex">
                                    <div class="input-group">
                                        <select class="cvx-inp1" name="added_on" id="added_on" onchange="filterAppointmentByValue()">
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

                                    <button class="input-group-append  btn btn-sm btn-outline-primary rounded-0 shadow-none" type="button" id="filter-reset-2" onclick="resteUrl(this.id)" style="z-index: 100; background: white; height: 29px" data-bs-toggle="tooltip" data-bs-placement="top" title="Clear Date Filter" ><i class="fas fa-times"></i></button>


                                    <label class="d-none" id="select-start-date"><?php echo $startDate; ?></label>
                                    <label class="d-none" id="select-end-date"><?php echo $endDate; ?></label>
                                </div>

                                <div class="doc-control d-none">
                                    <input class="d-none" type="text" id="doctor_id" value="">
                                    <label class="d-none" id="select-docId"></label>
                                    <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="filter-reset-3" style="margin-left: -26px; z-index: 100; background: white;" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                </div>

                                <div class="col 3 col-md-3 mt-2 d-flex">
                                    <div class="input-group">
                                        <select class="cvx-inp1" id="added_by" onchange="filterAppointmentByValue()">
                                            <option value="" disabled="" selected="">Select Staff
                                            </option>
                                            <?php
                                            foreach ($employeeDetails as $empData) {
                                                echo '<option value="' . $empData->emp_id . '">' . $empData->emp_name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="filter-reset-4" style="z-index: 100; background: white;" data-bs-toggle="tooltip" data-bs-placement="top" title="Clear Epployee Filter" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>


                                    <label class="d-none" id="select-empId"><?php echo $empId; ?></label>
                                </div>
                            </div>

                            <div class="dropdown-menu row " id="dtPickerDiv" style="display: none;position: relative;margin-left: 356px; background-color: rgba(255, 255, 255, 0.8);">
                                <label class="d-none" id="date-range-control-flag">0</label>
                                <label class="d-none" id="url-control-flag">0</label>
                                <div class="col-md-12">
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

                        <!-- <a data-toggle="modal" data-target="#appointmentSelection"><button
                                    class="btn btn-sm btn-primary" onclick="addNewPatientData()"><i class="fas fa-edit"></i>Add New</button></a> -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Patient ID</th>
                                            <th>Patient Name</th>
                                            <th>Age</th>
                                            <th>Contact</th>
                                            <th>Visits</th>
                                            <th>Area PIN</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($slicedPatients)) {
                                            foreach ($slicedPatients as $slicedPatientsdetails) {
                                                // print_r($slicedPatientsdetails);
                                                $slicedPatientsID   = $slicedPatientsdetails->patient_id;
                                                $slicedPatientsName = $slicedPatientsdetails->name;
                                                $slicedPatientsAge = $slicedPatientsdetails->age;
                                                $slicedPatientsPhone = $slicedPatientsdetails->phno;
                                                $slicedPatientsAge = $slicedPatientsdetails->age;
                                                $slicedPatientsVisited = $slicedPatientsdetails->visited;
                                                $slicedPatientsLabVisited = $slicedPatientsdetails->lab_visited;
                                                $slicedPatientsPin = $slicedPatientsdetails->patient_pin;
                                            echo '<tr>
                                                    <td>' . $slicedPatientsID . '</td>
                                                    <td>' . $slicedPatientsName . '</td>
                                                    <td>' . $slicedPatientsAge . '</td>
                                                    <td><a class="text-decoration-none" href="tel:'.$slicedPatientsPhone.'">' . $slicedPatientsPhone . '</a></td>
                                                    <td class="align-middle pb-0 pt-0">
                                                        <small class="small">
                                                            <span class="pr-2">OPD: ' . $slicedPatientsVisited . '</span>
                                                            <span>Lab: ' . $slicedPatientsLabVisited . '</span></small>
                                                    </td>
                                                    <td> ' . $slicedPatientsPin . '</td>

                                                    <td class="text-center">
                                                        <a class="text-primary" href="patient-details.php?patient=' . url_enc($slicedPatientsID) . '"
                                                            title="View and Edit"><i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>';
                                            }
                                        } ?>
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

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


    <!-- ADD NEW PATIENT MODAL -->
    <div class="modal fade appointmentSelection" tabindex="-1" role="dialog" aria-labelledby="appointmentSelectionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Patient Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body add-new-patient">
                    <!-- Appointments Details Goes Here By Ajax -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload()">ADD</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END OF ADD NEW PATIENT MODAL -->


    <!-- Scroll to Top Button-->
    <!-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a> -->
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>
    <!-- polyclinic search filter script  -->
    <script src="<?= JS_PATH ?>polyclinic-searchFilter.js"></script>


    <script>
        /*
        const returnFilter = (t) => {

            document.getElementById('dtPickerDiv').style.display = 'none';

            var id = t.id;
            var value = t.value;

            console.log(value);

            if (value != 'CR') {
                //fetch current url and pathname
                var currentURLWithoutQuery = window.location.origin + window.location.pathname;
                // create new url with added value to previous url
                var newUrl = `${currentURLWithoutQuery}?search=${id}&searchKey=${value}`;
                // replace previous url with new url
                window.location.replace(newUrl);
            }

            if (value == 'CR') {
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


        const filterPatients = () => {

            document.getElementById('dtPickerDiv').style.display = 'none';

            var id = document.getElementById('search-by-id-name').id;
            var value = document.getElementById('search-by-id-name').value;

            var currentURLWithoutQuery = window.location.origin + window.location.pathname;
            var newUrl = `${currentURLWithoutQuery}?search=${id}&searchKey=${value}`;

            window.location.replace(newUrl);
        }
        */
    </script>
</body>

</html>