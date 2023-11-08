<?php 
date_default_timezone_set("Asia/Kolkata");

// Check if a specific session variable exists to determine if the user is logged in
if (!isset($_SESSION['LOGGEDIN'])) {
    header("Location: ".URL."login.php");
    exit;
}

if($_SESSION['ADMIN']){
  // echo 'true';
  $userEmail    = $_SESSION['USER_EMAIL'];
  $userRole     = $_SESSION['USER_ROLE'];
  $userFname    = $_SESSION['USER_FNAME'];
  $username     = $_SESSION['USERNAME'];
  $adminId      = $_SESSION['ADMINID'];
  $employeeId = ' ';
  $addedBy = $adminId;
}else{
  // echo 'false';
  $userEmail      = $_SESSION['USER_EMAIL'] ;
  $userRole       = $_SESSION['USER_ROLE'];
  $userFname      = $_SESSION['USER_FNAME'];
  $username       = $_SESSION['USERNAME'];
  $employeeId     = $_SESSION['EMPID'];
  $adminId        = $_SESSION['ADMIN_ID'];
  $addedBy = $employeeId;
}
