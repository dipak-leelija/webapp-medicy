<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    setcookie("appointmentId", $appointmentId, time() + (120 * 30), "/");
    header("location: login.php");
    exit;
}


require_once '../php_control/hospital.class.php';
require_once '../php_control/appoinments.class.php';
require_once '../php_control/doctors.class.php';
require_once '../php_control/patients.class.php';


//Initilizing Classes
$hospital           = new HelthCare();
$appointments       = new Appointments();
$Patients           = new Patients();

// Fetching Hospital Info
$hospitalDetails = $hospital->showhelthCare();
foreach($hospitalDetails as $showShowHospital){
    $hospitalName = $showShowHospital['hospital_name'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $appointmentDate    = $_POST["appointmentDate"];
    $patientName        = $_POST["patientName"];
    $patientGurdianName = $_POST["patientGurdianName"];
    $patientEmail       = $_POST["patientEmail"];
    $patientPhoneNumber = $_POST["patientPhoneNumber"];
    $patientDOB         = $_POST["patientDOB"];
    $patientWeight      = $_POST["patientWeight"];
    $gender             = $_POST["gender"];
    $patientAddress1    = $_POST["patientAddress1"];
    $patientAddress2    = $_POST["patientAddress2"];
    $patientPS          = $_POST["patientPS"];
    $patientDist        = $_POST["patientDist"];
    $patientPIN         = $_POST["patientPIN"];
    $patientState       = $_POST["patientState"];
    $patientDoctor      = $_POST["patientDoctor"];
    // $patientDoctorShift= $_POST["doctorTiming"];


    $healthCareNameTrimed = strtoupper(substr($hospitalName, 0, 2));//first 2 leter oh healthcare center name
    $appointmentDateForId = str_replace("-", "", $appointmentDate);//removing hyphen from appointment date
    $randCode = rand(1000, 9999);//generating random number

    // Appointment iD Generated
    $appointmentId = $healthCareNameTrimed.''.$appointmentDateForId.''.$randCode ;

    //Patient Id Generate
    $prand      = rand(100000000, 999999999);
    $patientId  = 'PE'.$prand;


    //insert data into the database using addAppointments function of Appointments Class
    $insertData = $appointments->addFromInternal($appointmentId, $appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientDOB, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor);
    

    //redirect if the insertion has done
    if ($insertData) {
        $visited = 1;
         // Inserting Into Patients Database
        $addPatients = $Patients->addPatients($patientId, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $visited);
        if ($addPatients) {
            setcookie("appointmentId", $appointmentId, time() + (86400 * 30), "/");
        header("location: appointment-sucess.php");
        exit();
        }else{
            echo "<script>alert('Patient Not Inserted, Something is Wrong!')</script>";
        }
    }else{
      echo "New Record Insertion Failed ==>". mysqli_error($conn);
    }
}

?>

<!doctype html>

<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../css/font-awesome.css">
    <link rel="stylesheet" href="require/patient-style.css">
    <script src="../js/bootstrap-js-5/bootstrap.js"></script>
    <title>Enter Patient Details</title>

</head>

<body>

    <div class="container-fluid px-1 py-5 mx-auto">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center">
                <h3 class="mb-2"><?php echo $hospitalName ?></h3>
                <h6 class="text-center mb-4">Fill The Patient Details</h6>
                <div class="card mt-4">
                    <div style="align-items: left; text-align: left;">
                      
                        <a onclick="history.back()" style="cursor: pointer;"><i class="fa fa-arrow-left" aria-hidden="true"> Back</i></a>
                    
                    </div>
                    <form class="form-card" action="patient-registration.php" method="post">
                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientName">Patient Name<span class="text-danger"> *</span></label>
                                <input type="text" id="patientName" name="patientName" placeholder="Enter Patient Name" required>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientGurdianName">Patient's Gurdian
                                    Name<span class="text-danger"> *</span></label>

                                <input type="text" id="patientGurdianName" name="patientGurdianName"
                                    placeholder="Enter Patient's Gurdian Name" required>

                            </div>

                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientEmail">Patient Email</label>
                                <input type="text" id="patientEmail" name="patientEmail" placeholder="Patient Email" required>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientPhoneNumber">Phone number<span
                                        class="text-danger"> *</span></label>
                                <input type="text" id="patientPhoneNumber" name="patientPhoneNumber"
                                    placeholder="Patient Phone Number" maxlength="10" minlength="10" required>
                            </div>
                        </div>

                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="appointmentDate">Appointment Date<span
                                        class="text-danger"> *</span></label>
                                <input type="date" id="appointmentDate" name="appointmentDate" placeholder="" required>
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientWeight">Weight <small>(in
                                        kg)</small><span class="text-danger"> *</span></label>
                                <input type="text" id="patientWeight" name="patientWeight" placeholder="Patient Weight"
                                    maxlength="3" required>
                            </div>

                        </div>


                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientDOB">Age<span class="text-danger"> *</span></label>
                                <input type="text" id="patientDOB" name="patientDOB" placeholder="Patient Age" maxlength="3" minlength="1" required>
                            </div>

                            <div class="col-md-6 mt-4">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" id="male" value="Male" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-secondary" for="male" value="Male">Male</label>

                                <input type="radio" class="btn-check" name="gender" id="female" value="Female" autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-secondary" for="female" value="Female">Female</label>

                                <input type="radio" class="btn-check" name="gender" id="secret" value="Secret"  autocomplete="off" required>
                                <label class="btn btn-sm btn-outline-secondary" for="secret" value="Secret">Secret</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>

                            </div>

                        </div>



                        <h5 class="text-center mb-4 mt-5">Patient Address</h5>



                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientAddress1">Address Line 1<span
                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientAddress1" name="patientAddress1"
                                    placeholder="Patient Address Line 1" required>

                            </div>



                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientAddress2">Address Line 2</label>

                                <input type="text" id="patientAddress2" name="patientAddress2"
                                    placeholder="Patient Address Line 2">

                            </div>

                        </div>

                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientPS">Police Station<span
                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientPS" name="patientPS" placeholder="Patient Police Station"
                                    required>

                            </div>



                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientDist">District<span
                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientDist" name="patientDist" placeholder="Patient District"
                                    required>

                            </div>

                        </div>



                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientPIN">PIN Code<span
                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientPIN" name="patientPIN" placeholder="Patient PIN Code"
                                    maxlength="7" required>

                            </div>



                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientState">State<span
                                        class="text-danger"> *</span></label>

                                <select id="dropSelection" name="patientState" required>

                                    <option value="" disabled>Select State</option>

                                    <option value="West bengal" selected>West Bengal</option>

                                    <option value="Assam">Assam</option>

                                    <option value="Kerala">Kerala</option>

                                    <option value="Delhi">Delhi</option>

                                </select>

                            </div>

                        </div>



                        <div class="row justify-content-between text-left">

                            <h5 class="text-center mb-4 mt-5">Select Doctor</h5>

                            <div class="form-group col-sm-12 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientDoctor">Doctor Name<span
                                        class="text-danger"> *</span></label>

                                <select id="docList" class="customDropSelection" name="patientDoctor"
                                    onChange="getShift()" required>

                                    <option value="" disabled selected>Select Doctor</option>

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

                            <!-- <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="doctorTiming">Time Slot<span
                                        class="text-danger"> *</span></label>

                                <select id="shiftList" class="customDropSelection" name="doctorTiming"
                                    onChange="getShiftValues()" required>

                                    <option disabled selected>Select Doctor First</option>



                                    Option goes here by ajax 



                                </select>

                            </div> -->

                            <!-- <label id="shiftValue"></label> -->

                        </div>

                        <div class="row justify-content-end">

                            <div class="form-group col-sm-4"> <button type="submit"
                                    class="btn-block btn-primary">Submit</button> </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script type="text/javascript">
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
    </script>

</body>

</html>