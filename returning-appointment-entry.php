<?php
$page = "appointments";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR. 'encrypt.inc.php';

//Creating Object of Appointments Class
$appointments   = new Appointments();
$Patients       = new Patients();
$doctors        = new Doctors();

$showDoctors = json_decode($doctors->showDoctors($adminId));

// var_dump($_POST);
// if (isset($_POST['proceed'])) {
if (isset($_POST['patientId'])) {
    $patientId = $_POST['patientId'];

    $patient = json_decode($Patients->patientsDisplayByPId($patientId));
    $name            = $patient->name;
    $age             = $patient->age;
    $gurdianName     = $patient->gurdian_name;
    $phno            = $patient->phno;
    $email           = $patient->email;
    $gender          = $patient->gender;
    $addres1         = $patient->address_1;
    $addres2         = $patient->address_2;
    $patientPs       = $patient->patient_ps;
    $patientDist     = $patient->patient_dist;
    $patientPIN      = $patient->patient_pin;
    $patientState    = $patient->patient_state;
}
// }



//sending to database
if (isset($_POST['submit'])) {
    $patientId          = $_POST['patientId'];
    $appointmentDate    = $_POST["appointmentDate"];
    $patientName        = $_POST["patientName"];
    $patientGurdianName = $_POST["patientGurdianName"];
    $patientEmail       = $_POST["patientEmail"];
    $patientPhoneNumber = $_POST["patientPhoneNumber"];
    $patientAge         = $_POST["patientAge"];
    $patientWeight      = $_POST["patientWeight"];
    $gender             = $_POST["gender"];
    $patientAddress1    = $_POST["patientAddress1"];
    $patientAddress2    = $_POST["patientAddress2"];
    $patientPS          = $_POST["patientPS"];
    $patientDist        = $_POST["patientDist"];
    $patientPIN         = $_POST["patientPIN"];
    $patientState       = $_POST["patientState"];
    $patientDoctor      = $_POST["patientDoctor"];

    $healthCareNameTrimed = strtoupper(substr($healthCareName, 0, 2)); //first 2 leter oh healthcare center name
    $appointmentDateForId = str_replace("-", "", $appointmentDate); //removing hyphen from appointment date
    $randCode = rand(1000, 9999); //generating random number

    // Appointment iD Generated
    $appointmentId = $healthCareNameTrimed . '' . $appointmentDateForId . '' . $randCode;


    // Inserting Into Appointments Database
    $addAppointment = $appointments->addFromInternal($appointmentId, $patientId, $appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $addedBy, NOW, $adminId);

    if ($addAppointment) {
        $patientsDisplayByPId = json_decode($Patients->patientsDisplayByPId($patientId));

        $visited = $patientsDisplayByPId->visited;
        $visited = (int)$visited + 1;

        // Inserting Into Patients Database
        $updatePatientsVisitingTime = $Patients->updatePatientsVisitingTime($patientId, $patientEmail, $patientPhoneNumber, $patientAge, $visited);
        if ($updatePatientsVisitingTime) {
            $appointmentId = url_enc($appointmentId);
            header("location: appointment-sucess.php?appointmentId={$appointmentId}");
            exit();
        } else {
            echo "<script>alert('Patient Not Visiting Not Counting, Something is Wrong!')</script>";
        }
    } else {
        echo "Something is wrong! ";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Update/Verify Patient - <?= $HEALTHCARENAME ?></title>

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>patient-style.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/appointment.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
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


                <div class="container-fluid px-1  mx-auto">
                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-9 col-lg-10 col-md-10 text-center">
                            <div class="card shadow-sm p-4">

                                <div class="card-body">
                                    <h4 class="text-center py-2"><b>Update/Verify Details</b></h4>

                                    <form class="form-card" method="post">
                                        <div class="row justify-content-between text-left">
                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label" for="patientName">Patient Name<span class="text-danger"> *</span></label>
                                                <input type="text" class="form-control" id="patientName" name="patientName" value="<?php echo $name; ?>" autocomplete="off">
                                                <input type="text" value="<?php echo $patientId; ?>" hidden name="patientId">

                                            </div>

                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientPhoneNumber">Phone
                                                    number<span class="text-danger"> *</span></label>

                                                <input type="number" id="patientPhoneNumber" name="patientPhoneNumber" placeholder="Phone Number" value="<?php echo $phno; ?>" maxlength="10" minlength="10" required onfocusout="checkContactNo(this)" autocomplete="off">
                                            </div>

                                        </div>

                                        <div class="row justify-content-between text-left">
                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientAge">Age<span class="text-danger"> *</span></label>

                                                <input type="number" id="patientAge" name="patientAge" placeholder="Age" Value="<?php echo $age; ?>" maxlength="3" minlength="1" required onfocusout="checkAge(this)" autocomplete="off">

                                            </div>

                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientWeight">Weight
                                                    <small>(in
                                                        kg)</small><span class="text-danger"> *</span></label>

                                                <input type="number" id="patientWeight" name="patientWeight" placeholder="Weight in kg" maxlength="3" required onfocusout="checkWeight(this)" autocomplete="off">
                                            </div>
                                        </div>


                                        <div class="row justify-content-between text-left">
                                            <div class="col-sm-6 mt-4">
                                                <label class="form-label mb-3 mr-1" for="gender">Gender: </label>

                                                <input type="radio" class="btn-check" name="gender" id="male" value="Male" autocomplete="off" <?= $gender == "Male" ? "checked" : ''; ?> required>
                                                <label class="btn btn-sm btn-outline-secondary" for="male" value="Male">Male</label>

                                                <input type="radio" class="btn-check" name="gender" id="female" value="Female" autocomplete="off" <?= $gender == "Female" ? "checked" : ''; ?> required>
                                                <label class="btn btn-sm btn-outline-secondary" for="female" value="Female">Female</label>

                                                <input type="radio" class="btn-check" name="gender" id="Others" value="Others" autocomplete="off" <?= $gender == "Others" ? "checked" : ''; ?> required>

                                                <label class="btn btn-sm btn-outline-secondary" for="secret" value="Secret">Others</label>
                                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                                            </div>


                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="appointmentDate">Appointment
                                                    Date<span class="text-danger"> *</span></label>

                                                <input type="date" id="appointmentDate" name="appointmentDate" placeholder="" value="<?php print(date("Y-m-d")) ?>" required>
                                            </div>
                                        </div>


                                        <div class="row justify-content-between text-left">
                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientGurdianName">Patient's
                                                    Gurdian Name<span class="text-danger"> *</span></label>

                                                <input type="text" id="patientGurdianName" name="patientGurdianName" placeholder="Enter Patient's Gurdian Name" value="<?php echo $gurdianName; ?>" required autocomplete="off">
                                            </div>

                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="email">Email</label>
                                                <input type="email" id="email" name="patientEmail" placeholder="Patient Email" value="<?php echo $email; ?>" onfocusout="checkMail(this)" autocomplete="off">
                                            </div>



                                        </div>

                                        <h5 class="text-center mb-4 mt-5">Patient Address</h5>

                                        <div class="row justify-content-between text-left">
                                            <div class="form-group col-sm-6 flex-column d-flex">

                                                <label class="form-control-label px-3" for="patientAddress1">Address
                                                    Line
                                                    1<span class="text-danger"> *</span></label>

                                                <input type="text" id="patientAddress1" name="patientAddress1" placeholder="Address Line 1" value="<?php echo $addres1; ?>" required autocomplete="off">

                                            </div>

                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientAddress2">Address
                                                    Line
                                                    2</label>
                                                <input type="text" id="patientAddress2" name="patientAddress2" placeholder="Address Line 2" value="<?php echo $addres2; ?>" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="row justify-content-between text-left">
                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientPS">Police
                                                    Station<span class="text-danger"> *</span></label>
                                                <input type="text" id="patientPS" name="patientPS" placeholder="Police Station" value="<?php echo $addres2; ?>" autocomplete="off">
                                            </div>

                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientDist">District<span class="text-danger"> *</span></label>
                                                <input type="text" id="patientDist" name="patientDist" placeholder="District" value="<?php echo $patientDist; ?>" required autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="row justify-content-between text-left">
                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientPIN">PIN Code<span class="text-danger"> *</span></label>
                                                <input type="number" id="patientPIN" name="patientPIN" placeholder="Pin Code" maxlength="7" value="<?php echo $patientPIN; ?>" required onfocusout="checkPin(this)" autocomplete="off">
                                            </div>

                                            <div class="form-group col-sm-6 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientState">State<span class="text-danger"> *</span></label>
                                                <select id="dropSelection" name="patientState" required>
                                                    <option disabled>Select State</option>
                                                    <option value="West bengal" <?php if ($patientState == "West Bengal") {
                                                                                    echo "selected";
                                                                                } ?>>West
                                                        Bengal</option>
                                                    <option value="Other" <?php if ($patientState == "Other") {
                                                                                echo "selected";
                                                                            } ?>>Other
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row justify-content-between text-left">

                                            <h5 class="text-center mb-4 mt-5">Select Doctor</h5>
                                            <div class="form-group col-sm-12 flex-column d-flex">
                                                <label class="form-control-label px-3" for="patientDoctor">Doctor
                                                    Name<span class="text-danger"> *</span></label>

                                                <select id="docList" class="customDropSelection" name="patientDoctor" required>
                                                    <option value="" disabled selected>Select Doctor</option>
                                                    <?php
                                                    if ($showDoctors->status == 1) {
                                                        $showDoctors = $showDoctors->data;
                                                        foreach ($showDoctors as $showDoctorDetails) {
                                                            $doctorId = $showDoctorDetails->doctor_id;
                                                            $doctorName = $showDoctorDetails->doctor_name;
                                                            echo '<option value=' . $doctorId . '>' . $doctorName . '</option>';
                                                        }
                                                    } else {
                                                        echo '<option value="" select disabled>No data found</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="form-group col-sm-4">
                                                <button type="submit" name="submit" class="btn-block btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.js"></script>

    <!-- script for sweetalert2 -->
    <script src="<?php echo JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

    <!-- custom script for add patient -->
    <script src="<?php echo JS_PATH ?>add-patient.js"></script>


    <script>
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
        console.log(todayFullDate);
        document.getElementById("appointmentDate").setAttribute("min", todayFullDate);
        // $('#docView-Edit').on('click', function() {
        //     $('#docViewAndEdit').modal('show');

        // })
    </script>

</body>

</html>