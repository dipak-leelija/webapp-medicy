<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
</head>

<body>

    <div class="form-group mb-3">
        <input type="password" class="form-control " id="bpassword" name="password" maxlength="12" placeholder="Current Password" required>
    </div>
    <div class="form-group  mb-3">
        <input type="password" class="form-control " id="password" name="password" maxlength="12" placeholder="Enter New Password" required>
    </div>
    <div class="form-group mb-3 ">
        <input type="password" class="form-control " id="cpassword" name="cpassword" maxlength="12" placeholder="Confirm Password" required>
        <small>
            <p id="cpasserror" class="text-danger" style="display: none;"></p>
        </small>
    </div>
    <div class="mt-2 d-flex justify-content-end">
        <button type="button" class="btn btn-sm btn-primary">Save Changes</button>
    </div>


    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>

</body>

</html>