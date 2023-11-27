<?php
$page = "lab-tests";
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not
require_once ROOT_DIR . '_config/accessPermission.php';


require_once CLASS_DIR . 'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';

require_once CLASS_DIR . 'labtypes.class.php';
require_once CLASS_DIR . 'sub-test.class.php';


$labTypes = new LabTypes;
$subTests = new SubTests;


//######################################################
// Adding Lab Category
if (isset($_POST['submit-lab-type']) == true) {

    $img = $_FILES['lab-image']['name'];
    // echo "img name : $img<br>";

    $tempImgname = $_FILES['lab-image']['tmp_name'];
    // echo "tempImg name : $tempImgname<br>";

    $imgFolder = "img/lab-tests/" . $img;
    move_uploaded_file($tempImgname, $imgFolder);

    $testName = $_POST['test-name'];
    $testName = str_replace("<", "&lt", $testName);
    $testName = str_replace("'", "\\", $testName);

    $testPvdBy = $_POST['provided-by'];
    $testPvdBy = str_replace("<", "&lt", $testPvdBy);
    $testPvdBy = str_replace("'", "\\", $testPvdBy);

    $testDsc = $_POST['test-dsc'];
    $testDsc = str_replace("<", "&lt", $testDsc);
    $testDsc = str_replace("'", "\\", $testDsc);

    //Object initilizing for Adding Main/Parent Tests/Labs
    $addLabType = $labTypes->addLabTypes($img, $testName, $testPvdBy, $testDsc);
}
// End of Adding Lab Category

//######################################################
//Object initilizing for Fetching Tests/Labs
$showLabTypes = $labTypes->showLabTypes();

// $showLabTypes = json_decode($showLabTypes);
// print_r($showLabTypes);
//######################################################
// Adding Sub tests Category
if (isset($_POST['subtest-submit']) == true) {


    $subTestName = $_POST['subtest-name'];
    $subTestName = str_replace("<", "&lt", $subTestName);
    $subTestName = str_replace("'", "\\", $subTestName);

    $parentTestId = $_POST['parent-test'];
    $parentTestId = str_replace("<", "&lt", $parentTestId);
    $parentTestId = str_replace("'", "\\", $parentTestId);

    $ageGroup = $_POST['age-group'];
    $ageGroup = str_replace("<", "&lt", $ageGroup);
    $ageGroup = str_replace("'", "\\", $ageGroup);

    $subTestPrep = $_POST['test-prep'];
    $subTestPrep = str_replace("<", "&lt", $subTestPrep);
    $subTestPrep = str_replace("'", "\\", $subTestPrep);

    $subTestDsc = $_POST['subtest-dsc'];
    $subTestDsc = str_replace("<", "&lt", $subTestDsc);
    $subTestDsc = str_replace("'", "\\", $subTestDsc);

    $price = $_POST['price'];
    $price = str_replace("<", "&lt", $price);
    $price = str_replace("'", "\\", $price);

    $SubTestUnit = $_POST['subtest-unit'];
    $SubTestUnit = str_replace("<", "&lt", $SubTestUnit);
    $SubTestUnit = str_replace("'", "\\", $SubTestUnit);

    $addsubTests = $subTests->addSubTests($subTestName, $SubTestUnit, $parentTestId, $ageGroup, $subTestPrep, $subTestDsc, $price);
    if (!$addsubTests) {
        echo "Something is wrong!";
    }
    // else {
    //     echo '<script>
    //     alert("'.$subTestName.' Test has been added!")
    //     </script>';
    // }
}
// End of Adding Sub tests Category

