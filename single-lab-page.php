<?php
require_once __DIR__.'/config/constant.php';

require_once CLASS_DIR.'labtypes.class.php';
require_once CLASS_DIR.'sub-test.class.php';


if (isset($_GET['id'])) {

    $showLabtypeId = $_GET['id'];
    //Fetching Test Categories
    $labTypes = new LabTypes();
    $showLabType = $labTypes->showLabTypesById($showLabtypeId);

    foreach ($showLabType as $labtype) {
        $labTypeImge = $labtype['image'];
        $labTypeName = $labtype['test_type_name'];
        $pvdBy       = $labtype['provided_by'];
        $labTypeDsc  = $labtype['dsc'];
    }

    //Fetching Sub Tests
    $subTests = new SubTests();
    $subTestShow = $subTests->showSubTestsByCatId($showLabtypeId);
    // echo $subTests.$this->conn->error;
    // exit;
}

?>

<head>
    <?php include 'headerlink.php';?>
    <!-- Site Metas -->
    <title>Medicy Healthcare - we care for you</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSS For single page lab  -->
    <link rel="stylesheet" href="css/single-lab/single-lab-page.css">
    <!-- <link rel="stylesheet" href="css/bootstrap 5/bootstrap.css"> -->
</head>

<body id="top" class="clinic_version">

    <!-- Navbar -->
    <?php include ROOT_COMPONENT.'nav.php' ?>
    <!-- Navbar End -->

    <!-- <section class="main_section">
        <div class="single_page">
            <div class="image">
                <img src="<?php echo $labTypeImge; ?>" alt="Lab Type Image">
                <div class="provide">
                    <h2>Provided by</h2>
                </div>
                <div class="provide_lab">
                    <div class="medicy_lab"><b>Medicy Health Care</b></div>
                    <div class="logo"><img src="admin/img/Logo.png" alt=""></div>
                </div>
                <div class="provide_lab_para">
                    <p><?php echo $pvdBy; ?></p>
                </div>
            </div>

            <div class="text_box">
                <div class="title">
                    <h1><?php echo $labTypeName; ?></h1>
                    <p>Ideal for individuals aged <b>11-80 years</b> <br> Includes
                        <b><?php  if ($subTestShow != 0) { echo count($subTestShow).'tests'; }else{ echo '0 Tests';} ?>
                        </b>
                    </p>
                </div>

                <div class="title2">
                    <h2>Description</h2>
                    <p><?php echo $labTypeDsc; ?></p>
                </div>

                <div class="included_test">
                    <h2>Includes
                        <?php  if ($subTestShow != 0) { echo count($subTestShow).'tests'; }else{ echo '0 Tests';} ?>
                        Tests</h2>

                </div>
            </div>
        </div>
    </section> -->
    <div class="container my-6" style="margin: 200px auto 0;">

    

        <div class="custom-row">
            <?php
                if ($subTestShow != 0) {
                    $i = 0;
                    foreach($subTestShow as $subTest){
                        $subTestName = $subTest['sub_test_name'];
                        $subTestAge = $subTest['age_group'];
                        $subTestPrep = $subTest['test_preparation'];
                        $subTestDsc = $subTest['test_dsc'];
                        $subTestPrice = $subTest['price'];
                echo '<div class="c-col-33">
                        <div class="c-card">
                            <div class="c-card-body">
                                <h4>'.$subTestName.'</h4>
                            </div>
                            <div>
                                <small>'.$subTestAge.'</small>
                            </div>
                            <hr style="margin-bottom: 2px;">
                            <div class="c-card-footer">
                                <b>Price: 500</b>
                            </div>
                        </div>
                    </div>';
            }
            }else{
                 echo 'No Tests avilable in this type of Test';
                }
                ?>

        </div>


        <div class="custom-row">

        


        <div class="c-col-33">
            <div class="c-card">
                <div class="c-card-body">
                    <h4>Test Name</h4>
                </div>
                <hr style="margin-bottom: 2px;">
                <div class="c-card-footer">
                    <b>Price: 500</b>
                </div>
            </div>
        </div>


        <div class="c-col-33">
            <div class="c-card">
                <div class="c-card-body">
                    <h4>Test Name</h4>
                </div>
            </div>
        </div>

        </div>


        
    </div>

    <!-- Footer file  -->
    <?php include ROOT_COMPONENT.'footer.php' ?>
    <!-- end copyrights -->
    <a href="#top" data-scroll class="dmtop global-radius"><i class="fa fa-angle-up"></i></a>
    <!-- all js files -->
    <script src="js/all.js"></script>
    <!-- all plugins -->
    <script src="js/custom.js"></script>
    <!-- map -->
    <!-- Search JQuery -->
    <script type="text/javascript">
    let searchReult = document.getElementById('searchReult');

    function imu(x) {
        if (x.length == 0) {
            searchReult.innerHTML = ''
        } else {
            var XML = new XMLHttpRequest();
            XML.onreadystatechange = function() {
                if (XML.readyState == 4 && XML.status == 200) {
                    searchReult.innerHTML = XML.responseText;
                }
            };
            XML.open('GET', 'search.php?data=' + x, true);
            XML.send();
        }
    }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCNUPWkb4Cjd7Wxo-T4uoUldFjoiUA1fJc&callback=myMap">
    </script>
</body>

</html>