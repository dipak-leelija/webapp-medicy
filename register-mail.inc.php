<?php
include_once __DIR__ . "/config/constant.php";
require_once CLASS_DIR . 'dbconnect.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'admin.class.php';


$PHPMailer		= new PHPMailer();
$Utility        = new Utility;
$Admin 			= new Admin;


// $_SESSION['vkey']		= 87677;
// $_SESSION['fisrt-name'] = 'Dipak';
// $_SESSION['email']		= 'dipakmajumdar.leelija@gmail.com';

/// =========== REGISTRATION HANDELING SECTION  ================

if (isset($_SESSION['vkey']) && isset($_SESSION['first-name']) && isset($_SESSION['email']) && isset($_SESSION['last_activity']) && isset($_SESSION['time_out'])) {

	$sessionStartTime = $_SESSION['last_activity'];
	$verificationKey = $_SESSION['vkey'];
	$fname = $_SESSION['first-name'];
	$email = $_SESSION['email'];
	$timeOut = $_SESSION['time_out'];


	// echo $sessionStartTime, "<br>";
	echo $verificationKey, "<br>";
	// echo $fname, "<br>";
	// echo $email, "<br>";
	// echo $timeOut, "<br>";



	// $elapsed_time = time() - $_SESSION['last_activity'];
	// if ($elapsed_time > $timeout_duration) {
	// 	session_unset();
	// 	session_destroy();
	// 	header("Location: registration.php"); 
	// }

	
/*
	$verifyKey  	= strip_tags(trim($_SESSION['vkey']));
	$firstName 		= strip_tags(trim($_SESSION['first-name']));
	$txtEmail 		= strip_tags(trim($_SESSION['email']));



	$sess_arr	= array('vkey', 'newCustomerSess', 'fisrt-name', 'last-name', 'profession');
	$Utility->delSessArr($sess_arr);

	
	$msgBody = "Dear $firstName,
			<br>
			Welcome to Medicy! We're thrilled to have you on board.
			<br>
			To ensure the security of your account, please use the following One-Time Password (OTP) for verification:
			<br>
			<br>
			<b>Your Verification OTP: $verifyKey</b>
			<br>
			<br>
			Please enter this code to complete the registration process. If you didn't sign up for an account on Medicy, please ignore this email.
			<br>
			Thank you for choosing Medicy. We look forward to providing you with the best Healthcare System experience."; */

	/*===================================================================================================
	|									    send mail to new customer									|
	====================================================================================================*/
	/*
	try {
		$PHPMailer->IsSendmail();
		$PHPMailer->IsHTML(true);
		$PHPMailer->Host        = gethostname();
		$PHPMailer->SMTPAuth    = true;
		$PHPMailer->Username    = SITE_EMAIL;
		$PHPMailer->Password    = SITE_EMAIL_P;
		$PHPMailer->From        = SITE_EMAIL;
		$PHPMailer->FromName    = SITE_NAME;
		$PHPMailer->Sender      = SITE_EMAIL;
		$PHPMailer->addAddress($txtEmail, $firstName);
		$PHPMailer->Subject     = "Account Verification OTP - " . SITE_NAME;
		$PHPMailer->Body        = $msgBody;

		if (!$PHPMailer->send()) {
			echo "Message could not be sent to customer. Mailer Error:-> {$PHPMailer->ErrorInfo}<br>";
		} else {
			echo 'mail sent';
		}
		$PHPMailer->clearAllRecipients();
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error:-> {$PHPMailer->ErrorInfo}";
	} */
} else {
	session_destroy();
}


// header("location: verification-sent.php");
// exit;

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
		.otp-input {
			display: flex;
			justify-content: space-between;
			width: 200px;
			/* Adjust as needed */
			margin: auto;
			/* Center the input block */
		}

		.otp-input input {
			width: 30px;
			/* Adjust as needed */
			text-align: center;
		}
	</style>


</head>


<body class="bg-gradient-primary">

	<main>


		<?php
		if (isset($_SESSION['vkey']) && isset($_SESSION['adm_id']) && isset($_SESSION['last_activity']) && isset($_SESSION['time_out'])) {  echo $_SESSION['vkey']; ?>



			<div class="card o-hidden border-0 shadow-lg my-5">
				<div class="card-body p-0">
					<!-- Nested Row within Card Body -->
					<div class="p-5">
						<div class="text-center">
							<h1 class="h4 text-gray-900 mb-4">Verify OTP</h1>
						</div>

						<form class="user" action="_config/form-submission/register-inc.php" method="post">

							<div class="">
								<div class="otp-input">
									<input type="text" maxlength="1" name="digit1" id="digit1" pattern="\d" title="Please enter only digits" required>
									<input type="text" maxlength="1" name="digit2" id="digit2" pattern="\d" title="Please enter only digits" required>
									<input type="text" maxlength="1" name="digit3" id="digit3" pattern="\d" title="Please enter only digits" required>
									<input type="text" maxlength="1" name="digit4" id="digit4" pattern="\d" title="Please enter only digits" required>
									<input type="text" maxlength="1" name="digit5" id="digit5" pattern="\d" title="Please enter only digits" required>
									<input type="text" maxlength="1" name="digit6" id="digit6" pattern="\d" title="Please enter only digits" required>
								</div>
								<div class="">
									<button class="btn btn-primary btn-user btn-block mt-3" type="submit" name="otp-submit">Register
										Account</button>
								</div>
								<!-- <div class="d-none"><input type="text" name="adm-val" id="adm-val" value="<?php $_SESSION['adm_id']; ?>"><?php $_SESSION['adm_id']; ?></div> -->
							</div>




							<!-- <hr> -->
							<!-- <a href="index.html" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a> -->
						</form>
						<!-- <hr> -->
					</div>
				</div>
			</div>

		<?php } ?>
	</main>

	<!-- Bootstrap core JavaScript-->
	<script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
	<script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

	<!-- custom script for register.php -->
	<script src="<?= JS_PATH ?>adminRegistration.js"></script>


	<!-- Custom scripts for all pages-->
	<script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
	<script src="assets/js/sweetalert2/sweetalert2.all.min.js"></script>

</body>

</html>