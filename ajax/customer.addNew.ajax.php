<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'hospital.class.php';


//Classes Initilizing
$Patients        = new Patients();
$IdsGeneration   = new IdsGeneration();
$HealthCare      = new HealthCare;



$clinicInfo  = $HealthCare->showHealthCare($adminId);
$clinicInfo  = json_decode($clinicInfo, true);

if ($clinicInfo['status'] == 1) {
    $data = $clinicInfo['data'];
    $email    = $data['hospital_name'];
    $district = $data['dist'];
    $pin      = $data['pin'];
    $state    = $data['health_care_state'];
} else {
    echo "Error: " . $clinicInfo['msg'];
}
?>

<!doctype html>

<html lang="en">

<head>

    <!-- Required meta tags -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>patient-style.css">

    <link href="<?= CSS_PATH ?>new-sales.css" rel="stylesheet">
    <title>Enter Patient Details</title>

    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= PLUGIN_PATH ?>font-asesome-5/font-awesome-5.15.4-all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= ASSETS_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <script src="<?= JS_PATH ?>sweetAlert.min.js"></script>

</head>

<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $patientId = $IdsGeneration->patientidGenerate();

        $visited = 0;

        $added = $Patients->addPatients($patientId, $_POST['patientName'], ' ', $email, $_POST['patientPhoneNumber'], ' ', ' ', $_POST['patientAddress1'], ' ', $district, $pin, $state, $visited, $employeeId, NOW, $adminId);
        // print_r($added);

        if ($added) {
    ?>

            <script>
                swal({
                    title: "Success",
                    text: "Customer Added Successfully!",
                    icon: "success",
                    button: "OK"
                }).then(() => {
                    parent.location.reload();
                });
            </script>

        <?php
        } else {
        ?>

            <script>
                swal({
                    title: "Failed",
                    text: "Customer Addition Failed !",
                    icon: "error",
                    button: "OK"
                }).then(() => {
                    parent.location.reload();
                });
            </script>

    <?php
        }
    }

    ?>

    <!-- Page Wrapper -->
    <div>
        <div class="row d-flex justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 text-center">
                <div class="bg-light p-4 pb-0">
                    <!-- <h4 class="text-center mb-4 mt-0"><b>Fill The Patient Details</b></h4> -->
                    <form class="form-card" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label h6" for="patientName"><i class="fas fa-user" style="color: #8584e9; margin-right: 8px;"></i> Patient Name<span class="text-danger"> *</span></label>
                                <input class="newsalesAdd" type="text" id="patientName" name="patientName" placeholder="Enter Patient Name" value="" required autocomplete="off">
                            </div>

                            <div class="form-group col-sm-6 flex-column d-flex">
                                <label class="form-control-label h6" for="patientPhoneNumber"><i class="fas fa-phone" style="color: #8584e9; margin-right: 8px;"></i>Phone
                                    number<span class="text-danger"> *</span></label>
                                <input class="newsalesAdd" type="text" id="patientPhoneNumber" name="patientPhoneNumber" placeholder="Phone Number" maxlength="10" minlength="10" value="" required autocomplete="off">
                            </div>
                        </div>

                        <!-- <h6 class="text-center mb-4 mt-5">Patient Address</h6> -->
                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-12 flex-column d-flex">
                                <label class="form-control-label h6" for="patientAddress1"><i class="fas fa-home" style="color: #8584e9; margin-right: 8px;"></i>Address
                                    <span class="text-danger"> *</span></label>
                                <input class="newsalesAdd" type="text" id="patientAddress1" name="patientAddress1" placeholder="Address Line " value="" required autocomplete="off">
                            </div>
                        </div>
                        <div class="row justify-content-center mt-5">
                            <div class="form-group col-sm-4">
                                <button type="submit" name="submit" class="btn-block btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/End Part 1  -->
</body>

</html>