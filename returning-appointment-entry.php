<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php';//check admin loggedin or not
require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'appoinments.class.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'patients.class.php';


//Creating Object of Appointments Class
$appointments = new Appointments();
$Patients = new Patients();


// Fetching Hospital Info
$HealthCare = new HelthCare();
$hospitalDetails = $HealthCare->showhelthCare();
foreach($hospitalDetails as $showShowHospital){
    $hospitalName = $showShowHospital['hospital_name'];
}

?>

<!doctype html>

<html lang="en">

<head>

    <!-- Required meta tags -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../css/patient-style.css">
    <script src="../js/bootstrap-js-5/bootstrap.js"></script>
    <title>Enter Patient Details</title>


    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->

    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->

    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/custom/appointment.css">

</head>

<body>
    <?php


if (isset($_POST['proceed'])) {
    $patientId = $_POST['patientName'];

    $patient = json_decode($Patients->patientsDisplayByPId($patientId));
    $name            = $patient->name;
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
    // echo 'Working';
    // exit;
    
    $healthCareNameTrimed = strtoupper(substr($hospitalName, 0, 2));//first 2 leter oh healthcare center name
    $appointmentDateForId = str_replace("-", "", $appointmentDate);//removing hyphen from appointment date
    $randCode = rand(1000, 9999);//generating random number

    //Patient Id Generate
    $prand      = rand(100000000, 999999999);
    $patientId  = 'PE'.$prand;

    // Appointment iD Generated
    $appointmentId = $healthCareNameTrimed.''.$appointmentDateForId.''.$randCode ;
    

    // Inserting Into Appointments Database
    $addAppointment = $appointments->addFromInternal($appointmentId, $patientId, $appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor);
    echo var_dump($addAppointment);

    if ($addAppointment) {
      $patientsDisplayByPId = json_decode($Patients->patientsDisplayByPId($patientId));
        $visited = $patientsDisplayByPId->visited;
        // echo $visited;
        // exit;
        $visited = (int)$visited + 1;

        // echo $visited;
        // exit;
    //   echo $visited;
       // Inserting Into Patients Database
      $updatePatientsVisitingTime = $Patients->updatePatientsVisitingTime($patientId, $patientEmail, $patientPhoneNumber, $patientAge, $visited);
      if ($updatePatientsVisitingTime) {
        echo '<script>alert(Appointment Added!)</script>';
        // setcookie("appointmentId", $appointmentId, time() + (120 * 30), "/");
        header("location: appointment-sucess.php");
        exit();
      }else{
        echo "<script>alert('Patient Not Inserted, Something is Wrong!')</script>";
      }
    }else{
      echo "Something is wrong! ";
    }
}
      ?>

    <!-- Page Wrapper -->

    <div id="wrapper">

        <!-- sidebar -->
        <?php include PORTAL_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include PORTAL_COMPONENT.'topbar.php'; ?>
                <!-- End of top bar -->


                <div class="container-fluid">
                    <h4 class=" mb-4 mt-0"><b>Fill Returning Patient Details</b></h4>
                    <div class="row d-flex justify-content-center">
                        <!-- <div class="col-xl-7 col-lg-8 col-md-10 col-11 text-center"> -->
                        <div class=" col-md-10 text-center">
                            <div class="card mt-0">


                                <form class="form-card" method="post">
                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label" for="patientName">Patient Name<span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" id="patientName" name="patientName" value="<?php echo $name; ?>">
                                            <input type="text" value="<?php echo $patientId; ?>" hidden name="patientId">

                                        </div>
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientGurdianName">Patient's Gurdian Name<span class="text-danger"> *</span></label>

                                            <input type="text" id="patientGurdianName" name="patientGurdianName" placeholder="Enter Patient's Gurdian Name" value="<?php echo $gurdianName; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientEmail">Patient Email</label>

                                            <input type="text" id="patientEmail" name="patientEmail"
                                                placeholder="Patient Email" value="<?php echo $email; ?>" required>
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientPhoneNumber">Phone
                                                number<span class="text-danger"> *</span></label>

                                            <input type="text" id="patientPhoneNumber" name="patientPhoneNumber"
                                                placeholder="Phone Number" value="<?php echo $phno; ?>" maxlength="10"
                                                minlength="10" required>
                                        </div>
                                    </div>


                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="appointmentDate">Appointment
                                                Date<span class="text-danger"> *</span></label>

                                            <input type="date" id="appointmentDate" name="appointmentDate"
                                                placeholder="" required>
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientWeight">Weight <small>(in
                                                    kg)</small><span class="text-danger"> *</span></label>

                                            <input type="text" id="patientWeight" name="patientWeight"
                                                placeholder="Weight in kg" maxlength="3" required>
                                        </div>
                                    </div>

                                    <div class="row justify-content-between text-left">

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientAge">Age<span
                                                    class="text-danger"> *</span></label>

                                            <input type="text" id="patientAge" name="patientAge" placeholder="Age"
                                                maxlength="3" minlength="1" required>

                                        </div>

                                        <div class="col-sm-6 mt-4">

                                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                                            <input type="radio" class="btn-check" name="gender" id="male" value="Male"
                                                autocomplete="off" <?php if ($gender == "Male") { echo "checked";} ?>
                                                required>
                                            <label class="btn btn-sm btn-outline-secondary" for="male"
                                                value="Male">Male</label>

                                            <input type="radio" class="btn-check" name="gender" id="female"
                                                value="Female" autocomplete="off"
                                                <?php if ($gender == "Female") { echo "checked";} ?> required>
                                            <label class="btn btn-sm btn-outline-secondary" for="female"
                                                value="Female">Female</label>

                                            <input type="radio" class="btn-check" name="gender" id="secret"
                                                value="Secret" autocomplete="off"
                                                <?php if ($gender == "Secret") { echo "checked";} ?> required>
                                            <label class="btn btn-sm btn-outline-secondary" for="secret"
                                                value="Secret">Others</label>

                                            <div class="valid-feedback mv-up">You selected a gender!</div>
                                            <div class="invalid-feedback mv-up">Please select a gender!</div>
                                        </div>

                                    </div>

                                    <h5 class="text-center mb-4 mt-5">Patient Address</h5>

                                    <div class="row justify-content-between text-left">

                                        <div class="form-group col-sm-6 flex-column d-flex">

                                            <label class="form-control-label px-3" for="patientAddress1">Address Line
                                                1<span class="text-danger"> *</span></label>

                                            <input type="text" id="patientAddress1" name="patientAddress1"
                                                placeholder="Address Line 1" value="<?php echo $addres1; ?>" required>

                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">

                                            <label class="form-control-label px-3" for="patientAddress2">Address Line
                                                2<span class="text-danger"> *</span></label>
                                            <input type="text" id="patientAddress2" name="patientAddress2"
                                                placeholder="Address Line 2" value="<?php echo $addres2; ?>">
                                        </div>
                                    </div>

                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientPS">Police Station<span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" id="patientPS" name="patientPS"
                                                placeholder="Police Station" value="<?php echo $addres2; ?>" required>

                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientDist">District<span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" id="patientDist" name="patientDist"
                                                placeholder="District" value="<?php echo $patientDist; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientPIN">PIN Code<span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" id="patientPIN" name="patientPIN" placeholder="Pin Code"
                                                maxlength="7" value="<?php echo $patientPIN; ?>" required>
                                        </div>

                                        <div class="form-group col-sm-6 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientState">State<span
                                                    class="text-danger"> *</span></label>
                                            <select id="dropSelection" name="patientState" required>
                                                <option disabled>Select State</option>
                                                <option value="West bengal" <?php if($patientState == "West Bengal"){ echo "selected";} ?>>West Bengal</option>
                                                <option value="Other" <?php if($patientState == "Other"){ echo "selected";} ?>>Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row justify-content-between text-left">

                                        <h5 class="text-center mb-4 mt-5">Select Doctor</h5>
                                        <div class="form-group col-sm-12 flex-column d-flex">
                                            <label class="form-control-label px-3" for="patientDoctor">Doctor Name<span
                                                    class="text-danger"> *</span></label>

                                            <select id="docList" class="customDropSelection" name="patientDoctor" required>
                                                <option disabled selected>Select Doctor</option>
                                                <?php

                                            $doctors = new Doctors();
                                            $showDoctors = $doctors->showDoctors();
                                            foreach ($showDoctors as $showDoctorDetails) {
                                                $doctorId = $showDoctorDetails['doctor_id'];
                                                $doctorName = $showDoctorDetails['doctor_name'];
                                                echo'<option value='.$doctorId.'>'. $doctorName.'</option>';
                                            }
                                        ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="form-group col-sm-4">
                                            <button type="submit" name="submit"
                                                class="btn-block btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <?php include PORTAL_COMPONENT.'footer-text.php'; ?>
                <!-- End of Footer -->

                <!-- Bootstrap core JavaScript-->
                <script src="vendor/jquery/jquery.min.js"></script>
                <script src="vendor/jquery/jquery.slim.js"></script>
                <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>
                <script src="../js/bootstrap-js-4/bootstrap.min.js"></script>
                <script src="../js/bootstrap-js-4/bootstrap.js"></script>



                <!-- Core plugin JavaScript-->
                <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="js/sb-admin-2.min.js"></script>

                <!-- Page level plugins -->
                <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

                <!-- Page level custom scripts -->
                <!-- <script src="js/demo/chart-area-demo.js"></script> -->
                <!-- <script src="js/demo/chart-pie-demo.js"></script> -->

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