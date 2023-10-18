<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR.'hospital.class.php';

$page = "helth-care";

//Health Care Class Initilized
$helthCare = new HelthCare();


// Healthcare Addesss and details
if (isset($_POST['update']) ==  true) {

    $logo = $_FILES['site-logo']['name'];
    $tempImgname = $_FILES['site-logo']['tmp_name'];
    $imgFolder = "img/".$logo;
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

    $UpdateHealthcare = $helthCare->updateHealthCare($imgFolder, $healthCareName, $healthCareAddress1, $healthCareAddress2, $healthCareCity, $healthCareDist, $healthCarePin, $healthCareState, $healthCareEmail, $healthCareHelpLineNo, $healthCareApntBookingNo);
    if($UpdateHealthcare){
        echo "<script>alert('Updated!')</script>";
    }else{
        echo "<script>alert('Updation Failed!')</script>";

    }

}




//Fetching Healt Care Details
$showHelthCare = $helthCare->showhelthCare();
foreach($showHelthCare as $helthCare){
    $helthCareLogo      = $helthCare['logo'];
    $helthCareId        = $helthCare['hospital_id'];
    $helthCareName      = $helthCare['hospital_name'];
    $helthCareAddress1  = $helthCare['address_1'];
    $helthCareAddress2  = $helthCare['address_2'];
    $helthCareCity      = $helthCare['city'];
    $helthCareDist      = $helthCare['dist'];
    $helthCarePin       = $helthCare['pin'];
    $helthCareState     = $helthCare['health_care_state'];
    $helthCareEmail     = $helthCare['hospital_email'];
    $helthCarePhno      = $helthCare['hospital_phno'];
    $helthCareApntbkNo  = $helthCare['appointment_help_line'];

}


       



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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/upload-design.css" rel="stylesheet">
    <link rel="stylesheet" href="css/helth-care.css">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include PORTAL_COMPONENT.'sidebar.php'; ?>
        <!-- end sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include PORTAL_COMPONENT.'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Health Care</h1>
                   
                    <!-- New Section -->
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Update Helth Care Details</h6>
                            </div>
                            <div class="card-body">
                                <form action="helth-care.php" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                        <div >
                                                <img class="mb-0 mt-3 rounded" src="<?php echo $helthCareLogo; ?>" width="100%" height="180" alt="">
                                        </div>
                                            <div class="col-md-12">
                                            <div>
                                                <label class="mb-0 mt-1" for="name">Hospital Logo</label>
                                            </div>
                                            <div class="btn btn-primary btn-sm hospital_logo">
                                                
                                                <input class="hospital_logo_input" name="site-logo" id="site-logo" type="file">
                                            </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="helthcare-name">Helth Care Name</label>
                                                <input class="form-control" type="text" name="helthcare-name" id="helthcare-name" value="<?php echo $helthCareName; ?>" required>
                                            </div>
        
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="helpline-no">Help Line Number</Address></label>
                                                <input class="form-control" type="text" name="helpline-no" id="helpline-no" value="<?php echo $helthCarePhno; ?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="apnt-booking-no">Appointment Help Line</label>
                                                <input class="form-control" type="text" name="apnt-booking-no" id="apnt-booking-no" value="<?php echo $helthCareApntbkNo; ?>" required>
                                            </div>
                                            
                                        </div>

                                        <div class="col-md-6">
                                        <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="email">Helth Care Email</label>
                                                <input class="form-control" type="text" name="email" id="email" value="<?php echo $helthCareEmail; ?>">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="state">Select State</label>
                                                <select class="form-control" name="state" id="state" required>
                                                    <?php echo '<option value="'.$helthCareState.'">'.$helthCareState.'</option>';?>
                                                    <option value="West Bengal">West Bengal</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="address-1">Address 1</label>
                                                <input class="form-control" type="text" maxlength="50" name="address-1" id="address-1" value="<?php echo $helthCareAddress1; ?>" required>
                                            </div>
        
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="address-2">Address 2</label>
                                                <input class="form-control" type="text" maxlength="50" name="address-2" id="address-2" value="<?php echo $helthCareAddress2; ?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="city">City</label>
                                                <input class="form-control" type="text" maxlength="50" name="city" id="city" value="<?php echo $helthCareCity; ?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="dist">Dist</label>
                                                <input class="form-control" type="text" maxlength="50" name="dist" id="dist" value="<?php echo $helthCareDist; ?>" required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1 ps-1" for="pin">PIN</label>
                                                <input class="form-control" type="number" maxlength="7" minlength="7" name="pin" id="pin" value="<?php echo $helthCarePin; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2">
                                        <button class="btn btn-success me-md-2" name="update" type="submit">Update</button>
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
            <?php include PORTAL_COMPONENT.'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>