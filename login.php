<?php
require_once 'config/constant.php';

if (isset($_SESSION['LOGGEDIN'])) {
    header("Location: " . URL);
    exit;
}

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'login.class.php';

require_once CLASS_DIR . 'designation.class.php';

$desRole = new Designation();
$roleData = $desRole->designationRole();
// print_r($roleData);

$loginForm = new LoginForm();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $login    = $loginForm->login($username, $password, $roleData);
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
        <form class="p-5 border border-muted rounded " style="margin-top: 100px;" action="login.php" method="post">
            
            <div class="d-flex align-items-center justify-content-center"><h1>Login</h1></div>
            <div class="form-group ">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username">
            </div>
            <div class="form-group ">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <div class="form-group ">
                <button class="btn btn-primary btn-s" type="submit" style=" width:316px; border:none;" name="login">Login</button>
            </div>
            <div class=" d-flex justify-content-around mt-1">
                <a href="register.php">Register</a>
                <a href="reset-password.php">reset password</a>
            </div>
        </form>
    </div>
</body>

</html>

<!-- <div>
                <label for="password">Roll:</label>
                <select class="form-control" name="emp-role" id="emp-role" required>
                    <option value="role1">admin</option>
                    <option value="role1">user</option>
                </select>
</div> -->