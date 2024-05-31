<?php
if (isset($_SESSION['LOGGEDIN'])) {
    header("Location: " . URL);
    exit;
}

require_once __DIR__ . '/config/constant.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'login.class.php';

require_once CLASS_DIR . 'empRole.class.php';

$desRole = new Emproles();
$roleData = $desRole->designationRoleCheckForLogin();
// print_r($roleData);


$loginForm = new LoginForm();

$errorMessage = '';
$enteredUsername = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $enteredUsername = $username;

    if (empty($username) || empty($password)) {
        $errorMessage = 'Please fill in both username and password !';
    } else {
        session_destroy();
        $login    = $loginForm->login($username, $password, $roleData);
        // print_r($login);
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
    <title>Login</title>
</head>

<body>

    <div class="d-flex align-items-center justify-content-center flex-column h-100">
        <form class="p-4 border rounded main" action="login.php" method="post" autocomplete="off">

            <div class="d-flex align-items-center justify-content-center mb-3">
                <img style="width: 160px;" src="<?= IMAGES_PATH ?>logo.png" alt="">
            </div>
            <?php if (!empty($errorMessage)) : ?>
                <div class="alert alert-warning text-center" role="alert" id='errorMessage'><?php echo $errorMessage ?></div>
            <?php endif; ?>
            <div class="form-group ">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required autocomplete="off" value="<?php echo htmlspecialchars($enteredUsername); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required autocomplete="off" oninput="showToggleBtn('password','toggleBtn')">
                <i class="fas fa-eye " id="toggleBtn" style="display:none;font-size:1.2rem" onclick="togglePassword('password','toggleBtn')"></i>
            </div>
            <div class="form-group ">
                <button class="btn btn-primary btn-s w-100" type="submit" name="login">Login</button>
            </div>
            <div class=" d-flex justify-content-around mt-1">
                <a href="register.php">Register</a>
                <!-- <a href="reset-password.php">Forget password</a> -->
                <a href="forgetPassword.php">Forget password</a>
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