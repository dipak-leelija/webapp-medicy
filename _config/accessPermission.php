<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php'; 
require_once CLASS_DIR . 'accessPermission.class.php';
require_once CLASS_DIR . 'employee.class.php';

$AccessPermission  = new AccessPermission;
$Employees         = new Employees;

$employeesData = $Employees->empDisplayByAdminAndEmpId($employeeId, $adminId);
if($employeesData != null){
    $employeesData = json_decode($employeesData);
    $empRole = $employeesData->emp_role;

    $permissionDetails = $AccessPermission->showPermission($empRole, $adminId);
    $permissionDetails = json_decode($permissionDetails);
}

if($userRole != 'ADMIN'){

    // print_r($permissionDetails);

    $currentURL = $_SERVER['REQUEST_URI'];

    foreach($permissionDetails as $permissionDetails){

        // echo "<br><br>Current URL : $currentURL";

        // echo "<br>permission page : ".LOCAL_DIR.$permissionDetails->allow_page."<br>";

        if($currentURL == LOCAL_DIR.$permissionDetails->allow_page){
            // echo "<br>". LOCAL_DIR.$permissionDetails->allow_page;
            echo "permission granted";
            break;
        }else{
            echo "<script>alert('Your message goes here.');</script>";
            header("Location: " .LOCAL_DIR.'index.php');
        }
    }

    
   
    
   



}






?>