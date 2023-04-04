<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '_config/sessionCheck.php'; //check admin loggedin or not


require_once '../php_control/hospital.class.php';
require_once '../php_control/doctors.class.php';
require_once '../php_control/idsgeneration.class.php';
require_once '../php_control/patients.class.php';
require_once '../php_control/stockOut.class.php';
require_once '../php_control/currentStock.class.php';






//  INSTANTIATING CLASS
$HelthCare       = new HelthCare();
$Doctors         = new Doctors();
$Patients        = new Patients();
$IdGeneration    = new IdGeneration();
$StockOut        = new StockOut();
$CurrentStock    = new CurrentStock();



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $qtyTypes       = $_POST['qty-types'];
    //print_r($qtyTypes);
    $pMode          = $_POST['payment-mode'];
    //echo $pMode;
    $customerName   = $_POST['customer-name'];
    $patientId     = $_POST['customer-id'];

    $patientAge = '';
    $patientPhno = '';

    //echo $patientId; 
    if ($patientId != 'Cash Sales') {
        $patientName = $Patients->patientsDisplayByPId($patientId);
        //print_r($patientName);
        $patientAge = 'Age: ' . $patientName[0]['age'];
        $patientPhno = 'M: ' . $patientName[0]['phno'];
    }


    $reffby         = $_POST['doctor-name'];
    $billdate       = $_POST['bill-date'];
    $totalItems     = $_POST['total-items'];
    $totalQty       = $_POST['total-qty'];
    $totalGSt       = $_POST['total-gst'];
    $totalMrp       = $_POST['total-mrp'];
    $billAmout      = $_POST['bill-amount'];

    $disc = $totalMrp - $billAmout;

    $addedBy        = $_SESSION['employee_username'];
    $addedOn        = date("Y/m/d");

    $prductId    = $_POST['product-id'];
    // print_r($prductId); echo "<br><br>";
    $batchNo     = $_POST['batch-no'];
    // print_r($batchNo); echo "<br><br>"; 
    $qty = $_POST['qty'];
    // print_r($qty); echo "<br><br>"; 
    $qtyTypes = $_POST['qty-types'];
    // print_r($qtyTypes); echo "<br><br>"; 
    $discount = $_POST['disc'];
    // print_r($discount); echo "<br><br>"; 
    $gst = $_POST['gst'];
    // print_r($gst);
    // echo "<br><br>";
    $amount = $_POST['amount'];
    // print_r($amount);
    // echo "<br><br>";



    if (isset($_POST['submit'])) {
        $invoiceId = $IdGeneration->pharmecyInvoiceId();

        $stockOut = $StockOut->addStockOut($invoiceId, $patientId, $reffby, $totalItems, $totalQty, $totalMrp, $disc, $totalGSt, $billAmout, $pMode, $billdate, $addedBy);

        //----------------------------------------------------------------RD-----------

        for ($i = 0; $i < count($prductId); $i++) {

            $productDetails = $CurrentStock->showCurrentStocByProductIdandBatchNo($prductId[$i], $batchNo[$i]);

            // print_r($productDetails);
            // echo "<br><br>";

            $productID = $productDetails[0]['product_id'];
            $BatchNo = $productDetails[0]['batch_no'];
            $ExpairyDate = $productDetails[0]['exp_date'];
            $Weightage = $productDetails[0]['weightage'];
            $Unit = $productDetails[0]['unit'];
            $MRP = $productDetails[0]['mrp'];
            $PTR = $productDetails[0]['ptr'];
            $GST = $productDetails[0]['gst'];
            $LooselyPrice = $productDetails[0]['loosely_price'];

            if ($qtyTypes[$i] == 'Loose') {
                $margin = (floatval($amount[$i]) - (((floatval($PTR)) / (floatval($Weightage))) * (intval($qty[$i]))));

                if ($Unit == 'tab' || $Unit == 'cap' && $qtyTypes[$i] == 'Pack') {
                    $looselyCount = $qty[$i];
                    $qty[$i] = "0";
                }else{
                    $looselyCount = "0";
                }
            
            } else {
                $margin = (floatval($amount[$i]) - ((floatval($PTR)) * (intval($qty[$i]))));

                if ($Unit == 'tab' || $Unit == 'cap' && $qtyTypes[$i] == 'Pack') {
                    $looselyCount = (intval($Weightage) * intval($qty[$i]));
                } else {
                    $looselyCount = "0";
                }
            }

            // echo "<br>";
            // print_r($invoiceId);
            // echo "<br>";
            // print_r($productID);
            // echo "<br>";
            // print_r($BatchNo);
            // echo "<br>";
            // print_r($ExpairyDate);
            // echo "<br>";
            // print_r($Weightage);
            // echo "<br>";
            // print_r($Unit);
            // echo "<br>";
            // print_r($qty[$i]);
            // echo "<br>";
            // print_r($looselyCount);
            // echo "<br>";
            // print_r($MRP);
            // echo "<br>";
            // print_r($PTR);
            // echo "<br>";
            // print_r($discount[$i]);
            // echo "<br>";
            // print_r($GST);
            // echo "<br>";
            // print_r($margin);
            // echo "<br>";
            // print_r($amount[$i]);
            // echo "<br>";
            // print_r($addedBy);
            // echo "<br>";
            // print_r($addedOn);
            // echo "<br>";
            

            if ($stockOut === true) {

                $stockOutDetails = $StockOut->addStockOutDetails($invoiceId, $productID, $BatchNo, $ExpairyDate, $Weightage, $Unit, $qty[$i], $looselyCount, $MRP, $PTR, $discount[$i], $GST, $margin, $amount[$i], $addedBy, $addedOn);
            }
        }
    }
    //------------------------------------------------------------------------------

    if (isset($_POST['update'])) {
        $invoiceId = $_POST['invoice-id'];

        $stockOut = $StockOut->updateLabBill($invoiceId, $patientId, $reffby, $totalItems, $totalQty, $totalMrp, $disc, $totalGSt, $billAmout, $pMode, $billdate, $addedBy);
    }
    // echo var_dump($stockOut);
    if ($stockOut == TRUE) {
        //array
        $itemIds    = $_POST['product-id'];
        $itemNames  = $_POST['product-name'];
        $Manufs     = $_POST['Manuf'];
        $weatage    = $_POST['weightage'];
        $batchNo    = $_POST['batch-no'];
        $expdates   = $_POST['exp-date'];
        $mrp        = $_POST['mrp'];
        $qtys       = $_POST['qty']; // for printing invoice
        $qty         = $_POST['qty']; // for inserting details
        $qtyTp   = $_POST['qty-types'];
        //print_r($qtyTp);
        $discs      = $_POST['disc'];
        $dPrices    = $_POST['dPrice'];
        $gst        = $_POST['gst'];
        $netGst     = $_POST['netGst'];
        $amounts    = $_POST['amount'];

        foreach ($itemIds as $itemId) {
            $uBatchNo   = array_shift($_POST['batch-no']);
            $uWeightage = array_shift($_POST['weightage']);
            $nWeightage = (float) filter_var($uWeightage, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            // echo $uWeightage;

            $inStock     = $CurrentStock->checkStock($itemId, $uBatchNo);
            // print_r($inStock);
            // if (count($inStock) != 0) {

            $getQuantity = $inStock[0]['qty'];
            $getLCount   =  $inStock[0]['loosely_count'];
            // }
            $qtyType        = array_shift($qtyTypes);

            $pQty         = array_shift($qty);

            if (isset($_POST['submit'])) {
                if ($qtyType == "Loose") {
                    $newLCount     = $getLCount - $pQty;
                    $lCount        = $pQty;
                    // $getLCount / $nWeightage;
                    $newQuantity   = intval($newLCount / $nWeightage);
                    $pQty           = 0;
                } elseif ($qtyType == "Pack") {
                    $newQuantity  = $getQuantity - $pQty;
                    $newLCount    = $getLCount - ($pQty * $nWeightage);
                    $lCount       = 0;
                } else {
                    $newQuantity  = $getQuantity - $pQty;
                    $newLCount    = 0;
                    $lCount       = 0;
                }
                // echo $newLCount
                $CurrentStock->updateStock($itemId, $uBatchNo, $newQuantity, $newLCount);

                $StockOut->addPharmacyBillDetails($invoiceId, $itemId, array_shift($_POST['product-name']), $uBatchNo, $uWeightage, array_shift($_POST['exp-date']), $pQty, $lCount, array_shift($_POST['mrp']), array_shift($_POST['disc']), array_shift($_POST['dPrice']), array_shift($_POST['gst']), array_shift($_POST['netGst']), array_shift($_POST['amount']), $addedBy);
            }

            // if (isset($_POST['update'])) {
            //     $oldBill = $StockOut->stockOutDetailsById($invoiceId);
            //     print_r($oldBill);

            //     $oldQty       = $oldBill[0]['qty'];
            //     $oldLCount    = $oldBill[0]['loosely_count'];
            //     $existWeatage = $oldBill[0]['weatage'];

            //     if (str_contains($existWeatage, 'tab')) {
            //         $oldWeatage = 'tab';
            //     }elseif(str_contains($existWeatage, 'cap')){
            //         $oldWeatage = 'cap';
            //     }else {
            //         $oldWeatage = '';
            //     }

            //     if ($oldQty == 0) {
            //         $qtyType = 'Loose';
            //     }elseif ($oldLCount == 0 && $oldQty > 0) {


            //         if ($oldWeatage == 'tab' || $oldWeatage == 'cap') {
            //             $qtyType = '';
            //             $qtyType = 'Pack';
            //         }

            //         if ($oldWeatage != 'tab' && $oldWeatage != 'cap') {
            //             $qtyType = '';
            //         }
            //     }

            //         if ($qtyType == "Loose") {

            //             if ($oldLCount > $pQty) { 
            //                 $updateQty = $oldLCount - $pQty;
            //                 $newLCount = $getLCount + $updateQty;
            //             }
            //             elseif ($oldLCount < $pQty) {
            //                 $updateQty = $pQty - $oldLCount;
            //                 $newLCount = $getLCount - $updateQty;
            //             }else{
            //                 $newLCount = $getLCount;
            //             }

            //             $newQuantity   = intval($newLCount / $nWeightage);
            //             $lCount         = $pQty;
            //             $pQty           = 0;

            //         }elseif($qtyType == "Pack"){

            //             if ($oldQty > $pQty) { 
            //                 $updateQty = $oldQty - $pQty;
            //                 $newQuantity = $getQuantity + $updateQty;
            //                 $newLCount    = $getLCount + ($updateQty * $nWeightage);
            //             }
            //             elseif ($oldQty < $pQty) {
            //                 $updateQty = $pQty - $oldQty;
            //                 $newQuantity = $getQuantity - $updateQty;
            //                 $newLCount   = $getLCount - ($updateQty * $nWeightage);

            //             }else{
            //                 $newQuantity = $getQuantity;
            //                 $newLCount   = $getLCount;
            //             }

            //             $lCount       = 0;

            //         }else{



            //             if ($oldQty > $pQty) { 
            //                 $updateQty = $oldQty - $pQty;
            //                 $newQuantity = $getQuantity + $updateQty;
            //             }
            //             elseif ($oldQty < $pQty) {
            //                 $updateQty = $pQty - $oldQty;
            //                 $newQuantity = $getQuantity - $updateQty;
            //             }else{
            //                 $newQuantity = $getQuantity;
            //             }

            //             $newLCount    = 0;
            //             $lCount       = 0;
            //         }

            //         $CurrentStock->updateStock($itemId, $uBatchNo, $newQuantity, $newLCount);


            //     $StockOut->updateBillDetail($invoiceId, $itemId, array_shift($_POST['product-name']), $uBatchNo, $uWeightage, array_shift($_POST['exp-date']), $pQty, $lCount, array_shift($_POST['mrp']), array_shift($_POST['disc']), array_shift($_POST['dPrice']), array_shift($_POST['gst']), array_shift($_POST['netGst']), array_shift($_POST['amount']), $addedBy);
            // }
        }
    }
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
        <div class="custom-body <?php if ($pMode != 'Credit') {
                                    echo "paid-bg";
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="../images/logo-p.jpg" alt="Medicy">
                    </div>
                    <div class="col-sm-8">
                        <h4 class="text-start my-0"><?php echo $healthCareName; ?></h4>
                        <p class="text-start" style="margin-top: -5px; margin-bottom: 0px;">
                            <small><?php echo $healthCareAddress1 . ', ' . $healthCareAddress2 . ', ' . $healthCareCity . ', ' . $healthCarePIN; ?></small>
                        </p>
                        <p class="text-start" style="margin-top: -8px; margin-bottom: 0px;">
                            <small><?php echo 'M: ' . $healthCarePhno . ', ' . $healthCareApntbkNo; ?></small>
                        </p>

                    </div>
                    <div class="col-sm-3 border-start border-dark">
                        <p class="my-0"><b>Invoice</b></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Bill id:
                                <?php echo $invoiceId; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Payment: <?php echo $pMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Date: <?php echo $billdate; ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-0" style="height:1px; background: #000000; border: #000000;">
            <div class="row my-0">
                <div class="col-sm-6 my-0">
                    <p style="margin-top: -3px; margin-bottom: 0px;">
                        <small><b>Patient: </b> <?php echo $customerName . ' ' . $patientAge . ' ' . $patientPhno; ?>
                        </small>
                    </p>

                </div>
                <div class="col-sm-6 my-0">
                    <p class="text-end" style="margin-top: -3px; margin-bottom: 0px;"><small><b>Refered By:</b>
                            <?php echo $reffby; ?></small></p>
                    <p class="text-end" style="margin-top: -5px; margin-bottom: 0px;">
                        <small><?php //if($doctorReg != NULL){echo 'Reg: '.$doctorReg; } 
                                ?></small>
                    </p>
                </div>

            </div>
            <hr class="my-0" style="height:1px;">

            <div class="row">
                <!-- table heading -->
                <div class="col-sm-1 text-center">
                    <small><b>SL.</b></small>
                </div>
                <div class="col-sm-2 ">
                    <small><b>Name</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Manuf.</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Packing</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Batch</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Exp.</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>QTY</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>MRP</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Disc(%)</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>GST(%)</b></small>
                </div>
                <div class="col-sm-1 text-end">
                    <small><b>Amount</b></small>
                </div>
                <!--/end table heading -->
            </div>

            <hr class="my-0" style="height:1px;">

            <div class="row">
                <?php
                $slno = 0;
                $subTotal = floatval(00.00);
                $itemIds    = $_POST['product-id'];
                $count = count($itemIds);
                for ($i = 0; $i < $count; $i++) {
                    $slno++;

                    // echo "<br>"; print_r($qtyTp);
                    // echo "<br>"; print_r($qtys);
                    // echo "<br>"; print_r($mrp);
                    if ($qtyTp[$i] == 'Loose') {
                        $type = " (L)";
                        $itemQTY = $qtys[$i] . $type;
                    } else {
                        $itemQTY = $qtys[$i];
                    }

                    $itemQty  = $qtys[$i];
                    $mrpOnQty = $mrp[$i];
                    $mrpOnQty = $mrpOnQty * $itemQty;

                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }

                    echo '<div class="col-sm-1 text-center">
                                    <small>' . $slno . '</small>
                            </div>
                                <div class="col-sm-2 ">
                                    <small>' . substr(array_shift($itemNames), 0, 15) . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . strtoupper(substr(array_shift($Manufs), 0, 6)) . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . array_shift($weatage) . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . array_shift($batchNo) . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . array_shift($expdates) . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $itemQTY . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $mrpOnQty . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . array_shift($discs) . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . array_shift($gst) . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . array_shift($amounts) . '</small>
                                </div>';

                    // $subTotal = floatval($subTotal + $amount);
                }
                ?>

            </div>
            <!-- </div> -->

            <!-- </div> -->
            <div class="footer">
                <hr calss="my-0" style="height: 1px;">

                <!-- table total calculation -->
                <div class="row my-0">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>CGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $totalGSt / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $totalGSt / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo floatval($totalGSt); ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total MRP:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small><b>₹<?php echo floatval($totalMrp); ?></b></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Net:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small><b>₹<?php echo floatval($billAmout); ?></b></small>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>You Saved:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $totalMrp - $billAmout; ?></small>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>



            </div>
            <hr style="height: 1px; margin-top: 2px;">
        </div>
    </div>
    <div class="justify-content-center print-sec d-flex my-5">
        <!-- <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button> -->
        <button class="btn btn-primary shadow mx-2" onclick="history.back()">Go Back</button>
        <button class="btn btn-primary shadow mx-2" onclick="window.print()">Print Bill</button>
    </div>
    </div>
    <?php



    ?>
</body>
<script src="../js/bootstrap-js-5/bootstrap.js"></script>

</html>