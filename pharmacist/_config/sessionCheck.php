<?php
date_default_timezone_set("Asia/Kolkata");
session_start();
$_SESSION['pharmacy_section'] = $_SERVER['REQUEST_URI'];
// echo $_SESSION['pharmacy_section'];
if($_SESSION['pharmacist'] == FALSE){
    header("Location: ../employee/config/login.php");
    exit();
  }
?>
