<?php
require_once 'config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'subscription.class.php';
require_once CLASS_DIR . 'utility.class.php';

$Subscription   = new Subscription;
$Utility        = new Utility;

$currentUrl = $Utility->currentUrl();
// Healthcare Addesss and details
if (isset($_POST['update']) ==  true) {

    // print_r($_FILES);exit;
    if (!empty($_FILES['site-logo']['name'])) {
        $logo = $_FILES['site-logo']['name'];
        $tempImgname    = $_FILES['site-logo']['tmp_name'];
        $imgFolder      = "assets/images/orgs/" . $logo;
        move_uploaded_file($tempImgname, $imgFolder);
    } else {
        $imgFolder  = '';
    }

    $healthCareName          = $_POST['helthcare-name'];
    $healthCareAddress1      = $_POST['address-1'];
    $healthCareAddress2      = $_POST['address-2'];
    $healthCareCity          = $_POST['city'];
    $healthCareDist          = $_POST['dist'];
    $healthCarePin           = $_POST['pin'];
    $healthCareState         = $_POST['state'];
    $healthCareEmail         = $_POST['email'];
    $healthCareHelpLineNo    = $_POST['helpline-no'];
    $healthCareApntBookingNo = $_POST['apnt-booking-no'];

    $UpdateHealthcare = $HealthCare->updateHealthCare($imgFolder, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCarePin, $healthCareState, $healthCareEmail, $healthCareHelpLineNo, $healthCareApntBookingNo, $adminId);
    // print_r($UpdateHealthcare);

    if ($UpdateHealthcare) {
        header("Location: $currentUrl?setup=Clinic Data Updated");
        exit;
    } else {
        header("Location: $currentUrl?setup=Updation Failed!");
        exit;
    }
}

$bills = json_decode($Subscription->getSubscription($adminId));
if ($bills->status) {
    $allBills = $bills->data;
} else {
    $allBills = array();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
    <title><?= $healthCareName . " - " . SITE_NAME ?></title>

    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>upload-design.css" type="text/css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>helth-care.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>img-uv/img-uv.css" type="text/css" />
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />


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

                    <!-- New Section -->
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Add / Update Helth Care Details</h6>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_GET['setup'])) : ?>
                                    <div class="alert alert-warning" role="alert">
                                        <?= $_GET['setup'] ?>
                                    </div>
                                <?php endif; ?>
                                <form action="<?= PAGE ?>" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="alert alert-danger d-none" id="err-show" role="alert">
                                                Only jpg/jpeg and png files are allowed!
                                            </div>
                                            <div class="d-flex justify-content-around align-items-center">
                                                <img class="mb-0 mt-3 rounded img-uv-view" src="<?= $healthCareLogo; ?>" width="100%" height="180" alt="">
                                                <div class="">
                                                    <input type="file" style="display:none;" id="img-uv-input" accept=".jpg,.jpeg,.png" name="site-logo" onchange="validateFileType()">
                                                    <label for="img-uv-input" class="btn btn-primary">Change
                                                        Logo</label>
                                                </div>

                                            </div>

                                            <div class="col-md-12 mt-md-5">
                                                <label class="mb-0 mt-1" for="helthcare-name">Organization/Helth Care
                                                    Name <span class="text-danger font-weight-bold">*</span></label>
                                                <input class="form-control" type="text" name="helthcare-name" id="helthcare-name" value="<?= $healthCareName; ?>" required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="helpline-no">Help Line Number <span class="text-danger font-weight-bold">*</span></Address>
                                                </label>
                                                <input class="form-control" type="text" name="helpline-no" id="helpline-no" value="<?php echo $healthCarePhno; ?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="apnt-booking-no">Appointment Help
                                                    Line <span class="text-danger font-weight-bold">*</span></label>
                                                <input class="form-control" type="text" name="apnt-booking-no" id="apnt-booking-no" value="<?php echo $healthCareApntbkNo; ?>" required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="email">Helth Care Email</label>
                                                <input class="form-control" type="text" name="email" id="email" value="<?php echo $healthCareEmail; ?>">
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="address-1">Address 1 <span class="text-danger font-weight-bold">*</span></label>
                                                <textarea class="form-control" maxlength="50" name="address-1" id="address-1" rows="2" required><?= $healthCareAddress1; ?></textarea>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="address-2">Address 2</label>
                                                <textarea class="form-control" type="text" maxlength="50" name="address-2" id="address-2" rows="2"><?= $healthCareAddress2; ?></textarea>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="city">City <span class="text-danger font-weight-bold">*</span></label>
                                                <input class="form-control" type="text" maxlength="50" name="city" id="city" value="<?php echo $healthCareCity; ?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="dist">Dist <span class="text-danger font-weight-bold">*</span></label>
                                                <input class="form-control" type="text" maxlength="50" name="dist" id="dist" value="<?php echo $healthCareDist; ?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="state">Select State <span class="text-danger font-weight-bold">*</span></label>
                                                <select class="form-control" name="state" id="state" required>
                                                    <?php echo '<option value="' . $healthCareState . '">' . $healthCareState . '</option>'; ?>
                                                    <option value="West Bengal">West Bengal</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1 ps-1" for="pin">PIN <span class="text-danger font-weight-bold">*</span></label>
                                                <input class="form-control" type="number" maxlength="7" minlength="7" name="pin" id="pin" value="<?php echo $healthCarePin; ?>" required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1 ps-1" for="country">Country <span class="text-danger font-weight-bold">*</span></label>
                                                <select class="form-control" name="country" id="country" required>
                                                    <option value="India">India</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2">
                                        <button class="btn btn-success me-md-2" name="update" type="submit">Update</button>
                                    </div>

                                </form>
                            </div>
                        </div>

                        <!-- billing section -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Subscriptions</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Plan</th>
                                            <th scope="col">Start</th>
                                            <th scope="col">Upto</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($allBills as $eachBill) {
                                            echo "
                                            <tr>
                                                <th scope='row'>$eachBill->plan</th>
                                                <td>$eachBill->start</td>
                                                <td>$eachBill->end</td>
                                                <td>$eachBill->paid</td>
                                                <td>$eachBill->status</td>
                                            </tr>
                                            ";
                                        } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- billing section -->
                    </div>
                    <!-- New Section End -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <script>
        function validateFileType() {
            var fileName = document.getElementById("img-uv-input").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                document.getElementById("err-show").classList.add("d-none");
            } else {
                document.getElementById("err-show").classList.remove("d-none");
                // Show current image when error occurs
                document.querySelector('.img-uv-view').src = "<?= $healthCareLogo; ?>";
            }
        }
    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= PLUGIN_PATH ?>img-uv/img-uv.js"></script>

    <!-- Sweet alert plugins -->
    <!-- <script src="<?= JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script> -->

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>


</body>

</html>