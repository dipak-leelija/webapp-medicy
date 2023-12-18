<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

$Admin = new Admin;
$Employees = new Employees;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $oldPassword = $_POST['old-password'];
    $newPassword = $_POST['new-password'];
    $cnfPassword = $_POST['cnf-password'];


    if ($_SESSION['ADMIN']) {
        $oldAdminPass = $adminPass;
        $x_password = pass_dec($oldAdminPass, ADMIN_PASS);

        if ($oldPassword === $x_password) {
            if ($newPassword === $cnfPassword) {
                $adminPassUpdate = $Admin->updateAdminPassword($newPassword, $adminId);

                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Failed!</strong> password changed successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
            } else {
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Failed!</strong> Inputed password dosenot matched!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        } else {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Failed!</strong> Wrong Old password inputed!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }
    } else {

        $oldEmpPass = $empPass;
        $x_password = pass_dec($oldEmpPass, EMP_PASS);

        if ($oldPassword === $x_password) {

            if ($newPassword === $cnfPassword) {

                $empPassUpdate = $Employees->updateEmployeePassword($newPassword, $employeeId, $adminId);
                // print_r($empPassUpdate);

                if($empPassUpdate['result']){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Failed!</strong> password changed successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                } else {
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Failed!</strong> Update fail! Internal server error.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                }
                
            } else {
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Failed!</strong> Inputed password dosenot matched!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
            }
        } else {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Failed!</strong> Password Updation Failed!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link href="<?php echo CSS_PATH ?>login.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>/custom/password-show-hide.css" rel="stylesheet">
</head>

<body>

    <div class="mt-2 reportUpdate" id="reportUpdate">
        <!-- Ajax Update Reporet Goes Here -->
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group mb-3">
            <input type="password" class="form-control " id="old-password" name="old-password" maxlength="12" placeholder="Current Password" required oninput="showToggleBtn1()">
            <button type="button" class=" btn btn-success btn-sm m-0 p-0 rounded btnfocus" id="toggleBtn1" style="display:none;" onclick="togglePassword1()"><i class="fas fa-eye"></i></button>
        </div>
        <div class="form-group  mb-3">
            <input type="password" class="form-control " id="new-password" name="new-password" maxlength="12" placeholder="Enter New Password" required oninput="showToggleBtn2()">
            <button type="button" class=" btn btn-success btn-sm m-0 p-0 rounded btnfocus" id="toggleBtn2" style="display:none;" onclick="togglePassword2()"><i class="fas fa-eye"></i></button>
        </div>
        <div class="form-group mb-3 ">
            <input type="password" class="form-control " id="cnf-password" name="cnf-password" maxlength="12" placeholder="Confirm Password" required oninput="showToggleBtn3()">
            <button type="button" class=" btn btn-success btn-sm m-0 p-0 rounded btnfocus" id="toggleBtn3" style="display:none;" onclick="togglePassword3()"><i class="fas fa-eye"></i></button>
            <small>
                <p id="cpasserror" class="text-danger" style="display: none;"></p>
            </small>
        </div>
        <div class="mt-2 d-flex justify-content-end">
            <button type="submit" name="submit" id="change-password" class="btn btn-sm btn-primary">Save Changes</button>
        </div>
    </form>

    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>
    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>
    <script src="<?= JS_PATH ?>password-show-hide.js"></script>


    <!-- <script>
        var xmlResponse = request.responseText;

        const updatePassword = () => {
            let oldPass = document.getElementById("old-password").value;
            let newPass = document.getElementById("new-password").value;
            let cnfPass = document.getElementById("cnf-password").value;

            let url = `update-profiel-password.ajax.php?oldPass=${oldPass}&newPass=${newPass}$cnfPass=${cnfPass}`;

            request.open('GET', url, true);

            request.onreadystatechange = getEditUpdates;

            request.send(null);
        }


        function getEditUpdates() {
            if (request.readyState == 4) {
                if (request.status == 200) {
                    // var xmlResponse = request.responseText;
                    document.getElementById('reportUpdate').innerHTML = xmlResponse;
                } else if (request.status == 404) {
                    alert("Request page doesn't exist");
                } else if (request.status == 403) {
                    alert("Request page doesn't exist");
                } else {
                    alert("Error: Status Code is " + request.statusText);
                }
            }
        } //eof getEditUpdates
    </script> -->

</body>

</html>