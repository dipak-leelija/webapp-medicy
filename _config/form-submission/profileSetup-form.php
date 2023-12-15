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


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <script src="<?php echo JS_PATH ?>sweetAlert.min.js"></script>
</head>

<body>
    <div>
    </div>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {

            $imageName         = $_FILES['profile-image']['name'];
            $tempImgName       = $_FILES['profile-image']['tmp_name'];


            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            // $userName = $_POST['user-name'];
            $email = $_POST['email'];
            $phNo = $_POST['mobile-number'];

            // $cnfPass = $_POST['cpassword'];
            $address = $_POST['address'];

            // echo "<br>$fname";
            // echo "<br>$lname";
            // echo "<br>$imageName";
            // echo "<br>$email";
            // echo "<br>$phNo";
            // echo "<br>$address";
            // echo "<br>$employeeId";
            // echo "<br>$adminId";

            $flag = 0;

            if (!empty($_FILES['profile-image']['name'])) {
                if ($_SESSION['ADMIN']) {

                    $updateAdminData = $Admin->updateAdminDetails($fname, $lname, $imageName, $email, $phNo,  $address, NOW, $adminId);

                    if ($updateAdminData['result']) {

                        $imgFolder = ADM_IMG_DIR . $imageName;
                        move_uploaded_file($tempImgName, $imgFolder);
                        $_SESSION['ADMIN_IMG'] = $imageName;
                        $flag = 1;
                    }
                } else {

                    $updateEmployeeData = $Employees->updateEmpData($fname . ' ' . $lname, $imageName, $email, $phNo, $address, NOW, $employeeId, $adminId);


                    if ($updateEmployeeData['result']) {

                        $imgFolder = EMP_IMG_DIR . $imageName;
                        move_uploaded_file($tempImgName, $imgFolder);
                        $_SESSION['EMP_IMG'] = $imageName;
                        $flag = 1;
                    }
                }
            } else {
                if ($_SESSION['ADMIN']) {
                    $imageName = $_SESSION['ADMIN_IMG'];
                    $updateAdminData = $Admin->updateAdminDetails($fname, $lname, $imageName, $email, $phNo,  $address, NOW, $adminId);
                    if ($updateAdminData['result']) {
                        $flag = 1;
                    }
                } else {
                    $imageName = $_SESSION['EMP_IMG'];
                    $updateEmployeeData = $Employees->updateEmpData($fname . ' ' . $lname, $imageName, $email, $phNo, $address, NOW, $employeeId, $adminId);
                    if ($updateEmployeeData['result']) {
                        $flag = 1;
                    }
                }
            }

            if ($flag == 1) {
    ?>
                <script>
                    swal("Success", "Data Updated!", "success")
                        .then((value) => {
                            window.location = '<?php echo URL ?>profile.php';
                        });
                </script>
            <?php
            } else {
            ?>
                <script>
                    swal("Error", "Updation Fails!", "error")
                        .then((value) => {
                            window.location = '<?php echo URL ?>profile.php';
                        });
                </script>
    <?php
            }
        }
    }

    ?>

</body>

</html>