// $updateClicked = $_GET['updateClicked'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>SB Admin 2 - Buttons</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <!-- Custom fonts for this template-->
    <link href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo CSS_PATH ?>sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_PATH ?>lab-test.css">

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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Lab Tests</h1>
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 booked_btn">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="m-0 font-weight-bold text-primary">Lab Tests</h6>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="wrapper wrapper-content animated fadeInUp">
                                            <div class="ibox">
                                                <div class="ibox-content">
                                                    <div class="row m-t-sm">
                                                        <div class="col-lg-12">
                                                            <div class="panel blank-panel">
                                                                <div class="panel-heading">
                                                                    <div class="panel-options">
                                                                        <div class="row">
                                                                            <?php
                                                                            if ($showLabTypes == 0) {
                                                                                echo "No Test Type Avilable.";
                                                                            } else {
                                                                                // if($showLabTypes && isset($showLabTypes['status']) && $showLabTypes['status'] == 1){
                                                                                foreach ($showLabTypes as $showLabTypesShow) {
                                                                                    $testTypeId = $showLabTypesShow['id'];
                                                                                    $testName = $showLabTypesShow['test_type_name'];
                                                                                    $testDsc = $showLabTypesShow['dsc'];
                                                                                    $testPvdBy = $showLabTypesShow['provided_by'];

                                                                                    $delTestTypeId = "test-type-delete.php?deletetestype=" . $testTypeId;


                                                                                    echo '<div class="col-sm-3">
                                                                                <div class="card mt-3" style="min-width: 14rem; min-height: 20rem; max-height: 20rem;">
                                                                                    <img src=' . LABTEST_IMG_PATH . $showLabTypesShow['image'] . '
                                                                                        class="card-img-top mt-2 mh-25 mw-25 min-vw-25 min-vh-25" alt="...">
                                                                                        <a href="' . $delTestTypeId . '" onclick="return deleteConfirmation()" ><i class="far fa-trash-alt delete" ></i></a>
                                                                                    
                                                                                    <div class="card-body">
                                                                                        <h5 class="card-title">' . $testName . '</h5>
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <p class="card-text">' . substr($testDsc, 0, 90) . '</p>
                                                                                    </div>
                                                                                    <div
                                                                                        class="text-center mb-4 view_edit">
                                                                                        <a href="single-lab-page.php?labtypeid=' . $testTypeId . '"
                                                                                            class="btn btn-sm btn-primary mx-4 view">View</a>

                                                                                        <a class="btn btn-sm btn-primary mx-4 editCategory" data-bs-toggle="modal" data-bs-target="#LabCategoryEditModal" onclick="LabCategoryEditModal(' . $testTypeId . ')">Edit</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>';
                                                                                }
                                                                            }

                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add New Catagory Section -->

                    <!-- single page  -->
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Add New Lab Tests Details</h6>
                            </div>
                            <div class="card-body">
                                <form action="lab-tests.php" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="lab-image">Feature Image</label>
                                                <input class="form-control" id="lab-image" name="lab-image" type="file">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="name">Provided By</Address></label>
                                                <textarea class="form-control" name="provided-by" id="" cols="30" rows="5"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="name">Test Name</label>
                                                <input class="form-control" type="text" name="test-name">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="mb-0 mt-1" for="name">Description</label>
                                                <textarea class="form-control" name="test-dsc" id="" cols="30" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2">
                                        <button class="btn btn-success me-md-2" type="submit" name="submit-lab-type">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Lab Test Text -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Add New Sub Category Tests</h6>
                        </div>
                        <div class="card-body">
                            <form action="lab-tests.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="parent-test">Parent Test Name</label>
                                            <select name="parent-test" class="form-control" id="parent-test" required>
                                                <option value="" disabled selected>Select Main Test</option>
                                                <?php
                                                // if ($showLabTypes && isset($showLabTypes['status']) && $showLabTypes['status'] == 1) {
                                                    foreach ($showLabTypes as $labTypeName) {
                                                        echo '<option value="' . $labTypeName['id'] . '">' . $labTypeName['test_type_name'] . '</option>';
                                                    }
                                                // }
                                                ?>

                                            </select>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="test-prep">What preparation is needed for this
                                                Checkup?</Address></label>
                                            <textarea class="form-control" id="test-prep" name="test-prep" cols="30" rows="4" required></textarea>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="age-group">Age Group</label>
                                            <select class="form-control" id="age-group" name="age-group" required>
                                                <option value="" disabled selected>Select Age Group</option>
                                                <option value="Any Age Group">Any Age Group</option>
                                                <option value="Bellow 18">Bellow 18</option>
                                                <option value="Above 18">Above 18</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="subtest-name"> Sub Test Name</label>
                                            <input class="form-control" id="subtest-name" name="subtest-name" type="text" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="subtest-unit"> Sub Test Unit</label>
                                            <input class="form-control" id="subtest-unit" name="subtest-unit" type="text" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="subtest-dsc">Description</label>
                                            <textarea class="form-control" id="subtest-dsc" name="subtest-dsc" cols="30" rows="4" required></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-1" for="price">Price</label>
                                            <input class="form-control" id="price" name="price" type="number" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2 me-md-2">
                                    <button class="btn btn-success me-md-2" name="subtest-submit" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- End Lab Test Text -->
                    <!-- /Add New Catagory Section -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include ROOT_COMPONENT . 'footer-text.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>

    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Lab ptient selection Modal -->



    <!-- Category Edit Modal -->
    <div class="modal fade" id="LabCategoryEditModal" tabindex="-1" aria-labelledby="LabCategoryEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNicheDetails">Edit Lab Test Category</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="far fa-times-circle"></i></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <button type="button" class="btn btn-sm btn-primary" onClick="refreshPage()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Category Edit Modal -->

    <!-- Custom Javascript -->
    <script src="<?php echo JS_PATH ?>custom-js.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo JS_PATH ?>sb-admin-2.min.js"></script>


</body>

</html>