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


$Admin = new Admin;
$Employees = new Employees;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['submit'])){

        $imageName         = $_FILES['profile-image']['name'];
        $tempImgName       = $_FILES['profile-image']['tmp_name'];


        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $userName = $_POST['user-name'];
        $email = $_POST['email'];
        $phNo = $_POST['mobile-number'];

        $pss = $_POST['password'];
        $hashedPassword = password_hash($pss, PASSWORD_DEFAULT);

        $cnfPass = $_POST['cpassword'];
        $address = $_POST['address'];


        if($_SESSION['ADMIN']){
            
            $updateAdminData = $Admin->updateAdminDetails($fname, $lname, $imageName, $userName, $hashedPassword, $email, $phNo,  $address, NOW, $adminId);

            if($updateAdminData['result']){

                $imgFolder = ADM_IMG_DIR.$imageName;
                move_uploaded_file($tempImgName, $imgFolder);
            }

        } else {
                
            $updateEmployeeData = $Employees->updateEmpData($fname.$lname, $imageName, $email, $phNo, $address, $userName, $hashedPassword, NOW, $employeeId, $adminId);

            if($updateEmployeeData['result']){

                $imgFolder = EMP_IMG_DIR.$imageName;
                move_uploaded_file($tempImgName, $imgFolder);

            }
        } 
    }
}

?>