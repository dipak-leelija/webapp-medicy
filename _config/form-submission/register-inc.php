
<?php
include_once dirname(dirname(__DIR__)) . "/config/constant.php";
require_once ROOT_DIR . '_config/registrationSessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';

$Admin = new Admin;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>
</body>

</html>



<?php 


if (isset($_POST['otp-submit'])) {
    $key = $verificationKey;
    // $key = '123456';
    $admId = $adminId;
    $status = 0;

    $chkOtp = $_POST['digit1'] . $_POST['digit2'] . $_POST['digit3'] . $_POST['digit4'] . $_POST['digit5'] . $_POST['digit6'];

    // echo "key value = $key<br>";
    // echo "check otp = $chkOtp<br><br>";

    if ($chkOtp == $key) {
        $status = 1;

        $admStatusUpdate = $Admin->updateAdminStatus($admId, $status);
        // echo 
        // print_r($admStatusUpdate);

        if ($admStatusUpdate['result']) {
            
            handleRegistrationSuccess();

        } else {

            // $delAdmn = $Admin->deleteAdminData($admId);
            // print_r($delAdmn);

            handleRegistrationFailure($admStatusUpdate['message']);
        }

    } else {

        $delAdmn = $Admin->deleteAdminData($admId);
        // print_r($delAdmn);

        handleFailure();
    }
}

function handleRegistrationSuccess() {
    global $Admin, $admId;

    session_destroy();

    echo '
        <script>
        Swal.fire({
            icon: "success",
            title: "Registration Successful",
            showConfirmButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "' . LOCAL_DIR . 'login.php";
            }
          });
          </script>';

    exit;
}

function handleRegistrationFailure($message) {

    session_destroy();

    echo '
        <script>
        Swal.fire({
            icon: "error",
            title: "'.$message.'",
            showConfirmButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "' . LOCAL_DIR . 'register.php";
            }
          });
          </script>';

    exit;
}


function handleFailure(){

    session_destroy();

    echo '
        <script>
        Swal.fire({
            icon: "error",
            title: "INVALID OTP",
            showConfirmButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "' . LOCAL_DIR . 'register.php";
            }
          });
          </script>';

    exit;
}
?>


