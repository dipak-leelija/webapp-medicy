<?php
session_start();
if (!isset($_SESSION['mobNum'])) {
  header('Location: ../login.php');
}

require_once '../php_control/appoinments.class.php';
require_once '../php_control/hospital.class.php';
require_once '../php_control/doctors.class.php';



// Classes Initilized
$hospital = new HelthCare();
$appointments = new Appointments();

// Hospital Data fetched
$hospitalDetails = $hospital->showhelthCare();
foreach ($hospitalDetails as $showShowHospital) {
  $hospitalName = $showShowHospital['hospital_name'];
}



if (isset($_POST['submit'])) {
  $patientName = $_POST['patientName'];
  $patientGurdianNAme = $_POST['ateintGurdianName'];
  $patientEmail = $_POST['email'];
  $patientPhoneNumber = $_POST['phone'];
  $patientDOB = $_POST['age'];
  $gender = $_POST['gender'];
  $patientWeight = $_POST['weight'];
  $appointmentDate =  $_POST['appointmentDate'];
  $patientDoctor = $_POST['doctor'];
  $patientDoctorShift = $_POST['appointmentSlot'];
  $patientAddress1 = $_POST['addressLine1'];
  $patientAddress2 = $_POST['addressLine2'];
  $patientPS = $_POST['policeStation'];
  $patientDist = $_POST['district'];
  $patientPIN = $_POST['pin'];
  $patientState = $_POST['state'];

  $healthCareNameTrimed = strtoupper(substr($hospitalName, 0, 2)); //first 2 leter oh healthcare center name
  $appointmentDateForId = str_replace("-", "", $appointmentDate); //removing hyphen from appointment date
  $randCode = rand(1000, 9999); //generating random number

  // Appointment iD Generated
  $appointmentId = $healthCareNameTrimed . '' . $appointmentDateForId . '' . $randCode;


  //insertion into DB
  $addAppointment = $appointments->addAppointments($appointmentId, $patientId, $appointmentDate, $patientName, $patientGurdianNAme, $patientEmail, $patientPhoneNumber, $patientDOB, $patientWeight, $gender, $patientAddress1, $patientAddress2, $patientPS, $patientDist, $patientPIN, $patientState, $patientDoctor, $patientDoctorShift);

  if ($addAppointment) {
    echo "Perfect";
    unset($_SESSION['mobNum']);
    session_destroy();
    exit;
  } else {
    echo "So Sorry";
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
    
  <script src="script.js" defer></script>
  <title>Registraion Form</title>
</head>

<body>
  <form action="index.php" class="form" method="post">
    <h1 class="text-center">Book Your Appointment</h1>
    <!-- Progress bar -->
    <div class="progressbar">
      <div class="progress" id="progress"></div>

      <div class="progress-step progress-step-active" data-title="Intro"></div>
      <div class="progress-step" data-title="Doctor Selection"></div>
      <!-- <div class="progress-step" data-title="ID"></div> -->
      <div class="progress-step" data-title="Address"></div>
    </div>

    <!-- Step 1 -->
    <div class="form-step form-step-active mtm-2">
      <div class="input-group">
        <label for="patientName">Patient Name</label>
        <input type="text" name="patientName" id="patientName" placeholder="Enter Patient Name" required />
      </div>
      <div class="input-group mtm-1">
        <label for="ateintGurdianName">Pateint Gurdian Name</label>
        <input type="text" name="ateintGurdianName" id="pateintGurdianName" placeholder="Enetr Pateint Gurdian Name" required />
      </div>
      <div class="line-group mtm-1">
        <div>
          <label for="email">Email</label>
          <input type="email" name="email" id="email" placeholder="Enter Email Address" />
        </div>
        <div>
          <label for="phone">Phone</label>
          <input type="text" name="phone" id="phone" value="<?php echo $_SESSION['mobNum']; ?>" required disabled />
          <input type="text" name="phone" id="phone" value="<?php echo $_SESSION['mobNum']; ?>" required style="display: none;" />

        </div>
      </div>
      <div class="line-group mt-1">
        <div>
          <label for="age">Age</label>
          <input type="text" name="age" id="age" placeholder="Patient Age" required />
        </div>
        <div>
          <label for="gender">Gender</label>
          <select name="gender" id="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Secret">Secret</option>
          </select>
        </div>
      </div>
      <div class="line-group mt-1">
        <div>
          <label for="weight">Weight</label>
          <input type="text" name="weight" id="weight" placeholder="Patient Age" required />
        </div>
        <div>
          <label for="appointmentDate">Appointment Date</label>
          <input type="date" name="appointmentDate" id="appointmentDate" required />
        </div>
      </div>
      <div class="mt-1">
        <a class="btn btn-next width-50 ml-auto">Next</a>
      </div>
    </div>
    <!--end of Step 1 -->

    <!-- Step 2 -->
    <div class="form-step mtm-2">
      <h3>Select Doctor and Slot</h3>
      <div class="line-group">
        <div>
          <label for="doctor">Doctor</label>
          <select onchange="getShift()" name="doctor" id="docList" required>
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
        <div>
          <label for="appointmentSlot">Slot Date</label>
          <select id="shiftList" class="customDropSelection" name="appointmentSlot"
                                    onChange="getShiftValues()" required>

                                    <option disabled selected>Select Doctor First</option>



                                    <!-- Option goes here by ajax -->



                                </select>
        </div>
                            <label id="shiftValue"></label>

      </div>

      <div class="btns-group mt-1">
        <a class="btn btn-prev">Previous</a>
        <a class="btn btn-next">Next</a>
      </div>
    </div>
    <!--End of Step 2 -->

    <!--Step 4 -->
    <div class="form-step">
      <div class="line-group">
        <div>
          <label for="addressLine1">Address Line 1</label>
          <input type="text" name="addressLine1" id="addressLine1" required />
        </div>
        <div>
          <label for="phone">Address Line 2</label>
          <input type="text" name="addressLine2" id="addressLine2" />
        </div>
      </div>
      <div class="line-group">
        <div>
          <label for="policeStation">Police Station</label>
          <input type="text" name="policeStation" id="policeStation" required />
        </div>
        <div>
          <label for="district">District</label>
          <input type="text" name="district" id="district" required />
        </div>
      </div>
      <div class="line-group">
        <div>
          <label for="pin">PIN Code</label>
          <input type="text" name="pin" id="pin" required />
        </div>
        <div>
          <label for="state">State</label>
          <select name="state" id="state" required>
            <option value="West Bengal">West Bengal</option>
            <option value="Others">Others</option>
          </select>
        </div>
      </div>

      <div class="btns-group mt-1">
        <a class="btn btn-prev">Previous</a>
        <input type="submit" value="Submit" name="submit" class="btn" />
      </div>
    </div>
    <!--End of Step 4 -->
  </form>


     <script type="text/javascript">
    function getShift() {

        var xmlhttp = new XMLHttpRequest();

        xmlhttp.open("GET", "../employee/ajax.php?doctor_shift=" + document.getElementById("docList").value, false);

        xmlhttp.send(null);

        document.getElementById("shiftList").innerHTML = xmlhttp.responseText;

    }

    function getShiftValues() {



        // document.getElementById('shiftValue').innerHTML= ("The Selected Dropdown value is: "+formid.doctorTiming[formid.doctorTiming.selectedIndex].text)

        var getShiftValues = document.getElementById("shiftList").value;

        console.log(getShiftValues);

    }
    </script>
    <script language="javascript">
    $(document).ready(function () {
        $("#appointmentDate").datepicker({
            minDate: 0
        });
    });
</script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script> -->

</body>

</html>