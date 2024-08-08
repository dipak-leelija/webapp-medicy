<?php
require_once __DIR__ . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'encrypt.inc.php';
require_once CLASS_DIR . 'hospital.class.php';
require_once CLASS_DIR . 'doctors.class.php';
require_once CLASS_DIR . 'appoinments.class.php';
require_once CLASS_DIR . 'patients.class.php';
require_once CLASS_DIR . 'Pathology.class.php';
require_once CLASS_DIR . 'labBilling.class.php';
require_once CLASS_DIR . 'labBillDetails.class.php';


//Classes Initilized
$appointments    = new Appointments();
$Patients        = new Patients();
$Pathology       = new Pathology;
$Doctors         = new Doctors();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();


//Function Initilized
$showDoctors    = json_decode($Doctors->showDoctors($adminId));
$showSubTests   = $Pathology->showTestList();


if (isset($_GET['invoice'])) {

  $billId     = url_dec($_GET['invoice']);
  $patientId  = 0;
  $refDoc     = 0;

  $labBillDisplay = json_decode($LabBilling->labBillDisplayById($billId));
  $patientId    = $labBillDisplay->data->patient_id;
  $testDate     = $labBillDisplay->data->test_date;
  $refDoc       = $labBillDisplay->data->refered_doctor;
  $totalAmount  = $labBillDisplay->data->total_amount;
  $lessAmount   = $labBillDisplay->data->discount;
  $payable      = $labBillDisplay->data->total_after_discount;
  $paidAmount   = $labBillDisplay->data->paid_amount;
  $dueAmount    = $labBillDisplay->data->due_amount;
  $payStatus    = $labBillDisplay->data->status;

  $patientsDisplay = null;
  if ($patientId) {
    $patientsDisplay = json_decode($Patients->patientsDisplayByPId($patientId));
  }
}
############################################################################################

$existsDoctorId = NULL;
$existsDocName = NULL;

