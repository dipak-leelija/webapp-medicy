<?php
$page = "profile-setup";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'empRole.class.php';


$Utility    = new Utility;
$Admin      = new Admin;
$employees  = new Employees();
$desigRole = new Emproles();

$currentUrl = $Utility->currentUrl();

$showEmployees = $employees->employeesDisplay($adminId);
$showDesignation = $desigRole->designationRole($adminId);
$showDesignation = json_decode($showDesignation, true);

$profileDetails = array();
if ($_SESSION['ADMIN']) {
    $adminDetails = $Admin->adminDetails($adminId);
    $adminDetails = json_decode($adminDetails);
    if ($adminDetails->status) {
        $adminData = $adminDetails->data;

        foreach ($adminData as $adminData) {

            $firstName = $adminData->fname;
            $lastName = $adminData->lname;
            $image = $adminData->adm_img;
            $userName = $adminData->username;
            $email = $adminData->email;
            $phone = $adminData->mobile_no;
            $password = $adminData->password;
            $address = $adminData->address;
        }
    }
} else {

    $employeeDetails = $employees->employeeDetails($employeeId, $adminId);
    $employeeDetails = json_decode($employeeDetails);

    if ($employeeDetails->status) {
        $employeeData = $employeeDetails->data;

        foreach ($employeeData as $employeeData) {

            $empName = $employeeData->emp_name;

            $lastSpacePos = strrpos($empName, ' ');

            if ($lastSpacePos !== false) {

                $firstName = substr($empName, 0, $lastSpacePos);
                $lastName = substr($empName, $lastSpacePos + 1);
            }

            $firstName = $firstName;
            $lastName = $lastName;
            $image = $employeeData->emp_img;
            $userName = $employeeData->emp_username;
            $email = $employeeData->emp_email;
            $phone = $employeeData->emp_contact_no;
            $password = $employeeData->emp_password;
            $address = $employeeData->emp_address;
        }
    }
}





?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Employees</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/employees.css">
    <style>
        #toggle {
            /* position: absolutte;
            top: 25%;
            left: 200px; */
            position: relative;
            float: right;
            transform: translateY(-115%);
            width: 30px;
            height: 30px;
            background: url(img/hide-password.png);
            /* background-color: black; */
            background-size: cover;
            cursor: pointer;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <!-- <h1 class="h3 mb-2 text-gray-800">Employees</h1> -->

                    <!-- DataTales Example -->

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class=" d-flex justify-content-center align-items-center">
                                <div class="p-5 w-75">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class=" w-100 p-3 mb-2 bg-light ">
                                            <h1 class="h4 text-gray "><i class="fas fa-user"></i> <?= $userName ?></h1>
                                        </div>
                                    </div>
                                    <form class="user" action="_config/form-submission/profileSetup-form.php" method="post" enctype="multipart/form-data" id="edit-profile">

                                        <div class=" d-flex justify-content-center align-items-center mb-5">
                                            <!-- <div class="position-relative"> -->
                                            <img class="img-uv-view rounded-circle" style="width: 20%;" src="<?= ($image) ? $image : ASSETS_PATH ?>images/undraw_profile.svg" alt="">
                                            <div class="position-absolute translate-middle">
                                                <input type="file" style="display:none;" id="img-uv-input" accept="image/*" name="profile-image">
                                                <label for="img-uv-input" class="btn btn-sm btn-outline-secondary ml-5" style="margin-top: 115%;"><i class="fas fa-edit"></i></label>
                                            </div>
                                            <!-- </div> -->
                                        </div>
                                        <div class=" w-100 p-3 mb-2 bg-light ">
                                            <div class="form-group row mb-3">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control" id="fname" name="fname" maxlength="20" value="<?= $firstName; ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control " id="lname" name="lname" maxlength="20" value="<?= $lastName; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <input type="text" class="form-control " id="user-name" name="user-name" maxlength="24" value="<?= $userName; ?>">
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <input type="email" class="form-control " id="email" name="email" maxlength="80" value="<?= $email; ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="number" class="form-control " id="mobile-number" name="mobile-number" maxlength="10" value="<?= $phone; ?>" max="9999999999">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="password" class="form-control " id="password" name="password" maxlength="12" value="<?= $password; ?>" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="password" class="form-control " id="cpassword" name="cpassword" maxlength="12" placeholder="Repeat Password" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <!-- <input type="" class="form-control form-control-user" id="mobile-number" name="mobile-number" maxlength="10" placeholder="Mobile Number" max="9999999999"> -->
                                                <textarea class="form-control" id="exampleFormControlTextarea1" name="address" rows="3" value="<?= $address; ?>"></textarea>
                                            </div>
                                            <!-- <?php

                                                    if ($emailExists) {
                                                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Sorry!</strong> Given Email Already Exists.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>';
                                                    }

                                                    if ($userExists) {
                                                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Sorry!</strong> Username Already Exists.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>';
                                                    }

                                                    if ($diffrentPassword) {
                                                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Sorry!</strong> Password Does not match.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>';
                                                    }
                                                    ?> -->

                                            <button class="btn btn-primary btn-user btn-block" type="submit" name="submit">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include ROOT_COMPONENT . 'footer-text.php'; ?>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->



        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Custom Javascript -->
        <script src="<?php echo JS_PATH ?>custom-js.js"></script>

        <!-- Bootstrap core JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
        <script src="<?= PLUGIN_PATH ?>img-uv/img-uv.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="<?php echo PLUGIN_PATH ?>datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="<?php echo JS_PATH ?>demo/datatables-demo.js"></script>


        <script>
            $(document).ready(function() {
                $(document).on("click", ".delete-btn", function() {

                    if (confirm("Are you want delete data?")) {
                        empId = $(this).data("id");
                        //echo $empDelete.$this->conn->error;exit;

                        btn = this;
                        $.ajax({
                            url: "ajax/employee.Delete.ajax.php",
                            type: "POST",
                            data: {
                                id: empId
                            },
                            success: function(response) {

                                if (response == 1) {
                                    $(btn).closest("tr").fadeOut()
                                } else {
                                    // $("#error-message").html("Deletion Field !!!").slideDown();
                                    // $("success-message").slideUp();
                                    alert(response);
                                }

                            }
                        });
                    }
                    return false;

                })

            })
        </script>
        <script>
            function showHide(fieldId) {
                const password = document.getElementById(fieldId);
                const toggle = document.getElementById('toggle');

                if (password.type === 'password') {
                    password.setAttribute('type', 'text');
                    // toggle.classList.add('hide');
                } else {
                    password.setAttribute('type', 'password');
                    // toggle.classList.remove('hide');
                }
            }
        </script>


</body>

</html>