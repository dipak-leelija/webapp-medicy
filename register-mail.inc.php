<?php
include_once __DIR__ . "/config/constant.php";
require_once CLASS_DIR .'dbconnect.php';
require_once __DIR__.'/PHPMailer/PHPMailer.php';

$PHPMailer		= new PHPMailer();

$_SESSION['vkey']		= 87677;
$_SESSION['fisrt-name'] = 'Dipak';
$_SESSION['email']		= 'dipakmajumdar.leelija@gmail.com';


if(isset($_SESSION['vkey']) && isset($_SESSION['fisrt-name']) && isset($_SESSION['email'])){	

	$verifyKey  	= strip_tags(trim($_SESSION['vkey'])); 
	$firstName 		= strip_tags(trim($_SESSION['fisrt-name']));
	$txtEmail 		= strip_tags(trim($_SESSION['email']));

	$sess_arr	= array('vkey', 'newCustomerSess', 'fisrt-name', 'last-name', 'profession');
	$utility->delSessArr($sess_arr);			
	$uMesg->dispMesgWithMesgVal(SUCONTACT001,"SUCCESS","images/icon/",'','error-block','success-block');

	$msgBody = "Dear $firstName,
			<br>
			Welcome to Medicy! We're thrilled to have you on board.
			<br>
			To ensure the security of your account, please use the following One-Time Password (OTP) for verification:
			<br>
			<br>
			<b>Your Verification OTP: $verifyKey<b>
			<br>
			<br>
			Please enter this code to complete the registration process. If you didn't sign up for an account on Medicy, please ignore this email.
			<br>
			Thank you for choosing Medicy. We look forward to providing you with the best Healthcare System experience";

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
		$PHPMailer->Subject     = "Account Verification OTP - ". SITE_NAME;
		$PHPMailer->Body        = $msgBody;
		
		if (!$PHPMailer->send()) {
			echo "Message could not be sent to customer. Mailer Error:-> {$PHPMailer->ErrorInfo}<br>";
		}else {
			echo 'mail sent';	
		}
			$PHPMailer->clearAllRecipients();

	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error:-> {$PHPMailer->ErrorInfo}";
	}

}
	

// header("location: verification-sent.php");
// exit;

?>