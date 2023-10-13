<?php
require_once dirname(__DIR__).'/config/constant.php';
require_once ADM_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR.'labtypes.class.php';

require_once CLASS_DIR.'sub-test.class.php';



if (isset($_GET['labtypeid'])) {

    $showLabtypeId = $_GET['labtypeid'];

    //Fetching Test Categories
    $labTypes = new LabTypes();
    $showLabType = $labTypes->showLabTypesById($showLabtypeId);

    foreach ($showLabType as $labtype) {
        $labTypeImge = $labtype['image'];
        $labTypeName = $labtype['test_type_name'];
        $pvdBy = $labtype['provided_by'];
        $labTypeDsc = $labtype['dsc'];
    }

    //Fetching Sub Tests
    $subTests = new SubTests();
    $subTestShow = $subTests->showSubTestsByCatId($showLabtypeId);
    // echo $subTests.$this->conn->error;
    // exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/custom/single-lab-page.css">

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="../css/bootstrap 5/bootstrap.css" rel="stylesheet">
    <link href="../css/bootstrap 5/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>



<body>


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

                <section class="main_section">

                    <div class="single_page">



                        <div class="image">

                        <img src="<?php echo $labTypeImge; ?>" alt="Lab Type Image">



                            <div class="provide">

                                <h2>Provided by</h2>

                            </div>

                            <div class="provide_lab">

                                <div class="medicy_lab"><b>Medicy Health Care</b></div>

                                <div class="logo"><img src="img/Logo.png" alt=""></div>

                            </div>

                            <div class="provide_lab_para">

                                <p><?php echo $pvdBy; ?></p>

                            </div>



                            <!-- <div class="need">

                                <h2>What preparation is needed for this Checkup?</h2>

                                <p>If any write here</p>

                            </div> -->





                        </div>



                        <div class="text_box">

                            <div class="title">

                                <h1><?php echo $labTypeName; ?></h1>

                                <p>Ideal for individuals aged <b>11-80 years</b> <br> Includes

                                    <b><?php  if ($subTestShow != 0) { echo count($subTestShow).'tests'; }else{ echo '0 Tests';} ?> </b></p>

                            </div>



                            <div class="title2">

                                <h2>Description</h2>

                                <p><?php echo $labTypeDsc; ?></p>

                            </div>



                            <div class="included_test">

                                <h2>Includes <?php  if ($subTestShow != 0) { echo count($subTestShow).'tests'; }else{ echo '0 Tests';} ?> Tests</h2>


                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <?php
                                    if ($subTestShow != 0) {
                                        $i = 0;
                                        foreach($subTestShow as $subTest){

                                            $subTestName = $subTest['sub_test_name'];
                                            // $ = $subTest['parent_test_id'];
                                            $subTestAge = $subTest['age_group'];
                                            $subTestPrep = $subTest['test_preparation'];
                                            $subTestDsc = $subTest['test_dsc'];
                                            $subTestPrice = $subTest['price'];

                                            $accordionId = $i++;
                                            // echo $accordionId;

                            echo '<div class="accordion-item">

                                        <h2 class="accordion-header" id="flush-heading'.$accordionId.'">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse'.$accordionId.'" aria-expanded="false" aria-controls="flush-collapse'.$accordionId.'">
                                                <div class="test_photo">
                                                    <img src="img/lab-tests/test_photo.png" alt="">
                                                </div>'.$subTestName.'
                                            </button>
                                        </h2>

                                        <div id="flush-collapse'.$accordionId.'" class="accordion-collapse collapse" aria-labelledby="flush-heading'.$accordionId.'" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <h4>Description</h4>
                                                <p>'.$subTestDsc.'</p>
                                                <h4>Percussion For This Test</h4>
                                                <p>'.$subTestPrep.'</p>
                                                <p>Age Group: <b>'.$subTestAge.'</b></p>
                                                <div>
                                                    <p>Rs-'.$subTestPrice.'</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';    
                                        }
                                    }else{
                                         echo 'No Tests avilable in this type of Test';
                                        }



                                       

                                    ?>
                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                <!-- Bootstrap JS -->

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"

                    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"

                    crossorigin="anonymous"></script>

                <div>

                    <?php include PORTAL_COMPONENT.'footer-text.php'; ?>

                </div>

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

                            <div class="modal-body">Select "Logout" below if you are ready to end your current session.

                            </div>

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



                <!-- Page level plugins -->

                <script src="vendor/chart.js/Chart.min.js"></script>



                <!-- Page level custom scripts -->

                <script src="js/demo/chart-area-demo.js"></script>

                <script src="js/demo/chart-pie-demo.js"></script>

</body>



</html>