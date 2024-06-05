<?php
require_once dirname(dirname(__DIR__)).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php';//check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'encrypt.inc.php';
require_once ROOT_DIR.'_config/healthcare.inc.php';
require_once CLASS_DIR.'sub-test.class.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'labBilling.class.php';
require_once CLASS_DIR.'labBillDetails.class.php';
require_once CLASS_DIR.'patients.class.php';
require_once CLASS_DIR.'utility.class.php';



//  INSTANTIATING CLASS
$SubTests        = new SubTests();
$Doctors         = new Doctors();
$Patients        = new Patients();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();
$Utility         = new Utility;

if (isset($_POST['bill-generate'])) {

    $testIds         = $_POST['testId'];        // each test id
    $priceOfTest     = $_POST['priceOfTest'];   // each test
    $testDisc        = $_POST['disc'];          //of % of each test
    $testAmount      = $_POST['amountOfTest'];  // of each test after discount
    $totalAmount     = $_POST['total'];         // of all tests
    $payable         = $_POST['payable'];  //payable by customer
    $paidAmount      = $_POST['paid_amount'];  //paid by customer
    $dueAmount       = $_POST['due'];
    $discountOnTotal = $_POST['less_amount'];
    $status          = $_POST['status'];


    $patientId       = $_POST['patientId'];
    $patientName     = $_POST['patientName'];
    $patientAge      = $_POST['patientAge'];
    $patientGender   = $_POST['patientGender'];
    $patientPhno     = $_POST['patientPhnNo'];
    $testDate        = $_POST['patientTestDate'];
    $docId           = $_POST['prefferedDocId'];
    $referedDocName  = $_POST['refferedDocName'];

    // echo "<br>disc percent :"; print_r($testDisc);
    // echo "<br>discount on total : "; print_r($discountOnTotal);
    // // echo "<br>disc percent : $testDisc";
    // exit;
    ###################### Patient Visit Update ######################
    $labVisited = $Patients->labVisists($patientId);

    if($labVisited == NULL){
        $labVisited = 1;
    }else{
        $labVisited = $labVisited +1;
    }

    $updateVisit = $Patients->updateLabVisiting($patientId, $labVisited);
    if ($updateVisit) {

        ##################################################################
        ######################### Bill Insertion #########################
        ##################################################################
        $testDiscBck   = $testDisc;
        $testAmountBck = $testAmount;

        ################ Bill id/ invoice id generation #############

        $billId = $Utility->randomKeysByNumber(15);
        
        ############ End Of Bill ID / Invoice Id Generagtion #########

        ################ Doctor Selection ###############
        $referedDoc = '';
        $doctorName = '';
        $doctorReg  = '';
        if ($docId == 'Self') {
            $referedDoc = $docId;
            $doctorName = 'Self';
            $doctorReg  = NULL;
        }else{
            if ($docId != NULL) {
                //function calling
                $showDoctorById = $Doctors->showDoctorNameById($docId);
                $showDoctorById = json_decode($showDoctorById);
                // print_r($showDoctorById);
                if($showDoctorById->status == 1){
                    foreach($showDoctorById->data as $rowDoctor){
                        $referedDoc = $docId;
                        $doctorName = $rowDoctor->doctor_name;
                        // print_r($doctorName);
                        $doctorReg  = $rowDoctor->doctor_reg_no;
                    }
                } 
            }
        }
        
        if ($referedDocName != NULL) {
            $referedDoc = $referedDocName;
            $doctorName = $referedDocName;
            $doctorReg  = NULL;
        }
        ############# End of Doctor Selection ############


        ############# CGST & SGST Generation #############
        
        $cgst = 0;
        
        $sgst = 0;
        ########## End of CGST & SGST Generation ##########

        $totalAfterDiscount = $payable;

        $addLabBill = $LabBilling->addLabBill($billId, NOW, $patientId, $referedDoc, $testDate, $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status, $employeeId, NOW, $adminId);

        $addLabBill = json_decode($addLabBill);
        // print_r($addLabBill);

        if ($addLabBill->status) {
            ##################################################################
            ###################### Bill Details Insertion ####################
            ##################################################################

            $testDiscsBck   = $testDisc;
            $testAmountsBck = $testAmount;
            $priceOfTestBck = $priceOfTest;

            foreach ($testIds as $testId) {
                $percentageOfDiscount   = array_shift($testDiscsBck);
                $priceAfterDiscount     = array_shift($testAmountsBck);
                $testPrice              = array_shift($priceOfTestBck);

                $addBillDetails = $LabBillDetails->addLabBillDetails($billId, NOW, $testDate, $testId, $testPrice, $percentageOfDiscount, $priceAfterDiscount);
            }

            $addBillDetails = json_decode($addBillDetails);
            
            if($addBillDetails->status){
                header("Location: lab-bill-generation.php?billId=".url_enc($billId));
                exit;
            }else{
                echo "<script>alert('Bill details Not added!!, Something is Wrong!');</script>";
                header("Location: lab-patient-selection.php?test=true");
                exit;
            }
        }else{
            echo "<script>alert('Unable to generate lab bill !!, Something is Wrong!');</script>";
            header("Location: lab-patient-selection.php?test=true");
            exit;
        }

    }else{
        echo "<script>alert('Patient Visiting Not Updated!!, Something is Wrong!');</script>";
        header("Location: lab-patient-selection.php?test=true");
        exit;
    } 
}else{
    header("Location: lab-patient-selection.php?test=true");
    exit;
}

