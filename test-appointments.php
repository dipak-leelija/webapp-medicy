<?php
require_once __DIR__.'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'labBilling.class.php';
require_once CLASS_DIR.'labBillDetails.class.php';
require_once CLASS_DIR.'sub-test.class.php';
require_once CLASS_DIR.'doctors.class.php';




$page = "test-appointments";

// $LabAppointments = new LabAppointments();
$Patients        = new Patients();
$SubTests        = new SubTests();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();
$Doctors         = new Doctors();



$labBillDisplay = $LabBilling->labBillDisplay();

// $showLabAppointments = $LabAppointments->showLabAppointments();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <link href="<?php echo CSS_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Sweet Alert Link  -->
    <script src="<?php echo JS_PATH ?>sweetAlert.min.js"></script>



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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Test Appointments</h1>


                    <!-- Test Appointments -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 booked_btn">
                            <a data-toggle="modal" data-target="#labPatientSelection"><button class="btn btn-primary"><i
                                        class="fas fa-edit"></i> Add Test Bill</button></a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                    <tfoot>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Test Date</th>
                                            <th>Test</th>
                                            <th>Refered By</th>
                                            <th>Paid Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php 
                                        foreach ($labBillDisplay as $rowlabBill) {
                                            $billId        = $rowlabBill['bill_id'];
                                            $patientId     = $rowlabBill['patient_id'];
                                            $referdDoc     = $rowlabBill['refered_doctor'];
                                            $testDate      = $rowlabBill['test_date'];
                                            $paidAmount    = $rowlabBill['paid_amount'];
                                            $status        = $rowlabBill['status'];



                                            $billDetails = $LabBillDetails->billDetailsById($billId);
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
                                            }else {
                                                $docName = $referdDoc;
                                            }
                                              echo '<tr ';
                                               
                                              if($status == "Credit"){
                                                echo 'style="background-color:#FFCCCB";';
                                              }elseif ($status == "Partial Due") {
                                                echo 'style="background-color: #FFFF99";';
                                              }elseif ( $status == "Cancelled") {
                                                echo 'style="background-color: #b51212; color: #FFF;"';
                                              }
                                              else{
                                                echo 'style="background-color:white";';
                                              }
                                              echo '>
                                                        <td>'.$billId.'</td>
                                                        <td>'.$testDate.'</td>
                                                        <td>'.$test.' Tests</td>
                                                        <td>'.$docName.'</td>
                                                        <td>Rs. '.$paidAmount.'</td>
                                                        <td>'.$status.'</td>
                                                        <td><a class="text-primary mx-2" data-toggle="modal" data-target="#billModal" onclick="billViewandEdit('.$billId.')" title="View and Edit"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                                        <a class="text-primary text-center" title="Print" href="reprint-test-bill.php?bill_id='.$billId.'"><i class="fas fa-print"></i></a>

                                                        <a class="delete-btn text-danger mx-2" id="'.$billId.'" title="Cancel" onclick="cancelBill('.$billId.')"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                        <a class="text-primary text-center" title="Print" href="test-report-generate.php?bill-id='.$billId.'"><i class="fa fa-flask" aria-hidden="true"></i></a>
                                                        </td>
                                                    </tr>';
                                        }
                                            // href="ajax/appointment.delete.ajax.php?appointmentId='.$appointmentID.'"
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--/end Test Appointments -->


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

    <!-- Lab ptient selection Modal -->
    <div class="modal fade" id="labPatientSelection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Choose Patient type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-around">
                    <a class="btn btn-primary mx-4" href="lab-entry.php">New Patient</a>
                    or
                    <a class="btn btn-primary mx-4" href="lab-patient-selection.php">Returning Patient</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
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
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>



    <script>
    billViewandEdit = (obj) => {
        if (obj < 10) {
            obj = '0' + obj
        }
        let billId = obj;
        // alert(billId);
        let url = "ajax/labBill.view.ajax.php?billId=" + billId;


        $(".billview").html(
            '<iframe width="99%" height="500px" frameborder="0" overflow-x: hidden; overflow-y: scroll; allowtransparency="true"  src="' +
            url + '"></iframe>');
    } // end of viewAndEdit function
    // 
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
    </script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo PLUGIN_PATH ?>/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo JS_PATH ?>/demo/datatables-demo.js"></script>

</body>

</html>