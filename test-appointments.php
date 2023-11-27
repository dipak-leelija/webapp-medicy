<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'pagination.class.php';





// $LabAppointments = new LabAppointments();
$Patients        = new Patients();
$SubTests        = new SubTests();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();
$Doctors         = new Doctors();
$Pagination      = new Pagination;

$DoctorsList = json_decode($Doctors->showDoctors($adminId));
if (!empty($DoctorsList->data)) {
    $DoctorList = $DoctorsList->data;
}

// exit;

if (isset($_GET['doctor'])) {
    $doctorID = $_GET['doctor'];
    $labBillDisplay = $LabBilling->labBillFilter($adminId, 'refered_doctor', $doctorID);
}elseif (isset($_GET['search'])) {
    $match = $_GET['search'];
    $labBillDisplay = $LabBilling->labBillFilter($adminId, 'search', $match);
}

else {
    $labBillDisplay = $LabBilling->labBillDisplay($adminId);
}

$billsResponse  = json_decode($Pagination->arrayPagination($labBillDisplay));
if ($billsResponse->status == 1) {
    $slicedLabBills = $billsResponse->items;
    $billsPagination = $billsResponse->paginationHTML;
}else {
    $slicedLabBills = '';
    $billsPagination = '';
}
// print_r($slicedLabBills);
// $showLabAppointments = $LabAppointments->showLabAppointments();
// exit;
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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
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

                    <!-- Test Appointments -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 booked_btn">

                            <div class="row mt-2">
                                <div class="col-md-2 col-6">
                                    <div class="input-group">
                                        <input class="cvx-inp" type="text" placeholder="Invoice ID / Patient ID"
                                            name="appointment-search" id="appointment-search" style="outline: none;" aria-describedby="button-addon2" value="<?= isset($match) ? $match : ''; ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-outline-primary shadow-none" type="button"
                                                id="button-addon2" onclick="filterAppointment('appointment-search')"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <select class="cvx-inp1" name="added_on" id="added_on"
                                        onchange="returnFilter(this)">
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
                                    <select class="cvx-inp1" name="refund_mode" id="refund_mode"
                                        onchange="dictorFilter(this)">
                                        <option value="" selected="" disabled="">Find By Doctor</option>
                                        <?php
                                        foreach ($DoctorList as $doctor) {
                                            $selected = $doctorID ==  $doctor->doctor_id ? 'selected' : '';
                                            echo "<option $selected value='$doctor->doctor_id'>$doctor->doctor_name</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-6">
                                    <select class="cvx-inp1" id="added_by" onchange="returnFilter(this)">
                                        <option value="" disabled="" selected="">Select Staff
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2 col-6">
                                    <select class="cvx-inp1" name="refund_mode" id="refund_mode"
                                        onchange="filterAppointment(this)">
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
                                    <a class="btn btn-sm btn-primary " data-toggle="modal"
                                        data-target="#labPatientSelection">
                                        <i class="fas fa-edit"></i>Entry
                                    </a>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
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
                                        if (is_array($labBillDisplay) && count($labBillDisplay) > 0) {
                                            foreach ($slicedLabBills as $rowlabBill) {
                                                $billId        = $rowlabBill->bill_id;
                                                $patientId     = $rowlabBill->patient_id;
                                                $referdDoc     = $rowlabBill->refered_doctor;
                                                $testDate      = $rowlabBill->test_date;
                                                $paidAmount    = $rowlabBill->paid_amount;
                                                $status        = $rowlabBill->status;


                                                $billDetails = $LabBillDetails->billDetailsById($billId);
                                                if (is_array($billDetails))
                                                    $test = count($billDetails);

                                                // echo print_r($billDetails);exit;
                                                if ($test == 1) {
                                                    foreach ($billDetails as $rowBillDetails) {
                                                        $subTestId = $rowBillDetails['test_id'];

                                                        $showSubTest = $SubTests->showSubTestsId($subTestId);
                                                        foreach ($showSubTest as $rowSubTest) {
                                                            $test = $rowSubTest['sub_test_name'];
                                                            // echo $test;
                                                        }
                                                    }
                                                }


                                                $docId = $referdDoc;
                                                if (is_numeric($docId)) {
                                                    $showDoctor = $Doctors->showDoctorById($docId);
                                                    foreach ($showDoctor as $rowDoctor) {
                                                        $docName = $rowDoctor['doctor_name'];
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
                                                        <td>' . $test . ' Tests</td>
                                                        <td>' . $docName . '</td>
                                                        <td>Rs. ' . $paidAmount . '</td>
                                                        <td>' . $status . '</td>
                                                        <td>
                                                        <a class="text-primary mx-2" data-toggle="modal" data-target="#billModal" onclick="billViewandEdit(' . $billId . ')" title="View and Edit"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                                        <a class="text-primary text-center" title="Print" href="reprint-test-bill.php?bill_id=' . $billId . '"><i class="fas fa-print"></i></a>

                                                        <a class="delete-btn text-danger mx-2" id="' . $billId . '" title="Cancel" onclick="cancelBill(' . $billId . ')"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                        <a class="text-primary text-center" title="Print" href="test-report-generate.php?bill-id=' . $billId . '"><i class="fa fa-flask" aria-hidden="true"></i></a>
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

                            <div class="d-flex justify-content-center">
                                <?= $billsPagination ?>
                            </div>

                        </div>
                    </div>
                    <!--/end Test Appointments -->


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

    <!-- Lab ptient selection Modal -->
    <div class="modal fade" id="labPatientSelection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
    </div>
    <!-- end Lab ptient selection Modal -->


    <!-- Bill View Modal -->
    <div class="modal fade" id="billModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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

    const dictorFilter = (t) => {
        doctorId = t.value;
        // Get the current URL
        var currentURL = window.location.href;

        // Get the current URL without the query string
        var currentURLWithoutQuery = window.location.origin + window.location.pathname;

        var newURL = `${currentURLWithoutQuery}?doctor=${doctorId}`;
     
        // alert(newURL);
        window.location.replace(newURL);
    }

    const filterAppointment = (searchId) =>{
        var search = document.getElementById(searchId);
        var currentURLWithoutQuery = window.location.origin + window.location.pathname;
        if (search.value.length > 2) {
            var newURL = `${currentURLWithoutQuery}?search=${search.value}`;
            window.location.replace(newURL);
        }else{
            alert('Please Enter Minimum 3 Character!');
        }

    }


    </script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>/sb-admin-2.min.js"></script>


</body>

</html>