<?php
require_once '../../php_control/employee.class.php';

$empId = $_GET['empId'];
$empUsername = $_GET['empUsername'];
$empName = $_GET['empName'];
$empRole = $_GET['empRole'];
$empEmail = $_GET['empEmail'];


$employees = new Employees();
$EditEmp = $employees->updateEmp($empUsername, $empName, $empRole, $empEmail,/*Last Variable for id which one you want to update */ $empId);

if($EditEmp){
    echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>
            <strong>Success</strong>Your Employee Data Has been Updateed!
        </div>";
}else {
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>Failed!</strong> Employee Data Not Updated!
        </div>";
}


?>