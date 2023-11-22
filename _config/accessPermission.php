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
    print_r($permissionDetails);
}
?>