<?php
$page = "employees";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
// require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'empRole.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'utility.class.php';

$Utility    = new Utility;
$employees  = new Employees();
$desigRole  = new Emproles();
$IdsGeneration = NEW IdsGeneration;

$currentUrl = $Utility->currentUrl();

$showEmployees = $employees->employeesDisplay($ADMINID);
$showDesignation = json_decode($desigRole->designationRoleCheckForLogin(), true);

if (isset($_POST['add-emp']) == true) {


    $fName      = $_POST['fname'];
    $lName      = $_POST['lname'];
    $empUsername  = $_POST['emp-username'];
    $empMail      = $_POST['emp-mail'];
    $empContact   = $_POST['emp-contact'];
    $empRole      = $_POST['emp-role'];
    $empPass      = $_POST['emp-pass'];
    $empCPass     = $_POST['emp-cpass'];
    $empAddress   = $_POST['emp-address'];


    if ($empPass == $empCPass) {
        $wrongPasword = false;
        $empId = $IdsGeneration->empIdGenerate($HEALTHCARENAME);

        $addEmployee = $employees->addEmp($empId, $ADMINID, $empUsername, $fName, $lName, $empRole, $empMail, $empContact, $empAddress, $empPass);
        // print_r($addEmployee);
        if ($addEmployee['result']) {
            $Utility->redirectURL($currentUrl, 'SUCCESS', 'Employee Added Successfuly!');
        } else {
            $response = json_encode($addEmployee);
            echo "<script>alert($response)</script>";
        }
    } else {
        echo "<script>alert('Password Did Not Matched!')</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Employees - <?= $HEALTHCARENAME ?></title>

    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/employees.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>/custom/password-show-hide.css" type="text/css" />

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

                    <!-- DataTales Example -->

                    <div class="card shadow-sm">
                        <div class="card-header py-3">
                            <div class="col-12 d-flex align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Employees List</h6>
                                
                                <?php
                                if (isset($_GET['action'])) {
                                    if (isset($_GET['msg'])) {
                                        echo "<p><strong>{$_GET['msg']}</strong></p>";
                                    }
                                }
                                ?>
                                
                                
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bd-example-modal-lg">Add New Employee</button>

                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Email</th>
                                            <th>Last Update</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        if (!empty($showEmployees)) {

                                            foreach ($showEmployees as $showEmployees) {
                                                $empId          = $showEmployees['emp_id'];
                                                $empUsername    = $showEmployees['emp_username'];
                                                $empMail        = $showEmployees['emp_email'];
                                                $fName          = $showEmployees['fname'];
                                                $lName          = $showEmployees['lname'];
                                                $empRoleId      = $showEmployees['emp_role'];
                                                $lastUpdate     = $showEmployees['updated_on'];

                                                $empRolData     = json_decode($desigRole->designationRoleID($empRoleId), true);
                                                // print_r($empRolData);

                                                if($empRolData['status']){
                                                    $empRole = $empRolData['data']['desig_name'];

                                                    if($empRole == 'pharmacist'){
                                                        $empRole = 'Pharmacist';
                                                    }elseif($empRole == 'receptionist'){
                                                        $empRole = 'Receptionist';
                                                    }
                                                }else{
                                                    $empRole = '';
                                                }
                                                
                                                
                                                echo '<tr>
                                                        <td>' . $empId . '</td>
                                                        <td>' . $empUsername . '</td>
                                                        <td>' . $fName .' '.$lName.  '</td>
                                                        <td>' . $empRole . '</td>
                                                        <td>' . $empMail . '</td>
                                                        <td>' . formatDateTime($lastUpdate) .'</td>
                                                        <td>
                                                            <a class="text-primary" onclick="viewAndEdit(' . $empId . ')" title="Edit" data-toggle="modal" data-target="#empViewAndEditModal"><i class="fas fa-edit"></i></a>
    
                                                            <a class="delete-btn" data-id="' . $empId . '"  title="Delete"><i class="far fa-trash-alt"></i></a>
                                                        </td>
                                                    </tr>';
                                            }
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
                <!--Entry Section-->
                <div class="col" style="margin: 0 auto; width:98%;">
                    
                    <!-- .........employee add modal start........ -->
                    <div class="modal fade bd-example-modal-lg" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content modal-center">
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
                                                    <label class="mb-0 mt-1" for="fname"> First Name:</label>
                                                    <input class="form-control" type="text" name="fname" id="fname" maxlength="30" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="lname"> Last Name:</label>
                                                    <input class="form-control" type="text" name="lname" id="lname" maxlength="30" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-username">Employee Username:</label>
                                                    <input class="form-control" type="text" name="emp-username" id="emp-username" maxlength="12" required onfocusout="checkEmpUsrNm(this)">
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-mail">Employee Mail:</label>
                                                    <input class="form-control" type="email" name="emp-mail" id="emp-mail" maxlength="100" required onfocusout="checkEmpEmail(this)">
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-mail">Employee Contact:</label>
                                                    <input class="form-control" type="number" name="emp-contact" id="emp-contact" minlength="10" maxlength="10" required>
                                                </div>

                                                <!-- <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-role">Employee Role:</label>
                                                    <select class="form-control" name="emp-role" id="emp-role" required>
                                                        <option value="role1">Choose role..</option>
                                                        <?php foreach ($showDesignation as $desig) { ?>
                                                            <option value="<?php echo $desig['id']; ?>"><?php echo $desig['desig_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div> -->
                                            </div>
                                            <div class="col-md-6">
                                            <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-role">Employee Role:</label>
                                                    <select class="form-control" name="emp-role" id="emp-role" required>
                                                        <option value="role1">Choose role..</option>
                                                        <?php foreach ($showDesignation as $desig) { ?>
                                                            <option value="<?php echo $desig['id']; ?>"><?php echo $desig['desig_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-address">Full Address:</label>
                                                    <textarea class="form-control" name="emp-address" id="emp-address" cols="30" rows="3" maxlength="255"></textarea>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-pass">Password:</label>
                                                    <input class="form-control" type="password" name="emp-pass" id="emp-pass" maxlength="12" required oninput="showToggleBtn('emp-pass','toggleBtn')">
                                                    <i class="fas fa-eye " id="toggleBtn" style="display:none;font-size:1.2rem;margin-right:14px" onclick="togglePassword('emp-pass','toggleBtn')"></i>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="mb-0 mt-1" for="emp-conf-pass">Confirm Password:</label>
                                                    <input class="form-control" type="password" name="emp-cpass" id="emp-conf-pass" maxlength="12" required oninput="showToggleBtn('emp-conf-pass','toggleBtn1')">
                                                    <i class="fas fa-eye " id="toggleBtn1" style="display:none;font-size:1.2rem;margin-right:12px;margin-top:12px;" onclick="togglePassword('emp-conf-pass','toggleBtn1')"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2"> -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button class="btn btn-primary me-md-2" type="submit" name="add-emp">Add Now</button>
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
    <!-- <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a> -->
    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

    <!-- Custom Javascript -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>
    <script>

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

    <!-- custom script path -->
    <script src="<?php echo JS_PATH ?>custom/employees.js"></script>
    <script src="<?= JS_PATH ?>password-show-hide.js"></script>

</body>

</html>