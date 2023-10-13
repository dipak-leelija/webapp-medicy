<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once dirname(__DIR__).'/config/constant.php';
require_once CLASS_DIR.'sub-test.class.php';
require_once CLASS_DIR.'doctors.class.php';
require_once CLASS_DIR.'labBilling.class.php';
require_once CLASS_DIR.'labBillDetails.class.php';
require_once CLASS_DIR.'hospital.class.php';
require_once CLASS_DIR.'patients.class.php';



//  INSTANTIATING CLASS
$SubTests        = new SubTests();
$Doctors         = new Doctors();
$Patients        = new Patients();
$HelthCare       = new HelthCare();
$LabBilling      = new LabBilling();
$LabBillDetails  = new LabBillDetails();




//variable declreation
// $totalAmount = 0;


if (isset($_POST['bill-generate'])) {
    // echo '<pre>';
    // print_r($_POST);
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

    echo $docId ;
    echo $referedDocName;

    // echo $payable.'<br>';
    // echo $paidAmount.'<br>';
    // echo $dueAmount.'<br>';
    // echo $discountOnTotal.'<br>';
    // echo $status.'<br>';
    // exit;


##################################################################
###################### Patient Visit Update ######################
##################################################################
$showPatient = $Patients->patientsDisplayByPId($patientId);
foreach ($showPatient as $rowPatient) {
    $labVisited = $rowPatient['lab_visited'];
    // echo $labVisited;
}

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
    $LabBillDisplay = $LabBilling->LabBillDisplay();
    if ($LabBillDisplay == NULL) {
        $billId = 1;
    }else{
        foreach ($LabBillDisplay as $rowLabBill) {
            $billId = $rowLabBill['bill_id'];
            $billId = $billId+1;
        }
    }
    if ($billId < 10) {
        $billId = '0'.$billId;
    }
    ############ End Of Bill ID / Invoice Id Generagtion #########


    ################ Doctor Selection ###############

    if ($docId == 'Self') {
        $referedDoc = $docId;
        $doctorName = 'Self';
        $doctorReg  = NULL;
    }else{
        if ($docId != NULL) {
            //function calling
            $showDoctorById = $Doctors->showDoctorById($docId);
            foreach($showDoctorById as $rowDoctor){
                $referedDoc = $docId;
                $doctorName = $rowDoctor['doctor_name'];
                $doctorReg  = $rowDoctor['doctor_reg_no'];
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
    // $cgstPercentage = 5;
    // $cgst = $cgstPercentage/100*$totalBill;
    
    // $sgstPercentage = 5;
    // $sgst = $sgstPercentage/100*$totalBill;

    // CGST & SGST Generation
    // $cgstPercentage = 5;
    $cgst = 0;
    
    // $sgstPercentage = 5;
    $sgst = 0;
    ########## End of CGST & SGST Generation ##########

    $totalAfterDiscount = $payable;


    ######## Billing Date #######
    date_default_timezone_set('Asia/Kolkata');
    $billingDate = date("d-m-Y :: G:i:s");
    #### End of Billing Date ####

    $addLabBill = $LabBilling->addLabBill($billId, $billingDate, $patientId, $referedDoc, $testDate, $totalAmount, $discountOnTotal, $totalAfterDiscount, $cgst, $sgst, $paidAmount, $dueAmount, $status );

    if ($addLabBill) {
        echo "<script>alert('Bill Generated.');</script>";
    }else {
        echo "<script>alert('Bill Addition Failed!');</script>";
    }

    /* ========================= Bill Insertion End ========================= */


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


        $addBillDetails = $LabBillDetails->addLabBillDetails($billId, $billingDate, $testDate, $testId, $testPrice, $percentageOfDiscount, $priceAfterDiscount);
    }
    /* ========================= Bill Details Insertion End ========================= */





}else{
        echo "<script>alert('Patient Visiting Not Updated!!, Something is Wrong!');</script>";
}
/* ============================ End ============================ */
    




}else {
    header("Location: lab-billing.php");
    exit;
}


$showhelthCare = $HelthCare->showhelthCare();
foreach ($showhelthCare as $rowhelthCare) {
    $healthCareName     = $rowhelthCare['hospital_name'];
    $healthCareAddress1 = $rowhelthCare['address_1'];
    $healthCareAddress2 = $rowhelthCare['address_2'];
    $healthCareCity     = $rowhelthCare['city'];
    $healthCarePIN      = $rowhelthCare['pin'];
    $healthCarePhno     = $rowhelthCare['hospital_phno'];
    $healthCareApntbkNo = $rowhelthCare['appointment_help_line'];

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicy Health Care Lab Test Bill</title>
    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../css/custom/test-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if($payable == $paidAmount){ echo "paid-bg";} ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="../images/logo-p.jpg"
                            alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1.', '.$healthCareAddress2.', '.$healthCareCity.', '.$healthCarePIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: '.$healthCarePhno.', '.$healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-primary">
                        <p class="my-0">Invoice</p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id: <?php echo $billId; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Date:
                                <?php echo $billingDate;?></small></p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">
            <div class="row my-0">
                <div class="col-sm-6 my-0">
                    <p style="margin-top: -3px; margin-bottom: 0px;"><small><b>Patient: </b>
                            <?php echo $patientName.', Age: '.$patientAge; ?></small></p>
                    <p style="margin-top: -5px; margin-bottom: 0px;"><small>M:
                            <?php echo $patientPhno; echo ', Test date: '.$testDate;?></small></p>
                </div>
                <div class="col-sm-6 my-0">
                    <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                            <?php echo $doctorName; ?></small></p>
                    <p class="text-end" style="margin-top: -5px; margin-bottom: 0px;">
                        <small><?php if($doctorReg != NULL){echo 'Reg: '.$doctorReg; } ?></small>
                    </p>
                </div>

            </div>
            <hr class="my-0" style="height:1px;">
            
            <div class="row">
                <!-- table heading -->
                <div class="col-sm-2 text-center">
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
            
            <hr class="my-0" style="height:1px;">

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
                                    echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                                }

                                echo '
                                <div class="col-sm-2 text-center my-0">
                                            <small>'.$slno.'</small>
                                        </div>
                                        <div class="col-sm-4 my-0">
                                            <small>'.$testName.'</small>
                                        </div>
                                        <div class="col-sm-2">
                                            <small>'.$testPrice.'</b></small>
                                        </div>
                                        <div class="col-sm-2">
                                            <small><b>'.$disc.'</b></small>
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
                <hr calss="my-0" style="height: 1px;">

                <!-- table total calculation -->
                <div class="row my-0">
                    <div class="col-sm-8 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total Amount:</small></p>
                    </div>
                    <div class="col-sm-4 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹<?php echo floatval($subTotal); ?></small></b>
                        </p>
                    </div>
                </div>

                <?php
                if($discountOnTotal != NULL && $discountOnTotal > 0){
                    echo '<div class="row my-0">
                    <div class="col-sm-8 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Less Amount:</small></p>
                    </div>
                    <div class="col-sm-4 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹ '.$discountOnTotal.'</small></b>
                        </p>
                    </div>
                </div>';
                }

                if ($dueAmount != NULL && $dueAmount > 0) {
                    echo '<div class="row my-0">
                    <div class="col-sm-8 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Due Amount:</small></p>
                    </div>
                    <div class="col-sm-4 mt-0 mb-1 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹ '.$dueAmount.'</small></b>
                        </p>
                    </div>
                </div>';
                }
                ?>
                
                

                <div class="row my-0">
                    <div class="col-sm-8 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Paid Amount:</small></p>
                    </div>
                    <div class="col-sm-4 mb-3 text-end">
                        <p style="margin-top: -5px; margin-bottom: 0px;">
                            <small><b>₹<?php echo floatval($paidAmount); ?></small></b>
                        </p>
                    </div>
                </div>
                <!--/end table total calculation -->

            </div>
            <hr style="height: 1px; margin-top: 2px;">
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
        <a class="btn btn-primary shadow mx-2" href="test-appointments.php">Go Back</a>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
    <?php



    ?>
</body>
<script src="../js/bootstrap-js-5/bootstrap.js"></script>

</html>