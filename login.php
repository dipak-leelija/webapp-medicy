<?php
require_once 'config/constant.php';

if (isset($_SESSION['LOGGEDIN'])) {
    header("Location: " . URL);
    exit;
}

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'login.class.php';

require_once CLASS_DIR . 'empRole.class.php';

$desRole = new Emproles();
$roleData = $desRole->designationRoleCheckForLogin();
// print_r($roleData);


$loginForm = new LoginForm();

$errorMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $errorMessage = 'Please fill in both username and password !';
    } else {
        $login    = $loginForm->login($username, $password, $roleData);
        if ($login === 'Wrong Password') {
            $errorMessage = 'Please fill up with correct Password !';
        } elseif ($login === 'not found') {
            $errorMessage = 'Please fill up with correct Username !';
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
    <title>Login</title>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center">
        <form class="p-4 border border-muted rounded" style="margin-top: 100px; width: 30%;" action="login.php" method="post" autocomplete="off">

            <div class="d-flex align-items-center justify-content-center">
                <h1 class="fw-bold">Login</h1>
            </div>
            <?php if (!empty($errorMessage)) : ?>
                <div class="alert alert-warning text-center" role="alert" id='errorMessage'><?php echo $errorMessage ?></div>
            <?php endif; ?>
            <div class="form-group ">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" require autocomplete="off">
            </div>
            <div class="form-group ">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" require autocomplete="off">
            </div>
            <div class="form-group ">
                <button class="btn btn-primary btn-s w-100" type="submit" name="login">Login</button>
            </div>
            <div class=" d-flex justify-content-around mt-1">
                <a href="register.php">Register</a>
                <a href="reset-password.php">Forget password</a>
            </div>
        </form>
    </div>

</body>

</html>
