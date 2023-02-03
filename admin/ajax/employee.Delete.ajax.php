<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../php_control/employee.class.php';


$deleteEmpId = $_POST['id'];
// $deleteDocId = 6464;

$emp = new Employees();
$empDelete = $emp->deleteEmp($deleteEmpId);
//echo $empDelete.$this->conn->error;exit;

if ($empDelete) {
    echo 1;
}else {
    echo 0;
}
?>
