<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once ROOT_DIR . '_config/user-details.inc.php';
require_once CLASS_DIR . 'employee.class.php';
require_once CLASS_DIR . 'admin.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'empRole.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';

// echo $adminId;

$Utility    = new Utility;
$Admin      = new Admin;
$employees  = new Employees();
$desigRole = new Emproles();

$currentUrl = $Utility->currentUrl();

// $showEmployees = $employees->employeesDisplay($ADMINID);
// $showDesignation = $desigRole->designationRoleCheckForLogin();
// $showDesignation = json_decode($showDesignation, true);



$profileDetails = array();
if ($_SESSION['ADMIN']) {
    $adminDetails = $Admin->adminDetails($ADMINID);
    $adminDetails = json_decode($adminDetails);
    if ($adminDetails->status) {
        $adminData = $adminDetails->data[0];

        // foreach ($adminData as $adminData) {
        $firstName  = $adminData->fname;
        $lastName   = $adminData->lname;
        $image      = $adminData->adm_img;
        $profileImg = ADM_IMG_PATH . $image;
        $userName   = $adminData->username;
        $email      = $adminData->email;
        $phone      = $adminData->mobile_no;
        $password   = $adminData->password;
        $address    = $adminData->address;
        // }
    }
} else {

    $employeeDetails = json_decode($employees->employeeDetails($employeeId, $ADMINID));

    if ($employeeDetails->status) {
        $employeeData = $employeeDetails->data;
        // print_r($employeeData);
        foreach ($employeeData as $employeeData) {

            // $empName = $employeeData->emp_name;

            // $lastSpacePos = strrpos($empName, ' ');

            // if ($lastSpacePos !== false) {
            //     $firstName = substr($empName, 0, $lastSpacePos);
            //     $lastName = substr($empName, $lastSpacePos + 1);
            // }
            $firstName = $employeeData->fname;
            $lastName  = $employeeData->lname;
            $image      = $employeeData->emp_img;

            if (empty($image)) {
                $profileImg = DEFAULT_USER_IMG_PATH;
            } else {
                $profileImg = EMPLOYEE_IMG_PATH . $image;
            }

            $userName   = $employeeData->emp_username;
            $email      = $employeeData->emp_email;

            if (isset($employeeData->contact)) {
                $phone      = $employeeData->contact;
            } else {
                $phone      = "";
            }

            $password   = $employeeData->emp_password;
            $address    = $employeeData->emp_address;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title><?= $firstName . " " . $lastName ?> - <?= $HEALTHCARENAME?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/employees.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>form.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>/custom/password-show-hide.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
    
    <style>
        #toggle {
            /* position: absolutte;
            top: 25%;
            left: 200px; */
            position: relative;
            float: right;
            transform: translateY(-115%);
            width: 30px;
            height: 30px;
            background: url(img/hide-password.png);
            /* background-color: black; */
            background-size: cover;
            cursor: pointer;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT . 'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT . 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card-body">
                        <div class=" d-flex justify-content-center align-items-center">
                            <div class="shadow-sm bg-white rounded  profile">

                                <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
                                    <div class="w-25">
                                        <img class="img-uv-view shadow-lg " src="<?= !empty($profileImg) ?  $profileImg : ASSETS_PATH . 'images/undraw_profile.svg' ?>" alt="">
                                        <div class="position-absolute translate-middle ml-5">
                                            <form method="POST" action="<?= URL ?>_config/form-submission/profileSetup-form.php" id="profileImageForm" enctype="multipart/form-data">
                                                <input type="file" style="display:none;" id="img-uv-input" accept=".jpg,.jpeg,.png" name="profile-image" onchange="validateFileType(this)">
                                                <label for="img-uv-input" class="btn btn-sm btn-success ml-5 mt-n5 rounded-circle border-white"><i class="fas fa-camera"></i></label>
                                                <div class="alert alert-danger d-none" id="err-show" role="alert">
                                                    Only jpg/jpeg and png files are allowed!
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="ml-4 w-75">
                                        <h2 class="mb-0"><?= $firstName . " " . $lastName ?></h2>
                                        <p class="text-primary mb-0">Username: <?= $userName; ?></p>
                                        <p class="text-primary small">ID: <?= $adminId; ?></p>
                                    </div>
                                </div>

                                <div>
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs row" role="tablist">
                                        <li class="nav-item col-6">
                                            <a class="nav-link active" data-toggle="tab" href="#home">Details</a>
                                        </li>
                                        <li class="nav-item col-6">
                                            <a class="nav-link" data-toggle="tab" href="#menu1">Password</a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <label class="d-none" id="menue-control">0</label>
                                        <div id="home" class="tab-pane active">
                                            <div class="user w-100 p-3 mb-2 ">
                                                <form action="_config/form-submission/profileSetup-form.php" method="post" id="edit-profile">
                                                    <div class="d-flex justify-content-between align-item-between flex-wrap">
                                                        <div class="col-md-6 form-group">
                                                            <input type="text" class=" med-input" id="fname" name="fname" maxlength="20" value="<?= $firstName; ?>" required>
                                                            <label class="med-label" style="margin-left:10px;" for="fname">First Name</label>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <input type="text" class=" med-input" id="lname" name="lname" maxlength="20" value="<?= $lastName; ?>" required>
                                                            <label class="med-label" style="margin-left:10px;" for="lname">Last Name</label>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-item-between flex-wrap">
                                                        <div class="col-md-6">
                                                            <div class=" form-group">
                                                                <input type="email" class=" med-input" id="email" name="email" maxlength="80" value="<?= $email; ?>" required>
                                                                <label class="med-label" for="email">Email
                                                                    Address</label>
                                                            </div>
                                                            <div class=" form-group">
                                                                <input type="number" class=" med-input" id="mobile-number" name="mobile-number" maxlength="10" max="9999999999" value="<?= $phone; ?>" required>
                                                                <label class="med-label" for="mobile-number">Contact
                                                                    Number</label>
                                                            </div>
                                                            <!-- <div>
                                                                <input type="number" class="form-control mb-3 "
                                                                    id="mobile-number" name="mobile-number"
                                                                    placeholder="Contact Number" value="<?= $phone; ?>"
                                                                    maxlength="10" max="9999999999">
                                                            </div> -->
                                                        </div>

                                                        <div class="col-md-6 form-group">
                                                            <textarea class="med-input" placeholder="" name="address" rows="6" style="height: 105px;"><?= $address; ?></textarea>
                                                            <label class="med-label" style="margin-left:10px;" for="address">Address</label>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn-sm btn-primary" type="submit" name="submit" id="updateBtn">Update</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>

                                        <div id="menu1" class="tab-pane fade">
                                            <form class="p-3" action="<?php echo htmlspecialchars(URL . 'ajax/updateProfile-password.ajax.php'); ?>" method="post">

                                                <div class="form-group mb-3">
                                                    <input type="password" class="med-input " id="old-password" name="old-password" maxlength="12" placeholder="" required oninput="showToggleBtn('old-password', 'toggleBtn1')">
                                                    <label class="med-label" for="old-password">Current Password</label>
                                                    <i class="fas fa-eye " id="toggleBtn1" style="display:none;font-size:1.2rem;width:4%;top:30%;right:4px;" onclick="togglePassword('old-password', 'toggleBtn1')"></i>
                                                </div>
                                                <div class="form-group  mb-3">
                                                    <input type="password" class="med-input " id="new-password" name="new-password" maxlength="12" placeholder="" required oninput="showToggleBtn('new-password', 'toggleBtn2')">
                                                    <label class="med-label" for="new-password">Enter New Password</label>
                                                    <i class="fas fa-eye " id="toggleBtn2" style="display:none;font-size:1.2rem;width:4%;top:30%;right:4px;" onclick="togglePassword('new-password', 'toggleBtn2')"></i>
                                                </div>
                                                <div class="form-group mb-3 ">
                                                    <input type="password" class="med-input " id="cnf-password" name="cnf-password" maxlength="12" placeholder="" required oninput="showToggleBtn('cnf-password', 'toggleBtn3')">
                                                    <label class="med-label" for="cnf-password">Confirm Password</label>
                                                    <i class="fas fa-eye " id="toggleBtn3" style="display:none;font-size:1.2rem;width:4%;top:30%;right:4px;" onclick="togglePassword('cnf-password', 'toggleBtn3')"></i>
                                                    <small>
                                                        <p id="cpasserror" class="text-danger" style="display: none;">
                                                        </p>
                                                    </small>
                                                </div>
                                                <div class="mt-2 d-flex justify-content-end">
                                                    <button type="submit" name="submit" id="change-password" class="btn btn-sm btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <?php include ROOT_COMPONENT . 'footer-text.php'; ?> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php include ROOT_COMPONENT . 'generateTicket.php'; ?>

    <!-- Custom Javascript -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    <script src="<?php echo JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>


    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.js"></script>
    <script src="<?= JS_PATH ?>password-show-hide.js"></script>




    <script>
        function validateFileType(t) {
            Swal.fire({
                title: "Are you sure?",
                text: "Change Profile Photo?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#0047AB",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Change!"
            }).then((result) => {
                if (result.isConfirmed) {
                    var fileName = document.getElementById("img-uv-input").value;
                    var idxDot = fileName.lastIndexOf(".") + 1;
                    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
                    if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                        readURL(t, fileName);
                    } else {
                        document.getElementById("err-show").classList.remove("d-none");
                    }
                }
            });
        }


        const readURL = (input, fileName) => {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = (e) => {
                    document.querySelector(".img-uv-view").src = e.target.result;
                    console.log(fileName.split(/(\\|\/)/g).pop());
                    $('#profileImageForm').submit();
                }

                reader.readAsDataURL(input.files[0]);
                document.querySelector("#upload-btn").classList.remove("d-none");
            }
        };



        if (document.getElementById("menue-control").innerHTML.trim() === '1') {

            // control home menue
            document.getElementById("home").classList.remove("active");
            document.getElementById("home").classList.add("fade");

            // control menue 1
            document.getElementById("menu1").classList.remove("fade");
            document.getElementById("menu1").classList.add("show", "active");
            document.getElementById("menu1").classList.add("active");

            document.getElementById("menue-control").innerHTML = '0';
        }
    </script>
</body>

</html>