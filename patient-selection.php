<?php

require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or no
// require_once CLASS_DIR . 'dbconnect.php';
// require_once ROOT_DIR . '_config/healthcare.inc.php';
// require_once CLASS_DIR . 'patients.class.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'hospital.class.php';

// $Patients = new Patients();

$appointments   = new Appointments;
$IdsGeneration  = new IdsGeneration;
$Patients       = new Patients;
$Utility        = new Utility;
$HealthCare     = new HealthCare;
$doctors        = new Doctors();


$currentURL = $Utility->currentUrl();

// $test = false;
// if (isset($_GET['test'])) {
//     if ($_GET['test'] == 'true') {
//         $test = true;
//     }
// }


$showDoctors = $doctors->showDoctors($adminId);
$showDoctors = json_decode($showDoctors);
$allDoctors  = $showDoctors->data;

$clinicInfo  = $HealthCare->showHealthCare($adminId);
$clinicInfo  = json_decode($clinicInfo, true);

if ($clinicInfo['status'] == 1) {
    $data = $clinicInfo['data'];
    $district = $data['dist'];
    $pin      = $data['pin'];
    $state    = $data['health_care_state'];
} else {
    echo "Error: " . $clinicInfo['msg'];
}

$showPatients = json_decode($Patients->allPatients($adminId));



if (isset($_SESSION['appointment-data'])) {
    unset($_SESSION['appointment-data']);
}

if (isset($_POST['submit'])) {

    $appointmentDate    = $_POST["appointmentDate"];
    $patientName        = $_POST["patientName"];
    $patientGurdianName = $_POST["patientGurdianName"];
    $patientEmail       = $_POST["patientEmail"];
    $patientPhoneNumber = $_POST["patientPhoneNumber"];
    $patientAge         = $_POST["patientAge"];
    $patientWeight      = $_POST["patientWeight"];
    $gender             = $_POST["gender"];
    $patientAddress1    = $_POST["patientAddress1"];
    // $patientAddress2    = $_POST["patientAddress2"];
    $patientPS          = $_POST["patientPS"];
    $patientDist        = $_POST["patientDist"];
    $patientPIN         = $_POST["patientPIN"];
    $patientState       = $_POST["patientState"];
    $patientDoctor      = $_POST["patientDoctor"];
    // $patientDoctorShift = $_POST["doctorTime"];

    //Patient Id Generate
    $patientId = $IdsGeneration->patientidGenerate();

    //redirect if the insertion has done
    $visited = 1;

    // Inserting Into Patients Database
    $addPatients = $Patients->addPatients($patientId, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $gender, $patientAddress1, $patientPS, $patientDist, $patientPIN, $patientState, $visited, $employeeId, NOW, $adminId);

    if ($addPatients) {

        $_SESSION['appointment-data'] = array(
            'patientId' => $patientId,
            'appointmentDate' => $appointmentDate,
            'patientName' => $patientName,
            'patientGurdianName' => $patientGurdianName,
            'patientEmail' => $patientEmail,
            'patientPhoneNumber' => $patientPhoneNumber,
            'patientAge' => $patientAge,
            'patientWeight' => intval($patientWeight),
            'gender' => $gender,
            'patientAddress1' => $patientAddress1,
            'patientAddress2' => $patientAddress2,
            'patientPS' => $patientPS,
            'patientDist' => $patientDist,
            'patientPIN' => $patientPIN,
            'patientState' => $patientState,
            'patientDoctor' => $patientDoctor
        );

        if ($test) {
            header("location: lab-billing.php");
        } else {
            header("location: appointment-entry.php");
        }
    } else {
        echo "<script>alert('Patient Not Inserted, Something is Wrong!')</script>";
    }
}
?>

<!doctype html>

<html lang="en">

<head>

    <!-- Required meta tags -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>patient-style.css">
    <title>Enter Patient Details</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css"> -->
    <link href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" rel="stylesheet" />
</head>

<body>

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
                <!-- End of top bar -->


                <div class="container-fluid">
                    <div class="row" style="z-index: 999;">
                        <div class="col-12">
                            <?php include ROOT_COMPONENT . "drugPermitDataAlert.php"; ?>
                        </div>
                    </div>

                    <div class="card p-0">
                        <div class="card-header">
                            <h5 class="text-primary font-weight-bolder">Select Patient</h5>
                        </div>
                        <div class="card-body my-5 my-md-1 p-md-5">
                            <form class="row flex-column align-items-center" action="returning-appointment-entry.php" method="post" id="form-submit">
                                <div class="section col-12 col-md-6">

                                    <input type="text" class="d-none" id="patientId" name="patientId">
                                    <input type="text" class="form-control w-100" id="patientName" name="patientName" placeholder='Enter patient name' onkeyup="getPatient(this.value)" autocomplete="off" required>
                                    <div id="patient-list">
                                    </div>

                                </div>

                                <div class="form-group col-12 col-md-6 mt-4">
                                    <button type="submit" name="proceed" class="btn-block btn-primary">Proceed</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <?php include ROOT_COMPONENT . 'footer-text.php'; ?>
            </div>

        </div>
    </div>
    <!-- Page Wrapper end -->

    <!-- Footer -->

    <!-- End of Footer -->

    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="addnewTestbill" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal" onclick="window.location.reload()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body addnewTestbill" id="newTestModalBody" value="">

                </div>
            </div>
        </div>
    </div>




    <!-- Bootstrap core JavaScript-->


    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery-3-5-1.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.slim.js"></script>
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

    <!-- Core plugin JavaScript-->
    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="<?php echo JS_PATH ?>add-patient.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

    <!-- Page level custom scripts -->
    <!-- <script src="js/demo/chart-area-demo.js"></script> -->
    <!-- <script src="js/demo/chart-pie-demo.js"></script> -->



    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script> -->
    <script src="<?= PLUGIN_PATH ?>choices/assets/scripts/choices.js"></script>


    <script>
        // patient selection js
        // $(document).ready(function() {
        //     $('.patient-select').selectpicker();

        // })
        // document.addEventListener('DOMContentLoaded', function() {
        //     new Choices('#choices-remove-button', {
        //         allowHTML: true,
        //         removeItemButton: true,
        //     });
        // });
    </script>

    <script>
        const addnewpatient = () => {

            document.getElementById('form-submit').removeAttribute('action');
            // formId.removeAttribute('action');

            let url = "ajax/newTestBill-add.ajax.php";
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('newTestModalBody').innerHTML = data;
                    console.log(data);

                    $('#addnewTestbill').modal('show');
                })
                .catch(error => {
                    console.error('Error fetching content:', error);
                });
        }
    </script>

</body>

</html>