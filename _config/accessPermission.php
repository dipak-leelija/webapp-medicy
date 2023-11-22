<?php
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'accessPermission.class.php';
require_once CLASS_DIR . 'utility.class.php';

$AccessPermission  = new AccessPermission;
$Employees         = new Employees;
$Utility           = new Utility;

$currentURL        = $Utility->currentUrl();
$currentFile = basename($currentURL);


$allowedPages = $AccessPermission->showPermission($userRole, $adminId);
  $allowedPages = json_decode($allowedPages);

  if ($allowedPages->status == 1) {
    $allowedPages = $allowedPages->data;

    if(!in_array($currentFile, $allowedPages)) {
      echo "You are not allowed";
    }
  }

?>