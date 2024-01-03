<?php
require_once  dirname(__DIR__).'/config/constant.php';
// Check if a specific session variable exists to determine if the user is logged in
if (!isset($_SESSION['REGISTRATION'])) {
    header("Location: ".ROOT_DIR."verification-sent.php");
    exit;
}


if($_SESSION['ADMIN_REGISER']){
    // echo "Debugging: " . print_r($_SESSION, true);
    $sessionStartTime       = $_SESSION['session_start_time'];
    $verificationKey        = $_SESSION['verify_key'];
    $Fname                  = $_SESSION['first-name'];
    $email                  = $_SESSION['email'];
    $userName               = $_SESSION['username'];
    $adminId                = $_SESSION['adm_id'];
    $sessionTimeOutDuration = $_SESSION['time_out'];
}



if($_SESSION['PASS_RECOVERY']){
    if($_SESSION['ADM_PASS_RECOVERY']){
        $admFname   =   $_SESSION['ADM_FNAME'];
        $admUsrNm   =   $_SESSION['ADM_USRNM'];
        $admEmail   =   $_SESSION['ADM_EMAIL']; 
        $admOtp     =   $_SESSION['ADM_OTP'] ;
    }


    if($_SESSION['EMP_PASS_RECOVERY']){
        $empFname   =   $_SESSION['ADM_FNAME'];
        $empUsrNm   =   $_SESSION['ADM_USRNM'];
        $empEmail   =   $_SESSION['ADM_EMAIL']; 
        $empOtp     =   $_SESSION['ADM_OTP'] ;
    }
}

                