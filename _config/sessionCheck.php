<?php
// Check if a specific session variable exists to determine if the user is logged in
if (!isset($_SESSION['LOGGEDIN'])) {
    header("Location: ".URL."login.php");
    exit;
}

if($_SESSION['ADMIN']){
  // echo 'true';
  $userEmail    = $_SESSION['ADMIN_EMAIL'];
  $adminContact = $_SESSION['ADMIN_CONTACT_NO'];
  $userRole     = $_SESSION['USER_ROLE'];
  $userFname    = $_SESSION['ADMIN_FNAME'];
  $adminLname   = $_SESSION['ADMIN_LNAME'];
  $username     = $_SESSION['ADMIN_USERNAME'];
  $adminPass    = $_SESSION['ADMIN_PASSWORD'];
  $adminId      = $_SESSION['ADMINID'];
  $employeeId   = '';
  $addedBy      = $adminId;
}else{
  // echo 'false';
  $userEmail      = $_SESSION['EMP_EMAIL'] ;
  $empContact     = $_SESSION['EMP_CONTACT_NO'];
  $userRole       = $_SESSION['EMP_ROLE'];
  $userFname      = $_SESSION['EMP_NAME'];
  $username       = $_SESSION['EMP_USERNAME'];
  $empPass        = $_SESSION['EMP_PASSWORD'];
  $employeeId     = $_SESSION['EMPID'];
  $adminId        = $_SESSION['ADMIN_ID'];
  $addedBy        = $employeeId;
}
