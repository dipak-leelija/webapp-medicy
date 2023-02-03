<?php


require_once '../php_control/hospital.class.php';
require_once '../php_control/appoinments.class.php';
require_once '../php_control/doctors.class.php';

require_once '../php_control/patients.class.php';

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    setcookie("appointmentId", $appointmentId, time() + (120 * 30), "/");
    header("location: login.php");
    exit;
}


$Patients = new Patients();
$showPatients = $Patients->patientsDisplay();


// Fetching Hospital Info
$hospital = new HelthCare();
$hospitalDetails = $hospital->showhelthCare();
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
    <link rel="stylesheet" href="require/patient-style.css">
    <link rel="stylesheet" href="../css/font-awesome.css">

    <script src="../js/bootstrap-js-5/bootstrap.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
 

    <title>Enter Patient Details</title>

</head>

<body>

    <div class="container-fluid px-1 py-5 mx-auto">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center">

                <h3 class="mb-4"><?php echo $hospitalName ?></h3>

                <div class="card mt-4">
                <div style="align-items: left; text-align: left;">
                      
                      <a onclick="history.back()" style="cursor: pointer;"><i class="fa fa-arrow-left" aria-hidden="true"> Back</i></a>
                  
                  </div>
                    <h5 class="text-center mb-4">Search or Select Patient</h5>
                    <form class="form-card" action="returning-patient.php" method="post">
                        <div class="row justify-content-between text-left">
                            <div class="col-md-12 mb-2">
                                <label class="form-control-label" for="patientNameId">Patient Name<span
                                        class="text-danger"> *</span></label>
                                <select class="form-control customDropSelection patient-select" data-live-search="true"
                                    name="patientNameId" id="patientNameId" ">
                                    <option value="" selected disabled> Search Patient Name </option>
                                    <?php
                                        foreach ($showPatients as $patientsRow) {
                                            // $data[] = $patientsRow;
                                            echo "<option value='".$patientsRow['patient_id']."'>".$patientsRow['phno']." - ".$patientsRow['name']."</option>";
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="form-group col-sm-4"> 
                                <button type="proceed" name="proceed" class="btn-block btn-primary">Proceed</button> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../admin/vendor/jquery/jquery.slim.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $('.patient-select').selectpicker();

    })
    </script>

</body>

</html>