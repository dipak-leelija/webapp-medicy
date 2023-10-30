<?php
require_once __DIR__. '/config/constant.php';
require_once ADM_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR .'dbconnect.php';
require_once CLASS_DIR.'admin.class.php';

$admin = new Admin();


$userExists = false;
$emailExists = false;
$diffrentPassword = false;


if (isset($_POST['register'])) {
    $Fname = $_POST['fname'];
    $Lname = $_POST['lname'];
    $username = $_POST['user-name'];
    $email = $_POST['email'];
    $mobNo = $_POST['mobile-number'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $password =  $_POST['password'];
    $cpassword = $_POST['cpassword'];

    $adminStr1 = 'ADM'.mt_rand(0, 9999999);
    $adminSubStr1 = substr($email, 0, 4);
    $adminSubStr2 = substr($mobNo, 0, 2);
    $adminSubStr3 = substr($mobNo, -2);
    $adminId = $adminStr1.$adminSubStr1.$adminSubStr2.$adminSubStr3;

    $checkUser = $admin->echeckUsername($username);
    if($checkUser > 0){
        $userExists = true;
    }else{
        $userExists = false;
        $checkMail = $admin->echeckEmail($email);
        if($checkMail > 0){
            $emailExists = true;
        }else {
            $emailExists = false;
            if($password == $cpassword){
                $diffrentPassword = false;
                $password = password_hash($password, PASSWORD_DEFAULT);
                $register = $admin->registration($adminId, $Fname, $Lname, $username, $password, $email, $mobNo, $address, $city);
                if ($register) {
                    header("Location: login.php");
                    exit;
                }
            }else {
                $diffrentPassword = true;
            }
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

    <title>Medicy Health Care - Admin Registration</title>

    <!-- Custom fonts for this template-->
    <link href="admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="admin/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block "><img style="width:100%;" src="admin/img/welcome-admin.jpg"
                            alt=""></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
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
                                    <input type="text" class="form-control form-control-user" id="user-name" name="user-name" maxlength="12" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="email" name="email" maxlength="80" placeholder="Email Address">
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-user" id="mobile-number" name="mobile-number" maxlength="10" placeholder="Mobile Number">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="address" name="address" maxlength="255" placeholder="Your Address">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="city" name="city" maxlength="80" placeholder="Your City">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="password" name="password" maxlength="12" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="cpassword" name="cpassword" maxlength="12" placeholder="Repeat Password">
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

                                <button class="btn btn-primary btn-user btn-block" type="submit" name="register">Register Account</button>
                                <!-- <hr> -->
                                <!-- <a href="index.html" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a> -->
                            </form>
                            <!-- <hr> -->
                            <div class="text-center" style="margin-top:15px;">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="admin/vendor/jquery/jquery.min.js"></script>
    <script src="js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="admin/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="admin/js/sb-admin-2.min.js"></script>

</body>

</html>