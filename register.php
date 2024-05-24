<?php
require_once __DIR__ . '/config/constant.php';
// Check if a specific session variable exists to determine if the user is logged in
if (isset($_SESSION['LOGGEDIN'])) {
    header("Location: " . URL);
    exit;
}

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';

$admin          = new Admin;
$Subscription   = new Subscription;
$HealthCare     = new HealthCare;
$IdGenerate     = new IdsGeneration;

$userExists = false;
$emailExists = false;
$diffrentPassword = false;

// $adminId  = $IdGenerate->generateAdminId();
// echo $adminId;

if (isset($_POST['pid']) || isset($_SESSION['PURCHASEPLANID']) || isset($_POST['register'])) {

    if (isset($_POST['pid'])) {
        $_SESSION['PURCHASEPLANID'] = $_POST['pid'];
    }

    if (isset($_POST['register'])) {
        $Fname      = $_POST['fname'];
        $Lname      = $_POST['lname'];
        $username   = $_POST['user-name'];
        $email      = $_POST['email'];
        $mobNo      = $_POST['mobile-number'];
        $password   = $_POST['password'];
        $cpassword  = $_POST['cpassword'];

        // echo $Fname;

        $adminId  = $IdGenerate->generateAdminId();

        $clinicId = $IdGenerate->generateClinicId($adminId);

        $status = '0';
        $timeout_duration = 600; // 3*60(seconds) = 3 minutes.

        // ======== OTP GENERATOR =========
        $OTP  = $IdGenerate->otpGgenerator();
        //----------------------------------

        $checkUser = $admin->echeckUsername($username);
        // print_r($checkUser->data);

        if ($checkUser) {
            $userExists = true;
        } else {
            $userExists = false;
            $checkMail = $admin->echeckEmail($email);
            if ($checkMail > 0) {
                $emailExists = true;
            } else {
                $emailExists = false;
                if ($password == $cpassword) {
                    $diffrentPassword = false;

                    $register = $admin->registration($adminId, $Fname, $Lname, $username, $password, $email, $mobNo, $expiry, NOW, intval($status));

                    if ($register) {

                        session_unset();
                        session_destroy();
                        session_start();

                        $_SESSION['REGISTRATION']       = true;
                        $_SESSION['ADMIN_REGISER']      = true;
                        $_SESSION['PRIMARY_REGISTER']   = true;
                        $_SESSION['SECONDARY_REGISTER'] = false;
                        $_SESSION['session_start_time']  = date('H:i:s');
                        $_SESSION['time_out']           = $timeout_duration;
                        $_SESSION['verify_key']         = $OTP;
                        $_SESSION['first-name']         = $Fname;
                        $_SESSION['email']              = $email;
                        $_SESSION['username']           = $username;
                        $_SESSION['adm_id']             = $adminId;

                        $addToClinicInfo = $HealthCare->addClinicInfo($clinicId, $adminId, NOW);
                        if ($addToClinicInfo) {
                            header("Location: register-mail.inc.php");
                            exit;
                        } else {
                            $errMsg = "Clinic Info Can't Added!";
                        }
                    }
                } else {
                    $diffrentPassword = true;
                }
            }
        }
    }
} else {
    header("Location: https://medicy.in/pricing");
    exit;
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
    <link href="<?php echo PLUGIN_PATH ?>/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <title>Medicy Health Care - Admin Registration</title>

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>register.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>/custom/password-show-hide.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <main>

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
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
                            <input type="text" class="form-control form-control-user" id="user-name" name="user-name" maxlength="24" placeholder="Username" onfocusout="verifyUsername(this)">
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control form-control-user" id="email" name="email" maxlength="80" placeholder="Email Address" onfocusout="verifyEmail()">
                        </div>

                        <div class="form-group">
                            <input type="tel" class="form-control form-control-user" id="mobile-number" name="mobile-number" pattern="[0-9]{10}" placeholder="12345 67890" onkeydown="validateMobileNumber()" onfocusout="verifyMobileNumber()" maxlength="10" required>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control form-control-user" id="password" name="password" minlength="8" maxlength="12" placeholder="Password" required oninput="showToggleBtn('password','toggleBtn1')">
                                <i class="fas fa-eye " id="toggleBtn1" style="display:none;font-size:1.2rem;right:26px;" onclick="togglePassword('password','toggleBtn1')"></i>
                            </div>
                            <div class="form-group col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control form-control-user" id="cpassword" name="cpassword" minlength="8" maxlength="12" placeholder="Repeat Password" required oninput="showToggleBtn('cpassword','toggleBtn2')">
                                <i class="fas fa-eye " id="toggleBtn2" style="display:none;font-size:1.2rem;right:26px;" onclick="togglePassword('cpassword','toggleBtn2')"></i>
                            </div>
                        </div>
                        <?php

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

                        if (isset($errMsg)) {
                            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                        <strong>Please Contact Support. </strong> $errMsg
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                          <span aria-hidden='true'>&times;</span>
                                        </button>
                                      </div>";
                        }
                        ?>

                        <button class="btn btn-primary btn-user btn-block" type="submit" name="register">Register
                            Account</button>
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
                        <a class="small" href="forgetPassword.php">Reset Password</a>
                    </div>
                    <div class="text-center">
                        <a class="small" href="login.php">Already have an account? Login!</a>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- custom script for register.php -->
    <script src="<?= JS_PATH ?>adminRegistration.js"></script>
    <script src="<?= JS_PATH ?>password-show-hide.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="assets/js/sweetalert2/sweetalert2.all.min.js"></script>

</body>

</html>