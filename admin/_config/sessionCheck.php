<?php

if (!isset($_SESSION['SUPERADMINLOGGEDIN'])) {
  header("Location: " . ADM_URL . "login-superAdmin.php");
  exit;
}


if ($_SESSION['SUPER_ADMIN']) {
  $supAdminEmail    = $_SESSION['SUPER_ADMIN_EMAIL'];
  $supAdminContact  = $_SESSION['SUPER_ADMIN_CONTACT_NO'];
  $userType         = $_SESSION['USER_TYPE'];
  $userRole         = $_SESSION['USER_ROLE'];
  $supAdminFname    = $_SESSION['SUPER_ADMIN_FNAME'];
  $supAdminLname    = $_SESSION['SUPER_ADMIN_LNAME'];
  $supAdminImg      = $_SESSION['SUPER_ADMIN_IMG'];
  $supAdminAddress  = $_SESSION['SUPER_ADMIN_ADDRESS'];
  $supAdminusername = $_SESSION['SUPER_ADMIN_USERNAME'];
  $supAdminPass     = $_SESSION['SUPER_ADMIN_PASSWORD'];
  $supAdminId       = $_SESSION['SUPER_ADMINID'];
  $employeeId       = '';
  $addedBy          = $supAdminId;
}

// Check if a specific session variable exists to determine if the user is logged in
// if (!isset($_SESSION['LOGGEDIN'])) {
//     header("Location: ".URL."login.php");
//     exit;
// }

// if($_SESSION['ADMIN']){
//   // echo 'true';
//   $userEmail    = $_SESSION['ADMIN_EMAIL'];
//   $adminContact = $_SESSION['ADMIN_CONTACT_NO'];
//   $userType     = $_SESSION['USER_TYPE'];
//   $userRole     = $_SESSION['USER_ROLE'];
//   $userFname    = $_SESSION['ADMIN_FNAME'];
//   $adminLname   = $_SESSION['ADMIN_LNAME'];
//   $adminImg     = $_SESSION['ADMIN_IMG'] ;
//   $adminAddress = $_SESSION['ADMIN_ADDRESS'];
//   $username     = $_SESSION['ADMIN_USERNAME'];
//   $adminPass    = $_SESSION['ADMIN_PASSWORD'];
//   $adminId      = $_SESSION['ADMINID'];
//   $employeeId   = '';
//   $addedBy      = $adminId;
// }else{
//   // echo 'false';
//   $userEmail      = $_SESSION['EMP_EMAIL'] ;
//   $empContact     = $_SESSION['EMP_CONTACT_NO'];
//   $userType       = $_SESSION['USER_TYPE'];
//   $userRole       = $_SESSION['EMP_ROLE'];
//   $userFname      = $_SESSION['EMP_NAME'];
//   $empImg         = $_SESSION['EMP_IMG']; 
//   $empAddress     = $_SESSION['EMP_ADDRESS'];
//   $username       = $_SESSION['EMP_USERNAME'];
//   $empPass        = $_SESSION['EMP_PASSWORD'];
//   $employeeId     = $_SESSION['EMPID'];
//   $adminId        = $_SESSION['ADMIN_ID'];
//   $addedBy        = $employeeId;
// }
