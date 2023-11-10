<?php
$page = "helth-care";
require_once 'config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'hospital.class.php';


//Health Care Class Initilized
$HealthCare = new HelthCare();


// Healthcare Addesss and details
if (isset($_POST['update']) ==  true) {

    $logo = $_FILES['site-logo']['name'];
    $tempImgname    = $_FILES['site-logo']['tmp_name'];
    $imgFolder      = "images/orgs/".$logo;
    move_uploaded_file($tempImgname, $imgFolder);

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

    $UpdateHealthcare = $HealthCare->updateHealthCare($imgFolder, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCarePin, $healthCareState, $healthCareEmail, $healthCareHelpLineNo, $healthCareApntBookingNo);
    if($UpdateHealthcare){
        echo "<script>alert('Updated!')</script>";
    }else{
        echo "<script>alert('Updation Failed!')</script>";

    }

}




//Fetching Healt Care Details
$showHealthCare = $HealthCare->showhelthCare($adminId);

$healthCareLogo      = $showHealthCare['logo'];
$healthCareId        = $showHealthCare['hospital_id'];
$healthCareName      = $showHealthCare['hospital_name'];
$healthCareAddress1  = $showHealthCare['address_1'];
$healthCareAddress2  = $showHealthCare['address_2'];
$healthCareCity      = $showHealthCare['city'];
$healthCareDist      = $showHealthCare['dist'];
$healthCarePin       = $showHealthCare['pin'];
$healthCareState     = $showHealthCare['health_care_state'];
$healthCareEmail     = $showHealthCare['hospital_email'];
$healthCarePhno      = $showHealthCare['hospital_phno'];
$healthCareApntbkNo  = $showHealthCare['appointment_help_line'];

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Buttons</title>

    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>upload-design.css" rel="stylesheet">
    <link href="<?= CSS_PATH ?>helth-care.css" rel="stylesheet">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include ROOT_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include ROOT_COMPONENT.'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Health Care</h1>

                    <!-- New Section -->
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Add / Update Helth Care Details</h6>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_GET['setup'])): ?>
                                <div class="alert alert-warning" role="alert">
                                    <?= $_GET['setup'] ?>
                                </div>
                                <?php endif; ?>
                                <form action="<?= PAGE ?>" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div>
                                                <img class="mb-0 mt-3 rounded" src="<?php echo $healthCareLogo; ?>"
                                                    width="100%" height="180" alt="">
                                            </div>
                                            <div class="col-md-12">
                                                <div>
                                                    <label class="mb-0 mt-1" for="name">Hospital Logo</label>
                                                </div>
                                                <div class="btn btn-primary btn-sm hospital_logo">

                                                    <input class="hospital_logo_input" name="site-logo" id="site-logo"
                                                        type="file">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="helthcare-name">Helth Care Name</label>
                                                <input class="form-control" type="text" name="helthcare-name"
                                                    id="helthcare-name" value="<?php echo $healthCareName; ?>" required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="helpline-no">Help Line Number</Address>
                                                    </label>
                                                <input class="form-control" type="text" name="helpline-no"
                                                    id="helpline-no" value="<?php echo $healthCarePhno; ?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="apnt-booking-no">Appointment Help
                                                    Line</label>
                                                <input class="form-control" type="text" name="apnt-booking-no"
                                                    id="apnt-booking-no" value="<?php echo $healthCareApntbkNo; ?>"
                                                    required>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="email">Helth Care Email</label>
                                                <input class="form-control" type="text" name="email" id="email"
                                                    value="<?php echo $healthCareEmail; ?>">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="state">Select State</label>
                                                <select class="form-control" name="state" id="state" required>
                                                    <?php echo '<option value="'.$healthCareState.'">'.$healthCareState.'</option>';?>
                                                    <option value="West Bengal">West Bengal</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="address-1">Address 1</label>
                                                <input class="form-control" type="text" maxlength="50" name="address-1"
                                                    id="address-1" value="<?php echo $healthCareAddress1; ?>" required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="address-2">Address 2</label>
                                                <input class="form-control" type="text" maxlength="50" name="address-2"
                                                    id="address-2" value="<?php echo $healthCareAddress2; ?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="city">City</label>
                                                <input class="form-control" type="text" maxlength="50" name="city"
                                                    id="city" value="<?php echo $healthCareCity; ?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="dist">Dist</label>
                                                <input class="form-control" type="text" maxlength="50" name="dist"
                                                    id="dist" value="<?php echo $healthCareDist; ?>" required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1 ps-1" for="pin">PIN</label>
                                                <input class="form-control" type="number" maxlength="7" minlength="7"
                                                    name="pin" id="pin" value="<?php echo $healthCarePin; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2">
                                        <button class="btn btn-success me-md-2" name="update"
                                            type="submit">Update</button>&nbsp

                                        <button class="btn btn-primary me-md-2" name="update" type="submit">ADD</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- New Section End -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include ROOT_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>

</body>

</html>