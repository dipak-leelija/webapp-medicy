<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../_config/sessionCheck.php';//check admin loggedin or not

require_once '../../../php_control/hospital.class.php';
require_once '../../../php_control/doctors.class.php';
require_once '../../../php_control/idsgeneration.class.php';
require_once '../../../php_control/patients.class.php';
require_once '../../../php_control/stockOut.class.php';
require_once '../../../php_control/currentStock.class.php';
require_once '../../../php_control/manufacturer.class.php';


//  INSTANTIATING CLASS
$HelthCare       = new HelthCare();
$Doctors         = new Doctors();
$Patients        = new Patients();
$IdGeneration    = new IdGeneration();
$StockOut        = new StockOut();
$CurrentStock    = new CurrentStock();
$Manufacturur    = new Manufacturer();



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $addedBy        = $_SESSION['employee_username'];

    $invoiceNo = $_POST['invoice-id'];

    $billDAte = $_POST['bill-date'];
    $customerId = $_POST['customer-id'];
    $customerName = $_POST['customer-name'];
    $doctorName = $_POST['final-doctor-name'];
    $paymentMode = $_POST['payment-mode'];

    $itemsCount  = $_POST['total-items'];
    $totalItemsQantity = $_POST['total-qty'];
    $totalGstAmount = $_POST['total-gst'];
    $totalMRP = $_POST['total-mrp'];
    $netPaybleAmount = $_POST['bill-amount'];


    //echo $patientId; 
    if ($customerId != 'Cash Sales') {
        $patientName = $Patients->patientsDisplayByPId($customerId);
        //print_r($patientName);
        foreach ($patientName as $patientName) {
            $patientAge = 'Age: ' . $patientName['age'];
            $patientPhno = 'M: ' . $patientName['phno'];
        }
    }else{
        $customerId = 'Cash Sales';
        $customerName = 'Cash Sales';
    }

    // print_r($_POST);

    echo "<br>Added by : $addedBy<br>";

    echo "<br>Invoice id : $invoiceNo";
    echo "<br>bill date : $billDAte";
    echo "Paticent id check : $customerId<br>";
    echo "<br>customer name check : $customerName<br>";
    echo "Paticen age check : $patientAge<br>";
    echo "patient phone no check : $patientPhno<br>";
    echo "reffered doctor : $doctorName<br>";
    
    echo "payment mode : $paymentMode<br>";
    echo "items count : $itemsCount<br>";
    echo "total qantity count : $totalItemsQantity<br>";
    echo "total gst amount : $totalGstAmount<br>";
    echo "total mrp : $totalMRP<br>";
    echo "total payble amount : $netPaybleAmount<br>";
    // echo "bill discount amount : $disc<br>";

    // echo "<br>======= ARRAYS SECTION ==========<br>";
    // ================ ARRAYS ======================

    $itemId             = $_POST['item-id'];
    $prductId           = $_POST['product-id'];
    $prodName           = $_POST['product-name'];
    $manufId            = $_POST['Manuf'];
    $pharmacyDataId     = $_POST['pharmacy-data-id'];
    $stockOutDataId     = $_POST['stockOut-details-id'];
    $batchNo            = $_POST['batch-no'];
    $weightage          = $_POST['weightage'];
    // $itemWeightage      = $_POST['ItemUnit'];
    // $ItemUnit           = $_POST['ItemPower'];
    $expDate            = $_POST['exp-date'];
    $mrp                = $_POST['mrp'];
    $discParcent        = $_POST['disc'];
    $discPrice          = $_POST['dPrice'];
    $gstparcent         = $_POST['gst'];
    $gstAmountPerItem   = $_POST['gst-amount'];
    $qty                = $_POST['qty'];
    $qtyType            = $_POST['qty-type'];
    $taxable            = $_POST['taxable'];    
    $amount             = $_POST['amount'];

    // $looseStock         = $_POST['lStock'];
    // $looselyPrice       = $_POST['lPrice'];    
    $ptrPerItem         = $_POST['ptr'];    
    $marginPerItem      = $_POST['margin'];

    
    echo "<br><br>================== data arrays ====================== <br>";
    echo "<br>Item courrent stock id : "; print_r($itemId);
    echo "<br>Product Id : "; print_r($prductId);
    echo "<br>Product Name : "; print_r($prodName);
    echo "<br>Manufacturur Id : "; print_r($manufId);
    echo "<br>Pharmacy invoice tabel id : "; print_r($pharmacyDataId);
    echo "<br>Stock out details table id : "; print_r($stockOutDataId);
    echo "<br>Batch No : "; print_r($batchNo);
    echo "<br>Item Pack of : "; print_r($weightage);
    // echo "<br>Item weatage : "; print_r($itemWeightage);
    // echo "<br>Item Unit : "; print_r($ItemUnit);
    echo "<br>Item exp date : "; print_r($expDate);
    echo "<br>MRP : "; print_r($mrp);
    echo "<br>Disc Parcent : "; print_r($discParcent);
    echo "<br>Discout Amount : "; print_r($discPrice);
    echo "<br>GST Parcent : "; print_r($gstparcent);
    echo "<br>Gst amount per item : "; print_r($gstAmountPerItem);
    echo "<br>QANTITTY PER ITEM : "; print_r($qty);
    echo "<br>Qantity type : "; print_r($qtyType);
    echo "<br>Taxable amount per item : "; print_r($taxable);
    echo "<br>Net payble per item : "; print_r($amount);
    // echo "<br>Loose Stock : "; print_r($looseStock);
    // echo "<br>Loose Price : "; print_r($looselyPrice);
    echo "<br>PTR per Item : "; print_r($ptrPerItem);
    echo "<br>Margin amount per item : "; print_r($marginPerItem);
    

    
    

    //========================== STOCK OUT AND SALES EDIT UPDATE AREA ==========================
    if (isset($_POST['update'])) {
    
        $discountAmount = floatval($totalMRP) - floatval($netPaybleAmount);

        // $stockOutUpdate = $StockOut->updateLabBill($invoiceNo, $customerId, $doctorName, $itemsCount, $totalItemsQantity, $totalMRP, $discountAmount, $totalGstAmount, $netPaybleAmount, $paymentMode, $billDAte, $addedBy);
        $stockOutUpdate = true;

        if($stockOutUpdate == true){

            $invoiceIdList = [];
            $pharmacyInvoiceData = $StockOut->stockOutDetailsById($invoiceNo);
            foreach($pharmacyInvoiceData as $pharmacyInvoiceData){
                array_push($invoiceIdList, $pharmacyInvoiceData['id']);
            }
            echo "<br><br><br><br>Pharmacy id list : "; print_r($invoiceIdList);

            $pharmacyIdArraydiff = array_diff($invoiceIdList, $pharmacyDataId);

            echo "<br> Pharmacy id diff list : "; print_r($pharmacyIdArraydiff);

            $stockOutDetailsIdList = [];
            $stockOutDetails = $StockOut->stockOutDetailsDisplayById($invoiceNo);
            foreach($stockOutDetails as $stockOutDetails){
                array_push($stockOutDetailsIdList, $stockOutDetails['id']);
            }
            echo "<br><br><br>Stock out details id list : "; print_r($stockOutDetailsIdList);

            $stockOutDetailsIdArrayDiff = array_diff($stockOutDetailsIdList, $stockOutDataId);

            echo "<br>Stock out details id diff list : "; print_r($stockOutDetailsIdArrayDiff);


            exit;

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
    <title>Medicy Health Care Sales Bill</title>
    <link rel="stylesheet" href="../../../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../../../css/custom/test-bill.css">

</head>


<body>
    <div class="custom-container">
        <div class="custom-body <?php if ($pMode != 'Credit') {
                                    echo "paid-bg";
                                } ?>">
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-1">
                        <img class="float-end" style="height: 55px; width: 58px;" src="../../../images/logo-p.jpg" alt="Medicy">
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
                    $string1 = ' (';
                    $string2 = ')';
                    $looseString = 'L';

                    if ($qtyTp[$i] == 'Pack') {
                        $wholeQty = intval($qty[$i]) / intval($itemWeightage[$i]);
                        $ItemSellQantity = $wholeQty. $string1.$qty[$i] . $looseString.$string2;
                    } elseif ($qtyTp[$i] == 'Loose') {
                        $wholeQty = '';
                        $ItemSellQantity = $wholeQty. $qty[$i] .$string1. $looseString.$string2;
                    } elseif ($qtyTp[$i] == '') {
                        $wholeQty = '';
                        $ItemSellQantity = $wholeQty.$qty[$i];
                    }

                    $perItemTotalSell =  $ItemSellQantity;


                    $manufDetail = $Manufacturur->showManufacturerById($manufId[$i]);
                    $manufSName = $manufDetail[0]['short_name'];


                    if ($slno > 1) {
                        echo '<hr style="width: 98%; border-top: 1px dashed #8c8b8b; margin: 0 10px 0; align-items: center;">';
                    }

                    echo '<div class="col-sm-1 text-center">
                                    <small>' . $slno . '</small>
                            </div>
                                <div class="col-sm-2 ">
                                    <small>' . $prodName[$i] . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $manufSName . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $batchNo[$i] . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $weightage[$i] . '</small>
                                </div>
                                <div class="col-sm-1">
                                    <small>' . $expDate[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $perItemTotalSell . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $mrp[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $discountPercent[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $gstparcent[$i] . '</small>
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
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Net Price :</small></p>
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
<script src="../../../js/bootstrap-js-5/bootstrap.js"></script>

</html>