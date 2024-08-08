<?php
require_once __DIR__ . '/config/constant.php';
// Check if a specific session variable exists to determine if the user is logged in
if (isset($_SESSION['LOGGEDIN'])) {
    header("Location: " . URL);
    exit;
}

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
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
} 
else {
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
    
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Registration - <?= $HEALTHCARENAME ?></title>
    
    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>register.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>form.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>/custom/password-show-hide.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>/fontawesome-free/css/all.min.css" type="text/css" />

</head>

<body class="bg-gradient-primary">
    <main>
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                    </div>

                    <form class="user" action="register.php" method="post">
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <!-- <input type="text" class="form-control form-control-user" id="fname" name="fname" maxlength="20" placeholder="First Name"> -->
                                <input type="text" class=" med-input" id="fname" name="fname" maxlength="20" required>
                                <label class="med-label" style="left:22px" for="fname">First Name <span
                                        class="form-asterisk"></span></label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="med-input" id="lname" name="lname" maxlength="20"
                                    maxlength="20" required>
                                <label class="med-label" style="left:22px" for="lname">Last Name <span
                                        class="form-asterisk"></span></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="med-input" id="user-name" name="user-name" maxlength="24"
                                onfocusout="verifyUsername(this)" required>
                            <label class="med-label" for="user-name">Username <span
                                    class="form-asterisk"></span></label>
                        </div>

                        <div class="form-group">
                            <input type="email" class="med-input" id="email" name="email" maxlength="80"
                                onfocusout="verifyEmail()" required>
                            <label class="med-label" for="email">Email Address <span
                                    class="form-asterisk"></span></label>
                        </div>

                        <div class="form-group">
                            <input type="tel" class="med-input" id="mobile-number"
                                name="mobile-number" pattern="[0-9]{10}" placeholder=""
                                onkeydown="validateMobileNumber()" onfocusout="verifyMobileNumber()" maxlength="10"
                                required>
                                <label class="med-label" for="mobile-number">Mobile Number <span class="form-asterisk"></span>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="med-input" id="password"
                                    name="password" maxlength="12" placeholder="" required
                                    oninput="showToggleBtn('password','toggleBtn1')">
                                    <label class="med-label" for="password" style="left:22px">Password <span class="form-asterisk"></span></label>
                                <i class="fas fa-eye " id="toggleBtn1" style="display:none;"
                                    onclick="togglePassword('password','toggleBtn1')"></i>
                            </div>
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="med-input" id="cpassword"
                                    name="cpassword" maxlength="12" placeholder="" required
                                    oninput="showToggleBtn('cpassword','toggleBtn2')">
                                    <label class="med-label" for="cpassword" style="left:22px">Repeat Password <span class="form-asterisk"></span></label>
                                <i class="fas fa-eye " id="toggleBtn2" style="display:none;"
                                    onclick="togglePassword('cpassword','toggleBtn2')"></i>
                            </div>
                        </div>
                        <span id="password-error" class="error-msg">Password must be at least 8 characters long.</span>
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

                        <button class="btn btn-primary btn-block" type="submit" name="register">Register
                            Account</button>
                    </form>
                    
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