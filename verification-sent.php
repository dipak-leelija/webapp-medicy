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
                        <h1 class="h4 text-gray-900 mb-4">Verify OTP</h1>
                    </div>

                    <div id="timer-div">
                        <small><span class="d-flex justify-content-center" id="timer"></span></small>
                    </div>


                    <!-- <button id="resendOTP" style="display: none;"><small><span class="d-flex justify-content-center" onclick="getNewotp()"><u>Resend OTP</u></span></small></button> -->

                    <div class="d-flex justify-content-center">
                        <button class="btn btn-small" type="button" name="resendOTP" id="resendOTP" onclick="getNewotp()" style="display: none; color: blue; text-decoration: underline;">Resend OTP</button>
                    </div>



                    <div class="otp-container mt-2">
                            <div class="d-flex justify-content-center">
                            <input class="otp-input" type="text" maxlength="1" name="digit1" id="digit1" oninput="moveToNext(this, 'digit2')" required>
                            <input class="otp-input" type="text" maxlength="1" name="digit2" id="digit2" oninput="moveToNext(this, 'digit3')" required>
                            <input class="otp-input" type="text" maxlength="1" name="digit3" id="digit3" oninput="moveToNext(this, 'digit4')" required>
                            <input class="otp-input" type="text" maxlength="1" name="digit4" id="digit4" oninput="moveToNext(this, 'digit5')" required>
                            <input class="otp-input" type="text" maxlength="1" name="digit5" id="digit5" oninput="moveToNext(this, 'digit6')" required>
                            <input class="otp-input" type="text" maxlength="1" name="digit6" id="digit6" oninput="moveToNext(this, 'digit6')" required>
                        </div>
                    </div>

                    <div class="text-center m-0">
                        <h6 class="h6 text-green-900 mb-2 mt-2 alert alert-info p-2 m-0">OTP sent to your registerd mail address <b><?php echo $email; ?></b></h6>
                    </div>

                    <div class="m-0">
                        <button class="btn btn-primary btn-user btn-block mt-0" type="submit" name="otp-submit" onclick="submitOtp()">Register
                            Account</button>
                    </div>

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

        // ============ otp submit button action ===============
        const submitOtp = () => {

            let digit1 = document.getElementById('digit1').value;
            let digit2 = document.getElementById('digit2').value;
            let digit3 = document.getElementById('digit3').value;
            let digit4 = document.getElementById('digit4').value;
            let digit5 = document.getElementById('digit5').value;
            let digit6 = document.getElementById('digit6').value;

            var submittedOtp = (digit1 + digit2 + digit3 + digit4 + digit5 + digit6);

            console.log(submittedOtp);


            $.ajax({
                url: "ajax/registrationOnOtpSubmission.ajax.php",
                type: "POST",
                data: {
                    otpsubmit: submittedOtp,
                },
                success: function(data) {
                    console.log("ajax return data : " + data);
                    if (data == 1) {
                        handleRegistrationSuccess();
                    } else if (data == 2) {
                        handleFailure();
                    } else {
                        handleRegistrationFailure(data);
                    }
                }
            });
        }






        function handleRegistrationSuccess() {

            Swal.fire({
                icon: "success",
                title: "Registration Successful",
                showConfirmButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "login.php";
                }
            });
        }




        function handleRegistrationFailure($message) {

            Swal.fire({
                icon: "error",
                title: "'.$message.'",
                showConfirmButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "login.php";
                }
            });

        }


        function handleFailure() {

            Swal.fire({
                icon: "error",
                title: "INVALID OTP",
                showConfirmButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });

        }





        function messageResentAlert() {

            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "NEW OTP SEND",
                showConfirmButton: false,
                timer: 1500
            });
        }


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

        }

        timer(120);



        // ============ new otp generation =============
        const getNewotp = () => {
            
            document.getElementById("resendOTP").style.display = 'none';
            document.getElementById("timer-div").style.display = 'block';

            timer(150);


            $.ajax({
                url: "ajax/resendOtpOnRegistration.ajax.php",
                type: "POST",
                data: {
                    resendOtp: 'generate-new-otp',
                },
                success: function(data) {
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
    </script>
</body>

</html>