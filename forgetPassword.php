<?php
require_once __DIR__ . '/config/constant.php';
// require_once ROOT_DIR . '_config/sessionCheck.php';

// if (isset($_SESSION['LOGGEDIN'])) {
//     // header("Location: " . URL);
//     // exit;
//     session_unset();
//     session_destroy();
// }

session_unset();
session_destroy();

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'recoverPassword.class.php';

$Admin = new Admin;
$Employee = new Employees;


$errorMessage = '';
$enteredUsername = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];

    $enteredUsername = $username;

    if (empty($username)) {
        $errorMessage = 'Please fill username or Email id !';
    } else {
        $recoverPassword    = $loginForm->login($username, $password, $roleData);

        if ($login === 'Wrong Password') {
            $errorMessage = 'Please fill up with correct Password !';
        } elseif ($login === 'not found') {
            $errorMessage = 'Please fill up with correct Username !';
            $enteredUsername = '';
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

        <form class="p-4 border rounded main" action="forgetPassword.php" method="post" autocomplete="off">

            <div class="d-flex align-items-center justify-content-center mb-3">
                <h5 class="">Recover Password</h5>
            </div>

            <div class="d-flex justify-content-around">
                <label>
                    <input type="radio" name="checkUser" value="admin" onclick="chkUsr(this)">Admin
                </label>
                <label>
                    <input type="radio" name="checkUser" value="employee" onclick="chkUsr(this)">Employee
                </label>
            </div>

            <div class="recoverAdmin" style="display: none;">
                <?php if (!empty($errorMessage)) : ?>
                    <div class="alert alert-warning text-center" role="alert" id='errorMessage'><?php echo $errorMessage ?></div>
                <?php endif; ?>
                <div class="form-group ">
                    <label for="username">Username / Email:</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter Your Username/ Email" required autocomplete="off" value="<?php echo htmlspecialchars($enteredUsername); ?>">
                </div>

                <div class="form-group ">
                    <button class="btn btn-primary btn-s w-100" type="submit" name="adm-recover-password">Go</button>
                </div>

            </div>

            <div class="recoverEmployee" style="display: none;">
                <?php if (!empty($errorMessage)) : ?>
                    <div class="alert alert-warning text-center" role="alert" id='errorMessage'><?php echo $errorMessage ?></div>
                <?php endif; ?>

                <div class="form-group ">
                    <label for="username">Admin Email:</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Admin Email" required autocomplete="off" value="<?php echo htmlspecialchars($enteredUsername); ?>">
                </div>

                <div class="form-group ">
                    <label for="username">Employee Username / Email:</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter Your Username/ Email" required autocomplete="off" value="<?php echo htmlspecialchars($enteredUsername); ?>">
                </div>

                <div class="form-group ">
                    <button class="btn btn-primary btn-s w-100" type="submit" name="Emp-recover-password">Go</button>
                </div>
            </div>



        </form>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var errorMessageDiv = document.getElementById('errorMessage');

            var usernameInput = document.getElementById('username');
            var passwordInput = document.getElementById('password');
            if (errorMessageDiv) {
                usernameInput.addEventListener('input', function() {
                    errorMessageDiv.innerHTML = '';
                    errorMessageDiv.remove();
                });

                passwordInput.addEventListener('input', function() {
                    errorMessageDiv.innerHTML = '';
                    errorMessageDiv.remove();
                });
            }

        });


        const chkUsr = (t) =>{
            
            if(t.value == 'admin'){
                document.querySelector('.recoverAdmin').style.display = 'block';
                document.querySelector('.recoverEmployee').style.display = 'none';
            }

            if(t.value == 'employee'){
                document.querySelector('.recoverAdmin').style.display = 'none';
                document.querySelector('.recoverEmployee').style.display = 'block';
            }
        }
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