<?php
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'accessPermission.class.php';
require_once CLASS_DIR . 'utility.class.php';

$AccessPermission  = new AccessPermission;
$Employees         = new Employees;

$employeesData = $Employees->empDisplayByAdminAndEmpId($employeeId, $adminId);
if($employeesData != null){
    $employeesData = json_decode($employeesData);
    $empRole = $employeesData->emp_role;

    $permissionDetails = $AccessPermission->showPermission($empRole, $adminId);
    $permissionDetails = json_decode($permissionDetails);

    $permissonPages = [];
    // array_push($permissonPages, LOCAL_DIR);
    foreach($permissionDetails as $permissionDetails){
        array_push($permissonPages, $permissionDetails->allow_page);
    }
    // print_r($permissonPages);
}

if($userRole != 'ADMIN'){

    $flag = 0; 

    $currentURL = $_SERVER['REQUEST_URI'];

    for($i = 0; $i<count($permissonPages); $i++){
        if($currentURL == LOCAL_DIR.$permissonPages[$i] || $currentURL == LOCAL_DIR){
            $flag = 1;
            break;
        }
    }

    if($flag == 0){
        header("Location: {$_SERVER['HTTP_REFERER']}");
        echo "<script>alert('Your message goes here.');</script>";
    }
    
   
    
   



}






?>