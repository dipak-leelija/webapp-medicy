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

    print_r($permissionDetails);

    // for($i=0; $i<count($permissionDetails); $i++){
    foreach($permissionDetails as $permissionDetails){
        echo $permissionDetails->allow_page."<br>";
    }

    $currentURL = $_SERVER['REQUEST_URI'];

    echo "Current URL: $currentURL";
    echo "<br>";
    echo LOCAL_DIR;



}






?>