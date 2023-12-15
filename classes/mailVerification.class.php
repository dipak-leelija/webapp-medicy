<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require_once "../PHPMailer/PHPMailer.php";
require_once "../PHPMailer/SMTP.php";
require_once "../PHPMailer/Exception.php";

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




    function sendMail($email, $code)
    {
       
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug    = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'leelijawebsolutions@gmail.com';
            $mail->Password   = 'Password@123';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('leelijawebsolutions@gmail.com', 'MEDICY');
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Email Verification from MEDICY MASTER';
            $mail->Body    = 'Thanks for registration!
            Click the link below to verify the emil address 
            <a href="' . LOCAL_DIR . 'verify.php?email=' . $email . '&vCode=' . $code . '">Verify</a>';

            $mail->send();
            echo 'Message has been sent.';
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return $e->getMessage();
            return false;
        }
    }
}
