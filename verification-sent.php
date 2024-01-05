<?php
include_once __DIR__ . "/config/constant.php";
require_once ROOT_DIR . '_config/registrationSessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'admin.class.php';



$PHPMailer      = new PHPMailer();
$Utility        = new Utility;
$Admin          = new Admin;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Medicy Health Care - Admin OTP VERIFICATION ON Registration</title>

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>register.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">


    <style>
        .input-group {
            width: 2rem;
            text-align: center;
            margin: 3px;
        }
    </style>


</head>


<body class="bg-gradient-primary">

    <main>

        <!-- <?php
                echo $adminId . "<br>";
                echo $randomNumber;
                ?> -->



        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Verify OTP</h1>
                    </div>

                    <!-- <form class="user" action="_config/form-submission/register-inc.php" method="post"> -->

                        <div class="otp-input">
                            <div class="d-flex justify-content-center">
                                <input class="input-group" type="text" maxlength="1" name="digit1" id="digit1" oninput="moveNext(this)" required>
                                <input class="input-group" type="text" maxlength="1" name="digit2" id="digit2" oninput="moveNext(this)" required>
                                <input class="input-group" type="text" maxlength="1" name="digit3" id="digit3" oninput="moveNext(this)" required>
                                <input class="input-group" type="text" maxlength="1" name="digit4" id="digit4" oninput="moveNext(this)" required>
                                <input class="input-group" type="text" maxlength="1" name="digit5" id="digit5" oninput="moveNext(this)" required>
                                <input class="input-group" type="text" maxlength="1" name="digit6" id="digit6" oninput="moveNext(this)" required>
                            </div>

                        </div>

                        <div class="text-center m-0">
                            <h6 class="h6 text-green-900 mb-2 mt-2 alert alert-info p-2 m-0">OTP sent to your registerd mail address <b><?php echo $email; ?></b></h6>
                        </div> 

                        <div class="m-0">
                            <button class="btn btn-primary btn-user btn-block mt-0" type="submit" name="otp-submit" onclick="submitOtp()">Register
                                Account</button>
                        </div>
                        
                    <!-- </form> -->
                  
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- custom script for register.php
    <script src="<?= JS_PATH ?>adminRegistration.js"></script> -->


    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="assets/js/sweetalert2/sweetalert2.all.min.js"></script>

    <!-- custom script for register.php -->
    <script src="<?= JS_PATH ?>adminRegistration.js"></script>



    <script>

    </script>
</body>

</html>