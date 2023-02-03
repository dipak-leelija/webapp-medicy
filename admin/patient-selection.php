<?php
require_once 'sessionCheck.php';//check admin loggedin or not

require_once '../php_control/patients.class.php';

$Patients = new Patients();
$showPatients = $Patients->patientsDisplay();

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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


    <!-- Custom styles for this page -->

    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/custom/appointment.css">

</head>

<body>

    <!-- Page Wrapper -->

    <div id="wrapper">

        <!-- sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'topbar.php'; ?>
                <!-- End of top bar -->


                <div class="container-fluid">
                    <h4 class=" mb-4 mt-0"><b>Fill Returning Patient Details</b></h4>
                    <div class="row d-flex justify-content-center">
                        <!-- <div class="col-xl-7 col-lg-8 col-md-10 col-11 text-center"> -->
                            <div class=" col-md-10 text-center">
                            <div class="card mt-0">


                                <form class="form-card" action="returning-appointment-entry.php" method="post">
                                    <div class="row justify-content-between text-left">
                                        <div class="col-md-12 mb-2">
                                        <label class="form-control-label" for="patientName">Patient Name<span class="text-danger"> *</span></label>
                                            <select class="form-control customDropSelection patient-select" data-live-search="true" name="patientName" id="patientList" onChange="getPhno()">
                                                <option value="" selected disabled> Search Patient Name </option>
                                                <?php
                                                    foreach ($showPatients as $patientsRow) {
                                                        // $data[] = $patientsRow;
                                                        echo "<option value='".$patientsRow['patient_id']."'>".$patientsRow['patient_id']." - ".$patientsRow['name']."</option>";
                                                    }
                                                    ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="row justify-content-between text-left">
                                        <div class="col-md-12 mb-2">
                                            <label for="">Patient Gurdian Name</label>
                                            <input class="form-control" id="gurdianName" type="text" onChange="getGurdian()">
                                        </div>
                                    </div> -->
                    

                                    <div class="row justify-content-end">
                                        <div class="form-group col-sm-4">
                                            <button type="submit" name="proceed" class="btn-block btn-primary" >Proceed</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <?php include 'footer-text.php'; ?>
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


                
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->

<script>

// function getPhno() {
//         var xmlhttp = new XMLHttpRequest();
//         xmlhttp.open("GET", "ajax/patientPhno.ajax.php?patient-id=" + document.getElementById("patientList").value, false);
//         xmlhttp.send(null);
//         document.getElementById("gurdianName").innerHTML = xmlhttp.responseText;
//     }

//     function getGurdian() {

//         // document.getElementById('shiftValue').innerHTML= ("The Selected Dropdown value is: "+formid.doctorTiming[formid.doctorTiming.selectedIndex].text)
//         var getGurdian = document.getElementById("gurdianName").value;
//         console.log(getGurdian);
//     }

    //patient selection js
    $(document).ready(function(){
        $('.patient-select').selectpicker();
        
    })


</script>


</body>

</html>