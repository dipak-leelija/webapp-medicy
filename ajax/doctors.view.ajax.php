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
                <label for="doc-name" class="col-form-label">Doctor Name:<span class="text-danger small">*</span></label>
                <input type="text" class="form-control" id="u-doctor-id" value="<?php echo $docId; ?>" readonly hidden>
                <input type="text" class="form-control" id="u-doctor-name" value="<?php echo $docName; ?>" autocomplete="off">
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="doc-reg-no" class="col-form-label">Registration No:<span class="text-danger small">*</span></label>
                <input type="text" class="form-control" id="u-doc-reg-no" value="<?php echo $docRegNo; ?>">
            </div>
        </div>
    </div>

    <div class="row justify-content-between text-left">

        <div class="col-sm-6">
            <div class="form-group">
                <label for="doc-splz" class="col-form-label">Specialization: <span class="text-danger small">*</span></label>
                <select id="u-doc-speclz-id" class="form-control">
                    <?php
                    foreach ($docSplzList as $eachSplzList) {
                        $selected = $docSplz == $eachSplzList['doctor_category_id'] ? 'selected' : '';
                        echo '<option value="' . $eachSplzList["doctor_category_id"] . '" ' . $selected . '>' . $eachSplzList["category_name"] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>


        <div class="form-group col-sm-6 flex-column d-flex">
            <label for="doc-degree" class="col-form-label">Doctor Degree:<span class="text-danger small">*</span></label>
            <input type="text" class="form-control" id="u-doc-degree" value="<?php echo $docDegree; ?>" autocomplete="off">
        </div>
    </div>

    <div class="row justify-content-between text-left">
        <div class="form-group col-sm-6 flex-column d-flex">
            <label for="doc-email" class="col-form-label">Email:</label>
            <input type="email" class="form-control" id="u-doc-email" value="<?php echo $docEmail; ?>" autocomplete="off">
        </div>
        <div class="form-group col-sm-6 flex-column d-flex">
            <label for="doc-phno" class="col-form-label">Contact Number:</label>
            <input type="text" class="form-control" id="u-doc-phno" value="<?php echo $docPhno; ?>" autocomplete="off">
        </div>
    </div>

    <div class="row justify-content-between text-left">

        <div class="form-group col-sm-6 flex-column d-flex">
            <label for="doc-address" class="col-form-label">Address:</label>
            <textarea class="form-control" id="u-doc-address" rows="3"><?php echo $docAddress; ?></textarea autocomplete="off">
            </div>
            <div class="form-group col-sm-6 flex-column d-flex">
                <label for="doc-with" class="col-form-label">Also With:</label>
                <input type="text" class="form-control" id="u-doc-with" value="<?php echo $docAlsoWith; ?>" autocomplete="off">
            </div>
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-sm btn-primary" onclick="editDoc()">Save changes</button>
        </div>

    </div>
<div>