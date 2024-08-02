<?php
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'doctor.category.class.php';


$docId = $_GET['docId'];

$Doctors        = new Doctors();
$doctorCategory = new DoctorCategory();

$showDoctor = json_decode($Doctors->showDoctorNameById($docId));
$docSplzList = $doctorCategory->showDoctorCategory();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= CSS_PATH ?>sb-admin-2.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>lab-test.css" type="text/css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>sweetalert2/sweetalert2.min.css" type="text/css">
    <link rel="stylesheet" href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css">

</head>

<body class="mx-3">

    <?php
    $showDoctor = $showDoctor->data;

    $docId          = $showDoctor->doctor_id;
    $docRegNo       = $showDoctor->doctor_reg_no;
    $docName        = $showDoctor->doctor_name;
    $docSplz        = $showDoctor->doctor_specialization;
    $docDegree      = $showDoctor->doctor_degree;
    $docAlsoWith    = $showDoctor->also_with;
    $docAddress     = $showDoctor->doctor_address;
    $docEmail       = $showDoctor->doctor_email;
    $docPhno        = $showDoctor->doctor_phno;

    ?>

    <div>

        <div class="row justify-content-between text-left">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="doc-name" class="col-form-label">Doctor Name:</label>
                    <input type="text" class="form-control" id="doc-name" value="<?php echo $docName; ?>" autocomplete="off">
                    <input type="text" class="form-control" id="doc-id" value="<?php echo $docId; ?>" readonly hidden>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="doc-reg-no" class="col-form-label">Doctor Reg. No:</label>
                    <input type="text" class="form-control" id="doc-reg-no" value="<?php echo $docRegNo; ?>">
                </div>
            </div>
        </div>

        <div class="row justify-content-between text-left">

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="doc-splz" class="col-form-label">Specialization: <span class="text-danger small">*</span></label>
                    <input type="text" name="doc-speclz-id" id="doc-speclz-id" value="<?= $docSplz ?>" autocomplete="off" hidden>

                    <?php
                        foreach ($docSplzList as $eachSplzList) {
                            if ($docSplz == $eachSplzList['doctor_category_id']) {
                                $docCatName = $eachSplzList['category_name'];
                            }
                        }
                    ?>

                    <input type="text" name="doc-speclz" id="doc-speclz" class="form-control" value="<?= $docCatName ?>">

                    <div class="p-2 bg-light col-md-6 c-dropdown" id="doc-specialization-list" style="display: none;">
                        <div class="lists" id="lists">
                            <?php if (!empty($docSplzList)) : ?>
                                <?php foreach ($docSplzList as $docSplzList) { ?>
                                    <div class="p-1 border-bottom list" id="<?= $docSplzList['doctor_category_id'] ?>" onclick="setDocSpecialization(this)">
                                        <?= $docSplzList['category_name'] ?>
                                    </div>
                                <?php } ?>

                                <div class="d-flex flex-column justify-content-center mt-1" onclick="addDocSpecialization()">
                                    <button type="button" id="add-specialization" class="text-primary border-0">
                                        <i class="fas fa-plus-circle"></i> Add Now</button>
                                </div>
                            <?php else : ?>
                                <p class="text-center font-weight-bold mb-1">Not Found!</p>
                                <div class="d-flex flex-column justify-content-center" onclick="addDocSpecialization()">
                                    <button type="button" id="add-specialization" class="text-primary border-0">
                                        <i class="fas fa-plus-circle"></i>Add Now</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>


            <div class="form-group col-sm-6 flex-column d-flex">
                <label for="doc-degree" class="col-form-label">Doctor Degree:</label>
                <input type="text" class="form-control" id="doc-degree" value="<?php echo $docDegree; ?>" autocomplete="off">
            </div>
        </div>

        <div class="row justify-content-between text-left">
            <div class="form-group col-sm-6 flex-column d-flex">
                <label for="doc-email" class="col-form-label">Email:</label>
                <input type="email" class="form-control" id="email" value="<?php echo $docEmail; ?>" autocomplete="off">
            </div>
            <div class="form-group col-sm-6 flex-column d-flex">
                <label for="doc-phno" class="col-form-label">Contact Number:</label>
                <input type="text" class="form-control" id="doc-phno" value="<?php echo $docPhno; ?>" autocomplete="off">
            </div>
        </div>

        <div class="row justify-content-between text-left">

            <div class="form-group col-sm-6 flex-column d-flex">
                <label for="doc-address" class="col-form-label">Address:</label>
                <textarea class="form-control" id="doc-address" rows="3"><?php echo $docAddress; ?></textarea autocomplete="off">
            </div>
            <div class="form-group col-sm-6 flex-column d-flex">
                <label for="doc-with" class="col-form-label">Also With:</label>
                <input type="text" class="form-control" id="doc-with" value="<?php echo $docAlsoWith; ?>" autocomplete="off">
            </div>
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-sm btn-primary" onclick="editDoc()">Save changes</button>
        </div>

    </div>

    <script src="<?php echo JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?php echo JS_PATH ?>doctors.js"></script>
    <script src="<?php echo JS_PATH ?>sweetalert2/sweetalert2.all.min.js"></script>

</body>

</html>