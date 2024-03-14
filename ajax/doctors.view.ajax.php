<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';


$docId = $_GET['docId'];

$Doctors = new Doctors();
$doctorCategory = new DoctorCategory();


$showDoctor = json_decode($Doctors->showDoctorNameById($docId));
// print_r($showDoctor);

$docSplzList = $doctorCategory->showDoctorCategoryByAdmin($adminId);
// print_r($docSplzList);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-test.css">

</head>

<body class="mx-2">

    <?php
    $showDoctor = $showDoctor->data;

    foreach ($showDoctor as $doctor) {
        $docId = $doctor->doctor_id;
        $docRegNo = $doctor->doctor_reg_no;
        $docName = $doctor->doctor_name;
        $docSplz = $doctor->doctor_specialization;
        $docDegree = $doctor->doctor_degree;
        $docAlsoWith = $doctor->also_with;
        $docAddress = $doctor->doctor_address;
        $docEmail = $doctor->doctor_email;
        $docPhno = $doctor->doctor_phno;

        // echo $docSplz;
    }
    ?>

    <div class="container-fluid px-1  mx-auto">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-9 col-lg-10 col-md-10 text-center">
                <div class="col-xl-12 card shadow-sm p-4">
                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-name" class="col-form-label">Doctor Name:</label>
                            <input type="text" class="form-control" id="doc-name" value="<?php echo $docName; ?>" autocomplete="off">
                            <input type="text" class="form-control" id="doc-id" value="<?php echo $docId; ?>" readonly hidden>
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-with" class="col-form-label">Doctor Also With:</label>
                            <input type="text" class="form-control" id="doc-with" value="<?php echo $docAlsoWith; ?>" autocomplete="off">
                        </div>
                    </div>

                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-splz" class="col-form-label">Doctor Specialization:</label>
                            <select class="form-control" name="" id="doc-splz" autocomplete="off">
                                <?php

                                foreach ($docSplzList as $splzList) {

                                ?>
                                    <option <?= $docSplz == $splzList['doctor_category_id'] ? 'selected' : ''; ?> value="<?php echo $splzList['doctor_category_id'] ?>">
                                        <?php echo $splzList['category_name'] ?>
                                    </option>';

                                <?php
                                }

                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-email" class="col-form-label">Doctor Email:</label>
                            <input type="email" class="form-control" id="email" value="<?php echo $docEmail; ?>" autocomplete="off">
                        </div>
                    </div>

                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-reg-no" class="col-form-label">Doctor Reg. No:</label>
                            <input type="text" class="form-control" id="doc-reg-no" value="<?php echo $docRegNo; ?>">
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-phno" class="col-form-label">Doctor Contact Number:</label>
                            <input type="text" class="form-control" id="doc-phno" value="<?php echo $docPhno; ?>" autocomplete="off">
                        </div>
                    </div>

                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-degree" class="col-form-label">Doctor Degree:</label>
                            <input type="text" class="form-control" id="doc-degree" value="<?php echo $docDegree; ?>" autocomplete="off">
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-address" class="col-form-label">Doctor Address:</label>
                            <textarea class="form-control" id="doc-address" rows="3"><?php echo $docAddress; ?></textarea autocomplete="off">
                        </div>
                    </div>

                    <div class="alert alert-success" role="alert" id="reportUpdateSuccess" style="display: none;">
                        
                    </div>

                    <div class="alert alert-danger" role="alert" id="reportUpdateFail" style="display: none;">
                       
                    </div>

                    <div class="d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-sm btn-primary" onclick="editDoc()">Save changes</button>
                    </div>

                </div>
            </div>
        </div>
    </div>


    

    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <!-- <script src="<?php echo PLUGIN_PATH ?>bootstrap/js/bootstrap.bundle.min.js"></script> -->
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

    <script src="<?php echo JS_PATH ?>doctors.js"></script>
</body>

</html>