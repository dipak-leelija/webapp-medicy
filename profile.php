<?php
$page = "employees";
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
$employees  = new Employees();
$desigRole = new Emproles();

$currentUrl = $Utility->currentUrl();

$showEmployees = $employees->employeesDisplay($adminId);
$showDesignation = $desigRole->designationRole($adminId);
$showDesignation = json_decode($showDesignation, true);
// print_r($showDesignation);

//Employee Class Initilzed
// $employees = new Employees();




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
                        <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray mb-4">Profile</h1>
                            </div>
                            <form class="user" action="register.php" method="post">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="fname" name="fname" maxlength="20" placeholder="First Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="lname" name="lname" maxlength="20" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="user-name" name="user-name" maxlength="24" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="email" name="email" maxlength="80" placeholder="Email Address">
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-user" id="mobile-number" name="mobile-number" maxlength="10" placeholder="Mobile Number" max="9999999999">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="password" name="password" maxlength="12" placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="cpassword" name="cpassword" maxlength="12" placeholder="Repeat Password" required>
                                    </div>
                                </div>
                                <?php

                                    if($emailExists){
                                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Sorry!</strong> Given Email Already Exists.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>';
                                    }

                                    if($userExists){
                                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Sorry!</strong> Username Already Exists.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>';
                                    }

                                    if($diffrentPassword){
                                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Sorry!</strong> Password Does not match.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>';
                                    }
                                ?>

                                <button class="btn btn-primary btn-user btn-block" type="submit" name="register">Update</button>
                                <!-- <hr> -->
                                <!-- <a href="index.html" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a> -->
                            </form>
                            <!-- <hr> -->
                            <!-- <div class="text-center" style="margin-top:15px;">
                                <a class="small" href="forgot-password.html">Reset Password</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div> -->
                        </div>
                    </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
                <!--Entry Section-->
                <div class="col" style="margin: 0 auto; width:98%;">
                    <div class="card shadow mb-4">

                    </div>
                    <!-- ...........modal start........ -->
                    <div class="modal fade bd-example-modal-lg " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="employees.php" method="post">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-name"> Employee Name:</label>
                                                    <input class="form-control" type="text" name="emp-name" id="emp-name" maxlength="30" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-username">Employee Username:</label>
                                                    <input class="form-control" type="text" name="emp-username" id="emp-username" maxlength="12" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-mail">Employee Mail:</label>
                                                    <input class="form-control" type="email" name="emp-mail" id="emp-mail" maxlength="100" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-role">Employee Role:</label>
                                                    <select class="form-control" name="emp-role" id="emp-role" required>
                                                        <option value="role1">Choose role..</option>
                                                        <?php foreach ($showDesignation as $desig) { ?>
                                                            <option value="<?php echo $desig['id']; ?>"><?php echo $desig['desig_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-address">Full Address:</label>
                                                    <textarea class="form-control" name="emp-address" id="emp-address" cols="30" rows="4" maxlength="255"></textarea>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-pass">Password:</label>
                                                    <input class="form-control" type="password" name="emp-pass" id="emp-pass" maxlength="12" required>
                                                    <div id="toggle" onclick="showHide('emp-pass');"></div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-conf-pass">Confirm Password:</label>
                                                    <input class="form-control" type="password" name="emp-cpass" id="emp-conf-pass" maxlength="12" required>
                                                    <div id="toggle" onclick="showHide('emp-conf-pass');"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2"> -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button class="btn btn-success me-md-2" type="submit" name="add-emp">Add Now</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- ...........modal end........ -->
                    </div>
                </div>
                <!--End Entry Section-->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Emp Edit and View Modal -->
    <div class="modal fade" id="empViewAndEditModal" tabindex="-1" role="dialog" aria-labelledby="empViewAndEditModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="empViewAndEditModalLabel">Employee Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body viewnedit">
                    <!-- MODAL CONTENT GOES HERE BY AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="refreshPage()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Emp Edit and View Modal End -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Custom Javascript -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script>
        viewAndEdit = (empId) => {
            let employeeId = empId;
            let url = "ajax/emp.view.ajax.php?employeeId=" + employeeId;
            $(".viewnedit").html('<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');
        } // end of viewAndEdit function
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

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