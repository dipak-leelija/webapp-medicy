<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../../_config/sessionCheck.php'; //check admin loggedin or not

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
            $customerName = $patientName['name'];
            $patientAge = 'Age: ' . $patientName['age'];
            $patientPhno = 'M: ' . $patientName['phno'];
        }
    } else {
        $customerId = 'Cash Sales';
        $customerName = 'Cash Sales';
    }

    // print_r($_POST);

    // echo "<br>Added by : $addedBy<br>";

    // echo "<br>Invoice id : $invoiceNo";
    // echo "<br>bill date : $billDAte";
    // echo "Paticent id check : $customerId<br>";
    // echo "<br>customer name check : $customerName<br>";
    // echo "Paticen age check : $patientAge<br>";
    // echo "patient phone no check : $patientPhno<br>";
    // echo "reffered doctor : $doctorName<br>";

    // echo "payment mode : $paymentMode<br>";
    // echo "items count : $itemsCount<br>";
    // echo "total qantity count : $totalItemsQantity<br>";
    // echo "total gst amount : $totalGstAmount<br>";
    // echo "total mrp : $totalMRP<br>";
    // echo "total payble amount : $netPaybleAmount<br>";
    
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


    // echo "<br><br>================== data arrays ====================== <br>";
    // echo "<br>Item courrent stock id : ";
    // print_r($itemId);
    // echo "<br>Product Id : ";
    // print_r($prductId);
    // echo "<br>Product Name : ";
    // print_r($prodName);
    // echo "<br>Manufacturur Id : ";
    // print_r($manufId);
    // echo "<br>Pharmacy invoice tabel id : ";
    // print_r($pharmacyDataId);
    // echo "<br>Stock out details table id : ";
    // print_r($stockOutDataId);
    // echo "<br>Batch No : ";
    // print_r($batchNo);
    // echo "<br>Item Pack of : ";
    // print_r($weightage);
    // // echo "<br>Item weatage : "; print_r($itemWeightage);
    // // echo "<br>Item Unit : "; print_r($ItemUnit);
    // echo "<br>Item exp date : ";
    // print_r($expDate);
    // echo "<br>MRP : ";
    // print_r($mrp);
    // echo "<br>Disc Parcent : ";
    // print_r($discParcent);
    // echo "<br>Discout Amount : ";
    // print_r($discPrice);
    // echo "<br>GST Parcent : ";
    // print_r($gstparcent);
    // echo "<br>Gst amount per item : ";
    // print_r($gstAmountPerItem);
    // echo "<br>QANTITTY PER ITEM : ";
    // print_r($qty);
    // echo "<br>Qantity type : ";
    // print_r($qtyType);
    // echo "<br>Taxable amount per item : ";
    // print_r($taxable);
    // echo "<br>Net payble per item : ";
    // print_r($amount);
    // // echo "<br>Loose Stock : "; print_r($looseStock);
    // // echo "<br>Loose Price : "; print_r($looselyPrice);
    // echo "<br>PTR per Item : ";
    // print_r($ptrPerItem);
    // echo "<br>Margin amount per item : ";
    // print_r($marginPerItem);





    //========================== STOCK OUT AND SALES EDIT UPDATE AREA ==========================
    if (isset($_POST['update'])) {

        $discountAmount = floatval($totalMRP) - floatval($netPaybleAmount);

        $stockOutUpdate = $StockOut->updateLabBill($invoiceNo, $customerId, $doctorName, $itemsCount, $totalItemsQantity, $totalMRP, $discountAmount, $totalGstAmount, $netPaybleAmount, $paymentMode, $billDAte, $addedBy);
        // $stockOutUpdate = true;

        if ($stockOutUpdate == true) {


            // =========== DELETE DATA FROM PHARMACY AND STOCK OUT DETAILS TABLE SECTION =============
            $invoiceIdList = [];
            $pharmacyInvoiceData = $StockOut->stockOutDetailsById($invoiceNo);
            foreach ($pharmacyInvoiceData as $pharmacyInvoiceData) {
                array_push($invoiceIdList, $pharmacyInvoiceData['id']);
            }
            // echo "<br><br><br><br>Pharmacy id list : "; print_r($invoiceIdList);

            $pharmacyIdArraydiff = array_diff($invoiceIdList, $pharmacyDataId);
            $pharmacyIdArraydiff = array_values($pharmacyIdArraydiff);
            // echo "<br> Pharmacy id diff list : "; print_r($pharmacyIdArraydiff);



            $stockOutDetailsIdList = [];
            $stockOutDetails = $StockOut->stockOutDetailsDisplayById($invoiceNo);
            foreach ($stockOutDetails as $stockOutDetails) {
                array_push($stockOutDetailsIdList, $stockOutDetails['id']);
            }
            // echo "<br><br><br>Stock out details id list : "; print_r($stockOutDetailsIdList);

            $stockOutDetailsIdArrayDiff = array_diff($stockOutDetailsIdList, $stockOutDataId);
            $stockOutDetailsIdArrayDiff = array_values($stockOutDetailsIdArrayDiff);
            // echo "<br>Stock out details id diff list : "; print_r($stockOutDetailsIdArrayDiff);

            for ($i = 0; $i < count($pharmacyIdArraydiff) && $i < count($stockOutDetailsIdArrayDiff); $i++) {

                $selectFromStockOutDetails = $StockOut->stokOutDetailsDataOnTable('id', $stockOutDetailsIdArrayDiff[$i]);
                // echo "<br><br><br>";
                // print_r($selectFromStockOutDetails);

                foreach ($selectFromStockOutDetails as $stockOutData) {
                    $currenStockItemId = $stockOutData['item_id'];
                    if ($stockOutData['unit'] == 'tab' || $stockOutData['unit'] == 'cap') {
                        $itemQantity = $stockOutData['loosely_count'];
                    } else {
                        $itemQantity = $stockOutData['qty'];
                    }
                }
                // echo "<br>Item qantity : $itemQantity";
                // echo "<br>Item id : $currenStockItemId";

                $currenStockData = $CurrentStock->showCurrentStocById($currenStockItemId);
                foreach ($currenStockData as $currenStockData) {
                    if ($currenStockData['unit'] == 'tab' || $currenStockData['unit'] == 'cap') {
                        $currentQty = $currenStockData['loosely_count'];
                        $updatedLooseQty = intval($currentQty) + intval($itemQantity);
                        $updatedQty = intdiv($updatedLooseQty, intval($currenStockData['weightage']));
                    } else {
                        $currentQty = $currenStockData['qty'];
                        $updatedQty = intval($currentQty) + intval($itemQantity);
                        $updatedLooseQty = 0;
                    }
                }
                // echo "<br>Item updated qantity : $updatedQty";
                // echo "<br>Item updated loose qantity : $updatedLooseQty";
                // echo "<br>Item id : $currenStockItemId";

                //======== UPDATE CURRENT STOCK AND DELTE FROM PHAMACY INVOCIE AND STOCK OUT DATA ======
                $updateCurrenStock = $CurrentStock->updateCurrentStockById($currenStockItemId, $updatedQty, $updatedLooseQty);

                $delteFromPharmacy = $StockOut->delteItemFromInvoice($pharmacyIdArraydiff[$i]);
                $delteFromStockOutDetails = $StockOut->deleteFromStockOutDetailsOnId($stockOutDetailsIdArrayDiff[$i]);
            }

            // ================ UPDATE DATA ON PHARMACY AND STOCK OUT DETAILS TABLE ===========
            for ($i = 0; $i < count($pharmacyDataId) && $i < count($stockOutDataId); $i++) {
                // echo "<br><br><br>";
                if ($pharmacyDataId[$i] == '' && $stockOutDataId[$i] == '') {
                    $item_id = $itemId[$i];
                    $product_id = $prductId[$i];
                    $product_name = $prodName[$i];
                    $phamacy_id = '';
                    $stock_out_id = '';
                    $batch_number = $batchNo[$i];
                    $setOf = $weightage[$i];
                    $item_unit = preg_replace('/[0-9]/', '', $setOf);
                    $item_weatage = preg_replace('/[a-z]/', '', $setOf);
                    $exp_date = $expDate[$i];
                    $item_mrp = $mrp[$i];
                    $disc_parcent = $discParcent[$i];
                    $disc_price = $discPrice[$i];
                    $gst_parcent = $gstparcent[$i];
                    $gst_amount = $gstAmountPerItem[$i];
                    if ($item_unit == 'tab' || $item_unit == 'cap') {
                        $item_qty = intdiv(intval($qty[$i]), intval($item_weatage));
                        $item_loose_qty = $qty[$i];
                    } else {
                        $item_qty = $qty[$i];
                        $item_loose_qty = 0;
                    }
                    $qty_type = $qtyType[$i];
                    $taxable_amount = $taxable[$i];
                    $payble_amount = $amount[$i];
                    $item_ptr = $ptrPerItem[$i];
                    $margin_amount = $marginPerItem[$i];

                    // =========== ADD NEW DATA ON PHARMACY AND STOCK OUT DETAILS TABLE =============\

                    //====== add new item to pharmacy invocie =======
                    $addPharmacyInvoice = $StockOut->addPharmacyBillDetails($invoiceNo,	$item_id, $product_name,$batch_number, $setOf, $exp_date, $item_qty, $item_loose_qty, $item_mrp, $disc_parcent, $taxable_amount, $gst_parcent, $gst_amount, $payble_amount, $addedBy);

                    // ========= add new item to stock out details ==========
                    $addStockOutDetails = $StockOut->addStockOutDetails($invoiceNo, $item_id, $product_id, $batch_number, $exp_date, $item_weatage, $item_unit, $item_qty, $item_loose_qty, $item_mrp, $item_ptr, $disc_parcent, $gst_parcent, $margin_amount, $payble_amount, $addedBy);

                    //========== update current stock ==========
                    $currentStockData = $CurrentStock->showCurrentStocById($item_id);
                    foreach ($currentStockData as $currentData) {
                        if ($currentData['unit'] == 'tab' || $currentData['unit'] == 'cap') {
                            $currentLooseQty = $currentData['loosely_count'];
                            $updatedLooseQty = intval($currentLooseQty) - intval($item_loose_qty);
                            $updatedCurrentQty = intdiv(intval($updatedLooseQty), intval($currentData['weightage']));
                        } else {
                            $currentQty = $currentData['qty'];
                            $updatedLooseQty = 0;
                            $updatedCurrentQty = intval($currentQty) - intval($item_qty);
                        }
                    }
                    $updateCurrentStock = $CurrentStock->updateCurrentStockById($item_id, $updatedCurrentQty,$updatedLooseQty);
                }

                if ($pharmacyDataId[$i] != '' && $stockOutDataId[$i] != '') {
                    $item_id = $itemId[$i];
                    $product_id = $prductId[$i];
                    $product_name = $prodName[$i];
                    $phamacy_id = $pharmacyDataId[$i];
                    $stock_out_id = $stockOutDataId[$i];
                    $batch_number = $batchNo[$i];
                    $setOf = $weightage[$i];
                    $item_unit = preg_replace('/[0-9]/', '', $setOf);
                    $item_weatage = preg_replace('/[a-z]/', '', $setOf);
                    $exp_date = $expDate[$i];
                    $item_mrp = $mrp[$i];
                    $disc_parcent = $discParcent[$i];
                    $disc_price = $discPrice[$i];
                    $gst_parcent = $gstparcent[$i];
                    $gst_amount = $gstAmountPerItem[$i];
                    if ($item_unit == 'tab' || $item_unit == 'cap') {
                        $item_qty = intdiv(intval($qty[$i]), intval($item_weatage));
                        $item_loose_qty = $qty[$i];
                    } else {
                        $item_qty = $qty[$i];
                        $item_loose_qty = 0;
                    }
                    $qty_type = $qtyType[$i];
                    $taxable_amount = $taxable[$i];
                    $payble_amount = $amount[$i];
                    $item_ptr = $ptrPerItem[$i];
                    $margin_amount = $marginPerItem[$i];
                    // echo "<br>OLD ITEMS=====";

                    // ======================== UPDATE DATA ==========================
                    $table = 'id';
                    $selectStockOutDetailsData = $StockOut->stokOutDetailsDataOnTable($table, $stock_out_id);
                    foreach($selectStockOutDetailsData as $stockOutDataCheck){
                        if($stockOutDataCheck['unit'] == 'tab' || $stockOutDataCheck['unit'] == 'cap'){
                            $stockOutItemLooseCount = $stockOutDataCheck['loosely_count'];
                            $itemCountDiff = intval($stockOutItemLooseCount) - intval($item_loose_qty);
                        }else{
                            $stockOutItemQantity = $stockOutDataCheck['qty'];
                            $itemCountDiff = intval($stockOutItemQantity) - intval($item_qty);
                        }
                    }


                    // ====== update current stock ===========
                    $currentStockData = $CurrentStock->showCurrentStocById($item_id);
                    // echo "<br>"; print_r($currentStockData);
                    foreach ($currentStockData as $currentData) {
                        if ($currentData['unit'] == 'tab' || $currentData['unit'] == 'cap') {
                            $currentLooseQty = $currentData['loosely_count'];
                            $updatedLooseQty = intval($currentLooseQty) + (intval($itemCountDiff));
                            $updatedCurrentQty = intdiv(intval($updatedLooseQty), intval($currentData['weightage']));
                        } else {
                            $currentQty = $currentData['qty'];
                            $updatedLooseQty = 0;
                            $updatedCurrentQty = intval($currentQty) + (intval($itemCountDiff));
                        }
                    }

                    // ====== update current stock data ===========
                    $updateCurrentStock = $CurrentStock->updateCurrentStockById($item_id, $updatedCurrentQty,$updatedLooseQty);

                    // ====== update pharmacy data ===========
                    $updatePharmacyData = $StockOut->updatePharmacyDataById($phamacy_id, $item_qty, $item_loose_qty, $disc_parcent, $taxable_amount, $gst_amount, $payble_amount, $addedBy);

                    // ====== update stock out details =======
                    $updateStockOutData = $StockOut->updateStockOutDetaislById($stock_out_id, $item_qty, $item_loose_qty, $disc_parcent, $margin_amount, $payble_amount, $addedBy);
                }



                // echo "<br>Item id : $item_id";
                // echo "<br>product id : $product_id";
                // echo "<br>product Name : $product_name";
                // echo "<br>Pharmacy Id : $phamacy_id";
                // echo "<br>Stock out details id : $stock_out_id";
                // echo "<br>Batch number : $batch_number";
                // echo "<br>Item set of : $setOf";
                // echo "<br>Item unit : $item_unit";
                // echo "<br>Item weatage : $item_weatage";
                // echo "<br>Item exp date : $exp_date";
                // echo "<br>Item mrp : $item_mrp";
                // echo "<br>Item disc parcent: $disc_parcent";
                // echo "<br>Item disc price : $disc_price";
                // echo "<br>Item gst parcent : $gst_parcent";
                // echo "<br>Item gst amount : $gst_amount";
                // echo "<br>Item qty : $item_qty";
                // echo "<br>Item loose qty : $item_loose_qty";
                // echo "<br>Item qty types : $qty_type";
                // echo "<br>Item taxable amount : $taxable_amount";
                // echo "<br>Item pyable amount : $payble_amount";
                // echo "<br>Item ptr : $item_ptr";
                // echo "<br>Item margin : $margin_amount";
            }
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
        <div class="custom-body <?php if ($paymentMode != 'Credit') {
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
                                <?php echo $invoiceNo; ?></small></p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Payment: <?php echo $paymentMode; ?></small>
                        </p>
                        <p style="margin-top: -5px; margin-bottom: 0px;"><small>Date: <?php echo $billDAte; ?></small>
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
                            <?php echo $doctorName; ?></small></p>
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
                                    <small>' . $qty[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $mrp[$i] . '</small>
                                </div>
                                <div class="col-sm-1 text-end">
                                    <small>' . $discParcent[$i] . '</small>
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
                                    <small>₹<?php echo $totalGstAmount / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>SGST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $totalGstAmount / 2; ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Total GST:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo floatval($totalGstAmount); ?></small>
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
                                    <small><b>₹<?php echo floatval($totalMRP); ?></b></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>Net Price :</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small><b>₹<?php echo floatval($netPaybleAmount); ?></b></small>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;"><small>You Saved:</small></p>
                            </div>
                            <div class="col-4 text-end">
                                <p style="margin-top: -5px; margin-bottom: 0px;">
                                    <small>₹<?php echo $totalMRP - $netPaybleAmount; ?></small>
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