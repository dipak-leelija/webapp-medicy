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

        //Patient Id Generate
        $patientId = $IdsGeneration->patientidGenerate();

        $visited = 0;

        $added = $Patients->addPatients($patientId, $_POST['patientName'], ' ', $email, $_POST['patientPhoneNumber'], ' ', ' ', $_POST['patientAddress1'], ' ', $district, $pin, $state, $visited, $employeeId, ' ', NOW, $adminId);

        if ($added == TRUE) {
    ?>

            <script>
                swal("Success", "Customer Added Successfuly!", "success");
            </script>

        <?php
        } else {
        ?>

            <script>
                swal("Failed", "Customer Addition Failed !", "error");
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
                    <form action="lab-tests.php" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <label class="mb-0 mt-1" for="parent-test">Parent Test Name <span class="text-danger font-weight-bold">*</span></label>
                                    <select name="parent-test" class="form-control" id="parent-test" required>
                                        <option value="" disabled selected>Select Main Test</option>
                                        <?php
                                        // if ($showLabTypes && isset($showLabTypes['status']) && $showLabTypes['status'] == 1) {
                                        foreach ($showLabTypes as $labTypeName) {
                                            echo '<option value="' . $labTypeName['id'] . '">' . $labTypeName['test_type_name'] . '</option>';
                                        }
                                        // }
                                        ?>

                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="mb-0 mt-1" for="test-prep">What preparation is needed for this
                                        Checkup? <span class="text-danger font-weight-bold">*</span></Address></label>
                                    <textarea class="form-control" id="test-prep" name="test-prep" cols="30" rows="4" required></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="mb-0 mt-1" for="age-group">Age Group <span class="text-danger font-weight-bold">*</span></label>
                                    <select class="form-control" id="age-group" name="age-group" required>
                                        <option value="" disabled selected>Select Age Group <span class="text-danger font-weight-bold">*</span></option>
                                        <option value="Any Age Group">Any Age Group <span class="text-danger font-weight-bold">*</span></option>
                                        <option value="Bellow 18">Bellow 18</option>
                                        <option value="Above 18">Above 18</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <label class="mb-0 mt-1" for="subtest-name"> Sub Test Name <span class="text-danger font-weight-bold">*</span></label>
                                    <input class="form-control" id="subtest-name" name="subtest-name" type="text" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0 mt-1" for="subtest-unit"> Sub Test Unit <span class="text-danger font-weight-bold">*</span></label>
                                    <input class="form-control" id="subtest-unit" name="subtest-unit" type="text" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0 mt-1" for="subtest-dsc">Description <span class="text-danger font-weight-bold">*</span></label>
                                    <textarea class="form-control" id="subtest-dsc" name="subtest-dsc" cols="30" rows="4" required></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0 mt-1" for="price">Price <span class="text-danger font-weight-bold">*</span></label>
                                    <input class="form-control" id="price" name="price" type="number" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2">
                            <button class="btn btn-success me-md-2" name="add-new-subtest" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--/End Part 1  -->

</body>

</html>