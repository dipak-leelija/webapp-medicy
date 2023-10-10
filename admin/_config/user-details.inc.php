<?php
if($_SESSION['ADMIN'] == true){
    require_once CLASS_DIR.'admin.class.php';
    $Admin      = new Admin;

}else {
    require_once CLASS_DIR.'employee.class.php';
    $Employees  = new Employees;
    $user = $Employees->selectEmpByCol('id', $userId);
    print_r($user);
}

?>