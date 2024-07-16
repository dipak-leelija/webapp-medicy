<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR . 'UtilityFiles.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'sub-test.class.php';
require_once CLASS_DIR . 'encrypt.inc.php';


$showLabtypeId = $_GET['labtypeid'];
if (isset($_GET['labtypeid'])) {
    $showLabtypeId = url_dec($_GET['labtypeid']);
    //Fetching Test Categories
    $Pathology       = new Pathology;

    $labCat = $Pathology->showLabCat($showLabtypeId);


    if (is_array($labCat)) {
        foreach ($labCat as $eachCat) {
            $catImage  = $eachCat['image'];
            $catName   = $eachCat['name'];
            $catDsc    = $eachCat['dsc'];
            // $catStatus = $eachCat['status'];

            if (!empty($catImage)) {
                $catImage = LABTEST_IMG_PATH .  $catImage;
            } else {
                $catImage = LABTEST_IMG_PATH . 'default-lab-test/labtest.svg';
            }
        }
    }
    //Fetching Sub Tests data
    $allTests = json_decode($Pathology->showTestByCat($showLabtypeId));
    if ($allTests->status) {
        $allTests = $allTests->data;
    } else {
        $allTests = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/single-lab-page.css">

    <!-- Custom fonts for this template -->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH ?>bootstrap 5/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo PLUGIN_PATH ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body>
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
                <section class="main_section">
                    <div class="provide_lab mobile-only mx-3">
                        <div class="h5 medicy_lab"><b><?= $catName; ?></b></div>
                        <div class="logo"><img src="<?= ASSETS_PATH ?>img/Logo.png" alt=""></div>
                    </div>
                    <div class="single_page">
                        <div class="image">
                            <div class="border border-2 p-2" style="max-height: 270px;">
                                <img src="<?= $catImage ?>" alt="Lab Type Image">
                            </div>
                        </div>

                        <div class="text_box">
                            <div class="provide_lab large-screen">
                                <div class="medicy_lab">
                                    <b><?= $catName; ?></b>
                                </div>
                                <div class="logo"><img src="<?php echo ASSETS_PATH ?>img/Logo.png" alt=""></div>
                            </div>
                            <div>
                                <?= $catDsc; ?>
                            </div>
                        </div>

                    </div>

                    <h4 class="text-white text-center m-2 py-2" style="background-color:#4e73df;" >Includes <?= $allTests != 0 ? count($allTests) : '0'; ?> Tests</h4>

                    <div class="row m-2 ">
                        <?php
                        if ($allTests != 0) {
                            $subTestCount = count($allTests);
                            $columnCount = ceil($subTestCount / 3);
                            $column = 1;

                            // Counter for the accordion ID
                            $accordionId = 0;

                            // Counter for the subtest items
                            $itemCount = 0;

                            // Loop through each subtest
                            foreach ($allTests as $subTest) {
                                // Increment the accordion ID
                                $accordionId++;

                                // Get subtest details
                                $subTestName = $subTest->name;
                                $subTestPrep = $subTest->preparation;
                                $subTestDsc = $subTest->dsc;
                                $subTestPrice = $subTest->price;

                                // If it's the first item or a multiple of the column count, start a new column
                                if ($itemCount == 0 || $itemCount % $columnCount == 0) {
                                    // Close previous column if it's not the first one
                                    if ($itemCount > 0) {
                                        echo '</div>';
                                    }
                                    // Start a new column
                                    echo '<div class="col-12 col-md-4 p-3 py-4 item-column">';
                                }

                                // Increment item counter
                                $itemCount++;

                                // Output accordion item
                                echo '<div class="accordion accordion-flush border-0 bg-none mb-2" id="accordionFlushExample' . $accordionId . '">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading' . $accordionId . '">
                            <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse' . $accordionId . '" aria-expanded="false" aria-controls="flush-collapse' . $accordionId . '">
                                <div class="test_photo">
                                    <img src="img/lab-tests/test_photo.png" alt="">
                                </div>' . $subTestName . '
                            </button>
                        </h2>

                        <div id="flush-collapse' . $accordionId . '" class="accordion-collapse collapse" aria-labelledby="flush-heading' . $accordionId . '" data-bs-parent="#accordionFlushExample' . $accordionId . '">
                            <div class="accordion-body">
                                <h5>Description</h5>
                                <p>' . $subTestDsc . '</p>
                                <h5>Percussion For This Test</h5>
                                <p>' . $subTestPrep . '</p>
                                <div>
                                    <p>Rs-' . $subTestPrice . '</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
                            }

                            // Close the last column
                            echo '</div>';
                        } else {
                            echo '<div class="border border-3"><div class=" h5 text-center mt-2 py-5" style="border: 2px dashed #a9a7a7">No Tests available in this type of Test</div></div>';
                        }
                        ?>
                    </div>


                </section>

                <!-- Bootstrap JS -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
                </script>

                <!-- Bootstrap core JavaScript-->
                <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
                <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

                <!-- Core plugin JavaScript-->
                <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>

                <!-- Page level plugins -->
                <!-- <script src="<?php echo PLUGIN_PATH ?>chart.js/Chart.min.js"></script> -->

                <!-- Page level custom scripts -->
                <!-- <script src="<?php echo JS_PATH ?>demo/chart-area-demo.js"></script>
                <script src="<?php echo JS_PATH ?>demo/chart-pie-demo.js"></script> -->

</body>



</html>