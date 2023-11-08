<?php 
    require_once dirname(__DIR__).'/config/constant.php';
    require_once ROOT_DIR.'/config/sessionCheck.php';
    require_once '../php_control/designation.class.php';

    $desRole = new Designation();

    $data = $desRole->designationRole();
?>

