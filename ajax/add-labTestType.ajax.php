<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'hospital.class.php';



//Classes Initilizing
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
    ?>
        <script>
            swal("Failed", "Customer Addition Failed !", "error");
        </script>
    <?php
    }
    ?>
    <!-- Page Wrapper -->
    <div>
        <div class="row d-flex justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 text-center">
                <div class="bg-light p-4 pb-0">
                    <form action="../lab-tests.php" method="post" enctype="multipart/form-data">
                        <div class="col-12 d-flex">
                            <div class="col-md-6 justify-content-between text-left">
                                <div class="form-group col-sm-12">
                                    <div class="alert alert-danger d-none" id="err-show" role="alert">
                                        Only jpg/jpeg and png files are allowed!
                                    </div>
                                    <div class="d-flex justify-content-around align-items-center">
                                        <img class="mb-0 mt-3 rounded img-uv-view border border-dark" src="" width="100%" height="180" alt="">
                                    </div>
                                    <div class="mt-1">
                                        <input type="file" style="display:none;" id="img-uv-input" accept=".jpg,.jpeg,.png" name="labTest-img" onchange="validateFileType()">
                                        <label for="img-uv-input" class="btn btn-primary">Change
                                            Image</label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 justify-content-between text-left">

                                <div class="form-group col-sm-12 flex-column d-flex">
                                    <label class="form-control-label h6" for="patientName"><i class="fas fa-vial" style="color: #8584e9; margin-right: 8px;"></i> Test Name<span class="text-danger"> *</span></label>
                                    <input class="newsalesAdd" type="text" id="test-name" name="test-name" placeholder="Enter Test Name" value="" required autocomplete="off">
                                </div>

                                <div class="form-group col-sm-12 flex-column d-flex">
                                    <label class="form-control-label h6" for="provided-by"><i class="fas fa-flask-vial" style="color: #8584e9; margin-right: 8px;"></i> Provided By<span class="text-danger"> *</span></label>

                                    <input class="newsalesAdd" type="text" id="provided-by" name="provided-by" placeholder="Test Provider" required autocomplete="off">
                                </div>

                                <div class="form-group col-sm-12 flex-column d-flex">
                                    <label class="form-control-label h6" for="provided-by"><i class="fas fa-waveform" style="color: #8584e9; margin-right: 8px;"></i>Description<span class="text-danger"> *</span></label>

                                    <textarea class="newsalesAdd" type="text" name="test-dsc" id="test-dsc" cols="30" rows="3" required autocomplete="off"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- <h6 class="text-center mb-4 mt-5">Patient Address</h6> -->

                        <div class="row justify-content-center mt-2">
                            <div class="form-group col-sm-4">
                                <button class="btn btn-success me-md-2" type="submit" name="new-lab-test-data">Submit</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--/End Part 1  -->

    <script src="<?= PLUGIN_PATH ?>img-uv/img-uv.js"></script>
    <script>
        function validateFileType() {
            var fileName = document.getElementById("img-uv-input").value;
            console.log(fileName);
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                document.getElementById("err-show").classList.add("d-none");
            } else {
                document.getElementById("err-show").classList.remove("d-none");
                // Show current image when error occurs
                document.querySelector('.img-uv-view').src = "";
            }
        }
    </script>
</body>

</html>