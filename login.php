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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>bootstrap/bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>login.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>form.css" rel="stylesheet">

    <link href="<?php echo CSS_PATH ?>/custom/password-show-hide.css" rel="stylesheet">
    <title>Login</title>
</head>

<body>

    <div class="login-wrapper d-flex align-items-center justify-content-center flex-column">
        <form class="p-4 border rounded" action="login.php" method="post" autocomplete="off">

            <div class="d-flex align-items-center justify-content-center py-4">
                <img class="pb-2" style="width: 160px;" src="<?= IMAGES_PATH ?>logo.png" alt="">
            </div>
            <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-warning text-center" role="alert" id='errorMessage'><?= $errorMessage ?>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <input type="text" class=" med-input" name="username" id="username" value="<?= htmlspecialchars($enteredUsername); ?>" placeholder="" required autocomplete="off">
                <label class="med-label" for="username">Username</label>
            </div>

            <div class="form-group">
                <input type="password" class=" med-input" name="password" id="password"  autocomplete="off" oninput="showToggleBtn('password','toggleBtn')" placeholder="" required>
                <label class="med-label" for="password">Password</label>
                <i class="fas fa-eye " id="toggleBtn" style="display:none;" onclick="togglePassword('password','toggleBtn')"></i>
            </div>

            <div class="form-group ">
                <button class="btn btn-primary btn-s w-100" type="submit" name="login">Login</button>
            </div>
            <div class="d-flex justify-content-around mt-1">
                <a href="register.php">Register</a>
                <a href="forgetPassword.php">Forget password</a>
            </div>
        </form>
    </div>


    <!-- <script>
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
    </script> -->

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>password-show-hide.js"></script>

</body>

</html>