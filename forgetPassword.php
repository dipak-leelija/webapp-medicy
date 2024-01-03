<?php
require_once __DIR__ . '/config/constant.php';

// require_once ROOT_DIR . '_config/sessionCheck.php';

// if (isset($_SESSION['LOGGEDIN'])) {
//     header("Location: " . URL);
//     exit;
// }

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

    if (isset($_POST['adm-recover-password'])) {

        $admUsername = $_POST["adm-username"];
        // $enteredUsername = $admUsername;

        if (empty($admUsername)) {
            $admErrorMessage = 'Please fill username or Email id !';
        } else {
            $admRecoverPassword    = json_decode($RecoverPass->adminPassRecover($admUsername));

            // print_r($admRecoverPassword);

            if ($admRecoverPassword->status) {
                $admData = $admRecoverPassword->data;
                // print_r($admData);

                session_start();
                $_SESSION['PASS_RECOVERY']       = true;
                $_SESSION['ADM_PASS_RECOVERY']      = true;
                $_SESSION['ADM_FNAME'] = $admData->fname;
                $_SESSION['ADM_USRNM'] = $admData->username;
                $_SESSION['ADM_EMAIL'] = $admData->email;
                $_SESSION['ADM_OTP']   = $OTP;
                
                header("Location: adm-pass-recover-mail.inc.php");
            } else {
                $admErrorMessage = 'Please fill up with correct Username or Email !';
                $selectedRadio = 'admin';
                // echo $admErrorMessage;
            }
        }
    }


    if (isset($_POST['emp-recover-password'])) {

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
                
                // header("Location: register-mail.inc.php");
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
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo PLUGIN_PATH ?>/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>login.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>/custom/password-show-hide.css" rel="stylesheet">
    <title>Forget Password</title>
</head>

<body>

    <div class="d-flex align-items-center justify-content-center flex-column">
        <h4 class=""><img style="width: 160px;margin-top:55%;" src="<?php echo ASSETS_PATH ?>img/lab-tests/logo.png" alt=""></h4>

        <div class="col-12 p-4 border rounded main">

            <div class="d-flex align-items-center justify-content-center mb-3">
                <h5 class="">Recover Password</h5>
            </div>

            <div class="d-flex justify-content-around">
                <label>
                    <input type="radio" name="checkUser" id="adm-radio" value="admin" onclick="chkUsr(this.value)" <?php if ($selectedRadio == 'admin') echo 'checked'; ?>> Admin
                </label>
                <label>
                    <input type="radio" name="checkUser" id="emp-radio" value="employee" onclick="chkUsr(this.value)" <?php if ($selectedRadio == 'employee') echo 'checked'; ?>> Employee
                </label>
            </div>


            <?php if (!empty($admErrorMessage)) : ?>
                <div class="alert alert-warning text-center" role="alert" id='admErrorMessage'><?php echo $admErrorMessage ?></div>
                <script>
                    let admVal = 'admin';
                    // chkUsr(admVal);
                </script>
            <?php endif; ?>

            <?php if (!empty($empErrorMessage)) : ?>
                <div class="alert alert-warning text-center" role="alert" id='empErrorMessage'><?php echo $empErrorMessage ?></div>
                <script>
                    let empVal = 'employee';
                    // chkUsr(empVal);
                </script>
            <?php endif; ?>


            <div class="recoverAdmin" style="display: none;">

                <form class="" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">

                    <div class="form-group">
                        <label for="adm-username">Username / Email:</label>
                        <input type="text" class="form-control" name="adm-username" id="adm-username" placeholder="Enter Your Username/ Email" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary btn-s w-100" type="submit" name="adm-recover-password">Go</button>
                    </div>
                </form>
            </div>




            <div class="recoverEmployee" style="display: none;">

                <form class="" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">

                    <div class="form-group">
                        <label for="adm-email">Admin Email:</label>
                        <input type="text" class="form-control" name="adm-email" id="adm-email" placeholder="Admin Email" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="emp-username">Employee Username / Email:</label>
                        <input type="text" class="form-control" name="emp-username" id="emp-username" placeholder="Enter Your Username/ Email" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary btn-s w-100" type="submit" name="emp-recover-password">Go</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        const chkUsr = (val) => {
            console.log("chk usr function val : " + val);
            var recoverAdminDiv = document.querySelector('.recoverAdmin');
            var recoverEmployeeDiv = document.querySelector('.recoverEmployee');

            if (val == 'admin') {
                recoverAdminDiv.style.display = 'block';
                recoverEmployeeDiv.style.display = 'none';
            }

            if (val == 'employee') {
                recoverAdminDiv.style.display = 'none';
                recoverEmployeeDiv.style.display = 'block';
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            var admErrorMessageDiv = document.getElementById('admErrorMessage');
            var empErrorMessageDiv = document.getElementById('empErrorMessage');

            var admUsernmInput = document.getElementById('adm-username');
            var empUsernmInput = document.getElementById('emp-username');

            if (admErrorMessageDiv) {
                admUsernmInput.addEventListener('input', function() {
                    admErrorMessageDiv.innerHTML = '';
                    admErrorMessageDiv.remove();
                });

                empUsernmInput.addEventListener('input', function() {
                    empErrorMessageDiv.innerHTML = '';
                    empErrorMessageDiv.remove();
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