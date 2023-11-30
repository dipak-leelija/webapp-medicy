<?php

require_once dirname(dirname(__DIR__)) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once ROOT_DIR . '_config/accessPermission.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'empRole.class.php';


if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['submit'])){



        echo "hello";
        if($_SESSION['ADMIN']){
            echo 'admin';
        } else {
            echo 'employee';
        }

    }
}

?>