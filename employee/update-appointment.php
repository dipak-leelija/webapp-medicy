<?php

    include 'config/dbconnect.php';

    require_once '../php_control/appoinments.class.php';

    require_once '../php_control/doctors.class.php';



    $appointments = new Appointments();//appointment class



session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {

  header("location: login.php");

  exit;

}

$appointmentID = $_GET['update-prescription'];



    

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // $appointmentDate = date('m d Y', time());

    $appointmentDate = $_POST["appointmentDate"];

    $patientName= $_POST["patientName"];

    $patientGurdianNAme= $_POST["patientGurdianNAme"];

    $patientEmail= $_POST["patientEmail"];

    $patientPhoneNumber = $_POST["patientPhoneNumber"];

    $patientWeight= $_POST["patientWeight"];

    $patientDOB= $_POST["patientDOB"];

    $gender= $_POST["gender"];

    $patientAddress1= $_POST["patientAddress1"];

    $patientAddress2= $_POST["patientAddress2"];

    $patientPS= $_POST["patientPS"];

    $patientDist= $_POST["patientDist"];

    $patientPIN= $_POST["patientPIN"];

    $patientState= $_POST["patientState"];

    $patientDoctor= $_POST["patientDoctor"];

    $patientDoctorTiming= $_POST["doctorTiming"];







    //Update Function 

    $updateAppointment = $appointments->updateAppointmentsbyId($appointmentDate,$patientName,$patientGurdianNAme,$patientEmail,$patientPhoneNumber,$patientDOB,$patientWeight,$gender,$patientAddress1,$patientAddress2,$patientPS,$patientDist,$patientPIN,$patientState,$patientDoctor,$patientDoctorTiming, /*Last Parameter For Appointment Id Which Details You Want to Update*/$appointmentID);



    //redirect if Updation Successfull

    if ($updateAppointment){

    header("location: appointments.php");

    // echo "Updated";

    exit();

    }

}



    //Fetching Fields by Id

    $selectAppointment = $appointments->appointmentsDisplaybyId($appointmentID);

    foreach($selectAppointment as $selectAppointmentDetails){

        $currentAppointmentID = $selectAppointmentDetails['appointment_id'];
        $getAppointmentDate = $selectAppointmentDetails['appointment_date'];
        $getPatientName = $selectAppointmentDetails['patient_name'];
        $getPatientGurdianName = $selectAppointmentDetails['patient_gurdian_name'];
        $getPatientEmail = $selectAppointmentDetails['patient_email'];
        $getPatientPhno = $selectAppointmentDetails['patient_phno'];
        $getPatientGender = $selectAppointmentDetails['patient_gender'];
        $getPatientDob = $selectAppointmentDetails['patient_dob'];
        $getPatientWeight = $selectAppointmentDetails['patient_weight'];
        $getPatientAddress1 = $selectAppointmentDetails['patient_addres1'];
        $getPatientAddress2 = $selectAppointmentDetails['patient_addres2'];
        $getPatientPs = $selectAppointmentDetails['patient_ps'];
        $getPatientDist = $selectAppointmentDetails['patient_dist'];
        $getPatientPin = $selectAppointmentDetails['patient_pin'];
        $getPatientState = $selectAppointmentDetails['patient_state'];
        $getDoctorForPatient = $selectAppointmentDetails['doctor_id'];
        $getShiftForPatient = $selectAppointmentDetails['patient_doc_shift'];

    }

    $doctors = new Doctors(); //Doctor Class 

    $selectDoctorByid = $doctors->showDoctorsForPatient($getDoctorForPatient);

    foreach($selectDoctorByid as $DoctorByidDetails){
        $doctorName =$DoctorByidDetails['doctor_name'];
    }



?>

<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">



    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css">

    <link rel="stylesheet" href="require/patient-style.css">

    <script src="../js/bootstrap-js-5/bootstrap.js"></script>

    <title>Enter Patient Details</title>

</head>