/* ============================ End ============================ 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="<?= CSS_PATH ?>custom/test-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if($payable == $paidAmount){ echo "paid-bg";} ?>">
            <div class="card-body ">
                <div class="row mt-2">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;position: absolute;"
                            src="<?= $healthCareLogo?>" alt="Medicy">
                    </div>
                    <div class="col-sm-8 ps-4">
                        <h4 class="text-start mb-1"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1.', '.$healthCareAddress2; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php  echo $healthCareCity.', '.$healthCarePin; ?></small>
                        </p>

                        <p class="text-start" style="margin-top: -5px; margin-bottom: 2px;">
                            <small><?php echo 'M: '.$healthCarePhno.', '.$healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-secondary">
                        <p class="my-0">Invoice</p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id: <?php echo $billId; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;width: 68%;"><small>Date:
                                <?= NOW ?></small></p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">
            <div class="row my-1">
                <div class="col-sm-6 my-0">
                    <p style="margin-top: -3px; margin-bottom: 0px;"><small><b>Patient: </b>
                            <?php echo $patientName.', <b>Age:</b> '.$patientAge; ?></small></p>
                    <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>M:</b>
                            <?php echo $patientPhno; echo ', <b>Test date:</b> '.$testDate;?></small></p>
                </div>
                <div class="col-sm-6 my-0">
                    <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                            <?php echo $doctorName; ?></small></p>
                    <p class="text-end" style="margin-top: -5px; margin-bottom: 0px;">
                        <small><?php if($doctorReg != NULL){echo '<b>Reg:</b> '.$doctorReg; } ?></small>
                    </p>
                </div>

            </div>
            <hr class="my-0" style="height:1px;opacity: 1.25;">

            <div class="row py-1">
                <!-- table heading -->
                <div class="col-sm-2 ps-4">
                    <small><b>SL. NO.</b></small>
                </div>
                <div class="col-sm-4">
                    <small><b>Description</b></small>
                </div>
                <div class="col-sm-2">
                    <small><b>Price (₹)</b></small>
                </div>
                <div class="col-sm-2">
                    <small><b>Disc (%)</b></small>
                </div>
                <div class="col-sm-2 text-end">
                    <small><b>Amount (₹)</b></small>
                </div>
                <!--/end table heading -->
            </div>

            <hr class="my-0" style="height:2px;border: 1px solid;opacity: 1.25;">

            <div class="row">
                <?php
                $slno = 1;
                $subTotal = floatval(00.00);
                    foreach ($testIds as $subTestId) {
                        if($subTestId != ''){
                            $showSubTest = $SubTests->showSubTestsId($subTestId);
                            foreach ($showSubTest as $rowTest) {
                                $testName = $rowTest['sub_test_name'];
                                $testPrice = $rowTest['price'];

                                
                                $disc   = array_shift($testDiscBck);
                                $amount = array_shift($testAmountBck);

                                if ($slno >1) {
                                    echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 4px 10px; align-items: center;">';
                                }

                                echo '
                                <div class="col-sm-2 ps-4 my-0">
                                            <small>'.$slno.'</small>
                                        </div>
                                        <div class="col-sm-4 my-0">
                                            <small>'.$testName.'</small>
                                        </div>
                                        <div class="col-sm-2">
                                            <small>'.$testPrice.'</small>
                                        </div>
                                        <div class="col-sm-2">
                                            <small>'.$disc.'</small>
                                        </div>
                                        <div class="col-sm-2 text-end my-0">
                                            <small>'.$amount.'</small>
                                        </div>';
                                $slno++;
                                $subTotal = floatval($subTotal + $amount);
                            }
                        }
                    }
                ?>

            </div>
            <!-- </div> -->

            <!-- </div> -->
            <div class="footer">
                <hr calss="my-0" style="height: 1px;opacity: 1.25;">

                <!-- table total calculation -->
                <div class="row my-0">
                    <div class="col-sm-8 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Total Amount:</b></small></p>
                    </div>
                    <div class="col-sm-4 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹<?php echo floatval($subTotal); ?></small></b>
                        </p>
                    </div>

                    <?php
                   if($discountOnTotal != NULL && $discountOnTotal > 0){
                    echo '
                    <div class="col-sm-8 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Less Amount:</b></small></p>
                    </div>
                    <div class="col-sm-4 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹ '.$discountOnTotal.'</small></b>
                        </p>
                    </div>';
                   }

                   if ($dueAmount != NULL && $dueAmount > 0) {
                    echo '
                    <div class="col-sm-8 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Due Amount:</b></small></p>
                    </div>
                    <div class="col-sm-4 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹ '.$dueAmount.'</small></b>
                        </p>
                    </div>';
                    }
                    ?>

                    <div class="col-sm-8 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Paid Amount:</b></small></p>
                    </div>
                    <div class="col-sm-4 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹<?php echo floatval($paidAmount); ?></small></b>
                        </p>
                    </div>
                </div>





                <!-- <div class="row my-0">
                    <div class="col-sm-8 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small><b>Paid Amount:</b></small></p>
                    </div>
                    <div class="col-sm-4 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹<?php echo floatval($paidAmount); ?></small></b>
                        </p>
                    </div>
                </div> -->
                <!--/end table total calculation -->
            <hr style="height: 1px; margin-top: 2px;opacity: 1.25;">
        </div>

    </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
        <a class="btn btn-primary shadow mx-2" href="../../test-appointments.php">Go Back</a>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
    <?php



    ?>
</body>
<script src="<?php echo JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>

</html>
*/