if (is_numeric($refDoc)) {
  $existsDoctorId = $refDoc;
  $docDetails = $Doctors->showDoctorNameById($refDoc);
  $docDetails = json_decode($docDetails);
  if ($docDetails->status == 1) {
    $existsDoctorName = $docDetails->data->doctor_name;
  }
} else {
  $existsDoctorName = $refDoc;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" type="image/x-icon" href="<?= FAVCON_PATH ?>">
  <title>Test Bill Edit - #<?= $billId ?></title>
  
  <link rel="stylesheet" href="<?php echo CSS_PATH ?>sb-admin-2.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/custom-form-style.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo CSS_PATH ?>font-awesome.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo PLUGIN_PATH ?>fontawesome-free/css/all.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo CSS_PATH ?>custom/appointment.css" type="text/css" />
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
        <!-- End of top bar -->


        <div class="container-fluid px-1  mx-auto">
          <div class="row d-flex justify-content-center align-items-stretch">
            <div class="col-xl-5 col-lg-5 col-md-5">
              <div class="card shadow p-4 mt-0">
                <div class="row justify-content-between text-left">
                  <div class="form-group col-sm-12 flex-column d-flex">
                    <div class="row justify-content-start">
                      <div class="col-md-5 mb-0">
                        <p>Patient Name: </p>
                      </div>
                      <div class="col-md-7 mb-0 justify-content-start">
                        <p class="text-start"><b><?php echo isset($patientsDisplay->name) ? $patientsDisplay->name : 'N/A'; ?> </b></p>
                      </div>

                      <div class="col-md-5 mb-0">
                        <p>Patient ID: </p>
                      </div>
                      <div class="col-md-7 mb-0 justify-content-start">
                        <p class="text-start"><b><?php echo $patientId; ?></b></p>
                      </div>
                      <div class="col-md-5 mb-0">
                        <p>Test Date: </p>
                      </div>
                      <div class="col-md-7 mb-0 justify-content-start">
                        <p class="text-start"><b><?php echo $testDate; ?> </b></p>
                      </div>

                      <div class="col-md-5 mb-0">
                        <p>Rrefered Doctor: </p>
                      </div>
                      <div class="col-md-7 mb-0 justify-content-start">
                        <p class="text-start"><b><span id="preferedDoc"><?php echo $existsDoctorName ?> </span></b></p>
                      </div>

                    </div>

                  </div>
                </div>

                <div class="row justify-content-between">
                  <div class="form-group col-sm-12 my-0">
                    <label class="form-control-label" for="patientDoctor">Rreffered By</label>
                    <select id="docList" class="form-control" name="patientDoctor" onChange="getDoc()" required>
                      <option value="">New Doctor</option>
                      <option value="Self">By Self</option>
                      <?php
                      foreach ($showDoctors->data as $showDoctorDetails) {
                        $doctorId = $showDoctorDetails->doctor_id;
                        $doctorName = $showDoctorDetails->doctor_name;
                        $selected = ($existsDoctorName == $doctorName) ?  'selected' : "";
                        echo "<option value='$doctorId' $selected>$doctorName</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <p class="text-center w-100 my-1">OR</p>
                  <div class="form-group col-sm-12 flex-column d-flex mt-0">
                    <input type="text" id="docName" class="form-control" placeholder="Enter Doctor Name" onkeyup="newDoctor(this.value);">
                  </div>
                </div>

                <div class="row justify-content-between text-left">
                  <div class="form-group col-sm-12 flex-column d-flex mt-0">
                    <input type="text" id="test-name" hidden>
                    <input type="text" id="test-id" hidden>
                    <select id="test" class="form-control" name="test" onChange="getPrice()" required>
                      <option disabled selected>Select Test</option>
                      <?php
                      foreach ($showSubTests as $rowSubTests) {
                        echo '<option value=' . $rowSubTests['id'] . '>' . $rowSubTests['name'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="row justify-content-between text-left">
                  <div class="form-group col-sm-5 flex-column d-flex mt-0">
                    <p class="form-control">Price ₹<span id="price">0</span></p>
                  </div>

                  <div class="form-group col-sm-5 flex-column d-flex mt-0">
                    <input class="form-control" id="disc" type="number" step="any" onkeyup="getDisc(this.value);" placeholder="Discount %" disabled>
                  </div>
                </div>
                <div class="row justify-content-between text-left">
                  <div class="form-group col-sm-6 flex-column d-flex mt-0">
                    <p class="form-control">Total ₹<span id="total">0</span></p>
                  </div>
                  <div class="form-group col-sm-5 flex-column d-flex mt-0">
                    <button class="btn btn-primary" id="add-bill-btn" type="button" onClick="getBill()" disabled>Add to Bill <i class="fa fa-arrow-right"></i></button>
                  </div>
                </div>


              </div>
            </div>


            <div class="col-xl-7 col-lg-7 col-md-7 text-center">
              <div class="card shadow h-100 p-4 mt-0">
                <form class="form-card d-flex flex-column justify-content-between h-100" action="altered-tests-bill-invoice.php" method="post">
                  <div>
                    <input type="hidden" name="patientId" value="<?php echo $patientId; ?>">
                    <input type="hidden" name="billId" value="<?php echo $billId; ?>">
                    <input type="hidden" name="patientName" value="<?php echo $patientsDisplay->name; ?>">
                    <input type="hidden" name="patientAge" value="<?php echo $patientsDisplay->age; ?>">
                    <input type="hidden" name="patientGender" value="<?php echo $patientsDisplay->gender; ?>">
                    <input type="hidden" name="patientPhnNo" value="<?php echo $patientsDisplay->phno; ?>">
                    <input type="hidden" name="patientTestDate" value="<?php echo $testDate; ?>">
                    <input type="hidden" name="prefferedDocId" id="prefferedDocId" value="<?php echo $existsDoctorId; ?>">
                    <input type="hidden" name="refferedDocName" id="refferedDocName" value="<?php echo $existsDoctorName; ?>">


                    <!-- Header Row -->
                    <div class="row justify-content-between text-left my-0 py-0">
                      <div class="form-group col-sm-1 flex-column my-0 py-0 d-flex">
                        <p class="my-0 py-0">SL</p>
                      </div>
                      <div class="form-group col-sm-4 flex-column mb-0 mt-0 d-flex">
                        <p class="my-0 py-0 ">Description</p>
                      </div>
                      <div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex">
                        <p class="my-0 py-0 ">Price ₹</p>
                      </div>
                      <div class="form-group col-sm-2 flex-column mb-0 mt-0 d-flex">
                        <p class="my-0 py-0 ">Disc %</p>
                      </div>
                      <div class="form-group col-sm-2 flex-column my-0 py-0 d-flex">
                        <p class="my-0 py-0 text-end">Amount</p>
                      </div>
                      <div class="form-group col-sm-1 flex-column my-0 py-0 d-flex">
                        <p class="my-0 py-0 text-end"></p>
                      </div>
                    </div>
                    <!--/END Header Row -->
                    <hr>
                    <!-- Test List Row -->
                    <div id="lists">
                      <?php $count = 0; ?>
                      <?php
                      $subTests = $LabBillDetails->testsNum($billId);
                      foreach ($subTests as $rowsubTests) {

                        $subTestId = $rowsubTests['test_id'];
                        $subTest = $Pathology->showTestById($subTestId);

                        $subTestPrice = $rowsubTests['test_price'];
                        $disc         = $rowsubTests['percentage_of_discount_on_test'];
                        $afterDisc    = $rowsubTests['price_after_discount'];

                        $count++;
                        echo "
                                      <div id='box-id-" . $count . "' class='row justify-content-between text-left my-0 py-0'>
                                        <div class='form-group col-sm-1 mb-2 py-0 '>
                                            <p class='my-0 py-0'>" . $count . "</p>
                                        </div>
                                        <div class='form-group col-sm-4 mb-2 mt-0'>
                                            <p class='my-0 py-0 '>" . $subTest['name'] . "</p>
                                            <input type='text' name='testId[]' value='" . $subTestId . "' hidden>
                                        </div>
                                        <div class='form-group col-sm-2 mb-2 py-0'>
                                            <p class='my-0 py-0 '>" . $subTestPrice . "</p>
                                            <input type='text' name='priceOfTest[]' value='" . $subTestPrice . "' hidden>
                                        </div>
                                        <div class='form-group col-sm-2 mb-2 mt-0'>
                                            <p class='my-0 py-0 '>" . $disc . "</p>
                                            <input type='text' name='disc[]' value='" . $disc . "' hidden>
                                        </div>
                                        <div class='form-group col-sm-2 mb-2 mt-0'>
                                            <p class='my-0 py-0 text-end'>" . $afterDisc . " </p>
                                            <input type='text' name='amountOfTest[]' value='" . $afterDisc . "' hidden>
                                        </div>
                                        <div class='form-group col-sm-1 mb-2 py-0'>
                                            <a class='my-0 py-0 text-end' onClick='removeField(" . $count . "," . $afterDisc . ")'>
                                                <i class='far fa-trash-alt'></i>
                                            </a>
                                        </div>
                                      </div>";
                      }
                      ?>
                      <input type="text" id="dynamic-id" value="<?php echo (int)$count; ?>" hidden>

                      <!-- Items are shown here by jquery -->
                    </div>
                    <!--/END Test List Row -->
                  </div>

                  <div>
                    <hr>
                    <div class="row justify-content-between text-left">
                      <div class="form-group col-sm-9 flex-column d-flex">
                        <p class="mb-1">Total: </p>
                      </div>
                      <div class="form-group col-sm-3 flex-column d-flex ">
                        <input type="number" step="any" name="total" id="total-test-price" value="<?php echo floatval($totalAmount); ?>" hidden>
                        <p class="mb-1 text-center">₹ <span id="total-view"><?php echo floatval($totalAmount); ?></span></p>
                      </div>
                      <!-- <div class="form-group col-sm-1 flex-column d-flex">
                      <p class="mb-1 text-end"> </p>
                    </div> -->
                    </div>

                    <!-- ################################################## -->
                    <div class="row justify-content-between text-left calculation">
                      <div class="form-group col-sm-9 flex-column d-flex">
                        <p class="mb-1">Payable: </p>
                      </div>
                      <div class="form-group col-sm-3 flex-column d-flex ">
                        <input class="myForm text-center" id="payable" type="number" step="any" name="payable" onkeyup="getLessAmount(this.value)" value="<?php echo  floatval($payable); ?>" required>
                      </div>
                    </div>
                    <!-- ################################################## -->

                    <div onload="disabledField();" class="row justify-content-between text-left">
                      <div class="form-group col-sm-3 flex-column d-flex">
                        <label class="form-control-label" for="">Update</label>
                        <select class="form-control" onchange="updateBill(this.value)" name="status" id="update" required>
                          <option value="" disabled selected>Select Update</option>
                          <option value="Completed" <?= $payStatus == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                          <option value="Partial Due" <?= $payStatus == 'Partial Due' ? 'selected' : ''; ?>>Partial Due</option>
                          <option value="Credit" <?= $payStatus == 'Credit' ? 'selected' : ''; ?>>Credit</option>
                        </select>

                        <!-- <span style="color:red;">*Update Status </span> -->

                      </div>
                      <div class="form-group col-sm-3 flex-column d-flex ">
                        <label class="form-control-label" for="">Due Amount</label>
                        <input class="myForm text-center" name="due" id="due" type="number" step="any" value="<?php echo floatval($dueAmount); ?>" onkeyup="dueAmount(this.value)" required readonly>
                      </div>
                      <div class="form-group col-sm-3 flex-column d-flex">
                        <label class="form-control-label" for="less-amount">Less Amount</label>
                        <input class="myForm text-center" id="less-amount" name="less_amount" type="any" value="<?php echo floatval($lessAmount); ?>" readonly>
                      </div>
                      <div class="form-group col-sm-3 flex-column d-flex">
                        <label class="form-control-label" for="">Paid Amount</label>
                        <input class="myForm text-center" name="paid_amount" id="paid-amount" type="number" step="any" value="<?php echo floatval($paidAmount); ?>" onkeyup="paidAmount(this.value)" required readonly>
                      </div>
                    </div>

                    <div class="row justify-content-end">
                      <button class="btn btn-primary w-25" type="submit" id="bill-generate" name="bill-generate" disabled>Update Bill</button>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>


        <!--/End Part 1  -->

        <!-- Bootstrap core JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery/jquery.min.js"></script>
        <script src="<?php echo JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="<?php echo PLUGIN_PATH ?>jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?= JS_PATH ?>sb-admin-2.min.js"></script>
        <script src="<?= JS_PATH ?>lab-bill-edit.js"></script>
</body>

</html>