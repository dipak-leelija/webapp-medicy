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
require_once '../php_control/manufacturer.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HelthCare();
$Doctors         = new Doctors();
$Patients        = new Patients();
$IdGeneration    = new IdGeneration();
$StockOut        = new StockOut();
$CurrentStock    = new CurrentStock();
$Manufacturur    = new Manufacturer();



if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $customerName   = $_POST['customer-name'];
    $patientId     = $_POST['customer-id'];
    $patientAge = '';
    $patientPhno = '';

    //echo $patientId; 
    if ($patientId != 'Cash Sales') {
        $patientName = $Patients->patientsDisplayByPId($patientId);
        //print_r($patientName);
        foreach ($patientName as $patientName) {
            $patientAge = 'Age: ' . $patientName['age'];
            $patientPhno = 'M: ' . $patientName['phno'];
        }
    }

    $reffby         = $_POST['doctor-name'];
    $billdate       = $_POST['bill-date'];
    $pMode          = $_POST['payment-mode'];

    $totalItems     = $_POST['total-items'];
    $totalQty       = $_POST['total-qty'];
    $totalGSt       = $_POST['total-gst'];
    $totalMrp       = $_POST['total-mrp'];
    $billAmout      = $_POST['bill-amount'];

    $disc = $totalMrp - $billAmout;

    $addedBy        = $_SESSION['employee_username'];
    $addedOn        = date("Y/m/d");

    echo "<br>customer name check : $customerName<br>";
    echo "Paticent id check : $patientId<br>";
    echo "Paticen age check : $patientAge<br>";
    echo "patient phone no check : $patientPhno<br>";
    echo "reffered doctor : $reffby<br>";
    echo "bill date : $billdate<br>";
    echo "payment mode : $pMode<br>";
    echo "total items count : $totalItems<br>";
    echo "total qantity count : $totalQty<br>";
    echo "total gst amount : $totalGSt<br>";
    echo "total mrp amount : $totalMrp<br>";
    echo "total payble amount : $billAmout<br>";
    echo "bill discount amount : $disc<br>";

    // echo "<br>======= ARRAYS SECTION ==========<br>";
    // ================ ARRAYS ======================

    $prductId           = $_POST['product-id'];
    $prodName           = $_POST['product-name'];
    $manufId            = $_POST['ManufId'];
    $manufName          = $_POST['ManufName'];
    $batchNo            = $_POST['batch-no'];
    $weightage          = $_POST['weightage'];
    $ItemWeightage      = $_POST['ItemWeightage'];
    $ItemUnit      = $_POST['ItemUnit'];
    $expDate            = $_POST['exp-date'];
    $mrp                = $_POST['mrp'];
    $ptr                = $_POST['itemPtr'];
    $qtyTp              = $_POST['qtyTp'];
    $qty                = $_POST['qty'];
    $discountPercent    = $_POST['discPercent'];
    $marginPerItem      = $_POST['marginAmount'];
    $taxable            = $_POST['taxable'];
    $gstparcent         = $_POST['gst'];
    $gstAmountPerItem   = $_POST['gstVal'];
    $amount             = $_POST['amount'];

                echo "<br><br>product ids array : "; print_r($prductId);
                echo "<br>Product name array : "; print_r($prodName);
                echo "<br>Manufacturur id array : "; print_r($manufId);
                echo "<br>Manufacturur name array : "; print_r($manufName);
                echo "<br>BATCH NUMBER array : "; print_r($batchNo);
                echo "<br>items WEIGHTAGE arrya : "; print_r($weightage);
                echo "<br>items item weightage array : "; print_r($ItemWeightage);
                echo "<br>items item unit : "; print_r($ItemUnit);
                echo "<br>items expiary date : "; print_r($expDate);
                echo "<br>items MRP : "; print_r($mrp );
                echo "<br>items PTR : "; print_r($ptr);
                echo "<br>PER ITEM QANTITY TYPES : "; print_r($qtyTp);
                echo "<br>PER ITEM QANTITY: "; print_r($qty);
                echo "<br>PER ITEM DISCOUNT PERCETN : "; print_r($discountPercent);
                echo "<br>PER ITEM MARGINE AMOUNT : "; print_r($marginPerItem);
                echo "<br>PER ITEM TAXABLE AMOUNT : "; print_r($taxable);
                echo "<br>PER ITEM GST PARCENT : "; print_r($gstparcent);
                echo "<br>PER ITEM GST AMOUNT : "; print_r($gstAmountPerItem );
                echo "<br>PER ITEM PAYBLE AMOUNT array : "; print_r($amount);
