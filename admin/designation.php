<?php 
    require_once '../php_control/dbconnect.php';
    require_once '../php_control/designation.class.php';

    $desRole = new Designation();

    $data = $desRole->designationRole();
?>

