<?php
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);

require_once '../php_control/hospital.class.php';
require_once '../php_control/appoinments.class.php';
require_once '../php_control/doctors.class.php';
require_once '../php_control/patients.class.php';



session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    setcookie("appointmentId", $appointmentId, time() + (120 * 30), "/");
    header("location: config/login.php");
    exit;
}
 


// Fetching Hospital Info
$hospital = new HelthCare();
$appointments     = new Appointments();
$Patients         = new Patients();


$hospitalDetails = $hospital->showhelthCare();
foreach($hospitalDetails as $showShowHospital){
    $hospitalName = $showShowHospital['hospital_name'];
}

// echo $patientId;
if (isset($_POST['proceed'])) {
    
    $patientId = $_POST['patientNameId'];
    // $Patients = new Patients();
    $patientsDisplayByPId = $Patients->patientsDisplayByPId($patientId);
    foreach($patientsDisplayByPId as $patientsRow){
        $name           = $patientsRow['name'];
        $gurdianName    = $patientsRow['gurdian_name'];
        $phno           = $patientsRow['phno'];
        $email          = $patientsRow['email'];
        $gender         = $patientsRow['gender'];
        $address1       = $patientsRow['address_1'];
        $address2       = $patientsRow['address_2'];
        $patientPs      = $patientsRow['patient_ps'];
        $patientDist    = $patientsRow['patient_dist'];
        $patientPIN     = $patientsRow['patient_pin'];
        $patientState   = $patientsRow['patient_state'];
    }
    
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

    // Appointment iD Generated
    $appointmentId = $healthCareNameTrimed.''.$appointmentDateForId.''.$randCode ;
    

    // Inserting Into Appointments Database
    $addAppointment = $appointments->addFromInternal($appointmentId, $appointmentDate, $patientName, $patientGurdianName, $patientEmail, $patientPhoneNumber, $patientAge, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor);

    if ($addAppointment) {
      $patientsDisplayByPId = $Patients->patientsDisplayByPId($patientId);
      foreach($patientsDisplayByPId as $rowPatient){
        $visited = $rowPatient['visited'];
        // echo $visited;
        // exit;
        $visited = (int)$visited + 1;
      }
    //   echo $visited;
       // Inserting Into Patients Database
      $updatePatientsVisitingTime = $Patients->updatePatientsVisitingTime($patientId, $patientEmail, $patientPhoneNumber, $patientAge, $visited);
      if ($updatePatientsVisitingTime) {
        echo '<script>alert(Appointment Added!)</script>';
        setcookie("appointmentId", $appointmentId, time() + (120 * 30), "/");
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
                <h3 class="mb-4"><?php echo $hospitalName ?></h3>
                <h6 class="text-center mb-4">Fill The Patient Details</h6>
                <div class="card mt-4">
                <div style="align-items: left; text-align: left;">
                      
                      <a onclick="history.back()" style="cursor: pointer;"><i class="fa fa-arrow-left" aria-hidden="true"> Back</i></a>
                  
                  </div>
                    <form class="form-card" method="post">
                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientName">Patient Name<span
                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientName" name="patientName" placeholder="Enter Patient Name" value="<?php echo $name; ?>" required>
                                <input type="text" value="<?php echo $patientId; ?>" hidden name="patientId">

                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientGurdianName">Patient's Gurdian
                                    Name<span class="text-danger"> *</span></label>

                                <input type="text" id="patientGurdianName" name="patientGurdianName"
                                    placeholder="Enter Patient's Gurdian Name" value="<?php echo $gurdianName; ?>" required>

                            </div>

                        </div>

                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientEmail">Patient Email</label>

                                <input type="text" id="patientEmail" name="patientEmail" placeholder="Patient Email" value="<?php echo $email; ?>" required>

                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientPhoneNumber">Phone number<span
                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientPhoneNumber" name="patientPhoneNumber" placeholder="Patient Phone Number" maxlength="10" minlength="10" value="<?php echo $phno; ?>" required>

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
                                <label class="form-control-label px-3" for="patientAge">Age<span class="text-danger">*</span></label>

                                <input type="text" id="patientAge" name="patientAge" placeholder="Patient Age" maxlength="3" minlength="1" required>

                            </div>

                            <div class="col-md-6 mt-4">
                                <label class="mb-3 mr-1" for="gender">Gender: </label>

                                <input type="radio" class="btn-check" name="gender" id="male" value="Male"
                                    autocomplete="off" <?php if($gender == 'Male') echo 'checked'; ?> required>
                                <label class="btn btn-sm btn-outline-secondary" for="male" value="Male">Male</label>


                                <input type="radio" class="btn-check" name="gender" id="female" value="Female"
                                    autocomplete="off" <?php if($gender == 'Female') echo 'checked'; ?> required>
                                <label class="btn btn-sm btn-outline-secondary" for="female"
                                    value="Female">Female</label>


                                <input type="radio" class="btn-check" name="gender" id="secret" value="Secret"
                                    autocomplete="off" <?php if($gender == 'Secret') echo 'checked'; ?> required>
                                <label class="btn btn-sm btn-outline-secondary" for="secret"
                                    value="Secret">Secret</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>
                                <div class="invalid-feedback mv-up">Please select a gender!</div>
                            </div>
                        </div>



                        <h5 class="text-center mb-4 mt-5">Patient Address</h5>
                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label px-3" for="patientAddress1">Address Line 1<span class="text-danger"> *</span></label>

                                <input type="text" id="patientAddress1" name="patientAddress1" placeholder="Patient Address Line 1" value="<?php echo $address1; ?>" required>
                            </div>



                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientAddress2">Address Line 2</label>

                                <input type="text" id="patientAddress2" name="patientAddress2"
                                    placeholder="Patient Address Line 2" value="<?php echo $address2; ?>">

                            </div>

                        </div>

                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientPS">Police Station<span
                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientPS" name="patientPS" placeholder="Patient Police Station"
                                    required value="<?php echo $patientPs; ?>">

                            </div>



                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientDist">District<span
                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientDist" name="patientDist" placeholder="Patient District"
                                value="<?php echo $patientDist; ?>" required>

                            </div>

                        </div>



                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientPIN">PIN Code<span
                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientPIN" name="patientPIN" placeholder="Patient PIN Code"
                                    maxlength="7" value="<?php echo $patientPIN; ?>" required>

                            </div>



                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientState">State<span
                                        class="text-danger"> *</span></label>

                                <select id="dropSelection" name="patientState" required>

                                    <option value="" disabled>Select State</option>

                                    <option value="West bengal" <?php if($patientState == "West bengal"){ echo 'selected';} ?>">West Bengal</option>
                                    <option value="Other" <?php if($patientState == "Other"){ echo 'selected';} ?>">>Delhi</option>

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

                            <div class="form-group col-sm-4"> <button type="submit" name="submit" class="btn-block btn-primary">Submit</button> </div>

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