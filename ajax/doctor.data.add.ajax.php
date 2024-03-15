<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';



$DoctorCategory = new DoctorCategory;


$docSplzList = $DoctorCategory->showDoctorCategoryByAdmin($adminId);

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
    <!-- css for sweetalert2 -->
    <link href="<?php echo CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">

</head>

<body class="mx-2">

    <div class="container-fluid px-1  mx-auto">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-9 col-lg-10 col-md-10 text-center">
                <div class="col-xl-12 card shadow-sm p-4">

                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-name" class="col-form-label">Doctor Name:</label>
                            <input type="text" class="form-control" id="doc-name" autocomplete="off">
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-reg-no" class="col-form-label">Doctor Reg. No:</label>
                            <input type="text" class="form-control" id="doc-reg-no" autocomplete="off">
                        </div>
                    </div>

                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-splz" class="col-form-label">Doctor Specialization:</label>
                            <select class="form-control" name="" id="doc-splz" autocomplete="off">
                                <?php

                                foreach ($docSplzList as $splzList) {

                                ?>
                                    <option value="<?php echo $splzList['doctor_category_id'] ?>">
                                        <?php echo $splzList['category_name'] ?>
                                    </option>';

                                <?php
                                }

                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-degree" class="col-form-label">Doctor Degree:</label>
                            <input type="text" class="form-control" id="doc-degree" autocomplete="off">
                        </div>
                    </div>

                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-email" class="col-form-label">Doctor Email:</label>
                            <input type="email" class="form-control" id="email" autocomplete="off" onfocusout="checkMail(this)">
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-phno" class="col-form-label">Doctor Contact Number:</label>
                            <input type="number" class="form-control" id="doc-phno" autocomplete="off" onkeypress="checkMobNo(this)" onfocusout="checkContactNo(this)">
                        </div>
                    </div>

                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-12 flex-column d-flex">
                            <label for="doc-address" class="col-form-label">Doctor Address:</label>
                            <textarea class="form-control" id="doc-address" rows="1"></textarea autocomplete="off">
                        </div>
                    </div>

                    <div class="row justify-content-between text-left">
                        <div class="form-group col-sm-6 flex-column d-flex">
                            <label for="doc-with" class="col-form-label">Doctor Also With:</label>
                            <input type="text" class="form-control" id="doc-with" autocomplete="off">
                        </div>
                        <div class="form-group col-sm-6 flex-column d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn-sm btn-primary" onclick="addDocDetails()">Add New Doctor</button>
                        </div>
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

    <!-- sweetalert2 js link  -->
    <script src="<?php echo JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

    <!-- custom javascript for action -->
    <script src="<?php echo JS_PATH ?>doctors.js"></script>
</body>

</html>