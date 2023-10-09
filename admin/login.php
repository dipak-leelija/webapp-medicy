<?php
require_once '../php_control/admin.class.php';
$admin = new Admin();

$wrongUser = false;
$wrongPassword = false;

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $checkSymble = '@';

    if (strpos($email, $checkSymble) !== false){
        $flag = 0;
    }else{
        $flag = 1;
    }
    
    if ($flag == 0) {
        $login = $admin->login($email);
        if ($login == FALSE) {
            $wrongUser = TRUE;
        } else {
            foreach ($login as $loginDetails) {
                if (password_verify($password, $loginDetails['password'])) {
                    // echo 'Your Email is: '.$loginDetails['email'];
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['admin'] = true;
                    $_SESSION['userEmail'] = $email;
                    $_SESSION['adminId'] = $loginDetails['id'];
                    $_SESSION['adminFname'] = $loginDetails['fname'];
                    //echo "loggedin ".$row['user_name'];

                    $redirectLink = $_SESSION['last_page'];
                    if ($redirectLink == '') {
                        header("Location: index.php");
                    } else {
                        // echo $redirectLink;
                        // exit;
                        header("Location: " . $redirectLink . "");
                    }
                    exit;
                } else {
                    $wrongPassword = TRUE;
                    // echo 'Wrong Password';
                }
            }
        }
    }

    if ($flag == 1) {
        echo "Employee login";
    }
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

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"><img style="width:100%;" src="img/welcome-admin.jpg" alt=""></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" action="login.php" method="post">
                                        <div class="form-group">
                                            <input class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" name="email" placeholder="Enter Email Address / Username" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember Me</label>
                                            </div>
                                        </div>
                                        <!-- <a href="index.html" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </a> -->
                                        <div class="form-group">
                                            <?php
                                            if ($wrongUser) {
                                                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <strong>Sorry! </strong> You Have Entered Incorrect Username.
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>';
                                            }

                                            if ($wrongPassword) {
                                                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <strong>Sorry! </strong> You Have Entered Incorrect Password.
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>';
                                            }
                                            ?>
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block" name="login" type="submit">Login</button>
                                    </form>
                                    <!-- <hr> -->
                                    <div class="text-center" style="margin-top:15px;">
                                        <a class="small" href="forgot-password.php">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>