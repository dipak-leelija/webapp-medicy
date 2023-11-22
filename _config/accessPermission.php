<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php'; 
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'accessPermission.class.php';

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



    if ($flag == 0) {
        echo '<link rel="stylesheet" href="' . CSS_PATH . 'sweetalert2/sweetalert2.min.css">';
        echo '<script src="' . JS_PATH . 'sweetalert2/sweetalert2.all.min.js"></script>';
        
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        
        $redirectFlag = 1;
    
        

    }



}

?>