exit;
    // ========================== checking end ===================================

    // ===================== STOCK OUT AND SALES ITEM BILL GENERATION AREA =========================
    if (isset($_POST['submit'])) {
        $invoiceId = $IdGeneration->pharmecyInvoiceId();

        // $stockOut = $StockOut->addStockOut($invoiceId, $patientId, $reffby, $totalItems, $totalQty, $totalMrp, $disc, $totalGSt, $billAmout, $pMode, $billdate, $addedBy);
        $stockOut = true;

        if ($stockOut === true) {
            for ($i = 0; $i < count($prductId); $i++) {
                // echo "<br>qantity types : $qtyTypes[$i]";
                $ItemUnit = preg_replace("/[^a-z]/", '', $weightage[$i]);
                $ItemWeightage = preg_replace("/[^0-9]/", '', $weightage[$i]);

                if ($qtyTypes[$i] == 'Loose' || $qtyTypes[$i] == 'Pack') {
                    
                    if ($ItemUnit == 'tab' || $ItemUnit == 'cap') {
                        if ($qtyTypes[$i] == 'Loose') {
                            // echo "$qtyTypes[$i]";
                            $looselyCount = $qty[$i];
                            $itemPerchaeQty = 0;   
                        }

                        if ($qtyTypes[$i] == 'Pack') {
                            $Count = $qty[$i];
                            $looselyCount = intval($Count) * $ItemWeightage;
                            $itemPerchaeQty = $Count;
                        }
                    }
                } else {
                    $looselyCount = 0;
                    $itemPerchaeQty = $qty[$i];

                    // echo "<br>Other block loose count : $looselyCount";
                    // echo "<br>Other block whole count : $itemPerchaeQty";
                }

                // echo "<br><br>FINAL check QUANTITY per item : $itemPerchaeQty";
                // echo "<br>FINAL check loose count per item : $looselyCount";

                $productDetails = $CurrentStock->showCurrentStocByProductIdandBatchNo($prductId[$i], $batchNo[$i]);
                // echo "<br><br> CURRENT STOCK ITEM DETAILS : ";
                // print_r($productDetails);

                foreach ($productDetails as $itemData) {
                    $itemId = $itemData['id'];
                    $productID = $itemData['product_id'];
                    $BatchNo = $itemData['batch_no'];
                    $ExpairyDate = $itemData['exp_date'];
                    $Weightage = $itemData['weightage'];
                    $Unit = $itemData['unit'];
                    $MRP = $itemData['mrp'];
                    $PTR = $itemData['ptr'];
                    $GST = $itemData['gst'];
                    $LooselyPrice = $itemData['loosely_price'];
                    $itemQty = $itemData['qty'];
                    $itemLooseQty = $itemData['loosely_count'];
                }

                // echo "<br><br>CURRENT STOCK ITEM DATA<br>";
                // echo "<br>Item id : $itemId";
                // echo "<br>Product ID : $productID";
                // echo "<br>Item current stock qantity : $itemQty";
                // echo "<br>Item current stock Loose qantity : $itemLooseQty";


                // $stockOutDetails = $StockOut->addStockOutDetails($invoiceId, $prductId[$i], $batchNo[$i], $expDate[$i], $ItemWeightage, $ItemUnit, $itemPerchaeQty, $looselyCount, $mrp[$i], $ptr[$i], $discount[$i], $gst[$i], $marginPerItem[$i], $amount[$i], $addedBy, $addedOn);

                // echo "<br><br>product ids array : $prductId[$i]";
                // echo "<br>Product name array : $prodName[$i]";
                // echo "<br>Manufacturur array : $manuf[$i]";
                // echo "<br>BATCH NUMBER array : $batchNo[$i]";
                // echo "<br>items WEIGHTAGE arrya : $weightage[$i]";
                // echo "<br>items exp date array : $expDate[$i]";
                // echo "<br>items MRP array : $mrp[$i]";
                // echo "<br>items PTR array : $ptr[$i]";
                // // echo "<br><br> qtyT arrya check : $qtyT[$i]";;
                // echo "<br>QANTITY array : $qty[$i]";
                // echo "<br>QUANTITY TYPE array : $qtyTypes[$i]";
                // echo "<br>PER ITEM DISCOUNT PERCENT array : $discount[$i]";
                // echo "<br>PER ITEM MARGINE AMOUNT array : $marginPerItem[$i]";
                // echo "<br>PER ITEM TAXABLE array : $taxable[$i]";
                // echo "<br>PER ITEM GST PERCENT array : $gst[$i]";
                // echo "<br>PER ITEM GST AMOUNT array : $gstPerItem[$i]";
                // echo "<br>PER ITEM PAYBLE AMOUNT array : $amount[$i]";

                // echo "<br><br>";
                // echo "<br>extra check invoice id : $invoiceId";
                // echo "<br>extra check item id : $itemId";
                // echo "<br>extra check loose count : $looselyCount";

                // $StockOut->addPharmacyBillDetails($invoiceId, $itemId, array_shift($_POST['product-name']), array_shift($_POST['batch-no']), array_shift($_POST['weightage']), array_shift($_POST['exp-date']), $itemPerchaeQty, $looselyCount, array_shift($_POST['mrp']), array_shift($_POST['disc']), array_shift($_POST['taxable']), array_shift($_POST['gst']), array_shift($_POST['gstVal']), array_shift($_POST['amount']), $addedBy);

                // =========== AFTER SELL CURREN STOCK CALCULATION AND UPDATE AREA ============= 

                if ($qtyTypes[$i] == 'Loose') {
                    $updatedQty = $itemQty - $itemPerchaeQty;
                    $updatedLooseCount     = $itemLooseQty - $looselyCount;

                    $UpdatedNewQuantity   = intval($updatedLooseCount / $ItemWeightage);
                } elseif ($qtyTypes[$i] == "Pack") {
                    $updatedQty = $itemQty - $itemPerchaeQty;
                    $updatedLooseCount     = $itemLooseQty - intval($itemPerchaeQty * $ItemWeightage);

                    $UpdatedNewQuantity   = intval($updatedLooseCount / $ItemWeightage);
                } else {
                    $updatedQty = $itemQty - $itemPerchaeQty;
                    $UpdatedNewQuantity = $updatedQty;
                    $updatedLooseCount = 0;
                }

                // echo "<br><br>UPDATED CURRENT STOCK QTY : $UpdatedNewQuantity";
                // echo "<br>UPDATED CURRENT LOOSE QTY : $updatedLooseCount";

                // $CurrentStock->updateStock($itemId, $batchNo[$i], $UpdatedNewQuantity, $updatedLooseCount);
            }
        }
    }

    //========================== STOCK OUT AND SALES EDIT UPDATE AREA ==========================
    if (isset($_POST['update'])) {
        $invoiceId = $_POST['invoice-id'];

        $stockOutUpdate = $StockOut->updateLabBill($invoiceId, $patientId, $reffby, $totalItems, $totalQty, $totalMrp, $disc, $totalGSt, $billAmout, $pMode, $billdate, $addedBy);
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
    <title>Medicy Health Care Sales Bill</title>
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
                    <small><b>Batch</b></small>
                </div>
                <div class="col-sm-1">
                    <small><b>Packing</b></small>
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

                    // echo "<br>qantity types : $qtyTp[$i]";

                    if ($qtyTypes[$i] == 'Loose') {
                        $type = " (L)";
                        $itemQTY = $qty[$i] . $type;
                    } else if ($qtyTypes[$i] == 'Pack'){
                        $itemQTY = $qty[$i];
                    } else if ($qtyTypes[$i] == null){
                        $itemQTY = $qty[$i];
                    }

                    // echo "<br>qantity : $itemQTY";

                    $itemQty  = $qty[$i];
                    $mrpOnQty = $mrp[$i];
                    $mrpOnQty = $mrpOnQty * $itemQty;

                    // echo "<br>qantity : $itemQty";

                    $manufDetail = $Manufacturur->showManufacturerById($manuf[$i]);
                    $manufSName = $manufDetail[0]['short_name'];
                 

                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }

                    echo '<div class="col-sm-1 text-center">
                                    <small>' . $slno . '</small>
                            </div>
                                <div class="col-sm-2 ">
                                    <small>' . $prodName[$i]. '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $manufSName . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $batchNo[$i]. '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $weightage[$i]. '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $expDate[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $itemQTY . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $mrp[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $discount[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $gst[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $amount[$i] . '</small>
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