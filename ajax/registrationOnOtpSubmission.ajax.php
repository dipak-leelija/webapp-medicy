<?php
include_once dirname(__DIR__) . "/config/constant.php";
require_once ROOT_DIR . '_config/registrationSessionCheck.php';
require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';

$Admin = new Admin;


if (isset($_POST['otpsubmit'])) {

    $key = $verificationKey;
    // $key = '123456';
    $admId = $adminId;
    $status = 0;

    $chkOtp = $_POST['otpsubmit'];

    // echo "key value = $key<br>";
    // echo "check otp = $chkOtp<br><br>";

    if ($chkOtp == $key) {
        $status = 1;

        $admStatusUpdate = $Admin->updateAdminStatus($admId, $status);
        

        if ($admStatusUpdate['result']) {
            session_destroy();

            // handleRegistrationSuccess();

            echo 1;

        } else {

            // $delAdmn = $Admin->deleteAdminData($admId);
            // print_r($delAdmn);
            session_destroy();

            // handleRegistrationFailure($message)

            echo $admStatusUpdate['message'];
        }

    } else {

        // $delAdmn = $Admin->deleteAdminData($admId);
        // print_r($delAdmn);
        // session_destroy();
        
        echo 2;

        // handleFailure();
    }
}


// function handleRegistrationSuccess() {
//     global $Admin, $admId;

//     session_destroy();

//     echo '
//         <script>
//         Swal.fire({
//             icon: "success",
//             title: "Registration Successful",
//             showConfirmButton: true,
//             confirmButtonColor: "#3085d6",
//             confirmButtonText: "OK"
//           }).then((result) => {
//             if (result.isConfirmed) {
//                 window.location.href = "' . LOCAL_DIR . 'login.php";
//             }
//           });
//           </script>';

//     exit;
// }

// function handleRegistrationFailure($message) {

//     session_destroy();

//     echo '
//         <script>
//         Swal.fire({
//             icon: "error",
//             title: "'.$message.'",
//             showConfirmButton: true,
//             confirmButtonColor: "#3085d6",
//             confirmButtonText: "OK"
//           }).then((result) => {
//             if (result.isConfirmed) {
//                 window.location.href = "' . LOCAL_DIR . 'login.php";
//             }
//           });
//           </script>';

//     exit;
// }


// function handleFailure(){

//     session_destroy();

//     echo '
//         <script>
//         Swal.fire({
//             icon: "error",
//             title: "INVALID OTP",
//             showConfirmButton: true,
//             confirmButtonColor: "#3085d6",
//             confirmButtonText: "OK"
//           }).then((result) => {
//             if (result.isConfirmed) {
//                 window.location.href = "' . LOCAL_DIR . 'register.php";
//             }
//           });
//           </script>';

//     exit;
// }
?>


