<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not


require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'pagination.class.php';





// $LabAppointments = new LabAppointments();
$Patients        = new Patients();
$SubTests        = new SubTests();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();
$Doctors         = new Doctors();
$Employees       = new Employees;
$Pagination      = new Pagination;


// ================ doctor detials ===================
$DoctorsList = json_decode($Doctors->showDoctors($adminId));
if (!empty($DoctorsList->data)) {
    $DoctorList = $DoctorsList->data;
}

// ================ employee detials ===================
$empCol = 'admin_id';
$employeeDetails = $Employees->selectEmpByCol($empCol, $adminId);
$employeeDetails = json_decode($employeeDetails);
$employeeDetails = $employeeDetails->data;


// ================ data filter ===================
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

    $labBillDisplay = $LabBilling->labBillDataSearchFilter($searchVal, $startDate, $endDate, $docId, $empId, $adminId);
} else {
    $labBillDisplay = $LabBilling->labBillDisplay($adminId);
}

// print_r($labBillDisplay);

$labBillDisplay = json_decode($labBillDisplay);
// print_r($labBillDisplay);
$slicedLabBills = '';
if ($labBillDisplay->status) {
    if ($labBillDisplay->data != '') {
        $billsResponseData = $labBillDisplay->data;
        if (is_array($billsResponseData)) {
            $response = json_decode($Pagination->arrayPagination($billsResponseData));

            $paginationHTML = '';
            $totalItem = $slicedLabBills = $response->totalitem;

            if ($response->status == 1) {
                $slicedLabBills = $response->items;
                $paginationHTML = $response->paginationHTML;
            }
        } else {
            $totalItem = 0;
        }
    } else {
        $totalItem = 0;
        $paginationHTML = '';
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
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lab Appointments - <?= $healthCareName ?> | <?= SITE_NAME ?></title>

    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Sweet Alert Link  -->
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

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

                    <!-- Test Appointments -->
                    <div class="card shadow mb-2">
                        <!-- <div class="card-header py-3 justify-content-between"> -->
                        <div class="row d-flex">
                            <div class="col-md-5">
                                <h6 class="mt-3 ml-4 font-weight-bold text-primary">List of Bookings : <?= $totalItem ?></h6>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end ml-5">
                                <a class="mt-3   btn btn-sm btn-primary" href="lab-patient-selection.php?test=true">
                                    <p class="m-0 p-0">Entry</p>
                                </a>
                            </div>
                        </div>

                        <div class="row d-flex">
                            <div class="col-12 d-flex mt-4">
                                <div class="col-sm-6 col-md-3">
                                    <label class="d-none" id="control-flag"><?= $flagVal; ?></label>
                                    <label class="d-none" id="parent-url"><?php echo URL; ?></label>

                                    <div class="input-group">
                                        <input class="cvx-inp" type="text" placeholder="Invoice ID / Patient ID" name="appointment-search" aria-describedby="button-addon2" id="search-by-id-name-contact" style="outline: none;" value="<?= isset($match) ? $match : ''; ?>" /*onkeyup="filterAppointmentByValue()" * />

                                        <div class="input-group-append" id="appointment-search-filter-1">
                                            <button class="btn btn-sm btn-outline-primary shadow-none" type="button" id="button-addon" onclick="filterAppointmentByValue()"><i class="fas fa-search"></i></button>
                                        </div>

                                        <div class="input-group-append">
                                            <button class="d-none btn btn-sm btn-outline-primary shadow-none input-group-append" id="filter-reset-1" type="button" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="input-group">

                                        <select class="col-md-11 cvx-inp1" name="added_on" id="added_on" onchange="filterAppointmentByValue()">
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
                                        <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="filter-reset-2" style="margin-left: -26px; z-index: 100; background: white;" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                    </div>

                                    <label class="d-none" id="select-start-date"><?php echo $startDate; ?></label>
                                    <label class="d-none" id="select-end-date"><?php echo $endDate; ?></label>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="input-group">
                                        <select class="col-md-11 cvx-inp1" name="doctor-filter" id="doctor_id" onchange="filterAppointmentByValue()">
                                            <option value="" selected disabled>Find By Doctor</option>
                                            <?php
                                            foreach ($DoctorList as $doctor) {
                                                $selected = $doctorID ==  $doctor->doctor_id ? 'selected' : '';
                                                echo "<option $selected value='$doctor->doctor_id'>$doctor->doctor_name</option>";
                                            }
                                            ?>
                                        </select>
                                        <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="filter-reset-3" style="margin-left: -26px; z-index: 100; background: white;" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                    </div>
                                    <label class="d-none" id="select-docId"><?php echo $docId; ?></label>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="input-group">
                                        <select class="col-md-11 cvx-inp1" name="added_by" id="added_by" onchange="filterAppointmentByValue()">
                                            <option value="" disabled="" selected="">Select Staff</option>
                                            <?php
                                            foreach ($employeeDetails as $empData) {
                                                echo '<option value="' . $empData->emp_id . '">' . $empData->emp_name . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <button class="d-none btn btn-sm btn-outline-primary shadow-none rounded-0" type="button" id="filter-reset-4" style="margin-left: -26px; z-index: 100; background: white;" onclick="resteUrl(this.id)"><i class="fas fa-times"></i></button>
                                    </div>
                                    <label class="d-none" id="select-empId"><?php echo $empId; ?></label>
                                </div>
                            </div>
                        </div>

                        <label class="d-none" id="date-range-control-flag">0</label>
                        <label class="d-none" id="url-control-flag">0</label>
                        <div class="dropdown-menu  p-2 row ml-4 mt-2" id="dtPickerDiv" style="display: none; position: relative; background-color: rgba(255, 255, 255, 0.8);">
                            <div class=" col-md-8">
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

                        <!-- </div> -->



                        <div class="card-body">

                            <?php
                            if ($totalItem > 0) {
                            ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Invoice ID</th>
                                                <th>Test Date</th>
                                                <th>Test</th>
                                                <th>Refered By</th>
                                                <th>Paid Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (is_array($slicedLabBills) && count($slicedLabBills) > 0) {
                                                foreach ($slicedLabBills as $rowlabBill) {
                                                    $billId        = $rowlabBill->bill_id;
                                                    $patientId     = $rowlabBill->patient_id;
                                                    $referdDoc     = $rowlabBill->refered_doctor;
                                                    $testDate      = $rowlabBill->test_date;
                                                    $paidAmount    = $rowlabBill->paid_amount;
                                                    $status        = $rowlabBill->status;

                                                    $billDetails = json_decode($LabBillDetails->billDetailsById($billId));
                                                    
                                                    if($billDetails->status){
                                                        $billDetails = $billDetails->data;
                                                    }else{
                                                        $billDetails = [];
                                                    }

                                                    $test = count($billDetails);
                                                    
                                                    // foreach ($billDetails as $rowBillDetails) {
                                                                                                    
                                                    //     $subTestId = $rowBillDetails->test_id;

                                                    //     $showSubTest = $SubTests->showSubTestsId($subTestId);
                                                        
                                                    // }


                                                    $docId = $referdDoc;
                                                    if (is_numeric($docId)) {
                                                        $showDoctor = $Doctors->showDoctorNameById($docId);
                                                        $showDoctor = json_decode($showDoctor);
                                                        if ($showDoctor->status == 1) {
                                                            foreach ($showDoctor->data as $rowDoctor) {
                                                                $docName = $rowDoctor->doctor_name;
                                                            }
                                                        }

                                                    } else {
                                                        $docName = $referdDoc;
                                                    }
                                                    echo '<tr ';

                                                    if ($status == "Credit") {
                                                        echo 'style="background-color:#FFCCCB";';
                                                    } elseif ($status == "Partial Due") {
                                                        echo 'style="background-color: #FFFF99";';
                                                    } elseif ($status == "Cancelled") {
                                                        echo 'style="background-color: #b51212; color: #FFF;"';
                                                    } else {
                                                        echo 'style="background-color:white";';
                                                    }
                                                    echo '>
                                                        <td>' . $billId . '</td>
                                                        <td>' . $testDate . '</td>
                                                        <td>'.$test.'</td>
                                                        <td>' . $docName . '</td>
                                                        <td>Rs. ' . $paidAmount . '</td>
                                                        <td>' . $status . '</td>
                                                        <td>
                                                        <a class="text-primary mx-2" data-toggle="modal" data-target="#billModal" onclick="billViewandEdit(' . $billId . ')" title="View and Edit"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                                        <a class="text-primary text-center" title="Print" href="test-invoice.php?bill_id=' .url_enc($billId) . '"><i class="fas fa-print"></i></a>

                                                        <a class="delete-btn text-danger mx-2" id="' . $billId . '" title="Cancel" onclick="cancelBill(' . $billId . ')"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                        <a class="text-primary text-center" title="Report" href="test-report-generate.php?bill-id=' . $billId . '"><i class="fa fa-flask" aria-hidden="true"></i></a>
                                                        </td>
                                                    </tr>';
                                                    // }
                                                }
                                            }
                                            // href="ajax/appointment.delete.ajax.php?appointmentId='.$appointmentID.'"
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            <?php
                            }else{
                               echo '<div class="col-md-12  p-2 row p-2 d-flex justify-content-center" id="dtPickerDiv" style="position: relative; background-color: rgba(255, 255, 255, 0.8);">
                                    <label class="text-danger font-weight-bold">No Data Found</label>
                               </div>';
                            }
                            
                            if($totalItem > 16){
                                echo '<div class="d-flex justify-content-center">
                                ' . $paginationHTML . '
                                </div>';
                            }

                            ?>

                        </div>
                    </div>
                    <!--/end Test Appointments -->


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->




        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Lab ptient selection Modal -->
    <!-- <div class="modal fade" id="labPatientSelection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body d-flex justify-content-around align-items-center py-5">
                    <a class="btn btn-primary mx-4" href="add-patient.php?test=true">New Patient</a>
                    OR
                    <a class="btn btn-primary mx-4" href="lab-patient-selection.php">Returning Patient</a>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> -->
    <!-- end Lab ptient selection Modal -->


    <!-- Bill View Modal -->
    <div class="modal fade" id="billModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body billview">
                    <!-- Data will be appeare here by ajax  -->
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <script src="<?= JS_PATH ?>polyclinic-searchFilter.js"></script>



    <script>
        billViewandEdit = (obj) => {

            let billId = obj;
            // alert(billId);
            let url = "ajax/labBill.view.ajax.php?billId=" + billId;
            $(".billview").html(
                '<iframe width="99%" height="500px" frameborder="0" overflow-x: hidden; overflow-y: scroll; allowtransparency="true"  src="' +
                url + '"></iframe>');

        } // end of viewAndEdit function

        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        }


        cancelBill = (billId) => {
            swal({
                    title: "Are you sure?",
                    text: "Once Cancelled, You Will Not Be Able to Modify This Bill.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        $.ajax({
                            url: "ajax/labBill.delete.ajax.php",
                            type: "POST",
                            data: {
                                billId: billId,
                                status: "Cancelled",
                            },
                            success: function(data) {
                                // alert (data);
                                if (data == 1) {
                                    swal("Done! Your Bill Has Been Cancelled.", {
                                        icon: "success",
                                    });
                                    row = document.getElementById(billId);
                                    row.closest('tr').style.background = '#b51212';
                                    row.closest('tr').style.color = '#FFFFFF';
                                } else {
                                    $("#error-message").html("Cancellation Field !!!").slideDown();
                                }

                            }
                        });

                    }
                });
        }


        /*
        const returnFilter = (t) => {

            document.getElementById('dtPickerDiv').style.display = 'none'

            var search = t.id;
            var searchKey = t.value;

            if (searchKey != 'CR') {
                // Get the current URL
                var currentURL = window.location.href;
                // Get the current URL without the query string
                var currentURLWithoutQuery = window.location.origin + window.location.pathname;

                var newURL = `${currentURLWithoutQuery}?search=${search}&searchKey=${searchKey}`;

                window.location.replace(newURL);
            }


            if (searchKey == 'CR') {
                document.getElementById('dtPickerDiv').style.display = 'block'
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


        const filterAppointment = (searchId) => {
            document.getElementById('dtPickerDiv').style.display = 'none'

            // window.alert(searchId);
            var id = document.getElementById('appointment-search').id;
            var value = document.getElementById('appointment-search').value;

            var currentURLWithoutQuery = window.location.origin + window.location.pathname;
            if (value.length > 2) {
                var newURL = `${currentURLWithoutQuery}?search=${id}&searchKey=${value}`;
                window.location.replace(newURL);
            } else {
                alert('Please Enter Minimum 3 Character!');
            }
        }*/
    </script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>/sb-admin-2.min.js"></script>


</body>

</html>