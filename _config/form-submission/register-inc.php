<?php
include_once dirname(dirname(__DIR__)) . "/config/constant.php";
require_once ROOT_DIR . '_config/registrationSessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';

$Admin = new Admin;

// if ($_SESSION['vkey'] && $_SESSION['adm_id'] && $_SESSION['last_activity'] && $_SESSION['time_out']) {

   
    $Key = $randomNumber;
    $admId = $adminId;
    $status = 0;

    if (isset($_POST['otp-submit'])) {
      
        $chkOtp = $_POST['digit1'].$_POST['digit2'].$_POST['digit3'].$_POST['digit4'].$_POST['digit5'].$_POST['digit6'];

        if ($chkOtp == $Key) {
            $status = 1;

            $admStatusUpdate = $Admin->updateAdminStatus($admId, $status);

            if ($admStatusUpdate['result']) {
                
                header("Location: " . LOCAL_DIR . "login.php");
                session_destroy();
                exit; 

            } else {
                $delAdmn = $Admin->deleteAdminData($admId);
                echo "Status update failed.";
                session_destroy();
            }

        } else {
            $delAdmn = $Admin->deleteAdminData($admId);
            echo "Invalid OTP.";
            session_destroy();
            header("Location: " . LOCAL_DIR . "login.php");
        }
    }
// }
?>
