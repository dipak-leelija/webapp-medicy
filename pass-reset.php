<?php
include_once __DIR__ . "/config/constant.php";
require_once ROOT_DIR . '_config/passRecoverySessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once CLASS_DIR . 'utility.class.php';



$PHPMailer      = new PHPMailer();
$Utility        = new Utility;


// print_r($_SESSION);

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
    <link href="<?= CSS_PATH ?>sb-admin-2.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>register.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">


    <style>
        .input-group {
            width: 2rem;
            text-align: center;
            margin: 3px;
        }

        #single-input {
            border: none;
            border-bottom: 1px solid black;
            width: 15rem;
            font-family: monospace;
            letter-spacing: 10px;
            text-align: center;
        }

        .otp-container {
            display: flex;
            justify-content: center;
        }

        .otp-input {
            width: 40px;
            height: 40px;
            font-size: 20px;
            text-align: center;
            margin-right: 10px;
            border: 1px solid #ccc;
        }
    </style>


</head>


<body class="bg-gradient-primary">

    <main>

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900">Verify OTP</h1>
                    </div>

                    <!-- <div id="timer-div">
                        <small><span class="d-flex justify-content-center" id="timer"></span></small>
                    </div> -->

                    <form class="mt-4 user" action="_config/form-submission/password-reset-form.php" method="post">

                        <div class="col-12 mb-3 mt-1 mb-sm-0">
                            <input type="password" class="form-control form-control-user" id="password" name="password" minlength="8" maxlength="12" placeholder="Enter New Password" required>
                        </div>

                        <div class="col-12 mb-3 mt-3 mb-sm-0">
                            <input type="password" class="form-control form-control-user" id="confirm-password" name="confirm-password" minlength="8" maxlength="12" placeholder="Retype New Password" required onfocusout="chechMatch(this)">
                        </div>

                        <div class="otp-container mt-4">
                            <div class="d-flex justify-content-center">
                                <input class="otp-input" type="text" maxlength="1" name="digit1" id="digit1" oninput="moveToNext(this, 'digit2')" required>
                                <input class="otp-input" type="text" maxlength="1" name="digit2" id="digit2" oninput="moveToNext(this, 'digit3')" required>
                                <input class="otp-input" type="text" maxlength="1" name="digit3" id="digit3" oninput="moveToNext(this, 'digit4')" required>
                                <input class="otp-input" type="text" maxlength="1" name="digit4" id="digit4" oninput="moveToNext(this, 'digit5')" required>
                                <input class="otp-input" type="text" maxlength="1" name="digit5" id="digit5" oninput="moveToNext(this, 'digit6')" required>
                                <input class="otp-input" type="text" maxlength="1" name="digit6" id="digit6" oninput="moveToNext(this, 'digit6')" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center col-12 mb-3 mt-1 mb-sm-0 mt-3">
                                <!-- <button class=" btn btn-small" type="button" name="resendOTP" id="resendOTP" onclick="getNewOtp()" style="display: none; color: blue; text-decoration: underline;">Resend OTP</button> -->

                            <label style="color: red; display: block;" id="info-lebel-1"><small>Enter Verification OTP send to your registereed e-mail</small></label>
                        </div>


                        <div class="">
                            <button class="btn btn-primary btn-user btn-block mt-3" type="submit" name="pass-reset">Reset Password
                            </button>
                        </div>

                    </form>
                    <!-- <hr> -->
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
    <script src="assets/js/sweetalert2/sweetalert2.all.min.js"></script>


    <!-- custom script for register.php -->
    <script src="<?= JS_PATH ?>adminRegistration.js"></script>




    <script>
        // =============== timer script =================

        let timerOn = true;

        function timer(remaining) {
            var m = Math.floor(remaining / 60);
            var s = remaining % 60;

            m = m < 10 ? '0' + m : m;
            s = s < 10 ? '0' + s : s;
            document.getElementById('timer').innerHTML = m + ':' + s;
            remaining -= 1;

            if (remaining >= 0 && timerOn) {
                setTimeout(function() {
                    timer(remaining);
                }, 1000);
                return;
            }

            if (!timerOn) {
                // Do validate stuff here
                return;
            }

            // Do timeout stuff here
            document.getElementById("resendOTP").style.display = 'block';
            document.getElementById("timer-div").style.display = 'none';
            document.getElementById("info-lebel-1").style.display = 'none';

        }

        // timer(5);



        // ============ new otp generation =============
        function getNewOtp() {

            document.getElementById("resendOTP").style.display = 'none';
            document.getElementById("timer-div").style.display = 'block';
            document.getElementById("info-lebel-1").style.display = 'block';

            timer(5);


            $.ajax({
                url: "ajax/resendOtpOnPassRecover.ajax.php",
                type: "POST",
                data: {
                    resendRestOtp: 'generate-new-otp',
                },
                success: function(data) {
                    console.log("chakachak");
                    console.log("ajax return data : " + data);
                    if (data == 1) {
                        messageResentAlert();
                        console.log(data);
                    } else {
                        // messageResentAlert();
                        // alert(data);
                        console.log(data);
                    }
                }
            });

        }

        const chechMatch = (t) => {
            let pass = document.getElementById("password").value;
            let retypePass = t.value;

            if (pass != retypePass) {
                Swal.fire({
                    icon: "error",
                    title: "Password missmatch",
                    showConfirmButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById("password").value = "";
                        document.getElementById("confirm-password").value = "";
                    }
                });
            }
        }
    </script>
</body>

</html>