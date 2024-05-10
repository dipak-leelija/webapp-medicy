<?php
$page = "appointments";
require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR.'dbconnect.php';
require_once ROOT_DIR . '_config/healthcare.inc.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'idsgeneration.class.php';
require_once CLASS_DIR . 'utility.class.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR. 'encrypt.inc.php';


//Classes Initilizing
// $appointments   = new Appointments;
// $IdsGeneration  = new IdsGeneration;
// $Patients       = new Patients;
// $Utility        = new Utility;
$HealthCare     = new HealthCare;
$doctors        = new Doctors();

// $currentURL = $Utility->currentUrl();


$test = false;
if (isset($_GET['test'])) {
    if ($_GET['test'] == 'true') {
        $test = true;
    }
}

$showDoctors = $doctors->showDoctors($adminId);
$showDoctors = json_decode($showDoctors);
$allDoctors  = $showDoctors->data;

$clinicInfo  = $HealthCare -> showHealthCare($adminId);
$clinicInfo  = json_decode($clinicInfo, true);

if ($clinicInfo['status'] == 1) {
    $data = $clinicInfo['data'];
     $district = $data['dist'];
     $pin      = $data['pin'];
     $state    = $data['health_care_state'];
} else {
    echo "Error: " . $clinicInfo['msg'];
}

?>

<!-- <!DOCTYPE html>
<html lang="en">

<head> -->


