<?php 
require_once 'php_control/dbconnect.php';
require_once 'php_control/login.class.php';

require_once 'php_control/designation.class.php';

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
    <link rel="stylesheet" href="https://www.phptutorial.net/app/css/style.css">
    <title>Login</title>
</head>

<body>
    <main>
        <form style="margin-top: 100px;" action="login.php" method="post">
            <h1>Login</h1>
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>
            <!-- <div>
                <label for="password">Roll:</label>
                <select class="form-control" name="emp-role" id="emp-role" required>
                    <option value="role1">admin</option>
                    <option value="role1">user</option>
                </select>
            </div> -->
            <div><button type="submit" style="background-color:#007bff; width:366px; border:none" name="login" type="submit">Login</button></div>
            <section style="justify-content:space-evenly;">

                <a href="register.php">Register</a>
                <a href="register.php">reset password</a>
            </section>
        </form>
    </main>
</body>

</html>