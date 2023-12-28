<?php
require_once  dirname(__DIR__).'/config/constant.php';
// Check if a specific session variable exists to determine if the user is logged in
if (!isset($_SESSION['REGISTRATION'])) {
    header("Location: ".ROOT_DIR."verification-sent.php");
    exit;
}


if($_SESSION['ADMIN_REGISER']){
    echo "Debugging: " . print_r($_SESSION, true);
    $randomNumber = $_SESSION['vkey'];
    $Fname = $_SESSION['first-name'];
    $email = $_SESSION['email'];
    $adminId = $_SESSION['adm_id'];
}