<body>

    <div class="container-fluid px-1 py-5 mx-auto">

        <div class="row d-flex justify-content-center">

            <div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center">

                <h3>XYZ Helthcare</h3>

                <!-- <p class="blue-text">Just answer a few questions<br> so that we can personalize the right experience for you.</p> -->

                <div class="card">

                    <h5 class="text-center mb-4">Fill The Patient Details</h5>

                    <form class="form-card" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientName">Patient Name<span

                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientName" name="patientName" placeholder="Enter Patient Name"

                                    value="<?php echo $getPatientName ?>">

                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientGurdianNAme">Patient's Gurdian

                                    Name<span class="text-danger"> *</span></label>

                                <input type="text" id="patientGurdianNAme" name="patientGurdianNAme"

                                    placeholder="Enter Patient's Gurdian Name"

                                    value="<?php echo $getPatientGurdianName ?>">

                            </div>

                        </div>

                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientEmail">Patient Email</label>

                                <input type="text" id="patientEmail" name="patientEmail" placeholder=""

                                    value="<?php echo $getPatientEmail ?>">

                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientPhoneNumber">Phone number<span

                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientPhoneNumber" name="patientPhoneNumber" placeholder=""

                                    value="<?php echo $getPatientPhno ?>">

                            </div>

                        </div>



                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="appointmentDate">Appointment Date<span

                                        class="text-danger"> *</span></label>

                                <input type="date" id="appointmentDate" name="appointmentDate" placeholder=""

                                    value="<?php echo $getAppointmentDate ?>">

                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientWeight">Weight <small>(in

                                        kg)</small><span class="text-danger"> *</span></label>

                                <input type="text" id="patientWeight" name="patientWeight" placeholder="" maxlength="3"

                                    value="<?php echo $getPatientWeight ?>">

                            </div>

                        </div>



                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientDOB">Age<span class="text-danger">

                                        *</span></label>

                                <input type="text" id="patientDOB" name="patientDOB" placeholder="" maxlength="3"

                                    minlength="1" value="<?php echo $getPatientDob ?>">

                            </div>



                            <div class="col-md-6 mt-4">

                                <label class="mb-3 mr-1" for="gender">Gender: </label>



                                <input type="radio" class="btn-check" name="gender" id="male" value="Male"

                                    autocomplete="off" required <?php 

                                    if($getPatientGender=="Male"){ echo "checked"; } ?>>

                                <label class="btn btn-sm btn-outline-secondary" for="male" value="Male" <?php 

                                    if($getPatientGender=="Male"){  echo "checked"; } ?>>Male</label>



                                <input type="radio" class="btn-check" name="gender" id="female" value="Female"

                                    autocomplete="off" required <?php 

                                    if($getPatientGender=="Female"){ echo "checked"; } ?>>

                                <label class="btn btn-sm btn-outline-secondary" for="female" value="Female" <?php 

                                    if($getPatientGender=="Female"){ echo "checked"; } ?>>Female</label>



                                <input type="radio" class="btn-check" name="gender" id="secret" value="Secret"

                                    autocomplete="off" required <?php 

                                    if($getPatientGender=="Secret"){ echo "checked"; } ?>>

                                <label class="btn btn-sm btn-outline-secondary" for="secret" value="Secret" <?php 

                                    if($getPatientGender=="Secret"){ echo "checked"; } ?>>Secret</label>

                                <div class="valid-feedback mv-up">You selected a gender!</div>

                                <div class="invalid-feedback mv-up">Please select a gender!</div>

                            </div>

                        </div>



                        <h5 class="text-center mb-4 mt-5">Patient Address</h5>



                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientAddress1">Address Line 1<span

                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientAddress1" name="patientAddress1" placeholder=""

                                    value="<?php echo $getPatientAddress1 ?>">

                            </div>



                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientAddress2">Address Line 2<span

                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientAddress2" name="patientAddress2" placeholder=""

                                    value="<?php echo $getPatientAddress2 ?>">

                            </div>

                        </div>

                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientPS">Police Station<span

                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientPS" name="patientPS" placeholder=""

                                    value="<?php echo $getPatientPs ?>">

                            </div>



                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientDist">District<span

                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientDist" name="patientDist" placeholder=""

                                    value="<?php echo $getPatientDist ?>">

                            </div>

                        </div>



                        <div class="row justify-content-between text-left">

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientPIN">PIN Code<span

                                        class="text-danger"> *</span></label>

                                <input type="text" id="patientPIN" name="patientPIN" placeholder="" maxlength="7"

                                    value="<?php echo $getPatientPin ?>">

                            </div>



                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientState">State<span

                                        class="text-danger"> *</span></label>

                                <select id="dropSelection" name="patientState">

                                    <?php echo '<option value='.$getPatientState.'>'.$getPatientState.'</option>'?>

                                    <option value="West bengal">West Bengal</option>

                                    <option value="Assam">Assam</option>

                                    <option value="Kerala">Kerala</option>

                                    <option value="Delhi">Delhi</option>

                                </select>

                            </div>

                        </div>



                        <div class="row justify-content-between text-left">

                            <h5 class="text-center mb-4 mt-5">Select Doctor</h5>

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="patientDoctor">Doctor Name<span

                                        class="text-danger"> *</span></label>

                                <select id="docList" class="customDropSelection" name="patientDoctor"

                                    onChange="getShift()">

                                    <!-- <option disabled >Select Doctor</option> -->

                                    <?php

                                        echo'<option selected value='.$getDoctorForPatient.'>'.$doctorName.'</option>';

                                        

                                            //doctor class's object is already decleared above

                                            $ShowDoctors = $doctors->showDoctors();

                                            foreach($ShowDoctors as $ShowDoctorsDetails){

                                                $doctorId = $ShowDoctorsDetails['doctor_id'];

                                                $doctorName = $ShowDoctorsDetails['doctor_name'];

                                                echo'<option value='.$doctorId.'>'. $doctorName.'</option>';



                                            }

                                        ?>

                                </select>

                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">

                                <label class="form-control-label px-3" for="doctorTiming">Time Slot<span

                                        class="text-danger"> *</span></label>

                                <select id="shiftList"  class="customDropSelection" name="doctorTiming">

                                    <?php echo '<option value='.$getShiftForPatient.'>'.$getShiftForPatient.'</option>'?>



                                    <!-- <option disabled selected>Select Doctor First</option> -->

                                    <!-- Option goes here by ajax -->

                                </select>

                            </div>

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

    function getShift() {

        var xmlhttp = new XMLHttpRequest();

        xmlhttp.open("GET", "ajax.php?doctor_shift=" + document.getElementById("docList").value, false);

        xmlhttp.send(null);

        document.getElementById("shiftList").innerHTML = xmlhttp.responseText;

    }

    </script>

</body>



</html>