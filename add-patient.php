<?php
// $page = "appointments";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'hospital.class.php';


//Classes Initilizing
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



$showDoctors = $doctors->showDoctors($adminId);
$showDoctors = json_decode($showDoctors);
$allDoctors  = $showDoctors->data;

$clinicInfo  = $HealthCare -> showHealthCare($adminId);
$clinicInfo  = json_decode($clinicInfo, true);

if ($clinicInfo['status'] == 1) {
    $data = $clinicInfo['data'];
     $district = $data['dist'];
     $pin      = $data['pin'];
     $state    = $data['health_care_state'];
} else {
    echo "Error: " . $clinicInfo['msg'];
}




// find the patient name 
// $Patients = $Patients->patientFilterByAdminId($adminId);
// $Patients = json_decode($Patients);
?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Patient - <?= $healthCareName ?> | <?= SITE_NAME ?></title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?= CSS_PATH ?>custom/appointment.css" rel="stylesheet" type="text/css" />
    <link href="<?= CSS_PATH ?>patient-style.css" rel="stylesheet" type="text/css" />

    <!-- css for sweetalert2 -->
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

</head>

<body>
    <?php
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
        $addPatients = $Patients->addPatients($patientId, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $gender, $patientAddress1, $patientPS, $patientDist, $patientPIN, $patientState, $visited, $employeeId,$appointmentDate, NOW, $adminId);

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


                <div class="container-fluid px-1  mx-auto">
                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-9 col-lg-10 col-md-10 text-center">
                            <div class="card shadow-sm p-4">
                                <h4 class="text-center mb-4 mt-0"><b>Fill The Patient Details</b></h4>
                                <form class="form-card " action="<?= $currentURL ?>" method="post">
                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientName">Patient Name<span class="text-danger"> *</span></label>

                                            <input type="text" id="patientName" name="patientName" placeholder="Enter Patient Name" onkeyup="getpatient(this.value)" required autocomplete="off">
                                            <div id="patients-list">

                                            </div>
                                            <input type="text" id="patientId" hidden>
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientPhoneNumber">Phone
                                                number<span class="text-danger"> *</span></label>
                                            <input type="tel" id="patientPhoneNumber" name="patientPhoneNumber" placeholder="Phone Number" required /*onkeypress="checkMobNo(this)"*/  onchange="checkContactNo(this)" autocomplete="off" maxlength="10">
                                        </div>


                                    </div>

                                    <div class="row justify-content-between text-left">

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientAge">Age<span class="text-danger"> *</span></label>
                                            <input type="number" id="patientAge" name="patientAge" placeholder="Age" onfocusout="checkAge(this)" required autocomplete="off">
                                        </div>

                                        <div class="col-sm-6 mt-4">
                                            <label class="mb-3 mr-1" for="gender">Gender: </label>
                                            <input type="radio" class="btn-check" name="gender" id="male" value="Male" autocomplete="off" required>

                                            <label class="btn btn-sm btn-outline-secondary" for="male" value="Male">Male</label>
                                            <input type="radio" class="btn-check" name="gender" id="female" value="Female" autocomplete="off" required>

                                            <label class="btn btn-sm btn-outline-secondary" for="female" value="Female">Female</label>
                                            <input type="radio" class="btn-check" name="gender" id="secret" value="Others" autocomplete="off" required>

                                            <label class="btn btn-sm btn-outline-secondary" for="secret" value="Secret">Others</label>

                                            <div class="valid-feedback mv-up">You selected a gender!</div>

                                            <div class="invalid-feedback mv-up">Please select a gender!</div>

                                        </div>

                                    </div>


                                    <div class="row justify-content-between text-left">

                                    <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientWeight">Weight <small>(in
                                                    kg)</small><span class="text-danger"> *</span></label>
                                            <input type="number" id="patientWeight" name="patientWeight" placeholder="Weight in kg" onfocusout="checkWeight(this)" required autocomplete="off">
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="appointmentDate">Appointment
                                                Date<span class="text-danger"> *</span></label>
                                            <input type="date" id="appointmentDate" value="<?php print(date("Y-m-d")) ?>" name="appointmentDate" required>
                                        </div>
                                    
                                    </div>



                                    <div class="row justify-content-between text-left">

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientGurdianName">Patient's
                                                Gurdian Name<span class="text-danger"> *</span></label>
                                            <input type="text" id="patientGurdianName" name="patientGurdianName" placeholder="Enter Patient's Gurdian Name" required autocomplete="off">
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientEmail">Patient
                                                Email</label>
                                            <input type="email" id="email" name="patientEmail" placeholder="Patient Email" onfocusout="checkMail(this)" autocomplete="off">
                                        </div>

                                    </div>



                                    <h5 class="text-center mb-4 mt-5">Patient Address</h5>

                                    <div class="row justify-content-between text-left">

                                        <div class="form-group col-sm-12 flex-column d-flex">

                                            <label class="form-control-label px-3" for="patientAddress1">Address <span class="text-danger"> *</span></label>

                                            <input type="text" id="patientAddress1" name="patientAddress1" placeholder="Address Line 1" required autocomplete="off">

                                        </div>

                                        <!-- <div class="form-group col-sm-6 flex-column d-flex">

                                            <label class="form-control-label px-3" for="patientAddress2">Address Line
                                                2<span class="text-danger"> *</span></label>

                                            <input type="text" id="patientAddress2" name="patientAddress2" placeholder="Address Line 2" autocomplete="off">

                                        </div> -->

                                    </div>

                                    <div class="row justify-content-between text-left">

                                        <div class="form-group col-sm-6 flex-column d-flex">

                                            <label class="form-control-label px-3" for="patientPS">Police Station<span class="text-danger"> *</span></label>

                                            <input type="text" id="patientPS" name="patientPS" placeholder="Police Station" required autocomplete="off">

                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">

                                            <label class="form-control-label px-3" for="patientDist">District<span class="text-danger"> *</span></label>

                                            <input type="text" id="patientDist" Value="<?= $district; ?>" name="patientDist" placeholder="District" required autocomplete="off">

                                        </div>

                                    </div>



                                    <div class="row justify-content-between text-left">

                                        <div class="form-group col-sm-6 flex-column d-flex">

                                            <label class="form-control-label px-3" for="patientPIN">PIN Code<span class="text-danger"> *</span></label>

                                            <input type="number" id="patientPIN" Value="<?= $pin ?>" name="patientPIN" placeholder="Pin Code" onfocusout="checkPin(this)" required autocomplete="off">

                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">

                                            <label class="form-control-label px-3" for="patientState">State<span class="text-danger"> *</span></label>

                                            <select id="dropSelection" name="patientState" required>

                                                <option Value="<?= $state ?>" selected><?= $state ?></option>

                                                <option value="West bengal">West Bengal</option>

                                                <option value="Other">Other</option>

                                            </select>

                                        </div>

                                    </div>


                                    <?php if (!$test) : ?>
                                        <div class="row justify-content-between text-left">
                                            <h5 class="text-center mb-4 mt-5">Select Doctor</h5>
                                            <div class="form-group col-sm-12 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientDoctor">Doctor Name<span class="text-danger"> *</span></label>
                                                <select id="docList" class="customDropSelection" name="patientDoctor" required>
                                                    <option value="" disabled selected>Select Doctor</option>
                                                    <?php
                                                    if ($allDoctors != null) {
                                                        foreach ($allDoctors as $showDoctorDetails) {
                                                            $doctorId = $showDoctorDetails->doctor_id;
                                                            $doctorName = $showDoctorDetails->doctor_name;
                                                            echo '<option value=' . $doctorId . '>' . $doctorName . '</option>';
                                                        }
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                            <!-- <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="doctorTiming">Time Slot<span class="text-danger"> *</span></label>
                                            <select id="shiftList" class="customDropSelection" name="doctorTime" onChange="getShiftValues()" required>
                                                <option disabled selected>Select Doctor First</option>

                                             Option goes here by ajax 

                                            </select>
                                        </div> -->
                                            <!-- <label id="shiftValue"></label> -->

                                        </div>
                                    <?php else: ?>
                                        <input class="d-none" type="text" value=" " name="patientDoctor">
                                    <?php endif; ?>
                                                    
                                    <div class="row justify-content-end">

                                        <div class="form-group col-sm-4">
                                            <button type="submit" name="submit" class="btn-block btn-primary">Submit</button>
                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                </script>
                <!-- Footer -->
                <?php include ROOT_COMPONENT . 'footer-text.php'; ?>
                <!-- End of Footer -->

                <!-- Bootstrap core JavaScript-->
                <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
                <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

                <!-- Core plugin JavaScript-->
                <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

                <!-- script for sweetalert2 -->
                <script src="<?php echo JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

                <!-- custom script for add patient -->
                <script src="<?php echo JS_PATH ?>add-patient.js"></script>


                <!-- <script type="text/javascript">
                    var todayDate = new Date();

                    var date = todayDate.getDate();
                    var month = todayDate.getMonth() + 1;
                    var year = todayDate.getFullYear();

                    if (date < 10) {
                        date = '0' + date;
                    }
                    if (month < 10) {
                        month = '0' + month;
                    }
                    var todayFullDate = year + "-" + month + "-" + date;
                    document.getElementById("appointmentDate").setAttribute("min", todayFullDate);
                </script> -->


</body>

</html>