<?php
require_once __DIR__ . '/config/constant.php';

// require_once ROOT_DIR . '_config/sessionCheck.php'
session_unset();
session_destroy();

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'recoverPassword.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';

$Admin          = new Admin;
$Employee       = new Employees;
$RecoverPass    = new recoverPass;
$IdGenerate     = new IdsGeneration;


$errorMessage = '';
$enteredUsername = '';
$selectedRadio = '';

$OTP  = $IdGenerate->otpGgenerator();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['recover-password'])) {

        $username = $_POST["username"];
        // $enteredUsername = $admUsername;

        if (empty($username)) {
            $errorMessage = 'Please fill username or Email id !';
        } else {
            $recoverPassword    = json_decode($RecoverPass->recoverPassword($username));

            // print_r($recoverPassword);


            if ($recoverPassword->status) {

                if ($recoverPassword->message == 'adminData') {

                    $admData = $recoverPassword->data;

                    session_start();

                    $_SESSION['PASS_RECOVERY']       = true;
                    $_SESSION['ADM_PASS_RECOVERY']      = true;
                    $_SESSION['EMP_PASS_RECOVERY']      = false;
                    $_SESSION['ADM_ID']    = $admData[0]->admin_id;
                    $_SESSION['ADM_FNAME'] = $admData[0]->fname;
                    $_SESSION['ADM_USRNM'] = $admData[0]->username;
                    $_SESSION['ADM_EMAIL'] = $admData[0]->email;
                    $_SESSION['ADM_OTP']   = $OTP;

                    header("Location: pass-recover-mail.inc.php");

                } elseif ($recoverPassword->message == 'empData') {
                    $empData = $recoverPassword->data;
                    
                    session_start();
                    $_SESSION['PASS_RECOVERY']       = true;
                    $_SESSION['ADM_PASS_RECOVERY']      = false;
                    $_SESSION['EMP_PASS_RECOVERY']      = true;
                    $_SESSION['EMP_ID']     = $empData[0]->emp_id;
                    $_SESSION['EMP_ADM_ID'] = $empData[0]->admin_id;
                    $_SESSION['EMP_NAME']   = $empData[0]->emp_name;
                    $_SESSION['EMP_USRNM']  = $empData[0]->emp_username;
                    $_SESSION['EMP_EMAIL']  = $empData[0]->emp_email;
                    $_SESSION['EMP_OTP']    = $OTP;

                    header("Location: pass-recover-mail.inc.php");
                }
            } else {
                $errorMessage = 'Enter corret data !';
            }
        }
    }



    /* if (isset($_POST['emp-recover-password'])) {

        $admUsername = $_POST["adm-email"];
        $empUsernmae = $_POST["emp-username"];
        // $enteredUsername = $admUsername;

        if (empty($admUsername) || empty($empUsernmae)) {
            $empErrorMessage = 'Please fill both username or Email id !';
        } else {

            $adminData = json_decode($RecoverPass->adminData($admUsername));

            // print_r($adminData); echo "<br>";
            // echo $adminData->data[0]->admin_id;

            if ($adminData->status) {
                $empData    = json_decode($RecoverPass->employeePassRecover($adminData->data[0]->admin_id, $empUsernmae));

                // print_r($empData->data);

                if ($empData->status) {
                    $empData = $empData->data;

                    session_start();
                    $_SESSION['PASS_RECOVERY']       = true;
                    $_SESSION['EMP_PASS_RECOVERY']      = true;
                    $_SESSION['EMP_NAME'] = $empData[0]->emp_name;
                    $_SESSION['EMP_USRNM'] = $empData[0]->emp_username;
                    $_SESSION['EMP_EMAIL'] = $empData[0]->emp_email;
                    $_SESSION['EMP_OTP']   = $OTP;
                
                    header("Location: register-mail.inc.php");
                    echo 'redirect to mail service page';
                } else {
                    $empErrorMessage = 'Please fill up with correct Username or Email !';
                    $selectedRadio = 'employee';
                    
                }
            } else {
                $empErrorMessage = 'Enter corret admin Data !';
                $selectedRadio = 'employee';
            }
        }
    }*/
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title>Forget Password - <?= $HEALTHCARENAME ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>/sb-admin-2.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>login.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>form.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/password-show-hide.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
</head>

<body class="bg-gradient-primary">

    <div class="d-flex align-items-center justify-content-center flex-column">
        <h4 class=""><img style="width: 160px;margin-top:55%;" src="<?php echo ASSETS_PATH ?>img/lab-tests/logo.png"
                alt=""></h4>

        <div class="col-12 p-4 main">

            <div class="d-flex align-items-center justify-content-center mb-3">
                <h5 class="text-white">Recover Password</h5>
            </div>

            <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-warning text-center" role="alert" id='errorMessage'><?php echo $errorMessage ?>
            </div>
            <?php endif; ?>

            <div class="recoverPassword d-flex justify-content-center">

                <form class="p-4 rounded" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                    <div class="form-group">
                        <input type="text" class=" med-input" id="username" name="username" placeholder=" "
                            autocomplete="off" required>
                        <label class="med-label" for="username">Username / Email</label>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-s w-100" type="submit" name="recover-password">Go</button>
                    </div>

                    <div class="d-flex justify-content-center col-12">
                        <div class="col-md-6 text-center">
                            <a class="small" href="register.php"><b>Sign Up</b></a>
                        </div>
                        <div class=" col-md-6 text-center">
                            <a class="small" href="login.php"><b>Sign In</b></a>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>


    <script>

    document.addEventListener('DOMContentLoaded', function() {
        var errorMessageDiv = document.getElementById('errorMessage');
        // var empErrorMessageDiv = document.getElementById('empErrorMessage');

        var usernmInput = document.getElementById('username');
        // var empUsernmInput = document.getElementById('emp-username');

        if (errorMessageDiv) {
            usernmInput.addEventListener('input', function() {
                errorMessageDiv.innerHTML = '';
                errorMessageDiv.remove();
            });
        }
    });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="<?= JS_PATH ?>password-show-hide.js"></script>

</body>

</html>