<?php

$warning = false;
$captchaCode = rand(10000, 999999);

setcookie('loginCaptcha', $captchaCode, time() + (300 * 30), "/");

if(isset($_POST['send-otp'])){
	if ($_COOKIE['loginCaptcha']==$_POST['captchaCode']){
		$mobNum = $_POST['mobileNumber'];
		
		session_start();
		$_SESSION['mobNum'] = $mobNum;
		header('Location: registration/');
	}else{
		$warning = true;
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login V3</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.min.css">
    <!--===============================================================================================-->
    <style>
    .captchaShow {
        /* background-color: #000; */
        align-items: center;
        text-align: center;
    }

    .captchaShow p {
        background-color: #fff;
        border: 1px solid blue;
        border-radius: 3px;
        display: inline-block;
        width: 30%;
    }
    </style>

</head>

<body>

    <div class="limiter">
        <div class="container-login100"">
			<div class=" wrap-login100" style="background: #0071d1e8;">
            <!-- <form action=""> -->
            <form id="otpForm" class="login100-form validate-form" action="login.php" method="post">
                <span class="login100-form-logo">
                    <!-- <i class="zmdi zmdi-landscape"></i> -->
                    <i class="zmdi zmdi-account-box"></i>
                </span>

                <span class="login100-form-title p-b-34 p-t-27">
                    Book Your Appointment 
                </span>

                <div id="otpNum" class="wrap-input100 validate-input" data-validate="Enter Mobile Number">
                    <input class="input100" type="text" name="mobileNumber" placeholder="Enter Mobile Number"
                        maxlength="10" minlength="10" required>
                    <span class="focus-input100" data-placeholder="&#xf207;"></span>
                </div>


                <!-- ############################################ -->

                <div class="captchaShow">
                    <p><strong><?php echo $captchaCode; ?></strong></p>
                </div>

                <!-- ############################################ -->


                <div id="captchaEnter" class="wrap-input100 validate-input" data-validate="Enter The Code">
                    <input class="input100" type="text" name="captchaCode" placeholder="Enter The Code">
                    <span class="focus-input100" data-placeholder="&#xf15a;"></span>
                </div>

                <!-- ############################################ -->

                <?php
						if($warning){
							echo  '<div class="alert alert-warning alert-dismissible fade show" role="alert">
							<strong>Invalid!</strong> Captcha Code.
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>';
						}
				?>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" name="send-otp">Continue</button>
                </div>
            </form>
        </div>
    </div>
    </div>



    <script src="js/bootstrap-js-5/bootstrap.js"></script>
    <script src="js/bootstrap-js-5/bootstrap.min.js"></script>
    <script src="registration/js/jquery-3.3.1.min.js"></script>

	<script>
		 $(document).ready(function() {
          $('strong').bind('cut copy', function(e) {
              e.preventDefault();
            });
        });
	</script>

	<!-- bootstrap 3.3.7 js for dismissable alert -->
	<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
    <!--===============================================================================================-->

</body>

</html>