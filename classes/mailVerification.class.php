<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once ROOT_DIR."vendor/autoload.php";

require_once ROOT_DIR."PHPMailer/Exception.php";
require_once ROOT_DIR."PHPMailer/PHPMailer.php";
require_once ROOT_DIR."PHPMailer/SMTP.php";




class MailVerification extends DatabaseConnection
{


    function addVerifyToken($email, $token, $status)
    {
        try {

            $insert = "INSERT INTO emil_verification (`email`, `varification_token`, `status`)   VALUES (?, ?, ?)";

            $stmt = $this->conn->prepare($insert);

            if ($stmt) {
                $stmt->bind_param("ssi", $email, $token, $status);

                $insertQuery = $stmt->execute();
                $stmt->close();

                return ['status' => '1'];
            } else {
                return ['status' => '0'];
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }




    function sendMail($email,$code)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug    = SMTP::DEBUG_SERVER;
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'leelijawebsolutions@gmail.com';                     //SMTP username
            $mail->Password   = 'Password@123';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('roodro.leelija@gmail.com', 'MEDICY.IN');
            $mail->addAddress($email);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Email Verification from MEDICY MASTER';
            $mail->Body    = 'Thanks for registration!
            Click the link below to verify the emil address 
            <a href="'.LOCAL_DIR.'verify.php?email='.$email.'&vCode='.$code.'">Verify</a>';

            $mail->send();
            return true;

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return $e->getMessage();
            return false;
        }
    }

} 