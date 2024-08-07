<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once CLASS_DIR.'dbconnect.php';
// require_once ROOT_DIR . '_config/healthcare.inc.php';
// require_once CLASS_DIR.'patients.class.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

// $Patients = new Patients();

$appointments   = new Appointments;
$IdsGeneration  = new IdsGeneration;
$Patients       = new Patients;
$Utility        = new Utility;
$HealthCare     = new HealthCare;
$doctors        = new Doctors();

$currentURL = $Utility->currentUrl();

$test = false;
if (isset($_GET['test'])) {
    if ($_GET['test'] == 'true') {
        $test = true;
    }
}

$showPatients = json_decode($Patients->allPatients($adminId));
$showPatients = $showPatients->data;
// print_r($showPatients);



if (isset($_SESSION['appointment-data'])) {
    unset($_SESSION['appointment-data']);
}

if (isset($_POST['submit'])) {

    $appointmentDate    = $_POST['appointmentDate'];
    $patientName        = $_POST["patientName"];
    $patientGurdianName = $_POST["patientGurdianName"];
    $patientEmail       = $_POST["patientEmail"];
    $patientPhoneNumber = $_POST["patientPhoneNumber"];
    $patientAge         = $_POST["patientAge"];
    $patientWeight      = '';
    $gender             = $_POST["gender"];
    $patientAddress1    = $_POST["patientAddress1"];
    $patientPS          = $_POST["patientPS"];
    $patientDist        = $_POST["patientDist"];
    $patientPIN         = $_POST["patientPIN"];
    $patientState       = $_POST["patientState"];

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
            // 'patientAddress2' => $patientAddress2,
            'patientPS' => $patientPS,
            'patientDist' => $patientDist,
            'patientPIN' => $patientPIN,
            'patientState' => $patientState,
            'patientDoctor' => $patientDoctor
        );

        header("location: lab-billing.php?pa");
    } else {
        echo "<script>alert('Patient Not Inserted, Something is Wrong!')</script>";
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Patient Selection - <?= $HEALTHCARENAME ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>/bootstrap/5.3.3/dist/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>patient-style.css" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>select2/select2.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>choices/assets/styles/choices.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom-dropdown.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>lab-test.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/appointment.css" type="text/css" />
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

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
                            <h5><b>
                                    <p class="text-primary">Lab Test</p>
                                </b></h5>
                        </div>
                        <div class="card-body my-5 my-md-1 p-md-5">

                            <form class="row flex-column align-items-center" action="lab-billing.php" method="post">
                                <div class=" col-md-6 col-12">
                                    <input type="text" class="d-none" id="patientId" name="patientId">
                                    <input type="text" class="form-control w-100" id="patientName" name="patientName" placeholder='Enter patient name' onkeyup="getPatient(this.value)" autocomplete="off">
                                    <div id="patient-list">
                                    </div>
                                </div>

                                <div class="form-group col-12 col-md-6 mt-2">
                                    <input type="date" class="form-control w-100" id="testDate" name="testDate" placeholder="" required>
                                </div>
                                <div class="form-group col-12 col-md-2">
                                    <button type="submit" name="bill-proceed" class="btn-block btn-primary">Proceed</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- main content end -->
        </div>
        <!-- Content Wrapper end-->
    </div>
    <!-- page wrapper end -->

    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="addnewTestbill" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Lab Test</h5>
                    <button type="button" class="close" data-dismiss="modal" onclick="window.location.reload()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body addnewTestbill" id="newTestModalBody">
                    <!-- onclick="window.location.reload()" -->
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->

    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.slim.js"></script>

    <!-- custom script for add patient -->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>

    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.js"></script>
    <!-- Core plugin JavaScript-->
    <!-- <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script> -->
    <script src="<?= PLUGIN_PATH ?>select2/select2.min.js"></script>


    <script src="<?php echo JS_PATH ?>add-patient.js"></script>


    <script>
        const addnewpatient = () => {
            let url = "ajax/newTestBill-add.ajax.php?test=true";
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('newTestModalBody').innerHTML = data;
                    $('#addnewTestbill').modal('show');
                })
                .catch(error => {
                    console.error('Error fetching content:', error);
                });
        }
    </script>

</body>

</html>