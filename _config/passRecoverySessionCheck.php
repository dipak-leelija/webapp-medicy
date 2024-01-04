<?php
require_once  dirname(__DIR__) . '/config/constant.php';
// Check if a specific session variable exists to determine if the user is logged in
if (!isset($_SESSION['PASS_RECOVERY'])) {
    header("Location: " . ROOT_DIR . "forgetPassword.php");
    exit;
}


if ($_SESSION['PASS_RECOVERY']) {
    if ($_SESSION['ADM_PASS_RECOVERY']) {
        $adminId    =   $_SESSION['ADM_ID'];
        $admFname   =   $_SESSION['ADM_FNAME'];
        $admUsrNm   =   $_SESSION['ADM_USRNM'];
        $admEmail   =   $_SESSION['ADM_EMAIL'];
        $admOtp     =   $_SESSION['ADM_OTP'];
    }


    // if ($_SESSION['EMP_PASS_RECOVERY']) {
    //     $empFname   =   $_SESSION['ADM_FNAME'];
    //     $empUsrNm   =   $_SESSION['ADM_USRNM'];
    //     $empEmail   =   $_SESSION['ADM_EMAIL'];
    //     $empOtp     =   $_SESSION['ADM_OTP'];
    // }
}

