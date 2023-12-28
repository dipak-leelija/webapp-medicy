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
	// echo $verificationKey, "<br>";
	// echo $fname, "<br>";
	// echo $email, "<br>";
	// echo $timeOut, "<br>";



	// $elapsed_time = time() - $_SESSION['last_activity'];
	// if ($elapsed_time > $timeout_duration) {
	// 	session_unset();
	// 	session_destroy();
	// 	header("Location: registration.php"); 
	// }

	

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
			Thank you for choosing Medicy. We look forward to providing you with the best Healthcare System experience."; 

	/*===================================================================================================
	|									    send mail to new customer									|
	====================================================================================================*/
	
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
			header("location: verification-sent.php");
		}
		
		$PHPMailer->clearAllRecipients();
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error:-> {$PHPMailer->ErrorInfo}";
	} 
} else {
	session_destroy();
}


// header("location: verification-sent.php");
// exit;

?>