<div class="container-fluid px-1  mx-auto">
    <div class="row d-flex justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12 text-center">
            <div class="card  p-4">
                <h4 class="text-center mb-4 mt-0"><b>Fill The Patient Details</b></h4>

                <form id="patientForm" class="form-card " action="" method="post">
                    <div class="row justify-content-between text-left">
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="text" class="form-control newbillInput" id="patientName" name="patientName"
                                placeholder="" Required autocomplete="off">
                            <label for="patientName">Patient Name<span class="text-danger"> *</span></label>
                        </div>
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="text" class="form-control newbillInput" id="patientGurdianName"
                                name="patientGurdianName" placeholder="" required autocomplete="off">
                            <label for="patientGurdianName">Gurdian Name<span class="text-danger"> *</span></label>
                        </div>
                    </div>

                    <div class="row justify-content-between text-left">
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="number" class="form-control newbillInput" id="patientAge" name="patientAge"
                                onfocusout="checkAge(this)" placeholder="" required autocomplete="off">
                            <label for="patientAge">Age<span class="text-danger"> *</span></label>
                            <p class='text-danger fs-6' id='ageMsg'></p>
                        </div>
                        <div class="col-sm-6 mt-4 mt-2">
                            <label class="mb-3 mr-1" for="gender">Gender: </label>
                            <input type="radio" class="btn-check" name="gender" id="male" value="Male"
                                autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="male" value="Male">Male</label>
                            <input type="radio" class="btn-check" name="gender" id="female" value="Female"
                                autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="female" value="Female">Female</label>
                            <input type="radio" class="btn-check" name="gender" id="secret" value="Others"
                                autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="secret" value="Secret">Others</label>
                            <div class="valid-feedback mv-up">You selected a gender!</div>
                            <div class="invalid-feedback mv-up">Please select a gender!</div>
                        </div>
                    </div>

                    <div class="row justify-content-between text-left">
                        <?php if(!$test) : ?>
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="number" class="form-control newbillInput" id="patientWeight"
                                name="patientWeight" onfocusout="checkWeight(this)" placeholder="" required
                                autocomplete="off">
                            <label for="patientWeight">Weight<span class="text-danger"> *</span></label>
                        </div>
                        <?php endif; ?>
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="tel" class="form-control newbillInput" id="patientPhoneNumber"
                                name="patientPhoneNumber" onchange="checkContactNo(this)" placeholder="" required
                                autocomplete="off" maxlength="10">
                            <label for="patientPhoneNumber">Phone
                                number<span class="text-danger"> *</span></label>
                                <div class='text-danger fs-6' id="pMsg"></div>
                        </div>
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="date" class="form-control newbillInput" id="appointmentDate"
                                name="appointmentDate" placeholder="" value="<?php print(date("Y-m-d")) ?>" required
                                autocomplete="off">
                            <label for="appointmentDate">Appointment
                                Date<span class="text-danger"> *</span></label>
                        </div>
                        <?php if (!$test) : ?>
                        <!-- <div class="form-group col-sm-6 flex-column d-flex">
                            <label class="form-control-label px-3" for="patientEmail">Patient
                                Email</label>
                            <input class="newbillInput" type="email" id="email" name="patientEmail"
                                placeholder="Patient Email" onfocusout="checkMail(this)" autocomplete="off">
                        </div> -->
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="email" class="form-control newbillInput" id="email" name="patientEmail"
                                placeholder="" autocomplete="off" onchange="checkMail(this)">
                            <label for="patientEmail">Patient Email</span></label>
                            <div class='text-danger fs-6' id='emailMsg'></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="row justify-content-between text-left">
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="text" class="form-control newbillInput" id="patientAddress1"
                                name="patientAddress1" placeholder="" required autocomplete="off">
                            <label for="patientAddress1">Address <span class="text-danger"> *</span></span></label>
                        </div>
                        <?php if ($test) : ?>
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="email" class="form-control newbillInput" id="email" name="patientEmail"
                                placeholder="" autocomplete="off" onchange="checkMail(this)">
                            <label for="patientEmail">Patient Email</span></label>
                            <div class='text-danger fs-6' id='emailMsg'></div>
                        </div>
                        <?php endif; ?>
                        <?php if (!$test) : ?>
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="text" class="form-control newbillInput" id="patientPS" name="patientPS"
                                placeholder="" required autocomplete="off">
                            <label for="patientPS">Police Station<span class="text-danger"> *</span></label>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="row justify-content-between text-left">
                        <?php if ($test) : ?>
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="text" class="form-control newbillInput" id="patientPS" name="patientPS"
                                placeholder="" required autocomplete="off">
                            <label for="patientPS">Police Station<span class="text-danger"> *</span></label>
                        </div>
                        <?php endif; ?>
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="text" class="form-control newbillInput" id="patientDist" name="patientDist"
                                Value="<?= $district; ?>" placeholder="" required autocomplete="off">
                            <label for="patientDist">District<span class="text-danger"> *</span></label>
                        </div>
                        <?php if (!$test) : ?>
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="number" class="form-control newbillInput" id="patientPIN" name="patientPIN" required
                                onfocusout="checkPin(this)" Value="<?= $pin ?>" placeholder="" autocomplete="off">
                            <label for="patientPIN">PIN Code<span class="text-danger"> *</span></label>
                            <div class='text-danger fs-6' id='pinMsg'></div>
                        </div>
                        <?php endif; ?>

                    </div>

                    <div class="row justify-content-between text-left">
                        <?php if ($test) : ?>
                        <div class="form-floating col-sm-6 mt-3">
                            <input type="number" class="form-control newbillInput" id="patientPIN" name="patientPIN" required
                                onfocusout="checkPin(this)" Value="<?= $pin ?>" placeholder="" autocomplete="off">
                            <label for="patientPIN">PIN Code<span class="text-danger"> *</span></label>
                            <div class='text-danger fs-6' id='pinMsg'></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($test) : ?>
                        <div class="form-floating col-sm-6 mt-3">
                            <select class="form-select newbillInput" id="" name="patientState" required>
                                <option Value="<?= $state ?>" selected><?= $state ?></option>
                                <option value="West bengal">West Bengal</option>
                                <option value="Other">Other</option>
                            </select>
                            <label for="patientState">State<span class="text-danger"> *</span></label>
                        </div>
                        <?php endif; ?>
                    </div>



                    <?php if (!$test) : ?>
                    <div class="row justify-content-between text-left">
                        <div class="form-floating col-sm-6 mt-3">
                            <select class="form-select newbillInput" id="" name="patientState" required>
                                <option Value="<?= $state ?>" selected><?= $state ?></option>
                                <option value="West bengal">West Bengal</option>
                                <option value="Other">Other</option>
                            </select>
                            <label for="patientState">State<span class="text-danger"> *</span></label>
                        </div>

                        <div class="form-floating col-sm-6 mt-3">
                            <select class="form-select newbillInput" id="docList" name="patientDoctor" required>
                                <?php
                                                    if ($allDoctors != null) {
                                                        foreach ($allDoctors as $showDoctorDetails) {
                                                            $doctorId = $showDoctorDetails->doctor_id;
                                                            $doctorName = $showDoctorDetails->doctor_name;
                                                            echo '<option value=' . $doctorId . '>' . $doctorName . '</option>';
                                                        }
                                                    }
                                                    ?>
                            </select>
                            <label for="patientDoctor">Doctor Name<span class="text-danger"> *</span></label>
                        </div>
                    </div>
                    <?php else: ?>
                    <input class="d-none" type="text" value=" " name="patientDoctor">
                    <?php endif; ?>

                    <div class="row justify-content-end mt-3">
                        <div class="form-group col-sm-4">
                            <button type="submit" name="submit" id="submitBtn"
                                class="btn-block btn-primary">Submit</button>
                            <!-- onclick=submitNewLabPatientData() -->
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>

</div>